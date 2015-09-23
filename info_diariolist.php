<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "info_diarioinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$info_diario_list = NULL; // Initialize page object first

class cinfo_diario_list extends cinfo_diario {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'info_diario';

	// Page object name
	var $PageObjName = 'info_diario_list';

	// Grid form hidden field names
	var $FormName = 'finfo_diariolist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (info_diario)
		if (!isset($GLOBALS["info_diario"]) || get_class($GLOBALS["info_diario"]) == "cinfo_diario") {
			$GLOBALS["info_diario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["info_diario"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "info_diarioadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "info_diariodelete.php";
		$this->MultiUpdateUrl = "info_diarioupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'info_diario', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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
		global $EW_EXPORT, $info_diario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($info_diario);
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = EW_SELECT_LIMIT;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->llave, $bCtrl); // llave
			$this->UpdateSort($this->Cargo_gme, $bCtrl); // Cargo_gme
			$this->UpdateSort($this->NOM_PE, $bCtrl); // NOM_PE
			$this->UpdateSort($this->Otro_PE, $bCtrl); // Otro_PE
			$this->UpdateSort($this->Otro_NOM_PGE, $bCtrl); // Otro_NOM_PGE
			$this->UpdateSort($this->Otro_CC_PGE, $bCtrl); // Otro_CC_PGE
			$this->UpdateSort($this->FECHA_REPORT, $bCtrl); // FECHA_REPORT
			$this->UpdateSort($this->DIA, $bCtrl); // DIA
			$this->UpdateSort($this->MES, $bCtrl); // MES
			$this->UpdateSort($this->Departamento, $bCtrl); // Departamento
			$this->UpdateSort($this->NOM_VDA, $bCtrl); // NOM_VDA
			$this->UpdateSort($this->Ha_Coca, $bCtrl); // Ha_Coca
			$this->UpdateSort($this->Ha_Amapola, $bCtrl); // Ha_Amapola
			$this->UpdateSort($this->Ha_Marihuana, $bCtrl); // Ha_Marihuana
			$this->UpdateSort($this->T_erradi, $bCtrl); // T_erradi
			$this->UpdateSort($this->GRA_LAT_Sector, $bCtrl); // GRA_LAT_Sector
			$this->UpdateSort($this->MIN_LAT_Sector, $bCtrl); // MIN_LAT_Sector
			$this->UpdateSort($this->SEG_LAT_Sector, $bCtrl); // SEG_LAT_Sector
			$this->UpdateSort($this->GRA_LONG_Sector, $bCtrl); // GRA_LONG_Sector
			$this->UpdateSort($this->MIN_LONG_Sector, $bCtrl); // MIN_LONG_Sector
			$this->UpdateSort($this->SEG_LONG_Sector, $bCtrl); // SEG_LONG_Sector
			$this->UpdateSort($this->Ini_Jorna, $bCtrl); // Ini_Jorna
			$this->UpdateSort($this->Fin_Jorna, $bCtrl); // Fin_Jorna
			$this->UpdateSort($this->Situ_Especial, $bCtrl); // Situ_Especial
			$this->UpdateSort($this->Adm_GME, $bCtrl); // Adm_GME
			$this->UpdateSort($this->_1_Abastecimiento, $bCtrl); // 1_Abastecimiento
			$this->UpdateSort($this->_1_Acompanamiento_firma_GME, $bCtrl); // 1_Acompanamiento_firma_GME
			$this->UpdateSort($this->_1_Apoyo_zonal_sin_punto_asignado, $bCtrl); // 1_Apoyo_zonal_sin_punto_asignado
			$this->UpdateSort($this->_1_Descanso_en_dia_habil, $bCtrl); // 1_Descanso_en_dia_habil
			$this->UpdateSort($this->_1_Descanso_festivo_dominical, $bCtrl); // 1_Descanso_festivo_dominical
			$this->UpdateSort($this->_1_Dia_compensatorio, $bCtrl); // 1_Dia_compensatorio
			$this->UpdateSort($this->_1_Erradicacion_en_dia_festivo, $bCtrl); // 1_Erradicacion_en_dia_festivo
			$this->UpdateSort($this->_1_Espera_helicoptero_Helistar, $bCtrl); // 1_Espera_helicoptero_Helistar
			$this->UpdateSort($this->_1_Extraccion, $bCtrl); // 1_Extraccion
			$this->UpdateSort($this->_1_Firma_contrato_GME, $bCtrl); // 1_Firma_contrato_GME
			$this->UpdateSort($this->_1_Induccion_Apoyo_Zonal, $bCtrl); // 1_Induccion_Apoyo_Zonal
			$this->UpdateSort($this->_1_Insercion, $bCtrl); // 1_Insercion
			$this->UpdateSort($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase, $bCtrl); // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->UpdateSort($this->_1_Novedad_apoyo_zonal, $bCtrl); // 1_Novedad_apoyo_zonal
			$this->UpdateSort($this->_1_Novedad_enfermero, $bCtrl); // 1_Novedad_enfermero
			$this->UpdateSort($this->_1_Punto_fuera_del_area_de_erradicacion, $bCtrl); // 1_Punto_fuera_del_area_de_erradicacion
			$this->UpdateSort($this->_1_Transporte_bus, $bCtrl); // 1_Transporte_bus
			$this->UpdateSort($this->_1_Traslado_apoyo_zonal, $bCtrl); // 1_Traslado_apoyo_zonal
			$this->UpdateSort($this->_1_Traslado_area_vivac, $bCtrl); // 1_Traslado_area_vivac
			$this->UpdateSort($this->Adm_Fuerza, $bCtrl); // Adm_Fuerza
			$this->UpdateSort($this->_2_A_la_espera_definicion_nuevo_punto_FP, $bCtrl); // 2_A_la_espera_definicion_nuevo_punto_FP
			$this->UpdateSort($this->_2_Espera_helicoptero_FP_de_seguridad, $bCtrl); // 2_Espera_helicoptero_FP_de_seguridad
			$this->UpdateSort($this->_2_Espera_helicoptero_FP_que_abastece, $bCtrl); // 2_Espera_helicoptero_FP_que_abastece
			$this->UpdateSort($this->_2_Induccion_FP, $bCtrl); // 2_Induccion_FP
			$this->UpdateSort($this->_2_Novedad_canino_o_del_grupo_de_deteccion, $bCtrl); // 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->UpdateSort($this->_2_Problemas_fuerza_publica, $bCtrl); // 2_Problemas_fuerza_publica
			$this->UpdateSort($this->_2_Sin_seguridad, $bCtrl); // 2_Sin_seguridad
			$this->UpdateSort($this->Sit_Seguridad, $bCtrl); // Sit_Seguridad
			$this->UpdateSort($this->_3_AEI_controlado, $bCtrl); // 3_AEI_controlado
			$this->UpdateSort($this->_3_AEI_no_controlado, $bCtrl); // 3_AEI_no_controlado
			$this->UpdateSort($this->_3_Bloqueo_parcial_de_la_comunidad, $bCtrl); // 3_Bloqueo_parcial_de_la_comunidad
			$this->UpdateSort($this->_3_Bloqueo_total_de_la_comunidad, $bCtrl); // 3_Bloqueo_total_de_la_comunidad
			$this->UpdateSort($this->_3_Combate, $bCtrl); // 3_Combate
			$this->UpdateSort($this->_3_Hostigamiento, $bCtrl); // 3_Hostigamiento
			$this->UpdateSort($this->_3_MAP_Controlada, $bCtrl); // 3_MAP_Controlada
			$this->UpdateSort($this->_3_MAP_No_controlada, $bCtrl); // 3_MAP_No_controlada
			$this->UpdateSort($this->_3_Operaciones_de_seguridad, $bCtrl); // 3_Operaciones_de_seguridad
			$this->UpdateSort($this->GRA_LAT_segurid, $bCtrl); // GRA_LAT_segurid
			$this->UpdateSort($this->MIN_LAT_segurid, $bCtrl); // MIN_LAT_segurid
			$this->UpdateSort($this->SEG_LAT_segurid, $bCtrl); // SEG_LAT_segurid
			$this->UpdateSort($this->GRA_LONG_seguri, $bCtrl); // GRA_LONG_seguri
			$this->UpdateSort($this->MIN_LONG_seguri, $bCtrl); // MIN_LONG_seguri
			$this->UpdateSort($this->SEG_LONG_seguri, $bCtrl); // SEG_LONG_seguri
			$this->UpdateSort($this->Novedad, $bCtrl); // Novedad
			$this->UpdateSort($this->_4_Epidemia, $bCtrl); // 4_Epidemia
			$this->UpdateSort($this->_4_Novedad_climatologica, $bCtrl); // 4_Novedad_climatologica
			$this->UpdateSort($this->_4_Registro_de_cultivos, $bCtrl); // 4_Registro_de_cultivos
			$this->UpdateSort($this->_4_Zona_con_cultivos_muy_dispersos, $bCtrl); // 4_Zona_con_cultivos_muy_dispersos
			$this->UpdateSort($this->_4_Zona_de_cruce_de_rios_caudalosos, $bCtrl); // 4_Zona_de_cruce_de_rios_caudalosos
			$this->UpdateSort($this->_4_Zona_sin_cultivos, $bCtrl); // 4_Zona_sin_cultivos
			$this->UpdateSort($this->Num_Erra_Salen, $bCtrl); // Num_Erra_Salen
			$this->UpdateSort($this->Num_Erra_Quedan, $bCtrl); // Num_Erra_Quedan
			$this->UpdateSort($this->No_ENFERMERO, $bCtrl); // No_ENFERMERO
			$this->UpdateSort($this->NUM_FP, $bCtrl); // NUM_FP
			$this->UpdateSort($this->NUM_Perso_EVA, $bCtrl); // NUM_Perso_EVA
			$this->UpdateSort($this->NUM_Poli, $bCtrl); // NUM_Poli
			$this->UpdateSort($this->Otro_Tema, $bCtrl); // Otro_Tema
			$this->UpdateSort($this->OBSERVACION, $bCtrl); // OBSERVACION
			$this->UpdateSort($this->AD1O, $bCtrl); // AÑO
			$this->UpdateSort($this->FASE, $bCtrl); // FASE
			$this->UpdateSort($this->F_Sincron, $bCtrl); // F_Sincron
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->llave->setSort("");
				$this->Cargo_gme->setSort("");
				$this->NOM_PE->setSort("");
				$this->Otro_PE->setSort("");
				$this->Otro_NOM_PGE->setSort("");
				$this->Otro_CC_PGE->setSort("");
				$this->FECHA_REPORT->setSort("");
				$this->DIA->setSort("");
				$this->MES->setSort("");
				$this->Departamento->setSort("");
				$this->NOM_VDA->setSort("");
				$this->Ha_Coca->setSort("");
				$this->Ha_Amapola->setSort("");
				$this->Ha_Marihuana->setSort("");
				$this->T_erradi->setSort("");
				$this->GRA_LAT_Sector->setSort("");
				$this->MIN_LAT_Sector->setSort("");
				$this->SEG_LAT_Sector->setSort("");
				$this->GRA_LONG_Sector->setSort("");
				$this->MIN_LONG_Sector->setSort("");
				$this->SEG_LONG_Sector->setSort("");
				$this->Ini_Jorna->setSort("");
				$this->Fin_Jorna->setSort("");
				$this->Situ_Especial->setSort("");
				$this->Adm_GME->setSort("");
				$this->_1_Abastecimiento->setSort("");
				$this->_1_Acompanamiento_firma_GME->setSort("");
				$this->_1_Apoyo_zonal_sin_punto_asignado->setSort("");
				$this->_1_Descanso_en_dia_habil->setSort("");
				$this->_1_Descanso_festivo_dominical->setSort("");
				$this->_1_Dia_compensatorio->setSort("");
				$this->_1_Erradicacion_en_dia_festivo->setSort("");
				$this->_1_Espera_helicoptero_Helistar->setSort("");
				$this->_1_Extraccion->setSort("");
				$this->_1_Firma_contrato_GME->setSort("");
				$this->_1_Induccion_Apoyo_Zonal->setSort("");
				$this->_1_Insercion->setSort("");
				$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->setSort("");
				$this->_1_Novedad_apoyo_zonal->setSort("");
				$this->_1_Novedad_enfermero->setSort("");
				$this->_1_Punto_fuera_del_area_de_erradicacion->setSort("");
				$this->_1_Transporte_bus->setSort("");
				$this->_1_Traslado_apoyo_zonal->setSort("");
				$this->_1_Traslado_area_vivac->setSort("");
				$this->Adm_Fuerza->setSort("");
				$this->_2_A_la_espera_definicion_nuevo_punto_FP->setSort("");
				$this->_2_Espera_helicoptero_FP_de_seguridad->setSort("");
				$this->_2_Espera_helicoptero_FP_que_abastece->setSort("");
				$this->_2_Induccion_FP->setSort("");
				$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setSort("");
				$this->_2_Problemas_fuerza_publica->setSort("");
				$this->_2_Sin_seguridad->setSort("");
				$this->Sit_Seguridad->setSort("");
				$this->_3_AEI_controlado->setSort("");
				$this->_3_AEI_no_controlado->setSort("");
				$this->_3_Bloqueo_parcial_de_la_comunidad->setSort("");
				$this->_3_Bloqueo_total_de_la_comunidad->setSort("");
				$this->_3_Combate->setSort("");
				$this->_3_Hostigamiento->setSort("");
				$this->_3_MAP_Controlada->setSort("");
				$this->_3_MAP_No_controlada->setSort("");
				$this->_3_Operaciones_de_seguridad->setSort("");
				$this->GRA_LAT_segurid->setSort("");
				$this->MIN_LAT_segurid->setSort("");
				$this->SEG_LAT_segurid->setSort("");
				$this->GRA_LONG_seguri->setSort("");
				$this->MIN_LONG_seguri->setSort("");
				$this->SEG_LONG_seguri->setSort("");
				$this->Novedad->setSort("");
				$this->_4_Epidemia->setSort("");
				$this->_4_Novedad_climatologica->setSort("");
				$this->_4_Registro_de_cultivos->setSort("");
				$this->_4_Zona_con_cultivos_muy_dispersos->setSort("");
				$this->_4_Zona_de_cruce_de_rios_caudalosos->setSort("");
				$this->_4_Zona_sin_cultivos->setSort("");
				$this->Num_Erra_Salen->setSort("");
				$this->Num_Erra_Quedan->setSort("");
				$this->No_ENFERMERO->setSort("");
				$this->NUM_FP->setSort("");
				$this->NUM_Perso_EVA->setSort("");
				$this->NUM_Poli->setSort("");
				$this->Otro_Tema->setSort("");
				$this->OBSERVACION->setSort("");
				$this->AD1O->setSort("");
				$this->FASE->setSort("");
				$this->F_Sincron->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.finfo_diariolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch())
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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
		$this->llave->setDbValue($rs->fields('llave'));
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
		$this->TEMA->setDbValue($rs->fields('TEMA'));
		$this->Otro_Tema->setDbValue($rs->fields('Otro_Tema'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->AD1O->setDbValue($rs->fields('AÑO'));
		$this->FASE->setDbValue($rs->fields('FASE'));
		$this->F_Sincron->setDbValue($rs->fields('F_Sincron'));
		$this->FUERZA->setDbValue($rs->fields('FUERZA'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->llave->DbValue = $row['llave'];
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
		$this->TEMA->DbValue = $row['TEMA'];
		$this->Otro_Tema->DbValue = $row['Otro_Tema'];
		$this->OBSERVACION->DbValue = $row['OBSERVACION'];
		$this->AD1O->DbValue = $row['AÑO'];
		$this->FASE->DbValue = $row['FASE'];
		$this->F_Sincron->DbValue = $row['F_Sincron'];
		$this->FUERZA->DbValue = $row['FUERZA'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		// TEMA
		// Otro_Tema
		// OBSERVACION
		// AÑO
		// FASE
		// F_Sincron
		// FUERZA

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// llave
			$this->llave->ViewValue = $this->llave->CurrentValue;
			$this->llave->ViewCustomAttributes = "";

			// Cargo_gme
			$this->Cargo_gme->ViewValue = $this->Cargo_gme->CurrentValue;
			$this->Cargo_gme->ViewCustomAttributes = "";

			// NOM_PE
			$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
			$this->NOM_PE->ViewCustomAttributes = "";

			// Otro_PE
			$this->Otro_PE->ViewValue = $this->Otro_PE->CurrentValue;
			$this->Otro_PE->ViewCustomAttributes = "";

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->ViewValue = $this->Otro_NOM_PGE->CurrentValue;
			$this->Otro_NOM_PGE->ViewCustomAttributes = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
			$this->Otro_CC_PGE->ViewCustomAttributes = "";

			// FECHA_REPORT
			$this->FECHA_REPORT->ViewValue = $this->FECHA_REPORT->CurrentValue;
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
			$this->Situ_Especial->ViewValue = $this->Situ_Especial->CurrentValue;
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

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->ViewValue = $this->_3_Operaciones_de_seguridad->CurrentValue;
			$this->_3_Operaciones_de_seguridad->ViewCustomAttributes = "";

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

			// Otro_Tema
			$this->Otro_Tema->ViewValue = $this->Otro_Tema->CurrentValue;
			$this->Otro_Tema->ViewCustomAttributes = "";

			// OBSERVACION
			$this->OBSERVACION->ViewValue = $this->OBSERVACION->CurrentValue;
			$this->OBSERVACION->ViewCustomAttributes = "";

			// AÑO
			$this->AD1O->ViewValue = $this->AD1O->CurrentValue;
			$this->AD1O->ViewCustomAttributes = "";

			// FASE
			$this->FASE->ViewValue = $this->FASE->CurrentValue;
			$this->FASE->ViewCustomAttributes = "";

			// F_Sincron
			$this->F_Sincron->ViewValue = $this->F_Sincron->CurrentValue;
			$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 7);
			$this->F_Sincron->ViewCustomAttributes = "";

			// llave
			$this->llave->LinkCustomAttributes = "";
			$this->llave->HrefValue = "";
			$this->llave->TooltipValue = "";

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

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->LinkCustomAttributes = "";
			$this->Otro_NOM_PGE->HrefValue = "";
			$this->Otro_NOM_PGE->TooltipValue = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->LinkCustomAttributes = "";
			$this->Otro_CC_PGE->HrefValue = "";
			$this->Otro_CC_PGE->TooltipValue = "";

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

			// Otro_Tema
			$this->Otro_Tema->LinkCustomAttributes = "";
			$this->Otro_Tema->HrefValue = "";
			$this->Otro_Tema->TooltipValue = "";

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

			// F_Sincron
			$this->F_Sincron->LinkCustomAttributes = "";
			$this->F_Sincron->HrefValue = "";
			$this->F_Sincron->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_info_diario\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_info_diario',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.finfo_diariolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Call Page Exported server event
		$this->Page_Exported();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($info_diario_list)) $info_diario_list = new cinfo_diario_list();

// Page init
$info_diario_list->Page_Init();

// Page main
$info_diario_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$info_diario_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($info_diario->Export == "") { ?>
<script type="text/javascript">

// Page object
var info_diario_list = new ew_Page("info_diario_list");
info_diario_list.PageID = "list"; // Page ID
var EW_PAGE_ID = info_diario_list.PageID; // For backward compatibility

// Form object
var finfo_diariolist = new ew_Form("finfo_diariolist");
finfo_diariolist.FormKeyCountName = '<?php echo $info_diario_list->FormKeyCountName ?>';

// Form_CustomValidate event
finfo_diariolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finfo_diariolist.ValidateRequired = true;
<?php } else { ?>
finfo_diariolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($info_diario->Export == "") { ?>
<div class="ewToolbar">
<?php if ($info_diario->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($info_diario_list->TotalRecs > 0 && $info_diario_list->ExportOptions->Visible()) { ?>

<?php } ?>
<?php if ($info_diario->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($info_diario_list->TotalRecs <= 0)
			$info_diario_list->TotalRecs = $info_diario->SelectRecordCount();
	} else {
		if (!$info_diario_list->Recordset && ($info_diario_list->Recordset = $info_diario_list->LoadRecordset()))
			$info_diario_list->TotalRecs = $info_diario_list->Recordset->RecordCount();
	}
	$info_diario_list->StartRec = 1;
	if ($info_diario_list->DisplayRecs <= 0 || ($info_diario->Export <> "" && $info_diario->ExportAll)) // Display all records
		$info_diario_list->DisplayRecs = $info_diario_list->TotalRecs;
	if (!($info_diario->Export <> "" && $info_diario->ExportAll))
		$info_diario_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$info_diario_list->Recordset = $info_diario_list->LoadRecordset($info_diario_list->StartRec-1, $info_diario_list->DisplayRecs);

	// Set no record found message
	if ($info_diario->CurrentAction == "" && $info_diario_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$info_diario_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($info_diario_list->SearchWhere == "0=101")
			$info_diario_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$info_diario_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$info_diario_list->RenderOtherOptions();
?>
<?php $info_diario_list->ShowPageHeader(); ?>
<?php
$info_diario_list->ShowMessage();
?>
<?php if ($info_diario_list->TotalRecs > 0 || $info_diario->CurrentAction <> "") { ?>
<div>
<script type="text/javascript">
		
		
		$(document).ready(function(){

			$.ajax({
					type: "GET",
					url: "mail_accidentes.php",
					cache: false,
					dataType: "json",
					success: function(html)
					{
						

					}			
				});
		});

	</script>


<table>
	
	<h2>Datos de Informe Diario</h2>
	<br>
	<table>
	<tr>
	<td>Fecha y hora del último registro sincronizado en base de datos:</td><td><i><div id="actualizacion"></div></i></td>
	</tr>
	</table>
	<br>
	<p>Este módulo permite descargar un archivo Excel con los datos del formulario "Informe Diario" </p> 
	<br>
	
	
	
	

	<script type="text/javascript">
		
		$(document).ready(function(){
			var formulario="Informe_Diario";
			var dataString = 'formulario=' + formulario;
			$.ajax({
					type: "GET",
					async: false,
					url: "Precarga_datos.php",
					data: dataString,
					cache: false,
					dataType: "json",
					success: function(html)
					{
						$("#actualizacion").html(html[0]);

					},
					error: function() {
					
						alert("No hay conección con la base de datos apra realizar la descarga");
					}			
				});
		});

		$(document).ready(function(){
			$("#boton_ID").click(function(){
				var id=this.id
				var dataString = 'id='+ id;
				$.ajax({
					type: "POST",
					url: "Descarga_datos_ID.php",
					data: dataString,
					success: function(html)
					{
						 window.location ="Descarga_datos_ID.php";

					},
					error: function() {
					
						alert("No hay datos sincronizados en este fromulario");
					}			
				});
			});

		});
	</script>
	<button type="button" id="boton_ID" >Descargar Excel</button> 
	

	

</table>
</div>
<?php } ?>
<?php if ($info_diario->Export == "") { ?>
<script type="text/javascript">
finfo_diariolist.Init();
</script>
<?php } ?>
<?php
$info_diario_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($info_diario->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$info_diario_list->Page_Terminate();
?>
