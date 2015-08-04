<?php

// Global variable for table object
$grafica_erradicacion_fuerza_departamento = NULL;

//
// Table class for grafica_erradicacion_fuerza_departamento
//
class cgrafica_erradicacion_fuerza_departamento extends cTable {
	var $fuerza;
	var $Departamento;
	var $Muncipio;
	var $Punto;
	var $aF1o;
	var $fase;
	var $Profesional_especializado;
	var $Ha_Coca;
	var $Ha_Amapola;
	var $Ha_Marihuana;
	var $Total_erradicado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'grafica_erradicacion_fuerza_departamento';
		$this->TableName = 'grafica_erradicacion_fuerza_departamento';
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

		// fuerza
		$this->fuerza = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_fuerza', 'fuerza', '`fuerza`', '`fuerza`', 200, -1, FALSE, '`fuerza`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fuerza'] = &$this->fuerza;

		// Departamento
		$this->Departamento = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Departamento', 'Departamento', '`Departamento`', '`Departamento`', 200, -1, FALSE, '`Departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Departamento'] = &$this->Departamento;

		// Muncipio
		$this->Muncipio = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Muncipio', 'Muncipio', '`Muncipio`', '`Muncipio`', 201, -1, FALSE, '`Muncipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Muncipio'] = &$this->Muncipio;

		// Punto
		$this->Punto = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Punto', 'Punto', '`Punto`', '`Punto`', 201, -1, FALSE, '`Punto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Punto'] = &$this->Punto;

		// año
		$this->aF1o = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_aF1o', 'año', '`año`', '`año`', 18, -1, FALSE, '`año`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->aF1o->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['año'] = &$this->aF1o;

		// fase
		$this->fase = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_fase', 'fase', '`fase`', '`fase`', 201, -1, FALSE, '`fase`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fase'] = &$this->fase;

		// Profesional_especializado
		$this->Profesional_especializado = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Profesional_especializado', 'Profesional_especializado', '`Profesional_especializado`', '`Profesional_especializado`', 201, -1, FALSE, '`Profesional_especializado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Profesional_especializado'] = &$this->Profesional_especializado;

		// Ha_Coca
		$this->Ha_Coca = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Ha_Coca', 'Ha_Coca', '`Ha_Coca`', '`Ha_Coca`', 131, -1, FALSE, '`Ha_Coca`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Ha_Coca->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Ha_Coca'] = &$this->Ha_Coca;

		// Ha_Amapola
		$this->Ha_Amapola = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Ha_Amapola', 'Ha_Amapola', '`Ha_Amapola`', '`Ha_Amapola`', 131, -1, FALSE, '`Ha_Amapola`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Ha_Amapola->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Ha_Amapola'] = &$this->Ha_Amapola;

		// Ha_Marihuana
		$this->Ha_Marihuana = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Ha_Marihuana', 'Ha_Marihuana', '`Ha_Marihuana`', '`Ha_Marihuana`', 131, -1, FALSE, '`Ha_Marihuana`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Ha_Marihuana->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Ha_Marihuana'] = &$this->Ha_Marihuana;

		// Total_erradicado
		$this->Total_erradicado = new cField('grafica_erradicacion_fuerza_departamento', 'grafica_erradicacion_fuerza_departamento', 'x_Total_erradicado', 'Total_erradicado', '`Total_erradicado`', '`Total_erradicado`', 131, -1, FALSE, '`Total_erradicado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Total_erradicado->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['Total_erradicado'] = &$this->Total_erradicado;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`grafica_erradicacion_fuerza_departamento`";
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
	var $UpdateTable = "`grafica_erradicacion_fuerza_departamento`";

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
			return "grafica_erradicacion_fuerza_departamentolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "grafica_erradicacion_fuerza_departamentolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("grafica_erradicacion_fuerza_departamentoview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("grafica_erradicacion_fuerza_departamentoview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "grafica_erradicacion_fuerza_departamentoadd.php?" . $this->UrlParm($parm);
		else
			return "grafica_erradicacion_fuerza_departamentoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("grafica_erradicacion_fuerza_departamentoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("grafica_erradicacion_fuerza_departamentoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("grafica_erradicacion_fuerza_departamentodelete.php", $this->UrlParm());
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
		$this->fuerza->setDbValue($rs->fields('fuerza'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->aF1o->setDbValue($rs->fields('año'));
		$this->fase->setDbValue($rs->fields('fase'));
		$this->Profesional_especializado->setDbValue($rs->fields('Profesional_especializado'));
		$this->Ha_Coca->setDbValue($rs->fields('Ha_Coca'));
		$this->Ha_Amapola->setDbValue($rs->fields('Ha_Amapola'));
		$this->Ha_Marihuana->setDbValue($rs->fields('Ha_Marihuana'));
		$this->Total_erradicado->setDbValue($rs->fields('Total_erradicado'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// fuerza
		// Departamento
		// Muncipio
		// Punto
		// año
		// fase
		// Profesional_especializado
		// Ha_Coca
		// Ha_Amapola
		// Ha_Marihuana
		// Total_erradicado
		// fuerza

		$this->fuerza->ViewValue = $this->fuerza->CurrentValue;
		$this->fuerza->ViewCustomAttributes = "";

		// Departamento
		$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
		$this->Departamento->ViewCustomAttributes = "";

		// Muncipio
		$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
		$this->Muncipio->ViewCustomAttributes = "";

		// Punto
		$this->Punto->ViewValue = $this->Punto->CurrentValue;
		$this->Punto->ViewCustomAttributes = "";

		// año
		$this->aF1o->ViewValue = $this->aF1o->CurrentValue;
		$this->aF1o->ViewCustomAttributes = "";

		// fase
		$this->fase->ViewValue = $this->fase->CurrentValue;
		$this->fase->ViewCustomAttributes = "";

		// Profesional_especializado
		$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->CurrentValue;
		$this->Profesional_especializado->ViewCustomAttributes = "";

		// Ha_Coca
		$this->Ha_Coca->ViewValue = $this->Ha_Coca->CurrentValue;
		$this->Ha_Coca->ViewCustomAttributes = "";

		// Ha_Amapola
		$this->Ha_Amapola->ViewValue = $this->Ha_Amapola->CurrentValue;
		$this->Ha_Amapola->ViewCustomAttributes = "";

		// Ha_Marihuana
		$this->Ha_Marihuana->ViewValue = $this->Ha_Marihuana->CurrentValue;
		$this->Ha_Marihuana->ViewCustomAttributes = "";

		// Total_erradicado
		$this->Total_erradicado->ViewValue = $this->Total_erradicado->CurrentValue;
		$this->Total_erradicado->ViewCustomAttributes = "";

		// fuerza
		$this->fuerza->LinkCustomAttributes = "";
		$this->fuerza->HrefValue = "";
		$this->fuerza->TooltipValue = "";

		// Departamento
		$this->Departamento->LinkCustomAttributes = "";
		$this->Departamento->HrefValue = "";
		$this->Departamento->TooltipValue = "";

		// Muncipio
		$this->Muncipio->LinkCustomAttributes = "";
		$this->Muncipio->HrefValue = "";
		$this->Muncipio->TooltipValue = "";

		// Punto
		$this->Punto->LinkCustomAttributes = "";
		$this->Punto->HrefValue = "";
		$this->Punto->TooltipValue = "";

		// año
		$this->aF1o->LinkCustomAttributes = "";
		$this->aF1o->HrefValue = "";
		$this->aF1o->TooltipValue = "";

		// fase
		$this->fase->LinkCustomAttributes = "";
		$this->fase->HrefValue = "";
		$this->fase->TooltipValue = "";

		// Profesional_especializado
		$this->Profesional_especializado->LinkCustomAttributes = "";
		$this->Profesional_especializado->HrefValue = "";
		$this->Profesional_especializado->TooltipValue = "";

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

		// Total_erradicado
		$this->Total_erradicado->LinkCustomAttributes = "";
		$this->Total_erradicado->HrefValue = "";
		$this->Total_erradicado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// fuerza
		$this->fuerza->EditAttrs["class"] = "form-control";
		$this->fuerza->EditCustomAttributes = "";
		$this->fuerza->EditValue = ew_HtmlEncode($this->fuerza->CurrentValue);
		$this->fuerza->PlaceHolder = ew_RemoveHtml($this->fuerza->FldCaption());

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

		// Punto
		$this->Punto->EditAttrs["class"] = "form-control";
		$this->Punto->EditCustomAttributes = "";
		$this->Punto->EditValue = ew_HtmlEncode($this->Punto->CurrentValue);
		$this->Punto->PlaceHolder = ew_RemoveHtml($this->Punto->FldCaption());

		// año
		$this->aF1o->EditAttrs["class"] = "form-control";
		$this->aF1o->EditCustomAttributes = "";
		$this->aF1o->EditValue = ew_HtmlEncode($this->aF1o->CurrentValue);
		$this->aF1o->PlaceHolder = ew_RemoveHtml($this->aF1o->FldCaption());

		// fase
		$this->fase->EditAttrs["class"] = "form-control";
		$this->fase->EditCustomAttributes = "";
		$this->fase->EditValue = ew_HtmlEncode($this->fase->CurrentValue);
		$this->fase->PlaceHolder = ew_RemoveHtml($this->fase->FldCaption());

		// Profesional_especializado
		$this->Profesional_especializado->EditAttrs["class"] = "form-control";
		$this->Profesional_especializado->EditCustomAttributes = "";
		$this->Profesional_especializado->EditValue = ew_HtmlEncode($this->Profesional_especializado->CurrentValue);
		$this->Profesional_especializado->PlaceHolder = ew_RemoveHtml($this->Profesional_especializado->FldCaption());

		// Ha_Coca
		$this->Ha_Coca->EditAttrs["class"] = "form-control";
		$this->Ha_Coca->EditCustomAttributes = "";
		$this->Ha_Coca->EditValue = ew_HtmlEncode($this->Ha_Coca->CurrentValue);
		$this->Ha_Coca->PlaceHolder = ew_RemoveHtml($this->Ha_Coca->FldCaption());
		if (strval($this->Ha_Coca->EditValue) <> "" && is_numeric($this->Ha_Coca->EditValue)) $this->Ha_Coca->EditValue = ew_FormatNumber($this->Ha_Coca->EditValue, -2, -1, -2, 0);

		// Ha_Amapola
		$this->Ha_Amapola->EditAttrs["class"] = "form-control";
		$this->Ha_Amapola->EditCustomAttributes = "";
		$this->Ha_Amapola->EditValue = ew_HtmlEncode($this->Ha_Amapola->CurrentValue);
		$this->Ha_Amapola->PlaceHolder = ew_RemoveHtml($this->Ha_Amapola->FldCaption());
		if (strval($this->Ha_Amapola->EditValue) <> "" && is_numeric($this->Ha_Amapola->EditValue)) $this->Ha_Amapola->EditValue = ew_FormatNumber($this->Ha_Amapola->EditValue, -2, -1, -2, 0);

		// Ha_Marihuana
		$this->Ha_Marihuana->EditAttrs["class"] = "form-control";
		$this->Ha_Marihuana->EditCustomAttributes = "";
		$this->Ha_Marihuana->EditValue = ew_HtmlEncode($this->Ha_Marihuana->CurrentValue);
		$this->Ha_Marihuana->PlaceHolder = ew_RemoveHtml($this->Ha_Marihuana->FldCaption());
		if (strval($this->Ha_Marihuana->EditValue) <> "" && is_numeric($this->Ha_Marihuana->EditValue)) $this->Ha_Marihuana->EditValue = ew_FormatNumber($this->Ha_Marihuana->EditValue, -2, -1, -2, 0);

		// Total_erradicado
		$this->Total_erradicado->EditAttrs["class"] = "form-control";
		$this->Total_erradicado->EditCustomAttributes = "";
		$this->Total_erradicado->EditValue = ew_HtmlEncode($this->Total_erradicado->CurrentValue);
		$this->Total_erradicado->PlaceHolder = ew_RemoveHtml($this->Total_erradicado->FldCaption());
		if (strval($this->Total_erradicado->EditValue) <> "" && is_numeric($this->Total_erradicado->EditValue)) $this->Total_erradicado->EditValue = ew_FormatNumber($this->Total_erradicado->EditValue, -2, -1, -2, 0);

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
					if ($this->fuerza->Exportable) $Doc->ExportCaption($this->fuerza);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->aF1o->Exportable) $Doc->ExportCaption($this->aF1o);
					if ($this->fase->Exportable) $Doc->ExportCaption($this->fase);
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
					if ($this->Ha_Coca->Exportable) $Doc->ExportCaption($this->Ha_Coca);
					if ($this->Ha_Amapola->Exportable) $Doc->ExportCaption($this->Ha_Amapola);
					if ($this->Ha_Marihuana->Exportable) $Doc->ExportCaption($this->Ha_Marihuana);
					if ($this->Total_erradicado->Exportable) $Doc->ExportCaption($this->Total_erradicado);
				} else {
					if ($this->fuerza->Exportable) $Doc->ExportCaption($this->fuerza);
					if ($this->Departamento->Exportable) $Doc->ExportCaption($this->Departamento);
					if ($this->Muncipio->Exportable) $Doc->ExportCaption($this->Muncipio);
					if ($this->Punto->Exportable) $Doc->ExportCaption($this->Punto);
					if ($this->aF1o->Exportable) $Doc->ExportCaption($this->aF1o);
					if ($this->fase->Exportable) $Doc->ExportCaption($this->fase);
					if ($this->Profesional_especializado->Exportable) $Doc->ExportCaption($this->Profesional_especializado);
					if ($this->Ha_Coca->Exportable) $Doc->ExportCaption($this->Ha_Coca);
					if ($this->Ha_Amapola->Exportable) $Doc->ExportCaption($this->Ha_Amapola);
					if ($this->Ha_Marihuana->Exportable) $Doc->ExportCaption($this->Ha_Marihuana);
					if ($this->Total_erradicado->Exportable) $Doc->ExportCaption($this->Total_erradicado);
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
						if ($this->fuerza->Exportable) $Doc->ExportField($this->fuerza);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->aF1o->Exportable) $Doc->ExportField($this->aF1o);
						if ($this->fase->Exportable) $Doc->ExportField($this->fase);
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
						if ($this->Ha_Coca->Exportable) $Doc->ExportField($this->Ha_Coca);
						if ($this->Ha_Amapola->Exportable) $Doc->ExportField($this->Ha_Amapola);
						if ($this->Ha_Marihuana->Exportable) $Doc->ExportField($this->Ha_Marihuana);
						if ($this->Total_erradicado->Exportable) $Doc->ExportField($this->Total_erradicado);
					} else {
						if ($this->fuerza->Exportable) $Doc->ExportField($this->fuerza);
						if ($this->Departamento->Exportable) $Doc->ExportField($this->Departamento);
						if ($this->Muncipio->Exportable) $Doc->ExportField($this->Muncipio);
						if ($this->Punto->Exportable) $Doc->ExportField($this->Punto);
						if ($this->aF1o->Exportable) $Doc->ExportField($this->aF1o);
						if ($this->fase->Exportable) $Doc->ExportField($this->fase);
						if ($this->Profesional_especializado->Exportable) $Doc->ExportField($this->Profesional_especializado);
						if ($this->Ha_Coca->Exportable) $Doc->ExportField($this->Ha_Coca);
						if ($this->Ha_Amapola->Exportable) $Doc->ExportField($this->Ha_Amapola);
						if ($this->Ha_Marihuana->Exportable) $Doc->ExportField($this->Ha_Marihuana);
						if ($this->Total_erradicado->Exportable) $Doc->ExportField($this->Total_erradicado);
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
