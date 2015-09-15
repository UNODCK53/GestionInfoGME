<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view_e_y_ninfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view_e_y_n_edit = NULL; // Initialize page object first

class cview_e_y_n_edit extends cview_e_y_n {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_e_y_n';

	// Page object name
	var $PageObjName = 'view_e_y_n_edit';

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

		// Table object (view_e_y_n)
		if (!isset($GLOBALS["view_e_y_n"]) || get_class($GLOBALS["view_e_y_n"]) == "cview_e_y_n") {
			$GLOBALS["view_e_y_n"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_e_y_n"];
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
			define("EW_TABLE_NAME", 'view_e_y_n', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view_e_y_nlist.php"));
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
		global $EW_EXPORT, $view_e_y_n;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_e_y_n);
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
			$this->Page_Terminate("view_e_y_nlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("view_e_y_nlist.php"); // No matching record, return to list
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
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render as View
		} else {
			$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		}
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
		if (!$this->ID_Formulario->FldIsDetailKey) {
			$this->ID_Formulario->setFormValue($objForm->GetValue("x_ID_Formulario"));
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
		if (!$this->NOM_GE->FldIsDetailKey) {
			$this->NOM_GE->setFormValue($objForm->GetValue("x_NOM_GE"));
		}
		if (!$this->Otro_PGE->FldIsDetailKey) {
			$this->Otro_PGE->setFormValue($objForm->GetValue("x_Otro_PGE"));
		}
		if (!$this->Otro_CC_PGE->FldIsDetailKey) {
			$this->Otro_CC_PGE->setFormValue($objForm->GetValue("x_Otro_CC_PGE"));
		}
		if (!$this->TIPO_INFORME->FldIsDetailKey) {
			$this->TIPO_INFORME->setFormValue($objForm->GetValue("x_TIPO_INFORME"));
		}
		if (!$this->FECHA_NOVEDAD->FldIsDetailKey) {
			$this->FECHA_NOVEDAD->setFormValue($objForm->GetValue("x_FECHA_NOVEDAD"));
		}
		if (!$this->DIA->FldIsDetailKey) {
			$this->DIA->setFormValue($objForm->GetValue("x_DIA"));
		}
		if (!$this->MES->FldIsDetailKey) {
			$this->MES->setFormValue($objForm->GetValue("x_MES"));
		}
		if (!$this->Num_Evacua->FldIsDetailKey) {
			$this->Num_Evacua->setFormValue($objForm->GetValue("x_Num_Evacua"));
		}
		if (!$this->PTO_INCOMU->FldIsDetailKey) {
			$this->PTO_INCOMU->setFormValue($objForm->GetValue("x_PTO_INCOMU"));
		}
		if (!$this->OBS_punt_inco->FldIsDetailKey) {
			$this->OBS_punt_inco->setFormValue($objForm->GetValue("x_OBS_punt_inco"));
		}
		if (!$this->OBS_ENLACE->FldIsDetailKey) {
			$this->OBS_ENLACE->setFormValue($objForm->GetValue("x_OBS_ENLACE"));
		}
		if (!$this->NUM_Novedad->FldIsDetailKey) {
			$this->NUM_Novedad->setFormValue($objForm->GetValue("x_NUM_Novedad"));
		}
		if (!$this->Nom_Per_Evacu->FldIsDetailKey) {
			$this->Nom_Per_Evacu->setFormValue($objForm->GetValue("x_Nom_Per_Evacu"));
		}
		if (!$this->Nom_Otro_Per_Evacu->FldIsDetailKey) {
			$this->Nom_Otro_Per_Evacu->setFormValue($objForm->GetValue("x_Nom_Otro_Per_Evacu"));
		}
		if (!$this->CC_Otro_Per_Evacu->FldIsDetailKey) {
			$this->CC_Otro_Per_Evacu->setFormValue($objForm->GetValue("x_CC_Otro_Per_Evacu"));
		}
		if (!$this->Cargo_Per_EVA->FldIsDetailKey) {
			$this->Cargo_Per_EVA->setFormValue($objForm->GetValue("x_Cargo_Per_EVA"));
		}
		if (!$this->Motivo_Eva->FldIsDetailKey) {
			$this->Motivo_Eva->setFormValue($objForm->GetValue("x_Motivo_Eva"));
		}
		if (!$this->OBS_EVA->FldIsDetailKey) {
			$this->OBS_EVA->setFormValue($objForm->GetValue("x_OBS_EVA"));
		}
		if (!$this->NOM_PE->FldIsDetailKey) {
			$this->NOM_PE->setFormValue($objForm->GetValue("x_NOM_PE"));
		}
		if (!$this->Otro_Nom_PE->FldIsDetailKey) {
			$this->Otro_Nom_PE->setFormValue($objForm->GetValue("x_Otro_Nom_PE"));
		}
		if (!$this->NOM_CAPATAZ->FldIsDetailKey) {
			$this->NOM_CAPATAZ->setFormValue($objForm->GetValue("x_NOM_CAPATAZ"));
		}
		if (!$this->Otro_Nom_Capata->FldIsDetailKey) {
			$this->Otro_Nom_Capata->setFormValue($objForm->GetValue("x_Otro_Nom_Capata"));
		}
		if (!$this->Otro_CC_Capata->FldIsDetailKey) {
			$this->Otro_CC_Capata->setFormValue($objForm->GetValue("x_Otro_CC_Capata"));
		}
		if (!$this->Muncipio->FldIsDetailKey) {
			$this->Muncipio->setFormValue($objForm->GetValue("x_Muncipio"));
		}
		if (!$this->F_llegada->FldIsDetailKey) {
			$this->F_llegada->setFormValue($objForm->GetValue("x_F_llegada"));
		}
		if (!$this->Fecha->FldIsDetailKey) {
			$this->Fecha->setFormValue($objForm->GetValue("x_Fecha"));
		}
		if (!$this->Modificado->FldIsDetailKey) {
			$this->Modificado->setFormValue($objForm->GetValue("x_Modificado"));
		}
		if (!$this->departamento->FldIsDetailKey) {
			$this->departamento->setFormValue($objForm->GetValue("x_departamento"));
		}
		if (!$this->llave_2->FldIsDetailKey)
			$this->llave_2->setFormValue($objForm->GetValue("x_llave_2"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->llave_2->CurrentValue = $this->llave_2->FormValue;
		$this->ID_Formulario->CurrentValue = $this->ID_Formulario->FormValue;
		$this->F_Sincron->CurrentValue = $this->F_Sincron->FormValue;
		$this->F_Sincron->CurrentValue = ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 7);
		$this->USUARIO->CurrentValue = $this->USUARIO->FormValue;
		$this->Cargo_gme->CurrentValue = $this->Cargo_gme->FormValue;
		$this->NOM_GE->CurrentValue = $this->NOM_GE->FormValue;
		$this->Otro_PGE->CurrentValue = $this->Otro_PGE->FormValue;
		$this->Otro_CC_PGE->CurrentValue = $this->Otro_CC_PGE->FormValue;
		$this->TIPO_INFORME->CurrentValue = $this->TIPO_INFORME->FormValue;
		$this->FECHA_NOVEDAD->CurrentValue = $this->FECHA_NOVEDAD->FormValue;
		$this->DIA->CurrentValue = $this->DIA->FormValue;
		$this->MES->CurrentValue = $this->MES->FormValue;
		$this->Num_Evacua->CurrentValue = $this->Num_Evacua->FormValue;
		$this->PTO_INCOMU->CurrentValue = $this->PTO_INCOMU->FormValue;
		$this->OBS_punt_inco->CurrentValue = $this->OBS_punt_inco->FormValue;
		$this->OBS_ENLACE->CurrentValue = $this->OBS_ENLACE->FormValue;
		$this->NUM_Novedad->CurrentValue = $this->NUM_Novedad->FormValue;
		$this->Nom_Per_Evacu->CurrentValue = $this->Nom_Per_Evacu->FormValue;
		$this->Nom_Otro_Per_Evacu->CurrentValue = $this->Nom_Otro_Per_Evacu->FormValue;
		$this->CC_Otro_Per_Evacu->CurrentValue = $this->CC_Otro_Per_Evacu->FormValue;
		$this->Cargo_Per_EVA->CurrentValue = $this->Cargo_Per_EVA->FormValue;
		$this->Motivo_Eva->CurrentValue = $this->Motivo_Eva->FormValue;
		$this->OBS_EVA->CurrentValue = $this->OBS_EVA->FormValue;
		$this->NOM_PE->CurrentValue = $this->NOM_PE->FormValue;
		$this->Otro_Nom_PE->CurrentValue = $this->Otro_Nom_PE->FormValue;
		$this->NOM_CAPATAZ->CurrentValue = $this->NOM_CAPATAZ->FormValue;
		$this->Otro_Nom_Capata->CurrentValue = $this->Otro_Nom_Capata->FormValue;
		$this->Otro_CC_Capata->CurrentValue = $this->Otro_CC_Capata->FormValue;
		$this->Muncipio->CurrentValue = $this->Muncipio->FormValue;
		$this->F_llegada->CurrentValue = $this->F_llegada->FormValue;
		$this->Fecha->CurrentValue = $this->Fecha->FormValue;
		$this->Modificado->CurrentValue = $this->Modificado->FormValue;
		$this->departamento->CurrentValue = $this->departamento->FormValue;
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
		$this->F_llegada->setDbValue($rs->fields('F_llegada'));
		$this->Fecha->setDbValue($rs->fields('Fecha'));
		$this->Modificado->setDbValue($rs->fields('Modificado'));
		$this->llave_2->setDbValue($rs->fields('llave_2'));
		$this->departamento->setDbValue($rs->fields('departamento'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID_Formulario->DbValue = $row['ID_Formulario'];
		$this->F_Sincron->DbValue = $row['F_Sincron'];
		$this->USUARIO->DbValue = $row['USUARIO'];
		$this->Cargo_gme->DbValue = $row['Cargo_gme'];
		$this->NOM_GE->DbValue = $row['NOM_GE'];
		$this->Otro_PGE->DbValue = $row['Otro_PGE'];
		$this->Otro_CC_PGE->DbValue = $row['Otro_CC_PGE'];
		$this->TIPO_INFORME->DbValue = $row['TIPO_INFORME'];
		$this->FECHA_NOVEDAD->DbValue = $row['FECHA_NOVEDAD'];
		$this->DIA->DbValue = $row['DIA'];
		$this->MES->DbValue = $row['MES'];
		$this->Num_Evacua->DbValue = $row['Num_Evacua'];
		$this->PTO_INCOMU->DbValue = $row['PTO_INCOMU'];
		$this->OBS_punt_inco->DbValue = $row['OBS_punt_inco'];
		$this->OBS_ENLACE->DbValue = $row['OBS_ENLACE'];
		$this->NUM_Novedad->DbValue = $row['NUM_Novedad'];
		$this->Nom_Per_Evacu->DbValue = $row['Nom_Per_Evacu'];
		$this->Nom_Otro_Per_Evacu->DbValue = $row['Nom_Otro_Per_Evacu'];
		$this->CC_Otro_Per_Evacu->DbValue = $row['CC_Otro_Per_Evacu'];
		$this->Cargo_Per_EVA->DbValue = $row['Cargo_Per_EVA'];
		$this->Motivo_Eva->DbValue = $row['Motivo_Eva'];
		$this->OBS_EVA->DbValue = $row['OBS_EVA'];
		$this->NOM_PE->DbValue = $row['NOM_PE'];
		$this->Otro_Nom_PE->DbValue = $row['Otro_Nom_PE'];
		$this->NOM_CAPATAZ->DbValue = $row['NOM_CAPATAZ'];
		$this->Otro_Nom_Capata->DbValue = $row['Otro_Nom_Capata'];
		$this->Otro_CC_Capata->DbValue = $row['Otro_CC_Capata'];
		$this->Muncipio->DbValue = $row['Muncipio'];
		$this->F_llegada->DbValue = $row['F_llegada'];
		$this->Fecha->DbValue = $row['Fecha'];
		$this->Modificado->DbValue = $row['Modificado'];
		$this->llave_2->DbValue = $row['llave_2'];
		$this->departamento->DbValue = $row['departamento'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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
		// F_llegada
		// Fecha
		// Modificado
		// llave_2
		// departamento

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// ID_Formulario
			$this->ID_Formulario->ViewValue = $this->ID_Formulario->CurrentValue;
			$this->ID_Formulario->ViewCustomAttributes = "";

			// F_Sincron
			$this->F_Sincron->ViewValue = $this->F_Sincron->CurrentValue;
			$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 7);
			$this->F_Sincron->ViewCustomAttributes = "";

			// USUARIO
			if (strval($this->USUARIO->CurrentValue) <> "") {
				$sFilterWrk = "`USUARIO`" . ew_SearchString("=", $this->USUARIO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->USUARIO, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `USUARIO` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->USUARIO->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->USUARIO->ViewValue = $this->USUARIO->CurrentValue;
				}
			} else {
				$this->USUARIO->ViewValue = NULL;
			}
			$this->USUARIO->ViewCustomAttributes = "";

			// Cargo_gme
			$this->Cargo_gme->ViewValue = $this->Cargo_gme->CurrentValue;
			$this->Cargo_gme->ViewCustomAttributes = "";

			// NOM_GE
			if (strval($this->NOM_GE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_GE`" . ew_SearchString("=", $this->NOM_GE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_GE`, `NOM_GE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_GE`, `NOM_GE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_GE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_GE` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->NOM_GE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->NOM_GE->ViewValue = $this->NOM_GE->CurrentValue;
				}
			} else {
				$this->NOM_GE->ViewValue = NULL;
			}
			$this->NOM_GE->ViewCustomAttributes = "";

			// Otro_PGE
			$this->Otro_PGE->ViewValue = $this->Otro_PGE->CurrentValue;
			$this->Otro_PGE->ViewCustomAttributes = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
			$this->Otro_CC_PGE->ViewCustomAttributes = "";

			// TIPO_INFORME
			if (strval($this->TIPO_INFORME->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->TIPO_INFORME->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dominio`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dominio`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`list name`='informe'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TIPO_INFORME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->TIPO_INFORME->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->TIPO_INFORME->ViewValue = $this->TIPO_INFORME->CurrentValue;
				}
			} else {
				$this->TIPO_INFORME->ViewValue = NULL;
			}
			$this->TIPO_INFORME->ViewCustomAttributes = "";

			// FECHA_NOVEDAD
			$this->FECHA_NOVEDAD->ViewValue = $this->FECHA_NOVEDAD->CurrentValue;
			$this->FECHA_NOVEDAD->ViewValue = ew_FormatDateTime($this->FECHA_NOVEDAD->ViewValue, 5);
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
			if (strval($this->PTO_INCOMU->CurrentValue) <> "") {
				switch ($this->PTO_INCOMU->CurrentValue) {
					case $this->PTO_INCOMU->FldTagValue(1):
						$this->PTO_INCOMU->ViewValue = $this->PTO_INCOMU->FldTagCaption(1) <> "" ? $this->PTO_INCOMU->FldTagCaption(1) : $this->PTO_INCOMU->CurrentValue;
						break;
					case $this->PTO_INCOMU->FldTagValue(2):
						$this->PTO_INCOMU->ViewValue = $this->PTO_INCOMU->FldTagCaption(2) <> "" ? $this->PTO_INCOMU->FldTagCaption(2) : $this->PTO_INCOMU->CurrentValue;
						break;
					default:
						$this->PTO_INCOMU->ViewValue = $this->PTO_INCOMU->CurrentValue;
				}
			} else {
				$this->PTO_INCOMU->ViewValue = NULL;
			}
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
			if (strval($this->Cargo_Per_EVA->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->Cargo_Per_EVA->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dominio`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dominio`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`list name`='cargo'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Cargo_Per_EVA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Cargo_Per_EVA->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Cargo_Per_EVA->ViewValue = $this->Cargo_Per_EVA->CurrentValue;
				}
			} else {
				$this->Cargo_Per_EVA->ViewValue = NULL;
			}
			$this->Cargo_Per_EVA->ViewCustomAttributes = "";

			// Motivo_Eva
			if (strval($this->Motivo_Eva->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->Motivo_Eva->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dominio`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `dominio`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`list name`='motivo'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Motivo_Eva, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
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

			// OBS_EVA
			$this->OBS_EVA->ViewValue = $this->OBS_EVA->CurrentValue;
			$this->OBS_EVA->ViewCustomAttributes = "";

			// NOM_PE
			if (strval($this->NOM_PE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PE`" . ew_SearchString("=", $this->NOM_PE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_PE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_PE` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->NOM_PE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
				}
			} else {
				$this->NOM_PE->ViewValue = NULL;
			}
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

			// F_llegada
			if (strval($this->F_llegada->CurrentValue) <> "") {
				switch ($this->F_llegada->CurrentValue) {
					case $this->F_llegada->FldTagValue(1):
						$this->F_llegada->ViewValue = $this->F_llegada->FldTagCaption(1) <> "" ? $this->F_llegada->FldTagCaption(1) : $this->F_llegada->CurrentValue;
						break;
					case $this->F_llegada->FldTagValue(2):
						$this->F_llegada->ViewValue = $this->F_llegada->FldTagCaption(2) <> "" ? $this->F_llegada->FldTagCaption(2) : $this->F_llegada->CurrentValue;
						break;
					default:
						$this->F_llegada->ViewValue = $this->F_llegada->CurrentValue;
				}
			} else {
				$this->F_llegada->ViewValue = NULL;
			}
			$this->F_llegada->ViewCustomAttributes = "";

			// Fecha
			$this->Fecha->ViewValue = $this->Fecha->CurrentValue;
			$this->Fecha->ViewCustomAttributes = "";

			// Modificado
			if (strval($this->Modificado->CurrentValue) <> "") {
				switch ($this->Modificado->CurrentValue) {
					case $this->Modificado->FldTagValue(1):
						$this->Modificado->ViewValue = $this->Modificado->FldTagCaption(1) <> "" ? $this->Modificado->FldTagCaption(1) : $this->Modificado->CurrentValue;
						break;
					case $this->Modificado->FldTagValue(2):
						$this->Modificado->ViewValue = $this->Modificado->FldTagCaption(2) <> "" ? $this->Modificado->FldTagCaption(2) : $this->Modificado->CurrentValue;
						break;
					default:
						$this->Modificado->ViewValue = $this->Modificado->CurrentValue;
				}
			} else {
				$this->Modificado->ViewValue = NULL;
			}
			$this->Modificado->ViewCustomAttributes = "";

			// departamento
			$this->departamento->ViewValue = $this->departamento->CurrentValue;
			$this->departamento->ViewCustomAttributes = "";

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

			// departamento
			$this->departamento->LinkCustomAttributes = "";
			$this->departamento->HrefValue = "";
			$this->departamento->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			if (strval($this->USUARIO->CurrentValue) <> "") {
				$sFilterWrk = "`USUARIO`" . ew_SearchString("=", $this->USUARIO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->USUARIO, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `USUARIO` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->USUARIO->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->USUARIO->EditValue = $this->USUARIO->CurrentValue;
				}
			} else {
				$this->USUARIO->EditValue = NULL;
			}
			$this->USUARIO->ViewCustomAttributes = "";

			// Cargo_gme
			$this->Cargo_gme->EditAttrs["class"] = "form-control";
			$this->Cargo_gme->EditCustomAttributes = "";
			$this->Cargo_gme->EditValue = $this->Cargo_gme->CurrentValue;
			$this->Cargo_gme->ViewCustomAttributes = "";

			// NOM_GE
			$this->NOM_GE->EditAttrs["class"] = "form-control";
			$this->NOM_GE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_GE`, `NOM_GE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_GE`, `NOM_GE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_GE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_GE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->NOM_GE->EditValue = $arwrk;

			// Otro_PGE
			$this->Otro_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_PGE->EditCustomAttributes = "";
			$this->Otro_PGE->EditValue = ew_HtmlEncode($this->Otro_PGE->CurrentValue);
			$this->Otro_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_PGE->FldCaption());

			// Otro_CC_PGE
			$this->Otro_CC_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_CC_PGE->EditCustomAttributes = "";
			$this->Otro_CC_PGE->EditValue = ew_HtmlEncode($this->Otro_CC_PGE->CurrentValue);
			$this->Otro_CC_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_CC_PGE->FldCaption());

			// TIPO_INFORME
			$this->TIPO_INFORME->EditAttrs["class"] = "form-control";
			$this->TIPO_INFORME->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`list name`='informe'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TIPO_INFORME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->TIPO_INFORME->EditValue = $arwrk;

			// FECHA_NOVEDAD
			$this->FECHA_NOVEDAD->EditAttrs["class"] = "form-control";
			$this->FECHA_NOVEDAD->EditCustomAttributes = "";
			$this->FECHA_NOVEDAD->EditValue = ew_HtmlEncode($this->FECHA_NOVEDAD->CurrentValue);
			$this->FECHA_NOVEDAD->PlaceHolder = ew_RemoveHtml($this->FECHA_NOVEDAD->FldCaption());

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

			// Num_Evacua
			$this->Num_Evacua->EditAttrs["class"] = "form-control";
			$this->Num_Evacua->EditCustomAttributes = "";
			$this->Num_Evacua->EditValue = ew_HtmlEncode($this->Num_Evacua->CurrentValue);
			$this->Num_Evacua->PlaceHolder = ew_RemoveHtml($this->Num_Evacua->FldCaption());

			// PTO_INCOMU
			$this->PTO_INCOMU->EditAttrs["class"] = "form-control";
			$this->PTO_INCOMU->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->PTO_INCOMU->FldTagValue(1), $this->PTO_INCOMU->FldTagCaption(1) <> "" ? $this->PTO_INCOMU->FldTagCaption(1) : $this->PTO_INCOMU->FldTagValue(1));
			$arwrk[] = array($this->PTO_INCOMU->FldTagValue(2), $this->PTO_INCOMU->FldTagCaption(2) <> "" ? $this->PTO_INCOMU->FldTagCaption(2) : $this->PTO_INCOMU->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->PTO_INCOMU->EditValue = $arwrk;

			// OBS_punt_inco
			$this->OBS_punt_inco->EditAttrs["class"] = "form-control";
			$this->OBS_punt_inco->EditCustomAttributes = "";
			$this->OBS_punt_inco->EditValue = ew_HtmlEncode($this->OBS_punt_inco->CurrentValue);
			$this->OBS_punt_inco->PlaceHolder = ew_RemoveHtml($this->OBS_punt_inco->FldCaption());

			// OBS_ENLACE
			$this->OBS_ENLACE->EditAttrs["class"] = "form-control";
			$this->OBS_ENLACE->EditCustomAttributes = "";
			$this->OBS_ENLACE->EditValue = ew_HtmlEncode($this->OBS_ENLACE->CurrentValue);
			$this->OBS_ENLACE->PlaceHolder = ew_RemoveHtml($this->OBS_ENLACE->FldCaption());

			// NUM_Novedad
			$this->NUM_Novedad->EditAttrs["class"] = "form-control";
			$this->NUM_Novedad->EditCustomAttributes = "";
			$this->NUM_Novedad->EditValue = ew_HtmlEncode($this->NUM_Novedad->CurrentValue);
			$this->NUM_Novedad->PlaceHolder = ew_RemoveHtml($this->NUM_Novedad->FldCaption());

			// Nom_Per_Evacu
			$this->Nom_Per_Evacu->EditAttrs["class"] = "form-control";
			$this->Nom_Per_Evacu->EditCustomAttributes = "";
			$this->Nom_Per_Evacu->EditValue = ew_HtmlEncode($this->Nom_Per_Evacu->CurrentValue);
			$this->Nom_Per_Evacu->PlaceHolder = ew_RemoveHtml($this->Nom_Per_Evacu->FldCaption());

			// Nom_Otro_Per_Evacu
			$this->Nom_Otro_Per_Evacu->EditAttrs["class"] = "form-control";
			$this->Nom_Otro_Per_Evacu->EditCustomAttributes = "";
			$this->Nom_Otro_Per_Evacu->EditValue = ew_HtmlEncode($this->Nom_Otro_Per_Evacu->CurrentValue);
			$this->Nom_Otro_Per_Evacu->PlaceHolder = ew_RemoveHtml($this->Nom_Otro_Per_Evacu->FldCaption());

			// CC_Otro_Per_Evacu
			$this->CC_Otro_Per_Evacu->EditAttrs["class"] = "form-control";
			$this->CC_Otro_Per_Evacu->EditCustomAttributes = "";
			$this->CC_Otro_Per_Evacu->EditValue = ew_HtmlEncode($this->CC_Otro_Per_Evacu->CurrentValue);
			$this->CC_Otro_Per_Evacu->PlaceHolder = ew_RemoveHtml($this->CC_Otro_Per_Evacu->FldCaption());

			// Cargo_Per_EVA
			$this->Cargo_Per_EVA->EditAttrs["class"] = "form-control";
			$this->Cargo_Per_EVA->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`list name`='cargo'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Cargo_Per_EVA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Cargo_Per_EVA->EditValue = $arwrk;

			// Motivo_Eva
			$this->Motivo_Eva->EditAttrs["class"] = "form-control";
			$this->Motivo_Eva->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
					$sWhereWrk = "";
					break;
			}
			$lookuptblfilter = "`list name`='motivo'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Motivo_Eva, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Motivo_Eva->EditValue = $arwrk;

			// OBS_EVA
			$this->OBS_EVA->EditAttrs["class"] = "form-control";
			$this->OBS_EVA->EditCustomAttributes = "";
			$this->OBS_EVA->EditValue = ew_HtmlEncode($this->OBS_EVA->CurrentValue);
			$this->OBS_EVA->PlaceHolder = ew_RemoveHtml($this->OBS_EVA->FldCaption());

			// NOM_PE
			$this->NOM_PE->EditAttrs["class"] = "form-control";
			$this->NOM_PE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_e_y_n`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_PE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_PE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->NOM_PE->EditValue = $arwrk;

			// Otro_Nom_PE
			$this->Otro_Nom_PE->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_PE->EditCustomAttributes = "";
			$this->Otro_Nom_PE->EditValue = ew_HtmlEncode($this->Otro_Nom_PE->CurrentValue);
			$this->Otro_Nom_PE->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_PE->FldCaption());

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->EditAttrs["class"] = "form-control";
			$this->NOM_CAPATAZ->EditCustomAttributes = "";
			$this->NOM_CAPATAZ->EditValue = ew_HtmlEncode($this->NOM_CAPATAZ->CurrentValue);
			$this->NOM_CAPATAZ->PlaceHolder = ew_RemoveHtml($this->NOM_CAPATAZ->FldCaption());

			// Otro_Nom_Capata
			$this->Otro_Nom_Capata->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_Capata->EditCustomAttributes = "";
			$this->Otro_Nom_Capata->EditValue = ew_HtmlEncode($this->Otro_Nom_Capata->CurrentValue);
			$this->Otro_Nom_Capata->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Capata->FldCaption());

			// Otro_CC_Capata
			$this->Otro_CC_Capata->EditAttrs["class"] = "form-control";
			$this->Otro_CC_Capata->EditCustomAttributes = "";
			$this->Otro_CC_Capata->EditValue = ew_HtmlEncode($this->Otro_CC_Capata->CurrentValue);
			$this->Otro_CC_Capata->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Capata->FldCaption());

			// Muncipio
			$this->Muncipio->EditAttrs["class"] = "form-control";
			$this->Muncipio->EditCustomAttributes = "";
			$this->Muncipio->EditValue = ew_HtmlEncode($this->Muncipio->CurrentValue);
			$this->Muncipio->PlaceHolder = ew_RemoveHtml($this->Muncipio->FldCaption());

			// F_llegada
			$this->F_llegada->EditAttrs["class"] = "form-control";
			$this->F_llegada->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->F_llegada->FldTagValue(1), $this->F_llegada->FldTagCaption(1) <> "" ? $this->F_llegada->FldTagCaption(1) : $this->F_llegada->FldTagValue(1));
			$arwrk[] = array($this->F_llegada->FldTagValue(2), $this->F_llegada->FldTagCaption(2) <> "" ? $this->F_llegada->FldTagCaption(2) : $this->F_llegada->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->F_llegada->EditValue = $arwrk;

			// Fecha
			$this->Fecha->EditAttrs["class"] = "form-control";
			$this->Fecha->EditCustomAttributes = "";
			$this->Fecha->EditValue = ew_HtmlEncode($this->Fecha->CurrentValue);
			$this->Fecha->PlaceHolder = ew_RemoveHtml($this->Fecha->FldCaption());

			// Modificado
			$this->Modificado->EditAttrs["class"] = "form-control";
			$this->Modificado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Modificado->FldTagValue(1), $this->Modificado->FldTagCaption(1) <> "" ? $this->Modificado->FldTagCaption(1) : $this->Modificado->FldTagValue(1));
			$arwrk[] = array($this->Modificado->FldTagValue(2), $this->Modificado->FldTagCaption(2) <> "" ? $this->Modificado->FldTagCaption(2) : $this->Modificado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Modificado->EditValue = $arwrk;

			// departamento
			$this->departamento->EditAttrs["class"] = "form-control";
			$this->departamento->EditCustomAttributes = "";
			$this->departamento->EditValue = ew_HtmlEncode($this->departamento->CurrentValue);
			$this->departamento->PlaceHolder = ew_RemoveHtml($this->departamento->FldCaption());

			// Edit refer script
			// ID_Formulario

			$this->ID_Formulario->HrefValue = "";

			// F_Sincron
			$this->F_Sincron->HrefValue = "";

			// USUARIO
			$this->USUARIO->HrefValue = "";

			// Cargo_gme
			$this->Cargo_gme->HrefValue = "";

			// NOM_GE
			$this->NOM_GE->HrefValue = "";

			// Otro_PGE
			$this->Otro_PGE->HrefValue = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->HrefValue = "";

			// TIPO_INFORME
			$this->TIPO_INFORME->HrefValue = "";

			// FECHA_NOVEDAD
			$this->FECHA_NOVEDAD->HrefValue = "";

			// DIA
			$this->DIA->HrefValue = "";

			// MES
			$this->MES->HrefValue = "";

			// Num_Evacua
			$this->Num_Evacua->HrefValue = "";

			// PTO_INCOMU
			$this->PTO_INCOMU->HrefValue = "";

			// OBS_punt_inco
			$this->OBS_punt_inco->HrefValue = "";

			// OBS_ENLACE
			$this->OBS_ENLACE->HrefValue = "";

			// NUM_Novedad
			$this->NUM_Novedad->HrefValue = "";

			// Nom_Per_Evacu
			$this->Nom_Per_Evacu->HrefValue = "";

			// Nom_Otro_Per_Evacu
			$this->Nom_Otro_Per_Evacu->HrefValue = "";

			// CC_Otro_Per_Evacu
			$this->CC_Otro_Per_Evacu->HrefValue = "";

			// Cargo_Per_EVA
			$this->Cargo_Per_EVA->HrefValue = "";

			// Motivo_Eva
			$this->Motivo_Eva->HrefValue = "";

			// OBS_EVA
			$this->OBS_EVA->HrefValue = "";

			// NOM_PE
			$this->NOM_PE->HrefValue = "";

			// Otro_Nom_PE
			$this->Otro_Nom_PE->HrefValue = "";

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->HrefValue = "";

			// Otro_Nom_Capata
			$this->Otro_Nom_Capata->HrefValue = "";

			// Otro_CC_Capata
			$this->Otro_CC_Capata->HrefValue = "";

			// Muncipio
			$this->Muncipio->HrefValue = "";

			// F_llegada
			$this->F_llegada->HrefValue = "";

			// Fecha
			$this->Fecha->HrefValue = "";

			// Modificado
			$this->Modificado->HrefValue = "";

			// departamento
			$this->departamento->HrefValue = "";
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
		if (!ew_CheckInteger($this->Num_Evacua->FormValue)) {
			ew_AddMessage($gsFormError, $this->Num_Evacua->FldErrMsg());
		}
		if (!ew_CheckInteger($this->NUM_Novedad->FormValue)) {
			ew_AddMessage($gsFormError, $this->NUM_Novedad->FldErrMsg());
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

			// NOM_GE
			$this->NOM_GE->SetDbValueDef($rsnew, $this->NOM_GE->CurrentValue, NULL, $this->NOM_GE->ReadOnly);

			// Otro_PGE
			$this->Otro_PGE->SetDbValueDef($rsnew, $this->Otro_PGE->CurrentValue, NULL, $this->Otro_PGE->ReadOnly);

			// Otro_CC_PGE
			$this->Otro_CC_PGE->SetDbValueDef($rsnew, $this->Otro_CC_PGE->CurrentValue, NULL, $this->Otro_CC_PGE->ReadOnly);

			// TIPO_INFORME
			$this->TIPO_INFORME->SetDbValueDef($rsnew, $this->TIPO_INFORME->CurrentValue, NULL, $this->TIPO_INFORME->ReadOnly);

			// FECHA_NOVEDAD
			$this->FECHA_NOVEDAD->SetDbValueDef($rsnew, $this->FECHA_NOVEDAD->CurrentValue, NULL, $this->FECHA_NOVEDAD->ReadOnly);

			// DIA
			$this->DIA->SetDbValueDef($rsnew, $this->DIA->CurrentValue, NULL, $this->DIA->ReadOnly);

			// MES
			$this->MES->SetDbValueDef($rsnew, $this->MES->CurrentValue, NULL, $this->MES->ReadOnly);

			// Num_Evacua
			$this->Num_Evacua->SetDbValueDef($rsnew, $this->Num_Evacua->CurrentValue, NULL, $this->Num_Evacua->ReadOnly);

			// PTO_INCOMU
			$this->PTO_INCOMU->SetDbValueDef($rsnew, $this->PTO_INCOMU->CurrentValue, NULL, $this->PTO_INCOMU->ReadOnly);

			// OBS_punt_inco
			$this->OBS_punt_inco->SetDbValueDef($rsnew, $this->OBS_punt_inco->CurrentValue, NULL, $this->OBS_punt_inco->ReadOnly);

			// OBS_ENLACE
			$this->OBS_ENLACE->SetDbValueDef($rsnew, $this->OBS_ENLACE->CurrentValue, NULL, $this->OBS_ENLACE->ReadOnly);

			// NUM_Novedad
			$this->NUM_Novedad->SetDbValueDef($rsnew, $this->NUM_Novedad->CurrentValue, NULL, $this->NUM_Novedad->ReadOnly);

			// Nom_Per_Evacu
			$this->Nom_Per_Evacu->SetDbValueDef($rsnew, $this->Nom_Per_Evacu->CurrentValue, NULL, $this->Nom_Per_Evacu->ReadOnly);

			// Nom_Otro_Per_Evacu
			$this->Nom_Otro_Per_Evacu->SetDbValueDef($rsnew, $this->Nom_Otro_Per_Evacu->CurrentValue, NULL, $this->Nom_Otro_Per_Evacu->ReadOnly);

			// CC_Otro_Per_Evacu
			$this->CC_Otro_Per_Evacu->SetDbValueDef($rsnew, $this->CC_Otro_Per_Evacu->CurrentValue, NULL, $this->CC_Otro_Per_Evacu->ReadOnly);

			// Cargo_Per_EVA
			$this->Cargo_Per_EVA->SetDbValueDef($rsnew, $this->Cargo_Per_EVA->CurrentValue, NULL, $this->Cargo_Per_EVA->ReadOnly);

			// Motivo_Eva
			$this->Motivo_Eva->SetDbValueDef($rsnew, $this->Motivo_Eva->CurrentValue, NULL, $this->Motivo_Eva->ReadOnly);

			// OBS_EVA
			$this->OBS_EVA->SetDbValueDef($rsnew, $this->OBS_EVA->CurrentValue, NULL, $this->OBS_EVA->ReadOnly);

			// NOM_PE
			$this->NOM_PE->SetDbValueDef($rsnew, $this->NOM_PE->CurrentValue, NULL, $this->NOM_PE->ReadOnly);

			// Otro_Nom_PE
			$this->Otro_Nom_PE->SetDbValueDef($rsnew, $this->Otro_Nom_PE->CurrentValue, NULL, $this->Otro_Nom_PE->ReadOnly);

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->SetDbValueDef($rsnew, $this->NOM_CAPATAZ->CurrentValue, NULL, $this->NOM_CAPATAZ->ReadOnly);

			// Otro_Nom_Capata
			$this->Otro_Nom_Capata->SetDbValueDef($rsnew, $this->Otro_Nom_Capata->CurrentValue, NULL, $this->Otro_Nom_Capata->ReadOnly);

			// Otro_CC_Capata
			$this->Otro_CC_Capata->SetDbValueDef($rsnew, $this->Otro_CC_Capata->CurrentValue, NULL, $this->Otro_CC_Capata->ReadOnly);

			// Muncipio
			$this->Muncipio->SetDbValueDef($rsnew, $this->Muncipio->CurrentValue, NULL, $this->Muncipio->ReadOnly);

			// F_llegada
			$this->F_llegada->SetDbValueDef($rsnew, $this->F_llegada->CurrentValue, NULL, $this->F_llegada->ReadOnly);

			// Fecha
			$this->Fecha->SetDbValueDef($rsnew, $this->Fecha->CurrentValue, NULL, $this->Fecha->ReadOnly);

			// Modificado
			$this->Modificado->SetDbValueDef($rsnew, $this->Modificado->CurrentValue, "", $this->Modificado->ReadOnly);

			// departamento
			$this->departamento->SetDbValueDef($rsnew, $this->departamento->CurrentValue, NULL, $this->departamento->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "view_e_y_nlist.php", "", $this->TableVar, TRUE);
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
if (!isset($view_e_y_n_edit)) $view_e_y_n_edit = new cview_e_y_n_edit();

// Page init
$view_e_y_n_edit->Page_Init();

// Page main
$view_e_y_n_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_e_y_n_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_e_y_n_edit = new ew_Page("view_e_y_n_edit");
view_e_y_n_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = view_e_y_n_edit.PageID; // For backward compatibility

// Form object
var fview_e_y_nedit = new ew_Form("fview_e_y_nedit");

// Validate form
fview_e_y_nedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Num_Evacua");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_e_y_n->Num_Evacua->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_NUM_Novedad");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_e_y_n->NUM_Novedad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Modificado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_e_y_n->Modificado->FldCaption(), $view_e_y_n->Modificado->ReqErrMsg)) ?>");

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
fview_e_y_nedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_e_y_nedit.ValidateRequired = true;
<?php } else { ?>
fview_e_y_nedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_e_y_nedit.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_e_y_nedit.Lists["x_NOM_GE"] = {"LinkField":"x_NOM_GE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_GE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_e_y_nedit.Lists["x_TIPO_INFORME"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_e_y_nedit.Lists["x_Cargo_Per_EVA"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_e_y_nedit.Lists["x_Motivo_Eva"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_e_y_nedit.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $view_e_y_n_edit->ShowPageHeader(); ?>
<?php
$view_e_y_n_edit->ShowMessage();
?>
<form name="fview_e_y_nedit" id="fview_e_y_nedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_e_y_n_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_e_y_n_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_e_y_n">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($view_e_y_n->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<div>
<?php if ($view_e_y_n->ID_Formulario->Visible) { // ID_Formulario ?>
	<div id="r_ID_Formulario" class="form-group">
		<label id="elh_view_e_y_n_ID_Formulario" for="x_ID_Formulario" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->ID_Formulario->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->ID_Formulario->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_ID_Formulario">
<span<?php echo $view_e_y_n->ID_Formulario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->ID_Formulario->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_ID_Formulario" name="x_ID_Formulario" id="x_ID_Formulario" value="<?php echo ew_HtmlEncode($view_e_y_n->ID_Formulario->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_e_y_n_ID_Formulario">
<span<?php echo $view_e_y_n->ID_Formulario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->ID_Formulario->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_ID_Formulario" name="x_ID_Formulario" id="x_ID_Formulario" value="<?php echo ew_HtmlEncode($view_e_y_n->ID_Formulario->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->ID_Formulario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->F_Sincron->Visible) { // F_Sincron ?>
	<div id="r_F_Sincron" class="form-group">
		<label id="elh_view_e_y_n_F_Sincron" for="x_F_Sincron" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->F_Sincron->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->F_Sincron->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_F_Sincron">
<span<?php echo $view_e_y_n->F_Sincron->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->F_Sincron->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" value="<?php echo ew_HtmlEncode($view_e_y_n->F_Sincron->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_e_y_n_F_Sincron">
<span<?php echo $view_e_y_n->F_Sincron->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->F_Sincron->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" value="<?php echo ew_HtmlEncode($view_e_y_n->F_Sincron->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->F_Sincron->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->USUARIO->Visible) { // USUARIO ?>
	<div id="r_USUARIO" class="form-group">
		<label id="elh_view_e_y_n_USUARIO" for="x_USUARIO" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->USUARIO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->USUARIO->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_USUARIO">
<span<?php echo $view_e_y_n->USUARIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->USUARIO->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" value="<?php echo ew_HtmlEncode($view_e_y_n->USUARIO->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_e_y_n_USUARIO">
<span<?php echo $view_e_y_n->USUARIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->USUARIO->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" value="<?php echo ew_HtmlEncode($view_e_y_n->USUARIO->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->USUARIO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Cargo_gme->Visible) { // Cargo_gme ?>
	<div id="r_Cargo_gme" class="form-group">
		<label id="elh_view_e_y_n_Cargo_gme" for="x_Cargo_gme" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Cargo_gme->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Cargo_gme->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Cargo_gme">
<span<?php echo $view_e_y_n->Cargo_gme->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Cargo_gme->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" value="<?php echo ew_HtmlEncode($view_e_y_n->Cargo_gme->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_e_y_n_Cargo_gme">
<span<?php echo $view_e_y_n->Cargo_gme->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Cargo_gme->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" value="<?php echo ew_HtmlEncode($view_e_y_n->Cargo_gme->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Cargo_gme->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->NOM_GE->Visible) { // NOM_GE ?>
	<div id="r_NOM_GE" class="form-group">
		<label id="elh_view_e_y_n_NOM_GE" for="x_NOM_GE" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->NOM_GE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->NOM_GE->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_NOM_GE">
<select data-field="x_NOM_GE" id="x_NOM_GE" name="x_NOM_GE"<?php echo $view_e_y_n->NOM_GE->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->NOM_GE->EditValue)) {
	$arwrk = $view_e_y_n->NOM_GE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->NOM_GE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fview_e_y_nedit.Lists["x_NOM_GE"].Options = <?php echo (is_array($view_e_y_n->NOM_GE->EditValue)) ? ew_ArrayToJson($view_e_y_n->NOM_GE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_NOM_GE">
<span<?php echo $view_e_y_n->NOM_GE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->NOM_GE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_GE" name="x_NOM_GE" id="x_NOM_GE" value="<?php echo ew_HtmlEncode($view_e_y_n->NOM_GE->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->NOM_GE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Otro_PGE->Visible) { // Otro_PGE ?>
	<div id="r_Otro_PGE" class="form-group">
		<label id="elh_view_e_y_n_Otro_PGE" for="x_Otro_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Otro_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Otro_PGE->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Otro_PGE">
<input type="text" data-field="x_Otro_PGE" name="x_Otro_PGE" id="x_Otro_PGE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Otro_PGE->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Otro_PGE->EditValue ?>"<?php echo $view_e_y_n->Otro_PGE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Otro_PGE">
<span<?php echo $view_e_y_n->Otro_PGE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Otro_PGE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_PGE" name="x_Otro_PGE" id="x_Otro_PGE" value="<?php echo ew_HtmlEncode($view_e_y_n->Otro_PGE->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Otro_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
	<div id="r_Otro_CC_PGE" class="form-group">
		<label id="elh_view_e_y_n_Otro_CC_PGE" for="x_Otro_CC_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Otro_CC_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Otro_CC_PGE->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Otro_CC_PGE">
<input type="text" data-field="x_Otro_CC_PGE" name="x_Otro_CC_PGE" id="x_Otro_CC_PGE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Otro_CC_PGE->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Otro_CC_PGE->EditValue ?>"<?php echo $view_e_y_n->Otro_CC_PGE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Otro_CC_PGE">
<span<?php echo $view_e_y_n->Otro_CC_PGE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Otro_CC_PGE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_CC_PGE" name="x_Otro_CC_PGE" id="x_Otro_CC_PGE" value="<?php echo ew_HtmlEncode($view_e_y_n->Otro_CC_PGE->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Otro_CC_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
	<div id="r_TIPO_INFORME" class="form-group">
		<label id="elh_view_e_y_n_TIPO_INFORME" for="x_TIPO_INFORME" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->TIPO_INFORME->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->TIPO_INFORME->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_TIPO_INFORME">
<select data-field="x_TIPO_INFORME" id="x_TIPO_INFORME" name="x_TIPO_INFORME"<?php echo $view_e_y_n->TIPO_INFORME->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->TIPO_INFORME->EditValue)) {
	$arwrk = $view_e_y_n->TIPO_INFORME->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->TIPO_INFORME->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fview_e_y_nedit.Lists["x_TIPO_INFORME"].Options = <?php echo (is_array($view_e_y_n->TIPO_INFORME->EditValue)) ? ew_ArrayToJson($view_e_y_n->TIPO_INFORME->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_TIPO_INFORME">
<span<?php echo $view_e_y_n->TIPO_INFORME->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->TIPO_INFORME->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_TIPO_INFORME" name="x_TIPO_INFORME" id="x_TIPO_INFORME" value="<?php echo ew_HtmlEncode($view_e_y_n->TIPO_INFORME->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->TIPO_INFORME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->FECHA_NOVEDAD->Visible) { // FECHA_NOVEDAD ?>
	<div id="r_FECHA_NOVEDAD" class="form-group">
		<label id="elh_view_e_y_n_FECHA_NOVEDAD" for="x_FECHA_NOVEDAD" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->FECHA_NOVEDAD->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->FECHA_NOVEDAD->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_FECHA_NOVEDAD">
<input type="text" data-field="x_FECHA_NOVEDAD" name="x_FECHA_NOVEDAD" id="x_FECHA_NOVEDAD" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->FECHA_NOVEDAD->PlaceHolder) ?>" value="<?php echo $view_e_y_n->FECHA_NOVEDAD->EditValue ?>"<?php echo $view_e_y_n->FECHA_NOVEDAD->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_FECHA_NOVEDAD">
<span<?php echo $view_e_y_n->FECHA_NOVEDAD->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->FECHA_NOVEDAD->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_FECHA_NOVEDAD" name="x_FECHA_NOVEDAD" id="x_FECHA_NOVEDAD" value="<?php echo ew_HtmlEncode($view_e_y_n->FECHA_NOVEDAD->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->FECHA_NOVEDAD->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->DIA->Visible) { // DIA ?>
	<div id="r_DIA" class="form-group">
		<label id="elh_view_e_y_n_DIA" for="x_DIA" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->DIA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->DIA->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_DIA">
<input type="text" data-field="x_DIA" name="x_DIA" id="x_DIA" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->DIA->PlaceHolder) ?>" value="<?php echo $view_e_y_n->DIA->EditValue ?>"<?php echo $view_e_y_n->DIA->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_DIA">
<span<?php echo $view_e_y_n->DIA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->DIA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_DIA" name="x_DIA" id="x_DIA" value="<?php echo ew_HtmlEncode($view_e_y_n->DIA->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->DIA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->MES->Visible) { // MES ?>
	<div id="r_MES" class="form-group">
		<label id="elh_view_e_y_n_MES" for="x_MES" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->MES->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->MES->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_MES">
<input type="text" data-field="x_MES" name="x_MES" id="x_MES" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->MES->PlaceHolder) ?>" value="<?php echo $view_e_y_n->MES->EditValue ?>"<?php echo $view_e_y_n->MES->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_MES">
<span<?php echo $view_e_y_n->MES->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->MES->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_MES" name="x_MES" id="x_MES" value="<?php echo ew_HtmlEncode($view_e_y_n->MES->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->MES->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Num_Evacua->Visible) { // Num_Evacua ?>
	<div id="r_Num_Evacua" class="form-group">
		<label id="elh_view_e_y_n_Num_Evacua" for="x_Num_Evacua" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Num_Evacua->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Num_Evacua->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Num_Evacua">
<input type="text" data-field="x_Num_Evacua" name="x_Num_Evacua" id="x_Num_Evacua" size="30" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Num_Evacua->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Num_Evacua->EditValue ?>"<?php echo $view_e_y_n->Num_Evacua->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Num_Evacua">
<span<?php echo $view_e_y_n->Num_Evacua->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Num_Evacua->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Num_Evacua" name="x_Num_Evacua" id="x_Num_Evacua" value="<?php echo ew_HtmlEncode($view_e_y_n->Num_Evacua->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Num_Evacua->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->PTO_INCOMU->Visible) { // PTO_INCOMU ?>
	<div id="r_PTO_INCOMU" class="form-group">
		<label id="elh_view_e_y_n_PTO_INCOMU" for="x_PTO_INCOMU" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->PTO_INCOMU->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->PTO_INCOMU->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_PTO_INCOMU">
<select data-field="x_PTO_INCOMU" id="x_PTO_INCOMU" name="x_PTO_INCOMU"<?php echo $view_e_y_n->PTO_INCOMU->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->PTO_INCOMU->EditValue)) {
	$arwrk = $view_e_y_n->PTO_INCOMU->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->PTO_INCOMU->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php } else { ?>
<span id="el_view_e_y_n_PTO_INCOMU">
<span<?php echo $view_e_y_n->PTO_INCOMU->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->PTO_INCOMU->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_PTO_INCOMU" name="x_PTO_INCOMU" id="x_PTO_INCOMU" value="<?php echo ew_HtmlEncode($view_e_y_n->PTO_INCOMU->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->PTO_INCOMU->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->OBS_punt_inco->Visible) { // OBS_punt_inco ?>
	<div id="r_OBS_punt_inco" class="form-group">
		<label id="elh_view_e_y_n_OBS_punt_inco" for="x_OBS_punt_inco" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->OBS_punt_inco->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->OBS_punt_inco->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_OBS_punt_inco">
<textarea data-field="x_OBS_punt_inco" name="x_OBS_punt_inco" id="x_OBS_punt_inco" cols="120" rows="4" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->OBS_punt_inco->PlaceHolder) ?>"<?php echo $view_e_y_n->OBS_punt_inco->EditAttributes() ?>><?php echo $view_e_y_n->OBS_punt_inco->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_OBS_punt_inco">
<span<?php echo $view_e_y_n->OBS_punt_inco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->OBS_punt_inco->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_OBS_punt_inco" name="x_OBS_punt_inco" id="x_OBS_punt_inco" value="<?php echo ew_HtmlEncode($view_e_y_n->OBS_punt_inco->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->OBS_punt_inco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->OBS_ENLACE->Visible) { // OBS_ENLACE ?>
	<div id="r_OBS_ENLACE" class="form-group">
		<label id="elh_view_e_y_n_OBS_ENLACE" for="x_OBS_ENLACE" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->OBS_ENLACE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->OBS_ENLACE->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_OBS_ENLACE">
<textarea data-field="x_OBS_ENLACE" name="x_OBS_ENLACE" id="x_OBS_ENLACE" cols="120" rows="4" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->OBS_ENLACE->PlaceHolder) ?>"<?php echo $view_e_y_n->OBS_ENLACE->EditAttributes() ?>><?php echo $view_e_y_n->OBS_ENLACE->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_OBS_ENLACE">
<span<?php echo $view_e_y_n->OBS_ENLACE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->OBS_ENLACE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_OBS_ENLACE" name="x_OBS_ENLACE" id="x_OBS_ENLACE" value="<?php echo ew_HtmlEncode($view_e_y_n->OBS_ENLACE->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->OBS_ENLACE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->NUM_Novedad->Visible) { // NUM_Novedad ?>
	<div id="r_NUM_Novedad" class="form-group">
		<label id="elh_view_e_y_n_NUM_Novedad" for="x_NUM_Novedad" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->NUM_Novedad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->NUM_Novedad->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_NUM_Novedad">
<input type="text" data-field="x_NUM_Novedad" name="x_NUM_Novedad" id="x_NUM_Novedad" size="30" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->NUM_Novedad->PlaceHolder) ?>" value="<?php echo $view_e_y_n->NUM_Novedad->EditValue ?>"<?php echo $view_e_y_n->NUM_Novedad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_NUM_Novedad">
<span<?php echo $view_e_y_n->NUM_Novedad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->NUM_Novedad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NUM_Novedad" name="x_NUM_Novedad" id="x_NUM_Novedad" value="<?php echo ew_HtmlEncode($view_e_y_n->NUM_Novedad->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->NUM_Novedad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Nom_Per_Evacu->Visible) { // Nom_Per_Evacu ?>
	<div id="r_Nom_Per_Evacu" class="form-group">
		<label id="elh_view_e_y_n_Nom_Per_Evacu" for="x_Nom_Per_Evacu" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Nom_Per_Evacu->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Nom_Per_Evacu->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Nom_Per_Evacu">
<textarea data-field="x_Nom_Per_Evacu" name="x_Nom_Per_Evacu" id="x_Nom_Per_Evacu" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Nom_Per_Evacu->PlaceHolder) ?>"<?php echo $view_e_y_n->Nom_Per_Evacu->EditAttributes() ?>><?php echo $view_e_y_n->Nom_Per_Evacu->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Nom_Per_Evacu">
<span<?php echo $view_e_y_n->Nom_Per_Evacu->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Nom_Per_Evacu->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Nom_Per_Evacu" name="x_Nom_Per_Evacu" id="x_Nom_Per_Evacu" value="<?php echo ew_HtmlEncode($view_e_y_n->Nom_Per_Evacu->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Nom_Per_Evacu->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Nom_Otro_Per_Evacu->Visible) { // Nom_Otro_Per_Evacu ?>
	<div id="r_Nom_Otro_Per_Evacu" class="form-group">
		<label id="elh_view_e_y_n_Nom_Otro_Per_Evacu" for="x_Nom_Otro_Per_Evacu" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Nom_Otro_Per_Evacu->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Nom_Otro_Per_Evacu">
<input type="text" data-field="x_Nom_Otro_Per_Evacu" name="x_Nom_Otro_Per_Evacu" id="x_Nom_Otro_Per_Evacu" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Nom_Otro_Per_Evacu->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->EditValue ?>"<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Nom_Otro_Per_Evacu">
<span<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Nom_Otro_Per_Evacu->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Nom_Otro_Per_Evacu" name="x_Nom_Otro_Per_Evacu" id="x_Nom_Otro_Per_Evacu" value="<?php echo ew_HtmlEncode($view_e_y_n->Nom_Otro_Per_Evacu->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->CC_Otro_Per_Evacu->Visible) { // CC_Otro_Per_Evacu ?>
	<div id="r_CC_Otro_Per_Evacu" class="form-group">
		<label id="elh_view_e_y_n_CC_Otro_Per_Evacu" for="x_CC_Otro_Per_Evacu" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->CC_Otro_Per_Evacu->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->CC_Otro_Per_Evacu->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_CC_Otro_Per_Evacu">
<input type="text" data-field="x_CC_Otro_Per_Evacu" name="x_CC_Otro_Per_Evacu" id="x_CC_Otro_Per_Evacu" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->CC_Otro_Per_Evacu->PlaceHolder) ?>" value="<?php echo $view_e_y_n->CC_Otro_Per_Evacu->EditValue ?>"<?php echo $view_e_y_n->CC_Otro_Per_Evacu->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_CC_Otro_Per_Evacu">
<span<?php echo $view_e_y_n->CC_Otro_Per_Evacu->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->CC_Otro_Per_Evacu->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_CC_Otro_Per_Evacu" name="x_CC_Otro_Per_Evacu" id="x_CC_Otro_Per_Evacu" value="<?php echo ew_HtmlEncode($view_e_y_n->CC_Otro_Per_Evacu->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->CC_Otro_Per_Evacu->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Cargo_Per_EVA->Visible) { // Cargo_Per_EVA ?>
	<div id="r_Cargo_Per_EVA" class="form-group">
		<label id="elh_view_e_y_n_Cargo_Per_EVA" for="x_Cargo_Per_EVA" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Cargo_Per_EVA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Cargo_Per_EVA->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Cargo_Per_EVA">
<select data-field="x_Cargo_Per_EVA" id="x_Cargo_Per_EVA" name="x_Cargo_Per_EVA"<?php echo $view_e_y_n->Cargo_Per_EVA->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->Cargo_Per_EVA->EditValue)) {
	$arwrk = $view_e_y_n->Cargo_Per_EVA->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->Cargo_Per_EVA->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fview_e_y_nedit.Lists["x_Cargo_Per_EVA"].Options = <?php echo (is_array($view_e_y_n->Cargo_Per_EVA->EditValue)) ? ew_ArrayToJson($view_e_y_n->Cargo_Per_EVA->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Cargo_Per_EVA">
<span<?php echo $view_e_y_n->Cargo_Per_EVA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Cargo_Per_EVA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_Per_EVA" name="x_Cargo_Per_EVA" id="x_Cargo_Per_EVA" value="<?php echo ew_HtmlEncode($view_e_y_n->Cargo_Per_EVA->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Cargo_Per_EVA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Motivo_Eva->Visible) { // Motivo_Eva ?>
	<div id="r_Motivo_Eva" class="form-group">
		<label id="elh_view_e_y_n_Motivo_Eva" for="x_Motivo_Eva" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Motivo_Eva->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Motivo_Eva->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Motivo_Eva">
<select data-field="x_Motivo_Eva" id="x_Motivo_Eva" name="x_Motivo_Eva"<?php echo $view_e_y_n->Motivo_Eva->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->Motivo_Eva->EditValue)) {
	$arwrk = $view_e_y_n->Motivo_Eva->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->Motivo_Eva->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fview_e_y_nedit.Lists["x_Motivo_Eva"].Options = <?php echo (is_array($view_e_y_n->Motivo_Eva->EditValue)) ? ew_ArrayToJson($view_e_y_n->Motivo_Eva->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Motivo_Eva">
<span<?php echo $view_e_y_n->Motivo_Eva->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Motivo_Eva->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Motivo_Eva" name="x_Motivo_Eva" id="x_Motivo_Eva" value="<?php echo ew_HtmlEncode($view_e_y_n->Motivo_Eva->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Motivo_Eva->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->OBS_EVA->Visible) { // OBS_EVA ?>
	<div id="r_OBS_EVA" class="form-group">
		<label id="elh_view_e_y_n_OBS_EVA" for="x_OBS_EVA" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->OBS_EVA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->OBS_EVA->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_OBS_EVA">
<textarea data-field="x_OBS_EVA" name="x_OBS_EVA" id="x_OBS_EVA" cols="70" rows="4" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->OBS_EVA->PlaceHolder) ?>"<?php echo $view_e_y_n->OBS_EVA->EditAttributes() ?>><?php echo $view_e_y_n->OBS_EVA->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_OBS_EVA">
<span<?php echo $view_e_y_n->OBS_EVA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->OBS_EVA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_OBS_EVA" name="x_OBS_EVA" id="x_OBS_EVA" value="<?php echo ew_HtmlEncode($view_e_y_n->OBS_EVA->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->OBS_EVA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->NOM_PE->Visible) { // NOM_PE ?>
	<div id="r_NOM_PE" class="form-group">
		<label id="elh_view_e_y_n_NOM_PE" for="x_NOM_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->NOM_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->NOM_PE->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_NOM_PE">
<select data-field="x_NOM_PE" id="x_NOM_PE" name="x_NOM_PE"<?php echo $view_e_y_n->NOM_PE->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->NOM_PE->EditValue)) {
	$arwrk = $view_e_y_n->NOM_PE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->NOM_PE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fview_e_y_nedit.Lists["x_NOM_PE"].Options = <?php echo (is_array($view_e_y_n->NOM_PE->EditValue)) ? ew_ArrayToJson($view_e_y_n->NOM_PE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_NOM_PE">
<span<?php echo $view_e_y_n->NOM_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->NOM_PE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_PE" name="x_NOM_PE" id="x_NOM_PE" value="<?php echo ew_HtmlEncode($view_e_y_n->NOM_PE->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->NOM_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Otro_Nom_PE->Visible) { // Otro_Nom_PE ?>
	<div id="r_Otro_Nom_PE" class="form-group">
		<label id="elh_view_e_y_n_Otro_Nom_PE" for="x_Otro_Nom_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Otro_Nom_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Otro_Nom_PE->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Otro_Nom_PE">
<input type="text" data-field="x_Otro_Nom_PE" name="x_Otro_Nom_PE" id="x_Otro_Nom_PE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Otro_Nom_PE->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Otro_Nom_PE->EditValue ?>"<?php echo $view_e_y_n->Otro_Nom_PE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Otro_Nom_PE">
<span<?php echo $view_e_y_n->Otro_Nom_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Otro_Nom_PE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_Nom_PE" name="x_Otro_Nom_PE" id="x_Otro_Nom_PE" value="<?php echo ew_HtmlEncode($view_e_y_n->Otro_Nom_PE->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Otro_Nom_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
	<div id="r_NOM_CAPATAZ" class="form-group">
		<label id="elh_view_e_y_n_NOM_CAPATAZ" for="x_NOM_CAPATAZ" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->NOM_CAPATAZ->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->NOM_CAPATAZ->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_NOM_CAPATAZ">
<textarea data-field="x_NOM_CAPATAZ" name="x_NOM_CAPATAZ" id="x_NOM_CAPATAZ" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->NOM_CAPATAZ->PlaceHolder) ?>"<?php echo $view_e_y_n->NOM_CAPATAZ->EditAttributes() ?>><?php echo $view_e_y_n->NOM_CAPATAZ->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_NOM_CAPATAZ">
<span<?php echo $view_e_y_n->NOM_CAPATAZ->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->NOM_CAPATAZ->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_CAPATAZ" name="x_NOM_CAPATAZ" id="x_NOM_CAPATAZ" value="<?php echo ew_HtmlEncode($view_e_y_n->NOM_CAPATAZ->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->NOM_CAPATAZ->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Otro_Nom_Capata->Visible) { // Otro_Nom_Capata ?>
	<div id="r_Otro_Nom_Capata" class="form-group">
		<label id="elh_view_e_y_n_Otro_Nom_Capata" for="x_Otro_Nom_Capata" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Otro_Nom_Capata->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Otro_Nom_Capata->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Otro_Nom_Capata">
<input type="text" data-field="x_Otro_Nom_Capata" name="x_Otro_Nom_Capata" id="x_Otro_Nom_Capata" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Otro_Nom_Capata->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Otro_Nom_Capata->EditValue ?>"<?php echo $view_e_y_n->Otro_Nom_Capata->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Otro_Nom_Capata">
<span<?php echo $view_e_y_n->Otro_Nom_Capata->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Otro_Nom_Capata->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_Nom_Capata" name="x_Otro_Nom_Capata" id="x_Otro_Nom_Capata" value="<?php echo ew_HtmlEncode($view_e_y_n->Otro_Nom_Capata->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Otro_Nom_Capata->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Otro_CC_Capata->Visible) { // Otro_CC_Capata ?>
	<div id="r_Otro_CC_Capata" class="form-group">
		<label id="elh_view_e_y_n_Otro_CC_Capata" for="x_Otro_CC_Capata" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Otro_CC_Capata->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Otro_CC_Capata->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Otro_CC_Capata">
<input type="text" data-field="x_Otro_CC_Capata" name="x_Otro_CC_Capata" id="x_Otro_CC_Capata" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Otro_CC_Capata->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Otro_CC_Capata->EditValue ?>"<?php echo $view_e_y_n->Otro_CC_Capata->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Otro_CC_Capata">
<span<?php echo $view_e_y_n->Otro_CC_Capata->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Otro_CC_Capata->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_CC_Capata" name="x_Otro_CC_Capata" id="x_Otro_CC_Capata" value="<?php echo ew_HtmlEncode($view_e_y_n->Otro_CC_Capata->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Otro_CC_Capata->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Muncipio->Visible) { // Muncipio ?>
	<div id="r_Muncipio" class="form-group">
		<label id="elh_view_e_y_n_Muncipio" for="x_Muncipio" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Muncipio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Muncipio->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Muncipio">
<textarea data-field="x_Muncipio" name="x_Muncipio" id="x_Muncipio" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Muncipio->PlaceHolder) ?>"<?php echo $view_e_y_n->Muncipio->EditAttributes() ?>><?php echo $view_e_y_n->Muncipio->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Muncipio">
<span<?php echo $view_e_y_n->Muncipio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Muncipio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Muncipio" name="x_Muncipio" id="x_Muncipio" value="<?php echo ew_HtmlEncode($view_e_y_n->Muncipio->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Muncipio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->F_llegada->Visible) { // F_llegada ?>
	<div id="r_F_llegada" class="form-group">
		<label id="elh_view_e_y_n_F_llegada" for="x_F_llegada" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->F_llegada->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->F_llegada->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_F_llegada">
<select data-field="x_F_llegada" id="x_F_llegada" name="x_F_llegada"<?php echo $view_e_y_n->F_llegada->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->F_llegada->EditValue)) {
	$arwrk = $view_e_y_n->F_llegada->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->F_llegada->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php } else { ?>
<span id="el_view_e_y_n_F_llegada">
<span<?php echo $view_e_y_n->F_llegada->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->F_llegada->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_llegada" name="x_F_llegada" id="x_F_llegada" value="<?php echo ew_HtmlEncode($view_e_y_n->F_llegada->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->F_llegada->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Fecha->Visible) { // Fecha ?>
	<div id="r_Fecha" class="form-group">
		<label id="elh_view_e_y_n_Fecha" for="x_Fecha" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Fecha->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Fecha">
<input type="text" data-field="x_Fecha" name="x_Fecha" id="x_Fecha" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->Fecha->PlaceHolder) ?>" value="<?php echo $view_e_y_n->Fecha->EditValue ?>"<?php echo $view_e_y_n->Fecha->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_Fecha">
<span<?php echo $view_e_y_n->Fecha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Fecha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Fecha" name="x_Fecha" id="x_Fecha" value="<?php echo ew_HtmlEncode($view_e_y_n->Fecha->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->Modificado->Visible) { // Modificado ?>
	<div id="r_Modificado" class="form-group">
		<label id="elh_view_e_y_n_Modificado" for="x_Modificado" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->Modificado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->Modificado->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_Modificado">
<select data-field="x_Modificado" id="x_Modificado" name="x_Modificado"<?php echo $view_e_y_n->Modificado->EditAttributes() ?>>
<?php
if (is_array($view_e_y_n->Modificado->EditValue)) {
	$arwrk = $view_e_y_n->Modificado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_e_y_n->Modificado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php } else { ?>
<span id="el_view_e_y_n_Modificado">
<span<?php echo $view_e_y_n->Modificado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->Modificado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Modificado" name="x_Modificado" id="x_Modificado" value="<?php echo ew_HtmlEncode($view_e_y_n->Modificado->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->Modificado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_e_y_n->departamento->Visible) { // departamento ?>
	<div id="r_departamento" class="form-group">
		<label id="elh_view_e_y_n_departamento" for="x_departamento" class="col-sm-2 control-label ewLabel"><?php echo $view_e_y_n->departamento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_e_y_n->departamento->CellAttributes() ?>>
<?php if ($view_e_y_n->CurrentAction <> "F") { ?>
<span id="el_view_e_y_n_departamento">
<input type="text" data-field="x_departamento" name="x_departamento" id="x_departamento" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->departamento->PlaceHolder) ?>" value="<?php echo $view_e_y_n->departamento->EditValue ?>"<?php echo $view_e_y_n->departamento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_e_y_n_departamento">
<span<?php echo $view_e_y_n->departamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_e_y_n->departamento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_departamento" name="x_departamento" id="x_departamento" value="<?php echo ew_HtmlEncode($view_e_y_n->departamento->FormValue) ?>">
<?php } ?>
<?php echo $view_e_y_n->departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_llave_2" name="x_llave_2" id="x_llave_2" value="<?php echo ew_HtmlEncode($view_e_y_n->llave_2->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<?php if ($view_e_y_n->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div>
</div>
</form>
<script type="text/javascript">
fview_e_y_nedit.Init();
</script>
<?php
$view_e_y_n_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_e_y_n_edit->Page_Terminate();
?>
