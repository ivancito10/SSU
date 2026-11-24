CREATE TABLE IF NOT EXISTS administracion.soporte_notificaciones (
    id BIGSERIAL PRIMARY KEY,
    id_usuario BIGINT NOT NULL,
    titulo VARCHAR(180) NOT NULL,
    mensaje TEXT NOT NULL,
    datos JSONB NOT NULL DEFAULT '{}'::jsonb,
    leida BOOLEAN NOT NULL DEFAULT FALSE,
    creado_en TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    leida_en TIMESTAMPTZ
);

CREATE INDEX IF NOT EXISTS idx_soporte_notificaciones_usuario_creado
    ON administracion.soporte_notificaciones (id_usuario, creado_en DESC);

CREATE INDEX IF NOT EXISTS idx_soporte_notificaciones_usuario_no_leida
    ON administracion.soporte_notificaciones (id_usuario, leida)
    WHERE leida = FALSE;
