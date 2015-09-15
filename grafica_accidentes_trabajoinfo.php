<?php

// Global variable for table object
$grafica_accidentes_trabajo = NULL;

//
// Table class for grafica_accidentes_trabajo
//
class cgrafica_accidentes_trabajo extends cTable {
	var $Profesional_especializado;
	var $Punto;
	var $Cargo_Afectado;
	var $Tipo_incidente;
	var $Evacuado;
	var $No_evacuado;
	var $Total_Evacuados;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'grafica_accidentes_trabajo';
		$this->TableName = 'grafica_accidentes_trabajo';
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

		// Profesional_especializado
		$this->Profesional_especializado = new cField('grafica_accidentes_trabajo', 'grafica_accidentes_trabajo', 'x_Profesional_especializado', 'Profesional_especializado', '`Profesional_especializado`', '`Profesional_especializado`', 201, -1, FALSE, '`Profesional_especializado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Profesional_especializado'] = &$this->Profesional_especializado;

		// Punto
		$this->Punto = new cField('grafica_accidentes_trabajo', 'grafica_accidentes_trabajo', 'x_Punto', 'Punto', '`Punto`', '`Punto`', 201, -1, FALSE, '`Punto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Punto'] = &$this->Punto;

		// Cargo_Afectado
		$this->Cargo_Afectado = new cField('grafica_accidentes_trabajo', 'grafica_accidentes_trabajo', 'x_Cargo_Afectado', 'Cargo_Afectado', '`Cargo_Afectado`', '`Cargo_Afectado`', 201, -1, FALSE, '`Cargo_Afectado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Cargo_Afectado'] = &$this->Cargo_Afectado;

		// Tipo_incidente
		$this->Tipo_incidente = new cField('grafica_accidentes_trabajo', 'grafica_accidentes_trabajo', 'x_Tipo_incidente', 'Tipo_incidente', '`Tipo_incidente`', '`Tipo_incidente`', 200, -1, FALSE, '`Tipo_incidente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Tipo_incidente'] = &$this->Tipo_incidente;

		// Evacuado
		$this->Evacuado = new cField('grafica_accidentes_trabajo', 'grafica_accidentes_trabajo', 'x_Evacuado', 'Evacuado', '`Evacuado`', '`Evacuado`', 131, -1, FALSE, '`Evacuado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Evacuado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Evacuado'] = &$this->Evacuado;

		// No_evacuado
		$this->No_evacuado = new cField('grafica_accidentes_trabajo', 'grafica_accidentes_trabajo', 'x_No_evacuado', 'No_evacuado', '`No_evacuado`', '`No_evacuado`', 131, -1, FALSE, '`No_evacuado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->No_evacuado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['No_evacuado'] = &$this->No_evacuado;

		// Total_Evacuados
		$this->Total_Evacuados = new cField('grafica_accidentes_trabajo', 'grafica_accidentes_trabajo', 'x_Total_Evacuados', 'Total_Evacuados', '`Total_Evacuados`', '`Total_Evacuados`', 20, -1, FALSE, '`Total_Evacuados`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Total_Evacuados->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Total_Evacuados'] = &$this->Total_Evacuados;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`grafica_accidentes_trabajo`";
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
	var $UpdateTable = "`grafica_accidentes_trabajo`";

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
			return "grafica_accidentes_trabajolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "grafica_accidentes_trabajolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("grafica_accidentes_trabajoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("grafica_accidentes_trabajoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "grafica_accidentes_trabajoadd.php?" . $this->UrlParm($parm);
		else
			return "grafica_accidentes_trabajoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("grafica_accidentes_trabajoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("grafica_accidentes_trabajoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("grafica_accidentes_trabajodelete.php", $this->UrlParm());
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
		$this->Profesional_especializado->setDbValue($rs->fields('Profesional_especializado'));
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->Cargo_Afectado->setDbValue($rs->fields('Cargo_Afectado'));
		$this->Tipo_incidente->setDbValue($rs->fields('Tipo_incidente'));
		$this->Evacuado->setDbValue($rs->fields('Evacuado'));
		$this->No_evacuado->setDbValue($rs->fields('No_evacuado'));
		$this->Total_Evacuados->setDbValue($rs->fields('Total_Evacuados'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Profesional_especializado
		// Punto
		// Cargo_Afectado
		// Tipo_incidente
		// Evacuado
		// No_evacuado
		// Total_Evacuados
		// Profesional_especializado

		if (strval($this->Profesional_especializado->CurrentValue) <> "") {
			$sFilterWrk = "`Profesional_especializado`" . ew_SearchString("=", $this->Profesional_especializado->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
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

		// Punto
		if (strval($this->Punto->CurrentValue) <> "") {
			$sFilterWrk = "`Punto`" . ew_SearchString("=", $this->Punto->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
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

		// Cargo_Afectado
		if (strval($this->Cargo_Afectado->CurrentValue) <> "") {
			$sFilterWrk = "`Cargo_Afectado`" . ew_SearchString("=", $this->Cargo_Afectado->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Cargo_Afectado`, `Cargo_Afectado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Cargo_Afectado`, `Cargo_Afectado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Cargo_Afectado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Cargo_Afectado` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Cargo_Afectado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Cargo_Afectado->ViewValue = $this->Cargo_Afectado->CurrentValue;
			}
		} else {
			$this->Cargo_Afectado->ViewValue = NULL;
		}
		$this->Cargo_Afectado->ViewCustomAttributes = "";

		// Tipo_incidente
		if (strval($this->Tipo_incidente->CurrentValue) <> "") {
			$sFilterWrk = "`Tipo_incidente`" . ew_SearchString("=", $this->Tipo_incidente->CurrentValue, EW_DATATYPE_STRING);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
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

		// Evacuado
		if (strval($this->Evacuado->CurrentValue) <> "") {
			$sFilterWrk = "`Evacuado`" . ew_SearchString("=", $this->Evacuado->CurrentValue, EW_DATATYPE_NUMBER);
		switch (@$gsLanguage) {
			case "en":
				$sSqlWrk = "SELECT DISTINCT `Evacuado`, `Evacuado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT DISTINCT `Evacuado`, `Evacuado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
				$sWhereWrk = "";
				break;
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->Evacuado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `Evacuado` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->Evacuado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->Evacuado->ViewValue = $this->Evacuado->CurrentValue;
			}
		} else {
			$this->Evacuado->ViewValue = NULL;
		}
		$this->Evacuado->ViewCustomAttributes = "";

		// No_evacuado
		$this->No_evacuado->ViewValue = $this->No_evacuado->CurrentValue;
		$this->No_evacuado->ViewCustomAttributes = "";

		// Total_Evacuados
		$this->Total_Evacuados->ViewValue = $this->Total_Evacuados->CurrentValue;
		$this->Total_Evacuados->ViewCustomAttributes = "";

		// Profesional_especializado
		$this->Profesional_especializado->LinkCustomAttributes = "";
		$this->Profesional_especializado->HrefValue = "";
		$this->Profesional_especializado->TooltipValue = "";

		// Punto
		$this->Punto->LinkCustomAttributes = "";
		$this->Punto->HrefValue = "";
		$this->Punto->TooltipValue = "";

		// Cargo_Afectado
		$this->Cargo_Afectado->LinkCustomAttributes = "";
		$this->Cargo_Afectado->HrefValue = "";
		$this->Cargo_Afectado->TooltipValue = "";

		// Tipo_incidente
		$this->Tipo_incidente->LinkCustomAttributes = "";
		$this->Tipo_incidente->HrefValue = "";
		$this->Tipo_incidente->TooltipValue = "";

		// Evacuado
		$this->Evacuado->LinkCustomAttributes = "";
		$this->Evacuado->HrefValue = "";
		$this->Evacuado->TooltipValue = "";

		// No_evacuado
		$this->No_evacuado->LinkCustomAttributes = "";
		$this->No_evacuado->HrefValue = "";
		$this->No_evacuado->TooltipValue = "";

		// Total_Evacuados
		$this->Total_Evacuados->LinkCustomAttributes = "";
		$this->Total_Evacuados->HrefValue = "";
		$this->Total_Evacuados->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Profesional_especializado
		$this->Profesional_especializado->EditAttrs["class"] = "form-control";
		$this->Profesional_especializado->EditCustomAttributes = "";

		// Punto
		$this->Punto->EditAttrs["class"] = "form-control";
		$this->Punto->EditCustomAttributes = "";

		// Cargo_Afectado
		$this->Cargo_Afectado->EditAttrs["class"] = "form-control";
		$this->Cargo_Afectado->EditCustomAttributes = "";

		// Tipo_incidente
		$this->Tipo_incidente->EditAttrs["class"] = "form-control";
		$this->Tipo_incidente->EditCustomAttributes = "";

		// Evacuado
		$this->Evacuado->EditAttrs["class"] = "form-control";
		$this->Evacuado->EditCustomAttributes = "";

		// No_evacuado
		$this->No_evacuado->EditAttrs["class"] = "form-control";
		$this->No_evacuado->EditCustomAttributes = "";
		$this->No_evacuado->EditValue = ew_HtmlEncode($this->No_evacuado->CurrentValue);
		$this->No_evacuado->PlaceHolder = ew_RemoveHtml($this->No_evacuado->FldCaption());
		if (strval($this->No_evacuado->EditValue) <> "" && is_numeric($this->No_evacuado->EditValue)) $this->No_evacuado->EditValue = ew_FormatNumber($this->No_evacuado->EditValue, -2, -1, -2, 0);

		// Total_Evacuados
		$this->Total_Evacuados->EditAttrs["class"] = "form-control";
		$this->Total_Evacuados->EditCustomAttributes = "";
		$this->Total_Evacuados->EditValue = ew_HtmlEncode($this->Total_Evacuados->CurrentValue);
		$this->Total_Evacuados->PlaceHolder = ew_RemoveHtml($this->Total_Evacuados->FldCaption());

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
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->Cargo_Afectado->Exportable) $Doc->ExportCaption($this->Cargo_Afectado);
					if ($this->Tipo_incidente->Exportable) $Doc->ExportCaption($this->Tipo_incidente);
					if ($this->Evacuado->Exportable) $Doc->ExportCaption($this->Evacuado);
					if ($this->No_evacuado->Exportable) $Doc->ExportCaption($this->No_evacuado);
					if ($this->Total_Evacuados->Exportable) $Doc->ExportCaption($this->Total_Evacuados);
				} else {
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->Cargo_Afectado->Exportable) $Doc->ExportCaption($this->Cargo_Afectado);
					if ($this->Tipo_incidente->Exportable) $Doc->ExportCaption($this->Tipo_incidente);
					if ($this->Evacuado->Exportable) $Doc->ExportCaption($this->Evacuado);
					if ($this->No_evacuado->Exportable) $Doc->ExportCaption($this->No_evacuado);
					if ($this->Total_Evacuados->Exportable) $Doc->ExportCaption($this->Total_Evacuados);
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
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->Cargo_Afectado->Exportable) $Doc->ExportField($this->Cargo_Afectado);
						if ($this->Tipo_incidente->Exportable) $Doc->ExportField($this->Tipo_incidente);
						if ($this->Evacuado->Exportable) $Doc->ExportField($this->Evacuado);
						if ($this->No_evacuado->Exportable) $Doc->ExportField($this->No_evacuado);
						if ($this->Total_Evacuados->Exportable) $Doc->ExportField($this->Total_Evacuados);
					} else {
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->Cargo_Afectado->Exportable) $Doc->ExportField($this->Cargo_Afectado);
						if ($this->Tipo_incidente->Exportable) $Doc->ExportField($this->Tipo_incidente);
						if ($this->Evacuado->Exportable) $Doc->ExportField($this->Evacuado);
						if ($this->No_evacuado->Exportable) $Doc->ExportField($this->No_evacuado);
						if ($this->Total_Evacuados->Exportable) $Doc->ExportField($this->Total_Evacuados);
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
