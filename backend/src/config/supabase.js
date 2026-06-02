const { createClient } = require('@supabase/supabase-js');

if (!process.env.SUPABASE_URL || !process.env.SUPABASE_SERVICE_KEY) {
  console.error('⚠️  FALTAN VARIABLES: SUPABASE_URL o SUPABASE_SERVICE_KEY no están configuradas');
}

// Service role key — acceso completo, omite RLS.
// Solo se usa en el servidor, nunca en el cliente.
const supabase = createClient(
  process.env.SUPABASE_URL,
  process.env.SUPABASE_SERVICE_KEY,
  { auth: { autoRefreshToken: false, persistSession: false } }
);

module.exports = supabase;
