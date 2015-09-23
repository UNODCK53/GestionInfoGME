<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "view_idinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$view_id_list = NULL; // Initialize page object first

class cview_id_list extends cview_id {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_id';

	// Page object name
	var $PageObjName = 'view_id_list';

	// Grid form hidden field names
	var $FormName = 'fview_idlist';
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

		// Table object (view_id)
		if (!isset($GLOBALS["view_id"]) || get_class($GLOBALS["view_id"]) == "cview_id") {
			$GLOBALS["view_id"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_id"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view_idadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view_iddelete.php";
		$this->MultiUpdateUrl = "view_idupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_id', TRUE);

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
		global $EW_EXPORT, $view_id;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view_id);
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
		$this->BuildSearchSql($sWhere, $this->NOM_PGE, $Default, FALSE); // NOM_PGE
		$this->BuildSearchSql($sWhere, $this->Otro_NOM_PGE, $Default, FALSE); // Otro_NOM_PGE
		$this->BuildSearchSql($sWhere, $this->FECHA_REPORT, $Default, FALSE); // FECHA_REPORT
		$this->BuildSearchSql($sWhere, $this->Departamento, $Default, FALSE); // Departamento
		$this->BuildSearchSql($sWhere, $this->Muncipio, $Default, FALSE); // Muncipio
		$this->BuildSearchSql($sWhere, $this->FUERZA, $Default, FALSE); // FUERZA
		$this->BuildSearchSql($sWhere, $this->_3_MUSE, $Default, FALSE); // 3_MUSE
		$this->BuildSearchSql($sWhere, $this->AD1O, $Default, FALSE); // AÑO
		$this->BuildSearchSql($sWhere, $this->FASE, $Default, FALSE); // FASE
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
			$this->NOM_PGE->AdvancedSearch->Save(); // NOM_PGE
			$this->Otro_NOM_PGE->AdvancedSearch->Save(); // Otro_NOM_PGE
			$this->FECHA_REPORT->AdvancedSearch->Save(); // FECHA_REPORT
			$this->Departamento->AdvancedSearch->Save(); // Departamento
			$this->Muncipio->AdvancedSearch->Save(); // Muncipio
			$this->FUERZA->AdvancedSearch->Save(); // FUERZA
			$this->_3_MUSE->AdvancedSearch->Save(); // 3_MUSE
			$this->AD1O->AdvancedSearch->Save(); // AÑO
			$this->FASE->AdvancedSearch->Save(); // FASE
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
		$this->BuildBasicSearchSQL($sWhere, $this->NOM_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Otro_NOM_PGE, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FECHA_REPORT, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Departamento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Muncipio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FUERZA, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->AD1O, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->FASE, $arKeywords, $type);
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
		if ($this->NOM_PGE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Otro_NOM_PGE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FECHA_REPORT->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Departamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Muncipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FUERZA->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_3_MUSE->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->AD1O->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->FASE->AdvancedSearch->IssetSession())
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
		$this->NOM_PGE->AdvancedSearch->UnsetSession();
		$this->Otro_NOM_PGE->AdvancedSearch->UnsetSession();
		$this->FECHA_REPORT->AdvancedSearch->UnsetSession();
		$this->Departamento->AdvancedSearch->UnsetSession();
		$this->Muncipio->AdvancedSearch->UnsetSession();
		$this->FUERZA->AdvancedSearch->UnsetSession();
		$this->_3_MUSE->AdvancedSearch->UnsetSession();
		$this->AD1O->AdvancedSearch->UnsetSession();
		$this->FASE->AdvancedSearch->UnsetSession();
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
		$this->NOM_PGE->AdvancedSearch->Load();
		$this->Otro_NOM_PGE->AdvancedSearch->Load();
		$this->FECHA_REPORT->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->FUERZA->AdvancedSearch->Load();
		$this->_3_MUSE->AdvancedSearch->Load();
		$this->AD1O->AdvancedSearch->Load();
		$this->FASE->AdvancedSearch->Load();
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
			$this->UpdateSort($this->NOM_PGE, $bCtrl); // NOM_PGE
			$this->UpdateSort($this->Otro_NOM_PGE, $bCtrl); // Otro_NOM_PGE
			$this->UpdateSort($this->Otro_CC_PGE, $bCtrl); // Otro_CC_PGE
			$this->UpdateSort($this->TIPO_INFORME, $bCtrl); // TIPO_INFORME
			$this->UpdateSort($this->FECHA_REPORT, $bCtrl); // FECHA_REPORT
			$this->UpdateSort($this->DIA, $bCtrl); // DIA
			$this->UpdateSort($this->MES, $bCtrl); // MES
			$this->UpdateSort($this->Departamento, $bCtrl); // Departamento
			$this->UpdateSort($this->Muncipio, $bCtrl); // Muncipio
			$this->UpdateSort($this->TEMA, $bCtrl); // TEMA
			$this->UpdateSort($this->Otro_Tema, $bCtrl); // Otro_Tema
			$this->UpdateSort($this->OBSERVACION, $bCtrl); // OBSERVACION
			$this->UpdateSort($this->NOM_VDA, $bCtrl); // NOM_VDA
			$this->UpdateSort($this->Ha_Coca, $bCtrl); // Ha_Coca
			$this->UpdateSort($this->Ha_Amapola, $bCtrl); // Ha_Amapola
			$this->UpdateSort($this->Ha_Marihuana, $bCtrl); // Ha_Marihuana
			$this->UpdateSort($this->T_erradi, $bCtrl); // T_erradi
			$this->UpdateSort($this->LATITUD_sector, $bCtrl); // LATITUD_sector
			$this->UpdateSort($this->GRA_LAT_Sector, $bCtrl); // GRA_LAT_Sector
			$this->UpdateSort($this->MIN_LAT_Sector, $bCtrl); // MIN_LAT_Sector
			$this->UpdateSort($this->SEG_LAT_Sector, $bCtrl); // SEG_LAT_Sector
			$this->UpdateSort($this->GRA_LONG_Sector, $bCtrl); // GRA_LONG_Sector
			$this->UpdateSort($this->MIN_LONG_Sector, $bCtrl); // MIN_LONG_Sector
			$this->UpdateSort($this->SEG_LONG_Sector, $bCtrl); // SEG_LONG_Sector
			$this->UpdateSort($this->Ini_Jorna, $bCtrl); // Ini_Jorna
			$this->UpdateSort($this->Fin_Jorna, $bCtrl); // Fin_Jorna
			$this->UpdateSort($this->Situ_Especial, $bCtrl); // Situ_Especial
			$this->UpdateSort($this->Adm_GME, $bCtrl); // Adm_GME
			$this->UpdateSort($this->_1_Abastecimiento, $bCtrl); // 1_Abastecimiento
			$this->UpdateSort($this->_1_Acompanamiento_firma_GME, $bCtrl); // 1_Acompanamiento_firma_GME
			$this->UpdateSort($this->_1_Apoyo_zonal_sin_punto_asignado, $bCtrl); // 1_Apoyo_zonal_sin_punto_asignado
			$this->UpdateSort($this->_1_Descanso_en_dia_habil, $bCtrl); // 1_Descanso_en_dia_habil
			$this->UpdateSort($this->_1_Descanso_festivo_dominical, $bCtrl); // 1_Descanso_festivo_dominical
			$this->UpdateSort($this->_1_Dia_compensatorio, $bCtrl); // 1_Dia_compensatorio
			$this->UpdateSort($this->_1_Erradicacion_en_dia_festivo, $bCtrl); // 1_Erradicacion_en_dia_festivo
			$this->UpdateSort($this->_1_Espera_helicoptero_Helistar, $bCtrl); // 1_Espera_helicoptero_Helistar
			$this->UpdateSort($this->_1_Extraccion, $bCtrl); // 1_Extraccion
			$this->UpdateSort($this->_1_Firma_contrato_GME, $bCtrl); // 1_Firma_contrato_GME
			$this->UpdateSort($this->_1_Induccion_Apoyo_Zonal, $bCtrl); // 1_Induccion_Apoyo_Zonal
			$this->UpdateSort($this->_1_Insercion, $bCtrl); // 1_Insercion
			$this->UpdateSort($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase, $bCtrl); // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->UpdateSort($this->_1_Novedad_apoyo_zonal, $bCtrl); // 1_Novedad_apoyo_zonal
			$this->UpdateSort($this->_1_Novedad_enfermero, $bCtrl); // 1_Novedad_enfermero
			$this->UpdateSort($this->_1_Punto_fuera_del_area_de_erradicacion, $bCtrl); // 1_Punto_fuera_del_area_de_erradicacion
			$this->UpdateSort($this->_1_Transporte_bus, $bCtrl); // 1_Transporte_bus
			$this->UpdateSort($this->_1_Traslado_apoyo_zonal, $bCtrl); // 1_Traslado_apoyo_zonal
			$this->UpdateSort($this->_1_Traslado_area_vivac, $bCtrl); // 1_Traslado_area_vivac
			$this->UpdateSort($this->Adm_Fuerza, $bCtrl); // Adm_Fuerza
			$this->UpdateSort($this->_2_A_la_espera_definicion_nuevo_punto_FP, $bCtrl); // 2_A_la_espera_definicion_nuevo_punto_FP
			$this->UpdateSort($this->_2_Espera_helicoptero_FP_de_seguridad, $bCtrl); // 2_Espera_helicoptero_FP_de_seguridad
			$this->UpdateSort($this->_2_Espera_helicoptero_FP_que_abastece, $bCtrl); // 2_Espera_helicoptero_FP_que_abastece
			$this->UpdateSort($this->_2_Induccion_FP, $bCtrl); // 2_Induccion_FP
			$this->UpdateSort($this->_2_Novedad_canino_o_del_grupo_de_deteccion, $bCtrl); // 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->UpdateSort($this->_2_Problemas_fuerza_publica, $bCtrl); // 2_Problemas_fuerza_publica
			$this->UpdateSort($this->_2_Sin_seguridad, $bCtrl); // 2_Sin_seguridad
			$this->UpdateSort($this->Sit_Seguridad, $bCtrl); // Sit_Seguridad
			$this->UpdateSort($this->_3_AEI_controlado, $bCtrl); // 3_AEI_controlado
			$this->UpdateSort($this->_3_AEI_no_controlado, $bCtrl); // 3_AEI_no_controlado
			$this->UpdateSort($this->_3_Bloqueo_parcial_de_la_comunidad, $bCtrl); // 3_Bloqueo_parcial_de_la_comunidad
			$this->UpdateSort($this->_3_Bloqueo_total_de_la_comunidad, $bCtrl); // 3_Bloqueo_total_de_la_comunidad
			$this->UpdateSort($this->_3_Combate, $bCtrl); // 3_Combate
			$this->UpdateSort($this->_3_Hostigamiento, $bCtrl); // 3_Hostigamiento
			$this->UpdateSort($this->_3_MAP_Controlada, $bCtrl); // 3_MAP_Controlada
			$this->UpdateSort($this->_3_MAP_No_controlada, $bCtrl); // 3_MAP_No_controlada
			$this->UpdateSort($this->_3_MUSE, $bCtrl); // 3_MUSE
			$this->UpdateSort($this->_3_Operaciones_de_seguridad, $bCtrl); // 3_Operaciones_de_seguridad
			$this->UpdateSort($this->LATITUD_segurid, $bCtrl); // LATITUD_segurid
			$this->UpdateSort($this->GRA_LAT_segurid, $bCtrl); // GRA_LAT_segurid
			$this->UpdateSort($this->MIN_LAT_segurid, $bCtrl); // MIN_LAT_segurid
			$this->UpdateSort($this->SEG_LAT_segurid, $bCtrl); // SEG_LAT_segurid
			$this->UpdateSort($this->GRA_LONG_seguri, $bCtrl); // GRA_LONG_seguri
			$this->UpdateSort($this->MIN_LONG_seguri, $bCtrl); // MIN_LONG_seguri
			$this->UpdateSort($this->SEG_LONG_seguri, $bCtrl); // SEG_LONG_seguri
			$this->UpdateSort($this->Novedad, $bCtrl); // Novedad
			$this->UpdateSort($this->_4_Epidemia, $bCtrl); // 4_Epidemia
			$this->UpdateSort($this->_4_Novedad_climatologica, $bCtrl); // 4_Novedad_climatologica
			$this->UpdateSort($this->_4_Registro_de_cultivos, $bCtrl); // 4_Registro_de_cultivos
			$this->UpdateSort($this->_4_Zona_con_cultivos_muy_dispersos, $bCtrl); // 4_Zona_con_cultivos_muy_dispersos
			$this->UpdateSort($this->_4_Zona_de_cruce_de_rios_caudalosos, $bCtrl); // 4_Zona_de_cruce_de_rios_caudalosos
			$this->UpdateSort($this->_4_Zona_sin_cultivos, $bCtrl); // 4_Zona_sin_cultivos
			$this->UpdateSort($this->Num_Erra_Salen, $bCtrl); // Num_Erra_Salen
			$this->UpdateSort($this->Num_Erra_Quedan, $bCtrl); // Num_Erra_Quedan
			$this->UpdateSort($this->No_ENFERMERO, $bCtrl); // No_ENFERMERO
			$this->UpdateSort($this->NUM_FP, $bCtrl); // NUM_FP
			$this->UpdateSort($this->NUM_Perso_EVA, $bCtrl); // NUM_Perso_EVA
			$this->UpdateSort($this->NUM_Poli, $bCtrl); // NUM_Poli
			$this->UpdateSort($this->AD1O, $bCtrl); // AÑO
			$this->UpdateSort($this->FASE, $bCtrl); // FASE
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
				$this->NOM_PGE->setSort("");
				$this->Otro_NOM_PGE->setSort("");
				$this->Otro_CC_PGE->setSort("");
				$this->TIPO_INFORME->setSort("");
				$this->FECHA_REPORT->setSort("");
				$this->DIA->setSort("");
				$this->MES->setSort("");
				$this->Departamento->setSort("");
				$this->Muncipio->setSort("");
				$this->TEMA->setSort("");
				$this->Otro_Tema->setSort("");
				$this->OBSERVACION->setSort("");
				$this->NOM_VDA->setSort("");
				$this->Ha_Coca->setSort("");
				$this->Ha_Amapola->setSort("");
				$this->Ha_Marihuana->setSort("");
				$this->T_erradi->setSort("");
				$this->LATITUD_sector->setSort("");
				$this->GRA_LAT_Sector->setSort("");
				$this->MIN_LAT_Sector->setSort("");
				$this->SEG_LAT_Sector->setSort("");
				$this->GRA_LONG_Sector->setSort("");
				$this->MIN_LONG_Sector->setSort("");
				$this->SEG_LONG_Sector->setSort("");
				$this->Ini_Jorna->setSort("");
				$this->Fin_Jorna->setSort("");
				$this->Situ_Especial->setSort("");
				$this->Adm_GME->setSort("");
				$this->_1_Abastecimiento->setSort("");
				$this->_1_Acompanamiento_firma_GME->setSort("");
				$this->_1_Apoyo_zonal_sin_punto_asignado->setSort("");
				$this->_1_Descanso_en_dia_habil->setSort("");
				$this->_1_Descanso_festivo_dominical->setSort("");
				$this->_1_Dia_compensatorio->setSort("");
				$this->_1_Erradicacion_en_dia_festivo->setSort("");
				$this->_1_Espera_helicoptero_Helistar->setSort("");
				$this->_1_Extraccion->setSort("");
				$this->_1_Firma_contrato_GME->setSort("");
				$this->_1_Induccion_Apoyo_Zonal->setSort("");
				$this->_1_Insercion->setSort("");
				$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->setSort("");
				$this->_1_Novedad_apoyo_zonal->setSort("");
				$this->_1_Novedad_enfermero->setSort("");
				$this->_1_Punto_fuera_del_area_de_erradicacion->setSort("");
				$this->_1_Transporte_bus->setSort("");
				$this->_1_Traslado_apoyo_zonal->setSort("");
				$this->_1_Traslado_area_vivac->setSort("");
				$this->Adm_Fuerza->setSort("");
				$this->_2_A_la_espera_definicion_nuevo_punto_FP->setSort("");
				$this->_2_Espera_helicoptero_FP_de_seguridad->setSort("");
				$this->_2_Espera_helicoptero_FP_que_abastece->setSort("");
				$this->_2_Induccion_FP->setSort("");
				$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setSort("");
				$this->_2_Problemas_fuerza_publica->setSort("");
				$this->_2_Sin_seguridad->setSort("");
				$this->Sit_Seguridad->setSort("");
				$this->_3_AEI_controlado->setSort("");
				$this->_3_AEI_no_controlado->setSort("");
				$this->_3_Bloqueo_parcial_de_la_comunidad->setSort("");
				$this->_3_Bloqueo_total_de_la_comunidad->setSort("");
				$this->_3_Combate->setSort("");
				$this->_3_Hostigamiento->setSort("");
				$this->_3_MAP_Controlada->setSort("");
				$this->_3_MAP_No_controlada->setSort("");
				$this->_3_MUSE->setSort("");
				$this->_3_Operaciones_de_seguridad->setSort("");
				$this->LATITUD_segurid->setSort("");
				$this->GRA_LAT_segurid->setSort("");
				$this->MIN_LAT_segurid->setSort("");
				$this->SEG_LAT_segurid->setSort("");
				$this->GRA_LONG_seguri->setSort("");
				$this->MIN_LONG_seguri->setSort("");
				$this->SEG_LONG_seguri->setSort("");
				$this->Novedad->setSort("");
				$this->_4_Epidemia->setSort("");
				$this->_4_Novedad_climatologica->setSort("");
				$this->_4_Registro_de_cultivos->setSort("");
				$this->_4_Zona_con_cultivos_muy_dispersos->setSort("");
				$this->_4_Zona_de_cruce_de_rios_caudalosos->setSort("");
				$this->_4_Zona_sin_cultivos->setSort("");
				$this->Num_Erra_Salen->setSort("");
				$this->Num_Erra_Quedan->setSort("");
				$this->No_ENFERMERO->setSort("");
				$this->NUM_FP->setSort("");
				$this->NUM_Perso_EVA->setSort("");
				$this->NUM_Poli->setSort("");
				$this->AD1O->setSort("");
				$this->FASE->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fview_idlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview_idlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// NOM_PGE
		$this->NOM_PGE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_NOM_PGE"]);
		if ($this->NOM_PGE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->NOM_PGE->AdvancedSearch->SearchOperator = @$_GET["z_NOM_PGE"];

		// Otro_NOM_PGE
		$this->Otro_NOM_PGE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Otro_NOM_PGE"]);
		if ($this->Otro_NOM_PGE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Otro_NOM_PGE->AdvancedSearch->SearchOperator = @$_GET["z_Otro_NOM_PGE"];

		// FECHA_REPORT
		$this->FECHA_REPORT->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FECHA_REPORT"]);
		if ($this->FECHA_REPORT->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FECHA_REPORT->AdvancedSearch->SearchOperator = @$_GET["z_FECHA_REPORT"];

		// Departamento
		$this->Departamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Departamento"]);
		if ($this->Departamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Departamento->AdvancedSearch->SearchOperator = @$_GET["z_Departamento"];

		// Muncipio
		$this->Muncipio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Muncipio"]);
		if ($this->Muncipio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Muncipio->AdvancedSearch->SearchOperator = @$_GET["z_Muncipio"];

		// FUERZA
		$this->FUERZA->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FUERZA"]);
		if ($this->FUERZA->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FUERZA->AdvancedSearch->SearchOperator = @$_GET["z_FUERZA"];

		// 3_MUSE
		$this->_3_MUSE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__3_MUSE"]);
		if ($this->_3_MUSE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_3_MUSE->AdvancedSearch->SearchOperator = @$_GET["z__3_MUSE"];

		// AÑO
		$this->AD1O->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_AD1O"]);
		if ($this->AD1O->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->AD1O->AdvancedSearch->SearchOperator = @$_GET["z_AD1O"];

		// FASE
		$this->FASE->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_FASE"]);
		if ($this->FASE->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->FASE->AdvancedSearch->SearchOperator = @$_GET["z_FASE"];

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
		$this->NOM_PGE->setDbValue($rs->fields('NOM_PGE'));
		$this->Otro_NOM_PGE->setDbValue($rs->fields('Otro_NOM_PGE'));
		$this->Otro_CC_PGE->setDbValue($rs->fields('Otro_CC_PGE'));
		$this->TIPO_INFORME->setDbValue($rs->fields('TIPO_INFORME'));
		$this->FECHA_REPORT->setDbValue($rs->fields('FECHA_REPORT'));
		$this->DIA->setDbValue($rs->fields('DIA'));
		$this->MES->setDbValue($rs->fields('MES'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->TEMA->setDbValue($rs->fields('TEMA'));
		$this->Otro_Tema->setDbValue($rs->fields('Otro_Tema'));
		$this->OBSERVACION->setDbValue($rs->fields('OBSERVACION'));
		$this->FUERZA->setDbValue($rs->fields('FUERZA'));
		$this->NOM_VDA->setDbValue($rs->fields('NOM_VDA'));
		$this->Ha_Coca->setDbValue($rs->fields('Ha_Coca'));
		$this->Ha_Amapola->setDbValue($rs->fields('Ha_Amapola'));
		$this->Ha_Marihuana->setDbValue($rs->fields('Ha_Marihuana'));
		$this->T_erradi->setDbValue($rs->fields('T_erradi'));
		$this->LATITUD_sector->setDbValue($rs->fields('LATITUD_sector'));
		$this->GRA_LAT_Sector->setDbValue($rs->fields('GRA_LAT_Sector'));
		$this->MIN_LAT_Sector->setDbValue($rs->fields('MIN_LAT_Sector'));
		$this->SEG_LAT_Sector->setDbValue($rs->fields('SEG_LAT_Sector'));
		$this->GRA_LONG_Sector->setDbValue($rs->fields('GRA_LONG_Sector'));
		$this->MIN_LONG_Sector->setDbValue($rs->fields('MIN_LONG_Sector'));
		$this->SEG_LONG_Sector->setDbValue($rs->fields('SEG_LONG_Sector'));
		$this->Ini_Jorna->setDbValue($rs->fields('Ini_Jorna'));
		$this->Fin_Jorna->setDbValue($rs->fields('Fin_Jorna'));
		$this->Situ_Especial->setDbValue($rs->fields('Situ_Especial'));
		$this->Adm_GME->setDbValue($rs->fields('Adm_GME'));
		$this->_1_Abastecimiento->setDbValue($rs->fields('1_Abastecimiento'));
		$this->_1_Acompanamiento_firma_GME->setDbValue($rs->fields('1_Acompanamiento_firma_GME'));
		$this->_1_Apoyo_zonal_sin_punto_asignado->setDbValue($rs->fields('1_Apoyo_zonal_sin_punto_asignado'));
		$this->_1_Descanso_en_dia_habil->setDbValue($rs->fields('1_Descanso_en_dia_habil'));
		$this->_1_Descanso_festivo_dominical->setDbValue($rs->fields('1_Descanso_festivo_dominical'));
		$this->_1_Dia_compensatorio->setDbValue($rs->fields('1_Dia_compensatorio'));
		$this->_1_Erradicacion_en_dia_festivo->setDbValue($rs->fields('1_Erradicacion_en_dia_festivo'));
		$this->_1_Espera_helicoptero_Helistar->setDbValue($rs->fields('1_Espera_helicoptero_Helistar'));
		$this->_1_Extraccion->setDbValue($rs->fields('1_Extraccion'));
		$this->_1_Firma_contrato_GME->setDbValue($rs->fields('1_Firma_contrato_GME'));
		$this->_1_Induccion_Apoyo_Zonal->setDbValue($rs->fields('1_Induccion_Apoyo_Zonal'));
		$this->_1_Insercion->setDbValue($rs->fields('1_Insercion'));
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->setDbValue($rs->fields('1_Llegada_GME_a_su_lugar_de_Origen_fin_fase'));
		$this->_1_Novedad_apoyo_zonal->setDbValue($rs->fields('1_Novedad_apoyo_zonal'));
		$this->_1_Novedad_enfermero->setDbValue($rs->fields('1_Novedad_enfermero'));
		$this->_1_Punto_fuera_del_area_de_erradicacion->setDbValue($rs->fields('1_Punto_fuera_del_area_de_erradicacion'));
		$this->_1_Transporte_bus->setDbValue($rs->fields('1_Transporte_bus'));
		$this->_1_Traslado_apoyo_zonal->setDbValue($rs->fields('1_Traslado_apoyo_zonal'));
		$this->_1_Traslado_area_vivac->setDbValue($rs->fields('1_Traslado_area_vivac'));
		$this->Adm_Fuerza->setDbValue($rs->fields('Adm_Fuerza'));
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->setDbValue($rs->fields('2_A_la_espera_definicion_nuevo_punto_FP'));
		$this->_2_Espera_helicoptero_FP_de_seguridad->setDbValue($rs->fields('2_Espera_helicoptero_FP_de_seguridad'));
		$this->_2_Espera_helicoptero_FP_que_abastece->setDbValue($rs->fields('2_Espera_helicoptero_FP_que_abastece'));
		$this->_2_Induccion_FP->setDbValue($rs->fields('2_Induccion_FP'));
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->setDbValue($rs->fields('2_Novedad_canino_o_del_grupo_de_deteccion'));
		$this->_2_Problemas_fuerza_publica->setDbValue($rs->fields('2_Problemas_fuerza_publica'));
		$this->_2_Sin_seguridad->setDbValue($rs->fields('2_Sin_seguridad'));
		$this->Sit_Seguridad->setDbValue($rs->fields('Sit_Seguridad'));
		$this->_3_AEI_controlado->setDbValue($rs->fields('3_AEI_controlado'));
		$this->_3_AEI_no_controlado->setDbValue($rs->fields('3_AEI_no_controlado'));
		$this->_3_Bloqueo_parcial_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_parcial_de_la_comunidad'));
		$this->_3_Bloqueo_total_de_la_comunidad->setDbValue($rs->fields('3_Bloqueo_total_de_la_comunidad'));
		$this->_3_Combate->setDbValue($rs->fields('3_Combate'));
		$this->_3_Hostigamiento->setDbValue($rs->fields('3_Hostigamiento'));
		$this->_3_MAP_Controlada->setDbValue($rs->fields('3_MAP_Controlada'));
		$this->_3_MAP_No_controlada->setDbValue($rs->fields('3_MAP_No_controlada'));
		$this->_3_MUSE->setDbValue($rs->fields('3_MUSE'));
		$this->_3_Operaciones_de_seguridad->setDbValue($rs->fields('3_Operaciones_de_seguridad'));
		$this->LATITUD_segurid->setDbValue($rs->fields('LATITUD_segurid'));
		$this->GRA_LAT_segurid->setDbValue($rs->fields('GRA_LAT_segurid'));
		$this->MIN_LAT_segurid->setDbValue($rs->fields('MIN_LAT_segurid'));
		$this->SEG_LAT_segurid->setDbValue($rs->fields('SEG_LAT_segurid'));
		$this->GRA_LONG_seguri->setDbValue($rs->fields('GRA_LONG_seguri'));
		$this->MIN_LONG_seguri->setDbValue($rs->fields('MIN_LONG_seguri'));
		$this->SEG_LONG_seguri->setDbValue($rs->fields('SEG_LONG_seguri'));
		$this->Novedad->setDbValue($rs->fields('Novedad'));
		$this->_4_Epidemia->setDbValue($rs->fields('4_Epidemia'));
		$this->_4_Novedad_climatologica->setDbValue($rs->fields('4_Novedad_climatologica'));
		$this->_4_Registro_de_cultivos->setDbValue($rs->fields('4_Registro_de_cultivos'));
		$this->_4_Zona_con_cultivos_muy_dispersos->setDbValue($rs->fields('4_Zona_con_cultivos_muy_dispersos'));
		$this->_4_Zona_de_cruce_de_rios_caudalosos->setDbValue($rs->fields('4_Zona_de_cruce_de_rios_caudalosos'));
		$this->_4_Zona_sin_cultivos->setDbValue($rs->fields('4_Zona_sin_cultivos'));
		$this->Num_Erra_Salen->setDbValue($rs->fields('Num_Erra_Salen'));
		$this->Num_Erra_Quedan->setDbValue($rs->fields('Num_Erra_Quedan'));
		$this->No_ENFERMERO->setDbValue($rs->fields('No_ENFERMERO'));
		$this->NUM_FP->setDbValue($rs->fields('NUM_FP'));
		$this->NUM_Perso_EVA->setDbValue($rs->fields('NUM_Perso_EVA'));
		$this->NUM_Poli->setDbValue($rs->fields('NUM_Poli'));
		$this->AD1O->setDbValue($rs->fields('AÑO'));
		$this->FASE->setDbValue($rs->fields('FASE'));
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
		$this->NOM_PGE->DbValue = $row['NOM_PGE'];
		$this->Otro_NOM_PGE->DbValue = $row['Otro_NOM_PGE'];
		$this->Otro_CC_PGE->DbValue = $row['Otro_CC_PGE'];
		$this->TIPO_INFORME->DbValue = $row['TIPO_INFORME'];
		$this->FECHA_REPORT->DbValue = $row['FECHA_REPORT'];
		$this->DIA->DbValue = $row['DIA'];
		$this->MES->DbValue = $row['MES'];
		$this->Departamento->DbValue = $row['Departamento'];
		$this->Muncipio->DbValue = $row['Muncipio'];
		$this->TEMA->DbValue = $row['TEMA'];
		$this->Otro_Tema->DbValue = $row['Otro_Tema'];
		$this->OBSERVACION->DbValue = $row['OBSERVACION'];
		$this->FUERZA->DbValue = $row['FUERZA'];
		$this->NOM_VDA->DbValue = $row['NOM_VDA'];
		$this->Ha_Coca->DbValue = $row['Ha_Coca'];
		$this->Ha_Amapola->DbValue = $row['Ha_Amapola'];
		$this->Ha_Marihuana->DbValue = $row['Ha_Marihuana'];
		$this->T_erradi->DbValue = $row['T_erradi'];
		$this->LATITUD_sector->DbValue = $row['LATITUD_sector'];
		$this->GRA_LAT_Sector->DbValue = $row['GRA_LAT_Sector'];
		$this->MIN_LAT_Sector->DbValue = $row['MIN_LAT_Sector'];
		$this->SEG_LAT_Sector->DbValue = $row['SEG_LAT_Sector'];
		$this->GRA_LONG_Sector->DbValue = $row['GRA_LONG_Sector'];
		$this->MIN_LONG_Sector->DbValue = $row['MIN_LONG_Sector'];
		$this->SEG_LONG_Sector->DbValue = $row['SEG_LONG_Sector'];
		$this->Ini_Jorna->DbValue = $row['Ini_Jorna'];
		$this->Fin_Jorna->DbValue = $row['Fin_Jorna'];
		$this->Situ_Especial->DbValue = $row['Situ_Especial'];
		$this->Adm_GME->DbValue = $row['Adm_GME'];
		$this->_1_Abastecimiento->DbValue = $row['1_Abastecimiento'];
		$this->_1_Acompanamiento_firma_GME->DbValue = $row['1_Acompanamiento_firma_GME'];
		$this->_1_Apoyo_zonal_sin_punto_asignado->DbValue = $row['1_Apoyo_zonal_sin_punto_asignado'];
		$this->_1_Descanso_en_dia_habil->DbValue = $row['1_Descanso_en_dia_habil'];
		$this->_1_Descanso_festivo_dominical->DbValue = $row['1_Descanso_festivo_dominical'];
		$this->_1_Dia_compensatorio->DbValue = $row['1_Dia_compensatorio'];
		$this->_1_Erradicacion_en_dia_festivo->DbValue = $row['1_Erradicacion_en_dia_festivo'];
		$this->_1_Espera_helicoptero_Helistar->DbValue = $row['1_Espera_helicoptero_Helistar'];
		$this->_1_Extraccion->DbValue = $row['1_Extraccion'];
		$this->_1_Firma_contrato_GME->DbValue = $row['1_Firma_contrato_GME'];
		$this->_1_Induccion_Apoyo_Zonal->DbValue = $row['1_Induccion_Apoyo_Zonal'];
		$this->_1_Insercion->DbValue = $row['1_Insercion'];
		$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->DbValue = $row['1_Llegada_GME_a_su_lugar_de_Origen_fin_fase'];
		$this->_1_Novedad_apoyo_zonal->DbValue = $row['1_Novedad_apoyo_zonal'];
		$this->_1_Novedad_enfermero->DbValue = $row['1_Novedad_enfermero'];
		$this->_1_Punto_fuera_del_area_de_erradicacion->DbValue = $row['1_Punto_fuera_del_area_de_erradicacion'];
		$this->_1_Transporte_bus->DbValue = $row['1_Transporte_bus'];
		$this->_1_Traslado_apoyo_zonal->DbValue = $row['1_Traslado_apoyo_zonal'];
		$this->_1_Traslado_area_vivac->DbValue = $row['1_Traslado_area_vivac'];
		$this->Adm_Fuerza->DbValue = $row['Adm_Fuerza'];
		$this->_2_A_la_espera_definicion_nuevo_punto_FP->DbValue = $row['2_A_la_espera_definicion_nuevo_punto_FP'];
		$this->_2_Espera_helicoptero_FP_de_seguridad->DbValue = $row['2_Espera_helicoptero_FP_de_seguridad'];
		$this->_2_Espera_helicoptero_FP_que_abastece->DbValue = $row['2_Espera_helicoptero_FP_que_abastece'];
		$this->_2_Induccion_FP->DbValue = $row['2_Induccion_FP'];
		$this->_2_Novedad_canino_o_del_grupo_de_deteccion->DbValue = $row['2_Novedad_canino_o_del_grupo_de_deteccion'];
		$this->_2_Problemas_fuerza_publica->DbValue = $row['2_Problemas_fuerza_publica'];
		$this->_2_Sin_seguridad->DbValue = $row['2_Sin_seguridad'];
		$this->Sit_Seguridad->DbValue = $row['Sit_Seguridad'];
		$this->_3_AEI_controlado->DbValue = $row['3_AEI_controlado'];
		$this->_3_AEI_no_controlado->DbValue = $row['3_AEI_no_controlado'];
		$this->_3_Bloqueo_parcial_de_la_comunidad->DbValue = $row['3_Bloqueo_parcial_de_la_comunidad'];
		$this->_3_Bloqueo_total_de_la_comunidad->DbValue = $row['3_Bloqueo_total_de_la_comunidad'];
		$this->_3_Combate->DbValue = $row['3_Combate'];
		$this->_3_Hostigamiento->DbValue = $row['3_Hostigamiento'];
		$this->_3_MAP_Controlada->DbValue = $row['3_MAP_Controlada'];
		$this->_3_MAP_No_controlada->DbValue = $row['3_MAP_No_controlada'];
		$this->_3_MUSE->DbValue = $row['3_MUSE'];
		$this->_3_Operaciones_de_seguridad->DbValue = $row['3_Operaciones_de_seguridad'];
		$this->LATITUD_segurid->DbValue = $row['LATITUD_segurid'];
		$this->GRA_LAT_segurid->DbValue = $row['GRA_LAT_segurid'];
		$this->MIN_LAT_segurid->DbValue = $row['MIN_LAT_segurid'];
		$this->SEG_LAT_segurid->DbValue = $row['SEG_LAT_segurid'];
		$this->GRA_LONG_seguri->DbValue = $row['GRA_LONG_seguri'];
		$this->MIN_LONG_seguri->DbValue = $row['MIN_LONG_seguri'];
		$this->SEG_LONG_seguri->DbValue = $row['SEG_LONG_seguri'];
		$this->Novedad->DbValue = $row['Novedad'];
		$this->_4_Epidemia->DbValue = $row['4_Epidemia'];
		$this->_4_Novedad_climatologica->DbValue = $row['4_Novedad_climatologica'];
		$this->_4_Registro_de_cultivos->DbValue = $row['4_Registro_de_cultivos'];
		$this->_4_Zona_con_cultivos_muy_dispersos->DbValue = $row['4_Zona_con_cultivos_muy_dispersos'];
		$this->_4_Zona_de_cruce_de_rios_caudalosos->DbValue = $row['4_Zona_de_cruce_de_rios_caudalosos'];
		$this->_4_Zona_sin_cultivos->DbValue = $row['4_Zona_sin_cultivos'];
		$this->Num_Erra_Salen->DbValue = $row['Num_Erra_Salen'];
		$this->Num_Erra_Quedan->DbValue = $row['Num_Erra_Quedan'];
		$this->No_ENFERMERO->DbValue = $row['No_ENFERMERO'];
		$this->NUM_FP->DbValue = $row['NUM_FP'];
		$this->NUM_Perso_EVA->DbValue = $row['NUM_Perso_EVA'];
		$this->NUM_Poli->DbValue = $row['NUM_Poli'];
		$this->AD1O->DbValue = $row['AÑO'];
		$this->FASE->DbValue = $row['FASE'];
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
		if ($this->Ha_Coca->FormValue == $this->Ha_Coca->CurrentValue && is_numeric(ew_StrToFloat($this->Ha_Coca->CurrentValue)))
			$this->Ha_Coca->CurrentValue = ew_StrToFloat($this->Ha_Coca->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Ha_Amapola->FormValue == $this->Ha_Amapola->CurrentValue && is_numeric(ew_StrToFloat($this->Ha_Amapola->CurrentValue)))
			$this->Ha_Amapola->CurrentValue = ew_StrToFloat($this->Ha_Amapola->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Ha_Marihuana->FormValue == $this->Ha_Marihuana->CurrentValue && is_numeric(ew_StrToFloat($this->Ha_Marihuana->CurrentValue)))
			$this->Ha_Marihuana->CurrentValue = ew_StrToFloat($this->Ha_Marihuana->CurrentValue);

		// Convert decimal values if posted back
		if ($this->T_erradi->FormValue == $this->T_erradi->CurrentValue && is_numeric(ew_StrToFloat($this->T_erradi->CurrentValue)))
			$this->T_erradi->CurrentValue = ew_StrToFloat($this->T_erradi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LAT_Sector->FormValue == $this->SEG_LAT_Sector->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LAT_Sector->CurrentValue)))
			$this->SEG_LAT_Sector->CurrentValue = ew_StrToFloat($this->SEG_LAT_Sector->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LONG_Sector->FormValue == $this->SEG_LONG_Sector->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LONG_Sector->CurrentValue)))
			$this->SEG_LONG_Sector->CurrentValue = ew_StrToFloat($this->SEG_LONG_Sector->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Adm_GME->FormValue == $this->Adm_GME->CurrentValue && is_numeric(ew_StrToFloat($this->Adm_GME->CurrentValue)))
			$this->Adm_GME->CurrentValue = ew_StrToFloat($this->Adm_GME->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Abastecimiento->FormValue == $this->_1_Abastecimiento->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Abastecimiento->CurrentValue)))
			$this->_1_Abastecimiento->CurrentValue = ew_StrToFloat($this->_1_Abastecimiento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Acompanamiento_firma_GME->FormValue == $this->_1_Acompanamiento_firma_GME->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Acompanamiento_firma_GME->CurrentValue)))
			$this->_1_Acompanamiento_firma_GME->CurrentValue = ew_StrToFloat($this->_1_Acompanamiento_firma_GME->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Apoyo_zonal_sin_punto_asignado->FormValue == $this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue)))
			$this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue = ew_StrToFloat($this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Descanso_en_dia_habil->FormValue == $this->_1_Descanso_en_dia_habil->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Descanso_en_dia_habil->CurrentValue)))
			$this->_1_Descanso_en_dia_habil->CurrentValue = ew_StrToFloat($this->_1_Descanso_en_dia_habil->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Descanso_festivo_dominical->FormValue == $this->_1_Descanso_festivo_dominical->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Descanso_festivo_dominical->CurrentValue)))
			$this->_1_Descanso_festivo_dominical->CurrentValue = ew_StrToFloat($this->_1_Descanso_festivo_dominical->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Dia_compensatorio->FormValue == $this->_1_Dia_compensatorio->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Dia_compensatorio->CurrentValue)))
			$this->_1_Dia_compensatorio->CurrentValue = ew_StrToFloat($this->_1_Dia_compensatorio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Erradicacion_en_dia_festivo->FormValue == $this->_1_Erradicacion_en_dia_festivo->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Erradicacion_en_dia_festivo->CurrentValue)))
			$this->_1_Erradicacion_en_dia_festivo->CurrentValue = ew_StrToFloat($this->_1_Erradicacion_en_dia_festivo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Espera_helicoptero_Helistar->FormValue == $this->_1_Espera_helicoptero_Helistar->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Espera_helicoptero_Helistar->CurrentValue)))
			$this->_1_Espera_helicoptero_Helistar->CurrentValue = ew_StrToFloat($this->_1_Espera_helicoptero_Helistar->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Extraccion->FormValue == $this->_1_Extraccion->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Extraccion->CurrentValue)))
			$this->_1_Extraccion->CurrentValue = ew_StrToFloat($this->_1_Extraccion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Firma_contrato_GME->FormValue == $this->_1_Firma_contrato_GME->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Firma_contrato_GME->CurrentValue)))
			$this->_1_Firma_contrato_GME->CurrentValue = ew_StrToFloat($this->_1_Firma_contrato_GME->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Induccion_Apoyo_Zonal->FormValue == $this->_1_Induccion_Apoyo_Zonal->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Induccion_Apoyo_Zonal->CurrentValue)))
			$this->_1_Induccion_Apoyo_Zonal->CurrentValue = ew_StrToFloat($this->_1_Induccion_Apoyo_Zonal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Insercion->FormValue == $this->_1_Insercion->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Insercion->CurrentValue)))
			$this->_1_Insercion->CurrentValue = ew_StrToFloat($this->_1_Insercion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FormValue == $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue)))
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue = ew_StrToFloat($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Novedad_apoyo_zonal->FormValue == $this->_1_Novedad_apoyo_zonal->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Novedad_apoyo_zonal->CurrentValue)))
			$this->_1_Novedad_apoyo_zonal->CurrentValue = ew_StrToFloat($this->_1_Novedad_apoyo_zonal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Novedad_enfermero->FormValue == $this->_1_Novedad_enfermero->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Novedad_enfermero->CurrentValue)))
			$this->_1_Novedad_enfermero->CurrentValue = ew_StrToFloat($this->_1_Novedad_enfermero->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Punto_fuera_del_area_de_erradicacion->FormValue == $this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue)))
			$this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue = ew_StrToFloat($this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Transporte_bus->FormValue == $this->_1_Transporte_bus->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Transporte_bus->CurrentValue)))
			$this->_1_Transporte_bus->CurrentValue = ew_StrToFloat($this->_1_Transporte_bus->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Traslado_apoyo_zonal->FormValue == $this->_1_Traslado_apoyo_zonal->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Traslado_apoyo_zonal->CurrentValue)))
			$this->_1_Traslado_apoyo_zonal->CurrentValue = ew_StrToFloat($this->_1_Traslado_apoyo_zonal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_1_Traslado_area_vivac->FormValue == $this->_1_Traslado_area_vivac->CurrentValue && is_numeric(ew_StrToFloat($this->_1_Traslado_area_vivac->CurrentValue)))
			$this->_1_Traslado_area_vivac->CurrentValue = ew_StrToFloat($this->_1_Traslado_area_vivac->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Adm_Fuerza->FormValue == $this->Adm_Fuerza->CurrentValue && is_numeric(ew_StrToFloat($this->Adm_Fuerza->CurrentValue)))
			$this->Adm_Fuerza->CurrentValue = ew_StrToFloat($this->Adm_Fuerza->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_A_la_espera_definicion_nuevo_punto_FP->FormValue == $this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue && is_numeric(ew_StrToFloat($this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue)))
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue = ew_StrToFloat($this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Espera_helicoptero_FP_de_seguridad->FormValue == $this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue)))
			$this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue = ew_StrToFloat($this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Espera_helicoptero_FP_que_abastece->FormValue == $this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue)))
			$this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue = ew_StrToFloat($this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Induccion_FP->FormValue == $this->_2_Induccion_FP->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Induccion_FP->CurrentValue)))
			$this->_2_Induccion_FP->CurrentValue = ew_StrToFloat($this->_2_Induccion_FP->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Novedad_canino_o_del_grupo_de_deteccion->FormValue == $this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue)))
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue = ew_StrToFloat($this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Problemas_fuerza_publica->FormValue == $this->_2_Problemas_fuerza_publica->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Problemas_fuerza_publica->CurrentValue)))
			$this->_2_Problemas_fuerza_publica->CurrentValue = ew_StrToFloat($this->_2_Problemas_fuerza_publica->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_2_Sin_seguridad->FormValue == $this->_2_Sin_seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->_2_Sin_seguridad->CurrentValue)))
			$this->_2_Sin_seguridad->CurrentValue = ew_StrToFloat($this->_2_Sin_seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Sit_Seguridad->FormValue == $this->Sit_Seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->Sit_Seguridad->CurrentValue)))
			$this->Sit_Seguridad->CurrentValue = ew_StrToFloat($this->Sit_Seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_AEI_controlado->FormValue == $this->_3_AEI_controlado->CurrentValue && is_numeric(ew_StrToFloat($this->_3_AEI_controlado->CurrentValue)))
			$this->_3_AEI_controlado->CurrentValue = ew_StrToFloat($this->_3_AEI_controlado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_AEI_no_controlado->FormValue == $this->_3_AEI_no_controlado->CurrentValue && is_numeric(ew_StrToFloat($this->_3_AEI_no_controlado->CurrentValue)))
			$this->_3_AEI_no_controlado->CurrentValue = ew_StrToFloat($this->_3_AEI_no_controlado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Bloqueo_parcial_de_la_comunidad->FormValue == $this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue)))
			$this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue = ew_StrToFloat($this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Bloqueo_total_de_la_comunidad->FormValue == $this->_3_Bloqueo_total_de_la_comunidad->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Bloqueo_total_de_la_comunidad->CurrentValue)))
			$this->_3_Bloqueo_total_de_la_comunidad->CurrentValue = ew_StrToFloat($this->_3_Bloqueo_total_de_la_comunidad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Combate->FormValue == $this->_3_Combate->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Combate->CurrentValue)))
			$this->_3_Combate->CurrentValue = ew_StrToFloat($this->_3_Combate->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Hostigamiento->FormValue == $this->_3_Hostigamiento->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Hostigamiento->CurrentValue)))
			$this->_3_Hostigamiento->CurrentValue = ew_StrToFloat($this->_3_Hostigamiento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_MAP_Controlada->FormValue == $this->_3_MAP_Controlada->CurrentValue && is_numeric(ew_StrToFloat($this->_3_MAP_Controlada->CurrentValue)))
			$this->_3_MAP_Controlada->CurrentValue = ew_StrToFloat($this->_3_MAP_Controlada->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_MAP_No_controlada->FormValue == $this->_3_MAP_No_controlada->CurrentValue && is_numeric(ew_StrToFloat($this->_3_MAP_No_controlada->CurrentValue)))
			$this->_3_MAP_No_controlada->CurrentValue = ew_StrToFloat($this->_3_MAP_No_controlada->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_MUSE->FormValue == $this->_3_MUSE->CurrentValue && is_numeric(ew_StrToFloat($this->_3_MUSE->CurrentValue)))
			$this->_3_MUSE->CurrentValue = ew_StrToFloat($this->_3_MUSE->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_3_Operaciones_de_seguridad->FormValue == $this->_3_Operaciones_de_seguridad->CurrentValue && is_numeric(ew_StrToFloat($this->_3_Operaciones_de_seguridad->CurrentValue)))
			$this->_3_Operaciones_de_seguridad->CurrentValue = ew_StrToFloat($this->_3_Operaciones_de_seguridad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LAT_segurid->FormValue == $this->SEG_LAT_segurid->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LAT_segurid->CurrentValue)))
			$this->SEG_LAT_segurid->CurrentValue = ew_StrToFloat($this->SEG_LAT_segurid->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LONG_seguri->FormValue == $this->SEG_LONG_seguri->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LONG_seguri->CurrentValue)))
			$this->SEG_LONG_seguri->CurrentValue = ew_StrToFloat($this->SEG_LONG_seguri->CurrentValue);

		// Convert decimal values if posted back
		if ($this->Novedad->FormValue == $this->Novedad->CurrentValue && is_numeric(ew_StrToFloat($this->Novedad->CurrentValue)))
			$this->Novedad->CurrentValue = ew_StrToFloat($this->Novedad->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Epidemia->FormValue == $this->_4_Epidemia->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Epidemia->CurrentValue)))
			$this->_4_Epidemia->CurrentValue = ew_StrToFloat($this->_4_Epidemia->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Novedad_climatologica->FormValue == $this->_4_Novedad_climatologica->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Novedad_climatologica->CurrentValue)))
			$this->_4_Novedad_climatologica->CurrentValue = ew_StrToFloat($this->_4_Novedad_climatologica->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Registro_de_cultivos->FormValue == $this->_4_Registro_de_cultivos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Registro_de_cultivos->CurrentValue)))
			$this->_4_Registro_de_cultivos->CurrentValue = ew_StrToFloat($this->_4_Registro_de_cultivos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Zona_con_cultivos_muy_dispersos->FormValue == $this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue)))
			$this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue = ew_StrToFloat($this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Zona_de_cruce_de_rios_caudalosos->FormValue == $this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue)))
			$this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue = ew_StrToFloat($this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->_4_Zona_sin_cultivos->FormValue == $this->_4_Zona_sin_cultivos->CurrentValue && is_numeric(ew_StrToFloat($this->_4_Zona_sin_cultivos->CurrentValue)))
			$this->_4_Zona_sin_cultivos->CurrentValue = ew_StrToFloat($this->_4_Zona_sin_cultivos->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// llave
		// F_Sincron
		// USUARIO
		// Cargo_gme
		// NOM_PE
		// Otro_PE
		// NOM_PGE
		// Otro_NOM_PGE
		// Otro_CC_PGE
		// TIPO_INFORME
		// FECHA_REPORT
		// DIA
		// MES
		// Departamento
		// Muncipio
		// TEMA
		// Otro_Tema
		// OBSERVACION
		// FUERZA
		// NOM_VDA
		// Ha_Coca
		// Ha_Amapola
		// Ha_Marihuana
		// T_erradi
		// LATITUD_sector
		// GRA_LAT_Sector
		// MIN_LAT_Sector
		// SEG_LAT_Sector
		// GRA_LONG_Sector
		// MIN_LONG_Sector
		// SEG_LONG_Sector
		// Ini_Jorna
		// Fin_Jorna
		// Situ_Especial
		// Adm_GME
		// 1_Abastecimiento
		// 1_Acompanamiento_firma_GME
		// 1_Apoyo_zonal_sin_punto_asignado
		// 1_Descanso_en_dia_habil
		// 1_Descanso_festivo_dominical
		// 1_Dia_compensatorio
		// 1_Erradicacion_en_dia_festivo
		// 1_Espera_helicoptero_Helistar
		// 1_Extraccion
		// 1_Firma_contrato_GME
		// 1_Induccion_Apoyo_Zonal
		// 1_Insercion
		// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
		// 1_Novedad_apoyo_zonal
		// 1_Novedad_enfermero
		// 1_Punto_fuera_del_area_de_erradicacion
		// 1_Transporte_bus
		// 1_Traslado_apoyo_zonal
		// 1_Traslado_area_vivac
		// Adm_Fuerza
		// 2_A_la_espera_definicion_nuevo_punto_FP
		// 2_Espera_helicoptero_FP_de_seguridad
		// 2_Espera_helicoptero_FP_que_abastece
		// 2_Induccion_FP
		// 2_Novedad_canino_o_del_grupo_de_deteccion
		// 2_Problemas_fuerza_publica
		// 2_Sin_seguridad
		// Sit_Seguridad
		// 3_AEI_controlado
		// 3_AEI_no_controlado
		// 3_Bloqueo_parcial_de_la_comunidad
		// 3_Bloqueo_total_de_la_comunidad
		// 3_Combate
		// 3_Hostigamiento
		// 3_MAP_Controlada
		// 3_MAP_No_controlada
		// 3_MUSE
		// 3_Operaciones_de_seguridad
		// LATITUD_segurid
		// GRA_LAT_segurid
		// MIN_LAT_segurid
		// SEG_LAT_segurid
		// GRA_LONG_seguri
		// MIN_LONG_seguri
		// SEG_LONG_seguri
		// Novedad
		// 4_Epidemia
		// 4_Novedad_climatologica
		// 4_Registro_de_cultivos
		// 4_Zona_con_cultivos_muy_dispersos
		// 4_Zona_de_cruce_de_rios_caudalosos
		// 4_Zona_sin_cultivos
		// Num_Erra_Salen
		// Num_Erra_Quedan
		// No_ENFERMERO
		// NUM_FP
		// NUM_Perso_EVA
		// NUM_Poli
		// AÑO
		// FASE
		// Modificado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// llave
			$this->llave->ViewValue = $this->llave->CurrentValue;
			$this->llave->ViewCustomAttributes = "";

			// F_Sincron
			$this->F_Sincron->ViewValue = $this->F_Sincron->CurrentValue;
			$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 7);
			$this->F_Sincron->ViewCustomAttributes = "";

			// USUARIO
			if (strval($this->USUARIO->CurrentValue) <> "") {
				$sFilterWrk = "`USUARIO`" . ew_SearchString("=", $this->USUARIO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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

			// NOM_PGE
			if (strval($this->NOM_PGE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PGE`" . ew_SearchString("=", $this->NOM_PGE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->ViewValue = $this->Otro_NOM_PGE->CurrentValue;
			$this->Otro_NOM_PGE->ViewCustomAttributes = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
			$this->Otro_CC_PGE->ViewCustomAttributes = "";

			// TIPO_INFORME
			$this->TIPO_INFORME->ViewValue = $this->TIPO_INFORME->CurrentValue;
			$this->TIPO_INFORME->ViewCustomAttributes = "";

			// FECHA_REPORT
			$this->FECHA_REPORT->ViewValue = $this->FECHA_REPORT->CurrentValue;
			$this->FECHA_REPORT->ViewCustomAttributes = "";

			// DIA
			$this->DIA->ViewValue = $this->DIA->CurrentValue;
			$this->DIA->ViewCustomAttributes = "";

			// MES
			$this->MES->ViewValue = $this->MES->CurrentValue;
			$this->MES->ViewCustomAttributes = "";

			// Departamento
			$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
			$this->Departamento->ViewCustomAttributes = "";

			// Muncipio
			$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
			$this->Muncipio->ViewCustomAttributes = "";

			// TEMA
			$this->TEMA->ViewValue = $this->TEMA->CurrentValue;
			$this->TEMA->ViewCustomAttributes = "";

			// Otro_Tema
			$this->Otro_Tema->ViewValue = $this->Otro_Tema->CurrentValue;
			$this->Otro_Tema->ViewCustomAttributes = "";

			// OBSERVACION
			$this->OBSERVACION->ViewValue = $this->OBSERVACION->CurrentValue;
			$this->OBSERVACION->ViewCustomAttributes = "";

			// NOM_VDA
			$this->NOM_VDA->ViewValue = $this->NOM_VDA->CurrentValue;
			$this->NOM_VDA->ViewCustomAttributes = "";

			// Ha_Coca
			$this->Ha_Coca->ViewValue = $this->Ha_Coca->CurrentValue;
			$this->Ha_Coca->ViewCustomAttributes = "";

			// Ha_Amapola
			$this->Ha_Amapola->ViewValue = $this->Ha_Amapola->CurrentValue;
			$this->Ha_Amapola->ViewCustomAttributes = "";

			// Ha_Marihuana
			$this->Ha_Marihuana->ViewValue = $this->Ha_Marihuana->CurrentValue;
			$this->Ha_Marihuana->ViewCustomAttributes = "";

			// T_erradi
			$this->T_erradi->ViewValue = $this->T_erradi->CurrentValue;
			$this->T_erradi->ViewCustomAttributes = "";

			// LATITUD_sector
			$this->LATITUD_sector->ViewValue = $this->LATITUD_sector->CurrentValue;
			$this->LATITUD_sector->ViewCustomAttributes = "";

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->ViewValue = $this->GRA_LAT_Sector->CurrentValue;
			$this->GRA_LAT_Sector->ViewCustomAttributes = "";

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->ViewValue = $this->MIN_LAT_Sector->CurrentValue;
			$this->MIN_LAT_Sector->ViewCustomAttributes = "";

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->ViewValue = $this->SEG_LAT_Sector->CurrentValue;
			$this->SEG_LAT_Sector->ViewCustomAttributes = "";

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->ViewValue = $this->GRA_LONG_Sector->CurrentValue;
			$this->GRA_LONG_Sector->ViewCustomAttributes = "";

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->ViewValue = $this->MIN_LONG_Sector->CurrentValue;
			$this->MIN_LONG_Sector->ViewCustomAttributes = "";

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->ViewValue = $this->SEG_LONG_Sector->CurrentValue;
			$this->SEG_LONG_Sector->ViewCustomAttributes = "";

			// Ini_Jorna
			$this->Ini_Jorna->ViewValue = $this->Ini_Jorna->CurrentValue;
			$this->Ini_Jorna->ViewCustomAttributes = "";

			// Fin_Jorna
			$this->Fin_Jorna->ViewValue = $this->Fin_Jorna->CurrentValue;
			$this->Fin_Jorna->ViewCustomAttributes = "";

			
			// Situ_Especial
			if (strval($this->Situ_Especial->CurrentValue) <> "") {
				switch ($this->Situ_Especial->CurrentValue) {
					case $this->Situ_Especial->FldTagValue(1):
						$this->Situ_Especial->ViewValue = $this->Situ_Especial->FldTagCaption(1) <> "" ? $this->Situ_Especial->FldTagCaption(1) : $this->Situ_Especial->CurrentValue;
						break;
					case $this->Situ_Especial->FldTagValue(2):
						$this->Situ_Especial->ViewValue = $this->Situ_Especial->FldTagCaption(2) <> "" ? $this->Situ_Especial->FldTagCaption(2) : $this->Situ_Especial->CurrentValue;
						break;
					default:
						$this->Situ_Especial->ViewValue = $this->Situ_Especial->CurrentValue;
				}
			} else {
				$this->Situ_Especial->ViewValue = NULL;
			}
			$this->Situ_Especial->ViewCustomAttributes = "";

			// Adm_GME
			$this->Adm_GME->ViewValue = $this->Adm_GME->CurrentValue;
			$this->Adm_GME->ViewCustomAttributes = "";

			// 1_Abastecimiento
			$this->_1_Abastecimiento->ViewValue = $this->_1_Abastecimiento->CurrentValue;
			$this->_1_Abastecimiento->ViewCustomAttributes = "";

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->ViewValue = $this->_1_Acompanamiento_firma_GME->CurrentValue;
			$this->_1_Acompanamiento_firma_GME->ViewCustomAttributes = "";

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->ViewValue = $this->_1_Apoyo_zonal_sin_punto_asignado->CurrentValue;
			$this->_1_Apoyo_zonal_sin_punto_asignado->ViewCustomAttributes = "";

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->ViewValue = $this->_1_Descanso_en_dia_habil->CurrentValue;
			$this->_1_Descanso_en_dia_habil->ViewCustomAttributes = "";

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->ViewValue = $this->_1_Descanso_festivo_dominical->CurrentValue;
			$this->_1_Descanso_festivo_dominical->ViewCustomAttributes = "";

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->ViewValue = $this->_1_Dia_compensatorio->CurrentValue;
			$this->_1_Dia_compensatorio->ViewCustomAttributes = "";

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->ViewValue = $this->_1_Erradicacion_en_dia_festivo->CurrentValue;
			$this->_1_Erradicacion_en_dia_festivo->ViewCustomAttributes = "";

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->ViewValue = $this->_1_Espera_helicoptero_Helistar->CurrentValue;
			$this->_1_Espera_helicoptero_Helistar->ViewCustomAttributes = "";

			// 1_Extraccion
			$this->_1_Extraccion->ViewValue = $this->_1_Extraccion->CurrentValue;
			$this->_1_Extraccion->ViewCustomAttributes = "";

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->ViewValue = $this->_1_Firma_contrato_GME->CurrentValue;
			$this->_1_Firma_contrato_GME->ViewCustomAttributes = "";

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->ViewValue = $this->_1_Induccion_Apoyo_Zonal->CurrentValue;
			$this->_1_Induccion_Apoyo_Zonal->ViewCustomAttributes = "";

			// 1_Insercion
			$this->_1_Insercion->ViewValue = $this->_1_Insercion->CurrentValue;
			$this->_1_Insercion->ViewCustomAttributes = "";

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewValue = $this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CurrentValue;
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewCustomAttributes = "";

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->ViewValue = $this->_1_Novedad_apoyo_zonal->CurrentValue;
			$this->_1_Novedad_apoyo_zonal->ViewCustomAttributes = "";

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->ViewValue = $this->_1_Novedad_enfermero->CurrentValue;
			$this->_1_Novedad_enfermero->ViewCustomAttributes = "";

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->ViewValue = $this->_1_Punto_fuera_del_area_de_erradicacion->CurrentValue;
			$this->_1_Punto_fuera_del_area_de_erradicacion->ViewCustomAttributes = "";

			// 1_Transporte_bus
			$this->_1_Transporte_bus->ViewValue = $this->_1_Transporte_bus->CurrentValue;
			$this->_1_Transporte_bus->ViewCustomAttributes = "";

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->ViewValue = $this->_1_Traslado_apoyo_zonal->CurrentValue;
			$this->_1_Traslado_apoyo_zonal->ViewCustomAttributes = "";

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->ViewValue = $this->_1_Traslado_area_vivac->CurrentValue;
			$this->_1_Traslado_area_vivac->ViewCustomAttributes = "";

			// Adm_Fuerza
			$this->Adm_Fuerza->ViewValue = $this->Adm_Fuerza->CurrentValue;
			$this->Adm_Fuerza->ViewCustomAttributes = "";

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->ViewValue = $this->_2_A_la_espera_definicion_nuevo_punto_FP->CurrentValue;
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->ViewCustomAttributes = "";

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->ViewValue = $this->_2_Espera_helicoptero_FP_de_seguridad->CurrentValue;
			$this->_2_Espera_helicoptero_FP_de_seguridad->ViewCustomAttributes = "";

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->ViewValue = $this->_2_Espera_helicoptero_FP_que_abastece->CurrentValue;
			$this->_2_Espera_helicoptero_FP_que_abastece->ViewCustomAttributes = "";

			// 2_Induccion_FP
			$this->_2_Induccion_FP->ViewValue = $this->_2_Induccion_FP->CurrentValue;
			$this->_2_Induccion_FP->ViewCustomAttributes = "";

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewValue = $this->_2_Novedad_canino_o_del_grupo_de_deteccion->CurrentValue;
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewCustomAttributes = "";

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->ViewValue = $this->_2_Problemas_fuerza_publica->CurrentValue;
			$this->_2_Problemas_fuerza_publica->ViewCustomAttributes = "";

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->ViewValue = $this->_2_Sin_seguridad->CurrentValue;
			$this->_2_Sin_seguridad->ViewCustomAttributes = "";

			// Sit_Seguridad
			$this->Sit_Seguridad->ViewValue = $this->Sit_Seguridad->CurrentValue;
			$this->Sit_Seguridad->ViewCustomAttributes = "";

			// 3_AEI_controlado
			$this->_3_AEI_controlado->ViewValue = $this->_3_AEI_controlado->CurrentValue;
			$this->_3_AEI_controlado->ViewCustomAttributes = "";

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->ViewValue = $this->_3_AEI_no_controlado->CurrentValue;
			$this->_3_AEI_no_controlado->ViewCustomAttributes = "";

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->ViewValue = $this->_3_Bloqueo_parcial_de_la_comunidad->CurrentValue;
			$this->_3_Bloqueo_parcial_de_la_comunidad->ViewCustomAttributes = "";

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->ViewValue = $this->_3_Bloqueo_total_de_la_comunidad->CurrentValue;
			$this->_3_Bloqueo_total_de_la_comunidad->ViewCustomAttributes = "";

			// 3_Combate
			$this->_3_Combate->ViewValue = $this->_3_Combate->CurrentValue;
			$this->_3_Combate->ViewCustomAttributes = "";

			// 3_Hostigamiento
			$this->_3_Hostigamiento->ViewValue = $this->_3_Hostigamiento->CurrentValue;
			$this->_3_Hostigamiento->ViewCustomAttributes = "";

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->ViewValue = $this->_3_MAP_Controlada->CurrentValue;
			$this->_3_MAP_Controlada->ViewCustomAttributes = "";

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->ViewValue = $this->_3_MAP_No_controlada->CurrentValue;
			$this->_3_MAP_No_controlada->ViewCustomAttributes = "";

			// 3_MUSE
			$this->_3_MUSE->ViewValue = $this->_3_MUSE->CurrentValue;
			$this->_3_MUSE->ViewCustomAttributes = "";

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->ViewValue = $this->_3_Operaciones_de_seguridad->CurrentValue;
			$this->_3_Operaciones_de_seguridad->ViewCustomAttributes = "";

			// LATITUD_segurid
			$this->LATITUD_segurid->ViewValue = $this->LATITUD_segurid->CurrentValue;
			$this->LATITUD_segurid->ViewCustomAttributes = "";

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->ViewValue = $this->GRA_LAT_segurid->CurrentValue;
			$this->GRA_LAT_segurid->ViewCustomAttributes = "";

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->ViewValue = $this->MIN_LAT_segurid->CurrentValue;
			$this->MIN_LAT_segurid->ViewCustomAttributes = "";

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->ViewValue = $this->SEG_LAT_segurid->CurrentValue;
			$this->SEG_LAT_segurid->ViewCustomAttributes = "";

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->ViewValue = $this->GRA_LONG_seguri->CurrentValue;
			$this->GRA_LONG_seguri->ViewCustomAttributes = "";

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->ViewValue = $this->MIN_LONG_seguri->CurrentValue;
			$this->MIN_LONG_seguri->ViewCustomAttributes = "";

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->ViewValue = $this->SEG_LONG_seguri->CurrentValue;
			$this->SEG_LONG_seguri->ViewCustomAttributes = "";

			// Novedad
			$this->Novedad->ViewValue = $this->Novedad->CurrentValue;
			$this->Novedad->ViewCustomAttributes = "";

			// 4_Epidemia
			$this->_4_Epidemia->ViewValue = $this->_4_Epidemia->CurrentValue;
			$this->_4_Epidemia->ViewCustomAttributes = "";

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->ViewValue = $this->_4_Novedad_climatologica->CurrentValue;
			$this->_4_Novedad_climatologica->ViewCustomAttributes = "";

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->ViewValue = $this->_4_Registro_de_cultivos->CurrentValue;
			$this->_4_Registro_de_cultivos->ViewCustomAttributes = "";

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->ViewValue = $this->_4_Zona_con_cultivos_muy_dispersos->CurrentValue;
			$this->_4_Zona_con_cultivos_muy_dispersos->ViewCustomAttributes = "";

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->ViewValue = $this->_4_Zona_de_cruce_de_rios_caudalosos->CurrentValue;
			$this->_4_Zona_de_cruce_de_rios_caudalosos->ViewCustomAttributes = "";

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->ViewValue = $this->_4_Zona_sin_cultivos->CurrentValue;
			$this->_4_Zona_sin_cultivos->ViewCustomAttributes = "";

			// Num_Erra_Salen
			$this->Num_Erra_Salen->ViewValue = $this->Num_Erra_Salen->CurrentValue;
			$this->Num_Erra_Salen->ViewCustomAttributes = "";

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->ViewValue = $this->Num_Erra_Quedan->CurrentValue;
			$this->Num_Erra_Quedan->ViewCustomAttributes = "";

			// No_ENFERMERO
			$this->No_ENFERMERO->ViewValue = $this->No_ENFERMERO->CurrentValue;
			$this->No_ENFERMERO->ViewCustomAttributes = "";

			// NUM_FP
			$this->NUM_FP->ViewValue = $this->NUM_FP->CurrentValue;
			$this->NUM_FP->ViewCustomAttributes = "";

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->ViewValue = $this->NUM_Perso_EVA->CurrentValue;
			$this->NUM_Perso_EVA->ViewCustomAttributes = "";

			// NUM_Poli
			$this->NUM_Poli->ViewValue = $this->NUM_Poli->CurrentValue;
			$this->NUM_Poli->ViewCustomAttributes = "";

			// AÑO
			if (strval($this->AD1O->CurrentValue) <> "") {
				$sFilterWrk = "`AÑO`" . ew_SearchString("=", $this->AD1O->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_id`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FASE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `FASE` ASC";
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

			// Modificado
			$this->Modificado->ViewValue = $this->Modificado->CurrentValue;
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

			// NOM_PGE
			$this->NOM_PGE->LinkCustomAttributes = "";
			$this->NOM_PGE->HrefValue = "";
			$this->NOM_PGE->TooltipValue = "";

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->LinkCustomAttributes = "";
			$this->Otro_NOM_PGE->HrefValue = "";
			$this->Otro_NOM_PGE->TooltipValue = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->LinkCustomAttributes = "";
			$this->Otro_CC_PGE->HrefValue = "";
			$this->Otro_CC_PGE->TooltipValue = "";

			// TIPO_INFORME
			$this->TIPO_INFORME->LinkCustomAttributes = "";
			$this->TIPO_INFORME->HrefValue = "";
			$this->TIPO_INFORME->TooltipValue = "";

			// FECHA_REPORT
			$this->FECHA_REPORT->LinkCustomAttributes = "";
			$this->FECHA_REPORT->HrefValue = "";
			$this->FECHA_REPORT->TooltipValue = "";

			// DIA
			$this->DIA->LinkCustomAttributes = "";
			$this->DIA->HrefValue = "";
			$this->DIA->TooltipValue = "";

			// MES
			$this->MES->LinkCustomAttributes = "";
			$this->MES->HrefValue = "";
			$this->MES->TooltipValue = "";

			// Departamento
			$this->Departamento->LinkCustomAttributes = "";
			$this->Departamento->HrefValue = "";
			$this->Departamento->TooltipValue = "";

			// Muncipio
			$this->Muncipio->LinkCustomAttributes = "";
			$this->Muncipio->HrefValue = "";
			$this->Muncipio->TooltipValue = "";

			// TEMA
			$this->TEMA->LinkCustomAttributes = "";
			$this->TEMA->HrefValue = "";
			$this->TEMA->TooltipValue = "";

			// Otro_Tema
			$this->Otro_Tema->LinkCustomAttributes = "";
			$this->Otro_Tema->HrefValue = "";
			$this->Otro_Tema->TooltipValue = "";

			// OBSERVACION
			$this->OBSERVACION->LinkCustomAttributes = "";
			$this->OBSERVACION->HrefValue = "";
			$this->OBSERVACION->TooltipValue = "";

			// NOM_VDA
			$this->NOM_VDA->LinkCustomAttributes = "";
			$this->NOM_VDA->HrefValue = "";
			$this->NOM_VDA->TooltipValue = "";

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

			// T_erradi
			$this->T_erradi->LinkCustomAttributes = "";
			$this->T_erradi->HrefValue = "";
			$this->T_erradi->TooltipValue = "";

			// LATITUD_sector
			$this->LATITUD_sector->LinkCustomAttributes = "";
			$this->LATITUD_sector->HrefValue = "";
			$this->LATITUD_sector->TooltipValue = "";

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->LinkCustomAttributes = "";
			$this->GRA_LAT_Sector->HrefValue = "";
			$this->GRA_LAT_Sector->TooltipValue = "";

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->LinkCustomAttributes = "";
			$this->MIN_LAT_Sector->HrefValue = "";
			$this->MIN_LAT_Sector->TooltipValue = "";

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->LinkCustomAttributes = "";
			$this->SEG_LAT_Sector->HrefValue = "";
			$this->SEG_LAT_Sector->TooltipValue = "";

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->LinkCustomAttributes = "";
			$this->GRA_LONG_Sector->HrefValue = "";
			$this->GRA_LONG_Sector->TooltipValue = "";

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->LinkCustomAttributes = "";
			$this->MIN_LONG_Sector->HrefValue = "";
			$this->MIN_LONG_Sector->TooltipValue = "";

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->LinkCustomAttributes = "";
			$this->SEG_LONG_Sector->HrefValue = "";
			$this->SEG_LONG_Sector->TooltipValue = "";

			// Ini_Jorna
			$this->Ini_Jorna->LinkCustomAttributes = "";
			$this->Ini_Jorna->HrefValue = "";
			$this->Ini_Jorna->TooltipValue = "";

			// Fin_Jorna
			$this->Fin_Jorna->LinkCustomAttributes = "";
			$this->Fin_Jorna->HrefValue = "";
			$this->Fin_Jorna->TooltipValue = "";

			// Situ_Especial
			$this->Situ_Especial->LinkCustomAttributes = "";
			$this->Situ_Especial->HrefValue = "";
			$this->Situ_Especial->TooltipValue = "";

			// Adm_GME
			$this->Adm_GME->LinkCustomAttributes = "";
			$this->Adm_GME->HrefValue = "";
			$this->Adm_GME->TooltipValue = "";

			// 1_Abastecimiento
			$this->_1_Abastecimiento->LinkCustomAttributes = "";
			$this->_1_Abastecimiento->HrefValue = "";
			$this->_1_Abastecimiento->TooltipValue = "";

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->LinkCustomAttributes = "";
			$this->_1_Acompanamiento_firma_GME->HrefValue = "";
			$this->_1_Acompanamiento_firma_GME->TooltipValue = "";

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->LinkCustomAttributes = "";
			$this->_1_Apoyo_zonal_sin_punto_asignado->HrefValue = "";
			$this->_1_Apoyo_zonal_sin_punto_asignado->TooltipValue = "";

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->LinkCustomAttributes = "";
			$this->_1_Descanso_en_dia_habil->HrefValue = "";
			$this->_1_Descanso_en_dia_habil->TooltipValue = "";

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->LinkCustomAttributes = "";
			$this->_1_Descanso_festivo_dominical->HrefValue = "";
			$this->_1_Descanso_festivo_dominical->TooltipValue = "";

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->LinkCustomAttributes = "";
			$this->_1_Dia_compensatorio->HrefValue = "";
			$this->_1_Dia_compensatorio->TooltipValue = "";

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->LinkCustomAttributes = "";
			$this->_1_Erradicacion_en_dia_festivo->HrefValue = "";
			$this->_1_Erradicacion_en_dia_festivo->TooltipValue = "";

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->LinkCustomAttributes = "";
			$this->_1_Espera_helicoptero_Helistar->HrefValue = "";
			$this->_1_Espera_helicoptero_Helistar->TooltipValue = "";

			// 1_Extraccion
			$this->_1_Extraccion->LinkCustomAttributes = "";
			$this->_1_Extraccion->HrefValue = "";
			$this->_1_Extraccion->TooltipValue = "";

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->LinkCustomAttributes = "";
			$this->_1_Firma_contrato_GME->HrefValue = "";
			$this->_1_Firma_contrato_GME->TooltipValue = "";

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->LinkCustomAttributes = "";
			$this->_1_Induccion_Apoyo_Zonal->HrefValue = "";
			$this->_1_Induccion_Apoyo_Zonal->TooltipValue = "";

			// 1_Insercion
			$this->_1_Insercion->LinkCustomAttributes = "";
			$this->_1_Insercion->HrefValue = "";
			$this->_1_Insercion->TooltipValue = "";

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->LinkCustomAttributes = "";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->HrefValue = "";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->TooltipValue = "";

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->LinkCustomAttributes = "";
			$this->_1_Novedad_apoyo_zonal->HrefValue = "";
			$this->_1_Novedad_apoyo_zonal->TooltipValue = "";

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->LinkCustomAttributes = "";
			$this->_1_Novedad_enfermero->HrefValue = "";
			$this->_1_Novedad_enfermero->TooltipValue = "";

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->LinkCustomAttributes = "";
			$this->_1_Punto_fuera_del_area_de_erradicacion->HrefValue = "";
			$this->_1_Punto_fuera_del_area_de_erradicacion->TooltipValue = "";

			// 1_Transporte_bus
			$this->_1_Transporte_bus->LinkCustomAttributes = "";
			$this->_1_Transporte_bus->HrefValue = "";
			$this->_1_Transporte_bus->TooltipValue = "";

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->LinkCustomAttributes = "";
			$this->_1_Traslado_apoyo_zonal->HrefValue = "";
			$this->_1_Traslado_apoyo_zonal->TooltipValue = "";

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->LinkCustomAttributes = "";
			$this->_1_Traslado_area_vivac->HrefValue = "";
			$this->_1_Traslado_area_vivac->TooltipValue = "";

			// Adm_Fuerza
			$this->Adm_Fuerza->LinkCustomAttributes = "";
			$this->Adm_Fuerza->HrefValue = "";
			$this->Adm_Fuerza->TooltipValue = "";

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->LinkCustomAttributes = "";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->HrefValue = "";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->TooltipValue = "";

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->LinkCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_de_seguridad->HrefValue = "";
			$this->_2_Espera_helicoptero_FP_de_seguridad->TooltipValue = "";

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->LinkCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_que_abastece->HrefValue = "";
			$this->_2_Espera_helicoptero_FP_que_abastece->TooltipValue = "";

			// 2_Induccion_FP
			$this->_2_Induccion_FP->LinkCustomAttributes = "";
			$this->_2_Induccion_FP->HrefValue = "";
			$this->_2_Induccion_FP->TooltipValue = "";

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->LinkCustomAttributes = "";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->HrefValue = "";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->TooltipValue = "";

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->LinkCustomAttributes = "";
			$this->_2_Problemas_fuerza_publica->HrefValue = "";
			$this->_2_Problemas_fuerza_publica->TooltipValue = "";

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->LinkCustomAttributes = "";
			$this->_2_Sin_seguridad->HrefValue = "";
			$this->_2_Sin_seguridad->TooltipValue = "";

			// Sit_Seguridad
			$this->Sit_Seguridad->LinkCustomAttributes = "";
			$this->Sit_Seguridad->HrefValue = "";
			$this->Sit_Seguridad->TooltipValue = "";

			// 3_AEI_controlado
			$this->_3_AEI_controlado->LinkCustomAttributes = "";
			$this->_3_AEI_controlado->HrefValue = "";
			$this->_3_AEI_controlado->TooltipValue = "";

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->LinkCustomAttributes = "";
			$this->_3_AEI_no_controlado->HrefValue = "";
			$this->_3_AEI_no_controlado->TooltipValue = "";

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->LinkCustomAttributes = "";
			$this->_3_Bloqueo_parcial_de_la_comunidad->HrefValue = "";
			$this->_3_Bloqueo_parcial_de_la_comunidad->TooltipValue = "";

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->LinkCustomAttributes = "";
			$this->_3_Bloqueo_total_de_la_comunidad->HrefValue = "";
			$this->_3_Bloqueo_total_de_la_comunidad->TooltipValue = "";

			// 3_Combate
			$this->_3_Combate->LinkCustomAttributes = "";
			$this->_3_Combate->HrefValue = "";
			$this->_3_Combate->TooltipValue = "";

			// 3_Hostigamiento
			$this->_3_Hostigamiento->LinkCustomAttributes = "";
			$this->_3_Hostigamiento->HrefValue = "";
			$this->_3_Hostigamiento->TooltipValue = "";

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->LinkCustomAttributes = "";
			$this->_3_MAP_Controlada->HrefValue = "";
			$this->_3_MAP_Controlada->TooltipValue = "";

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->LinkCustomAttributes = "";
			$this->_3_MAP_No_controlada->HrefValue = "";
			$this->_3_MAP_No_controlada->TooltipValue = "";

			// 3_MUSE
			$this->_3_MUSE->LinkCustomAttributes = "";
			$this->_3_MUSE->HrefValue = "";
			$this->_3_MUSE->TooltipValue = "";

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->LinkCustomAttributes = "";
			$this->_3_Operaciones_de_seguridad->HrefValue = "";
			$this->_3_Operaciones_de_seguridad->TooltipValue = "";

			// LATITUD_segurid
			$this->LATITUD_segurid->LinkCustomAttributes = "";
			$this->LATITUD_segurid->HrefValue = "";
			$this->LATITUD_segurid->TooltipValue = "";

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->LinkCustomAttributes = "";
			$this->GRA_LAT_segurid->HrefValue = "";
			$this->GRA_LAT_segurid->TooltipValue = "";

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->LinkCustomAttributes = "";
			$this->MIN_LAT_segurid->HrefValue = "";
			$this->MIN_LAT_segurid->TooltipValue = "";

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->LinkCustomAttributes = "";
			$this->SEG_LAT_segurid->HrefValue = "";
			$this->SEG_LAT_segurid->TooltipValue = "";

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->LinkCustomAttributes = "";
			$this->GRA_LONG_seguri->HrefValue = "";
			$this->GRA_LONG_seguri->TooltipValue = "";

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->LinkCustomAttributes = "";
			$this->MIN_LONG_seguri->HrefValue = "";
			$this->MIN_LONG_seguri->TooltipValue = "";

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->LinkCustomAttributes = "";
			$this->SEG_LONG_seguri->HrefValue = "";
			$this->SEG_LONG_seguri->TooltipValue = "";

			// Novedad
			$this->Novedad->LinkCustomAttributes = "";
			$this->Novedad->HrefValue = "";
			$this->Novedad->TooltipValue = "";

			// 4_Epidemia
			$this->_4_Epidemia->LinkCustomAttributes = "";
			$this->_4_Epidemia->HrefValue = "";
			$this->_4_Epidemia->TooltipValue = "";

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->LinkCustomAttributes = "";
			$this->_4_Novedad_climatologica->HrefValue = "";
			$this->_4_Novedad_climatologica->TooltipValue = "";

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->LinkCustomAttributes = "";
			$this->_4_Registro_de_cultivos->HrefValue = "";
			$this->_4_Registro_de_cultivos->TooltipValue = "";

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->LinkCustomAttributes = "";
			$this->_4_Zona_con_cultivos_muy_dispersos->HrefValue = "";
			$this->_4_Zona_con_cultivos_muy_dispersos->TooltipValue = "";

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->LinkCustomAttributes = "";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->HrefValue = "";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->TooltipValue = "";

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->LinkCustomAttributes = "";
			$this->_4_Zona_sin_cultivos->HrefValue = "";
			$this->_4_Zona_sin_cultivos->TooltipValue = "";

			// Num_Erra_Salen
			$this->Num_Erra_Salen->LinkCustomAttributes = "";
			$this->Num_Erra_Salen->HrefValue = "";
			$this->Num_Erra_Salen->TooltipValue = "";

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->LinkCustomAttributes = "";
			$this->Num_Erra_Quedan->HrefValue = "";
			$this->Num_Erra_Quedan->TooltipValue = "";

			// No_ENFERMERO
			$this->No_ENFERMERO->LinkCustomAttributes = "";
			$this->No_ENFERMERO->HrefValue = "";
			$this->No_ENFERMERO->TooltipValue = "";

			// NUM_FP
			$this->NUM_FP->LinkCustomAttributes = "";
			$this->NUM_FP->HrefValue = "";
			$this->NUM_FP->TooltipValue = "";

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->LinkCustomAttributes = "";
			$this->NUM_Perso_EVA->HrefValue = "";
			$this->NUM_Perso_EVA->TooltipValue = "";

			// NUM_Poli
			$this->NUM_Poli->LinkCustomAttributes = "";
			$this->NUM_Poli->HrefValue = "";
			$this->NUM_Poli->TooltipValue = "";

			// AÑO
			$this->AD1O->LinkCustomAttributes = "";
			$this->AD1O->HrefValue = "";
			$this->AD1O->TooltipValue = "";

			// FASE
			$this->FASE->LinkCustomAttributes = "";
			$this->FASE->HrefValue = "";
			$this->FASE->TooltipValue = "";

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
			$this->F_Sincron->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->F_Sincron->AdvancedSearch->SearchValue, 7), 7));
			$this->F_Sincron->PlaceHolder = ew_RemoveHtml($this->F_Sincron->FldCaption());

			// USUARIO
			$this->USUARIO->EditAttrs["class"] = "form-control";
			$this->USUARIO->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
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

			// NOM_PGE
			$this->NOM_PGE->EditAttrs["class"] = "form-control";
			$this->NOM_PGE->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
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

			// Otro_NOM_PGE
			$this->Otro_NOM_PGE->EditAttrs["class"] = "form-control";
			$this->Otro_NOM_PGE->EditCustomAttributes = "";
			$this->Otro_NOM_PGE->EditValue = ew_HtmlEncode($this->Otro_NOM_PGE->AdvancedSearch->SearchValue);
			$this->Otro_NOM_PGE->PlaceHolder = ew_RemoveHtml($this->Otro_NOM_PGE->FldCaption());

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

			// FECHA_REPORT
			$this->FECHA_REPORT->EditAttrs["class"] = "form-control";
			$this->FECHA_REPORT->EditCustomAttributes = "";
			$this->FECHA_REPORT->EditValue = ew_HtmlEncode($this->FECHA_REPORT->AdvancedSearch->SearchValue);
			$this->FECHA_REPORT->PlaceHolder = ew_RemoveHtml($this->FECHA_REPORT->FldCaption());

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

			// TEMA
			$this->TEMA->EditAttrs["class"] = "form-control";
			$this->TEMA->EditCustomAttributes = "";
			$this->TEMA->EditValue = ew_HtmlEncode($this->TEMA->AdvancedSearch->SearchValue);
			$this->TEMA->PlaceHolder = ew_RemoveHtml($this->TEMA->FldCaption());

			// Otro_Tema
			$this->Otro_Tema->EditAttrs["class"] = "form-control";
			$this->Otro_Tema->EditCustomAttributes = "";
			$this->Otro_Tema->EditValue = ew_HtmlEncode($this->Otro_Tema->AdvancedSearch->SearchValue);
			$this->Otro_Tema->PlaceHolder = ew_RemoveHtml($this->Otro_Tema->FldCaption());

			// OBSERVACION
			$this->OBSERVACION->EditAttrs["class"] = "form-control";
			$this->OBSERVACION->EditCustomAttributes = "";
			$this->OBSERVACION->EditValue = ew_HtmlEncode($this->OBSERVACION->AdvancedSearch->SearchValue);
			$this->OBSERVACION->PlaceHolder = ew_RemoveHtml($this->OBSERVACION->FldCaption());

			// NOM_VDA
			$this->NOM_VDA->EditAttrs["class"] = "form-control";
			$this->NOM_VDA->EditCustomAttributes = "";
			$this->NOM_VDA->EditValue = ew_HtmlEncode($this->NOM_VDA->AdvancedSearch->SearchValue);
			$this->NOM_VDA->PlaceHolder = ew_RemoveHtml($this->NOM_VDA->FldCaption());

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

			// T_erradi
			$this->T_erradi->EditAttrs["class"] = "form-control";
			$this->T_erradi->EditCustomAttributes = "";
			$this->T_erradi->EditValue = ew_HtmlEncode($this->T_erradi->AdvancedSearch->SearchValue);
			$this->T_erradi->PlaceHolder = ew_RemoveHtml($this->T_erradi->FldCaption());

			// LATITUD_sector
			$this->LATITUD_sector->EditAttrs["class"] = "form-control";
			$this->LATITUD_sector->EditCustomAttributes = "";
			$this->LATITUD_sector->EditValue = ew_HtmlEncode($this->LATITUD_sector->AdvancedSearch->SearchValue);
			$this->LATITUD_sector->PlaceHolder = ew_RemoveHtml($this->LATITUD_sector->FldCaption());

			// GRA_LAT_Sector
			$this->GRA_LAT_Sector->EditAttrs["class"] = "form-control";
			$this->GRA_LAT_Sector->EditCustomAttributes = "";
			$this->GRA_LAT_Sector->EditValue = ew_HtmlEncode($this->GRA_LAT_Sector->AdvancedSearch->SearchValue);
			$this->GRA_LAT_Sector->PlaceHolder = ew_RemoveHtml($this->GRA_LAT_Sector->FldCaption());

			// MIN_LAT_Sector
			$this->MIN_LAT_Sector->EditAttrs["class"] = "form-control";
			$this->MIN_LAT_Sector->EditCustomAttributes = "";
			$this->MIN_LAT_Sector->EditValue = ew_HtmlEncode($this->MIN_LAT_Sector->AdvancedSearch->SearchValue);
			$this->MIN_LAT_Sector->PlaceHolder = ew_RemoveHtml($this->MIN_LAT_Sector->FldCaption());

			// SEG_LAT_Sector
			$this->SEG_LAT_Sector->EditAttrs["class"] = "form-control";
			$this->SEG_LAT_Sector->EditCustomAttributes = "";
			$this->SEG_LAT_Sector->EditValue = ew_HtmlEncode($this->SEG_LAT_Sector->AdvancedSearch->SearchValue);
			$this->SEG_LAT_Sector->PlaceHolder = ew_RemoveHtml($this->SEG_LAT_Sector->FldCaption());

			// GRA_LONG_Sector
			$this->GRA_LONG_Sector->EditAttrs["class"] = "form-control";
			$this->GRA_LONG_Sector->EditCustomAttributes = "";
			$this->GRA_LONG_Sector->EditValue = ew_HtmlEncode($this->GRA_LONG_Sector->AdvancedSearch->SearchValue);
			$this->GRA_LONG_Sector->PlaceHolder = ew_RemoveHtml($this->GRA_LONG_Sector->FldCaption());

			// MIN_LONG_Sector
			$this->MIN_LONG_Sector->EditAttrs["class"] = "form-control";
			$this->MIN_LONG_Sector->EditCustomAttributes = "";
			$this->MIN_LONG_Sector->EditValue = ew_HtmlEncode($this->MIN_LONG_Sector->AdvancedSearch->SearchValue);
			$this->MIN_LONG_Sector->PlaceHolder = ew_RemoveHtml($this->MIN_LONG_Sector->FldCaption());

			// SEG_LONG_Sector
			$this->SEG_LONG_Sector->EditAttrs["class"] = "form-control";
			$this->SEG_LONG_Sector->EditCustomAttributes = "";
			$this->SEG_LONG_Sector->EditValue = ew_HtmlEncode($this->SEG_LONG_Sector->AdvancedSearch->SearchValue);
			$this->SEG_LONG_Sector->PlaceHolder = ew_RemoveHtml($this->SEG_LONG_Sector->FldCaption());

			// Ini_Jorna
			$this->Ini_Jorna->EditAttrs["class"] = "form-control";
			$this->Ini_Jorna->EditCustomAttributes = "";
			$this->Ini_Jorna->EditValue = ew_HtmlEncode($this->Ini_Jorna->AdvancedSearch->SearchValue);
			$this->Ini_Jorna->PlaceHolder = ew_RemoveHtml($this->Ini_Jorna->FldCaption());

			// Fin_Jorna
			$this->Fin_Jorna->EditAttrs["class"] = "form-control";
			$this->Fin_Jorna->EditCustomAttributes = "";
			$this->Fin_Jorna->EditValue = ew_HtmlEncode($this->Fin_Jorna->AdvancedSearch->SearchValue);
			$this->Fin_Jorna->PlaceHolder = ew_RemoveHtml($this->Fin_Jorna->FldCaption());

			// Situ_Especial
			
			$this->Situ_Especial->EditAttrs["class"] = "form-control";
			$this->Situ_Especial->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->Situ_Especial->FldTagValue(1), $this->Situ_Especial->FldTagCaption(1) <> "" ? $this->Situ_Especial->FldTagCaption(1) : $this->Situ_Especial->FldTagValue(1));
			$arwrk[] = array($this->Situ_Especial->FldTagValue(2), $this->Situ_Especial->FldTagCaption(2) <> "" ? $this->Situ_Especial->FldTagCaption(2) : $this->Situ_Especial->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->Situ_Especial->EditValue = $arwrk;
			
			
			// Adm_GME
			$this->Adm_GME->EditAttrs["class"] = "form-control";
			$this->Adm_GME->EditCustomAttributes = "";
			$this->Adm_GME->EditValue = ew_HtmlEncode($this->Adm_GME->AdvancedSearch->SearchValue);
			$this->Adm_GME->PlaceHolder = ew_RemoveHtml($this->Adm_GME->FldCaption());

			// 1_Abastecimiento
			$this->_1_Abastecimiento->EditAttrs["class"] = "form-control";
			$this->_1_Abastecimiento->EditCustomAttributes = "";
			$this->_1_Abastecimiento->EditValue = ew_HtmlEncode($this->_1_Abastecimiento->AdvancedSearch->SearchValue);
			$this->_1_Abastecimiento->PlaceHolder = ew_RemoveHtml($this->_1_Abastecimiento->FldCaption());

			// 1_Acompanamiento_firma_GME
			$this->_1_Acompanamiento_firma_GME->EditAttrs["class"] = "form-control";
			$this->_1_Acompanamiento_firma_GME->EditCustomAttributes = "";
			$this->_1_Acompanamiento_firma_GME->EditValue = ew_HtmlEncode($this->_1_Acompanamiento_firma_GME->AdvancedSearch->SearchValue);
			$this->_1_Acompanamiento_firma_GME->PlaceHolder = ew_RemoveHtml($this->_1_Acompanamiento_firma_GME->FldCaption());

			// 1_Apoyo_zonal_sin_punto_asignado
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditAttrs["class"] = "form-control";
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditCustomAttributes = "";
			$this->_1_Apoyo_zonal_sin_punto_asignado->EditValue = ew_HtmlEncode($this->_1_Apoyo_zonal_sin_punto_asignado->AdvancedSearch->SearchValue);
			$this->_1_Apoyo_zonal_sin_punto_asignado->PlaceHolder = ew_RemoveHtml($this->_1_Apoyo_zonal_sin_punto_asignado->FldCaption());

			// 1_Descanso_en_dia_habil
			$this->_1_Descanso_en_dia_habil->EditAttrs["class"] = "form-control";
			$this->_1_Descanso_en_dia_habil->EditCustomAttributes = "";
			$this->_1_Descanso_en_dia_habil->EditValue = ew_HtmlEncode($this->_1_Descanso_en_dia_habil->AdvancedSearch->SearchValue);
			$this->_1_Descanso_en_dia_habil->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_en_dia_habil->FldCaption());

			// 1_Descanso_festivo_dominical
			$this->_1_Descanso_festivo_dominical->EditAttrs["class"] = "form-control";
			$this->_1_Descanso_festivo_dominical->EditCustomAttributes = "";
			$this->_1_Descanso_festivo_dominical->EditValue = ew_HtmlEncode($this->_1_Descanso_festivo_dominical->AdvancedSearch->SearchValue);
			$this->_1_Descanso_festivo_dominical->PlaceHolder = ew_RemoveHtml($this->_1_Descanso_festivo_dominical->FldCaption());

			// 1_Dia_compensatorio
			$this->_1_Dia_compensatorio->EditAttrs["class"] = "form-control";
			$this->_1_Dia_compensatorio->EditCustomAttributes = "";
			$this->_1_Dia_compensatorio->EditValue = ew_HtmlEncode($this->_1_Dia_compensatorio->AdvancedSearch->SearchValue);
			$this->_1_Dia_compensatorio->PlaceHolder = ew_RemoveHtml($this->_1_Dia_compensatorio->FldCaption());

			// 1_Erradicacion_en_dia_festivo
			$this->_1_Erradicacion_en_dia_festivo->EditAttrs["class"] = "form-control";
			$this->_1_Erradicacion_en_dia_festivo->EditCustomAttributes = "";
			$this->_1_Erradicacion_en_dia_festivo->EditValue = ew_HtmlEncode($this->_1_Erradicacion_en_dia_festivo->AdvancedSearch->SearchValue);
			$this->_1_Erradicacion_en_dia_festivo->PlaceHolder = ew_RemoveHtml($this->_1_Erradicacion_en_dia_festivo->FldCaption());

			// 1_Espera_helicoptero_Helistar
			$this->_1_Espera_helicoptero_Helistar->EditAttrs["class"] = "form-control";
			$this->_1_Espera_helicoptero_Helistar->EditCustomAttributes = "";
			$this->_1_Espera_helicoptero_Helistar->EditValue = ew_HtmlEncode($this->_1_Espera_helicoptero_Helistar->AdvancedSearch->SearchValue);
			$this->_1_Espera_helicoptero_Helistar->PlaceHolder = ew_RemoveHtml($this->_1_Espera_helicoptero_Helistar->FldCaption());

			// 1_Extraccion
			$this->_1_Extraccion->EditAttrs["class"] = "form-control";
			$this->_1_Extraccion->EditCustomAttributes = "";
			$this->_1_Extraccion->EditValue = ew_HtmlEncode($this->_1_Extraccion->AdvancedSearch->SearchValue);
			$this->_1_Extraccion->PlaceHolder = ew_RemoveHtml($this->_1_Extraccion->FldCaption());

			// 1_Firma_contrato_GME
			$this->_1_Firma_contrato_GME->EditAttrs["class"] = "form-control";
			$this->_1_Firma_contrato_GME->EditCustomAttributes = "";
			$this->_1_Firma_contrato_GME->EditValue = ew_HtmlEncode($this->_1_Firma_contrato_GME->AdvancedSearch->SearchValue);
			$this->_1_Firma_contrato_GME->PlaceHolder = ew_RemoveHtml($this->_1_Firma_contrato_GME->FldCaption());

			// 1_Induccion_Apoyo_Zonal
			$this->_1_Induccion_Apoyo_Zonal->EditAttrs["class"] = "form-control";
			$this->_1_Induccion_Apoyo_Zonal->EditCustomAttributes = "";
			$this->_1_Induccion_Apoyo_Zonal->EditValue = ew_HtmlEncode($this->_1_Induccion_Apoyo_Zonal->AdvancedSearch->SearchValue);
			$this->_1_Induccion_Apoyo_Zonal->PlaceHolder = ew_RemoveHtml($this->_1_Induccion_Apoyo_Zonal->FldCaption());

			// 1_Insercion
			$this->_1_Insercion->EditAttrs["class"] = "form-control";
			$this->_1_Insercion->EditCustomAttributes = "";
			$this->_1_Insercion->EditValue = ew_HtmlEncode($this->_1_Insercion->AdvancedSearch->SearchValue);
			$this->_1_Insercion->PlaceHolder = ew_RemoveHtml($this->_1_Insercion->FldCaption());

			// 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditAttrs["class"] = "form-control";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditCustomAttributes = "";
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->EditValue = ew_HtmlEncode($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->AdvancedSearch->SearchValue);
			$this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->PlaceHolder = ew_RemoveHtml($this->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption());

			// 1_Novedad_apoyo_zonal
			$this->_1_Novedad_apoyo_zonal->EditAttrs["class"] = "form-control";
			$this->_1_Novedad_apoyo_zonal->EditCustomAttributes = "";
			$this->_1_Novedad_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Novedad_apoyo_zonal->AdvancedSearch->SearchValue);
			$this->_1_Novedad_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_apoyo_zonal->FldCaption());

			// 1_Novedad_enfermero
			$this->_1_Novedad_enfermero->EditAttrs["class"] = "form-control";
			$this->_1_Novedad_enfermero->EditCustomAttributes = "";
			$this->_1_Novedad_enfermero->EditValue = ew_HtmlEncode($this->_1_Novedad_enfermero->AdvancedSearch->SearchValue);
			$this->_1_Novedad_enfermero->PlaceHolder = ew_RemoveHtml($this->_1_Novedad_enfermero->FldCaption());

			// 1_Punto_fuera_del_area_de_erradicacion
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditAttrs["class"] = "form-control";
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditCustomAttributes = "";
			$this->_1_Punto_fuera_del_area_de_erradicacion->EditValue = ew_HtmlEncode($this->_1_Punto_fuera_del_area_de_erradicacion->AdvancedSearch->SearchValue);
			$this->_1_Punto_fuera_del_area_de_erradicacion->PlaceHolder = ew_RemoveHtml($this->_1_Punto_fuera_del_area_de_erradicacion->FldCaption());

			// 1_Transporte_bus
			$this->_1_Transporte_bus->EditAttrs["class"] = "form-control";
			$this->_1_Transporte_bus->EditCustomAttributes = "";
			$this->_1_Transporte_bus->EditValue = ew_HtmlEncode($this->_1_Transporte_bus->AdvancedSearch->SearchValue);
			$this->_1_Transporte_bus->PlaceHolder = ew_RemoveHtml($this->_1_Transporte_bus->FldCaption());

			// 1_Traslado_apoyo_zonal
			$this->_1_Traslado_apoyo_zonal->EditAttrs["class"] = "form-control";
			$this->_1_Traslado_apoyo_zonal->EditCustomAttributes = "";
			$this->_1_Traslado_apoyo_zonal->EditValue = ew_HtmlEncode($this->_1_Traslado_apoyo_zonal->AdvancedSearch->SearchValue);
			$this->_1_Traslado_apoyo_zonal->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_apoyo_zonal->FldCaption());

			// 1_Traslado_area_vivac
			$this->_1_Traslado_area_vivac->EditAttrs["class"] = "form-control";
			$this->_1_Traslado_area_vivac->EditCustomAttributes = "";
			$this->_1_Traslado_area_vivac->EditValue = ew_HtmlEncode($this->_1_Traslado_area_vivac->AdvancedSearch->SearchValue);
			$this->_1_Traslado_area_vivac->PlaceHolder = ew_RemoveHtml($this->_1_Traslado_area_vivac->FldCaption());

			// Adm_Fuerza
			$this->Adm_Fuerza->EditAttrs["class"] = "form-control";
			$this->Adm_Fuerza->EditCustomAttributes = "";
			$this->Adm_Fuerza->EditValue = ew_HtmlEncode($this->Adm_Fuerza->AdvancedSearch->SearchValue);
			$this->Adm_Fuerza->PlaceHolder = ew_RemoveHtml($this->Adm_Fuerza->FldCaption());

			// 2_A_la_espera_definicion_nuevo_punto_FP
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditAttrs["class"] = "form-control";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditCustomAttributes = "";
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->EditValue = ew_HtmlEncode($this->_2_A_la_espera_definicion_nuevo_punto_FP->AdvancedSearch->SearchValue);
			$this->_2_A_la_espera_definicion_nuevo_punto_FP->PlaceHolder = ew_RemoveHtml($this->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption());

			// 2_Espera_helicoptero_FP_de_seguridad
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditAttrs["class"] = "form-control";
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_de_seguridad->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_de_seguridad->AdvancedSearch->SearchValue);
			$this->_2_Espera_helicoptero_FP_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_de_seguridad->FldCaption());

			// 2_Espera_helicoptero_FP_que_abastece
			$this->_2_Espera_helicoptero_FP_que_abastece->EditAttrs["class"] = "form-control";
			$this->_2_Espera_helicoptero_FP_que_abastece->EditCustomAttributes = "";
			$this->_2_Espera_helicoptero_FP_que_abastece->EditValue = ew_HtmlEncode($this->_2_Espera_helicoptero_FP_que_abastece->AdvancedSearch->SearchValue);
			$this->_2_Espera_helicoptero_FP_que_abastece->PlaceHolder = ew_RemoveHtml($this->_2_Espera_helicoptero_FP_que_abastece->FldCaption());

			// 2_Induccion_FP
			$this->_2_Induccion_FP->EditAttrs["class"] = "form-control";
			$this->_2_Induccion_FP->EditCustomAttributes = "";
			$this->_2_Induccion_FP->EditValue = ew_HtmlEncode($this->_2_Induccion_FP->AdvancedSearch->SearchValue);
			$this->_2_Induccion_FP->PlaceHolder = ew_RemoveHtml($this->_2_Induccion_FP->FldCaption());

			// 2_Novedad_canino_o_del_grupo_de_deteccion
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditAttrs["class"] = "form-control";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditCustomAttributes = "";
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->EditValue = ew_HtmlEncode($this->_2_Novedad_canino_o_del_grupo_de_deteccion->AdvancedSearch->SearchValue);
			$this->_2_Novedad_canino_o_del_grupo_de_deteccion->PlaceHolder = ew_RemoveHtml($this->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption());

			// 2_Problemas_fuerza_publica
			$this->_2_Problemas_fuerza_publica->EditAttrs["class"] = "form-control";
			$this->_2_Problemas_fuerza_publica->EditCustomAttributes = "";
			$this->_2_Problemas_fuerza_publica->EditValue = ew_HtmlEncode($this->_2_Problemas_fuerza_publica->AdvancedSearch->SearchValue);
			$this->_2_Problemas_fuerza_publica->PlaceHolder = ew_RemoveHtml($this->_2_Problemas_fuerza_publica->FldCaption());

			// 2_Sin_seguridad
			$this->_2_Sin_seguridad->EditAttrs["class"] = "form-control";
			$this->_2_Sin_seguridad->EditCustomAttributes = "";
			$this->_2_Sin_seguridad->EditValue = ew_HtmlEncode($this->_2_Sin_seguridad->AdvancedSearch->SearchValue);
			$this->_2_Sin_seguridad->PlaceHolder = ew_RemoveHtml($this->_2_Sin_seguridad->FldCaption());

			// Sit_Seguridad
			$this->Sit_Seguridad->EditAttrs["class"] = "form-control";
			$this->Sit_Seguridad->EditCustomAttributes = "";
			$this->Sit_Seguridad->EditValue = ew_HtmlEncode($this->Sit_Seguridad->AdvancedSearch->SearchValue);
			$this->Sit_Seguridad->PlaceHolder = ew_RemoveHtml($this->Sit_Seguridad->FldCaption());

			// 3_AEI_controlado
			$this->_3_AEI_controlado->EditAttrs["class"] = "form-control";
			$this->_3_AEI_controlado->EditCustomAttributes = "";
			$this->_3_AEI_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_controlado->AdvancedSearch->SearchValue);
			$this->_3_AEI_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_controlado->FldCaption());

			// 3_AEI_no_controlado
			$this->_3_AEI_no_controlado->EditAttrs["class"] = "form-control";
			$this->_3_AEI_no_controlado->EditCustomAttributes = "";
			$this->_3_AEI_no_controlado->EditValue = ew_HtmlEncode($this->_3_AEI_no_controlado->AdvancedSearch->SearchValue);
			$this->_3_AEI_no_controlado->PlaceHolder = ew_RemoveHtml($this->_3_AEI_no_controlado->FldCaption());

			// 3_Bloqueo_parcial_de_la_comunidad
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditAttrs["class"] = "form-control";
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditCustomAttributes = "";
			$this->_3_Bloqueo_parcial_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_parcial_de_la_comunidad->AdvancedSearch->SearchValue);
			$this->_3_Bloqueo_parcial_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_parcial_de_la_comunidad->FldCaption());

			// 3_Bloqueo_total_de_la_comunidad
			$this->_3_Bloqueo_total_de_la_comunidad->EditAttrs["class"] = "form-control";
			$this->_3_Bloqueo_total_de_la_comunidad->EditCustomAttributes = "";
			$this->_3_Bloqueo_total_de_la_comunidad->EditValue = ew_HtmlEncode($this->_3_Bloqueo_total_de_la_comunidad->AdvancedSearch->SearchValue);
			$this->_3_Bloqueo_total_de_la_comunidad->PlaceHolder = ew_RemoveHtml($this->_3_Bloqueo_total_de_la_comunidad->FldCaption());

			// 3_Combate
			$this->_3_Combate->EditAttrs["class"] = "form-control";
			$this->_3_Combate->EditCustomAttributes = "";
			$this->_3_Combate->EditValue = ew_HtmlEncode($this->_3_Combate->AdvancedSearch->SearchValue);
			$this->_3_Combate->PlaceHolder = ew_RemoveHtml($this->_3_Combate->FldCaption());

			// 3_Hostigamiento
			$this->_3_Hostigamiento->EditAttrs["class"] = "form-control";
			$this->_3_Hostigamiento->EditCustomAttributes = "";
			$this->_3_Hostigamiento->EditValue = ew_HtmlEncode($this->_3_Hostigamiento->AdvancedSearch->SearchValue);
			$this->_3_Hostigamiento->PlaceHolder = ew_RemoveHtml($this->_3_Hostigamiento->FldCaption());

			// 3_MAP_Controlada
			$this->_3_MAP_Controlada->EditAttrs["class"] = "form-control";
			$this->_3_MAP_Controlada->EditCustomAttributes = "";
			$this->_3_MAP_Controlada->EditValue = ew_HtmlEncode($this->_3_MAP_Controlada->AdvancedSearch->SearchValue);
			$this->_3_MAP_Controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_Controlada->FldCaption());

			// 3_MAP_No_controlada
			$this->_3_MAP_No_controlada->EditAttrs["class"] = "form-control";
			$this->_3_MAP_No_controlada->EditCustomAttributes = "";
			$this->_3_MAP_No_controlada->EditValue = ew_HtmlEncode($this->_3_MAP_No_controlada->AdvancedSearch->SearchValue);
			$this->_3_MAP_No_controlada->PlaceHolder = ew_RemoveHtml($this->_3_MAP_No_controlada->FldCaption());

			// 3_MUSE
			$this->_3_MUSE->EditAttrs["class"] = "form-control";
			$this->_3_MUSE->EditCustomAttributes = "";
			$this->_3_MUSE->EditValue = ew_HtmlEncode($this->_3_MUSE->AdvancedSearch->SearchValue);
			$this->_3_MUSE->PlaceHolder = ew_RemoveHtml($this->_3_MUSE->FldCaption());

			// 3_Operaciones_de_seguridad
			$this->_3_Operaciones_de_seguridad->EditAttrs["class"] = "form-control";
			$this->_3_Operaciones_de_seguridad->EditCustomAttributes = "";
			$this->_3_Operaciones_de_seguridad->EditValue = ew_HtmlEncode($this->_3_Operaciones_de_seguridad->AdvancedSearch->SearchValue);
			$this->_3_Operaciones_de_seguridad->PlaceHolder = ew_RemoveHtml($this->_3_Operaciones_de_seguridad->FldCaption());

			// LATITUD_segurid
			$this->LATITUD_segurid->EditAttrs["class"] = "form-control";
			$this->LATITUD_segurid->EditCustomAttributes = "";
			$this->LATITUD_segurid->EditValue = ew_HtmlEncode($this->LATITUD_segurid->AdvancedSearch->SearchValue);
			$this->LATITUD_segurid->PlaceHolder = ew_RemoveHtml($this->LATITUD_segurid->FldCaption());

			// GRA_LAT_segurid
			$this->GRA_LAT_segurid->EditAttrs["class"] = "form-control";
			$this->GRA_LAT_segurid->EditCustomAttributes = "";
			$this->GRA_LAT_segurid->EditValue = ew_HtmlEncode($this->GRA_LAT_segurid->AdvancedSearch->SearchValue);
			$this->GRA_LAT_segurid->PlaceHolder = ew_RemoveHtml($this->GRA_LAT_segurid->FldCaption());

			// MIN_LAT_segurid
			$this->MIN_LAT_segurid->EditAttrs["class"] = "form-control";
			$this->MIN_LAT_segurid->EditCustomAttributes = "";
			$this->MIN_LAT_segurid->EditValue = ew_HtmlEncode($this->MIN_LAT_segurid->AdvancedSearch->SearchValue);
			$this->MIN_LAT_segurid->PlaceHolder = ew_RemoveHtml($this->MIN_LAT_segurid->FldCaption());

			// SEG_LAT_segurid
			$this->SEG_LAT_segurid->EditAttrs["class"] = "form-control";
			$this->SEG_LAT_segurid->EditCustomAttributes = "";
			$this->SEG_LAT_segurid->EditValue = ew_HtmlEncode($this->SEG_LAT_segurid->AdvancedSearch->SearchValue);
			$this->SEG_LAT_segurid->PlaceHolder = ew_RemoveHtml($this->SEG_LAT_segurid->FldCaption());

			// GRA_LONG_seguri
			$this->GRA_LONG_seguri->EditAttrs["class"] = "form-control";
			$this->GRA_LONG_seguri->EditCustomAttributes = "";
			$this->GRA_LONG_seguri->EditValue = ew_HtmlEncode($this->GRA_LONG_seguri->AdvancedSearch->SearchValue);
			$this->GRA_LONG_seguri->PlaceHolder = ew_RemoveHtml($this->GRA_LONG_seguri->FldCaption());

			// MIN_LONG_seguri
			$this->MIN_LONG_seguri->EditAttrs["class"] = "form-control";
			$this->MIN_LONG_seguri->EditCustomAttributes = "";
			$this->MIN_LONG_seguri->EditValue = ew_HtmlEncode($this->MIN_LONG_seguri->AdvancedSearch->SearchValue);
			$this->MIN_LONG_seguri->PlaceHolder = ew_RemoveHtml($this->MIN_LONG_seguri->FldCaption());

			// SEG_LONG_seguri
			$this->SEG_LONG_seguri->EditAttrs["class"] = "form-control";
			$this->SEG_LONG_seguri->EditCustomAttributes = "";
			$this->SEG_LONG_seguri->EditValue = ew_HtmlEncode($this->SEG_LONG_seguri->AdvancedSearch->SearchValue);
			$this->SEG_LONG_seguri->PlaceHolder = ew_RemoveHtml($this->SEG_LONG_seguri->FldCaption());

			// Novedad
			$this->Novedad->EditAttrs["class"] = "form-control";
			$this->Novedad->EditCustomAttributes = "";
			$this->Novedad->EditValue = ew_HtmlEncode($this->Novedad->AdvancedSearch->SearchValue);
			$this->Novedad->PlaceHolder = ew_RemoveHtml($this->Novedad->FldCaption());

			// 4_Epidemia
			$this->_4_Epidemia->EditAttrs["class"] = "form-control";
			$this->_4_Epidemia->EditCustomAttributes = "";
			$this->_4_Epidemia->EditValue = ew_HtmlEncode($this->_4_Epidemia->AdvancedSearch->SearchValue);
			$this->_4_Epidemia->PlaceHolder = ew_RemoveHtml($this->_4_Epidemia->FldCaption());

			// 4_Novedad_climatologica
			$this->_4_Novedad_climatologica->EditAttrs["class"] = "form-control";
			$this->_4_Novedad_climatologica->EditCustomAttributes = "";
			$this->_4_Novedad_climatologica->EditValue = ew_HtmlEncode($this->_4_Novedad_climatologica->AdvancedSearch->SearchValue);
			$this->_4_Novedad_climatologica->PlaceHolder = ew_RemoveHtml($this->_4_Novedad_climatologica->FldCaption());

			// 4_Registro_de_cultivos
			$this->_4_Registro_de_cultivos->EditAttrs["class"] = "form-control";
			$this->_4_Registro_de_cultivos->EditCustomAttributes = "";
			$this->_4_Registro_de_cultivos->EditValue = ew_HtmlEncode($this->_4_Registro_de_cultivos->AdvancedSearch->SearchValue);
			$this->_4_Registro_de_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Registro_de_cultivos->FldCaption());

			// 4_Zona_con_cultivos_muy_dispersos
			$this->_4_Zona_con_cultivos_muy_dispersos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_con_cultivos_muy_dispersos->EditCustomAttributes = "";
			$this->_4_Zona_con_cultivos_muy_dispersos->EditValue = ew_HtmlEncode($this->_4_Zona_con_cultivos_muy_dispersos->AdvancedSearch->SearchValue);
			$this->_4_Zona_con_cultivos_muy_dispersos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_con_cultivos_muy_dispersos->FldCaption());

			// 4_Zona_de_cruce_de_rios_caudalosos
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditCustomAttributes = "";
			$this->_4_Zona_de_cruce_de_rios_caudalosos->EditValue = ew_HtmlEncode($this->_4_Zona_de_cruce_de_rios_caudalosos->AdvancedSearch->SearchValue);
			$this->_4_Zona_de_cruce_de_rios_caudalosos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption());

			// 4_Zona_sin_cultivos
			$this->_4_Zona_sin_cultivos->EditAttrs["class"] = "form-control";
			$this->_4_Zona_sin_cultivos->EditCustomAttributes = "";
			$this->_4_Zona_sin_cultivos->EditValue = ew_HtmlEncode($this->_4_Zona_sin_cultivos->AdvancedSearch->SearchValue);
			$this->_4_Zona_sin_cultivos->PlaceHolder = ew_RemoveHtml($this->_4_Zona_sin_cultivos->FldCaption());

			// Num_Erra_Salen
			$this->Num_Erra_Salen->EditAttrs["class"] = "form-control";
			$this->Num_Erra_Salen->EditCustomAttributes = "";
			$this->Num_Erra_Salen->EditValue = ew_HtmlEncode($this->Num_Erra_Salen->AdvancedSearch->SearchValue);
			$this->Num_Erra_Salen->PlaceHolder = ew_RemoveHtml($this->Num_Erra_Salen->FldCaption());

			// Num_Erra_Quedan
			$this->Num_Erra_Quedan->EditAttrs["class"] = "form-control";
			$this->Num_Erra_Quedan->EditCustomAttributes = "";
			$this->Num_Erra_Quedan->EditValue = ew_HtmlEncode($this->Num_Erra_Quedan->AdvancedSearch->SearchValue);
			$this->Num_Erra_Quedan->PlaceHolder = ew_RemoveHtml($this->Num_Erra_Quedan->FldCaption());

			// No_ENFERMERO
			$this->No_ENFERMERO->EditAttrs["class"] = "form-control";
			$this->No_ENFERMERO->EditCustomAttributes = "";
			$this->No_ENFERMERO->EditValue = ew_HtmlEncode($this->No_ENFERMERO->AdvancedSearch->SearchValue);
			$this->No_ENFERMERO->PlaceHolder = ew_RemoveHtml($this->No_ENFERMERO->FldCaption());

			// NUM_FP
			$this->NUM_FP->EditAttrs["class"] = "form-control";
			$this->NUM_FP->EditCustomAttributes = "";
			$this->NUM_FP->EditValue = ew_HtmlEncode($this->NUM_FP->AdvancedSearch->SearchValue);
			$this->NUM_FP->PlaceHolder = ew_RemoveHtml($this->NUM_FP->FldCaption());

			// NUM_Perso_EVA
			$this->NUM_Perso_EVA->EditAttrs["class"] = "form-control";
			$this->NUM_Perso_EVA->EditCustomAttributes = "";
			$this->NUM_Perso_EVA->EditValue = ew_HtmlEncode($this->NUM_Perso_EVA->AdvancedSearch->SearchValue);
			$this->NUM_Perso_EVA->PlaceHolder = ew_RemoveHtml($this->NUM_Perso_EVA->FldCaption());

			// NUM_Poli
			$this->NUM_Poli->EditAttrs["class"] = "form-control";
			$this->NUM_Poli->EditCustomAttributes = "";
			$this->NUM_Poli->EditValue = ew_HtmlEncode($this->NUM_Poli->AdvancedSearch->SearchValue);
			$this->NUM_Poli->PlaceHolder = ew_RemoveHtml($this->NUM_Poli->FldCaption());

			// AÑO
			$this->AD1O->EditAttrs["class"] = "form-control";
			$this->AD1O->EditCustomAttributes = "";
			$sFilterWrk = "";
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
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
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `view_id`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->FASE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `FASE` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->FASE->EditValue = $arwrk;

			// Modificado
			$this->Modificado->EditAttrs["class"] = "form-control";
			$this->Modificado->EditCustomAttributes = "";
			$this->Modificado->EditValue = ew_HtmlEncode($this->Modificado->AdvancedSearch->SearchValue);
			$this->Modificado->PlaceHolder = ew_RemoveHtml($this->Modificado->FldCaption());
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
		$this->NOM_PGE->AdvancedSearch->Load();
		$this->Otro_NOM_PGE->AdvancedSearch->Load();
		$this->FECHA_REPORT->AdvancedSearch->Load();
		$this->Departamento->AdvancedSearch->Load();
		$this->Muncipio->AdvancedSearch->Load();
		$this->FUERZA->AdvancedSearch->Load();
		$this->_3_MUSE->AdvancedSearch->Load();
		$this->AD1O->AdvancedSearch->Load();
		$this->FASE->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_view_id\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view_id',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview_idlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view_id_list)) $view_id_list = new cview_id_list();

// Page init
$view_id_list->Page_Init();

// Page main
$view_id_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_id_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view_id->Export == "") { ?>
<script type="text/javascript">

// Page object
var view_id_list = new ew_Page("view_id_list");
view_id_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view_id_list.PageID; // For backward compatibility

// Form object
var fview_idlist = new ew_Form("fview_idlist");
fview_idlist.FormKeyCountName = '<?php echo $view_id_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview_idlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_idlist.ValidateRequired = true;
<?php } else { ?>
fview_idlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_idlist.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlist.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlist.Lists["x_NOM_PGE"] = {"LinkField":"x_NOM_PGE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PGE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlist.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlist.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fview_idlistsrch = new ew_Form("fview_idlistsrch");

// Validate function for search
fview_idlistsrch.Validate = function(fobj) {
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
fview_idlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_idlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fview_idlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fview_idlistsrch.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlistsrch.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlistsrch.Lists["x_NOM_PGE"] = {"LinkField":"x_NOM_PGE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PGE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlistsrch.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_idlistsrch.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view_id->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view_id->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($view_id->Export == "") { ?>
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
			<?php if ($view_id_list->TotalRecs > 0 && $view_id_list->ExportOptions->Visible()) { ?>

			<?php $view_id_list->ExportOptions->Render("body") ?>
			<?php } ?>

		</td>
		<td>
			Si desea exportar la tabla en formato excel haga click en el siguiente icono 
		</td>	
	</tr>	
</table> 

<hr>

</div>
<?php if ($view_id->Export == "") { ?>

<div>
<br>
<table>
	<tr>
		<td>
			<?php if ($view_id_list->SearchOptions->Visible()) { ?>
			<?php $view_id_list->SearchOptions->Render("body") ?>
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
		if ($view_id_list->TotalRecs <= 0)
			$view_id_list->TotalRecs = $view_id->SelectRecordCount();
	} else {
		if (!$view_id_list->Recordset && ($view_id_list->Recordset = $view_id_list->LoadRecordset()))
			$view_id_list->TotalRecs = $view_id_list->Recordset->RecordCount();
	}
	$view_id_list->StartRec = 1;
	if ($view_id_list->DisplayRecs <= 0 || ($view_id->Export <> "" && $view_id->ExportAll)) // Display all records
		$view_id_list->DisplayRecs = $view_id_list->TotalRecs;
	if (!($view_id->Export <> "" && $view_id->ExportAll))
		$view_id_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view_id_list->Recordset = $view_id_list->LoadRecordset($view_id_list->StartRec-1, $view_id_list->DisplayRecs);

	// Set no record found message
	if ($view_id->CurrentAction == "" && $view_id_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view_id_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view_id_list->SearchWhere == "0=101")
			$view_id_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view_id_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view_id_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view_id->Export == "" && $view_id->CurrentAction == "") { ?>
<form name="fview_idlistsrch" id="fview_idlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view_id_list->SearchWhere <> "") ? " " : " "; ?>
<div id="fview_idlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_id">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$view_id_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$view_id->RowType = EW_ROWTYPE_SEARCH;

// Render row
$view_id->ResetAttrs();
$view_id_list->RenderRow();
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
			<select style="min-width: 350px;" data-field="x_USUARIO" id="x_USUARIO" name="x_USUARIO"<?php echo $view_id->USUARIO->EditAttributes() ?>>
				<?php
				if (is_array($view_id->USUARIO->EditValue)) {
					$arwrk = $view_id->USUARIO->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_id->USUARIO->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_idlistsrch.Lists["x_USUARIO"].Options = <?php echo (is_array($view_id->USUARIO->EditValue)) ? ew_ArrayToJson($view_id->USUARIO->EditValue, 1) : "[]" ?>;
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
			<select style="min-width: 350px;" data-field="x_NOM_PE" id="x_NOM_PE" name="x_NOM_PE"<?php echo $view_id->NOM_PE->EditAttributes() ?>>
				<?php
				if (is_array($view_id->NOM_PE->EditValue)) {
					$arwrk = $view_id->NOM_PE->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_id->NOM_PE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_idlistsrch.Lists["x_NOM_PE"].Options = <?php echo (is_array($view_id->NOM_PE->EditValue)) ? ew_ArrayToJson($view_id->NOM_PE->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>
	<tr>
		<td>
			<label for="x_NOM_PGE" class="ewSearchCaption ewLabel">Profesional especializado</label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_NOM_PGE" id="z_NOM_PGE" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_NOM_PGE" id="x_NOM_PGE" name="x_NOM_PGE"<?php echo $view_id->NOM_PGE->EditAttributes() ?>>
				<?php
				if (is_array($view_id->NOM_PGE->EditValue)) {
					$arwrk = $view_id->NOM_PGE->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_id->NOM_PGE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_idlistsrch.Lists["x_NOM_PGE"].Options = <?php echo (is_array($view_id->NOM_PGE->EditValue)) ? ew_ArrayToJson($view_id->NOM_PGE->EditValue, 1) : "[]" ?>;
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
			<select style="min-width: 350px;" data-field="x_AD1O" id="x_AD1O" name="x_AD1O"<?php echo $view_id->AD1O->EditAttributes() ?>>
				<?php
				if (is_array($view_id->AD1O->EditValue)) {
					$arwrk = $view_id->AD1O->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_id->AD1O->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_idlistsrch.Lists["x_AD1O"].Options = <?php echo (is_array($view_id->AD1O->EditValue)) ? ew_ArrayToJson($view_id->AD1O->EditValue, 1) : "[]" ?>;
				</script>
			</span>
		</td>
	</tr>	
	<tr>
		<td>
			<label for="x_FASE" class="ewSearchCaption ewLabel"><?php echo $view_id->FASE->FldCaption() ?></label>
			<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_FASE" id="z_FASE" value="LIKE"></span>
		</td>
		<td width="5%"></td>
		<td>
			<span class="ewSearchField">
			<select style="min-width: 350px;" data-field="x_FASE" id="x_FASE" name="x_FASE"<?php echo $view_id->FASE->EditAttributes() ?>>
				<?php
				if (is_array($view_id->FASE->EditValue)) {
					$arwrk = $view_id->FASE->EditValue;
					$rowswrk = count($arwrk);
					$emptywrk = TRUE;
					for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
						$selwrk = (strval($view_id->FASE->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
				fview_idlistsrch.Lists["x_FASE"].Options = <?php echo (is_array($view_id->FASE->EditValue)) ? ew_ArrayToJson($view_id->FASE->EditValue, 1) : "[]" ?>;
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
<?php $view_id_list->ShowPageHeader(); ?>
<?php
$view_id_list->ShowMessage();
?>
<?php if ($view_id_list->TotalRecs > 0 || $view_id->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($view_id->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($view_id->CurrentAction <> "gridadd" && $view_id->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_id_list->Pager)) $view_id_list->Pager = new cPrevNextPager($view_id_list->StartRec, $view_id_list->DisplayRecs, $view_id_list->TotalRecs) ?>
<?php if ($view_id_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_id_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_id_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_id_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_id_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_id_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_id_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_id_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_id_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_id_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_id_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview_idlist" id="fview_idlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_id_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_id_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_id">
<div id="gmp_view_id" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view_id_list->TotalRecs > 0) { ?>
<table id="tbl_view_idlist" class="table ewTable">
<?php echo $view_id->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view_id->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view_id_list->RenderListOptions();

// Render list options (header, left)
$view_id_list->ListOptions->Render("header", "left");
?>
<?php if ($view_id->llave->Visible) { // llave ?>
	<?php if ($view_id->SortUrl($view_id->llave) == "") { ?>
		<th data-name="llave"><div id="elh_view_id_llave" class="view_id_llave"><div class="ewTableHeaderCaption"><?php echo $view_id->llave->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="llave"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->llave) ?>',2);"><div id="elh_view_id_llave" class="view_id_llave">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->llave->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_id->llave->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->llave->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->F_Sincron->Visible) { // F_Sincron ?>
	<?php if ($view_id->SortUrl($view_id->F_Sincron) == "") { ?>
		<th data-name="F_Sincron"><div id="elh_view_id_F_Sincron" class="view_id_F_Sincron"><div class="ewTableHeaderCaption"><?php echo $view_id->F_Sincron->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F_Sincron"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->F_Sincron) ?>',2);"><div id="elh_view_id_F_Sincron" class="view_id_F_Sincron">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->F_Sincron->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->F_Sincron->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->F_Sincron->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->USUARIO->Visible) { // USUARIO ?>
	<?php if ($view_id->SortUrl($view_id->USUARIO) == "") { ?>
		<th data-name="USUARIO"><div id="elh_view_id_USUARIO" class="view_id_USUARIO"><div class="ewTableHeaderCaption"><?php echo $view_id->USUARIO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="USUARIO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->USUARIO) ?>',2);"><div id="elh_view_id_USUARIO" class="view_id_USUARIO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->USUARIO->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->USUARIO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->USUARIO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Cargo_gme->Visible) { // Cargo_gme ?>
	<?php if ($view_id->SortUrl($view_id->Cargo_gme) == "") { ?>
		<th data-name="Cargo_gme"><div id="elh_view_id_Cargo_gme" class="view_id_Cargo_gme"><div class="ewTableHeaderCaption"><?php echo $view_id->Cargo_gme->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo_gme"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Cargo_gme) ?>',2);"><div id="elh_view_id_Cargo_gme" class="view_id_Cargo_gme">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Cargo_gme->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Cargo_gme->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Cargo_gme->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->NOM_PE->Visible) { // NOM_PE ?>
	<?php if ($view_id->SortUrl($view_id->NOM_PE) == "") { ?>
		<th data-name="NOM_PE"><div id="elh_view_id_NOM_PE" class="view_id_NOM_PE"><div class="ewTableHeaderCaption"><?php echo $view_id->NOM_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->NOM_PE) ?>',2);"><div id="elh_view_id_NOM_PE" class="view_id_NOM_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->NOM_PE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->NOM_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->NOM_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Otro_PE->Visible) { // Otro_PE ?>
	<?php if ($view_id->SortUrl($view_id->Otro_PE) == "") { ?>
		<th data-name="Otro_PE"><div id="elh_view_id_Otro_PE" class="view_id_Otro_PE"><div class="ewTableHeaderCaption"><?php echo $view_id->Otro_PE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_PE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Otro_PE) ?>',2);"><div id="elh_view_id_Otro_PE" class="view_id_Otro_PE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Otro_PE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Otro_PE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Otro_PE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->NOM_PGE->Visible) { // NOM_PGE ?>
	<?php if ($view_id->SortUrl($view_id->NOM_PGE) == "") { ?>
		<th data-name="NOM_PGE"><div id="elh_view_id_NOM_PGE" class="view_id_NOM_PGE"><div class="ewTableHeaderCaption"><?php echo $view_id->NOM_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->NOM_PGE) ?>',2);"><div id="elh_view_id_NOM_PGE" class="view_id_NOM_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->NOM_PGE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->NOM_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->NOM_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Otro_NOM_PGE->Visible) { // Otro_NOM_PGE ?>
	<?php if ($view_id->SortUrl($view_id->Otro_NOM_PGE) == "") { ?>
		<th data-name="Otro_NOM_PGE"><div id="elh_view_id_Otro_NOM_PGE" class="view_id_Otro_NOM_PGE"><div class="ewTableHeaderCaption"><?php echo $view_id->Otro_NOM_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_NOM_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Otro_NOM_PGE) ?>',2);"><div id="elh_view_id_Otro_NOM_PGE" class="view_id_Otro_NOM_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Otro_NOM_PGE->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Otro_NOM_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Otro_NOM_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
	<?php if ($view_id->SortUrl($view_id->Otro_CC_PGE) == "") { ?>
		<th data-name="Otro_CC_PGE"><div id="elh_view_id_Otro_CC_PGE" class="view_id_Otro_CC_PGE"><div class="ewTableHeaderCaption"><?php echo $view_id->Otro_CC_PGE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_CC_PGE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Otro_CC_PGE) ?>',2);"><div id="elh_view_id_Otro_CC_PGE" class="view_id_Otro_CC_PGE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Otro_CC_PGE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Otro_CC_PGE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Otro_CC_PGE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
	<?php if ($view_id->SortUrl($view_id->TIPO_INFORME) == "") { ?>
		<th data-name="TIPO_INFORME"><div id="elh_view_id_TIPO_INFORME" class="view_id_TIPO_INFORME"><div class="ewTableHeaderCaption"><?php echo $view_id->TIPO_INFORME->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TIPO_INFORME"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->TIPO_INFORME) ?>',2);"><div id="elh_view_id_TIPO_INFORME" class="view_id_TIPO_INFORME">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->TIPO_INFORME->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->TIPO_INFORME->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->TIPO_INFORME->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->FECHA_REPORT->Visible) { // FECHA_REPORT ?>
	<?php if ($view_id->SortUrl($view_id->FECHA_REPORT) == "") { ?>
		<th data-name="FECHA_REPORT"><div id="elh_view_id_FECHA_REPORT" class="view_id_FECHA_REPORT"><div class="ewTableHeaderCaption"><?php echo $view_id->FECHA_REPORT->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FECHA_REPORT"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->FECHA_REPORT) ?>',2);"><div id="elh_view_id_FECHA_REPORT" class="view_id_FECHA_REPORT">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->FECHA_REPORT->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_id->FECHA_REPORT->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->FECHA_REPORT->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->DIA->Visible) { // DIA ?>
	<?php if ($view_id->SortUrl($view_id->DIA) == "") { ?>
		<th data-name="DIA"><div id="elh_view_id_DIA" class="view_id_DIA"><div class="ewTableHeaderCaption"><?php echo $view_id->DIA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DIA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->DIA) ?>',2);"><div id="elh_view_id_DIA" class="view_id_DIA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->DIA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->DIA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->DIA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->MES->Visible) { // MES ?>
	<?php if ($view_id->SortUrl($view_id->MES) == "") { ?>
		<th data-name="MES"><div id="elh_view_id_MES" class="view_id_MES"><div class="ewTableHeaderCaption"><?php echo $view_id->MES->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MES"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->MES) ?>',2);"><div id="elh_view_id_MES" class="view_id_MES">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->MES->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->MES->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->MES->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Departamento->Visible) { // Departamento ?>
	<?php if ($view_id->SortUrl($view_id->Departamento) == "") { ?>
		<th data-name="Departamento"><div id="elh_view_id_Departamento" class="view_id_Departamento"><div class="ewTableHeaderCaption"><?php echo $view_id->Departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Departamento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Departamento) ?>',2);"><div id="elh_view_id_Departamento" class="view_id_Departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Departamento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Muncipio->Visible) { // Muncipio ?>
	<?php if ($view_id->SortUrl($view_id->Muncipio) == "") { ?>
		<th data-name="Muncipio"><div id="elh_view_id_Muncipio" class="view_id_Muncipio"><div class="ewTableHeaderCaption"><?php echo $view_id->Muncipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Muncipio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Muncipio) ?>',2);"><div id="elh_view_id_Muncipio" class="view_id_Muncipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Muncipio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Muncipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Muncipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->TEMA->Visible) { // TEMA ?>
	<?php if ($view_id->SortUrl($view_id->TEMA) == "") { ?>
		<th data-name="TEMA"><div id="elh_view_id_TEMA" class="view_id_TEMA"><div class="ewTableHeaderCaption"><?php echo $view_id->TEMA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TEMA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->TEMA) ?>',2);"><div id="elh_view_id_TEMA" class="view_id_TEMA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->TEMA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->TEMA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->TEMA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Otro_Tema->Visible) { // Otro_Tema ?>
	<?php if ($view_id->SortUrl($view_id->Otro_Tema) == "") { ?>
		<th data-name="Otro_Tema"><div id="elh_view_id_Otro_Tema" class="view_id_Otro_Tema"><div class="ewTableHeaderCaption"><?php echo $view_id->Otro_Tema->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Otro_Tema"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Otro_Tema) ?>',2);"><div id="elh_view_id_Otro_Tema" class="view_id_Otro_Tema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Otro_Tema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Otro_Tema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Otro_Tema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->OBSERVACION->Visible) { // OBSERVACION ?>
	<?php if ($view_id->SortUrl($view_id->OBSERVACION) == "") { ?>
		<th data-name="OBSERVACION"><div id="elh_view_id_OBSERVACION" class="view_id_OBSERVACION"><div class="ewTableHeaderCaption"><?php echo $view_id->OBSERVACION->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OBSERVACION"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->OBSERVACION) ?>',2);"><div id="elh_view_id_OBSERVACION" class="view_id_OBSERVACION">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->OBSERVACION->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->OBSERVACION->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->OBSERVACION->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->NOM_VDA->Visible) { // NOM_VDA ?>
	<?php if ($view_id->SortUrl($view_id->NOM_VDA) == "") { ?>
		<th data-name="NOM_VDA"><div id="elh_view_id_NOM_VDA" class="view_id_NOM_VDA"><div class="ewTableHeaderCaption"><?php echo $view_id->NOM_VDA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOM_VDA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->NOM_VDA) ?>',2);"><div id="elh_view_id_NOM_VDA" class="view_id_NOM_VDA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->NOM_VDA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->NOM_VDA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->NOM_VDA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Ha_Coca->Visible) { // Ha_Coca ?>
	<?php if ($view_id->SortUrl($view_id->Ha_Coca) == "") { ?>
		<th data-name="Ha_Coca"><div id="elh_view_id_Ha_Coca" class="view_id_Ha_Coca"><div class="ewTableHeaderCaption"><?php echo $view_id->Ha_Coca->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ha_Coca"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Ha_Coca) ?>',2);"><div id="elh_view_id_Ha_Coca" class="view_id_Ha_Coca">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Ha_Coca->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Ha_Coca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Ha_Coca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Ha_Amapola->Visible) { // Ha_Amapola ?>
	<?php if ($view_id->SortUrl($view_id->Ha_Amapola) == "") { ?>
		<th data-name="Ha_Amapola"><div id="elh_view_id_Ha_Amapola" class="view_id_Ha_Amapola"><div class="ewTableHeaderCaption"><?php echo $view_id->Ha_Amapola->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ha_Amapola"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Ha_Amapola) ?>',2);"><div id="elh_view_id_Ha_Amapola" class="view_id_Ha_Amapola">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Ha_Amapola->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Ha_Amapola->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Ha_Amapola->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Ha_Marihuana->Visible) { // Ha_Marihuana ?>
	<?php if ($view_id->SortUrl($view_id->Ha_Marihuana) == "") { ?>
		<th data-name="Ha_Marihuana"><div id="elh_view_id_Ha_Marihuana" class="view_id_Ha_Marihuana"><div class="ewTableHeaderCaption"><?php echo $view_id->Ha_Marihuana->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ha_Marihuana"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Ha_Marihuana) ?>',2);"><div id="elh_view_id_Ha_Marihuana" class="view_id_Ha_Marihuana">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Ha_Marihuana->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Ha_Marihuana->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Ha_Marihuana->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->T_erradi->Visible) { // T_erradi ?>
	<?php if ($view_id->SortUrl($view_id->T_erradi) == "") { ?>
		<th data-name="T_erradi"><div id="elh_view_id_T_erradi" class="view_id_T_erradi"><div class="ewTableHeaderCaption"><?php echo $view_id->T_erradi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="T_erradi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->T_erradi) ?>',2);"><div id="elh_view_id_T_erradi" class="view_id_T_erradi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->T_erradi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->T_erradi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->T_erradi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->LATITUD_sector->Visible) { // LATITUD_sector ?>
	<?php if ($view_id->SortUrl($view_id->LATITUD_sector) == "") { ?>
		<th data-name="LATITUD_sector"><div id="elh_view_id_LATITUD_sector" class="view_id_LATITUD_sector"><div class="ewTableHeaderCaption"><?php echo $view_id->LATITUD_sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="LATITUD_sector"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->LATITUD_sector) ?>',2);"><div id="elh_view_id_LATITUD_sector" class="view_id_LATITUD_sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->LATITUD_sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->LATITUD_sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->LATITUD_sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->GRA_LAT_Sector->Visible) { // GRA_LAT_Sector ?>
	<?php if ($view_id->SortUrl($view_id->GRA_LAT_Sector) == "") { ?>
		<th data-name="GRA_LAT_Sector"><div id="elh_view_id_GRA_LAT_Sector" class="view_id_GRA_LAT_Sector"><div class="ewTableHeaderCaption"><?php echo $view_id->GRA_LAT_Sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LAT_Sector"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->GRA_LAT_Sector) ?>',2);"><div id="elh_view_id_GRA_LAT_Sector" class="view_id_GRA_LAT_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->GRA_LAT_Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->GRA_LAT_Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->GRA_LAT_Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->MIN_LAT_Sector->Visible) { // MIN_LAT_Sector ?>
	<?php if ($view_id->SortUrl($view_id->MIN_LAT_Sector) == "") { ?>
		<th data-name="MIN_LAT_Sector"><div id="elh_view_id_MIN_LAT_Sector" class="view_id_MIN_LAT_Sector"><div class="ewTableHeaderCaption"><?php echo $view_id->MIN_LAT_Sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LAT_Sector"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->MIN_LAT_Sector) ?>',2);"><div id="elh_view_id_MIN_LAT_Sector" class="view_id_MIN_LAT_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->MIN_LAT_Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->MIN_LAT_Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->MIN_LAT_Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->SEG_LAT_Sector->Visible) { // SEG_LAT_Sector ?>
	<?php if ($view_id->SortUrl($view_id->SEG_LAT_Sector) == "") { ?>
		<th data-name="SEG_LAT_Sector"><div id="elh_view_id_SEG_LAT_Sector" class="view_id_SEG_LAT_Sector"><div class="ewTableHeaderCaption"><?php echo $view_id->SEG_LAT_Sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LAT_Sector"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->SEG_LAT_Sector) ?>',2);"><div id="elh_view_id_SEG_LAT_Sector" class="view_id_SEG_LAT_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->SEG_LAT_Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->SEG_LAT_Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->SEG_LAT_Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->GRA_LONG_Sector->Visible) { // GRA_LONG_Sector ?>
	<?php if ($view_id->SortUrl($view_id->GRA_LONG_Sector) == "") { ?>
		<th data-name="GRA_LONG_Sector"><div id="elh_view_id_GRA_LONG_Sector" class="view_id_GRA_LONG_Sector"><div class="ewTableHeaderCaption"><?php echo $view_id->GRA_LONG_Sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LONG_Sector"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->GRA_LONG_Sector) ?>',2);"><div id="elh_view_id_GRA_LONG_Sector" class="view_id_GRA_LONG_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->GRA_LONG_Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->GRA_LONG_Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->GRA_LONG_Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->MIN_LONG_Sector->Visible) { // MIN_LONG_Sector ?>
	<?php if ($view_id->SortUrl($view_id->MIN_LONG_Sector) == "") { ?>
		<th data-name="MIN_LONG_Sector"><div id="elh_view_id_MIN_LONG_Sector" class="view_id_MIN_LONG_Sector"><div class="ewTableHeaderCaption"><?php echo $view_id->MIN_LONG_Sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LONG_Sector"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->MIN_LONG_Sector) ?>',2);"><div id="elh_view_id_MIN_LONG_Sector" class="view_id_MIN_LONG_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->MIN_LONG_Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->MIN_LONG_Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->MIN_LONG_Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->SEG_LONG_Sector->Visible) { // SEG_LONG_Sector ?>
	<?php if ($view_id->SortUrl($view_id->SEG_LONG_Sector) == "") { ?>
		<th data-name="SEG_LONG_Sector"><div id="elh_view_id_SEG_LONG_Sector" class="view_id_SEG_LONG_Sector"><div class="ewTableHeaderCaption"><?php echo $view_id->SEG_LONG_Sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LONG_Sector"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->SEG_LONG_Sector) ?>',2);"><div id="elh_view_id_SEG_LONG_Sector" class="view_id_SEG_LONG_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->SEG_LONG_Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->SEG_LONG_Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->SEG_LONG_Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Ini_Jorna->Visible) { // Ini_Jorna ?>
	<?php if ($view_id->SortUrl($view_id->Ini_Jorna) == "") { ?>
		<th data-name="Ini_Jorna"><div id="elh_view_id_Ini_Jorna" class="view_id_Ini_Jorna"><div class="ewTableHeaderCaption"><?php echo $view_id->Ini_Jorna->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Ini_Jorna"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Ini_Jorna) ?>',2);"><div id="elh_view_id_Ini_Jorna" class="view_id_Ini_Jorna">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Ini_Jorna->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Ini_Jorna->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Ini_Jorna->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Fin_Jorna->Visible) { // Fin_Jorna ?>
	<?php if ($view_id->SortUrl($view_id->Fin_Jorna) == "") { ?>
		<th data-name="Fin_Jorna"><div id="elh_view_id_Fin_Jorna" class="view_id_Fin_Jorna"><div class="ewTableHeaderCaption"><?php echo $view_id->Fin_Jorna->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fin_Jorna"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Fin_Jorna) ?>',2);"><div id="elh_view_id_Fin_Jorna" class="view_id_Fin_Jorna">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Fin_Jorna->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Fin_Jorna->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Fin_Jorna->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Situ_Especial->Visible) { // Situ_Especial ?>
	<?php if ($view_id->SortUrl($view_id->Situ_Especial) == "") { ?>
		<th data-name="Situ_Especial"><div id="elh_view_id_Situ_Especial" class="view_id_Situ_Especial"><div class="ewTableHeaderCaption"><?php echo $view_id->Situ_Especial->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Situ_Especial"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Situ_Especial) ?>',2);"><div id="elh_view_id_Situ_Especial" class="view_id_Situ_Especial">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Situ_Especial->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Situ_Especial->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Situ_Especial->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Adm_GME->Visible) { // Adm_GME ?>
	<?php if ($view_id->SortUrl($view_id->Adm_GME) == "") { ?>
		<th data-name="Adm_GME"><div id="elh_view_id_Adm_GME" class="view_id_Adm_GME"><div class="ewTableHeaderCaption"><?php echo $view_id->Adm_GME->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Adm_GME"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Adm_GME) ?>',2);"><div id="elh_view_id_Adm_GME" class="view_id_Adm_GME">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Adm_GME->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Adm_GME->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Adm_GME->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Abastecimiento->Visible) { // 1_Abastecimiento ?>
	<?php if ($view_id->SortUrl($view_id->_1_Abastecimiento) == "") { ?>
		<th data-name="_1_Abastecimiento"><div id="elh_view_id__1_Abastecimiento" class="view_id__1_Abastecimiento"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Abastecimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Abastecimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Abastecimiento) ?>',2);"><div id="elh_view_id__1_Abastecimiento" class="view_id__1_Abastecimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Abastecimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Abastecimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Abastecimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Acompanamiento_firma_GME->Visible) { // 1_Acompanamiento_firma_GME ?>
	<?php if ($view_id->SortUrl($view_id->_1_Acompanamiento_firma_GME) == "") { ?>
		<th data-name="_1_Acompanamiento_firma_GME"><div id="elh_view_id__1_Acompanamiento_firma_GME" class="view_id__1_Acompanamiento_firma_GME"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Acompanamiento_firma_GME->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Acompanamiento_firma_GME"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Acompanamiento_firma_GME) ?>',2);"><div id="elh_view_id__1_Acompanamiento_firma_GME" class="view_id__1_Acompanamiento_firma_GME">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Acompanamiento_firma_GME->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Acompanamiento_firma_GME->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Acompanamiento_firma_GME->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Apoyo_zonal_sin_punto_asignado->Visible) { // 1_Apoyo_zonal_sin_punto_asignado ?>
	<?php if ($view_id->SortUrl($view_id->_1_Apoyo_zonal_sin_punto_asignado) == "") { ?>
		<th data-name="_1_Apoyo_zonal_sin_punto_asignado"><div id="elh_view_id__1_Apoyo_zonal_sin_punto_asignado" class="view_id__1_Apoyo_zonal_sin_punto_asignado"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Apoyo_zonal_sin_punto_asignado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Apoyo_zonal_sin_punto_asignado) ?>',2);"><div id="elh_view_id__1_Apoyo_zonal_sin_punto_asignado" class="view_id__1_Apoyo_zonal_sin_punto_asignado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Apoyo_zonal_sin_punto_asignado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Apoyo_zonal_sin_punto_asignado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Descanso_en_dia_habil->Visible) { // 1_Descanso_en_dia_habil ?>
	<?php if ($view_id->SortUrl($view_id->_1_Descanso_en_dia_habil) == "") { ?>
		<th data-name="_1_Descanso_en_dia_habil"><div id="elh_view_id__1_Descanso_en_dia_habil" class="view_id__1_Descanso_en_dia_habil"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Descanso_en_dia_habil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Descanso_en_dia_habil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Descanso_en_dia_habil) ?>',2);"><div id="elh_view_id__1_Descanso_en_dia_habil" class="view_id__1_Descanso_en_dia_habil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Descanso_en_dia_habil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Descanso_en_dia_habil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Descanso_en_dia_habil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Descanso_festivo_dominical->Visible) { // 1_Descanso_festivo_dominical ?>
	<?php if ($view_id->SortUrl($view_id->_1_Descanso_festivo_dominical) == "") { ?>
		<th data-name="_1_Descanso_festivo_dominical"><div id="elh_view_id__1_Descanso_festivo_dominical" class="view_id__1_Descanso_festivo_dominical"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Descanso_festivo_dominical->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Descanso_festivo_dominical"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Descanso_festivo_dominical) ?>',2);"><div id="elh_view_id__1_Descanso_festivo_dominical" class="view_id__1_Descanso_festivo_dominical">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Descanso_festivo_dominical->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Descanso_festivo_dominical->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Descanso_festivo_dominical->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Dia_compensatorio->Visible) { // 1_Dia_compensatorio ?>
	<?php if ($view_id->SortUrl($view_id->_1_Dia_compensatorio) == "") { ?>
		<th data-name="_1_Dia_compensatorio"><div id="elh_view_id__1_Dia_compensatorio" class="view_id__1_Dia_compensatorio"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Dia_compensatorio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Dia_compensatorio"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Dia_compensatorio) ?>',2);"><div id="elh_view_id__1_Dia_compensatorio" class="view_id__1_Dia_compensatorio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Dia_compensatorio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Dia_compensatorio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Dia_compensatorio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Erradicacion_en_dia_festivo->Visible) { // 1_Erradicacion_en_dia_festivo ?>
	<?php if ($view_id->SortUrl($view_id->_1_Erradicacion_en_dia_festivo) == "") { ?>
		<th data-name="_1_Erradicacion_en_dia_festivo"><div id="elh_view_id__1_Erradicacion_en_dia_festivo" class="view_id__1_Erradicacion_en_dia_festivo"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Erradicacion_en_dia_festivo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Erradicacion_en_dia_festivo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Erradicacion_en_dia_festivo) ?>',2);"><div id="elh_view_id__1_Erradicacion_en_dia_festivo" class="view_id__1_Erradicacion_en_dia_festivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Erradicacion_en_dia_festivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Erradicacion_en_dia_festivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Erradicacion_en_dia_festivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Espera_helicoptero_Helistar->Visible) { // 1_Espera_helicoptero_Helistar ?>
	<?php if ($view_id->SortUrl($view_id->_1_Espera_helicoptero_Helistar) == "") { ?>
		<th data-name="_1_Espera_helicoptero_Helistar"><div id="elh_view_id__1_Espera_helicoptero_Helistar" class="view_id__1_Espera_helicoptero_Helistar"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Espera_helicoptero_Helistar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Espera_helicoptero_Helistar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Espera_helicoptero_Helistar) ?>',2);"><div id="elh_view_id__1_Espera_helicoptero_Helistar" class="view_id__1_Espera_helicoptero_Helistar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Espera_helicoptero_Helistar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Espera_helicoptero_Helistar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Espera_helicoptero_Helistar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Extraccion->Visible) { // 1_Extraccion ?>
	<?php if ($view_id->SortUrl($view_id->_1_Extraccion) == "") { ?>
		<th data-name="_1_Extraccion"><div id="elh_view_id__1_Extraccion" class="view_id__1_Extraccion"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Extraccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Extraccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Extraccion) ?>',2);"><div id="elh_view_id__1_Extraccion" class="view_id__1_Extraccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Extraccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Extraccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Extraccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Firma_contrato_GME->Visible) { // 1_Firma_contrato_GME ?>
	<?php if ($view_id->SortUrl($view_id->_1_Firma_contrato_GME) == "") { ?>
		<th data-name="_1_Firma_contrato_GME"><div id="elh_view_id__1_Firma_contrato_GME" class="view_id__1_Firma_contrato_GME"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Firma_contrato_GME->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Firma_contrato_GME"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Firma_contrato_GME) ?>',2);"><div id="elh_view_id__1_Firma_contrato_GME" class="view_id__1_Firma_contrato_GME">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Firma_contrato_GME->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Firma_contrato_GME->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Firma_contrato_GME->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Induccion_Apoyo_Zonal->Visible) { // 1_Induccion_Apoyo_Zonal ?>
	<?php if ($view_id->SortUrl($view_id->_1_Induccion_Apoyo_Zonal) == "") { ?>
		<th data-name="_1_Induccion_Apoyo_Zonal"><div id="elh_view_id__1_Induccion_Apoyo_Zonal" class="view_id__1_Induccion_Apoyo_Zonal"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Induccion_Apoyo_Zonal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Induccion_Apoyo_Zonal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Induccion_Apoyo_Zonal) ?>',2);"><div id="elh_view_id__1_Induccion_Apoyo_Zonal" class="view_id__1_Induccion_Apoyo_Zonal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Induccion_Apoyo_Zonal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Induccion_Apoyo_Zonal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Induccion_Apoyo_Zonal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Insercion->Visible) { // 1_Insercion ?>
	<?php if ($view_id->SortUrl($view_id->_1_Insercion) == "") { ?>
		<th data-name="_1_Insercion"><div id="elh_view_id__1_Insercion" class="view_id__1_Insercion"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Insercion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Insercion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Insercion) ?>',2);"><div id="elh_view_id__1_Insercion" class="view_id__1_Insercion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Insercion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Insercion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Insercion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Visible) { // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase ?>
	<?php if ($view_id->SortUrl($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) == "") { ?>
		<th data-name="_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"><div id="elh_view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase) ?>',2);"><div id="elh_view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Novedad_apoyo_zonal->Visible) { // 1_Novedad_apoyo_zonal ?>
	<?php if ($view_id->SortUrl($view_id->_1_Novedad_apoyo_zonal) == "") { ?>
		<th data-name="_1_Novedad_apoyo_zonal"><div id="elh_view_id__1_Novedad_apoyo_zonal" class="view_id__1_Novedad_apoyo_zonal"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Novedad_apoyo_zonal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Novedad_apoyo_zonal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Novedad_apoyo_zonal) ?>',2);"><div id="elh_view_id__1_Novedad_apoyo_zonal" class="view_id__1_Novedad_apoyo_zonal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Novedad_apoyo_zonal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Novedad_apoyo_zonal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Novedad_apoyo_zonal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Novedad_enfermero->Visible) { // 1_Novedad_enfermero ?>
	<?php if ($view_id->SortUrl($view_id->_1_Novedad_enfermero) == "") { ?>
		<th data-name="_1_Novedad_enfermero"><div id="elh_view_id__1_Novedad_enfermero" class="view_id__1_Novedad_enfermero"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Novedad_enfermero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Novedad_enfermero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Novedad_enfermero) ?>',2);"><div id="elh_view_id__1_Novedad_enfermero" class="view_id__1_Novedad_enfermero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Novedad_enfermero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Novedad_enfermero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Novedad_enfermero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Punto_fuera_del_area_de_erradicacion->Visible) { // 1_Punto_fuera_del_area_de_erradicacion ?>
	<?php if ($view_id->SortUrl($view_id->_1_Punto_fuera_del_area_de_erradicacion) == "") { ?>
		<th data-name="_1_Punto_fuera_del_area_de_erradicacion"><div id="elh_view_id__1_Punto_fuera_del_area_de_erradicacion" class="view_id__1_Punto_fuera_del_area_de_erradicacion"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Punto_fuera_del_area_de_erradicacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Punto_fuera_del_area_de_erradicacion) ?>',2);"><div id="elh_view_id__1_Punto_fuera_del_area_de_erradicacion" class="view_id__1_Punto_fuera_del_area_de_erradicacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Punto_fuera_del_area_de_erradicacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Punto_fuera_del_area_de_erradicacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Transporte_bus->Visible) { // 1_Transporte_bus ?>
	<?php if ($view_id->SortUrl($view_id->_1_Transporte_bus) == "") { ?>
		<th data-name="_1_Transporte_bus"><div id="elh_view_id__1_Transporte_bus" class="view_id__1_Transporte_bus"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Transporte_bus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Transporte_bus"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Transporte_bus) ?>',2);"><div id="elh_view_id__1_Transporte_bus" class="view_id__1_Transporte_bus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Transporte_bus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Transporte_bus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Transporte_bus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Traslado_apoyo_zonal->Visible) { // 1_Traslado_apoyo_zonal ?>
	<?php if ($view_id->SortUrl($view_id->_1_Traslado_apoyo_zonal) == "") { ?>
		<th data-name="_1_Traslado_apoyo_zonal"><div id="elh_view_id__1_Traslado_apoyo_zonal" class="view_id__1_Traslado_apoyo_zonal"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Traslado_apoyo_zonal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Traslado_apoyo_zonal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Traslado_apoyo_zonal) ?>',2);"><div id="elh_view_id__1_Traslado_apoyo_zonal" class="view_id__1_Traslado_apoyo_zonal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Traslado_apoyo_zonal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Traslado_apoyo_zonal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Traslado_apoyo_zonal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_1_Traslado_area_vivac->Visible) { // 1_Traslado_area_vivac ?>
	<?php if ($view_id->SortUrl($view_id->_1_Traslado_area_vivac) == "") { ?>
		<th data-name="_1_Traslado_area_vivac"><div id="elh_view_id__1_Traslado_area_vivac" class="view_id__1_Traslado_area_vivac"><div class="ewTableHeaderCaption"><?php echo $view_id->_1_Traslado_area_vivac->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_1_Traslado_area_vivac"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_1_Traslado_area_vivac) ?>',2);"><div id="elh_view_id__1_Traslado_area_vivac" class="view_id__1_Traslado_area_vivac">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_1_Traslado_area_vivac->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_1_Traslado_area_vivac->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_1_Traslado_area_vivac->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Adm_Fuerza->Visible) { // Adm_Fuerza ?>
	<?php if ($view_id->SortUrl($view_id->Adm_Fuerza) == "") { ?>
		<th data-name="Adm_Fuerza"><div id="elh_view_id_Adm_Fuerza" class="view_id_Adm_Fuerza"><div class="ewTableHeaderCaption"><?php echo $view_id->Adm_Fuerza->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Adm_Fuerza"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Adm_Fuerza) ?>',2);"><div id="elh_view_id_Adm_Fuerza" class="view_id_Adm_Fuerza">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Adm_Fuerza->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Adm_Fuerza->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Adm_Fuerza->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->Visible) { // 2_A_la_espera_definicion_nuevo_punto_FP ?>
	<?php if ($view_id->SortUrl($view_id->_2_A_la_espera_definicion_nuevo_punto_FP) == "") { ?>
		<th data-name="_2_A_la_espera_definicion_nuevo_punto_FP"><div id="elh_view_id__2_A_la_espera_definicion_nuevo_punto_FP" class="view_id__2_A_la_espera_definicion_nuevo_punto_FP"><div class="ewTableHeaderCaption"><?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_A_la_espera_definicion_nuevo_punto_FP"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_2_A_la_espera_definicion_nuevo_punto_FP) ?>',2);"><div id="elh_view_id__2_A_la_espera_definicion_nuevo_punto_FP" class="view_id__2_A_la_espera_definicion_nuevo_punto_FP">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_2_Espera_helicoptero_FP_de_seguridad->Visible) { // 2_Espera_helicoptero_FP_de_seguridad ?>
	<?php if ($view_id->SortUrl($view_id->_2_Espera_helicoptero_FP_de_seguridad) == "") { ?>
		<th data-name="_2_Espera_helicoptero_FP_de_seguridad"><div id="elh_view_id__2_Espera_helicoptero_FP_de_seguridad" class="view_id__2_Espera_helicoptero_FP_de_seguridad"><div class="ewTableHeaderCaption"><?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Espera_helicoptero_FP_de_seguridad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_2_Espera_helicoptero_FP_de_seguridad) ?>',2);"><div id="elh_view_id__2_Espera_helicoptero_FP_de_seguridad" class="view_id__2_Espera_helicoptero_FP_de_seguridad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_2_Espera_helicoptero_FP_de_seguridad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_2_Espera_helicoptero_FP_de_seguridad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_2_Espera_helicoptero_FP_que_abastece->Visible) { // 2_Espera_helicoptero_FP_que_abastece ?>
	<?php if ($view_id->SortUrl($view_id->_2_Espera_helicoptero_FP_que_abastece) == "") { ?>
		<th data-name="_2_Espera_helicoptero_FP_que_abastece"><div id="elh_view_id__2_Espera_helicoptero_FP_que_abastece" class="view_id__2_Espera_helicoptero_FP_que_abastece"><div class="ewTableHeaderCaption"><?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Espera_helicoptero_FP_que_abastece"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_2_Espera_helicoptero_FP_que_abastece) ?>',2);"><div id="elh_view_id__2_Espera_helicoptero_FP_que_abastece" class="view_id__2_Espera_helicoptero_FP_que_abastece">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_2_Espera_helicoptero_FP_que_abastece->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_2_Espera_helicoptero_FP_que_abastece->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_2_Induccion_FP->Visible) { // 2_Induccion_FP ?>
	<?php if ($view_id->SortUrl($view_id->_2_Induccion_FP) == "") { ?>
		<th data-name="_2_Induccion_FP"><div id="elh_view_id__2_Induccion_FP" class="view_id__2_Induccion_FP"><div class="ewTableHeaderCaption"><?php echo $view_id->_2_Induccion_FP->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Induccion_FP"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_2_Induccion_FP) ?>',2);"><div id="elh_view_id__2_Induccion_FP" class="view_id__2_Induccion_FP">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_2_Induccion_FP->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_2_Induccion_FP->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_2_Induccion_FP->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->Visible) { // 2_Novedad_canino_o_del_grupo_de_deteccion ?>
	<?php if ($view_id->SortUrl($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion) == "") { ?>
		<th data-name="_2_Novedad_canino_o_del_grupo_de_deteccion"><div id="elh_view_id__2_Novedad_canino_o_del_grupo_de_deteccion" class="view_id__2_Novedad_canino_o_del_grupo_de_deteccion"><div class="ewTableHeaderCaption"><?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Novedad_canino_o_del_grupo_de_deteccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion) ?>',2);"><div id="elh_view_id__2_Novedad_canino_o_del_grupo_de_deteccion" class="view_id__2_Novedad_canino_o_del_grupo_de_deteccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_2_Problemas_fuerza_publica->Visible) { // 2_Problemas_fuerza_publica ?>
	<?php if ($view_id->SortUrl($view_id->_2_Problemas_fuerza_publica) == "") { ?>
		<th data-name="_2_Problemas_fuerza_publica"><div id="elh_view_id__2_Problemas_fuerza_publica" class="view_id__2_Problemas_fuerza_publica"><div class="ewTableHeaderCaption"><?php echo $view_id->_2_Problemas_fuerza_publica->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Problemas_fuerza_publica"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_2_Problemas_fuerza_publica) ?>',2);"><div id="elh_view_id__2_Problemas_fuerza_publica" class="view_id__2_Problemas_fuerza_publica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_2_Problemas_fuerza_publica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_2_Problemas_fuerza_publica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_2_Problemas_fuerza_publica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_2_Sin_seguridad->Visible) { // 2_Sin_seguridad ?>
	<?php if ($view_id->SortUrl($view_id->_2_Sin_seguridad) == "") { ?>
		<th data-name="_2_Sin_seguridad"><div id="elh_view_id__2_Sin_seguridad" class="view_id__2_Sin_seguridad"><div class="ewTableHeaderCaption"><?php echo $view_id->_2_Sin_seguridad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_2_Sin_seguridad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_2_Sin_seguridad) ?>',2);"><div id="elh_view_id__2_Sin_seguridad" class="view_id__2_Sin_seguridad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_2_Sin_seguridad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_2_Sin_seguridad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_2_Sin_seguridad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Sit_Seguridad->Visible) { // Sit_Seguridad ?>
	<?php if ($view_id->SortUrl($view_id->Sit_Seguridad) == "") { ?>
		<th data-name="Sit_Seguridad"><div id="elh_view_id_Sit_Seguridad" class="view_id_Sit_Seguridad"><div class="ewTableHeaderCaption"><?php echo $view_id->Sit_Seguridad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sit_Seguridad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Sit_Seguridad) ?>',2);"><div id="elh_view_id_Sit_Seguridad" class="view_id_Sit_Seguridad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Sit_Seguridad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Sit_Seguridad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Sit_Seguridad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_AEI_controlado->Visible) { // 3_AEI_controlado ?>
	<?php if ($view_id->SortUrl($view_id->_3_AEI_controlado) == "") { ?>
		<th data-name="_3_AEI_controlado"><div id="elh_view_id__3_AEI_controlado" class="view_id__3_AEI_controlado"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_AEI_controlado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_AEI_controlado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_AEI_controlado) ?>',2);"><div id="elh_view_id__3_AEI_controlado" class="view_id__3_AEI_controlado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_AEI_controlado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_AEI_controlado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_AEI_controlado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_AEI_no_controlado->Visible) { // 3_AEI_no_controlado ?>
	<?php if ($view_id->SortUrl($view_id->_3_AEI_no_controlado) == "") { ?>
		<th data-name="_3_AEI_no_controlado"><div id="elh_view_id__3_AEI_no_controlado" class="view_id__3_AEI_no_controlado"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_AEI_no_controlado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_AEI_no_controlado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_AEI_no_controlado) ?>',2);"><div id="elh_view_id__3_AEI_no_controlado" class="view_id__3_AEI_no_controlado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_AEI_no_controlado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_AEI_no_controlado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_AEI_no_controlado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_Bloqueo_parcial_de_la_comunidad->Visible) { // 3_Bloqueo_parcial_de_la_comunidad ?>
	<?php if ($view_id->SortUrl($view_id->_3_Bloqueo_parcial_de_la_comunidad) == "") { ?>
		<th data-name="_3_Bloqueo_parcial_de_la_comunidad"><div id="elh_view_id__3_Bloqueo_parcial_de_la_comunidad" class="view_id__3_Bloqueo_parcial_de_la_comunidad"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Bloqueo_parcial_de_la_comunidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_Bloqueo_parcial_de_la_comunidad) ?>',2);"><div id="elh_view_id__3_Bloqueo_parcial_de_la_comunidad" class="view_id__3_Bloqueo_parcial_de_la_comunidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_Bloqueo_parcial_de_la_comunidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_Bloqueo_parcial_de_la_comunidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_Bloqueo_total_de_la_comunidad->Visible) { // 3_Bloqueo_total_de_la_comunidad ?>
	<?php if ($view_id->SortUrl($view_id->_3_Bloqueo_total_de_la_comunidad) == "") { ?>
		<th data-name="_3_Bloqueo_total_de_la_comunidad"><div id="elh_view_id__3_Bloqueo_total_de_la_comunidad" class="view_id__3_Bloqueo_total_de_la_comunidad"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Bloqueo_total_de_la_comunidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_Bloqueo_total_de_la_comunidad) ?>',2);"><div id="elh_view_id__3_Bloqueo_total_de_la_comunidad" class="view_id__3_Bloqueo_total_de_la_comunidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_Bloqueo_total_de_la_comunidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_Bloqueo_total_de_la_comunidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_Combate->Visible) { // 3_Combate ?>
	<?php if ($view_id->SortUrl($view_id->_3_Combate) == "") { ?>
		<th data-name="_3_Combate"><div id="elh_view_id__3_Combate" class="view_id__3_Combate"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_Combate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Combate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_Combate) ?>',2);"><div id="elh_view_id__3_Combate" class="view_id__3_Combate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_Combate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_Combate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_Combate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_Hostigamiento->Visible) { // 3_Hostigamiento ?>
	<?php if ($view_id->SortUrl($view_id->_3_Hostigamiento) == "") { ?>
		<th data-name="_3_Hostigamiento"><div id="elh_view_id__3_Hostigamiento" class="view_id__3_Hostigamiento"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_Hostigamiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Hostigamiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_Hostigamiento) ?>',2);"><div id="elh_view_id__3_Hostigamiento" class="view_id__3_Hostigamiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_Hostigamiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_Hostigamiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_Hostigamiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_MAP_Controlada->Visible) { // 3_MAP_Controlada ?>
	<?php if ($view_id->SortUrl($view_id->_3_MAP_Controlada) == "") { ?>
		<th data-name="_3_MAP_Controlada"><div id="elh_view_id__3_MAP_Controlada" class="view_id__3_MAP_Controlada"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_MAP_Controlada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_MAP_Controlada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_MAP_Controlada) ?>',2);"><div id="elh_view_id__3_MAP_Controlada" class="view_id__3_MAP_Controlada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_MAP_Controlada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_MAP_Controlada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_MAP_Controlada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_MAP_No_controlada->Visible) { // 3_MAP_No_controlada ?>
	<?php if ($view_id->SortUrl($view_id->_3_MAP_No_controlada) == "") { ?>
		<th data-name="_3_MAP_No_controlada"><div id="elh_view_id__3_MAP_No_controlada" class="view_id__3_MAP_No_controlada"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_MAP_No_controlada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_MAP_No_controlada"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_MAP_No_controlada) ?>',2);"><div id="elh_view_id__3_MAP_No_controlada" class="view_id__3_MAP_No_controlada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_MAP_No_controlada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_MAP_No_controlada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_MAP_No_controlada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_MUSE->Visible) { // 3_MUSE ?>
	<?php if ($view_id->SortUrl($view_id->_3_MUSE) == "") { ?>
		<th data-name="_3_MUSE"><div id="elh_view_id__3_MUSE" class="view_id__3_MUSE"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_MUSE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_MUSE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_MUSE) ?>',2);"><div id="elh_view_id__3_MUSE" class="view_id__3_MUSE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_MUSE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_MUSE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_MUSE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_3_Operaciones_de_seguridad->Visible) { // 3_Operaciones_de_seguridad ?>
	<?php if ($view_id->SortUrl($view_id->_3_Operaciones_de_seguridad) == "") { ?>
		<th data-name="_3_Operaciones_de_seguridad"><div id="elh_view_id__3_Operaciones_de_seguridad" class="view_id__3_Operaciones_de_seguridad"><div class="ewTableHeaderCaption"><?php echo $view_id->_3_Operaciones_de_seguridad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_3_Operaciones_de_seguridad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_3_Operaciones_de_seguridad) ?>',2);"><div id="elh_view_id__3_Operaciones_de_seguridad" class="view_id__3_Operaciones_de_seguridad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_3_Operaciones_de_seguridad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_3_Operaciones_de_seguridad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_3_Operaciones_de_seguridad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->LATITUD_segurid->Visible) { // LATITUD_segurid ?>
	<?php if ($view_id->SortUrl($view_id->LATITUD_segurid) == "") { ?>
		<th data-name="LATITUD_segurid"><div id="elh_view_id_LATITUD_segurid" class="view_id_LATITUD_segurid"><div class="ewTableHeaderCaption"><?php echo $view_id->LATITUD_segurid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="LATITUD_segurid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->LATITUD_segurid) ?>',2);"><div id="elh_view_id_LATITUD_segurid" class="view_id_LATITUD_segurid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->LATITUD_segurid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->LATITUD_segurid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->LATITUD_segurid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->GRA_LAT_segurid->Visible) { // GRA_LAT_segurid ?>
	<?php if ($view_id->SortUrl($view_id->GRA_LAT_segurid) == "") { ?>
		<th data-name="GRA_LAT_segurid"><div id="elh_view_id_GRA_LAT_segurid" class="view_id_GRA_LAT_segurid"><div class="ewTableHeaderCaption"><?php echo $view_id->GRA_LAT_segurid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LAT_segurid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->GRA_LAT_segurid) ?>',2);"><div id="elh_view_id_GRA_LAT_segurid" class="view_id_GRA_LAT_segurid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->GRA_LAT_segurid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->GRA_LAT_segurid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->GRA_LAT_segurid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->MIN_LAT_segurid->Visible) { // MIN_LAT_segurid ?>
	<?php if ($view_id->SortUrl($view_id->MIN_LAT_segurid) == "") { ?>
		<th data-name="MIN_LAT_segurid"><div id="elh_view_id_MIN_LAT_segurid" class="view_id_MIN_LAT_segurid"><div class="ewTableHeaderCaption"><?php echo $view_id->MIN_LAT_segurid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LAT_segurid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->MIN_LAT_segurid) ?>',2);"><div id="elh_view_id_MIN_LAT_segurid" class="view_id_MIN_LAT_segurid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->MIN_LAT_segurid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->MIN_LAT_segurid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->MIN_LAT_segurid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->SEG_LAT_segurid->Visible) { // SEG_LAT_segurid ?>
	<?php if ($view_id->SortUrl($view_id->SEG_LAT_segurid) == "") { ?>
		<th data-name="SEG_LAT_segurid"><div id="elh_view_id_SEG_LAT_segurid" class="view_id_SEG_LAT_segurid"><div class="ewTableHeaderCaption"><?php echo $view_id->SEG_LAT_segurid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LAT_segurid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->SEG_LAT_segurid) ?>',2);"><div id="elh_view_id_SEG_LAT_segurid" class="view_id_SEG_LAT_segurid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->SEG_LAT_segurid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->SEG_LAT_segurid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->SEG_LAT_segurid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->GRA_LONG_seguri->Visible) { // GRA_LONG_seguri ?>
	<?php if ($view_id->SortUrl($view_id->GRA_LONG_seguri) == "") { ?>
		<th data-name="GRA_LONG_seguri"><div id="elh_view_id_GRA_LONG_seguri" class="view_id_GRA_LONG_seguri"><div class="ewTableHeaderCaption"><?php echo $view_id->GRA_LONG_seguri->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="GRA_LONG_seguri"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->GRA_LONG_seguri) ?>',2);"><div id="elh_view_id_GRA_LONG_seguri" class="view_id_GRA_LONG_seguri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->GRA_LONG_seguri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->GRA_LONG_seguri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->GRA_LONG_seguri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->MIN_LONG_seguri->Visible) { // MIN_LONG_seguri ?>
	<?php if ($view_id->SortUrl($view_id->MIN_LONG_seguri) == "") { ?>
		<th data-name="MIN_LONG_seguri"><div id="elh_view_id_MIN_LONG_seguri" class="view_id_MIN_LONG_seguri"><div class="ewTableHeaderCaption"><?php echo $view_id->MIN_LONG_seguri->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MIN_LONG_seguri"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->MIN_LONG_seguri) ?>',2);"><div id="elh_view_id_MIN_LONG_seguri" class="view_id_MIN_LONG_seguri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->MIN_LONG_seguri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->MIN_LONG_seguri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->MIN_LONG_seguri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->SEG_LONG_seguri->Visible) { // SEG_LONG_seguri ?>
	<?php if ($view_id->SortUrl($view_id->SEG_LONG_seguri) == "") { ?>
		<th data-name="SEG_LONG_seguri"><div id="elh_view_id_SEG_LONG_seguri" class="view_id_SEG_LONG_seguri"><div class="ewTableHeaderCaption"><?php echo $view_id->SEG_LONG_seguri->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SEG_LONG_seguri"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->SEG_LONG_seguri) ?>',2);"><div id="elh_view_id_SEG_LONG_seguri" class="view_id_SEG_LONG_seguri">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->SEG_LONG_seguri->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->SEG_LONG_seguri->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->SEG_LONG_seguri->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Novedad->Visible) { // Novedad ?>
	<?php if ($view_id->SortUrl($view_id->Novedad) == "") { ?>
		<th data-name="Novedad"><div id="elh_view_id_Novedad" class="view_id_Novedad"><div class="ewTableHeaderCaption"><?php echo $view_id->Novedad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Novedad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Novedad) ?>',2);"><div id="elh_view_id_Novedad" class="view_id_Novedad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Novedad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Novedad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Novedad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_4_Epidemia->Visible) { // 4_Epidemia ?>
	<?php if ($view_id->SortUrl($view_id->_4_Epidemia) == "") { ?>
		<th data-name="_4_Epidemia"><div id="elh_view_id__4_Epidemia" class="view_id__4_Epidemia"><div class="ewTableHeaderCaption"><?php echo $view_id->_4_Epidemia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Epidemia"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_4_Epidemia) ?>',2);"><div id="elh_view_id__4_Epidemia" class="view_id__4_Epidemia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_4_Epidemia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_4_Epidemia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_4_Epidemia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_4_Novedad_climatologica->Visible) { // 4_Novedad_climatologica ?>
	<?php if ($view_id->SortUrl($view_id->_4_Novedad_climatologica) == "") { ?>
		<th data-name="_4_Novedad_climatologica"><div id="elh_view_id__4_Novedad_climatologica" class="view_id__4_Novedad_climatologica"><div class="ewTableHeaderCaption"><?php echo $view_id->_4_Novedad_climatologica->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Novedad_climatologica"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_4_Novedad_climatologica) ?>',2);"><div id="elh_view_id__4_Novedad_climatologica" class="view_id__4_Novedad_climatologica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_4_Novedad_climatologica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_4_Novedad_climatologica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_4_Novedad_climatologica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_4_Registro_de_cultivos->Visible) { // 4_Registro_de_cultivos ?>
	<?php if ($view_id->SortUrl($view_id->_4_Registro_de_cultivos) == "") { ?>
		<th data-name="_4_Registro_de_cultivos"><div id="elh_view_id__4_Registro_de_cultivos" class="view_id__4_Registro_de_cultivos"><div class="ewTableHeaderCaption"><?php echo $view_id->_4_Registro_de_cultivos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Registro_de_cultivos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_4_Registro_de_cultivos) ?>',2);"><div id="elh_view_id__4_Registro_de_cultivos" class="view_id__4_Registro_de_cultivos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_4_Registro_de_cultivos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_4_Registro_de_cultivos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_4_Registro_de_cultivos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_4_Zona_con_cultivos_muy_dispersos->Visible) { // 4_Zona_con_cultivos_muy_dispersos ?>
	<?php if ($view_id->SortUrl($view_id->_4_Zona_con_cultivos_muy_dispersos) == "") { ?>
		<th data-name="_4_Zona_con_cultivos_muy_dispersos"><div id="elh_view_id__4_Zona_con_cultivos_muy_dispersos" class="view_id__4_Zona_con_cultivos_muy_dispersos"><div class="ewTableHeaderCaption"><?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Zona_con_cultivos_muy_dispersos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_4_Zona_con_cultivos_muy_dispersos) ?>',2);"><div id="elh_view_id__4_Zona_con_cultivos_muy_dispersos" class="view_id__4_Zona_con_cultivos_muy_dispersos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_4_Zona_con_cultivos_muy_dispersos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_4_Zona_con_cultivos_muy_dispersos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_4_Zona_de_cruce_de_rios_caudalosos->Visible) { // 4_Zona_de_cruce_de_rios_caudalosos ?>
	<?php if ($view_id->SortUrl($view_id->_4_Zona_de_cruce_de_rios_caudalosos) == "") { ?>
		<th data-name="_4_Zona_de_cruce_de_rios_caudalosos"><div id="elh_view_id__4_Zona_de_cruce_de_rios_caudalosos" class="view_id__4_Zona_de_cruce_de_rios_caudalosos"><div class="ewTableHeaderCaption"><?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Zona_de_cruce_de_rios_caudalosos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_4_Zona_de_cruce_de_rios_caudalosos) ?>',2);"><div id="elh_view_id__4_Zona_de_cruce_de_rios_caudalosos" class="view_id__4_Zona_de_cruce_de_rios_caudalosos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_4_Zona_de_cruce_de_rios_caudalosos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_4_Zona_de_cruce_de_rios_caudalosos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->_4_Zona_sin_cultivos->Visible) { // 4_Zona_sin_cultivos ?>
	<?php if ($view_id->SortUrl($view_id->_4_Zona_sin_cultivos) == "") { ?>
		<th data-name="_4_Zona_sin_cultivos"><div id="elh_view_id__4_Zona_sin_cultivos" class="view_id__4_Zona_sin_cultivos"><div class="ewTableHeaderCaption"><?php echo $view_id->_4_Zona_sin_cultivos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_4_Zona_sin_cultivos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->_4_Zona_sin_cultivos) ?>',2);"><div id="elh_view_id__4_Zona_sin_cultivos" class="view_id__4_Zona_sin_cultivos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->_4_Zona_sin_cultivos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->_4_Zona_sin_cultivos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->_4_Zona_sin_cultivos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Num_Erra_Salen->Visible) { // Num_Erra_Salen ?>
	<?php if ($view_id->SortUrl($view_id->Num_Erra_Salen) == "") { ?>
		<th data-name="Num_Erra_Salen"><div id="elh_view_id_Num_Erra_Salen" class="view_id_Num_Erra_Salen"><div class="ewTableHeaderCaption"><?php echo $view_id->Num_Erra_Salen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Num_Erra_Salen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Num_Erra_Salen) ?>',2);"><div id="elh_view_id_Num_Erra_Salen" class="view_id_Num_Erra_Salen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Num_Erra_Salen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Num_Erra_Salen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Num_Erra_Salen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Num_Erra_Quedan->Visible) { // Num_Erra_Quedan ?>
	<?php if ($view_id->SortUrl($view_id->Num_Erra_Quedan) == "") { ?>
		<th data-name="Num_Erra_Quedan"><div id="elh_view_id_Num_Erra_Quedan" class="view_id_Num_Erra_Quedan"><div class="ewTableHeaderCaption"><?php echo $view_id->Num_Erra_Quedan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Num_Erra_Quedan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Num_Erra_Quedan) ?>',2);"><div id="elh_view_id_Num_Erra_Quedan" class="view_id_Num_Erra_Quedan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Num_Erra_Quedan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Num_Erra_Quedan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Num_Erra_Quedan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->No_ENFERMERO->Visible) { // No_ENFERMERO ?>
	<?php if ($view_id->SortUrl($view_id->No_ENFERMERO) == "") { ?>
		<th data-name="No_ENFERMERO"><div id="elh_view_id_No_ENFERMERO" class="view_id_No_ENFERMERO"><div class="ewTableHeaderCaption"><?php echo $view_id->No_ENFERMERO->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="No_ENFERMERO"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->No_ENFERMERO) ?>',2);"><div id="elh_view_id_No_ENFERMERO" class="view_id_No_ENFERMERO">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->No_ENFERMERO->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->No_ENFERMERO->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->No_ENFERMERO->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->NUM_FP->Visible) { // NUM_FP ?>
	<?php if ($view_id->SortUrl($view_id->NUM_FP) == "") { ?>
		<th data-name="NUM_FP"><div id="elh_view_id_NUM_FP" class="view_id_NUM_FP"><div class="ewTableHeaderCaption"><?php echo $view_id->NUM_FP->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NUM_FP"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->NUM_FP) ?>',2);"><div id="elh_view_id_NUM_FP" class="view_id_NUM_FP">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->NUM_FP->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->NUM_FP->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->NUM_FP->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->NUM_Perso_EVA->Visible) { // NUM_Perso_EVA ?>
	<?php if ($view_id->SortUrl($view_id->NUM_Perso_EVA) == "") { ?>
		<th data-name="NUM_Perso_EVA"><div id="elh_view_id_NUM_Perso_EVA" class="view_id_NUM_Perso_EVA"><div class="ewTableHeaderCaption"><?php echo $view_id->NUM_Perso_EVA->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NUM_Perso_EVA"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->NUM_Perso_EVA) ?>',2);"><div id="elh_view_id_NUM_Perso_EVA" class="view_id_NUM_Perso_EVA">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->NUM_Perso_EVA->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->NUM_Perso_EVA->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->NUM_Perso_EVA->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->NUM_Poli->Visible) { // NUM_Poli ?>
	<?php if ($view_id->SortUrl($view_id->NUM_Poli) == "") { ?>
		<th data-name="NUM_Poli"><div id="elh_view_id_NUM_Poli" class="view_id_NUM_Poli"><div class="ewTableHeaderCaption"><?php echo $view_id->NUM_Poli->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NUM_Poli"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->NUM_Poli) ?>',2);"><div id="elh_view_id_NUM_Poli" class="view_id_NUM_Poli">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->NUM_Poli->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->NUM_Poli->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->NUM_Poli->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->AD1O->Visible) { // AÑO ?>
	<?php if ($view_id->SortUrl($view_id->AD1O) == "") { ?>
		<th data-name="AD1O"><div id="elh_view_id_AD1O" class="view_id_AD1O"><div class="ewTableHeaderCaption"><?php echo $view_id->AD1O->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="AD1O"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->AD1O) ?>',2);"><div id="elh_view_id_AD1O" class="view_id_AD1O">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->AD1O->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->AD1O->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->AD1O->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->FASE->Visible) { // FASE ?>
	<?php if ($view_id->SortUrl($view_id->FASE) == "") { ?>
		<th data-name="FASE"><div id="elh_view_id_FASE" class="view_id_FASE"><div class="ewTableHeaderCaption"><?php echo $view_id->FASE->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FASE"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->FASE) ?>',2);"><div id="elh_view_id_FASE" class="view_id_FASE">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->FASE->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view_id->FASE->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->FASE->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view_id->Modificado->Visible) { // Modificado ?>
	<?php if ($view_id->SortUrl($view_id->Modificado) == "") { ?>
		<th data-name="Modificado"><div id="elh_view_id_Modificado" class="view_id_Modificado"><div class="ewTableHeaderCaption"><?php echo $view_id->Modificado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Modificado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view_id->SortUrl($view_id->Modificado) ?>',2);"><div id="elh_view_id_Modificado" class="view_id_Modificado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view_id->Modificado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view_id->Modificado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view_id->Modificado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view_id_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view_id->ExportAll && $view_id->Export <> "") {
	$view_id_list->StopRec = $view_id_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view_id_list->TotalRecs > $view_id_list->StartRec + $view_id_list->DisplayRecs - 1)
		$view_id_list->StopRec = $view_id_list->StartRec + $view_id_list->DisplayRecs - 1;
	else
		$view_id_list->StopRec = $view_id_list->TotalRecs;
}
$view_id_list->RecCnt = $view_id_list->StartRec - 1;
if ($view_id_list->Recordset && !$view_id_list->Recordset->EOF) {
	$view_id_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $view_id_list->StartRec > 1)
		$view_id_list->Recordset->Move($view_id_list->StartRec - 1);
} elseif (!$view_id->AllowAddDeleteRow && $view_id_list->StopRec == 0) {
	$view_id_list->StopRec = $view_id->GridAddRowCount;
}

// Initialize aggregate
$view_id->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view_id->ResetAttrs();
$view_id_list->RenderRow();
while ($view_id_list->RecCnt < $view_id_list->StopRec) {
	$view_id_list->RecCnt++;
	if (intval($view_id_list->RecCnt) >= intval($view_id_list->StartRec)) {
		$view_id_list->RowCnt++;

		// Set up key count
		$view_id_list->KeyCount = $view_id_list->RowIndex;

		// Init row class and style
		$view_id->ResetAttrs();
		$view_id->CssClass = "";
		if ($view_id->CurrentAction == "gridadd") {
		} else {
			$view_id_list->LoadRowValues($view_id_list->Recordset); // Load row values
		}
		$view_id->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view_id->RowAttrs = array_merge($view_id->RowAttrs, array('data-rowindex'=>$view_id_list->RowCnt, 'id'=>'r' . $view_id_list->RowCnt . '_view_id', 'data-rowtype'=>$view_id->RowType));

		// Render row
		$view_id_list->RenderRow();

		// Render list options
		$view_id_list->RenderListOptions();
?>
	<tr<?php echo $view_id->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view_id_list->ListOptions->Render("body", "left", $view_id_list->RowCnt);
?>
	<?php if ($view_id->llave->Visible) { // llave ?>
		<td data-name="llave"<?php echo $view_id->llave->CellAttributes() ?>>
<span<?php echo $view_id->llave->ViewAttributes() ?>>
<?php echo $view_id->llave->ListViewValue() ?></span>
<a id="<?php echo $view_id_list->PageObjName . "_row_" . $view_id_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view_id->F_Sincron->Visible) { // F_Sincron ?>
		<td data-name="F_Sincron"<?php echo $view_id->F_Sincron->CellAttributes() ?>>
<span<?php echo $view_id->F_Sincron->ViewAttributes() ?>>
<?php echo $view_id->F_Sincron->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->USUARIO->Visible) { // USUARIO ?>
		<td data-name="USUARIO"<?php echo $view_id->USUARIO->CellAttributes() ?>>
<span<?php echo $view_id->USUARIO->ViewAttributes() ?>>
<?php echo $view_id->USUARIO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Cargo_gme->Visible) { // Cargo_gme ?>
		<td data-name="Cargo_gme"<?php echo $view_id->Cargo_gme->CellAttributes() ?>>
<span<?php echo $view_id->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_id->Cargo_gme->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->NOM_PE->Visible) { // NOM_PE ?>
		<td data-name="NOM_PE"<?php echo $view_id->NOM_PE->CellAttributes() ?>>
<span<?php echo $view_id->NOM_PE->ViewAttributes() ?>>
<?php echo $view_id->NOM_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Otro_PE->Visible) { // Otro_PE ?>
		<td data-name="Otro_PE"<?php echo $view_id->Otro_PE->CellAttributes() ?>>
<span<?php echo $view_id->Otro_PE->ViewAttributes() ?>>
<?php echo $view_id->Otro_PE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->NOM_PGE->Visible) { // NOM_PGE ?>
		<td data-name="NOM_PGE"<?php echo $view_id->NOM_PGE->CellAttributes() ?>>
<span<?php echo $view_id->NOM_PGE->ViewAttributes() ?>>
<?php echo $view_id->NOM_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Otro_NOM_PGE->Visible) { // Otro_NOM_PGE ?>
		<td data-name="Otro_NOM_PGE"<?php echo $view_id->Otro_NOM_PGE->CellAttributes() ?>>
<span<?php echo $view_id->Otro_NOM_PGE->ViewAttributes() ?>>
<?php echo $view_id->Otro_NOM_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<td data-name="Otro_CC_PGE"<?php echo $view_id->Otro_CC_PGE->CellAttributes() ?>>
<span<?php echo $view_id->Otro_CC_PGE->ViewAttributes() ?>>
<?php echo $view_id->Otro_CC_PGE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
		<td data-name="TIPO_INFORME"<?php echo $view_id->TIPO_INFORME->CellAttributes() ?>>
<span<?php echo $view_id->TIPO_INFORME->ViewAttributes() ?>>
<?php echo $view_id->TIPO_INFORME->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->FECHA_REPORT->Visible) { // FECHA_REPORT ?>
		<td data-name="FECHA_REPORT"<?php echo $view_id->FECHA_REPORT->CellAttributes() ?>>
<span<?php echo $view_id->FECHA_REPORT->ViewAttributes() ?>>
<?php echo $view_id->FECHA_REPORT->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->DIA->Visible) { // DIA ?>
		<td data-name="DIA"<?php echo $view_id->DIA->CellAttributes() ?>>
<span<?php echo $view_id->DIA->ViewAttributes() ?>>
<?php echo $view_id->DIA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->MES->Visible) { // MES ?>
		<td data-name="MES"<?php echo $view_id->MES->CellAttributes() ?>>
<span<?php echo $view_id->MES->ViewAttributes() ?>>
<?php echo $view_id->MES->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Departamento->Visible) { // Departamento ?>
		<td data-name="Departamento"<?php echo $view_id->Departamento->CellAttributes() ?>>
<span<?php echo $view_id->Departamento->ViewAttributes() ?>>
<?php echo $view_id->Departamento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Muncipio->Visible) { // Muncipio ?>
		<td data-name="Muncipio"<?php echo $view_id->Muncipio->CellAttributes() ?>>
<span<?php echo $view_id->Muncipio->ViewAttributes() ?>>
<?php echo $view_id->Muncipio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->TEMA->Visible) { // TEMA ?>
		<td data-name="TEMA"<?php echo $view_id->TEMA->CellAttributes() ?>>
<span<?php echo $view_id->TEMA->ViewAttributes() ?>>
<?php echo $view_id->TEMA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Otro_Tema->Visible) { // Otro_Tema ?>
		<td data-name="Otro_Tema"<?php echo $view_id->Otro_Tema->CellAttributes() ?>>
<span<?php echo $view_id->Otro_Tema->ViewAttributes() ?>>
<?php echo $view_id->Otro_Tema->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->OBSERVACION->Visible) { // OBSERVACION ?>
		<td data-name="OBSERVACION"<?php echo $view_id->OBSERVACION->CellAttributes() ?>>
<span<?php echo $view_id->OBSERVACION->ViewAttributes() ?>>
<?php echo $view_id->OBSERVACION->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->NOM_VDA->Visible) { // NOM_VDA ?>
		<td data-name="NOM_VDA"<?php echo $view_id->NOM_VDA->CellAttributes() ?>>
<span<?php echo $view_id->NOM_VDA->ViewAttributes() ?>>
<?php echo $view_id->NOM_VDA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Ha_Coca->Visible) { // Ha_Coca ?>
		<td data-name="Ha_Coca"<?php echo $view_id->Ha_Coca->CellAttributes() ?>>
<span<?php echo $view_id->Ha_Coca->ViewAttributes() ?>>
<?php echo $view_id->Ha_Coca->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Ha_Amapola->Visible) { // Ha_Amapola ?>
		<td data-name="Ha_Amapola"<?php echo $view_id->Ha_Amapola->CellAttributes() ?>>
<span<?php echo $view_id->Ha_Amapola->ViewAttributes() ?>>
<?php echo $view_id->Ha_Amapola->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Ha_Marihuana->Visible) { // Ha_Marihuana ?>
		<td data-name="Ha_Marihuana"<?php echo $view_id->Ha_Marihuana->CellAttributes() ?>>
<span<?php echo $view_id->Ha_Marihuana->ViewAttributes() ?>>
<?php echo $view_id->Ha_Marihuana->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->T_erradi->Visible) { // T_erradi ?>
		<td data-name="T_erradi"<?php echo $view_id->T_erradi->CellAttributes() ?>>
<span<?php echo $view_id->T_erradi->ViewAttributes() ?>>
<?php echo $view_id->T_erradi->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->LATITUD_sector->Visible) { // LATITUD_sector ?>
		<td data-name="LATITUD_sector"<?php echo $view_id->LATITUD_sector->CellAttributes() ?>>
<span<?php echo $view_id->LATITUD_sector->ViewAttributes() ?>>
<?php echo $view_id->LATITUD_sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->GRA_LAT_Sector->Visible) { // GRA_LAT_Sector ?>
		<td data-name="GRA_LAT_Sector"<?php echo $view_id->GRA_LAT_Sector->CellAttributes() ?>>
<span<?php echo $view_id->GRA_LAT_Sector->ViewAttributes() ?>>
<?php echo $view_id->GRA_LAT_Sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->MIN_LAT_Sector->Visible) { // MIN_LAT_Sector ?>
		<td data-name="MIN_LAT_Sector"<?php echo $view_id->MIN_LAT_Sector->CellAttributes() ?>>
<span<?php echo $view_id->MIN_LAT_Sector->ViewAttributes() ?>>
<?php echo $view_id->MIN_LAT_Sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->SEG_LAT_Sector->Visible) { // SEG_LAT_Sector ?>
		<td data-name="SEG_LAT_Sector"<?php echo $view_id->SEG_LAT_Sector->CellAttributes() ?>>
<span<?php echo $view_id->SEG_LAT_Sector->ViewAttributes() ?>>
<?php echo $view_id->SEG_LAT_Sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->GRA_LONG_Sector->Visible) { // GRA_LONG_Sector ?>
		<td data-name="GRA_LONG_Sector"<?php echo $view_id->GRA_LONG_Sector->CellAttributes() ?>>
<span<?php echo $view_id->GRA_LONG_Sector->ViewAttributes() ?>>
<?php echo $view_id->GRA_LONG_Sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->MIN_LONG_Sector->Visible) { // MIN_LONG_Sector ?>
		<td data-name="MIN_LONG_Sector"<?php echo $view_id->MIN_LONG_Sector->CellAttributes() ?>>
<span<?php echo $view_id->MIN_LONG_Sector->ViewAttributes() ?>>
<?php echo $view_id->MIN_LONG_Sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->SEG_LONG_Sector->Visible) { // SEG_LONG_Sector ?>
		<td data-name="SEG_LONG_Sector"<?php echo $view_id->SEG_LONG_Sector->CellAttributes() ?>>
<span<?php echo $view_id->SEG_LONG_Sector->ViewAttributes() ?>>
<?php echo $view_id->SEG_LONG_Sector->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Ini_Jorna->Visible) { // Ini_Jorna ?>
		<td data-name="Ini_Jorna"<?php echo $view_id->Ini_Jorna->CellAttributes() ?>>
<span<?php echo $view_id->Ini_Jorna->ViewAttributes() ?>>
<?php echo $view_id->Ini_Jorna->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Fin_Jorna->Visible) { // Fin_Jorna ?>
		<td data-name="Fin_Jorna"<?php echo $view_id->Fin_Jorna->CellAttributes() ?>>
<span<?php echo $view_id->Fin_Jorna->ViewAttributes() ?>>
<?php echo $view_id->Fin_Jorna->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Situ_Especial->Visible) { // Situ_Especial ?>
		<td data-name="Situ_Especial"<?php echo $view_id->Situ_Especial->CellAttributes() ?>>
<span<?php echo $view_id->Situ_Especial->ViewAttributes() ?>>
<?php echo $view_id->Situ_Especial->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Adm_GME->Visible) { // Adm_GME ?>
		<td data-name="Adm_GME"<?php echo $view_id->Adm_GME->CellAttributes() ?>>
<span<?php echo $view_id->Adm_GME->ViewAttributes() ?>>
<?php echo $view_id->Adm_GME->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Abastecimiento->Visible) { // 1_Abastecimiento ?>
		<td data-name="_1_Abastecimiento"<?php echo $view_id->_1_Abastecimiento->CellAttributes() ?>>
<span<?php echo $view_id->_1_Abastecimiento->ViewAttributes() ?>>
<?php echo $view_id->_1_Abastecimiento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Acompanamiento_firma_GME->Visible) { // 1_Acompanamiento_firma_GME ?>
		<td data-name="_1_Acompanamiento_firma_GME"<?php echo $view_id->_1_Acompanamiento_firma_GME->CellAttributes() ?>>
<span<?php echo $view_id->_1_Acompanamiento_firma_GME->ViewAttributes() ?>>
<?php echo $view_id->_1_Acompanamiento_firma_GME->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Apoyo_zonal_sin_punto_asignado->Visible) { // 1_Apoyo_zonal_sin_punto_asignado ?>
		<td data-name="_1_Apoyo_zonal_sin_punto_asignado"<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->CellAttributes() ?>>
<span<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->ViewAttributes() ?>>
<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Descanso_en_dia_habil->Visible) { // 1_Descanso_en_dia_habil ?>
		<td data-name="_1_Descanso_en_dia_habil"<?php echo $view_id->_1_Descanso_en_dia_habil->CellAttributes() ?>>
<span<?php echo $view_id->_1_Descanso_en_dia_habil->ViewAttributes() ?>>
<?php echo $view_id->_1_Descanso_en_dia_habil->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Descanso_festivo_dominical->Visible) { // 1_Descanso_festivo_dominical ?>
		<td data-name="_1_Descanso_festivo_dominical"<?php echo $view_id->_1_Descanso_festivo_dominical->CellAttributes() ?>>
<span<?php echo $view_id->_1_Descanso_festivo_dominical->ViewAttributes() ?>>
<?php echo $view_id->_1_Descanso_festivo_dominical->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Dia_compensatorio->Visible) { // 1_Dia_compensatorio ?>
		<td data-name="_1_Dia_compensatorio"<?php echo $view_id->_1_Dia_compensatorio->CellAttributes() ?>>
<span<?php echo $view_id->_1_Dia_compensatorio->ViewAttributes() ?>>
<?php echo $view_id->_1_Dia_compensatorio->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Erradicacion_en_dia_festivo->Visible) { // 1_Erradicacion_en_dia_festivo ?>
		<td data-name="_1_Erradicacion_en_dia_festivo"<?php echo $view_id->_1_Erradicacion_en_dia_festivo->CellAttributes() ?>>
<span<?php echo $view_id->_1_Erradicacion_en_dia_festivo->ViewAttributes() ?>>
<?php echo $view_id->_1_Erradicacion_en_dia_festivo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Espera_helicoptero_Helistar->Visible) { // 1_Espera_helicoptero_Helistar ?>
		<td data-name="_1_Espera_helicoptero_Helistar"<?php echo $view_id->_1_Espera_helicoptero_Helistar->CellAttributes() ?>>
<span<?php echo $view_id->_1_Espera_helicoptero_Helistar->ViewAttributes() ?>>
<?php echo $view_id->_1_Espera_helicoptero_Helistar->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Extraccion->Visible) { // 1_Extraccion ?>
		<td data-name="_1_Extraccion"<?php echo $view_id->_1_Extraccion->CellAttributes() ?>>
<span<?php echo $view_id->_1_Extraccion->ViewAttributes() ?>>
<?php echo $view_id->_1_Extraccion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Firma_contrato_GME->Visible) { // 1_Firma_contrato_GME ?>
		<td data-name="_1_Firma_contrato_GME"<?php echo $view_id->_1_Firma_contrato_GME->CellAttributes() ?>>
<span<?php echo $view_id->_1_Firma_contrato_GME->ViewAttributes() ?>>
<?php echo $view_id->_1_Firma_contrato_GME->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Induccion_Apoyo_Zonal->Visible) { // 1_Induccion_Apoyo_Zonal ?>
		<td data-name="_1_Induccion_Apoyo_Zonal"<?php echo $view_id->_1_Induccion_Apoyo_Zonal->CellAttributes() ?>>
<span<?php echo $view_id->_1_Induccion_Apoyo_Zonal->ViewAttributes() ?>>
<?php echo $view_id->_1_Induccion_Apoyo_Zonal->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Insercion->Visible) { // 1_Insercion ?>
		<td data-name="_1_Insercion"<?php echo $view_id->_1_Insercion->CellAttributes() ?>>
<span<?php echo $view_id->_1_Insercion->ViewAttributes() ?>>
<?php echo $view_id->_1_Insercion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Visible) { // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase ?>
		<td data-name="_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CellAttributes() ?>>
<span<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewAttributes() ?>>
<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Novedad_apoyo_zonal->Visible) { // 1_Novedad_apoyo_zonal ?>
		<td data-name="_1_Novedad_apoyo_zonal"<?php echo $view_id->_1_Novedad_apoyo_zonal->CellAttributes() ?>>
<span<?php echo $view_id->_1_Novedad_apoyo_zonal->ViewAttributes() ?>>
<?php echo $view_id->_1_Novedad_apoyo_zonal->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Novedad_enfermero->Visible) { // 1_Novedad_enfermero ?>
		<td data-name="_1_Novedad_enfermero"<?php echo $view_id->_1_Novedad_enfermero->CellAttributes() ?>>
<span<?php echo $view_id->_1_Novedad_enfermero->ViewAttributes() ?>>
<?php echo $view_id->_1_Novedad_enfermero->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Punto_fuera_del_area_de_erradicacion->Visible) { // 1_Punto_fuera_del_area_de_erradicacion ?>
		<td data-name="_1_Punto_fuera_del_area_de_erradicacion"<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->CellAttributes() ?>>
<span<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->ViewAttributes() ?>>
<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Transporte_bus->Visible) { // 1_Transporte_bus ?>
		<td data-name="_1_Transporte_bus"<?php echo $view_id->_1_Transporte_bus->CellAttributes() ?>>
<span<?php echo $view_id->_1_Transporte_bus->ViewAttributes() ?>>
<?php echo $view_id->_1_Transporte_bus->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Traslado_apoyo_zonal->Visible) { // 1_Traslado_apoyo_zonal ?>
		<td data-name="_1_Traslado_apoyo_zonal"<?php echo $view_id->_1_Traslado_apoyo_zonal->CellAttributes() ?>>
<span<?php echo $view_id->_1_Traslado_apoyo_zonal->ViewAttributes() ?>>
<?php echo $view_id->_1_Traslado_apoyo_zonal->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_1_Traslado_area_vivac->Visible) { // 1_Traslado_area_vivac ?>
		<td data-name="_1_Traslado_area_vivac"<?php echo $view_id->_1_Traslado_area_vivac->CellAttributes() ?>>
<span<?php echo $view_id->_1_Traslado_area_vivac->ViewAttributes() ?>>
<?php echo $view_id->_1_Traslado_area_vivac->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Adm_Fuerza->Visible) { // Adm_Fuerza ?>
		<td data-name="Adm_Fuerza"<?php echo $view_id->Adm_Fuerza->CellAttributes() ?>>
<span<?php echo $view_id->Adm_Fuerza->ViewAttributes() ?>>
<?php echo $view_id->Adm_Fuerza->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->Visible) { // 2_A_la_espera_definicion_nuevo_punto_FP ?>
		<td data-name="_2_A_la_espera_definicion_nuevo_punto_FP"<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->CellAttributes() ?>>
<span<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->ViewAttributes() ?>>
<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_2_Espera_helicoptero_FP_de_seguridad->Visible) { // 2_Espera_helicoptero_FP_de_seguridad ?>
		<td data-name="_2_Espera_helicoptero_FP_de_seguridad"<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->CellAttributes() ?>>
<span<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->ViewAttributes() ?>>
<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_2_Espera_helicoptero_FP_que_abastece->Visible) { // 2_Espera_helicoptero_FP_que_abastece ?>
		<td data-name="_2_Espera_helicoptero_FP_que_abastece"<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->CellAttributes() ?>>
<span<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->ViewAttributes() ?>>
<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_2_Induccion_FP->Visible) { // 2_Induccion_FP ?>
		<td data-name="_2_Induccion_FP"<?php echo $view_id->_2_Induccion_FP->CellAttributes() ?>>
<span<?php echo $view_id->_2_Induccion_FP->ViewAttributes() ?>>
<?php echo $view_id->_2_Induccion_FP->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->Visible) { // 2_Novedad_canino_o_del_grupo_de_deteccion ?>
		<td data-name="_2_Novedad_canino_o_del_grupo_de_deteccion"<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->CellAttributes() ?>>
<span<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewAttributes() ?>>
<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_2_Problemas_fuerza_publica->Visible) { // 2_Problemas_fuerza_publica ?>
		<td data-name="_2_Problemas_fuerza_publica"<?php echo $view_id->_2_Problemas_fuerza_publica->CellAttributes() ?>>
<span<?php echo $view_id->_2_Problemas_fuerza_publica->ViewAttributes() ?>>
<?php echo $view_id->_2_Problemas_fuerza_publica->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_2_Sin_seguridad->Visible) { // 2_Sin_seguridad ?>
		<td data-name="_2_Sin_seguridad"<?php echo $view_id->_2_Sin_seguridad->CellAttributes() ?>>
<span<?php echo $view_id->_2_Sin_seguridad->ViewAttributes() ?>>
<?php echo $view_id->_2_Sin_seguridad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Sit_Seguridad->Visible) { // Sit_Seguridad ?>
		<td data-name="Sit_Seguridad"<?php echo $view_id->Sit_Seguridad->CellAttributes() ?>>
<span<?php echo $view_id->Sit_Seguridad->ViewAttributes() ?>>
<?php echo $view_id->Sit_Seguridad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_AEI_controlado->Visible) { // 3_AEI_controlado ?>
		<td data-name="_3_AEI_controlado"<?php echo $view_id->_3_AEI_controlado->CellAttributes() ?>>
<span<?php echo $view_id->_3_AEI_controlado->ViewAttributes() ?>>
<?php echo $view_id->_3_AEI_controlado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_AEI_no_controlado->Visible) { // 3_AEI_no_controlado ?>
		<td data-name="_3_AEI_no_controlado"<?php echo $view_id->_3_AEI_no_controlado->CellAttributes() ?>>
<span<?php echo $view_id->_3_AEI_no_controlado->ViewAttributes() ?>>
<?php echo $view_id->_3_AEI_no_controlado->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_Bloqueo_parcial_de_la_comunidad->Visible) { // 3_Bloqueo_parcial_de_la_comunidad ?>
		<td data-name="_3_Bloqueo_parcial_de_la_comunidad"<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->CellAttributes() ?>>
<span<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->ViewAttributes() ?>>
<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_Bloqueo_total_de_la_comunidad->Visible) { // 3_Bloqueo_total_de_la_comunidad ?>
		<td data-name="_3_Bloqueo_total_de_la_comunidad"<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->CellAttributes() ?>>
<span<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->ViewAttributes() ?>>
<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_Combate->Visible) { // 3_Combate ?>
		<td data-name="_3_Combate"<?php echo $view_id->_3_Combate->CellAttributes() ?>>
<span<?php echo $view_id->_3_Combate->ViewAttributes() ?>>
<?php echo $view_id->_3_Combate->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_Hostigamiento->Visible) { // 3_Hostigamiento ?>
		<td data-name="_3_Hostigamiento"<?php echo $view_id->_3_Hostigamiento->CellAttributes() ?>>
<span<?php echo $view_id->_3_Hostigamiento->ViewAttributes() ?>>
<?php echo $view_id->_3_Hostigamiento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_MAP_Controlada->Visible) { // 3_MAP_Controlada ?>
		<td data-name="_3_MAP_Controlada"<?php echo $view_id->_3_MAP_Controlada->CellAttributes() ?>>
<span<?php echo $view_id->_3_MAP_Controlada->ViewAttributes() ?>>
<?php echo $view_id->_3_MAP_Controlada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_MAP_No_controlada->Visible) { // 3_MAP_No_controlada ?>
		<td data-name="_3_MAP_No_controlada"<?php echo $view_id->_3_MAP_No_controlada->CellAttributes() ?>>
<span<?php echo $view_id->_3_MAP_No_controlada->ViewAttributes() ?>>
<?php echo $view_id->_3_MAP_No_controlada->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_MUSE->Visible) { // 3_MUSE ?>
		<td data-name="_3_MUSE"<?php echo $view_id->_3_MUSE->CellAttributes() ?>>
<span<?php echo $view_id->_3_MUSE->ViewAttributes() ?>>
<?php echo $view_id->_3_MUSE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_3_Operaciones_de_seguridad->Visible) { // 3_Operaciones_de_seguridad ?>
		<td data-name="_3_Operaciones_de_seguridad"<?php echo $view_id->_3_Operaciones_de_seguridad->CellAttributes() ?>>
<span<?php echo $view_id->_3_Operaciones_de_seguridad->ViewAttributes() ?>>
<?php echo $view_id->_3_Operaciones_de_seguridad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->LATITUD_segurid->Visible) { // LATITUD_segurid ?>
		<td data-name="LATITUD_segurid"<?php echo $view_id->LATITUD_segurid->CellAttributes() ?>>
<span<?php echo $view_id->LATITUD_segurid->ViewAttributes() ?>>
<?php echo $view_id->LATITUD_segurid->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->GRA_LAT_segurid->Visible) { // GRA_LAT_segurid ?>
		<td data-name="GRA_LAT_segurid"<?php echo $view_id->GRA_LAT_segurid->CellAttributes() ?>>
<span<?php echo $view_id->GRA_LAT_segurid->ViewAttributes() ?>>
<?php echo $view_id->GRA_LAT_segurid->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->MIN_LAT_segurid->Visible) { // MIN_LAT_segurid ?>
		<td data-name="MIN_LAT_segurid"<?php echo $view_id->MIN_LAT_segurid->CellAttributes() ?>>
<span<?php echo $view_id->MIN_LAT_segurid->ViewAttributes() ?>>
<?php echo $view_id->MIN_LAT_segurid->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->SEG_LAT_segurid->Visible) { // SEG_LAT_segurid ?>
		<td data-name="SEG_LAT_segurid"<?php echo $view_id->SEG_LAT_segurid->CellAttributes() ?>>
<span<?php echo $view_id->SEG_LAT_segurid->ViewAttributes() ?>>
<?php echo $view_id->SEG_LAT_segurid->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->GRA_LONG_seguri->Visible) { // GRA_LONG_seguri ?>
		<td data-name="GRA_LONG_seguri"<?php echo $view_id->GRA_LONG_seguri->CellAttributes() ?>>
<span<?php echo $view_id->GRA_LONG_seguri->ViewAttributes() ?>>
<?php echo $view_id->GRA_LONG_seguri->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->MIN_LONG_seguri->Visible) { // MIN_LONG_seguri ?>
		<td data-name="MIN_LONG_seguri"<?php echo $view_id->MIN_LONG_seguri->CellAttributes() ?>>
<span<?php echo $view_id->MIN_LONG_seguri->ViewAttributes() ?>>
<?php echo $view_id->MIN_LONG_seguri->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->SEG_LONG_seguri->Visible) { // SEG_LONG_seguri ?>
		<td data-name="SEG_LONG_seguri"<?php echo $view_id->SEG_LONG_seguri->CellAttributes() ?>>
<span<?php echo $view_id->SEG_LONG_seguri->ViewAttributes() ?>>
<?php echo $view_id->SEG_LONG_seguri->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Novedad->Visible) { // Novedad ?>
		<td data-name="Novedad"<?php echo $view_id->Novedad->CellAttributes() ?>>
<span<?php echo $view_id->Novedad->ViewAttributes() ?>>
<?php echo $view_id->Novedad->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_4_Epidemia->Visible) { // 4_Epidemia ?>
		<td data-name="_4_Epidemia"<?php echo $view_id->_4_Epidemia->CellAttributes() ?>>
<span<?php echo $view_id->_4_Epidemia->ViewAttributes() ?>>
<?php echo $view_id->_4_Epidemia->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_4_Novedad_climatologica->Visible) { // 4_Novedad_climatologica ?>
		<td data-name="_4_Novedad_climatologica"<?php echo $view_id->_4_Novedad_climatologica->CellAttributes() ?>>
<span<?php echo $view_id->_4_Novedad_climatologica->ViewAttributes() ?>>
<?php echo $view_id->_4_Novedad_climatologica->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_4_Registro_de_cultivos->Visible) { // 4_Registro_de_cultivos ?>
		<td data-name="_4_Registro_de_cultivos"<?php echo $view_id->_4_Registro_de_cultivos->CellAttributes() ?>>
<span<?php echo $view_id->_4_Registro_de_cultivos->ViewAttributes() ?>>
<?php echo $view_id->_4_Registro_de_cultivos->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_4_Zona_con_cultivos_muy_dispersos->Visible) { // 4_Zona_con_cultivos_muy_dispersos ?>
		<td data-name="_4_Zona_con_cultivos_muy_dispersos"<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->CellAttributes() ?>>
<span<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->ViewAttributes() ?>>
<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_4_Zona_de_cruce_de_rios_caudalosos->Visible) { // 4_Zona_de_cruce_de_rios_caudalosos ?>
		<td data-name="_4_Zona_de_cruce_de_rios_caudalosos"<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->CellAttributes() ?>>
<span<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->ViewAttributes() ?>>
<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->_4_Zona_sin_cultivos->Visible) { // 4_Zona_sin_cultivos ?>
		<td data-name="_4_Zona_sin_cultivos"<?php echo $view_id->_4_Zona_sin_cultivos->CellAttributes() ?>>
<span<?php echo $view_id->_4_Zona_sin_cultivos->ViewAttributes() ?>>
<?php echo $view_id->_4_Zona_sin_cultivos->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Num_Erra_Salen->Visible) { // Num_Erra_Salen ?>
		<td data-name="Num_Erra_Salen"<?php echo $view_id->Num_Erra_Salen->CellAttributes() ?>>
<span<?php echo $view_id->Num_Erra_Salen->ViewAttributes() ?>>
<?php echo $view_id->Num_Erra_Salen->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Num_Erra_Quedan->Visible) { // Num_Erra_Quedan ?>
		<td data-name="Num_Erra_Quedan"<?php echo $view_id->Num_Erra_Quedan->CellAttributes() ?>>
<span<?php echo $view_id->Num_Erra_Quedan->ViewAttributes() ?>>
<?php echo $view_id->Num_Erra_Quedan->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->No_ENFERMERO->Visible) { // No_ENFERMERO ?>
		<td data-name="No_ENFERMERO"<?php echo $view_id->No_ENFERMERO->CellAttributes() ?>>
<span<?php echo $view_id->No_ENFERMERO->ViewAttributes() ?>>
<?php echo $view_id->No_ENFERMERO->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->NUM_FP->Visible) { // NUM_FP ?>
		<td data-name="NUM_FP"<?php echo $view_id->NUM_FP->CellAttributes() ?>>
<span<?php echo $view_id->NUM_FP->ViewAttributes() ?>>
<?php echo $view_id->NUM_FP->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->NUM_Perso_EVA->Visible) { // NUM_Perso_EVA ?>
		<td data-name="NUM_Perso_EVA"<?php echo $view_id->NUM_Perso_EVA->CellAttributes() ?>>
<span<?php echo $view_id->NUM_Perso_EVA->ViewAttributes() ?>>
<?php echo $view_id->NUM_Perso_EVA->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->NUM_Poli->Visible) { // NUM_Poli ?>
		<td data-name="NUM_Poli"<?php echo $view_id->NUM_Poli->CellAttributes() ?>>
<span<?php echo $view_id->NUM_Poli->ViewAttributes() ?>>
<?php echo $view_id->NUM_Poli->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->AD1O->Visible) { // AÑO ?>
		<td data-name="AD1O"<?php echo $view_id->AD1O->CellAttributes() ?>>
<span<?php echo $view_id->AD1O->ViewAttributes() ?>>
<?php echo $view_id->AD1O->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->FASE->Visible) { // FASE ?>
		<td data-name="FASE"<?php echo $view_id->FASE->CellAttributes() ?>>
<span<?php echo $view_id->FASE->ViewAttributes() ?>>
<?php echo $view_id->FASE->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($view_id->Modificado->Visible) { // Modificado ?>
		<td data-name="Modificado"<?php echo $view_id->Modificado->CellAttributes() ?>>
<span<?php echo $view_id->Modificado->ViewAttributes() ?>>
<?php echo $view_id->Modificado->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view_id_list->ListOptions->Render("body", "right", $view_id_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view_id->CurrentAction <> "gridadd")
		$view_id_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view_id->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view_id_list->Recordset)
	$view_id_list->Recordset->Close();
?>
<?php if ($view_id->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($view_id->CurrentAction <> "gridadd" && $view_id->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view_id_list->Pager)) $view_id_list->Pager = new cPrevNextPager($view_id_list->StartRec, $view_id_list->DisplayRecs, $view_id_list->TotalRecs) ?>
<?php if ($view_id_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view_id_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view_id_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view_id_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view_id_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view_id_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view_id_list->PageUrl() ?>start=<?php echo $view_id_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view_id_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view_id_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view_id_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view_id_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_id_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view_id_list->TotalRecs == 0 && $view_id->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view_id_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view_id->Export == "") { ?>
<script type="text/javascript">
fview_idlistsrch.Init();
fview_idlist.Init();
</script>
<?php } ?>
<?php
$view_id_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view_id->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view_id_list->Page_Terminate();
?>
