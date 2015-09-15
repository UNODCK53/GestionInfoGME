<?php

// Global variable for table object
$fuerza = NULL;

//
// Table class for fuerza
//
class cfuerza extends cTable {
	var $Fuerza;
	var $Estado;
	var $AF1o;
	var $Fase;
	var $_23_del_punto;
	var $Punto;
	var $Profesional_especializado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'fuerza';
		$this->TableName = 'fuerza';
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

		// Fuerza
		$this->Fuerza = new cField('fuerza', 'fuerza', 'x_Fuerza', 'Fuerza', '`Fuerza`', '`Fuerza`', 200, -1, FALSE, '`Fuerza`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fuerza'] = &$this->Fuerza;

		// Estado
		$this->Estado = new cField('fuerza', 'fuerza', 'x_Estado', 'Estado', '`Estado`', '`Estado`', 200, -1, FALSE, '`Estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Estado'] = &$this->Estado;

		// Año
		$this->AF1o = new cField('fuerza', 'fuerza', 'x_AF1o', 'Año', '`Año`', '`Año`', 18, -1, FALSE, '`Año`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->AF1o->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Año'] = &$this->AF1o;

		// Fase
		$this->Fase = new cField('fuerza', 'fuerza', 'x_Fase', 'Fase', '`Fase`', '`Fase`', 201, -1, FALSE, '`Fase`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Fase'] = &$this->Fase;

		// #_del_punto
		$this->_23_del_punto = new cField('fuerza', 'fuerza', 'x__23_del_punto', '#_del_punto', '`#_del_punto`', '`#_del_punto`', 200, -1, FALSE, '`#_del_punto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['#_del_punto'] = &$this->_23_del_punto;

		// Punto
		$this->Punto = new cField('fuerza', 'fuerza', 'x_Punto', 'Punto', '`Punto`', '`Punto`', 200, -1, FALSE, '`Punto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Punto'] = &$this->Punto;

		// Profesional_especializado
		$this->Profesional_especializado = new cField('fuerza', 'fuerza', 'x_Profesional_especializado', 'Profesional_especializado', '`Profesional_especializado`', '`Profesional_especializado`', 200, -1, FALSE, '`Profesional_especializado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Profesional_especializado'] = &$this->Profesional_especializado;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`fuerza`";
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
	var $UpdateTable = "`fuerza`";

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
			if (array_key_exists('Punto', $rs))
				ew_AddFilter($where, ew_QuotedName('Punto') . '=' . ew_QuotedValue($rs['Punto'], $this->Punto->FldDataType));
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
		return "`Punto` = '@Punto@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@Punto@", ew_AdjustSql($this->Punto->CurrentValue), $sKeyFilter); // Replace key value
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
			return "fuerzalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "fuerzalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("fuerzaview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("fuerzaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "fuerzaadd.php?" . $this->UrlParm($parm);
		else
			return "fuerzaadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("fuerzaedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("fuerzaadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("fuerzadelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Punto->CurrentValue)) {
			$sUrl .= "Punto=" . urlencode($this->Punto->CurrentValue);
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
			$arKeys[] = @$_GET["Punto"]; // Punto

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
			$this->Punto->CurrentValue = $key;
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
		$this->Fuerza->setDbValue($rs->fields('Fuerza'));
		$this->Estado->setDbValue($rs->fields('Estado'));
		$this->AF1o->setDbValue($rs->fields('Año'));
		$this->Fase->setDbValue($rs->fields('Fase'));
		$this->_23_del_punto->setDbValue($rs->fields('#_del_punto'));
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->Profesional_especializado->setDbValue($rs->fields('Profesional_especializado'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Fuerza
		// Estado
		// Año
		// Fase
		// #_del_punto
		// Punto
		// Profesional_especializado
		// Fuerza

		if (strval($this->Fuerza->CurrentValue) <> "") {
			switch ($this->Fuerza->CurrentValue) {
				case $this->Fuerza->FldTagValue(1):
					$this->Fuerza->ViewValue = $this->Fuerza->FldTagCaption(1) <> "" ? $this->Fuerza->FldTagCaption(1) : $this->Fuerza->CurrentValue;
					break;
				case $this->Fuerza->FldTagValue(2):
					$this->Fuerza->ViewValue = $this->Fuerza->FldTagCaption(2) <> "" ? $this->Fuerza->FldTagCaption(2) : $this->Fuerza->CurrentValue;
					break;
				case $this->Fuerza->FldTagValue(3):
					$this->Fuerza->ViewValue = $this->Fuerza->FldTagCaption(3) <> "" ? $this->Fuerza->FldTagCaption(3) : $this->Fuerza->CurrentValue;
					break;
				default:
					$this->Fuerza->ViewValue = $this->Fuerza->CurrentValue;
			}
		} else {
			$this->Fuerza->ViewValue = NULL;
		}
		$this->Fuerza->ViewCustomAttributes = "";

		// Estado
		if (strval($this->Estado->CurrentValue) <> "") {
			switch ($this->Estado->CurrentValue) {
				case $this->Estado->FldTagValue(1):
					$this->Estado->ViewValue = $this->Estado->FldTagCaption(1) <> "" ? $this->Estado->FldTagCaption(1) : $this->Estado->CurrentValue;
					break;
				case $this->Estado->FldTagValue(2):
					$this->Estado->ViewValue = $this->Estado->FldTagCaption(2) <> "" ? $this->Estado->FldTagCaption(2) : $this->Estado->CurrentValue;
					break;
				default:
					$this->Estado->ViewValue = $this->Estado->CurrentValue;
			}
		} else {
			$this->Estado->ViewValue = NULL;
		}
		$this->Estado->ViewCustomAttributes = "";

		// Año
		$this->AF1o->ViewValue = $this->AF1o->CurrentValue;
		$this->AF1o->ViewCustomAttributes = "";

		// Fase
		if (strval($this->Fase->CurrentValue) <> "") {
			switch ($this->Fase->CurrentValue) {
				case $this->Fase->FldTagValue(1):
					$this->Fase->ViewValue = $this->Fase->FldTagCaption(1) <> "" ? $this->Fase->FldTagCaption(1) : $this->Fase->CurrentValue;
					break;
				case $this->Fase->FldTagValue(2):
					$this->Fase->ViewValue = $this->Fase->FldTagCaption(2) <> "" ? $this->Fase->FldTagCaption(2) : $this->Fase->CurrentValue;
					break;
				case $this->Fase->FldTagValue(3):
					$this->Fase->ViewValue = $this->Fase->FldTagCaption(3) <> "" ? $this->Fase->FldTagCaption(3) : $this->Fase->CurrentValue;
					break;
				case $this->Fase->FldTagValue(4):
					$this->Fase->ViewValue = $this->Fase->FldTagCaption(4) <> "" ? $this->Fase->FldTagCaption(4) : $this->Fase->CurrentValue;
					break;
				default:
					$this->Fase->ViewValue = $this->Fase->CurrentValue;
			}
		} else {
			$this->Fase->ViewValue = NULL;
		}
		$this->Fase->ViewCustomAttributes = "";

		// #_del_punto
		$this->_23_del_punto->ViewValue = $this->_23_del_punto->CurrentValue;
		$this->_23_del_punto->ViewCustomAttributes = "";

		// Punto
		$this->Punto->ViewValue = $this->Punto->CurrentValue;
		$this->Punto->ViewCustomAttributes = "";

		// Profesional_especializado
		if (strval($this->Profesional_especializado->CurrentValue) <> "") {
			switch ($this->Profesional_especializado->CurrentValue) {
				case $this->Profesional_especializado->FldTagValue(1):
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(1) <> "" ? $this->Profesional_especializado->FldTagCaption(1) : $this->Profesional_especializado->CurrentValue;
					break;
				case $this->Profesional_especializado->FldTagValue(2):
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(2) <> "" ? $this->Profesional_especializado->FldTagCaption(2) : $this->Profesional_especializado->CurrentValue;
					break;
				case $this->Profesional_especializado->FldTagValue(3):
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(3) <> "" ? $this->Profesional_especializado->FldTagCaption(3) : $this->Profesional_especializado->CurrentValue;
					break;
				case $this->Profesional_especializado->FldTagValue(4):
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(4) <> "" ? $this->Profesional_especializado->FldTagCaption(4) : $this->Profesional_especializado->CurrentValue;
					break;
				case $this->Profesional_especializado->FldTagValue(5):
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(5) <> "" ? $this->Profesional_especializado->FldTagCaption(5) : $this->Profesional_especializado->CurrentValue;
					break;
				case $this->Profesional_especializado->FldTagValue(6):
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(6) <> "" ? $this->Profesional_especializado->FldTagCaption(6) : $this->Profesional_especializado->CurrentValue;
					break;
				case $this->Profesional_especializado->FldTagValue(7):
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(7) <> "" ? $this->Profesional_especializado->FldTagCaption(7) : $this->Profesional_especializado->CurrentValue;
					break;
				default:
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->CurrentValue;
			}
		} else {
			$this->Profesional_especializado->ViewValue = NULL;
		}
		$this->Profesional_especializado->ViewCustomAttributes = "";

		// Fuerza
		$this->Fuerza->LinkCustomAttributes = "";
		$this->Fuerza->HrefValue = "";
		$this->Fuerza->TooltipValue = "";

		// Estado
		$this->Estado->LinkCustomAttributes = "";
		$this->Estado->HrefValue = "";
		$this->Estado->TooltipValue = "";

		// Año
		$this->AF1o->LinkCustomAttributes = "";
		$this->AF1o->HrefValue = "";
		$this->AF1o->TooltipValue = "";

		// Fase
		$this->Fase->LinkCustomAttributes = "";
		$this->Fase->HrefValue = "";
		$this->Fase->TooltipValue = "";

		// #_del_punto
		$this->_23_del_punto->LinkCustomAttributes = "";
		$this->_23_del_punto->HrefValue = "";
		$this->_23_del_punto->TooltipValue = "";

		// Punto
		$this->Punto->LinkCustomAttributes = "";
		$this->Punto->HrefValue = "";
		$this->Punto->TooltipValue = "";

		// Profesional_especializado
		$this->Profesional_especializado->LinkCustomAttributes = "";
		$this->Profesional_especializado->HrefValue = "";
		$this->Profesional_especializado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Fuerza
		$this->Fuerza->EditAttrs["class"] = "form-control";
		$this->Fuerza->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->Fuerza->FldTagValue(1), $this->Fuerza->FldTagCaption(1) <> "" ? $this->Fuerza->FldTagCaption(1) : $this->Fuerza->FldTagValue(1));
		$arwrk[] = array($this->Fuerza->FldTagValue(2), $this->Fuerza->FldTagCaption(2) <> "" ? $this->Fuerza->FldTagCaption(2) : $this->Fuerza->FldTagValue(2));
		$arwrk[] = array($this->Fuerza->FldTagValue(3), $this->Fuerza->FldTagCaption(3) <> "" ? $this->Fuerza->FldTagCaption(3) : $this->Fuerza->FldTagValue(3));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->Fuerza->EditValue = $arwrk;

		// Estado
		$this->Estado->EditAttrs["class"] = "form-control";
		$this->Estado->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->Estado->FldTagValue(1), $this->Estado->FldTagCaption(1) <> "" ? $this->Estado->FldTagCaption(1) : $this->Estado->FldTagValue(1));
		$arwrk[] = array($this->Estado->FldTagValue(2), $this->Estado->FldTagCaption(2) <> "" ? $this->Estado->FldTagCaption(2) : $this->Estado->FldTagValue(2));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->Estado->EditValue = $arwrk;

		// Año
		$this->AF1o->EditAttrs["class"] = "form-control";
		$this->AF1o->EditCustomAttributes = "";
		$this->AF1o->EditValue = $this->AF1o->CurrentValue;
		$this->AF1o->ViewCustomAttributes = "";

		// Fase
		$this->Fase->EditAttrs["class"] = "form-control";
		$this->Fase->EditCustomAttributes = "";
		if (strval($this->Fase->CurrentValue) <> "") {
			switch ($this->Fase->CurrentValue) {
				case $this->Fase->FldTagValue(1):
					$this->Fase->EditValue = $this->Fase->FldTagCaption(1) <> "" ? $this->Fase->FldTagCaption(1) : $this->Fase->CurrentValue;
					break;
				case $this->Fase->FldTagValue(2):
					$this->Fase->EditValue = $this->Fase->FldTagCaption(2) <> "" ? $this->Fase->FldTagCaption(2) : $this->Fase->CurrentValue;
					break;
				case $this->Fase->FldTagValue(3):
					$this->Fase->EditValue = $this->Fase->FldTagCaption(3) <> "" ? $this->Fase->FldTagCaption(3) : $this->Fase->CurrentValue;
					break;
				case $this->Fase->FldTagValue(4):
					$this->Fase->EditValue = $this->Fase->FldTagCaption(4) <> "" ? $this->Fase->FldTagCaption(4) : $this->Fase->CurrentValue;
					break;
				default:
					$this->Fase->EditValue = $this->Fase->CurrentValue;
			}
		} else {
			$this->Fase->EditValue = NULL;
		}
		$this->Fase->ViewCustomAttributes = "";

		// #_del_punto
		$this->_23_del_punto->EditAttrs["class"] = "form-control";
		$this->_23_del_punto->EditCustomAttributes = "";
		$this->_23_del_punto->EditValue = $this->_23_del_punto->CurrentValue;
		$this->_23_del_punto->ViewCustomAttributes = "";

		// Punto
		$this->Punto->EditAttrs["class"] = "form-control";
		$this->Punto->EditCustomAttributes = "";
		$this->Punto->EditValue = $this->Punto->CurrentValue;
		$this->Punto->ViewCustomAttributes = "";

		// Profesional_especializado
		$this->Profesional_especializado->EditAttrs["class"] = "form-control";
		$this->Profesional_especializado->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->Profesional_especializado->FldTagValue(1), $this->Profesional_especializado->FldTagCaption(1) <> "" ? $this->Profesional_especializado->FldTagCaption(1) : $this->Profesional_especializado->FldTagValue(1));
		$arwrk[] = array($this->Profesional_especializado->FldTagValue(2), $this->Profesional_especializado->FldTagCaption(2) <> "" ? $this->Profesional_especializado->FldTagCaption(2) : $this->Profesional_especializado->FldTagValue(2));
		$arwrk[] = array($this->Profesional_especializado->FldTagValue(3), $this->Profesional_especializado->FldTagCaption(3) <> "" ? $this->Profesional_especializado->FldTagCaption(3) : $this->Profesional_especializado->FldTagValue(3));
		$arwrk[] = array($this->Profesional_especializado->FldTagValue(4), $this->Profesional_especializado->FldTagCaption(4) <> "" ? $this->Profesional_especializado->FldTagCaption(4) : $this->Profesional_especializado->FldTagValue(4));
		$arwrk[] = array($this->Profesional_especializado->FldTagValue(5), $this->Profesional_especializado->FldTagCaption(5) <> "" ? $this->Profesional_especializado->FldTagCaption(5) : $this->Profesional_especializado->FldTagValue(5));
		$arwrk[] = array($this->Profesional_especializado->FldTagValue(6), $this->Profesional_especializado->FldTagCaption(6) <> "" ? $this->Profesional_especializado->FldTagCaption(6) : $this->Profesional_especializado->FldTagValue(6));
		$arwrk[] = array($this->Profesional_especializado->FldTagValue(7), $this->Profesional_especializado->FldTagCaption(7) <> "" ? $this->Profesional_especializado->FldTagCaption(7) : $this->Profesional_especializado->FldTagValue(7));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->Profesional_especializado->EditValue = $arwrk;

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
					if ($this->Fuerza->Exportable) $Doc->ExportCaption($this->Fuerza);
					if ($this->Estado->Exportable) $Doc->ExportCaption($this->Estado);
					if ($this->AF1o->Exportable) $Doc->ExportCaption($this->AF1o);
					if ($this->Fase->Exportable) $Doc->ExportCaption($this->Fase);
					if ($this->_23_del_punto->Exportable) $Doc->ExportCaption($this->_23_del_punto);
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
				} else {
					if ($this->Fuerza->Exportable) $Doc->ExportCaption($this->Fuerza);
					if ($this->Estado->Exportable) $Doc->ExportCaption($this->Estado);
					if ($this->AF1o->Exportable) $Doc->ExportCaption($this->AF1o);
					if ($this->Fase->Exportable) $Doc->ExportCaption($this->Fase);
					if ($this->_23_del_punto->Exportable) $Doc->ExportCaption($this->_23_del_punto);
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
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
						if ($this->Fuerza->Exportable) $Doc->ExportField($this->Fuerza);
						if ($this->Estado->Exportable) $Doc->ExportField($this->Estado);
						if ($this->AF1o->Exportable) $Doc->ExportField($this->AF1o);
						if ($this->Fase->Exportable) $Doc->ExportField($this->Fase);
						if ($this->_23_del_punto->Exportable) $Doc->ExportField($this->_23_del_punto);
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
					} else {
						if ($this->Fuerza->Exportable) $Doc->ExportField($this->Fuerza);
						if ($this->Estado->Exportable) $Doc->ExportField($this->Estado);
						if ($this->AF1o->Exportable) $Doc->ExportField($this->AF1o);
						if ($this->Fase->Exportable) $Doc->ExportField($this->Fase);
						if ($this->_23_del_punto->Exportable) $Doc->ExportField($this->_23_del_punto);
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
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
