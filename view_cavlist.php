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

$view_cav_list = NULL; // Initialize page object first

class cview_cav_list extends cview_cav {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_cav';

	// Page object name
	var $PageObjName = 'view_cav_list';

	// Grid form hidden field names
	var $FormName = 'fview_cavlist';
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

		// Table object (view_cav)
		if (!isset($GLOBALS["view_cav"]) || get_class($GLOBALS["view_cav"]) == "cview_cav") {
			$GLOBALS["view_cav"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_cav"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view_cavadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view_cavdelete.php";
		$this->MultiUpdateUrl = "view_cavupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_cav', TRUE);

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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->llave, $Default, FALSE); // llave
		$this->BuildSearchSql($sWhere, $this->USUARIO, $Default, FALSE); // USUARIO
		$this->BuildSearchSql($sWhere, $this->Num_AV, $Default, FALSE); // Num_AV
		$this->BuildSearchSql($sWhere, $this->NOM_APOYO, $Default, FALSE); // NOM_APOYO
		$this->BuildSearchSql($sWhere, $this->Otro_Nom_Apoyo, $Default, FALSE); // Otro_Nom_Apoyo
		$this->BuildSearchSql($sWhere, $this->NOM_PE, $Default, FALSE); // NOM_PE
		$this->BuildSearchSql($sWhere, $this->Otro_PE, $Default, FALSE); // Otro_PE
		$this->BuildSearchSql($sWhere, $this->Departamento, $Default, FALSE); // Departamento
		$this->BuildSearchSql($sWhere, $this->Muncipio, $Default, FALSE); // Muncipio
		$this->BuildSearchSql($sWhere, $this->NOM_VDA, $Default, FALSE); // NOM_VDA
		$this->BuildSearchSql($sWhere, $this->FECHA_INTO_AV, $Default, FALSE); // FECHA_INTO_AV
		$this->BuildSearchSql($sWhere, $this->AD1O, $Default, FALSE); // AÑO
		$this->BuildSearchSql($sWhere, $this->FASE, $Default, FALSE); // FASE

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->llave->AdvancedSearch->Save(); // llave
			$this->USUARIO->AdvancedSearch->Save(); // USUARIO
			$this->Num_AV->AdvancedSearch->Save(); // Num_AV
			$this->NOM_APOYO->AdvancedSearch->Save(); // NOM_APOYO
			$this->Otro_Nom_Apoyo->AdvancedSearch->Save(); // Otro_Nom_Apoyo
			$this->NOM_PE->AdvancedSearch->Save(); // NOM_PE
			$this->Otro_PE->AdvancedSearch->Save(); // Otro_PE
			$this->Departamento->AdvancedSearch->Save(); // Departamento
			$this->Muncipio->AdvancedSearch->Save(); // Muncipio
			$this->NOM_VDA->AdvancedSearch->Save(); // NOM_VDA
			$this->FECHA_INTO_AV->AdvancedSearch->Save(); // FECHA_INTO_AV
			$this->AD1O->AdvancedSearch->Save(); // AÑO
			$this->FASE->AdvancedSearch->Save(); // FASE
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
		$this->BuildBasicSearchSQL($sWhere, $this->Num_AV, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_APOYO, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_Nom_Apoyo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Departamento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Muncipio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_VDA, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FECHA_INTO_AV, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->AD1O, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FASE, $arKeywords, $type);
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
		if ($this->USUARIO->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Num_AV->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_APOYO->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_Nom_Apoyo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Departamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Muncipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_VDA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FECHA_INTO_AV->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->AD1O->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FASE->AdvancedSearch->IssetSession())
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
		$this->USUARIO->AdvancedSearch->UnsetSession();
		$this->Num_AV->AdvancedSearch->UnsetSession();
		$this->NOM_APOYO->AdvancedSearch->UnsetSession();
		$this->Otro_Nom_Apoyo->AdvancedSearch->UnsetSession();
		$this->NOM_PE->AdvancedSearch->UnsetSession();
		$this->Otro_PE->AdvancedSearch->UnsetSession();
		$this->Departamento->AdvancedSearch->UnsetSession();
		$this->Muncipio->AdvancedSearch->UnsetSession();
		$this->NOM_VDA->AdvancedSearch->UnsetSession();
		$this->FECHA_INTO_AV->AdvancedSearch->UnsetSession();
		$this->AD1O->AdvancedSearch->UnsetSession();
		$this->FASE->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->llave->AdvancedSearch->Load();
		$this->USUARIO->AdvancedSearch->Load();
		$this->Num_AV->AdvancedSearch->Load();
		$this->NOM_APOYO->AdvancedSearch->Load();
		$this->Otro_Nom_Apoyo->AdvancedSearch->Load();
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_PE->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->NOM_VDA->AdvancedSearch->Load();
		$this->FECHA_INTO_AV->AdvancedSearch->Load();
		$this->AD1O->AdvancedSearch->Load();
		$this->FASE->AdvancedSearch->Load();
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
			$this->UpdateSort($this->Num_AV, $bCtrl); // Num_AV
			$this->UpdateSort($this->NOM_APOYO, $bCtrl); // NOM_APOYO
			$this->UpdateSort($this->Otro_Nom_Apoyo, $bCtrl); // Otro_Nom_Apoyo
			$this->UpdateSort($this->Otro_CC_Apoyo, $bCtrl); // Otro_CC_Apoyo
			$this->UpdateSort($this->NOM_PE, $bCtrl); // NOM_PE
			$this->UpdateSort($this->Otro_PE, $bCtrl); // Otro_PE
			$this->UpdateSort($this->Departamento, $bCtrl); // Departamento
			$this->UpdateSort($this->Muncipio, $bCtrl); // Muncipio
			$this->UpdateSort($this->NOM_VDA, $bCtrl); // NOM_VDA
			$this->UpdateSort($this->NO_E, $bCtrl); // NO_E
			$this->UpdateSort($this->NO_OF, $bCtrl); // NO_OF
			$this->UpdateSort($this->NO_SUBOF, $bCtrl); // NO_SUBOF
			$this->UpdateSort($this->NO_SOL, $bCtrl); // NO_SOL
			$this->UpdateSort($this->NO_PATRU, $bCtrl); // NO_PATRU
			$this->UpdateSort($this->Nom_enfer, $bCtrl); // Nom_enfer
			$this->UpdateSort($this->Otro_Nom_Enfer, $bCtrl); // Otro_Nom_Enfer
			$this->UpdateSort($this->Otro_CC_Enfer, $bCtrl); // Otro_CC_Enfer
			$this->UpdateSort($this->Armada, $bCtrl); // Armada
			$this->UpdateSort($this->Ejercito, $bCtrl); // Ejercito
			$this->UpdateSort($this->Policia, $bCtrl); // Policia
			$this->UpdateSort($this->NOM_UNIDAD, $bCtrl); // NOM_UNIDAD
			$this->UpdateSort($this->NOM_COMAN, $bCtrl); // NOM_COMAN
			$this->UpdateSort($this->CC_COMAN, $bCtrl); // CC_COMAN
			$this->UpdateSort($this->TEL_COMAN, $bCtrl); // TEL_COMAN
			$this->UpdateSort($this->RANGO_COMAN, $bCtrl); // RANGO_COMAN
			$this->UpdateSort($this->Otro_rango, $bCtrl); // Otro_rango
			$this->UpdateSort($this->NO_GDETECCION, $bCtrl); // NO_GDETECCION
			$this->UpdateSort($this->NO_BINOMIO, $bCtrl); // NO_BINOMIO
			$this->UpdateSort($this->FECHA_INTO_AV, $bCtrl); // FECHA_INTO_AV
			$this->UpdateSort($this->DIA, $bCtrl); // DIA
			$this->UpdateSort($this->MES, $bCtrl); // MES
			$this->UpdateSort($this->LATITUD, $bCtrl); // LATITUD
			$this->UpdateSort($this->GRA_LAT, $bCtrl); // GRA_LAT
			$this->UpdateSort($this->MIN_LAT, $bCtrl); // MIN_LAT
			$this->UpdateSort($this->SEG_LAT, $bCtrl); // SEG_LAT
			$this->UpdateSort($this->GRA_LONG, $bCtrl); // GRA_LONG
			$this->UpdateSort($this->MIN_LONG, $bCtrl); // MIN_LONG
			$this->UpdateSort($this->SEG_LONG, $bCtrl); // SEG_LONG
			$this->UpdateSort($this->OBSERVACION, $bCtrl); // OBSERVACION
			$this->UpdateSort($this->AD1O, $bCtrl); // AÑO
			$this->UpdateSort($this->FASE, $bCtrl); // FASE
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
				$this->Num_AV->setSort("");
				$this->NOM_APOYO->setSort("");
				$this->Otro_Nom_Apoyo->setSort("");
				$this->Otro_CC_Apoyo->setSort("");
				$this->NOM_PE->setSort("");
				$this->Otro_PE->setSort("");
				$this->Departamento->setSort("");
				$this->Muncipio->setSort("");
				$this->NOM_VDA->setSort("");
				$this->NO_E->setSort("");
				$this->NO_OF->setSort("");
				$this->NO_SUBOF->setSort("");
				$this->NO_SOL->setSort("");
				$this->NO_PATRU->setSort("");
				$this->Nom_enfer->setSort("");
				$this->Otro_Nom_Enfer->setSort("");
				$this->Otro_CC_Enfer->setSort("");
				$this->Armada->setSort("");
				$this->Ejercito->setSort("");
				$this->Policia->setSort("");
				$this->NOM_UNIDAD->setSort("");
				$this->NOM_COMAN->setSort("");
				$this->CC_COMAN->setSort("");
				$this->TEL_COMAN->setSort("");
				$this->RANGO_COMAN->setSort("");
				$this->Otro_rango->setSort("");
				$this->NO_GDETECCION->setSort("");
				$this->NO_BINOMIO->setSort("");
				$this->FECHA_INTO_AV->setSort("");
				$this->DIA->setSort("");
				$this->MES->setSort("");
				$this->LATITUD->setSort("");
				$this->GRA_LAT->setSort("");
				$this->MIN_LAT->setSort("");
				$this->SEG_LAT->setSort("");
				$this->GRA_LONG->setSort("");
				$this->MIN_LONG->setSort("");
				$this->SEG_LONG->setSort("");
				$this->OBSERVACION->setSort("");
				$this->AD1O->setSort("");
				$this->FASE->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview_cavlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview_cavlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// USUARIO
		$this->USUARIO->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_USUARIO"]);
		if ($this->USUARIO->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->USUARIO->AdvancedSearch->SearchOperator = @$_GET["z_USUARIO"];

		// Num_AV
		$this->Num_AV->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Num_AV"]);
		if ($this->Num_AV->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Num_AV->AdvancedSearch->SearchOperator = @$_GET["z_Num_AV"];

		// NOM_APOYO
		$this->NOM_APOYO->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_APOYO"]);
		if ($this->NOM_APOYO->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_APOYO->AdvancedSearch->SearchOperator = @$_GET["z_NOM_APOYO"];

		// Otro_Nom_Apoyo
		$this->Otro_Nom_Apoyo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_Nom_Apoyo"]);
		if ($this->Otro_Nom_Apoyo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_Nom_Apoyo->AdvancedSearch->SearchOperator = @$_GET["z_Otro_Nom_Apoyo"];

		// NOM_PE
		$this->NOM_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_PE"]);
		if ($this->NOM_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_PE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_PE"];

		// Otro_PE
		$this->Otro_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_PE"]);
		if ($this->Otro_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_PE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_PE"];

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

		// FECHA_INTO_AV
		$this->FECHA_INTO_AV->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FECHA_INTO_AV"]);
		if ($this->FECHA_INTO_AV->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FECHA_INTO_AV->AdvancedSearch->SearchOperator = @$_GET["z_FECHA_INTO_AV"];

		// AÑO
		$this->AD1O->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_AD1O"]);
		if ($this->AD1O->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->AD1O->AdvancedSearch->SearchOperator = @$_GET["z_AD1O"];

		// FASE
		$this->FASE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FASE"]);
		if ($this->FASE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FASE->AdvancedSearch->SearchOperator = @$_GET["z_FASE"];
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// llave
			$this->llave->EditAttrs["class"] = "form-control";
			$this->llave->EditCustomAttributes = "";
			$this->llave->EditValue = ew_HtmlEncode($this->llave->AdvancedSearch->SearchValue);
			$this->llave->PlaceHolder = ew_RemoveHtml($this->llave->FldCaption());

			// F_Sincron
			$this->F_Sincron->EditAttrs["class"] = "form-control";
			$this->F_Sincron->EditCustomAttributes = "";
			$this->F_Sincron->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->F_Sincron->AdvancedSearch->SearchValue, 7), 7));
			$this->F_Sincron->PlaceHolder = ew_RemoveHtml($this->F_Sincron->FldCaption());

			// USUARIO
			$this->USUARIO->EditAttrs["class"] = "form-control";
			$this->USUARIO->EditCustomAttributes = "";
			$this->USUARIO->EditValue = ew_HtmlEncode($this->USUARIO->AdvancedSearch->SearchValue);
			$this->USUARIO->PlaceHolder = ew_RemoveHtml($this->USUARIO->FldCaption());

			// Cargo_gme
			$this->Cargo_gme->EditAttrs["class"] = "form-control";
			$this->Cargo_gme->EditCustomAttributes = "";
			$this->Cargo_gme->EditValue = ew_HtmlEncode($this->Cargo_gme->AdvancedSearch->SearchValue);
			$this->Cargo_gme->PlaceHolder = ew_RemoveHtml($this->Cargo_gme->FldCaption());

			// Num_AV
			$this->Num_AV->EditAttrs["class"] = "form-control";
			$this->Num_AV->EditCustomAttributes = "";
			$this->Num_AV->EditValue = ew_HtmlEncode($this->Num_AV->AdvancedSearch->SearchValue);
			$this->Num_AV->PlaceHolder = ew_RemoveHtml($this->Num_AV->FldCaption());

			// NOM_APOYO
			$this->NOM_APOYO->EditAttrs["class"] = "form-control";
			$this->NOM_APOYO->EditCustomAttributes = "";
			$this->NOM_APOYO->EditValue = ew_HtmlEncode($this->NOM_APOYO->AdvancedSearch->SearchValue);
			$this->NOM_APOYO->PlaceHolder = ew_RemoveHtml($this->NOM_APOYO->FldCaption());

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

			// NOM_PE
			$this->NOM_PE->EditAttrs["class"] = "form-control";
			$this->NOM_PE->EditCustomAttributes = "";
			$this->NOM_PE->EditValue = ew_HtmlEncode($this->NOM_PE->AdvancedSearch->SearchValue);
			$this->NOM_PE->PlaceHolder = ew_RemoveHtml($this->NOM_PE->FldCaption());

			// Otro_PE
			$this->Otro_PE->EditAttrs["class"] = "form-control";
			$this->Otro_PE->EditCustomAttributes = "";
			$this->Otro_PE->EditValue = ew_HtmlEncode($this->Otro_PE->AdvancedSearch->SearchValue);
			$this->Otro_PE->PlaceHolder = ew_RemoveHtml($this->Otro_PE->FldCaption());

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

			// NO_E
			$this->NO_E->EditAttrs["class"] = "form-control";
			$this->NO_E->EditCustomAttributes = "";
			$this->NO_E->EditValue = ew_HtmlEncode($this->NO_E->AdvancedSearch->SearchValue);
			$this->NO_E->PlaceHolder = ew_RemoveHtml($this->NO_E->FldCaption());

			// NO_OF
			$this->NO_OF->EditAttrs["class"] = "form-control";
			$this->NO_OF->EditCustomAttributes = "";
			$this->NO_OF->EditValue = ew_HtmlEncode($this->NO_OF->AdvancedSearch->SearchValue);
			$this->NO_OF->PlaceHolder = ew_RemoveHtml($this->NO_OF->FldCaption());

			// NO_SUBOF
			$this->NO_SUBOF->EditAttrs["class"] = "form-control";
			$this->NO_SUBOF->EditCustomAttributes = "";
			$this->NO_SUBOF->EditValue = ew_HtmlEncode($this->NO_SUBOF->AdvancedSearch->SearchValue);
			$this->NO_SUBOF->PlaceHolder = ew_RemoveHtml($this->NO_SUBOF->FldCaption());

			// NO_SOL
			$this->NO_SOL->EditAttrs["class"] = "form-control";
			$this->NO_SOL->EditCustomAttributes = "";
			$this->NO_SOL->EditValue = ew_HtmlEncode($this->NO_SOL->AdvancedSearch->SearchValue);
			$this->NO_SOL->PlaceHolder = ew_RemoveHtml($this->NO_SOL->FldCaption());

			// NO_PATRU
			$this->NO_PATRU->EditAttrs["class"] = "form-control";
			$this->NO_PATRU->EditCustomAttributes = "";
			$this->NO_PATRU->EditValue = ew_HtmlEncode($this->NO_PATRU->AdvancedSearch->SearchValue);
			$this->NO_PATRU->PlaceHolder = ew_RemoveHtml($this->NO_PATRU->FldCaption());

			// Nom_enfer
			$this->Nom_enfer->EditAttrs["class"] = "form-control";
			$this->Nom_enfer->EditCustomAttributes = "";
			$this->Nom_enfer->EditValue = ew_HtmlEncode($this->Nom_enfer->AdvancedSearch->SearchValue);
			$this->Nom_enfer->PlaceHolder = ew_RemoveHtml($this->Nom_enfer->FldCaption());

			// Otro_Nom_Enfer
			$this->Otro_Nom_Enfer->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_Enfer->EditCustomAttributes = "";
			$this->Otro_Nom_Enfer->EditValue = ew_HtmlEncode($this->Otro_Nom_Enfer->AdvancedSearch->SearchValue);
			$this->Otro_Nom_Enfer->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Enfer->FldCaption());

			// Otro_CC_Enfer
			$this->Otro_CC_Enfer->EditAttrs["class"] = "form-control";
			$this->Otro_CC_Enfer->EditCustomAttributes = "";
			$this->Otro_CC_Enfer->EditValue = ew_HtmlEncode($this->Otro_CC_Enfer->AdvancedSearch->SearchValue);
			$this->Otro_CC_Enfer->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Enfer->FldCaption());

			// Armada
			$this->Armada->EditAttrs["class"] = "form-control";
			$this->Armada->EditCustomAttributes = "";
			$this->Armada->EditValue = ew_HtmlEncode($this->Armada->AdvancedSearch->SearchValue);
			$this->Armada->PlaceHolder = ew_RemoveHtml($this->Armada->FldCaption());

			// Ejercito
			$this->Ejercito->EditAttrs["class"] = "form-control";
			$this->Ejercito->EditCustomAttributes = "";
			$this->Ejercito->EditValue = ew_HtmlEncode($this->Ejercito->AdvancedSearch->SearchValue);
			$this->Ejercito->PlaceHolder = ew_RemoveHtml($this->Ejercito->FldCaption());

			// Policia
			$this->Policia->EditAttrs["class"] = "form-control";
			$this->Policia->EditCustomAttributes = "";
			$this->Policia->EditValue = ew_HtmlEncode($this->Policia->AdvancedSearch->SearchValue);
			$this->Policia->PlaceHolder = ew_RemoveHtml($this->Policia->FldCaption());

			// NOM_UNIDAD
			$this->NOM_UNIDAD->EditAttrs["class"] = "form-control";
			$this->NOM_UNIDAD->EditCustomAttributes = "";
			$this->NOM_UNIDAD->EditValue = ew_HtmlEncode($this->NOM_UNIDAD->AdvancedSearch->SearchValue);
			$this->NOM_UNIDAD->PlaceHolder = ew_RemoveHtml($this->NOM_UNIDAD->FldCaption());

			// NOM_COMAN
			$this->NOM_COMAN->EditAttrs["class"] = "form-control";
			$this->NOM_COMAN->EditCustomAttributes = "";
			$this->NOM_COMAN->EditValue = ew_HtmlEncode($this->NOM_COMAN->AdvancedSearch->SearchValue);
			$this->NOM_COMAN->PlaceHolder = ew_RemoveHtml($this->NOM_COMAN->FldCaption());

			// CC_COMAN
			$this->CC_COMAN->EditAttrs["class"] = "form-control";
			$this->CC_COMAN->EditCustomAttributes = "";
			$this->CC_COMAN->EditValue = ew_HtmlEncode($this->CC_COMAN->AdvancedSearch->SearchValue);
			$this->CC_COMAN->PlaceHolder = ew_RemoveHtml($this->CC_COMAN->FldCaption());

			// TEL_COMAN
			$this->TEL_COMAN->EditAttrs["class"] = "form-control";
			$this->TEL_COMAN->EditCustomAttributes = "";
			$this->TEL_COMAN->EditValue = ew_HtmlEncode($this->TEL_COMAN->AdvancedSearch->SearchValue);
			$this->TEL_COMAN->PlaceHolder = ew_RemoveHtml($this->TEL_COMAN->FldCaption());

			// RANGO_COMAN
			$this->RANGO_COMAN->EditAttrs["class"] = "form-control";
			$this->RANGO_COMAN->EditCustomAttributes = "";
			$this->RANGO_COMAN->EditValue = ew_HtmlEncode($this->RANGO_COMAN->AdvancedSearch->SearchValue);
			$this->RANGO_COMAN->PlaceHolder = ew_RemoveHtml($this->RANGO_COMAN->FldCaption());

			// Otro_rango
			$this->Otro_rango->EditAttrs["class"] = "form-control";
			$this->Otro_rango->EditCustomAttributes = "";
			$this->Otro_rango->EditValue = ew_HtmlEncode($this->Otro_rango->AdvancedSearch->SearchValue);
			$this->Otro_rango->PlaceHolder = ew_RemoveHtml($this->Otro_rango->FldCaption());

			// NO_GDETECCION
			$this->NO_GDETECCION->EditAttrs["class"] = "form-control";
			$this->NO_GDETECCION->EditCustomAttributes = "";
			$this->NO_GDETECCION->EditValue = ew_HtmlEncode($this->NO_GDETECCION->AdvancedSearch->SearchValue);
			$this->NO_GDETECCION->PlaceHolder = ew_RemoveHtml($this->NO_GDETECCION->FldCaption());

			// NO_BINOMIO
			$this->NO_BINOMIO->EditAttrs["class"] = "form-control";
			$this->NO_BINOMIO->EditCustomAttributes = "";
			$this->NO_BINOMIO->EditValue = ew_HtmlEncode($this->NO_BINOMIO->AdvancedSearch->SearchValue);
			$this->NO_BINOMIO->PlaceHolder = ew_RemoveHtml($this->NO_BINOMIO->FldCaption());

			// FECHA_INTO_AV
			$this->FECHA_INTO_AV->EditAttrs["class"] = "form-control";
			$this->FECHA_INTO_AV->EditCustomAttributes = "";
			$this->FECHA_INTO_AV->EditValue = ew_HtmlEncode($this->FECHA_INTO_AV->AdvancedSearch->SearchValue);
			$this->FECHA_INTO_AV->PlaceHolder = ew_RemoveHtml($this->FECHA_INTO_AV->FldCaption());

			// DIA
			$this->DIA->EditAttrs["class"] = "form-control";
			$this->DIA->EditCustomAttributes = "";
			$this->DIA->EditValue = ew_HtmlEncode($this->DIA->AdvancedSearch->SearchValue);
			$this->DIA->PlaceHolder = ew_RemoveHtml($this->DIA->FldCaption());

			// MES
			$this->MES->EditAttrs["class"] = "form-control";
			$this->MES->EditCustomAttributes = "";
			$this->MES->EditValue = ew_HtmlEncode($this->MES->AdvancedSearch->SearchValue);
			$this->MES->PlaceHolder = ew_RemoveHtml($this->MES->FldCaption());

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

			// OBSERVACION
			$this->OBSERVACION->EditAttrs["class"] = "form-control";
			$this->OBSERVACION->EditCustomAttributes = "";
			$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->AdvancedSearch->SearchValue);
			$this->OBSERVACION->PlaceHolder = ew_RemoveHtml($this->OBSERVACION->FldCaption());

			// AÑO
			$this->AD1O->EditAttrs["class"] = "form-control";
			$this->AD1O->EditCustomAttributes = "";
			$this->AD1O->EditValue = ew_HtmlEncode($this->AD1O->AdvancedSearch->SearchValue);
			$this->AD1O->PlaceHolder = ew_RemoveHtml($this->AD1O->FldCaption());

			// FASE
			$this->FASE->EditAttrs["class"] = "form-control";
			$this->FASE->EditCustomAttributes = "";
			$this->FASE->EditValue = ew_HtmlEncode($this->FASE->AdvancedSearch->SearchValue);
			$this->FASE->PlaceHolder = ew_RemoveHtml($this->FASE->FldCaption());
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
		$this->USUARIO->AdvancedSearch->Load();
		$this->Num_AV->AdvancedSearch->Load();
		$this->NOM_APOYO->AdvancedSearch->Load();
		$this->Otro_Nom_Apoyo->AdvancedSearch->Load();
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_PE->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->NOM_VDA->AdvancedSearch->Load();
		$this->FECHA_INTO_AV->AdvancedSearch->Load();
		$this->AD1O->AdvancedSearch->Load();
		$this->FASE->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_view_cav\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_cav',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_cavlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view_cav_list)) $view_cav_list = new cview_cav_list();

// Page init
$view_cav_list->Page_Init();

// Page main
$view_cav_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_cav_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view_cav->Export == "") { ?>
<script type="text/javascript">

// Page object
var view_cav_list = new ew_Page("view_cav_list");
view_cav_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view_cav_list.PageID; // For backward compatibility

// Form object
var fview_cavlist = new ew_Form("fview_cavlist");
fview_cavlist.FormKeyCountName = '<?php echo $view_cav_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview_cavlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_cavlist.ValidateRequired = true;
<?php } else { ?>
fview_cavlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fview_cavlistsrch = new ew_Form("fview_cavlistsrch");

// Validate function for search
fview_cavlistsrch.Validate = function(fobj) {
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
fview_cavlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_cavlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fview_cavlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view_cav->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view_cav->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<H2> Control de áreas VIVAC</h2>

<p>La siguiente tabla contiene los informes de Áreas VIVAC realizados desde la fase II de erradicación 2015 a la fecha</p>

<hr>

<table>
	<tr>
		<td><?php if ($view_cav_list->TotalRecs > 0 && $view_cav_list->ExportOptions->Visible()) { ?>
			<?php $view_cav_list->ExportOptions->Render("body") ?>
			<?php } ?></td>
		<td>Si desea exportar la tabla en formato excel haga click en el siguiente icono </td>		
	</tr>	
</table>
<hr>

<?php if ($view_cav->Export == "") { ?>

<?php } ?>

</div>
<br>
<table>
	<tr>
		<td><?php if ($view_cav_list->SearchOptions->Visible()) { ?>

<?php $view_cav_list->SearchOptions->Render("body") ?></td>
		<td>Si desea realizar filtros en la tabla haga click en el siguiente icono e ingrese el dato en la columna correspondiente</td>
	</tr>
</table>
<br>
<hr>
<br>

<?php } ?>

<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($view_cav_list->TotalRecs <= 0)
			$view_cav_list->TotalRecs = $view_cav->SelectRecordCount();
	} else {
		if (!$view_cav_list->Recordset && ($view_cav_list->Recordset = $view_cav_list->LoadRecordset()))
			$view_cav_list->TotalRecs = $view_cav_list->Recordset->RecordCount();
	}
	$view_cav_list->StartRec = 1;
	if ($view_cav_list->DisplayRecs <= 0 || ($view_cav->Export <> "" && $view_cav->ExportAll)) // Display all records
		$view_cav_list->DisplayRecs = $view_cav_list->TotalRecs;
	if (!($view_cav->Export <> "" && $view_cav->ExportAll))
		$view_cav_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view_cav_list->Recordset = $view_cav_list->LoadRecordset($view_cav_list->StartRec-1, $view_cav_list->DisplayRecs);

	// Set no record found message
	if ($view_cav->CurrentAction == "" && $view_cav_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_cav_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_cav_list->SearchWhere == "0=101")
			$view_cav_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_cav_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view_cav_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view_cav->Export == "" && $view_cav->CurrentAction == "") { ?>
<form name="fview_cavlistsrch" id="fview_cavlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view_cav_list->SearchWhere <> "") ? " " : " "; ?>
<div id="fview_cavlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_cav">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$view_cav_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$view_cav->RowType = EW_ROWTYPE_SEARCH;

// Render row
$view_cav->ResetAttrs();
$view_cav_list->RenderRow();
?>

<table>
	<tr>
		<td><label for="x_USUARIO" class="ewSearchCaption ewLabel"><?php echo $view_cav->USUARIO->FldCaption() ?></label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_USUARIO" id="z_USUARIO" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
		<input type="text" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" size="35" placeholder="<?php echo ew_HtmlEncode($view_cav->USUARIO->PlaceHolder) ?>" value="<?php echo $view_cav->USUARIO->EditValue ?>"<?php echo $view_cav->USUARIO->EditAttributes() ?>>
		</span></td>
	</tr>
	<tr>
		<td><label for="x_NOM_APOYO" class="ewSearchCaption ewLabel">NOMBRE APOYO ZONAL</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_APOYO" id="z_NOM_APOYO" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
		<input type="text" data-field="x_NOM_APOYO" name="x_NOM_APOYO" id="x_NOM_APOYO" size="35" placeholder="<?php echo ew_HtmlEncode($view_cav->NOM_APOYO->PlaceHolder) ?>" value="<?php echo $view_cav->NOM_APOYO->EditValue ?>"<?php echo $view_cav->NOM_APOYO->EditAttributes() ?>>
		</span></td>
	</tr>
	<tr>
		<td><label for="x_NOM_PE" class="ewSearchCaption ewLabel">PUNTO DE ERRADICACIÓN</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_PE" id="z_NOM_PE" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
		<input type="text" data-field="x_NOM_PE" name="x_NOM_PE" id="x_NOM_PE" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($view_cav->NOM_PE->PlaceHolder) ?>" value="<?php echo $view_cav->NOM_PE->EditValue ?>"<?php echo $view_cav->NOM_PE->EditAttributes() ?>>
		</span></td>
	</tr>
	<tr>
		<td><label for="x_AD1O" class="ewSearchCaption ewLabel">AÑO</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_AD1O" id="z_AD1O" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
		<input type="text" title="Últimos dos dígitos del año" data-field="x_AD1O" name="x_AD1O" id="x_AD1O" size="30" maxlength="2" placeholder="AÑO" value="<?php echo $view_cav->AD1O->EditValue ?>"<?php echo $view_cav->AD1O->EditAttributes() ?>>
		</span></td>
	</tr>
	<tr>
		<td><label for="x_FASE" class="ewSearchCaption ewLabel"><?php echo $view_cav->FASE->FldCaption() ?></label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_FASE" id="z_FASE" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<input type="text" data-field="x_FASE" name="x_FASE" id="x_FASE" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($view_cav->FASE->PlaceHolder) ?>" value="<?php echo $view_cav->FASE->EditValue ?>"<?php echo $view_cav->FASE->EditAttributes() ?>>
			</span></td>
	</tr>
</table>

<br>

<?php if ($view_cav->USUARIO->Visible) { // USUARIO ?>
<?php } ?>
<?php if ($view_cav->NOM_APOYO->Visible) { // NOM_APOYO ?>
<?php } ?>
<?php if ($view_cav->NOM_PE->Visible) { // NOM_PE ?>
<?php } ?>
<?php if ($view_cav->AD1O->Visible) { // AÑO ?>
<?php } ?>
<?php if ($view_cav->FASE->Visible) { // FASE ?>
<?php } ?>

<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>

<br>
<br>
<hr>

	</div>
</div>

</form>

<?php } ?>
<?php } ?>
<?php $view_cav_list->ShowPageHeader(); ?>
<?php
$view_cav_list->ShowMessage();
?>
<?php if ($view_cav_list->TotalRecs > 0 || $view_cav->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($view_cav->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($view_cav->CurrentAction <> "gridadd" && $view_cav->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_cav_list->Pager)) $view_cav_list->Pager = new cPrevNextPager($view_cav_list->StartRec, $view_cav_list->DisplayRecs, $view_cav_list->TotalRecs) ?>
<?php if ($view_cav_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_cav_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_cav_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_cav_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_cav_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_cav_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_cav_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_cav_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_cav_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_cav_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_cav_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview_cavlist" id="fview_cavlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_cav_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_cav_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_cav">
<div id="gmp_view_cav" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view_cav_list->TotalRecs > 0) { ?>
<table id="tbl_view_cavlist" class="table ewTable">
<?php echo $view_cav->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_cav->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view_cav_list->RenderListOptions();

// Render list options (header, left)
$view_cav_list->ListOptions->Render("header", "left");
?>
<?php if ($view_cav->llave->Visible) { // llave ?>
	<?php if ($view_cav->SortUrl($view_cav->llave) == "") { ?>
		<th data-name="llave"><div id="elh_view_cav_llave" class="view_cav_llave"><div class="ewTableHeaderCaption"><?php echo $view_cav->llave->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="llave"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->llave) ?>',2);"><div id="elh_view_cav_llave" class="view_cav_llave">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->llave->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->llave->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->llave->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->F_Sincron->Visible) { // F_Sincron ?>
	<?php if ($view_cav->SortUrl($view_cav->F_Sincron) == "") { ?>
		<th data-name="F_Sincron"><div id="elh_view_cav_F_Sincron" class="view_cav_F_Sincron"><div class="ewTableHeaderCaption"><?php echo $view_cav->F_Sincron->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F_Sincron"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->F_Sincron) ?>',2);"><div id="elh_view_cav_F_Sincron" class="view_cav_F_Sincron">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->F_Sincron->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->F_Sincron->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->F_Sincron->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->USUARIO->Visible) { // USUARIO ?>
	<?php if ($view_cav->SortUrl($view_cav->USUARIO) == "") { ?>
		<th data-name="USUARIO"><div id="elh_view_cav_USUARIO" class="view_cav_USUARIO"><div class="ewTableHeaderCaption"><?php echo $view_cav->USUARIO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="USUARIO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->USUARIO) ?>',2);"><div id="elh_view_cav_USUARIO" class="view_cav_USUARIO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->USUARIO->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->USUARIO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->USUARIO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Cargo_gme->Visible) { // Cargo_gme ?>
	<?php if ($view_cav->SortUrl($view_cav->Cargo_gme) == "") { ?>
		<th data-name="Cargo_gme"><div id="elh_view_cav_Cargo_gme" class="view_cav_Cargo_gme"><div class="ewTableHeaderCaption"><?php echo $view_cav->Cargo_gme->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_gme"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Cargo_gme) ?>',2);"><div id="elh_view_cav_Cargo_gme" class="view_cav_Cargo_gme">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Cargo_gme->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Cargo_gme->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Cargo_gme->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Num_AV->Visible) { // Num_AV ?>
	<?php if ($view_cav->SortUrl($view_cav->Num_AV) == "") { ?>
		<th data-name="Num_AV"><div id="elh_view_cav_Num_AV" class="view_cav_Num_AV"><div class="ewTableHeaderCaption"><?php echo $view_cav->Num_AV->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Num_AV"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Num_AV) ?>',2);"><div id="elh_view_cav_Num_AV" class="view_cav_Num_AV">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Num_AV->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Num_AV->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Num_AV->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NOM_APOYO->Visible) { // NOM_APOYO ?>
	<?php if ($view_cav->SortUrl($view_cav->NOM_APOYO) == "") { ?>
		<th data-name="NOM_APOYO"><div id="elh_view_cav_NOM_APOYO" class="view_cav_NOM_APOYO"><div class="ewTableHeaderCaption"><?php echo $view_cav->NOM_APOYO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_APOYO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NOM_APOYO) ?>',2);"><div id="elh_view_cav_NOM_APOYO" class="view_cav_NOM_APOYO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NOM_APOYO->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NOM_APOYO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NOM_APOYO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
	<?php if ($view_cav->SortUrl($view_cav->Otro_Nom_Apoyo) == "") { ?>
		<th data-name="Otro_Nom_Apoyo"><div id="elh_view_cav_Otro_Nom_Apoyo" class="view_cav_Otro_Nom_Apoyo"><div class="ewTableHeaderCaption"><?php echo $view_cav->Otro_Nom_Apoyo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_Apoyo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Otro_Nom_Apoyo) ?>',2);"><div id="elh_view_cav_Otro_Nom_Apoyo" class="view_cav_Otro_Nom_Apoyo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Otro_Nom_Apoyo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Otro_Nom_Apoyo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Otro_Nom_Apoyo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
	<?php if ($view_cav->SortUrl($view_cav->Otro_CC_Apoyo) == "") { ?>
		<th data-name="Otro_CC_Apoyo"><div id="elh_view_cav_Otro_CC_Apoyo" class="view_cav_Otro_CC_Apoyo"><div class="ewTableHeaderCaption"><?php echo $view_cav->Otro_CC_Apoyo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_Apoyo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Otro_CC_Apoyo) ?>',2);"><div id="elh_view_cav_Otro_CC_Apoyo" class="view_cav_Otro_CC_Apoyo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Otro_CC_Apoyo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Otro_CC_Apoyo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Otro_CC_Apoyo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NOM_PE->Visible) { // NOM_PE ?>
	<?php if ($view_cav->SortUrl($view_cav->NOM_PE) == "") { ?>
		<th data-name="NOM_PE"><div id="elh_view_cav_NOM_PE" class="view_cav_NOM_PE"><div class="ewTableHeaderCaption"><?php echo $view_cav->NOM_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NOM_PE) ?>',2);"><div id="elh_view_cav_NOM_PE" class="view_cav_NOM_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NOM_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NOM_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NOM_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Otro_PE->Visible) { // Otro_PE ?>
	<?php if ($view_cav->SortUrl($view_cav->Otro_PE) == "") { ?>
		<th data-name="Otro_PE"><div id="elh_view_cav_Otro_PE" class="view_cav_Otro_PE"><div class="ewTableHeaderCaption"><?php echo $view_cav->Otro_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Otro_PE) ?>',2);"><div id="elh_view_cav_Otro_PE" class="view_cav_Otro_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Otro_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Otro_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Otro_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Departamento->Visible) { // Departamento ?>
	<?php if ($view_cav->SortUrl($view_cav->Departamento) == "") { ?>
		<th data-name="Departamento"><div id="elh_view_cav_Departamento" class="view_cav_Departamento"><div class="ewTableHeaderCaption"><?php echo $view_cav->Departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Departamento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Departamento) ?>',2);"><div id="elh_view_cav_Departamento" class="view_cav_Departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Departamento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Muncipio->Visible) { // Muncipio ?>
	<?php if ($view_cav->SortUrl($view_cav->Muncipio) == "") { ?>
		<th data-name="Muncipio"><div id="elh_view_cav_Muncipio" class="view_cav_Muncipio"><div class="ewTableHeaderCaption"><?php echo $view_cav->Muncipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Muncipio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Muncipio) ?>',2);"><div id="elh_view_cav_Muncipio" class="view_cav_Muncipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Muncipio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Muncipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Muncipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NOM_VDA->Visible) { // NOM_VDA ?>
	<?php if ($view_cav->SortUrl($view_cav->NOM_VDA) == "") { ?>
		<th data-name="NOM_VDA"><div id="elh_view_cav_NOM_VDA" class="view_cav_NOM_VDA"><div class="ewTableHeaderCaption"><?php echo $view_cav->NOM_VDA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_VDA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NOM_VDA) ?>',2);"><div id="elh_view_cav_NOM_VDA" class="view_cav_NOM_VDA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NOM_VDA->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NOM_VDA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NOM_VDA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NO_E->Visible) { // NO_E ?>
	<?php if ($view_cav->SortUrl($view_cav->NO_E) == "") { ?>
		<th data-name="NO_E"><div id="elh_view_cav_NO_E" class="view_cav_NO_E"><div class="ewTableHeaderCaption"><?php echo $view_cav->NO_E->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NO_E"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NO_E) ?>',2);"><div id="elh_view_cav_NO_E" class="view_cav_NO_E">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NO_E->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NO_E->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NO_E->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NO_OF->Visible) { // NO_OF ?>
	<?php if ($view_cav->SortUrl($view_cav->NO_OF) == "") { ?>
		<th data-name="NO_OF"><div id="elh_view_cav_NO_OF" class="view_cav_NO_OF"><div class="ewTableHeaderCaption"><?php echo $view_cav->NO_OF->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NO_OF"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NO_OF) ?>',2);"><div id="elh_view_cav_NO_OF" class="view_cav_NO_OF">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NO_OF->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NO_OF->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NO_OF->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NO_SUBOF->Visible) { // NO_SUBOF ?>
	<?php if ($view_cav->SortUrl($view_cav->NO_SUBOF) == "") { ?>
		<th data-name="NO_SUBOF"><div id="elh_view_cav_NO_SUBOF" class="view_cav_NO_SUBOF"><div class="ewTableHeaderCaption"><?php echo $view_cav->NO_SUBOF->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NO_SUBOF"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NO_SUBOF) ?>',2);"><div id="elh_view_cav_NO_SUBOF" class="view_cav_NO_SUBOF">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NO_SUBOF->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NO_SUBOF->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NO_SUBOF->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NO_SOL->Visible) { // NO_SOL ?>
	<?php if ($view_cav->SortUrl($view_cav->NO_SOL) == "") { ?>
		<th data-name="NO_SOL"><div id="elh_view_cav_NO_SOL" class="view_cav_NO_SOL"><div class="ewTableHeaderCaption"><?php echo $view_cav->NO_SOL->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NO_SOL"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NO_SOL) ?>',2);"><div id="elh_view_cav_NO_SOL" class="view_cav_NO_SOL">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NO_SOL->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NO_SOL->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NO_SOL->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NO_PATRU->Visible) { // NO_PATRU ?>
	<?php if ($view_cav->SortUrl($view_cav->NO_PATRU) == "") { ?>
		<th data-name="NO_PATRU"><div id="elh_view_cav_NO_PATRU" class="view_cav_NO_PATRU"><div class="ewTableHeaderCaption"><?php echo $view_cav->NO_PATRU->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NO_PATRU"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NO_PATRU) ?>',2);"><div id="elh_view_cav_NO_PATRU" class="view_cav_NO_PATRU">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NO_PATRU->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NO_PATRU->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NO_PATRU->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Nom_enfer->Visible) { // Nom_enfer ?>
	<?php if ($view_cav->SortUrl($view_cav->Nom_enfer) == "") { ?>
		<th data-name="Nom_enfer"><div id="elh_view_cav_Nom_enfer" class="view_cav_Nom_enfer"><div class="ewTableHeaderCaption"><?php echo $view_cav->Nom_enfer->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nom_enfer"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Nom_enfer) ?>',2);"><div id="elh_view_cav_Nom_enfer" class="view_cav_Nom_enfer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Nom_enfer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Nom_enfer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Nom_enfer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Otro_Nom_Enfer->Visible) { // Otro_Nom_Enfer ?>
	<?php if ($view_cav->SortUrl($view_cav->Otro_Nom_Enfer) == "") { ?>
		<th data-name="Otro_Nom_Enfer"><div id="elh_view_cav_Otro_Nom_Enfer" class="view_cav_Otro_Nom_Enfer"><div class="ewTableHeaderCaption"><?php echo $view_cav->Otro_Nom_Enfer->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_Enfer"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Otro_Nom_Enfer) ?>',2);"><div id="elh_view_cav_Otro_Nom_Enfer" class="view_cav_Otro_Nom_Enfer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Otro_Nom_Enfer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Otro_Nom_Enfer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Otro_Nom_Enfer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Otro_CC_Enfer->Visible) { // Otro_CC_Enfer ?>
	<?php if ($view_cav->SortUrl($view_cav->Otro_CC_Enfer) == "") { ?>
		<th data-name="Otro_CC_Enfer"><div id="elh_view_cav_Otro_CC_Enfer" class="view_cav_Otro_CC_Enfer"><div class="ewTableHeaderCaption"><?php echo $view_cav->Otro_CC_Enfer->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_Enfer"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Otro_CC_Enfer) ?>',2);"><div id="elh_view_cav_Otro_CC_Enfer" class="view_cav_Otro_CC_Enfer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Otro_CC_Enfer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Otro_CC_Enfer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Otro_CC_Enfer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Armada->Visible) { // Armada ?>
	<?php if ($view_cav->SortUrl($view_cav->Armada) == "") { ?>
		<th data-name="Armada"><div id="elh_view_cav_Armada" class="view_cav_Armada"><div class="ewTableHeaderCaption"><?php echo $view_cav->Armada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Armada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Armada) ?>',2);"><div id="elh_view_cav_Armada" class="view_cav_Armada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Armada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Armada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Armada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Ejercito->Visible) { // Ejercito ?>
	<?php if ($view_cav->SortUrl($view_cav->Ejercito) == "") { ?>
		<th data-name="Ejercito"><div id="elh_view_cav_Ejercito" class="view_cav_Ejercito"><div class="ewTableHeaderCaption"><?php echo $view_cav->Ejercito->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ejercito"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Ejercito) ?>',2);"><div id="elh_view_cav_Ejercito" class="view_cav_Ejercito">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Ejercito->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Ejercito->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Ejercito->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Policia->Visible) { // Policia ?>
	<?php if ($view_cav->SortUrl($view_cav->Policia) == "") { ?>
		<th data-name="Policia"><div id="elh_view_cav_Policia" class="view_cav_Policia"><div class="ewTableHeaderCaption"><?php echo $view_cav->Policia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Policia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Policia) ?>',2);"><div id="elh_view_cav_Policia" class="view_cav_Policia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Policia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Policia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Policia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NOM_UNIDAD->Visible) { // NOM_UNIDAD ?>
	<?php if ($view_cav->SortUrl($view_cav->NOM_UNIDAD) == "") { ?>
		<th data-name="NOM_UNIDAD"><div id="elh_view_cav_NOM_UNIDAD" class="view_cav_NOM_UNIDAD"><div class="ewTableHeaderCaption"><?php echo $view_cav->NOM_UNIDAD->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_UNIDAD"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NOM_UNIDAD) ?>',2);"><div id="elh_view_cav_NOM_UNIDAD" class="view_cav_NOM_UNIDAD">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NOM_UNIDAD->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NOM_UNIDAD->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NOM_UNIDAD->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NOM_COMAN->Visible) { // NOM_COMAN ?>
	<?php if ($view_cav->SortUrl($view_cav->NOM_COMAN) == "") { ?>
		<th data-name="NOM_COMAN"><div id="elh_view_cav_NOM_COMAN" class="view_cav_NOM_COMAN"><div class="ewTableHeaderCaption"><?php echo $view_cav->NOM_COMAN->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_COMAN"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NOM_COMAN) ?>',2);"><div id="elh_view_cav_NOM_COMAN" class="view_cav_NOM_COMAN">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NOM_COMAN->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NOM_COMAN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NOM_COMAN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->CC_COMAN->Visible) { // CC_COMAN ?>
	<?php if ($view_cav->SortUrl($view_cav->CC_COMAN) == "") { ?>
		<th data-name="CC_COMAN"><div id="elh_view_cav_CC_COMAN" class="view_cav_CC_COMAN"><div class="ewTableHeaderCaption"><?php echo $view_cav->CC_COMAN->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CC_COMAN"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->CC_COMAN) ?>',2);"><div id="elh_view_cav_CC_COMAN" class="view_cav_CC_COMAN">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->CC_COMAN->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->CC_COMAN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->CC_COMAN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->TEL_COMAN->Visible) { // TEL_COMAN ?>
	<?php if ($view_cav->SortUrl($view_cav->TEL_COMAN) == "") { ?>
		<th data-name="TEL_COMAN"><div id="elh_view_cav_TEL_COMAN" class="view_cav_TEL_COMAN"><div class="ewTableHeaderCaption"><?php echo $view_cav->TEL_COMAN->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TEL_COMAN"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->TEL_COMAN) ?>',2);"><div id="elh_view_cav_TEL_COMAN" class="view_cav_TEL_COMAN">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->TEL_COMAN->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->TEL_COMAN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->TEL_COMAN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->RANGO_COMAN->Visible) { // RANGO_COMAN ?>
	<?php if ($view_cav->SortUrl($view_cav->RANGO_COMAN) == "") { ?>
		<th data-name="RANGO_COMAN"><div id="elh_view_cav_RANGO_COMAN" class="view_cav_RANGO_COMAN"><div class="ewTableHeaderCaption"><?php echo $view_cav->RANGO_COMAN->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="RANGO_COMAN"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->RANGO_COMAN) ?>',2);"><div id="elh_view_cav_RANGO_COMAN" class="view_cav_RANGO_COMAN">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->RANGO_COMAN->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->RANGO_COMAN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->RANGO_COMAN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->Otro_rango->Visible) { // Otro_rango ?>
	<?php if ($view_cav->SortUrl($view_cav->Otro_rango) == "") { ?>
		<th data-name="Otro_rango"><div id="elh_view_cav_Otro_rango" class="view_cav_Otro_rango"><div class="ewTableHeaderCaption"><?php echo $view_cav->Otro_rango->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_rango"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->Otro_rango) ?>',2);"><div id="elh_view_cav_Otro_rango" class="view_cav_Otro_rango">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->Otro_rango->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->Otro_rango->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->Otro_rango->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NO_GDETECCION->Visible) { // NO_GDETECCION ?>
	<?php if ($view_cav->SortUrl($view_cav->NO_GDETECCION) == "") { ?>
		<th data-name="NO_GDETECCION"><div id="elh_view_cav_NO_GDETECCION" class="view_cav_NO_GDETECCION"><div class="ewTableHeaderCaption"><?php echo $view_cav->NO_GDETECCION->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NO_GDETECCION"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NO_GDETECCION) ?>',2);"><div id="elh_view_cav_NO_GDETECCION" class="view_cav_NO_GDETECCION">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NO_GDETECCION->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NO_GDETECCION->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NO_GDETECCION->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->NO_BINOMIO->Visible) { // NO_BINOMIO ?>
	<?php if ($view_cav->SortUrl($view_cav->NO_BINOMIO) == "") { ?>
		<th data-name="NO_BINOMIO"><div id="elh_view_cav_NO_BINOMIO" class="view_cav_NO_BINOMIO"><div class="ewTableHeaderCaption"><?php echo $view_cav->NO_BINOMIO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NO_BINOMIO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->NO_BINOMIO) ?>',2);"><div id="elh_view_cav_NO_BINOMIO" class="view_cav_NO_BINOMIO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->NO_BINOMIO->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->NO_BINOMIO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->NO_BINOMIO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->FECHA_INTO_AV->Visible) { // FECHA_INTO_AV ?>
	<?php if ($view_cav->SortUrl($view_cav->FECHA_INTO_AV) == "") { ?>
		<th data-name="FECHA_INTO_AV"><div id="elh_view_cav_FECHA_INTO_AV" class="view_cav_FECHA_INTO_AV"><div class="ewTableHeaderCaption"><?php echo $view_cav->FECHA_INTO_AV->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FECHA_INTO_AV"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->FECHA_INTO_AV) ?>',2);"><div id="elh_view_cav_FECHA_INTO_AV" class="view_cav_FECHA_INTO_AV">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->FECHA_INTO_AV->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->FECHA_INTO_AV->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->FECHA_INTO_AV->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->DIA->Visible) { // DIA ?>
	<?php if ($view_cav->SortUrl($view_cav->DIA) == "") { ?>
		<th data-name="DIA"><div id="elh_view_cav_DIA" class="view_cav_DIA"><div class="ewTableHeaderCaption"><?php echo $view_cav->DIA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DIA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->DIA) ?>',2);"><div id="elh_view_cav_DIA" class="view_cav_DIA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->DIA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->DIA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->DIA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->MES->Visible) { // MES ?>
	<?php if ($view_cav->SortUrl($view_cav->MES) == "") { ?>
		<th data-name="MES"><div id="elh_view_cav_MES" class="view_cav_MES"><div class="ewTableHeaderCaption"><?php echo $view_cav->MES->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MES"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->MES) ?>',2);"><div id="elh_view_cav_MES" class="view_cav_MES">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->MES->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->MES->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->MES->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->LATITUD->Visible) { // LATITUD ?>
	<?php if ($view_cav->SortUrl($view_cav->LATITUD) == "") { ?>
		<th data-name="LATITUD"><div id="elh_view_cav_LATITUD" class="view_cav_LATITUD"><div class="ewTableHeaderCaption"><?php echo $view_cav->LATITUD->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="LATITUD"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->LATITUD) ?>',2);"><div id="elh_view_cav_LATITUD" class="view_cav_LATITUD">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->LATITUD->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->LATITUD->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->LATITUD->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->GRA_LAT->Visible) { // GRA_LAT ?>
	<?php if ($view_cav->SortUrl($view_cav->GRA_LAT) == "") { ?>
		<th data-name="GRA_LAT"><div id="elh_view_cav_GRA_LAT" class="view_cav_GRA_LAT"><div class="ewTableHeaderCaption"><?php echo $view_cav->GRA_LAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->GRA_LAT) ?>',2);"><div id="elh_view_cav_GRA_LAT" class="view_cav_GRA_LAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->GRA_LAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->GRA_LAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->GRA_LAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->MIN_LAT->Visible) { // MIN_LAT ?>
	<?php if ($view_cav->SortUrl($view_cav->MIN_LAT) == "") { ?>
		<th data-name="MIN_LAT"><div id="elh_view_cav_MIN_LAT" class="view_cav_MIN_LAT"><div class="ewTableHeaderCaption"><?php echo $view_cav->MIN_LAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->MIN_LAT) ?>',2);"><div id="elh_view_cav_MIN_LAT" class="view_cav_MIN_LAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->MIN_LAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->MIN_LAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->MIN_LAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->SEG_LAT->Visible) { // SEG_LAT ?>
	<?php if ($view_cav->SortUrl($view_cav->SEG_LAT) == "") { ?>
		<th data-name="SEG_LAT"><div id="elh_view_cav_SEG_LAT" class="view_cav_SEG_LAT"><div class="ewTableHeaderCaption"><?php echo $view_cav->SEG_LAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->SEG_LAT) ?>',2);"><div id="elh_view_cav_SEG_LAT" class="view_cav_SEG_LAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->SEG_LAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->SEG_LAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->SEG_LAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->GRA_LONG->Visible) { // GRA_LONG ?>
	<?php if ($view_cav->SortUrl($view_cav->GRA_LONG) == "") { ?>
		<th data-name="GRA_LONG"><div id="elh_view_cav_GRA_LONG" class="view_cav_GRA_LONG"><div class="ewTableHeaderCaption"><?php echo $view_cav->GRA_LONG->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LONG"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->GRA_LONG) ?>',2);"><div id="elh_view_cav_GRA_LONG" class="view_cav_GRA_LONG">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->GRA_LONG->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->GRA_LONG->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->GRA_LONG->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->MIN_LONG->Visible) { // MIN_LONG ?>
	<?php if ($view_cav->SortUrl($view_cav->MIN_LONG) == "") { ?>
		<th data-name="MIN_LONG"><div id="elh_view_cav_MIN_LONG" class="view_cav_MIN_LONG"><div class="ewTableHeaderCaption"><?php echo $view_cav->MIN_LONG->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LONG"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->MIN_LONG) ?>',2);"><div id="elh_view_cav_MIN_LONG" class="view_cav_MIN_LONG">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->MIN_LONG->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->MIN_LONG->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->MIN_LONG->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->SEG_LONG->Visible) { // SEG_LONG ?>
	<?php if ($view_cav->SortUrl($view_cav->SEG_LONG) == "") { ?>
		<th data-name="SEG_LONG"><div id="elh_view_cav_SEG_LONG" class="view_cav_SEG_LONG"><div class="ewTableHeaderCaption"><?php echo $view_cav->SEG_LONG->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LONG"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->SEG_LONG) ?>',2);"><div id="elh_view_cav_SEG_LONG" class="view_cav_SEG_LONG">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->SEG_LONG->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->SEG_LONG->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->SEG_LONG->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->OBSERVACION->Visible) { // OBSERVACION ?>
	<?php if ($view_cav->SortUrl($view_cav->OBSERVACION) == "") { ?>
		<th data-name="OBSERVACION"><div id="elh_view_cav_OBSERVACION" class="view_cav_OBSERVACION"><div class="ewTableHeaderCaption"><?php echo $view_cav->OBSERVACION->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBSERVACION"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->OBSERVACION) ?>',2);"><div id="elh_view_cav_OBSERVACION" class="view_cav_OBSERVACION">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->OBSERVACION->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->OBSERVACION->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->OBSERVACION->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->AD1O->Visible) { // AÑO ?>
	<?php if ($view_cav->SortUrl($view_cav->AD1O) == "") { ?>
		<th data-name="AD1O"><div id="elh_view_cav_AD1O" class="view_cav_AD1O"><div class="ewTableHeaderCaption"><?php echo $view_cav->AD1O->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="AD1O"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->AD1O) ?>',2);"><div id="elh_view_cav_AD1O" class="view_cav_AD1O">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->AD1O->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->AD1O->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->AD1O->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_cav->FASE->Visible) { // FASE ?>
	<?php if ($view_cav->SortUrl($view_cav->FASE) == "") { ?>
		<th data-name="FASE"><div id="elh_view_cav_FASE" class="view_cav_FASE"><div class="ewTableHeaderCaption"><?php echo $view_cav->FASE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FASE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_cav->SortUrl($view_cav->FASE) ?>',2);"><div id="elh_view_cav_FASE" class="view_cav_FASE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_cav->FASE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_cav->FASE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_cav->FASE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_cav_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view_cav->ExportAll && $view_cav->Export <> "") {
	$view_cav_list->StopRec = $view_cav_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view_cav_list->TotalRecs > $view_cav_list->StartRec + $view_cav_list->DisplayRecs - 1)
		$view_cav_list->StopRec = $view_cav_list->StartRec + $view_cav_list->DisplayRecs - 1;
	else
		$view_cav_list->StopRec = $view_cav_list->TotalRecs;
}
$view_cav_list->RecCnt = $view_cav_list->StartRec - 1;
if ($view_cav_list->Recordset && !$view_cav_list->Recordset->EOF) {
	$view_cav_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $view_cav_list->StartRec > 1)
		$view_cav_list->Recordset->Move($view_cav_list->StartRec - 1);
} elseif (!$view_cav->AllowAddDeleteRow && $view_cav_list->StopRec == 0) {
	$view_cav_list->StopRec = $view_cav->GridAddRowCount;
}

// Initialize aggregate
$view_cav->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_cav->ResetAttrs();
$view_cav_list->RenderRow();
while ($view_cav_list->RecCnt < $view_cav_list->StopRec) {
	$view_cav_list->RecCnt++;
	if (intval($view_cav_list->RecCnt) >= intval($view_cav_list->StartRec)) {
		$view_cav_list->RowCnt++;

		// Set up key count
		$view_cav_list->KeyCount = $view_cav_list->RowIndex;

		// Init row class and style
		$view_cav->ResetAttrs();
		$view_cav->CssClass = "";
		if ($view_cav->CurrentAction == "gridadd") {
		} else {
			$view_cav_list->LoadRowValues($view_cav_list->Recordset); // Load row values
		}
		$view_cav->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view_cav->RowAttrs = array_merge($view_cav->RowAttrs, array('data-rowindex'=>$view_cav_list->RowCnt, 'id'=>'r' . $view_cav_list->RowCnt . '_view_cav', 'data-rowtype'=>$view_cav->RowType));

		// Render row
		$view_cav_list->RenderRow();

		// Render list options
		$view_cav_list->RenderListOptions();
?>
	<tr<?php echo $view_cav->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_cav_list->ListOptions->Render("body", "left", $view_cav_list->RowCnt);
?>
	<?php if ($view_cav->llave->Visible) { // llave ?>
		<td data-name="llave"<?php echo $view_cav->llave->CellAttributes() ?>>
<span<?php echo $view_cav->llave->ViewAttributes() ?>>
<?php echo $view_cav->llave->ListViewValue() ?></span>
<a id="<?php echo $view_cav_list->PageObjName . "_row_" . $view_cav_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_cav->F_Sincron->Visible) { // F_Sincron ?>
		<td data-name="F_Sincron"<?php echo $view_cav->F_Sincron->CellAttributes() ?>>
<span<?php echo $view_cav->F_Sincron->ViewAttributes() ?>>
<?php echo $view_cav->F_Sincron->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->USUARIO->Visible) { // USUARIO ?>
		<td data-name="USUARIO"<?php echo $view_cav->USUARIO->CellAttributes() ?>>
<span<?php echo $view_cav->USUARIO->ViewAttributes() ?>>
<?php echo $view_cav->USUARIO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Cargo_gme->Visible) { // Cargo_gme ?>
		<td data-name="Cargo_gme"<?php echo $view_cav->Cargo_gme->CellAttributes() ?>>
<span<?php echo $view_cav->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_cav->Cargo_gme->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Num_AV->Visible) { // Num_AV ?>
		<td data-name="Num_AV"<?php echo $view_cav->Num_AV->CellAttributes() ?>>
<span<?php echo $view_cav->Num_AV->ViewAttributes() ?>>
<?php echo $view_cav->Num_AV->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NOM_APOYO->Visible) { // NOM_APOYO ?>
		<td data-name="NOM_APOYO"<?php echo $view_cav->NOM_APOYO->CellAttributes() ?>>
<span<?php echo $view_cav->NOM_APOYO->ViewAttributes() ?>>
<?php echo $view_cav->NOM_APOYO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
		<td data-name="Otro_Nom_Apoyo"<?php echo $view_cav->Otro_Nom_Apoyo->CellAttributes() ?>>
<span<?php echo $view_cav->Otro_Nom_Apoyo->ViewAttributes() ?>>
<?php echo $view_cav->Otro_Nom_Apoyo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
		<td data-name="Otro_CC_Apoyo"<?php echo $view_cav->Otro_CC_Apoyo->CellAttributes() ?>>
<span<?php echo $view_cav->Otro_CC_Apoyo->ViewAttributes() ?>>
<?php echo $view_cav->Otro_CC_Apoyo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NOM_PE->Visible) { // NOM_PE ?>
		<td data-name="NOM_PE"<?php echo $view_cav->NOM_PE->CellAttributes() ?>>
<span<?php echo $view_cav->NOM_PE->ViewAttributes() ?>>
<?php echo $view_cav->NOM_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Otro_PE->Visible) { // Otro_PE ?>
		<td data-name="Otro_PE"<?php echo $view_cav->Otro_PE->CellAttributes() ?>>
<span<?php echo $view_cav->Otro_PE->ViewAttributes() ?>>
<?php echo $view_cav->Otro_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Departamento->Visible) { // Departamento ?>
		<td data-name="Departamento"<?php echo $view_cav->Departamento->CellAttributes() ?>>
<span<?php echo $view_cav->Departamento->ViewAttributes() ?>>
<?php echo $view_cav->Departamento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Muncipio->Visible) { // Muncipio ?>
		<td data-name="Muncipio"<?php echo $view_cav->Muncipio->CellAttributes() ?>>
<span<?php echo $view_cav->Muncipio->ViewAttributes() ?>>
<?php echo $view_cav->Muncipio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NOM_VDA->Visible) { // NOM_VDA ?>
		<td data-name="NOM_VDA"<?php echo $view_cav->NOM_VDA->CellAttributes() ?>>
<span<?php echo $view_cav->NOM_VDA->ViewAttributes() ?>>
<?php echo $view_cav->NOM_VDA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NO_E->Visible) { // NO_E ?>
		<td data-name="NO_E"<?php echo $view_cav->NO_E->CellAttributes() ?>>
<span<?php echo $view_cav->NO_E->ViewAttributes() ?>>
<?php echo $view_cav->NO_E->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NO_OF->Visible) { // NO_OF ?>
		<td data-name="NO_OF"<?php echo $view_cav->NO_OF->CellAttributes() ?>>
<span<?php echo $view_cav->NO_OF->ViewAttributes() ?>>
<?php echo $view_cav->NO_OF->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NO_SUBOF->Visible) { // NO_SUBOF ?>
		<td data-name="NO_SUBOF"<?php echo $view_cav->NO_SUBOF->CellAttributes() ?>>
<span<?php echo $view_cav->NO_SUBOF->ViewAttributes() ?>>
<?php echo $view_cav->NO_SUBOF->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NO_SOL->Visible) { // NO_SOL ?>
		<td data-name="NO_SOL"<?php echo $view_cav->NO_SOL->CellAttributes() ?>>
<span<?php echo $view_cav->NO_SOL->ViewAttributes() ?>>
<?php echo $view_cav->NO_SOL->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NO_PATRU->Visible) { // NO_PATRU ?>
		<td data-name="NO_PATRU"<?php echo $view_cav->NO_PATRU->CellAttributes() ?>>
<span<?php echo $view_cav->NO_PATRU->ViewAttributes() ?>>
<?php echo $view_cav->NO_PATRU->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Nom_enfer->Visible) { // Nom_enfer ?>
		<td data-name="Nom_enfer"<?php echo $view_cav->Nom_enfer->CellAttributes() ?>>
<span<?php echo $view_cav->Nom_enfer->ViewAttributes() ?>>
<?php echo $view_cav->Nom_enfer->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Otro_Nom_Enfer->Visible) { // Otro_Nom_Enfer ?>
		<td data-name="Otro_Nom_Enfer"<?php echo $view_cav->Otro_Nom_Enfer->CellAttributes() ?>>
<span<?php echo $view_cav->Otro_Nom_Enfer->ViewAttributes() ?>>
<?php echo $view_cav->Otro_Nom_Enfer->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Otro_CC_Enfer->Visible) { // Otro_CC_Enfer ?>
		<td data-name="Otro_CC_Enfer"<?php echo $view_cav->Otro_CC_Enfer->CellAttributes() ?>>
<span<?php echo $view_cav->Otro_CC_Enfer->ViewAttributes() ?>>
<?php echo $view_cav->Otro_CC_Enfer->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Armada->Visible) { // Armada ?>
		<td data-name="Armada"<?php echo $view_cav->Armada->CellAttributes() ?>>
<span<?php echo $view_cav->Armada->ViewAttributes() ?>>
<?php echo $view_cav->Armada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Ejercito->Visible) { // Ejercito ?>
		<td data-name="Ejercito"<?php echo $view_cav->Ejercito->CellAttributes() ?>>
<span<?php echo $view_cav->Ejercito->ViewAttributes() ?>>
<?php echo $view_cav->Ejercito->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Policia->Visible) { // Policia ?>
		<td data-name="Policia"<?php echo $view_cav->Policia->CellAttributes() ?>>
<span<?php echo $view_cav->Policia->ViewAttributes() ?>>
<?php echo $view_cav->Policia->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NOM_UNIDAD->Visible) { // NOM_UNIDAD ?>
		<td data-name="NOM_UNIDAD"<?php echo $view_cav->NOM_UNIDAD->CellAttributes() ?>>
<span<?php echo $view_cav->NOM_UNIDAD->ViewAttributes() ?>>
<?php echo $view_cav->NOM_UNIDAD->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NOM_COMAN->Visible) { // NOM_COMAN ?>
		<td data-name="NOM_COMAN"<?php echo $view_cav->NOM_COMAN->CellAttributes() ?>>
<span<?php echo $view_cav->NOM_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->NOM_COMAN->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->CC_COMAN->Visible) { // CC_COMAN ?>
		<td data-name="CC_COMAN"<?php echo $view_cav->CC_COMAN->CellAttributes() ?>>
<span<?php echo $view_cav->CC_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->CC_COMAN->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->TEL_COMAN->Visible) { // TEL_COMAN ?>
		<td data-name="TEL_COMAN"<?php echo $view_cav->TEL_COMAN->CellAttributes() ?>>
<span<?php echo $view_cav->TEL_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->TEL_COMAN->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->RANGO_COMAN->Visible) { // RANGO_COMAN ?>
		<td data-name="RANGO_COMAN"<?php echo $view_cav->RANGO_COMAN->CellAttributes() ?>>
<span<?php echo $view_cav->RANGO_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->RANGO_COMAN->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->Otro_rango->Visible) { // Otro_rango ?>
		<td data-name="Otro_rango"<?php echo $view_cav->Otro_rango->CellAttributes() ?>>
<span<?php echo $view_cav->Otro_rango->ViewAttributes() ?>>
<?php echo $view_cav->Otro_rango->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NO_GDETECCION->Visible) { // NO_GDETECCION ?>
		<td data-name="NO_GDETECCION"<?php echo $view_cav->NO_GDETECCION->CellAttributes() ?>>
<span<?php echo $view_cav->NO_GDETECCION->ViewAttributes() ?>>
<?php echo $view_cav->NO_GDETECCION->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->NO_BINOMIO->Visible) { // NO_BINOMIO ?>
		<td data-name="NO_BINOMIO"<?php echo $view_cav->NO_BINOMIO->CellAttributes() ?>>
<span<?php echo $view_cav->NO_BINOMIO->ViewAttributes() ?>>
<?php echo $view_cav->NO_BINOMIO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->FECHA_INTO_AV->Visible) { // FECHA_INTO_AV ?>
		<td data-name="FECHA_INTO_AV"<?php echo $view_cav->FECHA_INTO_AV->CellAttributes() ?>>
<span<?php echo $view_cav->FECHA_INTO_AV->ViewAttributes() ?>>
<?php echo $view_cav->FECHA_INTO_AV->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->DIA->Visible) { // DIA ?>
		<td data-name="DIA"<?php echo $view_cav->DIA->CellAttributes() ?>>
<span<?php echo $view_cav->DIA->ViewAttributes() ?>>
<?php echo $view_cav->DIA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->MES->Visible) { // MES ?>
		<td data-name="MES"<?php echo $view_cav->MES->CellAttributes() ?>>
<span<?php echo $view_cav->MES->ViewAttributes() ?>>
<?php echo $view_cav->MES->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->LATITUD->Visible) { // LATITUD ?>
		<td data-name="LATITUD"<?php echo $view_cav->LATITUD->CellAttributes() ?>>
<span<?php echo $view_cav->LATITUD->ViewAttributes() ?>>
<?php echo $view_cav->LATITUD->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->GRA_LAT->Visible) { // GRA_LAT ?>
		<td data-name="GRA_LAT"<?php echo $view_cav->GRA_LAT->CellAttributes() ?>>
<span<?php echo $view_cav->GRA_LAT->ViewAttributes() ?>>
<?php echo $view_cav->GRA_LAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->MIN_LAT->Visible) { // MIN_LAT ?>
		<td data-name="MIN_LAT"<?php echo $view_cav->MIN_LAT->CellAttributes() ?>>
<span<?php echo $view_cav->MIN_LAT->ViewAttributes() ?>>
<?php echo $view_cav->MIN_LAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->SEG_LAT->Visible) { // SEG_LAT ?>
		<td data-name="SEG_LAT"<?php echo $view_cav->SEG_LAT->CellAttributes() ?>>
<span<?php echo $view_cav->SEG_LAT->ViewAttributes() ?>>
<?php echo $view_cav->SEG_LAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->GRA_LONG->Visible) { // GRA_LONG ?>
		<td data-name="GRA_LONG"<?php echo $view_cav->GRA_LONG->CellAttributes() ?>>
<span<?php echo $view_cav->GRA_LONG->ViewAttributes() ?>>
<?php echo $view_cav->GRA_LONG->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->MIN_LONG->Visible) { // MIN_LONG ?>
		<td data-name="MIN_LONG"<?php echo $view_cav->MIN_LONG->CellAttributes() ?>>
<span<?php echo $view_cav->MIN_LONG->ViewAttributes() ?>>
<?php echo $view_cav->MIN_LONG->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->SEG_LONG->Visible) { // SEG_LONG ?>
		<td data-name="SEG_LONG"<?php echo $view_cav->SEG_LONG->CellAttributes() ?>>
<span<?php echo $view_cav->SEG_LONG->ViewAttributes() ?>>
<?php echo $view_cav->SEG_LONG->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->OBSERVACION->Visible) { // OBSERVACION ?>
		<td data-name="OBSERVACION"<?php echo $view_cav->OBSERVACION->CellAttributes() ?>>
<span<?php echo $view_cav->OBSERVACION->ViewAttributes() ?>>
<?php echo $view_cav->OBSERVACION->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->AD1O->Visible) { // AÑO ?>
		<td data-name="AD1O"<?php echo $view_cav->AD1O->CellAttributes() ?>>
<span<?php echo $view_cav->AD1O->ViewAttributes() ?>>
<?php echo $view_cav->AD1O->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_cav->FASE->Visible) { // FASE ?>
		<td data-name="FASE"<?php echo $view_cav->FASE->CellAttributes() ?>>
<span<?php echo $view_cav->FASE->ViewAttributes() ?>>
<?php echo $view_cav->FASE->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_cav_list->ListOptions->Render("body", "right", $view_cav_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view_cav->CurrentAction <> "gridadd")
		$view_cav_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view_cav->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view_cav_list->Recordset)
	$view_cav_list->Recordset->Close();
?>
<?php if ($view_cav->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view_cav->CurrentAction <> "gridadd" && $view_cav->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_cav_list->Pager)) $view_cav_list->Pager = new cPrevNextPager($view_cav_list->StartRec, $view_cav_list->DisplayRecs, $view_cav_list->TotalRecs) ?>
<?php if ($view_cav_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_cav_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_cav_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_cav_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_cav_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_cav_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_cav_list->PageUrl() ?>start=<?php echo $view_cav_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_cav_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_cav_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_cav_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_cav_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_cav_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view_cav_list->TotalRecs == 0 && $view_cav->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_cav_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view_cav->Export == "") { ?>
<script type="text/javascript">
fview_cavlistsrch.Init();
fview_cavlist.Init();
</script>
<?php } ?>
<?php
$view_cav_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view_cav->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view_cav_list->Page_Terminate();
?>
