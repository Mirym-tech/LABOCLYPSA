const router = require('express').Router();
const supabase = require('../config/supabase');
const { requireAuth } = require('../middleware/auth');

// GET /api/catalogo/analisis — tipos de análisis disponibles
router.get('/analisis', requireAuth, async (req, res) => {
  const { data, error } = await supabase
    .from('analisis_tipos')
    .select()
    .eq('activo', true)
    .order('categoria')
    .order('nombre');

  if (error) return res.status(400).json({ error: error.message });
  res.json(data);
});

// GET /api/catalogo/laboratorios — lista de laboratorios
router.get('/laboratorios', requireAuth, async (req, res) => {
  const { data, error } = await supabase
    .from('laboratorios')
    .select()
    .eq('activo', true)
    .order('nombre');

  if (error) return res.status(400).json({ error: error.message });
  res.json(data);
});

module.exports = router;
