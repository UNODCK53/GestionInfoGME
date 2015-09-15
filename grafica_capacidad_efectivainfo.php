<?php

// Global variable for table object
$grafica_capacidad_efectiva = NULL;

//
// Table class for grafica_capacidad_efectiva
//
class cgrafica_capacidad_efectiva extends cTable {
	var $Punto;
	var $Total_general;
	var $Dia_sin_novedad_especial;
	var $_1_Apoyo_zonal_sin_punto_asignado;
	var $_1_Descanso_en_dia_habil;
	var $_1_Descanso_festivo_dominical;
	var $_1_Dia_compensatorio;
	var $_1_Erradicacion_en_dia_festivo;
	var $_1_Espera_helicoptero_Helistar;
	var $_1_Extraccion;
	var $_1_Firma_contrato_GME;
	var $_1_Induccion_Apoyo_Zonal;
	var $_1_Insercion;
	var $_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase;
	var $_1_Novedad_apoyo_zonal;
	var $_1_Novedad_enfermero;
	var $_1_Punto_fuera_del_area_de_erradicacion;
	var $_1_Transporte_bus;
	var $_1_Traslado_apoyo_zonal;
	var $_1_Traslado_area_vivac;
	var $_2_A_la_espera_definicion_nuevo_punto_FP;
	var $_2_Espera_helicoptero_FP_de_seguridad;
	var $_2_Espera_helicoptero_FP_que_abastece;
	var $_2_Induccion_FP;
	var $_2_Novedad_canino_o_del_grupo_de_deteccion;
	var $_2_Problemas_fuerza_publica;
	var $_2_Sin_seguridad;
	var $_3_AEI_controlado;
	var $_3_AEI_no_controlado;
	var $_3_Bloqueo_parcial_de_la_comunidad;
	var $_3_Bloqueo_total_de_la_comunidad;
	var $_3_Combate;
	var $_3_Hostigamiento;
	var $_3_MAP_Controlada;
	var $_3_MAP_No_controlada;
	var $_3_Operaciones_de_seguridad;
	var $_4_Epidemia;
	var $_4_Novedad_climatologica;
	var $_4_Registro_de_cultivos;
	var $_4_Zona_con_cultivos_muy_dispersos;
	var $_4_Zona_de_cruce_de_rios_caudalosos;
	var $_4_Zona_sin_cultivos;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'grafica_capacidad_efectiva';
		$this->TableName = 'grafica_capacidad_efectiva';
		$this->TableType = 'VIEW';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Punto
		$this->Punto = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x_Punto', 'Punto', '`Punto`', '`Punto`', 201, -1, FALSE, '`Punto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Punto'] = &$this->Punto;

		// Total_general
		$this->Total_general = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x_Total_general', 'Total_general', '`Total_general`', '`Total_general`', 20, -1, FALSE, '`Total_general`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Total_general->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Total_general'] = &$this->Total_general;

		// Dia_sin_novedad_especial
		$this->Dia_sin_novedad_especial = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x_Dia_sin_novedad_especial', 'Dia_sin_novedad_especial', '`Dia_sin_novedad_especial`', '`Dia_sin_novedad_especial`', 131, -1, FALSE, '`Dia_sin_novedad_especial`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Dia_sin_novedad_especial->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Dia_sin_novedad_especial'] = &$this->Dia_sin_novedad_especial;

		// 1_Apoyo_zonal_sin_punto_asignado
		$this->_1_Apoyo_zonal_sin_punto_asignado = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Apoyo_zonal_sin_punto_asignado', '1_Apoyo_zonal_sin_punto_asignado', '`1_Apoyo_zonal_sin_punto_asignado`', '`1_Apoyo_zonal_sin_punto_asignado`', 131, -1, FALSE, '`1_Apoyo_zonal_sin_punto_asignado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Apoyo_zonal_sin_punto_asignado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Apoyo_zonal_sin_punto_asignado'] = &$this->_1_Apoyo_zonal_sin_punto_asignado;

		// 1_Descanso_en_dia_habil
		$this->_1_Descanso_en_dia_habil = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Descanso_en_dia_habil', '1_Descanso_en_dia_habil', '`1_Descanso_en_dia_habil`', '`1_Descanso_en_dia_habil`', 131, -1, FALSE, '`1_Descanso_en_dia_habil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Descanso_en_dia_habil->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Descanso_en_dia_habil'] = &$this->_1_Descanso_en_dia_habil;

		// 1_Descanso_festivo_dominical
		$this->_1_Descanso_festivo_dominical = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Descanso_festivo_dominical', '1_Descanso_festivo_dominical', '`1_Descanso_festivo_dominical`', '`1_Descanso_festivo_dominical`', 131, -1, FALSE, '`1_Descanso_festivo_dominical`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Descanso_festivo_dominical->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Descanso_festivo_dominical'] = &$this->_1_Descanso_festivo_dominical;

		// 1_Dia_compensatorio
		$this->_1_Dia_compensatorio = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Dia_compensatorio', '1_Dia_compensatorio', '`1_Dia_compensatorio`', '`1_Dia_compensatorio`', 131, -1, FALSE, '`1_Dia_compensatorio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Dia_compensatorio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Dia_compensatorio'] = &$this->_1_Dia_compensatorio;

		// 1_Erradicacion_en_dia_festivo
		$this->_1_Erradicacion_en_dia_festivo = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Erradicacion_en_dia_festivo', '1_Erradicacion_en_dia_festivo', '`1_Erradicacion_en_dia_festivo`', '`1_Erradicacion_en_dia_festivo`', 131, -1, FALSE, '`1_Erradicacion_en_dia_festivo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Erradicacion_en_dia_festivo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Erradicacion_en_dia_festivo'] = &$this->_1_Erradicacion_en_dia_festivo;

		// 1_Espera_helicoptero_Helistar
		$this->_1_Espera_helicoptero_Helistar = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Espera_helicoptero_Helistar', '1_Espera_helicoptero_Helistar', '`1_Espera_helicoptero_Helistar`', '`1_Espera_helicoptero_Helistar`', 131, -1, FALSE, '`1_Espera_helicoptero_Helistar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Espera_helicoptero_Helistar->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Espera_helicoptero_Helistar'] = &$this->_1_Espera_helicoptero_Helistar;

		// 1_Extraccion
		$this->_1_Extraccion = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Extraccion', '1_Extraccion', '`1_Extraccion`', '`1_Extraccion`', 131, -1, FALSE, '`1_Extraccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Extraccion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Extraccion'] = &$this->_1_Extraccion;

		// 1_Firma_contrato_GME
		$this->_1_Firma_contrato_GME = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Firma_contrato_GME', '1_Firma_contrato_GME', '`1_Firma_contrato_GME`', '`1_Firma_contrato_GME`', 131, -1, FALSE, '`1_Firma_contrato_GME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Firma_contrato_GME->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Firma_contrato_GME'] = &$this->_1_Firma_contrato_GME;

		// 1_Induccion_Apoyo_Zonal
		$this->_1_Induccion_Apoyo_Zonal = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Induccion_Apoyo_Zonal', '1_Induccion_Apoyo_Zonal', '`1_Induccion_Apoyo_Zonal`', '`1_Induccion_Apoyo_Zonal`', 131, -1, FALSE, '`1_Induccion_Apoyo_Zonal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Induccion_Apoyo_Zonal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Induccion_Apoyo_Zonal'] = &$this->_1_Induccion_Apoyo_Zonal;

		// 1_Insercion
		$this->_1_Insercion = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Insercion', '1_Insercion', '`1_Insercion`', '`1_Insercion`', 131, -1, FALSE, '`1_Insercion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Insercion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Insercion'] = &$this->_1_Insercion;

		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase', '1_Llegada_GME_a_su_lugar_de_Origen_fin_fase', '`1_Llegada_GME_a_su_lugar_de_Origen_fin_fase`', '`1_Llegada_GME_a_su_lugar_de_Origen_fin_fase`', 131, -1, FALSE, '`1_Llegada_GME_a_su_lugar_de_Origen_fin_fase`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Llegada_GME_a_su_lugar_de_Origen_fin_fase'] = &$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase;

		// 1_Novedad_apoyo_zonal
		$this->_1_Novedad_apoyo_zonal = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Novedad_apoyo_zonal', '1_Novedad_apoyo_zonal', '`1_Novedad_apoyo_zonal`', '`1_Novedad_apoyo_zonal`', 131, -1, FALSE, '`1_Novedad_apoyo_zonal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Novedad_apoyo_zonal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Novedad_apoyo_zonal'] = &$this->_1_Novedad_apoyo_zonal;

		// 1_Novedad_enfermero
		$this->_1_Novedad_enfermero = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Novedad_enfermero', '1_Novedad_enfermero', '`1_Novedad_enfermero`', '`1_Novedad_enfermero`', 131, -1, FALSE, '`1_Novedad_enfermero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Novedad_enfermero->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Novedad_enfermero'] = &$this->_1_Novedad_enfermero;

		// 1_Punto_fuera_del_area_de_erradicacion
		$this->_1_Punto_fuera_del_area_de_erradicacion = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Punto_fuera_del_area_de_erradicacion', '1_Punto_fuera_del_area_de_erradicacion', '`1_Punto_fuera_del_area_de_erradicacion`', '`1_Punto_fuera_del_area_de_erradicacion`', 131, -1, FALSE, '`1_Punto_fuera_del_area_de_erradicacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Punto_fuera_del_area_de_erradicacion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Punto_fuera_del_area_de_erradicacion'] = &$this->_1_Punto_fuera_del_area_de_erradicacion;

		// 1_Transporte_bus
		$this->_1_Transporte_bus = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Transporte_bus', '1_Transporte_bus', '`1_Transporte_bus`', '`1_Transporte_bus`', 131, -1, FALSE, '`1_Transporte_bus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Transporte_bus->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Transporte_bus'] = &$this->_1_Transporte_bus;

		// 1_Traslado_apoyo_zonal
		$this->_1_Traslado_apoyo_zonal = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Traslado_apoyo_zonal', '1_Traslado_apoyo_zonal', '`1_Traslado_apoyo_zonal`', '`1_Traslado_apoyo_zonal`', 131, -1, FALSE, '`1_Traslado_apoyo_zonal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Traslado_apoyo_zonal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Traslado_apoyo_zonal'] = &$this->_1_Traslado_apoyo_zonal;

		// 1_Traslado_area_vivac
		$this->_1_Traslado_area_vivac = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__1_Traslado_area_vivac', '1_Traslado_area_vivac', '`1_Traslado_area_vivac`', '`1_Traslado_area_vivac`', 131, -1, FALSE, '`1_Traslado_area_vivac`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Traslado_area_vivac->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Traslado_area_vivac'] = &$this->_1_Traslado_area_vivac;

		// 2_A_la_espera_definicion_nuevo_punto_FP
		$this->_2_A_la_espera_definicion_nuevo_punto_FP = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__2_A_la_espera_definicion_nuevo_punto_FP', '2_A_la_espera_definicion_nuevo_punto_FP', '`2_A_la_espera_definicion_nuevo_punto_FP`', '`2_A_la_espera_definicion_nuevo_punto_FP`', 131, -1, FALSE, '`2_A_la_espera_definicion_nuevo_punto_FP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_A_la_espera_definicion_nuevo_punto_FP'] = &$this->_2_A_la_espera_definicion_nuevo_punto_FP;

		// 2_Espera_helicoptero_FP_de_seguridad
		$this->_2_Espera_helicoptero_FP_de_seguridad = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__2_Espera_helicoptero_FP_de_seguridad', '2_Espera_helicoptero_FP_de_seguridad', '`2_Espera_helicoptero_FP_de_seguridad`', '`2_Espera_helicoptero_FP_de_seguridad`', 131, -1, FALSE, '`2_Espera_helicoptero_FP_de_seguridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Espera_helicoptero_FP_de_seguridad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Espera_helicoptero_FP_de_seguridad'] = &$this->_2_Espera_helicoptero_FP_de_seguridad;

		// 2_Espera_helicoptero_FP_que_abastece
		$this->_2_Espera_helicoptero_FP_que_abastece = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__2_Espera_helicoptero_FP_que_abastece', '2_Espera_helicoptero_FP_que_abastece', '`2_Espera_helicoptero_FP_que_abastece`', '`2_Espera_helicoptero_FP_que_abastece`', 131, -1, FALSE, '`2_Espera_helicoptero_FP_que_abastece`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Espera_helicoptero_FP_que_abastece->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Espera_helicoptero_FP_que_abastece'] = &$this->_2_Espera_helicoptero_FP_que_abastece;

		// 2_Induccion_FP
		$this->_2_Induccion_FP = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__2_Induccion_FP', '2_Induccion_FP', '`2_Induccion_FP`', '`2_Induccion_FP`', 131, -1, FALSE, '`2_Induccion_FP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Induccion_FP->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Induccion_FP'] = &$this->_2_Induccion_FP;

		// 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__2_Novedad_canino_o_del_grupo_de_deteccion', '2_Novedad_canino_o_del_grupo_de_deteccion', '`2_Novedad_canino_o_del_grupo_de_deteccion`', '`2_Novedad_canino_o_del_grupo_de_deteccion`', 131, -1, FALSE, '`2_Novedad_canino_o_del_grupo_de_deteccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Novedad_canino_o_del_grupo_de_deteccion'] = &$this->_2_Novedad_canino_o_del_grupo_de_deteccion;

		// 2_Problemas_fuerza_publica
		$this->_2_Problemas_fuerza_publica = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__2_Problemas_fuerza_publica', '2_Problemas_fuerza_publica', '`2_Problemas_fuerza_publica`', '`2_Problemas_fuerza_publica`', 131, -1, FALSE, '`2_Problemas_fuerza_publica`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Problemas_fuerza_publica->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Problemas_fuerza_publica'] = &$this->_2_Problemas_fuerza_publica;

		// 2_Sin_seguridad
		$this->_2_Sin_seguridad = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__2_Sin_seguridad', '2_Sin_seguridad', '`2_Sin_seguridad`', '`2_Sin_seguridad`', 131, -1, FALSE, '`2_Sin_seguridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Sin_seguridad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Sin_seguridad'] = &$this->_2_Sin_seguridad;

		// 3_AEI_controlado
		$this->_3_AEI_controlado = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_AEI_controlado', '3_AEI_controlado', '`3_AEI_controlado`', '`3_AEI_controlado`', 131, -1, FALSE, '`3_AEI_controlado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_AEI_controlado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_AEI_controlado'] = &$this->_3_AEI_controlado;

		// 3_AEI_no_controlado
		$this->_3_AEI_no_controlado = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_AEI_no_controlado', '3_AEI_no_controlado', '`3_AEI_no_controlado`', '`3_AEI_no_controlado`', 131, -1, FALSE, '`3_AEI_no_controlado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_AEI_no_controlado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_AEI_no_controlado'] = &$this->_3_AEI_no_controlado;

		// 3_Bloqueo_parcial_de_la_comunidad
		$this->_3_Bloqueo_parcial_de_la_comunidad = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_Bloqueo_parcial_de_la_comunidad', '3_Bloqueo_parcial_de_la_comunidad', '`3_Bloqueo_parcial_de_la_comunidad`', '`3_Bloqueo_parcial_de_la_comunidad`', 131, -1, FALSE, '`3_Bloqueo_parcial_de_la_comunidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Bloqueo_parcial_de_la_comunidad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Bloqueo_parcial_de_la_comunidad'] = &$this->_3_Bloqueo_parcial_de_la_comunidad;

		// 3_Bloqueo_total_de_la_comunidad
		$this->_3_Bloqueo_total_de_la_comunidad = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_Bloqueo_total_de_la_comunidad', '3_Bloqueo_total_de_la_comunidad', '`3_Bloqueo_total_de_la_comunidad`', '`3_Bloqueo_total_de_la_comunidad`', 131, -1, FALSE, '`3_Bloqueo_total_de_la_comunidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Bloqueo_total_de_la_comunidad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Bloqueo_total_de_la_comunidad'] = &$this->_3_Bloqueo_total_de_la_comunidad;

		// 3_Combate
		$this->_3_Combate = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_Combate', '3_Combate', '`3_Combate`', '`3_Combate`', 131, -1, FALSE, '`3_Combate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Combate->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Combate'] = &$this->_3_Combate;

		// 3_Hostigamiento
		$this->_3_Hostigamiento = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_Hostigamiento', '3_Hostigamiento', '`3_Hostigamiento`', '`3_Hostigamiento`', 131, -1, FALSE, '`3_Hostigamiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Hostigamiento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Hostigamiento'] = &$this->_3_Hostigamiento;

		// 3_MAP_Controlada
		$this->_3_MAP_Controlada = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_MAP_Controlada', '3_MAP_Controlada', '`3_MAP_Controlada`', '`3_MAP_Controlada`', 131, -1, FALSE, '`3_MAP_Controlada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_MAP_Controlada->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_MAP_Controlada'] = &$this->_3_MAP_Controlada;

		// 3_MAP_No_controlada
		$this->_3_MAP_No_controlada = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_MAP_No_controlada', '3_MAP_No_controlada', '`3_MAP_No_controlada`', '`3_MAP_No_controlada`', 131, -1, FALSE, '`3_MAP_No_controlada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_MAP_No_controlada->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_MAP_No_controlada'] = &$this->_3_MAP_No_controlada;

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__3_Operaciones_de_seguridad', '3_Operaciones_de_seguridad', '`3_Operaciones_de_seguridad`', '`3_Operaciones_de_seguridad`', 131, -1, FALSE, '`3_Operaciones_de_seguridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Operaciones_de_seguridad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Operaciones_de_seguridad'] = &$this->_3_Operaciones_de_seguridad;

		// 4_Epidemia
		$this->_4_Epidemia = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__4_Epidemia', '4_Epidemia', '`4_Epidemia`', '`4_Epidemia`', 131, -1, FALSE, '`4_Epidemia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Epidemia->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Epidemia'] = &$this->_4_Epidemia;

		// 4_Novedad_climatologica
		$this->_4_Novedad_climatologica = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__4_Novedad_climatologica', '4_Novedad_climatologica', '`4_Novedad_climatologica`', '`4_Novedad_climatologica`', 131, -1, FALSE, '`4_Novedad_climatologica`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Novedad_climatologica->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Novedad_climatologica'] = &$this->_4_Novedad_climatologica;

		// 4_Registro_de_cultivos
		$this->_4_Registro_de_cultivos = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__4_Registro_de_cultivos', '4_Registro_de_cultivos', '`4_Registro_de_cultivos`', '`4_Registro_de_cultivos`', 131, -1, FALSE, '`4_Registro_de_cultivos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Registro_de_cultivos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Registro_de_cultivos'] = &$this->_4_Registro_de_cultivos;

		// 4_Zona_con_cultivos_muy_dispersos
		$this->_4_Zona_con_cultivos_muy_dispersos = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__4_Zona_con_cultivos_muy_dispersos', '4_Zona_con_cultivos_muy_dispersos', '`4_Zona_con_cultivos_muy_dispersos`', '`4_Zona_con_cultivos_muy_dispersos`', 131, -1, FALSE, '`4_Zona_con_cultivos_muy_dispersos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Zona_con_cultivos_muy_dispersos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Zona_con_cultivos_muy_dispersos'] = &$this->_4_Zona_con_cultivos_muy_dispersos;

		// 4_Zona_de_cruce_de_rios_caudalosos
		$this->_4_Zona_de_cruce_de_rios_caudalosos = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__4_Zona_de_cruce_de_rios_caudalosos', '4_Zona_de_cruce_de_rios_caudalosos', '`4_Zona_de_cruce_de_rios_caudalosos`', '`4_Zona_de_cruce_de_rios_caudalosos`', 131, -1, FALSE, '`4_Zona_de_cruce_de_rios_caudalosos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Zona_de_cruce_de_rios_caudalosos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Zona_de_cruce_de_rios_caudalosos'] = &$this->_4_Zona_de_cruce_de_rios_caudalosos;

		// 4_Zona_sin_cultivos
		$this->_4_Zona_sin_cultivos = new cField('grafica_capacidad_efectiva', 'grafica_capacidad_efectiva', 'x__4_Zona_sin_cultivos', '4_Zona_sin_cultivos', '`4_Zona_sin_cultivos`', '`4_Zona_sin_cultivos`', 131, -1, FALSE, '`4_Zona_sin_cultivos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Zona_sin_cultivos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Zona_sin_cultivos'] = &$this->_4_Zona_sin_cultivos;
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`grafica_capacidad_efectiva`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`grafica_capacidad_efectiva`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "grafica_capacidad_efectivalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "grafica_capacidad_efectivalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("grafica_capacidad_efectivaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("grafica_capacidad_efectivaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "grafica_capacidad_efectivaadd.php?" . $this->UrlParm($parm);
		else
			return "grafica_capacidad_efectivaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("grafica_capacidad_efectivaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("grafica_capacidad_efectivaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("grafica_capacidad_efectivadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->Total_general->setDbValue($rs->fields('Total_general'));
		$this->Dia_sin_novedad_especial->setDbValue($rs->fields('Dia_sin_novedad_especial'));
		$this->_1_Apoyo_zonal_sin_punto_asignado->setDbValue($rs->fields('1_Apoyo_zonal_sin_punto_asignado'));
		$this->_1_Descanso_en_dia_habil->setDbValue($rs->fields('1_Descanso_en_dia_habil'));
		$this->_1_Descanso_festivo_dominical->setDbValue($rs->fields('1_Descanso_festivo_dominical'));
		$this->_1_Dia_compensatorio->setDbValue($rs->fields('1_Dia_compensatorio'));
		$this->_1_Erradicacion_en_dia_festivo->setDbValue($rs->fields('1_Erradicacion_en_dia_festivo'));
		$this->_1_Espera_helicoptero_Helistar->setDbValue($rs->fields('1_Espera_helicoptero_Helistar'));
		$this->_1_Extraccion->setDbValue($rs->fields('1_Extraccion'));
		$this->_1_Firma_contrato_GME->setDbValue($rs->fields('1_Firma_contrato_GME'));
		$this->_1_Induccion_Apoyo_Zonal->setDbValue($rs->fields('1_Induccion_Apoyo_Zonal'));
		$this->_1_Insercion->setDbValue($rs->fields('1_Insercion'));
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->setDbValue($rs->fields('1_Llegada_GME_a_su_lugar_de_Origen_fin_fase'));
		$this->_1_Novedad_apoyo_zonal->setDbValue($rs->fields('1_Novedad_apoyo_zonal'));
		$this->_1_Novedad_enfermero->setDbValue($rs->fields('1_Novedad_enfermero'));
		$this->_1_Punto_fuera_del_area_de_erradicacion->setDbValue($rs->fields('1_Punto_fuera_del_area_de_erradicacion'));
		$this->_1_Transporte_bus->setDbValue($rs->fields('1_Transporte_bus'));
		$this->_1_Traslado_apoyo_zonal->setDbValue($rs->fields('1_Traslado_apoyo_zonal'));
		$this->_1_Traslado_area_vivac->setDbValue($rs->fields('1_Traslado_area_vivac'));
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->setDbValue($rs->fields('2_A_la_espera_definicion_nuevo_punto_FP'));
		$this->_2_Espera_helicoptero_FP_de_seguridad->setDbValue($rs->fields('2_Espera_helicoptero_FP_de_seguridad'));
		$this->_2_Espera_helicoptero_FP_que_abastece->setDbValue($rs->fields('2_Espera_helicoptero_FP_que_abastece'));
		$this->_2_Induccion_FP->setDbValue($rs->fields('2_Induccion_FP'));
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setDbValue($rs->fields('2_Novedad_canino_o_del_grupo_de_deteccion'));
		$this->_2_Problemas_fuerza_publica->setDbValue($rs->fields('2_Problemas_fuerza_publica'));
		$this->_2_Sin_seguridad->setDbValue($rs->fields('2_Sin_seguridad'));
		$this->_3_AEI_controlado->setDbValue($rs->fields('3_AEI_controlado'));
		$this->_3_AEI_no_controlado->setDbValue($rs->fields('3_AEI_no_controlado'));
		$this->_3_Bloqueo_parcial_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_parcial_de_la_comunidad'));
		$this->_3_Bloqueo_total_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_total_de_la_comunidad'));
		$this->_3_Combate->setDbValue($rs->fields('3_Combate'));
		$this->_3_Hostigamiento->setDbValue($rs->fields('3_Hostigamiento'));
		$this->_3_MAP_Controlada->setDbValue($rs->fields('3_MAP_Controlada'));
		$this->_3_MAP_No_controlada->setDbValue($rs->fields('3_MAP_No_controlada'));
		$this->_3_Operaciones_de_seguridad->setDbValue($rs->fields('3_Operaciones_de_seguridad'));
		$this->_4_Epidemia->setDbValue($rs->fields('4_Epidemia'));
		$this->_4_Novedad_climatologica->setDbValue($rs->fields('4_Novedad_climatologica'));
		$this->_4_Registro_de_cultivos->setDbValue($rs->fields('4_Registro_de_cultivos'));
		$this->_4_Zona_con_cultivos_muy_dispersos->setDbValue($rs->fields('4_Zona_con_cultivos_muy_dispersos'));
		$this->_4_Zona_de_cruce_de_rios_caudalosos->setDbValue($rs->fields('4_Zona_de_cruce_de_rios_caudalosos'));
		$this->_4_Zona_sin_cultivos->setDbValue($rs->fields('4_Zona_sin_cultivos'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Punto
		// Total_general
		// Dia_sin_novedad_especial
		// 1_Apoyo_zonal_sin_punto_asignado
		// 1_Descanso_en_dia_habil
		// 1_Descanso_festivo_dominical
		// 1_Dia_compensatorio
		// 1_Erradicacion_en_dia_festivo
		// 1_Espera_helicoptero_Helistar
		// 1_Extraccion
		// 1_Firma_contrato_GME
		// 1_Induccion_Apoyo_Zonal
		// 1_Insercion
		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		// 1_Novedad_apoyo_zonal
		// 1_Novedad_enfermero
		// 1_Punto_fuera_del_area_de_erradicacion
		// 1_Transporte_bus
		// 1_Traslado_apoyo_zonal
		// 1_Traslado_area_vivac
		// 2_A_la_espera_definicion_nuevo_punto_FP
		// 2_Espera_helicoptero_FP_de_seguridad
		// 2_Espera_helicoptero_FP_que_abastece
		// 2_Induccion_FP
		// 2_Novedad_canino_o_del_grupo_de_deteccion
		// 2_Problemas_fuerza_publica
		// 2_Sin_seguridad
		// 3_AEI_controlado
		// 3_AEI_no_controlado
		// 3_Bloqueo_parcial_de_la_comunidad
		// 3_Bloqueo_total_de_la_comunidad
		// 3_Combate
		// 3_Hostigamiento
		// 3_MAP_Controlada
		// 3_MAP_No_controlada
		// 3_Operaciones_de_seguridad
		// 4_Epidemia
		// 4_Novedad_climatologica
		// 4_Registro_de_cultivos
		// 4_Zona_con_cultivos_muy_dispersos
		// 4_Zona_de_cruce_de_rios_caudalosos
		// 4_Zona_sin_cultivos
		// Punto

		if (strval($this->Punto->CurrentValue) <> "") {
			$sFilterWrk = "`Punto`" . ew_SearchString("=", $this->Punto->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_capacidad_efectiva`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_capacidad_efectiva`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Punto, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Punto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Punto->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Punto->ViewValue = $this->Punto->CurrentValue;
			}
		} else {
			$this->Punto->ViewValue = NULL;
		}
		$this->Punto->ViewCustomAttributes = "";

		// Total_general
		$this->Total_general->ViewValue = $this->Total_general->CurrentValue;
		$this->Total_general->ViewCustomAttributes = "";

		// Dia_sin_novedad_especial
		$this->Dia_sin_novedad_especial->ViewValue = $this->Dia_sin_novedad_especial->CurrentValue;
		$this->Dia_sin_novedad_especial->ViewCustomAttributes = "";

		// 1_Apoyo_zonal_sin_punto_asignado
		$this->_1_Apoyo_zonal_sin_punto_asignado->ViewValue = $this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue;
		$this->_1_Apoyo_zonal_sin_punto_asignado->ViewCustomAttributes = "";

		// 1_Descanso_en_dia_habil
		$this->_1_Descanso_en_dia_habil->ViewValue = $this->_1_Descanso_en_dia_habil->CurrentValue;
		$this->_1_Descanso_en_dia_habil->ViewCustomAttributes = "";

		// 1_Descanso_festivo_dominical
		$this->_1_Descanso_festivo_dominical->ViewValue = $this->_1_Descanso_festivo_dominical->CurrentValue;
		$this->_1_Descanso_festivo_dominical->ViewCustomAttributes = "";

		// 1_Dia_compensatorio
		$this->_1_Dia_compensatorio->ViewValue = $this->_1_Dia_compensatorio->CurrentValue;
		$this->_1_Dia_compensatorio->ViewCustomAttributes = "";

		// 1_Erradicacion_en_dia_festivo
		$this->_1_Erradicacion_en_dia_festivo->ViewValue = $this->_1_Erradicacion_en_dia_festivo->CurrentValue;
		$this->_1_Erradicacion_en_dia_festivo->ViewCustomAttributes = "";

		// 1_Espera_helicoptero_Helistar
		$this->_1_Espera_helicoptero_Helistar->ViewValue = $this->_1_Espera_helicoptero_Helistar->CurrentValue;
		$this->_1_Espera_helicoptero_Helistar->ViewCustomAttributes = "";

		// 1_Extraccion
		$this->_1_Extraccion->ViewValue = $this->_1_Extraccion->CurrentValue;
		$this->_1_Extraccion->ViewCustomAttributes = "";

		// 1_Firma_contrato_GME
		$this->_1_Firma_contrato_GME->ViewValue = $this->_1_Firma_contrato_GME->CurrentValue;
		$this->_1_Firma_contrato_GME->ViewCustomAttributes = "";

		// 1_Induccion_Apoyo_Zonal
		$this->_1_Induccion_Apoyo_Zonal->ViewValue = $this->_1_Induccion_Apoyo_Zonal->CurrentValue;
		$this->_1_Induccion_Apoyo_Zonal->ViewCustomAttributes = "";

		// 1_Insercion
		$this->_1_Insercion->ViewValue = $this->_1_Insercion->CurrentValue;
		$this->_1_Insercion->ViewCustomAttributes = "";

		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewValue = $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue;
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewCustomAttributes = "";

		// 1_Novedad_apoyo_zonal
		$this->_1_Novedad_apoyo_zonal->ViewValue = $this->_1_Novedad_apoyo_zonal->CurrentValue;
		$this->_1_Novedad_apoyo_zonal->ViewCustomAttributes = "";

		// 1_Novedad_enfermero
		$this->_1_Novedad_enfermero->ViewValue = $this->_1_Novedad_enfermero->CurrentValue;
		$this->_1_Novedad_enfermero->ViewCustomAttributes = "";

		// 1_Punto_fuera_del_area_de_erradicacion
		$this->_1_Punto_fuera_del_area_de_erradicacion->ViewValue = $this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue;
		$this->_1_Punto_fuera_del_area_de_erradicacion->ViewCustomAttributes = "";

		// 1_Transporte_bus
		$this->_1_Transporte_bus->ViewValue = $this->_1_Transporte_bus->CurrentValue;
		$this->_1_Transporte_bus->ViewCustomAttributes = "";

		// 1_Traslado_apoyo_zonal
		$this->_1_Traslado_apoyo_zonal->ViewValue = $this->_1_Traslado_apoyo_zonal->CurrentValue;
		$this->_1_Traslado_apoyo_zonal->ViewCustomAttributes = "";

		// 1_Traslado_area_vivac
		$this->_1_Traslado_area_vivac->ViewValue = $this->_1_Traslado_area_vivac->CurrentValue;
		$this->_1_Traslado_area_vivac->ViewCustomAttributes = "";

		// 2_A_la_espera_definicion_nuevo_punto_FP
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->ViewValue = $this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue;
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->ViewCustomAttributes = "";

		// 2_Espera_helicoptero_FP_de_seguridad
		$this->_2_Espera_helicoptero_FP_de_seguridad->ViewValue = $this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue;
		$this->_2_Espera_helicoptero_FP_de_seguridad->ViewCustomAttributes = "";

		// 2_Espera_helicoptero_FP_que_abastece
		$this->_2_Espera_helicoptero_FP_que_abastece->ViewValue = $this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue;
		$this->_2_Espera_helicoptero_FP_que_abastece->ViewCustomAttributes = "";

		// 2_Induccion_FP
		$this->_2_Induccion_FP->ViewValue = $this->_2_Induccion_FP->CurrentValue;
		$this->_2_Induccion_FP->ViewCustomAttributes = "";

		// 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewValue = $this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue;
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewCustomAttributes = "";

		// 2_Problemas_fuerza_publica
		$this->_2_Problemas_fuerza_publica->ViewValue = $this->_2_Problemas_fuerza_publica->CurrentValue;
		$this->_2_Problemas_fuerza_publica->ViewCustomAttributes = "";

		// 2_Sin_seguridad
		$this->_2_Sin_seguridad->ViewValue = $this->_2_Sin_seguridad->CurrentValue;
		$this->_2_Sin_seguridad->ViewCustomAttributes = "";

		// 3_AEI_controlado
		$this->_3_AEI_controlado->ViewValue = $this->_3_AEI_controlado->CurrentValue;
		$this->_3_AEI_controlado->ViewCustomAttributes = "";

		// 3_AEI_no_controlado
		$this->_3_AEI_no_controlado->ViewValue = $this->_3_AEI_no_controlado->CurrentValue;
		$this->_3_AEI_no_controlado->ViewCustomAttributes = "";

		// 3_Bloqueo_parcial_de_la_comunidad
		$this->_3_Bloqueo_parcial_de_la_comunidad->ViewValue = $this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue;
		$this->_3_Bloqueo_parcial_de_la_comunidad->ViewCustomAttributes = "";

		// 3_Bloqueo_total_de_la_comunidad
		$this->_3_Bloqueo_total_de_la_comunidad->ViewValue = $this->_3_Bloqueo_total_de_la_comunidad->CurrentValue;
		$this->_3_Bloqueo_total_de_la_comunidad->ViewCustomAttributes = "";

		// 3_Combate
		$this->_3_Combate->ViewValue = $this->_3_Combate->CurrentValue;
		$this->_3_Combate->ViewCustomAttributes = "";

		// 3_Hostigamiento
		$this->_3_Hostigamiento->ViewValue = $this->_3_Hostigamiento->CurrentValue;
		$this->_3_Hostigamiento->ViewCustomAttributes = "";

		// 3_MAP_Controlada
		$this->_3_MAP_Controlada->ViewValue = $this->_3_MAP_Controlada->CurrentValue;
		$this->_3_MAP_Controlada->ViewCustomAttributes = "";

		// 3_MAP_No_controlada
		$this->_3_MAP_No_controlada->ViewValue = $this->_3_MAP_No_controlada->CurrentValue;
		$this->_3_MAP_No_controlada->ViewCustomAttributes = "";

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad->ViewValue = $this->_3_Operaciones_de_seguridad->CurrentValue;
		$this->_3_Operaciones_de_seguridad->ViewCustomAttributes = "";

		// 4_Epidemia
		$this->_4_Epidemia->ViewValue = $this->_4_Epidemia->CurrentValue;
		$this->_4_Epidemia->ViewCustomAttributes = "";

		// 4_Novedad_climatologica
		$this->_4_Novedad_climatologica->ViewValue = $this->_4_Novedad_climatologica->CurrentValue;
		$this->_4_Novedad_climatologica->ViewCustomAttributes = "";

		// 4_Registro_de_cultivos
		$this->_4_Registro_de_cultivos->ViewValue = $this->_4_Registro_de_cultivos->CurrentValue;
		$this->_4_Registro_de_cultivos->ViewCustomAttributes = "";

		// 4_Zona_con_cultivos_muy_dispersos
		$this->_4_Zona_con_cultivos_muy_dispersos->ViewValue = $this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue;
		$this->_4_Zona_con_cultivos_muy_dispersos->ViewCustomAttributes = "";

		// 4_Zona_de_cruce_de_rios_caudalosos
		$this->_4_Zona_de_cruce_de_rios_caudalosos->ViewValue = $this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue;
		$this->_4_Zona_de_cruce_de_rios_caudalosos->ViewCustomAttributes = "";

		// 4_Zona_sin_cultivos
		$this->_4_Zona_sin_cultivos->ViewValue = $this->_4_Zona_sin_cultivos->CurrentValue;
		$this->_4_Zona_sin_cultivos->ViewCustomAttributes = "";

		// Punto
		$this->Punto->LinkCustomAttributes = "";
		$this->Punto->HrefValue = "";
		$this->Punto->TooltipValue = "";

		// Total_general
		$this->Total_general->LinkCustomAttributes = "";
		$this->Total_general->HrefValue = "";
		$this->Total_general->TooltipValue = "";

		// Dia_sin_novedad_especial
		$this->Dia_sin_novedad_especial->LinkCustomAttributes = "";
		$this->Dia_sin_novedad_especial->HrefValue = "";
		$this->Dia_sin_novedad_especial->TooltipValue = "";

		// 1_Apoyo_zonal_sin_punto_asignado
		$this->_1_Apoyo_zonal_sin_punto_asignado->LinkCustomAttributes = "";
		$this->_1_Apoyo_zonal_sin_punto_asignado->HrefValue = "";
		$this->_1_Apoyo_zonal_sin_punto_asignado->TooltipValue = "";

		// 1_Descanso_en_dia_habil
		$this->_1_Descanso_en_dia_habil->LinkCustomAttributes = "";
		$this->_1_Descanso_en_dia_habil->HrefValue = "";
		$this->_1_Descanso_en_dia_habil->TooltipValue = "";

		// 1_Descanso_festivo_dominical
		$this->_1_Descanso_festivo_dominical->LinkCustomAttributes = "";
		$this->_1_Descanso_festivo_dominical->HrefValue = "";
		$this->_1_Descanso_festivo_dominical->TooltipValue = "";

		// 1_Dia_compensatorio
		$this->_1_Dia_compensatorio->LinkCustomAttributes = "";
		$this->_1_Dia_compensatorio->HrefValue = "";
		$this->_1_Dia_compensatorio->TooltipValue = "";

		// 1_Erradicacion_en_dia_festivo
		$this->_1_Erradicacion_en_dia_festivo->LinkCustomAttributes = "";
		$this->_1_Erradicacion_en_dia_festivo->HrefValue = "";
		$this->_1_Erradicacion_en_dia_festivo->TooltipValue = "";

		// 1_Espera_helicoptero_Helistar
		$this->_1_Espera_helicoptero_Helistar->LinkCustomAttributes = "";
		$this->_1_Espera_helicoptero_Helistar->HrefValue = "";
		$this->_1_Espera_helicoptero_Helistar->TooltipValue = "";

		// 1_Extraccion
		$this->_1_Extraccion->LinkCustomAttributes = "";
		$this->_1_Extraccion->HrefValue = "";
		$this->_1_Extraccion->TooltipValue = "";

		// 1_Firma_contrato_GME
		$this->_1_Firma_contrato_GME->LinkCustomAttributes = "";
		$this->_1_Firma_contrato_GME->HrefValue = "";
		$this->_1_Firma_contrato_GME->TooltipValue = "";

		// 1_Induccion_Apoyo_Zonal
		$this->_1_Induccion_Apoyo_Zonal->LinkCustomAttributes = "";
		$this->_1_Induccion_Apoyo_Zonal->HrefValue = "";
		$this->_1_Induccion_Apoyo_Zonal->TooltipValue = "";

		// 1_Insercion
		$this->_1_Insercion->LinkCustomAttributes = "";
		$this->_1_Insercion->HrefValue = "";
		$this->_1_Insercion->TooltipValue = "";

		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->LinkCustomAttributes = "";
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->HrefValue = "";
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->TooltipValue = "";

		// 1_Novedad_apoyo_zonal
		$this->_1_Novedad_apoyo_zonal->LinkCustomAttributes = "";
		$this->_1_Novedad_apoyo_zonal->HrefValue = "";
		$this->_1_Novedad_apoyo_zonal->TooltipValue = "";

		// 1_Novedad_enfermero
		$this->_1_Novedad_enfermero->LinkCustomAttributes = "";
		$this->_1_Novedad_enfermero->HrefValue = "";
		$this->_1_Novedad_enfermero->TooltipValue = "";

		// 1_Punto_fuera_del_area_de_erradicacion
		$this->_1_Punto_fuera_del_area_de_erradicacion->LinkCustomAttributes = "";
		$this->_1_Punto_fuera_del_area_de_erradicacion->HrefValue = "";
		$this->_1_Punto_fuera_del_area_de_erradicacion->TooltipValue = "";

		// 1_Transporte_bus
		$this->_1_Transporte_bus->LinkCustomAttributes = "";
		$this->_1_Transporte_bus->HrefValue = "";
		$this->_1_Transporte_bus->TooltipValue = "";

		// 1_Traslado_apoyo_zonal
		$this->_1_Traslado_apoyo_zonal->LinkCustomAttributes = "";
		$this->_1_Traslado_apoyo_zonal->HrefValue = "";
		$this->_1_Traslado_apoyo_zonal->TooltipValue = "";

		// 1_Traslado_area_vivac
		$this->_1_Traslado_area_vivac->LinkCustomAttributes = "";
		$this->_1_Traslado_area_vivac->HrefValue = "";
		$this->_1_Traslado_area_vivac->TooltipValue = "";

		// 2_A_la_espera_definicion_nuevo_punto_FP
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->LinkCustomAttributes = "";
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->HrefValue = "";
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->TooltipValue = "";

		// 2_Espera_helicoptero_FP_de_seguridad
		$this->_2_Espera_helicoptero_FP_de_seguridad->LinkCustomAttributes = "";
		$this->_2_Espera_helicoptero_FP_de_seguridad->HrefValue = "";
		$this->_2_Espera_helicoptero_FP_de_seguridad->TooltipValue = "";

		// 2_Espera_helicoptero_FP_que_abastece
		$this->_2_Espera_helicoptero_FP_que_abastece->LinkCustomAttributes = "";
		$this->_2_Espera_helicoptero_FP_que_abastece->HrefValue = "";
		$this->_2_Espera_helicoptero_FP_que_abastece->TooltipValue = "";

		// 2_Induccion_FP
		$this->_2_Induccion_FP->LinkCustomAttributes = "";
		$this->_2_Induccion_FP->HrefValue = "";
		$this->_2_Induccion_FP->TooltipValue = "";

		// 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->LinkCustomAttributes = "";
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->HrefValue = "";
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->TooltipValue = "";

		// 2_Problemas_fuerza_publica
		$this->_2_Problemas_fuerza_publica->LinkCustomAttributes = "";
		$this->_2_Problemas_fuerza_publica->HrefValue = "";
		$this->_2_Problemas_fuerza_publica->TooltipValue = "";

		// 2_Sin_seguridad
		$this->_2_Sin_seguridad->LinkCustomAttributes = "";
		$this->_2_Sin_seguridad->HrefValue = "";
		$this->_2_Sin_seguridad->TooltipValue = "";

		// 3_AEI_controlado
		$this->_3_AEI_controlado->LinkCustomAttributes = "";
		$this->_3_AEI_controlado->HrefValue = "";
		$this->_3_AEI_controlado->TooltipValue = "";

		// 3_AEI_no_controlado
		$this->_3_AEI_no_controlado->LinkCustomAttributes = "";
		$this->_3_AEI_no_controlado->HrefValue = "";
		$this->_3_AEI_no_controlado->TooltipValue = "";

		// 3_Bloqueo_parcial_de_la_comunidad
		$this->_3_Bloqueo_parcial_de_la_comunidad->LinkCustomAttributes = "";
		$this->_3_Bloqueo_parcial_de_la_comunidad->HrefValue = "";
		$this->_3_Bloqueo_parcial_de_la_comunidad->TooltipValue = "";

		// 3_Bloqueo_total_de_la_comunidad
		$this->_3_Bloqueo_total_de_la_comunidad->LinkCustomAttributes = "";
		$this->_3_Bloqueo_total_de_la_comunidad->HrefValue = "";
		$this->_3_Bloqueo_total_de_la_comunidad->TooltipValue = "";

		// 3_Combate
		$this->_3_Combate->LinkCustomAttributes = "";
		$this->_3_Combate->HrefValue = "";
		$this->_3_Combate->TooltipValue = "";

		// 3_Hostigamiento
		$this->_3_Hostigamiento->LinkCustomAttributes = "";
		$this->_3_Hostigamiento->HrefValue = "";
		$this->_3_Hostigamiento->TooltipValue = "";

		// 3_MAP_Controlada
		$this->_3_MAP_Controlada->LinkCustomAttributes = "";
		$this->_3_MAP_Controlada->HrefValue = "";
		$this->_3_MAP_Controlada->TooltipValue = "";

		// 3_MAP_No_controlada
		$this->_3_MAP_No_controlada->LinkCustomAttributes = "";
		$this->_3_MAP_No_controlada->HrefValue = "";
		$this->_3_MAP_No_controlada->TooltipValue = "";

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad->LinkCustomAttributes = "";
		$this->_3_Operaciones_de_seguridad->HrefValue = "";
		$this->_3_Operaciones_de_seguridad->TooltipValue = "";

		// 4_Epidemia
		$this->_4_Epidemia->LinkCustomAttributes = "";
		$this->_4_Epidemia->HrefValue = "";
		$this->_4_Epidemia->TooltipValue = "";

		// 4_Novedad_climatologica
		$this->_4_Novedad_climatologica->LinkCustomAttributes = "";
		$this->_4_Novedad_climatologica->HrefValue = "";
		$this->_4_Novedad_climatologica->TooltipValue = "";

		// 4_Registro_de_cultivos
		$this->_4_Registro_de_cultivos->LinkCustomAttributes = "";
		$this->_4_Registro_de_cultivos->HrefValue = "";
		$this->_4_Registro_de_cultivos->TooltipValue = "";

		// 4_Zona_con_cultivos_muy_dispersos
		$this->_4_Zona_con_cultivos_muy_dispersos->LinkCustomAttributes = "";
		$this->_4_Zona_con_cultivos_muy_dispersos->HrefValue = "";
		$this->_4_Zona_con_cultivos_muy_dispersos->TooltipValue = "";

		// 4_Zona_de_cruce_de_rios_caudalosos
		$this->_4_Zona_de_cruce_de_rios_caudalosos->LinkCustomAttributes = "";
		$this->_4_Zona_de_cruce_de_rios_caudalosos->HrefValue = "";
		$this->_4_Zona_de_cruce_de_rios_caudalosos->TooltipValue = "";

		// 4_Zona_sin_cultivos
		$this->_4_Zona_sin_cultivos->LinkCustomAttributes = "";
		$this->_4_Zona_sin_cultivos->HrefValue = "";
		$this->_4_Zona_sin_cultivos->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Punto
		$this->Punto->EditAttrs["class"] = "form-control";
		$this->Punto->EditCustomAttributes = "";

		// Total_general
		$this->Total_general->EditAttrs["class"] = "form-control";
		$this->Total_general->EditCustomAttributes = "";
		$this->Total_general->EditValue = ew_HtmlEncode($this->Total_general->CurrentValue);
		$this->Total_general->PlaceHolder = ew_RemoveHtml($this->Total_general->FldCaption());

		// Dia_sin_novedad_especial
		$this->Dia_sin_novedad_especial->EditAttrs["class"] = "form-control";
		$this->Dia_sin_novedad_especial->EditCustomAttributes = "";
		$this->Dia_sin_novedad_especial->EditValue = ew_HtmlEncode($this->Dia_sin_novedad_especial->CurrentValue);
		$this->Dia_sin_novedad_especial->PlaceHolder = ew_RemoveHtml($this->Dia_sin_novedad_especial->FldCaption());
		if (strval($this->Dia_sin_novedad_especial->EditValue) <> "" && is_numeric($this->Dia_sin_novedad_especial->EditValue)) $this->Dia_sin_novedad_especial->EditValue = ew_FormatNumber($this->Dia_sin_novedad_especial->EditValue, -2, -1, -2, 0);

		// 1_Apoyo_zonal_sin_punto_asignado
		$this->_1_Apoyo_zonal_sin_punto_asignado->EditAttrs["class"] = "form-control";
		$this->_1_Apoyo_zonal_sin_punto_asignado->EditCustomAttributes = "";
		$this->_1_Apoyo_zonal_sin_punto_asignado->EditValue = ew_HtmlEncode($this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue);
		$this->_1_Apoyo_zonal_sin_punto_asignado->PlaceHolder = ew_RemoveHtml($this->_1_Apoyo_zonal_sin_punto_asignado->FldCaption());
		if (strval($this->_1_Apoyo_zonal_sin_punto_asignado->EditValue) <> "" && is_numeric($this->_1_Apoyo_zonal_sin_punto_asignado->EditValue)) $this->_1_Apoyo_zonal_sin_punto_asignado->EditValue = ew_FormatNumber($this->_1_Apoyo_zonal_sin_punto_asignado->EditValue, -2, -1, -2, 0);

		// 1_Descanso_en_dia_habil
		$this->_1_Descanso_en_dia_habil->EditAttrs["class"] = "form-control";
		$this->_1_Descanso_en_dia_habil->EditCustomAttributes = "";
		$this->_1_Descanso_en_dia_habil->EditValue = ew_HtmlEncode($this->_1_Descanso_en_dia_habil->CurrentValue);
		$this->_1_Descanso_en_dia_habil->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_en_dia_habil->FldCaption());
		if (strval($this->_1_Descanso_en_dia_habil->EditValue) <> "" && is_numeric($this->_1_Descanso_en_dia_habil->EditValue)) $this->_1_Descanso_en_dia_habil->EditValue = ew_FormatNumber($this->_1_Descanso_en_dia_habil->EditValue, -2, -1, -2, 0);

		// 1_Descanso_festivo_dominical
		$this->_1_Descanso_festivo_dominical->EditAttrs["class"] = "form-control";
		$this->_1_Descanso_festivo_dominical->EditCustomAttributes = "";
		$this->_1_Descanso_festivo_dominical->EditValue = ew_HtmlEncode($this->_1_Descanso_festivo_dominical->CurrentValue);
		$this->_1_Descanso_festivo_dominical->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_festivo_dominical->FldCaption());
		if (strval($this->_1_Descanso_festivo_dominical->EditValue) <> "" && is_numeric($this->_1_Descanso_festivo_dominical->EditValue)) $this->_1_Descanso_festivo_dominical->EditValue = ew_FormatNumber($this->_1_Descanso_festivo_dominical->EditValue, -2, -1, -2, 0);

		// 1_Dia_compensatorio
		$this->_1_Dia_compensatorio->EditAttrs["class"] = "form-control";
		$this->_1_Dia_compensatorio->EditCustomAttributes = "";
		$this->_1_Dia_compensatorio->EditValue = ew_HtmlEncode($this->_1_Dia_compensatorio->CurrentValue);
		$this->_1_Dia_compensatorio->PlaceHolder = ew_RemoveHtml($this->_1_Dia_compensatorio->FldCaption());
		if (strval($this->_1_Dia_compensatorio->EditValue) <> "" && is_numeric($this->_1_Dia_compensatorio->EditValue)) $this->_1_Dia_compensatorio->EditValue = ew_FormatNumber($this->_1_Dia_compensatorio->EditValue, -2, -1, -2, 0);

		// 1_Erradicacion_en_dia_festivo
		$this->_1_Erradicacion_en_dia_festivo->EditAttrs["class"] = "form-control";
		$this->_1_Erradicacion_en_dia_festivo->EditCustomAttributes = "";
		$this->_1_Erradicacion_en_dia_festivo->EditValue = ew_HtmlEncode($this->_1_Erradicacion_en_dia_festivo->CurrentValue);
		$this->_1_Erradicacion_en_dia_festivo->PlaceHolder = ew_RemoveHtml($this->_1_Erradicacion_en_dia_festivo->FldCaption());
		if (strval($this->_1_Erradicacion_en_dia_festivo->EditValue) <> "" && is_numeric($this->_1_Erradicacion_en_dia_festivo->EditValue)) $this->_1_Erradicacion_en_dia_festivo->EditValue = ew_FormatNumber($this->_1_Erradicacion_en_dia_festivo->EditValue, -2, -1, -2, 0);

		// 1_Espera_helicoptero_Helistar
		$this->_1_Espera_helicoptero_Helistar->EditAttrs["class"] = "form-control";
		$this->_1_Espera_helicoptero_Helistar->EditCustomAttributes = "";
		$this->_1_Espera_helicoptero_Helistar->EditValue = ew_HtmlEncode($this->_1_Espera_helicoptero_Helistar->CurrentValue);
		$this->_1_Espera_helicoptero_Helistar->PlaceHolder = ew_RemoveHtml($this->_1_Espera_helicoptero_Helistar->FldCaption());
		if (strval($this->_1_Espera_helicoptero_Helistar->EditValue) <> "" && is_numeric($this->_1_Espera_helicoptero_Helistar->EditValue)) $this->_1_Espera_helicoptero_Helistar->EditValue = ew_FormatNumber($this->_1_Espera_helicoptero_Helistar->EditValue, -2, -1, -2, 0);

		// 1_Extraccion
		$this->_1_Extraccion->EditAttrs["class"] = "form-control";
		$this->_1_Extraccion->EditCustomAttributes = "";
		$this->_1_Extraccion->EditValue = ew_HtmlEncode($this->_1_Extraccion->CurrentValue);
		$this->_1_Extraccion->PlaceHolder = ew_RemoveHtml($this->_1_Extraccion->FldCaption());
		if (strval($this->_1_Extraccion->EditValue) <> "" && is_numeric($this->_1_Extraccion->EditValue)) $this->_1_Extraccion->EditValue = ew_FormatNumber($this->_1_Extraccion->EditValue, -2, -1, -2, 0);

		// 1_Firma_contrato_GME
		$this->_1_Firma_contrato_GME->EditAttrs["class"] = "form-control";
		$this->_1_Firma_contrato_GME->EditCustomAttributes = "";
		$this->_1_Firma_contrato_GME->EditValue = ew_HtmlEncode($this->_1_Firma_contrato_GME->CurrentValue);
		$this->_1_Firma_contrato_GME->PlaceHolder = ew_RemoveHtml($this->_1_Firma_contrato_GME->FldCaption());
		if (strval($this->_1_Firma_contrato_GME->EditValue) <> "" && is_numeric($this->_1_Firma_contrato_GME->EditValue)) $this->_1_Firma_contrato_GME->EditValue = ew_FormatNumber($this->_1_Firma_contrato_GME->EditValue, -2, -1, -2, 0);

		// 1_Induccion_Apoyo_Zonal
		$this->_1_Induccion_Apoyo_Zonal->EditAttrs["class"] = "form-control";
		$this->_1_Induccion_Apoyo_Zonal->EditCustomAttributes = "";
		$this->_1_Induccion_Apoyo_Zonal->EditValue = ew_HtmlEncode($this->_1_Induccion_Apoyo_Zonal->CurrentValue);
		$this->_1_Induccion_Apoyo_Zonal->PlaceHolder = ew_RemoveHtml($this->_1_Induccion_Apoyo_Zonal->FldCaption());
		if (strval($this->_1_Induccion_Apoyo_Zonal->EditValue) <> "" && is_numeric($this->_1_Induccion_Apoyo_Zonal->EditValue)) $this->_1_Induccion_Apoyo_Zonal->EditValue = ew_FormatNumber($this->_1_Induccion_Apoyo_Zonal->EditValue, -2, -1, -2, 0);

		// 1_Insercion
		$this->_1_Insercion->EditAttrs["class"] = "form-control";
		$this->_1_Insercion->EditCustomAttributes = "";
		$this->_1_Insercion->EditValue = ew_HtmlEncode($this->_1_Insercion->CurrentValue);
		$this->_1_Insercion->PlaceHolder = ew_RemoveHtml($this->_1_Insercion->FldCaption());
		if (strval($this->_1_Insercion->EditValue) <> "" && is_numeric($this->_1_Insercion->EditValue)) $this->_1_Insercion->EditValue = ew_FormatNumber($this->_1_Insercion->EditValue, -2, -1, -2, 0);

		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditAttrs["class"] = "form-control";
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditCustomAttributes = "";
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue = ew_HtmlEncode($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue);
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->PlaceHolder = ew_RemoveHtml($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption());
		if (strval($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue) <> "" && is_numeric($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue)) $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue = ew_FormatNumber($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue, -2, -1, -2, 0);

		// 1_Novedad_apoyo_zonal
		$this->_1_Novedad_apoyo_zonal->EditAttrs["class"] = "form-control";
		$this->_1_Novedad_apoyo_zonal->EditCustomAttributes = "";
		$this->_1_Novedad_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Novedad_apoyo_zonal->CurrentValue);
		$this->_1_Novedad_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_apoyo_zonal->FldCaption());
		if (strval($this->_1_Novedad_apoyo_zonal->EditValue) <> "" && is_numeric($this->_1_Novedad_apoyo_zonal->EditValue)) $this->_1_Novedad_apoyo_zonal->EditValue = ew_FormatNumber($this->_1_Novedad_apoyo_zonal->EditValue, -2, -1, -2, 0);

		// 1_Novedad_enfermero
		$this->_1_Novedad_enfermero->EditAttrs["class"] = "form-control";
		$this->_1_Novedad_enfermero->EditCustomAttributes = "";
		$this->_1_Novedad_enfermero->EditValue = ew_HtmlEncode($this->_1_Novedad_enfermero->CurrentValue);
		$this->_1_Novedad_enfermero->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_enfermero->FldCaption());
		if (strval($this->_1_Novedad_enfermero->EditValue) <> "" && is_numeric($this->_1_Novedad_enfermero->EditValue)) $this->_1_Novedad_enfermero->EditValue = ew_FormatNumber($this->_1_Novedad_enfermero->EditValue, -2, -1, -2, 0);

		// 1_Punto_fuera_del_area_de_erradicacion
		$this->_1_Punto_fuera_del_area_de_erradicacion->EditAttrs["class"] = "form-control";
		$this->_1_Punto_fuera_del_area_de_erradicacion->EditCustomAttributes = "";
		$this->_1_Punto_fuera_del_area_de_erradicacion->EditValue = ew_HtmlEncode($this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue);
		$this->_1_Punto_fuera_del_area_de_erradicacion->PlaceHolder = ew_RemoveHtml($this->_1_Punto_fuera_del_area_de_erradicacion->FldCaption());
		if (strval($this->_1_Punto_fuera_del_area_de_erradicacion->EditValue) <> "" && is_numeric($this->_1_Punto_fuera_del_area_de_erradicacion->EditValue)) $this->_1_Punto_fuera_del_area_de_erradicacion->EditValue = ew_FormatNumber($this->_1_Punto_fuera_del_area_de_erradicacion->EditValue, -2, -1, -2, 0);

		// 1_Transporte_bus
		$this->_1_Transporte_bus->EditAttrs["class"] = "form-control";
		$this->_1_Transporte_bus->EditCustomAttributes = "";
		$this->_1_Transporte_bus->EditValue = ew_HtmlEncode($this->_1_Transporte_bus->CurrentValue);
		$this->_1_Transporte_bus->PlaceHolder = ew_RemoveHtml($this->_1_Transporte_bus->FldCaption());
		if (strval($this->_1_Transporte_bus->EditValue) <> "" && is_numeric($this->_1_Transporte_bus->EditValue)) $this->_1_Transporte_bus->EditValue = ew_FormatNumber($this->_1_Transporte_bus->EditValue, -2, -1, -2, 0);

		// 1_Traslado_apoyo_zonal
		$this->_1_Traslado_apoyo_zonal->EditAttrs["class"] = "form-control";
		$this->_1_Traslado_apoyo_zonal->EditCustomAttributes = "";
		$this->_1_Traslado_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Traslado_apoyo_zonal->CurrentValue);
		$this->_1_Traslado_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_apoyo_zonal->FldCaption());
		if (strval($this->_1_Traslado_apoyo_zonal->EditValue) <> "" && is_numeric($this->_1_Traslado_apoyo_zonal->EditValue)) $this->_1_Traslado_apoyo_zonal->EditValue = ew_FormatNumber($this->_1_Traslado_apoyo_zonal->EditValue, -2, -1, -2, 0);

		// 1_Traslado_area_vivac
		$this->_1_Traslado_area_vivac->EditAttrs["class"] = "form-control";
		$this->_1_Traslado_area_vivac->EditCustomAttributes = "";
		$this->_1_Traslado_area_vivac->EditValue = ew_HtmlEncode($this->_1_Traslado_area_vivac->CurrentValue);
		$this->_1_Traslado_area_vivac->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_area_vivac->FldCaption());
		if (strval($this->_1_Traslado_area_vivac->EditValue) <> "" && is_numeric($this->_1_Traslado_area_vivac->EditValue)) $this->_1_Traslado_area_vivac->EditValue = ew_FormatNumber($this->_1_Traslado_area_vivac->EditValue, -2, -1, -2, 0);

		// 2_A_la_espera_definicion_nuevo_punto_FP
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditAttrs["class"] = "form-control";
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditCustomAttributes = "";
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue = ew_HtmlEncode($this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue);
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->PlaceHolder = ew_RemoveHtml($this->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption());
		if (strval($this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue) <> "" && is_numeric($this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue)) $this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue = ew_FormatNumber($this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue, -2, -1, -2, 0);

		// 2_Espera_helicoptero_FP_de_seguridad
		$this->_2_Espera_helicoptero_FP_de_seguridad->EditAttrs["class"] = "form-control";
		$this->_2_Espera_helicoptero_FP_de_seguridad->EditCustomAttributes = "";
		$this->_2_Espera_helicoptero_FP_de_seguridad->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue);
		$this->_2_Espera_helicoptero_FP_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_de_seguridad->FldCaption());
		if (strval($this->_2_Espera_helicoptero_FP_de_seguridad->EditValue) <> "" && is_numeric($this->_2_Espera_helicoptero_FP_de_seguridad->EditValue)) $this->_2_Espera_helicoptero_FP_de_seguridad->EditValue = ew_FormatNumber($this->_2_Espera_helicoptero_FP_de_seguridad->EditValue, -2, -1, -2, 0);

		// 2_Espera_helicoptero_FP_que_abastece
		$this->_2_Espera_helicoptero_FP_que_abastece->EditAttrs["class"] = "form-control";
		$this->_2_Espera_helicoptero_FP_que_abastece->EditCustomAttributes = "";
		$this->_2_Espera_helicoptero_FP_que_abastece->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue);
		$this->_2_Espera_helicoptero_FP_que_abastece->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_que_abastece->FldCaption());
		if (strval($this->_2_Espera_helicoptero_FP_que_abastece->EditValue) <> "" && is_numeric($this->_2_Espera_helicoptero_FP_que_abastece->EditValue)) $this->_2_Espera_helicoptero_FP_que_abastece->EditValue = ew_FormatNumber($this->_2_Espera_helicoptero_FP_que_abastece->EditValue, -2, -1, -2, 0);

		// 2_Induccion_FP
		$this->_2_Induccion_FP->EditAttrs["class"] = "form-control";
		$this->_2_Induccion_FP->EditCustomAttributes = "";
		$this->_2_Induccion_FP->EditValue = ew_HtmlEncode($this->_2_Induccion_FP->CurrentValue);
		$this->_2_Induccion_FP->PlaceHolder = ew_RemoveHtml($this->_2_Induccion_FP->FldCaption());
		if (strval($this->_2_Induccion_FP->EditValue) <> "" && is_numeric($this->_2_Induccion_FP->EditValue)) $this->_2_Induccion_FP->EditValue = ew_FormatNumber($this->_2_Induccion_FP->EditValue, -2, -1, -2, 0);

		// 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditAttrs["class"] = "form-control";
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditCustomAttributes = "";
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue = ew_HtmlEncode($this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue);
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->PlaceHolder = ew_RemoveHtml($this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption());
		if (strval($this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue) <> "" && is_numeric($this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue)) $this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue = ew_FormatNumber($this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue, -2, -1, -2, 0);

		// 2_Problemas_fuerza_publica
		$this->_2_Problemas_fuerza_publica->EditAttrs["class"] = "form-control";
		$this->_2_Problemas_fuerza_publica->EditCustomAttributes = "";
		$this->_2_Problemas_fuerza_publica->EditValue = ew_HtmlEncode($this->_2_Problemas_fuerza_publica->CurrentValue);
		$this->_2_Problemas_fuerza_publica->PlaceHolder = ew_RemoveHtml($this->_2_Problemas_fuerza_publica->FldCaption());
		if (strval($this->_2_Problemas_fuerza_publica->EditValue) <> "" && is_numeric($this->_2_Problemas_fuerza_publica->EditValue)) $this->_2_Problemas_fuerza_publica->EditValue = ew_FormatNumber($this->_2_Problemas_fuerza_publica->EditValue, -2, -1, -2, 0);

		// 2_Sin_seguridad
		$this->_2_Sin_seguridad->EditAttrs["class"] = "form-control";
		$this->_2_Sin_seguridad->EditCustomAttributes = "";
		$this->_2_Sin_seguridad->EditValue = ew_HtmlEncode($this->_2_Sin_seguridad->CurrentValue);
		$this->_2_Sin_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Sin_seguridad->FldCaption());
		if (strval($this->_2_Sin_seguridad->EditValue) <> "" && is_numeric($this->_2_Sin_seguridad->EditValue)) $this->_2_Sin_seguridad->EditValue = ew_FormatNumber($this->_2_Sin_seguridad->EditValue, -2, -1, -2, 0);

		// 3_AEI_controlado
		$this->_3_AEI_controlado->EditAttrs["class"] = "form-control";
		$this->_3_AEI_controlado->EditCustomAttributes = "";
		$this->_3_AEI_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_controlado->CurrentValue);
		$this->_3_AEI_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_controlado->FldCaption());
		if (strval($this->_3_AEI_controlado->EditValue) <> "" && is_numeric($this->_3_AEI_controlado->EditValue)) $this->_3_AEI_controlado->EditValue = ew_FormatNumber($this->_3_AEI_controlado->EditValue, -2, -1, -2, 0);

		// 3_AEI_no_controlado
		$this->_3_AEI_no_controlado->EditAttrs["class"] = "form-control";
		$this->_3_AEI_no_controlado->EditCustomAttributes = "";
		$this->_3_AEI_no_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_no_controlado->CurrentValue);
		$this->_3_AEI_no_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_no_controlado->FldCaption());
		if (strval($this->_3_AEI_no_controlado->EditValue) <> "" && is_numeric($this->_3_AEI_no_controlado->EditValue)) $this->_3_AEI_no_controlado->EditValue = ew_FormatNumber($this->_3_AEI_no_controlado->EditValue, -2, -1, -2, 0);

		// 3_Bloqueo_parcial_de_la_comunidad
		$this->_3_Bloqueo_parcial_de_la_comunidad->EditAttrs["class"] = "form-control";
		$this->_3_Bloqueo_parcial_de_la_comunidad->EditCustomAttributes = "";
		$this->_3_Bloqueo_parcial_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue);
		$this->_3_Bloqueo_parcial_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_parcial_de_la_comunidad->FldCaption());
		if (strval($this->_3_Bloqueo_parcial_de_la_comunidad->EditValue) <> "" && is_numeric($this->_3_Bloqueo_parcial_de_la_comunidad->EditValue)) $this->_3_Bloqueo_parcial_de_la_comunidad->EditValue = ew_FormatNumber($this->_3_Bloqueo_parcial_de_la_comunidad->EditValue, -2, -1, -2, 0);

		// 3_Bloqueo_total_de_la_comunidad
		$this->_3_Bloqueo_total_de_la_comunidad->EditAttrs["class"] = "form-control";
		$this->_3_Bloqueo_total_de_la_comunidad->EditCustomAttributes = "";
		$this->_3_Bloqueo_total_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_total_de_la_comunidad->CurrentValue);
		$this->_3_Bloqueo_total_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_total_de_la_comunidad->FldCaption());
		if (strval($this->_3_Bloqueo_total_de_la_comunidad->EditValue) <> "" && is_numeric($this->_3_Bloqueo_total_de_la_comunidad->EditValue)) $this->_3_Bloqueo_total_de_la_comunidad->EditValue = ew_FormatNumber($this->_3_Bloqueo_total_de_la_comunidad->EditValue, -2, -1, -2, 0);

		// 3_Combate
		$this->_3_Combate->EditAttrs["class"] = "form-control";
		$this->_3_Combate->EditCustomAttributes = "";
		$this->_3_Combate->EditValue = ew_HtmlEncode($this->_3_Combate->CurrentValue);
		$this->_3_Combate->PlaceHolder = ew_RemoveHtml($this->_3_Combate->FldCaption());
		if (strval($this->_3_Combate->EditValue) <> "" && is_numeric($this->_3_Combate->EditValue)) $this->_3_Combate->EditValue = ew_FormatNumber($this->_3_Combate->EditValue, -2, -1, -2, 0);

		// 3_Hostigamiento
		$this->_3_Hostigamiento->EditAttrs["class"] = "form-control";
		$this->_3_Hostigamiento->EditCustomAttributes = "";
		$this->_3_Hostigamiento->EditValue = ew_HtmlEncode($this->_3_Hostigamiento->CurrentValue);
		$this->_3_Hostigamiento->PlaceHolder = ew_RemoveHtml($this->_3_Hostigamiento->FldCaption());
		if (strval($this->_3_Hostigamiento->EditValue) <> "" && is_numeric($this->_3_Hostigamiento->EditValue)) $this->_3_Hostigamiento->EditValue = ew_FormatNumber($this->_3_Hostigamiento->EditValue, -2, -1, -2, 0);

		// 3_MAP_Controlada
		$this->_3_MAP_Controlada->EditAttrs["class"] = "form-control";
		$this->_3_MAP_Controlada->EditCustomAttributes = "";
		$this->_3_MAP_Controlada->EditValue = ew_HtmlEncode($this->_3_MAP_Controlada->CurrentValue);
		$this->_3_MAP_Controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_Controlada->FldCaption());
		if (strval($this->_3_MAP_Controlada->EditValue) <> "" && is_numeric($this->_3_MAP_Controlada->EditValue)) $this->_3_MAP_Controlada->EditValue = ew_FormatNumber($this->_3_MAP_Controlada->EditValue, -2, -1, -2, 0);

		// 3_MAP_No_controlada
		$this->_3_MAP_No_controlada->EditAttrs["class"] = "form-control";
		$this->_3_MAP_No_controlada->EditCustomAttributes = "";
		$this->_3_MAP_No_controlada->EditValue = ew_HtmlEncode($this->_3_MAP_No_controlada->CurrentValue);
		$this->_3_MAP_No_controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_No_controlada->FldCaption());
		if (strval($this->_3_MAP_No_controlada->EditValue) <> "" && is_numeric($this->_3_MAP_No_controlada->EditValue)) $this->_3_MAP_No_controlada->EditValue = ew_FormatNumber($this->_3_MAP_No_controlada->EditValue, -2, -1, -2, 0);

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad->EditAttrs["class"] = "form-control";
		$this->_3_Operaciones_de_seguridad->EditCustomAttributes = "";
		$this->_3_Operaciones_de_seguridad->EditValue = ew_HtmlEncode($this->_3_Operaciones_de_seguridad->CurrentValue);
		$this->_3_Operaciones_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_3_Operaciones_de_seguridad->FldCaption());
		if (strval($this->_3_Operaciones_de_seguridad->EditValue) <> "" && is_numeric($this->_3_Operaciones_de_seguridad->EditValue)) $this->_3_Operaciones_de_seguridad->EditValue = ew_FormatNumber($this->_3_Operaciones_de_seguridad->EditValue, -2, -1, -2, 0);

		// 4_Epidemia
		$this->_4_Epidemia->EditAttrs["class"] = "form-control";
		$this->_4_Epidemia->EditCustomAttributes = "";
		$this->_4_Epidemia->EditValue = ew_HtmlEncode($this->_4_Epidemia->CurrentValue);
		$this->_4_Epidemia->PlaceHolder = ew_RemoveHtml($this->_4_Epidemia->FldCaption());
		if (strval($this->_4_Epidemia->EditValue) <> "" && is_numeric($this->_4_Epidemia->EditValue)) $this->_4_Epidemia->EditValue = ew_FormatNumber($this->_4_Epidemia->EditValue, -2, -1, -2, 0);

		// 4_Novedad_climatologica
		$this->_4_Novedad_climatologica->EditAttrs["class"] = "form-control";
		$this->_4_Novedad_climatologica->EditCustomAttributes = "";
		$this->_4_Novedad_climatologica->EditValue = ew_HtmlEncode($this->_4_Novedad_climatologica->CurrentValue);
		$this->_4_Novedad_climatologica->PlaceHolder = ew_RemoveHtml($this->_4_Novedad_climatologica->FldCaption());
		if (strval($this->_4_Novedad_climatologica->EditValue) <> "" && is_numeric($this->_4_Novedad_climatologica->EditValue)) $this->_4_Novedad_climatologica->EditValue = ew_FormatNumber($this->_4_Novedad_climatologica->EditValue, -2, -1, -2, 0);

		// 4_Registro_de_cultivos
		$this->_4_Registro_de_cultivos->EditAttrs["class"] = "form-control";
		$this->_4_Registro_de_cultivos->EditCustomAttributes = "";
		$this->_4_Registro_de_cultivos->EditValue = ew_HtmlEncode($this->_4_Registro_de_cultivos->CurrentValue);
		$this->_4_Registro_de_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Registro_de_cultivos->FldCaption());
		if (strval($this->_4_Registro_de_cultivos->EditValue) <> "" && is_numeric($this->_4_Registro_de_cultivos->EditValue)) $this->_4_Registro_de_cultivos->EditValue = ew_FormatNumber($this->_4_Registro_de_cultivos->EditValue, -2, -1, -2, 0);

		// 4_Zona_con_cultivos_muy_dispersos
		$this->_4_Zona_con_cultivos_muy_dispersos->EditAttrs["class"] = "form-control";
		$this->_4_Zona_con_cultivos_muy_dispersos->EditCustomAttributes = "";
		$this->_4_Zona_con_cultivos_muy_dispersos->EditValue = ew_HtmlEncode($this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue);
		$this->_4_Zona_con_cultivos_muy_dispersos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_con_cultivos_muy_dispersos->FldCaption());
		if (strval($this->_4_Zona_con_cultivos_muy_dispersos->EditValue) <> "" && is_numeric($this->_4_Zona_con_cultivos_muy_dispersos->EditValue)) $this->_4_Zona_con_cultivos_muy_dispersos->EditValue = ew_FormatNumber($this->_4_Zona_con_cultivos_muy_dispersos->EditValue, -2, -1, -2, 0);

		// 4_Zona_de_cruce_de_rios_caudalosos
		$this->_4_Zona_de_cruce_de_rios_caudalosos->EditAttrs["class"] = "form-control";
		$this->_4_Zona_de_cruce_de_rios_caudalosos->EditCustomAttributes = "";
		$this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue = ew_HtmlEncode($this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue);
		$this->_4_Zona_de_cruce_de_rios_caudalosos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption());
		if (strval($this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue) <> "" && is_numeric($this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue)) $this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue = ew_FormatNumber($this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue, -2, -1, -2, 0);

		// 4_Zona_sin_cultivos
		$this->_4_Zona_sin_cultivos->EditAttrs["class"] = "form-control";
		$this->_4_Zona_sin_cultivos->EditCustomAttributes = "";
		$this->_4_Zona_sin_cultivos->EditValue = ew_HtmlEncode($this->_4_Zona_sin_cultivos->CurrentValue);
		$this->_4_Zona_sin_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_sin_cultivos->FldCaption());
		if (strval($this->_4_Zona_sin_cultivos->EditValue) <> "" && is_numeric($this->_4_Zona_sin_cultivos->EditValue)) $this->_4_Zona_sin_cultivos->EditValue = ew_FormatNumber($this->_4_Zona_sin_cultivos->EditValue, -2, -1, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->Total_general->Exportable) $Doc->ExportCaption($this->Total_general);
					if ($this->Dia_sin_novedad_especial->Exportable) $Doc->ExportCaption($this->Dia_sin_novedad_especial);
					if ($this->_1_Apoyo_zonal_sin_punto_asignado->Exportable) $Doc->ExportCaption($this->_1_Apoyo_zonal_sin_punto_asignado);
					if ($this->_1_Descanso_en_dia_habil->Exportable) $Doc->ExportCaption($this->_1_Descanso_en_dia_habil);
					if ($this->_1_Descanso_festivo_dominical->Exportable) $Doc->ExportCaption($this->_1_Descanso_festivo_dominical);
					if ($this->_1_Dia_compensatorio->Exportable) $Doc->ExportCaption($this->_1_Dia_compensatorio);
					if ($this->_1_Erradicacion_en_dia_festivo->Exportable) $Doc->ExportCaption($this->_1_Erradicacion_en_dia_festivo);
					if ($this->_1_Espera_helicoptero_Helistar->Exportable) $Doc->ExportCaption($this->_1_Espera_helicoptero_Helistar);
					if ($this->_1_Extraccion->Exportable) $Doc->ExportCaption($this->_1_Extraccion);
					if ($this->_1_Firma_contrato_GME->Exportable) $Doc->ExportCaption($this->_1_Firma_contrato_GME);
					if ($this->_1_Induccion_Apoyo_Zonal->Exportable) $Doc->ExportCaption($this->_1_Induccion_Apoyo_Zonal);
					if ($this->_1_Insercion->Exportable) $Doc->ExportCaption($this->_1_Insercion);
					if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Exportable) $Doc->ExportCaption($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase);
					if ($this->_1_Novedad_apoyo_zonal->Exportable) $Doc->ExportCaption($this->_1_Novedad_apoyo_zonal);
					if ($this->_1_Novedad_enfermero->Exportable) $Doc->ExportCaption($this->_1_Novedad_enfermero);
					if ($this->_1_Punto_fuera_del_area_de_erradicacion->Exportable) $Doc->ExportCaption($this->_1_Punto_fuera_del_area_de_erradicacion);
					if ($this->_1_Transporte_bus->Exportable) $Doc->ExportCaption($this->_1_Transporte_bus);
					if ($this->_1_Traslado_apoyo_zonal->Exportable) $Doc->ExportCaption($this->_1_Traslado_apoyo_zonal);
					if ($this->_1_Traslado_area_vivac->Exportable) $Doc->ExportCaption($this->_1_Traslado_area_vivac);
					if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportCaption($this->_2_A_la_espera_definicion_nuevo_punto_FP);
					if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_de_seguridad);
					if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_que_abastece);
					if ($this->_2_Induccion_FP->Exportable) $Doc->ExportCaption($this->_2_Induccion_FP);
					if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportCaption($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
					if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportCaption($this->_2_Problemas_fuerza_publica);
					if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportCaption($this->_2_Sin_seguridad);
					if ($this->_3_AEI_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_controlado);
					if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_no_controlado);
					if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_parcial_de_la_comunidad);
					if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_total_de_la_comunidad);
					if ($this->_3_Combate->Exportable) $Doc->ExportCaption($this->_3_Combate);
					if ($this->_3_Hostigamiento->Exportable) $Doc->ExportCaption($this->_3_Hostigamiento);
					if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_Controlada);
					if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_No_controlada);
					if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportCaption($this->_3_Operaciones_de_seguridad);
					if ($this->_4_Epidemia->Exportable) $Doc->ExportCaption($this->_4_Epidemia);
					if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportCaption($this->_4_Novedad_climatologica);
					if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportCaption($this->_4_Registro_de_cultivos);
					if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportCaption($this->_4_Zona_con_cultivos_muy_dispersos);
					if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportCaption($this->_4_Zona_de_cruce_de_rios_caudalosos);
					if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportCaption($this->_4_Zona_sin_cultivos);
				} else {
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->Total_general->Exportable) $Doc->ExportCaption($this->Total_general);
					if ($this->Dia_sin_novedad_especial->Exportable) $Doc->ExportCaption($this->Dia_sin_novedad_especial);
					if ($this->_1_Apoyo_zonal_sin_punto_asignado->Exportable) $Doc->ExportCaption($this->_1_Apoyo_zonal_sin_punto_asignado);
					if ($this->_1_Descanso_en_dia_habil->Exportable) $Doc->ExportCaption($this->_1_Descanso_en_dia_habil);
					if ($this->_1_Descanso_festivo_dominical->Exportable) $Doc->ExportCaption($this->_1_Descanso_festivo_dominical);
					if ($this->_1_Dia_compensatorio->Exportable) $Doc->ExportCaption($this->_1_Dia_compensatorio);
					if ($this->_1_Erradicacion_en_dia_festivo->Exportable) $Doc->ExportCaption($this->_1_Erradicacion_en_dia_festivo);
					if ($this->_1_Espera_helicoptero_Helistar->Exportable) $Doc->ExportCaption($this->_1_Espera_helicoptero_Helistar);
					if ($this->_1_Extraccion->Exportable) $Doc->ExportCaption($this->_1_Extraccion);
					if ($this->_1_Firma_contrato_GME->Exportable) $Doc->ExportCaption($this->_1_Firma_contrato_GME);
					if ($this->_1_Induccion_Apoyo_Zonal->Exportable) $Doc->ExportCaption($this->_1_Induccion_Apoyo_Zonal);
					if ($this->_1_Insercion->Exportable) $Doc->ExportCaption($this->_1_Insercion);
					if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Exportable) $Doc->ExportCaption($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase);
					if ($this->_1_Novedad_apoyo_zonal->Exportable) $Doc->ExportCaption($this->_1_Novedad_apoyo_zonal);
					if ($this->_1_Novedad_enfermero->Exportable) $Doc->ExportCaption($this->_1_Novedad_enfermero);
					if ($this->_1_Punto_fuera_del_area_de_erradicacion->Exportable) $Doc->ExportCaption($this->_1_Punto_fuera_del_area_de_erradicacion);
					if ($this->_1_Transporte_bus->Exportable) $Doc->ExportCaption($this->_1_Transporte_bus);
					if ($this->_1_Traslado_apoyo_zonal->Exportable) $Doc->ExportCaption($this->_1_Traslado_apoyo_zonal);
					if ($this->_1_Traslado_area_vivac->Exportable) $Doc->ExportCaption($this->_1_Traslado_area_vivac);
					if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportCaption($this->_2_A_la_espera_definicion_nuevo_punto_FP);
					if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_de_seguridad);
					if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_que_abastece);
					if ($this->_2_Induccion_FP->Exportable) $Doc->ExportCaption($this->_2_Induccion_FP);
					if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportCaption($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
					if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportCaption($this->_2_Problemas_fuerza_publica);
					if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportCaption($this->_2_Sin_seguridad);
					if ($this->_3_AEI_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_controlado);
					if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_no_controlado);
					if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_parcial_de_la_comunidad);
					if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_total_de_la_comunidad);
					if ($this->_3_Combate->Exportable) $Doc->ExportCaption($this->_3_Combate);
					if ($this->_3_Hostigamiento->Exportable) $Doc->ExportCaption($this->_3_Hostigamiento);
					if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_Controlada);
					if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_No_controlada);
					if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportCaption($this->_3_Operaciones_de_seguridad);
					if ($this->_4_Epidemia->Exportable) $Doc->ExportCaption($this->_4_Epidemia);
					if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportCaption($this->_4_Novedad_climatologica);
					if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportCaption($this->_4_Registro_de_cultivos);
					if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportCaption($this->_4_Zona_con_cultivos_muy_dispersos);
					if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportCaption($this->_4_Zona_de_cruce_de_rios_caudalosos);
					if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportCaption($this->_4_Zona_sin_cultivos);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->Total_general->Exportable) $Doc->ExportField($this->Total_general);
						if ($this->Dia_sin_novedad_especial->Exportable) $Doc->ExportField($this->Dia_sin_novedad_especial);
						if ($this->_1_Apoyo_zonal_sin_punto_asignado->Exportable) $Doc->ExportField($this->_1_Apoyo_zonal_sin_punto_asignado);
						if ($this->_1_Descanso_en_dia_habil->Exportable) $Doc->ExportField($this->_1_Descanso_en_dia_habil);
						if ($this->_1_Descanso_festivo_dominical->Exportable) $Doc->ExportField($this->_1_Descanso_festivo_dominical);
						if ($this->_1_Dia_compensatorio->Exportable) $Doc->ExportField($this->_1_Dia_compensatorio);
						if ($this->_1_Erradicacion_en_dia_festivo->Exportable) $Doc->ExportField($this->_1_Erradicacion_en_dia_festivo);
						if ($this->_1_Espera_helicoptero_Helistar->Exportable) $Doc->ExportField($this->_1_Espera_helicoptero_Helistar);
						if ($this->_1_Extraccion->Exportable) $Doc->ExportField($this->_1_Extraccion);
						if ($this->_1_Firma_contrato_GME->Exportable) $Doc->ExportField($this->_1_Firma_contrato_GME);
						if ($this->_1_Induccion_Apoyo_Zonal->Exportable) $Doc->ExportField($this->_1_Induccion_Apoyo_Zonal);
						if ($this->_1_Insercion->Exportable) $Doc->ExportField($this->_1_Insercion);
						if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Exportable) $Doc->ExportField($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase);
						if ($this->_1_Novedad_apoyo_zonal->Exportable) $Doc->ExportField($this->_1_Novedad_apoyo_zonal);
						if ($this->_1_Novedad_enfermero->Exportable) $Doc->ExportField($this->_1_Novedad_enfermero);
						if ($this->_1_Punto_fuera_del_area_de_erradicacion->Exportable) $Doc->ExportField($this->_1_Punto_fuera_del_area_de_erradicacion);
						if ($this->_1_Transporte_bus->Exportable) $Doc->ExportField($this->_1_Transporte_bus);
						if ($this->_1_Traslado_apoyo_zonal->Exportable) $Doc->ExportField($this->_1_Traslado_apoyo_zonal);
						if ($this->_1_Traslado_area_vivac->Exportable) $Doc->ExportField($this->_1_Traslado_area_vivac);
						if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportField($this->_2_A_la_espera_definicion_nuevo_punto_FP);
						if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_de_seguridad);
						if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_que_abastece);
						if ($this->_2_Induccion_FP->Exportable) $Doc->ExportField($this->_2_Induccion_FP);
						if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportField($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
						if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportField($this->_2_Problemas_fuerza_publica);
						if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportField($this->_2_Sin_seguridad);
						if ($this->_3_AEI_controlado->Exportable) $Doc->ExportField($this->_3_AEI_controlado);
						if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportField($this->_3_AEI_no_controlado);
						if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_parcial_de_la_comunidad);
						if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_total_de_la_comunidad);
						if ($this->_3_Combate->Exportable) $Doc->ExportField($this->_3_Combate);
						if ($this->_3_Hostigamiento->Exportable) $Doc->ExportField($this->_3_Hostigamiento);
						if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportField($this->_3_MAP_Controlada);
						if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportField($this->_3_MAP_No_controlada);
						if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportField($this->_3_Operaciones_de_seguridad);
						if ($this->_4_Epidemia->Exportable) $Doc->ExportField($this->_4_Epidemia);
						if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportField($this->_4_Novedad_climatologica);
						if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportField($this->_4_Registro_de_cultivos);
						if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportField($this->_4_Zona_con_cultivos_muy_dispersos);
						if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportField($this->_4_Zona_de_cruce_de_rios_caudalosos);
						if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportField($this->_4_Zona_sin_cultivos);
					} else {
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->Total_general->Exportable) $Doc->ExportField($this->Total_general);
						if ($this->Dia_sin_novedad_especial->Exportable) $Doc->ExportField($this->Dia_sin_novedad_especial);
						if ($this->_1_Apoyo_zonal_sin_punto_asignado->Exportable) $Doc->ExportField($this->_1_Apoyo_zonal_sin_punto_asignado);
						if ($this->_1_Descanso_en_dia_habil->Exportable) $Doc->ExportField($this->_1_Descanso_en_dia_habil);
						if ($this->_1_Descanso_festivo_dominical->Exportable) $Doc->ExportField($this->_1_Descanso_festivo_dominical);
						if ($this->_1_Dia_compensatorio->Exportable) $Doc->ExportField($this->_1_Dia_compensatorio);
						if ($this->_1_Erradicacion_en_dia_festivo->Exportable) $Doc->ExportField($this->_1_Erradicacion_en_dia_festivo);
						if ($this->_1_Espera_helicoptero_Helistar->Exportable) $Doc->ExportField($this->_1_Espera_helicoptero_Helistar);
						if ($this->_1_Extraccion->Exportable) $Doc->ExportField($this->_1_Extraccion);
						if ($this->_1_Firma_contrato_GME->Exportable) $Doc->ExportField($this->_1_Firma_contrato_GME);
						if ($this->_1_Induccion_Apoyo_Zonal->Exportable) $Doc->ExportField($this->_1_Induccion_Apoyo_Zonal);
						if ($this->_1_Insercion->Exportable) $Doc->ExportField($this->_1_Insercion);
						if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Exportable) $Doc->ExportField($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase);
						if ($this->_1_Novedad_apoyo_zonal->Exportable) $Doc->ExportField($this->_1_Novedad_apoyo_zonal);
						if ($this->_1_Novedad_enfermero->Exportable) $Doc->ExportField($this->_1_Novedad_enfermero);
						if ($this->_1_Punto_fuera_del_area_de_erradicacion->Exportable) $Doc->ExportField($this->_1_Punto_fuera_del_area_de_erradicacion);
						if ($this->_1_Transporte_bus->Exportable) $Doc->ExportField($this->_1_Transporte_bus);
						if ($this->_1_Traslado_apoyo_zonal->Exportable) $Doc->ExportField($this->_1_Traslado_apoyo_zonal);
						if ($this->_1_Traslado_area_vivac->Exportable) $Doc->ExportField($this->_1_Traslado_area_vivac);
						if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportField($this->_2_A_la_espera_definicion_nuevo_punto_FP);
						if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_de_seguridad);
						if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_que_abastece);
						if ($this->_2_Induccion_FP->Exportable) $Doc->ExportField($this->_2_Induccion_FP);
						if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportField($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
						if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportField($this->_2_Problemas_fuerza_publica);
						if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportField($this->_2_Sin_seguridad);
						if ($this->_3_AEI_controlado->Exportable) $Doc->ExportField($this->_3_AEI_controlado);
						if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportField($this->_3_AEI_no_controlado);
						if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_parcial_de_la_comunidad);
						if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_total_de_la_comunidad);
						if ($this->_3_Combate->Exportable) $Doc->ExportField($this->_3_Combate);
						if ($this->_3_Hostigamiento->Exportable) $Doc->ExportField($this->_3_Hostigamiento);
						if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportField($this->_3_MAP_Controlada);
						if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportField($this->_3_MAP_No_controlada);
						if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportField($this->_3_Operaciones_de_seguridad);
						if ($this->_4_Epidemia->Exportable) $Doc->ExportField($this->_4_Epidemia);
						if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportField($this->_4_Novedad_climatologica);
						if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportField($this->_4_Registro_de_cultivos);
						if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportField($this->_4_Zona_con_cultivos_muy_dispersos);
						if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportField($this->_4_Zona_de_cruce_de_rios_caudalosos);
						if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportField($this->_4_Zona_sin_cultivos);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
