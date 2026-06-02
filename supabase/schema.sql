-- ================================================================
-- LABOCLYPSA — Schema de base de datos para Supabase / PostgreSQL
-- Ejecutar en: Supabase Dashboard > SQL Editor
-- ================================================================

-- 1. LABORATORIOS
CREATE TABLE laboratorios (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  nombre TEXT NOT NULL,
  ubicacion TEXT,
  activo BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO laboratorios (nombre, ubicacion) VALUES
  ('Lab 1 — Norte', 'Sede Norte'),
  ('Lab 2 — Sur',   'Sede Sur');

-- 2. PERFILES DE USUARIO (extiende auth.users de Supabase)
CREATE TABLE perfiles (
  id UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
  nombre_completo TEXT NOT NULL,
  rol TEXT NOT NULL CHECK (rol IN ('admin', 'bioanalista', 'recepcionista')),
  laboratorio_id UUID REFERENCES laboratorios(id),
  activo BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 3. PACIENTES
CREATE TABLE pacientes (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  codigo TEXT UNIQUE NOT NULL,
  nombres TEXT NOT NULL,
  apellidos TEXT NOT NULL,
  cedula TEXT UNIQUE NOT NULL,
  fecha_nacimiento DATE,
  sexo TEXT CHECK (sexo IN ('M', 'F')),
  telefono TEXT,
  email TEXT,
  direccion TEXT,
  medico_referidor TEXT,
  laboratorio_origen_id UUID REFERENCES laboratorios(id),
  created_by UUID REFERENCES auth.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- 4. TIPOS DE ANÁLISIS (catálogo con parámetros y rangos)
CREATE TABLE analisis_tipos (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  nombre TEXT NOT NULL,
  categoria TEXT,
  campos JSONB,   -- [{ "nombre": "Hemoglobina", "unidad": "g/dL", "rango_min": 12, "rango_max": 16 }]
  activo BOOLEAN DEFAULT true
);

INSERT INTO analisis_tipos (nombre, categoria, campos) VALUES
('Hemograma completo', 'Hematología', '[
  {"nombre":"Hemoglobina","unidad":"g/dL","rango_min":12.0,"rango_max":16.0},
  {"nombre":"Hematocrito","unidad":"%","rango_min":36.0,"rango_max":46.0},
  {"nombre":"Leucocitos (WBC)","unidad":"x10³/µL","rango_min":4.5,"rango_max":11.0},
  {"nombre":"Plaquetas","unidad":"x10³/µL","rango_min":150,"rango_max":400},
  {"nombre":"Eritrocitos (RBC)","unidad":"x10⁶/µL","rango_min":4.0,"rango_max":5.2},
  {"nombre":"Neutrófilos","unidad":"%","rango_min":50,"rango_max":70},
  {"nombre":"Linfocitos","unidad":"%","rango_min":20,"rango_max":40}
]'),
('Glucosa en ayunas', 'Química', '[
  {"nombre":"Glucosa","unidad":"mg/dL","rango_min":70,"rango_max":100}
]'),
('Perfil lipídico', 'Química', '[
  {"nombre":"Colesterol total","unidad":"mg/dL","rango_min":0,"rango_max":200},
  {"nombre":"Triglicéridos","unidad":"mg/dL","rango_min":0,"rango_max":150},
  {"nombre":"HDL","unidad":"mg/dL","rango_min":40,"rango_max":60},
  {"nombre":"LDL","unidad":"mg/dL","rango_min":0,"rango_max":130}
]'),
('Orina completa', 'Urología', '[
  {"nombre":"Color","unidad":"","rango_min":null,"rango_max":null},
  {"nombre":"Aspecto","unidad":"","rango_min":null,"rango_max":null},
  {"nombre":"pH","unidad":"","rango_min":4.5,"rango_max":8.0},
  {"nombre":"Proteínas","unidad":"mg/dL","rango_min":null,"rango_max":null},
  {"nombre":"Glucosa en orina","unidad":"","rango_min":null,"rango_max":null},
  {"nombre":"Leucocitos en orina","unidad":"por campo","rango_min":0,"rango_max":5}
]'),
('Creatinina', 'Química', '[
  {"nombre":"Creatinina","unidad":"mg/dL","rango_min":0.6,"rango_max":1.2}
]'),
('Urea / BUN', 'Química', '[
  {"nombre":"Urea","unidad":"mg/dL","rango_min":10,"rango_max":50},
  {"nombre":"BUN","unidad":"mg/dL","rango_min":7,"rango_max":23}
]'),
('TSH / T4 (tiroides)', 'Endocrinología', '[
  {"nombre":"TSH","unidad":"µUI/mL","rango_min":0.4,"rango_max":4.0},
  {"nombre":"T4 libre","unidad":"ng/dL","rango_min":0.8,"rango_max":1.8}
]'),
('Transaminasas (TGO/TGP)', 'Química', '[
  {"nombre":"TGO (AST)","unidad":"U/L","rango_min":10,"rango_max":40},
  {"nombre":"TGP (ALT)","unidad":"U/L","rango_min":7,"rango_max":56}
]'),
('PCR (Proteína C Reactiva)', 'Inmunología', '[
  {"nombre":"PCR","unidad":"mg/L","rango_min":0,"rango_max":5}
]'),
('Ácido úrico', 'Química', '[
  {"nombre":"Ácido úrico","unidad":"mg/dL","rango_min":3.5,"rango_max":7.2}
]'),
('HIV (ELISA)', 'Serología', '[
  {"nombre":"HIV 1/2","unidad":"","rango_min":null,"rango_max":null}
]'),
('VDRL / RPR', 'Serología', '[
  {"nombre":"VDRL","unidad":"","rango_min":null,"rango_max":null}
]');

-- 5. ÓRDENES DE ANÁLISIS
CREATE TABLE ordenes (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  numero TEXT UNIQUE NOT NULL,
  paciente_id UUID REFERENCES pacientes(id) NOT NULL,
  laboratorio_id UUID REFERENCES laboratorios(id) NOT NULL,
  medico_solicitante TEXT,
  estado TEXT DEFAULT 'pendiente' CHECK (estado IN ('pendiente','en_proceso','por_validar','validado')),
  prioridad TEXT DEFAULT 'normal' CHECK (prioridad IN ('normal','urgente')),
  created_by UUID REFERENCES auth.users(id),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- 6. ORDEN — ANÁLISIS (relación muchos a muchos + resultados)
CREATE TABLE orden_analisis (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  orden_id UUID REFERENCES ordenes(id) ON DELETE CASCADE,
  analisis_tipo_id UUID REFERENCES analisis_tipos(id),
  estado TEXT DEFAULT 'pendiente' CHECK (estado IN ('pendiente','en_proceso','completado')),
  resultados JSONB,         -- { "Hemoglobina": "14.5", "Hematocrito": "42.0", ... }
  observaciones TEXT,
  validado_por UUID REFERENCES auth.users(id),
  validado_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- 7. AUDITORÍA
CREATE TABLE auditoria (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  usuario_id UUID REFERENCES auth.users(id),
  usuario_nombre TEXT,
  accion TEXT NOT NULL,
  tabla TEXT,
  registro_id TEXT,
  datos_anteriores JSONB,
  datos_nuevos JSONB,
  laboratorio_id UUID REFERENCES laboratorios(id),
  ip_address TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- ================================================================
-- ROW LEVEL SECURITY (RLS) — todos ven todos los registros
-- porque los dos labs comparten la misma BD
-- El control de acceso se hace a nivel de API / backend
-- ================================================================
ALTER TABLE laboratorios   ENABLE ROW LEVEL SECURITY;
ALTER TABLE perfiles       ENABLE ROW LEVEL SECURITY;
ALTER TABLE pacientes      ENABLE ROW LEVEL SECURITY;
ALTER TABLE analisis_tipos ENABLE ROW LEVEL SECURITY;
ALTER TABLE ordenes        ENABLE ROW LEVEL SECURITY;
ALTER TABLE orden_analisis ENABLE ROW LEVEL SECURITY;
ALTER TABLE auditoria      ENABLE ROW LEVEL SECURITY;

-- Política: solo usuarios autenticados pueden leer/escribir
CREATE POLICY "autenticados_todo" ON laboratorios   FOR ALL USING (auth.role() = 'authenticated');
CREATE POLICY "autenticados_todo" ON perfiles       FOR ALL USING (auth.role() = 'authenticated');
CREATE POLICY "autenticados_todo" ON pacientes      FOR ALL USING (auth.role() = 'authenticated');
CREATE POLICY "autenticados_todo" ON analisis_tipos FOR ALL USING (auth.role() = 'authenticated');
CREATE POLICY "autenticados_todo" ON ordenes        FOR ALL USING (auth.role() = 'authenticated');
CREATE POLICY "autenticados_todo" ON orden_analisis FOR ALL USING (auth.role() = 'authenticated');
CREATE POLICY "autenticados_todo" ON auditoria      FOR ALL USING (auth.role() = 'authenticated');

-- ================================================================
-- FUNCIÓN: actualizar updated_at automáticamente
-- ================================================================
CREATE OR REPLACE FUNCTION update_updated_at()
RETURNS TRIGGER AS $$
BEGIN NEW.updated_at = NOW(); RETURN NEW; END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_pacientes_updated   BEFORE UPDATE ON pacientes   FOR EACH ROW EXECUTE FUNCTION update_updated_at();
CREATE TRIGGER trg_ordenes_updated     BEFORE UPDATE ON ordenes     FOR EACH ROW EXECUTE FUNCTION update_updated_at();
CREATE TRIGGER trg_oa_updated          BEFORE UPDATE ON orden_analisis FOR EACH ROW EXECUTE FUNCTION update_updated_at();
