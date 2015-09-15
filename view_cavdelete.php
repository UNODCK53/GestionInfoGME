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

$view_cav_delete = NULL; // Initialize page object first

class cview_cav_delete extends cview_cav {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_cav';

	// Page object name
	var $PageObjName = 'view_cav_delete';

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

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view_cav', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("view_cavlist.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("view_cavlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in view_cav class, view_cavinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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
		$this->Modificado->DbValue = $row['Modificado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `USUARIO`, `USUARIO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
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

			// Num_AV
			$this->Num_AV->ViewValue = $this->Num_AV->CurrentValue;
			$this->Num_AV->ViewCustomAttributes = "";

			// NOM_APOYO
			if (strval($this->NOM_APOYO->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_APOYO`" . ew_SearchString("=", $this->NOM_APOYO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_APOYO`, `NOM_APOYO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_APOYO`, `NOM_APOYO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
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

			// NOM_PE
			if (strval($this->NOM_PE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PE`" . ew_SearchString("=", $this->NOM_PE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
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
			if (strval($this->RANGO_COMAN->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->RANGO_COMAN->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='rango'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->RANGO_COMAN, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->RANGO_COMAN->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->RANGO_COMAN->ViewValue = $this->RANGO_COMAN->CurrentValue;
				}
			} else {
				$this->RANGO_COMAN->ViewValue = NULL;
			}
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
			$this->FECHA_INTO_AV->ViewValue = ew_FormatDateTime($this->FECHA_INTO_AV->ViewValue, 5);
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
			if (strval($this->AD1O->CurrentValue) <> "") {
				$sFilterWrk = "`AÑO`" . ew_SearchString("=", $this->AD1O->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `AÑO`, `AÑO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
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
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `FASE`, `FASE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view_cav`";
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

			// Modificado
			$this->Modificado->LinkCustomAttributes = "";
			$this->Modificado->HrefValue = "";
			$this->Modificado->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['llave'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "view_cavlist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($view_cav_delete)) $view_cav_delete = new cview_cav_delete();

// Page init
$view_cav_delete->Page_Init();

// Page main
$view_cav_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_cav_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_cav_delete = new ew_Page("view_cav_delete");
view_cav_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = view_cav_delete.PageID; // For backward compatibility

// Form object
var fview_cavdelete = new ew_Form("fview_cavdelete");

// Form_CustomValidate event
fview_cavdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_cavdelete.ValidateRequired = true;
<?php } else { ?>
fview_cavdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_cavdelete.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_cavdelete.Lists["x_NOM_APOYO"] = {"LinkField":"x_NOM_APOYO","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_APOYO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_cavdelete.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_cavdelete.Lists["x_RANGO_COMAN"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_cavdelete.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_cavdelete.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($view_cav_delete->Recordset = $view_cav_delete->LoadRecordset())
	$view_cav_deleteTotalRecs = $view_cav_delete->Recordset->RecordCount(); // Get record count
if ($view_cav_deleteTotalRecs <= 0) { // No record found, exit
	if ($view_cav_delete->Recordset)
		$view_cav_delete->Recordset->Close();
	$view_cav_delete->Page_Terminate("view_cavlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $view_cav_delete->ShowPageHeader(); ?>
<?php
$view_cav_delete->ShowMessage();
?>
<form name="fview_cavdelete" id="fview_cavdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_cav_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_cav_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_cav">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($view_cav_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $view_cav->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($view_cav->llave->Visible) { // llave ?>
		<th><span id="elh_view_cav_llave" class="view_cav_llave"><?php echo $view_cav->llave->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->F_Sincron->Visible) { // F_Sincron ?>
		<th><span id="elh_view_cav_F_Sincron" class="view_cav_F_Sincron"><?php echo $view_cav->F_Sincron->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->USUARIO->Visible) { // USUARIO ?>
		<th><span id="elh_view_cav_USUARIO" class="view_cav_USUARIO"><?php echo $view_cav->USUARIO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Cargo_gme->Visible) { // Cargo_gme ?>
		<th><span id="elh_view_cav_Cargo_gme" class="view_cav_Cargo_gme"><?php echo $view_cav->Cargo_gme->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Num_AV->Visible) { // Num_AV ?>
		<th><span id="elh_view_cav_Num_AV" class="view_cav_Num_AV"><?php echo $view_cav->Num_AV->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NOM_APOYO->Visible) { // NOM_APOYO ?>
		<th><span id="elh_view_cav_NOM_APOYO" class="view_cav_NOM_APOYO"><?php echo $view_cav->NOM_APOYO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
		<th><span id="elh_view_cav_Otro_Nom_Apoyo" class="view_cav_Otro_Nom_Apoyo"><?php echo $view_cav->Otro_Nom_Apoyo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
		<th><span id="elh_view_cav_Otro_CC_Apoyo" class="view_cav_Otro_CC_Apoyo"><?php echo $view_cav->Otro_CC_Apoyo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NOM_PE->Visible) { // NOM_PE ?>
		<th><span id="elh_view_cav_NOM_PE" class="view_cav_NOM_PE"><?php echo $view_cav->NOM_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Otro_PE->Visible) { // Otro_PE ?>
		<th><span id="elh_view_cav_Otro_PE" class="view_cav_Otro_PE"><?php echo $view_cav->Otro_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Departamento->Visible) { // Departamento ?>
		<th><span id="elh_view_cav_Departamento" class="view_cav_Departamento"><?php echo $view_cav->Departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Muncipio->Visible) { // Muncipio ?>
		<th><span id="elh_view_cav_Muncipio" class="view_cav_Muncipio"><?php echo $view_cav->Muncipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NOM_VDA->Visible) { // NOM_VDA ?>
		<th><span id="elh_view_cav_NOM_VDA" class="view_cav_NOM_VDA"><?php echo $view_cav->NOM_VDA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NO_E->Visible) { // NO_E ?>
		<th><span id="elh_view_cav_NO_E" class="view_cav_NO_E"><?php echo $view_cav->NO_E->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NO_OF->Visible) { // NO_OF ?>
		<th><span id="elh_view_cav_NO_OF" class="view_cav_NO_OF"><?php echo $view_cav->NO_OF->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NO_SUBOF->Visible) { // NO_SUBOF ?>
		<th><span id="elh_view_cav_NO_SUBOF" class="view_cav_NO_SUBOF"><?php echo $view_cav->NO_SUBOF->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NO_SOL->Visible) { // NO_SOL ?>
		<th><span id="elh_view_cav_NO_SOL" class="view_cav_NO_SOL"><?php echo $view_cav->NO_SOL->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NO_PATRU->Visible) { // NO_PATRU ?>
		<th><span id="elh_view_cav_NO_PATRU" class="view_cav_NO_PATRU"><?php echo $view_cav->NO_PATRU->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Nom_enfer->Visible) { // Nom_enfer ?>
		<th><span id="elh_view_cav_Nom_enfer" class="view_cav_Nom_enfer"><?php echo $view_cav->Nom_enfer->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Otro_Nom_Enfer->Visible) { // Otro_Nom_Enfer ?>
		<th><span id="elh_view_cav_Otro_Nom_Enfer" class="view_cav_Otro_Nom_Enfer"><?php echo $view_cav->Otro_Nom_Enfer->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Otro_CC_Enfer->Visible) { // Otro_CC_Enfer ?>
		<th><span id="elh_view_cav_Otro_CC_Enfer" class="view_cav_Otro_CC_Enfer"><?php echo $view_cav->Otro_CC_Enfer->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Armada->Visible) { // Armada ?>
		<th><span id="elh_view_cav_Armada" class="view_cav_Armada"><?php echo $view_cav->Armada->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Ejercito->Visible) { // Ejercito ?>
		<th><span id="elh_view_cav_Ejercito" class="view_cav_Ejercito"><?php echo $view_cav->Ejercito->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Policia->Visible) { // Policia ?>
		<th><span id="elh_view_cav_Policia" class="view_cav_Policia"><?php echo $view_cav->Policia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NOM_UNIDAD->Visible) { // NOM_UNIDAD ?>
		<th><span id="elh_view_cav_NOM_UNIDAD" class="view_cav_NOM_UNIDAD"><?php echo $view_cav->NOM_UNIDAD->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NOM_COMAN->Visible) { // NOM_COMAN ?>
		<th><span id="elh_view_cav_NOM_COMAN" class="view_cav_NOM_COMAN"><?php echo $view_cav->NOM_COMAN->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->CC_COMAN->Visible) { // CC_COMAN ?>
		<th><span id="elh_view_cav_CC_COMAN" class="view_cav_CC_COMAN"><?php echo $view_cav->CC_COMAN->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->TEL_COMAN->Visible) { // TEL_COMAN ?>
		<th><span id="elh_view_cav_TEL_COMAN" class="view_cav_TEL_COMAN"><?php echo $view_cav->TEL_COMAN->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->RANGO_COMAN->Visible) { // RANGO_COMAN ?>
		<th><span id="elh_view_cav_RANGO_COMAN" class="view_cav_RANGO_COMAN"><?php echo $view_cav->RANGO_COMAN->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Otro_rango->Visible) { // Otro_rango ?>
		<th><span id="elh_view_cav_Otro_rango" class="view_cav_Otro_rango"><?php echo $view_cav->Otro_rango->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NO_GDETECCION->Visible) { // NO_GDETECCION ?>
		<th><span id="elh_view_cav_NO_GDETECCION" class="view_cav_NO_GDETECCION"><?php echo $view_cav->NO_GDETECCION->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->NO_BINOMIO->Visible) { // NO_BINOMIO ?>
		<th><span id="elh_view_cav_NO_BINOMIO" class="view_cav_NO_BINOMIO"><?php echo $view_cav->NO_BINOMIO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->FECHA_INTO_AV->Visible) { // FECHA_INTO_AV ?>
		<th><span id="elh_view_cav_FECHA_INTO_AV" class="view_cav_FECHA_INTO_AV"><?php echo $view_cav->FECHA_INTO_AV->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->DIA->Visible) { // DIA ?>
		<th><span id="elh_view_cav_DIA" class="view_cav_DIA"><?php echo $view_cav->DIA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->MES->Visible) { // MES ?>
		<th><span id="elh_view_cav_MES" class="view_cav_MES"><?php echo $view_cav->MES->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->LATITUD->Visible) { // LATITUD ?>
		<th><span id="elh_view_cav_LATITUD" class="view_cav_LATITUD"><?php echo $view_cav->LATITUD->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->GRA_LAT->Visible) { // GRA_LAT ?>
		<th><span id="elh_view_cav_GRA_LAT" class="view_cav_GRA_LAT"><?php echo $view_cav->GRA_LAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->MIN_LAT->Visible) { // MIN_LAT ?>
		<th><span id="elh_view_cav_MIN_LAT" class="view_cav_MIN_LAT"><?php echo $view_cav->MIN_LAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->SEG_LAT->Visible) { // SEG_LAT ?>
		<th><span id="elh_view_cav_SEG_LAT" class="view_cav_SEG_LAT"><?php echo $view_cav->SEG_LAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->GRA_LONG->Visible) { // GRA_LONG ?>
		<th><span id="elh_view_cav_GRA_LONG" class="view_cav_GRA_LONG"><?php echo $view_cav->GRA_LONG->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->MIN_LONG->Visible) { // MIN_LONG ?>
		<th><span id="elh_view_cav_MIN_LONG" class="view_cav_MIN_LONG"><?php echo $view_cav->MIN_LONG->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->SEG_LONG->Visible) { // SEG_LONG ?>
		<th><span id="elh_view_cav_SEG_LONG" class="view_cav_SEG_LONG"><?php echo $view_cav->SEG_LONG->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->OBSERVACION->Visible) { // OBSERVACION ?>
		<th><span id="elh_view_cav_OBSERVACION" class="view_cav_OBSERVACION"><?php echo $view_cav->OBSERVACION->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->AD1O->Visible) { // AÑO ?>
		<th><span id="elh_view_cav_AD1O" class="view_cav_AD1O"><?php echo $view_cav->AD1O->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->FASE->Visible) { // FASE ?>
		<th><span id="elh_view_cav_FASE" class="view_cav_FASE"><?php echo $view_cav->FASE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_cav->Modificado->Visible) { // Modificado ?>
		<th><span id="elh_view_cav_Modificado" class="view_cav_Modificado"><?php echo $view_cav->Modificado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$view_cav_delete->RecCnt = 0;
$i = 0;
while (!$view_cav_delete->Recordset->EOF) {
	$view_cav_delete->RecCnt++;
	$view_cav_delete->RowCnt++;

	// Set row properties
	$view_cav->ResetAttrs();
	$view_cav->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$view_cav_delete->LoadRowValues($view_cav_delete->Recordset);

	// Render row
	$view_cav_delete->RenderRow();
?>
	<tr<?php echo $view_cav->RowAttributes() ?>>
<?php if ($view_cav->llave->Visible) { // llave ?>
		<td<?php echo $view_cav->llave->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_llave" class="view_cav_llave">
<span<?php echo $view_cav->llave->ViewAttributes() ?>>
<?php echo $view_cav->llave->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->F_Sincron->Visible) { // F_Sincron ?>
		<td<?php echo $view_cav->F_Sincron->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_F_Sincron" class="view_cav_F_Sincron">
<span<?php echo $view_cav->F_Sincron->ViewAttributes() ?>>
<?php echo $view_cav->F_Sincron->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->USUARIO->Visible) { // USUARIO ?>
		<td<?php echo $view_cav->USUARIO->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_USUARIO" class="view_cav_USUARIO">
<span<?php echo $view_cav->USUARIO->ViewAttributes() ?>>
<?php echo $view_cav->USUARIO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Cargo_gme->Visible) { // Cargo_gme ?>
		<td<?php echo $view_cav->Cargo_gme->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Cargo_gme" class="view_cav_Cargo_gme">
<span<?php echo $view_cav->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_cav->Cargo_gme->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Num_AV->Visible) { // Num_AV ?>
		<td<?php echo $view_cav->Num_AV->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Num_AV" class="view_cav_Num_AV">
<span<?php echo $view_cav->Num_AV->ViewAttributes() ?>>
<?php echo $view_cav->Num_AV->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NOM_APOYO->Visible) { // NOM_APOYO ?>
		<td<?php echo $view_cav->NOM_APOYO->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NOM_APOYO" class="view_cav_NOM_APOYO">
<span<?php echo $view_cav->NOM_APOYO->ViewAttributes() ?>>
<?php echo $view_cav->NOM_APOYO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
		<td<?php echo $view_cav->Otro_Nom_Apoyo->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Otro_Nom_Apoyo" class="view_cav_Otro_Nom_Apoyo">
<span<?php echo $view_cav->Otro_Nom_Apoyo->ViewAttributes() ?>>
<?php echo $view_cav->Otro_Nom_Apoyo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
		<td<?php echo $view_cav->Otro_CC_Apoyo->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Otro_CC_Apoyo" class="view_cav_Otro_CC_Apoyo">
<span<?php echo $view_cav->Otro_CC_Apoyo->ViewAttributes() ?>>
<?php echo $view_cav->Otro_CC_Apoyo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NOM_PE->Visible) { // NOM_PE ?>
		<td<?php echo $view_cav->NOM_PE->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NOM_PE" class="view_cav_NOM_PE">
<span<?php echo $view_cav->NOM_PE->ViewAttributes() ?>>
<?php echo $view_cav->NOM_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Otro_PE->Visible) { // Otro_PE ?>
		<td<?php echo $view_cav->Otro_PE->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Otro_PE" class="view_cav_Otro_PE">
<span<?php echo $view_cav->Otro_PE->ViewAttributes() ?>>
<?php echo $view_cav->Otro_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Departamento->Visible) { // Departamento ?>
		<td<?php echo $view_cav->Departamento->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Departamento" class="view_cav_Departamento">
<span<?php echo $view_cav->Departamento->ViewAttributes() ?>>
<?php echo $view_cav->Departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Muncipio->Visible) { // Muncipio ?>
		<td<?php echo $view_cav->Muncipio->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Muncipio" class="view_cav_Muncipio">
<span<?php echo $view_cav->Muncipio->ViewAttributes() ?>>
<?php echo $view_cav->Muncipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NOM_VDA->Visible) { // NOM_VDA ?>
		<td<?php echo $view_cav->NOM_VDA->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NOM_VDA" class="view_cav_NOM_VDA">
<span<?php echo $view_cav->NOM_VDA->ViewAttributes() ?>>
<?php echo $view_cav->NOM_VDA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NO_E->Visible) { // NO_E ?>
		<td<?php echo $view_cav->NO_E->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NO_E" class="view_cav_NO_E">
<span<?php echo $view_cav->NO_E->ViewAttributes() ?>>
<?php echo $view_cav->NO_E->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NO_OF->Visible) { // NO_OF ?>
		<td<?php echo $view_cav->NO_OF->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NO_OF" class="view_cav_NO_OF">
<span<?php echo $view_cav->NO_OF->ViewAttributes() ?>>
<?php echo $view_cav->NO_OF->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NO_SUBOF->Visible) { // NO_SUBOF ?>
		<td<?php echo $view_cav->NO_SUBOF->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NO_SUBOF" class="view_cav_NO_SUBOF">
<span<?php echo $view_cav->NO_SUBOF->ViewAttributes() ?>>
<?php echo $view_cav->NO_SUBOF->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NO_SOL->Visible) { // NO_SOL ?>
		<td<?php echo $view_cav->NO_SOL->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NO_SOL" class="view_cav_NO_SOL">
<span<?php echo $view_cav->NO_SOL->ViewAttributes() ?>>
<?php echo $view_cav->NO_SOL->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NO_PATRU->Visible) { // NO_PATRU ?>
		<td<?php echo $view_cav->NO_PATRU->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NO_PATRU" class="view_cav_NO_PATRU">
<span<?php echo $view_cav->NO_PATRU->ViewAttributes() ?>>
<?php echo $view_cav->NO_PATRU->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Nom_enfer->Visible) { // Nom_enfer ?>
		<td<?php echo $view_cav->Nom_enfer->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Nom_enfer" class="view_cav_Nom_enfer">
<span<?php echo $view_cav->Nom_enfer->ViewAttributes() ?>>
<?php echo $view_cav->Nom_enfer->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Otro_Nom_Enfer->Visible) { // Otro_Nom_Enfer ?>
		<td<?php echo $view_cav->Otro_Nom_Enfer->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Otro_Nom_Enfer" class="view_cav_Otro_Nom_Enfer">
<span<?php echo $view_cav->Otro_Nom_Enfer->ViewAttributes() ?>>
<?php echo $view_cav->Otro_Nom_Enfer->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Otro_CC_Enfer->Visible) { // Otro_CC_Enfer ?>
		<td<?php echo $view_cav->Otro_CC_Enfer->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Otro_CC_Enfer" class="view_cav_Otro_CC_Enfer">
<span<?php echo $view_cav->Otro_CC_Enfer->ViewAttributes() ?>>
<?php echo $view_cav->Otro_CC_Enfer->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Armada->Visible) { // Armada ?>
		<td<?php echo $view_cav->Armada->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Armada" class="view_cav_Armada">
<span<?php echo $view_cav->Armada->ViewAttributes() ?>>
<?php echo $view_cav->Armada->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Ejercito->Visible) { // Ejercito ?>
		<td<?php echo $view_cav->Ejercito->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Ejercito" class="view_cav_Ejercito">
<span<?php echo $view_cav->Ejercito->ViewAttributes() ?>>
<?php echo $view_cav->Ejercito->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Policia->Visible) { // Policia ?>
		<td<?php echo $view_cav->Policia->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Policia" class="view_cav_Policia">
<span<?php echo $view_cav->Policia->ViewAttributes() ?>>
<?php echo $view_cav->Policia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NOM_UNIDAD->Visible) { // NOM_UNIDAD ?>
		<td<?php echo $view_cav->NOM_UNIDAD->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NOM_UNIDAD" class="view_cav_NOM_UNIDAD">
<span<?php echo $view_cav->NOM_UNIDAD->ViewAttributes() ?>>
<?php echo $view_cav->NOM_UNIDAD->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NOM_COMAN->Visible) { // NOM_COMAN ?>
		<td<?php echo $view_cav->NOM_COMAN->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NOM_COMAN" class="view_cav_NOM_COMAN">
<span<?php echo $view_cav->NOM_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->NOM_COMAN->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->CC_COMAN->Visible) { // CC_COMAN ?>
		<td<?php echo $view_cav->CC_COMAN->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_CC_COMAN" class="view_cav_CC_COMAN">
<span<?php echo $view_cav->CC_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->CC_COMAN->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->TEL_COMAN->Visible) { // TEL_COMAN ?>
		<td<?php echo $view_cav->TEL_COMAN->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_TEL_COMAN" class="view_cav_TEL_COMAN">
<span<?php echo $view_cav->TEL_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->TEL_COMAN->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->RANGO_COMAN->Visible) { // RANGO_COMAN ?>
		<td<?php echo $view_cav->RANGO_COMAN->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_RANGO_COMAN" class="view_cav_RANGO_COMAN">
<span<?php echo $view_cav->RANGO_COMAN->ViewAttributes() ?>>
<?php echo $view_cav->RANGO_COMAN->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Otro_rango->Visible) { // Otro_rango ?>
		<td<?php echo $view_cav->Otro_rango->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Otro_rango" class="view_cav_Otro_rango">
<span<?php echo $view_cav->Otro_rango->ViewAttributes() ?>>
<?php echo $view_cav->Otro_rango->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NO_GDETECCION->Visible) { // NO_GDETECCION ?>
		<td<?php echo $view_cav->NO_GDETECCION->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NO_GDETECCION" class="view_cav_NO_GDETECCION">
<span<?php echo $view_cav->NO_GDETECCION->ViewAttributes() ?>>
<?php echo $view_cav->NO_GDETECCION->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->NO_BINOMIO->Visible) { // NO_BINOMIO ?>
		<td<?php echo $view_cav->NO_BINOMIO->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_NO_BINOMIO" class="view_cav_NO_BINOMIO">
<span<?php echo $view_cav->NO_BINOMIO->ViewAttributes() ?>>
<?php echo $view_cav->NO_BINOMIO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->FECHA_INTO_AV->Visible) { // FECHA_INTO_AV ?>
		<td<?php echo $view_cav->FECHA_INTO_AV->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_FECHA_INTO_AV" class="view_cav_FECHA_INTO_AV">
<span<?php echo $view_cav->FECHA_INTO_AV->ViewAttributes() ?>>
<?php echo $view_cav->FECHA_INTO_AV->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->DIA->Visible) { // DIA ?>
		<td<?php echo $view_cav->DIA->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_DIA" class="view_cav_DIA">
<span<?php echo $view_cav->DIA->ViewAttributes() ?>>
<?php echo $view_cav->DIA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->MES->Visible) { // MES ?>
		<td<?php echo $view_cav->MES->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_MES" class="view_cav_MES">
<span<?php echo $view_cav->MES->ViewAttributes() ?>>
<?php echo $view_cav->MES->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->LATITUD->Visible) { // LATITUD ?>
		<td<?php echo $view_cav->LATITUD->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_LATITUD" class="view_cav_LATITUD">
<span<?php echo $view_cav->LATITUD->ViewAttributes() ?>>
<?php echo $view_cav->LATITUD->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->GRA_LAT->Visible) { // GRA_LAT ?>
		<td<?php echo $view_cav->GRA_LAT->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_GRA_LAT" class="view_cav_GRA_LAT">
<span<?php echo $view_cav->GRA_LAT->ViewAttributes() ?>>
<?php echo $view_cav->GRA_LAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->MIN_LAT->Visible) { // MIN_LAT ?>
		<td<?php echo $view_cav->MIN_LAT->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_MIN_LAT" class="view_cav_MIN_LAT">
<span<?php echo $view_cav->MIN_LAT->ViewAttributes() ?>>
<?php echo $view_cav->MIN_LAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->SEG_LAT->Visible) { // SEG_LAT ?>
		<td<?php echo $view_cav->SEG_LAT->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_SEG_LAT" class="view_cav_SEG_LAT">
<span<?php echo $view_cav->SEG_LAT->ViewAttributes() ?>>
<?php echo $view_cav->SEG_LAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->GRA_LONG->Visible) { // GRA_LONG ?>
		<td<?php echo $view_cav->GRA_LONG->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_GRA_LONG" class="view_cav_GRA_LONG">
<span<?php echo $view_cav->GRA_LONG->ViewAttributes() ?>>
<?php echo $view_cav->GRA_LONG->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->MIN_LONG->Visible) { // MIN_LONG ?>
		<td<?php echo $view_cav->MIN_LONG->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_MIN_LONG" class="view_cav_MIN_LONG">
<span<?php echo $view_cav->MIN_LONG->ViewAttributes() ?>>
<?php echo $view_cav->MIN_LONG->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->SEG_LONG->Visible) { // SEG_LONG ?>
		<td<?php echo $view_cav->SEG_LONG->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_SEG_LONG" class="view_cav_SEG_LONG">
<span<?php echo $view_cav->SEG_LONG->ViewAttributes() ?>>
<?php echo $view_cav->SEG_LONG->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->OBSERVACION->Visible) { // OBSERVACION ?>
		<td<?php echo $view_cav->OBSERVACION->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_OBSERVACION" class="view_cav_OBSERVACION">
<span<?php echo $view_cav->OBSERVACION->ViewAttributes() ?>>
<?php echo $view_cav->OBSERVACION->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->AD1O->Visible) { // AÑO ?>
		<td<?php echo $view_cav->AD1O->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_AD1O" class="view_cav_AD1O">
<span<?php echo $view_cav->AD1O->ViewAttributes() ?>>
<?php echo $view_cav->AD1O->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->FASE->Visible) { // FASE ?>
		<td<?php echo $view_cav->FASE->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_FASE" class="view_cav_FASE">
<span<?php echo $view_cav->FASE->ViewAttributes() ?>>
<?php echo $view_cav->FASE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_cav->Modificado->Visible) { // Modificado ?>
		<td<?php echo $view_cav->Modificado->CellAttributes() ?>>
<span id="el<?php echo $view_cav_delete->RowCnt ?>_view_cav_Modificado" class="view_cav_Modificado">
<span<?php echo $view_cav->Modificado->ViewAttributes() ?>>
<?php echo $view_cav->Modificado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$view_cav_delete->Recordset->MoveNext();
}
$view_cav_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fview_cavdelete.Init();
</script>
<?php
$view_cav_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_cav_delete->Page_Terminate();
?>
