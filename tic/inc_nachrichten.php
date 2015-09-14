<CENTER>

    <font size="4">Nachrichten</font><br>
    <font size="-1">(neues aus den Allies und dem T.I.C.)</font><br>&nbsp;
    <?PHP
        $SQL_Result = mysql_query('SELECT * FROM `gn4nachrichten` where ticid="'.$Benutzer['ticid'].'" ORDER BY id DESC;', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num == 0)
            echo '<B>Es sind keine Nachrichten vorhanden.</B>';
        else {
            echo '<TABLE WIDTH=80%>';
            for ($n = 0; $n < $SQL_Num; $n++) {
                echo '  <TR>';
                echo '      <td WIDTH=50% BGCOLOR=#333333><font size="-1"><FONT COLOR=#FFFFFF><B>'.mysql_result($SQL_Result, $n, 'titel').'</B></FONT></td></font><td WIDTH=15% BGCOLOR=#333333><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B>'.mysql_result($SQL_Result, $n, 'zeit').'</B></FONT></td></font><td WIDTH=35% BGCOLOR=#333333><font size="-1"><FONT COLOR=#FFFFFF SIZE="-1"><B>'.mysql_result($SQL_Result, $n, 'name').'</B></FONT></td></font>';
                echo '  </TR>';
                echo '  <TR>';
                echo '  <td COLSPAN=3 WIDTH=100%><font size="-1"><P CLASS="hell">'.mysql_result($SQL_Result, $n, 'text').'</P></td></font>';
                echo '  </TR>';
                if ($Benutzer['rang'] > $Rang_GC) {
                    echo '<P CLASS="dunkel"><TR><td COLSPAN=3 WIDTH=100%><font size="-1">';
                    echo '<FORM ACTION="./main.php" METHOD="POST">';
                            echo '<INPUT TYPE="hidden" NAME="modul" VALUE="nachrichten">';
                            echo '<INPUT TYPE="hidden" NAME="id" VALUE="'.mysql_result($SQL_Result, $n, 'id').'">';
                            echo '<INPUT TYPE="hidden" NAME="action" VALUE="nachrichtloeschen">';
                            echo '<INPUT TYPE="submit" VALUE="Löschen"> </form>';
                    echo '</p></font></td></form></TR>';
                }
                echo '  <TR>';
                echo '      <td COLSPAN=3 WIDTH=100%><font size="-1"><BR></td></font>';
                echo '  </TR>';
            }
            echo '</TABLE>';
        }
    ?>
</CENTER>
