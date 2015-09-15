<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "grafica_accidentes_trabajoinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$grafica_accidentes_trabajo_list = NULL; // Initialize page object first

class cgrafica_accidentes_trabajo_list extends cgrafica_accidentes_trabajo {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'grafica_accidentes_trabajo';

	// Page object name
	var $PageObjName = 'grafica_accidentes_trabajo_list';

	// Grid form hidden field names
	var $FormName = 'fgrafica_accidentes_trabajolist';
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

		// Table object (grafica_accidentes_trabajo)
		if (!isset($GLOBALS["grafica_accidentes_trabajo"]) || get_class($GLOBALS["grafica_accidentes_trabajo"]) == "cgrafica_accidentes_trabajo") {
			$GLOBALS["grafica_accidentes_trabajo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["grafica_accidentes_trabajo"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "grafica_accidentes_trabajoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "grafica_accidentes_trabajodelete.php";
		$this->MultiUpdateUrl = "grafica_accidentes_trabajoupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'grafica_accidentes_trabajo', TRUE);

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
		global $EW_EXPORT, $grafica_accidentes_trabajo;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($grafica_accidentes_trabajo);
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
		$this->BuildSearchSql($sWhere, $this->Profesional_especializado, $Default, FALSE); // Profesional_especializado
		$this->BuildSearchSql($sWhere, $this->Punto, $Default, FALSE); // Punto
		$this->BuildSearchSql($sWhere, $this->Cargo_Afectado, $Default, FALSE); // Cargo_Afectado
		$this->BuildSearchSql($sWhere, $this->Tipo_incidente, $Default, FALSE); // Tipo_incidente
		$this->BuildSearchSql($sWhere, $this->Evacuado, $Default, FALSE); // Evacuado
		$this->BuildSearchSql($sWhere, $this->No_evacuado, $Default, FALSE); // No_evacuado
		$this->BuildSearchSql($sWhere, $this->Total_Evacuados, $Default, FALSE); // Total_Evacuados

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Profesional_especializado->AdvancedSearch->Save(); // Profesional_especializado
			$this->Punto->AdvancedSearch->Save(); // Punto
			$this->Cargo_Afectado->AdvancedSearch->Save(); // Cargo_Afectado
			$this->Tipo_incidente->AdvancedSearch->Save(); // Tipo_incidente
			$this->Evacuado->AdvancedSearch->Save(); // Evacuado
			$this->No_evacuado->AdvancedSearch->Save(); // No_evacuado
			$this->Total_Evacuados->AdvancedSearch->Save(); // Total_Evacuados
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
		if ($this->Profesional_especializado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Punto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Cargo_Afectado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Tipo_incidente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Evacuado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->No_evacuado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Total_Evacuados->AdvancedSearch->IssetSession())
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
		$this->Profesional_especializado->AdvancedSearch->UnsetSession();
		$this->Punto->AdvancedSearch->UnsetSession();
		$this->Cargo_Afectado->AdvancedSearch->UnsetSession();
		$this->Tipo_incidente->AdvancedSearch->UnsetSession();
		$this->Evacuado->AdvancedSearch->UnsetSession();
		$this->No_evacuado->AdvancedSearch->UnsetSession();
		$this->Total_Evacuados->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Profesional_especializado->AdvancedSearch->Load();
		$this->Punto->AdvancedSearch->Load();
		$this->Cargo_Afectado->AdvancedSearch->Load();
		$this->Tipo_incidente->AdvancedSearch->Load();
		$this->Evacuado->AdvancedSearch->Load();
		$this->No_evacuado->AdvancedSearch->Load();
		$this->Total_Evacuados->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Profesional_especializado, $bCtrl); // Profesional_especializado
			$this->UpdateSort($this->Punto, $bCtrl); // Punto
			$this->UpdateSort($this->Cargo_Afectado, $bCtrl); // Cargo_Afectado
			$this->UpdateSort($this->Tipo_incidente, $bCtrl); // Tipo_incidente
			$this->UpdateSort($this->Evacuado, $bCtrl); // Evacuado
			$this->UpdateSort($this->No_evacuado, $bCtrl); // No_evacuado
			$this->UpdateSort($this->Total_Evacuados, $bCtrl); // Total_Evacuados
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
				$this->Profesional_especializado->setSort("");
				$this->Punto->setSort("");
				$this->Cargo_Afectado->setSort("");
				$this->Tipo_incidente->setSort("");
				$this->Evacuado->setSort("");
				$this->No_evacuado->setSort("");
				$this->Total_Evacuados->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgrafica_accidentes_trabajolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fgrafica_accidentes_trabajolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// Profesional_especializado

		$this->Profesional_especializado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Profesional_especializado"]);
		if ($this->Profesional_especializado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Profesional_especializado->AdvancedSearch->SearchOperator = @$_GET["z_Profesional_especializado"];

		// Punto
		$this->Punto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Punto"]);
		if ($this->Punto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Punto->AdvancedSearch->SearchOperator = @$_GET["z_Punto"];

		// Cargo_Afectado
		$this->Cargo_Afectado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Cargo_Afectado"]);
		if ($this->Cargo_Afectado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Cargo_Afectado->AdvancedSearch->SearchOperator = @$_GET["z_Cargo_Afectado"];

		// Tipo_incidente
		$this->Tipo_incidente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Tipo_incidente"]);
		if ($this->Tipo_incidente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Tipo_incidente->AdvancedSearch->SearchOperator = @$_GET["z_Tipo_incidente"];

		// Evacuado
		$this->Evacuado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Evacuado"]);
		if ($this->Evacuado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Evacuado->AdvancedSearch->SearchOperator = @$_GET["z_Evacuado"];

		// No_evacuado
		$this->No_evacuado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_No_evacuado"]);
		if ($this->No_evacuado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->No_evacuado->AdvancedSearch->SearchOperator = @$_GET["z_No_evacuado"];

		// Total_Evacuados
		$this->Total_Evacuados->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Total_Evacuados"]);
		if ($this->Total_Evacuados->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Total_Evacuados->AdvancedSearch->SearchOperator = @$_GET["z_Total_Evacuados"];
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
		$this->Profesional_especializado->setDbValue($rs->fields('Profesional_especializado'));
		$this->Punto->setDbValue($rs->fields('Punto'));
		$this->Cargo_Afectado->setDbValue($rs->fields('Cargo_Afectado'));
		$this->Tipo_incidente->setDbValue($rs->fields('Tipo_incidente'));
		$this->Evacuado->setDbValue($rs->fields('Evacuado'));
		$this->No_evacuado->setDbValue($rs->fields('No_evacuado'));
		$this->Total_Evacuados->setDbValue($rs->fields('Total_Evacuados'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Profesional_especializado->DbValue = $row['Profesional_especializado'];
		$this->Punto->DbValue = $row['Punto'];
		$this->Cargo_Afectado->DbValue = $row['Cargo_Afectado'];
		$this->Tipo_incidente->DbValue = $row['Tipo_incidente'];
		$this->Evacuado->DbValue = $row['Evacuado'];
		$this->No_evacuado->DbValue = $row['No_evacuado'];
		$this->Total_Evacuados->DbValue = $row['Total_Evacuados'];
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
		if ($this->No_evacuado->FormValue == $this->No_evacuado->CurrentValue && is_numeric(ew_StrToFloat($this->No_evacuado->CurrentValue)))
			$this->No_evacuado->CurrentValue = ew_StrToFloat($this->No_evacuado->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Profesional_especializado
		// Punto
		// Cargo_Afectado
		// Tipo_incidente
		// Evacuado
		// No_evacuado
		// Total_Evacuados

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Profesional_especializado
			if (strval($this->Profesional_especializado->CurrentValue) <> "") {
				$sFilterWrk = "`Profesional_especializado`" . ew_SearchString("=", $this->Profesional_especializado->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Profesional_especializado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Profesional_especializado` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Profesional_especializado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Profesional_especializado->ViewValue = $this->Profesional_especializado->CurrentValue;
				}
			} else {
				$this->Profesional_especializado->ViewValue = NULL;
			}
			$this->Profesional_especializado->ViewCustomAttributes = "";

			// Punto
			if (strval($this->Punto->CurrentValue) <> "") {
				$sFilterWrk = "`Punto`" . ew_SearchString("=", $this->Punto->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Punto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Punto` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Punto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Punto->ViewValue = $this->Punto->CurrentValue;
				}
			} else {
				$this->Punto->ViewValue = NULL;
			}
			$this->Punto->ViewCustomAttributes = "";

			// Cargo_Afectado
			if (strval($this->Cargo_Afectado->CurrentValue) <> "") {
				$sFilterWrk = "`Cargo_Afectado`" . ew_SearchString("=", $this->Cargo_Afectado->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Cargo_Afectado`, `Cargo_Afectado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Cargo_Afectado`, `Cargo_Afectado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Cargo_Afectado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Cargo_Afectado` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Cargo_Afectado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Cargo_Afectado->ViewValue = $this->Cargo_Afectado->CurrentValue;
				}
			} else {
				$this->Cargo_Afectado->ViewValue = NULL;
			}
			$this->Cargo_Afectado->ViewCustomAttributes = "";

			// Tipo_incidente
			if (strval($this->Tipo_incidente->CurrentValue) <> "") {
				$sFilterWrk = "`Tipo_incidente`" . ew_SearchString("=", $this->Tipo_incidente->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Tipo_incidente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_incidente` ASC";
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

			// Evacuado
			if (strval($this->Evacuado->CurrentValue) <> "") {
				$sFilterWrk = "`Evacuado`" . ew_SearchString("=", $this->Evacuado->CurrentValue, EW_DATATYPE_NUMBER);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Evacuado`, `Evacuado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Evacuado`, `Evacuado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Evacuado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Evacuado` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Evacuado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Evacuado->ViewValue = $this->Evacuado->CurrentValue;
				}
			} else {
				$this->Evacuado->ViewValue = NULL;
			}
			$this->Evacuado->ViewCustomAttributes = "";

			// No_evacuado
			$this->No_evacuado->ViewValue = $this->No_evacuado->CurrentValue;
			$this->No_evacuado->ViewCustomAttributes = "";

			// Total_Evacuados
			$this->Total_Evacuados->ViewValue = $this->Total_Evacuados->CurrentValue;
			$this->Total_Evacuados->ViewCustomAttributes = "";

			// Profesional_especializado
			$this->Profesional_especializado->LinkCustomAttributes = "";
			$this->Profesional_especializado->HrefValue = "";
			$this->Profesional_especializado->TooltipValue = "";

			// Punto
			$this->Punto->LinkCustomAttributes = "";
			$this->Punto->HrefValue = "";
			$this->Punto->TooltipValue = "";

			// Cargo_Afectado
			$this->Cargo_Afectado->LinkCustomAttributes = "";
			$this->Cargo_Afectado->HrefValue = "";
			$this->Cargo_Afectado->TooltipValue = "";

			// Tipo_incidente
			$this->Tipo_incidente->LinkCustomAttributes = "";
			$this->Tipo_incidente->HrefValue = "";
			$this->Tipo_incidente->TooltipValue = "";

			// Evacuado
			$this->Evacuado->LinkCustomAttributes = "";
			$this->Evacuado->HrefValue = "";
			$this->Evacuado->TooltipValue = "";

			// No_evacuado
			$this->No_evacuado->LinkCustomAttributes = "";
			$this->No_evacuado->HrefValue = "";
			$this->No_evacuado->TooltipValue = "";

			// Total_Evacuados
			$this->Total_Evacuados->LinkCustomAttributes = "";
			$this->Total_Evacuados->HrefValue = "";
			$this->Total_Evacuados->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Profesional_especializado
			$this->Profesional_especializado->EditAttrs["class"] = "form-control";
			$this->Profesional_especializado->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Profesional_especializado`, `Profesional_especializado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Profesional_especializado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Profesional_especializado` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Profesional_especializado->EditValue = $arwrk;

			// Punto
			$this->Punto->EditAttrs["class"] = "form-control";
			$this->Punto->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Punto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Punto` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Punto->EditValue = $arwrk;

			// Cargo_Afectado
			$this->Cargo_Afectado->EditAttrs["class"] = "form-control";
			$this->Cargo_Afectado->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Cargo_Afectado`, `Cargo_Afectado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Cargo_Afectado`, `Cargo_Afectado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Cargo_Afectado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Cargo_Afectado` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Cargo_Afectado->EditValue = $arwrk;

			// Tipo_incidente
			$this->Tipo_incidente->EditAttrs["class"] = "form-control";
			$this->Tipo_incidente->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Tipo_incidente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Tipo_incidente` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Tipo_incidente->EditValue = $arwrk;

			// Evacuado
			$this->Evacuado->EditAttrs["class"] = "form-control";
			$this->Evacuado->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Evacuado`, `Evacuado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Evacuado`, `Evacuado` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_accidentes_trabajo`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Evacuado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Evacuado` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Evacuado->EditValue = $arwrk;

			// No_evacuado
			$this->No_evacuado->EditAttrs["class"] = "form-control";
			$this->No_evacuado->EditCustomAttributes = "";
			$this->No_evacuado->EditValue = ew_HtmlEncode($this->No_evacuado->AdvancedSearch->SearchValue);
			$this->No_evacuado->PlaceHolder = ew_RemoveHtml($this->No_evacuado->FldCaption());

			// Total_Evacuados
			$this->Total_Evacuados->EditAttrs["class"] = "form-control";
			$this->Total_Evacuados->EditCustomAttributes = "";
			$this->Total_Evacuados->EditValue = ew_HtmlEncode($this->Total_Evacuados->AdvancedSearch->SearchValue);
			$this->Total_Evacuados->PlaceHolder = ew_RemoveHtml($this->Total_Evacuados->FldCaption());
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
		$this->Profesional_especializado->AdvancedSearch->Load();
		$this->Punto->AdvancedSearch->Load();
		$this->Cargo_Afectado->AdvancedSearch->Load();
		$this->Tipo_incidente->AdvancedSearch->Load();
		$this->Evacuado->AdvancedSearch->Load();
		$this->No_evacuado->AdvancedSearch->Load();
		$this->Total_Evacuados->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_grafica_accidentes_trabajo\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_grafica_accidentes_trabajo',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fgrafica_accidentes_trabajolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($grafica_accidentes_trabajo_list)) $grafica_accidentes_trabajo_list = new cgrafica_accidentes_trabajo_list();

// Page init
$grafica_accidentes_trabajo_list->Page_Init();

// Page main
$grafica_accidentes_trabajo_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$grafica_accidentes_trabajo_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<script type="text/javascript">

// Page object
var grafica_accidentes_trabajo_list = new ew_Page("grafica_accidentes_trabajo_list");
grafica_accidentes_trabajo_list.PageID = "list"; // Page ID
var EW_PAGE_ID = grafica_accidentes_trabajo_list.PageID; // For backward compatibility

// Form object
var fgrafica_accidentes_trabajolist = new ew_Form("fgrafica_accidentes_trabajolist");
fgrafica_accidentes_trabajolist.FormKeyCountName = '<?php echo $grafica_accidentes_trabajo_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgrafica_accidentes_trabajolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_accidentes_trabajolist.ValidateRequired = true;
<?php } else { ?>
fgrafica_accidentes_trabajolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgrafica_accidentes_trabajolist.Lists["x_Profesional_especializado"] = {"LinkField":"x_Profesional_especializado","Ajax":null,"AutoFill":false,"DisplayFields":["x_Profesional_especializado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolist.Lists["x_Punto"] = {"LinkField":"x_Punto","Ajax":null,"AutoFill":false,"DisplayFields":["x_Punto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolist.Lists["x_Cargo_Afectado"] = {"LinkField":"x_Cargo_Afectado","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Afectado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolist.Lists["x_Tipo_incidente"] = {"LinkField":"x_Tipo_incidente","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_incidente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolist.Lists["x_Evacuado"] = {"LinkField":"x_Evacuado","Ajax":null,"AutoFill":false,"DisplayFields":["x_Evacuado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fgrafica_accidentes_trabajolistsrch = new ew_Form("fgrafica_accidentes_trabajolistsrch");

// Validate function for search
fgrafica_accidentes_trabajolistsrch.Validate = function(fobj) {
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
fgrafica_accidentes_trabajolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_accidentes_trabajolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgrafica_accidentes_trabajolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fgrafica_accidentes_trabajolistsrch.Lists["x_Profesional_especializado"] = {"LinkField":"x_Profesional_especializado","Ajax":null,"AutoFill":false,"DisplayFields":["x_Profesional_especializado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolistsrch.Lists["x_Punto"] = {"LinkField":"x_Punto","Ajax":null,"AutoFill":false,"DisplayFields":["x_Punto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolistsrch.Lists["x_Cargo_Afectado"] = {"LinkField":"x_Cargo_Afectado","Ajax":null,"AutoFill":false,"DisplayFields":["x_Cargo_Afectado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolistsrch.Lists["x_Tipo_incidente"] = {"LinkField":"x_Tipo_incidente","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_incidente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_accidentes_trabajolistsrch.Lists["x_Evacuado"] = {"LinkField":"x_Evacuado","Ajax":null,"AutoFill":false,"DisplayFields":["x_Evacuado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<div class="ewToolbar">
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<div class="ewToolbar">
<H2> Reporte de accidentes</h2>
<p>La siguiente tabla contiene los reportes de accidentes reportdados por los apoyos zonales e indica el cargo de la persona afectada y el tipo de incidente que se presento</p>

<hr>
<table>
	<tr>
		<td>
			<?php if ($grafica_accidentes_trabajo_list->TotalRecs > 0 && $grafica_accidentes_trabajo_list->ExportOptions->Visible()) { ?>

			<?php $grafica_accidentes_trabajo_list->ExportOptions->Render("body") ?>
			<?php } ?>

		</td>
		<td>
			Si desea exportar la tabla en formato excel haga click en el siguiente icono 
		</td>	
	</tr>	
</table> 

<hr>

</div>
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>

<div>
<br>
<table>
	<tr>
		<td>
			<?php if ($grafica_accidentes_trabajo_list->SearchOptions->Visible()) { ?>
			<?php $grafica_accidentes_trabajo_list->SearchOptions->Render("body") ?>
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
		if ($grafica_accidentes_trabajo_list->TotalRecs <= 0)
			$grafica_accidentes_trabajo_list->TotalRecs = $grafica_accidentes_trabajo->SelectRecordCount();
	} else {
		if (!$grafica_accidentes_trabajo_list->Recordset && ($grafica_accidentes_trabajo_list->Recordset = $grafica_accidentes_trabajo_list->LoadRecordset()))
			$grafica_accidentes_trabajo_list->TotalRecs = $grafica_accidentes_trabajo_list->Recordset->RecordCount();
	}
	$grafica_accidentes_trabajo_list->StartRec = 1;
	if ($grafica_accidentes_trabajo_list->DisplayRecs <= 0 || ($grafica_accidentes_trabajo->Export <> "" && $grafica_accidentes_trabajo->ExportAll)) // Display all records
		$grafica_accidentes_trabajo_list->DisplayRecs = $grafica_accidentes_trabajo_list->TotalRecs;
	if (!($grafica_accidentes_trabajo->Export <> "" && $grafica_accidentes_trabajo->ExportAll))
		$grafica_accidentes_trabajo_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$grafica_accidentes_trabajo_list->Recordset = $grafica_accidentes_trabajo_list->LoadRecordset($grafica_accidentes_trabajo_list->StartRec-1, $grafica_accidentes_trabajo_list->DisplayRecs);

	// Set no record found message
	if ($grafica_accidentes_trabajo->CurrentAction == "" && $grafica_accidentes_trabajo_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$grafica_accidentes_trabajo_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($grafica_accidentes_trabajo_list->SearchWhere == "0=101")
			$grafica_accidentes_trabajo_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$grafica_accidentes_trabajo_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$grafica_accidentes_trabajo_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($grafica_accidentes_trabajo->Export == "" && $grafica_accidentes_trabajo->CurrentAction == "") { ?>
<form name="fgrafica_accidentes_trabajolistsrch" id="fgrafica_accidentes_trabajolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($grafica_accidentes_trabajo_list->SearchWhere <> "") ? " " : " "; ?>
<div id="fgrafica_accidentes_trabajolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="grafica_accidentes_trabajo">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$grafica_accidentes_trabajo_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$grafica_accidentes_trabajo->RowType = EW_ROWTYPE_SEARCH;

// Render row
$grafica_accidentes_trabajo->ResetAttrs();
$grafica_accidentes_trabajo_list->RenderRow();
?>
<br>
<table>
	<tr>
		<td>
			<label for="x_Profesional_especializado" class="ewSearchCaption ewLabel"><?php echo $grafica_accidentes_trabajo->Profesional_especializado->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Profesional_especializado" id="z_Profesional_especializado" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_Profesional_especializado" id="x_Profesional_especializado" name="x_Profesional_especializado"<?php echo $grafica_accidentes_trabajo->Profesional_especializado->EditAttributes() ?>>
				<?php
				if (is_array($grafica_accidentes_trabajo->Profesional_especializado->EditValue)) {
					$arwrk = $grafica_accidentes_trabajo->Profesional_especializado->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($grafica_accidentes_trabajo->Profesional_especializado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fgrafica_accidentes_trabajolistsrch.Lists["x_Profesional_especializado"].Options = <?php echo (is_array($grafica_accidentes_trabajo->Profesional_especializado->EditValue)) ? ew_ArrayToJson($grafica_accidentes_trabajo->Profesional_especializado->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
		<tr>
		<td>
			<label for="x_Punto" class="ewSearchCaption ewLabel"><?php echo $grafica_accidentes_trabajo->Punto->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Punto" id="z_Punto" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_Punto" id="x_Punto" name="x_Punto"<?php echo $grafica_accidentes_trabajo->Punto->EditAttributes() ?>>
				<?php
				if (is_array($grafica_accidentes_trabajo->Punto->EditValue)) {
					$arwrk = $grafica_accidentes_trabajo->Punto->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($grafica_accidentes_trabajo->Punto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fgrafica_accidentes_trabajolistsrch.Lists["x_Punto"].Options = <?php echo (is_array($grafica_accidentes_trabajo->Punto->EditValue)) ? ew_ArrayToJson($grafica_accidentes_trabajo->Punto->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	</tr>
		<tr>
		<td>
			<label for="x_Cargo_Afectado" class="ewSearchCaption ewLabel"><?php echo $grafica_accidentes_trabajo->Cargo_Afectado->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Cargo_Afectado" id="z_Cargo_Afectado" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_Cargo_Afectado" id="x_Cargo_Afectado" name="x_Cargo_Afectado"<?php echo $grafica_accidentes_trabajo->Cargo_Afectado->EditAttributes() ?>>
				<?php
				if (is_array($grafica_accidentes_trabajo->Cargo_Afectado->EditValue)) {
					$arwrk = $grafica_accidentes_trabajo->Cargo_Afectado->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($grafica_accidentes_trabajo->Cargo_Afectado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fgrafica_accidentes_trabajolistsrch.Lists["x_Cargo_Afectado"].Options = <?php echo (is_array($grafica_accidentes_trabajo->Cargo_Afectado->EditValue)) ? ew_ArrayToJson($grafica_accidentes_trabajo->Cargo_Afectado->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_Tipo_incidente" class="ewSearchCaption ewLabel"><?php echo $grafica_accidentes_trabajo->Tipo_incidente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Tipo_incidente" id="z_Tipo_incidente" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_Tipo_incidente" id="x_Tipo_incidente" name="x_Tipo_incidente"<?php echo $grafica_accidentes_trabajo->Tipo_incidente->EditAttributes() ?>>
				<?php
				if (is_array($grafica_accidentes_trabajo->Tipo_incidente->EditValue)) {
					$arwrk = $grafica_accidentes_trabajo->Tipo_incidente->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($grafica_accidentes_trabajo->Tipo_incidente->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fgrafica_accidentes_trabajolistsrch.Lists["x_Tipo_incidente"].Options = <?php echo (is_array($grafica_accidentes_trabajo->Tipo_incidente->EditValue)) ? ew_ArrayToJson($grafica_accidentes_trabajo->Tipo_incidente->EditValue, 1) : "[]" ?>;
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
<?php $grafica_accidentes_trabajo_list->ShowPageHeader(); ?>
<?php
$grafica_accidentes_trabajo_list->ShowMessage();
?>
<?php if ($grafica_accidentes_trabajo_list->TotalRecs > 0 || $grafica_accidentes_trabajo->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($grafica_accidentes_trabajo->CurrentAction <> "gridadd" && $grafica_accidentes_trabajo->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_accidentes_trabajo_list->Pager)) $grafica_accidentes_trabajo_list->Pager = new cPrevNextPager($grafica_accidentes_trabajo_list->StartRec, $grafica_accidentes_trabajo_list->DisplayRecs, $grafica_accidentes_trabajo_list->TotalRecs) ?>
<?php if ($grafica_accidentes_trabajo_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_accidentes_trabajo_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_accidentes_trabajo_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fgrafica_accidentes_trabajolist" id="fgrafica_accidentes_trabajolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($grafica_accidentes_trabajo_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $grafica_accidentes_trabajo_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="grafica_accidentes_trabajo">
<div id="gmp_grafica_accidentes_trabajo" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($grafica_accidentes_trabajo_list->TotalRecs > 0) { ?>
<table id="tbl_grafica_accidentes_trabajolist" class="table ewTable">
<?php echo $grafica_accidentes_trabajo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$grafica_accidentes_trabajo->RowType = EW_ROWTYPE_HEADER;

// Render list options
$grafica_accidentes_trabajo_list->RenderListOptions();

// Render list options (header, left)
$grafica_accidentes_trabajo_list->ListOptions->Render("header", "left");
?>
<?php if ($grafica_accidentes_trabajo->Profesional_especializado->Visible) { // Profesional_especializado ?>
	<?php if ($grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Profesional_especializado) == "") { ?>
		<th data-name="Profesional_especializado"><div id="elh_grafica_accidentes_trabajo_Profesional_especializado" class="grafica_accidentes_trabajo_Profesional_especializado"><div class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Profesional_especializado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Profesional_especializado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Profesional_especializado) ?>',2);"><div id="elh_grafica_accidentes_trabajo_Profesional_especializado" class="grafica_accidentes_trabajo_Profesional_especializado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Profesional_especializado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_accidentes_trabajo->Profesional_especializado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_accidentes_trabajo->Profesional_especializado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_accidentes_trabajo->Punto->Visible) { // Punto ?>
	<?php if ($grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Punto) == "") { ?>
		<th data-name="Punto"><div id="elh_grafica_accidentes_trabajo_Punto" class="grafica_accidentes_trabajo_Punto"><div class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Punto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Punto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Punto) ?>',2);"><div id="elh_grafica_accidentes_trabajo_Punto" class="grafica_accidentes_trabajo_Punto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Punto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_accidentes_trabajo->Punto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_accidentes_trabajo->Punto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_accidentes_trabajo->Cargo_Afectado->Visible) { // Cargo_Afectado ?>
	<?php if ($grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Cargo_Afectado) == "") { ?>
		<th data-name="Cargo_Afectado"><div id="elh_grafica_accidentes_trabajo_Cargo_Afectado" class="grafica_accidentes_trabajo_Cargo_Afectado"><div class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Cargo_Afectado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_Afectado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Cargo_Afectado) ?>',2);"><div id="elh_grafica_accidentes_trabajo_Cargo_Afectado" class="grafica_accidentes_trabajo_Cargo_Afectado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Cargo_Afectado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_accidentes_trabajo->Cargo_Afectado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_accidentes_trabajo->Cargo_Afectado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_accidentes_trabajo->Tipo_incidente->Visible) { // Tipo_incidente ?>
	<?php if ($grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Tipo_incidente) == "") { ?>
		<th data-name="Tipo_incidente"><div id="elh_grafica_accidentes_trabajo_Tipo_incidente" class="grafica_accidentes_trabajo_Tipo_incidente"><div class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Tipo_incidente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tipo_incidente"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Tipo_incidente) ?>',2);"><div id="elh_grafica_accidentes_trabajo_Tipo_incidente" class="grafica_accidentes_trabajo_Tipo_incidente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Tipo_incidente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_accidentes_trabajo->Tipo_incidente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_accidentes_trabajo->Tipo_incidente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_accidentes_trabajo->Evacuado->Visible) { // Evacuado ?>
	<?php if ($grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Evacuado) == "") { ?>
		<th data-name="Evacuado"><div id="elh_grafica_accidentes_trabajo_Evacuado" class="grafica_accidentes_trabajo_Evacuado"><div class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Evacuado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Evacuado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Evacuado) ?>',2);"><div id="elh_grafica_accidentes_trabajo_Evacuado" class="grafica_accidentes_trabajo_Evacuado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Evacuado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_accidentes_trabajo->Evacuado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_accidentes_trabajo->Evacuado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_accidentes_trabajo->No_evacuado->Visible) { // No_evacuado ?>
	<?php if ($grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->No_evacuado) == "") { ?>
		<th data-name="No_evacuado"><div id="elh_grafica_accidentes_trabajo_No_evacuado" class="grafica_accidentes_trabajo_No_evacuado"><div class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->No_evacuado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="No_evacuado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->No_evacuado) ?>',2);"><div id="elh_grafica_accidentes_trabajo_No_evacuado" class="grafica_accidentes_trabajo_No_evacuado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->No_evacuado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_accidentes_trabajo->No_evacuado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_accidentes_trabajo->No_evacuado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_accidentes_trabajo->Total_Evacuados->Visible) { // Total_Evacuados ?>
	<?php if ($grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Total_Evacuados) == "") { ?>
		<th data-name="Total_Evacuados"><div id="elh_grafica_accidentes_trabajo_Total_Evacuados" class="grafica_accidentes_trabajo_Total_Evacuados"><div class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Total_Evacuados->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_Evacuados"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_accidentes_trabajo->SortUrl($grafica_accidentes_trabajo->Total_Evacuados) ?>',2);"><div id="elh_grafica_accidentes_trabajo_Total_Evacuados" class="grafica_accidentes_trabajo_Total_Evacuados">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_accidentes_trabajo->Total_Evacuados->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_accidentes_trabajo->Total_Evacuados->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_accidentes_trabajo->Total_Evacuados->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$grafica_accidentes_trabajo_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($grafica_accidentes_trabajo->ExportAll && $grafica_accidentes_trabajo->Export <> "") {
	$grafica_accidentes_trabajo_list->StopRec = $grafica_accidentes_trabajo_list->TotalRecs;
} else {

	// Set the last record to display
	if ($grafica_accidentes_trabajo_list->TotalRecs > $grafica_accidentes_trabajo_list->StartRec + $grafica_accidentes_trabajo_list->DisplayRecs - 1)
		$grafica_accidentes_trabajo_list->StopRec = $grafica_accidentes_trabajo_list->StartRec + $grafica_accidentes_trabajo_list->DisplayRecs - 1;
	else
		$grafica_accidentes_trabajo_list->StopRec = $grafica_accidentes_trabajo_list->TotalRecs;
}
$grafica_accidentes_trabajo_list->RecCnt = $grafica_accidentes_trabajo_list->StartRec - 1;
if ($grafica_accidentes_trabajo_list->Recordset && !$grafica_accidentes_trabajo_list->Recordset->EOF) {
	$grafica_accidentes_trabajo_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $grafica_accidentes_trabajo_list->StartRec > 1)
		$grafica_accidentes_trabajo_list->Recordset->Move($grafica_accidentes_trabajo_list->StartRec - 1);
} elseif (!$grafica_accidentes_trabajo->AllowAddDeleteRow && $grafica_accidentes_trabajo_list->StopRec == 0) {
	$grafica_accidentes_trabajo_list->StopRec = $grafica_accidentes_trabajo->GridAddRowCount;
}

// Initialize aggregate
$grafica_accidentes_trabajo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$grafica_accidentes_trabajo->ResetAttrs();
$grafica_accidentes_trabajo_list->RenderRow();
while ($grafica_accidentes_trabajo_list->RecCnt < $grafica_accidentes_trabajo_list->StopRec) {
	$grafica_accidentes_trabajo_list->RecCnt++;
	if (intval($grafica_accidentes_trabajo_list->RecCnt) >= intval($grafica_accidentes_trabajo_list->StartRec)) {
		$grafica_accidentes_trabajo_list->RowCnt++;

		// Set up key count
		$grafica_accidentes_trabajo_list->KeyCount = $grafica_accidentes_trabajo_list->RowIndex;

		// Init row class and style
		$grafica_accidentes_trabajo->ResetAttrs();
		$grafica_accidentes_trabajo->CssClass = "";
		if ($grafica_accidentes_trabajo->CurrentAction == "gridadd") {
		} else {
			$grafica_accidentes_trabajo_list->LoadRowValues($grafica_accidentes_trabajo_list->Recordset); // Load row values
		}
		$grafica_accidentes_trabajo->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$grafica_accidentes_trabajo->RowAttrs = array_merge($grafica_accidentes_trabajo->RowAttrs, array('data-rowindex'=>$grafica_accidentes_trabajo_list->RowCnt, 'id'=>'r' . $grafica_accidentes_trabajo_list->RowCnt . '_grafica_accidentes_trabajo', 'data-rowtype'=>$grafica_accidentes_trabajo->RowType));

		// Render row
		$grafica_accidentes_trabajo_list->RenderRow();

		// Render list options
		$grafica_accidentes_trabajo_list->RenderListOptions();
?>
	<tr<?php echo $grafica_accidentes_trabajo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$grafica_accidentes_trabajo_list->ListOptions->Render("body", "left", $grafica_accidentes_trabajo_list->RowCnt);
?>
	<?php if ($grafica_accidentes_trabajo->Profesional_especializado->Visible) { // Profesional_especializado ?>
		<td data-name="Profesional_especializado"<?php echo $grafica_accidentes_trabajo->Profesional_especializado->CellAttributes() ?>>
<span<?php echo $grafica_accidentes_trabajo->Profesional_especializado->ViewAttributes() ?>>
<?php echo $grafica_accidentes_trabajo->Profesional_especializado->ListViewValue() ?></span>
<a id="<?php echo $grafica_accidentes_trabajo_list->PageObjName . "_row_" . $grafica_accidentes_trabajo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($grafica_accidentes_trabajo->Punto->Visible) { // Punto ?>
		<td data-name="Punto"<?php echo $grafica_accidentes_trabajo->Punto->CellAttributes() ?>>
<span<?php echo $grafica_accidentes_trabajo->Punto->ViewAttributes() ?>>
<?php echo $grafica_accidentes_trabajo->Punto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_accidentes_trabajo->Cargo_Afectado->Visible) { // Cargo_Afectado ?>
		<td data-name="Cargo_Afectado"<?php echo $grafica_accidentes_trabajo->Cargo_Afectado->CellAttributes() ?>>
<span<?php echo $grafica_accidentes_trabajo->Cargo_Afectado->ViewAttributes() ?>>
<?php echo $grafica_accidentes_trabajo->Cargo_Afectado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_accidentes_trabajo->Tipo_incidente->Visible) { // Tipo_incidente ?>
		<td data-name="Tipo_incidente"<?php echo $grafica_accidentes_trabajo->Tipo_incidente->CellAttributes() ?>>
<span<?php echo $grafica_accidentes_trabajo->Tipo_incidente->ViewAttributes() ?>>
<?php echo $grafica_accidentes_trabajo->Tipo_incidente->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_accidentes_trabajo->Evacuado->Visible) { // Evacuado ?>
		<td data-name="Evacuado"<?php echo $grafica_accidentes_trabajo->Evacuado->CellAttributes() ?>>
<span<?php echo $grafica_accidentes_trabajo->Evacuado->ViewAttributes() ?>>
<?php echo $grafica_accidentes_trabajo->Evacuado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_accidentes_trabajo->No_evacuado->Visible) { // No_evacuado ?>
		<td data-name="No_evacuado"<?php echo $grafica_accidentes_trabajo->No_evacuado->CellAttributes() ?>>
<span<?php echo $grafica_accidentes_trabajo->No_evacuado->ViewAttributes() ?>>
<?php echo $grafica_accidentes_trabajo->No_evacuado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_accidentes_trabajo->Total_Evacuados->Visible) { // Total_Evacuados ?>
		<td data-name="Total_Evacuados"<?php echo $grafica_accidentes_trabajo->Total_Evacuados->CellAttributes() ?>>
<span<?php echo $grafica_accidentes_trabajo->Total_Evacuados->ViewAttributes() ?>>
<?php echo $grafica_accidentes_trabajo->Total_Evacuados->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$grafica_accidentes_trabajo_list->ListOptions->Render("body", "right", $grafica_accidentes_trabajo_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($grafica_accidentes_trabajo->CurrentAction <> "gridadd")
		$grafica_accidentes_trabajo_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($grafica_accidentes_trabajo->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($grafica_accidentes_trabajo_list->Recordset)
	$grafica_accidentes_trabajo_list->Recordset->Close();
?>
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($grafica_accidentes_trabajo->CurrentAction <> "gridadd" && $grafica_accidentes_trabajo->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_accidentes_trabajo_list->Pager)) $grafica_accidentes_trabajo_list->Pager = new cPrevNextPager($grafica_accidentes_trabajo_list->StartRec, $grafica_accidentes_trabajo_list->DisplayRecs, $grafica_accidentes_trabajo_list->TotalRecs) ?>
<?php if ($grafica_accidentes_trabajo_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_accidentes_trabajo_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_accidentes_trabajo_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_accidentes_trabajo_list->PageUrl() ?>start=<?php echo $grafica_accidentes_trabajo_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_accidentes_trabajo_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_accidentes_trabajo_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($grafica_accidentes_trabajo_list->TotalRecs == 0 && $grafica_accidentes_trabajo->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_accidentes_trabajo_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<script type="text/javascript">
fgrafica_accidentes_trabajolistsrch.Init();
fgrafica_accidentes_trabajolist.Init();
</script>
<?php } ?>
<?php
$grafica_accidentes_trabajo_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($grafica_accidentes_trabajo->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$grafica_accidentes_trabajo_list->Page_Terminate();
?>
