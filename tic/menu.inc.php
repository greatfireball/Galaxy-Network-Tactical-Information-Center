<!-- START: menu.inc.php -->
<font color="#000000"><b>T.I.C. Navigation</b></font>
<br>
<br>
<table width="120">
	<tr>
		<td width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menutop">Taktik</td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=taktikbildschirm&mode=1">Incomings</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=taktikbildschirm&mode=2">Flotten</a></td>
	</tr>
<?php
	if ($Benutzer['rang']>=$Rang_GC) {
		echo "	<tr>\n";
		echo "		<td bgcolor=\"#0000FF\" class=\"menu\" width=\"3\">&nbsp;</td>\n";
		echo "		<td bgcolor=\"#6490BB\" class=\"menu\"><a href=\"./main.php?modul=taktikbildschirm&mode=3\">Alles</a></td>\n";
		echo "	</tr>\n";
	}
	echo "	<tr>\n";
	echo "		<td bgcolor=\"#0000FF\" class=\"menu\" width=\"3\">&nbsp;</td>\n";
	echo "		<td bgcolor=\"#6490BB\" class=\"menu\"><a href=\"./main.php?modul=taktikbildschirm&mode=4&allianz=".$Benutzer['allianz']."\">Ally</a></td>\n";
	echo "	</tr>\n";
?>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=taktikbildschirm&mode=5">Galaxie <?=$Benutzer['galaxie']?></a></td>
	</tr>
	<tr>
		<td width="3">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menutop">Tic-Intern</td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=NWshow">Nachtwache</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=scans">Scan-Erfassen</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=showgalascans&displaytype=0&xgala=<?=$Benutzer['galaxie']?>&xplanet=<?=$Benutzer['planet']?>">Scans-Anzeige</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><A HREF="./main.php?modul=forum&faction=show&falli=0&ftopic=0">Forum</A></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=profil">Profil</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><A HREF="./main.php?modul=attplanung">Att-Planung</A></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="help/help.html" target="tic-hilfe">Hilfe</a></td>
	</tr>
<?php
	if ($Benutzer['rang'] >= $Rang_GC) {
		echo "	<tr>\n";
		echo "		<td width=\"3\">&nbsp;</td>\n";
		echo "		<td>&nbsp;</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td width=\"3\">&nbsp;</td>\n";
		echo "		<td bgcolor=\"#6490BB\" class=\"menutop\">Admin</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td bgcolor=\"#0000FF\" class=\"menu\" width=\"3\"> </td>\n";
		echo "		<td bgcolor=\"#6490BB\" class=\"menu\"><A HREF=\"./main.php?modul=management\">Management</A></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td bgcolor=\"#0000FF\" class=\"menu\" width=\"3\"> </td>\n";
		echo "		<td bgcolor=\"#6490BB\" class=\"menu\"><A HREF=\"./main.php?modul=userman\">Benutzerverw.</A></td>\n";
		echo "	</tr>\n";
		if ($Benutzer['rang'] > $Rang_GC) {
			echo "	<tr>\n";
			echo "		<td bgcolor=\"#0000FF\" class=\"menu\" width=\"3\"> </td>\n";
			echo "		<td bgcolor=\"#6490BB\" class=\"menu\"><A HREF=\"./main.php?modul=nachrichtschreiben\">Nachricht</A></td>\n";
			echo "	</tr>\n";
		}
		if ($Benutzer['rang'] >= $Rang_Techniker) {
			echo "	<tr>\n";
			echo "		<td bgcolor=\"#0000FF\" class=\"menu\" width=\"3\"> </td>\n";
			echo "		<td bgcolor=\"#6490BB\" class=\"menu\"><A HREF=\"./main.php?modul=log\">Log</A></td>\n";
			echo "	</tr>\n";
		}
	}
?>
	<tr>
		<td width="3">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menutop">Sonstiges</td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=kampf">Kampf-Simu</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=statistic">Statistic</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=listen">Listen</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=impressum">Impressum</a></td>
	</tr>
	<tr>
		<td bgcolor="#0000FF" class="menu" width="3">&nbsp;</td>
		<td bgcolor="#6490BB" class="menu"><a href="./main.php?modul=logout">Logout</a></td>
	</tr>
	<tr>
		<td width="3">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
		
</table>
<!-- END: menu.inc.php -->
