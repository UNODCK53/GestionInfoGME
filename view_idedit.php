<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view_idinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view_id_edit = NULL; // Initialize page object first

class cview_id_edit extends cview_id {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_id';

	// Page object name
	var $PageObjName = 'view_id_edit';

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

		// Table object (view_id)
		if (!isset($GLOBALS["view_id"]) || get_class($GLOBALS["view_id"]) == "cview_id") {
			$GLOBALS["view_id"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_id"];
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
			define("EW_TABLE_NAME", 'view_id', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view_idlist.php"));
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
		global $EW_EXPORT, $view_id;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_id);
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
			$this->Page_Terminate("view_idlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("view_idlist.php"); // No matching record, return to list
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
		if (!$this->NOM_PGE->FldIsDetailKey) {
			$this->NOM_PGE->setFormValue($objForm->GetValue("x_NOM_PGE"));
		}
		if (!$this->Otro_NOM_PGE->FldIsDetailKey) {
			$this->Otro_NOM_PGE->setFormValue($objForm->GetValue("x_Otro_NOM_PGE"));
		}
		if (!$this->Otro_CC_PGE->FldIsDetailKey) {
			$this->Otro_CC_PGE->setFormValue($objForm->GetValue("x_Otro_CC_PGE"));
		}
		if (!$this->TIPO_INFORME->FldIsDetailKey) {
			$this->TIPO_INFORME->setFormValue($objForm->GetValue("x_TIPO_INFORME"));
		}
		if (!$this->FECHA_REPORT->FldIsDetailKey) {
			$this->FECHA_REPORT->setFormValue($objForm->GetValue("x_FECHA_REPORT"));
		}
		if (!$this->DIA->FldIsDetailKey) {
			$this->DIA->setFormValue($objForm->GetValue("x_DIA"));
		}
		if (!$this->MES->FldIsDetailKey) {
			$this->MES->setFormValue($objForm->GetValue("x_MES"));
		}
		if (!$this->Departamento->FldIsDetailKey) {
			$this->Departamento->setFormValue($objForm->GetValue("x_Departamento"));
		}
		if (!$this->Muncipio->FldIsDetailKey) {
			$this->Muncipio->setFormValue($objForm->GetValue("x_Muncipio"));
		}
		if (!$this->TEMA->FldIsDetailKey) {
			$this->TEMA->setFormValue($objForm->GetValue("x_TEMA"));
		}
		if (!$this->Otro_Tema->FldIsDetailKey) {
			$this->Otro_Tema->setFormValue($objForm->GetValue("x_Otro_Tema"));
		}
		if (!$this->OBSERVACION->FldIsDetailKey) {
			$this->OBSERVACION->setFormValue($objForm->GetValue("x_OBSERVACION"));
		}
		if (!$this->FUERZA->FldIsDetailKey) {
			$this->FUERZA->setFormValue($objForm->GetValue("x_FUERZA"));
		}
		if (!$this->NOM_VDA->FldIsDetailKey) {
			$this->NOM_VDA->setFormValue($objForm->GetValue("x_NOM_VDA"));
		}
		if (!$this->Ha_Coca->FldIsDetailKey) {
			$this->Ha_Coca->setFormValue($objForm->GetValue("x_Ha_Coca"));
		}
		if (!$this->Ha_Amapola->FldIsDetailKey) {
			$this->Ha_Amapola->setFormValue($objForm->GetValue("x_Ha_Amapola"));
		}
		if (!$this->Ha_Marihuana->FldIsDetailKey) {
			$this->Ha_Marihuana->setFormValue($objForm->GetValue("x_Ha_Marihuana"));
		}
		if (!$this->T_erradi->FldIsDetailKey) {
			$this->T_erradi->setFormValue($objForm->GetValue("x_T_erradi"));
		}
		if (!$this->GRA_LAT_Sector->FldIsDetailKey) {
			$this->GRA_LAT_Sector->setFormValue($objForm->GetValue("x_GRA_LAT_Sector"));
		}
		if (!$this->MIN_LAT_Sector->FldIsDetailKey) {
			$this->MIN_LAT_Sector->setFormValue($objForm->GetValue("x_MIN_LAT_Sector"));
		}
		if (!$this->SEG_LAT_Sector->FldIsDetailKey) {
			$this->SEG_LAT_Sector->setFormValue($objForm->GetValue("x_SEG_LAT_Sector"));
		}
		if (!$this->GRA_LONG_Sector->FldIsDetailKey) {
			$this->GRA_LONG_Sector->setFormValue($objForm->GetValue("x_GRA_LONG_Sector"));
		}
		if (!$this->MIN_LONG_Sector->FldIsDetailKey) {
			$this->MIN_LONG_Sector->setFormValue($objForm->GetValue("x_MIN_LONG_Sector"));
		}
		if (!$this->SEG_LONG_Sector->FldIsDetailKey) {
			$this->SEG_LONG_Sector->setFormValue($objForm->GetValue("x_SEG_LONG_Sector"));
		}
		if (!$this->Ini_Jorna->FldIsDetailKey) {
			$this->Ini_Jorna->setFormValue($objForm->GetValue("x_Ini_Jorna"));
		}
		if (!$this->Fin_Jorna->FldIsDetailKey) {
			$this->Fin_Jorna->setFormValue($objForm->GetValue("x_Fin_Jorna"));
		}
		if (!$this->Situ_Especial->FldIsDetailKey) {
			$this->Situ_Especial->setFormValue($objForm->GetValue("x_Situ_Especial"));
		}
		if (!$this->Adm_GME->FldIsDetailKey) {
			$this->Adm_GME->setFormValue($objForm->GetValue("x_Adm_GME"));
		}
		if (!$this->_1_Abastecimiento->FldIsDetailKey) {
			$this->_1_Abastecimiento->setFormValue($objForm->GetValue("x__1_Abastecimiento"));
		}
		if (!$this->_1_Acompanamiento_firma_GME->FldIsDetailKey) {
			$this->_1_Acompanamiento_firma_GME->setFormValue($objForm->GetValue("x__1_Acompanamiento_firma_GME"));
		}
		if (!$this->_1_Apoyo_zonal_sin_punto_asignado->FldIsDetailKey) {
			$this->_1_Apoyo_zonal_sin_punto_asignado->setFormValue($objForm->GetValue("x__1_Apoyo_zonal_sin_punto_asignado"));
		}
		if (!$this->_1_Descanso_en_dia_habil->FldIsDetailKey) {
			$this->_1_Descanso_en_dia_habil->setFormValue($objForm->GetValue("x__1_Descanso_en_dia_habil"));
		}
		if (!$this->_1_Descanso_festivo_dominical->FldIsDetailKey) {
			$this->_1_Descanso_festivo_dominical->setFormValue($objForm->GetValue("x__1_Descanso_festivo_dominical"));
		}
		if (!$this->_1_Dia_compensatorio->FldIsDetailKey) {
			$this->_1_Dia_compensatorio->setFormValue($objForm->GetValue("x__1_Dia_compensatorio"));
		}
		if (!$this->_1_Erradicacion_en_dia_festivo->FldIsDetailKey) {
			$this->_1_Erradicacion_en_dia_festivo->setFormValue($objForm->GetValue("x__1_Erradicacion_en_dia_festivo"));
		}
		if (!$this->_1_Espera_helicoptero_Helistar->FldIsDetailKey) {
			$this->_1_Espera_helicoptero_Helistar->setFormValue($objForm->GetValue("x__1_Espera_helicoptero_Helistar"));
		}
		if (!$this->_1_Extraccion->FldIsDetailKey) {
			$this->_1_Extraccion->setFormValue($objForm->GetValue("x__1_Extraccion"));
		}
		if (!$this->_1_Firma_contrato_GME->FldIsDetailKey) {
			$this->_1_Firma_contrato_GME->setFormValue($objForm->GetValue("x__1_Firma_contrato_GME"));
		}
		if (!$this->_1_Induccion_Apoyo_Zonal->FldIsDetailKey) {
			$this->_1_Induccion_Apoyo_Zonal->setFormValue($objForm->GetValue("x__1_Induccion_Apoyo_Zonal"));
		}
		if (!$this->_1_Insercion->FldIsDetailKey) {
			$this->_1_Insercion->setFormValue($objForm->GetValue("x__1_Insercion"));
		}
		if (!$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldIsDetailKey) {
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->setFormValue($objForm->GetValue("x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"));
		}
		if (!$this->_1_Novedad_apoyo_zonal->FldIsDetailKey) {
			$this->_1_Novedad_apoyo_zonal->setFormValue($objForm->GetValue("x__1_Novedad_apoyo_zonal"));
		}
		if (!$this->_1_Novedad_enfermero->FldIsDetailKey) {
			$this->_1_Novedad_enfermero->setFormValue($objForm->GetValue("x__1_Novedad_enfermero"));
		}
		if (!$this->_1_Punto_fuera_del_area_de_erradicacion->FldIsDetailKey) {
			$this->_1_Punto_fuera_del_area_de_erradicacion->setFormValue($objForm->GetValue("x__1_Punto_fuera_del_area_de_erradicacion"));
		}
		if (!$this->_1_Transporte_bus->FldIsDetailKey) {
			$this->_1_Transporte_bus->setFormValue($objForm->GetValue("x__1_Transporte_bus"));
		}
		if (!$this->_1_Traslado_apoyo_zonal->FldIsDetailKey) {
			$this->_1_Traslado_apoyo_zonal->setFormValue($objForm->GetValue("x__1_Traslado_apoyo_zonal"));
		}
		if (!$this->_1_Traslado_area_vivac->FldIsDetailKey) {
			$this->_1_Traslado_area_vivac->setFormValue($objForm->GetValue("x__1_Traslado_area_vivac"));
		}
		if (!$this->Adm_Fuerza->FldIsDetailKey) {
			$this->Adm_Fuerza->setFormValue($objForm->GetValue("x_Adm_Fuerza"));
		}
		if (!$this->_2_A_la_espera_definicion_nuevo_punto_FP->FldIsDetailKey) {
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->setFormValue($objForm->GetValue("x__2_A_la_espera_definicion_nuevo_punto_FP"));
		}
		if (!$this->_2_Espera_helicoptero_FP_de_seguridad->FldIsDetailKey) {
			$this->_2_Espera_helicoptero_FP_de_seguridad->setFormValue($objForm->GetValue("x__2_Espera_helicoptero_FP_de_seguridad"));
		}
		if (!$this->_2_Espera_helicoptero_FP_que_abastece->FldIsDetailKey) {
			$this->_2_Espera_helicoptero_FP_que_abastece->setFormValue($objForm->GetValue("x__2_Espera_helicoptero_FP_que_abastece"));
		}
		if (!$this->_2_Induccion_FP->FldIsDetailKey) {
			$this->_2_Induccion_FP->setFormValue($objForm->GetValue("x__2_Induccion_FP"));
		}
		if (!$this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldIsDetailKey) {
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setFormValue($objForm->GetValue("x__2_Novedad_canino_o_del_grupo_de_deteccion"));
		}
		if (!$this->_2_Problemas_fuerza_publica->FldIsDetailKey) {
			$this->_2_Problemas_fuerza_publica->setFormValue($objForm->GetValue("x__2_Problemas_fuerza_publica"));
		}
		if (!$this->_2_Sin_seguridad->FldIsDetailKey) {
			$this->_2_Sin_seguridad->setFormValue($objForm->GetValue("x__2_Sin_seguridad"));
		}
		if (!$this->Sit_Seguridad->FldIsDetailKey) {
			$this->Sit_Seguridad->setFormValue($objForm->GetValue("x_Sit_Seguridad"));
		}
		if (!$this->_3_AEI_controlado->FldIsDetailKey) {
			$this->_3_AEI_controlado->setFormValue($objForm->GetValue("x__3_AEI_controlado"));
		}
		if (!$this->_3_AEI_no_controlado->FldIsDetailKey) {
			$this->_3_AEI_no_controlado->setFormValue($objForm->GetValue("x__3_AEI_no_controlado"));
		}
		if (!$this->_3_Bloqueo_parcial_de_la_comunidad->FldIsDetailKey) {
			$this->_3_Bloqueo_parcial_de_la_comunidad->setFormValue($objForm->GetValue("x__3_Bloqueo_parcial_de_la_comunidad"));
		}
		if (!$this->_3_Bloqueo_total_de_la_comunidad->FldIsDetailKey) {
			$this->_3_Bloqueo_total_de_la_comunidad->setFormValue($objForm->GetValue("x__3_Bloqueo_total_de_la_comunidad"));
		}
		if (!$this->_3_Combate->FldIsDetailKey) {
			$this->_3_Combate->setFormValue($objForm->GetValue("x__3_Combate"));
		}
		if (!$this->_3_Hostigamiento->FldIsDetailKey) {
			$this->_3_Hostigamiento->setFormValue($objForm->GetValue("x__3_Hostigamiento"));
		}
		if (!$this->_3_MAP_Controlada->FldIsDetailKey) {
			$this->_3_MAP_Controlada->setFormValue($objForm->GetValue("x__3_MAP_Controlada"));
		}
		if (!$this->_3_MAP_No_controlada->FldIsDetailKey) {
			$this->_3_MAP_No_controlada->setFormValue($objForm->GetValue("x__3_MAP_No_controlada"));
		}
		if (!$this->_3_MUSE->FldIsDetailKey) {
			$this->_3_MUSE->setFormValue($objForm->GetValue("x__3_MUSE"));
		}
		if (!$this->_3_Operaciones_de_seguridad->FldIsDetailKey) {
			$this->_3_Operaciones_de_seguridad->setFormValue($objForm->GetValue("x__3_Operaciones_de_seguridad"));
		}
		if (!$this->GRA_LAT_segurid->FldIsDetailKey) {
			$this->GRA_LAT_segurid->setFormValue($objForm->GetValue("x_GRA_LAT_segurid"));
		}
		if (!$this->MIN_LAT_segurid->FldIsDetailKey) {
			$this->MIN_LAT_segurid->setFormValue($objForm->GetValue("x_MIN_LAT_segurid"));
		}
		if (!$this->SEG_LAT_segurid->FldIsDetailKey) {
			$this->SEG_LAT_segurid->setFormValue($objForm->GetValue("x_SEG_LAT_segurid"));
		}
		if (!$this->GRA_LONG_seguri->FldIsDetailKey) {
			$this->GRA_LONG_seguri->setFormValue($objForm->GetValue("x_GRA_LONG_seguri"));
		}
		if (!$this->MIN_LONG_seguri->FldIsDetailKey) {
			$this->MIN_LONG_seguri->setFormValue($objForm->GetValue("x_MIN_LONG_seguri"));
		}
		if (!$this->SEG_LONG_seguri->FldIsDetailKey) {
			$this->SEG_LONG_seguri->setFormValue($objForm->GetValue("x_SEG_LONG_seguri"));
		}
		if (!$this->Novedad->FldIsDetailKey) {
			$this->Novedad->setFormValue($objForm->GetValue("x_Novedad"));
		}
		if (!$this->_4_Epidemia->FldIsDetailKey) {
			$this->_4_Epidemia->setFormValue($objForm->GetValue("x__4_Epidemia"));
		}
		if (!$this->_4_Novedad_climatologica->FldIsDetailKey) {
			$this->_4_Novedad_climatologica->setFormValue($objForm->GetValue("x__4_Novedad_climatologica"));
		}
		if (!$this->_4_Registro_de_cultivos->FldIsDetailKey) {
			$this->_4_Registro_de_cultivos->setFormValue($objForm->GetValue("x__4_Registro_de_cultivos"));
		}
		if (!$this->_4_Zona_con_cultivos_muy_dispersos->FldIsDetailKey) {
			$this->_4_Zona_con_cultivos_muy_dispersos->setFormValue($objForm->GetValue("x__4_Zona_con_cultivos_muy_dispersos"));
		}
		if (!$this->_4_Zona_de_cruce_de_rios_caudalosos->FldIsDetailKey) {
			$this->_4_Zona_de_cruce_de_rios_caudalosos->setFormValue($objForm->GetValue("x__4_Zona_de_cruce_de_rios_caudalosos"));
		}
		if (!$this->_4_Zona_sin_cultivos->FldIsDetailKey) {
			$this->_4_Zona_sin_cultivos->setFormValue($objForm->GetValue("x__4_Zona_sin_cultivos"));
		}
		if (!$this->Num_Erra_Salen->FldIsDetailKey) {
			$this->Num_Erra_Salen->setFormValue($objForm->GetValue("x_Num_Erra_Salen"));
		}
		if (!$this->Num_Erra_Quedan->FldIsDetailKey) {
			$this->Num_Erra_Quedan->setFormValue($objForm->GetValue("x_Num_Erra_Quedan"));
		}
		if (!$this->No_ENFERMERO->FldIsDetailKey) {
			$this->No_ENFERMERO->setFormValue($objForm->GetValue("x_No_ENFERMERO"));
		}
		if (!$this->NUM_FP->FldIsDetailKey) {
			$this->NUM_FP->setFormValue($objForm->GetValue("x_NUM_FP"));
		}
		if (!$this->NUM_Perso_EVA->FldIsDetailKey) {
			$this->NUM_Perso_EVA->setFormValue($objForm->GetValue("x_NUM_Perso_EVA"));
		}
		if (!$this->NUM_Poli->FldIsDetailKey) {
			$this->NUM_Poli->setFormValue($objForm->GetValue("x_NUM_Poli"));
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
		$this->F_Sincron->CurrentValue = ew_UnFormatDateTime($this->F_Sincron->CurrentValue, 5);
		$this->USUARIO->CurrentValue = $this->USUARIO->FormValue;
		$this->Cargo_gme->CurrentValue = $this->Cargo_gme->FormValue;
		$this->NOM_PE->CurrentValue = $this->NOM_PE->FormValue;
		$this->Otro_PE->CurrentValue = $this->Otro_PE->FormValue;
		$this->NOM_PGE->CurrentValue = $this->NOM_PGE->FormValue;
		$this->Otro_NOM_PGE->CurrentValue = $this->Otro_NOM_PGE->FormValue;
		$this->Otro_CC_PGE->CurrentValue = $this->Otro_CC_PGE->FormValue;
		$this->TIPO_INFORME->CurrentValue = $this->TIPO_INFORME->FormValue;
		$this->FECHA_REPORT->CurrentValue = $this->FECHA_REPORT->FormValue;
		$this->DIA->CurrentValue = $this->DIA->FormValue;
		$this->MES->CurrentValue = $this->MES->FormValue;
		$this->Departamento->CurrentValue = $this->Departamento->FormValue;
		$this->Muncipio->CurrentValue = $this->Muncipio->FormValue;
		$this->TEMA->CurrentValue = $this->TEMA->FormValue;
		$this->Otro_Tema->CurrentValue = $this->Otro_Tema->FormValue;
		$this->OBSERVACION->CurrentValue = $this->OBSERVACION->FormValue;
		$this->FUERZA->CurrentValue = $this->FUERZA->FormValue;
		$this->NOM_VDA->CurrentValue = $this->NOM_VDA->FormValue;
		$this->Ha_Coca->CurrentValue = $this->Ha_Coca->FormValue;
		$this->Ha_Amapola->CurrentValue = $this->Ha_Amapola->FormValue;
		$this->Ha_Marihuana->CurrentValue = $this->Ha_Marihuana->FormValue;
		$this->T_erradi->CurrentValue = $this->T_erradi->FormValue;
		$this->GRA_LAT_Sector->CurrentValue = $this->GRA_LAT_Sector->FormValue;
		$this->MIN_LAT_Sector->CurrentValue = $this->MIN_LAT_Sector->FormValue;
		$this->SEG_LAT_Sector->CurrentValue = $this->SEG_LAT_Sector->FormValue;
		$this->GRA_LONG_Sector->CurrentValue = $this->GRA_LONG_Sector->FormValue;
		$this->MIN_LONG_Sector->CurrentValue = $this->MIN_LONG_Sector->FormValue;
		$this->SEG_LONG_Sector->CurrentValue = $this->SEG_LONG_Sector->FormValue;
		$this->Ini_Jorna->CurrentValue = $this->Ini_Jorna->FormValue;
		$this->Fin_Jorna->CurrentValue = $this->Fin_Jorna->FormValue;
		$this->Situ_Especial->CurrentValue = $this->Situ_Especial->FormValue;
		$this->Adm_GME->CurrentValue = $this->Adm_GME->FormValue;
		$this->_1_Abastecimiento->CurrentValue = $this->_1_Abastecimiento->FormValue;
		$this->_1_Acompanamiento_firma_GME->CurrentValue = $this->_1_Acompanamiento_firma_GME->FormValue;
		$this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue = $this->_1_Apoyo_zonal_sin_punto_asignado->FormValue;
		$this->_1_Descanso_en_dia_habil->CurrentValue = $this->_1_Descanso_en_dia_habil->FormValue;
		$this->_1_Descanso_festivo_dominical->CurrentValue = $this->_1_Descanso_festivo_dominical->FormValue;
		$this->_1_Dia_compensatorio->CurrentValue = $this->_1_Dia_compensatorio->FormValue;
		$this->_1_Erradicacion_en_dia_festivo->CurrentValue = $this->_1_Erradicacion_en_dia_festivo->FormValue;
		$this->_1_Espera_helicoptero_Helistar->CurrentValue = $this->_1_Espera_helicoptero_Helistar->FormValue;
		$this->_1_Extraccion->CurrentValue = $this->_1_Extraccion->FormValue;
		$this->_1_Firma_contrato_GME->CurrentValue = $this->_1_Firma_contrato_GME->FormValue;
		$this->_1_Induccion_Apoyo_Zonal->CurrentValue = $this->_1_Induccion_Apoyo_Zonal->FormValue;
		$this->_1_Insercion->CurrentValue = $this->_1_Insercion->FormValue;
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue = $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FormValue;
		$this->_1_Novedad_apoyo_zonal->CurrentValue = $this->_1_Novedad_apoyo_zonal->FormValue;
		$this->_1_Novedad_enfermero->CurrentValue = $this->_1_Novedad_enfermero->FormValue;
		$this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue = $this->_1_Punto_fuera_del_area_de_erradicacion->FormValue;
		$this->_1_Transporte_bus->CurrentValue = $this->_1_Transporte_bus->FormValue;
		$this->_1_Traslado_apoyo_zonal->CurrentValue = $this->_1_Traslado_apoyo_zonal->FormValue;
		$this->_1_Traslado_area_vivac->CurrentValue = $this->_1_Traslado_area_vivac->FormValue;
		$this->Adm_Fuerza->CurrentValue = $this->Adm_Fuerza->FormValue;
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue = $this->_2_A_la_espera_definicion_nuevo_punto_FP->FormValue;
		$this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue = $this->_2_Espera_helicoptero_FP_de_seguridad->FormValue;
		$this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue = $this->_2_Espera_helicoptero_FP_que_abastece->FormValue;
		$this->_2_Induccion_FP->CurrentValue = $this->_2_Induccion_FP->FormValue;
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue = $this->_2_Novedad_canino_o_del_grupo_de_deteccion->FormValue;
		$this->_2_Problemas_fuerza_publica->CurrentValue = $this->_2_Problemas_fuerza_publica->FormValue;
		$this->_2_Sin_seguridad->CurrentValue = $this->_2_Sin_seguridad->FormValue;
		$this->Sit_Seguridad->CurrentValue = $this->Sit_Seguridad->FormValue;
		$this->_3_AEI_controlado->CurrentValue = $this->_3_AEI_controlado->FormValue;
		$this->_3_AEI_no_controlado->CurrentValue = $this->_3_AEI_no_controlado->FormValue;
		$this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue = $this->_3_Bloqueo_parcial_de_la_comunidad->FormValue;
		$this->_3_Bloqueo_total_de_la_comunidad->CurrentValue = $this->_3_Bloqueo_total_de_la_comunidad->FormValue;
		$this->_3_Combate->CurrentValue = $this->_3_Combate->FormValue;
		$this->_3_Hostigamiento->CurrentValue = $this->_3_Hostigamiento->FormValue;
		$this->_3_MAP_Controlada->CurrentValue = $this->_3_MAP_Controlada->FormValue;
		$this->_3_MAP_No_controlada->CurrentValue = $this->_3_MAP_No_controlada->FormValue;
		$this->_3_MUSE->CurrentValue = $this->_3_MUSE->FormValue;
		$this->_3_Operaciones_de_seguridad->CurrentValue = $this->_3_Operaciones_de_seguridad->FormValue;
		$this->GRA_LAT_segurid->CurrentValue = $this->GRA_LAT_segurid->FormValue;
		$this->MIN_LAT_segurid->CurrentValue = $this->MIN_LAT_segurid->FormValue;
		$this->SEG_LAT_segurid->CurrentValue = $this->SEG_LAT_segurid->FormValue;
		$this->GRA_LONG_seguri->CurrentValue = $this->GRA_LONG_seguri->FormValue;
		$this->MIN_LONG_seguri->CurrentValue = $this->MIN_LONG_seguri->FormValue;
		$this->SEG_LONG_seguri->CurrentValue = $this->SEG_LONG_seguri->FormValue;
		$this->Novedad->CurrentValue = $this->Novedad->FormValue;
		$this->_4_Epidemia->CurrentValue = $this->_4_Epidemia->FormValue;
		$this->_4_Novedad_climatologica->CurrentValue = $this->_4_Novedad_climatologica->FormValue;
		$this->_4_Registro_de_cultivos->CurrentValue = $this->_4_Registro_de_cultivos->FormValue;
		$this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue = $this->_4_Zona_con_cultivos_muy_dispersos->FormValue;
		$this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue = $this->_4_Zona_de_cruce_de_rios_caudalosos->FormValue;
		$this->_4_Zona_sin_cultivos->CurrentValue = $this->_4_Zona_sin_cultivos->FormValue;
		$this->Num_Erra_Salen->CurrentValue = $this->Num_Erra_Salen->FormValue;
		$this->Num_Erra_Quedan->CurrentValue = $this->Num_Erra_Quedan->FormValue;
		$this->No_ENFERMERO->CurrentValue = $this->No_ENFERMERO->FormValue;
		$this->NUM_FP->CurrentValue = $this->NUM_FP->FormValue;
		$this->NUM_Perso_EVA->CurrentValue = $this->NUM_Perso_EVA->FormValue;
		$this->NUM_Poli->CurrentValue = $this->NUM_Poli->FormValue;
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
		$this->NOM_PE->setDbValue($rs->fields('NOM_PE'));
		$this->Otro_PE->setDbValue($rs->fields('Otro_PE'));
		$this->NOM_PGE->setDbValue($rs->fields('NOM_PGE'));
		$this->Otro_NOM_PGE->setDbValue($rs->fields('Otro_NOM_PGE'));
		$this->Otro_CC_PGE->setDbValue($rs->fields('Otro_CC_PGE'));
		$this->TIPO_INFORME->setDbValue($rs->fields('TIPO_INFORME'));
		$this->FECHA_REPORT->setDbValue($rs->fields('FECHA_REPORT'));
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->TEMA->setDbValue($rs->fields('TEMA'));
		$this->Otro_Tema->setDbValue($rs->fields('Otro_Tema'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->FUERZA->setDbValue($rs->fields('FUERZA'));
		$this->NOM_VDA->setDbValue($rs->fields('NOM_VDA'));
		$this->Ha_Coca->setDbValue($rs->fields('Ha_Coca'));
		$this->Ha_Amapola->setDbValue($rs->fields('Ha_Amapola'));
		$this->Ha_Marihuana->setDbValue($rs->fields('Ha_Marihuana'));
		$this->T_erradi->setDbValue($rs->fields('T_erradi'));
		$this->LATITUD_sector->setDbValue($rs->fields('LATITUD_sector'));
		$this->GRA_LAT_Sector->setDbValue($rs->fields('GRA_LAT_Sector'));
		$this->MIN_LAT_Sector->setDbValue($rs->fields('MIN_LAT_Sector'));
		$this->SEG_LAT_Sector->setDbValue($rs->fields('SEG_LAT_Sector'));
		$this->GRA_LONG_Sector->setDbValue($rs->fields('GRA_LONG_Sector'));
		$this->MIN_LONG_Sector->setDbValue($rs->fields('MIN_LONG_Sector'));
		$this->SEG_LONG_Sector->setDbValue($rs->fields('SEG_LONG_Sector'));
		$this->Ini_Jorna->setDbValue($rs->fields('Ini_Jorna'));
		$this->Fin_Jorna->setDbValue($rs->fields('Fin_Jorna'));
		$this->Situ_Especial->setDbValue($rs->fields('Situ_Especial'));
		$this->Adm_GME->setDbValue($rs->fields('Adm_GME'));
		$this->_1_Abastecimiento->setDbValue($rs->fields('1_Abastecimiento'));
		$this->_1_Acompanamiento_firma_GME->setDbValue($rs->fields('1_Acompanamiento_firma_GME'));
		$this->_1_Apoyo_zonal_sin_punto_asignado->setDbValue($rs->fields('1_Apoyo_zonal_sin_punto_asignado'));
		$this->_1_Descanso_en_dia_habil->setDbValue($rs->fields('1_Descanso_en_dia_habil'));
		$this->_1_Descanso_festivo_dominical->setDbValue($rs->fields('1_Descanso_festivo_dominical'));
		$this->_1_Dia_compensatorio->setDbValue($rs->fields('1_Dia_compensatorio'));
		$this->_1_Erradicacion_en_dia_festivo->setDbValue($rs->fields('1_Erradicacion_en_dia_festivo'));
		$this->_1_Espera_helicoptero_Helistar->setDbValue($rs->fields('1_Espera_helicoptero_Helistar'));
		$this->_1_Extraccion->setDbValue($rs->fields('1_Extraccion'));
		$this->_1_Firma_contrato_GME->setDbValue($rs->fields('1_Firma_contrato_GME'));
		$this->_1_Induccion_Apoyo_Zonal->setDbValue($rs->fields('1_Induccion_Apoyo_Zonal'));
		$this->_1_Insercion->setDbValue($rs->fields('1_Insercion'));
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->setDbValue($rs->fields('1_Llegada_GME_a_su_lugar_de_Origen_fin_fase'));
		$this->_1_Novedad_apoyo_zonal->setDbValue($rs->fields('1_Novedad_apoyo_zonal'));
		$this->_1_Novedad_enfermero->setDbValue($rs->fields('1_Novedad_enfermero'));
		$this->_1_Punto_fuera_del_area_de_erradicacion->setDbValue($rs->fields('1_Punto_fuera_del_area_de_erradicacion'));
		$this->_1_Transporte_bus->setDbValue($rs->fields('1_Transporte_bus'));
		$this->_1_Traslado_apoyo_zonal->setDbValue($rs->fields('1_Traslado_apoyo_zonal'));
		$this->_1_Traslado_area_vivac->setDbValue($rs->fields('1_Traslado_area_vivac'));
		$this->Adm_Fuerza->setDbValue($rs->fields('Adm_Fuerza'));
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->setDbValue($rs->fields('2_A_la_espera_definicion_nuevo_punto_FP'));
		$this->_2_Espera_helicoptero_FP_de_seguridad->setDbValue($rs->fields('2_Espera_helicoptero_FP_de_seguridad'));
		$this->_2_Espera_helicoptero_FP_que_abastece->setDbValue($rs->fields('2_Espera_helicoptero_FP_que_abastece'));
		$this->_2_Induccion_FP->setDbValue($rs->fields('2_Induccion_FP'));
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setDbValue($rs->fields('2_Novedad_canino_o_del_grupo_de_deteccion'));
		$this->_2_Problemas_fuerza_publica->setDbValue($rs->fields('2_Problemas_fuerza_publica'));
		$this->_2_Sin_seguridad->setDbValue($rs->fields('2_Sin_seguridad'));
		$this->Sit_Seguridad->setDbValue($rs->fields('Sit_Seguridad'));
		$this->_3_AEI_controlado->setDbValue($rs->fields('3_AEI_controlado'));
		$this->_3_AEI_no_controlado->setDbValue($rs->fields('3_AEI_no_controlado'));
		$this->_3_Bloqueo_parcial_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_parcial_de_la_comunidad'));
		$this->_3_Bloqueo_total_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_total_de_la_comunidad'));
		$this->_3_Combate->setDbValue($rs->fields('3_Combate'));
		$this->_3_Hostigamiento->setDbValue($rs->fields('3_Hostigamiento'));
		$this->_3_MAP_Controlada->setDbValue($rs->fields('3_MAP_Controlada'));
		$this->_3_MAP_No_controlada->setDbValue($rs->fields('3_MAP_No_controlada'));
		$this->_3_MUSE->setDbValue($rs->fields('3_MUSE'));
		$this->_3_Operaciones_de_seguridad->setDbValue($rs->fields('3_Operaciones_de_seguridad'));
		$this->LATITUD_segurid->setDbValue($rs->fields('LATITUD_segurid'));
		$this->GRA_LAT_segurid->setDbValue($rs->fields('GRA_LAT_segurid'));
		$this->MIN_LAT_segurid->setDbValue($rs->fields('MIN_LAT_segurid'));
		$this->SEG_LAT_segurid->setDbValue($rs->fields('SEG_LAT_segurid'));
		$this->GRA_LONG_seguri->setDbValue($rs->fields('GRA_LONG_seguri'));
		$this->MIN_LONG_seguri->setDbValue($rs->fields('MIN_LONG_seguri'));
		$this->SEG_LONG_seguri->setDbValue($rs->fields('SEG_LONG_seguri'));
		$this->Novedad->setDbValue($rs->fields('Novedad'));
		$this->_4_Epidemia->setDbValue($rs->fields('4_Epidemia'));
		$this->_4_Novedad_climatologica->setDbValue($rs->fields('4_Novedad_climatologica'));
		$this->_4_Registro_de_cultivos->setDbValue($rs->fields('4_Registro_de_cultivos'));
		$this->_4_Zona_con_cultivos_muy_dispersos->setDbValue($rs->fields('4_Zona_con_cultivos_muy_dispersos'));
		$this->_4_Zona_de_cruce_de_rios_caudalosos->setDbValue($rs->fields('4_Zona_de_cruce_de_rios_caudalosos'));
		$this->_4_Zona_sin_cultivos->setDbValue($rs->fields('4_Zona_sin_cultivos'));
		$this->Num_Erra_Salen->setDbValue($rs->fields('Num_Erra_Salen'));
		$this->Num_Erra_Quedan->setDbValue($rs->fields('Num_Erra_Quedan'));
		$this->No_ENFERMERO->setDbValue($rs->fields('No_ENFERMERO'));
		$this->NUM_FP->setDbValue($rs->fields('NUM_FP'));
		$this->NUM_Perso_EVA->setDbValue($rs->fields('NUM_Perso_EVA'));
		$this->NUM_Poli->setDbValue($rs->fields('NUM_Poli'));
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
		$this->NOM_PE->DbValue = $row['NOM_PE'];
		$this->Otro_PE->DbValue = $row['Otro_PE'];
		$this->NOM_PGE->DbValue = $row['NOM_PGE'];
		$this->Otro_NOM_PGE->DbValue = $row['Otro_NOM_PGE'];
		$this->Otro_CC_PGE->DbValue = $row['Otro_CC_PGE'];
		$this->TIPO_INFORME->DbValue = $row['TIPO_INFORME'];
		$this->FECHA_REPORT->DbValue = $row['FECHA_REPORT'];
		$this->DIA->DbValue = $row['DIA'];
		$this->MES->DbValue = $row['MES'];
		$this->Departamento->DbValue = $row['Departamento'];
		$this->Muncipio->DbValue = $row['Muncipio'];
		$this->TEMA->DbValue = $row['TEMA'];
		$this->Otro_Tema->DbValue = $row['Otro_Tema'];
		$this->OBSERVACION->DbValue = $row['OBSERVACION'];
		$this->FUERZA->DbValue = $row['FUERZA'];
		$this->NOM_VDA->DbValue = $row['NOM_VDA'];
		$this->Ha_Coca->DbValue = $row['Ha_Coca'];
		$this->Ha_Amapola->DbValue = $row['Ha_Amapola'];
		$this->Ha_Marihuana->DbValue = $row['Ha_Marihuana'];
		$this->T_erradi->DbValue = $row['T_erradi'];
		$this->LATITUD_sector->DbValue = $row['LATITUD_sector'];
		$this->GRA_LAT_Sector->DbValue = $row['GRA_LAT_Sector'];
		$this->MIN_LAT_Sector->DbValue = $row['MIN_LAT_Sector'];
		$this->SEG_LAT_Sector->DbValue = $row['SEG_LAT_Sector'];
		$this->GRA_LONG_Sector->DbValue = $row['GRA_LONG_Sector'];
		$this->MIN_LONG_Sector->DbValue = $row['MIN_LONG_Sector'];
		$this->SEG_LONG_Sector->DbValue = $row['SEG_LONG_Sector'];
		$this->Ini_Jorna->DbValue = $row['Ini_Jorna'];
		$this->Fin_Jorna->DbValue = $row['Fin_Jorna'];
		$this->Situ_Especial->DbValue = $row['Situ_Especial'];
		$this->Adm_GME->DbValue = $row['Adm_GME'];
		$this->_1_Abastecimiento->DbValue = $row['1_Abastecimiento'];
		$this->_1_Acompanamiento_firma_GME->DbValue = $row['1_Acompanamiento_firma_GME'];
		$this->_1_Apoyo_zonal_sin_punto_asignado->DbValue = $row['1_Apoyo_zonal_sin_punto_asignado'];
		$this->_1_Descanso_en_dia_habil->DbValue = $row['1_Descanso_en_dia_habil'];
		$this->_1_Descanso_festivo_dominical->DbValue = $row['1_Descanso_festivo_dominical'];
		$this->_1_Dia_compensatorio->DbValue = $row['1_Dia_compensatorio'];
		$this->_1_Erradicacion_en_dia_festivo->DbValue = $row['1_Erradicacion_en_dia_festivo'];
		$this->_1_Espera_helicoptero_Helistar->DbValue = $row['1_Espera_helicoptero_Helistar'];
		$this->_1_Extraccion->DbValue = $row['1_Extraccion'];
		$this->_1_Firma_contrato_GME->DbValue = $row['1_Firma_contrato_GME'];
		$this->_1_Induccion_Apoyo_Zonal->DbValue = $row['1_Induccion_Apoyo_Zonal'];
		$this->_1_Insercion->DbValue = $row['1_Insercion'];
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->DbValue = $row['1_Llegada_GME_a_su_lugar_de_Origen_fin_fase'];
		$this->_1_Novedad_apoyo_zonal->DbValue = $row['1_Novedad_apoyo_zonal'];
		$this->_1_Novedad_enfermero->DbValue = $row['1_Novedad_enfermero'];
		$this->_1_Punto_fuera_del_area_de_erradicacion->DbValue = $row['1_Punto_fuera_del_area_de_erradicacion'];
		$this->_1_Transporte_bus->DbValue = $row['1_Transporte_bus'];
		$this->_1_Traslado_apoyo_zonal->DbValue = $row['1_Traslado_apoyo_zonal'];
		$this->_1_Traslado_area_vivac->DbValue = $row['1_Traslado_area_vivac'];
		$this->Adm_Fuerza->DbValue = $row['Adm_Fuerza'];
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->DbValue = $row['2_A_la_espera_definicion_nuevo_punto_FP'];
		$this->_2_Espera_helicoptero_FP_de_seguridad->DbValue = $row['2_Espera_helicoptero_FP_de_seguridad'];
		$this->_2_Espera_helicoptero_FP_que_abastece->DbValue = $row['2_Espera_helicoptero_FP_que_abastece'];
		$this->_2_Induccion_FP->DbValue = $row['2_Induccion_FP'];
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->DbValue = $row['2_Novedad_canino_o_del_grupo_de_deteccion'];
		$this->_2_Problemas_fuerza_publica->DbValue = $row['2_Problemas_fuerza_publica'];
		$this->_2_Sin_seguridad->DbValue = $row['2_Sin_seguridad'];
		$this->Sit_Seguridad->DbValue = $row['Sit_Seguridad'];
		$this->_3_AEI_controlado->DbValue = $row['3_AEI_controlado'];
		$this->_3_AEI_no_controlado->DbValue = $row['3_AEI_no_controlado'];
		$this->_3_Bloqueo_parcial_de_la_comunidad->DbValue = $row['3_Bloqueo_parcial_de_la_comunidad'];
		$this->_3_Bloqueo_total_de_la_comunidad->DbValue = $row['3_Bloqueo_total_de_la_comunidad'];
		$this->_3_Combate->DbValue = $row['3_Combate'];
		$this->_3_Hostigamiento->DbValue = $row['3_Hostigamiento'];
		$this->_3_MAP_Controlada->DbValue = $row['3_MAP_Controlada'];
		$this->_3_MAP_No_controlada->DbValue = $row['3_MAP_No_controlada'];
		$this->_3_MUSE->DbValue = $row['3_MUSE'];
		$this->_3_Operaciones_de_seguridad->DbValue = $row['3_Operaciones_de_seguridad'];
		$this->LATITUD_segurid->DbValue = $row['LATITUD_segurid'];
		$this->GRA_LAT_segurid->DbValue = $row['GRA_LAT_segurid'];
		$this->MIN_LAT_segurid->DbValue = $row['MIN_LAT_segurid'];
		$this->SEG_LAT_segurid->DbValue = $row['SEG_LAT_segurid'];
		$this->GRA_LONG_seguri->DbValue = $row['GRA_LONG_seguri'];
		$this->MIN_LONG_seguri->DbValue = $row['MIN_LONG_seguri'];
		$this->SEG_LONG_seguri->DbValue = $row['SEG_LONG_seguri'];
		$this->Novedad->DbValue = $row['Novedad'];
		$this->_4_Epidemia->DbValue = $row['4_Epidemia'];
		$this->_4_Novedad_climatologica->DbValue = $row['4_Novedad_climatologica'];
		$this->_4_Registro_de_cultivos->DbValue = $row['4_Registro_de_cultivos'];
		$this->_4_Zona_con_cultivos_muy_dispersos->DbValue = $row['4_Zona_con_cultivos_muy_dispersos'];
		$this->_4_Zona_de_cruce_de_rios_caudalosos->DbValue = $row['4_Zona_de_cruce_de_rios_caudalosos'];
		$this->_4_Zona_sin_cultivos->DbValue = $row['4_Zona_sin_cultivos'];
		$this->Num_Erra_Salen->DbValue = $row['Num_Erra_Salen'];
		$this->Num_Erra_Quedan->DbValue = $row['Num_Erra_Quedan'];
		$this->No_ENFERMERO->DbValue = $row['No_ENFERMERO'];
		$this->NUM_FP->DbValue = $row['NUM_FP'];
		$this->NUM_Perso_EVA->DbValue = $row['NUM_Perso_EVA'];
		$this->NUM_Poli->DbValue = $row['NUM_Poli'];
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

		if ($this->Ha_Coca->FormValue == $this->Ha_Coca->CurrentValue && is_numeric(ew_StrToFloat($this->Ha_Coca->CurrentValue)))
			$this->Ha_Coca->CurrentValue = ew_StrToFloat($this->Ha_Coca->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Ha_Amapola->FormValue == $this->Ha_Amapola->CurrentValue && is_numeric(ew_StrToFloat($this->Ha_Amapola->CurrentValue)))
			$this->Ha_Amapola->CurrentValue = ew_StrToFloat($this->Ha_Amapola->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Ha_Marihuana->FormValue == $this->Ha_Marihuana->CurrentValue && is_numeric(ew_StrToFloat($this->Ha_Marihuana->CurrentValue)))
			$this->Ha_Marihuana->CurrentValue = ew_StrToFloat($this->Ha_Marihuana->CurrentValue);

		// Convert decimal values if posted back
		if ($this->T_erradi->FormValue == $this->T_erradi->CurrentValue && is_numeric(ew_StrToFloat($this->T_erradi->CurrentValue)))
			$this->T_erradi->CurrentValue = ew_StrToFloat($this->T_erradi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LAT_Sector->FormValue == $this->SEG_LAT_Sector->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LAT_Sector->CurrentValue)))
			$this->SEG_LAT_Sector->CurrentValue = ew_StrToFloat($this->SEG_LAT_Sector->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LONG_Sector->FormValue == $this->SEG_LONG_Sector->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LONG_Sector->CurrentValue)))
			$this->SEG_LONG_Sector->CurrentValue = ew_StrToFloat($this->SEG_LONG_Sector->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Adm_GME->FormValue == $this->Adm_GME->CurrentValue && is_numeric(ew_StrToFloat($this->Adm_GME->CurrentValue)))
			$this->Adm_GME->CurrentValue = ew_StrToFloat($this->Adm_GME->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Abastecimiento->FormValue == $this->_1_Abastecimiento->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Abastecimiento->CurrentValue)))
			$this->_1_Abastecimiento->CurrentValue = ew_StrToFloat($this->_1_Abastecimiento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Acompanamiento_firma_GME->FormValue == $this->_1_Acompanamiento_firma_GME->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Acompanamiento_firma_GME->CurrentValue)))
			$this->_1_Acompanamiento_firma_GME->CurrentValue = ew_StrToFloat($this->_1_Acompanamiento_firma_GME->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Apoyo_zonal_sin_punto_asignado->FormValue == $this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue)))
			$this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue = ew_StrToFloat($this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Descanso_en_dia_habil->FormValue == $this->_1_Descanso_en_dia_habil->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Descanso_en_dia_habil->CurrentValue)))
			$this->_1_Descanso_en_dia_habil->CurrentValue = ew_StrToFloat($this->_1_Descanso_en_dia_habil->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Descanso_festivo_dominical->FormValue == $this->_1_Descanso_festivo_dominical->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Descanso_festivo_dominical->CurrentValue)))
			$this->_1_Descanso_festivo_dominical->CurrentValue = ew_StrToFloat($this->_1_Descanso_festivo_dominical->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Dia_compensatorio->FormValue == $this->_1_Dia_compensatorio->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Dia_compensatorio->CurrentValue)))
			$this->_1_Dia_compensatorio->CurrentValue = ew_StrToFloat($this->_1_Dia_compensatorio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Erradicacion_en_dia_festivo->FormValue == $this->_1_Erradicacion_en_dia_festivo->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Erradicacion_en_dia_festivo->CurrentValue)))
			$this->_1_Erradicacion_en_dia_festivo->CurrentValue = ew_StrToFloat($this->_1_Erradicacion_en_dia_festivo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Espera_helicoptero_Helistar->FormValue == $this->_1_Espera_helicoptero_Helistar->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Espera_helicoptero_Helistar->CurrentValue)))
			$this->_1_Espera_helicoptero_Helistar->CurrentValue = ew_StrToFloat($this->_1_Espera_helicoptero_Helistar->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Extraccion->FormValue == $this->_1_Extraccion->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Extraccion->CurrentValue)))
			$this->_1_Extraccion->CurrentValue = ew_StrToFloat($this->_1_Extraccion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Firma_contrato_GME->FormValue == $this->_1_Firma_contrato_GME->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Firma_contrato_GME->CurrentValue)))
			$this->_1_Firma_contrato_GME->CurrentValue = ew_StrToFloat($this->_1_Firma_contrato_GME->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Induccion_Apoyo_Zonal->FormValue == $this->_1_Induccion_Apoyo_Zonal->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Induccion_Apoyo_Zonal->CurrentValue)))
			$this->_1_Induccion_Apoyo_Zonal->CurrentValue = ew_StrToFloat($this->_1_Induccion_Apoyo_Zonal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Insercion->FormValue == $this->_1_Insercion->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Insercion->CurrentValue)))
			$this->_1_Insercion->CurrentValue = ew_StrToFloat($this->_1_Insercion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FormValue == $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue)))
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue = ew_StrToFloat($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Novedad_apoyo_zonal->FormValue == $this->_1_Novedad_apoyo_zonal->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Novedad_apoyo_zonal->CurrentValue)))
			$this->_1_Novedad_apoyo_zonal->CurrentValue = ew_StrToFloat($this->_1_Novedad_apoyo_zonal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Novedad_enfermero->FormValue == $this->_1_Novedad_enfermero->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Novedad_enfermero->CurrentValue)))
			$this->_1_Novedad_enfermero->CurrentValue = ew_StrToFloat($this->_1_Novedad_enfermero->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Punto_fuera_del_area_de_erradicacion->FormValue == $this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue)))
			$this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue = ew_StrToFloat($this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Transporte_bus->FormValue == $this->_1_Transporte_bus->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Transporte_bus->CurrentValue)))
			$this->_1_Transporte_bus->CurrentValue = ew_StrToFloat($this->_1_Transporte_bus->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Traslado_apoyo_zonal->FormValue == $this->_1_Traslado_apoyo_zonal->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Traslado_apoyo_zonal->CurrentValue)))
			$this->_1_Traslado_apoyo_zonal->CurrentValue = ew_StrToFloat($this->_1_Traslado_apoyo_zonal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Traslado_area_vivac->FormValue == $this->_1_Traslado_area_vivac->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Traslado_area_vivac->CurrentValue)))
			$this->_1_Traslado_area_vivac->CurrentValue = ew_StrToFloat($this->_1_Traslado_area_vivac->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Adm_Fuerza->FormValue == $this->Adm_Fuerza->CurrentValue && is_numeric(ew_StrToFloat($this->Adm_Fuerza->CurrentValue)))
			$this->Adm_Fuerza->CurrentValue = ew_StrToFloat($this->Adm_Fuerza->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->FormValue == $this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue && is_numeric(ew_StrToFloat($this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue)))
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue = ew_StrToFloat($this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Espera_helicoptero_FP_de_seguridad->FormValue == $this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue)))
			$this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue = ew_StrToFloat($this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Espera_helicoptero_FP_que_abastece->FormValue == $this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue)))
			$this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue = ew_StrToFloat($this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Induccion_FP->FormValue == $this->_2_Induccion_FP->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Induccion_FP->CurrentValue)))
			$this->_2_Induccion_FP->CurrentValue = ew_StrToFloat($this->_2_Induccion_FP->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->FormValue == $this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue)))
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue = ew_StrToFloat($this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Problemas_fuerza_publica->FormValue == $this->_2_Problemas_fuerza_publica->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Problemas_fuerza_publica->CurrentValue)))
			$this->_2_Problemas_fuerza_publica->CurrentValue = ew_StrToFloat($this->_2_Problemas_fuerza_publica->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Sin_seguridad->FormValue == $this->_2_Sin_seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Sin_seguridad->CurrentValue)))
			$this->_2_Sin_seguridad->CurrentValue = ew_StrToFloat($this->_2_Sin_seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Sit_Seguridad->FormValue == $this->Sit_Seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->Sit_Seguridad->CurrentValue)))
			$this->Sit_Seguridad->CurrentValue = ew_StrToFloat($this->Sit_Seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_AEI_controlado->FormValue == $this->_3_AEI_controlado->CurrentValue && is_numeric(ew_StrToFloat($this->_3_AEI_controlado->CurrentValue)))
			$this->_3_AEI_controlado->CurrentValue = ew_StrToFloat($this->_3_AEI_controlado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_AEI_no_controlado->FormValue == $this->_3_AEI_no_controlado->CurrentValue && is_numeric(ew_StrToFloat($this->_3_AEI_no_controlado->CurrentValue)))
			$this->_3_AEI_no_controlado->CurrentValue = ew_StrToFloat($this->_3_AEI_no_controlado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Bloqueo_parcial_de_la_comunidad->FormValue == $this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue)))
			$this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue = ew_StrToFloat($this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Bloqueo_total_de_la_comunidad->FormValue == $this->_3_Bloqueo_total_de_la_comunidad->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Bloqueo_total_de_la_comunidad->CurrentValue)))
			$this->_3_Bloqueo_total_de_la_comunidad->CurrentValue = ew_StrToFloat($this->_3_Bloqueo_total_de_la_comunidad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Combate->FormValue == $this->_3_Combate->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Combate->CurrentValue)))
			$this->_3_Combate->CurrentValue = ew_StrToFloat($this->_3_Combate->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Hostigamiento->FormValue == $this->_3_Hostigamiento->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Hostigamiento->CurrentValue)))
			$this->_3_Hostigamiento->CurrentValue = ew_StrToFloat($this->_3_Hostigamiento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_MAP_Controlada->FormValue == $this->_3_MAP_Controlada->CurrentValue && is_numeric(ew_StrToFloat($this->_3_MAP_Controlada->CurrentValue)))
			$this->_3_MAP_Controlada->CurrentValue = ew_StrToFloat($this->_3_MAP_Controlada->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_MAP_No_controlada->FormValue == $this->_3_MAP_No_controlada->CurrentValue && is_numeric(ew_StrToFloat($this->_3_MAP_No_controlada->CurrentValue)))
			$this->_3_MAP_No_controlada->CurrentValue = ew_StrToFloat($this->_3_MAP_No_controlada->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_MUSE->FormValue == $this->_3_MUSE->CurrentValue && is_numeric(ew_StrToFloat($this->_3_MUSE->CurrentValue)))
			$this->_3_MUSE->CurrentValue = ew_StrToFloat($this->_3_MUSE->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Operaciones_de_seguridad->FormValue == $this->_3_Operaciones_de_seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Operaciones_de_seguridad->CurrentValue)))
			$this->_3_Operaciones_de_seguridad->CurrentValue = ew_StrToFloat($this->_3_Operaciones_de_seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LAT_segurid->FormValue == $this->SEG_LAT_segurid->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LAT_segurid->CurrentValue)))
			$this->SEG_LAT_segurid->CurrentValue = ew_StrToFloat($this->SEG_LAT_segurid->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LONG_seguri->FormValue == $this->SEG_LONG_seguri->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LONG_seguri->CurrentValue)))
			$this->SEG_LONG_seguri->CurrentValue = ew_StrToFloat($this->SEG_LONG_seguri->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Novedad->FormValue == $this->Novedad->CurrentValue && is_numeric(ew_StrToFloat($this->Novedad->CurrentValue)))
			$this->Novedad->CurrentValue = ew_StrToFloat($this->Novedad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Epidemia->FormValue == $this->_4_Epidemia->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Epidemia->CurrentValue)))
			$this->_4_Epidemia->CurrentValue = ew_StrToFloat($this->_4_Epidemia->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Novedad_climatologica->FormValue == $this->_4_Novedad_climatologica->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Novedad_climatologica->CurrentValue)))
			$this->_4_Novedad_climatologica->CurrentValue = ew_StrToFloat($this->_4_Novedad_climatologica->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Registro_de_cultivos->FormValue == $this->_4_Registro_de_cultivos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Registro_de_cultivos->CurrentValue)))
			$this->_4_Registro_de_cultivos->CurrentValue = ew_StrToFloat($this->_4_Registro_de_cultivos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Zona_con_cultivos_muy_dispersos->FormValue == $this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue)))
			$this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue = ew_StrToFloat($this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Zona_de_cruce_de_rios_caudalosos->FormValue == $this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue)))
			$this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue = ew_StrToFloat($this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Zona_sin_cultivos->FormValue == $this->_4_Zona_sin_cultivos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Zona_sin_cultivos->CurrentValue)))
			$this->_4_Zona_sin_cultivos->CurrentValue = ew_StrToFloat($this->_4_Zona_sin_cultivos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// llave
		// F_Sincron
		// USUARIO
		// Cargo_gme
		// NOM_PE
		// Otro_PE
		// NOM_PGE
		// Otro_NOM_PGE
		// Otro_CC_PGE
		// TIPO_INFORME
		// FECHA_REPORT
		// DIA
		// MES
		// Departamento
		// Muncipio
		// TEMA
		// Otro_Tema
		// OBSERVACION
		// FUERZA
		// NOM_VDA
		// Ha_Coca
		// Ha_Amapola
		// Ha_Marihuana
		// T_erradi
		// LATITUD_sector
		// GRA_LAT_Sector
		// MIN_LAT_Sector
		// SEG_LAT_Sector
		// GRA_LONG_Sector
		// MIN_LONG_Sector
		// SEG_LONG_Sector
		// Ini_Jorna
		// Fin_Jorna
		// Situ_Especial
		// Adm_GME
		// 1_Abastecimiento
		// 1_Acompanamiento_firma_GME
		// 1_Apoyo_zonal_sin_punto_asignado
		// 1_Descanso_en_dia_habil
		// 1_Descanso_festivo_dominical
		// 1_Dia_compensatorio
		// 1_Erradicacion_en_dia_festivo
		// 1_Espera_helicoptero_Helistar
		// 1_Extraccion
		// 1_Firma_contrato_GME
		// 1_Induccion_Apoyo_Zonal
		// 1_Insercion
		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		// 1_Novedad_apoyo_zonal
		// 1_Novedad_enfermero
		// 1_Punto_fuera_del_area_de_erradicacion
		// 1_Transporte_bus
		// 1_Traslado_apoyo_zonal
		// 1_Traslado_area_vivac
		// Adm_Fuerza
		// 2_A_la_espera_definicion_nuevo_punto_FP
		// 2_Espera_helicoptero_FP_de_seguridad
		// 2_Espera_helicoptero_FP_que_abastece
		// 2_Induccion_FP
		// 2_Novedad_canino_o_del_grupo_de_deteccion
		// 2_Problemas_fuerza_publica
		// 2_Sin_seguridad
		// Sit_Seguridad
		// 3_AEI_controlado
		// 3_AEI_no_controlado
		// 3_Bloqueo_parcial_de_la_comunidad
		// 3_Bloqueo_total_de_la_comunidad
		// 3_Combate
		// 3_Hostigamiento
		// 3_MAP_Controlada
		// 3_MAP_No_controlada
		// 3_MUSE
		// 3_Operaciones_de_seguridad
		// LATITUD_segurid
		// GRA_LAT_segurid
		// MIN_LAT_segurid
		// SEG_LAT_segurid
		// GRA_LONG_seguri
		// MIN_LONG_seguri
		// SEG_LONG_seguri
		// Novedad
		// 4_Epidemia
		// 4_Novedad_climatologica
		// 4_Registro_de_cultivos
		// 4_Zona_con_cultivos_muy_dispersos
		// 4_Zona_de_cruce_de_rios_caudalosos
		// 4_Zona_sin_cultivos
		// Num_Erra_Salen
		// Num_Erra_Quedan
		// No_ENFERMERO
		// NUM_FP
		// NUM_Perso_EVA
		// NUM_Poli
		// AÑO
		// FASE
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
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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

			// NOM_PGE
			if (strval($this->NOM_PGE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PGE`" . ew_SearchString("=", $this->NOM_PGE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_PGE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_PGE` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->NOM_PGE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->NOM_PGE->ViewValue = $this->NOM_PGE->CurrentValue;
				}
			} else {
				$this->NOM_PGE->ViewValue = NULL;
			}
			$this->NOM_PGE->ViewCustomAttributes = "";

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->ViewValue = $this->Otro_NOM_PGE->CurrentValue;
			$this->Otro_NOM_PGE->ViewCustomAttributes = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
			$this->Otro_CC_PGE->ViewCustomAttributes = "";

			// TIPO_INFORME
			if (strval($this->TIPO_INFORME->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->TIPO_INFORME->CurrentValue, EW_DATATYPE_STRING);
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

			// FECHA_REPORT
			$this->FECHA_REPORT->ViewValue = $this->FECHA_REPORT->CurrentValue;
			$this->FECHA_REPORT->ViewValue = ew_FormatDateTime($this->FECHA_REPORT->ViewValue, 5);
			$this->FECHA_REPORT->ViewCustomAttributes = "";

			// DIA
			$this->DIA->ViewValue = $this->DIA->CurrentValue;
			$this->DIA->ViewCustomAttributes = "";

			// MES
			$this->MES->ViewValue = $this->MES->CurrentValue;
			$this->MES->ViewCustomAttributes = "";

			// Departamento
			$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
			$this->Departamento->ViewCustomAttributes = "";

			// Muncipio
			$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
			$this->Muncipio->ViewCustomAttributes = "";

			// TEMA
			if (strval($this->TEMA->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->TEMA->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='tema'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TEMA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->TEMA->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->TEMA->ViewValue = $this->TEMA->CurrentValue;
				}
			} else {
				$this->TEMA->ViewValue = NULL;
			}
			$this->TEMA->ViewCustomAttributes = "";

			// Otro_Tema
			$this->Otro_Tema->ViewValue = $this->Otro_Tema->CurrentValue;
			$this->Otro_Tema->ViewCustomAttributes = "";

			// OBSERVACION
			$this->OBSERVACION->ViewValue = $this->OBSERVACION->CurrentValue;
			$this->OBSERVACION->ViewCustomAttributes = "";

			// FUERZA
			if (strval($this->FUERZA->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->FUERZA->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='fp'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FUERZA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->FUERZA->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->FUERZA->ViewValue = $this->FUERZA->CurrentValue;
				}
			} else {
				$this->FUERZA->ViewValue = NULL;
			}
			$this->FUERZA->ViewCustomAttributes = "";

			// NOM_VDA
			$this->NOM_VDA->ViewValue = $this->NOM_VDA->CurrentValue;
			$this->NOM_VDA->ViewCustomAttributes = "";

			// Ha_Coca
			$this->Ha_Coca->ViewValue = $this->Ha_Coca->CurrentValue;
			$this->Ha_Coca->ViewCustomAttributes = "";

			// Ha_Amapola
			$this->Ha_Amapola->ViewValue = $this->Ha_Amapola->CurrentValue;
			$this->Ha_Amapola->ViewCustomAttributes = "";

			// Ha_Marihuana
			$this->Ha_Marihuana->ViewValue = $this->Ha_Marihuana->CurrentValue;
			$this->Ha_Marihuana->ViewCustomAttributes = "";

			// T_erradi
			$this->T_erradi->ViewValue = $this->T_erradi->CurrentValue;
			$this->T_erradi->ViewCustomAttributes = "";

			// LATITUD_sector
			$this->LATITUD_sector->ViewValue = $this->LATITUD_sector->CurrentValue;
			$this->LATITUD_sector->ViewCustomAttributes = "";

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->ViewValue = $this->GRA_LAT_Sector->CurrentValue;
			$this->GRA_LAT_Sector->ViewCustomAttributes = "";

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->ViewValue = $this->MIN_LAT_Sector->CurrentValue;
			$this->MIN_LAT_Sector->ViewCustomAttributes = "";

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->ViewValue = $this->SEG_LAT_Sector->CurrentValue;
			$this->SEG_LAT_Sector->ViewCustomAttributes = "";

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->ViewValue = $this->GRA_LONG_Sector->CurrentValue;
			$this->GRA_LONG_Sector->ViewCustomAttributes = "";

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->ViewValue = $this->MIN_LONG_Sector->CurrentValue;
			$this->MIN_LONG_Sector->ViewCustomAttributes = "";

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->ViewValue = $this->SEG_LONG_Sector->CurrentValue;
			$this->SEG_LONG_Sector->ViewCustomAttributes = "";

			// Ini_Jorna
			$this->Ini_Jorna->ViewValue = $this->Ini_Jorna->CurrentValue;
			$this->Ini_Jorna->ViewCustomAttributes = "";

			// Fin_Jorna
			$this->Fin_Jorna->ViewValue = $this->Fin_Jorna->CurrentValue;
			$this->Fin_Jorna->ViewCustomAttributes = "";

			// Situ_Especial
			if (strval($this->Situ_Especial->CurrentValue) <> "") {
				switch ($this->Situ_Especial->CurrentValue) {
					case $this->Situ_Especial->FldTagValue(1):
						$this->Situ_Especial->ViewValue = $this->Situ_Especial->FldTagCaption(1) <> "" ? $this->Situ_Especial->FldTagCaption(1) : $this->Situ_Especial->CurrentValue;
						break;
					case $this->Situ_Especial->FldTagValue(2):
						$this->Situ_Especial->ViewValue = $this->Situ_Especial->FldTagCaption(2) <> "" ? $this->Situ_Especial->FldTagCaption(2) : $this->Situ_Especial->CurrentValue;
						break;
					default:
						$this->Situ_Especial->ViewValue = $this->Situ_Especial->CurrentValue;
				}
			} else {
				$this->Situ_Especial->ViewValue = NULL;
			}
			$this->Situ_Especial->ViewCustomAttributes = "";

			// Adm_GME
			$this->Adm_GME->ViewValue = $this->Adm_GME->CurrentValue;
			$this->Adm_GME->ViewCustomAttributes = "";

			// 1_Abastecimiento
			$this->_1_Abastecimiento->ViewValue = $this->_1_Abastecimiento->CurrentValue;
			$this->_1_Abastecimiento->ViewCustomAttributes = "";

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->ViewValue = $this->_1_Acompanamiento_firma_GME->CurrentValue;
			$this->_1_Acompanamiento_firma_GME->ViewCustomAttributes = "";

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->ViewValue = $this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue;
			$this->_1_Apoyo_zonal_sin_punto_asignado->ViewCustomAttributes = "";

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->ViewValue = $this->_1_Descanso_en_dia_habil->CurrentValue;
			$this->_1_Descanso_en_dia_habil->ViewCustomAttributes = "";

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->ViewValue = $this->_1_Descanso_festivo_dominical->CurrentValue;
			$this->_1_Descanso_festivo_dominical->ViewCustomAttributes = "";

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->ViewValue = $this->_1_Dia_compensatorio->CurrentValue;
			$this->_1_Dia_compensatorio->ViewCustomAttributes = "";

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->ViewValue = $this->_1_Erradicacion_en_dia_festivo->CurrentValue;
			$this->_1_Erradicacion_en_dia_festivo->ViewCustomAttributes = "";

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->ViewValue = $this->_1_Espera_helicoptero_Helistar->CurrentValue;
			$this->_1_Espera_helicoptero_Helistar->ViewCustomAttributes = "";

			// 1_Extraccion
			$this->_1_Extraccion->ViewValue = $this->_1_Extraccion->CurrentValue;
			$this->_1_Extraccion->ViewCustomAttributes = "";

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->ViewValue = $this->_1_Firma_contrato_GME->CurrentValue;
			$this->_1_Firma_contrato_GME->ViewCustomAttributes = "";

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->ViewValue = $this->_1_Induccion_Apoyo_Zonal->CurrentValue;
			$this->_1_Induccion_Apoyo_Zonal->ViewCustomAttributes = "";

			// 1_Insercion
			$this->_1_Insercion->ViewValue = $this->_1_Insercion->CurrentValue;
			$this->_1_Insercion->ViewCustomAttributes = "";

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewValue = $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue;
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewCustomAttributes = "";

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->ViewValue = $this->_1_Novedad_apoyo_zonal->CurrentValue;
			$this->_1_Novedad_apoyo_zonal->ViewCustomAttributes = "";

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->ViewValue = $this->_1_Novedad_enfermero->CurrentValue;
			$this->_1_Novedad_enfermero->ViewCustomAttributes = "";

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->ViewValue = $this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue;
			$this->_1_Punto_fuera_del_area_de_erradicacion->ViewCustomAttributes = "";

			// 1_Transporte_bus
			$this->_1_Transporte_bus->ViewValue = $this->_1_Transporte_bus->CurrentValue;
			$this->_1_Transporte_bus->ViewCustomAttributes = "";

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->ViewValue = $this->_1_Traslado_apoyo_zonal->CurrentValue;
			$this->_1_Traslado_apoyo_zonal->ViewCustomAttributes = "";

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->ViewValue = $this->_1_Traslado_area_vivac->CurrentValue;
			$this->_1_Traslado_area_vivac->ViewCustomAttributes = "";

			// Adm_Fuerza
			$this->Adm_Fuerza->ViewValue = $this->Adm_Fuerza->CurrentValue;
			$this->Adm_Fuerza->ViewCustomAttributes = "";

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->ViewValue = $this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue;
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->ViewCustomAttributes = "";

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->ViewValue = $this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue;
			$this->_2_Espera_helicoptero_FP_de_seguridad->ViewCustomAttributes = "";

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->ViewValue = $this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue;
			$this->_2_Espera_helicoptero_FP_que_abastece->ViewCustomAttributes = "";

			// 2_Induccion_FP
			$this->_2_Induccion_FP->ViewValue = $this->_2_Induccion_FP->CurrentValue;
			$this->_2_Induccion_FP->ViewCustomAttributes = "";

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewValue = $this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue;
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewCustomAttributes = "";

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->ViewValue = $this->_2_Problemas_fuerza_publica->CurrentValue;
			$this->_2_Problemas_fuerza_publica->ViewCustomAttributes = "";

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->ViewValue = $this->_2_Sin_seguridad->CurrentValue;
			$this->_2_Sin_seguridad->ViewCustomAttributes = "";

			// Sit_Seguridad
			$this->Sit_Seguridad->ViewValue = $this->Sit_Seguridad->CurrentValue;
			$this->Sit_Seguridad->ViewCustomAttributes = "";

			// 3_AEI_controlado
			$this->_3_AEI_controlado->ViewValue = $this->_3_AEI_controlado->CurrentValue;
			$this->_3_AEI_controlado->ViewCustomAttributes = "";

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->ViewValue = $this->_3_AEI_no_controlado->CurrentValue;
			$this->_3_AEI_no_controlado->ViewCustomAttributes = "";

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->ViewValue = $this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue;
			$this->_3_Bloqueo_parcial_de_la_comunidad->ViewCustomAttributes = "";

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->ViewValue = $this->_3_Bloqueo_total_de_la_comunidad->CurrentValue;
			$this->_3_Bloqueo_total_de_la_comunidad->ViewCustomAttributes = "";

			// 3_Combate
			$this->_3_Combate->ViewValue = $this->_3_Combate->CurrentValue;
			$this->_3_Combate->ViewCustomAttributes = "";

			// 3_Hostigamiento
			$this->_3_Hostigamiento->ViewValue = $this->_3_Hostigamiento->CurrentValue;
			$this->_3_Hostigamiento->ViewCustomAttributes = "";

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->ViewValue = $this->_3_MAP_Controlada->CurrentValue;
			$this->_3_MAP_Controlada->ViewCustomAttributes = "";

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->ViewValue = $this->_3_MAP_No_controlada->CurrentValue;
			$this->_3_MAP_No_controlada->ViewCustomAttributes = "";

			// 3_MUSE
			$this->_3_MUSE->ViewValue = $this->_3_MUSE->CurrentValue;
			$this->_3_MUSE->ViewCustomAttributes = "";

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->ViewValue = $this->_3_Operaciones_de_seguridad->CurrentValue;
			$this->_3_Operaciones_de_seguridad->ViewCustomAttributes = "";

			// LATITUD_segurid
			$this->LATITUD_segurid->ViewValue = $this->LATITUD_segurid->CurrentValue;
			$this->LATITUD_segurid->ViewCustomAttributes = "";

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->ViewValue = $this->GRA_LAT_segurid->CurrentValue;
			$this->GRA_LAT_segurid->ViewCustomAttributes = "";

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->ViewValue = $this->MIN_LAT_segurid->CurrentValue;
			$this->MIN_LAT_segurid->ViewCustomAttributes = "";

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->ViewValue = $this->SEG_LAT_segurid->CurrentValue;
			$this->SEG_LAT_segurid->ViewCustomAttributes = "";

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->ViewValue = $this->GRA_LONG_seguri->CurrentValue;
			$this->GRA_LONG_seguri->ViewCustomAttributes = "";

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->ViewValue = $this->MIN_LONG_seguri->CurrentValue;
			$this->MIN_LONG_seguri->ViewCustomAttributes = "";

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->ViewValue = $this->SEG_LONG_seguri->CurrentValue;
			$this->SEG_LONG_seguri->ViewCustomAttributes = "";

			// Novedad
			$this->Novedad->ViewValue = $this->Novedad->CurrentValue;
			$this->Novedad->ViewCustomAttributes = "";

			// 4_Epidemia
			$this->_4_Epidemia->ViewValue = $this->_4_Epidemia->CurrentValue;
			$this->_4_Epidemia->ViewCustomAttributes = "";

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->ViewValue = $this->_4_Novedad_climatologica->CurrentValue;
			$this->_4_Novedad_climatologica->ViewCustomAttributes = "";

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->ViewValue = $this->_4_Registro_de_cultivos->CurrentValue;
			$this->_4_Registro_de_cultivos->ViewCustomAttributes = "";

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->ViewValue = $this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue;
			$this->_4_Zona_con_cultivos_muy_dispersos->ViewCustomAttributes = "";

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->ViewValue = $this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue;
			$this->_4_Zona_de_cruce_de_rios_caudalosos->ViewCustomAttributes = "";

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->ViewValue = $this->_4_Zona_sin_cultivos->CurrentValue;
			$this->_4_Zona_sin_cultivos->ViewCustomAttributes = "";

			// Num_Erra_Salen
			$this->Num_Erra_Salen->ViewValue = $this->Num_Erra_Salen->CurrentValue;
			$this->Num_Erra_Salen->ViewCustomAttributes = "";

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->ViewValue = $this->Num_Erra_Quedan->CurrentValue;
			$this->Num_Erra_Quedan->ViewCustomAttributes = "";

			// No_ENFERMERO
			$this->No_ENFERMERO->ViewValue = $this->No_ENFERMERO->CurrentValue;
			$this->No_ENFERMERO->ViewCustomAttributes = "";

			// NUM_FP
			$this->NUM_FP->ViewValue = $this->NUM_FP->CurrentValue;
			$this->NUM_FP->ViewCustomAttributes = "";

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->ViewValue = $this->NUM_Perso_EVA->CurrentValue;
			$this->NUM_Perso_EVA->ViewCustomAttributes = "";

			// NUM_Poli
			$this->NUM_Poli->ViewValue = $this->NUM_Poli->CurrentValue;
			$this->NUM_Poli->ViewCustomAttributes = "";

			// AÑO
			if (strval($this->AD1O->CurrentValue) <> "") {
				$sFilterWrk = "`AÑO`" . ew_SearchString("=", $this->AD1O->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FASE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `FASE` ASC";
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

			// NOM_PGE
			$this->NOM_PGE->LinkCustomAttributes = "";
			$this->NOM_PGE->HrefValue = "";
			$this->NOM_PGE->TooltipValue = "";

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->LinkCustomAttributes = "";
			$this->Otro_NOM_PGE->HrefValue = "";
			$this->Otro_NOM_PGE->TooltipValue = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->LinkCustomAttributes = "";
			$this->Otro_CC_PGE->HrefValue = "";
			$this->Otro_CC_PGE->TooltipValue = "";

			// TIPO_INFORME
			$this->TIPO_INFORME->LinkCustomAttributes = "";
			$this->TIPO_INFORME->HrefValue = "";
			$this->TIPO_INFORME->TooltipValue = "";

			// FECHA_REPORT
			$this->FECHA_REPORT->LinkCustomAttributes = "";
			$this->FECHA_REPORT->HrefValue = "";
			$this->FECHA_REPORT->TooltipValue = "";

			// DIA
			$this->DIA->LinkCustomAttributes = "";
			$this->DIA->HrefValue = "";
			$this->DIA->TooltipValue = "";

			// MES
			$this->MES->LinkCustomAttributes = "";
			$this->MES->HrefValue = "";
			$this->MES->TooltipValue = "";

			// Departamento
			$this->Departamento->LinkCustomAttributes = "";
			$this->Departamento->HrefValue = "";
			$this->Departamento->TooltipValue = "";

			// Muncipio
			$this->Muncipio->LinkCustomAttributes = "";
			$this->Muncipio->HrefValue = "";
			$this->Muncipio->TooltipValue = "";

			// TEMA
			$this->TEMA->LinkCustomAttributes = "";
			$this->TEMA->HrefValue = "";
			$this->TEMA->TooltipValue = "";

			// Otro_Tema
			$this->Otro_Tema->LinkCustomAttributes = "";
			$this->Otro_Tema->HrefValue = "";
			$this->Otro_Tema->TooltipValue = "";

			// OBSERVACION
			$this->OBSERVACION->LinkCustomAttributes = "";
			$this->OBSERVACION->HrefValue = "";
			$this->OBSERVACION->TooltipValue = "";

			// FUERZA
			$this->FUERZA->LinkCustomAttributes = "";
			$this->FUERZA->HrefValue = "";
			$this->FUERZA->TooltipValue = "";

			// NOM_VDA
			$this->NOM_VDA->LinkCustomAttributes = "";
			$this->NOM_VDA->HrefValue = "";
			$this->NOM_VDA->TooltipValue = "";

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

			// T_erradi
			$this->T_erradi->LinkCustomAttributes = "";
			$this->T_erradi->HrefValue = "";
			$this->T_erradi->TooltipValue = "";

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->LinkCustomAttributes = "";
			$this->GRA_LAT_Sector->HrefValue = "";
			$this->GRA_LAT_Sector->TooltipValue = "";

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->LinkCustomAttributes = "";
			$this->MIN_LAT_Sector->HrefValue = "";
			$this->MIN_LAT_Sector->TooltipValue = "";

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->LinkCustomAttributes = "";
			$this->SEG_LAT_Sector->HrefValue = "";
			$this->SEG_LAT_Sector->TooltipValue = "";

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->LinkCustomAttributes = "";
			$this->GRA_LONG_Sector->HrefValue = "";
			$this->GRA_LONG_Sector->TooltipValue = "";

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->LinkCustomAttributes = "";
			$this->MIN_LONG_Sector->HrefValue = "";
			$this->MIN_LONG_Sector->TooltipValue = "";

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->LinkCustomAttributes = "";
			$this->SEG_LONG_Sector->HrefValue = "";
			$this->SEG_LONG_Sector->TooltipValue = "";

			// Ini_Jorna
			$this->Ini_Jorna->LinkCustomAttributes = "";
			$this->Ini_Jorna->HrefValue = "";
			$this->Ini_Jorna->TooltipValue = "";

			// Fin_Jorna
			$this->Fin_Jorna->LinkCustomAttributes = "";
			$this->Fin_Jorna->HrefValue = "";
			$this->Fin_Jorna->TooltipValue = "";

			// Situ_Especial
			$this->Situ_Especial->LinkCustomAttributes = "";
			$this->Situ_Especial->HrefValue = "";
			$this->Situ_Especial->TooltipValue = "";

			// Adm_GME
			$this->Adm_GME->LinkCustomAttributes = "";
			$this->Adm_GME->HrefValue = "";
			$this->Adm_GME->TooltipValue = "";

			// 1_Abastecimiento
			$this->_1_Abastecimiento->LinkCustomAttributes = "";
			$this->_1_Abastecimiento->HrefValue = "";
			$this->_1_Abastecimiento->TooltipValue = "";

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->LinkCustomAttributes = "";
			$this->_1_Acompanamiento_firma_GME->HrefValue = "";
			$this->_1_Acompanamiento_firma_GME->TooltipValue = "";

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->LinkCustomAttributes = "";
			$this->_1_Apoyo_zonal_sin_punto_asignado->HrefValue = "";
			$this->_1_Apoyo_zonal_sin_punto_asignado->TooltipValue = "";

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->LinkCustomAttributes = "";
			$this->_1_Descanso_en_dia_habil->HrefValue = "";
			$this->_1_Descanso_en_dia_habil->TooltipValue = "";

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->LinkCustomAttributes = "";
			$this->_1_Descanso_festivo_dominical->HrefValue = "";
			$this->_1_Descanso_festivo_dominical->TooltipValue = "";

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->LinkCustomAttributes = "";
			$this->_1_Dia_compensatorio->HrefValue = "";
			$this->_1_Dia_compensatorio->TooltipValue = "";

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->LinkCustomAttributes = "";
			$this->_1_Erradicacion_en_dia_festivo->HrefValue = "";
			$this->_1_Erradicacion_en_dia_festivo->TooltipValue = "";

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->LinkCustomAttributes = "";
			$this->_1_Espera_helicoptero_Helistar->HrefValue = "";
			$this->_1_Espera_helicoptero_Helistar->TooltipValue = "";

			// 1_Extraccion
			$this->_1_Extraccion->LinkCustomAttributes = "";
			$this->_1_Extraccion->HrefValue = "";
			$this->_1_Extraccion->TooltipValue = "";

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->LinkCustomAttributes = "";
			$this->_1_Firma_contrato_GME->HrefValue = "";
			$this->_1_Firma_contrato_GME->TooltipValue = "";

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->LinkCustomAttributes = "";
			$this->_1_Induccion_Apoyo_Zonal->HrefValue = "";
			$this->_1_Induccion_Apoyo_Zonal->TooltipValue = "";

			// 1_Insercion
			$this->_1_Insercion->LinkCustomAttributes = "";
			$this->_1_Insercion->HrefValue = "";
			$this->_1_Insercion->TooltipValue = "";

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->LinkCustomAttributes = "";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->HrefValue = "";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->TooltipValue = "";

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->LinkCustomAttributes = "";
			$this->_1_Novedad_apoyo_zonal->HrefValue = "";
			$this->_1_Novedad_apoyo_zonal->TooltipValue = "";

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->LinkCustomAttributes = "";
			$this->_1_Novedad_enfermero->HrefValue = "";
			$this->_1_Novedad_enfermero->TooltipValue = "";

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->LinkCustomAttributes = "";
			$this->_1_Punto_fuera_del_area_de_erradicacion->HrefValue = "";
			$this->_1_Punto_fuera_del_area_de_erradicacion->TooltipValue = "";

			// 1_Transporte_bus
			$this->_1_Transporte_bus->LinkCustomAttributes = "";
			$this->_1_Transporte_bus->HrefValue = "";
			$this->_1_Transporte_bus->TooltipValue = "";

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->LinkCustomAttributes = "";
			$this->_1_Traslado_apoyo_zonal->HrefValue = "";
			$this->_1_Traslado_apoyo_zonal->TooltipValue = "";

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->LinkCustomAttributes = "";
			$this->_1_Traslado_area_vivac->HrefValue = "";
			$this->_1_Traslado_area_vivac->TooltipValue = "";

			// Adm_Fuerza
			$this->Adm_Fuerza->LinkCustomAttributes = "";
			$this->Adm_Fuerza->HrefValue = "";
			$this->Adm_Fuerza->TooltipValue = "";

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->LinkCustomAttributes = "";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->HrefValue = "";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->TooltipValue = "";

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->LinkCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_de_seguridad->HrefValue = "";
			$this->_2_Espera_helicoptero_FP_de_seguridad->TooltipValue = "";

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->LinkCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_que_abastece->HrefValue = "";
			$this->_2_Espera_helicoptero_FP_que_abastece->TooltipValue = "";

			// 2_Induccion_FP
			$this->_2_Induccion_FP->LinkCustomAttributes = "";
			$this->_2_Induccion_FP->HrefValue = "";
			$this->_2_Induccion_FP->TooltipValue = "";

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->LinkCustomAttributes = "";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->HrefValue = "";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->TooltipValue = "";

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->LinkCustomAttributes = "";
			$this->_2_Problemas_fuerza_publica->HrefValue = "";
			$this->_2_Problemas_fuerza_publica->TooltipValue = "";

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->LinkCustomAttributes = "";
			$this->_2_Sin_seguridad->HrefValue = "";
			$this->_2_Sin_seguridad->TooltipValue = "";

			// Sit_Seguridad
			$this->Sit_Seguridad->LinkCustomAttributes = "";
			$this->Sit_Seguridad->HrefValue = "";
			$this->Sit_Seguridad->TooltipValue = "";

			// 3_AEI_controlado
			$this->_3_AEI_controlado->LinkCustomAttributes = "";
			$this->_3_AEI_controlado->HrefValue = "";
			$this->_3_AEI_controlado->TooltipValue = "";

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->LinkCustomAttributes = "";
			$this->_3_AEI_no_controlado->HrefValue = "";
			$this->_3_AEI_no_controlado->TooltipValue = "";

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->LinkCustomAttributes = "";
			$this->_3_Bloqueo_parcial_de_la_comunidad->HrefValue = "";
			$this->_3_Bloqueo_parcial_de_la_comunidad->TooltipValue = "";

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->LinkCustomAttributes = "";
			$this->_3_Bloqueo_total_de_la_comunidad->HrefValue = "";
			$this->_3_Bloqueo_total_de_la_comunidad->TooltipValue = "";

			// 3_Combate
			$this->_3_Combate->LinkCustomAttributes = "";
			$this->_3_Combate->HrefValue = "";
			$this->_3_Combate->TooltipValue = "";

			// 3_Hostigamiento
			$this->_3_Hostigamiento->LinkCustomAttributes = "";
			$this->_3_Hostigamiento->HrefValue = "";
			$this->_3_Hostigamiento->TooltipValue = "";

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->LinkCustomAttributes = "";
			$this->_3_MAP_Controlada->HrefValue = "";
			$this->_3_MAP_Controlada->TooltipValue = "";

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->LinkCustomAttributes = "";
			$this->_3_MAP_No_controlada->HrefValue = "";
			$this->_3_MAP_No_controlada->TooltipValue = "";

			// 3_MUSE
			$this->_3_MUSE->LinkCustomAttributes = "";
			$this->_3_MUSE->HrefValue = "";
			$this->_3_MUSE->TooltipValue = "";

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->LinkCustomAttributes = "";
			$this->_3_Operaciones_de_seguridad->HrefValue = "";
			$this->_3_Operaciones_de_seguridad->TooltipValue = "";

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->LinkCustomAttributes = "";
			$this->GRA_LAT_segurid->HrefValue = "";
			$this->GRA_LAT_segurid->TooltipValue = "";

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->LinkCustomAttributes = "";
			$this->MIN_LAT_segurid->HrefValue = "";
			$this->MIN_LAT_segurid->TooltipValue = "";

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->LinkCustomAttributes = "";
			$this->SEG_LAT_segurid->HrefValue = "";
			$this->SEG_LAT_segurid->TooltipValue = "";

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->LinkCustomAttributes = "";
			$this->GRA_LONG_seguri->HrefValue = "";
			$this->GRA_LONG_seguri->TooltipValue = "";

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->LinkCustomAttributes = "";
			$this->MIN_LONG_seguri->HrefValue = "";
			$this->MIN_LONG_seguri->TooltipValue = "";

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->LinkCustomAttributes = "";
			$this->SEG_LONG_seguri->HrefValue = "";
			$this->SEG_LONG_seguri->TooltipValue = "";

			// Novedad
			$this->Novedad->LinkCustomAttributes = "";
			$this->Novedad->HrefValue = "";
			$this->Novedad->TooltipValue = "";

			// 4_Epidemia
			$this->_4_Epidemia->LinkCustomAttributes = "";
			$this->_4_Epidemia->HrefValue = "";
			$this->_4_Epidemia->TooltipValue = "";

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->LinkCustomAttributes = "";
			$this->_4_Novedad_climatologica->HrefValue = "";
			$this->_4_Novedad_climatologica->TooltipValue = "";

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->LinkCustomAttributes = "";
			$this->_4_Registro_de_cultivos->HrefValue = "";
			$this->_4_Registro_de_cultivos->TooltipValue = "";

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->LinkCustomAttributes = "";
			$this->_4_Zona_con_cultivos_muy_dispersos->HrefValue = "";
			$this->_4_Zona_con_cultivos_muy_dispersos->TooltipValue = "";

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->LinkCustomAttributes = "";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->HrefValue = "";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->TooltipValue = "";

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->LinkCustomAttributes = "";
			$this->_4_Zona_sin_cultivos->HrefValue = "";
			$this->_4_Zona_sin_cultivos->TooltipValue = "";

			// Num_Erra_Salen
			$this->Num_Erra_Salen->LinkCustomAttributes = "";
			$this->Num_Erra_Salen->HrefValue = "";
			$this->Num_Erra_Salen->TooltipValue = "";

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->LinkCustomAttributes = "";
			$this->Num_Erra_Quedan->HrefValue = "";
			$this->Num_Erra_Quedan->TooltipValue = "";

			// No_ENFERMERO
			$this->No_ENFERMERO->LinkCustomAttributes = "";
			$this->No_ENFERMERO->HrefValue = "";
			$this->No_ENFERMERO->TooltipValue = "";

			// NUM_FP
			$this->NUM_FP->LinkCustomAttributes = "";
			$this->NUM_FP->HrefValue = "";
			$this->NUM_FP->TooltipValue = "";

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->LinkCustomAttributes = "";
			$this->NUM_Perso_EVA->HrefValue = "";
			$this->NUM_Perso_EVA->TooltipValue = "";

			// NUM_Poli
			$this->NUM_Poli->LinkCustomAttributes = "";
			$this->NUM_Poli->HrefValue = "";
			$this->NUM_Poli->TooltipValue = "";

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
			$this->F_Sincron->EditValue = ew_FormatDateTime($this->F_Sincron->EditValue, 5);
			$this->F_Sincron->ViewCustomAttributes = "";

			// USUARIO
			$this->USUARIO->EditAttrs["class"] = "form-control";
			$this->USUARIO->EditCustomAttributes = "";
			if (strval($this->USUARIO->CurrentValue) <> "") {
				$sFilterWrk = "`USUARIO`" . ew_SearchString("=", $this->USUARIO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
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

			// NOM_PGE
			$this->NOM_PGE->EditAttrs["class"] = "form-control";
			$this->NOM_PGE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_PGE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_PGE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->NOM_PGE->EditValue = $arwrk;

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_NOM_PGE->EditCustomAttributes = "";
			$this->Otro_NOM_PGE->EditValue = ew_HtmlEncode($this->Otro_NOM_PGE->CurrentValue);
			$this->Otro_NOM_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_NOM_PGE->FldCaption());

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
					$sSqlWrk = "SELECT DISTINCT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `label`, `label` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `dominio`";
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

			// FECHA_REPORT
			$this->FECHA_REPORT->EditAttrs["class"] = "form-control";
			$this->FECHA_REPORT->EditCustomAttributes = "";
			$this->FECHA_REPORT->EditValue = ew_HtmlEncode($this->FECHA_REPORT->CurrentValue);
			$this->FECHA_REPORT->PlaceHolder = ew_RemoveHtml($this->FECHA_REPORT->FldCaption());

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

			// TEMA
			$this->TEMA->EditAttrs["class"] = "form-control";
			$this->TEMA->EditCustomAttributes = "";
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
			$lookuptblfilter = "`list name`='tema'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TEMA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->TEMA->EditValue = $arwrk;

			// Otro_Tema
			$this->Otro_Tema->EditAttrs["class"] = "form-control";
			$this->Otro_Tema->EditCustomAttributes = "";
			$this->Otro_Tema->EditValue = ew_HtmlEncode($this->Otro_Tema->CurrentValue);
			$this->Otro_Tema->PlaceHolder = ew_RemoveHtml($this->Otro_Tema->FldCaption());

			// OBSERVACION
			$this->OBSERVACION->EditAttrs["class"] = "form-control";
			$this->OBSERVACION->EditCustomAttributes = "";
			$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->CurrentValue);
			$this->OBSERVACION->PlaceHolder = ew_RemoveHtml($this->OBSERVACION->FldCaption());

			// FUERZA
			$this->FUERZA->EditAttrs["class"] = "form-control";
			$this->FUERZA->EditCustomAttributes = "";
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
			$lookuptblfilter = "`list name`='fp'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FUERZA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->FUERZA->EditValue = $arwrk;

			// NOM_VDA
			$this->NOM_VDA->EditAttrs["class"] = "form-control";
			$this->NOM_VDA->EditCustomAttributes = "";
			$this->NOM_VDA->EditValue = ew_HtmlEncode($this->NOM_VDA->CurrentValue);
			$this->NOM_VDA->PlaceHolder = ew_RemoveHtml($this->NOM_VDA->FldCaption());

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

			// T_erradi
			$this->T_erradi->EditAttrs["class"] = "form-control";
			$this->T_erradi->EditCustomAttributes = "";
			$this->T_erradi->EditValue = ew_HtmlEncode($this->T_erradi->CurrentValue);
			$this->T_erradi->PlaceHolder = ew_RemoveHtml($this->T_erradi->FldCaption());
			if (strval($this->T_erradi->EditValue) <> "" && is_numeric($this->T_erradi->EditValue)) $this->T_erradi->EditValue = ew_FormatNumber($this->T_erradi->EditValue, -2, -1, -2, 0);

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->EditAttrs["class"] = "form-control";
			$this->GRA_LAT_Sector->EditCustomAttributes = "";
			$this->GRA_LAT_Sector->EditValue = ew_HtmlEncode($this->GRA_LAT_Sector->CurrentValue);
			$this->GRA_LAT_Sector->PlaceHolder = ew_RemoveHtml($this->GRA_LAT_Sector->FldCaption());

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->EditAttrs["class"] = "form-control";
			$this->MIN_LAT_Sector->EditCustomAttributes = "";
			$this->MIN_LAT_Sector->EditValue = ew_HtmlEncode($this->MIN_LAT_Sector->CurrentValue);
			$this->MIN_LAT_Sector->PlaceHolder = ew_RemoveHtml($this->MIN_LAT_Sector->FldCaption());

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->EditAttrs["class"] = "form-control";
			$this->SEG_LAT_Sector->EditCustomAttributes = "";
			$this->SEG_LAT_Sector->EditValue = ew_HtmlEncode($this->SEG_LAT_Sector->CurrentValue);
			$this->SEG_LAT_Sector->PlaceHolder = ew_RemoveHtml($this->SEG_LAT_Sector->FldCaption());
			if (strval($this->SEG_LAT_Sector->EditValue) <> "" && is_numeric($this->SEG_LAT_Sector->EditValue)) $this->SEG_LAT_Sector->EditValue = ew_FormatNumber($this->SEG_LAT_Sector->EditValue, -2, -1, -2, 0);

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->EditAttrs["class"] = "form-control";
			$this->GRA_LONG_Sector->EditCustomAttributes = "";
			$this->GRA_LONG_Sector->EditValue = ew_HtmlEncode($this->GRA_LONG_Sector->CurrentValue);
			$this->GRA_LONG_Sector->PlaceHolder = ew_RemoveHtml($this->GRA_LONG_Sector->FldCaption());

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->EditAttrs["class"] = "form-control";
			$this->MIN_LONG_Sector->EditCustomAttributes = "";
			$this->MIN_LONG_Sector->EditValue = ew_HtmlEncode($this->MIN_LONG_Sector->CurrentValue);
			$this->MIN_LONG_Sector->PlaceHolder = ew_RemoveHtml($this->MIN_LONG_Sector->FldCaption());

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->EditAttrs["class"] = "form-control";
			$this->SEG_LONG_Sector->EditCustomAttributes = "";
			$this->SEG_LONG_Sector->EditValue = ew_HtmlEncode($this->SEG_LONG_Sector->CurrentValue);
			$this->SEG_LONG_Sector->PlaceHolder = ew_RemoveHtml($this->SEG_LONG_Sector->FldCaption());
			if (strval($this->SEG_LONG_Sector->EditValue) <> "" && is_numeric($this->SEG_LONG_Sector->EditValue)) $this->SEG_LONG_Sector->EditValue = ew_FormatNumber($this->SEG_LONG_Sector->EditValue, -2, -1, -2, 0);

			// Ini_Jorna
			$this->Ini_Jorna->EditAttrs["class"] = "form-control";
			$this->Ini_Jorna->EditCustomAttributes = "";
			$this->Ini_Jorna->EditValue = ew_HtmlEncode($this->Ini_Jorna->CurrentValue);
			$this->Ini_Jorna->PlaceHolder = ew_RemoveHtml($this->Ini_Jorna->FldCaption());

			// Fin_Jorna
			$this->Fin_Jorna->EditAttrs["class"] = "form-control";
			$this->Fin_Jorna->EditCustomAttributes = "";
			$this->Fin_Jorna->EditValue = ew_HtmlEncode($this->Fin_Jorna->CurrentValue);
			$this->Fin_Jorna->PlaceHolder = ew_RemoveHtml($this->Fin_Jorna->FldCaption());

			// Situ_Especial
			$this->Situ_Especial->EditAttrs["class"] = "form-control";
			$this->Situ_Especial->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Situ_Especial->FldTagValue(1), $this->Situ_Especial->FldTagCaption(1) <> "" ? $this->Situ_Especial->FldTagCaption(1) : $this->Situ_Especial->FldTagValue(1));
			$arwrk[] = array($this->Situ_Especial->FldTagValue(2), $this->Situ_Especial->FldTagCaption(2) <> "" ? $this->Situ_Especial->FldTagCaption(2) : $this->Situ_Especial->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Situ_Especial->EditValue = $arwrk;

			// Adm_GME
			$this->Adm_GME->EditAttrs["class"] = "form-control";
			$this->Adm_GME->EditCustomAttributes = "";
			$this->Adm_GME->EditValue = ew_HtmlEncode($this->Adm_GME->CurrentValue);
			$this->Adm_GME->PlaceHolder = ew_RemoveHtml($this->Adm_GME->FldCaption());
			if (strval($this->Adm_GME->EditValue) <> "" && is_numeric($this->Adm_GME->EditValue)) $this->Adm_GME->EditValue = ew_FormatNumber($this->Adm_GME->EditValue, -2, -1, -2, 0);

			// 1_Abastecimiento
			$this->_1_Abastecimiento->EditAttrs["class"] = "form-control";
			$this->_1_Abastecimiento->EditCustomAttributes = "";
			$this->_1_Abastecimiento->EditValue = ew_HtmlEncode($this->_1_Abastecimiento->CurrentValue);
			$this->_1_Abastecimiento->PlaceHolder = ew_RemoveHtml($this->_1_Abastecimiento->FldCaption());
			if (strval($this->_1_Abastecimiento->EditValue) <> "" && is_numeric($this->_1_Abastecimiento->EditValue)) $this->_1_Abastecimiento->EditValue = ew_FormatNumber($this->_1_Abastecimiento->EditValue, -2, -1, -2, 0);

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->EditAttrs["class"] = "form-control";
			$this->_1_Acompanamiento_firma_GME->EditCustomAttributes = "";
			$this->_1_Acompanamiento_firma_GME->EditValue = ew_HtmlEncode($this->_1_Acompanamiento_firma_GME->CurrentValue);
			$this->_1_Acompanamiento_firma_GME->PlaceHolder = ew_RemoveHtml($this->_1_Acompanamiento_firma_GME->FldCaption());
			if (strval($this->_1_Acompanamiento_firma_GME->EditValue) <> "" && is_numeric($this->_1_Acompanamiento_firma_GME->EditValue)) $this->_1_Acompanamiento_firma_GME->EditValue = ew_FormatNumber($this->_1_Acompanamiento_firma_GME->EditValue, -2, -1, -2, 0);

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditAttrs["class"] = "form-control";
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditCustomAttributes = "";
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditValue = ew_HtmlEncode($this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue);
			$this->_1_Apoyo_zonal_sin_punto_asignado->PlaceHolder = ew_RemoveHtml($this->_1_Apoyo_zonal_sin_punto_asignado->FldCaption());
			if (strval($this->_1_Apoyo_zonal_sin_punto_asignado->EditValue) <> "" && is_numeric($this->_1_Apoyo_zonal_sin_punto_asignado->EditValue)) $this->_1_Apoyo_zonal_sin_punto_asignado->EditValue = ew_FormatNumber($this->_1_Apoyo_zonal_sin_punto_asignado->EditValue, -2, -1, -2, 0);

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->EditAttrs["class"] = "form-control";
			$this->_1_Descanso_en_dia_habil->EditCustomAttributes = "";
			$this->_1_Descanso_en_dia_habil->EditValue = ew_HtmlEncode($this->_1_Descanso_en_dia_habil->CurrentValue);
			$this->_1_Descanso_en_dia_habil->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_en_dia_habil->FldCaption());
			if (strval($this->_1_Descanso_en_dia_habil->EditValue) <> "" && is_numeric($this->_1_Descanso_en_dia_habil->EditValue)) $this->_1_Descanso_en_dia_habil->EditValue = ew_FormatNumber($this->_1_Descanso_en_dia_habil->EditValue, -2, -1, -2, 0);

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->EditAttrs["class"] = "form-control";
			$this->_1_Descanso_festivo_dominical->EditCustomAttributes = "";
			$this->_1_Descanso_festivo_dominical->EditValue = ew_HtmlEncode($this->_1_Descanso_festivo_dominical->CurrentValue);
			$this->_1_Descanso_festivo_dominical->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_festivo_dominical->FldCaption());
			if (strval($this->_1_Descanso_festivo_dominical->EditValue) <> "" && is_numeric($this->_1_Descanso_festivo_dominical->EditValue)) $this->_1_Descanso_festivo_dominical->EditValue = ew_FormatNumber($this->_1_Descanso_festivo_dominical->EditValue, -2, -1, -2, 0);

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->EditAttrs["class"] = "form-control";
			$this->_1_Dia_compensatorio->EditCustomAttributes = "";
			$this->_1_Dia_compensatorio->EditValue = ew_HtmlEncode($this->_1_Dia_compensatorio->CurrentValue);
			$this->_1_Dia_compensatorio->PlaceHolder = ew_RemoveHtml($this->_1_Dia_compensatorio->FldCaption());
			if (strval($this->_1_Dia_compensatorio->EditValue) <> "" && is_numeric($this->_1_Dia_compensatorio->EditValue)) $this->_1_Dia_compensatorio->EditValue = ew_FormatNumber($this->_1_Dia_compensatorio->EditValue, -2, -1, -2, 0);

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->EditAttrs["class"] = "form-control";
			$this->_1_Erradicacion_en_dia_festivo->EditCustomAttributes = "";
			$this->_1_Erradicacion_en_dia_festivo->EditValue = ew_HtmlEncode($this->_1_Erradicacion_en_dia_festivo->CurrentValue);
			$this->_1_Erradicacion_en_dia_festivo->PlaceHolder = ew_RemoveHtml($this->_1_Erradicacion_en_dia_festivo->FldCaption());
			if (strval($this->_1_Erradicacion_en_dia_festivo->EditValue) <> "" && is_numeric($this->_1_Erradicacion_en_dia_festivo->EditValue)) $this->_1_Erradicacion_en_dia_festivo->EditValue = ew_FormatNumber($this->_1_Erradicacion_en_dia_festivo->EditValue, -2, -1, -2, 0);

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->EditAttrs["class"] = "form-control";
			$this->_1_Espera_helicoptero_Helistar->EditCustomAttributes = "";
			$this->_1_Espera_helicoptero_Helistar->EditValue = ew_HtmlEncode($this->_1_Espera_helicoptero_Helistar->CurrentValue);
			$this->_1_Espera_helicoptero_Helistar->PlaceHolder = ew_RemoveHtml($this->_1_Espera_helicoptero_Helistar->FldCaption());
			if (strval($this->_1_Espera_helicoptero_Helistar->EditValue) <> "" && is_numeric($this->_1_Espera_helicoptero_Helistar->EditValue)) $this->_1_Espera_helicoptero_Helistar->EditValue = ew_FormatNumber($this->_1_Espera_helicoptero_Helistar->EditValue, -2, -1, -2, 0);

			// 1_Extraccion
			$this->_1_Extraccion->EditAttrs["class"] = "form-control";
			$this->_1_Extraccion->EditCustomAttributes = "";
			$this->_1_Extraccion->EditValue = ew_HtmlEncode($this->_1_Extraccion->CurrentValue);
			$this->_1_Extraccion->PlaceHolder = ew_RemoveHtml($this->_1_Extraccion->FldCaption());
			if (strval($this->_1_Extraccion->EditValue) <> "" && is_numeric($this->_1_Extraccion->EditValue)) $this->_1_Extraccion->EditValue = ew_FormatNumber($this->_1_Extraccion->EditValue, -2, -1, -2, 0);

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->EditAttrs["class"] = "form-control";
			$this->_1_Firma_contrato_GME->EditCustomAttributes = "";
			$this->_1_Firma_contrato_GME->EditValue = ew_HtmlEncode($this->_1_Firma_contrato_GME->CurrentValue);
			$this->_1_Firma_contrato_GME->PlaceHolder = ew_RemoveHtml($this->_1_Firma_contrato_GME->FldCaption());
			if (strval($this->_1_Firma_contrato_GME->EditValue) <> "" && is_numeric($this->_1_Firma_contrato_GME->EditValue)) $this->_1_Firma_contrato_GME->EditValue = ew_FormatNumber($this->_1_Firma_contrato_GME->EditValue, -2, -1, -2, 0);

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->EditAttrs["class"] = "form-control";
			$this->_1_Induccion_Apoyo_Zonal->EditCustomAttributes = "";
			$this->_1_Induccion_Apoyo_Zonal->EditValue = ew_HtmlEncode($this->_1_Induccion_Apoyo_Zonal->CurrentValue);
			$this->_1_Induccion_Apoyo_Zonal->PlaceHolder = ew_RemoveHtml($this->_1_Induccion_Apoyo_Zonal->FldCaption());
			if (strval($this->_1_Induccion_Apoyo_Zonal->EditValue) <> "" && is_numeric($this->_1_Induccion_Apoyo_Zonal->EditValue)) $this->_1_Induccion_Apoyo_Zonal->EditValue = ew_FormatNumber($this->_1_Induccion_Apoyo_Zonal->EditValue, -2, -1, -2, 0);

			// 1_Insercion
			$this->_1_Insercion->EditAttrs["class"] = "form-control";
			$this->_1_Insercion->EditCustomAttributes = "";
			$this->_1_Insercion->EditValue = ew_HtmlEncode($this->_1_Insercion->CurrentValue);
			$this->_1_Insercion->PlaceHolder = ew_RemoveHtml($this->_1_Insercion->FldCaption());
			if (strval($this->_1_Insercion->EditValue) <> "" && is_numeric($this->_1_Insercion->EditValue)) $this->_1_Insercion->EditValue = ew_FormatNumber($this->_1_Insercion->EditValue, -2, -1, -2, 0);

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditAttrs["class"] = "form-control";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditCustomAttributes = "";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue = ew_HtmlEncode($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue);
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->PlaceHolder = ew_RemoveHtml($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption());
			if (strval($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue) <> "" && is_numeric($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue)) $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue = ew_FormatNumber($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue, -2, -1, -2, 0);

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->EditAttrs["class"] = "form-control";
			$this->_1_Novedad_apoyo_zonal->EditCustomAttributes = "";
			$this->_1_Novedad_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Novedad_apoyo_zonal->CurrentValue);
			$this->_1_Novedad_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_apoyo_zonal->FldCaption());
			if (strval($this->_1_Novedad_apoyo_zonal->EditValue) <> "" && is_numeric($this->_1_Novedad_apoyo_zonal->EditValue)) $this->_1_Novedad_apoyo_zonal->EditValue = ew_FormatNumber($this->_1_Novedad_apoyo_zonal->EditValue, -2, -1, -2, 0);

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->EditAttrs["class"] = "form-control";
			$this->_1_Novedad_enfermero->EditCustomAttributes = "";
			$this->_1_Novedad_enfermero->EditValue = ew_HtmlEncode($this->_1_Novedad_enfermero->CurrentValue);
			$this->_1_Novedad_enfermero->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_enfermero->FldCaption());
			if (strval($this->_1_Novedad_enfermero->EditValue) <> "" && is_numeric($this->_1_Novedad_enfermero->EditValue)) $this->_1_Novedad_enfermero->EditValue = ew_FormatNumber($this->_1_Novedad_enfermero->EditValue, -2, -1, -2, 0);

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditAttrs["class"] = "form-control";
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditCustomAttributes = "";
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditValue = ew_HtmlEncode($this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue);
			$this->_1_Punto_fuera_del_area_de_erradicacion->PlaceHolder = ew_RemoveHtml($this->_1_Punto_fuera_del_area_de_erradicacion->FldCaption());
			if (strval($this->_1_Punto_fuera_del_area_de_erradicacion->EditValue) <> "" && is_numeric($this->_1_Punto_fuera_del_area_de_erradicacion->EditValue)) $this->_1_Punto_fuera_del_area_de_erradicacion->EditValue = ew_FormatNumber($this->_1_Punto_fuera_del_area_de_erradicacion->EditValue, -2, -1, -2, 0);

			// 1_Transporte_bus
			$this->_1_Transporte_bus->EditAttrs["class"] = "form-control";
			$this->_1_Transporte_bus->EditCustomAttributes = "";
			$this->_1_Transporte_bus->EditValue = ew_HtmlEncode($this->_1_Transporte_bus->CurrentValue);
			$this->_1_Transporte_bus->PlaceHolder = ew_RemoveHtml($this->_1_Transporte_bus->FldCaption());
			if (strval($this->_1_Transporte_bus->EditValue) <> "" && is_numeric($this->_1_Transporte_bus->EditValue)) $this->_1_Transporte_bus->EditValue = ew_FormatNumber($this->_1_Transporte_bus->EditValue, -2, -1, -2, 0);

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->EditAttrs["class"] = "form-control";
			$this->_1_Traslado_apoyo_zonal->EditCustomAttributes = "";
			$this->_1_Traslado_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Traslado_apoyo_zonal->CurrentValue);
			$this->_1_Traslado_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_apoyo_zonal->FldCaption());
			if (strval($this->_1_Traslado_apoyo_zonal->EditValue) <> "" && is_numeric($this->_1_Traslado_apoyo_zonal->EditValue)) $this->_1_Traslado_apoyo_zonal->EditValue = ew_FormatNumber($this->_1_Traslado_apoyo_zonal->EditValue, -2, -1, -2, 0);

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->EditAttrs["class"] = "form-control";
			$this->_1_Traslado_area_vivac->EditCustomAttributes = "";
			$this->_1_Traslado_area_vivac->EditValue = ew_HtmlEncode($this->_1_Traslado_area_vivac->CurrentValue);
			$this->_1_Traslado_area_vivac->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_area_vivac->FldCaption());
			if (strval($this->_1_Traslado_area_vivac->EditValue) <> "" && is_numeric($this->_1_Traslado_area_vivac->EditValue)) $this->_1_Traslado_area_vivac->EditValue = ew_FormatNumber($this->_1_Traslado_area_vivac->EditValue, -2, -1, -2, 0);

			// Adm_Fuerza
			$this->Adm_Fuerza->EditAttrs["class"] = "form-control";
			$this->Adm_Fuerza->EditCustomAttributes = "";
			$this->Adm_Fuerza->EditValue = ew_HtmlEncode($this->Adm_Fuerza->CurrentValue);
			$this->Adm_Fuerza->PlaceHolder = ew_RemoveHtml($this->Adm_Fuerza->FldCaption());
			if (strval($this->Adm_Fuerza->EditValue) <> "" && is_numeric($this->Adm_Fuerza->EditValue)) $this->Adm_Fuerza->EditValue = ew_FormatNumber($this->Adm_Fuerza->EditValue, -2, -1, -2, 0);

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditAttrs["class"] = "form-control";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditCustomAttributes = "";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue = ew_HtmlEncode($this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue);
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->PlaceHolder = ew_RemoveHtml($this->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption());
			if (strval($this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue) <> "" && is_numeric($this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue)) $this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue = ew_FormatNumber($this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue, -2, -1, -2, 0);

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditAttrs["class"] = "form-control";
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue);
			$this->_2_Espera_helicoptero_FP_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_de_seguridad->FldCaption());
			if (strval($this->_2_Espera_helicoptero_FP_de_seguridad->EditValue) <> "" && is_numeric($this->_2_Espera_helicoptero_FP_de_seguridad->EditValue)) $this->_2_Espera_helicoptero_FP_de_seguridad->EditValue = ew_FormatNumber($this->_2_Espera_helicoptero_FP_de_seguridad->EditValue, -2, -1, -2, 0);

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->EditAttrs["class"] = "form-control";
			$this->_2_Espera_helicoptero_FP_que_abastece->EditCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_que_abastece->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue);
			$this->_2_Espera_helicoptero_FP_que_abastece->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_que_abastece->FldCaption());
			if (strval($this->_2_Espera_helicoptero_FP_que_abastece->EditValue) <> "" && is_numeric($this->_2_Espera_helicoptero_FP_que_abastece->EditValue)) $this->_2_Espera_helicoptero_FP_que_abastece->EditValue = ew_FormatNumber($this->_2_Espera_helicoptero_FP_que_abastece->EditValue, -2, -1, -2, 0);

			// 2_Induccion_FP
			$this->_2_Induccion_FP->EditAttrs["class"] = "form-control";
			$this->_2_Induccion_FP->EditCustomAttributes = "";
			$this->_2_Induccion_FP->EditValue = ew_HtmlEncode($this->_2_Induccion_FP->CurrentValue);
			$this->_2_Induccion_FP->PlaceHolder = ew_RemoveHtml($this->_2_Induccion_FP->FldCaption());
			if (strval($this->_2_Induccion_FP->EditValue) <> "" && is_numeric($this->_2_Induccion_FP->EditValue)) $this->_2_Induccion_FP->EditValue = ew_FormatNumber($this->_2_Induccion_FP->EditValue, -2, -1, -2, 0);

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditAttrs["class"] = "form-control";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditCustomAttributes = "";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue = ew_HtmlEncode($this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue);
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->PlaceHolder = ew_RemoveHtml($this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption());
			if (strval($this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue) <> "" && is_numeric($this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue)) $this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue = ew_FormatNumber($this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue, -2, -1, -2, 0);

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->EditAttrs["class"] = "form-control";
			$this->_2_Problemas_fuerza_publica->EditCustomAttributes = "";
			$this->_2_Problemas_fuerza_publica->EditValue = ew_HtmlEncode($this->_2_Problemas_fuerza_publica->CurrentValue);
			$this->_2_Problemas_fuerza_publica->PlaceHolder = ew_RemoveHtml($this->_2_Problemas_fuerza_publica->FldCaption());
			if (strval($this->_2_Problemas_fuerza_publica->EditValue) <> "" && is_numeric($this->_2_Problemas_fuerza_publica->EditValue)) $this->_2_Problemas_fuerza_publica->EditValue = ew_FormatNumber($this->_2_Problemas_fuerza_publica->EditValue, -2, -1, -2, 0);

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->EditAttrs["class"] = "form-control";
			$this->_2_Sin_seguridad->EditCustomAttributes = "";
			$this->_2_Sin_seguridad->EditValue = ew_HtmlEncode($this->_2_Sin_seguridad->CurrentValue);
			$this->_2_Sin_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Sin_seguridad->FldCaption());
			if (strval($this->_2_Sin_seguridad->EditValue) <> "" && is_numeric($this->_2_Sin_seguridad->EditValue)) $this->_2_Sin_seguridad->EditValue = ew_FormatNumber($this->_2_Sin_seguridad->EditValue, -2, -1, -2, 0);

			// Sit_Seguridad
			$this->Sit_Seguridad->EditAttrs["class"] = "form-control";
			$this->Sit_Seguridad->EditCustomAttributes = "";
			$this->Sit_Seguridad->EditValue = ew_HtmlEncode($this->Sit_Seguridad->CurrentValue);
			$this->Sit_Seguridad->PlaceHolder = ew_RemoveHtml($this->Sit_Seguridad->FldCaption());
			if (strval($this->Sit_Seguridad->EditValue) <> "" && is_numeric($this->Sit_Seguridad->EditValue)) $this->Sit_Seguridad->EditValue = ew_FormatNumber($this->Sit_Seguridad->EditValue, -2, -1, -2, 0);

			// 3_AEI_controlado
			$this->_3_AEI_controlado->EditAttrs["class"] = "form-control";
			$this->_3_AEI_controlado->EditCustomAttributes = "";
			$this->_3_AEI_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_controlado->CurrentValue);
			$this->_3_AEI_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_controlado->FldCaption());
			if (strval($this->_3_AEI_controlado->EditValue) <> "" && is_numeric($this->_3_AEI_controlado->EditValue)) $this->_3_AEI_controlado->EditValue = ew_FormatNumber($this->_3_AEI_controlado->EditValue, -2, -1, -2, 0);

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->EditAttrs["class"] = "form-control";
			$this->_3_AEI_no_controlado->EditCustomAttributes = "";
			$this->_3_AEI_no_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_no_controlado->CurrentValue);
			$this->_3_AEI_no_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_no_controlado->FldCaption());
			if (strval($this->_3_AEI_no_controlado->EditValue) <> "" && is_numeric($this->_3_AEI_no_controlado->EditValue)) $this->_3_AEI_no_controlado->EditValue = ew_FormatNumber($this->_3_AEI_no_controlado->EditValue, -2, -1, -2, 0);

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditAttrs["class"] = "form-control";
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditCustomAttributes = "";
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue);
			$this->_3_Bloqueo_parcial_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_parcial_de_la_comunidad->FldCaption());
			if (strval($this->_3_Bloqueo_parcial_de_la_comunidad->EditValue) <> "" && is_numeric($this->_3_Bloqueo_parcial_de_la_comunidad->EditValue)) $this->_3_Bloqueo_parcial_de_la_comunidad->EditValue = ew_FormatNumber($this->_3_Bloqueo_parcial_de_la_comunidad->EditValue, -2, -1, -2, 0);

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->EditAttrs["class"] = "form-control";
			$this->_3_Bloqueo_total_de_la_comunidad->EditCustomAttributes = "";
			$this->_3_Bloqueo_total_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_total_de_la_comunidad->CurrentValue);
			$this->_3_Bloqueo_total_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_total_de_la_comunidad->FldCaption());
			if (strval($this->_3_Bloqueo_total_de_la_comunidad->EditValue) <> "" && is_numeric($this->_3_Bloqueo_total_de_la_comunidad->EditValue)) $this->_3_Bloqueo_total_de_la_comunidad->EditValue = ew_FormatNumber($this->_3_Bloqueo_total_de_la_comunidad->EditValue, -2, -1, -2, 0);

			// 3_Combate
			$this->_3_Combate->EditAttrs["class"] = "form-control";
			$this->_3_Combate->EditCustomAttributes = "";
			$this->_3_Combate->EditValue = ew_HtmlEncode($this->_3_Combate->CurrentValue);
			$this->_3_Combate->PlaceHolder = ew_RemoveHtml($this->_3_Combate->FldCaption());
			if (strval($this->_3_Combate->EditValue) <> "" && is_numeric($this->_3_Combate->EditValue)) $this->_3_Combate->EditValue = ew_FormatNumber($this->_3_Combate->EditValue, -2, -1, -2, 0);

			// 3_Hostigamiento
			$this->_3_Hostigamiento->EditAttrs["class"] = "form-control";
			$this->_3_Hostigamiento->EditCustomAttributes = "";
			$this->_3_Hostigamiento->EditValue = ew_HtmlEncode($this->_3_Hostigamiento->CurrentValue);
			$this->_3_Hostigamiento->PlaceHolder = ew_RemoveHtml($this->_3_Hostigamiento->FldCaption());
			if (strval($this->_3_Hostigamiento->EditValue) <> "" && is_numeric($this->_3_Hostigamiento->EditValue)) $this->_3_Hostigamiento->EditValue = ew_FormatNumber($this->_3_Hostigamiento->EditValue, -2, -1, -2, 0);

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->EditAttrs["class"] = "form-control";
			$this->_3_MAP_Controlada->EditCustomAttributes = "";
			$this->_3_MAP_Controlada->EditValue = ew_HtmlEncode($this->_3_MAP_Controlada->CurrentValue);
			$this->_3_MAP_Controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_Controlada->FldCaption());
			if (strval($this->_3_MAP_Controlada->EditValue) <> "" && is_numeric($this->_3_MAP_Controlada->EditValue)) $this->_3_MAP_Controlada->EditValue = ew_FormatNumber($this->_3_MAP_Controlada->EditValue, -2, -1, -2, 0);

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->EditAttrs["class"] = "form-control";
			$this->_3_MAP_No_controlada->EditCustomAttributes = "";
			$this->_3_MAP_No_controlada->EditValue = ew_HtmlEncode($this->_3_MAP_No_controlada->CurrentValue);
			$this->_3_MAP_No_controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_No_controlada->FldCaption());
			if (strval($this->_3_MAP_No_controlada->EditValue) <> "" && is_numeric($this->_3_MAP_No_controlada->EditValue)) $this->_3_MAP_No_controlada->EditValue = ew_FormatNumber($this->_3_MAP_No_controlada->EditValue, -2, -1, -2, 0);

			// 3_MUSE
			$this->_3_MUSE->EditAttrs["class"] = "form-control";
			$this->_3_MUSE->EditCustomAttributes = "";
			$this->_3_MUSE->EditValue = ew_HtmlEncode($this->_3_MUSE->CurrentValue);
			$this->_3_MUSE->PlaceHolder = ew_RemoveHtml($this->_3_MUSE->FldCaption());
			if (strval($this->_3_MUSE->EditValue) <> "" && is_numeric($this->_3_MUSE->EditValue)) $this->_3_MUSE->EditValue = ew_FormatNumber($this->_3_MUSE->EditValue, -2, -1, -2, 0);

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->EditAttrs["class"] = "form-control";
			$this->_3_Operaciones_de_seguridad->EditCustomAttributes = "";
			$this->_3_Operaciones_de_seguridad->EditValue = ew_HtmlEncode($this->_3_Operaciones_de_seguridad->CurrentValue);
			$this->_3_Operaciones_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_3_Operaciones_de_seguridad->FldCaption());
			if (strval($this->_3_Operaciones_de_seguridad->EditValue) <> "" && is_numeric($this->_3_Operaciones_de_seguridad->EditValue)) $this->_3_Operaciones_de_seguridad->EditValue = ew_FormatNumber($this->_3_Operaciones_de_seguridad->EditValue, -2, -1, -2, 0);

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->EditAttrs["class"] = "form-control";
			$this->GRA_LAT_segurid->EditCustomAttributes = "";
			$this->GRA_LAT_segurid->EditValue = ew_HtmlEncode($this->GRA_LAT_segurid->CurrentValue);
			$this->GRA_LAT_segurid->PlaceHolder = ew_RemoveHtml($this->GRA_LAT_segurid->FldCaption());

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->EditAttrs["class"] = "form-control";
			$this->MIN_LAT_segurid->EditCustomAttributes = "";
			$this->MIN_LAT_segurid->EditValue = ew_HtmlEncode($this->MIN_LAT_segurid->CurrentValue);
			$this->MIN_LAT_segurid->PlaceHolder = ew_RemoveHtml($this->MIN_LAT_segurid->FldCaption());

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->EditAttrs["class"] = "form-control";
			$this->SEG_LAT_segurid->EditCustomAttributes = "";
			$this->SEG_LAT_segurid->EditValue = ew_HtmlEncode($this->SEG_LAT_segurid->CurrentValue);
			$this->SEG_LAT_segurid->PlaceHolder = ew_RemoveHtml($this->SEG_LAT_segurid->FldCaption());
			if (strval($this->SEG_LAT_segurid->EditValue) <> "" && is_numeric($this->SEG_LAT_segurid->EditValue)) $this->SEG_LAT_segurid->EditValue = ew_FormatNumber($this->SEG_LAT_segurid->EditValue, -2, -1, -2, 0);

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->EditAttrs["class"] = "form-control";
			$this->GRA_LONG_seguri->EditCustomAttributes = "";
			$this->GRA_LONG_seguri->EditValue = ew_HtmlEncode($this->GRA_LONG_seguri->CurrentValue);
			$this->GRA_LONG_seguri->PlaceHolder = ew_RemoveHtml($this->GRA_LONG_seguri->FldCaption());

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->EditAttrs["class"] = "form-control";
			$this->MIN_LONG_seguri->EditCustomAttributes = "";
			$this->MIN_LONG_seguri->EditValue = ew_HtmlEncode($this->MIN_LONG_seguri->CurrentValue);
			$this->MIN_LONG_seguri->PlaceHolder = ew_RemoveHtml($this->MIN_LONG_seguri->FldCaption());

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->EditAttrs["class"] = "form-control";
			$this->SEG_LONG_seguri->EditCustomAttributes = "";
			$this->SEG_LONG_seguri->EditValue = ew_HtmlEncode($this->SEG_LONG_seguri->CurrentValue);
			$this->SEG_LONG_seguri->PlaceHolder = ew_RemoveHtml($this->SEG_LONG_seguri->FldCaption());
			if (strval($this->SEG_LONG_seguri->EditValue) <> "" && is_numeric($this->SEG_LONG_seguri->EditValue)) $this->SEG_LONG_seguri->EditValue = ew_FormatNumber($this->SEG_LONG_seguri->EditValue, -2, -1, -2, 0);

			// Novedad
			$this->Novedad->EditAttrs["class"] = "form-control";
			$this->Novedad->EditCustomAttributes = "";
			$this->Novedad->EditValue = ew_HtmlEncode($this->Novedad->CurrentValue);
			$this->Novedad->PlaceHolder = ew_RemoveHtml($this->Novedad->FldCaption());
			if (strval($this->Novedad->EditValue) <> "" && is_numeric($this->Novedad->EditValue)) $this->Novedad->EditValue = ew_FormatNumber($this->Novedad->EditValue, -2, -1, -2, 0);

			// 4_Epidemia
			$this->_4_Epidemia->EditAttrs["class"] = "form-control";
			$this->_4_Epidemia->EditCustomAttributes = "";
			$this->_4_Epidemia->EditValue = ew_HtmlEncode($this->_4_Epidemia->CurrentValue);
			$this->_4_Epidemia->PlaceHolder = ew_RemoveHtml($this->_4_Epidemia->FldCaption());
			if (strval($this->_4_Epidemia->EditValue) <> "" && is_numeric($this->_4_Epidemia->EditValue)) $this->_4_Epidemia->EditValue = ew_FormatNumber($this->_4_Epidemia->EditValue, -2, -1, -2, 0);

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->EditAttrs["class"] = "form-control";
			$this->_4_Novedad_climatologica->EditCustomAttributes = "";
			$this->_4_Novedad_climatologica->EditValue = ew_HtmlEncode($this->_4_Novedad_climatologica->CurrentValue);
			$this->_4_Novedad_climatologica->PlaceHolder = ew_RemoveHtml($this->_4_Novedad_climatologica->FldCaption());
			if (strval($this->_4_Novedad_climatologica->EditValue) <> "" && is_numeric($this->_4_Novedad_climatologica->EditValue)) $this->_4_Novedad_climatologica->EditValue = ew_FormatNumber($this->_4_Novedad_climatologica->EditValue, -2, -1, -2, 0);

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->EditAttrs["class"] = "form-control";
			$this->_4_Registro_de_cultivos->EditCustomAttributes = "";
			$this->_4_Registro_de_cultivos->EditValue = ew_HtmlEncode($this->_4_Registro_de_cultivos->CurrentValue);
			$this->_4_Registro_de_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Registro_de_cultivos->FldCaption());
			if (strval($this->_4_Registro_de_cultivos->EditValue) <> "" && is_numeric($this->_4_Registro_de_cultivos->EditValue)) $this->_4_Registro_de_cultivos->EditValue = ew_FormatNumber($this->_4_Registro_de_cultivos->EditValue, -2, -1, -2, 0);

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_con_cultivos_muy_dispersos->EditCustomAttributes = "";
			$this->_4_Zona_con_cultivos_muy_dispersos->EditValue = ew_HtmlEncode($this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue);
			$this->_4_Zona_con_cultivos_muy_dispersos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_con_cultivos_muy_dispersos->FldCaption());
			if (strval($this->_4_Zona_con_cultivos_muy_dispersos->EditValue) <> "" && is_numeric($this->_4_Zona_con_cultivos_muy_dispersos->EditValue)) $this->_4_Zona_con_cultivos_muy_dispersos->EditValue = ew_FormatNumber($this->_4_Zona_con_cultivos_muy_dispersos->EditValue, -2, -1, -2, 0);

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditCustomAttributes = "";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue = ew_HtmlEncode($this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue);
			$this->_4_Zona_de_cruce_de_rios_caudalosos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption());
			if (strval($this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue) <> "" && is_numeric($this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue)) $this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue = ew_FormatNumber($this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue, -2, -1, -2, 0);

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_sin_cultivos->EditCustomAttributes = "";
			$this->_4_Zona_sin_cultivos->EditValue = ew_HtmlEncode($this->_4_Zona_sin_cultivos->CurrentValue);
			$this->_4_Zona_sin_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_sin_cultivos->FldCaption());
			if (strval($this->_4_Zona_sin_cultivos->EditValue) <> "" && is_numeric($this->_4_Zona_sin_cultivos->EditValue)) $this->_4_Zona_sin_cultivos->EditValue = ew_FormatNumber($this->_4_Zona_sin_cultivos->EditValue, -2, -1, -2, 0);

			// Num_Erra_Salen
			$this->Num_Erra_Salen->EditAttrs["class"] = "form-control";
			$this->Num_Erra_Salen->EditCustomAttributes = "";
			$this->Num_Erra_Salen->EditValue = ew_HtmlEncode($this->Num_Erra_Salen->CurrentValue);
			$this->Num_Erra_Salen->PlaceHolder = ew_RemoveHtml($this->Num_Erra_Salen->FldCaption());

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->EditAttrs["class"] = "form-control";
			$this->Num_Erra_Quedan->EditCustomAttributes = "";
			$this->Num_Erra_Quedan->EditValue = ew_HtmlEncode($this->Num_Erra_Quedan->CurrentValue);
			$this->Num_Erra_Quedan->PlaceHolder = ew_RemoveHtml($this->Num_Erra_Quedan->FldCaption());

			// No_ENFERMERO
			$this->No_ENFERMERO->EditAttrs["class"] = "form-control";
			$this->No_ENFERMERO->EditCustomAttributes = "";
			$this->No_ENFERMERO->EditValue = ew_HtmlEncode($this->No_ENFERMERO->CurrentValue);
			$this->No_ENFERMERO->PlaceHolder = ew_RemoveHtml($this->No_ENFERMERO->FldCaption());

			// NUM_FP
			$this->NUM_FP->EditAttrs["class"] = "form-control";
			$this->NUM_FP->EditCustomAttributes = "";
			$this->NUM_FP->EditValue = ew_HtmlEncode($this->NUM_FP->CurrentValue);
			$this->NUM_FP->PlaceHolder = ew_RemoveHtml($this->NUM_FP->FldCaption());

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->EditAttrs["class"] = "form-control";
			$this->NUM_Perso_EVA->EditCustomAttributes = "";
			$this->NUM_Perso_EVA->EditValue = ew_HtmlEncode($this->NUM_Perso_EVA->CurrentValue);
			$this->NUM_Perso_EVA->PlaceHolder = ew_RemoveHtml($this->NUM_Perso_EVA->FldCaption());

			// NUM_Poli
			$this->NUM_Poli->EditAttrs["class"] = "form-control";
			$this->NUM_Poli->EditCustomAttributes = "";
			$this->NUM_Poli->EditValue = ew_HtmlEncode($this->NUM_Poli->CurrentValue);
			$this->NUM_Poli->PlaceHolder = ew_RemoveHtml($this->NUM_Poli->FldCaption());

			// AÑO
			$this->AD1O->EditAttrs["class"] = "form-control";
			$this->AD1O->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FASE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `FASE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->FASE->EditValue = $arwrk;

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

			// NOM_PGE
			$this->NOM_PGE->HrefValue = "";

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->HrefValue = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->HrefValue = "";

			// TIPO_INFORME
			$this->TIPO_INFORME->HrefValue = "";

			// FECHA_REPORT
			$this->FECHA_REPORT->HrefValue = "";

			// DIA
			$this->DIA->HrefValue = "";

			// MES
			$this->MES->HrefValue = "";

			// Departamento
			$this->Departamento->HrefValue = "";

			// Muncipio
			$this->Muncipio->HrefValue = "";

			// TEMA
			$this->TEMA->HrefValue = "";

			// Otro_Tema
			$this->Otro_Tema->HrefValue = "";

			// OBSERVACION
			$this->OBSERVACION->HrefValue = "";

			// FUERZA
			$this->FUERZA->HrefValue = "";

			// NOM_VDA
			$this->NOM_VDA->HrefValue = "";

			// Ha_Coca
			$this->Ha_Coca->HrefValue = "";

			// Ha_Amapola
			$this->Ha_Amapola->HrefValue = "";

			// Ha_Marihuana
			$this->Ha_Marihuana->HrefValue = "";

			// T_erradi
			$this->T_erradi->HrefValue = "";

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->HrefValue = "";

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->HrefValue = "";

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->HrefValue = "";

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->HrefValue = "";

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->HrefValue = "";

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->HrefValue = "";

			// Ini_Jorna
			$this->Ini_Jorna->HrefValue = "";

			// Fin_Jorna
			$this->Fin_Jorna->HrefValue = "";

			// Situ_Especial
			$this->Situ_Especial->HrefValue = "";

			// Adm_GME
			$this->Adm_GME->HrefValue = "";

			// 1_Abastecimiento
			$this->_1_Abastecimiento->HrefValue = "";

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->HrefValue = "";

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->HrefValue = "";

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->HrefValue = "";

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->HrefValue = "";

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->HrefValue = "";

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->HrefValue = "";

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->HrefValue = "";

			// 1_Extraccion
			$this->_1_Extraccion->HrefValue = "";

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->HrefValue = "";

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->HrefValue = "";

			// 1_Insercion
			$this->_1_Insercion->HrefValue = "";

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->HrefValue = "";

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->HrefValue = "";

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->HrefValue = "";

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->HrefValue = "";

			// 1_Transporte_bus
			$this->_1_Transporte_bus->HrefValue = "";

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->HrefValue = "";

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->HrefValue = "";

			// Adm_Fuerza
			$this->Adm_Fuerza->HrefValue = "";

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->HrefValue = "";

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->HrefValue = "";

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->HrefValue = "";

			// 2_Induccion_FP
			$this->_2_Induccion_FP->HrefValue = "";

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->HrefValue = "";

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->HrefValue = "";

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->HrefValue = "";

			// Sit_Seguridad
			$this->Sit_Seguridad->HrefValue = "";

			// 3_AEI_controlado
			$this->_3_AEI_controlado->HrefValue = "";

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->HrefValue = "";

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->HrefValue = "";

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->HrefValue = "";

			// 3_Combate
			$this->_3_Combate->HrefValue = "";

			// 3_Hostigamiento
			$this->_3_Hostigamiento->HrefValue = "";

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->HrefValue = "";

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->HrefValue = "";

			// 3_MUSE
			$this->_3_MUSE->HrefValue = "";

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->HrefValue = "";

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->HrefValue = "";

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->HrefValue = "";

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->HrefValue = "";

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->HrefValue = "";

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->HrefValue = "";

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->HrefValue = "";

			// Novedad
			$this->Novedad->HrefValue = "";

			// 4_Epidemia
			$this->_4_Epidemia->HrefValue = "";

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->HrefValue = "";

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->HrefValue = "";

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->HrefValue = "";

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->HrefValue = "";

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->HrefValue = "";

			// Num_Erra_Salen
			$this->Num_Erra_Salen->HrefValue = "";

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->HrefValue = "";

			// No_ENFERMERO
			$this->No_ENFERMERO->HrefValue = "";

			// NUM_FP
			$this->NUM_FP->HrefValue = "";

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->HrefValue = "";

			// NUM_Poli
			$this->NUM_Poli->HrefValue = "";

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
		if (!ew_CheckNumber($this->Ha_Coca->FormValue)) {
			ew_AddMessage($gsFormError, $this->Ha_Coca->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Ha_Amapola->FormValue)) {
			ew_AddMessage($gsFormError, $this->Ha_Amapola->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Ha_Marihuana->FormValue)) {
			ew_AddMessage($gsFormError, $this->Ha_Marihuana->FldErrMsg());
		}
		if (!ew_CheckNumber($this->T_erradi->FormValue)) {
			ew_AddMessage($gsFormError, $this->T_erradi->FldErrMsg());
		}
		if (!ew_CheckInteger($this->GRA_LAT_Sector->FormValue)) {
			ew_AddMessage($gsFormError, $this->GRA_LAT_Sector->FldErrMsg());
		}
		if (!ew_CheckInteger($this->MIN_LAT_Sector->FormValue)) {
			ew_AddMessage($gsFormError, $this->MIN_LAT_Sector->FldErrMsg());
		}
		if (!ew_CheckNumber($this->SEG_LAT_Sector->FormValue)) {
			ew_AddMessage($gsFormError, $this->SEG_LAT_Sector->FldErrMsg());
		}
		if (!ew_CheckInteger($this->GRA_LONG_Sector->FormValue)) {
			ew_AddMessage($gsFormError, $this->GRA_LONG_Sector->FldErrMsg());
		}
		if (!ew_CheckInteger($this->MIN_LONG_Sector->FormValue)) {
			ew_AddMessage($gsFormError, $this->MIN_LONG_Sector->FldErrMsg());
		}
		if (!ew_CheckNumber($this->SEG_LONG_Sector->FormValue)) {
			ew_AddMessage($gsFormError, $this->SEG_LONG_Sector->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Adm_GME->FormValue)) {
			ew_AddMessage($gsFormError, $this->Adm_GME->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Abastecimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Abastecimiento->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Acompanamiento_firma_GME->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Acompanamiento_firma_GME->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Apoyo_zonal_sin_punto_asignado->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Apoyo_zonal_sin_punto_asignado->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Descanso_en_dia_habil->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Descanso_en_dia_habil->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Descanso_festivo_dominical->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Descanso_festivo_dominical->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Dia_compensatorio->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Dia_compensatorio->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Erradicacion_en_dia_festivo->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Erradicacion_en_dia_festivo->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Espera_helicoptero_Helistar->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Espera_helicoptero_Helistar->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Extraccion->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Extraccion->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Firma_contrato_GME->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Firma_contrato_GME->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Induccion_Apoyo_Zonal->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Induccion_Apoyo_Zonal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Insercion->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Insercion->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Novedad_apoyo_zonal->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Novedad_apoyo_zonal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Novedad_enfermero->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Novedad_enfermero->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Punto_fuera_del_area_de_erradicacion->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Punto_fuera_del_area_de_erradicacion->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Transporte_bus->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Transporte_bus->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Traslado_apoyo_zonal->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Traslado_apoyo_zonal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_1_Traslado_area_vivac->FormValue)) {
			ew_AddMessage($gsFormError, $this->_1_Traslado_area_vivac->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Adm_Fuerza->FormValue)) {
			ew_AddMessage($gsFormError, $this->Adm_Fuerza->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_2_A_la_espera_definicion_nuevo_punto_FP->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_A_la_espera_definicion_nuevo_punto_FP->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_2_Espera_helicoptero_FP_de_seguridad->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Espera_helicoptero_FP_de_seguridad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_2_Espera_helicoptero_FP_que_abastece->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Espera_helicoptero_FP_que_abastece->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_2_Induccion_FP->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Induccion_FP->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_2_Novedad_canino_o_del_grupo_de_deteccion->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_2_Problemas_fuerza_publica->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Problemas_fuerza_publica->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_2_Sin_seguridad->FormValue)) {
			ew_AddMessage($gsFormError, $this->_2_Sin_seguridad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Sit_Seguridad->FormValue)) {
			ew_AddMessage($gsFormError, $this->Sit_Seguridad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_AEI_controlado->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_AEI_controlado->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_AEI_no_controlado->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_AEI_no_controlado->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_Bloqueo_parcial_de_la_comunidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Bloqueo_parcial_de_la_comunidad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_Bloqueo_total_de_la_comunidad->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Bloqueo_total_de_la_comunidad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_Combate->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Combate->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_Hostigamiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Hostigamiento->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_MAP_Controlada->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_MAP_Controlada->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_MAP_No_controlada->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_MAP_No_controlada->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_MUSE->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_MUSE->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_3_Operaciones_de_seguridad->FormValue)) {
			ew_AddMessage($gsFormError, $this->_3_Operaciones_de_seguridad->FldErrMsg());
		}
		if (!ew_CheckInteger($this->GRA_LAT_segurid->FormValue)) {
			ew_AddMessage($gsFormError, $this->GRA_LAT_segurid->FldErrMsg());
		}
		if (!ew_CheckInteger($this->MIN_LAT_segurid->FormValue)) {
			ew_AddMessage($gsFormError, $this->MIN_LAT_segurid->FldErrMsg());
		}
		if (!ew_CheckNumber($this->SEG_LAT_segurid->FormValue)) {
			ew_AddMessage($gsFormError, $this->SEG_LAT_segurid->FldErrMsg());
		}
		if (!ew_CheckInteger($this->GRA_LONG_seguri->FormValue)) {
			ew_AddMessage($gsFormError, $this->GRA_LONG_seguri->FldErrMsg());
		}
		if (!ew_CheckInteger($this->MIN_LONG_seguri->FormValue)) {
			ew_AddMessage($gsFormError, $this->MIN_LONG_seguri->FldErrMsg());
		}
		if (!ew_CheckNumber($this->SEG_LONG_seguri->FormValue)) {
			ew_AddMessage($gsFormError, $this->SEG_LONG_seguri->FldErrMsg());
		}
		if (!ew_CheckNumber($this->Novedad->FormValue)) {
			ew_AddMessage($gsFormError, $this->Novedad->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_4_Epidemia->FormValue)) {
			ew_AddMessage($gsFormError, $this->_4_Epidemia->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_4_Novedad_climatologica->FormValue)) {
			ew_AddMessage($gsFormError, $this->_4_Novedad_climatologica->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_4_Registro_de_cultivos->FormValue)) {
			ew_AddMessage($gsFormError, $this->_4_Registro_de_cultivos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_4_Zona_con_cultivos_muy_dispersos->FormValue)) {
			ew_AddMessage($gsFormError, $this->_4_Zona_con_cultivos_muy_dispersos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_4_Zona_de_cruce_de_rios_caudalosos->FormValue)) {
			ew_AddMessage($gsFormError, $this->_4_Zona_de_cruce_de_rios_caudalosos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->_4_Zona_sin_cultivos->FormValue)) {
			ew_AddMessage($gsFormError, $this->_4_Zona_sin_cultivos->FldErrMsg());
		}
		if (!ew_CheckInteger($this->Num_Erra_Salen->FormValue)) {
			ew_AddMessage($gsFormError, $this->Num_Erra_Salen->FldErrMsg());
		}
		if (!ew_CheckInteger($this->Num_Erra_Quedan->FormValue)) {
			ew_AddMessage($gsFormError, $this->Num_Erra_Quedan->FldErrMsg());
		}
		if (!ew_CheckInteger($this->NUM_FP->FormValue)) {
			ew_AddMessage($gsFormError, $this->NUM_FP->FldErrMsg());
		}
		if (!ew_CheckInteger($this->NUM_Perso_EVA->FormValue)) {
			ew_AddMessage($gsFormError, $this->NUM_Perso_EVA->FldErrMsg());
		}
		if (!ew_CheckInteger($this->NUM_Poli->FormValue)) {
			ew_AddMessage($gsFormError, $this->NUM_Poli->FldErrMsg());
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

			// NOM_PGE
			$this->NOM_PGE->SetDbValueDef($rsnew, $this->NOM_PGE->CurrentValue, NULL, $this->NOM_PGE->ReadOnly);

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->SetDbValueDef($rsnew, $this->Otro_NOM_PGE->CurrentValue, NULL, $this->Otro_NOM_PGE->ReadOnly);

			// Otro_CC_PGE
			$this->Otro_CC_PGE->SetDbValueDef($rsnew, $this->Otro_CC_PGE->CurrentValue, NULL, $this->Otro_CC_PGE->ReadOnly);

			// TIPO_INFORME
			$this->TIPO_INFORME->SetDbValueDef($rsnew, $this->TIPO_INFORME->CurrentValue, NULL, $this->TIPO_INFORME->ReadOnly);

			// FECHA_REPORT
			$this->FECHA_REPORT->SetDbValueDef($rsnew, $this->FECHA_REPORT->CurrentValue, NULL, $this->FECHA_REPORT->ReadOnly);

			// DIA
			$this->DIA->SetDbValueDef($rsnew, $this->DIA->CurrentValue, NULL, $this->DIA->ReadOnly);

			// MES
			$this->MES->SetDbValueDef($rsnew, $this->MES->CurrentValue, NULL, $this->MES->ReadOnly);

			// Departamento
			$this->Departamento->SetDbValueDef($rsnew, $this->Departamento->CurrentValue, NULL, $this->Departamento->ReadOnly);

			// Muncipio
			$this->Muncipio->SetDbValueDef($rsnew, $this->Muncipio->CurrentValue, NULL, $this->Muncipio->ReadOnly);

			// TEMA
			$this->TEMA->SetDbValueDef($rsnew, $this->TEMA->CurrentValue, NULL, $this->TEMA->ReadOnly);

			// Otro_Tema
			$this->Otro_Tema->SetDbValueDef($rsnew, $this->Otro_Tema->CurrentValue, NULL, $this->Otro_Tema->ReadOnly);

			// OBSERVACION
			$this->OBSERVACION->SetDbValueDef($rsnew, $this->OBSERVACION->CurrentValue, NULL, $this->OBSERVACION->ReadOnly);

			// FUERZA
			$this->FUERZA->SetDbValueDef($rsnew, $this->FUERZA->CurrentValue, NULL, $this->FUERZA->ReadOnly);

			// NOM_VDA
			$this->NOM_VDA->SetDbValueDef($rsnew, $this->NOM_VDA->CurrentValue, NULL, $this->NOM_VDA->ReadOnly);

			// Ha_Coca
			$this->Ha_Coca->SetDbValueDef($rsnew, $this->Ha_Coca->CurrentValue, 0, $this->Ha_Coca->ReadOnly);

			// Ha_Amapola
			$this->Ha_Amapola->SetDbValueDef($rsnew, $this->Ha_Amapola->CurrentValue, 0, $this->Ha_Amapola->ReadOnly);

			// Ha_Marihuana
			$this->Ha_Marihuana->SetDbValueDef($rsnew, $this->Ha_Marihuana->CurrentValue, 0, $this->Ha_Marihuana->ReadOnly);

			// T_erradi
			$this->T_erradi->SetDbValueDef($rsnew, $this->T_erradi->CurrentValue, NULL, $this->T_erradi->ReadOnly);

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->SetDbValueDef($rsnew, $this->GRA_LAT_Sector->CurrentValue, NULL, $this->GRA_LAT_Sector->ReadOnly);

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->SetDbValueDef($rsnew, $this->MIN_LAT_Sector->CurrentValue, NULL, $this->MIN_LAT_Sector->ReadOnly);

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->SetDbValueDef($rsnew, $this->SEG_LAT_Sector->CurrentValue, 0, $this->SEG_LAT_Sector->ReadOnly);

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->SetDbValueDef($rsnew, $this->GRA_LONG_Sector->CurrentValue, NULL, $this->GRA_LONG_Sector->ReadOnly);

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->SetDbValueDef($rsnew, $this->MIN_LONG_Sector->CurrentValue, NULL, $this->MIN_LONG_Sector->ReadOnly);

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->SetDbValueDef($rsnew, $this->SEG_LONG_Sector->CurrentValue, 0, $this->SEG_LONG_Sector->ReadOnly);

			// Ini_Jorna
			$this->Ini_Jorna->SetDbValueDef($rsnew, $this->Ini_Jorna->CurrentValue, NULL, $this->Ini_Jorna->ReadOnly);

			// Fin_Jorna
			$this->Fin_Jorna->SetDbValueDef($rsnew, $this->Fin_Jorna->CurrentValue, NULL, $this->Fin_Jorna->ReadOnly);

			// Situ_Especial
			$this->Situ_Especial->SetDbValueDef($rsnew, $this->Situ_Especial->CurrentValue, NULL, $this->Situ_Especial->ReadOnly);

			// Adm_GME
			$this->Adm_GME->SetDbValueDef($rsnew, $this->Adm_GME->CurrentValue, NULL, $this->Adm_GME->ReadOnly);

			// 1_Abastecimiento
			$this->_1_Abastecimiento->SetDbValueDef($rsnew, $this->_1_Abastecimiento->CurrentValue, NULL, $this->_1_Abastecimiento->ReadOnly);

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->SetDbValueDef($rsnew, $this->_1_Acompanamiento_firma_GME->CurrentValue, NULL, $this->_1_Acompanamiento_firma_GME->ReadOnly);

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->SetDbValueDef($rsnew, $this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue, NULL, $this->_1_Apoyo_zonal_sin_punto_asignado->ReadOnly);

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->SetDbValueDef($rsnew, $this->_1_Descanso_en_dia_habil->CurrentValue, NULL, $this->_1_Descanso_en_dia_habil->ReadOnly);

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->SetDbValueDef($rsnew, $this->_1_Descanso_festivo_dominical->CurrentValue, NULL, $this->_1_Descanso_festivo_dominical->ReadOnly);

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->SetDbValueDef($rsnew, $this->_1_Dia_compensatorio->CurrentValue, NULL, $this->_1_Dia_compensatorio->ReadOnly);

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->SetDbValueDef($rsnew, $this->_1_Erradicacion_en_dia_festivo->CurrentValue, NULL, $this->_1_Erradicacion_en_dia_festivo->ReadOnly);

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->SetDbValueDef($rsnew, $this->_1_Espera_helicoptero_Helistar->CurrentValue, NULL, $this->_1_Espera_helicoptero_Helistar->ReadOnly);

			// 1_Extraccion
			$this->_1_Extraccion->SetDbValueDef($rsnew, $this->_1_Extraccion->CurrentValue, NULL, $this->_1_Extraccion->ReadOnly);

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->SetDbValueDef($rsnew, $this->_1_Firma_contrato_GME->CurrentValue, NULL, $this->_1_Firma_contrato_GME->ReadOnly);

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->SetDbValueDef($rsnew, $this->_1_Induccion_Apoyo_Zonal->CurrentValue, NULL, $this->_1_Induccion_Apoyo_Zonal->ReadOnly);

			// 1_Insercion
			$this->_1_Insercion->SetDbValueDef($rsnew, $this->_1_Insercion->CurrentValue, NULL, $this->_1_Insercion->ReadOnly);

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->SetDbValueDef($rsnew, $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue, NULL, $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ReadOnly);

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->SetDbValueDef($rsnew, $this->_1_Novedad_apoyo_zonal->CurrentValue, NULL, $this->_1_Novedad_apoyo_zonal->ReadOnly);

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->SetDbValueDef($rsnew, $this->_1_Novedad_enfermero->CurrentValue, NULL, $this->_1_Novedad_enfermero->ReadOnly);

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->SetDbValueDef($rsnew, $this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue, NULL, $this->_1_Punto_fuera_del_area_de_erradicacion->ReadOnly);

			// 1_Transporte_bus
			$this->_1_Transporte_bus->SetDbValueDef($rsnew, $this->_1_Transporte_bus->CurrentValue, NULL, $this->_1_Transporte_bus->ReadOnly);

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->SetDbValueDef($rsnew, $this->_1_Traslado_apoyo_zonal->CurrentValue, NULL, $this->_1_Traslado_apoyo_zonal->ReadOnly);

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->SetDbValueDef($rsnew, $this->_1_Traslado_area_vivac->CurrentValue, NULL, $this->_1_Traslado_area_vivac->ReadOnly);

			// Adm_Fuerza
			$this->Adm_Fuerza->SetDbValueDef($rsnew, $this->Adm_Fuerza->CurrentValue, NULL, $this->Adm_Fuerza->ReadOnly);

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->SetDbValueDef($rsnew, $this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue, NULL, $this->_2_A_la_espera_definicion_nuevo_punto_FP->ReadOnly);

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->SetDbValueDef($rsnew, $this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue, NULL, $this->_2_Espera_helicoptero_FP_de_seguridad->ReadOnly);

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->SetDbValueDef($rsnew, $this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue, NULL, $this->_2_Espera_helicoptero_FP_que_abastece->ReadOnly);

			// 2_Induccion_FP
			$this->_2_Induccion_FP->SetDbValueDef($rsnew, $this->_2_Induccion_FP->CurrentValue, NULL, $this->_2_Induccion_FP->ReadOnly);

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->SetDbValueDef($rsnew, $this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue, NULL, $this->_2_Novedad_canino_o_del_grupo_de_deteccion->ReadOnly);

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->SetDbValueDef($rsnew, $this->_2_Problemas_fuerza_publica->CurrentValue, NULL, $this->_2_Problemas_fuerza_publica->ReadOnly);

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->SetDbValueDef($rsnew, $this->_2_Sin_seguridad->CurrentValue, NULL, $this->_2_Sin_seguridad->ReadOnly);

			// Sit_Seguridad
			$this->Sit_Seguridad->SetDbValueDef($rsnew, $this->Sit_Seguridad->CurrentValue, NULL, $this->Sit_Seguridad->ReadOnly);

			// 3_AEI_controlado
			$this->_3_AEI_controlado->SetDbValueDef($rsnew, $this->_3_AEI_controlado->CurrentValue, NULL, $this->_3_AEI_controlado->ReadOnly);

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->SetDbValueDef($rsnew, $this->_3_AEI_no_controlado->CurrentValue, NULL, $this->_3_AEI_no_controlado->ReadOnly);

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->SetDbValueDef($rsnew, $this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue, NULL, $this->_3_Bloqueo_parcial_de_la_comunidad->ReadOnly);

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->SetDbValueDef($rsnew, $this->_3_Bloqueo_total_de_la_comunidad->CurrentValue, NULL, $this->_3_Bloqueo_total_de_la_comunidad->ReadOnly);

			// 3_Combate
			$this->_3_Combate->SetDbValueDef($rsnew, $this->_3_Combate->CurrentValue, NULL, $this->_3_Combate->ReadOnly);

			// 3_Hostigamiento
			$this->_3_Hostigamiento->SetDbValueDef($rsnew, $this->_3_Hostigamiento->CurrentValue, NULL, $this->_3_Hostigamiento->ReadOnly);

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->SetDbValueDef($rsnew, $this->_3_MAP_Controlada->CurrentValue, NULL, $this->_3_MAP_Controlada->ReadOnly);

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->SetDbValueDef($rsnew, $this->_3_MAP_No_controlada->CurrentValue, NULL, $this->_3_MAP_No_controlada->ReadOnly);

			// 3_MUSE
			$this->_3_MUSE->SetDbValueDef($rsnew, $this->_3_MUSE->CurrentValue, NULL, $this->_3_MUSE->ReadOnly);

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->SetDbValueDef($rsnew, $this->_3_Operaciones_de_seguridad->CurrentValue, NULL, $this->_3_Operaciones_de_seguridad->ReadOnly);

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->SetDbValueDef($rsnew, $this->GRA_LAT_segurid->CurrentValue, NULL, $this->GRA_LAT_segurid->ReadOnly);

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->SetDbValueDef($rsnew, $this->MIN_LAT_segurid->CurrentValue, NULL, $this->MIN_LAT_segurid->ReadOnly);

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->SetDbValueDef($rsnew, $this->SEG_LAT_segurid->CurrentValue, 0, $this->SEG_LAT_segurid->ReadOnly);

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->SetDbValueDef($rsnew, $this->GRA_LONG_seguri->CurrentValue, NULL, $this->GRA_LONG_seguri->ReadOnly);

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->SetDbValueDef($rsnew, $this->MIN_LONG_seguri->CurrentValue, NULL, $this->MIN_LONG_seguri->ReadOnly);

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->SetDbValueDef($rsnew, $this->SEG_LONG_seguri->CurrentValue, 0, $this->SEG_LONG_seguri->ReadOnly);

			// Novedad
			$this->Novedad->SetDbValueDef($rsnew, $this->Novedad->CurrentValue, NULL, $this->Novedad->ReadOnly);

			// 4_Epidemia
			$this->_4_Epidemia->SetDbValueDef($rsnew, $this->_4_Epidemia->CurrentValue, NULL, $this->_4_Epidemia->ReadOnly);

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->SetDbValueDef($rsnew, $this->_4_Novedad_climatologica->CurrentValue, NULL, $this->_4_Novedad_climatologica->ReadOnly);

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->SetDbValueDef($rsnew, $this->_4_Registro_de_cultivos->CurrentValue, NULL, $this->_4_Registro_de_cultivos->ReadOnly);

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->SetDbValueDef($rsnew, $this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue, NULL, $this->_4_Zona_con_cultivos_muy_dispersos->ReadOnly);

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->SetDbValueDef($rsnew, $this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue, NULL, $this->_4_Zona_de_cruce_de_rios_caudalosos->ReadOnly);

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->SetDbValueDef($rsnew, $this->_4_Zona_sin_cultivos->CurrentValue, NULL, $this->_4_Zona_sin_cultivos->ReadOnly);

			// Num_Erra_Salen
			$this->Num_Erra_Salen->SetDbValueDef($rsnew, $this->Num_Erra_Salen->CurrentValue, NULL, $this->Num_Erra_Salen->ReadOnly);

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->SetDbValueDef($rsnew, $this->Num_Erra_Quedan->CurrentValue, NULL, $this->Num_Erra_Quedan->ReadOnly);

			// No_ENFERMERO
			$this->No_ENFERMERO->SetDbValueDef($rsnew, $this->No_ENFERMERO->CurrentValue, NULL, $this->No_ENFERMERO->ReadOnly);

			// NUM_FP
			$this->NUM_FP->SetDbValueDef($rsnew, $this->NUM_FP->CurrentValue, NULL, $this->NUM_FP->ReadOnly);

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->SetDbValueDef($rsnew, $this->NUM_Perso_EVA->CurrentValue, NULL, $this->NUM_Perso_EVA->ReadOnly);

			// NUM_Poli
			$this->NUM_Poli->SetDbValueDef($rsnew, $this->NUM_Poli->CurrentValue, NULL, $this->NUM_Poli->ReadOnly);

			// AÑO
			$this->AD1O->SetDbValueDef($rsnew, $this->AD1O->CurrentValue, NULL, $this->AD1O->ReadOnly);

			// FASE
			$this->FASE->SetDbValueDef($rsnew, $this->FASE->CurrentValue, NULL, $this->FASE->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "view_idlist.php", "", $this->TableVar, TRUE);
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
if (!isset($view_id_edit)) $view_id_edit = new cview_id_edit();

// Page init
$view_id_edit->Page_Init();

// Page main
$view_id_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_id_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_id_edit = new ew_Page("view_id_edit");
view_id_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = view_id_edit.PageID; // For backward compatibility

// Form object
var fview_idedit = new ew_Form("fview_idedit");

// Validate form
fview_idedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Ha_Coca");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Ha_Coca->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Ha_Amapola");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Ha_Amapola->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Ha_Marihuana");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Ha_Marihuana->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_T_erradi");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->T_erradi->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_GRA_LAT_Sector");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->GRA_LAT_Sector->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_MIN_LAT_Sector");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->MIN_LAT_Sector->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SEG_LAT_Sector");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->SEG_LAT_Sector->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_GRA_LONG_Sector");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->GRA_LONG_Sector->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_MIN_LONG_Sector");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->MIN_LONG_Sector->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SEG_LONG_Sector");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->SEG_LONG_Sector->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Adm_GME");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Adm_GME->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Abastecimiento");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Abastecimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Acompanamiento_firma_GME");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Acompanamiento_firma_GME->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Apoyo_zonal_sin_punto_asignado");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Apoyo_zonal_sin_punto_asignado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Descanso_en_dia_habil");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Descanso_en_dia_habil->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Descanso_festivo_dominical");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Descanso_festivo_dominical->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Dia_compensatorio");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Dia_compensatorio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Erradicacion_en_dia_festivo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Erradicacion_en_dia_festivo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Espera_helicoptero_Helistar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Espera_helicoptero_Helistar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Extraccion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Extraccion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Firma_contrato_GME");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Firma_contrato_GME->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Induccion_Apoyo_Zonal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Induccion_Apoyo_Zonal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Insercion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Insercion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Novedad_apoyo_zonal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Novedad_apoyo_zonal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Novedad_enfermero");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Novedad_enfermero->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Punto_fuera_del_area_de_erradicacion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Punto_fuera_del_area_de_erradicacion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Transporte_bus");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Transporte_bus->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Traslado_apoyo_zonal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Traslado_apoyo_zonal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__1_Traslado_area_vivac");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_1_Traslado_area_vivac->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Adm_Fuerza");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Adm_Fuerza->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_A_la_espera_definicion_nuevo_punto_FP");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Espera_helicoptero_FP_de_seguridad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_2_Espera_helicoptero_FP_de_seguridad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Espera_helicoptero_FP_que_abastece");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_2_Espera_helicoptero_FP_que_abastece->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Induccion_FP");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_2_Induccion_FP->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Novedad_canino_o_del_grupo_de_deteccion");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Problemas_fuerza_publica");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_2_Problemas_fuerza_publica->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__2_Sin_seguridad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_2_Sin_seguridad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Sit_Seguridad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Sit_Seguridad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_AEI_controlado");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_AEI_controlado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_AEI_no_controlado");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_AEI_no_controlado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Bloqueo_parcial_de_la_comunidad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_Bloqueo_parcial_de_la_comunidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Bloqueo_total_de_la_comunidad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_Bloqueo_total_de_la_comunidad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Combate");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_Combate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Hostigamiento");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_Hostigamiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_MAP_Controlada");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_MAP_Controlada->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_MAP_No_controlada");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_MAP_No_controlada->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_MUSE");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_MUSE->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__3_Operaciones_de_seguridad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_3_Operaciones_de_seguridad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_GRA_LAT_segurid");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->GRA_LAT_segurid->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_MIN_LAT_segurid");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->MIN_LAT_segurid->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SEG_LAT_segurid");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->SEG_LAT_segurid->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_GRA_LONG_seguri");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->GRA_LONG_seguri->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_MIN_LONG_seguri");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->MIN_LONG_seguri->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SEG_LONG_seguri");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->SEG_LONG_seguri->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Novedad");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Novedad->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__4_Epidemia");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_4_Epidemia->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__4_Novedad_climatologica");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_4_Novedad_climatologica->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__4_Registro_de_cultivos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_4_Registro_de_cultivos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__4_Zona_con_cultivos_muy_dispersos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_4_Zona_con_cultivos_muy_dispersos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__4_Zona_de_cruce_de_rios_caudalosos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_4_Zona_de_cruce_de_rios_caudalosos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__4_Zona_sin_cultivos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->_4_Zona_sin_cultivos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Num_Erra_Salen");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Num_Erra_Salen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Num_Erra_Quedan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->Num_Erra_Quedan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_NUM_FP");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->NUM_FP->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_NUM_Perso_EVA");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->NUM_Perso_EVA->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_NUM_Poli");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($view_id->NUM_Poli->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Modificado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $view_id->Modificado->FldCaption(), $view_id->Modificado->ReqErrMsg)) ?>");

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
fview_idedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_idedit.ValidateRequired = true;
<?php } else { ?>
fview_idedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_idedit.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idedit.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idedit.Lists["x_NOM_PGE"] = {"LinkField":"x_NOM_PGE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PGE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idedit.Lists["x_TIPO_INFORME"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idedit.Lists["x_TEMA"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idedit.Lists["x_FUERZA"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idedit.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idedit.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $view_id_edit->ShowPageHeader(); ?>
<?php
$view_id_edit->ShowMessage();
?>
<form name="fview_idedit" id="fview_idedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_id_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_id_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_id">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($view_id->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<div>
<?php if ($view_id->llave->Visible) { // llave ?>
	<div id="r_llave" class="form-group">
		<label id="elh_view_id_llave" for="x_llave" class="col-sm-2 control-label ewLabel"><?php echo $view_id->llave->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->llave->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_llave">
<span<?php echo $view_id->llave->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->llave->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_llave" name="x_llave" id="x_llave" value="<?php echo ew_HtmlEncode($view_id->llave->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_id_llave">
<span<?php echo $view_id->llave->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->llave->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_llave" name="x_llave" id="x_llave" value="<?php echo ew_HtmlEncode($view_id->llave->FormValue) ?>">
<?php } ?>
<?php echo $view_id->llave->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->F_Sincron->Visible) { // F_Sincron ?>
	<div id="r_F_Sincron" class="form-group">
		<label id="elh_view_id_F_Sincron" for="x_F_Sincron" class="col-sm-2 control-label ewLabel"><?php echo $view_id->F_Sincron->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->F_Sincron->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_F_Sincron">
<span<?php echo $view_id->F_Sincron->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->F_Sincron->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" value="<?php echo ew_HtmlEncode($view_id->F_Sincron->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_id_F_Sincron">
<span<?php echo $view_id->F_Sincron->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->F_Sincron->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_F_Sincron" name="x_F_Sincron" id="x_F_Sincron" value="<?php echo ew_HtmlEncode($view_id->F_Sincron->FormValue) ?>">
<?php } ?>
<?php echo $view_id->F_Sincron->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->USUARIO->Visible) { // USUARIO ?>
	<div id="r_USUARIO" class="form-group">
		<label id="elh_view_id_USUARIO" for="x_USUARIO" class="col-sm-2 control-label ewLabel"><?php echo $view_id->USUARIO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->USUARIO->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_USUARIO">
<span<?php echo $view_id->USUARIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->USUARIO->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" value="<?php echo ew_HtmlEncode($view_id->USUARIO->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_id_USUARIO">
<span<?php echo $view_id->USUARIO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->USUARIO->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" value="<?php echo ew_HtmlEncode($view_id->USUARIO->FormValue) ?>">
<?php } ?>
<?php echo $view_id->USUARIO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Cargo_gme->Visible) { // Cargo_gme ?>
	<div id="r_Cargo_gme" class="form-group">
		<label id="elh_view_id_Cargo_gme" for="x_Cargo_gme" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Cargo_gme->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Cargo_gme->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Cargo_gme">
<span<?php echo $view_id->Cargo_gme->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Cargo_gme->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" value="<?php echo ew_HtmlEncode($view_id->Cargo_gme->CurrentValue) ?>">
<?php } else { ?>
<span id="el_view_id_Cargo_gme">
<span<?php echo $view_id->Cargo_gme->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Cargo_gme->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Cargo_gme" name="x_Cargo_gme" id="x_Cargo_gme" value="<?php echo ew_HtmlEncode($view_id->Cargo_gme->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Cargo_gme->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->NOM_PE->Visible) { // NOM_PE ?>
	<div id="r_NOM_PE" class="form-group">
		<label id="elh_view_id_NOM_PE" for="x_NOM_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_id->NOM_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->NOM_PE->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_NOM_PE">
<select data-field="x_NOM_PE" id="x_NOM_PE" name="x_NOM_PE"<?php echo $view_id->NOM_PE->EditAttributes() ?>>
<?php
if (is_array($view_id->NOM_PE->EditValue)) {
	$arwrk = $view_id->NOM_PE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->NOM_PE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_idedit.Lists["x_NOM_PE"].Options = <?php echo (is_array($view_id->NOM_PE->EditValue)) ? ew_ArrayToJson($view_id->NOM_PE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_id_NOM_PE">
<span<?php echo $view_id->NOM_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->NOM_PE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_PE" name="x_NOM_PE" id="x_NOM_PE" value="<?php echo ew_HtmlEncode($view_id->NOM_PE->FormValue) ?>">
<?php } ?>
<?php echo $view_id->NOM_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Otro_PE->Visible) { // Otro_PE ?>
	<div id="r_Otro_PE" class="form-group">
		<label id="elh_view_id_Otro_PE" for="x_Otro_PE" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Otro_PE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Otro_PE->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Otro_PE">
<input type="text" data-field="x_Otro_PE" name="x_Otro_PE" id="x_Otro_PE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_id->Otro_PE->PlaceHolder) ?>" value="<?php echo $view_id->Otro_PE->EditValue ?>"<?php echo $view_id->Otro_PE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Otro_PE">
<span<?php echo $view_id->Otro_PE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Otro_PE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_PE" name="x_Otro_PE" id="x_Otro_PE" value="<?php echo ew_HtmlEncode($view_id->Otro_PE->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Otro_PE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->NOM_PGE->Visible) { // NOM_PGE ?>
	<div id="r_NOM_PGE" class="form-group">
		<label id="elh_view_id_NOM_PGE" for="x_NOM_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view_id->NOM_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->NOM_PGE->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_NOM_PGE">
<select data-field="x_NOM_PGE" id="x_NOM_PGE" name="x_NOM_PGE"<?php echo $view_id->NOM_PGE->EditAttributes() ?>>
<?php
if (is_array($view_id->NOM_PGE->EditValue)) {
	$arwrk = $view_id->NOM_PGE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->NOM_PGE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_idedit.Lists["x_NOM_PGE"].Options = <?php echo (is_array($view_id->NOM_PGE->EditValue)) ? ew_ArrayToJson($view_id->NOM_PGE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_id_NOM_PGE">
<span<?php echo $view_id->NOM_PGE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->NOM_PGE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_PGE" name="x_NOM_PGE" id="x_NOM_PGE" value="<?php echo ew_HtmlEncode($view_id->NOM_PGE->FormValue) ?>">
<?php } ?>
<?php echo $view_id->NOM_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Otro_NOM_PGE->Visible) { // Otro_NOM_PGE ?>
	<div id="r_Otro_NOM_PGE" class="form-group">
		<label id="elh_view_id_Otro_NOM_PGE" for="x_Otro_NOM_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Otro_NOM_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Otro_NOM_PGE->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Otro_NOM_PGE">
<input type="text" data-field="x_Otro_NOM_PGE" name="x_Otro_NOM_PGE" id="x_Otro_NOM_PGE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_id->Otro_NOM_PGE->PlaceHolder) ?>" value="<?php echo $view_id->Otro_NOM_PGE->EditValue ?>"<?php echo $view_id->Otro_NOM_PGE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Otro_NOM_PGE">
<span<?php echo $view_id->Otro_NOM_PGE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Otro_NOM_PGE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_NOM_PGE" name="x_Otro_NOM_PGE" id="x_Otro_NOM_PGE" value="<?php echo ew_HtmlEncode($view_id->Otro_NOM_PGE->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Otro_NOM_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
	<div id="r_Otro_CC_PGE" class="form-group">
		<label id="elh_view_id_Otro_CC_PGE" for="x_Otro_CC_PGE" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Otro_CC_PGE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Otro_CC_PGE->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Otro_CC_PGE">
<input type="text" data-field="x_Otro_CC_PGE" name="x_Otro_CC_PGE" id="x_Otro_CC_PGE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_id->Otro_CC_PGE->PlaceHolder) ?>" value="<?php echo $view_id->Otro_CC_PGE->EditValue ?>"<?php echo $view_id->Otro_CC_PGE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Otro_CC_PGE">
<span<?php echo $view_id->Otro_CC_PGE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Otro_CC_PGE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_CC_PGE" name="x_Otro_CC_PGE" id="x_Otro_CC_PGE" value="<?php echo ew_HtmlEncode($view_id->Otro_CC_PGE->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Otro_CC_PGE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
	<div id="r_TIPO_INFORME" class="form-group">
		<label id="elh_view_id_TIPO_INFORME" for="x_TIPO_INFORME" class="col-sm-2 control-label ewLabel"><?php echo $view_id->TIPO_INFORME->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->TIPO_INFORME->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_TIPO_INFORME">
<select data-field="x_TIPO_INFORME" id="x_TIPO_INFORME" name="x_TIPO_INFORME"<?php echo $view_id->TIPO_INFORME->EditAttributes() ?>>
<?php
if (is_array($view_id->TIPO_INFORME->EditValue)) {
	$arwrk = $view_id->TIPO_INFORME->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->TIPO_INFORME->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_idedit.Lists["x_TIPO_INFORME"].Options = <?php echo (is_array($view_id->TIPO_INFORME->EditValue)) ? ew_ArrayToJson($view_id->TIPO_INFORME->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_id_TIPO_INFORME">
<span<?php echo $view_id->TIPO_INFORME->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->TIPO_INFORME->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_TIPO_INFORME" name="x_TIPO_INFORME" id="x_TIPO_INFORME" value="<?php echo ew_HtmlEncode($view_id->TIPO_INFORME->FormValue) ?>">
<?php } ?>
<?php echo $view_id->TIPO_INFORME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->FECHA_REPORT->Visible) { // FECHA_REPORT ?>
	<div id="r_FECHA_REPORT" class="form-group">
		<label id="elh_view_id_FECHA_REPORT" for="x_FECHA_REPORT" class="col-sm-2 control-label ewLabel"><?php echo $view_id->FECHA_REPORT->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->FECHA_REPORT->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_FECHA_REPORT">
<input type="text" data-field="x_FECHA_REPORT" name="x_FECHA_REPORT" id="x_FECHA_REPORT" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($view_id->FECHA_REPORT->PlaceHolder) ?>" value="<?php echo $view_id->FECHA_REPORT->EditValue ?>"<?php echo $view_id->FECHA_REPORT->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_FECHA_REPORT">
<span<?php echo $view_id->FECHA_REPORT->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->FECHA_REPORT->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_FECHA_REPORT" name="x_FECHA_REPORT" id="x_FECHA_REPORT" value="<?php echo ew_HtmlEncode($view_id->FECHA_REPORT->FormValue) ?>">
<?php } ?>
<?php echo $view_id->FECHA_REPORT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->DIA->Visible) { // DIA ?>
	<div id="r_DIA" class="form-group">
		<label id="elh_view_id_DIA" for="x_DIA" class="col-sm-2 control-label ewLabel"><?php echo $view_id->DIA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->DIA->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_DIA">
<input type="text" data-field="x_DIA" name="x_DIA" id="x_DIA" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_id->DIA->PlaceHolder) ?>" value="<?php echo $view_id->DIA->EditValue ?>"<?php echo $view_id->DIA->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_DIA">
<span<?php echo $view_id->DIA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->DIA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_DIA" name="x_DIA" id="x_DIA" value="<?php echo ew_HtmlEncode($view_id->DIA->FormValue) ?>">
<?php } ?>
<?php echo $view_id->DIA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->MES->Visible) { // MES ?>
	<div id="r_MES" class="form-group">
		<label id="elh_view_id_MES" for="x_MES" class="col-sm-2 control-label ewLabel"><?php echo $view_id->MES->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->MES->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_MES">
<input type="text" data-field="x_MES" name="x_MES" id="x_MES" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_id->MES->PlaceHolder) ?>" value="<?php echo $view_id->MES->EditValue ?>"<?php echo $view_id->MES->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_MES">
<span<?php echo $view_id->MES->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->MES->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_MES" name="x_MES" id="x_MES" value="<?php echo ew_HtmlEncode($view_id->MES->FormValue) ?>">
<?php } ?>
<?php echo $view_id->MES->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Departamento->Visible) { // Departamento ?>
	<div id="r_Departamento" class="form-group">
		<label id="elh_view_id_Departamento" for="x_Departamento" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Departamento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Departamento->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Departamento">
<input type="text" data-field="x_Departamento" name="x_Departamento" id="x_Departamento" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($view_id->Departamento->PlaceHolder) ?>" value="<?php echo $view_id->Departamento->EditValue ?>"<?php echo $view_id->Departamento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Departamento">
<span<?php echo $view_id->Departamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Departamento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Departamento" name="x_Departamento" id="x_Departamento" value="<?php echo ew_HtmlEncode($view_id->Departamento->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Muncipio->Visible) { // Muncipio ?>
	<div id="r_Muncipio" class="form-group">
		<label id="elh_view_id_Muncipio" for="x_Muncipio" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Muncipio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Muncipio->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Muncipio">
<textarea data-field="x_Muncipio" name="x_Muncipio" id="x_Muncipio" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($view_id->Muncipio->PlaceHolder) ?>"<?php echo $view_id->Muncipio->EditAttributes() ?>><?php echo $view_id->Muncipio->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_id_Muncipio">
<span<?php echo $view_id->Muncipio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Muncipio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Muncipio" name="x_Muncipio" id="x_Muncipio" value="<?php echo ew_HtmlEncode($view_id->Muncipio->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Muncipio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->TEMA->Visible) { // TEMA ?>
	<div id="r_TEMA" class="form-group">
		<label id="elh_view_id_TEMA" for="x_TEMA" class="col-sm-2 control-label ewLabel"><?php echo $view_id->TEMA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->TEMA->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_TEMA">
<select data-field="x_TEMA" id="x_TEMA" name="x_TEMA"<?php echo $view_id->TEMA->EditAttributes() ?>>
<?php
if (is_array($view_id->TEMA->EditValue)) {
	$arwrk = $view_id->TEMA->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->TEMA->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_idedit.Lists["x_TEMA"].Options = <?php echo (is_array($view_id->TEMA->EditValue)) ? ew_ArrayToJson($view_id->TEMA->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_id_TEMA">
<span<?php echo $view_id->TEMA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->TEMA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_TEMA" name="x_TEMA" id="x_TEMA" value="<?php echo ew_HtmlEncode($view_id->TEMA->FormValue) ?>">
<?php } ?>
<?php echo $view_id->TEMA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Otro_Tema->Visible) { // Otro_Tema ?>
	<div id="r_Otro_Tema" class="form-group">
		<label id="elh_view_id_Otro_Tema" for="x_Otro_Tema" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Otro_Tema->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Otro_Tema->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Otro_Tema">
<input type="text" data-field="x_Otro_Tema" name="x_Otro_Tema" id="x_Otro_Tema" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_id->Otro_Tema->PlaceHolder) ?>" value="<?php echo $view_id->Otro_Tema->EditValue ?>"<?php echo $view_id->Otro_Tema->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Otro_Tema">
<span<?php echo $view_id->Otro_Tema->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Otro_Tema->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Otro_Tema" name="x_Otro_Tema" id="x_Otro_Tema" value="<?php echo ew_HtmlEncode($view_id->Otro_Tema->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Otro_Tema->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->OBSERVACION->Visible) { // OBSERVACION ?>
	<div id="r_OBSERVACION" class="form-group">
		<label id="elh_view_id_OBSERVACION" for="x_OBSERVACION" class="col-sm-2 control-label ewLabel"><?php echo $view_id->OBSERVACION->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->OBSERVACION->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_OBSERVACION">
<textarea data-field="x_OBSERVACION" name="x_OBSERVACION" id="x_OBSERVACION" cols="120" rows="4" placeholder="<?php echo ew_HtmlEncode($view_id->OBSERVACION->PlaceHolder) ?>"<?php echo $view_id->OBSERVACION->EditAttributes() ?>><?php echo $view_id->OBSERVACION->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el_view_id_OBSERVACION">
<span<?php echo $view_id->OBSERVACION->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->OBSERVACION->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_OBSERVACION" name="x_OBSERVACION" id="x_OBSERVACION" value="<?php echo ew_HtmlEncode($view_id->OBSERVACION->FormValue) ?>">
<?php } ?>
<?php echo $view_id->OBSERVACION->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->FUERZA->Visible) { // FUERZA ?>
	<div id="r_FUERZA" class="form-group">
		<label id="elh_view_id_FUERZA" for="x_FUERZA" class="col-sm-2 control-label ewLabel"><?php echo $view_id->FUERZA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->FUERZA->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_FUERZA">
<select data-field="x_FUERZA" id="x_FUERZA" name="x_FUERZA"<?php echo $view_id->FUERZA->EditAttributes() ?>>
<?php
if (is_array($view_id->FUERZA->EditValue)) {
	$arwrk = $view_id->FUERZA->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->FUERZA->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_idedit.Lists["x_FUERZA"].Options = <?php echo (is_array($view_id->FUERZA->EditValue)) ? ew_ArrayToJson($view_id->FUERZA->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_id_FUERZA">
<span<?php echo $view_id->FUERZA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->FUERZA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_FUERZA" name="x_FUERZA" id="x_FUERZA" value="<?php echo ew_HtmlEncode($view_id->FUERZA->FormValue) ?>">
<?php } ?>
<?php echo $view_id->FUERZA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->NOM_VDA->Visible) { // NOM_VDA ?>
	<div id="r_NOM_VDA" class="form-group">
		<label id="elh_view_id_NOM_VDA" for="x_NOM_VDA" class="col-sm-2 control-label ewLabel"><?php echo $view_id->NOM_VDA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->NOM_VDA->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_NOM_VDA">
<input type="text" data-field="x_NOM_VDA" name="x_NOM_VDA" id="x_NOM_VDA" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_id->NOM_VDA->PlaceHolder) ?>" value="<?php echo $view_id->NOM_VDA->EditValue ?>"<?php echo $view_id->NOM_VDA->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_NOM_VDA">
<span<?php echo $view_id->NOM_VDA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->NOM_VDA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NOM_VDA" name="x_NOM_VDA" id="x_NOM_VDA" value="<?php echo ew_HtmlEncode($view_id->NOM_VDA->FormValue) ?>">
<?php } ?>
<?php echo $view_id->NOM_VDA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Ha_Coca->Visible) { // Ha_Coca ?>
	<div id="r_Ha_Coca" class="form-group">
		<label id="elh_view_id_Ha_Coca" for="x_Ha_Coca" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Ha_Coca->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Ha_Coca->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Ha_Coca">
<input type="text" data-field="x_Ha_Coca" name="x_Ha_Coca" id="x_Ha_Coca" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Ha_Coca->PlaceHolder) ?>" value="<?php echo $view_id->Ha_Coca->EditValue ?>"<?php echo $view_id->Ha_Coca->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Ha_Coca">
<span<?php echo $view_id->Ha_Coca->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Ha_Coca->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Ha_Coca" name="x_Ha_Coca" id="x_Ha_Coca" value="<?php echo ew_HtmlEncode($view_id->Ha_Coca->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Ha_Coca->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Ha_Amapola->Visible) { // Ha_Amapola ?>
	<div id="r_Ha_Amapola" class="form-group">
		<label id="elh_view_id_Ha_Amapola" for="x_Ha_Amapola" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Ha_Amapola->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Ha_Amapola->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Ha_Amapola">
<input type="text" data-field="x_Ha_Amapola" name="x_Ha_Amapola" id="x_Ha_Amapola" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Ha_Amapola->PlaceHolder) ?>" value="<?php echo $view_id->Ha_Amapola->EditValue ?>"<?php echo $view_id->Ha_Amapola->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Ha_Amapola">
<span<?php echo $view_id->Ha_Amapola->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Ha_Amapola->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Ha_Amapola" name="x_Ha_Amapola" id="x_Ha_Amapola" value="<?php echo ew_HtmlEncode($view_id->Ha_Amapola->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Ha_Amapola->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Ha_Marihuana->Visible) { // Ha_Marihuana ?>
	<div id="r_Ha_Marihuana" class="form-group">
		<label id="elh_view_id_Ha_Marihuana" for="x_Ha_Marihuana" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Ha_Marihuana->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Ha_Marihuana->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Ha_Marihuana">
<input type="text" data-field="x_Ha_Marihuana" name="x_Ha_Marihuana" id="x_Ha_Marihuana" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Ha_Marihuana->PlaceHolder) ?>" value="<?php echo $view_id->Ha_Marihuana->EditValue ?>"<?php echo $view_id->Ha_Marihuana->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Ha_Marihuana">
<span<?php echo $view_id->Ha_Marihuana->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Ha_Marihuana->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Ha_Marihuana" name="x_Ha_Marihuana" id="x_Ha_Marihuana" value="<?php echo ew_HtmlEncode($view_id->Ha_Marihuana->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Ha_Marihuana->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->T_erradi->Visible) { // T_erradi ?>
	<div id="r_T_erradi" class="form-group">
		<label id="elh_view_id_T_erradi" for="x_T_erradi" class="col-sm-2 control-label ewLabel"><?php echo $view_id->T_erradi->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->T_erradi->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_T_erradi">
<input type="text" data-field="x_T_erradi" name="x_T_erradi" id="x_T_erradi" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->T_erradi->PlaceHolder) ?>" value="<?php echo $view_id->T_erradi->EditValue ?>"<?php echo $view_id->T_erradi->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_T_erradi">
<span<?php echo $view_id->T_erradi->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->T_erradi->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_T_erradi" name="x_T_erradi" id="x_T_erradi" value="<?php echo ew_HtmlEncode($view_id->T_erradi->FormValue) ?>">
<?php } ?>
<?php echo $view_id->T_erradi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->GRA_LAT_Sector->Visible) { // GRA_LAT_Sector ?>
	<div id="r_GRA_LAT_Sector" class="form-group">
		<label id="elh_view_id_GRA_LAT_Sector" for="x_GRA_LAT_Sector" class="col-sm-2 control-label ewLabel"><?php echo $view_id->GRA_LAT_Sector->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->GRA_LAT_Sector->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_GRA_LAT_Sector">
<input type="text" data-field="x_GRA_LAT_Sector" name="x_GRA_LAT_Sector" id="x_GRA_LAT_Sector" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->GRA_LAT_Sector->PlaceHolder) ?>" value="<?php echo $view_id->GRA_LAT_Sector->EditValue ?>"<?php echo $view_id->GRA_LAT_Sector->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_GRA_LAT_Sector">
<span<?php echo $view_id->GRA_LAT_Sector->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->GRA_LAT_Sector->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_GRA_LAT_Sector" name="x_GRA_LAT_Sector" id="x_GRA_LAT_Sector" value="<?php echo ew_HtmlEncode($view_id->GRA_LAT_Sector->FormValue) ?>">
<?php } ?>
<?php echo $view_id->GRA_LAT_Sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->MIN_LAT_Sector->Visible) { // MIN_LAT_Sector ?>
	<div id="r_MIN_LAT_Sector" class="form-group">
		<label id="elh_view_id_MIN_LAT_Sector" for="x_MIN_LAT_Sector" class="col-sm-2 control-label ewLabel"><?php echo $view_id->MIN_LAT_Sector->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->MIN_LAT_Sector->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_MIN_LAT_Sector">
<input type="text" data-field="x_MIN_LAT_Sector" name="x_MIN_LAT_Sector" id="x_MIN_LAT_Sector" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->MIN_LAT_Sector->PlaceHolder) ?>" value="<?php echo $view_id->MIN_LAT_Sector->EditValue ?>"<?php echo $view_id->MIN_LAT_Sector->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_MIN_LAT_Sector">
<span<?php echo $view_id->MIN_LAT_Sector->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->MIN_LAT_Sector->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_MIN_LAT_Sector" name="x_MIN_LAT_Sector" id="x_MIN_LAT_Sector" value="<?php echo ew_HtmlEncode($view_id->MIN_LAT_Sector->FormValue) ?>">
<?php } ?>
<?php echo $view_id->MIN_LAT_Sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->SEG_LAT_Sector->Visible) { // SEG_LAT_Sector ?>
	<div id="r_SEG_LAT_Sector" class="form-group">
		<label id="elh_view_id_SEG_LAT_Sector" for="x_SEG_LAT_Sector" class="col-sm-2 control-label ewLabel"><?php echo $view_id->SEG_LAT_Sector->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->SEG_LAT_Sector->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_SEG_LAT_Sector">
<input type="text" data-field="x_SEG_LAT_Sector" name="x_SEG_LAT_Sector" id="x_SEG_LAT_Sector" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->SEG_LAT_Sector->PlaceHolder) ?>" value="<?php echo $view_id->SEG_LAT_Sector->EditValue ?>"<?php echo $view_id->SEG_LAT_Sector->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_SEG_LAT_Sector">
<span<?php echo $view_id->SEG_LAT_Sector->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->SEG_LAT_Sector->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_SEG_LAT_Sector" name="x_SEG_LAT_Sector" id="x_SEG_LAT_Sector" value="<?php echo ew_HtmlEncode($view_id->SEG_LAT_Sector->FormValue) ?>">
<?php } ?>
<?php echo $view_id->SEG_LAT_Sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->GRA_LONG_Sector->Visible) { // GRA_LONG_Sector ?>
	<div id="r_GRA_LONG_Sector" class="form-group">
		<label id="elh_view_id_GRA_LONG_Sector" for="x_GRA_LONG_Sector" class="col-sm-2 control-label ewLabel"><?php echo $view_id->GRA_LONG_Sector->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->GRA_LONG_Sector->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_GRA_LONG_Sector">
<input type="text" data-field="x_GRA_LONG_Sector" name="x_GRA_LONG_Sector" id="x_GRA_LONG_Sector" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->GRA_LONG_Sector->PlaceHolder) ?>" value="<?php echo $view_id->GRA_LONG_Sector->EditValue ?>"<?php echo $view_id->GRA_LONG_Sector->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_GRA_LONG_Sector">
<span<?php echo $view_id->GRA_LONG_Sector->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->GRA_LONG_Sector->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_GRA_LONG_Sector" name="x_GRA_LONG_Sector" id="x_GRA_LONG_Sector" value="<?php echo ew_HtmlEncode($view_id->GRA_LONG_Sector->FormValue) ?>">
<?php } ?>
<?php echo $view_id->GRA_LONG_Sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->MIN_LONG_Sector->Visible) { // MIN_LONG_Sector ?>
	<div id="r_MIN_LONG_Sector" class="form-group">
		<label id="elh_view_id_MIN_LONG_Sector" for="x_MIN_LONG_Sector" class="col-sm-2 control-label ewLabel"><?php echo $view_id->MIN_LONG_Sector->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->MIN_LONG_Sector->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_MIN_LONG_Sector">
<input type="text" data-field="x_MIN_LONG_Sector" name="x_MIN_LONG_Sector" id="x_MIN_LONG_Sector" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->MIN_LONG_Sector->PlaceHolder) ?>" value="<?php echo $view_id->MIN_LONG_Sector->EditValue ?>"<?php echo $view_id->MIN_LONG_Sector->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_MIN_LONG_Sector">
<span<?php echo $view_id->MIN_LONG_Sector->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->MIN_LONG_Sector->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_MIN_LONG_Sector" name="x_MIN_LONG_Sector" id="x_MIN_LONG_Sector" value="<?php echo ew_HtmlEncode($view_id->MIN_LONG_Sector->FormValue) ?>">
<?php } ?>
<?php echo $view_id->MIN_LONG_Sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->SEG_LONG_Sector->Visible) { // SEG_LONG_Sector ?>
	<div id="r_SEG_LONG_Sector" class="form-group">
		<label id="elh_view_id_SEG_LONG_Sector" for="x_SEG_LONG_Sector" class="col-sm-2 control-label ewLabel"><?php echo $view_id->SEG_LONG_Sector->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->SEG_LONG_Sector->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_SEG_LONG_Sector">
<input type="text" data-field="x_SEG_LONG_Sector" name="x_SEG_LONG_Sector" id="x_SEG_LONG_Sector" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->SEG_LONG_Sector->PlaceHolder) ?>" value="<?php echo $view_id->SEG_LONG_Sector->EditValue ?>"<?php echo $view_id->SEG_LONG_Sector->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_SEG_LONG_Sector">
<span<?php echo $view_id->SEG_LONG_Sector->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->SEG_LONG_Sector->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_SEG_LONG_Sector" name="x_SEG_LONG_Sector" id="x_SEG_LONG_Sector" value="<?php echo ew_HtmlEncode($view_id->SEG_LONG_Sector->FormValue) ?>">
<?php } ?>
<?php echo $view_id->SEG_LONG_Sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Ini_Jorna->Visible) { // Ini_Jorna ?>
	<div id="r_Ini_Jorna" class="form-group">
		<label id="elh_view_id_Ini_Jorna" for="x_Ini_Jorna" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Ini_Jorna->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Ini_Jorna->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Ini_Jorna">
<input type="text" data-field="x_Ini_Jorna" name="x_Ini_Jorna" id="x_Ini_Jorna" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($view_id->Ini_Jorna->PlaceHolder) ?>" value="<?php echo $view_id->Ini_Jorna->EditValue ?>"<?php echo $view_id->Ini_Jorna->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Ini_Jorna">
<span<?php echo $view_id->Ini_Jorna->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Ini_Jorna->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Ini_Jorna" name="x_Ini_Jorna" id="x_Ini_Jorna" value="<?php echo ew_HtmlEncode($view_id->Ini_Jorna->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Ini_Jorna->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Fin_Jorna->Visible) { // Fin_Jorna ?>
	<div id="r_Fin_Jorna" class="form-group">
		<label id="elh_view_id_Fin_Jorna" for="x_Fin_Jorna" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Fin_Jorna->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Fin_Jorna->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Fin_Jorna">
<input type="text" data-field="x_Fin_Jorna" name="x_Fin_Jorna" id="x_Fin_Jorna" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($view_id->Fin_Jorna->PlaceHolder) ?>" value="<?php echo $view_id->Fin_Jorna->EditValue ?>"<?php echo $view_id->Fin_Jorna->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Fin_Jorna">
<span<?php echo $view_id->Fin_Jorna->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Fin_Jorna->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Fin_Jorna" name="x_Fin_Jorna" id="x_Fin_Jorna" value="<?php echo ew_HtmlEncode($view_id->Fin_Jorna->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Fin_Jorna->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Situ_Especial->Visible) { // Situ_Especial ?>
	<div id="r_Situ_Especial" class="form-group">
		<label id="elh_view_id_Situ_Especial" for="x_Situ_Especial" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Situ_Especial->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Situ_Especial->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Situ_Especial">
<select data-field="x_Situ_Especial" id="x_Situ_Especial" name="x_Situ_Especial"<?php echo $view_id->Situ_Especial->EditAttributes() ?>>
<?php
if (is_array($view_id->Situ_Especial->EditValue)) {
	$arwrk = $view_id->Situ_Especial->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->Situ_Especial->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<span id="el_view_id_Situ_Especial">
<span<?php echo $view_id->Situ_Especial->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Situ_Especial->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Situ_Especial" name="x_Situ_Especial" id="x_Situ_Especial" value="<?php echo ew_HtmlEncode($view_id->Situ_Especial->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Situ_Especial->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Adm_GME->Visible) { // Adm_GME ?>
	<div id="r_Adm_GME" class="form-group">
		<label id="elh_view_id_Adm_GME" for="x_Adm_GME" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Adm_GME->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Adm_GME->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Adm_GME">
<input type="text" data-field="x_Adm_GME" name="x_Adm_GME" id="x_Adm_GME" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Adm_GME->PlaceHolder) ?>" value="<?php echo $view_id->Adm_GME->EditValue ?>"<?php echo $view_id->Adm_GME->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Adm_GME">
<span<?php echo $view_id->Adm_GME->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Adm_GME->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Adm_GME" name="x_Adm_GME" id="x_Adm_GME" value="<?php echo ew_HtmlEncode($view_id->Adm_GME->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Adm_GME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Abastecimiento->Visible) { // 1_Abastecimiento ?>
	<div id="r__1_Abastecimiento" class="form-group">
		<label id="elh_view_id__1_Abastecimiento" for="x__1_Abastecimiento" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Abastecimiento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Abastecimiento->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Abastecimiento">
<input type="text" data-field="x__1_Abastecimiento" name="x__1_Abastecimiento" id="x__1_Abastecimiento" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Abastecimiento->PlaceHolder) ?>" value="<?php echo $view_id->_1_Abastecimiento->EditValue ?>"<?php echo $view_id->_1_Abastecimiento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Abastecimiento">
<span<?php echo $view_id->_1_Abastecimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Abastecimiento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Abastecimiento" name="x__1_Abastecimiento" id="x__1_Abastecimiento" value="<?php echo ew_HtmlEncode($view_id->_1_Abastecimiento->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Abastecimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Acompanamiento_firma_GME->Visible) { // 1_Acompanamiento_firma_GME ?>
	<div id="r__1_Acompanamiento_firma_GME" class="form-group">
		<label id="elh_view_id__1_Acompanamiento_firma_GME" for="x__1_Acompanamiento_firma_GME" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Acompanamiento_firma_GME->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Acompanamiento_firma_GME->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Acompanamiento_firma_GME">
<input type="text" data-field="x__1_Acompanamiento_firma_GME" name="x__1_Acompanamiento_firma_GME" id="x__1_Acompanamiento_firma_GME" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Acompanamiento_firma_GME->PlaceHolder) ?>" value="<?php echo $view_id->_1_Acompanamiento_firma_GME->EditValue ?>"<?php echo $view_id->_1_Acompanamiento_firma_GME->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Acompanamiento_firma_GME">
<span<?php echo $view_id->_1_Acompanamiento_firma_GME->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Acompanamiento_firma_GME->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Acompanamiento_firma_GME" name="x__1_Acompanamiento_firma_GME" id="x__1_Acompanamiento_firma_GME" value="<?php echo ew_HtmlEncode($view_id->_1_Acompanamiento_firma_GME->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Acompanamiento_firma_GME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Apoyo_zonal_sin_punto_asignado->Visible) { // 1_Apoyo_zonal_sin_punto_asignado ?>
	<div id="r__1_Apoyo_zonal_sin_punto_asignado" class="form-group">
		<label id="elh_view_id__1_Apoyo_zonal_sin_punto_asignado" for="x__1_Apoyo_zonal_sin_punto_asignado" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Apoyo_zonal_sin_punto_asignado">
<input type="text" data-field="x__1_Apoyo_zonal_sin_punto_asignado" name="x__1_Apoyo_zonal_sin_punto_asignado" id="x__1_Apoyo_zonal_sin_punto_asignado" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Apoyo_zonal_sin_punto_asignado->PlaceHolder) ?>" value="<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->EditValue ?>"<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Apoyo_zonal_sin_punto_asignado">
<span<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Apoyo_zonal_sin_punto_asignado" name="x__1_Apoyo_zonal_sin_punto_asignado" id="x__1_Apoyo_zonal_sin_punto_asignado" value="<?php echo ew_HtmlEncode($view_id->_1_Apoyo_zonal_sin_punto_asignado->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Descanso_en_dia_habil->Visible) { // 1_Descanso_en_dia_habil ?>
	<div id="r__1_Descanso_en_dia_habil" class="form-group">
		<label id="elh_view_id__1_Descanso_en_dia_habil" for="x__1_Descanso_en_dia_habil" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Descanso_en_dia_habil->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Descanso_en_dia_habil->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Descanso_en_dia_habil">
<input type="text" data-field="x__1_Descanso_en_dia_habil" name="x__1_Descanso_en_dia_habil" id="x__1_Descanso_en_dia_habil" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Descanso_en_dia_habil->PlaceHolder) ?>" value="<?php echo $view_id->_1_Descanso_en_dia_habil->EditValue ?>"<?php echo $view_id->_1_Descanso_en_dia_habil->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Descanso_en_dia_habil">
<span<?php echo $view_id->_1_Descanso_en_dia_habil->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Descanso_en_dia_habil->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Descanso_en_dia_habil" name="x__1_Descanso_en_dia_habil" id="x__1_Descanso_en_dia_habil" value="<?php echo ew_HtmlEncode($view_id->_1_Descanso_en_dia_habil->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Descanso_en_dia_habil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Descanso_festivo_dominical->Visible) { // 1_Descanso_festivo_dominical ?>
	<div id="r__1_Descanso_festivo_dominical" class="form-group">
		<label id="elh_view_id__1_Descanso_festivo_dominical" for="x__1_Descanso_festivo_dominical" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Descanso_festivo_dominical->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Descanso_festivo_dominical->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Descanso_festivo_dominical">
<input type="text" data-field="x__1_Descanso_festivo_dominical" name="x__1_Descanso_festivo_dominical" id="x__1_Descanso_festivo_dominical" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Descanso_festivo_dominical->PlaceHolder) ?>" value="<?php echo $view_id->_1_Descanso_festivo_dominical->EditValue ?>"<?php echo $view_id->_1_Descanso_festivo_dominical->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Descanso_festivo_dominical">
<span<?php echo $view_id->_1_Descanso_festivo_dominical->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Descanso_festivo_dominical->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Descanso_festivo_dominical" name="x__1_Descanso_festivo_dominical" id="x__1_Descanso_festivo_dominical" value="<?php echo ew_HtmlEncode($view_id->_1_Descanso_festivo_dominical->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Descanso_festivo_dominical->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Dia_compensatorio->Visible) { // 1_Dia_compensatorio ?>
	<div id="r__1_Dia_compensatorio" class="form-group">
		<label id="elh_view_id__1_Dia_compensatorio" for="x__1_Dia_compensatorio" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Dia_compensatorio->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Dia_compensatorio->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Dia_compensatorio">
<input type="text" data-field="x__1_Dia_compensatorio" name="x__1_Dia_compensatorio" id="x__1_Dia_compensatorio" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Dia_compensatorio->PlaceHolder) ?>" value="<?php echo $view_id->_1_Dia_compensatorio->EditValue ?>"<?php echo $view_id->_1_Dia_compensatorio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Dia_compensatorio">
<span<?php echo $view_id->_1_Dia_compensatorio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Dia_compensatorio->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Dia_compensatorio" name="x__1_Dia_compensatorio" id="x__1_Dia_compensatorio" value="<?php echo ew_HtmlEncode($view_id->_1_Dia_compensatorio->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Dia_compensatorio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Erradicacion_en_dia_festivo->Visible) { // 1_Erradicacion_en_dia_festivo ?>
	<div id="r__1_Erradicacion_en_dia_festivo" class="form-group">
		<label id="elh_view_id__1_Erradicacion_en_dia_festivo" for="x__1_Erradicacion_en_dia_festivo" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Erradicacion_en_dia_festivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Erradicacion_en_dia_festivo->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Erradicacion_en_dia_festivo">
<input type="text" data-field="x__1_Erradicacion_en_dia_festivo" name="x__1_Erradicacion_en_dia_festivo" id="x__1_Erradicacion_en_dia_festivo" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Erradicacion_en_dia_festivo->PlaceHolder) ?>" value="<?php echo $view_id->_1_Erradicacion_en_dia_festivo->EditValue ?>"<?php echo $view_id->_1_Erradicacion_en_dia_festivo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Erradicacion_en_dia_festivo">
<span<?php echo $view_id->_1_Erradicacion_en_dia_festivo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Erradicacion_en_dia_festivo->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Erradicacion_en_dia_festivo" name="x__1_Erradicacion_en_dia_festivo" id="x__1_Erradicacion_en_dia_festivo" value="<?php echo ew_HtmlEncode($view_id->_1_Erradicacion_en_dia_festivo->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Erradicacion_en_dia_festivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Espera_helicoptero_Helistar->Visible) { // 1_Espera_helicoptero_Helistar ?>
	<div id="r__1_Espera_helicoptero_Helistar" class="form-group">
		<label id="elh_view_id__1_Espera_helicoptero_Helistar" for="x__1_Espera_helicoptero_Helistar" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Espera_helicoptero_Helistar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Espera_helicoptero_Helistar->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Espera_helicoptero_Helistar">
<input type="text" data-field="x__1_Espera_helicoptero_Helistar" name="x__1_Espera_helicoptero_Helistar" id="x__1_Espera_helicoptero_Helistar" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Espera_helicoptero_Helistar->PlaceHolder) ?>" value="<?php echo $view_id->_1_Espera_helicoptero_Helistar->EditValue ?>"<?php echo $view_id->_1_Espera_helicoptero_Helistar->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Espera_helicoptero_Helistar">
<span<?php echo $view_id->_1_Espera_helicoptero_Helistar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Espera_helicoptero_Helistar->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Espera_helicoptero_Helistar" name="x__1_Espera_helicoptero_Helistar" id="x__1_Espera_helicoptero_Helistar" value="<?php echo ew_HtmlEncode($view_id->_1_Espera_helicoptero_Helistar->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Espera_helicoptero_Helistar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Extraccion->Visible) { // 1_Extraccion ?>
	<div id="r__1_Extraccion" class="form-group">
		<label id="elh_view_id__1_Extraccion" for="x__1_Extraccion" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Extraccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Extraccion->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Extraccion">
<input type="text" data-field="x__1_Extraccion" name="x__1_Extraccion" id="x__1_Extraccion" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Extraccion->PlaceHolder) ?>" value="<?php echo $view_id->_1_Extraccion->EditValue ?>"<?php echo $view_id->_1_Extraccion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Extraccion">
<span<?php echo $view_id->_1_Extraccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Extraccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Extraccion" name="x__1_Extraccion" id="x__1_Extraccion" value="<?php echo ew_HtmlEncode($view_id->_1_Extraccion->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Extraccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Firma_contrato_GME->Visible) { // 1_Firma_contrato_GME ?>
	<div id="r__1_Firma_contrato_GME" class="form-group">
		<label id="elh_view_id__1_Firma_contrato_GME" for="x__1_Firma_contrato_GME" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Firma_contrato_GME->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Firma_contrato_GME->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Firma_contrato_GME">
<input type="text" data-field="x__1_Firma_contrato_GME" name="x__1_Firma_contrato_GME" id="x__1_Firma_contrato_GME" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Firma_contrato_GME->PlaceHolder) ?>" value="<?php echo $view_id->_1_Firma_contrato_GME->EditValue ?>"<?php echo $view_id->_1_Firma_contrato_GME->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Firma_contrato_GME">
<span<?php echo $view_id->_1_Firma_contrato_GME->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Firma_contrato_GME->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Firma_contrato_GME" name="x__1_Firma_contrato_GME" id="x__1_Firma_contrato_GME" value="<?php echo ew_HtmlEncode($view_id->_1_Firma_contrato_GME->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Firma_contrato_GME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Induccion_Apoyo_Zonal->Visible) { // 1_Induccion_Apoyo_Zonal ?>
	<div id="r__1_Induccion_Apoyo_Zonal" class="form-group">
		<label id="elh_view_id__1_Induccion_Apoyo_Zonal" for="x__1_Induccion_Apoyo_Zonal" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Induccion_Apoyo_Zonal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Induccion_Apoyo_Zonal->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Induccion_Apoyo_Zonal">
<input type="text" data-field="x__1_Induccion_Apoyo_Zonal" name="x__1_Induccion_Apoyo_Zonal" id="x__1_Induccion_Apoyo_Zonal" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Induccion_Apoyo_Zonal->PlaceHolder) ?>" value="<?php echo $view_id->_1_Induccion_Apoyo_Zonal->EditValue ?>"<?php echo $view_id->_1_Induccion_Apoyo_Zonal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Induccion_Apoyo_Zonal">
<span<?php echo $view_id->_1_Induccion_Apoyo_Zonal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Induccion_Apoyo_Zonal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Induccion_Apoyo_Zonal" name="x__1_Induccion_Apoyo_Zonal" id="x__1_Induccion_Apoyo_Zonal" value="<?php echo ew_HtmlEncode($view_id->_1_Induccion_Apoyo_Zonal->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Induccion_Apoyo_Zonal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Insercion->Visible) { // 1_Insercion ?>
	<div id="r__1_Insercion" class="form-group">
		<label id="elh_view_id__1_Insercion" for="x__1_Insercion" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Insercion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Insercion->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Insercion">
<input type="text" data-field="x__1_Insercion" name="x__1_Insercion" id="x__1_Insercion" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Insercion->PlaceHolder) ?>" value="<?php echo $view_id->_1_Insercion->EditValue ?>"<?php echo $view_id->_1_Insercion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Insercion">
<span<?php echo $view_id->_1_Insercion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Insercion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Insercion" name="x__1_Insercion" id="x__1_Insercion" value="<?php echo ew_HtmlEncode($view_id->_1_Insercion->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Insercion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Visible) { // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase ?>
	<div id="r__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="form-group">
		<label id="elh_view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" for="x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase">
<input type="text" data-field="x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" name="x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" id="x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->PlaceHolder) ?>" value="<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue ?>"<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase">
<span<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" name="x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" id="x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" value="<?php echo ew_HtmlEncode($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Novedad_apoyo_zonal->Visible) { // 1_Novedad_apoyo_zonal ?>
	<div id="r__1_Novedad_apoyo_zonal" class="form-group">
		<label id="elh_view_id__1_Novedad_apoyo_zonal" for="x__1_Novedad_apoyo_zonal" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Novedad_apoyo_zonal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Novedad_apoyo_zonal->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Novedad_apoyo_zonal">
<input type="text" data-field="x__1_Novedad_apoyo_zonal" name="x__1_Novedad_apoyo_zonal" id="x__1_Novedad_apoyo_zonal" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Novedad_apoyo_zonal->PlaceHolder) ?>" value="<?php echo $view_id->_1_Novedad_apoyo_zonal->EditValue ?>"<?php echo $view_id->_1_Novedad_apoyo_zonal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Novedad_apoyo_zonal">
<span<?php echo $view_id->_1_Novedad_apoyo_zonal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Novedad_apoyo_zonal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Novedad_apoyo_zonal" name="x__1_Novedad_apoyo_zonal" id="x__1_Novedad_apoyo_zonal" value="<?php echo ew_HtmlEncode($view_id->_1_Novedad_apoyo_zonal->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Novedad_apoyo_zonal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Novedad_enfermero->Visible) { // 1_Novedad_enfermero ?>
	<div id="r__1_Novedad_enfermero" class="form-group">
		<label id="elh_view_id__1_Novedad_enfermero" for="x__1_Novedad_enfermero" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Novedad_enfermero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Novedad_enfermero->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Novedad_enfermero">
<input type="text" data-field="x__1_Novedad_enfermero" name="x__1_Novedad_enfermero" id="x__1_Novedad_enfermero" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Novedad_enfermero->PlaceHolder) ?>" value="<?php echo $view_id->_1_Novedad_enfermero->EditValue ?>"<?php echo $view_id->_1_Novedad_enfermero->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Novedad_enfermero">
<span<?php echo $view_id->_1_Novedad_enfermero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Novedad_enfermero->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Novedad_enfermero" name="x__1_Novedad_enfermero" id="x__1_Novedad_enfermero" value="<?php echo ew_HtmlEncode($view_id->_1_Novedad_enfermero->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Novedad_enfermero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Punto_fuera_del_area_de_erradicacion->Visible) { // 1_Punto_fuera_del_area_de_erradicacion ?>
	<div id="r__1_Punto_fuera_del_area_de_erradicacion" class="form-group">
		<label id="elh_view_id__1_Punto_fuera_del_area_de_erradicacion" for="x__1_Punto_fuera_del_area_de_erradicacion" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Punto_fuera_del_area_de_erradicacion">
<input type="text" data-field="x__1_Punto_fuera_del_area_de_erradicacion" name="x__1_Punto_fuera_del_area_de_erradicacion" id="x__1_Punto_fuera_del_area_de_erradicacion" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Punto_fuera_del_area_de_erradicacion->PlaceHolder) ?>" value="<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->EditValue ?>"<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Punto_fuera_del_area_de_erradicacion">
<span<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Punto_fuera_del_area_de_erradicacion" name="x__1_Punto_fuera_del_area_de_erradicacion" id="x__1_Punto_fuera_del_area_de_erradicacion" value="<?php echo ew_HtmlEncode($view_id->_1_Punto_fuera_del_area_de_erradicacion->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Transporte_bus->Visible) { // 1_Transporte_bus ?>
	<div id="r__1_Transporte_bus" class="form-group">
		<label id="elh_view_id__1_Transporte_bus" for="x__1_Transporte_bus" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Transporte_bus->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Transporte_bus->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Transporte_bus">
<input type="text" data-field="x__1_Transporte_bus" name="x__1_Transporte_bus" id="x__1_Transporte_bus" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Transporte_bus->PlaceHolder) ?>" value="<?php echo $view_id->_1_Transporte_bus->EditValue ?>"<?php echo $view_id->_1_Transporte_bus->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Transporte_bus">
<span<?php echo $view_id->_1_Transporte_bus->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Transporte_bus->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Transporte_bus" name="x__1_Transporte_bus" id="x__1_Transporte_bus" value="<?php echo ew_HtmlEncode($view_id->_1_Transporte_bus->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Transporte_bus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Traslado_apoyo_zonal->Visible) { // 1_Traslado_apoyo_zonal ?>
	<div id="r__1_Traslado_apoyo_zonal" class="form-group">
		<label id="elh_view_id__1_Traslado_apoyo_zonal" for="x__1_Traslado_apoyo_zonal" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Traslado_apoyo_zonal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Traslado_apoyo_zonal->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Traslado_apoyo_zonal">
<input type="text" data-field="x__1_Traslado_apoyo_zonal" name="x__1_Traslado_apoyo_zonal" id="x__1_Traslado_apoyo_zonal" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Traslado_apoyo_zonal->PlaceHolder) ?>" value="<?php echo $view_id->_1_Traslado_apoyo_zonal->EditValue ?>"<?php echo $view_id->_1_Traslado_apoyo_zonal->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Traslado_apoyo_zonal">
<span<?php echo $view_id->_1_Traslado_apoyo_zonal->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Traslado_apoyo_zonal->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Traslado_apoyo_zonal" name="x__1_Traslado_apoyo_zonal" id="x__1_Traslado_apoyo_zonal" value="<?php echo ew_HtmlEncode($view_id->_1_Traslado_apoyo_zonal->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Traslado_apoyo_zonal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_1_Traslado_area_vivac->Visible) { // 1_Traslado_area_vivac ?>
	<div id="r__1_Traslado_area_vivac" class="form-group">
		<label id="elh_view_id__1_Traslado_area_vivac" for="x__1_Traslado_area_vivac" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_1_Traslado_area_vivac->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_1_Traslado_area_vivac->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__1_Traslado_area_vivac">
<input type="text" data-field="x__1_Traslado_area_vivac" name="x__1_Traslado_area_vivac" id="x__1_Traslado_area_vivac" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_1_Traslado_area_vivac->PlaceHolder) ?>" value="<?php echo $view_id->_1_Traslado_area_vivac->EditValue ?>"<?php echo $view_id->_1_Traslado_area_vivac->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__1_Traslado_area_vivac">
<span<?php echo $view_id->_1_Traslado_area_vivac->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_1_Traslado_area_vivac->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__1_Traslado_area_vivac" name="x__1_Traslado_area_vivac" id="x__1_Traslado_area_vivac" value="<?php echo ew_HtmlEncode($view_id->_1_Traslado_area_vivac->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_1_Traslado_area_vivac->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Adm_Fuerza->Visible) { // Adm_Fuerza ?>
	<div id="r_Adm_Fuerza" class="form-group">
		<label id="elh_view_id_Adm_Fuerza" for="x_Adm_Fuerza" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Adm_Fuerza->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Adm_Fuerza->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Adm_Fuerza">
<input type="text" data-field="x_Adm_Fuerza" name="x_Adm_Fuerza" id="x_Adm_Fuerza" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Adm_Fuerza->PlaceHolder) ?>" value="<?php echo $view_id->Adm_Fuerza->EditValue ?>"<?php echo $view_id->Adm_Fuerza->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Adm_Fuerza">
<span<?php echo $view_id->Adm_Fuerza->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Adm_Fuerza->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Adm_Fuerza" name="x_Adm_Fuerza" id="x_Adm_Fuerza" value="<?php echo ew_HtmlEncode($view_id->Adm_Fuerza->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Adm_Fuerza->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->Visible) { // 2_A_la_espera_definicion_nuevo_punto_FP ?>
	<div id="r__2_A_la_espera_definicion_nuevo_punto_FP" class="form-group">
		<label id="elh_view_id__2_A_la_espera_definicion_nuevo_punto_FP" for="x__2_A_la_espera_definicion_nuevo_punto_FP" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__2_A_la_espera_definicion_nuevo_punto_FP">
<input type="text" data-field="x__2_A_la_espera_definicion_nuevo_punto_FP" name="x__2_A_la_espera_definicion_nuevo_punto_FP" id="x__2_A_la_espera_definicion_nuevo_punto_FP" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->PlaceHolder) ?>" value="<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue ?>"<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__2_A_la_espera_definicion_nuevo_punto_FP">
<span<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_A_la_espera_definicion_nuevo_punto_FP" name="x__2_A_la_espera_definicion_nuevo_punto_FP" id="x__2_A_la_espera_definicion_nuevo_punto_FP" value="<?php echo ew_HtmlEncode($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_2_Espera_helicoptero_FP_de_seguridad->Visible) { // 2_Espera_helicoptero_FP_de_seguridad ?>
	<div id="r__2_Espera_helicoptero_FP_de_seguridad" class="form-group">
		<label id="elh_view_id__2_Espera_helicoptero_FP_de_seguridad" for="x__2_Espera_helicoptero_FP_de_seguridad" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__2_Espera_helicoptero_FP_de_seguridad">
<input type="text" data-field="x__2_Espera_helicoptero_FP_de_seguridad" name="x__2_Espera_helicoptero_FP_de_seguridad" id="x__2_Espera_helicoptero_FP_de_seguridad" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_2_Espera_helicoptero_FP_de_seguridad->PlaceHolder) ?>" value="<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->EditValue ?>"<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__2_Espera_helicoptero_FP_de_seguridad">
<span<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Espera_helicoptero_FP_de_seguridad" name="x__2_Espera_helicoptero_FP_de_seguridad" id="x__2_Espera_helicoptero_FP_de_seguridad" value="<?php echo ew_HtmlEncode($view_id->_2_Espera_helicoptero_FP_de_seguridad->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_2_Espera_helicoptero_FP_que_abastece->Visible) { // 2_Espera_helicoptero_FP_que_abastece ?>
	<div id="r__2_Espera_helicoptero_FP_que_abastece" class="form-group">
		<label id="elh_view_id__2_Espera_helicoptero_FP_que_abastece" for="x__2_Espera_helicoptero_FP_que_abastece" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__2_Espera_helicoptero_FP_que_abastece">
<input type="text" data-field="x__2_Espera_helicoptero_FP_que_abastece" name="x__2_Espera_helicoptero_FP_que_abastece" id="x__2_Espera_helicoptero_FP_que_abastece" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_2_Espera_helicoptero_FP_que_abastece->PlaceHolder) ?>" value="<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->EditValue ?>"<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__2_Espera_helicoptero_FP_que_abastece">
<span<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Espera_helicoptero_FP_que_abastece" name="x__2_Espera_helicoptero_FP_que_abastece" id="x__2_Espera_helicoptero_FP_que_abastece" value="<?php echo ew_HtmlEncode($view_id->_2_Espera_helicoptero_FP_que_abastece->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_2_Induccion_FP->Visible) { // 2_Induccion_FP ?>
	<div id="r__2_Induccion_FP" class="form-group">
		<label id="elh_view_id__2_Induccion_FP" for="x__2_Induccion_FP" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_2_Induccion_FP->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_2_Induccion_FP->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__2_Induccion_FP">
<input type="text" data-field="x__2_Induccion_FP" name="x__2_Induccion_FP" id="x__2_Induccion_FP" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_2_Induccion_FP->PlaceHolder) ?>" value="<?php echo $view_id->_2_Induccion_FP->EditValue ?>"<?php echo $view_id->_2_Induccion_FP->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__2_Induccion_FP">
<span<?php echo $view_id->_2_Induccion_FP->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_2_Induccion_FP->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Induccion_FP" name="x__2_Induccion_FP" id="x__2_Induccion_FP" value="<?php echo ew_HtmlEncode($view_id->_2_Induccion_FP->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_2_Induccion_FP->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->Visible) { // 2_Novedad_canino_o_del_grupo_de_deteccion ?>
	<div id="r__2_Novedad_canino_o_del_grupo_de_deteccion" class="form-group">
		<label id="elh_view_id__2_Novedad_canino_o_del_grupo_de_deteccion" for="x__2_Novedad_canino_o_del_grupo_de_deteccion" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__2_Novedad_canino_o_del_grupo_de_deteccion">
<input type="text" data-field="x__2_Novedad_canino_o_del_grupo_de_deteccion" name="x__2_Novedad_canino_o_del_grupo_de_deteccion" id="x__2_Novedad_canino_o_del_grupo_de_deteccion" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->PlaceHolder) ?>" value="<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue ?>"<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__2_Novedad_canino_o_del_grupo_de_deteccion">
<span<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Novedad_canino_o_del_grupo_de_deteccion" name="x__2_Novedad_canino_o_del_grupo_de_deteccion" id="x__2_Novedad_canino_o_del_grupo_de_deteccion" value="<?php echo ew_HtmlEncode($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_2_Problemas_fuerza_publica->Visible) { // 2_Problemas_fuerza_publica ?>
	<div id="r__2_Problemas_fuerza_publica" class="form-group">
		<label id="elh_view_id__2_Problemas_fuerza_publica" for="x__2_Problemas_fuerza_publica" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_2_Problemas_fuerza_publica->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_2_Problemas_fuerza_publica->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__2_Problemas_fuerza_publica">
<input type="text" data-field="x__2_Problemas_fuerza_publica" name="x__2_Problemas_fuerza_publica" id="x__2_Problemas_fuerza_publica" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_2_Problemas_fuerza_publica->PlaceHolder) ?>" value="<?php echo $view_id->_2_Problemas_fuerza_publica->EditValue ?>"<?php echo $view_id->_2_Problemas_fuerza_publica->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__2_Problemas_fuerza_publica">
<span<?php echo $view_id->_2_Problemas_fuerza_publica->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_2_Problemas_fuerza_publica->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Problemas_fuerza_publica" name="x__2_Problemas_fuerza_publica" id="x__2_Problemas_fuerza_publica" value="<?php echo ew_HtmlEncode($view_id->_2_Problemas_fuerza_publica->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_2_Problemas_fuerza_publica->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_2_Sin_seguridad->Visible) { // 2_Sin_seguridad ?>
	<div id="r__2_Sin_seguridad" class="form-group">
		<label id="elh_view_id__2_Sin_seguridad" for="x__2_Sin_seguridad" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_2_Sin_seguridad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_2_Sin_seguridad->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__2_Sin_seguridad">
<input type="text" data-field="x__2_Sin_seguridad" name="x__2_Sin_seguridad" id="x__2_Sin_seguridad" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_2_Sin_seguridad->PlaceHolder) ?>" value="<?php echo $view_id->_2_Sin_seguridad->EditValue ?>"<?php echo $view_id->_2_Sin_seguridad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__2_Sin_seguridad">
<span<?php echo $view_id->_2_Sin_seguridad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_2_Sin_seguridad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__2_Sin_seguridad" name="x__2_Sin_seguridad" id="x__2_Sin_seguridad" value="<?php echo ew_HtmlEncode($view_id->_2_Sin_seguridad->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_2_Sin_seguridad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Sit_Seguridad->Visible) { // Sit_Seguridad ?>
	<div id="r_Sit_Seguridad" class="form-group">
		<label id="elh_view_id_Sit_Seguridad" for="x_Sit_Seguridad" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Sit_Seguridad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Sit_Seguridad->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Sit_Seguridad">
<input type="text" data-field="x_Sit_Seguridad" name="x_Sit_Seguridad" id="x_Sit_Seguridad" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Sit_Seguridad->PlaceHolder) ?>" value="<?php echo $view_id->Sit_Seguridad->EditValue ?>"<?php echo $view_id->Sit_Seguridad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Sit_Seguridad">
<span<?php echo $view_id->Sit_Seguridad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Sit_Seguridad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Sit_Seguridad" name="x_Sit_Seguridad" id="x_Sit_Seguridad" value="<?php echo ew_HtmlEncode($view_id->Sit_Seguridad->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Sit_Seguridad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_AEI_controlado->Visible) { // 3_AEI_controlado ?>
	<div id="r__3_AEI_controlado" class="form-group">
		<label id="elh_view_id__3_AEI_controlado" for="x__3_AEI_controlado" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_AEI_controlado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_AEI_controlado->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_AEI_controlado">
<input type="text" data-field="x__3_AEI_controlado" name="x__3_AEI_controlado" id="x__3_AEI_controlado" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_AEI_controlado->PlaceHolder) ?>" value="<?php echo $view_id->_3_AEI_controlado->EditValue ?>"<?php echo $view_id->_3_AEI_controlado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_AEI_controlado">
<span<?php echo $view_id->_3_AEI_controlado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_AEI_controlado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_AEI_controlado" name="x__3_AEI_controlado" id="x__3_AEI_controlado" value="<?php echo ew_HtmlEncode($view_id->_3_AEI_controlado->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_AEI_controlado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_AEI_no_controlado->Visible) { // 3_AEI_no_controlado ?>
	<div id="r__3_AEI_no_controlado" class="form-group">
		<label id="elh_view_id__3_AEI_no_controlado" for="x__3_AEI_no_controlado" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_AEI_no_controlado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_AEI_no_controlado->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_AEI_no_controlado">
<input type="text" data-field="x__3_AEI_no_controlado" name="x__3_AEI_no_controlado" id="x__3_AEI_no_controlado" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_AEI_no_controlado->PlaceHolder) ?>" value="<?php echo $view_id->_3_AEI_no_controlado->EditValue ?>"<?php echo $view_id->_3_AEI_no_controlado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_AEI_no_controlado">
<span<?php echo $view_id->_3_AEI_no_controlado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_AEI_no_controlado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_AEI_no_controlado" name="x__3_AEI_no_controlado" id="x__3_AEI_no_controlado" value="<?php echo ew_HtmlEncode($view_id->_3_AEI_no_controlado->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_AEI_no_controlado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_Bloqueo_parcial_de_la_comunidad->Visible) { // 3_Bloqueo_parcial_de_la_comunidad ?>
	<div id="r__3_Bloqueo_parcial_de_la_comunidad" class="form-group">
		<label id="elh_view_id__3_Bloqueo_parcial_de_la_comunidad" for="x__3_Bloqueo_parcial_de_la_comunidad" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_Bloqueo_parcial_de_la_comunidad">
<input type="text" data-field="x__3_Bloqueo_parcial_de_la_comunidad" name="x__3_Bloqueo_parcial_de_la_comunidad" id="x__3_Bloqueo_parcial_de_la_comunidad" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_Bloqueo_parcial_de_la_comunidad->PlaceHolder) ?>" value="<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->EditValue ?>"<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_Bloqueo_parcial_de_la_comunidad">
<span<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Bloqueo_parcial_de_la_comunidad" name="x__3_Bloqueo_parcial_de_la_comunidad" id="x__3_Bloqueo_parcial_de_la_comunidad" value="<?php echo ew_HtmlEncode($view_id->_3_Bloqueo_parcial_de_la_comunidad->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_Bloqueo_total_de_la_comunidad->Visible) { // 3_Bloqueo_total_de_la_comunidad ?>
	<div id="r__3_Bloqueo_total_de_la_comunidad" class="form-group">
		<label id="elh_view_id__3_Bloqueo_total_de_la_comunidad" for="x__3_Bloqueo_total_de_la_comunidad" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_Bloqueo_total_de_la_comunidad">
<input type="text" data-field="x__3_Bloqueo_total_de_la_comunidad" name="x__3_Bloqueo_total_de_la_comunidad" id="x__3_Bloqueo_total_de_la_comunidad" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_Bloqueo_total_de_la_comunidad->PlaceHolder) ?>" value="<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->EditValue ?>"<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_Bloqueo_total_de_la_comunidad">
<span<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Bloqueo_total_de_la_comunidad" name="x__3_Bloqueo_total_de_la_comunidad" id="x__3_Bloqueo_total_de_la_comunidad" value="<?php echo ew_HtmlEncode($view_id->_3_Bloqueo_total_de_la_comunidad->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_Combate->Visible) { // 3_Combate ?>
	<div id="r__3_Combate" class="form-group">
		<label id="elh_view_id__3_Combate" for="x__3_Combate" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_Combate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_Combate->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_Combate">
<input type="text" data-field="x__3_Combate" name="x__3_Combate" id="x__3_Combate" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_Combate->PlaceHolder) ?>" value="<?php echo $view_id->_3_Combate->EditValue ?>"<?php echo $view_id->_3_Combate->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_Combate">
<span<?php echo $view_id->_3_Combate->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_Combate->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Combate" name="x__3_Combate" id="x__3_Combate" value="<?php echo ew_HtmlEncode($view_id->_3_Combate->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_Combate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_Hostigamiento->Visible) { // 3_Hostigamiento ?>
	<div id="r__3_Hostigamiento" class="form-group">
		<label id="elh_view_id__3_Hostigamiento" for="x__3_Hostigamiento" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_Hostigamiento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_Hostigamiento->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_Hostigamiento">
<input type="text" data-field="x__3_Hostigamiento" name="x__3_Hostigamiento" id="x__3_Hostigamiento" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_Hostigamiento->PlaceHolder) ?>" value="<?php echo $view_id->_3_Hostigamiento->EditValue ?>"<?php echo $view_id->_3_Hostigamiento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_Hostigamiento">
<span<?php echo $view_id->_3_Hostigamiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_Hostigamiento->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Hostigamiento" name="x__3_Hostigamiento" id="x__3_Hostigamiento" value="<?php echo ew_HtmlEncode($view_id->_3_Hostigamiento->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_Hostigamiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_MAP_Controlada->Visible) { // 3_MAP_Controlada ?>
	<div id="r__3_MAP_Controlada" class="form-group">
		<label id="elh_view_id__3_MAP_Controlada" for="x__3_MAP_Controlada" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_MAP_Controlada->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_MAP_Controlada->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_MAP_Controlada">
<input type="text" data-field="x__3_MAP_Controlada" name="x__3_MAP_Controlada" id="x__3_MAP_Controlada" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_MAP_Controlada->PlaceHolder) ?>" value="<?php echo $view_id->_3_MAP_Controlada->EditValue ?>"<?php echo $view_id->_3_MAP_Controlada->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_MAP_Controlada">
<span<?php echo $view_id->_3_MAP_Controlada->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_MAP_Controlada->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_MAP_Controlada" name="x__3_MAP_Controlada" id="x__3_MAP_Controlada" value="<?php echo ew_HtmlEncode($view_id->_3_MAP_Controlada->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_MAP_Controlada->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_MAP_No_controlada->Visible) { // 3_MAP_No_controlada ?>
	<div id="r__3_MAP_No_controlada" class="form-group">
		<label id="elh_view_id__3_MAP_No_controlada" for="x__3_MAP_No_controlada" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_MAP_No_controlada->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_MAP_No_controlada->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_MAP_No_controlada">
<input type="text" data-field="x__3_MAP_No_controlada" name="x__3_MAP_No_controlada" id="x__3_MAP_No_controlada" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_MAP_No_controlada->PlaceHolder) ?>" value="<?php echo $view_id->_3_MAP_No_controlada->EditValue ?>"<?php echo $view_id->_3_MAP_No_controlada->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_MAP_No_controlada">
<span<?php echo $view_id->_3_MAP_No_controlada->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_MAP_No_controlada->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_MAP_No_controlada" name="x__3_MAP_No_controlada" id="x__3_MAP_No_controlada" value="<?php echo ew_HtmlEncode($view_id->_3_MAP_No_controlada->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_MAP_No_controlada->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_MUSE->Visible) { // 3_MUSE ?>
	<div id="r__3_MUSE" class="form-group">
		<label id="elh_view_id__3_MUSE" for="x__3_MUSE" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_MUSE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_MUSE->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_MUSE">
<input type="text" data-field="x__3_MUSE" name="x__3_MUSE" id="x__3_MUSE" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_MUSE->PlaceHolder) ?>" value="<?php echo $view_id->_3_MUSE->EditValue ?>"<?php echo $view_id->_3_MUSE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_MUSE">
<span<?php echo $view_id->_3_MUSE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_MUSE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_MUSE" name="x__3_MUSE" id="x__3_MUSE" value="<?php echo ew_HtmlEncode($view_id->_3_MUSE->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_MUSE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_3_Operaciones_de_seguridad->Visible) { // 3_Operaciones_de_seguridad ?>
	<div id="r__3_Operaciones_de_seguridad" class="form-group">
		<label id="elh_view_id__3_Operaciones_de_seguridad" for="x__3_Operaciones_de_seguridad" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_3_Operaciones_de_seguridad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_3_Operaciones_de_seguridad->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__3_Operaciones_de_seguridad">
<input type="text" data-field="x__3_Operaciones_de_seguridad" name="x__3_Operaciones_de_seguridad" id="x__3_Operaciones_de_seguridad" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_3_Operaciones_de_seguridad->PlaceHolder) ?>" value="<?php echo $view_id->_3_Operaciones_de_seguridad->EditValue ?>"<?php echo $view_id->_3_Operaciones_de_seguridad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__3_Operaciones_de_seguridad">
<span<?php echo $view_id->_3_Operaciones_de_seguridad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_3_Operaciones_de_seguridad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__3_Operaciones_de_seguridad" name="x__3_Operaciones_de_seguridad" id="x__3_Operaciones_de_seguridad" value="<?php echo ew_HtmlEncode($view_id->_3_Operaciones_de_seguridad->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_3_Operaciones_de_seguridad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->GRA_LAT_segurid->Visible) { // GRA_LAT_segurid ?>
	<div id="r_GRA_LAT_segurid" class="form-group">
		<label id="elh_view_id_GRA_LAT_segurid" for="x_GRA_LAT_segurid" class="col-sm-2 control-label ewLabel"><?php echo $view_id->GRA_LAT_segurid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->GRA_LAT_segurid->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_GRA_LAT_segurid">
<input type="text" data-field="x_GRA_LAT_segurid" name="x_GRA_LAT_segurid" id="x_GRA_LAT_segurid" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->GRA_LAT_segurid->PlaceHolder) ?>" value="<?php echo $view_id->GRA_LAT_segurid->EditValue ?>"<?php echo $view_id->GRA_LAT_segurid->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_GRA_LAT_segurid">
<span<?php echo $view_id->GRA_LAT_segurid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->GRA_LAT_segurid->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_GRA_LAT_segurid" name="x_GRA_LAT_segurid" id="x_GRA_LAT_segurid" value="<?php echo ew_HtmlEncode($view_id->GRA_LAT_segurid->FormValue) ?>">
<?php } ?>
<?php echo $view_id->GRA_LAT_segurid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->MIN_LAT_segurid->Visible) { // MIN_LAT_segurid ?>
	<div id="r_MIN_LAT_segurid" class="form-group">
		<label id="elh_view_id_MIN_LAT_segurid" for="x_MIN_LAT_segurid" class="col-sm-2 control-label ewLabel"><?php echo $view_id->MIN_LAT_segurid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->MIN_LAT_segurid->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_MIN_LAT_segurid">
<input type="text" data-field="x_MIN_LAT_segurid" name="x_MIN_LAT_segurid" id="x_MIN_LAT_segurid" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->MIN_LAT_segurid->PlaceHolder) ?>" value="<?php echo $view_id->MIN_LAT_segurid->EditValue ?>"<?php echo $view_id->MIN_LAT_segurid->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_MIN_LAT_segurid">
<span<?php echo $view_id->MIN_LAT_segurid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->MIN_LAT_segurid->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_MIN_LAT_segurid" name="x_MIN_LAT_segurid" id="x_MIN_LAT_segurid" value="<?php echo ew_HtmlEncode($view_id->MIN_LAT_segurid->FormValue) ?>">
<?php } ?>
<?php echo $view_id->MIN_LAT_segurid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->SEG_LAT_segurid->Visible) { // SEG_LAT_segurid ?>
	<div id="r_SEG_LAT_segurid" class="form-group">
		<label id="elh_view_id_SEG_LAT_segurid" for="x_SEG_LAT_segurid" class="col-sm-2 control-label ewLabel"><?php echo $view_id->SEG_LAT_segurid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->SEG_LAT_segurid->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_SEG_LAT_segurid">
<input type="text" data-field="x_SEG_LAT_segurid" name="x_SEG_LAT_segurid" id="x_SEG_LAT_segurid" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->SEG_LAT_segurid->PlaceHolder) ?>" value="<?php echo $view_id->SEG_LAT_segurid->EditValue ?>"<?php echo $view_id->SEG_LAT_segurid->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_SEG_LAT_segurid">
<span<?php echo $view_id->SEG_LAT_segurid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->SEG_LAT_segurid->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_SEG_LAT_segurid" name="x_SEG_LAT_segurid" id="x_SEG_LAT_segurid" value="<?php echo ew_HtmlEncode($view_id->SEG_LAT_segurid->FormValue) ?>">
<?php } ?>
<?php echo $view_id->SEG_LAT_segurid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->GRA_LONG_seguri->Visible) { // GRA_LONG_seguri ?>
	<div id="r_GRA_LONG_seguri" class="form-group">
		<label id="elh_view_id_GRA_LONG_seguri" for="x_GRA_LONG_seguri" class="col-sm-2 control-label ewLabel"><?php echo $view_id->GRA_LONG_seguri->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->GRA_LONG_seguri->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_GRA_LONG_seguri">
<input type="text" data-field="x_GRA_LONG_seguri" name="x_GRA_LONG_seguri" id="x_GRA_LONG_seguri" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->GRA_LONG_seguri->PlaceHolder) ?>" value="<?php echo $view_id->GRA_LONG_seguri->EditValue ?>"<?php echo $view_id->GRA_LONG_seguri->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_GRA_LONG_seguri">
<span<?php echo $view_id->GRA_LONG_seguri->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->GRA_LONG_seguri->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_GRA_LONG_seguri" name="x_GRA_LONG_seguri" id="x_GRA_LONG_seguri" value="<?php echo ew_HtmlEncode($view_id->GRA_LONG_seguri->FormValue) ?>">
<?php } ?>
<?php echo $view_id->GRA_LONG_seguri->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->MIN_LONG_seguri->Visible) { // MIN_LONG_seguri ?>
	<div id="r_MIN_LONG_seguri" class="form-group">
		<label id="elh_view_id_MIN_LONG_seguri" for="x_MIN_LONG_seguri" class="col-sm-2 control-label ewLabel"><?php echo $view_id->MIN_LONG_seguri->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->MIN_LONG_seguri->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_MIN_LONG_seguri">
<input type="text" data-field="x_MIN_LONG_seguri" name="x_MIN_LONG_seguri" id="x_MIN_LONG_seguri" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->MIN_LONG_seguri->PlaceHolder) ?>" value="<?php echo $view_id->MIN_LONG_seguri->EditValue ?>"<?php echo $view_id->MIN_LONG_seguri->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_MIN_LONG_seguri">
<span<?php echo $view_id->MIN_LONG_seguri->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->MIN_LONG_seguri->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_MIN_LONG_seguri" name="x_MIN_LONG_seguri" id="x_MIN_LONG_seguri" value="<?php echo ew_HtmlEncode($view_id->MIN_LONG_seguri->FormValue) ?>">
<?php } ?>
<?php echo $view_id->MIN_LONG_seguri->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->SEG_LONG_seguri->Visible) { // SEG_LONG_seguri ?>
	<div id="r_SEG_LONG_seguri" class="form-group">
		<label id="elh_view_id_SEG_LONG_seguri" for="x_SEG_LONG_seguri" class="col-sm-2 control-label ewLabel"><?php echo $view_id->SEG_LONG_seguri->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->SEG_LONG_seguri->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_SEG_LONG_seguri">
<input type="text" data-field="x_SEG_LONG_seguri" name="x_SEG_LONG_seguri" id="x_SEG_LONG_seguri" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->SEG_LONG_seguri->PlaceHolder) ?>" value="<?php echo $view_id->SEG_LONG_seguri->EditValue ?>"<?php echo $view_id->SEG_LONG_seguri->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_SEG_LONG_seguri">
<span<?php echo $view_id->SEG_LONG_seguri->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->SEG_LONG_seguri->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_SEG_LONG_seguri" name="x_SEG_LONG_seguri" id="x_SEG_LONG_seguri" value="<?php echo ew_HtmlEncode($view_id->SEG_LONG_seguri->FormValue) ?>">
<?php } ?>
<?php echo $view_id->SEG_LONG_seguri->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Novedad->Visible) { // Novedad ?>
	<div id="r_Novedad" class="form-group">
		<label id="elh_view_id_Novedad" for="x_Novedad" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Novedad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Novedad->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Novedad">
<input type="text" data-field="x_Novedad" name="x_Novedad" id="x_Novedad" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Novedad->PlaceHolder) ?>" value="<?php echo $view_id->Novedad->EditValue ?>"<?php echo $view_id->Novedad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Novedad">
<span<?php echo $view_id->Novedad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Novedad->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Novedad" name="x_Novedad" id="x_Novedad" value="<?php echo ew_HtmlEncode($view_id->Novedad->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Novedad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_4_Epidemia->Visible) { // 4_Epidemia ?>
	<div id="r__4_Epidemia" class="form-group">
		<label id="elh_view_id__4_Epidemia" for="x__4_Epidemia" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_4_Epidemia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_4_Epidemia->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__4_Epidemia">
<input type="text" data-field="x__4_Epidemia" name="x__4_Epidemia" id="x__4_Epidemia" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_4_Epidemia->PlaceHolder) ?>" value="<?php echo $view_id->_4_Epidemia->EditValue ?>"<?php echo $view_id->_4_Epidemia->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__4_Epidemia">
<span<?php echo $view_id->_4_Epidemia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_4_Epidemia->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__4_Epidemia" name="x__4_Epidemia" id="x__4_Epidemia" value="<?php echo ew_HtmlEncode($view_id->_4_Epidemia->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_4_Epidemia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_4_Novedad_climatologica->Visible) { // 4_Novedad_climatologica ?>
	<div id="r__4_Novedad_climatologica" class="form-group">
		<label id="elh_view_id__4_Novedad_climatologica" for="x__4_Novedad_climatologica" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_4_Novedad_climatologica->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_4_Novedad_climatologica->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__4_Novedad_climatologica">
<input type="text" data-field="x__4_Novedad_climatologica" name="x__4_Novedad_climatologica" id="x__4_Novedad_climatologica" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_4_Novedad_climatologica->PlaceHolder) ?>" value="<?php echo $view_id->_4_Novedad_climatologica->EditValue ?>"<?php echo $view_id->_4_Novedad_climatologica->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__4_Novedad_climatologica">
<span<?php echo $view_id->_4_Novedad_climatologica->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_4_Novedad_climatologica->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__4_Novedad_climatologica" name="x__4_Novedad_climatologica" id="x__4_Novedad_climatologica" value="<?php echo ew_HtmlEncode($view_id->_4_Novedad_climatologica->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_4_Novedad_climatologica->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_4_Registro_de_cultivos->Visible) { // 4_Registro_de_cultivos ?>
	<div id="r__4_Registro_de_cultivos" class="form-group">
		<label id="elh_view_id__4_Registro_de_cultivos" for="x__4_Registro_de_cultivos" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_4_Registro_de_cultivos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_4_Registro_de_cultivos->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__4_Registro_de_cultivos">
<input type="text" data-field="x__4_Registro_de_cultivos" name="x__4_Registro_de_cultivos" id="x__4_Registro_de_cultivos" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_4_Registro_de_cultivos->PlaceHolder) ?>" value="<?php echo $view_id->_4_Registro_de_cultivos->EditValue ?>"<?php echo $view_id->_4_Registro_de_cultivos->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__4_Registro_de_cultivos">
<span<?php echo $view_id->_4_Registro_de_cultivos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_4_Registro_de_cultivos->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__4_Registro_de_cultivos" name="x__4_Registro_de_cultivos" id="x__4_Registro_de_cultivos" value="<?php echo ew_HtmlEncode($view_id->_4_Registro_de_cultivos->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_4_Registro_de_cultivos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_4_Zona_con_cultivos_muy_dispersos->Visible) { // 4_Zona_con_cultivos_muy_dispersos ?>
	<div id="r__4_Zona_con_cultivos_muy_dispersos" class="form-group">
		<label id="elh_view_id__4_Zona_con_cultivos_muy_dispersos" for="x__4_Zona_con_cultivos_muy_dispersos" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__4_Zona_con_cultivos_muy_dispersos">
<input type="text" data-field="x__4_Zona_con_cultivos_muy_dispersos" name="x__4_Zona_con_cultivos_muy_dispersos" id="x__4_Zona_con_cultivos_muy_dispersos" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_4_Zona_con_cultivos_muy_dispersos->PlaceHolder) ?>" value="<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->EditValue ?>"<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__4_Zona_con_cultivos_muy_dispersos">
<span<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__4_Zona_con_cultivos_muy_dispersos" name="x__4_Zona_con_cultivos_muy_dispersos" id="x__4_Zona_con_cultivos_muy_dispersos" value="<?php echo ew_HtmlEncode($view_id->_4_Zona_con_cultivos_muy_dispersos->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_4_Zona_de_cruce_de_rios_caudalosos->Visible) { // 4_Zona_de_cruce_de_rios_caudalosos ?>
	<div id="r__4_Zona_de_cruce_de_rios_caudalosos" class="form-group">
		<label id="elh_view_id__4_Zona_de_cruce_de_rios_caudalosos" for="x__4_Zona_de_cruce_de_rios_caudalosos" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__4_Zona_de_cruce_de_rios_caudalosos">
<input type="text" data-field="x__4_Zona_de_cruce_de_rios_caudalosos" name="x__4_Zona_de_cruce_de_rios_caudalosos" id="x__4_Zona_de_cruce_de_rios_caudalosos" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_4_Zona_de_cruce_de_rios_caudalosos->PlaceHolder) ?>" value="<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->EditValue ?>"<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__4_Zona_de_cruce_de_rios_caudalosos">
<span<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__4_Zona_de_cruce_de_rios_caudalosos" name="x__4_Zona_de_cruce_de_rios_caudalosos" id="x__4_Zona_de_cruce_de_rios_caudalosos" value="<?php echo ew_HtmlEncode($view_id->_4_Zona_de_cruce_de_rios_caudalosos->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->_4_Zona_sin_cultivos->Visible) { // 4_Zona_sin_cultivos ?>
	<div id="r__4_Zona_sin_cultivos" class="form-group">
		<label id="elh_view_id__4_Zona_sin_cultivos" for="x__4_Zona_sin_cultivos" class="col-sm-2 control-label ewLabel"><?php echo $view_id->_4_Zona_sin_cultivos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->_4_Zona_sin_cultivos->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id__4_Zona_sin_cultivos">
<input type="text" data-field="x__4_Zona_sin_cultivos" name="x__4_Zona_sin_cultivos" id="x__4_Zona_sin_cultivos" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->_4_Zona_sin_cultivos->PlaceHolder) ?>" value="<?php echo $view_id->_4_Zona_sin_cultivos->EditValue ?>"<?php echo $view_id->_4_Zona_sin_cultivos->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id__4_Zona_sin_cultivos">
<span<?php echo $view_id->_4_Zona_sin_cultivos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->_4_Zona_sin_cultivos->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x__4_Zona_sin_cultivos" name="x__4_Zona_sin_cultivos" id="x__4_Zona_sin_cultivos" value="<?php echo ew_HtmlEncode($view_id->_4_Zona_sin_cultivos->FormValue) ?>">
<?php } ?>
<?php echo $view_id->_4_Zona_sin_cultivos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Num_Erra_Salen->Visible) { // Num_Erra_Salen ?>
	<div id="r_Num_Erra_Salen" class="form-group">
		<label id="elh_view_id_Num_Erra_Salen" for="x_Num_Erra_Salen" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Num_Erra_Salen->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Num_Erra_Salen->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Num_Erra_Salen">
<input type="text" data-field="x_Num_Erra_Salen" name="x_Num_Erra_Salen" id="x_Num_Erra_Salen" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Num_Erra_Salen->PlaceHolder) ?>" value="<?php echo $view_id->Num_Erra_Salen->EditValue ?>"<?php echo $view_id->Num_Erra_Salen->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Num_Erra_Salen">
<span<?php echo $view_id->Num_Erra_Salen->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Num_Erra_Salen->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Num_Erra_Salen" name="x_Num_Erra_Salen" id="x_Num_Erra_Salen" value="<?php echo ew_HtmlEncode($view_id->Num_Erra_Salen->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Num_Erra_Salen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Num_Erra_Quedan->Visible) { // Num_Erra_Quedan ?>
	<div id="r_Num_Erra_Quedan" class="form-group">
		<label id="elh_view_id_Num_Erra_Quedan" for="x_Num_Erra_Quedan" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Num_Erra_Quedan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Num_Erra_Quedan->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Num_Erra_Quedan">
<input type="text" data-field="x_Num_Erra_Quedan" name="x_Num_Erra_Quedan" id="x_Num_Erra_Quedan" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->Num_Erra_Quedan->PlaceHolder) ?>" value="<?php echo $view_id->Num_Erra_Quedan->EditValue ?>"<?php echo $view_id->Num_Erra_Quedan->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_Num_Erra_Quedan">
<span<?php echo $view_id->Num_Erra_Quedan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Num_Erra_Quedan->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Num_Erra_Quedan" name="x_Num_Erra_Quedan" id="x_Num_Erra_Quedan" value="<?php echo ew_HtmlEncode($view_id->Num_Erra_Quedan->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Num_Erra_Quedan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->No_ENFERMERO->Visible) { // No_ENFERMERO ?>
	<div id="r_No_ENFERMERO" class="form-group">
		<label id="elh_view_id_No_ENFERMERO" for="x_No_ENFERMERO" class="col-sm-2 control-label ewLabel"><?php echo $view_id->No_ENFERMERO->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->No_ENFERMERO->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_No_ENFERMERO">
<input type="text" data-field="x_No_ENFERMERO" name="x_No_ENFERMERO" id="x_No_ENFERMERO" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_id->No_ENFERMERO->PlaceHolder) ?>" value="<?php echo $view_id->No_ENFERMERO->EditValue ?>"<?php echo $view_id->No_ENFERMERO->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_No_ENFERMERO">
<span<?php echo $view_id->No_ENFERMERO->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->No_ENFERMERO->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_No_ENFERMERO" name="x_No_ENFERMERO" id="x_No_ENFERMERO" value="<?php echo ew_HtmlEncode($view_id->No_ENFERMERO->FormValue) ?>">
<?php } ?>
<?php echo $view_id->No_ENFERMERO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->NUM_FP->Visible) { // NUM_FP ?>
	<div id="r_NUM_FP" class="form-group">
		<label id="elh_view_id_NUM_FP" for="x_NUM_FP" class="col-sm-2 control-label ewLabel"><?php echo $view_id->NUM_FP->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->NUM_FP->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_NUM_FP">
<input type="text" data-field="x_NUM_FP" name="x_NUM_FP" id="x_NUM_FP" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->NUM_FP->PlaceHolder) ?>" value="<?php echo $view_id->NUM_FP->EditValue ?>"<?php echo $view_id->NUM_FP->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_NUM_FP">
<span<?php echo $view_id->NUM_FP->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->NUM_FP->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NUM_FP" name="x_NUM_FP" id="x_NUM_FP" value="<?php echo ew_HtmlEncode($view_id->NUM_FP->FormValue) ?>">
<?php } ?>
<?php echo $view_id->NUM_FP->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->NUM_Perso_EVA->Visible) { // NUM_Perso_EVA ?>
	<div id="r_NUM_Perso_EVA" class="form-group">
		<label id="elh_view_id_NUM_Perso_EVA" for="x_NUM_Perso_EVA" class="col-sm-2 control-label ewLabel"><?php echo $view_id->NUM_Perso_EVA->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->NUM_Perso_EVA->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_NUM_Perso_EVA">
<input type="text" data-field="x_NUM_Perso_EVA" name="x_NUM_Perso_EVA" id="x_NUM_Perso_EVA" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->NUM_Perso_EVA->PlaceHolder) ?>" value="<?php echo $view_id->NUM_Perso_EVA->EditValue ?>"<?php echo $view_id->NUM_Perso_EVA->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_NUM_Perso_EVA">
<span<?php echo $view_id->NUM_Perso_EVA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->NUM_Perso_EVA->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NUM_Perso_EVA" name="x_NUM_Perso_EVA" id="x_NUM_Perso_EVA" value="<?php echo ew_HtmlEncode($view_id->NUM_Perso_EVA->FormValue) ?>">
<?php } ?>
<?php echo $view_id->NUM_Perso_EVA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->NUM_Poli->Visible) { // NUM_Poli ?>
	<div id="r_NUM_Poli" class="form-group">
		<label id="elh_view_id_NUM_Poli" for="x_NUM_Poli" class="col-sm-2 control-label ewLabel"><?php echo $view_id->NUM_Poli->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->NUM_Poli->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_NUM_Poli">
<input type="text" data-field="x_NUM_Poli" name="x_NUM_Poli" id="x_NUM_Poli" size="30" placeholder="<?php echo ew_HtmlEncode($view_id->NUM_Poli->PlaceHolder) ?>" value="<?php echo $view_id->NUM_Poli->EditValue ?>"<?php echo $view_id->NUM_Poli->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_view_id_NUM_Poli">
<span<?php echo $view_id->NUM_Poli->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->NUM_Poli->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_NUM_Poli" name="x_NUM_Poli" id="x_NUM_Poli" value="<?php echo ew_HtmlEncode($view_id->NUM_Poli->FormValue) ?>">
<?php } ?>
<?php echo $view_id->NUM_Poli->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->AD1O->Visible) { // AÑO ?>
	<div id="r_AD1O" class="form-group">
		<label id="elh_view_id_AD1O" for="x_AD1O" class="col-sm-2 control-label ewLabel"><?php echo $view_id->AD1O->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->AD1O->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_AD1O">
<select data-field="x_AD1O" id="x_AD1O" name="x_AD1O"<?php echo $view_id->AD1O->EditAttributes() ?>>
<?php
if (is_array($view_id->AD1O->EditValue)) {
	$arwrk = $view_id->AD1O->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->AD1O->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_idedit.Lists["x_AD1O"].Options = <?php echo (is_array($view_id->AD1O->EditValue)) ? ew_ArrayToJson($view_id->AD1O->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_id_AD1O">
<span<?php echo $view_id->AD1O->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->AD1O->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_AD1O" name="x_AD1O" id="x_AD1O" value="<?php echo ew_HtmlEncode($view_id->AD1O->FormValue) ?>">
<?php } ?>
<?php echo $view_id->AD1O->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->FASE->Visible) { // FASE ?>
	<div id="r_FASE" class="form-group">
		<label id="elh_view_id_FASE" for="x_FASE" class="col-sm-2 control-label ewLabel"><?php echo $view_id->FASE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->FASE->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_FASE">
<select data-field="x_FASE" id="x_FASE" name="x_FASE"<?php echo $view_id->FASE->EditAttributes() ?>>
<?php
if (is_array($view_id->FASE->EditValue)) {
	$arwrk = $view_id->FASE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->FASE->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fview_idedit.Lists["x_FASE"].Options = <?php echo (is_array($view_id->FASE->EditValue)) ? ew_ArrayToJson($view_id->FASE->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el_view_id_FASE">
<span<?php echo $view_id->FASE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->FASE->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_FASE" name="x_FASE" id="x_FASE" value="<?php echo ew_HtmlEncode($view_id->FASE->FormValue) ?>">
<?php } ?>
<?php echo $view_id->FASE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($view_id->Modificado->Visible) { // Modificado ?>
	<div id="r_Modificado" class="form-group">
		<label id="elh_view_id_Modificado" for="x_Modificado" class="col-sm-2 control-label ewLabel"><?php echo $view_id->Modificado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $view_id->Modificado->CellAttributes() ?>>
<?php if ($view_id->CurrentAction <> "F") { ?>
<span id="el_view_id_Modificado">
<select data-field="x_Modificado" id="x_Modificado" name="x_Modificado"<?php echo $view_id->Modificado->EditAttributes() ?>>
<?php
if (is_array($view_id->Modificado->EditValue)) {
	$arwrk = $view_id->Modificado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view_id->Modificado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<span id="el_view_id_Modificado">
<span<?php echo $view_id->Modificado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $view_id->Modificado->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_Modificado" name="x_Modificado" id="x_Modificado" value="<?php echo ew_HtmlEncode($view_id->Modificado->FormValue) ?>">
<?php } ?>
<?php echo $view_id->Modificado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<?php if ($view_id->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_edit.value='F';"><?php echo $Language->Phrase("SaveBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_edit.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div>
</div>
</form>
<script type="text/javascript">
fview_idedit.Init();
</script>
<?php
$view_id_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_id_edit->Page_Terminate();
?>
