-- Ejecutar una sola vez en la base de datos de Soporte.
-- Los eventos futuros guardarán el rol desde el que se hizo la acción.
ALTER TABLE administracion.soporte_reasignaciones
  ADD COLUMN IF NOT EXISTS rol_derivacion VARCHAR(20);

DO $$
BEGIN
  IF NOT EXISTS (
    SELECT 1
    FROM pg_constraint
    WHERE conname = 'ck_soporte_reasignaciones_rol_derivacion'
      AND conrelid = 'administracion.soporte_reasignaciones'::regclass
  ) THEN
    ALTER TABLE administracion.soporte_reasignaciones
      ADD CONSTRAINT ck_soporte_reasignaciones_rol_derivacion
      CHECK (rol_derivacion IS NULL OR rol_derivacion IN ('OPERADOR', 'TECNICO'));
  END IF;
END $$;

-- Los eventos anteriores quedan NULL porque la base no conserva qué rol eligió
-- el actor al momento de derivar/reasignar.
