let app;
try {
  app = require('../backend/src/app');
} catch (err) {
  console.error('Error al cargar el servidor:', err);
  app = (req, res) => res.status(500).json({
    error: 'Error de inicializacion',
    details: err.message,
    stack: err.stack,
  });
}
module.exports = app;
