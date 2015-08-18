<?php

// Global variable for table object
$view_e_y_n = NULL;

//
// Table class for view_e_y_n
//
class cview_e_y_n extends cTable {
	var $ID_Formulario;
	var $F_Sincron;
	var $USUARIO;
	var $Cargo_gme;
	var $NOM_GE;
	var $Otro_PGE;
	var $Otro_CC_PGE;
	var $TIPO_INFORME;
	var $FECHA_NOVEDAD;
	var $DIA;
	var $MES;
	var $Num_Evacua;
	var $PTO_INCOMU;
	var $OBS_punt_inco;
	var $OBS_ENLACE;
	var $NUM_Novedad;
	var $Nom_Per_Evacu;
	var $Nom_Otro_Per_Evacu;
	var $CC_Otro_Per_Evacu;
	var $Cargo_Per_EVA;
	var $Motivo_Eva;
	var $OBS_EVA;
	var $NOM_PE;
	var $Otro_Nom_PE;
	var $NOM_CAPATAZ;
	var $Otro_Nom_Capata;
	var $Otro_CC_Capata;
	var $Muncipio;
	var $Departamento;
	var $F_llegada;
	var $Fecha;
	var $Modificado;
	var $llave_2;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view_e_y_n';
		$this->TableName = 'view_e_y_n';
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

		// ID_Formulario
		$this->ID_Formulario = new cField('view_e_y_n', 'view_e_y_n', 'x_ID_Formulario', 'ID_Formulario', '`ID_Formulario`', '`ID_Formulario`', 200, -1, FALSE, '`ID_Formulario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ID_Formulario'] = &$this->ID_Formulario;

		// F_Sincron
		$this->F_Sincron = new cField('view_e_y_n', 'view_e_y_n', 'x_F_Sincron', 'F_Sincron', '`F_Sincron`', 'DATE_FORMAT(`F_Sincron`, \'%d/%m/%Y\')', 135, 7, FALSE, '`F_Sincron`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->F_Sincron->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['F_Sincron'] = &$this->F_Sincron;

		// USUARIO
		$this->USUARIO = new cField('view_e_y_n', 'view_e_y_n', 'x_USUARIO', 'USUARIO', '`USUARIO`', '`USUARIO`', 201, -1, FALSE, '`USUARIO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['USUARIO'] = &$this->USUARIO;

		// Cargo_gme
		$this->Cargo_gme = new cField('view_e_y_n', 'view_e_y_n', 'x_Cargo_gme', 'Cargo_gme', '`Cargo_gme`', '`Cargo_gme`', 200, -1, FALSE, '`Cargo_gme`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_gme'] = &$this->Cargo_gme;

		// NOM_GE
		$this->NOM_GE = new cField('view_e_y_n', 'view_e_y_n', 'x_NOM_GE', 'NOM_GE', '`NOM_GE`', '`NOM_GE`', 201, -1, FALSE, '`NOM_GE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_GE'] = &$this->NOM_GE;

		// Otro_PGE
		$this->Otro_PGE = new cField('view_e_y_n', 'view_e_y_n', 'x_Otro_PGE', 'Otro_PGE', '`Otro_PGE`', '`Otro_PGE`', 200, -1, FALSE, '`Otro_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_PGE'] = &$this->Otro_PGE;

		// Otro_CC_PGE
		$this->Otro_CC_PGE = new cField('view_e_y_n', 'view_e_y_n', 'x_Otro_CC_PGE', 'Otro_CC_PGE', '`Otro_CC_PGE`', '`Otro_CC_PGE`', 200, -1, FALSE, '`Otro_CC_PGE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_PGE'] = &$this->Otro_CC_PGE;

		// TIPO_INFORME
		$this->TIPO_INFORME = new cField('view_e_y_n', 'view_e_y_n', 'x_TIPO_INFORME', 'TIPO_INFORME', '`TIPO_INFORME`', '`TIPO_INFORME`', 201, -1, FALSE, '`TIPO_INFORME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['TIPO_INFORME'] = &$this->TIPO_INFORME;

		// FECHA_NOVEDAD
		$this->FECHA_NOVEDAD = new cField('view_e_y_n', 'view_e_y_n', 'x_FECHA_NOVEDAD', 'FECHA_NOVEDAD', '`FECHA_NOVEDAD`', '`FECHA_NOVEDAD`', 200, -1, FALSE, '`FECHA_NOVEDAD`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['FECHA_NOVEDAD'] = &$this->FECHA_NOVEDAD;

		// DIA
		$this->DIA = new cField('view_e_y_n', 'view_e_y_n', 'x_DIA', 'DIA', '`DIA`', '`DIA`', 200, -1, FALSE, '`DIA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['DIA'] = &$this->DIA;

		// MES
		$this->MES = new cField('view_e_y_n', 'view_e_y_n', 'x_MES', 'MES', '`MES`', '`MES`', 200, -1, FALSE, '`MES`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['MES'] = &$this->MES;

		// Num_Evacua
		$this->Num_Evacua = new cField('view_e_y_n', 'view_e_y_n', 'x_Num_Evacua', 'Num_Evacua', '`Num_Evacua`', '`Num_Evacua`', 3, -1, FALSE, '`Num_Evacua`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Num_Evacua->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Num_Evacua'] = &$this->Num_Evacua;

		// PTO_INCOMU
		$this->PTO_INCOMU = new cField('view_e_y_n', 'view_e_y_n', 'x_PTO_INCOMU', 'PTO_INCOMU', '`PTO_INCOMU`', '`PTO_INCOMU`', 201, -1, FALSE, '`PTO_INCOMU`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['PTO_INCOMU'] = &$this->PTO_INCOMU;

		// OBS_punt_inco
		$this->OBS_punt_inco = new cField('view_e_y_n', 'view_e_y_n', 'x_OBS_punt_inco', 'OBS_punt_inco', '`OBS_punt_inco`', '`OBS_punt_inco`', 201, -1, FALSE, '`OBS_punt_inco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['OBS_punt_inco'] = &$this->OBS_punt_inco;

		// OBS_ENLACE
		$this->OBS_ENLACE = new cField('view_e_y_n', 'view_e_y_n', 'x_OBS_ENLACE', 'OBS_ENLACE', '`OBS_ENLACE`', '`OBS_ENLACE`', 201, -1, FALSE, '`OBS_ENLACE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['OBS_ENLACE'] = &$this->OBS_ENLACE;

		// NUM_Novedad
		$this->NUM_Novedad = new cField('view_e_y_n', 'view_e_y_n', 'x_NUM_Novedad', 'NUM_Novedad', '`NUM_Novedad`', '`NUM_Novedad`', 3, -1, FALSE, '`NUM_Novedad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->NUM_Novedad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NUM_Novedad'] = &$this->NUM_Novedad;

		// Nom_Per_Evacu
		$this->Nom_Per_Evacu = new cField('view_e_y_n', 'view_e_y_n', 'x_Nom_Per_Evacu', 'Nom_Per_Evacu', '`Nom_Per_Evacu`', '`Nom_Per_Evacu`', 201, -1, FALSE, '`Nom_Per_Evacu`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nom_Per_Evacu'] = &$this->Nom_Per_Evacu;

		// Nom_Otro_Per_Evacu
		$this->Nom_Otro_Per_Evacu = new cField('view_e_y_n', 'view_e_y_n', 'x_Nom_Otro_Per_Evacu', 'Nom_Otro_Per_Evacu', '`Nom_Otro_Per_Evacu`', '`Nom_Otro_Per_Evacu`', 200, -1, FALSE, '`Nom_Otro_Per_Evacu`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Nom_Otro_Per_Evacu'] = &$this->Nom_Otro_Per_Evacu;

		// CC_Otro_Per_Evacu
		$this->CC_Otro_Per_Evacu = new cField('view_e_y_n', 'view_e_y_n', 'x_CC_Otro_Per_Evacu', 'CC_Otro_Per_Evacu', '`CC_Otro_Per_Evacu`', '`CC_Otro_Per_Evacu`', 200, -1, FALSE, '`CC_Otro_Per_Evacu`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CC_Otro_Per_Evacu'] = &$this->CC_Otro_Per_Evacu;

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA = new cField('view_e_y_n', 'view_e_y_n', 'x_Cargo_Per_EVA', 'Cargo_Per_EVA', '`Cargo_Per_EVA`', '`Cargo_Per_EVA`', 201, -1, FALSE, '`Cargo_Per_EVA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_Per_EVA'] = &$this->Cargo_Per_EVA;

		// Motivo_Eva
		$this->Motivo_Eva = new cField('view_e_y_n', 'view_e_y_n', 'x_Motivo_Eva', 'Motivo_Eva', '`Motivo_Eva`', '`Motivo_Eva`', 201, -1, FALSE, '`Motivo_Eva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Motivo_Eva'] = &$this->Motivo_Eva;

		// OBS_EVA
		$this->OBS_EVA = new cField('view_e_y_n', 'view_e_y_n', 'x_OBS_EVA', 'OBS_EVA', '`OBS_EVA`', '`OBS_EVA`', 201, -1, FALSE, '`OBS_EVA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['OBS_EVA'] = &$this->OBS_EVA;

		// NOM_PE
		$this->NOM_PE = new cField('view_e_y_n', 'view_e_y_n', 'x_NOM_PE', 'NOM_PE', '`NOM_PE`', '`NOM_PE`', 201, -1, FALSE, '`NOM_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_PE'] = &$this->NOM_PE;

		// Otro_Nom_PE
		$this->Otro_Nom_PE = new cField('view_e_y_n', 'view_e_y_n', 'x_Otro_Nom_PE', 'Otro_Nom_PE', '`Otro_Nom_PE`', '`Otro_Nom_PE`', 200, -1, FALSE, '`Otro_Nom_PE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Nom_PE'] = &$this->Otro_Nom_PE;

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ = new cField('view_e_y_n', 'view_e_y_n', 'x_NOM_CAPATAZ', 'NOM_CAPATAZ', '`NOM_CAPATAZ`', '`NOM_CAPATAZ`', 201, -1, FALSE, '`NOM_CAPATAZ`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['NOM_CAPATAZ'] = &$this->NOM_CAPATAZ;

		// Otro_Nom_Capata
		$this->Otro_Nom_Capata = new cField('view_e_y_n', 'view_e_y_n', 'x_Otro_Nom_Capata', 'Otro_Nom_Capata', '`Otro_Nom_Capata`', '`Otro_Nom_Capata`', 200, -1, FALSE, '`Otro_Nom_Capata`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_Nom_Capata'] = &$this->Otro_Nom_Capata;

		// Otro_CC_Capata
		$this->Otro_CC_Capata = new cField('view_e_y_n', 'view_e_y_n', 'x_Otro_CC_Capata', 'Otro_CC_Capata', '`Otro_CC_Capata`', '`Otro_CC_Capata`', 200, -1, FALSE, '`Otro_CC_Capata`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Otro_CC_Capata'] = &$this->Otro_CC_Capata;

		// Muncipio
		$this->Muncipio = new cField('view_e_y_n', 'view_e_y_n', 'x_Muncipio', 'Muncipio', '`Muncipio`', '`Muncipio`', 201, -1, FALSE, '`Muncipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Muncipio'] = &$this->Muncipio;

		// Departamento
		$this->Departamento = new cField('view_e_y_n', 'view_e_y_n', 'x_Departamento', 'Departamento', '`Departamento`', '`Departamento`', 200, -1, FALSE, '`Departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Departamento'] = &$this->Departamento;

		// F_llegada
		$this->F_llegada = new cField('view_e_y_n', 'view_e_y_n', 'x_F_llegada', 'F_llegada', '`F_llegada`', '`F_llegada`', 201, -1, FALSE, '`F_llegada`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['F_llegada'] = &$this->F_llegada;

		// Fecha
		$this->Fecha = new cField('view_e_y_n', 'view_e_y_n', 'x_Fecha', 'Fecha', '`Fecha`', '`Fecha`', 200, -1, FALSE, '`Fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fecha'] = &$this->Fecha;

		// Modificado
		$this->Modificado = new cField('view_e_y_n', 'view_e_y_n', 'x_Modificado', 'Modificado', '`Modificado`', '`Modificado`', 200, -1, FALSE, '`Modificado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Modificado'] = &$this->Modificado;

		// llave_2
		$this->llave_2 = new cField('view_e_y_n', 'view_e_y_n', 'x_llave_2', 'llave_2', '`llave_2`', '`llave_2`', 200, -1, FALSE, '`llave_2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`view_e_y_n`";
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
	var $UpdateTable = "`view_e_y_n`";

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
			return "view_e_y_nlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view_e_y_nlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("view_e_y_nview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("view_e_y_nview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "view_e_y_nadd.php?" . $this->UrlParm($parm);
		else
			return "view_e_y_nadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("view_e_y_nedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("view_e_y_nadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view_e_y_ndelete.php", $this->UrlParm());
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
		$this->ID_Formulario->setDbValue($rs->fields('ID_Formulario'));
		$this->F_Sincron->setDbValue($rs->fields('F_Sincron'));
		$this->USUARIO->setDbValue($rs->fields('USUARIO'));
		$this->Cargo_gme->setDbValue($rs->fields('Cargo_gme'));
		$this->NOM_GE->setDbValue($rs->fields('NOM_GE'));
		$this->Otro_PGE->setDbValue($rs->fields('Otro_PGE'));
		$this->Otro_CC_PGE->setDbValue($rs->fields('Otro_CC_PGE'));
		$this->TIPO_INFORME->setDbValue($rs->fields('TIPO_INFORME'));
		$this->FECHA_NOVEDAD->setDbValue($rs->fields('FECHA_NOVEDAD'));
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->Num_Evacua->setDbValue($rs->fields('Num_Evacua'));
		$this->PTO_INCOMU->setDbValue($rs->fields('PTO_INCOMU'));
		$this->OBS_punt_inco->setDbValue($rs->fields('OBS_punt_inco'));
		$this->OBS_ENLACE->setDbValue($rs->fields('OBS_ENLACE'));
		$this->NUM_Novedad->setDbValue($rs->fields('NUM_Novedad'));
		$this->Nom_Per_Evacu->setDbValue($rs->fields('Nom_Per_Evacu'));
		$this->Nom_Otro_Per_Evacu->setDbValue($rs->fields('Nom_Otro_Per_Evacu'));
		$this->CC_Otro_Per_Evacu->setDbValue($rs->fields('CC_Otro_Per_Evacu'));
		$this->Cargo_Per_EVA->setDbValue($rs->fields('Cargo_Per_EVA'));
		$this->Motivo_Eva->setDbValue($rs->fields('Motivo_Eva'));
		$this->OBS_EVA->setDbValue($rs->fields('OBS_EVA'));
		$this->NOM_PE->setDbValue($rs->fields('NOM_PE'));
		$this->Otro_Nom_PE->setDbValue($rs->fields('Otro_Nom_PE'));
		$this->NOM_CAPATAZ->setDbValue($rs->fields('NOM_CAPATAZ'));
		$this->Otro_Nom_Capata->setDbValue($rs->fields('Otro_Nom_Capata'));
		$this->Otro_CC_Capata->setDbValue($rs->fields('Otro_CC_Capata'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->F_llegada->setDbValue($rs->fields('F_llegada'));
		$this->Fecha->setDbValue($rs->fields('Fecha'));
		$this->Modificado->setDbValue($rs->fields('Modificado'));
		$this->llave_2->setDbValue($rs->fields('llave_2'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID_Formulario
		// F_Sincron
		// USUARIO
		// Cargo_gme
		// NOM_GE
		// Otro_PGE
		// Otro_CC_PGE
		// TIPO_INFORME
		// FECHA_NOVEDAD
		// DIA
		// MES
		// Num_Evacua
		// PTO_INCOMU
		// OBS_punt_inco
		// OBS_ENLACE
		// NUM_Novedad
		// Nom_Per_Evacu
		// Nom_Otro_Per_Evacu
		// CC_Otro_Per_Evacu
		// Cargo_Per_EVA
		// Motivo_Eva
		// OBS_EVA
		// NOM_PE
		// Otro_Nom_PE
		// NOM_CAPATAZ
		// Otro_Nom_Capata
		// Otro_CC_Capata
		// Muncipio
		// Departamento
		// F_llegada
		// Fecha
		// Modificado
		// llave_2

		$this->llave_2->CellCssStyle = "white-space: nowrap;";

		// ID_Formulario
		$this->ID_Formulario->ViewValue = $this->ID_Formulario->CurrentValue;
		$this->ID_Formulario->ViewCustomAttributes = "";

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

		// NOM_GE
		$this->NOM_GE->ViewValue = $this->NOM_GE->CurrentValue;
		$this->NOM_GE->ViewCustomAttributes = "";

		// Otro_PGE
		$this->Otro_PGE->ViewValue = $this->Otro_PGE->CurrentValue;
		$this->Otro_PGE->ViewCustomAttributes = "";

		// Otro_CC_PGE
		$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
		$this->Otro_CC_PGE->ViewCustomAttributes = "";

		// TIPO_INFORME
		$this->TIPO_INFORME->ViewValue = $this->TIPO_INFORME->CurrentValue;
		$this->TIPO_INFORME->ViewCustomAttributes = "";

		// FECHA_NOVEDAD
		$this->FECHA_NOVEDAD->ViewValue = $this->FECHA_NOVEDAD->CurrentValue;
		$this->FECHA_NOVEDAD->ViewCustomAttributes = "";

		// DIA
		$this->DIA->ViewValue = $this->DIA->CurrentValue;
		$this->DIA->ViewCustomAttributes = "";

		// MES
		$this->MES->ViewValue = $this->MES->CurrentValue;
		$this->MES->ViewCustomAttributes = "";

		// Num_Evacua
		$this->Num_Evacua->ViewValue = $this->Num_Evacua->CurrentValue;
		$this->Num_Evacua->ViewCustomAttributes = "";

		// PTO_INCOMU
		$this->PTO_INCOMU->ViewValue = $this->PTO_INCOMU->CurrentValue;
		$this->PTO_INCOMU->ViewCustomAttributes = "";

		// OBS_punt_inco
		$this->OBS_punt_inco->ViewValue = $this->OBS_punt_inco->CurrentValue;
		$this->OBS_punt_inco->ViewCustomAttributes = "";

		// OBS_ENLACE
		$this->OBS_ENLACE->ViewValue = $this->OBS_ENLACE->CurrentValue;
		$this->OBS_ENLACE->ViewCustomAttributes = "";

		// NUM_Novedad
		$this->NUM_Novedad->ViewValue = $this->NUM_Novedad->CurrentValue;
		$this->NUM_Novedad->ViewCustomAttributes = "";

		// Nom_Per_Evacu
		$this->Nom_Per_Evacu->ViewValue = $this->Nom_Per_Evacu->CurrentValue;
		$this->Nom_Per_Evacu->ViewCustomAttributes = "";

		// Nom_Otro_Per_Evacu
		$this->Nom_Otro_Per_Evacu->ViewValue = $this->Nom_Otro_Per_Evacu->CurrentValue;
		$this->Nom_Otro_Per_Evacu->ViewCustomAttributes = "";

		// CC_Otro_Per_Evacu
		$this->CC_Otro_Per_Evacu->ViewValue = $this->CC_Otro_Per_Evacu->CurrentValue;
		$this->CC_Otro_Per_Evacu->ViewCustomAttributes = "";

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA->ViewValue = $this->Cargo_Per_EVA->CurrentValue;
		$this->Cargo_Per_EVA->ViewCustomAttributes = "";

		// Motivo_Eva
		$this->Motivo_Eva->ViewValue = $this->Motivo_Eva->CurrentValue;
		$this->Motivo_Eva->ViewCustomAttributes = "";

		// OBS_EVA
		$this->OBS_EVA->ViewValue = $this->OBS_EVA->CurrentValue;
		$this->OBS_EVA->ViewCustomAttributes = "";

		// NOM_PE
		$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
		$this->NOM_PE->ViewCustomAttributes = "";

		// Otro_Nom_PE
		$this->Otro_Nom_PE->ViewValue = $this->Otro_Nom_PE->CurrentValue;
		$this->Otro_Nom_PE->ViewCustomAttributes = "";

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ->ViewValue = $this->NOM_CAPATAZ->CurrentValue;
		$this->NOM_CAPATAZ->ViewCustomAttributes = "";

		// Otro_Nom_Capata
		$this->Otro_Nom_Capata->ViewValue = $this->Otro_Nom_Capata->CurrentValue;
		$this->Otro_Nom_Capata->ViewCustomAttributes = "";

		// Otro_CC_Capata
		$this->Otro_CC_Capata->ViewValue = $this->Otro_CC_Capata->CurrentValue;
		$this->Otro_CC_Capata->ViewCustomAttributes = "";

		// Muncipio
		$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
		$this->Muncipio->ViewCustomAttributes = "";

		// Departamento
		$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// F_llegada
		$this->F_llegada->ViewValue = $this->F_llegada->CurrentValue;
		$this->F_llegada->ViewCustomAttributes = "";

		// Fecha
		$this->Fecha->ViewValue = $this->Fecha->CurrentValue;
		$this->Fecha->ViewCustomAttributes = "";

		// Modificado
		$this->Modificado->ViewValue = $this->Modificado->CurrentValue;
		$this->Modificado->ViewCustomAttributes = "";

		// llave_2
		$this->llave_2->ViewValue = $this->llave_2->CurrentValue;
		$this->llave_2->ViewCustomAttributes = "";

		// ID_Formulario
		$this->ID_Formulario->LinkCustomAttributes = "";
		$this->ID_Formulario->HrefValue = "";
		$this->ID_Formulario->TooltipValue = "";

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

		// NOM_GE
		$this->NOM_GE->LinkCustomAttributes = "";
		$this->NOM_GE->HrefValue = "";
		$this->NOM_GE->TooltipValue = "";

		// Otro_PGE
		$this->Otro_PGE->LinkCustomAttributes = "";
		$this->Otro_PGE->HrefValue = "";
		$this->Otro_PGE->TooltipValue = "";

		// Otro_CC_PGE
		$this->Otro_CC_PGE->LinkCustomAttributes = "";
		$this->Otro_CC_PGE->HrefValue = "";
		$this->Otro_CC_PGE->TooltipValue = "";

		// TIPO_INFORME
		$this->TIPO_INFORME->LinkCustomAttributes = "";
		$this->TIPO_INFORME->HrefValue = "";
		$this->TIPO_INFORME->TooltipValue = "";

		// FECHA_NOVEDAD
		$this->FECHA_NOVEDAD->LinkCustomAttributes = "";
		$this->FECHA_NOVEDAD->HrefValue = "";
		$this->FECHA_NOVEDAD->TooltipValue = "";

		// DIA
		$this->DIA->LinkCustomAttributes = "";
		$this->DIA->HrefValue = "";
		$this->DIA->TooltipValue = "";

		// MES
		$this->MES->LinkCustomAttributes = "";
		$this->MES->HrefValue = "";
		$this->MES->TooltipValue = "";

		// Num_Evacua
		$this->Num_Evacua->LinkCustomAttributes = "";
		$this->Num_Evacua->HrefValue = "";
		$this->Num_Evacua->TooltipValue = "";

		// PTO_INCOMU
		$this->PTO_INCOMU->LinkCustomAttributes = "";
		$this->PTO_INCOMU->HrefValue = "";
		$this->PTO_INCOMU->TooltipValue = "";

		// OBS_punt_inco
		$this->OBS_punt_inco->LinkCustomAttributes = "";
		$this->OBS_punt_inco->HrefValue = "";
		$this->OBS_punt_inco->TooltipValue = "";

		// OBS_ENLACE
		$this->OBS_ENLACE->LinkCustomAttributes = "";
		$this->OBS_ENLACE->HrefValue = "";
		$this->OBS_ENLACE->TooltipValue = "";

		// NUM_Novedad
		$this->NUM_Novedad->LinkCustomAttributes = "";
		$this->NUM_Novedad->HrefValue = "";
		$this->NUM_Novedad->TooltipValue = "";

		// Nom_Per_Evacu
		$this->Nom_Per_Evacu->LinkCustomAttributes = "";
		$this->Nom_Per_Evacu->HrefValue = "";
		$this->Nom_Per_Evacu->TooltipValue = "";

		// Nom_Otro_Per_Evacu
		$this->Nom_Otro_Per_Evacu->LinkCustomAttributes = "";
		$this->Nom_Otro_Per_Evacu->HrefValue = "";
		$this->Nom_Otro_Per_Evacu->TooltipValue = "";

		// CC_Otro_Per_Evacu
		$this->CC_Otro_Per_Evacu->LinkCustomAttributes = "";
		$this->CC_Otro_Per_Evacu->HrefValue = "";
		$this->CC_Otro_Per_Evacu->TooltipValue = "";

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA->LinkCustomAttributes = "";
		$this->Cargo_Per_EVA->HrefValue = "";
		$this->Cargo_Per_EVA->TooltipValue = "";

		// Motivo_Eva
		$this->Motivo_Eva->LinkCustomAttributes = "";
		$this->Motivo_Eva->HrefValue = "";
		$this->Motivo_Eva->TooltipValue = "";

		// OBS_EVA
		$this->OBS_EVA->LinkCustomAttributes = "";
		$this->OBS_EVA->HrefValue = "";
		$this->OBS_EVA->TooltipValue = "";

		// NOM_PE
		$this->NOM_PE->LinkCustomAttributes = "";
		$this->NOM_PE->HrefValue = "";
		$this->NOM_PE->TooltipValue = "";

		// Otro_Nom_PE
		$this->Otro_Nom_PE->LinkCustomAttributes = "";
		$this->Otro_Nom_PE->HrefValue = "";
		$this->Otro_Nom_PE->TooltipValue = "";

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ->LinkCustomAttributes = "";
		$this->NOM_CAPATAZ->HrefValue = "";
		$this->NOM_CAPATAZ->TooltipValue = "";

		// Otro_Nom_Capata
		$this->Otro_Nom_Capata->LinkCustomAttributes = "";
		$this->Otro_Nom_Capata->HrefValue = "";
		$this->Otro_Nom_Capata->TooltipValue = "";

		// Otro_CC_Capata
		$this->Otro_CC_Capata->LinkCustomAttributes = "";
		$this->Otro_CC_Capata->HrefValue = "";
		$this->Otro_CC_Capata->TooltipValue = "";

		// Muncipio
		$this->Muncipio->LinkCustomAttributes = "";
		$this->Muncipio->HrefValue = "";
		$this->Muncipio->TooltipValue = "";

		// Departamento
		$this->Departamento->LinkCustomAttributes = "";
		$this->Departamento->HrefValue = "";
		$this->Departamento->TooltipValue = "";

		// F_llegada
		$this->F_llegada->LinkCustomAttributes = "";
		$this->F_llegada->HrefValue = "";
		$this->F_llegada->TooltipValue = "";

		// Fecha
		$this->Fecha->LinkCustomAttributes = "";
		$this->Fecha->HrefValue = "";
		$this->Fecha->TooltipValue = "";

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

		// ID_Formulario
		$this->ID_Formulario->EditAttrs["class"] = "form-control";
		$this->ID_Formulario->EditCustomAttributes = "";
		$this->ID_Formulario->EditValue = $this->ID_Formulario->CurrentValue;
		$this->ID_Formulario->ViewCustomAttributes = "";

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

		// NOM_GE
		$this->NOM_GE->EditAttrs["class"] = "form-control";
		$this->NOM_GE->EditCustomAttributes = "";
		$this->NOM_GE->EditValue = $this->NOM_GE->CurrentValue;
		$this->NOM_GE->ViewCustomAttributes = "";

		// Otro_PGE
		$this->Otro_PGE->EditAttrs["class"] = "form-control";
		$this->Otro_PGE->EditCustomAttributes = "";
		$this->Otro_PGE->EditValue = $this->Otro_PGE->CurrentValue;
		$this->Otro_PGE->ViewCustomAttributes = "";

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

		// FECHA_NOVEDAD
		$this->FECHA_NOVEDAD->EditAttrs["class"] = "form-control";
		$this->FECHA_NOVEDAD->EditCustomAttributes = "";
		$this->FECHA_NOVEDAD->EditValue = $this->FECHA_NOVEDAD->CurrentValue;
		$this->FECHA_NOVEDAD->ViewCustomAttributes = "";

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

		// Num_Evacua
		$this->Num_Evacua->EditAttrs["class"] = "form-control";
		$this->Num_Evacua->EditCustomAttributes = "";
		$this->Num_Evacua->EditValue = $this->Num_Evacua->CurrentValue;
		$this->Num_Evacua->ViewCustomAttributes = "";

		// PTO_INCOMU
		$this->PTO_INCOMU->EditAttrs["class"] = "form-control";
		$this->PTO_INCOMU->EditCustomAttributes = "";
		$this->PTO_INCOMU->EditValue = $this->PTO_INCOMU->CurrentValue;
		$this->PTO_INCOMU->ViewCustomAttributes = "";

		// OBS_punt_inco
		$this->OBS_punt_inco->EditAttrs["class"] = "form-control";
		$this->OBS_punt_inco->EditCustomAttributes = "";
		$this->OBS_punt_inco->EditValue = $this->OBS_punt_inco->CurrentValue;
		$this->OBS_punt_inco->ViewCustomAttributes = "";

		// OBS_ENLACE
		$this->OBS_ENLACE->EditAttrs["class"] = "form-control";
		$this->OBS_ENLACE->EditCustomAttributes = "";
		$this->OBS_ENLACE->EditValue = ew_HtmlEncode($this->OBS_ENLACE->CurrentValue);
		$this->OBS_ENLACE->PlaceHolder = ew_RemoveHtml($this->OBS_ENLACE->FldCaption());

		// NUM_Novedad
		$this->NUM_Novedad->EditAttrs["class"] = "form-control";
		$this->NUM_Novedad->EditCustomAttributes = "";
		$this->NUM_Novedad->EditValue = $this->NUM_Novedad->CurrentValue;
		$this->NUM_Novedad->ViewCustomAttributes = "";

		// Nom_Per_Evacu
		$this->Nom_Per_Evacu->EditAttrs["class"] = "form-control";
		$this->Nom_Per_Evacu->EditCustomAttributes = "";
		$this->Nom_Per_Evacu->EditValue = $this->Nom_Per_Evacu->CurrentValue;
		$this->Nom_Per_Evacu->ViewCustomAttributes = "";

		// Nom_Otro_Per_Evacu
		$this->Nom_Otro_Per_Evacu->EditAttrs["class"] = "form-control";
		$this->Nom_Otro_Per_Evacu->EditCustomAttributes = "";
		$this->Nom_Otro_Per_Evacu->EditValue = $this->Nom_Otro_Per_Evacu->CurrentValue;
		$this->Nom_Otro_Per_Evacu->ViewCustomAttributes = "";

		// CC_Otro_Per_Evacu
		$this->CC_Otro_Per_Evacu->EditAttrs["class"] = "form-control";
		$this->CC_Otro_Per_Evacu->EditCustomAttributes = "";
		$this->CC_Otro_Per_Evacu->EditValue = $this->CC_Otro_Per_Evacu->CurrentValue;
		$this->CC_Otro_Per_Evacu->ViewCustomAttributes = "";

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA->EditAttrs["class"] = "form-control";
		$this->Cargo_Per_EVA->EditCustomAttributes = "";
		$this->Cargo_Per_EVA->EditValue = $this->Cargo_Per_EVA->CurrentValue;
		$this->Cargo_Per_EVA->ViewCustomAttributes = "";

		// Motivo_Eva
		$this->Motivo_Eva->EditAttrs["class"] = "form-control";
		$this->Motivo_Eva->EditCustomAttributes = "";
		$this->Motivo_Eva->EditValue = $this->Motivo_Eva->CurrentValue;
		$this->Motivo_Eva->ViewCustomAttributes = "";

		// OBS_EVA
		$this->OBS_EVA->EditAttrs["class"] = "form-control";
		$this->OBS_EVA->EditCustomAttributes = "";
		$this->OBS_EVA->EditValue = ew_HtmlEncode($this->OBS_EVA->CurrentValue);
		$this->OBS_EVA->PlaceHolder = ew_RemoveHtml($this->OBS_EVA->FldCaption());

		// NOM_PE
		$this->NOM_PE->EditAttrs["class"] = "form-control";
		$this->NOM_PE->EditCustomAttributes = "";
		$this->NOM_PE->EditValue = $this->NOM_PE->CurrentValue;
		$this->NOM_PE->ViewCustomAttributes = "";

		// Otro_Nom_PE
		$this->Otro_Nom_PE->EditAttrs["class"] = "form-control";
		$this->Otro_Nom_PE->EditCustomAttributes = "";
		$this->Otro_Nom_PE->EditValue = $this->Otro_Nom_PE->CurrentValue;
		$this->Otro_Nom_PE->ViewCustomAttributes = "";

		// NOM_CAPATAZ
		$this->NOM_CAPATAZ->EditAttrs["class"] = "form-control";
		$this->NOM_CAPATAZ->EditCustomAttributes = "";
		$this->NOM_CAPATAZ->EditValue = $this->NOM_CAPATAZ->CurrentValue;
		$this->NOM_CAPATAZ->ViewCustomAttributes = "";

		// Otro_Nom_Capata
		$this->Otro_Nom_Capata->EditAttrs["class"] = "form-control";
		$this->Otro_Nom_Capata->EditCustomAttributes = "";
		$this->Otro_Nom_Capata->EditValue = $this->Otro_Nom_Capata->CurrentValue;
		$this->Otro_Nom_Capata->ViewCustomAttributes = "";

		// Otro_CC_Capata
		$this->Otro_CC_Capata->EditAttrs["class"] = "form-control";
		$this->Otro_CC_Capata->EditCustomAttributes = "";
		$this->Otro_CC_Capata->EditValue = $this->Otro_CC_Capata->CurrentValue;
		$this->Otro_CC_Capata->ViewCustomAttributes = "";

		// Muncipio
		$this->Muncipio->EditAttrs["class"] = "form-control";
		$this->Muncipio->EditCustomAttributes = "";
		$this->Muncipio->EditValue = $this->Muncipio->CurrentValue;
		$this->Muncipio->ViewCustomAttributes = "";

		// Departamento
		$this->Departamento->EditAttrs["class"] = "form-control";
		$this->Departamento->EditCustomAttributes = "";
		$this->Departamento->EditValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// F_llegada
		$this->F_llegada->EditAttrs["class"] = "form-control";
		$this->F_llegada->EditCustomAttributes = "";
		$this->F_llegada->EditValue = $this->F_llegada->CurrentValue;
		$this->F_llegada->ViewCustomAttributes = "";

		// Fecha
		$this->Fecha->EditAttrs["class"] = "form-control";
		$this->Fecha->EditCustomAttributes = "";
		$this->Fecha->EditValue = $this->Fecha->CurrentValue;
		$this->Fecha->ViewCustomAttributes = "";

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
					if ($this->ID_Formulario->Exportable) $Doc->ExportCaption($this->ID_Formulario);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
					if ($this->USUARIO->Exportable) $Doc->ExportCaption($this->USUARIO);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->NOM_GE->Exportable) $Doc->ExportCaption($this->NOM_GE);
					if ($this->Otro_PGE->Exportable) $Doc->ExportCaption($this->Otro_PGE);
					if ($this->Otro_CC_PGE->Exportable) $Doc->ExportCaption($this->Otro_CC_PGE);
					if ($this->TIPO_INFORME->Exportable) $Doc->ExportCaption($this->TIPO_INFORME);
					if ($this->FECHA_NOVEDAD->Exportable) $Doc->ExportCaption($this->FECHA_NOVEDAD);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->Num_Evacua->Exportable) $Doc->ExportCaption($this->Num_Evacua);
					if ($this->PTO_INCOMU->Exportable) $Doc->ExportCaption($this->PTO_INCOMU);
					if ($this->OBS_punt_inco->Exportable) $Doc->ExportCaption($this->OBS_punt_inco);
					if ($this->OBS_ENLACE->Exportable) $Doc->ExportCaption($this->OBS_ENLACE);
					if ($this->NUM_Novedad->Exportable) $Doc->ExportCaption($this->NUM_Novedad);
					if ($this->Nom_Per_Evacu->Exportable) $Doc->ExportCaption($this->Nom_Per_Evacu);
					if ($this->Nom_Otro_Per_Evacu->Exportable) $Doc->ExportCaption($this->Nom_Otro_Per_Evacu);
					if ($this->CC_Otro_Per_Evacu->Exportable) $Doc->ExportCaption($this->CC_Otro_Per_Evacu);
					if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportCaption($this->Cargo_Per_EVA);
					if ($this->Motivo_Eva->Exportable) $Doc->ExportCaption($this->Motivo_Eva);
					if ($this->OBS_EVA->Exportable) $Doc->ExportCaption($this->OBS_EVA);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_Nom_PE->Exportable) $Doc->ExportCaption($this->Otro_Nom_PE);
					if ($this->NOM_CAPATAZ->Exportable) $Doc->ExportCaption($this->NOM_CAPATAZ);
					if ($this->Otro_Nom_Capata->Exportable) $Doc->ExportCaption($this->Otro_Nom_Capata);
					if ($this->Otro_CC_Capata->Exportable) $Doc->ExportCaption($this->Otro_CC_Capata);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->F_llegada->Exportable) $Doc->ExportCaption($this->F_llegada);
					if ($this->Fecha->Exportable) $Doc->ExportCaption($this->Fecha);
					if ($this->Modificado->Exportable) $Doc->ExportCaption($this->Modificado);
				} else {
					if ($this->ID_Formulario->Exportable) $Doc->ExportCaption($this->ID_Formulario);
					if ($this->F_Sincron->Exportable) $Doc->ExportCaption($this->F_Sincron);
					if ($this->USUARIO->Exportable) $Doc->ExportCaption($this->USUARIO);
					if ($this->Cargo_gme->Exportable) $Doc->ExportCaption($this->Cargo_gme);
					if ($this->NOM_GE->Exportable) $Doc->ExportCaption($this->NOM_GE);
					if ($this->Otro_PGE->Exportable) $Doc->ExportCaption($this->Otro_PGE);
					if ($this->Otro_CC_PGE->Exportable) $Doc->ExportCaption($this->Otro_CC_PGE);
					if ($this->TIPO_INFORME->Exportable) $Doc->ExportCaption($this->TIPO_INFORME);
					if ($this->FECHA_NOVEDAD->Exportable) $Doc->ExportCaption($this->FECHA_NOVEDAD);
					if ($this->DIA->Exportable) $Doc->ExportCaption($this->DIA);
					if ($this->MES->Exportable) $Doc->ExportCaption($this->MES);
					if ($this->Num_Evacua->Exportable) $Doc->ExportCaption($this->Num_Evacua);
					if ($this->PTO_INCOMU->Exportable) $Doc->ExportCaption($this->PTO_INCOMU);
					if ($this->OBS_punt_inco->Exportable) $Doc->ExportCaption($this->OBS_punt_inco);
					if ($this->OBS_ENLACE->Exportable) $Doc->ExportCaption($this->OBS_ENLACE);
					if ($this->NUM_Novedad->Exportable) $Doc->ExportCaption($this->NUM_Novedad);
					if ($this->Nom_Per_Evacu->Exportable) $Doc->ExportCaption($this->Nom_Per_Evacu);
					if ($this->Nom_Otro_Per_Evacu->Exportable) $Doc->ExportCaption($this->Nom_Otro_Per_Evacu);
					if ($this->CC_Otro_Per_Evacu->Exportable) $Doc->ExportCaption($this->CC_Otro_Per_Evacu);
					if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportCaption($this->Cargo_Per_EVA);
					if ($this->Motivo_Eva->Exportable) $Doc->ExportCaption($this->Motivo_Eva);
					if ($this->OBS_EVA->Exportable) $Doc->ExportCaption($this->OBS_EVA);
					if ($this->NOM_PE->Exportable) $Doc->ExportCaption($this->NOM_PE);
					if ($this->Otro_Nom_PE->Exportable) $Doc->ExportCaption($this->Otro_Nom_PE);
					if ($this->NOM_CAPATAZ->Exportable) $Doc->ExportCaption($this->NOM_CAPATAZ);
					if ($this->Otro_Nom_Capata->Exportable) $Doc->ExportCaption($this->Otro_Nom_Capata);
					if ($this->Otro_CC_Capata->Exportable) $Doc->ExportCaption($this->Otro_CC_Capata);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->F_llegada->Exportable) $Doc->ExportCaption($this->F_llegada);
					if ($this->Fecha->Exportable) $Doc->ExportCaption($this->Fecha);
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
						if ($this->ID_Formulario->Exportable) $Doc->ExportField($this->ID_Formulario);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
						if ($this->USUARIO->Exportable) $Doc->ExportField($this->USUARIO);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->NOM_GE->Exportable) $Doc->ExportField($this->NOM_GE);
						if ($this->Otro_PGE->Exportable) $Doc->ExportField($this->Otro_PGE);
						if ($this->Otro_CC_PGE->Exportable) $Doc->ExportField($this->Otro_CC_PGE);
						if ($this->TIPO_INFORME->Exportable) $Doc->ExportField($this->TIPO_INFORME);
						if ($this->FECHA_NOVEDAD->Exportable) $Doc->ExportField($this->FECHA_NOVEDAD);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->Num_Evacua->Exportable) $Doc->ExportField($this->Num_Evacua);
						if ($this->PTO_INCOMU->Exportable) $Doc->ExportField($this->PTO_INCOMU);
						if ($this->OBS_punt_inco->Exportable) $Doc->ExportField($this->OBS_punt_inco);
						if ($this->OBS_ENLACE->Exportable) $Doc->ExportField($this->OBS_ENLACE);
						if ($this->NUM_Novedad->Exportable) $Doc->ExportField($this->NUM_Novedad);
						if ($this->Nom_Per_Evacu->Exportable) $Doc->ExportField($this->Nom_Per_Evacu);
						if ($this->Nom_Otro_Per_Evacu->Exportable) $Doc->ExportField($this->Nom_Otro_Per_Evacu);
						if ($this->CC_Otro_Per_Evacu->Exportable) $Doc->ExportField($this->CC_Otro_Per_Evacu);
						if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportField($this->Cargo_Per_EVA);
						if ($this->Motivo_Eva->Exportable) $Doc->ExportField($this->Motivo_Eva);
						if ($this->OBS_EVA->Exportable) $Doc->ExportField($this->OBS_EVA);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_Nom_PE->Exportable) $Doc->ExportField($this->Otro_Nom_PE);
						if ($this->NOM_CAPATAZ->Exportable) $Doc->ExportField($this->NOM_CAPATAZ);
						if ($this->Otro_Nom_Capata->Exportable) $Doc->ExportField($this->Otro_Nom_Capata);
						if ($this->Otro_CC_Capata->Exportable) $Doc->ExportField($this->Otro_CC_Capata);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->F_llegada->Exportable) $Doc->ExportField($this->F_llegada);
						if ($this->Fecha->Exportable) $Doc->ExportField($this->Fecha);
						if ($this->Modificado->Exportable) $Doc->ExportField($this->Modificado);
					} else {
						if ($this->ID_Formulario->Exportable) $Doc->ExportField($this->ID_Formulario);
						if ($this->F_Sincron->Exportable) $Doc->ExportField($this->F_Sincron);
						if ($this->USUARIO->Exportable) $Doc->ExportField($this->USUARIO);
						if ($this->Cargo_gme->Exportable) $Doc->ExportField($this->Cargo_gme);
						if ($this->NOM_GE->Exportable) $Doc->ExportField($this->NOM_GE);
						if ($this->Otro_PGE->Exportable) $Doc->ExportField($this->Otro_PGE);
						if ($this->Otro_CC_PGE->Exportable) $Doc->ExportField($this->Otro_CC_PGE);
						if ($this->TIPO_INFORME->Exportable) $Doc->ExportField($this->TIPO_INFORME);
						if ($this->FECHA_NOVEDAD->Exportable) $Doc->ExportField($this->FECHA_NOVEDAD);
						if ($this->DIA->Exportable) $Doc->ExportField($this->DIA);
						if ($this->MES->Exportable) $Doc->ExportField($this->MES);
						if ($this->Num_Evacua->Exportable) $Doc->ExportField($this->Num_Evacua);
						if ($this->PTO_INCOMU->Exportable) $Doc->ExportField($this->PTO_INCOMU);
						if ($this->OBS_punt_inco->Exportable) $Doc->ExportField($this->OBS_punt_inco);
						if ($this->OBS_ENLACE->Exportable) $Doc->ExportField($this->OBS_ENLACE);
						if ($this->NUM_Novedad->Exportable) $Doc->ExportField($this->NUM_Novedad);
						if ($this->Nom_Per_Evacu->Exportable) $Doc->ExportField($this->Nom_Per_Evacu);
						if ($this->Nom_Otro_Per_Evacu->Exportable) $Doc->ExportField($this->Nom_Otro_Per_Evacu);
						if ($this->CC_Otro_Per_Evacu->Exportable) $Doc->ExportField($this->CC_Otro_Per_Evacu);
						if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportField($this->Cargo_Per_EVA);
						if ($this->Motivo_Eva->Exportable) $Doc->ExportField($this->Motivo_Eva);
						if ($this->OBS_EVA->Exportable) $Doc->ExportField($this->OBS_EVA);
						if ($this->NOM_PE->Exportable) $Doc->ExportField($this->NOM_PE);
						if ($this->Otro_Nom_PE->Exportable) $Doc->ExportField($this->Otro_Nom_PE);
						if ($this->NOM_CAPATAZ->Exportable) $Doc->ExportField($this->NOM_CAPATAZ);
						if ($this->Otro_Nom_Capata->Exportable) $Doc->ExportField($this->Otro_Nom_Capata);
						if ($this->Otro_CC_Capata->Exportable) $Doc->ExportField($this->Otro_CC_Capata);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->F_llegada->Exportable) $Doc->ExportField($this->F_llegada);
						if ($this->Fecha->Exportable) $Doc->ExportField($this->Fecha);
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
