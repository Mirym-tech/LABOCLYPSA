const supabase = require('../config/supabase');

async function requireAuth(req, res, next) {
  const token = req.headers.authorization?.replace('Bearer ', '').trim();
  if (!token) return res.status(401).json({ error: 'No autorizado — token requerido' });

  const { data: { user }, error } = await supabase.auth.getUser(token);
  if (error || !user) return res.status(401).json({ error: 'Token inválido o expirado' });

  const { data: perfil } = await supabase
    .from('perfiles')
    .select('*, laboratorios(*)')
    .eq('id', user.id)
    .single();

  if (!perfil?.activo) return res.status(403).json({ error: 'Usuario desactivado' });

  req.user = { ...user, perfil };
  next();
}

function requireRole(...roles) {
  return (req, res, next) => {
    if (!roles.includes(req.user?.perfil?.rol)) {
      return res.status(403).json({ error: `Se requiere rol: ${roles.join(' o ')}` });
    }
    next();
  };
}

module.exports = { requireAuth, requireRole };
