-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: PROGWEB
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora` (
  `Id_b` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time NOT NULL,
  `accion` varchar(50) DEFAULT NULL,
  `id_u` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_b`),
  KEY `id_u` (`id_u`),
  CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `usuario` (`Id_u`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (39,'2025-04-11','00:00:00','Inserción de usuario',7),(40,'2025-04-11','02:49:52','Inserción de usuario',7),(41,'2025-04-11','02:52:19','Inserción de usuario',7),(42,'2025-04-11','02:56:15','Actualización de usuario con ID: 16',7),(43,'2025-04-11','02:57:10','Eliminación',7),(44,'2025-04-11','07:40:10','Inserción de usuario',7),(45,'2025-04-11','07:40:18','Actualización',7),(46,'2025-04-11','07:40:22','Eliminación',7),(47,'2025-04-11','09:31:00','Eliminación',7),(48,'2025-04-11','09:31:18','Eliminación',7),(49,'2025-05-12','11:27:17','Inserción de usuario ID: 0',7);
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mod_perfil`
--

DROP TABLE IF EXISTS `mod_perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_perfil` (
  `Id_mod` int(11) NOT NULL,
  `Id_p` int(11) NOT NULL,
  PRIMARY KEY (`Id_mod`,`Id_p`),
  KEY `Id_p` (`Id_p`),
  CONSTRAINT `mod_perfil_ibfk_1` FOREIGN KEY (`Id_mod`) REFERENCES `modulo` (`Id_mod`),
  CONSTRAINT `mod_perfil_ibfk_2` FOREIGN KEY (`Id_p`) REFERENCES `perfil` (`Id_p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mod_perfil`
--

LOCK TABLES `mod_perfil` WRITE;
/*!40000 ALTER TABLE `mod_perfil` DISABLE KEYS */;
INSERT INTO `mod_perfil` VALUES (1,1001),(1,1002),(2,1001),(2,1002),(2,1003),(6,1001),(6,1002);
/*!40000 ALTER TABLE `mod_perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo` (
  `Id_mod` int(11) NOT NULL,
  `Nombre` varchar(40) DEFAULT NULL,
  `URL` varchar(70) DEFAULT NULL,
  `Borrado` char(1) DEFAULT NULL,
  PRIMARY KEY (`Id_mod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'AdmUsuario','AdmUsuario/index.php','0'),(2,'AdmBitacora','AdmBitacora/index.php','0'),(3,'AdmPerfil','AdmPerfil/index.php','0'),(4,'AdmVacante','AdmVacante/index.php','0'),(5,'AdmPostulacion','AdmPostulacion/index.php','0'),(6,'AdmModulo','AdmModulo/index.php','0');
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `Id_p` int(11) NOT NULL,
  `Nombre` varchar(25) DEFAULT NULL,
  `Descripcion` varchar(70) DEFAULT NULL,
  `Borrado` char(1) DEFAULT NULL,
  PRIMARY KEY (`Id_p`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1001,'estudiante','persona que estudia.','0'),(1002,'administrador','persona que administra la escuela.','0'),(1003,'profesor','persona que ense?a a estudiantes.','0');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona` (
  `id_p` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `edad` int(2) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `borrado` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_p`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (2,'Fernando',22,'fernando@gmail.com',0),(6,'Alejandra',22,NULL,0),(7,'Tito',25,NULL,0),(8,'Carlos',25,NULL,0),(9,'jose',12,NULL,0),(11,'Karen',22,NULL,0),(13,'Juan',12,NULL,0),(15,'Admin',12,NULL,0),(16,'Juan',33,NULL,0),(17,'leo',50,NULL,0);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `Id_u` int(11) NOT NULL AUTO_INCREMENT,
  `Nick` varchar(20) NOT NULL,
  `Pwd` varchar(255) NOT NULL,
  `Id_p` int(11) DEFAULT NULL,
  `id_pp` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_u`),
  KEY `Id_p` (`Id_p`),
  KEY `fk_persona` (`id_pp`),
  CONSTRAINT `fk_persona` FOREIGN KEY (`id_pp`) REFERENCES `persona` (`id_p`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Id_p`) REFERENCES `perfil` (`Id_p`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (7,'fernando','123',1002,2),(10,'ale','1212',1002,6),(11,'tito','tito',1002,7),(12,'carlos','123',1001,8),(13,'jose','1212',1003,9),(15,'karen','2222',1003,11),(17,'juan','1212',1002,13),(19,'admin','1212',1002,15),(20,'ju','1212',1001,16),(21,'leo','leo',1002,17);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vista_bitacora`
--

DROP TABLE IF EXISTS `vista_bitacora`;
/*!50001 DROP VIEW IF EXISTS `vista_bitacora`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_bitacora` AS SELECT
 1 AS `Id_b`,
  1 AS `accion`,
  1 AS `fecha`,
  1 AS `hora`,
  1 AS `nombre`,
  1 AS `nick`,
  1 AS `perfil`,
  1 AS `mod` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vista_usuario_completo`
--

DROP TABLE IF EXISTS `vista_usuario_completo`;
/*!50001 DROP VIEW IF EXISTS `vista_usuario_completo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vista_usuario_completo` AS SELECT
 1 AS `id_u`,
  1 AS `persona_nombre`,
  1 AS `edad`,
  1 AS `nick`,
  1 AS `Pwd`,
  1 AS `perfil_nombre`,
  1 AS `estado` */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'PROGWEB'
--
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_actualizar_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_usuario`(
    IN p_id_usuario INT,
    IN p_nombre VARCHAR(100),
    IN p_edad INT,
    IN p_nick VARCHAR(20),
    IN p_pwd VARCHAR(255),
    IN p_borrado INT,
    IN p_id_perfil INT
)
BEGIN
    DECLARE v_id_persona INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    
    SELECT id_pp INTO v_id_persona FROM usuario WHERE Id_u = p_id_usuario;
    
    
    UPDATE persona 
    SET nombre = p_nombre, 
        edad = p_edad, 
        borrado = p_borrado
    WHERE id_p = v_id_persona;
    
    
    IF p_pwd IS NOT NULL AND p_pwd != '' THEN
        UPDATE usuario 
        SET Nick = p_nick, 
            Pwd = p_pwd,  
            Id_p = p_id_perfil
        WHERE Id_u = p_id_usuario;
    ELSE
        UPDATE usuario 
        SET Nick = p_nick, 
            Id_p = p_id_perfil
        WHERE Id_u = p_id_usuario;
    END IF;
    
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_crear_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_crear_usuario`(
    IN p_nombre VARCHAR(100),
    IN p_edad INT,
    IN p_nick VARCHAR(20),
    IN p_pwd VARCHAR(255),
    IN p_borrado INT,
    IN p_id_perfil INT
)
BEGIN
    DECLARE persona_id INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    
    INSERT INTO persona (nombre, edad, borrado)
    VALUES (p_nombre, p_edad, p_borrado);

    SET persona_id = LAST_INSERT_ID();

    
    INSERT INTO usuario (Nick, Pwd, Id_p, id_pp)
    VALUES (p_nick, p_pwd, p_id_perfil, persona_id);

    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_eliminar_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_usuario`(
    IN p_id_usuario INT
)
BEGIN
    DECLARE v_id_persona INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    
    SELECT id_pp INTO v_id_persona FROM usuario WHERE Id_u = p_id_usuario;
    
    
    DELETE FROM usuario WHERE Id_u = p_id_usuario;
    
    
    DELETE FROM persona WHERE id_p = v_id_persona;
    
    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `vista_bitacora`
--

/*!50001 DROP VIEW IF EXISTS `vista_bitacora`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_bitacora` AS select `b`.`Id_b` AS `Id_b`,`b`.`accion` AS `accion`,`b`.`fecha` AS `fecha`,`b`.`hora` AS `hora`,`pp`.`nombre` AS `nombre`,`u`.`Nick` AS `nick`,`p`.`Nombre` AS `perfil`,'Bit�cora' AS `mod` from (((`bitacora` `b` left join `usuario` `u` on(`b`.`id_u` = `u`.`Id_u`)) left join `persona` `pp` on(`u`.`id_pp` = `pp`.`id_p`)) left join `perfil` `p` on(`u`.`Id_p` = `p`.`Id_p`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vista_usuario_completo`
--

/*!50001 DROP VIEW IF EXISTS `vista_usuario_completo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vista_usuario_completo` AS select `u`.`Id_u` AS `id_u`,`pp`.`nombre` AS `persona_nombre`,`pp`.`edad` AS `edad`,`u`.`Nick` AS `nick`,`u`.`Pwd` AS `Pwd`,`p`.`Nombre` AS `perfil_nombre`,case when `pp`.`borrado` = 1 then 'Inactivo' else 'Activo' end AS `estado` from ((`usuario` `u` left join `perfil` `p` on(`u`.`Id_p` = `p`.`Id_p`)) left join `persona` `pp` on(`pp`.`id_p` = `u`.`id_pp`)) */;
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

-- Dump completed on 2025-05-16 11:40:43
