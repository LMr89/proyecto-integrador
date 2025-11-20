# 🏗️ Arquitectura del Sistema - Seven Service

## Visión General

Seven Service es un sistema monolítico basado en el patrón **MVC (Modelo-Vista-Controlador)** desarrollado en **PHP Vanilla** (sin frameworks) con una arquitectura limpia y escalable.

---

## 📐 Principios de Diseño

### 1. Separación de Responsabilidades (SoC)
Cada componente tiene una única responsabilidad:

- **Modelos**: Lógica de datos y acceso a BD
- **Vistas**: Presentación y UI
- **Controladores**: Lógica de negocio y coordinación

### 2. DRY (Don't Repeat Yourself)
Código reutilizable mediante:
- Clase `Model` base
- Clase `Controller` base
- Helpers compartidos
- Layouts comunes

### 3. SOLID Principles

#### S - Single Responsibility
Cada clase tiene una única razón para cambiar.
```php
// ✅ Cliente.php solo maneja datos de clientes
class Cliente extends Model {
    protected string $table = 'clientes';
}
```

#### O - Open/Closed
Abierto para extensión, cerrado para modificación.
```php
// Extiendes Model sin modificarlo
class Producto extends Model {
    // Métodos específicos de productos
}
```

#### L - Liskov Substitution
Las clases derivadas son intercambiables.
```php
// Cualquier Model puede usar los mismos métodos
$cliente = new Cliente();
$producto = new Producto();

$cliente->findAll(); // ✅
$producto->findAll(); // ✅
```

#### D - Dependency Inversion
Depende de abstracciones, no de implementaciones concretas.
```php
// El controlador depende del Model (abstracción)
// no de la implementación específica de MySQL
class ClienteController extends Controller {
    private Cliente $clienteModel;
}
```

---

## 🧱 Capas de la Arquitectura

```
┌─────────────────────────────────────────────┐
│         CAPA DE PRESENTACIÓN                │
│  (Views - HTML/CSS/JS con Tailwind)         │
├─────────────────────────────────────────────┤
│         CAPA DE APLICACIÓN                  │
│  (Controllers - Lógica de Negocio)          │
├─────────────────────────────────────────────┤
│         CAPA DE DOMINIO                     │
│  (Models - Lógica de Datos)                 │
├─────────────────────────────────────────────┤
│         CAPA DE INFRAESTRUCTURA             │
│  (Database - Acceso a BD)                   │
└─────────────────────────────────────────────┘
```

### 1. Capa de Presentación (Views)

**Responsabilidad:** Renderizar HTML y manejar la interacción del usuario.

**Componentes:**
- Layouts (header, footer, sidebar)
- Vistas específicas por módulo
- JavaScript para interactividad (Alpine.js)
- Estilos (Tailwind CSS)

**Ejemplo:**
```php
// app/Views/clientes/index.php
<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container">
    <h1>Clientes</h1>
    <?php foreach ($clientes as $cliente): ?>
        <div><?= htmlspecialchars($cliente['nombre']) ?></div>
    <?php endforeach; ?>
</div>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
```

### 2. Capa de Aplicación (Controllers)

**Responsabilidad:** Coordinar flujos de trabajo y aplicar lógica de negocio.

**Componentes:**
- AuthController: Autenticación
- DashboardController: Estadísticas
- ClienteController: Gestión de clientes
- OrdenController: Órdenes de servicio
- InventarioController: Gestión de stock

**Flujo típico:**
```php
class ClienteController extends Controller {
    public function index() {
        // 1. Obtener datos del modelo
        $clientes = $this->clienteModel->getActiveClientes();
        
        // 2. Procesamiento (si es necesario)
        $clientes = array_map(function($cliente) {
            $cliente['nombre_upper'] = strtoupper($cliente['nombre']);
            return $cliente;
        }, $clientes);
        
        // 3. Pasar a la vista
        $this->view('clientes/index', ['clientes' => $clientes]);
    }
}
```

### 3. Capa de Dominio (Models)

**Responsabilidad:** Representar entidades de negocio y lógica de datos.

**Patrón:** Active Record simplificado

**Ejemplo:**
```php
class Cliente extends Model {
    protected string $table = 'clientes';
    protected string $primaryKey = 'id_cliente';
    
    // Métodos de negocio específicos
    public function getHistorial(int $id): array {
        // Lógica compleja de consulta
    }
}
```

### 4. Capa de Infraestructura (Database)

**Responsabilidad:** Conexión y comunicación con la base de datos.

**Patrón:** Singleton

**Características:**
- Una sola conexión por request
- Prepared statements (seguridad)
- Manejo de transacciones
- Logging de consultas

---

## 🔄 Flujo de una Petición HTTP

```
1. Usuario: http://localhost/clientes/crear
   ↓
2. Apache: Recibe petición
   ↓
3. .htaccess: Redirige a public/index.php
   ↓
4. index.php (Front Controller):
   - Carga config.php
   - Inicializa autoloader
   - Carga routes.php
   ↓
5. Router:
   - Analiza la URL
   - Encuentra: ClienteController@create
   - Aplica middleware: AuthMiddleware
   ↓
6. AuthMiddleware:
   - Verifica sesión activa
   - Si no → redirige a login
   - Si sí → continúa
   ↓
7. ClienteController:
   - Ejecuta método create()
   - Prepara datos para la vista
   ↓
8. View (clientes/create.php):
   - Renderiza formulario HTML
   ↓
9. Navegador:
   - Muestra formulario al usuario
```

---

## 🔐 Sistema de Seguridad

### 1. Autenticación

**Proceso de Login:**
```php
Usuario → POST /login
       ↓
AuthController@login
       ↓
Usuario->authenticate(correo, password)
       ↓
password_verify() ← Compara hash
       ↓
Crear sesión $_SESSION['usuario_id']
       ↓
Redirigir a /dashboard
```

### 2. Autorización

**Middleware de Roles:**
```php
// config/routes.php
$router->get('/usuarios', 'UsuarioController@index', [
    'AuthMiddleware',    // Debe estar logueado
    'RoleMiddleware'     // Debe ser admin
]);
```

### 3. Protección contra SQL Injection

**Uso de Prepared Statements:**
```php
// ❌ VULNERABLE
$query = "SELECT * FROM usuarios WHERE correo = '$correo'";

// ✅ SEGURO
$query = "SELECT * FROM usuarios WHERE correo = :correo";
$stmt = $pdo->prepare($query);
$stmt->execute([':correo' => $correo]);
```

### 4. Protección XSS

**Sanitización de salida:**
```php
// En las vistas
<?= htmlspecialchars($data, ENT_QUOTES, 'UTF-8') ?>

// O en el controlador
$data = $this->sanitize($_POST);
```

### 5. CSRF Protection

**Implementación recomendada:**
```php
// Generar token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// En formularios
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

// Validar en POST
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Token inválido');
}
```

---

## 💾 Gestión de Datos

### Modelo de Base de Datos

**Entidades Principales:**
- **usuarios**: Personal del taller
- **clientes**: Clientes del negocio
- **bicicletas**: Bicicletas de los clientes
- **productos**: Inventario de repuestos
- **ordenes_servicio**: Trabajos de reparación
- **detalle_orden**: Productos usados en órdenes
- **pagos**: Pagos realizados

**Relaciones:**
```sql
clientes (1) ──→ (N) bicicletas
clientes (1) ──→ (N) ordenes_servicio
bicicletas (1) ─→ (N) ordenes_servicio
ordenes_servicio (1) ─→ (N) detalle_orden
productos (1) ──→ (N) detalle_orden
```

### Triggers Automáticos

**1. Actualización de Stock:**
```sql
-- Cuando se agrega un producto a una orden
CREATE TRIGGER after_detalle_orden_insert
AFTER INSERT ON detalle_orden
FOR EACH ROW
BEGIN
    UPDATE productos 
    SET stock_actual = stock_actual - NEW.cantidad
    WHERE id_producto = NEW.id_producto;
END;
```

**2. Cálculo Automático de Totales:**
```sql
-- Cuando cambian los productos de una orden
CREATE TRIGGER after_detalle_orden_insert_update_total
AFTER INSERT ON detalle_orden
FOR EACH ROW
BEGIN
    UPDATE ordenes_servicio
    SET costo_total = costo_mano_obra + 
        (SELECT SUM(subtotal) FROM detalle_orden WHERE id_orden = NEW.id_orden)
    WHERE id_orden = NEW.id_orden;
END;
```

### Transacciones

**Uso en operaciones críticas:**
```php
try {
    $this->beginTransaction();
    
    // Crear orden
    $idOrden = $this->ordenModel->create($dataOrden);
    
    // Agregar productos
    foreach ($productos as $producto) {
        $this->detalleModel->create([
            'id_orden' => $idOrden,
            'id_producto' => $producto['id'],
            'cantidad' => $producto['cantidad']
        ]);
    }
    
    $this->commit();
} catch (Exception $e) {
    $this->rollback();
    throw $e;
}
```

---

## 🚀 Optimizaciones

### 1. Indexación de Base de Datos

```sql
-- Índices en columnas frecuentemente consultadas
CREATE INDEX idx_clientes_nombre ON clientes(nombre);
CREATE INDEX idx_productos_sku ON productos(sku);
CREATE INDEX idx_ordenes_fecha ON ordenes_servicio(fecha_creacion);
CREATE INDEX idx_ordenes_estado ON ordenes_servicio(estado);
```

### 2. Vistas Materializadas

```sql
-- Vista precalculada para stock bajo
CREATE VIEW vista_productos_stock_bajo AS
SELECT p.*, c.nombre as categoria
FROM productos p
JOIN categorias c ON p.id_categoria = c.id_categoria
WHERE p.stock_actual <= p.stock_minimo;
```

### 3. Caché de Sesión

```php
// Cachear datos de usuario en sesión
$_SESSION['usuario_cache'] = [
    'permisos' => $usuario->getPermisos(),
    'configuracion' => $usuario->getConfiguracion()
];
```

### 4. Lazy Loading

```php
// Cargar relaciones solo cuando se necesitan
public function getBicicletas(int $idCliente) {
    // Solo se ejecuta si se llama explícitamente
    return $this->db->query(
        "SELECT * FROM bicicletas WHERE id_cliente = :id",
        [':id' => $idCliente]
    );
}
```

---

## 📊 Monitoreo y Logs

### Sistema de Logging

```php
// app/Core/Database.php
private function log(string $message, string $level = 'INFO'): void {
    $date = date('Y-m-d H:i:s');
    $logMessage = "[{$date}] [{$level}] {$message}" . PHP_EOL;
    file_put_contents(LOGS_PATH . '/database.log', $logMessage, FILE_APPEND);
}
```

**Tipos de Logs:**
- `storage/logs/database.log`: Consultas y errores de BD
- `storage/logs/php_errors.log`: Errores de PHP
- `storage/logs/app.log`: Eventos de la aplicación

---

## 🔮 Escalabilidad Futura

### Posibles Mejoras

1. **Migración a Composer**
   ```bash
   composer init
   composer require vlucas/phpdotenv
   ```

2. **Sistema de Cache (Redis)**
   ```php
   $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   $redis->set('productos_stock_bajo', json_encode($productos), 300);
   ```

3. **API RESTful con JWT**
   ```php
   use Firebase\JWT\JWT;
   
   $token = JWT::encode($payload, $secret, 'HS256');
   ```

4. **Queue System para Tareas Asíncronas**
   ```php
   // Enviar emails en background
   Queue::push('SendEmailJob', ['email' => $cliente['email']]);
   ```

5. **Microservicios**
   - Servicio de Inventario
   - Servicio de Facturación
   - Servicio de Notificaciones

---

## 📚 Referencias y Recursos

- [PHP Best Practices](https://www.phptherightway.com/)
- [MVC Pattern Explained](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
- [PDO Tutorial](https://phpdelusions.net/pdo)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)

---

**Documento actualizado:** Octubre 2025
**Versión del Sistema:** 1.0.0
