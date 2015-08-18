<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view_cavinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view_cav_edit = NULL; // Initialize page object first

class cview_cav_edit extends cview_cav {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_cav';

	// Page object name
	var $PageObjName = 'view_cav_edit';

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

		// Table object (view_cav)
		if (!isset($GLOBALS["view_cav"]) || get_class($GLOBALS["view_cav"]) == "cview_cav") {
			$GLOBALS["view_cav"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_cav"];
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
			define("EW_TABLE_NAME", 'view_cav', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view_cavlist.php"));
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
		global $EW_EXPORT, $view_cav;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_cav);
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
		if (@$_GET["llave"] <> "") {
			$this->llave->setQueryStringValue($_GET["llave"]);
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
		if ($this->llave->CurrentValue == "")
			$this->Page_Terminate("view_cavlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("view_cavlist.php"); // No matching record, return to list
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
		if (!$this->llave->FldIsDetailKey) {
			$this->llave->setFormValue($objForm->GetValue("x_llave"));
		}
		if (!$this->F_Sincron->FldIsDetailKey) {
			$this->F_Sincron->setFormValue($objForm->GetValue("x_F_Sincron"));
			$this->F_Sincron->CurrentValue = ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 7);
		}
		if (!$this->USUARIO->FldIsDetailKey) {
			$this->USUARIO->setFormValue($objForm->GetValue("x_USUARIO"));
		}
		if (!$this->Cargo_gme->FldIsDetailKey) {
			$this->Cargo_gme->setFormValue($objForm->GetValue("x_Cargo_gme"));
		}
		if (!$this->Num_AV->FldIsDetailKey) {
			$this->Num_AV->setFormValue($objForm->GetValue("x_Num_AV"));
		}
		if (!$this->NOM_APOYO->FldIsDetailKey) {
			$this->NOM_APOYO->setFormValue($objForm->GetValue("x_NOM_APOYO"));
		}
		if (!$this->Otro_Nom_Apoyo->FldIsDetailKey) {
			$this->Otro_Nom_Apoyo->setFormValue($objForm->GetValue("x_Otro_Nom_Apoyo"));
		}
		if (!$this->Otro_CC_Apoyo->FldIsDetailKey) {
			$this->Otro_CC_Apoyo->setFormValue($objForm->GetValue("x_Otro_CC_Apoyo"));
		}
		if (!$this->NOM_PE->FldIsDetailKey) {
			$this->NOM_PE->setFormValue($objForm->GetValue("x_NOM_PE"));
		}
		if (!$this->Otro_PE->FldIsDetailKey) {
			$this->Otro_PE->setFormValue($objForm->GetValue("x_Otro_PE"));
		}
		if (!$this->Departamento->FldIsDetailKey) {
			$this->Departamento->setFormValue($objForm->GetValue("x_Departamento"));
		}
		if (!$this->Muncipio->FldIsDetailKey) {
			$this->Muncipio->setFormValue($objForm->GetValue("x_Muncipio"));
		}
		if (!$this->NOM_VDA->FldIsDetailKey) {
			$this->NOM_VDA->setFormValue($objForm->GetValue("x_NOM_VDA"));
		}
		if (!$this->NO_E->FldIsDetailKey) {
			$this->NO_E->setFormValue($objForm->GetValue("x_NO_E"));
		}
		if (!$this->NO_OF->FldIsDetailKey) {
			$this->NO_OF->setFormValue($objForm->GetValue("x_NO_OF"));
		}
		if (!$this->NO_SUBOF->FldIsDetailKey) {
			$this->NO_SUBOF->setFormValue($objForm->GetValue("x_NO_SUBOF"));
		}
		if (!$this->NO_SOL->FldIsDetailKey) {
			$this->NO_SOL->setFormValue($objForm->GetValue("x_NO_SOL"));
		}
		if (!$this->NO_PATRU->FldIsDetailKey) {
			$this->NO_PATRU->setFormValue($objForm->GetValue("x_NO_PATRU"));
		}
		if (!$this->Nom_enfer->FldIsDetailKey) {
			$this->Nom_enfer->setFormValue($objForm->GetValue("x_Nom_enfer"));
		}
		if (!$this->Otro_Nom_Enfer->FldIsDetailKey) {
			$this->Otro_Nom_Enfer->setFormValue($objForm->GetValue("x_Otro_Nom_Enfer"));
		}
		if (!$this->Otro_CC_Enfer->FldIsDetailKey) {
			$this->Otro_CC_Enfer->setFormValue($objForm->GetValue("x_Otro_CC_Enfer"));
		}
		if (!$this->Armada->FldIsDetailKey) {
			$this->Armada->setFormValue($objForm->GetValue("x_Armada"));
		}
		if (!$this->Ejercito->FldIsDetailKey) {
			$this->Ejercito->setFormValue($objForm->GetValue("x_Ejercito"));
		}
		if (!$this->Policia->FldIsDetailKey) {
			$this->Policia->setFormValue($objForm->GetValue("x_Policia"));
		}
		if (!$this->NOM_UNIDAD->FldIsDetailKey) {
			$this->NOM_UNIDAD->setFormValue($objForm->GetValue("x_NOM_UNIDAD"));
		}
		if (!$this->NOM_COMAN->FldIsDetailKey) {
			$this->NOM_COMAN->setFormValue($objForm->GetValue("x_NOM_COMAN"));
		}
		if (!$this->CC_COMAN->FldIsDetailKey) {
			$this->CC_COMAN->setFormValue($objForm->GetValue("x_CC_COMAN"));
		}
		if (!$this->TEL_COMAN->FldIsDetailKey) {
			$this->TEL_COMAN->setFormValue($objForm->GetValue("x_TEL_COMAN"));
		}
		if (!$this->RANGO_COMAN->FldIsDetailKey) {
			$this->RANGO_COMAN->setFormValue($objForm->GetValue("x_RANGO_COMAN"));
		}
		if (!$this->Otro_rango->FldIsDetailKey) {
			$this->Otro_rango->setFormValue($objForm->GetValue("x_Otro_rango"));
		}
		if (!$this->NO_GDETECCION->FldIsDetailKey) {
			$this->NO_GDETECCION->setFormValue($objForm->GetValue("x_NO_GDETECCION"));
		}
		if (!$this->NO_BINOMIO->FldIsDetailKey) {
			$this->NO_BINOMIO->setFormValue($objForm->GetValue("x_NO_BINOMIO"));
		}
		if (!$this->FECHA_INTO_AV->FldIsDetailKey) {
			$this->FECHA_INTO_AV->setFormValue($objForm->GetValue("x_FECHA_INTO_AV"));
		}
		if (!$this->DIA->FldIsDetailKey) {
			$this->DIA->setFormValue($objForm->GetValue("x_DIA"));
		}
		if (!$this->MES->FldIsDetailKey) {
			$this->MES->setFormValue($objForm->GetValue("x_MES"));
		}
		if (!$this->LATITUD->FldIsDetailKey) {
			$this->LATITUD->setFormValue($objForm->GetValue("x_LATITUD"));
		}
		if (!$this->GRA_LAT->FldIsDetailKey) {
			$this->GRA_LAT->setFormValue($objForm->GetValue("x_GRA_LAT"));
		}
		if (!$this->MIN_LAT->FldIsDetailKey) {
			$this->MIN_LAT->setFormValue($objForm->GetValue("x_MIN_LAT"));
		}
		if (!$this->SEG_LAT->FldIsDetailKey) {
			$this->SEG_LAT->setFormValue($objForm->GetValue("x_SEG_LAT"));
		}
		if (!$this->GRA_LONG->FldIsDetailKey) {
			$this->GRA_LONG->setFormValue($objForm->GetValue("x_GRA_LONG"));
		}
		if (!$this->MIN_LONG->FldIsDetailKey) {
			$this->MIN_LONG->setFormValue($objForm->GetValue("x_MIN_LONG"));
		}
		if (!$this->SEG_LONG->FldIsDetailKey) {
			$this->SEG_LONG->setFormValue($objForm->GetValue("x_SEG_LONG"));
		}
		if (!$this->OBSERVACION->FldIsDetailKey) {
			$this->OBSERVACION->setFormValue($objForm->GetValue("x_OBSERVACION"));
		}
		if (!$this->AD1O->FldIsDetailKey) {
			$this->AD1O->setFormValue($objForm->GetValue("x_AD1O"));
		}
		if (!$this->FASE->FldIsDetailKey) {
			$this->FASE->setFormValue($objForm->GetValue("x_FASE"));
		}
		if (!$this->Modificado->FldIsDetailKey) {
			$this->Modificado->setFormValue($objForm->GetValue("x_Modificado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->llave->CurrentValue = $this->llave->FormValue;
		$this->F_Sincron->CurrentValue = $this->F_Sincron->FormValue;
		$this->F_Sincron->CurrentValue = ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 7);
		$this->USUARIO->CurrentValue = $this->USUARIO->FormValue;
		$this->Cargo_gme->CurrentValue = $this->Cargo_gme->FormValue;
		$this->Num_AV->CurrentValue = $this->Num_AV->FormValue;
		$this->NOM_APOYO->CurrentValue = $this->NOM_APOYO->FormValue;
		$this->Otro_Nom_Apoyo->CurrentValue = $this->Otro_Nom_Apoyo->FormValue;
		$this->Otro_CC_Apoyo->CurrentValue = $this->Otro_CC_Apoyo->FormValue;
		$this->NOM_PE->CurrentValue = $this->NOM_PE->FormValue;
		$this->Otro_PE->CurrentValue = $this->Otro_PE->FormValue;
		$this->Departamento->CurrentValue = $this->Departamento->FormValue;
		$this->Muncipio->CurrentValue = $this->Muncipio->FormValue;
		$this->NOM_VDA->CurrentValue = $this->NOM_VDA->FormValue;
		$this->NO_E->CurrentValue = $this->NO_E->FormValue;
		$this->NO_OF->CurrentValue = $this->NO_OF->FormValue;
		$this->NO_SUBOF->CurrentValue = $this->NO_SUBOF->FormValue;
		$this->NO_SOL->CurrentValue = $this->NO_SOL->FormValue;
		$this->NO_PATRU->CurrentValue = $this->NO_PATRU->FormValue;
		$this->Nom_enfer->CurrentValue = $this->Nom_enfer->FormValue;
		$this->Otro_Nom_Enfer->CurrentValue = $this->Otro_Nom_Enfer->FormValue;
		$this->Otro_CC_Enfer->CurrentValue = $this->Otro_CC_Enfer->FormValue;
		$this->Armada->CurrentValue = $this->Armada->FormValue;
		$this->Ejercito->CurrentValue = $this->Ejercito->FormValue;
		$this->Policia->CurrentValue = $this->Policia->FormValue;
		$this->NOM_UNIDAD->CurrentValue = $this->NOM_UNIDAD->FormValue;
		$this->NOM_COMAN->CurrentValue = $this->NOM_COMAN->FormValue;
		$this->CC_COMAN->CurrentValue = $this->CC_COMAN->FormValue;
		$this->TEL_COMAN->CurrentValue = $this->TEL_COMAN->FormValue;
		$this->RANGO_COMAN->CurrentValue = $this->RANGO_COMAN->FormValue;
		$this->Otro_rango->CurrentValue = $this->Otro_rango->FormValue;
		$this->NO_GDETECCION->CurrentValue = $this->NO_GDETECCION->FormValue;
		$this->NO_BINOMIO->CurrentValue = $this->NO_BINOMIO->FormValue;
		$this->FECHA_INTO_AV->CurrentValue = $this->FECHA_INTO_AV->FormValue;
		$this->DIA->CurrentValue = $this->DIA->FormValue;
		$this->MES->CurrentValue = $this->MES->FormValue;
		$this->LATITUD->CurrentValue = $this->LATITUD->FormValue;
		$this->GRA_LAT->CurrentValue = $this->GRA_LAT->FormValue;
		$this->MIN_LAT->CurrentValue = $this->MIN_LAT->FormValue;
		$this->SEG_LAT->CurrentValue = $this->SEG_LAT->FormValue;
		$this->GRA_LONG->CurrentValue = $this->GRA_LONG->FormValue;
		$this->MIN_LONG->CurrentValue = $this->MIN_LONG->FormValue;
		$this->SEG_LONG->CurrentValue = $this->SEG_LONG->FormValue;
		$this->OBSERVACION->CurrentValue = $this->OBSERVACION->FormValue;
		$this->AD1O->CurrentValue = $this->AD1O->FormValue;
		$this->FASE->CurrentValue = $this->FASE->FormValue;
		$this->Modificado->CurrentValue = $this->Modificado->FormValue;
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
		$this->llave->setDbValue($rs->fields('llave'));
		$this->F_Sincron->setDbValue($rs->fields('F_Sincron'));
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
		$this->Modificado->setDbValue($rs->fields('Modificado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->llave->DbValue = $row['llave'];
		$this->F_Sincron->DbValue = $row['F_Sincron'];
		$this->USUARIO->DbValue = $row['USUARIO'];
		$this->Cargo_gme->DbValue = $row['Cargo_gme'];
		$this->Num_AV->DbValue = $row['Num_AV'];
		$this->NOM_APOYO->DbValue = $row['NOM_APOYO'];
		$this->Otro_Nom_Apoyo->DbValue = $row['Otro_Nom_Apoyo'];
		$this->Otro_CC_Apoyo->DbValue = $row['Otro_CC_Apoyo'];
		$this->NOM_PE->DbValue = $row['NOM_PE'];
		$this->Otro_PE->DbValue = $row['Otro_PE'];
		$this->Departamento->DbValue = $row['Departamento'];
		$this->Muncipio->DbValue = $row['Muncipio'];
		$this->NOM_VDA->DbValue = $row['NOM_VDA'];
		$this->NO_E->DbValue = $row['NO_E'];
		$this->NO_OF->DbValue = $row['NO_OF'];
		$this->NO_SUBOF->DbValue = $row['NO_SUBOF'];
		$this->NO_SOL->DbValue = $row['NO_SOL'];
		$this->NO_PATRU->DbValue = $row['NO_PATRU'];
		$this->Nom_enfer->DbValue = $row['Nom_enfer'];
		$this->Otro_Nom_Enfer->DbValue = $row['Otro_Nom_Enfer'];
		$this->Otro_CC_Enfer->DbValue = $row['Otro_CC_Enfer'];
		$this->Armada->DbValue = $row['Armada'];
		$this->Ejercito->DbValue = $row['Ejercito'];
		$this->Policia->DbValue = $row['Policia'];
		$this->NOM_UNIDAD->DbValue = $row['NOM_UNIDAD'];
		$this->NOM_COMAN->DbValue = $row['NOM_COMAN'];
		$this->CC_COMAN->DbValue = $row['CC_COMAN'];
		$this->TEL_COMAN->DbValue = $row['TEL_COMAN'];
		$this->RANGO_COMAN->DbValue = $row['RANGO_COMAN'];
		$this->Otro_rango->DbValue = $row['Otro_rango'];
		$this->NO_GDETECCION->DbValue = $row['NO_GDETECCION'];
		$this->NO_BINOMIO->DbValue = $row['NO_BINOMIO'];
		$this->FECHA_INTO_AV->DbValue = $row['FECHA_INTO_AV'];
		$this->DIA->DbValue = $row['DIA'];
		$this->MES->DbValue = $row['MES'];
		$this->LATITUD->DbValue = $row['LATITUD'];
		$this->GRA_LAT->DbValue = $row['GRA_LAT'];
		$this->MIN_LAT->DbValue = $row['MIN_LAT'];
		$this->SEG_LAT->DbValue = $row['SEG_LAT'];
		$this->GRA_LONG->DbValue = $row['GRA_LONG'];
		$this->MIN_LONG->DbValue = $row['MIN_LONG'];
		$this->SEG_LONG->DbValue = $row['SEG_LONG'];
		$this->OBSERVACION->DbValue = $row['OBSERVACION'];
		$this->AD1O->DbValue = $row['AÑO'];
		$this->FASE->DbValue = $row['FASE'];
		$this->Modificado->DbValue = $row['Modificado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->Armada->FormValue == $this->Armada->CurrentValue && is_numeric(ew_StrToFloat($this->Armada->CurrentValue)))
			$this->Armada->CurrentValue = ew_StrToFloat($this->Armada->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Ejercito->FormValue == $this->Ejercito->CurrentValue && is_numeric(ew_StrToFloat($this->Ejercito->CurrentValue)))
			$this->Ejercito->CurrentValue = ew_StrToFloat($this->Ejercito->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Policia->FormValue == $this->Policia->CurrentValue && is_numeric(ew_StrToFloat($this->Policia->CurrentValue)))
			$this->Policia->CurrentValue = ew_StrToFloat($this->Policia->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LAT->FormValue == $this->SEG_LAT->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LAT->CurrentValue)))
			$this->SEG_LAT->CurrentValue = ew_StrToFloat($this->SEG_LAT->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LONG->FormValue == $this->SEG_LONG->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LONG->CurrentValue)))
			$this->SEG_LONG->CurrentValue = ew_StrToFloat($this->SEG_LONG->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// llave
		// F_Sincron
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
		// Modificado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// Modificado
			$this->Modificado->ViewValue = $this->Modificado->CurrentValue;
			$this->Modificado->ViewCustomAttributes = "";

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

			// Modificado
			$this->Modificado->LinkCustomAttributes = "";
			$this->Modificado->HrefValue = "";
			$this->Modificado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// llave
			$this->llave->EditAttrs["class"] = "form-control";
			$this->llave->EditCustomAttributes = "";
			$this->llave->EditValue = $this->llave->CurrentValue;
			$this->llave->ViewCustomAttributes = "";

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

			// Num_AV
			$this->Num_AV->EditAttrs["class"] = "form-control";
			$this->Num_AV->EditCustomAttributes = "";
			$this->Num_AV->EditValue = $this->Num_AV->CurrentValue;
			$this->Num_AV->ViewCustomAttributes = "";

			// NOM_APOYO
			$this->NOM_APOYO->EditAttrs["class"] = "form-control";
			$this->NOM_APOYO->EditCustomAttributes = "";
			$this->NOM_APOYO->EditValue = $this->NOM_APOYO->CurrentValue;
			$this->NOM_APOYO->ViewCustomAttributes = "";

			// Otro_Nom_Apoyo
			$this->Otro_Nom_Apoyo->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_Apoyo->EditCustomAttributes = "";
			$this->Otro_Nom_Apoyo->EditValue = $this->Otro_Nom_Apoyo->CurrentValue;
			$this->Otro_Nom_Apoyo->ViewCustomAttributes = "";

			// Otro_CC_Apoyo
			$this->Otro_CC_Apoyo->EditAttrs["class"] = "form-control";
			$this->Otro_CC_Apoyo->EditCustomAttributes = "";
			$this->Otro_CC_Apoyo->EditValue = $this->Otro_CC_Apoyo->CurrentValue;
			$this->Otro_CC_Apoyo->ViewCustomAttributes = "";

			// NOM_PE
			$this->NOM_PE->EditAttrs["class"] = "form-control";
			$this->NOM_PE->EditCustomAttributes = "";
			$this->NOM_PE->EditValue = $this->NOM_PE->CurrentValue;
			$this->NOM_PE->ViewCustomAttributes = "";

			// Otro_PE
			$this->Otro_PE->EditAttrs["class"] = "form-control";
			$this->Otro_PE->EditCustomAttributes = "";
			$this->Otro_PE->EditValue = $this->Otro_PE->CurrentValue;
			$this->Otro_PE->ViewCustomAttributes = "";

			// Departamento
			$this->Departamento->EditAttrs["class"] = "form-control";
			$this->Departamento->EditCustomAttributes = "";
			$this->Departamento->EditValue = $this->Departamento->CurrentValue;
			$this->Departamento->ViewCustomAttributes = "";

			// Muncipio
			$this->Muncipio->EditAttrs["class"] = "form-control";
			$this->Muncipio->EditCustomAttributes = "";
			$this->Muncipio->EditValue = $this->Muncipio->CurrentValue;
			$this->Muncipio->ViewCustomAttributes = "";

			// NOM_VDA
			$this->NOM_VDA->EditAttrs["class"] = "form-control";
			$this->NOM_VDA->EditCustomAttributes = "";
			$this->NOM_VDA->EditValue = $this->NOM_VDA->CurrentValue;
			$this->NOM_VDA->ViewCustomAttributes = "";

			// NO_E
			$this->NO_E->EditAttrs["class"] = "form-control";
			$this->NO_E->EditCustomAttributes = "";
			$this->NO_E->EditValue = $this->NO_E->CurrentValue;
			$this->NO_E->ViewCustomAttributes = "";

			// NO_OF
			$this->NO_OF->EditAttrs["class"] = "form-control";
			$this->NO_OF->EditCustomAttributes = "";
			$this->NO_OF->EditValue = $this->NO_OF->CurrentValue;
			$this->NO_OF->ViewCustomAttributes = "";

			// NO_SUBOF
			$this->NO_SUBOF->EditAttrs["class"] = "form-control";
			$this->NO_SUBOF->EditCustomAttributes = "";
			$this->NO_SUBOF->EditValue = $this->NO_SUBOF->CurrentValue;
			$this->NO_SUBOF->ViewCustomAttributes = "";

			// NO_SOL
			$this->NO_SOL->EditAttrs["class"] = "form-control";
			$this->NO_SOL->EditCustomAttributes = "";
			$this->NO_SOL->EditValue = $this->NO_SOL->CurrentValue;
			$this->NO_SOL->ViewCustomAttributes = "";

			// NO_PATRU
			$this->NO_PATRU->EditAttrs["class"] = "form-control";
			$this->NO_PATRU->EditCustomAttributes = "";
			$this->NO_PATRU->EditValue = $this->NO_PATRU->CurrentValue;
			$this->NO_PATRU->ViewCustomAttributes = "";

			// Nom_enfer
			$this->Nom_enfer->EditAttrs["class"] = "form-control";
			$this->Nom_enfer->EditCustomAttributes = "";
			$this->Nom_enfer->EditValue = $this->Nom_enfer->CurrentValue;
			$this->Nom_enfer->ViewCustomAttributes = "";

			// Otro_Nom_Enfer
			$this->Otro_Nom_Enfer->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_Enfer->EditCustomAttributes = "";
			$this->Otro_Nom_Enfer->EditValue = $this->Otro_Nom_Enfer->CurrentValue;
			$this->Otro_Nom_Enfer->ViewCustomAttributes = "";

			// Otro_CC_Enfer
			$this->Otro_CC_Enfer->EditAttrs["class"] = "form-control";
			$this->Otro_CC_Enfer->EditCustomAttributes = "";
			$this->Otro_CC_Enfer->EditValue = $this->Otro_CC_Enfer->CurrentValue;
			$this->Otro_CC_Enfer->ViewCustomAttributes = "";

			// Armada
			$this->Armada->EditAttrs["class"] = "form-control";
			$this->Armada->EditCustomAttributes = "";
			$this->Armada->EditValue = $this->Armada->CurrentValue;
			$this->Armada->ViewCustomAttributes = "";

			// Ejercito
			$this->Ejercito->EditAttrs["class"] = "form-control";
			$this->Ejercito->EditCustomAttributes = "";
			$this->Ejercito->EditValue = $this->Ejercito->CurrentValue;
			$this->Ejercito->ViewCustomAttributes = "";

			// Policia
			$this->Policia->EditAttrs["class"] = "form-control";
			$this->Policia->EditCustomAttributes = "";
			$this->Policia->EditValue = $this->Policia->CurrentValue;
			$this->Policia->ViewCustomAttributes = "";

			// NOM_UNIDAD
			$this->NOM_UNIDAD->EditAttrs["class"] = "form-control";
			$this->NOM_UNIDAD->EditCustomAttributes = "";
			$this->NOM_UNIDAD->EditValue = $this->NOM_UNIDAD->CurrentValue;
			$this->NOM_UNIDAD->ViewCustomAttributes = "";

			// NOM_COMAN
			$this->NOM_COMAN->EditAttrs["class"] = "form-control";
			$this->NOM_COMAN->EditCustomAttributes = "";
			$this->NOM_COMAN->EditValue = $this->NOM_COMAN->CurrentValue;
			$this->NOM_COMAN->ViewCustomAttributes = "";

			// CC_COMAN
			$this->CC_COMAN->EditAttrs["class"] = "form-control";
			$this->CC_COMAN->EditCustomAttributes = "";
			$this->CC_COMAN->EditValue = $this->CC_COMAN->CurrentValue;
			$this->CC_COMAN->ViewCustomAttributes = "";

			// TEL_COMAN
			$this->TEL_COMAN->EditAttrs["class"] = "form-control";
			$this->TEL_COMAN->EditCustomAttributes = "";
			$this->TEL_COMAN->EditValue = $this->TEL_COMAN->CurrentValue;
			$this->TEL_COMAN->ViewCustomAttributes = "";

			// RANGO_COMAN
			$this->RANGO_COMAN->EditAttrs["class"] = "form-control";
			$this->RANGO_COMAN->EditCustomAttributes = "";
			$this->RANGO_COMAN->EditValue = $this->RANGO_COMAN->CurrentValue;
			$this->RANGO_COMAN->ViewCustomAttributes = "";

			// Otro_rango
			$this->Otro_rango->EditAttrs["class"] = "form-control";
			$this->Otro_rango->EditCustomAttributes = "";
			$this->Otro_rango->EditValue = $this->Otro_rango->CurrentValue;
			$this->Otro_rango->ViewCustomAttributes = "";

			// NO_GDETECCION
			$this->NO_GDETECCION->EditAttrs["class"] = "form-control";
			$this->NO_GDETECCION->EditCustomAttributes = "";
			$this->NO_GDETECCION->EditValue = $this->NO_GDETECCION->CurrentValue;
			$this->NO_GDETECCION->ViewCustomAttributes = "";

			// NO_BINOMIO
			$this->NO_BINOMIO->EditAttrs["class"] = "form-control";
			$this->NO_BINOMIO->EditCustomAttributes = "";
			$this->NO_BINOMIO->EditValue = $this->NO_BINOMIO->CurrentValue;
			$this->NO_BINOMIO->ViewCustomAttributes = "";

			// FECHA_INTO_AV
			$this->FECHA_INTO_AV->EditAttrs["class"] = "form-control";
			$this->FECHA_INTO_AV->EditCustomAttributes = "";
			$this->FECHA_INTO_AV->EditValue = $this->FECHA_INTO_AV->CurrentValue;
			$this->FECHA_INTO_AV->ViewCustomAttributes = "";

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

			// LATITUD
			$this->LATITUD->EditAttrs["class"] = "form-control";
			$this->LATITUD->EditCustomAttributes = "";
			$this->LATITUD->EditValue = $this->LATITUD->CurrentValue;
			$this->LATITUD->ViewCustomAttributes = "";

			// GRA_LAT
			$this->GRA_LAT->EditAttrs["class"] = "form-control";
			$this->GRA_LAT->EditCustomAttributes = "";
			$this->GRA_LAT->EditValue = $this->GRA_LAT->CurrentValue;
			$this->GRA_LAT->ViewCustomAttributes = "";

			// MIN_LAT
			$this->MIN_LAT->EditAttrs["class"] = "form-control";
			$this->MIN_LAT->EditCustomAttributes = "";
			$this->MIN_LAT->EditValue = ew_HtmlEncode($this->MIN_LAT->CurrentValue);
			$this->MIN_LAT->PlaceHolder = ew_RemoveHtml($this->MIN_LAT->FldCaption());

			// SEG_LAT
			$this->SEG_LAT->EditAttrs["class"] = "form-control";
			$this->SEG_LAT->EditCustomAttributes = "";
			$this->SEG_LAT->EditValue = $this->SEG_LAT->CurrentValue;
			$this->SEG_LAT->ViewCustomAttributes = "";

			// GRA_LONG
			$this->GRA_LONG->EditAttrs["class"] = "form-control";
			$this->GRA_LONG->EditCustomAttributes = "";
			$this->GRA_LONG->EditValue = $this->GRA_LONG->CurrentValue;
			$this->GRA_LONG->ViewCustomAttributes = "";

			// MIN_LONG
			$this->MIN_LONG->EditAttrs["class"] = "form-control";
			$this->MIN_LONG->EditCustomAttributes = "";
			$this->MIN_LONG->EditValue = $this->MIN_LONG->CurrentValue;
			$this->MIN_LONG->ViewCustomAttributes = "";

			// SEG_LONG
			$this->SEG_LONG->EditAttrs["class"] = "form-control";
			$this->SEG_LONG->EditCustomAttributes = "";
			$this->SEG_LONG->EditValue = $this->SEG_LONG->CurrentValue;
			$this->SEG_LONG->ViewCustomAttributes = "";

			// OBSERVACION
			$this->OBSERVACION->EditAttrs["class"] = "form-control";
			$this->OBSERVACION->EditCustomAttributes = "";
			$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->CurrentValue);
			$this->OBSERVACION->PlaceHolder = ew_RemoveHtml($this->OBSERVACION->FldCaption());

			// AÑO
			$this->AD1O->EditAttrs["class"] = "form-control";
			$this->AD1O->EditCustomAttributes = "";
			$this->AD1O->EditValue = $this->AD1O->CurrentValue;
			$this->AD1O->ViewCustomAttributes = "";

			// FASE
			$this->FASE->EditAttrs["class"] = "form-control";
			$this->FASE->EditCustomAttributes = "";
			$this->FASE->EditValue = $this->FASE->CurrentValue;
			$this->FASE->ViewCustomAttributes = "";

			// Modificado
			$this->Modificado->EditAttrs["class"] = "form-control";
			$this->Modificado->EditCustomAttributes = "";
			$this->Modificado->EditValue = ew_HtmlEncode($this->Modificado->CurrentValue);
			$this->Modificado->PlaceHolder = ew_RemoveHtml($this->Modificado->FldCaption());

			// Edit refer script
			// llave

			$this->llave->HrefValue = "";

			// F_Sincron
			$this->F_Sincron->HrefValue = "";

			// USUARIO
			$this->USUARIO->HrefValue = "";

			// Cargo_gme
			$this->Cargo_gme->HrefValue = "";

			// Num_AV
			$this->Num_AV->HrefValue = "";

			// NOM_APOYO
			$this->NOM_APOYO->HrefValue = "";

			// Otro_Nom_Apoyo
			$this->Otro_Nom_Apoyo->HrefValue = "";

			// Otro_CC_Apoyo
			$this->Otro_CC_Apoyo->HrefValue = "";

			// NOM_PE
			$this->NOM_PE->HrefValue = "";

			// Otro_PE
			$this->Otro_PE->HrefValue = "";

			// Departamento
			$this->Departamento->HrefValue = "";

			// Muncipio
			$this->Muncipio->HrefValue = "";

			// NOM_VDA
			$this->NOM_VDA->HrefValue = "";

			// NO_E
			$this->NO_E->HrefValue = "";

			// NO_OF
			$this->NO_OF->HrefValue = "";

			// NO_SUBOF
			$this->NO_SUBOF->HrefValue = "";

			// NO_SOL
			$this->NO_SOL->HrefValue = "";

			// NO_PATRU
			$this->NO_PATRU->HrefValue = "";

			// Nom_enfer
			$this->Nom_enfer->HrefValue = "";

			// Otro_Nom_Enfer
			$this->Otro_Nom_Enfer->HrefValue = "";

			// Otro_CC_Enfer
			$this->Otro_CC_Enfer->HrefValue = "";

			// Armada
			$this->Armada->HrefValue = "";

			// Ejercito
			$this->Ejercito->HrefValue = "";

			// Policia
			$this->Policia->HrefValue = "";

			// NOM_UNIDAD
			$this->NOM_UNIDAD->HrefValue = "";

			// NOM_COMAN
			$this->NOM_COMAN->HrefValue = "";

			// CC_COMAN
			$this->CC_COMAN->HrefValue = "";

			// TEL_COMAN
			$this->TEL_COMAN->HrefValue = "";

			// RANGO_COMAN
			$this->RANGO_COMAN->HrefValue = "";

			// Otro_rango
			$this->Otro_rango->HrefValue = "";

			// NO_GDETECCION
			$this->NO_GDETECCION->HrefValue = "";

			// NO_BINOMIO
			$this->NO_BINOMIO->HrefValue = "";

			// FECHA_INTO_AV
			$this->FECHA_INTO_AV->HrefValue = "";

			// DIA
			$this->DIA->HrefValue = "";

			// MES
			$this->MES->HrefValue = "";

			// LATITUD
			$this->LATITUD->HrefValue = "";

			// GRA_LAT
			$this->GRA_LAT->HrefValue = "";

			// MIN_LAT
			$this->MIN_LAT->HrefValue = "";

			// SEG_LAT
			$this->SEG_LAT->HrefValue = "";

			// GRA_LONG
			$this->GRA_LONG->HrefValue = "";

			// MIN_LONG
			$this->MIN_LONG->HrefValue = "";

			// SEG_LONG
			$this->SEG_LONG->HrefValue = "";

			// OBSERVACION
			$this->OBSERVACION->HrefValue = "";

			// AÑO
			$this->AD1O->HrefValue = "";

			// FASE
			$this->FASE->HrefValue = "";

			// Modificado
			$this->Modificado->HrefValue = "";
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
		if (!ew_CheckInteger($this->MIN_LAT->FormValue)) {
			ew_AddMessage($gsFormError, $this->MIN_LAT->FldErrMsg());
		}
		if (!$this->Modificado->FldIsDetailKey && !is_null($this->Modificado->FormValue) && $this->Modificado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Modificado->FldCaption(), $this->Modificado->ReqErrMsg));
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

			// MIN_LAT
			$this->MIN_LAT->SetDbValueDef($rsnew, $this->MIN_LAT->CurrentValue, NULL, $this->MIN_LAT->ReadOnly);

			// OBSERVACION
			$this->OBSERVACION->SetDbValueDef($rsnew, $this->OBSERVACION->CurrentValue, NULL, $this->OBSERVACION->ReadOnly);

			// Modificado
			$this->Modificado->SetDbValueDef($rsnew, $this->Modificado->CurrentValue, "", $this->Modificado->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "view_cavlist.php", "", $this->TableVar, TRUE);
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
if (!isset($view_cav_edit)) $view_cav_edit = new cview_cav_edit();

// Page init
$view_cav_edit->Page_Init();

// Page main
$view_cav_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_cav_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_cav_edit = new ew_Page("view_cav_edit");
view_cav_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = view_cav_edit.PageID; // For backward compatibility

// Form object
var fview_cavedit = new ew_Form("fview_cavedit");

// Validate form
fview_cavedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_MIN_LAT");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_cav->MIN_LAT->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Modificado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_cav->Modificado->FldCaption(), $view_cav->Modificado->ReqErrMsg)) ?>");

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
fview_cavedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_cavedit.ValidateRequired = true;
<?php } else { ?>
fview_cavedit.ValidateRequired = false; 
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
<?php $view_cav_edit->ShowPageHeader(); ?>
<?php
$view_cav_edit->ShowMessage();
?>
<form name="fview_cavedit" id="fview_cavedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_cav_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_cav_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_cav">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($view_cav->llave->Visible) { // llave ?>
	<div id="r_llave" class="form-group">
		<label id="elh_view_cav_llave" for="x_llave" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->llave->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->llave->CellAttributes() ?>>
<span id="el_view_cav_llave">
<span<?php echo $view_cav->llave->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->llave->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_llave" name="x_llave" id="x_llave" value="<?php echo ew_HtmlEncode($view_cav->llave->CurrentValue) ?>">
<?php echo $view_cav->llave->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->F_Sincron->Visible) { // F_Sincron ?>
	<div id="r_F_Sincron" class="form-group">
		<label id="elh_view_cav_F_Sincron" for="x_F_Sincron" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->F_Sincron->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->F_Sincron->CellAttributes() ?>>
<span id="el_view_cav_F_Sincron">
<span<?php echo $view_cav->F_Sincron->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->F_Sincron->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" value="<?php echo ew_HtmlEncode($view_cav->F_Sincron->CurrentValue) ?>">
<?php echo $view_cav->F_Sincron->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->USUARIO->Visible) { // USUARIO ?>
	<div id="r_USUARIO" class="form-group">
		<label id="elh_view_cav_USUARIO" for="x_USUARIO" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->USUARIO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->USUARIO->CellAttributes() ?>>
<span id="el_view_cav_USUARIO">
<span<?php echo $view_cav->USUARIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->USUARIO->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" value="<?php echo ew_HtmlEncode($view_cav->USUARIO->CurrentValue) ?>">
<?php echo $view_cav->USUARIO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Cargo_gme->Visible) { // Cargo_gme ?>
	<div id="r_Cargo_gme" class="form-group">
		<label id="elh_view_cav_Cargo_gme" for="x_Cargo_gme" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Cargo_gme->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Cargo_gme->CellAttributes() ?>>
<span id="el_view_cav_Cargo_gme">
<span<?php echo $view_cav->Cargo_gme->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Cargo_gme->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" value="<?php echo ew_HtmlEncode($view_cav->Cargo_gme->CurrentValue) ?>">
<?php echo $view_cav->Cargo_gme->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Num_AV->Visible) { // Num_AV ?>
	<div id="r_Num_AV" class="form-group">
		<label id="elh_view_cav_Num_AV" for="x_Num_AV" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Num_AV->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Num_AV->CellAttributes() ?>>
<span id="el_view_cav_Num_AV">
<span<?php echo $view_cav->Num_AV->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Num_AV->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Num_AV" name="x_Num_AV" id="x_Num_AV" value="<?php echo ew_HtmlEncode($view_cav->Num_AV->CurrentValue) ?>">
<?php echo $view_cav->Num_AV->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NOM_APOYO->Visible) { // NOM_APOYO ?>
	<div id="r_NOM_APOYO" class="form-group">
		<label id="elh_view_cav_NOM_APOYO" for="x_NOM_APOYO" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NOM_APOYO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NOM_APOYO->CellAttributes() ?>>
<span id="el_view_cav_NOM_APOYO">
<span<?php echo $view_cav->NOM_APOYO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NOM_APOYO->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_APOYO" name="x_NOM_APOYO" id="x_NOM_APOYO" value="<?php echo ew_HtmlEncode($view_cav->NOM_APOYO->CurrentValue) ?>">
<?php echo $view_cav->NOM_APOYO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
	<div id="r_Otro_Nom_Apoyo" class="form-group">
		<label id="elh_view_cav_Otro_Nom_Apoyo" for="x_Otro_Nom_Apoyo" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Otro_Nom_Apoyo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Otro_Nom_Apoyo->CellAttributes() ?>>
<span id="el_view_cav_Otro_Nom_Apoyo">
<span<?php echo $view_cav->Otro_Nom_Apoyo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Otro_Nom_Apoyo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_Nom_Apoyo" name="x_Otro_Nom_Apoyo" id="x_Otro_Nom_Apoyo" value="<?php echo ew_HtmlEncode($view_cav->Otro_Nom_Apoyo->CurrentValue) ?>">
<?php echo $view_cav->Otro_Nom_Apoyo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
	<div id="r_Otro_CC_Apoyo" class="form-group">
		<label id="elh_view_cav_Otro_CC_Apoyo" for="x_Otro_CC_Apoyo" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Otro_CC_Apoyo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Otro_CC_Apoyo->CellAttributes() ?>>
<span id="el_view_cav_Otro_CC_Apoyo">
<span<?php echo $view_cav->Otro_CC_Apoyo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Otro_CC_Apoyo->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_CC_Apoyo" name="x_Otro_CC_Apoyo" id="x_Otro_CC_Apoyo" value="<?php echo ew_HtmlEncode($view_cav->Otro_CC_Apoyo->CurrentValue) ?>">
<?php echo $view_cav->Otro_CC_Apoyo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NOM_PE->Visible) { // NOM_PE ?>
	<div id="r_NOM_PE" class="form-group">
		<label id="elh_view_cav_NOM_PE" for="x_NOM_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NOM_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NOM_PE->CellAttributes() ?>>
<span id="el_view_cav_NOM_PE">
<span<?php echo $view_cav->NOM_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NOM_PE->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_PE" name="x_NOM_PE" id="x_NOM_PE" value="<?php echo ew_HtmlEncode($view_cav->NOM_PE->CurrentValue) ?>">
<?php echo $view_cav->NOM_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Otro_PE->Visible) { // Otro_PE ?>
	<div id="r_Otro_PE" class="form-group">
		<label id="elh_view_cav_Otro_PE" for="x_Otro_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Otro_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Otro_PE->CellAttributes() ?>>
<span id="el_view_cav_Otro_PE">
<span<?php echo $view_cav->Otro_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Otro_PE->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_PE" name="x_Otro_PE" id="x_Otro_PE" value="<?php echo ew_HtmlEncode($view_cav->Otro_PE->CurrentValue) ?>">
<?php echo $view_cav->Otro_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Departamento->Visible) { // Departamento ?>
	<div id="r_Departamento" class="form-group">
		<label id="elh_view_cav_Departamento" for="x_Departamento" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Departamento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Departamento->CellAttributes() ?>>
<span id="el_view_cav_Departamento">
<span<?php echo $view_cav->Departamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Departamento->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Departamento" name="x_Departamento" id="x_Departamento" value="<?php echo ew_HtmlEncode($view_cav->Departamento->CurrentValue) ?>">
<?php echo $view_cav->Departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Muncipio->Visible) { // Muncipio ?>
	<div id="r_Muncipio" class="form-group">
		<label id="elh_view_cav_Muncipio" for="x_Muncipio" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Muncipio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Muncipio->CellAttributes() ?>>
<span id="el_view_cav_Muncipio">
<span<?php echo $view_cav->Muncipio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Muncipio->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Muncipio" name="x_Muncipio" id="x_Muncipio" value="<?php echo ew_HtmlEncode($view_cav->Muncipio->CurrentValue) ?>">
<?php echo $view_cav->Muncipio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NOM_VDA->Visible) { // NOM_VDA ?>
	<div id="r_NOM_VDA" class="form-group">
		<label id="elh_view_cav_NOM_VDA" for="x_NOM_VDA" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NOM_VDA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NOM_VDA->CellAttributes() ?>>
<span id="el_view_cav_NOM_VDA">
<span<?php echo $view_cav->NOM_VDA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NOM_VDA->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_VDA" name="x_NOM_VDA" id="x_NOM_VDA" value="<?php echo ew_HtmlEncode($view_cav->NOM_VDA->CurrentValue) ?>">
<?php echo $view_cav->NOM_VDA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NO_E->Visible) { // NO_E ?>
	<div id="r_NO_E" class="form-group">
		<label id="elh_view_cav_NO_E" for="x_NO_E" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NO_E->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NO_E->CellAttributes() ?>>
<span id="el_view_cav_NO_E">
<span<?php echo $view_cav->NO_E->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NO_E->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NO_E" name="x_NO_E" id="x_NO_E" value="<?php echo ew_HtmlEncode($view_cav->NO_E->CurrentValue) ?>">
<?php echo $view_cav->NO_E->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NO_OF->Visible) { // NO_OF ?>
	<div id="r_NO_OF" class="form-group">
		<label id="elh_view_cav_NO_OF" for="x_NO_OF" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NO_OF->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NO_OF->CellAttributes() ?>>
<span id="el_view_cav_NO_OF">
<span<?php echo $view_cav->NO_OF->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NO_OF->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NO_OF" name="x_NO_OF" id="x_NO_OF" value="<?php echo ew_HtmlEncode($view_cav->NO_OF->CurrentValue) ?>">
<?php echo $view_cav->NO_OF->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NO_SUBOF->Visible) { // NO_SUBOF ?>
	<div id="r_NO_SUBOF" class="form-group">
		<label id="elh_view_cav_NO_SUBOF" for="x_NO_SUBOF" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NO_SUBOF->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NO_SUBOF->CellAttributes() ?>>
<span id="el_view_cav_NO_SUBOF">
<span<?php echo $view_cav->NO_SUBOF->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NO_SUBOF->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NO_SUBOF" name="x_NO_SUBOF" id="x_NO_SUBOF" value="<?php echo ew_HtmlEncode($view_cav->NO_SUBOF->CurrentValue) ?>">
<?php echo $view_cav->NO_SUBOF->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NO_SOL->Visible) { // NO_SOL ?>
	<div id="r_NO_SOL" class="form-group">
		<label id="elh_view_cav_NO_SOL" for="x_NO_SOL" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NO_SOL->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NO_SOL->CellAttributes() ?>>
<span id="el_view_cav_NO_SOL">
<span<?php echo $view_cav->NO_SOL->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NO_SOL->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NO_SOL" name="x_NO_SOL" id="x_NO_SOL" value="<?php echo ew_HtmlEncode($view_cav->NO_SOL->CurrentValue) ?>">
<?php echo $view_cav->NO_SOL->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NO_PATRU->Visible) { // NO_PATRU ?>
	<div id="r_NO_PATRU" class="form-group">
		<label id="elh_view_cav_NO_PATRU" for="x_NO_PATRU" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NO_PATRU->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NO_PATRU->CellAttributes() ?>>
<span id="el_view_cav_NO_PATRU">
<span<?php echo $view_cav->NO_PATRU->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NO_PATRU->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NO_PATRU" name="x_NO_PATRU" id="x_NO_PATRU" value="<?php echo ew_HtmlEncode($view_cav->NO_PATRU->CurrentValue) ?>">
<?php echo $view_cav->NO_PATRU->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Nom_enfer->Visible) { // Nom_enfer ?>
	<div id="r_Nom_enfer" class="form-group">
		<label id="elh_view_cav_Nom_enfer" for="x_Nom_enfer" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Nom_enfer->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Nom_enfer->CellAttributes() ?>>
<span id="el_view_cav_Nom_enfer">
<span<?php echo $view_cav->Nom_enfer->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Nom_enfer->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Nom_enfer" name="x_Nom_enfer" id="x_Nom_enfer" value="<?php echo ew_HtmlEncode($view_cav->Nom_enfer->CurrentValue) ?>">
<?php echo $view_cav->Nom_enfer->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Otro_Nom_Enfer->Visible) { // Otro_Nom_Enfer ?>
	<div id="r_Otro_Nom_Enfer" class="form-group">
		<label id="elh_view_cav_Otro_Nom_Enfer" for="x_Otro_Nom_Enfer" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Otro_Nom_Enfer->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Otro_Nom_Enfer->CellAttributes() ?>>
<span id="el_view_cav_Otro_Nom_Enfer">
<span<?php echo $view_cav->Otro_Nom_Enfer->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Otro_Nom_Enfer->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_Nom_Enfer" name="x_Otro_Nom_Enfer" id="x_Otro_Nom_Enfer" value="<?php echo ew_HtmlEncode($view_cav->Otro_Nom_Enfer->CurrentValue) ?>">
<?php echo $view_cav->Otro_Nom_Enfer->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Otro_CC_Enfer->Visible) { // Otro_CC_Enfer ?>
	<div id="r_Otro_CC_Enfer" class="form-group">
		<label id="elh_view_cav_Otro_CC_Enfer" for="x_Otro_CC_Enfer" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Otro_CC_Enfer->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Otro_CC_Enfer->CellAttributes() ?>>
<span id="el_view_cav_Otro_CC_Enfer">
<span<?php echo $view_cav->Otro_CC_Enfer->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Otro_CC_Enfer->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_CC_Enfer" name="x_Otro_CC_Enfer" id="x_Otro_CC_Enfer" value="<?php echo ew_HtmlEncode($view_cav->Otro_CC_Enfer->CurrentValue) ?>">
<?php echo $view_cav->Otro_CC_Enfer->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Armada->Visible) { // Armada ?>
	<div id="r_Armada" class="form-group">
		<label id="elh_view_cav_Armada" for="x_Armada" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Armada->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Armada->CellAttributes() ?>>
<span id="el_view_cav_Armada">
<span<?php echo $view_cav->Armada->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Armada->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Armada" name="x_Armada" id="x_Armada" value="<?php echo ew_HtmlEncode($view_cav->Armada->CurrentValue) ?>">
<?php echo $view_cav->Armada->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Ejercito->Visible) { // Ejercito ?>
	<div id="r_Ejercito" class="form-group">
		<label id="elh_view_cav_Ejercito" for="x_Ejercito" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Ejercito->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Ejercito->CellAttributes() ?>>
<span id="el_view_cav_Ejercito">
<span<?php echo $view_cav->Ejercito->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Ejercito->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Ejercito" name="x_Ejercito" id="x_Ejercito" value="<?php echo ew_HtmlEncode($view_cav->Ejercito->CurrentValue) ?>">
<?php echo $view_cav->Ejercito->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Policia->Visible) { // Policia ?>
	<div id="r_Policia" class="form-group">
		<label id="elh_view_cav_Policia" for="x_Policia" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Policia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Policia->CellAttributes() ?>>
<span id="el_view_cav_Policia">
<span<?php echo $view_cav->Policia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Policia->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Policia" name="x_Policia" id="x_Policia" value="<?php echo ew_HtmlEncode($view_cav->Policia->CurrentValue) ?>">
<?php echo $view_cav->Policia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NOM_UNIDAD->Visible) { // NOM_UNIDAD ?>
	<div id="r_NOM_UNIDAD" class="form-group">
		<label id="elh_view_cav_NOM_UNIDAD" for="x_NOM_UNIDAD" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NOM_UNIDAD->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NOM_UNIDAD->CellAttributes() ?>>
<span id="el_view_cav_NOM_UNIDAD">
<span<?php echo $view_cav->NOM_UNIDAD->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NOM_UNIDAD->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_UNIDAD" name="x_NOM_UNIDAD" id="x_NOM_UNIDAD" value="<?php echo ew_HtmlEncode($view_cav->NOM_UNIDAD->CurrentValue) ?>">
<?php echo $view_cav->NOM_UNIDAD->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NOM_COMAN->Visible) { // NOM_COMAN ?>
	<div id="r_NOM_COMAN" class="form-group">
		<label id="elh_view_cav_NOM_COMAN" for="x_NOM_COMAN" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NOM_COMAN->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NOM_COMAN->CellAttributes() ?>>
<span id="el_view_cav_NOM_COMAN">
<span<?php echo $view_cav->NOM_COMAN->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NOM_COMAN->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_COMAN" name="x_NOM_COMAN" id="x_NOM_COMAN" value="<?php echo ew_HtmlEncode($view_cav->NOM_COMAN->CurrentValue) ?>">
<?php echo $view_cav->NOM_COMAN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->CC_COMAN->Visible) { // CC_COMAN ?>
	<div id="r_CC_COMAN" class="form-group">
		<label id="elh_view_cav_CC_COMAN" for="x_CC_COMAN" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->CC_COMAN->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->CC_COMAN->CellAttributes() ?>>
<span id="el_view_cav_CC_COMAN">
<span<?php echo $view_cav->CC_COMAN->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->CC_COMAN->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_CC_COMAN" name="x_CC_COMAN" id="x_CC_COMAN" value="<?php echo ew_HtmlEncode($view_cav->CC_COMAN->CurrentValue) ?>">
<?php echo $view_cav->CC_COMAN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->TEL_COMAN->Visible) { // TEL_COMAN ?>
	<div id="r_TEL_COMAN" class="form-group">
		<label id="elh_view_cav_TEL_COMAN" for="x_TEL_COMAN" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->TEL_COMAN->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->TEL_COMAN->CellAttributes() ?>>
<span id="el_view_cav_TEL_COMAN">
<span<?php echo $view_cav->TEL_COMAN->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->TEL_COMAN->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_TEL_COMAN" name="x_TEL_COMAN" id="x_TEL_COMAN" value="<?php echo ew_HtmlEncode($view_cav->TEL_COMAN->CurrentValue) ?>">
<?php echo $view_cav->TEL_COMAN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->RANGO_COMAN->Visible) { // RANGO_COMAN ?>
	<div id="r_RANGO_COMAN" class="form-group">
		<label id="elh_view_cav_RANGO_COMAN" for="x_RANGO_COMAN" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->RANGO_COMAN->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->RANGO_COMAN->CellAttributes() ?>>
<span id="el_view_cav_RANGO_COMAN">
<span<?php echo $view_cav->RANGO_COMAN->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->RANGO_COMAN->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_RANGO_COMAN" name="x_RANGO_COMAN" id="x_RANGO_COMAN" value="<?php echo ew_HtmlEncode($view_cav->RANGO_COMAN->CurrentValue) ?>">
<?php echo $view_cav->RANGO_COMAN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Otro_rango->Visible) { // Otro_rango ?>
	<div id="r_Otro_rango" class="form-group">
		<label id="elh_view_cav_Otro_rango" for="x_Otro_rango" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Otro_rango->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Otro_rango->CellAttributes() ?>>
<span id="el_view_cav_Otro_rango">
<span<?php echo $view_cav->Otro_rango->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->Otro_rango->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_rango" name="x_Otro_rango" id="x_Otro_rango" value="<?php echo ew_HtmlEncode($view_cav->Otro_rango->CurrentValue) ?>">
<?php echo $view_cav->Otro_rango->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NO_GDETECCION->Visible) { // NO_GDETECCION ?>
	<div id="r_NO_GDETECCION" class="form-group">
		<label id="elh_view_cav_NO_GDETECCION" for="x_NO_GDETECCION" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NO_GDETECCION->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NO_GDETECCION->CellAttributes() ?>>
<span id="el_view_cav_NO_GDETECCION">
<span<?php echo $view_cav->NO_GDETECCION->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NO_GDETECCION->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NO_GDETECCION" name="x_NO_GDETECCION" id="x_NO_GDETECCION" value="<?php echo ew_HtmlEncode($view_cav->NO_GDETECCION->CurrentValue) ?>">
<?php echo $view_cav->NO_GDETECCION->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->NO_BINOMIO->Visible) { // NO_BINOMIO ?>
	<div id="r_NO_BINOMIO" class="form-group">
		<label id="elh_view_cav_NO_BINOMIO" for="x_NO_BINOMIO" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->NO_BINOMIO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->NO_BINOMIO->CellAttributes() ?>>
<span id="el_view_cav_NO_BINOMIO">
<span<?php echo $view_cav->NO_BINOMIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->NO_BINOMIO->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_NO_BINOMIO" name="x_NO_BINOMIO" id="x_NO_BINOMIO" value="<?php echo ew_HtmlEncode($view_cav->NO_BINOMIO->CurrentValue) ?>">
<?php echo $view_cav->NO_BINOMIO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->FECHA_INTO_AV->Visible) { // FECHA_INTO_AV ?>
	<div id="r_FECHA_INTO_AV" class="form-group">
		<label id="elh_view_cav_FECHA_INTO_AV" for="x_FECHA_INTO_AV" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->FECHA_INTO_AV->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->FECHA_INTO_AV->CellAttributes() ?>>
<span id="el_view_cav_FECHA_INTO_AV">
<span<?php echo $view_cav->FECHA_INTO_AV->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->FECHA_INTO_AV->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_FECHA_INTO_AV" name="x_FECHA_INTO_AV" id="x_FECHA_INTO_AV" value="<?php echo ew_HtmlEncode($view_cav->FECHA_INTO_AV->CurrentValue) ?>">
<?php echo $view_cav->FECHA_INTO_AV->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->DIA->Visible) { // DIA ?>
	<div id="r_DIA" class="form-group">
		<label id="elh_view_cav_DIA" for="x_DIA" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->DIA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->DIA->CellAttributes() ?>>
<span id="el_view_cav_DIA">
<span<?php echo $view_cav->DIA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->DIA->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_DIA" name="x_DIA" id="x_DIA" value="<?php echo ew_HtmlEncode($view_cav->DIA->CurrentValue) ?>">
<?php echo $view_cav->DIA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->MES->Visible) { // MES ?>
	<div id="r_MES" class="form-group">
		<label id="elh_view_cav_MES" for="x_MES" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->MES->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->MES->CellAttributes() ?>>
<span id="el_view_cav_MES">
<span<?php echo $view_cav->MES->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->MES->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_MES" name="x_MES" id="x_MES" value="<?php echo ew_HtmlEncode($view_cav->MES->CurrentValue) ?>">
<?php echo $view_cav->MES->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->LATITUD->Visible) { // LATITUD ?>
	<div id="r_LATITUD" class="form-group">
		<label id="elh_view_cav_LATITUD" for="x_LATITUD" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->LATITUD->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->LATITUD->CellAttributes() ?>>
<span id="el_view_cav_LATITUD">
<span<?php echo $view_cav->LATITUD->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->LATITUD->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_LATITUD" name="x_LATITUD" id="x_LATITUD" value="<?php echo ew_HtmlEncode($view_cav->LATITUD->CurrentValue) ?>">
<?php echo $view_cav->LATITUD->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->GRA_LAT->Visible) { // GRA_LAT ?>
	<div id="r_GRA_LAT" class="form-group">
		<label id="elh_view_cav_GRA_LAT" for="x_GRA_LAT" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->GRA_LAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->GRA_LAT->CellAttributes() ?>>
<span id="el_view_cav_GRA_LAT">
<span<?php echo $view_cav->GRA_LAT->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->GRA_LAT->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_GRA_LAT" name="x_GRA_LAT" id="x_GRA_LAT" value="<?php echo ew_HtmlEncode($view_cav->GRA_LAT->CurrentValue) ?>">
<?php echo $view_cav->GRA_LAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->MIN_LAT->Visible) { // MIN_LAT ?>
	<div id="r_MIN_LAT" class="form-group">
		<label id="elh_view_cav_MIN_LAT" for="x_MIN_LAT" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->MIN_LAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->MIN_LAT->CellAttributes() ?>>
<span id="el_view_cav_MIN_LAT">
<input type="text" data-field="x_MIN_LAT" name="x_MIN_LAT" id="x_MIN_LAT" size="30" placeholder="<?php echo ew_HtmlEncode($view_cav->MIN_LAT->PlaceHolder) ?>" value="<?php echo $view_cav->MIN_LAT->EditValue ?>"<?php echo $view_cav->MIN_LAT->EditAttributes() ?>>
</span>
<?php echo $view_cav->MIN_LAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->SEG_LAT->Visible) { // SEG_LAT ?>
	<div id="r_SEG_LAT" class="form-group">
		<label id="elh_view_cav_SEG_LAT" for="x_SEG_LAT" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->SEG_LAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->SEG_LAT->CellAttributes() ?>>
<span id="el_view_cav_SEG_LAT">
<span<?php echo $view_cav->SEG_LAT->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->SEG_LAT->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_SEG_LAT" name="x_SEG_LAT" id="x_SEG_LAT" value="<?php echo ew_HtmlEncode($view_cav->SEG_LAT->CurrentValue) ?>">
<?php echo $view_cav->SEG_LAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->GRA_LONG->Visible) { // GRA_LONG ?>
	<div id="r_GRA_LONG" class="form-group">
		<label id="elh_view_cav_GRA_LONG" for="x_GRA_LONG" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->GRA_LONG->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->GRA_LONG->CellAttributes() ?>>
<span id="el_view_cav_GRA_LONG">
<span<?php echo $view_cav->GRA_LONG->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->GRA_LONG->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_GRA_LONG" name="x_GRA_LONG" id="x_GRA_LONG" value="<?php echo ew_HtmlEncode($view_cav->GRA_LONG->CurrentValue) ?>">
<?php echo $view_cav->GRA_LONG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->MIN_LONG->Visible) { // MIN_LONG ?>
	<div id="r_MIN_LONG" class="form-group">
		<label id="elh_view_cav_MIN_LONG" for="x_MIN_LONG" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->MIN_LONG->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->MIN_LONG->CellAttributes() ?>>
<span id="el_view_cav_MIN_LONG">
<span<?php echo $view_cav->MIN_LONG->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->MIN_LONG->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_MIN_LONG" name="x_MIN_LONG" id="x_MIN_LONG" value="<?php echo ew_HtmlEncode($view_cav->MIN_LONG->CurrentValue) ?>">
<?php echo $view_cav->MIN_LONG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->SEG_LONG->Visible) { // SEG_LONG ?>
	<div id="r_SEG_LONG" class="form-group">
		<label id="elh_view_cav_SEG_LONG" for="x_SEG_LONG" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->SEG_LONG->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->SEG_LONG->CellAttributes() ?>>
<span id="el_view_cav_SEG_LONG">
<span<?php echo $view_cav->SEG_LONG->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->SEG_LONG->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_SEG_LONG" name="x_SEG_LONG" id="x_SEG_LONG" value="<?php echo ew_HtmlEncode($view_cav->SEG_LONG->CurrentValue) ?>">
<?php echo $view_cav->SEG_LONG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->OBSERVACION->Visible) { // OBSERVACION ?>
	<div id="r_OBSERVACION" class="form-group">
		<label id="elh_view_cav_OBSERVACION" for="x_OBSERVACION" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->OBSERVACION->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->OBSERVACION->CellAttributes() ?>>
<span id="el_view_cav_OBSERVACION">
<textarea data-field="x_OBSERVACION" name="x_OBSERVACION" id="x_OBSERVACION" cols="120" rows="4" placeholder="<?php echo ew_HtmlEncode($view_cav->OBSERVACION->PlaceHolder) ?>"<?php echo $view_cav->OBSERVACION->EditAttributes() ?>><?php echo $view_cav->OBSERVACION->EditValue ?></textarea>
</span>
<?php echo $view_cav->OBSERVACION->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->AD1O->Visible) { // AÑO ?>
	<div id="r_AD1O" class="form-group">
		<label id="elh_view_cav_AD1O" for="x_AD1O" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->AD1O->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->AD1O->CellAttributes() ?>>
<span id="el_view_cav_AD1O">
<span<?php echo $view_cav->AD1O->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->AD1O->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_AD1O" name="x_AD1O" id="x_AD1O" value="<?php echo ew_HtmlEncode($view_cav->AD1O->CurrentValue) ?>">
<?php echo $view_cav->AD1O->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->FASE->Visible) { // FASE ?>
	<div id="r_FASE" class="form-group">
		<label id="elh_view_cav_FASE" for="x_FASE" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->FASE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->FASE->CellAttributes() ?>>
<span id="el_view_cav_FASE">
<span<?php echo $view_cav->FASE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_cav->FASE->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_FASE" name="x_FASE" id="x_FASE" value="<?php echo ew_HtmlEncode($view_cav->FASE->CurrentValue) ?>">
<?php echo $view_cav->FASE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_cav->Modificado->Visible) { // Modificado ?>
	<div id="r_Modificado" class="form-group">
		<label id="elh_view_cav_Modificado" for="x_Modificado" class="col-sm-2 control-label ewLabel"><?php echo $view_cav->Modificado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $view_cav->Modificado->CellAttributes() ?>>
<span id="el_view_cav_Modificado">
<input type="text" data-field="x_Modificado" name="x_Modificado" id="x_Modificado" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_cav->Modificado->PlaceHolder) ?>" value="<?php echo $view_cav->Modificado->EditValue ?>"<?php echo $view_cav->Modificado->EditAttributes() ?>>
</span>
<?php echo $view_cav->Modificado->CustomMsg ?></div></div>
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
fview_cavedit.Init();
</script>
<?php
$view_cav_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_cav_edit->Page_Terminate();
?>
