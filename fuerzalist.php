<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "fuerzainfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$fuerza_list = NULL; // Initialize page object first

class cfuerza_list extends cfuerza {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'fuerza';

	// Page object name
	var $PageObjName = 'fuerza_list';

	// Grid form hidden field names
	var $FormName = 'ffuerzalist';
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

		// Table object (fuerza)
		if (!isset($GLOBALS["fuerza"]) || get_class($GLOBALS["fuerza"]) == "cfuerza") {
			$GLOBALS["fuerza"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fuerza"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "fuerzaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "fuerzadelete.php";
		$this->MultiUpdateUrl = "fuerzaupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fuerza', TRUE);

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
		global $EW_EXPORT, $fuerza;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($fuerza);
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
			$this->Punto->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Fuerza, $Default, FALSE); // Fuerza
		$this->BuildSearchSql($sWhere, $this->Estado, $Default, FALSE); // Estado
		$this->BuildSearchSql($sWhere, $this->AF1o, $Default, FALSE); // Año
		$this->BuildSearchSql($sWhere, $this->Fase, $Default, FALSE); // Fase
		$this->BuildSearchSql($sWhere, $this->_23_del_punto, $Default, FALSE); // #_del_punto
		$this->BuildSearchSql($sWhere, $this->Punto, $Default, FALSE); // Punto
		$this->BuildSearchSql($sWhere, $this->Profesional_especializado, $Default, FALSE); // Profesional_especializado

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Fuerza->AdvancedSearch->Save(); // Fuerza
			$this->Estado->AdvancedSearch->Save(); // Estado
			$this->AF1o->AdvancedSearch->Save(); // Año
			$this->Fase->AdvancedSearch->Save(); // Fase
			$this->_23_del_punto->AdvancedSearch->Save(); // #_del_punto
			$this->Punto->AdvancedSearch->Save(); // Punto
			$this->Profesional_especializado->AdvancedSearch->Save(); // Profesional_especializado
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
		$this->BuildBasicSearchSQL($sWhere, $this->Fuerza, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Estado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Fase, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_23_del_punto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Punto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Profesional_especializado, $arKeywords, $type);
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
		if ($this->Fuerza->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Estado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->AF1o->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fase->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_23_del_punto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Punto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Profesional_especializado->AdvancedSearch->IssetSession())
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
		$this->Fuerza->AdvancedSearch->UnsetSession();
		$this->Estado->AdvancedSearch->UnsetSession();
		$this->AF1o->AdvancedSearch->UnsetSession();
		$this->Fase->AdvancedSearch->UnsetSession();
		$this->_23_del_punto->AdvancedSearch->UnsetSession();
		$this->Punto->AdvancedSearch->UnsetSession();
		$this->Profesional_especializado->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->Fuerza->AdvancedSearch->Load();
		$this->Estado->AdvancedSearch->Load();
		$this->AF1o->AdvancedSearch->Load();
		$this->Fase->AdvancedSearch->Load();
		$this->_23_del_punto->AdvancedSearch->Load();
		$this->Punto->AdvancedSearch->Load();
		$this->Profesional_especializado->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Fuerza, $bCtrl); // Fuerza
			$this->UpdateSort($this->Estado, $bCtrl); // Estado
			$this->UpdateSort($this->AF1o, $bCtrl); // Año
			$this->UpdateSort($this->Fase, $bCtrl); // Fase
			$this->UpdateSort($this->_23_del_punto, $bCtrl); // #_del_punto
			$this->UpdateSort($this->Punto, $bCtrl); // Punto
			$this->UpdateSort($this->Profesional_especializado, $bCtrl); // Profesional_especializado
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
				$this->Fuerza->setSort("");
				$this->Estado->setSort("");
				$this->AF1o->setSort("");
				$this->Fase->setSort("");
				$this->_23_del_punto->setSort("");
				$this->Punto->setSort("");
				$this->Profesional_especializado->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Punto->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.ffuerzalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ffuerzalistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// Fuerza

		$this->Fuerza->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fuerza"]);
		if ($this->Fuerza->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fuerza->AdvancedSearch->SearchOperator = @$_GET["z_Fuerza"];

		// Estado
		$this->Estado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Estado"]);
		if ($this->Estado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Estado->AdvancedSearch->SearchOperator = @$_GET["z_Estado"];

		// Año
		$this->AF1o->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_AF1o"]);
		if ($this->AF1o->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->AF1o->AdvancedSearch->SearchOperator = @$_GET["z_AF1o"];

		// Fase
		$this->Fase->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fase"]);
		if ($this->Fase->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fase->AdvancedSearch->SearchOperator = @$_GET["z_Fase"];

		// #_del_punto
		$this->_23_del_punto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__23_del_punto"]);
		if ($this->_23_del_punto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_23_del_punto->AdvancedSearch->SearchOperator = @$_GET["z__23_del_punto"];

		// Punto
		$this->Punto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Punto"]);
		if ($this->Punto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Punto->AdvancedSearch->SearchOperator = @$_GET["z_Punto"];

		// Profesional_especializado
		$this->Profesional_especializado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Profesional_especializado"]);
		if ($this->Profesional_especializado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Profesional_especializado->AdvancedSearch->SearchOperator = @$_GET["z_Profesional_especializado"];
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
		$this->Fuerza->setDbValue($rs->fields('Fuerza'));
		$this->Estado->setDbValue($rs->fields('Estado'));
		$this->AF1o->setDbValue($rs->fields('Año'));
		$this->Fase->setDbValue($rs->fields('Fase'));
		$this->_23_del_punto->setDbValue($rs->fields('#_del_punto'));
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->Profesional_especializado->setDbValue($rs->fields('Profesional_especializado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Fuerza->DbValue = $row['Fuerza'];
		$this->Estado->DbValue = $row['Estado'];
		$this->AF1o->DbValue = $row['Año'];
		$this->Fase->DbValue = $row['Fase'];
		$this->_23_del_punto->DbValue = $row['#_del_punto'];
		$this->Punto->DbValue = $row['Punto'];
		$this->Profesional_especializado->DbValue = $row['Profesional_especializado'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Punto")) <> "")
			$this->Punto->CurrentValue = $this->getKey("Punto"); // Punto
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Fuerza
		// Estado
		// Año
		// Fase
		// #_del_punto
		// Punto
		// Profesional_especializado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Fuerza
			if (strval($this->Fuerza->CurrentValue) <> "") {
				switch ($this->Fuerza->CurrentValue) {
					case $this->Fuerza->FldTagValue(1):
						$this->Fuerza->ViewValue = $this->Fuerza->FldTagCaption(1) <> "" ? $this->Fuerza->FldTagCaption(1) : $this->Fuerza->CurrentValue;
						break;
					case $this->Fuerza->FldTagValue(2):
						$this->Fuerza->ViewValue = $this->Fuerza->FldTagCaption(2) <> "" ? $this->Fuerza->FldTagCaption(2) : $this->Fuerza->CurrentValue;
						break;
					case $this->Fuerza->FldTagValue(3):
						$this->Fuerza->ViewValue = $this->Fuerza->FldTagCaption(3) <> "" ? $this->Fuerza->FldTagCaption(3) : $this->Fuerza->CurrentValue;
						break;
					default:
						$this->Fuerza->ViewValue = $this->Fuerza->CurrentValue;
				}
			} else {
				$this->Fuerza->ViewValue = NULL;
			}
			$this->Fuerza->ViewCustomAttributes = "";

			// Estado
			if (strval($this->Estado->CurrentValue) <> "") {
				switch ($this->Estado->CurrentValue) {
					case $this->Estado->FldTagValue(1):
						$this->Estado->ViewValue = $this->Estado->FldTagCaption(1) <> "" ? $this->Estado->FldTagCaption(1) : $this->Estado->CurrentValue;
						break;
					case $this->Estado->FldTagValue(2):
						$this->Estado->ViewValue = $this->Estado->FldTagCaption(2) <> "" ? $this->Estado->FldTagCaption(2) : $this->Estado->CurrentValue;
						break;
					default:
						$this->Estado->ViewValue = $this->Estado->CurrentValue;
				}
			} else {
				$this->Estado->ViewValue = NULL;
			}
			$this->Estado->ViewCustomAttributes = "";

			// Año
			$this->AF1o->ViewValue = $this->AF1o->CurrentValue;
			$this->AF1o->ViewCustomAttributes = "";

			// Fase
			if (strval($this->Fase->CurrentValue) <> "") {
				switch ($this->Fase->CurrentValue) {
					case $this->Fase->FldTagValue(1):
						$this->Fase->ViewValue = $this->Fase->FldTagCaption(1) <> "" ? $this->Fase->FldTagCaption(1) : $this->Fase->CurrentValue;
						break;
					case $this->Fase->FldTagValue(2):
						$this->Fase->ViewValue = $this->Fase->FldTagCaption(2) <> "" ? $this->Fase->FldTagCaption(2) : $this->Fase->CurrentValue;
						break;
					case $this->Fase->FldTagValue(3):
						$this->Fase->ViewValue = $this->Fase->FldTagCaption(3) <> "" ? $this->Fase->FldTagCaption(3) : $this->Fase->CurrentValue;
						break;
					case $this->Fase->FldTagValue(4):
						$this->Fase->ViewValue = $this->Fase->FldTagCaption(4) <> "" ? $this->Fase->FldTagCaption(4) : $this->Fase->CurrentValue;
						break;
					default:
						$this->Fase->ViewValue = $this->Fase->CurrentValue;
				}
			} else {
				$this->Fase->ViewValue = NULL;
			}
			$this->Fase->ViewCustomAttributes = "";

			// #_del_punto
			$this->_23_del_punto->ViewValue = $this->_23_del_punto->CurrentValue;
			$this->_23_del_punto->ViewCustomAttributes = "";

			// Punto
			$this->Punto->ViewValue = $this->Punto->CurrentValue;
			$this->Punto->ViewCustomAttributes = "";

			// Profesional_especializado
			if (strval($this->Profesional_especializado->CurrentValue) <> "") {
				switch ($this->Profesional_especializado->CurrentValue) {
					case $this->Profesional_especializado->FldTagValue(1):
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(1) <> "" ? $this->Profesional_especializado->FldTagCaption(1) : $this->Profesional_especializado->CurrentValue;
						break;
					case $this->Profesional_especializado->FldTagValue(2):
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(2) <> "" ? $this->Profesional_especializado->FldTagCaption(2) : $this->Profesional_especializado->CurrentValue;
						break;
					case $this->Profesional_especializado->FldTagValue(3):
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(3) <> "" ? $this->Profesional_especializado->FldTagCaption(3) : $this->Profesional_especializado->CurrentValue;
						break;
					case $this->Profesional_especializado->FldTagValue(4):
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(4) <> "" ? $this->Profesional_especializado->FldTagCaption(4) : $this->Profesional_especializado->CurrentValue;
						break;
					case $this->Profesional_especializado->FldTagValue(5):
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(5) <> "" ? $this->Profesional_especializado->FldTagCaption(5) : $this->Profesional_especializado->CurrentValue;
						break;
					case $this->Profesional_especializado->FldTagValue(6):
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(6) <> "" ? $this->Profesional_especializado->FldTagCaption(6) : $this->Profesional_especializado->CurrentValue;
						break;
					case $this->Profesional_especializado->FldTagValue(7):
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->FldTagCaption(7) <> "" ? $this->Profesional_especializado->FldTagCaption(7) : $this->Profesional_especializado->CurrentValue;
						break;
					default:
						$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->CurrentValue;
				}
			} else {
				$this->Profesional_especializado->ViewValue = NULL;
			}
			$this->Profesional_especializado->ViewCustomAttributes = "";

			// Fuerza
			$this->Fuerza->LinkCustomAttributes = "";
			$this->Fuerza->HrefValue = "";
			$this->Fuerza->TooltipValue = "";

			// Estado
			$this->Estado->LinkCustomAttributes = "";
			$this->Estado->HrefValue = "";
			$this->Estado->TooltipValue = "";

			// Año
			$this->AF1o->LinkCustomAttributes = "";
			$this->AF1o->HrefValue = "";
			$this->AF1o->TooltipValue = "";

			// Fase
			$this->Fase->LinkCustomAttributes = "";
			$this->Fase->HrefValue = "";
			$this->Fase->TooltipValue = "";

			// #_del_punto
			$this->_23_del_punto->LinkCustomAttributes = "";
			$this->_23_del_punto->HrefValue = "";
			$this->_23_del_punto->TooltipValue = "";

			// Punto
			$this->Punto->LinkCustomAttributes = "";
			$this->Punto->HrefValue = "";
			$this->Punto->TooltipValue = "";

			// Profesional_especializado
			$this->Profesional_especializado->LinkCustomAttributes = "";
			$this->Profesional_especializado->HrefValue = "";
			$this->Profesional_especializado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Fuerza
			$this->Fuerza->EditAttrs["class"] = "form-control";
			$this->Fuerza->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Fuerza->FldTagValue(1), $this->Fuerza->FldTagCaption(1) <> "" ? $this->Fuerza->FldTagCaption(1) : $this->Fuerza->FldTagValue(1));
			$arwrk[] = array($this->Fuerza->FldTagValue(2), $this->Fuerza->FldTagCaption(2) <> "" ? $this->Fuerza->FldTagCaption(2) : $this->Fuerza->FldTagValue(2));
			$arwrk[] = array($this->Fuerza->FldTagValue(3), $this->Fuerza->FldTagCaption(3) <> "" ? $this->Fuerza->FldTagCaption(3) : $this->Fuerza->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Fuerza->EditValue = $arwrk;

			// Estado
			$this->Estado->EditAttrs["class"] = "form-control";
			$this->Estado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Estado->FldTagValue(1), $this->Estado->FldTagCaption(1) <> "" ? $this->Estado->FldTagCaption(1) : $this->Estado->FldTagValue(1));
			$arwrk[] = array($this->Estado->FldTagValue(2), $this->Estado->FldTagCaption(2) <> "" ? $this->Estado->FldTagCaption(2) : $this->Estado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Estado->EditValue = $arwrk;

			// Año
			$this->AF1o->EditAttrs["class"] = "form-control";
			$this->AF1o->EditCustomAttributes = "";
			$this->AF1o->EditValue = ew_HtmlEncode($this->AF1o->AdvancedSearch->SearchValue);
			$this->AF1o->PlaceHolder = ew_RemoveHtml($this->AF1o->FldCaption());

			// Fase
			$this->Fase->EditAttrs["class"] = "form-control";
			$this->Fase->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Fase->FldTagValue(1), $this->Fase->FldTagCaption(1) <> "" ? $this->Fase->FldTagCaption(1) : $this->Fase->FldTagValue(1));
			$arwrk[] = array($this->Fase->FldTagValue(2), $this->Fase->FldTagCaption(2) <> "" ? $this->Fase->FldTagCaption(2) : $this->Fase->FldTagValue(2));
			$arwrk[] = array($this->Fase->FldTagValue(3), $this->Fase->FldTagCaption(3) <> "" ? $this->Fase->FldTagCaption(3) : $this->Fase->FldTagValue(3));
			$arwrk[] = array($this->Fase->FldTagValue(4), $this->Fase->FldTagCaption(4) <> "" ? $this->Fase->FldTagCaption(4) : $this->Fase->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Fase->EditValue = $arwrk;

			// #_del_punto
			$this->_23_del_punto->EditAttrs["class"] = "form-control";
			$this->_23_del_punto->EditCustomAttributes = "";
			$this->_23_del_punto->EditValue = ew_HtmlEncode($this->_23_del_punto->AdvancedSearch->SearchValue);
			$this->_23_del_punto->PlaceHolder = ew_RemoveHtml($this->_23_del_punto->FldCaption());

			// Punto
			$this->Punto->EditAttrs["class"] = "form-control";
			$this->Punto->EditCustomAttributes = "";
			$this->Punto->EditValue = ew_HtmlEncode($this->Punto->AdvancedSearch->SearchValue);
			$this->Punto->PlaceHolder = ew_RemoveHtml($this->Punto->FldCaption());

			// Profesional_especializado
			$this->Profesional_especializado->EditAttrs["class"] = "form-control";
			$this->Profesional_especializado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Profesional_especializado->FldTagValue(1), $this->Profesional_especializado->FldTagCaption(1) <> "" ? $this->Profesional_especializado->FldTagCaption(1) : $this->Profesional_especializado->FldTagValue(1));
			$arwrk[] = array($this->Profesional_especializado->FldTagValue(2), $this->Profesional_especializado->FldTagCaption(2) <> "" ? $this->Profesional_especializado->FldTagCaption(2) : $this->Profesional_especializado->FldTagValue(2));
			$arwrk[] = array($this->Profesional_especializado->FldTagValue(3), $this->Profesional_especializado->FldTagCaption(3) <> "" ? $this->Profesional_especializado->FldTagCaption(3) : $this->Profesional_especializado->FldTagValue(3));
			$arwrk[] = array($this->Profesional_especializado->FldTagValue(4), $this->Profesional_especializado->FldTagCaption(4) <> "" ? $this->Profesional_especializado->FldTagCaption(4) : $this->Profesional_especializado->FldTagValue(4));
			$arwrk[] = array($this->Profesional_especializado->FldTagValue(5), $this->Profesional_especializado->FldTagCaption(5) <> "" ? $this->Profesional_especializado->FldTagCaption(5) : $this->Profesional_especializado->FldTagValue(5));
			$arwrk[] = array($this->Profesional_especializado->FldTagValue(6), $this->Profesional_especializado->FldTagCaption(6) <> "" ? $this->Profesional_especializado->FldTagCaption(6) : $this->Profesional_especializado->FldTagValue(6));
			$arwrk[] = array($this->Profesional_especializado->FldTagValue(7), $this->Profesional_especializado->FldTagCaption(7) <> "" ? $this->Profesional_especializado->FldTagCaption(7) : $this->Profesional_especializado->FldTagValue(7));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Profesional_especializado->EditValue = $arwrk;
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
		if (!ew_CheckInteger($this->AF1o->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->AF1o->FldErrMsg());
		}

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
		$this->Fuerza->AdvancedSearch->Load();
		$this->Estado->AdvancedSearch->Load();
		$this->AF1o->AdvancedSearch->Load();
		$this->Fase->AdvancedSearch->Load();
		$this->_23_del_punto->AdvancedSearch->Load();
		$this->Punto->AdvancedSearch->Load();
		$this->Profesional_especializado->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_fuerza\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_fuerza',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ffuerzalist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($fuerza_list)) $fuerza_list = new cfuerza_list();

// Page init
$fuerza_list->Page_Init();

// Page main
$fuerza_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fuerza_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($fuerza->Export == "") { ?>
<script type="text/javascript">

// Page object
var fuerza_list = new ew_Page("fuerza_list");
fuerza_list.PageID = "list"; // Page ID
var EW_PAGE_ID = fuerza_list.PageID; // For backward compatibility

// Form object
var ffuerzalist = new ew_Form("ffuerzalist");
ffuerzalist.FormKeyCountName = '<?php echo $fuerza_list->FormKeyCountName ?>';

// Form_CustomValidate event
ffuerzalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffuerzalist.ValidateRequired = true;
<?php } else { ?>
ffuerzalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var ffuerzalistsrch = new ew_Form("ffuerzalistsrch");

// Validate function for search
ffuerzalistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_AF1o");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($fuerza->AF1o->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
ffuerzalistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffuerzalistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ffuerzalistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($fuerza->Export == "") { ?>
<div class="ewToolbar">
<?php if ($fuerza->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<h2>Fuerza Pública</h2>
<p>En esta página se observa, adiciona y edita la información referente a la fuerza pública que esta acompañando la labor de erradicación en cada Punto <p>
<hr>

<table>
	<tr>
		<td><?php $fuerza_list->ExportOptions->Render("body") ?></td>
		<td>Si desea exportar la tabla en formato excel haga click en el siguiente icono</td>
	</tr>	
</table>

<hr>
</div>
<div>
<br>

<table>
	<tr>
		<td><?php $fuerza_list->SearchOptions->Render("body") ?></td>
		<td>Si desea realizar filtros en la tabla haga click en el siguiente icono e ingrese el dato en la columna correspondiente</td>
	</tr>
</table>
<br>
<hr>
<br>

<?php if ($fuerza_list->TotalRecs > 0 && $fuerza_list->ExportOptions->Visible()) { ?>


<?php } ?>
<?php if ($fuerza_list->SearchOptions->Visible()) { ?>


<?php } ?>
<?php if ($fuerza->Export == "") { ?>


<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($fuerza_list->TotalRecs <= 0)
			$fuerza_list->TotalRecs = $fuerza->SelectRecordCount();
	} else {
		if (!$fuerza_list->Recordset && ($fuerza_list->Recordset = $fuerza_list->LoadRecordset()))
			$fuerza_list->TotalRecs = $fuerza_list->Recordset->RecordCount();
	}
	$fuerza_list->StartRec = 1;
	if ($fuerza_list->DisplayRecs <= 0 || ($fuerza->Export <> "" && $fuerza->ExportAll)) // Display all records
		$fuerza_list->DisplayRecs = $fuerza_list->TotalRecs;
	if (!($fuerza->Export <> "" && $fuerza->ExportAll))
		$fuerza_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$fuerza_list->Recordset = $fuerza_list->LoadRecordset($fuerza_list->StartRec-1, $fuerza_list->DisplayRecs);

	// Set no record found message
	if ($fuerza->CurrentAction == "" && $fuerza_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$fuerza_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($fuerza_list->SearchWhere == "0=101")
			$fuerza_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$fuerza_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$fuerza_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($fuerza->Export == "" && $fuerza->CurrentAction == "") { ?>
<form name="ffuerzalistsrch" id="ffuerzalistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($fuerza_list->SearchWhere <> "") ? " " : " "; ?>
<div id="ffuerzalistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="fuerza">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$fuerza_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$fuerza->RowType = EW_ROWTYPE_SEARCH;

// Render row
$fuerza->ResetAttrs();
$fuerza_list->RenderRow();
?>


<table>
	<tr>
		<td><label for="x_Fuerza" class="ewSearchCaption ewLabel">FUERZA</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Fuerza" id="z_Fuerza" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<select data-field="x_Fuerza" id="x_Fuerza" name="x_Fuerza"<?php echo $fuerza->Fuerza->EditAttributes() ?>>
			<?php
			if (is_array($fuerza->Fuerza->EditValue)) {
				$arwrk = $fuerza->Fuerza->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($fuerza->Fuerza->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			</span></td>
	</tr>
	<tr>
		<td><label for="x_Estado" class="ewSearchCaption ewLabel">ESTADO</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Estado" id="z_Estado" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<select data-field="x_Estado" id="x_Estado" name="x_Estado"<?php echo $fuerza->Estado->EditAttributes() ?>>
			<?php
			if (is_array($fuerza->Estado->EditValue)) {
				$arwrk = $fuerza->Estado->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($fuerza->Estado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			</span></td>
	</tr>
	<tr>
		<td><label for="x_AF1o" class="ewSearchCaption ewLabel">AÑO</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_AF1o" id="z_AF1o" value="="></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<input type="text" data-field="x_AF1o" name="x_AF1o" id="x_AF1o" size="30" placeholder="<?php echo ew_HtmlEncode($fuerza->AF1o->PlaceHolder) ?>" value="<?php echo $fuerza->AF1o->EditValue ?>"<?php echo $fuerza->AF1o->EditAttributes() ?>>
			</span></td>
	</tr>
	<tr>
		<td><label for="x_Fase" class="ewSearchCaption ewLabel">FASE</label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Fase" id="z_Fase" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<select data-field="x_Fase" id="x_Fase" name="x_Fase"<?php echo $fuerza->Fase->EditAttributes() ?>>
			<?php
			if (is_array($fuerza->Fase->EditValue)) {
				$arwrk = $fuerza->Fase->EditValue;
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = (strval($fuerza->Fase->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			</span></td>
	</tr>
	<tr>
		<td><label for="x__23_del_punto" class="ewSearchCaption ewLabel">NÚMERO DEL PUNTO</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__23_del_punto" id="z__23_del_punto" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<input type="text" data-field="x__23_del_punto" name="x__23_del_punto" id="x__23_del_punto" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($fuerza->_23_del_punto->PlaceHolder) ?>" value="<?php echo $fuerza->_23_del_punto->EditValue ?>"<?php echo $fuerza->_23_del_punto->EditAttributes() ?>>
			</span></td>
	</tr>
	<tr>
		<td><label for="x_Punto" class="ewSearchCaption ewLabel">PUNTO DE ERRADICACIÓN</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Punto" id="z_Punto" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<input type="text" data-field="x_Punto" name="x_Punto" id="x_Punto" size="30" maxlength="6" placeholder="<?php echo ew_HtmlEncode($fuerza->Punto->PlaceHolder) ?>" value="<?php echo $fuerza->Punto->EditValue ?>"<?php echo $fuerza->Punto->EditAttributes() ?>>
			</span></td>
	</tr>
	<tr>
		<td><label for="x_Profesional_especializado" class="ewSearchCaption ewLabel"><?php echo $fuerza->Profesional_especializado->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Profesional_especializado" id="z_Profesional_especializado" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<select data-field="x_Profesional_especializado" id="x_Profesional_especializado" name="x_Profesional_especializado"<?php echo $fuerza->Profesional_especializado->EditAttributes() ?>>
<?php
if (is_array($fuerza->Profesional_especializado->EditValue)) {
	$arwrk = $fuerza->Profesional_especializado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fuerza->Profesional_especializado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
			</span></td>
	</tr>
</table>
<?php if ($fuerza->Fuerza->Visible) { // Fuerza ?>

<?php } ?>

<?php if ($fuerza->Estado->Visible) { // Estado ?>
	
<?php } ?>

<?php if ($fuerza->AF1o->Visible) { // Año ?>

<?php } ?>

<?php if ($fuerza->Fase->Visible) { // Fase ?>
	
<?php } ?>

<?php if ($fuerza->_23_del_punto->Visible) { // #_del_punto ?>

<?php } ?>

<?php if ($fuerza->Punto->Visible) { // Punto ?>
	
<?php } ?>

<?php if ($fuerza->Profesional_especializado->Visible) { // Profesional_especializado ?>

<?php } ?>

	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>

<br><br>
<hr>

	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $fuerza_list->ShowPageHeader(); ?>
<?php
$fuerza_list->ShowMessage();
?>
<?php if ($fuerza_list->TotalRecs > 0 || $fuerza->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($fuerza->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($fuerza->CurrentAction <> "gridadd" && $fuerza->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($fuerza_list->Pager)) $fuerza_list->Pager = new cPrevNextPager($fuerza_list->StartRec, $fuerza_list->DisplayRecs, $fuerza_list->TotalRecs) ?>
<?php if ($fuerza_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($fuerza_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($fuerza_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $fuerza_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($fuerza_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($fuerza_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $fuerza_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $fuerza_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $fuerza_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $fuerza_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($fuerza_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="ffuerzalist" id="ffuerzalist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($fuerza_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $fuerza_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="fuerza">
<div id="gmp_fuerza" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($fuerza_list->TotalRecs > 0) { ?>
<table id="tbl_fuerzalist" class="table ewTable">
<?php echo $fuerza->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$fuerza->RowType = EW_ROWTYPE_HEADER;

// Render list options
$fuerza_list->RenderListOptions();

// Render list options (header, left)
$fuerza_list->ListOptions->Render("header", "left");
?>
<?php if ($fuerza->Fuerza->Visible) { // Fuerza ?>
	<?php if ($fuerza->SortUrl($fuerza->Fuerza) == "") { ?>
		<th data-name="Fuerza"><div id="elh_fuerza_Fuerza" class="fuerza_Fuerza"><div class="ewTableHeaderCaption"><?php echo $fuerza->Fuerza->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fuerza"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $fuerza->SortUrl($fuerza->Fuerza) ?>',2);"><div id="elh_fuerza_Fuerza" class="fuerza_Fuerza">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fuerza->Fuerza->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fuerza->Fuerza->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fuerza->Fuerza->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($fuerza->Estado->Visible) { // Estado ?>
	<?php if ($fuerza->SortUrl($fuerza->Estado) == "") { ?>
		<th data-name="Estado"><div id="elh_fuerza_Estado" class="fuerza_Estado"><div class="ewTableHeaderCaption"><?php echo $fuerza->Estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Estado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $fuerza->SortUrl($fuerza->Estado) ?>',2);"><div id="elh_fuerza_Estado" class="fuerza_Estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fuerza->Estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fuerza->Estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fuerza->Estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($fuerza->AF1o->Visible) { // Año ?>
	<?php if ($fuerza->SortUrl($fuerza->AF1o) == "") { ?>
		<th data-name="AF1o"><div id="elh_fuerza_AF1o" class="fuerza_AF1o"><div class="ewTableHeaderCaption"><?php echo $fuerza->AF1o->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="AF1o"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $fuerza->SortUrl($fuerza->AF1o) ?>',2);"><div id="elh_fuerza_AF1o" class="fuerza_AF1o">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fuerza->AF1o->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fuerza->AF1o->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fuerza->AF1o->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($fuerza->Fase->Visible) { // Fase ?>
	<?php if ($fuerza->SortUrl($fuerza->Fase) == "") { ?>
		<th data-name="Fase"><div id="elh_fuerza_Fase" class="fuerza_Fase"><div class="ewTableHeaderCaption"><?php echo $fuerza->Fase->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fase"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $fuerza->SortUrl($fuerza->Fase) ?>',2);"><div id="elh_fuerza_Fase" class="fuerza_Fase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fuerza->Fase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fuerza->Fase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fuerza->Fase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($fuerza->_23_del_punto->Visible) { // #_del_punto ?>
	<?php if ($fuerza->SortUrl($fuerza->_23_del_punto) == "") { ?>
		<th data-name="_23_del_punto"><div id="elh_fuerza__23_del_punto" class="fuerza__23_del_punto"><div class="ewTableHeaderCaption"><?php echo $fuerza->_23_del_punto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_23_del_punto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $fuerza->SortUrl($fuerza->_23_del_punto) ?>',2);"><div id="elh_fuerza__23_del_punto" class="fuerza__23_del_punto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fuerza->_23_del_punto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($fuerza->_23_del_punto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fuerza->_23_del_punto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($fuerza->Punto->Visible) { // Punto ?>
	<?php if ($fuerza->SortUrl($fuerza->Punto) == "") { ?>
		<th data-name="Punto"><div id="elh_fuerza_Punto" class="fuerza_Punto"><div class="ewTableHeaderCaption"><?php echo $fuerza->Punto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Punto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $fuerza->SortUrl($fuerza->Punto) ?>',2);"><div id="elh_fuerza_Punto" class="fuerza_Punto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fuerza->Punto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($fuerza->Punto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fuerza->Punto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($fuerza->Profesional_especializado->Visible) { // Profesional_especializado ?>
	<?php if ($fuerza->SortUrl($fuerza->Profesional_especializado) == "") { ?>
		<th data-name="Profesional_especializado"><div id="elh_fuerza_Profesional_especializado" class="fuerza_Profesional_especializado"><div class="ewTableHeaderCaption"><?php echo $fuerza->Profesional_especializado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Profesional_especializado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $fuerza->SortUrl($fuerza->Profesional_especializado) ?>',2);"><div id="elh_fuerza_Profesional_especializado" class="fuerza_Profesional_especializado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fuerza->Profesional_especializado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fuerza->Profesional_especializado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fuerza->Profesional_especializado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$fuerza_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($fuerza->ExportAll && $fuerza->Export <> "") {
	$fuerza_list->StopRec = $fuerza_list->TotalRecs;
} else {

	// Set the last record to display
	if ($fuerza_list->TotalRecs > $fuerza_list->StartRec + $fuerza_list->DisplayRecs - 1)
		$fuerza_list->StopRec = $fuerza_list->StartRec + $fuerza_list->DisplayRecs - 1;
	else
		$fuerza_list->StopRec = $fuerza_list->TotalRecs;
}
$fuerza_list->RecCnt = $fuerza_list->StartRec - 1;
if ($fuerza_list->Recordset && !$fuerza_list->Recordset->EOF) {
	$fuerza_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $fuerza_list->StartRec > 1)
		$fuerza_list->Recordset->Move($fuerza_list->StartRec - 1);
} elseif (!$fuerza->AllowAddDeleteRow && $fuerza_list->StopRec == 0) {
	$fuerza_list->StopRec = $fuerza->GridAddRowCount;
}

// Initialize aggregate
$fuerza->RowType = EW_ROWTYPE_AGGREGATEINIT;
$fuerza->ResetAttrs();
$fuerza_list->RenderRow();
while ($fuerza_list->RecCnt < $fuerza_list->StopRec) {
	$fuerza_list->RecCnt++;
	if (intval($fuerza_list->RecCnt) >= intval($fuerza_list->StartRec)) {
		$fuerza_list->RowCnt++;

		// Set up key count
		$fuerza_list->KeyCount = $fuerza_list->RowIndex;

		// Init row class and style
		$fuerza->ResetAttrs();
		$fuerza->CssClass = "";
		if ($fuerza->CurrentAction == "gridadd") {
		} else {
			$fuerza_list->LoadRowValues($fuerza_list->Recordset); // Load row values
		}
		$fuerza->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$fuerza->RowAttrs = array_merge($fuerza->RowAttrs, array('data-rowindex'=>$fuerza_list->RowCnt, 'id'=>'r' . $fuerza_list->RowCnt . '_fuerza', 'data-rowtype'=>$fuerza->RowType));

		// Render row
		$fuerza_list->RenderRow();

		// Render list options
		$fuerza_list->RenderListOptions();
?>
	<tr<?php echo $fuerza->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fuerza_list->ListOptions->Render("body", "left", $fuerza_list->RowCnt);
?>
	<?php if ($fuerza->Fuerza->Visible) { // Fuerza ?>
		<td data-name="Fuerza"<?php echo $fuerza->Fuerza->CellAttributes() ?>>
<span<?php echo $fuerza->Fuerza->ViewAttributes() ?>>
<?php echo $fuerza->Fuerza->ListViewValue() ?></span>
<a id="<?php echo $fuerza_list->PageObjName . "_row_" . $fuerza_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($fuerza->Estado->Visible) { // Estado ?>
		<td data-name="Estado"<?php echo $fuerza->Estado->CellAttributes() ?>>
<span<?php echo $fuerza->Estado->ViewAttributes() ?>>
<?php echo $fuerza->Estado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($fuerza->AF1o->Visible) { // Año ?>
		<td data-name="AF1o"<?php echo $fuerza->AF1o->CellAttributes() ?>>
<span<?php echo $fuerza->AF1o->ViewAttributes() ?>>
<?php echo $fuerza->AF1o->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($fuerza->Fase->Visible) { // Fase ?>
		<td data-name="Fase"<?php echo $fuerza->Fase->CellAttributes() ?>>
<span<?php echo $fuerza->Fase->ViewAttributes() ?>>
<?php echo $fuerza->Fase->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($fuerza->_23_del_punto->Visible) { // #_del_punto ?>
		<td data-name="_23_del_punto"<?php echo $fuerza->_23_del_punto->CellAttributes() ?>>
<span<?php echo $fuerza->_23_del_punto->ViewAttributes() ?>>
<?php echo $fuerza->_23_del_punto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($fuerza->Punto->Visible) { // Punto ?>
		<td data-name="Punto"<?php echo $fuerza->Punto->CellAttributes() ?>>
<span<?php echo $fuerza->Punto->ViewAttributes() ?>>
<?php echo $fuerza->Punto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($fuerza->Profesional_especializado->Visible) { // Profesional_especializado ?>
		<td data-name="Profesional_especializado"<?php echo $fuerza->Profesional_especializado->CellAttributes() ?>>
<span<?php echo $fuerza->Profesional_especializado->ViewAttributes() ?>>
<?php echo $fuerza->Profesional_especializado->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$fuerza_list->ListOptions->Render("body", "right", $fuerza_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($fuerza->CurrentAction <> "gridadd")
		$fuerza_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($fuerza->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($fuerza_list->Recordset)
	$fuerza_list->Recordset->Close();
?>
<?php if ($fuerza->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($fuerza->CurrentAction <> "gridadd" && $fuerza->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($fuerza_list->Pager)) $fuerza_list->Pager = new cPrevNextPager($fuerza_list->StartRec, $fuerza_list->DisplayRecs, $fuerza_list->TotalRecs) ?>
<?php if ($fuerza_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($fuerza_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($fuerza_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $fuerza_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($fuerza_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($fuerza_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $fuerza_list->PageUrl() ?>start=<?php echo $fuerza_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $fuerza_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $fuerza_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $fuerza_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $fuerza_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($fuerza_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($fuerza_list->TotalRecs == 0 && $fuerza->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($fuerza_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($fuerza->Export == "") { ?>
<script type="text/javascript">
ffuerzalistsrch.Init();
ffuerzalist.Init();
</script>
<?php } ?>
<?php
$fuerza_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($fuerza->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$fuerza_list->Page_Terminate();
?>
