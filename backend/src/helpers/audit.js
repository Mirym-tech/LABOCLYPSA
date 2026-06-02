const supabase = require('../config/supabase');

async function log(req, { accion, tabla, registroId, datosAnteriores, datosNuevos }) {
  try {
    await supabase.from('auditoria').insert({
      usuario_id:      req.user?.id || null,
      usuario_nombre:  req.user?.perfil?.nombre_completo || req.user?.email || 'Sistema',
      accion,
      tabla:           tabla || null,
      registro_id:     registroId ? String(registroId) : null,
      datos_anteriores: datosAnteriores || null,
      datos_nuevos:    datosNuevos || null,
      laboratorio_id:  req.user?.perfil?.laboratorio_id || null,
      ip_address:      req.ip,
    });
  } catch (e) {
    console.error('Error al registrar auditoría:', e.message);
  }
}

module.exports = { log };
