<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}";

	// Page object name
	var $PageObjName = 'default';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// User table object (usuarios)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 'info_diario'))
		$this->Page_Terminate("info_diariolist.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'usuarios'))
			$this->Page_Terminate("usuarioslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'userlevels'))
			$this->Page_Terminate("userlevelslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'control_vivac'))
			$this->Page_Terminate("control_vivaclist.php");
		if ($Security->AllowList(CurrentProjectID() . 'inventario'))
			$this->Page_Terminate("inventariolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'accidentes'))
			$this->Page_Terminate("accidenteslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'enlace_novedad'))
			$this->Page_Terminate("enlace_novedadlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_cav'))
			$this->Page_Terminate("view_cavlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_e_y_n'))
			$this->Page_Terminate("view_e_y_nlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view1_acc'))
			$this->Page_Terminate("view1_acclist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_inv'))
			$this->Page_Terminate("view_invlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view_id'))
			$this->Page_Terminate("view_idlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_accidentes_trabajo'))
			$this->Page_Terminate("grafica_accidentes_trabajolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_apoyos_zonales'))
			$this->Page_Terminate("grafica_apoyos_zonaleslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_desempeno_punto'))
			$this->Page_Terminate("grafica_desempeno_puntolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_dia_dia_hectareas'))
			$this->Page_Terminate("grafica_dia_dia_hectareaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_dias_contratados'))
			$this->Page_Terminate("grafica_dias_contratadoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_disponibilidad_gme'))
			$this->Page_Terminate("grafica_disponibilidad_gmelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_erradicacion_fuerza_departamento'))
			$this->Page_Terminate("grafica_erradicacion_fuerza_departamentolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_gme_activos'))
			$this->Page_Terminate("grafica_gme_activoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_impacto_situacion'))
			$this->Page_Terminate("grafica_impacto_situacionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_intervencion_geografica'))
			$this->Page_Terminate("grafica_intervencion_geograficalist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_movimientos'))
			$this->Page_Terminate("grafica_movimientoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_retiro_personal'))
			$this->Page_Terminate("grafica_retiro_personallist.php");
		if ($Security->AllowList(CurrentProjectID() . 'grafica_produccion'))
			$this->Page_Terminate("grafica_produccionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'fuerza'))
			$this->Page_Terminate("fuerzalist.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage($Language->Phrase("NoPermission") . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
		}
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
