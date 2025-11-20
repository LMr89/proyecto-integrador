# 📚 Índice General de Documentación - Seven Service

Bienvenido al centro de documentación del proyecto **Seven Service**, sistema de gestión para talleres de bicicletas.

---

## 🗂️ Estructura de Documentación

```
docs/
├── README_DOCS.md           ← Estás aquí (índice general)
├── FRONTEND_README.md       ← Guía completa para desarrolladores frontend
├── ARQUITECTURA.md          ← Diseño técnico del sistema
├── INICIO_RAPIDO.md         ← Setup en 5 minutos
├── DIAGRAMAS.md            ← Diagramas visuales del sistema
├── PRUEBAS.md              ← Checklist de testing
└── swagger/
    └── swagger.yaml         ← Especificación OpenAPI 3.0
```

---

## 📖 Guías por Perfil

### 👨‍💻 Desarrolladores Frontend

**Empieza aquí:** [`FRONTEND_README.md`](./FRONTEND_README.md)

Esta guía contiene:
- ✅ Quick Start con ejemplos de código
- ✅ Todos los endpoints disponibles
- ✅ Autenticación y manejo de sesiones
- ✅ Ejemplos con Vanilla JS, React y Vue
- ✅ Manejo de errores y CORS
- ✅ Modelos de datos TypeScript
- ✅ Troubleshooting común

**También útil:**
- 🌐 **Documentación Visual Interactiva:**  
  `http://localhost/UNIVERSIDAD/Integrador/7service/public/api-documentation.html`
  
- 📄 **JSON de la API:**  
  `http://localhost/UNIVERSIDAD/Integrador/7service/public/api-docs.php`

---

### 🏗️ Desarrolladores Backend / Arquitectos

**Empieza aquí:** [`ARQUITECTURA.md`](./ARQUITECTURA.md)

Esta guía contiene:
- ✅ Patrones de diseño (MVC, Singleton, Active Record)
- ✅ Principios SOLID aplicados
- ✅ Estructura de carpetas y responsabilidades
- ✅ Flujo de peticiones
- ✅ Seguridad (SQL Injection, XSS, CSRF)
- ✅ Transacciones y optimización
- ✅ Estrategias de escalabilidad

**También útil:**
- 📊 [`DIAGRAMAS.md`](./DIAGRAMAS.md) - Diagramas de flujo y ERD
- 📝 **Código fuente comentado** en `/app/`

---

### 🚀 Nuevos Integrantes del Equipo

**Empieza aquí:** [`INICIO_RAPIDO.md`](./INICIO_RAPIDO.md)

Esta guía contiene:
- ✅ Setup del entorno en 5 minutos
- ✅ Configuración de base de datos
- ✅ Verificación de requisitos
- ✅ Credenciales de acceso
- ✅ Problemas comunes y soluciones
- ✅ Checklist de verificación

**También útil:**
- 📋 **README principal:** [`../README.md`](../README.md) - Visión general del proyecto

---

### 🧪 QA / Testers

**Empieza aquí:** [`PRUEBAS.md`](./PRUEBAS.md)

Esta guía contiene:
- ✅ 16 tests funcionales paso a paso
- ✅ Tests de seguridad
- ✅ Tests de rendimiento
- ✅ Tests de responsive
- ✅ Casos de uso completos
- ✅ Reporte de bugs

---

### 📐 Analistas / Product Owners

**Empieza aquí:** [`../README.md`](../README.md) + [`DIAGRAMAS.md`](./DIAGRAMAS.md)

Estas guías contienen:
- ✅ Funcionalidades del sistema
- ✅ Casos de uso principales
- ✅ Flujos de trabajo
- ✅ Diagramas de navegación
- ✅ Matriz de permisos por rol
- ✅ Métricas de rendimiento

---

## 🎯 Guías por Tarea

### "Quiero consumir la API desde mi frontend"
1. Lee [`FRONTEND_README.md`](./FRONTEND_README.md)
2. Abre `http://localhost/UNIVERSIDAD/Integrador/7service/public/api-documentation.html`
3. Prueba los ejemplos de código JavaScript

### "Quiero entender cómo funciona el sistema"
1. Lee [`ARQUITECTURA.md`](./ARQUITECTURA.md)
2. Revisa [`DIAGRAMAS.md`](./DIAGRAMAS.md)
3. Explora el código en `/app/Core/`

### "Quiero instalar y correr el proyecto"
1. Lee [`INICIO_RAPIDO.md`](./INICIO_RAPIDO.md)
2. Sigue el checklist paso a paso
3. Si hay problemas, consulta la sección Troubleshooting

### "Quiero probar que todo funcione"
1. Lee [`PRUEBAS.md`](./PRUEBAS.md)
2. Ejecuta los 16 tests funcionales
3. Marca cada uno completado

### "Quiero agregar una nueva funcionalidad"
1. Lee la sección "Crear Nuevos Módulos" en [`../README.md`](../README.md)
2. Revisa [`ARQUITECTURA.md`](./ARQUITECTURA.md) para entender los patrones
3. Mira ejemplos existentes en `/app/Controllers/` y `/app/Models/`

---

## 📊 Documentación Técnica Detallada

### Arquitectura del Sistema

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │ HTTP Request
       ▼
┌─────────────────────────────────┐
│   Apache Web Server             │
│   + mod_rewrite (URLs limpias)  │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   public/index.php              │
│   (Front Controller)            │
│   - Autoloader                  │
│   - Route parsing               │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   Router                        │
│   - Match route                 │
│   - Execute middleware          │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   Middleware (Opcional)         │
│   - AuthMiddleware              │
│   - RoleMiddleware              │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   Controller                    │
│   - Validar input               │
│   - Llamar a Models             │
│   - Retornar View/JSON          │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   Model (Active Record)         │
│   - Lógica de negocio           │
│   - CRUD operations             │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   Database (Singleton)          │
│   - PDO Connection              │
│   - Prepared Statements         │
│   - Transactions                │
└────────────┬────────────────────┘
             │
             ▼
┌─────────────────────────────────┐
│   MySQL Database                │
│   (Remote via SSH tunnel)       │
│   localhost:5060                │
└─────────────────────────────────┘
```

Más detalles en: [`ARQUITECTURA.md`](./ARQUITECTURA.md)

---

### Stack Tecnológico

| Capa | Tecnología | Versión |
|------|------------|---------|
| **Backend** | PHP | 8.2+ |
| **Base de Datos** | MySQL | 8.4+ |
| **Servidor Web** | Apache | 2.4+ (XAMPP) |
| **Frontend (Integrado)** | Tailwind CSS | 3.x CDN |
| **Interactividad** | Alpine.js | 3.x CDN |
| **Iconos** | Font Awesome | 6.4 |
| **Autenticación** | PHP Sessions | - |
| **Passwords** | Bcrypt | - |
| **Database Access** | PDO | - |
| **Arquitectura** | MVC | Custom |
| **API Documentation** | Swagger/OpenAPI | 3.0 |

---

### Estructura de Carpetas

```
Integrador/7service/
│
├── app/                          # Aplicación principal
│   ├── Controllers/              # Controladores MVC
│   │   ├── Controller.php        # Base controller
│   │   ├── AuthController.php    # Autenticación
│   │   ├── DashboardController.php
│   │   └── ClienteController.php
│   │
│   ├── Models/                   # Modelos (Active Record)
│   │   ├── Model.php             # Base model
│   │   ├── Usuario.php
│   │   ├── Cliente.php
│   │   ├── Producto.php
│   │   └── OrdenServicio.php
│   │
│   ├── Views/                    # Vistas (HTML/PHP)
│   │   ├── layouts/              # Plantillas
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   ├── auth/
│   │   │   └── login.php
│   │   └── dashboard/
│   │       └── index.php
│   │
│   ├── Core/                     # Clases del núcleo
│   │   ├── Router.php            # Enrutador
│   │   └── Database.php          # Singleton DB
│   │
│   └── Middleware/               # Middleware
│       ├── AuthMiddleware.php
│       └── RoleMiddleware.php
│
├── config/                       # Configuración
│   ├── config.php                # Config principal
│   └── routes.php                # Definición de rutas
│
├── public/                       # Punto de entrada web
│   ├── index.php                 # Front Controller
│   ├── .htaccess                 # Rewrite rules
│   ├── api-docs.php              # API JSON
│   ├── api-documentation.html    # Docs visuales
│   ├── process_login.php         # Login handler
│   ├── login_simple.php          # Login alternativo
│   └── debug_login.php           # Debug tools
│
├── docs/                         # 📚 DOCUMENTACIÓN
│   ├── README_DOCS.md            # Este archivo
│   ├── FRONTEND_README.md        # Guía para frontend
│   ├── ARQUITECTURA.md           # Diseño del sistema
│   ├── INICIO_RAPIDO.md          # Quick start
│   ├── DIAGRAMAS.md              # Diagramas visuales
│   ├── PRUEBAS.md                # Tests
│   └── swagger/
│       └── swagger.yaml          # OpenAPI spec
│
├── info/                         # Datos iniciales
│   ├── dump-taller_bicicletas-*.sql
│   ├── datos_iniciales.sql
│   └── *.md                      # Info del negocio
│
├── storage/                      # Archivos generados
│   ├── logs/                     # Logs del sistema
│   └── cache/                    # Caché
│
├── .env                          # Variables de entorno
├── .env.example                  # Template de .env
├── .gitignore                    # Git exclusions
└── README.md                     # README principal
```

---

## 🔗 Enlaces Rápidos

### Documentación en Archivos

| Documento | Descripción |
|-----------|-------------|
| [`README.md`](../README.md) | Visión general, instalación, uso básico |
| [`FRONTEND_README.md`](./FRONTEND_README.md) | **Guía completa para frontend developers** |
| [`ARQUITECTURA.md`](./ARQUITECTURA.md) | Diseño técnico y patrones |
| [`INICIO_RAPIDO.md`](./INICIO_RAPIDO.md) | Setup en 5 minutos |
| [`DIAGRAMAS.md`](./DIAGRAMAS.md) | Diagramas de flujo y ERD |
| [`PRUEBAS.md`](./PRUEBAS.md) | Checklist de testing |
| [`swagger/swagger.yaml`](./swagger/swagger.yaml) | Especificación OpenAPI |

### Documentación Web (Requiere servidor activo)

| URL | Descripción |
|-----|-------------|
| `http://localhost/UNIVERSIDAD/Integrador/7service/public/api-documentation.html` | **Docs visuales interactivas** |
| `http://localhost/UNIVERSIDAD/Integrador/7service/public/api-docs.php` | API en formato JSON |
| `http://localhost/UNIVERSIDAD/Integrador/7service/public/` | Login del sistema |
| `http://localhost/UNIVERSIDAD/Integrador/7service/public/dashboard` | Dashboard principal |

---

## 🎓 Tutoriales Paso a Paso

### Para Principiantes en PHP/MVC

1. **Entiende la arquitectura:**
   - Lee [`ARQUITECTURA.md`](./ARQUITECTURA.md) sección "Arquitectura para Principiantes"
   - Revisa [`DIAGRAMAS.md`](./DIAGRAMAS.md) para ver flujos visuales

2. **Sigue un request de principio a fin:**
   - Usuario visita `/clientes`
   - Apache redirige a `public/index.php` (Front Controller)
   - Router matchea la ruta y llama `ClienteController@index`
   - Controller llama `Cliente::findAll()` (Model)
   - Model ejecuta query en Database
   - Controller renderiza `Views/clientes/index.php`

3. **Crea tu primer módulo:**
   - Sigue el tutorial en [`README.md`](../README.md) sección "Crear Nuevos Módulos"

### Para Frontend Developers

1. **Setup inicial:**
   - Lee [`INICIO_RAPIDO.md`](./INICIO_RAPIDO.md)
   - Verifica que el backend funcione

2. **Consume la API:**
   - Abre [`FRONTEND_README.md`](./FRONTEND_README.md)
   - Copia los ejemplos de código
   - Prueba en tu consola del navegador

3. **Integra en tu framework:**
   - Usa los ejemplos de React/Vue/Angular en [`FRONTEND_README.md`](./FRONTEND_README.md)
   - Configura CORS si es necesario

---

## 🐛 Reportar Problemas

Si encuentras un bug o tienes una sugerencia:

1. **Verifica primero:**
   - Consulta la sección Troubleshooting del documento relevante
   - Revisa los logs en `/storage/logs/`
   - Usa las herramientas de desarrollo del navegador (F12)

2. **Recopila información:**
   - ¿Qué estabas intentando hacer?
   - ¿Qué pasó en su lugar?
   - ¿Mensaje de error exacto?
   - ¿Captura de pantalla?

3. **Dónde reportar:**
   - Crea un issue en GitHub (si aplica)
   - Documenta el problema detalladamente
   - Incluye pasos para reproducir

---

## 📞 Contacto

**Proyecto:** Seven Service - Sistema de Gestión de Taller de Bicicletas  
**Versión:** 1.0.0  
**Última actualización:** Octubre 2025

---

## 🎯 Checklist de Onboarding

### Para nuevos desarrolladores:

- [ ] Leer [`README.md`](../README.md) principal
- [ ] Seguir [`INICIO_RAPIDO.md`](./INICIO_RAPIDO.md) para setup
- [ ] Entender la arquitectura en [`ARQUITECTURA.md`](./ARQUITECTURA.md)
- [ ] Revisar [`DIAGRAMAS.md`](./DIAGRAMAS.md)
- [ ] Ejecutar los tests en [`PRUEBAS.md`](./PRUEBAS.md)
- [ ] Si eres frontend, leer [`FRONTEND_README.md`](./FRONTEND_README.md)
- [ ] Explorar el código en `/app/`
- [ ] Hacer login y probar el sistema
- [ ] Crear un cliente de prueba
- [ ] Crear una orden de servicio

### Para integración con frontend:

- [ ] Leer [`FRONTEND_README.md`](./FRONTEND_README.md)
- [ ] Abrir `api-documentation.html` en el navegador
- [ ] Probar el login con fetch/axios
- [ ] Obtener estadísticas del dashboard
- [ ] Buscar un cliente
- [ ] Crear un cliente de prueba
- [ ] Manejar errores correctamente
- [ ] Configurar CORS si es necesario

---

**¡Bienvenido al equipo! 🚀**

Si tienes dudas, consulta esta documentación o contacta al equipo.
