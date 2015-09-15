<?php

// Global variable for table object
$view1_acc = NULL;

//
// Table class for view1_acc
//
class cview1_acc extends cTable {
	var $llave;
	var $F_Sincron;
	var $USUARIO;
	var $Cargo_gme;
	var $NOM_PE;
	var $Otro_PE;
	var $NOM_APOYO;
	var $Otro_Nom_Apoyo;
	var $Otro_CC_Apoyo;
	var $NOM_ENLACE;
	var $Otro_Nom_Enlace;
	var $Otro_CC_Enlace;
	var $NOM_PGE;
	var $Otro_Nom_PGE;
	var $Otro_CC_PGE;
	var $Departamento;
	var $Muncipio;
	var $NOM_VDA;
	var $LATITUD;
	var $GRA_LAT;
	var $MIN_LAT;
	var $SEG_LAT;
	var $GRA_LONG;
	var $MIN_LONG;
	var $SEG_LONG;
	var $FECHA_ACC;
	var $HORA_ACC;
	var $Hora_ingreso;
	var $FP_Armada;
	var $FP_Ejercito;
	var $FP_Policia;
	var $NOM_COMANDANTE;
	var $TESTI1;
	var $CC_TESTI1;
	var $CARGO_TESTI1;
	var $TESTI2;
	var $CC_TESTI2;
	var $CARGO_TESTI2;
	var $Afectados;
	var $NUM_Afectado;
	var $Nom_Afectado;
	var $CC_Afectado;
	var $Cargo_Afectado;
	var $Tipo_incidente;
	var $Parte_Cuerpo;
	var $ESTADO_AFEC;
	var $EVACUADO;
	var $DESC_ACC;
	var $Modificado;
	var $llave_2;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view1_acc';
		$this->TableName = 'view1_acc';
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

		// llave
		$this->llave = new cField('view1_acc', 'view1_acc', 'x_llave', 'llave', '`llave`', '`llave`', 200, -1, FALSE, '`llave`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['llave'] = &$this->llave;

		// F_Sincron
		$this->F_Sincron = new cField('view1_acc', 'view1_acc', 'x_F_Sincron', 'F_Sincron', '`F_Sincron`', 'DATE_FORMAT(`F_Sincron`, \'%d/%m/%Y\')', 135, 7, FALSE, '`F_Sincron`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->F_Sincron->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['F_Sincron'] = &$this->F_Sincron;

		// USUARIO
		$this->USUARIO = new cField('view1_acc', 'view1_acc', 'x_USUARIO', 'USUARIO', '`USUARIO`', '`USUARIO`', 201, -1, FALSE, '`USUARIO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['USUARIO'] = &$this->USUARIO;

		// Cargo_gme
		$this->Cargo_gme = new cField('view1_acc', 'view1_acc', 'x_Cargo_gme', 'Cargo_gme', '`Cargo_gme`', '`Cargo_gme`', 200, -1, FALSE, '`Cargo_gme`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_gme'] = &$this->Cargo_gme;

		// NOM_PE
		$this->NOM_PE = new cField('view1_acc', 'view1_acc', 'x_NOM_PE', 'NOM_PE', '`NOM_PE`', '`NOM_PE`', 201, -1, FALSE, '`NOM_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_PE'] = &$this->NOM_PE;

		// Otro_PE
		$this->Otro_PE = new cField('view1_acc', 'view1_acc', 'x_Otro_PE', 'Otro_PE', '`Otro_PE`', '`Otro_PE`', 200, -1, FALSE, '`Otro_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_PE'] = &$this->Otro_PE;

		// NOM_APOYO
		$this->NOM_APOYO = new cField('view1_acc', 'view1_acc', 'x_NOM_APOYO', 'NOM_APOYO', '`NOM_APOYO`', '`NOM_APOYO`', 201, -1, FALSE, '`NOM_APOYO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_APOYO'] = &$this->NOM_APOYO;

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo = new cField('view1_acc', 'view1_acc', 'x_Otro_Nom_Apoyo', 'Otro_Nom_Apoyo', '`Otro_Nom_Apoyo`', '`Otro_Nom_Apoyo`', 200, -1, FALSE, '`Otro_Nom_Apoyo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Nom_Apoyo'] = &$this->Otro_Nom_Apoyo;

		// Otro_CC_Apoyo
		$this->Otro_CC_Apoyo = new cField('view1_acc', 'view1_acc', 'x_Otro_CC_Apoyo', 'Otro_CC_Apoyo', '`Otro_CC_Apoyo`', '`Otro_CC_Apoyo`', 200, -1, FALSE, '`Otro_CC_Apoyo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_Apoyo'] = &$this->Otro_CC_Apoyo;

		// NOM_ENLACE
		$this->NOM_ENLACE = new cField('view1_acc', 'view1_acc', 'x_NOM_ENLACE', 'NOM_ENLACE', '`NOM_ENLACE`', '`NOM_ENLACE`', 201, -1, FALSE, '`NOM_ENLACE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_ENLACE'] = &$this->NOM_ENLACE;

		// Otro_Nom_Enlace
		$this->Otro_Nom_Enlace = new cField('view1_acc', 'view1_acc', 'x_Otro_Nom_Enlace', 'Otro_Nom_Enlace', '`Otro_Nom_Enlace`', '`Otro_Nom_Enlace`', 200, -1, FALSE, '`Otro_Nom_Enlace`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Nom_Enlace'] = &$this->Otro_Nom_Enlace;

		// Otro_CC_Enlace
		$this->Otro_CC_Enlace = new cField('view1_acc', 'view1_acc', 'x_Otro_CC_Enlace', 'Otro_CC_Enlace', '`Otro_CC_Enlace`', '`Otro_CC_Enlace`', 200, -1, FALSE, '`Otro_CC_Enlace`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_Enlace'] = &$this->Otro_CC_Enlace;

		// NOM_PGE
		$this->NOM_PGE = new cField('view1_acc', 'view1_acc', 'x_NOM_PGE', 'NOM_PGE', '`NOM_PGE`', '`NOM_PGE`', 201, -1, FALSE, '`NOM_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_PGE'] = &$this->NOM_PGE;

		// Otro_Nom_PGE
		$this->Otro_Nom_PGE = new cField('view1_acc', 'view1_acc', 'x_Otro_Nom_PGE', 'Otro_Nom_PGE', '`Otro_Nom_PGE`', '`Otro_Nom_PGE`', 200, -1, FALSE, '`Otro_Nom_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Nom_PGE'] = &$this->Otro_Nom_PGE;

		// Otro_CC_PGE
		$this->Otro_CC_PGE = new cField('view1_acc', 'view1_acc', 'x_Otro_CC_PGE', 'Otro_CC_PGE', '`Otro_CC_PGE`', '`Otro_CC_PGE`', 200, -1, FALSE, '`Otro_CC_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_PGE'] = &$this->Otro_CC_PGE;

		// Departamento
		$this->Departamento = new cField('view1_acc', 'view1_acc', 'x_Departamento', 'Departamento', '`Departamento`', '`Departamento`', 200, -1, FALSE, '`Departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Departamento'] = &$this->Departamento;

		// Muncipio
		$this->Muncipio = new cField('view1_acc', 'view1_acc', 'x_Muncipio', 'Muncipio', '`Muncipio`', '`Muncipio`', 201, -1, FALSE, '`Muncipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Muncipio'] = &$this->Muncipio;

		// NOM_VDA
		$this->NOM_VDA = new cField('view1_acc', 'view1_acc', 'x_NOM_VDA', 'NOM_VDA', '`NOM_VDA`', '`NOM_VDA`', 200, -1, FALSE, '`NOM_VDA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_VDA'] = &$this->NOM_VDA;

		// LATITUD
		$this->LATITUD = new cField('view1_acc', 'view1_acc', 'x_LATITUD', 'LATITUD', '`LATITUD`', '`LATITUD`', 201, -1, FALSE, '`LATITUD`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LATITUD'] = &$this->LATITUD;

		// GRA_LAT
		$this->GRA_LAT = new cField('view1_acc', 'view1_acc', 'x_GRA_LAT', 'GRA_LAT', '`GRA_LAT`', '`GRA_LAT`', 3, -1, FALSE, '`GRA_LAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LAT->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LAT'] = &$this->GRA_LAT;

		// MIN_LAT
		$this->MIN_LAT = new cField('view1_acc', 'view1_acc', 'x_MIN_LAT', 'MIN_LAT', '`MIN_LAT`', '`MIN_LAT`', 3, -1, FALSE, '`MIN_LAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LAT->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LAT'] = &$this->MIN_LAT;

		// SEG_LAT
		$this->SEG_LAT = new cField('view1_acc', 'view1_acc', 'x_SEG_LAT', 'SEG_LAT', '`SEG_LAT`', '`SEG_LAT`', 131, -1, FALSE, '`SEG_LAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LAT->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LAT'] = &$this->SEG_LAT;

		// GRA_LONG
		$this->GRA_LONG = new cField('view1_acc', 'view1_acc', 'x_GRA_LONG', 'GRA_LONG', '`GRA_LONG`', '`GRA_LONG`', 3, -1, FALSE, '`GRA_LONG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LONG->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LONG'] = &$this->GRA_LONG;

		// MIN_LONG
		$this->MIN_LONG = new cField('view1_acc', 'view1_acc', 'x_MIN_LONG', 'MIN_LONG', '`MIN_LONG`', '`MIN_LONG`', 3, -1, FALSE, '`MIN_LONG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LONG->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LONG'] = &$this->MIN_LONG;

		// SEG_LONG
		$this->SEG_LONG = new cField('view1_acc', 'view1_acc', 'x_SEG_LONG', 'SEG_LONG', '`SEG_LONG`', '`SEG_LONG`', 131, -1, FALSE, '`SEG_LONG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LONG->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LONG'] = &$this->SEG_LONG;

		// FECHA_ACC
		$this->FECHA_ACC = new cField('view1_acc', 'view1_acc', 'x_FECHA_ACC', 'FECHA_ACC', '`FECHA_ACC`', '`FECHA_ACC`', 200, -1, FALSE, '`FECHA_ACC`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FECHA_ACC'] = &$this->FECHA_ACC;

		// HORA_ACC
		$this->HORA_ACC = new cField('view1_acc', 'view1_acc', 'x_HORA_ACC', 'HORA_ACC', '`HORA_ACC`', '`HORA_ACC`', 200, -1, FALSE, '`HORA_ACC`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['HORA_ACC'] = &$this->HORA_ACC;

		// Hora_ingreso
		$this->Hora_ingreso = new cField('view1_acc', 'view1_acc', 'x_Hora_ingreso', 'Hora_ingreso', '`Hora_ingreso`', '`Hora_ingreso`', 200, -1, FALSE, '`Hora_ingreso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Hora_ingreso'] = &$this->Hora_ingreso;

		// FP_Armada
		$this->FP_Armada = new cField('view1_acc', 'view1_acc', 'x_FP_Armada', 'FP_Armada', '`FP_Armada`', '`FP_Armada`', 131, -1, FALSE, '`FP_Armada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->FP_Armada->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['FP_Armada'] = &$this->FP_Armada;

		// FP_Ejercito
		$this->FP_Ejercito = new cField('view1_acc', 'view1_acc', 'x_FP_Ejercito', 'FP_Ejercito', '`FP_Ejercito`', '`FP_Ejercito`', 131, -1, FALSE, '`FP_Ejercito`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->FP_Ejercito->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['FP_Ejercito'] = &$this->FP_Ejercito;

		// FP_Policia
		$this->FP_Policia = new cField('view1_acc', 'view1_acc', 'x_FP_Policia', 'FP_Policia', '`FP_Policia`', '`FP_Policia`', 131, -1, FALSE, '`FP_Policia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->FP_Policia->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['FP_Policia'] = &$this->FP_Policia;

		// NOM_COMANDANTE
		$this->NOM_COMANDANTE = new cField('view1_acc', 'view1_acc', 'x_NOM_COMANDANTE', 'NOM_COMANDANTE', '`NOM_COMANDANTE`', '`NOM_COMANDANTE`', 200, -1, FALSE, '`NOM_COMANDANTE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_COMANDANTE'] = &$this->NOM_COMANDANTE;

		// TESTI1
		$this->TESTI1 = new cField('view1_acc', 'view1_acc', 'x_TESTI1', 'TESTI1', '`TESTI1`', '`TESTI1`', 200, -1, FALSE, '`TESTI1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TESTI1'] = &$this->TESTI1;

		// CC_TESTI1
		$this->CC_TESTI1 = new cField('view1_acc', 'view1_acc', 'x_CC_TESTI1', 'CC_TESTI1', '`CC_TESTI1`', '`CC_TESTI1`', 200, -1, FALSE, '`CC_TESTI1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CC_TESTI1'] = &$this->CC_TESTI1;

		// CARGO_TESTI1
		$this->CARGO_TESTI1 = new cField('view1_acc', 'view1_acc', 'x_CARGO_TESTI1', 'CARGO_TESTI1', '`CARGO_TESTI1`', '`CARGO_TESTI1`', 201, -1, FALSE, '`CARGO_TESTI1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CARGO_TESTI1'] = &$this->CARGO_TESTI1;

		// TESTI2
		$this->TESTI2 = new cField('view1_acc', 'view1_acc', 'x_TESTI2', 'TESTI2', '`TESTI2`', '`TESTI2`', 200, -1, FALSE, '`TESTI2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TESTI2'] = &$this->TESTI2;

		// CC_TESTI2
		$this->CC_TESTI2 = new cField('view1_acc', 'view1_acc', 'x_CC_TESTI2', 'CC_TESTI2', '`CC_TESTI2`', '`CC_TESTI2`', 200, -1, FALSE, '`CC_TESTI2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CC_TESTI2'] = &$this->CC_TESTI2;

		// CARGO_TESTI2
		$this->CARGO_TESTI2 = new cField('view1_acc', 'view1_acc', 'x_CARGO_TESTI2', 'CARGO_TESTI2', '`CARGO_TESTI2`', '`CARGO_TESTI2`', 201, -1, FALSE, '`CARGO_TESTI2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CARGO_TESTI2'] = &$this->CARGO_TESTI2;

		// Afectados
		$this->Afectados = new cField('view1_acc', 'view1_acc', 'x_Afectados', 'Afectados', '`Afectados`', '`Afectados`', 3, -1, FALSE, '`Afectados`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Afectados->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Afectados'] = &$this->Afectados;

		// NUM_Afectado
		$this->NUM_Afectado = new cField('view1_acc', 'view1_acc', 'x_NUM_Afectado', 'NUM_Afectado', '`NUM_Afectado`', '`NUM_Afectado`', 3, -1, FALSE, '`NUM_Afectado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NUM_Afectado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NUM_Afectado'] = &$this->NUM_Afectado;

		// Nom_Afectado
		$this->Nom_Afectado = new cField('view1_acc', 'view1_acc', 'x_Nom_Afectado', 'Nom_Afectado', '`Nom_Afectado`', '`Nom_Afectado`', 201, -1, FALSE, '`Nom_Afectado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nom_Afectado'] = &$this->Nom_Afectado;

		// CC_Afectado
		$this->CC_Afectado = new cField('view1_acc', 'view1_acc', 'x_CC_Afectado', 'CC_Afectado', '`CC_Afectado`', '`CC_Afectado`', 200, -1, FALSE, '`CC_Afectado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CC_Afectado'] = &$this->CC_Afectado;

		// Cargo_Afectado
		$this->Cargo_Afectado = new cField('view1_acc', 'view1_acc', 'x_Cargo_Afectado', 'Cargo_Afectado', '`Cargo_Afectado`', '`Cargo_Afectado`', 201, -1, FALSE, '`Cargo_Afectado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_Afectado'] = &$this->Cargo_Afectado;

		// Tipo_incidente
		$this->Tipo_incidente = new cField('view1_acc', 'view1_acc', 'x_Tipo_incidente', 'Tipo_incidente', '`Tipo_incidente`', '`Tipo_incidente`', 200, -1, FALSE, '`Tipo_incidente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Tipo_incidente'] = &$this->Tipo_incidente;

		// Parte_Cuerpo
		$this->Parte_Cuerpo = new cField('view1_acc', 'view1_acc', 'x_Parte_Cuerpo', 'Parte_Cuerpo', '`Parte_Cuerpo`', '`Parte_Cuerpo`', 201, -1, FALSE, '`Parte_Cuerpo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Parte_Cuerpo'] = &$this->Parte_Cuerpo;

		// ESTADO_AFEC
		$this->ESTADO_AFEC = new cField('view1_acc', 'view1_acc', 'x_ESTADO_AFEC', 'ESTADO_AFEC', '`ESTADO_AFEC`', '`ESTADO_AFEC`', 201, -1, FALSE, '`ESTADO_AFEC`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ESTADO_AFEC'] = &$this->ESTADO_AFEC;

		// EVACUADO
		$this->EVACUADO = new cField('view1_acc', 'view1_acc', 'x_EVACUADO', 'EVACUADO', '`EVACUADO`', '`EVACUADO`', 201, -1, FALSE, '`EVACUADO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['EVACUADO'] = &$this->EVACUADO;

		// DESC_ACC
		$this->DESC_ACC = new cField('view1_acc', 'view1_acc', 'x_DESC_ACC', 'DESC_ACC', '`DESC_ACC`', '`DESC_ACC`', 201, -1, FALSE, '`DESC_ACC`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DESC_ACC'] = &$this->DESC_ACC;

		// Modificado
		$this->Modificado = new cField('view1_acc', 'view1_acc', 'x_Modificado', 'Modificado', '`Modificado`', '`Modificado`', 200, -1, FALSE, '`Modificado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Modificado'] = &$this->Modificado;

		// llave_2
		$this->llave_2 = new cField('view1_acc', 'view1_acc', 'x_llave_2', 'llave_2', '`llave_2`', '`llave_2`', 200, -1, FALSE, '`llave_2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['llave_2'] = &$this->llave_2;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`view1_acc`";
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
	var $UpdateTable = "`view1_acc`";

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
			if (array_key_exists('llave_2', $rs))
				ew_AddFilter($where, ew_QuotedName('llave_2') . '=' . ew_QuotedValue($rs['llave_2'], $this->llave_2->FldDataType));
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
		return "`llave_2` = '@llave_2@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@llave_2@", ew_AdjustSql($this->llave_2->CurrentValue), $sKeyFilter); // Replace key value
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
			return "view1_acclist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view1_acclist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("view1_accview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("view1_accview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "view1_accadd.php?" . $this->UrlParm($parm);
		else
			return "view1_accadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("view1_accedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("view1_accadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view1_accdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->llave_2->CurrentValue)) {
			$sUrl .= "llave_2=" . urlencode($this->llave_2->CurrentValue);
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
			$arKeys[] = @$_GET["llave_2"]; // llave_2

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
			$this->llave_2->CurrentValue = $key;
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
		$this->NOM_APOYO->setDbValue($rs->fields('NOM_APOYO'));
		$this->Otro_Nom_Apoyo->setDbValue($rs->fields('Otro_Nom_Apoyo'));
		$this->Otro_CC_Apoyo->setDbValue($rs->fields('Otro_CC_Apoyo'));
		$this->NOM_ENLACE->setDbValue($rs->fields('NOM_ENLACE'));
		$this->Otro_Nom_Enlace->setDbValue($rs->fields('Otro_Nom_Enlace'));
		$this->Otro_CC_Enlace->setDbValue($rs->fields('Otro_CC_Enlace'));
		$this->NOM_PGE->setDbValue($rs->fields('NOM_PGE'));
		$this->Otro_Nom_PGE->setDbValue($rs->fields('Otro_Nom_PGE'));
		$this->Otro_CC_PGE->setDbValue($rs->fields('Otro_CC_PGE'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->NOM_VDA->setDbValue($rs->fields('NOM_VDA'));
		$this->LATITUD->setDbValue($rs->fields('LATITUD'));
		$this->GRA_LAT->setDbValue($rs->fields('GRA_LAT'));
		$this->MIN_LAT->setDbValue($rs->fields('MIN_LAT'));
		$this->SEG_LAT->setDbValue($rs->fields('SEG_LAT'));
		$this->GRA_LONG->setDbValue($rs->fields('GRA_LONG'));
		$this->MIN_LONG->setDbValue($rs->fields('MIN_LONG'));
		$this->SEG_LONG->setDbValue($rs->fields('SEG_LONG'));
		$this->FECHA_ACC->setDbValue($rs->fields('FECHA_ACC'));
		$this->HORA_ACC->setDbValue($rs->fields('HORA_ACC'));
		$this->Hora_ingreso->setDbValue($rs->fields('Hora_ingreso'));
		$this->FP_Armada->setDbValue($rs->fields('FP_Armada'));
		$this->FP_Ejercito->setDbValue($rs->fields('FP_Ejercito'));
		$this->FP_Policia->setDbValue($rs->fields('FP_Policia'));
		$this->NOM_COMANDANTE->setDbValue($rs->fields('NOM_COMANDANTE'));
		$this->TESTI1->setDbValue($rs->fields('TESTI1'));
		$this->CC_TESTI1->setDbValue($rs->fields('CC_TESTI1'));
		$this->CARGO_TESTI1->setDbValue($rs->fields('CARGO_TESTI1'));
		$this->TESTI2->setDbValue($rs->fields('TESTI2'));
		$this->CC_TESTI2->setDbValue($rs->fields('CC_TESTI2'));
		$this->CARGO_TESTI2->setDbValue($rs->fields('CARGO_TESTI2'));
		$this->Afectados->setDbValue($rs->fields('Afectados'));
		$this->NUM_Afectado->setDbValue($rs->fields('NUM_Afectado'));
		$this->Nom_Afectado->setDbValue($rs->fields('Nom_Afectado'));
		$this->CC_Afectado->setDbValue($rs->fields('CC_Afectado'));
		$this->Cargo_Afectado->setDbValue($rs->fields('Cargo_Afectado'));
		$this->Tipo_incidente->setDbValue($rs->fields('Tipo_incidente'));
		$this->Parte_Cuerpo->setDbValue($rs->fields('Parte_Cuerpo'));
		$this->ESTADO_AFEC->setDbValue($rs->fields('ESTADO_AFEC'));
		$this->EVACUADO->setDbValue($rs->fields('EVACUADO'));
		$this->DESC_ACC->setDbValue($rs->fields('DESC_ACC'));
		$this->Modificado->setDbValue($rs->fields('Modificado'));
		$this->llave_2->setDbValue($rs->fields('llave_2'));
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
		// NOM_APOYO
		// Otro_Nom_Apoyo
		// Otro_CC_Apoyo
		// NOM_ENLACE
		// Otro_Nom_Enlace
		// Otro_CC_Enlace
		// NOM_PGE
		// Otro_Nom_PGE
		// Otro_CC_PGE
		// Departamento
		// Muncipio
		// NOM_VDA
		// LATITUD
		// GRA_LAT
		// MIN_LAT
		// SEG_LAT
		// GRA_LONG
		// MIN_LONG
		// SEG_LONG
		// FECHA_ACC
		// HORA_ACC
		// Hora_ingreso
		// FP_Armada
		// FP_Ejercito
		// FP_Policia
		// NOM_COMANDANTE
		// TESTI1
		// CC_TESTI1
		// CARGO_TESTI1
		// TESTI2
		// CC_TESTI2
		// CARGO_TESTI2
		// Afectados
		// NUM_Afectado
		// Nom_Afectado
		// CC_Afectado
		// Cargo_Afectado
		// Tipo_incidente
		// Parte_Cuerpo
		// ESTADO_AFEC
		// EVACUADO
		// DESC_ACC
		// Modificado
		// llave_2

		$this->llave_2->CellCssStyle = "white-space: nowrap;";

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
		if (strval($this->NOM_PE->CurrentValue) <> "") {
			$sFilterWrk = "`NOM_PE`" . ew_SearchString("=", $this->NOM_PE->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->NOM_PE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `NOM_PE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->NOM_PE->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
			}
		} else {
			$this->NOM_PE->ViewValue = NULL;
		}
		$this->NOM_PE->ViewCustomAttributes = "";

		// Otro_PE
		$this->Otro_PE->ViewValue = $this->Otro_PE->CurrentValue;
		$this->Otro_PE->ViewCustomAttributes = "";

		// NOM_APOYO
		$this->NOM_APOYO->ViewValue = $this->NOM_APOYO->CurrentValue;
		$this->NOM_APOYO->ViewCustomAttributes = "";

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo->ViewValue = $this->Otro_Nom_Apoyo->CurrentValue;
		$this->Otro_Nom_Apoyo->ViewCustomAttributes = "";

		// Otro_CC_Apoyo
		$this->Otro_CC_Apoyo->ViewValue = $this->Otro_CC_Apoyo->CurrentValue;
		$this->Otro_CC_Apoyo->ViewCustomAttributes = "";

		// NOM_ENLACE
		$this->NOM_ENLACE->ViewValue = $this->NOM_ENLACE->CurrentValue;
		$this->NOM_ENLACE->ViewCustomAttributes = "";

		// Otro_Nom_Enlace
		$this->Otro_Nom_Enlace->ViewValue = $this->Otro_Nom_Enlace->CurrentValue;
		$this->Otro_Nom_Enlace->ViewCustomAttributes = "";

		// Otro_CC_Enlace
		$this->Otro_CC_Enlace->ViewValue = $this->Otro_CC_Enlace->CurrentValue;
		$this->Otro_CC_Enlace->ViewCustomAttributes = "";

		// NOM_PGE
		if (strval($this->NOM_PGE->CurrentValue) <> "") {
			$sFilterWrk = "`NOM_PGE`" . ew_SearchString("=", $this->NOM_PGE->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->NOM_PGE, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `NOM_PGE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->NOM_PGE->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->NOM_PGE->ViewValue = $this->NOM_PGE->CurrentValue;
			}
		} else {
			$this->NOM_PGE->ViewValue = NULL;
		}
		$this->NOM_PGE->ViewCustomAttributes = "";

		// Otro_Nom_PGE
		$this->Otro_Nom_PGE->ViewValue = $this->Otro_Nom_PGE->CurrentValue;
		$this->Otro_Nom_PGE->ViewCustomAttributes = "";

		// Otro_CC_PGE
		$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
		$this->Otro_CC_PGE->ViewCustomAttributes = "";

		// Departamento
		$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// Muncipio
		$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
		$this->Muncipio->ViewCustomAttributes = "";

		// NOM_VDA
		$this->NOM_VDA->ViewValue = $this->NOM_VDA->CurrentValue;
		$this->NOM_VDA->ViewCustomAttributes = "";

		// LATITUD
		$this->LATITUD->ViewValue = $this->LATITUD->CurrentValue;
		$this->LATITUD->ViewCustomAttributes = "";

		// GRA_LAT
		$this->GRA_LAT->ViewValue = $this->GRA_LAT->CurrentValue;
		$this->GRA_LAT->ViewCustomAttributes = "";

		// MIN_LAT
		$this->MIN_LAT->ViewValue = $this->MIN_LAT->CurrentValue;
		$this->MIN_LAT->ViewCustomAttributes = "";

		// SEG_LAT
		$this->SEG_LAT->ViewValue = $this->SEG_LAT->CurrentValue;
		$this->SEG_LAT->ViewCustomAttributes = "";

		// GRA_LONG
		$this->GRA_LONG->ViewValue = $this->GRA_LONG->CurrentValue;
		$this->GRA_LONG->ViewCustomAttributes = "";

		// MIN_LONG
		$this->MIN_LONG->ViewValue = $this->MIN_LONG->CurrentValue;
		$this->MIN_LONG->ViewCustomAttributes = "";

		// SEG_LONG
		$this->SEG_LONG->ViewValue = $this->SEG_LONG->CurrentValue;
		$this->SEG_LONG->ViewCustomAttributes = "";

		// FECHA_ACC
		$this->FECHA_ACC->ViewValue = $this->FECHA_ACC->CurrentValue;
		$this->FECHA_ACC->ViewCustomAttributes = "";

		// HORA_ACC
		$this->HORA_ACC->ViewValue = $this->HORA_ACC->CurrentValue;
		$this->HORA_ACC->ViewCustomAttributes = "";

		// Hora_ingreso
		$this->Hora_ingreso->ViewValue = $this->Hora_ingreso->CurrentValue;
		$this->Hora_ingreso->ViewCustomAttributes = "";

		// FP_Armada
		$this->FP_Armada->ViewValue = $this->FP_Armada->CurrentValue;
		$this->FP_Armada->ViewCustomAttributes = "";

		// FP_Ejercito
		$this->FP_Ejercito->ViewValue = $this->FP_Ejercito->CurrentValue;
		$this->FP_Ejercito->ViewCustomAttributes = "";

		// FP_Policia
		$this->FP_Policia->ViewValue = $this->FP_Policia->CurrentValue;
		$this->FP_Policia->ViewCustomAttributes = "";

		// NOM_COMANDANTE
		$this->NOM_COMANDANTE->ViewValue = $this->NOM_COMANDANTE->CurrentValue;
		$this->NOM_COMANDANTE->ViewCustomAttributes = "";

		// TESTI1
		$this->TESTI1->ViewValue = $this->TESTI1->CurrentValue;
		$this->TESTI1->ViewCustomAttributes = "";

		// CC_TESTI1
		$this->CC_TESTI1->ViewValue = $this->CC_TESTI1->CurrentValue;
		$this->CC_TESTI1->ViewCustomAttributes = "";

		// CARGO_TESTI1
		$this->CARGO_TESTI1->ViewValue = $this->CARGO_TESTI1->CurrentValue;
		$this->CARGO_TESTI1->ViewCustomAttributes = "";

		// TESTI2
		$this->TESTI2->ViewValue = $this->TESTI2->CurrentValue;
		$this->TESTI2->ViewCustomAttributes = "";

		// CC_TESTI2
		$this->CC_TESTI2->ViewValue = $this->CC_TESTI2->CurrentValue;
		$this->CC_TESTI2->ViewCustomAttributes = "";

		// CARGO_TESTI2
		$this->CARGO_TESTI2->ViewValue = $this->CARGO_TESTI2->CurrentValue;
		$this->CARGO_TESTI2->ViewCustomAttributes = "";

		// Afectados
		$this->Afectados->ViewValue = $this->Afectados->CurrentValue;
		$this->Afectados->ViewCustomAttributes = "";

		// NUM_Afectado
		$this->NUM_Afectado->ViewValue = $this->NUM_Afectado->CurrentValue;
		$this->NUM_Afectado->ViewCustomAttributes = "";

		// Nom_Afectado
		$this->Nom_Afectado->ViewValue = $this->Nom_Afectado->CurrentValue;
		$this->Nom_Afectado->ViewCustomAttributes = "";

		// CC_Afectado
		$this->CC_Afectado->ViewValue = $this->CC_Afectado->CurrentValue;
		$this->CC_Afectado->ViewCustomAttributes = "";

		// Cargo_Afectado
		$this->Cargo_Afectado->ViewValue = $this->Cargo_Afectado->CurrentValue;
		$this->Cargo_Afectado->ViewCustomAttributes = "";

		// Tipo_incidente
		if (strval($this->Tipo_incidente->CurrentValue) <> "") {
			$sFilterWrk = "`Tipo_incidente`" . ew_SearchString("=", $this->Tipo_incidente->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Tipo_incidente, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Tipo_incidente` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Tipo_incidente->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Tipo_incidente->ViewValue = $this->Tipo_incidente->CurrentValue;
			}
		} else {
			$this->Tipo_incidente->ViewValue = NULL;
		}
		$this->Tipo_incidente->ViewCustomAttributes = "";

		// Parte_Cuerpo
		$this->Parte_Cuerpo->ViewValue = $this->Parte_Cuerpo->CurrentValue;
		$this->Parte_Cuerpo->ViewCustomAttributes = "";

		// ESTADO_AFEC
		$this->ESTADO_AFEC->ViewValue = $this->ESTADO_AFEC->CurrentValue;
		$this->ESTADO_AFEC->ViewCustomAttributes = "";

		// EVACUADO
		if (strval($this->EVACUADO->CurrentValue) <> "") {
			$sFilterWrk = "`EVACUADO`" . ew_SearchString("=", $this->EVACUADO->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT `EVACUADO`, `EVACUADO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `EVACUADO`, `EVACUADO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->EVACUADO, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `EVACUADO` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->EVACUADO->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->EVACUADO->ViewValue = $this->EVACUADO->CurrentValue;
			}
		} else {
			$this->EVACUADO->ViewValue = NULL;
		}
		$this->EVACUADO->ViewCustomAttributes = "";

		// DESC_ACC
		$this->DESC_ACC->ViewValue = $this->DESC_ACC->CurrentValue;
		$this->DESC_ACC->ViewCustomAttributes = "";

		// Modificado
		$this->Modificado->ViewValue = $this->Modificado->CurrentValue;
		$this->Modificado->ViewCustomAttributes = "";

		// llave_2
		$this->llave_2->ViewValue = $this->llave_2->CurrentValue;
		$this->llave_2->ViewCustomAttributes = "";

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

		// NOM_APOYO
		$this->NOM_APOYO->LinkCustomAttributes = "";
		$this->NOM_APOYO->HrefValue = "";
		$this->NOM_APOYO->TooltipValue = "";

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo->LinkCustomAttributes = "";
		$this->Otro_Nom_Apoyo->HrefValue = "";
		$this->Otro_Nom_Apoyo->TooltipValue = "";

		// Otro_CC_Apoyo
		$this->Otro_CC_Apoyo->LinkCustomAttributes = "";
		$this->Otro_CC_Apoyo->HrefValue = "";
		$this->Otro_CC_Apoyo->TooltipValue = "";

		// NOM_ENLACE
		$this->NOM_ENLACE->LinkCustomAttributes = "";
		$this->NOM_ENLACE->HrefValue = "";
		$this->NOM_ENLACE->TooltipValue = "";

		// Otro_Nom_Enlace
		$this->Otro_Nom_Enlace->LinkCustomAttributes = "";
		$this->Otro_Nom_Enlace->HrefValue = "";
		$this->Otro_Nom_Enlace->TooltipValue = "";

		// Otro_CC_Enlace
		$this->Otro_CC_Enlace->LinkCustomAttributes = "";
		$this->Otro_CC_Enlace->HrefValue = "";
		$this->Otro_CC_Enlace->TooltipValue = "";

		// NOM_PGE
		$this->NOM_PGE->LinkCustomAttributes = "";
		$this->NOM_PGE->HrefValue = "";
		$this->NOM_PGE->TooltipValue = "";

		// Otro_Nom_PGE
		$this->Otro_Nom_PGE->LinkCustomAttributes = "";
		$this->Otro_Nom_PGE->HrefValue = "";
		$this->Otro_Nom_PGE->TooltipValue = "";

		// Otro_CC_PGE
		$this->Otro_CC_PGE->LinkCustomAttributes = "";
		$this->Otro_CC_PGE->HrefValue = "";
		$this->Otro_CC_PGE->TooltipValue = "";

		// Departamento
		$this->Departamento->LinkCustomAttributes = "";
		$this->Departamento->HrefValue = "";
		$this->Departamento->TooltipValue = "";

		// Muncipio
		$this->Muncipio->LinkCustomAttributes = "";
		$this->Muncipio->HrefValue = "";
		$this->Muncipio->TooltipValue = "";

		// NOM_VDA
		$this->NOM_VDA->LinkCustomAttributes = "";
		$this->NOM_VDA->HrefValue = "";
		$this->NOM_VDA->TooltipValue = "";

		// LATITUD
		$this->LATITUD->LinkCustomAttributes = "";
		$this->LATITUD->HrefValue = "";
		$this->LATITUD->TooltipValue = "";

		// GRA_LAT
		$this->GRA_LAT->LinkCustomAttributes = "";
		$this->GRA_LAT->HrefValue = "";
		$this->GRA_LAT->TooltipValue = "";

		// MIN_LAT
		$this->MIN_LAT->LinkCustomAttributes = "";
		$this->MIN_LAT->HrefValue = "";
		$this->MIN_LAT->TooltipValue = "";

		// SEG_LAT
		$this->SEG_LAT->LinkCustomAttributes = "";
		$this->SEG_LAT->HrefValue = "";
		$this->SEG_LAT->TooltipValue = "";

		// GRA_LONG
		$this->GRA_LONG->LinkCustomAttributes = "";
		$this->GRA_LONG->HrefValue = "";
		$this->GRA_LONG->TooltipValue = "";

		// MIN_LONG
		$this->MIN_LONG->LinkCustomAttributes = "";
		$this->MIN_LONG->HrefValue = "";
		$this->MIN_LONG->TooltipValue = "";

		// SEG_LONG
		$this->SEG_LONG->LinkCustomAttributes = "";
		$this->SEG_LONG->HrefValue = "";
		$this->SEG_LONG->TooltipValue = "";

		// FECHA_ACC
		$this->FECHA_ACC->LinkCustomAttributes = "";
		$this->FECHA_ACC->HrefValue = "";
		$this->FECHA_ACC->TooltipValue = "";

		// HORA_ACC
		$this->HORA_ACC->LinkCustomAttributes = "";
		$this->HORA_ACC->HrefValue = "";
		$this->HORA_ACC->TooltipValue = "";

		// Hora_ingreso
		$this->Hora_ingreso->LinkCustomAttributes = "";
		$this->Hora_ingreso->HrefValue = "";
		$this->Hora_ingreso->TooltipValue = "";

		// FP_Armada
		$this->FP_Armada->LinkCustomAttributes = "";
		$this->FP_Armada->HrefValue = "";
		$this->FP_Armada->TooltipValue = "";

		// FP_Ejercito
		$this->FP_Ejercito->LinkCustomAttributes = "";
		$this->FP_Ejercito->HrefValue = "";
		$this->FP_Ejercito->TooltipValue = "";

		// FP_Policia
		$this->FP_Policia->LinkCustomAttributes = "";
		$this->FP_Policia->HrefValue = "";
		$this->FP_Policia->TooltipValue = "";

		// NOM_COMANDANTE
		$this->NOM_COMANDANTE->LinkCustomAttributes = "";
		$this->NOM_COMANDANTE->HrefValue = "";
		$this->NOM_COMANDANTE->TooltipValue = "";

		// TESTI1
		$this->TESTI1->LinkCustomAttributes = "";
		$this->TESTI1->HrefValue = "";
		$this->TESTI1->TooltipValue = "";

		// CC_TESTI1
		$this->CC_TESTI1->LinkCustomAttributes = "";
		$this->CC_TESTI1->HrefValue = "";
		$this->CC_TESTI1->TooltipValue = "";

		// CARGO_TESTI1
		$this->CARGO_TESTI1->LinkCustomAttributes = "";
		$this->CARGO_TESTI1->HrefValue = "";
		$this->CARGO_TESTI1->TooltipValue = "";

		// TESTI2
		$this->TESTI2->LinkCustomAttributes = "";
		$this->TESTI2->HrefValue = "";
		$this->TESTI2->TooltipValue = "";

		// CC_TESTI2
		$this->CC_TESTI2->LinkCustomAttributes = "";
		$this->CC_TESTI2->HrefValue = "";
		$this->CC_TESTI2->TooltipValue = "";

		// CARGO_TESTI2
		$this->CARGO_TESTI2->LinkCustomAttributes = "";
		$this->CARGO_TESTI2->HrefValue = "";
		$this->CARGO_TESTI2->TooltipValue = "";

		// Afectados
		$this->Afectados->LinkCustomAttributes = "";
		$this->Afectados->HrefValue = "";
		$this->Afectados->TooltipValue = "";

		// NUM_Afectado
		$this->NUM_Afectado->LinkCustomAttributes = "";
		$this->NUM_Afectado->HrefValue = "";
		$this->NUM_Afectado->TooltipValue = "";

		// Nom_Afectado
		$this->Nom_Afectado->LinkCustomAttributes = "";
		$this->Nom_Afectado->HrefValue = "";
		$this->Nom_Afectado->TooltipValue = "";

		// CC_Afectado
		$this->CC_Afectado->LinkCustomAttributes = "";
		$this->CC_Afectado->HrefValue = "";
		$this->CC_Afectado->TooltipValue = "";

		// Cargo_Afectado
		$this->Cargo_Afectado->LinkCustomAttributes = "";
		$this->Cargo_Afectado->HrefValue = "";
		$this->Cargo_Afectado->TooltipValue = "";

		// Tipo_incidente
		$this->Tipo_incidente->LinkCustomAttributes = "";
		$this->Tipo_incidente->HrefValue = "";
		$this->Tipo_incidente->TooltipValue = "";

		// Parte_Cuerpo
		$this->Parte_Cuerpo->LinkCustomAttributes = "";
		$this->Parte_Cuerpo->HrefValue = "";
		$this->Parte_Cuerpo->TooltipValue = "";

		// ESTADO_AFEC
		$this->ESTADO_AFEC->LinkCustomAttributes = "";
		$this->ESTADO_AFEC->HrefValue = "";
		$this->ESTADO_AFEC->TooltipValue = "";

		// EVACUADO
		$this->EVACUADO->LinkCustomAttributes = "";
		$this->EVACUADO->HrefValue = "";
		$this->EVACUADO->TooltipValue = "";

		// DESC_ACC
		$this->DESC_ACC->LinkCustomAttributes = "";
		$this->DESC_ACC->HrefValue = "";
		$this->DESC_ACC->TooltipValue = "";

		// Modificado
		$this->Modificado->LinkCustomAttributes = "";
		$this->Modificado->HrefValue = "";
		$this->Modificado->TooltipValue = "";

		// llave_2
		$this->llave_2->LinkCustomAttributes = "";
		$this->llave_2->HrefValue = "";
		$this->llave_2->TooltipValue = "";

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

		// F_Sincron
		$this->F_Sincron->EditAttrs["class"] = "form-control";
		$this->F_Sincron->EditCustomAttributes = "";
		$this->F_Sincron->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->F_Sincron->CurrentValue, 7));
		$this->F_Sincron->PlaceHolder = ew_RemoveHtml($this->F_Sincron->FldCaption());

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

		// NOM_PE
		$this->NOM_PE->EditAttrs["class"] = "form-control";
		$this->NOM_PE->EditCustomAttributes = "";

		// Otro_PE
		$this->Otro_PE->EditAttrs["class"] = "form-control";
		$this->Otro_PE->EditCustomAttributes = "";
		$this->Otro_PE->EditValue = ew_HtmlEncode($this->Otro_PE->CurrentValue);
		$this->Otro_PE->PlaceHolder = ew_RemoveHtml($this->Otro_PE->FldCaption());

		// NOM_APOYO
		$this->NOM_APOYO->EditAttrs["class"] = "form-control";
		$this->NOM_APOYO->EditCustomAttributes = "";
		$this->NOM_APOYO->EditValue = ew_HtmlEncode($this->NOM_APOYO->CurrentValue);
		$this->NOM_APOYO->PlaceHolder = ew_RemoveHtml($this->NOM_APOYO->FldCaption());

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo->EditAttrs["class"] = "form-control";
		$this->Otro_Nom_Apoyo->EditCustomAttributes = "";
		$this->Otro_Nom_Apoyo->EditValue = ew_HtmlEncode($this->Otro_Nom_Apoyo->CurrentValue);
		$this->Otro_Nom_Apoyo->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Apoyo->FldCaption());

		// Otro_CC_Apoyo
		$this->Otro_CC_Apoyo->EditAttrs["class"] = "form-control";
		$this->Otro_CC_Apoyo->EditCustomAttributes = "";
		$this->Otro_CC_Apoyo->EditValue = ew_HtmlEncode($this->Otro_CC_Apoyo->CurrentValue);
		$this->Otro_CC_Apoyo->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Apoyo->FldCaption());

		// NOM_ENLACE
		$this->NOM_ENLACE->EditAttrs["class"] = "form-control";
		$this->NOM_ENLACE->EditCustomAttributes = "";
		$this->NOM_ENLACE->EditValue = ew_HtmlEncode($this->NOM_ENLACE->CurrentValue);
		$this->NOM_ENLACE->PlaceHolder = ew_RemoveHtml($this->NOM_ENLACE->FldCaption());

		// Otro_Nom_Enlace
		$this->Otro_Nom_Enlace->EditAttrs["class"] = "form-control";
		$this->Otro_Nom_Enlace->EditCustomAttributes = "";
		$this->Otro_Nom_Enlace->EditValue = ew_HtmlEncode($this->Otro_Nom_Enlace->CurrentValue);
		$this->Otro_Nom_Enlace->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Enlace->FldCaption());

		// Otro_CC_Enlace
		$this->Otro_CC_Enlace->EditAttrs["class"] = "form-control";
		$this->Otro_CC_Enlace->EditCustomAttributes = "";
		$this->Otro_CC_Enlace->EditValue = ew_HtmlEncode($this->Otro_CC_Enlace->CurrentValue);
		$this->Otro_CC_Enlace->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Enlace->FldCaption());

		// NOM_PGE
		$this->NOM_PGE->EditAttrs["class"] = "form-control";
		$this->NOM_PGE->EditCustomAttributes = "";

		// Otro_Nom_PGE
		$this->Otro_Nom_PGE->EditAttrs["class"] = "form-control";
		$this->Otro_Nom_PGE->EditCustomAttributes = "";
		$this->Otro_Nom_PGE->EditValue = ew_HtmlEncode($this->Otro_Nom_PGE->CurrentValue);
		$this->Otro_Nom_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_PGE->FldCaption());

		// Otro_CC_PGE
		$this->Otro_CC_PGE->EditAttrs["class"] = "form-control";
		$this->Otro_CC_PGE->EditCustomAttributes = "";
		$this->Otro_CC_PGE->EditValue = ew_HtmlEncode($this->Otro_CC_PGE->CurrentValue);
		$this->Otro_CC_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_CC_PGE->FldCaption());

		// Departamento
		$this->Departamento->EditAttrs["class"] = "form-control";
		$this->Departamento->EditCustomAttributes = "";
		$this->Departamento->EditValue = ew_HtmlEncode($this->Departamento->CurrentValue);
		$this->Departamento->PlaceHolder = ew_RemoveHtml($this->Departamento->FldCaption());

		// Muncipio
		$this->Muncipio->EditAttrs["class"] = "form-control";
		$this->Muncipio->EditCustomAttributes = "";
		$this->Muncipio->EditValue = ew_HtmlEncode($this->Muncipio->CurrentValue);
		$this->Muncipio->PlaceHolder = ew_RemoveHtml($this->Muncipio->FldCaption());

		// NOM_VDA
		$this->NOM_VDA->EditAttrs["class"] = "form-control";
		$this->NOM_VDA->EditCustomAttributes = "";
		$this->NOM_VDA->EditValue = ew_HtmlEncode($this->NOM_VDA->CurrentValue);
		$this->NOM_VDA->PlaceHolder = ew_RemoveHtml($this->NOM_VDA->FldCaption());

		// LATITUD
		$this->LATITUD->EditAttrs["class"] = "form-control";
		$this->LATITUD->EditCustomAttributes = "";
		$this->LATITUD->EditValue = ew_HtmlEncode($this->LATITUD->CurrentValue);
		$this->LATITUD->PlaceHolder = ew_RemoveHtml($this->LATITUD->FldCaption());

		// GRA_LAT
		$this->GRA_LAT->EditAttrs["class"] = "form-control";
		$this->GRA_LAT->EditCustomAttributes = "";
		$this->GRA_LAT->EditValue = ew_HtmlEncode($this->GRA_LAT->CurrentValue);
		$this->GRA_LAT->PlaceHolder = ew_RemoveHtml($this->GRA_LAT->FldCaption());

		// MIN_LAT
		$this->MIN_LAT->EditAttrs["class"] = "form-control";
		$this->MIN_LAT->EditCustomAttributes = "";
		$this->MIN_LAT->EditValue = ew_HtmlEncode($this->MIN_LAT->CurrentValue);
		$this->MIN_LAT->PlaceHolder = ew_RemoveHtml($this->MIN_LAT->FldCaption());

		// SEG_LAT
		$this->SEG_LAT->EditAttrs["class"] = "form-control";
		$this->SEG_LAT->EditCustomAttributes = "";
		$this->SEG_LAT->EditValue = ew_HtmlEncode($this->SEG_LAT->CurrentValue);
		$this->SEG_LAT->PlaceHolder = ew_RemoveHtml($this->SEG_LAT->FldCaption());
		if (strval($this->SEG_LAT->EditValue) <> "" && is_numeric($this->SEG_LAT->EditValue)) $this->SEG_LAT->EditValue = ew_FormatNumber($this->SEG_LAT->EditValue, -2, -1, -2, 0);

		// GRA_LONG
		$this->GRA_LONG->EditAttrs["class"] = "form-control";
		$this->GRA_LONG->EditCustomAttributes = "";
		$this->GRA_LONG->EditValue = ew_HtmlEncode($this->GRA_LONG->CurrentValue);
		$this->GRA_LONG->PlaceHolder = ew_RemoveHtml($this->GRA_LONG->FldCaption());

		// MIN_LONG
		$this->MIN_LONG->EditAttrs["class"] = "form-control";
		$this->MIN_LONG->EditCustomAttributes = "";
		$this->MIN_LONG->EditValue = ew_HtmlEncode($this->MIN_LONG->CurrentValue);
		$this->MIN_LONG->PlaceHolder = ew_RemoveHtml($this->MIN_LONG->FldCaption());

		// SEG_LONG
		$this->SEG_LONG->EditAttrs["class"] = "form-control";
		$this->SEG_LONG->EditCustomAttributes = "";
		$this->SEG_LONG->EditValue = ew_HtmlEncode($this->SEG_LONG->CurrentValue);
		$this->SEG_LONG->PlaceHolder = ew_RemoveHtml($this->SEG_LONG->FldCaption());
		if (strval($this->SEG_LONG->EditValue) <> "" && is_numeric($this->SEG_LONG->EditValue)) $this->SEG_LONG->EditValue = ew_FormatNumber($this->SEG_LONG->EditValue, -2, -1, -2, 0);

		// FECHA_ACC
		$this->FECHA_ACC->EditAttrs["class"] = "form-control";
		$this->FECHA_ACC->EditCustomAttributes = "";
		$this->FECHA_ACC->EditValue = ew_HtmlEncode($this->FECHA_ACC->CurrentValue);
		$this->FECHA_ACC->PlaceHolder = ew_RemoveHtml($this->FECHA_ACC->FldCaption());

		// HORA_ACC
		$this->HORA_ACC->EditAttrs["class"] = "form-control";
		$this->HORA_ACC->EditCustomAttributes = "";
		$this->HORA_ACC->EditValue = ew_HtmlEncode($this->HORA_ACC->CurrentValue);
		$this->HORA_ACC->PlaceHolder = ew_RemoveHtml($this->HORA_ACC->FldCaption());

		// Hora_ingreso
		$this->Hora_ingreso->EditAttrs["class"] = "form-control";
		$this->Hora_ingreso->EditCustomAttributes = "";
		$this->Hora_ingreso->EditValue = ew_HtmlEncode($this->Hora_ingreso->CurrentValue);
		$this->Hora_ingreso->PlaceHolder = ew_RemoveHtml($this->Hora_ingreso->FldCaption());

		// FP_Armada
		$this->FP_Armada->EditAttrs["class"] = "form-control";
		$this->FP_Armada->EditCustomAttributes = "";
		$this->FP_Armada->EditValue = ew_HtmlEncode($this->FP_Armada->CurrentValue);
		$this->FP_Armada->PlaceHolder = ew_RemoveHtml($this->FP_Armada->FldCaption());
		if (strval($this->FP_Armada->EditValue) <> "" && is_numeric($this->FP_Armada->EditValue)) $this->FP_Armada->EditValue = ew_FormatNumber($this->FP_Armada->EditValue, -2, -1, -2, 0);

		// FP_Ejercito
		$this->FP_Ejercito->EditAttrs["class"] = "form-control";
		$this->FP_Ejercito->EditCustomAttributes = "";
		$this->FP_Ejercito->EditValue = ew_HtmlEncode($this->FP_Ejercito->CurrentValue);
		$this->FP_Ejercito->PlaceHolder = ew_RemoveHtml($this->FP_Ejercito->FldCaption());
		if (strval($this->FP_Ejercito->EditValue) <> "" && is_numeric($this->FP_Ejercito->EditValue)) $this->FP_Ejercito->EditValue = ew_FormatNumber($this->FP_Ejercito->EditValue, -2, -1, -2, 0);

		// FP_Policia
		$this->FP_Policia->EditAttrs["class"] = "form-control";
		$this->FP_Policia->EditCustomAttributes = "";
		$this->FP_Policia->EditValue = ew_HtmlEncode($this->FP_Policia->CurrentValue);
		$this->FP_Policia->PlaceHolder = ew_RemoveHtml($this->FP_Policia->FldCaption());
		if (strval($this->FP_Policia->EditValue) <> "" && is_numeric($this->FP_Policia->EditValue)) $this->FP_Policia->EditValue = ew_FormatNumber($this->FP_Policia->EditValue, -2, -1, -2, 0);

		// NOM_COMANDANTE
		$this->NOM_COMANDANTE->EditAttrs["class"] = "form-control";
		$this->NOM_COMANDANTE->EditCustomAttributes = "";
		$this->NOM_COMANDANTE->EditValue = ew_HtmlEncode($this->NOM_COMANDANTE->CurrentValue);
		$this->NOM_COMANDANTE->PlaceHolder = ew_RemoveHtml($this->NOM_COMANDANTE->FldCaption());

		// TESTI1
		$this->TESTI1->EditAttrs["class"] = "form-control";
		$this->TESTI1->EditCustomAttributes = "";
		$this->TESTI1->EditValue = ew_HtmlEncode($this->TESTI1->CurrentValue);
		$this->TESTI1->PlaceHolder = ew_RemoveHtml($this->TESTI1->FldCaption());

		// CC_TESTI1
		$this->CC_TESTI1->EditAttrs["class"] = "form-control";
		$this->CC_TESTI1->EditCustomAttributes = "";
		$this->CC_TESTI1->EditValue = ew_HtmlEncode($this->CC_TESTI1->CurrentValue);
		$this->CC_TESTI1->PlaceHolder = ew_RemoveHtml($this->CC_TESTI1->FldCaption());

		// CARGO_TESTI1
		$this->CARGO_TESTI1->EditAttrs["class"] = "form-control";
		$this->CARGO_TESTI1->EditCustomAttributes = "";
		$this->CARGO_TESTI1->EditValue = ew_HtmlEncode($this->CARGO_TESTI1->CurrentValue);
		$this->CARGO_TESTI1->PlaceHolder = ew_RemoveHtml($this->CARGO_TESTI1->FldCaption());

		// TESTI2
		$this->TESTI2->EditAttrs["class"] = "form-control";
		$this->TESTI2->EditCustomAttributes = "";
		$this->TESTI2->EditValue = ew_HtmlEncode($this->TESTI2->CurrentValue);
		$this->TESTI2->PlaceHolder = ew_RemoveHtml($this->TESTI2->FldCaption());

		// CC_TESTI2
		$this->CC_TESTI2->EditAttrs["class"] = "form-control";
		$this->CC_TESTI2->EditCustomAttributes = "";
		$this->CC_TESTI2->EditValue = ew_HtmlEncode($this->CC_TESTI2->CurrentValue);
		$this->CC_TESTI2->PlaceHolder = ew_RemoveHtml($this->CC_TESTI2->FldCaption());

		// CARGO_TESTI2
		$this->CARGO_TESTI2->EditAttrs["class"] = "form-control";
		$this->CARGO_TESTI2->EditCustomAttributes = "";
		$this->CARGO_TESTI2->EditValue = ew_HtmlEncode($this->CARGO_TESTI2->CurrentValue);
		$this->CARGO_TESTI2->PlaceHolder = ew_RemoveHtml($this->CARGO_TESTI2->FldCaption());

		// Afectados
		$this->Afectados->EditAttrs["class"] = "form-control";
		$this->Afectados->EditCustomAttributes = "";
		$this->Afectados->EditValue = ew_HtmlEncode($this->Afectados->CurrentValue);
		$this->Afectados->PlaceHolder = ew_RemoveHtml($this->Afectados->FldCaption());

		// NUM_Afectado
		$this->NUM_Afectado->EditAttrs["class"] = "form-control";
		$this->NUM_Afectado->EditCustomAttributes = "";
		$this->NUM_Afectado->EditValue = ew_HtmlEncode($this->NUM_Afectado->CurrentValue);
		$this->NUM_Afectado->PlaceHolder = ew_RemoveHtml($this->NUM_Afectado->FldCaption());

		// Nom_Afectado
		$this->Nom_Afectado->EditAttrs["class"] = "form-control";
		$this->Nom_Afectado->EditCustomAttributes = "";
		$this->Nom_Afectado->EditValue = ew_HtmlEncode($this->Nom_Afectado->CurrentValue);
		$this->Nom_Afectado->PlaceHolder = ew_RemoveHtml($this->Nom_Afectado->FldCaption());

		// CC_Afectado
		$this->CC_Afectado->EditAttrs["class"] = "form-control";
		$this->CC_Afectado->EditCustomAttributes = "";
		$this->CC_Afectado->EditValue = ew_HtmlEncode($this->CC_Afectado->CurrentValue);
		$this->CC_Afectado->PlaceHolder = ew_RemoveHtml($this->CC_Afectado->FldCaption());

		// Cargo_Afectado
		$this->Cargo_Afectado->EditAttrs["class"] = "form-control";
		$this->Cargo_Afectado->EditCustomAttributes = "";
		$this->Cargo_Afectado->EditValue = ew_HtmlEncode($this->Cargo_Afectado->CurrentValue);
		$this->Cargo_Afectado->PlaceHolder = ew_RemoveHtml($this->Cargo_Afectado->FldCaption());

		// Tipo_incidente
		$this->Tipo_incidente->EditAttrs["class"] = "form-control";
		$this->Tipo_incidente->EditCustomAttributes = "";

		// Parte_Cuerpo
		$this->Parte_Cuerpo->EditAttrs["class"] = "form-control";
		$this->Parte_Cuerpo->EditCustomAttributes = "";
		$this->Parte_Cuerpo->EditValue = ew_HtmlEncode($this->Parte_Cuerpo->CurrentValue);
		$this->Parte_Cuerpo->PlaceHolder = ew_RemoveHtml($this->Parte_Cuerpo->FldCaption());

		// ESTADO_AFEC
		$this->ESTADO_AFEC->EditAttrs["class"] = "form-control";
		$this->ESTADO_AFEC->EditCustomAttributes = "";
		$this->ESTADO_AFEC->EditValue = ew_HtmlEncode($this->ESTADO_AFEC->CurrentValue);
		$this->ESTADO_AFEC->PlaceHolder = ew_RemoveHtml($this->ESTADO_AFEC->FldCaption());

		// EVACUADO
		$this->EVACUADO->EditAttrs["class"] = "form-control";
		$this->EVACUADO->EditCustomAttributes = "";

		// DESC_ACC
		$this->DESC_ACC->EditAttrs["class"] = "form-control";
		$this->DESC_ACC->EditCustomAttributes = "";
		$this->DESC_ACC->EditValue = ew_HtmlEncode($this->DESC_ACC->CurrentValue);
		$this->DESC_ACC->PlaceHolder = ew_RemoveHtml($this->DESC_ACC->FldCaption());

		// Modificado
		$this->Modificado->EditAttrs["class"] = "form-control";
		$this->Modificado->EditCustomAttributes = "";
		$this->Modificado->EditValue = ew_HtmlEncode($this->Modificado->CurrentValue);
		$this->Modificado->PlaceHolder = ew_RemoveHtml($this->Modificado->FldCaption());

		// llave_2
		$this->llave_2->EditAttrs["class"] = "form-control";
		$this->llave_2->EditCustomAttributes = "";
		$this->llave_2->EditValue = $this->llave_2->CurrentValue;
		$this->llave_2->ViewCustomAttributes = "";

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
					if ($this->NOM_APOYO->Exportable) $Doc->ExportCaption($this->NOM_APOYO);
					if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_Nom_Apoyo);
					if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_CC_Apoyo);
					if ($this->NOM_ENLACE->Exportable) $Doc->ExportCaption($this->NOM_ENLACE);
					if ($this->Otro_Nom_Enlace->Exportable) $Doc->ExportCaption($this->Otro_Nom_Enlace);
					if ($this->Otro_CC_Enlace->Exportable) $Doc->ExportCaption($this->Otro_CC_Enlace);
					if ($this->NOM_PGE->Exportable) $Doc->ExportCaption($this->NOM_PGE);
					if ($this->Otro_Nom_PGE->Exportable) $Doc->ExportCaption($this->Otro_Nom_PGE);
					if ($this->Otro_CC_PGE->Exportable) $Doc->ExportCaption($this->Otro_CC_PGE);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->NOM_VDA->Exportable) $Doc->ExportCaption($this->NOM_VDA);
					if ($this->LATITUD->Exportable) $Doc->ExportCaption($this->LATITUD);
					if ($this->GRA_LAT->Exportable) $Doc->ExportCaption($this->GRA_LAT);
					if ($this->MIN_LAT->Exportable) $Doc->ExportCaption($this->MIN_LAT);
					if ($this->SEG_LAT->Exportable) $Doc->ExportCaption($this->SEG_LAT);
					if ($this->GRA_LONG->Exportable) $Doc->ExportCaption($this->GRA_LONG);
					if ($this->MIN_LONG->Exportable) $Doc->ExportCaption($this->MIN_LONG);
					if ($this->SEG_LONG->Exportable) $Doc->ExportCaption($this->SEG_LONG);
					if ($this->FECHA_ACC->Exportable) $Doc->ExportCaption($this->FECHA_ACC);
					if ($this->HORA_ACC->Exportable) $Doc->ExportCaption($this->HORA_ACC);
					if ($this->Hora_ingreso->Exportable) $Doc->ExportCaption($this->Hora_ingreso);
					if ($this->FP_Armada->Exportable) $Doc->ExportCaption($this->FP_Armada);
					if ($this->FP_Ejercito->Exportable) $Doc->ExportCaption($this->FP_Ejercito);
					if ($this->FP_Policia->Exportable) $Doc->ExportCaption($this->FP_Policia);
					if ($this->NOM_COMANDANTE->Exportable) $Doc->ExportCaption($this->NOM_COMANDANTE);
					if ($this->TESTI1->Exportable) $Doc->ExportCaption($this->TESTI1);
					if ($this->CC_TESTI1->Exportable) $Doc->ExportCaption($this->CC_TESTI1);
					if ($this->CARGO_TESTI1->Exportable) $Doc->ExportCaption($this->CARGO_TESTI1);
					if ($this->TESTI2->Exportable) $Doc->ExportCaption($this->TESTI2);
					if ($this->CC_TESTI2->Exportable) $Doc->ExportCaption($this->CC_TESTI2);
					if ($this->CARGO_TESTI2->Exportable) $Doc->ExportCaption($this->CARGO_TESTI2);
					if ($this->Afectados->Exportable) $Doc->ExportCaption($this->Afectados);
					if ($this->NUM_Afectado->Exportable) $Doc->ExportCaption($this->NUM_Afectado);
					if ($this->Nom_Afectado->Exportable) $Doc->ExportCaption($this->Nom_Afectado);
					if ($this->CC_Afectado->Exportable) $Doc->ExportCaption($this->CC_Afectado);
					if ($this->Cargo_Afectado->Exportable) $Doc->ExportCaption($this->Cargo_Afectado);
					if ($this->Tipo_incidente->Exportable) $Doc->ExportCaption($this->Tipo_incidente);
					if ($this->Parte_Cuerpo->Exportable) $Doc->ExportCaption($this->Parte_Cuerpo);
					if ($this->ESTADO_AFEC->Exportable) $Doc->ExportCaption($this->ESTADO_AFEC);
					if ($this->EVACUADO->Exportable) $Doc->ExportCaption($this->EVACUADO);
					if ($this->DESC_ACC->Exportable) $Doc->ExportCaption($this->DESC_ACC);
					if ($this->Modificado->Exportable) $Doc->ExportCaption($this->Modificado);
				} else {
					if ($this->llave->Exportable) $Doc->ExportCaption($this->llave);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
					if ($this->USUARIO->Exportable) $Doc->ExportCaption($this->USUARIO);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_PE->Exportable) $Doc->ExportCaption($this->Otro_PE);
					if ($this->NOM_APOYO->Exportable) $Doc->ExportCaption($this->NOM_APOYO);
					if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_Nom_Apoyo);
					if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_CC_Apoyo);
					if ($this->NOM_ENLACE->Exportable) $Doc->ExportCaption($this->NOM_ENLACE);
					if ($this->Otro_Nom_Enlace->Exportable) $Doc->ExportCaption($this->Otro_Nom_Enlace);
					if ($this->Otro_CC_Enlace->Exportable) $Doc->ExportCaption($this->Otro_CC_Enlace);
					if ($this->NOM_PGE->Exportable) $Doc->ExportCaption($this->NOM_PGE);
					if ($this->Otro_Nom_PGE->Exportable) $Doc->ExportCaption($this->Otro_Nom_PGE);
					if ($this->Otro_CC_PGE->Exportable) $Doc->ExportCaption($this->Otro_CC_PGE);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->NOM_VDA->Exportable) $Doc->ExportCaption($this->NOM_VDA);
					if ($this->LATITUD->Exportable) $Doc->ExportCaption($this->LATITUD);
					if ($this->GRA_LAT->Exportable) $Doc->ExportCaption($this->GRA_LAT);
					if ($this->MIN_LAT->Exportable) $Doc->ExportCaption($this->MIN_LAT);
					if ($this->SEG_LAT->Exportable) $Doc->ExportCaption($this->SEG_LAT);
					if ($this->GRA_LONG->Exportable) $Doc->ExportCaption($this->GRA_LONG);
					if ($this->MIN_LONG->Exportable) $Doc->ExportCaption($this->MIN_LONG);
					if ($this->SEG_LONG->Exportable) $Doc->ExportCaption($this->SEG_LONG);
					if ($this->FECHA_ACC->Exportable) $Doc->ExportCaption($this->FECHA_ACC);
					if ($this->HORA_ACC->Exportable) $Doc->ExportCaption($this->HORA_ACC);
					if ($this->Hora_ingreso->Exportable) $Doc->ExportCaption($this->Hora_ingreso);
					if ($this->FP_Armada->Exportable) $Doc->ExportCaption($this->FP_Armada);
					if ($this->FP_Ejercito->Exportable) $Doc->ExportCaption($this->FP_Ejercito);
					if ($this->FP_Policia->Exportable) $Doc->ExportCaption($this->FP_Policia);
					if ($this->NOM_COMANDANTE->Exportable) $Doc->ExportCaption($this->NOM_COMANDANTE);
					if ($this->TESTI1->Exportable) $Doc->ExportCaption($this->TESTI1);
					if ($this->CC_TESTI1->Exportable) $Doc->ExportCaption($this->CC_TESTI1);
					if ($this->CARGO_TESTI1->Exportable) $Doc->ExportCaption($this->CARGO_TESTI1);
					if ($this->TESTI2->Exportable) $Doc->ExportCaption($this->TESTI2);
					if ($this->CC_TESTI2->Exportable) $Doc->ExportCaption($this->CC_TESTI2);
					if ($this->CARGO_TESTI2->Exportable) $Doc->ExportCaption($this->CARGO_TESTI2);
					if ($this->Afectados->Exportable) $Doc->ExportCaption($this->Afectados);
					if ($this->NUM_Afectado->Exportable) $Doc->ExportCaption($this->NUM_Afectado);
					if ($this->Nom_Afectado->Exportable) $Doc->ExportCaption($this->Nom_Afectado);
					if ($this->CC_Afectado->Exportable) $Doc->ExportCaption($this->CC_Afectado);
					if ($this->Cargo_Afectado->Exportable) $Doc->ExportCaption($this->Cargo_Afectado);
					if ($this->Tipo_incidente->Exportable) $Doc->ExportCaption($this->Tipo_incidente);
					if ($this->Parte_Cuerpo->Exportable) $Doc->ExportCaption($this->Parte_Cuerpo);
					if ($this->ESTADO_AFEC->Exportable) $Doc->ExportCaption($this->ESTADO_AFEC);
					if ($this->EVACUADO->Exportable) $Doc->ExportCaption($this->EVACUADO);
					if ($this->DESC_ACC->Exportable) $Doc->ExportCaption($this->DESC_ACC);
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
						if ($this->NOM_APOYO->Exportable) $Doc->ExportField($this->NOM_APOYO);
						if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportField($this->Otro_Nom_Apoyo);
						if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportField($this->Otro_CC_Apoyo);
						if ($this->NOM_ENLACE->Exportable) $Doc->ExportField($this->NOM_ENLACE);
						if ($this->Otro_Nom_Enlace->Exportable) $Doc->ExportField($this->Otro_Nom_Enlace);
						if ($this->Otro_CC_Enlace->Exportable) $Doc->ExportField($this->Otro_CC_Enlace);
						if ($this->NOM_PGE->Exportable) $Doc->ExportField($this->NOM_PGE);
						if ($this->Otro_Nom_PGE->Exportable) $Doc->ExportField($this->Otro_Nom_PGE);
						if ($this->Otro_CC_PGE->Exportable) $Doc->ExportField($this->Otro_CC_PGE);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->NOM_VDA->Exportable) $Doc->ExportField($this->NOM_VDA);
						if ($this->LATITUD->Exportable) $Doc->ExportField($this->LATITUD);
						if ($this->GRA_LAT->Exportable) $Doc->ExportField($this->GRA_LAT);
						if ($this->MIN_LAT->Exportable) $Doc->ExportField($this->MIN_LAT);
						if ($this->SEG_LAT->Exportable) $Doc->ExportField($this->SEG_LAT);
						if ($this->GRA_LONG->Exportable) $Doc->ExportField($this->GRA_LONG);
						if ($this->MIN_LONG->Exportable) $Doc->ExportField($this->MIN_LONG);
						if ($this->SEG_LONG->Exportable) $Doc->ExportField($this->SEG_LONG);
						if ($this->FECHA_ACC->Exportable) $Doc->ExportField($this->FECHA_ACC);
						if ($this->HORA_ACC->Exportable) $Doc->ExportField($this->HORA_ACC);
						if ($this->Hora_ingreso->Exportable) $Doc->ExportField($this->Hora_ingreso);
						if ($this->FP_Armada->Exportable) $Doc->ExportField($this->FP_Armada);
						if ($this->FP_Ejercito->Exportable) $Doc->ExportField($this->FP_Ejercito);
						if ($this->FP_Policia->Exportable) $Doc->ExportField($this->FP_Policia);
						if ($this->NOM_COMANDANTE->Exportable) $Doc->ExportField($this->NOM_COMANDANTE);
						if ($this->TESTI1->Exportable) $Doc->ExportField($this->TESTI1);
						if ($this->CC_TESTI1->Exportable) $Doc->ExportField($this->CC_TESTI1);
						if ($this->CARGO_TESTI1->Exportable) $Doc->ExportField($this->CARGO_TESTI1);
						if ($this->TESTI2->Exportable) $Doc->ExportField($this->TESTI2);
						if ($this->CC_TESTI2->Exportable) $Doc->ExportField($this->CC_TESTI2);
						if ($this->CARGO_TESTI2->Exportable) $Doc->ExportField($this->CARGO_TESTI2);
						if ($this->Afectados->Exportable) $Doc->ExportField($this->Afectados);
						if ($this->NUM_Afectado->Exportable) $Doc->ExportField($this->NUM_Afectado);
						if ($this->Nom_Afectado->Exportable) $Doc->ExportField($this->Nom_Afectado);
						if ($this->CC_Afectado->Exportable) $Doc->ExportField($this->CC_Afectado);
						if ($this->Cargo_Afectado->Exportable) $Doc->ExportField($this->Cargo_Afectado);
						if ($this->Tipo_incidente->Exportable) $Doc->ExportField($this->Tipo_incidente);
						if ($this->Parte_Cuerpo->Exportable) $Doc->ExportField($this->Parte_Cuerpo);
						if ($this->ESTADO_AFEC->Exportable) $Doc->ExportField($this->ESTADO_AFEC);
						if ($this->EVACUADO->Exportable) $Doc->ExportField($this->EVACUADO);
						if ($this->DESC_ACC->Exportable) $Doc->ExportField($this->DESC_ACC);
						if ($this->Modificado->Exportable) $Doc->ExportField($this->Modificado);
					} else {
						if ($this->llave->Exportable) $Doc->ExportField($this->llave);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
						if ($this->USUARIO->Exportable) $Doc->ExportField($this->USUARIO);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_PE->Exportable) $Doc->ExportField($this->Otro_PE);
						if ($this->NOM_APOYO->Exportable) $Doc->ExportField($this->NOM_APOYO);
						if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportField($this->Otro_Nom_Apoyo);
						if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportField($this->Otro_CC_Apoyo);
						if ($this->NOM_ENLACE->Exportable) $Doc->ExportField($this->NOM_ENLACE);
						if ($this->Otro_Nom_Enlace->Exportable) $Doc->ExportField($this->Otro_Nom_Enlace);
						if ($this->Otro_CC_Enlace->Exportable) $Doc->ExportField($this->Otro_CC_Enlace);
						if ($this->NOM_PGE->Exportable) $Doc->ExportField($this->NOM_PGE);
						if ($this->Otro_Nom_PGE->Exportable) $Doc->ExportField($this->Otro_Nom_PGE);
						if ($this->Otro_CC_PGE->Exportable) $Doc->ExportField($this->Otro_CC_PGE);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->NOM_VDA->Exportable) $Doc->ExportField($this->NOM_VDA);
						if ($this->LATITUD->Exportable) $Doc->ExportField($this->LATITUD);
						if ($this->GRA_LAT->Exportable) $Doc->ExportField($this->GRA_LAT);
						if ($this->MIN_LAT->Exportable) $Doc->ExportField($this->MIN_LAT);
						if ($this->SEG_LAT->Exportable) $Doc->ExportField($this->SEG_LAT);
						if ($this->GRA_LONG->Exportable) $Doc->ExportField($this->GRA_LONG);
						if ($this->MIN_LONG->Exportable) $Doc->ExportField($this->MIN_LONG);
						if ($this->SEG_LONG->Exportable) $Doc->ExportField($this->SEG_LONG);
						if ($this->FECHA_ACC->Exportable) $Doc->ExportField($this->FECHA_ACC);
						if ($this->HORA_ACC->Exportable) $Doc->ExportField($this->HORA_ACC);
						if ($this->Hora_ingreso->Exportable) $Doc->ExportField($this->Hora_ingreso);
						if ($this->FP_Armada->Exportable) $Doc->ExportField($this->FP_Armada);
						if ($this->FP_Ejercito->Exportable) $Doc->ExportField($this->FP_Ejercito);
						if ($this->FP_Policia->Exportable) $Doc->ExportField($this->FP_Policia);
						if ($this->NOM_COMANDANTE->Exportable) $Doc->ExportField($this->NOM_COMANDANTE);
						if ($this->TESTI1->Exportable) $Doc->ExportField($this->TESTI1);
						if ($this->CC_TESTI1->Exportable) $Doc->ExportField($this->CC_TESTI1);
						if ($this->CARGO_TESTI1->Exportable) $Doc->ExportField($this->CARGO_TESTI1);
						if ($this->TESTI2->Exportable) $Doc->ExportField($this->TESTI2);
						if ($this->CC_TESTI2->Exportable) $Doc->ExportField($this->CC_TESTI2);
						if ($this->CARGO_TESTI2->Exportable) $Doc->ExportField($this->CARGO_TESTI2);
						if ($this->Afectados->Exportable) $Doc->ExportField($this->Afectados);
						if ($this->NUM_Afectado->Exportable) $Doc->ExportField($this->NUM_Afectado);
						if ($this->Nom_Afectado->Exportable) $Doc->ExportField($this->Nom_Afectado);
						if ($this->CC_Afectado->Exportable) $Doc->ExportField($this->CC_Afectado);
						if ($this->Cargo_Afectado->Exportable) $Doc->ExportField($this->Cargo_Afectado);
						if ($this->Tipo_incidente->Exportable) $Doc->ExportField($this->Tipo_incidente);
						if ($this->Parte_Cuerpo->Exportable) $Doc->ExportField($this->Parte_Cuerpo);
						if ($this->ESTADO_AFEC->Exportable) $Doc->ExportField($this->ESTADO_AFEC);
						if ($this->EVACUADO->Exportable) $Doc->ExportField($this->EVACUADO);
						if ($this->DESC_ACC->Exportable) $Doc->ExportField($this->DESC_ACC);
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
