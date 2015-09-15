<?php

// Global variable for table object
$grafica_retiro_personal = NULL;

//
// Table class for grafica_retiro_personal
//
class cgrafica_retiro_personal extends cTable {
	var $Punto;
	var $Departamento;
	var $Municipio;
	var $CC_Evacuado;
	var $Evacuado;
	var $Cargo_Per_EVA;
	var $Motivo_Eva;
	var $Fecha_llegada_del_evacuado;
	var $Profesional_especializado;
	var $OBS_EVA;
	var $Fecha_de_Novedad;
	var $AF1o;
	var $Fase;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'grafica_retiro_personal';
		$this->TableName = 'grafica_retiro_personal';
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
		$this->Punto = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Punto', 'Punto', '`Punto`', '`Punto`', 201, -1, FALSE, '`Punto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Punto'] = &$this->Punto;

		// Departamento
		$this->Departamento = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Departamento', 'Departamento', '`Departamento`', '`Departamento`', 200, -1, FALSE, '`Departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Departamento'] = &$this->Departamento;

		// Municipio
		$this->Municipio = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Municipio', 'Municipio', '`Municipio`', '`Municipio`', 201, -1, FALSE, '`Municipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Municipio'] = &$this->Municipio;

		// CC_Evacuado
		$this->CC_Evacuado = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_CC_Evacuado', 'CC_Evacuado', '`CC_Evacuado`', '`CC_Evacuado`', 200, -1, FALSE, '`CC_Evacuado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['CC_Evacuado'] = &$this->CC_Evacuado;

		// Evacuado
		$this->Evacuado = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Evacuado', 'Evacuado', '`Evacuado`', '`Evacuado`', 201, -1, FALSE, '`Evacuado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Evacuado'] = &$this->Evacuado;

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Cargo_Per_EVA', 'Cargo_Per_EVA', '`Cargo_Per_EVA`', '`Cargo_Per_EVA`', 201, -1, FALSE, '`Cargo_Per_EVA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_Per_EVA'] = &$this->Cargo_Per_EVA;

		// Motivo_Eva
		$this->Motivo_Eva = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Motivo_Eva', 'Motivo_Eva', '`Motivo_Eva`', '`Motivo_Eva`', 201, -1, FALSE, '`Motivo_Eva`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Motivo_Eva'] = &$this->Motivo_Eva;

		// Fecha_llegada_del_evacuado
		$this->Fecha_llegada_del_evacuado = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Fecha_llegada_del_evacuado', 'Fecha_llegada_del_evacuado', '`Fecha_llegada_del_evacuado`', '`Fecha_llegada_del_evacuado`', 200, -1, FALSE, '`Fecha_llegada_del_evacuado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fecha_llegada_del_evacuado'] = &$this->Fecha_llegada_del_evacuado;

		// Profesional_especializado
		$this->Profesional_especializado = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Profesional_especializado', 'Profesional_especializado', '`Profesional_especializado`', '`Profesional_especializado`', 201, -1, FALSE, '`Profesional_especializado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Profesional_especializado'] = &$this->Profesional_especializado;

		// OBS_EVA
		$this->OBS_EVA = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_OBS_EVA', 'OBS_EVA', '`OBS_EVA`', '`OBS_EVA`', 201, -1, FALSE, '`OBS_EVA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['OBS_EVA'] = &$this->OBS_EVA;

		// Fecha_de_Novedad
		$this->Fecha_de_Novedad = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Fecha_de_Novedad', 'Fecha_de_Novedad', '`Fecha_de_Novedad`', '`Fecha_de_Novedad`', 200, -1, FALSE, '`Fecha_de_Novedad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fecha_de_Novedad'] = &$this->Fecha_de_Novedad;

		// Año
		$this->AF1o = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_AF1o', 'Año', '`Año`', '`Año`', 200, -1, FALSE, '`Año`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Año'] = &$this->AF1o;

		// Fase
		$this->Fase = new cField('grafica_retiro_personal', 'grafica_retiro_personal', 'x_Fase', 'Fase', '`Fase`', '`Fase`', 200, -1, FALSE, '`Fase`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fase'] = &$this->Fase;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`grafica_retiro_personal`";
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
	var $UpdateTable = "`grafica_retiro_personal`";

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
			return "grafica_retiro_personallist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "grafica_retiro_personallist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("grafica_retiro_personalview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("grafica_retiro_personalview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "grafica_retiro_personaladd.php?" . $this->UrlParm($parm);
		else
			return "grafica_retiro_personaladd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("grafica_retiro_personaledit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("grafica_retiro_personaladd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("grafica_retiro_personaldelete.php", $this->UrlParm());
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
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Municipio->setDbValue($rs->fields('Municipio'));
		$this->CC_Evacuado->setDbValue($rs->fields('CC_Evacuado'));
		$this->Evacuado->setDbValue($rs->fields('Evacuado'));
		$this->Cargo_Per_EVA->setDbValue($rs->fields('Cargo_Per_EVA'));
		$this->Motivo_Eva->setDbValue($rs->fields('Motivo_Eva'));
		$this->Fecha_llegada_del_evacuado->setDbValue($rs->fields('Fecha_llegada_del_evacuado'));
		$this->Profesional_especializado->setDbValue($rs->fields('Profesional_especializado'));
		$this->OBS_EVA->setDbValue($rs->fields('OBS_EVA'));
		$this->Fecha_de_Novedad->setDbValue($rs->fields('Fecha_de_Novedad'));
		$this->AF1o->setDbValue($rs->fields('Año'));
		$this->Fase->setDbValue($rs->fields('Fase'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Punto
		// Departamento
		// Municipio
		// CC_Evacuado
		// Evacuado
		// Cargo_Per_EVA
		// Motivo_Eva
		// Fecha_llegada_del_evacuado
		// Profesional_especializado
		// OBS_EVA
		// Fecha_de_Novedad
		// Año
		// Fase
		// Punto

		if (strval($this->Punto->CurrentValue) <> "") {
			$sFilterWrk = "`Punto`" . ew_SearchString("=", $this->Punto->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
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

		// Departamento
		$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// Municipio
		$this->Municipio->ViewValue = $this->Municipio->CurrentValue;
		$this->Municipio->ViewCustomAttributes = "";

		// CC_Evacuado
		$this->CC_Evacuado->ViewValue = $this->CC_Evacuado->CurrentValue;
		$this->CC_Evacuado->ViewCustomAttributes = "";

		// Evacuado
		$this->Evacuado->ViewValue = $this->Evacuado->CurrentValue;
		$this->Evacuado->ViewCustomAttributes = "";

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA->ViewValue = $this->Cargo_Per_EVA->CurrentValue;
		$this->Cargo_Per_EVA->ViewCustomAttributes = "";

		// Motivo_Eva
		if (strval($this->Motivo_Eva->CurrentValue) <> "") {
			$sFilterWrk = "`Motivo_Eva`" . ew_SearchString("=", $this->Motivo_Eva->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Motivo_Eva`, `Motivo_Eva` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Motivo_Eva`, `Motivo_Eva` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Motivo_Eva, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Motivo_Eva` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Motivo_Eva->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Motivo_Eva->ViewValue = $this->Motivo_Eva->CurrentValue;
			}
		} else {
			$this->Motivo_Eva->ViewValue = NULL;
		}
		$this->Motivo_Eva->ViewCustomAttributes = "";

		// Fecha_llegada_del_evacuado
		$this->Fecha_llegada_del_evacuado->ViewValue = $this->Fecha_llegada_del_evacuado->CurrentValue;
		$this->Fecha_llegada_del_evacuado->ViewCustomAttributes = "";

		// Profesional_especializado
		if (strval($this->Profesional_especializado->CurrentValue) <> "") {
			$sFilterWrk = "`Profesional_especializado`" . ew_SearchString("=", $this->Profesional_especializado->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Profesional_especializado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Profesional_especializado` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Profesional_especializado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->CurrentValue;
			}
		} else {
			$this->Profesional_especializado->ViewValue = NULL;
		}
		$this->Profesional_especializado->ViewCustomAttributes = "";

		// OBS_EVA
		$this->OBS_EVA->ViewValue = $this->OBS_EVA->CurrentValue;
		$this->OBS_EVA->ViewCustomAttributes = "";

		// Fecha_de_Novedad
		$this->Fecha_de_Novedad->ViewValue = $this->Fecha_de_Novedad->CurrentValue;
		$this->Fecha_de_Novedad->ViewCustomAttributes = "";

		// Año
		if (strval($this->AF1o->CurrentValue) <> "") {
			$sFilterWrk = "`Año`" . ew_SearchString("=", $this->AF1o->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Año`, `Año` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Año`, `Año` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->AF1o, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Año` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->AF1o->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->AF1o->ViewValue = $this->AF1o->CurrentValue;
			}
		} else {
			$this->AF1o->ViewValue = NULL;
		}
		$this->AF1o->ViewCustomAttributes = "";

		// Fase
		if (strval($this->Fase->CurrentValue) <> "") {
			$sFilterWrk = "`Fase`" . ew_SearchString("=", $this->Fase->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Fase`, `Fase` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Fase`, `Fase` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_retiro_personal`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Fase, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Fase` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Fase->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Fase->ViewValue = $this->Fase->CurrentValue;
			}
		} else {
			$this->Fase->ViewValue = NULL;
		}
		$this->Fase->ViewCustomAttributes = "";

		// Punto
		$this->Punto->LinkCustomAttributes = "";
		$this->Punto->HrefValue = "";
		$this->Punto->TooltipValue = "";

		// Departamento
		$this->Departamento->LinkCustomAttributes = "";
		$this->Departamento->HrefValue = "";
		$this->Departamento->TooltipValue = "";

		// Municipio
		$this->Municipio->LinkCustomAttributes = "";
		$this->Municipio->HrefValue = "";
		$this->Municipio->TooltipValue = "";

		// CC_Evacuado
		$this->CC_Evacuado->LinkCustomAttributes = "";
		$this->CC_Evacuado->HrefValue = "";
		$this->CC_Evacuado->TooltipValue = "";

		// Evacuado
		$this->Evacuado->LinkCustomAttributes = "";
		$this->Evacuado->HrefValue = "";
		$this->Evacuado->TooltipValue = "";

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA->LinkCustomAttributes = "";
		$this->Cargo_Per_EVA->HrefValue = "";
		$this->Cargo_Per_EVA->TooltipValue = "";

		// Motivo_Eva
		$this->Motivo_Eva->LinkCustomAttributes = "";
		$this->Motivo_Eva->HrefValue = "";
		$this->Motivo_Eva->TooltipValue = "";

		// Fecha_llegada_del_evacuado
		$this->Fecha_llegada_del_evacuado->LinkCustomAttributes = "";
		$this->Fecha_llegada_del_evacuado->HrefValue = "";
		$this->Fecha_llegada_del_evacuado->TooltipValue = "";

		// Profesional_especializado
		$this->Profesional_especializado->LinkCustomAttributes = "";
		$this->Profesional_especializado->HrefValue = "";
		$this->Profesional_especializado->TooltipValue = "";

		// OBS_EVA
		$this->OBS_EVA->LinkCustomAttributes = "";
		$this->OBS_EVA->HrefValue = "";
		$this->OBS_EVA->TooltipValue = "";

		// Fecha_de_Novedad
		$this->Fecha_de_Novedad->LinkCustomAttributes = "";
		$this->Fecha_de_Novedad->HrefValue = "";
		$this->Fecha_de_Novedad->TooltipValue = "";

		// Año
		$this->AF1o->LinkCustomAttributes = "";
		$this->AF1o->HrefValue = "";
		$this->AF1o->TooltipValue = "";

		// Fase
		$this->Fase->LinkCustomAttributes = "";
		$this->Fase->HrefValue = "";
		$this->Fase->TooltipValue = "";

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

		// Departamento
		$this->Departamento->EditAttrs["class"] = "form-control";
		$this->Departamento->EditCustomAttributes = "";
		$this->Departamento->EditValue = ew_HtmlEncode($this->Departamento->CurrentValue);
		$this->Departamento->PlaceHolder = ew_RemoveHtml($this->Departamento->FldCaption());

		// Municipio
		$this->Municipio->EditAttrs["class"] = "form-control";
		$this->Municipio->EditCustomAttributes = "";
		$this->Municipio->EditValue = ew_HtmlEncode($this->Municipio->CurrentValue);
		$this->Municipio->PlaceHolder = ew_RemoveHtml($this->Municipio->FldCaption());

		// CC_Evacuado
		$this->CC_Evacuado->EditAttrs["class"] = "form-control";
		$this->CC_Evacuado->EditCustomAttributes = "";
		$this->CC_Evacuado->EditValue = ew_HtmlEncode($this->CC_Evacuado->CurrentValue);
		$this->CC_Evacuado->PlaceHolder = ew_RemoveHtml($this->CC_Evacuado->FldCaption());

		// Evacuado
		$this->Evacuado->EditAttrs["class"] = "form-control";
		$this->Evacuado->EditCustomAttributes = "";
		$this->Evacuado->EditValue = ew_HtmlEncode($this->Evacuado->CurrentValue);
		$this->Evacuado->PlaceHolder = ew_RemoveHtml($this->Evacuado->FldCaption());

		// Cargo_Per_EVA
		$this->Cargo_Per_EVA->EditAttrs["class"] = "form-control";
		$this->Cargo_Per_EVA->EditCustomAttributes = "";
		$this->Cargo_Per_EVA->EditValue = ew_HtmlEncode($this->Cargo_Per_EVA->CurrentValue);
		$this->Cargo_Per_EVA->PlaceHolder = ew_RemoveHtml($this->Cargo_Per_EVA->FldCaption());

		// Motivo_Eva
		$this->Motivo_Eva->EditAttrs["class"] = "form-control";
		$this->Motivo_Eva->EditCustomAttributes = "";

		// Fecha_llegada_del_evacuado
		$this->Fecha_llegada_del_evacuado->EditAttrs["class"] = "form-control";
		$this->Fecha_llegada_del_evacuado->EditCustomAttributes = "";
		$this->Fecha_llegada_del_evacuado->EditValue = ew_HtmlEncode($this->Fecha_llegada_del_evacuado->CurrentValue);
		$this->Fecha_llegada_del_evacuado->PlaceHolder = ew_RemoveHtml($this->Fecha_llegada_del_evacuado->FldCaption());

		// Profesional_especializado
		$this->Profesional_especializado->EditAttrs["class"] = "form-control";
		$this->Profesional_especializado->EditCustomAttributes = "";

		// OBS_EVA
		$this->OBS_EVA->EditAttrs["class"] = "form-control";
		$this->OBS_EVA->EditCustomAttributes = "";
		$this->OBS_EVA->EditValue = ew_HtmlEncode($this->OBS_EVA->CurrentValue);
		$this->OBS_EVA->PlaceHolder = ew_RemoveHtml($this->OBS_EVA->FldCaption());

		// Fecha_de_Novedad
		$this->Fecha_de_Novedad->EditAttrs["class"] = "form-control";
		$this->Fecha_de_Novedad->EditCustomAttributes = "";
		$this->Fecha_de_Novedad->EditValue = ew_HtmlEncode($this->Fecha_de_Novedad->CurrentValue);
		$this->Fecha_de_Novedad->PlaceHolder = ew_RemoveHtml($this->Fecha_de_Novedad->FldCaption());

		// Año
		$this->AF1o->EditAttrs["class"] = "form-control";
		$this->AF1o->EditCustomAttributes = "";

		// Fase
		$this->Fase->EditAttrs["class"] = "form-control";
		$this->Fase->EditCustomAttributes = "";

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
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Municipio->Exportable) $Doc->ExportCaption($this->Municipio);
					if ($this->CC_Evacuado->Exportable) $Doc->ExportCaption($this->CC_Evacuado);
					if ($this->Evacuado->Exportable) $Doc->ExportCaption($this->Evacuado);
					if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportCaption($this->Cargo_Per_EVA);
					if ($this->Motivo_Eva->Exportable) $Doc->ExportCaption($this->Motivo_Eva);
					if ($this->Fecha_llegada_del_evacuado->Exportable) $Doc->ExportCaption($this->Fecha_llegada_del_evacuado);
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
					if ($this->OBS_EVA->Exportable) $Doc->ExportCaption($this->OBS_EVA);
					if ($this->Fecha_de_Novedad->Exportable) $Doc->ExportCaption($this->Fecha_de_Novedad);
					if ($this->AF1o->Exportable) $Doc->ExportCaption($this->AF1o);
					if ($this->Fase->Exportable) $Doc->ExportCaption($this->Fase);
				} else {
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Municipio->Exportable) $Doc->ExportCaption($this->Municipio);
					if ($this->CC_Evacuado->Exportable) $Doc->ExportCaption($this->CC_Evacuado);
					if ($this->Evacuado->Exportable) $Doc->ExportCaption($this->Evacuado);
					if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportCaption($this->Cargo_Per_EVA);
					if ($this->Motivo_Eva->Exportable) $Doc->ExportCaption($this->Motivo_Eva);
					if ($this->Fecha_llegada_del_evacuado->Exportable) $Doc->ExportCaption($this->Fecha_llegada_del_evacuado);
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
					if ($this->OBS_EVA->Exportable) $Doc->ExportCaption($this->OBS_EVA);
					if ($this->Fecha_de_Novedad->Exportable) $Doc->ExportCaption($this->Fecha_de_Novedad);
					if ($this->AF1o->Exportable) $Doc->ExportCaption($this->AF1o);
					if ($this->Fase->Exportable) $Doc->ExportCaption($this->Fase);
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
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Municipio->Exportable) $Doc->ExportField($this->Municipio);
						if ($this->CC_Evacuado->Exportable) $Doc->ExportField($this->CC_Evacuado);
						if ($this->Evacuado->Exportable) $Doc->ExportField($this->Evacuado);
						if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportField($this->Cargo_Per_EVA);
						if ($this->Motivo_Eva->Exportable) $Doc->ExportField($this->Motivo_Eva);
						if ($this->Fecha_llegada_del_evacuado->Exportable) $Doc->ExportField($this->Fecha_llegada_del_evacuado);
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
						if ($this->OBS_EVA->Exportable) $Doc->ExportField($this->OBS_EVA);
						if ($this->Fecha_de_Novedad->Exportable) $Doc->ExportField($this->Fecha_de_Novedad);
						if ($this->AF1o->Exportable) $Doc->ExportField($this->AF1o);
						if ($this->Fase->Exportable) $Doc->ExportField($this->Fase);
					} else {
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Municipio->Exportable) $Doc->ExportField($this->Municipio);
						if ($this->CC_Evacuado->Exportable) $Doc->ExportField($this->CC_Evacuado);
						if ($this->Evacuado->Exportable) $Doc->ExportField($this->Evacuado);
						if ($this->Cargo_Per_EVA->Exportable) $Doc->ExportField($this->Cargo_Per_EVA);
						if ($this->Motivo_Eva->Exportable) $Doc->ExportField($this->Motivo_Eva);
						if ($this->Fecha_llegada_del_evacuado->Exportable) $Doc->ExportField($this->Fecha_llegada_del_evacuado);
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
						if ($this->OBS_EVA->Exportable) $Doc->ExportField($this->OBS_EVA);
						if ($this->Fecha_de_Novedad->Exportable) $Doc->ExportField($this->Fecha_de_Novedad);
						if ($this->AF1o->Exportable) $Doc->ExportField($this->AF1o);
						if ($this->Fase->Exportable) $Doc->ExportField($this->Fase);
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
