<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "inventarioinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$inventario_list = NULL; // Initialize page object first

class cinventario_list extends cinventario {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'inventario';

	// Page object name
	var $PageObjName = 'inventario_list';

	// Grid form hidden field names
	var $FormName = 'finventariolist';
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

		// Table object (inventario)
		if (!isset($GLOBALS["inventario"]) || get_class($GLOBALS["inventario"]) == "cinventario") {
			$GLOBALS["inventario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["inventario"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "inventarioadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "inventariodelete.php";
		$this->MultiUpdateUrl = "inventarioupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'inventario', TRUE);

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
		global $EW_EXPORT, $inventario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($inventario);
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
			$this->UpdateSort($this->DIA, $bCtrl); // DIA
			$this->UpdateSort($this->MES, $bCtrl); // MES
			$this->UpdateSort($this->NOM_PE, $bCtrl); // NOM_PE
			$this->UpdateSort($this->Otro_PE, $bCtrl); // Otro_PE
			$this->UpdateSort($this->OBSERVACION, $bCtrl); // OBSERVACION
			$this->UpdateSort($this->AD1O, $bCtrl); // AÑO
			$this->UpdateSort($this->FASE, $bCtrl); // FASE
			$this->UpdateSort($this->F_Sincron, $bCtrl); // F_Sincron
			$this->UpdateSort($this->FECHA_INV, $bCtrl); // FECHA_INV
			$this->UpdateSort($this->Otro_NOM_CAPAT, $bCtrl); // Otro_NOM_CAPAT
			$this->UpdateSort($this->Otro_CC_CAPAT, $bCtrl); // Otro_CC_CAPAT
			$this->UpdateSort($this->NOM_LUGAR, $bCtrl); // NOM_LUGAR
			$this->UpdateSort($this->Cocina, $bCtrl); // Cocina
			$this->UpdateSort($this->_1_Abrelatas, $bCtrl); // 1_Abrelatas
			$this->UpdateSort($this->_1_Balde, $bCtrl); // 1_Balde
			$this->UpdateSort($this->_1_Arrocero_50, $bCtrl); // 1_Arrocero_50
			$this->UpdateSort($this->_1_Arrocero_44, $bCtrl); // 1_Arrocero_44
			$this->UpdateSort($this->_1_Chocolatera, $bCtrl); // 1_Chocolatera
			$this->UpdateSort($this->_1_Colador, $bCtrl); // 1_Colador
			$this->UpdateSort($this->_1_Cucharon_sopa, $bCtrl); // 1_Cucharon_sopa
			$this->UpdateSort($this->_1_Cucharon_arroz, $bCtrl); // 1_Cucharon_arroz
			$this->UpdateSort($this->_1_Cuchillo, $bCtrl); // 1_Cuchillo
			$this->UpdateSort($this->_1_Embudo, $bCtrl); // 1_Embudo
			$this->UpdateSort($this->_1_Espumera, $bCtrl); // 1_Espumera
			$this->UpdateSort($this->_1_Estufa, $bCtrl); // 1_Estufa
			$this->UpdateSort($this->_1_Cuchara_sopa, $bCtrl); // 1_Cuchara_sopa
			$this->UpdateSort($this->_1_Recipiente, $bCtrl); // 1_Recipiente
			$this->UpdateSort($this->_1_Kit_Repue_estufa, $bCtrl); // 1_Kit_Repue_estufa
			$this->UpdateSort($this->_1_Molinillo, $bCtrl); // 1_Molinillo
			$this->UpdateSort($this->_1_Olla_36, $bCtrl); // 1_Olla_36
			$this->UpdateSort($this->_1_Olla_40, $bCtrl); // 1_Olla_40
			$this->UpdateSort($this->_1_Paila_32, $bCtrl); // 1_Paila_32
			$this->UpdateSort($this->_1_Paila_36_37, $bCtrl); // 1_Paila_36_37
			$this->UpdateSort($this->Camping, $bCtrl); // Camping
			$this->UpdateSort($this->_2_Aislante, $bCtrl); // 2_Aislante
			$this->UpdateSort($this->_2_Carpa_hamaca, $bCtrl); // 2_Carpa_hamaca
			$this->UpdateSort($this->_2_Carpa_rancho, $bCtrl); // 2_Carpa_rancho
			$this->UpdateSort($this->_2_Fibra_rollo, $bCtrl); // 2_Fibra_rollo
			$this->UpdateSort($this->_2_CAL, $bCtrl); // 2_CAL
			$this->UpdateSort($this->_2_Linterna, $bCtrl); // 2_Linterna
			$this->UpdateSort($this->_2_Botiquin, $bCtrl); // 2_Botiquin
			$this->UpdateSort($this->_2_Mascara_filtro, $bCtrl); // 2_Mascara_filtro
			$this->UpdateSort($this->_2_Pimpina, $bCtrl); // 2_Pimpina
			$this->UpdateSort($this->_2_SleepingA0, $bCtrl); // 2_Sleeping 
			$this->UpdateSort($this->_2_Plastico_negro, $bCtrl); // 2_Plastico_negro
			$this->UpdateSort($this->_2_Tula_tropa, $bCtrl); // 2_Tula_tropa
			$this->UpdateSort($this->_2_Camilla, $bCtrl); // 2_Camilla
			$this->UpdateSort($this->Herramientas, $bCtrl); // Herramientas
			$this->UpdateSort($this->_3_Abrazadera, $bCtrl); // 3_Abrazadera
			$this->UpdateSort($this->_3_Aspersora, $bCtrl); // 3_Aspersora
			$this->UpdateSort($this->_3_Cabo_hacha, $bCtrl); // 3_Cabo_hacha
			$this->UpdateSort($this->_3_Funda_machete, $bCtrl); // 3_Funda_machete
			$this->UpdateSort($this->_3_Glifosato_4lt, $bCtrl); // 3_Glifosato_4lt
			$this->UpdateSort($this->_3_Hacha, $bCtrl); // 3_Hacha
			$this->UpdateSort($this->_3_Lima_12_uni, $bCtrl); // 3_Lima_12_uni
			$this->UpdateSort($this->_3_Llave_mixta, $bCtrl); // 3_Llave_mixta
			$this->UpdateSort($this->_3_Machete, $bCtrl); // 3_Machete
			$this->UpdateSort($this->_3_Gafa_traslucida, $bCtrl); // 3_Gafa_traslucida
			$this->UpdateSort($this->_3_Motosierra, $bCtrl); // 3_Motosierra
			$this->UpdateSort($this->_3_Palin, $bCtrl); // 3_Palin
			$this->UpdateSort($this->_3_Tubo_galvanizado, $bCtrl); // 3_Tubo_galvanizado
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
				$this->DIA->setSort("");
				$this->MES->setSort("");
				$this->NOM_PE->setSort("");
				$this->Otro_PE->setSort("");
				$this->OBSERVACION->setSort("");
				$this->AD1O->setSort("");
				$this->FASE->setSort("");
				$this->F_Sincron->setSort("");
				$this->FECHA_INV->setSort("");
				$this->Otro_NOM_CAPAT->setSort("");
				$this->Otro_CC_CAPAT->setSort("");
				$this->NOM_LUGAR->setSort("");
				$this->Cocina->setSort("");
				$this->_1_Abrelatas->setSort("");
				$this->_1_Balde->setSort("");
				$this->_1_Arrocero_50->setSort("");
				$this->_1_Arrocero_44->setSort("");
				$this->_1_Chocolatera->setSort("");
				$this->_1_Colador->setSort("");
				$this->_1_Cucharon_sopa->setSort("");
				$this->_1_Cucharon_arroz->setSort("");
				$this->_1_Cuchillo->setSort("");
				$this->_1_Embudo->setSort("");
				$this->_1_Espumera->setSort("");
				$this->_1_Estufa->setSort("");
				$this->_1_Cuchara_sopa->setSort("");
				$this->_1_Recipiente->setSort("");
				$this->_1_Kit_Repue_estufa->setSort("");
				$this->_1_Molinillo->setSort("");
				$this->_1_Olla_36->setSort("");
				$this->_1_Olla_40->setSort("");
				$this->_1_Paila_32->setSort("");
				$this->_1_Paila_36_37->setSort("");
				$this->Camping->setSort("");
				$this->_2_Aislante->setSort("");
				$this->_2_Carpa_hamaca->setSort("");
				$this->_2_Carpa_rancho->setSort("");
				$this->_2_Fibra_rollo->setSort("");
				$this->_2_CAL->setSort("");
				$this->_2_Linterna->setSort("");
				$this->_2_Botiquin->setSort("");
				$this->_2_Mascara_filtro->setSort("");
				$this->_2_Pimpina->setSort("");
				$this->_2_SleepingA0->setSort("");
				$this->_2_Plastico_negro->setSort("");
				$this->_2_Tula_tropa->setSort("");
				$this->_2_Camilla->setSort("");
				$this->Herramientas->setSort("");
				$this->_3_Abrazadera->setSort("");
				$this->_3_Aspersora->setSort("");
				$this->_3_Cabo_hacha->setSort("");
				$this->_3_Funda_machete->setSort("");
				$this->_3_Glifosato_4lt->setSort("");
				$this->_3_Hacha->setSort("");
				$this->_3_Lima_12_uni->setSort("");
				$this->_3_Llave_mixta->setSort("");
				$this->_3_Machete->setSort("");
				$this->_3_Gafa_traslucida->setSort("");
				$this->_3_Motosierra->setSort("");
				$this->_3_Palin->setSort("");
				$this->_3_Tubo_galvanizado->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.finventariolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->NOM_PE->setDbValue($rs->fields('NOM_PE'));
		$this->Otro_PE->setDbValue($rs->fields('Otro_PE'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->AD1O->setDbValue($rs->fields('AÑO'));
		$this->FASE->setDbValue($rs->fields('FASE'));
		$this->F_Sincron->setDbValue($rs->fields('F_Sincron'));
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
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->llave->DbValue = $row['llave'];
		$this->USUARIO->DbValue = $row['USUARIO'];
		$this->Cargo_gme->DbValue = $row['Cargo_gme'];
		$this->DIA->DbValue = $row['DIA'];
		$this->MES->DbValue = $row['MES'];
		$this->NOM_PE->DbValue = $row['NOM_PE'];
		$this->Otro_PE->DbValue = $row['Otro_PE'];
		$this->OBSERVACION->DbValue = $row['OBSERVACION'];
		$this->AD1O->DbValue = $row['AÑO'];
		$this->FASE->DbValue = $row['FASE'];
		$this->F_Sincron->DbValue = $row['F_Sincron'];
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
		// USUARIO
		// Cargo_gme
		// DIA
		// MES
		// NOM_PE
		// Otro_PE
		// OBSERVACION
		// AÑO
		// FASE
		// F_Sincron
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// llave
			$this->llave->ViewValue = $this->llave->CurrentValue;
			$this->llave->ViewCustomAttributes = "";

			// Cargo_gme
			$this->Cargo_gme->ViewValue = $this->Cargo_gme->CurrentValue;
			$this->Cargo_gme->ViewCustomAttributes = "";

			// DIA
			$this->DIA->ViewValue = $this->DIA->CurrentValue;
			$this->DIA->ViewCustomAttributes = "";

			// MES
			$this->MES->ViewValue = $this->MES->CurrentValue;
			$this->MES->ViewCustomAttributes = "";

			// NOM_PE
			$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
			$this->NOM_PE->ViewCustomAttributes = "";

			// Otro_PE
			$this->Otro_PE->ViewValue = $this->Otro_PE->CurrentValue;
			$this->Otro_PE->ViewCustomAttributes = "";

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

			// FECHA_INV
			$this->FECHA_INV->ViewValue = $this->FECHA_INV->CurrentValue;
			$this->FECHA_INV->ViewCustomAttributes = "";

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

			// llave
			$this->llave->LinkCustomAttributes = "";
			$this->llave->HrefValue = "";
			$this->llave->TooltipValue = "";

			// Cargo_gme
			$this->Cargo_gme->LinkCustomAttributes = "";
			$this->Cargo_gme->HrefValue = "";
			$this->Cargo_gme->TooltipValue = "";

			// DIA
			$this->DIA->LinkCustomAttributes = "";
			$this->DIA->HrefValue = "";
			$this->DIA->TooltipValue = "";

			// MES
			$this->MES->LinkCustomAttributes = "";
			$this->MES->HrefValue = "";
			$this->MES->TooltipValue = "";

			// NOM_PE
			$this->NOM_PE->LinkCustomAttributes = "";
			$this->NOM_PE->HrefValue = "";
			$this->NOM_PE->TooltipValue = "";

			// Otro_PE
			$this->Otro_PE->LinkCustomAttributes = "";
			$this->Otro_PE->HrefValue = "";
			$this->Otro_PE->TooltipValue = "";

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

			// FECHA_INV
			$this->FECHA_INV->LinkCustomAttributes = "";
			$this->FECHA_INV->HrefValue = "";
			$this->FECHA_INV->TooltipValue = "";

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
		$item->Body = "<button id=\"emf_inventario\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_inventario',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.finventariolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($inventario_list)) $inventario_list = new cinventario_list();

// Page init
$inventario_list->Page_Init();

// Page main
$inventario_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$inventario_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($inventario->Export == "") { ?>
<script type="text/javascript">

// Page object
var inventario_list = new ew_Page("inventario_list");
inventario_list.PageID = "list"; // Page ID
var EW_PAGE_ID = inventario_list.PageID; // For backward compatibility

// Form object
var finventariolist = new ew_Form("finventariolist");
finventariolist.FormKeyCountName = '<?php echo $inventario_list->FormKeyCountName ?>';

// Form_CustomValidate event
finventariolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
finventariolist.ValidateRequired = true;
<?php } else { ?>
finventariolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($inventario->Export == "") { ?>
<div class="ewToolbar">
<?php if ($inventario->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($inventario_list->TotalRecs > 0 && $inventario_list->ExportOptions->Visible()) { ?>

<?php } ?>
<?php if ($inventario->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($inventario_list->TotalRecs <= 0)
			$inventario_list->TotalRecs = $inventario->SelectRecordCount();
	} else {
		if (!$inventario_list->Recordset && ($inventario_list->Recordset = $inventario_list->LoadRecordset()))
			$inventario_list->TotalRecs = $inventario_list->Recordset->RecordCount();
	}
	$inventario_list->StartRec = 1;
	if ($inventario_list->DisplayRecs <= 0 || ($inventario->Export <> "" && $inventario->ExportAll)) // Display all records
		$inventario_list->DisplayRecs = $inventario_list->TotalRecs;
	if (!($inventario->Export <> "" && $inventario->ExportAll))
		$inventario_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$inventario_list->Recordset = $inventario_list->LoadRecordset($inventario_list->StartRec-1, $inventario_list->DisplayRecs);

	// Set no record found message
	if ($inventario->CurrentAction == "" && $inventario_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$inventario_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($inventario_list->SearchWhere == "0=101")
			$inventario_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$inventario_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$inventario_list->RenderOtherOptions();
?>
<?php $inventario_list->ShowPageHeader(); ?>
<?php
$inventario_list->ShowMessage();
?>
<?php if ($inventario_list->TotalRecs > 0 || $inventario->CurrentAction <> "") { ?>
<div>
<table>
	
	<h2>Datos de Informe de Inventario</h2>
	<br>
	<table>
	<tr>
	<td>Fecha y hora del último registro sincronizado en base de datos:</td><td><i><div id="actualizacion"></div></i></td>
	</tr>
	</table>
	<br>
	<p>Este módulo permite descargar un archivo Excel con los datos del formulario "Informe de Inventario" </p> 
	<br>
	
	
	
	

	<script type="text/javascript">
		var formulario="Informe_Inventario";
		var dataString = 'formulario=' + formulario;
		$(document).ready(function(){
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
					url: "Descarga_datos_INV.php",
					data: dataString,
					success: function(html)
					{
						 window.location ="Descarga_datos_INV.php";

					},
					error: function() {
					
						alert("No hay conección con la base de datos apra realizar la descarga");
					}			
				});
			});

		});
	</script>
	<button type="button" id="boton_ID" >Descargar Excel</button> 
	

	

</table>
</div>
<?php } ?>
<?php if ($inventario->Export == "") { ?>
<script type="text/javascript">
finventariolist.Init();
</script>
<?php } ?>
<?php
$inventario_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($inventario->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$inventario_list->Page_Terminate();
?>
