<!-- START: inc_show_login -->
<?php
	if ( !isset( $sort ) ) {
		$sort = 'ASC';
		$newsort = 'ASC';
	} else {
		if ( $sort=='ASC') {
			$newsort = 'DESC';
		} else {
			$newsort = 'ASC';
		}
	}
?>
<center>
	<h2>Liste der letzten Anmeldungen</h2>
	<table width="400" align="center">
		<tr class="datatablehead">
			<th>Nummer</th>
			<th>Allianz</th>
			<th>Name</th>
			<th><a class="datatablesortselected" href="./main.php?modul=show_login&amp;sort=<?=$newsort?>">Anmeldedatum</a></th>
		</tr>
<?php
	$action = "";
	$SQL_Result = tic_mysql_query('SELECT name, lastlogin, allianz FROM `gn4accounts` where ticid="'.$Benutzer['ticid'].'" ORDER BY lastlogin '.$sort.', name;', $SQL_DBConn) or $error_code = 4;
	for ( $i=0; $i<mysql_num_rows($SQL_Result); $i++) {
		if ( $i%2 == 0 ) {
			$colour='class="fieldnormallight"';
		} else {
			$colour='class="fieldnormaldark"';
		}
		$tmp_name = mysql_result($SQL_Result, $i, 'name');

		// determine alli-name
		$tmp_alli = mysql_result($SQL_Result, $i, 'allianz');
		$SQL_Result2 = tic_mysql_query('SELECT tag FROM `gn4allianzen` WHERE id="'.$tmp_alli.'";', $SQL_DBConn) or $error_code = 4;
		$tmp_tag = mysql_result($SQL_Result2, 0, 'tag');

		$tmp_lastlogin = mysql_result($SQL_Result, $i, 'lastlogin');
		if ( $tmp_lastlogin == '' )
			$tmp_lastlogin = "0";  /* in old format!! - because all new ones are '' - force the update in db */

		/* change the format (if old format xx.xx.xxxx found) - update in db */
		if ( substr( $tmp_lastlogin, 2,1 )== '.' ) {
			$xdate = explode( ".", $tmp_lastlogin );
			if ( $xdate[0] && $xdate[1] && $xdate[2] ) {
				$tmp_lastlogin = mktime(0,0,0,$xdate[1],$xdate[0],$xdate[2]);
			} else {
				$tmp_lastlogin = 0;
			}
			if ( $tmp_lastlogin == -1 ) $tmp_lastlogin = 0;
			tic_mysql_query('update `gn4accounts` SET lastlogin="'.$tmp_lastlogin.'" WHERE name="'.$tmp_name.'" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn) or $error_code = 4;
		}

		/* change the format (if old format xxxx-xx-xx found) - update in db */
		if ( substr( $tmp_lastlogin, 4,1 )== '-' ) {
			$xdate = explode( "-", $tmp_lastlogin );
			if ( $xdate[0] && $xdate[1] && $xdate[2] ) {
				$tmp_lastlogin = mktime(0,0,0,$xdate[1],$xdate[2],$xdate[0]);
			} else {
				$tmp_lastlogin = 0;
			}
			if ( $tmp_lastlogin == -1 ) $tmp_lastlogin = 0;
			tic_mysql_query('update `gn4accounts` SET lastlogin="'.$tmp_lastlogin.'" WHERE name="'.$tmp_name.'" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn) or $error_code = 4;
		}

		if ( $tmp_lastlogin == -1 ) {
			$tmp_lastlogin = 0;
			tic_mysql_query('update `gn4accounts` SET lastlogin="'.$tmp_lastlogin.'" WHERE name="'.$tmp_name.'" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn) or $error_code = 4;
		}

		$j=$i+1;
		echo "		<tr ".$colour.">";
		echo "<td align=\"right\">".$j.".)&nbsp;</td>\n";
		echo "<td align=\"center\">[".$tmp_tag."]</td>";
		echo "<td>".$tmp_name."</td>";
		echo "<td align=\"center\" style=\"white-space:nowrap\">".($tmp_lastlogin?strftime("%d.%m.%Y %H:%M", $tmp_lastlogin):"nie")."</td></tr>\n";
	}
?>
	</table>
</center>
<!-- ENDE: inc_show_login -->
