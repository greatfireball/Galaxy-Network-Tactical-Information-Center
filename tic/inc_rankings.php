<?PHP
    if (!isset($top_counter)) $top_counter = 10;
?>
<CENTER>
    <font size="4">
    <B>T.I.C. Rankings</B><BR></font>
    <A HREF="./main.php?modul=rankings&top_counter=10">Top 10</A> | <A HREF="./main.php?modul=rankings&top_counter=20">Top 20</A> | <A HREF="./main.php?modul=rankings&top_counter=50">Top 50</A>
    <BR>
    <BR>

  <TABLE WIDTH=100%>
    <TR>
      <TD WIDTH=50%>
        <CENTER>
          <TABLE>
            <TR>
              <TD BGCOLOR=#333333 COLSPAN=4><font color="#FFFFFF" size="-1"><B>Punkte
                (Top
                <?=$top_counter?>
                )</B></font>
            </TR>
            <?php
                            $SQL_Result = mysql_query('SELECT gn4accounts.id, pts, gn4accounts.name, allianz, umod FROM gn4scans, gn4accounts WHERE rg=galaxie AND rp=planet AND type="0" ORDER BY pts DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<TR><TD COLSPAN=4><I>Keine Einträge gefunden</I></TD></TR>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $farbe = '';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $farbe = '_blau';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $farbe = '_gruen';
                                    if (mysql_result($SQL_Result, $n, 'pts') > 0) echo '<TR><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1">'.($n + 1).'.</font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</font></TD><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1"><A HREF="./main.php?modul=anzeigen&id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</A></font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result, $n, 'pts')).'</font></TD></TR>';
                                }
                            }
                        ?>
          </TABLE>
        </CENTER>
      </TD>
      <TD WIDTH=50%>
        <CENTER>
          <TABLE>
            <TR>
              <TD BGCOLOR=#333333 COLSPAN=4><font color="#FFFFFF" size="-1"><B>Extraktoren
                (Top
                <?=$top_counter?>
                )</B></font>
            </TR>
            <?php
                            $SQL_Result = mysql_query('SELECT gn4accounts.id, gn4accounts.name, allianz, me, ke, umod FROM gn4scans, gn4accounts WHERE rg=galaxie and rp=planet AND type="0" ORDER BY (me + ke) DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<TR><TD COLSPAN=4><I>Keine Einträge gefunden</I></TD></TR>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $farbe = '';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $farbe = '_blau';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $farbe = '_gruen';
                                    if ((mysql_result($SQL_Result, $n, 'me') + mysql_result($SQL_Result, $n, 'ke')) > 0) echo '<TR><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1">'.($n + 1).'.</font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</font></TD><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1"><A HREF="./main.php?modul=anzeigen&id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</A></font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result, $n, 'me') + mysql_result($SQL_Result, $n, 'ke')).'</font></TD></TR>';
                                }
                            }
                        ?>
          </TABLE>
        </CENTER>
      </TD>
    </TR>
    <TR>
      <TD><font size="-1"><BR>
        </font></TD>
    </TR>
    <TR>
      <TD WIDTH=50%>
        <CENTER>
          <TABLE>
            <TR>
              <TD BGCOLOR=#333333 COLSPAN=4><font color="#FFFFFF" size="-1"><B>Flotten
                (Top
                <?=$top_counter?>
                )</B></font>
            </TR>
            <?php
                            $SQL_Result = mysql_query('SELECT gn4accounts.id, s, gn4accounts.name, allianz, umod FROM gn4scans, gn4accounts WHERE rg=galaxie AND rp=planet AND type="0" ORDER BY s DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn);
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<TR><TD COLSPAN=4><I>Keine Einträge gefunden</I></TD></TR>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $farbe = '';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $farbe = '_blau';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $farbe = '_gruen';
                                    if (mysql_result($SQL_Result, $n, 's') > 0) echo '<TR><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1">'.($n + 1).'.</font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</font></TD><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1"><A HREF="./main.php?modul=anzeigen&id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</A></font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result, $n, 's')).'</font></TD></TR>';
                                }
                            }
                        ?>
          </TABLE>
        </CENTER>
      </TD>
      <TD WIDTH=50%>
        <CENTER>
          <TABLE>
            <TR>
              <TD BGCOLOR=#333333 COLSPAN=4><font color="#FFFFFF" size="-1"><B>Geschütze
                (Top
                <?=$top_counter?>
                )</B></font>
            </TR>
            <?
                            $SQL_Result = mysql_query('SELECT gn4accounts.id, d, gn4accounts.name, allianz, umod FROM gn4scans, gn4accounts WHERE rg=galaxie AND rp=planet AND type="0" ORDER BY d DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn);
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<TR><TD COLSPAN=4><I>Keine Einträge gefunden</I></TD></TR>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $farbe = '';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $farbe = '_blau';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $farbe = '_gruen';
                                    if (mysql_result($SQL_Result, $n, 'd') > 0) echo '<TR><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1">'.($n + 1).'.</font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].'><font size="-1">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</font></TD><TD BGCOLOR=#'.$htmlstyle['hell'.$farbe].'><font size="-1"><A HREF="./main.php?modul=anzeigen&id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</A></font></TD><TD BGCOLOR=#'.$htmlstyle['dunkel'.$farbe].' ALIGN="right"><font size="-1">'.ZahlZuText(mysql_result($SQL_Result, $n, 'd')).'</font></TD></TR>';
                                }
                            }
                        ?>
          </TABLE>
        </CENTER>
      </TD>
    </TR>
  </TABLE>
    <BR>
    <FONT SIZE="-1"><B>(<U>Blau</U> makierte Spieler sind im Urlaubs-Modus)</B></FONT>
</CENTER>
