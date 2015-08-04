<?php

// Global variable for table object
$inventario = NULL;

//
// Table class for inventario
//
class cinventario extends cTable {
	var $llave;
	var $USUARIO;
	var $Cargo_gme;
	var $DIA;
	var $MES;
	var $NOM_PE;
	var $Otro_PE;
	var $OBSERVACION;
	var $AD1O;
	var $FASE;
	var $F_Sincron;
	var $FECHA_INV;
	var $TIPO_INV;
	var $NOM_CAPATAZ;
	var $Otro_NOM_CAPAT;
	var $Otro_CC_CAPAT;
	var $NOM_LUGAR;
	var $Cocina;
	var $_1_Abrelatas;
	var $_1_Balde;
	var $_1_Arrocero_50;
	var $_1_Arrocero_44;
	var $_1_Chocolatera;
	var $_1_Colador;
	var $_1_Cucharon_sopa;
	var $_1_Cucharon_arroz;
	var $_1_Cuchillo;
	var $_1_Embudo;
	var $_1_Espumera;
	var $_1_Estufa;
	var $_1_Cuchara_sopa;
	var $_1_Recipiente;
	var $_1_Kit_Repue_estufa;
	var $_1_Molinillo;
	var $_1_Olla_36;
	var $_1_Olla_40;
	var $_1_Paila_32;
	var $_1_Paila_36_37;
	var $Camping;
	var $_2_Aislante;
	var $_2_Carpa_hamaca;
	var $_2_Carpa_rancho;
	var $_2_Fibra_rollo;
	var $_2_CAL;
	var $_2_Linterna;
	var $_2_Botiquin;
	var $_2_Mascara_filtro;
	var $_2_Pimpina;
	var $_2_SleepingA0;
	var $_2_Plastico_negro;
	var $_2_Tula_tropa;
	var $_2_Camilla;
	var $Herramientas;
	var $_3_Abrazadera;
	var $_3_Aspersora;
	var $_3_Cabo_hacha;
	var $_3_Funda_machete;
	var $_3_Glifosato_4lt;
	var $_3_Hacha;
	var $_3_Lima_12_uni;
	var $_3_Llave_mixta;
	var $_3_Machete;
	var $_3_Gafa_traslucida;
	var $_3_Motosierra;
	var $_3_Palin;
	var $_3_Tubo_galvanizado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'inventario';
		$this->TableName = 'inventario';
		$this->TableType = 'TABLE';
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

		// llave
		$this->llave = new cField('inventario', 'inventario', 'x_llave', 'llave', '`llave`', '`llave`', 200, -1, FALSE, '`llave`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['llave'] = &$this->llave;

		// USUARIO
		$this->USUARIO = new cField('inventario', 'inventario', 'x_USUARIO', 'USUARIO', '`USUARIO`', '`USUARIO`', 201, -1, FALSE, '`USUARIO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['USUARIO'] = &$this->USUARIO;

		// Cargo_gme
		$this->Cargo_gme = new cField('inventario', 'inventario', 'x_Cargo_gme', 'Cargo_gme', '`Cargo_gme`', '`Cargo_gme`', 200, -1, FALSE, '`Cargo_gme`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_gme'] = &$this->Cargo_gme;

		// DIA
		$this->DIA = new cField('inventario', 'inventario', 'x_DIA', 'DIA', '`DIA`', '`DIA`', 200, -1, FALSE, '`DIA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DIA'] = &$this->DIA;

		// MES
		$this->MES = new cField('inventario', 'inventario', 'x_MES', 'MES', '`MES`', '`MES`', 200, -1, FALSE, '`MES`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['MES'] = &$this->MES;

		// NOM_PE
		$this->NOM_PE = new cField('inventario', 'inventario', 'x_NOM_PE', 'NOM_PE', '`NOM_PE`', '`NOM_PE`', 200, -1, FALSE, '`NOM_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_PE'] = &$this->NOM_PE;

		// Otro_PE
		$this->Otro_PE = new cField('inventario', 'inventario', 'x_Otro_PE', 'Otro_PE', '`Otro_PE`', '`Otro_PE`', 200, -1, FALSE, '`Otro_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_PE'] = &$this->Otro_PE;

		// OBSERVACION
		$this->OBSERVACION = new cField('inventario', 'inventario', 'x_OBSERVACION', 'OBSERVACION', '`OBSERVACION`', '`OBSERVACION`', 201, -1, FALSE, '`OBSERVACION`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['OBSERVACION'] = &$this->OBSERVACION;

		// AÑO
		$this->AD1O = new cField('inventario', 'inventario', 'x_AD1O', 'AÑO', '`AÑO`', '`AÑO`', 200, -1, FALSE, '`AÑO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['AÑO'] = &$this->AD1O;

		// FASE
		$this->FASE = new cField('inventario', 'inventario', 'x_FASE', 'FASE', '`FASE`', '`FASE`', 200, -1, FALSE, '`FASE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FASE'] = &$this->FASE;

		// F_Sincron
		$this->F_Sincron = new cField('inventario', 'inventario', 'x_F_Sincron', 'F_Sincron', '`F_Sincron`', 'DATE_FORMAT(`F_Sincron`, \'%d/%m/%Y\')', 135, 7, FALSE, '`F_Sincron`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->F_Sincron->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['F_Sincron'] = &$this->F_Sincron;

		// FECHA_INV
		$this->FECHA_INV = new cField('inventario', 'inventario', 'x_FECHA_INV', 'FECHA_INV', '`FECHA_INV`', '`FECHA_INV`', 200, -1, FALSE, '`FECHA_INV`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FECHA_INV'] = &$this->FECHA_INV;

		// TIPO_INV
		$this->TIPO_INV = new cField('inventario', 'inventario', 'x_TIPO_INV', 'TIPO_INV', '`TIPO_INV`', '`TIPO_INV`', 201, -1, FALSE, '`TIPO_INV`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TIPO_INV'] = &$this->TIPO_INV;

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ = new cField('inventario', 'inventario', 'x_NOM_CAPATAZ', 'NOM_CAPATAZ', '`NOM_CAPATAZ`', '`NOM_CAPATAZ`', 201, -1, FALSE, '`NOM_CAPATAZ`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_CAPATAZ'] = &$this->NOM_CAPATAZ;

		// Otro_NOM_CAPAT
		$this->Otro_NOM_CAPAT = new cField('inventario', 'inventario', 'x_Otro_NOM_CAPAT', 'Otro_NOM_CAPAT', '`Otro_NOM_CAPAT`', '`Otro_NOM_CAPAT`', 200, -1, FALSE, '`Otro_NOM_CAPAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_NOM_CAPAT'] = &$this->Otro_NOM_CAPAT;

		// Otro_CC_CAPAT
		$this->Otro_CC_CAPAT = new cField('inventario', 'inventario', 'x_Otro_CC_CAPAT', 'Otro_CC_CAPAT', '`Otro_CC_CAPAT`', '`Otro_CC_CAPAT`', 200, -1, FALSE, '`Otro_CC_CAPAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_CAPAT'] = &$this->Otro_CC_CAPAT;

		// NOM_LUGAR
		$this->NOM_LUGAR = new cField('inventario', 'inventario', 'x_NOM_LUGAR', 'NOM_LUGAR', '`NOM_LUGAR`', '`NOM_LUGAR`', 200, -1, FALSE, '`NOM_LUGAR`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_LUGAR'] = &$this->NOM_LUGAR;

		// Cocina
		$this->Cocina = new cField('inventario', 'inventario', 'x_Cocina', 'Cocina', '`Cocina`', '`Cocina`', 131, -1, FALSE, '`Cocina`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Cocina->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Cocina'] = &$this->Cocina;

		// 1_Abrelatas
		$this->_1_Abrelatas = new cField('inventario', 'inventario', 'x__1_Abrelatas', '1_Abrelatas', '`1_Abrelatas`', '`1_Abrelatas`', 3, -1, FALSE, '`1_Abrelatas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Abrelatas->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Abrelatas'] = &$this->_1_Abrelatas;

		// 1_Balde
		$this->_1_Balde = new cField('inventario', 'inventario', 'x__1_Balde', '1_Balde', '`1_Balde`', '`1_Balde`', 3, -1, FALSE, '`1_Balde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Balde->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Balde'] = &$this->_1_Balde;

		// 1_Arrocero_50
		$this->_1_Arrocero_50 = new cField('inventario', 'inventario', 'x__1_Arrocero_50', '1_Arrocero_50', '`1_Arrocero_50`', '`1_Arrocero_50`', 3, -1, FALSE, '`1_Arrocero_50`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Arrocero_50->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Arrocero_50'] = &$this->_1_Arrocero_50;

		// 1_Arrocero_44
		$this->_1_Arrocero_44 = new cField('inventario', 'inventario', 'x__1_Arrocero_44', '1_Arrocero_44', '`1_Arrocero_44`', '`1_Arrocero_44`', 3, -1, FALSE, '`1_Arrocero_44`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Arrocero_44->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Arrocero_44'] = &$this->_1_Arrocero_44;

		// 1_Chocolatera
		$this->_1_Chocolatera = new cField('inventario', 'inventario', 'x__1_Chocolatera', '1_Chocolatera', '`1_Chocolatera`', '`1_Chocolatera`', 3, -1, FALSE, '`1_Chocolatera`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Chocolatera->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Chocolatera'] = &$this->_1_Chocolatera;

		// 1_Colador
		$this->_1_Colador = new cField('inventario', 'inventario', 'x__1_Colador', '1_Colador', '`1_Colador`', '`1_Colador`', 3, -1, FALSE, '`1_Colador`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Colador->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Colador'] = &$this->_1_Colador;

		// 1_Cucharon_sopa
		$this->_1_Cucharon_sopa = new cField('inventario', 'inventario', 'x__1_Cucharon_sopa', '1_Cucharon_sopa', '`1_Cucharon_sopa`', '`1_Cucharon_sopa`', 3, -1, FALSE, '`1_Cucharon_sopa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Cucharon_sopa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Cucharon_sopa'] = &$this->_1_Cucharon_sopa;

		// 1_Cucharon_arroz
		$this->_1_Cucharon_arroz = new cField('inventario', 'inventario', 'x__1_Cucharon_arroz', '1_Cucharon_arroz', '`1_Cucharon_arroz`', '`1_Cucharon_arroz`', 3, -1, FALSE, '`1_Cucharon_arroz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Cucharon_arroz->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Cucharon_arroz'] = &$this->_1_Cucharon_arroz;

		// 1_Cuchillo
		$this->_1_Cuchillo = new cField('inventario', 'inventario', 'x__1_Cuchillo', '1_Cuchillo', '`1_Cuchillo`', '`1_Cuchillo`', 3, -1, FALSE, '`1_Cuchillo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Cuchillo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Cuchillo'] = &$this->_1_Cuchillo;

		// 1_Embudo
		$this->_1_Embudo = new cField('inventario', 'inventario', 'x__1_Embudo', '1_Embudo', '`1_Embudo`', '`1_Embudo`', 3, -1, FALSE, '`1_Embudo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Embudo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Embudo'] = &$this->_1_Embudo;

		// 1_Espumera
		$this->_1_Espumera = new cField('inventario', 'inventario', 'x__1_Espumera', '1_Espumera', '`1_Espumera`', '`1_Espumera`', 3, -1, FALSE, '`1_Espumera`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Espumera->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Espumera'] = &$this->_1_Espumera;

		// 1_Estufa
		$this->_1_Estufa = new cField('inventario', 'inventario', 'x__1_Estufa', '1_Estufa', '`1_Estufa`', '`1_Estufa`', 3, -1, FALSE, '`1_Estufa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Estufa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Estufa'] = &$this->_1_Estufa;

		// 1_Cuchara_sopa
		$this->_1_Cuchara_sopa = new cField('inventario', 'inventario', 'x__1_Cuchara_sopa', '1_Cuchara_sopa', '`1_Cuchara_sopa`', '`1_Cuchara_sopa`', 3, -1, FALSE, '`1_Cuchara_sopa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Cuchara_sopa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Cuchara_sopa'] = &$this->_1_Cuchara_sopa;

		// 1_Recipiente
		$this->_1_Recipiente = new cField('inventario', 'inventario', 'x__1_Recipiente', '1_Recipiente', '`1_Recipiente`', '`1_Recipiente`', 3, -1, FALSE, '`1_Recipiente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Recipiente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Recipiente'] = &$this->_1_Recipiente;

		// 1_Kit_Repue_estufa
		$this->_1_Kit_Repue_estufa = new cField('inventario', 'inventario', 'x__1_Kit_Repue_estufa', '1_Kit_Repue_estufa', '`1_Kit_Repue_estufa`', '`1_Kit_Repue_estufa`', 3, -1, FALSE, '`1_Kit_Repue_estufa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Kit_Repue_estufa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Kit_Repue_estufa'] = &$this->_1_Kit_Repue_estufa;

		// 1_Molinillo
		$this->_1_Molinillo = new cField('inventario', 'inventario', 'x__1_Molinillo', '1_Molinillo', '`1_Molinillo`', '`1_Molinillo`', 3, -1, FALSE, '`1_Molinillo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Molinillo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Molinillo'] = &$this->_1_Molinillo;

		// 1_Olla_36
		$this->_1_Olla_36 = new cField('inventario', 'inventario', 'x__1_Olla_36', '1_Olla_36', '`1_Olla_36`', '`1_Olla_36`', 3, -1, FALSE, '`1_Olla_36`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Olla_36->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Olla_36'] = &$this->_1_Olla_36;

		// 1_Olla_40
		$this->_1_Olla_40 = new cField('inventario', 'inventario', 'x__1_Olla_40', '1_Olla_40', '`1_Olla_40`', '`1_Olla_40`', 3, -1, FALSE, '`1_Olla_40`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Olla_40->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Olla_40'] = &$this->_1_Olla_40;

		// 1_Paila_32
		$this->_1_Paila_32 = new cField('inventario', 'inventario', 'x__1_Paila_32', '1_Paila_32', '`1_Paila_32`', '`1_Paila_32`', 3, -1, FALSE, '`1_Paila_32`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Paila_32->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Paila_32'] = &$this->_1_Paila_32;

		// 1_Paila_36_37
		$this->_1_Paila_36_37 = new cField('inventario', 'inventario', 'x__1_Paila_36_37', '1_Paila_36_37', '`1_Paila_36_37`', '`1_Paila_36_37`', 3, -1, FALSE, '`1_Paila_36_37`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_1_Paila_36_37->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['1_Paila_36_37'] = &$this->_1_Paila_36_37;

		// Camping
		$this->Camping = new cField('inventario', 'inventario', 'x_Camping', 'Camping', '`Camping`', '`Camping`', 131, -1, FALSE, '`Camping`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Camping->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Camping'] = &$this->Camping;

		// 2_Aislante
		$this->_2_Aislante = new cField('inventario', 'inventario', 'x__2_Aislante', '2_Aislante', '`2_Aislante`', '`2_Aislante`', 3, -1, FALSE, '`2_Aislante`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Aislante->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Aislante'] = &$this->_2_Aislante;

		// 2_Carpa_hamaca
		$this->_2_Carpa_hamaca = new cField('inventario', 'inventario', 'x__2_Carpa_hamaca', '2_Carpa_hamaca', '`2_Carpa_hamaca`', '`2_Carpa_hamaca`', 3, -1, FALSE, '`2_Carpa_hamaca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Carpa_hamaca->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Carpa_hamaca'] = &$this->_2_Carpa_hamaca;

		// 2_Carpa_rancho
		$this->_2_Carpa_rancho = new cField('inventario', 'inventario', 'x__2_Carpa_rancho', '2_Carpa_rancho', '`2_Carpa_rancho`', '`2_Carpa_rancho`', 3, -1, FALSE, '`2_Carpa_rancho`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Carpa_rancho->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Carpa_rancho'] = &$this->_2_Carpa_rancho;

		// 2_Fibra_rollo
		$this->_2_Fibra_rollo = new cField('inventario', 'inventario', 'x__2_Fibra_rollo', '2_Fibra_rollo', '`2_Fibra_rollo`', '`2_Fibra_rollo`', 3, -1, FALSE, '`2_Fibra_rollo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Fibra_rollo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Fibra_rollo'] = &$this->_2_Fibra_rollo;

		// 2_CAL
		$this->_2_CAL = new cField('inventario', 'inventario', 'x__2_CAL', '2_CAL', '`2_CAL`', '`2_CAL`', 3, -1, FALSE, '`2_CAL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_CAL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_CAL'] = &$this->_2_CAL;

		// 2_Linterna
		$this->_2_Linterna = new cField('inventario', 'inventario', 'x__2_Linterna', '2_Linterna', '`2_Linterna`', '`2_Linterna`', 3, -1, FALSE, '`2_Linterna`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Linterna->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Linterna'] = &$this->_2_Linterna;

		// 2_Botiquin
		$this->_2_Botiquin = new cField('inventario', 'inventario', 'x__2_Botiquin', '2_Botiquin', '`2_Botiquin`', '`2_Botiquin`', 3, -1, FALSE, '`2_Botiquin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Botiquin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Botiquin'] = &$this->_2_Botiquin;

		// 2_Mascara_filtro
		$this->_2_Mascara_filtro = new cField('inventario', 'inventario', 'x__2_Mascara_filtro', '2_Mascara_filtro', '`2_Mascara_filtro`', '`2_Mascara_filtro`', 3, -1, FALSE, '`2_Mascara_filtro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Mascara_filtro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Mascara_filtro'] = &$this->_2_Mascara_filtro;

		// 2_Pimpina
		$this->_2_Pimpina = new cField('inventario', 'inventario', 'x__2_Pimpina', '2_Pimpina', '`2_Pimpina`', '`2_Pimpina`', 3, -1, FALSE, '`2_Pimpina`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Pimpina->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Pimpina'] = &$this->_2_Pimpina;

		// 2_Sleeping 
		$this->_2_SleepingA0 = new cField('inventario', 'inventario', 'x__2_SleepingA0', '2_Sleeping ', '`2_Sleeping `', '`2_Sleeping `', 3, -1, FALSE, '`2_Sleeping `', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_SleepingA0->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Sleeping '] = &$this->_2_SleepingA0;

		// 2_Plastico_negro
		$this->_2_Plastico_negro = new cField('inventario', 'inventario', 'x__2_Plastico_negro', '2_Plastico_negro', '`2_Plastico_negro`', '`2_Plastico_negro`', 3, -1, FALSE, '`2_Plastico_negro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Plastico_negro->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Plastico_negro'] = &$this->_2_Plastico_negro;

		// 2_Tula_tropa
		$this->_2_Tula_tropa = new cField('inventario', 'inventario', 'x__2_Tula_tropa', '2_Tula_tropa', '`2_Tula_tropa`', '`2_Tula_tropa`', 3, -1, FALSE, '`2_Tula_tropa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Tula_tropa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Tula_tropa'] = &$this->_2_Tula_tropa;

		// 2_Camilla
		$this->_2_Camilla = new cField('inventario', 'inventario', 'x__2_Camilla', '2_Camilla', '`2_Camilla`', '`2_Camilla`', 3, -1, FALSE, '`2_Camilla`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_2_Camilla->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['2_Camilla'] = &$this->_2_Camilla;

		// Herramientas
		$this->Herramientas = new cField('inventario', 'inventario', 'x_Herramientas', 'Herramientas', '`Herramientas`', '`Herramientas`', 131, -1, FALSE, '`Herramientas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Herramientas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Herramientas'] = &$this->Herramientas;

		// 3_Abrazadera
		$this->_3_Abrazadera = new cField('inventario', 'inventario', 'x__3_Abrazadera', '3_Abrazadera', '`3_Abrazadera`', '`3_Abrazadera`', 3, -1, FALSE, '`3_Abrazadera`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Abrazadera->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Abrazadera'] = &$this->_3_Abrazadera;

		// 3_Aspersora
		$this->_3_Aspersora = new cField('inventario', 'inventario', 'x__3_Aspersora', '3_Aspersora', '`3_Aspersora`', '`3_Aspersora`', 3, -1, FALSE, '`3_Aspersora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Aspersora->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Aspersora'] = &$this->_3_Aspersora;

		// 3_Cabo_hacha
		$this->_3_Cabo_hacha = new cField('inventario', 'inventario', 'x__3_Cabo_hacha', '3_Cabo_hacha', '`3_Cabo_hacha`', '`3_Cabo_hacha`', 3, -1, FALSE, '`3_Cabo_hacha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Cabo_hacha->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Cabo_hacha'] = &$this->_3_Cabo_hacha;

		// 3_Funda_machete
		$this->_3_Funda_machete = new cField('inventario', 'inventario', 'x__3_Funda_machete', '3_Funda_machete', '`3_Funda_machete`', '`3_Funda_machete`', 3, -1, FALSE, '`3_Funda_machete`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Funda_machete->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Funda_machete'] = &$this->_3_Funda_machete;

		// 3_Glifosato_4lt
		$this->_3_Glifosato_4lt = new cField('inventario', 'inventario', 'x__3_Glifosato_4lt', '3_Glifosato_4lt', '`3_Glifosato_4lt`', '`3_Glifosato_4lt`', 3, -1, FALSE, '`3_Glifosato_4lt`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Glifosato_4lt->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Glifosato_4lt'] = &$this->_3_Glifosato_4lt;

		// 3_Hacha
		$this->_3_Hacha = new cField('inventario', 'inventario', 'x__3_Hacha', '3_Hacha', '`3_Hacha`', '`3_Hacha`', 3, -1, FALSE, '`3_Hacha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Hacha->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Hacha'] = &$this->_3_Hacha;

		// 3_Lima_12_uni
		$this->_3_Lima_12_uni = new cField('inventario', 'inventario', 'x__3_Lima_12_uni', '3_Lima_12_uni', '`3_Lima_12_uni`', '`3_Lima_12_uni`', 3, -1, FALSE, '`3_Lima_12_uni`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Lima_12_uni->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Lima_12_uni'] = &$this->_3_Lima_12_uni;

		// 3_Llave_mixta
		$this->_3_Llave_mixta = new cField('inventario', 'inventario', 'x__3_Llave_mixta', '3_Llave_mixta', '`3_Llave_mixta`', '`3_Llave_mixta`', 3, -1, FALSE, '`3_Llave_mixta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Llave_mixta->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Llave_mixta'] = &$this->_3_Llave_mixta;

		// 3_Machete
		$this->_3_Machete = new cField('inventario', 'inventario', 'x__3_Machete', '3_Machete', '`3_Machete`', '`3_Machete`', 3, -1, FALSE, '`3_Machete`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Machete->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Machete'] = &$this->_3_Machete;

		// 3_Gafa_traslucida
		$this->_3_Gafa_traslucida = new cField('inventario', 'inventario', 'x__3_Gafa_traslucida', '3_Gafa_traslucida', '`3_Gafa_traslucida`', '`3_Gafa_traslucida`', 3, -1, FALSE, '`3_Gafa_traslucida`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Gafa_traslucida->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Gafa_traslucida'] = &$this->_3_Gafa_traslucida;

		// 3_Motosierra
		$this->_3_Motosierra = new cField('inventario', 'inventario', 'x__3_Motosierra', '3_Motosierra', '`3_Motosierra`', '`3_Motosierra`', 3, -1, FALSE, '`3_Motosierra`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Motosierra->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Motosierra'] = &$this->_3_Motosierra;

		// 3_Palin
		$this->_3_Palin = new cField('inventario', 'inventario', 'x__3_Palin', '3_Palin', '`3_Palin`', '`3_Palin`', 3, -1, FALSE, '`3_Palin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Palin->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Palin'] = &$this->_3_Palin;

		// 3_Tubo_galvanizado
		$this->_3_Tubo_galvanizado = new cField('inventario', 'inventario', 'x__3_Tubo_galvanizado', '3_Tubo_galvanizado', '`3_Tubo_galvanizado`', '`3_Tubo_galvanizado`', 3, -1, FALSE, '`3_Tubo_galvanizado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->_3_Tubo_galvanizado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['3_Tubo_galvanizado'] = &$this->_3_Tubo_galvanizado;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`inventario`";
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
	var $UpdateTable = "`inventario`";

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
			return "inventariolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "inventariolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("inventarioview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("inventarioview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "inventarioadd.php?" . $this->UrlParm($parm);
		else
			return "inventarioadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("inventarioedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("inventarioadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("inventariodelete.php", $this->UrlParm());
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
		$this->llave->setDbValue($rs->fields('llave'));
		$this->USUARIO->setDbValue($rs->fields('USUARIO'));
		$this->Cargo_gme->setDbValue($rs->fields('Cargo_gme'));
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->NOM_PE->setDbValue($rs->fields('NOM_PE'));
		$this->Otro_PE->setDbValue($rs->fields('Otro_PE'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->AD1O->setDbValue($rs->fields('AÑO'));
		$this->FASE->setDbValue($rs->fields('FASE'));
		$this->F_Sincron->setDbValue($rs->fields('F_Sincron'));
		$this->FECHA_INV->setDbValue($rs->fields('FECHA_INV'));
		$this->TIPO_INV->setDbValue($rs->fields('TIPO_INV'));
		$this->NOM_CAPATAZ->setDbValue($rs->fields('NOM_CAPATAZ'));
		$this->Otro_NOM_CAPAT->setDbValue($rs->fields('Otro_NOM_CAPAT'));
		$this->Otro_CC_CAPAT->setDbValue($rs->fields('Otro_CC_CAPAT'));
		$this->NOM_LUGAR->setDbValue($rs->fields('NOM_LUGAR'));
		$this->Cocina->setDbValue($rs->fields('Cocina'));
		$this->_1_Abrelatas->setDbValue($rs->fields('1_Abrelatas'));
		$this->_1_Balde->setDbValue($rs->fields('1_Balde'));
		$this->_1_Arrocero_50->setDbValue($rs->fields('1_Arrocero_50'));
		$this->_1_Arrocero_44->setDbValue($rs->fields('1_Arrocero_44'));
		$this->_1_Chocolatera->setDbValue($rs->fields('1_Chocolatera'));
		$this->_1_Colador->setDbValue($rs->fields('1_Colador'));
		$this->_1_Cucharon_sopa->setDbValue($rs->fields('1_Cucharon_sopa'));
		$this->_1_Cucharon_arroz->setDbValue($rs->fields('1_Cucharon_arroz'));
		$this->_1_Cuchillo->setDbValue($rs->fields('1_Cuchillo'));
		$this->_1_Embudo->setDbValue($rs->fields('1_Embudo'));
		$this->_1_Espumera->setDbValue($rs->fields('1_Espumera'));
		$this->_1_Estufa->setDbValue($rs->fields('1_Estufa'));
		$this->_1_Cuchara_sopa->setDbValue($rs->fields('1_Cuchara_sopa'));
		$this->_1_Recipiente->setDbValue($rs->fields('1_Recipiente'));
		$this->_1_Kit_Repue_estufa->setDbValue($rs->fields('1_Kit_Repue_estufa'));
		$this->_1_Molinillo->setDbValue($rs->fields('1_Molinillo'));
		$this->_1_Olla_36->setDbValue($rs->fields('1_Olla_36'));
		$this->_1_Olla_40->setDbValue($rs->fields('1_Olla_40'));
		$this->_1_Paila_32->setDbValue($rs->fields('1_Paila_32'));
		$this->_1_Paila_36_37->setDbValue($rs->fields('1_Paila_36_37'));
		$this->Camping->setDbValue($rs->fields('Camping'));
		$this->_2_Aislante->setDbValue($rs->fields('2_Aislante'));
		$this->_2_Carpa_hamaca->setDbValue($rs->fields('2_Carpa_hamaca'));
		$this->_2_Carpa_rancho->setDbValue($rs->fields('2_Carpa_rancho'));
		$this->_2_Fibra_rollo->setDbValue($rs->fields('2_Fibra_rollo'));
		$this->_2_CAL->setDbValue($rs->fields('2_CAL'));
		$this->_2_Linterna->setDbValue($rs->fields('2_Linterna'));
		$this->_2_Botiquin->setDbValue($rs->fields('2_Botiquin'));
		$this->_2_Mascara_filtro->setDbValue($rs->fields('2_Mascara_filtro'));
		$this->_2_Pimpina->setDbValue($rs->fields('2_Pimpina'));
		$this->_2_SleepingA0->setDbValue($rs->fields('2_Sleeping '));
		$this->_2_Plastico_negro->setDbValue($rs->fields('2_Plastico_negro'));
		$this->_2_Tula_tropa->setDbValue($rs->fields('2_Tula_tropa'));
		$this->_2_Camilla->setDbValue($rs->fields('2_Camilla'));
		$this->Herramientas->setDbValue($rs->fields('Herramientas'));
		$this->_3_Abrazadera->setDbValue($rs->fields('3_Abrazadera'));
		$this->_3_Aspersora->setDbValue($rs->fields('3_Aspersora'));
		$this->_3_Cabo_hacha->setDbValue($rs->fields('3_Cabo_hacha'));
		$this->_3_Funda_machete->setDbValue($rs->fields('3_Funda_machete'));
		$this->_3_Glifosato_4lt->setDbValue($rs->fields('3_Glifosato_4lt'));
		$this->_3_Hacha->setDbValue($rs->fields('3_Hacha'));
		$this->_3_Lima_12_uni->setDbValue($rs->fields('3_Lima_12_uni'));
		$this->_3_Llave_mixta->setDbValue($rs->fields('3_Llave_mixta'));
		$this->_3_Machete->setDbValue($rs->fields('3_Machete'));
		$this->_3_Gafa_traslucida->setDbValue($rs->fields('3_Gafa_traslucida'));
		$this->_3_Motosierra->setDbValue($rs->fields('3_Motosierra'));
		$this->_3_Palin->setDbValue($rs->fields('3_Palin'));
		$this->_3_Tubo_galvanizado->setDbValue($rs->fields('3_Tubo_galvanizado'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// llave
		// USUARIO
		// Cargo_gme
		// DIA
		// MES
		// NOM_PE
		// Otro_PE
		// OBSERVACION
		// AÑO
		// FASE
		// F_Sincron
		// FECHA_INV
		// TIPO_INV
		// NOM_CAPATAZ
		// Otro_NOM_CAPAT
		// Otro_CC_CAPAT
		// NOM_LUGAR
		// Cocina
		// 1_Abrelatas
		// 1_Balde
		// 1_Arrocero_50
		// 1_Arrocero_44
		// 1_Chocolatera
		// 1_Colador
		// 1_Cucharon_sopa
		// 1_Cucharon_arroz
		// 1_Cuchillo
		// 1_Embudo
		// 1_Espumera
		// 1_Estufa
		// 1_Cuchara_sopa
		// 1_Recipiente
		// 1_Kit_Repue_estufa
		// 1_Molinillo
		// 1_Olla_36
		// 1_Olla_40
		// 1_Paila_32
		// 1_Paila_36_37
		// Camping
		// 2_Aislante
		// 2_Carpa_hamaca
		// 2_Carpa_rancho
		// 2_Fibra_rollo
		// 2_CAL
		// 2_Linterna
		// 2_Botiquin
		// 2_Mascara_filtro
		// 2_Pimpina
		// 2_Sleeping 
		// 2_Plastico_negro
		// 2_Tula_tropa
		// 2_Camilla
		// Herramientas
		// 3_Abrazadera
		// 3_Aspersora
		// 3_Cabo_hacha
		// 3_Funda_machete
		// 3_Glifosato_4lt
		// 3_Hacha
		// 3_Lima_12_uni
		// 3_Llave_mixta
		// 3_Machete
		// 3_Gafa_traslucida
		// 3_Motosierra
		// 3_Palin
		// 3_Tubo_galvanizado
		// llave

		$this->llave->ViewValue = $this->llave->CurrentValue;
		$this->llave->ViewCustomAttributes = "";

		// USUARIO
		$this->USUARIO->ViewValue = $this->USUARIO->CurrentValue;
		$this->USUARIO->ViewCustomAttributes = "";

		// Cargo_gme
		$this->Cargo_gme->ViewValue = $this->Cargo_gme->CurrentValue;
		$this->Cargo_gme->ViewCustomAttributes = "";

		// DIA
		$this->DIA->ViewValue = $this->DIA->CurrentValue;
		$this->DIA->ViewCustomAttributes = "";

		// MES
		$this->MES->ViewValue = $this->MES->CurrentValue;
		$this->MES->ViewCustomAttributes = "";

		// NOM_PE
		$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
		$this->NOM_PE->ViewCustomAttributes = "";

		// Otro_PE
		$this->Otro_PE->ViewValue = $this->Otro_PE->CurrentValue;
		$this->Otro_PE->ViewCustomAttributes = "";

		// OBSERVACION
		$this->OBSERVACION->ViewValue = $this->OBSERVACION->CurrentValue;
		$this->OBSERVACION->ViewCustomAttributes = "";

		// AÑO
		$this->AD1O->ViewValue = $this->AD1O->CurrentValue;
		$this->AD1O->ViewCustomAttributes = "";

		// FASE
		$this->FASE->ViewValue = $this->FASE->CurrentValue;
		$this->FASE->ViewCustomAttributes = "";

		// F_Sincron
		$this->F_Sincron->ViewValue = $this->F_Sincron->CurrentValue;
		$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 7);
		$this->F_Sincron->ViewCustomAttributes = "";

		// FECHA_INV
		$this->FECHA_INV->ViewValue = $this->FECHA_INV->CurrentValue;
		$this->FECHA_INV->ViewCustomAttributes = "";

		// TIPO_INV
		$this->TIPO_INV->ViewValue = $this->TIPO_INV->CurrentValue;
		$this->TIPO_INV->ViewCustomAttributes = "";

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ->ViewValue = $this->NOM_CAPATAZ->CurrentValue;
		$this->NOM_CAPATAZ->ViewCustomAttributes = "";

		// Otro_NOM_CAPAT
		$this->Otro_NOM_CAPAT->ViewValue = $this->Otro_NOM_CAPAT->CurrentValue;
		$this->Otro_NOM_CAPAT->ViewCustomAttributes = "";

		// Otro_CC_CAPAT
		$this->Otro_CC_CAPAT->ViewValue = $this->Otro_CC_CAPAT->CurrentValue;
		$this->Otro_CC_CAPAT->ViewCustomAttributes = "";

		// NOM_LUGAR
		$this->NOM_LUGAR->ViewValue = $this->NOM_LUGAR->CurrentValue;
		$this->NOM_LUGAR->ViewCustomAttributes = "";

		// Cocina
		$this->Cocina->ViewValue = $this->Cocina->CurrentValue;
		$this->Cocina->ViewCustomAttributes = "";

		// 1_Abrelatas
		$this->_1_Abrelatas->ViewValue = $this->_1_Abrelatas->CurrentValue;
		$this->_1_Abrelatas->ViewCustomAttributes = "";

		// 1_Balde
		$this->_1_Balde->ViewValue = $this->_1_Balde->CurrentValue;
		$this->_1_Balde->ViewCustomAttributes = "";

		// 1_Arrocero_50
		$this->_1_Arrocero_50->ViewValue = $this->_1_Arrocero_50->CurrentValue;
		$this->_1_Arrocero_50->ViewCustomAttributes = "";

		// 1_Arrocero_44
		$this->_1_Arrocero_44->ViewValue = $this->_1_Arrocero_44->CurrentValue;
		$this->_1_Arrocero_44->ViewCustomAttributes = "";

		// 1_Chocolatera
		$this->_1_Chocolatera->ViewValue = $this->_1_Chocolatera->CurrentValue;
		$this->_1_Chocolatera->ViewCustomAttributes = "";

		// 1_Colador
		$this->_1_Colador->ViewValue = $this->_1_Colador->CurrentValue;
		$this->_1_Colador->ViewCustomAttributes = "";

		// 1_Cucharon_sopa
		$this->_1_Cucharon_sopa->ViewValue = $this->_1_Cucharon_sopa->CurrentValue;
		$this->_1_Cucharon_sopa->ViewCustomAttributes = "";

		// 1_Cucharon_arroz
		$this->_1_Cucharon_arroz->ViewValue = $this->_1_Cucharon_arroz->CurrentValue;
		$this->_1_Cucharon_arroz->ViewCustomAttributes = "";

		// 1_Cuchillo
		$this->_1_Cuchillo->ViewValue = $this->_1_Cuchillo->CurrentValue;
		$this->_1_Cuchillo->ViewCustomAttributes = "";

		// 1_Embudo
		$this->_1_Embudo->ViewValue = $this->_1_Embudo->CurrentValue;
		$this->_1_Embudo->ViewCustomAttributes = "";

		// 1_Espumera
		$this->_1_Espumera->ViewValue = $this->_1_Espumera->CurrentValue;
		$this->_1_Espumera->ViewCustomAttributes = "";

		// 1_Estufa
		$this->_1_Estufa->ViewValue = $this->_1_Estufa->CurrentValue;
		$this->_1_Estufa->ViewCustomAttributes = "";

		// 1_Cuchara_sopa
		$this->_1_Cuchara_sopa->ViewValue = $this->_1_Cuchara_sopa->CurrentValue;
		$this->_1_Cuchara_sopa->ViewCustomAttributes = "";

		// 1_Recipiente
		$this->_1_Recipiente->ViewValue = $this->_1_Recipiente->CurrentValue;
		$this->_1_Recipiente->ViewCustomAttributes = "";

		// 1_Kit_Repue_estufa
		$this->_1_Kit_Repue_estufa->ViewValue = $this->_1_Kit_Repue_estufa->CurrentValue;
		$this->_1_Kit_Repue_estufa->ViewCustomAttributes = "";

		// 1_Molinillo
		$this->_1_Molinillo->ViewValue = $this->_1_Molinillo->CurrentValue;
		$this->_1_Molinillo->ViewCustomAttributes = "";

		// 1_Olla_36
		$this->_1_Olla_36->ViewValue = $this->_1_Olla_36->CurrentValue;
		$this->_1_Olla_36->ViewCustomAttributes = "";

		// 1_Olla_40
		$this->_1_Olla_40->ViewValue = $this->_1_Olla_40->CurrentValue;
		$this->_1_Olla_40->ViewCustomAttributes = "";

		// 1_Paila_32
		$this->_1_Paila_32->ViewValue = $this->_1_Paila_32->CurrentValue;
		$this->_1_Paila_32->ViewCustomAttributes = "";

		// 1_Paila_36_37
		$this->_1_Paila_36_37->ViewValue = $this->_1_Paila_36_37->CurrentValue;
		$this->_1_Paila_36_37->ViewCustomAttributes = "";

		// Camping
		$this->Camping->ViewValue = $this->Camping->CurrentValue;
		$this->Camping->ViewCustomAttributes = "";

		// 2_Aislante
		$this->_2_Aislante->ViewValue = $this->_2_Aislante->CurrentValue;
		$this->_2_Aislante->ViewCustomAttributes = "";

		// 2_Carpa_hamaca
		$this->_2_Carpa_hamaca->ViewValue = $this->_2_Carpa_hamaca->CurrentValue;
		$this->_2_Carpa_hamaca->ViewCustomAttributes = "";

		// 2_Carpa_rancho
		$this->_2_Carpa_rancho->ViewValue = $this->_2_Carpa_rancho->CurrentValue;
		$this->_2_Carpa_rancho->ViewCustomAttributes = "";

		// 2_Fibra_rollo
		$this->_2_Fibra_rollo->ViewValue = $this->_2_Fibra_rollo->CurrentValue;
		$this->_2_Fibra_rollo->ViewCustomAttributes = "";

		// 2_CAL
		$this->_2_CAL->ViewValue = $this->_2_CAL->CurrentValue;
		$this->_2_CAL->ViewCustomAttributes = "";

		// 2_Linterna
		$this->_2_Linterna->ViewValue = $this->_2_Linterna->CurrentValue;
		$this->_2_Linterna->ViewCustomAttributes = "";

		// 2_Botiquin
		$this->_2_Botiquin->ViewValue = $this->_2_Botiquin->CurrentValue;
		$this->_2_Botiquin->ViewCustomAttributes = "";

		// 2_Mascara_filtro
		$this->_2_Mascara_filtro->ViewValue = $this->_2_Mascara_filtro->CurrentValue;
		$this->_2_Mascara_filtro->ViewCustomAttributes = "";

		// 2_Pimpina
		$this->_2_Pimpina->ViewValue = $this->_2_Pimpina->CurrentValue;
		$this->_2_Pimpina->ViewCustomAttributes = "";

		// 2_Sleeping 
		$this->_2_SleepingA0->ViewValue = $this->_2_SleepingA0->CurrentValue;
		$this->_2_SleepingA0->ViewCustomAttributes = "";

		// 2_Plastico_negro
		$this->_2_Plastico_negro->ViewValue = $this->_2_Plastico_negro->CurrentValue;
		$this->_2_Plastico_negro->ViewCustomAttributes = "";

		// 2_Tula_tropa
		$this->_2_Tula_tropa->ViewValue = $this->_2_Tula_tropa->CurrentValue;
		$this->_2_Tula_tropa->ViewCustomAttributes = "";

		// 2_Camilla
		$this->_2_Camilla->ViewValue = $this->_2_Camilla->CurrentValue;
		$this->_2_Camilla->ViewCustomAttributes = "";

		// Herramientas
		$this->Herramientas->ViewValue = $this->Herramientas->CurrentValue;
		$this->Herramientas->ViewCustomAttributes = "";

		// 3_Abrazadera
		$this->_3_Abrazadera->ViewValue = $this->_3_Abrazadera->CurrentValue;
		$this->_3_Abrazadera->ViewCustomAttributes = "";

		// 3_Aspersora
		$this->_3_Aspersora->ViewValue = $this->_3_Aspersora->CurrentValue;
		$this->_3_Aspersora->ViewCustomAttributes = "";

		// 3_Cabo_hacha
		$this->_3_Cabo_hacha->ViewValue = $this->_3_Cabo_hacha->CurrentValue;
		$this->_3_Cabo_hacha->ViewCustomAttributes = "";

		// 3_Funda_machete
		$this->_3_Funda_machete->ViewValue = $this->_3_Funda_machete->CurrentValue;
		$this->_3_Funda_machete->ViewCustomAttributes = "";

		// 3_Glifosato_4lt
		$this->_3_Glifosato_4lt->ViewValue = $this->_3_Glifosato_4lt->CurrentValue;
		$this->_3_Glifosato_4lt->ViewCustomAttributes = "";

		// 3_Hacha
		$this->_3_Hacha->ViewValue = $this->_3_Hacha->CurrentValue;
		$this->_3_Hacha->ViewCustomAttributes = "";

		// 3_Lima_12_uni
		$this->_3_Lima_12_uni->ViewValue = $this->_3_Lima_12_uni->CurrentValue;
		$this->_3_Lima_12_uni->ViewCustomAttributes = "";

		// 3_Llave_mixta
		$this->_3_Llave_mixta->ViewValue = $this->_3_Llave_mixta->CurrentValue;
		$this->_3_Llave_mixta->ViewCustomAttributes = "";

		// 3_Machete
		$this->_3_Machete->ViewValue = $this->_3_Machete->CurrentValue;
		$this->_3_Machete->ViewCustomAttributes = "";

		// 3_Gafa_traslucida
		$this->_3_Gafa_traslucida->ViewValue = $this->_3_Gafa_traslucida->CurrentValue;
		$this->_3_Gafa_traslucida->ViewCustomAttributes = "";

		// 3_Motosierra
		$this->_3_Motosierra->ViewValue = $this->_3_Motosierra->CurrentValue;
		$this->_3_Motosierra->ViewCustomAttributes = "";

		// 3_Palin
		$this->_3_Palin->ViewValue = $this->_3_Palin->CurrentValue;
		$this->_3_Palin->ViewCustomAttributes = "";

		// 3_Tubo_galvanizado
		$this->_3_Tubo_galvanizado->ViewValue = $this->_3_Tubo_galvanizado->CurrentValue;
		$this->_3_Tubo_galvanizado->ViewCustomAttributes = "";

		// llave
		$this->llave->LinkCustomAttributes = "";
		$this->llave->HrefValue = "";
		$this->llave->TooltipValue = "";

		// USUARIO
		$this->USUARIO->LinkCustomAttributes = "";
		$this->USUARIO->HrefValue = "";
		$this->USUARIO->TooltipValue = "";

		// Cargo_gme
		$this->Cargo_gme->LinkCustomAttributes = "";
		$this->Cargo_gme->HrefValue = "";
		$this->Cargo_gme->TooltipValue = "";

		// DIA
		$this->DIA->LinkCustomAttributes = "";
		$this->DIA->HrefValue = "";
		$this->DIA->TooltipValue = "";

		// MES
		$this->MES->LinkCustomAttributes = "";
		$this->MES->HrefValue = "";
		$this->MES->TooltipValue = "";

		// NOM_PE
		$this->NOM_PE->LinkCustomAttributes = "";
		$this->NOM_PE->HrefValue = "";
		$this->NOM_PE->TooltipValue = "";

		// Otro_PE
		$this->Otro_PE->LinkCustomAttributes = "";
		$this->Otro_PE->HrefValue = "";
		$this->Otro_PE->TooltipValue = "";

		// OBSERVACION
		$this->OBSERVACION->LinkCustomAttributes = "";
		$this->OBSERVACION->HrefValue = "";
		$this->OBSERVACION->TooltipValue = "";

		// AÑO
		$this->AD1O->LinkCustomAttributes = "";
		$this->AD1O->HrefValue = "";
		$this->AD1O->TooltipValue = "";

		// FASE
		$this->FASE->LinkCustomAttributes = "";
		$this->FASE->HrefValue = "";
		$this->FASE->TooltipValue = "";

		// F_Sincron
		$this->F_Sincron->LinkCustomAttributes = "";
		$this->F_Sincron->HrefValue = "";
		$this->F_Sincron->TooltipValue = "";

		// FECHA_INV
		$this->FECHA_INV->LinkCustomAttributes = "";
		$this->FECHA_INV->HrefValue = "";
		$this->FECHA_INV->TooltipValue = "";

		// TIPO_INV
		$this->TIPO_INV->LinkCustomAttributes = "";
		$this->TIPO_INV->HrefValue = "";
		$this->TIPO_INV->TooltipValue = "";

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ->LinkCustomAttributes = "";
		$this->NOM_CAPATAZ->HrefValue = "";
		$this->NOM_CAPATAZ->TooltipValue = "";

		// Otro_NOM_CAPAT
		$this->Otro_NOM_CAPAT->LinkCustomAttributes = "";
		$this->Otro_NOM_CAPAT->HrefValue = "";
		$this->Otro_NOM_CAPAT->TooltipValue = "";

		// Otro_CC_CAPAT
		$this->Otro_CC_CAPAT->LinkCustomAttributes = "";
		$this->Otro_CC_CAPAT->HrefValue = "";
		$this->Otro_CC_CAPAT->TooltipValue = "";

		// NOM_LUGAR
		$this->NOM_LUGAR->LinkCustomAttributes = "";
		$this->NOM_LUGAR->HrefValue = "";
		$this->NOM_LUGAR->TooltipValue = "";

		// Cocina
		$this->Cocina->LinkCustomAttributes = "";
		$this->Cocina->HrefValue = "";
		$this->Cocina->TooltipValue = "";

		// 1_Abrelatas
		$this->_1_Abrelatas->LinkCustomAttributes = "";
		$this->_1_Abrelatas->HrefValue = "";
		$this->_1_Abrelatas->TooltipValue = "";

		// 1_Balde
		$this->_1_Balde->LinkCustomAttributes = "";
		$this->_1_Balde->HrefValue = "";
		$this->_1_Balde->TooltipValue = "";

		// 1_Arrocero_50
		$this->_1_Arrocero_50->LinkCustomAttributes = "";
		$this->_1_Arrocero_50->HrefValue = "";
		$this->_1_Arrocero_50->TooltipValue = "";

		// 1_Arrocero_44
		$this->_1_Arrocero_44->LinkCustomAttributes = "";
		$this->_1_Arrocero_44->HrefValue = "";
		$this->_1_Arrocero_44->TooltipValue = "";

		// 1_Chocolatera
		$this->_1_Chocolatera->LinkCustomAttributes = "";
		$this->_1_Chocolatera->HrefValue = "";
		$this->_1_Chocolatera->TooltipValue = "";

		// 1_Colador
		$this->_1_Colador->LinkCustomAttributes = "";
		$this->_1_Colador->HrefValue = "";
		$this->_1_Colador->TooltipValue = "";

		// 1_Cucharon_sopa
		$this->_1_Cucharon_sopa->LinkCustomAttributes = "";
		$this->_1_Cucharon_sopa->HrefValue = "";
		$this->_1_Cucharon_sopa->TooltipValue = "";

		// 1_Cucharon_arroz
		$this->_1_Cucharon_arroz->LinkCustomAttributes = "";
		$this->_1_Cucharon_arroz->HrefValue = "";
		$this->_1_Cucharon_arroz->TooltipValue = "";

		// 1_Cuchillo
		$this->_1_Cuchillo->LinkCustomAttributes = "";
		$this->_1_Cuchillo->HrefValue = "";
		$this->_1_Cuchillo->TooltipValue = "";

		// 1_Embudo
		$this->_1_Embudo->LinkCustomAttributes = "";
		$this->_1_Embudo->HrefValue = "";
		$this->_1_Embudo->TooltipValue = "";

		// 1_Espumera
		$this->_1_Espumera->LinkCustomAttributes = "";
		$this->_1_Espumera->HrefValue = "";
		$this->_1_Espumera->TooltipValue = "";

		// 1_Estufa
		$this->_1_Estufa->LinkCustomAttributes = "";
		$this->_1_Estufa->HrefValue = "";
		$this->_1_Estufa->TooltipValue = "";

		// 1_Cuchara_sopa
		$this->_1_Cuchara_sopa->LinkCustomAttributes = "";
		$this->_1_Cuchara_sopa->HrefValue = "";
		$this->_1_Cuchara_sopa->TooltipValue = "";

		// 1_Recipiente
		$this->_1_Recipiente->LinkCustomAttributes = "";
		$this->_1_Recipiente->HrefValue = "";
		$this->_1_Recipiente->TooltipValue = "";

		// 1_Kit_Repue_estufa
		$this->_1_Kit_Repue_estufa->LinkCustomAttributes = "";
		$this->_1_Kit_Repue_estufa->HrefValue = "";
		$this->_1_Kit_Repue_estufa->TooltipValue = "";

		// 1_Molinillo
		$this->_1_Molinillo->LinkCustomAttributes = "";
		$this->_1_Molinillo->HrefValue = "";
		$this->_1_Molinillo->TooltipValue = "";

		// 1_Olla_36
		$this->_1_Olla_36->LinkCustomAttributes = "";
		$this->_1_Olla_36->HrefValue = "";
		$this->_1_Olla_36->TooltipValue = "";

		// 1_Olla_40
		$this->_1_Olla_40->LinkCustomAttributes = "";
		$this->_1_Olla_40->HrefValue = "";
		$this->_1_Olla_40->TooltipValue = "";

		// 1_Paila_32
		$this->_1_Paila_32->LinkCustomAttributes = "";
		$this->_1_Paila_32->HrefValue = "";
		$this->_1_Paila_32->TooltipValue = "";

		// 1_Paila_36_37
		$this->_1_Paila_36_37->LinkCustomAttributes = "";
		$this->_1_Paila_36_37->HrefValue = "";
		$this->_1_Paila_36_37->TooltipValue = "";

		// Camping
		$this->Camping->LinkCustomAttributes = "";
		$this->Camping->HrefValue = "";
		$this->Camping->TooltipValue = "";

		// 2_Aislante
		$this->_2_Aislante->LinkCustomAttributes = "";
		$this->_2_Aislante->HrefValue = "";
		$this->_2_Aislante->TooltipValue = "";

		// 2_Carpa_hamaca
		$this->_2_Carpa_hamaca->LinkCustomAttributes = "";
		$this->_2_Carpa_hamaca->HrefValue = "";
		$this->_2_Carpa_hamaca->TooltipValue = "";

		// 2_Carpa_rancho
		$this->_2_Carpa_rancho->LinkCustomAttributes = "";
		$this->_2_Carpa_rancho->HrefValue = "";
		$this->_2_Carpa_rancho->TooltipValue = "";

		// 2_Fibra_rollo
		$this->_2_Fibra_rollo->LinkCustomAttributes = "";
		$this->_2_Fibra_rollo->HrefValue = "";
		$this->_2_Fibra_rollo->TooltipValue = "";

		// 2_CAL
		$this->_2_CAL->LinkCustomAttributes = "";
		$this->_2_CAL->HrefValue = "";
		$this->_2_CAL->TooltipValue = "";

		// 2_Linterna
		$this->_2_Linterna->LinkCustomAttributes = "";
		$this->_2_Linterna->HrefValue = "";
		$this->_2_Linterna->TooltipValue = "";

		// 2_Botiquin
		$this->_2_Botiquin->LinkCustomAttributes = "";
		$this->_2_Botiquin->HrefValue = "";
		$this->_2_Botiquin->TooltipValue = "";

		// 2_Mascara_filtro
		$this->_2_Mascara_filtro->LinkCustomAttributes = "";
		$this->_2_Mascara_filtro->HrefValue = "";
		$this->_2_Mascara_filtro->TooltipValue = "";

		// 2_Pimpina
		$this->_2_Pimpina->LinkCustomAttributes = "";
		$this->_2_Pimpina->HrefValue = "";
		$this->_2_Pimpina->TooltipValue = "";

		// 2_Sleeping 
		$this->_2_SleepingA0->LinkCustomAttributes = "";
		$this->_2_SleepingA0->HrefValue = "";
		$this->_2_SleepingA0->TooltipValue = "";

		// 2_Plastico_negro
		$this->_2_Plastico_negro->LinkCustomAttributes = "";
		$this->_2_Plastico_negro->HrefValue = "";
		$this->_2_Plastico_negro->TooltipValue = "";

		// 2_Tula_tropa
		$this->_2_Tula_tropa->LinkCustomAttributes = "";
		$this->_2_Tula_tropa->HrefValue = "";
		$this->_2_Tula_tropa->TooltipValue = "";

		// 2_Camilla
		$this->_2_Camilla->LinkCustomAttributes = "";
		$this->_2_Camilla->HrefValue = "";
		$this->_2_Camilla->TooltipValue = "";

		// Herramientas
		$this->Herramientas->LinkCustomAttributes = "";
		$this->Herramientas->HrefValue = "";
		$this->Herramientas->TooltipValue = "";

		// 3_Abrazadera
		$this->_3_Abrazadera->LinkCustomAttributes = "";
		$this->_3_Abrazadera->HrefValue = "";
		$this->_3_Abrazadera->TooltipValue = "";

		// 3_Aspersora
		$this->_3_Aspersora->LinkCustomAttributes = "";
		$this->_3_Aspersora->HrefValue = "";
		$this->_3_Aspersora->TooltipValue = "";

		// 3_Cabo_hacha
		$this->_3_Cabo_hacha->LinkCustomAttributes = "";
		$this->_3_Cabo_hacha->HrefValue = "";
		$this->_3_Cabo_hacha->TooltipValue = "";

		// 3_Funda_machete
		$this->_3_Funda_machete->LinkCustomAttributes = "";
		$this->_3_Funda_machete->HrefValue = "";
		$this->_3_Funda_machete->TooltipValue = "";

		// 3_Glifosato_4lt
		$this->_3_Glifosato_4lt->LinkCustomAttributes = "";
		$this->_3_Glifosato_4lt->HrefValue = "";
		$this->_3_Glifosato_4lt->TooltipValue = "";

		// 3_Hacha
		$this->_3_Hacha->LinkCustomAttributes = "";
		$this->_3_Hacha->HrefValue = "";
		$this->_3_Hacha->TooltipValue = "";

		// 3_Lima_12_uni
		$this->_3_Lima_12_uni->LinkCustomAttributes = "";
		$this->_3_Lima_12_uni->HrefValue = "";
		$this->_3_Lima_12_uni->TooltipValue = "";

		// 3_Llave_mixta
		$this->_3_Llave_mixta->LinkCustomAttributes = "";
		$this->_3_Llave_mixta->HrefValue = "";
		$this->_3_Llave_mixta->TooltipValue = "";

		// 3_Machete
		$this->_3_Machete->LinkCustomAttributes = "";
		$this->_3_Machete->HrefValue = "";
		$this->_3_Machete->TooltipValue = "";

		// 3_Gafa_traslucida
		$this->_3_Gafa_traslucida->LinkCustomAttributes = "";
		$this->_3_Gafa_traslucida->HrefValue = "";
		$this->_3_Gafa_traslucida->TooltipValue = "";

		// 3_Motosierra
		$this->_3_Motosierra->LinkCustomAttributes = "";
		$this->_3_Motosierra->HrefValue = "";
		$this->_3_Motosierra->TooltipValue = "";

		// 3_Palin
		$this->_3_Palin->LinkCustomAttributes = "";
		$this->_3_Palin->HrefValue = "";
		$this->_3_Palin->TooltipValue = "";

		// 3_Tubo_galvanizado
		$this->_3_Tubo_galvanizado->LinkCustomAttributes = "";
		$this->_3_Tubo_galvanizado->HrefValue = "";
		$this->_3_Tubo_galvanizado->TooltipValue = "";

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
		$this->llave->EditValue = ew_HtmlEncode($this->llave->CurrentValue);
		$this->llave->PlaceHolder = ew_RemoveHtml($this->llave->FldCaption());

		// USUARIO
		$this->USUARIO->EditAttrs["class"] = "form-control";
		$this->USUARIO->EditCustomAttributes = "";
		$this->USUARIO->EditValue = ew_HtmlEncode($this->USUARIO->CurrentValue);
		$this->USUARIO->PlaceHolder = ew_RemoveHtml($this->USUARIO->FldCaption());

		// Cargo_gme
		$this->Cargo_gme->EditAttrs["class"] = "form-control";
		$this->Cargo_gme->EditCustomAttributes = "";
		$this->Cargo_gme->EditValue = ew_HtmlEncode($this->Cargo_gme->CurrentValue);
		$this->Cargo_gme->PlaceHolder = ew_RemoveHtml($this->Cargo_gme->FldCaption());

		// DIA
		$this->DIA->EditAttrs["class"] = "form-control";
		$this->DIA->EditCustomAttributes = "";
		$this->DIA->EditValue = ew_HtmlEncode($this->DIA->CurrentValue);
		$this->DIA->PlaceHolder = ew_RemoveHtml($this->DIA->FldCaption());

		// MES
		$this->MES->EditAttrs["class"] = "form-control";
		$this->MES->EditCustomAttributes = "";
		$this->MES->EditValue = ew_HtmlEncode($this->MES->CurrentValue);
		$this->MES->PlaceHolder = ew_RemoveHtml($this->MES->FldCaption());

		// NOM_PE
		$this->NOM_PE->EditAttrs["class"] = "form-control";
		$this->NOM_PE->EditCustomAttributes = "";
		$this->NOM_PE->EditValue = ew_HtmlEncode($this->NOM_PE->CurrentValue);
		$this->NOM_PE->PlaceHolder = ew_RemoveHtml($this->NOM_PE->FldCaption());

		// Otro_PE
		$this->Otro_PE->EditAttrs["class"] = "form-control";
		$this->Otro_PE->EditCustomAttributes = "";
		$this->Otro_PE->EditValue = ew_HtmlEncode($this->Otro_PE->CurrentValue);
		$this->Otro_PE->PlaceHolder = ew_RemoveHtml($this->Otro_PE->FldCaption());

		// OBSERVACION
		$this->OBSERVACION->EditAttrs["class"] = "form-control";
		$this->OBSERVACION->EditCustomAttributes = "";
		$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->CurrentValue);
		$this->OBSERVACION->PlaceHolder = ew_RemoveHtml($this->OBSERVACION->FldCaption());

		// AÑO
		$this->AD1O->EditAttrs["class"] = "form-control";
		$this->AD1O->EditCustomAttributes = "";
		$this->AD1O->EditValue = ew_HtmlEncode($this->AD1O->CurrentValue);
		$this->AD1O->PlaceHolder = ew_RemoveHtml($this->AD1O->FldCaption());

		// FASE
		$this->FASE->EditAttrs["class"] = "form-control";
		$this->FASE->EditCustomAttributes = "";
		$this->FASE->EditValue = ew_HtmlEncode($this->FASE->CurrentValue);
		$this->FASE->PlaceHolder = ew_RemoveHtml($this->FASE->FldCaption());

		// F_Sincron
		$this->F_Sincron->EditAttrs["class"] = "form-control";
		$this->F_Sincron->EditCustomAttributes = "";
		$this->F_Sincron->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->F_Sincron->CurrentValue, 7));
		$this->F_Sincron->PlaceHolder = ew_RemoveHtml($this->F_Sincron->FldCaption());

		// FECHA_INV
		$this->FECHA_INV->EditAttrs["class"] = "form-control";
		$this->FECHA_INV->EditCustomAttributes = "";
		$this->FECHA_INV->EditValue = ew_HtmlEncode($this->FECHA_INV->CurrentValue);
		$this->FECHA_INV->PlaceHolder = ew_RemoveHtml($this->FECHA_INV->FldCaption());

		// TIPO_INV
		$this->TIPO_INV->EditAttrs["class"] = "form-control";
		$this->TIPO_INV->EditCustomAttributes = "";
		$this->TIPO_INV->EditValue = ew_HtmlEncode($this->TIPO_INV->CurrentValue);
		$this->TIPO_INV->PlaceHolder = ew_RemoveHtml($this->TIPO_INV->FldCaption());

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ->EditAttrs["class"] = "form-control";
		$this->NOM_CAPATAZ->EditCustomAttributes = "";
		$this->NOM_CAPATAZ->EditValue = ew_HtmlEncode($this->NOM_CAPATAZ->CurrentValue);
		$this->NOM_CAPATAZ->PlaceHolder = ew_RemoveHtml($this->NOM_CAPATAZ->FldCaption());

		// Otro_NOM_CAPAT
		$this->Otro_NOM_CAPAT->EditAttrs["class"] = "form-control";
		$this->Otro_NOM_CAPAT->EditCustomAttributes = "";
		$this->Otro_NOM_CAPAT->EditValue = ew_HtmlEncode($this->Otro_NOM_CAPAT->CurrentValue);
		$this->Otro_NOM_CAPAT->PlaceHolder = ew_RemoveHtml($this->Otro_NOM_CAPAT->FldCaption());

		// Otro_CC_CAPAT
		$this->Otro_CC_CAPAT->EditAttrs["class"] = "form-control";
		$this->Otro_CC_CAPAT->EditCustomAttributes = "";
		$this->Otro_CC_CAPAT->EditValue = ew_HtmlEncode($this->Otro_CC_CAPAT->CurrentValue);
		$this->Otro_CC_CAPAT->PlaceHolder = ew_RemoveHtml($this->Otro_CC_CAPAT->FldCaption());

		// NOM_LUGAR
		$this->NOM_LUGAR->EditAttrs["class"] = "form-control";
		$this->NOM_LUGAR->EditCustomAttributes = "";
		$this->NOM_LUGAR->EditValue = ew_HtmlEncode($this->NOM_LUGAR->CurrentValue);
		$this->NOM_LUGAR->PlaceHolder = ew_RemoveHtml($this->NOM_LUGAR->FldCaption());

		// Cocina
		$this->Cocina->EditAttrs["class"] = "form-control";
		$this->Cocina->EditCustomAttributes = "";
		$this->Cocina->EditValue = ew_HtmlEncode($this->Cocina->CurrentValue);
		$this->Cocina->PlaceHolder = ew_RemoveHtml($this->Cocina->FldCaption());
		if (strval($this->Cocina->EditValue) <> "" && is_numeric($this->Cocina->EditValue)) $this->Cocina->EditValue = ew_FormatNumber($this->Cocina->EditValue, -2, -1, -2, 0);

		// 1_Abrelatas
		$this->_1_Abrelatas->EditAttrs["class"] = "form-control";
		$this->_1_Abrelatas->EditCustomAttributes = "";
		$this->_1_Abrelatas->EditValue = ew_HtmlEncode($this->_1_Abrelatas->CurrentValue);
		$this->_1_Abrelatas->PlaceHolder = ew_RemoveHtml($this->_1_Abrelatas->FldCaption());

		// 1_Balde
		$this->_1_Balde->EditAttrs["class"] = "form-control";
		$this->_1_Balde->EditCustomAttributes = "";
		$this->_1_Balde->EditValue = ew_HtmlEncode($this->_1_Balde->CurrentValue);
		$this->_1_Balde->PlaceHolder = ew_RemoveHtml($this->_1_Balde->FldCaption());

		// 1_Arrocero_50
		$this->_1_Arrocero_50->EditAttrs["class"] = "form-control";
		$this->_1_Arrocero_50->EditCustomAttributes = "";
		$this->_1_Arrocero_50->EditValue = ew_HtmlEncode($this->_1_Arrocero_50->CurrentValue);
		$this->_1_Arrocero_50->PlaceHolder = ew_RemoveHtml($this->_1_Arrocero_50->FldCaption());

		// 1_Arrocero_44
		$this->_1_Arrocero_44->EditAttrs["class"] = "form-control";
		$this->_1_Arrocero_44->EditCustomAttributes = "";
		$this->_1_Arrocero_44->EditValue = ew_HtmlEncode($this->_1_Arrocero_44->CurrentValue);
		$this->_1_Arrocero_44->PlaceHolder = ew_RemoveHtml($this->_1_Arrocero_44->FldCaption());

		// 1_Chocolatera
		$this->_1_Chocolatera->EditAttrs["class"] = "form-control";
		$this->_1_Chocolatera->EditCustomAttributes = "";
		$this->_1_Chocolatera->EditValue = ew_HtmlEncode($this->_1_Chocolatera->CurrentValue);
		$this->_1_Chocolatera->PlaceHolder = ew_RemoveHtml($this->_1_Chocolatera->FldCaption());

		// 1_Colador
		$this->_1_Colador->EditAttrs["class"] = "form-control";
		$this->_1_Colador->EditCustomAttributes = "";
		$this->_1_Colador->EditValue = ew_HtmlEncode($this->_1_Colador->CurrentValue);
		$this->_1_Colador->PlaceHolder = ew_RemoveHtml($this->_1_Colador->FldCaption());

		// 1_Cucharon_sopa
		$this->_1_Cucharon_sopa->EditAttrs["class"] = "form-control";
		$this->_1_Cucharon_sopa->EditCustomAttributes = "";
		$this->_1_Cucharon_sopa->EditValue = ew_HtmlEncode($this->_1_Cucharon_sopa->CurrentValue);
		$this->_1_Cucharon_sopa->PlaceHolder = ew_RemoveHtml($this->_1_Cucharon_sopa->FldCaption());

		// 1_Cucharon_arroz
		$this->_1_Cucharon_arroz->EditAttrs["class"] = "form-control";
		$this->_1_Cucharon_arroz->EditCustomAttributes = "";
		$this->_1_Cucharon_arroz->EditValue = ew_HtmlEncode($this->_1_Cucharon_arroz->CurrentValue);
		$this->_1_Cucharon_arroz->PlaceHolder = ew_RemoveHtml($this->_1_Cucharon_arroz->FldCaption());

		// 1_Cuchillo
		$this->_1_Cuchillo->EditAttrs["class"] = "form-control";
		$this->_1_Cuchillo->EditCustomAttributes = "";
		$this->_1_Cuchillo->EditValue = ew_HtmlEncode($this->_1_Cuchillo->CurrentValue);
		$this->_1_Cuchillo->PlaceHolder = ew_RemoveHtml($this->_1_Cuchillo->FldCaption());

		// 1_Embudo
		$this->_1_Embudo->EditAttrs["class"] = "form-control";
		$this->_1_Embudo->EditCustomAttributes = "";
		$this->_1_Embudo->EditValue = ew_HtmlEncode($this->_1_Embudo->CurrentValue);
		$this->_1_Embudo->PlaceHolder = ew_RemoveHtml($this->_1_Embudo->FldCaption());

		// 1_Espumera
		$this->_1_Espumera->EditAttrs["class"] = "form-control";
		$this->_1_Espumera->EditCustomAttributes = "";
		$this->_1_Espumera->EditValue = ew_HtmlEncode($this->_1_Espumera->CurrentValue);
		$this->_1_Espumera->PlaceHolder = ew_RemoveHtml($this->_1_Espumera->FldCaption());

		// 1_Estufa
		$this->_1_Estufa->EditAttrs["class"] = "form-control";
		$this->_1_Estufa->EditCustomAttributes = "";
		$this->_1_Estufa->EditValue = ew_HtmlEncode($this->_1_Estufa->CurrentValue);
		$this->_1_Estufa->PlaceHolder = ew_RemoveHtml($this->_1_Estufa->FldCaption());

		// 1_Cuchara_sopa
		$this->_1_Cuchara_sopa->EditAttrs["class"] = "form-control";
		$this->_1_Cuchara_sopa->EditCustomAttributes = "";
		$this->_1_Cuchara_sopa->EditValue = ew_HtmlEncode($this->_1_Cuchara_sopa->CurrentValue);
		$this->_1_Cuchara_sopa->PlaceHolder = ew_RemoveHtml($this->_1_Cuchara_sopa->FldCaption());

		// 1_Recipiente
		$this->_1_Recipiente->EditAttrs["class"] = "form-control";
		$this->_1_Recipiente->EditCustomAttributes = "";
		$this->_1_Recipiente->EditValue = ew_HtmlEncode($this->_1_Recipiente->CurrentValue);
		$this->_1_Recipiente->PlaceHolder = ew_RemoveHtml($this->_1_Recipiente->FldCaption());

		// 1_Kit_Repue_estufa
		$this->_1_Kit_Repue_estufa->EditAttrs["class"] = "form-control";
		$this->_1_Kit_Repue_estufa->EditCustomAttributes = "";
		$this->_1_Kit_Repue_estufa->EditValue = ew_HtmlEncode($this->_1_Kit_Repue_estufa->CurrentValue);
		$this->_1_Kit_Repue_estufa->PlaceHolder = ew_RemoveHtml($this->_1_Kit_Repue_estufa->FldCaption());

		// 1_Molinillo
		$this->_1_Molinillo->EditAttrs["class"] = "form-control";
		$this->_1_Molinillo->EditCustomAttributes = "";
		$this->_1_Molinillo->EditValue = ew_HtmlEncode($this->_1_Molinillo->CurrentValue);
		$this->_1_Molinillo->PlaceHolder = ew_RemoveHtml($this->_1_Molinillo->FldCaption());

		// 1_Olla_36
		$this->_1_Olla_36->EditAttrs["class"] = "form-control";
		$this->_1_Olla_36->EditCustomAttributes = "";
		$this->_1_Olla_36->EditValue = ew_HtmlEncode($this->_1_Olla_36->CurrentValue);
		$this->_1_Olla_36->PlaceHolder = ew_RemoveHtml($this->_1_Olla_36->FldCaption());

		// 1_Olla_40
		$this->_1_Olla_40->EditAttrs["class"] = "form-control";
		$this->_1_Olla_40->EditCustomAttributes = "";
		$this->_1_Olla_40->EditValue = ew_HtmlEncode($this->_1_Olla_40->CurrentValue);
		$this->_1_Olla_40->PlaceHolder = ew_RemoveHtml($this->_1_Olla_40->FldCaption());

		// 1_Paila_32
		$this->_1_Paila_32->EditAttrs["class"] = "form-control";
		$this->_1_Paila_32->EditCustomAttributes = "";
		$this->_1_Paila_32->EditValue = ew_HtmlEncode($this->_1_Paila_32->CurrentValue);
		$this->_1_Paila_32->PlaceHolder = ew_RemoveHtml($this->_1_Paila_32->FldCaption());

		// 1_Paila_36_37
		$this->_1_Paila_36_37->EditAttrs["class"] = "form-control";
		$this->_1_Paila_36_37->EditCustomAttributes = "";
		$this->_1_Paila_36_37->EditValue = ew_HtmlEncode($this->_1_Paila_36_37->CurrentValue);
		$this->_1_Paila_36_37->PlaceHolder = ew_RemoveHtml($this->_1_Paila_36_37->FldCaption());

		// Camping
		$this->Camping->EditAttrs["class"] = "form-control";
		$this->Camping->EditCustomAttributes = "";
		$this->Camping->EditValue = ew_HtmlEncode($this->Camping->CurrentValue);
		$this->Camping->PlaceHolder = ew_RemoveHtml($this->Camping->FldCaption());
		if (strval($this->Camping->EditValue) <> "" && is_numeric($this->Camping->EditValue)) $this->Camping->EditValue = ew_FormatNumber($this->Camping->EditValue, -2, -1, -2, 0);

		// 2_Aislante
		$this->_2_Aislante->EditAttrs["class"] = "form-control";
		$this->_2_Aislante->EditCustomAttributes = "";
		$this->_2_Aislante->EditValue = ew_HtmlEncode($this->_2_Aislante->CurrentValue);
		$this->_2_Aislante->PlaceHolder = ew_RemoveHtml($this->_2_Aislante->FldCaption());

		// 2_Carpa_hamaca
		$this->_2_Carpa_hamaca->EditAttrs["class"] = "form-control";
		$this->_2_Carpa_hamaca->EditCustomAttributes = "";
		$this->_2_Carpa_hamaca->EditValue = ew_HtmlEncode($this->_2_Carpa_hamaca->CurrentValue);
		$this->_2_Carpa_hamaca->PlaceHolder = ew_RemoveHtml($this->_2_Carpa_hamaca->FldCaption());

		// 2_Carpa_rancho
		$this->_2_Carpa_rancho->EditAttrs["class"] = "form-control";
		$this->_2_Carpa_rancho->EditCustomAttributes = "";
		$this->_2_Carpa_rancho->EditValue = ew_HtmlEncode($this->_2_Carpa_rancho->CurrentValue);
		$this->_2_Carpa_rancho->PlaceHolder = ew_RemoveHtml($this->_2_Carpa_rancho->FldCaption());

		// 2_Fibra_rollo
		$this->_2_Fibra_rollo->EditAttrs["class"] = "form-control";
		$this->_2_Fibra_rollo->EditCustomAttributes = "";
		$this->_2_Fibra_rollo->EditValue = ew_HtmlEncode($this->_2_Fibra_rollo->CurrentValue);
		$this->_2_Fibra_rollo->PlaceHolder = ew_RemoveHtml($this->_2_Fibra_rollo->FldCaption());

		// 2_CAL
		$this->_2_CAL->EditAttrs["class"] = "form-control";
		$this->_2_CAL->EditCustomAttributes = "";
		$this->_2_CAL->EditValue = ew_HtmlEncode($this->_2_CAL->CurrentValue);
		$this->_2_CAL->PlaceHolder = ew_RemoveHtml($this->_2_CAL->FldCaption());

		// 2_Linterna
		$this->_2_Linterna->EditAttrs["class"] = "form-control";
		$this->_2_Linterna->EditCustomAttributes = "";
		$this->_2_Linterna->EditValue = ew_HtmlEncode($this->_2_Linterna->CurrentValue);
		$this->_2_Linterna->PlaceHolder = ew_RemoveHtml($this->_2_Linterna->FldCaption());

		// 2_Botiquin
		$this->_2_Botiquin->EditAttrs["class"] = "form-control";
		$this->_2_Botiquin->EditCustomAttributes = "";
		$this->_2_Botiquin->EditValue = ew_HtmlEncode($this->_2_Botiquin->CurrentValue);
		$this->_2_Botiquin->PlaceHolder = ew_RemoveHtml($this->_2_Botiquin->FldCaption());

		// 2_Mascara_filtro
		$this->_2_Mascara_filtro->EditAttrs["class"] = "form-control";
		$this->_2_Mascara_filtro->EditCustomAttributes = "";
		$this->_2_Mascara_filtro->EditValue = ew_HtmlEncode($this->_2_Mascara_filtro->CurrentValue);
		$this->_2_Mascara_filtro->PlaceHolder = ew_RemoveHtml($this->_2_Mascara_filtro->FldCaption());

		// 2_Pimpina
		$this->_2_Pimpina->EditAttrs["class"] = "form-control";
		$this->_2_Pimpina->EditCustomAttributes = "";
		$this->_2_Pimpina->EditValue = ew_HtmlEncode($this->_2_Pimpina->CurrentValue);
		$this->_2_Pimpina->PlaceHolder = ew_RemoveHtml($this->_2_Pimpina->FldCaption());

		// 2_Sleeping 
		$this->_2_SleepingA0->EditAttrs["class"] = "form-control";
		$this->_2_SleepingA0->EditCustomAttributes = "";
		$this->_2_SleepingA0->EditValue = ew_HtmlEncode($this->_2_SleepingA0->CurrentValue);
		$this->_2_SleepingA0->PlaceHolder = ew_RemoveHtml($this->_2_SleepingA0->FldCaption());

		// 2_Plastico_negro
		$this->_2_Plastico_negro->EditAttrs["class"] = "form-control";
		$this->_2_Plastico_negro->EditCustomAttributes = "";
		$this->_2_Plastico_negro->EditValue = ew_HtmlEncode($this->_2_Plastico_negro->CurrentValue);
		$this->_2_Plastico_negro->PlaceHolder = ew_RemoveHtml($this->_2_Plastico_negro->FldCaption());

		// 2_Tula_tropa
		$this->_2_Tula_tropa->EditAttrs["class"] = "form-control";
		$this->_2_Tula_tropa->EditCustomAttributes = "";
		$this->_2_Tula_tropa->EditValue = ew_HtmlEncode($this->_2_Tula_tropa->CurrentValue);
		$this->_2_Tula_tropa->PlaceHolder = ew_RemoveHtml($this->_2_Tula_tropa->FldCaption());

		// 2_Camilla
		$this->_2_Camilla->EditAttrs["class"] = "form-control";
		$this->_2_Camilla->EditCustomAttributes = "";
		$this->_2_Camilla->EditValue = ew_HtmlEncode($this->_2_Camilla->CurrentValue);
		$this->_2_Camilla->PlaceHolder = ew_RemoveHtml($this->_2_Camilla->FldCaption());

		// Herramientas
		$this->Herramientas->EditAttrs["class"] = "form-control";
		$this->Herramientas->EditCustomAttributes = "";
		$this->Herramientas->EditValue = ew_HtmlEncode($this->Herramientas->CurrentValue);
		$this->Herramientas->PlaceHolder = ew_RemoveHtml($this->Herramientas->FldCaption());
		if (strval($this->Herramientas->EditValue) <> "" && is_numeric($this->Herramientas->EditValue)) $this->Herramientas->EditValue = ew_FormatNumber($this->Herramientas->EditValue, -2, -1, -2, 0);

		// 3_Abrazadera
		$this->_3_Abrazadera->EditAttrs["class"] = "form-control";
		$this->_3_Abrazadera->EditCustomAttributes = "";
		$this->_3_Abrazadera->EditValue = ew_HtmlEncode($this->_3_Abrazadera->CurrentValue);
		$this->_3_Abrazadera->PlaceHolder = ew_RemoveHtml($this->_3_Abrazadera->FldCaption());

		// 3_Aspersora
		$this->_3_Aspersora->EditAttrs["class"] = "form-control";
		$this->_3_Aspersora->EditCustomAttributes = "";
		$this->_3_Aspersora->EditValue = ew_HtmlEncode($this->_3_Aspersora->CurrentValue);
		$this->_3_Aspersora->PlaceHolder = ew_RemoveHtml($this->_3_Aspersora->FldCaption());

		// 3_Cabo_hacha
		$this->_3_Cabo_hacha->EditAttrs["class"] = "form-control";
		$this->_3_Cabo_hacha->EditCustomAttributes = "";
		$this->_3_Cabo_hacha->EditValue = ew_HtmlEncode($this->_3_Cabo_hacha->CurrentValue);
		$this->_3_Cabo_hacha->PlaceHolder = ew_RemoveHtml($this->_3_Cabo_hacha->FldCaption());

		// 3_Funda_machete
		$this->_3_Funda_machete->EditAttrs["class"] = "form-control";
		$this->_3_Funda_machete->EditCustomAttributes = "";
		$this->_3_Funda_machete->EditValue = ew_HtmlEncode($this->_3_Funda_machete->CurrentValue);
		$this->_3_Funda_machete->PlaceHolder = ew_RemoveHtml($this->_3_Funda_machete->FldCaption());

		// 3_Glifosato_4lt
		$this->_3_Glifosato_4lt->EditAttrs["class"] = "form-control";
		$this->_3_Glifosato_4lt->EditCustomAttributes = "";
		$this->_3_Glifosato_4lt->EditValue = ew_HtmlEncode($this->_3_Glifosato_4lt->CurrentValue);
		$this->_3_Glifosato_4lt->PlaceHolder = ew_RemoveHtml($this->_3_Glifosato_4lt->FldCaption());

		// 3_Hacha
		$this->_3_Hacha->EditAttrs["class"] = "form-control";
		$this->_3_Hacha->EditCustomAttributes = "";
		$this->_3_Hacha->EditValue = ew_HtmlEncode($this->_3_Hacha->CurrentValue);
		$this->_3_Hacha->PlaceHolder = ew_RemoveHtml($this->_3_Hacha->FldCaption());

		// 3_Lima_12_uni
		$this->_3_Lima_12_uni->EditAttrs["class"] = "form-control";
		$this->_3_Lima_12_uni->EditCustomAttributes = "";
		$this->_3_Lima_12_uni->EditValue = ew_HtmlEncode($this->_3_Lima_12_uni->CurrentValue);
		$this->_3_Lima_12_uni->PlaceHolder = ew_RemoveHtml($this->_3_Lima_12_uni->FldCaption());

		// 3_Llave_mixta
		$this->_3_Llave_mixta->EditAttrs["class"] = "form-control";
		$this->_3_Llave_mixta->EditCustomAttributes = "";
		$this->_3_Llave_mixta->EditValue = ew_HtmlEncode($this->_3_Llave_mixta->CurrentValue);
		$this->_3_Llave_mixta->PlaceHolder = ew_RemoveHtml($this->_3_Llave_mixta->FldCaption());

		// 3_Machete
		$this->_3_Machete->EditAttrs["class"] = "form-control";
		$this->_3_Machete->EditCustomAttributes = "";
		$this->_3_Machete->EditValue = ew_HtmlEncode($this->_3_Machete->CurrentValue);
		$this->_3_Machete->PlaceHolder = ew_RemoveHtml($this->_3_Machete->FldCaption());

		// 3_Gafa_traslucida
		$this->_3_Gafa_traslucida->EditAttrs["class"] = "form-control";
		$this->_3_Gafa_traslucida->EditCustomAttributes = "";
		$this->_3_Gafa_traslucida->EditValue = ew_HtmlEncode($this->_3_Gafa_traslucida->CurrentValue);
		$this->_3_Gafa_traslucida->PlaceHolder = ew_RemoveHtml($this->_3_Gafa_traslucida->FldCaption());

		// 3_Motosierra
		$this->_3_Motosierra->EditAttrs["class"] = "form-control";
		$this->_3_Motosierra->EditCustomAttributes = "";
		$this->_3_Motosierra->EditValue = ew_HtmlEncode($this->_3_Motosierra->CurrentValue);
		$this->_3_Motosierra->PlaceHolder = ew_RemoveHtml($this->_3_Motosierra->FldCaption());

		// 3_Palin
		$this->_3_Palin->EditAttrs["class"] = "form-control";
		$this->_3_Palin->EditCustomAttributes = "";
		$this->_3_Palin->EditValue = ew_HtmlEncode($this->_3_Palin->CurrentValue);
		$this->_3_Palin->PlaceHolder = ew_RemoveHtml($this->_3_Palin->FldCaption());

		// 3_Tubo_galvanizado
		$this->_3_Tubo_galvanizado->EditAttrs["class"] = "form-control";
		$this->_3_Tubo_galvanizado->EditCustomAttributes = "";
		$this->_3_Tubo_galvanizado->EditValue = ew_HtmlEncode($this->_3_Tubo_galvanizado->CurrentValue);
		$this->_3_Tubo_galvanizado->PlaceHolder = ew_RemoveHtml($this->_3_Tubo_galvanizado->FldCaption());

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
					if ($this->USUARIO->Exportable) $Doc->ExportCaption($this->USUARIO);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_PE->Exportable) $Doc->ExportCaption($this->Otro_PE);
					if ($this->OBSERVACION->Exportable) $Doc->ExportCaption($this->OBSERVACION);
					if ($this->AD1O->Exportable) $Doc->ExportCaption($this->AD1O);
					if ($this->FASE->Exportable) $Doc->ExportCaption($this->FASE);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
					if ($this->FECHA_INV->Exportable) $Doc->ExportCaption($this->FECHA_INV);
					if ($this->TIPO_INV->Exportable) $Doc->ExportCaption($this->TIPO_INV);
					if ($this->NOM_CAPATAZ->Exportable) $Doc->ExportCaption($this->NOM_CAPATAZ);
					if ($this->Otro_NOM_CAPAT->Exportable) $Doc->ExportCaption($this->Otro_NOM_CAPAT);
					if ($this->Otro_CC_CAPAT->Exportable) $Doc->ExportCaption($this->Otro_CC_CAPAT);
					if ($this->NOM_LUGAR->Exportable) $Doc->ExportCaption($this->NOM_LUGAR);
					if ($this->Cocina->Exportable) $Doc->ExportCaption($this->Cocina);
					if ($this->_1_Abrelatas->Exportable) $Doc->ExportCaption($this->_1_Abrelatas);
					if ($this->_1_Balde->Exportable) $Doc->ExportCaption($this->_1_Balde);
					if ($this->_1_Arrocero_50->Exportable) $Doc->ExportCaption($this->_1_Arrocero_50);
					if ($this->_1_Arrocero_44->Exportable) $Doc->ExportCaption($this->_1_Arrocero_44);
					if ($this->_1_Chocolatera->Exportable) $Doc->ExportCaption($this->_1_Chocolatera);
					if ($this->_1_Colador->Exportable) $Doc->ExportCaption($this->_1_Colador);
					if ($this->_1_Cucharon_sopa->Exportable) $Doc->ExportCaption($this->_1_Cucharon_sopa);
					if ($this->_1_Cucharon_arroz->Exportable) $Doc->ExportCaption($this->_1_Cucharon_arroz);
					if ($this->_1_Cuchillo->Exportable) $Doc->ExportCaption($this->_1_Cuchillo);
					if ($this->_1_Embudo->Exportable) $Doc->ExportCaption($this->_1_Embudo);
					if ($this->_1_Espumera->Exportable) $Doc->ExportCaption($this->_1_Espumera);
					if ($this->_1_Estufa->Exportable) $Doc->ExportCaption($this->_1_Estufa);
					if ($this->_1_Cuchara_sopa->Exportable) $Doc->ExportCaption($this->_1_Cuchara_sopa);
					if ($this->_1_Recipiente->Exportable) $Doc->ExportCaption($this->_1_Recipiente);
					if ($this->_1_Kit_Repue_estufa->Exportable) $Doc->ExportCaption($this->_1_Kit_Repue_estufa);
					if ($this->_1_Molinillo->Exportable) $Doc->ExportCaption($this->_1_Molinillo);
					if ($this->_1_Olla_36->Exportable) $Doc->ExportCaption($this->_1_Olla_36);
					if ($this->_1_Olla_40->Exportable) $Doc->ExportCaption($this->_1_Olla_40);
					if ($this->_1_Paila_32->Exportable) $Doc->ExportCaption($this->_1_Paila_32);
					if ($this->_1_Paila_36_37->Exportable) $Doc->ExportCaption($this->_1_Paila_36_37);
					if ($this->Camping->Exportable) $Doc->ExportCaption($this->Camping);
					if ($this->_2_Aislante->Exportable) $Doc->ExportCaption($this->_2_Aislante);
					if ($this->_2_Carpa_hamaca->Exportable) $Doc->ExportCaption($this->_2_Carpa_hamaca);
					if ($this->_2_Carpa_rancho->Exportable) $Doc->ExportCaption($this->_2_Carpa_rancho);
					if ($this->_2_Fibra_rollo->Exportable) $Doc->ExportCaption($this->_2_Fibra_rollo);
					if ($this->_2_CAL->Exportable) $Doc->ExportCaption($this->_2_CAL);
					if ($this->_2_Linterna->Exportable) $Doc->ExportCaption($this->_2_Linterna);
					if ($this->_2_Botiquin->Exportable) $Doc->ExportCaption($this->_2_Botiquin);
					if ($this->_2_Mascara_filtro->Exportable) $Doc->ExportCaption($this->_2_Mascara_filtro);
					if ($this->_2_Pimpina->Exportable) $Doc->ExportCaption($this->_2_Pimpina);
					if ($this->_2_SleepingA0->Exportable) $Doc->ExportCaption($this->_2_SleepingA0);
					if ($this->_2_Plastico_negro->Exportable) $Doc->ExportCaption($this->_2_Plastico_negro);
					if ($this->_2_Tula_tropa->Exportable) $Doc->ExportCaption($this->_2_Tula_tropa);
					if ($this->_2_Camilla->Exportable) $Doc->ExportCaption($this->_2_Camilla);
					if ($this->Herramientas->Exportable) $Doc->ExportCaption($this->Herramientas);
					if ($this->_3_Abrazadera->Exportable) $Doc->ExportCaption($this->_3_Abrazadera);
					if ($this->_3_Aspersora->Exportable) $Doc->ExportCaption($this->_3_Aspersora);
					if ($this->_3_Cabo_hacha->Exportable) $Doc->ExportCaption($this->_3_Cabo_hacha);
					if ($this->_3_Funda_machete->Exportable) $Doc->ExportCaption($this->_3_Funda_machete);
					if ($this->_3_Glifosato_4lt->Exportable) $Doc->ExportCaption($this->_3_Glifosato_4lt);
					if ($this->_3_Hacha->Exportable) $Doc->ExportCaption($this->_3_Hacha);
					if ($this->_3_Lima_12_uni->Exportable) $Doc->ExportCaption($this->_3_Lima_12_uni);
					if ($this->_3_Llave_mixta->Exportable) $Doc->ExportCaption($this->_3_Llave_mixta);
					if ($this->_3_Machete->Exportable) $Doc->ExportCaption($this->_3_Machete);
					if ($this->_3_Gafa_traslucida->Exportable) $Doc->ExportCaption($this->_3_Gafa_traslucida);
					if ($this->_3_Motosierra->Exportable) $Doc->ExportCaption($this->_3_Motosierra);
					if ($this->_3_Palin->Exportable) $Doc->ExportCaption($this->_3_Palin);
					if ($this->_3_Tubo_galvanizado->Exportable) $Doc->ExportCaption($this->_3_Tubo_galvanizado);
				} else {
					if ($this->llave->Exportable) $Doc->ExportCaption($this->llave);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_PE->Exportable) $Doc->ExportCaption($this->Otro_PE);
					if ($this->OBSERVACION->Exportable) $Doc->ExportCaption($this->OBSERVACION);
					if ($this->AD1O->Exportable) $Doc->ExportCaption($this->AD1O);
					if ($this->FASE->Exportable) $Doc->ExportCaption($this->FASE);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
					if ($this->FECHA_INV->Exportable) $Doc->ExportCaption($this->FECHA_INV);
					if ($this->Otro_NOM_CAPAT->Exportable) $Doc->ExportCaption($this->Otro_NOM_CAPAT);
					if ($this->Otro_CC_CAPAT->Exportable) $Doc->ExportCaption($this->Otro_CC_CAPAT);
					if ($this->NOM_LUGAR->Exportable) $Doc->ExportCaption($this->NOM_LUGAR);
					if ($this->Cocina->Exportable) $Doc->ExportCaption($this->Cocina);
					if ($this->_1_Abrelatas->Exportable) $Doc->ExportCaption($this->_1_Abrelatas);
					if ($this->_1_Balde->Exportable) $Doc->ExportCaption($this->_1_Balde);
					if ($this->_1_Arrocero_50->Exportable) $Doc->ExportCaption($this->_1_Arrocero_50);
					if ($this->_1_Arrocero_44->Exportable) $Doc->ExportCaption($this->_1_Arrocero_44);
					if ($this->_1_Chocolatera->Exportable) $Doc->ExportCaption($this->_1_Chocolatera);
					if ($this->_1_Colador->Exportable) $Doc->ExportCaption($this->_1_Colador);
					if ($this->_1_Cucharon_sopa->Exportable) $Doc->ExportCaption($this->_1_Cucharon_sopa);
					if ($this->_1_Cucharon_arroz->Exportable) $Doc->ExportCaption($this->_1_Cucharon_arroz);
					if ($this->_1_Cuchillo->Exportable) $Doc->ExportCaption($this->_1_Cuchillo);
					if ($this->_1_Embudo->Exportable) $Doc->ExportCaption($this->_1_Embudo);
					if ($this->_1_Espumera->Exportable) $Doc->ExportCaption($this->_1_Espumera);
					if ($this->_1_Estufa->Exportable) $Doc->ExportCaption($this->_1_Estufa);
					if ($this->_1_Cuchara_sopa->Exportable) $Doc->ExportCaption($this->_1_Cuchara_sopa);
					if ($this->_1_Recipiente->Exportable) $Doc->ExportCaption($this->_1_Recipiente);
					if ($this->_1_Kit_Repue_estufa->Exportable) $Doc->ExportCaption($this->_1_Kit_Repue_estufa);
					if ($this->_1_Molinillo->Exportable) $Doc->ExportCaption($this->_1_Molinillo);
					if ($this->_1_Olla_36->Exportable) $Doc->ExportCaption($this->_1_Olla_36);
					if ($this->_1_Olla_40->Exportable) $Doc->ExportCaption($this->_1_Olla_40);
					if ($this->_1_Paila_32->Exportable) $Doc->ExportCaption($this->_1_Paila_32);
					if ($this->_1_Paila_36_37->Exportable) $Doc->ExportCaption($this->_1_Paila_36_37);
					if ($this->Camping->Exportable) $Doc->ExportCaption($this->Camping);
					if ($this->_2_Aislante->Exportable) $Doc->ExportCaption($this->_2_Aislante);
					if ($this->_2_Carpa_hamaca->Exportable) $Doc->ExportCaption($this->_2_Carpa_hamaca);
					if ($this->_2_Carpa_rancho->Exportable) $Doc->ExportCaption($this->_2_Carpa_rancho);
					if ($this->_2_Fibra_rollo->Exportable) $Doc->ExportCaption($this->_2_Fibra_rollo);
					if ($this->_2_CAL->Exportable) $Doc->ExportCaption($this->_2_CAL);
					if ($this->_2_Linterna->Exportable) $Doc->ExportCaption($this->_2_Linterna);
					if ($this->_2_Botiquin->Exportable) $Doc->ExportCaption($this->_2_Botiquin);
					if ($this->_2_Mascara_filtro->Exportable) $Doc->ExportCaption($this->_2_Mascara_filtro);
					if ($this->_2_Pimpina->Exportable) $Doc->ExportCaption($this->_2_Pimpina);
					if ($this->_2_SleepingA0->Exportable) $Doc->ExportCaption($this->_2_SleepingA0);
					if ($this->_2_Plastico_negro->Exportable) $Doc->ExportCaption($this->_2_Plastico_negro);
					if ($this->_2_Tula_tropa->Exportable) $Doc->ExportCaption($this->_2_Tula_tropa);
					if ($this->_2_Camilla->Exportable) $Doc->ExportCaption($this->_2_Camilla);
					if ($this->Herramientas->Exportable) $Doc->ExportCaption($this->Herramientas);
					if ($this->_3_Abrazadera->Exportable) $Doc->ExportCaption($this->_3_Abrazadera);
					if ($this->_3_Aspersora->Exportable) $Doc->ExportCaption($this->_3_Aspersora);
					if ($this->_3_Cabo_hacha->Exportable) $Doc->ExportCaption($this->_3_Cabo_hacha);
					if ($this->_3_Funda_machete->Exportable) $Doc->ExportCaption($this->_3_Funda_machete);
					if ($this->_3_Glifosato_4lt->Exportable) $Doc->ExportCaption($this->_3_Glifosato_4lt);
					if ($this->_3_Hacha->Exportable) $Doc->ExportCaption($this->_3_Hacha);
					if ($this->_3_Lima_12_uni->Exportable) $Doc->ExportCaption($this->_3_Lima_12_uni);
					if ($this->_3_Llave_mixta->Exportable) $Doc->ExportCaption($this->_3_Llave_mixta);
					if ($this->_3_Machete->Exportable) $Doc->ExportCaption($this->_3_Machete);
					if ($this->_3_Gafa_traslucida->Exportable) $Doc->ExportCaption($this->_3_Gafa_traslucida);
					if ($this->_3_Motosierra->Exportable) $Doc->ExportCaption($this->_3_Motosierra);
					if ($this->_3_Palin->Exportable) $Doc->ExportCaption($this->_3_Palin);
					if ($this->_3_Tubo_galvanizado->Exportable) $Doc->ExportCaption($this->_3_Tubo_galvanizado);
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
						if ($this->USUARIO->Exportable) $Doc->ExportField($this->USUARIO);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_PE->Exportable) $Doc->ExportField($this->Otro_PE);
						if ($this->OBSERVACION->Exportable) $Doc->ExportField($this->OBSERVACION);
						if ($this->AD1O->Exportable) $Doc->ExportField($this->AD1O);
						if ($this->FASE->Exportable) $Doc->ExportField($this->FASE);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
						if ($this->FECHA_INV->Exportable) $Doc->ExportField($this->FECHA_INV);
						if ($this->TIPO_INV->Exportable) $Doc->ExportField($this->TIPO_INV);
						if ($this->NOM_CAPATAZ->Exportable) $Doc->ExportField($this->NOM_CAPATAZ);
						if ($this->Otro_NOM_CAPAT->Exportable) $Doc->ExportField($this->Otro_NOM_CAPAT);
						if ($this->Otro_CC_CAPAT->Exportable) $Doc->ExportField($this->Otro_CC_CAPAT);
						if ($this->NOM_LUGAR->Exportable) $Doc->ExportField($this->NOM_LUGAR);
						if ($this->Cocina->Exportable) $Doc->ExportField($this->Cocina);
						if ($this->_1_Abrelatas->Exportable) $Doc->ExportField($this->_1_Abrelatas);
						if ($this->_1_Balde->Exportable) $Doc->ExportField($this->_1_Balde);
						if ($this->_1_Arrocero_50->Exportable) $Doc->ExportField($this->_1_Arrocero_50);
						if ($this->_1_Arrocero_44->Exportable) $Doc->ExportField($this->_1_Arrocero_44);
						if ($this->_1_Chocolatera->Exportable) $Doc->ExportField($this->_1_Chocolatera);
						if ($this->_1_Colador->Exportable) $Doc->ExportField($this->_1_Colador);
						if ($this->_1_Cucharon_sopa->Exportable) $Doc->ExportField($this->_1_Cucharon_sopa);
						if ($this->_1_Cucharon_arroz->Exportable) $Doc->ExportField($this->_1_Cucharon_arroz);
						if ($this->_1_Cuchillo->Exportable) $Doc->ExportField($this->_1_Cuchillo);
						if ($this->_1_Embudo->Exportable) $Doc->ExportField($this->_1_Embudo);
						if ($this->_1_Espumera->Exportable) $Doc->ExportField($this->_1_Espumera);
						if ($this->_1_Estufa->Exportable) $Doc->ExportField($this->_1_Estufa);
						if ($this->_1_Cuchara_sopa->Exportable) $Doc->ExportField($this->_1_Cuchara_sopa);
						if ($this->_1_Recipiente->Exportable) $Doc->ExportField($this->_1_Recipiente);
						if ($this->_1_Kit_Repue_estufa->Exportable) $Doc->ExportField($this->_1_Kit_Repue_estufa);
						if ($this->_1_Molinillo->Exportable) $Doc->ExportField($this->_1_Molinillo);
						if ($this->_1_Olla_36->Exportable) $Doc->ExportField($this->_1_Olla_36);
						if ($this->_1_Olla_40->Exportable) $Doc->ExportField($this->_1_Olla_40);
						if ($this->_1_Paila_32->Exportable) $Doc->ExportField($this->_1_Paila_32);
						if ($this->_1_Paila_36_37->Exportable) $Doc->ExportField($this->_1_Paila_36_37);
						if ($this->Camping->Exportable) $Doc->ExportField($this->Camping);
						if ($this->_2_Aislante->Exportable) $Doc->ExportField($this->_2_Aislante);
						if ($this->_2_Carpa_hamaca->Exportable) $Doc->ExportField($this->_2_Carpa_hamaca);
						if ($this->_2_Carpa_rancho->Exportable) $Doc->ExportField($this->_2_Carpa_rancho);
						if ($this->_2_Fibra_rollo->Exportable) $Doc->ExportField($this->_2_Fibra_rollo);
						if ($this->_2_CAL->Exportable) $Doc->ExportField($this->_2_CAL);
						if ($this->_2_Linterna->Exportable) $Doc->ExportField($this->_2_Linterna);
						if ($this->_2_Botiquin->Exportable) $Doc->ExportField($this->_2_Botiquin);
						if ($this->_2_Mascara_filtro->Exportable) $Doc->ExportField($this->_2_Mascara_filtro);
						if ($this->_2_Pimpina->Exportable) $Doc->ExportField($this->_2_Pimpina);
						if ($this->_2_SleepingA0->Exportable) $Doc->ExportField($this->_2_SleepingA0);
						if ($this->_2_Plastico_negro->Exportable) $Doc->ExportField($this->_2_Plastico_negro);
						if ($this->_2_Tula_tropa->Exportable) $Doc->ExportField($this->_2_Tula_tropa);
						if ($this->_2_Camilla->Exportable) $Doc->ExportField($this->_2_Camilla);
						if ($this->Herramientas->Exportable) $Doc->ExportField($this->Herramientas);
						if ($this->_3_Abrazadera->Exportable) $Doc->ExportField($this->_3_Abrazadera);
						if ($this->_3_Aspersora->Exportable) $Doc->ExportField($this->_3_Aspersora);
						if ($this->_3_Cabo_hacha->Exportable) $Doc->ExportField($this->_3_Cabo_hacha);
						if ($this->_3_Funda_machete->Exportable) $Doc->ExportField($this->_3_Funda_machete);
						if ($this->_3_Glifosato_4lt->Exportable) $Doc->ExportField($this->_3_Glifosato_4lt);
						if ($this->_3_Hacha->Exportable) $Doc->ExportField($this->_3_Hacha);
						if ($this->_3_Lima_12_uni->Exportable) $Doc->ExportField($this->_3_Lima_12_uni);
						if ($this->_3_Llave_mixta->Exportable) $Doc->ExportField($this->_3_Llave_mixta);
						if ($this->_3_Machete->Exportable) $Doc->ExportField($this->_3_Machete);
						if ($this->_3_Gafa_traslucida->Exportable) $Doc->ExportField($this->_3_Gafa_traslucida);
						if ($this->_3_Motosierra->Exportable) $Doc->ExportField($this->_3_Motosierra);
						if ($this->_3_Palin->Exportable) $Doc->ExportField($this->_3_Palin);
						if ($this->_3_Tubo_galvanizado->Exportable) $Doc->ExportField($this->_3_Tubo_galvanizado);
					} else {
						if ($this->llave->Exportable) $Doc->ExportField($this->llave);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_PE->Exportable) $Doc->ExportField($this->Otro_PE);
						if ($this->OBSERVACION->Exportable) $Doc->ExportField($this->OBSERVACION);
						if ($this->AD1O->Exportable) $Doc->ExportField($this->AD1O);
						if ($this->FASE->Exportable) $Doc->ExportField($this->FASE);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
						if ($this->FECHA_INV->Exportable) $Doc->ExportField($this->FECHA_INV);
						if ($this->Otro_NOM_CAPAT->Exportable) $Doc->ExportField($this->Otro_NOM_CAPAT);
						if ($this->Otro_CC_CAPAT->Exportable) $Doc->ExportField($this->Otro_CC_CAPAT);
						if ($this->NOM_LUGAR->Exportable) $Doc->ExportField($this->NOM_LUGAR);
						if ($this->Cocina->Exportable) $Doc->ExportField($this->Cocina);
						if ($this->_1_Abrelatas->Exportable) $Doc->ExportField($this->_1_Abrelatas);
						if ($this->_1_Balde->Exportable) $Doc->ExportField($this->_1_Balde);
						if ($this->_1_Arrocero_50->Exportable) $Doc->ExportField($this->_1_Arrocero_50);
						if ($this->_1_Arrocero_44->Exportable) $Doc->ExportField($this->_1_Arrocero_44);
						if ($this->_1_Chocolatera->Exportable) $Doc->ExportField($this->_1_Chocolatera);
						if ($this->_1_Colador->Exportable) $Doc->ExportField($this->_1_Colador);
						if ($this->_1_Cucharon_sopa->Exportable) $Doc->ExportField($this->_1_Cucharon_sopa);
						if ($this->_1_Cucharon_arroz->Exportable) $Doc->ExportField($this->_1_Cucharon_arroz);
						if ($this->_1_Cuchillo->Exportable) $Doc->ExportField($this->_1_Cuchillo);
						if ($this->_1_Embudo->Exportable) $Doc->ExportField($this->_1_Embudo);
						if ($this->_1_Espumera->Exportable) $Doc->ExportField($this->_1_Espumera);
						if ($this->_1_Estufa->Exportable) $Doc->ExportField($this->_1_Estufa);
						if ($this->_1_Cuchara_sopa->Exportable) $Doc->ExportField($this->_1_Cuchara_sopa);
						if ($this->_1_Recipiente->Exportable) $Doc->ExportField($this->_1_Recipiente);
						if ($this->_1_Kit_Repue_estufa->Exportable) $Doc->ExportField($this->_1_Kit_Repue_estufa);
						if ($this->_1_Molinillo->Exportable) $Doc->ExportField($this->_1_Molinillo);
						if ($this->_1_Olla_36->Exportable) $Doc->ExportField($this->_1_Olla_36);
						if ($this->_1_Olla_40->Exportable) $Doc->ExportField($this->_1_Olla_40);
						if ($this->_1_Paila_32->Exportable) $Doc->ExportField($this->_1_Paila_32);
						if ($this->_1_Paila_36_37->Exportable) $Doc->ExportField($this->_1_Paila_36_37);
						if ($this->Camping->Exportable) $Doc->ExportField($this->Camping);
						if ($this->_2_Aislante->Exportable) $Doc->ExportField($this->_2_Aislante);
						if ($this->_2_Carpa_hamaca->Exportable) $Doc->ExportField($this->_2_Carpa_hamaca);
						if ($this->_2_Carpa_rancho->Exportable) $Doc->ExportField($this->_2_Carpa_rancho);
						if ($this->_2_Fibra_rollo->Exportable) $Doc->ExportField($this->_2_Fibra_rollo);
						if ($this->_2_CAL->Exportable) $Doc->ExportField($this->_2_CAL);
						if ($this->_2_Linterna->Exportable) $Doc->ExportField($this->_2_Linterna);
						if ($this->_2_Botiquin->Exportable) $Doc->ExportField($this->_2_Botiquin);
						if ($this->_2_Mascara_filtro->Exportable) $Doc->ExportField($this->_2_Mascara_filtro);
						if ($this->_2_Pimpina->Exportable) $Doc->ExportField($this->_2_Pimpina);
						if ($this->_2_SleepingA0->Exportable) $Doc->ExportField($this->_2_SleepingA0);
						if ($this->_2_Plastico_negro->Exportable) $Doc->ExportField($this->_2_Plastico_negro);
						if ($this->_2_Tula_tropa->Exportable) $Doc->ExportField($this->_2_Tula_tropa);
						if ($this->_2_Camilla->Exportable) $Doc->ExportField($this->_2_Camilla);
						if ($this->Herramientas->Exportable) $Doc->ExportField($this->Herramientas);
						if ($this->_3_Abrazadera->Exportable) $Doc->ExportField($this->_3_Abrazadera);
						if ($this->_3_Aspersora->Exportable) $Doc->ExportField($this->_3_Aspersora);
						if ($this->_3_Cabo_hacha->Exportable) $Doc->ExportField($this->_3_Cabo_hacha);
						if ($this->_3_Funda_machete->Exportable) $Doc->ExportField($this->_3_Funda_machete);
						if ($this->_3_Glifosato_4lt->Exportable) $Doc->ExportField($this->_3_Glifosato_4lt);
						if ($this->_3_Hacha->Exportable) $Doc->ExportField($this->_3_Hacha);
						if ($this->_3_Lima_12_uni->Exportable) $Doc->ExportField($this->_3_Lima_12_uni);
						if ($this->_3_Llave_mixta->Exportable) $Doc->ExportField($this->_3_Llave_mixta);
						if ($this->_3_Machete->Exportable) $Doc->ExportField($this->_3_Machete);
						if ($this->_3_Gafa_traslucida->Exportable) $Doc->ExportField($this->_3_Gafa_traslucida);
						if ($this->_3_Motosierra->Exportable) $Doc->ExportField($this->_3_Motosierra);
						if ($this->_3_Palin->Exportable) $Doc->ExportField($this->_3_Palin);
						if ($this->_3_Tubo_galvanizado->Exportable) $Doc->ExportField($this->_3_Tubo_galvanizado);
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
