const router = require('express').Router();
const supabase = require('../config/supabase');
const { requireAuth, requireRole } = require('../middleware/auth');
const audit = require('../helpers/audit');

// GET /api/pacientes — listar / buscar (todos los roles)
router.get('/', requireAuth, async (req, res) => {
  const { q, page = 1, limit = 20 } = req.query;

  let query = supabase
    .from('pacientes')
    .select('*, laboratorios(nombre)', { count: 'exact' });

  if (q) {
    query = query.or(
      `nombres.ilike.%${q}%,apellidos.ilike.%${q}%,cedula.ilike.%${q}%,codigo.ilike.%${q}%`
    );
  }

  const offset = (Number(page) - 1) * Number(limit);
  query = query.range(offset, offset + Number(limit) - 1).order('created_at', { ascending: false });

  const { data, error, count } = await query;
  if (error) return res.status(400).json({ error: error.message });
  res.json({ data, total: count, page: Number(page), limit: Number(limit) });
});

// GET /api/pacientes/:id — detalle + historial de órdenes
router.get('/:id', requireAuth, async (req, res) => {
  const { data: paciente, error } = await supabase
    .from('pacientes')
    .select('*, laboratorios(nombre)')
    .eq('id', req.params.id)
    .single();

  if (error || !paciente) return res.status(404).json({ error: 'Paciente no encontrado' });

  const { data: ordenes } = await supabase
    .from('ordenes')
    .select('*, laboratorios(nombre), orden_analisis(*, analisis_tipos(nombre, campos))')
    .eq('paciente_id', req.params.id)
    .order('created_at', { ascending: false });

  res.json({ paciente, historial: ordenes || [] });
});

// POST /api/pacientes — registrar nuevo
router.post('/', requireAuth, requireRole('admin', 'recepcionista'), async (req, res) => {
  const { nombres, apellidos, cedula, fecha_nacimiento, sexo, telefono, email, direccion, medico_referidor } = req.body;

  if (!nombres?.trim() || !apellidos?.trim() || !cedula?.trim()) {
    return res.status(400).json({ error: 'Nombres, apellidos y cédula son obligatorios' });
  }

  // Generar código correlativo
  const { count } = await supabase.from('pacientes').select('*', { count: 'exact', head: true });
  const codigo = `#${String((count || 0) + 1).padStart(5, '0')}`;

  const { data, error } = await supabase
    .from('pacientes')
    .insert({
      codigo,
      nombres: nombres.trim(),
      apellidos: apellidos.trim(),
      cedula: cedula.trim(),
      fecha_nacimiento: fecha_nacimiento || null,
      sexo: sexo || null,
      telefono: telefono || null,
      email: email || null,
      direccion: direccion || null,
      medico_referidor: medico_referidor || null,
      laboratorio_origen_id: req.user.perfil?.laboratorio_id || null,
      created_by: req.user.id,
    })
    .select()
    .single();

  if (error) {
    if (error.code === '23505') return res.status(409).json({ error: 'Ya existe un paciente con esa cédula' });
    return res.status(400).json({ error: error.message });
  }

  await audit.log(req, { accion: 'crear_paciente', tabla: 'pacientes', registroId: data.id, datosNuevos: { codigo, nombres, apellidos, cedula } });
  res.status(201).json(data);
});

// PUT /api/pacientes/:id — editar
router.put('/:id', requireAuth, requireRole('admin', 'recepcionista'), async (req, res) => {
  const { nombres, apellidos, cedula, fecha_nacimiento, sexo, telefono, email, direccion, medico_referidor } = req.body;

  const { data: anterior } = await supabase.from('pacientes').select().eq('id', req.params.id).single();
  if (!anterior) return res.status(404).json({ error: 'Paciente no encontrado' });

  const { data, error } = await supabase
    .from('pacientes')
    .update({ nombres, apellidos, cedula, fecha_nacimiento, sexo, telefono, email, direccion, medico_referidor })
    .eq('id', req.params.id)
    .select()
    .single();

  if (error) return res.status(400).json({ error: error.message });

  await audit.log(req, {
    accion: 'editar_paciente', tabla: 'pacientes', registroId: data.id,
    datosAnteriores: { nombres: anterior.nombres, apellidos: anterior.apellidos, cedula: anterior.cedula },
    datosNuevos: { nombres, apellidos, cedula },
  });
  res.json(data);
});

module.exports = router;
