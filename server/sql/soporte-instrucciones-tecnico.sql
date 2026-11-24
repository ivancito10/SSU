-- Guarda por separado las indicaciones internas que el operador entrega al técnico.
-- No se mezcla con observacion, que el técnico utiliza como diagnóstico al finalizar.
ALTER TABLE administracion.soporte_asignaciones
  ADD COLUMN IF NOT EXISTS instrucciones_tecnico TEXT;
