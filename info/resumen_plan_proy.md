# 02-Resumen_Plan_Desarrollo: Alcance y Requerimientos

## 1. Alcance del Proyecto (MVP)

**Objetivo**: Implementar un Sistema Web de Gestión de Inventario y Servicios (Monolito MVC) para el taller "Seven Service".

### ✅ Dentro del Alcance

| Módulo | Descripción |
|--------|-------------|
| **Autenticación** | Login seguro y restricción de acceso por rol (admin, tecnico, encargado_almacen) |
| **Clientes** | Mantenimiento (CRUD) y visualización de historial de servicios |
| **Inventario** | Mantenimiento (CRUD) de productos y categorías. Descuento automático de stock al usar piezas en orden |
| **Órdenes de Servicio** | Creación de órdenes (vinculadas a cliente/bicicleta), gestión de estados, asignación de repuestos y cálculo de costos |
| **Reportes** | Dashboard, reportes de ventas, rotación de inventario y alertas de stock bajo |
| **Interfaz Móvil** | Vistas reactivas (Tailwind CSS) optimizadas para uso en celular por técnicos |
| **Seguimiento Público** | Permite a clientes consultar estado de orden mediante código único |

### ❌ Fuera del Alcance (Restricciones)

- Facturación Electrónica (ej. SUNAT)
- Plataforma de E-commerce (solo gestión interna)
- Módulo de Compras a Proveedores (solo registro del proveedor)
- Aplicación Móvil Nativa (solo App Web)
- Integración con hardware especializado (lectores de códigos de barras)
- Contabilidad Avanzada

## 2. Requerimientos Funcionales Clave (RF)

| Código | Requerimiento | Descripción |
|--------|---------------|-------------|
| **RF-01** | Registro de Usuarios | Sistema de autenticación con roles (admin, tecnico, encargado_almacen) |
| **RF-02** | Gestión de Clientes | Mantenimiento completo de clientes y su historial |
| **RF-03** | Gestión de Inventario | CRUD de productos (nombre, SKU, precio, stock, etc.) |
| **RF-04** | Control de Stock Automático | Al usar pieza en servicio, stock se actualiza automáticamente **(CRÍTICO)** |
| **RF-05** | Registro de Órdenes | Crear orden con cliente, bicicleta, descripción del problema y fecha estimada |
| **RF-06** | Asignación de Repuestos | Vincular productos del inventario a una orden de servicio |
| **RF-07** | Cálculo de Costo | Sumar mano de obra + repuestos usados (precio congelado de la orden) |
| **RF-09** | Generación de Reportes | Reportes de ventas, inventario (bajas existencias, más usados) con filtros de fecha |
| **RF-10** | Búsqueda y Filtros | Buscar productos, clientes u órdenes por criterios |

## 3. Experiencia de Usuario por Rol (Vistas Solicitadas)

### A. Técnico (Foco: Agilidad Móvil)

| Vista | Propósito | Características Clave |
|-------|-----------|----------------------|
| **Login** | Acceso seguro | Interfaz limpia, responsive |
| **Nueva Orden (Paso 1/2/3)** | Formulario de registro rápido de servicio | Multi-paso (gestionado con JS), listas desplegables para Clientes, Bicicletas y Repuestos |
| **Inventario (Consulta)** | Verificación rápida de disponibilidad | Solo vista, búsqueda por SKU/Nombre, muestra stock_actual |
| **Historial Cliente** | Consulta de servicios anteriores | Acceso rápido al historial vinculado al perfil del cliente |

### B. Administrador (Foco: Control y Análisis)

| Vista | Propósito | Características Clave |
|-------|-----------|----------------------|
| **Dashboard** | Visión general del negocio | KPIs de ventas, órdenes registradas, alertas de stock bajo (vista_productos_stock_bajo) |
| **Inventario & Proveedores** | Gestión total de recursos | Tablas CRUD completas para productos, categorias y proveedores |
| **Usuarios** | Gestión de perfiles | CRUD de usuarios con asignación de roles (admin, tecnico, almacen) |

### C. Cliente (Foco: Transparencia)

| Vista | Propósito | Características Clave |
|-------|-----------|----------------------|
| **Seguimiento Público** | Verificar estado de reparación | Formulario de entrada de código único (ID de Orden). Muestra estado actual |

