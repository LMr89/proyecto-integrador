# 🚴 Arquitectura: Portal del Cliente - Seguimiento de Órdenes

## 📊 Análisis de la Situación Actual

### Base de Datos Existente ✅

Tu BD ya está **PERFECTAMENTE diseñada** para esta funcionalidad:

**Tabla: `ordenes_servicio`**
- Campo `estado` (ENUM): 
  - ✅ 'Pendiente'
  - ✅ 'En Diagnostico'
  - ✅ 'Esperando Aprobacion'
  - ✅ 'En Reparacion'
  - ✅ 'Listo para Entrega'
  - ✅ 'Entregado'
  - ✅ 'Cancelado'

**Tabla: `historial_estados_orden`** ✅
- Registra automáticamente cada cambio de estado
- Incluye: fecha_cambio, estado_anterior, estado_nuevo, usuario que hizo el cambio, comentarios

**Triggers Automáticos** ✅
- `before_orden_entregada`: Registra fecha_finalizacion cuando estado = 'Entregado'

---

## 🎯 Propuesta de Implementación

### 1️⃣ **SISTEMA DE AUTENTICACIÓN DUAL**

#### Opción A: Portal con Token/Código (⭐ RECOMENDADO)
**Sin necesidad de crear usuarios para clientes**

```
┌─────────────────────────────────────────┐
│  Cliente recibe SMS/Email con código   │
│  Código: ABC123 (único por orden)      │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  Portal: /seguimiento                   │
│  Input: Código de seguimiento          │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  Muestra estado de SU orden            │
│  + Historial de cambios                │
└─────────────────────────────────────────┘
```

**Ventajas:**
- ✅ Sin registro de clientes
- ✅ Acceso inmediato
- ✅ Un código = una orden específica
- ✅ No requiere contraseñas

**Implementación en BD:**
```sql
-- Agregar columna a ordenes_servicio
ALTER TABLE ordenes_servicio 
ADD COLUMN codigo_seguimiento VARCHAR(10) UNIQUE AFTER id_orden;

-- Crear índice
CREATE INDEX idx_codigo_seguimiento ON ordenes_servicio(codigo_seguimiento);
```

---

#### Opción B: Sistema de Login Completo
**Con registro de usuarios clientes**

```
┌─────────────────────────────────────────┐
│  Cliente se registra con email         │
│  Vincular email → id_cliente           │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  Portal Cliente: /cliente/login        │
│  Login: email + contraseña             │
└─────────────────────────────────────────┘
              ↓
┌─────────────────────────────────────────┐
│  Dashboard: Todas sus órdenes          │
│  + Bicicletas registradas              │
└─────────────────────────────────────────┘
```

**Ventajas:**
- ✅ Cliente ve todas sus órdenes
- ✅ Historial completo
- ✅ Gestión de múltiples bicicletas

**Implementación en BD:**
```sql
-- Agregar columnas a tabla clientes
ALTER TABLE clientes 
ADD COLUMN email_verificado TINYINT(1) DEFAULT 0,
ADD COLUMN password_hash VARCHAR(255) DEFAULT NULL,
ADD COLUMN ultimo_acceso TIMESTAMP NULL,
ADD COLUMN token_verificacion VARCHAR(64) UNIQUE;
```

---

### 2️⃣ **GESTIÓN DE ESTADOS (Técnico)**

El técnico ya tiene la infraestructura necesaria. Solo falta la interfaz.

**Vista: `ordenes/show.php` (para técnicos)**

```php
┌──────────────────────────────────────────────────┐
│  ORDEN #123 - Trek Mountain Bike X500          │
│  Cliente: Juan Pérez | Tel: 987654321          │
├──────────────────────────────────────────────────┤
│  📍 Estado Actual: En Reparación                │
│                                                  │
│  ⬇️ Cambiar Estado:                             │
│  [ Dropdown con estados siguientes ]           │
│  [ Textarea: Comentarios del cambio ]          │
│  [Botón: Actualizar Estado]                    │
├──────────────────────────────────────────────────┤
│  📜 Historial de Estados                        │
│  • 02/11/2024 10:30 - Pendiente → En Diagnóstico│
│    Por: Juan Técnico                            │
│  • 02/11/2024 14:15 - En Diagnóstico → En Rep. │
│    Por: Juan Técnico                            │
│    "Se encontró cadena oxidada, cambio necesario"│
└──────────────────────────────────────────────────┘
```

**Flujo de Estados Recomendado:**

```
Pendiente 
   ↓
En Diagnostico (técnico evalúa)
   ↓
Esperando Aprobacion (si requiere_aprobacion = 1)
   ↓
En Reparacion (trabajo en proceso)
   ↓
Listo para Entrega (trabajo terminado)
   ↓
Entregado (cliente recoge)

(Cancelado puede aplicarse en cualquier momento)
```

---

### 3️⃣ **PORTAL DEL CLIENTE (Vista Pública)**

**Ruta: `/seguimiento` o `/track`**

```php
┌──────────────────────────────────────────────────┐
│  🔍 Seguimiento de Orden                        │
│  ┌────────────────────────────────────┐         │
│  │ Ingresa tu código: [________]      │         │
│  │              [Buscar]              │         │
│  └────────────────────────────────────┘         │
└──────────────────────────────────────────────────┘
              ↓ (ingresa código ABC123)
┌──────────────────────────────────────────────────┐
│  📋 ORDEN #00123                                │
│  Estado: 🔧 En Reparación                       │
├──────────────────────────────────────────────────┤
│  🚲 Bicicleta: Trek Mountain Bike X500         │
│  📅 Fecha Ingreso: 01/11/2024                   │
│  📆 Fecha Estimada: 05/11/2024                  │
│  👤 Técnico Asignado: Juan Rodríguez            │
├──────────────────────────────────────────────────┤
│  📝 Problema Reportado:                         │
│  "Frenos delanteros no responden..."           │
│                                                  │
│  🔬 Diagnóstico Técnico:                        │
│  "Pastillas desgastadas al 80%..."             │
├──────────────────────────────────────────────────┤
│  📊 Progreso de la Reparación                   │
│  ✅ Recibido                                    │
│  ✅ Diagnosticado                               │
│  🔄 En Reparación (actual)                      │
│  ⏳ Listo para Entrega                          │
│  ⏳ Entregado                                    │
├──────────────────────────────────────────────────┤
│  💰 Costo Total: S/ 150.00                      │
│  (Pendiente de pago)                            │
└──────────────────────────────────────────────────┘
```

---

## 🛠️ Implementación Técnica

### Archivos a Crear:

#### 1. **Controlador: `SeguimientoController.php`**
```php
<?php
namespace App\Controllers;

class SeguimientoController extends Controller
{
    // Portal público
    public function index() // Formulario de búsqueda
    public function buscar() // Procesa código
    public function ver($codigo) // Muestra orden
    
    // API para actualizaciones en tiempo real
    public function apiEstado($codigo) // JSON del estado actual
}
```

#### 2. **Vista: `seguimiento/index.php`** (Portal público)
- Formulario de búsqueda por código
- Sin autenticación requerida

#### 3. **Vista: `seguimiento/ver.php`**
- Timeline visual del progreso
- Información completa de la orden
- Actualización automática (AJAX cada 30 seg)

#### 4. **Métodos en `OrdenServicio.php`**
```php
public function getByCodigoSeguimiento($codigo)
public function generarCodigoSeguimiento() // ABC123 único
public function getEstadoPublico($codigo) // Info para clientes
```

#### 5. **Vista Técnico: `ordenes/show.php`**
- Panel de cambio de estado
- Formulario con dropdown + comentarios
- Historial completo

#### 6. **Método en `OrdenController.php`**
```php
public function cambiarEstado($id) // Procesa cambio de estado
```

---

## 🎨 UI/UX Recomendada

### Timeline Visual (CSS puro):
```
     O ← Recibido (✅ Completado)
     |
     O ← Diagnosticado (✅ Completado)
     |
     ⦿ ← En Reparación (🔄 ACTUAL)
     |
     O ← Listo para Entrega (⏳ Pendiente)
     |
     O ← Entregado (⏳ Pendiente)
```

### Notificaciones Automáticas:
- Cuando el estado cambia, enviar:
  - 📧 Email al cliente
  - 📱 SMS (opcional, requiere API)
  - 🔔 Notificación push (avanzado)

---

## 🔐 Seguridad

### Portal de Seguimiento Público:
1. ✅ Código de 8-10 caracteres alfanuméricos
2. ✅ No mostrar información sensible (teléfono completo, dirección)
3. ✅ Rate limiting (max 5 búsquedas por minuto por IP)
4. ✅ Código único e irrepetible

### Portal Técnico:
1. ✅ Requiere autenticación
2. ✅ Solo técnicos asignados pueden cambiar estado
3. ✅ Registrar quién hizo cada cambio (ya implementado)

---

## 📝 Cambios en Base de Datos

### OPCIÓN A (Sistema de Códigos) - Mínimo cambio:
```sql
ALTER TABLE ordenes_servicio 
ADD COLUMN codigo_seguimiento VARCHAR(10) UNIQUE AFTER id_orden,
ADD INDEX idx_codigo_seguimiento (codigo_seguimiento);
```

### OPCIÓN B (Login de Clientes) - Más completo:
```sql
ALTER TABLE clientes 
ADD COLUMN password_hash VARCHAR(255) DEFAULT NULL,
ADD COLUMN email_verificado TINYINT(1) DEFAULT 0,
ADD COLUMN token_verificacion VARCHAR(64) UNIQUE,
ADD COLUMN ultimo_acceso TIMESTAMP NULL;
```

---

## 🚀 Plan de Implementación Sugerido

### FASE 1 (Rápido - 1-2 horas):
1. ✅ Agregar columna `codigo_seguimiento` a BD
2. ✅ Crear método para generar códigos
3. ✅ Modificar `OrdenController::store()` para generar código automáticamente
4. ✅ Crear vista pública simple de seguimiento

### FASE 2 (Medio - 2-3 horas):
1. ✅ Vista `ordenes/show.php` para técnicos
2. ✅ Método `cambiarEstado()` en controlador
3. ✅ Timeline visual en portal público
4. ✅ AJAX para actualización automática

### FASE 3 (Avanzado - futuro):
1. ⏳ Sistema de notificaciones por email
2. ⏳ Login opcional para clientes
3. ⏳ SMS con código de seguimiento
4. ⏳ App móvil

---

## 🎯 Recomendación Final

**Para tu caso, recomiendo OPCIÓN A** (Sistema de Códigos):

✅ **Por qué:**
- Implementación rápida
- Sin complejidad de gestión de usuarios
- Cliente accede fácilmente
- Ideal para talleres pequeños/medianos
- Puedes escalar a Opción B después

✅ **Flujo perfecto:**
1. Técnico crea orden → Sistema genera código ABC123
2. Técnico imprime ticket con código QR
3. Cliente escanea QR o ingresa código en web
4. Ve estado en tiempo real
5. Técnico actualiza estado desde su panel

---

## 📞 Próximos Pasos

¿Quieres que implemente alguna de estas opciones? 

Te recomiendo empezar con:
1. **Sistema de códigos de seguimiento**
2. **Vista de cambio de estado para técnicos**
3. **Portal público de consulta**

¿Te parece bien este enfoque? 🚴‍♂️
