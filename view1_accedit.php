<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view1_accinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view1_acc_edit = NULL; // Initialize page object first

class cview1_acc_edit extends cview1_acc {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view1_acc';

	// Page object name
	var $PageObjName = 'view1_acc_edit';

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

		// Table object (view1_acc)
		if (!isset($GLOBALS["view1_acc"]) || get_class($GLOBALS["view1_acc"]) == "cview1_acc") {
			$GLOBALS["view1_acc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1_acc"];
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
			define("EW_TABLE_NAME", 'view1_acc', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view1_acclist.php"));
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
		global $EW_EXPORT, $view1_acc;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view1_acc);
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
		if (@$_GET["llave_2"] <> "") {
			$this->llave_2->setQueryStringValue($_GET["llave_2"]);
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
		if ($this->llave_2->CurrentValue == "")
			$this->Page_Terminate("view1_acclist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("view1_acclist.php"); // No matching record, return to list
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
		if (!$this->NOM_PE->FldIsDetailKey) {
			$this->NOM_PE->setFormValue($objForm->GetValue("x_NOM_PE"));
		}
		if (!$this->Otro_PE->FldIsDetailKey) {
			$this->Otro_PE->setFormValue($objForm->GetValue("x_Otro_PE"));
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
		if (!$this->NOM_ENLACE->FldIsDetailKey) {
			$this->NOM_ENLACE->setFormValue($objForm->GetValue("x_NOM_ENLACE"));
		}
		if (!$this->Otro_Nom_Enlace->FldIsDetailKey) {
			$this->Otro_Nom_Enlace->setFormValue($objForm->GetValue("x_Otro_Nom_Enlace"));
		}
		if (!$this->Otro_CC_Enlace->FldIsDetailKey) {
			$this->Otro_CC_Enlace->setFormValue($objForm->GetValue("x_Otro_CC_Enlace"));
		}
		if (!$this->NOM_PGE->FldIsDetailKey) {
			$this->NOM_PGE->setFormValue($objForm->GetValue("x_NOM_PGE"));
		}
		if (!$this->Otro_Nom_PGE->FldIsDetailKey) {
			$this->Otro_Nom_PGE->setFormValue($objForm->GetValue("x_Otro_Nom_PGE"));
		}
		if (!$this->Otro_CC_PGE->FldIsDetailKey) {
			$this->Otro_CC_PGE->setFormValue($objForm->GetValue("x_Otro_CC_PGE"));
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
		if (!$this->FECHA_ACC->FldIsDetailKey) {
			$this->FECHA_ACC->setFormValue($objForm->GetValue("x_FECHA_ACC"));
		}
		if (!$this->HORA_ACC->FldIsDetailKey) {
			$this->HORA_ACC->setFormValue($objForm->GetValue("x_HORA_ACC"));
		}
		if (!$this->Hora_ingreso->FldIsDetailKey) {
			$this->Hora_ingreso->setFormValue($objForm->GetValue("x_Hora_ingreso"));
		}
		if (!$this->FP_Armada->FldIsDetailKey) {
			$this->FP_Armada->setFormValue($objForm->GetValue("x_FP_Armada"));
		}
		if (!$this->FP_Ejercito->FldIsDetailKey) {
			$this->FP_Ejercito->setFormValue($objForm->GetValue("x_FP_Ejercito"));
		}
		if (!$this->FP_Policia->FldIsDetailKey) {
			$this->FP_Policia->setFormValue($objForm->GetValue("x_FP_Policia"));
		}
		if (!$this->NOM_COMANDANTE->FldIsDetailKey) {
			$this->NOM_COMANDANTE->setFormValue($objForm->GetValue("x_NOM_COMANDANTE"));
		}
		if (!$this->TESTI1->FldIsDetailKey) {
			$this->TESTI1->setFormValue($objForm->GetValue("x_TESTI1"));
		}
		if (!$this->CC_TESTI1->FldIsDetailKey) {
			$this->CC_TESTI1->setFormValue($objForm->GetValue("x_CC_TESTI1"));
		}
		if (!$this->CARGO_TESTI1->FldIsDetailKey) {
			$this->CARGO_TESTI1->setFormValue($objForm->GetValue("x_CARGO_TESTI1"));
		}
		if (!$this->TESTI2->FldIsDetailKey) {
			$this->TESTI2->setFormValue($objForm->GetValue("x_TESTI2"));
		}
		if (!$this->CC_TESTI2->FldIsDetailKey) {
			$this->CC_TESTI2->setFormValue($objForm->GetValue("x_CC_TESTI2"));
		}
		if (!$this->CARGO_TESTI2->FldIsDetailKey) {
			$this->CARGO_TESTI2->setFormValue($objForm->GetValue("x_CARGO_TESTI2"));
		}
		if (!$this->Afectados->FldIsDetailKey) {
			$this->Afectados->setFormValue($objForm->GetValue("x_Afectados"));
		}
		if (!$this->NUM_Afectado->FldIsDetailKey) {
			$this->NUM_Afectado->setFormValue($objForm->GetValue("x_NUM_Afectado"));
		}
		if (!$this->Nom_Afectado->FldIsDetailKey) {
			$this->Nom_Afectado->setFormValue($objForm->GetValue("x_Nom_Afectado"));
		}
		if (!$this->CC_Afectado->FldIsDetailKey) {
			$this->CC_Afectado->setFormValue($objForm->GetValue("x_CC_Afectado"));
		}
		if (!$this->Cargo_Afectado->FldIsDetailKey) {
			$this->Cargo_Afectado->setFormValue($objForm->GetValue("x_Cargo_Afectado"));
		}
		if (!$this->Tipo_incidente->FldIsDetailKey) {
			$this->Tipo_incidente->setFormValue($objForm->GetValue("x_Tipo_incidente"));
		}
		if (!$this->Parte_Cuerpo->FldIsDetailKey) {
			$this->Parte_Cuerpo->setFormValue($objForm->GetValue("x_Parte_Cuerpo"));
		}
		if (!$this->ESTADO_AFEC->FldIsDetailKey) {
			$this->ESTADO_AFEC->setFormValue($objForm->GetValue("x_ESTADO_AFEC"));
		}
		if (!$this->EVACUADO->FldIsDetailKey) {
			$this->EVACUADO->setFormValue($objForm->GetValue("x_EVACUADO"));
		}
		if (!$this->DESC_ACC->FldIsDetailKey) {
			$this->DESC_ACC->setFormValue($objForm->GetValue("x_DESC_ACC"));
		}
		if (!$this->Modificado->FldIsDetailKey) {
			$this->Modificado->setFormValue($objForm->GetValue("x_Modificado"));
		}
		if (!$this->llave_2->FldIsDetailKey)
			$this->llave_2->setFormValue($objForm->GetValue("x_llave_2"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->llave_2->CurrentValue = $this->llave_2->FormValue;
		$this->llave->CurrentValue = $this->llave->FormValue;
		$this->F_Sincron->CurrentValue = $this->F_Sincron->FormValue;
		$this->F_Sincron->CurrentValue = ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 7);
		$this->USUARIO->CurrentValue = $this->USUARIO->FormValue;
		$this->Cargo_gme->CurrentValue = $this->Cargo_gme->FormValue;
		$this->NOM_PE->CurrentValue = $this->NOM_PE->FormValue;
		$this->Otro_PE->CurrentValue = $this->Otro_PE->FormValue;
		$this->NOM_APOYO->CurrentValue = $this->NOM_APOYO->FormValue;
		$this->Otro_Nom_Apoyo->CurrentValue = $this->Otro_Nom_Apoyo->FormValue;
		$this->Otro_CC_Apoyo->CurrentValue = $this->Otro_CC_Apoyo->FormValue;
		$this->NOM_ENLACE->CurrentValue = $this->NOM_ENLACE->FormValue;
		$this->Otro_Nom_Enlace->CurrentValue = $this->Otro_Nom_Enlace->FormValue;
		$this->Otro_CC_Enlace->CurrentValue = $this->Otro_CC_Enlace->FormValue;
		$this->NOM_PGE->CurrentValue = $this->NOM_PGE->FormValue;
		$this->Otro_Nom_PGE->CurrentValue = $this->Otro_Nom_PGE->FormValue;
		$this->Otro_CC_PGE->CurrentValue = $this->Otro_CC_PGE->FormValue;
		$this->Departamento->CurrentValue = $this->Departamento->FormValue;
		$this->Muncipio->CurrentValue = $this->Muncipio->FormValue;
		$this->NOM_VDA->CurrentValue = $this->NOM_VDA->FormValue;
		$this->LATITUD->CurrentValue = $this->LATITUD->FormValue;
		$this->GRA_LAT->CurrentValue = $this->GRA_LAT->FormValue;
		$this->MIN_LAT->CurrentValue = $this->MIN_LAT->FormValue;
		$this->SEG_LAT->CurrentValue = $this->SEG_LAT->FormValue;
		$this->GRA_LONG->CurrentValue = $this->GRA_LONG->FormValue;
		$this->MIN_LONG->CurrentValue = $this->MIN_LONG->FormValue;
		$this->SEG_LONG->CurrentValue = $this->SEG_LONG->FormValue;
		$this->FECHA_ACC->CurrentValue = $this->FECHA_ACC->FormValue;
		$this->HORA_ACC->CurrentValue = $this->HORA_ACC->FormValue;
		$this->Hora_ingreso->CurrentValue = $this->Hora_ingreso->FormValue;
		$this->FP_Armada->CurrentValue = $this->FP_Armada->FormValue;
		$this->FP_Ejercito->CurrentValue = $this->FP_Ejercito->FormValue;
		$this->FP_Policia->CurrentValue = $this->FP_Policia->FormValue;
		$this->NOM_COMANDANTE->CurrentValue = $this->NOM_COMANDANTE->FormValue;
		$this->TESTI1->CurrentValue = $this->TESTI1->FormValue;
		$this->CC_TESTI1->CurrentValue = $this->CC_TESTI1->FormValue;
		$this->CARGO_TESTI1->CurrentValue = $this->CARGO_TESTI1->FormValue;
		$this->TESTI2->CurrentValue = $this->TESTI2->FormValue;
		$this->CC_TESTI2->CurrentValue = $this->CC_TESTI2->FormValue;
		$this->CARGO_TESTI2->CurrentValue = $this->CARGO_TESTI2->FormValue;
		$this->Afectados->CurrentValue = $this->Afectados->FormValue;
		$this->NUM_Afectado->CurrentValue = $this->NUM_Afectado->FormValue;
		$this->Nom_Afectado->CurrentValue = $this->Nom_Afectado->FormValue;
		$this->CC_Afectado->CurrentValue = $this->CC_Afectado->FormValue;
		$this->Cargo_Afectado->CurrentValue = $this->Cargo_Afectado->FormValue;
		$this->Tipo_incidente->CurrentValue = $this->Tipo_incidente->FormValue;
		$this->Parte_Cuerpo->CurrentValue = $this->Parte_Cuerpo->FormValue;
		$this->ESTADO_AFEC->CurrentValue = $this->ESTADO_AFEC->FormValue;
		$this->EVACUADO->CurrentValue = $this->EVACUADO->FormValue;
		$this->DESC_ACC->CurrentValue = $this->DESC_ACC->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->llave->DbValue = $row['llave'];
		$this->F_Sincron->DbValue = $row['F_Sincron'];
		$this->USUARIO->DbValue = $row['USUARIO'];
		$this->Cargo_gme->DbValue = $row['Cargo_gme'];
		$this->NOM_PE->DbValue = $row['NOM_PE'];
		$this->Otro_PE->DbValue = $row['Otro_PE'];
		$this->NOM_APOYO->DbValue = $row['NOM_APOYO'];
		$this->Otro_Nom_Apoyo->DbValue = $row['Otro_Nom_Apoyo'];
		$this->Otro_CC_Apoyo->DbValue = $row['Otro_CC_Apoyo'];
		$this->NOM_ENLACE->DbValue = $row['NOM_ENLACE'];
		$this->Otro_Nom_Enlace->DbValue = $row['Otro_Nom_Enlace'];
		$this->Otro_CC_Enlace->DbValue = $row['Otro_CC_Enlace'];
		$this->NOM_PGE->DbValue = $row['NOM_PGE'];
		$this->Otro_Nom_PGE->DbValue = $row['Otro_Nom_PGE'];
		$this->Otro_CC_PGE->DbValue = $row['Otro_CC_PGE'];
		$this->Departamento->DbValue = $row['Departamento'];
		$this->Muncipio->DbValue = $row['Muncipio'];
		$this->NOM_VDA->DbValue = $row['NOM_VDA'];
		$this->LATITUD->DbValue = $row['LATITUD'];
		$this->GRA_LAT->DbValue = $row['GRA_LAT'];
		$this->MIN_LAT->DbValue = $row['MIN_LAT'];
		$this->SEG_LAT->DbValue = $row['SEG_LAT'];
		$this->GRA_LONG->DbValue = $row['GRA_LONG'];
		$this->MIN_LONG->DbValue = $row['MIN_LONG'];
		$this->SEG_LONG->DbValue = $row['SEG_LONG'];
		$this->FECHA_ACC->DbValue = $row['FECHA_ACC'];
		$this->HORA_ACC->DbValue = $row['HORA_ACC'];
		$this->Hora_ingreso->DbValue = $row['Hora_ingreso'];
		$this->FP_Armada->DbValue = $row['FP_Armada'];
		$this->FP_Ejercito->DbValue = $row['FP_Ejercito'];
		$this->FP_Policia->DbValue = $row['FP_Policia'];
		$this->NOM_COMANDANTE->DbValue = $row['NOM_COMANDANTE'];
		$this->TESTI1->DbValue = $row['TESTI1'];
		$this->CC_TESTI1->DbValue = $row['CC_TESTI1'];
		$this->CARGO_TESTI1->DbValue = $row['CARGO_TESTI1'];
		$this->TESTI2->DbValue = $row['TESTI2'];
		$this->CC_TESTI2->DbValue = $row['CC_TESTI2'];
		$this->CARGO_TESTI2->DbValue = $row['CARGO_TESTI2'];
		$this->Afectados->DbValue = $row['Afectados'];
		$this->NUM_Afectado->DbValue = $row['NUM_Afectado'];
		$this->Nom_Afectado->DbValue = $row['Nom_Afectado'];
		$this->CC_Afectado->DbValue = $row['CC_Afectado'];
		$this->Cargo_Afectado->DbValue = $row['Cargo_Afectado'];
		$this->Tipo_incidente->DbValue = $row['Tipo_incidente'];
		$this->Parte_Cuerpo->DbValue = $row['Parte_Cuerpo'];
		$this->ESTADO_AFEC->DbValue = $row['ESTADO_AFEC'];
		$this->EVACUADO->DbValue = $row['EVACUADO'];
		$this->DESC_ACC->DbValue = $row['DESC_ACC'];
		$this->Modificado->DbValue = $row['Modificado'];
		$this->llave_2->DbValue = $row['llave_2'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->SEG_LAT->FormValue == $this->SEG_LAT->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LAT->CurrentValue)))
			$this->SEG_LAT->CurrentValue = ew_StrToFloat($this->SEG_LAT->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LONG->FormValue == $this->SEG_LONG->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LONG->CurrentValue)))
			$this->SEG_LONG->CurrentValue = ew_StrToFloat($this->SEG_LONG->CurrentValue);

		// Convert decimal values if posted back
		if ($this->FP_Armada->FormValue == $this->FP_Armada->CurrentValue && is_numeric(ew_StrToFloat($this->FP_Armada->CurrentValue)))
			$this->FP_Armada->CurrentValue = ew_StrToFloat($this->FP_Armada->CurrentValue);

		// Convert decimal values if posted back
		if ($this->FP_Ejercito->FormValue == $this->FP_Ejercito->CurrentValue && is_numeric(ew_StrToFloat($this->FP_Ejercito->CurrentValue)))
			$this->FP_Ejercito->CurrentValue = ew_StrToFloat($this->FP_Ejercito->CurrentValue);

		// Convert decimal values if posted back
		if ($this->FP_Policia->FormValue == $this->FP_Policia->CurrentValue && is_numeric(ew_StrToFloat($this->FP_Policia->CurrentValue)))
			$this->FP_Policia->CurrentValue = ew_StrToFloat($this->FP_Policia->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

			// NOM_PE
			$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
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
			$this->NOM_PGE->ViewValue = $this->NOM_PGE->CurrentValue;
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
			$this->Tipo_incidente->ViewValue = $this->Tipo_incidente->CurrentValue;
			$this->Tipo_incidente->ViewCustomAttributes = "";

			// Parte_Cuerpo
			$this->Parte_Cuerpo->ViewValue = $this->Parte_Cuerpo->CurrentValue;
			$this->Parte_Cuerpo->ViewCustomAttributes = "";

			// ESTADO_AFEC
			$this->ESTADO_AFEC->ViewValue = $this->ESTADO_AFEC->CurrentValue;
			$this->ESTADO_AFEC->ViewCustomAttributes = "";

			// EVACUADO
			$this->EVACUADO->ViewValue = $this->EVACUADO->CurrentValue;
			$this->EVACUADO->ViewCustomAttributes = "";

			// DESC_ACC
			$this->DESC_ACC->ViewValue = $this->DESC_ACC->CurrentValue;
			$this->DESC_ACC->ViewCustomAttributes = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			$this->NOM_PE->EditValue = ew_HtmlEncode($this->NOM_PE->CurrentValue);
			$this->NOM_PE->PlaceHolder = ew_RemoveHtml($this->NOM_PE->FldCaption());

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
			$this->NOM_PGE->EditValue = ew_HtmlEncode($this->NOM_PGE->CurrentValue);
			$this->NOM_PGE->PlaceHolder = ew_RemoveHtml($this->NOM_PGE->FldCaption());

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
			$this->Tipo_incidente->EditValue = ew_HtmlEncode($this->Tipo_incidente->CurrentValue);
			$this->Tipo_incidente->PlaceHolder = ew_RemoveHtml($this->Tipo_incidente->FldCaption());

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
			$this->EVACUADO->EditValue = ew_HtmlEncode($this->EVACUADO->CurrentValue);
			$this->EVACUADO->PlaceHolder = ew_RemoveHtml($this->EVACUADO->FldCaption());

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

			// Edit refer script
			// llave

			$this->llave->HrefValue = "";

			// F_Sincron
			$this->F_Sincron->HrefValue = "";

			// USUARIO
			$this->USUARIO->HrefValue = "";

			// Cargo_gme
			$this->Cargo_gme->HrefValue = "";

			// NOM_PE
			$this->NOM_PE->HrefValue = "";

			// Otro_PE
			$this->Otro_PE->HrefValue = "";

			// NOM_APOYO
			$this->NOM_APOYO->HrefValue = "";

			// Otro_Nom_Apoyo
			$this->Otro_Nom_Apoyo->HrefValue = "";

			// Otro_CC_Apoyo
			$this->Otro_CC_Apoyo->HrefValue = "";

			// NOM_ENLACE
			$this->NOM_ENLACE->HrefValue = "";

			// Otro_Nom_Enlace
			$this->Otro_Nom_Enlace->HrefValue = "";

			// Otro_CC_Enlace
			$this->Otro_CC_Enlace->HrefValue = "";

			// NOM_PGE
			$this->NOM_PGE->HrefValue = "";

			// Otro_Nom_PGE
			$this->Otro_Nom_PGE->HrefValue = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->HrefValue = "";

			// Departamento
			$this->Departamento->HrefValue = "";

			// Muncipio
			$this->Muncipio->HrefValue = "";

			// NOM_VDA
			$this->NOM_VDA->HrefValue = "";

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

			// FECHA_ACC
			$this->FECHA_ACC->HrefValue = "";

			// HORA_ACC
			$this->HORA_ACC->HrefValue = "";

			// Hora_ingreso
			$this->Hora_ingreso->HrefValue = "";

			// FP_Armada
			$this->FP_Armada->HrefValue = "";

			// FP_Ejercito
			$this->FP_Ejercito->HrefValue = "";

			// FP_Policia
			$this->FP_Policia->HrefValue = "";

			// NOM_COMANDANTE
			$this->NOM_COMANDANTE->HrefValue = "";

			// TESTI1
			$this->TESTI1->HrefValue = "";

			// CC_TESTI1
			$this->CC_TESTI1->HrefValue = "";

			// CARGO_TESTI1
			$this->CARGO_TESTI1->HrefValue = "";

			// TESTI2
			$this->TESTI2->HrefValue = "";

			// CC_TESTI2
			$this->CC_TESTI2->HrefValue = "";

			// CARGO_TESTI2
			$this->CARGO_TESTI2->HrefValue = "";

			// Afectados
			$this->Afectados->HrefValue = "";

			// NUM_Afectado
			$this->NUM_Afectado->HrefValue = "";

			// Nom_Afectado
			$this->Nom_Afectado->HrefValue = "";

			// CC_Afectado
			$this->CC_Afectado->HrefValue = "";

			// Cargo_Afectado
			$this->Cargo_Afectado->HrefValue = "";

			// Tipo_incidente
			$this->Tipo_incidente->HrefValue = "";

			// Parte_Cuerpo
			$this->Parte_Cuerpo->HrefValue = "";

			// ESTADO_AFEC
			$this->ESTADO_AFEC->HrefValue = "";

			// EVACUADO
			$this->EVACUADO->HrefValue = "";

			// DESC_ACC
			$this->DESC_ACC->HrefValue = "";

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
		if (!$this->llave->FldIsDetailKey && !is_null($this->llave->FormValue) && $this->llave->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->llave->FldCaption(), $this->llave->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->F_Sincron->FormValue)) {
			ew_AddMessage($gsFormError, $this->F_Sincron->FldErrMsg());
		}
		if (!ew_CheckInteger($this->GRA_LAT->FormValue)) {
			ew_AddMessage($gsFormError, $this->GRA_LAT->FldErrMsg());
		}
		if (!ew_CheckInteger($this->MIN_LAT->FormValue)) {
			ew_AddMessage($gsFormError, $this->MIN_LAT->FldErrMsg());
		}
		if (!ew_CheckNumber($this->SEG_LAT->FormValue)) {
			ew_AddMessage($gsFormError, $this->SEG_LAT->FldErrMsg());
		}
		if (!ew_CheckInteger($this->GRA_LONG->FormValue)) {
			ew_AddMessage($gsFormError, $this->GRA_LONG->FldErrMsg());
		}
		if (!ew_CheckInteger($this->MIN_LONG->FormValue)) {
			ew_AddMessage($gsFormError, $this->MIN_LONG->FldErrMsg());
		}
		if (!ew_CheckNumber($this->SEG_LONG->FormValue)) {
			ew_AddMessage($gsFormError, $this->SEG_LONG->FldErrMsg());
		}
		if (!ew_CheckNumber($this->FP_Armada->FormValue)) {
			ew_AddMessage($gsFormError, $this->FP_Armada->FldErrMsg());
		}
		if (!ew_CheckNumber($this->FP_Ejercito->FormValue)) {
			ew_AddMessage($gsFormError, $this->FP_Ejercito->FldErrMsg());
		}
		if (!ew_CheckNumber($this->FP_Policia->FormValue)) {
			ew_AddMessage($gsFormError, $this->FP_Policia->FldErrMsg());
		}
		if (!ew_CheckInteger($this->Afectados->FormValue)) {
			ew_AddMessage($gsFormError, $this->Afectados->FldErrMsg());
		}
		if (!ew_CheckInteger($this->NUM_Afectado->FormValue)) {
			ew_AddMessage($gsFormError, $this->NUM_Afectado->FldErrMsg());
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

			// llave
			$this->llave->SetDbValueDef($rsnew, $this->llave->CurrentValue, "", $this->llave->ReadOnly);

			// F_Sincron
			$this->F_Sincron->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 7), NULL, $this->F_Sincron->ReadOnly);

			// USUARIO
			$this->USUARIO->SetDbValueDef($rsnew, $this->USUARIO->CurrentValue, NULL, $this->USUARIO->ReadOnly);

			// Cargo_gme
			$this->Cargo_gme->SetDbValueDef($rsnew, $this->Cargo_gme->CurrentValue, NULL, $this->Cargo_gme->ReadOnly);

			// NOM_PE
			$this->NOM_PE->SetDbValueDef($rsnew, $this->NOM_PE->CurrentValue, NULL, $this->NOM_PE->ReadOnly);

			// Otro_PE
			$this->Otro_PE->SetDbValueDef($rsnew, $this->Otro_PE->CurrentValue, NULL, $this->Otro_PE->ReadOnly);

			// NOM_APOYO
			$this->NOM_APOYO->SetDbValueDef($rsnew, $this->NOM_APOYO->CurrentValue, NULL, $this->NOM_APOYO->ReadOnly);

			// Otro_Nom_Apoyo
			$this->Otro_Nom_Apoyo->SetDbValueDef($rsnew, $this->Otro_Nom_Apoyo->CurrentValue, NULL, $this->Otro_Nom_Apoyo->ReadOnly);

			// Otro_CC_Apoyo
			$this->Otro_CC_Apoyo->SetDbValueDef($rsnew, $this->Otro_CC_Apoyo->CurrentValue, NULL, $this->Otro_CC_Apoyo->ReadOnly);

			// NOM_ENLACE
			$this->NOM_ENLACE->SetDbValueDef($rsnew, $this->NOM_ENLACE->CurrentValue, NULL, $this->NOM_ENLACE->ReadOnly);

			// Otro_Nom_Enlace
			$this->Otro_Nom_Enlace->SetDbValueDef($rsnew, $this->Otro_Nom_Enlace->CurrentValue, NULL, $this->Otro_Nom_Enlace->ReadOnly);

			// Otro_CC_Enlace
			$this->Otro_CC_Enlace->SetDbValueDef($rsnew, $this->Otro_CC_Enlace->CurrentValue, NULL, $this->Otro_CC_Enlace->ReadOnly);

			// NOM_PGE
			$this->NOM_PGE->SetDbValueDef($rsnew, $this->NOM_PGE->CurrentValue, NULL, $this->NOM_PGE->ReadOnly);

			// Otro_Nom_PGE
			$this->Otro_Nom_PGE->SetDbValueDef($rsnew, $this->Otro_Nom_PGE->CurrentValue, NULL, $this->Otro_Nom_PGE->ReadOnly);

			// Otro_CC_PGE
			$this->Otro_CC_PGE->SetDbValueDef($rsnew, $this->Otro_CC_PGE->CurrentValue, NULL, $this->Otro_CC_PGE->ReadOnly);

			// Departamento
			$this->Departamento->SetDbValueDef($rsnew, $this->Departamento->CurrentValue, NULL, $this->Departamento->ReadOnly);

			// Muncipio
			$this->Muncipio->SetDbValueDef($rsnew, $this->Muncipio->CurrentValue, NULL, $this->Muncipio->ReadOnly);

			// NOM_VDA
			$this->NOM_VDA->SetDbValueDef($rsnew, $this->NOM_VDA->CurrentValue, NULL, $this->NOM_VDA->ReadOnly);

			// LATITUD
			$this->LATITUD->SetDbValueDef($rsnew, $this->LATITUD->CurrentValue, NULL, $this->LATITUD->ReadOnly);

			// GRA_LAT
			$this->GRA_LAT->SetDbValueDef($rsnew, $this->GRA_LAT->CurrentValue, NULL, $this->GRA_LAT->ReadOnly);

			// MIN_LAT
			$this->MIN_LAT->SetDbValueDef($rsnew, $this->MIN_LAT->CurrentValue, NULL, $this->MIN_LAT->ReadOnly);

			// SEG_LAT
			$this->SEG_LAT->SetDbValueDef($rsnew, $this->SEG_LAT->CurrentValue, NULL, $this->SEG_LAT->ReadOnly);

			// GRA_LONG
			$this->GRA_LONG->SetDbValueDef($rsnew, $this->GRA_LONG->CurrentValue, NULL, $this->GRA_LONG->ReadOnly);

			// MIN_LONG
			$this->MIN_LONG->SetDbValueDef($rsnew, $this->MIN_LONG->CurrentValue, NULL, $this->MIN_LONG->ReadOnly);

			// SEG_LONG
			$this->SEG_LONG->SetDbValueDef($rsnew, $this->SEG_LONG->CurrentValue, NULL, $this->SEG_LONG->ReadOnly);

			// FECHA_ACC
			$this->FECHA_ACC->SetDbValueDef($rsnew, $this->FECHA_ACC->CurrentValue, NULL, $this->FECHA_ACC->ReadOnly);

			// HORA_ACC
			$this->HORA_ACC->SetDbValueDef($rsnew, $this->HORA_ACC->CurrentValue, NULL, $this->HORA_ACC->ReadOnly);

			// Hora_ingreso
			$this->Hora_ingreso->SetDbValueDef($rsnew, $this->Hora_ingreso->CurrentValue, NULL, $this->Hora_ingreso->ReadOnly);

			// FP_Armada
			$this->FP_Armada->SetDbValueDef($rsnew, $this->FP_Armada->CurrentValue, NULL, $this->FP_Armada->ReadOnly);

			// FP_Ejercito
			$this->FP_Ejercito->SetDbValueDef($rsnew, $this->FP_Ejercito->CurrentValue, NULL, $this->FP_Ejercito->ReadOnly);

			// FP_Policia
			$this->FP_Policia->SetDbValueDef($rsnew, $this->FP_Policia->CurrentValue, NULL, $this->FP_Policia->ReadOnly);

			// NOM_COMANDANTE
			$this->NOM_COMANDANTE->SetDbValueDef($rsnew, $this->NOM_COMANDANTE->CurrentValue, NULL, $this->NOM_COMANDANTE->ReadOnly);

			// TESTI1
			$this->TESTI1->SetDbValueDef($rsnew, $this->TESTI1->CurrentValue, NULL, $this->TESTI1->ReadOnly);

			// CC_TESTI1
			$this->CC_TESTI1->SetDbValueDef($rsnew, $this->CC_TESTI1->CurrentValue, NULL, $this->CC_TESTI1->ReadOnly);

			// CARGO_TESTI1
			$this->CARGO_TESTI1->SetDbValueDef($rsnew, $this->CARGO_TESTI1->CurrentValue, NULL, $this->CARGO_TESTI1->ReadOnly);

			// TESTI2
			$this->TESTI2->SetDbValueDef($rsnew, $this->TESTI2->CurrentValue, NULL, $this->TESTI2->ReadOnly);

			// CC_TESTI2
			$this->CC_TESTI2->SetDbValueDef($rsnew, $this->CC_TESTI2->CurrentValue, NULL, $this->CC_TESTI2->ReadOnly);

			// CARGO_TESTI2
			$this->CARGO_TESTI2->SetDbValueDef($rsnew, $this->CARGO_TESTI2->CurrentValue, NULL, $this->CARGO_TESTI2->ReadOnly);

			// Afectados
			$this->Afectados->SetDbValueDef($rsnew, $this->Afectados->CurrentValue, NULL, $this->Afectados->ReadOnly);

			// NUM_Afectado
			$this->NUM_Afectado->SetDbValueDef($rsnew, $this->NUM_Afectado->CurrentValue, NULL, $this->NUM_Afectado->ReadOnly);

			// Nom_Afectado
			$this->Nom_Afectado->SetDbValueDef($rsnew, $this->Nom_Afectado->CurrentValue, NULL, $this->Nom_Afectado->ReadOnly);

			// CC_Afectado
			$this->CC_Afectado->SetDbValueDef($rsnew, $this->CC_Afectado->CurrentValue, NULL, $this->CC_Afectado->ReadOnly);

			// Cargo_Afectado
			$this->Cargo_Afectado->SetDbValueDef($rsnew, $this->Cargo_Afectado->CurrentValue, NULL, $this->Cargo_Afectado->ReadOnly);

			// Tipo_incidente
			$this->Tipo_incidente->SetDbValueDef($rsnew, $this->Tipo_incidente->CurrentValue, NULL, $this->Tipo_incidente->ReadOnly);

			// Parte_Cuerpo
			$this->Parte_Cuerpo->SetDbValueDef($rsnew, $this->Parte_Cuerpo->CurrentValue, NULL, $this->Parte_Cuerpo->ReadOnly);

			// ESTADO_AFEC
			$this->ESTADO_AFEC->SetDbValueDef($rsnew, $this->ESTADO_AFEC->CurrentValue, NULL, $this->ESTADO_AFEC->ReadOnly);

			// EVACUADO
			$this->EVACUADO->SetDbValueDef($rsnew, $this->EVACUADO->CurrentValue, NULL, $this->EVACUADO->ReadOnly);

			// DESC_ACC
			$this->DESC_ACC->SetDbValueDef($rsnew, $this->DESC_ACC->CurrentValue, NULL, $this->DESC_ACC->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "view1_acclist.php", "", $this->TableVar, TRUE);
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
if (!isset($view1_acc_edit)) $view1_acc_edit = new cview1_acc_edit();

// Page init
$view1_acc_edit->Page_Init();

// Page main
$view1_acc_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_acc_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view1_acc_edit = new ew_Page("view1_acc_edit");
view1_acc_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = view1_acc_edit.PageID; // For backward compatibility

// Form object
var fview1_accedit = new ew_Form("fview1_accedit");

// Validate form
fview1_accedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_llave");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view1_acc->llave->FldCaption(), $view1_acc->llave->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_F_Sincron");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->F_Sincron->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_GRA_LAT");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->GRA_LAT->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_MIN_LAT");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->MIN_LAT->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SEG_LAT");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->SEG_LAT->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_GRA_LONG");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->GRA_LONG->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_MIN_LONG");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->MIN_LONG->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SEG_LONG");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->SEG_LONG->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_FP_Armada");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->FP_Armada->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_FP_Ejercito");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->FP_Ejercito->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_FP_Policia");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->FP_Policia->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Afectados");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->Afectados->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_NUM_Afectado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view1_acc->NUM_Afectado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Modificado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view1_acc->Modificado->FldCaption(), $view1_acc->Modificado->ReqErrMsg)) ?>");

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
fview1_accedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1_accedit.ValidateRequired = true;
<?php } else { ?>
fview1_accedit.ValidateRequired = false; 
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
<?php $view1_acc_edit->ShowPageHeader(); ?>
<?php
$view1_acc_edit->ShowMessage();
?>
<form name="fview1_accedit" id="fview1_accedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view1_acc_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view1_acc_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view1_acc">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($view1_acc->llave->Visible) { // llave ?>
	<div id="r_llave" class="form-group">
		<label id="elh_view1_acc_llave" for="x_llave" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->llave->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->llave->CellAttributes() ?>>
<span id="el_view1_acc_llave">
<input type="text" data-field="x_llave" name="x_llave" id="x_llave" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($view1_acc->llave->PlaceHolder) ?>" value="<?php echo $view1_acc->llave->EditValue ?>"<?php echo $view1_acc->llave->EditAttributes() ?>>
</span>
<?php echo $view1_acc->llave->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->F_Sincron->Visible) { // F_Sincron ?>
	<div id="r_F_Sincron" class="form-group">
		<label id="elh_view1_acc_F_Sincron" for="x_F_Sincron" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->F_Sincron->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->F_Sincron->CellAttributes() ?>>
<span id="el_view1_acc_F_Sincron">
<input type="text" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" placeholder="<?php echo ew_HtmlEncode($view1_acc->F_Sincron->PlaceHolder) ?>" value="<?php echo $view1_acc->F_Sincron->EditValue ?>"<?php echo $view1_acc->F_Sincron->EditAttributes() ?>>
</span>
<?php echo $view1_acc->F_Sincron->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->USUARIO->Visible) { // USUARIO ?>
	<div id="r_USUARIO" class="form-group">
		<label id="elh_view1_acc_USUARIO" for="x_USUARIO" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->USUARIO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->USUARIO->CellAttributes() ?>>
<span id="el_view1_acc_USUARIO">
<textarea data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->USUARIO->PlaceHolder) ?>"<?php echo $view1_acc->USUARIO->EditAttributes() ?>><?php echo $view1_acc->USUARIO->EditValue ?></textarea>
</span>
<?php echo $view1_acc->USUARIO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Cargo_gme->Visible) { // Cargo_gme ?>
	<div id="r_Cargo_gme" class="form-group">
		<label id="elh_view1_acc_Cargo_gme" for="x_Cargo_gme" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Cargo_gme->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Cargo_gme->CellAttributes() ?>>
<span id="el_view1_acc_Cargo_gme">
<input type="text" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view1_acc->Cargo_gme->PlaceHolder) ?>" value="<?php echo $view1_acc->Cargo_gme->EditValue ?>"<?php echo $view1_acc->Cargo_gme->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Cargo_gme->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->NOM_PE->Visible) { // NOM_PE ?>
	<div id="r_NOM_PE" class="form-group">
		<label id="elh_view1_acc_NOM_PE" for="x_NOM_PE" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->NOM_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->NOM_PE->CellAttributes() ?>>
<span id="el_view1_acc_NOM_PE">
<textarea data-field="x_NOM_PE" name="x_NOM_PE" id="x_NOM_PE" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->NOM_PE->PlaceHolder) ?>"<?php echo $view1_acc->NOM_PE->EditAttributes() ?>><?php echo $view1_acc->NOM_PE->EditValue ?></textarea>
</span>
<?php echo $view1_acc->NOM_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Otro_PE->Visible) { // Otro_PE ?>
	<div id="r_Otro_PE" class="form-group">
		<label id="elh_view1_acc_Otro_PE" for="x_Otro_PE" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Otro_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Otro_PE->CellAttributes() ?>>
<span id="el_view1_acc_Otro_PE">
<input type="text" data-field="x_Otro_PE" name="x_Otro_PE" id="x_Otro_PE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Otro_PE->PlaceHolder) ?>" value="<?php echo $view1_acc->Otro_PE->EditValue ?>"<?php echo $view1_acc->Otro_PE->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Otro_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->NOM_APOYO->Visible) { // NOM_APOYO ?>
	<div id="r_NOM_APOYO" class="form-group">
		<label id="elh_view1_acc_NOM_APOYO" for="x_NOM_APOYO" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->NOM_APOYO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->NOM_APOYO->CellAttributes() ?>>
<span id="el_view1_acc_NOM_APOYO">
<textarea data-field="x_NOM_APOYO" name="x_NOM_APOYO" id="x_NOM_APOYO" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->NOM_APOYO->PlaceHolder) ?>"<?php echo $view1_acc->NOM_APOYO->EditAttributes() ?>><?php echo $view1_acc->NOM_APOYO->EditValue ?></textarea>
</span>
<?php echo $view1_acc->NOM_APOYO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
	<div id="r_Otro_Nom_Apoyo" class="form-group">
		<label id="elh_view1_acc_Otro_Nom_Apoyo" for="x_Otro_Nom_Apoyo" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Otro_Nom_Apoyo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Otro_Nom_Apoyo->CellAttributes() ?>>
<span id="el_view1_acc_Otro_Nom_Apoyo">
<input type="text" data-field="x_Otro_Nom_Apoyo" name="x_Otro_Nom_Apoyo" id="x_Otro_Nom_Apoyo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Otro_Nom_Apoyo->PlaceHolder) ?>" value="<?php echo $view1_acc->Otro_Nom_Apoyo->EditValue ?>"<?php echo $view1_acc->Otro_Nom_Apoyo->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Otro_Nom_Apoyo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
	<div id="r_Otro_CC_Apoyo" class="form-group">
		<label id="elh_view1_acc_Otro_CC_Apoyo" for="x_Otro_CC_Apoyo" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Otro_CC_Apoyo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Otro_CC_Apoyo->CellAttributes() ?>>
<span id="el_view1_acc_Otro_CC_Apoyo">
<input type="text" data-field="x_Otro_CC_Apoyo" name="x_Otro_CC_Apoyo" id="x_Otro_CC_Apoyo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Otro_CC_Apoyo->PlaceHolder) ?>" value="<?php echo $view1_acc->Otro_CC_Apoyo->EditValue ?>"<?php echo $view1_acc->Otro_CC_Apoyo->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Otro_CC_Apoyo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->NOM_ENLACE->Visible) { // NOM_ENLACE ?>
	<div id="r_NOM_ENLACE" class="form-group">
		<label id="elh_view1_acc_NOM_ENLACE" for="x_NOM_ENLACE" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->NOM_ENLACE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->NOM_ENLACE->CellAttributes() ?>>
<span id="el_view1_acc_NOM_ENLACE">
<textarea data-field="x_NOM_ENLACE" name="x_NOM_ENLACE" id="x_NOM_ENLACE" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->NOM_ENLACE->PlaceHolder) ?>"<?php echo $view1_acc->NOM_ENLACE->EditAttributes() ?>><?php echo $view1_acc->NOM_ENLACE->EditValue ?></textarea>
</span>
<?php echo $view1_acc->NOM_ENLACE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_Enlace->Visible) { // Otro_Nom_Enlace ?>
	<div id="r_Otro_Nom_Enlace" class="form-group">
		<label id="elh_view1_acc_Otro_Nom_Enlace" for="x_Otro_Nom_Enlace" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Otro_Nom_Enlace->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Otro_Nom_Enlace->CellAttributes() ?>>
<span id="el_view1_acc_Otro_Nom_Enlace">
<input type="text" data-field="x_Otro_Nom_Enlace" name="x_Otro_Nom_Enlace" id="x_Otro_Nom_Enlace" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Otro_Nom_Enlace->PlaceHolder) ?>" value="<?php echo $view1_acc->Otro_Nom_Enlace->EditValue ?>"<?php echo $view1_acc->Otro_Nom_Enlace->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Otro_Nom_Enlace->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Otro_CC_Enlace->Visible) { // Otro_CC_Enlace ?>
	<div id="r_Otro_CC_Enlace" class="form-group">
		<label id="elh_view1_acc_Otro_CC_Enlace" for="x_Otro_CC_Enlace" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Otro_CC_Enlace->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Otro_CC_Enlace->CellAttributes() ?>>
<span id="el_view1_acc_Otro_CC_Enlace">
<input type="text" data-field="x_Otro_CC_Enlace" name="x_Otro_CC_Enlace" id="x_Otro_CC_Enlace" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Otro_CC_Enlace->PlaceHolder) ?>" value="<?php echo $view1_acc->Otro_CC_Enlace->EditValue ?>"<?php echo $view1_acc->Otro_CC_Enlace->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Otro_CC_Enlace->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->NOM_PGE->Visible) { // NOM_PGE ?>
	<div id="r_NOM_PGE" class="form-group">
		<label id="elh_view1_acc_NOM_PGE" for="x_NOM_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->NOM_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->NOM_PGE->CellAttributes() ?>>
<span id="el_view1_acc_NOM_PGE">
<textarea data-field="x_NOM_PGE" name="x_NOM_PGE" id="x_NOM_PGE" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->NOM_PGE->PlaceHolder) ?>"<?php echo $view1_acc->NOM_PGE->EditAttributes() ?>><?php echo $view1_acc->NOM_PGE->EditValue ?></textarea>
</span>
<?php echo $view1_acc->NOM_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_PGE->Visible) { // Otro_Nom_PGE ?>
	<div id="r_Otro_Nom_PGE" class="form-group">
		<label id="elh_view1_acc_Otro_Nom_PGE" for="x_Otro_Nom_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Otro_Nom_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Otro_Nom_PGE->CellAttributes() ?>>
<span id="el_view1_acc_Otro_Nom_PGE">
<input type="text" data-field="x_Otro_Nom_PGE" name="x_Otro_Nom_PGE" id="x_Otro_Nom_PGE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Otro_Nom_PGE->PlaceHolder) ?>" value="<?php echo $view1_acc->Otro_Nom_PGE->EditValue ?>"<?php echo $view1_acc->Otro_Nom_PGE->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Otro_Nom_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
	<div id="r_Otro_CC_PGE" class="form-group">
		<label id="elh_view1_acc_Otro_CC_PGE" for="x_Otro_CC_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Otro_CC_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Otro_CC_PGE->CellAttributes() ?>>
<span id="el_view1_acc_Otro_CC_PGE">
<input type="text" data-field="x_Otro_CC_PGE" name="x_Otro_CC_PGE" id="x_Otro_CC_PGE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Otro_CC_PGE->PlaceHolder) ?>" value="<?php echo $view1_acc->Otro_CC_PGE->EditValue ?>"<?php echo $view1_acc->Otro_CC_PGE->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Otro_CC_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Departamento->Visible) { // Departamento ?>
	<div id="r_Departamento" class="form-group">
		<label id="elh_view1_acc_Departamento" for="x_Departamento" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Departamento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Departamento->CellAttributes() ?>>
<span id="el_view1_acc_Departamento">
<input type="text" data-field="x_Departamento" name="x_Departamento" id="x_Departamento" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view1_acc->Departamento->PlaceHolder) ?>" value="<?php echo $view1_acc->Departamento->EditValue ?>"<?php echo $view1_acc->Departamento->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Muncipio->Visible) { // Muncipio ?>
	<div id="r_Muncipio" class="form-group">
		<label id="elh_view1_acc_Muncipio" for="x_Muncipio" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Muncipio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Muncipio->CellAttributes() ?>>
<span id="el_view1_acc_Muncipio">
<textarea data-field="x_Muncipio" name="x_Muncipio" id="x_Muncipio" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->Muncipio->PlaceHolder) ?>"<?php echo $view1_acc->Muncipio->EditAttributes() ?>><?php echo $view1_acc->Muncipio->EditValue ?></textarea>
</span>
<?php echo $view1_acc->Muncipio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->NOM_VDA->Visible) { // NOM_VDA ?>
	<div id="r_NOM_VDA" class="form-group">
		<label id="elh_view1_acc_NOM_VDA" for="x_NOM_VDA" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->NOM_VDA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->NOM_VDA->CellAttributes() ?>>
<span id="el_view1_acc_NOM_VDA">
<input type="text" data-field="x_NOM_VDA" name="x_NOM_VDA" id="x_NOM_VDA" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->NOM_VDA->PlaceHolder) ?>" value="<?php echo $view1_acc->NOM_VDA->EditValue ?>"<?php echo $view1_acc->NOM_VDA->EditAttributes() ?>>
</span>
<?php echo $view1_acc->NOM_VDA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->LATITUD->Visible) { // LATITUD ?>
	<div id="r_LATITUD" class="form-group">
		<label id="elh_view1_acc_LATITUD" for="x_LATITUD" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->LATITUD->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->LATITUD->CellAttributes() ?>>
<span id="el_view1_acc_LATITUD">
<textarea data-field="x_LATITUD" name="x_LATITUD" id="x_LATITUD" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->LATITUD->PlaceHolder) ?>"<?php echo $view1_acc->LATITUD->EditAttributes() ?>><?php echo $view1_acc->LATITUD->EditValue ?></textarea>
</span>
<?php echo $view1_acc->LATITUD->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->GRA_LAT->Visible) { // GRA_LAT ?>
	<div id="r_GRA_LAT" class="form-group">
		<label id="elh_view1_acc_GRA_LAT" for="x_GRA_LAT" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->GRA_LAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->GRA_LAT->CellAttributes() ?>>
<span id="el_view1_acc_GRA_LAT">
<input type="text" data-field="x_GRA_LAT" name="x_GRA_LAT" id="x_GRA_LAT" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->GRA_LAT->PlaceHolder) ?>" value="<?php echo $view1_acc->GRA_LAT->EditValue ?>"<?php echo $view1_acc->GRA_LAT->EditAttributes() ?>>
</span>
<?php echo $view1_acc->GRA_LAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->MIN_LAT->Visible) { // MIN_LAT ?>
	<div id="r_MIN_LAT" class="form-group">
		<label id="elh_view1_acc_MIN_LAT" for="x_MIN_LAT" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->MIN_LAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->MIN_LAT->CellAttributes() ?>>
<span id="el_view1_acc_MIN_LAT">
<input type="text" data-field="x_MIN_LAT" name="x_MIN_LAT" id="x_MIN_LAT" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->MIN_LAT->PlaceHolder) ?>" value="<?php echo $view1_acc->MIN_LAT->EditValue ?>"<?php echo $view1_acc->MIN_LAT->EditAttributes() ?>>
</span>
<?php echo $view1_acc->MIN_LAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->SEG_LAT->Visible) { // SEG_LAT ?>
	<div id="r_SEG_LAT" class="form-group">
		<label id="elh_view1_acc_SEG_LAT" for="x_SEG_LAT" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->SEG_LAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->SEG_LAT->CellAttributes() ?>>
<span id="el_view1_acc_SEG_LAT">
<input type="text" data-field="x_SEG_LAT" name="x_SEG_LAT" id="x_SEG_LAT" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->SEG_LAT->PlaceHolder) ?>" value="<?php echo $view1_acc->SEG_LAT->EditValue ?>"<?php echo $view1_acc->SEG_LAT->EditAttributes() ?>>
</span>
<?php echo $view1_acc->SEG_LAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->GRA_LONG->Visible) { // GRA_LONG ?>
	<div id="r_GRA_LONG" class="form-group">
		<label id="elh_view1_acc_GRA_LONG" for="x_GRA_LONG" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->GRA_LONG->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->GRA_LONG->CellAttributes() ?>>
<span id="el_view1_acc_GRA_LONG">
<input type="text" data-field="x_GRA_LONG" name="x_GRA_LONG" id="x_GRA_LONG" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->GRA_LONG->PlaceHolder) ?>" value="<?php echo $view1_acc->GRA_LONG->EditValue ?>"<?php echo $view1_acc->GRA_LONG->EditAttributes() ?>>
</span>
<?php echo $view1_acc->GRA_LONG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->MIN_LONG->Visible) { // MIN_LONG ?>
	<div id="r_MIN_LONG" class="form-group">
		<label id="elh_view1_acc_MIN_LONG" for="x_MIN_LONG" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->MIN_LONG->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->MIN_LONG->CellAttributes() ?>>
<span id="el_view1_acc_MIN_LONG">
<input type="text" data-field="x_MIN_LONG" name="x_MIN_LONG" id="x_MIN_LONG" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->MIN_LONG->PlaceHolder) ?>" value="<?php echo $view1_acc->MIN_LONG->EditValue ?>"<?php echo $view1_acc->MIN_LONG->EditAttributes() ?>>
</span>
<?php echo $view1_acc->MIN_LONG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->SEG_LONG->Visible) { // SEG_LONG ?>
	<div id="r_SEG_LONG" class="form-group">
		<label id="elh_view1_acc_SEG_LONG" for="x_SEG_LONG" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->SEG_LONG->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->SEG_LONG->CellAttributes() ?>>
<span id="el_view1_acc_SEG_LONG">
<input type="text" data-field="x_SEG_LONG" name="x_SEG_LONG" id="x_SEG_LONG" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->SEG_LONG->PlaceHolder) ?>" value="<?php echo $view1_acc->SEG_LONG->EditValue ?>"<?php echo $view1_acc->SEG_LONG->EditAttributes() ?>>
</span>
<?php echo $view1_acc->SEG_LONG->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->FECHA_ACC->Visible) { // FECHA_ACC ?>
	<div id="r_FECHA_ACC" class="form-group">
		<label id="elh_view1_acc_FECHA_ACC" for="x_FECHA_ACC" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->FECHA_ACC->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->FECHA_ACC->CellAttributes() ?>>
<span id="el_view1_acc_FECHA_ACC">
<input type="text" data-field="x_FECHA_ACC" name="x_FECHA_ACC" id="x_FECHA_ACC" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view1_acc->FECHA_ACC->PlaceHolder) ?>" value="<?php echo $view1_acc->FECHA_ACC->EditValue ?>"<?php echo $view1_acc->FECHA_ACC->EditAttributes() ?>>
</span>
<?php echo $view1_acc->FECHA_ACC->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->HORA_ACC->Visible) { // HORA_ACC ?>
	<div id="r_HORA_ACC" class="form-group">
		<label id="elh_view1_acc_HORA_ACC" for="x_HORA_ACC" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->HORA_ACC->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->HORA_ACC->CellAttributes() ?>>
<span id="el_view1_acc_HORA_ACC">
<input type="text" data-field="x_HORA_ACC" name="x_HORA_ACC" id="x_HORA_ACC" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($view1_acc->HORA_ACC->PlaceHolder) ?>" value="<?php echo $view1_acc->HORA_ACC->EditValue ?>"<?php echo $view1_acc->HORA_ACC->EditAttributes() ?>>
</span>
<?php echo $view1_acc->HORA_ACC->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Hora_ingreso->Visible) { // Hora_ingreso ?>
	<div id="r_Hora_ingreso" class="form-group">
		<label id="elh_view1_acc_Hora_ingreso" for="x_Hora_ingreso" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Hora_ingreso->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Hora_ingreso->CellAttributes() ?>>
<span id="el_view1_acc_Hora_ingreso">
<input type="text" data-field="x_Hora_ingreso" name="x_Hora_ingreso" id="x_Hora_ingreso" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($view1_acc->Hora_ingreso->PlaceHolder) ?>" value="<?php echo $view1_acc->Hora_ingreso->EditValue ?>"<?php echo $view1_acc->Hora_ingreso->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Hora_ingreso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->FP_Armada->Visible) { // FP_Armada ?>
	<div id="r_FP_Armada" class="form-group">
		<label id="elh_view1_acc_FP_Armada" for="x_FP_Armada" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->FP_Armada->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->FP_Armada->CellAttributes() ?>>
<span id="el_view1_acc_FP_Armada">
<input type="text" data-field="x_FP_Armada" name="x_FP_Armada" id="x_FP_Armada" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->FP_Armada->PlaceHolder) ?>" value="<?php echo $view1_acc->FP_Armada->EditValue ?>"<?php echo $view1_acc->FP_Armada->EditAttributes() ?>>
</span>
<?php echo $view1_acc->FP_Armada->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->FP_Ejercito->Visible) { // FP_Ejercito ?>
	<div id="r_FP_Ejercito" class="form-group">
		<label id="elh_view1_acc_FP_Ejercito" for="x_FP_Ejercito" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->FP_Ejercito->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->FP_Ejercito->CellAttributes() ?>>
<span id="el_view1_acc_FP_Ejercito">
<input type="text" data-field="x_FP_Ejercito" name="x_FP_Ejercito" id="x_FP_Ejercito" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->FP_Ejercito->PlaceHolder) ?>" value="<?php echo $view1_acc->FP_Ejercito->EditValue ?>"<?php echo $view1_acc->FP_Ejercito->EditAttributes() ?>>
</span>
<?php echo $view1_acc->FP_Ejercito->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->FP_Policia->Visible) { // FP_Policia ?>
	<div id="r_FP_Policia" class="form-group">
		<label id="elh_view1_acc_FP_Policia" for="x_FP_Policia" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->FP_Policia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->FP_Policia->CellAttributes() ?>>
<span id="el_view1_acc_FP_Policia">
<input type="text" data-field="x_FP_Policia" name="x_FP_Policia" id="x_FP_Policia" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->FP_Policia->PlaceHolder) ?>" value="<?php echo $view1_acc->FP_Policia->EditValue ?>"<?php echo $view1_acc->FP_Policia->EditAttributes() ?>>
</span>
<?php echo $view1_acc->FP_Policia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->NOM_COMANDANTE->Visible) { // NOM_COMANDANTE ?>
	<div id="r_NOM_COMANDANTE" class="form-group">
		<label id="elh_view1_acc_NOM_COMANDANTE" for="x_NOM_COMANDANTE" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->NOM_COMANDANTE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->NOM_COMANDANTE->CellAttributes() ?>>
<span id="el_view1_acc_NOM_COMANDANTE">
<input type="text" data-field="x_NOM_COMANDANTE" name="x_NOM_COMANDANTE" id="x_NOM_COMANDANTE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->NOM_COMANDANTE->PlaceHolder) ?>" value="<?php echo $view1_acc->NOM_COMANDANTE->EditValue ?>"<?php echo $view1_acc->NOM_COMANDANTE->EditAttributes() ?>>
</span>
<?php echo $view1_acc->NOM_COMANDANTE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->TESTI1->Visible) { // TESTI1 ?>
	<div id="r_TESTI1" class="form-group">
		<label id="elh_view1_acc_TESTI1" for="x_TESTI1" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->TESTI1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->TESTI1->CellAttributes() ?>>
<span id="el_view1_acc_TESTI1">
<input type="text" data-field="x_TESTI1" name="x_TESTI1" id="x_TESTI1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->TESTI1->PlaceHolder) ?>" value="<?php echo $view1_acc->TESTI1->EditValue ?>"<?php echo $view1_acc->TESTI1->EditAttributes() ?>>
</span>
<?php echo $view1_acc->TESTI1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->CC_TESTI1->Visible) { // CC_TESTI1 ?>
	<div id="r_CC_TESTI1" class="form-group">
		<label id="elh_view1_acc_CC_TESTI1" for="x_CC_TESTI1" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->CC_TESTI1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->CC_TESTI1->CellAttributes() ?>>
<span id="el_view1_acc_CC_TESTI1">
<input type="text" data-field="x_CC_TESTI1" name="x_CC_TESTI1" id="x_CC_TESTI1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->CC_TESTI1->PlaceHolder) ?>" value="<?php echo $view1_acc->CC_TESTI1->EditValue ?>"<?php echo $view1_acc->CC_TESTI1->EditAttributes() ?>>
</span>
<?php echo $view1_acc->CC_TESTI1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->CARGO_TESTI1->Visible) { // CARGO_TESTI1 ?>
	<div id="r_CARGO_TESTI1" class="form-group">
		<label id="elh_view1_acc_CARGO_TESTI1" for="x_CARGO_TESTI1" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->CARGO_TESTI1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->CARGO_TESTI1->CellAttributes() ?>>
<span id="el_view1_acc_CARGO_TESTI1">
<textarea data-field="x_CARGO_TESTI1" name="x_CARGO_TESTI1" id="x_CARGO_TESTI1" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->CARGO_TESTI1->PlaceHolder) ?>"<?php echo $view1_acc->CARGO_TESTI1->EditAttributes() ?>><?php echo $view1_acc->CARGO_TESTI1->EditValue ?></textarea>
</span>
<?php echo $view1_acc->CARGO_TESTI1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->TESTI2->Visible) { // TESTI2 ?>
	<div id="r_TESTI2" class="form-group">
		<label id="elh_view1_acc_TESTI2" for="x_TESTI2" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->TESTI2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->TESTI2->CellAttributes() ?>>
<span id="el_view1_acc_TESTI2">
<input type="text" data-field="x_TESTI2" name="x_TESTI2" id="x_TESTI2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->TESTI2->PlaceHolder) ?>" value="<?php echo $view1_acc->TESTI2->EditValue ?>"<?php echo $view1_acc->TESTI2->EditAttributes() ?>>
</span>
<?php echo $view1_acc->TESTI2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->CC_TESTI2->Visible) { // CC_TESTI2 ?>
	<div id="r_CC_TESTI2" class="form-group">
		<label id="elh_view1_acc_CC_TESTI2" for="x_CC_TESTI2" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->CC_TESTI2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->CC_TESTI2->CellAttributes() ?>>
<span id="el_view1_acc_CC_TESTI2">
<input type="text" data-field="x_CC_TESTI2" name="x_CC_TESTI2" id="x_CC_TESTI2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->CC_TESTI2->PlaceHolder) ?>" value="<?php echo $view1_acc->CC_TESTI2->EditValue ?>"<?php echo $view1_acc->CC_TESTI2->EditAttributes() ?>>
</span>
<?php echo $view1_acc->CC_TESTI2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->CARGO_TESTI2->Visible) { // CARGO_TESTI2 ?>
	<div id="r_CARGO_TESTI2" class="form-group">
		<label id="elh_view1_acc_CARGO_TESTI2" for="x_CARGO_TESTI2" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->CARGO_TESTI2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->CARGO_TESTI2->CellAttributes() ?>>
<span id="el_view1_acc_CARGO_TESTI2">
<textarea data-field="x_CARGO_TESTI2" name="x_CARGO_TESTI2" id="x_CARGO_TESTI2" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->CARGO_TESTI2->PlaceHolder) ?>"<?php echo $view1_acc->CARGO_TESTI2->EditAttributes() ?>><?php echo $view1_acc->CARGO_TESTI2->EditValue ?></textarea>
</span>
<?php echo $view1_acc->CARGO_TESTI2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Afectados->Visible) { // Afectados ?>
	<div id="r_Afectados" class="form-group">
		<label id="elh_view1_acc_Afectados" for="x_Afectados" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Afectados->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Afectados->CellAttributes() ?>>
<span id="el_view1_acc_Afectados">
<input type="text" data-field="x_Afectados" name="x_Afectados" id="x_Afectados" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->Afectados->PlaceHolder) ?>" value="<?php echo $view1_acc->Afectados->EditValue ?>"<?php echo $view1_acc->Afectados->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Afectados->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->NUM_Afectado->Visible) { // NUM_Afectado ?>
	<div id="r_NUM_Afectado" class="form-group">
		<label id="elh_view1_acc_NUM_Afectado" for="x_NUM_Afectado" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->NUM_Afectado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->NUM_Afectado->CellAttributes() ?>>
<span id="el_view1_acc_NUM_Afectado">
<input type="text" data-field="x_NUM_Afectado" name="x_NUM_Afectado" id="x_NUM_Afectado" size="30" placeholder="<?php echo ew_HtmlEncode($view1_acc->NUM_Afectado->PlaceHolder) ?>" value="<?php echo $view1_acc->NUM_Afectado->EditValue ?>"<?php echo $view1_acc->NUM_Afectado->EditAttributes() ?>>
</span>
<?php echo $view1_acc->NUM_Afectado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Nom_Afectado->Visible) { // Nom_Afectado ?>
	<div id="r_Nom_Afectado" class="form-group">
		<label id="elh_view1_acc_Nom_Afectado" for="x_Nom_Afectado" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Nom_Afectado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Nom_Afectado->CellAttributes() ?>>
<span id="el_view1_acc_Nom_Afectado">
<textarea data-field="x_Nom_Afectado" name="x_Nom_Afectado" id="x_Nom_Afectado" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->Nom_Afectado->PlaceHolder) ?>"<?php echo $view1_acc->Nom_Afectado->EditAttributes() ?>><?php echo $view1_acc->Nom_Afectado->EditValue ?></textarea>
</span>
<?php echo $view1_acc->Nom_Afectado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->CC_Afectado->Visible) { // CC_Afectado ?>
	<div id="r_CC_Afectado" class="form-group">
		<label id="elh_view1_acc_CC_Afectado" for="x_CC_Afectado" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->CC_Afectado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->CC_Afectado->CellAttributes() ?>>
<span id="el_view1_acc_CC_Afectado">
<input type="text" data-field="x_CC_Afectado" name="x_CC_Afectado" id="x_CC_Afectado" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->CC_Afectado->PlaceHolder) ?>" value="<?php echo $view1_acc->CC_Afectado->EditValue ?>"<?php echo $view1_acc->CC_Afectado->EditAttributes() ?>>
</span>
<?php echo $view1_acc->CC_Afectado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Cargo_Afectado->Visible) { // Cargo_Afectado ?>
	<div id="r_Cargo_Afectado" class="form-group">
		<label id="elh_view1_acc_Cargo_Afectado" for="x_Cargo_Afectado" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Cargo_Afectado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Cargo_Afectado->CellAttributes() ?>>
<span id="el_view1_acc_Cargo_Afectado">
<textarea data-field="x_Cargo_Afectado" name="x_Cargo_Afectado" id="x_Cargo_Afectado" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->Cargo_Afectado->PlaceHolder) ?>"<?php echo $view1_acc->Cargo_Afectado->EditAttributes() ?>><?php echo $view1_acc->Cargo_Afectado->EditValue ?></textarea>
</span>
<?php echo $view1_acc->Cargo_Afectado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Tipo_incidente->Visible) { // Tipo_incidente ?>
	<div id="r_Tipo_incidente" class="form-group">
		<label id="elh_view1_acc_Tipo_incidente" for="x_Tipo_incidente" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Tipo_incidente->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Tipo_incidente->CellAttributes() ?>>
<span id="el_view1_acc_Tipo_incidente">
<input type="text" data-field="x_Tipo_incidente" name="x_Tipo_incidente" id="x_Tipo_incidente" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view1_acc->Tipo_incidente->PlaceHolder) ?>" value="<?php echo $view1_acc->Tipo_incidente->EditValue ?>"<?php echo $view1_acc->Tipo_incidente->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Tipo_incidente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Parte_Cuerpo->Visible) { // Parte_Cuerpo ?>
	<div id="r_Parte_Cuerpo" class="form-group">
		<label id="elh_view1_acc_Parte_Cuerpo" for="x_Parte_Cuerpo" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Parte_Cuerpo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Parte_Cuerpo->CellAttributes() ?>>
<span id="el_view1_acc_Parte_Cuerpo">
<textarea data-field="x_Parte_Cuerpo" name="x_Parte_Cuerpo" id="x_Parte_Cuerpo" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->Parte_Cuerpo->PlaceHolder) ?>"<?php echo $view1_acc->Parte_Cuerpo->EditAttributes() ?>><?php echo $view1_acc->Parte_Cuerpo->EditValue ?></textarea>
</span>
<?php echo $view1_acc->Parte_Cuerpo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->ESTADO_AFEC->Visible) { // ESTADO_AFEC ?>
	<div id="r_ESTADO_AFEC" class="form-group">
		<label id="elh_view1_acc_ESTADO_AFEC" for="x_ESTADO_AFEC" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->ESTADO_AFEC->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->ESTADO_AFEC->CellAttributes() ?>>
<span id="el_view1_acc_ESTADO_AFEC">
<textarea data-field="x_ESTADO_AFEC" name="x_ESTADO_AFEC" id="x_ESTADO_AFEC" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->ESTADO_AFEC->PlaceHolder) ?>"<?php echo $view1_acc->ESTADO_AFEC->EditAttributes() ?>><?php echo $view1_acc->ESTADO_AFEC->EditValue ?></textarea>
</span>
<?php echo $view1_acc->ESTADO_AFEC->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->EVACUADO->Visible) { // EVACUADO ?>
	<div id="r_EVACUADO" class="form-group">
		<label id="elh_view1_acc_EVACUADO" for="x_EVACUADO" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->EVACUADO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->EVACUADO->CellAttributes() ?>>
<span id="el_view1_acc_EVACUADO">
<textarea data-field="x_EVACUADO" name="x_EVACUADO" id="x_EVACUADO" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->EVACUADO->PlaceHolder) ?>"<?php echo $view1_acc->EVACUADO->EditAttributes() ?>><?php echo $view1_acc->EVACUADO->EditValue ?></textarea>
</span>
<?php echo $view1_acc->EVACUADO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->DESC_ACC->Visible) { // DESC_ACC ?>
	<div id="r_DESC_ACC" class="form-group">
		<label id="elh_view1_acc_DESC_ACC" for="x_DESC_ACC" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->DESC_ACC->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->DESC_ACC->CellAttributes() ?>>
<span id="el_view1_acc_DESC_ACC">
<textarea data-field="x_DESC_ACC" name="x_DESC_ACC" id="x_DESC_ACC" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view1_acc->DESC_ACC->PlaceHolder) ?>"<?php echo $view1_acc->DESC_ACC->EditAttributes() ?>><?php echo $view1_acc->DESC_ACC->EditValue ?></textarea>
</span>
<?php echo $view1_acc->DESC_ACC->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view1_acc->Modificado->Visible) { // Modificado ?>
	<div id="r_Modificado" class="form-group">
		<label id="elh_view1_acc_Modificado" for="x_Modificado" class="col-sm-2 control-label ewLabel"><?php echo $view1_acc->Modificado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $view1_acc->Modificado->CellAttributes() ?>>
<span id="el_view1_acc_Modificado">
<input type="text" data-field="x_Modificado" name="x_Modificado" id="x_Modificado" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view1_acc->Modificado->PlaceHolder) ?>" value="<?php echo $view1_acc->Modificado->EditValue ?>"<?php echo $view1_acc->Modificado->EditAttributes() ?>>
</span>
<?php echo $view1_acc->Modificado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_llave_2" name="x_llave_2" id="x_llave_2" value="<?php echo ew_HtmlEncode($view1_acc->llave_2->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fview1_accedit.Init();
</script>
<?php
$view1_acc_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view1_acc_edit->Page_Terminate();
?>
