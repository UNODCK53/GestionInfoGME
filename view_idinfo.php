<?php

// Global variable for table object
$view_id = NULL;

//
// Table class for view_id
//
class cview_id extends cTable {
	var $llave;
	var $F_Sincron;
	var $USUARIO;
	var $Cargo_gme;
	var $NOM_PE;
	var $Otro_PE;
	var $NOM_PGE;
	var $Otro_NOM_PGE;
	var $Otro_CC_PGE;
	var $TIPO_INFORME;
	var $FECHA_REPORT;
	var $DIA;
	var $MES;
	var $Departamento;
	var $Muncipio;
	var $TEMA;
	var $Otro_Tema;
	var $OBSERVACION;
	var $FUERZA;
	var $NOM_VDA;
	var $Ha_Coca;
	var $Ha_Amapola;
	var $Ha_Marihuana;
	var $T_erradi;
	var $LATITUD_sector;
	var $GRA_LAT_Sector;
	var $MIN_LAT_Sector;
	var $SEG_LAT_Sector;
	var $GRA_LONG_Sector;
	var $MIN_LONG_Sector;
	var $SEG_LONG_Sector;
	var $Ini_Jorna;
	var $Fin_Jorna;
	var $Situ_Especial;
	var $Adm_GME;
	var $_1_Abastecimiento;
	var $_1_Acompanamiento_firma_GME;
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
	var $Adm_Fuerza;
	var $_2_A_la_espera_definicion_nuevo_punto_FP;
	var $_2_Espera_helicoptero_FP_de_seguridad;
	var $_2_Espera_helicoptero_FP_que_abastece;
	var $_2_Induccion_FP;
	var $_2_Novedad_canino_o_del_grupo_de_deteccion;
	var $_2_Problemas_fuerza_publica;
	var $_2_Sin_seguridad;
	var $Sit_Seguridad;
	var $_3_AEI_controlado;
	var $_3_AEI_no_controlado;
	var $_3_Bloqueo_parcial_de_la_comunidad;
	var $_3_Bloqueo_total_de_la_comunidad;
	var $_3_Combate;
	var $_3_Hostigamiento;
	var $_3_MAP_Controlada;
	var $_3_MAP_No_controlada;
	var $_3_MUSE;
	var $_3_Operaciones_de_seguridad;
	var $LATITUD_segurid;
	var $GRA_LAT_segurid;
	var $MIN_LAT_segurid;
	var $SEG_LAT_segurid;
	var $GRA_LONG_seguri;
	var $MIN_LONG_seguri;
	var $SEG_LONG_seguri;
	var $Novedad;
	var $_4_Epidemia;
	var $_4_Novedad_climatologica;
	var $_4_Registro_de_cultivos;
	var $_4_Zona_con_cultivos_muy_dispersos;
	var $_4_Zona_de_cruce_de_rios_caudalosos;
	var $_4_Zona_sin_cultivos;
	var $Num_Erra_Salen;
	var $Num_Erra_Quedan;
	var $No_ENFERMERO;
	var $NUM_FP;
	var $NUM_Perso_EVA;
	var $NUM_Poli;
	var $AD1O;
	var $FASE;
	var $Modificado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view_id';
		$this->TableName = 'view_id';
		$this->TableType = 'VIEW';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// llave
		$this->llave = new cField('view_id', 'view_id', 'x_llave', 'llave', '`llave`', '`llave`', 200, -1, FALSE, '`llave`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['llave'] = &$this->llave;

		// F_Sincron
		$this->F_Sincron = new cField('view_id', 'view_id', 'x_F_Sincron', 'F_Sincron', '`F_Sincron`', 'DATE_FORMAT(`F_Sincron`, \'%d/%m/%Y\')', 135, 7, FALSE, '`F_Sincron`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->F_Sincron->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['F_Sincron'] = &$this->F_Sincron;

		// USUARIO
		$this->USUARIO = new cField('view_id', 'view_id', 'x_USUARIO', 'USUARIO', '`USUARIO`', '`USUARIO`', 201, -1, FALSE, '`USUARIO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['USUARIO'] = &$this->USUARIO;

		// Cargo_gme
		$this->Cargo_gme = new cField('view_id', 'view_id', 'x_Cargo_gme', 'Cargo_gme', '`Cargo_gme`', '`Cargo_gme`', 200, -1, FALSE, '`Cargo_gme`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_gme'] = &$this->Cargo_gme;

		// NOM_PE
		$this->NOM_PE = new cField('view_id', 'view_id', 'x_NOM_PE', 'NOM_PE', '`NOM_PE`', '`NOM_PE`', 201, -1, FALSE, '`NOM_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_PE'] = &$this->NOM_PE;

		// Otro_PE
		$this->Otro_PE = new cField('view_id', 'view_id', 'x_Otro_PE', 'Otro_PE', '`Otro_PE`', '`Otro_PE`', 200, -1, FALSE, '`Otro_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_PE'] = &$this->Otro_PE;

		// NOM_PGE
		$this->NOM_PGE = new cField('view_id', 'view_id', 'x_NOM_PGE', 'NOM_PGE', '`NOM_PGE`', '`NOM_PGE`', 201, -1, FALSE, '`NOM_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_PGE'] = &$this->NOM_PGE;

		// Otro_NOM_PGE
		$this->Otro_NOM_PGE = new cField('view_id', 'view_id', 'x_Otro_NOM_PGE', 'Otro_NOM_PGE', '`Otro_NOM_PGE`', '`Otro_NOM_PGE`', 200, -1, FALSE, '`Otro_NOM_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_NOM_PGE'] = &$this->Otro_NOM_PGE;

		// Otro_CC_PGE
		$this->Otro_CC_PGE = new cField('view_id', 'view_id', 'x_Otro_CC_PGE', 'Otro_CC_PGE', '`Otro_CC_PGE`', '`Otro_CC_PGE`', 200, -1, FALSE, '`Otro_CC_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_PGE'] = &$this->Otro_CC_PGE;

		// TIPO_INFORME
		$this->TIPO_INFORME = new cField('view_id', 'view_id', 'x_TIPO_INFORME', 'TIPO_INFORME', '`TIPO_INFORME`', '`TIPO_INFORME`', 201, -1, FALSE, '`TIPO_INFORME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TIPO_INFORME'] = &$this->TIPO_INFORME;

		// FECHA_REPORT
		$this->FECHA_REPORT = new cField('view_id', 'view_id', 'x_FECHA_REPORT', 'FECHA_REPORT', '`FECHA_REPORT`', '`FECHA_REPORT`', 200, -1, FALSE, '`FECHA_REPORT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FECHA_REPORT'] = &$this->FECHA_REPORT;

		// DIA
		$this->DIA = new cField('view_id', 'view_id', 'x_DIA', 'DIA', '`DIA`', '`DIA`', 200, -1, FALSE, '`DIA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DIA'] = &$this->DIA;

		// MES
		$this->MES = new cField('view_id', 'view_id', 'x_MES', 'MES', '`MES`', '`MES`', 200, -1, FALSE, '`MES`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['MES'] = &$this->MES;

		// Departamento
		$this->Departamento = new cField('view_id', 'view_id', 'x_Departamento', 'Departamento', '`Departamento`', '`Departamento`', 200, -1, FALSE, '`Departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Departamento'] = &$this->Departamento;

		// Muncipio
		$this->Muncipio = new cField('view_id', 'view_id', 'x_Muncipio', 'Muncipio', '`Muncipio`', '`Muncipio`', 201, -1, FALSE, '`Muncipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Muncipio'] = &$this->Muncipio;

		// TEMA
		$this->TEMA = new cField('view_id', 'view_id', 'x_TEMA', 'TEMA', '`TEMA`', '`TEMA`', 201, -1, FALSE, '`TEMA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TEMA'] = &$this->TEMA;

		// Otro_Tema
		$this->Otro_Tema = new cField('view_id', 'view_id', 'x_Otro_Tema', 'Otro_Tema', '`Otro_Tema`', '`Otro_Tema`', 200, -1, FALSE, '`Otro_Tema`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Tema'] = &$this->Otro_Tema;

		// OBSERVACION
		$this->OBSERVACION = new cField('view_id', 'view_id', 'x_OBSERVACION', 'OBSERVACION', '`OBSERVACION`', '`OBSERVACION`', 201, -1, FALSE, '`OBSERVACION`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['OBSERVACION'] = &$this->OBSERVACION;

		// FUERZA
		$this->FUERZA = new cField('view_id', 'view_id', 'x_FUERZA', 'FUERZA', '`FUERZA`', '`FUERZA`', 201, -1, FALSE, '`FUERZA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FUERZA'] = &$this->FUERZA;

		// NOM_VDA
		$this->NOM_VDA = new cField('view_id', 'view_id', 'x_NOM_VDA', 'NOM_VDA', '`NOM_VDA`', '`NOM_VDA`', 200, -1, FALSE, '`NOM_VDA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_VDA'] = &$this->NOM_VDA;

		// Ha_Coca
		$this->Ha_Coca = new cField('view_id', 'view_id', 'x_Ha_Coca', 'Ha_Coca', '`Ha_Coca`', '`Ha_Coca`', 131, -1, FALSE, '`Ha_Coca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Ha_Coca->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Ha_Coca'] = &$this->Ha_Coca;

		// Ha_Amapola
		$this->Ha_Amapola = new cField('view_id', 'view_id', 'x_Ha_Amapola', 'Ha_Amapola', '`Ha_Amapola`', '`Ha_Amapola`', 131, -1, FALSE, '`Ha_Amapola`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Ha_Amapola->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Ha_Amapola'] = &$this->Ha_Amapola;

		// Ha_Marihuana
		$this->Ha_Marihuana = new cField('view_id', 'view_id', 'x_Ha_Marihuana', 'Ha_Marihuana', '`Ha_Marihuana`', '`Ha_Marihuana`', 131, -1, FALSE, '`Ha_Marihuana`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Ha_Marihuana->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Ha_Marihuana'] = &$this->Ha_Marihuana;

		// T_erradi
		$this->T_erradi = new cField('view_id', 'view_id', 'x_T_erradi', 'T_erradi', '`T_erradi`', '`T_erradi`', 131, -1, FALSE, '`T_erradi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->T_erradi->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['T_erradi'] = &$this->T_erradi;

		// LATITUD_sector
		$this->LATITUD_sector = new cField('view_id', 'view_id', 'x_LATITUD_sector', 'LATITUD_sector', '`LATITUD_sector`', '`LATITUD_sector`', 201, -1, FALSE, '`LATITUD_sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LATITUD_sector'] = &$this->LATITUD_sector;

		// GRA_LAT_Sector
		$this->GRA_LAT_Sector = new cField('view_id', 'view_id', 'x_GRA_LAT_Sector', 'GRA_LAT_Sector', '`GRA_LAT_Sector`', '`GRA_LAT_Sector`', 3, -1, FALSE, '`GRA_LAT_Sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LAT_Sector->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LAT_Sector'] = &$this->GRA_LAT_Sector;

		// MIN_LAT_Sector
		$this->MIN_LAT_Sector = new cField('view_id', 'view_id', 'x_MIN_LAT_Sector', 'MIN_LAT_Sector', '`MIN_LAT_Sector`', '`MIN_LAT_Sector`', 3, -1, FALSE, '`MIN_LAT_Sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LAT_Sector->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LAT_Sector'] = &$this->MIN_LAT_Sector;

		// SEG_LAT_Sector
		$this->SEG_LAT_Sector = new cField('view_id', 'view_id', 'x_SEG_LAT_Sector', 'SEG_LAT_Sector', '`SEG_LAT_Sector`', '`SEG_LAT_Sector`', 131, -1, FALSE, '`SEG_LAT_Sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LAT_Sector->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LAT_Sector'] = &$this->SEG_LAT_Sector;

		// GRA_LONG_Sector
		$this->GRA_LONG_Sector = new cField('view_id', 'view_id', 'x_GRA_LONG_Sector', 'GRA_LONG_Sector', '`GRA_LONG_Sector`', '`GRA_LONG_Sector`', 3, -1, FALSE, '`GRA_LONG_Sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LONG_Sector->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LONG_Sector'] = &$this->GRA_LONG_Sector;

		// MIN_LONG_Sector
		$this->MIN_LONG_Sector = new cField('view_id', 'view_id', 'x_MIN_LONG_Sector', 'MIN_LONG_Sector', '`MIN_LONG_Sector`', '`MIN_LONG_Sector`', 3, -1, FALSE, '`MIN_LONG_Sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LONG_Sector->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LONG_Sector'] = &$this->MIN_LONG_Sector;

		// SEG_LONG_Sector
		$this->SEG_LONG_Sector = new cField('view_id', 'view_id', 'x_SEG_LONG_Sector', 'SEG_LONG_Sector', '`SEG_LONG_Sector`', '`SEG_LONG_Sector`', 131, -1, FALSE, '`SEG_LONG_Sector`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LONG_Sector->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LONG_Sector'] = &$this->SEG_LONG_Sector;

		// Ini_Jorna
		$this->Ini_Jorna = new cField('view_id', 'view_id', 'x_Ini_Jorna', 'Ini_Jorna', '`Ini_Jorna`', '`Ini_Jorna`', 200, -1, FALSE, '`Ini_Jorna`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Ini_Jorna'] = &$this->Ini_Jorna;

		// Fin_Jorna
		$this->Fin_Jorna = new cField('view_id', 'view_id', 'x_Fin_Jorna', 'Fin_Jorna', '`Fin_Jorna`', '`Fin_Jorna`', 200, -1, FALSE, '`Fin_Jorna`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fin_Jorna'] = &$this->Fin_Jorna;

		// Situ_Especial
		$this->Situ_Especial = new cField('view_id', 'view_id', 'x_Situ_Especial', 'Situ_Especial', '`Situ_Especial`', '`Situ_Especial`', 200, -1, FALSE, '`Situ_Especial`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Situ_Especial'] = &$this->Situ_Especial;

		// Adm_GME
		$this->Adm_GME = new cField('view_id', 'view_id', 'x_Adm_GME', 'Adm_GME', '`Adm_GME`', '`Adm_GME`', 131, -1, FALSE, '`Adm_GME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Adm_GME->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Adm_GME'] = &$this->Adm_GME;

		// 1_Abastecimiento
		$this->_1_Abastecimiento = new cField('view_id', 'view_id', 'x__1_Abastecimiento', '1_Abastecimiento', '`1_Abastecimiento`', '`1_Abastecimiento`', 131, -1, FALSE, '`1_Abastecimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Abastecimiento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Abastecimiento'] = &$this->_1_Abastecimiento;

		// 1_Acompanamiento_firma_GME
		$this->_1_Acompanamiento_firma_GME = new cField('view_id', 'view_id', 'x__1_Acompanamiento_firma_GME', '1_Acompanamiento_firma_GME', '`1_Acompanamiento_firma_GME`', '`1_Acompanamiento_firma_GME`', 131, -1, FALSE, '`1_Acompanamiento_firma_GME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Acompanamiento_firma_GME->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Acompanamiento_firma_GME'] = &$this->_1_Acompanamiento_firma_GME;

		// 1_Apoyo_zonal_sin_punto_asignado
		$this->_1_Apoyo_zonal_sin_punto_asignado = new cField('view_id', 'view_id', 'x__1_Apoyo_zonal_sin_punto_asignado', '1_Apoyo_zonal_sin_punto_asignado', '`1_Apoyo_zonal_sin_punto_asignado`', '`1_Apoyo_zonal_sin_punto_asignado`', 131, -1, FALSE, '`1_Apoyo_zonal_sin_punto_asignado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Apoyo_zonal_sin_punto_asignado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Apoyo_zonal_sin_punto_asignado'] = &$this->_1_Apoyo_zonal_sin_punto_asignado;

		// 1_Descanso_en_dia_habil
		$this->_1_Descanso_en_dia_habil = new cField('view_id', 'view_id', 'x__1_Descanso_en_dia_habil', '1_Descanso_en_dia_habil', '`1_Descanso_en_dia_habil`', '`1_Descanso_en_dia_habil`', 131, -1, FALSE, '`1_Descanso_en_dia_habil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Descanso_en_dia_habil->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Descanso_en_dia_habil'] = &$this->_1_Descanso_en_dia_habil;

		// 1_Descanso_festivo_dominical
		$this->_1_Descanso_festivo_dominical = new cField('view_id', 'view_id', 'x__1_Descanso_festivo_dominical', '1_Descanso_festivo_dominical', '`1_Descanso_festivo_dominical`', '`1_Descanso_festivo_dominical`', 131, -1, FALSE, '`1_Descanso_festivo_dominical`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Descanso_festivo_dominical->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Descanso_festivo_dominical'] = &$this->_1_Descanso_festivo_dominical;

		// 1_Dia_compensatorio
		$this->_1_Dia_compensatorio = new cField('view_id', 'view_id', 'x__1_Dia_compensatorio', '1_Dia_compensatorio', '`1_Dia_compensatorio`', '`1_Dia_compensatorio`', 131, -1, FALSE, '`1_Dia_compensatorio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Dia_compensatorio->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Dia_compensatorio'] = &$this->_1_Dia_compensatorio;

		// 1_Erradicacion_en_dia_festivo
		$this->_1_Erradicacion_en_dia_festivo = new cField('view_id', 'view_id', 'x__1_Erradicacion_en_dia_festivo', '1_Erradicacion_en_dia_festivo', '`1_Erradicacion_en_dia_festivo`', '`1_Erradicacion_en_dia_festivo`', 131, -1, FALSE, '`1_Erradicacion_en_dia_festivo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Erradicacion_en_dia_festivo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Erradicacion_en_dia_festivo'] = &$this->_1_Erradicacion_en_dia_festivo;

		// 1_Espera_helicoptero_Helistar
		$this->_1_Espera_helicoptero_Helistar = new cField('view_id', 'view_id', 'x__1_Espera_helicoptero_Helistar', '1_Espera_helicoptero_Helistar', '`1_Espera_helicoptero_Helistar`', '`1_Espera_helicoptero_Helistar`', 131, -1, FALSE, '`1_Espera_helicoptero_Helistar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Espera_helicoptero_Helistar->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Espera_helicoptero_Helistar'] = &$this->_1_Espera_helicoptero_Helistar;

		// 1_Extraccion
		$this->_1_Extraccion = new cField('view_id', 'view_id', 'x__1_Extraccion', '1_Extraccion', '`1_Extraccion`', '`1_Extraccion`', 131, -1, FALSE, '`1_Extraccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Extraccion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Extraccion'] = &$this->_1_Extraccion;

		// 1_Firma_contrato_GME
		$this->_1_Firma_contrato_GME = new cField('view_id', 'view_id', 'x__1_Firma_contrato_GME', '1_Firma_contrato_GME', '`1_Firma_contrato_GME`', '`1_Firma_contrato_GME`', 131, -1, FALSE, '`1_Firma_contrato_GME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Firma_contrato_GME->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Firma_contrato_GME'] = &$this->_1_Firma_contrato_GME;

		// 1_Induccion_Apoyo_Zonal
		$this->_1_Induccion_Apoyo_Zonal = new cField('view_id', 'view_id', 'x__1_Induccion_Apoyo_Zonal', '1_Induccion_Apoyo_Zonal', '`1_Induccion_Apoyo_Zonal`', '`1_Induccion_Apoyo_Zonal`', 131, -1, FALSE, '`1_Induccion_Apoyo_Zonal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Induccion_Apoyo_Zonal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Induccion_Apoyo_Zonal'] = &$this->_1_Induccion_Apoyo_Zonal;

		// 1_Insercion
		$this->_1_Insercion = new cField('view_id', 'view_id', 'x__1_Insercion', '1_Insercion', '`1_Insercion`', '`1_Insercion`', 131, -1, FALSE, '`1_Insercion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Insercion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Insercion'] = &$this->_1_Insercion;

		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase = new cField('view_id', 'view_id', 'x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase', '1_Llegada_GME_a_su_lugar_de_Origen_fin_fase', '`1_Llegada_GME_a_su_lugar_de_Origen_fin_fase`', '`1_Llegada_GME_a_su_lugar_de_Origen_fin_fase`', 131, -1, FALSE, '`1_Llegada_GME_a_su_lugar_de_Origen_fin_fase`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Llegada_GME_a_su_lugar_de_Origen_fin_fase'] = &$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase;

		// 1_Novedad_apoyo_zonal
		$this->_1_Novedad_apoyo_zonal = new cField('view_id', 'view_id', 'x__1_Novedad_apoyo_zonal', '1_Novedad_apoyo_zonal', '`1_Novedad_apoyo_zonal`', '`1_Novedad_apoyo_zonal`', 131, -1, FALSE, '`1_Novedad_apoyo_zonal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Novedad_apoyo_zonal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Novedad_apoyo_zonal'] = &$this->_1_Novedad_apoyo_zonal;

		// 1_Novedad_enfermero
		$this->_1_Novedad_enfermero = new cField('view_id', 'view_id', 'x__1_Novedad_enfermero', '1_Novedad_enfermero', '`1_Novedad_enfermero`', '`1_Novedad_enfermero`', 131, -1, FALSE, '`1_Novedad_enfermero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Novedad_enfermero->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Novedad_enfermero'] = &$this->_1_Novedad_enfermero;

		// 1_Punto_fuera_del_area_de_erradicacion
		$this->_1_Punto_fuera_del_area_de_erradicacion = new cField('view_id', 'view_id', 'x__1_Punto_fuera_del_area_de_erradicacion', '1_Punto_fuera_del_area_de_erradicacion', '`1_Punto_fuera_del_area_de_erradicacion`', '`1_Punto_fuera_del_area_de_erradicacion`', 131, -1, FALSE, '`1_Punto_fuera_del_area_de_erradicacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Punto_fuera_del_area_de_erradicacion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Punto_fuera_del_area_de_erradicacion'] = &$this->_1_Punto_fuera_del_area_de_erradicacion;

		// 1_Transporte_bus
		$this->_1_Transporte_bus = new cField('view_id', 'view_id', 'x__1_Transporte_bus', '1_Transporte_bus', '`1_Transporte_bus`', '`1_Transporte_bus`', 131, -1, FALSE, '`1_Transporte_bus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Transporte_bus->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Transporte_bus'] = &$this->_1_Transporte_bus;

		// 1_Traslado_apoyo_zonal
		$this->_1_Traslado_apoyo_zonal = new cField('view_id', 'view_id', 'x__1_Traslado_apoyo_zonal', '1_Traslado_apoyo_zonal', '`1_Traslado_apoyo_zonal`', '`1_Traslado_apoyo_zonal`', 131, -1, FALSE, '`1_Traslado_apoyo_zonal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Traslado_apoyo_zonal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Traslado_apoyo_zonal'] = &$this->_1_Traslado_apoyo_zonal;

		// 1_Traslado_area_vivac
		$this->_1_Traslado_area_vivac = new cField('view_id', 'view_id', 'x__1_Traslado_area_vivac', '1_Traslado_area_vivac', '`1_Traslado_area_vivac`', '`1_Traslado_area_vivac`', 131, -1, FALSE, '`1_Traslado_area_vivac`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Traslado_area_vivac->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['1_Traslado_area_vivac'] = &$this->_1_Traslado_area_vivac;

		// Adm_Fuerza
		$this->Adm_Fuerza = new cField('view_id', 'view_id', 'x_Adm_Fuerza', 'Adm_Fuerza', '`Adm_Fuerza`', '`Adm_Fuerza`', 131, -1, FALSE, '`Adm_Fuerza`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Adm_Fuerza->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Adm_Fuerza'] = &$this->Adm_Fuerza;

		// 2_A_la_espera_definicion_nuevo_punto_FP
		$this->_2_A_la_espera_definicion_nuevo_punto_FP = new cField('view_id', 'view_id', 'x__2_A_la_espera_definicion_nuevo_punto_FP', '2_A_la_espera_definicion_nuevo_punto_FP', '`2_A_la_espera_definicion_nuevo_punto_FP`', '`2_A_la_espera_definicion_nuevo_punto_FP`', 131, -1, FALSE, '`2_A_la_espera_definicion_nuevo_punto_FP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_A_la_espera_definicion_nuevo_punto_FP'] = &$this->_2_A_la_espera_definicion_nuevo_punto_FP;

		// 2_Espera_helicoptero_FP_de_seguridad
		$this->_2_Espera_helicoptero_FP_de_seguridad = new cField('view_id', 'view_id', 'x__2_Espera_helicoptero_FP_de_seguridad', '2_Espera_helicoptero_FP_de_seguridad', '`2_Espera_helicoptero_FP_de_seguridad`', '`2_Espera_helicoptero_FP_de_seguridad`', 131, -1, FALSE, '`2_Espera_helicoptero_FP_de_seguridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Espera_helicoptero_FP_de_seguridad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Espera_helicoptero_FP_de_seguridad'] = &$this->_2_Espera_helicoptero_FP_de_seguridad;

		// 2_Espera_helicoptero_FP_que_abastece
		$this->_2_Espera_helicoptero_FP_que_abastece = new cField('view_id', 'view_id', 'x__2_Espera_helicoptero_FP_que_abastece', '2_Espera_helicoptero_FP_que_abastece', '`2_Espera_helicoptero_FP_que_abastece`', '`2_Espera_helicoptero_FP_que_abastece`', 131, -1, FALSE, '`2_Espera_helicoptero_FP_que_abastece`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Espera_helicoptero_FP_que_abastece->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Espera_helicoptero_FP_que_abastece'] = &$this->_2_Espera_helicoptero_FP_que_abastece;

		// 2_Induccion_FP
		$this->_2_Induccion_FP = new cField('view_id', 'view_id', 'x__2_Induccion_FP', '2_Induccion_FP', '`2_Induccion_FP`', '`2_Induccion_FP`', 131, -1, FALSE, '`2_Induccion_FP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Induccion_FP->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Induccion_FP'] = &$this->_2_Induccion_FP;

		// 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion = new cField('view_id', 'view_id', 'x__2_Novedad_canino_o_del_grupo_de_deteccion', '2_Novedad_canino_o_del_grupo_de_deteccion', '`2_Novedad_canino_o_del_grupo_de_deteccion`', '`2_Novedad_canino_o_del_grupo_de_deteccion`', 131, -1, FALSE, '`2_Novedad_canino_o_del_grupo_de_deteccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Novedad_canino_o_del_grupo_de_deteccion'] = &$this->_2_Novedad_canino_o_del_grupo_de_deteccion;

		// 2_Problemas_fuerza_publica
		$this->_2_Problemas_fuerza_publica = new cField('view_id', 'view_id', 'x__2_Problemas_fuerza_publica', '2_Problemas_fuerza_publica', '`2_Problemas_fuerza_publica`', '`2_Problemas_fuerza_publica`', 131, -1, FALSE, '`2_Problemas_fuerza_publica`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Problemas_fuerza_publica->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Problemas_fuerza_publica'] = &$this->_2_Problemas_fuerza_publica;

		// 2_Sin_seguridad
		$this->_2_Sin_seguridad = new cField('view_id', 'view_id', 'x__2_Sin_seguridad', '2_Sin_seguridad', '`2_Sin_seguridad`', '`2_Sin_seguridad`', 131, -1, FALSE, '`2_Sin_seguridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Sin_seguridad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['2_Sin_seguridad'] = &$this->_2_Sin_seguridad;

		// Sit_Seguridad
		$this->Sit_Seguridad = new cField('view_id', 'view_id', 'x_Sit_Seguridad', 'Sit_Seguridad', '`Sit_Seguridad`', '`Sit_Seguridad`', 131, -1, FALSE, '`Sit_Seguridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Sit_Seguridad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Sit_Seguridad'] = &$this->Sit_Seguridad;

		// 3_AEI_controlado
		$this->_3_AEI_controlado = new cField('view_id', 'view_id', 'x__3_AEI_controlado', '3_AEI_controlado', '`3_AEI_controlado`', '`3_AEI_controlado`', 131, -1, FALSE, '`3_AEI_controlado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_AEI_controlado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_AEI_controlado'] = &$this->_3_AEI_controlado;

		// 3_AEI_no_controlado
		$this->_3_AEI_no_controlado = new cField('view_id', 'view_id', 'x__3_AEI_no_controlado', '3_AEI_no_controlado', '`3_AEI_no_controlado`', '`3_AEI_no_controlado`', 131, -1, FALSE, '`3_AEI_no_controlado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_AEI_no_controlado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_AEI_no_controlado'] = &$this->_3_AEI_no_controlado;

		// 3_Bloqueo_parcial_de_la_comunidad
		$this->_3_Bloqueo_parcial_de_la_comunidad = new cField('view_id', 'view_id', 'x__3_Bloqueo_parcial_de_la_comunidad', '3_Bloqueo_parcial_de_la_comunidad', '`3_Bloqueo_parcial_de_la_comunidad`', '`3_Bloqueo_parcial_de_la_comunidad`', 131, -1, FALSE, '`3_Bloqueo_parcial_de_la_comunidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Bloqueo_parcial_de_la_comunidad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Bloqueo_parcial_de_la_comunidad'] = &$this->_3_Bloqueo_parcial_de_la_comunidad;

		// 3_Bloqueo_total_de_la_comunidad
		$this->_3_Bloqueo_total_de_la_comunidad = new cField('view_id', 'view_id', 'x__3_Bloqueo_total_de_la_comunidad', '3_Bloqueo_total_de_la_comunidad', '`3_Bloqueo_total_de_la_comunidad`', '`3_Bloqueo_total_de_la_comunidad`', 131, -1, FALSE, '`3_Bloqueo_total_de_la_comunidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Bloqueo_total_de_la_comunidad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Bloqueo_total_de_la_comunidad'] = &$this->_3_Bloqueo_total_de_la_comunidad;

		// 3_Combate
		$this->_3_Combate = new cField('view_id', 'view_id', 'x__3_Combate', '3_Combate', '`3_Combate`', '`3_Combate`', 131, -1, FALSE, '`3_Combate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Combate->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Combate'] = &$this->_3_Combate;

		// 3_Hostigamiento
		$this->_3_Hostigamiento = new cField('view_id', 'view_id', 'x__3_Hostigamiento', '3_Hostigamiento', '`3_Hostigamiento`', '`3_Hostigamiento`', 131, -1, FALSE, '`3_Hostigamiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Hostigamiento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Hostigamiento'] = &$this->_3_Hostigamiento;

		// 3_MAP_Controlada
		$this->_3_MAP_Controlada = new cField('view_id', 'view_id', 'x__3_MAP_Controlada', '3_MAP_Controlada', '`3_MAP_Controlada`', '`3_MAP_Controlada`', 131, -1, FALSE, '`3_MAP_Controlada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_MAP_Controlada->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_MAP_Controlada'] = &$this->_3_MAP_Controlada;

		// 3_MAP_No_controlada
		$this->_3_MAP_No_controlada = new cField('view_id', 'view_id', 'x__3_MAP_No_controlada', '3_MAP_No_controlada', '`3_MAP_No_controlada`', '`3_MAP_No_controlada`', 131, -1, FALSE, '`3_MAP_No_controlada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_MAP_No_controlada->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_MAP_No_controlada'] = &$this->_3_MAP_No_controlada;

		// 3_MUSE
		$this->_3_MUSE = new cField('view_id', 'view_id', 'x__3_MUSE', '3_MUSE', '`3_MUSE`', '`3_MUSE`', 131, -1, FALSE, '`3_MUSE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_MUSE->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_MUSE'] = &$this->_3_MUSE;

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad = new cField('view_id', 'view_id', 'x__3_Operaciones_de_seguridad', '3_Operaciones_de_seguridad', '`3_Operaciones_de_seguridad`', '`3_Operaciones_de_seguridad`', 131, -1, FALSE, '`3_Operaciones_de_seguridad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Operaciones_de_seguridad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['3_Operaciones_de_seguridad'] = &$this->_3_Operaciones_de_seguridad;

		// LATITUD_segurid
		$this->LATITUD_segurid = new cField('view_id', 'view_id', 'x_LATITUD_segurid', 'LATITUD_segurid', '`LATITUD_segurid`', '`LATITUD_segurid`', 201, -1, FALSE, '`LATITUD_segurid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LATITUD_segurid'] = &$this->LATITUD_segurid;

		// GRA_LAT_segurid
		$this->GRA_LAT_segurid = new cField('view_id', 'view_id', 'x_GRA_LAT_segurid', 'GRA_LAT_segurid', '`GRA_LAT_segurid`', '`GRA_LAT_segurid`', 3, -1, FALSE, '`GRA_LAT_segurid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LAT_segurid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LAT_segurid'] = &$this->GRA_LAT_segurid;

		// MIN_LAT_segurid
		$this->MIN_LAT_segurid = new cField('view_id', 'view_id', 'x_MIN_LAT_segurid', 'MIN_LAT_segurid', '`MIN_LAT_segurid`', '`MIN_LAT_segurid`', 3, -1, FALSE, '`MIN_LAT_segurid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LAT_segurid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LAT_segurid'] = &$this->MIN_LAT_segurid;

		// SEG_LAT_segurid
		$this->SEG_LAT_segurid = new cField('view_id', 'view_id', 'x_SEG_LAT_segurid', 'SEG_LAT_segurid', '`SEG_LAT_segurid`', '`SEG_LAT_segurid`', 131, -1, FALSE, '`SEG_LAT_segurid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LAT_segurid->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LAT_segurid'] = &$this->SEG_LAT_segurid;

		// GRA_LONG_seguri
		$this->GRA_LONG_seguri = new cField('view_id', 'view_id', 'x_GRA_LONG_seguri', 'GRA_LONG_seguri', '`GRA_LONG_seguri`', '`GRA_LONG_seguri`', 3, -1, FALSE, '`GRA_LONG_seguri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LONG_seguri->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LONG_seguri'] = &$this->GRA_LONG_seguri;

		// MIN_LONG_seguri
		$this->MIN_LONG_seguri = new cField('view_id', 'view_id', 'x_MIN_LONG_seguri', 'MIN_LONG_seguri', '`MIN_LONG_seguri`', '`MIN_LONG_seguri`', 3, -1, FALSE, '`MIN_LONG_seguri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LONG_seguri->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LONG_seguri'] = &$this->MIN_LONG_seguri;

		// SEG_LONG_seguri
		$this->SEG_LONG_seguri = new cField('view_id', 'view_id', 'x_SEG_LONG_seguri', 'SEG_LONG_seguri', '`SEG_LONG_seguri`', '`SEG_LONG_seguri`', 131, -1, FALSE, '`SEG_LONG_seguri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LONG_seguri->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LONG_seguri'] = &$this->SEG_LONG_seguri;

		// Novedad
		$this->Novedad = new cField('view_id', 'view_id', 'x_Novedad', 'Novedad', '`Novedad`', '`Novedad`', 131, -1, FALSE, '`Novedad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Novedad->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Novedad'] = &$this->Novedad;

		// 4_Epidemia
		$this->_4_Epidemia = new cField('view_id', 'view_id', 'x__4_Epidemia', '4_Epidemia', '`4_Epidemia`', '`4_Epidemia`', 131, -1, FALSE, '`4_Epidemia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Epidemia->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Epidemia'] = &$this->_4_Epidemia;

		// 4_Novedad_climatologica
		$this->_4_Novedad_climatologica = new cField('view_id', 'view_id', 'x__4_Novedad_climatologica', '4_Novedad_climatologica', '`4_Novedad_climatologica`', '`4_Novedad_climatologica`', 131, -1, FALSE, '`4_Novedad_climatologica`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Novedad_climatologica->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Novedad_climatologica'] = &$this->_4_Novedad_climatologica;

		// 4_Registro_de_cultivos
		$this->_4_Registro_de_cultivos = new cField('view_id', 'view_id', 'x__4_Registro_de_cultivos', '4_Registro_de_cultivos', '`4_Registro_de_cultivos`', '`4_Registro_de_cultivos`', 131, -1, FALSE, '`4_Registro_de_cultivos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Registro_de_cultivos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Registro_de_cultivos'] = &$this->_4_Registro_de_cultivos;

		// 4_Zona_con_cultivos_muy_dispersos
		$this->_4_Zona_con_cultivos_muy_dispersos = new cField('view_id', 'view_id', 'x__4_Zona_con_cultivos_muy_dispersos', '4_Zona_con_cultivos_muy_dispersos', '`4_Zona_con_cultivos_muy_dispersos`', '`4_Zona_con_cultivos_muy_dispersos`', 131, -1, FALSE, '`4_Zona_con_cultivos_muy_dispersos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Zona_con_cultivos_muy_dispersos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Zona_con_cultivos_muy_dispersos'] = &$this->_4_Zona_con_cultivos_muy_dispersos;

		// 4_Zona_de_cruce_de_rios_caudalosos
		$this->_4_Zona_de_cruce_de_rios_caudalosos = new cField('view_id', 'view_id', 'x__4_Zona_de_cruce_de_rios_caudalosos', '4_Zona_de_cruce_de_rios_caudalosos', '`4_Zona_de_cruce_de_rios_caudalosos`', '`4_Zona_de_cruce_de_rios_caudalosos`', 131, -1, FALSE, '`4_Zona_de_cruce_de_rios_caudalosos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Zona_de_cruce_de_rios_caudalosos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Zona_de_cruce_de_rios_caudalosos'] = &$this->_4_Zona_de_cruce_de_rios_caudalosos;

		// 4_Zona_sin_cultivos
		$this->_4_Zona_sin_cultivos = new cField('view_id', 'view_id', 'x__4_Zona_sin_cultivos', '4_Zona_sin_cultivos', '`4_Zona_sin_cultivos`', '`4_Zona_sin_cultivos`', 131, -1, FALSE, '`4_Zona_sin_cultivos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_4_Zona_sin_cultivos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['4_Zona_sin_cultivos'] = &$this->_4_Zona_sin_cultivos;

		// Num_Erra_Salen
		$this->Num_Erra_Salen = new cField('view_id', 'view_id', 'x_Num_Erra_Salen', 'Num_Erra_Salen', '`Num_Erra_Salen`', '`Num_Erra_Salen`', 3, -1, FALSE, '`Num_Erra_Salen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Num_Erra_Salen->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Num_Erra_Salen'] = &$this->Num_Erra_Salen;

		// Num_Erra_Quedan
		$this->Num_Erra_Quedan = new cField('view_id', 'view_id', 'x_Num_Erra_Quedan', 'Num_Erra_Quedan', '`Num_Erra_Quedan`', '`Num_Erra_Quedan`', 3, -1, FALSE, '`Num_Erra_Quedan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Num_Erra_Quedan->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Num_Erra_Quedan'] = &$this->Num_Erra_Quedan;

		// No_ENFERMERO
		$this->No_ENFERMERO = new cField('view_id', 'view_id', 'x_No_ENFERMERO', 'No_ENFERMERO', '`No_ENFERMERO`', '`No_ENFERMERO`', 200, -1, FALSE, '`No_ENFERMERO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['No_ENFERMERO'] = &$this->No_ENFERMERO;

		// NUM_FP
		$this->NUM_FP = new cField('view_id', 'view_id', 'x_NUM_FP', 'NUM_FP', '`NUM_FP`', '`NUM_FP`', 3, -1, FALSE, '`NUM_FP`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NUM_FP->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NUM_FP'] = &$this->NUM_FP;

		// NUM_Perso_EVA
		$this->NUM_Perso_EVA = new cField('view_id', 'view_id', 'x_NUM_Perso_EVA', 'NUM_Perso_EVA', '`NUM_Perso_EVA`', '`NUM_Perso_EVA`', 3, -1, FALSE, '`NUM_Perso_EVA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NUM_Perso_EVA->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NUM_Perso_EVA'] = &$this->NUM_Perso_EVA;

		// NUM_Poli
		$this->NUM_Poli = new cField('view_id', 'view_id', 'x_NUM_Poli', 'NUM_Poli', '`NUM_Poli`', '`NUM_Poli`', 3, -1, FALSE, '`NUM_Poli`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NUM_Poli->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NUM_Poli'] = &$this->NUM_Poli;

		// AÑO
		$this->AD1O = new cField('view_id', 'view_id', 'x_AD1O', 'AÑO', '`AÑO`', '`AÑO`', 200, -1, FALSE, '`AÑO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['AÑO'] = &$this->AD1O;

		// FASE
		$this->FASE = new cField('view_id', 'view_id', 'x_FASE', 'FASE', '`FASE`', '`FASE`', 200, -1, FALSE, '`FASE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FASE'] = &$this->FASE;

		// Modificado
		$this->Modificado = new cField('view_id', 'view_id', 'x_Modificado', 'Modificado', '`Modificado`', '`Modificado`', 200, -1, FALSE, '`Modificado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Modificado'] = &$this->Modificado;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`view_id`";
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
	var $UpdateTable = "`view_id`";

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
			if (array_key_exists('llave', $rs))
				ew_AddFilter($where, ew_QuotedName('llave') . '=' . ew_QuotedValue($rs['llave'], $this->llave->FldDataType));
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
		return "`llave` = '@llave@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@llave@", ew_AdjustSql($this->llave->CurrentValue), $sKeyFilter); // Replace key value
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
			return "view_idlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view_idlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("view_idview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("view_idview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "view_idadd.php?" . $this->UrlParm($parm);
		else
			return "view_idadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("view_idedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("view_idadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view_iddelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->llave->CurrentValue)) {
			$sUrl .= "llave=" . urlencode($this->llave->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
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
			$arKeys[] = @$_GET["llave"]; // llave

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
			$this->llave->CurrentValue = $key;
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
		$this->llave->setDbValue($rs->fields('llave'));
		$this->F_Sincron->setDbValue($rs->fields('F_Sincron'));
		$this->USUARIO->setDbValue($rs->fields('USUARIO'));
		$this->Cargo_gme->setDbValue($rs->fields('Cargo_gme'));
		$this->NOM_PE->setDbValue($rs->fields('NOM_PE'));
		$this->Otro_PE->setDbValue($rs->fields('Otro_PE'));
		$this->NOM_PGE->setDbValue($rs->fields('NOM_PGE'));
		$this->Otro_NOM_PGE->setDbValue($rs->fields('Otro_NOM_PGE'));
		$this->Otro_CC_PGE->setDbValue($rs->fields('Otro_CC_PGE'));
		$this->TIPO_INFORME->setDbValue($rs->fields('TIPO_INFORME'));
		$this->FECHA_REPORT->setDbValue($rs->fields('FECHA_REPORT'));
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->TEMA->setDbValue($rs->fields('TEMA'));
		$this->Otro_Tema->setDbValue($rs->fields('Otro_Tema'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->FUERZA->setDbValue($rs->fields('FUERZA'));
		$this->NOM_VDA->setDbValue($rs->fields('NOM_VDA'));
		$this->Ha_Coca->setDbValue($rs->fields('Ha_Coca'));
		$this->Ha_Amapola->setDbValue($rs->fields('Ha_Amapola'));
		$this->Ha_Marihuana->setDbValue($rs->fields('Ha_Marihuana'));
		$this->T_erradi->setDbValue($rs->fields('T_erradi'));
		$this->LATITUD_sector->setDbValue($rs->fields('LATITUD_sector'));
		$this->GRA_LAT_Sector->setDbValue($rs->fields('GRA_LAT_Sector'));
		$this->MIN_LAT_Sector->setDbValue($rs->fields('MIN_LAT_Sector'));
		$this->SEG_LAT_Sector->setDbValue($rs->fields('SEG_LAT_Sector'));
		$this->GRA_LONG_Sector->setDbValue($rs->fields('GRA_LONG_Sector'));
		$this->MIN_LONG_Sector->setDbValue($rs->fields('MIN_LONG_Sector'));
		$this->SEG_LONG_Sector->setDbValue($rs->fields('SEG_LONG_Sector'));
		$this->Ini_Jorna->setDbValue($rs->fields('Ini_Jorna'));
		$this->Fin_Jorna->setDbValue($rs->fields('Fin_Jorna'));
		$this->Situ_Especial->setDbValue($rs->fields('Situ_Especial'));
		$this->Adm_GME->setDbValue($rs->fields('Adm_GME'));
		$this->_1_Abastecimiento->setDbValue($rs->fields('1_Abastecimiento'));
		$this->_1_Acompanamiento_firma_GME->setDbValue($rs->fields('1_Acompanamiento_firma_GME'));
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
		$this->Adm_Fuerza->setDbValue($rs->fields('Adm_Fuerza'));
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->setDbValue($rs->fields('2_A_la_espera_definicion_nuevo_punto_FP'));
		$this->_2_Espera_helicoptero_FP_de_seguridad->setDbValue($rs->fields('2_Espera_helicoptero_FP_de_seguridad'));
		$this->_2_Espera_helicoptero_FP_que_abastece->setDbValue($rs->fields('2_Espera_helicoptero_FP_que_abastece'));
		$this->_2_Induccion_FP->setDbValue($rs->fields('2_Induccion_FP'));
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setDbValue($rs->fields('2_Novedad_canino_o_del_grupo_de_deteccion'));
		$this->_2_Problemas_fuerza_publica->setDbValue($rs->fields('2_Problemas_fuerza_publica'));
		$this->_2_Sin_seguridad->setDbValue($rs->fields('2_Sin_seguridad'));
		$this->Sit_Seguridad->setDbValue($rs->fields('Sit_Seguridad'));
		$this->_3_AEI_controlado->setDbValue($rs->fields('3_AEI_controlado'));
		$this->_3_AEI_no_controlado->setDbValue($rs->fields('3_AEI_no_controlado'));
		$this->_3_Bloqueo_parcial_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_parcial_de_la_comunidad'));
		$this->_3_Bloqueo_total_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_total_de_la_comunidad'));
		$this->_3_Combate->setDbValue($rs->fields('3_Combate'));
		$this->_3_Hostigamiento->setDbValue($rs->fields('3_Hostigamiento'));
		$this->_3_MAP_Controlada->setDbValue($rs->fields('3_MAP_Controlada'));
		$this->_3_MAP_No_controlada->setDbValue($rs->fields('3_MAP_No_controlada'));
		$this->_3_MUSE->setDbValue($rs->fields('3_MUSE'));
		$this->_3_Operaciones_de_seguridad->setDbValue($rs->fields('3_Operaciones_de_seguridad'));
		$this->LATITUD_segurid->setDbValue($rs->fields('LATITUD_segurid'));
		$this->GRA_LAT_segurid->setDbValue($rs->fields('GRA_LAT_segurid'));
		$this->MIN_LAT_segurid->setDbValue($rs->fields('MIN_LAT_segurid'));
		$this->SEG_LAT_segurid->setDbValue($rs->fields('SEG_LAT_segurid'));
		$this->GRA_LONG_seguri->setDbValue($rs->fields('GRA_LONG_seguri'));
		$this->MIN_LONG_seguri->setDbValue($rs->fields('MIN_LONG_seguri'));
		$this->SEG_LONG_seguri->setDbValue($rs->fields('SEG_LONG_seguri'));
		$this->Novedad->setDbValue($rs->fields('Novedad'));
		$this->_4_Epidemia->setDbValue($rs->fields('4_Epidemia'));
		$this->_4_Novedad_climatologica->setDbValue($rs->fields('4_Novedad_climatologica'));
		$this->_4_Registro_de_cultivos->setDbValue($rs->fields('4_Registro_de_cultivos'));
		$this->_4_Zona_con_cultivos_muy_dispersos->setDbValue($rs->fields('4_Zona_con_cultivos_muy_dispersos'));
		$this->_4_Zona_de_cruce_de_rios_caudalosos->setDbValue($rs->fields('4_Zona_de_cruce_de_rios_caudalosos'));
		$this->_4_Zona_sin_cultivos->setDbValue($rs->fields('4_Zona_sin_cultivos'));
		$this->Num_Erra_Salen->setDbValue($rs->fields('Num_Erra_Salen'));
		$this->Num_Erra_Quedan->setDbValue($rs->fields('Num_Erra_Quedan'));
		$this->No_ENFERMERO->setDbValue($rs->fields('No_ENFERMERO'));
		$this->NUM_FP->setDbValue($rs->fields('NUM_FP'));
		$this->NUM_Perso_EVA->setDbValue($rs->fields('NUM_Perso_EVA'));
		$this->NUM_Poli->setDbValue($rs->fields('NUM_Poli'));
		$this->AD1O->setDbValue($rs->fields('AÑO'));
		$this->FASE->setDbValue($rs->fields('FASE'));
		$this->Modificado->setDbValue($rs->fields('Modificado'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// llave
		// F_Sincron
		// USUARIO
		// Cargo_gme
		// NOM_PE
		// Otro_PE
		// NOM_PGE
		// Otro_NOM_PGE
		// Otro_CC_PGE
		// TIPO_INFORME
		// FECHA_REPORT
		// DIA
		// MES
		// Departamento
		// Muncipio
		// TEMA
		// Otro_Tema
		// OBSERVACION
		// FUERZA
		// NOM_VDA
		// Ha_Coca
		// Ha_Amapola
		// Ha_Marihuana
		// T_erradi
		// LATITUD_sector
		// GRA_LAT_Sector
		// MIN_LAT_Sector
		// SEG_LAT_Sector
		// GRA_LONG_Sector
		// MIN_LONG_Sector
		// SEG_LONG_Sector
		// Ini_Jorna
		// Fin_Jorna
		// Situ_Especial
		// Adm_GME
		// 1_Abastecimiento
		// 1_Acompanamiento_firma_GME
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
		// Adm_Fuerza
		// 2_A_la_espera_definicion_nuevo_punto_FP
		// 2_Espera_helicoptero_FP_de_seguridad
		// 2_Espera_helicoptero_FP_que_abastece
		// 2_Induccion_FP
		// 2_Novedad_canino_o_del_grupo_de_deteccion
		// 2_Problemas_fuerza_publica
		// 2_Sin_seguridad
		// Sit_Seguridad
		// 3_AEI_controlado
		// 3_AEI_no_controlado
		// 3_Bloqueo_parcial_de_la_comunidad
		// 3_Bloqueo_total_de_la_comunidad
		// 3_Combate
		// 3_Hostigamiento
		// 3_MAP_Controlada
		// 3_MAP_No_controlada
		// 3_MUSE
		// 3_Operaciones_de_seguridad
		// LATITUD_segurid
		// GRA_LAT_segurid
		// MIN_LAT_segurid
		// SEG_LAT_segurid
		// GRA_LONG_seguri
		// MIN_LONG_seguri
		// SEG_LONG_seguri
		// Novedad
		// 4_Epidemia
		// 4_Novedad_climatologica
		// 4_Registro_de_cultivos
		// 4_Zona_con_cultivos_muy_dispersos
		// 4_Zona_de_cruce_de_rios_caudalosos
		// 4_Zona_sin_cultivos
		// Num_Erra_Salen
		// Num_Erra_Quedan
		// No_ENFERMERO
		// NUM_FP
		// NUM_Perso_EVA
		// NUM_Poli
		// AÑO
		// FASE
		// Modificado
		// llave

		$this->llave->ViewValue = $this->llave->CurrentValue;
		$this->llave->ViewCustomAttributes = "";

		// F_Sincron
		$this->F_Sincron->ViewValue = $this->F_Sincron->CurrentValue;
		$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 7);
		$this->F_Sincron->ViewCustomAttributes = "";

		// USUARIO
		$this->USUARIO->ViewValue = $this->USUARIO->CurrentValue;
		$this->USUARIO->ViewCustomAttributes = "";

		// Cargo_gme
		$this->Cargo_gme->ViewValue = $this->Cargo_gme->CurrentValue;
		$this->Cargo_gme->ViewCustomAttributes = "";

		// NOM_PE
		$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
		$this->NOM_PE->ViewCustomAttributes = "";

		// Otro_PE
		$this->Otro_PE->ViewValue = $this->Otro_PE->CurrentValue;
		$this->Otro_PE->ViewCustomAttributes = "";

		// NOM_PGE
		$this->NOM_PGE->ViewValue = $this->NOM_PGE->CurrentValue;
		$this->NOM_PGE->ViewCustomAttributes = "";

		// Otro_NOM_PGE
		$this->Otro_NOM_PGE->ViewValue = $this->Otro_NOM_PGE->CurrentValue;
		$this->Otro_NOM_PGE->ViewCustomAttributes = "";

		// Otro_CC_PGE
		$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
		$this->Otro_CC_PGE->ViewCustomAttributes = "";

		// TIPO_INFORME
		$this->TIPO_INFORME->ViewValue = $this->TIPO_INFORME->CurrentValue;
		$this->TIPO_INFORME->ViewCustomAttributes = "";

		// FECHA_REPORT
		$this->FECHA_REPORT->ViewValue = $this->FECHA_REPORT->CurrentValue;
		$this->FECHA_REPORT->ViewCustomAttributes = "";

		// DIA
		$this->DIA->ViewValue = $this->DIA->CurrentValue;
		$this->DIA->ViewCustomAttributes = "";

		// MES
		$this->MES->ViewValue = $this->MES->CurrentValue;
		$this->MES->ViewCustomAttributes = "";

		// Departamento
		$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// Muncipio
		$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
		$this->Muncipio->ViewCustomAttributes = "";

		// TEMA
		$this->TEMA->ViewValue = $this->TEMA->CurrentValue;
		$this->TEMA->ViewCustomAttributes = "";

		// Otro_Tema
		$this->Otro_Tema->ViewValue = $this->Otro_Tema->CurrentValue;
		$this->Otro_Tema->ViewCustomAttributes = "";

		// OBSERVACION
		$this->OBSERVACION->ViewValue = $this->OBSERVACION->CurrentValue;
		$this->OBSERVACION->ViewCustomAttributes = "";

		// FUERZA
		$this->FUERZA->ViewValue = $this->FUERZA->CurrentValue;
		$this->FUERZA->ViewCustomAttributes = "";

		// NOM_VDA
		$this->NOM_VDA->ViewValue = $this->NOM_VDA->CurrentValue;
		$this->NOM_VDA->ViewCustomAttributes = "";

		// Ha_Coca
		$this->Ha_Coca->ViewValue = $this->Ha_Coca->CurrentValue;
		$this->Ha_Coca->ViewCustomAttributes = "";

		// Ha_Amapola
		$this->Ha_Amapola->ViewValue = $this->Ha_Amapola->CurrentValue;
		$this->Ha_Amapola->ViewCustomAttributes = "";

		// Ha_Marihuana
		$this->Ha_Marihuana->ViewValue = $this->Ha_Marihuana->CurrentValue;
		$this->Ha_Marihuana->ViewCustomAttributes = "";

		// T_erradi
		$this->T_erradi->ViewValue = $this->T_erradi->CurrentValue;
		$this->T_erradi->ViewCustomAttributes = "";

		// LATITUD_sector
		$this->LATITUD_sector->ViewValue = $this->LATITUD_sector->CurrentValue;
		$this->LATITUD_sector->ViewCustomAttributes = "";

		// GRA_LAT_Sector
		$this->GRA_LAT_Sector->ViewValue = $this->GRA_LAT_Sector->CurrentValue;
		$this->GRA_LAT_Sector->ViewCustomAttributes = "";

		// MIN_LAT_Sector
		$this->MIN_LAT_Sector->ViewValue = $this->MIN_LAT_Sector->CurrentValue;
		$this->MIN_LAT_Sector->ViewCustomAttributes = "";

		// SEG_LAT_Sector
		$this->SEG_LAT_Sector->ViewValue = $this->SEG_LAT_Sector->CurrentValue;
		$this->SEG_LAT_Sector->ViewCustomAttributes = "";

		// GRA_LONG_Sector
		$this->GRA_LONG_Sector->ViewValue = $this->GRA_LONG_Sector->CurrentValue;
		$this->GRA_LONG_Sector->ViewCustomAttributes = "";

		// MIN_LONG_Sector
		$this->MIN_LONG_Sector->ViewValue = $this->MIN_LONG_Sector->CurrentValue;
		$this->MIN_LONG_Sector->ViewCustomAttributes = "";

		// SEG_LONG_Sector
		$this->SEG_LONG_Sector->ViewValue = $this->SEG_LONG_Sector->CurrentValue;
		$this->SEG_LONG_Sector->ViewCustomAttributes = "";

		// Ini_Jorna
		$this->Ini_Jorna->ViewValue = $this->Ini_Jorna->CurrentValue;
		$this->Ini_Jorna->ViewCustomAttributes = "";

		// Fin_Jorna
		$this->Fin_Jorna->ViewValue = $this->Fin_Jorna->CurrentValue;
		$this->Fin_Jorna->ViewCustomAttributes = "";

		// Situ_Especial
		$this->Situ_Especial->ViewValue = $this->Situ_Especial->CurrentValue;
		$this->Situ_Especial->ViewCustomAttributes = "";

		// Adm_GME
		$this->Adm_GME->ViewValue = $this->Adm_GME->CurrentValue;
		$this->Adm_GME->ViewCustomAttributes = "";

		// 1_Abastecimiento
		$this->_1_Abastecimiento->ViewValue = $this->_1_Abastecimiento->CurrentValue;
		$this->_1_Abastecimiento->ViewCustomAttributes = "";

		// 1_Acompanamiento_firma_GME
		$this->_1_Acompanamiento_firma_GME->ViewValue = $this->_1_Acompanamiento_firma_GME->CurrentValue;
		$this->_1_Acompanamiento_firma_GME->ViewCustomAttributes = "";

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

		// Adm_Fuerza
		$this->Adm_Fuerza->ViewValue = $this->Adm_Fuerza->CurrentValue;
		$this->Adm_Fuerza->ViewCustomAttributes = "";

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

		// Sit_Seguridad
		$this->Sit_Seguridad->ViewValue = $this->Sit_Seguridad->CurrentValue;
		$this->Sit_Seguridad->ViewCustomAttributes = "";

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

		// 3_MUSE
		$this->_3_MUSE->ViewValue = $this->_3_MUSE->CurrentValue;
		$this->_3_MUSE->ViewCustomAttributes = "";

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad->ViewValue = $this->_3_Operaciones_de_seguridad->CurrentValue;
		$this->_3_Operaciones_de_seguridad->ViewCustomAttributes = "";

		// LATITUD_segurid
		$this->LATITUD_segurid->ViewValue = $this->LATITUD_segurid->CurrentValue;
		$this->LATITUD_segurid->ViewCustomAttributes = "";

		// GRA_LAT_segurid
		$this->GRA_LAT_segurid->ViewValue = $this->GRA_LAT_segurid->CurrentValue;
		$this->GRA_LAT_segurid->ViewCustomAttributes = "";

		// MIN_LAT_segurid
		$this->MIN_LAT_segurid->ViewValue = $this->MIN_LAT_segurid->CurrentValue;
		$this->MIN_LAT_segurid->ViewCustomAttributes = "";

		// SEG_LAT_segurid
		$this->SEG_LAT_segurid->ViewValue = $this->SEG_LAT_segurid->CurrentValue;
		$this->SEG_LAT_segurid->ViewCustomAttributes = "";

		// GRA_LONG_seguri
		$this->GRA_LONG_seguri->ViewValue = $this->GRA_LONG_seguri->CurrentValue;
		$this->GRA_LONG_seguri->ViewCustomAttributes = "";

		// MIN_LONG_seguri
		$this->MIN_LONG_seguri->ViewValue = $this->MIN_LONG_seguri->CurrentValue;
		$this->MIN_LONG_seguri->ViewCustomAttributes = "";

		// SEG_LONG_seguri
		$this->SEG_LONG_seguri->ViewValue = $this->SEG_LONG_seguri->CurrentValue;
		$this->SEG_LONG_seguri->ViewCustomAttributes = "";

		// Novedad
		$this->Novedad->ViewValue = $this->Novedad->CurrentValue;
		$this->Novedad->ViewCustomAttributes = "";

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

		// Num_Erra_Salen
		$this->Num_Erra_Salen->ViewValue = $this->Num_Erra_Salen->CurrentValue;
		$this->Num_Erra_Salen->ViewCustomAttributes = "";

		// Num_Erra_Quedan
		$this->Num_Erra_Quedan->ViewValue = $this->Num_Erra_Quedan->CurrentValue;
		$this->Num_Erra_Quedan->ViewCustomAttributes = "";

		// No_ENFERMERO
		$this->No_ENFERMERO->ViewValue = $this->No_ENFERMERO->CurrentValue;
		$this->No_ENFERMERO->ViewCustomAttributes = "";

		// NUM_FP
		$this->NUM_FP->ViewValue = $this->NUM_FP->CurrentValue;
		$this->NUM_FP->ViewCustomAttributes = "";

		// NUM_Perso_EVA
		$this->NUM_Perso_EVA->ViewValue = $this->NUM_Perso_EVA->CurrentValue;
		$this->NUM_Perso_EVA->ViewCustomAttributes = "";

		// NUM_Poli
		$this->NUM_Poli->ViewValue = $this->NUM_Poli->CurrentValue;
		$this->NUM_Poli->ViewCustomAttributes = "";

		// AÑO
		$this->AD1O->ViewValue = $this->AD1O->CurrentValue;
		$this->AD1O->ViewCustomAttributes = "";

		// FASE
		$this->FASE->ViewValue = $this->FASE->CurrentValue;
		$this->FASE->ViewCustomAttributes = "";

		// Modificado
		$this->Modificado->ViewValue = $this->Modificado->CurrentValue;
		$this->Modificado->ViewCustomAttributes = "";

		// llave
		$this->llave->LinkCustomAttributes = "";
		$this->llave->HrefValue = "";
		$this->llave->TooltipValue = "";

		// F_Sincron
		$this->F_Sincron->LinkCustomAttributes = "";
		$this->F_Sincron->HrefValue = "";
		$this->F_Sincron->TooltipValue = "";

		// USUARIO
		$this->USUARIO->LinkCustomAttributes = "";
		$this->USUARIO->HrefValue = "";
		$this->USUARIO->TooltipValue = "";

		// Cargo_gme
		$this->Cargo_gme->LinkCustomAttributes = "";
		$this->Cargo_gme->HrefValue = "";
		$this->Cargo_gme->TooltipValue = "";

		// NOM_PE
		$this->NOM_PE->LinkCustomAttributes = "";
		$this->NOM_PE->HrefValue = "";
		$this->NOM_PE->TooltipValue = "";

		// Otro_PE
		$this->Otro_PE->LinkCustomAttributes = "";
		$this->Otro_PE->HrefValue = "";
		$this->Otro_PE->TooltipValue = "";

		// NOM_PGE
		$this->NOM_PGE->LinkCustomAttributes = "";
		$this->NOM_PGE->HrefValue = "";
		$this->NOM_PGE->TooltipValue = "";

		// Otro_NOM_PGE
		$this->Otro_NOM_PGE->LinkCustomAttributes = "";
		$this->Otro_NOM_PGE->HrefValue = "";
		$this->Otro_NOM_PGE->TooltipValue = "";

		// Otro_CC_PGE
		$this->Otro_CC_PGE->LinkCustomAttributes = "";
		$this->Otro_CC_PGE->HrefValue = "";
		$this->Otro_CC_PGE->TooltipValue = "";

		// TIPO_INFORME
		$this->TIPO_INFORME->LinkCustomAttributes = "";
		$this->TIPO_INFORME->HrefValue = "";
		$this->TIPO_INFORME->TooltipValue = "";

		// FECHA_REPORT
		$this->FECHA_REPORT->LinkCustomAttributes = "";
		$this->FECHA_REPORT->HrefValue = "";
		$this->FECHA_REPORT->TooltipValue = "";

		// DIA
		$this->DIA->LinkCustomAttributes = "";
		$this->DIA->HrefValue = "";
		$this->DIA->TooltipValue = "";

		// MES
		$this->MES->LinkCustomAttributes = "";
		$this->MES->HrefValue = "";
		$this->MES->TooltipValue = "";

		// Departamento
		$this->Departamento->LinkCustomAttributes = "";
		$this->Departamento->HrefValue = "";
		$this->Departamento->TooltipValue = "";

		// Muncipio
		$this->Muncipio->LinkCustomAttributes = "";
		$this->Muncipio->HrefValue = "";
		$this->Muncipio->TooltipValue = "";

		// TEMA
		$this->TEMA->LinkCustomAttributes = "";
		$this->TEMA->HrefValue = "";
		$this->TEMA->TooltipValue = "";

		// Otro_Tema
		$this->Otro_Tema->LinkCustomAttributes = "";
		$this->Otro_Tema->HrefValue = "";
		$this->Otro_Tema->TooltipValue = "";

		// OBSERVACION
		$this->OBSERVACION->LinkCustomAttributes = "";
		$this->OBSERVACION->HrefValue = "";
		$this->OBSERVACION->TooltipValue = "";

		// FUERZA
		$this->FUERZA->LinkCustomAttributes = "";
		$this->FUERZA->HrefValue = "";
		$this->FUERZA->TooltipValue = "";

		// NOM_VDA
		$this->NOM_VDA->LinkCustomAttributes = "";
		$this->NOM_VDA->HrefValue = "";
		$this->NOM_VDA->TooltipValue = "";

		// Ha_Coca
		$this->Ha_Coca->LinkCustomAttributes = "";
		$this->Ha_Coca->HrefValue = "";
		$this->Ha_Coca->TooltipValue = "";

		// Ha_Amapola
		$this->Ha_Amapola->LinkCustomAttributes = "";
		$this->Ha_Amapola->HrefValue = "";
		$this->Ha_Amapola->TooltipValue = "";

		// Ha_Marihuana
		$this->Ha_Marihuana->LinkCustomAttributes = "";
		$this->Ha_Marihuana->HrefValue = "";
		$this->Ha_Marihuana->TooltipValue = "";

		// T_erradi
		$this->T_erradi->LinkCustomAttributes = "";
		$this->T_erradi->HrefValue = "";
		$this->T_erradi->TooltipValue = "";

		// LATITUD_sector
		$this->LATITUD_sector->LinkCustomAttributes = "";
		$this->LATITUD_sector->HrefValue = "";
		$this->LATITUD_sector->TooltipValue = "";

		// GRA_LAT_Sector
		$this->GRA_LAT_Sector->LinkCustomAttributes = "";
		$this->GRA_LAT_Sector->HrefValue = "";
		$this->GRA_LAT_Sector->TooltipValue = "";

		// MIN_LAT_Sector
		$this->MIN_LAT_Sector->LinkCustomAttributes = "";
		$this->MIN_LAT_Sector->HrefValue = "";
		$this->MIN_LAT_Sector->TooltipValue = "";

		// SEG_LAT_Sector
		$this->SEG_LAT_Sector->LinkCustomAttributes = "";
		$this->SEG_LAT_Sector->HrefValue = "";
		$this->SEG_LAT_Sector->TooltipValue = "";

		// GRA_LONG_Sector
		$this->GRA_LONG_Sector->LinkCustomAttributes = "";
		$this->GRA_LONG_Sector->HrefValue = "";
		$this->GRA_LONG_Sector->TooltipValue = "";

		// MIN_LONG_Sector
		$this->MIN_LONG_Sector->LinkCustomAttributes = "";
		$this->MIN_LONG_Sector->HrefValue = "";
		$this->MIN_LONG_Sector->TooltipValue = "";

		// SEG_LONG_Sector
		$this->SEG_LONG_Sector->LinkCustomAttributes = "";
		$this->SEG_LONG_Sector->HrefValue = "";
		$this->SEG_LONG_Sector->TooltipValue = "";

		// Ini_Jorna
		$this->Ini_Jorna->LinkCustomAttributes = "";
		$this->Ini_Jorna->HrefValue = "";
		$this->Ini_Jorna->TooltipValue = "";

		// Fin_Jorna
		$this->Fin_Jorna->LinkCustomAttributes = "";
		$this->Fin_Jorna->HrefValue = "";
		$this->Fin_Jorna->TooltipValue = "";

		// Situ_Especial
		$this->Situ_Especial->LinkCustomAttributes = "";
		$this->Situ_Especial->HrefValue = "";
		$this->Situ_Especial->TooltipValue = "";

		// Adm_GME
		$this->Adm_GME->LinkCustomAttributes = "";
		$this->Adm_GME->HrefValue = "";
		$this->Adm_GME->TooltipValue = "";

		// 1_Abastecimiento
		$this->_1_Abastecimiento->LinkCustomAttributes = "";
		$this->_1_Abastecimiento->HrefValue = "";
		$this->_1_Abastecimiento->TooltipValue = "";

		// 1_Acompanamiento_firma_GME
		$this->_1_Acompanamiento_firma_GME->LinkCustomAttributes = "";
		$this->_1_Acompanamiento_firma_GME->HrefValue = "";
		$this->_1_Acompanamiento_firma_GME->TooltipValue = "";

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

		// Adm_Fuerza
		$this->Adm_Fuerza->LinkCustomAttributes = "";
		$this->Adm_Fuerza->HrefValue = "";
		$this->Adm_Fuerza->TooltipValue = "";

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

		// Sit_Seguridad
		$this->Sit_Seguridad->LinkCustomAttributes = "";
		$this->Sit_Seguridad->HrefValue = "";
		$this->Sit_Seguridad->TooltipValue = "";

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

		// 3_MUSE
		$this->_3_MUSE->LinkCustomAttributes = "";
		$this->_3_MUSE->HrefValue = "";
		$this->_3_MUSE->TooltipValue = "";

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad->LinkCustomAttributes = "";
		$this->_3_Operaciones_de_seguridad->HrefValue = "";
		$this->_3_Operaciones_de_seguridad->TooltipValue = "";

		// LATITUD_segurid
		$this->LATITUD_segurid->LinkCustomAttributes = "";
		$this->LATITUD_segurid->HrefValue = "";
		$this->LATITUD_segurid->TooltipValue = "";

		// GRA_LAT_segurid
		$this->GRA_LAT_segurid->LinkCustomAttributes = "";
		$this->GRA_LAT_segurid->HrefValue = "";
		$this->GRA_LAT_segurid->TooltipValue = "";

		// MIN_LAT_segurid
		$this->MIN_LAT_segurid->LinkCustomAttributes = "";
		$this->MIN_LAT_segurid->HrefValue = "";
		$this->MIN_LAT_segurid->TooltipValue = "";

		// SEG_LAT_segurid
		$this->SEG_LAT_segurid->LinkCustomAttributes = "";
		$this->SEG_LAT_segurid->HrefValue = "";
		$this->SEG_LAT_segurid->TooltipValue = "";

		// GRA_LONG_seguri
		$this->GRA_LONG_seguri->LinkCustomAttributes = "";
		$this->GRA_LONG_seguri->HrefValue = "";
		$this->GRA_LONG_seguri->TooltipValue = "";

		// MIN_LONG_seguri
		$this->MIN_LONG_seguri->LinkCustomAttributes = "";
		$this->MIN_LONG_seguri->HrefValue = "";
		$this->MIN_LONG_seguri->TooltipValue = "";

		// SEG_LONG_seguri
		$this->SEG_LONG_seguri->LinkCustomAttributes = "";
		$this->SEG_LONG_seguri->HrefValue = "";
		$this->SEG_LONG_seguri->TooltipValue = "";

		// Novedad
		$this->Novedad->LinkCustomAttributes = "";
		$this->Novedad->HrefValue = "";
		$this->Novedad->TooltipValue = "";

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

		// Num_Erra_Salen
		$this->Num_Erra_Salen->LinkCustomAttributes = "";
		$this->Num_Erra_Salen->HrefValue = "";
		$this->Num_Erra_Salen->TooltipValue = "";

		// Num_Erra_Quedan
		$this->Num_Erra_Quedan->LinkCustomAttributes = "";
		$this->Num_Erra_Quedan->HrefValue = "";
		$this->Num_Erra_Quedan->TooltipValue = "";

		// No_ENFERMERO
		$this->No_ENFERMERO->LinkCustomAttributes = "";
		$this->No_ENFERMERO->HrefValue = "";
		$this->No_ENFERMERO->TooltipValue = "";

		// NUM_FP
		$this->NUM_FP->LinkCustomAttributes = "";
		$this->NUM_FP->HrefValue = "";
		$this->NUM_FP->TooltipValue = "";

		// NUM_Perso_EVA
		$this->NUM_Perso_EVA->LinkCustomAttributes = "";
		$this->NUM_Perso_EVA->HrefValue = "";
		$this->NUM_Perso_EVA->TooltipValue = "";

		// NUM_Poli
		$this->NUM_Poli->LinkCustomAttributes = "";
		$this->NUM_Poli->HrefValue = "";
		$this->NUM_Poli->TooltipValue = "";

		// AÑO
		$this->AD1O->LinkCustomAttributes = "";
		$this->AD1O->HrefValue = "";
		$this->AD1O->TooltipValue = "";

		// FASE
		$this->FASE->LinkCustomAttributes = "";
		$this->FASE->HrefValue = "";
		$this->FASE->TooltipValue = "";

		// Modificado
		$this->Modificado->LinkCustomAttributes = "";
		$this->Modificado->HrefValue = "";
		$this->Modificado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// llave
		$this->llave->EditAttrs["class"] = "form-control";
		$this->llave->EditCustomAttributes = "";
		$this->llave->EditValue = $this->llave->CurrentValue;
		$this->llave->ViewCustomAttributes = "";

		// F_Sincron
		$this->F_Sincron->EditAttrs["class"] = "form-control";
		$this->F_Sincron->EditCustomAttributes = "";
		$this->F_Sincron->EditValue = $this->F_Sincron->CurrentValue;
		$this->F_Sincron->EditValue = ew_FormatDateTime($this->F_Sincron->EditValue, 7);
		$this->F_Sincron->ViewCustomAttributes = "";

		// USUARIO
		$this->USUARIO->EditAttrs["class"] = "form-control";
		$this->USUARIO->EditCustomAttributes = "";
		$this->USUARIO->EditValue = $this->USUARIO->CurrentValue;
		$this->USUARIO->ViewCustomAttributes = "";

		// Cargo_gme
		$this->Cargo_gme->EditAttrs["class"] = "form-control";
		$this->Cargo_gme->EditCustomAttributes = "";
		$this->Cargo_gme->EditValue = $this->Cargo_gme->CurrentValue;
		$this->Cargo_gme->ViewCustomAttributes = "";

		// NOM_PE
		$this->NOM_PE->EditAttrs["class"] = "form-control";
		$this->NOM_PE->EditCustomAttributes = "";
		$this->NOM_PE->EditValue = $this->NOM_PE->CurrentValue;
		$this->NOM_PE->ViewCustomAttributes = "";

		// Otro_PE
		$this->Otro_PE->EditAttrs["class"] = "form-control";
		$this->Otro_PE->EditCustomAttributes = "";
		$this->Otro_PE->EditValue = $this->Otro_PE->CurrentValue;
		$this->Otro_PE->ViewCustomAttributes = "";

		// NOM_PGE
		$this->NOM_PGE->EditAttrs["class"] = "form-control";
		$this->NOM_PGE->EditCustomAttributes = "";
		$this->NOM_PGE->EditValue = $this->NOM_PGE->CurrentValue;
		$this->NOM_PGE->ViewCustomAttributes = "";

		// Otro_NOM_PGE
		$this->Otro_NOM_PGE->EditAttrs["class"] = "form-control";
		$this->Otro_NOM_PGE->EditCustomAttributes = "";
		$this->Otro_NOM_PGE->EditValue = $this->Otro_NOM_PGE->CurrentValue;
		$this->Otro_NOM_PGE->ViewCustomAttributes = "";

		// Otro_CC_PGE
		$this->Otro_CC_PGE->EditAttrs["class"] = "form-control";
		$this->Otro_CC_PGE->EditCustomAttributes = "";
		$this->Otro_CC_PGE->EditValue = $this->Otro_CC_PGE->CurrentValue;
		$this->Otro_CC_PGE->ViewCustomAttributes = "";

		// TIPO_INFORME
		$this->TIPO_INFORME->EditAttrs["class"] = "form-control";
		$this->TIPO_INFORME->EditCustomAttributes = "";
		$this->TIPO_INFORME->EditValue = $this->TIPO_INFORME->CurrentValue;
		$this->TIPO_INFORME->ViewCustomAttributes = "";

		// FECHA_REPORT
		$this->FECHA_REPORT->EditAttrs["class"] = "form-control";
		$this->FECHA_REPORT->EditCustomAttributes = "";
		$this->FECHA_REPORT->EditValue = $this->FECHA_REPORT->CurrentValue;
		$this->FECHA_REPORT->ViewCustomAttributes = "";

		// DIA
		$this->DIA->EditAttrs["class"] = "form-control";
		$this->DIA->EditCustomAttributes = "";
		$this->DIA->EditValue = $this->DIA->CurrentValue;
		$this->DIA->ViewCustomAttributes = "";

		// MES
		$this->MES->EditAttrs["class"] = "form-control";
		$this->MES->EditCustomAttributes = "";
		$this->MES->EditValue = $this->MES->CurrentValue;
		$this->MES->ViewCustomAttributes = "";

		// Departamento
		$this->Departamento->EditAttrs["class"] = "form-control";
		$this->Departamento->EditCustomAttributes = "";
		$this->Departamento->EditValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// Muncipio
		$this->Muncipio->EditAttrs["class"] = "form-control";
		$this->Muncipio->EditCustomAttributes = "";
		$this->Muncipio->EditValue = $this->Muncipio->CurrentValue;
		$this->Muncipio->ViewCustomAttributes = "";

		// TEMA
		$this->TEMA->EditAttrs["class"] = "form-control";
		$this->TEMA->EditCustomAttributes = "";
		$this->TEMA->EditValue = $this->TEMA->CurrentValue;
		$this->TEMA->ViewCustomAttributes = "";

		// Otro_Tema
		$this->Otro_Tema->EditAttrs["class"] = "form-control";
		$this->Otro_Tema->EditCustomAttributes = "";
		$this->Otro_Tema->EditValue = $this->Otro_Tema->CurrentValue;
		$this->Otro_Tema->ViewCustomAttributes = "";

		// OBSERVACION
		$this->OBSERVACION->EditAttrs["class"] = "form-control";
		$this->OBSERVACION->EditCustomAttributes = "";
		$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->CurrentValue);
		$this->OBSERVACION->PlaceHolder = ew_RemoveHtml($this->OBSERVACION->FldCaption());

		// FUERZA
		$this->FUERZA->EditAttrs["class"] = "form-control";
		$this->FUERZA->EditCustomAttributes = "";
		$this->FUERZA->EditValue = ew_HtmlEncode($this->FUERZA->CurrentValue);
		$this->FUERZA->PlaceHolder = ew_RemoveHtml($this->FUERZA->FldCaption());

		// NOM_VDA
		$this->NOM_VDA->EditAttrs["class"] = "form-control";
		$this->NOM_VDA->EditCustomAttributes = "";
		$this->NOM_VDA->EditValue = $this->NOM_VDA->CurrentValue;
		$this->NOM_VDA->ViewCustomAttributes = "";

		// Ha_Coca
		$this->Ha_Coca->EditAttrs["class"] = "form-control";
		$this->Ha_Coca->EditCustomAttributes = "";
		$this->Ha_Coca->EditValue = $this->Ha_Coca->CurrentValue;
		$this->Ha_Coca->ViewCustomAttributes = "";

		// Ha_Amapola
		$this->Ha_Amapola->EditAttrs["class"] = "form-control";
		$this->Ha_Amapola->EditCustomAttributes = "";
		$this->Ha_Amapola->EditValue = $this->Ha_Amapola->CurrentValue;
		$this->Ha_Amapola->ViewCustomAttributes = "";

		// Ha_Marihuana
		$this->Ha_Marihuana->EditAttrs["class"] = "form-control";
		$this->Ha_Marihuana->EditCustomAttributes = "";
		$this->Ha_Marihuana->EditValue = $this->Ha_Marihuana->CurrentValue;
		$this->Ha_Marihuana->ViewCustomAttributes = "";

		// T_erradi
		$this->T_erradi->EditAttrs["class"] = "form-control";
		$this->T_erradi->EditCustomAttributes = "";
		$this->T_erradi->EditValue = $this->T_erradi->CurrentValue;
		$this->T_erradi->ViewCustomAttributes = "";

		// LATITUD_sector
		$this->LATITUD_sector->EditAttrs["class"] = "form-control";
		$this->LATITUD_sector->EditCustomAttributes = "";
		$this->LATITUD_sector->EditValue = $this->LATITUD_sector->CurrentValue;
		$this->LATITUD_sector->ViewCustomAttributes = "";

		// GRA_LAT_Sector
		$this->GRA_LAT_Sector->EditAttrs["class"] = "form-control";
		$this->GRA_LAT_Sector->EditCustomAttributes = "";
		$this->GRA_LAT_Sector->EditValue = $this->GRA_LAT_Sector->CurrentValue;
		$this->GRA_LAT_Sector->ViewCustomAttributes = "";

		// MIN_LAT_Sector
		$this->MIN_LAT_Sector->EditAttrs["class"] = "form-control";
		$this->MIN_LAT_Sector->EditCustomAttributes = "";
		$this->MIN_LAT_Sector->EditValue = $this->MIN_LAT_Sector->CurrentValue;
		$this->MIN_LAT_Sector->ViewCustomAttributes = "";

		// SEG_LAT_Sector
		$this->SEG_LAT_Sector->EditAttrs["class"] = "form-control";
		$this->SEG_LAT_Sector->EditCustomAttributes = "";
		$this->SEG_LAT_Sector->EditValue = $this->SEG_LAT_Sector->CurrentValue;
		$this->SEG_LAT_Sector->ViewCustomAttributes = "";

		// GRA_LONG_Sector
		$this->GRA_LONG_Sector->EditAttrs["class"] = "form-control";
		$this->GRA_LONG_Sector->EditCustomAttributes = "";
		$this->GRA_LONG_Sector->EditValue = $this->GRA_LONG_Sector->CurrentValue;
		$this->GRA_LONG_Sector->ViewCustomAttributes = "";

		// MIN_LONG_Sector
		$this->MIN_LONG_Sector->EditAttrs["class"] = "form-control";
		$this->MIN_LONG_Sector->EditCustomAttributes = "";
		$this->MIN_LONG_Sector->EditValue = $this->MIN_LONG_Sector->CurrentValue;
		$this->MIN_LONG_Sector->ViewCustomAttributes = "";

		// SEG_LONG_Sector
		$this->SEG_LONG_Sector->EditAttrs["class"] = "form-control";
		$this->SEG_LONG_Sector->EditCustomAttributes = "";
		$this->SEG_LONG_Sector->EditValue = $this->SEG_LONG_Sector->CurrentValue;
		$this->SEG_LONG_Sector->ViewCustomAttributes = "";

		// Ini_Jorna
		$this->Ini_Jorna->EditAttrs["class"] = "form-control";
		$this->Ini_Jorna->EditCustomAttributes = "";
		$this->Ini_Jorna->EditValue = $this->Ini_Jorna->CurrentValue;
		$this->Ini_Jorna->ViewCustomAttributes = "";

		// Fin_Jorna
		$this->Fin_Jorna->EditAttrs["class"] = "form-control";
		$this->Fin_Jorna->EditCustomAttributes = "";
		$this->Fin_Jorna->EditValue = $this->Fin_Jorna->CurrentValue;
		$this->Fin_Jorna->ViewCustomAttributes = "";

		// Situ_Especial
		$this->Situ_Especial->EditAttrs["class"] = "form-control";
		$this->Situ_Especial->EditCustomAttributes = "";
		$this->Situ_Especial->EditValue = $this->Situ_Especial->CurrentValue;
		$this->Situ_Especial->ViewCustomAttributes = "";

		// Adm_GME
		$this->Adm_GME->EditAttrs["class"] = "form-control";
		$this->Adm_GME->EditCustomAttributes = "";
		$this->Adm_GME->EditValue = $this->Adm_GME->CurrentValue;
		$this->Adm_GME->ViewCustomAttributes = "";

		// 1_Abastecimiento
		$this->_1_Abastecimiento->EditAttrs["class"] = "form-control";
		$this->_1_Abastecimiento->EditCustomAttributes = "";
		$this->_1_Abastecimiento->EditValue = $this->_1_Abastecimiento->CurrentValue;
		$this->_1_Abastecimiento->ViewCustomAttributes = "";

		// 1_Acompanamiento_firma_GME
		$this->_1_Acompanamiento_firma_GME->EditAttrs["class"] = "form-control";
		$this->_1_Acompanamiento_firma_GME->EditCustomAttributes = "";
		$this->_1_Acompanamiento_firma_GME->EditValue = $this->_1_Acompanamiento_firma_GME->CurrentValue;
		$this->_1_Acompanamiento_firma_GME->ViewCustomAttributes = "";

		// 1_Apoyo_zonal_sin_punto_asignado
		$this->_1_Apoyo_zonal_sin_punto_asignado->EditAttrs["class"] = "form-control";
		$this->_1_Apoyo_zonal_sin_punto_asignado->EditCustomAttributes = "";
		$this->_1_Apoyo_zonal_sin_punto_asignado->EditValue = $this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue;
		$this->_1_Apoyo_zonal_sin_punto_asignado->ViewCustomAttributes = "";

		// 1_Descanso_en_dia_habil
		$this->_1_Descanso_en_dia_habil->EditAttrs["class"] = "form-control";
		$this->_1_Descanso_en_dia_habil->EditCustomAttributes = "";
		$this->_1_Descanso_en_dia_habil->EditValue = $this->_1_Descanso_en_dia_habil->CurrentValue;
		$this->_1_Descanso_en_dia_habil->ViewCustomAttributes = "";

		// 1_Descanso_festivo_dominical
		$this->_1_Descanso_festivo_dominical->EditAttrs["class"] = "form-control";
		$this->_1_Descanso_festivo_dominical->EditCustomAttributes = "";
		$this->_1_Descanso_festivo_dominical->EditValue = $this->_1_Descanso_festivo_dominical->CurrentValue;
		$this->_1_Descanso_festivo_dominical->ViewCustomAttributes = "";

		// 1_Dia_compensatorio
		$this->_1_Dia_compensatorio->EditAttrs["class"] = "form-control";
		$this->_1_Dia_compensatorio->EditCustomAttributes = "";
		$this->_1_Dia_compensatorio->EditValue = $this->_1_Dia_compensatorio->CurrentValue;
		$this->_1_Dia_compensatorio->ViewCustomAttributes = "";

		// 1_Erradicacion_en_dia_festivo
		$this->_1_Erradicacion_en_dia_festivo->EditAttrs["class"] = "form-control";
		$this->_1_Erradicacion_en_dia_festivo->EditCustomAttributes = "";
		$this->_1_Erradicacion_en_dia_festivo->EditValue = $this->_1_Erradicacion_en_dia_festivo->CurrentValue;
		$this->_1_Erradicacion_en_dia_festivo->ViewCustomAttributes = "";

		// 1_Espera_helicoptero_Helistar
		$this->_1_Espera_helicoptero_Helistar->EditAttrs["class"] = "form-control";
		$this->_1_Espera_helicoptero_Helistar->EditCustomAttributes = "";
		$this->_1_Espera_helicoptero_Helistar->EditValue = $this->_1_Espera_helicoptero_Helistar->CurrentValue;
		$this->_1_Espera_helicoptero_Helistar->ViewCustomAttributes = "";

		// 1_Extraccion
		$this->_1_Extraccion->EditAttrs["class"] = "form-control";
		$this->_1_Extraccion->EditCustomAttributes = "";
		$this->_1_Extraccion->EditValue = $this->_1_Extraccion->CurrentValue;
		$this->_1_Extraccion->ViewCustomAttributes = "";

		// 1_Firma_contrato_GME
		$this->_1_Firma_contrato_GME->EditAttrs["class"] = "form-control";
		$this->_1_Firma_contrato_GME->EditCustomAttributes = "";
		$this->_1_Firma_contrato_GME->EditValue = $this->_1_Firma_contrato_GME->CurrentValue;
		$this->_1_Firma_contrato_GME->ViewCustomAttributes = "";

		// 1_Induccion_Apoyo_Zonal
		$this->_1_Induccion_Apoyo_Zonal->EditAttrs["class"] = "form-control";
		$this->_1_Induccion_Apoyo_Zonal->EditCustomAttributes = "";
		$this->_1_Induccion_Apoyo_Zonal->EditValue = $this->_1_Induccion_Apoyo_Zonal->CurrentValue;
		$this->_1_Induccion_Apoyo_Zonal->ViewCustomAttributes = "";

		// 1_Insercion
		$this->_1_Insercion->EditAttrs["class"] = "form-control";
		$this->_1_Insercion->EditCustomAttributes = "";
		$this->_1_Insercion->EditValue = $this->_1_Insercion->CurrentValue;
		$this->_1_Insercion->ViewCustomAttributes = "";

		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditAttrs["class"] = "form-control";
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditCustomAttributes = "";
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue = $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue;
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewCustomAttributes = "";

		// 1_Novedad_apoyo_zonal
		$this->_1_Novedad_apoyo_zonal->EditAttrs["class"] = "form-control";
		$this->_1_Novedad_apoyo_zonal->EditCustomAttributes = "";
		$this->_1_Novedad_apoyo_zonal->EditValue = $this->_1_Novedad_apoyo_zonal->CurrentValue;
		$this->_1_Novedad_apoyo_zonal->ViewCustomAttributes = "";

		// 1_Novedad_enfermero
		$this->_1_Novedad_enfermero->EditAttrs["class"] = "form-control";
		$this->_1_Novedad_enfermero->EditCustomAttributes = "";
		$this->_1_Novedad_enfermero->EditValue = $this->_1_Novedad_enfermero->CurrentValue;
		$this->_1_Novedad_enfermero->ViewCustomAttributes = "";

		// 1_Punto_fuera_del_area_de_erradicacion
		$this->_1_Punto_fuera_del_area_de_erradicacion->EditAttrs["class"] = "form-control";
		$this->_1_Punto_fuera_del_area_de_erradicacion->EditCustomAttributes = "";
		$this->_1_Punto_fuera_del_area_de_erradicacion->EditValue = $this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue;
		$this->_1_Punto_fuera_del_area_de_erradicacion->ViewCustomAttributes = "";

		// 1_Transporte_bus
		$this->_1_Transporte_bus->EditAttrs["class"] = "form-control";
		$this->_1_Transporte_bus->EditCustomAttributes = "";
		$this->_1_Transporte_bus->EditValue = $this->_1_Transporte_bus->CurrentValue;
		$this->_1_Transporte_bus->ViewCustomAttributes = "";

		// 1_Traslado_apoyo_zonal
		$this->_1_Traslado_apoyo_zonal->EditAttrs["class"] = "form-control";
		$this->_1_Traslado_apoyo_zonal->EditCustomAttributes = "";
		$this->_1_Traslado_apoyo_zonal->EditValue = $this->_1_Traslado_apoyo_zonal->CurrentValue;
		$this->_1_Traslado_apoyo_zonal->ViewCustomAttributes = "";

		// 1_Traslado_area_vivac
		$this->_1_Traslado_area_vivac->EditAttrs["class"] = "form-control";
		$this->_1_Traslado_area_vivac->EditCustomAttributes = "";
		$this->_1_Traslado_area_vivac->EditValue = $this->_1_Traslado_area_vivac->CurrentValue;
		$this->_1_Traslado_area_vivac->ViewCustomAttributes = "";

		// Adm_Fuerza
		$this->Adm_Fuerza->EditAttrs["class"] = "form-control";
		$this->Adm_Fuerza->EditCustomAttributes = "";
		$this->Adm_Fuerza->EditValue = $this->Adm_Fuerza->CurrentValue;
		$this->Adm_Fuerza->ViewCustomAttributes = "";

		// 2_A_la_espera_definicion_nuevo_punto_FP
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditAttrs["class"] = "form-control";
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditCustomAttributes = "";
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue = $this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue;
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->ViewCustomAttributes = "";

		// 2_Espera_helicoptero_FP_de_seguridad
		$this->_2_Espera_helicoptero_FP_de_seguridad->EditAttrs["class"] = "form-control";
		$this->_2_Espera_helicoptero_FP_de_seguridad->EditCustomAttributes = "";
		$this->_2_Espera_helicoptero_FP_de_seguridad->EditValue = $this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue;
		$this->_2_Espera_helicoptero_FP_de_seguridad->ViewCustomAttributes = "";

		// 2_Espera_helicoptero_FP_que_abastece
		$this->_2_Espera_helicoptero_FP_que_abastece->EditAttrs["class"] = "form-control";
		$this->_2_Espera_helicoptero_FP_que_abastece->EditCustomAttributes = "";
		$this->_2_Espera_helicoptero_FP_que_abastece->EditValue = $this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue;
		$this->_2_Espera_helicoptero_FP_que_abastece->ViewCustomAttributes = "";

		// 2_Induccion_FP
		$this->_2_Induccion_FP->EditAttrs["class"] = "form-control";
		$this->_2_Induccion_FP->EditCustomAttributes = "";
		$this->_2_Induccion_FP->EditValue = $this->_2_Induccion_FP->CurrentValue;
		$this->_2_Induccion_FP->ViewCustomAttributes = "";

		// 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditAttrs["class"] = "form-control";
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditCustomAttributes = "";
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue = $this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue;
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewCustomAttributes = "";

		// 2_Problemas_fuerza_publica
		$this->_2_Problemas_fuerza_publica->EditAttrs["class"] = "form-control";
		$this->_2_Problemas_fuerza_publica->EditCustomAttributes = "";
		$this->_2_Problemas_fuerza_publica->EditValue = $this->_2_Problemas_fuerza_publica->CurrentValue;
		$this->_2_Problemas_fuerza_publica->ViewCustomAttributes = "";

		// 2_Sin_seguridad
		$this->_2_Sin_seguridad->EditAttrs["class"] = "form-control";
		$this->_2_Sin_seguridad->EditCustomAttributes = "";
		$this->_2_Sin_seguridad->EditValue = $this->_2_Sin_seguridad->CurrentValue;
		$this->_2_Sin_seguridad->ViewCustomAttributes = "";

		// Sit_Seguridad
		$this->Sit_Seguridad->EditAttrs["class"] = "form-control";
		$this->Sit_Seguridad->EditCustomAttributes = "";
		$this->Sit_Seguridad->EditValue = $this->Sit_Seguridad->CurrentValue;
		$this->Sit_Seguridad->ViewCustomAttributes = "";

		// 3_AEI_controlado
		$this->_3_AEI_controlado->EditAttrs["class"] = "form-control";
		$this->_3_AEI_controlado->EditCustomAttributes = "";
		$this->_3_AEI_controlado->EditValue = $this->_3_AEI_controlado->CurrentValue;
		$this->_3_AEI_controlado->ViewCustomAttributes = "";

		// 3_AEI_no_controlado
		$this->_3_AEI_no_controlado->EditAttrs["class"] = "form-control";
		$this->_3_AEI_no_controlado->EditCustomAttributes = "";
		$this->_3_AEI_no_controlado->EditValue = $this->_3_AEI_no_controlado->CurrentValue;
		$this->_3_AEI_no_controlado->ViewCustomAttributes = "";

		// 3_Bloqueo_parcial_de_la_comunidad
		$this->_3_Bloqueo_parcial_de_la_comunidad->EditAttrs["class"] = "form-control";
		$this->_3_Bloqueo_parcial_de_la_comunidad->EditCustomAttributes = "";
		$this->_3_Bloqueo_parcial_de_la_comunidad->EditValue = $this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue;
		$this->_3_Bloqueo_parcial_de_la_comunidad->ViewCustomAttributes = "";

		// 3_Bloqueo_total_de_la_comunidad
		$this->_3_Bloqueo_total_de_la_comunidad->EditAttrs["class"] = "form-control";
		$this->_3_Bloqueo_total_de_la_comunidad->EditCustomAttributes = "";
		$this->_3_Bloqueo_total_de_la_comunidad->EditValue = $this->_3_Bloqueo_total_de_la_comunidad->CurrentValue;
		$this->_3_Bloqueo_total_de_la_comunidad->ViewCustomAttributes = "";

		// 3_Combate
		$this->_3_Combate->EditAttrs["class"] = "form-control";
		$this->_3_Combate->EditCustomAttributes = "";
		$this->_3_Combate->EditValue = $this->_3_Combate->CurrentValue;
		$this->_3_Combate->ViewCustomAttributes = "";

		// 3_Hostigamiento
		$this->_3_Hostigamiento->EditAttrs["class"] = "form-control";
		$this->_3_Hostigamiento->EditCustomAttributes = "";
		$this->_3_Hostigamiento->EditValue = $this->_3_Hostigamiento->CurrentValue;
		$this->_3_Hostigamiento->ViewCustomAttributes = "";

		// 3_MAP_Controlada
		$this->_3_MAP_Controlada->EditAttrs["class"] = "form-control";
		$this->_3_MAP_Controlada->EditCustomAttributes = "";
		$this->_3_MAP_Controlada->EditValue = $this->_3_MAP_Controlada->CurrentValue;
		$this->_3_MAP_Controlada->ViewCustomAttributes = "";

		// 3_MAP_No_controlada
		$this->_3_MAP_No_controlada->EditAttrs["class"] = "form-control";
		$this->_3_MAP_No_controlada->EditCustomAttributes = "";
		$this->_3_MAP_No_controlada->EditValue = $this->_3_MAP_No_controlada->CurrentValue;
		$this->_3_MAP_No_controlada->ViewCustomAttributes = "";

		// 3_MUSE
		$this->_3_MUSE->EditAttrs["class"] = "form-control";
		$this->_3_MUSE->EditCustomAttributes = "";
		$this->_3_MUSE->EditValue = $this->_3_MUSE->CurrentValue;
		$this->_3_MUSE->ViewCustomAttributes = "";

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad->EditAttrs["class"] = "form-control";
		$this->_3_Operaciones_de_seguridad->EditCustomAttributes = "";
		$this->_3_Operaciones_de_seguridad->EditValue = $this->_3_Operaciones_de_seguridad->CurrentValue;
		$this->_3_Operaciones_de_seguridad->ViewCustomAttributes = "";

		// LATITUD_segurid
		$this->LATITUD_segurid->EditAttrs["class"] = "form-control";
		$this->LATITUD_segurid->EditCustomAttributes = "";
		$this->LATITUD_segurid->EditValue = $this->LATITUD_segurid->CurrentValue;
		$this->LATITUD_segurid->ViewCustomAttributes = "";

		// GRA_LAT_segurid
		$this->GRA_LAT_segurid->EditAttrs["class"] = "form-control";
		$this->GRA_LAT_segurid->EditCustomAttributes = "";
		$this->GRA_LAT_segurid->EditValue = $this->GRA_LAT_segurid->CurrentValue;
		$this->GRA_LAT_segurid->ViewCustomAttributes = "";

		// MIN_LAT_segurid
		$this->MIN_LAT_segurid->EditAttrs["class"] = "form-control";
		$this->MIN_LAT_segurid->EditCustomAttributes = "";
		$this->MIN_LAT_segurid->EditValue = $this->MIN_LAT_segurid->CurrentValue;
		$this->MIN_LAT_segurid->ViewCustomAttributes = "";

		// SEG_LAT_segurid
		$this->SEG_LAT_segurid->EditAttrs["class"] = "form-control";
		$this->SEG_LAT_segurid->EditCustomAttributes = "";
		$this->SEG_LAT_segurid->EditValue = $this->SEG_LAT_segurid->CurrentValue;
		$this->SEG_LAT_segurid->ViewCustomAttributes = "";

		// GRA_LONG_seguri
		$this->GRA_LONG_seguri->EditAttrs["class"] = "form-control";
		$this->GRA_LONG_seguri->EditCustomAttributes = "";
		$this->GRA_LONG_seguri->EditValue = $this->GRA_LONG_seguri->CurrentValue;
		$this->GRA_LONG_seguri->ViewCustomAttributes = "";

		// MIN_LONG_seguri
		$this->MIN_LONG_seguri->EditAttrs["class"] = "form-control";
		$this->MIN_LONG_seguri->EditCustomAttributes = "";
		$this->MIN_LONG_seguri->EditValue = $this->MIN_LONG_seguri->CurrentValue;
		$this->MIN_LONG_seguri->ViewCustomAttributes = "";

		// SEG_LONG_seguri
		$this->SEG_LONG_seguri->EditAttrs["class"] = "form-control";
		$this->SEG_LONG_seguri->EditCustomAttributes = "";
		$this->SEG_LONG_seguri->EditValue = $this->SEG_LONG_seguri->CurrentValue;
		$this->SEG_LONG_seguri->ViewCustomAttributes = "";

		// Novedad
		$this->Novedad->EditAttrs["class"] = "form-control";
		$this->Novedad->EditCustomAttributes = "";
		$this->Novedad->EditValue = $this->Novedad->CurrentValue;
		$this->Novedad->ViewCustomAttributes = "";

		// 4_Epidemia
		$this->_4_Epidemia->EditAttrs["class"] = "form-control";
		$this->_4_Epidemia->EditCustomAttributes = "";
		$this->_4_Epidemia->EditValue = $this->_4_Epidemia->CurrentValue;
		$this->_4_Epidemia->ViewCustomAttributes = "";

		// 4_Novedad_climatologica
		$this->_4_Novedad_climatologica->EditAttrs["class"] = "form-control";
		$this->_4_Novedad_climatologica->EditCustomAttributes = "";
		$this->_4_Novedad_climatologica->EditValue = $this->_4_Novedad_climatologica->CurrentValue;
		$this->_4_Novedad_climatologica->ViewCustomAttributes = "";

		// 4_Registro_de_cultivos
		$this->_4_Registro_de_cultivos->EditAttrs["class"] = "form-control";
		$this->_4_Registro_de_cultivos->EditCustomAttributes = "";
		$this->_4_Registro_de_cultivos->EditValue = $this->_4_Registro_de_cultivos->CurrentValue;
		$this->_4_Registro_de_cultivos->ViewCustomAttributes = "";

		// 4_Zona_con_cultivos_muy_dispersos
		$this->_4_Zona_con_cultivos_muy_dispersos->EditAttrs["class"] = "form-control";
		$this->_4_Zona_con_cultivos_muy_dispersos->EditCustomAttributes = "";
		$this->_4_Zona_con_cultivos_muy_dispersos->EditValue = $this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue;
		$this->_4_Zona_con_cultivos_muy_dispersos->ViewCustomAttributes = "";

		// 4_Zona_de_cruce_de_rios_caudalosos
		$this->_4_Zona_de_cruce_de_rios_caudalosos->EditAttrs["class"] = "form-control";
		$this->_4_Zona_de_cruce_de_rios_caudalosos->EditCustomAttributes = "";
		$this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue = $this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue;
		$this->_4_Zona_de_cruce_de_rios_caudalosos->ViewCustomAttributes = "";

		// 4_Zona_sin_cultivos
		$this->_4_Zona_sin_cultivos->EditAttrs["class"] = "form-control";
		$this->_4_Zona_sin_cultivos->EditCustomAttributes = "";
		$this->_4_Zona_sin_cultivos->EditValue = $this->_4_Zona_sin_cultivos->CurrentValue;
		$this->_4_Zona_sin_cultivos->ViewCustomAttributes = "";

		// Num_Erra_Salen
		$this->Num_Erra_Salen->EditAttrs["class"] = "form-control";
		$this->Num_Erra_Salen->EditCustomAttributes = "";
		$this->Num_Erra_Salen->EditValue = $this->Num_Erra_Salen->CurrentValue;
		$this->Num_Erra_Salen->ViewCustomAttributes = "";

		// Num_Erra_Quedan
		$this->Num_Erra_Quedan->EditAttrs["class"] = "form-control";
		$this->Num_Erra_Quedan->EditCustomAttributes = "";
		$this->Num_Erra_Quedan->EditValue = $this->Num_Erra_Quedan->CurrentValue;
		$this->Num_Erra_Quedan->ViewCustomAttributes = "";

		// No_ENFERMERO
		$this->No_ENFERMERO->EditAttrs["class"] = "form-control";
		$this->No_ENFERMERO->EditCustomAttributes = "";
		$this->No_ENFERMERO->EditValue = $this->No_ENFERMERO->CurrentValue;
		$this->No_ENFERMERO->ViewCustomAttributes = "";

		// NUM_FP
		$this->NUM_FP->EditAttrs["class"] = "form-control";
		$this->NUM_FP->EditCustomAttributes = "";
		$this->NUM_FP->EditValue = $this->NUM_FP->CurrentValue;
		$this->NUM_FP->ViewCustomAttributes = "";

		// NUM_Perso_EVA
		$this->NUM_Perso_EVA->EditAttrs["class"] = "form-control";
		$this->NUM_Perso_EVA->EditCustomAttributes = "";
		$this->NUM_Perso_EVA->EditValue = $this->NUM_Perso_EVA->CurrentValue;
		$this->NUM_Perso_EVA->ViewCustomAttributes = "";

		// NUM_Poli
		$this->NUM_Poli->EditAttrs["class"] = "form-control";
		$this->NUM_Poli->EditCustomAttributes = "";
		$this->NUM_Poli->EditValue = $this->NUM_Poli->CurrentValue;
		$this->NUM_Poli->ViewCustomAttributes = "";

		// AÑO
		$this->AD1O->EditAttrs["class"] = "form-control";
		$this->AD1O->EditCustomAttributes = "";
		$this->AD1O->EditValue = $this->AD1O->CurrentValue;
		$this->AD1O->ViewCustomAttributes = "";

		// FASE
		$this->FASE->EditAttrs["class"] = "form-control";
		$this->FASE->EditCustomAttributes = "";
		$this->FASE->EditValue = $this->FASE->CurrentValue;
		$this->FASE->ViewCustomAttributes = "";

		// Modificado
		$this->Modificado->EditAttrs["class"] = "form-control";
		$this->Modificado->EditCustomAttributes = "";
		$this->Modificado->EditValue = $this->Modificado->CurrentValue;
		$this->Modificado->ViewCustomAttributes = "";

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
					if ($this->llave->Exportable) $Doc->ExportCaption($this->llave);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
					if ($this->USUARIO->Exportable) $Doc->ExportCaption($this->USUARIO);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_PE->Exportable) $Doc->ExportCaption($this->Otro_PE);
					if ($this->NOM_PGE->Exportable) $Doc->ExportCaption($this->NOM_PGE);
					if ($this->Otro_NOM_PGE->Exportable) $Doc->ExportCaption($this->Otro_NOM_PGE);
					if ($this->Otro_CC_PGE->Exportable) $Doc->ExportCaption($this->Otro_CC_PGE);
					if ($this->TIPO_INFORME->Exportable) $Doc->ExportCaption($this->TIPO_INFORME);
					if ($this->FECHA_REPORT->Exportable) $Doc->ExportCaption($this->FECHA_REPORT);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->TEMA->Exportable) $Doc->ExportCaption($this->TEMA);
					if ($this->Otro_Tema->Exportable) $Doc->ExportCaption($this->Otro_Tema);
					if ($this->OBSERVACION->Exportable) $Doc->ExportCaption($this->OBSERVACION);
					if ($this->FUERZA->Exportable) $Doc->ExportCaption($this->FUERZA);
					if ($this->NOM_VDA->Exportable) $Doc->ExportCaption($this->NOM_VDA);
					if ($this->Ha_Coca->Exportable) $Doc->ExportCaption($this->Ha_Coca);
					if ($this->Ha_Amapola->Exportable) $Doc->ExportCaption($this->Ha_Amapola);
					if ($this->Ha_Marihuana->Exportable) $Doc->ExportCaption($this->Ha_Marihuana);
					if ($this->T_erradi->Exportable) $Doc->ExportCaption($this->T_erradi);
					if ($this->LATITUD_sector->Exportable) $Doc->ExportCaption($this->LATITUD_sector);
					if ($this->GRA_LAT_Sector->Exportable) $Doc->ExportCaption($this->GRA_LAT_Sector);
					if ($this->MIN_LAT_Sector->Exportable) $Doc->ExportCaption($this->MIN_LAT_Sector);
					if ($this->SEG_LAT_Sector->Exportable) $Doc->ExportCaption($this->SEG_LAT_Sector);
					if ($this->GRA_LONG_Sector->Exportable) $Doc->ExportCaption($this->GRA_LONG_Sector);
					if ($this->MIN_LONG_Sector->Exportable) $Doc->ExportCaption($this->MIN_LONG_Sector);
					if ($this->SEG_LONG_Sector->Exportable) $Doc->ExportCaption($this->SEG_LONG_Sector);
					if ($this->Ini_Jorna->Exportable) $Doc->ExportCaption($this->Ini_Jorna);
					if ($this->Fin_Jorna->Exportable) $Doc->ExportCaption($this->Fin_Jorna);
					if ($this->Situ_Especial->Exportable) $Doc->ExportCaption($this->Situ_Especial);
					if ($this->Adm_GME->Exportable) $Doc->ExportCaption($this->Adm_GME);
					if ($this->_1_Abastecimiento->Exportable) $Doc->ExportCaption($this->_1_Abastecimiento);
					if ($this->_1_Acompanamiento_firma_GME->Exportable) $Doc->ExportCaption($this->_1_Acompanamiento_firma_GME);
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
					if ($this->Adm_Fuerza->Exportable) $Doc->ExportCaption($this->Adm_Fuerza);
					if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportCaption($this->_2_A_la_espera_definicion_nuevo_punto_FP);
					if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_de_seguridad);
					if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_que_abastece);
					if ($this->_2_Induccion_FP->Exportable) $Doc->ExportCaption($this->_2_Induccion_FP);
					if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportCaption($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
					if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportCaption($this->_2_Problemas_fuerza_publica);
					if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportCaption($this->_2_Sin_seguridad);
					if ($this->Sit_Seguridad->Exportable) $Doc->ExportCaption($this->Sit_Seguridad);
					if ($this->_3_AEI_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_controlado);
					if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_no_controlado);
					if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_parcial_de_la_comunidad);
					if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_total_de_la_comunidad);
					if ($this->_3_Combate->Exportable) $Doc->ExportCaption($this->_3_Combate);
					if ($this->_3_Hostigamiento->Exportable) $Doc->ExportCaption($this->_3_Hostigamiento);
					if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_Controlada);
					if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_No_controlada);
					if ($this->_3_MUSE->Exportable) $Doc->ExportCaption($this->_3_MUSE);
					if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportCaption($this->_3_Operaciones_de_seguridad);
					if ($this->LATITUD_segurid->Exportable) $Doc->ExportCaption($this->LATITUD_segurid);
					if ($this->GRA_LAT_segurid->Exportable) $Doc->ExportCaption($this->GRA_LAT_segurid);
					if ($this->MIN_LAT_segurid->Exportable) $Doc->ExportCaption($this->MIN_LAT_segurid);
					if ($this->SEG_LAT_segurid->Exportable) $Doc->ExportCaption($this->SEG_LAT_segurid);
					if ($this->GRA_LONG_seguri->Exportable) $Doc->ExportCaption($this->GRA_LONG_seguri);
					if ($this->MIN_LONG_seguri->Exportable) $Doc->ExportCaption($this->MIN_LONG_seguri);
					if ($this->SEG_LONG_seguri->Exportable) $Doc->ExportCaption($this->SEG_LONG_seguri);
					if ($this->Novedad->Exportable) $Doc->ExportCaption($this->Novedad);
					if ($this->_4_Epidemia->Exportable) $Doc->ExportCaption($this->_4_Epidemia);
					if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportCaption($this->_4_Novedad_climatologica);
					if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportCaption($this->_4_Registro_de_cultivos);
					if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportCaption($this->_4_Zona_con_cultivos_muy_dispersos);
					if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportCaption($this->_4_Zona_de_cruce_de_rios_caudalosos);
					if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportCaption($this->_4_Zona_sin_cultivos);
					if ($this->Num_Erra_Salen->Exportable) $Doc->ExportCaption($this->Num_Erra_Salen);
					if ($this->Num_Erra_Quedan->Exportable) $Doc->ExportCaption($this->Num_Erra_Quedan);
					if ($this->No_ENFERMERO->Exportable) $Doc->ExportCaption($this->No_ENFERMERO);
					if ($this->NUM_FP->Exportable) $Doc->ExportCaption($this->NUM_FP);
					if ($this->NUM_Perso_EVA->Exportable) $Doc->ExportCaption($this->NUM_Perso_EVA);
					if ($this->NUM_Poli->Exportable) $Doc->ExportCaption($this->NUM_Poli);
					if ($this->AD1O->Exportable) $Doc->ExportCaption($this->AD1O);
					if ($this->FASE->Exportable) $Doc->ExportCaption($this->FASE);
					if ($this->Modificado->Exportable) $Doc->ExportCaption($this->Modificado);
				} else {
					if ($this->llave->Exportable) $Doc->ExportCaption($this->llave);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
					if ($this->USUARIO->Exportable) $Doc->ExportCaption($this->USUARIO);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_PE->Exportable) $Doc->ExportCaption($this->Otro_PE);
					if ($this->NOM_PGE->Exportable) $Doc->ExportCaption($this->NOM_PGE);
					if ($this->Otro_NOM_PGE->Exportable) $Doc->ExportCaption($this->Otro_NOM_PGE);
					if ($this->Otro_CC_PGE->Exportable) $Doc->ExportCaption($this->Otro_CC_PGE);
					if ($this->TIPO_INFORME->Exportable) $Doc->ExportCaption($this->TIPO_INFORME);
					if ($this->FECHA_REPORT->Exportable) $Doc->ExportCaption($this->FECHA_REPORT);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->TEMA->Exportable) $Doc->ExportCaption($this->TEMA);
					if ($this->Otro_Tema->Exportable) $Doc->ExportCaption($this->Otro_Tema);
					if ($this->OBSERVACION->Exportable) $Doc->ExportCaption($this->OBSERVACION);
					if ($this->NOM_VDA->Exportable) $Doc->ExportCaption($this->NOM_VDA);
					if ($this->Ha_Coca->Exportable) $Doc->ExportCaption($this->Ha_Coca);
					if ($this->Ha_Amapola->Exportable) $Doc->ExportCaption($this->Ha_Amapola);
					if ($this->Ha_Marihuana->Exportable) $Doc->ExportCaption($this->Ha_Marihuana);
					if ($this->T_erradi->Exportable) $Doc->ExportCaption($this->T_erradi);
					if ($this->LATITUD_sector->Exportable) $Doc->ExportCaption($this->LATITUD_sector);
					if ($this->GRA_LAT_Sector->Exportable) $Doc->ExportCaption($this->GRA_LAT_Sector);
					if ($this->MIN_LAT_Sector->Exportable) $Doc->ExportCaption($this->MIN_LAT_Sector);
					if ($this->SEG_LAT_Sector->Exportable) $Doc->ExportCaption($this->SEG_LAT_Sector);
					if ($this->GRA_LONG_Sector->Exportable) $Doc->ExportCaption($this->GRA_LONG_Sector);
					if ($this->MIN_LONG_Sector->Exportable) $Doc->ExportCaption($this->MIN_LONG_Sector);
					if ($this->SEG_LONG_Sector->Exportable) $Doc->ExportCaption($this->SEG_LONG_Sector);
					if ($this->Ini_Jorna->Exportable) $Doc->ExportCaption($this->Ini_Jorna);
					if ($this->Fin_Jorna->Exportable) $Doc->ExportCaption($this->Fin_Jorna);
					if ($this->Situ_Especial->Exportable) $Doc->ExportCaption($this->Situ_Especial);
					if ($this->Adm_GME->Exportable) $Doc->ExportCaption($this->Adm_GME);
					if ($this->_1_Abastecimiento->Exportable) $Doc->ExportCaption($this->_1_Abastecimiento);
					if ($this->_1_Acompanamiento_firma_GME->Exportable) $Doc->ExportCaption($this->_1_Acompanamiento_firma_GME);
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
					if ($this->Adm_Fuerza->Exportable) $Doc->ExportCaption($this->Adm_Fuerza);
					if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportCaption($this->_2_A_la_espera_definicion_nuevo_punto_FP);
					if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_de_seguridad);
					if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportCaption($this->_2_Espera_helicoptero_FP_que_abastece);
					if ($this->_2_Induccion_FP->Exportable) $Doc->ExportCaption($this->_2_Induccion_FP);
					if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportCaption($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
					if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportCaption($this->_2_Problemas_fuerza_publica);
					if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportCaption($this->_2_Sin_seguridad);
					if ($this->Sit_Seguridad->Exportable) $Doc->ExportCaption($this->Sit_Seguridad);
					if ($this->_3_AEI_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_controlado);
					if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportCaption($this->_3_AEI_no_controlado);
					if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_parcial_de_la_comunidad);
					if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportCaption($this->_3_Bloqueo_total_de_la_comunidad);
					if ($this->_3_Combate->Exportable) $Doc->ExportCaption($this->_3_Combate);
					if ($this->_3_Hostigamiento->Exportable) $Doc->ExportCaption($this->_3_Hostigamiento);
					if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_Controlada);
					if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportCaption($this->_3_MAP_No_controlada);
					if ($this->_3_MUSE->Exportable) $Doc->ExportCaption($this->_3_MUSE);
					if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportCaption($this->_3_Operaciones_de_seguridad);
					if ($this->LATITUD_segurid->Exportable) $Doc->ExportCaption($this->LATITUD_segurid);
					if ($this->GRA_LAT_segurid->Exportable) $Doc->ExportCaption($this->GRA_LAT_segurid);
					if ($this->MIN_LAT_segurid->Exportable) $Doc->ExportCaption($this->MIN_LAT_segurid);
					if ($this->SEG_LAT_segurid->Exportable) $Doc->ExportCaption($this->SEG_LAT_segurid);
					if ($this->GRA_LONG_seguri->Exportable) $Doc->ExportCaption($this->GRA_LONG_seguri);
					if ($this->MIN_LONG_seguri->Exportable) $Doc->ExportCaption($this->MIN_LONG_seguri);
					if ($this->SEG_LONG_seguri->Exportable) $Doc->ExportCaption($this->SEG_LONG_seguri);
					if ($this->Novedad->Exportable) $Doc->ExportCaption($this->Novedad);
					if ($this->_4_Epidemia->Exportable) $Doc->ExportCaption($this->_4_Epidemia);
					if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportCaption($this->_4_Novedad_climatologica);
					if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportCaption($this->_4_Registro_de_cultivos);
					if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportCaption($this->_4_Zona_con_cultivos_muy_dispersos);
					if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportCaption($this->_4_Zona_de_cruce_de_rios_caudalosos);
					if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportCaption($this->_4_Zona_sin_cultivos);
					if ($this->Num_Erra_Salen->Exportable) $Doc->ExportCaption($this->Num_Erra_Salen);
					if ($this->Num_Erra_Quedan->Exportable) $Doc->ExportCaption($this->Num_Erra_Quedan);
					if ($this->No_ENFERMERO->Exportable) $Doc->ExportCaption($this->No_ENFERMERO);
					if ($this->NUM_FP->Exportable) $Doc->ExportCaption($this->NUM_FP);
					if ($this->NUM_Perso_EVA->Exportable) $Doc->ExportCaption($this->NUM_Perso_EVA);
					if ($this->NUM_Poli->Exportable) $Doc->ExportCaption($this->NUM_Poli);
					if ($this->AD1O->Exportable) $Doc->ExportCaption($this->AD1O);
					if ($this->FASE->Exportable) $Doc->ExportCaption($this->FASE);
					if ($this->Modificado->Exportable) $Doc->ExportCaption($this->Modificado);
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
						if ($this->llave->Exportable) $Doc->ExportField($this->llave);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
						if ($this->USUARIO->Exportable) $Doc->ExportField($this->USUARIO);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_PE->Exportable) $Doc->ExportField($this->Otro_PE);
						if ($this->NOM_PGE->Exportable) $Doc->ExportField($this->NOM_PGE);
						if ($this->Otro_NOM_PGE->Exportable) $Doc->ExportField($this->Otro_NOM_PGE);
						if ($this->Otro_CC_PGE->Exportable) $Doc->ExportField($this->Otro_CC_PGE);
						if ($this->TIPO_INFORME->Exportable) $Doc->ExportField($this->TIPO_INFORME);
						if ($this->FECHA_REPORT->Exportable) $Doc->ExportField($this->FECHA_REPORT);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->TEMA->Exportable) $Doc->ExportField($this->TEMA);
						if ($this->Otro_Tema->Exportable) $Doc->ExportField($this->Otro_Tema);
						if ($this->OBSERVACION->Exportable) $Doc->ExportField($this->OBSERVACION);
						if ($this->FUERZA->Exportable) $Doc->ExportField($this->FUERZA);
						if ($this->NOM_VDA->Exportable) $Doc->ExportField($this->NOM_VDA);
						if ($this->Ha_Coca->Exportable) $Doc->ExportField($this->Ha_Coca);
						if ($this->Ha_Amapola->Exportable) $Doc->ExportField($this->Ha_Amapola);
						if ($this->Ha_Marihuana->Exportable) $Doc->ExportField($this->Ha_Marihuana);
						if ($this->T_erradi->Exportable) $Doc->ExportField($this->T_erradi);
						if ($this->LATITUD_sector->Exportable) $Doc->ExportField($this->LATITUD_sector);
						if ($this->GRA_LAT_Sector->Exportable) $Doc->ExportField($this->GRA_LAT_Sector);
						if ($this->MIN_LAT_Sector->Exportable) $Doc->ExportField($this->MIN_LAT_Sector);
						if ($this->SEG_LAT_Sector->Exportable) $Doc->ExportField($this->SEG_LAT_Sector);
						if ($this->GRA_LONG_Sector->Exportable) $Doc->ExportField($this->GRA_LONG_Sector);
						if ($this->MIN_LONG_Sector->Exportable) $Doc->ExportField($this->MIN_LONG_Sector);
						if ($this->SEG_LONG_Sector->Exportable) $Doc->ExportField($this->SEG_LONG_Sector);
						if ($this->Ini_Jorna->Exportable) $Doc->ExportField($this->Ini_Jorna);
						if ($this->Fin_Jorna->Exportable) $Doc->ExportField($this->Fin_Jorna);
						if ($this->Situ_Especial->Exportable) $Doc->ExportField($this->Situ_Especial);
						if ($this->Adm_GME->Exportable) $Doc->ExportField($this->Adm_GME);
						if ($this->_1_Abastecimiento->Exportable) $Doc->ExportField($this->_1_Abastecimiento);
						if ($this->_1_Acompanamiento_firma_GME->Exportable) $Doc->ExportField($this->_1_Acompanamiento_firma_GME);
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
						if ($this->Adm_Fuerza->Exportable) $Doc->ExportField($this->Adm_Fuerza);
						if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportField($this->_2_A_la_espera_definicion_nuevo_punto_FP);
						if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_de_seguridad);
						if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_que_abastece);
						if ($this->_2_Induccion_FP->Exportable) $Doc->ExportField($this->_2_Induccion_FP);
						if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportField($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
						if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportField($this->_2_Problemas_fuerza_publica);
						if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportField($this->_2_Sin_seguridad);
						if ($this->Sit_Seguridad->Exportable) $Doc->ExportField($this->Sit_Seguridad);
						if ($this->_3_AEI_controlado->Exportable) $Doc->ExportField($this->_3_AEI_controlado);
						if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportField($this->_3_AEI_no_controlado);
						if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_parcial_de_la_comunidad);
						if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_total_de_la_comunidad);
						if ($this->_3_Combate->Exportable) $Doc->ExportField($this->_3_Combate);
						if ($this->_3_Hostigamiento->Exportable) $Doc->ExportField($this->_3_Hostigamiento);
						if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportField($this->_3_MAP_Controlada);
						if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportField($this->_3_MAP_No_controlada);
						if ($this->_3_MUSE->Exportable) $Doc->ExportField($this->_3_MUSE);
						if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportField($this->_3_Operaciones_de_seguridad);
						if ($this->LATITUD_segurid->Exportable) $Doc->ExportField($this->LATITUD_segurid);
						if ($this->GRA_LAT_segurid->Exportable) $Doc->ExportField($this->GRA_LAT_segurid);
						if ($this->MIN_LAT_segurid->Exportable) $Doc->ExportField($this->MIN_LAT_segurid);
						if ($this->SEG_LAT_segurid->Exportable) $Doc->ExportField($this->SEG_LAT_segurid);
						if ($this->GRA_LONG_seguri->Exportable) $Doc->ExportField($this->GRA_LONG_seguri);
						if ($this->MIN_LONG_seguri->Exportable) $Doc->ExportField($this->MIN_LONG_seguri);
						if ($this->SEG_LONG_seguri->Exportable) $Doc->ExportField($this->SEG_LONG_seguri);
						if ($this->Novedad->Exportable) $Doc->ExportField($this->Novedad);
						if ($this->_4_Epidemia->Exportable) $Doc->ExportField($this->_4_Epidemia);
						if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportField($this->_4_Novedad_climatologica);
						if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportField($this->_4_Registro_de_cultivos);
						if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportField($this->_4_Zona_con_cultivos_muy_dispersos);
						if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportField($this->_4_Zona_de_cruce_de_rios_caudalosos);
						if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportField($this->_4_Zona_sin_cultivos);
						if ($this->Num_Erra_Salen->Exportable) $Doc->ExportField($this->Num_Erra_Salen);
						if ($this->Num_Erra_Quedan->Exportable) $Doc->ExportField($this->Num_Erra_Quedan);
						if ($this->No_ENFERMERO->Exportable) $Doc->ExportField($this->No_ENFERMERO);
						if ($this->NUM_FP->Exportable) $Doc->ExportField($this->NUM_FP);
						if ($this->NUM_Perso_EVA->Exportable) $Doc->ExportField($this->NUM_Perso_EVA);
						if ($this->NUM_Poli->Exportable) $Doc->ExportField($this->NUM_Poli);
						if ($this->AD1O->Exportable) $Doc->ExportField($this->AD1O);
						if ($this->FASE->Exportable) $Doc->ExportField($this->FASE);
						if ($this->Modificado->Exportable) $Doc->ExportField($this->Modificado);
					} else {
						if ($this->llave->Exportable) $Doc->ExportField($this->llave);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
						if ($this->USUARIO->Exportable) $Doc->ExportField($this->USUARIO);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_PE->Exportable) $Doc->ExportField($this->Otro_PE);
						if ($this->NOM_PGE->Exportable) $Doc->ExportField($this->NOM_PGE);
						if ($this->Otro_NOM_PGE->Exportable) $Doc->ExportField($this->Otro_NOM_PGE);
						if ($this->Otro_CC_PGE->Exportable) $Doc->ExportField($this->Otro_CC_PGE);
						if ($this->TIPO_INFORME->Exportable) $Doc->ExportField($this->TIPO_INFORME);
						if ($this->FECHA_REPORT->Exportable) $Doc->ExportField($this->FECHA_REPORT);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->TEMA->Exportable) $Doc->ExportField($this->TEMA);
						if ($this->Otro_Tema->Exportable) $Doc->ExportField($this->Otro_Tema);
						if ($this->OBSERVACION->Exportable) $Doc->ExportField($this->OBSERVACION);
						if ($this->NOM_VDA->Exportable) $Doc->ExportField($this->NOM_VDA);
						if ($this->Ha_Coca->Exportable) $Doc->ExportField($this->Ha_Coca);
						if ($this->Ha_Amapola->Exportable) $Doc->ExportField($this->Ha_Amapola);
						if ($this->Ha_Marihuana->Exportable) $Doc->ExportField($this->Ha_Marihuana);
						if ($this->T_erradi->Exportable) $Doc->ExportField($this->T_erradi);
						if ($this->LATITUD_sector->Exportable) $Doc->ExportField($this->LATITUD_sector);
						if ($this->GRA_LAT_Sector->Exportable) $Doc->ExportField($this->GRA_LAT_Sector);
						if ($this->MIN_LAT_Sector->Exportable) $Doc->ExportField($this->MIN_LAT_Sector);
						if ($this->SEG_LAT_Sector->Exportable) $Doc->ExportField($this->SEG_LAT_Sector);
						if ($this->GRA_LONG_Sector->Exportable) $Doc->ExportField($this->GRA_LONG_Sector);
						if ($this->MIN_LONG_Sector->Exportable) $Doc->ExportField($this->MIN_LONG_Sector);
						if ($this->SEG_LONG_Sector->Exportable) $Doc->ExportField($this->SEG_LONG_Sector);
						if ($this->Ini_Jorna->Exportable) $Doc->ExportField($this->Ini_Jorna);
						if ($this->Fin_Jorna->Exportable) $Doc->ExportField($this->Fin_Jorna);
						if ($this->Situ_Especial->Exportable) $Doc->ExportField($this->Situ_Especial);
						if ($this->Adm_GME->Exportable) $Doc->ExportField($this->Adm_GME);
						if ($this->_1_Abastecimiento->Exportable) $Doc->ExportField($this->_1_Abastecimiento);
						if ($this->_1_Acompanamiento_firma_GME->Exportable) $Doc->ExportField($this->_1_Acompanamiento_firma_GME);
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
						if ($this->Adm_Fuerza->Exportable) $Doc->ExportField($this->Adm_Fuerza);
						if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->Exportable) $Doc->ExportField($this->_2_A_la_espera_definicion_nuevo_punto_FP);
						if ($this->_2_Espera_helicoptero_FP_de_seguridad->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_de_seguridad);
						if ($this->_2_Espera_helicoptero_FP_que_abastece->Exportable) $Doc->ExportField($this->_2_Espera_helicoptero_FP_que_abastece);
						if ($this->_2_Induccion_FP->Exportable) $Doc->ExportField($this->_2_Induccion_FP);
						if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->Exportable) $Doc->ExportField($this->_2_Novedad_canino_o_del_grupo_de_deteccion);
						if ($this->_2_Problemas_fuerza_publica->Exportable) $Doc->ExportField($this->_2_Problemas_fuerza_publica);
						if ($this->_2_Sin_seguridad->Exportable) $Doc->ExportField($this->_2_Sin_seguridad);
						if ($this->Sit_Seguridad->Exportable) $Doc->ExportField($this->Sit_Seguridad);
						if ($this->_3_AEI_controlado->Exportable) $Doc->ExportField($this->_3_AEI_controlado);
						if ($this->_3_AEI_no_controlado->Exportable) $Doc->ExportField($this->_3_AEI_no_controlado);
						if ($this->_3_Bloqueo_parcial_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_parcial_de_la_comunidad);
						if ($this->_3_Bloqueo_total_de_la_comunidad->Exportable) $Doc->ExportField($this->_3_Bloqueo_total_de_la_comunidad);
						if ($this->_3_Combate->Exportable) $Doc->ExportField($this->_3_Combate);
						if ($this->_3_Hostigamiento->Exportable) $Doc->ExportField($this->_3_Hostigamiento);
						if ($this->_3_MAP_Controlada->Exportable) $Doc->ExportField($this->_3_MAP_Controlada);
						if ($this->_3_MAP_No_controlada->Exportable) $Doc->ExportField($this->_3_MAP_No_controlada);
						if ($this->_3_MUSE->Exportable) $Doc->ExportField($this->_3_MUSE);
						if ($this->_3_Operaciones_de_seguridad->Exportable) $Doc->ExportField($this->_3_Operaciones_de_seguridad);
						if ($this->LATITUD_segurid->Exportable) $Doc->ExportField($this->LATITUD_segurid);
						if ($this->GRA_LAT_segurid->Exportable) $Doc->ExportField($this->GRA_LAT_segurid);
						if ($this->MIN_LAT_segurid->Exportable) $Doc->ExportField($this->MIN_LAT_segurid);
						if ($this->SEG_LAT_segurid->Exportable) $Doc->ExportField($this->SEG_LAT_segurid);
						if ($this->GRA_LONG_seguri->Exportable) $Doc->ExportField($this->GRA_LONG_seguri);
						if ($this->MIN_LONG_seguri->Exportable) $Doc->ExportField($this->MIN_LONG_seguri);
						if ($this->SEG_LONG_seguri->Exportable) $Doc->ExportField($this->SEG_LONG_seguri);
						if ($this->Novedad->Exportable) $Doc->ExportField($this->Novedad);
						if ($this->_4_Epidemia->Exportable) $Doc->ExportField($this->_4_Epidemia);
						if ($this->_4_Novedad_climatologica->Exportable) $Doc->ExportField($this->_4_Novedad_climatologica);
						if ($this->_4_Registro_de_cultivos->Exportable) $Doc->ExportField($this->_4_Registro_de_cultivos);
						if ($this->_4_Zona_con_cultivos_muy_dispersos->Exportable) $Doc->ExportField($this->_4_Zona_con_cultivos_muy_dispersos);
						if ($this->_4_Zona_de_cruce_de_rios_caudalosos->Exportable) $Doc->ExportField($this->_4_Zona_de_cruce_de_rios_caudalosos);
						if ($this->_4_Zona_sin_cultivos->Exportable) $Doc->ExportField($this->_4_Zona_sin_cultivos);
						if ($this->Num_Erra_Salen->Exportable) $Doc->ExportField($this->Num_Erra_Salen);
						if ($this->Num_Erra_Quedan->Exportable) $Doc->ExportField($this->Num_Erra_Quedan);
						if ($this->No_ENFERMERO->Exportable) $Doc->ExportField($this->No_ENFERMERO);
						if ($this->NUM_FP->Exportable) $Doc->ExportField($this->NUM_FP);
						if ($this->NUM_Perso_EVA->Exportable) $Doc->ExportField($this->NUM_Perso_EVA);
						if ($this->NUM_Poli->Exportable) $Doc->ExportField($this->NUM_Poli);
						if ($this->AD1O->Exportable) $Doc->ExportField($this->AD1O);
						if ($this->FASE->Exportable) $Doc->ExportField($this->FASE);
						if ($this->Modificado->Exportable) $Doc->ExportField($this->Modificado);
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
