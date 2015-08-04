<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(126, "mmci_Informe_Diario", $Language->MenuPhrase("126", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(230, "mmi_view_id", $Language->MenuPhrase("230", "MenuText"), "view_idlist.php", 126, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}view_id'), FALSE);
$RootMenu->AddMenuItem(60, "mmi_info_diario", $Language->MenuPhrase("60", "MenuText"), "info_diariolist.php", 126, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}info_diario'), FALSE);
$RootMenu->AddMenuItem(128, "mmci_Inventario", $Language->MenuPhrase("128", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(229, "mmi_view_inv", $Language->MenuPhrase("229", "MenuText"), "view_invlist.php", 128, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}view_inv'), FALSE);
$RootMenu->AddMenuItem(65, "mmi_inventario", $Language->MenuPhrase("65", "MenuText"), "inventariolist.php", 128, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}inventario'), FALSE);
$RootMenu->AddMenuItem(127, "mmci_Control_c1reas_Vivac", $Language->MenuPhrase("127", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(159, "mmi_view_cav", $Language->MenuPhrase("159", "MenuText"), "view_cavlist.php", 127, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}view_cav'), FALSE);
$RootMenu->AddMenuItem(64, "mmi_control_vivac", $Language->MenuPhrase("64", "MenuText"), "control_vivaclist.php", 127, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}control_vivac'), FALSE);
$RootMenu->AddMenuItem(151, "mmci_Reporte_de_Accidentes", $Language->MenuPhrase("151", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(163, "mmi_view1_acc", $Language->MenuPhrase("163", "MenuText"), "view1_acclist.php", 151, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}view1_acc'), FALSE);
$RootMenu->AddMenuItem(144, "mmi_accidentes", $Language->MenuPhrase("144", "MenuText"), "accidenteslist.php", 151, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}accidentes'), FALSE);
$RootMenu->AddMenuItem(152, "mmci_Enlace_y_Novedad", $Language->MenuPhrase("152", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(162, "mmi_view_e_y_n", $Language->MenuPhrase("162", "MenuText"), "view_e_y_nlist.php", 152, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}view_e_y_n'), FALSE);
$RootMenu->AddMenuItem(145, "mmi_enlace_novedad", $Language->MenuPhrase("145", "MenuText"), "enlace_novedadlist.php", 152, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}enlace_novedad'), FALSE);
$RootMenu->AddMenuItem(228, "mmci_Modulo_de_Gre1ficas", $Language->MenuPhrase("228", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(231, "mmi_grafica_accidentes_trabajo", $Language->MenuPhrase("231", "MenuText"), "grafica_accidentes_trabajolist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_accidentes_trabajo'), FALSE);
$RootMenu->AddMenuItem(232, "mmi_grafica_apoyos_zonales", $Language->MenuPhrase("232", "MenuText"), "grafica_apoyos_zonaleslist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_apoyos_zonales'), FALSE);
$RootMenu->AddMenuItem(233, "mmi_grafica_desempeno_punto", $Language->MenuPhrase("233", "MenuText"), "grafica_desempeno_puntolist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_desempeno_punto'), FALSE);
$RootMenu->AddMenuItem(234, "mmi_grafica_dia_dia_hectareas", $Language->MenuPhrase("234", "MenuText"), "grafica_dia_dia_hectareaslist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_dia_dia_hectareas'), FALSE);
$RootMenu->AddMenuItem(235, "mmi_grafica_dias_contratados", $Language->MenuPhrase("235", "MenuText"), "grafica_dias_contratadoslist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_dias_contratados'), FALSE);
$RootMenu->AddMenuItem(236, "mmi_grafica_disponibilidad_gme", $Language->MenuPhrase("236", "MenuText"), "grafica_disponibilidad_gmelist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_disponibilidad_gme'), FALSE);
$RootMenu->AddMenuItem(237, "mmi_grafica_erradicacion_fuerza_departamento", $Language->MenuPhrase("237", "MenuText"), "grafica_erradicacion_fuerza_departamentolist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_erradicacion_fuerza_departamento'), FALSE);
$RootMenu->AddMenuItem(238, "mmi_grafica_gme_activos", $Language->MenuPhrase("238", "MenuText"), "grafica_gme_activoslist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_gme_activos'), FALSE);
$RootMenu->AddMenuItem(239, "mmi_grafica_impacto_situacion", $Language->MenuPhrase("239", "MenuText"), "grafica_impacto_situacionlist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_impacto_situacion'), FALSE);
$RootMenu->AddMenuItem(240, "mmi_grafica_intervencion_geografica", $Language->MenuPhrase("240", "MenuText"), "grafica_intervencion_geograficalist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_intervencion_geografica'), FALSE);
$RootMenu->AddMenuItem(241, "mmi_grafica_movimientos", $Language->MenuPhrase("241", "MenuText"), "grafica_movimientoslist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_movimientos'), FALSE);
$RootMenu->AddMenuItem(243, "mmi_grafica_produccion", $Language->MenuPhrase("243", "MenuText"), "grafica_produccionlist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_produccion'), FALSE);
$RootMenu->AddMenuItem(242, "mmi_grafica_retiro_personal", $Language->MenuPhrase("242", "MenuText"), "grafica_retiro_personallist.php", 228, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}grafica_retiro_personal'), FALSE);
$RootMenu->AddMenuItem(129, "mmci_Otras_tablas", $Language->MenuPhrase("129", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(253, "mmi_fuerza", $Language->MenuPhrase("253", "MenuText"), "fuerzalist.php", 129, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}fuerza'), FALSE);
$RootMenu->AddMenuItem(61, "mmi_usuarios", $Language->MenuPhrase("61", "MenuText"), "usuarioslist.php", 129, "", AllowListMenu('{D6213859-7C64-4DB8-BACE-8E97DF9FA7FD}usuarios'), FALSE);
$RootMenu->AddMenuItem(63, "mmi_userlevels", $Language->MenuPhrase("63", "MenuText"), "userlevelslist.php", 129, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
