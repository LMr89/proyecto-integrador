# 01-Resumen_Empresa: Seven Service

## 1. Identificación del Negocio

| Atributo | Detalle |
|----------|---------|
| **Nombre** | Seven Service |
| **Rubro** | Taller de Bicicletas |
| **Actividades** | 1. **Servicios**: Mantenimiento preventivo y reparación de bicicletas.<br>2. **Venta Minorista**: Comercialización de repuestos, componentes y accesorios. |
| **Problema Principal** | Pérdida de tiempo, errores en registros manuales (papel y Excel), falta de control de inventario en tiempo real, y dificultad para generar reportes. |
| **Misión** | Ofrecer servicios expertos de reparación y mantenimiento de bicicletas con alta calidad y atención personalizada, asegurando la máxima seguridad y confianza al ciclista. |
| **Visión** | Consolidarse como el taller de bicicletas de mayor confianza en la comunidad, reconocido por su excelencia técnica, transparencia y eficiencia en sus procesos de gestión. |


## 👥 2. Recursos Humanos y Roles

El sistema debe soportar una estructura de roles que refleje el personal del taller:

| Puesto | Cantidad | Rol en el Sistema | Permisos |
|--------|----------|-------------------|----------|
| **Administrador** | 1 | `Admin` | Control total: Dashboard, CRUDs de todo el sistema, gestión de usuarios y reportes |
| **Técnicos** | 4 | `Tecnico` | Foco operativo: Registro de órdenes, asignación de repuestos, atención al cliente y consulta de historial |
| **Encargado de Almacén** | 1 | `Encargado Almacen` | Foco logístico: CRUD completo de Inventario y Proveedores, gestión de stock y alertas |
| **Recepción** | 1 | _(Compartido)_ | Sus tareas se reparten entre Técnico y Admin en el sistema |


## 💻 3. Entorno Tecnológico Actual

**El proyecto busca reemplazar:**
- ❌ Registros manuales en papel
- ❌ Hojas de cálculo Excel desactualizadas

## 🔄 4. Flujo de Negocio Simplificado

El proceso central que el sistema debe automatizar es la **Orden de Servicio**:

### Proceso de Atención

1. **📝 Recepción**
   - El cliente solicita el servicio
   - El Técnico crea una orden
   - Registra/busca al cliente y la bicicleta

2. **🔍 Diagnóstico**
   - El Técnico identifica el problema
   - Asigna repuestos del inventario

3. **🔧 Reparación y Stock**
   - Se realiza la reparación
   - El sistema descuenta automáticamente las piezas usadas del stock

4. **💰 Cierre y Costo**
   - El sistema calcula el costo total (mano de obra + repuestos usados)
   - Se genera un código único para seguimiento

5. **✅ Entrega y Facturación**
   - El cliente retira y paga
   - Se registra el pago en el sistema

6. **📊 Análisis**
   - El Administrador genera reportes de ventas
   - Análisis de rotación de inventario


## 🛠️ 5. Requerimientos Tecnológicos (Stack Elegido)

| Capa | Tecnología | Descripción |
|------|------------|-------------|
| **Backend** | PHP (Vanilla) | Arquitectura MVC |
| **Base de Datos** | MySQL | Acceso vía túnel SSH en puerto 5060 |
| **Control de BD** | DBeaver | Gestión y administración de la base de datos |
| **Frontend** | HTML, CSS, JavaScript | Tailwind CSS para estilos, JS para reactividad |
| **Entorno de Desarrollo** | VSCode | Editor principal de código |
| **Control de Versiones** | Git + GitHub | Conventional Commits |
| **Hosting** | Servidor Remoto | A través de túnel SSH |

---

## 📌 Resumen Ejecutivo

Seven Service es un taller de bicicletas que busca **digitalizar y optimizar** sus procesos de gestión mediante un sistema web desarrollado con tecnologías modernas. El objetivo principal es eliminar los errores manuales, mejorar el control de inventario y facilitar la generación de reportes para una mejor toma de decisiones.

---
