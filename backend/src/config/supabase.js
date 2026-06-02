const { createClient } = require('@supabase/supabase-js');

const url = (process.env.SUPABASE_URL || '').trim();
const key = (process.env.SUPABASE_SERVICE_KEY || '').trim();

console.log('Supabase URL recibida:', url ? `"${url.substring(0, 30)}..."` : 'VACIA');
console.log('Supabase KEY recibida:', key ? 'SET ✓' : 'VACIA ✗');

if (!url || !key) {
  throw new Error(`Variables de Supabase faltantes. URL="${url}" KEY="${key ? 'SET' : 'EMPTY'}"`);
}

const supabase = createClient(url, key, {
  auth: { autoRefreshToken: false, persistSession: false },
});

module.exports = supabase;
