-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: taller_bicicletas
-- ------------------------------------------------------
-- Server version	8.4.6

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bicicletas`
--

DROP TABLE IF EXISTS `bicicletas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bicicletas` (
  `id_bicicleta` int unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int unsigned NOT NULL,
  `marca` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `modelo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero_serie` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `año_fabricacion` year DEFAULT NULL,
  `foto_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_general_ci,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bicicleta`),
  UNIQUE KEY `numero_serie` (`numero_serie`),
  KEY `idx_cliente` (`id_cliente`),
  KEY `idx_serie` (`numero_serie`),
  CONSTRAINT `bicicletas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registra cada bicicleta para un historial de servicio preciso.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bicicletas`
--

LOCK TABLES `bicicletas` WRITE;
/*!40000 ALTER TABLE `bicicletas` DISABLE KEYS */;
INSERT INTO `bicicletas` VALUES (3,2,'Specialized','Rockhopper Sport','Montaña','Gris Oscuro','RHSP23B1001CM',2023,'https://ejemplo.com/fotos/bici_carlos.jpg','Requiere ajuste de cambios.','2025-10-25 09:00:00'),(4,3,'Trek','Domane AL 2','Ruta','Rojo Vibrante','DMAL24R2002LT',2024,'https://ejemplo.com/fotos/bici_lucia1.jpg','Se usa para entrenamiento diario.','2025-10-25 09:15:00'),(5,4,'Giant','Contend AR 3','Ruta','Azul Eléctrico','CTAR22A3003FR',2022,'https://ejemplo.com/fotos/bici_fernando.jpg','Ruedas de 28mm.','2025-10-25 09:30:00'),(6,5,'Orbea','Diem 40','Urbana','Blanco Perla','DIM4023D4004GP',2023,'https://ejemplo.com/fotos/bici_gabriela.jpg','Revisión de motor de asistencia.','2025-10-25 09:45:00'),(7,6,'Cube','Town Pro','Urbana','Negro Mate','TWNPR24H5005LC',2024,'https://ejemplo.com/fotos/bici_luis.jpg','Equipada con luces y parrilla.','2025-10-25 10:00:00'),(8,2,'Cannondale','Trail SE 4','Montaña','Verde Militar','TRSE21C6006CM',2021,'https://ejemplo.com/fotos/bici_carlos2.jpg','Para uso rudo.','2025-10-25 10:15:00'),(9,3,'Scott','Speedster 50','Ruta','Negro/Gris','SPED22S7007LT',2022,'https://ejemplo.com/fotos/bici_lucia2.jpg','Se usa como bici de repuesto.','2025-10-25 10:30:00'),(10,4,'Merida','Big.Nine 40','Montaña','Marrón Tierra','BGN923M8008FR',2023,'https://ejemplo.com/fotos/bici_fernando2.jpg','Neumáticos de tacos.','2025-10-25 10:45:00'),(11,5,'Trek','FX 1 Disc','Urbana','Azul Claro','FXD124B9009GP',2024,'https://ejemplo.com/fotos/bici_gabriela2.jpg',NULL,'2025-10-25 11:00:00'),(12,6,'Haro','Freestyler','BMX','Cromo','HFS20B0010LC',2020,'https://ejemplo.com/fotos/bici_luis2.jpg','Bicicleta para trucos y uso recreativo.','2025-10-25 11:15:00');
/*!40000 ALTER TABLE `bicicletas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id_categoria` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Organiza los productos en grupos para facilitar la gestión.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Llantas y Neumáticos','Llantas, neumáticos, cámaras y accesorios',1),(2,'Frenos','Sistemas de frenos, pastillas, cables y líquidos',1),(3,'Transmisión','Cadenas, piñones, platos, desviadores y cambios',1),(4,'Suspensión','Horquillas, amortiguadores y componentes',1),(5,'Componentes','Manubrios, potencias, tijas, sillines',1),(6,'Iluminación','Luces delanteras, traseras y accesorios',1),(7,'Herramientas','Herramientas para mantenimiento y reparación',1),(8,'Accesorios','Portabidones, bolsos, guardabarros, espejos',1),(9,'Lubricantes','Aceites, grasas y productos de limpieza',1),(10,'Electrónica','Ciclocomputadores, sensores y componentes eléctricos',1);
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `citas`
--

DROP TABLE IF EXISTS `citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `citas` (
  `id_cita` int unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int unsigned NOT NULL,
  `id_bicicleta` int unsigned DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `id_usuario_asignado` int unsigned DEFAULT NULL,
  `motivo` text COLLATE utf8mb4_general_ci NOT NULL,
  `estado` enum('Agendada','Confirmada','En Proceso','Completada','Cancelada') COLLATE utf8mb4_general_ci DEFAULT 'Agendada',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `notas` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_cita`),
  KEY `id_bicicleta` (`id_bicicleta`),
  KEY `id_usuario_asignado` (`id_usuario_asignado`),
  KEY `idx_fecha` (`fecha_hora`),
  KEY `idx_estado` (`estado`),
  KEY `idx_cliente` (`id_cliente`),
  CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE RESTRICT,
  CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_bicicleta`) REFERENCES `bicicletas` (`id_bicicleta`) ON DELETE SET NULL,
  CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_usuario_asignado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Gestiona las citas agendadas para el taller.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citas`
--

LOCK TABLES `citas` WRITE;
/*!40000 ALTER TABLE `citas` DISABLE KEYS */;
/*!40000 ALTER TABLE `citas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id_cliente` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `contacto_telefono` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `contacto_email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_general_ci,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `notas` text COLLATE utf8mb4_general_ci,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_cliente`),
  KEY `idx_telefono` (`contacto_telefono`),
  KEY `idx_email` (`contacto_email`),
  KEY `idx_nombre` (`nombre`),
  KEY `idx_clientes_activo` (`activo`,`fecha_registro`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Centraliza la información de cada cliente del negocio.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (2,'Carlos Mendoza','999123456','carlos.mendoza@example.com','Av. Los Alisos 234, Lima','2025-10-24 05:05:18','Cliente frecuente, solicita mantenimiento mensual.',1),(3,'Lucía Torres','987654321','lucia.torres@example.com','Jr. San Martín 112, Arequipa','2025-10-24 05:05:18','Solicita repuestos originales.',1),(4,'Fernando Rojas','912334455','fernando.rojas@example.com','Calle Los Nogales 45, Cusco','2025-10-24 05:05:18','Atendido el 15 de julio, bicicleta de ruta.',1),(5,'Gabriela Pérez','956789012','gabriela.perez@example.com','Av. La Marina 870, Lima','2025-10-24 05:05:18','Reparación de frenos hidráulicos.',1),(6,'Luis Castro','945678901','luis.castro@example.com','Calle Unión 678, Trujillo','2025-10-24 05:05:18','Compra de accesorios y casco.',1);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion` (
  `clave` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo_dato` enum('texto','numero','booleano','fecha') COLLATE utf8mb4_general_ci DEFAULT 'texto',
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Almacena parámetros globales del sistema.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion`
--

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
INSERT INTO `configuracion` VALUES ('direccion','','Dirección física del negocio','texto'),('email','','Email del negocio','texto'),('garantia_dias_defecto','30','Días de garantía por defecto','numero'),('horario_apertura','09:00','Hora de apertura del taller','texto'),('horario_cierre','18:00','Hora de cierre del taller','texto'),('igv_porcentaje','18','Porcentaje de IGV','numero'),('margen_ganancia_defecto','30','Porcentaje de margen de ganancia por defecto','numero'),('moneda','PEN','Moneda local (PEN, USD)','texto'),('nombre_negocio','Taller de Bicicletas','Nombre del taller','texto'),('notificaciones_activas','true','Activar sistema de notificaciones','booleano'),('ruc','','RUC del negocio','texto'),('telefono','','Teléfono de contacto principal','texto'),('tiempo_estimado_diagnostico','24','Horas estimadas para diagnóstico','numero');
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_orden`
--

DROP TABLE IF EXISTS `detalle_orden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_orden` (
  `id_detalle` int unsigned NOT NULL AUTO_INCREMENT,
  `id_orden` int unsigned NOT NULL,
  `id_producto` int unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario_congelado` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle`),
  UNIQUE KEY `unique_orden_producto` (`id_orden`,`id_producto`),
  KEY `id_producto` (`id_producto`),
  KEY `idx_orden` (`id_orden`),
  CONSTRAINT `detalle_orden_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_servicio` (`id_orden`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_orden_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `detalle_orden_chk_1` CHECK ((`cantidad` > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Conecta órdenes con productos, detallando los repuestos usados.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_orden`
--

LOCK TABLES `detalle_orden` WRITE;
/*!40000 ALTER TABLE `detalle_orden` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_orden` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `after_detalle_orden_insert` AFTER INSERT ON `detalle_orden` FOR EACH ROW BEGIN
    -- Actualizar stock del producto
    UPDATE productos
    SET stock_actual = stock_actual - NEW.cantidad
    WHERE id_producto = NEW.id_producto;

    -- Registrar movimiento de inventario
    INSERT INTO movimientos_inventario
    (id_producto, tipo_movimiento, cantidad, stock_anterior, stock_nuevo, id_usuario, id_orden, motivo)
    SELECT
        NEW.id_producto,
        'Salida',
        NEW.cantidad,
        stock_actual + NEW.cantidad,
        stock_actual,
        os.id_usuario_creador,
        NEW.id_orden,
        CONCAT('Uso en orden de servicio #', NEW.id_orden)
    FROM productos p
             CROSS JOIN ordenes_servicio os
    WHERE p.id_producto = NEW.id_producto
      AND os.id_orden = NEW.id_orden;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `after_detalle_orden_insert_update_total` AFTER INSERT ON `detalle_orden` FOR EACH ROW BEGIN
    UPDATE ordenes_servicio
    SET costo_productos = (
        SELECT COALESCE(SUM(subtotal), 0)
        FROM detalle_orden
        WHERE id_orden = NEW.id_orden
    ),
        costo_total = costo_mano_obra + (
            SELECT COALESCE(SUM(subtotal), 0)
            FROM detalle_orden
            WHERE id_orden = NEW.id_orden
        ) - descuento
    WHERE id_orden = NEW.id_orden;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `garantias`
--

DROP TABLE IF EXISTS `garantias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `garantias` (
  `id_garantia` int unsigned NOT NULL AUTO_INCREMENT,
  `id_orden` int unsigned NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `condiciones` text COLLATE utf8mb4_general_ci,
  `estado` enum('Activa','Vencida','Usada','Cancelada') COLLATE utf8mb4_general_ci DEFAULT 'Activa',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_garantia`),
  KEY `idx_orden` (`id_orden`),
  KEY `idx_estado` (`estado`),
  KEY `idx_fecha_fin` (`fecha_fin`),
  CONSTRAINT `garantias_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_servicio` (`id_orden`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Gestiona las garantías otorgadas por los servicios realizados.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `garantias`
--

LOCK TABLES `garantias` WRITE;
/*!40000 ALTER TABLE `garantias` DISABLE KEYS */;
/*!40000 ALTER TABLE `garantias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_estados_orden`
--

DROP TABLE IF EXISTS `historial_estados_orden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_estados_orden` (
  `id_historial` int unsigned NOT NULL AUTO_INCREMENT,
  `id_orden` int unsigned NOT NULL,
  `estado_anterior` enum('Pendiente','En Diagnostico','Esperando Aprobacion','En Reparacion','Listo para Entrega','Entregado','Cancelado') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado_nuevo` enum('Pendiente','En Diagnostico','Esperando Aprobacion','En Reparacion','Listo para Entrega','Entregado','Cancelado') COLLATE utf8mb4_general_ci NOT NULL,
  `id_usuario` int unsigned NOT NULL,
  `fecha_cambio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_historial`),
  KEY `id_usuario` (`id_usuario`),
  KEY `idx_orden` (`id_orden`),
  KEY `idx_fecha` (`fecha_cambio`),
  CONSTRAINT `historial_estados_orden_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_servicio` (`id_orden`) ON DELETE CASCADE,
  CONSTRAINT `historial_estados_orden_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Mantiene un historial completo de los cambios de estado de cada orden.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_estados_orden`
--

LOCK TABLES `historial_estados_orden` WRITE;
/*!40000 ALTER TABLE `historial_estados_orden` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_estados_orden` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_inventario`
--

DROP TABLE IF EXISTS `movimientos_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos_inventario` (
  `id_movimiento` int unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int unsigned NOT NULL,
  `tipo_movimiento` enum('Entrada','Salida','Ajuste','Merma','Devolucion') COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` int NOT NULL,
  `stock_anterior` int NOT NULL,
  `stock_nuevo` int NOT NULL,
  `id_usuario` int unsigned NOT NULL,
  `id_orden` int unsigned DEFAULT NULL,
  `fecha_movimiento` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `motivo` text COLLATE utf8mb4_general_ci,
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_orden` (`id_orden`),
  KEY `idx_producto` (`id_producto`),
  KEY `idx_fecha` (`fecha_movimiento`),
  KEY `idx_tipo` (`tipo_movimiento`),
  KEY `idx_movimientos_fecha_tipo` (`fecha_movimiento`,`tipo_movimiento`),
  CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE RESTRICT,
  CONSTRAINT `movimientos_inventario_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT,
  CONSTRAINT `movimientos_inventario_ibfk_3` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_servicio` (`id_orden`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registra todos los movimientos del inventario para auditoría.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_inventario`
--

LOCK TABLES `movimientos_inventario` WRITE;
/*!40000 ALTER TABLE `movimientos_inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id_notificacion` int unsigned NOT NULL AUTO_INCREMENT,
  `id_orden` int unsigned NOT NULL,
  `tipo` enum('SMS','Email','WhatsApp','Llamada') COLLATE utf8mb4_general_ci NOT NULL,
  `destinatario` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `asunto` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_programada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_envio` timestamp NULL DEFAULT NULL,
  `estado` enum('Pendiente','Enviado','Error','Cancelado') COLLATE utf8mb4_general_ci DEFAULT 'Pendiente',
  `error_detalle` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_notificacion`),
  KEY `idx_orden` (`id_orden`),
  KEY `idx_estado` (`estado`),
  KEY `idx_fecha_programada` (`fecha_programada`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_servicio` (`id_orden`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Gestiona las notificaciones enviadas a los clientes.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordenes_servicio`
--

DROP TABLE IF EXISTS `ordenes_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordenes_servicio` (
  `id_orden` int unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int unsigned NOT NULL,
  `id_bicicleta` int unsigned NOT NULL,
  `id_usuario_creador` int unsigned NOT NULL,
  `id_usuario_asignado` int unsigned DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_estimada_entrega` date DEFAULT NULL,
  `fecha_finalizacion` timestamp NULL DEFAULT NULL,
  `estado` enum('Pendiente','En Diagnostico','Esperando Aprobacion','En Reparacion','Listo para Entrega','Entregado','Cancelado') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pendiente',
  `prioridad` enum('Baja','Normal','Alta','Urgente') COLLATE utf8mb4_general_ci DEFAULT 'Normal',
  `descripcion_problema` text COLLATE utf8mb4_general_ci NOT NULL,
  `diagnostico_tecnico` text COLLATE utf8mb4_general_ci,
  `observaciones_entrega` text COLLATE utf8mb4_general_ci,
  `costo_mano_obra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costo_productos` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costo_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `requiere_aprobacion` tinyint(1) DEFAULT '0',
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `aprobado_por_cliente` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_orden`),
  KEY `id_bicicleta` (`id_bicicleta`),
  KEY `id_usuario_creador` (`id_usuario_creador`),
  KEY `id_usuario_asignado` (`id_usuario_asignado`),
  KEY `idx_estado` (`estado`),
  KEY `idx_fecha_creacion` (`fecha_creacion`),
  KEY `idx_fecha_estimada` (`fecha_estimada_entrega`),
  KEY `idx_cliente` (`id_cliente`),
  KEY `idx_prioridad` (`prioridad`),
  KEY `idx_ordenes_fecha_estado` (`fecha_creacion`,`estado`),
  CONSTRAINT `ordenes_servicio_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `ordenes_servicio_ibfk_2` FOREIGN KEY (`id_bicicleta`) REFERENCES `bicicletas` (`id_bicicleta`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `ordenes_servicio_ibfk_3` FOREIGN KEY (`id_usuario_creador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `ordenes_servicio_ibfk_4` FOREIGN KEY (`id_usuario_asignado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registra cada trabajo de reparación y su estado.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordenes_servicio`
--

LOCK TABLES `ordenes_servicio` WRITE;
/*!40000 ALTER TABLE `ordenes_servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `ordenes_servicio` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `before_orden_entregada` BEFORE UPDATE ON `ordenes_servicio` FOR EACH ROW BEGIN
    IF NEW.estado = 'Entregado' AND OLD.estado != 'Entregado' THEN
        SET NEW.fecha_finalizacion = CURRENT_TIMESTAMP;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `after_orden_estado_update` AFTER UPDATE ON `ordenes_servicio` FOR EACH ROW BEGIN
    IF OLD.estado != NEW.estado THEN
        INSERT INTO historial_estados_orden
        (id_orden, estado_anterior, estado_nuevo, id_usuario, comentario)
        VALUES (
                   NEW.id_orden,
                   OLD.estado,
                   NEW.estado,
                   NEW.id_usuario_creador,
                   CONCAT('Cambio automático de estado')
               );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id_pago` int unsigned NOT NULL AUTO_INCREMENT,
  `id_orden` int unsigned NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('Efectivo','Tarjeta Debito','Tarjeta Credito','Transferencia','Yape','Plin','Otro') COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `numero_comprobante` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo_comprobante` enum('Boleta','Factura','Recibo') COLLATE utf8mb4_general_ci DEFAULT 'Boleta',
  `id_usuario_registra` int unsigned NOT NULL,
  `notas` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_pago`),
  KEY `id_usuario_registra` (`id_usuario_registra`),
  KEY `idx_orden` (`id_orden`),
  KEY `idx_fecha` (`fecha_pago`),
  KEY `idx_metodo` (`metodo_pago`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_orden`) REFERENCES `ordenes_servicio` (`id_orden`) ON DELETE RESTRICT,
  CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_usuario_registra`) REFERENCES `usuarios` (`id_usuario`) ON DELETE RESTRICT,
  CONSTRAINT `pagos_chk_1` CHECK ((`monto` > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registra los pagos realizados por los clientes.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id_producto` int unsigned NOT NULL AUTO_INCREMENT,
  `id_categoria` int unsigned NOT NULL,
  `id_proveedor` int unsigned DEFAULT NULL,
  `sku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock_actual` int NOT NULL DEFAULT '0',
  `stock_minimo` int NOT NULL DEFAULT '0',
  `unidad_medida` enum('Unidad','Par','Juego','Metro','Litro','Kilo') COLLATE utf8mb4_general_ci DEFAULT 'Unidad',
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_producto`),
  UNIQUE KEY `sku` (`sku`),
  KEY `id_proveedor` (`id_proveedor`),
  KEY `idx_sku` (`sku`),
  KEY `idx_categoria` (`id_categoria`),
  KEY `idx_stock` (`stock_actual`,`stock_minimo`),
  KEY `idx_nombre` (`nombre`),
  KEY `idx_productos_nombre_activo` (`nombre`,`activo`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `productos_chk_1` CHECK ((`precio_compra` >= 0)),
  CONSTRAINT `productos_chk_2` CHECK ((`precio_venta` >= 0)),
  CONSTRAINT `productos_chk_3` CHECK ((`stock_actual` >= 0)),
  CONSTRAINT `productos_chk_4` CHECK ((`stock_minimo` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Controla el inventario de repuestos y accesorios.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id_proveedor` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `contacto_nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_general_ci,
  `ruc` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  `notas` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_proveedor`),
  KEY `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Gestiona los proveedores de repuestos y productos.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contraseña_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rol` enum('admin','tecnico') COLLATE utf8mb4_general_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ultima_sesion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`),
  KEY `idx_correo` (`correo`),
  KEY `idx_rol` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Almacena los datos de los empleados que usan el sistema.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vista_historial_clientes`
--

DROP TABLE IF EXISTS `vista_historial_clientes`;
/*!50001 DROP VIEW IF EXISTS `vista_historial_clientes`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vista_historial_clientes` AS SELECT 
 1 AS `id_cliente`,
 1 AS `nombre`,
 1 AS `contacto_telefono`,
 1 AS `total_bicicletas`,
 1 AS `total_ordenes`,
 1 AS `total_gastado`,
 1 AS `ultima_visita`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vista_ordenes_completas`
--

DROP TABLE IF EXISTS `vista_ordenes_completas`;
/*!50001 DROP VIEW IF EXISTS `vista_ordenes_completas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vista_ordenes_completas` AS SELECT 
 1 AS `id_orden`,
 1 AS `fecha_creacion`,
 1 AS `fecha_estimada_entrega`,
 1 AS `estado`,
 1 AS `prioridad`,
 1 AS `cliente_nombre`,
 1 AS `cliente_telefono`,
 1 AS `bicicleta_marca`,
 1 AS `bicicleta_modelo`,
 1 AS `tecnico_asignado`,
 1 AS `costo_total`,
 1 AS `total_pagado`,
 1 AS `saldo_pendiente`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vista_productos_stock_bajo`
--

DROP TABLE IF EXISTS `vista_productos_stock_bajo`;
/*!50001 DROP VIEW IF EXISTS `vista_productos_stock_bajo`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vista_productos_stock_bajo` AS SELECT 
 1 AS `id_producto`,
 1 AS `sku`,
 1 AS `nombre`,
 1 AS `categoria`,
 1 AS `stock_actual`,
 1 AS `stock_minimo`,
 1 AS `unidades_faltantes`,
 1 AS `proveedor`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'taller_bicicletas'
--
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_orden_servicio` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `sp_crear_orden_servicio`(
    IN p_id_cliente INT UNSIGNED,
    IN p_id_bicicleta INT UNSIGNED,
    IN p_id_usuario INT UNSIGNED,
    IN p_descripcion_problema TEXT,
    IN p_prioridad ENUM('Baja', 'Normal', 'Alta', 'Urgente'),
    IN p_fecha_estimada DATE,
    OUT p_id_orden INT UNSIGNED
)
BEGIN
    INSERT INTO ordenes_servicio
    (id_cliente, id_bicicleta, id_usuario_creador, descripcion_problema, prioridad, fecha_estimada_entrega, estado)
    VALUES
        (p_id_cliente, p_id_bicicleta, p_id_usuario, p_descripcion_problema, p_prioridad, p_fecha_estimada, 'Pendiente');

    SET p_id_orden = LAST_INSERT_ID();
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_registrar_pago` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`%` PROCEDURE `sp_registrar_pago`(
    IN p_id_orden INT UNSIGNED,
    IN p_monto DECIMAL(10,2),
    IN p_metodo_pago VARCHAR(50),
    IN p_id_usuario INT UNSIGNED,
    IN p_tipo_comprobante VARCHAR(20),
    OUT p_saldo_pendiente DECIMAL(10,2)
)
BEGIN
    DECLARE v_total_orden DECIMAL(10,2);
    DECLARE v_total_pagado DECIMAL(10,2);

    -- Insertar el pago
    INSERT INTO pagos (id_orden, monto, metodo_pago, id_usuario_registra, tipo_comprobante)
    VALUES (p_id_orden, p_monto, p_metodo_pago, p_id_usuario, p_tipo_comprobante);

    -- Calcular saldo pendiente
    SELECT costo_total INTO v_total_orden
    FROM ordenes_servicio
    WHERE id_orden = p_id_orden;

    SELECT COALESCE(SUM(monto), 0) INTO v_total_pagado
    FROM pagos
    WHERE id_orden = p_id_orden;

    SET p_saldo_pendiente = v_total_orden - v_total_pagado;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `vista_historial_clientes`
--

/*!50001 DROP VIEW IF EXISTS `vista_historial_clientes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_historial_clientes` AS select `c`.`id_cliente` AS `id_cliente`,`c`.`nombre` AS `nombre`,`c`.`contacto_telefono` AS `contacto_telefono`,count(distinct `b`.`id_bicicleta`) AS `total_bicicletas`,count(distinct `o`.`id_orden`) AS `total_ordenes`,coalesce(sum(`o`.`costo_total`),0) AS `total_gastado`,max(`o`.`fecha_creacion`) AS `ultima_visita` from ((`clientes` `c` left join `bicicletas` `b` on((`c`.`id_cliente` = `b`.`id_cliente`))) left join `ordenes_servicio` `o` on((`c`.`id_cliente` = `o`.`id_cliente`))) where (`c`.`activo` = true) group by `c`.`id_cliente` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_ordenes_completas`
--

/*!50001 DROP VIEW IF EXISTS `vista_ordenes_completas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_ordenes_completas` AS select `o`.`id_orden` AS `id_orden`,`o`.`fecha_creacion` AS `fecha_creacion`,`o`.`fecha_estimada_entrega` AS `fecha_estimada_entrega`,`o`.`estado` AS `estado`,`o`.`prioridad` AS `prioridad`,`c`.`nombre` AS `cliente_nombre`,`c`.`contacto_telefono` AS `cliente_telefono`,`b`.`marca` AS `bicicleta_marca`,`b`.`modelo` AS `bicicleta_modelo`,`u`.`nombre` AS `tecnico_asignado`,`o`.`costo_total` AS `costo_total`,coalesce(sum(`p`.`monto`),0) AS `total_pagado`,(`o`.`costo_total` - coalesce(sum(`p`.`monto`),0)) AS `saldo_pendiente` from ((((`ordenes_servicio` `o` join `clientes` `c` on((`o`.`id_cliente` = `c`.`id_cliente`))) join `bicicletas` `b` on((`o`.`id_bicicleta` = `b`.`id_bicicleta`))) left join `usuarios` `u` on((`o`.`id_usuario_asignado` = `u`.`id_usuario`))) left join `pagos` `p` on((`o`.`id_orden` = `p`.`id_orden`))) group by `o`.`id_orden` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_productos_stock_bajo`
--

/*!50001 DROP VIEW IF EXISTS `vista_productos_stock_bajo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_productos_stock_bajo` AS select `p`.`id_producto` AS `id_producto`,`p`.`sku` AS `sku`,`p`.`nombre` AS `nombre`,`c`.`nombre` AS `categoria`,`p`.`stock_actual` AS `stock_actual`,`p`.`stock_minimo` AS `stock_minimo`,(`p`.`stock_minimo` - `p`.`stock_actual`) AS `unidades_faltantes`,`pr`.`nombre` AS `proveedor` from ((`productos` `p` join `categorias` `c` on((`p`.`id_categoria` = `c`.`id_categoria`))) left join `proveedores` `pr` on((`p`.`id_proveedor` = `pr`.`id_proveedor`))) where ((`p`.`stock_actual` <= `p`.`stock_minimo`) and (`p`.`activo` = true)) order by (`p`.`stock_minimo` - `p`.`stock_actual`) desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-27 22:54:02
