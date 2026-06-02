const router = require('express').Router();
const supabase = require('../config/supabase');
const { requireAuth, requireRole } = require('../middleware/auth');
const audit = require('../helpers/audit');

// GET /api/ordenes — listar con filtros
router.get('/', requireAuth, async (req, res) => {
  const { estado, lab_id, paciente_id, page = 1, limit = 20 } = req.query;

  let query = supabase
    .from('ordenes')
    .select(
      '*, pacientes(nombres, apellidos, cedula, codigo), laboratorios(nombre), orden_analisis(id, estado, validado_at, analisis_tipos(nombre))',
      { count: 'exact' }
    );

  if (estado) query = query.eq('estado', estado);
  if (lab_id) query = query.eq('laboratorio_id', lab_id);
  if (paciente_id) query = query.eq('paciente_id', paciente_id);

  const offset = (Number(page) - 1) * Number(limit);
  query = query.range(offset, offset + Number(limit) - 1).order('created_at', { ascending: false });

  const { data, error, count } = await query;
  if (error) return res.status(400).json({ error: error.message });
  res.json({ data, total: count, page: Number(page), limit: Number(limit) });
});

// GET /api/ordenes/:id — detalle completo de una orden
router.get('/:id', requireAuth, async (req, res) => {
  const { data, error } = await supabase
    .from('ordenes')
    .select('*, pacientes(*), laboratorios(nombre), orden_analisis(*, analisis_tipos(nombre, campos))')
    .eq('id', req.params.id)
    .single();

  if (error || !data) return res.status(404).json({ error: 'Orden no encontrada' });
  res.json(data);
});

// POST /api/ordenes — crear nueva orden
router.post('/', requireAuth, requireRole('admin', 'recepcionista'), async (req, res) => {
  const { paciente_id, analisis_tipos_ids, medico_solicitante, prioridad, laboratorio_id } = req.body;

  if (!paciente_id) return res.status(400).json({ error: 'Paciente es requerido' });
  if (!Array.isArray(analisis_tipos_ids) || analisis_tipos_ids.length === 0) {
    return res.status(400).json({ error: 'Selecciona al menos un análisis' });
  }

  const { count } = await supabase.from('ordenes').select('*', { count: 'exact', head: true });
  const numero = `#ORD-${String((count || 0) + 1).padStart(4, '0')}`;
  const labId = laboratorio_id || req.user.perfil?.laboratorio_id;

  const { data: orden, error } = await supabase
    .from('ordenes')
    .insert({ numero, paciente_id, medico_solicitante: medico_solicitante || null, prioridad: prioridad || 'normal', laboratorio_id: labId, created_by: req.user.id })
    .select()
    .single();

  if (error) return res.status(400).json({ error: error.message });

  await supabase.from('orden_analisis').insert(
    analisis_tipos_ids.map(id => ({ orden_id: orden.id, analisis_tipo_id: id }))
  );

  await audit.log(req, {
    accion: 'crear_orden', tabla: 'ordenes', registroId: orden.id,
    datosNuevos: { numero, paciente_id, analisis: analisis_tipos_ids.length },
  });
  res.status(201).json(orden);
});

// PATCH /api/ordenes/:id/estado — cambiar estado
router.patch('/:id/estado', requireAuth, async (req, res) => {
  const { estado } = req.body;
  const validos = ['pendiente', 'en_proceso', 'por_validar', 'validado'];
  if (!validos.includes(estado)) return res.status(400).json({ error: 'Estado inválido' });

  const { data, error } = await supabase
    .from('ordenes')
    .update({ estado })
    .eq('id', req.params.id)
    .select()
    .single();

  if (error) return res.status(400).json({ error: error.message });
  await audit.log(req, { accion: 'cambiar_estado_orden', tabla: 'ordenes', registroId: data.id, datosNuevos: { estado } });
  res.json(data);
});

module.exports = router;
