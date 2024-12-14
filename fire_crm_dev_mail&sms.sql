/*
 Navicat Premium Data Transfer

 Source Server         : Laragon Server
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3306
 Source Schema         : fire_crm_dev

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 13/12/2024 19:25:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for afirmaciones_dimensiones_competencias_calidad
-- ----------------------------
DROP TABLE IF EXISTS `afirmaciones_dimensiones_competencias_calidad`;
CREATE TABLE `afirmaciones_dimensiones_competencias_calidad`  (
  `id` int(0) NOT NULL,
  `id_dimension` int(0) NULL DEFAULT NULL,
  `nombre` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ponderacion` int(0) NULL DEFAULT NULL,
  `descripcion_simple` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `corte` decimal(10, 1) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for agendamiento
-- ----------------------------
DROP TABLE IF EXISTS `agendamiento`;
CREATE TABLE `agendamiento`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Rut` int(0) NULL DEFAULT NULL,
  `Agenda` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FechaAgenda` datetime(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `id_gestion` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for agendamiento_compromiso
-- ----------------------------
DROP TABLE IF EXISTS `agendamiento_compromiso`;
CREATE TABLE `agendamiento_compromiso`  (
  `id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Compromiso` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FechaCompromiso` datetime(0) NULL DEFAULT NULL,
  `MontoCompromiso` int(0) NULL DEFAULT NULL,
  `NumeroFactura` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `id_gestion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for agendamientos
-- ----------------------------
DROP TABLE IF EXISTS `agendamientos`;
CREATE TABLE `agendamientos`  (
  `id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Fecha` datetime(0) NULL DEFAULT NULL,
  `Agendamiento` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'OK' COMMENT 'OK'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for aprobacion_patron_personalidad_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `aprobacion_patron_personalidad_reclutamiento`;
CREATE TABLE `aprobacion_patron_personalidad_reclutamiento`  (
  `id` int(0) NOT NULL,
  `patron` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for asignacion_cola
-- ----------------------------
DROP TABLE IF EXISTS `asignacion_cola`;
CREATE TABLE `asignacion_cola`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_cola` int(0) NULL DEFAULT NULL,
  `asignacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for asterisk_agentes
-- ----------------------------
DROP TABLE IF EXISTS `asterisk_agentes`;
CREATE TABLE `asterisk_agentes`  (
  `id` int(0) NOT NULL,
  `Agente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Queue` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for asterisk_all_queues
-- ----------------------------
DROP TABLE IF EXISTS `asterisk_all_queues`;
CREATE TABLE `asterisk_all_queues`  (
  `id` int(0) NOT NULL,
  `Queue` int(0) NULL DEFAULT NULL,
  `id_discador` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for asterisk_discador_cola
-- ----------------------------
DROP TABLE IF EXISTS `asterisk_discador_cola`;
CREATE TABLE `asterisk_discador_cola`  (
  `id` int(0) NOT NULL,
  `Cola` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `numero_canales` int(0) NULL DEFAULT NULL,
  `telfxrut` int(0) NULL DEFAULT NULL,
  `tipo_telefono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Estado` int(0) NULL DEFAULT NULL,
  `Status` int(0) NULL DEFAULT NULL,
  `FeMin` datetime(0) NULL DEFAULT NULL,
  `FeFin` datetime(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Salida` int(0) NULL DEFAULT NULL,
  `Test` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TipoCategorias` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for asterisk_inbound_cola
-- ----------------------------
DROP TABLE IF EXISTS `asterisk_inbound_cola`;
CREATE TABLE `asterisk_inbound_cola`  (
  `id` int(0) NOT NULL,
  `Queue` int(0) NULL DEFAULT NULL,
  `Descripcion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for audios
-- ----------------------------
DROP TABLE IF EXISTS `audios`;
CREATE TABLE `audios`  (
  `audio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `hora` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  INDEX `audio`(`audio`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for auditoria_calidad
-- ----------------------------
DROP TABLE IF EXISTS `auditoria_calidad`;
CREATE TABLE `auditoria_calidad`  (
  `id` int(0) NOT NULL,
  `idUsuario` int(0) NULL DEFAULT NULL,
  `idAdministrador` int(0) NULL DEFAULT NULL,
  `idEvaluacion` int(0) NULL DEFAULT NULL,
  `errorCritico` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipoAutorizacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fechaAutorizacion` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for auditoria_gestion
-- ----------------------------
DROP TABLE IF EXISTS `auditoria_gestion`;
CREATE TABLE `auditoria_gestion`  (
  `id` int(0) NOT NULL,
  `Rut` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Fono` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT NULL,
  `resultado_n3` int(0) NULL DEFAULT NULL,
  `n1` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `n2` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `n3` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `origen` int(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url_grabacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `monto_comp` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `id_usuarioSupervisor` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for autorizacionejecutivos
-- ----------------------------
DROP TABLE IF EXISTS `autorizacionejecutivos`;
CREATE TABLE `autorizacionejecutivos`  (
  `id` int(0) NOT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_UsuarioSupervisor` int(0) NULL DEFAULT NULL,
  `tipoAutorizacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Fecha` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for campaigns
-- ----------------------------
DROP TABLE IF EXISTS `campaigns`;
CREATE TABLE `campaigns`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `service_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'discador',
  `registros` int(0) NULL DEFAULT 0,
  `parametros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `estadisticas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `activa` tinyint(0) NULL DEFAULT 1,
  `creada_el` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizada_el` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id_cedente_2`(`id_cedente`, `service_id`, `tipo`) USING BTREE,
  INDEX `id_cedente`(`id_cedente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for campos_cargas_asignaciones
-- ----------------------------
DROP TABLE IF EXISTS `campos_cargas_asignaciones`;
CREATE TABLE `campos_cargas_asignaciones`  (
  `id` int(0) NOT NULL,
  `fecha` datetime(0) NULL DEFAULT NULL,
  `tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `campos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Id_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for campos_gestion
-- ----------------------------
DROP TABLE IF EXISTS `campos_gestion`;
CREATE TABLE `campos_gestion`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ValorEjemplo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ValorPredeterminado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo` int(0) NULL DEFAULT NULL,
  `Dinamico` int(0) NULL DEFAULT NULL,
  `CampoDB` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mandatorio` int(0) NULL DEFAULT NULL,
  `Deshabilitado` int(0) NULL DEFAULT NULL,
  `Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Respuesta_Nivel3` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Respuesta_Nivel4` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for campos_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `campos_reclutamiento`;
CREATE TABLE `campos_reclutamiento`  (
  `id` int(0) NOT NULL,
  `id_contenedor` int(0) NULL DEFAULT NULL,
  `Codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ValorEjemplo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ValorPredeterminado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo` int(0) NULL DEFAULT NULL,
  `Dinamico` int(0) NULL DEFAULT NULL,
  `CampoDB` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mandatorio` int(0) NULL DEFAULT NULL,
  `Deshabilitado` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for canales_omnicanalidad
-- ----------------------------
DROP TABLE IF EXISTS `canales_omnicanalidad`;
CREATE TABLE `canales_omnicanalidad`  (
  `id` int(0) NOT NULL,
  `canal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_compromisos_6_meses_cedente
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_compromisos_6_meses_cedente`;
CREATE TABLE `cantidad_compromisos_6_meses_cedente`  (
  `Cantidad` bigint(0) NOT NULL,
  `Rut` int(0) NOT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_compromisos_cedente
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_compromisos_cedente`;
CREATE TABLE `cantidad_compromisos_cedente`  (
  `Cantidad` bigint(0) NOT NULL,
  `Rut` int(0) NOT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_compromisos_mes_cedente
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_compromisos_mes_cedente`;
CREATE TABLE `cantidad_compromisos_mes_cedente`  (
  `Cantidad` bigint(0) NOT NULL,
  `Rut` int(0) NOT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_gestiones
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_gestiones`;
CREATE TABLE `cantidad_gestiones`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Cantidad_Gestiones_Mes` int(0) NOT NULL,
  `Cantidad_Gestiones_6mes` int(0) NOT NULL,
  `Cantidad_Gestiones_Mas6mes` int(0) NOT NULL,
  `Cantidad_Contactos_Mes` int(0) NOT NULL,
  `Cantidad_Contactos_Menos6mes` int(0) NOT NULL,
  `Cantidad_Contactos_Mas6mes` int(0) NOT NULL,
  `Cantidad_Compromisos_Mes` int(0) NOT NULL,
  `Cantidad_Compromisos_Menos6mes` int(0) NOT NULL,
  `Cantidad_Compromisos_Mas6mes` int(0) NOT NULL,
  `Cantidad_SMS` int(0) NOT NULL,
  `Cantidad_SMS_Menos6mes` int(0) NOT NULL,
  `Cantidad_SMS_Mas6Mes` int(0) NOT NULL,
  `Cantidad_IVR_Menos6mes` int(0) NOT NULL,
  `Cantidad_IVR_Mas6mes` int(0) NOT NULL,
  `Cantidad_IVR` int(0) NOT NULL,
  `Cantidad_Correos` int(0) NOT NULL,
  `Cantidad_Correos_Mas6mes` int(0) NOT NULL,
  `Cantidad_Correos_Menos6mes` int(0) NOT NULL,
  `Cantidad_Compromisos_Rotos` int(0) NOT NULL,
  `Fecha_Ultimo_Compromiso` date NULL DEFAULT NULL,
  `Dias_Para_Cump_Compromiso` int(0) NOT NULL,
  `Dias_Sin_gestion` int(0) NOT NULL,
  `Dias_Desde_Ult_Contacto` int(0) NOT NULL,
  `Tramo_Dias_Desde_Ult_Contacto` longblob NULL,
  `Sin_Gestion_Con_Fono` int(0) NOT NULL,
  `Sin_Gestion_Sin_Fono` int(0) NOT NULL,
  `Con_Mail` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_gestiones_6_meses_cedente
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_gestiones_6_meses_cedente`;
CREATE TABLE `cantidad_gestiones_6_meses_cedente`  (
  `Cantidad` bigint(0) NOT NULL,
  `Rut` int(0) NOT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_gestiones_cedente
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_gestiones_cedente`;
CREATE TABLE `cantidad_gestiones_cedente`  (
  `Cantidad` bigint(0) NOT NULL DEFAULT 0,
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL DEFAULT 0,
  `Nombre_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_gestiones_mes
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_gestiones_mes`;
CREATE TABLE `cantidad_gestiones_mes`  (
  `Cantidad` bigint(0) NOT NULL DEFAULT 0,
  `Rut` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_gestiones_mes_cedente
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_gestiones_mes_cedente`;
CREATE TABLE `cantidad_gestiones_mes_cedente`  (
  `Cantidad` bigint(0) NOT NULL,
  `Rut` int(0) NOT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_llamadas_gestion_mes
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_llamadas_gestion_mes`;
CREATE TABLE `cantidad_llamadas_gestion_mes`  (
  `Cantidad` bigint(0) NOT NULL DEFAULT 0,
  `Rut` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidad_llamadas_mes
-- ----------------------------
DROP TABLE IF EXISTS `cantidad_llamadas_mes`;
CREATE TABLE `cantidad_llamadas_mes`  (
  `Cantidad` bigint(0) NOT NULL DEFAULT 0,
  `Rut` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidadevauacionessemanales_calidad
-- ----------------------------
DROP TABLE IF EXISTS `cantidadevauacionessemanales_calidad`;
CREATE TABLE `cantidadevauacionessemanales_calidad`  (
  `id` int(0) NOT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `cantidadEvaluaciones` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidadgestiones
-- ----------------------------
DROP TABLE IF EXISTS `cantidadgestiones`;
CREATE TABLE `cantidadgestiones`  (
  `cantidad` bigint(0) NOT NULL,
  `Rut` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cantidadgestionespredictivo
-- ----------------------------
DROP TABLE IF EXISTS `cantidadgestionespredictivo`;
CREATE TABLE `cantidadgestionespredictivo`  (
  `id` int(0) NOT NULL,
  `anexo` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cartera` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cases
-- ----------------------------
DROP TABLE IF EXISTS `cases`;
CREATE TABLE `cases`  (
  `id_gestion` int(0) NOT NULL COMMENT 'Relacion con tabla gestion_ult_trimestre',
  `cerrado` int(0) NOT NULL DEFAULT 0 COMMENT '0 = False, 1 = True, para pantalla de supervisores de casos pendientes por cerrar',
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Campo detalle para el supervisor',
  `supervisor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Campo que indica a que supervisor se asigno el caso',
  `accion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Ejemplo = \"Ejecutivo deriva a Supervisor\", \"Supervisor Deriva a Gerente\", \"Aprobado Por Gerente\"',
  `accionado_por` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nombre de ejecutivo que ejecuta la accion'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Tabla relacional para reporte que permite a un supervisor saber que casos quedan pendientes por cerrar' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cedente
-- ----------------------------
DROP TABLE IF EXISTS `cedente`;
CREATE TABLE `cedente`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NOT NULL,
  `Nombre_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Fecha_Ingreso` date NULL DEFAULT NULL,
  `Tipo_Cartera` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `metaRecupero` int(0) NULL DEFAULT NULL,
  `tasaCumplimientoEsperado` int(0) NULL DEFAULT NULL,
  `tipo_archivo_asignacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estructura_fija` int(0) NULL DEFAULT NULL,
  `periodicidad_asignacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Estado` int(0) NULL DEFAULT NULL,
  `periodicidad_reportes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cedente_Global` int(0) NULL DEFAULT NULL,
  `Alias` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `planDiscado` int(0) NULL DEFAULT NULL,
  `IPDiscador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DialPlan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `omnicanalidad` int(0) NULL DEFAULT NULL,
  `agendamiento` int(0) NULL DEFAULT 0,
  `tipo_refresco` int(0) NULL DEFAULT 0,
  `posee_speech` int(0) NULL DEFAULT 0,
  `inicio_periodo` date NULL DEFAULT NULL,
  `fin_periodo` date NULL DEFAULT NULL,
  `facturas` int(0) NULL DEFAULT 0,
  `compromiso` int(0) NULL DEFAULT 0,
  `posee_scoring` int(0) NULL DEFAULT 0,
  `carga_personalizada` int(0) NULL DEFAULT 0,
  `agendamiento_obligatorio` int(0) NULL DEFAULT 0,
  `algoritmo_discado` int(0) NULL DEFAULT 1,
  `id_mandante` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `id_pais` int(0) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cedentes_listas
-- ----------------------------
DROP TABLE IF EXISTS `cedentes_listas`;
CREATE TABLE `cedentes_listas`  (
  `id` int(0) NOT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Id_Lista` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cierre_evaluaciones
-- ----------------------------
DROP TABLE IF EXISTS `cierre_evaluaciones`;
CREATE TABLE `cierre_evaluaciones`  (
  `id` int(0) NOT NULL,
  `Id_Evaluaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `Aspectos_Fortalecer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Aspectos_Corregir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Compromiso_Ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ponderacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Calf_Ponderada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo_cierre` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clientes
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes`  (
  `id` int(0) NOT NULL,
  `rut` int(0) NOT NULL,
  `dv` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `origen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DISAL',
  `fechaHora` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `usuario` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for codigo_area
-- ----------------------------
DROP TABLE IF EXISTS `codigo_area`;
CREATE TABLE `codigo_area`  (
  `id` int(0) NOT NULL,
  `Comuna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Codigo` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for colores_estatus_reporteonline
-- ----------------------------
DROP TABLE IF EXISTS `colores_estatus_reporteonline`;
CREATE TABLE `colores_estatus_reporteonline`  (
  `id` int(0) NOT NULL,
  `estatus` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icono` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `hovercolor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for columnas_agentes
-- ----------------------------
DROP TABLE IF EXISTS `columnas_agentes`;
CREATE TABLE `columnas_agentes`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `columnas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for columnas_asignacion_crm
-- ----------------------------
DROP TABLE IF EXISTS `columnas_asignacion_crm`;
CREATE TABLE `columnas_asignacion_crm`  (
  `id` int(0) NOT NULL,
  `prioridad` int(0) NULL DEFAULT 0,
  `columna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `destacar` int(0) NULL DEFAULT 0,
  `id_nuevo` int(0) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_nuevo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for columnas_asignacion_dial
-- ----------------------------
DROP TABLE IF EXISTS `columnas_asignacion_dial`;
CREATE TABLE `columnas_asignacion_dial`  (
  `id` int(0) NOT NULL,
  `Nombre` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Prioridad` int(0) NULL DEFAULT NULL,
  `Tabla` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Campo` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Operacion` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo_Campo` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for columnas_reporte_gestion_estructurado
-- ----------------------------
DROP TABLE IF EXISTS `columnas_reporte_gestion_estructurado`;
CREATE TABLE `columnas_reporte_gestion_estructurado`  (
  `id` int(0) NOT NULL,
  `Prioridad` int(0) NULL DEFAULT NULL,
  `CantCaracteres` int(0) NULL DEFAULT NULL,
  `Relleno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Formato` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Valor_Columna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Columna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TipoColumna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `PatronFecha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for columnas_tabs_asignacion_crm
-- ----------------------------
DROP TABLE IF EXISTS `columnas_tabs_asignacion_crm`;
CREATE TABLE `columnas_tabs_asignacion_crm`  (
  `id` int(0) NOT NULL,
  `columna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_tab` int(0) NULL DEFAULT NULL,
  `prioridad` int(0) NULL DEFAULT NULL,
  `tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for columnas_template_carga
-- ----------------------------
DROP TABLE IF EXISTS `columnas_template_carga`;
CREATE TABLE `columnas_template_carga`  (
  `id` int(0) NOT NULL,
  `id_template` int(0) NULL DEFAULT NULL,
  `Columna` int(0) NULL DEFAULT NULL,
  `posicionInicio` int(0) NULL DEFAULT NULL,
  `cantCaracteres` int(0) NULL DEFAULT NULL,
  `id_sheet` int(0) NULL DEFAULT NULL,
  `Funcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Parametros` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tabla` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Campo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `PatronFecha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Configurado` int(0) NULL DEFAULT NULL,
  `Mandatorio` int(0) NULL DEFAULT NULL,
  `Prioridad_Fono` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for competencias_calidad
-- ----------------------------
DROP TABLE IF EXISTS `competencias_calidad`;
CREATE TABLE `competencias_calidad`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Esperado` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `descripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ponderacion` int(0) NULL DEFAULT NULL,
  `tag` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_contenedor` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for competencias_periles_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `competencias_periles_reclutamiento`;
CREATE TABLE `competencias_periles_reclutamiento`  (
  `id` int(0) NOT NULL,
  `competencia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_empresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_perfil` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for compromiso_roto_historico
-- ----------------------------
DROP TABLE IF EXISTS `compromiso_roto_historico`;
CREATE TABLE `compromiso_roto_historico`  (
  `Rut` int(0) NOT NULL,
  `Fecha_Gestion` date NULL DEFAULT NULL,
  `Ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fec_Compromiso` date NULL DEFAULT NULL,
  `Monto_Compromiso` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for compromiso_roto_periodo
-- ----------------------------
DROP TABLE IF EXISTS `compromiso_roto_periodo`;
CREATE TABLE `compromiso_roto_periodo`  (
  `Rut` int(0) NOT NULL,
  `Fecha_Gestion` date NULL DEFAULT NULL,
  `Ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fec_Compromiso` date NULL DEFAULT NULL,
  `Monto_Compromiso` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for compromisos
-- ----------------------------
DROP TABLE IF EXISTS `compromisos`;
CREATE TABLE `compromisos`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `compromiso_id` int(0) NULL DEFAULT NULL,
  `cedente_id` int(0) NULL DEFAULT NULL,
  `rut` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telefono` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_compromiso` date NULL DEFAULT NULL,
  `monto_compromiso` decimal(20, 2) NULL DEFAULT NULL,
  `estado` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Pendiente',
  `dias` int(0) NULL DEFAULT NULL,
  `fecha_operacion` datetime(0) NULL DEFAULT NULL,
  `fecha_descargo` date NULL DEFAULT NULL,
  `creado_el` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for comuna
-- ----------------------------
DROP TABLE IF EXISTS `comuna`;
CREATE TABLE `comuna`  (
  `Id_Comuna` int(0) NOT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Provincia` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for conf_pantalla_cedente
-- ----------------------------
DROP TABLE IF EXISTS `conf_pantalla_cedente`;
CREATE TABLE `conf_pantalla_cedente`  (
  `Id_Conf` int(0) NOT NULL,
  `Nombre_Conf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Id_Cedente` int(0) NOT NULL,
  `Nombre_Tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descripcion_Consulta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nombre_Campos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nombre_Columnas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for config_derivaciones
-- ----------------------------
DROP TABLE IF EXISTS `config_derivaciones`;
CREATE TABLE `config_derivaciones`  (
  `id` int(0) NOT NULL,
  `htmlReprosDiaria` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `htmlReprosMensuales` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `htmlAcuerdosDiaria` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `htmlAcuerdosMensuales` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `correosCC` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for config_inhabilitacionfonos
-- ----------------------------
DROP TABLE IF EXISTS `config_inhabilitacionfonos`;
CREATE TABLE `config_inhabilitacionfonos`  (
  `id` int(0) NOT NULL,
  `id_nivel3` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for configuracion_columnas
-- ----------------------------
DROP TABLE IF EXISTS `configuracion_columnas`;
CREATE TABLE `configuracion_columnas`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `cedente_id` int(0) NULL DEFAULT NULL,
  `bloque` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cedente_id`(`cedente_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for configuracion_notificacion
-- ----------------------------
DROP TABLE IF EXISTS `configuracion_notificacion`;
CREATE TABLE `configuracion_notificacion`  (
  `id` int(0) NOT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `protocolo` smallint(0) NULL DEFAULT NULL,
  `secure` smallint(0) NULL DEFAULT NULL,
  `host` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `puerto` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contrasena` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `from_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `from_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for confirmacion
-- ----------------------------
DROP TABLE IF EXISTS `confirmacion`;
CREATE TABLE `confirmacion`  (
  `id` int(0) NOT NULL,
  `codigo` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_envio` int(0) NULL DEFAULT NULL,
  `emails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contactos_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `contactos_reclutamiento`;
CREATE TABLE `contactos_reclutamiento`  (
  `IdContacto` int(0) NOT NULL,
  `IdUsuarioReclutamiento` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Parentesco` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Celular1` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Celular2` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contenedor_competencias_calidad
-- ----------------------------
DROP TABLE IF EXISTS `contenedor_competencias_calidad`;
CREATE TABLE `contenedor_competencias_calidad`  (
  `id` int(0) NOT NULL,
  `nombreContenedor` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_TipoContacto` int(0) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contenedor_competencias_calidad_cedente
-- ----------------------------
DROP TABLE IF EXISTS `contenedor_competencias_calidad_cedente`;
CREATE TABLE `contenedor_competencias_calidad_cedente`  (
  `id` int(0) NOT NULL,
  `id_contenedor` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contenedores_campos_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `contenedores_campos_reclutamiento`;
CREATE TABLE `contenedores_campos_reclutamiento`  (
  `id` int(0) NOT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contratos
-- ----------------------------
DROP TABLE IF EXISTS `contratos`;
CREATE TABLE `contratos`  (
  `id` int(0) NOT NULL,
  `contrato` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fono` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `correo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rut` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sucursal` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cargo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora_creacion` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for control_envio
-- ----------------------------
DROP TABLE IF EXISTS `control_envio`;
CREATE TABLE `control_envio`  (
  `id` int(0) NOT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `protocolo` smallint(0) NULL DEFAULT NULL,
  `secure` smallint(0) NULL DEFAULT NULL,
  `host` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `puerto` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contrasena` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `from_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `from_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipoModulo` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `ConfirmReadingTo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT ''
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for control_notificaciones
-- ----------------------------
DROP TABLE IF EXISTS `control_notificaciones`;
CREATE TABLE `control_notificaciones`  (
  `id` int(0) NOT NULL,
  `cantidad_horas` int(0) NULL DEFAULT 0,
  `tipo_notificacion` int(0) NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for corte_nivel_ejecutivo_calidad
-- ----------------------------
DROP TABLE IF EXISTS `corte_nivel_ejecutivo_calidad`;
CREATE TABLE `corte_nivel_ejecutivo_calidad`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notaMin` decimal(10, 2) NULL DEFAULT NULL,
  `notaMax` decimal(10, 2) NULL DEFAULT NULL,
  `descripcion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for credenciales_sms
-- ----------------------------
DROP TABLE IF EXISTS `credenciales_sms`;
CREATE TABLE `credenciales_sms`  (
  `id` int(0) NOT NULL,
  `urlEnvio` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `urlConsulta` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contrasena` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `urlSaldo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cron_email
-- ----------------------------
DROP TABLE IF EXISTS `cron_email`;
CREATE TABLE `cron_email`  (
  `id` int(0) NOT NULL,
  `estatus` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for datos_generales_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `datos_generales_reclutamiento`;
CREATE TABLE `datos_generales_reclutamiento`  (
  `IdDatosGenerales` int(0) NOT NULL,
  `IdUsuarioReclutamiento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Rut` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Apellidos` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombres` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Telefono` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FechaNacimiento` date NULL DEFAULT NULL,
  `Correo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Status` int(0) NULL DEFAULT 1
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for datos_personales_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `datos_personales_reclutamiento`;
CREATE TABLE `datos_personales_reclutamiento`  (
  `IdDatosPersonales` int(0) NOT NULL,
  `IdUsuarioReclutamiento` int(0) NULL DEFAULT NULL,
  `Afp` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SistemaSalud` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `UF` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ges` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for descargos
-- ----------------------------
DROP TABLE IF EXISTS `descargos`;
CREATE TABLE `descargos`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `entidad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monto` decimal(10, 2) NULL DEFAULT 0.00,
  `saldo` decimal(10, 2) NULL DEFAULT 0.00,
  `fecha_vencimiento` date NULL DEFAULT NULL,
  `fecha_ingreso` date NULL DEFAULT NULL,
  `procesado_el` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE,
  INDEX `factura`(`factura`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for detalle_envio_sms
-- ----------------------------
DROP TABLE IF EXISTS `detalle_envio_sms`;
CREATE TABLE `detalle_envio_sms`  (
  `id` int(0) NOT NULL,
  `id_envio_sms` int(0) NULL DEFAULT NULL,
  `fono` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rut` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `respuesta` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for detalle_evaluaciones
-- ----------------------------
DROP TABLE IF EXISTS `detalle_evaluaciones`;
CREATE TABLE `detalle_evaluaciones`  (
  `id` int(0) NOT NULL,
  `Id_Evaluacion` int(0) NULL DEFAULT NULL,
  `Descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Esperado` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Ponderacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observacion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `resumen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for detalle_grupos_evaluaciones
-- ----------------------------
DROP TABLE IF EXISTS `detalle_grupos_evaluaciones`;
CREATE TABLE `detalle_grupos_evaluaciones`  (
  `id` int(0) NOT NULL,
  `Id_Grupo` int(0) NULL DEFAULT NULL,
  `Id_Evaluacion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for deuda
-- ----------------------------
DROP TABLE IF EXISTS `deuda`;
CREATE TABLE `deuda`  (
  `Id_deuda` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT 0,
  `Deuda` decimal(20, 2) NULL DEFAULT 0.00,
  `Saldo` decimal(20, 2) NULL DEFAULT 0.00,
  `Saldo_Dia` decimal(20, 2) NULL DEFAULT 0.00,
  `oferta` decimal(20, 2) NULL DEFAULT 0.00,
  `desc_put` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `oferta_put` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `valor_cuota_ap` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `desc_con` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `oferta_con` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `cuotas_con` int(0) NOT NULL DEFAULT 0,
  `abono_con` decimal(18, 2) NOT NULL DEFAULT 0.00,
  `Entidad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Convenio` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Asignacion` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Comprobante` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Agente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Estado` enum('ALTA','BAJA') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'ALTA',
  `Tipo_Factura` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Factura` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Emision` date NULL DEFAULT NULL,
  `Fecha_Vencimiento` date NULL DEFAULT NULL,
  `Tramo_Doc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tramo_Antiguo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Rechazo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cartera` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `cta_cte` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Marca_Externa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cobrador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_asignacion` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_descargo` date NULL DEFAULT NULL,
  `fecha_operacion` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`Id_deuda`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE,
  INDEX `Rut_Cartera`(`Rut`, `Cartera`, `Factura`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for deuda_convenio
-- ----------------------------
DROP TABLE IF EXISTS `deuda_convenio`;
CREATE TABLE `deuda_convenio`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `cedente_id` int(0) NOT NULL DEFAULT 5,
  `rut` int(0) NOT NULL,
  `saldo` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `monto_con` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `n_coutas` smallint(0) NOT NULL DEFAULT 0,
  `valor_cuota` decimal(19, 2) NOT NULL DEFAULT 0.00,
  `vencimiento` date NOT NULL,
  `tramo_mora` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mes` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `n_cuotas_pagadas` smallint(0) NOT NULL DEFAULT 0,
  `n_cuotas_pendientes` smallint(0) NOT NULL DEFAULT 0,
  `riesgo` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cta_cte` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activa` tinyint(0) NOT NULL DEFAULT 1,
  `creado_el` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_el` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `rut`(`rut`) USING BTREE,
  INDEX `cedente_id`(`cedente_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dias_para_cumplimiento_agendamiento
-- ----------------------------
DROP TABLE IF EXISTS `dias_para_cumplimiento_agendamiento`;
CREATE TABLE `dias_para_cumplimiento_agendamiento`  (
  `Dias_Para_Agendamiento` int(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dias_para_cumplimiento_compromiso
-- ----------------------------
DROP TABLE IF EXISTS `dias_para_cumplimiento_compromiso`;
CREATE TABLE `dias_para_cumplimiento_compromiso`  (
  `Dias_Para_Cumplimiento_Compromiso` int(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dias_sin_gestion
-- ----------------------------
DROP TABLE IF EXISTS `dias_sin_gestion`;
CREATE TABLE `dias_sin_gestion`  (
  `Dias_Sin_Gestion` int(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dimensiones_competencias_calidad
-- ----------------------------
DROP TABLE IF EXISTS `dimensiones_competencias_calidad`;
CREATE TABLE `dimensiones_competencias_calidad`  (
  `id` int(0) NOT NULL,
  `id_competencia` int(0) NULL DEFAULT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ponderacion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for direccion_factura
-- ----------------------------
DROP TABLE IF EXISTS `direccion_factura`;
CREATE TABLE `direccion_factura`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `cedente_id` int(0) NULL DEFAULT NULL,
  `factura` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rut` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `region` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comuna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_ingreso` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `factura_id_2`(`factura`, `rut`, `cedente_id`) USING BTREE,
  INDEX `factura_id`(`factura`) USING BTREE,
  INDEX `cedente_id`(`cedente_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for direcciones
-- ----------------------------
DROP TABLE IF EXISTS `direcciones`;
CREATE TABLE `direcciones`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `cedente_id` int(0) NULL DEFAULT NULL,
  `rut` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `region` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comuna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ciudad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_ingreso` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `factura_id_2`(`rut`, `cedente_id`) USING BTREE,
  INDEX `cedente_id`(`cedente_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for direcciones_cedente
-- ----------------------------
DROP TABLE IF EXISTS `direcciones_cedente`;
CREATE TABLE `direcciones_cedente`  (
  `id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Complemento_Direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Codigo_postal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Comuna` int(0) NULL DEFAULT NULL,
  `Id_Provincia` int(0) NULL DEFAULT NULL,
  `Id_Region` int(0) NULL DEFAULT NULL,
  `Id_Tipo_Demografico` int(0) NULL DEFAULT NULL,
  `Fecha_Ingreso` date NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Comuna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ciudad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Origen` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `jose` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for direcciones_tmp
-- ----------------------------
DROP TABLE IF EXISTS `direcciones_tmp`;
CREATE TABLE `direcciones_tmp`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Complemento_Direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Codigo_postal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Comuna` int(0) NULL DEFAULT NULL,
  `Id_Provincia` int(0) NULL DEFAULT NULL,
  `Id_Region` int(0) NULL DEFAULT NULL,
  `Id_Tipo_Demografico` int(0) NULL DEFAULT NULL,
  `Fecha_Ingreso` date NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Comuna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ciudad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Origen` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `jose` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for distribuidores_speech
-- ----------------------------
DROP TABLE IF EXISTS `distribuidores_speech`;
CREATE TABLE `distribuidores_speech`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for domicilio_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `domicilio_reclutamiento`;
CREATE TABLE `domicilio_reclutamiento`  (
  `IdDomicilio` int(0) NOT NULL,
  `IdUsuarioReclutamiento` int(0) NULL DEFAULT NULL,
  `Direccion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Region` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ciudad` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Comuna` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ee_empresa_externa
-- ----------------------------
DROP TABLE IF EXISTS `ee_empresa_externa`;
CREATE TABLE `ee_empresa_externa`  (
  `idEmpresaExterna` int(0) NOT NULL,
  `nombre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telefono` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `direccion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ejecutivo_carga_sistema
-- ----------------------------
DROP TABLE IF EXISTS `ejecutivo_carga_sistema`;
CREATE TABLE `ejecutivo_carga_sistema`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `nombre_carga` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nombre que viene en la carga que se sube al sistema',
  `nombre_sistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nombre que tiene el usuario en el sistema',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `Nombres`(`nombre_carga`, `nombre_sistema`) USING BTREE COMMENT 'Para que no se dupliquen registros',
  UNIQUE INDEX `Nombre_carga`(`nombre_carga`) USING BTREE COMMENT 'Para evitar que un nombre de la carga tenga diferentes usuarios en el sistema'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for email
-- ----------------------------
DROP TABLE IF EXISTS `email`;
CREATE TABLE `email`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Marca` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'Asignación',
  `Observacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_ingreso` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `UK_Rut_Email`(`Rut`, `Email`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for empresa_externa
-- ----------------------------
DROP TABLE IF EXISTS `empresa_externa`;
CREATE TABLE `empresa_externa`  (
  `IdEmpresaExterna` int(0) NOT NULL,
  `Nombre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Correo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Direccion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `IdCedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for empresas_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `empresas_reclutamiento`;
CREATE TABLE `empresas_reclutamiento`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for envio_email
-- ----------------------------
DROP TABLE IF EXISTS `envio_email`;
CREATE TABLE `envio_email`  (
  `id` int(0) NOT NULL,
  `estrategia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `asunto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `offset` int(0) NULL DEFAULT NULL,
  `status` smallint(0) NULL DEFAULT NULL,
  `actualizacion` datetime(0) NULL DEFAULT NULL,
  `adjuntar` smallint(0) NULL DEFAULT NULL,
  `reenvio` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `tabla_email` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `continuar` int(0) NULL DEFAULT NULL,
  `FechaProceso` datetime(0) NULL DEFAULT NULL,
  `template` int(0) NULL DEFAULT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for envio_sms
-- ----------------------------
DROP TABLE IF EXISTS `envio_sms`;
CREATE TABLE `envio_sms`  (
  `id` int(0) NOT NULL,
  `asignacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `sms` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tabla_fonos` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `fechaHora` datetime(0) NULL DEFAULT NULL,
  `colores` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `template` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for errores_criticos_calidad
-- ----------------------------
DROP TABLE IF EXISTS `errores_criticos_calidad`;
CREATE TABLE `errores_criticos_calidad`  (
  `id` int(0) NOT NULL,
  `Descripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for estado_eventos
-- ----------------------------
DROP TABLE IF EXISTS `estado_eventos`;
CREATE TABLE `estado_eventos`  (
  `ID` int(0) NOT NULL,
  `STATUS` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `EVENT_NAME` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `EVENT_TYPE` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `INTERVAL_VALUE` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `INTERVAL_FIELD` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `STARTS` datetime(0) NULL DEFAULT NULL,
  `LAST_EXECUTED` datetime(0) NULL DEFAULT NULL,
  `USER_LAST_EXECUTED` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FINAL_VIEW` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for estado_eventos_procedures
-- ----------------------------
DROP TABLE IF EXISTS `estado_eventos_procedures`;
CREATE TABLE `estado_eventos_procedures`  (
  `ID` int(0) NOT NULL,
  `ID_EVENT` int(0) NULL DEFAULT NULL,
  `PROCEDURE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ORDEN` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for estatus_aspirante_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `estatus_aspirante_reclutamiento`;
CREATE TABLE `estatus_aspirante_reclutamiento`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for estrategias_cola_asignacion
-- ----------------------------
DROP TABLE IF EXISTS `estrategias_cola_asignacion`;
CREATE TABLE `estrategias_cola_asignacion`  (
  `id` int(0) NOT NULL,
  `id_estrategia` int(0) NULL DEFAULT NULL,
  `id_cola` int(0) NULL DEFAULT NULL,
  `id_asignacion` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluaciones
-- ----------------------------
DROP TABLE IF EXISTS `evaluaciones`;
CREATE TABLE `evaluaciones`  (
  `id` int(0) NOT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `Id_Grabacion` int(0) NULL DEFAULT NULL,
  `Evaluacion_Final` decimal(5, 2) NULL DEFAULT NULL,
  `Fecha_Evaluacion` datetime(0) NULL DEFAULT NULL,
  `Aspectos_Fortalecer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Aspectos_Corregir` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Compromiso_Ejecutivo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `byCalidadSystem` smallint(0) NULL DEFAULT 0,
  `byCalidadMandante` smallint(0) NULL DEFAULT 0,
  `byEjecutivoSystem` smallint(0) NULL DEFAULT 0,
  `byEjecutivoMandante` smallint(0) NULL DEFAULT 0,
  `bySupervisorSystem` smallint(0) NULL DEFAULT 0,
  `bySupervisorMandante` smallint(0) NULL DEFAULT 0,
  `lastEvaluation` int(0) NULL DEFAULT 1,
  `errorCritico` int(0) NULL DEFAULT 0,
  `id_errorCritico` int(0) NULL DEFAULT 0,
  `observacion` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `supervisor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluaciones_auditoria_calidad
-- ----------------------------
DROP TABLE IF EXISTS `evaluaciones_auditoria_calidad`;
CREATE TABLE `evaluaciones_auditoria_calidad`  (
  `id` int(0) NOT NULL,
  `idAuditoria` int(0) NULL DEFAULT NULL,
  `competencia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notaCompetencia` decimal(11, 2) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluaciones_coaching_calidad
-- ----------------------------
DROP TABLE IF EXISTS `evaluaciones_coaching_calidad`;
CREATE TABLE `evaluaciones_coaching_calidad`  (
  `id` int(0) NOT NULL,
  `Id_Objecion` int(0) NULL DEFAULT NULL,
  `Id_Grabacion` int(0) NULL DEFAULT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Estado` int(0) NOT NULL DEFAULT 0 COMMENT '1 => Roll Play; 2 => Pasillo; 3 => Online; 4 => Seguimiento',
  `Fecha` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluaciones_entregadas_calidad
-- ----------------------------
DROP TABLE IF EXISTS `evaluaciones_entregadas_calidad`;
CREATE TABLE `evaluaciones_entregadas_calidad`  (
  `id` int(0) NOT NULL,
  `Id_Evaluacion` int(0) NULL DEFAULT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Entregada` int(0) NULL DEFAULT 1,
  `Aceptada` int(0) NULL DEFAULT 0,
  `fechaEntrega` datetime(0) NULL DEFAULT NULL,
  `fechaAceptacion` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluaciones_semanas_calidad
-- ----------------------------
DROP TABLE IF EXISTS `evaluaciones_semanas_calidad`;
CREATE TABLE `evaluaciones_semanas_calidad`  (
  `id` int(0) NOT NULL,
  `Mes` int(0) NULL DEFAULT NULL,
  `Ano` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `cantEvaluaciones` int(0) NULL DEFAULT NULL,
  `semanaAno` int(0) NULL DEFAULT NULL,
  `semanaMes` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for evaluaciones_status_objecion_calidad
-- ----------------------------
DROP TABLE IF EXISTS `evaluaciones_status_objecion_calidad`;
CREATE TABLE `evaluaciones_status_objecion_calidad`  (
  `id` int(0) NOT NULL,
  `Id_Objecion` int(0) NULL DEFAULT NULL,
  `Id_Grabacion` int(0) NULL DEFAULT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Estado` int(0) NOT NULL DEFAULT 0 COMMENT '1 => Objetada; 2 => Aprobada',
  `Fecha` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for exclusion_telefonos
-- ----------------------------
DROP TABLE IF EXISTS `exclusion_telefonos`;
CREATE TABLE `exclusion_telefonos`  (
  `Numero` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for exclusiones
-- ----------------------------
DROP TABLE IF EXISTS `exclusiones`;
CREATE TABLE `exclusiones`  (
  `id_registr` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Fecha_Inic` date NULL DEFAULT NULL,
  `Fecha_Term` date NULL DEFAULT NULL,
  `Descripcio` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Documento` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo` int(0) NULL DEFAULT NULL,
  `Dato` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for facturas_detalle
-- ----------------------------
DROP TABLE IF EXISTS `facturas_detalle`;
CREATE TABLE `facturas_detalle`  (
  `Id` int(0) NOT NULL,
  `FacturaId` int(0) NULL DEFAULT NULL,
  `Servicio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Valor` decimal(11, 2) NULL DEFAULT NULL,
  `Descuento` float NULL DEFAULT NULL,
  `TipoMoneda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for facturas_inubicables
-- ----------------------------
DROP TABLE IF EXISTS `facturas_inubicables`;
CREATE TABLE `facturas_inubicables`  (
  `id` int(0) NOT NULL,
  `Numero_Factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha` datetime(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_clientes
-- ----------------------------
DROP TABLE IF EXISTS `fe_clientes`;
CREATE TABLE `fe_clientes`  (
  `id` int(0) NOT NULL,
  `rut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `dv` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comuna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telefono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contacto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_empresa` int(0) NULL DEFAULT NULL,
  `giro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_empresas
-- ----------------------------
DROP TABLE IF EXISTS `fe_empresas`;
CREATE TABLE `fe_empresas`  (
  `id` int(0) NOT NULL,
  `rut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_giros
-- ----------------------------
DROP TABLE IF EXISTS `fe_giros`;
CREATE TABLE `fe_giros`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_grupos
-- ----------------------------
DROP TABLE IF EXISTS `fe_grupos`;
CREATE TABLE `fe_grupos`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_monedas
-- ----------------------------
DROP TABLE IF EXISTS `fe_monedas`;
CREATE TABLE `fe_monedas`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_servicios
-- ----------------------------
DROP TABLE IF EXISTS `fe_servicios`;
CREATE TABLE `fe_servicios`  (
  `id` int(0) NOT NULL,
  `rut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_tipo_servicio` int(0) NULL DEFAULT NULL,
  `id_grupo` int(0) NULL DEFAULT NULL,
  `valor` decimal(13, 2) NULL DEFAULT NULL,
  `descuento` float NULL DEFAULT NULL,
  `id_moneda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_tipo_servicios
-- ----------------------------
DROP TABLE IF EXISTS `fe_tipo_servicios`;
CREATE TABLE `fe_tipo_servicios`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_ventas
-- ----------------------------
DROP TABLE IF EXISTS `fe_ventas`;
CREATE TABLE `fe_ventas`  (
  `id` int(0) NOT NULL,
  `rut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_documento` date NULL DEFAULT NULL,
  `estatus` int(0) NULL DEFAULT NULL,
  `numero_documento` int(0) NULL DEFAULT NULL,
  `id_documento_bsale` int(0) NULL DEFAULT NULL,
  `url_pdf_bsale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `informed_sii_bsale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `response_msg_sii_bsale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_creacion` date NULL DEFAULT NULL,
  `hora_creacion` time(0) NULL DEFAULT NULL,
  `tipo_documento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_vencimiento` date NULL DEFAULT NULL,
  `impuesto` float NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_ventas_detalle
-- ----------------------------
DROP TABLE IF EXISTS `fe_ventas_detalle`;
CREATE TABLE `fe_ventas_detalle`  (
  `id` int(0) NOT NULL,
  `venta_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `concepto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `precio` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `total` decimal(13, 2) NULL DEFAULT NULL,
  `id_moneda` int(0) NULL DEFAULT NULL,
  `descuento` decimal(13, 2) NULL DEFAULT NULL,
  `exencion` int(0) NULL DEFAULT NULL,
  `exento` decimal(13, 2) NULL DEFAULT NULL,
  `neto` decimal(13, 2) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fe_ventas_detalle_tmp
-- ----------------------------
DROP TABLE IF EXISTS `fe_ventas_detalle_tmp`;
CREATE TABLE `fe_ventas_detalle_tmp`  (
  `id` int(0) NOT NULL,
  `concepto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `precio` decimal(13, 2) NULL DEFAULT NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `total` decimal(13, 2) NULL DEFAULT NULL,
  `id_moneda` int(0) NULL DEFAULT NULL,
  `descuento` decimal(13, 2) NULL DEFAULT NULL,
  `neto` decimal(13, 2) NULL DEFAULT NULL,
  `exencion` int(0) NULL DEFAULT NULL,
  `exento` decimal(13, 2) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fireconfig
-- ----------------------------
DROP TABLE IF EXISTS `fireconfig`;
CREATE TABLE `fireconfig`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `CodigoFoco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipoSistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cantidadMaxMandantes` int(0) NULL DEFAULT NULL,
  `cantidadMaxCedentes` int(0) NULL DEFAULT NULL,
  `NotaMaximaEvaluacion` int(0) NULL DEFAULT NULL,
  `tipoMenu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IpServidorDiscado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cantidadCorreos` int(0) NULL DEFAULT NULL,
  `sonidoNotificaciones` int(0) NULL DEFAULT 0,
  `IpServidorDiscadoAux` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `logoEmpresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `serverNodePublica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `serverNodePrivada` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `portNode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fono_cob
-- ----------------------------
DROP TABLE IF EXISTS `fono_cob`;
CREATE TABLE `fono_cob`  (
  `id_fono` bigint(0) NOT NULL AUTO_INCREMENT,
  `Rut` int(0) NULL DEFAULT NULL,
  `codigo_pais` int(0) NULL DEFAULT NULL,
  `codigo_area` int(0) NULL DEFAULT NULL,
  `numero_telefono` int(0) NULL DEFAULT NULL,
  `formato_dial` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `formato_subtel` bigint(0) NULL DEFAULT NULL,
  `tipo_fono` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `vigente` bigint(0) NULL DEFAULT 1,
  `fecha_carga` date NULL DEFAULT NULL,
  `cedente` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `discado` int(0) NULL DEFAULT NULL,
  `id_categoria` int(0) NULL DEFAULT NULL,
  `color` int(0) NULL DEFAULT 35,
  `color_ivr` int(0) NULL DEFAULT NULL,
  `cantidad_llamados` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cargo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Prioridad_Fono` int(0) NULL DEFAULT 0,
  PRIMARY KEY (`id_fono`) USING BTREE,
  UNIQUE INDEX `rut_phone`(`formato_subtel`, `Rut`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fonos_correctos
-- ----------------------------
DROP TABLE IF EXISTS `fonos_correctos`;
CREATE TABLE `fonos_correctos`  (
  `IdFonosCorrectos` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Fono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FechaRegistro` date NULL DEFAULT NULL,
  `FechaActualizacion` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fonos_fail_log
-- ----------------------------
DROP TABLE IF EXISTS `fonos_fail_log`;
CREATE TABLE `fonos_fail_log`  (
  `id_fono` bigint(0) NOT NULL AUTO_INCREMENT,
  `Rut` int(0) NULL DEFAULT NULL,
  `codigo_pais` int(0) NULL DEFAULT NULL,
  `codigo_area` int(0) NULL DEFAULT NULL,
  `numero_telefono` int(0) NULL DEFAULT NULL,
  `formato_dial` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `formato_subtel` bigint(0) NULL DEFAULT NULL,
  `tipo_fono` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `score` decimal(5, 2) NULL DEFAULT NULL,
  `vigente` bigint(0) NULL DEFAULT 1,
  `fecha_carga` date NULL DEFAULT NULL,
  `cedente` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `discado` int(0) NULL DEFAULT NULL,
  `id_categoria` int(0) NULL DEFAULT NULL,
  `color` int(0) NULL DEFAULT 35,
  `color_ivr` int(0) NULL DEFAULT NULL,
  `cantidad_llamados` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cargo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Prioridad_Fono` int(0) NULL DEFAULT 0,
  PRIMARY KEY (`id_fono`) USING BTREE,
  UNIQUE INDEX `rut_phone`(`formato_subtel`, `Rut`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fonos_incorrectos
-- ----------------------------
DROP TABLE IF EXISTS `fonos_incorrectos`;
CREATE TABLE `fonos_incorrectos`  (
  `IdFonosIncorrectos` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Fono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FechaRegistro` date NULL DEFAULT NULL,
  `FechaActualizacion` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for funciones_template_carga
-- ----------------------------
DROP TABLE IF EXISTS `funciones_template_carga`;
CREATE TABLE `funciones_template_carga`  (
  `id` int(0) NOT NULL,
  `Codigo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ge_1
-- ----------------------------
DROP TABLE IF EXISTS `ge_1`;
CREATE TABLE `ge_1`  (
  `id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `CodigoEvento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Sede` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FechaCreacion` date NULL DEFAULT NULL,
  `Codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TipoEvento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Auxiliar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Empresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `UsuarioCreacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `RazonSocial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TipoDoc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Folio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Sucursal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion
-- ----------------------------
DROP TABLE IF EXISTS `gestion`;
CREATE TABLE `gestion`  (
  `id_gestion` int(0) NOT NULL,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `duracion` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `origen` int(0) NULL DEFAULT NULL,
  `monto_comp` int(0) NULL DEFAULT 0,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `url_grabacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subrespuesta` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT 0,
  `resultado_n3` int(0) NULL DEFAULT 0,
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p4` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT 0,
  `sox` int(0) NULL DEFAULT 0,
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fechaAgendamiento` datetime(0) NULL DEFAULT NULL,
  `motivobasal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `canales` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_sms` int(0) NULL DEFAULT NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_asignacion
-- ----------------------------
DROP TABLE IF EXISTS `gestion_asignacion`;
CREATE TABLE `gestion_asignacion`  (
  `id_gestion` int(0) NOT NULL,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `duracion` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `origen` int(0) NULL DEFAULT NULL,
  `monto_comp` int(0) NULL DEFAULT 0,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `url_grabacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subrespuesta` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT 0,
  `resultado_n3` int(0) NULL DEFAULT 0,
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p4` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT 0,
  `sox` int(0) NULL DEFAULT 0,
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fechaAgendamiento` datetime(0) NULL DEFAULT NULL,
  `motivobasal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `canales` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_sms` int(0) NULL DEFAULT NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_bot
-- ----------------------------
DROP TABLE IF EXISTS `gestion_bot`;
CREATE TABLE `gestion_bot`  (
  `id` int(0) NOT NULL,
  `Rut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cola` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_llamada` int(0) NULL DEFAULT NULL,
  `duracion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(6) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_correo
-- ----------------------------
DROP TABLE IF EXISTS `gestion_correo`;
CREATE TABLE `gestion_correo`  (
  `id_gestion` int(0) NOT NULL,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `correos` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `facturas` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `template` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado` int(0) NULL DEFAULT NULL,
  `id_envio` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_diaria
-- ----------------------------
DROP TABLE IF EXISTS `gestion_diaria`;
CREATE TABLE `gestion_diaria`  (
  `id_gestion` bigint(0) NOT NULL,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `subrespuesta` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT NULL,
  `resultado_n3` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fono_discado` int(0) NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `Origen` int(0) NULL DEFAULT NULL,
  `id_eje` int(0) NULL DEFAULT NULL,
  `monto_comp` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_ivr
-- ----------------------------
DROP TABLE IF EXISTS `gestion_ivr`;
CREATE TABLE `gestion_ivr`  (
  `id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cola` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_llamada` int(0) NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `estado` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(6) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_positiva_hoy
-- ----------------------------
DROP TABLE IF EXISTS `gestion_positiva_hoy`;
CREATE TABLE `gestion_positiva_hoy`  (
  `Cantidad` int(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Gestion_Positiva_Hoy` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'SI'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_positiva_semana
-- ----------------------------
DROP TABLE IF EXISTS `gestion_positiva_semana`;
CREATE TABLE `gestion_positiva_semana`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Semana_gestion` int(0) NULL DEFAULT NULL,
  `Semana_hoy` int(0) NULL DEFAULT NULL,
  `Mes` int(0) NULL DEFAULT NULL,
  `Ano` int(0) NULL DEFAULT NULL,
  `Cantidad_gestiones` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Estado_Compromiso` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_compromiso` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_refacturada
-- ----------------------------
DROP TABLE IF EXISTS `gestion_refacturada`;
CREATE TABLE `gestion_refacturada`  (
  `id_gestion` int(0) NULL DEFAULT NULL COMMENT 'Relacionado con la tabla gestion_ult_trimestr',
  `monto` double(14, 2) NULL DEFAULT NULL COMMENT 'Monto a refacturar'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_sqlserver
-- ----------------------------
DROP TABLE IF EXISTS `gestion_sqlserver`;
CREATE TABLE `gestion_sqlserver`  (
  `id_gestion` int(0) NOT NULL,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `duracion` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `origen` int(0) NULL DEFAULT NULL,
  `monto_comp` int(0) NULL DEFAULT 0,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `url_grabacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subrespuesta` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT 0,
  `resultado_n3` int(0) NULL DEFAULT 0,
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p4` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT 0,
  `sox` int(0) NULL DEFAULT 0,
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fechaAgendamiento` datetime(0) NULL DEFAULT NULL,
  `motivobasal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `canales` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_sms` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_ult_trimestre
-- ----------------------------
DROP TABLE IF EXISTS `gestion_ult_trimestre`;
CREATE TABLE `gestion_ult_trimestre`  (
  `id_gestion` int(0) NOT NULL AUTO_INCREMENT,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(0) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `duracion` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `origen` int(0) NULL DEFAULT NULL,
  `monto_comp` int(0) NULL DEFAULT 0,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `url_grabacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subrespuesta` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT 0,
  `resultado_n3` int(0) NULL DEFAULT 0,
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n4` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `p4` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT 0,
  `sox` int(0) NULL DEFAULT 0,
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fechaAgendamiento` datetime(0) NULL DEFAULT NULL,
  `motivobasal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `canales` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_sms` int(0) NULL DEFAULT NULL,
  `area` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COBRANZA',
  `cod_campaign` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cod_list` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `dst` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `dst_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_gestion`) USING BTREE,
  INDEX `rut_cliente`(`rut_cliente`) USING BTREE,
  INDEX `cedente`(`cedente`) USING BTREE,
  INDEX `nombre_ejecutivo`(`nombre_ejecutivo`) USING BTREE,
  INDEX `cod_campaign`(`cod_campaign`) USING BTREE,
  INDEX `Id_TipoGestion`(`Id_TipoGestion`) USING BTREE,
  INDEX `n1`(`n1`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_vicidial
-- ----------------------------
DROP TABLE IF EXISTS `gestion_vicidial`;
CREATE TABLE `gestion_vicidial`  (
  `id_gestion` bigint(0) NOT NULL,
  `rut_cliente` int(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `fechahora` datetime(0) NOT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `subrespuesta` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT NULL,
  `resultado_n3` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fono_discado` int(0) NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NOT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `Origen` int(0) NOT NULL,
  `id_eje` int(0) NOT NULL,
  `monto_comp` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestion_vicidial_dia
-- ----------------------------
DROP TABLE IF EXISTS `gestion_vicidial_dia`;
CREATE TABLE `gestion_vicidial_dia`  (
  `id_gestion` bigint(0) NOT NULL,
  `rut_cliente` int(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `fechahora` datetime(0) NOT NULL,
  `resultado` int(0) NULL DEFAULT NULL,
  `subrespuesta` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `resultado_n2` int(0) NULL DEFAULT NULL,
  `resultado_n3` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fono_discado` int(0) NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NOT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `Origen` int(0) NOT NULL,
  `id_eje` int(0) NOT NULL,
  `monto_comp` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gestiones_voicebot
-- ----------------------------
DROP TABLE IF EXISTS `gestiones_voicebot`;
CREATE TABLE `gestiones_voicebot`  (
  `RUT` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CONTACTO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MONEDA` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COD_EMPRESA` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FECHA_HORA_GESTION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TIPO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CONTACTABILIDAD` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `RESULTADO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COMENTARIO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FECHA_COMPROMISO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MONTO_COMPROMISO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MTODEUMOMGES` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `NOMBRE_CLI` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FECHA_GESTION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FONO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `RUT_USUARIO_ECE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MAIL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DIRECCION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CIUDAD` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `REGION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  INDEX `RUT`(`RUT`) USING BTREE,
  INDEX `FECHA_GESTION`(`FECHA_GESTION`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grabacion_2
-- ----------------------------
DROP TABLE IF EXISTS `grabacion_2`;
CREATE TABLE `grabacion_2`  (
  `id` int(0) NOT NULL,
  `Nombre_Grabacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha` date NULL DEFAULT NULL,
  `Cartera` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Telefono` int(0) NULL DEFAULT NULL,
  `Estado` int(0) NULL DEFAULT NULL,
  `url_grabacion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_gestion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grabacion_2_his
-- ----------------------------
DROP TABLE IF EXISTS `grabacion_2_his`;
CREATE TABLE `grabacion_2_his`  (
  `id` int(0) NOT NULL,
  `Nombre_Grabacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha` date NULL DEFAULT NULL,
  `Cartera` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Telefono` int(0) NULL DEFAULT NULL,
  `Estado` int(0) NULL DEFAULT NULL,
  `url_grabacion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_gestion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grupo_estrategia
-- ----------------------------
DROP TABLE IF EXISTS `grupo_estrategia`;
CREATE TABLE `grupo_estrategia`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grupo_servicio
-- ----------------------------
DROP TABLE IF EXISTS `grupo_servicio`;
CREATE TABLE `grupo_servicio`  (
  `IdGrupo` int(0) NOT NULL,
  `Nombre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grupo_usuario
-- ----------------------------
DROP TABLE IF EXISTS `grupo_usuario`;
CREATE TABLE `grupo_usuario`  (
  `id` int(0) NOT NULL,
  `id_grupo` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grupos
-- ----------------------------
DROP TABLE IF EXISTS `grupos`;
CREATE TABLE `grupos`  (
  `IdGrupo` int(0) NOT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IdCedente` int(0) NULL DEFAULT NULL,
  `cola` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grupos_empresas
-- ----------------------------
DROP TABLE IF EXISTS `grupos_empresas`;
CREATE TABLE `grupos_empresas`  (
  `IdGrupPersEmpr` int(0) NOT NULL,
  `IdGrupo` int(0) NULL DEFAULT NULL,
  `IdEmpresaExterna` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grupos_personas
-- ----------------------------
DROP TABLE IF EXISTS `grupos_personas`;
CREATE TABLE `grupos_personas`  (
  `IdGrupPersEmpr` int(0) NOT NULL,
  `IdGrupo` int(0) NULL DEFAULT NULL,
  `Rut` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for heavy_process
-- ----------------------------
DROP TABLE IF EXISTS `heavy_process`;
CREATE TABLE `heavy_process`  (
  `id` int(0) NOT NULL,
  `proceso` int(0) NOT NULL,
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `Id_Cedente` int(0) NOT NULL DEFAULT 0
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for historico_carga
-- ----------------------------
DROP TABLE IF EXISTS `historico_carga`;
CREATE TABLE `historico_carga`  (
  `id` int(0) NOT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `fecha` datetime(0) NULL DEFAULT NULL,
  `fecha_fin` datetime(0) NULL DEFAULT NULL,
  `Cant_Ruts` int(0) NULL DEFAULT NULL,
  `Deuda_Total` double NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for homologacion_tipocontacto
-- ----------------------------
DROP TABLE IF EXISTS `homologacion_tipocontacto`;
CREATE TABLE `homologacion_tipocontacto`  (
  `id` int(0) NOT NULL,
  `Gestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for homologacion_tipogestion_nivel3
-- ----------------------------
DROP TABLE IF EXISTS `homologacion_tipogestion_nivel3`;
CREATE TABLE `homologacion_tipogestion_nivel3`  (
  `id` int(0) NOT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `resultado_n3` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for java_process
-- ----------------------------
DROP TABLE IF EXISTS `java_process`;
CREATE TABLE `java_process`  (
  `id` int(0) NOT NULL,
  `process_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for java_process_live
-- ----------------------------
DROP TABLE IF EXISTS `java_process_live`;
CREATE TABLE `java_process_live`  (
  `id` int(0) NOT NULL,
  `id_process` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `status` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date` datetime(0) NULL DEFAULT NULL,
  `fileName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `errorMessage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for load_configs
-- ----------------------------
DROP TABLE IF EXISTS `load_configs`;
CREATE TABLE `load_configs`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `cedente_id` int(0) NULL DEFAULT NULL,
  `aplica_cedentes` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `columnas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tabla_destino` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `campos_destino` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `reinicia_tabla` tinyint(0) NULL DEFAULT 1,
  `activa` tinyint(0) NULL DEFAULT 1,
  `creada_el` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cedente_id`(`cedente_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for load_files
-- ----------------------------
DROP TABLE IF EXISTS `load_files`;
CREATE TABLE `load_files`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `cedente_id` int(0) NOT NULL,
  `config_id` int(0) NULL DEFAULT NULL,
  `load_id` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `hojas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `columnas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `relacion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `segmentacion_inicial` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `resultados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ejecutor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ruta_archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ruta_ejecutor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `procesado` tinyint(0) NULL DEFAULT 0,
  `asignado` tinyint(0) NULL DEFAULT 0,
  `active` tinyint(0) NULL DEFAULT 1,
  `registros` int(0) NULL DEFAULT 0,
  `creado_el` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cedente_id`(`cedente_id`, `archivo`, `ejecutor`) USING BTREE,
  INDEX `load_id`(`load_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_eventos
-- ----------------------------
DROP TABLE IF EXISTS `log_eventos`;
CREATE TABLE `log_eventos`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Hora` datetime(0) NULL DEFAULT NULL,
  `Evento` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_fallidos_login
-- ----------------------------
DROP TABLE IF EXISTS `log_fallidos_login`;
CREATE TABLE `log_fallidos_login`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `fecha` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_modulo
-- ----------------------------
DROP TABLE IF EXISTS `log_modulo`;
CREATE TABLE `log_modulo`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `fecha` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_menu` int(0) NULL DEFAULT NULL,
  `ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_sistema
-- ----------------------------
DROP TABLE IF EXISTS `log_sistema`;
CREATE TABLE `log_sistema`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Fecha` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `operacion` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_registro` int(0) NULL DEFAULT NULL,
  `tabla` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `query` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 277 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_triggers
-- ----------------------------
DROP TABLE IF EXISTS `log_triggers`;
CREATE TABLE `log_triggers`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `evento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `usuario` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for logo
-- ----------------------------
DROP TABLE IF EXISTS `logo`;
CREATE TABLE `logo`  (
  `id_logo` int(0) NOT NULL,
  `logo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipoSistema` int(0) NULL DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_logo`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mail
-- ----------------------------
DROP TABLE IF EXISTS `mail`;
CREATE TABLE `mail`  (
  `id_mail` int(0) NOT NULL,
  `correo_electronico` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `vigente` bigint(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `id_cliente` bigint(0) NULL DEFAULT NULL,
  `fecha_ingreso` date NULL DEFAULT NULL,
  `Origen` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Cargo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo_Uso` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Marca` int(0) NULL DEFAULT NULL,
  `Observacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activo` int(0) NULL DEFAULT NULL
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mail_campaigns
-- ----------------------------
DROP TABLE IF EXISTS `mail_campaigns`;
CREATE TABLE `mail_campaigns`  (
  `id` bigint(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` tinyint(1) NOT NULL DEFAULT 0,
  `emailResponse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NULL DEFAULT NULL COMMENT 'fecha cuando se programa el envio del correo.',
  `template_id` bigint(0) UNSIGNED NULL DEFAULT NULL,
  `status` enum('CARGADA','PAUSADO','PROCESANDO','COMPLETADO','ELIMINADO','ERROR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CARGADA' COMMENT 'CARGADA,PAUSADO,PROCESANDO,COMPLETADO,ELIMINADO,ERROR',
  `statistics` json NULL COMMENT '{\"campania\": 1011,\"pendientes\":1,\"enviados\":268,\"entregados\":0,\"erroneos\":1,\"error\":8,\"total\":269,\"enviados_percent\":100,\"entregados_percent\":0}',
  `statusMessage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Orden de iniciar desde el front,Pausado por orden del front,Pausado por fuera de horario',
  `unsubcribe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `isDeleted` tinyint(1) NULL DEFAULT 0,
  `idCedente` int(0) NULL DEFAULT NULL,
  `idMandante` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mail_data_emails
-- ----------------------------
DROP TABLE IF EXISTS `mail_data_emails`;
CREATE TABLE `mail_data_emails`  (
  `id` bigint(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `identity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customVariables` json NOT NULL,
  `campaign_id` bigint(0) UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attems` int(0) NOT NULL DEFAULT 1,
  `isValid` tinyint(1) NOT NULL DEFAULT 1,
  `isSent` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'si fue enviado',
  `sentId` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'ID del correo enviado, que retorna el servidor',
  `sentDate` timestamp(0) NULL DEFAULT NULL COMMENT 'Fecha y hora del envio del correo',
  `serverSent` int(0) NULL DEFAULT NULL COMMENT 'ID del servidor por el que fue enviado el correo',
  `inQueue` enum('0','1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `inQueueFrom` timestamp(0) NULL DEFAULT NULL,
  `wasOpened` tinyint(1) NOT NULL DEFAULT 0,
  `openedCount` int(0) NULL DEFAULT NULL,
  `dateOpened` timestamp(0) NULL DEFAULT NULL,
  `dataOpened` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `requestUnsubcribe` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'si el destinatario solicitó darse de baja',
  `requestUnsubcribeDate` timestamp(0) NULL DEFAULT NULL COMMENT 'cuando hizo la solicitud de baja',
  `error` tinyint(1) NULL DEFAULT NULL,
  `errorMessage` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `data_emails_campaign_id_foreign`(`campaign_id`) USING BTREE,
  CONSTRAINT `data_emails_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `mail_campaigns` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 701987 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mail_templates
-- ----------------------------
DROP TABLE IF EXISTS `mail_templates`;
CREATE TABLE `mail_templates`  (
  `id` bigint(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `html_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `json_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `urlPreview` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `base64Image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `customVariables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `enable` tinyint(1) NOT NULL DEFAULT 1,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `idCedente` bigint(0) NULL DEFAULT NULL,
  `idMandante` bigint(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mandante
-- ----------------------------
DROP TABLE IF EXISTS `mandante`;
CREATE TABLE `mandante`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Empieza` smallint(0) NULL DEFAULT NULL,
  `estatus` int(0) NULL DEFAULT 1,
  `have360Evaluation` int(0) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mandante_cedente
-- ----------------------------
DROP TABLE IF EXISTS `mandante_cedente`;
CREATE TABLE `mandante_cedente`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Meta` int(0) NULL DEFAULT NULL,
  `Lista_Vicidial` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activo` int(0) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE,
  INDEX `Id_Mandante`(`Id_Mandante`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_bot
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_bot`;
CREATE TABLE `mantenedor_bot`  (
  `id` int(0) NOT NULL,
  `horaInicio` time(0) NULL DEFAULT NULL,
  `horaFin` time(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `cantidad` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_correo
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_correo`;
CREATE TABLE `mantenedor_correo`  (
  `id` int(0) NOT NULL,
  `horaInicio` time(6) NULL DEFAULT NULL,
  `horaFin` time(6) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `cantidad` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_evaluaciones
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_evaluaciones`;
CREATE TABLE `mantenedor_evaluaciones`  (
  `id` int(0) NOT NULL,
  `resumen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Esperado` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Ponderacion` decimal(5, 2) NULL DEFAULT NULL,
  `id_resumen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_perfil` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_ivr
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_ivr`;
CREATE TABLE `mantenedor_ivr`  (
  `id` int(0) NOT NULL,
  `horaInicio` time(0) NULL DEFAULT NULL,
  `horaFin` time(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `cantidad` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_servicios
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_servicios`;
CREATE TABLE `mantenedor_servicios`  (
  `IdServicio` int(0) NOT NULL,
  `servicio` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_sms
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_sms`;
CREATE TABLE `mantenedor_sms`  (
  `id` int(0) NOT NULL,
  `horaInicio` time(6) NULL DEFAULT NULL,
  `horaFin` time(6) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL,
  `costoSMS` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cantidad` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_telefonia
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_telefonia`;
CREATE TABLE `mantenedor_telefonia`  (
  `id` int(0) NOT NULL,
  `costoTelefonia` int(0) NULL DEFAULT NULL,
  `tipo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_tipo_factura
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_tipo_factura`;
CREATE TABLE `mantenedor_tipo_factura`  (
  `id` int(0) NOT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mantenedor_uf
-- ----------------------------
DROP TABLE IF EXISTS `mantenedor_uf`;
CREATE TABLE `mantenedor_uf`  (
  `id` int(0) NOT NULL,
  `mes` int(0) NULL DEFAULT NULL,
  `valor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for marcas_carga
-- ----------------------------
DROP TABLE IF EXISTS `marcas_carga`;
CREATE TABLE `marcas_carga`  (
  `id` int(0) NOT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CampoUpdate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ValorUpdate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CampoRelacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ValorRelacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for medio_pago_cedente
-- ----------------------------
DROP TABLE IF EXISTS `medio_pago_cedente`;
CREATE TABLE `medio_pago_cedente`  (
  `id` int(0) NOT NULL,
  `medio_pago` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion`;
CREATE TABLE `mejor_gestion`  (
  `Rut` int(0) NOT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `Fecha_Gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `Respuesta_N1` int(0) NULL DEFAULT NULL,
  `Respuesta_N2` int(0) NULL DEFAULT NULL,
  `Respuesta_N3` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Fec_Compromiso` date NULL DEFAULT NULL,
  `Fono_Gestion` int(0) NULL DEFAULT NULL,
  `Ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Monto_Compromiso` int(0) NULL DEFAULT NULL,
  `Tipo_Contacto` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_cedente
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_cedente`;
CREATE TABLE `mejor_gestion_cedente`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `monto_comp` decimal(20, 0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_cedente_mes
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_cedente_mes`;
CREATE TABLE `mejor_gestion_cedente_mes`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `monto_comp` decimal(20, 0) NULL DEFAULT NULL,
  `Fecha_Actualizacion` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_cedente_temp
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_cedente_temp`;
CREATE TABLE `mejor_gestion_cedente_temp`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `monto_comp` decimal(20, 0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_cedente_temp_backup
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_cedente_temp_backup`;
CREATE TABLE `mejor_gestion_cedente_temp_backup`  (
  `Rut` int(0) NOT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL,
  `Nombre_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_empresa
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_empresa`;
CREATE TABLE `mejor_gestion_empresa`  (
  `Rut` int(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Dias_Desde_ult_Contacto` int(0) NULL DEFAULT NULL,
  `Tramo_Ult_Contacto` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Tipo_Contacto` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_historica
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_historica`;
CREATE TABLE `mejor_gestion_historica`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `monto_comp` int(0) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_historica_temp
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_historica_temp`;
CREATE TABLE `mejor_gestion_historica_temp`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `monto_comp` int(0) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_mes
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_mes`;
CREATE TABLE `mejor_gestion_mes`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `monto_comp` int(0) NULL DEFAULT 0,
  `n1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Id_Cedente` int(0) NOT NULL,
  `fec_compromiso` date NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_periodo
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_periodo`;
CREATE TABLE `mejor_gestion_periodo`  (
  `Rut` int(0) NOT NULL,
  `fechahora` datetime(0) NOT NULL,
  `Fecha_Gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `Respuesta_N1` int(0) NULL DEFAULT NULL,
  `Respuesta_N2` int(0) NULL DEFAULT NULL,
  `Respuesta_N3` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Fec_Compromiso` date NULL DEFAULT NULL,
  `Fono_Gestion` int(0) NULL DEFAULT NULL,
  `Ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Monto_Compromiso` int(0) NULL DEFAULT NULL,
  `Tipo_Contacto` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_periodo_temp1
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_periodo_temp1`;
CREATE TABLE `mejor_gestion_periodo_temp1`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mejor_gestion_periodo_tmp
-- ----------------------------
DROP TABLE IF EXISTS `mejor_gestion_periodo_tmp`;
CREATE TABLE `mejor_gestion_periodo_tmp`  (
  `rut_cliente` int(0) NOT NULL,
  `fechahora` datetime(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `Respuesta_N1` int(0) NULL DEFAULT NULL,
  `Respuesta_N2` int(0) NULL DEFAULT NULL,
  `Respuesta_N3` int(0) NULL DEFAULT NULL,
  `cedente` int(0) NOT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `fono_discado` int(0) NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monto_comp` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mensajes
-- ----------------------------
DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE `mensajes`  (
  `id` int(0) NOT NULL,
  `mensaje` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `timestamp` datetime(0) NULL DEFAULT NULL,
  `status` int(0) NULL DEFAULT NULL,
  `tiempo_ejecucion` bigint(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_menu` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `enlace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `permisos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activo` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `padre` int(0) NULL DEFAULT NULL,
  `tipoSistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `adminCedente` int(0) NULL DEFAULT NULL,
  `prioridad` int(0) NULL DEFAULT NULL,
  `tipoMenu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 179 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu_roles
-- ----------------------------
DROP TABLE IF EXISTS `menu_roles`;
CREATE TABLE `menu_roles`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_menu` int(0) NULL DEFAULT NULL,
  `id_rol` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_menu`(`id_menu`) USING BTREE,
  INDEX `id_rol`(`id_rol`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 601 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for modulos_plan_accion
-- ----------------------------
DROP TABLE IF EXISTS `modulos_plan_accion`;
CREATE TABLE `modulos_plan_accion`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_competencia` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for msj_campaign_sms
-- ----------------------------
DROP TABLE IF EXISTS `msj_campaign_sms`;
CREATE TABLE `msj_campaign_sms`  (
  `id` bigint(0) NOT NULL AUTO_INCREMENT,
  `identity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `quantity` int(0) NULL DEFAULT NULL,
  `preview` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mensaje de muestra. ( lo que escribieron en el textarea, tal cual)',
  `status` enum('CARGADO','PROCESANDO','PAUSADO','COMPLETADO','ERROR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'CARGADO' COMMENT '\"CARGADO\",\"PROCESANDO\",\"PAUSADO\",\"COMPLETADO\",\"ERROR\"',
  `isDeleted` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `idCedente` int(0) NOT NULL,
  `idMandante` int(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for msj_data_sms
-- ----------------------------
DROP TABLE IF EXISTS `msj_data_sms`;
CREATE TABLE `msj_data_sms`  (
  `id` bigint(0) NOT NULL AUTO_INCREMENT,
  `identity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cant_sms` int(0) NULL DEFAULT NULL,
  `customVariables` json NULL,
  `campaign_sms_id` bigint(0) NOT NULL,
  `sending_date` datetime(0) NULL DEFAULT NULL COMMENT 'Fecha de envio',
  `sent_date` datetime(0) NULL DEFAULT NULL COMMENT 'Fecha de entrega',
  `operator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nombre de la compañia ( telefonica,claro,entel...)',
  `message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'ID del mensaje',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'El mensaje formateado con la información',
  `quantity` int(0) NULL DEFAULT NULL COMMENT 'Cantidad de caracteres',
  `special_characters` tinyint(1) NULL DEFAULT 0 COMMENT 'Si contiene caracteres especiales',
  `status` enum('CARGADO','ENVIADO','ENTREGADO','NO ENTREGADO','ERROR') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'CARGADO' COMMENT '\"CARGADO\",\"ENVIADO\",\"ENTREGADO\",\"NO ENTREGADO\",\"ERROR\"',
  `isDeleted` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_phone`(`phone`) USING BTREE,
  INDEX `fk_identity`(`identity`) USING BTREE,
  INDEX `fk_campaign_sms_id`(`campaign_sms_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3135 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for nivel1
-- ----------------------------
DROP TABLE IF EXISTS `nivel1`;
CREATE TABLE `nivel1`  (
  `Id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Respuesta_N1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  UNIQUE INDEX `unq_id_cedente`(`Id`, `Id_Cedente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for nivel2
-- ----------------------------
DROP TABLE IF EXISTS `nivel2`;
CREATE TABLE `nivel2`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Nivel1` int(0) NULL DEFAULT NULL,
  `Respuesta_N2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nivel` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `Codigo` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for nivel3
-- ----------------------------
DROP TABLE IF EXISTS `nivel3`;
CREATE TABLE `nivel3`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Nivel2` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `Respuesta_N3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `P1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `P2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `P3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `P4` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for nivel4
-- ----------------------------
DROP TABLE IF EXISTS `nivel4`;
CREATE TABLE `nivel4`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Nivel3` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `Respuesta_N4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `P1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `P2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `P3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `P4` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Id_Nivel2`(`Id_Nivel3`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for numeros_transferencias_crm
-- ----------------------------
DROP TABLE IF EXISTS `numeros_transferencias_crm`;
CREATE TABLE `numeros_transferencias_crm`  (
  `id` int(0) NOT NULL,
  `Descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Numero` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for objeciones_calidad
-- ----------------------------
DROP TABLE IF EXISTS `objeciones_calidad`;
CREATE TABLE `objeciones_calidad`  (
  `id` int(0) NOT NULL,
  `id_grabacion` int(0) NULL DEFAULT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `id_mandante` int(0) NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `Objecion` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notaObjetada` decimal(10, 2) NULL DEFAULT NULL,
  `fechaObjecion` datetime(0) NULL DEFAULT NULL,
  `tipo_comentario` int(0) NULL DEFAULT 0,
  `visto` int(0) NULL DEFAULT 0,
  `notificacionVisible` int(0) NULL DEFAULT 1
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for objeciones_calidad_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `objeciones_calidad_usuarios`;
CREATE TABLE `objeciones_calidad_usuarios`  (
  `id` int(0) NOT NULL,
  `id_objecion` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ofertas
-- ----------------------------
DROP TABLE IF EXISTS `ofertas`;
CREATE TABLE `ofertas`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cliente` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cluster` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `riesgo` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deuda_total` decimal(10, 0) NULL DEFAULT NULL,
  `mora` decimal(10, 0) NULL DEFAULT NULL,
  `put_a_pagar` decimal(10, 0) NULL DEFAULT NULL,
  `put_descuento` decimal(10, 0) NULL DEFAULT NULL,
  `cuotas_a_pagar` decimal(10, 0) NULL DEFAULT NULL,
  `cuotas_descuento` decimal(10, 0) NULL DEFAULT NULL,
  `cuotas_maximas` int(0) NULL DEFAULT NULL,
  `fecha_creacion` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario_creacion` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `rut`(`rut`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for omnicanalidad
-- ----------------------------
DROP TABLE IF EXISTS `omnicanalidad`;
CREATE TABLE `omnicanalidad`  (
  `id` int(0) NOT NULL,
  `rut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_gestion` int(0) NULL DEFAULT NULL,
  `canales` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `prioridades` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for opciones_afirmaciones_competencias_calidad
-- ----------------------------
DROP TABLE IF EXISTS `opciones_afirmaciones_competencias_calidad`;
CREATE TABLE `opciones_afirmaciones_competencias_calidad`  (
  `id` int(0) NOT NULL,
  `id_afirmacion` int(0) NULL DEFAULT NULL,
  `nombre` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `valor` decimal(10, 2) NULL DEFAULT NULL,
  `descripcion_caracteristica` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for opciones_campos_gestion
-- ----------------------------
DROP TABLE IF EXISTS `opciones_campos_gestion`;
CREATE TABLE `opciones_campos_gestion`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_campo` int(0) NULL DEFAULT NULL,
  `Prioridad` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Seleccionado` int(0) NULL DEFAULT NULL,
  `corrreo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Campo utilizado en derivacion',
  `correo_supervisor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Campo utilizado en derivacion',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for opciones_campos_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `opciones_campos_reclutamiento`;
CREATE TABLE `opciones_campos_reclutamiento`  (
  `id` int(0) NOT NULL,
  `id_campo` int(0) NULL DEFAULT NULL,
  `Prioridad` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Seleccionado` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for opciones_preguntas_competencias_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `opciones_preguntas_competencias_reclutamiento`;
CREATE TABLE `opciones_preguntas_competencias_reclutamiento`  (
  `id` int(0) NOT NULL,
  `opcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ponderacion` int(0) NULL DEFAULT NULL,
  `id_pregunta` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for opciones_preguntas_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `opciones_preguntas_reclutamiento`;
CREATE TABLE `opciones_preguntas_reclutamiento`  (
  `id` int(0) NOT NULL,
  `opcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ponderacion` decimal(11, 2) NULL DEFAULT NULL,
  `id_pregunta` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for orden_campos_gestion
-- ----------------------------
DROP TABLE IF EXISTS `orden_campos_gestion`;
CREATE TABLE `orden_campos_gestion`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Prioridad` int(0) NULL DEFAULT NULL,
  `id_campo` int(0) NULL DEFAULT NULL,
  `Anchura` int(0) NULL DEFAULT NULL,
  `Cedente` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for orden_campos_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `orden_campos_reclutamiento`;
CREATE TABLE `orden_campos_reclutamiento`  (
  `id` int(0) NOT NULL,
  `Prioridad` int(0) NULL DEFAULT NULL,
  `id_campo` int(0) NULL DEFAULT NULL,
  `Anchura` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for orden_notas_aspirantes_excel_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `orden_notas_aspirantes_excel_reclutamiento`;
CREATE TABLE `orden_notas_aspirantes_excel_reclutamiento`  (
  `id` int(0) NOT NULL,
  `id_campo` int(0) NULL DEFAULT NULL,
  `Prioridad` int(0) NULL DEFAULT NULL,
  `Titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for origenes_gestiones
-- ----------------------------
DROP TABLE IF EXISTS `origenes_gestiones`;
CREATE TABLE `origenes_gestiones`  (
  `id` int(0) NOT NULL,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pa_contador_gestiones
-- ----------------------------
DROP TABLE IF EXISTS `pa_contador_gestiones`;
CREATE TABLE `pa_contador_gestiones`  (
  `id` int(0) NOT NULL,
  `Fono` int(0) NOT NULL,
  `Rut` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int(0) NOT NULL,
  `Cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pagos
-- ----------------------------
DROP TABLE IF EXISTS `pagos`;
CREATE TABLE `pagos`  (
  `Id_Pagos` bigint(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Fecha_Pago` date NULL DEFAULT NULL,
  `Monto_Pago` bigint(0) NULL DEFAULT NULL,
  `Fecha_Ingreso_Sistema` date NULL DEFAULT NULL,
  `Sucursal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo_Pago` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Numero_Operacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Numero_Factura` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Pagos`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pagos_deudas
-- ----------------------------
DROP TABLE IF EXISTS `pagos_deudas`;
CREATE TABLE `pagos_deudas`  (
  `id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Numero_Operacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Numero_Factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Monto` int(0) NULL DEFAULT NULL,
  `Fecha_Pago` date NULL DEFAULT NULL,
  `Mandante` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Tipo_Pago` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cartera` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_vencimiento` date NULL DEFAULT NULL,
  `Dias_vencimiento` int(0) NULL DEFAULT NULL,
  `Tramo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mejor_gestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ejecutivo_mejor_gestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_mejor_gestion` date NULL DEFAULT NULL,
  `Fec_compromiso` date NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `Fecha_emision` date NULL DEFAULT NULL,
  `Cedente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fechahora_mejor_gestion` time(0) NULL DEFAULT NULL,
  `Castigo` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nro_Doc_Pago` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Monto_Total_Pago` int(0) NULL DEFAULT NULL,
  `Cobrador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pagos_deudas_errados
-- ----------------------------
DROP TABLE IF EXISTS `pagos_deudas_errados`;
CREATE TABLE `pagos_deudas_errados`  (
  `id` int(0) NOT NULL,
  `Fecha_Carga` datetime(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Numero_Operacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Numero_Factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_emision` date NULL DEFAULT NULL,
  `Monto` double NULL DEFAULT NULL,
  `Fecha_Pago` date NULL DEFAULT NULL,
  `Mandante` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Tipo_Pago` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cartera` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_vencimiento` date NULL DEFAULT NULL,
  `Dias_vencimiento` int(0) NULL DEFAULT NULL,
  `Tramo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mejor_gestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Ejecutivo_mejor_gestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_mejor_gestion` date NULL DEFAULT NULL,
  `Fec_compromiso` date NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pagos_deudas_tmp
-- ----------------------------
DROP TABLE IF EXISTS `pagos_deudas_tmp`;
CREATE TABLE `pagos_deudas_tmp`  (
  `id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Numero_Operacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Numero_Factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Monto` double NULL DEFAULT NULL,
  `Fecha_Pago` date NULL DEFAULT NULL,
  `Mandante` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Tipo_Pago` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cartera` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nro_Doc_Pago` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Monto_Total_Pago` int(0) NULL DEFAULT NULL,
  `Cobrador` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pagos_nuevos
-- ----------------------------
DROP TABLE IF EXISTS `pagos_nuevos`;
CREATE TABLE `pagos_nuevos`  (
  `id` int(0) NOT NULL,
  `empresa` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COL 2` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nro` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COL 5` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monto` int(0) NULL DEFAULT NULL,
  `COL 7` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COL 8` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COL 9` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `saldo` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `rut` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pais
-- ----------------------------
DROP TABLE IF EXISTS `pais`;
CREATE TABLE `pais`  (
  `Id_Pais` int(0) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`Id_Pais`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for palabras_claves_transcripcion_speech
-- ----------------------------
DROP TABLE IF EXISTS `palabras_claves_transcripcion_speech`;
CREATE TABLE `palabras_claves_transcripcion_speech`  (
  `id` int(0) NOT NULL,
  `id_palabra` int(0) NULL DEFAULT NULL,
  `id_transcripcion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for palabras_speech
-- ----------------------------
DROP TABLE IF EXISTS `palabras_speech`;
CREATE TABLE `palabras_speech`  (
  `id` int(0) NOT NULL,
  `NombreMetrica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Grupo` int(0) NULL DEFAULT NULL,
  `ValorMetrica` int(0) NULL DEFAULT NULL,
  `PesoGrupo` int(0) NULL DEFAULT NULL,
  `Veces` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for patrones_personalidad_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `patrones_personalidad_reclutamiento`;
CREATE TABLE `patrones_personalidad_reclutamiento`  (
  `id` int(0) NOT NULL,
  `patronNumero` int(0) NULL DEFAULT NULL,
  `patronTexto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for perfil_personal
-- ----------------------------
DROP TABLE IF EXISTS `perfil_personal`;
CREATE TABLE `perfil_personal`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for perfiles_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `perfiles_reclutamiento`;
CREATE TABLE `perfiles_reclutamiento`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_empresa` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for periodo_cedente
-- ----------------------------
DROP TABLE IF EXISTS `periodo_cedente`;
CREATE TABLE `periodo_cedente`  (
  `id` int(0) NOT NULL,
  `Cedente` int(0) NULL DEFAULT NULL,
  `Fecha_Inicio` date NULL DEFAULT NULL,
  `Fecha_Termino` date NULL DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for periodo_gestion
-- ----------------------------
DROP TABLE IF EXISTS `periodo_gestion`;
CREATE TABLE `periodo_gestion`  (
  `Cedente` int(0) NOT NULL,
  `Fecha_Inicio` date NULL DEFAULT NULL,
  `Fecha_Termino` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for periodo_gestion_cedente
-- ----------------------------
DROP TABLE IF EXISTS `periodo_gestion_cedente`;
CREATE TABLE `periodo_gestion_cedente`  (
  `id_periodo_cedente` int(0) NOT NULL,
  `Cedente` int(0) NULL DEFAULT NULL,
  `Fecha_Inicio` date NULL DEFAULT NULL,
  `Fecha_Termino` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for periodo_mandante
-- ----------------------------
DROP TABLE IF EXISTS `periodo_mandante`;
CREATE TABLE `periodo_mandante`  (
  `ID` int(0) NOT NULL,
  `Mandante` int(0) NULL DEFAULT NULL,
  `Fecha_Inicio` date NULL DEFAULT NULL,
  `Fecha_Termino` date NULL DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for persona
-- ----------------------------
DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona`  (
  `id_persona` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Rut` int(0) NOT NULL,
  `Digito_Verificador` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Completo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Sexo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Nacimiento` date NULL DEFAULT NULL,
  `Fecha_Ingreso` date NULL DEFAULT NULL,
  `edad` int(0) NULL DEFAULT 1,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `con_deudas` tinyint(0) NULL DEFAULT 1,
  `Mandante` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo_rut` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `prioridad` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `probabilidad` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `vertical` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `judicial_externa` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `convenio_pago` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comportamiento_pagos` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_persona`) USING BTREE,
  UNIQUE INDEX `rut_cedente`(`Rut`, `Digito_Verificador`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for persona_historico
-- ----------------------------
DROP TABLE IF EXISTS `persona_historico`;
CREATE TABLE `persona_historico`  (
  `Rut` int(0) NOT NULL,
  `Digito_Verificador` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Completo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Sexo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Nacimiento` date NULL DEFAULT NULL,
  `Fecha_Ingreso` date NULL DEFAULT NULL,
  `id_persona` int(0) NOT NULL,
  `edad` int(0) NULL DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mandante` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for persona_periodo
-- ----------------------------
DROP TABLE IF EXISTS `persona_periodo`;
CREATE TABLE `persona_periodo`  (
  `Rut` int(0) NOT NULL,
  `Digito_Verificador` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Completo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Sexo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Nacimiento` date NULL DEFAULT NULL,
  `Fecha_Ingreso` date NULL DEFAULT NULL,
  `id` int(0) NOT NULL,
  `edad` int(0) NULL DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mandante` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for persona_tmp
-- ----------------------------
DROP TABLE IF EXISTS `persona_tmp`;
CREATE TABLE `persona_tmp`  (
  `Rut` int(0) NOT NULL,
  `Digito_Verificador` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Completo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Sexo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Nacimiento` date NULL DEFAULT NULL,
  `Fecha_Ingreso` date NULL DEFAULT NULL,
  `edad` int(0) NULL DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mandante` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for personaempresa
-- ----------------------------
DROP TABLE IF EXISTS `personaempresa`;
CREATE TABLE `personaempresa`  (
  `id` int(0) NOT NULL,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `giro` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `direccion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `correo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contacto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comentario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telefono` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comuna` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo_cliente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IdUsuarioSession` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for personal
-- ----------------------------
DROP TABLE IF EXISTS `personal`;
CREATE TABLE `personal`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Personal` int(0) NOT NULL,
  `Nombre_Usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre_dial` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Rut` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DV` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Nacimiento` date NULL DEFAULT NULL,
  `Sexo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `edad` int(0) NULL DEFAULT NULL,
  `hijos` int(0) NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fono_particular` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fono_movil` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_ingreso` date NULL DEFAULT NULL,
  `fecha_termino` date NULL DEFAULT NULL,
  `estado_contrato` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_estatus_egreso` int(0) NULL DEFAULT NULL,
  `AFP` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sistema_salud` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `UF` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `GES` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pensionado` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_nacionalidad` int(0) NULL DEFAULT NULL,
  `id_contrato` int(0) NULL DEFAULT NULL,
  `id_cargo` int(0) NULL DEFAULT NULL,
  `id_grupo` int(0) NULL DEFAULT NULL,
  `id_sueldo` int(0) NULL DEFAULT NULL,
  `id_jornada` int(0) NULL DEFAULT NULL,
  `id_estado` int(0) NULL DEFAULT NULL,
  `id_comuna` int(0) NULL DEFAULT NULL,
  `id_tipo_ejecutivo` int(0) NULL DEFAULT NULL,
  `id_perfil` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL COMMENT '0',
  `id_antiguedad` int(0) NULL DEFAULT NULL,
  `id_sexo` int(0) NULL DEFAULT NULL,
  `id_sucursal` int(0) NULL DEFAULT 0,
  `Activo` int(0) NULL DEFAULT 1,
  `remuneracion` float NULL DEFAULT NULL,
  `id_supervisor` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Id_Personal`(`Id_Personal`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for plan_accion_ejecutivo
-- ----------------------------
DROP TABLE IF EXISTS `plan_accion_ejecutivo`;
CREATE TABLE `plan_accion_ejecutivo`  (
  `id` int(0) NOT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `id_competencia` int(0) NULL DEFAULT NULL,
  `id_modulo` int(0) NULL DEFAULT NULL,
  `id_topico` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for politica_cedente
-- ----------------------------
DROP TABLE IF EXISTS `politica_cedente`;
CREATE TABLE `politica_cedente`  (
  `id` int(0) NOT NULL,
  `politica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for prefijofonospaises
-- ----------------------------
DROP TABLE IF EXISTS `prefijofonospaises`;
CREATE TABLE `prefijofonospaises`  (
  `id` int(0) NOT NULL,
  `id_pais` int(0) NULL DEFAULT NULL,
  `prefijo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fonoLength` int(0) NULL DEFAULT NULL,
  `lengthOperation` int(0) NULL DEFAULT NULL COMMENT '1.- Igual\r\n2.- Menor o Igual\r\n3.- Mayor o Igual'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for preguntas_competencias_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `preguntas_competencias_reclutamiento`;
CREATE TABLE `preguntas_competencias_reclutamiento`  (
  `id` int(0) NOT NULL,
  `pregunta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_competencia` int(0) NULL DEFAULT NULL,
  `opciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for preguntas_personalidad_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `preguntas_personalidad_reclutamiento`;
CREATE TABLE `preguntas_personalidad_reclutamiento`  (
  `id` int(0) NOT NULL,
  `pregunta` int(0) NULL DEFAULT NULL,
  `Opcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Valor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for preguntas_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `preguntas_reclutamiento`;
CREATE TABLE `preguntas_reclutamiento`  (
  `id` int(0) NOT NULL,
  `pregunta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `calf_minima` decimal(11, 2) NULL DEFAULT NULL,
  `id_perfil` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for provincia
-- ----------------------------
DROP TABLE IF EXISTS `provincia`;
CREATE TABLE `provincia`  (
  `Id_Provincia` int(0) NOT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Region` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for pruebas_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `pruebas_reclutamiento`;
CREATE TABLE `pruebas_reclutamiento`  (
  `id` int(0) NOT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `id_empresa` int(0) NULL DEFAULT NULL,
  `id_perfil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_tipotest` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `status` int(0) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for queriesactualizaciones
-- ----------------------------
DROP TABLE IF EXISTS `queriesactualizaciones`;
CREATE TABLE `queriesactualizaciones`  (
  `id` int(0) NOT NULL,
  `Query` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `TipoQuery` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CampoClave` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ValorClave` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Status` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for querys_segmentaciones
-- ----------------------------
DROP TABLE IF EXISTS `querys_segmentaciones`;
CREATE TABLE `querys_segmentaciones`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cedente_id` int(0) UNSIGNED NOT NULL,
  `segmentacion_id` int(0) UNSIGNED NOT NULL,
  `guuid` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentos` int(0) UNSIGNED NOT NULL DEFAULT 0,
  `facturas` int(0) UNSIGNED NOT NULL DEFAULT 0,
  `deudas` decimal(25, 2) NOT NULL DEFAULT 0.00,
  `saldos` decimal(25, 2) NOT NULL DEFAULT 0.00,
  `query` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_el` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_el` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `segmentacion_id`(`segmentacion_id`) USING BTREE,
  INDEX `cedente_id`(`cedente_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ratio_contactabilidad
-- ----------------------------
DROP TABLE IF EXISTS `ratio_contactabilidad`;
CREATE TABLE `ratio_contactabilidad`  (
  `id` int(0) NOT NULL,
  `id_tipo_contacto` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ratio_penetracion
-- ----------------------------
DROP TABLE IF EXISTS `ratio_penetracion`;
CREATE TABLE `ratio_penetracion`  (
  `id` int(0) NOT NULL,
  `id_tipo_contacto` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ratios_cola
-- ----------------------------
DROP TABLE IF EXISTS `ratios_cola`;
CREATE TABLE `ratios_cola`  (
  `id` int(0) NOT NULL,
  `id_cola` int(0) NULL DEFAULT NULL,
  `id_ratio` int(0) NULL DEFAULT NULL,
  `porcentaje` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ratios_mantenedor
-- ----------------------------
DROP TABLE IF EXISTS `ratios_mantenedor`;
CREATE TABLE `ratios_mantenedor`  (
  `id` int(0) NOT NULL,
  `ratio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `muestra` int(0) NULL DEFAULT NULL,
  `tiempo_act` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ratios_tipo_contacto
-- ----------------------------
DROP TABLE IF EXISTS `ratios_tipo_contacto`;
CREATE TABLE `ratios_tipo_contacto`  (
  `id` int(0) NOT NULL,
  `id_ratio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_tipo_contacto` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rc_totalgestionesejecutivoshora
-- ----------------------------
DROP TABLE IF EXISTS `rc_totalgestionesejecutivoshora`;
CREATE TABLE `rc_totalgestionesejecutivoshora`  (
  `id` int(0) NOT NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `usuario` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `hora` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for region
-- ----------------------------
DROP TABLE IF EXISTS `region`;
CREATE TABLE `region`  (
  `Id_Region` int(0) NOT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relacion
-- ----------------------------
DROP TABLE IF EXISTS `relacion`;
CREATE TABLE `relacion`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `RUT`(`rut`, `dv`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relacion_08jun
-- ----------------------------
DROP TABLE IF EXISTS `relacion_08jun`;
CREATE TABLE `relacion_08jun`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `RUT`(`rut`, `dv`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relacion_18m
-- ----------------------------
DROP TABLE IF EXISTS `relacion_18m`;
CREATE TABLE `relacion_18m`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `RUT`(`rut`, `dv`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relacion_2
-- ----------------------------
DROP TABLE IF EXISTS `relacion_2`;
CREATE TABLE `relacion_2`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `RUT`(`rut`, `dv`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relacion_24m
-- ----------------------------
DROP TABLE IF EXISTS `relacion_24m`;
CREATE TABLE `relacion_24m`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `RUT`(`rut`, `dv`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relacion_noviembre
-- ----------------------------
DROP TABLE IF EXISTS `relacion_noviembre`;
CREATE TABLE `relacion_noviembre`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NULL DEFAULT 5,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `RUT`(`rut`, `dv`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reporteonline
-- ----------------------------
DROP TABLE IF EXISTS `reporteonline`;
CREATE TABLE `reporteonline`  (
  `id_reporte` int(0) NOT NULL,
  `anexo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estatus` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pausa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `inicio` time(6) NULL DEFAULT NULL,
  `termino` time(6) NULL DEFAULT NULL,
  `tiempo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cartera` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activo` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reporteonlinehistorico
-- ----------------------------
DROP TABLE IF EXISTS `reporteonlinehistorico`;
CREATE TABLE `reporteonlinehistorico`  (
  `id_reporte` int(0) NOT NULL,
  `anexo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estatus` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pausa` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tiempo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cartera` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reportesdiarios
-- ----------------------------
DROP TABLE IF EXISTS `reportesdiarios`;
CREATE TABLE `reportesdiarios`  (
  `id` bigint(0) NOT NULL,
  `rutAsignados` int(0) NULL DEFAULT NULL,
  `rutGestionados` int(0) NULL DEFAULT NULL,
  `rutNoGestionados` int(0) NULL DEFAULT NULL,
  `totalGestiones` int(0) NULL DEFAULT NULL,
  `telefonicas` int(0) NULL DEFAULT NULL,
  `sms` int(0) NULL DEFAULT NULL,
  `mail` int(0) NULL DEFAULT NULL,
  `fechaControl` date NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `compromisos` int(0) NULL DEFAULT NULL,
  `cumplimientoEsperado` int(0) NULL DEFAULT NULL,
  `metaRecupero` int(0) NULL DEFAULT NULL,
  `tasaCumplimientoCompromiso` int(0) NULL DEFAULT NULL,
  `tasaCumplimientoEsperado` int(0) NULL DEFAULT NULL,
  `contactados` int(0) NULL DEFAULT NULL,
  `noContactados` int(0) NULL DEFAULT NULL,
  `totalConFono` int(0) NULL DEFAULT NULL,
  `totalSinFono` int(0) NULL DEFAULT NULL,
  `totalConMail` int(0) NULL DEFAULT NULL,
  `totalSinMail` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reportesmensuales
-- ----------------------------
DROP TABLE IF EXISTS `reportesmensuales`;
CREATE TABLE `reportesmensuales`  (
  `id` int(0) NOT NULL,
  `tasaCumplimientoMensual` decimal(11, 0) NULL DEFAULT NULL,
  `mes` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fechaRegistro` date NULL DEFAULT NULL,
  `idCedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for reportessemanales
-- ----------------------------
DROP TABLE IF EXISTS `reportessemanales`;
CREATE TABLE `reportessemanales`  (
  `id` bigint(0) NOT NULL,
  `rutAsignados` int(0) NULL DEFAULT NULL,
  `rutGestionados` int(0) NULL DEFAULT NULL,
  `rutnoGestionados` int(0) NULL DEFAULT NULL,
  `contactados` int(0) NULL DEFAULT NULL,
  `numeroSemanaDelmes` int(0) NULL DEFAULT NULL,
  `numeroMes` int(0) NULL DEFAULT NULL,
  `fechaRegistro` date NULL DEFAULT NULL,
  `tasaCumplimientoCompromiso` int(0) NULL DEFAULT NULL,
  `tasaCumplimientoEsperado` int(0) NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `tele` int(0) NULL DEFAULT NULL,
  `sms` int(0) NULL DEFAULT NULL,
  `mail` int(0) NULL DEFAULT NULL,
  `noContactados` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for requerimiento
-- ----------------------------
DROP TABLE IF EXISTS `requerimiento`;
CREATE TABLE `requerimiento`  (
  `id_requerimiento` int(0) NOT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `modulo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `descripcion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `prioridad` int(0) NULL DEFAULT NULL,
  `usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuesta_gestion
-- ----------------------------
DROP TABLE IF EXISTS `respuesta_gestion`;
CREATE TABLE `respuesta_gestion`  (
  `Id_Respuesta` int(0) NOT NULL,
  `Gestion_Nivel_1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Gestion_Nivel_2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Gestion_Nivel_3` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado` int(0) NULL DEFAULT NULL,
  `Id_TipoContacto` int(0) NULL DEFAULT NULL,
  `Ponderacion` int(0) NULL DEFAULT NULL,
  `Gestion_Agrupada` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuesta_opciones_afirmaciones_calidad
-- ----------------------------
DROP TABLE IF EXISTS `respuesta_opciones_afirmaciones_calidad`;
CREATE TABLE `respuesta_opciones_afirmaciones_calidad`  (
  `Id_Evaluacion` int(0) NULL DEFAULT NULL,
  `id_afirmacion` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL,
  `Valor` decimal(10, 2) NULL DEFAULT NULL,
  `Nota` decimal(10, 2) NULL DEFAULT NULL,
  `id` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuesta_rapida
-- ----------------------------
DROP TABLE IF EXISTS `respuesta_rapida`;
CREATE TABLE `respuesta_rapida`  (
  `id_Respuesta_Rapida` int(0) NOT NULL,
  `Respuesta_Nivel3` int(0) NULL DEFAULT NULL,
  `Id_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuestas_campos_gestion
-- ----------------------------
DROP TABLE IF EXISTS `respuestas_campos_gestion`;
CREATE TABLE `respuestas_campos_gestion`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_campo` int(0) NULL DEFAULT NULL,
  `id_gestion` int(0) NULL DEFAULT NULL,
  `Valor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_opcion_campo` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuestas_campos_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `respuestas_campos_reclutamiento`;
CREATE TABLE `respuestas_campos_reclutamiento`  (
  `id` int(0) NOT NULL,
  `id_campo` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `Valor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_opcion_campo` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuestas_opciones_preguntas_competencias_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `respuestas_opciones_preguntas_competencias_reclutamiento`;
CREATE TABLE `respuestas_opciones_preguntas_competencias_reclutamiento`  (
  `id` int(0) NOT NULL,
  `id_pregunta` int(0) NULL DEFAULT NULL,
  `alto` int(0) NULL DEFAULT NULL,
  `promedio` int(0) NULL DEFAULT NULL,
  `bajo` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `id_prueba` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuestas_opciones_preguntas_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `respuestas_opciones_preguntas_reclutamiento`;
CREATE TABLE `respuestas_opciones_preguntas_reclutamiento`  (
  `id` int(0) NOT NULL,
  `id_pregunta` int(0) NULL DEFAULT NULL,
  `id_opcion` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `fecha` date NULL DEFAULT NULL,
  `id_prueba` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for respuestas_preguntas_personalidad_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `respuestas_preguntas_personalidad_reclutamiento`;
CREATE TABLE `respuestas_preguntas_personalidad_reclutamiento`  (
  `id` int(0) NOT NULL,
  `pregunta` int(0) NULL DEFAULT NULL,
  `opcion` int(0) NULL DEFAULT NULL,
  `respuesta` int(0) NULL DEFAULT NULL,
  `id_usuario` int(0) NULL DEFAULT NULL,
  `id_prueba` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for resultado_patron_personalidad_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `resultado_patron_personalidad_reclutamiento`;
CREATE TABLE `resultado_patron_personalidad_reclutamiento`  (
  `id` int(0) NOT NULL,
  `E` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `M` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `J` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `I` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `S` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `A` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `B` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `T` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SE` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `O1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `O2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `O3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `patronNumber` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_antiguedad
-- ----------------------------
DROP TABLE IF EXISTS `rh_antiguedad`;
CREATE TABLE `rh_antiguedad`  (
  `id_antiguedad` int(0) NOT NULL,
  `antiguedad` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_cargo
-- ----------------------------
DROP TABLE IF EXISTS `rh_cargo`;
CREATE TABLE `rh_cargo`  (
  `id_cargo` int(0) NOT NULL,
  `cargo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_comuna
-- ----------------------------
DROP TABLE IF EXISTS `rh_comuna`;
CREATE TABLE `rh_comuna`  (
  `id_comuna` int(0) NOT NULL,
  `comuna` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_provincia` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_comuna_resp
-- ----------------------------
DROP TABLE IF EXISTS `rh_comuna_resp`;
CREATE TABLE `rh_comuna_resp`  (
  `id_comuna` int(0) NOT NULL,
  `comuna` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_provincia` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_contacto_personal
-- ----------------------------
DROP TABLE IF EXISTS `rh_contacto_personal`;
CREATE TABLE `rh_contacto_personal`  (
  `id_contacto` int(0) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parentesco` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `celular1` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `celular2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_personal` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_estado_civil
-- ----------------------------
DROP TABLE IF EXISTS `rh_estado_civil`;
CREATE TABLE `rh_estado_civil`  (
  `id_estado` int(0) NOT NULL,
  `estado` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_estatus_egreso
-- ----------------------------
DROP TABLE IF EXISTS `rh_estatus_egreso`;
CREATE TABLE `rh_estatus_egreso`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_grupo
-- ----------------------------
DROP TABLE IF EXISTS `rh_grupo`;
CREATE TABLE `rh_grupo`  (
  `id_grupo` int(0) NOT NULL,
  `grupo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_horas
-- ----------------------------
DROP TABLE IF EXISTS `rh_horas`;
CREATE TABLE `rh_horas`  (
  `id_horas` int(0) NOT NULL,
  `horas` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_nacionalidad
-- ----------------------------
DROP TABLE IF EXISTS `rh_nacionalidad`;
CREATE TABLE `rh_nacionalidad`  (
  `id_nacionalidad` int(0) NOT NULL,
  `nacionalidad` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_provincia
-- ----------------------------
DROP TABLE IF EXISTS `rh_provincia`;
CREATE TABLE `rh_provincia`  (
  `id_provincia` int(0) NOT NULL,
  `provincia` varchar(23) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_region` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_provincia_resp
-- ----------------------------
DROP TABLE IF EXISTS `rh_provincia_resp`;
CREATE TABLE `rh_provincia_resp`  (
  `id_provincia` int(0) NOT NULL,
  `provincia` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_region` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_region
-- ----------------------------
DROP TABLE IF EXISTS `rh_region`;
CREATE TABLE `rh_region`  (
  `id_region` int(0) NOT NULL,
  `region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ISO_3166_2_CL` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_region_resp
-- ----------------------------
DROP TABLE IF EXISTS `rh_region_resp`;
CREATE TABLE `rh_region_resp`  (
  `id_region` int(0) NOT NULL,
  `region` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_sexo
-- ----------------------------
DROP TABLE IF EXISTS `rh_sexo`;
CREATE TABLE `rh_sexo`  (
  `id_sexo` int(0) NOT NULL,
  `sexo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_sucursal
-- ----------------------------
DROP TABLE IF EXISTS `rh_sucursal`;
CREATE TABLE `rh_sucursal`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estatus` int(0) NULL DEFAULT 1
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_tipo_contrato
-- ----------------------------
DROP TABLE IF EXISTS `rh_tipo_contrato`;
CREATE TABLE `rh_tipo_contrato`  (
  `id_tipo_contrato` int(0) NOT NULL,
  `contrato` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_tipo_ejecutivo
-- ----------------------------
DROP TABLE IF EXISTS `rh_tipo_ejecutivo`;
CREATE TABLE `rh_tipo_ejecutivo`  (
  `id_tipo_ejecutivo` int(0) NOT NULL,
  `tipo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nota` decimal(11, 2) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_tipo_jornada
-- ----------------------------
DROP TABLE IF EXISTS `rh_tipo_jornada`;
CREATE TABLE `rh_tipo_jornada`  (
  `id_jornada` int(0) NOT NULL,
  `jornada` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rh_tipo_sueldo_base
-- ----------------------------
DROP TABLE IF EXISTS `rh_tipo_sueldo_base`;
CREATE TABLE `rh_tipo_sueldo_base`  (
  `id_sueldo` int(0) NOT NULL,
  `sueldo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nivel` int(0) NULL DEFAULT NULL,
  `activo` int(0) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rut_consulta_gestiones
-- ----------------------------
DROP TABLE IF EXISTS `rut_consulta_gestiones`;
CREATE TABLE `rut_consulta_gestiones`  (
  `Rut` int(0) NOT NULL,
  `Dv` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sac_reclamos_auditorias
-- ----------------------------
DROP TABLE IF EXISTS `sac_reclamos_auditorias`;
CREATE TABLE `sac_reclamos_auditorias`  (
  `id` int(0) NOT NULL,
  `id_c` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipollamada` int(0) NOT NULL,
  `fecha` datetime(0) NOT NULL,
  `comentario` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sac_reclamos_comentarios
-- ----------------------------
DROP TABLE IF EXISTS `sac_reclamos_comentarios`;
CREATE TABLE `sac_reclamos_comentarios`  (
  `id` int(0) NOT NULL,
  `id_c` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` datetime(0) NOT NULL,
  `comentario` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sac_reclamos_gestionados
-- ----------------------------
DROP TABLE IF EXISTS `sac_reclamos_gestionados`;
CREATE TABLE `sac_reclamos_gestionados`  (
  `id` int(0) NOT NULL,
  `id_c` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` datetime(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sac_reclamos_sucursales
-- ----------------------------
DROP TABLE IF EXISTS `sac_reclamos_sucursales`;
CREATE TABLE `sac_reclamos_sucursales`  (
  `id` int(0) NOT NULL,
  `codSucursal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sac_reclamos_tipollamada
-- ----------------------------
DROP TABLE IF EXISTS `sac_reclamos_tipollamada`;
CREATE TABLE `sac_reclamos_tipollamada`  (
  `id` int(0) NOT NULL,
  `cod_tipo` int(0) NOT NULL DEFAULT 0,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sac_subtipificacion
-- ----------------------------
DROP TABLE IF EXISTS `sac_subtipificacion`;
CREATE TABLE `sac_subtipificacion`  (
  `id` int(0) NOT NULL,
  `subTipificaion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `idTipificaion` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sac_tipificaion
-- ----------------------------
DROP TABLE IF EXISTS `sac_tipificaion`;
CREATE TABLE `sac_tipificaion`  (
  `id` int(0) NOT NULL,
  `tipificaion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for script_cedente
-- ----------------------------
DROP TABLE IF EXISTS `script_cedente`;
CREATE TABLE `script_cedente`  (
  `id_script` int(0) NOT NULL AUTO_INCREMENT,
  `script` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_script`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for script_completo_cedente
-- ----------------------------
DROP TABLE IF EXISTS `script_completo_cedente`;
CREATE TABLE `script_completo_cedente`  (
  `id_script` int(0) NOT NULL,
  `script` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for segmentador_params
-- ----------------------------
DROP TABLE IF EXISTS `segmentador_params`;
CREATE TABLE `segmentador_params`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `cedentes` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pivot` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `columns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for servicios
-- ----------------------------
DROP TABLE IF EXISTS `servicios`;
CREATE TABLE `servicios`  (
  `Id` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Grupo` int(0) NULL DEFAULT NULL,
  `TipoFactura` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Valor` decimal(11, 2) NULL DEFAULT NULL,
  `Descuento` decimal(11, 2) NULL DEFAULT NULL,
  `IdServicio` int(0) NULL DEFAULT NULL,
  `TiepoFacturacion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Codigo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `TipoMoneda` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Estatus` int(0) NULL DEFAULT NULL,
  `FechaInstalacion` date NULL DEFAULT NULL,
  `InstaladoPor` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Comentario` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IdUsuarioSession` int(0) NULL DEFAULT NULL,
  `Direccion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Latitud` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Longitud` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Referencia` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Contacto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fono` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FechaComprometidaInstalacion` date NULL DEFAULT NULL,
  `PosibleEstacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `UsuarioPppoeTeorico` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Equipamiento` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `SenalTeorica` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `IdUsuarioAsignado` int(0) NULL DEFAULT NULL,
  `SenalFinal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `EstacionFinal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `UsuarioPppoe` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `EstatusFacturacion` int(0) NULL DEFAULT NULL,
  `CostoInstalacion` decimal(11, 2) NULL DEFAULT NULL,
  `FacturarSinInstalacion` int(0) NULL DEFAULT NULL,
  `CostoInstalacionDescuento` decimal(11, 2) NULL DEFAULT NULL,
  `CostoInstalacionTipoMoneda` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sheet_template_carga
-- ----------------------------
DROP TABLE IF EXISTS `sheet_template_carga`;
CREATE TABLE `sheet_template_carga`  (
  `id` int(0) NOT NULL,
  `id_template` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Sheet` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TipoCarga` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sin_gestion_mes
-- ----------------------------
DROP TABLE IF EXISTS `sin_gestion_mes`;
CREATE TABLE `sin_gestion_mes`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Rut` int(0) NULL DEFAULT NULL,
  `Sin_Gestion_Mes` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'SIN GESTION',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sinonimos_palabras_speech
-- ----------------------------
DROP TABLE IF EXISTS `sinonimos_palabras_speech`;
CREATE TABLE `sinonimos_palabras_speech`  (
  `id` int(0) NOT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_palabra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_categoria_fonos
-- ----------------------------
DROP TABLE IF EXISTS `sis_categoria_fonos`;
CREATE TABLE `sis_categoria_fonos`  (
  `id` int(0) NOT NULL,
  `color` int(0) NULL DEFAULT NULL,
  `tipo_contacto` int(0) NULL DEFAULT NULL,
  `tipo_contacto_query` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `dias` int(0) NULL DEFAULT NULL,
  `cond1` int(0) NULL DEFAULT NULL,
  `cant1` int(0) NULL DEFAULT NULL,
  `logica` int(0) NULL DEFAULT NULL,
  `cond2` int(0) NULL DEFAULT NULL,
  `cant2` int(0) NULL DEFAULT NULL,
  `w` int(0) NULL DEFAULT NULL,
  `color_hex` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `color_nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sel` int(0) NULL DEFAULT 0,
  `tipo_var` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `proceso` int(0) NULL DEFAULT NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `prioridad` int(0) NULL DEFAULT NULL,
  `mundo` int(0) NULL DEFAULT 1
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_colores
-- ----------------------------
DROP TABLE IF EXISTS `sis_colores`;
CREATE TABLE `sis_colores`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comentario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_columnas
-- ----------------------------
DROP TABLE IF EXISTS `sis_columnas`;
CREATE TABLE `sis_columnas`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_tabla` int(0) NULL DEFAULT NULL,
  `columna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `view` int(0) NULL DEFAULT NULL,
  `alias` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `tipo_dato` int(0) NULL DEFAULT NULL,
  `orden` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `logica` int(0) NULL DEFAULT NULL,
  `relacion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nulo` int(0) NULL DEFAULT NULL,
  `nombre_nulo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_tabla`(`id_tabla`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_columnas_estrategias
-- ----------------------------
DROP TABLE IF EXISTS `sis_columnas_estrategias`;
CREATE TABLE `sis_columnas_estrategias`  (
  `id` int(0) NOT NULL,
  `id_tabla` int(0) NULL DEFAULT NULL,
  `Id_Cedente` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `columna` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `view` int(0) NULL DEFAULT NULL,
  `alias` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `tipo_dato` int(0) NULL DEFAULT 0,
  `orden` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `logica` int(0) NULL DEFAULT NULL,
  `relacion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nulo` int(0) NULL DEFAULT NULL,
  `nombre_nulo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cedente` int(0) NULL DEFAULT NULL,
  `suma` int(0) NULL DEFAULT 0,
  `ID_NUEVO` int(0) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID_NUEVO`) USING BTREE,
  INDEX `id_tabla`(`id_tabla`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_estrategias
-- ----------------------------
DROP TABLE IF EXISTS `sis_estrategias`;
CREATE TABLE `sis_estrategias`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `comentario` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(6) NULL DEFAULT NULL,
  `usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `modo_operacion` int(0) NULL DEFAULT NULL,
  `periodicidad` int(0) NULL DEFAULT NULL,
  `grupo` int(0) NULL DEFAULT NULL,
  `estado` int(0) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE,
  INDEX `Id_Usuario`(`Id_Usuario`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1587 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_procesos
-- ----------------------------
DROP TABLE IF EXISTS `sis_procesos`;
CREATE TABLE `sis_procesos`  (
  `id` int(0) NOT NULL,
  `numero` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_querys
-- ----------------------------
DROP TABLE IF EXISTS `sis_querys`;
CREATE TABLE `sis_querys`  (
  `id` int(0) NOT NULL,
  `query` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `id_estrategia` int(0) NULL DEFAULT NULL,
  `id_subquery` int(0) NULL DEFAULT NULL,
  `cantidad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `exportar` int(0) NULL DEFAULT NULL,
  `terminal` int(0) NULL DEFAULT NULL,
  `cola` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `columna` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `condicion` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `matriz` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `matriz_deuda` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `espacios` int(0) NULL DEFAULT NULL,
  `carpeta` int(0) NULL DEFAULT NULL,
  `sub` int(0) NULL DEFAULT NULL,
  `eliminar` int(0) NULL DEFAULT NULL,
  `prioridad` int(0) NULL DEFAULT NULL,
  `comentario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `query_deuda` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `discador` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_querys_activas
-- ----------------------------
DROP TABLE IF EXISTS `sis_querys_activas`;
CREATE TABLE `sis_querys_activas`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `id_estrategia` int(0) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedente` int(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 372 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_querys_estrategias
-- ----------------------------
DROP TABLE IF EXISTS `sis_querys_estrategias`;
CREATE TABLE `sis_querys_estrategias`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `query` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `id_estrategia` int(0) NULL DEFAULT NULL,
  `id_subquery` int(0) NULL DEFAULT NULL,
  `tabla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cantidad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `exportar` int(0) NULL DEFAULT NULL,
  `terminal` int(0) NULL DEFAULT NULL,
  `cola` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `columna` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `condicion` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `matriz` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `matriz_deuda` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `espacios` int(0) NULL DEFAULT NULL,
  `carpeta` int(0) NULL DEFAULT NULL,
  `sub` int(0) NULL DEFAULT NULL,
  `eliminar` int(0) NULL DEFAULT NULL,
  `prioridad` int(0) NULL DEFAULT 0,
  `comentario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `query_deuda` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `discador` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `query_resumen` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `dinamica` int(0) NULL DEFAULT NULL,
  `sistema` int(0) NULL DEFAULT 0,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cautiva` int(0) NULL DEFAULT NULL,
  `idUserCautiva` int(0) NULL DEFAULT NULL,
  `documentos` int(0) NULL DEFAULT NULL,
  `Prioridad_Fono` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ver_agenda` int(0) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 175 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_querys_estrategias2
-- ----------------------------
DROP TABLE IF EXISTS `sis_querys_estrategias2`;
CREATE TABLE `sis_querys_estrategias2`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `query` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `id_estrategia` int(0) NULL DEFAULT NULL,
  `id_subquery` int(0) NULL DEFAULT NULL,
  `cantidad` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `exportar` int(0) NULL DEFAULT NULL,
  `terminal` int(0) NULL DEFAULT NULL,
  `cola` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `columna` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `condicion` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `matriz` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `matriz_deuda` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `espacios` int(0) NULL DEFAULT NULL,
  `carpeta` int(0) NULL DEFAULT NULL,
  `sub` int(0) NULL DEFAULT NULL,
  `eliminar` int(0) NULL DEFAULT NULL,
  `prioridad` int(0) NULL DEFAULT 0,
  `comentario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `query_deuda` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `discador` int(0) NULL DEFAULT NULL,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  `query_resumen` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `dinamica` int(0) NULL DEFAULT NULL,
  `sistema` int(0) NULL DEFAULT 0,
  `color` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cautiva` int(0) NULL DEFAULT NULL,
  `idUserCautiva` int(0) NULL DEFAULT NULL,
  `documentos` int(0) NULL DEFAULT NULL,
  `Prioridad_Fono` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `ver_agenda` int(0) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 65 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_tablas
-- ----------------------------
DROP TABLE IF EXISTS `sis_tablas`;
CREATE TABLE `sis_tablas`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `view` int(0) NULL DEFAULT NULL,
  `relacion` int(0) NULL DEFAULT NULL,
  `Id_Cedente` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tipo` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_tablas_dinamicas
-- ----------------------------
DROP TABLE IF EXISTS `sis_tablas_dinamicas`;
CREATE TABLE `sis_tablas_dinamicas`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `view` int(0) NULL DEFAULT NULL,
  `relacion` int(0) NULL DEFAULT NULL,
  `Id_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sis_tipo_estrategia
-- ----------------------------
DROP TABLE IF EXISTS `sis_tipo_estrategia`;
CREATE TABLE `sis_tipo_estrategia`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sms
-- ----------------------------
DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms`  (
  `RUT` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CONTACTO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MONEDA` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COD_EMPRESA` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FECHA_HORA_GESTION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `TIPO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CONTACTABILIDAD` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `RESULTADO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `COMENTARIO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FECHA_COMPROMISO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MONTO_COMPROMISO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MTODEUMOMGES` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `NOMBRE_CLI` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FECHA_GESTION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `FONO` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `RUT_USUARIO_ECE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MOTIVO MORA` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `MAIL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `DIRECCION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `CIUDAD` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `REGION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sms_template
-- ----------------------------
DROP TABLE IF EXISTS `sms_template`;
CREATE TABLE `sms_template`  (
  `id` bigint(0) NOT NULL,
  `Nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `id_cedente` int(0) NULL DEFAULT NULL,
  `id_usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sucursal
-- ----------------------------
DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE `sucursal`  (
  `Sucursal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Sucursal` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for supervisor
-- ----------------------------
DROP TABLE IF EXISTS `supervisor`;
CREATE TABLE `supervisor`  (
  `id` int(0) NOT NULL,
  `sucursal` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(27) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fono` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rut` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cedente` int(0) NOT NULL DEFAULT 100
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tabs_asignacion_crm
-- ----------------------------
DROP TABLE IF EXISTS `tabs_asignacion_crm`;
CREATE TABLE `tabs_asignacion_crm`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `prioridad` int(0) NULL DEFAULT NULL,
  `tab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `sistema` int(0) NULL DEFAULT NULL,
  `id_tab_sistema` int(0) NULL DEFAULT NULL,
  `activo` int(0) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tabs_sistema_asignacion_crm
-- ----------------------------
DROP TABLE IF EXISTS `tabs_sistema_asignacion_crm`;
CREATE TABLE `tabs_sistema_asignacion_crm`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `tab` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tbl_gestiones_compromisos
-- ----------------------------
DROP TABLE IF EXISTS `tbl_gestiones_compromisos`;
CREATE TABLE `tbl_gestiones_compromisos`  (
  `id_gestion` int(0) NOT NULL COMMENT 'id relacional con la tabla gestion_ult_trimestre',
  `numero_factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'numero de la factura gestionada',
  `saldo_agregado` decimal(20, 2) NOT NULL COMMENT 'saldo de la factura gestionada'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for telefonos_base_unicos
-- ----------------------------
DROP TABLE IF EXISTS `telefonos_base_unicos`;
CREATE TABLE `telefonos_base_unicos`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `telefono` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  UNIQUE INDEX `ik`(`Rut`, `telefono`) USING BTREE,
  INDEX `Rut`(`Rut`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for template_carga
-- ----------------------------
DROP TABLE IF EXISTS `template_carga`;
CREATE TABLE `template_carga`  (
  `id` int(0) NOT NULL,
  `Tipo_Archivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Separador_Cabecero` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `haveHeader` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `NombreTemplate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipo_coaching_calidad
-- ----------------------------
DROP TABLE IF EXISTS `tipo_coaching_calidad`;
CREATE TABLE `tipo_coaching_calidad`  (
  `id` int(0) NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipo_contacto
-- ----------------------------
DROP TABLE IF EXISTS `tipo_contacto`;
CREATE TABLE `tipo_contacto`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_TipoContacto` int(0) NOT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mundo` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipo_exclusiones
-- ----------------------------
DROP TABLE IF EXISTS `tipo_exclusiones`;
CREATE TABLE `tipo_exclusiones`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipo_usuario_sac
-- ----------------------------
DROP TABLE IF EXISTS `tipo_usuario_sac`;
CREATE TABLE `tipo_usuario_sac`  (
  `id` int(0) NOT NULL,
  `cod_tipo` int(0) NOT NULL DEFAULT 0,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipocontacto_evaluaciones_automaticas_calidad
-- ----------------------------
DROP TABLE IF EXISTS `tipocontacto_evaluaciones_automaticas_calidad`;
CREATE TABLE `tipocontacto_evaluaciones_automaticas_calidad`  (
  `id` int(0) NOT NULL,
  `Id_TipoContacto` int(0) NULL DEFAULT NULL,
  `duracionMin` int(0) NULL DEFAULT NULL,
  `duracionMax` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipos_campos_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `tipos_campos_reclutamiento`;
CREATE TABLE `tipos_campos_reclutamiento`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tipos_test_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `tipos_test_reclutamiento`;
CREATE TABLE `tipos_test_reclutamiento`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `time` int(0) NULL DEFAULT NULL,
  `prioridad` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for titular_niveles_cola
-- ----------------------------
DROP TABLE IF EXISTS `titular_niveles_cola`;
CREATE TABLE `titular_niveles_cola`  (
  `id` int(0) NOT NULL,
  `id_cola` int(0) NULL DEFAULT NULL,
  `rut` int(0) NULL DEFAULT NULL,
  `nivel1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nivel2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nivel3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_tipo_gestion` int(0) NULL DEFAULT NULL,
  `fecha_hora` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for topicos_modulos_plan_accion
-- ----------------------------
DROP TABLE IF EXISTS `topicos_modulos_plan_accion`;
CREATE TABLE `topicos_modulos_plan_accion`  (
  `id` int(0) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_modulo` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tramos_cedentes
-- ----------------------------
DROP TABLE IF EXISTS `tramos_cedentes`;
CREATE TABLE `tramos_cedentes`  (
  `id` int(0) NOT NULL,
  `Descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `desde` int(0) NULL DEFAULT NULL,
  `hasta` int(0) NULL DEFAULT NULL,
  `operacion` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for transcripciones_speech
-- ----------------------------
DROP TABLE IF EXISTS `transcripciones_speech`;
CREATE TABLE `transcripciones_speech`  (
  `id` int(0) NOT NULL,
  `Transcripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `id_gestion` int(0) NULL DEFAULT NULL,
  `id_distribuidor` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trazabilidad_rut_cola
-- ----------------------------
DROP TABLE IF EXISTS `trazabilidad_rut_cola`;
CREATE TABLE `trazabilidad_rut_cola`  (
  `Id_Traza` int(0) NOT NULL,
  `Rut` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Cola_Trabajo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Traza` date NULL DEFAULT NULL,
  `Prefijo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trazabilidad_rut_grupo
-- ----------------------------
DROP TABLE IF EXISTS `trazabilidad_rut_grupo`;
CREATE TABLE `trazabilidad_rut_grupo`  (
  `id` int(0) NOT NULL,
  `Rut` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Cola_Trabajo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_Traza` date NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Monto` int(0) NULL DEFAULT NULL,
  `Registros` int(0) NULL DEFAULT NULL,
  `Recupero` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tripulacion
-- ----------------------------
DROP TABLE IF EXISTS `tripulacion`;
CREATE TABLE `tripulacion`  (
  `id` int(0) NOT NULL,
  `sucursal` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(27) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fono` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rut` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cedente` int(0) NOT NULL DEFAULT 100
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion`;
CREATE TABLE `ultima_gestion`  (
  `Rut` int(0) NOT NULL,
  `fechahora` datetime(0) NOT NULL,
  `Fecha_Gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `lista` bigint(0) NULL DEFAULT NULL,
  `Respuesta_N1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Respuesta_N2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Respuesta_N3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NOT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Fec_Compromiso` date NULL DEFAULT NULL,
  `Fono_Gestion` int(0) NULL DEFAULT NULL,
  `Ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Monto_Compromiso` int(0) NULL DEFAULT NULL,
  `Tipo_Contacto` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_compromiso
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_compromiso`;
CREATE TABLE `ultima_gestion_compromiso`  (
  `Rut` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_historica
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_historica`;
CREATE TABLE `ultima_gestion_historica`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `monto_comp` int(0) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_historica_periodo
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_historica_periodo`;
CREATE TABLE `ultima_gestion_historica_periodo`  (
  `Rut` int(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fono_discado` int(0) NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_historica_temp
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_historica_temp`;
CREATE TABLE `ultima_gestion_historica_temp`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fono_discado` int(0) NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_mes
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_mes`;
CREATE TABLE `ultima_gestion_mes`  (
  `Rut` int(0) NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT 0,
  `Peso` int(0) NULL DEFAULT 0,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `factura` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `monto_comp` int(0) NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_sin_contacto
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_sin_contacto`;
CREATE TABLE `ultima_gestion_sin_contacto`  (
  `Rut` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_tercero
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_tercero`;
CREATE TABLE `ultima_gestion_tercero`  (
  `rut_cliente` int(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Dias_Desde_ult_Contacto` int(0) NULL DEFAULT NULL,
  `Tramo_Ult_Contacto` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_creacion` datetime(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_gestion_titular
-- ----------------------------
DROP TABLE IF EXISTS `ultima_gestion_titular`;
CREATE TABLE `ultima_gestion_titular`  (
  `rut_cliente` int(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Dias_Desde_ult_Contacto` int(0) NULL DEFAULT NULL,
  `Tramo_Ult_Contacto` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_creacion` datetime(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultima_mejor_gestion
-- ----------------------------
DROP TABLE IF EXISTS `ultima_mejor_gestion`;
CREATE TABLE `ultima_mejor_gestion`  (
  `rut_cliente` int(0) NOT NULL,
  `Ult_Fecha` datetime(0) NULL DEFAULT NULL,
  `cedente` int(0) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultimo_compromiso
-- ----------------------------
DROP TABLE IF EXISTS `ultimo_compromiso`;
CREATE TABLE `ultimo_compromiso`  (
  `Rut` int(0) NOT NULL,
  `Id_TipoGestion` int(0) NOT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `observacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `fec_compromiso` date NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ultimo_pago
-- ----------------------------
DROP TABLE IF EXISTS `ultimo_pago`;
CREATE TABLE `ultimo_pago`  (
  `Id_Pago` int(0) NOT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `Fecha_Pago` date NULL DEFAULT NULL,
  `Monto_Pago` int(0) NULL DEFAULT NULL,
  `Cedente` int(0) NULL DEFAULT NULL,
  `Sucursal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo_Pago` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `rut` int(0) NULL DEFAULT NULL,
  `dv` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `clave` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nivel` int(0) NULL DEFAULT NULL,
  `cargo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_dial` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pass_dial` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mandante` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sexo` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `idEmpresaExterna` int(0) NULL DEFAULT NULL,
  `anexo_foco` int(0) NULL DEFAULT NULL,
  `INBOUND` int(0) NULL DEFAULT NULL,
  `nivelFactura` int(0) NOT NULL DEFAULT 0,
  `multiServicio` int(0) NOT NULL DEFAULT 0,
  `tipo_usuario_sac` int(0) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios_activos
-- ----------------------------
DROP TABLE IF EXISTS `usuarios_activos`;
CREATE TABLE `usuarios_activos`  (
  `id` int(0) NOT NULL,
  `idUsuario` int(0) NULL DEFAULT NULL,
  `idSocket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `Id_Mandante` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios_cedentes
-- ----------------------------
DROP TABLE IF EXISTS `usuarios_cedentes`;
CREATE TABLE `usuarios_cedentes`  (
  `Id` int(0) NOT NULL AUTO_INCREMENT,
  `Id_Usuario` int(0) NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  INDEX `Id_Usuario`(`Id_Usuario`) USING BTREE,
  INDEX `Id_Cedente`(`Id_Cedente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios_correos
-- ----------------------------
DROP TABLE IF EXISTS `usuarios_correos`;
CREATE TABLE `usuarios_correos`  (
  `id` int(0) NOT NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `usuario` int(0) NULL DEFAULT NULL,
  `mantenedor` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios_externo
-- ----------------------------
DROP TABLE IF EXISTS `usuarios_externo`;
CREATE TABLE `usuarios_externo`  (
  `id` int(0) NOT NULL,
  `usuario` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `clave` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nivel` int(0) NULL DEFAULT NULL,
  `cargo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_Cedente` int(0) NULL DEFAULT NULL,
  `user_dial` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `pass_dial` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios_reclutamiento
-- ----------------------------
DROP TABLE IF EXISTS `usuarios_reclutamiento`;
CREATE TABLE `usuarios_reclutamiento`  (
  `id` int(0) NOT NULL,
  `Username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_empresa` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios_sms
-- ----------------------------
DROP TABLE IF EXISTS `usuarios_sms`;
CREATE TABLE `usuarios_sms`  (
  `id` int(0) NOT NULL,
  `cantidad` int(0) NULL DEFAULT NULL,
  `usuario` int(0) NULL DEFAULT NULL,
  `mantenedor` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for variables
-- ----------------------------
DROP TABLE IF EXISTS `variables`;
CREATE TABLE `variables`  (
  `id` bigint(0) NOT NULL,
  `variable` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tabla` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `campo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `operacion` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for variablessms
-- ----------------------------
DROP TABLE IF EXISTS `variablessms`;
CREATE TABLE `variablessms`  (
  `id` bigint(0) NOT NULL,
  `variable` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `tabla` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `campo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `operacion` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for vicidial_campaigns
-- ----------------------------
DROP TABLE IF EXISTS `vicidial_campaigns`;
CREATE TABLE `vicidial_campaigns`  (
  `campaign_id` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `campaign_name` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `active` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_status_a` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_status_b` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_status_c` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_status_d` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_status_e` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lead_order` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `park_ext` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `park_file_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `web_form_address` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `allow_closers` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hopper_level` bigint(0) NULL DEFAULT NULL,
  `auto_dial_level` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `next_agent_call` varchar(21) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `local_call_time` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `voicemail_ext` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_timeout` tinyint(0) NULL DEFAULT NULL,
  `dial_prefix` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_cid` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_vdad_exten` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_rec_exten` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_recording` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_rec_filename` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_script` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `get_call_launch` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `am_message_exten` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `amd_send_to_vmx` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `xferconf_a_dtmf` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `xferconf_a_number` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `xferconf_b_dtmf` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `xferconf_b_number` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `alt_number_dialing` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `scheduled_callbacks` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lead_filter_id` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `drop_call_seconds` smallint(0) NULL DEFAULT NULL,
  `drop_action` varchar(13) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `safe_harbor_exten` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `display_dialable_count` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `wrapup_seconds` int(0) NULL DEFAULT NULL,
  `wrapup_message` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `closer_campaigns` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `use_internal_dnc` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `allcalls_delay` int(0) NULL DEFAULT NULL,
  `omit_phone_code` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_method` varchar(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_only_ratio_tally` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `adaptive_dropped_percentage` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `adaptive_maximum_level` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `adaptive_latest_server_time` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `adaptive_intensity` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `adaptive_dl_diff_target` smallint(0) NULL DEFAULT NULL,
  `concurrent_transfers` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `auto_alt_dial` varchar(26) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `auto_alt_dial_statuses` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_pause_codes_active` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_changedate` datetime(0) NULL DEFAULT NULL,
  `campaign_stats_refresh` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_logindate` datetime(0) NULL DEFAULT NULL,
  `dial_statuses` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `disable_alter_custdata` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_hopper_leads_logins` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `list_order_mix` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_allow_inbound` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `manual_dial_list_id` bigint(0) NULL DEFAULT NULL,
  `default_xfer_group` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `xfer_groups` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `queue_priority` smallint(0) NULL DEFAULT NULL,
  `drop_inbound_group` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `qc_enabled` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `qc_statuses` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `qc_lists` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `qc_shift_id` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `qc_get_record_launch` varchar(9) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `qc_show_recording` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `qc_web_form_address` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `qc_script` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_first_audio_file` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_dtmf_digits` varchar(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_ni_digit` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_opt_in_audio_file` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_ni_audio_file` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_method` varchar(14) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_no_response_action` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_ni_status` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_response_digit_map` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_xfer_exten` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_camp_record_dir` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `disable_alter_custphone` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `display_queue_count` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `manual_dial_filter` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_clipboard_copy` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_extended_alt_dial` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `use_campaign_dnc` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `three_way_call_cid` varchar(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `three_way_dial_prefix` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `web_form_target` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vtiger_search_category` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vtiger_create_call_record` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vtiger_create_lead_record` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vtiger_screen_login` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cpd_amd_action` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_allow_group_alias` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `default_group_alias` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vtiger_search_dead` varchar(9) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vtiger_status_call` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_third_digit` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_third_audio_file` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_third_status` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_third_exten` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_fourth_digit` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_fourth_audio_file` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_fourth_status` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_fourth_exten` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `drop_lockout_time` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `quick_transfer_button` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `prepopulate_transfer_preset` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `drop_rate_group` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `view_calls_in_queue` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `view_calls_in_queue_launch` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `grab_calls_in_queue` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `call_requeue_button` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pause_after_each_call` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_hopper_dialing` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_dial_owner_only` varchar(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_display_dialable_leads` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `web_form_address_two` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `waitforsilence_options` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_select_territories` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `campaign_calldate` datetime(0) NULL DEFAULT NULL,
  `crm_popup_login` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `crm_login_address` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `timer_action` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `timer_action_message` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `timer_action_seconds` int(0) NULL DEFAULT NULL,
  `start_call_url` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `dispo_call_url` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `xferconf_c_number` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `xferconf_d_number` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `xferconf_e_number` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `use_custom_cid` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `scheduled_callbacks_alert` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `queuemetrics_callstatus_override` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `extension_appended_cidname` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `scheduled_callbacks_count` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `manual_dial_override` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `blind_monitor_warning` varchar(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `blind_monitor_message` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `blind_monitor_filename` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `inbound_queue_no_dial` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `timer_action_destination` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `enable_xfer_presets` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hide_xfer_number_to_dial` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `manual_dial_prefix` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `customer_3way_hangup_logging` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `customer_3way_hangup_seconds` int(0) NULL DEFAULT NULL,
  `customer_3way_hangup_action` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ivr_park_call` varchar(21) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `ivr_park_call_agi` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `manual_preview_dial` varchar(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `realtime_agent_time_stats` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `use_auto_hopper` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `auto_hopper_multi` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `auto_hopper_level` int(0) NULL DEFAULT NULL,
  `auto_trim_hopper` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `api_manual_dial` varchar(18) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `manual_dial_call_time_check` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `display_leads_count` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lead_order_randomize` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lead_order_secondary` varchar(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `per_call_notes` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `my_callback_option` varchar(9) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_lead_search` varchar(28) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `agent_lead_search_method` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `queuemetrics_phone_environment` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `auto_pause_precall` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `auto_pause_precall_code` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `auto_resume_precall` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `manual_dial_cid` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `post_phone_time_diff_alert` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `custom_3way_button_transfer` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_only_tally_threshold` varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `available_only_tally_threshold_agents` int(0) NULL DEFAULT NULL,
  `dial_level_threshold` varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dial_level_threshold_agents` int(0) NULL DEFAULT NULL,
  `safe_harbor_audio` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `safe_harbor_menu_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_menu_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `callback_days_limit` smallint(0) NULL DEFAULT NULL,
  `dl_diff_target_method` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `disable_dispo_screen` varchar(21) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `disable_dispo_status` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `screen_labels` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status_display_fields` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `na_call_url` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `survey_recording` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pllb_grouping` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pllb_grouping_limit` smallint(0) NULL DEFAULT NULL,
  `call_count_limit` int(0) NULL DEFAULT NULL,
  `call_count_target` int(0) NULL DEFAULT NULL,
  `callback_hours_block` smallint(0) NULL DEFAULT NULL,
  `callback_list_calltime` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `user_group` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hopper_vlc_dup_check` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `in_group_dial` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `in_group_dial_select` varchar(17) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `safe_harbor_audio_field` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pause_after_next_call` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `owner_populate` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `use_other_campaign_dnc` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `allow_emails` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `amd_inbound_group` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `amd_callmenu` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `survey_wait_sec` smallint(0) NULL DEFAULT NULL,
  `manual_dial_lead_id` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dead_max` int(0) NULL DEFAULT NULL,
  `dead_max_dispo` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dispo_max` int(0) NULL DEFAULT NULL,
  `dispo_max_dispo` varchar(6) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pause_max` int(0) NULL DEFAULT NULL,
  `max_inbound_calls` int(0) NULL DEFAULT NULL,
  `manual_dial_search_checkbox` varchar(16) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `hide_call_log_info` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `timer_alt_seconds` smallint(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for vw_deuda_rut_cedente
-- ----------------------------
DROP TABLE IF EXISTS `vw_deuda_rut_cedente`;
CREATE TABLE `vw_deuda_rut_cedente`  (
  `Fecha_asignacion` datetime(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `deuda` decimal(42, 2) NULL DEFAULT NULL,
  `tramo_morosidad` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_vencimiento` date NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for vw_errorescriticos_mes
-- ----------------------------
DROP TABLE IF EXISTS `vw_errorescriticos_mes`;
CREATE TABLE `vw_errorescriticos_mes`  (
  `ID` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Nombre_Cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Descripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Cantidad` int(0) NULL DEFAULT NULL,
  `observacion` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `url_grabacion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Mes` int(0) NULL DEFAULT NULL,
  `IdError` int(0) NULL DEFAULT NULL,
  `Id_cedente` int(0) NULL DEFAULT NULL,
  `Id_Personal` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Supervisor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Pais` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cedente` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `Id_Evaluacion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for vw_evaluaciones_ejecutivo_detallado
-- ----------------------------
DROP TABLE IF EXISTS `vw_evaluaciones_ejecutivo_detallado`;
CREATE TABLE `vw_evaluaciones_ejecutivo_detallado`  (
  `Cartera` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Contenedor` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Competencia` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nota` decimal(32, 2) NULL DEFAULT NULL,
  `Meta` decimal(24, 4) NULL DEFAULT NULL,
  `Mes` int(0) NULL DEFAULT NULL,
  `Nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ponderacion` int(0) NULL DEFAULT NULL,
  `url_grabacion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Nombre_Grabacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `NotaMaximaEvaluacion` int(0) NULL DEFAULT NULL,
  `Pais` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observacion` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Supervisor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Usuario` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_evaluacion` datetime(0) NULL DEFAULT NULL,
  `id_evaluacion` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for vw_reporte_contactabilidad_bi
-- ----------------------------
DROP TABLE IF EXISTS `vw_reporte_contactabilidad_bi`;
CREATE TABLE `vw_reporte_contactabilidad_bi`  (
  `Fecha_asignacion` datetime(0) NULL DEFAULT NULL,
  `Rut` int(0) NULL DEFAULT NULL,
  `deuda` decimal(42, 2) NULL DEFAULT NULL,
  `tramo_morosidad` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fecha_vencimiento` date NULL DEFAULT NULL,
  `Dias_Mora` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(0) NULL DEFAULT NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `origen` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `TipoGestion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Peso` int(0) NULL DEFAULT NULL,
  `id_cedente` int(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for vw_reporte_gestiones_bi
-- ----------------------------
DROP TABLE IF EXISTS `vw_reporte_gestiones_bi`;
CREATE TABLE `vw_reporte_gestiones_bi`  (
  `pais` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha_asignacion` char(0) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Rut` char(0) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `id_gestion` int(0) NULL DEFAULT NULL,
  `rut_cliente` int(0) NULL DEFAULT NULL,
  `fecha_gestion` date NULL DEFAULT NULL,
  `hora_gestion` time(6) NULL DEFAULT NULL,
  `fechahora` datetime(0) NULL DEFAULT NULL,
  `fono_discado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre_ejecutivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nombre_grabacion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cedente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `fec_compromiso` date NULL DEFAULT NULL,
  `origen` int(0) NULL DEFAULT NULL,
  `nom_org` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Id_TipoGestion` int(0) NULL DEFAULT NULL,
  `url_grabacion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `n1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n2` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `n3` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Cantidad_codisas` int(0) NULL DEFAULT NULL,
  `observacion` binary(0) NULL DEFAULT NULL,
  `duracion` int(0) NULL DEFAULT NULL,
  `monto_comp` int(0) NULL DEFAULT NULL,
  `status` binary(0) NULL DEFAULT NULL,
  `status_name` binary(0) NULL DEFAULT NULL,
  `peso` int(0) NULL DEFAULT NULL,
  `sox` int(0) NULL DEFAULT NULL,
  `factura` binary(0) NULL DEFAULT NULL,
  `fechaAgendamiento` binary(0) NULL DEFAULT NULL,
  `motivobasal` binary(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for worksheet
-- ----------------------------
DROP TABLE IF EXISTS `worksheet`;
CREATE TABLE `worksheet`  (
  `Código Cliente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Teléfono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Tipo Gestión` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha Hora` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Gestión Compromiso` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Observación` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Fecha Compromiso` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Campaña` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Duración` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Link Grabación` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Razón Atraso de Pago` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `Razón de No Pago` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
