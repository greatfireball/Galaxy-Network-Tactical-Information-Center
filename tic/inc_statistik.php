<CENTER>

  <TABLE>
    <TR>
      <td COLSPAN=2 BGCOLOR=#333333><font size="-1"><font color="#FFFFFF" size="-1"><B>T.I.C.
        Statistik</B></font></td></font>
    </TR>
    <?php
                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Anzahl Flottenbewegungen:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';

                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus=1 and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Anzahl Verteidingungsfl&uuml;ge:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';
                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus=2 and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Anzahl Angriffsfl&uuml;ge:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';
                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4flottenbewegungen` where modus>2 and ticid="'.$Benutzer['ticid'].'" or modus=0 and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Anzahl R&uuml;ckfl&uuml;ge:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';

                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4accounts`', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Alle T.I.C. Accounts:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].' COLSPAN=2><font size="-1"><B>Accounts pro Allianz:</B></td></font></TR>';
                $SQL_Result = mysql_query('SELECT id, tag, name FROM `gn4allianzen` where ticid="'.$Benutzer['ticid'].'" ORDER BY id', $SQL_DBConn);
                for ($n = 0; $n < mysql_num_rows($SQL_Result); $n++) {
                    $SQL_Result2 = mysql_query('SELECT COUNT(*) FROM `gn4accounts` WHERE allianz="'.mysql_result($SQL_Result, $n, 'id').'" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                    $SQL_Row = mysql_fetch_row($SQL_Result2);
                    echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">['.mysql_result($SQL_Result, $n, 'tag').'] '.mysql_result($SQL_Result, $n, 'name').'</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.$SQL_Row[0].'</td></font></TR>';
                }
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].' COLSPAN=2><font size="-1"><B>Forenstatistik:</B></td></font></TR>';
                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE belongsto="0" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Themen:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';
                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4forum` WHERE NOT belongsto="0" and ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Antworten:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].' COLSPAN=2><font size="-1"><B>Scan Datenbank:</B></td></font></TR>';
                $SQL_Result = mysql_query('SELECT COUNT(*) FROM `gn4scans` where ticid="'.$Benutzer['ticid'].'"', $SQL_DBConn);
                $SQL_Row = mysql_fetch_row($SQL_Result);
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Anzahl Scans:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].' ALIGN="right"><font size="-1">'.ZahlZuText($SQL_Row[0]).'</td></font></TR>';
                echo '<TR><td BGCOLOR=#'.$htmlstyle['dunkel'].'><font size="-1">Letzte Scansäuberung:</td></font><td BGCOLOR=#'.$htmlstyle['hell'].'><font size="-1">'.$lastscanclean.'</td></font></TR>';
            ?>
  </TABLE>
</CENTER>
