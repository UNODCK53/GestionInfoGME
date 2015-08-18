<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "grafica_desempeno_puntoinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$grafica_desempeno_punto_list = NULL; // Initialize page object first

class cgrafica_desempeno_punto_list extends cgrafica_desempeno_punto {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'grafica_desempeno_punto';

	// Page object name
	var $PageObjName = 'grafica_desempeno_punto_list';

	// Grid form hidden field names
	var $FormName = 'fgrafica_desempeno_puntolist';
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

		// Table object (grafica_desempeno_punto)
		if (!isset($GLOBALS["grafica_desempeno_punto"]) || get_class($GLOBALS["grafica_desempeno_punto"]) == "cgrafica_desempeno_punto") {
			$GLOBALS["grafica_desempeno_punto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["grafica_desempeno_punto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "grafica_desempeno_puntoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "grafica_desempeno_puntodelete.php";
		$this->MultiUpdateUrl = "grafica_desempeno_puntoupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'grafica_desempeno_punto', TRUE);

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
		global $EW_EXPORT, $grafica_desempeno_punto;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($grafica_desempeno_punto);
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
		$this->BuildSearchSql($sWhere, $this->Dias_contratados, $Default, FALSE); // Dias_contratados
		$this->BuildSearchSql($sWhere, $this->Dias_erradicados, $Default, FALSE); // Dias_erradicados
		$this->BuildSearchSql($sWhere, $this->Promedio_ha, $Default, FALSE); // Promedio_ha
		$this->BuildSearchSql($sWhere, $this->Ha_Coca, $Default, FALSE); // Ha_Coca
		$this->BuildSearchSql($sWhere, $this->Ha_Amapola, $Default, FALSE); // Ha_Amapola
		$this->BuildSearchSql($sWhere, $this->Ha_Marihuana, $Default, FALSE); // Ha_Marihuana
		$this->BuildSearchSql($sWhere, $this->Total_erradicacion, $Default, FALSE); // Total_erradicacion

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Punto->AdvancedSearch->Save(); // Punto
			$this->Dias_contratados->AdvancedSearch->Save(); // Dias_contratados
			$this->Dias_erradicados->AdvancedSearch->Save(); // Dias_erradicados
			$this->Promedio_ha->AdvancedSearch->Save(); // Promedio_ha
			$this->Ha_Coca->AdvancedSearch->Save(); // Ha_Coca
			$this->Ha_Amapola->AdvancedSearch->Save(); // Ha_Amapola
			$this->Ha_Marihuana->AdvancedSearch->Save(); // Ha_Marihuana
			$this->Total_erradicacion->AdvancedSearch->Save(); // Total_erradicacion
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
		if ($this->Dias_contratados->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Dias_erradicados->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Promedio_ha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Ha_Coca->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Ha_Amapola->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Ha_Marihuana->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Total_erradicacion->AdvancedSearch->IssetSession())
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
		$this->Dias_contratados->AdvancedSearch->UnsetSession();
		$this->Dias_erradicados->AdvancedSearch->UnsetSession();
		$this->Promedio_ha->AdvancedSearch->UnsetSession();
		$this->Ha_Coca->AdvancedSearch->UnsetSession();
		$this->Ha_Amapola->AdvancedSearch->UnsetSession();
		$this->Ha_Marihuana->AdvancedSearch->UnsetSession();
		$this->Total_erradicacion->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Punto->AdvancedSearch->Load();
		$this->Dias_contratados->AdvancedSearch->Load();
		$this->Dias_erradicados->AdvancedSearch->Load();
		$this->Promedio_ha->AdvancedSearch->Load();
		$this->Ha_Coca->AdvancedSearch->Load();
		$this->Ha_Amapola->AdvancedSearch->Load();
		$this->Ha_Marihuana->AdvancedSearch->Load();
		$this->Total_erradicacion->AdvancedSearch->Load();
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
			$this->UpdateSort($this->Dias_contratados, $bCtrl); // Dias_contratados
			$this->UpdateSort($this->Dias_erradicados, $bCtrl); // Dias_erradicados
			$this->UpdateSort($this->Promedio_ha, $bCtrl); // Promedio_ha
			$this->UpdateSort($this->Ha_Coca, $bCtrl); // Ha_Coca
			$this->UpdateSort($this->Ha_Amapola, $bCtrl); // Ha_Amapola
			$this->UpdateSort($this->Ha_Marihuana, $bCtrl); // Ha_Marihuana
			$this->UpdateSort($this->Total_erradicacion, $bCtrl); // Total_erradicacion
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
				$this->Dias_contratados->setSort("");
				$this->Dias_erradicados->setSort("");
				$this->Promedio_ha->setSort("");
				$this->Ha_Coca->setSort("");
				$this->Ha_Amapola->setSort("");
				$this->Ha_Marihuana->setSort("");
				$this->Total_erradicacion->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgrafica_desempeno_puntolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fgrafica_desempeno_puntolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// Dias_contratados
		$this->Dias_contratados->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Dias_contratados"]);
		if ($this->Dias_contratados->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Dias_contratados->AdvancedSearch->SearchOperator = @$_GET["z_Dias_contratados"];

		// Dias_erradicados
		$this->Dias_erradicados->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Dias_erradicados"]);
		if ($this->Dias_erradicados->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Dias_erradicados->AdvancedSearch->SearchOperator = @$_GET["z_Dias_erradicados"];

		// Promedio_ha
		$this->Promedio_ha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Promedio_ha"]);
		if ($this->Promedio_ha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Promedio_ha->AdvancedSearch->SearchOperator = @$_GET["z_Promedio_ha"];

		// Ha_Coca
		$this->Ha_Coca->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Ha_Coca"]);
		if ($this->Ha_Coca->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Ha_Coca->AdvancedSearch->SearchOperator = @$_GET["z_Ha_Coca"];

		// Ha_Amapola
		$this->Ha_Amapola->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Ha_Amapola"]);
		if ($this->Ha_Amapola->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Ha_Amapola->AdvancedSearch->SearchOperator = @$_GET["z_Ha_Amapola"];

		// Ha_Marihuana
		$this->Ha_Marihuana->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Ha_Marihuana"]);
		if ($this->Ha_Marihuana->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Ha_Marihuana->AdvancedSearch->SearchOperator = @$_GET["z_Ha_Marihuana"];

		// Total_erradicacion
		$this->Total_erradicacion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Total_erradicacion"]);
		if ($this->Total_erradicacion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Total_erradicacion->AdvancedSearch->SearchOperator = @$_GET["z_Total_erradicacion"];
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
		$this->Dias_contratados->setDbValue($rs->fields('Dias_contratados'));
		$this->Dias_erradicados->setDbValue($rs->fields('Dias_erradicados'));
		$this->Promedio_ha->setDbValue($rs->fields('Promedio_ha'));
		$this->Ha_Coca->setDbValue($rs->fields('Ha_Coca'));
		$this->Ha_Amapola->setDbValue($rs->fields('Ha_Amapola'));
		$this->Ha_Marihuana->setDbValue($rs->fields('Ha_Marihuana'));
		$this->Total_erradicacion->setDbValue($rs->fields('Total_erradicacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Punto->DbValue = $row['Punto'];
		$this->Dias_contratados->DbValue = $row['Dias_contratados'];
		$this->Dias_erradicados->DbValue = $row['Dias_erradicados'];
		$this->Promedio_ha->DbValue = $row['Promedio_ha'];
		$this->Ha_Coca->DbValue = $row['Ha_Coca'];
		$this->Ha_Amapola->DbValue = $row['Ha_Amapola'];
		$this->Ha_Marihuana->DbValue = $row['Ha_Marihuana'];
		$this->Total_erradicacion->DbValue = $row['Total_erradicacion'];
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
		if ($this->Dias_erradicados->FormValue == $this->Dias_erradicados->CurrentValue && is_numeric(ew_StrToFloat($this->Dias_erradicados->CurrentValue)))
			$this->Dias_erradicados->CurrentValue = ew_StrToFloat($this->Dias_erradicados->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Promedio_ha->FormValue == $this->Promedio_ha->CurrentValue && is_numeric(ew_StrToFloat($this->Promedio_ha->CurrentValue)))
			$this->Promedio_ha->CurrentValue = ew_StrToFloat($this->Promedio_ha->CurrentValue);

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
		if ($this->Total_erradicacion->FormValue == $this->Total_erradicacion->CurrentValue && is_numeric(ew_StrToFloat($this->Total_erradicacion->CurrentValue)))
			$this->Total_erradicacion->CurrentValue = ew_StrToFloat($this->Total_erradicacion->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Punto
		// Dias_contratados
		// Dias_erradicados
		// Promedio_ha
		// Ha_Coca
		// Ha_Amapola
		// Ha_Marihuana
		// Total_erradicacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Punto
			$this->Punto->ViewValue = $this->Punto->CurrentValue;
			$this->Punto->ViewCustomAttributes = "";

			// Dias_contratados
			$this->Dias_contratados->ViewValue = $this->Dias_contratados->CurrentValue;
			$this->Dias_contratados->ViewCustomAttributes = "";

			// Dias_erradicados
			$this->Dias_erradicados->ViewValue = $this->Dias_erradicados->CurrentValue;
			$this->Dias_erradicados->ViewCustomAttributes = "";

			// Promedio_ha
			$this->Promedio_ha->ViewValue = $this->Promedio_ha->CurrentValue;
			$this->Promedio_ha->ViewCustomAttributes = "";

			// Ha_Coca
			$this->Ha_Coca->ViewValue = $this->Ha_Coca->CurrentValue;
			$this->Ha_Coca->ViewCustomAttributes = "";

			// Ha_Amapola
			$this->Ha_Amapola->ViewValue = $this->Ha_Amapola->CurrentValue;
			$this->Ha_Amapola->ViewCustomAttributes = "";

			// Ha_Marihuana
			$this->Ha_Marihuana->ViewValue = $this->Ha_Marihuana->CurrentValue;
			$this->Ha_Marihuana->ViewCustomAttributes = "";

			// Total_erradicacion
			$this->Total_erradicacion->ViewValue = $this->Total_erradicacion->CurrentValue;
			$this->Total_erradicacion->ViewCustomAttributes = "";

			// Punto
			$this->Punto->LinkCustomAttributes = "";
			$this->Punto->HrefValue = "";
			$this->Punto->TooltipValue = "";

			// Dias_contratados
			$this->Dias_contratados->LinkCustomAttributes = "";
			$this->Dias_contratados->HrefValue = "";
			$this->Dias_contratados->TooltipValue = "";

			// Dias_erradicados
			$this->Dias_erradicados->LinkCustomAttributes = "";
			$this->Dias_erradicados->HrefValue = "";
			$this->Dias_erradicados->TooltipValue = "";

			// Promedio_ha
			$this->Promedio_ha->LinkCustomAttributes = "";
			$this->Promedio_ha->HrefValue = "";
			$this->Promedio_ha->TooltipValue = "";

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

			// Total_erradicacion
			$this->Total_erradicacion->LinkCustomAttributes = "";
			$this->Total_erradicacion->HrefValue = "";
			$this->Total_erradicacion->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Punto
			$this->Punto->EditAttrs["class"] = "form-control";
			$this->Punto->EditCustomAttributes = "";
			$this->Punto->EditValue = ew_HtmlEncode($this->Punto->AdvancedSearch->SearchValue);
			$this->Punto->PlaceHolder = ew_RemoveHtml($this->Punto->FldCaption());

			// Dias_contratados
			$this->Dias_contratados->EditAttrs["class"] = "form-control";
			$this->Dias_contratados->EditCustomAttributes = "";
			$this->Dias_contratados->EditValue = ew_HtmlEncode($this->Dias_contratados->AdvancedSearch->SearchValue);
			$this->Dias_contratados->PlaceHolder = ew_RemoveHtml($this->Dias_contratados->FldCaption());

			// Dias_erradicados
			$this->Dias_erradicados->EditAttrs["class"] = "form-control";
			$this->Dias_erradicados->EditCustomAttributes = "";
			$this->Dias_erradicados->EditValue = ew_HtmlEncode($this->Dias_erradicados->AdvancedSearch->SearchValue);
			$this->Dias_erradicados->PlaceHolder = ew_RemoveHtml($this->Dias_erradicados->FldCaption());

			// Promedio_ha
			$this->Promedio_ha->EditAttrs["class"] = "form-control";
			$this->Promedio_ha->EditCustomAttributes = "";
			$this->Promedio_ha->EditValue = ew_HtmlEncode($this->Promedio_ha->AdvancedSearch->SearchValue);
			$this->Promedio_ha->PlaceHolder = ew_RemoveHtml($this->Promedio_ha->FldCaption());

			// Ha_Coca
			$this->Ha_Coca->EditAttrs["class"] = "form-control";
			$this->Ha_Coca->EditCustomAttributes = "";
			$this->Ha_Coca->EditValue = ew_HtmlEncode($this->Ha_Coca->AdvancedSearch->SearchValue);
			$this->Ha_Coca->PlaceHolder = ew_RemoveHtml($this->Ha_Coca->FldCaption());

			// Ha_Amapola
			$this->Ha_Amapola->EditAttrs["class"] = "form-control";
			$this->Ha_Amapola->EditCustomAttributes = "";
			$this->Ha_Amapola->EditValue = ew_HtmlEncode($this->Ha_Amapola->AdvancedSearch->SearchValue);
			$this->Ha_Amapola->PlaceHolder = ew_RemoveHtml($this->Ha_Amapola->FldCaption());

			// Ha_Marihuana
			$this->Ha_Marihuana->EditAttrs["class"] = "form-control";
			$this->Ha_Marihuana->EditCustomAttributes = "";
			$this->Ha_Marihuana->EditValue = ew_HtmlEncode($this->Ha_Marihuana->AdvancedSearch->SearchValue);
			$this->Ha_Marihuana->PlaceHolder = ew_RemoveHtml($this->Ha_Marihuana->FldCaption());

			// Total_erradicacion
			$this->Total_erradicacion->EditAttrs["class"] = "form-control";
			$this->Total_erradicacion->EditCustomAttributes = "";
			$this->Total_erradicacion->EditValue = ew_HtmlEncode($this->Total_erradicacion->AdvancedSearch->SearchValue);
			$this->Total_erradicacion->PlaceHolder = ew_RemoveHtml($this->Total_erradicacion->FldCaption());
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
		$this->Dias_contratados->AdvancedSearch->Load();
		$this->Dias_erradicados->AdvancedSearch->Load();
		$this->Promedio_ha->AdvancedSearch->Load();
		$this->Ha_Coca->AdvancedSearch->Load();
		$this->Ha_Amapola->AdvancedSearch->Load();
		$this->Ha_Marihuana->AdvancedSearch->Load();
		$this->Total_erradicacion->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_grafica_desempeno_punto\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_grafica_desempeno_punto',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fgrafica_desempeno_puntolist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($grafica_desempeno_punto_list)) $grafica_desempeno_punto_list = new cgrafica_desempeno_punto_list();

// Page init
$grafica_desempeno_punto_list->Page_Init();

// Page main
$grafica_desempeno_punto_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$grafica_desempeno_punto_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($grafica_desempeno_punto->Export == "") { ?>
<script type="text/javascript">

// Page object
var grafica_desempeno_punto_list = new ew_Page("grafica_desempeno_punto_list");
grafica_desempeno_punto_list.PageID = "list"; // Page ID
var EW_PAGE_ID = grafica_desempeno_punto_list.PageID; // For backward compatibility

// Form object
var fgrafica_desempeno_puntolist = new ew_Form("fgrafica_desempeno_puntolist");
fgrafica_desempeno_puntolist.FormKeyCountName = '<?php echo $grafica_desempeno_punto_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgrafica_desempeno_puntolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_desempeno_puntolist.ValidateRequired = true;
<?php } else { ?>
fgrafica_desempeno_puntolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fgrafica_desempeno_puntolistsrch = new ew_Form("fgrafica_desempeno_puntolistsrch");

// Validate function for search
fgrafica_desempeno_puntolistsrch.Validate = function(fobj) {
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
fgrafica_desempeno_puntolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_desempeno_puntolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgrafica_desempeno_puntolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($grafica_desempeno_punto->Export == "") { ?>
<div class="ewToolbar">
<?php if ($grafica_desempeno_punto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<div>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="./Highcharts/js/modules/exporting.js"></script>
<script src="./Highcharts/js/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/drilldown.js"></script>

<table>
	<tr>
		<td><h2>Desempeño de erradicación por Punto</h2></td>
		<td width="5%"></td>
		<td></td>
	</tr>
</table>



<table >

	
	
	<br>
	<p> Este reporte despliega el promedio de área erradicada diariamente en cada punto, calculando el total de hectáreas erradicadas en el punto y dividiéndolo entre el total de días efectivos en los que se realizó erradicación </p></p><p> <font color="#F78181">Datos operativos del grupo de erradicación, cifras no oficiales, pendiente de validación y verificación por parte del ente neutral</font><br>
	<hr>
	<h3>Generador de gráfica</h3>
	<i><strong>Nota:</strong> Seleccione una opción en todos los campos</i><br>
	<br>
	<tr>
		<td >
			<table>
				<tr>

					<td>Año:</td>
					<td width="5%"></td>
					<td><select id="ano" name="ano" title="Seleccione el año de erradicación" onchange="borrar1(this)" required> 
							<option value="">Seleccione uno:</option>	
						</select></td>
				</tr>
				<tr>
					<td>Fase:</td>
					<td width="5%"></td>
					<td><select id="fase" name="fase" title="Seleccione la fase de erradicacón" required> 
							<option value="">Seleccione una:</option>
					</select></td>
				</tr>
				<tr>
					<td>Profesional:</td>
					<td width="5%"></td>
					<td><select id="profesional" name="profesional" title="Seleccione un profesional" required> 
							<option value="">Seleccione uno:</option>
					</select></td>
				</tr>
			</table>
		<br>
		<button class="btn btn-primary ewButton" name="btnsubmit" id="reporte" type="submit"> Generar gráfica </button>

		</td>
		
		
		
	</tr>
		
</table>

</div>
<br>
<hr>
<br>
<div id="container" style="max-height: 400px; min-width: 310px"></div>
<script>
$(document).ready(function(){
				$.ajax({
					type: "GET",
					url: "ano.php",
					cache: false,
					success: function(html)
					{
						$("#ano").html(html);
					},
					error: function() {
					
						$("#ano").val("");
					}			
				});
	
		
			$("#ano").change(function(){
				var id=$(this).val();
				var dataString = 'ano='+ id;
				$.ajax({
					type: "GET",
					url: "fase.php",
					data: dataString,
					cache: false,
					success: function(html)
					{
						$("#fase").html(html);
					},
					error: function() {
					
						$("#fase").val("");
					}			
				});
			});
			
			$("#fase").change(function(){
				var id=$(this).val();
				var ano = document.getElementById("ano").value;
				var fase=document.getElementById("fase").value;
				var dataString = 'ano='+ ano+'&fase='+ fase;
				$.ajax({
					type: "GET",
					url: "profesional_ID.php",
					data: dataString,
					cache: false,
					success: function(html)
					{
						$("#profesional").html(html);
					},
					error: function() {
					
						$("#profesional").val("");
					}			
				});
			});


	});
		
		function borrar1(x) {
			id=x.id;
			if (id=="ano" )	{
				document.getElementById("fase").value = "";
				document.getElementById("profesional").value = "";							
			}
		}
		
$("#reporte").click(function(){

	var fecha = new Date();
	var dd = fecha.getDate();
	var mm = fecha.getMonth()+1; 
	var yyyy = fecha.getFullYear();
	var fecha = dd+'/'+mm+'/'+yyyy;
	var ano = document.getElementById("ano").value;
	var fase=document.getElementById("fase").value;
	var profesional=document.getElementById("profesional").value;
	var dataString = 'ano='+ ano+'&fase='+ fase+'&profesional='+profesional;

	

	if(ano != "" && fase !="" && profesional !="" ){

		if(ano==99){	
				var titulo="Serie histórica desde el ";
				var fases="2015 fase II a "+fecha;
			}else if(fase==99 && ano != 99){
				var titulo="Todas las fases del ";
				var fases=ano;
			}else if(fase!=99 && ano != 99 && profesional == 99){
				var titulo="Fase "+ fase;
				var fases=" del "+ano;
			}else if(fase!=99 && ano != 99 && profesional != 99){
				var titulo="Fase "+ fase;
				var fases=" del "+ano+ " ,Profecional especializado "+profesional;
			}
	$.ajax({
					type: "GET",
					async: false,
					url: "desempeno.php",
					cache: false,
					dataType: "json",
					data: dataString,
					success: function(dato)
					{
						$('#container').highcharts({
							chart: {
								zoomType: 'xy'
							},
							title: {
								text: 'Desempeño de erradicación por punto'
							},
							subtitle: {
								text: titulo + fases + " .Fuente a: "+fecha
							},
							xAxis: [{
								categories: dato.c,
								crosshair: true
							}],
							yAxis: [{ // Primary yAxis
								labels: {
									format: '{value}',
									style: {
										color: Highcharts.getOptions().colors[1]
									}
								},
								title: {
									text: 'Días con erradicación',
									style: {
										color: Highcharts.getOptions().colors[1]
									}
								},
								min: 0
							}, { // Secondary yAxis
								title: {
									text: 'Hectáreas erradicadas',
									style: {
										color: Highcharts.getOptions().colors[0]
									}
								},
								labels: {
									format: '{value} ha',
									style: {
										color: Highcharts.getOptions().colors[0]
									}
								},
								opposite: true
							}, { // Tertiary yAxis
									gridLineWidth: 0,
									title: {
										text: 'Prodemio Diario de erradicación efectiva',
										style: {
											color: Highcharts.getOptions().colors[1]
										}
									},
									min: 0,
									ax: 2,
									labels: {
										format: '{value}',
										style: {
											color: Highcharts.getOptions().colors[1]
										}
									},
									opposite: true
								}],
							
							tooltip: {
									shared: true
								
							},
							legend: {
								layout: 'vertical',
								align: 'left',
								x: 120,
								verticalAlign: 'top',
								y: 100,
								floating: true,
								backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
							},
							series: [{
								name: 'Hectáreas erradicadas',
								type: 'column',
								yAxis: 1,
								data: dato.a,
								tooltip: {
									valueSuffix: ' ha'
								}

							}, {
								name: 'Días con erradicaión',
								type: 'spline',
								data: dato.b,
							},{
								name: 'Prodemio Diario',
								type: 'spline',
								yAxis: 1,
								data: dato.d,
								tooltip: {
									valueSuffix: ' ha/por dia'
								}

							}]
						});
						
						/*$('#container').highcharts({//grafica para barras y drilldown
							chart: {
								type: 'column'
							},
							title: {
								text: 'Desempeño de erradicación por punto'
							},
							subtitle: {
								text: 'Haga click sore las barra para acceder al total de hectáreas erradicadas por punto.'
							},
							xAxis: {
								type: 'category'
							},
							yAxis: {
								title: {
									text: 'Promedio de hectáreas erradicadas por dia'
								}

							},
							legend: {
								enabled: false
							},
							plotOptions: {
								series: {
									borderWidth: 0,
									dataLabels: {
										enabled: true,
										format: '{point.y:.2f}'
									}
								}
							},

							tooltip: {
								formatter: function() {
									var point = this.point,
										punto= this.point.name;
										color=this.point.color;
										dato=this.y;
										
									if (point.drilldown) {
										s = 'Punto de erradicación:<span style="color:'+color+'">'+ punto +'</span> con un promedio de <br><b>'+dato+'</b> hectáreas erradicadas por día<br/>';
									} else {
										s = 'Punto de erradicación '+punto.substring(5,12)+' con un total de '+dato+' hectáreas erradicadas de '+punto.substring(33);
									}
									return s;
								}
								
							},

							series: [{
								name: "Puntos",
								colorByPoint: true,
								data: dato.a
							}],
							drilldown: {
								series: dato.b
							}
						});*/
						
					},
					error: function() {
					
						alert("No hay conección con la base de datos apra realizar la descarga");
					}


			});
			

			
	}else
		{
			alert("Para generar la gráfica debe seleccionar una opción en todos los campos ");
		}

	
});

</script>

<script type="text/javascript">
$("#container").click(function(){
	
}	
</script>

<div id="linea"></div>


<script>
document.getElementById("reporte").onclick = function() {myFunction()};

function myFunction() {

	var ano = document.getElementById("ano").value;
	var fase=document.getElementById("fase").value;
	var profesional=document.getElementById("profesional").value;
	
	if(ano != "" && fase !="" && profesional !="" ){
		document.getElementById("linea").innerHTML = "<hr><br>";
	}
	else{
		pass;
	}    
}
</script>



<h3>Resumen de datos</h3>
<p>La siguiente tabla contiene las hectáres erradicadas por Punto, asi como el número de días contratados y con erradicación; datos necesarios para calcular el desempeño de erradicación por Punto</p>
<hr>
<div class="ewToolbar">
<table>
	<tr>
		<td><?php $grafica_desempeno_punto_list->ExportOptions->Render("body") ?></td>
		<td>Si desea exportar la tabla en formato excel haga click en el siguiente icono </td>
	</tr>
</table>
</div>
<?php if ($grafica_desempeno_punto_list->TotalRecs > 0 && $grafica_desempeno_punto_list->ExportOptions->Visible()) { ?>
<?php } ?> 
<hr>
<br>
<table>
	<tr>
		<td><?php if ($grafica_desempeno_punto_list->SearchOptions->Visible()) { ?><?php } ?><?php $grafica_desempeno_punto_list->SearchOptions->Render("body") ?>
		</td>
		<td>Si desea realizar filtros en la tabla ingrese el dato en la caja y haga click en "Buscar"</td>
	</tr>
</table>
<br>
<hr>
<br>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($grafica_desempeno_punto_list->TotalRecs <= 0)
			$grafica_desempeno_punto_list->TotalRecs = $grafica_desempeno_punto->SelectRecordCount();
	} else {
		if (!$grafica_desempeno_punto_list->Recordset && ($grafica_desempeno_punto_list->Recordset = $grafica_desempeno_punto_list->LoadRecordset()))
			$grafica_desempeno_punto_list->TotalRecs = $grafica_desempeno_punto_list->Recordset->RecordCount();
	}
	$grafica_desempeno_punto_list->StartRec = 1;
	if ($grafica_desempeno_punto_list->DisplayRecs <= 0 || ($grafica_desempeno_punto->Export <> "" && $grafica_desempeno_punto->ExportAll)) // Display all records
		$grafica_desempeno_punto_list->DisplayRecs = $grafica_desempeno_punto_list->TotalRecs;
	if (!($grafica_desempeno_punto->Export <> "" && $grafica_desempeno_punto->ExportAll))
		$grafica_desempeno_punto_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$grafica_desempeno_punto_list->Recordset = $grafica_desempeno_punto_list->LoadRecordset($grafica_desempeno_punto_list->StartRec-1, $grafica_desempeno_punto_list->DisplayRecs);

	// Set no record found message
	if ($grafica_desempeno_punto->CurrentAction == "" && $grafica_desempeno_punto_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$grafica_desempeno_punto_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($grafica_desempeno_punto_list->SearchWhere == "0=101")
			$grafica_desempeno_punto_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$grafica_desempeno_punto_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$grafica_desempeno_punto_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($grafica_desempeno_punto->Export == "" && $grafica_desempeno_punto->CurrentAction == "") { ?>
<form name="fgrafica_desempeno_puntolistsrch" id="fgrafica_desempeno_puntolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($grafica_desempeno_punto_list->SearchWhere <> "") ? " " : " "; ?>
<div id="fgrafica_desempeno_puntolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="grafica_desempeno_punto">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$grafica_desempeno_punto_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$grafica_desempeno_punto->RowType = EW_ROWTYPE_SEARCH;

// Render row
$grafica_desempeno_punto->ResetAttrs();
$grafica_desempeno_punto_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($grafica_desempeno_punto->Punto->Visible) { // Punto ?>
	<div id="xsc_Punto" class="ewCell form-group">
		<label for="x_Punto" class="ewSearchCaption ewLabel"><?php echo $grafica_desempeno_punto->Punto->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Punto" id="z_Punto" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_Punto" name="x_Punto" id="x_Punto" size="35" placeholder="<?php echo ew_HtmlEncode($grafica_desempeno_punto->Punto->PlaceHolder) ?>" value="<?php echo $grafica_desempeno_punto->Punto->EditValue ?>"<?php echo $grafica_desempeno_punto->Punto->EditAttributes() ?>>
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
<?php $grafica_desempeno_punto_list->ShowPageHeader(); ?>
<?php
$grafica_desempeno_punto_list->ShowMessage();
?>
<?php if ($grafica_desempeno_punto_list->TotalRecs > 0 || $grafica_desempeno_punto->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($grafica_desempeno_punto->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($grafica_desempeno_punto->CurrentAction <> "gridadd" && $grafica_desempeno_punto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_desempeno_punto_list->Pager)) $grafica_desempeno_punto_list->Pager = new cPrevNextPager($grafica_desempeno_punto_list->StartRec, $grafica_desempeno_punto_list->DisplayRecs, $grafica_desempeno_punto_list->TotalRecs) ?>
<?php if ($grafica_desempeno_punto_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_desempeno_punto_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_desempeno_punto_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fgrafica_desempeno_puntolist" id="fgrafica_desempeno_puntolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($grafica_desempeno_punto_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $grafica_desempeno_punto_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="grafica_desempeno_punto">
<div id="gmp_grafica_desempeno_punto" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($grafica_desempeno_punto_list->TotalRecs > 0) { ?>
<table id="tbl_grafica_desempeno_puntolist" class="table ewTable">
<?php echo $grafica_desempeno_punto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$grafica_desempeno_punto->RowType = EW_ROWTYPE_HEADER;

// Render list options
$grafica_desempeno_punto_list->RenderListOptions();

// Render list options (header, left)
$grafica_desempeno_punto_list->ListOptions->Render("header", "left");
?>
<?php if ($grafica_desempeno_punto->Punto->Visible) { // Punto ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Punto) == "") { ?>
		<th data-name="Punto"><div id="elh_grafica_desempeno_punto_Punto" class="grafica_desempeno_punto_Punto"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Punto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Punto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Punto) ?>',2);"><div id="elh_grafica_desempeno_punto_Punto" class="grafica_desempeno_punto_Punto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Punto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Punto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Punto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_desempeno_punto->Dias_contratados->Visible) { // Dias_contratados ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Dias_contratados) == "") { ?>
		<th data-name="Dias_contratados"><div id="elh_grafica_desempeno_punto_Dias_contratados" class="grafica_desempeno_punto_Dias_contratados"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Dias_contratados->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Dias_contratados"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Dias_contratados) ?>',2);"><div id="elh_grafica_desempeno_punto_Dias_contratados" class="grafica_desempeno_punto_Dias_contratados">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Dias_contratados->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Dias_contratados->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Dias_contratados->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_desempeno_punto->Dias_erradicados->Visible) { // Dias_erradicados ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Dias_erradicados) == "") { ?>
		<th data-name="Dias_erradicados"><div id="elh_grafica_desempeno_punto_Dias_erradicados" class="grafica_desempeno_punto_Dias_erradicados"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Dias_erradicados->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Dias_erradicados"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Dias_erradicados) ?>',2);"><div id="elh_grafica_desempeno_punto_Dias_erradicados" class="grafica_desempeno_punto_Dias_erradicados">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Dias_erradicados->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Dias_erradicados->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Dias_erradicados->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_desempeno_punto->Promedio_ha->Visible) { // Promedio_ha ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Promedio_ha) == "") { ?>
		<th data-name="Promedio_ha"><div id="elh_grafica_desempeno_punto_Promedio_ha" class="grafica_desempeno_punto_Promedio_ha"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Promedio_ha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Promedio_ha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Promedio_ha) ?>',2);"><div id="elh_grafica_desempeno_punto_Promedio_ha" class="grafica_desempeno_punto_Promedio_ha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Promedio_ha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Promedio_ha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Promedio_ha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_desempeno_punto->Ha_Coca->Visible) { // Ha_Coca ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Ha_Coca) == "") { ?>
		<th data-name="Ha_Coca"><div id="elh_grafica_desempeno_punto_Ha_Coca" class="grafica_desempeno_punto_Ha_Coca"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Ha_Coca->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ha_Coca"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Ha_Coca) ?>',2);"><div id="elh_grafica_desempeno_punto_Ha_Coca" class="grafica_desempeno_punto_Ha_Coca">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Ha_Coca->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Ha_Coca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Ha_Coca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_desempeno_punto->Ha_Amapola->Visible) { // Ha_Amapola ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Ha_Amapola) == "") { ?>
		<th data-name="Ha_Amapola"><div id="elh_grafica_desempeno_punto_Ha_Amapola" class="grafica_desempeno_punto_Ha_Amapola"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Ha_Amapola->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ha_Amapola"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Ha_Amapola) ?>',2);"><div id="elh_grafica_desempeno_punto_Ha_Amapola" class="grafica_desempeno_punto_Ha_Amapola">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Ha_Amapola->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Ha_Amapola->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Ha_Amapola->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_desempeno_punto->Ha_Marihuana->Visible) { // Ha_Marihuana ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Ha_Marihuana) == "") { ?>
		<th data-name="Ha_Marihuana"><div id="elh_grafica_desempeno_punto_Ha_Marihuana" class="grafica_desempeno_punto_Ha_Marihuana"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Ha_Marihuana->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ha_Marihuana"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Ha_Marihuana) ?>',2);"><div id="elh_grafica_desempeno_punto_Ha_Marihuana" class="grafica_desempeno_punto_Ha_Marihuana">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Ha_Marihuana->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Ha_Marihuana->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Ha_Marihuana->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_desempeno_punto->Total_erradicacion->Visible) { // Total_erradicacion ?>
	<?php if ($grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Total_erradicacion) == "") { ?>
		<th data-name="Total_erradicacion"><div id="elh_grafica_desempeno_punto_Total_erradicacion" class="grafica_desempeno_punto_Total_erradicacion"><div class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Total_erradicacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Total_erradicacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_desempeno_punto->SortUrl($grafica_desempeno_punto->Total_erradicacion) ?>',2);"><div id="elh_grafica_desempeno_punto_Total_erradicacion" class="grafica_desempeno_punto_Total_erradicacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_desempeno_punto->Total_erradicacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_desempeno_punto->Total_erradicacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_desempeno_punto->Total_erradicacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$grafica_desempeno_punto_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($grafica_desempeno_punto->ExportAll && $grafica_desempeno_punto->Export <> "") {
	$grafica_desempeno_punto_list->StopRec = $grafica_desempeno_punto_list->TotalRecs;
} else {

	// Set the last record to display
	if ($grafica_desempeno_punto_list->TotalRecs > $grafica_desempeno_punto_list->StartRec + $grafica_desempeno_punto_list->DisplayRecs - 1)
		$grafica_desempeno_punto_list->StopRec = $grafica_desempeno_punto_list->StartRec + $grafica_desempeno_punto_list->DisplayRecs - 1;
	else
		$grafica_desempeno_punto_list->StopRec = $grafica_desempeno_punto_list->TotalRecs;
}
$grafica_desempeno_punto_list->RecCnt = $grafica_desempeno_punto_list->StartRec - 1;
if ($grafica_desempeno_punto_list->Recordset && !$grafica_desempeno_punto_list->Recordset->EOF) {
	$grafica_desempeno_punto_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $grafica_desempeno_punto_list->StartRec > 1)
		$grafica_desempeno_punto_list->Recordset->Move($grafica_desempeno_punto_list->StartRec - 1);
} elseif (!$grafica_desempeno_punto->AllowAddDeleteRow && $grafica_desempeno_punto_list->StopRec == 0) {
	$grafica_desempeno_punto_list->StopRec = $grafica_desempeno_punto->GridAddRowCount;
}

// Initialize aggregate
$grafica_desempeno_punto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$grafica_desempeno_punto->ResetAttrs();
$grafica_desempeno_punto_list->RenderRow();
while ($grafica_desempeno_punto_list->RecCnt < $grafica_desempeno_punto_list->StopRec) {
	$grafica_desempeno_punto_list->RecCnt++;
	if (intval($grafica_desempeno_punto_list->RecCnt) >= intval($grafica_desempeno_punto_list->StartRec)) {
		$grafica_desempeno_punto_list->RowCnt++;

		// Set up key count
		$grafica_desempeno_punto_list->KeyCount = $grafica_desempeno_punto_list->RowIndex;

		// Init row class and style
		$grafica_desempeno_punto->ResetAttrs();
		$grafica_desempeno_punto->CssClass = "";
		if ($grafica_desempeno_punto->CurrentAction == "gridadd") {
		} else {
			$grafica_desempeno_punto_list->LoadRowValues($grafica_desempeno_punto_list->Recordset); // Load row values
		}
		$grafica_desempeno_punto->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$grafica_desempeno_punto->RowAttrs = array_merge($grafica_desempeno_punto->RowAttrs, array('data-rowindex'=>$grafica_desempeno_punto_list->RowCnt, 'id'=>'r' . $grafica_desempeno_punto_list->RowCnt . '_grafica_desempeno_punto', 'data-rowtype'=>$grafica_desempeno_punto->RowType));

		// Render row
		$grafica_desempeno_punto_list->RenderRow();

		// Render list options
		$grafica_desempeno_punto_list->RenderListOptions();
?>
	<tr<?php echo $grafica_desempeno_punto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$grafica_desempeno_punto_list->ListOptions->Render("body", "left", $grafica_desempeno_punto_list->RowCnt);
?>
	<?php if ($grafica_desempeno_punto->Punto->Visible) { // Punto ?>
		<td data-name="Punto"<?php echo $grafica_desempeno_punto->Punto->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Punto->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Punto->ListViewValue() ?></span>
<a id="<?php echo $grafica_desempeno_punto_list->PageObjName . "_row_" . $grafica_desempeno_punto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($grafica_desempeno_punto->Dias_contratados->Visible) { // Dias_contratados ?>
		<td data-name="Dias_contratados"<?php echo $grafica_desempeno_punto->Dias_contratados->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Dias_contratados->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Dias_contratados->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_desempeno_punto->Dias_erradicados->Visible) { // Dias_erradicados ?>
		<td data-name="Dias_erradicados"<?php echo $grafica_desempeno_punto->Dias_erradicados->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Dias_erradicados->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Dias_erradicados->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_desempeno_punto->Promedio_ha->Visible) { // Promedio_ha ?>
		<td data-name="Promedio_ha"<?php echo $grafica_desempeno_punto->Promedio_ha->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Promedio_ha->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Promedio_ha->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_desempeno_punto->Ha_Coca->Visible) { // Ha_Coca ?>
		<td data-name="Ha_Coca"<?php echo $grafica_desempeno_punto->Ha_Coca->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Ha_Coca->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Ha_Coca->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_desempeno_punto->Ha_Amapola->Visible) { // Ha_Amapola ?>
		<td data-name="Ha_Amapola"<?php echo $grafica_desempeno_punto->Ha_Amapola->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Ha_Amapola->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Ha_Amapola->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_desempeno_punto->Ha_Marihuana->Visible) { // Ha_Marihuana ?>
		<td data-name="Ha_Marihuana"<?php echo $grafica_desempeno_punto->Ha_Marihuana->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Ha_Marihuana->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Ha_Marihuana->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_desempeno_punto->Total_erradicacion->Visible) { // Total_erradicacion ?>
		<td data-name="Total_erradicacion"<?php echo $grafica_desempeno_punto->Total_erradicacion->CellAttributes() ?>>
<span<?php echo $grafica_desempeno_punto->Total_erradicacion->ViewAttributes() ?>>
<?php echo $grafica_desempeno_punto->Total_erradicacion->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$grafica_desempeno_punto_list->ListOptions->Render("body", "right", $grafica_desempeno_punto_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($grafica_desempeno_punto->CurrentAction <> "gridadd")
		$grafica_desempeno_punto_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($grafica_desempeno_punto->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($grafica_desempeno_punto_list->Recordset)
	$grafica_desempeno_punto_list->Recordset->Close();
?>
<?php if ($grafica_desempeno_punto->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($grafica_desempeno_punto->CurrentAction <> "gridadd" && $grafica_desempeno_punto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_desempeno_punto_list->Pager)) $grafica_desempeno_punto_list->Pager = new cPrevNextPager($grafica_desempeno_punto_list->StartRec, $grafica_desempeno_punto_list->DisplayRecs, $grafica_desempeno_punto_list->TotalRecs) ?>
<?php if ($grafica_desempeno_punto_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_desempeno_punto_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_desempeno_punto_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_desempeno_punto_list->PageUrl() ?>start=<?php echo $grafica_desempeno_punto_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_desempeno_punto_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_desempeno_punto_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($grafica_desempeno_punto_list->TotalRecs == 0 && $grafica_desempeno_punto->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_desempeno_punto_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($grafica_desempeno_punto->Export == "") { ?>
<script type="text/javascript">
fgrafica_desempeno_puntolistsrch.Init();
fgrafica_desempeno_puntolist.Init();
</script>
<?php } ?>
<?php
$grafica_desempeno_punto_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($grafica_desempeno_punto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$grafica_desempeno_punto_list->Page_Terminate();
?>
