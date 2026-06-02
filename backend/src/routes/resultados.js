const router = require('express').Router();
const supabase = require('../config/supabase');
const { requireAuth, requireRole } = require('../middleware/auth');
const audit = require('../helpers/audit');

// PUT /api/resultados/:ordenAnalisisId — guardar o actualizar resultados
router.put('/:id', requireAuth, requireRole('admin', 'bioanalista'), async (req, res) => {
  const { resultados, observaciones } = req.body;

  if (!resultados || typeof resultados !== 'object') {
    return res.status(400).json({ error: 'Los resultados deben ser un objeto con los valores' });
  }

  const { data: anterior } = await supabase.from('orden_analisis').select().eq('id', req.params.id).single();
  if (!anterior) return res.status(404).json({ error: 'Registro no encontrado' });

  const { data, error } = await supabase
    .from('orden_analisis')
    .update({ resultados, observaciones: observaciones || null, estado: 'en_proceso' })
    .eq('id', req.params.id)
    .select('*, analisis_tipos(nombre)')
    .single();

  if (error) return res.status(400).json({ error: error.message });

  // Actualizar estado de la orden a en_proceso
  await supabase.from('ordenes').update({ estado: 'en_proceso' }).eq('id', anterior.orden_id).eq('estado', 'pendiente');

  await audit.log(req, {
    accion: 'ingresar_resultados', tabla: 'orden_analisis', registroId: data.id,
    datosAnteriores: anterior.resultados,
    datosNuevos: resultados,
  });
  res.json(data);
});

// POST /api/resultados/:ordenAnalisisId/validar — bioanalista valida el resultado
router.post('/:id/validar', requireAuth, requireRole('admin', 'bioanalista'), async (req, res) => {
  const { data: oa } = await supabase.from('orden_analisis').select().eq('id', req.params.id).single();
  if (!oa) return res.status(404).json({ error: 'Registro no encontrado' });
  if (!oa.resultados) return res.status(400).json({ error: 'No se puede validar sin resultados ingresados' });

  const { data, error } = await supabase
    .from('orden_analisis')
    .update({ validado_por: req.user.id, validado_at: new Date(), estado: 'completado' })
    .eq('id', req.params.id)
    .select()
    .single();

  if (error) return res.status(400).json({ error: error.message });

  // Si todos los análisis de la orden están completados → orden pasa a "validado"
  const { data: todos } = await supabase
    .from('orden_analisis')
    .select('estado')
    .eq('orden_id', data.orden_id);

  const todosCompletos = todos.every(a => a.estado === 'completado');
  if (todosCompletos) {
    await supabase.from('ordenes').update({ estado: 'validado' }).eq('id', data.orden_id);
  } else {
    await supabase.from('ordenes').update({ estado: 'por_validar' }).eq('id', data.orden_id);
  }

  await audit.log(req, { accion: 'validar_resultado', tabla: 'orden_analisis', registroId: data.id });
  res.json(data);
});

// DELETE /api/resultados/:ordenAnalisisId/validacion — revertir validación (solo admin)
router.delete('/:id/validacion', requireAuth, requireRole('admin'), async (req, res) => {
  const { data, error } = await supabase
    .from('orden_analisis')
    .update({ validado_por: null, validado_at: null, estado: 'en_proceso' })
    .eq('id', req.params.id)
    .select()
    .single();

  if (error) return res.status(400).json({ error: error.message });
  await supabase.from('ordenes').update({ estado: 'en_proceso' }).eq('id', data.orden_id);
  await audit.log(req, { accion: 'revertir_validacion', tabla: 'orden_analisis', registroId: data.id });
  res.json(data);
});

module.exports = router;
