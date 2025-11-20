# 🚀 Seven Service API - Guía para Desarrolladores Frontend

## 📋 Tabla de Contenidos
- [Introducción](#introducción)
- [Quick Start](#quick-start)
- [Autenticación](#autenticación)
- [Endpoints Disponibles](#endpoints-disponibles)
- [Ejemplos de Uso](#ejemplos-de-uso)
- [Manejo de Errores](#manejo-de-errores)
- [CORS y Configuración](#cors-y-configuración)
- [Modelos de Datos](#modelos-de-datos)

---

## 🎯 Introducción

Bienvenido a la documentación de la API de **Seven Service**, un sistema de gestión para talleres de bicicletas.

### Información Básica

| Parámetro | Valor |
|-----------|-------|
| **Base URL** | `http://localhost/UNIVERSIDAD/Integrador/7service/public` |
| **Protocolo** | HTTP |
| **Formato** | JSON / HTML |
| **Autenticación** | Session-based (Cookies) |
| **Base de Datos** | MySQL (remota vía SSH en puerto 5060) |

### Tecnologías del Backend
- PHP 8.2+
- MySQL 8.4+
- Arquitectura MVC
- PDO para consultas seguras
- Bcrypt para passwords

---

## ⚡ Quick Start

### 1. Requisitos Previos
```bash
# El backend debe estar corriendo en:
http://localhost/UNIVERSIDAD/Integrador/7service/public

# El túnel SSH debe estar activo
ssh -L 5060:localhost:5060 proyecto_user@5.78.122.209
```

### 2. Primer Request - Login
```javascript
fetch('http://localhost/UNIVERSIDAD/Integrador/7service/public/process_login.php', {
    method: 'POST',
    credentials: 'include', // ⚠️ CRÍTICO: mantiene la sesión
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'correo=admin@sevenservice.com&password=admin123'
})
.then(response => {
    if (response.redirected) {
        console.log('✅ Login exitoso');
        return response;
    }
});
```

### 3. Segundo Request - Obtener Datos
```javascript
// Una vez logueado, puedes hacer requests a la API
fetch('http://localhost/UNIVERSIDAD/Integrador/7service/public/api/estadisticas', {
    credentials: 'include' // Envía la cookie de sesión
})
.then(response => response.json())
.then(data => console.log('Estadísticas:', data));
```

---

## 🔐 Autenticación

### Sistema de Sesiones

El backend usa **sesiones PHP** con cookies. NO usa tokens JWT ni API Keys.

#### ¿Cómo funciona?

1. **Login**: Envías credenciales → Backend crea sesión → Devuelve cookie `PHPSESSID`
2. **Requests posteriores**: Envías cookie automáticamente con `credentials: 'include'`
3. **Logout**: Destruye la sesión → Cookie inválida

### Usuarios de Prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| **Admin** | admin@sevenservice.com | admin123 |
| **Técnico** | tecnico@sevenservice.com | admin123 |

### Endpoints de Autenticación

#### 🔓 Login
```http
POST /process_login.php
Content-Type: application/x-www-form-urlencoded

correo=admin@sevenservice.com&password=admin123
```

**Respuesta exitosa:**
```
HTTP 302 Redirect
Location: /UNIVERSIDAD/Integrador/7service/public/dashboard
Set-Cookie: PHPSESSID=...
```

**Respuesta error:**
```
HTTP 302 Redirect
Location: /UNIVERSIDAD/Integrador/7service/public/
```
Revisa `$_SESSION['error_login']` para el mensaje de error.

#### 🚪 Logout
```http
GET /logout
```

---

## 📡 Endpoints Disponibles

### Dashboard

#### Obtener Estadísticas
```http
GET /api/estadisticas
```

**Parámetros Query (opcionales):**
- `fecha_inicio` (YYYY-MM-DD)
- `fecha_fin` (YYYY-MM-DD)

**Respuesta:**
```json
{
  "total_ordenes": 150,
  "ordenes_pendientes": 12,
  "ordenes_en_reparacion": 8,
  "ordenes_completadas": 130,
  "ingresos_totales": 15000.00,
  "ingresos_mes_actual": 3500.00,
  "productos_stock_bajo": 5,
  "clientes_activos": 45
}
```

---

### Clientes

#### Listar Clientes (HTML)
```http
GET /clientes
```
Retorna vista HTML del listado.

#### Buscar Clientes (JSON)
```http
GET /api/clientes/buscar?term=juan
```

**Respuesta:**
```json
[
  {
    "id_cliente": 1,
    "nombre": "Juan Pérez",
    "telefono": "987654321",
    "correo": "juan@example.com",
    "direccion": "Av. Principal 123",
    "activo": true
  }
]
```

#### Crear Cliente
```http
POST /clientes/crear
Content-Type: application/x-www-form-urlencoded

nombre=Juan Pérez&telefono=987654321&correo=juan@example.com
```

**Parámetros:**
- `nombre` (requerido)
- `telefono` (requerido)
- `correo` (opcional)
- `direccion` (opcional)
- `dni` (opcional)

**Respuesta exitosa:**
```json
{
  "success": true,
  "message": "Cliente creado exitosamente",
  "cliente_id": 15
}
```

#### Ver Detalle de Cliente
```http
GET /clientes/{id}
```

#### Actualizar Cliente
```http
POST /clientes/{id}/actualizar
Content-Type: application/x-www-form-urlencoded

nombre=Juan Pérez Actualizado&telefono=999888777
```

#### Eliminar Cliente (Soft Delete)
```http
POST /clientes/{id}/eliminar
```

---

### Órdenes de Servicio

#### Listar Órdenes
```http
GET /ordenes
```

#### Crear Orden
```http
POST /ordenes/crear
Content-Type: application/x-www-form-urlencoded

id_cliente=5&id_bicicleta=3&descripcion_problema=Frenos no funcionan&prioridad=urgente
```

**Parámetros:**
- `id_cliente` (int, requerido)
- `id_bicicleta` (int, requerido)
- `descripcion_problema` (text, requerido)
- `prioridad` (enum: normal, urgente)
- `fecha_entrega_estimada` (date, opcional)
- `productos[]` (array de IDs de productos usados)

#### Ver Detalle de Orden
```http
GET /ordenes/{id}
```

#### Cambiar Estado de Orden
```http
POST /ordenes/{id}/cambiar-estado
Content-Type: application/x-www-form-urlencoded

nuevo_estado=en_reparacion&observaciones=Iniciando reparación
```

**Estados posibles:**
- `pendiente`
- `en_reparacion`
- `esperando_repuestos`
- `completada`
- `entregada`
- `cancelada`

---

### Inventario / Productos

#### Listar Productos
```http
GET /inventario
```

#### Buscar Productos (JSON)
```http
GET /api/productos/buscar?term=cadena
```

**Respuesta:**
```json
[
  {
    "id_producto": 3,
    "sku": "CDN-001",
    "nombre": "Cadena Shimano HG-71",
    "precio_venta": 60.00,
    "stock_actual": 12,
    "categoria": "Transmisión"
  }
]
```

#### Crear Producto
```http
POST /inventario/crear
Content-Type: application/x-www-form-urlencoded

sku=TEST-001&nombre=Producto Prueba&id_categoria=1&id_proveedor=1&precio_compra=30.00&precio_venta=50.00&stock_actual=10&stock_minimo=3
```

**Parámetros:**
- `sku` (string único, requerido)
- `nombre` (string, requerido)
- `id_categoria` (int, requerido)
- `id_proveedor` (int, requerido)
- `precio_compra` (decimal, requerido)
- `precio_venta` (decimal, requerido)
- `stock_actual` (int, requerido)
- `stock_minimo` (int, requerido)
- `descripcion` (text, opcional)

---

## 💻 Ejemplos de Uso

### Ejemplo con Vanilla JavaScript

```javascript
// ============================================
// 1. CREAR CLASE API CLIENT
// ============================================
class SevenServiceAPI {
    constructor(baseURL) {
        this.baseURL = baseURL;
    }

    async login(correo, password) {
        const response = await fetch(`${this.baseURL}/process_login.php`, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `correo=${encodeURIComponent(correo)}&password=${encodeURIComponent(password)}`
        });

        return response.redirected; // true si login exitoso
    }

    async getEstadisticas(fechaInicio = null, fechaFin = null) {
        let url = `${this.baseURL}/api/estadisticas`;
        
        if (fechaInicio && fechaFin) {
            url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
        }

        const response = await fetch(url, {
            credentials: 'include'
        });

        return response.json();
    }

    async buscarClientes(term) {
        const response = await fetch(
            `${this.baseURL}/api/clientes/buscar?term=${encodeURIComponent(term)}`,
            { credentials: 'include' }
        );

        return response.json();
    }

    async crearCliente(data) {
        const formData = new URLSearchParams(data);

        const response = await fetch(`${this.baseURL}/clientes/crear`, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        });

        return response.json();
    }

    async logout() {
        const response = await fetch(`${this.baseURL}/logout`, {
            credentials: 'include'
        });
        
        return response.redirected;
    }
}

// ============================================
// 2. USAR LA API
// ============================================
const api = new SevenServiceAPI('http://localhost/UNIVERSIDAD/Integrador/7service/public');

// Login
async function iniciarSesion() {
    const success = await api.login('admin@sevenservice.com', 'admin123');
    
    if (success) {
        console.log('✅ Login exitoso');
        cargarDashboard();
    } else {
        console.error('❌ Login fallido');
    }
}

// Cargar dashboard
async function cargarDashboard() {
    const stats = await api.getEstadisticas();
    console.log('Estadísticas:', stats);
    
    // Actualizar UI
    document.getElementById('totalOrdenes').textContent = stats.total_ordenes;
    document.getElementById('ingresos').textContent = `$${stats.ingresos_totales}`;
}

// Buscar clientes con debounce
let searchTimeout;
function buscarCliente(event) {
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(async () => {
        const term = event.target.value;
        
        if (term.length >= 3) {
            const clientes = await api.buscarClientes(term);
            mostrarResultados(clientes);
        }
    }, 300);
}

// Crear nuevo cliente
async function crearNuevoCliente(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    const result = await api.crearCliente(data);
    
    if (result.success) {
        alert('Cliente creado exitosamente');
        event.target.reset();
    }
}
```

### Ejemplo con React

```jsx
// ============================================
// hooks/useSevenServiceAPI.js
// ============================================
import { useState, useEffect } from 'react';

const API_BASE = 'http://localhost/UNIVERSIDAD/Integrador/7service/public';

export function useAuth() {
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [loading, setLoading] = useState(false);

    const login = async (correo, password) => {
        setLoading(true);
        
        try {
            const response = await fetch(`${API_BASE}/process_login.php`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `correo=${encodeURIComponent(correo)}&password=${encodeURIComponent(password)}`
            });

            if (response.redirected) {
                setIsAuthenticated(true);
                return true;
            }
            
            return false;
        } finally {
            setLoading(false);
        }
    };

    const logout = async () => {
        await fetch(`${API_BASE}/logout`, {
            credentials: 'include'
        });
        
        setIsAuthenticated(false);
    };

    return { isAuthenticated, login, logout, loading };
}

export function useEstadisticas() {
    const [estadisticas, setEstadisticas] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchStats = async () => {
            const response = await fetch(`${API_BASE}/api/estadisticas`, {
                credentials: 'include'
            });
            
            const data = await response.json();
            setEstadisticas(data);
            setLoading(false);
        };

        fetchStats();
    }, []);

    return { estadisticas, loading };
}

// ============================================
// components/Dashboard.jsx
// ============================================
import React from 'react';
import { useEstadisticas } from '../hooks/useSevenServiceAPI';

export default function Dashboard() {
    const { estadisticas, loading } = useEstadisticas();

    if (loading) return <div>Cargando...</div>;

    return (
        <div className="dashboard">
            <h1>Dashboard</h1>
            <div className="stats-grid">
                <div className="stat-card">
                    <h3>Total Órdenes</h3>
                    <p>{estadisticas.total_ordenes}</p>
                </div>
                <div className="stat-card">
                    <h3>Ingresos Totales</h3>
                    <p>${estadisticas.ingresos_totales}</p>
                </div>
            </div>
        </div>
    );
}
```

### Ejemplo con Vue 3

```vue
<!-- composables/useAPI.js -->
<script>
import { ref } from 'vue';

const API_BASE = 'http://localhost/UNIVERSIDAD/Integrador/7service/public';

export function useAPI() {
    const loading = ref(false);
    const error = ref(null);

    const apiCall = async (url, options = {}) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(`${API_BASE}${url}`, {
                ...options,
                credentials: 'include'
            });

            const data = await response.json();
            return data;
        } catch (err) {
            error.value = err.message;
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return { apiCall, loading, error };
}
</script>

<!-- components/Dashboard.vue -->
<template>
    <div class="dashboard">
        <h1>Dashboard</h1>
        
        <div v-if="loading">Cargando...</div>
        
        <div v-else class="stats-grid">
            <div class="stat-card">
                <h3>Total Órdenes</h3>
                <p>{{ stats.total_ordenes }}</p>
            </div>
            <div class="stat-card">
                <h3>Ingresos</h3>
                <p>${{ stats.ingresos_totales }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAPI } from '../composables/useAPI';

const { apiCall, loading } = useAPI();
const stats = ref({});

onMounted(async () => {
    stats.value = await apiCall('/api/estadisticas');
});
</script>
```

---

## ⚠️ Manejo de Errores

### Códigos de Estado HTTP

| Código | Significado |
|--------|-------------|
| 200 | ✅ Éxito |
| 302 | 🔄 Redirección (normal en login/logout) |
| 400 | ❌ Datos inválidos |
| 401 | 🔐 No autenticado |
| 403 | 🚫 Sin permisos |
| 404 | 📭 No encontrado |
| 500 | 💥 Error del servidor |

### Estructura de Errores JSON

```json
{
  "success": false,
  "message": "Descripción del error",
  "errors": {
    "campo1": "Error específico del campo",
    "campo2": "Otro error"
  }
}
```

### Ejemplo de Manejo

```javascript
async function crearCliente(data) {
    try {
        const response = await fetch(`${API_BASE}/clientes/crear`, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        });

        const result = await response.json();

        if (!result.success) {
            // Mostrar errores específicos
            if (result.errors) {
                Object.entries(result.errors).forEach(([field, message]) => {
                    console.error(`${field}: ${message}`);
                });
            }
            
            throw new Error(result.message || 'Error al crear cliente');
        }

        return result;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}
```

---

## 🌐 CORS y Configuración

### CORS para Desarrollo

Si tu frontend corre en un puerto diferente (ej: React en `http://localhost:3000`), necesitas habilitar CORS en el backend.

**Ya está habilitado en `api-docs.php`:**
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true'); // Para cookies
```

### Configuración en tu Framework

#### Vite (React/Vue)
```javascript
// vite.config.js
export default {
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '/UNIVERSIDAD/Integrador/7service/public')
      }
    }
  }
}
```

#### Create React App
```javascript
// package.json
{
  "proxy": "http://localhost/UNIVERSIDAD/Integrador/7service/public"
}
```

---

## 📊 Modelos de Datos

### Cliente
```typescript
interface Cliente {
  id_cliente: number;
  nombre: string;
  telefono: string;
  correo?: string;
  direccion?: string;
  dni?: string;
  activo: boolean;
  fecha_registro: string; // ISO 8601
}
```

### Producto
```typescript
interface Producto {
  id_producto: number;
  sku: string;
  nombre: string;
  descripcion?: string;
  id_categoria: number;
  id_proveedor: number;
  precio_compra: number;
  precio_venta: number;
  stock_actual: number;
  stock_minimo: number;
  activo: boolean;
}
```

### Orden de Servicio
```typescript
interface OrdenServicio {
  id_orden: number;
  numero_orden: string; // Ej: "ORD-2025-001"
  id_cliente: number;
  id_bicicleta: number;
  descripcion_problema: string;
  diagnostico?: string;
  estado: 'pendiente' | 'en_reparacion' | 'esperando_repuestos' | 'completada' | 'entregada' | 'cancelada';
  prioridad: 'normal' | 'urgente';
  fecha_ingreso: string; // ISO 8601
  fecha_entrega_estimada?: string;
  fecha_entrega_real?: string;
  costo_mano_obra: number;
  costo_repuestos: number;
  costo_total: number;
  id_tecnico_asignado?: number;
}
```

### Bicicleta
```typescript
interface Bicicleta {
  id_bicicleta: number;
  id_cliente: number;
  marca: string;
  modelo: string;
  tipo: 'montana' | 'ruta' | 'hibrida' | 'urbana' | 'electrica' | 'bmx';
  color?: string;
  numero_serie?: string;
  año?: number;
  activo: boolean;
}
```

---

## 🔧 Troubleshooting

### Problema: No se mantiene la sesión

**Solución:** Asegúrate de usar `credentials: 'include'` en TODAS las peticiones.

### Problema: Error de CORS

**Solución:** Verifica que el backend tenga los headers CORS correctos o usa un proxy en tu bundler.

### Problema: La base de datos no responde

**Solución:** Verifica que el túnel SSH esté activo:
```bash
ssh -L 5060:localhost:5060 proyecto_user@5.78.122.209
```

### Problema: Sesión expira muy rápido

**Solución:** Modifica el `.env` del backend:
```env
SESSION_LIFETIME=7200  # 2 horas en segundos
```

---

## 📚 Recursos Adicionales

- **Documentación Visual:** `http://localhost/UNIVERSIDAD/Integrador/7service/public/api-documentation.html`
- **JSON de la API:** `http://localhost/UNIVERSIDAD/Integrador/7service/public/api-docs.php`
- **Swagger YAML:** `/docs/swagger/swagger.yaml`
- **README Principal:** `/README.md`
- **Arquitectura:** `/docs/ARQUITECTURA.md`

---

## 👥 Contacto y Soporte

Si tienes dudas o encuentras algún bug:

1. Revisa los logs del backend en `/storage/logs/`
2. Verifica la consola del navegador (F12)
3. Usa las herramientas de red del navegador para ver las peticiones
4. Consulta la documentación visual en `api-documentation.html`

---

**¡Feliz desarrollo! 🚀**
