<?PHP
/*
    ##########################################################
    #                                                        #
    #  T.I.C. | Tactical Information Center                  #
    #                                                        #
    #  Allianzorganisationstool für Galaxy-Network           #
    #  von NataS alias Tobias Sarnowski                      #
    #  von Pomel alias Achim Pomorin                         #
    #  von Abrafax alias ??????                              #
    #  vom tic-entwickler.de Team                            #
    #                                                        #
    ##########################################################
*/
//    error_reporting(E_ALL); // zu testzwecken einschalten
    ob_start("ob_gzhandler");
    foreach ($_GET as $key => $val) { $$key = $val; }

	// Session-Registrieren
	session_start();
	include 'sessionhelpers.inc.php';
	if (isset($_POST['login'])) {
		if ($userid=check_user($_POST['username'], $_POST['userpass'])) {
			$_SESSION['is_auth'] = 1;
			$_SESSION['userid'] = $userid;
		} else {
			$_SESSION['is_auth'] = 0;
			$_SESSION['userid'] = -1;
			die('Ihre Anmeldedaten waren nicht korrekt!');
		}
	}
	$mtime = microtime();
	$mtime = explode(" ", $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$start_time = $mtime;

	$version = '1.13.1 Beta8';

	include('./accdata.php' );
	include('./globalvars.php');

	// Kein Fehler zu Beginn ^^
	$error_code = 0;

	// HTML Style
	$htmlstyle['hell'] = 'eeeeee';
	$htmlstyle['dunkel'] = 'dddddd';
	$htmlstyle['hell_rot'] = 'ffaaaa';
	$htmlstyle['dunkel_rot'] = 'ff8888';
	$htmlstyle['hell_gruen'] = 'aaffaa';
	$htmlstyle['dunkel_gruen'] = '88ff88';
	$htmlstyle['hell_blau'] = 'aaaaff';
	$htmlstyle['dunkel_blau'] = '8888ff';

	$SQL_Result = mysql_query('SELECT * FROM `gn4accounts` WHERE id="'.$_SESSION['userid'].'";') or die(mysql_errno()." - ".mysql_error());
	if (mysql_num_rows($SQL_Result) == 1) {
		// Nameinfos setzen
		$Benutzer['id'] = mysql_result($SQL_Result, 0, 'id');
		$Benutzer['ticid'] = mysql_result($SQL_Result, 0, 'ticid');
		$Benutzer['name'] = mysql_result($SQL_Result, 0, 'name');
		$Benutzer['galaxie'] = mysql_result($SQL_Result, 0, 'galaxie');
		$Benutzer['pwdandern'] = mysql_result($SQL_Result, 0, 'pwdandern');
		$Benutzer['planet'] = mysql_result($SQL_Result, 0, 'planet');
		$Benutzer['rang'] = mysql_result($SQL_Result, 0, 'rang');
		$Benutzer['allianz'] = mysql_result($SQL_Result, 0, 'allianz');
		$Benutzer['scantyp'] = mysql_result($SQL_Result, 0, 'scantyp');
		$Benutzer['zeitformat'] = mysql_result($SQL_Result, 0, 'zeitformat');
		$Benutzer['svs'] = mysql_result($SQL_Result, 0, 'svs');
		$Benutzer['sbs'] = mysql_result($SQL_Result, 0, 'sbs');
		$Benutzer['umod'] = mysql_result($SQL_Result, 0, 'umod');
		$Benutzer['spy'] = mysql_result($SQL_Result, 0, 'spy');
		$Benutzer['help'] = mysql_result($SQL_Result, 0, 'help');
		$Benutzer['tcausw'] = mysql_result($SQL_Result, 0, 'tcausw');
	} else {
		echo '<a href="index.php" target="_top">Neu Einloggen</a>';
		exit;
	}

  	// Variablen laden
	include('./vars.php');

    //funktionen Laden
	include('./functions.php');

	// Pseudo-Cron
	include('./cron.php');

    //Nachtwache Kontrolle Laden
    include('./NWkontrolle.php');

    // Standardmodul wählen falls nicht angegeben
    if(isset($_POST['modul']) && $_POST['modul'] != "")
        $modul = $_POST['modul'];
    else if(isset($_GET['modul']) && $_GET['modul'] != "")
        $modul = $_GET['modul'];
    else
        $modul = "nachrichten";



	$SQL_Result2 = mysql_query('SELECT pts, s, d, me, ke FROM `gn4scans` WHERE rg="'.$Benutzer['galaxie'].'" AND rp="'.$Benutzer['planet'].'" AND type="0";', $SQL_DBConn);
	if (mysql_num_rows($SQL_Result2) != 1)
	{
		$Benutzer['punkte'] = 0;
		$Benutzer['schiffe'] = 0;
		$Benutzer['defensiv'] = 0;
		$Benutzer['exen_m'] = 0;
		$Benutzer['exen_k'] = 0;
	}
	else
	{
		$Benutzer['punkte'] = mysql_result($SQL_Result2, 0, 'pts');
		$Benutzer['schiffe'] = mysql_result($SQL_Result2, 0, 's');
		$Benutzer['defensiv'] = mysql_result($SQL_Result2, 0, 'd');
		$Benutzer['exen_m'] = mysql_result($SQL_Result2, 0, 'me');
		$Benutzer['exen_k'] = mysql_result($SQL_Result2, 0, 'ke');
	}
	$SQL_Result2 = mysql_query('SELECT blind FROM `gn4allianzen` WHERE id="'.$Benutzer['allianz'].'" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn);
	if (mysql_num_rows($SQL_Result2) != 1)
	{
		$Benutzer['blind'] = 1;
	}
	else
	{
		$Benutzer['blind'] = mysql_result($SQL_Result2, 0, 'blind');
	}

	//lastlogin setzen

	$SQL_Result = mysql_query('UPDATE `gn4accounts` SET lastlogin="'.time().'" WHERE id="'.$Benutzer['id'].'" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn) or $error_code = 7;



	// Spion???
	if($Benutzer['spy'] != 0 && $Benutzer["rang"] != $Rang_STechniker)
	{

?>
<html>
	<head>
		<title>TIC wird gewartet</title>
	</head>
	<body bgcolor=black>
		<table height="100%" width="100%">
			<tr height="80%"><td style="text-align:center">
				<font style="font: 36pt bold arial,sans-serif; color:red;;text-align:center">
					Das "Tactical Information Center" ist<br />
			 		wegen Wartungsarbeiten nicht erreichbar!<p><br /></p>
				</font>
			</td></tr>
		</table>
	</body>
</html>
<?php
		exit;
	}

    if(isset($_POST['action']) && $_POST['action'] != "")
        $action = $_POST['action'];
    else if(isset($_GET['action']) && $_GET['action'] != "")
        $action = $_GET['action'];
    else
        $action = "";

	// Incoming makieren
	if (isset($_GET['need_planet'])) {
		$SQL_Result = mysql_query('UPDATE `gn4flottenbewegungen` SET save="0" WHERE verteidiger_galaxie='.$need_galaxie.' and verteidiger_planet='.$need_planet.';', $SQL_DBConn) or $error_code = 7;
	}
	if (isset($_GET['needno_planet'])) {
		$SQL_Result = mysql_query('UPDATE `gn4flottenbewegungen` SET save="1" WHERE verteidiger_galaxie='.$needno_galaxie.' and verteidiger_planet='.$needno_planet.';', $SQL_DBConn) or $error_code = 7;
	}

	// Funktion einbinden
	// echo 'action=./function.'.$action.'.php<br>';
	if ($action != '') include('./function.'.$action.'.php');

	$SQL_Result6 = mysql_query( 'SELECT value FROM `gn4vars` WHERE name="ticeb" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn );
	$meta =  mysql_result($SQL_Result6, 0, 'value' );
?>
<html>
	<head>
		<title>T.I.C. | Tactical Information Center - Meta <?=$meta?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="refresh" content="900; URL=./main.php?auto">
		<basefont size="-1" face="Verdana">

		<link rel="stylesheet" href="./tic.css" type="text/css">
		<script language="JavaScript">
			function NeuFenster( link ) {
				MeinFenster = window.open( link, "Artikel", "width=800,height=300,scrollbars=yes,resizable=yes");
				MeinFenster.focus();
			}

//			if ( top.frames.length < 2) {
//				window.open("./frameset.html","_top");
//			}
		</script>
		<script type="text/javascript" src="./overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	</head>
	<body bgcolor="#FFFFFF" link="#0000ff" vlink="#0000ff" alink="#ff0000" style="margin:0px; padding:0px; background-image:url(images/bg.jpg); background-repeat:repeat-x">
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<table width="99%"><tr><td align="center" valign="top" width="150">
<?php
	include('./menu.inc.php');
?>
		</td><td align="center" valign="top">
			<font size="5" bgcolor="#eeeeee"><b>T.I.C. | Tactical Information Center der Meta <?=$meta?></b></font>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="-1">
<?php
	if ($error_code != 0)
		include('./inc_errors.php');
	else {
		include('./inc_accinfo.php');

		$mtime = microtime();
		$mtime = explode(" ", $mtime);
		$mtime = $mtime[1] + $mtime[0];
		$mid_time = $mtime;
		if (isset($_GET['auto'])) echo "Auto-Refresh...";
		if ($Benutzer['pwdandern'] != 1) {
			include('./inc_'.$modul.'.php');
		} else {
			include('./inc_pwdandern.php');
		}
		if ($error_code != 0) include('./inc_errors.php');
	}
?>
			</font>
			<br>
			<hr width="99%">
			<table width="99%"><tr>
				<td align="left" valign="top" width="40%">
					<font size="-1">T.I.C. v<?=$version?></font><br />
					<a href="http://game.galaxynet.4players.de/game/login.php" target="_blank"><img src="http://portal.galaxynet.4players.de/banner_images/gn-button.gif"/></a>
				</td>
				<td align="center" valign="top" width="20%"><font size="-1">
					erstellt in<br>
<?php
	$mtime = microtime();
	$mtime = explode(" ", $mtime);
	$mtime = $mtime[1] + $mtime[0];
	$end_time = $mtime;
	echo "					".sprintf("%01.3f", $end_time - $start_time)." sec\n";
	if (isset($mid_time) && $mid_time != 0) {
		echo "					<br>\n";
		echo "					(".sprintf("%01.3f", $mid_time - $start_time)." sec)\n";
	}
?>
				</font></td>
				<td align="right" valign="top" width="40%">
					<a href="http://www.tic-entwickler.de" target="_blank"><img src="./bilder/TICELogo.jpg"/></a>
				</td>
			</tr></table>
		</td></tr></table>
	</body>
</html>