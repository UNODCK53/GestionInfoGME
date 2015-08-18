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

$view_inv_delete = NULL; // Initialize page object first

class cview_inv_delete extends cview_inv {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_inv';

	// Page object name
	var $PageObjName = 'view_inv_delete';

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

		// Table object (view_inv)
		if (!isset($GLOBALS["view_inv"]) || get_class($GLOBALS["view_inv"]) == "cview_inv") {
			$GLOBALS["view_inv"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_inv"];
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
			define("EW_TABLE_NAME", 'view_inv', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view_invlist.php"));
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
			$this->Page_Terminate("view_invlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in view_inv class, view_invinfo.php

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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
			$this->F_Sincron->ViewValue = ew_FormatDateTime($this->F_Sincron->ViewValue, 7);
			$this->F_Sincron->ViewCustomAttributes = "";

			// USUARIO
			$this->USUARIO->ViewValue = $this->USUARIO->CurrentValue;
			$this->USUARIO->ViewCustomAttributes = "";

			// Cargo_gme
			$this->Cargo_gme->ViewValue = $this->Cargo_gme->CurrentValue;
			$this->Cargo_gme->ViewCustomAttributes = "";

			// NOM_PE
			$this->NOM_PE->ViewValue = $this->NOM_PE->CurrentValue;
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
			$this->AD1O->ViewValue = $this->AD1O->CurrentValue;
			$this->AD1O->ViewCustomAttributes = "";

			// FASE
			$this->FASE->ViewValue = $this->FASE->CurrentValue;
			$this->FASE->ViewCustomAttributes = "";

			// FECHA_INV
			$this->FECHA_INV->ViewValue = $this->FECHA_INV->CurrentValue;
			$this->FECHA_INV->ViewCustomAttributes = "";

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
		$Breadcrumb->Add("list", $this->TableVar, "view_invlist.php", "", $this->TableVar, TRUE);
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
if (!isset($view_inv_delete)) $view_inv_delete = new cview_inv_delete();

// Page init
$view_inv_delete->Page_Init();

// Page main
$view_inv_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_inv_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_inv_delete = new ew_Page("view_inv_delete");
view_inv_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = view_inv_delete.PageID; // For backward compatibility

// Form object
var fview_invdelete = new ew_Form("fview_invdelete");

// Form_CustomValidate event
fview_invdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_invdelete.ValidateRequired = true;
<?php } else { ?>
fview_invdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($view_inv_delete->Recordset = $view_inv_delete->LoadRecordset())
	$view_inv_deleteTotalRecs = $view_inv_delete->Recordset->RecordCount(); // Get record count
if ($view_inv_deleteTotalRecs <= 0) { // No record found, exit
	if ($view_inv_delete->Recordset)
		$view_inv_delete->Recordset->Close();
	$view_inv_delete->Page_Terminate("view_invlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $view_inv_delete->ShowPageHeader(); ?>
<?php
$view_inv_delete->ShowMessage();
?>
<form name="fview_invdelete" id="fview_invdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_inv_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_inv_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_inv">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($view_inv_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $view_inv->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($view_inv->llave->Visible) { // llave ?>
		<th><span id="elh_view_inv_llave" class="view_inv_llave"><?php echo $view_inv->llave->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->F_Sincron->Visible) { // F_Sincron ?>
		<th><span id="elh_view_inv_F_Sincron" class="view_inv_F_Sincron"><?php echo $view_inv->F_Sincron->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->USUARIO->Visible) { // USUARIO ?>
		<th><span id="elh_view_inv_USUARIO" class="view_inv_USUARIO"><?php echo $view_inv->USUARIO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Cargo_gme->Visible) { // Cargo_gme ?>
		<th><span id="elh_view_inv_Cargo_gme" class="view_inv_Cargo_gme"><?php echo $view_inv->Cargo_gme->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->NOM_PE->Visible) { // NOM_PE ?>
		<th><span id="elh_view_inv_NOM_PE" class="view_inv_NOM_PE"><?php echo $view_inv->NOM_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Otro_PE->Visible) { // Otro_PE ?>
		<th><span id="elh_view_inv_Otro_PE" class="view_inv_Otro_PE"><?php echo $view_inv->Otro_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->DIA->Visible) { // DIA ?>
		<th><span id="elh_view_inv_DIA" class="view_inv_DIA"><?php echo $view_inv->DIA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->MES->Visible) { // MES ?>
		<th><span id="elh_view_inv_MES" class="view_inv_MES"><?php echo $view_inv->MES->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->OBSERVACION->Visible) { // OBSERVACION ?>
		<th><span id="elh_view_inv_OBSERVACION" class="view_inv_OBSERVACION"><?php echo $view_inv->OBSERVACION->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->AD1O->Visible) { // AÑO ?>
		<th><span id="elh_view_inv_AD1O" class="view_inv_AD1O"><?php echo $view_inv->AD1O->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->FASE->Visible) { // FASE ?>
		<th><span id="elh_view_inv_FASE" class="view_inv_FASE"><?php echo $view_inv->FASE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->FECHA_INV->Visible) { // FECHA_INV ?>
		<th><span id="elh_view_inv_FECHA_INV" class="view_inv_FECHA_INV"><?php echo $view_inv->FECHA_INV->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Otro_NOM_CAPAT->Visible) { // Otro_NOM_CAPAT ?>
		<th><span id="elh_view_inv_Otro_NOM_CAPAT" class="view_inv_Otro_NOM_CAPAT"><?php echo $view_inv->Otro_NOM_CAPAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Otro_CC_CAPAT->Visible) { // Otro_CC_CAPAT ?>
		<th><span id="elh_view_inv_Otro_CC_CAPAT" class="view_inv_Otro_CC_CAPAT"><?php echo $view_inv->Otro_CC_CAPAT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->NOM_LUGAR->Visible) { // NOM_LUGAR ?>
		<th><span id="elh_view_inv_NOM_LUGAR" class="view_inv_NOM_LUGAR"><?php echo $view_inv->NOM_LUGAR->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Cocina->Visible) { // Cocina ?>
		<th><span id="elh_view_inv_Cocina" class="view_inv_Cocina"><?php echo $view_inv->Cocina->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Abrelatas->Visible) { // 1_Abrelatas ?>
		<th><span id="elh_view_inv__1_Abrelatas" class="view_inv__1_Abrelatas"><?php echo $view_inv->_1_Abrelatas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Balde->Visible) { // 1_Balde ?>
		<th><span id="elh_view_inv__1_Balde" class="view_inv__1_Balde"><?php echo $view_inv->_1_Balde->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Arrocero_50->Visible) { // 1_Arrocero_50 ?>
		<th><span id="elh_view_inv__1_Arrocero_50" class="view_inv__1_Arrocero_50"><?php echo $view_inv->_1_Arrocero_50->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Arrocero_44->Visible) { // 1_Arrocero_44 ?>
		<th><span id="elh_view_inv__1_Arrocero_44" class="view_inv__1_Arrocero_44"><?php echo $view_inv->_1_Arrocero_44->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Chocolatera->Visible) { // 1_Chocolatera ?>
		<th><span id="elh_view_inv__1_Chocolatera" class="view_inv__1_Chocolatera"><?php echo $view_inv->_1_Chocolatera->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Colador->Visible) { // 1_Colador ?>
		<th><span id="elh_view_inv__1_Colador" class="view_inv__1_Colador"><?php echo $view_inv->_1_Colador->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Cucharon_sopa->Visible) { // 1_Cucharon_sopa ?>
		<th><span id="elh_view_inv__1_Cucharon_sopa" class="view_inv__1_Cucharon_sopa"><?php echo $view_inv->_1_Cucharon_sopa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Cucharon_arroz->Visible) { // 1_Cucharon_arroz ?>
		<th><span id="elh_view_inv__1_Cucharon_arroz" class="view_inv__1_Cucharon_arroz"><?php echo $view_inv->_1_Cucharon_arroz->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Cuchillo->Visible) { // 1_Cuchillo ?>
		<th><span id="elh_view_inv__1_Cuchillo" class="view_inv__1_Cuchillo"><?php echo $view_inv->_1_Cuchillo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Embudo->Visible) { // 1_Embudo ?>
		<th><span id="elh_view_inv__1_Embudo" class="view_inv__1_Embudo"><?php echo $view_inv->_1_Embudo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Espumera->Visible) { // 1_Espumera ?>
		<th><span id="elh_view_inv__1_Espumera" class="view_inv__1_Espumera"><?php echo $view_inv->_1_Espumera->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Estufa->Visible) { // 1_Estufa ?>
		<th><span id="elh_view_inv__1_Estufa" class="view_inv__1_Estufa"><?php echo $view_inv->_1_Estufa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Cuchara_sopa->Visible) { // 1_Cuchara_sopa ?>
		<th><span id="elh_view_inv__1_Cuchara_sopa" class="view_inv__1_Cuchara_sopa"><?php echo $view_inv->_1_Cuchara_sopa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Recipiente->Visible) { // 1_Recipiente ?>
		<th><span id="elh_view_inv__1_Recipiente" class="view_inv__1_Recipiente"><?php echo $view_inv->_1_Recipiente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Kit_Repue_estufa->Visible) { // 1_Kit_Repue_estufa ?>
		<th><span id="elh_view_inv__1_Kit_Repue_estufa" class="view_inv__1_Kit_Repue_estufa"><?php echo $view_inv->_1_Kit_Repue_estufa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Molinillo->Visible) { // 1_Molinillo ?>
		<th><span id="elh_view_inv__1_Molinillo" class="view_inv__1_Molinillo"><?php echo $view_inv->_1_Molinillo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Olla_36->Visible) { // 1_Olla_36 ?>
		<th><span id="elh_view_inv__1_Olla_36" class="view_inv__1_Olla_36"><?php echo $view_inv->_1_Olla_36->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Olla_40->Visible) { // 1_Olla_40 ?>
		<th><span id="elh_view_inv__1_Olla_40" class="view_inv__1_Olla_40"><?php echo $view_inv->_1_Olla_40->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Paila_32->Visible) { // 1_Paila_32 ?>
		<th><span id="elh_view_inv__1_Paila_32" class="view_inv__1_Paila_32"><?php echo $view_inv->_1_Paila_32->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_1_Paila_36_37->Visible) { // 1_Paila_36_37 ?>
		<th><span id="elh_view_inv__1_Paila_36_37" class="view_inv__1_Paila_36_37"><?php echo $view_inv->_1_Paila_36_37->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Camping->Visible) { // Camping ?>
		<th><span id="elh_view_inv_Camping" class="view_inv_Camping"><?php echo $view_inv->Camping->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Aislante->Visible) { // 2_Aislante ?>
		<th><span id="elh_view_inv__2_Aislante" class="view_inv__2_Aislante"><?php echo $view_inv->_2_Aislante->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Carpa_hamaca->Visible) { // 2_Carpa_hamaca ?>
		<th><span id="elh_view_inv__2_Carpa_hamaca" class="view_inv__2_Carpa_hamaca"><?php echo $view_inv->_2_Carpa_hamaca->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Carpa_rancho->Visible) { // 2_Carpa_rancho ?>
		<th><span id="elh_view_inv__2_Carpa_rancho" class="view_inv__2_Carpa_rancho"><?php echo $view_inv->_2_Carpa_rancho->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Fibra_rollo->Visible) { // 2_Fibra_rollo ?>
		<th><span id="elh_view_inv__2_Fibra_rollo" class="view_inv__2_Fibra_rollo"><?php echo $view_inv->_2_Fibra_rollo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_CAL->Visible) { // 2_CAL ?>
		<th><span id="elh_view_inv__2_CAL" class="view_inv__2_CAL"><?php echo $view_inv->_2_CAL->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Linterna->Visible) { // 2_Linterna ?>
		<th><span id="elh_view_inv__2_Linterna" class="view_inv__2_Linterna"><?php echo $view_inv->_2_Linterna->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Botiquin->Visible) { // 2_Botiquin ?>
		<th><span id="elh_view_inv__2_Botiquin" class="view_inv__2_Botiquin"><?php echo $view_inv->_2_Botiquin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Mascara_filtro->Visible) { // 2_Mascara_filtro ?>
		<th><span id="elh_view_inv__2_Mascara_filtro" class="view_inv__2_Mascara_filtro"><?php echo $view_inv->_2_Mascara_filtro->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Pimpina->Visible) { // 2_Pimpina ?>
		<th><span id="elh_view_inv__2_Pimpina" class="view_inv__2_Pimpina"><?php echo $view_inv->_2_Pimpina->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_SleepingA0->Visible) { // 2_Sleeping  ?>
		<th><span id="elh_view_inv__2_SleepingA0" class="view_inv__2_SleepingA0"><?php echo $view_inv->_2_SleepingA0->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Plastico_negro->Visible) { // 2_Plastico_negro ?>
		<th><span id="elh_view_inv__2_Plastico_negro" class="view_inv__2_Plastico_negro"><?php echo $view_inv->_2_Plastico_negro->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Tula_tropa->Visible) { // 2_Tula_tropa ?>
		<th><span id="elh_view_inv__2_Tula_tropa" class="view_inv__2_Tula_tropa"><?php echo $view_inv->_2_Tula_tropa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_2_Camilla->Visible) { // 2_Camilla ?>
		<th><span id="elh_view_inv__2_Camilla" class="view_inv__2_Camilla"><?php echo $view_inv->_2_Camilla->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Herramientas->Visible) { // Herramientas ?>
		<th><span id="elh_view_inv_Herramientas" class="view_inv_Herramientas"><?php echo $view_inv->Herramientas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Abrazadera->Visible) { // 3_Abrazadera ?>
		<th><span id="elh_view_inv__3_Abrazadera" class="view_inv__3_Abrazadera"><?php echo $view_inv->_3_Abrazadera->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Aspersora->Visible) { // 3_Aspersora ?>
		<th><span id="elh_view_inv__3_Aspersora" class="view_inv__3_Aspersora"><?php echo $view_inv->_3_Aspersora->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Cabo_hacha->Visible) { // 3_Cabo_hacha ?>
		<th><span id="elh_view_inv__3_Cabo_hacha" class="view_inv__3_Cabo_hacha"><?php echo $view_inv->_3_Cabo_hacha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Funda_machete->Visible) { // 3_Funda_machete ?>
		<th><span id="elh_view_inv__3_Funda_machete" class="view_inv__3_Funda_machete"><?php echo $view_inv->_3_Funda_machete->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Glifosato_4lt->Visible) { // 3_Glifosato_4lt ?>
		<th><span id="elh_view_inv__3_Glifosato_4lt" class="view_inv__3_Glifosato_4lt"><?php echo $view_inv->_3_Glifosato_4lt->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Hacha->Visible) { // 3_Hacha ?>
		<th><span id="elh_view_inv__3_Hacha" class="view_inv__3_Hacha"><?php echo $view_inv->_3_Hacha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Lima_12_uni->Visible) { // 3_Lima_12_uni ?>
		<th><span id="elh_view_inv__3_Lima_12_uni" class="view_inv__3_Lima_12_uni"><?php echo $view_inv->_3_Lima_12_uni->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Llave_mixta->Visible) { // 3_Llave_mixta ?>
		<th><span id="elh_view_inv__3_Llave_mixta" class="view_inv__3_Llave_mixta"><?php echo $view_inv->_3_Llave_mixta->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Machete->Visible) { // 3_Machete ?>
		<th><span id="elh_view_inv__3_Machete" class="view_inv__3_Machete"><?php echo $view_inv->_3_Machete->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Gafa_traslucida->Visible) { // 3_Gafa_traslucida ?>
		<th><span id="elh_view_inv__3_Gafa_traslucida" class="view_inv__3_Gafa_traslucida"><?php echo $view_inv->_3_Gafa_traslucida->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Motosierra->Visible) { // 3_Motosierra ?>
		<th><span id="elh_view_inv__3_Motosierra" class="view_inv__3_Motosierra"><?php echo $view_inv->_3_Motosierra->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Palin->Visible) { // 3_Palin ?>
		<th><span id="elh_view_inv__3_Palin" class="view_inv__3_Palin"><?php echo $view_inv->_3_Palin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->_3_Tubo_galvanizado->Visible) { // 3_Tubo_galvanizado ?>
		<th><span id="elh_view_inv__3_Tubo_galvanizado" class="view_inv__3_Tubo_galvanizado"><?php echo $view_inv->_3_Tubo_galvanizado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_inv->Modificado->Visible) { // Modificado ?>
		<th><span id="elh_view_inv_Modificado" class="view_inv_Modificado"><?php echo $view_inv->Modificado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$view_inv_delete->RecCnt = 0;
$i = 0;
while (!$view_inv_delete->Recordset->EOF) {
	$view_inv_delete->RecCnt++;
	$view_inv_delete->RowCnt++;

	// Set row properties
	$view_inv->ResetAttrs();
	$view_inv->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$view_inv_delete->LoadRowValues($view_inv_delete->Recordset);

	// Render row
	$view_inv_delete->RenderRow();
?>
	<tr<?php echo $view_inv->RowAttributes() ?>>
<?php if ($view_inv->llave->Visible) { // llave ?>
		<td<?php echo $view_inv->llave->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_llave" class="view_inv_llave">
<span<?php echo $view_inv->llave->ViewAttributes() ?>>
<?php echo $view_inv->llave->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->F_Sincron->Visible) { // F_Sincron ?>
		<td<?php echo $view_inv->F_Sincron->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_F_Sincron" class="view_inv_F_Sincron">
<span<?php echo $view_inv->F_Sincron->ViewAttributes() ?>>
<?php echo $view_inv->F_Sincron->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->USUARIO->Visible) { // USUARIO ?>
		<td<?php echo $view_inv->USUARIO->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_USUARIO" class="view_inv_USUARIO">
<span<?php echo $view_inv->USUARIO->ViewAttributes() ?>>
<?php echo $view_inv->USUARIO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Cargo_gme->Visible) { // Cargo_gme ?>
		<td<?php echo $view_inv->Cargo_gme->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Cargo_gme" class="view_inv_Cargo_gme">
<span<?php echo $view_inv->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_inv->Cargo_gme->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->NOM_PE->Visible) { // NOM_PE ?>
		<td<?php echo $view_inv->NOM_PE->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_NOM_PE" class="view_inv_NOM_PE">
<span<?php echo $view_inv->NOM_PE->ViewAttributes() ?>>
<?php echo $view_inv->NOM_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Otro_PE->Visible) { // Otro_PE ?>
		<td<?php echo $view_inv->Otro_PE->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Otro_PE" class="view_inv_Otro_PE">
<span<?php echo $view_inv->Otro_PE->ViewAttributes() ?>>
<?php echo $view_inv->Otro_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->DIA->Visible) { // DIA ?>
		<td<?php echo $view_inv->DIA->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_DIA" class="view_inv_DIA">
<span<?php echo $view_inv->DIA->ViewAttributes() ?>>
<?php echo $view_inv->DIA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->MES->Visible) { // MES ?>
		<td<?php echo $view_inv->MES->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_MES" class="view_inv_MES">
<span<?php echo $view_inv->MES->ViewAttributes() ?>>
<?php echo $view_inv->MES->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->OBSERVACION->Visible) { // OBSERVACION ?>
		<td<?php echo $view_inv->OBSERVACION->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_OBSERVACION" class="view_inv_OBSERVACION">
<span<?php echo $view_inv->OBSERVACION->ViewAttributes() ?>>
<?php echo $view_inv->OBSERVACION->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->AD1O->Visible) { // AÑO ?>
		<td<?php echo $view_inv->AD1O->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_AD1O" class="view_inv_AD1O">
<span<?php echo $view_inv->AD1O->ViewAttributes() ?>>
<?php echo $view_inv->AD1O->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->FASE->Visible) { // FASE ?>
		<td<?php echo $view_inv->FASE->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_FASE" class="view_inv_FASE">
<span<?php echo $view_inv->FASE->ViewAttributes() ?>>
<?php echo $view_inv->FASE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->FECHA_INV->Visible) { // FECHA_INV ?>
		<td<?php echo $view_inv->FECHA_INV->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_FECHA_INV" class="view_inv_FECHA_INV">
<span<?php echo $view_inv->FECHA_INV->ViewAttributes() ?>>
<?php echo $view_inv->FECHA_INV->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Otro_NOM_CAPAT->Visible) { // Otro_NOM_CAPAT ?>
		<td<?php echo $view_inv->Otro_NOM_CAPAT->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Otro_NOM_CAPAT" class="view_inv_Otro_NOM_CAPAT">
<span<?php echo $view_inv->Otro_NOM_CAPAT->ViewAttributes() ?>>
<?php echo $view_inv->Otro_NOM_CAPAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Otro_CC_CAPAT->Visible) { // Otro_CC_CAPAT ?>
		<td<?php echo $view_inv->Otro_CC_CAPAT->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Otro_CC_CAPAT" class="view_inv_Otro_CC_CAPAT">
<span<?php echo $view_inv->Otro_CC_CAPAT->ViewAttributes() ?>>
<?php echo $view_inv->Otro_CC_CAPAT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->NOM_LUGAR->Visible) { // NOM_LUGAR ?>
		<td<?php echo $view_inv->NOM_LUGAR->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_NOM_LUGAR" class="view_inv_NOM_LUGAR">
<span<?php echo $view_inv->NOM_LUGAR->ViewAttributes() ?>>
<?php echo $view_inv->NOM_LUGAR->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Cocina->Visible) { // Cocina ?>
		<td<?php echo $view_inv->Cocina->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Cocina" class="view_inv_Cocina">
<span<?php echo $view_inv->Cocina->ViewAttributes() ?>>
<?php echo $view_inv->Cocina->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Abrelatas->Visible) { // 1_Abrelatas ?>
		<td<?php echo $view_inv->_1_Abrelatas->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Abrelatas" class="view_inv__1_Abrelatas">
<span<?php echo $view_inv->_1_Abrelatas->ViewAttributes() ?>>
<?php echo $view_inv->_1_Abrelatas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Balde->Visible) { // 1_Balde ?>
		<td<?php echo $view_inv->_1_Balde->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Balde" class="view_inv__1_Balde">
<span<?php echo $view_inv->_1_Balde->ViewAttributes() ?>>
<?php echo $view_inv->_1_Balde->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Arrocero_50->Visible) { // 1_Arrocero_50 ?>
		<td<?php echo $view_inv->_1_Arrocero_50->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Arrocero_50" class="view_inv__1_Arrocero_50">
<span<?php echo $view_inv->_1_Arrocero_50->ViewAttributes() ?>>
<?php echo $view_inv->_1_Arrocero_50->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Arrocero_44->Visible) { // 1_Arrocero_44 ?>
		<td<?php echo $view_inv->_1_Arrocero_44->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Arrocero_44" class="view_inv__1_Arrocero_44">
<span<?php echo $view_inv->_1_Arrocero_44->ViewAttributes() ?>>
<?php echo $view_inv->_1_Arrocero_44->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Chocolatera->Visible) { // 1_Chocolatera ?>
		<td<?php echo $view_inv->_1_Chocolatera->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Chocolatera" class="view_inv__1_Chocolatera">
<span<?php echo $view_inv->_1_Chocolatera->ViewAttributes() ?>>
<?php echo $view_inv->_1_Chocolatera->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Colador->Visible) { // 1_Colador ?>
		<td<?php echo $view_inv->_1_Colador->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Colador" class="view_inv__1_Colador">
<span<?php echo $view_inv->_1_Colador->ViewAttributes() ?>>
<?php echo $view_inv->_1_Colador->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Cucharon_sopa->Visible) { // 1_Cucharon_sopa ?>
		<td<?php echo $view_inv->_1_Cucharon_sopa->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Cucharon_sopa" class="view_inv__1_Cucharon_sopa">
<span<?php echo $view_inv->_1_Cucharon_sopa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cucharon_sopa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Cucharon_arroz->Visible) { // 1_Cucharon_arroz ?>
		<td<?php echo $view_inv->_1_Cucharon_arroz->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Cucharon_arroz" class="view_inv__1_Cucharon_arroz">
<span<?php echo $view_inv->_1_Cucharon_arroz->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cucharon_arroz->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Cuchillo->Visible) { // 1_Cuchillo ?>
		<td<?php echo $view_inv->_1_Cuchillo->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Cuchillo" class="view_inv__1_Cuchillo">
<span<?php echo $view_inv->_1_Cuchillo->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cuchillo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Embudo->Visible) { // 1_Embudo ?>
		<td<?php echo $view_inv->_1_Embudo->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Embudo" class="view_inv__1_Embudo">
<span<?php echo $view_inv->_1_Embudo->ViewAttributes() ?>>
<?php echo $view_inv->_1_Embudo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Espumera->Visible) { // 1_Espumera ?>
		<td<?php echo $view_inv->_1_Espumera->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Espumera" class="view_inv__1_Espumera">
<span<?php echo $view_inv->_1_Espumera->ViewAttributes() ?>>
<?php echo $view_inv->_1_Espumera->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Estufa->Visible) { // 1_Estufa ?>
		<td<?php echo $view_inv->_1_Estufa->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Estufa" class="view_inv__1_Estufa">
<span<?php echo $view_inv->_1_Estufa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Estufa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Cuchara_sopa->Visible) { // 1_Cuchara_sopa ?>
		<td<?php echo $view_inv->_1_Cuchara_sopa->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Cuchara_sopa" class="view_inv__1_Cuchara_sopa">
<span<?php echo $view_inv->_1_Cuchara_sopa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Cuchara_sopa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Recipiente->Visible) { // 1_Recipiente ?>
		<td<?php echo $view_inv->_1_Recipiente->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Recipiente" class="view_inv__1_Recipiente">
<span<?php echo $view_inv->_1_Recipiente->ViewAttributes() ?>>
<?php echo $view_inv->_1_Recipiente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Kit_Repue_estufa->Visible) { // 1_Kit_Repue_estufa ?>
		<td<?php echo $view_inv->_1_Kit_Repue_estufa->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Kit_Repue_estufa" class="view_inv__1_Kit_Repue_estufa">
<span<?php echo $view_inv->_1_Kit_Repue_estufa->ViewAttributes() ?>>
<?php echo $view_inv->_1_Kit_Repue_estufa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Molinillo->Visible) { // 1_Molinillo ?>
		<td<?php echo $view_inv->_1_Molinillo->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Molinillo" class="view_inv__1_Molinillo">
<span<?php echo $view_inv->_1_Molinillo->ViewAttributes() ?>>
<?php echo $view_inv->_1_Molinillo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Olla_36->Visible) { // 1_Olla_36 ?>
		<td<?php echo $view_inv->_1_Olla_36->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Olla_36" class="view_inv__1_Olla_36">
<span<?php echo $view_inv->_1_Olla_36->ViewAttributes() ?>>
<?php echo $view_inv->_1_Olla_36->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Olla_40->Visible) { // 1_Olla_40 ?>
		<td<?php echo $view_inv->_1_Olla_40->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Olla_40" class="view_inv__1_Olla_40">
<span<?php echo $view_inv->_1_Olla_40->ViewAttributes() ?>>
<?php echo $view_inv->_1_Olla_40->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Paila_32->Visible) { // 1_Paila_32 ?>
		<td<?php echo $view_inv->_1_Paila_32->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Paila_32" class="view_inv__1_Paila_32">
<span<?php echo $view_inv->_1_Paila_32->ViewAttributes() ?>>
<?php echo $view_inv->_1_Paila_32->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_1_Paila_36_37->Visible) { // 1_Paila_36_37 ?>
		<td<?php echo $view_inv->_1_Paila_36_37->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__1_Paila_36_37" class="view_inv__1_Paila_36_37">
<span<?php echo $view_inv->_1_Paila_36_37->ViewAttributes() ?>>
<?php echo $view_inv->_1_Paila_36_37->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Camping->Visible) { // Camping ?>
		<td<?php echo $view_inv->Camping->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Camping" class="view_inv_Camping">
<span<?php echo $view_inv->Camping->ViewAttributes() ?>>
<?php echo $view_inv->Camping->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Aislante->Visible) { // 2_Aislante ?>
		<td<?php echo $view_inv->_2_Aislante->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Aislante" class="view_inv__2_Aislante">
<span<?php echo $view_inv->_2_Aislante->ViewAttributes() ?>>
<?php echo $view_inv->_2_Aislante->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Carpa_hamaca->Visible) { // 2_Carpa_hamaca ?>
		<td<?php echo $view_inv->_2_Carpa_hamaca->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Carpa_hamaca" class="view_inv__2_Carpa_hamaca">
<span<?php echo $view_inv->_2_Carpa_hamaca->ViewAttributes() ?>>
<?php echo $view_inv->_2_Carpa_hamaca->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Carpa_rancho->Visible) { // 2_Carpa_rancho ?>
		<td<?php echo $view_inv->_2_Carpa_rancho->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Carpa_rancho" class="view_inv__2_Carpa_rancho">
<span<?php echo $view_inv->_2_Carpa_rancho->ViewAttributes() ?>>
<?php echo $view_inv->_2_Carpa_rancho->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Fibra_rollo->Visible) { // 2_Fibra_rollo ?>
		<td<?php echo $view_inv->_2_Fibra_rollo->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Fibra_rollo" class="view_inv__2_Fibra_rollo">
<span<?php echo $view_inv->_2_Fibra_rollo->ViewAttributes() ?>>
<?php echo $view_inv->_2_Fibra_rollo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_CAL->Visible) { // 2_CAL ?>
		<td<?php echo $view_inv->_2_CAL->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_CAL" class="view_inv__2_CAL">
<span<?php echo $view_inv->_2_CAL->ViewAttributes() ?>>
<?php echo $view_inv->_2_CAL->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Linterna->Visible) { // 2_Linterna ?>
		<td<?php echo $view_inv->_2_Linterna->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Linterna" class="view_inv__2_Linterna">
<span<?php echo $view_inv->_2_Linterna->ViewAttributes() ?>>
<?php echo $view_inv->_2_Linterna->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Botiquin->Visible) { // 2_Botiquin ?>
		<td<?php echo $view_inv->_2_Botiquin->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Botiquin" class="view_inv__2_Botiquin">
<span<?php echo $view_inv->_2_Botiquin->ViewAttributes() ?>>
<?php echo $view_inv->_2_Botiquin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Mascara_filtro->Visible) { // 2_Mascara_filtro ?>
		<td<?php echo $view_inv->_2_Mascara_filtro->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Mascara_filtro" class="view_inv__2_Mascara_filtro">
<span<?php echo $view_inv->_2_Mascara_filtro->ViewAttributes() ?>>
<?php echo $view_inv->_2_Mascara_filtro->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Pimpina->Visible) { // 2_Pimpina ?>
		<td<?php echo $view_inv->_2_Pimpina->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Pimpina" class="view_inv__2_Pimpina">
<span<?php echo $view_inv->_2_Pimpina->ViewAttributes() ?>>
<?php echo $view_inv->_2_Pimpina->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_SleepingA0->Visible) { // 2_Sleeping  ?>
		<td<?php echo $view_inv->_2_SleepingA0->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_SleepingA0" class="view_inv__2_SleepingA0">
<span<?php echo $view_inv->_2_SleepingA0->ViewAttributes() ?>>
<?php echo $view_inv->_2_SleepingA0->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Plastico_negro->Visible) { // 2_Plastico_negro ?>
		<td<?php echo $view_inv->_2_Plastico_negro->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Plastico_negro" class="view_inv__2_Plastico_negro">
<span<?php echo $view_inv->_2_Plastico_negro->ViewAttributes() ?>>
<?php echo $view_inv->_2_Plastico_negro->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Tula_tropa->Visible) { // 2_Tula_tropa ?>
		<td<?php echo $view_inv->_2_Tula_tropa->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Tula_tropa" class="view_inv__2_Tula_tropa">
<span<?php echo $view_inv->_2_Tula_tropa->ViewAttributes() ?>>
<?php echo $view_inv->_2_Tula_tropa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_2_Camilla->Visible) { // 2_Camilla ?>
		<td<?php echo $view_inv->_2_Camilla->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__2_Camilla" class="view_inv__2_Camilla">
<span<?php echo $view_inv->_2_Camilla->ViewAttributes() ?>>
<?php echo $view_inv->_2_Camilla->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Herramientas->Visible) { // Herramientas ?>
		<td<?php echo $view_inv->Herramientas->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Herramientas" class="view_inv_Herramientas">
<span<?php echo $view_inv->Herramientas->ViewAttributes() ?>>
<?php echo $view_inv->Herramientas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Abrazadera->Visible) { // 3_Abrazadera ?>
		<td<?php echo $view_inv->_3_Abrazadera->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Abrazadera" class="view_inv__3_Abrazadera">
<span<?php echo $view_inv->_3_Abrazadera->ViewAttributes() ?>>
<?php echo $view_inv->_3_Abrazadera->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Aspersora->Visible) { // 3_Aspersora ?>
		<td<?php echo $view_inv->_3_Aspersora->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Aspersora" class="view_inv__3_Aspersora">
<span<?php echo $view_inv->_3_Aspersora->ViewAttributes() ?>>
<?php echo $view_inv->_3_Aspersora->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Cabo_hacha->Visible) { // 3_Cabo_hacha ?>
		<td<?php echo $view_inv->_3_Cabo_hacha->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Cabo_hacha" class="view_inv__3_Cabo_hacha">
<span<?php echo $view_inv->_3_Cabo_hacha->ViewAttributes() ?>>
<?php echo $view_inv->_3_Cabo_hacha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Funda_machete->Visible) { // 3_Funda_machete ?>
		<td<?php echo $view_inv->_3_Funda_machete->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Funda_machete" class="view_inv__3_Funda_machete">
<span<?php echo $view_inv->_3_Funda_machete->ViewAttributes() ?>>
<?php echo $view_inv->_3_Funda_machete->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Glifosato_4lt->Visible) { // 3_Glifosato_4lt ?>
		<td<?php echo $view_inv->_3_Glifosato_4lt->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Glifosato_4lt" class="view_inv__3_Glifosato_4lt">
<span<?php echo $view_inv->_3_Glifosato_4lt->ViewAttributes() ?>>
<?php echo $view_inv->_3_Glifosato_4lt->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Hacha->Visible) { // 3_Hacha ?>
		<td<?php echo $view_inv->_3_Hacha->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Hacha" class="view_inv__3_Hacha">
<span<?php echo $view_inv->_3_Hacha->ViewAttributes() ?>>
<?php echo $view_inv->_3_Hacha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Lima_12_uni->Visible) { // 3_Lima_12_uni ?>
		<td<?php echo $view_inv->_3_Lima_12_uni->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Lima_12_uni" class="view_inv__3_Lima_12_uni">
<span<?php echo $view_inv->_3_Lima_12_uni->ViewAttributes() ?>>
<?php echo $view_inv->_3_Lima_12_uni->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Llave_mixta->Visible) { // 3_Llave_mixta ?>
		<td<?php echo $view_inv->_3_Llave_mixta->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Llave_mixta" class="view_inv__3_Llave_mixta">
<span<?php echo $view_inv->_3_Llave_mixta->ViewAttributes() ?>>
<?php echo $view_inv->_3_Llave_mixta->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Machete->Visible) { // 3_Machete ?>
		<td<?php echo $view_inv->_3_Machete->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Machete" class="view_inv__3_Machete">
<span<?php echo $view_inv->_3_Machete->ViewAttributes() ?>>
<?php echo $view_inv->_3_Machete->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Gafa_traslucida->Visible) { // 3_Gafa_traslucida ?>
		<td<?php echo $view_inv->_3_Gafa_traslucida->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Gafa_traslucida" class="view_inv__3_Gafa_traslucida">
<span<?php echo $view_inv->_3_Gafa_traslucida->ViewAttributes() ?>>
<?php echo $view_inv->_3_Gafa_traslucida->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Motosierra->Visible) { // 3_Motosierra ?>
		<td<?php echo $view_inv->_3_Motosierra->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Motosierra" class="view_inv__3_Motosierra">
<span<?php echo $view_inv->_3_Motosierra->ViewAttributes() ?>>
<?php echo $view_inv->_3_Motosierra->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Palin->Visible) { // 3_Palin ?>
		<td<?php echo $view_inv->_3_Palin->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Palin" class="view_inv__3_Palin">
<span<?php echo $view_inv->_3_Palin->ViewAttributes() ?>>
<?php echo $view_inv->_3_Palin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->_3_Tubo_galvanizado->Visible) { // 3_Tubo_galvanizado ?>
		<td<?php echo $view_inv->_3_Tubo_galvanizado->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv__3_Tubo_galvanizado" class="view_inv__3_Tubo_galvanizado">
<span<?php echo $view_inv->_3_Tubo_galvanizado->ViewAttributes() ?>>
<?php echo $view_inv->_3_Tubo_galvanizado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_inv->Modificado->Visible) { // Modificado ?>
		<td<?php echo $view_inv->Modificado->CellAttributes() ?>>
<span id="el<?php echo $view_inv_delete->RowCnt ?>_view_inv_Modificado" class="view_inv_Modificado">
<span<?php echo $view_inv->Modificado->ViewAttributes() ?>>
<?php echo $view_inv->Modificado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$view_inv_delete->Recordset->MoveNext();
}
$view_inv_delete->Recordset->Close();
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
fview_invdelete.Init();
</script>
<?php
$view_inv_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_inv_delete->Page_Terminate();
?>
