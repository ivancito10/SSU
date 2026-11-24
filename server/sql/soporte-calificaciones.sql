-- Calificación única del técnico por solicitud finalizada.
-- Ejecutar una sola vez en la base PostgreSQL de SIGHOV.
CREATE TABLE IF NOT EXISTS administracion.soporte_calificaciones (
  id BIGSERIAL PRIMARY KEY,
  id_solicitud INTEGER NOT NULL UNIQUE
    REFERENCES administracion.soporte_solicitudes(id) ON DELETE CASCADE,
  id_asignacion INTEGER NOT NULL
    REFERENCES administracion.soporte_asignaciones(id),
  id_usuario BIGINT NOT NULL,
  id_tecnico BIGINT NOT NULL,
  calificacion VARCHAR(20) NOT NULL
    CHECK (calificacion IN ('MUY_BIEN', 'BIEN', 'MAL')),
  created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_soporte_calificaciones_tecnico
  ON administracion.soporte_calificaciones (id_tecnico, created_at DESC);
