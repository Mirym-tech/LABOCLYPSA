// Cliente HTTP centralizado para LABOCLYPSA
// La URL del API se configura en js/config.js

function getToken() {
  return localStorage.getItem('lab_token');
}

function getUser() {
  try { return JSON.parse(localStorage.getItem('lab_user')); } catch { return null; }
}

async function request(path, options = {}) {
  const headers = { 'Content-Type': 'application/json', ...options.headers };
  const token = getToken();
  if (token) headers['Authorization'] = `Bearer ${token}`;

  const res = await fetch(`${API_BASE}${path}`, { ...options, headers });

  if (res.status === 401) {
    localStorage.removeItem('lab_token');
    localStorage.removeItem('lab_user');
    window.location.href = 'login.html';
    return;
  }

  const data = await res.json();
  if (!res.ok) throw new Error(data.error || `Error ${res.status}`);
  return data;
}

const api = {
  // AUTH
  auth: {
    me: () => request('/auth/me'),
    logout: () => request('/auth/logout', { method: 'POST' }),
  },

  // PACIENTES
  pacientes: {
    listar: (q = '', page = 1) => request(`/pacientes?q=${encodeURIComponent(q)}&page=${page}`),
    obtener: (id) => request(`/pacientes/${id}`),
    crear: (body) => request('/pacientes', { method: 'POST', body: JSON.stringify(body) }),
    editar: (id, body) => request(`/pacientes/${id}`, { method: 'PUT', body: JSON.stringify(body) }),
  },

  // ÓRDENES
  ordenes: {
    listar: (params = {}) => {
      const qs = new URLSearchParams(params).toString();
      return request(`/ordenes?${qs}`);
    },
    obtener: (id) => request(`/ordenes/${id}`),
    crear: (body) => request('/ordenes', { method: 'POST', body: JSON.stringify(body) }),
    cambiarEstado: (id, estado) => request(`/ordenes/${id}/estado`, { method: 'PATCH', body: JSON.stringify({ estado }) }),
  },

  // RESULTADOS
  resultados: {
    guardar: (ordenAnalisisId, body) => request(`/resultados/${ordenAnalisisId}`, { method: 'PUT', body: JSON.stringify(body) }),
    validar: (ordenAnalisisId) => request(`/resultados/${ordenAnalisisId}/validar`, { method: 'POST' }),
    revertir: (ordenAnalisisId) => request(`/resultados/${ordenAnalisisId}/validacion`, { method: 'DELETE' }),
  },

  // USUARIOS (solo admin)
  usuarios: {
    listar: () => request('/usuarios'),
    crear: (body) => request('/usuarios', { method: 'POST', body: JSON.stringify(body) }),
    editar: (id, body) => request(`/usuarios/${id}`, { method: 'PUT', body: JSON.stringify(body) }),
    cambiarPassword: (id, password) => request(`/usuarios/${id}/password`, { method: 'PUT', body: JSON.stringify({ password }) }),
  },

  // AUDITORÍA (solo admin)
  auditoria: {
    listar: (params = {}) => {
      const qs = new URLSearchParams(params).toString();
      return request(`/auditoria?${qs}`);
    },
  },

  // CATÁLOGO
  catalogo: {
    analisis: () => request('/catalogo/analisis'),
    laboratorios: () => request('/catalogo/laboratorios'),
  },
};

// Helpers de UI reutilizables
function showToast(msg, type = 'success') {
  const existing = document.getElementById('lab-toast');
  if (existing) existing.remove();

  const t = document.createElement('div');
  t.id = 'lab-toast';
  t.style.cssText = `
    position:fixed;bottom:24px;right:24px;z-index:9999;
    background:${type === 'error' ? '#7a1a1a' : '#1a5c3a'};
    color:#fff;padding:12px 18px;border-radius:8px;
    font-family:'DM Sans',sans-serif;font-size:13px;font-weight:500;
    box-shadow:0 4px 16px rgba(0,0,0,0.15);
    animation:slideIn 0.2s ease;
  `;
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

function estadoBadge(estado) {
  const map = {
    pendiente:   ['badge-blue',   'Pendiente'],
    en_proceso:  ['badge-amber',  'En proceso'],
    por_validar: ['badge-red',    'Por validar'],
    validado:    ['badge-green',  'Validado'],
    completado:  ['badge-green',  'Completado'],
  };
  const [cls, label] = map[estado] || ['badge-gray', estado];
  return `<span class="badge ${cls}">${label}</span>`;
}

function rolBadge(rol) {
  const map = {
    admin:         ['badge-purple', 'Administrador'],
    bioanalista:   ['badge-blue',   'Bioanalista'],
    recepcionista: ['badge-amber',  'Recepcionista'],
  };
  const [cls, label] = map[rol] || ['badge-gray', rol];
  return `<span class="badge ${cls}">${label}</span>`;
}

function formatFecha(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleDateString('es-DO', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function formatHora(iso) {
  if (!iso) return '—';
  return new Date(iso).toLocaleTimeString('es-DO', { hour: '2-digit', minute: '2-digit' });
}
