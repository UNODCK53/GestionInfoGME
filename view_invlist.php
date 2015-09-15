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

$view_inv_list = NULL; // Initialize page object first

class cview_inv_list extends cview_inv {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_inv';

	// Page object name
	var $PageObjName = 'view_inv_list';

	// Grid form hidden field names
	var $FormName = 'fview_invlist';
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

		// Table object (view_inv)
		if (!isset($GLOBALS["view_inv"]) || get_class($GLOBALS["view_inv"]) == "cview_inv") {
			$GLOBALS["view_inv"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_inv"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view_invadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view_invdelete.php";
		$this->MultiUpdateUrl = "view_invupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_inv', TRUE);

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
			$this->llave->setFormValue($arrKeyFlds[0]);
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
		$this->BuildSearchSql($sWhere, $this->NOM_PE, $Default, FALSE); // NOM_PE
		$this->BuildSearchSql($sWhere, $this->Otro_PE, $Default, FALSE); // Otro_PE
		$this->BuildSearchSql($sWhere, $this->AD1O, $Default, FALSE); // AÑO
		$this->BuildSearchSql($sWhere, $this->FASE, $Default, FALSE); // FASE
		$this->BuildSearchSql($sWhere, $this->FECHA_INV, $Default, FALSE); // FECHA_INV
		$this->BuildSearchSql($sWhere, $this->Modificado, $Default, FALSE); // Modificado

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->llave->AdvancedSearch->Save(); // llave
			$this->USUARIO->AdvancedSearch->Save(); // USUARIO
			$this->NOM_PE->AdvancedSearch->Save(); // NOM_PE
			$this->Otro_PE->AdvancedSearch->Save(); // Otro_PE
			$this->AD1O->AdvancedSearch->Save(); // AÑO
			$this->FASE->AdvancedSearch->Save(); // FASE
			$this->FECHA_INV->AdvancedSearch->Save(); // FECHA_INV
			$this->Modificado->AdvancedSearch->Save(); // Modificado
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
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->AD1O, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FASE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FECHA_INV, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Modificado, $arKeywords, $type);
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
		if ($this->NOM_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->AD1O->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FASE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FECHA_INV->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Modificado->AdvancedSearch->IssetSession())
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
		$this->NOM_PE->AdvancedSearch->UnsetSession();
		$this->Otro_PE->AdvancedSearch->UnsetSession();
		$this->AD1O->AdvancedSearch->UnsetSession();
		$this->FASE->AdvancedSearch->UnsetSession();
		$this->FECHA_INV->AdvancedSearch->UnsetSession();
		$this->Modificado->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->llave->AdvancedSearch->Load();
		$this->USUARIO->AdvancedSearch->Load();
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_PE->AdvancedSearch->Load();
		$this->AD1O->AdvancedSearch->Load();
		$this->FASE->AdvancedSearch->Load();
		$this->FECHA_INV->AdvancedSearch->Load();
		$this->Modificado->AdvancedSearch->Load();
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
			$this->UpdateSort($this->DIA, $bCtrl); // DIA
			$this->UpdateSort($this->MES, $bCtrl); // MES
			$this->UpdateSort($this->OBSERVACION, $bCtrl); // OBSERVACION
			$this->UpdateSort($this->AD1O, $bCtrl); // AÑO
			$this->UpdateSort($this->FASE, $bCtrl); // FASE
			$this->UpdateSort($this->FECHA_INV, $bCtrl); // FECHA_INV
			$this->UpdateSort($this->TIPO_INV, $bCtrl); // TIPO_INV
			$this->UpdateSort($this->NOM_CAPATAZ, $bCtrl); // NOM_CAPATAZ
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
			$this->UpdateSort($this->Modificado, $bCtrl); // Modificado
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
				$this->DIA->setSort("");
				$this->MES->setSort("");
				$this->OBSERVACION->setSort("");
				$this->AD1O->setSort("");
				$this->FASE->setSort("");
				$this->FECHA_INV->setSort("");
				$this->TIPO_INV->setSort("");
				$this->NOM_CAPATAZ->setSort("");
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
				$this->Modificado->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->llave->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview_invlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview_invlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// NOM_PE
		$this->NOM_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_PE"]);
		if ($this->NOM_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_PE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_PE"];

		// Otro_PE
		$this->Otro_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_PE"]);
		if ($this->Otro_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_PE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_PE"];

		// AÑO
		$this->AD1O->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_AD1O"]);
		if ($this->AD1O->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->AD1O->AdvancedSearch->SearchOperator = @$_GET["z_AD1O"];

		// FASE
		$this->FASE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FASE"]);
		if ($this->FASE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FASE->AdvancedSearch->SearchOperator = @$_GET["z_FASE"];

		// FECHA_INV
		$this->FECHA_INV->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FECHA_INV"]);
		if ($this->FECHA_INV->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FECHA_INV->AdvancedSearch->SearchOperator = @$_GET["z_FECHA_INV"];

		// Modificado
		$this->Modificado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Modificado"]);
		if ($this->Modificado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Modificado->AdvancedSearch->SearchOperator = @$_GET["z_Modificado"];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("llave")) <> "")
			$this->llave->CurrentValue = $this->getKey("llave"); // llave
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
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_inv`";
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
			$this->Otro_PE->EditValue = ew_HtmlEncode($this->Otro_PE->AdvancedSearch->SearchValue);
			$this->Otro_PE->PlaceHolder = ew_RemoveHtml($this->Otro_PE->FldCaption());

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

			// OBSERVACION
			$this->OBSERVACION->EditAttrs["class"] = "form-control";
			$this->OBSERVACION->EditCustomAttributes = "";
			$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->AdvancedSearch->SearchValue);
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
			$this->FECHA_INV->EditValue = ew_HtmlEncode($this->FECHA_INV->AdvancedSearch->SearchValue);
			$this->FECHA_INV->PlaceHolder = ew_RemoveHtml($this->FECHA_INV->FldCaption());

			// TIPO_INV
			$this->TIPO_INV->EditAttrs["class"] = "form-control";
			$this->TIPO_INV->EditCustomAttributes = "";

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->EditAttrs["class"] = "form-control";
			$this->NOM_CAPATAZ->EditCustomAttributes = "";
			$this->NOM_CAPATAZ->EditValue = ew_HtmlEncode($this->NOM_CAPATAZ->AdvancedSearch->SearchValue);
			$this->NOM_CAPATAZ->PlaceHolder = ew_RemoveHtml($this->NOM_CAPATAZ->FldCaption());
			// Otro_NOM_CAPAT
			$this->Otro_NOM_CAPAT->EditAttrs["class"] = "form-control";
			$this->Otro_NOM_CAPAT->EditCustomAttributes = "";
			$this->Otro_NOM_CAPAT->EditValue = ew_HtmlEncode($this->Otro_NOM_CAPAT->AdvancedSearch->SearchValue);
			$this->Otro_NOM_CAPAT->PlaceHolder = ew_RemoveHtml($this->Otro_NOM_CAPAT->FldCaption());

			// Otro_CC_CAPAT
			$this->Otro_CC_CAPAT->EditAttrs["class"] = "form-control";
			$this->Otro_CC_CAPAT->EditCustomAttributes = "";
			$this->Otro_CC_CAPAT->EditValue = ew_HtmlEncode($this->Otro_CC_CAPAT->AdvancedSearch->SearchValue);
			$this->Otro_CC_CAPAT->PlaceHolder = ew_RemoveHtml($this->Otro_CC_CAPAT->FldCaption());

			// NOM_LUGAR
			$this->NOM_LUGAR->EditAttrs["class"] = "form-control";
			$this->NOM_LUGAR->EditCustomAttributes = "";
			$this->NOM_LUGAR->EditValue = ew_HtmlEncode($this->NOM_LUGAR->AdvancedSearch->SearchValue);
			$this->NOM_LUGAR->PlaceHolder = ew_RemoveHtml($this->NOM_LUGAR->FldCaption());

			// Cocina
			$this->Cocina->EditAttrs["class"] = "form-control";
			$this->Cocina->EditCustomAttributes = "";
			$this->Cocina->EditValue = ew_HtmlEncode($this->Cocina->AdvancedSearch->SearchValue);
			$this->Cocina->PlaceHolder = ew_RemoveHtml($this->Cocina->FldCaption());

			// 1_Abrelatas
			$this->_1_Abrelatas->EditAttrs["class"] = "form-control";
			$this->_1_Abrelatas->EditCustomAttributes = "";
			$this->_1_Abrelatas->EditValue = ew_HtmlEncode($this->_1_Abrelatas->AdvancedSearch->SearchValue);
			$this->_1_Abrelatas->PlaceHolder = ew_RemoveHtml($this->_1_Abrelatas->FldCaption());

			// 1_Balde
			$this->_1_Balde->EditAttrs["class"] = "form-control";
			$this->_1_Balde->EditCustomAttributes = "";
			$this->_1_Balde->EditValue = ew_HtmlEncode($this->_1_Balde->AdvancedSearch->SearchValue);
			$this->_1_Balde->PlaceHolder = ew_RemoveHtml($this->_1_Balde->FldCaption());

			// 1_Arrocero_50
			$this->_1_Arrocero_50->EditAttrs["class"] = "form-control";
			$this->_1_Arrocero_50->EditCustomAttributes = "";
			$this->_1_Arrocero_50->EditValue = ew_HtmlEncode($this->_1_Arrocero_50->AdvancedSearch->SearchValue);
			$this->_1_Arrocero_50->PlaceHolder = ew_RemoveHtml($this->_1_Arrocero_50->FldCaption());

			// 1_Arrocero_44
			$this->_1_Arrocero_44->EditAttrs["class"] = "form-control";
			$this->_1_Arrocero_44->EditCustomAttributes = "";
			$this->_1_Arrocero_44->EditValue = ew_HtmlEncode($this->_1_Arrocero_44->AdvancedSearch->SearchValue);
			$this->_1_Arrocero_44->PlaceHolder = ew_RemoveHtml($this->_1_Arrocero_44->FldCaption());

			// 1_Chocolatera
			$this->_1_Chocolatera->EditAttrs["class"] = "form-control";
			$this->_1_Chocolatera->EditCustomAttributes = "";
			$this->_1_Chocolatera->EditValue = ew_HtmlEncode($this->_1_Chocolatera->AdvancedSearch->SearchValue);
			$this->_1_Chocolatera->PlaceHolder = ew_RemoveHtml($this->_1_Chocolatera->FldCaption());

			// 1_Colador
			$this->_1_Colador->EditAttrs["class"] = "form-control";
			$this->_1_Colador->EditCustomAttributes = "";
			$this->_1_Colador->EditValue = ew_HtmlEncode($this->_1_Colador->AdvancedSearch->SearchValue);
			$this->_1_Colador->PlaceHolder = ew_RemoveHtml($this->_1_Colador->FldCaption());

			// 1_Cucharon_sopa
			$this->_1_Cucharon_sopa->EditAttrs["class"] = "form-control";
			$this->_1_Cucharon_sopa->EditCustomAttributes = "";
			$this->_1_Cucharon_sopa->EditValue = ew_HtmlEncode($this->_1_Cucharon_sopa->AdvancedSearch->SearchValue);
			$this->_1_Cucharon_sopa->PlaceHolder = ew_RemoveHtml($this->_1_Cucharon_sopa->FldCaption());

			// 1_Cucharon_arroz
			$this->_1_Cucharon_arroz->EditAttrs["class"] = "form-control";
			$this->_1_Cucharon_arroz->EditCustomAttributes = "";
			$this->_1_Cucharon_arroz->EditValue = ew_HtmlEncode($this->_1_Cucharon_arroz->AdvancedSearch->SearchValue);
			$this->_1_Cucharon_arroz->PlaceHolder = ew_RemoveHtml($this->_1_Cucharon_arroz->FldCaption());

			// 1_Cuchillo
			$this->_1_Cuchillo->EditAttrs["class"] = "form-control";
			$this->_1_Cuchillo->EditCustomAttributes = "";
			$this->_1_Cuchillo->EditValue = ew_HtmlEncode($this->_1_Cuchillo->AdvancedSearch->SearchValue);
			$this->_1_Cuchillo->PlaceHolder = ew_RemoveHtml($this->_1_Cuchillo->FldCaption());

			// 1_Embudo
			$this->_1_Embudo->EditAttrs["class"] = "form-control";
			$this->_1_Embudo->EditCustomAttributes = "";
			$this->_1_Embudo->EditValue = ew_HtmlEncode($this->_1_Embudo->AdvancedSearch->SearchValue);
			$this->_1_Embudo->PlaceHolder = ew_RemoveHtml($this->_1_Embudo->FldCaption());

			// 1_Espumera
			$this->_1_Espumera->EditAttrs["class"] = "form-control";
			$this->_1_Espumera->EditCustomAttributes = "";
			$this->_1_Espumera->EditValue = ew_HtmlEncode($this->_1_Espumera->AdvancedSearch->SearchValue);
			$this->_1_Espumera->PlaceHolder = ew_RemoveHtml($this->_1_Espumera->FldCaption());

			// 1_Estufa
			$this->_1_Estufa->EditAttrs["class"] = "form-control";
			$this->_1_Estufa->EditCustomAttributes = "";
			$this->_1_Estufa->EditValue = ew_HtmlEncode($this->_1_Estufa->AdvancedSearch->SearchValue);
			$this->_1_Estufa->PlaceHolder = ew_RemoveHtml($this->_1_Estufa->FldCaption());

			// 1_Cuchara_sopa
			$this->_1_Cuchara_sopa->EditAttrs["class"] = "form-control";
			$this->_1_Cuchara_sopa->EditCustomAttributes = "";
			$this->_1_Cuchara_sopa->EditValue = ew_HtmlEncode($this->_1_Cuchara_sopa->AdvancedSearch->SearchValue);
			$this->_1_Cuchara_sopa->PlaceHolder = ew_RemoveHtml($this->_1_Cuchara_sopa->FldCaption());

			// 1_Recipiente
			$this->_1_Recipiente->EditAttrs["class"] = "form-control";
			$this->_1_Recipiente->EditCustomAttributes = "";
			$this->_1_Recipiente->EditValue = ew_HtmlEncode($this->_1_Recipiente->AdvancedSearch->SearchValue);
			$this->_1_Recipiente->PlaceHolder = ew_RemoveHtml($this->_1_Recipiente->FldCaption());

			// 1_Kit_Repue_estufa
			$this->_1_Kit_Repue_estufa->EditAttrs["class"] = "form-control";
			$this->_1_Kit_Repue_estufa->EditCustomAttributes = "";
			$this->_1_Kit_Repue_estufa->EditValue = ew_HtmlEncode($this->_1_Kit_Repue_estufa->AdvancedSearch->SearchValue);
			$this->_1_Kit_Repue_estufa->PlaceHolder = ew_RemoveHtml($this->_1_Kit_Repue_estufa->FldCaption());

			// 1_Molinillo
			$this->_1_Molinillo->EditAttrs["class"] = "form-control";
			$this->_1_Molinillo->EditCustomAttributes = "";
			$this->_1_Molinillo->EditValue = ew_HtmlEncode($this->_1_Molinillo->AdvancedSearch->SearchValue);
			$this->_1_Molinillo->PlaceHolder = ew_RemoveHtml($this->_1_Molinillo->FldCaption());

			// 1_Olla_36
			$this->_1_Olla_36->EditAttrs["class"] = "form-control";
			$this->_1_Olla_36->EditCustomAttributes = "";
			$this->_1_Olla_36->EditValue = ew_HtmlEncode($this->_1_Olla_36->AdvancedSearch->SearchValue);
			$this->_1_Olla_36->PlaceHolder = ew_RemoveHtml($this->_1_Olla_36->FldCaption());

			// 1_Olla_40
			$this->_1_Olla_40->EditAttrs["class"] = "form-control";
			$this->_1_Olla_40->EditCustomAttributes = "";
			$this->_1_Olla_40->EditValue = ew_HtmlEncode($this->_1_Olla_40->AdvancedSearch->SearchValue);
			$this->_1_Olla_40->PlaceHolder = ew_RemoveHtml($this->_1_Olla_40->FldCaption());

			// 1_Paila_32
			$this->_1_Paila_32->EditAttrs["class"] = "form-control";
			$this->_1_Paila_32->EditCustomAttributes = "";
			$this->_1_Paila_32->EditValue = ew_HtmlEncode($this->_1_Paila_32->AdvancedSearch->SearchValue);
			$this->_1_Paila_32->PlaceHolder = ew_RemoveHtml($this->_1_Paila_32->FldCaption());

			// 1_Paila_36_37
			$this->_1_Paila_36_37->EditAttrs["class"] = "form-control";
			$this->_1_Paila_36_37->EditCustomAttributes = "";
			$this->_1_Paila_36_37->EditValue = ew_HtmlEncode($this->_1_Paila_36_37->AdvancedSearch->SearchValue);
			$this->_1_Paila_36_37->PlaceHolder = ew_RemoveHtml($this->_1_Paila_36_37->FldCaption());

			// Camping
			$this->Camping->EditAttrs["class"] = "form-control";
			$this->Camping->EditCustomAttributes = "";
			$this->Camping->EditValue = ew_HtmlEncode($this->Camping->AdvancedSearch->SearchValue);
			$this->Camping->PlaceHolder = ew_RemoveHtml($this->Camping->FldCaption());

			// 2_Aislante
			$this->_2_Aislante->EditAttrs["class"] = "form-control";
			$this->_2_Aislante->EditCustomAttributes = "";
			$this->_2_Aislante->EditValue = ew_HtmlEncode($this->_2_Aislante->AdvancedSearch->SearchValue);
			$this->_2_Aislante->PlaceHolder = ew_RemoveHtml($this->_2_Aislante->FldCaption());

			// 2_Carpa_hamaca
			$this->_2_Carpa_hamaca->EditAttrs["class"] = "form-control";
			$this->_2_Carpa_hamaca->EditCustomAttributes = "";
			$this->_2_Carpa_hamaca->EditValue = ew_HtmlEncode($this->_2_Carpa_hamaca->AdvancedSearch->SearchValue);
			$this->_2_Carpa_hamaca->PlaceHolder = ew_RemoveHtml($this->_2_Carpa_hamaca->FldCaption());

			// 2_Carpa_rancho
			$this->_2_Carpa_rancho->EditAttrs["class"] = "form-control";
			$this->_2_Carpa_rancho->EditCustomAttributes = "";
			$this->_2_Carpa_rancho->EditValue = ew_HtmlEncode($this->_2_Carpa_rancho->AdvancedSearch->SearchValue);
			$this->_2_Carpa_rancho->PlaceHolder = ew_RemoveHtml($this->_2_Carpa_rancho->FldCaption());

			// 2_Fibra_rollo
			$this->_2_Fibra_rollo->EditAttrs["class"] = "form-control";
			$this->_2_Fibra_rollo->EditCustomAttributes = "";
			$this->_2_Fibra_rollo->EditValue = ew_HtmlEncode($this->_2_Fibra_rollo->AdvancedSearch->SearchValue);
			$this->_2_Fibra_rollo->PlaceHolder = ew_RemoveHtml($this->_2_Fibra_rollo->FldCaption());

			// 2_CAL
			$this->_2_CAL->EditAttrs["class"] = "form-control";
			$this->_2_CAL->EditCustomAttributes = "";
			$this->_2_CAL->EditValue = ew_HtmlEncode($this->_2_CAL->AdvancedSearch->SearchValue);
			$this->_2_CAL->PlaceHolder = ew_RemoveHtml($this->_2_CAL->FldCaption());

			// 2_Linterna
			$this->_2_Linterna->EditAttrs["class"] = "form-control";
			$this->_2_Linterna->EditCustomAttributes = "";
			$this->_2_Linterna->EditValue = ew_HtmlEncode($this->_2_Linterna->AdvancedSearch->SearchValue);
			$this->_2_Linterna->PlaceHolder = ew_RemoveHtml($this->_2_Linterna->FldCaption());

			// 2_Botiquin
			$this->_2_Botiquin->EditAttrs["class"] = "form-control";
			$this->_2_Botiquin->EditCustomAttributes = "";
			$this->_2_Botiquin->EditValue = ew_HtmlEncode($this->_2_Botiquin->AdvancedSearch->SearchValue);
			$this->_2_Botiquin->PlaceHolder = ew_RemoveHtml($this->_2_Botiquin->FldCaption());

			// 2_Mascara_filtro
			$this->_2_Mascara_filtro->EditAttrs["class"] = "form-control";
			$this->_2_Mascara_filtro->EditCustomAttributes = "";
			$this->_2_Mascara_filtro->EditValue = ew_HtmlEncode($this->_2_Mascara_filtro->AdvancedSearch->SearchValue);
			$this->_2_Mascara_filtro->PlaceHolder = ew_RemoveHtml($this->_2_Mascara_filtro->FldCaption());

			// 2_Pimpina
			$this->_2_Pimpina->EditAttrs["class"] = "form-control";
			$this->_2_Pimpina->EditCustomAttributes = "";
			$this->_2_Pimpina->EditValue = ew_HtmlEncode($this->_2_Pimpina->AdvancedSearch->SearchValue);
			$this->_2_Pimpina->PlaceHolder = ew_RemoveHtml($this->_2_Pimpina->FldCaption());

			// 2_Sleeping 
			$this->_2_SleepingA0->EditAttrs["class"] = "form-control";
			$this->_2_SleepingA0->EditCustomAttributes = "";
			$this->_2_SleepingA0->EditValue = ew_HtmlEncode($this->_2_SleepingA0->AdvancedSearch->SearchValue);
			$this->_2_SleepingA0->PlaceHolder = ew_RemoveHtml($this->_2_SleepingA0->FldCaption());

			// 2_Plastico_negro
			$this->_2_Plastico_negro->EditAttrs["class"] = "form-control";
			$this->_2_Plastico_negro->EditCustomAttributes = "";
			$this->_2_Plastico_negro->EditValue = ew_HtmlEncode($this->_2_Plastico_negro->AdvancedSearch->SearchValue);
			$this->_2_Plastico_negro->PlaceHolder = ew_RemoveHtml($this->_2_Plastico_negro->FldCaption());

			// 2_Tula_tropa
			$this->_2_Tula_tropa->EditAttrs["class"] = "form-control";
			$this->_2_Tula_tropa->EditCustomAttributes = "";
			$this->_2_Tula_tropa->EditValue = ew_HtmlEncode($this->_2_Tula_tropa->AdvancedSearch->SearchValue);
			$this->_2_Tula_tropa->PlaceHolder = ew_RemoveHtml($this->_2_Tula_tropa->FldCaption());

			// 2_Camilla
			$this->_2_Camilla->EditAttrs["class"] = "form-control";
			$this->_2_Camilla->EditCustomAttributes = "";
			$this->_2_Camilla->EditValue = ew_HtmlEncode($this->_2_Camilla->AdvancedSearch->SearchValue);
			$this->_2_Camilla->PlaceHolder = ew_RemoveHtml($this->_2_Camilla->FldCaption());

			// Herramientas
			$this->Herramientas->EditAttrs["class"] = "form-control";
			$this->Herramientas->EditCustomAttributes = "";
			$this->Herramientas->EditValue = ew_HtmlEncode($this->Herramientas->AdvancedSearch->SearchValue);
			$this->Herramientas->PlaceHolder = ew_RemoveHtml($this->Herramientas->FldCaption());

			// 3_Abrazadera
			$this->_3_Abrazadera->EditAttrs["class"] = "form-control";
			$this->_3_Abrazadera->EditCustomAttributes = "";
			$this->_3_Abrazadera->EditValue = ew_HtmlEncode($this->_3_Abrazadera->AdvancedSearch->SearchValue);
			$this->_3_Abrazadera->PlaceHolder = ew_RemoveHtml($this->_3_Abrazadera->FldCaption());

			// 3_Aspersora
			$this->_3_Aspersora->EditAttrs["class"] = "form-control";
			$this->_3_Aspersora->EditCustomAttributes = "";
			$this->_3_Aspersora->EditValue = ew_HtmlEncode($this->_3_Aspersora->AdvancedSearch->SearchValue);
			$this->_3_Aspersora->PlaceHolder = ew_RemoveHtml($this->_3_Aspersora->FldCaption());

			// 3_Cabo_hacha
			$this->_3_Cabo_hacha->EditAttrs["class"] = "form-control";
			$this->_3_Cabo_hacha->EditCustomAttributes = "";
			$this->_3_Cabo_hacha->EditValue = ew_HtmlEncode($this->_3_Cabo_hacha->AdvancedSearch->SearchValue);
			$this->_3_Cabo_hacha->PlaceHolder = ew_RemoveHtml($this->_3_Cabo_hacha->FldCaption());

			// 3_Funda_machete
			$this->_3_Funda_machete->EditAttrs["class"] = "form-control";
			$this->_3_Funda_machete->EditCustomAttributes = "";
			$this->_3_Funda_machete->EditValue = ew_HtmlEncode($this->_3_Funda_machete->AdvancedSearch->SearchValue);
			$this->_3_Funda_machete->PlaceHolder = ew_RemoveHtml($this->_3_Funda_machete->FldCaption());

			// 3_Glifosato_4lt
			$this->_3_Glifosato_4lt->EditAttrs["class"] = "form-control";
			$this->_3_Glifosato_4lt->EditCustomAttributes = "";
			$this->_3_Glifosato_4lt->EditValue = ew_HtmlEncode($this->_3_Glifosato_4lt->AdvancedSearch->SearchValue);
			$this->_3_Glifosato_4lt->PlaceHolder = ew_RemoveHtml($this->_3_Glifosato_4lt->FldCaption());

			// 3_Hacha
			$this->_3_Hacha->EditAttrs["class"] = "form-control";
			$this->_3_Hacha->EditCustomAttributes = "";
			$this->_3_Hacha->EditValue = ew_HtmlEncode($this->_3_Hacha->AdvancedSearch->SearchValue);
			$this->_3_Hacha->PlaceHolder = ew_RemoveHtml($this->_3_Hacha->FldCaption());

			// 3_Lima_12_uni
			$this->_3_Lima_12_uni->EditAttrs["class"] = "form-control";
			$this->_3_Lima_12_uni->EditCustomAttributes = "";
			$this->_3_Lima_12_uni->EditValue = ew_HtmlEncode($this->_3_Lima_12_uni->AdvancedSearch->SearchValue);
			$this->_3_Lima_12_uni->PlaceHolder = ew_RemoveHtml($this->_3_Lima_12_uni->FldCaption());

			// 3_Llave_mixta
			$this->_3_Llave_mixta->EditAttrs["class"] = "form-control";
			$this->_3_Llave_mixta->EditCustomAttributes = "";
			$this->_3_Llave_mixta->EditValue = ew_HtmlEncode($this->_3_Llave_mixta->AdvancedSearch->SearchValue);
			$this->_3_Llave_mixta->PlaceHolder = ew_RemoveHtml($this->_3_Llave_mixta->FldCaption());

			// 3_Machete
			$this->_3_Machete->EditAttrs["class"] = "form-control";
			$this->_3_Machete->EditCustomAttributes = "";
			$this->_3_Machete->EditValue = ew_HtmlEncode($this->_3_Machete->AdvancedSearch->SearchValue);
			$this->_3_Machete->PlaceHolder = ew_RemoveHtml($this->_3_Machete->FldCaption());

			// 3_Gafa_traslucida
			$this->_3_Gafa_traslucida->EditAttrs["class"] = "form-control";
			$this->_3_Gafa_traslucida->EditCustomAttributes = "";
			$this->_3_Gafa_traslucida->EditValue = ew_HtmlEncode($this->_3_Gafa_traslucida->AdvancedSearch->SearchValue);
			$this->_3_Gafa_traslucida->PlaceHolder = ew_RemoveHtml($this->_3_Gafa_traslucida->FldCaption());

			// 3_Motosierra
			$this->_3_Motosierra->EditAttrs["class"] = "form-control";
			$this->_3_Motosierra->EditCustomAttributes = "";
			$this->_3_Motosierra->EditValue = ew_HtmlEncode($this->_3_Motosierra->AdvancedSearch->SearchValue);
			$this->_3_Motosierra->PlaceHolder = ew_RemoveHtml($this->_3_Motosierra->FldCaption());

			// 3_Palin
			$this->_3_Palin->EditAttrs["class"] = "form-control";
			$this->_3_Palin->EditCustomAttributes = "";
			$this->_3_Palin->EditValue = ew_HtmlEncode($this->_3_Palin->AdvancedSearch->SearchValue);
			$this->_3_Palin->PlaceHolder = ew_RemoveHtml($this->_3_Palin->FldCaption());

			// 3_Tubo_galvanizado
			$this->_3_Tubo_galvanizado->EditAttrs["class"] = "form-control";
			$this->_3_Tubo_galvanizado->EditCustomAttributes = "";
			$this->_3_Tubo_galvanizado->EditValue = ew_HtmlEncode($this->_3_Tubo_galvanizado->AdvancedSearch->SearchValue);
			$this->_3_Tubo_galvanizado->PlaceHolder = ew_RemoveHtml($this->_3_Tubo_galvanizado->FldCaption());

			// Modificado
			$this->Modificado->EditAttrs["class"] = "form-control";
			$this->Modificado->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Modificado->FldTagValue(1), $this->Modificado->FldTagCaption(1) <> "" ? $this->Modificado->FldTagCaption(1) : $this->Modificado->FldTagValue(1));
			$arwrk[] = array($this->Modificado->FldTagValue(2), $this->Modificado->FldTagCaption(2) <> "" ? $this->Modificado->FldTagCaption(2) : $this->Modificado->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));

			$this->Modificado->EditValue = $arwrk;
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
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_PE->AdvancedSearch->Load();
		$this->AD1O->AdvancedSearch->Load();
		$this->FASE->AdvancedSearch->Load();
		$this->FECHA_INV->AdvancedSearch->Load();
		$this->Modificado->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_view_inv\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_inv',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_invlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view_inv_list)) $view_inv_list = new cview_inv_list();

// Page init
$view_inv_list->Page_Init();

// Page main
$view_inv_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_inv_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view_inv->Export == "") { ?>
<script type="text/javascript">

// Page object
var view_inv_list = new ew_Page("view_inv_list");
view_inv_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view_inv_list.PageID; // For backward compatibility

// Form object
var fview_invlist = new ew_Form("fview_invlist");
fview_invlist.FormKeyCountName = '<?php echo $view_inv_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview_invlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_invlist.ValidateRequired = true;
<?php } else { ?>
fview_invlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_invlist.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invlist.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invlist.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invlist.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invlist.Lists["x_TIPO_INV"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fview_invlistsrch = new ew_Form("fview_invlistsrch");

// Validate function for search
fview_invlistsrch.Validate = function(fobj) {
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
fview_invlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_invlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fview_invlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fview_invlistsrch.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invlistsrch.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invlistsrch.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_invlistsrch.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view_inv->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view_inv->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($view_inv->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<div class="ewToolbar">
<H2> Informe diario</h2>
<p>La siguiente tabla contiene los informes diarios realizados desde la fase II de erradicación 2015 a la fecha</p>

<hr>
<table>
	<tr>
		<td>
			<?php if ($view_inv_list->TotalRecs > 0 && $view_inv_list->ExportOptions->Visible()) { ?>
			<?php $view_inv_list->ExportOptions->Render("body") ?>
			<?php } ?>

		</td>
		<td>
			Si desea exportar la tabla en formato excel haga click en el siguiente icono 
		</td>	
	</tr>	
</table> 
<hr>
</div>
<?php if ($view_inv->Export == "") { ?>

<div>
<br>
<table>
	<tr>
		<td>
			<?php if ($view_inv_list->SearchOptions->Visible()) { ?>
			<?php $view_inv_list->SearchOptions->Render("body") ?>
			<?php } ?>
		</td>
		<td>
			Si desea realizar filtros en la tabla haga click en el siguiente icono e ingrese el dato en la columna correspondiente
		</td>	
	</tr>
</table>
<BR>
</div>
<hr>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($view_inv_list->TotalRecs <= 0)
			$view_inv_list->TotalRecs = $view_inv->SelectRecordCount();
	} else {
		if (!$view_inv_list->Recordset && ($view_inv_list->Recordset = $view_inv_list->LoadRecordset()))
			$view_inv_list->TotalRecs = $view_inv_list->Recordset->RecordCount();
	}
	$view_inv_list->StartRec = 1;
	if ($view_inv_list->DisplayRecs <= 0 || ($view_inv->Export <> "" && $view_inv->ExportAll)) // Display all records
		$view_inv_list->DisplayRecs = $view_inv_list->TotalRecs;
	if (!($view_inv->Export <> "" && $view_inv->ExportAll))
		$view_inv_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view_inv_list->Recordset = $view_inv_list->LoadRecordset($view_inv_list->StartRec-1, $view_inv_list->DisplayRecs);

	// Set no record found message
	if ($view_inv->CurrentAction == "" && $view_inv_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_inv_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_inv_list->SearchWhere == "0=101")
			$view_inv_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_inv_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view_inv_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view_inv->Export == "" && $view_inv->CurrentAction == "") { ?>
<form name="fview_invlistsrch" id="fview_invlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view_inv_list->SearchWhere <> "") ? " " : " "; ?>
<div id="fview_invlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_inv">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$view_inv_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$view_inv->RowType = EW_ROWTYPE_SEARCH;

// Render row
$view_inv->ResetAttrs();
$view_inv_list->RenderRow();
?>
<br>
<table>
	<tr>
		<td>
			<label for="x_USUARIO" class="ewSearchCaption ewLabel">Apoyo zonal</label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_USUARIO" id="z_USUARIO" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_USUARIO" id="x_USUARIO" name="x_USUARIO"<?php echo $view_inv->USUARIO->EditAttributes() ?>>
				<?php
				if (is_array($view_inv->USUARIO->EditValue)) {
					$arwrk = $view_inv->USUARIO->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_inv->USUARIO->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_invlistsrch.Lists["x_USUARIO"].Options = <?php echo (is_array($view_inv->USUARIO->EditValue)) ? ew_ArrayToJson($view_inv->USUARIO->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_NOM_PE" class="ewSearchCaption ewLabel">Punto</label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_PE" id="z_NOM_PE" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_NOM_PE" id="x_NOM_PE" name="x_NOM_PE"<?php echo $view_inv->NOM_PE->EditAttributes() ?>>
				<?php
				if (is_array($view_inv->NOM_PE->EditValue)) {
					$arwrk = $view_inv->NOM_PE->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_inv->NOM_PE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_invlistsrch.Lists["x_NOM_PE"].Options = <?php echo (is_array($view_inv->NOM_PE->EditValue)) ? ew_ArrayToJson($view_inv->NOM_PE->EditValue, 1) : "[]" ?>;
				</script>
				<script type="text/javascript">
				fview_invlistsrch.Lists["x_USUARIO"].Options = <?php echo (is_array($view_inv->USUARIO->EditValue)) ? ew_ArrayToJson($view_inv->USUARIO->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_AD1O" class="ewSearchCaption ewLabel">AÑO</label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_AD1O" id="z_AD1O" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_AD1O" id="x_AD1O" name="x_AD1O"<?php echo $view_inv->AD1O->EditAttributes() ?>>
				<?php
				if (is_array($view_inv->AD1O->EditValue)) {
					$arwrk = $view_inv->AD1O->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_inv->AD1O->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_invlistsrch.Lists["x_AD1O"].Options = <?php echo (is_array($view_inv->AD1O->EditValue)) ? ew_ArrayToJson($view_inv->AD1O->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_FASE" class="ewSearchCaption ewLabel"><?php echo $view_inv->FASE->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_FASE" id="z_FASE" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_FASE" id="x_FASE" name="x_FASE"<?php echo $view_inv->FASE->EditAttributes() ?>>
				<?php
				if (is_array($view_inv->FASE->EditValue)) {
					$arwrk = $view_inv->FASE->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_inv->FASE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_invlistsrch.Lists["x_FASE"].Options = <?php echo (is_array($view_inv->FASE->EditValue)) ? ew_ArrayToJson($view_inv->FASE->EditValue, 1) : "[]" ?>;
				</script>
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
<?php $view_inv_list->ShowPageHeader(); ?>
<?php
$view_inv_list->ShowMessage();
?>
<?php if ($view_inv_list->TotalRecs > 0 || $view_inv->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($view_inv->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($view_inv->CurrentAction <> "gridadd" && $view_inv->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_inv_list->Pager)) $view_inv_list->Pager = new cPrevNextPager($view_inv_list->StartRec, $view_inv_list->DisplayRecs, $view_inv_list->TotalRecs) ?>
<?php if ($view_inv_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_inv_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_inv_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_inv_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_inv_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_inv_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_inv_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_inv_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_inv_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_inv_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_inv_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview_invlist" id="fview_invlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_inv_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_inv_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_inv">
<div id="gmp_view_inv" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view_inv_list->TotalRecs > 0) { ?>
<table id="tbl_view_invlist" class="table ewTable">
<?php echo $view_inv->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_inv->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view_inv_list->RenderListOptions();

// Render list options (header, left)
$view_inv_list->ListOptions->Render("header", "left");
?>
<?php if ($view_inv->llave->Visible) { // llave ?>
	<?php if ($view_inv->SortUrl($view_inv->llave) == "") { ?>
		<th data-name="llave"><div id="elh_view_inv_llave" class="view_inv_llave"><div class="ewTableHeaderCaption"><?php echo $view_inv->llave->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="llave"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->llave) ?>',2);"><div id="elh_view_inv_llave" class="view_inv_llave">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->llave->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->llave->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->llave->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->F_Sincron->Visible) { // F_Sincron ?>
	<?php if ($view_inv->SortUrl($view_inv->F_Sincron) == "") { ?>
		<th data-name="F_Sincron"><div id="elh_view_inv_F_Sincron" class="view_inv_F_Sincron"><div class="ewTableHeaderCaption"><?php echo $view_inv->F_Sincron->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F_Sincron"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->F_Sincron) ?>',2);"><div id="elh_view_inv_F_Sincron" class="view_inv_F_Sincron">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->F_Sincron->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->F_Sincron->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->F_Sincron->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->USUARIO->Visible) { // USUARIO ?>
	<?php if ($view_inv->SortUrl($view_inv->USUARIO) == "") { ?>
		<th data-name="USUARIO"><div id="elh_view_inv_USUARIO" class="view_inv_USUARIO"><div class="ewTableHeaderCaption"><?php echo $view_inv->USUARIO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="USUARIO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->USUARIO) ?>',2);"><div id="elh_view_inv_USUARIO" class="view_inv_USUARIO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->USUARIO->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->USUARIO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->USUARIO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->Cargo_gme->Visible) { // Cargo_gme ?>
	<?php if ($view_inv->SortUrl($view_inv->Cargo_gme) == "") { ?>
		<th data-name="Cargo_gme"><div id="elh_view_inv_Cargo_gme" class="view_inv_Cargo_gme"><div class="ewTableHeaderCaption"><?php echo $view_inv->Cargo_gme->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_gme"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Cargo_gme) ?>',2);"><div id="elh_view_inv_Cargo_gme" class="view_inv_Cargo_gme">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Cargo_gme->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Cargo_gme->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Cargo_gme->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->NOM_PE->Visible) { // NOM_PE ?>
	<?php if ($view_inv->SortUrl($view_inv->NOM_PE) == "") { ?>
		<th data-name="NOM_PE"><div id="elh_view_inv_NOM_PE" class="view_inv_NOM_PE"><div class="ewTableHeaderCaption"><?php echo $view_inv->NOM_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->NOM_PE) ?>',2);"><div id="elh_view_inv_NOM_PE" class="view_inv_NOM_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->NOM_PE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->NOM_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->NOM_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->Otro_PE->Visible) { // Otro_PE ?>
	<?php if ($view_inv->SortUrl($view_inv->Otro_PE) == "") { ?>
		<th data-name="Otro_PE"><div id="elh_view_inv_Otro_PE" class="view_inv_Otro_PE"><div class="ewTableHeaderCaption"><?php echo $view_inv->Otro_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Otro_PE) ?>',2);"><div id="elh_view_inv_Otro_PE" class="view_inv_Otro_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Otro_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Otro_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Otro_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->DIA->Visible) { // DIA ?>
	<?php if ($view_inv->SortUrl($view_inv->DIA) == "") { ?>
		<th data-name="DIA"><div id="elh_view_inv_DIA" class="view_inv_DIA"><div class="ewTableHeaderCaption"><?php echo $view_inv->DIA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DIA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->DIA) ?>',2);"><div id="elh_view_inv_DIA" class="view_inv_DIA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->DIA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->DIA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->DIA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->MES->Visible) { // MES ?>
	<?php if ($view_inv->SortUrl($view_inv->MES) == "") { ?>
		<th data-name="MES"><div id="elh_view_inv_MES" class="view_inv_MES"><div class="ewTableHeaderCaption"><?php echo $view_inv->MES->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MES"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->MES) ?>',2);"><div id="elh_view_inv_MES" class="view_inv_MES">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->MES->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->MES->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->MES->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->OBSERVACION->Visible) { // OBSERVACION ?>
	<?php if ($view_inv->SortUrl($view_inv->OBSERVACION) == "") { ?>
		<th data-name="OBSERVACION"><div id="elh_view_inv_OBSERVACION" class="view_inv_OBSERVACION"><div class="ewTableHeaderCaption"><?php echo $view_inv->OBSERVACION->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBSERVACION"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->OBSERVACION) ?>',2);"><div id="elh_view_inv_OBSERVACION" class="view_inv_OBSERVACION">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->OBSERVACION->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->OBSERVACION->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->OBSERVACION->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->AD1O->Visible) { // AÑO ?>
	<?php if ($view_inv->SortUrl($view_inv->AD1O) == "") { ?>
		<th data-name="AD1O"><div id="elh_view_inv_AD1O" class="view_inv_AD1O"><div class="ewTableHeaderCaption"><?php echo $view_inv->AD1O->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="AD1O"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->AD1O) ?>',2);"><div id="elh_view_inv_AD1O" class="view_inv_AD1O">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->AD1O->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->AD1O->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->AD1O->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->FASE->Visible) { // FASE ?>
	<?php if ($view_inv->SortUrl($view_inv->FASE) == "") { ?>
		<th data-name="FASE"><div id="elh_view_inv_FASE" class="view_inv_FASE"><div class="ewTableHeaderCaption"><?php echo $view_inv->FASE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FASE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->FASE) ?>',2);"><div id="elh_view_inv_FASE" class="view_inv_FASE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->FASE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->FASE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->FASE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->FECHA_INV->Visible) { // FECHA_INV ?>
	<?php if ($view_inv->SortUrl($view_inv->FECHA_INV) == "") { ?>
		<th data-name="FECHA_INV"><div id="elh_view_inv_FECHA_INV" class="view_inv_FECHA_INV"><div class="ewTableHeaderCaption"><?php echo $view_inv->FECHA_INV->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FECHA_INV"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->FECHA_INV) ?>',2);"><div id="elh_view_inv_FECHA_INV" class="view_inv_FECHA_INV">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->FECHA_INV->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->FECHA_INV->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->FECHA_INV->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->TIPO_INV->Visible) { // TIPO_INV ?>
	<?php if ($view_inv->SortUrl($view_inv->TIPO_INV) == "") { ?>
		<th data-name="TIPO_INV"><div id="elh_view_inv_TIPO_INV" class="view_inv_TIPO_INV"><div class="ewTableHeaderCaption"><?php echo $view_inv->TIPO_INV->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TIPO_INV"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->TIPO_INV) ?>',2);"><div id="elh_view_inv_TIPO_INV" class="view_inv_TIPO_INV">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->TIPO_INV->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->TIPO_INV->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->TIPO_INV->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
	<?php if ($view_inv->SortUrl($view_inv->NOM_CAPATAZ) == "") { ?>
		<th data-name="NOM_CAPATAZ"><div id="elh_view_inv_NOM_CAPATAZ" class="view_inv_NOM_CAPATAZ"><div class="ewTableHeaderCaption"><?php echo $view_inv->NOM_CAPATAZ->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_CAPATAZ"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->NOM_CAPATAZ) ?>',2);"><div id="elh_view_inv_NOM_CAPATAZ" class="view_inv_NOM_CAPATAZ">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->NOM_CAPATAZ->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->NOM_CAPATAZ->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->NOM_CAPATAZ->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>	
<?php if ($view_inv->Otro_NOM_CAPAT->Visible) { // Otro_NOM_CAPAT ?>
	<?php if ($view_inv->SortUrl($view_inv->Otro_NOM_CAPAT) == "") { ?>
		<th data-name="Otro_NOM_CAPAT"><div id="elh_view_inv_Otro_NOM_CAPAT" class="view_inv_Otro_NOM_CAPAT"><div class="ewTableHeaderCaption"><?php echo $view_inv->Otro_NOM_CAPAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_NOM_CAPAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Otro_NOM_CAPAT) ?>',2);"><div id="elh_view_inv_Otro_NOM_CAPAT" class="view_inv_Otro_NOM_CAPAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Otro_NOM_CAPAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Otro_NOM_CAPAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Otro_NOM_CAPAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->Otro_CC_CAPAT->Visible) { // Otro_CC_CAPAT ?>
	<?php if ($view_inv->SortUrl($view_inv->Otro_CC_CAPAT) == "") { ?>
		<th data-name="Otro_CC_CAPAT"><div id="elh_view_inv_Otro_CC_CAPAT" class="view_inv_Otro_CC_CAPAT"><div class="ewTableHeaderCaption"><?php echo $view_inv->Otro_CC_CAPAT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_CAPAT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Otro_CC_CAPAT) ?>',2);"><div id="elh_view_inv_Otro_CC_CAPAT" class="view_inv_Otro_CC_CAPAT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Otro_CC_CAPAT->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Otro_CC_CAPAT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Otro_CC_CAPAT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->NOM_LUGAR->Visible) { // NOM_LUGAR ?>
	<?php if ($view_inv->SortUrl($view_inv->NOM_LUGAR) == "") { ?>
		<th data-name="NOM_LUGAR"><div id="elh_view_inv_NOM_LUGAR" class="view_inv_NOM_LUGAR"><div class="ewTableHeaderCaption"><?php echo $view_inv->NOM_LUGAR->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_LUGAR"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->NOM_LUGAR) ?>',2);"><div id="elh_view_inv_NOM_LUGAR" class="view_inv_NOM_LUGAR">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->NOM_LUGAR->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->NOM_LUGAR->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->NOM_LUGAR->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->Cocina->Visible) { // Cocina ?>
	<?php if ($view_inv->SortUrl($view_inv->Cocina) == "") { ?>
		<th data-name="Cocina"><div id="elh_view_inv_Cocina" class="view_inv_Cocina"><div class="ewTableHeaderCaption"><?php echo $view_inv->Cocina->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cocina"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Cocina) ?>',2);"><div id="elh_view_inv_Cocina" class="view_inv_Cocina">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Cocina->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Cocina->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Cocina->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Abrelatas->Visible) { // 1_Abrelatas ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Abrelatas) == "") { ?>
		<th data-name="_1_Abrelatas"><div id="elh_view_inv__1_Abrelatas" class="view_inv__1_Abrelatas"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Abrelatas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Abrelatas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Abrelatas) ?>',2);"><div id="elh_view_inv__1_Abrelatas" class="view_inv__1_Abrelatas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Abrelatas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Abrelatas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Abrelatas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Balde->Visible) { // 1_Balde ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Balde) == "") { ?>
		<th data-name="_1_Balde"><div id="elh_view_inv__1_Balde" class="view_inv__1_Balde"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Balde->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Balde"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Balde) ?>',2);"><div id="elh_view_inv__1_Balde" class="view_inv__1_Balde">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Balde->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Balde->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Balde->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Arrocero_50->Visible) { // 1_Arrocero_50 ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Arrocero_50) == "") { ?>
		<th data-name="_1_Arrocero_50"><div id="elh_view_inv__1_Arrocero_50" class="view_inv__1_Arrocero_50"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Arrocero_50->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Arrocero_50"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Arrocero_50) ?>',2);"><div id="elh_view_inv__1_Arrocero_50" class="view_inv__1_Arrocero_50">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Arrocero_50->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Arrocero_50->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Arrocero_50->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Arrocero_44->Visible) { // 1_Arrocero_44 ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Arrocero_44) == "") { ?>
		<th data-name="_1_Arrocero_44"><div id="elh_view_inv__1_Arrocero_44" class="view_inv__1_Arrocero_44"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Arrocero_44->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Arrocero_44"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Arrocero_44) ?>',2);"><div id="elh_view_inv__1_Arrocero_44" class="view_inv__1_Arrocero_44">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Arrocero_44->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Arrocero_44->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Arrocero_44->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Chocolatera->Visible) { // 1_Chocolatera ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Chocolatera) == "") { ?>
		<th data-name="_1_Chocolatera"><div id="elh_view_inv__1_Chocolatera" class="view_inv__1_Chocolatera"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Chocolatera->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Chocolatera"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Chocolatera) ?>',2);"><div id="elh_view_inv__1_Chocolatera" class="view_inv__1_Chocolatera">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Chocolatera->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Chocolatera->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Chocolatera->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Colador->Visible) { // 1_Colador ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Colador) == "") { ?>
		<th data-name="_1_Colador"><div id="elh_view_inv__1_Colador" class="view_inv__1_Colador"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Colador->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Colador"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Colador) ?>',2);"><div id="elh_view_inv__1_Colador" class="view_inv__1_Colador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Colador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Colador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Colador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Cucharon_sopa->Visible) { // 1_Cucharon_sopa ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Cucharon_sopa) == "") { ?>
		<th data-name="_1_Cucharon_sopa"><div id="elh_view_inv__1_Cucharon_sopa" class="view_inv__1_Cucharon_sopa"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cucharon_sopa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Cucharon_sopa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Cucharon_sopa) ?>',2);"><div id="elh_view_inv__1_Cucharon_sopa" class="view_inv__1_Cucharon_sopa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cucharon_sopa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Cucharon_sopa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Cucharon_sopa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Cucharon_arroz->Visible) { // 1_Cucharon_arroz ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Cucharon_arroz) == "") { ?>
		<th data-name="_1_Cucharon_arroz"><div id="elh_view_inv__1_Cucharon_arroz" class="view_inv__1_Cucharon_arroz"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cucharon_arroz->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Cucharon_arroz"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Cucharon_arroz) ?>',2);"><div id="elh_view_inv__1_Cucharon_arroz" class="view_inv__1_Cucharon_arroz">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cucharon_arroz->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Cucharon_arroz->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Cucharon_arroz->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Cuchillo->Visible) { // 1_Cuchillo ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Cuchillo) == "") { ?>
		<th data-name="_1_Cuchillo"><div id="elh_view_inv__1_Cuchillo" class="view_inv__1_Cuchillo"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cuchillo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Cuchillo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Cuchillo) ?>',2);"><div id="elh_view_inv__1_Cuchillo" class="view_inv__1_Cuchillo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cuchillo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Cuchillo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Cuchillo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Embudo->Visible) { // 1_Embudo ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Embudo) == "") { ?>
		<th data-name="_1_Embudo"><div id="elh_view_inv__1_Embudo" class="view_inv__1_Embudo"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Embudo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Embudo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Embudo) ?>',2);"><div id="elh_view_inv__1_Embudo" class="view_inv__1_Embudo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Embudo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Embudo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Embudo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Espumera->Visible) { // 1_Espumera ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Espumera) == "") { ?>
		<th data-name="_1_Espumera"><div id="elh_view_inv__1_Espumera" class="view_inv__1_Espumera"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Espumera->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Espumera"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Espumera) ?>',2);"><div id="elh_view_inv__1_Espumera" class="view_inv__1_Espumera">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Espumera->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Espumera->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Espumera->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Estufa->Visible) { // 1_Estufa ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Estufa) == "") { ?>
		<th data-name="_1_Estufa"><div id="elh_view_inv__1_Estufa" class="view_inv__1_Estufa"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Estufa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Estufa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Estufa) ?>',2);"><div id="elh_view_inv__1_Estufa" class="view_inv__1_Estufa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Estufa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Estufa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Estufa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Cuchara_sopa->Visible) { // 1_Cuchara_sopa ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Cuchara_sopa) == "") { ?>
		<th data-name="_1_Cuchara_sopa"><div id="elh_view_inv__1_Cuchara_sopa" class="view_inv__1_Cuchara_sopa"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cuchara_sopa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Cuchara_sopa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Cuchara_sopa) ?>',2);"><div id="elh_view_inv__1_Cuchara_sopa" class="view_inv__1_Cuchara_sopa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Cuchara_sopa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Cuchara_sopa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Cuchara_sopa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Recipiente->Visible) { // 1_Recipiente ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Recipiente) == "") { ?>
		<th data-name="_1_Recipiente"><div id="elh_view_inv__1_Recipiente" class="view_inv__1_Recipiente"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Recipiente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Recipiente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Recipiente) ?>',2);"><div id="elh_view_inv__1_Recipiente" class="view_inv__1_Recipiente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Recipiente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Recipiente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Recipiente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Kit_Repue_estufa->Visible) { // 1_Kit_Repue_estufa ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Kit_Repue_estufa) == "") { ?>
		<th data-name="_1_Kit_Repue_estufa"><div id="elh_view_inv__1_Kit_Repue_estufa" class="view_inv__1_Kit_Repue_estufa"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Kit_Repue_estufa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Kit_Repue_estufa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Kit_Repue_estufa) ?>',2);"><div id="elh_view_inv__1_Kit_Repue_estufa" class="view_inv__1_Kit_Repue_estufa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Kit_Repue_estufa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Kit_Repue_estufa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Kit_Repue_estufa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Molinillo->Visible) { // 1_Molinillo ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Molinillo) == "") { ?>
		<th data-name="_1_Molinillo"><div id="elh_view_inv__1_Molinillo" class="view_inv__1_Molinillo"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Molinillo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Molinillo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Molinillo) ?>',2);"><div id="elh_view_inv__1_Molinillo" class="view_inv__1_Molinillo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Molinillo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Molinillo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Molinillo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Olla_36->Visible) { // 1_Olla_36 ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Olla_36) == "") { ?>
		<th data-name="_1_Olla_36"><div id="elh_view_inv__1_Olla_36" class="view_inv__1_Olla_36"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Olla_36->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Olla_36"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Olla_36) ?>',2);"><div id="elh_view_inv__1_Olla_36" class="view_inv__1_Olla_36">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Olla_36->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Olla_36->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Olla_36->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Olla_40->Visible) { // 1_Olla_40 ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Olla_40) == "") { ?>
		<th data-name="_1_Olla_40"><div id="elh_view_inv__1_Olla_40" class="view_inv__1_Olla_40"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Olla_40->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Olla_40"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Olla_40) ?>',2);"><div id="elh_view_inv__1_Olla_40" class="view_inv__1_Olla_40">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Olla_40->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Olla_40->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Olla_40->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Paila_32->Visible) { // 1_Paila_32 ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Paila_32) == "") { ?>
		<th data-name="_1_Paila_32"><div id="elh_view_inv__1_Paila_32" class="view_inv__1_Paila_32"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Paila_32->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Paila_32"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Paila_32) ?>',2);"><div id="elh_view_inv__1_Paila_32" class="view_inv__1_Paila_32">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Paila_32->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Paila_32->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Paila_32->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_1_Paila_36_37->Visible) { // 1_Paila_36_37 ?>
	<?php if ($view_inv->SortUrl($view_inv->_1_Paila_36_37) == "") { ?>
		<th data-name="_1_Paila_36_37"><div id="elh_view_inv__1_Paila_36_37" class="view_inv__1_Paila_36_37"><div class="ewTableHeaderCaption"><?php echo $view_inv->_1_Paila_36_37->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Paila_36_37"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_1_Paila_36_37) ?>',2);"><div id="elh_view_inv__1_Paila_36_37" class="view_inv__1_Paila_36_37">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_1_Paila_36_37->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_1_Paila_36_37->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_1_Paila_36_37->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->Camping->Visible) { // Camping ?>
	<?php if ($view_inv->SortUrl($view_inv->Camping) == "") { ?>
		<th data-name="Camping"><div id="elh_view_inv_Camping" class="view_inv_Camping"><div class="ewTableHeaderCaption"><?php echo $view_inv->Camping->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Camping"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Camping) ?>',2);"><div id="elh_view_inv_Camping" class="view_inv_Camping">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Camping->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Camping->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Camping->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Aislante->Visible) { // 2_Aislante ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Aislante) == "") { ?>
		<th data-name="_2_Aislante"><div id="elh_view_inv__2_Aislante" class="view_inv__2_Aislante"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Aislante->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Aislante"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Aislante) ?>',2);"><div id="elh_view_inv__2_Aislante" class="view_inv__2_Aislante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Aislante->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Aislante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Aislante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Carpa_hamaca->Visible) { // 2_Carpa_hamaca ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Carpa_hamaca) == "") { ?>
		<th data-name="_2_Carpa_hamaca"><div id="elh_view_inv__2_Carpa_hamaca" class="view_inv__2_Carpa_hamaca"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Carpa_hamaca->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Carpa_hamaca"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Carpa_hamaca) ?>',2);"><div id="elh_view_inv__2_Carpa_hamaca" class="view_inv__2_Carpa_hamaca">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Carpa_hamaca->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Carpa_hamaca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Carpa_hamaca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Carpa_rancho->Visible) { // 2_Carpa_rancho ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Carpa_rancho) == "") { ?>
		<th data-name="_2_Carpa_rancho"><div id="elh_view_inv__2_Carpa_rancho" class="view_inv__2_Carpa_rancho"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Carpa_rancho->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Carpa_rancho"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Carpa_rancho) ?>',2);"><div id="elh_view_inv__2_Carpa_rancho" class="view_inv__2_Carpa_rancho">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Carpa_rancho->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Carpa_rancho->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Carpa_rancho->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Fibra_rollo->Visible) { // 2_Fibra_rollo ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Fibra_rollo) == "") { ?>
		<th data-name="_2_Fibra_rollo"><div id="elh_view_inv__2_Fibra_rollo" class="view_inv__2_Fibra_rollo"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Fibra_rollo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Fibra_rollo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Fibra_rollo) ?>',2);"><div id="elh_view_inv__2_Fibra_rollo" class="view_inv__2_Fibra_rollo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Fibra_rollo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Fibra_rollo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Fibra_rollo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_CAL->Visible) { // 2_CAL ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_CAL) == "") { ?>
		<th data-name="_2_CAL"><div id="elh_view_inv__2_CAL" class="view_inv__2_CAL"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_CAL->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_CAL"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_CAL) ?>',2);"><div id="elh_view_inv__2_CAL" class="view_inv__2_CAL">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_CAL->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_CAL->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_CAL->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Linterna->Visible) { // 2_Linterna ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Linterna) == "") { ?>
		<th data-name="_2_Linterna"><div id="elh_view_inv__2_Linterna" class="view_inv__2_Linterna"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Linterna->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Linterna"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Linterna) ?>',2);"><div id="elh_view_inv__2_Linterna" class="view_inv__2_Linterna">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Linterna->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Linterna->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Linterna->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Botiquin->Visible) { // 2_Botiquin ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Botiquin) == "") { ?>
		<th data-name="_2_Botiquin"><div id="elh_view_inv__2_Botiquin" class="view_inv__2_Botiquin"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Botiquin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Botiquin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Botiquin) ?>',2);"><div id="elh_view_inv__2_Botiquin" class="view_inv__2_Botiquin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Botiquin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Botiquin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Botiquin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Mascara_filtro->Visible) { // 2_Mascara_filtro ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Mascara_filtro) == "") { ?>
		<th data-name="_2_Mascara_filtro"><div id="elh_view_inv__2_Mascara_filtro" class="view_inv__2_Mascara_filtro"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Mascara_filtro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Mascara_filtro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Mascara_filtro) ?>',2);"><div id="elh_view_inv__2_Mascara_filtro" class="view_inv__2_Mascara_filtro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Mascara_filtro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Mascara_filtro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Mascara_filtro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Pimpina->Visible) { // 2_Pimpina ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Pimpina) == "") { ?>
		<th data-name="_2_Pimpina"><div id="elh_view_inv__2_Pimpina" class="view_inv__2_Pimpina"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Pimpina->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Pimpina"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Pimpina) ?>',2);"><div id="elh_view_inv__2_Pimpina" class="view_inv__2_Pimpina">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Pimpina->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Pimpina->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Pimpina->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_SleepingA0->Visible) { // 2_Sleeping  ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_SleepingA0) == "") { ?>
		<th data-name="_2_SleepingA0"><div id="elh_view_inv__2_SleepingA0" class="view_inv__2_SleepingA0"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_SleepingA0->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_SleepingA0"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_SleepingA0) ?>',2);"><div id="elh_view_inv__2_SleepingA0" class="view_inv__2_SleepingA0">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_SleepingA0->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_SleepingA0->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_SleepingA0->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Plastico_negro->Visible) { // 2_Plastico_negro ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Plastico_negro) == "") { ?>
		<th data-name="_2_Plastico_negro"><div id="elh_view_inv__2_Plastico_negro" class="view_inv__2_Plastico_negro"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Plastico_negro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Plastico_negro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Plastico_negro) ?>',2);"><div id="elh_view_inv__2_Plastico_negro" class="view_inv__2_Plastico_negro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Plastico_negro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Plastico_negro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Plastico_negro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Tula_tropa->Visible) { // 2_Tula_tropa ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Tula_tropa) == "") { ?>
		<th data-name="_2_Tula_tropa"><div id="elh_view_inv__2_Tula_tropa" class="view_inv__2_Tula_tropa"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Tula_tropa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Tula_tropa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Tula_tropa) ?>',2);"><div id="elh_view_inv__2_Tula_tropa" class="view_inv__2_Tula_tropa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Tula_tropa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Tula_tropa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Tula_tropa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_2_Camilla->Visible) { // 2_Camilla ?>
	<?php if ($view_inv->SortUrl($view_inv->_2_Camilla) == "") { ?>
		<th data-name="_2_Camilla"><div id="elh_view_inv__2_Camilla" class="view_inv__2_Camilla"><div class="ewTableHeaderCaption"><?php echo $view_inv->_2_Camilla->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Camilla"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_2_Camilla) ?>',2);"><div id="elh_view_inv__2_Camilla" class="view_inv__2_Camilla">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_2_Camilla->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_2_Camilla->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_2_Camilla->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->Herramientas->Visible) { // Herramientas ?>
	<?php if ($view_inv->SortUrl($view_inv->Herramientas) == "") { ?>
		<th data-name="Herramientas"><div id="elh_view_inv_Herramientas" class="view_inv_Herramientas"><div class="ewTableHeaderCaption"><?php echo $view_inv->Herramientas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Herramientas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Herramientas) ?>',2);"><div id="elh_view_inv_Herramientas" class="view_inv_Herramientas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Herramientas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Herramientas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Herramientas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Abrazadera->Visible) { // 3_Abrazadera ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Abrazadera) == "") { ?>
		<th data-name="_3_Abrazadera"><div id="elh_view_inv__3_Abrazadera" class="view_inv__3_Abrazadera"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Abrazadera->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Abrazadera"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Abrazadera) ?>',2);"><div id="elh_view_inv__3_Abrazadera" class="view_inv__3_Abrazadera">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Abrazadera->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Abrazadera->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Abrazadera->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Aspersora->Visible) { // 3_Aspersora ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Aspersora) == "") { ?>
		<th data-name="_3_Aspersora"><div id="elh_view_inv__3_Aspersora" class="view_inv__3_Aspersora"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Aspersora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Aspersora"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Aspersora) ?>',2);"><div id="elh_view_inv__3_Aspersora" class="view_inv__3_Aspersora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Aspersora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Aspersora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Aspersora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Cabo_hacha->Visible) { // 3_Cabo_hacha ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Cabo_hacha) == "") { ?>
		<th data-name="_3_Cabo_hacha"><div id="elh_view_inv__3_Cabo_hacha" class="view_inv__3_Cabo_hacha"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Cabo_hacha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Cabo_hacha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Cabo_hacha) ?>',2);"><div id="elh_view_inv__3_Cabo_hacha" class="view_inv__3_Cabo_hacha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Cabo_hacha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Cabo_hacha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Cabo_hacha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Funda_machete->Visible) { // 3_Funda_machete ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Funda_machete) == "") { ?>
		<th data-name="_3_Funda_machete"><div id="elh_view_inv__3_Funda_machete" class="view_inv__3_Funda_machete"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Funda_machete->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Funda_machete"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Funda_machete) ?>',2);"><div id="elh_view_inv__3_Funda_machete" class="view_inv__3_Funda_machete">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Funda_machete->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Funda_machete->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Funda_machete->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Glifosato_4lt->Visible) { // 3_Glifosato_4lt ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Glifosato_4lt) == "") { ?>
		<th data-name="_3_Glifosato_4lt"><div id="elh_view_inv__3_Glifosato_4lt" class="view_inv__3_Glifosato_4lt"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Glifosato_4lt->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Glifosato_4lt"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Glifosato_4lt) ?>',2);"><div id="elh_view_inv__3_Glifosato_4lt" class="view_inv__3_Glifosato_4lt">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Glifosato_4lt->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Glifosato_4lt->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Glifosato_4lt->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Hacha->Visible) { // 3_Hacha ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Hacha) == "") { ?>
		<th data-name="_3_Hacha"><div id="elh_view_inv__3_Hacha" class="view_inv__3_Hacha"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Hacha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Hacha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Hacha) ?>',2);"><div id="elh_view_inv__3_Hacha" class="view_inv__3_Hacha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Hacha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Hacha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Hacha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Lima_12_uni->Visible) { // 3_Lima_12_uni ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Lima_12_uni) == "") { ?>
		<th data-name="_3_Lima_12_uni"><div id="elh_view_inv__3_Lima_12_uni" class="view_inv__3_Lima_12_uni"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Lima_12_uni->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Lima_12_uni"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Lima_12_uni) ?>',2);"><div id="elh_view_inv__3_Lima_12_uni" class="view_inv__3_Lima_12_uni">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Lima_12_uni->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Lima_12_uni->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Lima_12_uni->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Llave_mixta->Visible) { // 3_Llave_mixta ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Llave_mixta) == "") { ?>
		<th data-name="_3_Llave_mixta"><div id="elh_view_inv__3_Llave_mixta" class="view_inv__3_Llave_mixta"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Llave_mixta->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Llave_mixta"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Llave_mixta) ?>',2);"><div id="elh_view_inv__3_Llave_mixta" class="view_inv__3_Llave_mixta">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Llave_mixta->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Llave_mixta->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Llave_mixta->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Machete->Visible) { // 3_Machete ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Machete) == "") { ?>
		<th data-name="_3_Machete"><div id="elh_view_inv__3_Machete" class="view_inv__3_Machete"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Machete->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Machete"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Machete) ?>',2);"><div id="elh_view_inv__3_Machete" class="view_inv__3_Machete">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Machete->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Machete->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Machete->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Gafa_traslucida->Visible) { // 3_Gafa_traslucida ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Gafa_traslucida) == "") { ?>
		<th data-name="_3_Gafa_traslucida"><div id="elh_view_inv__3_Gafa_traslucida" class="view_inv__3_Gafa_traslucida"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Gafa_traslucida->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Gafa_traslucida"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Gafa_traslucida) ?>',2);"><div id="elh_view_inv__3_Gafa_traslucida" class="view_inv__3_Gafa_traslucida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Gafa_traslucida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Gafa_traslucida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Gafa_traslucida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Motosierra->Visible) { // 3_Motosierra ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Motosierra) == "") { ?>
		<th data-name="_3_Motosierra"><div id="elh_view_inv__3_Motosierra" class="view_inv__3_Motosierra"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Motosierra->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Motosierra"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Motosierra) ?>',2);"><div id="elh_view_inv__3_Motosierra" class="view_inv__3_Motosierra">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Motosierra->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Motosierra->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Motosierra->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Palin->Visible) { // 3_Palin ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Palin) == "") { ?>
		<th data-name="_3_Palin"><div id="elh_view_inv__3_Palin" class="view_inv__3_Palin"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Palin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Palin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Palin) ?>',2);"><div id="elh_view_inv__3_Palin" class="view_inv__3_Palin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Palin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Palin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Palin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->_3_Tubo_galvanizado->Visible) { // 3_Tubo_galvanizado ?>
	<?php if ($view_inv->SortUrl($view_inv->_3_Tubo_galvanizado) == "") { ?>
		<th data-name="_3_Tubo_galvanizado"><div id="elh_view_inv__3_Tubo_galvanizado" class="view_inv__3_Tubo_galvanizado"><div class="ewTableHeaderCaption"><?php echo $view_inv->_3_Tubo_galvanizado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Tubo_galvanizado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->_3_Tubo_galvanizado) ?>',2);"><div id="elh_view_inv__3_Tubo_galvanizado" class="view_inv__3_Tubo_galvanizado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->_3_Tubo_galvanizado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->_3_Tubo_galvanizado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->_3_Tubo_galvanizado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_inv->Modificado->Visible) { // Modificado ?>
	<?php if ($view_inv->SortUrl($view_inv->Modificado) == "") { ?>
		<th data-name="Modificado"><div id="elh_view_inv_Modificado" class="view_inv_Modificado"><div class="ewTableHeaderCaption"><?php echo $view_inv->Modificado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Modificado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_inv->SortUrl($view_inv->Modificado) ?>',2);"><div id="elh_view_inv_Modificado" class="view_inv_Modificado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_inv->Modificado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_inv->Modificado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_inv->Modificado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_inv_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view_inv->ExportAll && $view_inv->Export <> "") {
	$view_inv_list->StopRec = $view_inv_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view_inv_list->TotalRecs > $view_inv_list->StartRec + $view_inv_list->DisplayRecs - 1)
		$view_inv_list->StopRec = $view_inv_list->StartRec + $view_inv_list->DisplayRecs - 1;
	else
		$view_inv_list->StopRec = $view_inv_list->TotalRecs;
}
$view_inv_list->RecCnt = $view_inv_list->StartRec - 1;
if ($view_inv_list->Recordset && !$view_inv_list->Recordset->EOF) {
	$view_inv_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $view_inv_list->StartRec > 1)
		$view_inv_list->Recordset->Move($view_inv_list->StartRec - 1);
} elseif (!$view_inv->AllowAddDeleteRow && $view_inv_list->StopRec == 0) {
	$view_inv_list->StopRec = $view_inv->GridAddRowCount;
}

// Initialize aggregate
$view_inv->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_inv->ResetAttrs();
$view_inv_list->RenderRow();
while ($view_inv_list->RecCnt < $view_inv_list->StopRec) {
	$view_inv_list->RecCnt++;
	if (intval($view_inv_list->RecCnt) >= intval($view_inv_list->StartRec)) {
		$view_inv_list->RowCnt++;

		// Set up key count
		$view_inv_list->KeyCount = $view_inv_list->RowIndex;

		// Init row class and style
		$view_inv->ResetAttrs();
		$view_inv->CssClass = "";
		if ($view_inv->CurrentAction == "gridadd") {
		} else {
			$view_inv_list->LoadRowValues($view_inv_list->Recordset); // Load row values
		}
		$view_inv->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view_inv->RowAttrs = array_merge($view_inv->RowAttrs, array('data-rowindex'=>$view_inv_list->RowCnt, 'id'=>'r' . $view_inv_list->RowCnt . '_view_inv', 'data-rowtype'=>$view_inv->RowType));

		// Render row
		$view_inv_list->RenderRow();

		// Render list options
		$view_inv_list->RenderListOptions();
?>
	<tr<?php echo $view_inv->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_inv_list->ListOptions->Render("body", "left", $view_inv_list->RowCnt);
?>
	<?php if ($view_inv->llave->Visible) { // llave ?>
		<td data-name="llave"<?php echo $view_inv->llave->CellAttributes() ?>>
<span<?php echo $view_inv->llave->ViewAttributes() ?>>
<?php echo $view_inv->llave->ListViewValue() ?></span>
<a id="<?php echo $view_inv_list->PageObjName . "_row_" . $view_inv_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_inv->F_Sincron->Visible) { // F_Sincron ?>
		<td data-name="F_Sincron"<?php echo $view_inv->F_Sincron->CellAttributes() ?>>
<span<?php echo $view_inv->F_Sincron->ViewAttributes() ?>>
<?php echo $view_inv->F_Sincron->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->USUARIO->Visible) { // USUARIO ?>
		<td data-name="USUARIO"<?php echo $view_inv->USUARIO->CellAttributes() ?>>
<span<?php echo $view_inv->USUARIO->ViewAttributes() ?>>
<?php echo $view_inv->USUARIO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Cargo_gme->Visible) { // Cargo_gme ?>
		<td data-name="Cargo_gme"<?php echo $view_inv->Cargo_gme->CellAttributes() ?>>
<span<?php echo $view_inv->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_inv->Cargo_gme->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->NOM_PE->Visible) { // NOM_PE ?>
		<td data-name="NOM_PE"<?php echo $view_inv->NOM_PE->CellAttributes() ?>>
<span<?php echo $view_inv->NOM_PE->ViewAttributes() ?>>
<?php echo $view_inv->NOM_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Otro_PE->Visible) { // Otro_PE ?>
		<td data-name="Otro_PE"<?php echo $view_inv->Otro_PE->CellAttributes() ?>>
<span<?php echo $view_inv->Otro_PE->ViewAttributes() ?>>
<?php echo $view_inv->Otro_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->DIA->Visible) { // DIA ?>
		<td data-name="DIA"<?php echo $view_inv->DIA->CellAttributes() ?>>
<span<?php echo $view_inv->DIA->ViewAttributes() ?>>
<?php echo $view_inv->DIA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->MES->Visible) { // MES ?>
		<td data-name="MES"<?php echo $view_inv->MES->CellAttributes() ?>>
<span<?php echo $view_inv->MES->ViewAttributes() ?>>
<?php echo $view_inv->MES->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->OBSERVACION->Visible) { // OBSERVACION ?>
		<td data-name="OBSERVACION"<?php echo $view_inv->OBSERVACION->CellAttributes() ?>>
<span<?php echo $view_inv->OBSERVACION->ViewAttributes() ?>>
<?php echo $view_inv->OBSERVACION->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->AD1O->Visible) { // AÑO ?>
		<td data-name="AD1O"<?php echo $view_inv->AD1O->CellAttributes() ?>>
<span<?php echo $view_inv->AD1O->ViewAttributes() ?>>
<?php echo $view_inv->AD1O->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->FASE->Visible) { // FASE ?>
		<td data-name="FASE"<?php echo $view_inv->FASE->CellAttributes() ?>>
<span<?php echo $view_inv->FASE->ViewAttributes() ?>>
<?php echo $view_inv->FASE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->FECHA_INV->Visible) { // FECHA_INV ?>
		<td data-name="FECHA_INV"<?php echo $view_inv->FECHA_INV->CellAttributes() ?>>
<span<?php echo $view_inv->FECHA_INV->ViewAttributes() ?>>
<?php echo $view_inv->FECHA_INV->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->TIPO_INV->Visible) { // TIPO_INV ?>
		<td data-name="TIPO_INV"<?php echo $view_inv->TIPO_INV->CellAttributes() ?>>
<span<?php echo $view_inv->TIPO_INV->ViewAttributes() ?>>
<?php echo $view_inv->TIPO_INV->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
		<td data-name="NOM_CAPATAZ"<?php echo $view_inv->NOM_CAPATAZ->CellAttributes() ?>>
<span<?php echo $view_inv->NOM_CAPATAZ->ViewAttributes() ?>>
<?php echo $view_inv->NOM_CAPATAZ->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Otro_NOM_CAPAT->Visible) { // Otro_NOM_CAPAT ?>
		<td data-name="Otro_NOM_CAPAT"<?php echo $view_inv->Otro_NOM_CAPAT->CellAttributes() ?>>
<span<?php echo $view_inv->Otro_NOM_CAPAT->ViewAttributes() ?>>
<?php echo $view_inv->Otro_NOM_CAPAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Otro_CC_CAPAT->Visible) { // Otro_CC_CAPAT ?>
		<td data-name="Otro_CC_CAPAT"<?php echo $view_inv->Otro_CC_CAPAT->CellAttributes() ?>>
<span<?php echo $view_inv->Otro_CC_CAPAT->ViewAttributes() ?>>
<?php echo $view_inv->Otro_CC_CAPAT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->NOM_LUGAR->Visible) { // NOM_LUGAR ?>
		<td data-name="NOM_LUGAR"<?php echo $view_inv->NOM_LUGAR->CellAttributes() ?>>
<span<?php echo $view_inv->NOM_LUGAR->ViewAttributes() ?>>
<?php echo $view_inv->NOM_LUGAR->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Cocina->Visible) { // Cocina ?>
		<td data-name="Cocina"<?php echo $view_inv->Cocina->CellAttributes() ?>>
<span<?php echo $view_inv->Cocina->ViewAttributes() ?>>
<?php echo $view_inv->Cocina->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Abrelatas->Visible) { // 1_Abrelatas ?>
		<td data-name="_1_Abrelatas"<?php echo $view_inv->_1_Abrelatas->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Abrelatas->ViewAttributes() ?>>
<?php echo $view_inv->_1_Abrelatas->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Balde->Visible) { // 1_Balde ?>
		<td data-name="_1_Balde"<?php echo $view_inv->_1_Balde->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Balde->ViewAttributes() ?>>
<?php echo $view_inv->_1_Balde->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Arrocero_50->Visible) { // 1_Arrocero_50 ?>
		<td data-name="_1_Arrocero_50"<?php echo $view_inv->_1_Arrocero_50->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Arrocero_50->ViewAttributes() ?>>
<?php echo $view_inv->_1_Arrocero_50->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Arrocero_44->Visible) { // 1_Arrocero_44 ?>
		<td data-name="_1_Arrocero_44"<?php echo $view_inv->_1_Arrocero_44->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Arrocero_44->ViewAttributes() ?>>
<?php echo $view_inv->_1_Arrocero_44->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Chocolatera->Visible) { // 1_Chocolatera ?>
		<td data-name="_1_Chocolatera"<?php echo $view_inv->_1_Chocolatera->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Chocolatera->ViewAttributes() ?>>
<?php echo $view_inv->_1_Chocolatera->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Colador->Visible) { // 1_Colador ?>
		<td data-name="_1_Colador"<?php echo $view_inv->_1_Colador->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Colador->ViewAttributes() ?>>
<?php echo $view_inv->_1_Colador->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Cucharon_sopa->Visible) { // 1_Cucharon_sopa ?>
		<td data-name="_1_Cucharon_sopa"<?php echo $view_inv->_1_Cucharon_sopa->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Cucharon_sopa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cucharon_sopa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Cucharon_arroz->Visible) { // 1_Cucharon_arroz ?>
		<td data-name="_1_Cucharon_arroz"<?php echo $view_inv->_1_Cucharon_arroz->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Cucharon_arroz->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cucharon_arroz->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Cuchillo->Visible) { // 1_Cuchillo ?>
		<td data-name="_1_Cuchillo"<?php echo $view_inv->_1_Cuchillo->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Cuchillo->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cuchillo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Embudo->Visible) { // 1_Embudo ?>
		<td data-name="_1_Embudo"<?php echo $view_inv->_1_Embudo->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Embudo->ViewAttributes() ?>>
<?php echo $view_inv->_1_Embudo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Espumera->Visible) { // 1_Espumera ?>
		<td data-name="_1_Espumera"<?php echo $view_inv->_1_Espumera->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Espumera->ViewAttributes() ?>>
<?php echo $view_inv->_1_Espumera->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Estufa->Visible) { // 1_Estufa ?>
		<td data-name="_1_Estufa"<?php echo $view_inv->_1_Estufa->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Estufa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Estufa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Cuchara_sopa->Visible) { // 1_Cuchara_sopa ?>
		<td data-name="_1_Cuchara_sopa"<?php echo $view_inv->_1_Cuchara_sopa->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Cuchara_sopa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cuchara_sopa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Recipiente->Visible) { // 1_Recipiente ?>
		<td data-name="_1_Recipiente"<?php echo $view_inv->_1_Recipiente->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Recipiente->ViewAttributes() ?>>
<?php echo $view_inv->_1_Recipiente->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Kit_Repue_estufa->Visible) { // 1_Kit_Repue_estufa ?>
		<td data-name="_1_Kit_Repue_estufa"<?php echo $view_inv->_1_Kit_Repue_estufa->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Kit_Repue_estufa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Kit_Repue_estufa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Molinillo->Visible) { // 1_Molinillo ?>
		<td data-name="_1_Molinillo"<?php echo $view_inv->_1_Molinillo->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Molinillo->ViewAttributes() ?>>
<?php echo $view_inv->_1_Molinillo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Olla_36->Visible) { // 1_Olla_36 ?>
		<td data-name="_1_Olla_36"<?php echo $view_inv->_1_Olla_36->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Olla_36->ViewAttributes() ?>>
<?php echo $view_inv->_1_Olla_36->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Olla_40->Visible) { // 1_Olla_40 ?>
		<td data-name="_1_Olla_40"<?php echo $view_inv->_1_Olla_40->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Olla_40->ViewAttributes() ?>>
<?php echo $view_inv->_1_Olla_40->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Paila_32->Visible) { // 1_Paila_32 ?>
		<td data-name="_1_Paila_32"<?php echo $view_inv->_1_Paila_32->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Paila_32->ViewAttributes() ?>>
<?php echo $view_inv->_1_Paila_32->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_1_Paila_36_37->Visible) { // 1_Paila_36_37 ?>
		<td data-name="_1_Paila_36_37"<?php echo $view_inv->_1_Paila_36_37->CellAttributes() ?>>
<span<?php echo $view_inv->_1_Paila_36_37->ViewAttributes() ?>>
<?php echo $view_inv->_1_Paila_36_37->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Camping->Visible) { // Camping ?>
		<td data-name="Camping"<?php echo $view_inv->Camping->CellAttributes() ?>>
<span<?php echo $view_inv->Camping->ViewAttributes() ?>>
<?php echo $view_inv->Camping->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Aislante->Visible) { // 2_Aislante ?>
		<td data-name="_2_Aislante"<?php echo $view_inv->_2_Aislante->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Aislante->ViewAttributes() ?>>
<?php echo $view_inv->_2_Aislante->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Carpa_hamaca->Visible) { // 2_Carpa_hamaca ?>
		<td data-name="_2_Carpa_hamaca"<?php echo $view_inv->_2_Carpa_hamaca->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Carpa_hamaca->ViewAttributes() ?>>
<?php echo $view_inv->_2_Carpa_hamaca->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Carpa_rancho->Visible) { // 2_Carpa_rancho ?>
		<td data-name="_2_Carpa_rancho"<?php echo $view_inv->_2_Carpa_rancho->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Carpa_rancho->ViewAttributes() ?>>
<?php echo $view_inv->_2_Carpa_rancho->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Fibra_rollo->Visible) { // 2_Fibra_rollo ?>
		<td data-name="_2_Fibra_rollo"<?php echo $view_inv->_2_Fibra_rollo->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Fibra_rollo->ViewAttributes() ?>>
<?php echo $view_inv->_2_Fibra_rollo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_CAL->Visible) { // 2_CAL ?>
		<td data-name="_2_CAL"<?php echo $view_inv->_2_CAL->CellAttributes() ?>>
<span<?php echo $view_inv->_2_CAL->ViewAttributes() ?>>
<?php echo $view_inv->_2_CAL->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Linterna->Visible) { // 2_Linterna ?>
		<td data-name="_2_Linterna"<?php echo $view_inv->_2_Linterna->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Linterna->ViewAttributes() ?>>
<?php echo $view_inv->_2_Linterna->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Botiquin->Visible) { // 2_Botiquin ?>
		<td data-name="_2_Botiquin"<?php echo $view_inv->_2_Botiquin->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Botiquin->ViewAttributes() ?>>
<?php echo $view_inv->_2_Botiquin->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Mascara_filtro->Visible) { // 2_Mascara_filtro ?>
		<td data-name="_2_Mascara_filtro"<?php echo $view_inv->_2_Mascara_filtro->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Mascara_filtro->ViewAttributes() ?>>
<?php echo $view_inv->_2_Mascara_filtro->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Pimpina->Visible) { // 2_Pimpina ?>
		<td data-name="_2_Pimpina"<?php echo $view_inv->_2_Pimpina->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Pimpina->ViewAttributes() ?>>
<?php echo $view_inv->_2_Pimpina->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_SleepingA0->Visible) { // 2_Sleeping  ?>
		<td data-name="_2_SleepingA0"<?php echo $view_inv->_2_SleepingA0->CellAttributes() ?>>
<span<?php echo $view_inv->_2_SleepingA0->ViewAttributes() ?>>
<?php echo $view_inv->_2_SleepingA0->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Plastico_negro->Visible) { // 2_Plastico_negro ?>
		<td data-name="_2_Plastico_negro"<?php echo $view_inv->_2_Plastico_negro->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Plastico_negro->ViewAttributes() ?>>
<?php echo $view_inv->_2_Plastico_negro->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Tula_tropa->Visible) { // 2_Tula_tropa ?>
		<td data-name="_2_Tula_tropa"<?php echo $view_inv->_2_Tula_tropa->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Tula_tropa->ViewAttributes() ?>>
<?php echo $view_inv->_2_Tula_tropa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_2_Camilla->Visible) { // 2_Camilla ?>
		<td data-name="_2_Camilla"<?php echo $view_inv->_2_Camilla->CellAttributes() ?>>
<span<?php echo $view_inv->_2_Camilla->ViewAttributes() ?>>
<?php echo $view_inv->_2_Camilla->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Herramientas->Visible) { // Herramientas ?>
		<td data-name="Herramientas"<?php echo $view_inv->Herramientas->CellAttributes() ?>>
<span<?php echo $view_inv->Herramientas->ViewAttributes() ?>>
<?php echo $view_inv->Herramientas->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Abrazadera->Visible) { // 3_Abrazadera ?>
		<td data-name="_3_Abrazadera"<?php echo $view_inv->_3_Abrazadera->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Abrazadera->ViewAttributes() ?>>
<?php echo $view_inv->_3_Abrazadera->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Aspersora->Visible) { // 3_Aspersora ?>
		<td data-name="_3_Aspersora"<?php echo $view_inv->_3_Aspersora->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Aspersora->ViewAttributes() ?>>
<?php echo $view_inv->_3_Aspersora->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Cabo_hacha->Visible) { // 3_Cabo_hacha ?>
		<td data-name="_3_Cabo_hacha"<?php echo $view_inv->_3_Cabo_hacha->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Cabo_hacha->ViewAttributes() ?>>
<?php echo $view_inv->_3_Cabo_hacha->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Funda_machete->Visible) { // 3_Funda_machete ?>
		<td data-name="_3_Funda_machete"<?php echo $view_inv->_3_Funda_machete->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Funda_machete->ViewAttributes() ?>>
<?php echo $view_inv->_3_Funda_machete->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Glifosato_4lt->Visible) { // 3_Glifosato_4lt ?>
		<td data-name="_3_Glifosato_4lt"<?php echo $view_inv->_3_Glifosato_4lt->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Glifosato_4lt->ViewAttributes() ?>>
<?php echo $view_inv->_3_Glifosato_4lt->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Hacha->Visible) { // 3_Hacha ?>
		<td data-name="_3_Hacha"<?php echo $view_inv->_3_Hacha->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Hacha->ViewAttributes() ?>>
<?php echo $view_inv->_3_Hacha->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Lima_12_uni->Visible) { // 3_Lima_12_uni ?>
		<td data-name="_3_Lima_12_uni"<?php echo $view_inv->_3_Lima_12_uni->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Lima_12_uni->ViewAttributes() ?>>
<?php echo $view_inv->_3_Lima_12_uni->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Llave_mixta->Visible) { // 3_Llave_mixta ?>
		<td data-name="_3_Llave_mixta"<?php echo $view_inv->_3_Llave_mixta->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Llave_mixta->ViewAttributes() ?>>
<?php echo $view_inv->_3_Llave_mixta->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Machete->Visible) { // 3_Machete ?>
		<td data-name="_3_Machete"<?php echo $view_inv->_3_Machete->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Machete->ViewAttributes() ?>>
<?php echo $view_inv->_3_Machete->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Gafa_traslucida->Visible) { // 3_Gafa_traslucida ?>
		<td data-name="_3_Gafa_traslucida"<?php echo $view_inv->_3_Gafa_traslucida->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Gafa_traslucida->ViewAttributes() ?>>
<?php echo $view_inv->_3_Gafa_traslucida->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Motosierra->Visible) { // 3_Motosierra ?>
		<td data-name="_3_Motosierra"<?php echo $view_inv->_3_Motosierra->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Motosierra->ViewAttributes() ?>>
<?php echo $view_inv->_3_Motosierra->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Palin->Visible) { // 3_Palin ?>
		<td data-name="_3_Palin"<?php echo $view_inv->_3_Palin->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Palin->ViewAttributes() ?>>
<?php echo $view_inv->_3_Palin->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->_3_Tubo_galvanizado->Visible) { // 3_Tubo_galvanizado ?>
		<td data-name="_3_Tubo_galvanizado"<?php echo $view_inv->_3_Tubo_galvanizado->CellAttributes() ?>>
<span<?php echo $view_inv->_3_Tubo_galvanizado->ViewAttributes() ?>>
<?php echo $view_inv->_3_Tubo_galvanizado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_inv->Modificado->Visible) { // Modificado ?>
		<td data-name="Modificado"<?php echo $view_inv->Modificado->CellAttributes() ?>>
<span<?php echo $view_inv->Modificado->ViewAttributes() ?>>
<?php echo $view_inv->Modificado->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_inv_list->ListOptions->Render("body", "right", $view_inv_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view_inv->CurrentAction <> "gridadd")
		$view_inv_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view_inv->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view_inv_list->Recordset)
	$view_inv_list->Recordset->Close();
?>
<?php if ($view_inv->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view_inv->CurrentAction <> "gridadd" && $view_inv->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_inv_list->Pager)) $view_inv_list->Pager = new cPrevNextPager($view_inv_list->StartRec, $view_inv_list->DisplayRecs, $view_inv_list->TotalRecs) ?>
<?php if ($view_inv_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_inv_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_inv_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_inv_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_inv_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_inv_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_inv_list->PageUrl() ?>start=<?php echo $view_inv_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_inv_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_inv_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_inv_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_inv_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_inv_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view_inv_list->TotalRecs == 0 && $view_inv->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_inv_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view_inv->Export == "") { ?>
<script type="text/javascript">
fview_invlistsrch.Init();
fview_invlist.Init();
</script>
<?php } ?>
<?php
$view_inv_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view_inv->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view_inv_list->Page_Terminate();
?>
