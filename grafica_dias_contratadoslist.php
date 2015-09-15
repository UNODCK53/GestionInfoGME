<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "grafica_dias_contratadosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$grafica_dias_contratados_list = NULL; // Initialize page object first

class cgrafica_dias_contratados_list extends cgrafica_dias_contratados {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'grafica_dias_contratados';

	// Page object name
	var $PageObjName = 'grafica_dias_contratados_list';

	// Grid form hidden field names
	var $FormName = 'fgrafica_dias_contratadoslist';
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

		// Table object (grafica_dias_contratados)
		if (!isset($GLOBALS["grafica_dias_contratados"]) || get_class($GLOBALS["grafica_dias_contratados"]) == "cgrafica_dias_contratados") {
			$GLOBALS["grafica_dias_contratados"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["grafica_dias_contratados"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "grafica_dias_contratadosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "grafica_dias_contratadosdelete.php";
		$this->MultiUpdateUrl = "grafica_dias_contratadosupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'grafica_dias_contratados', TRUE);

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
		global $EW_EXPORT, $grafica_dias_contratados;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($grafica_dias_contratados);
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
		$this->BuildSearchSql($sWhere, $this->Profesional, $Default, FALSE); // Profesional
		$this->BuildSearchSql($sWhere, $this->Firma_Contrato_Gme, $Default, FALSE); // Firma_Contrato_Gme
		$this->BuildSearchSql($sWhere, $this->Llegada_GME_a_su_lugar_de_Origen, $Default, FALSE); // Llegada_GME_a_su_lugar_de_Origen
		$this->BuildSearchSql($sWhere, $this->dias, $Default, FALSE); // dias
		$this->BuildSearchSql($sWhere, $this->Dias_reportados, $Default, FALSE); // Dias_reportados

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Punto->AdvancedSearch->Save(); // Punto
			$this->Profesional->AdvancedSearch->Save(); // Profesional
			$this->Firma_Contrato_Gme->AdvancedSearch->Save(); // Firma_Contrato_Gme
			$this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->Save(); // Llegada_GME_a_su_lugar_de_Origen
			$this->dias->AdvancedSearch->Save(); // dias
			$this->Dias_reportados->AdvancedSearch->Save(); // Dias_reportados
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
		if ($this->Profesional->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Firma_Contrato_Gme->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dias->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Dias_reportados->AdvancedSearch->IssetSession())
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
		$this->Profesional->AdvancedSearch->UnsetSession();
		$this->Firma_Contrato_Gme->AdvancedSearch->UnsetSession();
		$this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->UnsetSession();
		$this->dias->AdvancedSearch->UnsetSession();
		$this->Dias_reportados->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Punto->AdvancedSearch->Load();
		$this->Profesional->AdvancedSearch->Load();
		$this->Firma_Contrato_Gme->AdvancedSearch->Load();
		$this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->Load();
		$this->dias->AdvancedSearch->Load();
		$this->Dias_reportados->AdvancedSearch->Load();
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
			$this->UpdateSort($this->Profesional, $bCtrl); // Profesional
			$this->UpdateSort($this->Firma_Contrato_Gme, $bCtrl); // Firma_Contrato_Gme
			$this->UpdateSort($this->Llegada_GME_a_su_lugar_de_Origen, $bCtrl); // Llegada_GME_a_su_lugar_de_Origen
			$this->UpdateSort($this->dias, $bCtrl); // dias
			$this->UpdateSort($this->Dias_reportados, $bCtrl); // Dias_reportados
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
				$this->Profesional->setSort("");
				$this->Firma_Contrato_Gme->setSort("");
				$this->Llegada_GME_a_su_lugar_de_Origen->setSort("");
				$this->dias->setSort("");
				$this->Dias_reportados->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgrafica_dias_contratadoslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fgrafica_dias_contratadoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// Profesional
		$this->Profesional->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Profesional"]);
		if ($this->Profesional->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Profesional->AdvancedSearch->SearchOperator = @$_GET["z_Profesional"];

		// Firma_Contrato_Gme
		$this->Firma_Contrato_Gme->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Firma_Contrato_Gme"]);
		if ($this->Firma_Contrato_Gme->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Firma_Contrato_Gme->AdvancedSearch->SearchOperator = @$_GET["z_Firma_Contrato_Gme"];

		// Llegada_GME_a_su_lugar_de_Origen
		$this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Llegada_GME_a_su_lugar_de_Origen"]);
		if ($this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->SearchOperator = @$_GET["z_Llegada_GME_a_su_lugar_de_Origen"];

		// dias
		$this->dias->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dias"]);
		if ($this->dias->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dias->AdvancedSearch->SearchOperator = @$_GET["z_dias"];

		// Dias_reportados
		$this->Dias_reportados->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Dias_reportados"]);
		if ($this->Dias_reportados->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Dias_reportados->AdvancedSearch->SearchOperator = @$_GET["z_Dias_reportados"];
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
		$this->Profesional->setDbValue($rs->fields('Profesional'));
		$this->Firma_Contrato_Gme->setDbValue($rs->fields('Firma_Contrato_Gme'));
		$this->Llegada_GME_a_su_lugar_de_Origen->setDbValue($rs->fields('Llegada_GME_a_su_lugar_de_Origen'));
		$this->dias->setDbValue($rs->fields('dias'));
		$this->Dias_reportados->setDbValue($rs->fields('Dias_reportados'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Punto->DbValue = $row['Punto'];
		$this->Profesional->DbValue = $row['Profesional'];
		$this->Firma_Contrato_Gme->DbValue = $row['Firma_Contrato_Gme'];
		$this->Llegada_GME_a_su_lugar_de_Origen->DbValue = $row['Llegada_GME_a_su_lugar_de_Origen'];
		$this->dias->DbValue = $row['dias'];
		$this->Dias_reportados->DbValue = $row['Dias_reportados'];
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
		// Punto
		// Profesional
		// Firma_Contrato_Gme
		// Llegada_GME_a_su_lugar_de_Origen
		// dias
		// Dias_reportados

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// Punto
			if (strval($this->Punto->CurrentValue) <> "") {
				$sFilterWrk = "`Punto`" . ew_SearchString("=", $this->Punto->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_dias_contratados`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_dias_contratados`";
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

			// Profesional
			if (strval($this->Profesional->CurrentValue) <> "") {
				$sFilterWrk = "`Profesional`" . ew_SearchString("=", $this->Profesional->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Profesional`, `Profesional` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_dias_contratados`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Profesional`, `Profesional` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `grafica_dias_contratados`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Profesional, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Profesional` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->Profesional->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->Profesional->ViewValue = $this->Profesional->CurrentValue;
				}
			} else {
				$this->Profesional->ViewValue = NULL;
			}
			$this->Profesional->ViewCustomAttributes = "";

			// Firma_Contrato_Gme
			$this->Firma_Contrato_Gme->ViewValue = $this->Firma_Contrato_Gme->CurrentValue;
			$this->Firma_Contrato_Gme->ViewCustomAttributes = "";

			// Llegada_GME_a_su_lugar_de_Origen
			$this->Llegada_GME_a_su_lugar_de_Origen->ViewValue = $this->Llegada_GME_a_su_lugar_de_Origen->CurrentValue;
			$this->Llegada_GME_a_su_lugar_de_Origen->ViewCustomAttributes = "";

			// dias
			$this->dias->ViewValue = $this->dias->CurrentValue;
			$this->dias->ViewCustomAttributes = "";

			// Dias_reportados
			$this->Dias_reportados->ViewValue = $this->Dias_reportados->CurrentValue;
			$this->Dias_reportados->ViewCustomAttributes = "";

			// Punto
			$this->Punto->LinkCustomAttributes = "";
			$this->Punto->HrefValue = "";
			$this->Punto->TooltipValue = "";

			// Profesional
			$this->Profesional->LinkCustomAttributes = "";
			$this->Profesional->HrefValue = "";
			$this->Profesional->TooltipValue = "";

			// Firma_Contrato_Gme
			$this->Firma_Contrato_Gme->LinkCustomAttributes = "";
			$this->Firma_Contrato_Gme->HrefValue = "";
			$this->Firma_Contrato_Gme->TooltipValue = "";

			// Llegada_GME_a_su_lugar_de_Origen
			$this->Llegada_GME_a_su_lugar_de_Origen->LinkCustomAttributes = "";
			$this->Llegada_GME_a_su_lugar_de_Origen->HrefValue = "";
			$this->Llegada_GME_a_su_lugar_de_Origen->TooltipValue = "";

			// dias
			$this->dias->LinkCustomAttributes = "";
			$this->dias->HrefValue = "";
			$this->dias->TooltipValue = "";

			// Dias_reportados
			$this->Dias_reportados->LinkCustomAttributes = "";
			$this->Dias_reportados->HrefValue = "";
			$this->Dias_reportados->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Punto
			$this->Punto->EditAttrs["class"] = "form-control";
			$this->Punto->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_dias_contratados`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Punto`, `Punto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_dias_contratados`";
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

			// Profesional
			$this->Profesional->EditAttrs["class"] = "form-control";
			$this->Profesional->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Profesional`, `Profesional` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_dias_contratados`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Profesional`, `Profesional` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `grafica_dias_contratados`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->Profesional, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `Profesional` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Profesional->EditValue = $arwrk;

			// Firma_Contrato_Gme
			$this->Firma_Contrato_Gme->EditAttrs["class"] = "form-control";
			$this->Firma_Contrato_Gme->EditCustomAttributes = "";
			$this->Firma_Contrato_Gme->EditValue = ew_HtmlEncode($this->Firma_Contrato_Gme->AdvancedSearch->SearchValue);
			$this->Firma_Contrato_Gme->PlaceHolder = ew_RemoveHtml($this->Firma_Contrato_Gme->FldCaption());

			// Llegada_GME_a_su_lugar_de_Origen
			$this->Llegada_GME_a_su_lugar_de_Origen->EditAttrs["class"] = "form-control";
			$this->Llegada_GME_a_su_lugar_de_Origen->EditCustomAttributes = "";
			$this->Llegada_GME_a_su_lugar_de_Origen->EditValue = ew_HtmlEncode($this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->SearchValue);
			$this->Llegada_GME_a_su_lugar_de_Origen->PlaceHolder = ew_RemoveHtml($this->Llegada_GME_a_su_lugar_de_Origen->FldCaption());

			// dias
			$this->dias->EditAttrs["class"] = "form-control";
			$this->dias->EditCustomAttributes = "";
			$this->dias->EditValue = ew_HtmlEncode($this->dias->AdvancedSearch->SearchValue);
			$this->dias->PlaceHolder = ew_RemoveHtml($this->dias->FldCaption());

			// Dias_reportados
			$this->Dias_reportados->EditAttrs["class"] = "form-control";
			$this->Dias_reportados->EditCustomAttributes = "";
			$this->Dias_reportados->EditValue = ew_HtmlEncode($this->Dias_reportados->AdvancedSearch->SearchValue);
			$this->Dias_reportados->PlaceHolder = ew_RemoveHtml($this->Dias_reportados->FldCaption());
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
		$this->Profesional->AdvancedSearch->Load();
		$this->Firma_Contrato_Gme->AdvancedSearch->Load();
		$this->Llegada_GME_a_su_lugar_de_Origen->AdvancedSearch->Load();
		$this->dias->AdvancedSearch->Load();
		$this->Dias_reportados->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_grafica_dias_contratados\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_grafica_dias_contratados',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fgrafica_dias_contratadoslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($grafica_dias_contratados_list)) $grafica_dias_contratados_list = new cgrafica_dias_contratados_list();

// Page init
$grafica_dias_contratados_list->Page_Init();

// Page main
$grafica_dias_contratados_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$grafica_dias_contratados_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($grafica_dias_contratados->Export == "") { ?>
<script type="text/javascript">

// Page object
var grafica_dias_contratados_list = new ew_Page("grafica_dias_contratados_list");
grafica_dias_contratados_list.PageID = "list"; // Page ID
var EW_PAGE_ID = grafica_dias_contratados_list.PageID; // For backward compatibility

// Form object
var fgrafica_dias_contratadoslist = new ew_Form("fgrafica_dias_contratadoslist");
fgrafica_dias_contratadoslist.FormKeyCountName = '<?php echo $grafica_dias_contratados_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgrafica_dias_contratadoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_dias_contratadoslist.ValidateRequired = true;
<?php } else { ?>
fgrafica_dias_contratadoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgrafica_dias_contratadoslist.Lists["x_Punto"] = {"LinkField":"x_Punto","Ajax":null,"AutoFill":false,"DisplayFields":["x_Punto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_dias_contratadoslist.Lists["x_Profesional"] = {"LinkField":"x_Profesional","Ajax":null,"AutoFill":false,"DisplayFields":["x_Profesional","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fgrafica_dias_contratadoslistsrch = new ew_Form("fgrafica_dias_contratadoslistsrch");

// Validate function for search
fgrafica_dias_contratadoslistsrch.Validate = function(fobj) {
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
fgrafica_dias_contratadoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrafica_dias_contratadoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgrafica_dias_contratadoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fgrafica_dias_contratadoslistsrch.Lists["x_Punto"] = {"LinkField":"x_Punto","Ajax":null,"AutoFill":false,"DisplayFields":["x_Punto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fgrafica_dias_contratadoslistsrch.Lists["x_Profesional"] = {"LinkField":"x_Profesional","Ajax":null,"AutoFill":false,"DisplayFields":["x_Profesional","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($grafica_dias_contratados->Export == "") { ?>
<div class="ewToolbar">
<?php if ($grafica_dias_contratados->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($grafica_dias_contratados->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<div class="ewToolbar">
<H2> Reporte de Días contratados</h2>
<p>La siguiente tabla contiene el listado de los días trabajados en zona por cada Punto de erradicación, desde que firmaron contrato hasta que finalizaron la fase</p><p><font color="#F78181">Datos operativos del grupo de erradicación, cifras no oficiales, pendiente de validación y verificación por parte del ente neutral</font></p>

<hr>
<table>
	<tr>
		<td>
			<?php if ($grafica_dias_contratados_list->TotalRecs > 0 && $grafica_dias_contratados_list->ExportOptions->Visible()) { ?>

			<?php $grafica_dias_contratados_list->ExportOptions->Render("body") ?>
			<?php } ?>

		</td>
		<td>
			Si desea exportar la tabla en formato excel haga click en el siguiente icono 
		</td>	
	</tr>	
</table> 

<hr>

</div>
<?php if ($grafica_dias_contratados->Export == "") { ?>

<div>
<br>
<table>
	<tr>
		<td>
			<?php if ($grafica_dias_contratados_list->SearchOptions->Visible()) { ?>
			<?php $grafica_dias_contratados_list->SearchOptions->Render("body") ?>
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
		if ($grafica_dias_contratados_list->TotalRecs <= 0)
			$grafica_dias_contratados_list->TotalRecs = $grafica_dias_contratados->SelectRecordCount();
	} else {
		if (!$grafica_dias_contratados_list->Recordset && ($grafica_dias_contratados_list->Recordset = $grafica_dias_contratados_list->LoadRecordset()))
			$grafica_dias_contratados_list->TotalRecs = $grafica_dias_contratados_list->Recordset->RecordCount();
	}
	$grafica_dias_contratados_list->StartRec = 1;
	if ($grafica_dias_contratados_list->DisplayRecs <= 0 || ($grafica_dias_contratados->Export <> "" && $grafica_dias_contratados->ExportAll)) // Display all records
		$grafica_dias_contratados_list->DisplayRecs = $grafica_dias_contratados_list->TotalRecs;
	if (!($grafica_dias_contratados->Export <> "" && $grafica_dias_contratados->ExportAll))
		$grafica_dias_contratados_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$grafica_dias_contratados_list->Recordset = $grafica_dias_contratados_list->LoadRecordset($grafica_dias_contratados_list->StartRec-1, $grafica_dias_contratados_list->DisplayRecs);

	// Set no record found message
	if ($grafica_dias_contratados->CurrentAction == "" && $grafica_dias_contratados_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$grafica_dias_contratados_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($grafica_dias_contratados_list->SearchWhere == "0=101")
			$grafica_dias_contratados_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$grafica_dias_contratados_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$grafica_dias_contratados_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($grafica_dias_contratados->Export == "" && $grafica_dias_contratados->CurrentAction == "") { ?>
<form name="fgrafica_dias_contratadoslistsrch" id="fgrafica_dias_contratadoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($grafica_dias_contratados_list->SearchWhere <> "") ? " " : " "; ?>
<div id="fgrafica_dias_contratadoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="grafica_dias_contratados">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$grafica_dias_contratados_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$grafica_dias_contratados->RowType = EW_ROWTYPE_SEARCH;

// Render row
$grafica_dias_contratados->ResetAttrs();
$grafica_dias_contratados_list->RenderRow();
?>
<br>
<table>
	<tr>
		<td>
			<label for="x_Punto" class="ewSearchCaption ewLabel"><?php echo $grafica_dias_contratados->Punto->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Punto" id="z_Punto" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_Punto" id="x_Punto" name="x_Punto"<?php echo $grafica_dias_contratados->Punto->EditAttributes() ?>>
				<?php
				if (is_array($grafica_dias_contratados->Punto->EditValue)) {
					$arwrk = $grafica_dias_contratados->Punto->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($grafica_dias_contratados->Punto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fgrafica_dias_contratadoslistsrch.Lists["x_Punto"].Options = <?php echo (is_array($grafica_dias_contratados->Punto->EditValue)) ? ew_ArrayToJson($grafica_dias_contratados->Punto->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_Profesional" class="ewSearchCaption ewLabel"><?php echo $grafica_dias_contratados->Profesional->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Profesional" id="z_Profesional" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select  style="min-width: 350px;" data-field="x_Profesional" id="x_Profesional" name="x_Profesional"<?php echo $grafica_dias_contratados->Profesional->EditAttributes() ?>>
				<?php
				if (is_array($grafica_dias_contratados->Profesional->EditValue)) {
					$arwrk = $grafica_dias_contratados->Profesional->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($grafica_dias_contratados->Profesional->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fgrafica_dias_contratadoslistsrch.Lists["x_Profesional"].Options = <?php echo (is_array($grafica_dias_contratados->Profesional->EditValue)) ? ew_ArrayToJson($grafica_dias_contratados->Profesional->EditValue, 1) : "[]" ?>;
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
<?php $grafica_dias_contratados_list->ShowPageHeader(); ?>
<?php
$grafica_dias_contratados_list->ShowMessage();
?>
<?php if ($grafica_dias_contratados_list->TotalRecs > 0 || $grafica_dias_contratados->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($grafica_dias_contratados->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($grafica_dias_contratados->CurrentAction <> "gridadd" && $grafica_dias_contratados->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_dias_contratados_list->Pager)) $grafica_dias_contratados_list->Pager = new cPrevNextPager($grafica_dias_contratados_list->StartRec, $grafica_dias_contratados_list->DisplayRecs, $grafica_dias_contratados_list->TotalRecs) ?>
<?php if ($grafica_dias_contratados_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_dias_contratados_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_dias_contratados_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_dias_contratados_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_dias_contratados_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_dias_contratados_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_dias_contratados_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fgrafica_dias_contratadoslist" id="fgrafica_dias_contratadoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($grafica_dias_contratados_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $grafica_dias_contratados_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="grafica_dias_contratados">
<div id="gmp_grafica_dias_contratados" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($grafica_dias_contratados_list->TotalRecs > 0) { ?>
<table id="tbl_grafica_dias_contratadoslist" class="table ewTable">
<?php echo $grafica_dias_contratados->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$grafica_dias_contratados->RowType = EW_ROWTYPE_HEADER;

// Render list options
$grafica_dias_contratados_list->RenderListOptions();

// Render list options (header, left)
$grafica_dias_contratados_list->ListOptions->Render("header", "left");
?>
<?php if ($grafica_dias_contratados->Punto->Visible) { // Punto ?>
	<?php if ($grafica_dias_contratados->SortUrl($grafica_dias_contratados->Punto) == "") { ?>
		<th data-name="Punto"><div id="elh_grafica_dias_contratados_Punto" class="grafica_dias_contratados_Punto"><div class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Punto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Punto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_dias_contratados->SortUrl($grafica_dias_contratados->Punto) ?>',2);"><div id="elh_grafica_dias_contratados_Punto" class="grafica_dias_contratados_Punto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Punto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_dias_contratados->Punto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_dias_contratados->Punto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_dias_contratados->Profesional->Visible) { // Profesional ?>
	<?php if ($grafica_dias_contratados->SortUrl($grafica_dias_contratados->Profesional) == "") { ?>
		<th data-name="Profesional"><div id="elh_grafica_dias_contratados_Profesional" class="grafica_dias_contratados_Profesional"><div class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Profesional->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Profesional"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_dias_contratados->SortUrl($grafica_dias_contratados->Profesional) ?>',2);"><div id="elh_grafica_dias_contratados_Profesional" class="grafica_dias_contratados_Profesional">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Profesional->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_dias_contratados->Profesional->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_dias_contratados->Profesional->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_dias_contratados->Firma_Contrato_Gme->Visible) { // Firma_Contrato_Gme ?>
	<?php if ($grafica_dias_contratados->SortUrl($grafica_dias_contratados->Firma_Contrato_Gme) == "") { ?>
		<th data-name="Firma_Contrato_Gme"><div id="elh_grafica_dias_contratados_Firma_Contrato_Gme" class="grafica_dias_contratados_Firma_Contrato_Gme"><div class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Firma_Contrato_Gme->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Firma_Contrato_Gme"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_dias_contratados->SortUrl($grafica_dias_contratados->Firma_Contrato_Gme) ?>',2);"><div id="elh_grafica_dias_contratados_Firma_Contrato_Gme" class="grafica_dias_contratados_Firma_Contrato_Gme">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Firma_Contrato_Gme->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_dias_contratados->Firma_Contrato_Gme->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_dias_contratados->Firma_Contrato_Gme->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->Visible) { // Llegada_GME_a_su_lugar_de_Origen ?>
	<?php if ($grafica_dias_contratados->SortUrl($grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen) == "") { ?>
		<th data-name="Llegada_GME_a_su_lugar_de_Origen"><div id="elh_grafica_dias_contratados_Llegada_GME_a_su_lugar_de_Origen" class="grafica_dias_contratados_Llegada_GME_a_su_lugar_de_Origen"><div class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Llegada_GME_a_su_lugar_de_Origen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_dias_contratados->SortUrl($grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen) ?>',2);"><div id="elh_grafica_dias_contratados_Llegada_GME_a_su_lugar_de_Origen" class="grafica_dias_contratados_Llegada_GME_a_su_lugar_de_Origen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_dias_contratados->dias->Visible) { // dias ?>
	<?php if ($grafica_dias_contratados->SortUrl($grafica_dias_contratados->dias) == "") { ?>
		<th data-name="dias"><div id="elh_grafica_dias_contratados_dias" class="grafica_dias_contratados_dias"><div class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->dias->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dias"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_dias_contratados->SortUrl($grafica_dias_contratados->dias) ?>',2);"><div id="elh_grafica_dias_contratados_dias" class="grafica_dias_contratados_dias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->dias->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_dias_contratados->dias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_dias_contratados->dias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($grafica_dias_contratados->Dias_reportados->Visible) { // Dias_reportados ?>
	<?php if ($grafica_dias_contratados->SortUrl($grafica_dias_contratados->Dias_reportados) == "") { ?>
		<th data-name="Dias_reportados"><div id="elh_grafica_dias_contratados_Dias_reportados" class="grafica_dias_contratados_Dias_reportados"><div class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Dias_reportados->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Dias_reportados"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $grafica_dias_contratados->SortUrl($grafica_dias_contratados->Dias_reportados) ?>',2);"><div id="elh_grafica_dias_contratados_Dias_reportados" class="grafica_dias_contratados_Dias_reportados">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $grafica_dias_contratados->Dias_reportados->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($grafica_dias_contratados->Dias_reportados->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($grafica_dias_contratados->Dias_reportados->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$grafica_dias_contratados_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($grafica_dias_contratados->ExportAll && $grafica_dias_contratados->Export <> "") {
	$grafica_dias_contratados_list->StopRec = $grafica_dias_contratados_list->TotalRecs;
} else {

	// Set the last record to display
	if ($grafica_dias_contratados_list->TotalRecs > $grafica_dias_contratados_list->StartRec + $grafica_dias_contratados_list->DisplayRecs - 1)
		$grafica_dias_contratados_list->StopRec = $grafica_dias_contratados_list->StartRec + $grafica_dias_contratados_list->DisplayRecs - 1;
	else
		$grafica_dias_contratados_list->StopRec = $grafica_dias_contratados_list->TotalRecs;
}
$grafica_dias_contratados_list->RecCnt = $grafica_dias_contratados_list->StartRec - 1;
if ($grafica_dias_contratados_list->Recordset && !$grafica_dias_contratados_list->Recordset->EOF) {
	$grafica_dias_contratados_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $grafica_dias_contratados_list->StartRec > 1)
		$grafica_dias_contratados_list->Recordset->Move($grafica_dias_contratados_list->StartRec - 1);
} elseif (!$grafica_dias_contratados->AllowAddDeleteRow && $grafica_dias_contratados_list->StopRec == 0) {
	$grafica_dias_contratados_list->StopRec = $grafica_dias_contratados->GridAddRowCount;
}

// Initialize aggregate
$grafica_dias_contratados->RowType = EW_ROWTYPE_AGGREGATEINIT;
$grafica_dias_contratados->ResetAttrs();
$grafica_dias_contratados_list->RenderRow();
while ($grafica_dias_contratados_list->RecCnt < $grafica_dias_contratados_list->StopRec) {
	$grafica_dias_contratados_list->RecCnt++;
	if (intval($grafica_dias_contratados_list->RecCnt) >= intval($grafica_dias_contratados_list->StartRec)) {
		$grafica_dias_contratados_list->RowCnt++;

		// Set up key count
		$grafica_dias_contratados_list->KeyCount = $grafica_dias_contratados_list->RowIndex;

		// Init row class and style
		$grafica_dias_contratados->ResetAttrs();
		$grafica_dias_contratados->CssClass = "";
		if ($grafica_dias_contratados->CurrentAction == "gridadd") {
		} else {
			$grafica_dias_contratados_list->LoadRowValues($grafica_dias_contratados_list->Recordset); // Load row values
		}
		$grafica_dias_contratados->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$grafica_dias_contratados->RowAttrs = array_merge($grafica_dias_contratados->RowAttrs, array('data-rowindex'=>$grafica_dias_contratados_list->RowCnt, 'id'=>'r' . $grafica_dias_contratados_list->RowCnt . '_grafica_dias_contratados', 'data-rowtype'=>$grafica_dias_contratados->RowType));

		// Render row
		$grafica_dias_contratados_list->RenderRow();

		// Render list options
		$grafica_dias_contratados_list->RenderListOptions();
?>
	<tr<?php echo $grafica_dias_contratados->RowAttributes() ?>>
<?php

// Render list options (body, left)
$grafica_dias_contratados_list->ListOptions->Render("body", "left", $grafica_dias_contratados_list->RowCnt);
?>
	<?php if ($grafica_dias_contratados->Punto->Visible) { // Punto ?>
		<td data-name="Punto"<?php echo $grafica_dias_contratados->Punto->CellAttributes() ?>>
<span<?php echo $grafica_dias_contratados->Punto->ViewAttributes() ?>>
<?php echo $grafica_dias_contratados->Punto->ListViewValue() ?></span>
<a id="<?php echo $grafica_dias_contratados_list->PageObjName . "_row_" . $grafica_dias_contratados_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($grafica_dias_contratados->Profesional->Visible) { // Profesional ?>
		<td data-name="Profesional"<?php echo $grafica_dias_contratados->Profesional->CellAttributes() ?>>
<span<?php echo $grafica_dias_contratados->Profesional->ViewAttributes() ?>>
<?php echo $grafica_dias_contratados->Profesional->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_dias_contratados->Firma_Contrato_Gme->Visible) { // Firma_Contrato_Gme ?>
		<td data-name="Firma_Contrato_Gme"<?php echo $grafica_dias_contratados->Firma_Contrato_Gme->CellAttributes() ?>>
<span<?php echo $grafica_dias_contratados->Firma_Contrato_Gme->ViewAttributes() ?>>
<?php echo $grafica_dias_contratados->Firma_Contrato_Gme->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->Visible) { // Llegada_GME_a_su_lugar_de_Origen ?>
		<td data-name="Llegada_GME_a_su_lugar_de_Origen"<?php echo $grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->CellAttributes() ?>>
<span<?php echo $grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->ViewAttributes() ?>>
<?php echo $grafica_dias_contratados->Llegada_GME_a_su_lugar_de_Origen->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_dias_contratados->dias->Visible) { // dias ?>
		<td data-name="dias"<?php echo $grafica_dias_contratados->dias->CellAttributes() ?>>
<span<?php echo $grafica_dias_contratados->dias->ViewAttributes() ?>>
<?php echo $grafica_dias_contratados->dias->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($grafica_dias_contratados->Dias_reportados->Visible) { // Dias_reportados ?>
		<td data-name="Dias_reportados"<?php echo $grafica_dias_contratados->Dias_reportados->CellAttributes() ?>>
<span<?php echo $grafica_dias_contratados->Dias_reportados->ViewAttributes() ?>>
<?php echo $grafica_dias_contratados->Dias_reportados->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$grafica_dias_contratados_list->ListOptions->Render("body", "right", $grafica_dias_contratados_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($grafica_dias_contratados->CurrentAction <> "gridadd")
		$grafica_dias_contratados_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($grafica_dias_contratados->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($grafica_dias_contratados_list->Recordset)
	$grafica_dias_contratados_list->Recordset->Close();
?>
<?php if ($grafica_dias_contratados->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($grafica_dias_contratados->CurrentAction <> "gridadd" && $grafica_dias_contratados->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($grafica_dias_contratados_list->Pager)) $grafica_dias_contratados_list->Pager = new cPrevNextPager($grafica_dias_contratados_list->StartRec, $grafica_dias_contratados_list->DisplayRecs, $grafica_dias_contratados_list->TotalRecs) ?>
<?php if ($grafica_dias_contratados_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($grafica_dias_contratados_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($grafica_dias_contratados_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $grafica_dias_contratados_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($grafica_dias_contratados_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($grafica_dias_contratados_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $grafica_dias_contratados_list->PageUrl() ?>start=<?php echo $grafica_dias_contratados_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $grafica_dias_contratados_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_dias_contratados_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($grafica_dias_contratados_list->TotalRecs == 0 && $grafica_dias_contratados->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($grafica_dias_contratados_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($grafica_dias_contratados->Export == "") { ?>
<script type="text/javascript">
fgrafica_dias_contratadoslistsrch.Init();
fgrafica_dias_contratadoslist.Init();
</script>
<?php } ?>
<?php
$grafica_dias_contratados_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($grafica_dias_contratados->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$grafica_dias_contratados_list->Page_Terminate();
?>
