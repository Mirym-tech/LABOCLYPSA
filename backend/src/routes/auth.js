const router = require('express').Router();
const supabase = require('../config/supabase');
const { requireAuth } = require('../middleware/auth');
const audit = require('../helpers/audit');

// POST /api/auth/login
router.post('/login', async (req, res) => {
  const { email, password } = req.body;
  if (!email || !password) return res.status(400).json({ error: 'Email y contraseña son requeridos' });

  const { data, error } = await supabase.auth.signInWithPassword({ email, password });
  if (error) return res.status(401).json({ error: 'Credenciales incorrectas' });

  const { data: perfil } = await supabase
    .from('perfiles')
    .select('*, laboratorios(*)')
    .eq('id', data.user.id)
    .single();

  if (!perfil) return res.status(403).json({ error: 'Usuario sin perfil asignado. Contacte al administrador.' });
  if (!perfil.activo) return res.status(403).json({ error: 'Usuario desactivado' });

  // Registrar login en auditoría
  await supabase.from('auditoria').insert({
    usuario_id: data.user.id,
    usuario_nombre: perfil.nombre_completo,
    accion: 'login',
    laboratorio_id: perfil.laboratorio_id,
    ip_address: req.ip,
  });

  res.json({
    token: data.session.access_token,
    user: {
      id: data.user.id,
      email: data.user.email,
      nombre_completo: perfil.nombre_completo,
      rol: perfil.rol,
      laboratorio: perfil.laboratorios,
      laboratorio_id: perfil.laboratorio_id,
    },
  });
});

// GET /api/auth/me — verificar token y obtener datos del usuario
router.get('/me', requireAuth, (req, res) => {
  const { perfil } = req.user;
  res.json({
    id: req.user.id,
    email: req.user.email,
    nombre_completo: perfil.nombre_completo,
    rol: perfil.rol,
    laboratorio: perfil.laboratorios,
    laboratorio_id: perfil.laboratorio_id,
  });
});

// POST /api/auth/logout
router.post('/logout', requireAuth, async (req, res) => {
  await audit.log(req, { accion: 'logout' });
  res.json({ ok: true });
});

module.exports = router;
