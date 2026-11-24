-- Agrega un código legible por día, por ejemplo SOP-20261123-001.
-- El id serial de soporte_solicitudes se conserva como identificador interno.
ALTER TABLE administracion.soporte_solicitudes
  ADD COLUMN IF NOT EXISTS codigo_ticket VARCHAR(24);

CREATE TABLE IF NOT EXISTS administracion.soporte_contadores_diarios (
  fecha DATE PRIMARY KEY,
  ultimo_numero INTEGER NOT NULL CHECK (ultimo_numero > 0)
);

-- Asigna números consecutivos a los tickets existentes, agrupados por su día de creación.
WITH numerados AS (
  SELECT id, created_at::date AS fecha_ticket,
         ROW_NUMBER() OVER (PARTITION BY created_at::date ORDER BY created_at, id) AS numero
  FROM administracion.soporte_solicitudes
  WHERE codigo_ticket IS NULL
)
UPDATE administracion.soporte_solicitudes s
SET codigo_ticket = 'SOP-' || TO_CHAR(n.fecha_ticket, 'YYYYMMDD') || '-' || LPAD(n.numero::text, 3, '0')
FROM numerados n
WHERE s.id = n.id;

-- Inicializa/avanza los contadores al mayor consecutivo ya asignado para cada día.
INSERT INTO administracion.soporte_contadores_diarios (fecha, ultimo_numero)
SELECT created_at::date,
       MAX(SUBSTRING(codigo_ticket FROM '([0-9]+)$')::integer)
FROM administracion.soporte_solicitudes
WHERE codigo_ticket IS NOT NULL
GROUP BY created_at::date
ON CONFLICT (fecha) DO UPDATE
SET ultimo_numero = GREATEST(
  administracion.soporte_contadores_diarios.ultimo_numero,
  EXCLUDED.ultimo_numero
);

CREATE UNIQUE INDEX IF NOT EXISTS uq_soporte_solicitudes_codigo_ticket
  ON administracion.soporte_solicitudes (codigo_ticket);

CREATE OR REPLACE FUNCTION administracion.generar_codigo_ticket_diario()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
DECLARE
  fecha_ticket DATE;
  numero_ticket INTEGER;
BEGIN
  IF NEW.codigo_ticket IS NULL OR BTRIM(NEW.codigo_ticket) = '' THEN
    fecha_ticket := COALESCE(NEW.created_at::date, CURRENT_DATE);

    INSERT INTO administracion.soporte_contadores_diarios (fecha, ultimo_numero)
    VALUES (fecha_ticket, 1)
    ON CONFLICT (fecha) DO UPDATE
      SET ultimo_numero = administracion.soporte_contadores_diarios.ultimo_numero + 1
    RETURNING ultimo_numero INTO numero_ticket;

    NEW.codigo_ticket := 'SOP-' || TO_CHAR(fecha_ticket, 'YYYYMMDD') || '-' || LPAD(numero_ticket::text, 3, '0');
  END IF;

  RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS trg_generar_codigo_ticket_diario
  ON administracion.soporte_solicitudes;

CREATE TRIGGER trg_generar_codigo_ticket_diario
BEFORE INSERT ON administracion.soporte_solicitudes
FOR EACH ROW
EXECUTE FUNCTION administracion.generar_codigo_ticket_diario();

ALTER TABLE administracion.soporte_solicitudes
  ALTER COLUMN codigo_ticket SET NOT NULL;
