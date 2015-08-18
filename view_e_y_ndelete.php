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

$view_e_y_n_delete = NULL; // Initialize page object first

class cview_e_y_n_delete extends cview_e_y_n {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_e_y_n';

	// Page object name
	var $PageObjName = 'view_e_y_n_delete';

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

		// Table object (view_e_y_n)
		if (!isset($GLOBALS["view_e_y_n"]) || get_class($GLOBALS["view_e_y_n"]) == "cview_e_y_n") {
			$GLOBALS["view_e_y_n"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_e_y_n"];
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
			define("EW_TABLE_NAME", 'view_e_y_n', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view_e_y_nlist.php"));
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
			$this->Page_Terminate("view_e_y_nlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in view_e_y_n class, view_e_y_ninfo.php

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
		$this->Modificado->setDbValue($rs->fields('Modificado'));
		$this->llave_2->setDbValue($rs->fields('llave_2'));
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
		$this->Modificado->DbValue = $row['Modificado'];
		$this->llave_2->DbValue = $row['llave_2'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
		// Modificado
		// llave_2

		$this->llave_2->CellCssStyle = "white-space: nowrap;";
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

			// Modificado
			$this->Modificado->ViewValue = $this->Modificado->CurrentValue;
			$this->Modificado->ViewCustomAttributes = "";

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
		$Breadcrumb->Add("list", $this->TableVar, "view_e_y_nlist.php", "", $this->TableVar, TRUE);
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
if (!isset($view_e_y_n_delete)) $view_e_y_n_delete = new cview_e_y_n_delete();

// Page init
$view_e_y_n_delete->Page_Init();

// Page main
$view_e_y_n_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_e_y_n_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_e_y_n_delete = new ew_Page("view_e_y_n_delete");
view_e_y_n_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = view_e_y_n_delete.PageID; // For backward compatibility

// Form object
var fview_e_y_ndelete = new ew_Form("fview_e_y_ndelete");

// Form_CustomValidate event
fview_e_y_ndelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_e_y_ndelete.ValidateRequired = true;
<?php } else { ?>
fview_e_y_ndelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($view_e_y_n_delete->Recordset = $view_e_y_n_delete->LoadRecordset())
	$view_e_y_n_deleteTotalRecs = $view_e_y_n_delete->Recordset->RecordCount(); // Get record count
if ($view_e_y_n_deleteTotalRecs <= 0) { // No record found, exit
	if ($view_e_y_n_delete->Recordset)
		$view_e_y_n_delete->Recordset->Close();
	$view_e_y_n_delete->Page_Terminate("view_e_y_nlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $view_e_y_n_delete->ShowPageHeader(); ?>
<?php
$view_e_y_n_delete->ShowMessage();
?>
<form name="fview_e_y_ndelete" id="fview_e_y_ndelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_e_y_n_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_e_y_n_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_e_y_n">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($view_e_y_n_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $view_e_y_n->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($view_e_y_n->ID_Formulario->Visible) { // ID_Formulario ?>
		<th><span id="elh_view_e_y_n_ID_Formulario" class="view_e_y_n_ID_Formulario"><?php echo $view_e_y_n->ID_Formulario->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->F_Sincron->Visible) { // F_Sincron ?>
		<th><span id="elh_view_e_y_n_F_Sincron" class="view_e_y_n_F_Sincron"><?php echo $view_e_y_n->F_Sincron->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->USUARIO->Visible) { // USUARIO ?>
		<th><span id="elh_view_e_y_n_USUARIO" class="view_e_y_n_USUARIO"><?php echo $view_e_y_n->USUARIO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Cargo_gme->Visible) { // Cargo_gme ?>
		<th><span id="elh_view_e_y_n_Cargo_gme" class="view_e_y_n_Cargo_gme"><?php echo $view_e_y_n->Cargo_gme->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->NOM_GE->Visible) { // NOM_GE ?>
		<th><span id="elh_view_e_y_n_NOM_GE" class="view_e_y_n_NOM_GE"><?php echo $view_e_y_n->NOM_GE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Otro_PGE->Visible) { // Otro_PGE ?>
		<th><span id="elh_view_e_y_n_Otro_PGE" class="view_e_y_n_Otro_PGE"><?php echo $view_e_y_n->Otro_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<th><span id="elh_view_e_y_n_Otro_CC_PGE" class="view_e_y_n_Otro_CC_PGE"><?php echo $view_e_y_n->Otro_CC_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
		<th><span id="elh_view_e_y_n_TIPO_INFORME" class="view_e_y_n_TIPO_INFORME"><?php echo $view_e_y_n->TIPO_INFORME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->FECHA_NOVEDAD->Visible) { // FECHA_NOVEDAD ?>
		<th><span id="elh_view_e_y_n_FECHA_NOVEDAD" class="view_e_y_n_FECHA_NOVEDAD"><?php echo $view_e_y_n->FECHA_NOVEDAD->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->DIA->Visible) { // DIA ?>
		<th><span id="elh_view_e_y_n_DIA" class="view_e_y_n_DIA"><?php echo $view_e_y_n->DIA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->MES->Visible) { // MES ?>
		<th><span id="elh_view_e_y_n_MES" class="view_e_y_n_MES"><?php echo $view_e_y_n->MES->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Num_Evacua->Visible) { // Num_Evacua ?>
		<th><span id="elh_view_e_y_n_Num_Evacua" class="view_e_y_n_Num_Evacua"><?php echo $view_e_y_n->Num_Evacua->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->PTO_INCOMU->Visible) { // PTO_INCOMU ?>
		<th><span id="elh_view_e_y_n_PTO_INCOMU" class="view_e_y_n_PTO_INCOMU"><?php echo $view_e_y_n->PTO_INCOMU->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->OBS_punt_inco->Visible) { // OBS_punt_inco ?>
		<th><span id="elh_view_e_y_n_OBS_punt_inco" class="view_e_y_n_OBS_punt_inco"><?php echo $view_e_y_n->OBS_punt_inco->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->OBS_ENLACE->Visible) { // OBS_ENLACE ?>
		<th><span id="elh_view_e_y_n_OBS_ENLACE" class="view_e_y_n_OBS_ENLACE"><?php echo $view_e_y_n->OBS_ENLACE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->NUM_Novedad->Visible) { // NUM_Novedad ?>
		<th><span id="elh_view_e_y_n_NUM_Novedad" class="view_e_y_n_NUM_Novedad"><?php echo $view_e_y_n->NUM_Novedad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Nom_Per_Evacu->Visible) { // Nom_Per_Evacu ?>
		<th><span id="elh_view_e_y_n_Nom_Per_Evacu" class="view_e_y_n_Nom_Per_Evacu"><?php echo $view_e_y_n->Nom_Per_Evacu->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Nom_Otro_Per_Evacu->Visible) { // Nom_Otro_Per_Evacu ?>
		<th><span id="elh_view_e_y_n_Nom_Otro_Per_Evacu" class="view_e_y_n_Nom_Otro_Per_Evacu"><?php echo $view_e_y_n->Nom_Otro_Per_Evacu->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->CC_Otro_Per_Evacu->Visible) { // CC_Otro_Per_Evacu ?>
		<th><span id="elh_view_e_y_n_CC_Otro_Per_Evacu" class="view_e_y_n_CC_Otro_Per_Evacu"><?php echo $view_e_y_n->CC_Otro_Per_Evacu->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Cargo_Per_EVA->Visible) { // Cargo_Per_EVA ?>
		<th><span id="elh_view_e_y_n_Cargo_Per_EVA" class="view_e_y_n_Cargo_Per_EVA"><?php echo $view_e_y_n->Cargo_Per_EVA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Motivo_Eva->Visible) { // Motivo_Eva ?>
		<th><span id="elh_view_e_y_n_Motivo_Eva" class="view_e_y_n_Motivo_Eva"><?php echo $view_e_y_n->Motivo_Eva->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->OBS_EVA->Visible) { // OBS_EVA ?>
		<th><span id="elh_view_e_y_n_OBS_EVA" class="view_e_y_n_OBS_EVA"><?php echo $view_e_y_n->OBS_EVA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->NOM_PE->Visible) { // NOM_PE ?>
		<th><span id="elh_view_e_y_n_NOM_PE" class="view_e_y_n_NOM_PE"><?php echo $view_e_y_n->NOM_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Otro_Nom_PE->Visible) { // Otro_Nom_PE ?>
		<th><span id="elh_view_e_y_n_Otro_Nom_PE" class="view_e_y_n_Otro_Nom_PE"><?php echo $view_e_y_n->Otro_Nom_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
		<th><span id="elh_view_e_y_n_NOM_CAPATAZ" class="view_e_y_n_NOM_CAPATAZ"><?php echo $view_e_y_n->NOM_CAPATAZ->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Otro_Nom_Capata->Visible) { // Otro_Nom_Capata ?>
		<th><span id="elh_view_e_y_n_Otro_Nom_Capata" class="view_e_y_n_Otro_Nom_Capata"><?php echo $view_e_y_n->Otro_Nom_Capata->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Otro_CC_Capata->Visible) { // Otro_CC_Capata ?>
		<th><span id="elh_view_e_y_n_Otro_CC_Capata" class="view_e_y_n_Otro_CC_Capata"><?php echo $view_e_y_n->Otro_CC_Capata->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Muncipio->Visible) { // Muncipio ?>
		<th><span id="elh_view_e_y_n_Muncipio" class="view_e_y_n_Muncipio"><?php echo $view_e_y_n->Muncipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Departamento->Visible) { // Departamento ?>
		<th><span id="elh_view_e_y_n_Departamento" class="view_e_y_n_Departamento"><?php echo $view_e_y_n->Departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->F_llegada->Visible) { // F_llegada ?>
		<th><span id="elh_view_e_y_n_F_llegada" class="view_e_y_n_F_llegada"><?php echo $view_e_y_n->F_llegada->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Fecha->Visible) { // Fecha ?>
		<th><span id="elh_view_e_y_n_Fecha" class="view_e_y_n_Fecha"><?php echo $view_e_y_n->Fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_e_y_n->Modificado->Visible) { // Modificado ?>
		<th><span id="elh_view_e_y_n_Modificado" class="view_e_y_n_Modificado"><?php echo $view_e_y_n->Modificado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$view_e_y_n_delete->RecCnt = 0;
$i = 0;
while (!$view_e_y_n_delete->Recordset->EOF) {
	$view_e_y_n_delete->RecCnt++;
	$view_e_y_n_delete->RowCnt++;

	// Set row properties
	$view_e_y_n->ResetAttrs();
	$view_e_y_n->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$view_e_y_n_delete->LoadRowValues($view_e_y_n_delete->Recordset);

	// Render row
	$view_e_y_n_delete->RenderRow();
?>
	<tr<?php echo $view_e_y_n->RowAttributes() ?>>
<?php if ($view_e_y_n->ID_Formulario->Visible) { // ID_Formulario ?>
		<td<?php echo $view_e_y_n->ID_Formulario->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_ID_Formulario" class="view_e_y_n_ID_Formulario">
<span<?php echo $view_e_y_n->ID_Formulario->ViewAttributes() ?>>
<?php echo $view_e_y_n->ID_Formulario->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->F_Sincron->Visible) { // F_Sincron ?>
		<td<?php echo $view_e_y_n->F_Sincron->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_F_Sincron" class="view_e_y_n_F_Sincron">
<span<?php echo $view_e_y_n->F_Sincron->ViewAttributes() ?>>
<?php echo $view_e_y_n->F_Sincron->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->USUARIO->Visible) { // USUARIO ?>
		<td<?php echo $view_e_y_n->USUARIO->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_USUARIO" class="view_e_y_n_USUARIO">
<span<?php echo $view_e_y_n->USUARIO->ViewAttributes() ?>>
<?php echo $view_e_y_n->USUARIO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Cargo_gme->Visible) { // Cargo_gme ?>
		<td<?php echo $view_e_y_n->Cargo_gme->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Cargo_gme" class="view_e_y_n_Cargo_gme">
<span<?php echo $view_e_y_n->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_e_y_n->Cargo_gme->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->NOM_GE->Visible) { // NOM_GE ?>
		<td<?php echo $view_e_y_n->NOM_GE->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_NOM_GE" class="view_e_y_n_NOM_GE">
<span<?php echo $view_e_y_n->NOM_GE->ViewAttributes() ?>>
<?php echo $view_e_y_n->NOM_GE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Otro_PGE->Visible) { // Otro_PGE ?>
		<td<?php echo $view_e_y_n->Otro_PGE->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Otro_PGE" class="view_e_y_n_Otro_PGE">
<span<?php echo $view_e_y_n->Otro_PGE->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<td<?php echo $view_e_y_n->Otro_CC_PGE->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Otro_CC_PGE" class="view_e_y_n_Otro_CC_PGE">
<span<?php echo $view_e_y_n->Otro_CC_PGE->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_CC_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
		<td<?php echo $view_e_y_n->TIPO_INFORME->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_TIPO_INFORME" class="view_e_y_n_TIPO_INFORME">
<span<?php echo $view_e_y_n->TIPO_INFORME->ViewAttributes() ?>>
<?php echo $view_e_y_n->TIPO_INFORME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->FECHA_NOVEDAD->Visible) { // FECHA_NOVEDAD ?>
		<td<?php echo $view_e_y_n->FECHA_NOVEDAD->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_FECHA_NOVEDAD" class="view_e_y_n_FECHA_NOVEDAD">
<span<?php echo $view_e_y_n->FECHA_NOVEDAD->ViewAttributes() ?>>
<?php echo $view_e_y_n->FECHA_NOVEDAD->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->DIA->Visible) { // DIA ?>
		<td<?php echo $view_e_y_n->DIA->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_DIA" class="view_e_y_n_DIA">
<span<?php echo $view_e_y_n->DIA->ViewAttributes() ?>>
<?php echo $view_e_y_n->DIA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->MES->Visible) { // MES ?>
		<td<?php echo $view_e_y_n->MES->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_MES" class="view_e_y_n_MES">
<span<?php echo $view_e_y_n->MES->ViewAttributes() ?>>
<?php echo $view_e_y_n->MES->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Num_Evacua->Visible) { // Num_Evacua ?>
		<td<?php echo $view_e_y_n->Num_Evacua->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Num_Evacua" class="view_e_y_n_Num_Evacua">
<span<?php echo $view_e_y_n->Num_Evacua->ViewAttributes() ?>>
<?php echo $view_e_y_n->Num_Evacua->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->PTO_INCOMU->Visible) { // PTO_INCOMU ?>
		<td<?php echo $view_e_y_n->PTO_INCOMU->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_PTO_INCOMU" class="view_e_y_n_PTO_INCOMU">
<span<?php echo $view_e_y_n->PTO_INCOMU->ViewAttributes() ?>>
<?php echo $view_e_y_n->PTO_INCOMU->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->OBS_punt_inco->Visible) { // OBS_punt_inco ?>
		<td<?php echo $view_e_y_n->OBS_punt_inco->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_OBS_punt_inco" class="view_e_y_n_OBS_punt_inco">
<span<?php echo $view_e_y_n->OBS_punt_inco->ViewAttributes() ?>>
<?php echo $view_e_y_n->OBS_punt_inco->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->OBS_ENLACE->Visible) { // OBS_ENLACE ?>
		<td<?php echo $view_e_y_n->OBS_ENLACE->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_OBS_ENLACE" class="view_e_y_n_OBS_ENLACE">
<span<?php echo $view_e_y_n->OBS_ENLACE->ViewAttributes() ?>>
<?php echo $view_e_y_n->OBS_ENLACE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->NUM_Novedad->Visible) { // NUM_Novedad ?>
		<td<?php echo $view_e_y_n->NUM_Novedad->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_NUM_Novedad" class="view_e_y_n_NUM_Novedad">
<span<?php echo $view_e_y_n->NUM_Novedad->ViewAttributes() ?>>
<?php echo $view_e_y_n->NUM_Novedad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Nom_Per_Evacu->Visible) { // Nom_Per_Evacu ?>
		<td<?php echo $view_e_y_n->Nom_Per_Evacu->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Nom_Per_Evacu" class="view_e_y_n_Nom_Per_Evacu">
<span<?php echo $view_e_y_n->Nom_Per_Evacu->ViewAttributes() ?>>
<?php echo $view_e_y_n->Nom_Per_Evacu->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Nom_Otro_Per_Evacu->Visible) { // Nom_Otro_Per_Evacu ?>
		<td<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Nom_Otro_Per_Evacu" class="view_e_y_n_Nom_Otro_Per_Evacu">
<span<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->ViewAttributes() ?>>
<?php echo $view_e_y_n->Nom_Otro_Per_Evacu->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->CC_Otro_Per_Evacu->Visible) { // CC_Otro_Per_Evacu ?>
		<td<?php echo $view_e_y_n->CC_Otro_Per_Evacu->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_CC_Otro_Per_Evacu" class="view_e_y_n_CC_Otro_Per_Evacu">
<span<?php echo $view_e_y_n->CC_Otro_Per_Evacu->ViewAttributes() ?>>
<?php echo $view_e_y_n->CC_Otro_Per_Evacu->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Cargo_Per_EVA->Visible) { // Cargo_Per_EVA ?>
		<td<?php echo $view_e_y_n->Cargo_Per_EVA->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Cargo_Per_EVA" class="view_e_y_n_Cargo_Per_EVA">
<span<?php echo $view_e_y_n->Cargo_Per_EVA->ViewAttributes() ?>>
<?php echo $view_e_y_n->Cargo_Per_EVA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Motivo_Eva->Visible) { // Motivo_Eva ?>
		<td<?php echo $view_e_y_n->Motivo_Eva->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Motivo_Eva" class="view_e_y_n_Motivo_Eva">
<span<?php echo $view_e_y_n->Motivo_Eva->ViewAttributes() ?>>
<?php echo $view_e_y_n->Motivo_Eva->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->OBS_EVA->Visible) { // OBS_EVA ?>
		<td<?php echo $view_e_y_n->OBS_EVA->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_OBS_EVA" class="view_e_y_n_OBS_EVA">
<span<?php echo $view_e_y_n->OBS_EVA->ViewAttributes() ?>>
<?php echo $view_e_y_n->OBS_EVA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->NOM_PE->Visible) { // NOM_PE ?>
		<td<?php echo $view_e_y_n->NOM_PE->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_NOM_PE" class="view_e_y_n_NOM_PE">
<span<?php echo $view_e_y_n->NOM_PE->ViewAttributes() ?>>
<?php echo $view_e_y_n->NOM_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Otro_Nom_PE->Visible) { // Otro_Nom_PE ?>
		<td<?php echo $view_e_y_n->Otro_Nom_PE->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Otro_Nom_PE" class="view_e_y_n_Otro_Nom_PE">
<span<?php echo $view_e_y_n->Otro_Nom_PE->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_Nom_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->NOM_CAPATAZ->Visible) { // NOM_CAPATAZ ?>
		<td<?php echo $view_e_y_n->NOM_CAPATAZ->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_NOM_CAPATAZ" class="view_e_y_n_NOM_CAPATAZ">
<span<?php echo $view_e_y_n->NOM_CAPATAZ->ViewAttributes() ?>>
<?php echo $view_e_y_n->NOM_CAPATAZ->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Otro_Nom_Capata->Visible) { // Otro_Nom_Capata ?>
		<td<?php echo $view_e_y_n->Otro_Nom_Capata->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Otro_Nom_Capata" class="view_e_y_n_Otro_Nom_Capata">
<span<?php echo $view_e_y_n->Otro_Nom_Capata->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_Nom_Capata->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Otro_CC_Capata->Visible) { // Otro_CC_Capata ?>
		<td<?php echo $view_e_y_n->Otro_CC_Capata->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Otro_CC_Capata" class="view_e_y_n_Otro_CC_Capata">
<span<?php echo $view_e_y_n->Otro_CC_Capata->ViewAttributes() ?>>
<?php echo $view_e_y_n->Otro_CC_Capata->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Muncipio->Visible) { // Muncipio ?>
		<td<?php echo $view_e_y_n->Muncipio->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Muncipio" class="view_e_y_n_Muncipio">
<span<?php echo $view_e_y_n->Muncipio->ViewAttributes() ?>>
<?php echo $view_e_y_n->Muncipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Departamento->Visible) { // Departamento ?>
		<td<?php echo $view_e_y_n->Departamento->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Departamento" class="view_e_y_n_Departamento">
<span<?php echo $view_e_y_n->Departamento->ViewAttributes() ?>>
<?php echo $view_e_y_n->Departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->F_llegada->Visible) { // F_llegada ?>
		<td<?php echo $view_e_y_n->F_llegada->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_F_llegada" class="view_e_y_n_F_llegada">
<span<?php echo $view_e_y_n->F_llegada->ViewAttributes() ?>>
<?php echo $view_e_y_n->F_llegada->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Fecha->Visible) { // Fecha ?>
		<td<?php echo $view_e_y_n->Fecha->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Fecha" class="view_e_y_n_Fecha">
<span<?php echo $view_e_y_n->Fecha->ViewAttributes() ?>>
<?php echo $view_e_y_n->Fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_e_y_n->Modificado->Visible) { // Modificado ?>
		<td<?php echo $view_e_y_n->Modificado->CellAttributes() ?>>
<span id="el<?php echo $view_e_y_n_delete->RowCnt ?>_view_e_y_n_Modificado" class="view_e_y_n_Modificado">
<span<?php echo $view_e_y_n->Modificado->ViewAttributes() ?>>
<?php echo $view_e_y_n->Modificado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$view_e_y_n_delete->Recordset->MoveNext();
}
$view_e_y_n_delete->Recordset->Close();
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
fview_e_y_ndelete.Init();
</script>
<?php
$view_e_y_n_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_e_y_n_delete->Page_Terminate();
?>
