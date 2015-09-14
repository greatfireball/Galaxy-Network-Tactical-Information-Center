<!-- START: inc_allifleets -->
<?php
	$allianz = isset($_GET['allianz'])?$_GET['allianz']:$Benutzer['allianz'];
?>
<center>
	<font size="+2">Allianz-Flotten&uuml;bersicht</font><br>
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
			echo "		&nbsp;<a href=\"./main.php?modul=allifleets&metanr=".$MetaNummer."\"><nobr> ".$MetaName." <nobr></a>&nbsp;\n";
	}
	mysql_free_result($SQL_Result_metas);

	echo "		<br>\n";
	foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
		if ($AllianzInfo[$AllianzNummer]['meta'] == $_SESSION['metanr']) {
			if ($AllianzInfo[$allianz]['meta'] != $_SESSION['metanr']) $allianz = $AllianzNummer;
			if ($AllianzNummer == $allianz)
				echo "	&nbsp;<font size=\"+1\"><b><nobr>[ ".$AllianzInfo[$AllianzNummer]['tag']." ] ".$AllianzNummerName."<nobr></font></b>&nbsp;\n";
			else
				echo "	&nbsp;<a href=\"./main.php?modul=allifleets&allianz=".$AllianzNummer."\"><nobr>[ ".$AllianzTag[$AllianzNummer]." ]<nobr></a>&nbsp;\n";
		}
	}
?>
	<br><br>
	<table border="0" cellspacing="2" cellpadding="1" width="100%" bgcolor="#BBBBBB">
		<colgroup>
			<col width="55">
			<col width="*">
			<col width="6%">
			<col width="6%">
			<col width="5%">
			<col width="5%">
			<col width="6%">
			<col width="6%">
			<col width="6%">
			<col width="6%">
			<col width="5%">
			<col width="5%">
			<col width="5%">
			<col width="5%">
			<col width="6%">
			<col width="6%">
		</colgroup>
		<tr bgcolor="#666666">
			<th><font size="-1" color="#FFFFFF">Sektor</font></td>
			<th><font size="-1" color="#FFFFFF">Name</font></td>
			<th colspan="5"><font size="-1" color="#FFFFFF">Deffensiv</font></td>
			<th colspan="9"><font size="-1" color="#FFFFFF">Offensiv</font></td>
		</tr>
		<tr bgcolor="#444444">
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th><font size="-2" color="#eeeeee" title="leichtes Orbitalgesch&uuml;tz">LO</font></th>
			<th><font size="-2" color="#eeeeee" title="leichtes Raumgesch&uuml;tz">LR</font></th>
			<th><font size="-2" color="#eeeeee" title="mittleres Raumgesch&uuml;tz">MR</font></th>
			<th><font size="-2" color="#eeeeee" title="schweres Raumgesch&uuml;tz">SR</font></th>
			<th><font size="-2" color="#eeeeee" title="Abfangj&auml;ger">AJ</font></th>
			<th><font size="-2" color="#eeeeee" title="J&auml;ger">J&auml;</font></th>
			<th><font size="-2" color="#eeeeee" title="Bomber">Bo</font></th>
			<th><font size="-2" color="#eeeeee" title="Fregatten">Fr</font></th>
			<th><font size="-2" color="#eeeeee" title="Zerst&ouml;rer">Ze</font></th>
			<th><font size="-2" color="#eeeeee" title="Kreuzer">Kr</font></th>
			<th><font size="-2" color="#eeeeee" title="Schlachtschiffe">SS</font></th>
			<th><font size="-2" color="#eeeeee" title="Tr&auml;ger">Tr</font></th>
			<th><font size="-2" color="#eeeeee" title="Kaperschiffe">Ka</font></b></th>
			<th><font size="-2" color="#eeeeee" title="Schildschiffe">Sch</font></th>
		</tr>
<?php
	$SQL_Result2 = mysql_query('SELECT id, name, galaxie, planet FROM `gn4accounts` WHERE allianz="'.$allianz.'" order by galaxie, planet', $SQL_DBConn);
	for ( $i=0; $i<mysql_num_rows($SQL_Result2); $i++ ) {
		$gala   = mysql_result($SQL_Result2, $i, 'galaxie');
		$planet = mysql_result($SQL_Result2, $i, 'planet');
		$name   = mysql_result($SQL_Result2, $i, 'name');

		$SQL_Result = mysql_query('SELECT sfj ,sfb ,sff ,sfz ,sfkr ,sfsa ,sft ,sfka ,sfsu FROM `gn4scans` WHERE rg="'.$gala.'" and rp="'.$planet.'" and type=1', $SQL_DBConn);
		$SQL_Result3 = mysql_query('SELECT glo,glr,gmr,gsr,ga FROM `gn4scans` WHERE rg="'.$gala.'" and rp="'.$planet.'" and type=3', $SQL_DBConn);

		if ( mysql_num_rows($SQL_Result) == 0 ) {
			$ja     = " ";
			$bo     = " ";
			$fr     = " ";
			$ze     = " ";
			$kr     = " ";
			$sl     = " ";
			$tr     = " ";
			$ka     = " ";
			$ca     = " ";
		} else {
			$ja     = mysql_result($SQL_Result, 0, 'sfj' );
			$bo     = mysql_result($SQL_Result, 0, 'sfb' );
			$fr     = mysql_result($SQL_Result, 0, 'sff' );
			$ze     = mysql_result($SQL_Result, 0, 'sfz' );
			$kr     = mysql_result($SQL_Result, 0, 'sfkr' );
			$sl     = mysql_result($SQL_Result, 0, 'sfsa' );
			$tr     = mysql_result($SQL_Result, 0, 'sft' );
			$ka     = mysql_result($SQL_Result, 0, 'sfka' );
			$ca     = mysql_result($SQL_Result, 0, 'sfsu' );
		}
		if ( mysql_num_rows($SQL_Result3) == 0 ) {
			$lo     = " ";
			$ro     = " ";
			$mr     = " ";
			$sr     = " ";
			$aj     = " ";
		} else {
			$lo     = mysql_result($SQL_Result3, 0, 'glo' );
			$ro     = mysql_result($SQL_Result3, 0, 'glr' );
			$mr     = mysql_result($SQL_Result3, 0, 'gmr' );
			$sr     = mysql_result($SQL_Result3, 0, 'gsr' );
			$aj     = mysql_result($SQL_Result3, 0, 'ga' );
		}
    $gja = $gja+$ja ;
    $gbo = $gbo+$bo ;
    $gfr = $gfr+$fr ;
    $gze = $gze+$ze ;
    $gkr = $gkr+$kr ;
    $gsl = $gsl+$sl ;
    $gtr = $gtr+$tr ;
    $gka = $gka+$ka ;
    $gca = $gca+$ca ;
    $glo = $glo+$lo ;
    $gro = $gro+$ro ;
    $gmr = $gmr+$mr ;
    $gsr = $gsr+$sr ;
    $gaj = $gaj+$aj ;
		echo "		<tr>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"center\"><font size=\"-1\">".$gala.":".$planet."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\"><font size=\"-1\"><nobr>".$name."</nobr></font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$lo."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$ro."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$mr."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$sr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$aj."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$ja."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$bo."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$fr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$ze."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$kr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$sl."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$tr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$ka."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$ca."</font></td>\n";
		echo "		</tr>\n";
        }
    echo "		<tr>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"center\"><font size=\"-1\">Ally</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\"><font size=\"-1\"><nobr>Gesammt</nobr></font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$glo."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gro."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gmr."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gsr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gaj."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gja."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gbo."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gfr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gze."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gkr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gsl."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gtr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gka."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gca."</font></td>\n";
		echo "		</tr>\n";
    $gja = IntVal($gja/mysql_num_rows($SQL_Result2)) ;
    $gbo = IntVal($gbo/mysql_num_rows($SQL_Result2)) ;
    $gfr = IntVal($gfr/mysql_num_rows($SQL_Result2)) ;
    $gze = IntVal($gze/mysql_num_rows($SQL_Result2)) ;
    $gkr = IntVal($gkr/mysql_num_rows($SQL_Result2)) ;
    $gsl = IntVal($gsl/mysql_num_rows($SQL_Result2)) ;
    $gtr = IntVal($gtr/mysql_num_rows($SQL_Result2)) ;
    $gka = IntVal($gka/mysql_num_rows($SQL_Result2)) ;
    $gca = IntVal($gca/mysql_num_rows($SQL_Result2)) ;
    $glo = IntVal($glo/mysql_num_rows($SQL_Result2)) ;
    $gro = IntVal($gro/mysql_num_rows($SQL_Result2)) ;
    $gmr = IntVal($gmr/mysql_num_rows($SQL_Result2)) ;
    $gsr = IntVal($gsr/mysql_num_rows($SQL_Result2)) ;
    $gaj = IntVal($gaj/mysql_num_rows($SQL_Result2)) ;
		echo "		<tr>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"center\"><font size=\"-1\">Ally</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\"><font size=\"-1\"><nobr>Durchschnitt</nobr></font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$glo."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gro."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gmr."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gsr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gaj."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gja."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gbo."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gfr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gze."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gkr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gsl."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gtr."</font></td>\n";
		echo "			<td bgcolor=\"#dddddd\" align=\"right\"><font size=\"-1\">".$gka."</font></td>\n";
		echo "			<td bgcolor=\"#eeeeee\" align=\"right\"><font size=\"-1\">".$gca."</font></td>\n";
		echo "		</tr>\n";

?>
	</table>
	<br>
	<font size="-1"><a href="./inc_printableallifleets.php" target="forptint">Druckerfreundliche Version</a></font>
</center>
<!-- ENDE: inc_allifleets -->
