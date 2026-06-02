require('dotenv').config();

// Diagnóstico de arranque — confirma qué variables ve el proceso
console.log('=== VARIABLES DE ENTORNO ===');
console.log('NODE_ENV:', process.env.NODE_ENV);
console.log('PORT:', process.env.PORT);
console.log('SUPABASE_URL:', process.env.SUPABASE_URL ? '✓ SET' : '✗ MISSING');
console.log('SUPABASE_SERVICE_KEY:', process.env.SUPABASE_SERVICE_KEY ? '✓ SET' : '✗ MISSING');
console.log('============================');
const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');

const app = express();

app.use(helmet());

app.use(cors({ origin: true, credentials: true }));
app.use(express.json());
app.use(rateLimit({ windowMs: 15 * 60 * 1000, max: 300, standardHeaders: true }));

app.use('/api/auth',       require('./routes/auth'));
app.use('/api/pacientes',  require('./routes/pacientes'));
app.use('/api/ordenes',    require('./routes/ordenes'));
app.use('/api/resultados', require('./routes/resultados'));
app.use('/api/usuarios',   require('./routes/usuarios'));
app.use('/api/auditoria',  require('./routes/auditoria'));
app.use('/api/catalogo',   require('./routes/catalogo'));

app.get('/api/health', (_, res) => res.json({ ok: true, ts: new Date() }));

app.use((err, req, res, _next) => {
  console.error(err);
  res.status(500).json({ error: 'Error interno del servidor' });
});

// Solo inicia el servidor cuando se ejecuta directamente (desarrollo local)
if (require.main === module) {
  const PORT = process.env.PORT || 3000;
  app.listen(PORT, () => console.log(`LABOCLYPSA API corriendo en puerto ${PORT}`));
}

module.exports = app;
