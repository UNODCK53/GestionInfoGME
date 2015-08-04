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

$fuerza_edit = NULL; // Initialize page object first

class cfuerza_edit extends cfuerza {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'fuerza';

	// Page object name
	var $PageObjName = 'fuerza_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("fuerzalist.php"));
		}

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["Punto"] <> "") {
			$this->Punto->setQueryStringValue($_GET["Punto"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->Punto->CurrentValue == "")
			$this->Page_Terminate("fuerzalist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("fuerzalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Fuerza->FldIsDetailKey) {
			$this->Fuerza->setFormValue($objForm->GetValue("x_Fuerza"));
		}
		if (!$this->Estado->FldIsDetailKey) {
			$this->Estado->setFormValue($objForm->GetValue("x_Estado"));
		}
		if (!$this->AF1o->FldIsDetailKey) {
			$this->AF1o->setFormValue($objForm->GetValue("x_AF1o"));
		}
		if (!$this->Fase->FldIsDetailKey) {
			$this->Fase->setFormValue($objForm->GetValue("x_Fase"));
		}
		if (!$this->_23_del_punto->FldIsDetailKey) {
			$this->_23_del_punto->setFormValue($objForm->GetValue("x__23_del_punto"));
		}
		if (!$this->Punto->FldIsDetailKey) {
			$this->Punto->setFormValue($objForm->GetValue("x_Punto"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Fuerza->CurrentValue = $this->Fuerza->FormValue;
		$this->Estado->CurrentValue = $this->Estado->FormValue;
		$this->AF1o->CurrentValue = $this->AF1o->FormValue;
		$this->Fase->CurrentValue = $this->Fase->FormValue;
		$this->_23_del_punto->CurrentValue = $this->_23_del_punto->FormValue;
		$this->Punto->CurrentValue = $this->Punto->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// Edit refer script
			// Fuerza

			$this->Fuerza->HrefValue = "";

			// Estado
			$this->Estado->HrefValue = "";

			// Año
			$this->AF1o->HrefValue = "";

			// Fase
			$this->Fase->HrefValue = "";

			// #_del_punto
			$this->_23_del_punto->HrefValue = "";

			// Punto
			$this->Punto->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Fuerza->FldIsDetailKey && !is_null($this->Fuerza->FormValue) && $this->Fuerza->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Fuerza->FldCaption(), $this->Fuerza->ReqErrMsg));
		}
		if (!$this->Estado->FldIsDetailKey && !is_null($this->Estado->FormValue) && $this->Estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Estado->FldCaption(), $this->Estado->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// Fuerza
			$this->Fuerza->SetDbValueDef($rsnew, $this->Fuerza->CurrentValue, "", $this->Fuerza->ReadOnly);

			// Estado
			$this->Estado->SetDbValueDef($rsnew, $this->Estado->CurrentValue, "", $this->Estado->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "fuerzalist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($fuerza_edit)) $fuerza_edit = new cfuerza_edit();

// Page init
$fuerza_edit->Page_Init();

// Page main
$fuerza_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fuerza_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fuerza_edit = new ew_Page("fuerza_edit");
fuerza_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = fuerza_edit.PageID; // For backward compatibility

// Form object
var ffuerzaedit = new ew_Form("ffuerzaedit");

// Validate form
ffuerzaedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Fuerza");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $fuerza->Fuerza->FldCaption(), $fuerza->Fuerza->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $fuerza->Estado->FldCaption(), $fuerza->Estado->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ffuerzaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffuerzaedit.ValidateRequired = true;
<?php } else { ?>
ffuerzaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $fuerza_edit->ShowPageHeader(); ?>
<?php
$fuerza_edit->ShowMessage();
?>
<form name="ffuerzaedit" id="ffuerzaedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($fuerza_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $fuerza_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="fuerza">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($fuerza->Fuerza->Visible) { // Fuerza ?>
	<div id="r_Fuerza" class="form-group">
		<label id="elh_fuerza_Fuerza" for="x_Fuerza" class="col-sm-2 control-label ewLabel"><?php echo $fuerza->Fuerza->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $fuerza->Fuerza->CellAttributes() ?>>
<span id="el_fuerza_Fuerza">
<select data-field="x_Fuerza" id="x_Fuerza" name="x_Fuerza"<?php echo $fuerza->Fuerza->EditAttributes() ?>>
<?php
if (is_array($fuerza->Fuerza->EditValue)) {
	$arwrk = $fuerza->Fuerza->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fuerza->Fuerza->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $fuerza->Fuerza->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fuerza->Estado->Visible) { // Estado ?>
	<div id="r_Estado" class="form-group">
		<label id="elh_fuerza_Estado" for="x_Estado" class="col-sm-2 control-label ewLabel"><?php echo $fuerza->Estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $fuerza->Estado->CellAttributes() ?>>
<span id="el_fuerza_Estado">
<select data-field="x_Estado" id="x_Estado" name="x_Estado"<?php echo $fuerza->Estado->EditAttributes() ?>>
<?php
if (is_array($fuerza->Estado->EditValue)) {
	$arwrk = $fuerza->Estado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fuerza->Estado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $fuerza->Estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fuerza->AF1o->Visible) { // Año ?>
	<div id="r_AF1o" class="form-group">
		<label id="elh_fuerza_AF1o" for="x_AF1o" class="col-sm-2 control-label ewLabel"><?php echo $fuerza->AF1o->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fuerza->AF1o->CellAttributes() ?>>
<span id="el_fuerza_AF1o">
<span<?php echo $fuerza->AF1o->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $fuerza->AF1o->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_AF1o" name="x_AF1o" id="x_AF1o" value="<?php echo ew_HtmlEncode($fuerza->AF1o->CurrentValue) ?>">
<?php echo $fuerza->AF1o->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fuerza->Fase->Visible) { // Fase ?>
	<div id="r_Fase" class="form-group">
		<label id="elh_fuerza_Fase" for="x_Fase" class="col-sm-2 control-label ewLabel"><?php echo $fuerza->Fase->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fuerza->Fase->CellAttributes() ?>>
<span id="el_fuerza_Fase">
<span<?php echo $fuerza->Fase->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $fuerza->Fase->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Fase" name="x_Fase" id="x_Fase" value="<?php echo ew_HtmlEncode($fuerza->Fase->CurrentValue) ?>">
<?php echo $fuerza->Fase->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fuerza->_23_del_punto->Visible) { // #_del_punto ?>
	<div id="r__23_del_punto" class="form-group">
		<label id="elh_fuerza__23_del_punto" for="x__23_del_punto" class="col-sm-2 control-label ewLabel"><?php echo $fuerza->_23_del_punto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fuerza->_23_del_punto->CellAttributes() ?>>
<span id="el_fuerza__23_del_punto">
<span<?php echo $fuerza->_23_del_punto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $fuerza->_23_del_punto->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x__23_del_punto" name="x__23_del_punto" id="x__23_del_punto" value="<?php echo ew_HtmlEncode($fuerza->_23_del_punto->CurrentValue) ?>">
<?php echo $fuerza->_23_del_punto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($fuerza->Punto->Visible) { // Punto ?>
	<div id="r_Punto" class="form-group">
		<label id="elh_fuerza_Punto" for="x_Punto" class="col-sm-2 control-label ewLabel"><?php echo $fuerza->Punto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $fuerza->Punto->CellAttributes() ?>>
<span id="el_fuerza_Punto">
<span<?php echo $fuerza->Punto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $fuerza->Punto->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Punto" name="x_Punto" id="x_Punto" value="<?php echo ew_HtmlEncode($fuerza->Punto->CurrentValue) ?>">
<?php echo $fuerza->Punto->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
ffuerzaedit.Init();
</script>
<?php
$fuerza_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fuerza_edit->Page_Terminate();
?>
