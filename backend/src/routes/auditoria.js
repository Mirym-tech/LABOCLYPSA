const router = require('express').Router();
const supabase = require('../config/supabase');
const { requireAuth, requireRole } = require('../middleware/auth');

// GET /api/auditoria — solo admin
router.get('/', requireAuth, requireRole('admin'), async (req, res) => {
  const { fecha, accion, usuario_id, page = 1, limit = 50 } = req.query;

  let query = supabase
    .from('auditoria')
    .select('*, laboratorios(nombre)', { count: 'exact' })
    .order('created_at', { ascending: false });

  if (fecha) {
    query = query
      .gte('created_at', `${fecha}T00:00:00.000Z`)
      .lte('created_at', `${fecha}T23:59:59.999Z`);
  }
  if (accion)     query = query.eq('accion', accion);
  if (usuario_id) query = query.eq('usuario_id', usuario_id);

  const offset = (Number(page) - 1) * Number(limit);
  query = query.range(offset, offset + Number(limit) - 1);

  const { data, error, count } = await query;
  if (error) return res.status(400).json({ error: error.message });
  res.json({ data, total: count, page: Number(page), limit: Number(limit) });
});

module.exports = router;
