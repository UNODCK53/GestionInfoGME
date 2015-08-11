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

$view_e_y_n_list = NULL; // Initialize page object first

class cview_e_y_n_list extends cview_e_y_n {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_e_y_n';

	// Page object name
	var $PageObjName = 'view_e_y_n_list';

	// Grid form hidden field names
	var $FormName = 'fview_e_y_nlist';
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

		// Table object (view_e_y_n)
		if (!isset($GLOBALS["view_e_y_n"]) || get_class($GLOBALS["view_e_y_n"]) == "cview_e_y_n") {
			$GLOBALS["view_e_y_n"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_e_y_n"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view_e_y_nadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view_e_y_ndelete.php";
		$this->MultiUpdateUrl = "view_e_y_nupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_e_y_n', TRUE);

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
		$this->BuildSearchSql($sWhere, $this->ID_Formulario, $Default, FALSE); // ID_Formulario
		$this->BuildSearchSql($sWhere, $this->USUARIO, $Default, FALSE); // USUARIO
		$this->BuildSearchSql($sWhere, $this->NOM_GE, $Default, FALSE); // NOM_GE
		$this->BuildSearchSql($sWhere, $this->Otro_PGE, $Default, FALSE); // Otro_PGE
		$this->BuildSearchSql($sWhere, $this->FECHA_NOVEDAD, $Default, FALSE); // FECHA_NOVEDAD
		$this->BuildSearchSql($sWhere, $this->NOM_PE, $Default, FALSE); // NOM_PE
		$this->BuildSearchSql($sWhere, $this->Otro_Nom_PE, $Default, FALSE); // Otro_Nom_PE
		$this->BuildSearchSql($sWhere, $this->Muncipio, $Default, FALSE); // Muncipio
		$this->BuildSearchSql($sWhere, $this->Departamento, $Default, FALSE); // Departamento

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->ID_Formulario->AdvancedSearch->Save(); // ID_Formulario
			$this->USUARIO->AdvancedSearch->Save(); // USUARIO
			$this->NOM_GE->AdvancedSearch->Save(); // NOM_GE
			$this->Otro_PGE->AdvancedSearch->Save(); // Otro_PGE
			$this->FECHA_NOVEDAD->AdvancedSearch->Save(); // FECHA_NOVEDAD
			$this->NOM_PE->AdvancedSearch->Save(); // NOM_PE
			$this->Otro_Nom_PE->AdvancedSearch->Save(); // Otro_Nom_PE
			$this->Muncipio->AdvancedSearch->Save(); // Muncipio
			$this->Departamento->AdvancedSearch->Save(); // Departamento
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
		$this->BuildBasicSearchSQL($sWhere, $this->ID_Formulario, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->USUARIO, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_GE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FECHA_NOVEDAD, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_Nom_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Muncipio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Departamento, $arKeywords, $type);
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
		if ($this->ID_Formulario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->USUARIO->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_GE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_PGE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FECHA_NOVEDAD->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->NOM_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_Nom_PE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Muncipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Departamento->AdvancedSearch->IssetSession())
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
		$this->ID_Formulario->AdvancedSearch->UnsetSession();
		$this->USUARIO->AdvancedSearch->UnsetSession();
		$this->NOM_GE->AdvancedSearch->UnsetSession();
		$this->Otro_PGE->AdvancedSearch->UnsetSession();
		$this->FECHA_NOVEDAD->AdvancedSearch->UnsetSession();
		$this->NOM_PE->AdvancedSearch->UnsetSession();
		$this->Otro_Nom_PE->AdvancedSearch->UnsetSession();
		$this->Muncipio->AdvancedSearch->UnsetSession();
		$this->Departamento->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->ID_Formulario->AdvancedSearch->Load();
		$this->USUARIO->AdvancedSearch->Load();
		$this->NOM_GE->AdvancedSearch->Load();
		$this->Otro_PGE->AdvancedSearch->Load();
		$this->FECHA_NOVEDAD->AdvancedSearch->Load();
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_Nom_PE->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->ID_Formulario, $bCtrl); // ID_Formulario
			$this->UpdateSort($this->F_Sincron, $bCtrl); // F_Sincron
			$this->UpdateSort($this->USUARIO, $bCtrl); // USUARIO
			$this->UpdateSort($this->Cargo_gme, $bCtrl); // Cargo_gme
			$this->UpdateSort($this->NOM_GE, $bCtrl); // NOM_GE
			$this->UpdateSort($this->Otro_PGE, $bCtrl); // Otro_PGE
			$this->UpdateSort($this->Otro_CC_PGE, $bCtrl); // Otro_CC_PGE
			$this->UpdateSort($this->TIPO_INFORME, $bCtrl); // TIPO_INFORME
			$this->UpdateSort($this->FECHA_NOVEDAD, $bCtrl); // FECHA_NOVEDAD
			$this->UpdateSort($this->DIA, $bCtrl); // DIA
			$this->UpdateSort($this->MES, $bCtrl); // MES
			$this->UpdateSort($this->Num_Evacua, $bCtrl); // Num_Evacua
			$this->UpdateSort($this->PTO_INCOMU, $bCtrl); // PTO_INCOMU
			$this->UpdateSort($this->OBS_punt_inco, $bCtrl); // OBS_punt_inco
			$this->UpdateSort($this->OBS_ENLACE, $bCtrl); // OBS_ENLACE
			$this->UpdateSort($this->NUM_Novedad, $bCtrl); // NUM_Novedad
			$this->UpdateSort($this->Nom_Per_Evacu, $bCtrl); // Nom_Per_Evacu
			$this->UpdateSort($this->Nom_Otro_Per_Evacu, $bCtrl); // Nom_Otro_Per_Evacu
			$this->UpdateSort($this->CC_Otro_Per_Evacu, $bCtrl); // CC_Otro_Per_Evacu
			$this->UpdateSort($this->Cargo_Per_EVA, $bCtrl); // Cargo_Per_EVA
			$this->UpdateSort($this->Motivo_Eva, $bCtrl); // Motivo_Eva
			$this->UpdateSort($this->OBS_EVA, $bCtrl); // OBS_EVA
			$this->UpdateSort($this->NOM_PE, $bCtrl); // NOM_PE
			$this->UpdateSort($this->Otro_Nom_PE, $bCtrl); // Otro_Nom_PE
			$this->UpdateSort($this->NOM_CAPATAZ, $bCtrl); // NOM_CAPATAZ
			$this->UpdateSort($this->Otro_Nom_Capata, $bCtrl); // Otro_Nom_Capata
			$this->UpdateSort($this->Otro_CC_Capata, $bCtrl); // Otro_CC_Capata
			$this->UpdateSort($this->Muncipio, $bCtrl); // Muncipio
			$this->UpdateSort($this->Departamento, $bCtrl); // Departamento
			$this->UpdateSort($this->F_llegada, $bCtrl); // F_llegada
			$this->UpdateSort($this->Fecha, $bCtrl); // Fecha
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
				$this->ID_Formulario->setSort("");
				$this->F_Sincron->setSort("");
				$this->USUARIO->setSort("");
				$this->Cargo_gme->setSort("");
				$this->NOM_GE->setSort("");
				$this->Otro_PGE->setSort("");
				$this->Otro_CC_PGE->setSort("");
				$this->TIPO_INFORME->setSort("");
				$this->FECHA_NOVEDAD->setSort("");
				$this->DIA->setSort("");
				$this->MES->setSort("");
				$this->Num_Evacua->setSort("");
				$this->PTO_INCOMU->setSort("");
				$this->OBS_punt_inco->setSort("");
				$this->OBS_ENLACE->setSort("");
				$this->NUM_Novedad->setSort("");
				$this->Nom_Per_Evacu->setSort("");
				$this->Nom_Otro_Per_Evacu->setSort("");
				$this->CC_Otro_Per_Evacu->setSort("");
				$this->Cargo_Per_EVA->setSort("");
				$this->Motivo_Eva->setSort("");
				$this->OBS_EVA->setSort("");
				$this->NOM_PE->setSort("");
				$this->Otro_Nom_PE->setSort("");
				$this->NOM_CAPATAZ->setSort("");
				$this->Otro_Nom_Capata->setSort("");
				$this->Otro_CC_Capata->setSort("");
				$this->Muncipio->setSort("");
				$this->Departamento->setSort("");
				$this->F_llegada->setSort("");
				$this->Fecha->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview_e_y_nlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview_e_y_nlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// ID_Formulario

		$this->ID_Formulario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ID_Formulario"]);
		if ($this->ID_Formulario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ID_Formulario->AdvancedSearch->SearchOperator = @$_GET["z_ID_Formulario"];

		// USUARIO
		$this->USUARIO->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_USUARIO"]);
		if ($this->USUARIO->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->USUARIO->AdvancedSearch->SearchOperator = @$_GET["z_USUARIO"];

		// NOM_GE
		$this->NOM_GE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_GE"]);
		if ($this->NOM_GE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_GE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_GE"];

		// Otro_PGE
		$this->Otro_PGE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_PGE"]);
		if ($this->Otro_PGE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_PGE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_PGE"];

		// FECHA_NOVEDAD
		$this->FECHA_NOVEDAD->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FECHA_NOVEDAD"]);
		if ($this->FECHA_NOVEDAD->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FECHA_NOVEDAD->AdvancedSearch->SearchOperator = @$_GET["z_FECHA_NOVEDAD"];

		// NOM_PE
		$this->NOM_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_PE"]);
		if ($this->NOM_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_PE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_PE"];

		// Otro_Nom_PE
		$this->Otro_Nom_PE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_Nom_PE"]);
		if ($this->Otro_Nom_PE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_Nom_PE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_Nom_PE"];

		// Muncipio
		$this->Muncipio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Muncipio"]);
		if ($this->Muncipio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Muncipio->AdvancedSearch->SearchOperator = @$_GET["z_Muncipio"];

		// Departamento
		$this->Departamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Departamento"]);
		if ($this->Departamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Departamento->AdvancedSearch->SearchOperator = @$_GET["z_Departamento"];
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
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->F_llegada->setDbValue($rs->fields('F_llegada'));
		$this->Fecha->setDbValue($rs->fields('Fecha'));
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
		$this->Departamento->DbValue = $row['Departamento'];
		$this->F_llegada->DbValue = $row['F_llegada'];
		$this->Fecha->DbValue = $row['Fecha'];
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
		// Departamento
		// F_llegada
		// Fecha

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// ID_Formulario
			$this->ID_Formulario->ViewValue = $this->ID_Formulario->CurrentValue;
			$this->ID_Formulario->ViewCustomAttributes = "";

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

			// NOM_GE
			$this->NOM_GE->ViewValue = $this->NOM_GE->CurrentValue;
			$this->NOM_GE->ViewCustomAttributes = "";

			// Otro_PGE
			$this->Otro_PGE->ViewValue = $this->Otro_PGE->CurrentValue;
			$this->Otro_PGE->ViewCustomAttributes = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
			$this->Otro_CC_PGE->ViewCustomAttributes = "";

			// TIPO_INFORME
			$this->TIPO_INFORME->ViewValue = $this->TIPO_INFORME->CurrentValue;
			$this->TIPO_INFORME->ViewCustomAttributes = "";

			// FECHA_NOVEDAD
			$this->FECHA_NOVEDAD->ViewValue = $this->FECHA_NOVEDAD->CurrentValue;
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
			$this->PTO_INCOMU->ViewValue = $this->PTO_INCOMU->CurrentValue;
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
			$this->Cargo_Per_EVA->ViewValue = $this->Cargo_Per_EVA->CurrentValue;
			$this->Cargo_Per_EVA->ViewCustomAttributes = "";

			// Motivo_Eva
			$this->Motivo_Eva->ViewValue = $this->Motivo_Eva->CurrentValue;
			$this->Motivo_Eva->ViewCustomAttributes = "";

			// OBS_EVA
			$this->OBS_EVA->ViewValue = $this->OBS_EVA->CurrentValue;
			$this->OBS_EVA->ViewCustomAttributes = "";

			// NOM_PE
			$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
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

			// Departamento
			$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
			$this->Departamento->ViewCustomAttributes = "";

			// F_llegada
			$this->F_llegada->ViewValue = $this->F_llegada->CurrentValue;
			$this->F_llegada->ViewCustomAttributes = "";

			// Fecha
			$this->Fecha->ViewValue = $this->Fecha->CurrentValue;
			$this->Fecha->ViewCustomAttributes = "";

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

			// Departamento
			$this->Departamento->LinkCustomAttributes = "";
			$this->Departamento->HrefValue = "";
			$this->Departamento->TooltipValue = "";

			// F_llegada
			$this->F_llegada->LinkCustomAttributes = "";
			$this->F_llegada->HrefValue = "";
			$this->F_llegada->TooltipValue = "";

			// Fecha
			$this->Fecha->LinkCustomAttributes = "";
			$this->Fecha->HrefValue = "";
			$this->Fecha->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// ID_Formulario
			$this->ID_Formulario->EditAttrs["class"] = "form-control";
			$this->ID_Formulario->EditCustomAttributes = "";
			$this->ID_Formulario->EditValue = ew_HtmlEncode($this->ID_Formulario->AdvancedSearch->SearchValue);
			$this->ID_Formulario->PlaceHolder = ew_RemoveHtml($this->ID_Formulario->FldCaption());

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

			// NOM_GE
			$this->NOM_GE->EditAttrs["class"] = "form-control";
			$this->NOM_GE->EditCustomAttributes = "";
			$this->NOM_GE->EditValue = ew_HtmlEncode($this->NOM_GE->AdvancedSearch->SearchValue);
			$this->NOM_GE->PlaceHolder = ew_RemoveHtml($this->NOM_GE->FldCaption());

			// Otro_PGE
			$this->Otro_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_PGE->EditCustomAttributes = "";
			$this->Otro_PGE->EditValue = ew_HtmlEncode($this->Otro_PGE->AdvancedSearch->SearchValue);
			$this->Otro_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_PGE->FldCaption());

			// Otro_CC_PGE
			$this->Otro_CC_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_CC_PGE->EditCustomAttributes = "";
			$this->Otro_CC_PGE->EditValue = ew_HtmlEncode($this->Otro_CC_PGE->AdvancedSearch->SearchValue);
			$this->Otro_CC_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_CC_PGE->FldCaption());

			// TIPO_INFORME
			$this->TIPO_INFORME->EditAttrs["class"] = "form-control";
			$this->TIPO_INFORME->EditCustomAttributes = "";
			$this->TIPO_INFORME->EditValue = ew_HtmlEncode($this->TIPO_INFORME->AdvancedSearch->SearchValue);
			$this->TIPO_INFORME->PlaceHolder = ew_RemoveHtml($this->TIPO_INFORME->FldCaption());

			// FECHA_NOVEDAD
			$this->FECHA_NOVEDAD->EditAttrs["class"] = "form-control";
			$this->FECHA_NOVEDAD->EditCustomAttributes = "";
			$this->FECHA_NOVEDAD->EditValue = ew_HtmlEncode($this->FECHA_NOVEDAD->AdvancedSearch->SearchValue);
			$this->FECHA_NOVEDAD->PlaceHolder = ew_RemoveHtml($this->FECHA_NOVEDAD->FldCaption());

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

			// Num_Evacua
			$this->Num_Evacua->EditAttrs["class"] = "form-control";
			$this->Num_Evacua->EditCustomAttributes = "";
			$this->Num_Evacua->EditValue = ew_HtmlEncode($this->Num_Evacua->AdvancedSearch->SearchValue);
			$this->Num_Evacua->PlaceHolder = ew_RemoveHtml($this->Num_Evacua->FldCaption());

			// PTO_INCOMU
			$this->PTO_INCOMU->EditAttrs["class"] = "form-control";
			$this->PTO_INCOMU->EditCustomAttributes = "";
			$this->PTO_INCOMU->EditValue = ew_HtmlEncode($this->PTO_INCOMU->AdvancedSearch->SearchValue);
			$this->PTO_INCOMU->PlaceHolder = ew_RemoveHtml($this->PTO_INCOMU->FldCaption());

			// OBS_punt_inco
			$this->OBS_punt_inco->EditAttrs["class"] = "form-control";
			$this->OBS_punt_inco->EditCustomAttributes = "";
			$this->OBS_punt_inco->EditValue = ew_HtmlEncode($this->OBS_punt_inco->AdvancedSearch->SearchValue);
			$this->OBS_punt_inco->PlaceHolder = ew_RemoveHtml($this->OBS_punt_inco->FldCaption());

			// OBS_ENLACE
			$this->OBS_ENLACE->EditAttrs["class"] = "form-control";
			$this->OBS_ENLACE->EditCustomAttributes = "";
			$this->OBS_ENLACE->EditValue = ew_HtmlEncode($this->OBS_ENLACE->AdvancedSearch->SearchValue);
			$this->OBS_ENLACE->PlaceHolder = ew_RemoveHtml($this->OBS_ENLACE->FldCaption());

			// NUM_Novedad
			$this->NUM_Novedad->EditAttrs["class"] = "form-control";
			$this->NUM_Novedad->EditCustomAttributes = "";
			$this->NUM_Novedad->EditValue = ew_HtmlEncode($this->NUM_Novedad->AdvancedSearch->SearchValue);
			$this->NUM_Novedad->PlaceHolder = ew_RemoveHtml($this->NUM_Novedad->FldCaption());

			// Nom_Per_Evacu
			$this->Nom_Per_Evacu->EditAttrs["class"] = "form-control";
			$this->Nom_Per_Evacu->EditCustomAttributes = "";
			$this->Nom_Per_Evacu->EditValue = ew_HtmlEncode($this->Nom_Per_Evacu->AdvancedSearch->SearchValue);
			$this->Nom_Per_Evacu->PlaceHolder = ew_RemoveHtml($this->Nom_Per_Evacu->FldCaption());

			// Nom_Otro_Per_Evacu
			$this->Nom_Otro_Per_Evacu->EditAttrs["class"] = "form-control";
			$this->Nom_Otro_Per_Evacu->EditCustomAttributes = "";
			$this->Nom_Otro_Per_Evacu->EditValue = ew_HtmlEncode($this->Nom_Otro_Per_Evacu->AdvancedSearch->SearchValue);
			$this->Nom_Otro_Per_Evacu->PlaceHolder = ew_RemoveHtml($this->Nom_Otro_Per_Evacu->FldCaption());

			// CC_Otro_Per_Evacu
			$this->CC_Otro_Per_Evacu->EditAttrs["class"] = "form-control";
			$this->CC_Otro_Per_Evacu->EditCustomAttributes = "";
			$this->CC_Otro_Per_Evacu->EditValue = ew_HtmlEncode($this->CC_Otro_Per_Evacu->AdvancedSearch->SearchValue);
			$this->CC_Otro_Per_Evacu->PlaceHolder = ew_RemoveHtml($this->CC_Otro_Per_Evacu->FldCaption());

			// Cargo_Per_EVA
			$this->Cargo_Per_EVA->EditAttrs["class"] = "form-control";
			$this->Cargo_Per_EVA->EditCustomAttributes = "";
			$this->Cargo_Per_EVA->EditValue = ew_HtmlEncode($this->Cargo_Per_EVA->AdvancedSearch->SearchValue);
			$this->Cargo_Per_EVA->PlaceHolder = ew_RemoveHtml($this->Cargo_Per_EVA->FldCaption());

			// Motivo_Eva
			$this->Motivo_Eva->EditAttrs["class"] = "form-control";
			$this->Motivo_Eva->EditCustomAttributes = "";
			$this->Motivo_Eva->EditValue = ew_HtmlEncode($this->Motivo_Eva->AdvancedSearch->SearchValue);
			$this->Motivo_Eva->PlaceHolder = ew_RemoveHtml($this->Motivo_Eva->FldCaption());

			// OBS_EVA
			$this->OBS_EVA->EditAttrs["class"] = "form-control";
			$this->OBS_EVA->EditCustomAttributes = "";
			$this->OBS_EVA->EditValue = ew_HtmlEncode($this->OBS_EVA->AdvancedSearch->SearchValue);
			$this->OBS_EVA->PlaceHolder = ew_RemoveHtml($this->OBS_EVA->FldCaption());

			// NOM_PE
			$this->NOM_PE->EditAttrs["class"] = "form-control";
			$this->NOM_PE->EditCustomAttributes = "";
			$this->NOM_PE->EditValue = ew_HtmlEncode($this->NOM_PE->AdvancedSearch->SearchValue);
			$this->NOM_PE->PlaceHolder = ew_RemoveHtml($this->NOM_PE->FldCaption());

			// Otro_Nom_PE
			$this->Otro_Nom_PE->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_PE->EditCustomAttributes = "";
			$this->Otro_Nom_PE->EditValue = ew_HtmlEncode($this->Otro_Nom_PE->AdvancedSearch->SearchValue);
			$this->Otro_Nom_PE->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_PE->FldCaption());

			// NOM_CAPATAZ
			$this->NOM_CAPATAZ->EditAttrs["class"] = "form-control";
			$this->NOM_CAPATAZ->EditCustomAttributes = "";
			$this->NOM_CAPATAZ->EditValue = ew_HtmlEncode($this->NOM_CAPATAZ->AdvancedSearch->SearchValue);
			$this->NOM_CAPATAZ->PlaceHolder = ew_RemoveHtml($this->NOM_CAPATAZ->FldCaption());

			// Otro_Nom_Capata
			$this->Otro_Nom_Capata->EditAttrs["class"] = "form-control";
			$this->Otro_Nom_Capata->EditCustomAttributes = "";
			$this->Otro_Nom_Capata->EditValue = ew_HtmlEncode($this->Otro_Nom_Capata->AdvancedSearch->SearchValue);
			$this->Otro_Nom_Capata->PlaceHolder = ew_RemoveHtml($this->Otro_Nom_Capata->FldCaption());

			// Otro_CC_Capata
			$this->Otro_CC_Capata->EditAttrs["class"] = "form-control";
			$this->Otro_CC_Capata->EditCustomAttributes = "";
			$this->Otro_CC_Capata->EditValue = ew_HtmlEncode($this->Otro_CC_Capata->AdvancedSearch->SearchValue);
			$this->Otro_CC_Capata->PlaceHolder = ew_RemoveHtml($this->Otro_CC_Capata->FldCaption());

			// Muncipio
			$this->Muncipio->EditAttrs["class"] = "form-control";
			$this->Muncipio->EditCustomAttributes = "";
			$this->Muncipio->EditValue = ew_HtmlEncode($this->Muncipio->AdvancedSearch->SearchValue);
			$this->Muncipio->PlaceHolder = ew_RemoveHtml($this->Muncipio->FldCaption());

			// Departamento
			$this->Departamento->EditAttrs["class"] = "form-control";
			$this->Departamento->EditCustomAttributes = "";
			$this->Departamento->EditValue = ew_HtmlEncode($this->Departamento->AdvancedSearch->SearchValue);
			$this->Departamento->PlaceHolder = ew_RemoveHtml($this->Departamento->FldCaption());

			// F_llegada
			$this->F_llegada->EditAttrs["class"] = "form-control";
			$this->F_llegada->EditCustomAttributes = "";
			$this->F_llegada->EditValue = ew_HtmlEncode($this->F_llegada->AdvancedSearch->SearchValue);
			$this->F_llegada->PlaceHolder = ew_RemoveHtml($this->F_llegada->FldCaption());

			// Fecha
			$this->Fecha->EditAttrs["class"] = "form-control";
			$this->Fecha->EditCustomAttributes = "";
			$this->Fecha->EditValue = ew_HtmlEncode($this->Fecha->AdvancedSearch->SearchValue);
			$this->Fecha->PlaceHolder = ew_RemoveHtml($this->Fecha->FldCaption());
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
		$this->ID_Formulario->AdvancedSearch->Load();
		$this->USUARIO->AdvancedSearch->Load();
		$this->NOM_GE->AdvancedSearch->Load();
		$this->Otro_PGE->AdvancedSearch->Load();
		$this->FECHA_NOVEDAD->AdvancedSearch->Load();
		$this->NOM_PE->AdvancedSearch->Load();
		$this->Otro_Nom_PE->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_view_e_y_n\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_e_y_n',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_e_y_nlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view_e_y_n_list)) $view_e_y_n_list = new cview_e_y_n_list();

// Page init
$view_e_y_n_list->Page_Init();

// Page main
$view_e_y_n_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_e_y_n_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view_e_y_n->Export == "") { ?>
<script type="text/javascript">

// Page object
var view_e_y_n_list = new ew_Page("view_e_y_n_list");
view_e_y_n_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view_e_y_n_list.PageID; // For backward compatibility

// Form object
var fview_e_y_nlist = new ew_Form("fview_e_y_nlist");
fview_e_y_nlist.FormKeyCountName = '<?php echo $view_e_y_n_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview_e_y_nlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_e_y_nlist.ValidateRequired = true;
<?php } else { ?>
fview_e_y_nlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fview_e_y_nlistsrch = new ew_Form("fview_e_y_nlistsrch");

// Validate function for search
fview_e_y_nlistsrch.Validate = function(fobj) {
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
fview_e_y_nlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_e_y_nlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fview_e_y_nlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view_e_y_n->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view_e_y_n->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<H2> Informe de Enlace y Novedad</h2>

<p>La siguiente tabla contiene los informes de Enlace y Novedad realizados desde la fase II de erradicación 2015 a la fecha</p>

<hr>

<table>
	<tr>
		<td><?php if ($view_e_y_n_list->TotalRecs > 0 && $view_e_y_n_list->ExportOptions->Visible()) { ?>
		<?php $view_e_y_n_list->ExportOptions->Render("body") ?></td>
		<td>Si desea exportar la tabla en formato excel haga click en el siguiente icono</td>	
	</tr>
</table>

<hr>

<?php } ?>
<?php if ($view_e_y_n->Export == "") { ?>

<?php } ?>

</div>

<br>

<table>
	<tr>
		<td><?php if ($view_e_y_n_list->SearchOptions->Visible()) { ?>
		<?php $view_e_y_n_list->SearchOptions->Render("body") ?></td>
		<td>Si desea realizar filtros en la tabla haga click en el siguiente icono e ingrese el dato en la columna correspondiente </td>
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
		if ($view_e_y_n_list->TotalRecs <= 0)
			$view_e_y_n_list->TotalRecs = $view_e_y_n->SelectRecordCount();
	} else {
		if (!$view_e_y_n_list->Recordset && ($view_e_y_n_list->Recordset = $view_e_y_n_list->LoadRecordset()))
			$view_e_y_n_list->TotalRecs = $view_e_y_n_list->Recordset->RecordCount();
	}
	$view_e_y_n_list->StartRec = 1;
	if ($view_e_y_n_list->DisplayRecs <= 0 || ($view_e_y_n->Export <> "" && $view_e_y_n->ExportAll)) // Display all records
		$view_e_y_n_list->DisplayRecs = $view_e_y_n_list->TotalRecs;
	if (!($view_e_y_n->Export <> "" && $view_e_y_n->ExportAll))
		$view_e_y_n_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view_e_y_n_list->Recordset = $view_e_y_n_list->LoadRecordset($view_e_y_n_list->StartRec-1, $view_e_y_n_list->DisplayRecs);

	// Set no record found message
	if ($view_e_y_n->CurrentAction == "" && $view_e_y_n_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_e_y_n_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_e_y_n_list->SearchWhere == "0=101")
			$view_e_y_n_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_e_y_n_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view_e_y_n_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view_e_y_n->Export == "" && $view_e_y_n->CurrentAction == "") { ?>
<form name="fview_e_y_nlistsrch" id="fview_e_y_nlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view_e_y_n_list->SearchWhere <> "") ? " " : " "; ?>
<div id="fview_e_y_nlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_e_y_n">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$view_e_y_n_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$view_e_y_n->RowType = EW_ROWTYPE_SEARCH;

// Render row
$view_e_y_n->ResetAttrs();
$view_e_y_n_list->RenderRow();
?>

<br>

<table>
	<tr>
		<td><label for="x_USUARIO" class="ewSearchCaption ewLabel"><?php echo $view_e_y_n->USUARIO->FldCaption() ?></label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_USUARIO" id="z_USUARIO" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<input type="text" data-field="x_USUARIO" name="x_USUARIO" id="x_USUARIO" size="35" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->USUARIO->PlaceHolder) ?>" value="<?php echo $view_e_y_n->USUARIO->EditValue ?>"<?php echo $view_e_y_n->USUARIO->EditAttributes() ?>>
			</span></td>
	</tr>
	<tr>
		<td><label for="x_NOM_GE" class="ewSearchCaption ewLabel">NOMBRE PROFESIONAL ESPECIALIZADO</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_GE" id="z_NOM_GE" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<input type="text" data-field="x_NOM_GE" name="x_NOM_GE" id="x_NOM_GE" size="35" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->NOM_GE->PlaceHolder) ?>" value="<?php echo $view_e_y_n->NOM_GE->EditValue ?>"<?php echo $view_e_y_n->NOM_GE->EditAttributes() ?>>
			</span></td>
	</tr>
	<tr>
		<td><label for="x_NOM_PE" class="ewSearchCaption ewLabel">PUNTO DE ERRADICACIÓN</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_PE" id="z_NOM_PE" value="LIKE"></span></td>
		<td width="5%"></td>
		<td><span class="ewSearchField">
			<input type="text" data-field="x_NOM_PE" name="x_NOM_PE" id="x_NOM_PE" size="35" placeholder="<?php echo ew_HtmlEncode($view_e_y_n->NOM_PE->PlaceHolder) ?>" value="<?php echo $view_e_y_n->NOM_PE->EditValue ?>"<?php echo $view_e_y_n->NOM_PE->EditAttributes() ?>>
			</span></td>
	</tr>
</table>

<br>

<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>

<?php if ($view_e_y_n->USUARIO->Visible) { // USUARIO ?>
<?php } ?>
<?php if ($view_e_y_n->NOM_GE->Visible) { // NOM_GE ?>
<?php } ?>
<?php if ($view_e_y_n->NOM_PE->Visible) { // NOM_PE ?>
<?php } ?>

<br>
<br>
<hr>


	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $view_e_y_n_list->ShowPageHeader(); ?>
<?php
$view_e_y_n_list->ShowMessage();
?>
<?php if ($view_e_y_n_list->TotalRecs > 0 || $view_e_y_n->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($view_e_y_n->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($view_e_y_n->CurrentAction <> "gridadd" && $view_e_y_n->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_e_y_n_list->Pager)) $view_e_y_n_list->Pager = new cPrevNextPager($view_e_y_n_list->StartRec, $view_e_y_n_list->DisplayRecs, $view_e_y_n_list->TotalRecs) ?>
<?php if ($view_e_y_n_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_e_y_n_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_e_y_n_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_e_y_n_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_e_y_n_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_e_y_n_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_e_y_n_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview_e_y_nlist" id="fview_e_y_nlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_e_y_n_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_e_y_n_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_e_y_n">
<div id="gmp_view_e_y_n" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view_e_y_n_list->TotalRecs > 0) { ?>
<table id="tbl_view_e_y_nlist" class="table ewTable">
<?php echo $view_e_y_n->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_e_y_n->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view_e_y_n_list->RenderListOptions();

// Render list options (header, left)
$view_e_y_n_list->ListOptions->Render("header", "left");
?>
<?php if ($view_e_y_n->ID_Formulario->Visible) { // ID_Formulario ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->ID_Formulario) == "") { ?>
		<th data-name="ID_Formulario"><div id="elh_view_e_y_n_ID_Formulario" class="view_e_y_n_ID_Formulario"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->ID_Formulario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ID_Formulario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->ID_Formulario) ?>',2);"><div id="elh_view_e_y_n_ID_Formulario" class="view_e_y_n_ID_Formulario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->ID_Formulario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->ID_Formulario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->ID_Formulario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->F_Sincron->Visible) { // F_Sincron ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->F_Sincron) == "") { ?>
		<th data-name="F_Sincron"><div id="elh_view_e_y_n_F_Sincron" class="view_e_y_n_F_Sincron"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->F_Sincron->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F_Sincron"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->F_Sincron) ?>',2);"><div id="elh_view_e_y_n_F_Sincron" class="view_e_y_n_F_Sincron">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->F_Sincron->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->F_Sincron->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->F_Sincron->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->USUARIO->Visible) { // USUARIO ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->USUARIO) == "") { ?>
		<th data-name="USUARIO"><div id="elh_view_e_y_n_USUARIO" class="view_e_y_n_USUARIO"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->USUARIO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="USUARIO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->USUARIO) ?>',2);"><div id="elh_view_e_y_n_USUARIO" class="view_e_y_n_USUARIO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->USUARIO->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->USUARIO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->USUARIO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Cargo_gme->Visible) { // Cargo_gme ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Cargo_gme) == "") { ?>
		<th data-name="Cargo_gme"><div id="elh_view_e_y_n_Cargo_gme" class="view_e_y_n_Cargo_gme"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Cargo_gme->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_gme"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Cargo_gme) ?>',2);"><div id="elh_view_e_y_n_Cargo_gme" class="view_e_y_n_Cargo_gme">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Cargo_gme->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Cargo_gme->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Cargo_gme->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->NOM_GE->Visible) { // NOM_GE ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->NOM_GE) == "") { ?>
		<th data-name="NOM_GE"><div id="elh_view_e_y_n_NOM_GE" class="view_e_y_n_NOM_GE"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->NOM_GE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_GE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->NOM_GE) ?>',2);"><div id="elh_view_e_y_n_NOM_GE" class="view_e_y_n_NOM_GE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->NOM_GE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->NOM_GE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->NOM_GE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Otro_PGE->Visible) { // Otro_PGE ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Otro_PGE) == "") { ?>
		<th data-name="Otro_PGE"><div id="elh_view_e_y_n_Otro_PGE" class="view_e_y_n_Otro_PGE"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Otro_PGE) ?>',2);"><div id="elh_view_e_y_n_Otro_PGE" class="view_e_y_n_Otro_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_PGE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Otro_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Otro_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Otro_CC_PGE) == "") { ?>
		<th data-name="Otro_CC_PGE"><div id="elh_view_e_y_n_Otro_CC_PGE" class="view_e_y_n_Otro_CC_PGE"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_CC_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Otro_CC_PGE) ?>',2);"><div id="elh_view_e_y_n_Otro_CC_PGE" class="view_e_y_n_Otro_CC_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_CC_PGE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Otro_CC_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Otro_CC_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->TIPO_INFORME) == "") { ?>
		<th data-name="TIPO_INFORME"><div id="elh_view_e_y_n_TIPO_INFORME" class="view_e_y_n_TIPO_INFORME"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->TIPO_INFORME->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TIPO_INFORME"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->TIPO_INFORME) ?>',2);"><div id="elh_view_e_y_n_TIPO_INFORME" class="view_e_y_n_TIPO_INFORME">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->TIPO_INFORME->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->TIPO_INFORME->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->TIPO_INFORME->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->FECHA_NOVEDAD->Visible) { // FECHA_NOVEDAD ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->FECHA_NOVEDAD) == "") { ?>
		<th data-name="FECHA_NOVEDAD"><div id="elh_view_e_y_n_FECHA_NOVEDAD" class="view_e_y_n_FECHA_NOVEDAD"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->FECHA_NOVEDAD->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FECHA_NOVEDAD"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->FECHA_NOVEDAD) ?>',2);"><div id="elh_view_e_y_n_FECHA_NOVEDAD" class="view_e_y_n_FECHA_NOVEDAD">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->FECHA_NOVEDAD->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->FECHA_NOVEDAD->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->FECHA_NOVEDAD->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->DIA->Visible) { // DIA ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->DIA) == "") { ?>
		<th data-name="DIA"><div id="elh_view_e_y_n_DIA" class="view_e_y_n_DIA"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->DIA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DIA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->DIA) ?>',2);"><div id="elh_view_e_y_n_DIA" class="view_e_y_n_DIA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->DIA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->DIA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->DIA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->MES->Visible) { // MES ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->MES) == "") { ?>
		<th data-name="MES"><div id="elh_view_e_y_n_MES" class="view_e_y_n_MES"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->MES->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MES"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->MES) ?>',2);"><div id="elh_view_e_y_n_MES" class="view_e_y_n_MES">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->MES->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->MES->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->MES->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Num_Evacua->Visible) { // Num_Evacua ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Num_Evacua) == "") { ?>
		<th data-name="Num_Evacua"><div id="elh_view_e_y_n_Num_Evacua" class="view_e_y_n_Num_Evacua"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Num_Evacua->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Num_Evacua"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Num_Evacua) ?>',2);"><div id="elh_view_e_y_n_Num_Evacua" class="view_e_y_n_Num_Evacua">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Num_Evacua->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Num_Evacua->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Num_Evacua->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->PTO_INCOMU->Visible) { // PTO_INCOMU ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->PTO_INCOMU) == "") { ?>
		<th data-name="PTO_INCOMU"><div id="elh_view_e_y_n_PTO_INCOMU" class="view_e_y_n_PTO_INCOMU"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->PTO_INCOMU->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PTO_INCOMU"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->PTO_INCOMU) ?>',2);"><div id="elh_view_e_y_n_PTO_INCOMU" class="view_e_y_n_PTO_INCOMU">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->PTO_INCOMU->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->PTO_INCOMU->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->PTO_INCOMU->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->OBS_punt_inco->Visible) { // OBS_punt_inco ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->OBS_punt_inco) == "") { ?>
		<th data-name="OBS_punt_inco"><div id="elh_view_e_y_n_OBS_punt_inco" class="view_e_y_n_OBS_punt_inco"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->OBS_punt_inco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBS_punt_inco"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->OBS_punt_inco) ?>',2);"><div id="elh_view_e_y_n_OBS_punt_inco" class="view_e_y_n_OBS_punt_inco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->OBS_punt_inco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->OBS_punt_inco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->OBS_punt_inco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->OBS_ENLACE->Visible) { // OBS_ENLACE ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->OBS_ENLACE) == "") { ?>
		<th data-name="OBS_ENLACE"><div id="elh_view_e_y_n_OBS_ENLACE" class="view_e_y_n_OBS_ENLACE"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->OBS_ENLACE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBS_ENLACE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->OBS_ENLACE) ?>',2);"><div id="elh_view_e_y_n_OBS_ENLACE" class="view_e_y_n_OBS_ENLACE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->OBS_ENLACE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->OBS_ENLACE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->OBS_ENLACE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->NUM_Novedad->Visible) { // NUM_Novedad ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->NUM_Novedad) == "") { ?>
		<th data-name="NUM_Novedad"><div id="elh_view_e_y_n_NUM_Novedad" class="view_e_y_n_NUM_Novedad"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->NUM_Novedad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NUM_Novedad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->NUM_Novedad) ?>',2);"><div id="elh_view_e_y_n_NUM_Novedad" class="view_e_y_n_NUM_Novedad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->NUM_Novedad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->NUM_Novedad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->NUM_Novedad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Nom_Per_Evacu->Visible) { // Nom_Per_Evacu ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Nom_Per_Evacu) == "") { ?>
		<th data-name="Nom_Per_Evacu"><div id="elh_view_e_y_n_Nom_Per_Evacu" class="view_e_y_n_Nom_Per_Evacu"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Nom_Per_Evacu->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nom_Per_Evacu"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Nom_Per_Evacu) ?>',2);"><div id="elh_view_e_y_n_Nom_Per_Evacu" class="view_e_y_n_Nom_Per_Evacu">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Nom_Per_Evacu->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Nom_Per_Evacu->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Nom_Per_Evacu->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Nom_Otro_Per_Evacu->Visible) { // Nom_Otro_Per_Evacu ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Nom_Otro_Per_Evacu) == "") { ?>
		<th data-name="Nom_Otro_Per_Evacu"><div id="elh_view_e_y_n_Nom_Otro_Per_Evacu" class="view_e_y_n_Nom_Otro_Per_Evacu"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Nom_Otro_Per_Evacu->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nom_Otro_Per_Evacu"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Nom_Otro_Per_Evacu) ?>',2);"><div id="elh_view_e_y_n_Nom_Otro_Per_Evacu" class="view_e_y_n_Nom_Otro_Per_Evacu">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Nom_Otro_Per_Evacu->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Nom_Otro_Per_Evacu->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Nom_Otro_Per_Evacu->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->CC_Otro_Per_Evacu->Visible) { // CC_Otro_Per_Evacu ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->CC_Otro_Per_Evacu) == "") { ?>
		<th data-name="CC_Otro_Per_Evacu"><div id="elh_view_e_y_n_CC_Otro_Per_Evacu" class="view_e_y_n_CC_Otro_Per_Evacu"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->CC_Otro_Per_Evacu->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CC_Otro_Per_Evacu"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->CC_Otro_Per_Evacu) ?>',2);"><div id="elh_view_e_y_n_CC_Otro_Per_Evacu" class="view_e_y_n_CC_Otro_Per_Evacu">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->CC_Otro_Per_Evacu->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->CC_Otro_Per_Evacu->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->CC_Otro_Per_Evacu->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Cargo_Per_EVA->Visible) { // Cargo_Per_EVA ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Cargo_Per_EVA) == "") { ?>
		<th data-name="Cargo_Per_EVA"><div id="elh_view_e_y_n_Cargo_Per_EVA" class="view_e_y_n_Cargo_Per_EVA"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Cargo_Per_EVA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_Per_EVA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Cargo_Per_EVA) ?>',2);"><div id="elh_view_e_y_n_Cargo_Per_EVA" class="view_e_y_n_Cargo_Per_EVA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Cargo_Per_EVA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Cargo_Per_EVA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Cargo_Per_EVA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Motivo_Eva->Visible) { // Motivo_Eva ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Motivo_Eva) == "") { ?>
		<th data-name="Motivo_Eva"><div id="elh_view_e_y_n_Motivo_Eva" class="view_e_y_n_Motivo_Eva"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Motivo_Eva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Motivo_Eva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Motivo_Eva) ?>',2);"><div id="elh_view_e_y_n_Motivo_Eva" class="view_e_y_n_Motivo_Eva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Motivo_Eva->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Motivo_Eva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Motivo_Eva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->OBS_EVA->Visible) { // OBS_EVA ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->OBS_EVA) == "") { ?>
		<th data-name="OBS_EVA"><div id="elh_view_e_y_n_OBS_EVA" class="view_e_y_n_OBS_EVA"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->OBS_EVA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBS_EVA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->OBS_EVA) ?>',2);"><div id="elh_view_e_y_n_OBS_EVA" class="view_e_y_n_OBS_EVA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->OBS_EVA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->OBS_EVA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->OBS_EVA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->NOM_PE->Visible) { // NOM_PE ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->NOM_PE) == "") { ?>
		<th data-name="NOM_PE"><div id="elh_view_e_y_n_NOM_PE" class="view_e_y_n_NOM_PE"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->NOM_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->NOM_PE) ?>',2);"><div id="elh_view_e_y_n_NOM_PE" class="view_e_y_n_NOM_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->NOM_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->NOM_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->NOM_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Otro_Nom_PE->Visible) { // Otro_Nom_PE ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Otro_Nom_PE) == "") { ?>
		<th data-name="Otro_Nom_PE"><div id="elh_view_e_y_n_Otro_Nom_PE" class="view_e_y_n_Otro_Nom_PE"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_Nom_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Otro_Nom_PE) ?>',2);"><div id="elh_view_e_y_n_Otro_Nom_PE" class="view_e_y_n_Otro_Nom_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_Nom_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Otro_Nom_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Otro_Nom_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->NOM_CAPATAZ) == "") { ?>
		<th data-name="NOM_CAPATAZ"><div id="elh_view_e_y_n_NOM_CAPATAZ" class="view_e_y_n_NOM_CAPATAZ"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->NOM_CAPATAZ->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_CAPATAZ"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->NOM_CAPATAZ) ?>',2);"><div id="elh_view_e_y_n_NOM_CAPATAZ" class="view_e_y_n_NOM_CAPATAZ">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->NOM_CAPATAZ->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->NOM_CAPATAZ->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->NOM_CAPATAZ->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Otro_Nom_Capata->Visible) { // Otro_Nom_Capata ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Otro_Nom_Capata) == "") { ?>
		<th data-name="Otro_Nom_Capata"><div id="elh_view_e_y_n_Otro_Nom_Capata" class="view_e_y_n_Otro_Nom_Capata"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_Nom_Capata->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_Capata"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Otro_Nom_Capata) ?>',2);"><div id="elh_view_e_y_n_Otro_Nom_Capata" class="view_e_y_n_Otro_Nom_Capata">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_Nom_Capata->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Otro_Nom_Capata->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Otro_Nom_Capata->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Otro_CC_Capata->Visible) { // Otro_CC_Capata ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Otro_CC_Capata) == "") { ?>
		<th data-name="Otro_CC_Capata"><div id="elh_view_e_y_n_Otro_CC_Capata" class="view_e_y_n_Otro_CC_Capata"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_CC_Capata->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_Capata"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Otro_CC_Capata) ?>',2);"><div id="elh_view_e_y_n_Otro_CC_Capata" class="view_e_y_n_Otro_CC_Capata">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Otro_CC_Capata->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Otro_CC_Capata->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Otro_CC_Capata->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Muncipio->Visible) { // Muncipio ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Muncipio) == "") { ?>
		<th data-name="Muncipio"><div id="elh_view_e_y_n_Muncipio" class="view_e_y_n_Muncipio"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Muncipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Muncipio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Muncipio) ?>',2);"><div id="elh_view_e_y_n_Muncipio" class="view_e_y_n_Muncipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Muncipio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Muncipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Muncipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Departamento->Visible) { // Departamento ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Departamento) == "") { ?>
		<th data-name="Departamento"><div id="elh_view_e_y_n_Departamento" class="view_e_y_n_Departamento"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Departamento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Departamento) ?>',2);"><div id="elh_view_e_y_n_Departamento" class="view_e_y_n_Departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Departamento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->F_llegada->Visible) { // F_llegada ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->F_llegada) == "") { ?>
		<th data-name="F_llegada"><div id="elh_view_e_y_n_F_llegada" class="view_e_y_n_F_llegada"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->F_llegada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F_llegada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->F_llegada) ?>',2);"><div id="elh_view_e_y_n_F_llegada" class="view_e_y_n_F_llegada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->F_llegada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->F_llegada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->F_llegada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_e_y_n->Fecha->Visible) { // Fecha ?>
	<?php if ($view_e_y_n->SortUrl($view_e_y_n->Fecha) == "") { ?>
		<th data-name="Fecha"><div id="elh_view_e_y_n_Fecha" class="view_e_y_n_Fecha"><div class="ewTableHeaderCaption"><?php echo $view_e_y_n->Fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_e_y_n->SortUrl($view_e_y_n->Fecha) ?>',2);"><div id="elh_view_e_y_n_Fecha" class="view_e_y_n_Fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_e_y_n->Fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_e_y_n->Fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_e_y_n->Fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_e_y_n_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view_e_y_n->ExportAll && $view_e_y_n->Export <> "") {
	$view_e_y_n_list->StopRec = $view_e_y_n_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view_e_y_n_list->TotalRecs > $view_e_y_n_list->StartRec + $view_e_y_n_list->DisplayRecs - 1)
		$view_e_y_n_list->StopRec = $view_e_y_n_list->StartRec + $view_e_y_n_list->DisplayRecs - 1;
	else
		$view_e_y_n_list->StopRec = $view_e_y_n_list->TotalRecs;
}
$view_e_y_n_list->RecCnt = $view_e_y_n_list->StartRec - 1;
if ($view_e_y_n_list->Recordset && !$view_e_y_n_list->Recordset->EOF) {
	$view_e_y_n_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $view_e_y_n_list->StartRec > 1)
		$view_e_y_n_list->Recordset->Move($view_e_y_n_list->StartRec - 1);
} elseif (!$view_e_y_n->AllowAddDeleteRow && $view_e_y_n_list->StopRec == 0) {
	$view_e_y_n_list->StopRec = $view_e_y_n->GridAddRowCount;
}

// Initialize aggregate
$view_e_y_n->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_e_y_n->ResetAttrs();
$view_e_y_n_list->RenderRow();
while ($view_e_y_n_list->RecCnt < $view_e_y_n_list->StopRec) {
	$view_e_y_n_list->RecCnt++;
	if (intval($view_e_y_n_list->RecCnt) >= intval($view_e_y_n_list->StartRec)) {
		$view_e_y_n_list->RowCnt++;

		// Set up key count
		$view_e_y_n_list->KeyCount = $view_e_y_n_list->RowIndex;

		// Init row class and style
		$view_e_y_n->ResetAttrs();
		$view_e_y_n->CssClass = "";
		if ($view_e_y_n->CurrentAction == "gridadd") {
		} else {
			$view_e_y_n_list->LoadRowValues($view_e_y_n_list->Recordset); // Load row values
		}
		$view_e_y_n->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view_e_y_n->RowAttrs = array_merge($view_e_y_n->RowAttrs, array('data-rowindex'=>$view_e_y_n_list->RowCnt, 'id'=>'r' . $view_e_y_n_list->RowCnt . '_view_e_y_n', 'data-rowtype'=>$view_e_y_n->RowType));

		// Render row
		$view_e_y_n_list->RenderRow();

		// Render list options
		$view_e_y_n_list->RenderListOptions();
?>
	<tr<?php echo $view_e_y_n->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_e_y_n_list->ListOptions->Render("body", "left", $view_e_y_n_list->RowCnt);
?>
	<?php if ($view_e_y_n->ID_Formulario->Visible) { // ID_Formulario ?>
		<td data-name="ID_Formulario"<?php echo $view_e_y_n->ID_Formulario->CellAttributes() ?>>
<span<?php echo $view_e_y_n->ID_Formulario->ViewAttributes() ?>>
<?php echo $view_e_y_n->ID_Formulario->ListViewValue() ?></span>
<a id="<?php echo $view_e_y_n_list->PageObjName . "_row_" . $view_e_y_n_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_e_y_n->F_Sincron->Visible) { // F_Sincron ?>
		<td data-name="F_Sincron"<?php echo $view_e_y_n->F_Sincron->CellAttributes() ?>>
<span<?php echo $view_e_y_n->F_Sincron->ViewAttributes() ?>>
<?php echo $view_e_y_n->F_Sincron->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->USUARIO->Visible) { // USUARIO ?>
		<td data-name="USUARIO"<?php echo $view_e_y_n->USUARIO->CellAttributes() ?>>
<span<?php echo $view_e_y_n->USUARIO->ViewAttributes() ?>>
<?php echo $view_e_y_n->USUARIO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Cargo_gme->Visible) { // Cargo_gme ?>
		<td data-name="Cargo_gme"<?php echo $view_e_y_n->Cargo_gme->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_e_y_n->Cargo_gme->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->NOM_GE->Visible) { // NOM_GE ?>
		<td data-name="NOM_GE"<?php echo $view_e_y_n->NOM_GE->CellAttributes() ?>>
<span<?php echo $view_e_y_n->NOM_GE->ViewAttributes() ?>>
<?php echo $view_e_y_n->NOM_GE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Otro_PGE->Visible) { // Otro_PGE ?>
		<td data-name="Otro_PGE"<?php echo $view_e_y_n->Otro_PGE->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Otro_PGE->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<td data-name="Otro_CC_PGE"<?php echo $view_e_y_n->Otro_CC_PGE->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Otro_CC_PGE->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_CC_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
		<td data-name="TIPO_INFORME"<?php echo $view_e_y_n->TIPO_INFORME->CellAttributes() ?>>
<span<?php echo $view_e_y_n->TIPO_INFORME->ViewAttributes() ?>>
<?php echo $view_e_y_n->TIPO_INFORME->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->FECHA_NOVEDAD->Visible) { // FECHA_NOVEDAD ?>
		<td data-name="FECHA_NOVEDAD"<?php echo $view_e_y_n->FECHA_NOVEDAD->CellAttributes() ?>>
<span<?php echo $view_e_y_n->FECHA_NOVEDAD->ViewAttributes() ?>>
<?php echo $view_e_y_n->FECHA_NOVEDAD->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->DIA->Visible) { // DIA ?>
		<td data-name="DIA"<?php echo $view_e_y_n->DIA->CellAttributes() ?>>
<span<?php echo $view_e_y_n->DIA->ViewAttributes() ?>>
<?php echo $view_e_y_n->DIA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->MES->Visible) { // MES ?>
		<td data-name="MES"<?php echo $view_e_y_n->MES->CellAttributes() ?>>
<span<?php echo $view_e_y_n->MES->ViewAttributes() ?>>
<?php echo $view_e_y_n->MES->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Num_Evacua->Visible) { // Num_Evacua ?>
		<td data-name="Num_Evacua"<?php echo $view_e_y_n->Num_Evacua->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Num_Evacua->ViewAttributes() ?>>
<?php echo $view_e_y_n->Num_Evacua->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->PTO_INCOMU->Visible) { // PTO_INCOMU ?>
		<td data-name="PTO_INCOMU"<?php echo $view_e_y_n->PTO_INCOMU->CellAttributes() ?>>
<span<?php echo $view_e_y_n->PTO_INCOMU->ViewAttributes() ?>>
<?php echo $view_e_y_n->PTO_INCOMU->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->OBS_punt_inco->Visible) { // OBS_punt_inco ?>
		<td data-name="OBS_punt_inco"<?php echo $view_e_y_n->OBS_punt_inco->CellAttributes() ?>>
<span<?php echo $view_e_y_n->OBS_punt_inco->ViewAttributes() ?>>
<?php echo $view_e_y_n->OBS_punt_inco->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->OBS_ENLACE->Visible) { // OBS_ENLACE ?>
		<td data-name="OBS_ENLACE"<?php echo $view_e_y_n->OBS_ENLACE->CellAttributes() ?>>
<span<?php echo $view_e_y_n->OBS_ENLACE->ViewAttributes() ?>>
<?php echo $view_e_y_n->OBS_ENLACE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->NUM_Novedad->Visible) { // NUM_Novedad ?>
		<td data-name="NUM_Novedad"<?php echo $view_e_y_n->NUM_Novedad->CellAttributes() ?>>
<span<?php echo $view_e_y_n->NUM_Novedad->ViewAttributes() ?>>
<?php echo $view_e_y_n->NUM_Novedad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Nom_Per_Evacu->Visible) { // Nom_Per_Evacu ?>
		<td data-name="Nom_Per_Evacu"<?php echo $view_e_y_n->Nom_Per_Evacu->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Nom_Per_Evacu->ViewAttributes() ?>>
<?php echo $view_e_y_n->Nom_Per_Evacu->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Nom_Otro_Per_Evacu->Visible) { // Nom_Otro_Per_Evacu ?>
		<td data-name="Nom_Otro_Per_Evacu"<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->ViewAttributes() ?>>
<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->CC_Otro_Per_Evacu->Visible) { // CC_Otro_Per_Evacu ?>
		<td data-name="CC_Otro_Per_Evacu"<?php echo $view_e_y_n->CC_Otro_Per_Evacu->CellAttributes() ?>>
<span<?php echo $view_e_y_n->CC_Otro_Per_Evacu->ViewAttributes() ?>>
<?php echo $view_e_y_n->CC_Otro_Per_Evacu->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Cargo_Per_EVA->Visible) { // Cargo_Per_EVA ?>
		<td data-name="Cargo_Per_EVA"<?php echo $view_e_y_n->Cargo_Per_EVA->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Cargo_Per_EVA->ViewAttributes() ?>>
<?php echo $view_e_y_n->Cargo_Per_EVA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Motivo_Eva->Visible) { // Motivo_Eva ?>
		<td data-name="Motivo_Eva"<?php echo $view_e_y_n->Motivo_Eva->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Motivo_Eva->ViewAttributes() ?>>
<?php echo $view_e_y_n->Motivo_Eva->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->OBS_EVA->Visible) { // OBS_EVA ?>
		<td data-name="OBS_EVA"<?php echo $view_e_y_n->OBS_EVA->CellAttributes() ?>>
<span<?php echo $view_e_y_n->OBS_EVA->ViewAttributes() ?>>
<?php echo $view_e_y_n->OBS_EVA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->NOM_PE->Visible) { // NOM_PE ?>
		<td data-name="NOM_PE"<?php echo $view_e_y_n->NOM_PE->CellAttributes() ?>>
<span<?php echo $view_e_y_n->NOM_PE->ViewAttributes() ?>>
<?php echo $view_e_y_n->NOM_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Otro_Nom_PE->Visible) { // Otro_Nom_PE ?>
		<td data-name="Otro_Nom_PE"<?php echo $view_e_y_n->Otro_Nom_PE->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Otro_Nom_PE->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_Nom_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
		<td data-name="NOM_CAPATAZ"<?php echo $view_e_y_n->NOM_CAPATAZ->CellAttributes() ?>>
<span<?php echo $view_e_y_n->NOM_CAPATAZ->ViewAttributes() ?>>
<?php echo $view_e_y_n->NOM_CAPATAZ->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Otro_Nom_Capata->Visible) { // Otro_Nom_Capata ?>
		<td data-name="Otro_Nom_Capata"<?php echo $view_e_y_n->Otro_Nom_Capata->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Otro_Nom_Capata->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_Nom_Capata->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Otro_CC_Capata->Visible) { // Otro_CC_Capata ?>
		<td data-name="Otro_CC_Capata"<?php echo $view_e_y_n->Otro_CC_Capata->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Otro_CC_Capata->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_CC_Capata->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Muncipio->Visible) { // Muncipio ?>
		<td data-name="Muncipio"<?php echo $view_e_y_n->Muncipio->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Muncipio->ViewAttributes() ?>>
<?php echo $view_e_y_n->Muncipio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Departamento->Visible) { // Departamento ?>
		<td data-name="Departamento"<?php echo $view_e_y_n->Departamento->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Departamento->ViewAttributes() ?>>
<?php echo $view_e_y_n->Departamento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->F_llegada->Visible) { // F_llegada ?>
		<td data-name="F_llegada"<?php echo $view_e_y_n->F_llegada->CellAttributes() ?>>
<span<?php echo $view_e_y_n->F_llegada->ViewAttributes() ?>>
<?php echo $view_e_y_n->F_llegada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_e_y_n->Fecha->Visible) { // Fecha ?>
		<td data-name="Fecha"<?php echo $view_e_y_n->Fecha->CellAttributes() ?>>
<span<?php echo $view_e_y_n->Fecha->ViewAttributes() ?>>
<?php echo $view_e_y_n->Fecha->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_e_y_n_list->ListOptions->Render("body", "right", $view_e_y_n_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view_e_y_n->CurrentAction <> "gridadd")
		$view_e_y_n_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view_e_y_n->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view_e_y_n_list->Recordset)
	$view_e_y_n_list->Recordset->Close();
?>
<?php if ($view_e_y_n->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view_e_y_n->CurrentAction <> "gridadd" && $view_e_y_n->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_e_y_n_list->Pager)) $view_e_y_n_list->Pager = new cPrevNextPager($view_e_y_n_list->StartRec, $view_e_y_n_list->DisplayRecs, $view_e_y_n_list->TotalRecs) ?>
<?php if ($view_e_y_n_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_e_y_n_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_e_y_n_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_e_y_n_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_e_y_n_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_e_y_n_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_e_y_n_list->PageUrl() ?>start=<?php echo $view_e_y_n_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_e_y_n_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_e_y_n_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view_e_y_n_list->TotalRecs == 0 && $view_e_y_n->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_e_y_n_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view_e_y_n->Export == "") { ?>
<script type="text/javascript">
fview_e_y_nlistsrch.Init();
fview_e_y_nlist.Init();
</script>
<?php } ?>
<?php
$view_e_y_n_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view_e_y_n->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view_e_y_n_list->Page_Terminate();
?>
