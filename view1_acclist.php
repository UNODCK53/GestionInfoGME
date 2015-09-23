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

$view1_acc_list = NULL; // Initialize page object first

class cview1_acc_list extends cview1_acc {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view1_acc';

	// Page object name
	var $PageObjName = 'view1_acc_list';

	// Grid form hidden field names
	var $FormName = 'fview1_acclist';
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

		// Table object (view1_acc)
		if (!isset($GLOBALS["view1_acc"]) || get_class($GLOBALS["view1_acc"]) == "cview1_acc") {
			$GLOBALS["view1_acc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1_acc"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view1_accadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view1_accdelete.php";
		$this->MultiUpdateUrl = "view1_accupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view1_acc', TRUE);

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
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

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

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

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
		if (count($arrKeyFlds) >= 1) {
			$this->llave_2->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->llave, $Default, FALSE); // llave
		$this->BuildSearchSql($sWhere, $this->F_Sincron, $Default, FALSE); // F_Sincron
		$this->BuildSearchSql($sWhere, $this->USUARIO, $Default, FALSE); // USUARIO
		$this->BuildSearchSql($sWhere, $this->Cargo_gme, $Default, FALSE); // Cargo_gme
		$this->BuildSearchSql($sWhere, $this->NOM_PE, $Default, FALSE); // NOM_PE
		$this->BuildSearchSql($sWhere, $this->Otro_PE, $Default, FALSE); // Otro_PE
		$this->BuildSearchSql($sWhere, $this->NOM_APOYO, $Default, FALSE); // NOM_APOYO
		$this->BuildSearchSql($sWhere, $this->Otro_Nom_Apoyo, $Default, FALSE); // Otro_Nom_Apoyo
		$this->BuildSearchSql($sWhere, $this->Otro_CC_Apoyo, $Default, FALSE); // Otro_CC_Apoyo
		$this->BuildSearchSql($sWhere, $this->NOM_ENLACE, $Default, FALSE); // NOM_ENLACE
		$this->BuildSearchSql($sWhere, $this->Otro_Nom_Enlace, $Default, FALSE); // Otro_Nom_Enlace
		$this->BuildSearchSql($sWhere, $this->Otro_CC_Enlace, $Default, FALSE); // Otro_CC_Enlace
		$this->BuildSearchSql($sWhere, $this->NOM_PGE, $Default, FALSE); // NOM_PGE
		$this->BuildSearchSql($sWhere, $this->Otro_Nom_PGE, $Default, FALSE); // Otro_Nom_PGE
		$this->BuildSearchSql($sWhere, $this->Otro_CC_PGE, $Default, FALSE); // Otro_CC_PGE
		$this->BuildSearchSql($sWhere, $this->Departamento, $Default, FALSE); // Departamento
		$this->BuildSearchSql($sWhere, $this->Muncipio, $Default, FALSE); // Muncipio
		$this->BuildSearchSql($sWhere, $this->NOM_VDA, $Default, FALSE); // NOM_VDA
		$this->BuildSearchSql($sWhere, $this->LATITUD, $Default, FALSE); // LATITUD
		$this->BuildSearchSql($sWhere, $this->GRA_LAT, $Default, FALSE); // GRA_LAT
		$this->BuildSearchSql($sWhere, $this->MIN_LAT, $Default, FALSE); // MIN_LAT
		$this->BuildSearchSql($sWhere, $this->SEG_LAT, $Default, FALSE); // SEG_LAT
		$this->BuildSearchSql($sWhere, $this->GRA_LONG, $Default, FALSE); // GRA_LONG
		$this->BuildSearchSql($sWhere, $this->MIN_LONG, $Default, FALSE); // MIN_LONG
		$this->BuildSearchSql($sWhere, $this->SEG_LONG, $Default, FALSE); // SEG_LONG
		$this->BuildSearchSql($sWhere, $this->FECHA_ACC, $Default, FALSE); // FECHA_ACC
		$this->BuildSearchSql($sWhere, $this->HORA_ACC, $Default, FALSE); // HORA_ACC
		$this->BuildSearchSql($sWhere, $this->Hora_ingreso, $Default, FALSE); // Hora_ingreso
		$this->BuildSearchSql($sWhere, $this->FP_Armada, $Default, FALSE); // FP_Armada
		$this->BuildSearchSql($sWhere, $this->FP_Ejercito, $Default, FALSE); // FP_Ejercito
		$this->BuildSearchSql($sWhere, $this->FP_Policia, $Default, FALSE); // FP_Policia
		$this->BuildSearchSql($sWhere, $this->NOM_COMANDANTE, $Default, FALSE); // NOM_COMANDANTE
		$this->BuildSearchSql($sWhere, $this->TESTI1, $Default, FALSE); // TESTI1
		$this->BuildSearchSql($sWhere, $this->CC_TESTI1, $Default, FALSE); // CC_TESTI1
		$this->BuildSearchSql($sWhere, $this->CARGO_TESTI1, $Default, FALSE); // CARGO_TESTI1
		$this->BuildSearchSql($sWhere, $this->TESTI2, $Default, FALSE); // TESTI2
		$this->BuildSearchSql($sWhere, $this->CC_TESTI2, $Default, FALSE); // CC_TESTI2
		$this->BuildSearchSql($sWhere, $this->CARGO_TESTI2, $Default, FALSE); // CARGO_TESTI2
		$this->BuildSearchSql($sWhere, $this->Afectados, $Default, FALSE); // Afectados
		$this->BuildSearchSql($sWhere, $this->NUM_Afectado, $Default, FALSE); // NUM_Afectado
		$this->BuildSearchSql($sWhere, $this->Nom_Afectado, $Default, FALSE); // Nom_Afectado
		$this->BuildSearchSql($sWhere, $this->CC_Afectado, $Default, FALSE); // CC_Afectado
		$this->BuildSearchSql($sWhere, $this->Cargo_Afectado, $Default, FALSE); // Cargo_Afectado
		$this->BuildSearchSql($sWhere, $this->Tipo_incidente, $Default, FALSE); // Tipo_incidente
		$this->BuildSearchSql($sWhere, $this->Riesgo, $Default, FALSE); // Riesgo
		$this->BuildSearchSql($sWhere, $this->Parte_Cuerpo, $Default, FALSE); // Parte_Cuerpo
		$this->BuildSearchSql($sWhere, $this->ESTADO_AFEC, $Default, FALSE); // ESTADO_AFEC
		$this->BuildSearchSql($sWhere, $this->EVACUADO, $Default, FALSE); // EVACUADO
		$this->BuildSearchSql($sWhere, $this->DESC_ACC, $Default, FALSE); // DESC_ACC
		$this->BuildSearchSql($sWhere, $this->Modificado, $Default, FALSE); // Modificado
		$this->BuildSearchSql($sWhere, $this->llave_2, $Default, FALSE); // llave_2

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->llave->AdvancedSearch->Save(); // llave
			$this->F_Sincron->AdvancedSearch->Save(); // F_Sincron
			$this->USUARIO->AdvancedSearch->Save(); // USUARIO
			$this->Cargo_gme->AdvancedSearch->Save(); // Cargo_gme
			$this->NOM_PE->AdvancedSearch->Save(); // NOM_PE
			$this->Otro_PE->AdvancedSearch->Save(); // Otro_PE
			$this->NOM_APOYO->AdvancedSearch->Save(); // NOM_APOYO
			$this->Otro_Nom_Apoyo->AdvancedSearch->Save(); // Otro_Nom_Apoyo
			$this->Otro_CC_Apoyo->AdvancedSearch->Save(); // Otro_CC_Apoyo
			$this->NOM_ENLACE->AdvancedSearch->Save(); // NOM_ENLACE
			$this->Otro_Nom_Enlace->AdvancedSearch->Save(); // Otro_Nom_Enlace
			$this->Otro_CC_Enlace->AdvancedSearch->Save(); // Otro_CC_Enlace
			$this->NOM_PGE->AdvancedSearch->Save(); // NOM_PGE
			$this->Otro_Nom_PGE->AdvancedSearch->Save(); // Otro_Nom_PGE
			$this->Otro_CC_PGE->AdvancedSearch->Save(); // Otro_CC_PGE
			$this->Departamento->AdvancedSearch->Save(); // Departamento
			$this->Muncipio->AdvancedSearch->Save(); // Muncipio
			$this->NOM_VDA->AdvancedSearch->Save(); // NOM_VDA
			$this->LATITUD->AdvancedSearch->Save(); // LATITUD
			$this->GRA_LAT->AdvancedSearch->Save(); // GRA_LAT
			$this->MIN_LAT->AdvancedSearch->Save(); // MIN_LAT
			$this->SEG_LAT->AdvancedSearch->Save(); // SEG_LAT
			$this->GRA_LONG->AdvancedSearch->Save(); // GRA_LONG
			$this->MIN_LONG->AdvancedSearch->Save(); // MIN_LONG
			$this->SEG_LONG->AdvancedSearch->Save(); // SEG_LONG
			$this->FECHA_ACC->AdvancedSearch->Save(); // FECHA_ACC
			$this->HORA_ACC->AdvancedSearch->Save(); // HORA_ACC
			$this->Hora_ingreso->AdvancedSearch->Save(); // Hora_ingreso
			$this->FP_Armada->AdvancedSearch->Save(); // FP_Armada
			$this->FP_Ejercito->AdvancedSearch->Save(); // FP_Ejercito
			$this->FP_Policia->AdvancedSearch->Save(); // FP_Policia
			$this->NOM_COMANDANTE->AdvancedSearch->Save(); // NOM_COMANDANTE
			$this->TESTI1->AdvancedSearch->Save(); // TESTI1
			$this->CC_TESTI1->AdvancedSearch->Save(); // CC_TESTI1
			$this->CARGO_TESTI1->AdvancedSearch->Save(); // CARGO_TESTI1
			$this->TESTI2->AdvancedSearch->Save(); // TESTI2
			$this->CC_TESTI2->AdvancedSearch->Save(); // CC_TESTI2
			$this->CARGO_TESTI2->AdvancedSearch->Save(); // CARGO_TESTI2
			$this->Afectados->AdvancedSearch->Save(); // Afectados
			$this->NUM_Afectado->AdvancedSearch->Save(); // NUM_Afectado
			$this->Nom_Afectado->AdvancedSearch->Save(); // Nom_Afectado
			$this->CC_Afectado->AdvancedSearch->Save(); // CC_Afectado
			$this->Cargo_Afectado->AdvancedSearch->Save(); // Cargo_Afectado
			$this->Tipo_incidente->AdvancedSearch->Save(); // Tipo_incidente
			$this->Riesgo->AdvancedSearch->Save(); // Riesgo
			$this->Parte_Cuerpo->AdvancedSearch->Save(); // Parte_Cuerpo
			$this->ESTADO_AFEC->AdvancedSearch->Save(); // ESTADO_AFEC
			$this->EVACUADO->AdvancedSearch->Save(); // EVACUADO
			$this->DESC_ACC->AdvancedSearch->Save(); // DESC_ACC
			$this->Modificado->AdvancedSearch->Save(); // Modificado
			$this->llave_2->AdvancedSearch->Save(); // llave_2
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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->llave, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->USUARIO, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cargo_gme, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_APOYO, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_Nom_Apoyo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_CC_Apoyo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_ENLACE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_Nom_Enlace, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_CC_Enlace, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_Nom_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_CC_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Departamento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Muncipio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_VDA, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->LATITUD, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FECHA_ACC, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->HORA_ACC, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Hora_ingreso, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_COMANDANTE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->TESTI1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->CC_TESTI1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->CARGO_TESTI1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->TESTI2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->CC_TESTI2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->CARGO_TESTI2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nom_Afectado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->CC_Afectado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cargo_Afectado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Tipo_incidente, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Riesgo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Parte_Cuerpo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ESTADO_AFEC, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->EVACUADO, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->DESC_ACC, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Modificado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->llave_2, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
						$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->llave->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->F_Sincron->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->USUARIO->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Cargo_gme->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_APOYO->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_Nom_Apoyo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_CC_Apoyo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_ENLACE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_Nom_Enlace->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_CC_Enlace->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_PGE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_Nom_PGE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_CC_PGE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Departamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Muncipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_VDA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->LATITUD->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->GRA_LAT->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->MIN_LAT->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->SEG_LAT->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->GRA_LONG->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->MIN_LONG->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->SEG_LONG->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FECHA_ACC->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->HORA_ACC->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Hora_ingreso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FP_Armada->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FP_Ejercito->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FP_Policia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_COMANDANTE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->TESTI1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CC_TESTI1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CARGO_TESTI1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->TESTI2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CC_TESTI2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CARGO_TESTI2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Afectados->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NUM_Afectado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nom_Afectado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->CC_Afectado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Cargo_Afectado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Tipo_incidente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Riesgo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Parte_Cuerpo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ESTADO_AFEC->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->EVACUADO->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->DESC_ACC->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Modificado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->llave_2->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->llave->AdvancedSearch->UnsetSession();
		$this->F_Sincron->AdvancedSearch->UnsetSession();
		$this->USUARIO->AdvancedSearch->UnsetSession();
		$this->Cargo_gme->AdvancedSearch->UnsetSession();
		$this->NOM_PE->AdvancedSearch->UnsetSession();
		$this->Otro_PE->AdvancedSearch->UnsetSession();
		$this->NOM_APOYO->AdvancedSearch->UnsetSession();
		$this->Otro_Nom_Apoyo->AdvancedSearch->UnsetSession();
		$this->Otro_CC_Apoyo->AdvancedSearch->UnsetSession();
		$this->NOM_ENLACE->AdvancedSearch->UnsetSession();
		$this->Otro_Nom_Enlace->AdvancedSearch->UnsetSession();
		$this->Otro_CC_Enlace->AdvancedSearch->UnsetSession();
		$this->NOM_PGE->AdvancedSearch->UnsetSession();
		$this->Otro_Nom_PGE->AdvancedSearch->UnsetSession();
		$this->Otro_CC_PGE->AdvancedSearch->UnsetSession();
		$this->Departamento->AdvancedSearch->UnsetSession();
		$this->Muncipio->AdvancedSearch->UnsetSession();
		$this->NOM_VDA->AdvancedSearch->UnsetSession();
		$this->LATITUD->AdvancedSearch->UnsetSession();
		$this->GRA_LAT->AdvancedSearch->UnsetSession();
		$this->MIN_LAT->AdvancedSearch->UnsetSession();
		$this->SEG_LAT->AdvancedSearch->UnsetSession();
		$this->GRA_LONG->AdvancedSearch->UnsetSession();
		$this->MIN_LONG->AdvancedSearch->UnsetSession();
		$this->SEG_LONG->AdvancedSearch->UnsetSession();
		$this->FECHA_ACC->AdvancedSearch->UnsetSession();
		$this->HORA_ACC->AdvancedSearch->UnsetSession();
		$this->Hora_ingreso->AdvancedSearch->UnsetSession();
		$this->FP_Armada->AdvancedSearch->UnsetSession();
		$this->FP_Ejercito->AdvancedSearch->UnsetSession();
		$this->FP_Policia->AdvancedSearch->UnsetSession();
		$this->NOM_COMANDANTE->AdvancedSearch->UnsetSession();
		$this->TESTI1->AdvancedSearch->UnsetSession();
		$this->CC_TESTI1->AdvancedSearch->UnsetSession();
		$this->CARGO_TESTI1->AdvancedSearch->UnsetSession();
		$this->TESTI2->AdvancedSearch->UnsetSession();
		$this->CC_TESTI2->AdvancedSearch->UnsetSession();
		$this->CARGO_TESTI2->AdvancedSearch->UnsetSession();
		$this->Afectados->AdvancedSearch->UnsetSession();
		$this->NUM_Afectado->AdvancedSearch->UnsetSession();
		$this->Nom_Afectado->AdvancedSearch->UnsetSession();
		$this->CC_Afectado->AdvancedSearch->UnsetSession();
		$this->Cargo_Afectado->AdvancedSearch->UnsetSession();
		$this->Tipo_incidente->AdvancedSearch->UnsetSession();
		$this->Riesgo->AdvancedSearch->UnsetSession();
		$this->Parte_Cuerpo->AdvancedSearch->UnsetSession();
		$this->ESTADO_AFEC->AdvancedSearch->UnsetSession();
		$this->EVACUADO->AdvancedSearch->UnsetSession();
		$this->DESC_ACC->AdvancedSearch->UnsetSession();
		$this->Modificado->AdvancedSearch->UnsetSession();
		$this->llave_2->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->llave->AdvancedSearch->Load();
		$this->F_Sincron->AdvancedSearch->Load();
		$this->USUARIO->AdvancedSearch->Load();
		$this->Cargo_gme->AdvancedSearch->Load();
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_PE->AdvancedSearch->Load();
		$this->NOM_APOYO->AdvancedSearch->Load();
		$this->Otro_Nom_Apoyo->AdvancedSearch->Load();
		$this->Otro_CC_Apoyo->AdvancedSearch->Load();
		$this->NOM_ENLACE->AdvancedSearch->Load();
		$this->Otro_Nom_Enlace->AdvancedSearch->Load();
		$this->Otro_CC_Enlace->AdvancedSearch->Load();
		$this->NOM_PGE->AdvancedSearch->Load();
		$this->Otro_Nom_PGE->AdvancedSearch->Load();
		$this->Otro_CC_PGE->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->NOM_VDA->AdvancedSearch->Load();
		$this->LATITUD->AdvancedSearch->Load();
		$this->GRA_LAT->AdvancedSearch->Load();
		$this->MIN_LAT->AdvancedSearch->Load();
		$this->SEG_LAT->AdvancedSearch->Load();
		$this->GRA_LONG->AdvancedSearch->Load();
		$this->MIN_LONG->AdvancedSearch->Load();
		$this->SEG_LONG->AdvancedSearch->Load();
		$this->FECHA_ACC->AdvancedSearch->Load();
		$this->HORA_ACC->AdvancedSearch->Load();
		$this->Hora_ingreso->AdvancedSearch->Load();
		$this->FP_Armada->AdvancedSearch->Load();
		$this->FP_Ejercito->AdvancedSearch->Load();
		$this->FP_Policia->AdvancedSearch->Load();
		$this->NOM_COMANDANTE->AdvancedSearch->Load();
		$this->TESTI1->AdvancedSearch->Load();
		$this->CC_TESTI1->AdvancedSearch->Load();
		$this->CARGO_TESTI1->AdvancedSearch->Load();
		$this->TESTI2->AdvancedSearch->Load();
		$this->CC_TESTI2->AdvancedSearch->Load();
		$this->CARGO_TESTI2->AdvancedSearch->Load();
		$this->Afectados->AdvancedSearch->Load();
		$this->NUM_Afectado->AdvancedSearch->Load();
		$this->Nom_Afectado->AdvancedSearch->Load();
		$this->CC_Afectado->AdvancedSearch->Load();
		$this->Cargo_Afectado->AdvancedSearch->Load();
		$this->Tipo_incidente->AdvancedSearch->Load();
		$this->Riesgo->AdvancedSearch->Load();
		$this->Parte_Cuerpo->AdvancedSearch->Load();
		$this->ESTADO_AFEC->AdvancedSearch->Load();
		$this->EVACUADO->AdvancedSearch->Load();
		$this->DESC_ACC->AdvancedSearch->Load();
		$this->Modificado->AdvancedSearch->Load();
		$this->llave_2->AdvancedSearch->Load();
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
			$this->UpdateSort($this->F_Sincron, $bCtrl); // F_Sincron
			$this->UpdateSort($this->USUARIO, $bCtrl); // USUARIO
			$this->UpdateSort($this->Cargo_gme, $bCtrl); // Cargo_gme
			$this->UpdateSort($this->NOM_PE, $bCtrl); // NOM_PE
			$this->UpdateSort($this->Otro_PE, $bCtrl); // Otro_PE
			$this->UpdateSort($this->NOM_APOYO, $bCtrl); // NOM_APOYO
			$this->UpdateSort($this->Otro_Nom_Apoyo, $bCtrl); // Otro_Nom_Apoyo
			$this->UpdateSort($this->Otro_CC_Apoyo, $bCtrl); // Otro_CC_Apoyo
			$this->UpdateSort($this->NOM_ENLACE, $bCtrl); // NOM_ENLACE
			$this->UpdateSort($this->Otro_Nom_Enlace, $bCtrl); // Otro_Nom_Enlace
			$this->UpdateSort($this->Otro_CC_Enlace, $bCtrl); // Otro_CC_Enlace
			$this->UpdateSort($this->NOM_PGE, $bCtrl); // NOM_PGE
			$this->UpdateSort($this->Otro_Nom_PGE, $bCtrl); // Otro_Nom_PGE
			$this->UpdateSort($this->Otro_CC_PGE, $bCtrl); // Otro_CC_PGE
			$this->UpdateSort($this->Departamento, $bCtrl); // Departamento
			$this->UpdateSort($this->Muncipio, $bCtrl); // Muncipio
			$this->UpdateSort($this->NOM_VDA, $bCtrl); // NOM_VDA
			$this->UpdateSort($this->LATITUD, $bCtrl); // LATITUD
			$this->UpdateSort($this->GRA_LAT, $bCtrl); // GRA_LAT
			$this->UpdateSort($this->MIN_LAT, $bCtrl); // MIN_LAT
			$this->UpdateSort($this->SEG_LAT, $bCtrl); // SEG_LAT
			$this->UpdateSort($this->GRA_LONG, $bCtrl); // GRA_LONG
			$this->UpdateSort($this->MIN_LONG, $bCtrl); // MIN_LONG
			$this->UpdateSort($this->SEG_LONG, $bCtrl); // SEG_LONG
			$this->UpdateSort($this->FECHA_ACC, $bCtrl); // FECHA_ACC
			$this->UpdateSort($this->HORA_ACC, $bCtrl); // HORA_ACC
			$this->UpdateSort($this->Hora_ingreso, $bCtrl); // Hora_ingreso
			$this->UpdateSort($this->FP_Armada, $bCtrl); // FP_Armada
			$this->UpdateSort($this->FP_Ejercito, $bCtrl); // FP_Ejercito
			$this->UpdateSort($this->FP_Policia, $bCtrl); // FP_Policia
			$this->UpdateSort($this->NOM_COMANDANTE, $bCtrl); // NOM_COMANDANTE
			$this->UpdateSort($this->TESTI1, $bCtrl); // TESTI1
			$this->UpdateSort($this->CC_TESTI1, $bCtrl); // CC_TESTI1
			$this->UpdateSort($this->CARGO_TESTI1, $bCtrl); // CARGO_TESTI1
			$this->UpdateSort($this->TESTI2, $bCtrl); // TESTI2
			$this->UpdateSort($this->CC_TESTI2, $bCtrl); // CC_TESTI2
			$this->UpdateSort($this->CARGO_TESTI2, $bCtrl); // CARGO_TESTI2
			$this->UpdateSort($this->Afectados, $bCtrl); // Afectados
			$this->UpdateSort($this->NUM_Afectado, $bCtrl); // NUM_Afectado
			$this->UpdateSort($this->Nom_Afectado, $bCtrl); // Nom_Afectado
			$this->UpdateSort($this->CC_Afectado, $bCtrl); // CC_Afectado
			$this->UpdateSort($this->Cargo_Afectado, $bCtrl); // Cargo_Afectado
			$this->UpdateSort($this->Tipo_incidente, $bCtrl); // Tipo_incidente
			$this->UpdateSort($this->Riesgo, $bCtrl); // Riesgo
			$this->UpdateSort($this->Parte_Cuerpo, $bCtrl); // Parte_Cuerpo
			$this->UpdateSort($this->ESTADO_AFEC, $bCtrl); // ESTADO_AFEC
			$this->UpdateSort($this->EVACUADO, $bCtrl); // EVACUADO
			$this->UpdateSort($this->DESC_ACC, $bCtrl); // DESC_ACC
			$this->UpdateSort($this->Modificado, $bCtrl); // Modificado
			$this->UpdateSort($this->llave_2, $bCtrl); // llave_2
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
				$this->llave->setSort("");
				$this->F_Sincron->setSort("");
				$this->USUARIO->setSort("");
				$this->Cargo_gme->setSort("");
				$this->NOM_PE->setSort("");
				$this->Otro_PE->setSort("");
				$this->NOM_APOYO->setSort("");
				$this->Otro_Nom_Apoyo->setSort("");
				$this->Otro_CC_Apoyo->setSort("");
				$this->NOM_ENLACE->setSort("");
				$this->Otro_Nom_Enlace->setSort("");
				$this->Otro_CC_Enlace->setSort("");
				$this->NOM_PGE->setSort("");
				$this->Otro_Nom_PGE->setSort("");
				$this->Otro_CC_PGE->setSort("");
				$this->Departamento->setSort("");
				$this->Muncipio->setSort("");
				$this->NOM_VDA->setSort("");
				$this->LATITUD->setSort("");
				$this->GRA_LAT->setSort("");
				$this->MIN_LAT->setSort("");
				$this->SEG_LAT->setSort("");
				$this->GRA_LONG->setSort("");
				$this->MIN_LONG->setSort("");
				$this->SEG_LONG->setSort("");
				$this->FECHA_ACC->setSort("");
				$this->HORA_ACC->setSort("");
				$this->Hora_ingreso->setSort("");
				$this->FP_Armada->setSort("");
				$this->FP_Ejercito->setSort("");
				$this->FP_Policia->setSort("");
				$this->NOM_COMANDANTE->setSort("");
				$this->TESTI1->setSort("");
				$this->CC_TESTI1->setSort("");
				$this->CARGO_TESTI1->setSort("");
				$this->TESTI2->setSort("");
				$this->CC_TESTI2->setSort("");
				$this->CARGO_TESTI2->setSort("");
				$this->Afectados->setSort("");
				$this->NUM_Afectado->setSort("");
				$this->Nom_Afectado->setSort("");
				$this->CC_Afectado->setSort("");
				$this->Cargo_Afectado->setSort("");
				$this->Tipo_incidente->setSort("");
				$this->Riesgo->setSort("");
				$this->Parte_Cuerpo->setSort("");
				$this->ESTADO_AFEC->setSort("");
				$this->EVACUADO->setSort("");
				$this->DESC_ACC->setSort("");
				$this->Modificado->setSort("");
				$this->llave_2->setSort("");
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

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

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

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->llave_2->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview1_acclist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview1_acclistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// llave

		$this->llave->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_llave"]);
		if ($this->llave->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->llave->AdvancedSearch->SearchOperator = @$_GET["z_llave"];

		// F_Sincron
		$this->F_Sincron->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_F_Sincron"]);
		if ($this->F_Sincron->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->F_Sincron->AdvancedSearch->SearchOperator = @$_GET["z_F_Sincron"];

		// USUARIO
		$this->USUARIO->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_USUARIO"]);
		if ($this->USUARIO->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->USUARIO->AdvancedSearch->SearchOperator = @$_GET["z_USUARIO"];

		// Cargo_gme
		$this->Cargo_gme->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Cargo_gme"]);
		if ($this->Cargo_gme->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Cargo_gme->AdvancedSearch->SearchOperator = @$_GET["z_Cargo_gme"];

		// NOM_PE
		$this->NOM_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_PE"]);
		if ($this->NOM_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_PE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_PE"];

		// Otro_PE
		$this->Otro_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_PE"]);
		if ($this->Otro_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_PE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_PE"];

		// NOM_APOYO
		$this->NOM_APOYO->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_APOYO"]);
		if ($this->NOM_APOYO->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_APOYO->AdvancedSearch->SearchOperator = @$_GET["z_NOM_APOYO"];

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_Nom_Apoyo"]);
		if ($this->Otro_Nom_Apoyo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_Nom_Apoyo->AdvancedSearch->SearchOperator = @$_GET["z_Otro_Nom_Apoyo"];

		// Otro_CC_Apoyo
		$this->Otro_CC_Apoyo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_CC_Apoyo"]);
		if ($this->Otro_CC_Apoyo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_CC_Apoyo->AdvancedSearch->SearchOperator = @$_GET["z_Otro_CC_Apoyo"];

		// NOM_ENLACE
		$this->NOM_ENLACE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_ENLACE"]);
		if ($this->NOM_ENLACE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_ENLACE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_ENLACE"];

		// Otro_Nom_Enlace
		$this->Otro_Nom_Enlace->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_Nom_Enlace"]);
		if ($this->Otro_Nom_Enlace->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_Nom_Enlace->AdvancedSearch->SearchOperator = @$_GET["z_Otro_Nom_Enlace"];

		// Otro_CC_Enlace
		$this->Otro_CC_Enlace->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_CC_Enlace"]);
		if ($this->Otro_CC_Enlace->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_CC_Enlace->AdvancedSearch->SearchOperator = @$_GET["z_Otro_CC_Enlace"];

		// NOM_PGE
		$this->NOM_PGE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_PGE"]);
		if ($this->NOM_PGE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_PGE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_PGE"];

		// Otro_Nom_PGE
		$this->Otro_Nom_PGE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_Nom_PGE"]);
		if ($this->Otro_Nom_PGE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_Nom_PGE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_Nom_PGE"];

		// Otro_CC_PGE
		$this->Otro_CC_PGE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_CC_PGE"]);
		if ($this->Otro_CC_PGE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_CC_PGE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_CC_PGE"];

		// Departamento
		$this->Departamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Departamento"]);
		if ($this->Departamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Departamento->AdvancedSearch->SearchOperator = @$_GET["z_Departamento"];

		// Muncipio
		$this->Muncipio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Muncipio"]);
		if ($this->Muncipio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Muncipio->AdvancedSearch->SearchOperator = @$_GET["z_Muncipio"];

		// NOM_VDA
		$this->NOM_VDA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_VDA"]);
		if ($this->NOM_VDA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_VDA->AdvancedSearch->SearchOperator = @$_GET["z_NOM_VDA"];

		// LATITUD
		$this->LATITUD->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_LATITUD"]);
		if ($this->LATITUD->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->LATITUD->AdvancedSearch->SearchOperator = @$_GET["z_LATITUD"];

		// GRA_LAT
		$this->GRA_LAT->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_GRA_LAT"]);
		if ($this->GRA_LAT->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->GRA_LAT->AdvancedSearch->SearchOperator = @$_GET["z_GRA_LAT"];

		// MIN_LAT
		$this->MIN_LAT->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_MIN_LAT"]);
		if ($this->MIN_LAT->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->MIN_LAT->AdvancedSearch->SearchOperator = @$_GET["z_MIN_LAT"];

		// SEG_LAT
		$this->SEG_LAT->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_SEG_LAT"]);
		if ($this->SEG_LAT->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->SEG_LAT->AdvancedSearch->SearchOperator = @$_GET["z_SEG_LAT"];

		// GRA_LONG
		$this->GRA_LONG->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_GRA_LONG"]);
		if ($this->GRA_LONG->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->GRA_LONG->AdvancedSearch->SearchOperator = @$_GET["z_GRA_LONG"];

		// MIN_LONG
		$this->MIN_LONG->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_MIN_LONG"]);
		if ($this->MIN_LONG->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->MIN_LONG->AdvancedSearch->SearchOperator = @$_GET["z_MIN_LONG"];

		// SEG_LONG
		$this->SEG_LONG->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_SEG_LONG"]);
		if ($this->SEG_LONG->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->SEG_LONG->AdvancedSearch->SearchOperator = @$_GET["z_SEG_LONG"];

		// FECHA_ACC
		$this->FECHA_ACC->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FECHA_ACC"]);
		if ($this->FECHA_ACC->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FECHA_ACC->AdvancedSearch->SearchOperator = @$_GET["z_FECHA_ACC"];

		// HORA_ACC
		$this->HORA_ACC->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_HORA_ACC"]);
		if ($this->HORA_ACC->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->HORA_ACC->AdvancedSearch->SearchOperator = @$_GET["z_HORA_ACC"];

		// Hora_ingreso
		$this->Hora_ingreso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Hora_ingreso"]);
		if ($this->Hora_ingreso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Hora_ingreso->AdvancedSearch->SearchOperator = @$_GET["z_Hora_ingreso"];

		// FP_Armada
		$this->FP_Armada->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FP_Armada"]);
		if ($this->FP_Armada->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FP_Armada->AdvancedSearch->SearchOperator = @$_GET["z_FP_Armada"];

		// FP_Ejercito
		$this->FP_Ejercito->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FP_Ejercito"]);
		if ($this->FP_Ejercito->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FP_Ejercito->AdvancedSearch->SearchOperator = @$_GET["z_FP_Ejercito"];

		// FP_Policia
		$this->FP_Policia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FP_Policia"]);
		if ($this->FP_Policia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FP_Policia->AdvancedSearch->SearchOperator = @$_GET["z_FP_Policia"];

		// NOM_COMANDANTE
		$this->NOM_COMANDANTE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_COMANDANTE"]);
		if ($this->NOM_COMANDANTE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_COMANDANTE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_COMANDANTE"];

		// TESTI1
		$this->TESTI1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_TESTI1"]);
		if ($this->TESTI1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->TESTI1->AdvancedSearch->SearchOperator = @$_GET["z_TESTI1"];

		// CC_TESTI1
		$this->CC_TESTI1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CC_TESTI1"]);
		if ($this->CC_TESTI1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CC_TESTI1->AdvancedSearch->SearchOperator = @$_GET["z_CC_TESTI1"];

		// CARGO_TESTI1
		$this->CARGO_TESTI1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CARGO_TESTI1"]);
		if ($this->CARGO_TESTI1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CARGO_TESTI1->AdvancedSearch->SearchOperator = @$_GET["z_CARGO_TESTI1"];

		// TESTI2
		$this->TESTI2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_TESTI2"]);
		if ($this->TESTI2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->TESTI2->AdvancedSearch->SearchOperator = @$_GET["z_TESTI2"];

		// CC_TESTI2
		$this->CC_TESTI2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CC_TESTI2"]);
		if ($this->CC_TESTI2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CC_TESTI2->AdvancedSearch->SearchOperator = @$_GET["z_CC_TESTI2"];

		// CARGO_TESTI2
		$this->CARGO_TESTI2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CARGO_TESTI2"]);
		if ($this->CARGO_TESTI2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CARGO_TESTI2->AdvancedSearch->SearchOperator = @$_GET["z_CARGO_TESTI2"];

		// Afectados
		$this->Afectados->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Afectados"]);
		if ($this->Afectados->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Afectados->AdvancedSearch->SearchOperator = @$_GET["z_Afectados"];

		// NUM_Afectado
		$this->NUM_Afectado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NUM_Afectado"]);
		if ($this->NUM_Afectado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NUM_Afectado->AdvancedSearch->SearchOperator = @$_GET["z_NUM_Afectado"];

		// Nom_Afectado
		$this->Nom_Afectado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nom_Afectado"]);
		if ($this->Nom_Afectado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nom_Afectado->AdvancedSearch->SearchOperator = @$_GET["z_Nom_Afectado"];

		// CC_Afectado
		$this->CC_Afectado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CC_Afectado"]);
		if ($this->CC_Afectado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CC_Afectado->AdvancedSearch->SearchOperator = @$_GET["z_CC_Afectado"];

		// Cargo_Afectado
		$this->Cargo_Afectado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Cargo_Afectado"]);
		if ($this->Cargo_Afectado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Cargo_Afectado->AdvancedSearch->SearchOperator = @$_GET["z_Cargo_Afectado"];

		// Tipo_incidente
		$this->Tipo_incidente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Tipo_incidente"]);
		if ($this->Tipo_incidente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Tipo_incidente->AdvancedSearch->SearchOperator = @$_GET["z_Tipo_incidente"];

		// Riesgo
		$this->Riesgo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Riesgo"]);
		if ($this->Riesgo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Riesgo->AdvancedSearch->SearchOperator = @$_GET["z_Riesgo"];

		// Parte_Cuerpo
		$this->Parte_Cuerpo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Parte_Cuerpo"]);
		if ($this->Parte_Cuerpo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Parte_Cuerpo->AdvancedSearch->SearchOperator = @$_GET["z_Parte_Cuerpo"];

		// ESTADO_AFEC
		$this->ESTADO_AFEC->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ESTADO_AFEC"]);
		if ($this->ESTADO_AFEC->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ESTADO_AFEC->AdvancedSearch->SearchOperator = @$_GET["z_ESTADO_AFEC"];

		// EVACUADO
		$this->EVACUADO->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_EVACUADO"]);
		if ($this->EVACUADO->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->EVACUADO->AdvancedSearch->SearchOperator = @$_GET["z_EVACUADO"];

		// DESC_ACC
		$this->DESC_ACC->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_DESC_ACC"]);
		if ($this->DESC_ACC->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->DESC_ACC->AdvancedSearch->SearchOperator = @$_GET["z_DESC_ACC"];

		// Modificado
		$this->Modificado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Modificado"]);
		if ($this->Modificado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Modificado->AdvancedSearch->SearchOperator = @$_GET["z_Modificado"];

		// llave_2
		$this->llave_2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_llave_2"]);
		if ($this->llave_2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->llave_2->AdvancedSearch->SearchOperator = @$_GET["z_llave_2"];
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
		$this->Riesgo->setDbValue($rs->fields('Riesgo'));
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
		$this->Riesgo->DbValue = $row['Riesgo'];
		$this->Parte_Cuerpo->DbValue = $row['Parte_Cuerpo'];
		$this->ESTADO_AFEC->DbValue = $row['ESTADO_AFEC'];
		$this->EVACUADO->DbValue = $row['EVACUADO'];
		$this->DESC_ACC->DbValue = $row['DESC_ACC'];
		$this->Modificado->DbValue = $row['Modificado'];
		$this->llave_2->DbValue = $row['llave_2'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("llave_2")) <> "")
			$this->llave_2->CurrentValue = $this->getKey("llave_2"); // llave_2
		else
			$bValidKey = FALSE;

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
		// Riesgo
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
			$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 5);
			$this->F_Sincron->ViewCustomAttributes = "";

			// USUARIO
			if (strval($this->USUARIO->CurrentValue) <> "") {
				$sFilterWrk = "`USUARIO`" . ew_SearchString("=", $this->USUARIO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
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
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
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

			// NOM_APOYO
			if (strval($this->NOM_APOYO->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_APOYO`" . ew_SearchString("=", $this->NOM_APOYO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_APOYO`, `NOM_APOYO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_APOYO`, `NOM_APOYO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_APOYO, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_APOYO` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->NOM_APOYO->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->NOM_APOYO->ViewValue = $this->NOM_APOYO->CurrentValue;
				}
			} else {
				$this->NOM_APOYO->ViewValue = NULL;
			}
			$this->NOM_APOYO->ViewCustomAttributes = "";

			// Otro_Nom_Apoyo
			$this->Otro_Nom_Apoyo->ViewValue = $this->Otro_Nom_Apoyo->CurrentValue;
			$this->Otro_Nom_Apoyo->ViewCustomAttributes = "";

			// Otro_CC_Apoyo
			$this->Otro_CC_Apoyo->ViewValue = $this->Otro_CC_Apoyo->CurrentValue;
			$this->Otro_CC_Apoyo->ViewCustomAttributes = "";

			// NOM_ENLACE
			if (strval($this->NOM_ENLACE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_ENLACE`" . ew_SearchString("=", $this->NOM_ENLACE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_ENLACE`, `NOM_ENLACE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_ENLACE`, `NOM_ENLACE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_ENLACE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_ENLACE` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->NOM_ENLACE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->NOM_ENLACE->ViewValue = $this->NOM_ENLACE->CurrentValue;
				}
			} else {
				$this->NOM_ENLACE->ViewValue = NULL;
			}
			$this->NOM_ENLACE->ViewCustomAttributes = "";

			// Otro_Nom_Enlace
			$this->Otro_Nom_Enlace->ViewValue = $this->Otro_Nom_Enlace->CurrentValue;
			$this->Otro_Nom_Enlace->ViewCustomAttributes = "";

			// Otro_CC_Enlace
			$this->Otro_CC_Enlace->ViewValue = $this->Otro_CC_Enlace->CurrentValue;
			$this->Otro_CC_Enlace->ViewCustomAttributes = "";

			// NOM_PGE
			if (strval($this->NOM_PGE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PGE`" . ew_SearchString("=", $this->NOM_PGE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
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
			if (strval($this->Tipo_incidente->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->Tipo_incidente->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='Incidente'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Tipo_incidente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Tipo_incidente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Tipo_incidente->ViewValue = $this->Tipo_incidente->CurrentValue;
				}
			} else {
				$this->Tipo_incidente->ViewValue = NULL;
			}
			$this->Tipo_incidente->ViewCustomAttributes = "";

			// Riesgo
			if (strval($this->Riesgo->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->Riesgo->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='Riesgo'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Riesgo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Riesgo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Riesgo->ViewValue = $this->Riesgo->CurrentValue;
				}
			} else {
				$this->Riesgo->ViewValue = NULL;
			}
			$this->Riesgo->ViewCustomAttributes = "";

			// Parte_Cuerpo
			$this->Parte_Cuerpo->ViewValue = $this->Parte_Cuerpo->CurrentValue;
			$this->Parte_Cuerpo->ViewCustomAttributes = "";

			// ESTADO_AFEC
			$this->ESTADO_AFEC->ViewValue = $this->ESTADO_AFEC->CurrentValue;
			$this->ESTADO_AFEC->ViewCustomAttributes = "";

			// EVACUADO
			if (strval($this->EVACUADO->CurrentValue) <> "") {
				switch ($this->EVACUADO->CurrentValue) {
					case $this->EVACUADO->FldTagValue(1):
						$this->EVACUADO->ViewValue = $this->EVACUADO->FldTagCaption(1) <> "" ? $this->EVACUADO->FldTagCaption(1) : $this->EVACUADO->CurrentValue;
						break;
					case $this->EVACUADO->FldTagValue(2):
						$this->EVACUADO->ViewValue = $this->EVACUADO->FldTagCaption(2) <> "" ? $this->EVACUADO->FldTagCaption(2) : $this->EVACUADO->CurrentValue;
						break;
					default:
						$this->EVACUADO->ViewValue = $this->EVACUADO->CurrentValue;
				}
			} else {
				$this->EVACUADO->ViewValue = NULL;
			}
			$this->EVACUADO->ViewCustomAttributes = "";

			// DESC_ACC
			$this->DESC_ACC->ViewValue = $this->DESC_ACC->CurrentValue;
			$this->DESC_ACC->ViewCustomAttributes = "";

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

			// llave_2
			$this->llave_2->ViewValue = $this->llave_2->CurrentValue;
			$this->llave_2->ViewCustomAttributes = "";

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

			// Riesgo
			$this->Riesgo->LinkCustomAttributes = "";
			$this->Riesgo->HrefValue = "";
			$this->Riesgo->TooltipValue = "";

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

			// llave_2
			$this->llave_2->LinkCustomAttributes = "";
			$this->llave_2->HrefValue = "";
			$this->llave_2->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// llave
			$this->llave->EditAttrs["class"] = "form-control";
			$this->llave->EditCustomAttributes = "";
			$this->llave->EditValue = ew_HtmlEncode($this->llave->AdvancedSearch->SearchValue);
			$this->llave->PlaceHolder = ew_RemoveHtml($this->llave->FldCaption());

			// F_Sincron
			$this->F_Sincron->EditAttrs["class"] = "form-control";
			$this->F_Sincron->EditCustomAttributes = "";
			$this->F_Sincron->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->F_Sincron->AdvancedSearch->SearchValue, 5), 5));
			$this->F_Sincron->PlaceHolder = ew_RemoveHtml($this->F_Sincron->FldCaption());

			// USUARIO
			$this->USUARIO->EditAttrs["class"] = "form-control";
			$this->USUARIO->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->USUARIO->EditValue = $arwrk;

			// Cargo_gme
			$this->Cargo_gme->EditAttrs["class"] = "form-control";
			$this->Cargo_gme->EditCustomAttributes = "";
			$this->Cargo_gme->EditValue = ew_HtmlEncode($this->Cargo_gme->AdvancedSearch->SearchValue);
			$this->Cargo_gme->PlaceHolder = ew_RemoveHtml($this->Cargo_gme->FldCaption());

			// NOM_PE
			$this->NOM_PE->EditAttrs["class"] = "form-control";
			$this->NOM_PE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
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
			$this->Otro_PE->EditValue = ew_HtmlEncode($this->Otro_PE->AdvancedSearch->SearchValue);
			$this->Otro_PE->PlaceHolder = ew_RemoveHtml($this->Otro_PE->FldCaption());

			// NOM_APOYO
			$this->NOM_APOYO->EditAttrs["class"] = "form-control";
			$this->NOM_APOYO->EditCustomAttributes = "";

			// Otro_Nom_Apoyo
			$this->Otro_Nom_Apoyo->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_Apoyo->EditCustomAttributes = "";
			$this->Otro_Nom_Apoyo->EditValue = ew_HtmlEncode($this->Otro_Nom_Apoyo->AdvancedSearch->SearchValue);
			$this->Otro_Nom_Apoyo->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Apoyo->FldCaption());

			// Otro_CC_Apoyo
			$this->Otro_CC_Apoyo->EditAttrs["class"] = "form-control";
			$this->Otro_CC_Apoyo->EditCustomAttributes = "";
			$this->Otro_CC_Apoyo->EditValue = ew_HtmlEncode($this->Otro_CC_Apoyo->AdvancedSearch->SearchValue);
			$this->Otro_CC_Apoyo->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Apoyo->FldCaption());

			// NOM_ENLACE
			$this->NOM_ENLACE->EditAttrs["class"] = "form-control";
			$this->NOM_ENLACE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_ENLACE`, `NOM_ENLACE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_ENLACE`, `NOM_ENLACE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->NOM_ENLACE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `NOM_ENLACE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->NOM_ENLACE->EditValue = $arwrk;

			// Otro_Nom_Enlace
			$this->Otro_Nom_Enlace->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_Enlace->EditCustomAttributes = "";
			$this->Otro_Nom_Enlace->EditValue = ew_HtmlEncode($this->Otro_Nom_Enlace->AdvancedSearch->SearchValue);
			$this->Otro_Nom_Enlace->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Enlace->FldCaption());

			// Otro_CC_Enlace
			$this->Otro_CC_Enlace->EditAttrs["class"] = "form-control";
			$this->Otro_CC_Enlace->EditCustomAttributes = "";
			$this->Otro_CC_Enlace->EditValue = ew_HtmlEncode($this->Otro_CC_Enlace->AdvancedSearch->SearchValue);
			$this->Otro_CC_Enlace->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Enlace->FldCaption());

			// NOM_PGE
			$this->NOM_PGE->EditAttrs["class"] = "form-control";
			$this->NOM_PGE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view1_acc`";
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

			// Otro_Nom_PGE
			$this->Otro_Nom_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_PGE->EditCustomAttributes = "";
			$this->Otro_Nom_PGE->EditValue = ew_HtmlEncode($this->Otro_Nom_PGE->AdvancedSearch->SearchValue);
			$this->Otro_Nom_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_PGE->FldCaption());

			// Otro_CC_PGE
			$this->Otro_CC_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_CC_PGE->EditCustomAttributes = "";
			$this->Otro_CC_PGE->EditValue = ew_HtmlEncode($this->Otro_CC_PGE->AdvancedSearch->SearchValue);
			$this->Otro_CC_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_CC_PGE->FldCaption());

			// Departamento
			$this->Departamento->EditAttrs["class"] = "form-control";
			$this->Departamento->EditCustomAttributes = "";
			$this->Departamento->EditValue = ew_HtmlEncode($this->Departamento->AdvancedSearch->SearchValue);
			$this->Departamento->PlaceHolder = ew_RemoveHtml($this->Departamento->FldCaption());

			// Muncipio
			$this->Muncipio->EditAttrs["class"] = "form-control";
			$this->Muncipio->EditCustomAttributes = "";
			$this->Muncipio->EditValue = ew_HtmlEncode($this->Muncipio->AdvancedSearch->SearchValue);
			$this->Muncipio->PlaceHolder = ew_RemoveHtml($this->Muncipio->FldCaption());

			// NOM_VDA
			$this->NOM_VDA->EditAttrs["class"] = "form-control";
			$this->NOM_VDA->EditCustomAttributes = "";
			$this->NOM_VDA->EditValue = ew_HtmlEncode($this->NOM_VDA->AdvancedSearch->SearchValue);
			$this->NOM_VDA->PlaceHolder = ew_RemoveHtml($this->NOM_VDA->FldCaption());

			// LATITUD
			$this->LATITUD->EditAttrs["class"] = "form-control";
			$this->LATITUD->EditCustomAttributes = "";
			$this->LATITUD->EditValue = ew_HtmlEncode($this->LATITUD->AdvancedSearch->SearchValue);
			$this->LATITUD->PlaceHolder = ew_RemoveHtml($this->LATITUD->FldCaption());

			// GRA_LAT
			$this->GRA_LAT->EditAttrs["class"] = "form-control";
			$this->GRA_LAT->EditCustomAttributes = "";
			$this->GRA_LAT->EditValue = ew_HtmlEncode($this->GRA_LAT->AdvancedSearch->SearchValue);
			$this->GRA_LAT->PlaceHolder = ew_RemoveHtml($this->GRA_LAT->FldCaption());

			// MIN_LAT
			$this->MIN_LAT->EditAttrs["class"] = "form-control";
			$this->MIN_LAT->EditCustomAttributes = "";
			$this->MIN_LAT->EditValue = ew_HtmlEncode($this->MIN_LAT->AdvancedSearch->SearchValue);
			$this->MIN_LAT->PlaceHolder = ew_RemoveHtml($this->MIN_LAT->FldCaption());

			// SEG_LAT
			$this->SEG_LAT->EditAttrs["class"] = "form-control";
			$this->SEG_LAT->EditCustomAttributes = "";
			$this->SEG_LAT->EditValue = ew_HtmlEncode($this->SEG_LAT->AdvancedSearch->SearchValue);
			$this->SEG_LAT->PlaceHolder = ew_RemoveHtml($this->SEG_LAT->FldCaption());

			// GRA_LONG
			$this->GRA_LONG->EditAttrs["class"] = "form-control";
			$this->GRA_LONG->EditCustomAttributes = "";
			$this->GRA_LONG->EditValue = ew_HtmlEncode($this->GRA_LONG->AdvancedSearch->SearchValue);
			$this->GRA_LONG->PlaceHolder = ew_RemoveHtml($this->GRA_LONG->FldCaption());

			// MIN_LONG
			$this->MIN_LONG->EditAttrs["class"] = "form-control";
			$this->MIN_LONG->EditCustomAttributes = "";
			$this->MIN_LONG->EditValue = ew_HtmlEncode($this->MIN_LONG->AdvancedSearch->SearchValue);
			$this->MIN_LONG->PlaceHolder = ew_RemoveHtml($this->MIN_LONG->FldCaption());

			// SEG_LONG
			$this->SEG_LONG->EditAttrs["class"] = "form-control";
			$this->SEG_LONG->EditCustomAttributes = "";
			$this->SEG_LONG->EditValue = ew_HtmlEncode($this->SEG_LONG->AdvancedSearch->SearchValue);
			$this->SEG_LONG->PlaceHolder = ew_RemoveHtml($this->SEG_LONG->FldCaption());

			// FECHA_ACC
			$this->FECHA_ACC->EditAttrs["class"] = "form-control";
			$this->FECHA_ACC->EditCustomAttributes = "";
			$this->FECHA_ACC->EditValue = ew_HtmlEncode($this->FECHA_ACC->AdvancedSearch->SearchValue);
			$this->FECHA_ACC->PlaceHolder = ew_RemoveHtml($this->FECHA_ACC->FldCaption());

			// HORA_ACC
			$this->HORA_ACC->EditAttrs["class"] = "form-control";
			$this->HORA_ACC->EditCustomAttributes = "";
			$this->HORA_ACC->EditValue = ew_HtmlEncode($this->HORA_ACC->AdvancedSearch->SearchValue);
			$this->HORA_ACC->PlaceHolder = ew_RemoveHtml($this->HORA_ACC->FldCaption());

			// Hora_ingreso
			$this->Hora_ingreso->EditAttrs["class"] = "form-control";
			$this->Hora_ingreso->EditCustomAttributes = "";
			$this->Hora_ingreso->EditValue = ew_HtmlEncode($this->Hora_ingreso->AdvancedSearch->SearchValue);
			$this->Hora_ingreso->PlaceHolder = ew_RemoveHtml($this->Hora_ingreso->FldCaption());

			// FP_Armada
			$this->FP_Armada->EditAttrs["class"] = "form-control";
			$this->FP_Armada->EditCustomAttributes = "";
			$this->FP_Armada->EditValue = ew_HtmlEncode($this->FP_Armada->AdvancedSearch->SearchValue);
			$this->FP_Armada->PlaceHolder = ew_RemoveHtml($this->FP_Armada->FldCaption());

			// FP_Ejercito
			$this->FP_Ejercito->EditAttrs["class"] = "form-control";
			$this->FP_Ejercito->EditCustomAttributes = "";
			$this->FP_Ejercito->EditValue = ew_HtmlEncode($this->FP_Ejercito->AdvancedSearch->SearchValue);
			$this->FP_Ejercito->PlaceHolder = ew_RemoveHtml($this->FP_Ejercito->FldCaption());

			// FP_Policia
			$this->FP_Policia->EditAttrs["class"] = "form-control";
			$this->FP_Policia->EditCustomAttributes = "";
			$this->FP_Policia->EditValue = ew_HtmlEncode($this->FP_Policia->AdvancedSearch->SearchValue);
			$this->FP_Policia->PlaceHolder = ew_RemoveHtml($this->FP_Policia->FldCaption());

			// NOM_COMANDANTE
			$this->NOM_COMANDANTE->EditAttrs["class"] = "form-control";
			$this->NOM_COMANDANTE->EditCustomAttributes = "";
			$this->NOM_COMANDANTE->EditValue = ew_HtmlEncode($this->NOM_COMANDANTE->AdvancedSearch->SearchValue);
			$this->NOM_COMANDANTE->PlaceHolder = ew_RemoveHtml($this->NOM_COMANDANTE->FldCaption());

			// TESTI1
			$this->TESTI1->EditAttrs["class"] = "form-control";
			$this->TESTI1->EditCustomAttributes = "";
			$this->TESTI1->EditValue = ew_HtmlEncode($this->TESTI1->AdvancedSearch->SearchValue);
			$this->TESTI1->PlaceHolder = ew_RemoveHtml($this->TESTI1->FldCaption());

			// CC_TESTI1
			$this->CC_TESTI1->EditAttrs["class"] = "form-control";
			$this->CC_TESTI1->EditCustomAttributes = "";
			$this->CC_TESTI1->EditValue = ew_HtmlEncode($this->CC_TESTI1->AdvancedSearch->SearchValue);
			$this->CC_TESTI1->PlaceHolder = ew_RemoveHtml($this->CC_TESTI1->FldCaption());

			// CARGO_TESTI1
			$this->CARGO_TESTI1->EditAttrs["class"] = "form-control";
			$this->CARGO_TESTI1->EditCustomAttributes = "";
			$this->CARGO_TESTI1->EditValue = ew_HtmlEncode($this->CARGO_TESTI1->AdvancedSearch->SearchValue);
			$this->CARGO_TESTI1->PlaceHolder = ew_RemoveHtml($this->CARGO_TESTI1->FldCaption());

			// TESTI2
			$this->TESTI2->EditAttrs["class"] = "form-control";
			$this->TESTI2->EditCustomAttributes = "";
			$this->TESTI2->EditValue = ew_HtmlEncode($this->TESTI2->AdvancedSearch->SearchValue);
			$this->TESTI2->PlaceHolder = ew_RemoveHtml($this->TESTI2->FldCaption());

			// CC_TESTI2
			$this->CC_TESTI2->EditAttrs["class"] = "form-control";
			$this->CC_TESTI2->EditCustomAttributes = "";
			$this->CC_TESTI2->EditValue = ew_HtmlEncode($this->CC_TESTI2->AdvancedSearch->SearchValue);
			$this->CC_TESTI2->PlaceHolder = ew_RemoveHtml($this->CC_TESTI2->FldCaption());

			// CARGO_TESTI2
			$this->CARGO_TESTI2->EditAttrs["class"] = "form-control";
			$this->CARGO_TESTI2->EditCustomAttributes = "";
			$this->CARGO_TESTI2->EditValue = ew_HtmlEncode($this->CARGO_TESTI2->AdvancedSearch->SearchValue);
			$this->CARGO_TESTI2->PlaceHolder = ew_RemoveHtml($this->CARGO_TESTI2->FldCaption());

			// Afectados
			$this->Afectados->EditAttrs["class"] = "form-control";
			$this->Afectados->EditCustomAttributes = "";
			$this->Afectados->EditValue = ew_HtmlEncode($this->Afectados->AdvancedSearch->SearchValue);
			$this->Afectados->PlaceHolder = ew_RemoveHtml($this->Afectados->FldCaption());

			// NUM_Afectado
			$this->NUM_Afectado->EditAttrs["class"] = "form-control";
			$this->NUM_Afectado->EditCustomAttributes = "";
			$this->NUM_Afectado->EditValue = ew_HtmlEncode($this->NUM_Afectado->AdvancedSearch->SearchValue);
			$this->NUM_Afectado->PlaceHolder = ew_RemoveHtml($this->NUM_Afectado->FldCaption());

			// Nom_Afectado
			$this->Nom_Afectado->EditAttrs["class"] = "form-control";
			$this->Nom_Afectado->EditCustomAttributes = "";
			$this->Nom_Afectado->EditValue = ew_HtmlEncode($this->Nom_Afectado->AdvancedSearch->SearchValue);
			$this->Nom_Afectado->PlaceHolder = ew_RemoveHtml($this->Nom_Afectado->FldCaption());

			// CC_Afectado
			$this->CC_Afectado->EditAttrs["class"] = "form-control";
			$this->CC_Afectado->EditCustomAttributes = "";
			$this->CC_Afectado->EditValue = ew_HtmlEncode($this->CC_Afectado->AdvancedSearch->SearchValue);
			$this->CC_Afectado->PlaceHolder = ew_RemoveHtml($this->CC_Afectado->FldCaption());

			// Cargo_Afectado
			$this->Cargo_Afectado->EditAttrs["class"] = "form-control";
			$this->Cargo_Afectado->EditCustomAttributes = "";
			$this->Cargo_Afectado->EditValue = ew_HtmlEncode($this->Cargo_Afectado->AdvancedSearch->SearchValue);
			$this->Cargo_Afectado->PlaceHolder = ew_RemoveHtml($this->Cargo_Afectado->FldCaption());

			// Tipo_incidente
			$this->Tipo_incidente->EditAttrs["class"] = "form-control";
			$this->Tipo_incidente->EditCustomAttributes = "";
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
			$lookuptblfilter = "`list name`='Incidente'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Tipo_incidente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Tipo_incidente->EditValue = $arwrk;

			// Riesgo
			$this->Riesgo->EditAttrs["class"] = "form-control";
			$this->Riesgo->EditCustomAttributes = "";
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
			$lookuptblfilter = "`list name`='Riesgo'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Riesgo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Riesgo->EditValue = $arwrk;

			// Parte_Cuerpo
			$this->Parte_Cuerpo->EditAttrs["class"] = "form-control";
			$this->Parte_Cuerpo->EditCustomAttributes = "";
			$this->Parte_Cuerpo->EditValue = ew_HtmlEncode($this->Parte_Cuerpo->AdvancedSearch->SearchValue);
			$this->Parte_Cuerpo->PlaceHolder = ew_RemoveHtml($this->Parte_Cuerpo->FldCaption());

			// ESTADO_AFEC
			$this->ESTADO_AFEC->EditAttrs["class"] = "form-control";
			$this->ESTADO_AFEC->EditCustomAttributes = "";
			$this->ESTADO_AFEC->EditValue = ew_HtmlEncode($this->ESTADO_AFEC->AdvancedSearch->SearchValue);
			$this->ESTADO_AFEC->PlaceHolder = ew_RemoveHtml($this->ESTADO_AFEC->FldCaption());

			// EVACUADO
			$this->EVACUADO->EditAttrs["class"] = "form-control";
			$this->EVACUADO->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->EVACUADO->FldTagValue(1), $this->EVACUADO->FldTagCaption(1) <> "" ? $this->EVACUADO->FldTagCaption(1) : $this->EVACUADO->FldTagValue(1));
			$arwrk[] = array($this->EVACUADO->FldTagValue(2), $this->EVACUADO->FldTagCaption(2) <> "" ? $this->EVACUADO->FldTagCaption(2) : $this->EVACUADO->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->EVACUADO->EditValue = $arwrk;

			// DESC_ACC
			$this->DESC_ACC->EditAttrs["class"] = "form-control";
			$this->DESC_ACC->EditCustomAttributes = "";
			$this->DESC_ACC->EditValue = ew_HtmlEncode($this->DESC_ACC->AdvancedSearch->SearchValue);
			$this->DESC_ACC->PlaceHolder = ew_RemoveHtml($this->DESC_ACC->FldCaption());

			// Modificado
			$this->Modificado->EditAttrs["class"] = "form-control";
			$this->Modificado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Modificado->FldTagValue(1), $this->Modificado->FldTagCaption(1) <> "" ? $this->Modificado->FldTagCaption(1) : $this->Modificado->FldTagValue(1));
			$arwrk[] = array($this->Modificado->FldTagValue(2), $this->Modificado->FldTagCaption(2) <> "" ? $this->Modificado->FldTagCaption(2) : $this->Modificado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Modificado->EditValue = $arwrk;

			// llave_2
			$this->llave_2->EditAttrs["class"] = "form-control";
			$this->llave_2->EditCustomAttributes = "";
			$this->llave_2->EditValue = ew_HtmlEncode($this->llave_2->AdvancedSearch->SearchValue);
			$this->llave_2->PlaceHolder = ew_RemoveHtml($this->llave_2->FldCaption());
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
		$this->llave->AdvancedSearch->Load();
		$this->F_Sincron->AdvancedSearch->Load();
		$this->USUARIO->AdvancedSearch->Load();
		$this->Cargo_gme->AdvancedSearch->Load();
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_PE->AdvancedSearch->Load();
		$this->NOM_APOYO->AdvancedSearch->Load();
		$this->Otro_Nom_Apoyo->AdvancedSearch->Load();
		$this->Otro_CC_Apoyo->AdvancedSearch->Load();
		$this->NOM_ENLACE->AdvancedSearch->Load();
		$this->Otro_Nom_Enlace->AdvancedSearch->Load();
		$this->Otro_CC_Enlace->AdvancedSearch->Load();
		$this->NOM_PGE->AdvancedSearch->Load();
		$this->Otro_Nom_PGE->AdvancedSearch->Load();
		$this->Otro_CC_PGE->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->NOM_VDA->AdvancedSearch->Load();
		$this->LATITUD->AdvancedSearch->Load();
		$this->GRA_LAT->AdvancedSearch->Load();
		$this->MIN_LAT->AdvancedSearch->Load();
		$this->SEG_LAT->AdvancedSearch->Load();
		$this->GRA_LONG->AdvancedSearch->Load();
		$this->MIN_LONG->AdvancedSearch->Load();
		$this->SEG_LONG->AdvancedSearch->Load();
		$this->FECHA_ACC->AdvancedSearch->Load();
		$this->HORA_ACC->AdvancedSearch->Load();
		$this->Hora_ingreso->AdvancedSearch->Load();
		$this->FP_Armada->AdvancedSearch->Load();
		$this->FP_Ejercito->AdvancedSearch->Load();
		$this->FP_Policia->AdvancedSearch->Load();
		$this->NOM_COMANDANTE->AdvancedSearch->Load();
		$this->TESTI1->AdvancedSearch->Load();
		$this->CC_TESTI1->AdvancedSearch->Load();
		$this->CARGO_TESTI1->AdvancedSearch->Load();
		$this->TESTI2->AdvancedSearch->Load();
		$this->CC_TESTI2->AdvancedSearch->Load();
		$this->CARGO_TESTI2->AdvancedSearch->Load();
		$this->Afectados->AdvancedSearch->Load();
		$this->NUM_Afectado->AdvancedSearch->Load();
		$this->Nom_Afectado->AdvancedSearch->Load();
		$this->CC_Afectado->AdvancedSearch->Load();
		$this->Cargo_Afectado->AdvancedSearch->Load();
		$this->Tipo_incidente->AdvancedSearch->Load();
		$this->Riesgo->AdvancedSearch->Load();
		$this->Parte_Cuerpo->AdvancedSearch->Load();
		$this->ESTADO_AFEC->AdvancedSearch->Load();
		$this->EVACUADO->AdvancedSearch->Load();
		$this->DESC_ACC->AdvancedSearch->Load();
		$this->Modificado->AdvancedSearch->Load();
		$this->llave_2->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_view1_acc\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view1_acc',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview1_acclist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view1_acc_list)) $view1_acc_list = new cview1_acc_list();

// Page init
$view1_acc_list->Page_Init();

// Page main
$view1_acc_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_acc_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view1_acc->Export == "") { ?>
<script type="text/javascript">

// Page object
var view1_acc_list = new ew_Page("view1_acc_list");
view1_acc_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view1_acc_list.PageID; // For backward compatibility

// Form object
var fview1_acclist = new ew_Form("fview1_acclist");
fview1_acclist.FormKeyCountName = '<?php echo $view1_acc_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview1_acclist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1_acclist.ValidateRequired = true;
<?php } else { ?>
fview1_acclist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview1_acclist.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclist.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclist.Lists["x_NOM_APOYO"] = {"LinkField":"x_NOM_APOYO","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_APOYO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclist.Lists["x_NOM_ENLACE"] = {"LinkField":"x_NOM_ENLACE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_ENLACE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclist.Lists["x_NOM_PGE"] = {"LinkField":"x_NOM_PGE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PGE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclist.Lists["x_Tipo_incidente"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclist.Lists["x_Riesgo"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fview1_acclistsrch = new ew_Form("fview1_acclistsrch");

// Validate function for search
fview1_acclistsrch.Validate = function(fobj) {
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
fview1_acclistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1_acclistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fview1_acclistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fview1_acclistsrch.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclistsrch.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclistsrch.Lists["x_NOM_ENLACE"] = {"LinkField":"x_NOM_ENLACE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_ENLACE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclistsrch.Lists["x_NOM_PGE"] = {"LinkField":"x_NOM_PGE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PGE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclistsrch.Lists["x_Tipo_incidente"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_acclistsrch.Lists["x_Riesgo"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view1_acc->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view1_acc->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($view1_acc->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>

<div class="clearfix"></div>
</div>
<?php } ?>
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
<div class="ewToolbar">
<H2> Reporte de accidentes (Módulo en prueba)</h2>
<p>La siguiente tabla contiene los reportes de accidentes realizados de prueba en la erradicación 2015 a la fecha</p>

<hr>
<table>
	<tr>
		<td>
			<?php if ($view1_acc_list->TotalRecs > 0 && $view1_acc_list->ExportOptions->Visible()) { ?>

			<?php $view1_acc_list->ExportOptions->Render("body") ?>
			<?php } ?>

		</td>
		<td>
			Si desea exportar la tabla en formato excel haga click en el siguiente icono 
		</td>	
	</tr>	
</table> 

<hr>

</div>
<?php if ($view1_acc->Export == "") { ?>

<div>
<br>
<table>
	<tr>
		<td>
			<?php if ($view1_acc_list->SearchOptions->Visible()) { ?>
			<?php $view1_acc_list->SearchOptions->Render("body") ?>
			<?php } ?>
		</td>
		<td>
			Si desea realizar filtros en la tabla haga click en el siguiente icono e ingrese el dato en la columna correspondiente
		</td>	
	</tr>
</table>
<br>
</div>

<hr>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($view1_acc_list->TotalRecs <= 0)
			$view1_acc_list->TotalRecs = $view1_acc->SelectRecordCount();
	} else {
		if (!$view1_acc_list->Recordset && ($view1_acc_list->Recordset = $view1_acc_list->LoadRecordset()))
			$view1_acc_list->TotalRecs = $view1_acc_list->Recordset->RecordCount();
	}
	$view1_acc_list->StartRec = 1;
	if ($view1_acc_list->DisplayRecs <= 0 || ($view1_acc->Export <> "" && $view1_acc->ExportAll)) // Display all records
		$view1_acc_list->DisplayRecs = $view1_acc_list->TotalRecs;
	if (!($view1_acc->Export <> "" && $view1_acc->ExportAll))
		$view1_acc_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view1_acc_list->Recordset = $view1_acc_list->LoadRecordset($view1_acc_list->StartRec-1, $view1_acc_list->DisplayRecs);

	// Set no record found message
	if ($view1_acc->CurrentAction == "" && $view1_acc_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view1_acc_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view1_acc_list->SearchWhere == "0=101")
			$view1_acc_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view1_acc_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view1_acc_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view1_acc->Export == "" && $view1_acc->CurrentAction == "") { ?>
<form name="fview1_acclistsrch" id="fview1_acclistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view1_acc_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fview1_acclistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view1_acc">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$view1_acc_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$view1_acc->RowType = EW_ROWTYPE_SEARCH;

// Render row
$view1_acc->ResetAttrs();
$view1_acc_list->RenderRow();
?>
<br>
<table>
	<tr>
		<td>
			<label for="x_USUARIO" class="ewSearchCaption ewLabel"><?php echo $view1_acc->USUARIO->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_USUARIO" id="z_USUARIO" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 355px;" data-field="x_USUARIO" id="x_USUARIO" name="x_USUARIO"<?php echo $view1_acc->USUARIO->EditAttributes() ?>>
				<?php
				if (is_array($view1_acc->USUARIO->EditValue)) {
					$arwrk = $view1_acc->USUARIO->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view1_acc->USUARIO->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview1_acclistsrch.Lists["x_USUARIO"].Options = <?php echo (is_array($view1_acc->USUARIO->EditValue)) ? ew_ArrayToJson($view1_acc->USUARIO->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_NOM_PE" class="ewSearchCaption ewLabel"><?php echo $view1_acc->NOM_PE->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_PE" id="z_NOM_PE" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 355px;" data-field="x_NOM_PE" id="x_NOM_PE" name="x_NOM_PE"<?php echo $view1_acc->NOM_PE->EditAttributes() ?>>
			<?php
			if (is_array($view1_acc->NOM_PE->EditValue)) {
				$arwrk = $view1_acc->NOM_PE->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($view1_acc->NOM_PE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			fview1_acclistsrch.Lists["x_NOM_PE"].Options = <?php echo (is_array($view1_acc->NOM_PE->EditValue)) ? ew_ArrayToJson($view1_acc->NOM_PE->EditValue, 1) : "[]" ?>;
			</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_NOM_ENLACE" class="ewSearchCaption ewLabel"><?php echo $view1_acc->NOM_ENLACE->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_ENLACE" id="z_NOM_ENLACE" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 355px;" data-field="x_NOM_ENLACE" id="x_NOM_ENLACE" name="x_NOM_ENLACE"<?php echo $view1_acc->NOM_ENLACE->EditAttributes() ?>>
			<?php
			if (is_array($view1_acc->NOM_ENLACE->EditValue)) {
				$arwrk = $view1_acc->NOM_ENLACE->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($view1_acc->NOM_ENLACE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			fview1_acclistsrch.Lists["x_NOM_ENLACE"].Options = <?php echo (is_array($view1_acc->NOM_ENLACE->EditValue)) ? ew_ArrayToJson($view1_acc->NOM_ENLACE->EditValue, 1) : "[]" ?>;
			</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_NOM_PGE" class="ewSearchCaption ewLabel"><?php echo $view1_acc->NOM_PGE->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_PGE" id="z_NOM_PGE" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 355px;" data-field="x_NOM_PGE" id="x_NOM_PGE" name="x_NOM_PGE"<?php echo $view1_acc->NOM_PGE->EditAttributes() ?>>
			<?php
			if (is_array($view1_acc->NOM_PGE->EditValue)) {
				$arwrk = $view1_acc->NOM_PGE->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($view1_acc->NOM_PGE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			fview1_acclistsrch.Lists["x_NOM_PGE"].Options = <?php echo (is_array($view1_acc->NOM_PGE->EditValue)) ? ew_ArrayToJson($view1_acc->NOM_PGE->EditValue, 1) : "[]" ?>;
			</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_CC_Afectado" class="ewSearchCaption ewLabel"><?php echo $view1_acc->CC_Afectado->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_CC_Afectado" id="z_CC_Afectado" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<input style="min-width: 355px;" type="text" data-field="x_CC_Afectado" name="x_CC_Afectado" id="x_CC_Afectado" size="30" maxlength="255" placeholder="Ingrese la cédula del afectado" value="<?php echo $view1_acc->CC_Afectado->EditValue ?>"<?php echo $view1_acc->CC_Afectado->EditAttributes() ?>>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_Tipo_incidente" class="ewSearchCaption ewLabel"><?php echo $view1_acc->Tipo_incidente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Tipo_incidente" id="z_Tipo_incidente" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 355px;" data-field="x_Tipo_incidente" id="x_Tipo_incidente" name="x_Tipo_incidente"<?php echo $view1_acc->Tipo_incidente->EditAttributes() ?>>
			<?php
			if (is_array($view1_acc->Tipo_incidente->EditValue)) {
				$arwrk = $view1_acc->Tipo_incidente->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($view1_acc->Tipo_incidente->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			fview1_acclistsrch.Lists["x_Tipo_incidente"].Options = <?php echo (is_array($view1_acc->Tipo_incidente->EditValue)) ? ew_ArrayToJson($view1_acc->Tipo_incidente->EditValue, 1) : "[]" ?>;
			</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_Riesgo" class="ewSearchCaption ewLabel"><?php echo $view1_acc->Riesgo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Riesgo" id="z_Riesgo" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 355px;" data-field="x_Riesgo" id="x_Riesgo" name="x_Riesgo"<?php echo $view1_acc->Riesgo->EditAttributes() ?>>
			<?php
			if (is_array($view1_acc->Riesgo->EditValue)) {
				$arwrk = $view1_acc->Riesgo->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($view1_acc->Riesgo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			fview1_acclistsrch.Lists["x_Riesgo"].Options = <?php echo (is_array($view1_acc->Riesgo->EditValue)) ? ew_ArrayToJson($view1_acc->Riesgo->EditValue, 1) : "[]" ?>;
			</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_EVACUADO" class="ewSearchCaption ewLabel"><?php echo $view1_acc->EVACUADO->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_EVACUADO" id="z_EVACUADO" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 355px;" data-field="x_EVACUADO" id="x_EVACUADO" name="x_EVACUADO"<?php echo $view1_acc->EVACUADO->EditAttributes() ?>>
			<?php
			if (is_array($view1_acc->EVACUADO->EditValue)) {
				$arwrk = $view1_acc->EVACUADO->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($view1_acc->EVACUADO->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
		</td>
	</tr>
	
</table>

	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>

	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $view1_acc_list->ShowPageHeader(); ?>
<?php
$view1_acc_list->ShowMessage();
?>
<?php if ($view1_acc_list->TotalRecs > 0 || $view1_acc->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($view1_acc->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($view1_acc->CurrentAction <> "gridadd" && $view1_acc->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view1_acc_list->Pager)) $view1_acc_list->Pager = new cPrevNextPager($view1_acc_list->StartRec, $view1_acc_list->DisplayRecs, $view1_acc_list->TotalRecs) ?>
<?php if ($view1_acc_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view1_acc_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view1_acc_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view1_acc_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view1_acc_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view1_acc_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view1_acc_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_acc_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_acc_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_acc_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_acc_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview1_acclist" id="fview1_acclist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view1_acc_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view1_acc_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view1_acc">
<div id="gmp_view1_acc" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view1_acc_list->TotalRecs > 0) { ?>
<table id="tbl_view1_acclist" class="table ewTable">
<?php echo $view1_acc->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view1_acc->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view1_acc_list->RenderListOptions();

// Render list options (header, left)
$view1_acc_list->ListOptions->Render("header", "left");
?>
<?php if ($view1_acc->llave->Visible) { // llave ?>
	<?php if ($view1_acc->SortUrl($view1_acc->llave) == "") { ?>
		<th data-name="llave"><div id="elh_view1_acc_llave" class="view1_acc_llave"><div class="ewTableHeaderCaption"><?php echo $view1_acc->llave->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="llave"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->llave) ?>',2);"><div id="elh_view1_acc_llave" class="view1_acc_llave">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->llave->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->llave->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->llave->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->F_Sincron->Visible) { // F_Sincron ?>
	<?php if ($view1_acc->SortUrl($view1_acc->F_Sincron) == "") { ?>
		<th data-name="F_Sincron"><div id="elh_view1_acc_F_Sincron" class="view1_acc_F_Sincron"><div class="ewTableHeaderCaption"><?php echo $view1_acc->F_Sincron->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F_Sincron"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->F_Sincron) ?>',2);"><div id="elh_view1_acc_F_Sincron" class="view1_acc_F_Sincron">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->F_Sincron->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->F_Sincron->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->F_Sincron->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>	
<?php if ($view1_acc->FECHA_ACC->Visible) { // FECHA_ACC ?>
	<?php if ($view1_acc->SortUrl($view1_acc->FECHA_ACC) == "") { ?>
		<th data-name="FECHA_ACC"><div id="elh_view1_acc_FECHA_ACC" class="view1_acc_FECHA_ACC"><div class="ewTableHeaderCaption"><?php echo $view1_acc->FECHA_ACC->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FECHA_ACC"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->FECHA_ACC) ?>',2);"><div id="elh_view1_acc_FECHA_ACC" class="view1_acc_FECHA_ACC">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->FECHA_ACC->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->FECHA_ACC->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->FECHA_ACC->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->HORA_ACC->Visible) { // HORA_ACC ?>
	<?php if ($view1_acc->SortUrl($view1_acc->HORA_ACC) == "") { ?>
		<th data-name="HORA_ACC"><div id="elh_view1_acc_HORA_ACC" class="view1_acc_HORA_ACC"><div class="ewTableHeaderCaption"><?php echo $view1_acc->HORA_ACC->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="HORA_ACC"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->HORA_ACC) ?>',2);"><div id="elh_view1_acc_HORA_ACC" class="view1_acc_HORA_ACC">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->HORA_ACC->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->HORA_ACC->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->HORA_ACC->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>	
<?php if ($view1_acc->USUARIO->Visible) { // USUARIO ?>
	<?php if ($view1_acc->SortUrl($view1_acc->USUARIO) == "") { ?>
		<th data-name="USUARIO"><div id="elh_view1_acc_USUARIO" class="view1_acc_USUARIO"><div class="ewTableHeaderCaption"><?php echo $view1_acc->USUARIO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="USUARIO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->USUARIO) ?>',2);"><div id="elh_view1_acc_USUARIO" class="view1_acc_USUARIO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->USUARIO->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->USUARIO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->USUARIO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Cargo_gme->Visible) { // Cargo_gme ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Cargo_gme) == "") { ?>
		<th data-name="Cargo_gme"><div id="elh_view1_acc_Cargo_gme" class="view1_acc_Cargo_gme"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Cargo_gme->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_gme"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Cargo_gme) ?>',2);"><div id="elh_view1_acc_Cargo_gme" class="view1_acc_Cargo_gme">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Cargo_gme->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Cargo_gme->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Cargo_gme->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_PE->Visible) { // NOM_PE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_PE) == "") { ?>
		<th data-name="NOM_PE"><div id="elh_view1_acc_NOM_PE" class="view1_acc_NOM_PE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_PE) ?>',2);"><div id="elh_view1_acc_NOM_PE" class="view1_acc_NOM_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_PE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_PE->Visible) { // Otro_PE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_PE) == "") { ?>
		<th data-name="Otro_PE"><div id="elh_view1_acc_Otro_PE" class="view1_acc_Otro_PE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_PE) ?>',2);"><div id="elh_view1_acc_Otro_PE" class="view1_acc_Otro_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_APOYO->Visible) { // NOM_APOYO ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_APOYO) == "") { ?>
		<th data-name="NOM_APOYO"><div id="elh_view1_acc_NOM_APOYO" class="view1_acc_NOM_APOYO"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_APOYO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_APOYO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_APOYO) ?>',2);"><div id="elh_view1_acc_NOM_APOYO" class="view1_acc_NOM_APOYO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_APOYO->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_APOYO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_APOYO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_Nom_Apoyo) == "") { ?>
		<th data-name="Otro_Nom_Apoyo"><div id="elh_view1_acc_Otro_Nom_Apoyo" class="view1_acc_Otro_Nom_Apoyo"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_Apoyo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_Apoyo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_Nom_Apoyo) ?>',2);"><div id="elh_view1_acc_Otro_Nom_Apoyo" class="view1_acc_Otro_Nom_Apoyo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_Apoyo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_Nom_Apoyo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_Nom_Apoyo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_CC_Apoyo) == "") { ?>
		<th data-name="Otro_CC_Apoyo"><div id="elh_view1_acc_Otro_CC_Apoyo" class="view1_acc_Otro_CC_Apoyo"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_Apoyo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_Apoyo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_CC_Apoyo) ?>',2);"><div id="elh_view1_acc_Otro_CC_Apoyo" class="view1_acc_Otro_CC_Apoyo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_Apoyo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_CC_Apoyo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_CC_Apoyo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_ENLACE->Visible) { // NOM_ENLACE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_ENLACE) == "") { ?>
		<th data-name="NOM_ENLACE"><div id="elh_view1_acc_NOM_ENLACE" class="view1_acc_NOM_ENLACE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_ENLACE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_ENLACE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_ENLACE) ?>',2);"><div id="elh_view1_acc_NOM_ENLACE" class="view1_acc_NOM_ENLACE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_ENLACE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_ENLACE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_ENLACE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_Nom_Enlace->Visible) { // Otro_Nom_Enlace ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_Nom_Enlace) == "") { ?>
		<th data-name="Otro_Nom_Enlace"><div id="elh_view1_acc_Otro_Nom_Enlace" class="view1_acc_Otro_Nom_Enlace"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_Enlace->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_Enlace"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_Nom_Enlace) ?>',2);"><div id="elh_view1_acc_Otro_Nom_Enlace" class="view1_acc_Otro_Nom_Enlace">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_Enlace->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_Nom_Enlace->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_Nom_Enlace->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_CC_Enlace->Visible) { // Otro_CC_Enlace ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_CC_Enlace) == "") { ?>
		<th data-name="Otro_CC_Enlace"><div id="elh_view1_acc_Otro_CC_Enlace" class="view1_acc_Otro_CC_Enlace"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_Enlace->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_Enlace"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_CC_Enlace) ?>',2);"><div id="elh_view1_acc_Otro_CC_Enlace" class="view1_acc_Otro_CC_Enlace">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_Enlace->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_CC_Enlace->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_CC_Enlace->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_PGE->Visible) { // NOM_PGE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_PGE) == "") { ?>
		<th data-name="NOM_PGE"><div id="elh_view1_acc_NOM_PGE" class="view1_acc_NOM_PGE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_PGE) ?>',2);"><div id="elh_view1_acc_NOM_PGE" class="view1_acc_NOM_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_PGE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_Nom_PGE->Visible) { // Otro_Nom_PGE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_Nom_PGE) == "") { ?>
		<th data-name="Otro_Nom_PGE"><div id="elh_view1_acc_Otro_Nom_PGE" class="view1_acc_Otro_Nom_PGE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_Nom_PGE) ?>',2);"><div id="elh_view1_acc_Otro_Nom_PGE" class="view1_acc_Otro_Nom_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_PGE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_Nom_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_Nom_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_CC_PGE) == "") { ?>
		<th data-name="Otro_CC_PGE"><div id="elh_view1_acc_Otro_CC_PGE" class="view1_acc_Otro_CC_PGE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_CC_PGE) ?>',2);"><div id="elh_view1_acc_Otro_CC_PGE" class="view1_acc_Otro_CC_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_PGE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_CC_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_CC_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Departamento->Visible) { // Departamento ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Departamento) == "") { ?>
		<th data-name="Departamento"><div id="elh_view1_acc_Departamento" class="view1_acc_Departamento"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Departamento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Departamento) ?>',2);"><div id="elh_view1_acc_Departamento" class="view1_acc_Departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Departamento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Muncipio->Visible) { // Muncipio ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Muncipio) == "") { ?>
		<th data-name="Muncipio"><div id="elh_view1_acc_Muncipio" class="view1_acc_Muncipio"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Muncipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Muncipio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Muncipio) ?>',2);"><div id="elh_view1_acc_Muncipio" class="view1_acc_Muncipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Muncipio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Muncipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Muncipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_VDA->Visible) { // NOM_VDA ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_VDA) == "") { ?>
		<th data-name="NOM_VDA"><div id="elh_view1_acc_NOM_VDA" class="view1_acc_NOM_VDA"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_VDA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_VDA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_VDA) ?>',2);"><div id="elh_view1_acc_NOM_VDA" class="view1_acc_NOM_VDA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_VDA->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_VDA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_VDA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->LATITUD->Visible) { // LATITUD ?>
	<?php if ($view1_acc->SortUrl($view1_acc->LATITUD) == "") { ?>
		<th data-name="LATITUD"><div id="elh_view1_acc_LATITUD" class="view1_acc_LATITUD"><div class="ewTableHeaderCaption"><?php echo $view1_acc->LATITUD->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="LATITUD"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->LATITUD) ?>',2);"><div id="elh_view1_acc_LATITUD" class="view1_acc_LATITUD">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->LATITUD->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->LATITUD->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->LATITUD->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->GRA_LAT->Visible) { // GRA_LAT ?>
	<?php if ($view1_acc->SortUrl($view1_acc->GRA_LAT) == "") { ?>
		<th data-name="GRA_LAT"><div id="elh_view1_acc_GRA_LAT" class="view1_acc_GRA_LAT"><div class="ewTableHeaderCaption"><?php echo $view1_acc->GRA_LAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->GRA_LAT) ?>',2);"><div id="elh_view1_acc_GRA_LAT" class="view1_acc_GRA_LAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->GRA_LAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->GRA_LAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->GRA_LAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->MIN_LAT->Visible) { // MIN_LAT ?>
	<?php if ($view1_acc->SortUrl($view1_acc->MIN_LAT) == "") { ?>
		<th data-name="MIN_LAT"><div id="elh_view1_acc_MIN_LAT" class="view1_acc_MIN_LAT"><div class="ewTableHeaderCaption"><?php echo $view1_acc->MIN_LAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->MIN_LAT) ?>',2);"><div id="elh_view1_acc_MIN_LAT" class="view1_acc_MIN_LAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->MIN_LAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->MIN_LAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->MIN_LAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->SEG_LAT->Visible) { // SEG_LAT ?>
	<?php if ($view1_acc->SortUrl($view1_acc->SEG_LAT) == "") { ?>
		<th data-name="SEG_LAT"><div id="elh_view1_acc_SEG_LAT" class="view1_acc_SEG_LAT"><div class="ewTableHeaderCaption"><?php echo $view1_acc->SEG_LAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->SEG_LAT) ?>',2);"><div id="elh_view1_acc_SEG_LAT" class="view1_acc_SEG_LAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->SEG_LAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->SEG_LAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->SEG_LAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->GRA_LONG->Visible) { // GRA_LONG ?>
	<?php if ($view1_acc->SortUrl($view1_acc->GRA_LONG) == "") { ?>
		<th data-name="GRA_LONG"><div id="elh_view1_acc_GRA_LONG" class="view1_acc_GRA_LONG"><div class="ewTableHeaderCaption"><?php echo $view1_acc->GRA_LONG->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LONG"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->GRA_LONG) ?>',2);"><div id="elh_view1_acc_GRA_LONG" class="view1_acc_GRA_LONG">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->GRA_LONG->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->GRA_LONG->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->GRA_LONG->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->MIN_LONG->Visible) { // MIN_LONG ?>
	<?php if ($view1_acc->SortUrl($view1_acc->MIN_LONG) == "") { ?>
		<th data-name="MIN_LONG"><div id="elh_view1_acc_MIN_LONG" class="view1_acc_MIN_LONG"><div class="ewTableHeaderCaption"><?php echo $view1_acc->MIN_LONG->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LONG"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->MIN_LONG) ?>',2);"><div id="elh_view1_acc_MIN_LONG" class="view1_acc_MIN_LONG">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->MIN_LONG->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->MIN_LONG->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->MIN_LONG->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->SEG_LONG->Visible) { // SEG_LONG ?>
	<?php if ($view1_acc->SortUrl($view1_acc->SEG_LONG) == "") { ?>
		<th data-name="SEG_LONG"><div id="elh_view1_acc_SEG_LONG" class="view1_acc_SEG_LONG"><div class="ewTableHeaderCaption"><?php echo $view1_acc->SEG_LONG->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LONG"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->SEG_LONG) ?>',2);"><div id="elh_view1_acc_SEG_LONG" class="view1_acc_SEG_LONG">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->SEG_LONG->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->SEG_LONG->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->SEG_LONG->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
		
<?php if ($view1_acc->Hora_ingreso->Visible) { // Hora_ingreso ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Hora_ingreso) == "") { ?>
		<th data-name="Hora_ingreso"><div id="elh_view1_acc_Hora_ingreso" class="view1_acc_Hora_ingreso"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Hora_ingreso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Hora_ingreso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Hora_ingreso) ?>',2);"><div id="elh_view1_acc_Hora_ingreso" class="view1_acc_Hora_ingreso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Hora_ingreso->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Hora_ingreso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Hora_ingreso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->FP_Armada->Visible) { // FP_Armada ?>
	<?php if ($view1_acc->SortUrl($view1_acc->FP_Armada) == "") { ?>
		<th data-name="FP_Armada"><div id="elh_view1_acc_FP_Armada" class="view1_acc_FP_Armada"><div class="ewTableHeaderCaption"><?php echo $view1_acc->FP_Armada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FP_Armada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->FP_Armada) ?>',2);"><div id="elh_view1_acc_FP_Armada" class="view1_acc_FP_Armada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->FP_Armada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->FP_Armada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->FP_Armada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->FP_Ejercito->Visible) { // FP_Ejercito ?>
	<?php if ($view1_acc->SortUrl($view1_acc->FP_Ejercito) == "") { ?>
		<th data-name="FP_Ejercito"><div id="elh_view1_acc_FP_Ejercito" class="view1_acc_FP_Ejercito"><div class="ewTableHeaderCaption"><?php echo $view1_acc->FP_Ejercito->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FP_Ejercito"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->FP_Ejercito) ?>',2);"><div id="elh_view1_acc_FP_Ejercito" class="view1_acc_FP_Ejercito">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->FP_Ejercito->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->FP_Ejercito->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->FP_Ejercito->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->FP_Policia->Visible) { // FP_Policia ?>
	<?php if ($view1_acc->SortUrl($view1_acc->FP_Policia) == "") { ?>
		<th data-name="FP_Policia"><div id="elh_view1_acc_FP_Policia" class="view1_acc_FP_Policia"><div class="ewTableHeaderCaption"><?php echo $view1_acc->FP_Policia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FP_Policia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->FP_Policia) ?>',2);"><div id="elh_view1_acc_FP_Policia" class="view1_acc_FP_Policia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->FP_Policia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->FP_Policia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->FP_Policia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_COMANDANTE->Visible) { // NOM_COMANDANTE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_COMANDANTE) == "") { ?>
		<th data-name="NOM_COMANDANTE"><div id="elh_view1_acc_NOM_COMANDANTE" class="view1_acc_NOM_COMANDANTE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_COMANDANTE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_COMANDANTE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_COMANDANTE) ?>',2);"><div id="elh_view1_acc_NOM_COMANDANTE" class="view1_acc_NOM_COMANDANTE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_COMANDANTE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_COMANDANTE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_COMANDANTE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->TESTI1->Visible) { // TESTI1 ?>
	<?php if ($view1_acc->SortUrl($view1_acc->TESTI1) == "") { ?>
		<th data-name="TESTI1"><div id="elh_view1_acc_TESTI1" class="view1_acc_TESTI1"><div class="ewTableHeaderCaption"><?php echo $view1_acc->TESTI1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TESTI1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->TESTI1) ?>',2);"><div id="elh_view1_acc_TESTI1" class="view1_acc_TESTI1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->TESTI1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->TESTI1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->TESTI1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->CC_TESTI1->Visible) { // CC_TESTI1 ?>
	<?php if ($view1_acc->SortUrl($view1_acc->CC_TESTI1) == "") { ?>
		<th data-name="CC_TESTI1"><div id="elh_view1_acc_CC_TESTI1" class="view1_acc_CC_TESTI1"><div class="ewTableHeaderCaption"><?php echo $view1_acc->CC_TESTI1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CC_TESTI1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->CC_TESTI1) ?>',2);"><div id="elh_view1_acc_CC_TESTI1" class="view1_acc_CC_TESTI1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->CC_TESTI1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->CC_TESTI1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->CC_TESTI1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->CARGO_TESTI1->Visible) { // CARGO_TESTI1 ?>
	<?php if ($view1_acc->SortUrl($view1_acc->CARGO_TESTI1) == "") { ?>
		<th data-name="CARGO_TESTI1"><div id="elh_view1_acc_CARGO_TESTI1" class="view1_acc_CARGO_TESTI1"><div class="ewTableHeaderCaption"><?php echo $view1_acc->CARGO_TESTI1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CARGO_TESTI1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->CARGO_TESTI1) ?>',2);"><div id="elh_view1_acc_CARGO_TESTI1" class="view1_acc_CARGO_TESTI1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->CARGO_TESTI1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->CARGO_TESTI1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->CARGO_TESTI1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->TESTI2->Visible) { // TESTI2 ?>
	<?php if ($view1_acc->SortUrl($view1_acc->TESTI2) == "") { ?>
		<th data-name="TESTI2"><div id="elh_view1_acc_TESTI2" class="view1_acc_TESTI2"><div class="ewTableHeaderCaption"><?php echo $view1_acc->TESTI2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TESTI2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->TESTI2) ?>',2);"><div id="elh_view1_acc_TESTI2" class="view1_acc_TESTI2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->TESTI2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->TESTI2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->TESTI2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->CC_TESTI2->Visible) { // CC_TESTI2 ?>
	<?php if ($view1_acc->SortUrl($view1_acc->CC_TESTI2) == "") { ?>
		<th data-name="CC_TESTI2"><div id="elh_view1_acc_CC_TESTI2" class="view1_acc_CC_TESTI2"><div class="ewTableHeaderCaption"><?php echo $view1_acc->CC_TESTI2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CC_TESTI2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->CC_TESTI2) ?>',2);"><div id="elh_view1_acc_CC_TESTI2" class="view1_acc_CC_TESTI2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->CC_TESTI2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->CC_TESTI2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->CC_TESTI2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->CARGO_TESTI2->Visible) { // CARGO_TESTI2 ?>
	<?php if ($view1_acc->SortUrl($view1_acc->CARGO_TESTI2) == "") { ?>
		<th data-name="CARGO_TESTI2"><div id="elh_view1_acc_CARGO_TESTI2" class="view1_acc_CARGO_TESTI2"><div class="ewTableHeaderCaption"><?php echo $view1_acc->CARGO_TESTI2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CARGO_TESTI2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->CARGO_TESTI2) ?>',2);"><div id="elh_view1_acc_CARGO_TESTI2" class="view1_acc_CARGO_TESTI2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->CARGO_TESTI2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->CARGO_TESTI2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->CARGO_TESTI2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Afectados->Visible) { // Afectados ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Afectados) == "") { ?>
		<th data-name="Afectados"><div id="elh_view1_acc_Afectados" class="view1_acc_Afectados"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Afectados->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Afectados"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Afectados) ?>',2);"><div id="elh_view1_acc_Afectados" class="view1_acc_Afectados">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Afectados->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Afectados->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Afectados->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NUM_Afectado->Visible) { // NUM_Afectado ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NUM_Afectado) == "") { ?>
		<th data-name="NUM_Afectado"><div id="elh_view1_acc_NUM_Afectado" class="view1_acc_NUM_Afectado"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NUM_Afectado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NUM_Afectado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NUM_Afectado) ?>',2);"><div id="elh_view1_acc_NUM_Afectado" class="view1_acc_NUM_Afectado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NUM_Afectado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NUM_Afectado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NUM_Afectado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Nom_Afectado->Visible) { // Nom_Afectado ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Nom_Afectado) == "") { ?>
		<th data-name="Nom_Afectado"><div id="elh_view1_acc_Nom_Afectado" class="view1_acc_Nom_Afectado"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Nom_Afectado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nom_Afectado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Nom_Afectado) ?>',2);"><div id="elh_view1_acc_Nom_Afectado" class="view1_acc_Nom_Afectado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Nom_Afectado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Nom_Afectado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Nom_Afectado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->CC_Afectado->Visible) { // CC_Afectado ?>
	<?php if ($view1_acc->SortUrl($view1_acc->CC_Afectado) == "") { ?>
		<th data-name="CC_Afectado"><div id="elh_view1_acc_CC_Afectado" class="view1_acc_CC_Afectado"><div class="ewTableHeaderCaption"><?php echo $view1_acc->CC_Afectado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CC_Afectado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->CC_Afectado) ?>',2);"><div id="elh_view1_acc_CC_Afectado" class="view1_acc_CC_Afectado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->CC_Afectado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->CC_Afectado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->CC_Afectado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Cargo_Afectado->Visible) { // Cargo_Afectado ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Cargo_Afectado) == "") { ?>
		<th data-name="Cargo_Afectado"><div id="elh_view1_acc_Cargo_Afectado" class="view1_acc_Cargo_Afectado"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Cargo_Afectado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_Afectado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Cargo_Afectado) ?>',2);"><div id="elh_view1_acc_Cargo_Afectado" class="view1_acc_Cargo_Afectado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Cargo_Afectado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Cargo_Afectado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Cargo_Afectado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Tipo_incidente->Visible) { // Tipo_incidente ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Tipo_incidente) == "") { ?>
		<th data-name="Tipo_incidente"><div id="elh_view1_acc_Tipo_incidente" class="view1_acc_Tipo_incidente"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Tipo_incidente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tipo_incidente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Tipo_incidente) ?>',2);"><div id="elh_view1_acc_Tipo_incidente" class="view1_acc_Tipo_incidente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Tipo_incidente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Tipo_incidente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Tipo_incidente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Riesgo->Visible) { // Riesgo ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Riesgo) == "") { ?>
		<th data-name="Riesgo"><div id="elh_view1_acc_Riesgo" class="view1_acc_Riesgo"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Riesgo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Riesgo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Riesgo) ?>',2);"><div id="elh_view1_acc_Riesgo" class="view1_acc_Riesgo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Riesgo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Riesgo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Riesgo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Parte_Cuerpo->Visible) { // Parte_Cuerpo ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Parte_Cuerpo) == "") { ?>
		<th data-name="Parte_Cuerpo"><div id="elh_view1_acc_Parte_Cuerpo" class="view1_acc_Parte_Cuerpo"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Parte_Cuerpo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Parte_Cuerpo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Parte_Cuerpo) ?>',2);"><div id="elh_view1_acc_Parte_Cuerpo" class="view1_acc_Parte_Cuerpo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Parte_Cuerpo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Parte_Cuerpo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Parte_Cuerpo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->ESTADO_AFEC->Visible) { // ESTADO_AFEC ?>
	<?php if ($view1_acc->SortUrl($view1_acc->ESTADO_AFEC) == "") { ?>
		<th data-name="ESTADO_AFEC"><div id="elh_view1_acc_ESTADO_AFEC" class="view1_acc_ESTADO_AFEC"><div class="ewTableHeaderCaption"><?php echo $view1_acc->ESTADO_AFEC->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ESTADO_AFEC"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->ESTADO_AFEC) ?>',2);"><div id="elh_view1_acc_ESTADO_AFEC" class="view1_acc_ESTADO_AFEC">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->ESTADO_AFEC->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->ESTADO_AFEC->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->ESTADO_AFEC->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->EVACUADO->Visible) { // EVACUADO ?>
	<?php if ($view1_acc->SortUrl($view1_acc->EVACUADO) == "") { ?>
		<th data-name="EVACUADO"><div id="elh_view1_acc_EVACUADO" class="view1_acc_EVACUADO"><div class="ewTableHeaderCaption"><?php echo $view1_acc->EVACUADO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="EVACUADO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->EVACUADO) ?>',2);"><div id="elh_view1_acc_EVACUADO" class="view1_acc_EVACUADO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->EVACUADO->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->EVACUADO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->EVACUADO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->DESC_ACC->Visible) { // DESC_ACC ?>
	<?php if ($view1_acc->SortUrl($view1_acc->DESC_ACC) == "") { ?>
		<th data-name="DESC_ACC"><div id="elh_view1_acc_DESC_ACC" class="view1_acc_DESC_ACC"><div class="ewTableHeaderCaption"><?php echo $view1_acc->DESC_ACC->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DESC_ACC"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->DESC_ACC) ?>',2);"><div id="elh_view1_acc_DESC_ACC" class="view1_acc_DESC_ACC">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->DESC_ACC->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->DESC_ACC->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->DESC_ACC->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Modificado->Visible) { // Modificado ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Modificado) == "") { ?>
		<th data-name="Modificado"><div id="elh_view1_acc_Modificado" class="view1_acc_Modificado"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Modificado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Modificado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Modificado) ?>',2);"><div id="elh_view1_acc_Modificado" class="view1_acc_Modificado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Modificado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Modificado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Modificado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->llave_2->Visible) { // llave_2 ?>
	<?php if ($view1_acc->SortUrl($view1_acc->llave_2) == "") { ?>
		<th data-name="llave_2"><div id="elh_view1_acc_llave_2" class="view1_acc_llave_2"><div class="ewTableHeaderCaption"><?php echo $view1_acc->llave_2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="llave_2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->llave_2) ?>',2);"><div id="elh_view1_acc_llave_2" class="view1_acc_llave_2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->llave_2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->llave_2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->llave_2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view1_acc_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view1_acc->ExportAll && $view1_acc->Export <> "") {
	$view1_acc_list->StopRec = $view1_acc_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view1_acc_list->TotalRecs > $view1_acc_list->StartRec + $view1_acc_list->DisplayRecs - 1)
		$view1_acc_list->StopRec = $view1_acc_list->StartRec + $view1_acc_list->DisplayRecs - 1;
	else
		$view1_acc_list->StopRec = $view1_acc_list->TotalRecs;
}
$view1_acc_list->RecCnt = $view1_acc_list->StartRec - 1;
if ($view1_acc_list->Recordset && !$view1_acc_list->Recordset->EOF) {
	$view1_acc_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $view1_acc_list->StartRec > 1)
		$view1_acc_list->Recordset->Move($view1_acc_list->StartRec - 1);
} elseif (!$view1_acc->AllowAddDeleteRow && $view1_acc_list->StopRec == 0) {
	$view1_acc_list->StopRec = $view1_acc->GridAddRowCount;
}

// Initialize aggregate
$view1_acc->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view1_acc->ResetAttrs();
$view1_acc_list->RenderRow();
while ($view1_acc_list->RecCnt < $view1_acc_list->StopRec) {
	$view1_acc_list->RecCnt++;
	if (intval($view1_acc_list->RecCnt) >= intval($view1_acc_list->StartRec)) {
		$view1_acc_list->RowCnt++;

		// Set up key count
		$view1_acc_list->KeyCount = $view1_acc_list->RowIndex;

		// Init row class and style
		$view1_acc->ResetAttrs();
		$view1_acc->CssClass = "";
		if ($view1_acc->CurrentAction == "gridadd") {
		} else {
			$view1_acc_list->LoadRowValues($view1_acc_list->Recordset); // Load row values
		}
		$view1_acc->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view1_acc->RowAttrs = array_merge($view1_acc->RowAttrs, array('data-rowindex'=>$view1_acc_list->RowCnt, 'id'=>'r' . $view1_acc_list->RowCnt . '_view1_acc', 'data-rowtype'=>$view1_acc->RowType));

		// Render row
		$view1_acc_list->RenderRow();

		// Render list options
		$view1_acc_list->RenderListOptions();
?>
	<tr<?php echo $view1_acc->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view1_acc_list->ListOptions->Render("body", "left", $view1_acc_list->RowCnt);
?>
	<?php if ($view1_acc->llave->Visible) { // llave ?>
		<td data-name="llave"<?php echo $view1_acc->llave->CellAttributes() ?>>
<span<?php echo $view1_acc->llave->ViewAttributes() ?>>
<?php echo $view1_acc->llave->ListViewValue() ?></span>
<a id="<?php echo $view1_acc_list->PageObjName . "_row_" . $view1_acc_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view1_acc->F_Sincron->Visible) { // F_Sincron ?>
		<td data-name="F_Sincron"<?php echo $view1_acc->F_Sincron->CellAttributes() ?>>
<span<?php echo $view1_acc->F_Sincron->ViewAttributes() ?>>
<?php echo $view1_acc->F_Sincron->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->FECHA_ACC->Visible) { // FECHA_ACC ?>
		<td data-name="FECHA_ACC"<?php echo $view1_acc->FECHA_ACC->CellAttributes() ?>>
<span<?php echo $view1_acc->FECHA_ACC->ViewAttributes() ?>>
<?php echo $view1_acc->FECHA_ACC->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->HORA_ACC->Visible) { // HORA_ACC ?>
		<td data-name="HORA_ACC"<?php echo $view1_acc->HORA_ACC->CellAttributes() ?>>
<span<?php echo $view1_acc->HORA_ACC->ViewAttributes() ?>>
<?php echo $view1_acc->HORA_ACC->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->USUARIO->Visible) { // USUARIO ?>
		<td data-name="USUARIO"<?php echo $view1_acc->USUARIO->CellAttributes() ?>>
<span<?php echo $view1_acc->USUARIO->ViewAttributes() ?>>
<?php echo $view1_acc->USUARIO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Cargo_gme->Visible) { // Cargo_gme ?>
		<td data-name="Cargo_gme"<?php echo $view1_acc->Cargo_gme->CellAttributes() ?>>
<span<?php echo $view1_acc->Cargo_gme->ViewAttributes() ?>>
<?php echo $view1_acc->Cargo_gme->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_PE->Visible) { // NOM_PE ?>
		<td data-name="NOM_PE"<?php echo $view1_acc->NOM_PE->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_PE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_PE->Visible) { // Otro_PE ?>
		<td data-name="Otro_PE"<?php echo $view1_acc->Otro_PE->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_PE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_APOYO->Visible) { // NOM_APOYO ?>
		<td data-name="NOM_APOYO"<?php echo $view1_acc->NOM_APOYO->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_APOYO->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_APOYO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
		<td data-name="Otro_Nom_Apoyo"<?php echo $view1_acc->Otro_Nom_Apoyo->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_Nom_Apoyo->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_Apoyo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
		<td data-name="Otro_CC_Apoyo"<?php echo $view1_acc->Otro_CC_Apoyo->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_CC_Apoyo->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_Apoyo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_ENLACE->Visible) { // NOM_ENLACE ?>
		<td data-name="NOM_ENLACE"<?php echo $view1_acc->NOM_ENLACE->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_ENLACE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_ENLACE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_Nom_Enlace->Visible) { // Otro_Nom_Enlace ?>
		<td data-name="Otro_Nom_Enlace"<?php echo $view1_acc->Otro_Nom_Enlace->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_Nom_Enlace->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_Enlace->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_CC_Enlace->Visible) { // Otro_CC_Enlace ?>
		<td data-name="Otro_CC_Enlace"<?php echo $view1_acc->Otro_CC_Enlace->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_CC_Enlace->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_Enlace->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_PGE->Visible) { // NOM_PGE ?>
		<td data-name="NOM_PGE"<?php echo $view1_acc->NOM_PGE->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_Nom_PGE->Visible) { // Otro_Nom_PGE ?>
		<td data-name="Otro_Nom_PGE"<?php echo $view1_acc->Otro_Nom_PGE->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_Nom_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<td data-name="Otro_CC_PGE"<?php echo $view1_acc->Otro_CC_PGE->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_CC_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Departamento->Visible) { // Departamento ?>
		<td data-name="Departamento"<?php echo $view1_acc->Departamento->CellAttributes() ?>>
<span<?php echo $view1_acc->Departamento->ViewAttributes() ?>>
<?php echo $view1_acc->Departamento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Muncipio->Visible) { // Muncipio ?>
		<td data-name="Muncipio"<?php echo $view1_acc->Muncipio->CellAttributes() ?>>
<span<?php echo $view1_acc->Muncipio->ViewAttributes() ?>>
<?php echo $view1_acc->Muncipio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_VDA->Visible) { // NOM_VDA ?>
		<td data-name="NOM_VDA"<?php echo $view1_acc->NOM_VDA->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_VDA->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_VDA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->LATITUD->Visible) { // LATITUD ?>
		<td data-name="LATITUD"<?php echo $view1_acc->LATITUD->CellAttributes() ?>>
<span<?php echo $view1_acc->LATITUD->ViewAttributes() ?>>
<?php echo $view1_acc->LATITUD->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->GRA_LAT->Visible) { // GRA_LAT ?>
		<td data-name="GRA_LAT"<?php echo $view1_acc->GRA_LAT->CellAttributes() ?>>
<span<?php echo $view1_acc->GRA_LAT->ViewAttributes() ?>>
<?php echo $view1_acc->GRA_LAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->MIN_LAT->Visible) { // MIN_LAT ?>
		<td data-name="MIN_LAT"<?php echo $view1_acc->MIN_LAT->CellAttributes() ?>>
<span<?php echo $view1_acc->MIN_LAT->ViewAttributes() ?>>
<?php echo $view1_acc->MIN_LAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->SEG_LAT->Visible) { // SEG_LAT ?>
		<td data-name="SEG_LAT"<?php echo $view1_acc->SEG_LAT->CellAttributes() ?>>
<span<?php echo $view1_acc->SEG_LAT->ViewAttributes() ?>>
<?php echo $view1_acc->SEG_LAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->GRA_LONG->Visible) { // GRA_LONG ?>
		<td data-name="GRA_LONG"<?php echo $view1_acc->GRA_LONG->CellAttributes() ?>>
<span<?php echo $view1_acc->GRA_LONG->ViewAttributes() ?>>
<?php echo $view1_acc->GRA_LONG->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->MIN_LONG->Visible) { // MIN_LONG ?>
		<td data-name="MIN_LONG"<?php echo $view1_acc->MIN_LONG->CellAttributes() ?>>
<span<?php echo $view1_acc->MIN_LONG->ViewAttributes() ?>>
<?php echo $view1_acc->MIN_LONG->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->SEG_LONG->Visible) { // SEG_LONG ?>
		<td data-name="SEG_LONG"<?php echo $view1_acc->SEG_LONG->CellAttributes() ?>>
<span<?php echo $view1_acc->SEG_LONG->ViewAttributes() ?>>
<?php echo $view1_acc->SEG_LONG->ListViewValue() ?></span>
</td>
	<?php } ?>
	
	<?php if ($view1_acc->Hora_ingreso->Visible) { // Hora_ingreso ?>
		<td data-name="Hora_ingreso"<?php echo $view1_acc->Hora_ingreso->CellAttributes() ?>>
<span<?php echo $view1_acc->Hora_ingreso->ViewAttributes() ?>>
<?php echo $view1_acc->Hora_ingreso->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->FP_Armada->Visible) { // FP_Armada ?>
		<td data-name="FP_Armada"<?php echo $view1_acc->FP_Armada->CellAttributes() ?>>
<span<?php echo $view1_acc->FP_Armada->ViewAttributes() ?>>
<?php echo $view1_acc->FP_Armada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->FP_Ejercito->Visible) { // FP_Ejercito ?>
		<td data-name="FP_Ejercito"<?php echo $view1_acc->FP_Ejercito->CellAttributes() ?>>
<span<?php echo $view1_acc->FP_Ejercito->ViewAttributes() ?>>
<?php echo $view1_acc->FP_Ejercito->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->FP_Policia->Visible) { // FP_Policia ?>
		<td data-name="FP_Policia"<?php echo $view1_acc->FP_Policia->CellAttributes() ?>>
<span<?php echo $view1_acc->FP_Policia->ViewAttributes() ?>>
<?php echo $view1_acc->FP_Policia->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_COMANDANTE->Visible) { // NOM_COMANDANTE ?>
		<td data-name="NOM_COMANDANTE"<?php echo $view1_acc->NOM_COMANDANTE->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_COMANDANTE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_COMANDANTE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->TESTI1->Visible) { // TESTI1 ?>
		<td data-name="TESTI1"<?php echo $view1_acc->TESTI1->CellAttributes() ?>>
<span<?php echo $view1_acc->TESTI1->ViewAttributes() ?>>
<?php echo $view1_acc->TESTI1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->CC_TESTI1->Visible) { // CC_TESTI1 ?>
		<td data-name="CC_TESTI1"<?php echo $view1_acc->CC_TESTI1->CellAttributes() ?>>
<span<?php echo $view1_acc->CC_TESTI1->ViewAttributes() ?>>
<?php echo $view1_acc->CC_TESTI1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->CARGO_TESTI1->Visible) { // CARGO_TESTI1 ?>
		<td data-name="CARGO_TESTI1"<?php echo $view1_acc->CARGO_TESTI1->CellAttributes() ?>>
<span<?php echo $view1_acc->CARGO_TESTI1->ViewAttributes() ?>>
<?php echo $view1_acc->CARGO_TESTI1->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->TESTI2->Visible) { // TESTI2 ?>
		<td data-name="TESTI2"<?php echo $view1_acc->TESTI2->CellAttributes() ?>>
<span<?php echo $view1_acc->TESTI2->ViewAttributes() ?>>
<?php echo $view1_acc->TESTI2->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->CC_TESTI2->Visible) { // CC_TESTI2 ?>
		<td data-name="CC_TESTI2"<?php echo $view1_acc->CC_TESTI2->CellAttributes() ?>>
<span<?php echo $view1_acc->CC_TESTI2->ViewAttributes() ?>>
<?php echo $view1_acc->CC_TESTI2->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->CARGO_TESTI2->Visible) { // CARGO_TESTI2 ?>
		<td data-name="CARGO_TESTI2"<?php echo $view1_acc->CARGO_TESTI2->CellAttributes() ?>>
<span<?php echo $view1_acc->CARGO_TESTI2->ViewAttributes() ?>>
<?php echo $view1_acc->CARGO_TESTI2->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Afectados->Visible) { // Afectados ?>
		<td data-name="Afectados"<?php echo $view1_acc->Afectados->CellAttributes() ?>>
<span<?php echo $view1_acc->Afectados->ViewAttributes() ?>>
<?php echo $view1_acc->Afectados->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NUM_Afectado->Visible) { // NUM_Afectado ?>
		<td data-name="NUM_Afectado"<?php echo $view1_acc->NUM_Afectado->CellAttributes() ?>>
<span<?php echo $view1_acc->NUM_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->NUM_Afectado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Nom_Afectado->Visible) { // Nom_Afectado ?>
		<td data-name="Nom_Afectado"<?php echo $view1_acc->Nom_Afectado->CellAttributes() ?>>
<span<?php echo $view1_acc->Nom_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->Nom_Afectado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->CC_Afectado->Visible) { // CC_Afectado ?>
		<td data-name="CC_Afectado"<?php echo $view1_acc->CC_Afectado->CellAttributes() ?>>
<span<?php echo $view1_acc->CC_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->CC_Afectado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Cargo_Afectado->Visible) { // Cargo_Afectado ?>
		<td data-name="Cargo_Afectado"<?php echo $view1_acc->Cargo_Afectado->CellAttributes() ?>>
<span<?php echo $view1_acc->Cargo_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->Cargo_Afectado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Tipo_incidente->Visible) { // Tipo_incidente ?>
		<td data-name="Tipo_incidente"<?php echo $view1_acc->Tipo_incidente->CellAttributes() ?>>
<span<?php echo $view1_acc->Tipo_incidente->ViewAttributes() ?>>
<?php echo $view1_acc->Tipo_incidente->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Riesgo->Visible) { // Riesgo ?>
		<td data-name="Riesgo"<?php echo $view1_acc->Riesgo->CellAttributes() ?>>
<span<?php echo $view1_acc->Riesgo->ViewAttributes() ?>>
<?php echo $view1_acc->Riesgo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Parte_Cuerpo->Visible) { // Parte_Cuerpo ?>
		<td data-name="Parte_Cuerpo"<?php echo $view1_acc->Parte_Cuerpo->CellAttributes() ?>>
<span<?php echo $view1_acc->Parte_Cuerpo->ViewAttributes() ?>>
<?php echo $view1_acc->Parte_Cuerpo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->ESTADO_AFEC->Visible) { // ESTADO_AFEC ?>
		<td data-name="ESTADO_AFEC"<?php echo $view1_acc->ESTADO_AFEC->CellAttributes() ?>>
<span<?php echo $view1_acc->ESTADO_AFEC->ViewAttributes() ?>>
<?php echo $view1_acc->ESTADO_AFEC->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->EVACUADO->Visible) { // EVACUADO ?>
		<td data-name="EVACUADO"<?php echo $view1_acc->EVACUADO->CellAttributes() ?>>
<span<?php echo $view1_acc->EVACUADO->ViewAttributes() ?>>
<?php echo $view1_acc->EVACUADO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->DESC_ACC->Visible) { // DESC_ACC ?>
		<td data-name="DESC_ACC"<?php echo $view1_acc->DESC_ACC->CellAttributes() ?>>
<span<?php echo $view1_acc->DESC_ACC->ViewAttributes() ?>>
<?php echo $view1_acc->DESC_ACC->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Modificado->Visible) { // Modificado ?>
		<td data-name="Modificado"<?php echo $view1_acc->Modificado->CellAttributes() ?>>
<span<?php echo $view1_acc->Modificado->ViewAttributes() ?>>
<?php echo $view1_acc->Modificado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->llave_2->Visible) { // llave_2 ?>
		<td data-name="llave_2"<?php echo $view1_acc->llave_2->CellAttributes() ?>>
<span<?php echo $view1_acc->llave_2->ViewAttributes() ?>>
<?php echo $view1_acc->llave_2->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view1_acc_list->ListOptions->Render("body", "right", $view1_acc_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view1_acc->CurrentAction <> "gridadd")
		$view1_acc_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view1_acc->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view1_acc_list->Recordset)
	$view1_acc_list->Recordset->Close();
?>
<?php if ($view1_acc->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view1_acc->CurrentAction <> "gridadd" && $view1_acc->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view1_acc_list->Pager)) $view1_acc_list->Pager = new cPrevNextPager($view1_acc_list->StartRec, $view1_acc_list->DisplayRecs, $view1_acc_list->TotalRecs) ?>
<?php if ($view1_acc_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view1_acc_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view1_acc_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view1_acc_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view1_acc_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view1_acc_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view1_acc_list->PageUrl() ?>start=<?php echo $view1_acc_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view1_acc_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_acc_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_acc_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_acc_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_acc_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view1_acc_list->TotalRecs == 0 && $view1_acc->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_acc_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view1_acc->Export == "") { ?>
<script type="text/javascript">
fview1_acclistsrch.Init();
fview1_acclist.Init();
</script>
<?php } ?>
<?php
$view1_acc_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view1_acc->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view1_acc_list->Page_Terminate();
?>
