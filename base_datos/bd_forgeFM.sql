-- MariaDB dump 10.19  Distrib 10.6.16-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bd_forgeFM
-- ------------------------------------------------------
-- Server version	10.6.16-MariaDB-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `archivos`
--

DROP TABLE IF EXISTS `archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archivos` (
  `id_archivo` int(11) NOT NULL,
  `ruta_archivo` varchar(700) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `tamanio` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `tipo_archivo` varchar(100) DEFAULT NULL,
  `estado_papelera` enum('si','no') NOT NULL DEFAULT 'no',
  `fecha_papelera` datetime DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_carpeta` int(11) DEFAULT NULL,
  `id_archivo_papelera` varchar(255) DEFAULT NULL,
  `ruta_archivo_papelera` varchar(700) DEFAULT NULL,
  `ruta_icono` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_archivo`,`ruta_archivo`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_carpeta` (`id_carpeta`),
  CONSTRAINT `archivos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `archivos_ibfk_2` FOREIGN KEY (`id_carpeta`) REFERENCES `carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CONSTRAINT_1` CHECK (`tamanio` >= 0),
  CONSTRAINT `CONSTRAINT_2` CHECK (`fecha_creacion` <= `fecha_papelera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carpetas`
--

DROP TABLE IF EXISTS `carpetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carpetas` (
  `id_carpeta` int(11) NOT NULL,
  `ruta_carpeta` varchar(700) NOT NULL,
  `nombre_carpeta` varchar(255) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_carpetaPadre` int(11) DEFAULT NULL,
  `id_carpeta_papelera` varchar(255) DEFAULT NULL,
  `ruta_carpeta_papelera` varchar(700) DEFAULT NULL,
  `fecha_papelera` datetime DEFAULT NULL,
  `estado_papelera` enum('si','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id_carpeta`,`ruta_carpeta`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_carpetaPadre` (`id_carpetaPadre`),
  CONSTRAINT `carpetas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carpetas_ibfk_2` FOREIGN KEY (`id_carpetaPadre`) REFERENCES `carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compartidos`
--

DROP TABLE IF EXISTS `compartidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compartidos` (
  `id_compartido` int(11) NOT NULL AUTO_INCREMENT,
  `id_archivo` int(11) DEFAULT NULL,
  `id_carpeta` int(11) DEFAULT NULL,
  `id_propietario` int(11) NOT NULL,
  `id_receptor` int(11) NOT NULL,
  `fecha_compartido` datetime NOT NULL,
  PRIMARY KEY (`id_compartido`),
  UNIQUE KEY `compartidos_ibfk_5` (`id_archivo`,`id_propietario`,`id_receptor`),
  UNIQUE KEY `compartidos_ibfk_6` (`id_carpeta`,`id_propietario`,`id_receptor`),
  KEY `id_propietario` (`id_propietario`),
  KEY `id_receptor` (`id_receptor`),
  KEY `id_archivo` (`id_archivo`),
  KEY `id_carpeta` (`id_carpeta`),
  CONSTRAINT `compartidos_ibfk_1` FOREIGN KEY (`id_propietario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compartidos_ibfk_2` FOREIGN KEY (`id_receptor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compartidos_ibfk_3` FOREIGN KEY (`id_archivo`) REFERENCES `archivos` (`id_archivo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compartidos_ibfk_4` FOREIGN KEY (`id_carpeta`) REFERENCES `carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=576 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `id_mapping`
--

DROP TABLE IF EXISTS `id_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `id_mapping` (
  `id` varchar(255) NOT NULL,
  `id_carpeta` int(11) DEFAULT NULL,
  `id_archivo` int(11) DEFAULT NULL,
  `tipo_elemento` enum('carpeta','archivo') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_carpeta` (`id_carpeta`),
  KEY `id_archivo` (`id_archivo`),
  CONSTRAINT `id_mapping_ibfk_1` FOREIGN KEY (`id_carpeta`) REFERENCES `carpetas` (`id_carpeta`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_mapping_ibfk_2` FOREIGN KEY (`id_archivo`) REFERENCES `archivos` (`id_archivo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registros`
--

DROP TABLE IF EXISTS `registros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registros` (
  `id_registro` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_evento` varchar(10) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `evento` varchar(500) DEFAULT NULL,
  `tipo` varchar(30) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_registro`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `registros_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1562 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `fecha_registro` date NOT NULL DEFAULT curdate(),
  `almacenamiento_total` bigint(20) NOT NULL DEFAULT 32212254720,
  `ruta_imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'bd_forgeFM'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ActualizarIdCarpetaArchivos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`gestoradmin`@`localhost` PROCEDURE `ActualizarIdCarpetaArchivos`(IN rutaRaiz VARCHAR(255))
BEGIN
    
    UPDATE archivos
    SET id_carpeta = NULL
    WHERE ruta_archivo LIKE CONCAT(rutaRaiz, '/%') AND ruta_archivo NOT LIKE CONCAT(rutaRaiz, '/%/%');

    
    UPDATE archivos AS child
    JOIN carpetas AS parent ON child.ruta_archivo LIKE CONCAT(parent.ruta_carpeta, '/%')
    AND child.ruta_archivo NOT LIKE CONCAT(parent.ruta_carpeta, '/%/%')
    SET child.id_carpeta = parent.id_carpeta;

    
    UPDATE archivos AS child
    LEFT JOIN carpetas AS parent ON child.ruta_archivo LIKE CONCAT(parent.ruta_carpeta, '/%')
    AND child.ruta_archivo NOT LIKE CONCAT(parent.ruta_carpeta, '/%/%')
    SET child.id_carpeta = IF(parent.id_carpeta IS NULL, NULL, parent.id_carpeta)
    WHERE child.ruta_archivo LIKE CONCAT(rutaRaiz, '/%/%');
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ActualizarIdCarpetaPadre` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`gestoradmin`@`localhost` PROCEDURE `ActualizarIdCarpetaPadre`(IN rutaRaiz VARCHAR(255))
BEGIN
    
    UPDATE carpetas
    SET id_carpetaPadre = NULL
    WHERE ruta_carpeta LIKE CONCAT(rutaRaiz, '/%') AND ruta_carpeta NOT LIKE CONCAT(rutaRaiz, '/%/%');

    
    UPDATE carpetas AS child
    JOIN carpetas AS parent ON child.ruta_carpeta LIKE CONCAT(parent.ruta_carpeta, '/%')
    AND child.ruta_carpeta NOT LIKE CONCAT(parent.ruta_carpeta, '/%/%')
    SET child.id_carpetaPadre = parent.id_carpeta
    WHERE parent.ruta_carpeta != rutaRaiz;

    
    UPDATE carpetas AS child
    LEFT JOIN carpetas AS parent ON child.ruta_carpeta LIKE CONCAT(parent.ruta_carpeta, '/%')
    AND child.ruta_carpeta NOT LIKE CONCAT(parent.ruta_carpeta, '/%/%')
    SET child.id_carpetaPadre = IF(parent.id_carpeta IS NULL, NULL, parent.id_carpeta)
    WHERE child.ruta_carpeta LIKE CONCAT(rutaRaiz, '/%/%');
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ActualizarRutasArchivos` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`gestoradmin`@`localhost` PROCEDURE `ActualizarRutasArchivos`(
    IN idCarpetaPadre INT, 
    IN viejaRuta VARCHAR(255), 
    IN nuevaRutaBase VARCHAR(255),
    IN rutaRaiz VARCHAR(255)
)
BEGIN
    DECLARE rutaCarpetaAntigua VARCHAR(255);

    
    SELECT ruta_carpeta INTO rutaCarpetaAntigua FROM carpetas WHERE id_carpeta = idCarpetaPadre;

    
    UPDATE archivos
    SET 
        ruta_archivo = CONCAT(nuevaRutaBase, SUBSTRING(ruta_archivo, LENGTH(rutaCarpetaAntigua) + 1))
    WHERE 
        ruta_archivo LIKE CONCAT(rutaCarpetaAntigua, '/%');

    
    CALL ActualizarIdCarpetaArchivos(rutaRaiz);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ActualizarRutasCarpetas` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`gestoradmin`@`localhost` PROCEDURE `ActualizarRutasCarpetas`(
    IN idCarpetaPadre INT, 
    IN viejaRuta VARCHAR(255), 
    IN nuevaRutaBase VARCHAR(255), 
    IN rutaRaiz VARCHAR(255)
)
BEGIN
    DECLARE rutaCarpetaAntigua VARCHAR(255);
    DECLARE nuevoIdCarpetaPadre INT;

    
    SELECT ruta_carpeta INTO rutaCarpetaAntigua FROM carpetas WHERE id_carpeta = idCarpetaPadre;

    
    UPDATE carpetas
    SET 
        ruta_carpeta = CONCAT(nuevaRutaBase, SUBSTRING(ruta_carpeta, LENGTH(rutaCarpetaAntigua) + 1))
    WHERE 
        ruta_carpeta LIKE CONCAT(rutaCarpetaAntigua, '/%');

    
    CALL ActualizarIdCarpetaPadre(rutaRaiz);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EncontrarRutaRestauracion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`gestoradmin`@`localhost` PROCEDURE `EncontrarRutaRestauracion`(
    IN carpetaId INT,
    IN rutaRaiz VARCHAR(255),
    OUT rutaRestauracion VARCHAR(255)
)
BEGIN
    DECLARE rutaActual VARCHAR(255);
    DECLARE carpetaPadreId INT DEFAULT NULL;
    DECLARE esPapelera INT;
    DECLARE done INT DEFAULT 0;

    SELECT ruta_carpeta, id_carpetaPadre INTO rutaActual, carpetaPadreId FROM carpetas WHERE id_carpeta = carpetaId;

    
    WHILE carpetaPadreId IS NOT NULL AND done = 0 DO
        
        SELECT c.ruta_carpeta, c.id_carpetaPadre, IF(c.estado_papelera = 'si', 1, 0) INTO rutaActual, carpetaPadreId, esPapelera
        FROM carpetas c WHERE c.id_carpeta = carpetaPadreId;

        
        IF esPapelera = 0 THEN
            SET rutaRestauracion = rutaActual;
            SET done = 1;
        ELSEIF carpetaPadreId IS NULL THEN
            
            SET rutaRestauracion = rutaRaiz;
            SET done = 1;
        END IF;
    END WHILE;

    
    IF done = 0 THEN
        SET rutaRestauracion = rutaRaiz;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EncontrarRutaRestauracionArchivo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`gestoradmin`@`localhost` PROCEDURE `EncontrarRutaRestauracionArchivo`(
    IN archivoId INT,
    IN rutaRaiz VARCHAR(255),
    OUT rutaRestauracion VARCHAR(255)
)
BEGIN
    DECLARE rutaArchivoActual VARCHAR(255);
    DECLARE carpetaPadreId INT DEFAULT NULL;
    DECLARE esPapelera INT;
    DECLARE done INT DEFAULT 0;

    
    SELECT ruta_archivo, id_carpeta INTO rutaArchivoActual, carpetaPadreId FROM archivos WHERE id_archivo = archivoId;

    
    WHILE carpetaPadreId IS NOT NULL AND done = 0 DO
        
        SELECT c.ruta_carpeta, c.id_carpetaPadre, IF(c.estado_papelera = 'si', 1, 0) INTO rutaArchivoActual, carpetaPadreId, esPapelera
        FROM carpetas c WHERE c.id_carpeta = carpetaPadreId;

        
        IF esPapelera = 0 THEN
            SET rutaRestauracion = rutaArchivoActual;
            SET done = 1;
        ELSEIF carpetaPadreId IS NULL THEN
            
            SET rutaRestauracion = rutaRaiz;
            SET done = 1;
        END IF;
    END WHILE;

    
    IF done = 0 THEN
        SET rutaRestauracion = rutaRaiz;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-09 12:38:39
