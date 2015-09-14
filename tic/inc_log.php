<CENTER>
<B>T.I.C. Log</B><BR>
<BR>
<?
	if ($Benutzer['rang'] < $Rang_Techniker)
		$error_code = 5;
	else {
		if ($Benutzer['rang'] < $Rang_STechniker) {
			$SQL_Result = mysql_query('SELECT * FROM `gn4log` WHERE gn4accounts.allianz="'.$Benutzer['allianz'].'" ORDER BY id DESC;', $SQL_DBConn) or $error_code = 4;
		} else {
			$SQL_Result = mysql_query('SELECT * FROM `gn4log` ORDER BY id DESC;', $SQL_DBConn) or $error_code = 4;
		}
?>
  <TABLE>
    <TR>
      <TH BGCOLOR=333333><font color="FFFFFF" size="-1">Zeit</font></TH>
      <TH BGCOLOR=333333><font color="FFFFFF" size="-1">Meta</font></TH>
      <TH BGCOLOR=333333><font color="FFFFFF" size="-1">Name</font></TH>
      <TH BGCOLOR=333333><font color="FFFFFF" size="-1">Aktion</font></TH>
    </TR>
    <?
        $SQL_Num = mysql_num_rows($SQL_Result);
        for ($n = 0; $n < $SQL_Num; $n++) {
		$user_r = mysql_result($SQL_Result, $n, 'rang');
		$user_a = mysql_result($SQL_Result, $n, 'allianz');
            echo '<TR>';
            echo '  <TD BGCOLOR='.$htmlstyle['dunkel'].'><nobr><font size="-1">'.mysql_result($SQL_Result, $n, 'zeit').'</font></nobr></TD>';
            echo '  <TD BGCOLOR='.$htmlstyle['hell'].'><nobr><font size="-1">'.$AllianzInfo[$user_a]['metaname'].'</font></nobr></TD>';
            echo "  <TD BGCOLOR='".$htmlstyle['dunkel']."'><nobr><font size=\"-1\"><img src=\"".$RangImage[$user_r]."\" width=\"20\" height=\"20\" border=\"0\" alt=\"".$RangName[$user_r]."\" title=\"".$RangName[$user_r]."\" align=\"middle\"><A HREF=\"./main.php?modul=anzeigen&id=".mysql_result($SQL_Result, $n, 'accid')."\"> [".$AllianzInfo[$user_a]['tag']."] ".mysql_result($SQL_Result, $n, 'name')."</A></font><nobr></TD>\n";
            echo '  <TD BGCOLOR='.$htmlstyle['hell'].'><font size="-1">'.mysql_result($SQL_Result, $n, 'aktion').'</font></TD>';
            echo '</TR>';
        }
        if ($SQL_Num == 0) echo '<TD COLSPAN=4><font size="-1"><B>Es existieren keine Log-Einträge</B></font></TD>';
    ?>
  </TABLE>
<?
}
?>
</CENTER>
