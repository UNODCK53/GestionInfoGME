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

$view1_acc_delete = NULL; // Initialize page object first

class cview1_acc_delete extends cview1_acc {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view1_acc';

	// Page object name
	var $PageObjName = 'view1_acc_delete';

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

		// Table object (view1_acc)
		if (!isset($GLOBALS["view1_acc"]) || get_class($GLOBALS["view1_acc"]) == "cview1_acc") {
			$GLOBALS["view1_acc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1_acc"];
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
			define("EW_TABLE_NAME", 'view1_acc', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view1_acclist.php"));
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
			$this->Page_Terminate("view1_acclist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in view1_acc class, view1_accinfo.php

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
		$this->NOM_PE->setDbValue($rs->fields('NOM_PE'));
		$this->Otro_PE->setDbValue($rs->fields('Otro_PE'));
		$this->NOM_APOYO->setDbValue($rs->fields('NOM_APOYO'));
		$this->Otro_Nom_Apoyo->setDbValue($rs->fields('Otro_Nom_Apoyo'));
		$this->Otro_CC_Apoyo->setDbValue($rs->fields('Otro_CC_Apoyo'));
		$this->NOM_ENLACE->setDbValue($rs->fields('NOM_ENLACE'));
		$this->Otro_Nom_Enlace->setDbValue($rs->fields('Otro_Nom_Enlace'));
		$this->Otro_CC_Enlace->setDbValue($rs->fields('Otro_CC_Enlace'));
		$this->NOM_PGE->setDbValue($rs->fields('NOM_PGE'));
		$this->Otro_Nom_PGE->setDbValue($rs->fields('Otro_Nom_PGE'));
		$this->Otro_CC_PGE->setDbValue($rs->fields('Otro_CC_PGE'));
		$this->Departamento->setDbValue($rs->fields('Departamento'));
		$this->Muncipio->setDbValue($rs->fields('Muncipio'));
		$this->NOM_VDA->setDbValue($rs->fields('NOM_VDA'));
		$this->LATITUD->setDbValue($rs->fields('LATITUD'));
		$this->GRA_LAT->setDbValue($rs->fields('GRA_LAT'));
		$this->MIN_LAT->setDbValue($rs->fields('MIN_LAT'));
		$this->SEG_LAT->setDbValue($rs->fields('SEG_LAT'));
		$this->GRA_LONG->setDbValue($rs->fields('GRA_LONG'));
		$this->MIN_LONG->setDbValue($rs->fields('MIN_LONG'));
		$this->SEG_LONG->setDbValue($rs->fields('SEG_LONG'));
		$this->FECHA_ACC->setDbValue($rs->fields('FECHA_ACC'));
		$this->HORA_ACC->setDbValue($rs->fields('HORA_ACC'));
		$this->Hora_ingreso->setDbValue($rs->fields('Hora_ingreso'));
		$this->FP_Armada->setDbValue($rs->fields('FP_Armada'));
		$this->FP_Ejercito->setDbValue($rs->fields('FP_Ejercito'));
		$this->FP_Policia->setDbValue($rs->fields('FP_Policia'));
		$this->NOM_COMANDANTE->setDbValue($rs->fields('NOM_COMANDANTE'));
		$this->TESTI1->setDbValue($rs->fields('TESTI1'));
		$this->CC_TESTI1->setDbValue($rs->fields('CC_TESTI1'));
		$this->CARGO_TESTI1->setDbValue($rs->fields('CARGO_TESTI1'));
		$this->TESTI2->setDbValue($rs->fields('TESTI2'));
		$this->CC_TESTI2->setDbValue($rs->fields('CC_TESTI2'));
		$this->CARGO_TESTI2->setDbValue($rs->fields('CARGO_TESTI2'));
		$this->Afectados->setDbValue($rs->fields('Afectados'));
		$this->NUM_Afectado->setDbValue($rs->fields('NUM_Afectado'));
		$this->Nom_Afectado->setDbValue($rs->fields('Nom_Afectado'));
		$this->CC_Afectado->setDbValue($rs->fields('CC_Afectado'));
		$this->Cargo_Afectado->setDbValue($rs->fields('Cargo_Afectado'));
		$this->Tipo_incidente->setDbValue($rs->fields('Tipo_incidente'));
		$this->Parte_Cuerpo->setDbValue($rs->fields('Parte_Cuerpo'));
		$this->ESTADO_AFEC->setDbValue($rs->fields('ESTADO_AFEC'));
		$this->EVACUADO->setDbValue($rs->fields('EVACUADO'));
		$this->DESC_ACC->setDbValue($rs->fields('DESC_ACC'));
		$this->Modificado->setDbValue($rs->fields('Modificado'));
		$this->llave_2->setDbValue($rs->fields('llave_2'));
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
		$this->NOM_APOYO->DbValue = $row['NOM_APOYO'];
		$this->Otro_Nom_Apoyo->DbValue = $row['Otro_Nom_Apoyo'];
		$this->Otro_CC_Apoyo->DbValue = $row['Otro_CC_Apoyo'];
		$this->NOM_ENLACE->DbValue = $row['NOM_ENLACE'];
		$this->Otro_Nom_Enlace->DbValue = $row['Otro_Nom_Enlace'];
		$this->Otro_CC_Enlace->DbValue = $row['Otro_CC_Enlace'];
		$this->NOM_PGE->DbValue = $row['NOM_PGE'];
		$this->Otro_Nom_PGE->DbValue = $row['Otro_Nom_PGE'];
		$this->Otro_CC_PGE->DbValue = $row['Otro_CC_PGE'];
		$this->Departamento->DbValue = $row['Departamento'];
		$this->Muncipio->DbValue = $row['Muncipio'];
		$this->NOM_VDA->DbValue = $row['NOM_VDA'];
		$this->LATITUD->DbValue = $row['LATITUD'];
		$this->GRA_LAT->DbValue = $row['GRA_LAT'];
		$this->MIN_LAT->DbValue = $row['MIN_LAT'];
		$this->SEG_LAT->DbValue = $row['SEG_LAT'];
		$this->GRA_LONG->DbValue = $row['GRA_LONG'];
		$this->MIN_LONG->DbValue = $row['MIN_LONG'];
		$this->SEG_LONG->DbValue = $row['SEG_LONG'];
		$this->FECHA_ACC->DbValue = $row['FECHA_ACC'];
		$this->HORA_ACC->DbValue = $row['HORA_ACC'];
		$this->Hora_ingreso->DbValue = $row['Hora_ingreso'];
		$this->FP_Armada->DbValue = $row['FP_Armada'];
		$this->FP_Ejercito->DbValue = $row['FP_Ejercito'];
		$this->FP_Policia->DbValue = $row['FP_Policia'];
		$this->NOM_COMANDANTE->DbValue = $row['NOM_COMANDANTE'];
		$this->TESTI1->DbValue = $row['TESTI1'];
		$this->CC_TESTI1->DbValue = $row['CC_TESTI1'];
		$this->CARGO_TESTI1->DbValue = $row['CARGO_TESTI1'];
		$this->TESTI2->DbValue = $row['TESTI2'];
		$this->CC_TESTI2->DbValue = $row['CC_TESTI2'];
		$this->CARGO_TESTI2->DbValue = $row['CARGO_TESTI2'];
		$this->Afectados->DbValue = $row['Afectados'];
		$this->NUM_Afectado->DbValue = $row['NUM_Afectado'];
		$this->Nom_Afectado->DbValue = $row['Nom_Afectado'];
		$this->CC_Afectado->DbValue = $row['CC_Afectado'];
		$this->Cargo_Afectado->DbValue = $row['Cargo_Afectado'];
		$this->Tipo_incidente->DbValue = $row['Tipo_incidente'];
		$this->Parte_Cuerpo->DbValue = $row['Parte_Cuerpo'];
		$this->ESTADO_AFEC->DbValue = $row['ESTADO_AFEC'];
		$this->EVACUADO->DbValue = $row['EVACUADO'];
		$this->DESC_ACC->DbValue = $row['DESC_ACC'];
		$this->Modificado->DbValue = $row['Modificado'];
		$this->llave_2->DbValue = $row['llave_2'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->SEG_LAT->FormValue == $this->SEG_LAT->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LAT->CurrentValue)))
			$this->SEG_LAT->CurrentValue = ew_StrToFloat($this->SEG_LAT->CurrentValue);

		// Convert decimal values if posted back
		if ($this->SEG_LONG->FormValue == $this->SEG_LONG->CurrentValue && is_numeric(ew_StrToFloat($this->SEG_LONG->CurrentValue)))
			$this->SEG_LONG->CurrentValue = ew_StrToFloat($this->SEG_LONG->CurrentValue);

		// Convert decimal values if posted back
		if ($this->FP_Armada->FormValue == $this->FP_Armada->CurrentValue && is_numeric(ew_StrToFloat($this->FP_Armada->CurrentValue)))
			$this->FP_Armada->CurrentValue = ew_StrToFloat($this->FP_Armada->CurrentValue);

		// Convert decimal values if posted back
		if ($this->FP_Ejercito->FormValue == $this->FP_Ejercito->CurrentValue && is_numeric(ew_StrToFloat($this->FP_Ejercito->CurrentValue)))
			$this->FP_Ejercito->CurrentValue = ew_StrToFloat($this->FP_Ejercito->CurrentValue);

		// Convert decimal values if posted back
		if ($this->FP_Policia->FormValue == $this->FP_Policia->CurrentValue && is_numeric(ew_StrToFloat($this->FP_Policia->CurrentValue)))
			$this->FP_Policia->CurrentValue = ew_StrToFloat($this->FP_Policia->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// llave
		// F_Sincron
		// USUARIO
		// Cargo_gme
		// NOM_PE
		// Otro_PE
		// NOM_APOYO
		// Otro_Nom_Apoyo
		// Otro_CC_Apoyo
		// NOM_ENLACE
		// Otro_Nom_Enlace
		// Otro_CC_Enlace
		// NOM_PGE
		// Otro_Nom_PGE
		// Otro_CC_PGE
		// Departamento
		// Muncipio
		// NOM_VDA
		// LATITUD
		// GRA_LAT
		// MIN_LAT
		// SEG_LAT
		// GRA_LONG
		// MIN_LONG
		// SEG_LONG
		// FECHA_ACC
		// HORA_ACC
		// Hora_ingreso
		// FP_Armada
		// FP_Ejercito
		// FP_Policia
		// NOM_COMANDANTE
		// TESTI1
		// CC_TESTI1
		// CARGO_TESTI1
		// TESTI2
		// CC_TESTI2
		// CARGO_TESTI2
		// Afectados
		// NUM_Afectado
		// Nom_Afectado
		// CC_Afectado
		// Cargo_Afectado
		// Tipo_incidente
		// Parte_Cuerpo
		// ESTADO_AFEC
		// EVACUADO
		// DESC_ACC
		// Modificado
		// llave_2

		$this->llave_2->CellCssStyle = "white-space: nowrap;";
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

			// NOM_PE
			if (strval($this->NOM_PE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PE`" . ew_SearchString("=", $this->NOM_PE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PE`, `NOM_PE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
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

			// NOM_APOYO
			$this->NOM_APOYO->ViewValue = $this->NOM_APOYO->CurrentValue;
			$this->NOM_APOYO->ViewCustomAttributes = "";

			// Otro_Nom_Apoyo
			$this->Otro_Nom_Apoyo->ViewValue = $this->Otro_Nom_Apoyo->CurrentValue;
			$this->Otro_Nom_Apoyo->ViewCustomAttributes = "";

			// Otro_CC_Apoyo
			$this->Otro_CC_Apoyo->ViewValue = $this->Otro_CC_Apoyo->CurrentValue;
			$this->Otro_CC_Apoyo->ViewCustomAttributes = "";

			// NOM_ENLACE
			$this->NOM_ENLACE->ViewValue = $this->NOM_ENLACE->CurrentValue;
			$this->NOM_ENLACE->ViewCustomAttributes = "";

			// Otro_Nom_Enlace
			$this->Otro_Nom_Enlace->ViewValue = $this->Otro_Nom_Enlace->CurrentValue;
			$this->Otro_Nom_Enlace->ViewCustomAttributes = "";

			// Otro_CC_Enlace
			$this->Otro_CC_Enlace->ViewValue = $this->Otro_CC_Enlace->CurrentValue;
			$this->Otro_CC_Enlace->ViewCustomAttributes = "";

			// NOM_PGE
			if (strval($this->NOM_PGE->CurrentValue) <> "") {
				$sFilterWrk = "`NOM_PGE`" . ew_SearchString("=", $this->NOM_PGE->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `NOM_PGE`, `NOM_PGE` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
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

			// Otro_Nom_PGE
			$this->Otro_Nom_PGE->ViewValue = $this->Otro_Nom_PGE->CurrentValue;
			$this->Otro_Nom_PGE->ViewCustomAttributes = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->ViewValue = $this->Otro_CC_PGE->CurrentValue;
			$this->Otro_CC_PGE->ViewCustomAttributes = "";

			// Departamento
			$this->Departamento->ViewValue = $this->Departamento->CurrentValue;
			$this->Departamento->ViewCustomAttributes = "";

			// Muncipio
			$this->Muncipio->ViewValue = $this->Muncipio->CurrentValue;
			$this->Muncipio->ViewCustomAttributes = "";

			// NOM_VDA
			$this->NOM_VDA->ViewValue = $this->NOM_VDA->CurrentValue;
			$this->NOM_VDA->ViewCustomAttributes = "";

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

			// FECHA_ACC
			$this->FECHA_ACC->ViewValue = $this->FECHA_ACC->CurrentValue;
			$this->FECHA_ACC->ViewCustomAttributes = "";

			// HORA_ACC
			$this->HORA_ACC->ViewValue = $this->HORA_ACC->CurrentValue;
			$this->HORA_ACC->ViewCustomAttributes = "";

			// Hora_ingreso
			$this->Hora_ingreso->ViewValue = $this->Hora_ingreso->CurrentValue;
			$this->Hora_ingreso->ViewCustomAttributes = "";

			// FP_Armada
			$this->FP_Armada->ViewValue = $this->FP_Armada->CurrentValue;
			$this->FP_Armada->ViewCustomAttributes = "";

			// FP_Ejercito
			$this->FP_Ejercito->ViewValue = $this->FP_Ejercito->CurrentValue;
			$this->FP_Ejercito->ViewCustomAttributes = "";

			// FP_Policia
			$this->FP_Policia->ViewValue = $this->FP_Policia->CurrentValue;
			$this->FP_Policia->ViewCustomAttributes = "";

			// NOM_COMANDANTE
			$this->NOM_COMANDANTE->ViewValue = $this->NOM_COMANDANTE->CurrentValue;
			$this->NOM_COMANDANTE->ViewCustomAttributes = "";

			// TESTI1
			$this->TESTI1->ViewValue = $this->TESTI1->CurrentValue;
			$this->TESTI1->ViewCustomAttributes = "";

			// CC_TESTI1
			$this->CC_TESTI1->ViewValue = $this->CC_TESTI1->CurrentValue;
			$this->CC_TESTI1->ViewCustomAttributes = "";

			// CARGO_TESTI1
			$this->CARGO_TESTI1->ViewValue = $this->CARGO_TESTI1->CurrentValue;
			$this->CARGO_TESTI1->ViewCustomAttributes = "";

			// TESTI2
			$this->TESTI2->ViewValue = $this->TESTI2->CurrentValue;
			$this->TESTI2->ViewCustomAttributes = "";

			// CC_TESTI2
			$this->CC_TESTI2->ViewValue = $this->CC_TESTI2->CurrentValue;
			$this->CC_TESTI2->ViewCustomAttributes = "";

			// CARGO_TESTI2
			$this->CARGO_TESTI2->ViewValue = $this->CARGO_TESTI2->CurrentValue;
			$this->CARGO_TESTI2->ViewCustomAttributes = "";

			// Afectados
			$this->Afectados->ViewValue = $this->Afectados->CurrentValue;
			$this->Afectados->ViewCustomAttributes = "";

			// NUM_Afectado
			$this->NUM_Afectado->ViewValue = $this->NUM_Afectado->CurrentValue;
			$this->NUM_Afectado->ViewCustomAttributes = "";

			// Nom_Afectado
			$this->Nom_Afectado->ViewValue = $this->Nom_Afectado->CurrentValue;
			$this->Nom_Afectado->ViewCustomAttributes = "";

			// CC_Afectado
			$this->CC_Afectado->ViewValue = $this->CC_Afectado->CurrentValue;
			$this->CC_Afectado->ViewCustomAttributes = "";

			// Cargo_Afectado
			$this->Cargo_Afectado->ViewValue = $this->Cargo_Afectado->CurrentValue;
			$this->Cargo_Afectado->ViewCustomAttributes = "";

			// Tipo_incidente
			if (strval($this->Tipo_incidente->CurrentValue) <> "") {
				$sFilterWrk = "`Tipo_incidente`" . ew_SearchString("=", $this->Tipo_incidente->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT DISTINCT `Tipo_incidente`, `Tipo_incidente` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
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

			// Parte_Cuerpo
			$this->Parte_Cuerpo->ViewValue = $this->Parte_Cuerpo->CurrentValue;
			$this->Parte_Cuerpo->ViewCustomAttributes = "";

			// ESTADO_AFEC
			$this->ESTADO_AFEC->ViewValue = $this->ESTADO_AFEC->CurrentValue;
			$this->ESTADO_AFEC->ViewCustomAttributes = "";

			// EVACUADO
			if (strval($this->EVACUADO->CurrentValue) <> "") {
				$sFilterWrk = "`EVACUADO`" . ew_SearchString("=", $this->EVACUADO->CurrentValue, EW_DATATYPE_STRING);
			switch (@$gsLanguage) {
				case "en":
					$sSqlWrk = "SELECT `EVACUADO`, `EVACUADO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `EVACUADO`, `EVACUADO` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `view1_acc`";
					$sWhereWrk = "";
					break;
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->EVACUADO, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `EVACUADO` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->EVACUADO->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->EVACUADO->ViewValue = $this->EVACUADO->CurrentValue;
				}
			} else {
				$this->EVACUADO->ViewValue = NULL;
			}
			$this->EVACUADO->ViewCustomAttributes = "";

			// DESC_ACC
			$this->DESC_ACC->ViewValue = $this->DESC_ACC->CurrentValue;
			$this->DESC_ACC->ViewCustomAttributes = "";

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

			// NOM_ENLACE
			$this->NOM_ENLACE->LinkCustomAttributes = "";
			$this->NOM_ENLACE->HrefValue = "";
			$this->NOM_ENLACE->TooltipValue = "";

			// Otro_Nom_Enlace
			$this->Otro_Nom_Enlace->LinkCustomAttributes = "";
			$this->Otro_Nom_Enlace->HrefValue = "";
			$this->Otro_Nom_Enlace->TooltipValue = "";

			// Otro_CC_Enlace
			$this->Otro_CC_Enlace->LinkCustomAttributes = "";
			$this->Otro_CC_Enlace->HrefValue = "";
			$this->Otro_CC_Enlace->TooltipValue = "";

			// NOM_PGE
			$this->NOM_PGE->LinkCustomAttributes = "";
			$this->NOM_PGE->HrefValue = "";
			$this->NOM_PGE->TooltipValue = "";

			// Otro_Nom_PGE
			$this->Otro_Nom_PGE->LinkCustomAttributes = "";
			$this->Otro_Nom_PGE->HrefValue = "";
			$this->Otro_Nom_PGE->TooltipValue = "";

			// Otro_CC_PGE
			$this->Otro_CC_PGE->LinkCustomAttributes = "";
			$this->Otro_CC_PGE->HrefValue = "";
			$this->Otro_CC_PGE->TooltipValue = "";

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

			// FECHA_ACC
			$this->FECHA_ACC->LinkCustomAttributes = "";
			$this->FECHA_ACC->HrefValue = "";
			$this->FECHA_ACC->TooltipValue = "";

			// HORA_ACC
			$this->HORA_ACC->LinkCustomAttributes = "";
			$this->HORA_ACC->HrefValue = "";
			$this->HORA_ACC->TooltipValue = "";

			// Hora_ingreso
			$this->Hora_ingreso->LinkCustomAttributes = "";
			$this->Hora_ingreso->HrefValue = "";
			$this->Hora_ingreso->TooltipValue = "";

			// FP_Armada
			$this->FP_Armada->LinkCustomAttributes = "";
			$this->FP_Armada->HrefValue = "";
			$this->FP_Armada->TooltipValue = "";

			// FP_Ejercito
			$this->FP_Ejercito->LinkCustomAttributes = "";
			$this->FP_Ejercito->HrefValue = "";
			$this->FP_Ejercito->TooltipValue = "";

			// FP_Policia
			$this->FP_Policia->LinkCustomAttributes = "";
			$this->FP_Policia->HrefValue = "";
			$this->FP_Policia->TooltipValue = "";

			// NOM_COMANDANTE
			$this->NOM_COMANDANTE->LinkCustomAttributes = "";
			$this->NOM_COMANDANTE->HrefValue = "";
			$this->NOM_COMANDANTE->TooltipValue = "";

			// TESTI1
			$this->TESTI1->LinkCustomAttributes = "";
			$this->TESTI1->HrefValue = "";
			$this->TESTI1->TooltipValue = "";

			// CC_TESTI1
			$this->CC_TESTI1->LinkCustomAttributes = "";
			$this->CC_TESTI1->HrefValue = "";
			$this->CC_TESTI1->TooltipValue = "";

			// CARGO_TESTI1
			$this->CARGO_TESTI1->LinkCustomAttributes = "";
			$this->CARGO_TESTI1->HrefValue = "";
			$this->CARGO_TESTI1->TooltipValue = "";

			// TESTI2
			$this->TESTI2->LinkCustomAttributes = "";
			$this->TESTI2->HrefValue = "";
			$this->TESTI2->TooltipValue = "";

			// CC_TESTI2
			$this->CC_TESTI2->LinkCustomAttributes = "";
			$this->CC_TESTI2->HrefValue = "";
			$this->CC_TESTI2->TooltipValue = "";

			// CARGO_TESTI2
			$this->CARGO_TESTI2->LinkCustomAttributes = "";
			$this->CARGO_TESTI2->HrefValue = "";
			$this->CARGO_TESTI2->TooltipValue = "";

			// Afectados
			$this->Afectados->LinkCustomAttributes = "";
			$this->Afectados->HrefValue = "";
			$this->Afectados->TooltipValue = "";

			// NUM_Afectado
			$this->NUM_Afectado->LinkCustomAttributes = "";
			$this->NUM_Afectado->HrefValue = "";
			$this->NUM_Afectado->TooltipValue = "";

			// Nom_Afectado
			$this->Nom_Afectado->LinkCustomAttributes = "";
			$this->Nom_Afectado->HrefValue = "";
			$this->Nom_Afectado->TooltipValue = "";

			// CC_Afectado
			$this->CC_Afectado->LinkCustomAttributes = "";
			$this->CC_Afectado->HrefValue = "";
			$this->CC_Afectado->TooltipValue = "";

			// Cargo_Afectado
			$this->Cargo_Afectado->LinkCustomAttributes = "";
			$this->Cargo_Afectado->HrefValue = "";
			$this->Cargo_Afectado->TooltipValue = "";

			// Tipo_incidente
			$this->Tipo_incidente->LinkCustomAttributes = "";
			$this->Tipo_incidente->HrefValue = "";
			$this->Tipo_incidente->TooltipValue = "";

			// Parte_Cuerpo
			$this->Parte_Cuerpo->LinkCustomAttributes = "";
			$this->Parte_Cuerpo->HrefValue = "";
			$this->Parte_Cuerpo->TooltipValue = "";

			// ESTADO_AFEC
			$this->ESTADO_AFEC->LinkCustomAttributes = "";
			$this->ESTADO_AFEC->HrefValue = "";
			$this->ESTADO_AFEC->TooltipValue = "";

			// EVACUADO
			$this->EVACUADO->LinkCustomAttributes = "";
			$this->EVACUADO->HrefValue = "";
			$this->EVACUADO->TooltipValue = "";

			// DESC_ACC
			$this->DESC_ACC->LinkCustomAttributes = "";
			$this->DESC_ACC->HrefValue = "";
			$this->DESC_ACC->TooltipValue = "";

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
				$sThisKey .= $row['llave_2'];
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
		$Breadcrumb->Add("list", $this->TableVar, "view1_acclist.php", "", $this->TableVar, TRUE);
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
if (!isset($view1_acc_delete)) $view1_acc_delete = new cview1_acc_delete();

// Page init
$view1_acc_delete->Page_Init();

// Page main
$view1_acc_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_acc_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view1_acc_delete = new ew_Page("view1_acc_delete");
view1_acc_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = view1_acc_delete.PageID; // For backward compatibility

// Form object
var fview1_accdelete = new ew_Form("fview1_accdelete");

// Form_CustomValidate event
fview1_accdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1_accdelete.ValidateRequired = true;
<?php } else { ?>
fview1_accdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview1_accdelete.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_accdelete.Lists["x_NOM_PGE"] = {"LinkField":"x_NOM_PGE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PGE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_accdelete.Lists["x_Tipo_incidente"] = {"LinkField":"x_Tipo_incidente","Ajax":null,"AutoFill":false,"DisplayFields":["x_Tipo_incidente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview1_accdelete.Lists["x_EVACUADO"] = {"LinkField":"x_EVACUADO","Ajax":null,"AutoFill":false,"DisplayFields":["x_EVACUADO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($view1_acc_delete->Recordset = $view1_acc_delete->LoadRecordset())
	$view1_acc_deleteTotalRecs = $view1_acc_delete->Recordset->RecordCount(); // Get record count
if ($view1_acc_deleteTotalRecs <= 0) { // No record found, exit
	if ($view1_acc_delete->Recordset)
		$view1_acc_delete->Recordset->Close();
	$view1_acc_delete->Page_Terminate("view1_acclist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $view1_acc_delete->ShowPageHeader(); ?>
<?php
$view1_acc_delete->ShowMessage();
?>
<form name="fview1_accdelete" id="fview1_accdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view1_acc_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view1_acc_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view1_acc">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($view1_acc_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $view1_acc->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($view1_acc->llave->Visible) { // llave ?>
		<th><span id="elh_view1_acc_llave" class="view1_acc_llave"><?php echo $view1_acc->llave->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->F_Sincron->Visible) { // F_Sincron ?>
		<th><span id="elh_view1_acc_F_Sincron" class="view1_acc_F_Sincron"><?php echo $view1_acc->F_Sincron->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->USUARIO->Visible) { // USUARIO ?>
		<th><span id="elh_view1_acc_USUARIO" class="view1_acc_USUARIO"><?php echo $view1_acc->USUARIO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Cargo_gme->Visible) { // Cargo_gme ?>
		<th><span id="elh_view1_acc_Cargo_gme" class="view1_acc_Cargo_gme"><?php echo $view1_acc->Cargo_gme->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->NOM_PE->Visible) { // NOM_PE ?>
		<th><span id="elh_view1_acc_NOM_PE" class="view1_acc_NOM_PE"><?php echo $view1_acc->NOM_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Otro_PE->Visible) { // Otro_PE ?>
		<th><span id="elh_view1_acc_Otro_PE" class="view1_acc_Otro_PE"><?php echo $view1_acc->Otro_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->NOM_APOYO->Visible) { // NOM_APOYO ?>
		<th><span id="elh_view1_acc_NOM_APOYO" class="view1_acc_NOM_APOYO"><?php echo $view1_acc->NOM_APOYO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
		<th><span id="elh_view1_acc_Otro_Nom_Apoyo" class="view1_acc_Otro_Nom_Apoyo"><?php echo $view1_acc->Otro_Nom_Apoyo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
		<th><span id="elh_view1_acc_Otro_CC_Apoyo" class="view1_acc_Otro_CC_Apoyo"><?php echo $view1_acc->Otro_CC_Apoyo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->NOM_ENLACE->Visible) { // NOM_ENLACE ?>
		<th><span id="elh_view1_acc_NOM_ENLACE" class="view1_acc_NOM_ENLACE"><?php echo $view1_acc->NOM_ENLACE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_Enlace->Visible) { // Otro_Nom_Enlace ?>
		<th><span id="elh_view1_acc_Otro_Nom_Enlace" class="view1_acc_Otro_Nom_Enlace"><?php echo $view1_acc->Otro_Nom_Enlace->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Otro_CC_Enlace->Visible) { // Otro_CC_Enlace ?>
		<th><span id="elh_view1_acc_Otro_CC_Enlace" class="view1_acc_Otro_CC_Enlace"><?php echo $view1_acc->Otro_CC_Enlace->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->NOM_PGE->Visible) { // NOM_PGE ?>
		<th><span id="elh_view1_acc_NOM_PGE" class="view1_acc_NOM_PGE"><?php echo $view1_acc->NOM_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_PGE->Visible) { // Otro_Nom_PGE ?>
		<th><span id="elh_view1_acc_Otro_Nom_PGE" class="view1_acc_Otro_Nom_PGE"><?php echo $view1_acc->Otro_Nom_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<th><span id="elh_view1_acc_Otro_CC_PGE" class="view1_acc_Otro_CC_PGE"><?php echo $view1_acc->Otro_CC_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Departamento->Visible) { // Departamento ?>
		<th><span id="elh_view1_acc_Departamento" class="view1_acc_Departamento"><?php echo $view1_acc->Departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Muncipio->Visible) { // Muncipio ?>
		<th><span id="elh_view1_acc_Muncipio" class="view1_acc_Muncipio"><?php echo $view1_acc->Muncipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->NOM_VDA->Visible) { // NOM_VDA ?>
		<th><span id="elh_view1_acc_NOM_VDA" class="view1_acc_NOM_VDA"><?php echo $view1_acc->NOM_VDA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->LATITUD->Visible) { // LATITUD ?>
		<th><span id="elh_view1_acc_LATITUD" class="view1_acc_LATITUD"><?php echo $view1_acc->LATITUD->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->GRA_LAT->Visible) { // GRA_LAT ?>
		<th><span id="elh_view1_acc_GRA_LAT" class="view1_acc_GRA_LAT"><?php echo $view1_acc->GRA_LAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->MIN_LAT->Visible) { // MIN_LAT ?>
		<th><span id="elh_view1_acc_MIN_LAT" class="view1_acc_MIN_LAT"><?php echo $view1_acc->MIN_LAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->SEG_LAT->Visible) { // SEG_LAT ?>
		<th><span id="elh_view1_acc_SEG_LAT" class="view1_acc_SEG_LAT"><?php echo $view1_acc->SEG_LAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->GRA_LONG->Visible) { // GRA_LONG ?>
		<th><span id="elh_view1_acc_GRA_LONG" class="view1_acc_GRA_LONG"><?php echo $view1_acc->GRA_LONG->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->MIN_LONG->Visible) { // MIN_LONG ?>
		<th><span id="elh_view1_acc_MIN_LONG" class="view1_acc_MIN_LONG"><?php echo $view1_acc->MIN_LONG->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->SEG_LONG->Visible) { // SEG_LONG ?>
		<th><span id="elh_view1_acc_SEG_LONG" class="view1_acc_SEG_LONG"><?php echo $view1_acc->SEG_LONG->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->FECHA_ACC->Visible) { // FECHA_ACC ?>
		<th><span id="elh_view1_acc_FECHA_ACC" class="view1_acc_FECHA_ACC"><?php echo $view1_acc->FECHA_ACC->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->HORA_ACC->Visible) { // HORA_ACC ?>
		<th><span id="elh_view1_acc_HORA_ACC" class="view1_acc_HORA_ACC"><?php echo $view1_acc->HORA_ACC->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Hora_ingreso->Visible) { // Hora_ingreso ?>
		<th><span id="elh_view1_acc_Hora_ingreso" class="view1_acc_Hora_ingreso"><?php echo $view1_acc->Hora_ingreso->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->FP_Armada->Visible) { // FP_Armada ?>
		<th><span id="elh_view1_acc_FP_Armada" class="view1_acc_FP_Armada"><?php echo $view1_acc->FP_Armada->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->FP_Ejercito->Visible) { // FP_Ejercito ?>
		<th><span id="elh_view1_acc_FP_Ejercito" class="view1_acc_FP_Ejercito"><?php echo $view1_acc->FP_Ejercito->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->FP_Policia->Visible) { // FP_Policia ?>
		<th><span id="elh_view1_acc_FP_Policia" class="view1_acc_FP_Policia"><?php echo $view1_acc->FP_Policia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->NOM_COMANDANTE->Visible) { // NOM_COMANDANTE ?>
		<th><span id="elh_view1_acc_NOM_COMANDANTE" class="view1_acc_NOM_COMANDANTE"><?php echo $view1_acc->NOM_COMANDANTE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->TESTI1->Visible) { // TESTI1 ?>
		<th><span id="elh_view1_acc_TESTI1" class="view1_acc_TESTI1"><?php echo $view1_acc->TESTI1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->CC_TESTI1->Visible) { // CC_TESTI1 ?>
		<th><span id="elh_view1_acc_CC_TESTI1" class="view1_acc_CC_TESTI1"><?php echo $view1_acc->CC_TESTI1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->CARGO_TESTI1->Visible) { // CARGO_TESTI1 ?>
		<th><span id="elh_view1_acc_CARGO_TESTI1" class="view1_acc_CARGO_TESTI1"><?php echo $view1_acc->CARGO_TESTI1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->TESTI2->Visible) { // TESTI2 ?>
		<th><span id="elh_view1_acc_TESTI2" class="view1_acc_TESTI2"><?php echo $view1_acc->TESTI2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->CC_TESTI2->Visible) { // CC_TESTI2 ?>
		<th><span id="elh_view1_acc_CC_TESTI2" class="view1_acc_CC_TESTI2"><?php echo $view1_acc->CC_TESTI2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->CARGO_TESTI2->Visible) { // CARGO_TESTI2 ?>
		<th><span id="elh_view1_acc_CARGO_TESTI2" class="view1_acc_CARGO_TESTI2"><?php echo $view1_acc->CARGO_TESTI2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Afectados->Visible) { // Afectados ?>
		<th><span id="elh_view1_acc_Afectados" class="view1_acc_Afectados"><?php echo $view1_acc->Afectados->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->NUM_Afectado->Visible) { // NUM_Afectado ?>
		<th><span id="elh_view1_acc_NUM_Afectado" class="view1_acc_NUM_Afectado"><?php echo $view1_acc->NUM_Afectado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Nom_Afectado->Visible) { // Nom_Afectado ?>
		<th><span id="elh_view1_acc_Nom_Afectado" class="view1_acc_Nom_Afectado"><?php echo $view1_acc->Nom_Afectado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->CC_Afectado->Visible) { // CC_Afectado ?>
		<th><span id="elh_view1_acc_CC_Afectado" class="view1_acc_CC_Afectado"><?php echo $view1_acc->CC_Afectado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Cargo_Afectado->Visible) { // Cargo_Afectado ?>
		<th><span id="elh_view1_acc_Cargo_Afectado" class="view1_acc_Cargo_Afectado"><?php echo $view1_acc->Cargo_Afectado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Tipo_incidente->Visible) { // Tipo_incidente ?>
		<th><span id="elh_view1_acc_Tipo_incidente" class="view1_acc_Tipo_incidente"><?php echo $view1_acc->Tipo_incidente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Parte_Cuerpo->Visible) { // Parte_Cuerpo ?>
		<th><span id="elh_view1_acc_Parte_Cuerpo" class="view1_acc_Parte_Cuerpo"><?php echo $view1_acc->Parte_Cuerpo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->ESTADO_AFEC->Visible) { // ESTADO_AFEC ?>
		<th><span id="elh_view1_acc_ESTADO_AFEC" class="view1_acc_ESTADO_AFEC"><?php echo $view1_acc->ESTADO_AFEC->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->EVACUADO->Visible) { // EVACUADO ?>
		<th><span id="elh_view1_acc_EVACUADO" class="view1_acc_EVACUADO"><?php echo $view1_acc->EVACUADO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->DESC_ACC->Visible) { // DESC_ACC ?>
		<th><span id="elh_view1_acc_DESC_ACC" class="view1_acc_DESC_ACC"><?php echo $view1_acc->DESC_ACC->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view1_acc->Modificado->Visible) { // Modificado ?>
		<th><span id="elh_view1_acc_Modificado" class="view1_acc_Modificado"><?php echo $view1_acc->Modificado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$view1_acc_delete->RecCnt = 0;
$i = 0;
while (!$view1_acc_delete->Recordset->EOF) {
	$view1_acc_delete->RecCnt++;
	$view1_acc_delete->RowCnt++;

	// Set row properties
	$view1_acc->ResetAttrs();
	$view1_acc->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$view1_acc_delete->LoadRowValues($view1_acc_delete->Recordset);

	// Render row
	$view1_acc_delete->RenderRow();
?>
	<tr<?php echo $view1_acc->RowAttributes() ?>>
<?php if ($view1_acc->llave->Visible) { // llave ?>
		<td<?php echo $view1_acc->llave->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_llave" class="view1_acc_llave">
<span<?php echo $view1_acc->llave->ViewAttributes() ?>>
<?php echo $view1_acc->llave->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->F_Sincron->Visible) { // F_Sincron ?>
		<td<?php echo $view1_acc->F_Sincron->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_F_Sincron" class="view1_acc_F_Sincron">
<span<?php echo $view1_acc->F_Sincron->ViewAttributes() ?>>
<?php echo $view1_acc->F_Sincron->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->USUARIO->Visible) { // USUARIO ?>
		<td<?php echo $view1_acc->USUARIO->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_USUARIO" class="view1_acc_USUARIO">
<span<?php echo $view1_acc->USUARIO->ViewAttributes() ?>>
<?php echo $view1_acc->USUARIO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Cargo_gme->Visible) { // Cargo_gme ?>
		<td<?php echo $view1_acc->Cargo_gme->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Cargo_gme" class="view1_acc_Cargo_gme">
<span<?php echo $view1_acc->Cargo_gme->ViewAttributes() ?>>
<?php echo $view1_acc->Cargo_gme->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->NOM_PE->Visible) { // NOM_PE ?>
		<td<?php echo $view1_acc->NOM_PE->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_NOM_PE" class="view1_acc_NOM_PE">
<span<?php echo $view1_acc->NOM_PE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Otro_PE->Visible) { // Otro_PE ?>
		<td<?php echo $view1_acc->Otro_PE->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Otro_PE" class="view1_acc_Otro_PE">
<span<?php echo $view1_acc->Otro_PE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->NOM_APOYO->Visible) { // NOM_APOYO ?>
		<td<?php echo $view1_acc->NOM_APOYO->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_NOM_APOYO" class="view1_acc_NOM_APOYO">
<span<?php echo $view1_acc->NOM_APOYO->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_APOYO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_Apoyo->Visible) { // Otro_Nom_Apoyo ?>
		<td<?php echo $view1_acc->Otro_Nom_Apoyo->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Otro_Nom_Apoyo" class="view1_acc_Otro_Nom_Apoyo">
<span<?php echo $view1_acc->Otro_Nom_Apoyo->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_Apoyo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Otro_CC_Apoyo->Visible) { // Otro_CC_Apoyo ?>
		<td<?php echo $view1_acc->Otro_CC_Apoyo->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Otro_CC_Apoyo" class="view1_acc_Otro_CC_Apoyo">
<span<?php echo $view1_acc->Otro_CC_Apoyo->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_Apoyo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->NOM_ENLACE->Visible) { // NOM_ENLACE ?>
		<td<?php echo $view1_acc->NOM_ENLACE->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_NOM_ENLACE" class="view1_acc_NOM_ENLACE">
<span<?php echo $view1_acc->NOM_ENLACE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_ENLACE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_Enlace->Visible) { // Otro_Nom_Enlace ?>
		<td<?php echo $view1_acc->Otro_Nom_Enlace->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Otro_Nom_Enlace" class="view1_acc_Otro_Nom_Enlace">
<span<?php echo $view1_acc->Otro_Nom_Enlace->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_Enlace->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Otro_CC_Enlace->Visible) { // Otro_CC_Enlace ?>
		<td<?php echo $view1_acc->Otro_CC_Enlace->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Otro_CC_Enlace" class="view1_acc_Otro_CC_Enlace">
<span<?php echo $view1_acc->Otro_CC_Enlace->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_Enlace->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->NOM_PGE->Visible) { // NOM_PGE ?>
		<td<?php echo $view1_acc->NOM_PGE->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_NOM_PGE" class="view1_acc_NOM_PGE">
<span<?php echo $view1_acc->NOM_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Otro_Nom_PGE->Visible) { // Otro_Nom_PGE ?>
		<td<?php echo $view1_acc->Otro_Nom_PGE->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Otro_Nom_PGE" class="view1_acc_Otro_Nom_PGE">
<span<?php echo $view1_acc->Otro_Nom_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_Nom_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<td<?php echo $view1_acc->Otro_CC_PGE->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Otro_CC_PGE" class="view1_acc_Otro_CC_PGE">
<span<?php echo $view1_acc->Otro_CC_PGE->ViewAttributes() ?>>
<?php echo $view1_acc->Otro_CC_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Departamento->Visible) { // Departamento ?>
		<td<?php echo $view1_acc->Departamento->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Departamento" class="view1_acc_Departamento">
<span<?php echo $view1_acc->Departamento->ViewAttributes() ?>>
<?php echo $view1_acc->Departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Muncipio->Visible) { // Muncipio ?>
		<td<?php echo $view1_acc->Muncipio->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Muncipio" class="view1_acc_Muncipio">
<span<?php echo $view1_acc->Muncipio->ViewAttributes() ?>>
<?php echo $view1_acc->Muncipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->NOM_VDA->Visible) { // NOM_VDA ?>
		<td<?php echo $view1_acc->NOM_VDA->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_NOM_VDA" class="view1_acc_NOM_VDA">
<span<?php echo $view1_acc->NOM_VDA->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_VDA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->LATITUD->Visible) { // LATITUD ?>
		<td<?php echo $view1_acc->LATITUD->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_LATITUD" class="view1_acc_LATITUD">
<span<?php echo $view1_acc->LATITUD->ViewAttributes() ?>>
<?php echo $view1_acc->LATITUD->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->GRA_LAT->Visible) { // GRA_LAT ?>
		<td<?php echo $view1_acc->GRA_LAT->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_GRA_LAT" class="view1_acc_GRA_LAT">
<span<?php echo $view1_acc->GRA_LAT->ViewAttributes() ?>>
<?php echo $view1_acc->GRA_LAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->MIN_LAT->Visible) { // MIN_LAT ?>
		<td<?php echo $view1_acc->MIN_LAT->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_MIN_LAT" class="view1_acc_MIN_LAT">
<span<?php echo $view1_acc->MIN_LAT->ViewAttributes() ?>>
<?php echo $view1_acc->MIN_LAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->SEG_LAT->Visible) { // SEG_LAT ?>
		<td<?php echo $view1_acc->SEG_LAT->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_SEG_LAT" class="view1_acc_SEG_LAT">
<span<?php echo $view1_acc->SEG_LAT->ViewAttributes() ?>>
<?php echo $view1_acc->SEG_LAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->GRA_LONG->Visible) { // GRA_LONG ?>
		<td<?php echo $view1_acc->GRA_LONG->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_GRA_LONG" class="view1_acc_GRA_LONG">
<span<?php echo $view1_acc->GRA_LONG->ViewAttributes() ?>>
<?php echo $view1_acc->GRA_LONG->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->MIN_LONG->Visible) { // MIN_LONG ?>
		<td<?php echo $view1_acc->MIN_LONG->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_MIN_LONG" class="view1_acc_MIN_LONG">
<span<?php echo $view1_acc->MIN_LONG->ViewAttributes() ?>>
<?php echo $view1_acc->MIN_LONG->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->SEG_LONG->Visible) { // SEG_LONG ?>
		<td<?php echo $view1_acc->SEG_LONG->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_SEG_LONG" class="view1_acc_SEG_LONG">
<span<?php echo $view1_acc->SEG_LONG->ViewAttributes() ?>>
<?php echo $view1_acc->SEG_LONG->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->FECHA_ACC->Visible) { // FECHA_ACC ?>
		<td<?php echo $view1_acc->FECHA_ACC->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_FECHA_ACC" class="view1_acc_FECHA_ACC">
<span<?php echo $view1_acc->FECHA_ACC->ViewAttributes() ?>>
<?php echo $view1_acc->FECHA_ACC->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->HORA_ACC->Visible) { // HORA_ACC ?>
		<td<?php echo $view1_acc->HORA_ACC->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_HORA_ACC" class="view1_acc_HORA_ACC">
<span<?php echo $view1_acc->HORA_ACC->ViewAttributes() ?>>
<?php echo $view1_acc->HORA_ACC->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Hora_ingreso->Visible) { // Hora_ingreso ?>
		<td<?php echo $view1_acc->Hora_ingreso->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Hora_ingreso" class="view1_acc_Hora_ingreso">
<span<?php echo $view1_acc->Hora_ingreso->ViewAttributes() ?>>
<?php echo $view1_acc->Hora_ingreso->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->FP_Armada->Visible) { // FP_Armada ?>
		<td<?php echo $view1_acc->FP_Armada->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_FP_Armada" class="view1_acc_FP_Armada">
<span<?php echo $view1_acc->FP_Armada->ViewAttributes() ?>>
<?php echo $view1_acc->FP_Armada->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->FP_Ejercito->Visible) { // FP_Ejercito ?>
		<td<?php echo $view1_acc->FP_Ejercito->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_FP_Ejercito" class="view1_acc_FP_Ejercito">
<span<?php echo $view1_acc->FP_Ejercito->ViewAttributes() ?>>
<?php echo $view1_acc->FP_Ejercito->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->FP_Policia->Visible) { // FP_Policia ?>
		<td<?php echo $view1_acc->FP_Policia->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_FP_Policia" class="view1_acc_FP_Policia">
<span<?php echo $view1_acc->FP_Policia->ViewAttributes() ?>>
<?php echo $view1_acc->FP_Policia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->NOM_COMANDANTE->Visible) { // NOM_COMANDANTE ?>
		<td<?php echo $view1_acc->NOM_COMANDANTE->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_NOM_COMANDANTE" class="view1_acc_NOM_COMANDANTE">
<span<?php echo $view1_acc->NOM_COMANDANTE->ViewAttributes() ?>>
<?php echo $view1_acc->NOM_COMANDANTE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->TESTI1->Visible) { // TESTI1 ?>
		<td<?php echo $view1_acc->TESTI1->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_TESTI1" class="view1_acc_TESTI1">
<span<?php echo $view1_acc->TESTI1->ViewAttributes() ?>>
<?php echo $view1_acc->TESTI1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->CC_TESTI1->Visible) { // CC_TESTI1 ?>
		<td<?php echo $view1_acc->CC_TESTI1->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_CC_TESTI1" class="view1_acc_CC_TESTI1">
<span<?php echo $view1_acc->CC_TESTI1->ViewAttributes() ?>>
<?php echo $view1_acc->CC_TESTI1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->CARGO_TESTI1->Visible) { // CARGO_TESTI1 ?>
		<td<?php echo $view1_acc->CARGO_TESTI1->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_CARGO_TESTI1" class="view1_acc_CARGO_TESTI1">
<span<?php echo $view1_acc->CARGO_TESTI1->ViewAttributes() ?>>
<?php echo $view1_acc->CARGO_TESTI1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->TESTI2->Visible) { // TESTI2 ?>
		<td<?php echo $view1_acc->TESTI2->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_TESTI2" class="view1_acc_TESTI2">
<span<?php echo $view1_acc->TESTI2->ViewAttributes() ?>>
<?php echo $view1_acc->TESTI2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->CC_TESTI2->Visible) { // CC_TESTI2 ?>
		<td<?php echo $view1_acc->CC_TESTI2->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_CC_TESTI2" class="view1_acc_CC_TESTI2">
<span<?php echo $view1_acc->CC_TESTI2->ViewAttributes() ?>>
<?php echo $view1_acc->CC_TESTI2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->CARGO_TESTI2->Visible) { // CARGO_TESTI2 ?>
		<td<?php echo $view1_acc->CARGO_TESTI2->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_CARGO_TESTI2" class="view1_acc_CARGO_TESTI2">
<span<?php echo $view1_acc->CARGO_TESTI2->ViewAttributes() ?>>
<?php echo $view1_acc->CARGO_TESTI2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Afectados->Visible) { // Afectados ?>
		<td<?php echo $view1_acc->Afectados->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Afectados" class="view1_acc_Afectados">
<span<?php echo $view1_acc->Afectados->ViewAttributes() ?>>
<?php echo $view1_acc->Afectados->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->NUM_Afectado->Visible) { // NUM_Afectado ?>
		<td<?php echo $view1_acc->NUM_Afectado->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_NUM_Afectado" class="view1_acc_NUM_Afectado">
<span<?php echo $view1_acc->NUM_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->NUM_Afectado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Nom_Afectado->Visible) { // Nom_Afectado ?>
		<td<?php echo $view1_acc->Nom_Afectado->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Nom_Afectado" class="view1_acc_Nom_Afectado">
<span<?php echo $view1_acc->Nom_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->Nom_Afectado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->CC_Afectado->Visible) { // CC_Afectado ?>
		<td<?php echo $view1_acc->CC_Afectado->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_CC_Afectado" class="view1_acc_CC_Afectado">
<span<?php echo $view1_acc->CC_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->CC_Afectado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Cargo_Afectado->Visible) { // Cargo_Afectado ?>
		<td<?php echo $view1_acc->Cargo_Afectado->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Cargo_Afectado" class="view1_acc_Cargo_Afectado">
<span<?php echo $view1_acc->Cargo_Afectado->ViewAttributes() ?>>
<?php echo $view1_acc->Cargo_Afectado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Tipo_incidente->Visible) { // Tipo_incidente ?>
		<td<?php echo $view1_acc->Tipo_incidente->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Tipo_incidente" class="view1_acc_Tipo_incidente">
<span<?php echo $view1_acc->Tipo_incidente->ViewAttributes() ?>>
<?php echo $view1_acc->Tipo_incidente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Parte_Cuerpo->Visible) { // Parte_Cuerpo ?>
		<td<?php echo $view1_acc->Parte_Cuerpo->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Parte_Cuerpo" class="view1_acc_Parte_Cuerpo">
<span<?php echo $view1_acc->Parte_Cuerpo->ViewAttributes() ?>>
<?php echo $view1_acc->Parte_Cuerpo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->ESTADO_AFEC->Visible) { // ESTADO_AFEC ?>
		<td<?php echo $view1_acc->ESTADO_AFEC->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_ESTADO_AFEC" class="view1_acc_ESTADO_AFEC">
<span<?php echo $view1_acc->ESTADO_AFEC->ViewAttributes() ?>>
<?php echo $view1_acc->ESTADO_AFEC->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->EVACUADO->Visible) { // EVACUADO ?>
		<td<?php echo $view1_acc->EVACUADO->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_EVACUADO" class="view1_acc_EVACUADO">
<span<?php echo $view1_acc->EVACUADO->ViewAttributes() ?>>
<?php echo $view1_acc->EVACUADO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->DESC_ACC->Visible) { // DESC_ACC ?>
		<td<?php echo $view1_acc->DESC_ACC->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_DESC_ACC" class="view1_acc_DESC_ACC">
<span<?php echo $view1_acc->DESC_ACC->ViewAttributes() ?>>
<?php echo $view1_acc->DESC_ACC->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view1_acc->Modificado->Visible) { // Modificado ?>
		<td<?php echo $view1_acc->Modificado->CellAttributes() ?>>
<span id="el<?php echo $view1_acc_delete->RowCnt ?>_view1_acc_Modificado" class="view1_acc_Modificado">
<span<?php echo $view1_acc->Modificado->ViewAttributes() ?>>
<?php echo $view1_acc->Modificado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$view1_acc_delete->Recordset->MoveNext();
}
$view1_acc_delete->Recordset->Close();
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
fview1_accdelete.Init();
</script>
<?php
$view1_acc_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view1_acc_delete->Page_Terminate();
?>
