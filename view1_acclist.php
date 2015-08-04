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

			// Get basic search values
			$this->LoadBasicSearchValues();

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

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->ID_Formulario, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->USUARIO, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cargo_gme, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_GE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_CC_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->TIPO_INFORME, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FECHA_NOVEDAD, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->DIA, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->MES, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->PTO_INCOMU, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->OBS_punt_inco, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->OBS_ENLACE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nom_Per_Evacu, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nom_Otro_Per_Evacu, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->CC_Otro_Per_Evacu, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cargo_Per_EVA, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Motivo_Eva, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->OBS_EVA, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_Nom_PE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_CAPATAZ, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_Nom_Capata, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_CC_Capata, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Muncipio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Departamento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->F_llegada, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Fecha, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
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
// Form object for search

var fview1_acclistsrch = new ew_Form("fview1_acclistsrch");
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
<?php if ($view1_acc_list->TotalRecs > 0 && $view1_acc_list->ExportOptions->Visible()) { ?>
<?php ##$view1_acc_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($view1_acc_list->SearchOptions->Visible()) { ?>
<?php ##$view1_acc_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($view1_acc->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
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
Módulo en construcción...
<script>
/*
<?php if ($Security->CanSearch()) { ?>
<?php if ($view1_acc->Export == "" && $view1_acc->CurrentAction == "") { ?>
<form name="fview1_acclistsrch" id="fview1_acclistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view1_acc_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fview1_acclistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view1_acc">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($view1_acc_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($view1_acc_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $view1_acc_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($view1_acc_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($view1_acc_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($view1_acc_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($view1_acc_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
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
<?php if ($view1_acc->ID_Formulario->Visible) { // ID_Formulario ?>
	<?php if ($view1_acc->SortUrl($view1_acc->ID_Formulario) == "") { ?>
		<th data-name="ID_Formulario"><div id="elh_view1_acc_ID_Formulario" class="view1_acc_ID_Formulario"><div class="ewTableHeaderCaption"><?php echo $view1_acc->ID_Formulario->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ID_Formulario"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->ID_Formulario) ?>',2);"><div id="elh_view1_acc_ID_Formulario" class="view1_acc_ID_Formulario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->ID_Formulario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->ID_Formulario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->ID_Formulario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->USUARIO->Visible) { // USUARIO ?>
	<?php if ($view1_acc->SortUrl($view1_acc->USUARIO) == "") { ?>
		<th data-name="USUARIO"><div id="elh_view1_acc_USUARIO" class="view1_acc_USUARIO"><div class="ewTableHeaderCaption"><?php echo $view1_acc->USUARIO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="USUARIO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->USUARIO) ?>',2);"><div id="elh_view1_acc_USUARIO" class="view1_acc_USUARIO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->USUARIO->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->USUARIO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->USUARIO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
<?php if ($view1_acc->NOM_GE->Visible) { // NOM_GE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_GE) == "") { ?>
		<th data-name="NOM_GE"><div id="elh_view1_acc_NOM_GE" class="view1_acc_NOM_GE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_GE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_GE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_GE) ?>',2);"><div id="elh_view1_acc_NOM_GE" class="view1_acc_NOM_GE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_GE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_GE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_GE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_PGE->Visible) { // Otro_PGE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_PGE) == "") { ?>
		<th data-name="Otro_PGE"><div id="elh_view1_acc_Otro_PGE" class="view1_acc_Otro_PGE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_PGE) ?>',2);"><div id="elh_view1_acc_Otro_PGE" class="view1_acc_Otro_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_PGE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
<?php if ($view1_acc->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
	<?php if ($view1_acc->SortUrl($view1_acc->TIPO_INFORME) == "") { ?>
		<th data-name="TIPO_INFORME"><div id="elh_view1_acc_TIPO_INFORME" class="view1_acc_TIPO_INFORME"><div class="ewTableHeaderCaption"><?php echo $view1_acc->TIPO_INFORME->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TIPO_INFORME"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->TIPO_INFORME) ?>',2);"><div id="elh_view1_acc_TIPO_INFORME" class="view1_acc_TIPO_INFORME">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->TIPO_INFORME->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->TIPO_INFORME->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->TIPO_INFORME->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->FECHA_NOVEDAD->Visible) { // FECHA_NOVEDAD ?>
	<?php if ($view1_acc->SortUrl($view1_acc->FECHA_NOVEDAD) == "") { ?>
		<th data-name="FECHA_NOVEDAD"><div id="elh_view1_acc_FECHA_NOVEDAD" class="view1_acc_FECHA_NOVEDAD"><div class="ewTableHeaderCaption"><?php echo $view1_acc->FECHA_NOVEDAD->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FECHA_NOVEDAD"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->FECHA_NOVEDAD) ?>',2);"><div id="elh_view1_acc_FECHA_NOVEDAD" class="view1_acc_FECHA_NOVEDAD">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->FECHA_NOVEDAD->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->FECHA_NOVEDAD->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->FECHA_NOVEDAD->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->DIA->Visible) { // DIA ?>
	<?php if ($view1_acc->SortUrl($view1_acc->DIA) == "") { ?>
		<th data-name="DIA"><div id="elh_view1_acc_DIA" class="view1_acc_DIA"><div class="ewTableHeaderCaption"><?php echo $view1_acc->DIA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DIA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->DIA) ?>',2);"><div id="elh_view1_acc_DIA" class="view1_acc_DIA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->DIA->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->DIA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->DIA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->MES->Visible) { // MES ?>
	<?php if ($view1_acc->SortUrl($view1_acc->MES) == "") { ?>
		<th data-name="MES"><div id="elh_view1_acc_MES" class="view1_acc_MES"><div class="ewTableHeaderCaption"><?php echo $view1_acc->MES->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MES"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->MES) ?>',2);"><div id="elh_view1_acc_MES" class="view1_acc_MES">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->MES->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->MES->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->MES->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Num_Evacua->Visible) { // Num_Evacua ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Num_Evacua) == "") { ?>
		<th data-name="Num_Evacua"><div id="elh_view1_acc_Num_Evacua" class="view1_acc_Num_Evacua"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Num_Evacua->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Num_Evacua"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Num_Evacua) ?>',2);"><div id="elh_view1_acc_Num_Evacua" class="view1_acc_Num_Evacua">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Num_Evacua->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Num_Evacua->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Num_Evacua->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->PTO_INCOMU->Visible) { // PTO_INCOMU ?>
	<?php if ($view1_acc->SortUrl($view1_acc->PTO_INCOMU) == "") { ?>
		<th data-name="PTO_INCOMU"><div id="elh_view1_acc_PTO_INCOMU" class="view1_acc_PTO_INCOMU"><div class="ewTableHeaderCaption"><?php echo $view1_acc->PTO_INCOMU->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PTO_INCOMU"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->PTO_INCOMU) ?>',2);"><div id="elh_view1_acc_PTO_INCOMU" class="view1_acc_PTO_INCOMU">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->PTO_INCOMU->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->PTO_INCOMU->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->PTO_INCOMU->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->OBS_punt_inco->Visible) { // OBS_punt_inco ?>
	<?php if ($view1_acc->SortUrl($view1_acc->OBS_punt_inco) == "") { ?>
		<th data-name="OBS_punt_inco"><div id="elh_view1_acc_OBS_punt_inco" class="view1_acc_OBS_punt_inco"><div class="ewTableHeaderCaption"><?php echo $view1_acc->OBS_punt_inco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBS_punt_inco"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->OBS_punt_inco) ?>',2);"><div id="elh_view1_acc_OBS_punt_inco" class="view1_acc_OBS_punt_inco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->OBS_punt_inco->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->OBS_punt_inco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->OBS_punt_inco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->OBS_ENLACE->Visible) { // OBS_ENLACE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->OBS_ENLACE) == "") { ?>
		<th data-name="OBS_ENLACE"><div id="elh_view1_acc_OBS_ENLACE" class="view1_acc_OBS_ENLACE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->OBS_ENLACE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBS_ENLACE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->OBS_ENLACE) ?>',2);"><div id="elh_view1_acc_OBS_ENLACE" class="view1_acc_OBS_ENLACE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->OBS_ENLACE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->OBS_ENLACE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->OBS_ENLACE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Nom_Per_Evacu->Visible) { // Nom_Per_Evacu ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Nom_Per_Evacu) == "") { ?>
		<th data-name="Nom_Per_Evacu"><div id="elh_view1_acc_Nom_Per_Evacu" class="view1_acc_Nom_Per_Evacu"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Nom_Per_Evacu->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nom_Per_Evacu"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Nom_Per_Evacu) ?>',2);"><div id="elh_view1_acc_Nom_Per_Evacu" class="view1_acc_Nom_Per_Evacu">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Nom_Per_Evacu->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Nom_Per_Evacu->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Nom_Per_Evacu->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Nom_Otro_Per_Evacu->Visible) { // Nom_Otro_Per_Evacu ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Nom_Otro_Per_Evacu) == "") { ?>
		<th data-name="Nom_Otro_Per_Evacu"><div id="elh_view1_acc_Nom_Otro_Per_Evacu" class="view1_acc_Nom_Otro_Per_Evacu"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Nom_Otro_Per_Evacu->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nom_Otro_Per_Evacu"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Nom_Otro_Per_Evacu) ?>',2);"><div id="elh_view1_acc_Nom_Otro_Per_Evacu" class="view1_acc_Nom_Otro_Per_Evacu">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Nom_Otro_Per_Evacu->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Nom_Otro_Per_Evacu->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Nom_Otro_Per_Evacu->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->CC_Otro_Per_Evacu->Visible) { // CC_Otro_Per_Evacu ?>
	<?php if ($view1_acc->SortUrl($view1_acc->CC_Otro_Per_Evacu) == "") { ?>
		<th data-name="CC_Otro_Per_Evacu"><div id="elh_view1_acc_CC_Otro_Per_Evacu" class="view1_acc_CC_Otro_Per_Evacu"><div class="ewTableHeaderCaption"><?php echo $view1_acc->CC_Otro_Per_Evacu->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CC_Otro_Per_Evacu"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->CC_Otro_Per_Evacu) ?>',2);"><div id="elh_view1_acc_CC_Otro_Per_Evacu" class="view1_acc_CC_Otro_Per_Evacu">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->CC_Otro_Per_Evacu->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->CC_Otro_Per_Evacu->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->CC_Otro_Per_Evacu->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Cargo_Per_EVA->Visible) { // Cargo_Per_EVA ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Cargo_Per_EVA) == "") { ?>
		<th data-name="Cargo_Per_EVA"><div id="elh_view1_acc_Cargo_Per_EVA" class="view1_acc_Cargo_Per_EVA"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Cargo_Per_EVA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_Per_EVA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Cargo_Per_EVA) ?>',2);"><div id="elh_view1_acc_Cargo_Per_EVA" class="view1_acc_Cargo_Per_EVA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Cargo_Per_EVA->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Cargo_Per_EVA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Cargo_Per_EVA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Motivo_Eva->Visible) { // Motivo_Eva ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Motivo_Eva) == "") { ?>
		<th data-name="Motivo_Eva"><div id="elh_view1_acc_Motivo_Eva" class="view1_acc_Motivo_Eva"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Motivo_Eva->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Motivo_Eva"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Motivo_Eva) ?>',2);"><div id="elh_view1_acc_Motivo_Eva" class="view1_acc_Motivo_Eva">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Motivo_Eva->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Motivo_Eva->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Motivo_Eva->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->OBS_EVA->Visible) { // OBS_EVA ?>
	<?php if ($view1_acc->SortUrl($view1_acc->OBS_EVA) == "") { ?>
		<th data-name="OBS_EVA"><div id="elh_view1_acc_OBS_EVA" class="view1_acc_OBS_EVA"><div class="ewTableHeaderCaption"><?php echo $view1_acc->OBS_EVA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBS_EVA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->OBS_EVA) ?>',2);"><div id="elh_view1_acc_OBS_EVA" class="view1_acc_OBS_EVA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->OBS_EVA->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->OBS_EVA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->OBS_EVA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_PE->Visible) { // NOM_PE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_PE) == "") { ?>
		<th data-name="NOM_PE"><div id="elh_view1_acc_NOM_PE" class="view1_acc_NOM_PE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_PE) ?>',2);"><div id="elh_view1_acc_NOM_PE" class="view1_acc_NOM_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_Nom_PE->Visible) { // Otro_Nom_PE ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_Nom_PE) == "") { ?>
		<th data-name="Otro_Nom_PE"><div id="elh_view1_acc_Otro_Nom_PE" class="view1_acc_Otro_Nom_PE"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_Nom_PE) ?>',2);"><div id="elh_view1_acc_Otro_Nom_PE" class="view1_acc_Otro_Nom_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_Nom_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_Nom_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
	<?php if ($view1_acc->SortUrl($view1_acc->NOM_CAPATAZ) == "") { ?>
		<th data-name="NOM_CAPATAZ"><div id="elh_view1_acc_NOM_CAPATAZ" class="view1_acc_NOM_CAPATAZ"><div class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_CAPATAZ->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_CAPATAZ"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->NOM_CAPATAZ) ?>',2);"><div id="elh_view1_acc_NOM_CAPATAZ" class="view1_acc_NOM_CAPATAZ">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->NOM_CAPATAZ->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->NOM_CAPATAZ->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->NOM_CAPATAZ->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_Nom_Capata->Visible) { // Otro_Nom_Capata ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_Nom_Capata) == "") { ?>
		<th data-name="Otro_Nom_Capata"><div id="elh_view1_acc_Otro_Nom_Capata" class="view1_acc_Otro_Nom_Capata"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_Capata->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Nom_Capata"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_Nom_Capata) ?>',2);"><div id="elh_view1_acc_Otro_Nom_Capata" class="view1_acc_Otro_Nom_Capata">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_Nom_Capata->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_Nom_Capata->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_Nom_Capata->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Otro_CC_Capata->Visible) { // Otro_CC_Capata ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Otro_CC_Capata) == "") { ?>
		<th data-name="Otro_CC_Capata"><div id="elh_view1_acc_Otro_CC_Capata" class="view1_acc_Otro_CC_Capata"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_Capata->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_Capata"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Otro_CC_Capata) ?>',2);"><div id="elh_view1_acc_Otro_CC_Capata" class="view1_acc_Otro_CC_Capata">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Otro_CC_Capata->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Otro_CC_Capata->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Otro_CC_Capata->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
<?php if ($view1_acc->Departamento->Visible) { // Departamento ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Departamento) == "") { ?>
		<th data-name="Departamento"><div id="elh_view1_acc_Departamento" class="view1_acc_Departamento"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Departamento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Departamento) ?>',2);"><div id="elh_view1_acc_Departamento" class="view1_acc_Departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Departamento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->F_llegada->Visible) { // F_llegada ?>
	<?php if ($view1_acc->SortUrl($view1_acc->F_llegada) == "") { ?>
		<th data-name="F_llegada"><div id="elh_view1_acc_F_llegada" class="view1_acc_F_llegada"><div class="ewTableHeaderCaption"><?php echo $view1_acc->F_llegada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F_llegada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->F_llegada) ?>',2);"><div id="elh_view1_acc_F_llegada" class="view1_acc_F_llegada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->F_llegada->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->F_llegada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->F_llegada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1_acc->Fecha->Visible) { // Fecha ?>
	<?php if ($view1_acc->SortUrl($view1_acc->Fecha) == "") { ?>
		<th data-name="Fecha"><div id="elh_view1_acc_Fecha" class="view1_acc_Fecha"><div class="ewTableHeaderCaption"><?php echo $view1_acc->Fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1_acc->SortUrl($view1_acc->Fecha) ?>',2);"><div id="elh_view1_acc_Fecha" class="view1_acc_Fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1_acc->Fecha->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1_acc->Fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1_acc->Fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
	<?php if ($view1_acc->ID_Formulario->Visible) { // ID_Formulario ?>
		<td data-name="ID_Formulario"<?php echo $view1_acc->ID_Formulario->CellAttributes() ?>>
<span<?php echo $view1_acc->ID_Formulario->ViewAttributes() ?>>
<?php echo $view1_acc->ID_Formulario->ListViewValue() ?></span>
<a id="<?php echo $view1_acc_list->PageObjName . "_row_" . $view1_acc_list->RowCnt ?>"></a></td>
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
	<?php if ($view1_acc->NOM_GE->Visible) { // NOM_GE ?>
		<td data-name="NOM_GE"<?php echo $view1_acc->NOM_GE->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_GE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_GE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_PGE->Visible) { // Otro_PGE ?>
		<td data-name="Otro_PGE"<?php echo $view1_acc->Otro_PGE->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<td data-name="Otro_CC_PGE"<?php echo $view1_acc->Otro_CC_PGE->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_CC_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
		<td data-name="TIPO_INFORME"<?php echo $view1_acc->TIPO_INFORME->CellAttributes() ?>>
<span<?php echo $view1_acc->TIPO_INFORME->ViewAttributes() ?>>
<?php echo $view1_acc->TIPO_INFORME->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->FECHA_NOVEDAD->Visible) { // FECHA_NOVEDAD ?>
		<td data-name="FECHA_NOVEDAD"<?php echo $view1_acc->FECHA_NOVEDAD->CellAttributes() ?>>
<span<?php echo $view1_acc->FECHA_NOVEDAD->ViewAttributes() ?>>
<?php echo $view1_acc->FECHA_NOVEDAD->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->DIA->Visible) { // DIA ?>
		<td data-name="DIA"<?php echo $view1_acc->DIA->CellAttributes() ?>>
<span<?php echo $view1_acc->DIA->ViewAttributes() ?>>
<?php echo $view1_acc->DIA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->MES->Visible) { // MES ?>
		<td data-name="MES"<?php echo $view1_acc->MES->CellAttributes() ?>>
<span<?php echo $view1_acc->MES->ViewAttributes() ?>>
<?php echo $view1_acc->MES->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Num_Evacua->Visible) { // Num_Evacua ?>
		<td data-name="Num_Evacua"<?php echo $view1_acc->Num_Evacua->CellAttributes() ?>>
<span<?php echo $view1_acc->Num_Evacua->ViewAttributes() ?>>
<?php echo $view1_acc->Num_Evacua->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->PTO_INCOMU->Visible) { // PTO_INCOMU ?>
		<td data-name="PTO_INCOMU"<?php echo $view1_acc->PTO_INCOMU->CellAttributes() ?>>
<span<?php echo $view1_acc->PTO_INCOMU->ViewAttributes() ?>>
<?php echo $view1_acc->PTO_INCOMU->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->OBS_punt_inco->Visible) { // OBS_punt_inco ?>
		<td data-name="OBS_punt_inco"<?php echo $view1_acc->OBS_punt_inco->CellAttributes() ?>>
<span<?php echo $view1_acc->OBS_punt_inco->ViewAttributes() ?>>
<?php echo $view1_acc->OBS_punt_inco->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->OBS_ENLACE->Visible) { // OBS_ENLACE ?>
		<td data-name="OBS_ENLACE"<?php echo $view1_acc->OBS_ENLACE->CellAttributes() ?>>
<span<?php echo $view1_acc->OBS_ENLACE->ViewAttributes() ?>>
<?php echo $view1_acc->OBS_ENLACE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Nom_Per_Evacu->Visible) { // Nom_Per_Evacu ?>
		<td data-name="Nom_Per_Evacu"<?php echo $view1_acc->Nom_Per_Evacu->CellAttributes() ?>>
<span<?php echo $view1_acc->Nom_Per_Evacu->ViewAttributes() ?>>
<?php echo $view1_acc->Nom_Per_Evacu->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Nom_Otro_Per_Evacu->Visible) { // Nom_Otro_Per_Evacu ?>
		<td data-name="Nom_Otro_Per_Evacu"<?php echo $view1_acc->Nom_Otro_Per_Evacu->CellAttributes() ?>>
<span<?php echo $view1_acc->Nom_Otro_Per_Evacu->ViewAttributes() ?>>
<?php echo $view1_acc->Nom_Otro_Per_Evacu->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->CC_Otro_Per_Evacu->Visible) { // CC_Otro_Per_Evacu ?>
		<td data-name="CC_Otro_Per_Evacu"<?php echo $view1_acc->CC_Otro_Per_Evacu->CellAttributes() ?>>
<span<?php echo $view1_acc->CC_Otro_Per_Evacu->ViewAttributes() ?>>
<?php echo $view1_acc->CC_Otro_Per_Evacu->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Cargo_Per_EVA->Visible) { // Cargo_Per_EVA ?>
		<td data-name="Cargo_Per_EVA"<?php echo $view1_acc->Cargo_Per_EVA->CellAttributes() ?>>
<span<?php echo $view1_acc->Cargo_Per_EVA->ViewAttributes() ?>>
<?php echo $view1_acc->Cargo_Per_EVA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Motivo_Eva->Visible) { // Motivo_Eva ?>
		<td data-name="Motivo_Eva"<?php echo $view1_acc->Motivo_Eva->CellAttributes() ?>>
<span<?php echo $view1_acc->Motivo_Eva->ViewAttributes() ?>>
<?php echo $view1_acc->Motivo_Eva->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->OBS_EVA->Visible) { // OBS_EVA ?>
		<td data-name="OBS_EVA"<?php echo $view1_acc->OBS_EVA->CellAttributes() ?>>
<span<?php echo $view1_acc->OBS_EVA->ViewAttributes() ?>>
<?php echo $view1_acc->OBS_EVA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_PE->Visible) { // NOM_PE ?>
		<td data-name="NOM_PE"<?php echo $view1_acc->NOM_PE->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_PE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_Nom_PE->Visible) { // Otro_Nom_PE ?>
		<td data-name="Otro_Nom_PE"<?php echo $view1_acc->Otro_Nom_PE->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_Nom_PE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
		<td data-name="NOM_CAPATAZ"<?php echo $view1_acc->NOM_CAPATAZ->CellAttributes() ?>>
<span<?php echo $view1_acc->NOM_CAPATAZ->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_CAPATAZ->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_Nom_Capata->Visible) { // Otro_Nom_Capata ?>
		<td data-name="Otro_Nom_Capata"<?php echo $view1_acc->Otro_Nom_Capata->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_Nom_Capata->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_Capata->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Otro_CC_Capata->Visible) { // Otro_CC_Capata ?>
		<td data-name="Otro_CC_Capata"<?php echo $view1_acc->Otro_CC_Capata->CellAttributes() ?>>
<span<?php echo $view1_acc->Otro_CC_Capata->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_Capata->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Muncipio->Visible) { // Muncipio ?>
		<td data-name="Muncipio"<?php echo $view1_acc->Muncipio->CellAttributes() ?>>
<span<?php echo $view1_acc->Muncipio->ViewAttributes() ?>>
<?php echo $view1_acc->Muncipio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Departamento->Visible) { // Departamento ?>
		<td data-name="Departamento"<?php echo $view1_acc->Departamento->CellAttributes() ?>>
<span<?php echo $view1_acc->Departamento->ViewAttributes() ?>>
<?php echo $view1_acc->Departamento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->F_llegada->Visible) { // F_llegada ?>
		<td data-name="F_llegada"<?php echo $view1_acc->F_llegada->CellAttributes() ?>>
<span<?php echo $view1_acc->F_llegada->ViewAttributes() ?>>
<?php echo $view1_acc->F_llegada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view1_acc->Fecha->Visible) { // Fecha ?>
		<td data-name="Fecha"<?php echo $view1_acc->Fecha->CellAttributes() ?>>
<span<?php echo $view1_acc->Fecha->ViewAttributes() ?>>
<?php echo $view1_acc->Fecha->ListViewValue() ?></span>
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
<?php } ?>*/
</script>
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
