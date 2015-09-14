<!-- START: inc_accountinfo -->
<table width="100%" cellspacing="6" border="0" cellpadding="1">
	<colgroup>
		<col width="85%">
		<col width="15%">
	</colgroup>
	<tr bgcolor="#dddddd">
		<td valign="middle" rowspan="2" align="center">
<?php
	include( "./inc_summary.php" );
?>
		</td>
		<td align="right" valign="top">
			<font size="-1">
				<nobr>[ <?=$AllianzTag[$Benutzer['allianz']]?> ] <?=$Benutzer['name']?></nobr><br>
				<nobr><img src="<?=$RangImage[$Benutzer['rang']]?>" width="20" height="20" border="0" alt="<?=$RangName[$Benutzer['rang']]?>" title="<?=$RangName[$Benutzer['rang']]?>" align="middle"> <?=$Benutzer['galaxie']?>:<?=$Benutzer['planet']?></nobr>
<?
	if ($Benutzer['umod'] != '') {
		echo "				<br><font size=\"-2\" COLOR=\"#".$htmlstyle['dunkel_blau']."\"><b>".$Benutzer['umod']."</b></font>\n";
	}
?>
			</font>
		</td>
	</tr>
	<tr bgcolor="#dddddd">
		<td align="right" valign="top">
			<div id="ticktime">
				Serverzeit: <span id="Uhr"><?=date("H:i:s")?></span><br \>
				Letzter Tick: <?=$lasttick."\n"?>
			</div>
		</td>
	</tr>
</table>
<script type="text/javascript">
	var Uhr = document.getElementById('Uhr');
	var TimeServer = new Date("<?=date("M, d Y H:i:s")?>");
	var TimeLocal = new Date();
	var offset = TimeServer.getTime() - TimeLocal.getTime();

	function serverzeit_anzeigen() {
		var jetzt = new Date();
		jetzt.setTime(jetzt.getTime() + offset);
		var Std = jetzt.getHours();
		var Min = jetzt.getMinutes();
		var Sec = jetzt.getSeconds();
		var StdAusgabe  = ((Std < 10) ? "0" + Std : Std);
		var MinAusgabe  = ((Min < 10) ? "0" + Min : Min);
		var SecAusgabe  = ((Sec < 10) ? "0" + Sec : Sec);
		Uhr.innerHTML = StdAusgabe + ':' + MinAusgabe + ':' + SecAusgabe;
		window.setTimeout('serverzeit_anzeigen();', 999);
	}
	window.onload = serverzeit_anzeigen;
</script>
<?
	if ($systemnachricht != '') {
		echo "<p class=\"sysmessage\" style=\"width: 500px;\">\n";
		echo nl2br(htmlentities($systemnachricht))."\n";
		echo "</p>\n";
	}
?>
<br>
<!-- ENDE: inc_accountinfo -->
