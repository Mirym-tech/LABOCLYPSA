require('dotenv').config();
const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');

const app = express();

app.use(helmet());
app.use(cors({
  origin: process.env.FRONTEND_URL || '*',
  credentials: true,
}));
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
