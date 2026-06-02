// Node.js 20 no tiene WebSocket nativo — Supabase lo necesita aunque no usemos realtime
if (typeof globalThis.WebSocket === 'undefined') {
  globalThis.WebSocket = require('ws');
}

const { createClient } = require('@supabase/supabase-js');

const url = (process.env.SUPABASE_URL || '').trim();
const key = (process.env.SUPABASE_SERVICE_KEY || '').trim();

if (!url || !key) {
  throw new Error(`Variables de Supabase faltantes — SUPABASE_URL o SUPABASE_SERVICE_KEY no configuradas`);
}

const supabase = createClient(url, key, {
  auth: { autoRefreshToken: false, persistSession: false },
});

module.exports = supabase;
