<!-- START: inc_allianz -->
<?php
	if (!isset($allianz)) $allianz = $Benutzer['allianz'];
	if (!isset($_GET['orderby'])) $_GET['orderby'] = "sektor";
	if (!isset($_GET['orderdir'])) $_GET['orderdir'] = "asc";
	$orderdir_new = ($_GET['orderdir'] == "asc"?"desc":"asc");
?>
<center>
	<font size="+2">Allianz-&Uuml;bersicht</font><br>
<?php
	if (isset($_GET['metanr'])) $_SESSION['metanr'] = $_GET['metanr'];
	if (!isset($_SESSION['metanr'])) $_SESSION['metanr'] = $Benutzer['ticid'];
	$SQL_Query = "SELECT * FROM gn4vars WHERE name='ticeb' ORDER BY value;";
	$SQL_Result_metas = mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 4;
	for ($m=0; $m<mysql_num_rows($SQL_Result_metas); $m++) {
		$MetaNummer = mysql_result($SQL_Result_metas, $m, 'ticid');
		$MetaName = mysql_result($SQL_Result_metas, $m, 'value');
		if ($MetaNummer == $_SESSION['metanr'])
			echo "		&nbsp;<font size=\"+1\"><b><nobr>".$MetaName."<nobr></font></b>&nbsp;\n";
		else
			echo "		&nbsp;<a href=\"./main.php?modul=allianz&metanr=".$MetaNummer."\"><nobr> ".$MetaName." <nobr></a>&nbsp;\n";
	}
	mysql_free_result($SQL_Result_metas);

	echo "		<br>\n";
	foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
		if ($AllianzInfo[$AllianzNummer]['meta'] == $_SESSION['metanr']) {
			if ($AllianzInfo[$allianz]['meta'] != $_SESSION['metanr']) $allianz = $AllianzNummer;
			if ($AllianzNummer == $allianz)
				echo "	&nbsp;<font size=\"+1\"><b><nobr>[ ".$AllianzInfo[$AllianzNummer]['tag']." ] ".$AllianzNummerName."<nobr></font></b>&nbsp;\n";
			else
				echo "	&nbsp;<a href=\"./main.php?modul=allianz&allianz=".$AllianzNummer."\"><nobr>[ ".$AllianzTag[$AllianzNummer]." ]<nobr></a>&nbsp;\n";
		}
	}
?>
	<br><br>
	<table border="0" cellspacing="2" cellpadding="1" width="100%">
		<colgroup>
			<col width="55">
			<col width="*">
			<col width="75">
			<col width="75">
			<col width="75">
			<col width="75">
			<col width="20%">
			<col width="15%">
		</colgroup>
		<tr>
			<th bgcolor="#333333"><a href="./main.php?modul=allianz&allianz=<?=$allianz?>&orderby=sektor&orderdir=<?=$orderdir_new?>"><font color="<?php echo ($_GET['orderby']=="sektor"?"#DD6666":"#DDDD66"); ?>" size="-1">Sektor</font></a></th>
			<th bgcolor="#333333"><a href="./main.php?modul=allianz&allianz=<?=$allianz?>&orderby=rang&orderdir=<?=$orderdir_new?>"><font color="<?php echo ($_GET['orderby']=="rang"?"#DD6666":"#DDDD66"); ?>" size="-1">Rang</font></a><font color="#FFFFFF" size="-1"> / </font><a href="./main.php?modul=allianz&orderby=name&orderdir=<?=$orderdir_new?>"><font color="<?php echo ($_GET['orderby']=="name"?"#DD6666":"#DDDD66"); ?>" size="-1">Name</font></a></th>
			<th bgcolor="#333333"><font color="#FFFFFF" size="-1">M-Exen</font></th>
			<th bgcolor="#333333"><font color="#FFFFFF" size="-1">K-Exen</font></th>
			<th bgcolor="#333333"><a href="./main.php?modul=allianz&allianz=<?=$allianz?>&orderby=svs&orderdir=<?=$orderdir_new?>"><font color="<?php echo ($_GET['orderby']=="svs"?"#DD6666":"#DDDD66"); ?>" size="-1">SVs</font></a></th>
			<th bgcolor="#333333"><a href="./main.php?modul=allianz&allianz=<?=$allianz?>&orderby=sbs&orderdir=<?=$orderdir_new?>"><font color="<?php echo ($_GET['orderby']=="sbs"?"#DD6666":"#DDDD66"); ?>" size="-1">SBs</font></a></th>
			<th bgcolor="#333333"><font color="#FFFFFF" size="-1">Scantyp</font></th>
			<th bgcolor="#333333"><a href="./main.php?modul=allianz&allianz=<?=$allianz?>&orderby=login&orderdir=<?=$orderdir_new?>"><font color="<?php echo ($_GET['orderby']=="login"?"#DD6666":"#DDDD66"); ?>" size="-1">Last Login</font></a></th>
		</tr>
<?
	if ($_GET['orderby'] == "sektor") {
		$_GET['orderstring'] = "galaxie ".$_GET['orderdir'].", planet ".$_GET['orderdir'];
	} elseif ($_GET['orderby'] == "rang") {
		$_GET['orderstring'] = "rang ".$_GET['orderdir'].", galaxie , planet";
	} elseif ($_GET['orderby'] == "name") {
		$_GET['orderstring'] = "name ".$_GET['orderdir'];
	} elseif ($_GET['orderby'] == "svs") {
		$_GET['orderstring'] = "svs ".$_GET['orderdir'].", galaxie, planet";
	} elseif ($_GET['orderby'] == "sbs") {
		$_GET['orderstring'] = "sbs ".$_GET['orderdir'].", galaxie, planet";
	} elseif ($_GET['orderby'] == "login") {
		$_GET['orderstring'] = "lastlogin ".$_GET['orderdir'].", galaxie, planet";
	}

	$gesamt_exen_m = 0;
	$gesamt_exen_k = 0;
	$sb_spieler = 0;
	$gesamt_sbs = 0;
	$SQL_Result = mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$allianz.'" ORDER BY '.$_GET['orderstring'].';', $SQL_DBConn) or $error_code = 4;
	$SQL_Num = mysql_num_rows($SQL_Result);
	for ($n = 0; $n < $SQL_Num; $n++) {
		$zusatz = '';
		$lastlogin = mysql_result($SQL_Result, $n, 'lastlogin');
		if ($lastlogin == "" || $lastlogin == "0000-00-00" || $lastlogin == 0 || $lastlogin < (time() - (3 * 24 * 3600)) ) {
			$zusatz = '_rot';
		}
		if (mysql_result($SQL_Result, $n, 'umod') != '') {
			$zusatz = '_blau';
		}
		if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $zusatz = '_gruen';
		$SQL_Result2 = mysql_query('SELECT me, ke FROM `gn4scans` WHERE rg="'.mysql_result($SQL_Result, $n, 'galaxie').'" AND rp="'.mysql_result($SQL_Result, $n, 'planet').'" and ticid="'.$Benutzer['ticid'].'" AND type="0"', $SQL_DBConn);
		if (mysql_num_rows($SQL_Result2) != 1) {
			$exen_m = 0;
			$exen_k = 0;
		} else {
			$exen_m = mysql_result($SQL_Result2, 0, 'me');
			$exen_k = mysql_result($SQL_Result2, 0, 'ke');
		}

		$koord_g = mysql_result($SQL_Result, $n, 'galaxie');
		$koord_p = mysql_result($SQL_Result, $n, 'planet');

		echo "		<tr>\n";
		echo "			<td bgcolor=\"".$htmlstyle['hell'.$zusatz]."\" align=\"center\"><font size=\"-1\"><a href=\"./main.php?modul=showgalascans&xgala=".$koord_g."&xplanet=".$koord_p."&displaymode=0\">".$koord_g.":".$koord_p."</a></font></td>\n";
		echo "			<td bgcolor=\"".$htmlstyle['dunkel'.$zusatz]."\"><font size=\"-1\"><nobr><img src=\"".$RangImage[mysql_result($SQL_Result, $n, 'rang')]."\" width=\"20\" height=\"20\" border=\"0\" alt=\"".$RangName[mysql_result($SQL_Result, $n, 'rang')]."\" title=\"".$RangName[mysql_result($SQL_Result, $n, 'rang')]."\" align=\"middle\"> <a href=\"./main.php?modul=anzeigen&id=".mysql_result($SQL_Result, $n, 'id')."\">".mysql_result($SQL_Result, $n, 'name')."</a></font></td>\n";
		echo "			<td bgcolor=\"".$htmlstyle['hell'.$zusatz]."\" align=\"right\"><font size=\"-1\">".ZahlZuText($exen_m)."</font></td>\n";
		echo "			<td bgcolor=\"".$htmlstyle['dunkel'.$zusatz]."\" align=\"right\"><font size=\"-1\">".ZahlZuText($exen_k)."</font></td>\n";
		echo "			<td bgcolor=\"".$htmlstyle['hell'.$zusatz]."\" align=\"right\"><font size=\"-1\">".ZahlZuText(mysql_result($SQL_Result, $n, 'svs'))."</font></td>\n";
		echo "			<td bgcolor=\"".$htmlstyle['dunkel'.$zusatz]."\" align=\"right\"><font size=\"-1\">".ZahlZuText(mysql_result($SQL_Result, $n, 'sbs'))."</font></td>\n";
		echo "			<td bgcolor=\"".$htmlstyle['hell'.$zusatz]."\"><font size=\"-1\">".$ScanTyp[mysql_result($SQL_Result, $n, 'scantyp')]."</font></td>\n";
		echo "			<td bgcolor=\"".$htmlstyle['dunkel'.$zusatz]."\" align=\"center\"><font size=\"-1\"><nobr>".($lastlogin?strftime("%d.%m.%Y %H:%M", $lastlogin):"nie")."</nobr></font></td>\n";
		echo "		</tr>\n";
		$gesamt_exen_m = $gesamt_exen_m + $exen_m;
		$gesamt_exen_k = $gesamt_exen_k + $exen_k;
		$gesamt_sbs = $gesamt_sbs + mysql_result($SQL_Result, $n, 'sbs');
		if (mysql_result($SQL_Result, $n, 'sbs') > 0) $sb_spieler++;
	}
	if ($sb_spieler > 0)
		$durchschnitt_sbs = IntVal($gesamt_sbs / $sb_spieler);
	else
		$durchschnitt_sbs = 0;
?>
		<tr>
			<td colspan="2" align="right"><font size="-1"><b>Extraktoren der Allianz:</b></font></td>
			<td bgcolor="<?=$htmlstyle['dunkel']?>" align="right"><font size="-1"><?=ZahlZuText($gesamt_exen_m)?></font></td>
			<td bgcolor="<?=$htmlstyle['hell']?>" align="right"><font size="-1"><?=ZahlZuText($gesamt_exen_k)?></font></td>
			<td></td>
			<td bgcolor="<?=$htmlstyle['hell']?>" align="right"><font size="-1"><?=ZahlZuText($durchschnitt_sbs)?></font></td>
			<td colspan="2" align="left"><font size="-1"><b>Scanblocker pro Spieler</b></font></td>
		</tr>
	</table>
	<br>
	<font size="-1"><b>(<u>Blau</u> makierte Spieler sind im Urlaubs-Modus)</b></font><br>
	<font size="-1"><b>(<u>Rot</u> makierte Spieler waren seit min. drei Tagen nicht online)</b></font>
</center>
<!-- ENDE: inc_allianz -->
