<?php
    if (!isset($top_counter)) $top_counter = 10;
?>
<center>
    <h2>T.I.C. Rankings</h2>
    <a href="./main.php?modul=rankings&amp;top_counter=10">Top 10</a> | <a href="./main.php?modul=rankings&amp;top_counter=20">Top 20</a> | <a href="./main.php?modul=rankings&amp;top_counter=50">Top 50</a>

  <table width="100%">
    <tr>
      <td width="50%">
          <table align="center">
            <tr>
              <td class="datatablehead" colspan="4">Punkte(Top <?=$top_counter?>)</td>
            </tr>
            <?php
                            $SQL_Result = tic_mysql_query('SELECT gn4accounts.id, pts, gn4accounts.name, allianz, umod FROM gn4scans, gn4accounts WHERE rg=galaxie AND rp=planet AND type="0" ORDER BY pts DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<tr><td colspan="4"><i>Keine Einträge gefunden</i></td></tr>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $ftype = 'normal';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $ftype = 'umode';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $ftype = 'myself';
                                    if (mysql_result($SQL_Result, $n, 'pts') > 0) echo '<tr><td class="field'.$ftype.'light">'.($n + 1).'.</td><td class="field'.$ftype.'dark">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</td><td  class="field'.$ftype.'light"><a href="./main.php?modul=anzeigen&amp;id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</a></td><td class="field'.$ftype.'dark" align="right">'.ZahlZuText(mysql_result($SQL_Result, $n, 'pts')).'</td></tr>';
                                }
                            }
                        ?>
          </table>
      </td>
      <td width="50%">
          <table align="center">
            <tr>
              <td class="datatablehead" colspan="4">Extraktoren(Top <?=$top_counter?>)</td>
            </tr>
            <?php
                            $SQL_Result = tic_mysql_query('SELECT gn4accounts.id, gn4accounts.name, allianz, me, ke, umod FROM gn4scans, gn4accounts WHERE rg=galaxie and rp=planet AND type="0" ORDER BY (me + ke) DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<tr><td colspan="4"><i>Keine Einträge gefunden</i></td></tr>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $ftype = 'normal';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $ftype = 'umode';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $ftype = 'myself';
                                    if ((mysql_result($SQL_Result, $n, 'me') + mysql_result($SQL_Result, $n, 'ke')) > 0) echo '<tr><td class="field'.$ftype.'light">'.($n + 1).'.</td><td class="field'.$ftype.'dark">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</td><td class="field'.$ftype.'light"><a href="./main.php?modul=anzeigen&amp;id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</a></td><td class="field'.$ftype.'dark" align="right">'.ZahlZuText(mysql_result($SQL_Result, $n, 'me') + mysql_result($SQL_Result, $n, 'ke')).'</td></tr>';
                                }
                            }
                        ?>
          </table>
      </td>
    </tr>
    <tr>
      <td></td>
    </tr>
    <tr>
      <td width="50%">
          <table align="center">
            <tr>
              <td class="datatablehead" colspan="4">Flotten(Top <?=$top_counter?>)</td>
            </tr>
            <?php
                            $SQL_Result = tic_mysql_query('SELECT gn4accounts.id, s, gn4accounts.name, allianz, umod FROM gn4scans, gn4accounts WHERE rg=galaxie AND rp=planet AND type="0" ORDER BY s DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn);
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<tr><td colspan="4"><i>Keine Einträge gefunden</i></td></tr>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $ftype = 'normal';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $ftype = 'umode';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $ftype = 'myself';
                                    if (mysql_result($SQL_Result, $n, 's') > 0) echo '<tr><td class="field'.$ftype.'light">'.($n + 1).'.</td><td class="field'.$ftype.'dark">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</td><td class="field'.$ftype.'light"><a href="./main.php?modul=anzeigen&amp;id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</a></td><td class="field'.$ftype.'dark" align="right">'.ZahlZuText(mysql_result($SQL_Result, $n, 's')).'</td></tr>';
                                }
                            }
                        ?>
          </table>
      </td>
      <td width="50%">
          <table align="center">
            <tr>
              <td class="datatablehead" colspan="4">Geschütze(Top <?=$top_counter?>)</td>
            </tr>
            <?
                            $SQL_Result = tic_mysql_query('SELECT gn4accounts.id, d, gn4accounts.name, allianz, umod FROM gn4scans, gn4accounts WHERE rg=galaxie AND rp=planet AND type="0" ORDER BY d DESC, galaxie ASC, planet ASC LIMIT '.$top_counter.';', $SQL_DBConn);
                            $SQL_Num = mysql_num_rows($SQL_Result);
                            if ($SQL_Num == 0) {
                                echo '<tr><td colspan="4"><i>Keine Einträge gefunden</i></td></tr>';
                            } else {
                                for ($n = 0; $n < $SQL_Num; $n++) {
                                    $farbe = '';
                                    $ftype = 'normal';
                                    if (mysql_result($SQL_Result, $n, 'umod') != '') $ftype = 'umode';
                                    if (mysql_result($SQL_Result, $n, 'id') == $Benutzer['id']) $ftype = 'myself';
                                    if (mysql_result($SQL_Result, $n, 'd') > 0) echo '<tr><td class="field'.$ftype.'light">'.($n + 1).'.</td><td class="field'.$ftype.'dark">['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].']</td><td class="field'.$ftype.'light"><a href="./main.php?modul=anzeigen&amp;id='.mysql_result($SQL_Result, $n, 'id').'">'.mysql_result($SQL_Result, $n, 'name').'</a></td><td class="field'.$ftype.'dark" align="right">'.ZahlZuText(mysql_result($SQL_Result, $n, 'd')).'</td></tr>';
                                }
                            }
                        ?>
          </table>
      </td>
    </tr>
  </table>
    <b>(<u>Blau</u> makierte Spieler sind im Urlaubs-Modus)</b>
</center>