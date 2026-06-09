# Prompt: Sistema de Laboratorio Clínico — Aplicación Web Completa

Quiero que desarrolles una **aplicación web completa** para un laboratorio clínico. Te comparto capturas del sistema actual que usan para que repliques exactamente su funcionalidad, pero en versión web moderna con base de datos en la nube.

---

## Contexto del negocio

Laboratorio clínico que procesa muestras y entrega resultados impresos a pacientes. El sistema actual es una aplicación de escritorio Windows que guarda datos localmente — si la PC falla, se pierde todo. Tienen **dos sucursales físicas independientes** que necesitan compartir pacientes y resultados en tiempo real.

---

## Problema a resolver

1. Datos guardados localmente → pérdida total si la PC falla
2. Dos laboratorios que no comparten información entre sí

**Solución:** aplicación web con base de datos centralizada en la nube, accesible desde cualquier navegador, donde ambas sucursales trabajen sobre los mismos datos.

---

## Formulario de captura de análisis (pantalla principal)

Campos del formulario de entrada de paciente:
- Número de orden (autogenerado)
- Tipo de paciente: Ambulatorio / Internado
- Fecha de entrada
- Número de factura
- Nombre del paciente
- Dirección
- Teléfono
- Código de paciente
- Cuenta #
- Médico tratante
- Seguro médico
- Edad
- Nacionalidad: Dominicana / Haitiana / Otras
- Sexo: Femenino / Masculino
- Embarazada (checkbox)
- Número de entrada (# Entrada, autogenerado)
- Lista de análisis seleccionados (código + descripción)

**Panel de selección de análisis** (lado derecho del formulario):
- HEMATOLOGIA
- HEMATO/COAGULACION
- BACTERIOLOGIA
- ANTIGENOS
- ANALISIS DE COLERA
- UROANALISIS
- DIGESTION EN HECES
- ANALISIS VARIOS

---

## Módulos de ingreso de resultados — detalle exacto por tipo

### 1. HEMATOLOGÍA I — Hemograma

**Pestaña General** (parámetros con resultado, unidad y rango de referencia):

| Parámetro | Unidad | Referencia |
|-----------|--------|------------|
| WBC | 10^3/UL | 4.0 – 10.0 |
| Lymph# | 10^3/UL | 0.60 – 4.10 |
| Mid# | 10^3/UL | 0.10 – 0.90 |
| Gran# | 10^3/UL | 2.00 – 7.80 |
| Lymph% | % | 20.0 – 50.0 |
| Mid% | % | 3.0 – 10.0 |
| Gran% | % | 40.0 – 70.0 |
| RBC | 10^3/UL | 3.80 – 5.80 |
| HGB | g/dL | 11.0 – 16.5 |
| HCT | % | 35.0 – 50.0 |
| MCV | fL | 80.0 – 100.0 |
| MCH | pg | 26.5 – 33.5 |
| MCHC | g/dL | 32.2 – 36.0 |
| RDW-CV | % | 10.0 – 15.0 |
| RDW-SD | fL | 35.0 – 56.0 |
| PLT | 10^3/UL | 150 – 450 |
| MPV | fL | 7.0 – 11.0 |
| PDW | % | 10.0 – 18.0 |
| PCT | % | 0.100 – 0.500 |
| P-LCR | % | 13.0 – 43.0 |
| VITAMINA B12 | 10^3/UL | — |
| ACIDO FOLICO | 10^3/UL | — |
| HIERRO | 10^3/UL | — |

- Campo: OBSERVACION
- Botones: Imprimir / Grabar / Cancelar

**Pestaña Hemograma** (formulario extendido):

Sección HEMOGRAMA COMPLETO:
- HEMOGLOBINA (gm/dl, %)
- HEMATOCRITO (%)
- ERITROCITOS (/mn)
- LEUCOCITOS (/mn)

Sección INDICES HEMATICOS con VALORES DE REFERENCIA:
- VCM — ref: 81–104
- HCM — ref: 27–31
- CHCM (%) — ref: 32–36

Sección % RECUENTO DIFERENCIAL:
- MIELOBLASTOS, PROMIELOCITOS, MIELOCITOS, METAMIELOCITOS, BANDAS, SEGMENTOS, LINFOCITOS, MONOCITOS, EOSINOFILOS, BASOFILOS

Sección OBSERVACIONES (campos de texto):
- HIPOCROMIA, POIQUILOCITOSIS, AMISOCITOSIS, CLS. EN DIANA, MACROCITOSIS, CLS. CRENADAS, MICROCITOSIS, MACROPLAQUET.

Sección DETERMINACION con RESULTADOS y VALOR REFERENCIA:
- ERITROSEDIMENTACION (mm/h)
- CONTEO EOSINOFILOS
- CONTEO PLAQUETAS (/mm3, ref: 150–450 Ml)
- CONTEO RETICULOCITOS (%)
- RETICULOCITOS CORREGIDOS
- INV. FALCEMIA
- INV. CELULAS L.E.
- INV. HEMATOZOARIOS

- Campo: OBSERVACION
- Botones: Imprimir / Grabar / Cancelar

---

### 2. BACTERIOLOGÍA (3 pestañas: General, Cont., Cont. 3)

**Pestaña General:**
- Bioanalista (dropdown)
- Estudio (dropdown): Descripción Cultivo, Cultivo de Absceso, Cultivo de BK, Cultivo de Esputo, Cultivo de Fluidos, Cultivo de Garganta, Cultivo de Heces Fecales, Cultivo de Heridas, Cultivo de LCR, Cultivo de Oído, Cultivo de Orina, Cultivo de Secreción Vaginal, Cultivo de Uretra
- Muestra de (texto libre)
- Organismo(s) (dropdown): Ningún Crecimiento De Microorganismo A Las 72 Horas De Incubación, Estafilococus Aereus, Pseudomonas Aeroginosas, Proteus Mirabilis, Proteus Spp, Proteus Vulgaris, Escherichia Coli, Enterobacter Aerogenes, Klebsiella Pneumonide, Klebsiella Spp, Haemofilus Influenzae, Candida Albicans, Candida Spp, Enterococcus Beta Hemolítico Grupo A
- Aislado(s) (texto libre)

ANTIBIOGRAMA (columnas 1, 2, 3 — S=Sensible, R=Resistente):

| Columna izquierda | Columna derecha |
|-------------------|-----------------|
| PENICILINA | NORFLOXACIN |
| PIPERACILINA | KARAMICINA |
| CARBENICILINA | GENTAMICINA |
| AMPICILINA | TABRAMICINA |
| AMOXICILINA | AMIKACINA |
| CEFALEXINA | CEFTRIAZONA |
| CEFOTAXINA | CEFAZOLIN |

**Pestaña Cont.:**

| Columna izquierda | Columna derecha |
|-------------------|-----------------|
| TETRACICLINA | LEVOFLOXACIN |
| MINOCICLINA | FURADANTOINA |
| ERITROCICLINA | CIPROFLAXACINA |
| LINCOMICINA | CLINDAMICINA |
| FOSFOCIL | SULFATRYM |
| CEFEPIME | VANCOMICINA |
| AC. NALIDIXICO | IMIPENEN |
| AMOX. AC. CLAV. | CEFUNOXIMA |

**Pestaña Cont. 3 — Examen Microscópico:**
- EPITELIOS, LEUCOCITOS, HEMATIES
- TINCION DE GRAM, TINCION ZIEHL NEELSEN
- BACTERIAS, LEVADURAS, T. VAGINALIS
- Campo: OBSERVACION

---

### 3. SEROLOGÍA — Aglutininas Febriles

- Bioanalista (dropdown)
- Checkbox: Reportar
- Parámetros con campo resultado (texto libre, ej: NEGATIVO):
  - SALMONELLA O GRUPO A, B, C, D
  - SALMONELLA H GRUPO A, B, C, D
  - PROTEUS OX 2, OX 19, OX K
  - BRUCELLA ABORTUS
  - TYPHOIDE O SOMATICA
- Campo: OBSERVACION
- Botones: Grabar / Cancelar

---

### 4. ANÁLISIS DE CÓLERA

- Bioanalista (dropdown)
- COLOR (texto, ej: AMARILLO)
- CONSISTENCIA (texto, ej: DIARREICA)
- Vibrio Cholerae (VC0-1): campo resultado
- Vibrio Cholerae (VC01-1): campo resultado
- Vibrio Cholerae (VC0-139): campo resultado
- Campo: OBSERVACION
- Botones: Imprimir Resultado / Grabar / Cancelar

---

### 5. UROANÁLISIS / COPROLÓGICO SERIADO (2 pestañas)

**Pestaña Uroanálisis:**

Físico-Químico:
- COLOR (texto libre)
- ASPECTO (dropdown): LIGERO TURBIO, TURBIO, LIGERO, CLARO
- DENSIDAD (número)
- PH (número)
- GLUCOSA (dropdown): NEGATIVO, POSITIVO 1(+), POSITIVO 2(+), POSITIVO 3(+), POSITIVO 4(+), TRAZAS
- PROTEINA (mismo dropdown)
- ACETONA (mismo dropdown)
- BILIRRUBINA (mismo dropdown)
- UROBILINOGENO (mismo dropdown)
- SANGRE OCULTA (mismo dropdown)
- HEMOGLOBINA (mismo dropdown)
- NITRITO (texto)

Segmento Urinario:
- LEUCOCITOS (texto, ej: 1-2 /C)
- ERITROCITOS (texto, ej: 0-1 /C)
- CELULAS EPITELIALES (dropdown): AUSENTES, ALGUNAS, ESCASAS, ABUNDANTES, MODERADAS, NUMEROSAS
- CELULAS RENALES (mismo dropdown)
- BACTERIAS (dropdown): AUSENTES, PRESENTES
- FIBRAS MUCOSAS (dropdown): AUSENTES, PRESENTES
- CRISTALES (dropdown): AUSENTES, URATOS AMORFOS, ACIDO URICO, OXALATO CALCIO, FOSFATO, FOSFATO TRIPLE
- CILINDROS (dropdown): AUSENTES, GRANULOSOS, HIALINOS, EPITELIALES, GLOBULOS ROJOS, GLOBULOS BLANCO
- LEVADURAS (dropdown): AUSENTES, PRESENTES
- T. VAGINALIS (dropdown): AUSENTES, PRESENTES

Botones: Imprimir Coprológico / Imprimir Examen Orina / Grabar / Cancelar

**Pestaña Coprológico:**
- Bioanalista
- TIPO ESTUDIO (texto, ej: NORMAL)
- COLOR (texto)
- CONSISTENCIA (texto)
- Checkbox: NO SE OBSERVAN ELEMENTOS PARASITARIOS EN ESTA MUESTRA
- SE OBSERVAN: 6 líneas de texto libre
- SANGRE OCULTA: campo texto
- INVEST. DE AMEBAS: 3 líneas de texto libre
- Campo: OBSERVACION

---

### 6. DIGESTIÓN EN HECES

Físico-Químico:
- COLOR, OLOR, CONSISTENCIA, ALIMENTOS NO DIGERIDOS, MUCUS, REACCION (PH), SANGRE OCULTA, GRASAS, SUSTANCIA REDUCTORA, TRIPSINA

Examen Microscópico:
- LEUCOCITOS, ERITROCITOS, CELULAS EPITELIALES, FIBRAS MUCOSAS, CRISTALES, BACTERIAS, HUEVOS, PARASITOS, QUISTES, GRANULOS, LARVAS, MATERIALES EXTRAÑOS

- Campo: OBSERVACION
- Botones: Imprimir Resultado / Grabar / Cancelar

---

### 7. ANÁLISIS VARIOS (módulo flexible)

Permite registrar cualquier análisis que no encaje en los formularios anteriores. Funciona con sistema Grupo → Sub-Grupo → Resultado.

**Formulario de captura:**
- Paciente (cabecera)
- Grupo (dropdown):
  - A- ANTIDOPING
  - HEMATOLOGIA
  - HORMONALES
  - INMUNO-SEROLOGIA
  - PRUEBAS ESPECIALES
  - QUIMICA CLINICA
  - SEROLOGIA
- Sub-Grupo (dropdown, cambia según el grupo). Ejemplo para SEROLOGIA:
  - A.S.O (NIÑOS)
  - A.S.O (ADULTOS)
  - FACTOR REUMATOIDE
  - PROTEINA C. REACTIVA (PCR)
  - PRUEBA DE EMBARAZO EN SUERO
  - TUBERCULINA EN SANGRE
  - V.D.R.L.
- Resultado (texto libre)
- Método (texto libre)
- Medidas (texto libre)
- Muestra (texto libre)
- Valor Ref. (texto libre)
- Botones: Aceptar / Cancelar / Grabar

Vista de reportados (tabla): Método, Valor Ref., Medidas, Muestra, Usuario

Acciones: Nuevo / Abrir / Borrar

---

## Módulos del sistema

### Pacientes
- Registrar nuevo paciente con todos los campos del formulario de captura
- Editar datos del paciente
- Buscar por nombre, cédula o código
- Ver historial completo de resultados anteriores

### Órdenes de análisis
- Crear orden asociada a un paciente
- Seleccionar uno o varios tipos de análisis
- Ver estado: pendiente / en proceso / listo / por validar

### Resultados
- Formulario específico por cada tipo de análisis (exactamente como se describe arriba)
- El bioanalista debe validar antes de poder imprimir

### Impresión / PDF
- Generar PDF con membrete del laboratorio
- Imprimir directamente desde el sistema
- Botón de impresión específico por tipo de análisis (ej: "Imprimir Coprológico" vs "Imprimir Examen Orina")

### Usuarios y roles
- **Recepcionista:** registra pacientes y crea órdenes
- **Bioanalista:** ingresa y valida resultados, aparece en dropdown "Bioanalista" de cada formulario
- **Administrador:** acceso total, gestión de usuarios y catálogos

### Auditoría (crítico — requisito legal)
Registrar automáticamente en cada acción:
- Quién creó un resultado (usuario)
- Quién lo modificó (con valor anterior y nuevo valor)
- Quién lo validó
- Fecha y hora exacta
- En qué laboratorio ocurrió

---

## Requisito técnico clave — multi-sucursal

- Lab 1 y Lab 2 son dos sucursales físicas distintas
- Ambas acceden a la misma base de datos en la nube
- Un paciente registrado en cualquier sucursal es visible en ambas
- Cada registro indica en qué laboratorio fue creado
- El selector de laboratorio activo debe estar visible en toda la interfaz

---

## Stack recomendado

- **Backend:** Laravel (PHP)
- **Frontend:** Laravel Blade + Tailwind CSS
- **Base de datos:** MySQL
- **PDF:** barryvdh/laravel-dompdf
- **Auditoría:** spatie/laravel-activitylog
- **Hosting:** Railway o Render (~$10–25/mes)

---

## Entregables esperados

1. Estructura completa del proyecto Laravel
2. Migraciones de base de datos para todas las tablas
3. Modelos, controladores y rutas
4. Vistas funcionales para cada módulo con sus formularios exactos
5. Sistema de roles y permisos (Recepcionista / Bioanalista / Administrador)
6. Registro de auditoría automático
7. Generación de PDF con plantilla del laboratorio
8. Catálogos editables: organismos, antibióticos, sub-grupos de análisis varios
9. Soporte para dos sucursales con base de datos compartida

---

Adjunto también una plantilla HTML con el diseño visual ya definido como referencia de interfaz.
