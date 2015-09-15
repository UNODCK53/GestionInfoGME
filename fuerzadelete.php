<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "fuerzainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$fuerza_delete = NULL; // Initialize page object first

class cfuerza_delete extends cfuerza {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'fuerza';

	// Page object name
	var $PageObjName = 'fuerza_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (fuerza)
		if (!isset($GLOBALS["fuerza"]) || get_class($GLOBALS["fuerza"]) == "cfuerza") {
			$GLOBALS["fuerza"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fuerza"];
		}

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fuerza', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("fuerzalist.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $fuerza;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($fuerza);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("fuerzalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in fuerza class, fuerzainfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->Fuerza->setDbValue($rs->fields('Fuerza'));
		$this->Estado->setDbValue($rs->fields('Estado'));
		$this->AF1o->setDbValue($rs->fields('Año'));
		$this->Fase->setDbValue($rs->fields('Fase'));
		$this->_23_del_punto->setDbValue($rs->fields('#_del_punto'));
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->Profesional_especializado->setDbValue($rs->fields('Profesional_especializado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Fuerza->DbValue = $row['Fuerza'];
		$this->Estado->DbValue = $row['Estado'];
		$this->AF1o->DbValue = $row['Año'];
		$this->Fase->DbValue = $row['Fase'];
		$this->_23_del_punto->DbValue = $row['#_del_punto'];
		$this->Punto->DbValue = $row['Punto'];
		$this->Profesional_especializado->DbValue = $row['Profesional_especializado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Fuerza
		// Estado
		// Año
		// Fase
		// #_del_punto
		// Punto
		// Profesional_especializado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Punto'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "fuerzalist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($fuerza_delete)) $fuerza_delete = new cfuerza_delete();

// Page init
$fuerza_delete->Page_Init();

// Page main
$fuerza_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fuerza_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fuerza_delete = new ew_Page("fuerza_delete");
fuerza_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = fuerza_delete.PageID; // For backward compatibility

// Form object
var ffuerzadelete = new ew_Form("ffuerzadelete");

// Form_CustomValidate event
ffuerzadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffuerzadelete.ValidateRequired = true;
<?php } else { ?>
ffuerzadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($fuerza_delete->Recordset = $fuerza_delete->LoadRecordset())
	$fuerza_deleteTotalRecs = $fuerza_delete->Recordset->RecordCount(); // Get record count
if ($fuerza_deleteTotalRecs <= 0) { // No record found, exit
	if ($fuerza_delete->Recordset)
		$fuerza_delete->Recordset->Close();
	$fuerza_delete->Page_Terminate("fuerzalist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $fuerza_delete->ShowPageHeader(); ?>
<?php
$fuerza_delete->ShowMessage();
?>
<form name="ffuerzadelete" id="ffuerzadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($fuerza_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $fuerza_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="fuerza">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($fuerza_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $fuerza->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($fuerza->Fuerza->Visible) { // Fuerza ?>
		<th><span id="elh_fuerza_Fuerza" class="fuerza_Fuerza"><?php echo $fuerza->Fuerza->FldCaption() ?></span></th>
<?php } ?>
<?php if ($fuerza->Estado->Visible) { // Estado ?>
		<th><span id="elh_fuerza_Estado" class="fuerza_Estado"><?php echo $fuerza->Estado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($fuerza->AF1o->Visible) { // Año ?>
		<th><span id="elh_fuerza_AF1o" class="fuerza_AF1o"><?php echo $fuerza->AF1o->FldCaption() ?></span></th>
<?php } ?>
<?php if ($fuerza->Fase->Visible) { // Fase ?>
		<th><span id="elh_fuerza_Fase" class="fuerza_Fase"><?php echo $fuerza->Fase->FldCaption() ?></span></th>
<?php } ?>
<?php if ($fuerza->_23_del_punto->Visible) { // #_del_punto ?>
		<th><span id="elh_fuerza__23_del_punto" class="fuerza__23_del_punto"><?php echo $fuerza->_23_del_punto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($fuerza->Punto->Visible) { // Punto ?>
		<th><span id="elh_fuerza_Punto" class="fuerza_Punto"><?php echo $fuerza->Punto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($fuerza->Profesional_especializado->Visible) { // Profesional_especializado ?>
		<th><span id="elh_fuerza_Profesional_especializado" class="fuerza_Profesional_especializado"><?php echo $fuerza->Profesional_especializado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$fuerza_delete->RecCnt = 0;
$i = 0;
while (!$fuerza_delete->Recordset->EOF) {
	$fuerza_delete->RecCnt++;
	$fuerza_delete->RowCnt++;

	// Set row properties
	$fuerza->ResetAttrs();
	$fuerza->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$fuerza_delete->LoadRowValues($fuerza_delete->Recordset);

	// Render row
	$fuerza_delete->RenderRow();
?>
	<tr<?php echo $fuerza->RowAttributes() ?>>
<?php if ($fuerza->Fuerza->Visible) { // Fuerza ?>
		<td<?php echo $fuerza->Fuerza->CellAttributes() ?>>
<span id="el<?php echo $fuerza_delete->RowCnt ?>_fuerza_Fuerza" class="fuerza_Fuerza">
<span<?php echo $fuerza->Fuerza->ViewAttributes() ?>>
<?php echo $fuerza->Fuerza->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($fuerza->Estado->Visible) { // Estado ?>
		<td<?php echo $fuerza->Estado->CellAttributes() ?>>
<span id="el<?php echo $fuerza_delete->RowCnt ?>_fuerza_Estado" class="fuerza_Estado">
<span<?php echo $fuerza->Estado->ViewAttributes() ?>>
<?php echo $fuerza->Estado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($fuerza->AF1o->Visible) { // Año ?>
		<td<?php echo $fuerza->AF1o->CellAttributes() ?>>
<span id="el<?php echo $fuerza_delete->RowCnt ?>_fuerza_AF1o" class="fuerza_AF1o">
<span<?php echo $fuerza->AF1o->ViewAttributes() ?>>
<?php echo $fuerza->AF1o->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($fuerza->Fase->Visible) { // Fase ?>
		<td<?php echo $fuerza->Fase->CellAttributes() ?>>
<span id="el<?php echo $fuerza_delete->RowCnt ?>_fuerza_Fase" class="fuerza_Fase">
<span<?php echo $fuerza->Fase->ViewAttributes() ?>>
<?php echo $fuerza->Fase->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($fuerza->_23_del_punto->Visible) { // #_del_punto ?>
		<td<?php echo $fuerza->_23_del_punto->CellAttributes() ?>>
<span id="el<?php echo $fuerza_delete->RowCnt ?>_fuerza__23_del_punto" class="fuerza__23_del_punto">
<span<?php echo $fuerza->_23_del_punto->ViewAttributes() ?>>
<?php echo $fuerza->_23_del_punto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($fuerza->Punto->Visible) { // Punto ?>
		<td<?php echo $fuerza->Punto->CellAttributes() ?>>
<span id="el<?php echo $fuerza_delete->RowCnt ?>_fuerza_Punto" class="fuerza_Punto">
<span<?php echo $fuerza->Punto->ViewAttributes() ?>>
<?php echo $fuerza->Punto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($fuerza->Profesional_especializado->Visible) { // Profesional_especializado ?>
		<td<?php echo $fuerza->Profesional_especializado->CellAttributes() ?>>
<span id="el<?php echo $fuerza_delete->RowCnt ?>_fuerza_Profesional_especializado" class="fuerza_Profesional_especializado">
<span<?php echo $fuerza->Profesional_especializado->ViewAttributes() ?>>
<?php echo $fuerza->Profesional_especializado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$fuerza_delete->Recordset->MoveNext();
}
$fuerza_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ffuerzadelete.Init();
</script>
<?php
$fuerza_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fuerza_delete->Page_Terminate();
?>
