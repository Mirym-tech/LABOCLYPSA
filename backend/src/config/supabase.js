const { createClient } = require('@supabase/supabase-js');

const url = process.env.SUPABASE_URL || '';
const key = process.env.SUPABASE_SERVICE_KEY || '';

if (!url || !key) {
  console.error('⚠️  SUPABASE_URL o SUPABASE_SERVICE_KEY no están configuradas');
}

// Usa placeholders si las vars faltan para que el proceso no se caiga en el arrange
const supabase = createClient(
  url || 'https://placeholder.supabase.co',
  key || 'placeholder-key',
  { auth: { autoRefreshToken: false, persistSession: false } }
);

module.exports = supabase;
