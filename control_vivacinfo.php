<?php

// Global variable for table object
$control_vivac = NULL;

//
// Table class for control_vivac
//
class ccontrol_vivac extends cTable {
	var $llave;
	var $USUARIO;
	var $Cargo_gme;
	var $Num_AV;
	var $NOM_APOYO;
	var $Otro_Nom_Apoyo;
	var $Otro_CC_Apoyo;
	var $NOM_PE;
	var $Otro_PE;
	var $Departamento;
	var $Muncipio;
	var $NOM_VDA;
	var $NO_E;
	var $NO_OF;
	var $NO_SUBOF;
	var $NO_SOL;
	var $NO_PATRU;
	var $Nom_enfer;
	var $Otro_Nom_Enfer;
	var $Otro_CC_Enfer;
	var $Armada;
	var $Ejercito;
	var $Policia;
	var $NOM_UNIDAD;
	var $NOM_COMAN;
	var $CC_COMAN;
	var $TEL_COMAN;
	var $RANGO_COMAN;
	var $Otro_rango;
	var $NO_GDETECCION;
	var $NO_BINOMIO;
	var $FECHA_INTO_AV;
	var $DIA;
	var $MES;
	var $LATITUD;
	var $GRA_LAT;
	var $MIN_LAT;
	var $SEG_LAT;
	var $GRA_LONG;
	var $MIN_LONG;
	var $SEG_LONG;
	var $OBSERVACION;
	var $AD1O;
	var $FASE;
	var $F_Sincron;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'control_vivac';
		$this->TableName = 'control_vivac';
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
		$this->llave = new cField('control_vivac', 'control_vivac', 'x_llave', 'llave', '`llave`', '`llave`', 200, -1, FALSE, '`llave`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['llave'] = &$this->llave;

		// USUARIO
		$this->USUARIO = new cField('control_vivac', 'control_vivac', 'x_USUARIO', 'USUARIO', '`USUARIO`', '`USUARIO`', 201, -1, FALSE, '`USUARIO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['USUARIO'] = &$this->USUARIO;

		// Cargo_gme
		$this->Cargo_gme = new cField('control_vivac', 'control_vivac', 'x_Cargo_gme', 'Cargo_gme', '`Cargo_gme`', '`Cargo_gme`', 200, -1, FALSE, '`Cargo_gme`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_gme'] = &$this->Cargo_gme;

		// Num_AV
		$this->Num_AV = new cField('control_vivac', 'control_vivac', 'x_Num_AV', 'Num_AV', '`Num_AV`', '`Num_AV`', 200, -1, FALSE, '`Num_AV`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Num_AV'] = &$this->Num_AV;

		// NOM_APOYO
		$this->NOM_APOYO = new cField('control_vivac', 'control_vivac', 'x_NOM_APOYO', 'NOM_APOYO', '`NOM_APOYO`', '`NOM_APOYO`', 201, -1, FALSE, '`NOM_APOYO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_APOYO'] = &$this->NOM_APOYO;

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo = new cField('control_vivac', 'control_vivac', 'x_Otro_Nom_Apoyo', 'Otro_Nom_Apoyo', '`Otro_Nom_Apoyo`', '`Otro_Nom_Apoyo`', 200, -1, FALSE, '`Otro_Nom_Apoyo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Nom_Apoyo'] = &$this->Otro_Nom_Apoyo;

		// Otro_CC_Apoyo
		$this->Otro_CC_Apoyo = new cField('control_vivac', 'control_vivac', 'x_Otro_CC_Apoyo', 'Otro_CC_Apoyo', '`Otro_CC_Apoyo`', '`Otro_CC_Apoyo`', 200, -1, FALSE, '`Otro_CC_Apoyo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_Apoyo'] = &$this->Otro_CC_Apoyo;

		// NOM_PE
		$this->NOM_PE = new cField('control_vivac', 'control_vivac', 'x_NOM_PE', 'NOM_PE', '`NOM_PE`', '`NOM_PE`', 200, -1, FALSE, '`NOM_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_PE'] = &$this->NOM_PE;

		// Otro_PE
		$this->Otro_PE = new cField('control_vivac', 'control_vivac', 'x_Otro_PE', 'Otro_PE', '`Otro_PE`', '`Otro_PE`', 200, -1, FALSE, '`Otro_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_PE'] = &$this->Otro_PE;

		// Departamento
		$this->Departamento = new cField('control_vivac', 'control_vivac', 'x_Departamento', 'Departamento', '`Departamento`', '`Departamento`', 200, -1, FALSE, '`Departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Departamento'] = &$this->Departamento;

		// Muncipio
		$this->Muncipio = new cField('control_vivac', 'control_vivac', 'x_Muncipio', 'Muncipio', '`Muncipio`', '`Muncipio`', 201, -1, FALSE, '`Muncipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Muncipio'] = &$this->Muncipio;

		// NOM_VDA
		$this->NOM_VDA = new cField('control_vivac', 'control_vivac', 'x_NOM_VDA', 'NOM_VDA', '`NOM_VDA`', '`NOM_VDA`', 200, -1, FALSE, '`NOM_VDA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_VDA'] = &$this->NOM_VDA;

		// NO_E
		$this->NO_E = new cField('control_vivac', 'control_vivac', 'x_NO_E', 'NO_E', '`NO_E`', '`NO_E`', 3, -1, FALSE, '`NO_E`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NO_E->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NO_E'] = &$this->NO_E;

		// NO_OF
		$this->NO_OF = new cField('control_vivac', 'control_vivac', 'x_NO_OF', 'NO_OF', '`NO_OF`', '`NO_OF`', 3, -1, FALSE, '`NO_OF`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NO_OF->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NO_OF'] = &$this->NO_OF;

		// NO_SUBOF
		$this->NO_SUBOF = new cField('control_vivac', 'control_vivac', 'x_NO_SUBOF', 'NO_SUBOF', '`NO_SUBOF`', '`NO_SUBOF`', 3, -1, FALSE, '`NO_SUBOF`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NO_SUBOF->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NO_SUBOF'] = &$this->NO_SUBOF;

		// NO_SOL
		$this->NO_SOL = new cField('control_vivac', 'control_vivac', 'x_NO_SOL', 'NO_SOL', '`NO_SOL`', '`NO_SOL`', 3, -1, FALSE, '`NO_SOL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NO_SOL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NO_SOL'] = &$this->NO_SOL;

		// NO_PATRU
		$this->NO_PATRU = new cField('control_vivac', 'control_vivac', 'x_NO_PATRU', 'NO_PATRU', '`NO_PATRU`', '`NO_PATRU`', 3, -1, FALSE, '`NO_PATRU`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NO_PATRU->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NO_PATRU'] = &$this->NO_PATRU;

		// Nom_enfer
		$this->Nom_enfer = new cField('control_vivac', 'control_vivac', 'x_Nom_enfer', 'Nom_enfer', '`Nom_enfer`', '`Nom_enfer`', 201, -1, FALSE, '`Nom_enfer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nom_enfer'] = &$this->Nom_enfer;

		// Otro_Nom_Enfer
		$this->Otro_Nom_Enfer = new cField('control_vivac', 'control_vivac', 'x_Otro_Nom_Enfer', 'Otro_Nom_Enfer', '`Otro_Nom_Enfer`', '`Otro_Nom_Enfer`', 200, -1, FALSE, '`Otro_Nom_Enfer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Nom_Enfer'] = &$this->Otro_Nom_Enfer;

		// Otro_CC_Enfer
		$this->Otro_CC_Enfer = new cField('control_vivac', 'control_vivac', 'x_Otro_CC_Enfer', 'Otro_CC_Enfer', '`Otro_CC_Enfer`', '`Otro_CC_Enfer`', 200, -1, FALSE, '`Otro_CC_Enfer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_Enfer'] = &$this->Otro_CC_Enfer;

		// Armada
		$this->Armada = new cField('control_vivac', 'control_vivac', 'x_Armada', 'Armada', '`Armada`', '`Armada`', 131, -1, FALSE, '`Armada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Armada->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Armada'] = &$this->Armada;

		// Ejercito
		$this->Ejercito = new cField('control_vivac', 'control_vivac', 'x_Ejercito', 'Ejercito', '`Ejercito`', '`Ejercito`', 131, -1, FALSE, '`Ejercito`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Ejercito->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Ejercito'] = &$this->Ejercito;

		// Policia
		$this->Policia = new cField('control_vivac', 'control_vivac', 'x_Policia', 'Policia', '`Policia`', '`Policia`', 131, -1, FALSE, '`Policia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Policia->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Policia'] = &$this->Policia;

		// NOM_UNIDAD
		$this->NOM_UNIDAD = new cField('control_vivac', 'control_vivac', 'x_NOM_UNIDAD', 'NOM_UNIDAD', '`NOM_UNIDAD`', '`NOM_UNIDAD`', 200, -1, FALSE, '`NOM_UNIDAD`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_UNIDAD'] = &$this->NOM_UNIDAD;

		// NOM_COMAN
		$this->NOM_COMAN = new cField('control_vivac', 'control_vivac', 'x_NOM_COMAN', 'NOM_COMAN', '`NOM_COMAN`', '`NOM_COMAN`', 200, -1, FALSE, '`NOM_COMAN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_COMAN'] = &$this->NOM_COMAN;

		// CC_COMAN
		$this->CC_COMAN = new cField('control_vivac', 'control_vivac', 'x_CC_COMAN', 'CC_COMAN', '`CC_COMAN`', '`CC_COMAN`', 200, -1, FALSE, '`CC_COMAN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CC_COMAN'] = &$this->CC_COMAN;

		// TEL_COMAN
		$this->TEL_COMAN = new cField('control_vivac', 'control_vivac', 'x_TEL_COMAN', 'TEL_COMAN', '`TEL_COMAN`', '`TEL_COMAN`', 200, -1, FALSE, '`TEL_COMAN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TEL_COMAN'] = &$this->TEL_COMAN;

		// RANGO_COMAN
		$this->RANGO_COMAN = new cField('control_vivac', 'control_vivac', 'x_RANGO_COMAN', 'RANGO_COMAN', '`RANGO_COMAN`', '`RANGO_COMAN`', 201, -1, FALSE, '`RANGO_COMAN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['RANGO_COMAN'] = &$this->RANGO_COMAN;

		// Otro_rango
		$this->Otro_rango = new cField('control_vivac', 'control_vivac', 'x_Otro_rango', 'Otro_rango', '`Otro_rango`', '`Otro_rango`', 200, -1, FALSE, '`Otro_rango`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_rango'] = &$this->Otro_rango;

		// NO_GDETECCION
		$this->NO_GDETECCION = new cField('control_vivac', 'control_vivac', 'x_NO_GDETECCION', 'NO_GDETECCION', '`NO_GDETECCION`', '`NO_GDETECCION`', 3, -1, FALSE, '`NO_GDETECCION`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NO_GDETECCION->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NO_GDETECCION'] = &$this->NO_GDETECCION;

		// NO_BINOMIO
		$this->NO_BINOMIO = new cField('control_vivac', 'control_vivac', 'x_NO_BINOMIO', 'NO_BINOMIO', '`NO_BINOMIO`', '`NO_BINOMIO`', 3, -1, FALSE, '`NO_BINOMIO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NO_BINOMIO->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NO_BINOMIO'] = &$this->NO_BINOMIO;

		// FECHA_INTO_AV
		$this->FECHA_INTO_AV = new cField('control_vivac', 'control_vivac', 'x_FECHA_INTO_AV', 'FECHA_INTO_AV', '`FECHA_INTO_AV`', '`FECHA_INTO_AV`', 200, -1, FALSE, '`FECHA_INTO_AV`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FECHA_INTO_AV'] = &$this->FECHA_INTO_AV;

		// DIA
		$this->DIA = new cField('control_vivac', 'control_vivac', 'x_DIA', 'DIA', '`DIA`', '`DIA`', 200, -1, FALSE, '`DIA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DIA'] = &$this->DIA;

		// MES
		$this->MES = new cField('control_vivac', 'control_vivac', 'x_MES', 'MES', '`MES`', '`MES`', 200, -1, FALSE, '`MES`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['MES'] = &$this->MES;

		// LATITUD
		$this->LATITUD = new cField('control_vivac', 'control_vivac', 'x_LATITUD', 'LATITUD', '`LATITUD`', '`LATITUD`', 201, -1, FALSE, '`LATITUD`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['LATITUD'] = &$this->LATITUD;

		// GRA_LAT
		$this->GRA_LAT = new cField('control_vivac', 'control_vivac', 'x_GRA_LAT', 'GRA_LAT', '`GRA_LAT`', '`GRA_LAT`', 3, -1, FALSE, '`GRA_LAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LAT->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LAT'] = &$this->GRA_LAT;

		// MIN_LAT
		$this->MIN_LAT = new cField('control_vivac', 'control_vivac', 'x_MIN_LAT', 'MIN_LAT', '`MIN_LAT`', '`MIN_LAT`', 3, -1, FALSE, '`MIN_LAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LAT->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LAT'] = &$this->MIN_LAT;

		// SEG_LAT
		$this->SEG_LAT = new cField('control_vivac', 'control_vivac', 'x_SEG_LAT', 'SEG_LAT', '`SEG_LAT`', '`SEG_LAT`', 131, -1, FALSE, '`SEG_LAT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LAT->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LAT'] = &$this->SEG_LAT;

		// GRA_LONG
		$this->GRA_LONG = new cField('control_vivac', 'control_vivac', 'x_GRA_LONG', 'GRA_LONG', '`GRA_LONG`', '`GRA_LONG`', 3, -1, FALSE, '`GRA_LONG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->GRA_LONG->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['GRA_LONG'] = &$this->GRA_LONG;

		// MIN_LONG
		$this->MIN_LONG = new cField('control_vivac', 'control_vivac', 'x_MIN_LONG', 'MIN_LONG', '`MIN_LONG`', '`MIN_LONG`', 3, -1, FALSE, '`MIN_LONG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->MIN_LONG->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['MIN_LONG'] = &$this->MIN_LONG;

		// SEG_LONG
		$this->SEG_LONG = new cField('control_vivac', 'control_vivac', 'x_SEG_LONG', 'SEG_LONG', '`SEG_LONG`', '`SEG_LONG`', 131, -1, FALSE, '`SEG_LONG`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->SEG_LONG->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['SEG_LONG'] = &$this->SEG_LONG;

		// OBSERVACION
		$this->OBSERVACION = new cField('control_vivac', 'control_vivac', 'x_OBSERVACION', 'OBSERVACION', '`OBSERVACION`', '`OBSERVACION`', 201, -1, FALSE, '`OBSERVACION`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['OBSERVACION'] = &$this->OBSERVACION;

		// AÑO
		$this->AD1O = new cField('control_vivac', 'control_vivac', 'x_AD1O', 'AÑO', '`AÑO`', '`AÑO`', 200, -1, FALSE, '`AÑO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['AÑO'] = &$this->AD1O;

		// FASE
		$this->FASE = new cField('control_vivac', 'control_vivac', 'x_FASE', 'FASE', '`FASE`', '`FASE`', 200, -1, FALSE, '`FASE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FASE'] = &$this->FASE;

		// F_Sincron
		$this->F_Sincron = new cField('control_vivac', 'control_vivac', 'x_F_Sincron', 'F_Sincron', '`F_Sincron`', 'DATE_FORMAT(`F_Sincron`, \'%d/%m/%Y\')', 135, 7, FALSE, '`F_Sincron`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->F_Sincron->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['F_Sincron'] = &$this->F_Sincron;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`control_vivac`";
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
	var $UpdateTable = "`control_vivac`";

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
			return "control_vivaclist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "control_vivaclist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("control_vivacview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("control_vivacview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "control_vivacadd.php?" . $this->UrlParm($parm);
		else
			return "control_vivacadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("control_vivacedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("control_vivacadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("control_vivacdelete.php", $this->UrlParm());
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
		$this->Num_AV->setDbValue($rs->fields('Num_AV'));
		$this->NOM_APOYO->setDbValue($rs->fields('NOM_APOYO'));
		$this->Otro_Nom_Apoyo->setDbValue($rs->fields('Otro_Nom_Apoyo'));
		$this->Otro_CC_Apoyo->setDbValue($rs->fields('Otro_CC_Apoyo'));
		$this->NOM_PE->setDbValue($rs->fields('NOM_PE'));
		$this->Otro_PE->setDbValue($rs->fields('Otro_PE'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->NOM_VDA->setDbValue($rs->fields('NOM_VDA'));
		$this->NO_E->setDbValue($rs->fields('NO_E'));
		$this->NO_OF->setDbValue($rs->fields('NO_OF'));
		$this->NO_SUBOF->setDbValue($rs->fields('NO_SUBOF'));
		$this->NO_SOL->setDbValue($rs->fields('NO_SOL'));
		$this->NO_PATRU->setDbValue($rs->fields('NO_PATRU'));
		$this->Nom_enfer->setDbValue($rs->fields('Nom_enfer'));
		$this->Otro_Nom_Enfer->setDbValue($rs->fields('Otro_Nom_Enfer'));
		$this->Otro_CC_Enfer->setDbValue($rs->fields('Otro_CC_Enfer'));
		$this->Armada->setDbValue($rs->fields('Armada'));
		$this->Ejercito->setDbValue($rs->fields('Ejercito'));
		$this->Policia->setDbValue($rs->fields('Policia'));
		$this->NOM_UNIDAD->setDbValue($rs->fields('NOM_UNIDAD'));
		$this->NOM_COMAN->setDbValue($rs->fields('NOM_COMAN'));
		$this->CC_COMAN->setDbValue($rs->fields('CC_COMAN'));
		$this->TEL_COMAN->setDbValue($rs->fields('TEL_COMAN'));
		$this->RANGO_COMAN->setDbValue($rs->fields('RANGO_COMAN'));
		$this->Otro_rango->setDbValue($rs->fields('Otro_rango'));
		$this->NO_GDETECCION->setDbValue($rs->fields('NO_GDETECCION'));
		$this->NO_BINOMIO->setDbValue($rs->fields('NO_BINOMIO'));
		$this->FECHA_INTO_AV->setDbValue($rs->fields('FECHA_INTO_AV'));
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->LATITUD->setDbValue($rs->fields('LATITUD'));
		$this->GRA_LAT->setDbValue($rs->fields('GRA_LAT'));
		$this->MIN_LAT->setDbValue($rs->fields('MIN_LAT'));
		$this->SEG_LAT->setDbValue($rs->fields('SEG_LAT'));
		$this->GRA_LONG->setDbValue($rs->fields('GRA_LONG'));
		$this->MIN_LONG->setDbValue($rs->fields('MIN_LONG'));
		$this->SEG_LONG->setDbValue($rs->fields('SEG_LONG'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->AD1O->setDbValue($rs->fields('AÑO'));
		$this->FASE->setDbValue($rs->fields('FASE'));
		$this->F_Sincron->setDbValue($rs->fields('F_Sincron'));
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
		// Num_AV
		// NOM_APOYO
		// Otro_Nom_Apoyo
		// Otro_CC_Apoyo
		// NOM_PE
		// Otro_PE
		// Departamento
		// Muncipio
		// NOM_VDA
		// NO_E
		// NO_OF
		// NO_SUBOF
		// NO_SOL
		// NO_PATRU
		// Nom_enfer
		// Otro_Nom_Enfer
		// Otro_CC_Enfer
		// Armada
		// Ejercito
		// Policia
		// NOM_UNIDAD
		// NOM_COMAN
		// CC_COMAN
		// TEL_COMAN
		// RANGO_COMAN
		// Otro_rango
		// NO_GDETECCION
		// NO_BINOMIO
		// FECHA_INTO_AV
		// DIA
		// MES
		// LATITUD
		// GRA_LAT
		// MIN_LAT
		// SEG_LAT
		// GRA_LONG
		// MIN_LONG
		// SEG_LONG
		// OBSERVACION
		// AÑO
		// FASE
		// F_Sincron
		// llave

		$this->llave->ViewValue = $this->llave->CurrentValue;
		$this->llave->ViewCustomAttributes = "";

		// USUARIO
		$this->USUARIO->ViewValue = $this->USUARIO->CurrentValue;
		$this->USUARIO->ViewCustomAttributes = "";

		// Cargo_gme
		$this->Cargo_gme->ViewValue = $this->Cargo_gme->CurrentValue;
		$this->Cargo_gme->ViewCustomAttributes = "";

		// Num_AV
		$this->Num_AV->ViewValue = $this->Num_AV->CurrentValue;
		$this->Num_AV->ViewCustomAttributes = "";

		// NOM_APOYO
		$this->NOM_APOYO->ViewValue = $this->NOM_APOYO->CurrentValue;
		$this->NOM_APOYO->ViewCustomAttributes = "";

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo->ViewValue = $this->Otro_Nom_Apoyo->CurrentValue;
		$this->Otro_Nom_Apoyo->ViewCustomAttributes = "";

		// Otro_CC_Apoyo
		$this->Otro_CC_Apoyo->ViewValue = $this->Otro_CC_Apoyo->CurrentValue;
		$this->Otro_CC_Apoyo->ViewCustomAttributes = "";

		// NOM_PE
		$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
		$this->NOM_PE->ViewCustomAttributes = "";

		// Otro_PE
		$this->Otro_PE->ViewValue = $this->Otro_PE->CurrentValue;
		$this->Otro_PE->ViewCustomAttributes = "";

		// Departamento
		$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// Muncipio
		$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
		$this->Muncipio->ViewCustomAttributes = "";

		// NOM_VDA
		$this->NOM_VDA->ViewValue = $this->NOM_VDA->CurrentValue;
		$this->NOM_VDA->ViewCustomAttributes = "";

		// NO_E
		$this->NO_E->ViewValue = $this->NO_E->CurrentValue;
		$this->NO_E->ViewCustomAttributes = "";

		// NO_OF
		$this->NO_OF->ViewValue = $this->NO_OF->CurrentValue;
		$this->NO_OF->ViewCustomAttributes = "";

		// NO_SUBOF
		$this->NO_SUBOF->ViewValue = $this->NO_SUBOF->CurrentValue;
		$this->NO_SUBOF->ViewCustomAttributes = "";

		// NO_SOL
		$this->NO_SOL->ViewValue = $this->NO_SOL->CurrentValue;
		$this->NO_SOL->ViewCustomAttributes = "";

		// NO_PATRU
		$this->NO_PATRU->ViewValue = $this->NO_PATRU->CurrentValue;
		$this->NO_PATRU->ViewCustomAttributes = "";

		// Nom_enfer
		$this->Nom_enfer->ViewValue = $this->Nom_enfer->CurrentValue;
		$this->Nom_enfer->ViewCustomAttributes = "";

		// Otro_Nom_Enfer
		$this->Otro_Nom_Enfer->ViewValue = $this->Otro_Nom_Enfer->CurrentValue;
		$this->Otro_Nom_Enfer->ViewCustomAttributes = "";

		// Otro_CC_Enfer
		$this->Otro_CC_Enfer->ViewValue = $this->Otro_CC_Enfer->CurrentValue;
		$this->Otro_CC_Enfer->ViewCustomAttributes = "";

		// Armada
		$this->Armada->ViewValue = $this->Armada->CurrentValue;
		$this->Armada->ViewCustomAttributes = "";

		// Ejercito
		$this->Ejercito->ViewValue = $this->Ejercito->CurrentValue;
		$this->Ejercito->ViewCustomAttributes = "";

		// Policia
		$this->Policia->ViewValue = $this->Policia->CurrentValue;
		$this->Policia->ViewCustomAttributes = "";

		// NOM_UNIDAD
		$this->NOM_UNIDAD->ViewValue = $this->NOM_UNIDAD->CurrentValue;
		$this->NOM_UNIDAD->ViewCustomAttributes = "";

		// NOM_COMAN
		$this->NOM_COMAN->ViewValue = $this->NOM_COMAN->CurrentValue;
		$this->NOM_COMAN->ViewCustomAttributes = "";

		// CC_COMAN
		$this->CC_COMAN->ViewValue = $this->CC_COMAN->CurrentValue;
		$this->CC_COMAN->ViewCustomAttributes = "";

		// TEL_COMAN
		$this->TEL_COMAN->ViewValue = $this->TEL_COMAN->CurrentValue;
		$this->TEL_COMAN->ViewCustomAttributes = "";

		// RANGO_COMAN
		$this->RANGO_COMAN->ViewValue = $this->RANGO_COMAN->CurrentValue;
		$this->RANGO_COMAN->ViewCustomAttributes = "";

		// Otro_rango
		$this->Otro_rango->ViewValue = $this->Otro_rango->CurrentValue;
		$this->Otro_rango->ViewCustomAttributes = "";

		// NO_GDETECCION
		$this->NO_GDETECCION->ViewValue = $this->NO_GDETECCION->CurrentValue;
		$this->NO_GDETECCION->ViewCustomAttributes = "";

		// NO_BINOMIO
		$this->NO_BINOMIO->ViewValue = $this->NO_BINOMIO->CurrentValue;
		$this->NO_BINOMIO->ViewCustomAttributes = "";

		// FECHA_INTO_AV
		$this->FECHA_INTO_AV->ViewValue = $this->FECHA_INTO_AV->CurrentValue;
		$this->FECHA_INTO_AV->ViewCustomAttributes = "";

		// DIA
		$this->DIA->ViewValue = $this->DIA->CurrentValue;
		$this->DIA->ViewCustomAttributes = "";

		// MES
		$this->MES->ViewValue = $this->MES->CurrentValue;
		$this->MES->ViewCustomAttributes = "";

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

		// Num_AV
		$this->Num_AV->LinkCustomAttributes = "";
		$this->Num_AV->HrefValue = "";
		$this->Num_AV->TooltipValue = "";

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

		// NOM_PE
		$this->NOM_PE->LinkCustomAttributes = "";
		$this->NOM_PE->HrefValue = "";
		$this->NOM_PE->TooltipValue = "";

		// Otro_PE
		$this->Otro_PE->LinkCustomAttributes = "";
		$this->Otro_PE->HrefValue = "";
		$this->Otro_PE->TooltipValue = "";

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

		// NO_E
		$this->NO_E->LinkCustomAttributes = "";
		$this->NO_E->HrefValue = "";
		$this->NO_E->TooltipValue = "";

		// NO_OF
		$this->NO_OF->LinkCustomAttributes = "";
		$this->NO_OF->HrefValue = "";
		$this->NO_OF->TooltipValue = "";

		// NO_SUBOF
		$this->NO_SUBOF->LinkCustomAttributes = "";
		$this->NO_SUBOF->HrefValue = "";
		$this->NO_SUBOF->TooltipValue = "";

		// NO_SOL
		$this->NO_SOL->LinkCustomAttributes = "";
		$this->NO_SOL->HrefValue = "";
		$this->NO_SOL->TooltipValue = "";

		// NO_PATRU
		$this->NO_PATRU->LinkCustomAttributes = "";
		$this->NO_PATRU->HrefValue = "";
		$this->NO_PATRU->TooltipValue = "";

		// Nom_enfer
		$this->Nom_enfer->LinkCustomAttributes = "";
		$this->Nom_enfer->HrefValue = "";
		$this->Nom_enfer->TooltipValue = "";

		// Otro_Nom_Enfer
		$this->Otro_Nom_Enfer->LinkCustomAttributes = "";
		$this->Otro_Nom_Enfer->HrefValue = "";
		$this->Otro_Nom_Enfer->TooltipValue = "";

		// Otro_CC_Enfer
		$this->Otro_CC_Enfer->LinkCustomAttributes = "";
		$this->Otro_CC_Enfer->HrefValue = "";
		$this->Otro_CC_Enfer->TooltipValue = "";

		// Armada
		$this->Armada->LinkCustomAttributes = "";
		$this->Armada->HrefValue = "";
		$this->Armada->TooltipValue = "";

		// Ejercito
		$this->Ejercito->LinkCustomAttributes = "";
		$this->Ejercito->HrefValue = "";
		$this->Ejercito->TooltipValue = "";

		// Policia
		$this->Policia->LinkCustomAttributes = "";
		$this->Policia->HrefValue = "";
		$this->Policia->TooltipValue = "";

		// NOM_UNIDAD
		$this->NOM_UNIDAD->LinkCustomAttributes = "";
		$this->NOM_UNIDAD->HrefValue = "";
		$this->NOM_UNIDAD->TooltipValue = "";

		// NOM_COMAN
		$this->NOM_COMAN->LinkCustomAttributes = "";
		$this->NOM_COMAN->HrefValue = "";
		$this->NOM_COMAN->TooltipValue = "";

		// CC_COMAN
		$this->CC_COMAN->LinkCustomAttributes = "";
		$this->CC_COMAN->HrefValue = "";
		$this->CC_COMAN->TooltipValue = "";

		// TEL_COMAN
		$this->TEL_COMAN->LinkCustomAttributes = "";
		$this->TEL_COMAN->HrefValue = "";
		$this->TEL_COMAN->TooltipValue = "";

		// RANGO_COMAN
		$this->RANGO_COMAN->LinkCustomAttributes = "";
		$this->RANGO_COMAN->HrefValue = "";
		$this->RANGO_COMAN->TooltipValue = "";

		// Otro_rango
		$this->Otro_rango->LinkCustomAttributes = "";
		$this->Otro_rango->HrefValue = "";
		$this->Otro_rango->TooltipValue = "";

		// NO_GDETECCION
		$this->NO_GDETECCION->LinkCustomAttributes = "";
		$this->NO_GDETECCION->HrefValue = "";
		$this->NO_GDETECCION->TooltipValue = "";

		// NO_BINOMIO
		$this->NO_BINOMIO->LinkCustomAttributes = "";
		$this->NO_BINOMIO->HrefValue = "";
		$this->NO_BINOMIO->TooltipValue = "";

		// FECHA_INTO_AV
		$this->FECHA_INTO_AV->LinkCustomAttributes = "";
		$this->FECHA_INTO_AV->HrefValue = "";
		$this->FECHA_INTO_AV->TooltipValue = "";

		// DIA
		$this->DIA->LinkCustomAttributes = "";
		$this->DIA->HrefValue = "";
		$this->DIA->TooltipValue = "";

		// MES
		$this->MES->LinkCustomAttributes = "";
		$this->MES->HrefValue = "";
		$this->MES->TooltipValue = "";

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

		// Num_AV
		$this->Num_AV->EditAttrs["class"] = "form-control";
		$this->Num_AV->EditCustomAttributes = "";
		$this->Num_AV->EditValue = ew_HtmlEncode($this->Num_AV->CurrentValue);
		$this->Num_AV->PlaceHolder = ew_RemoveHtml($this->Num_AV->FldCaption());

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

		// NO_E
		$this->NO_E->EditAttrs["class"] = "form-control";
		$this->NO_E->EditCustomAttributes = "";
		$this->NO_E->EditValue = ew_HtmlEncode($this->NO_E->CurrentValue);
		$this->NO_E->PlaceHolder = ew_RemoveHtml($this->NO_E->FldCaption());

		// NO_OF
		$this->NO_OF->EditAttrs["class"] = "form-control";
		$this->NO_OF->EditCustomAttributes = "";
		$this->NO_OF->EditValue = ew_HtmlEncode($this->NO_OF->CurrentValue);
		$this->NO_OF->PlaceHolder = ew_RemoveHtml($this->NO_OF->FldCaption());

		// NO_SUBOF
		$this->NO_SUBOF->EditAttrs["class"] = "form-control";
		$this->NO_SUBOF->EditCustomAttributes = "";
		$this->NO_SUBOF->EditValue = ew_HtmlEncode($this->NO_SUBOF->CurrentValue);
		$this->NO_SUBOF->PlaceHolder = ew_RemoveHtml($this->NO_SUBOF->FldCaption());

		// NO_SOL
		$this->NO_SOL->EditAttrs["class"] = "form-control";
		$this->NO_SOL->EditCustomAttributes = "";
		$this->NO_SOL->EditValue = ew_HtmlEncode($this->NO_SOL->CurrentValue);
		$this->NO_SOL->PlaceHolder = ew_RemoveHtml($this->NO_SOL->FldCaption());

		// NO_PATRU
		$this->NO_PATRU->EditAttrs["class"] = "form-control";
		$this->NO_PATRU->EditCustomAttributes = "";
		$this->NO_PATRU->EditValue = ew_HtmlEncode($this->NO_PATRU->CurrentValue);
		$this->NO_PATRU->PlaceHolder = ew_RemoveHtml($this->NO_PATRU->FldCaption());

		// Nom_enfer
		$this->Nom_enfer->EditAttrs["class"] = "form-control";
		$this->Nom_enfer->EditCustomAttributes = "";
		$this->Nom_enfer->EditValue = ew_HtmlEncode($this->Nom_enfer->CurrentValue);
		$this->Nom_enfer->PlaceHolder = ew_RemoveHtml($this->Nom_enfer->FldCaption());

		// Otro_Nom_Enfer
		$this->Otro_Nom_Enfer->EditAttrs["class"] = "form-control";
		$this->Otro_Nom_Enfer->EditCustomAttributes = "";
		$this->Otro_Nom_Enfer->EditValue = ew_HtmlEncode($this->Otro_Nom_Enfer->CurrentValue);
		$this->Otro_Nom_Enfer->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Enfer->FldCaption());

		// Otro_CC_Enfer
		$this->Otro_CC_Enfer->EditAttrs["class"] = "form-control";
		$this->Otro_CC_Enfer->EditCustomAttributes = "";
		$this->Otro_CC_Enfer->EditValue = ew_HtmlEncode($this->Otro_CC_Enfer->CurrentValue);
		$this->Otro_CC_Enfer->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Enfer->FldCaption());

		// Armada
		$this->Armada->EditAttrs["class"] = "form-control";
		$this->Armada->EditCustomAttributes = "";
		$this->Armada->EditValue = ew_HtmlEncode($this->Armada->CurrentValue);
		$this->Armada->PlaceHolder = ew_RemoveHtml($this->Armada->FldCaption());
		if (strval($this->Armada->EditValue) <> "" && is_numeric($this->Armada->EditValue)) $this->Armada->EditValue = ew_FormatNumber($this->Armada->EditValue, -2, -1, -2, 0);

		// Ejercito
		$this->Ejercito->EditAttrs["class"] = "form-control";
		$this->Ejercito->EditCustomAttributes = "";
		$this->Ejercito->EditValue = ew_HtmlEncode($this->Ejercito->CurrentValue);
		$this->Ejercito->PlaceHolder = ew_RemoveHtml($this->Ejercito->FldCaption());
		if (strval($this->Ejercito->EditValue) <> "" && is_numeric($this->Ejercito->EditValue)) $this->Ejercito->EditValue = ew_FormatNumber($this->Ejercito->EditValue, -2, -1, -2, 0);

		// Policia
		$this->Policia->EditAttrs["class"] = "form-control";
		$this->Policia->EditCustomAttributes = "";
		$this->Policia->EditValue = ew_HtmlEncode($this->Policia->CurrentValue);
		$this->Policia->PlaceHolder = ew_RemoveHtml($this->Policia->FldCaption());
		if (strval($this->Policia->EditValue) <> "" && is_numeric($this->Policia->EditValue)) $this->Policia->EditValue = ew_FormatNumber($this->Policia->EditValue, -2, -1, -2, 0);

		// NOM_UNIDAD
		$this->NOM_UNIDAD->EditAttrs["class"] = "form-control";
		$this->NOM_UNIDAD->EditCustomAttributes = "";
		$this->NOM_UNIDAD->EditValue = ew_HtmlEncode($this->NOM_UNIDAD->CurrentValue);
		$this->NOM_UNIDAD->PlaceHolder = ew_RemoveHtml($this->NOM_UNIDAD->FldCaption());

		// NOM_COMAN
		$this->NOM_COMAN->EditAttrs["class"] = "form-control";
		$this->NOM_COMAN->EditCustomAttributes = "";
		$this->NOM_COMAN->EditValue = ew_HtmlEncode($this->NOM_COMAN->CurrentValue);
		$this->NOM_COMAN->PlaceHolder = ew_RemoveHtml($this->NOM_COMAN->FldCaption());

		// CC_COMAN
		$this->CC_COMAN->EditAttrs["class"] = "form-control";
		$this->CC_COMAN->EditCustomAttributes = "";
		$this->CC_COMAN->EditValue = ew_HtmlEncode($this->CC_COMAN->CurrentValue);
		$this->CC_COMAN->PlaceHolder = ew_RemoveHtml($this->CC_COMAN->FldCaption());

		// TEL_COMAN
		$this->TEL_COMAN->EditAttrs["class"] = "form-control";
		$this->TEL_COMAN->EditCustomAttributes = "";
		$this->TEL_COMAN->EditValue = ew_HtmlEncode($this->TEL_COMAN->CurrentValue);
		$this->TEL_COMAN->PlaceHolder = ew_RemoveHtml($this->TEL_COMAN->FldCaption());

		// RANGO_COMAN
		$this->RANGO_COMAN->EditAttrs["class"] = "form-control";
		$this->RANGO_COMAN->EditCustomAttributes = "";
		$this->RANGO_COMAN->EditValue = ew_HtmlEncode($this->RANGO_COMAN->CurrentValue);
		$this->RANGO_COMAN->PlaceHolder = ew_RemoveHtml($this->RANGO_COMAN->FldCaption());

		// Otro_rango
		$this->Otro_rango->EditAttrs["class"] = "form-control";
		$this->Otro_rango->EditCustomAttributes = "";
		$this->Otro_rango->EditValue = ew_HtmlEncode($this->Otro_rango->CurrentValue);
		$this->Otro_rango->PlaceHolder = ew_RemoveHtml($this->Otro_rango->FldCaption());

		// NO_GDETECCION
		$this->NO_GDETECCION->EditAttrs["class"] = "form-control";
		$this->NO_GDETECCION->EditCustomAttributes = "";
		$this->NO_GDETECCION->EditValue = ew_HtmlEncode($this->NO_GDETECCION->CurrentValue);
		$this->NO_GDETECCION->PlaceHolder = ew_RemoveHtml($this->NO_GDETECCION->FldCaption());

		// NO_BINOMIO
		$this->NO_BINOMIO->EditAttrs["class"] = "form-control";
		$this->NO_BINOMIO->EditCustomAttributes = "";
		$this->NO_BINOMIO->EditValue = ew_HtmlEncode($this->NO_BINOMIO->CurrentValue);
		$this->NO_BINOMIO->PlaceHolder = ew_RemoveHtml($this->NO_BINOMIO->FldCaption());

		// FECHA_INTO_AV
		$this->FECHA_INTO_AV->EditAttrs["class"] = "form-control";
		$this->FECHA_INTO_AV->EditCustomAttributes = "";
		$this->FECHA_INTO_AV->EditValue = ew_HtmlEncode($this->FECHA_INTO_AV->CurrentValue);
		$this->FECHA_INTO_AV->PlaceHolder = ew_RemoveHtml($this->FECHA_INTO_AV->FldCaption());

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
					if ($this->Num_AV->Exportable) $Doc->ExportCaption($this->Num_AV);
					if ($this->NOM_APOYO->Exportable) $Doc->ExportCaption($this->NOM_APOYO);
					if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_Nom_Apoyo);
					if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_CC_Apoyo);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_PE->Exportable) $Doc->ExportCaption($this->Otro_PE);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->NOM_VDA->Exportable) $Doc->ExportCaption($this->NOM_VDA);
					if ($this->NO_E->Exportable) $Doc->ExportCaption($this->NO_E);
					if ($this->NO_OF->Exportable) $Doc->ExportCaption($this->NO_OF);
					if ($this->NO_SUBOF->Exportable) $Doc->ExportCaption($this->NO_SUBOF);
					if ($this->NO_SOL->Exportable) $Doc->ExportCaption($this->NO_SOL);
					if ($this->NO_PATRU->Exportable) $Doc->ExportCaption($this->NO_PATRU);
					if ($this->Nom_enfer->Exportable) $Doc->ExportCaption($this->Nom_enfer);
					if ($this->Otro_Nom_Enfer->Exportable) $Doc->ExportCaption($this->Otro_Nom_Enfer);
					if ($this->Otro_CC_Enfer->Exportable) $Doc->ExportCaption($this->Otro_CC_Enfer);
					if ($this->Armada->Exportable) $Doc->ExportCaption($this->Armada);
					if ($this->Ejercito->Exportable) $Doc->ExportCaption($this->Ejercito);
					if ($this->Policia->Exportable) $Doc->ExportCaption($this->Policia);
					if ($this->NOM_UNIDAD->Exportable) $Doc->ExportCaption($this->NOM_UNIDAD);
					if ($this->NOM_COMAN->Exportable) $Doc->ExportCaption($this->NOM_COMAN);
					if ($this->CC_COMAN->Exportable) $Doc->ExportCaption($this->CC_COMAN);
					if ($this->TEL_COMAN->Exportable) $Doc->ExportCaption($this->TEL_COMAN);
					if ($this->RANGO_COMAN->Exportable) $Doc->ExportCaption($this->RANGO_COMAN);
					if ($this->Otro_rango->Exportable) $Doc->ExportCaption($this->Otro_rango);
					if ($this->NO_GDETECCION->Exportable) $Doc->ExportCaption($this->NO_GDETECCION);
					if ($this->NO_BINOMIO->Exportable) $Doc->ExportCaption($this->NO_BINOMIO);
					if ($this->FECHA_INTO_AV->Exportable) $Doc->ExportCaption($this->FECHA_INTO_AV);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->LATITUD->Exportable) $Doc->ExportCaption($this->LATITUD);
					if ($this->GRA_LAT->Exportable) $Doc->ExportCaption($this->GRA_LAT);
					if ($this->MIN_LAT->Exportable) $Doc->ExportCaption($this->MIN_LAT);
					if ($this->SEG_LAT->Exportable) $Doc->ExportCaption($this->SEG_LAT);
					if ($this->GRA_LONG->Exportable) $Doc->ExportCaption($this->GRA_LONG);
					if ($this->MIN_LONG->Exportable) $Doc->ExportCaption($this->MIN_LONG);
					if ($this->SEG_LONG->Exportable) $Doc->ExportCaption($this->SEG_LONG);
					if ($this->OBSERVACION->Exportable) $Doc->ExportCaption($this->OBSERVACION);
					if ($this->AD1O->Exportable) $Doc->ExportCaption($this->AD1O);
					if ($this->FASE->Exportable) $Doc->ExportCaption($this->FASE);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
				} else {
					if ($this->llave->Exportable) $Doc->ExportCaption($this->llave);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->Num_AV->Exportable) $Doc->ExportCaption($this->Num_AV);
					if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_Nom_Apoyo);
					if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportCaption($this->Otro_CC_Apoyo);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_PE->Exportable) $Doc->ExportCaption($this->Otro_PE);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->NOM_VDA->Exportable) $Doc->ExportCaption($this->NOM_VDA);
					if ($this->NO_E->Exportable) $Doc->ExportCaption($this->NO_E);
					if ($this->NO_OF->Exportable) $Doc->ExportCaption($this->NO_OF);
					if ($this->NO_SUBOF->Exportable) $Doc->ExportCaption($this->NO_SUBOF);
					if ($this->NO_SOL->Exportable) $Doc->ExportCaption($this->NO_SOL);
					if ($this->NO_PATRU->Exportable) $Doc->ExportCaption($this->NO_PATRU);
					if ($this->Otro_Nom_Enfer->Exportable) $Doc->ExportCaption($this->Otro_Nom_Enfer);
					if ($this->Otro_CC_Enfer->Exportable) $Doc->ExportCaption($this->Otro_CC_Enfer);
					if ($this->Armada->Exportable) $Doc->ExportCaption($this->Armada);
					if ($this->Ejercito->Exportable) $Doc->ExportCaption($this->Ejercito);
					if ($this->Policia->Exportable) $Doc->ExportCaption($this->Policia);
					if ($this->NOM_UNIDAD->Exportable) $Doc->ExportCaption($this->NOM_UNIDAD);
					if ($this->NOM_COMAN->Exportable) $Doc->ExportCaption($this->NOM_COMAN);
					if ($this->CC_COMAN->Exportable) $Doc->ExportCaption($this->CC_COMAN);
					if ($this->TEL_COMAN->Exportable) $Doc->ExportCaption($this->TEL_COMAN);
					if ($this->Otro_rango->Exportable) $Doc->ExportCaption($this->Otro_rango);
					if ($this->NO_GDETECCION->Exportable) $Doc->ExportCaption($this->NO_GDETECCION);
					if ($this->NO_BINOMIO->Exportable) $Doc->ExportCaption($this->NO_BINOMIO);
					if ($this->FECHA_INTO_AV->Exportable) $Doc->ExportCaption($this->FECHA_INTO_AV);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->GRA_LAT->Exportable) $Doc->ExportCaption($this->GRA_LAT);
					if ($this->MIN_LAT->Exportable) $Doc->ExportCaption($this->MIN_LAT);
					if ($this->SEG_LAT->Exportable) $Doc->ExportCaption($this->SEG_LAT);
					if ($this->GRA_LONG->Exportable) $Doc->ExportCaption($this->GRA_LONG);
					if ($this->MIN_LONG->Exportable) $Doc->ExportCaption($this->MIN_LONG);
					if ($this->SEG_LONG->Exportable) $Doc->ExportCaption($this->SEG_LONG);
					if ($this->OBSERVACION->Exportable) $Doc->ExportCaption($this->OBSERVACION);
					if ($this->AD1O->Exportable) $Doc->ExportCaption($this->AD1O);
					if ($this->FASE->Exportable) $Doc->ExportCaption($this->FASE);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
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
						if ($this->Num_AV->Exportable) $Doc->ExportField($this->Num_AV);
						if ($this->NOM_APOYO->Exportable) $Doc->ExportField($this->NOM_APOYO);
						if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportField($this->Otro_Nom_Apoyo);
						if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportField($this->Otro_CC_Apoyo);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_PE->Exportable) $Doc->ExportField($this->Otro_PE);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->NOM_VDA->Exportable) $Doc->ExportField($this->NOM_VDA);
						if ($this->NO_E->Exportable) $Doc->ExportField($this->NO_E);
						if ($this->NO_OF->Exportable) $Doc->ExportField($this->NO_OF);
						if ($this->NO_SUBOF->Exportable) $Doc->ExportField($this->NO_SUBOF);
						if ($this->NO_SOL->Exportable) $Doc->ExportField($this->NO_SOL);
						if ($this->NO_PATRU->Exportable) $Doc->ExportField($this->NO_PATRU);
						if ($this->Nom_enfer->Exportable) $Doc->ExportField($this->Nom_enfer);
						if ($this->Otro_Nom_Enfer->Exportable) $Doc->ExportField($this->Otro_Nom_Enfer);
						if ($this->Otro_CC_Enfer->Exportable) $Doc->ExportField($this->Otro_CC_Enfer);
						if ($this->Armada->Exportable) $Doc->ExportField($this->Armada);
						if ($this->Ejercito->Exportable) $Doc->ExportField($this->Ejercito);
						if ($this->Policia->Exportable) $Doc->ExportField($this->Policia);
						if ($this->NOM_UNIDAD->Exportable) $Doc->ExportField($this->NOM_UNIDAD);
						if ($this->NOM_COMAN->Exportable) $Doc->ExportField($this->NOM_COMAN);
						if ($this->CC_COMAN->Exportable) $Doc->ExportField($this->CC_COMAN);
						if ($this->TEL_COMAN->Exportable) $Doc->ExportField($this->TEL_COMAN);
						if ($this->RANGO_COMAN->Exportable) $Doc->ExportField($this->RANGO_COMAN);
						if ($this->Otro_rango->Exportable) $Doc->ExportField($this->Otro_rango);
						if ($this->NO_GDETECCION->Exportable) $Doc->ExportField($this->NO_GDETECCION);
						if ($this->NO_BINOMIO->Exportable) $Doc->ExportField($this->NO_BINOMIO);
						if ($this->FECHA_INTO_AV->Exportable) $Doc->ExportField($this->FECHA_INTO_AV);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->LATITUD->Exportable) $Doc->ExportField($this->LATITUD);
						if ($this->GRA_LAT->Exportable) $Doc->ExportField($this->GRA_LAT);
						if ($this->MIN_LAT->Exportable) $Doc->ExportField($this->MIN_LAT);
						if ($this->SEG_LAT->Exportable) $Doc->ExportField($this->SEG_LAT);
						if ($this->GRA_LONG->Exportable) $Doc->ExportField($this->GRA_LONG);
						if ($this->MIN_LONG->Exportable) $Doc->ExportField($this->MIN_LONG);
						if ($this->SEG_LONG->Exportable) $Doc->ExportField($this->SEG_LONG);
						if ($this->OBSERVACION->Exportable) $Doc->ExportField($this->OBSERVACION);
						if ($this->AD1O->Exportable) $Doc->ExportField($this->AD1O);
						if ($this->FASE->Exportable) $Doc->ExportField($this->FASE);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
					} else {
						if ($this->llave->Exportable) $Doc->ExportField($this->llave);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->Num_AV->Exportable) $Doc->ExportField($this->Num_AV);
						if ($this->Otro_Nom_Apoyo->Exportable) $Doc->ExportField($this->Otro_Nom_Apoyo);
						if ($this->Otro_CC_Apoyo->Exportable) $Doc->ExportField($this->Otro_CC_Apoyo);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_PE->Exportable) $Doc->ExportField($this->Otro_PE);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->NOM_VDA->Exportable) $Doc->ExportField($this->NOM_VDA);
						if ($this->NO_E->Exportable) $Doc->ExportField($this->NO_E);
						if ($this->NO_OF->Exportable) $Doc->ExportField($this->NO_OF);
						if ($this->NO_SUBOF->Exportable) $Doc->ExportField($this->NO_SUBOF);
						if ($this->NO_SOL->Exportable) $Doc->ExportField($this->NO_SOL);
						if ($this->NO_PATRU->Exportable) $Doc->ExportField($this->NO_PATRU);
						if ($this->Otro_Nom_Enfer->Exportable) $Doc->ExportField($this->Otro_Nom_Enfer);
						if ($this->Otro_CC_Enfer->Exportable) $Doc->ExportField($this->Otro_CC_Enfer);
						if ($this->Armada->Exportable) $Doc->ExportField($this->Armada);
						if ($this->Ejercito->Exportable) $Doc->ExportField($this->Ejercito);
						if ($this->Policia->Exportable) $Doc->ExportField($this->Policia);
						if ($this->NOM_UNIDAD->Exportable) $Doc->ExportField($this->NOM_UNIDAD);
						if ($this->NOM_COMAN->Exportable) $Doc->ExportField($this->NOM_COMAN);
						if ($this->CC_COMAN->Exportable) $Doc->ExportField($this->CC_COMAN);
						if ($this->TEL_COMAN->Exportable) $Doc->ExportField($this->TEL_COMAN);
						if ($this->Otro_rango->Exportable) $Doc->ExportField($this->Otro_rango);
						if ($this->NO_GDETECCION->Exportable) $Doc->ExportField($this->NO_GDETECCION);
						if ($this->NO_BINOMIO->Exportable) $Doc->ExportField($this->NO_BINOMIO);
						if ($this->FECHA_INTO_AV->Exportable) $Doc->ExportField($this->FECHA_INTO_AV);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->GRA_LAT->Exportable) $Doc->ExportField($this->GRA_LAT);
						if ($this->MIN_LAT->Exportable) $Doc->ExportField($this->MIN_LAT);
						if ($this->SEG_LAT->Exportable) $Doc->ExportField($this->SEG_LAT);
						if ($this->GRA_LONG->Exportable) $Doc->ExportField($this->GRA_LONG);
						if ($this->MIN_LONG->Exportable) $Doc->ExportField($this->MIN_LONG);
						if ($this->SEG_LONG->Exportable) $Doc->ExportField($this->SEG_LONG);
						if ($this->OBSERVACION->Exportable) $Doc->ExportField($this->OBSERVACION);
						if ($this->AD1O->Exportable) $Doc->ExportField($this->AD1O);
						if ($this->FASE->Exportable) $Doc->ExportField($this->FASE);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
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
