// En desarrollo carga el .env local; en producción Railway inyecta las vars directamente
if (process.env.NODE_ENV !== 'production') {
  require('dotenv').config({ path: require('path').join(__dirname, '../../.env') });
}

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

const corsOptions = {
  origin: (origin, callback) => {
    // En desarrollo permite cualquier origen (incluye file://, Live Server, etc.)
    if (process.env.NODE_ENV !== 'production') return callback(null, true);
    // En producción solo permite el frontend registrado
    if (!origin || origin === process.env.FRONTEND_URL) return callback(null, true);
    callback(new Error('CORS: origen no permitido'));
  },
  credentials: true,
};
app.use(cors(corsOptions));
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

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`LABOCLYPSA API corriendo en puerto ${PORT}`));
