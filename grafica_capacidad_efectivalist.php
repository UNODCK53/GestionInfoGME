<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "grafica_capacidad_efectivainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$grafica_capacidad_efectiva_list = NULL; // Initialize page object first

class cgrafica_capacidad_efectiva_list extends cgrafica_capacidad_efectiva {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'grafica_capacidad_efectiva';

	// Page object name
	var $PageObjName = 'grafica_capacidad_efectiva_list';

	// Grid form hidden field names
	var $FormName = 'fgrafica_capacidad_efectivalist';
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

		// Table object (grafica_capacidad_efectiva)
		if (!isset($GLOBALS["grafica_capacidad_efectiva"]) || get_class($GLOBALS["grafica_capacidad_efectiva"]) == "cgrafica_capacidad_efectiva") {
			$GLOBALS["grafica_capacidad_efectiva"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["grafica_capacidad_efectiva"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "grafica_capacidad_efectivaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "grafica_capacidad_efectivadelete.php";
		$this->MultiUpdateUrl = "grafica_capacidad_efectivaupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'grafica_capacidad_efectiva', TRUE);

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
		global $EW_EXPORT, $grafica_capacidad_efectiva;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($grafica_capacidad_efectiva);
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

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Punto, $Default, FALSE); // Punto
		$this->BuildSearchSql($sWhere, $this->Total_general, $Default, FALSE); // Total_general
		$this->BuildSearchSql($sWhere, $this->_1_Apoyo_zonal_sin_punto_asignado, $Default, FALSE); // 1_Apoyo_zonal_sin_punto_asignado
		$this->BuildSearchSql($sWhere, $this->_1_Descanso_en_dia_habil, $Default, FALSE); // 1_Descanso_en_dia_habil
		$this->BuildSearchSql($sWhere, $this->_1_Descanso_festivo_dominical, $Default, FALSE); // 1_Descanso_festivo_dominical
		$this->BuildSearchSql($sWhere, $this->_1_Dia_compensatorio, $Default, FALSE); // 1_Dia_compensatorio
		$this->BuildSearchSql($sWhere, $this->_1_Erradicacion_en_dia_festivo, $Default, FALSE); // 1_Erradicacion_en_dia_festivo
		$this->BuildSearchSql($sWhere, $this->_1_Espera_helicoptero_Helistar, $Default, FALSE); // 1_Espera_helicoptero_Helistar
		$this->BuildSearchSql($sWhere, $this->_1_Extraccion, $Default, FALSE); // 1_Extraccion
		$this->BuildSearchSql($sWhere, $this->_1_Firma_contrato_GME, $Default, FALSE); // 1_Firma_contrato_GME
		$this->BuildSearchSql($sWhere, $this->_1_Induccion_Apoyo_Zonal, $Default, FALSE); // 1_Induccion_Apoyo_Zonal
		$this->BuildSearchSql($sWhere, $this->_1_Insercion, $Default, FALSE); // 1_Insercion
		$this->BuildSearchSql($sWhere, $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase, $Default, FALSE); // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->BuildSearchSql($sWhere, $this->_1_Novedad_apoyo_zonal, $Default, FALSE); // 1_Novedad_apoyo_zonal
		$this->BuildSearchSql($sWhere, $this->_1_Novedad_enfermero, $Default, FALSE); // 1_Novedad_enfermero
		$this->BuildSearchSql($sWhere, $this->_1_Punto_fuera_del_area_de_erradicacion, $Default, FALSE); // 1_Punto_fuera_del_area_de_erradicacion
		$this->BuildSearchSql($sWhere, $this->_1_Transporte_bus, $Default, FALSE); // 1_Transporte_bus
		$this->BuildSearchSql($sWhere, $this->_1_Traslado_apoyo_zonal, $Default, FALSE); // 1_Traslado_apoyo_zonal
		$this->BuildSearchSql($sWhere, $this->_1_Traslado_area_vivac, $Default, FALSE); // 1_Traslado_area_vivac
		$this->BuildSearchSql($sWhere, $this->_2_A_la_espera_definicion_nuevo_punto_FP, $Default, FALSE); // 2_A_la_espera_definicion_nuevo_punto_FP
		$this->BuildSearchSql($sWhere, $this->_2_Espera_helicoptero_FP_de_seguridad, $Default, FALSE); // 2_Espera_helicoptero_FP_de_seguridad
		$this->BuildSearchSql($sWhere, $this->_2_Espera_helicoptero_FP_que_abastece, $Default, FALSE); // 2_Espera_helicoptero_FP_que_abastece
		$this->BuildSearchSql($sWhere, $this->_2_Induccion_FP, $Default, FALSE); // 2_Induccion_FP
		$this->BuildSearchSql($sWhere, $this->_2_Novedad_canino_o_del_grupo_de_deteccion, $Default, FALSE); // 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->BuildSearchSql($sWhere, $this->_2_Problemas_fuerza_publica, $Default, FALSE); // 2_Problemas_fuerza_publica
		$this->BuildSearchSql($sWhere, $this->_2_Sin_seguridad, $Default, FALSE); // 2_Sin_seguridad
		$this->BuildSearchSql($sWhere, $this->_3_AEI_controlado, $Default, FALSE); // 3_AEI_controlado
		$this->BuildSearchSql($sWhere, $this->_3_AEI_no_controlado, $Default, FALSE); // 3_AEI_no_controlado
		$this->BuildSearchSql($sWhere, $this->_3_Bloqueo_parcial_de_la_comunidad, $Default, FALSE); // 3_Bloqueo_parcial_de_la_comunidad
		$this->BuildSearchSql($sWhere, $this->_3_Bloqueo_total_de_la_comunidad, $Default, FALSE); // 3_Bloqueo_total_de_la_comunidad
		$this->BuildSearchSql($sWhere, $this->_3_Combate, $Default, FALSE); // 3_Combate
		$this->BuildSearchSql($sWhere, $this->_3_Hostigamiento, $Default, FALSE); // 3_Hostigamiento
		$this->BuildSearchSql($sWhere, $this->_3_MAP_Controlada, $Default, FALSE); // 3_MAP_Controlada
		$this->BuildSearchSql($sWhere, $this->_3_MAP_No_controlada, $Default, FALSE); // 3_MAP_No_controlada
		$this->BuildSearchSql($sWhere, $this->_3_Operaciones_de_seguridad, $Default, FALSE); // 3_Operaciones_de_seguridad
		$this->BuildSearchSql($sWhere, $this->_4_Epidemia, $Default, FALSE); // 4_Epidemia
		$this->BuildSearchSql($sWhere, $this->_4_Novedad_climatologica, $Default, FALSE); // 4_Novedad_climatologica
		$this->BuildSearchSql($sWhere, $this->_4_Registro_de_cultivos, $Default, FALSE); // 4_Registro_de_cultivos
		$this->BuildSearchSql($sWhere, $this->_4_Zona_con_cultivos_muy_dispersos, $Default, FALSE); // 4_Zona_con_cultivos_muy_dispersos
		$this->BuildSearchSql($sWhere, $this->_4_Zona_de_cruce_de_rios_caudalosos, $Default, FALSE); // 4_Zona_de_cruce_de_rios_caudalosos
		$this->BuildSearchSql($sWhere, $this->_4_Zona_sin_cultivos, $Default, FALSE); // 4_Zona_sin_cultivos

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Punto->AdvancedSearch->Save(); // Punto
			$this->Total_general->AdvancedSearch->Save(); // Total_general
			$this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->Save(); // 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Descanso_en_dia_habil->AdvancedSearch->Save(); // 1_Descanso_en_dia_habil
			$this->_1_Descanso_festivo_dominical->AdvancedSearch->Save(); // 1_Descanso_festivo_dominical
			$this->_1_Dia_compensatorio->AdvancedSearch->Save(); // 1_Dia_compensatorio
			$this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->Save(); // 1_Erradicacion_en_dia_festivo
			$this->_1_Espera_helicoptero_Helistar->AdvancedSearch->Save(); // 1_Espera_helicoptero_Helistar
			$this->_1_Extraccion->AdvancedSearch->Save(); // 1_Extraccion
			$this->_1_Firma_contrato_GME->AdvancedSearch->Save(); // 1_Firma_contrato_GME
			$this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->Save(); // 1_Induccion_Apoyo_Zonal
			$this->_1_Insercion->AdvancedSearch->Save(); // 1_Insercion
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->Save(); // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Novedad_apoyo_zonal->AdvancedSearch->Save(); // 1_Novedad_apoyo_zonal
			$this->_1_Novedad_enfermero->AdvancedSearch->Save(); // 1_Novedad_enfermero
			$this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->Save(); // 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Transporte_bus->AdvancedSearch->Save(); // 1_Transporte_bus
			$this->_1_Traslado_apoyo_zonal->AdvancedSearch->Save(); // 1_Traslado_apoyo_zonal
			$this->_1_Traslado_area_vivac->AdvancedSearch->Save(); // 1_Traslado_area_vivac
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->Save(); // 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->Save(); // 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->Save(); // 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Induccion_FP->AdvancedSearch->Save(); // 2_Induccion_FP
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->Save(); // 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Problemas_fuerza_publica->AdvancedSearch->Save(); // 2_Problemas_fuerza_publica
			$this->_2_Sin_seguridad->AdvancedSearch->Save(); // 2_Sin_seguridad
			$this->_3_AEI_controlado->AdvancedSearch->Save(); // 3_AEI_controlado
			$this->_3_AEI_no_controlado->AdvancedSearch->Save(); // 3_AEI_no_controlado
			$this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->Save(); // 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->Save(); // 3_Bloqueo_total_de_la_comunidad
			$this->_3_Combate->AdvancedSearch->Save(); // 3_Combate
			$this->_3_Hostigamiento->AdvancedSearch->Save(); // 3_Hostigamiento
			$this->_3_MAP_Controlada->AdvancedSearch->Save(); // 3_MAP_Controlada
			$this->_3_MAP_No_controlada->AdvancedSearch->Save(); // 3_MAP_No_controlada
			$this->_3_Operaciones_de_seguridad->AdvancedSearch->Save(); // 3_Operaciones_de_seguridad
			$this->_4_Epidemia->AdvancedSearch->Save(); // 4_Epidemia
			$this->_4_Novedad_climatologica->AdvancedSearch->Save(); // 4_Novedad_climatologica
			$this->_4_Registro_de_cultivos->AdvancedSearch->Save(); // 4_Registro_de_cultivos
			$this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->Save(); // 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->Save(); // 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_sin_cultivos->AdvancedSearch->Save(); // 4_Zona_sin_cultivos
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->Punto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Total_general->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Descanso_en_dia_habil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Descanso_festivo_dominical->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Dia_compensatorio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Espera_helicoptero_Helistar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Extraccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Firma_contrato_GME->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Insercion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Novedad_apoyo_zonal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Novedad_enfermero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Transporte_bus->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Traslado_apoyo_zonal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_1_Traslado_area_vivac->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_2_Induccion_FP->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_2_Problemas_fuerza_publica->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_2_Sin_seguridad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_AEI_controlado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_AEI_no_controlado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_Combate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_Hostigamiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_MAP_Controlada->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_MAP_No_controlada->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_Operaciones_de_seguridad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_4_Epidemia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_4_Novedad_climatologica->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_4_Registro_de_cultivos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_4_Zona_sin_cultivos->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->Punto->AdvancedSearch->UnsetSession();
		$this->Total_general->AdvancedSearch->UnsetSession();
		$this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->UnsetSession();
		$this->_1_Descanso_en_dia_habil->AdvancedSearch->UnsetSession();
		$this->_1_Descanso_festivo_dominical->AdvancedSearch->UnsetSession();
		$this->_1_Dia_compensatorio->AdvancedSearch->UnsetSession();
		$this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->UnsetSession();
		$this->_1_Espera_helicoptero_Helistar->AdvancedSearch->UnsetSession();
		$this->_1_Extraccion->AdvancedSearch->UnsetSession();
		$this->_1_Firma_contrato_GME->AdvancedSearch->UnsetSession();
		$this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->UnsetSession();
		$this->_1_Insercion->AdvancedSearch->UnsetSession();
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->UnsetSession();
		$this->_1_Novedad_apoyo_zonal->AdvancedSearch->UnsetSession();
		$this->_1_Novedad_enfermero->AdvancedSearch->UnsetSession();
		$this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->UnsetSession();
		$this->_1_Transporte_bus->AdvancedSearch->UnsetSession();
		$this->_1_Traslado_apoyo_zonal->AdvancedSearch->UnsetSession();
		$this->_1_Traslado_area_vivac->AdvancedSearch->UnsetSession();
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->UnsetSession();
		$this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->UnsetSession();
		$this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->UnsetSession();
		$this->_2_Induccion_FP->AdvancedSearch->UnsetSession();
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->UnsetSession();
		$this->_2_Problemas_fuerza_publica->AdvancedSearch->UnsetSession();
		$this->_2_Sin_seguridad->AdvancedSearch->UnsetSession();
		$this->_3_AEI_controlado->AdvancedSearch->UnsetSession();
		$this->_3_AEI_no_controlado->AdvancedSearch->UnsetSession();
		$this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->UnsetSession();
		$this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->UnsetSession();
		$this->_3_Combate->AdvancedSearch->UnsetSession();
		$this->_3_Hostigamiento->AdvancedSearch->UnsetSession();
		$this->_3_MAP_Controlada->AdvancedSearch->UnsetSession();
		$this->_3_MAP_No_controlada->AdvancedSearch->UnsetSession();
		$this->_3_Operaciones_de_seguridad->AdvancedSearch->UnsetSession();
		$this->_4_Epidemia->AdvancedSearch->UnsetSession();
		$this->_4_Novedad_climatologica->AdvancedSearch->UnsetSession();
		$this->_4_Registro_de_cultivos->AdvancedSearch->UnsetSession();
		$this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->UnsetSession();
		$this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->UnsetSession();
		$this->_4_Zona_sin_cultivos->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Punto->AdvancedSearch->Load();
		$this->Total_general->AdvancedSearch->Load();
		$this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->Load();
		$this->_1_Descanso_en_dia_habil->AdvancedSearch->Load();
		$this->_1_Descanso_festivo_dominical->AdvancedSearch->Load();
		$this->_1_Dia_compensatorio->AdvancedSearch->Load();
		$this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->Load();
		$this->_1_Espera_helicoptero_Helistar->AdvancedSearch->Load();
		$this->_1_Extraccion->AdvancedSearch->Load();
		$this->_1_Firma_contrato_GME->AdvancedSearch->Load();
		$this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->Load();
		$this->_1_Insercion->AdvancedSearch->Load();
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->Load();
		$this->_1_Novedad_apoyo_zonal->AdvancedSearch->Load();
		$this->_1_Novedad_enfermero->AdvancedSearch->Load();
		$this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->Load();
		$this->_1_Transporte_bus->AdvancedSearch->Load();
		$this->_1_Traslado_apoyo_zonal->AdvancedSearch->Load();
		$this->_1_Traslado_area_vivac->AdvancedSearch->Load();
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->Load();
		$this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->Load();
		$this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->Load();
		$this->_2_Induccion_FP->AdvancedSearch->Load();
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->Load();
		$this->_2_Problemas_fuerza_publica->AdvancedSearch->Load();
		$this->_2_Sin_seguridad->AdvancedSearch->Load();
		$this->_3_AEI_controlado->AdvancedSearch->Load();
		$this->_3_AEI_no_controlado->AdvancedSearch->Load();
		$this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->Load();
		$this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->Load();
		$this->_3_Combate->AdvancedSearch->Load();
		$this->_3_Hostigamiento->AdvancedSearch->Load();
		$this->_3_MAP_Controlada->AdvancedSearch->Load();
		$this->_3_MAP_No_controlada->AdvancedSearch->Load();
		$this->_3_Operaciones_de_seguridad->AdvancedSearch->Load();
		$this->_4_Epidemia->AdvancedSearch->Load();
		$this->_4_Novedad_climatologica->AdvancedSearch->Load();
		$this->_4_Registro_de_cultivos->AdvancedSearch->Load();
		$this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->Load();
		$this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->Load();
		$this->_4_Zona_sin_cultivos->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Punto, $bCtrl); // Punto
			$this->UpdateSort($this->Total_general, $bCtrl); // Total_general
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
			$this->UpdateSort($this->_2_A_la_espera_definicion_nuevo_punto_FP, $bCtrl); // 2_A_la_espera_definicion_nuevo_punto_FP
			$this->UpdateSort($this->_2_Espera_helicoptero_FP_de_seguridad, $bCtrl); // 2_Espera_helicoptero_FP_de_seguridad
			$this->UpdateSort($this->_2_Espera_helicoptero_FP_que_abastece, $bCtrl); // 2_Espera_helicoptero_FP_que_abastece
			$this->UpdateSort($this->_2_Induccion_FP, $bCtrl); // 2_Induccion_FP
			$this->UpdateSort($this->_2_Novedad_canino_o_del_grupo_de_deteccion, $bCtrl); // 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->UpdateSort($this->_2_Problemas_fuerza_publica, $bCtrl); // 2_Problemas_fuerza_publica
			$this->UpdateSort($this->_2_Sin_seguridad, $bCtrl); // 2_Sin_seguridad
			$this->UpdateSort($this->_3_AEI_controlado, $bCtrl); // 3_AEI_controlado
			$this->UpdateSort($this->_3_AEI_no_controlado, $bCtrl); // 3_AEI_no_controlado
			$this->UpdateSort($this->_3_Bloqueo_parcial_de_la_comunidad, $bCtrl); // 3_Bloqueo_parcial_de_la_comunidad
			$this->UpdateSort($this->_3_Bloqueo_total_de_la_comunidad, $bCtrl); // 3_Bloqueo_total_de_la_comunidad
			$this->UpdateSort($this->_3_Combate, $bCtrl); // 3_Combate
			$this->UpdateSort($this->_3_Hostigamiento, $bCtrl); // 3_Hostigamiento
			$this->UpdateSort($this->_3_MAP_Controlada, $bCtrl); // 3_MAP_Controlada
			$this->UpdateSort($this->_3_MAP_No_controlada, $bCtrl); // 3_MAP_No_controlada
			$this->UpdateSort($this->_3_Operaciones_de_seguridad, $bCtrl); // 3_Operaciones_de_seguridad
			$this->UpdateSort($this->_4_Epidemia, $bCtrl); // 4_Epidemia
			$this->UpdateSort($this->_4_Novedad_climatologica, $bCtrl); // 4_Novedad_climatologica
			$this->UpdateSort($this->_4_Registro_de_cultivos, $bCtrl); // 4_Registro_de_cultivos
			$this->UpdateSort($this->_4_Zona_con_cultivos_muy_dispersos, $bCtrl); // 4_Zona_con_cultivos_muy_dispersos
			$this->UpdateSort($this->_4_Zona_de_cruce_de_rios_caudalosos, $bCtrl); // 4_Zona_de_cruce_de_rios_caudalosos
			$this->UpdateSort($this->_4_Zona_sin_cultivos, $bCtrl); // 4_Zona_sin_cultivos
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

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Punto->setSort("");
				$this->Total_general->setSort("");
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
				$this->_2_A_la_espera_definicion_nuevo_punto_FP->setSort("");
				$this->_2_Espera_helicoptero_FP_de_seguridad->setSort("");
				$this->_2_Espera_helicoptero_FP_que_abastece->setSort("");
				$this->_2_Induccion_FP->setSort("");
				$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setSort("");
				$this->_2_Problemas_fuerza_publica->setSort("");
				$this->_2_Sin_seguridad->setSort("");
				$this->_3_AEI_controlado->setSort("");
				$this->_3_AEI_no_controlado->setSort("");
				$this->_3_Bloqueo_parcial_de_la_comunidad->setSort("");
				$this->_3_Bloqueo_total_de_la_comunidad->setSort("");
				$this->_3_Combate->setSort("");
				$this->_3_Hostigamiento->setSort("");
				$this->_3_MAP_Controlada->setSort("");
				$this->_3_MAP_No_controlada->setSort("");
				$this->_3_Operaciones_de_seguridad->setSort("");
				$this->_4_Epidemia->setSort("");
				$this->_4_Novedad_climatologica->setSort("");
				$this->_4_Registro_de_cultivos->setSort("");
				$this->_4_Zona_con_cultivos_muy_dispersos->setSort("");
				$this->_4_Zona_de_cruce_de_rios_caudalosos->setSort("");
				$this->_4_Zona_sin_cultivos->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgrafica_capacidad_efectivalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fgrafica_capacidad_efectivalistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// Punto

		$this->Punto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Punto"]);
		if ($this->Punto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Punto->AdvancedSearch->SearchOperator = @$_GET["z_Punto"];

		// Total_general
		$this->Total_general->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Total_general"]);
		if ($this->Total_general->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Total_general->AdvancedSearch->SearchOperator = @$_GET["z_Total_general"];

		// 1_Apoyo_zonal_sin_punto_asignado
		$this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Apoyo_zonal_sin_punto_asignado"]);
		if ($this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->SearchOperator = @$_GET["z__1_Apoyo_zonal_sin_punto_asignado"];

		// 1_Descanso_en_dia_habil
		$this->_1_Descanso_en_dia_habil->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Descanso_en_dia_habil"]);
		if ($this->_1_Descanso_en_dia_habil->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Descanso_en_dia_habil->AdvancedSearch->SearchOperator = @$_GET["z__1_Descanso_en_dia_habil"];

		// 1_Descanso_festivo_dominical
		$this->_1_Descanso_festivo_dominical->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Descanso_festivo_dominical"]);
		if ($this->_1_Descanso_festivo_dominical->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Descanso_festivo_dominical->AdvancedSearch->SearchOperator = @$_GET["z__1_Descanso_festivo_dominical"];

		// 1_Dia_compensatorio
		$this->_1_Dia_compensatorio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Dia_compensatorio"]);
		if ($this->_1_Dia_compensatorio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Dia_compensatorio->AdvancedSearch->SearchOperator = @$_GET["z__1_Dia_compensatorio"];

		// 1_Erradicacion_en_dia_festivo
		$this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Erradicacion_en_dia_festivo"]);
		if ($this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->SearchOperator = @$_GET["z__1_Erradicacion_en_dia_festivo"];

		// 1_Espera_helicoptero_Helistar
		$this->_1_Espera_helicoptero_Helistar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Espera_helicoptero_Helistar"]);
		if ($this->_1_Espera_helicoptero_Helistar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Espera_helicoptero_Helistar->AdvancedSearch->SearchOperator = @$_GET["z__1_Espera_helicoptero_Helistar"];

		// 1_Extraccion
		$this->_1_Extraccion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Extraccion"]);
		if ($this->_1_Extraccion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Extraccion->AdvancedSearch->SearchOperator = @$_GET["z__1_Extraccion"];

		// 1_Firma_contrato_GME
		$this->_1_Firma_contrato_GME->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Firma_contrato_GME"]);
		if ($this->_1_Firma_contrato_GME->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Firma_contrato_GME->AdvancedSearch->SearchOperator = @$_GET["z__1_Firma_contrato_GME"];

		// 1_Induccion_Apoyo_Zonal
		$this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Induccion_Apoyo_Zonal"]);
		if ($this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->SearchOperator = @$_GET["z__1_Induccion_Apoyo_Zonal"];

		// 1_Insercion
		$this->_1_Insercion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Insercion"]);
		if ($this->_1_Insercion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Insercion->AdvancedSearch->SearchOperator = @$_GET["z__1_Insercion"];

		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"]);
		if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->SearchOperator = @$_GET["z__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"];

		// 1_Novedad_apoyo_zonal
		$this->_1_Novedad_apoyo_zonal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Novedad_apoyo_zonal"]);
		if ($this->_1_Novedad_apoyo_zonal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Novedad_apoyo_zonal->AdvancedSearch->SearchOperator = @$_GET["z__1_Novedad_apoyo_zonal"];

		// 1_Novedad_enfermero
		$this->_1_Novedad_enfermero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Novedad_enfermero"]);
		if ($this->_1_Novedad_enfermero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Novedad_enfermero->AdvancedSearch->SearchOperator = @$_GET["z__1_Novedad_enfermero"];

		// 1_Punto_fuera_del_area_de_erradicacion
		$this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Punto_fuera_del_area_de_erradicacion"]);
		if ($this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->SearchOperator = @$_GET["z__1_Punto_fuera_del_area_de_erradicacion"];

		// 1_Transporte_bus
		$this->_1_Transporte_bus->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Transporte_bus"]);
		if ($this->_1_Transporte_bus->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Transporte_bus->AdvancedSearch->SearchOperator = @$_GET["z__1_Transporte_bus"];

		// 1_Traslado_apoyo_zonal
		$this->_1_Traslado_apoyo_zonal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Traslado_apoyo_zonal"]);
		if ($this->_1_Traslado_apoyo_zonal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Traslado_apoyo_zonal->AdvancedSearch->SearchOperator = @$_GET["z__1_Traslado_apoyo_zonal"];

		// 1_Traslado_area_vivac
		$this->_1_Traslado_area_vivac->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__1_Traslado_area_vivac"]);
		if ($this->_1_Traslado_area_vivac->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_1_Traslado_area_vivac->AdvancedSearch->SearchOperator = @$_GET["z__1_Traslado_area_vivac"];

		// 2_A_la_espera_definicion_nuevo_punto_FP
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__2_A_la_espera_definicion_nuevo_punto_FP"]);
		if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->SearchOperator = @$_GET["z__2_A_la_espera_definicion_nuevo_punto_FP"];

		// 2_Espera_helicoptero_FP_de_seguridad
		$this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__2_Espera_helicoptero_FP_de_seguridad"]);
		if ($this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->SearchOperator = @$_GET["z__2_Espera_helicoptero_FP_de_seguridad"];

		// 2_Espera_helicoptero_FP_que_abastece
		$this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__2_Espera_helicoptero_FP_que_abastece"]);
		if ($this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->SearchOperator = @$_GET["z__2_Espera_helicoptero_FP_que_abastece"];

		// 2_Induccion_FP
		$this->_2_Induccion_FP->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__2_Induccion_FP"]);
		if ($this->_2_Induccion_FP->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_2_Induccion_FP->AdvancedSearch->SearchOperator = @$_GET["z__2_Induccion_FP"];

		// 2_Novedad_canino_o_del_grupo_de_deteccion
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__2_Novedad_canino_o_del_grupo_de_deteccion"]);
		if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->SearchOperator = @$_GET["z__2_Novedad_canino_o_del_grupo_de_deteccion"];

		// 2_Problemas_fuerza_publica
		$this->_2_Problemas_fuerza_publica->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__2_Problemas_fuerza_publica"]);
		if ($this->_2_Problemas_fuerza_publica->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_2_Problemas_fuerza_publica->AdvancedSearch->SearchOperator = @$_GET["z__2_Problemas_fuerza_publica"];

		// 2_Sin_seguridad
		$this->_2_Sin_seguridad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__2_Sin_seguridad"]);
		if ($this->_2_Sin_seguridad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_2_Sin_seguridad->AdvancedSearch->SearchOperator = @$_GET["z__2_Sin_seguridad"];

		// 3_AEI_controlado
		$this->_3_AEI_controlado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_AEI_controlado"]);
		if ($this->_3_AEI_controlado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_AEI_controlado->AdvancedSearch->SearchOperator = @$_GET["z__3_AEI_controlado"];

		// 3_AEI_no_controlado
		$this->_3_AEI_no_controlado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_AEI_no_controlado"]);
		if ($this->_3_AEI_no_controlado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_AEI_no_controlado->AdvancedSearch->SearchOperator = @$_GET["z__3_AEI_no_controlado"];

		// 3_Bloqueo_parcial_de_la_comunidad
		$this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_Bloqueo_parcial_de_la_comunidad"]);
		if ($this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->SearchOperator = @$_GET["z__3_Bloqueo_parcial_de_la_comunidad"];

		// 3_Bloqueo_total_de_la_comunidad
		$this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_Bloqueo_total_de_la_comunidad"]);
		if ($this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->SearchOperator = @$_GET["z__3_Bloqueo_total_de_la_comunidad"];

		// 3_Combate
		$this->_3_Combate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_Combate"]);
		if ($this->_3_Combate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_Combate->AdvancedSearch->SearchOperator = @$_GET["z__3_Combate"];

		// 3_Hostigamiento
		$this->_3_Hostigamiento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_Hostigamiento"]);
		if ($this->_3_Hostigamiento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_Hostigamiento->AdvancedSearch->SearchOperator = @$_GET["z__3_Hostigamiento"];

		// 3_MAP_Controlada
		$this->_3_MAP_Controlada->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_MAP_Controlada"]);
		if ($this->_3_MAP_Controlada->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_MAP_Controlada->AdvancedSearch->SearchOperator = @$_GET["z__3_MAP_Controlada"];

		// 3_MAP_No_controlada
		$this->_3_MAP_No_controlada->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_MAP_No_controlada"]);
		if ($this->_3_MAP_No_controlada->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_MAP_No_controlada->AdvancedSearch->SearchOperator = @$_GET["z__3_MAP_No_controlada"];

		// 3_Operaciones_de_seguridad
		$this->_3_Operaciones_de_seguridad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_Operaciones_de_seguridad"]);
		if ($this->_3_Operaciones_de_seguridad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_Operaciones_de_seguridad->AdvancedSearch->SearchOperator = @$_GET["z__3_Operaciones_de_seguridad"];

		// 4_Epidemia
		$this->_4_Epidemia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__4_Epidemia"]);
		if ($this->_4_Epidemia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_4_Epidemia->AdvancedSearch->SearchOperator = @$_GET["z__4_Epidemia"];

		// 4_Novedad_climatologica
		$this->_4_Novedad_climatologica->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__4_Novedad_climatologica"]);
		if ($this->_4_Novedad_climatologica->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_4_Novedad_climatologica->AdvancedSearch->SearchOperator = @$_GET["z__4_Novedad_climatologica"];

		// 4_Registro_de_cultivos
		$this->_4_Registro_de_cultivos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__4_Registro_de_cultivos"]);
		if ($this->_4_Registro_de_cultivos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_4_Registro_de_cultivos->AdvancedSearch->SearchOperator = @$_GET["z__4_Registro_de_cultivos"];

		// 4_Zona_con_cultivos_muy_dispersos
		$this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__4_Zona_con_cultivos_muy_dispersos"]);
		if ($this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->SearchOperator = @$_GET["z__4_Zona_con_cultivos_muy_dispersos"];

		// 4_Zona_de_cruce_de_rios_caudalosos
		$this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__4_Zona_de_cruce_de_rios_caudalosos"]);
		if ($this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->SearchOperator = @$_GET["z__4_Zona_de_cruce_de_rios_caudalosos"];

		// 4_Zona_sin_cultivos
		$this->_4_Zona_sin_cultivos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__4_Zona_sin_cultivos"]);
		if ($this->_4_Zona_sin_cultivos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_4_Zona_sin_cultivos->AdvancedSearch->SearchOperator = @$_GET["z__4_Zona_sin_cultivos"];
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
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->Total_general->setDbValue($rs->fields('Total_general'));
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
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->setDbValue($rs->fields('2_A_la_espera_definicion_nuevo_punto_FP'));
		$this->_2_Espera_helicoptero_FP_de_seguridad->setDbValue($rs->fields('2_Espera_helicoptero_FP_de_seguridad'));
		$this->_2_Espera_helicoptero_FP_que_abastece->setDbValue($rs->fields('2_Espera_helicoptero_FP_que_abastece'));
		$this->_2_Induccion_FP->setDbValue($rs->fields('2_Induccion_FP'));
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setDbValue($rs->fields('2_Novedad_canino_o_del_grupo_de_deteccion'));
		$this->_2_Problemas_fuerza_publica->setDbValue($rs->fields('2_Problemas_fuerza_publica'));
		$this->_2_Sin_seguridad->setDbValue($rs->fields('2_Sin_seguridad'));
		$this->_3_AEI_controlado->setDbValue($rs->fields('3_AEI_controlado'));
		$this->_3_AEI_no_controlado->setDbValue($rs->fields('3_AEI_no_controlado'));
		$this->_3_Bloqueo_parcial_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_parcial_de_la_comunidad'));
		$this->_3_Bloqueo_total_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_total_de_la_comunidad'));
		$this->_3_Combate->setDbValue($rs->fields('3_Combate'));
		$this->_3_Hostigamiento->setDbValue($rs->fields('3_Hostigamiento'));
		$this->_3_MAP_Controlada->setDbValue($rs->fields('3_MAP_Controlada'));
		$this->_3_MAP_No_controlada->setDbValue($rs->fields('3_MAP_No_controlada'));
		$this->_3_Operaciones_de_seguridad->setDbValue($rs->fields('3_Operaciones_de_seguridad'));
		$this->_4_Epidemia->setDbValue($rs->fields('4_Epidemia'));
		$this->_4_Novedad_climatologica->setDbValue($rs->fields('4_Novedad_climatologica'));
		$this->_4_Registro_de_cultivos->setDbValue($rs->fields('4_Registro_de_cultivos'));
		$this->_4_Zona_con_cultivos_muy_dispersos->setDbValue($rs->fields('4_Zona_con_cultivos_muy_dispersos'));
		$this->_4_Zona_de_cruce_de_rios_caudalosos->setDbValue($rs->fields('4_Zona_de_cruce_de_rios_caudalosos'));
		$this->_4_Zona_sin_cultivos->setDbValue($rs->fields('4_Zona_sin_cultivos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Punto->DbValue = $row['Punto'];
		$this->Total_general->DbValue = $row['Total_general'];
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
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->DbValue = $row['2_A_la_espera_definicion_nuevo_punto_FP'];
		$this->_2_Espera_helicoptero_FP_de_seguridad->DbValue = $row['2_Espera_helicoptero_FP_de_seguridad'];
		$this->_2_Espera_helicoptero_FP_que_abastece->DbValue = $row['2_Espera_helicoptero_FP_que_abastece'];
		$this->_2_Induccion_FP->DbValue = $row['2_Induccion_FP'];
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->DbValue = $row['2_Novedad_canino_o_del_grupo_de_deteccion'];
		$this->_2_Problemas_fuerza_publica->DbValue = $row['2_Problemas_fuerza_publica'];
		$this->_2_Sin_seguridad->DbValue = $row['2_Sin_seguridad'];
		$this->_3_AEI_controlado->DbValue = $row['3_AEI_controlado'];
		$this->_3_AEI_no_controlado->DbValue = $row['3_AEI_no_controlado'];
		$this->_3_Bloqueo_parcial_de_la_comunidad->DbValue = $row['3_Bloqueo_parcial_de_la_comunidad'];
		$this->_3_Bloqueo_total_de_la_comunidad->DbValue = $row['3_Bloqueo_total_de_la_comunidad'];
		$this->_3_Combate->DbValue = $row['3_Combate'];
		$this->_3_Hostigamiento->DbValue = $row['3_Hostigamiento'];
		$this->_3_MAP_Controlada->DbValue = $row['3_MAP_Controlada'];
		$this->_3_MAP_No_controlada->DbValue = $row['3_MAP_No_controlada'];
		$this->_3_Operaciones_de_seguridad->DbValue = $row['3_Operaciones_de_seguridad'];
		$this->_4_Epidemia->DbValue = $row['4_Epidemia'];
		$this->_4_Novedad_climatologica->DbValue = $row['4_Novedad_climatologica'];
		$this->_4_Registro_de_cultivos->DbValue = $row['4_Registro_de_cultivos'];
		$this->_4_Zona_con_cultivos_muy_dispersos->DbValue = $row['4_Zona_con_cultivos_muy_dispersos'];
		$this->_4_Zona_de_cruce_de_rios_caudalosos->DbValue = $row['4_Zona_de_cruce_de_rios_caudalosos'];
		$this->_4_Zona_sin_cultivos->DbValue = $row['4_Zona_sin_cultivos'];
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
		// Punto
		// Total_general
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
		// 2_A_la_espera_definicion_nuevo_punto_FP
		// 2_Espera_helicoptero_FP_de_seguridad
		// 2_Espera_helicoptero_FP_que_abastece
		// 2_Induccion_FP
		// 2_Novedad_canino_o_del_grupo_de_deteccion
		// 2_Problemas_fuerza_publica
		// 2_Sin_seguridad
		// 3_AEI_controlado
		// 3_AEI_no_controlado
		// 3_Bloqueo_parcial_de_la_comunidad
		// 3_Bloqueo_total_de_la_comunidad
		// 3_Combate
		// 3_Hostigamiento
		// 3_MAP_Controlada
		// 3_MAP_No_controlada
		// 3_Operaciones_de_seguridad
		// 4_Epidemia
		// 4_Novedad_climatologica
		// 4_Registro_de_cultivos
		// 4_Zona_con_cultivos_muy_dispersos
		// 4_Zona_de_cruce_de_rios_caudalosos
		// 4_Zona_sin_cultivos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Punto
			$this->Punto->ViewValue = $this->Punto->CurrentValue;
			$this->Punto->ViewCustomAttributes = "";

			// Total_general
			$this->Total_general->ViewValue = $this->Total_general->CurrentValue;
			$this->Total_general->ViewCustomAttributes = "";

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

			// Punto
			$this->Punto->LinkCustomAttributes = "";
			$this->Punto->HrefValue = "";
			$this->Punto->TooltipValue = "";

			// Total_general
			$this->Total_general->LinkCustomAttributes = "";
			$this->Total_general->HrefValue = "";
			$this->Total_general->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Punto
			$this->Punto->EditAttrs["class"] = "form-control";
			$this->Punto->EditCustomAttributes = "";
			$this->Punto->EditValue = ew_HtmlEncode($this->Punto->AdvancedSearch->SearchValue);
			$this->Punto->PlaceHolder = ew_RemoveHtml($this->Punto->FldCaption());

			// Total_general
			$this->Total_general->EditAttrs["class"] = "form-control";
			$this->Total_general->EditCustomAttributes = "";
			$this->Total_general->EditValue = ew_HtmlEncode($this->Total_general->AdvancedSearch->SearchValue);
			$this->Total_general->PlaceHolder = ew_RemoveHtml($this->Total_general->FldCaption());

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditAttrs["class"] = "form-control";
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditCustomAttributes = "";
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditValue = ew_HtmlEncode($this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->SearchValue);
			$this->_1_Apoyo_zonal_sin_punto_asignado->PlaceHolder = ew_RemoveHtml($this->_1_Apoyo_zonal_sin_punto_asignado->FldCaption());

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->EditAttrs["class"] = "form-control";
			$this->_1_Descanso_en_dia_habil->EditCustomAttributes = "";
			$this->_1_Descanso_en_dia_habil->EditValue = ew_HtmlEncode($this->_1_Descanso_en_dia_habil->AdvancedSearch->SearchValue);
			$this->_1_Descanso_en_dia_habil->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_en_dia_habil->FldCaption());

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->EditAttrs["class"] = "form-control";
			$this->_1_Descanso_festivo_dominical->EditCustomAttributes = "";
			$this->_1_Descanso_festivo_dominical->EditValue = ew_HtmlEncode($this->_1_Descanso_festivo_dominical->AdvancedSearch->SearchValue);
			$this->_1_Descanso_festivo_dominical->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_festivo_dominical->FldCaption());

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->EditAttrs["class"] = "form-control";
			$this->_1_Dia_compensatorio->EditCustomAttributes = "";
			$this->_1_Dia_compensatorio->EditValue = ew_HtmlEncode($this->_1_Dia_compensatorio->AdvancedSearch->SearchValue);
			$this->_1_Dia_compensatorio->PlaceHolder = ew_RemoveHtml($this->_1_Dia_compensatorio->FldCaption());

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->EditAttrs["class"] = "form-control";
			$this->_1_Erradicacion_en_dia_festivo->EditCustomAttributes = "";
			$this->_1_Erradicacion_en_dia_festivo->EditValue = ew_HtmlEncode($this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->SearchValue);
			$this->_1_Erradicacion_en_dia_festivo->PlaceHolder = ew_RemoveHtml($this->_1_Erradicacion_en_dia_festivo->FldCaption());

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->EditAttrs["class"] = "form-control";
			$this->_1_Espera_helicoptero_Helistar->EditCustomAttributes = "";
			$this->_1_Espera_helicoptero_Helistar->EditValue = ew_HtmlEncode($this->_1_Espera_helicoptero_Helistar->AdvancedSearch->SearchValue);
			$this->_1_Espera_helicoptero_Helistar->PlaceHolder = ew_RemoveHtml($this->_1_Espera_helicoptero_Helistar->FldCaption());

			// 1_Extraccion
			$this->_1_Extraccion->EditAttrs["class"] = "form-control";
			$this->_1_Extraccion->EditCustomAttributes = "";
			$this->_1_Extraccion->EditValue = ew_HtmlEncode($this->_1_Extraccion->AdvancedSearch->SearchValue);
			$this->_1_Extraccion->PlaceHolder = ew_RemoveHtml($this->_1_Extraccion->FldCaption());

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->EditAttrs["class"] = "form-control";
			$this->_1_Firma_contrato_GME->EditCustomAttributes = "";
			$this->_1_Firma_contrato_GME->EditValue = ew_HtmlEncode($this->_1_Firma_contrato_GME->AdvancedSearch->SearchValue);
			$this->_1_Firma_contrato_GME->PlaceHolder = ew_RemoveHtml($this->_1_Firma_contrato_GME->FldCaption());

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->EditAttrs["class"] = "form-control";
			$this->_1_Induccion_Apoyo_Zonal->EditCustomAttributes = "";
			$this->_1_Induccion_Apoyo_Zonal->EditValue = ew_HtmlEncode($this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->SearchValue);
			$this->_1_Induccion_Apoyo_Zonal->PlaceHolder = ew_RemoveHtml($this->_1_Induccion_Apoyo_Zonal->FldCaption());

			// 1_Insercion
			$this->_1_Insercion->EditAttrs["class"] = "form-control";
			$this->_1_Insercion->EditCustomAttributes = "";
			$this->_1_Insercion->EditValue = ew_HtmlEncode($this->_1_Insercion->AdvancedSearch->SearchValue);
			$this->_1_Insercion->PlaceHolder = ew_RemoveHtml($this->_1_Insercion->FldCaption());

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditAttrs["class"] = "form-control";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditCustomAttributes = "";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue = ew_HtmlEncode($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->SearchValue);
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->PlaceHolder = ew_RemoveHtml($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption());

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->EditAttrs["class"] = "form-control";
			$this->_1_Novedad_apoyo_zonal->EditCustomAttributes = "";
			$this->_1_Novedad_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Novedad_apoyo_zonal->AdvancedSearch->SearchValue);
			$this->_1_Novedad_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_apoyo_zonal->FldCaption());

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->EditAttrs["class"] = "form-control";
			$this->_1_Novedad_enfermero->EditCustomAttributes = "";
			$this->_1_Novedad_enfermero->EditValue = ew_HtmlEncode($this->_1_Novedad_enfermero->AdvancedSearch->SearchValue);
			$this->_1_Novedad_enfermero->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_enfermero->FldCaption());

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditAttrs["class"] = "form-control";
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditCustomAttributes = "";
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditValue = ew_HtmlEncode($this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->SearchValue);
			$this->_1_Punto_fuera_del_area_de_erradicacion->PlaceHolder = ew_RemoveHtml($this->_1_Punto_fuera_del_area_de_erradicacion->FldCaption());

			// 1_Transporte_bus
			$this->_1_Transporte_bus->EditAttrs["class"] = "form-control";
			$this->_1_Transporte_bus->EditCustomAttributes = "";
			$this->_1_Transporte_bus->EditValue = ew_HtmlEncode($this->_1_Transporte_bus->AdvancedSearch->SearchValue);
			$this->_1_Transporte_bus->PlaceHolder = ew_RemoveHtml($this->_1_Transporte_bus->FldCaption());

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->EditAttrs["class"] = "form-control";
			$this->_1_Traslado_apoyo_zonal->EditCustomAttributes = "";
			$this->_1_Traslado_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Traslado_apoyo_zonal->AdvancedSearch->SearchValue);
			$this->_1_Traslado_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_apoyo_zonal->FldCaption());

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->EditAttrs["class"] = "form-control";
			$this->_1_Traslado_area_vivac->EditCustomAttributes = "";
			$this->_1_Traslado_area_vivac->EditValue = ew_HtmlEncode($this->_1_Traslado_area_vivac->AdvancedSearch->SearchValue);
			$this->_1_Traslado_area_vivac->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_area_vivac->FldCaption());

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditAttrs["class"] = "form-control";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditCustomAttributes = "";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue = ew_HtmlEncode($this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->SearchValue);
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->PlaceHolder = ew_RemoveHtml($this->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption());

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditAttrs["class"] = "form-control";
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->SearchValue);
			$this->_2_Espera_helicoptero_FP_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_de_seguridad->FldCaption());

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->EditAttrs["class"] = "form-control";
			$this->_2_Espera_helicoptero_FP_que_abastece->EditCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_que_abastece->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->SearchValue);
			$this->_2_Espera_helicoptero_FP_que_abastece->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_que_abastece->FldCaption());

			// 2_Induccion_FP
			$this->_2_Induccion_FP->EditAttrs["class"] = "form-control";
			$this->_2_Induccion_FP->EditCustomAttributes = "";
			$this->_2_Induccion_FP->EditValue = ew_HtmlEncode($this->_2_Induccion_FP->AdvancedSearch->SearchValue);
			$this->_2_Induccion_FP->PlaceHolder = ew_RemoveHtml($this->_2_Induccion_FP->FldCaption());

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditAttrs["class"] = "form-control";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditCustomAttributes = "";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue = ew_HtmlEncode($this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->SearchValue);
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->PlaceHolder = ew_RemoveHtml($this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption());

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->EditAttrs["class"] = "form-control";
			$this->_2_Problemas_fuerza_publica->EditCustomAttributes = "";
			$this->_2_Problemas_fuerza_publica->EditValue = ew_HtmlEncode($this->_2_Problemas_fuerza_publica->AdvancedSearch->SearchValue);
			$this->_2_Problemas_fuerza_publica->PlaceHolder = ew_RemoveHtml($this->_2_Problemas_fuerza_publica->FldCaption());

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->EditAttrs["class"] = "form-control";
			$this->_2_Sin_seguridad->EditCustomAttributes = "";
			$this->_2_Sin_seguridad->EditValue = ew_HtmlEncode($this->_2_Sin_seguridad->AdvancedSearch->SearchValue);
			$this->_2_Sin_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Sin_seguridad->FldCaption());

			// 3_AEI_controlado
			$this->_3_AEI_controlado->EditAttrs["class"] = "form-control";
			$this->_3_AEI_controlado->EditCustomAttributes = "";
			$this->_3_AEI_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_controlado->AdvancedSearch->SearchValue);
			$this->_3_AEI_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_controlado->FldCaption());

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->EditAttrs["class"] = "form-control";
			$this->_3_AEI_no_controlado->EditCustomAttributes = "";
			$this->_3_AEI_no_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_no_controlado->AdvancedSearch->SearchValue);
			$this->_3_AEI_no_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_no_controlado->FldCaption());

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditAttrs["class"] = "form-control";
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditCustomAttributes = "";
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->SearchValue);
			$this->_3_Bloqueo_parcial_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_parcial_de_la_comunidad->FldCaption());

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->EditAttrs["class"] = "form-control";
			$this->_3_Bloqueo_total_de_la_comunidad->EditCustomAttributes = "";
			$this->_3_Bloqueo_total_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->SearchValue);
			$this->_3_Bloqueo_total_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_total_de_la_comunidad->FldCaption());

			// 3_Combate
			$this->_3_Combate->EditAttrs["class"] = "form-control";
			$this->_3_Combate->EditCustomAttributes = "";
			$this->_3_Combate->EditValue = ew_HtmlEncode($this->_3_Combate->AdvancedSearch->SearchValue);
			$this->_3_Combate->PlaceHolder = ew_RemoveHtml($this->_3_Combate->FldCaption());

			// 3_Hostigamiento
			$this->_3_Hostigamiento->EditAttrs["class"] = "form-control";
			$this->_3_Hostigamiento->EditCustomAttributes = "";
			$this->_3_Hostigamiento->EditValue = ew_HtmlEncode($this->_3_Hostigamiento->AdvancedSearch->SearchValue);
			$this->_3_Hostigamiento->PlaceHolder = ew_RemoveHtml($this->_3_Hostigamiento->FldCaption());

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->EditAttrs["class"] = "form-control";
			$this->_3_MAP_Controlada->EditCustomAttributes = "";
			$this->_3_MAP_Controlada->EditValue = ew_HtmlEncode($this->_3_MAP_Controlada->AdvancedSearch->SearchValue);
			$this->_3_MAP_Controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_Controlada->FldCaption());

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->EditAttrs["class"] = "form-control";
			$this->_3_MAP_No_controlada->EditCustomAttributes = "";
			$this->_3_MAP_No_controlada->EditValue = ew_HtmlEncode($this->_3_MAP_No_controlada->AdvancedSearch->SearchValue);
			$this->_3_MAP_No_controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_No_controlada->FldCaption());

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->EditAttrs["class"] = "form-control";
			$this->_3_Operaciones_de_seguridad->EditCustomAttributes = "";
			$this->_3_Operaciones_de_seguridad->EditValue = ew_HtmlEncode($this->_3_Operaciones_de_seguridad->AdvancedSearch->SearchValue);
			$this->_3_Operaciones_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_3_Operaciones_de_seguridad->FldCaption());

			// 4_Epidemia
			$this->_4_Epidemia->EditAttrs["class"] = "form-control";
			$this->_4_Epidemia->EditCustomAttributes = "";
			$this->_4_Epidemia->EditValue = ew_HtmlEncode($this->_4_Epidemia->AdvancedSearch->SearchValue);
			$this->_4_Epidemia->PlaceHolder = ew_RemoveHtml($this->_4_Epidemia->FldCaption());

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->EditAttrs["class"] = "form-control";
			$this->_4_Novedad_climatologica->EditCustomAttributes = "";
			$this->_4_Novedad_climatologica->EditValue = ew_HtmlEncode($this->_4_Novedad_climatologica->AdvancedSearch->SearchValue);
			$this->_4_Novedad_climatologica->PlaceHolder = ew_RemoveHtml($this->_4_Novedad_climatologica->FldCaption());

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->EditAttrs["class"] = "form-control";
			$this->_4_Registro_de_cultivos->EditCustomAttributes = "";
			$this->_4_Registro_de_cultivos->EditValue = ew_HtmlEncode($this->_4_Registro_de_cultivos->AdvancedSearch->SearchValue);
			$this->_4_Registro_de_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Registro_de_cultivos->FldCaption());

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_con_cultivos_muy_dispersos->EditCustomAttributes = "";
			$this->_4_Zona_con_cultivos_muy_dispersos->EditValue = ew_HtmlEncode($this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->SearchValue);
			$this->_4_Zona_con_cultivos_muy_dispersos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_con_cultivos_muy_dispersos->FldCaption());

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditCustomAttributes = "";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue = ew_HtmlEncode($this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->SearchValue);
			$this->_4_Zona_de_cruce_de_rios_caudalosos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption());

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_sin_cultivos->EditCustomAttributes = "";
			$this->_4_Zona_sin_cultivos->EditValue = ew_HtmlEncode($this->_4_Zona_sin_cultivos->AdvancedSearch->SearchValue);
			$this->_4_Zona_sin_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_sin_cultivos->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->Punto->AdvancedSearch->Load();
		$this->Total_general->AdvancedSearch->Load();
		$this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->Load();
		$this->_1_Descanso_en_dia_habil->AdvancedSearch->Load();
		$this->_1_Descanso_festivo_dominical->AdvancedSearch->Load();
		$this->_1_Dia_compensatorio->AdvancedSearch->Load();
		$this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->Load();
		$this->_1_Espera_helicoptero_Helistar->AdvancedSearch->Load();
		$this->_1_Extraccion->AdvancedSearch->Load();
		$this->_1_Firma_contrato_GME->AdvancedSearch->Load();
		$this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->Load();
		$this->_1_Insercion->AdvancedSearch->Load();
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->Load();
		$this->_1_Novedad_apoyo_zonal->AdvancedSearch->Load();
		$this->_1_Novedad_enfermero->AdvancedSearch->Load();
		$this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->Load();
		$this->_1_Transporte_bus->AdvancedSearch->Load();
		$this->_1_Traslado_apoyo_zonal->AdvancedSearch->Load();
		$this->_1_Traslado_area_vivac->AdvancedSearch->Load();
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->Load();
		$this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->Load();
		$this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->Load();
		$this->_2_Induccion_FP->AdvancedSearch->Load();
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->Load();
		$this->_2_Problemas_fuerza_publica->AdvancedSearch->Load();
		$this->_2_Sin_seguridad->AdvancedSearch->Load();
		$this->_3_AEI_controlado->AdvancedSearch->Load();
		$this->_3_AEI_no_controlado->AdvancedSearch->Load();
		$this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->Load();
		$this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->Load();
		$this->_3_Combate->AdvancedSearch->Load();
		$this->_3_Hostigamiento->AdvancedSearch->Load();
		$this->_3_MAP_Controlada->AdvancedSearch->Load();
		$this->_3_MAP_No_controlada->AdvancedSearch->Load();
		$this->_3_Operaciones_de_seguridad->AdvancedSearch->Load();
		$this->_4_Epidemia->AdvancedSearch->Load();
		$this->_4_Novedad_climatologica->AdvancedSearch->Load();
		$this->_4_Registro_de_cultivos->AdvancedSearch->Load();
		$this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->Load();
		$this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->Load();
		$this->_4_Zona_sin_cultivos->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_grafica_capacidad_efectiva\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_grafica_capacidad_efectiva',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fgrafica_capacidad_efectivalist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($grafica_capacidad_efectiva_list)) $grafica_capacidad_efectiva_list = new cgrafica_capacidad_efectiva_list();

// Page init
$grafica_capacidad_efectiva_list->Page_Init();

// Page main
$grafica_capacidad_efectiva_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$grafica_capacidad_efectiva_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<script type="text/javascript">

// Page object
var grafica_capacidad_efectiva_list = new ew_Page("grafica_capacidad_efectiva_list");
grafica_capacidad_efectiva_list.PageID = "list"; // Page ID
var EW_PAGE_ID = grafica_capacidad_efectiva_list.PageID; // For backward compatibility

// Form object
var fgrafica_capacidad_efectivalist = new ew_Form("fgrafica_capacidad_efectivalist");
fgrafica_capacidad_efectivalist.FormKeyCountName = '<?php echo $grafica_capacidad_efectiva_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgrafica_capacidad_efectivalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_capacidad_efectivalist.ValidateRequired = true;
<?php } else { ?>
fgrafica_capacidad_efectivalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fgrafica_capacidad_efectivalistsrch = new ew_Form("fgrafica_capacidad_efectivalistsrch");

// Validate function for search
fgrafica_capacidad_efectivalistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fgrafica_capacidad_efectivalistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_capacidad_efectivalistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgrafica_capacidad_efectivalistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<div class="ewToolbar">
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($grafica_capacidad_efectiva_list->TotalRecs > 0 && $grafica_capacidad_efectiva_list->ExportOptions->Visible()) { ?>
<?php $grafica_capacidad_efectiva_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($grafica_capacidad_efectiva_list->SearchOptions->Visible()) { ?>
<?php $grafica_capacidad_efectiva_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($grafica_capacidad_efectiva_list->TotalRecs <= 0)
			$grafica_capacidad_efectiva_list->TotalRecs = $grafica_capacidad_efectiva->SelectRecordCount();
	} else {
		if (!$grafica_capacidad_efectiva_list->Recordset && ($grafica_capacidad_efectiva_list->Recordset = $grafica_capacidad_efectiva_list->LoadRecordset()))
			$grafica_capacidad_efectiva_list->TotalRecs = $grafica_capacidad_efectiva_list->Recordset->RecordCount();
	}
	$grafica_capacidad_efectiva_list->StartRec = 1;
	if ($grafica_capacidad_efectiva_list->DisplayRecs <= 0 || ($grafica_capacidad_efectiva->Export <> "" && $grafica_capacidad_efectiva->ExportAll)) // Display all records
		$grafica_capacidad_efectiva_list->DisplayRecs = $grafica_capacidad_efectiva_list->TotalRecs;
	if (!($grafica_capacidad_efectiva->Export <> "" && $grafica_capacidad_efectiva->ExportAll))
		$grafica_capacidad_efectiva_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$grafica_capacidad_efectiva_list->Recordset = $grafica_capacidad_efectiva_list->LoadRecordset($grafica_capacidad_efectiva_list->StartRec-1, $grafica_capacidad_efectiva_list->DisplayRecs);

	// Set no record found message
	if ($grafica_capacidad_efectiva->CurrentAction == "" && $grafica_capacidad_efectiva_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$grafica_capacidad_efectiva_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($grafica_capacidad_efectiva_list->SearchWhere == "0=101")
			$grafica_capacidad_efectiva_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$grafica_capacidad_efectiva_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$grafica_capacidad_efectiva_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($grafica_capacidad_efectiva->Export == "" && $grafica_capacidad_efectiva->CurrentAction == "") { ?>
<form name="fgrafica_capacidad_efectivalistsrch" id="fgrafica_capacidad_efectivalistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($grafica_capacidad_efectiva_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fgrafica_capacidad_efectivalistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="grafica_capacidad_efectiva">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$grafica_capacidad_efectiva_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$grafica_capacidad_efectiva->RowType = EW_ROWTYPE_SEARCH;

// Render row
$grafica_capacidad_efectiva->ResetAttrs();
$grafica_capacidad_efectiva_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($grafica_capacidad_efectiva->Punto->Visible) { // Punto ?>
	<div id="xsc_Punto" class="ewCell form-group">
		<label for="x_Punto" class="ewSearchCaption ewLabel"><?php echo $grafica_capacidad_efectiva->Punto->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Punto" id="z_Punto" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Punto" name="x_Punto" id="x_Punto" size="35" placeholder="<?php echo ew_HtmlEncode($grafica_capacidad_efectiva->Punto->PlaceHolder) ?>" value="<?php echo $grafica_capacidad_efectiva->Punto->EditValue ?>"<?php echo $grafica_capacidad_efectiva->Punto->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $grafica_capacidad_efectiva_list->ShowPageHeader(); ?>
<?php
$grafica_capacidad_efectiva_list->ShowMessage();
?>
<?php if ($grafica_capacidad_efectiva_list->TotalRecs > 0 || $grafica_capacidad_efectiva->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($grafica_capacidad_efectiva->CurrentAction <> "gridadd" && $grafica_capacidad_efectiva->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_capacidad_efectiva_list->Pager)) $grafica_capacidad_efectiva_list->Pager = new cPrevNextPager($grafica_capacidad_efectiva_list->StartRec, $grafica_capacidad_efectiva_list->DisplayRecs, $grafica_capacidad_efectiva_list->TotalRecs) ?>
<?php if ($grafica_capacidad_efectiva_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_capacidad_efectiva_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_capacidad_efectiva_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fgrafica_capacidad_efectivalist" id="fgrafica_capacidad_efectivalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($grafica_capacidad_efectiva_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $grafica_capacidad_efectiva_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="grafica_capacidad_efectiva">
<div id="gmp_grafica_capacidad_efectiva" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($grafica_capacidad_efectiva_list->TotalRecs > 0) { ?>
<table id="tbl_grafica_capacidad_efectivalist" class="table ewTable">
<?php echo $grafica_capacidad_efectiva->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$grafica_capacidad_efectiva->RowType = EW_ROWTYPE_HEADER;

// Render list options
$grafica_capacidad_efectiva_list->RenderListOptions();

// Render list options (header, left)
$grafica_capacidad_efectiva_list->ListOptions->Render("header", "left");
?>
<?php if ($grafica_capacidad_efectiva->Punto->Visible) { // Punto ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->Punto) == "") { ?>
		<th data-name="Punto"><div id="elh_grafica_capacidad_efectiva_Punto" class="grafica_capacidad_efectiva_Punto"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->Punto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Punto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->Punto) ?>',2);"><div id="elh_grafica_capacidad_efectiva_Punto" class="grafica_capacidad_efectiva_Punto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->Punto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->Punto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->Punto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->Total_general->Visible) { // Total_general ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->Total_general) == "") { ?>
		<th data-name="Total_general"><div id="elh_grafica_capacidad_efectiva_Total_general" class="grafica_capacidad_efectiva_Total_general"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->Total_general->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_general"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->Total_general) ?>',2);"><div id="elh_grafica_capacidad_efectiva_Total_general" class="grafica_capacidad_efectiva_Total_general">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->Total_general->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->Total_general->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->Total_general->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->Visible) { // 1_Apoyo_zonal_sin_punto_asignado ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado) == "") { ?>
		<th data-name="_1_Apoyo_zonal_sin_punto_asignado"><div id="elh_grafica_capacidad_efectiva__1_Apoyo_zonal_sin_punto_asignado" class="grafica_capacidad_efectiva__1_Apoyo_zonal_sin_punto_asignado"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Apoyo_zonal_sin_punto_asignado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Apoyo_zonal_sin_punto_asignado" class="grafica_capacidad_efectiva__1_Apoyo_zonal_sin_punto_asignado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->Visible) { // 1_Descanso_en_dia_habil ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Descanso_en_dia_habil) == "") { ?>
		<th data-name="_1_Descanso_en_dia_habil"><div id="elh_grafica_capacidad_efectiva__1_Descanso_en_dia_habil" class="grafica_capacidad_efectiva__1_Descanso_en_dia_habil"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Descanso_en_dia_habil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Descanso_en_dia_habil) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Descanso_en_dia_habil" class="grafica_capacidad_efectiva__1_Descanso_en_dia_habil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->Visible) { // 1_Descanso_festivo_dominical ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Descanso_festivo_dominical) == "") { ?>
		<th data-name="_1_Descanso_festivo_dominical"><div id="elh_grafica_capacidad_efectiva__1_Descanso_festivo_dominical" class="grafica_capacidad_efectiva__1_Descanso_festivo_dominical"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Descanso_festivo_dominical"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Descanso_festivo_dominical) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Descanso_festivo_dominical" class="grafica_capacidad_efectiva__1_Descanso_festivo_dominical">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Dia_compensatorio->Visible) { // 1_Dia_compensatorio ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Dia_compensatorio) == "") { ?>
		<th data-name="_1_Dia_compensatorio"><div id="elh_grafica_capacidad_efectiva__1_Dia_compensatorio" class="grafica_capacidad_efectiva__1_Dia_compensatorio"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Dia_compensatorio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Dia_compensatorio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Dia_compensatorio) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Dia_compensatorio" class="grafica_capacidad_efectiva__1_Dia_compensatorio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Dia_compensatorio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Dia_compensatorio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Dia_compensatorio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->Visible) { // 1_Erradicacion_en_dia_festivo ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo) == "") { ?>
		<th data-name="_1_Erradicacion_en_dia_festivo"><div id="elh_grafica_capacidad_efectiva__1_Erradicacion_en_dia_festivo" class="grafica_capacidad_efectiva__1_Erradicacion_en_dia_festivo"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Erradicacion_en_dia_festivo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Erradicacion_en_dia_festivo" class="grafica_capacidad_efectiva__1_Erradicacion_en_dia_festivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->Visible) { // 1_Espera_helicoptero_Helistar ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar) == "") { ?>
		<th data-name="_1_Espera_helicoptero_Helistar"><div id="elh_grafica_capacidad_efectiva__1_Espera_helicoptero_Helistar" class="grafica_capacidad_efectiva__1_Espera_helicoptero_Helistar"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Espera_helicoptero_Helistar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Espera_helicoptero_Helistar" class="grafica_capacidad_efectiva__1_Espera_helicoptero_Helistar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Extraccion->Visible) { // 1_Extraccion ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Extraccion) == "") { ?>
		<th data-name="_1_Extraccion"><div id="elh_grafica_capacidad_efectiva__1_Extraccion" class="grafica_capacidad_efectiva__1_Extraccion"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Extraccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Extraccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Extraccion) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Extraccion" class="grafica_capacidad_efectiva__1_Extraccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Extraccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Extraccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Extraccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Firma_contrato_GME->Visible) { // 1_Firma_contrato_GME ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Firma_contrato_GME) == "") { ?>
		<th data-name="_1_Firma_contrato_GME"><div id="elh_grafica_capacidad_efectiva__1_Firma_contrato_GME" class="grafica_capacidad_efectiva__1_Firma_contrato_GME"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Firma_contrato_GME->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Firma_contrato_GME"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Firma_contrato_GME) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Firma_contrato_GME" class="grafica_capacidad_efectiva__1_Firma_contrato_GME">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Firma_contrato_GME->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Firma_contrato_GME->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Firma_contrato_GME->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->Visible) { // 1_Induccion_Apoyo_Zonal ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal) == "") { ?>
		<th data-name="_1_Induccion_Apoyo_Zonal"><div id="elh_grafica_capacidad_efectiva__1_Induccion_Apoyo_Zonal" class="grafica_capacidad_efectiva__1_Induccion_Apoyo_Zonal"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Induccion_Apoyo_Zonal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Induccion_Apoyo_Zonal" class="grafica_capacidad_efectiva__1_Induccion_Apoyo_Zonal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Insercion->Visible) { // 1_Insercion ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Insercion) == "") { ?>
		<th data-name="_1_Insercion"><div id="elh_grafica_capacidad_efectiva__1_Insercion" class="grafica_capacidad_efectiva__1_Insercion"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Insercion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Insercion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Insercion) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Insercion" class="grafica_capacidad_efectiva__1_Insercion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Insercion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Insercion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Insercion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Visible) { // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) == "") { ?>
		<th data-name="_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"><div id="elh_grafica_capacidad_efectiva__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="grafica_capacidad_efectiva__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="grafica_capacidad_efectiva__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->Visible) { // 1_Novedad_apoyo_zonal ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal) == "") { ?>
		<th data-name="_1_Novedad_apoyo_zonal"><div id="elh_grafica_capacidad_efectiva__1_Novedad_apoyo_zonal" class="grafica_capacidad_efectiva__1_Novedad_apoyo_zonal"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Novedad_apoyo_zonal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Novedad_apoyo_zonal" class="grafica_capacidad_efectiva__1_Novedad_apoyo_zonal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Novedad_enfermero->Visible) { // 1_Novedad_enfermero ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Novedad_enfermero) == "") { ?>
		<th data-name="_1_Novedad_enfermero"><div id="elh_grafica_capacidad_efectiva__1_Novedad_enfermero" class="grafica_capacidad_efectiva__1_Novedad_enfermero"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Novedad_enfermero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Novedad_enfermero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Novedad_enfermero) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Novedad_enfermero" class="grafica_capacidad_efectiva__1_Novedad_enfermero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Novedad_enfermero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Novedad_enfermero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Novedad_enfermero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->Visible) { // 1_Punto_fuera_del_area_de_erradicacion ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion) == "") { ?>
		<th data-name="_1_Punto_fuera_del_area_de_erradicacion"><div id="elh_grafica_capacidad_efectiva__1_Punto_fuera_del_area_de_erradicacion" class="grafica_capacidad_efectiva__1_Punto_fuera_del_area_de_erradicacion"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Punto_fuera_del_area_de_erradicacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Punto_fuera_del_area_de_erradicacion" class="grafica_capacidad_efectiva__1_Punto_fuera_del_area_de_erradicacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Transporte_bus->Visible) { // 1_Transporte_bus ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Transporte_bus) == "") { ?>
		<th data-name="_1_Transporte_bus"><div id="elh_grafica_capacidad_efectiva__1_Transporte_bus" class="grafica_capacidad_efectiva__1_Transporte_bus"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Transporte_bus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Transporte_bus"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Transporte_bus) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Transporte_bus" class="grafica_capacidad_efectiva__1_Transporte_bus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Transporte_bus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Transporte_bus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Transporte_bus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->Visible) { // 1_Traslado_apoyo_zonal ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal) == "") { ?>
		<th data-name="_1_Traslado_apoyo_zonal"><div id="elh_grafica_capacidad_efectiva__1_Traslado_apoyo_zonal" class="grafica_capacidad_efectiva__1_Traslado_apoyo_zonal"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Traslado_apoyo_zonal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Traslado_apoyo_zonal" class="grafica_capacidad_efectiva__1_Traslado_apoyo_zonal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_1_Traslado_area_vivac->Visible) { // 1_Traslado_area_vivac ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Traslado_area_vivac) == "") { ?>
		<th data-name="_1_Traslado_area_vivac"><div id="elh_grafica_capacidad_efectiva__1_Traslado_area_vivac" class="grafica_capacidad_efectiva__1_Traslado_area_vivac"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Traslado_area_vivac->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Traslado_area_vivac"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_1_Traslado_area_vivac) ?>',2);"><div id="elh_grafica_capacidad_efectiva__1_Traslado_area_vivac" class="grafica_capacidad_efectiva__1_Traslado_area_vivac">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_1_Traslado_area_vivac->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_1_Traslado_area_vivac->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_1_Traslado_area_vivac->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->Visible) { // 2_A_la_espera_definicion_nuevo_punto_FP ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP) == "") { ?>
		<th data-name="_2_A_la_espera_definicion_nuevo_punto_FP"><div id="elh_grafica_capacidad_efectiva__2_A_la_espera_definicion_nuevo_punto_FP" class="grafica_capacidad_efectiva__2_A_la_espera_definicion_nuevo_punto_FP"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_A_la_espera_definicion_nuevo_punto_FP"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP) ?>',2);"><div id="elh_grafica_capacidad_efectiva__2_A_la_espera_definicion_nuevo_punto_FP" class="grafica_capacidad_efectiva__2_A_la_espera_definicion_nuevo_punto_FP">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->Visible) { // 2_Espera_helicoptero_FP_de_seguridad ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad) == "") { ?>
		<th data-name="_2_Espera_helicoptero_FP_de_seguridad"><div id="elh_grafica_capacidad_efectiva__2_Espera_helicoptero_FP_de_seguridad" class="grafica_capacidad_efectiva__2_Espera_helicoptero_FP_de_seguridad"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Espera_helicoptero_FP_de_seguridad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad) ?>',2);"><div id="elh_grafica_capacidad_efectiva__2_Espera_helicoptero_FP_de_seguridad" class="grafica_capacidad_efectiva__2_Espera_helicoptero_FP_de_seguridad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->Visible) { // 2_Espera_helicoptero_FP_que_abastece ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece) == "") { ?>
		<th data-name="_2_Espera_helicoptero_FP_que_abastece"><div id="elh_grafica_capacidad_efectiva__2_Espera_helicoptero_FP_que_abastece" class="grafica_capacidad_efectiva__2_Espera_helicoptero_FP_que_abastece"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Espera_helicoptero_FP_que_abastece"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece) ?>',2);"><div id="elh_grafica_capacidad_efectiva__2_Espera_helicoptero_FP_que_abastece" class="grafica_capacidad_efectiva__2_Espera_helicoptero_FP_que_abastece">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_2_Induccion_FP->Visible) { // 2_Induccion_FP ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Induccion_FP) == "") { ?>
		<th data-name="_2_Induccion_FP"><div id="elh_grafica_capacidad_efectiva__2_Induccion_FP" class="grafica_capacidad_efectiva__2_Induccion_FP"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Induccion_FP->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Induccion_FP"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Induccion_FP) ?>',2);"><div id="elh_grafica_capacidad_efectiva__2_Induccion_FP" class="grafica_capacidad_efectiva__2_Induccion_FP">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Induccion_FP->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_2_Induccion_FP->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_2_Induccion_FP->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->Visible) { // 2_Novedad_canino_o_del_grupo_de_deteccion ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion) == "") { ?>
		<th data-name="_2_Novedad_canino_o_del_grupo_de_deteccion"><div id="elh_grafica_capacidad_efectiva__2_Novedad_canino_o_del_grupo_de_deteccion" class="grafica_capacidad_efectiva__2_Novedad_canino_o_del_grupo_de_deteccion"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Novedad_canino_o_del_grupo_de_deteccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion) ?>',2);"><div id="elh_grafica_capacidad_efectiva__2_Novedad_canino_o_del_grupo_de_deteccion" class="grafica_capacidad_efectiva__2_Novedad_canino_o_del_grupo_de_deteccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->Visible) { // 2_Problemas_fuerza_publica ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Problemas_fuerza_publica) == "") { ?>
		<th data-name="_2_Problemas_fuerza_publica"><div id="elh_grafica_capacidad_efectiva__2_Problemas_fuerza_publica" class="grafica_capacidad_efectiva__2_Problemas_fuerza_publica"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Problemas_fuerza_publica"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Problemas_fuerza_publica) ?>',2);"><div id="elh_grafica_capacidad_efectiva__2_Problemas_fuerza_publica" class="grafica_capacidad_efectiva__2_Problemas_fuerza_publica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_2_Sin_seguridad->Visible) { // 2_Sin_seguridad ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Sin_seguridad) == "") { ?>
		<th data-name="_2_Sin_seguridad"><div id="elh_grafica_capacidad_efectiva__2_Sin_seguridad" class="grafica_capacidad_efectiva__2_Sin_seguridad"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Sin_seguridad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Sin_seguridad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_2_Sin_seguridad) ?>',2);"><div id="elh_grafica_capacidad_efectiva__2_Sin_seguridad" class="grafica_capacidad_efectiva__2_Sin_seguridad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_2_Sin_seguridad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_2_Sin_seguridad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_2_Sin_seguridad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_AEI_controlado->Visible) { // 3_AEI_controlado ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_AEI_controlado) == "") { ?>
		<th data-name="_3_AEI_controlado"><div id="elh_grafica_capacidad_efectiva__3_AEI_controlado" class="grafica_capacidad_efectiva__3_AEI_controlado"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_AEI_controlado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_AEI_controlado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_AEI_controlado) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_AEI_controlado" class="grafica_capacidad_efectiva__3_AEI_controlado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_AEI_controlado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_AEI_controlado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_AEI_controlado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_AEI_no_controlado->Visible) { // 3_AEI_no_controlado ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_AEI_no_controlado) == "") { ?>
		<th data-name="_3_AEI_no_controlado"><div id="elh_grafica_capacidad_efectiva__3_AEI_no_controlado" class="grafica_capacidad_efectiva__3_AEI_no_controlado"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_AEI_no_controlado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_AEI_no_controlado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_AEI_no_controlado) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_AEI_no_controlado" class="grafica_capacidad_efectiva__3_AEI_no_controlado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_AEI_no_controlado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_AEI_no_controlado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_AEI_no_controlado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->Visible) { // 3_Bloqueo_parcial_de_la_comunidad ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad) == "") { ?>
		<th data-name="_3_Bloqueo_parcial_de_la_comunidad"><div id="elh_grafica_capacidad_efectiva__3_Bloqueo_parcial_de_la_comunidad" class="grafica_capacidad_efectiva__3_Bloqueo_parcial_de_la_comunidad"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Bloqueo_parcial_de_la_comunidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_Bloqueo_parcial_de_la_comunidad" class="grafica_capacidad_efectiva__3_Bloqueo_parcial_de_la_comunidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->Visible) { // 3_Bloqueo_total_de_la_comunidad ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad) == "") { ?>
		<th data-name="_3_Bloqueo_total_de_la_comunidad"><div id="elh_grafica_capacidad_efectiva__3_Bloqueo_total_de_la_comunidad" class="grafica_capacidad_efectiva__3_Bloqueo_total_de_la_comunidad"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Bloqueo_total_de_la_comunidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_Bloqueo_total_de_la_comunidad" class="grafica_capacidad_efectiva__3_Bloqueo_total_de_la_comunidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_Combate->Visible) { // 3_Combate ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Combate) == "") { ?>
		<th data-name="_3_Combate"><div id="elh_grafica_capacidad_efectiva__3_Combate" class="grafica_capacidad_efectiva__3_Combate"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Combate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Combate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Combate) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_Combate" class="grafica_capacidad_efectiva__3_Combate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Combate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_Combate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_Combate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_Hostigamiento->Visible) { // 3_Hostigamiento ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Hostigamiento) == "") { ?>
		<th data-name="_3_Hostigamiento"><div id="elh_grafica_capacidad_efectiva__3_Hostigamiento" class="grafica_capacidad_efectiva__3_Hostigamiento"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Hostigamiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Hostigamiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Hostigamiento) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_Hostigamiento" class="grafica_capacidad_efectiva__3_Hostigamiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Hostigamiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_Hostigamiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_Hostigamiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_MAP_Controlada->Visible) { // 3_MAP_Controlada ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_MAP_Controlada) == "") { ?>
		<th data-name="_3_MAP_Controlada"><div id="elh_grafica_capacidad_efectiva__3_MAP_Controlada" class="grafica_capacidad_efectiva__3_MAP_Controlada"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_MAP_Controlada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_MAP_Controlada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_MAP_Controlada) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_MAP_Controlada" class="grafica_capacidad_efectiva__3_MAP_Controlada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_MAP_Controlada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_MAP_Controlada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_MAP_Controlada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_MAP_No_controlada->Visible) { // 3_MAP_No_controlada ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_MAP_No_controlada) == "") { ?>
		<th data-name="_3_MAP_No_controlada"><div id="elh_grafica_capacidad_efectiva__3_MAP_No_controlada" class="grafica_capacidad_efectiva__3_MAP_No_controlada"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_MAP_No_controlada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_MAP_No_controlada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_MAP_No_controlada) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_MAP_No_controlada" class="grafica_capacidad_efectiva__3_MAP_No_controlada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_MAP_No_controlada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_MAP_No_controlada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_MAP_No_controlada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->Visible) { // 3_Operaciones_de_seguridad ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Operaciones_de_seguridad) == "") { ?>
		<th data-name="_3_Operaciones_de_seguridad"><div id="elh_grafica_capacidad_efectiva__3_Operaciones_de_seguridad" class="grafica_capacidad_efectiva__3_Operaciones_de_seguridad"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Operaciones_de_seguridad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_3_Operaciones_de_seguridad) ?>',2);"><div id="elh_grafica_capacidad_efectiva__3_Operaciones_de_seguridad" class="grafica_capacidad_efectiva__3_Operaciones_de_seguridad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_4_Epidemia->Visible) { // 4_Epidemia ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Epidemia) == "") { ?>
		<th data-name="_4_Epidemia"><div id="elh_grafica_capacidad_efectiva__4_Epidemia" class="grafica_capacidad_efectiva__4_Epidemia"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Epidemia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Epidemia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Epidemia) ?>',2);"><div id="elh_grafica_capacidad_efectiva__4_Epidemia" class="grafica_capacidad_efectiva__4_Epidemia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Epidemia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_4_Epidemia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_4_Epidemia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_4_Novedad_climatologica->Visible) { // 4_Novedad_climatologica ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Novedad_climatologica) == "") { ?>
		<th data-name="_4_Novedad_climatologica"><div id="elh_grafica_capacidad_efectiva__4_Novedad_climatologica" class="grafica_capacidad_efectiva__4_Novedad_climatologica"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Novedad_climatologica->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Novedad_climatologica"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Novedad_climatologica) ?>',2);"><div id="elh_grafica_capacidad_efectiva__4_Novedad_climatologica" class="grafica_capacidad_efectiva__4_Novedad_climatologica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Novedad_climatologica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_4_Novedad_climatologica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_4_Novedad_climatologica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_4_Registro_de_cultivos->Visible) { // 4_Registro_de_cultivos ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Registro_de_cultivos) == "") { ?>
		<th data-name="_4_Registro_de_cultivos"><div id="elh_grafica_capacidad_efectiva__4_Registro_de_cultivos" class="grafica_capacidad_efectiva__4_Registro_de_cultivos"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Registro_de_cultivos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Registro_de_cultivos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Registro_de_cultivos) ?>',2);"><div id="elh_grafica_capacidad_efectiva__4_Registro_de_cultivos" class="grafica_capacidad_efectiva__4_Registro_de_cultivos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Registro_de_cultivos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_4_Registro_de_cultivos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_4_Registro_de_cultivos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->Visible) { // 4_Zona_con_cultivos_muy_dispersos ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos) == "") { ?>
		<th data-name="_4_Zona_con_cultivos_muy_dispersos"><div id="elh_grafica_capacidad_efectiva__4_Zona_con_cultivos_muy_dispersos" class="grafica_capacidad_efectiva__4_Zona_con_cultivos_muy_dispersos"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Zona_con_cultivos_muy_dispersos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos) ?>',2);"><div id="elh_grafica_capacidad_efectiva__4_Zona_con_cultivos_muy_dispersos" class="grafica_capacidad_efectiva__4_Zona_con_cultivos_muy_dispersos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->Visible) { // 4_Zona_de_cruce_de_rios_caudalosos ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos) == "") { ?>
		<th data-name="_4_Zona_de_cruce_de_rios_caudalosos"><div id="elh_grafica_capacidad_efectiva__4_Zona_de_cruce_de_rios_caudalosos" class="grafica_capacidad_efectiva__4_Zona_de_cruce_de_rios_caudalosos"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Zona_de_cruce_de_rios_caudalosos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos) ?>',2);"><div id="elh_grafica_capacidad_efectiva__4_Zona_de_cruce_de_rios_caudalosos" class="grafica_capacidad_efectiva__4_Zona_de_cruce_de_rios_caudalosos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_capacidad_efectiva->_4_Zona_sin_cultivos->Visible) { // 4_Zona_sin_cultivos ?>
	<?php if ($grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Zona_sin_cultivos) == "") { ?>
		<th data-name="_4_Zona_sin_cultivos"><div id="elh_grafica_capacidad_efectiva__4_Zona_sin_cultivos" class="grafica_capacidad_efectiva__4_Zona_sin_cultivos"><div class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Zona_sin_cultivos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Zona_sin_cultivos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_capacidad_efectiva->SortUrl($grafica_capacidad_efectiva->_4_Zona_sin_cultivos) ?>',2);"><div id="elh_grafica_capacidad_efectiva__4_Zona_sin_cultivos" class="grafica_capacidad_efectiva__4_Zona_sin_cultivos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_capacidad_efectiva->_4_Zona_sin_cultivos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_capacidad_efectiva->_4_Zona_sin_cultivos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_capacidad_efectiva->_4_Zona_sin_cultivos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$grafica_capacidad_efectiva_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($grafica_capacidad_efectiva->ExportAll && $grafica_capacidad_efectiva->Export <> "") {
	$grafica_capacidad_efectiva_list->StopRec = $grafica_capacidad_efectiva_list->TotalRecs;
} else {

	// Set the last record to display
	if ($grafica_capacidad_efectiva_list->TotalRecs > $grafica_capacidad_efectiva_list->StartRec + $grafica_capacidad_efectiva_list->DisplayRecs - 1)
		$grafica_capacidad_efectiva_list->StopRec = $grafica_capacidad_efectiva_list->StartRec + $grafica_capacidad_efectiva_list->DisplayRecs - 1;
	else
		$grafica_capacidad_efectiva_list->StopRec = $grafica_capacidad_efectiva_list->TotalRecs;
}
$grafica_capacidad_efectiva_list->RecCnt = $grafica_capacidad_efectiva_list->StartRec - 1;
if ($grafica_capacidad_efectiva_list->Recordset && !$grafica_capacidad_efectiva_list->Recordset->EOF) {
	$grafica_capacidad_efectiva_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $grafica_capacidad_efectiva_list->StartRec > 1)
		$grafica_capacidad_efectiva_list->Recordset->Move($grafica_capacidad_efectiva_list->StartRec - 1);
} elseif (!$grafica_capacidad_efectiva->AllowAddDeleteRow && $grafica_capacidad_efectiva_list->StopRec == 0) {
	$grafica_capacidad_efectiva_list->StopRec = $grafica_capacidad_efectiva->GridAddRowCount;
}

// Initialize aggregate
$grafica_capacidad_efectiva->RowType = EW_ROWTYPE_AGGREGATEINIT;
$grafica_capacidad_efectiva->ResetAttrs();
$grafica_capacidad_efectiva_list->RenderRow();
while ($grafica_capacidad_efectiva_list->RecCnt < $grafica_capacidad_efectiva_list->StopRec) {
	$grafica_capacidad_efectiva_list->RecCnt++;
	if (intval($grafica_capacidad_efectiva_list->RecCnt) >= intval($grafica_capacidad_efectiva_list->StartRec)) {
		$grafica_capacidad_efectiva_list->RowCnt++;

		// Set up key count
		$grafica_capacidad_efectiva_list->KeyCount = $grafica_capacidad_efectiva_list->RowIndex;

		// Init row class and style
		$grafica_capacidad_efectiva->ResetAttrs();
		$grafica_capacidad_efectiva->CssClass = "";
		if ($grafica_capacidad_efectiva->CurrentAction == "gridadd") {
		} else {
			$grafica_capacidad_efectiva_list->LoadRowValues($grafica_capacidad_efectiva_list->Recordset); // Load row values
		}
		$grafica_capacidad_efectiva->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$grafica_capacidad_efectiva->RowAttrs = array_merge($grafica_capacidad_efectiva->RowAttrs, array('data-rowindex'=>$grafica_capacidad_efectiva_list->RowCnt, 'id'=>'r' . $grafica_capacidad_efectiva_list->RowCnt . '_grafica_capacidad_efectiva', 'data-rowtype'=>$grafica_capacidad_efectiva->RowType));

		// Render row
		$grafica_capacidad_efectiva_list->RenderRow();

		// Render list options
		$grafica_capacidad_efectiva_list->RenderListOptions();
?>
	<tr<?php echo $grafica_capacidad_efectiva->RowAttributes() ?>>
<?php

// Render list options (body, left)
$grafica_capacidad_efectiva_list->ListOptions->Render("body", "left", $grafica_capacidad_efectiva_list->RowCnt);
?>
	<?php if ($grafica_capacidad_efectiva->Punto->Visible) { // Punto ?>
		<td data-name="Punto"<?php echo $grafica_capacidad_efectiva->Punto->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->Punto->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->Punto->ListViewValue() ?></span>
<a id="<?php echo $grafica_capacidad_efectiva_list->PageObjName . "_row_" . $grafica_capacidad_efectiva_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->Total_general->Visible) { // Total_general ?>
		<td data-name="Total_general"<?php echo $grafica_capacidad_efectiva->Total_general->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->Total_general->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->Total_general->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->Visible) { // 1_Apoyo_zonal_sin_punto_asignado ?>
		<td data-name="_1_Apoyo_zonal_sin_punto_asignado"<?php echo $grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Apoyo_zonal_sin_punto_asignado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->Visible) { // 1_Descanso_en_dia_habil ?>
		<td data-name="_1_Descanso_en_dia_habil"<?php echo $grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Descanso_en_dia_habil->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->Visible) { // 1_Descanso_festivo_dominical ?>
		<td data-name="_1_Descanso_festivo_dominical"<?php echo $grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Descanso_festivo_dominical->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Dia_compensatorio->Visible) { // 1_Dia_compensatorio ?>
		<td data-name="_1_Dia_compensatorio"<?php echo $grafica_capacidad_efectiva->_1_Dia_compensatorio->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Dia_compensatorio->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Dia_compensatorio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->Visible) { // 1_Erradicacion_en_dia_festivo ?>
		<td data-name="_1_Erradicacion_en_dia_festivo"<?php echo $grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Erradicacion_en_dia_festivo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->Visible) { // 1_Espera_helicoptero_Helistar ?>
		<td data-name="_1_Espera_helicoptero_Helistar"<?php echo $grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Espera_helicoptero_Helistar->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Extraccion->Visible) { // 1_Extraccion ?>
		<td data-name="_1_Extraccion"<?php echo $grafica_capacidad_efectiva->_1_Extraccion->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Extraccion->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Extraccion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Firma_contrato_GME->Visible) { // 1_Firma_contrato_GME ?>
		<td data-name="_1_Firma_contrato_GME"<?php echo $grafica_capacidad_efectiva->_1_Firma_contrato_GME->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Firma_contrato_GME->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Firma_contrato_GME->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->Visible) { // 1_Induccion_Apoyo_Zonal ?>
		<td data-name="_1_Induccion_Apoyo_Zonal"<?php echo $grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Induccion_Apoyo_Zonal->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Insercion->Visible) { // 1_Insercion ?>
		<td data-name="_1_Insercion"<?php echo $grafica_capacidad_efectiva->_1_Insercion->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Insercion->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Insercion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Visible) { // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase ?>
		<td data-name="_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"<?php echo $grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->Visible) { // 1_Novedad_apoyo_zonal ?>
		<td data-name="_1_Novedad_apoyo_zonal"<?php echo $grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Novedad_apoyo_zonal->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Novedad_enfermero->Visible) { // 1_Novedad_enfermero ?>
		<td data-name="_1_Novedad_enfermero"<?php echo $grafica_capacidad_efectiva->_1_Novedad_enfermero->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Novedad_enfermero->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Novedad_enfermero->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->Visible) { // 1_Punto_fuera_del_area_de_erradicacion ?>
		<td data-name="_1_Punto_fuera_del_area_de_erradicacion"<?php echo $grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Punto_fuera_del_area_de_erradicacion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Transporte_bus->Visible) { // 1_Transporte_bus ?>
		<td data-name="_1_Transporte_bus"<?php echo $grafica_capacidad_efectiva->_1_Transporte_bus->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Transporte_bus->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Transporte_bus->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->Visible) { // 1_Traslado_apoyo_zonal ?>
		<td data-name="_1_Traslado_apoyo_zonal"<?php echo $grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Traslado_apoyo_zonal->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_1_Traslado_area_vivac->Visible) { // 1_Traslado_area_vivac ?>
		<td data-name="_1_Traslado_area_vivac"<?php echo $grafica_capacidad_efectiva->_1_Traslado_area_vivac->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_1_Traslado_area_vivac->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_1_Traslado_area_vivac->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->Visible) { // 2_A_la_espera_definicion_nuevo_punto_FP ?>
		<td data-name="_2_A_la_espera_definicion_nuevo_punto_FP"<?php echo $grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_2_A_la_espera_definicion_nuevo_punto_FP->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->Visible) { // 2_Espera_helicoptero_FP_de_seguridad ?>
		<td data-name="_2_Espera_helicoptero_FP_de_seguridad"<?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_de_seguridad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->Visible) { // 2_Espera_helicoptero_FP_que_abastece ?>
		<td data-name="_2_Espera_helicoptero_FP_que_abastece"<?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_2_Espera_helicoptero_FP_que_abastece->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_2_Induccion_FP->Visible) { // 2_Induccion_FP ?>
		<td data-name="_2_Induccion_FP"<?php echo $grafica_capacidad_efectiva->_2_Induccion_FP->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_2_Induccion_FP->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_2_Induccion_FP->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->Visible) { // 2_Novedad_canino_o_del_grupo_de_deteccion ?>
		<td data-name="_2_Novedad_canino_o_del_grupo_de_deteccion"<?php echo $grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_2_Novedad_canino_o_del_grupo_de_deteccion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->Visible) { // 2_Problemas_fuerza_publica ?>
		<td data-name="_2_Problemas_fuerza_publica"<?php echo $grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_2_Problemas_fuerza_publica->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_2_Sin_seguridad->Visible) { // 2_Sin_seguridad ?>
		<td data-name="_2_Sin_seguridad"<?php echo $grafica_capacidad_efectiva->_2_Sin_seguridad->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_2_Sin_seguridad->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_2_Sin_seguridad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_AEI_controlado->Visible) { // 3_AEI_controlado ?>
		<td data-name="_3_AEI_controlado"<?php echo $grafica_capacidad_efectiva->_3_AEI_controlado->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_AEI_controlado->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_AEI_controlado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_AEI_no_controlado->Visible) { // 3_AEI_no_controlado ?>
		<td data-name="_3_AEI_no_controlado"<?php echo $grafica_capacidad_efectiva->_3_AEI_no_controlado->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_AEI_no_controlado->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_AEI_no_controlado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->Visible) { // 3_Bloqueo_parcial_de_la_comunidad ?>
		<td data-name="_3_Bloqueo_parcial_de_la_comunidad"<?php echo $grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_Bloqueo_parcial_de_la_comunidad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->Visible) { // 3_Bloqueo_total_de_la_comunidad ?>
		<td data-name="_3_Bloqueo_total_de_la_comunidad"<?php echo $grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_Bloqueo_total_de_la_comunidad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_Combate->Visible) { // 3_Combate ?>
		<td data-name="_3_Combate"<?php echo $grafica_capacidad_efectiva->_3_Combate->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_Combate->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_Combate->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_Hostigamiento->Visible) { // 3_Hostigamiento ?>
		<td data-name="_3_Hostigamiento"<?php echo $grafica_capacidad_efectiva->_3_Hostigamiento->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_Hostigamiento->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_Hostigamiento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_MAP_Controlada->Visible) { // 3_MAP_Controlada ?>
		<td data-name="_3_MAP_Controlada"<?php echo $grafica_capacidad_efectiva->_3_MAP_Controlada->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_MAP_Controlada->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_MAP_Controlada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_MAP_No_controlada->Visible) { // 3_MAP_No_controlada ?>
		<td data-name="_3_MAP_No_controlada"<?php echo $grafica_capacidad_efectiva->_3_MAP_No_controlada->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_MAP_No_controlada->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_MAP_No_controlada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->Visible) { // 3_Operaciones_de_seguridad ?>
		<td data-name="_3_Operaciones_de_seguridad"<?php echo $grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_3_Operaciones_de_seguridad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_4_Epidemia->Visible) { // 4_Epidemia ?>
		<td data-name="_4_Epidemia"<?php echo $grafica_capacidad_efectiva->_4_Epidemia->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_4_Epidemia->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_4_Epidemia->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_4_Novedad_climatologica->Visible) { // 4_Novedad_climatologica ?>
		<td data-name="_4_Novedad_climatologica"<?php echo $grafica_capacidad_efectiva->_4_Novedad_climatologica->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_4_Novedad_climatologica->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_4_Novedad_climatologica->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_4_Registro_de_cultivos->Visible) { // 4_Registro_de_cultivos ?>
		<td data-name="_4_Registro_de_cultivos"<?php echo $grafica_capacidad_efectiva->_4_Registro_de_cultivos->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_4_Registro_de_cultivos->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_4_Registro_de_cultivos->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->Visible) { // 4_Zona_con_cultivos_muy_dispersos ?>
		<td data-name="_4_Zona_con_cultivos_muy_dispersos"<?php echo $grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_4_Zona_con_cultivos_muy_dispersos->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->Visible) { // 4_Zona_de_cruce_de_rios_caudalosos ?>
		<td data-name="_4_Zona_de_cruce_de_rios_caudalosos"<?php echo $grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_4_Zona_de_cruce_de_rios_caudalosos->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_capacidad_efectiva->_4_Zona_sin_cultivos->Visible) { // 4_Zona_sin_cultivos ?>
		<td data-name="_4_Zona_sin_cultivos"<?php echo $grafica_capacidad_efectiva->_4_Zona_sin_cultivos->CellAttributes() ?>>
<span<?php echo $grafica_capacidad_efectiva->_4_Zona_sin_cultivos->ViewAttributes() ?>>
<?php echo $grafica_capacidad_efectiva->_4_Zona_sin_cultivos->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$grafica_capacidad_efectiva_list->ListOptions->Render("body", "right", $grafica_capacidad_efectiva_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($grafica_capacidad_efectiva->CurrentAction <> "gridadd")
		$grafica_capacidad_efectiva_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($grafica_capacidad_efectiva->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($grafica_capacidad_efectiva_list->Recordset)
	$grafica_capacidad_efectiva_list->Recordset->Close();
?>
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($grafica_capacidad_efectiva->CurrentAction <> "gridadd" && $grafica_capacidad_efectiva->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_capacidad_efectiva_list->Pager)) $grafica_capacidad_efectiva_list->Pager = new cPrevNextPager($grafica_capacidad_efectiva_list->StartRec, $grafica_capacidad_efectiva_list->DisplayRecs, $grafica_capacidad_efectiva_list->TotalRecs) ?>
<?php if ($grafica_capacidad_efectiva_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_capacidad_efectiva_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_capacidad_efectiva_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_capacidad_efectiva_list->PageUrl() ?>start=<?php echo $grafica_capacidad_efectiva_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_capacidad_efectiva_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_capacidad_efectiva_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($grafica_capacidad_efectiva_list->TotalRecs == 0 && $grafica_capacidad_efectiva->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_capacidad_efectiva_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<script type="text/javascript">
fgrafica_capacidad_efectivalistsrch.Init();
fgrafica_capacidad_efectivalist.Init();
</script>
<?php } ?>
<?php
$grafica_capacidad_efectiva_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($grafica_capacidad_efectiva->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$grafica_capacidad_efectiva_list->Page_Terminate();
?>
