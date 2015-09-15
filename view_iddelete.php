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

$view_id_delete = NULL; // Initialize page object first

class cview_id_delete extends cview_id {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Table name
	var $TableName = 'view_id';

	// Page object name
	var $PageObjName = 'view_id_delete';

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

		// Table object (view_id)
		if (!isset($GLOBALS["view_id"]) || get_class($GLOBALS["view_id"]) == "cview_id") {
			$GLOBALS["view_id"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view_id"];
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
			define("EW_TABLE_NAME", 'view_id', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("view_idlist.php"));
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
			$this->Page_Terminate("view_idlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in view_id class, view_idinfo.php

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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
			if (strval($this->TIPO_INFORME->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->TIPO_INFORME->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='informe'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TIPO_INFORME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->TIPO_INFORME->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->TIPO_INFORME->ViewValue = $this->TIPO_INFORME->CurrentValue;
				}
			} else {
				$this->TIPO_INFORME->ViewValue = NULL;
			}
			$this->TIPO_INFORME->ViewCustomAttributes = "";

			// FECHA_REPORT
			$this->FECHA_REPORT->ViewValue = $this->FECHA_REPORT->CurrentValue;
			$this->FECHA_REPORT->ViewValue = ew_FormatDateTime($this->FECHA_REPORT->ViewValue, 5);
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
			if (strval($this->TEMA->CurrentValue) <> "") {
				$sFilterWrk = "`label`" . ew_SearchString("=", $this->TEMA->CurrentValue, EW_DATATYPE_STRING);
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
			$lookuptblfilter = "`list name`='tema'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->TEMA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `label` ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->TEMA->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->TEMA->ViewValue = $this->TEMA->CurrentValue;
				}
			} else {
				$this->TEMA->ViewValue = NULL;
			}
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
			$this->Situ_Especial->ViewValue = $this->Situ_Especial->CurrentValue;
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
		$Breadcrumb->Add("list", $this->TableVar, "view_idlist.php", "", $this->TableVar, TRUE);
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
if (!isset($view_id_delete)) $view_id_delete = new cview_id_delete();

// Page init
$view_id_delete->Page_Init();

// Page main
$view_id_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view_id_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view_id_delete = new ew_Page("view_id_delete");
view_id_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = view_id_delete.PageID; // For backward compatibility

// Form object
var fview_iddelete = new ew_Form("fview_iddelete");

// Form_CustomValidate event
fview_iddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview_iddelete.ValidateRequired = true;
<?php } else { ?>
fview_iddelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview_iddelete.Lists["x_USUARIO"] = {"LinkField":"x_USUARIO","Ajax":null,"AutoFill":false,"DisplayFields":["x_USUARIO","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_iddelete.Lists["x_NOM_PE"] = {"LinkField":"x_NOM_PE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_iddelete.Lists["x_NOM_PGE"] = {"LinkField":"x_NOM_PGE","Ajax":null,"AutoFill":false,"DisplayFields":["x_NOM_PGE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_iddelete.Lists["x_TIPO_INFORME"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_iddelete.Lists["x_TEMA"] = {"LinkField":"x_label","Ajax":null,"AutoFill":false,"DisplayFields":["x_label","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_iddelete.Lists["x_AD1O"] = {"LinkField":"x_AD1O","Ajax":null,"AutoFill":false,"DisplayFields":["x_AD1O","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fview_iddelete.Lists["x_FASE"] = {"LinkField":"x_FASE","Ajax":null,"AutoFill":false,"DisplayFields":["x_FASE","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($view_id_delete->Recordset = $view_id_delete->LoadRecordset())
	$view_id_deleteTotalRecs = $view_id_delete->Recordset->RecordCount(); // Get record count
if ($view_id_deleteTotalRecs <= 0) { // No record found, exit
	if ($view_id_delete->Recordset)
		$view_id_delete->Recordset->Close();
	$view_id_delete->Page_Terminate("view_idlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $view_id_delete->ShowPageHeader(); ?>
<?php
$view_id_delete->ShowMessage();
?>
<form name="fview_iddelete" id="fview_iddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view_id_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view_id_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view_id">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($view_id_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $view_id->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($view_id->llave->Visible) { // llave ?>
		<th><span id="elh_view_id_llave" class="view_id_llave"><?php echo $view_id->llave->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->F_Sincron->Visible) { // F_Sincron ?>
		<th><span id="elh_view_id_F_Sincron" class="view_id_F_Sincron"><?php echo $view_id->F_Sincron->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->USUARIO->Visible) { // USUARIO ?>
		<th><span id="elh_view_id_USUARIO" class="view_id_USUARIO"><?php echo $view_id->USUARIO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Cargo_gme->Visible) { // Cargo_gme ?>
		<th><span id="elh_view_id_Cargo_gme" class="view_id_Cargo_gme"><?php echo $view_id->Cargo_gme->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->NOM_PE->Visible) { // NOM_PE ?>
		<th><span id="elh_view_id_NOM_PE" class="view_id_NOM_PE"><?php echo $view_id->NOM_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Otro_PE->Visible) { // Otro_PE ?>
		<th><span id="elh_view_id_Otro_PE" class="view_id_Otro_PE"><?php echo $view_id->Otro_PE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->NOM_PGE->Visible) { // NOM_PGE ?>
		<th><span id="elh_view_id_NOM_PGE" class="view_id_NOM_PGE"><?php echo $view_id->NOM_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Otro_NOM_PGE->Visible) { // Otro_NOM_PGE ?>
		<th><span id="elh_view_id_Otro_NOM_PGE" class="view_id_Otro_NOM_PGE"><?php echo $view_id->Otro_NOM_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<th><span id="elh_view_id_Otro_CC_PGE" class="view_id_Otro_CC_PGE"><?php echo $view_id->Otro_CC_PGE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
		<th><span id="elh_view_id_TIPO_INFORME" class="view_id_TIPO_INFORME"><?php echo $view_id->TIPO_INFORME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->FECHA_REPORT->Visible) { // FECHA_REPORT ?>
		<th><span id="elh_view_id_FECHA_REPORT" class="view_id_FECHA_REPORT"><?php echo $view_id->FECHA_REPORT->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->DIA->Visible) { // DIA ?>
		<th><span id="elh_view_id_DIA" class="view_id_DIA"><?php echo $view_id->DIA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->MES->Visible) { // MES ?>
		<th><span id="elh_view_id_MES" class="view_id_MES"><?php echo $view_id->MES->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Departamento->Visible) { // Departamento ?>
		<th><span id="elh_view_id_Departamento" class="view_id_Departamento"><?php echo $view_id->Departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Muncipio->Visible) { // Muncipio ?>
		<th><span id="elh_view_id_Muncipio" class="view_id_Muncipio"><?php echo $view_id->Muncipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->TEMA->Visible) { // TEMA ?>
		<th><span id="elh_view_id_TEMA" class="view_id_TEMA"><?php echo $view_id->TEMA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Otro_Tema->Visible) { // Otro_Tema ?>
		<th><span id="elh_view_id_Otro_Tema" class="view_id_Otro_Tema"><?php echo $view_id->Otro_Tema->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->OBSERVACION->Visible) { // OBSERVACION ?>
		<th><span id="elh_view_id_OBSERVACION" class="view_id_OBSERVACION"><?php echo $view_id->OBSERVACION->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->NOM_VDA->Visible) { // NOM_VDA ?>
		<th><span id="elh_view_id_NOM_VDA" class="view_id_NOM_VDA"><?php echo $view_id->NOM_VDA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Ha_Coca->Visible) { // Ha_Coca ?>
		<th><span id="elh_view_id_Ha_Coca" class="view_id_Ha_Coca"><?php echo $view_id->Ha_Coca->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Ha_Amapola->Visible) { // Ha_Amapola ?>
		<th><span id="elh_view_id_Ha_Amapola" class="view_id_Ha_Amapola"><?php echo $view_id->Ha_Amapola->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Ha_Marihuana->Visible) { // Ha_Marihuana ?>
		<th><span id="elh_view_id_Ha_Marihuana" class="view_id_Ha_Marihuana"><?php echo $view_id->Ha_Marihuana->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->T_erradi->Visible) { // T_erradi ?>
		<th><span id="elh_view_id_T_erradi" class="view_id_T_erradi"><?php echo $view_id->T_erradi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->LATITUD_sector->Visible) { // LATITUD_sector ?>
		<th><span id="elh_view_id_LATITUD_sector" class="view_id_LATITUD_sector"><?php echo $view_id->LATITUD_sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->GRA_LAT_Sector->Visible) { // GRA_LAT_Sector ?>
		<th><span id="elh_view_id_GRA_LAT_Sector" class="view_id_GRA_LAT_Sector"><?php echo $view_id->GRA_LAT_Sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->MIN_LAT_Sector->Visible) { // MIN_LAT_Sector ?>
		<th><span id="elh_view_id_MIN_LAT_Sector" class="view_id_MIN_LAT_Sector"><?php echo $view_id->MIN_LAT_Sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->SEG_LAT_Sector->Visible) { // SEG_LAT_Sector ?>
		<th><span id="elh_view_id_SEG_LAT_Sector" class="view_id_SEG_LAT_Sector"><?php echo $view_id->SEG_LAT_Sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->GRA_LONG_Sector->Visible) { // GRA_LONG_Sector ?>
		<th><span id="elh_view_id_GRA_LONG_Sector" class="view_id_GRA_LONG_Sector"><?php echo $view_id->GRA_LONG_Sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->MIN_LONG_Sector->Visible) { // MIN_LONG_Sector ?>
		<th><span id="elh_view_id_MIN_LONG_Sector" class="view_id_MIN_LONG_Sector"><?php echo $view_id->MIN_LONG_Sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->SEG_LONG_Sector->Visible) { // SEG_LONG_Sector ?>
		<th><span id="elh_view_id_SEG_LONG_Sector" class="view_id_SEG_LONG_Sector"><?php echo $view_id->SEG_LONG_Sector->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Ini_Jorna->Visible) { // Ini_Jorna ?>
		<th><span id="elh_view_id_Ini_Jorna" class="view_id_Ini_Jorna"><?php echo $view_id->Ini_Jorna->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Fin_Jorna->Visible) { // Fin_Jorna ?>
		<th><span id="elh_view_id_Fin_Jorna" class="view_id_Fin_Jorna"><?php echo $view_id->Fin_Jorna->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Situ_Especial->Visible) { // Situ_Especial ?>
		<th><span id="elh_view_id_Situ_Especial" class="view_id_Situ_Especial"><?php echo $view_id->Situ_Especial->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Adm_GME->Visible) { // Adm_GME ?>
		<th><span id="elh_view_id_Adm_GME" class="view_id_Adm_GME"><?php echo $view_id->Adm_GME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Abastecimiento->Visible) { // 1_Abastecimiento ?>
		<th><span id="elh_view_id__1_Abastecimiento" class="view_id__1_Abastecimiento"><?php echo $view_id->_1_Abastecimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Acompanamiento_firma_GME->Visible) { // 1_Acompanamiento_firma_GME ?>
		<th><span id="elh_view_id__1_Acompanamiento_firma_GME" class="view_id__1_Acompanamiento_firma_GME"><?php echo $view_id->_1_Acompanamiento_firma_GME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Apoyo_zonal_sin_punto_asignado->Visible) { // 1_Apoyo_zonal_sin_punto_asignado ?>
		<th><span id="elh_view_id__1_Apoyo_zonal_sin_punto_asignado" class="view_id__1_Apoyo_zonal_sin_punto_asignado"><?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Descanso_en_dia_habil->Visible) { // 1_Descanso_en_dia_habil ?>
		<th><span id="elh_view_id__1_Descanso_en_dia_habil" class="view_id__1_Descanso_en_dia_habil"><?php echo $view_id->_1_Descanso_en_dia_habil->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Descanso_festivo_dominical->Visible) { // 1_Descanso_festivo_dominical ?>
		<th><span id="elh_view_id__1_Descanso_festivo_dominical" class="view_id__1_Descanso_festivo_dominical"><?php echo $view_id->_1_Descanso_festivo_dominical->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Dia_compensatorio->Visible) { // 1_Dia_compensatorio ?>
		<th><span id="elh_view_id__1_Dia_compensatorio" class="view_id__1_Dia_compensatorio"><?php echo $view_id->_1_Dia_compensatorio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Erradicacion_en_dia_festivo->Visible) { // 1_Erradicacion_en_dia_festivo ?>
		<th><span id="elh_view_id__1_Erradicacion_en_dia_festivo" class="view_id__1_Erradicacion_en_dia_festivo"><?php echo $view_id->_1_Erradicacion_en_dia_festivo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Espera_helicoptero_Helistar->Visible) { // 1_Espera_helicoptero_Helistar ?>
		<th><span id="elh_view_id__1_Espera_helicoptero_Helistar" class="view_id__1_Espera_helicoptero_Helistar"><?php echo $view_id->_1_Espera_helicoptero_Helistar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Extraccion->Visible) { // 1_Extraccion ?>
		<th><span id="elh_view_id__1_Extraccion" class="view_id__1_Extraccion"><?php echo $view_id->_1_Extraccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Firma_contrato_GME->Visible) { // 1_Firma_contrato_GME ?>
		<th><span id="elh_view_id__1_Firma_contrato_GME" class="view_id__1_Firma_contrato_GME"><?php echo $view_id->_1_Firma_contrato_GME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Induccion_Apoyo_Zonal->Visible) { // 1_Induccion_Apoyo_Zonal ?>
		<th><span id="elh_view_id__1_Induccion_Apoyo_Zonal" class="view_id__1_Induccion_Apoyo_Zonal"><?php echo $view_id->_1_Induccion_Apoyo_Zonal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Insercion->Visible) { // 1_Insercion ?>
		<th><span id="elh_view_id__1_Insercion" class="view_id__1_Insercion"><?php echo $view_id->_1_Insercion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Visible) { // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase ?>
		<th><span id="elh_view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase"><?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Novedad_apoyo_zonal->Visible) { // 1_Novedad_apoyo_zonal ?>
		<th><span id="elh_view_id__1_Novedad_apoyo_zonal" class="view_id__1_Novedad_apoyo_zonal"><?php echo $view_id->_1_Novedad_apoyo_zonal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Novedad_enfermero->Visible) { // 1_Novedad_enfermero ?>
		<th><span id="elh_view_id__1_Novedad_enfermero" class="view_id__1_Novedad_enfermero"><?php echo $view_id->_1_Novedad_enfermero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Punto_fuera_del_area_de_erradicacion->Visible) { // 1_Punto_fuera_del_area_de_erradicacion ?>
		<th><span id="elh_view_id__1_Punto_fuera_del_area_de_erradicacion" class="view_id__1_Punto_fuera_del_area_de_erradicacion"><?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Transporte_bus->Visible) { // 1_Transporte_bus ?>
		<th><span id="elh_view_id__1_Transporte_bus" class="view_id__1_Transporte_bus"><?php echo $view_id->_1_Transporte_bus->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Traslado_apoyo_zonal->Visible) { // 1_Traslado_apoyo_zonal ?>
		<th><span id="elh_view_id__1_Traslado_apoyo_zonal" class="view_id__1_Traslado_apoyo_zonal"><?php echo $view_id->_1_Traslado_apoyo_zonal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_1_Traslado_area_vivac->Visible) { // 1_Traslado_area_vivac ?>
		<th><span id="elh_view_id__1_Traslado_area_vivac" class="view_id__1_Traslado_area_vivac"><?php echo $view_id->_1_Traslado_area_vivac->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Adm_Fuerza->Visible) { // Adm_Fuerza ?>
		<th><span id="elh_view_id_Adm_Fuerza" class="view_id_Adm_Fuerza"><?php echo $view_id->Adm_Fuerza->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->Visible) { // 2_A_la_espera_definicion_nuevo_punto_FP ?>
		<th><span id="elh_view_id__2_A_la_espera_definicion_nuevo_punto_FP" class="view_id__2_A_la_espera_definicion_nuevo_punto_FP"><?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_2_Espera_helicoptero_FP_de_seguridad->Visible) { // 2_Espera_helicoptero_FP_de_seguridad ?>
		<th><span id="elh_view_id__2_Espera_helicoptero_FP_de_seguridad" class="view_id__2_Espera_helicoptero_FP_de_seguridad"><?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_2_Espera_helicoptero_FP_que_abastece->Visible) { // 2_Espera_helicoptero_FP_que_abastece ?>
		<th><span id="elh_view_id__2_Espera_helicoptero_FP_que_abastece" class="view_id__2_Espera_helicoptero_FP_que_abastece"><?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_2_Induccion_FP->Visible) { // 2_Induccion_FP ?>
		<th><span id="elh_view_id__2_Induccion_FP" class="view_id__2_Induccion_FP"><?php echo $view_id->_2_Induccion_FP->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->Visible) { // 2_Novedad_canino_o_del_grupo_de_deteccion ?>
		<th><span id="elh_view_id__2_Novedad_canino_o_del_grupo_de_deteccion" class="view_id__2_Novedad_canino_o_del_grupo_de_deteccion"><?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_2_Problemas_fuerza_publica->Visible) { // 2_Problemas_fuerza_publica ?>
		<th><span id="elh_view_id__2_Problemas_fuerza_publica" class="view_id__2_Problemas_fuerza_publica"><?php echo $view_id->_2_Problemas_fuerza_publica->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_2_Sin_seguridad->Visible) { // 2_Sin_seguridad ?>
		<th><span id="elh_view_id__2_Sin_seguridad" class="view_id__2_Sin_seguridad"><?php echo $view_id->_2_Sin_seguridad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Sit_Seguridad->Visible) { // Sit_Seguridad ?>
		<th><span id="elh_view_id_Sit_Seguridad" class="view_id_Sit_Seguridad"><?php echo $view_id->Sit_Seguridad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_AEI_controlado->Visible) { // 3_AEI_controlado ?>
		<th><span id="elh_view_id__3_AEI_controlado" class="view_id__3_AEI_controlado"><?php echo $view_id->_3_AEI_controlado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_AEI_no_controlado->Visible) { // 3_AEI_no_controlado ?>
		<th><span id="elh_view_id__3_AEI_no_controlado" class="view_id__3_AEI_no_controlado"><?php echo $view_id->_3_AEI_no_controlado->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_Bloqueo_parcial_de_la_comunidad->Visible) { // 3_Bloqueo_parcial_de_la_comunidad ?>
		<th><span id="elh_view_id__3_Bloqueo_parcial_de_la_comunidad" class="view_id__3_Bloqueo_parcial_de_la_comunidad"><?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_Bloqueo_total_de_la_comunidad->Visible) { // 3_Bloqueo_total_de_la_comunidad ?>
		<th><span id="elh_view_id__3_Bloqueo_total_de_la_comunidad" class="view_id__3_Bloqueo_total_de_la_comunidad"><?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_Combate->Visible) { // 3_Combate ?>
		<th><span id="elh_view_id__3_Combate" class="view_id__3_Combate"><?php echo $view_id->_3_Combate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_Hostigamiento->Visible) { // 3_Hostigamiento ?>
		<th><span id="elh_view_id__3_Hostigamiento" class="view_id__3_Hostigamiento"><?php echo $view_id->_3_Hostigamiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_MAP_Controlada->Visible) { // 3_MAP_Controlada ?>
		<th><span id="elh_view_id__3_MAP_Controlada" class="view_id__3_MAP_Controlada"><?php echo $view_id->_3_MAP_Controlada->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_MAP_No_controlada->Visible) { // 3_MAP_No_controlada ?>
		<th><span id="elh_view_id__3_MAP_No_controlada" class="view_id__3_MAP_No_controlada"><?php echo $view_id->_3_MAP_No_controlada->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_MUSE->Visible) { // 3_MUSE ?>
		<th><span id="elh_view_id__3_MUSE" class="view_id__3_MUSE"><?php echo $view_id->_3_MUSE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_3_Operaciones_de_seguridad->Visible) { // 3_Operaciones_de_seguridad ?>
		<th><span id="elh_view_id__3_Operaciones_de_seguridad" class="view_id__3_Operaciones_de_seguridad"><?php echo $view_id->_3_Operaciones_de_seguridad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->LATITUD_segurid->Visible) { // LATITUD_segurid ?>
		<th><span id="elh_view_id_LATITUD_segurid" class="view_id_LATITUD_segurid"><?php echo $view_id->LATITUD_segurid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->GRA_LAT_segurid->Visible) { // GRA_LAT_segurid ?>
		<th><span id="elh_view_id_GRA_LAT_segurid" class="view_id_GRA_LAT_segurid"><?php echo $view_id->GRA_LAT_segurid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->MIN_LAT_segurid->Visible) { // MIN_LAT_segurid ?>
		<th><span id="elh_view_id_MIN_LAT_segurid" class="view_id_MIN_LAT_segurid"><?php echo $view_id->MIN_LAT_segurid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->SEG_LAT_segurid->Visible) { // SEG_LAT_segurid ?>
		<th><span id="elh_view_id_SEG_LAT_segurid" class="view_id_SEG_LAT_segurid"><?php echo $view_id->SEG_LAT_segurid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->GRA_LONG_seguri->Visible) { // GRA_LONG_seguri ?>
		<th><span id="elh_view_id_GRA_LONG_seguri" class="view_id_GRA_LONG_seguri"><?php echo $view_id->GRA_LONG_seguri->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->MIN_LONG_seguri->Visible) { // MIN_LONG_seguri ?>
		<th><span id="elh_view_id_MIN_LONG_seguri" class="view_id_MIN_LONG_seguri"><?php echo $view_id->MIN_LONG_seguri->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->SEG_LONG_seguri->Visible) { // SEG_LONG_seguri ?>
		<th><span id="elh_view_id_SEG_LONG_seguri" class="view_id_SEG_LONG_seguri"><?php echo $view_id->SEG_LONG_seguri->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Novedad->Visible) { // Novedad ?>
		<th><span id="elh_view_id_Novedad" class="view_id_Novedad"><?php echo $view_id->Novedad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_4_Epidemia->Visible) { // 4_Epidemia ?>
		<th><span id="elh_view_id__4_Epidemia" class="view_id__4_Epidemia"><?php echo $view_id->_4_Epidemia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_4_Novedad_climatologica->Visible) { // 4_Novedad_climatologica ?>
		<th><span id="elh_view_id__4_Novedad_climatologica" class="view_id__4_Novedad_climatologica"><?php echo $view_id->_4_Novedad_climatologica->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_4_Registro_de_cultivos->Visible) { // 4_Registro_de_cultivos ?>
		<th><span id="elh_view_id__4_Registro_de_cultivos" class="view_id__4_Registro_de_cultivos"><?php echo $view_id->_4_Registro_de_cultivos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_4_Zona_con_cultivos_muy_dispersos->Visible) { // 4_Zona_con_cultivos_muy_dispersos ?>
		<th><span id="elh_view_id__4_Zona_con_cultivos_muy_dispersos" class="view_id__4_Zona_con_cultivos_muy_dispersos"><?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_4_Zona_de_cruce_de_rios_caudalosos->Visible) { // 4_Zona_de_cruce_de_rios_caudalosos ?>
		<th><span id="elh_view_id__4_Zona_de_cruce_de_rios_caudalosos" class="view_id__4_Zona_de_cruce_de_rios_caudalosos"><?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->_4_Zona_sin_cultivos->Visible) { // 4_Zona_sin_cultivos ?>
		<th><span id="elh_view_id__4_Zona_sin_cultivos" class="view_id__4_Zona_sin_cultivos"><?php echo $view_id->_4_Zona_sin_cultivos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Num_Erra_Salen->Visible) { // Num_Erra_Salen ?>
		<th><span id="elh_view_id_Num_Erra_Salen" class="view_id_Num_Erra_Salen"><?php echo $view_id->Num_Erra_Salen->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Num_Erra_Quedan->Visible) { // Num_Erra_Quedan ?>
		<th><span id="elh_view_id_Num_Erra_Quedan" class="view_id_Num_Erra_Quedan"><?php echo $view_id->Num_Erra_Quedan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->No_ENFERMERO->Visible) { // No_ENFERMERO ?>
		<th><span id="elh_view_id_No_ENFERMERO" class="view_id_No_ENFERMERO"><?php echo $view_id->No_ENFERMERO->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->NUM_FP->Visible) { // NUM_FP ?>
		<th><span id="elh_view_id_NUM_FP" class="view_id_NUM_FP"><?php echo $view_id->NUM_FP->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->NUM_Perso_EVA->Visible) { // NUM_Perso_EVA ?>
		<th><span id="elh_view_id_NUM_Perso_EVA" class="view_id_NUM_Perso_EVA"><?php echo $view_id->NUM_Perso_EVA->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->NUM_Poli->Visible) { // NUM_Poli ?>
		<th><span id="elh_view_id_NUM_Poli" class="view_id_NUM_Poli"><?php echo $view_id->NUM_Poli->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->AD1O->Visible) { // AÑO ?>
		<th><span id="elh_view_id_AD1O" class="view_id_AD1O"><?php echo $view_id->AD1O->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->FASE->Visible) { // FASE ?>
		<th><span id="elh_view_id_FASE" class="view_id_FASE"><?php echo $view_id->FASE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($view_id->Modificado->Visible) { // Modificado ?>
		<th><span id="elh_view_id_Modificado" class="view_id_Modificado"><?php echo $view_id->Modificado->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$view_id_delete->RecCnt = 0;
$i = 0;
while (!$view_id_delete->Recordset->EOF) {
	$view_id_delete->RecCnt++;
	$view_id_delete->RowCnt++;

	// Set row properties
	$view_id->ResetAttrs();
	$view_id->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$view_id_delete->LoadRowValues($view_id_delete->Recordset);

	// Render row
	$view_id_delete->RenderRow();
?>
	<tr<?php echo $view_id->RowAttributes() ?>>
<?php if ($view_id->llave->Visible) { // llave ?>
		<td<?php echo $view_id->llave->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_llave" class="view_id_llave">
<span<?php echo $view_id->llave->ViewAttributes() ?>>
<?php echo $view_id->llave->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->F_Sincron->Visible) { // F_Sincron ?>
		<td<?php echo $view_id->F_Sincron->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_F_Sincron" class="view_id_F_Sincron">
<span<?php echo $view_id->F_Sincron->ViewAttributes() ?>>
<?php echo $view_id->F_Sincron->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->USUARIO->Visible) { // USUARIO ?>
		<td<?php echo $view_id->USUARIO->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_USUARIO" class="view_id_USUARIO">
<span<?php echo $view_id->USUARIO->ViewAttributes() ?>>
<?php echo $view_id->USUARIO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Cargo_gme->Visible) { // Cargo_gme ?>
		<td<?php echo $view_id->Cargo_gme->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Cargo_gme" class="view_id_Cargo_gme">
<span<?php echo $view_id->Cargo_gme->ViewAttributes() ?>>
<?php echo $view_id->Cargo_gme->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->NOM_PE->Visible) { // NOM_PE ?>
		<td<?php echo $view_id->NOM_PE->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_NOM_PE" class="view_id_NOM_PE">
<span<?php echo $view_id->NOM_PE->ViewAttributes() ?>>
<?php echo $view_id->NOM_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Otro_PE->Visible) { // Otro_PE ?>
		<td<?php echo $view_id->Otro_PE->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Otro_PE" class="view_id_Otro_PE">
<span<?php echo $view_id->Otro_PE->ViewAttributes() ?>>
<?php echo $view_id->Otro_PE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->NOM_PGE->Visible) { // NOM_PGE ?>
		<td<?php echo $view_id->NOM_PGE->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_NOM_PGE" class="view_id_NOM_PGE">
<span<?php echo $view_id->NOM_PGE->ViewAttributes() ?>>
<?php echo $view_id->NOM_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Otro_NOM_PGE->Visible) { // Otro_NOM_PGE ?>
		<td<?php echo $view_id->Otro_NOM_PGE->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Otro_NOM_PGE" class="view_id_Otro_NOM_PGE">
<span<?php echo $view_id->Otro_NOM_PGE->ViewAttributes() ?>>
<?php echo $view_id->Otro_NOM_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Otro_CC_PGE->Visible) { // Otro_CC_PGE ?>
		<td<?php echo $view_id->Otro_CC_PGE->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Otro_CC_PGE" class="view_id_Otro_CC_PGE">
<span<?php echo $view_id->Otro_CC_PGE->ViewAttributes() ?>>
<?php echo $view_id->Otro_CC_PGE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->TIPO_INFORME->Visible) { // TIPO_INFORME ?>
		<td<?php echo $view_id->TIPO_INFORME->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_TIPO_INFORME" class="view_id_TIPO_INFORME">
<span<?php echo $view_id->TIPO_INFORME->ViewAttributes() ?>>
<?php echo $view_id->TIPO_INFORME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->FECHA_REPORT->Visible) { // FECHA_REPORT ?>
		<td<?php echo $view_id->FECHA_REPORT->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_FECHA_REPORT" class="view_id_FECHA_REPORT">
<span<?php echo $view_id->FECHA_REPORT->ViewAttributes() ?>>
<?php echo $view_id->FECHA_REPORT->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->DIA->Visible) { // DIA ?>
		<td<?php echo $view_id->DIA->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_DIA" class="view_id_DIA">
<span<?php echo $view_id->DIA->ViewAttributes() ?>>
<?php echo $view_id->DIA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->MES->Visible) { // MES ?>
		<td<?php echo $view_id->MES->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_MES" class="view_id_MES">
<span<?php echo $view_id->MES->ViewAttributes() ?>>
<?php echo $view_id->MES->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Departamento->Visible) { // Departamento ?>
		<td<?php echo $view_id->Departamento->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Departamento" class="view_id_Departamento">
<span<?php echo $view_id->Departamento->ViewAttributes() ?>>
<?php echo $view_id->Departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Muncipio->Visible) { // Muncipio ?>
		<td<?php echo $view_id->Muncipio->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Muncipio" class="view_id_Muncipio">
<span<?php echo $view_id->Muncipio->ViewAttributes() ?>>
<?php echo $view_id->Muncipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->TEMA->Visible) { // TEMA ?>
		<td<?php echo $view_id->TEMA->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_TEMA" class="view_id_TEMA">
<span<?php echo $view_id->TEMA->ViewAttributes() ?>>
<?php echo $view_id->TEMA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Otro_Tema->Visible) { // Otro_Tema ?>
		<td<?php echo $view_id->Otro_Tema->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Otro_Tema" class="view_id_Otro_Tema">
<span<?php echo $view_id->Otro_Tema->ViewAttributes() ?>>
<?php echo $view_id->Otro_Tema->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->OBSERVACION->Visible) { // OBSERVACION ?>
		<td<?php echo $view_id->OBSERVACION->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_OBSERVACION" class="view_id_OBSERVACION">
<span<?php echo $view_id->OBSERVACION->ViewAttributes() ?>>
<?php echo $view_id->OBSERVACION->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->NOM_VDA->Visible) { // NOM_VDA ?>
		<td<?php echo $view_id->NOM_VDA->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_NOM_VDA" class="view_id_NOM_VDA">
<span<?php echo $view_id->NOM_VDA->ViewAttributes() ?>>
<?php echo $view_id->NOM_VDA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Ha_Coca->Visible) { // Ha_Coca ?>
		<td<?php echo $view_id->Ha_Coca->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Ha_Coca" class="view_id_Ha_Coca">
<span<?php echo $view_id->Ha_Coca->ViewAttributes() ?>>
<?php echo $view_id->Ha_Coca->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Ha_Amapola->Visible) { // Ha_Amapola ?>
		<td<?php echo $view_id->Ha_Amapola->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Ha_Amapola" class="view_id_Ha_Amapola">
<span<?php echo $view_id->Ha_Amapola->ViewAttributes() ?>>
<?php echo $view_id->Ha_Amapola->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Ha_Marihuana->Visible) { // Ha_Marihuana ?>
		<td<?php echo $view_id->Ha_Marihuana->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Ha_Marihuana" class="view_id_Ha_Marihuana">
<span<?php echo $view_id->Ha_Marihuana->ViewAttributes() ?>>
<?php echo $view_id->Ha_Marihuana->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->T_erradi->Visible) { // T_erradi ?>
		<td<?php echo $view_id->T_erradi->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_T_erradi" class="view_id_T_erradi">
<span<?php echo $view_id->T_erradi->ViewAttributes() ?>>
<?php echo $view_id->T_erradi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->LATITUD_sector->Visible) { // LATITUD_sector ?>
		<td<?php echo $view_id->LATITUD_sector->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_LATITUD_sector" class="view_id_LATITUD_sector">
<span<?php echo $view_id->LATITUD_sector->ViewAttributes() ?>>
<?php echo $view_id->LATITUD_sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->GRA_LAT_Sector->Visible) { // GRA_LAT_Sector ?>
		<td<?php echo $view_id->GRA_LAT_Sector->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_GRA_LAT_Sector" class="view_id_GRA_LAT_Sector">
<span<?php echo $view_id->GRA_LAT_Sector->ViewAttributes() ?>>
<?php echo $view_id->GRA_LAT_Sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->MIN_LAT_Sector->Visible) { // MIN_LAT_Sector ?>
		<td<?php echo $view_id->MIN_LAT_Sector->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_MIN_LAT_Sector" class="view_id_MIN_LAT_Sector">
<span<?php echo $view_id->MIN_LAT_Sector->ViewAttributes() ?>>
<?php echo $view_id->MIN_LAT_Sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->SEG_LAT_Sector->Visible) { // SEG_LAT_Sector ?>
		<td<?php echo $view_id->SEG_LAT_Sector->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_SEG_LAT_Sector" class="view_id_SEG_LAT_Sector">
<span<?php echo $view_id->SEG_LAT_Sector->ViewAttributes() ?>>
<?php echo $view_id->SEG_LAT_Sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->GRA_LONG_Sector->Visible) { // GRA_LONG_Sector ?>
		<td<?php echo $view_id->GRA_LONG_Sector->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_GRA_LONG_Sector" class="view_id_GRA_LONG_Sector">
<span<?php echo $view_id->GRA_LONG_Sector->ViewAttributes() ?>>
<?php echo $view_id->GRA_LONG_Sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->MIN_LONG_Sector->Visible) { // MIN_LONG_Sector ?>
		<td<?php echo $view_id->MIN_LONG_Sector->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_MIN_LONG_Sector" class="view_id_MIN_LONG_Sector">
<span<?php echo $view_id->MIN_LONG_Sector->ViewAttributes() ?>>
<?php echo $view_id->MIN_LONG_Sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->SEG_LONG_Sector->Visible) { // SEG_LONG_Sector ?>
		<td<?php echo $view_id->SEG_LONG_Sector->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_SEG_LONG_Sector" class="view_id_SEG_LONG_Sector">
<span<?php echo $view_id->SEG_LONG_Sector->ViewAttributes() ?>>
<?php echo $view_id->SEG_LONG_Sector->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Ini_Jorna->Visible) { // Ini_Jorna ?>
		<td<?php echo $view_id->Ini_Jorna->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Ini_Jorna" class="view_id_Ini_Jorna">
<span<?php echo $view_id->Ini_Jorna->ViewAttributes() ?>>
<?php echo $view_id->Ini_Jorna->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Fin_Jorna->Visible) { // Fin_Jorna ?>
		<td<?php echo $view_id->Fin_Jorna->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Fin_Jorna" class="view_id_Fin_Jorna">
<span<?php echo $view_id->Fin_Jorna->ViewAttributes() ?>>
<?php echo $view_id->Fin_Jorna->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Situ_Especial->Visible) { // Situ_Especial ?>
		<td<?php echo $view_id->Situ_Especial->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Situ_Especial" class="view_id_Situ_Especial">
<span<?php echo $view_id->Situ_Especial->ViewAttributes() ?>>
<?php echo $view_id->Situ_Especial->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Adm_GME->Visible) { // Adm_GME ?>
		<td<?php echo $view_id->Adm_GME->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Adm_GME" class="view_id_Adm_GME">
<span<?php echo $view_id->Adm_GME->ViewAttributes() ?>>
<?php echo $view_id->Adm_GME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Abastecimiento->Visible) { // 1_Abastecimiento ?>
		<td<?php echo $view_id->_1_Abastecimiento->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Abastecimiento" class="view_id__1_Abastecimiento">
<span<?php echo $view_id->_1_Abastecimiento->ViewAttributes() ?>>
<?php echo $view_id->_1_Abastecimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Acompanamiento_firma_GME->Visible) { // 1_Acompanamiento_firma_GME ?>
		<td<?php echo $view_id->_1_Acompanamiento_firma_GME->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Acompanamiento_firma_GME" class="view_id__1_Acompanamiento_firma_GME">
<span<?php echo $view_id->_1_Acompanamiento_firma_GME->ViewAttributes() ?>>
<?php echo $view_id->_1_Acompanamiento_firma_GME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Apoyo_zonal_sin_punto_asignado->Visible) { // 1_Apoyo_zonal_sin_punto_asignado ?>
		<td<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Apoyo_zonal_sin_punto_asignado" class="view_id__1_Apoyo_zonal_sin_punto_asignado">
<span<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->ViewAttributes() ?>>
<?php echo $view_id->_1_Apoyo_zonal_sin_punto_asignado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Descanso_en_dia_habil->Visible) { // 1_Descanso_en_dia_habil ?>
		<td<?php echo $view_id->_1_Descanso_en_dia_habil->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Descanso_en_dia_habil" class="view_id__1_Descanso_en_dia_habil">
<span<?php echo $view_id->_1_Descanso_en_dia_habil->ViewAttributes() ?>>
<?php echo $view_id->_1_Descanso_en_dia_habil->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Descanso_festivo_dominical->Visible) { // 1_Descanso_festivo_dominical ?>
		<td<?php echo $view_id->_1_Descanso_festivo_dominical->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Descanso_festivo_dominical" class="view_id__1_Descanso_festivo_dominical">
<span<?php echo $view_id->_1_Descanso_festivo_dominical->ViewAttributes() ?>>
<?php echo $view_id->_1_Descanso_festivo_dominical->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Dia_compensatorio->Visible) { // 1_Dia_compensatorio ?>
		<td<?php echo $view_id->_1_Dia_compensatorio->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Dia_compensatorio" class="view_id__1_Dia_compensatorio">
<span<?php echo $view_id->_1_Dia_compensatorio->ViewAttributes() ?>>
<?php echo $view_id->_1_Dia_compensatorio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Erradicacion_en_dia_festivo->Visible) { // 1_Erradicacion_en_dia_festivo ?>
		<td<?php echo $view_id->_1_Erradicacion_en_dia_festivo->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Erradicacion_en_dia_festivo" class="view_id__1_Erradicacion_en_dia_festivo">
<span<?php echo $view_id->_1_Erradicacion_en_dia_festivo->ViewAttributes() ?>>
<?php echo $view_id->_1_Erradicacion_en_dia_festivo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Espera_helicoptero_Helistar->Visible) { // 1_Espera_helicoptero_Helistar ?>
		<td<?php echo $view_id->_1_Espera_helicoptero_Helistar->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Espera_helicoptero_Helistar" class="view_id__1_Espera_helicoptero_Helistar">
<span<?php echo $view_id->_1_Espera_helicoptero_Helistar->ViewAttributes() ?>>
<?php echo $view_id->_1_Espera_helicoptero_Helistar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Extraccion->Visible) { // 1_Extraccion ?>
		<td<?php echo $view_id->_1_Extraccion->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Extraccion" class="view_id__1_Extraccion">
<span<?php echo $view_id->_1_Extraccion->ViewAttributes() ?>>
<?php echo $view_id->_1_Extraccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Firma_contrato_GME->Visible) { // 1_Firma_contrato_GME ?>
		<td<?php echo $view_id->_1_Firma_contrato_GME->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Firma_contrato_GME" class="view_id__1_Firma_contrato_GME">
<span<?php echo $view_id->_1_Firma_contrato_GME->ViewAttributes() ?>>
<?php echo $view_id->_1_Firma_contrato_GME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Induccion_Apoyo_Zonal->Visible) { // 1_Induccion_Apoyo_Zonal ?>
		<td<?php echo $view_id->_1_Induccion_Apoyo_Zonal->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Induccion_Apoyo_Zonal" class="view_id__1_Induccion_Apoyo_Zonal">
<span<?php echo $view_id->_1_Induccion_Apoyo_Zonal->ViewAttributes() ?>>
<?php echo $view_id->_1_Induccion_Apoyo_Zonal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Insercion->Visible) { // 1_Insercion ?>
		<td<?php echo $view_id->_1_Insercion->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Insercion" class="view_id__1_Insercion">
<span<?php echo $view_id->_1_Insercion->ViewAttributes() ?>>
<?php echo $view_id->_1_Insercion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->Visible) { // 1_Llegada_GME_a_su_lugar_de_Origen_fin_fase ?>
		<td<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase" class="view_id__1_Llegada_GME_a_su_lugar_de_Origen_fin_fase">
<span<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ViewAttributes() ?>>
<?php echo $view_id->_1_Llegada_GME_a_su_lugar_de_Origen_fin_fase->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Novedad_apoyo_zonal->Visible) { // 1_Novedad_apoyo_zonal ?>
		<td<?php echo $view_id->_1_Novedad_apoyo_zonal->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Novedad_apoyo_zonal" class="view_id__1_Novedad_apoyo_zonal">
<span<?php echo $view_id->_1_Novedad_apoyo_zonal->ViewAttributes() ?>>
<?php echo $view_id->_1_Novedad_apoyo_zonal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Novedad_enfermero->Visible) { // 1_Novedad_enfermero ?>
		<td<?php echo $view_id->_1_Novedad_enfermero->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Novedad_enfermero" class="view_id__1_Novedad_enfermero">
<span<?php echo $view_id->_1_Novedad_enfermero->ViewAttributes() ?>>
<?php echo $view_id->_1_Novedad_enfermero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Punto_fuera_del_area_de_erradicacion->Visible) { // 1_Punto_fuera_del_area_de_erradicacion ?>
		<td<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Punto_fuera_del_area_de_erradicacion" class="view_id__1_Punto_fuera_del_area_de_erradicacion">
<span<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->ViewAttributes() ?>>
<?php echo $view_id->_1_Punto_fuera_del_area_de_erradicacion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Transporte_bus->Visible) { // 1_Transporte_bus ?>
		<td<?php echo $view_id->_1_Transporte_bus->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Transporte_bus" class="view_id__1_Transporte_bus">
<span<?php echo $view_id->_1_Transporte_bus->ViewAttributes() ?>>
<?php echo $view_id->_1_Transporte_bus->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Traslado_apoyo_zonal->Visible) { // 1_Traslado_apoyo_zonal ?>
		<td<?php echo $view_id->_1_Traslado_apoyo_zonal->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Traslado_apoyo_zonal" class="view_id__1_Traslado_apoyo_zonal">
<span<?php echo $view_id->_1_Traslado_apoyo_zonal->ViewAttributes() ?>>
<?php echo $view_id->_1_Traslado_apoyo_zonal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_1_Traslado_area_vivac->Visible) { // 1_Traslado_area_vivac ?>
		<td<?php echo $view_id->_1_Traslado_area_vivac->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__1_Traslado_area_vivac" class="view_id__1_Traslado_area_vivac">
<span<?php echo $view_id->_1_Traslado_area_vivac->ViewAttributes() ?>>
<?php echo $view_id->_1_Traslado_area_vivac->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Adm_Fuerza->Visible) { // Adm_Fuerza ?>
		<td<?php echo $view_id->Adm_Fuerza->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Adm_Fuerza" class="view_id_Adm_Fuerza">
<span<?php echo $view_id->Adm_Fuerza->ViewAttributes() ?>>
<?php echo $view_id->Adm_Fuerza->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_2_A_la_espera_definicion_nuevo_punto_FP->Visible) { // 2_A_la_espera_definicion_nuevo_punto_FP ?>
		<td<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__2_A_la_espera_definicion_nuevo_punto_FP" class="view_id__2_A_la_espera_definicion_nuevo_punto_FP">
<span<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->ViewAttributes() ?>>
<?php echo $view_id->_2_A_la_espera_definicion_nuevo_punto_FP->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_2_Espera_helicoptero_FP_de_seguridad->Visible) { // 2_Espera_helicoptero_FP_de_seguridad ?>
		<td<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__2_Espera_helicoptero_FP_de_seguridad" class="view_id__2_Espera_helicoptero_FP_de_seguridad">
<span<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->ViewAttributes() ?>>
<?php echo $view_id->_2_Espera_helicoptero_FP_de_seguridad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_2_Espera_helicoptero_FP_que_abastece->Visible) { // 2_Espera_helicoptero_FP_que_abastece ?>
		<td<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__2_Espera_helicoptero_FP_que_abastece" class="view_id__2_Espera_helicoptero_FP_que_abastece">
<span<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->ViewAttributes() ?>>
<?php echo $view_id->_2_Espera_helicoptero_FP_que_abastece->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_2_Induccion_FP->Visible) { // 2_Induccion_FP ?>
		<td<?php echo $view_id->_2_Induccion_FP->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__2_Induccion_FP" class="view_id__2_Induccion_FP">
<span<?php echo $view_id->_2_Induccion_FP->ViewAttributes() ?>>
<?php echo $view_id->_2_Induccion_FP->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->Visible) { // 2_Novedad_canino_o_del_grupo_de_deteccion ?>
		<td<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__2_Novedad_canino_o_del_grupo_de_deteccion" class="view_id__2_Novedad_canino_o_del_grupo_de_deteccion">
<span<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->ViewAttributes() ?>>
<?php echo $view_id->_2_Novedad_canino_o_del_grupo_de_deteccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_2_Problemas_fuerza_publica->Visible) { // 2_Problemas_fuerza_publica ?>
		<td<?php echo $view_id->_2_Problemas_fuerza_publica->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__2_Problemas_fuerza_publica" class="view_id__2_Problemas_fuerza_publica">
<span<?php echo $view_id->_2_Problemas_fuerza_publica->ViewAttributes() ?>>
<?php echo $view_id->_2_Problemas_fuerza_publica->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_2_Sin_seguridad->Visible) { // 2_Sin_seguridad ?>
		<td<?php echo $view_id->_2_Sin_seguridad->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__2_Sin_seguridad" class="view_id__2_Sin_seguridad">
<span<?php echo $view_id->_2_Sin_seguridad->ViewAttributes() ?>>
<?php echo $view_id->_2_Sin_seguridad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Sit_Seguridad->Visible) { // Sit_Seguridad ?>
		<td<?php echo $view_id->Sit_Seguridad->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Sit_Seguridad" class="view_id_Sit_Seguridad">
<span<?php echo $view_id->Sit_Seguridad->ViewAttributes() ?>>
<?php echo $view_id->Sit_Seguridad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_AEI_controlado->Visible) { // 3_AEI_controlado ?>
		<td<?php echo $view_id->_3_AEI_controlado->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_AEI_controlado" class="view_id__3_AEI_controlado">
<span<?php echo $view_id->_3_AEI_controlado->ViewAttributes() ?>>
<?php echo $view_id->_3_AEI_controlado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_AEI_no_controlado->Visible) { // 3_AEI_no_controlado ?>
		<td<?php echo $view_id->_3_AEI_no_controlado->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_AEI_no_controlado" class="view_id__3_AEI_no_controlado">
<span<?php echo $view_id->_3_AEI_no_controlado->ViewAttributes() ?>>
<?php echo $view_id->_3_AEI_no_controlado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_Bloqueo_parcial_de_la_comunidad->Visible) { // 3_Bloqueo_parcial_de_la_comunidad ?>
		<td<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_Bloqueo_parcial_de_la_comunidad" class="view_id__3_Bloqueo_parcial_de_la_comunidad">
<span<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->ViewAttributes() ?>>
<?php echo $view_id->_3_Bloqueo_parcial_de_la_comunidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_Bloqueo_total_de_la_comunidad->Visible) { // 3_Bloqueo_total_de_la_comunidad ?>
		<td<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_Bloqueo_total_de_la_comunidad" class="view_id__3_Bloqueo_total_de_la_comunidad">
<span<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->ViewAttributes() ?>>
<?php echo $view_id->_3_Bloqueo_total_de_la_comunidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_Combate->Visible) { // 3_Combate ?>
		<td<?php echo $view_id->_3_Combate->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_Combate" class="view_id__3_Combate">
<span<?php echo $view_id->_3_Combate->ViewAttributes() ?>>
<?php echo $view_id->_3_Combate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_Hostigamiento->Visible) { // 3_Hostigamiento ?>
		<td<?php echo $view_id->_3_Hostigamiento->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_Hostigamiento" class="view_id__3_Hostigamiento">
<span<?php echo $view_id->_3_Hostigamiento->ViewAttributes() ?>>
<?php echo $view_id->_3_Hostigamiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_MAP_Controlada->Visible) { // 3_MAP_Controlada ?>
		<td<?php echo $view_id->_3_MAP_Controlada->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_MAP_Controlada" class="view_id__3_MAP_Controlada">
<span<?php echo $view_id->_3_MAP_Controlada->ViewAttributes() ?>>
<?php echo $view_id->_3_MAP_Controlada->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_MAP_No_controlada->Visible) { // 3_MAP_No_controlada ?>
		<td<?php echo $view_id->_3_MAP_No_controlada->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_MAP_No_controlada" class="view_id__3_MAP_No_controlada">
<span<?php echo $view_id->_3_MAP_No_controlada->ViewAttributes() ?>>
<?php echo $view_id->_3_MAP_No_controlada->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_MUSE->Visible) { // 3_MUSE ?>
		<td<?php echo $view_id->_3_MUSE->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_MUSE" class="view_id__3_MUSE">
<span<?php echo $view_id->_3_MUSE->ViewAttributes() ?>>
<?php echo $view_id->_3_MUSE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_3_Operaciones_de_seguridad->Visible) { // 3_Operaciones_de_seguridad ?>
		<td<?php echo $view_id->_3_Operaciones_de_seguridad->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__3_Operaciones_de_seguridad" class="view_id__3_Operaciones_de_seguridad">
<span<?php echo $view_id->_3_Operaciones_de_seguridad->ViewAttributes() ?>>
<?php echo $view_id->_3_Operaciones_de_seguridad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->LATITUD_segurid->Visible) { // LATITUD_segurid ?>
		<td<?php echo $view_id->LATITUD_segurid->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_LATITUD_segurid" class="view_id_LATITUD_segurid">
<span<?php echo $view_id->LATITUD_segurid->ViewAttributes() ?>>
<?php echo $view_id->LATITUD_segurid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->GRA_LAT_segurid->Visible) { // GRA_LAT_segurid ?>
		<td<?php echo $view_id->GRA_LAT_segurid->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_GRA_LAT_segurid" class="view_id_GRA_LAT_segurid">
<span<?php echo $view_id->GRA_LAT_segurid->ViewAttributes() ?>>
<?php echo $view_id->GRA_LAT_segurid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->MIN_LAT_segurid->Visible) { // MIN_LAT_segurid ?>
		<td<?php echo $view_id->MIN_LAT_segurid->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_MIN_LAT_segurid" class="view_id_MIN_LAT_segurid">
<span<?php echo $view_id->MIN_LAT_segurid->ViewAttributes() ?>>
<?php echo $view_id->MIN_LAT_segurid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->SEG_LAT_segurid->Visible) { // SEG_LAT_segurid ?>
		<td<?php echo $view_id->SEG_LAT_segurid->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_SEG_LAT_segurid" class="view_id_SEG_LAT_segurid">
<span<?php echo $view_id->SEG_LAT_segurid->ViewAttributes() ?>>
<?php echo $view_id->SEG_LAT_segurid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->GRA_LONG_seguri->Visible) { // GRA_LONG_seguri ?>
		<td<?php echo $view_id->GRA_LONG_seguri->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_GRA_LONG_seguri" class="view_id_GRA_LONG_seguri">
<span<?php echo $view_id->GRA_LONG_seguri->ViewAttributes() ?>>
<?php echo $view_id->GRA_LONG_seguri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->MIN_LONG_seguri->Visible) { // MIN_LONG_seguri ?>
		<td<?php echo $view_id->MIN_LONG_seguri->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_MIN_LONG_seguri" class="view_id_MIN_LONG_seguri">
<span<?php echo $view_id->MIN_LONG_seguri->ViewAttributes() ?>>
<?php echo $view_id->MIN_LONG_seguri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->SEG_LONG_seguri->Visible) { // SEG_LONG_seguri ?>
		<td<?php echo $view_id->SEG_LONG_seguri->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_SEG_LONG_seguri" class="view_id_SEG_LONG_seguri">
<span<?php echo $view_id->SEG_LONG_seguri->ViewAttributes() ?>>
<?php echo $view_id->SEG_LONG_seguri->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Novedad->Visible) { // Novedad ?>
		<td<?php echo $view_id->Novedad->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Novedad" class="view_id_Novedad">
<span<?php echo $view_id->Novedad->ViewAttributes() ?>>
<?php echo $view_id->Novedad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_4_Epidemia->Visible) { // 4_Epidemia ?>
		<td<?php echo $view_id->_4_Epidemia->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__4_Epidemia" class="view_id__4_Epidemia">
<span<?php echo $view_id->_4_Epidemia->ViewAttributes() ?>>
<?php echo $view_id->_4_Epidemia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_4_Novedad_climatologica->Visible) { // 4_Novedad_climatologica ?>
		<td<?php echo $view_id->_4_Novedad_climatologica->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__4_Novedad_climatologica" class="view_id__4_Novedad_climatologica">
<span<?php echo $view_id->_4_Novedad_climatologica->ViewAttributes() ?>>
<?php echo $view_id->_4_Novedad_climatologica->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_4_Registro_de_cultivos->Visible) { // 4_Registro_de_cultivos ?>
		<td<?php echo $view_id->_4_Registro_de_cultivos->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__4_Registro_de_cultivos" class="view_id__4_Registro_de_cultivos">
<span<?php echo $view_id->_4_Registro_de_cultivos->ViewAttributes() ?>>
<?php echo $view_id->_4_Registro_de_cultivos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_4_Zona_con_cultivos_muy_dispersos->Visible) { // 4_Zona_con_cultivos_muy_dispersos ?>
		<td<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__4_Zona_con_cultivos_muy_dispersos" class="view_id__4_Zona_con_cultivos_muy_dispersos">
<span<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->ViewAttributes() ?>>
<?php echo $view_id->_4_Zona_con_cultivos_muy_dispersos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_4_Zona_de_cruce_de_rios_caudalosos->Visible) { // 4_Zona_de_cruce_de_rios_caudalosos ?>
		<td<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__4_Zona_de_cruce_de_rios_caudalosos" class="view_id__4_Zona_de_cruce_de_rios_caudalosos">
<span<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->ViewAttributes() ?>>
<?php echo $view_id->_4_Zona_de_cruce_de_rios_caudalosos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->_4_Zona_sin_cultivos->Visible) { // 4_Zona_sin_cultivos ?>
		<td<?php echo $view_id->_4_Zona_sin_cultivos->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id__4_Zona_sin_cultivos" class="view_id__4_Zona_sin_cultivos">
<span<?php echo $view_id->_4_Zona_sin_cultivos->ViewAttributes() ?>>
<?php echo $view_id->_4_Zona_sin_cultivos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Num_Erra_Salen->Visible) { // Num_Erra_Salen ?>
		<td<?php echo $view_id->Num_Erra_Salen->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Num_Erra_Salen" class="view_id_Num_Erra_Salen">
<span<?php echo $view_id->Num_Erra_Salen->ViewAttributes() ?>>
<?php echo $view_id->Num_Erra_Salen->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Num_Erra_Quedan->Visible) { // Num_Erra_Quedan ?>
		<td<?php echo $view_id->Num_Erra_Quedan->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Num_Erra_Quedan" class="view_id_Num_Erra_Quedan">
<span<?php echo $view_id->Num_Erra_Quedan->ViewAttributes() ?>>
<?php echo $view_id->Num_Erra_Quedan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->No_ENFERMERO->Visible) { // No_ENFERMERO ?>
		<td<?php echo $view_id->No_ENFERMERO->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_No_ENFERMERO" class="view_id_No_ENFERMERO">
<span<?php echo $view_id->No_ENFERMERO->ViewAttributes() ?>>
<?php echo $view_id->No_ENFERMERO->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->NUM_FP->Visible) { // NUM_FP ?>
		<td<?php echo $view_id->NUM_FP->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_NUM_FP" class="view_id_NUM_FP">
<span<?php echo $view_id->NUM_FP->ViewAttributes() ?>>
<?php echo $view_id->NUM_FP->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->NUM_Perso_EVA->Visible) { // NUM_Perso_EVA ?>
		<td<?php echo $view_id->NUM_Perso_EVA->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_NUM_Perso_EVA" class="view_id_NUM_Perso_EVA">
<span<?php echo $view_id->NUM_Perso_EVA->ViewAttributes() ?>>
<?php echo $view_id->NUM_Perso_EVA->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->NUM_Poli->Visible) { // NUM_Poli ?>
		<td<?php echo $view_id->NUM_Poli->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_NUM_Poli" class="view_id_NUM_Poli">
<span<?php echo $view_id->NUM_Poli->ViewAttributes() ?>>
<?php echo $view_id->NUM_Poli->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->AD1O->Visible) { // AÑO ?>
		<td<?php echo $view_id->AD1O->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_AD1O" class="view_id_AD1O">
<span<?php echo $view_id->AD1O->ViewAttributes() ?>>
<?php echo $view_id->AD1O->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->FASE->Visible) { // FASE ?>
		<td<?php echo $view_id->FASE->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_FASE" class="view_id_FASE">
<span<?php echo $view_id->FASE->ViewAttributes() ?>>
<?php echo $view_id->FASE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($view_id->Modificado->Visible) { // Modificado ?>
		<td<?php echo $view_id->Modificado->CellAttributes() ?>>
<span id="el<?php echo $view_id_delete->RowCnt ?>_view_id_Modificado" class="view_id_Modificado">
<span<?php echo $view_id->Modificado->ViewAttributes() ?>>
<?php echo $view_id->Modificado->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$view_id_delete->Recordset->MoveNext();
}
$view_id_delete->Recordset->Close();
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
fview_iddelete.Init();
</script>
<?php
$view_id_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view_id_delete->Page_Terminate();
?>
