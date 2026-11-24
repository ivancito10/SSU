ALTER TABLE administracion.soporte_solicitudes
    ADD COLUMN IF NOT EXISTS es_manual BOOLEAN NOT NULL DEFAULT FALSE,
    ADD COLUMN IF NOT EXISTS solicitante_manual VARCHAR(180);
