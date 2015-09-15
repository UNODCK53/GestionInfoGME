<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view_invinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view_inv_edit = NULL; // Initialize page object first

class cview_inv_edit extends cview_inv {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_inv';

	// Page object name
	var $PageObjName = 'view_inv_edit';

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

		// Table object (view_inv)
		if (!isset($GLOBALS["view_inv"]) || get_class($GLOBALS["view_inv"]) == "cview_inv") {
			$GLOBALS["view_inv"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_inv"];
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
			define("EW_TABLE_NAME", 'view_inv', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view_invlist.php"));
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
		global $EW_EXPORT, $view_inv;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_inv);
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
			$this->Page_Terminate("view_invlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("view_invlist.php"); // No matching record, return to list
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
		if (!$this->llave->FldIsDetailKey) {
			$this->llave->setFormValue($objForm->GetValue("x_llave"));
		}
		if (!$this->F_Sincron->FldIsDetailKey) {
			$this->F_Sincron->setFormValue($objForm->GetValue("x_F_Sincron"));
			$this->F_Sincron->CurrentValue = ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 5);
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
		if (!$this->DIA->FldIsDetailKey) {
			$this->DIA->setFormValue($objForm->GetValue("x_DIA"));
		}
		if (!$this->MES->FldIsDetailKey) {
			$this->MES->setFormValue($objForm->GetValue("x_MES"));
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
		if (!$this->FECHA_INV->FldIsDetailKey) {
			$this->FECHA_INV->setFormValue($objForm->GetValue("x_FECHA_INV"));
		}
		if (!$this->TIPO_INV->FldIsDetailKey) {
			$this->TIPO_INV->setFormValue($objForm->GetValue("x_TIPO_INV"));
		}
		if (!$this->NOM_CAPATAZ->FldIsDetailKey) {
			$this->NOM_CAPATAZ->setFormValue($objForm->GetValue("x_NOM_CAPATAZ"));
		}
		if (!$this->Otro_NOM_CAPAT->FldIsDetailKey) {
			$this->Otro_NOM_CAPAT->setFormValue($objForm->GetValue("x_Otro_NOM_CAPAT"));
		}
		if (!$this->Otro_CC_CAPAT->FldIsDetailKey) {
			$this->Otro_CC_CAPAT->setFormValue($objForm->GetValue("x_Otro_CC_CAPAT"));
		}
		if (!$this->NOM_LUGAR->FldIsDetailKey) {
			$this->NOM_LUGAR->setFormValue($objForm->GetValue("x_NOM_LUGAR"));
		}
		if (!$this->Cocina->FldIsDetailKey) {
			$this->Cocina->setFormValue($objForm->GetValue("x_Cocina"));
		}
		if (!$this->_1_Abrelatas->FldIsDetailKey) {
			$this->_1_Abrelatas->setFormValue($objForm->GetValue("x__1_Abrelatas"));
		}
		if (!$this->_1_Balde->FldIsDetailKey) {
			$this->_1_Balde->setFormValue($objForm->GetValue("x__1_Balde"));
		}
		if (!$this->_1_Arrocero_50->FldIsDetailKey) {
			$this->_1_Arrocero_50->setFormValue($objForm->GetValue("x__1_Arrocero_50"));
		}
		if (!$this->_1_Arrocero_44->FldIsDetailKey) {
			$this->_1_Arrocero_44->setFormValue($objForm->GetValue("x__1_Arrocero_44"));
		}
		if (!$this->_1_Chocolatera->FldIsDetailKey) {
			$this->_1_Chocolatera->setFormValue($objForm->GetValue("x__1_Chocolatera"));
		}
		if (!$this->_1_Colador->FldIsDetailKey) {
			$this->_1_Colador->setFormValue($objForm->GetValue("x__1_Colador"));
		}
		if (!$this->_1_Cucharon_sopa->FldIsDetailKey) {
			$this->_1_Cucharon_sopa->setFormValue($objForm->GetValue("x__1_Cucharon_sopa"));
		}
		if (!$this->_1_Cucharon_arroz->FldIsDetailKey) {
			$this->_1_Cucharon_arroz->setFormValue($objForm->GetValue("x__1_Cucharon_arroz"));
		}
		if (!$this->_1_Cuchillo->FldIsDetailKey) {
			$this->_1_Cuchillo->setFormValue($objForm->GetValue("x__1_Cuchillo"));
		}
		if (!$this->_1_Embudo->FldIsDetailKey) {
			$this->_1_Embudo->setFormValue($objForm->GetValue("x__1_Embudo"));
		}
		if (!$this->_1_Espumera->FldIsDetailKey) {
			$this->_1_Espumera->setFormValue($objForm->GetValue("x__1_Espumera"));
		}
		if (!$this->_1_Estufa->FldIsDetailKey) {
			$this->_1_Estufa->setFormValue($objForm->GetValue("x__1_Estufa"));
		}
		if (!$this->_1_Cuchara_sopa->FldIsDetailKey) {
			$this->_1_Cuchara_sopa->setFormValue($objForm->GetValue("x__1_Cuchara_sopa"));
		}
		if (!$this->_1_Recipiente->FldIsDetailKey) {
			$this->_1_Recipiente->setFormValue($objForm->GetValue("x__1_Recipiente"));
		}
		if (!$this->_1_Kit_Repue_estufa->FldIsDetailKey) {
			$this->_1_Kit_Repue_estufa->setFormValue($objForm->GetValue("x__1_Kit_Repue_estufa"));
		}
		if (!$this->_1_Molinillo->FldIsDetailKey) {
			$this->_1_Molinillo->setFormValue($objForm->GetValue("x__1_Molinillo"));
		}
		if (!$this->_1_Olla_36->FldIsDetailKey) {
			$this->_1_Olla_36->setFormValue($objForm->GetValue("x__1_Olla_36"));
		}
		if (!$this->_1_Olla_40->FldIsDetailKey) {
			$this->_1_Olla_40->setFormValue($objForm->GetValue("x__1_Olla_40"));
		}
		if (!$this->_1_Paila_32->FldIsDetailKey) {
			$this->_1_Paila_32->setFormValue($objForm->GetValue("x__1_Paila_32"));
		}
		if (!$this->_1_Paila_36_37->FldIsDetailKey) {
			$this->_1_Paila_36_37->setFormValue($objForm->GetValue("x__1_Paila_36_37"));
		}
		if (!$this->Camping->FldIsDetailKey) {
			$this->Camping->setFormValue($objForm->GetValue("x_Camping"));
		}
		if (!$this->_2_Aislante->FldIsDetailKey) {
			$this->_2_Aislante->setFormValue($objForm->GetValue("x__2_Aislante"));
		}
		if (!$this->_2_Carpa_hamaca->FldIsDetailKey) {
			$this->_2_Carpa_hamaca->setFormValue($objForm->GetValue("x__2_Carpa_hamaca"));
		}
		if (!$this->_2_Carpa_rancho->FldIsDetailKey) {
			$this->_2_Carpa_rancho->setFormValue($objForm->GetValue("x__2_Carpa_rancho"));
		}
		if (!$this->_2_Fibra_rollo->FldIsDetailKey) {
			$this->_2_Fibra_rollo->setFormValue($objForm->GetValue("x__2_Fibra_rollo"));
		}
		if (!$this->_2_CAL->FldIsDetailKey) {
			$this->_2_CAL->setFormValue($objForm->GetValue("x__2_CAL"));
		}
		if (!$this->_2_Linterna->FldIsDetailKey) {
			$this->_2_Linterna->setFormValue($objForm->GetValue("x__2_Linterna"));
		}
		if (!$this->_2_Botiquin->FldIsDetailKey) {
			$this->_2_Botiquin->setFormValue($objForm->GetValue("x__2_Botiquin"));
		}
		if (!$this->_2_Mascara_filtro->FldIsDetailKey) {
			$this->_2_Mascara_filtro->setFormValue($objForm->GetValue("x__2_Mascara_filtro"));
		}
		if (!$this->_2_Pimpina->FldIsDetailKey) {
			$this->_2_Pimpina->setFormValue($objForm->GetValue("x__2_Pimpina"));
		}
		if (!$this->_2_SleepingA0->FldIsDetailKey) {
			$this->_2_SleepingA0->setFormValue($objForm->GetValue("x__2_SleepingA0"));
		}
		if (!$this->_2_Plastico_negro->FldIsDetailKey) {
			$this->_2_Plastico_negro->setFormValue($objForm->GetValue("x__2_Plastico_negro"));
		}
		if (!$this->_2_Tula_tropa->FldIsDetailKey) {
			$this->_2_Tula_tropa->setFormValue($objForm->GetValue("x__2_Tula_tropa"));
		}
		if (!$this->_2_Camilla->FldIsDetailKey) {
			$this->_2_Camilla->setFormValue($objForm->GetValue("x__2_Camilla"));
		}
		if (!$this->Herramientas->FldIsDetailKey) {
			$this->Herramientas->setFormValue($objForm->GetValue("x_Herramientas"));
		}
		if (!$this->_3_Abrazadera->FldIsDetailKey) {
			$this->_3_Abrazadera->setFormValue($objForm->GetValue("x__3_Abrazadera"));
		}
		if (!$this->_3_Aspersora->FldIsDetailKey) {
			$this->_3_Aspersora->setFormValue($objForm->GetValue("x__3_Aspersora"));
		}
		if (!$this->_3_Cabo_hacha->FldIsDetailKey) {
			$this->_3_Cabo_hacha->setFormValue($objForm->GetValue("x__3_Cabo_hacha"));
		}
		if (!$this->_3_Funda_machete->FldIsDetailKey) {
			$this->_3_Funda_machete->setFormValue($objForm->GetValue("x__3_Funda_machete"));
		}
		if (!$this->_3_Glifosato_4lt->FldIsDetailKey) {
			$this->_3_Glifosato_4lt->setFormValue($objForm->GetValue("x__3_Glifosato_4lt"));
		}
		if (!$this->_3_Hacha->FldIsDetailKey) {
			$this->_3_Hacha->setFormValue($objForm->GetValue("x__3_Hacha"));
		}
		if (!$this->_3_Lima_12_uni->FldIsDetailKey) {
			$this->_3_Lima_12_uni->setFormValue($objForm->GetValue("x__3_Lima_12_uni"));
		}
		if (!$this->_3_Llave_mixta->FldIsDetailKey) {
			$this->_3_Llave_mixta->setFormValue($objForm->GetValue("x__3_Llave_mixta"));
		}
		if (!$this->_3_Machete->FldIsDetailKey) {
			$this->_3_Machete->setFormValue($objForm->GetValue("x__3_Machete"));
		}
		if (!$this->_3_Gafa_traslucida->FldIsDetailKey) {
			$this->_3_Gafa_traslucida->setFormValue($objForm->GetValue("x__3_Gafa_traslucida"));
		}
		if (!$this->_3_Motosierra->FldIsDetailKey) {
			$this->_3_Motosierra->setFormValue($objForm->GetValue("x__3_Motosierra"));
		}
		if (!$this->_3_Palin->FldIsDetailKey) {
			$this->_3_Palin->setFormValue($objForm->GetValue("x__3_Palin"));
		}
		if (!$this->_3_Tubo_galvanizado->FldIsDetailKey) {
			$this->_3_Tubo_galvanizado->setFormValue($objForm->GetValue("x__3_Tubo_galvanizado"));
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
		$this->F_Sincron->CurrentValue = ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 5);
		$this->USUARIO->CurrentValue = $this->USUARIO->FormValue;
		$this->Cargo_gme->CurrentValue = $this->Cargo_gme->FormValue;
		$this->NOM_PE->CurrentValue = $this->NOM_PE->FormValue;
		$this->Otro_PE->CurrentValue = $this->Otro_PE->FormValue;
		$this->DIA->CurrentValue = $this->DIA->FormValue;
		$this->MES->CurrentValue = $this->MES->FormValue;
		$this->OBSERVACION->CurrentValue = $this->OBSERVACION->FormValue;
		$this->AD1O->CurrentValue = $this->AD1O->FormValue;
		$this->FASE->CurrentValue = $this->FASE->FormValue;
		$this->FECHA_INV->CurrentValue = $this->FECHA_INV->FormValue;
		$this->TIPO_INV->CurrentValue = $this->TIPO_INV->FormValue;
		$this->NOM_CAPATAZ->CurrentValue = $this->NOM_CAPATAZ->FormValue;
		$this->Otro_NOM_CAPAT->CurrentValue = $this->Otro_NOM_CAPAT->FormValue;
		$this->Otro_CC_CAPAT->CurrentValue = $this->Otro_CC_CAPAT->FormValue;
		$this->NOM_LUGAR->CurrentValue = $this->NOM_LUGAR->FormValue;
		$this->Cocina->CurrentValue = $this->Cocina->FormValue;
		$this->_1_Abrelatas->CurrentValue = $this->_1_Abrelatas->FormValue;
		$this->_1_Balde->CurrentValue = $this->_1_Balde->FormValue;
		$this->_1_Arrocero_50->CurrentValue = $this->_1_Arrocero_50->FormValue;
		$this->_1_Arrocero_44->CurrentValue = $this->_1_Arrocero_44->FormValue;
		$this->_1_Chocolatera->CurrentValue = $this->_1_Chocolatera->FormValue;
		$this->_1_Colador->CurrentValue = $this->_1_Colador->FormValue;
		$this->_1_Cucharon_sopa->CurrentValue = $this->_1_Cucharon_sopa->FormValue;
		$this->_1_Cucharon_arroz->CurrentValue = $this->_1_Cucharon_arroz->FormValue;
		$this->_1_Cuchillo->CurrentValue = $this->_1_Cuchillo->FormValue;
		$this->_1_Embudo->CurrentValue = $this->_1_Embudo->FormValue;
		$this->_1_Espumera->CurrentValue = $this->_1_Espumera->FormValue;
		$this->_1_Estufa->CurrentValue = $this->_1_Estufa->FormValue;
		$this->_1_Cuchara_sopa->CurrentValue = $this->_1_Cuchara_sopa->FormValue;
		$this->_1_Recipiente->CurrentValue = $this->_1_Recipiente->FormValue;
		$this->_1_Kit_Repue_estufa->CurrentValue = $this->_1_Kit_Repue_estufa->FormValue;
		$this->_1_Molinillo->CurrentValue = $this->_1_Molinillo->FormValue;
		$this->_1_Olla_36->CurrentValue = $this->_1_Olla_36->FormValue;
		$this->_1_Olla_40->CurrentValue = $this->_1_Olla_40->FormValue;
		$this->_1_Paila_32->CurrentValue = $this->_1_Paila_32->FormValue;
		$this->_1_Paila_36_37->CurrentValue = $this->_1_Paila_36_37->FormValue;
		$this->Camping->CurrentValue = $this->Camping->FormValue;
		$this->_2_Aislante->CurrentValue = $this->_2_Aislante->FormValue;
		$this->_2_Carpa_hamaca->CurrentValue = $this->_2_Carpa_hamaca->FormValue;
		$this->_2_Carpa_rancho->CurrentValue = $this->_2_Carpa_rancho->FormValue;
		$this->_2_Fibra_rollo->CurrentValue = $this->_2_Fibra_rollo->FormValue;
		$this->_2_CAL->CurrentValue = $this->_2_CAL->FormValue;
		$this->_2_Linterna->CurrentValue = $this->_2_Linterna->FormValue;
		$this->_2_Botiquin->CurrentValue = $this->_2_Botiquin->FormValue;
		$this->_2_Mascara_filtro->CurrentValue = $this->_2_Mascara_filtro->FormValue;
		$this->_2_Pimpina->CurrentValue = $this->_2_Pimpina->FormValue;
		$this->_2_SleepingA0->CurrentValue = $this->_2_SleepingA0->FormValue;
		$this->_2_Plastico_negro->CurrentValue = $this->_2_Plastico_negro->FormValue;
		$this->_2_Tula_tropa->CurrentValue = $this->_2_Tula_tropa->FormValue;
		$this->_2_Camilla->CurrentValue = $this->_2_Camilla->FormValue;
		$this->Herramientas->CurrentValue = $this->Herramientas->FormValue;
		$this->_3_Abrazadera->CurrentValue = $this->_3_Abrazadera->FormValue;
		$this->_3_Aspersora->CurrentValue = $this->_3_Aspersora->FormValue;
		$this->_3_Cabo_hacha->CurrentValue = $this->_3_Cabo_hacha->FormValue;
		$this->_3_Funda_machete->CurrentValue = $this->_3_Funda_machete->FormValue;
		$this->_3_Glifosato_4lt->CurrentValue = $this->_3_Glifosato_4lt->FormValue;
		$this->_3_Hacha->CurrentValue = $this->_3_Hacha->FormValue;
		$this->_3_Lima_12_uni->CurrentValue = $this->_3_Lima_12_uni->FormValue;
		$this->_3_Llave_mixta->CurrentValue = $this->_3_Llave_mixta->FormValue;
		$this->_3_Machete->CurrentValue = $this->_3_Machete->FormValue;
		$this->_3_Gafa_traslucida->CurrentValue = $this->_3_Gafa_traslucida->FormValue;
		$this->_3_Motosierra->CurrentValue = $this->_3_Motosierra->FormValue;
		$this->_3_Palin->CurrentValue = $this->_3_Palin->FormValue;
		$this->_3_Tubo_galvanizado->CurrentValue = $this->_3_Tubo_galvanizado->FormValue;
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
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->AD1O->setDbValue($rs->fields('AÑO'));
		$this->FASE->setDbValue($rs->fields('FASE'));
		$this->FECHA_INV->setDbValue($rs->fields('FECHA_INV'));
		$this->TIPO_INV->setDbValue($rs->fields('TIPO_INV'));
		$this->NOM_CAPATAZ->setDbValue($rs->fields('NOM_CAPATAZ'));
		$this->Otro_NOM_CAPAT->setDbValue($rs->fields('Otro_NOM_CAPAT'));
		$this->Otro_CC_CAPAT->setDbValue($rs->fields('Otro_CC_CAPAT'));
		$this->NOM_LUGAR->setDbValue($rs->fields('NOM_LUGAR'));
		$this->Cocina->setDbValue($rs->fields('Cocina'));
		$this->_1_Abrelatas->setDbValue($rs->fields('1_Abrelatas'));
		$this->_1_Balde->setDbValue($rs->fields('1_Balde'));
		$this->_1_Arrocero_50->setDbValue($rs->fields('1_Arrocero_50'));
		$this->_1_Arrocero_44->setDbValue($rs->fields('1_Arrocero_44'));
		$this->_1_Chocolatera->setDbValue($rs->fields('1_Chocolatera'));
		$this->_1_Colador->setDbValue($rs->fields('1_Colador'));
		$this->_1_Cucharon_sopa->setDbValue($rs->fields('1_Cucharon_sopa'));
		$this->_1_Cucharon_arroz->setDbValue($rs->fields('1_Cucharon_arroz'));
		$this->_1_Cuchillo->setDbValue($rs->fields('1_Cuchillo'));
		$this->_1_Embudo->setDbValue($rs->fields('1_Embudo'));
		$this->_1_Espumera->setDbValue($rs->fields('1_Espumera'));
		$this->_1_Estufa->setDbValue($rs->fields('1_Estufa'));
		$this->_1_Cuchara_sopa->setDbValue($rs->fields('1_Cuchara_sopa'));
		$this->_1_Recipiente->setDbValue($rs->fields('1_Recipiente'));
		$this->_1_Kit_Repue_estufa->setDbValue($rs->fields('1_Kit_Repue_estufa'));
		$this->_1_Molinillo->setDbValue($rs->fields('1_Molinillo'));
		$this->_1_Olla_36->setDbValue($rs->fields('1_Olla_36'));
		$this->_1_Olla_40->setDbValue($rs->fields('1_Olla_40'));
		$this->_1_Paila_32->setDbValue($rs->fields('1_Paila_32'));
		$this->_1_Paila_36_37->setDbValue($rs->fields('1_Paila_36_37'));
		$this->Camping->setDbValue($rs->fields('Camping'));
		$this->_2_Aislante->setDbValue($rs->fields('2_Aislante'));
		$this->_2_Carpa_hamaca->setDbValue($rs->fields('2_Carpa_hamaca'));
		$this->_2_Carpa_rancho->setDbValue($rs->fields('2_Carpa_rancho'));
		$this->_2_Fibra_rollo->setDbValue($rs->fields('2_Fibra_rollo'));
		$this->_2_CAL->setDbValue($rs->fields('2_CAL'));
		$this->_2_Linterna->setDbValue($rs->fields('2_Linterna'));
		$this->_2_Botiquin->setDbValue($rs->fields('2_Botiquin'));
		$this->_2_Mascara_filtro->setDbValue($rs->fields('2_Mascara_filtro'));
		$this->_2_Pimpina->setDbValue($rs->fields('2_Pimpina'));
		$this->_2_SleepingA0->setDbValue($rs->fields('2_Sleeping '));
		$this->_2_Plastico_negro->setDbValue($rs->fields('2_Plastico_negro'));
		$this->_2_Tula_tropa->setDbValue($rs->fields('2_Tula_tropa'));
		$this->_2_Camilla->setDbValue($rs->fields('2_Camilla'));
		$this->Herramientas->setDbValue($rs->fields('Herramientas'));
		$this->_3_Abrazadera->setDbValue($rs->fields('3_Abrazadera'));
		$this->_3_Aspersora->setDbValue($rs->fields('3_Aspersora'));
		$this->_3_Cabo_hacha->setDbValue($rs->fields('3_Cabo_hacha'));
		$this->_3_Funda_machete->setDbValue($rs->fields('3_Funda_machete'));
		$this->_3_Glifosato_4lt->setDbValue($rs->fields('3_Glifosato_4lt'));
		$this->_3_Hacha->setDbValue($rs->fields('3_Hacha'));
		$this->_3_Lima_12_uni->setDbValue($rs->fields('3_Lima_12_uni'));
		$this->_3_Llave_mixta->setDbValue($rs->fields('3_Llave_mixta'));
		$this->_3_Machete->setDbValue($rs->fields('3_Machete'));
		$this->_3_Gafa_traslucida->setDbValue($rs->fields('3_Gafa_traslucida'));
		$this->_3_Motosierra->setDbValue($rs->fields('3_Motosierra'));
		$this->_3_Palin->setDbValue($rs->fields('3_Palin'));
		$this->_3_Tubo_galvanizado->setDbValue($rs->fields('3_Tubo_galvanizado'));
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
		$this->NOM_PE->DbValue = $row['NOM_PE'];
		$this->Otro_PE->DbValue = $row['Otro_PE'];
		$this->DIA->DbValue = $row['DIA'];
		$this->MES->DbValue = $row['MES'];
		$this->OBSERVACION->DbValue = $row['OBSERVACION'];
		$this->AD1O->DbValue = $row['AÑO'];
		$this->FASE->DbValue = $row['FASE'];
		$this->FECHA_INV->DbValue = $row['FECHA_INV'];
		$this->TIPO_INV->DbValue = $row['TIPO_INV'];
		$this->NOM_CAPATAZ->DbValue = $row['NOM_CAPATAZ'];
		$this->Otro_NOM_CAPAT->DbValue = $row['Otro_NOM_CAPAT'];
		$this->Otro_CC_CAPAT->DbValue = $row['Otro_CC_CAPAT'];
		$this->NOM_LUGAR->DbValue = $row['NOM_LUGAR'];
		$this->Cocina->DbValue = $row['Cocina'];
		$this->_1_Abrelatas->DbValue = $row['1_Abrelatas'];
		$this->_1_Balde->DbValue = $row['1_Balde'];
		$this->_1_Arrocero_50->DbValue = $row['1_Arrocero_50'];
		$this->_1_Arrocero_44->DbValue = $row['1_Arrocero_44'];
		$this->_1_Chocolatera->DbValue = $row['1_Chocolatera'];
		$this->_1_Colador->DbValue = $row['1_Colador'];
		$this->_1_Cucharon_sopa->DbValue = $row['1_Cucharon_sopa'];
		$this->_1_Cucharon_arroz->DbValue = $row['1_Cucharon_arroz'];
		$this->_1_Cuchillo->DbValue = $row['1_Cuchillo'];
		$this->_1_Embudo->DbValue = $row['1_Embudo'];
		$this->_1_Espumera->DbValue = $row['1_Espumera'];
		$this->_1_Estufa->DbValue = $row['1_Estufa'];
		$this->_1_Cuchara_sopa->DbValue = $row['1_Cuchara_sopa'];
		$this->_1_Recipiente->DbValue = $row['1_Recipiente'];
		$this->_1_Kit_Repue_estufa->DbValue = $row['1_Kit_Repue_estufa'];
		$this->_1_Molinillo->DbValue = $row['1_Molinillo'];
		$this->_1_Olla_36->DbValue = $row['1_Olla_36'];
		$this->_1_Olla_40->DbValue = $row['1_Olla_40'];
		$this->_1_Paila_32->DbValue = $row['1_Paila_32'];
		$this->_1_Paila_36_37->DbValue = $row['1_Paila_36_37'];
		$this->Camping->DbValue = $row['Camping'];
		$this->_2_Aislante->DbValue = $row['2_Aislante'];
		$this->_2_Carpa_hamaca->DbValue = $row['2_Carpa_hamaca'];
		$this->_2_Carpa_rancho->DbValue = $row['2_Carpa_rancho'];
		$this->_2_Fibra_rollo->DbValue = $row['2_Fibra_rollo'];
		$this->_2_CAL->DbValue = $row['2_CAL'];
		$this->_2_Linterna->DbValue = $row['2_Linterna'];
		$this->_2_Botiquin->DbValue = $row['2_Botiquin'];
		$this->_2_Mascara_filtro->DbValue = $row['2_Mascara_filtro'];
		$this->_2_Pimpina->DbValue = $row['2_Pimpina'];
		$this->_2_SleepingA0->DbValue = $row['2_Sleeping '];
		$this->_2_Plastico_negro->DbValue = $row['2_Plastico_negro'];
		$this->_2_Tula_tropa->DbValue = $row['2_Tula_tropa'];
		$this->_2_Camilla->DbValue = $row['2_Camilla'];
		$this->Herramientas->DbValue = $row['Herramientas'];
		$this->_3_Abrazadera->DbValue = $row['3_Abrazadera'];
		$this->_3_Aspersora->DbValue = $row['3_Aspersora'];
		$this->_3_Cabo_hacha->DbValue = $row['3_Cabo_hacha'];
		$this->_3_Funda_machete->DbValue = $row['3_Funda_machete'];
		$this->_3_Glifosato_4lt->DbValue = $row['3_Glifosato_4lt'];
		$this->_3_Hacha->DbValue = $row['3_Hacha'];
		$this->_3_Lima_12_uni->DbValue = $row['3_Lima_12_uni'];
		$this->_3_Llave_mixta->DbValue = $row['3_Llave_mixta'];
		$this->_3_Machete->DbValue = $row['3_Machete'];
		$this->_3_Gafa_traslucida->DbValue = $row['3_Gafa_traslucida'];
		$this->_3_Motosierra->DbValue = $row['3_Motosierra'];
		$this->_3_Palin->DbValue = $row['3_Palin'];
		$this->_3_Tubo_galvanizado->DbValue = $row['3_Tubo_galvanizado'];
		$this->Modificado->DbValue = $row['Modificado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->Cocina->FormValue == $this->Cocina->CurrentValue && is_numeric(ew_StrToFloat($this->Cocina->CurrentValue)))
			$this->Cocina->CurrentValue = ew_StrToFloat($this->Cocina->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Camping->FormValue == $this->Camping->CurrentValue && is_numeric(ew_StrToFloat($this->Camping->CurrentValue)))
			$this->Camping->CurrentValue = ew_StrToFloat($this->Camping->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Herramientas->FormValue == $this->Herramientas->CurrentValue && is_numeric(ew_StrToFloat($this->Herramientas->CurrentValue)))
			$this->Herramientas->CurrentValue = ew_StrToFloat($this->Herramientas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// llave
		// F_Sincron
		// USUARIO
		// Cargo_gme
		// NOM_PE
		// Otro_PE
		// DIA
		// MES
		// OBSERVACION
		// AÑO
		// FASE
		// FECHA_INV
		// TIPO_INV
		// NOM_CAPATAZ
		// Otro_NOM_CAPAT
		// Otro_CC_CAPAT
		// NOM_LUGAR
		// Cocina
		// 1_Abrelatas
		// 1_Balde
		// 1_Arrocero_50
		// 1_Arrocero_44
		// 1_Chocolatera
		// 1_Colador
		// 1_Cucharon_sopa
		// 1_Cucharon_arroz
		// 1_Cuchillo
		// 1_Embudo
		// 1_Espumera
		// 1_Estufa
		// 1_Cuchara_sopa
		// 1_Recipiente
		// 1_Kit_Repue_estufa
		// 1_Molinillo
		// 1_Olla_36
		// 1_Olla_40
		// 1_Paila_32
		// 1_Paila_36_37
		// Camping
		// 2_Aislante
		// 2_Carpa_hamaca
		// 2_Carpa_rancho
		// 2_Fibra_rollo
		// 2_CAL
		// 2_Linterna
		// 2_Botiquin
		// 2_Mascara_filtro
		// 2_Pimpina
		// 2_Sleeping 
		// 2_Plastico_negro
		// 2_Tula_tropa
		// 2_Camilla
		// Herramientas
		// 3_Abrazadera
		// 3_Aspersora
		// 3_Cabo_hacha
		// 3_Funda_machete
		// 3_Glifosato_4lt
		// 3_Hacha
		// 3_Lima_12_uni
		// 3_Llave_mixta
		// 3_Machete
		// 3_Gafa_traslucida
		// 3_Motosierra
		// 3_Palin
		// 3_Tubo_galvanizado
		// Modificado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// llave
			$this->llave->ViewValue = $this->llave->CurrentValue;
			$this->llave->ViewCustomAttributes = "";

			// F_Sincron
			$this->F_Sincron->ViewValue = $this->F_Sincron->CurrentValue;
			$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 5);
			$this->F_Sincron->ViewCustomAttributes = "";

			// USUARIO
			if (strval($this->USUARIO->CurrentValue) <> "") {
				$sFilterWrk = "`USUARIO`" . ew_SearchString("=", $this->USUARIO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
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

			// NOM_PE
			if (strval($this->NOM_PE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PE`" . ew_SearchString("=", $this->NOM_PE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
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

			// Otro_PE
			$this->Otro_PE->ViewValue = $this->Otro_PE->CurrentValue;
			$this->Otro_PE->ViewCustomAttributes = "";

			// DIA
			$this->DIA->ViewValue = $this->DIA->CurrentValue;
			$this->DIA->ViewCustomAttributes = "";

			// MES
			$this->MES->ViewValue = $this->MES->CurrentValue;
			$this->MES->ViewCustomAttributes = "";

			// OBSERVACION
			$this->OBSERVACION->ViewValue = $this->OBSERVACION->CurrentValue;
			$this->OBSERVACION->ViewCustomAttributes = "";

			// AÑO
			if (strval($this->AD1O->CurrentValue) <> "") {
				$sFilterWrk = "`AÑO`" . ew_SearchString("=", $this->AD1O->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->AD1O, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `AÑO` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->AD1O->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->AD1O->ViewValue = $this->AD1O->CurrentValue;
				}
			} else {
				$this->AD1O->ViewValue = NULL;
			}
			$this->AD1O->ViewCustomAttributes = "";

			// FASE
			if (strval($this->FASE->CurrentValue) <> "") {
				$sFilterWrk = "`FASE`" . ew_SearchString("=", $this->FASE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FASE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `FASE`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->FASE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->FASE->ViewValue = $this->FASE->CurrentValue;
				}
			} else {
				$this->FASE->ViewValue = NULL;
			}
			$this->FASE->ViewCustomAttributes = "";

			// FECHA_INV
			$this->FECHA_INV->ViewValue = $this->FECHA_INV->CurrentValue;
			$this->FECHA_INV->ViewValue = ew_FormatDateTime($this->FECHA_INV->ViewValue, 5);
			$this->FECHA_INV->ViewCustomAttributes = "";

			// TIPO_INV
			if (strval($this->TIPO_INV->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->TIPO_INV->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='inv'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TIPO_INV, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->TIPO_INV->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->TIPO_INV->ViewValue = $this->TIPO_INV->CurrentValue;
				}
			} else {
				$this->TIPO_INV->ViewValue = NULL;
			}
			$this->TIPO_INV->ViewCustomAttributes = "";

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->ViewValue = $this->NOM_CAPATAZ->CurrentValue;
			$this->NOM_CAPATAZ->ViewCustomAttributes = "";

			// Otro_NOM_CAPAT
			$this->Otro_NOM_CAPAT->ViewValue = $this->Otro_NOM_CAPAT->CurrentValue;
			$this->Otro_NOM_CAPAT->ViewCustomAttributes = "";

			// Otro_CC_CAPAT
			$this->Otro_CC_CAPAT->ViewValue = $this->Otro_CC_CAPAT->CurrentValue;
			$this->Otro_CC_CAPAT->ViewCustomAttributes = "";

			// NOM_LUGAR
			$this->NOM_LUGAR->ViewValue = $this->NOM_LUGAR->CurrentValue;
			$this->NOM_LUGAR->ViewCustomAttributes = "";

			// Cocina
			$this->Cocina->ViewValue = $this->Cocina->CurrentValue;
			$this->Cocina->ViewCustomAttributes = "";

			// 1_Abrelatas
			$this->_1_Abrelatas->ViewValue = $this->_1_Abrelatas->CurrentValue;
			$this->_1_Abrelatas->ViewCustomAttributes = "";

			// 1_Balde
			$this->_1_Balde->ViewValue = $this->_1_Balde->CurrentValue;
			$this->_1_Balde->ViewCustomAttributes = "";

			// 1_Arrocero_50
			$this->_1_Arrocero_50->ViewValue = $this->_1_Arrocero_50->CurrentValue;
			$this->_1_Arrocero_50->ViewCustomAttributes = "";

			// 1_Arrocero_44
			$this->_1_Arrocero_44->ViewValue = $this->_1_Arrocero_44->CurrentValue;
			$this->_1_Arrocero_44->ViewCustomAttributes = "";

			// 1_Chocolatera
			$this->_1_Chocolatera->ViewValue = $this->_1_Chocolatera->CurrentValue;
			$this->_1_Chocolatera->ViewCustomAttributes = "";

			// 1_Colador
			$this->_1_Colador->ViewValue = $this->_1_Colador->CurrentValue;
			$this->_1_Colador->ViewCustomAttributes = "";

			// 1_Cucharon_sopa
			$this->_1_Cucharon_sopa->ViewValue = $this->_1_Cucharon_sopa->CurrentValue;
			$this->_1_Cucharon_sopa->ViewCustomAttributes = "";

			// 1_Cucharon_arroz
			$this->_1_Cucharon_arroz->ViewValue = $this->_1_Cucharon_arroz->CurrentValue;
			$this->_1_Cucharon_arroz->ViewCustomAttributes = "";

			// 1_Cuchillo
			$this->_1_Cuchillo->ViewValue = $this->_1_Cuchillo->CurrentValue;
			$this->_1_Cuchillo->ViewCustomAttributes = "";

			// 1_Embudo
			$this->_1_Embudo->ViewValue = $this->_1_Embudo->CurrentValue;
			$this->_1_Embudo->ViewCustomAttributes = "";

			// 1_Espumera
			$this->_1_Espumera->ViewValue = $this->_1_Espumera->CurrentValue;
			$this->_1_Espumera->ViewCustomAttributes = "";

			// 1_Estufa
			$this->_1_Estufa->ViewValue = $this->_1_Estufa->CurrentValue;
			$this->_1_Estufa->ViewCustomAttributes = "";

			// 1_Cuchara_sopa
			$this->_1_Cuchara_sopa->ViewValue = $this->_1_Cuchara_sopa->CurrentValue;
			$this->_1_Cuchara_sopa->ViewCustomAttributes = "";

			// 1_Recipiente
			$this->_1_Recipiente->ViewValue = $this->_1_Recipiente->CurrentValue;
			$this->_1_Recipiente->ViewCustomAttributes = "";

			// 1_Kit_Repue_estufa
			$this->_1_Kit_Repue_estufa->ViewValue = $this->_1_Kit_Repue_estufa->CurrentValue;
			$this->_1_Kit_Repue_estufa->ViewCustomAttributes = "";

			// 1_Molinillo
			$this->_1_Molinillo->ViewValue = $this->_1_Molinillo->CurrentValue;
			$this->_1_Molinillo->ViewCustomAttributes = "";

			// 1_Olla_36
			$this->_1_Olla_36->ViewValue = $this->_1_Olla_36->CurrentValue;
			$this->_1_Olla_36->ViewCustomAttributes = "";

			// 1_Olla_40
			$this->_1_Olla_40->ViewValue = $this->_1_Olla_40->CurrentValue;
			$this->_1_Olla_40->ViewCustomAttributes = "";

			// 1_Paila_32
			$this->_1_Paila_32->ViewValue = $this->_1_Paila_32->CurrentValue;
			$this->_1_Paila_32->ViewCustomAttributes = "";

			// 1_Paila_36_37
			$this->_1_Paila_36_37->ViewValue = $this->_1_Paila_36_37->CurrentValue;
			$this->_1_Paila_36_37->ViewCustomAttributes = "";

			// Camping
			$this->Camping->ViewValue = $this->Camping->CurrentValue;
			$this->Camping->ViewCustomAttributes = "";

			// 2_Aislante
			$this->_2_Aislante->ViewValue = $this->_2_Aislante->CurrentValue;
			$this->_2_Aislante->ViewCustomAttributes = "";

			// 2_Carpa_hamaca
			$this->_2_Carpa_hamaca->ViewValue = $this->_2_Carpa_hamaca->CurrentValue;
			$this->_2_Carpa_hamaca->ViewCustomAttributes = "";

			// 2_Carpa_rancho
			$this->_2_Carpa_rancho->ViewValue = $this->_2_Carpa_rancho->CurrentValue;
			$this->_2_Carpa_rancho->ViewCustomAttributes = "";

			// 2_Fibra_rollo
			$this->_2_Fibra_rollo->ViewValue = $this->_2_Fibra_rollo->CurrentValue;
			$this->_2_Fibra_rollo->ViewCustomAttributes = "";

			// 2_CAL
			$this->_2_CAL->ViewValue = $this->_2_CAL->CurrentValue;
			$this->_2_CAL->ViewCustomAttributes = "";

			// 2_Linterna
			$this->_2_Linterna->ViewValue = $this->_2_Linterna->CurrentValue;
			$this->_2_Linterna->ViewCustomAttributes = "";

			// 2_Botiquin
			$this->_2_Botiquin->ViewValue = $this->_2_Botiquin->CurrentValue;
			$this->_2_Botiquin->ViewCustomAttributes = "";

			// 2_Mascara_filtro
			$this->_2_Mascara_filtro->ViewValue = $this->_2_Mascara_filtro->CurrentValue;
			$this->_2_Mascara_filtro->ViewCustomAttributes = "";

			// 2_Pimpina
			$this->_2_Pimpina->ViewValue = $this->_2_Pimpina->CurrentValue;
			$this->_2_Pimpina->ViewCustomAttributes = "";

			// 2_Sleeping 
			$this->_2_SleepingA0->ViewValue = $this->_2_SleepingA0->CurrentValue;
			$this->_2_SleepingA0->ViewCustomAttributes = "";

			// 2_Plastico_negro
			$this->_2_Plastico_negro->ViewValue = $this->_2_Plastico_negro->CurrentValue;
			$this->_2_Plastico_negro->ViewCustomAttributes = "";

			// 2_Tula_tropa
			$this->_2_Tula_tropa->ViewValue = $this->_2_Tula_tropa->CurrentValue;
			$this->_2_Tula_tropa->ViewCustomAttributes = "";

			// 2_Camilla
			$this->_2_Camilla->ViewValue = $this->_2_Camilla->CurrentValue;
			$this->_2_Camilla->ViewCustomAttributes = "";

			// Herramientas
			$this->Herramientas->ViewValue = $this->Herramientas->CurrentValue;
			$this->Herramientas->ViewCustomAttributes = "";

			// 3_Abrazadera
			$this->_3_Abrazadera->ViewValue = $this->_3_Abrazadera->CurrentValue;
			$this->_3_Abrazadera->ViewCustomAttributes = "";

			// 3_Aspersora
			$this->_3_Aspersora->ViewValue = $this->_3_Aspersora->CurrentValue;
			$this->_3_Aspersora->ViewCustomAttributes = "";

			// 3_Cabo_hacha
			$this->_3_Cabo_hacha->ViewValue = $this->_3_Cabo_hacha->CurrentValue;
			$this->_3_Cabo_hacha->ViewCustomAttributes = "";

			// 3_Funda_machete
			$this->_3_Funda_machete->ViewValue = $this->_3_Funda_machete->CurrentValue;
			$this->_3_Funda_machete->ViewCustomAttributes = "";

			// 3_Glifosato_4lt
			$this->_3_Glifosato_4lt->ViewValue = $this->_3_Glifosato_4lt->CurrentValue;
			$this->_3_Glifosato_4lt->ViewCustomAttributes = "";

			// 3_Hacha
			$this->_3_Hacha->ViewValue = $this->_3_Hacha->CurrentValue;
			$this->_3_Hacha->ViewCustomAttributes = "";

			// 3_Lima_12_uni
			$this->_3_Lima_12_uni->ViewValue = $this->_3_Lima_12_uni->CurrentValue;
			$this->_3_Lima_12_uni->ViewCustomAttributes = "";

			// 3_Llave_mixta
			$this->_3_Llave_mixta->ViewValue = $this->_3_Llave_mixta->CurrentValue;
			$this->_3_Llave_mixta->ViewCustomAttributes = "";

			// 3_Machete
			$this->_3_Machete->ViewValue = $this->_3_Machete->CurrentValue;
			$this->_3_Machete->ViewCustomAttributes = "";

			// 3_Gafa_traslucida
			$this->_3_Gafa_traslucida->ViewValue = $this->_3_Gafa_traslucida->CurrentValue;
			$this->_3_Gafa_traslucida->ViewCustomAttributes = "";

			// 3_Motosierra
			$this->_3_Motosierra->ViewValue = $this->_3_Motosierra->CurrentValue;
			$this->_3_Motosierra->ViewCustomAttributes = "";

			// 3_Palin
			$this->_3_Palin->ViewValue = $this->_3_Palin->CurrentValue;
			$this->_3_Palin->ViewCustomAttributes = "";

			// 3_Tubo_galvanizado
			$this->_3_Tubo_galvanizado->ViewValue = $this->_3_Tubo_galvanizado->CurrentValue;
			$this->_3_Tubo_galvanizado->ViewCustomAttributes = "";

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

			// DIA
			$this->DIA->LinkCustomAttributes = "";
			$this->DIA->HrefValue = "";
			$this->DIA->TooltipValue = "";

			// MES
			$this->MES->LinkCustomAttributes = "";
			$this->MES->HrefValue = "";
			$this->MES->TooltipValue = "";

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

			// FECHA_INV
			$this->FECHA_INV->LinkCustomAttributes = "";
			$this->FECHA_INV->HrefValue = "";
			$this->FECHA_INV->TooltipValue = "";

			// TIPO_INV
			$this->TIPO_INV->LinkCustomAttributes = "";
			$this->TIPO_INV->HrefValue = "";
			$this->TIPO_INV->TooltipValue = "";

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->LinkCustomAttributes = "";
			$this->NOM_CAPATAZ->HrefValue = "";
			$this->NOM_CAPATAZ->TooltipValue = "";

			// Otro_NOM_CAPAT
			$this->Otro_NOM_CAPAT->LinkCustomAttributes = "";
			$this->Otro_NOM_CAPAT->HrefValue = "";
			$this->Otro_NOM_CAPAT->TooltipValue = "";

			// Otro_CC_CAPAT
			$this->Otro_CC_CAPAT->LinkCustomAttributes = "";
			$this->Otro_CC_CAPAT->HrefValue = "";
			$this->Otro_CC_CAPAT->TooltipValue = "";

			// NOM_LUGAR
			$this->NOM_LUGAR->LinkCustomAttributes = "";
			$this->NOM_LUGAR->HrefValue = "";
			$this->NOM_LUGAR->TooltipValue = "";

			// Cocina
			$this->Cocina->LinkCustomAttributes = "";
			$this->Cocina->HrefValue = "";
			$this->Cocina->TooltipValue = "";

			// 1_Abrelatas
			$this->_1_Abrelatas->LinkCustomAttributes = "";
			$this->_1_Abrelatas->HrefValue = "";
			$this->_1_Abrelatas->TooltipValue = "";

			// 1_Balde
			$this->_1_Balde->LinkCustomAttributes = "";
			$this->_1_Balde->HrefValue = "";
			$this->_1_Balde->TooltipValue = "";

			// 1_Arrocero_50
			$this->_1_Arrocero_50->LinkCustomAttributes = "";
			$this->_1_Arrocero_50->HrefValue = "";
			$this->_1_Arrocero_50->TooltipValue = "";

			// 1_Arrocero_44
			$this->_1_Arrocero_44->LinkCustomAttributes = "";
			$this->_1_Arrocero_44->HrefValue = "";
			$this->_1_Arrocero_44->TooltipValue = "";

			// 1_Chocolatera
			$this->_1_Chocolatera->LinkCustomAttributes = "";
			$this->_1_Chocolatera->HrefValue = "";
			$this->_1_Chocolatera->TooltipValue = "";

			// 1_Colador
			$this->_1_Colador->LinkCustomAttributes = "";
			$this->_1_Colador->HrefValue = "";
			$this->_1_Colador->TooltipValue = "";

			// 1_Cucharon_sopa
			$this->_1_Cucharon_sopa->LinkCustomAttributes = "";
			$this->_1_Cucharon_sopa->HrefValue = "";
			$this->_1_Cucharon_sopa->TooltipValue = "";

			// 1_Cucharon_arroz
			$this->_1_Cucharon_arroz->LinkCustomAttributes = "";
			$this->_1_Cucharon_arroz->HrefValue = "";
			$this->_1_Cucharon_arroz->TooltipValue = "";

			// 1_Cuchillo
			$this->_1_Cuchillo->LinkCustomAttributes = "";
			$this->_1_Cuchillo->HrefValue = "";
			$this->_1_Cuchillo->TooltipValue = "";

			// 1_Embudo
			$this->_1_Embudo->LinkCustomAttributes = "";
			$this->_1_Embudo->HrefValue = "";
			$this->_1_Embudo->TooltipValue = "";

			// 1_Espumera
			$this->_1_Espumera->LinkCustomAttributes = "";
			$this->_1_Espumera->HrefValue = "";
			$this->_1_Espumera->TooltipValue = "";

			// 1_Estufa
			$this->_1_Estufa->LinkCustomAttributes = "";
			$this->_1_Estufa->HrefValue = "";
			$this->_1_Estufa->TooltipValue = "";

			// 1_Cuchara_sopa
			$this->_1_Cuchara_sopa->LinkCustomAttributes = "";
			$this->_1_Cuchara_sopa->HrefValue = "";
			$this->_1_Cuchara_sopa->TooltipValue = "";

			// 1_Recipiente
			$this->_1_Recipiente->LinkCustomAttributes = "";
			$this->_1_Recipiente->HrefValue = "";
			$this->_1_Recipiente->TooltipValue = "";

			// 1_Kit_Repue_estufa
			$this->_1_Kit_Repue_estufa->LinkCustomAttributes = "";
			$this->_1_Kit_Repue_estufa->HrefValue = "";
			$this->_1_Kit_Repue_estufa->TooltipValue = "";

			// 1_Molinillo
			$this->_1_Molinillo->LinkCustomAttributes = "";
			$this->_1_Molinillo->HrefValue = "";
			$this->_1_Molinillo->TooltipValue = "";

			// 1_Olla_36
			$this->_1_Olla_36->LinkCustomAttributes = "";
			$this->_1_Olla_36->HrefValue = "";
			$this->_1_Olla_36->TooltipValue = "";

			// 1_Olla_40
			$this->_1_Olla_40->LinkCustomAttributes = "";
			$this->_1_Olla_40->HrefValue = "";
			$this->_1_Olla_40->TooltipValue = "";

			// 1_Paila_32
			$this->_1_Paila_32->LinkCustomAttributes = "";
			$this->_1_Paila_32->HrefValue = "";
			$this->_1_Paila_32->TooltipValue = "";

			// 1_Paila_36_37
			$this->_1_Paila_36_37->LinkCustomAttributes = "";
			$this->_1_Paila_36_37->HrefValue = "";
			$this->_1_Paila_36_37->TooltipValue = "";

			// Camping
			$this->Camping->LinkCustomAttributes = "";
			$this->Camping->HrefValue = "";
			$this->Camping->TooltipValue = "";

			// 2_Aislante
			$this->_2_Aislante->LinkCustomAttributes = "";
			$this->_2_Aislante->HrefValue = "";
			$this->_2_Aislante->TooltipValue = "";

			// 2_Carpa_hamaca
			$this->_2_Carpa_hamaca->LinkCustomAttributes = "";
			$this->_2_Carpa_hamaca->HrefValue = "";
			$this->_2_Carpa_hamaca->TooltipValue = "";

			// 2_Carpa_rancho
			$this->_2_Carpa_rancho->LinkCustomAttributes = "";
			$this->_2_Carpa_rancho->HrefValue = "";
			$this->_2_Carpa_rancho->TooltipValue = "";

			// 2_Fibra_rollo
			$this->_2_Fibra_rollo->LinkCustomAttributes = "";
			$this->_2_Fibra_rollo->HrefValue = "";
			$this->_2_Fibra_rollo->TooltipValue = "";

			// 2_CAL
			$this->_2_CAL->LinkCustomAttributes = "";
			$this->_2_CAL->HrefValue = "";
			$this->_2_CAL->TooltipValue = "";

			// 2_Linterna
			$this->_2_Linterna->LinkCustomAttributes = "";
			$this->_2_Linterna->HrefValue = "";
			$this->_2_Linterna->TooltipValue = "";

			// 2_Botiquin
			$this->_2_Botiquin->LinkCustomAttributes = "";
			$this->_2_Botiquin->HrefValue = "";
			$this->_2_Botiquin->TooltipValue = "";

			// 2_Mascara_filtro
			$this->_2_Mascara_filtro->LinkCustomAttributes = "";
			$this->_2_Mascara_filtro->HrefValue = "";
			$this->_2_Mascara_filtro->TooltipValue = "";

			// 2_Pimpina
			$this->_2_Pimpina->LinkCustomAttributes = "";
			$this->_2_Pimpina->HrefValue = "";
			$this->_2_Pimpina->TooltipValue = "";

			// 2_Sleeping 
			$this->_2_SleepingA0->LinkCustomAttributes = "";
			$this->_2_SleepingA0->HrefValue = "";
			$this->_2_SleepingA0->TooltipValue = "";

			// 2_Plastico_negro
			$this->_2_Plastico_negro->LinkCustomAttributes = "";
			$this->_2_Plastico_negro->HrefValue = "";
			$this->_2_Plastico_negro->TooltipValue = "";

			// 2_Tula_tropa
			$this->_2_Tula_tropa->LinkCustomAttributes = "";
			$this->_2_Tula_tropa->HrefValue = "";
			$this->_2_Tula_tropa->TooltipValue = "";

			// 2_Camilla
			$this->_2_Camilla->LinkCustomAttributes = "";
			$this->_2_Camilla->HrefValue = "";
			$this->_2_Camilla->TooltipValue = "";

			// Herramientas
			$this->Herramientas->LinkCustomAttributes = "";
			$this->Herramientas->HrefValue = "";
			$this->Herramientas->TooltipValue = "";

			// 3_Abrazadera
			$this->_3_Abrazadera->LinkCustomAttributes = "";
			$this->_3_Abrazadera->HrefValue = "";
			$this->_3_Abrazadera->TooltipValue = "";

			// 3_Aspersora
			$this->_3_Aspersora->LinkCustomAttributes = "";
			$this->_3_Aspersora->HrefValue = "";
			$this->_3_Aspersora->TooltipValue = "";

			// 3_Cabo_hacha
			$this->_3_Cabo_hacha->LinkCustomAttributes = "";
			$this->_3_Cabo_hacha->HrefValue = "";
			$this->_3_Cabo_hacha->TooltipValue = "";

			// 3_Funda_machete
			$this->_3_Funda_machete->LinkCustomAttributes = "";
			$this->_3_Funda_machete->HrefValue = "";
			$this->_3_Funda_machete->TooltipValue = "";

			// 3_Glifosato_4lt
			$this->_3_Glifosato_4lt->LinkCustomAttributes = "";
			$this->_3_Glifosato_4lt->HrefValue = "";
			$this->_3_Glifosato_4lt->TooltipValue = "";

			// 3_Hacha
			$this->_3_Hacha->LinkCustomAttributes = "";
			$this->_3_Hacha->HrefValue = "";
			$this->_3_Hacha->TooltipValue = "";

			// 3_Lima_12_uni
			$this->_3_Lima_12_uni->LinkCustomAttributes = "";
			$this->_3_Lima_12_uni->HrefValue = "";
			$this->_3_Lima_12_uni->TooltipValue = "";

			// 3_Llave_mixta
			$this->_3_Llave_mixta->LinkCustomAttributes = "";
			$this->_3_Llave_mixta->HrefValue = "";
			$this->_3_Llave_mixta->TooltipValue = "";

			// 3_Machete
			$this->_3_Machete->LinkCustomAttributes = "";
			$this->_3_Machete->HrefValue = "";
			$this->_3_Machete->TooltipValue = "";

			// 3_Gafa_traslucida
			$this->_3_Gafa_traslucida->LinkCustomAttributes = "";
			$this->_3_Gafa_traslucida->HrefValue = "";
			$this->_3_Gafa_traslucida->TooltipValue = "";

			// 3_Motosierra
			$this->_3_Motosierra->LinkCustomAttributes = "";
			$this->_3_Motosierra->HrefValue = "";
			$this->_3_Motosierra->TooltipValue = "";

			// 3_Palin
			$this->_3_Palin->LinkCustomAttributes = "";
			$this->_3_Palin->HrefValue = "";
			$this->_3_Palin->TooltipValue = "";

			// 3_Tubo_galvanizado
			$this->_3_Tubo_galvanizado->LinkCustomAttributes = "";
			$this->_3_Tubo_galvanizado->HrefValue = "";
			$this->_3_Tubo_galvanizado->TooltipValue = "";

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
			$this->F_Sincron->EditValue = ew_FormatDateTime($this->F_Sincron->EditValue, 5);
			$this->F_Sincron->ViewCustomAttributes = "";

			// USUARIO
			$this->USUARIO->EditAttrs["class"] = "form-control";
			$this->USUARIO->EditCustomAttributes = "";
			if (strval($this->USUARIO->CurrentValue) <> "") {
				$sFilterWrk = "`USUARIO`" . ew_SearchString("=", $this->USUARIO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_inv`";
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

			// NOM_PE
			$this->NOM_PE->EditAttrs["class"] = "form-control";
			$this->NOM_PE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
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

			// Otro_PE
			$this->Otro_PE->EditAttrs["class"] = "form-control";
			$this->Otro_PE->EditCustomAttributes = "";
			$this->Otro_PE->EditValue = ew_HtmlEncode($this->Otro_PE->CurrentValue);
			$this->Otro_PE->PlaceHolder = ew_RemoveHtml($this->Otro_PE->FldCaption());

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

			// OBSERVACION
			$this->OBSERVACION->EditAttrs["class"] = "form-control";
			$this->OBSERVACION->EditCustomAttributes = "";
			$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->CurrentValue);
			$this->OBSERVACION->PlaceHolder = ew_RemoveHtml($this->OBSERVACION->FldCaption());

			// AÑO
			$this->AD1O->EditAttrs["class"] = "form-control";
			$this->AD1O->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->AD1O, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `AÑO` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->AD1O->EditValue = $arwrk;

			// FASE
			$this->FASE->EditAttrs["class"] = "form-control";
			$this->FASE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FASE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `FASE`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->FASE->EditValue = $arwrk;

			// FECHA_INV
			$this->FECHA_INV->EditAttrs["class"] = "form-control";
			$this->FECHA_INV->EditCustomAttributes = "";
			$this->FECHA_INV->EditValue = ew_HtmlEncode($this->FECHA_INV->CurrentValue);
			$this->FECHA_INV->PlaceHolder = ew_RemoveHtml($this->FECHA_INV->FldCaption());

			// TIPO_INV
			$this->TIPO_INV->EditAttrs["class"] = "form-control";
			$this->TIPO_INV->EditCustomAttributes = "";
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
			$lookuptblfilter = "`list name`='inv'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TIPO_INV, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->TIPO_INV->EditValue = $arwrk;

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->EditAttrs["class"] = "form-control";
			$this->NOM_CAPATAZ->EditCustomAttributes = "";
			$this->NOM_CAPATAZ->EditValue = ew_HtmlEncode($this->NOM_CAPATAZ->CurrentValue);
			$this->NOM_CAPATAZ->PlaceHolder = ew_RemoveHtml($this->NOM_CAPATAZ->FldCaption());

			// Otro_NOM_CAPAT
			$this->Otro_NOM_CAPAT->EditAttrs["class"] = "form-control";
			$this->Otro_NOM_CAPAT->EditCustomAttributes = "";
			$this->Otro_NOM_CAPAT->EditValue = ew_HtmlEncode($this->Otro_NOM_CAPAT->CurrentValue);
			$this->Otro_NOM_CAPAT->PlaceHolder = ew_RemoveHtml($this->Otro_NOM_CAPAT->FldCaption());

			// Otro_CC_CAPAT
			$this->Otro_CC_CAPAT->EditAttrs["class"] = "form-control";
			$this->Otro_CC_CAPAT->EditCustomAttributes = "";
			$this->Otro_CC_CAPAT->EditValue = ew_HtmlEncode($this->Otro_CC_CAPAT->CurrentValue);
			$this->Otro_CC_CAPAT->PlaceHolder = ew_RemoveHtml($this->Otro_CC_CAPAT->FldCaption());

			// NOM_LUGAR
			$this->NOM_LUGAR->EditAttrs["class"] = "form-control";
			$this->NOM_LUGAR->EditCustomAttributes = "";
			$this->NOM_LUGAR->EditValue = ew_HtmlEncode($this->NOM_LUGAR->CurrentValue);
			$this->NOM_LUGAR->PlaceHolder = ew_RemoveHtml($this->NOM_LUGAR->FldCaption());

			// Cocina
			$this->Cocina->EditAttrs["class"] = "form-control";
			$this->Cocina->EditCustomAttributes = "";
			$this->Cocina->EditValue = ew_HtmlEncode($this->Cocina->CurrentValue);
			$this->Cocina->PlaceHolder = ew_RemoveHtml($this->Cocina->FldCaption());
			if (strval($this->Cocina->EditValue) <> "" && is_numeric($this->Cocina->EditValue)) $this->Cocina->EditValue = ew_FormatNumber($this->Cocina->EditValue, -2, -1, -2, 0);

			// 1_Abrelatas
			$this->_1_Abrelatas->EditAttrs["class"] = "form-control";
			$this->_1_Abrelatas->EditCustomAttributes = "";
			$this->_1_Abrelatas->EditValue = ew_HtmlEncode($this->_1_Abrelatas->CurrentValue);
			$this->_1_Abrelatas->PlaceHolder = ew_RemoveHtml($this->_1_Abrelatas->FldCaption());

			// 1_Balde
			$this->_1_Balde->EditAttrs["class"] = "form-control";
			$this->_1_Balde->EditCustomAttributes = "";
			$this->_1_Balde->EditValue = ew_HtmlEncode($this->_1_Balde->CurrentValue);
			$this->_1_Balde->PlaceHolder = ew_RemoveHtml($this->_1_Balde->FldCaption());

			// 1_Arrocero_50
			$this->_1_Arrocero_50->EditAttrs["class"] = "form-control";
			$this->_1_Arrocero_50->EditCustomAttributes = "";
			$this->_1_Arrocero_50->EditValue = ew_HtmlEncode($this->_1_Arrocero_50->CurrentValue);
			$this->_1_Arrocero_50->PlaceHolder = ew_RemoveHtml($this->_1_Arrocero_50->FldCaption());

			// 1_Arrocero_44
			$this->_1_Arrocero_44->EditAttrs["class"] = "form-control";
			$this->_1_Arrocero_44->EditCustomAttributes = "";
			$this->_1_Arrocero_44->EditValue = ew_HtmlEncode($this->_1_Arrocero_44->CurrentValue);
			$this->_1_Arrocero_44->PlaceHolder = ew_RemoveHtml($this->_1_Arrocero_44->FldCaption());

			// 1_Chocolatera
			$this->_1_Chocolatera->EditAttrs["class"] = "form-control";
			$this->_1_Chocolatera->EditCustomAttributes = "";
			$this->_1_Chocolatera->EditValue = ew_HtmlEncode($this->_1_Chocolatera->CurrentValue);
			$this->_1_Chocolatera->PlaceHolder = ew_RemoveHtml($this->_1_Chocolatera->FldCaption());

			// 1_Colador
			$this->_1_Colador->EditAttrs["class"] = "form-control";
			$this->_1_Colador->EditCustomAttributes = "";
			$this->_1_Colador->EditValue = ew_HtmlEncode($this->_1_Colador->CurrentValue);
			$this->_1_Colador->PlaceHolder = ew_RemoveHtml($this->_1_Colador->FldCaption());

			// 1_Cucharon_sopa
			$this->_1_Cucharon_sopa->EditAttrs["class"] = "form-control";
			$this->_1_Cucharon_sopa->EditCustomAttributes = "";
			$this->_1_Cucharon_sopa->EditValue = ew_HtmlEncode($this->_1_Cucharon_sopa->CurrentValue);
			$this->_1_Cucharon_sopa->PlaceHolder = ew_RemoveHtml($this->_1_Cucharon_sopa->FldCaption());

			// 1_Cucharon_arroz
			$this->_1_Cucharon_arroz->EditAttrs["class"] = "form-control";
			$this->_1_Cucharon_arroz->EditCustomAttributes = "";
			$this->_1_Cucharon_arroz->EditValue = ew_HtmlEncode($this->_1_Cucharon_arroz->CurrentValue);
			$this->_1_Cucharon_arroz->PlaceHolder = ew_RemoveHtml($this->_1_Cucharon_arroz->FldCaption());

			// 1_Cuchillo
			$this->_1_Cuchillo->EditAttrs["class"] = "form-control";
			$this->_1_Cuchillo->EditCustomAttributes = "";
			$this->_1_Cuchillo->EditValue = ew_HtmlEncode($this->_1_Cuchillo->CurrentValue);
			$this->_1_Cuchillo->PlaceHolder = ew_RemoveHtml($this->_1_Cuchillo->FldCaption());

			// 1_Embudo
			$this->_1_Embudo->EditAttrs["class"] = "form-control";
			$this->_1_Embudo->EditCustomAttributes = "";
			$this->_1_Embudo->EditValue = ew_HtmlEncode($this->_1_Embudo->CurrentValue);
			$this->_1_Embudo->PlaceHolder = ew_RemoveHtml($this->_1_Embudo->FldCaption());

			// 1_Espumera
			$this->_1_Espumera->EditAttrs["class"] = "form-control";
			$this->_1_Espumera->EditCustomAttributes = "";
			$this->_1_Espumera->EditValue = ew_HtmlEncode($this->_1_Espumera->CurrentValue);
			$this->_1_Espumera->PlaceHolder = ew_RemoveHtml($this->_1_Espumera->FldCaption());

			// 1_Estufa
			$this->_1_Estufa->EditAttrs["class"] = "form-control";
			$this->_1_Estufa->EditCustomAttributes = "";
			$this->_1_Estufa->EditValue = ew_HtmlEncode($this->_1_Estufa->CurrentValue);
			$this->_1_Estufa->PlaceHolder = ew_RemoveHtml($this->_1_Estufa->FldCaption());

			// 1_Cuchara_sopa
			$this->_1_Cuchara_sopa->EditAttrs["class"] = "form-control";
			$this->_1_Cuchara_sopa->EditCustomAttributes = "";
			$this->_1_Cuchara_sopa->EditValue = ew_HtmlEncode($this->_1_Cuchara_sopa->CurrentValue);
			$this->_1_Cuchara_sopa->PlaceHolder = ew_RemoveHtml($this->_1_Cuchara_sopa->FldCaption());

			// 1_Recipiente
			$this->_1_Recipiente->EditAttrs["class"] = "form-control";
			$this->_1_Recipiente->EditCustomAttributes = "";
			$this->_1_Recipiente->EditValue = ew_HtmlEncode($this->_1_Recipiente->CurrentValue);
			$this->_1_Recipiente->PlaceHolder = ew_RemoveHtml($this->_1_Recipiente->FldCaption());

			// 1_Kit_Repue_estufa
			$this->_1_Kit_Repue_estufa->EditAttrs["class"] = "form-control";
			$this->_1_Kit_Repue_estufa->EditCustomAttributes = "";
			$this->_1_Kit_Repue_estufa->EditValue = ew_HtmlEncode($this->_1_Kit_Repue_estufa->CurrentValue);
			$this->_1_Kit_Repue_estufa->PlaceHolder = ew_RemoveHtml($this->_1_Kit_Repue_estufa->FldCaption());

			// 1_Molinillo
			$this->_1_Molinillo->EditAttrs["class"] = "form-control";
			$this->_1_Molinillo->EditCustomAttributes = "";
			$this->_1_Molinillo->EditValue = ew_HtmlEncode($this->_1_Molinillo->CurrentValue);
			$this->_1_Molinillo->PlaceHolder = ew_RemoveHtml($this->_1_Molinillo->FldCaption());

			// 1_Olla_36
			$this->_1_Olla_36->EditAttrs["class"] = "form-control";
			$this->_1_Olla_36->EditCustomAttributes = "";
			$this->_1_Olla_36->EditValue = ew_HtmlEncode($this->_1_Olla_36->CurrentValue);
			$this->_1_Olla_36->PlaceHolder = ew_RemoveHtml($this->_1_Olla_36->FldCaption());

			// 1_Olla_40
			$this->_1_Olla_40->EditAttrs["class"] = "form-control";
			$this->_1_Olla_40->EditCustomAttributes = "";
			$this->_1_Olla_40->EditValue = ew_HtmlEncode($this->_1_Olla_40->CurrentValue);
			$this->_1_Olla_40->PlaceHolder = ew_RemoveHtml($this->_1_Olla_40->FldCaption());

			// 1_Paila_32
			$this->_1_Paila_32->EditAttrs["class"] = "form-control";
			$this->_1_Paila_32->EditCustomAttributes = "";
			$this->_1_Paila_32->EditValue = ew_HtmlEncode($this->_1_Paila_32->CurrentValue);
			$this->_1_Paila_32->PlaceHolder = ew_RemoveHtml($this->_1_Paila_32->FldCaption());

			// 1_Paila_36_37
			$this->_1_Paila_36_37->EditAttrs["class"] = "form-control";
			$this->_1_Paila_36_37->EditCustomAttributes = "";
			$this->_1_Paila_36_37->EditValue = ew_HtmlEncode($this->_1_Paila_36_37->CurrentValue);
			$this->_1_Paila_36_37->PlaceHolder = ew_RemoveHtml($this->_1_Paila_36_37->FldCaption());

			// Camping
			$this->Camping->EditAttrs["class"] = "form-control";
			$this->Camping->EditCustomAttributes = "";
			$this->Camping->EditValue = $this->Camping->CurrentValue;
			$this->Camping->ViewCustomAttributes = "";

			// 2_Aislante
			$this->_2_Aislante->EditAttrs["class"] = "form-control";
			$this->_2_Aislante->EditCustomAttributes = "";
			$this->_2_Aislante->EditValue = ew_HtmlEncode($this->_2_Aislante->CurrentValue);
			$this->_2_Aislante->PlaceHolder = ew_RemoveHtml($this->_2_Aislante->FldCaption());

			// 2_Carpa_hamaca
			$this->_2_Carpa_hamaca->EditAttrs["class"] = "form-control";
			$this->_2_Carpa_hamaca->EditCustomAttributes = "";
			$this->_2_Carpa_hamaca->EditValue = ew_HtmlEncode($this->_2_Carpa_hamaca->CurrentValue);
			$this->_2_Carpa_hamaca->PlaceHolder = ew_RemoveHtml($this->_2_Carpa_hamaca->FldCaption());

			// 2_Carpa_rancho
			$this->_2_Carpa_rancho->EditAttrs["class"] = "form-control";
			$this->_2_Carpa_rancho->EditCustomAttributes = "";
			$this->_2_Carpa_rancho->EditValue = ew_HtmlEncode($this->_2_Carpa_rancho->CurrentValue);
			$this->_2_Carpa_rancho->PlaceHolder = ew_RemoveHtml($this->_2_Carpa_rancho->FldCaption());

			// 2_Fibra_rollo
			$this->_2_Fibra_rollo->EditAttrs["class"] = "form-control";
			$this->_2_Fibra_rollo->EditCustomAttributes = "";
			$this->_2_Fibra_rollo->EditValue = ew_HtmlEncode($this->_2_Fibra_rollo->CurrentValue);
			$this->_2_Fibra_rollo->PlaceHolder = ew_RemoveHtml($this->_2_Fibra_rollo->FldCaption());

			// 2_CAL
			$this->_2_CAL->EditAttrs["class"] = "form-control";
			$this->_2_CAL->EditCustomAttributes = "";
			$this->_2_CAL->EditValue = ew_HtmlEncode($this->_2_CAL->CurrentValue);
			$this->_2_CAL->PlaceHolder = ew_RemoveHtml($this->_2_CAL->FldCaption());

			// 2_Linterna
			$this->_2_Linterna->EditAttrs["class"] = "form-control";
			$this->_2_Linterna->EditCustomAttributes = "";
			$this->_2_Linterna->EditValue = ew_HtmlEncode($this->_2_Linterna->CurrentValue);
			$this->_2_Linterna->PlaceHolder = ew_RemoveHtml($this->_2_Linterna->FldCaption());

			// 2_Botiquin
			$this->_2_Botiquin->EditAttrs["class"] = "form-control";
			$this->_2_Botiquin->EditCustomAttributes = "";
			$this->_2_Botiquin->EditValue = ew_HtmlEncode($this->_2_Botiquin->CurrentValue);
			$this->_2_Botiquin->PlaceHolder = ew_RemoveHtml($this->_2_Botiquin->FldCaption());

			// 2_Mascara_filtro
			$this->_2_Mascara_filtro->EditAttrs["class"] = "form-control";
			$this->_2_Mascara_filtro->EditCustomAttributes = "";
			$this->_2_Mascara_filtro->EditValue = ew_HtmlEncode($this->_2_Mascara_filtro->CurrentValue);
			$this->_2_Mascara_filtro->PlaceHolder = ew_RemoveHtml($this->_2_Mascara_filtro->FldCaption());

			// 2_Pimpina
			$this->_2_Pimpina->EditAttrs["class"] = "form-control";
			$this->_2_Pimpina->EditCustomAttributes = "";
			$this->_2_Pimpina->EditValue = ew_HtmlEncode($this->_2_Pimpina->CurrentValue);
			$this->_2_Pimpina->PlaceHolder = ew_RemoveHtml($this->_2_Pimpina->FldCaption());

			// 2_Sleeping 
			$this->_2_SleepingA0->EditAttrs["class"] = "form-control";
			$this->_2_SleepingA0->EditCustomAttributes = "";
			$this->_2_SleepingA0->EditValue = ew_HtmlEncode($this->_2_SleepingA0->CurrentValue);
			$this->_2_SleepingA0->PlaceHolder = ew_RemoveHtml($this->_2_SleepingA0->FldCaption());

			// 2_Plastico_negro
			$this->_2_Plastico_negro->EditAttrs["class"] = "form-control";
			$this->_2_Plastico_negro->EditCustomAttributes = "";
			$this->_2_Plastico_negro->EditValue = ew_HtmlEncode($this->_2_Plastico_negro->CurrentValue);
			$this->_2_Plastico_negro->PlaceHolder = ew_RemoveHtml($this->_2_Plastico_negro->FldCaption());

			// 2_Tula_tropa
			$this->_2_Tula_tropa->EditAttrs["class"] = "form-control";
			$this->_2_Tula_tropa->EditCustomAttributes = "";
			$this->_2_Tula_tropa->EditValue = ew_HtmlEncode($this->_2_Tula_tropa->CurrentValue);
			$this->_2_Tula_tropa->PlaceHolder = ew_RemoveHtml($this->_2_Tula_tropa->FldCaption());

			// 2_Camilla
			$this->_2_Camilla->EditAttrs["class"] = "form-control";
			$this->_2_Camilla->EditCustomAttributes = "";
			$this->_2_Camilla->EditValue = ew_HtmlEncode($this->_2_Camilla->CurrentValue);
			$this->_2_Camilla->PlaceHolder = ew_RemoveHtml($this->_2_Camilla->FldCaption());

			// Herramientas
			$this->Herramientas->EditAttrs["class"] = "form-control";
			$this->Herramientas->EditCustomAttributes = "";
			$this->Herramientas->EditValue = ew_HtmlEncode($this->Herramientas->CurrentValue);
			$this->Herramientas->PlaceHolder = ew_RemoveHtml($this->Herramientas->FldCaption());
			if (strval($this->Herramientas->EditValue) <> "" && is_numeric($this->Herramientas->EditValue)) $this->Herramientas->EditValue = ew_FormatNumber($this->Herramientas->EditValue, -2, -1, -2, 0);

			// 3_Abrazadera
			$this->_3_Abrazadera->EditAttrs["class"] = "form-control";
			$this->_3_Abrazadera->EditCustomAttributes = "";
			$this->_3_Abrazadera->EditValue = $this->_3_Abrazadera->CurrentValue;
			$this->_3_Abrazadera->ViewCustomAttributes = "";

			// 3_Aspersora
			$this->_3_Aspersora->EditAttrs["class"] = "form-control";
			$this->_3_Aspersora->EditCustomAttributes = "";
			$this->_3_Aspersora->EditValue = ew_HtmlEncode($this->_3_Aspersora->CurrentValue);
			$this->_3_Aspersora->PlaceHolder = ew_RemoveHtml($this->_3_Aspersora->FldCaption());

			// 3_Cabo_hacha
			$this->_3_Cabo_hacha->EditAttrs["class"] = "form-control";
			$this->_3_Cabo_hacha->EditCustomAttributes = "";
			$this->_3_Cabo_hacha->EditValue = ew_HtmlEncode($this->_3_Cabo_hacha->CurrentValue);
			$this->_3_Cabo_hacha->PlaceHolder = ew_RemoveHtml($this->_3_Cabo_hacha->FldCaption());

			// 3_Funda_machete
			$this->_3_Funda_machete->EditAttrs["class"] = "form-control";
			$this->_3_Funda_machete->EditCustomAttributes = "";
			$this->_3_Funda_machete->EditValue = ew_HtmlEncode($this->_3_Funda_machete->CurrentValue);
			$this->_3_Funda_machete->PlaceHolder = ew_RemoveHtml($this->_3_Funda_machete->FldCaption());

			// 3_Glifosato_4lt
			$this->_3_Glifosato_4lt->EditAttrs["class"] = "form-control";
			$this->_3_Glifosato_4lt->EditCustomAttributes = "";
			$this->_3_Glifosato_4lt->EditValue = ew_HtmlEncode($this->_3_Glifosato_4lt->CurrentValue);
			$this->_3_Glifosato_4lt->PlaceHolder = ew_RemoveHtml($this->_3_Glifosato_4lt->FldCaption());

			// 3_Hacha
			$this->_3_Hacha->EditAttrs["class"] = "form-control";
			$this->_3_Hacha->EditCustomAttributes = "";
			$this->_3_Hacha->EditValue = ew_HtmlEncode($this->_3_Hacha->CurrentValue);
			$this->_3_Hacha->PlaceHolder = ew_RemoveHtml($this->_3_Hacha->FldCaption());

			// 3_Lima_12_uni
			$this->_3_Lima_12_uni->EditAttrs["class"] = "form-control";
			$this->_3_Lima_12_uni->EditCustomAttributes = "";
			$this->_3_Lima_12_uni->EditValue = ew_HtmlEncode($this->_3_Lima_12_uni->CurrentValue);
			$this->_3_Lima_12_uni->PlaceHolder = ew_RemoveHtml($this->_3_Lima_12_uni->FldCaption());

			// 3_Llave_mixta
			$this->_3_Llave_mixta->EditAttrs["class"] = "form-control";
			$this->_3_Llave_mixta->EditCustomAttributes = "";
			$this->_3_Llave_mixta->EditValue = ew_HtmlEncode($this->_3_Llave_mixta->CurrentValue);
			$this->_3_Llave_mixta->PlaceHolder = ew_RemoveHtml($this->_3_Llave_mixta->FldCaption());

			// 3_Machete
			$this->_3_Machete->EditAttrs["class"] = "form-control";
			$this->_3_Machete->EditCustomAttributes = "";
			$this->_3_Machete->EditValue = ew_HtmlEncode($this->_3_Machete->CurrentValue);
			$this->_3_Machete->PlaceHolder = ew_RemoveHtml($this->_3_Machete->FldCaption());

			// 3_Gafa_traslucida
			$this->_3_Gafa_traslucida->EditAttrs["class"] = "form-control";
			$this->_3_Gafa_traslucida->EditCustomAttributes = "";
			$this->_3_Gafa_traslucida->EditValue = ew_HtmlEncode($this->_3_Gafa_traslucida->CurrentValue);
			$this->_3_Gafa_traslucida->PlaceHolder = ew_RemoveHtml($this->_3_Gafa_traslucida->FldCaption());

			// 3_Motosierra
			$this->_3_Motosierra->EditAttrs["class"] = "form-control";
			$this->_3_Motosierra->EditCustomAttributes = "";
			$this->_3_Motosierra->EditValue = ew_HtmlEncode($this->_3_Motosierra->CurrentValue);
			$this->_3_Motosierra->PlaceHolder = ew_RemoveHtml($this->_3_Motosierra->FldCaption());

			// 3_Palin
			$this->_3_Palin->EditAttrs["class"] = "form-control";
			$this->_3_Palin->EditCustomAttributes = "";
			$this->_3_Palin->EditValue = ew_HtmlEncode($this->_3_Palin->CurrentValue);
			$this->_3_Palin->PlaceHolder = ew_RemoveHtml($this->_3_Palin->FldCaption());

			// 3_Tubo_galvanizado
			$this->_3_Tubo_galvanizado->EditAttrs["class"] = "form-control";
			$this->_3_Tubo_galvanizado->EditCustomAttributes = "";
			$this->_3_Tubo_galvanizado->EditValue = ew_HtmlEncode($this->_3_Tubo_galvanizado->CurrentValue);
			$this->_3_Tubo_galvanizado->PlaceHolder = ew_RemoveHtml($this->_3_Tubo_galvanizado->FldCaption());

			// Modificado
			$this->Modificado->EditAttrs["class"] = "form-control";
			$this->Modificado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Modificado->FldTagValue(1), $this->Modificado->FldTagCaption(1) <> "" ? $this->Modificado->FldTagCaption(1) : $this->Modificado->FldTagValue(1));
			$arwrk[] = array($this->Modificado->FldTagValue(2), $this->Modificado->FldTagCaption(2) <> "" ? $this->Modificado->FldTagCaption(2) : $this->Modificado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Modificado->EditValue = $arwrk;

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

			// DIA
			$this->DIA->HrefValue = "";

			// MES
			$this->MES->HrefValue = "";

			// OBSERVACION
			$this->OBSERVACION->HrefValue = "";

			// AÑO
			$this->AD1O->HrefValue = "";

			// FASE
			$this->FASE->HrefValue = "";

			// FECHA_INV
			$this->FECHA_INV->HrefValue = "";

			// TIPO_INV
			$this->TIPO_INV->HrefValue = "";

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->HrefValue = "";

			// Otro_NOM_CAPAT
			$this->Otro_NOM_CAPAT->HrefValue = "";

			// Otro_CC_CAPAT
			$this->Otro_CC_CAPAT->HrefValue = "";

			// NOM_LUGAR
			$this->NOM_LUGAR->HrefValue = "";

			// Cocina
			$this->Cocina->HrefValue = "";

			// 1_Abrelatas
			$this->_1_Abrelatas->HrefValue = "";

			// 1_Balde
			$this->_1_Balde->HrefValue = "";

			// 1_Arrocero_50
			$this->_1_Arrocero_50->HrefValue = "";

			// 1_Arrocero_44
			$this->_1_Arrocero_44->HrefValue = "";

			// 1_Chocolatera
			$this->_1_Chocolatera->HrefValue = "";

			// 1_Colador
			$this->_1_Colador->HrefValue = "";

			// 1_Cucharon_sopa
			$this->_1_Cucharon_sopa->HrefValue = "";

			// 1_Cucharon_arroz
			$this->_1_Cucharon_arroz->HrefValue = "";

			// 1_Cuchillo
			$this->_1_Cuchillo->HrefValue = "";

			// 1_Embudo
			$this->_1_Embudo->HrefValue = "";

			// 1_Espumera
			$this->_1_Espumera->HrefValue = "";

			// 1_Estufa
			$this->_1_Estufa->HrefValue = "";

			// 1_Cuchara_sopa
			$this->_1_Cuchara_sopa->HrefValue = "";

			// 1_Recipiente
			$this->_1_Recipiente->HrefValue = "";

			// 1_Kit_Repue_estufa
			$this->_1_Kit_Repue_estufa->HrefValue = "";

			// 1_Molinillo
			$this->_1_Molinillo->HrefValue = "";

			// 1_Olla_36
			$this->_1_Olla_36->HrefValue = "";

			// 1_Olla_40
			$this->_1_Olla_40->HrefValue = "";

			// 1_Paila_32
			$this->_1_Paila_32->HrefValue = "";

			// 1_Paila_36_37
			$this->_1_Paila_36_37->HrefValue = "";

			// Camping
			$this->Camping->HrefValue = "";

			// 2_Aislante
			$this->_2_Aislante->HrefValue = "";

			// 2_Carpa_hamaca
			$this->_2_Carpa_hamaca->HrefValue = "";

			// 2_Carpa_rancho
			$this->_2_Carpa_rancho->HrefValue = "";

			// 2_Fibra_rollo
			$this->_2_Fibra_rollo->HrefValue = "";

			// 2_CAL
			$this->_2_CAL->HrefValue = "";

			// 2_Linterna
			$this->_2_Linterna->HrefValue = "";

			// 2_Botiquin
			$this->_2_Botiquin->HrefValue = "";

			// 2_Mascara_filtro
			$this->_2_Mascara_filtro->HrefValue = "";

			// 2_Pimpina
			$this->_2_Pimpina->HrefValue = "";

			// 2_Sleeping 
			$this->_2_SleepingA0->HrefValue = "";

			// 2_Plastico_negro
			$this->_2_Plastico_negro->HrefValue = "";

			// 2_Tula_tropa
			$this->_2_Tula_tropa->HrefValue = "";

			// 2_Camilla
			$this->_2_Camilla->HrefValue = "";

			// Herramientas
			$this->Herramientas->HrefValue = "";

			// 3_Abrazadera
			$this->_3_Abrazadera->HrefValue = "";

			// 3_Aspersora
			$this->_3_Aspersora->HrefValue = "";

			// 3_Cabo_hacha
			$this->_3_Cabo_hacha->HrefValue = "";

			// 3_Funda_machete
			$this->_3_Funda_machete->HrefValue = "";

			// 3_Glifosato_4lt
			$this->_3_Glifosato_4lt->HrefValue = "";

			// 3_Hacha
			$this->_3_Hacha->HrefValue = "";

			// 3_Lima_12_uni
			$this->_3_Lima_12_uni->HrefValue = "";

			// 3_Llave_mixta
			$this->_3_Llave_mixta->HrefValue = "";

			// 3_Machete
			$this->_3_Machete->HrefValue = "";

			// 3_Gafa_traslucida
			$this->_3_Gafa_traslucida->HrefValue = "";

			// 3_Motosierra
			$this->_3_Motosierra->HrefValue = "";

			// 3_Palin
			$this->_3_Palin->HrefValue = "";

			// 3_Tubo_galvanizado
			$this->_3_Tubo_galvanizado->HrefValue = "";

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
		if (!ew_CheckNumber($this->Cocina->FormValue)) {
			ew_AddMessage($gsFormError, $this->Cocina->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Abrelatas->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Abrelatas->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Balde->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Balde->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Arrocero_50->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Arrocero_50->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Arrocero_44->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Arrocero_44->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Chocolatera->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Chocolatera->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Colador->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Colador->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Cucharon_sopa->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Cucharon_sopa->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Cucharon_arroz->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Cucharon_arroz->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Cuchillo->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Cuchillo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Embudo->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Embudo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Espumera->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Espumera->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Estufa->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Estufa->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Cuchara_sopa->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Cuchara_sopa->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Recipiente->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Recipiente->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Kit_Repue_estufa->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Kit_Repue_estufa->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Molinillo->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Molinillo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Olla_36->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Olla_36->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Olla_40->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Olla_40->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Paila_32->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Paila_32->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_1_Paila_36_37->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Paila_36_37->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Aislante->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Aislante->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Carpa_hamaca->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Carpa_hamaca->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Carpa_rancho->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Carpa_rancho->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Fibra_rollo->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Fibra_rollo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_CAL->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_CAL->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Linterna->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Linterna->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Botiquin->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Botiquin->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Mascara_filtro->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Mascara_filtro->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Pimpina->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Pimpina->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_SleepingA0->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_SleepingA0->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Plastico_negro->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Plastico_negro->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Tula_tropa->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Tula_tropa->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_2_Camilla->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Camilla->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Herramientas->FormValue)) {
			ew_AddMessage($gsFormError, $this->Herramientas->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Aspersora->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Aspersora->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Cabo_hacha->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Cabo_hacha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Funda_machete->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Funda_machete->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Glifosato_4lt->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Glifosato_4lt->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Hacha->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Hacha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Lima_12_uni->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Lima_12_uni->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Llave_mixta->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Llave_mixta->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Machete->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Machete->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Gafa_traslucida->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Gafa_traslucida->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Motosierra->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Motosierra->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Palin->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Palin->FldErrMsg());
		}
		if (!ew_CheckInteger($this->_3_Tubo_galvanizado->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Tubo_galvanizado->FldErrMsg());
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

			// NOM_PE
			$this->NOM_PE->SetDbValueDef($rsnew, $this->NOM_PE->CurrentValue, NULL, $this->NOM_PE->ReadOnly);

			// Otro_PE
			$this->Otro_PE->SetDbValueDef($rsnew, $this->Otro_PE->CurrentValue, NULL, $this->Otro_PE->ReadOnly);

			// DIA
			$this->DIA->SetDbValueDef($rsnew, $this->DIA->CurrentValue, NULL, $this->DIA->ReadOnly);

			// MES
			$this->MES->SetDbValueDef($rsnew, $this->MES->CurrentValue, NULL, $this->MES->ReadOnly);

			// OBSERVACION
			$this->OBSERVACION->SetDbValueDef($rsnew, $this->OBSERVACION->CurrentValue, NULL, $this->OBSERVACION->ReadOnly);

			// AÑO
			$this->AD1O->SetDbValueDef($rsnew, $this->AD1O->CurrentValue, NULL, $this->AD1O->ReadOnly);

			// FASE
			$this->FASE->SetDbValueDef($rsnew, $this->FASE->CurrentValue, NULL, $this->FASE->ReadOnly);

			// FECHA_INV
			$this->FECHA_INV->SetDbValueDef($rsnew, $this->FECHA_INV->CurrentValue, NULL, $this->FECHA_INV->ReadOnly);

			// TIPO_INV
			$this->TIPO_INV->SetDbValueDef($rsnew, $this->TIPO_INV->CurrentValue, NULL, $this->TIPO_INV->ReadOnly);

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->SetDbValueDef($rsnew, $this->NOM_CAPATAZ->CurrentValue, NULL, $this->NOM_CAPATAZ->ReadOnly);

			// Otro_NOM_CAPAT
			$this->Otro_NOM_CAPAT->SetDbValueDef($rsnew, $this->Otro_NOM_CAPAT->CurrentValue, NULL, $this->Otro_NOM_CAPAT->ReadOnly);

			// Otro_CC_CAPAT
			$this->Otro_CC_CAPAT->SetDbValueDef($rsnew, $this->Otro_CC_CAPAT->CurrentValue, NULL, $this->Otro_CC_CAPAT->ReadOnly);

			// NOM_LUGAR
			$this->NOM_LUGAR->SetDbValueDef($rsnew, $this->NOM_LUGAR->CurrentValue, NULL, $this->NOM_LUGAR->ReadOnly);

			// Cocina
			$this->Cocina->SetDbValueDef($rsnew, $this->Cocina->CurrentValue, NULL, $this->Cocina->ReadOnly);

			// 1_Abrelatas
			$this->_1_Abrelatas->SetDbValueDef($rsnew, $this->_1_Abrelatas->CurrentValue, NULL, $this->_1_Abrelatas->ReadOnly);

			// 1_Balde
			$this->_1_Balde->SetDbValueDef($rsnew, $this->_1_Balde->CurrentValue, NULL, $this->_1_Balde->ReadOnly);

			// 1_Arrocero_50
			$this->_1_Arrocero_50->SetDbValueDef($rsnew, $this->_1_Arrocero_50->CurrentValue, NULL, $this->_1_Arrocero_50->ReadOnly);

			// 1_Arrocero_44
			$this->_1_Arrocero_44->SetDbValueDef($rsnew, $this->_1_Arrocero_44->CurrentValue, NULL, $this->_1_Arrocero_44->ReadOnly);

			// 1_Chocolatera
			$this->_1_Chocolatera->SetDbValueDef($rsnew, $this->_1_Chocolatera->CurrentValue, NULL, $this->_1_Chocolatera->ReadOnly);

			// 1_Colador
			$this->_1_Colador->SetDbValueDef($rsnew, $this->_1_Colador->CurrentValue, NULL, $this->_1_Colador->ReadOnly);

			// 1_Cucharon_sopa
			$this->_1_Cucharon_sopa->SetDbValueDef($rsnew, $this->_1_Cucharon_sopa->CurrentValue, NULL, $this->_1_Cucharon_sopa->ReadOnly);

			// 1_Cucharon_arroz
			$this->_1_Cucharon_arroz->SetDbValueDef($rsnew, $this->_1_Cucharon_arroz->CurrentValue, NULL, $this->_1_Cucharon_arroz->ReadOnly);

			// 1_Cuchillo
			$this->_1_Cuchillo->SetDbValueDef($rsnew, $this->_1_Cuchillo->CurrentValue, NULL, $this->_1_Cuchillo->ReadOnly);

			// 1_Embudo
			$this->_1_Embudo->SetDbValueDef($rsnew, $this->_1_Embudo->CurrentValue, NULL, $this->_1_Embudo->ReadOnly);

			// 1_Espumera
			$this->_1_Espumera->SetDbValueDef($rsnew, $this->_1_Espumera->CurrentValue, NULL, $this->_1_Espumera->ReadOnly);

			// 1_Estufa
			$this->_1_Estufa->SetDbValueDef($rsnew, $this->_1_Estufa->CurrentValue, NULL, $this->_1_Estufa->ReadOnly);

			// 1_Cuchara_sopa
			$this->_1_Cuchara_sopa->SetDbValueDef($rsnew, $this->_1_Cuchara_sopa->CurrentValue, NULL, $this->_1_Cuchara_sopa->ReadOnly);

			// 1_Recipiente
			$this->_1_Recipiente->SetDbValueDef($rsnew, $this->_1_Recipiente->CurrentValue, NULL, $this->_1_Recipiente->ReadOnly);

			// 1_Kit_Repue_estufa
			$this->_1_Kit_Repue_estufa->SetDbValueDef($rsnew, $this->_1_Kit_Repue_estufa->CurrentValue, NULL, $this->_1_Kit_Repue_estufa->ReadOnly);

			// 1_Molinillo
			$this->_1_Molinillo->SetDbValueDef($rsnew, $this->_1_Molinillo->CurrentValue, NULL, $this->_1_Molinillo->ReadOnly);

			// 1_Olla_36
			$this->_1_Olla_36->SetDbValueDef($rsnew, $this->_1_Olla_36->CurrentValue, NULL, $this->_1_Olla_36->ReadOnly);

			// 1_Olla_40
			$this->_1_Olla_40->SetDbValueDef($rsnew, $this->_1_Olla_40->CurrentValue, NULL, $this->_1_Olla_40->ReadOnly);

			// 1_Paila_32
			$this->_1_Paila_32->SetDbValueDef($rsnew, $this->_1_Paila_32->CurrentValue, NULL, $this->_1_Paila_32->ReadOnly);

			// 1_Paila_36_37
			$this->_1_Paila_36_37->SetDbValueDef($rsnew, $this->_1_Paila_36_37->CurrentValue, NULL, $this->_1_Paila_36_37->ReadOnly);

			// 2_Aislante
			$this->_2_Aislante->SetDbValueDef($rsnew, $this->_2_Aislante->CurrentValue, NULL, $this->_2_Aislante->ReadOnly);

			// 2_Carpa_hamaca
			$this->_2_Carpa_hamaca->SetDbValueDef($rsnew, $this->_2_Carpa_hamaca->CurrentValue, NULL, $this->_2_Carpa_hamaca->ReadOnly);

			// 2_Carpa_rancho
			$this->_2_Carpa_rancho->SetDbValueDef($rsnew, $this->_2_Carpa_rancho->CurrentValue, NULL, $this->_2_Carpa_rancho->ReadOnly);

			// 2_Fibra_rollo
			$this->_2_Fibra_rollo->SetDbValueDef($rsnew, $this->_2_Fibra_rollo->CurrentValue, NULL, $this->_2_Fibra_rollo->ReadOnly);

			// 2_CAL
			$this->_2_CAL->SetDbValueDef($rsnew, $this->_2_CAL->CurrentValue, NULL, $this->_2_CAL->ReadOnly);

			// 2_Linterna
			$this->_2_Linterna->SetDbValueDef($rsnew, $this->_2_Linterna->CurrentValue, NULL, $this->_2_Linterna->ReadOnly);

			// 2_Botiquin
			$this->_2_Botiquin->SetDbValueDef($rsnew, $this->_2_Botiquin->CurrentValue, NULL, $this->_2_Botiquin->ReadOnly);

			// 2_Mascara_filtro
			$this->_2_Mascara_filtro->SetDbValueDef($rsnew, $this->_2_Mascara_filtro->CurrentValue, NULL, $this->_2_Mascara_filtro->ReadOnly);

			// 2_Pimpina
			$this->_2_Pimpina->SetDbValueDef($rsnew, $this->_2_Pimpina->CurrentValue, NULL, $this->_2_Pimpina->ReadOnly);

			// 2_Sleeping 
			$this->_2_SleepingA0->SetDbValueDef($rsnew, $this->_2_SleepingA0->CurrentValue, NULL, $this->_2_SleepingA0->ReadOnly);

			// 2_Plastico_negro
			$this->_2_Plastico_negro->SetDbValueDef($rsnew, $this->_2_Plastico_negro->CurrentValue, NULL, $this->_2_Plastico_negro->ReadOnly);

			// 2_Tula_tropa
			$this->_2_Tula_tropa->SetDbValueDef($rsnew, $this->_2_Tula_tropa->CurrentValue, NULL, $this->_2_Tula_tropa->ReadOnly);

			// 2_Camilla
			$this->_2_Camilla->SetDbValueDef($rsnew, $this->_2_Camilla->CurrentValue, NULL, $this->_2_Camilla->ReadOnly);

			// Herramientas
			$this->Herramientas->SetDbValueDef($rsnew, $this->Herramientas->CurrentValue, NULL, $this->Herramientas->ReadOnly);

			// 3_Aspersora
			$this->_3_Aspersora->SetDbValueDef($rsnew, $this->_3_Aspersora->CurrentValue, NULL, $this->_3_Aspersora->ReadOnly);

			// 3_Cabo_hacha
			$this->_3_Cabo_hacha->SetDbValueDef($rsnew, $this->_3_Cabo_hacha->CurrentValue, NULL, $this->_3_Cabo_hacha->ReadOnly);

			// 3_Funda_machete
			$this->_3_Funda_machete->SetDbValueDef($rsnew, $this->_3_Funda_machete->CurrentValue, NULL, $this->_3_Funda_machete->ReadOnly);

			// 3_Glifosato_4lt
			$this->_3_Glifosato_4lt->SetDbValueDef($rsnew, $this->_3_Glifosato_4lt->CurrentValue, NULL, $this->_3_Glifosato_4lt->ReadOnly);

			// 3_Hacha
			$this->_3_Hacha->SetDbValueDef($rsnew, $this->_3_Hacha->CurrentValue, NULL, $this->_3_Hacha->ReadOnly);

			// 3_Lima_12_uni
			$this->_3_Lima_12_uni->SetDbValueDef($rsnew, $this->_3_Lima_12_uni->CurrentValue, NULL, $this->_3_Lima_12_uni->ReadOnly);

			// 3_Llave_mixta
			$this->_3_Llave_mixta->SetDbValueDef($rsnew, $this->_3_Llave_mixta->CurrentValue, NULL, $this->_3_Llave_mixta->ReadOnly);

			// 3_Machete
			$this->_3_Machete->SetDbValueDef($rsnew, $this->_3_Machete->CurrentValue, NULL, $this->_3_Machete->ReadOnly);

			// 3_Gafa_traslucida
			$this->_3_Gafa_traslucida->SetDbValueDef($rsnew, $this->_3_Gafa_traslucida->CurrentValue, NULL, $this->_3_Gafa_traslucida->ReadOnly);

			// 3_Motosierra
			$this->_3_Motosierra->SetDbValueDef($rsnew, $this->_3_Motosierra->CurrentValue, NULL, $this->_3_Motosierra->ReadOnly);

			// 3_Palin
			$this->_3_Palin->SetDbValueDef($rsnew, $this->_3_Palin->CurrentValue, NULL, $this->_3_Palin->ReadOnly);

			// 3_Tubo_galvanizado
			$this->_3_Tubo_galvanizado->SetDbValueDef($rsnew, $this->_3_Tubo_galvanizado->CurrentValue, NULL, $this->_3_Tubo_galvanizado->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "view_invlist.php", "", $this->TableVar, TRUE);
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
if (!isset($view_inv_edit)) $view_inv_edit = new cview_inv_edit();

// Page init
$view_inv_edit->Page_Init();

// Page main
$view_inv_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_inv_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_inv_edit = new ew_Page("view_inv_edit");
view_inv_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = view_inv_edit.PageID; // For backward compatibility

// Form object
var fview_invedit = new ew_Form("fview_invedit");

// Validate form
fview_invedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Cocina");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->Cocina->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Abrelatas");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Abrelatas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Balde");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Balde->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Arrocero_50");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Arrocero_50->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Arrocero_44");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Arrocero_44->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Chocolatera");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Chocolatera->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Colador");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Colador->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Cucharon_sopa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Cucharon_sopa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Cucharon_arroz");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Cucharon_arroz->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Cuchillo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Cuchillo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Embudo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Embudo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Espumera");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Espumera->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Estufa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Estufa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Cuchara_sopa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Cuchara_sopa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Recipiente");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Recipiente->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Kit_Repue_estufa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Kit_Repue_estufa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Molinillo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Molinillo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Olla_36");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Olla_36->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Olla_40");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Olla_40->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Paila_32");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Paila_32->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Paila_36_37");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_1_Paila_36_37->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Aislante");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Aislante->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Carpa_hamaca");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Carpa_hamaca->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Carpa_rancho");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Carpa_rancho->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Fibra_rollo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Fibra_rollo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_CAL");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_CAL->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Linterna");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Linterna->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Botiquin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Botiquin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Mascara_filtro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Mascara_filtro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Pimpina");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Pimpina->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_SleepingA0");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_SleepingA0->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Plastico_negro");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Plastico_negro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Tula_tropa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Tula_tropa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Camilla");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_2_Camilla->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Herramientas");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->Herramientas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Aspersora");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Aspersora->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Cabo_hacha");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Cabo_hacha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Funda_machete");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Funda_machete->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Glifosato_4lt");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Glifosato_4lt->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Hacha");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Hacha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Lima_12_uni");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Lima_12_uni->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Llave_mixta");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Llave_mixta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Machete");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Machete->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Gafa_traslucida");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Gafa_traslucida->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Motosierra");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Motosierra->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Palin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Palin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Tubo_galvanizado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_inv->_3_Tubo_galvanizado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Modificado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_inv->Modificado->FldCaption(), $view_inv->Modificado->ReqErrMsg)) ?>");

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
fview_invedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_invedit.ValidateRequired = true;
<?php } else { ?>
fview_invedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_invedit.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invedit.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invedit.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invedit.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invedit.Lists["x_TIPO_INV"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $view_inv_edit->ShowPageHeader(); ?>
<?php
$view_inv_edit->ShowMessage();
?>
<form name="fview_invedit" id="fview_invedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_inv_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_inv_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_inv">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($view_inv->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<div>
<?php if ($view_inv->llave->Visible) { // llave ?>
	<div id="r_llave" class="form-group">
		<label id="elh_view_inv_llave" for="x_llave" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->llave->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->llave->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_llave">
<span<?php echo $view_inv->llave->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->llave->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_llave" name="x_llave" id="x_llave" value="<?php echo ew_HtmlEncode($view_inv->llave->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_inv_llave">
<span<?php echo $view_inv->llave->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->llave->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_llave" name="x_llave" id="x_llave" value="<?php echo ew_HtmlEncode($view_inv->llave->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->llave->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->F_Sincron->Visible) { // F_Sincron ?>
	<div id="r_F_Sincron" class="form-group">
		<label id="elh_view_inv_F_Sincron" for="x_F_Sincron" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->F_Sincron->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->F_Sincron->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_F_Sincron">
<span<?php echo $view_inv->F_Sincron->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->F_Sincron->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" value="<?php echo ew_HtmlEncode($view_inv->F_Sincron->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_inv_F_Sincron">
<span<?php echo $view_inv->F_Sincron->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->F_Sincron->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" value="<?php echo ew_HtmlEncode($view_inv->F_Sincron->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->F_Sincron->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->USUARIO->Visible) { // USUARIO ?>
	<div id="r_USUARIO" class="form-group">
		<label id="elh_view_inv_USUARIO" for="x_USUARIO" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->USUARIO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->USUARIO->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_USUARIO">
<span<?php echo $view_inv->USUARIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->USUARIO->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" value="<?php echo ew_HtmlEncode($view_inv->USUARIO->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_inv_USUARIO">
<span<?php echo $view_inv->USUARIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->USUARIO->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" value="<?php echo ew_HtmlEncode($view_inv->USUARIO->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->USUARIO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Cargo_gme->Visible) { // Cargo_gme ?>
	<div id="r_Cargo_gme" class="form-group">
		<label id="elh_view_inv_Cargo_gme" for="x_Cargo_gme" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Cargo_gme->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Cargo_gme->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Cargo_gme">
<span<?php echo $view_inv->Cargo_gme->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Cargo_gme->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" value="<?php echo ew_HtmlEncode($view_inv->Cargo_gme->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_inv_Cargo_gme">
<span<?php echo $view_inv->Cargo_gme->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Cargo_gme->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" value="<?php echo ew_HtmlEncode($view_inv->Cargo_gme->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Cargo_gme->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->NOM_PE->Visible) { // NOM_PE ?>
	<div id="r_NOM_PE" class="form-group">
		<label id="elh_view_inv_NOM_PE" for="x_NOM_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->NOM_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->NOM_PE->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_NOM_PE">
<select data-field="x_NOM_PE" id="x_NOM_PE" name="x_NOM_PE"<?php echo $view_inv->NOM_PE->EditAttributes() ?>>
<?php
if (is_array($view_inv->NOM_PE->EditValue)) {
	$arwrk = $view_inv->NOM_PE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_inv->NOM_PE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_invedit.Lists["x_NOM_PE"].Options = <?php echo (is_array($view_inv->NOM_PE->EditValue)) ? ew_ArrayToJson($view_inv->NOM_PE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_inv_NOM_PE">
<span<?php echo $view_inv->NOM_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->NOM_PE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_PE" name="x_NOM_PE" id="x_NOM_PE" value="<?php echo ew_HtmlEncode($view_inv->NOM_PE->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->NOM_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Otro_PE->Visible) { // Otro_PE ?>
	<div id="r_Otro_PE" class="form-group">
		<label id="elh_view_inv_Otro_PE" for="x_Otro_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Otro_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Otro_PE->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Otro_PE">
<input type="text" data-field="x_Otro_PE" name="x_Otro_PE" id="x_Otro_PE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_inv->Otro_PE->PlaceHolder) ?>" value="<?php echo $view_inv->Otro_PE->EditValue ?>"<?php echo $view_inv->Otro_PE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_Otro_PE">
<span<?php echo $view_inv->Otro_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Otro_PE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_PE" name="x_Otro_PE" id="x_Otro_PE" value="<?php echo ew_HtmlEncode($view_inv->Otro_PE->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Otro_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->DIA->Visible) { // DIA ?>
	<div id="r_DIA" class="form-group">
		<label id="elh_view_inv_DIA" for="x_DIA" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->DIA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->DIA->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_DIA">
<input type="text" data-field="x_DIA" name="x_DIA" id="x_DIA" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_inv->DIA->PlaceHolder) ?>" value="<?php echo $view_inv->DIA->EditValue ?>"<?php echo $view_inv->DIA->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_DIA">
<span<?php echo $view_inv->DIA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->DIA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_DIA" name="x_DIA" id="x_DIA" value="<?php echo ew_HtmlEncode($view_inv->DIA->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->DIA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->MES->Visible) { // MES ?>
	<div id="r_MES" class="form-group">
		<label id="elh_view_inv_MES" for="x_MES" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->MES->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->MES->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_MES">
<input type="text" data-field="x_MES" name="x_MES" id="x_MES" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_inv->MES->PlaceHolder) ?>" value="<?php echo $view_inv->MES->EditValue ?>"<?php echo $view_inv->MES->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_MES">
<span<?php echo $view_inv->MES->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->MES->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_MES" name="x_MES" id="x_MES" value="<?php echo ew_HtmlEncode($view_inv->MES->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->MES->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->OBSERVACION->Visible) { // OBSERVACION ?>
	<div id="r_OBSERVACION" class="form-group">
		<label id="elh_view_inv_OBSERVACION" for="x_OBSERVACION" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->OBSERVACION->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->OBSERVACION->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_OBSERVACION">
<textarea data-field="x_OBSERVACION" name="x_OBSERVACION" id="x_OBSERVACION" cols="120" rows="4" placeholder="<?php echo ew_HtmlEncode($view_inv->OBSERVACION->PlaceHolder) ?>"<?php echo $view_inv->OBSERVACION->EditAttributes() ?>><?php echo $view_inv->OBSERVACION->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_inv_OBSERVACION">
<span<?php echo $view_inv->OBSERVACION->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->OBSERVACION->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_OBSERVACION" name="x_OBSERVACION" id="x_OBSERVACION" value="<?php echo ew_HtmlEncode($view_inv->OBSERVACION->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->OBSERVACION->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->AD1O->Visible) { // AÑO ?>
	<div id="r_AD1O" class="form-group">
		<label id="elh_view_inv_AD1O" for="x_AD1O" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->AD1O->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->AD1O->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_AD1O">
<select data-field="x_AD1O" id="x_AD1O" name="x_AD1O"<?php echo $view_inv->AD1O->EditAttributes() ?>>
<?php
if (is_array($view_inv->AD1O->EditValue)) {
	$arwrk = $view_inv->AD1O->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_inv->AD1O->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_invedit.Lists["x_AD1O"].Options = <?php echo (is_array($view_inv->AD1O->EditValue)) ? ew_ArrayToJson($view_inv->AD1O->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_inv_AD1O">
<span<?php echo $view_inv->AD1O->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->AD1O->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_AD1O" name="x_AD1O" id="x_AD1O" value="<?php echo ew_HtmlEncode($view_inv->AD1O->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->AD1O->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->FASE->Visible) { // FASE ?>
	<div id="r_FASE" class="form-group">
		<label id="elh_view_inv_FASE" for="x_FASE" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->FASE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->FASE->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_FASE">
<select data-field="x_FASE" id="x_FASE" name="x_FASE"<?php echo $view_inv->FASE->EditAttributes() ?>>
<?php
if (is_array($view_inv->FASE->EditValue)) {
	$arwrk = $view_inv->FASE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_inv->FASE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_invedit.Lists["x_FASE"].Options = <?php echo (is_array($view_inv->FASE->EditValue)) ? ew_ArrayToJson($view_inv->FASE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_inv_FASE">
<span<?php echo $view_inv->FASE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->FASE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_FASE" name="x_FASE" id="x_FASE" value="<?php echo ew_HtmlEncode($view_inv->FASE->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->FASE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->FECHA_INV->Visible) { // FECHA_INV ?>
	<div id="r_FECHA_INV" class="form-group">
		<label id="elh_view_inv_FECHA_INV" for="x_FECHA_INV" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->FECHA_INV->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->FECHA_INV->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_FECHA_INV">
<input type="text" data-field="x_FECHA_INV" name="x_FECHA_INV" id="x_FECHA_INV" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view_inv->FECHA_INV->PlaceHolder) ?>" value="<?php echo $view_inv->FECHA_INV->EditValue ?>"<?php echo $view_inv->FECHA_INV->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_FECHA_INV">
<span<?php echo $view_inv->FECHA_INV->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->FECHA_INV->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_FECHA_INV" name="x_FECHA_INV" id="x_FECHA_INV" value="<?php echo ew_HtmlEncode($view_inv->FECHA_INV->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->FECHA_INV->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->TIPO_INV->Visible) { // TIPO_INV ?>
	<div id="r_TIPO_INV" class="form-group">
		<label id="elh_view_inv_TIPO_INV" for="x_TIPO_INV" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->TIPO_INV->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->TIPO_INV->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_TIPO_INV">
<select data-field="x_TIPO_INV" id="x_TIPO_INV" name="x_TIPO_INV"<?php echo $view_inv->TIPO_INV->EditAttributes() ?>>
<?php
if (is_array($view_inv->TIPO_INV->EditValue)) {
	$arwrk = $view_inv->TIPO_INV->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_inv->TIPO_INV->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_invedit.Lists["x_TIPO_INV"].Options = <?php echo (is_array($view_inv->TIPO_INV->EditValue)) ? ew_ArrayToJson($view_inv->TIPO_INV->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_inv_TIPO_INV">
<span<?php echo $view_inv->TIPO_INV->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->TIPO_INV->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_TIPO_INV" name="x_TIPO_INV" id="x_TIPO_INV" value="<?php echo ew_HtmlEncode($view_inv->TIPO_INV->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->TIPO_INV->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
	<div id="r_NOM_CAPATAZ" class="form-group">
		<label id="elh_view_inv_NOM_CAPATAZ" for="x_NOM_CAPATAZ" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->NOM_CAPATAZ->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->NOM_CAPATAZ->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_NOM_CAPATAZ">
<textarea data-field="x_NOM_CAPATAZ" name="x_NOM_CAPATAZ" id="x_NOM_CAPATAZ" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view_inv->NOM_CAPATAZ->PlaceHolder) ?>"<?php echo $view_inv->NOM_CAPATAZ->EditAttributes() ?>><?php echo $view_inv->NOM_CAPATAZ->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_inv_NOM_CAPATAZ">
<span<?php echo $view_inv->NOM_CAPATAZ->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->NOM_CAPATAZ->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_CAPATAZ" name="x_NOM_CAPATAZ" id="x_NOM_CAPATAZ" value="<?php echo ew_HtmlEncode($view_inv->NOM_CAPATAZ->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->NOM_CAPATAZ->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Otro_NOM_CAPAT->Visible) { // Otro_NOM_CAPAT ?>
	<div id="r_Otro_NOM_CAPAT" class="form-group">
		<label id="elh_view_inv_Otro_NOM_CAPAT" for="x_Otro_NOM_CAPAT" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Otro_NOM_CAPAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Otro_NOM_CAPAT->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Otro_NOM_CAPAT">
<input type="text" data-field="x_Otro_NOM_CAPAT" name="x_Otro_NOM_CAPAT" id="x_Otro_NOM_CAPAT" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_inv->Otro_NOM_CAPAT->PlaceHolder) ?>" value="<?php echo $view_inv->Otro_NOM_CAPAT->EditValue ?>"<?php echo $view_inv->Otro_NOM_CAPAT->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_Otro_NOM_CAPAT">
<span<?php echo $view_inv->Otro_NOM_CAPAT->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Otro_NOM_CAPAT->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_NOM_CAPAT" name="x_Otro_NOM_CAPAT" id="x_Otro_NOM_CAPAT" value="<?php echo ew_HtmlEncode($view_inv->Otro_NOM_CAPAT->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Otro_NOM_CAPAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Otro_CC_CAPAT->Visible) { // Otro_CC_CAPAT ?>
	<div id="r_Otro_CC_CAPAT" class="form-group">
		<label id="elh_view_inv_Otro_CC_CAPAT" for="x_Otro_CC_CAPAT" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Otro_CC_CAPAT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Otro_CC_CAPAT->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Otro_CC_CAPAT">
<input type="text" data-field="x_Otro_CC_CAPAT" name="x_Otro_CC_CAPAT" id="x_Otro_CC_CAPAT" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_inv->Otro_CC_CAPAT->PlaceHolder) ?>" value="<?php echo $view_inv->Otro_CC_CAPAT->EditValue ?>"<?php echo $view_inv->Otro_CC_CAPAT->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_Otro_CC_CAPAT">
<span<?php echo $view_inv->Otro_CC_CAPAT->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Otro_CC_CAPAT->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_CC_CAPAT" name="x_Otro_CC_CAPAT" id="x_Otro_CC_CAPAT" value="<?php echo ew_HtmlEncode($view_inv->Otro_CC_CAPAT->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Otro_CC_CAPAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->NOM_LUGAR->Visible) { // NOM_LUGAR ?>
	<div id="r_NOM_LUGAR" class="form-group">
		<label id="elh_view_inv_NOM_LUGAR" for="x_NOM_LUGAR" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->NOM_LUGAR->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->NOM_LUGAR->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_NOM_LUGAR">
<input type="text" data-field="x_NOM_LUGAR" name="x_NOM_LUGAR" id="x_NOM_LUGAR" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_inv->NOM_LUGAR->PlaceHolder) ?>" value="<?php echo $view_inv->NOM_LUGAR->EditValue ?>"<?php echo $view_inv->NOM_LUGAR->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_NOM_LUGAR">
<span<?php echo $view_inv->NOM_LUGAR->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->NOM_LUGAR->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_LUGAR" name="x_NOM_LUGAR" id="x_NOM_LUGAR" value="<?php echo ew_HtmlEncode($view_inv->NOM_LUGAR->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->NOM_LUGAR->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Cocina->Visible) { // Cocina ?>
	<div id="r_Cocina" class="form-group">
		<label id="elh_view_inv_Cocina" for="x_Cocina" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Cocina->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Cocina->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Cocina">
<input type="text" data-field="x_Cocina" name="x_Cocina" id="x_Cocina" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->Cocina->PlaceHolder) ?>" value="<?php echo $view_inv->Cocina->EditValue ?>"<?php echo $view_inv->Cocina->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_Cocina">
<span<?php echo $view_inv->Cocina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Cocina->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cocina" name="x_Cocina" id="x_Cocina" value="<?php echo ew_HtmlEncode($view_inv->Cocina->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Cocina->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Abrelatas->Visible) { // 1_Abrelatas ?>
	<div id="r__1_Abrelatas" class="form-group">
		<label id="elh_view_inv__1_Abrelatas" for="x__1_Abrelatas" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Abrelatas->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Abrelatas->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Abrelatas">
<input type="text" data-field="x__1_Abrelatas" name="x__1_Abrelatas" id="x__1_Abrelatas" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Abrelatas->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Abrelatas->EditValue ?>"<?php echo $view_inv->_1_Abrelatas->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Abrelatas">
<span<?php echo $view_inv->_1_Abrelatas->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Abrelatas->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Abrelatas" name="x__1_Abrelatas" id="x__1_Abrelatas" value="<?php echo ew_HtmlEncode($view_inv->_1_Abrelatas->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Abrelatas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Balde->Visible) { // 1_Balde ?>
	<div id="r__1_Balde" class="form-group">
		<label id="elh_view_inv__1_Balde" for="x__1_Balde" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Balde->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Balde->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Balde">
<input type="text" data-field="x__1_Balde" name="x__1_Balde" id="x__1_Balde" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Balde->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Balde->EditValue ?>"<?php echo $view_inv->_1_Balde->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Balde">
<span<?php echo $view_inv->_1_Balde->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Balde->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Balde" name="x__1_Balde" id="x__1_Balde" value="<?php echo ew_HtmlEncode($view_inv->_1_Balde->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Balde->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Arrocero_50->Visible) { // 1_Arrocero_50 ?>
	<div id="r__1_Arrocero_50" class="form-group">
		<label id="elh_view_inv__1_Arrocero_50" for="x__1_Arrocero_50" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Arrocero_50->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Arrocero_50->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Arrocero_50">
<input type="text" data-field="x__1_Arrocero_50" name="x__1_Arrocero_50" id="x__1_Arrocero_50" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Arrocero_50->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Arrocero_50->EditValue ?>"<?php echo $view_inv->_1_Arrocero_50->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Arrocero_50">
<span<?php echo $view_inv->_1_Arrocero_50->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Arrocero_50->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Arrocero_50" name="x__1_Arrocero_50" id="x__1_Arrocero_50" value="<?php echo ew_HtmlEncode($view_inv->_1_Arrocero_50->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Arrocero_50->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Arrocero_44->Visible) { // 1_Arrocero_44 ?>
	<div id="r__1_Arrocero_44" class="form-group">
		<label id="elh_view_inv__1_Arrocero_44" for="x__1_Arrocero_44" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Arrocero_44->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Arrocero_44->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Arrocero_44">
<input type="text" data-field="x__1_Arrocero_44" name="x__1_Arrocero_44" id="x__1_Arrocero_44" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Arrocero_44->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Arrocero_44->EditValue ?>"<?php echo $view_inv->_1_Arrocero_44->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Arrocero_44">
<span<?php echo $view_inv->_1_Arrocero_44->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Arrocero_44->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Arrocero_44" name="x__1_Arrocero_44" id="x__1_Arrocero_44" value="<?php echo ew_HtmlEncode($view_inv->_1_Arrocero_44->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Arrocero_44->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Chocolatera->Visible) { // 1_Chocolatera ?>
	<div id="r__1_Chocolatera" class="form-group">
		<label id="elh_view_inv__1_Chocolatera" for="x__1_Chocolatera" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Chocolatera->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Chocolatera->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Chocolatera">
<input type="text" data-field="x__1_Chocolatera" name="x__1_Chocolatera" id="x__1_Chocolatera" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Chocolatera->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Chocolatera->EditValue ?>"<?php echo $view_inv->_1_Chocolatera->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Chocolatera">
<span<?php echo $view_inv->_1_Chocolatera->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Chocolatera->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Chocolatera" name="x__1_Chocolatera" id="x__1_Chocolatera" value="<?php echo ew_HtmlEncode($view_inv->_1_Chocolatera->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Chocolatera->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Colador->Visible) { // 1_Colador ?>
	<div id="r__1_Colador" class="form-group">
		<label id="elh_view_inv__1_Colador" for="x__1_Colador" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Colador->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Colador->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Colador">
<input type="text" data-field="x__1_Colador" name="x__1_Colador" id="x__1_Colador" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Colador->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Colador->EditValue ?>"<?php echo $view_inv->_1_Colador->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Colador">
<span<?php echo $view_inv->_1_Colador->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Colador->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Colador" name="x__1_Colador" id="x__1_Colador" value="<?php echo ew_HtmlEncode($view_inv->_1_Colador->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Colador->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Cucharon_sopa->Visible) { // 1_Cucharon_sopa ?>
	<div id="r__1_Cucharon_sopa" class="form-group">
		<label id="elh_view_inv__1_Cucharon_sopa" for="x__1_Cucharon_sopa" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Cucharon_sopa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Cucharon_sopa->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Cucharon_sopa">
<input type="text" data-field="x__1_Cucharon_sopa" name="x__1_Cucharon_sopa" id="x__1_Cucharon_sopa" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Cucharon_sopa->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Cucharon_sopa->EditValue ?>"<?php echo $view_inv->_1_Cucharon_sopa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Cucharon_sopa">
<span<?php echo $view_inv->_1_Cucharon_sopa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Cucharon_sopa->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Cucharon_sopa" name="x__1_Cucharon_sopa" id="x__1_Cucharon_sopa" value="<?php echo ew_HtmlEncode($view_inv->_1_Cucharon_sopa->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Cucharon_sopa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Cucharon_arroz->Visible) { // 1_Cucharon_arroz ?>
	<div id="r__1_Cucharon_arroz" class="form-group">
		<label id="elh_view_inv__1_Cucharon_arroz" for="x__1_Cucharon_arroz" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Cucharon_arroz->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Cucharon_arroz->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Cucharon_arroz">
<input type="text" data-field="x__1_Cucharon_arroz" name="x__1_Cucharon_arroz" id="x__1_Cucharon_arroz" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Cucharon_arroz->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Cucharon_arroz->EditValue ?>"<?php echo $view_inv->_1_Cucharon_arroz->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Cucharon_arroz">
<span<?php echo $view_inv->_1_Cucharon_arroz->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Cucharon_arroz->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Cucharon_arroz" name="x__1_Cucharon_arroz" id="x__1_Cucharon_arroz" value="<?php echo ew_HtmlEncode($view_inv->_1_Cucharon_arroz->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Cucharon_arroz->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Cuchillo->Visible) { // 1_Cuchillo ?>
	<div id="r__1_Cuchillo" class="form-group">
		<label id="elh_view_inv__1_Cuchillo" for="x__1_Cuchillo" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Cuchillo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Cuchillo->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Cuchillo">
<input type="text" data-field="x__1_Cuchillo" name="x__1_Cuchillo" id="x__1_Cuchillo" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Cuchillo->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Cuchillo->EditValue ?>"<?php echo $view_inv->_1_Cuchillo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Cuchillo">
<span<?php echo $view_inv->_1_Cuchillo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Cuchillo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Cuchillo" name="x__1_Cuchillo" id="x__1_Cuchillo" value="<?php echo ew_HtmlEncode($view_inv->_1_Cuchillo->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Cuchillo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Embudo->Visible) { // 1_Embudo ?>
	<div id="r__1_Embudo" class="form-group">
		<label id="elh_view_inv__1_Embudo" for="x__1_Embudo" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Embudo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Embudo->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Embudo">
<input type="text" data-field="x__1_Embudo" name="x__1_Embudo" id="x__1_Embudo" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Embudo->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Embudo->EditValue ?>"<?php echo $view_inv->_1_Embudo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Embudo">
<span<?php echo $view_inv->_1_Embudo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Embudo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Embudo" name="x__1_Embudo" id="x__1_Embudo" value="<?php echo ew_HtmlEncode($view_inv->_1_Embudo->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Embudo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Espumera->Visible) { // 1_Espumera ?>
	<div id="r__1_Espumera" class="form-group">
		<label id="elh_view_inv__1_Espumera" for="x__1_Espumera" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Espumera->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Espumera->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Espumera">
<input type="text" data-field="x__1_Espumera" name="x__1_Espumera" id="x__1_Espumera" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Espumera->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Espumera->EditValue ?>"<?php echo $view_inv->_1_Espumera->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Espumera">
<span<?php echo $view_inv->_1_Espumera->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Espumera->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Espumera" name="x__1_Espumera" id="x__1_Espumera" value="<?php echo ew_HtmlEncode($view_inv->_1_Espumera->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Espumera->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Estufa->Visible) { // 1_Estufa ?>
	<div id="r__1_Estufa" class="form-group">
		<label id="elh_view_inv__1_Estufa" for="x__1_Estufa" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Estufa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Estufa->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Estufa">
<input type="text" data-field="x__1_Estufa" name="x__1_Estufa" id="x__1_Estufa" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Estufa->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Estufa->EditValue ?>"<?php echo $view_inv->_1_Estufa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Estufa">
<span<?php echo $view_inv->_1_Estufa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Estufa->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Estufa" name="x__1_Estufa" id="x__1_Estufa" value="<?php echo ew_HtmlEncode($view_inv->_1_Estufa->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Estufa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Cuchara_sopa->Visible) { // 1_Cuchara_sopa ?>
	<div id="r__1_Cuchara_sopa" class="form-group">
		<label id="elh_view_inv__1_Cuchara_sopa" for="x__1_Cuchara_sopa" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Cuchara_sopa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Cuchara_sopa->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Cuchara_sopa">
<input type="text" data-field="x__1_Cuchara_sopa" name="x__1_Cuchara_sopa" id="x__1_Cuchara_sopa" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Cuchara_sopa->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Cuchara_sopa->EditValue ?>"<?php echo $view_inv->_1_Cuchara_sopa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Cuchara_sopa">
<span<?php echo $view_inv->_1_Cuchara_sopa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Cuchara_sopa->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Cuchara_sopa" name="x__1_Cuchara_sopa" id="x__1_Cuchara_sopa" value="<?php echo ew_HtmlEncode($view_inv->_1_Cuchara_sopa->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Cuchara_sopa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Recipiente->Visible) { // 1_Recipiente ?>
	<div id="r__1_Recipiente" class="form-group">
		<label id="elh_view_inv__1_Recipiente" for="x__1_Recipiente" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Recipiente->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Recipiente->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Recipiente">
<input type="text" data-field="x__1_Recipiente" name="x__1_Recipiente" id="x__1_Recipiente" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Recipiente->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Recipiente->EditValue ?>"<?php echo $view_inv->_1_Recipiente->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Recipiente">
<span<?php echo $view_inv->_1_Recipiente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Recipiente->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Recipiente" name="x__1_Recipiente" id="x__1_Recipiente" value="<?php echo ew_HtmlEncode($view_inv->_1_Recipiente->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Recipiente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Kit_Repue_estufa->Visible) { // 1_Kit_Repue_estufa ?>
	<div id="r__1_Kit_Repue_estufa" class="form-group">
		<label id="elh_view_inv__1_Kit_Repue_estufa" for="x__1_Kit_Repue_estufa" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Kit_Repue_estufa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Kit_Repue_estufa->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Kit_Repue_estufa">
<input type="text" data-field="x__1_Kit_Repue_estufa" name="x__1_Kit_Repue_estufa" id="x__1_Kit_Repue_estufa" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Kit_Repue_estufa->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Kit_Repue_estufa->EditValue ?>"<?php echo $view_inv->_1_Kit_Repue_estufa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Kit_Repue_estufa">
<span<?php echo $view_inv->_1_Kit_Repue_estufa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Kit_Repue_estufa->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Kit_Repue_estufa" name="x__1_Kit_Repue_estufa" id="x__1_Kit_Repue_estufa" value="<?php echo ew_HtmlEncode($view_inv->_1_Kit_Repue_estufa->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Kit_Repue_estufa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Molinillo->Visible) { // 1_Molinillo ?>
	<div id="r__1_Molinillo" class="form-group">
		<label id="elh_view_inv__1_Molinillo" for="x__1_Molinillo" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Molinillo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Molinillo->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Molinillo">
<input type="text" data-field="x__1_Molinillo" name="x__1_Molinillo" id="x__1_Molinillo" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Molinillo->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Molinillo->EditValue ?>"<?php echo $view_inv->_1_Molinillo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Molinillo">
<span<?php echo $view_inv->_1_Molinillo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Molinillo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Molinillo" name="x__1_Molinillo" id="x__1_Molinillo" value="<?php echo ew_HtmlEncode($view_inv->_1_Molinillo->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Molinillo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Olla_36->Visible) { // 1_Olla_36 ?>
	<div id="r__1_Olla_36" class="form-group">
		<label id="elh_view_inv__1_Olla_36" for="x__1_Olla_36" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Olla_36->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Olla_36->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Olla_36">
<input type="text" data-field="x__1_Olla_36" name="x__1_Olla_36" id="x__1_Olla_36" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Olla_36->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Olla_36->EditValue ?>"<?php echo $view_inv->_1_Olla_36->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Olla_36">
<span<?php echo $view_inv->_1_Olla_36->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Olla_36->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Olla_36" name="x__1_Olla_36" id="x__1_Olla_36" value="<?php echo ew_HtmlEncode($view_inv->_1_Olla_36->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Olla_36->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Olla_40->Visible) { // 1_Olla_40 ?>
	<div id="r__1_Olla_40" class="form-group">
		<label id="elh_view_inv__1_Olla_40" for="x__1_Olla_40" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Olla_40->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Olla_40->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Olla_40">
<input type="text" data-field="x__1_Olla_40" name="x__1_Olla_40" id="x__1_Olla_40" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Olla_40->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Olla_40->EditValue ?>"<?php echo $view_inv->_1_Olla_40->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Olla_40">
<span<?php echo $view_inv->_1_Olla_40->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Olla_40->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Olla_40" name="x__1_Olla_40" id="x__1_Olla_40" value="<?php echo ew_HtmlEncode($view_inv->_1_Olla_40->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Olla_40->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Paila_32->Visible) { // 1_Paila_32 ?>
	<div id="r__1_Paila_32" class="form-group">
		<label id="elh_view_inv__1_Paila_32" for="x__1_Paila_32" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Paila_32->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Paila_32->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Paila_32">
<input type="text" data-field="x__1_Paila_32" name="x__1_Paila_32" id="x__1_Paila_32" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Paila_32->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Paila_32->EditValue ?>"<?php echo $view_inv->_1_Paila_32->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Paila_32">
<span<?php echo $view_inv->_1_Paila_32->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Paila_32->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Paila_32" name="x__1_Paila_32" id="x__1_Paila_32" value="<?php echo ew_HtmlEncode($view_inv->_1_Paila_32->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Paila_32->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_1_Paila_36_37->Visible) { // 1_Paila_36_37 ?>
	<div id="r__1_Paila_36_37" class="form-group">
		<label id="elh_view_inv__1_Paila_36_37" for="x__1_Paila_36_37" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_1_Paila_36_37->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_1_Paila_36_37->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__1_Paila_36_37">
<input type="text" data-field="x__1_Paila_36_37" name="x__1_Paila_36_37" id="x__1_Paila_36_37" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_1_Paila_36_37->PlaceHolder) ?>" value="<?php echo $view_inv->_1_Paila_36_37->EditValue ?>"<?php echo $view_inv->_1_Paila_36_37->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__1_Paila_36_37">
<span<?php echo $view_inv->_1_Paila_36_37->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_1_Paila_36_37->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Paila_36_37" name="x__1_Paila_36_37" id="x__1_Paila_36_37" value="<?php echo ew_HtmlEncode($view_inv->_1_Paila_36_37->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_1_Paila_36_37->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Camping->Visible) { // Camping ?>
	<div id="r_Camping" class="form-group">
		<label id="elh_view_inv_Camping" for="x_Camping" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Camping->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Camping->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Camping">
<span<?php echo $view_inv->Camping->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Camping->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Camping" name="x_Camping" id="x_Camping" value="<?php echo ew_HtmlEncode($view_inv->Camping->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_inv_Camping">
<span<?php echo $view_inv->Camping->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Camping->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Camping" name="x_Camping" id="x_Camping" value="<?php echo ew_HtmlEncode($view_inv->Camping->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Camping->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Aislante->Visible) { // 2_Aislante ?>
	<div id="r__2_Aislante" class="form-group">
		<label id="elh_view_inv__2_Aislante" for="x__2_Aislante" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Aislante->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Aislante->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Aislante">
<input type="text" data-field="x__2_Aislante" name="x__2_Aislante" id="x__2_Aislante" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Aislante->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Aislante->EditValue ?>"<?php echo $view_inv->_2_Aislante->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Aislante">
<span<?php echo $view_inv->_2_Aislante->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Aislante->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Aislante" name="x__2_Aislante" id="x__2_Aislante" value="<?php echo ew_HtmlEncode($view_inv->_2_Aislante->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Aislante->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Carpa_hamaca->Visible) { // 2_Carpa_hamaca ?>
	<div id="r__2_Carpa_hamaca" class="form-group">
		<label id="elh_view_inv__2_Carpa_hamaca" for="x__2_Carpa_hamaca" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Carpa_hamaca->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Carpa_hamaca->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Carpa_hamaca">
<input type="text" data-field="x__2_Carpa_hamaca" name="x__2_Carpa_hamaca" id="x__2_Carpa_hamaca" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Carpa_hamaca->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Carpa_hamaca->EditValue ?>"<?php echo $view_inv->_2_Carpa_hamaca->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Carpa_hamaca">
<span<?php echo $view_inv->_2_Carpa_hamaca->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Carpa_hamaca->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Carpa_hamaca" name="x__2_Carpa_hamaca" id="x__2_Carpa_hamaca" value="<?php echo ew_HtmlEncode($view_inv->_2_Carpa_hamaca->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Carpa_hamaca->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Carpa_rancho->Visible) { // 2_Carpa_rancho ?>
	<div id="r__2_Carpa_rancho" class="form-group">
		<label id="elh_view_inv__2_Carpa_rancho" for="x__2_Carpa_rancho" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Carpa_rancho->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Carpa_rancho->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Carpa_rancho">
<input type="text" data-field="x__2_Carpa_rancho" name="x__2_Carpa_rancho" id="x__2_Carpa_rancho" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Carpa_rancho->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Carpa_rancho->EditValue ?>"<?php echo $view_inv->_2_Carpa_rancho->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Carpa_rancho">
<span<?php echo $view_inv->_2_Carpa_rancho->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Carpa_rancho->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Carpa_rancho" name="x__2_Carpa_rancho" id="x__2_Carpa_rancho" value="<?php echo ew_HtmlEncode($view_inv->_2_Carpa_rancho->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Carpa_rancho->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Fibra_rollo->Visible) { // 2_Fibra_rollo ?>
	<div id="r__2_Fibra_rollo" class="form-group">
		<label id="elh_view_inv__2_Fibra_rollo" for="x__2_Fibra_rollo" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Fibra_rollo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Fibra_rollo->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Fibra_rollo">
<input type="text" data-field="x__2_Fibra_rollo" name="x__2_Fibra_rollo" id="x__2_Fibra_rollo" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Fibra_rollo->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Fibra_rollo->EditValue ?>"<?php echo $view_inv->_2_Fibra_rollo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Fibra_rollo">
<span<?php echo $view_inv->_2_Fibra_rollo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Fibra_rollo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Fibra_rollo" name="x__2_Fibra_rollo" id="x__2_Fibra_rollo" value="<?php echo ew_HtmlEncode($view_inv->_2_Fibra_rollo->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Fibra_rollo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_CAL->Visible) { // 2_CAL ?>
	<div id="r__2_CAL" class="form-group">
		<label id="elh_view_inv__2_CAL" for="x__2_CAL" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_CAL->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_CAL->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_CAL">
<input type="text" data-field="x__2_CAL" name="x__2_CAL" id="x__2_CAL" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_CAL->PlaceHolder) ?>" value="<?php echo $view_inv->_2_CAL->EditValue ?>"<?php echo $view_inv->_2_CAL->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_CAL">
<span<?php echo $view_inv->_2_CAL->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_CAL->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_CAL" name="x__2_CAL" id="x__2_CAL" value="<?php echo ew_HtmlEncode($view_inv->_2_CAL->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_CAL->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Linterna->Visible) { // 2_Linterna ?>
	<div id="r__2_Linterna" class="form-group">
		<label id="elh_view_inv__2_Linterna" for="x__2_Linterna" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Linterna->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Linterna->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Linterna">
<input type="text" data-field="x__2_Linterna" name="x__2_Linterna" id="x__2_Linterna" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Linterna->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Linterna->EditValue ?>"<?php echo $view_inv->_2_Linterna->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Linterna">
<span<?php echo $view_inv->_2_Linterna->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Linterna->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Linterna" name="x__2_Linterna" id="x__2_Linterna" value="<?php echo ew_HtmlEncode($view_inv->_2_Linterna->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Linterna->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Botiquin->Visible) { // 2_Botiquin ?>
	<div id="r__2_Botiquin" class="form-group">
		<label id="elh_view_inv__2_Botiquin" for="x__2_Botiquin" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Botiquin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Botiquin->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Botiquin">
<input type="text" data-field="x__2_Botiquin" name="x__2_Botiquin" id="x__2_Botiquin" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Botiquin->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Botiquin->EditValue ?>"<?php echo $view_inv->_2_Botiquin->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Botiquin">
<span<?php echo $view_inv->_2_Botiquin->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Botiquin->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Botiquin" name="x__2_Botiquin" id="x__2_Botiquin" value="<?php echo ew_HtmlEncode($view_inv->_2_Botiquin->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Botiquin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Mascara_filtro->Visible) { // 2_Mascara_filtro ?>
	<div id="r__2_Mascara_filtro" class="form-group">
		<label id="elh_view_inv__2_Mascara_filtro" for="x__2_Mascara_filtro" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Mascara_filtro->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Mascara_filtro->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Mascara_filtro">
<input type="text" data-field="x__2_Mascara_filtro" name="x__2_Mascara_filtro" id="x__2_Mascara_filtro" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Mascara_filtro->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Mascara_filtro->EditValue ?>"<?php echo $view_inv->_2_Mascara_filtro->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Mascara_filtro">
<span<?php echo $view_inv->_2_Mascara_filtro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Mascara_filtro->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Mascara_filtro" name="x__2_Mascara_filtro" id="x__2_Mascara_filtro" value="<?php echo ew_HtmlEncode($view_inv->_2_Mascara_filtro->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Mascara_filtro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Pimpina->Visible) { // 2_Pimpina ?>
	<div id="r__2_Pimpina" class="form-group">
		<label id="elh_view_inv__2_Pimpina" for="x__2_Pimpina" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Pimpina->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Pimpina->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Pimpina">
<input type="text" data-field="x__2_Pimpina" name="x__2_Pimpina" id="x__2_Pimpina" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Pimpina->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Pimpina->EditValue ?>"<?php echo $view_inv->_2_Pimpina->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Pimpina">
<span<?php echo $view_inv->_2_Pimpina->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Pimpina->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Pimpina" name="x__2_Pimpina" id="x__2_Pimpina" value="<?php echo ew_HtmlEncode($view_inv->_2_Pimpina->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Pimpina->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_SleepingA0->Visible) { // 2_Sleeping  ?>
	<div id="r__2_SleepingA0" class="form-group">
		<label id="elh_view_inv__2_SleepingA0" for="x__2_SleepingA0" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_SleepingA0->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_SleepingA0->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_SleepingA0">
<input type="text" data-field="x__2_SleepingA0" name="x__2_SleepingA0" id="x__2_SleepingA0" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_SleepingA0->PlaceHolder) ?>" value="<?php echo $view_inv->_2_SleepingA0->EditValue ?>"<?php echo $view_inv->_2_SleepingA0->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_SleepingA0">
<span<?php echo $view_inv->_2_SleepingA0->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_SleepingA0->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_SleepingA0" name="x__2_SleepingA0" id="x__2_SleepingA0" value="<?php echo ew_HtmlEncode($view_inv->_2_SleepingA0->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_SleepingA0->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Plastico_negro->Visible) { // 2_Plastico_negro ?>
	<div id="r__2_Plastico_negro" class="form-group">
		<label id="elh_view_inv__2_Plastico_negro" for="x__2_Plastico_negro" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Plastico_negro->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Plastico_negro->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Plastico_negro">
<input type="text" data-field="x__2_Plastico_negro" name="x__2_Plastico_negro" id="x__2_Plastico_negro" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Plastico_negro->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Plastico_negro->EditValue ?>"<?php echo $view_inv->_2_Plastico_negro->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Plastico_negro">
<span<?php echo $view_inv->_2_Plastico_negro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Plastico_negro->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Plastico_negro" name="x__2_Plastico_negro" id="x__2_Plastico_negro" value="<?php echo ew_HtmlEncode($view_inv->_2_Plastico_negro->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Plastico_negro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Tula_tropa->Visible) { // 2_Tula_tropa ?>
	<div id="r__2_Tula_tropa" class="form-group">
		<label id="elh_view_inv__2_Tula_tropa" for="x__2_Tula_tropa" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Tula_tropa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Tula_tropa->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Tula_tropa">
<input type="text" data-field="x__2_Tula_tropa" name="x__2_Tula_tropa" id="x__2_Tula_tropa" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Tula_tropa->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Tula_tropa->EditValue ?>"<?php echo $view_inv->_2_Tula_tropa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Tula_tropa">
<span<?php echo $view_inv->_2_Tula_tropa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Tula_tropa->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Tula_tropa" name="x__2_Tula_tropa" id="x__2_Tula_tropa" value="<?php echo ew_HtmlEncode($view_inv->_2_Tula_tropa->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Tula_tropa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_2_Camilla->Visible) { // 2_Camilla ?>
	<div id="r__2_Camilla" class="form-group">
		<label id="elh_view_inv__2_Camilla" for="x__2_Camilla" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_2_Camilla->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_2_Camilla->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__2_Camilla">
<input type="text" data-field="x__2_Camilla" name="x__2_Camilla" id="x__2_Camilla" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_2_Camilla->PlaceHolder) ?>" value="<?php echo $view_inv->_2_Camilla->EditValue ?>"<?php echo $view_inv->_2_Camilla->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__2_Camilla">
<span<?php echo $view_inv->_2_Camilla->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_2_Camilla->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Camilla" name="x__2_Camilla" id="x__2_Camilla" value="<?php echo ew_HtmlEncode($view_inv->_2_Camilla->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_2_Camilla->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Herramientas->Visible) { // Herramientas ?>
	<div id="r_Herramientas" class="form-group">
		<label id="elh_view_inv_Herramientas" for="x_Herramientas" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Herramientas->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Herramientas->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Herramientas">
<input type="text" data-field="x_Herramientas" name="x_Herramientas" id="x_Herramientas" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->Herramientas->PlaceHolder) ?>" value="<?php echo $view_inv->Herramientas->EditValue ?>"<?php echo $view_inv->Herramientas->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv_Herramientas">
<span<?php echo $view_inv->Herramientas->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Herramientas->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Herramientas" name="x_Herramientas" id="x_Herramientas" value="<?php echo ew_HtmlEncode($view_inv->Herramientas->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Herramientas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Abrazadera->Visible) { // 3_Abrazadera ?>
	<div id="r__3_Abrazadera" class="form-group">
		<label id="elh_view_inv__3_Abrazadera" for="x__3_Abrazadera" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Abrazadera->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Abrazadera->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Abrazadera">
<span<?php echo $view_inv->_3_Abrazadera->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Abrazadera->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Abrazadera" name="x__3_Abrazadera" id="x__3_Abrazadera" value="<?php echo ew_HtmlEncode($view_inv->_3_Abrazadera->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_inv__3_Abrazadera">
<span<?php echo $view_inv->_3_Abrazadera->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Abrazadera->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Abrazadera" name="x__3_Abrazadera" id="x__3_Abrazadera" value="<?php echo ew_HtmlEncode($view_inv->_3_Abrazadera->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Abrazadera->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Aspersora->Visible) { // 3_Aspersora ?>
	<div id="r__3_Aspersora" class="form-group">
		<label id="elh_view_inv__3_Aspersora" for="x__3_Aspersora" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Aspersora->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Aspersora->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Aspersora">
<input type="text" data-field="x__3_Aspersora" name="x__3_Aspersora" id="x__3_Aspersora" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Aspersora->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Aspersora->EditValue ?>"<?php echo $view_inv->_3_Aspersora->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Aspersora">
<span<?php echo $view_inv->_3_Aspersora->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Aspersora->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Aspersora" name="x__3_Aspersora" id="x__3_Aspersora" value="<?php echo ew_HtmlEncode($view_inv->_3_Aspersora->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Aspersora->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Cabo_hacha->Visible) { // 3_Cabo_hacha ?>
	<div id="r__3_Cabo_hacha" class="form-group">
		<label id="elh_view_inv__3_Cabo_hacha" for="x__3_Cabo_hacha" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Cabo_hacha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Cabo_hacha->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Cabo_hacha">
<input type="text" data-field="x__3_Cabo_hacha" name="x__3_Cabo_hacha" id="x__3_Cabo_hacha" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Cabo_hacha->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Cabo_hacha->EditValue ?>"<?php echo $view_inv->_3_Cabo_hacha->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Cabo_hacha">
<span<?php echo $view_inv->_3_Cabo_hacha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Cabo_hacha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Cabo_hacha" name="x__3_Cabo_hacha" id="x__3_Cabo_hacha" value="<?php echo ew_HtmlEncode($view_inv->_3_Cabo_hacha->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Cabo_hacha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Funda_machete->Visible) { // 3_Funda_machete ?>
	<div id="r__3_Funda_machete" class="form-group">
		<label id="elh_view_inv__3_Funda_machete" for="x__3_Funda_machete" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Funda_machete->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Funda_machete->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Funda_machete">
<input type="text" data-field="x__3_Funda_machete" name="x__3_Funda_machete" id="x__3_Funda_machete" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Funda_machete->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Funda_machete->EditValue ?>"<?php echo $view_inv->_3_Funda_machete->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Funda_machete">
<span<?php echo $view_inv->_3_Funda_machete->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Funda_machete->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Funda_machete" name="x__3_Funda_machete" id="x__3_Funda_machete" value="<?php echo ew_HtmlEncode($view_inv->_3_Funda_machete->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Funda_machete->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Glifosato_4lt->Visible) { // 3_Glifosato_4lt ?>
	<div id="r__3_Glifosato_4lt" class="form-group">
		<label id="elh_view_inv__3_Glifosato_4lt" for="x__3_Glifosato_4lt" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Glifosato_4lt->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Glifosato_4lt->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Glifosato_4lt">
<input type="text" data-field="x__3_Glifosato_4lt" name="x__3_Glifosato_4lt" id="x__3_Glifosato_4lt" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Glifosato_4lt->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Glifosato_4lt->EditValue ?>"<?php echo $view_inv->_3_Glifosato_4lt->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Glifosato_4lt">
<span<?php echo $view_inv->_3_Glifosato_4lt->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Glifosato_4lt->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Glifosato_4lt" name="x__3_Glifosato_4lt" id="x__3_Glifosato_4lt" value="<?php echo ew_HtmlEncode($view_inv->_3_Glifosato_4lt->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Glifosato_4lt->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Hacha->Visible) { // 3_Hacha ?>
	<div id="r__3_Hacha" class="form-group">
		<label id="elh_view_inv__3_Hacha" for="x__3_Hacha" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Hacha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Hacha->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Hacha">
<input type="text" data-field="x__3_Hacha" name="x__3_Hacha" id="x__3_Hacha" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Hacha->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Hacha->EditValue ?>"<?php echo $view_inv->_3_Hacha->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Hacha">
<span<?php echo $view_inv->_3_Hacha->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Hacha->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Hacha" name="x__3_Hacha" id="x__3_Hacha" value="<?php echo ew_HtmlEncode($view_inv->_3_Hacha->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Hacha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Lima_12_uni->Visible) { // 3_Lima_12_uni ?>
	<div id="r__3_Lima_12_uni" class="form-group">
		<label id="elh_view_inv__3_Lima_12_uni" for="x__3_Lima_12_uni" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Lima_12_uni->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Lima_12_uni->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Lima_12_uni">
<input type="text" data-field="x__3_Lima_12_uni" name="x__3_Lima_12_uni" id="x__3_Lima_12_uni" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Lima_12_uni->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Lima_12_uni->EditValue ?>"<?php echo $view_inv->_3_Lima_12_uni->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Lima_12_uni">
<span<?php echo $view_inv->_3_Lima_12_uni->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Lima_12_uni->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Lima_12_uni" name="x__3_Lima_12_uni" id="x__3_Lima_12_uni" value="<?php echo ew_HtmlEncode($view_inv->_3_Lima_12_uni->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Lima_12_uni->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Llave_mixta->Visible) { // 3_Llave_mixta ?>
	<div id="r__3_Llave_mixta" class="form-group">
		<label id="elh_view_inv__3_Llave_mixta" for="x__3_Llave_mixta" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Llave_mixta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Llave_mixta->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Llave_mixta">
<input type="text" data-field="x__3_Llave_mixta" name="x__3_Llave_mixta" id="x__3_Llave_mixta" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Llave_mixta->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Llave_mixta->EditValue ?>"<?php echo $view_inv->_3_Llave_mixta->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Llave_mixta">
<span<?php echo $view_inv->_3_Llave_mixta->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Llave_mixta->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Llave_mixta" name="x__3_Llave_mixta" id="x__3_Llave_mixta" value="<?php echo ew_HtmlEncode($view_inv->_3_Llave_mixta->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Llave_mixta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Machete->Visible) { // 3_Machete ?>
	<div id="r__3_Machete" class="form-group">
		<label id="elh_view_inv__3_Machete" for="x__3_Machete" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Machete->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Machete->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Machete">
<input type="text" data-field="x__3_Machete" name="x__3_Machete" id="x__3_Machete" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Machete->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Machete->EditValue ?>"<?php echo $view_inv->_3_Machete->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Machete">
<span<?php echo $view_inv->_3_Machete->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Machete->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Machete" name="x__3_Machete" id="x__3_Machete" value="<?php echo ew_HtmlEncode($view_inv->_3_Machete->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Machete->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Gafa_traslucida->Visible) { // 3_Gafa_traslucida ?>
	<div id="r__3_Gafa_traslucida" class="form-group">
		<label id="elh_view_inv__3_Gafa_traslucida" for="x__3_Gafa_traslucida" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Gafa_traslucida->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Gafa_traslucida->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Gafa_traslucida">
<input type="text" data-field="x__3_Gafa_traslucida" name="x__3_Gafa_traslucida" id="x__3_Gafa_traslucida" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Gafa_traslucida->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Gafa_traslucida->EditValue ?>"<?php echo $view_inv->_3_Gafa_traslucida->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Gafa_traslucida">
<span<?php echo $view_inv->_3_Gafa_traslucida->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Gafa_traslucida->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Gafa_traslucida" name="x__3_Gafa_traslucida" id="x__3_Gafa_traslucida" value="<?php echo ew_HtmlEncode($view_inv->_3_Gafa_traslucida->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Gafa_traslucida->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Motosierra->Visible) { // 3_Motosierra ?>
	<div id="r__3_Motosierra" class="form-group">
		<label id="elh_view_inv__3_Motosierra" for="x__3_Motosierra" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Motosierra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Motosierra->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Motosierra">
<input type="text" data-field="x__3_Motosierra" name="x__3_Motosierra" id="x__3_Motosierra" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Motosierra->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Motosierra->EditValue ?>"<?php echo $view_inv->_3_Motosierra->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Motosierra">
<span<?php echo $view_inv->_3_Motosierra->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Motosierra->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Motosierra" name="x__3_Motosierra" id="x__3_Motosierra" value="<?php echo ew_HtmlEncode($view_inv->_3_Motosierra->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Motosierra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Palin->Visible) { // 3_Palin ?>
	<div id="r__3_Palin" class="form-group">
		<label id="elh_view_inv__3_Palin" for="x__3_Palin" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Palin->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Palin->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Palin">
<input type="text" data-field="x__3_Palin" name="x__3_Palin" id="x__3_Palin" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Palin->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Palin->EditValue ?>"<?php echo $view_inv->_3_Palin->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Palin">
<span<?php echo $view_inv->_3_Palin->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Palin->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Palin" name="x__3_Palin" id="x__3_Palin" value="<?php echo ew_HtmlEncode($view_inv->_3_Palin->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Palin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->_3_Tubo_galvanizado->Visible) { // 3_Tubo_galvanizado ?>
	<div id="r__3_Tubo_galvanizado" class="form-group">
		<label id="elh_view_inv__3_Tubo_galvanizado" for="x__3_Tubo_galvanizado" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->_3_Tubo_galvanizado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->_3_Tubo_galvanizado->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv__3_Tubo_galvanizado">
<input type="text" data-field="x__3_Tubo_galvanizado" name="x__3_Tubo_galvanizado" id="x__3_Tubo_galvanizado" size="30" placeholder="<?php echo ew_HtmlEncode($view_inv->_3_Tubo_galvanizado->PlaceHolder) ?>" value="<?php echo $view_inv->_3_Tubo_galvanizado->EditValue ?>"<?php echo $view_inv->_3_Tubo_galvanizado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_inv__3_Tubo_galvanizado">
<span<?php echo $view_inv->_3_Tubo_galvanizado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->_3_Tubo_galvanizado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Tubo_galvanizado" name="x__3_Tubo_galvanizado" id="x__3_Tubo_galvanizado" value="<?php echo ew_HtmlEncode($view_inv->_3_Tubo_galvanizado->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->_3_Tubo_galvanizado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_inv->Modificado->Visible) { // Modificado ?>
	<div id="r_Modificado" class="form-group">
		<label id="elh_view_inv_Modificado" for="x_Modificado" class="col-sm-2 control-label ewLabel"><?php echo $view_inv->Modificado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $view_inv->Modificado->CellAttributes() ?>>
<?php if ($view_inv->CurrentAction <> "F") { ?>
<span id="el_view_inv_Modificado">
<select data-field="x_Modificado" id="x_Modificado" name="x_Modificado"<?php echo $view_inv->Modificado->EditAttributes() ?>>
<?php
if (is_array($view_inv->Modificado->EditValue)) {
	$arwrk = $view_inv->Modificado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_inv->Modificado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<span id="el_view_inv_Modificado">
<span<?php echo $view_inv->Modificado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_inv->Modificado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Modificado" name="x_Modificado" id="x_Modificado" value="<?php echo ew_HtmlEncode($view_inv->Modificado->FormValue) ?>">
<?php } ?>
<?php echo $view_inv->Modificado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<?php if ($view_inv->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div>
</div>
</form>
<script type="text/javascript">
fview_invedit.Init();
</script>
<?php
$view_inv_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_inv_edit->Page_Terminate();
?>
