const router = require('express').Router();
const supabase = require('../config/supabase');
const { requireAuth, requireRole } = require('../middleware/auth');
const audit = require('../helpers/audit');

// GET /api/usuarios — solo admin
router.get('/', requireAuth, requireRole('admin'), async (req, res) => {
  const { data, error } = await supabase
    .from('perfiles')
    .select('*, laboratorios(nombre)')
    .order('nombre_completo');

  if (error) return res.status(400).json({ error: error.message });
  res.json(data);
});

// POST /api/usuarios — crear usuario (admin crea el auth + perfil)
router.post('/', requireAuth, requireRole('admin'), async (req, res) => {
  const { email, password, nombre_completo, rol, laboratorio_id } = req.body;

  if (!email || !password || !nombre_completo || !rol) {
    return res.status(400).json({ error: 'Email, contraseña, nombre y rol son requeridos' });
  }
  if (password.length < 8) {
    return res.status(400).json({ error: 'La contraseña debe tener al menos 8 caracteres' });
  }

  const { data: authData, error: authError } = await supabase.auth.admin.createUser({
    email,
    password,
    email_confirm: true,
  });
  if (authError) return res.status(400).json({ error: authError.message });

  const { data, error } = await supabase
    .from('perfiles')
    .insert({ id: authData.user.id, nombre_completo, rol, laboratorio_id: laboratorio_id || null })
    .select('*, laboratorios(nombre)')
    .single();

  if (error) {
    await supabase.auth.admin.deleteUser(authData.user.id);
    return res.status(400).json({ error: error.message });
  }

  await audit.log(req, { accion: 'crear_usuario', tabla: 'perfiles', registroId: data.id, datosNuevos: { email, rol, nombre_completo } });
  res.status(201).json(data);
});

// PUT /api/usuarios/:id — editar perfil
router.put('/:id', requireAuth, requireRole('admin'), async (req, res) => {
  const { nombre_completo, rol, laboratorio_id, activo } = req.body;

  const { data, error } = await supabase
    .from('perfiles')
    .update({ nombre_completo, rol, laboratorio_id, activo })
    .eq('id', req.params.id)
    .select('*, laboratorios(nombre)')
    .single();

  if (error) return res.status(400).json({ error: error.message });
  await audit.log(req, { accion: 'editar_usuario', tabla: 'perfiles', registroId: data.id, datosNuevos: { rol, activo } });
  res.json(data);
});

// PUT /api/usuarios/:id/password — cambiar contraseña
router.put('/:id/password', requireAuth, requireRole('admin'), async (req, res) => {
  const { password } = req.body;
  if (!password || password.length < 8) return res.status(400).json({ error: 'La contraseña debe tener al menos 8 caracteres' });

  const { error } = await supabase.auth.admin.updateUserById(req.params.id, { password });
  if (error) return res.status(400).json({ error: error.message });

  await audit.log(req, { accion: 'cambiar_password', tabla: 'perfiles', registroId: req.params.id });
  res.json({ ok: true });
});

module.exports = router;
