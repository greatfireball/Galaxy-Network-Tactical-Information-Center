<!-- START: inc_summary -->
<?php
	$out_allis = "		<th align=\"right\">&nbsp;Meta:&nbsp;</th>\n";
	$out_incs = "		<th align=\"right\">&nbsp;Incomings:&nbsp;</th>\n";
	$out_online = "		<th align=\"right\">&nbsp;Online:&nbsp;</th>\n";

	$SQL_Query = "SELECT value, ticid FROM `gn4vars` WHERE name = 'ticeb' ORDER BY value;";
	$SQL_Result_Metas = mysql_query($SQL_Query, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
	$manzahl = mysql_num_rows($SQL_Result_Metas);

	$tsec = $Ticks['lange']*60;
	$time_now = ((int)(time()/($tsec)))*($tsec);

	for($b=0; $b<$manzahl; $b++) {
		$mname = mysql_result($SQL_Result_Metas, $b, 'value');
		$mticid = mysql_result($SQL_Result_Metas, $b, 'ticid');
		$incs_offen = 0;
		$incs_overtime = 0;
		$incs_safe = 0;

		$SQL_Query = "SELECT galaxie FROM gn4accounts WHERE ticid=".$mticid." GROUP BY galaxie;";
		$SQL_Result_alli_inc = mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 41;

		for ( $j=0; $j < mysql_num_rows($SQL_Result_alli_inc); $j++ ) {

			$SQL_Query = "SELECT ankunft, save FROM gn4flottenbewegungen WHERE verteidiger_galaxie = ".mysql_result($SQL_Result_alli_inc, $j, 'galaxie')." AND modus = 1;";
			$SQL_Result_alli_inc_fleets = mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 42;

			for ( $k=0; $k < mysql_num_rows($SQL_Result_alli_inc_fleets); $k++ ) {
				if ( mysql_result($SQL_Result_alli_inc_fleets, $k, 'save') != 0 )
					if (mysql_result($SQL_Result_alli_inc_fleets, $k, 'ankunft') - $time_now > ($tsec * 12))
						$incs_offen++;
					else
						$incs_overtime++;
				else
					$incs_safe++;
			}
		}
		mysql_free_result($SQL_Result_alli_inc);

		$SQL_Query = "SELECT count(*) FROM gn4accounts WHERE ticid = ".$mticid.";";
		$SQL_Result_alli_user = mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 43;

		$SQL_Query = "SELECT galaxie, planet, name, allianz, rang FROM gn4accounts WHERE ticid=".$mticid." AND lastlogin > ".(time() - 300)." ORDER BY galaxie, planet;";
		$SQL_Result_alli_user_online = mysql_query($SQL_Query, $SQL_DBConn) or $error_code = 44;

		$tt_incs = "<b>Incs bei Meta ".$mname." :</b><br />";
		if ($incs_offen + $incs_overtime + $incs_safe > 0) {
			if ($incs_offen > 0) $tt_incs .= "<span class=textincopen>".$incs_offen." offene</span><br />";
			if ($incs_overtime > 0) $tt_incs .= "<span class=textincovertime>".$incs_overtime." offene (nicht mehr deffbar)</span><br />";
			if ($incs_safe > 0) $tt_incs .= "<span class=textincsafe>".$incs_safe." sichere</span><br />";
		} else
			$tt_incs .= "<i>keine</i><br />";

		$out_online_names = "<b>Online bei Meta ".$mname." :</b><br />";
		if (mysql_num_rows($SQL_Result_alli_user_online) > 0)
	                for ($n = 0; $n < mysql_num_rows($SQL_Result_alli_user_online); $n++) {
				$rang = mysql_result($SQL_Result_alli_user_online, $n, 'rang');
				$out_online_names .= mysql_result($SQL_Result_alli_user_online, $n, 'galaxie').":".mysql_result($SQL_Result_alli_user_online, $n, 'planet')." [".$AllianzTag[mysql_result($SQL_Result_alli_user_online, $n, 'allianz')]."] ".($rang == $Rang_STechniker?"<font color=#ff0000>":($rang == $Rang_Techniker?"<font color=#0000ff>":"")).($rang > $Rang_GC?"<b>":"").mysql_result($SQL_Result_alli_user_online, $n, 'name').($rang > $Rang_GC?"</b>":"").($rang >= $Rang_Techniker?"</font>":"")."<br>";
			}
		else
			$out_online_names .= "<i>keiner</i><br />";

		$out_allis	.= "		<td align=\"center\" width=\"75\" onmouseover=\"return overlib('<b>".$mname."</b>');\" onmouseout=\"return nd();\">&nbsp;".($incs_offen>0?"<span class=\"textincopen\">":"").$mname.($incs_offen>0?"</span>":"")."&nbsp;</td>\n";
		$out_incs	.= "		<td align=\"center\" onmouseover=\"return overlib('".$tt_incs."');\" onmouseout=\"return nd();\">&nbsp;<a href=\"./main.php?modul=taktikbildschirm&mode=1&metanr=".$mticid."\"><span class=\"textinc".($incs_offen>0?"open":"none")."\">".$incs_offen."</span> / <span class=\"textinc".($incs_overtime>0?"overtime":"none")."\">".$incs_overtime."</span> / <span class=\"textinc".($incs_safe>0?"safe":"none")."\">".$incs_safe."</span></a>&nbsp;</td>\n";
		$out_online 	.= "		<td align=\"center\" onmouseover=\"return overlib('".$out_online_names."');\" onmouseout=\"return nd();\">&nbsp;".mysql_num_rows($SQL_Result_alli_user_online)." / ".mysql_result($SQL_Result_alli_user, 0, 'count(*)')."&nbsp;</td>\n";

		mysql_free_result($SQL_Result_alli_user);
		mysql_free_result($SQL_Result_alli_user_online);
	}
	mysql_free_result($SQL_Result_Metas);
?>
<table id="ticsummary">
	<tr>
<?php
	echo $out_allis;
?>
	</tr>
	<tr>
<?php
	echo $out_incs;
?>
	</tr>
	<tr>
<?php
	echo $out_online;
?>
	</tr>
</table>
<!-- ENDE: inc_summary -->