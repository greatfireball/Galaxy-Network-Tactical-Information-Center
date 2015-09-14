<CENTER>
<font size="4">Übersicht der Scanner</font><br>

    Sortieren nach: <A HREF="./main.php?modul=scanner&sort=svs">Scanverstärkern</A>, <A HREF="./main.php?modul=scanner&sort=scans">Anzahl Scans</A><BR>
    <BR>

<TABLE>
  <TR>
    <TD BGCOLOR=#333333 COLSPAN="5"><font color="#FFFFFF" size="-1"><B>
      <?=$ScanTyp[1]?>
      </B></font></TD>
    <TD><font size="-1"><BR>
      </font></TD>
    <TD BGCOLOR=#333333 COLSPAN="5"><font color="#FFFFFF" size="-1"><B>
      <?=$ScanTyp[2]?>
      </B></font></TD>
  </TR>
  <TR>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">ID</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">Name</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">Tag</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">SV's</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">Scans</font></B></P>
    </TD>
    <TD><font size="-1"><BR>
      </font></TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">ID</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">Name</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">Tag</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">SV's</font></B></P>
    </TD>
    <TD>
      <P CLASS="dunkel"><B><font size="-1">Scans</font></B></P>
    </TD>
  </TR>
  <?php
            if (!isset($sort)) $sort = 'svs';
            if ($sort == 'scans')
                $sort = 'scans DESC, svs DESC';
            else
                $sort = 'svs DESC, scans DESC';
            $SQL_Result1 = mysql_query('SELECT id, galaxie, planet, name, allianz, svs, umod, scans FROM `gn4accounts` WHERE scantyp="1" and ticid="'.$Benutzer['ticid'].'" AND svs>"0" ORDER BY '.$sort.', galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num1 = mysql_num_rows($SQL_Result1);
            $SQL_Result2 = mysql_query('SELECT id, galaxie, planet, name, allianz, svs, umod, scans FROM `gn4accounts` WHERE scantyp="2" and ticid="'.$Benutzer['ticid'].'" AND svs>"0" ORDER BY '.$sort.', galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num2 = mysql_num_rows($SQL_Result2);
            if ($SQL_Num1 > $SQL_Num2)
                $list_max = $SQL_Num1;
            else
                $list_max = $SQL_Num2;
            for ($n = 0; $n < $list_max; $n++) {
                echo '<TR>';
                if ($n < $SQL_Num1) {
                    $farbe = '';
                    if (mysql_result($SQL_Result1, $n, 'umod') != '') $farbe = '_blau';
                    if (mysql_result($SQL_Result1, $n, 'id') == $Benutzer['id']) $farbe = '_gruen';
                    echo '  <td BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">'.mysql_result($SQL_Result1, $n, 'galaxie').':'.mysql_result($SQL_Result1, $n, 'planet').'</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1">'.mysql_result($SQL_Result1, $n, 'name').'</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">['.$AllianzTag[mysql_result($SQL_Result1, $n, 'allianz')].']</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['hell'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result1, $n, 'svs')).'</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result1, $n, 'scans')).'</td></font>';
                } else {
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                }
                echo '  <td><font size="-1"><BR></td></font>';
                if ($n < $SQL_Num2) {
                    $farbe = '';
                    if (mysql_result($SQL_Result2, $n, 'umod') != '') $farbe = '_blau';
                    if (mysql_result($SQL_Result2, $n, 'id') == $Benutzer['id']) $farbe = '_gruen';
                    echo '  <td BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">'.mysql_result($SQL_Result2, $n, 'galaxie').':'.mysql_result($SQL_Result2, $n, 'planet').'</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1">'.mysql_result($SQL_Result2, $n, 'name').'</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">['.$AllianzTag[mysql_result($SQL_Result2, $n, 'allianz')].']</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['hell'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result2, $n, 'svs')).'</td></font>';
                    echo '  <td BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result2, $n, 'scans')).'</td></font>';
                } else {
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                    echo '  <td><font size="-1"><BR></td></font>';
                }
                echo '</TR>';
            }
        ?>
</TABLE>
    <BR>
    <FONT SIZE="-1"><B>(<U>Blau</U> makierte Spieler sind im Urlaubs-Modus)</B></FONT><BR>
<CENTER>
