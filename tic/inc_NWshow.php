<!-- START: inc_nachtwache -->
<?php

    if(!isset($NW_intervall))
        $NW_intervall = 120;
    if(!isset($NW_start))
        $NW_start     = 20*60;
    if(!isset($NW_stop))
        $NW_stop      = 12*60;
    if($NW_stop < $NW_start)
        $NW_stop += 24*60;

    if (!isset($_POST['gala']) && $Benutzer['rang'] >= RANG_VIZEADMIRAL)
        $selected_gala = $Benutzer['galaxie'];
    else
        $selected_gala = $_POST['gala'];

    if($Benutzer['rang'] >= RANG_VIZEADMIRAL)
    {
        echo "<form action=\"?modul=NWshow\" method=\"post\" name=\"NWshow\">";

        $SQL_Result = tic_mysql_query('SELECT galaxie as num FROM gn4accounts WHERE allianz = "'.$Benutzer['allianz'].'" AND ticid="'.$Benutzer['ticid'].'" GROUP BY galaxie') or die(tic_mysql_error(__FILE__,__LINE__));
        echo "<div style=\"font-size:10pt\">Galaxie: <select name=\"gala\">";
        while($gala = mysql_fetch_assoc($SQL_Result))
            echo "<option value=\"".$gala['num']."\"".($selected_gala == $gala['num'] ? " selected=\"selected\"" : "").">".$gala['num']."</option>";

        echo '</select> <input type="submit" value="Anzeigen" /></div></form>';
    }

    $SQL_Result = tic_mysql_query("SELECT name, planet FROM `gn4accounts` WHERE allianz = '".$Benutzer['allianz']."' AND ticid = '".$Benutzer['ticid']."' AND galaxie = '".$selected_gala."'") or die(tic_mysql_error(__FILE__,__LINE__));
    $gala_member = array("-Keiner-");
    while($member = mysql_fetch_assoc($SQL_Result))
    {
        $gala_member[$member['planet']] = $member['name'];
    }

    $style_class[0] = "fieldnormallight";
    $style_class[1] = "fieldnormaldark";

    $NW_times = array();

    $NW_intervall *= 60;
    $NW_start *= 60;
    $NW_stop *= 60;

    for($i = $NW_start;$i <= $NW_stop;$i += $NW_intervall)
        $NW_times[] = date("H:i", $i-3600);
    if($i < $NW_stop+$NW_intervall)
        $NW_times[] = date("H:i", $NW_stop-3600);

    echo "<table><tr><td colspan=\"2\" align=\"center\"><form action=\"?modul=NWshow\" method=\"post\">
          <input type=\"hidden\" name=\"action\" value=\"NWshow\" />
          <input type=\"hidden\" name=\"gala\" value=\"".$selected_gala."\" />
          <table cellspacing=\"1\" cellpadding=\"3\" class=\"datatable\" style=\"text-align:center;background:#000000;\">
            <tr class=\"datatablehead\"><th colspan=\"8\">Wachplan der heutigen Woche für Galaxie ".$selected_gala."</th></tr>
            <tr class=\"datatablehead\">
              <th></th>
              <th style=\"width:100px;\">Mo</th>
              <th style=\"width:100px;\">Di</th>
              <th style=\"width:100px;\">Mi</th>
              <th style=\"width:100px;\">Do</th>
              <th style=\"width:100px;\">Fr</th>
              <th style=\"width:100px;\">Sa</th>
              <th style=\"width:100px;\">So</th>
            </tr>";

    $today = date("w");
    if($today == 0)
        $today = 7;
    $weekstart = mktime(0, 0, 0, date("m"), date("d"), date("Y"))-(($today-1)*86400)+$NW_start;  
    $prevweekstart = $weekstart - 604800;
    $nextweekstart = $weekstart + 604800;

    $SQL_Result = tic_mysql_query("SELECT time, planet1, done1, planet2, done2, planet3, done3, planet4, done4, planet5, done5, planet6, done7, planet7, done7 FROM gn4nachtwache WHERE ticid = '".$Benutzer['ticid']."' AND gala = '".injsafe($selected_gala)."'") or die(tic_mysql_error(__FILE__,__LINE__));

    while($row = mysql_fetch_assoc($SQL_Result))
    {
        $data[$row['time']] = $row;
    }

    $time = $weekstart;
    for($x = 0; $x < count($NW_times)-1;$x++)
    {
        echo "<tr><td class=\"".$style_class[1]."\">".$NW_times[$x]."-".$NW_times[$x+1]."</td>";
        for($y = 0;$y < 7;$y++)
        {
            echo "<td class=\"".$style_class[$y%2]."\">";

            if(isset($data[$time]))
                $planet_id = $data[$time]['planet'.($y+1)];
            else
                $planet_id = 0;

            if($Benutzer['rang'] >= RANG_VIZEADMIRAL)
            {    
                if($time+24*3600*$y < time())
                {
                    if($planet_id != 0)
                    {
                        echo "<span style=\"color:".($data[$time]['done'.($y+1)] ? "#009000" : "#900000").";\">".$gala_member[$planet_id]."</span>";
                    }
                    else
                    {
                        echo $gala_member[$planet_id];
                    }
                }
                else
                {
                    echo "<select name=\"nachtwache[".$time."][".$y."]\">";
                    foreach($gala_member as $planet => $name)
                    {
                        echo "<option value=\"".$planet."\"".($planet == $planet_id ? " selected=\"selected\"" : "").">".$name."</option>";
                    }
                    echo "</select>";
                }
            }
            else
            {
                if($planet_id != 0)
                {
                    if($time+24*3600*$y < time())
                        echo "<span style=\"color:".($data[$time]['done'.($y+1)] ? "#009000" : "#900000").";\">".$gala_member[$planet_id]."</span>";
                    else
                        echo $gala_member[$planet_id];
                }
                else
                {
                    echo $gala_member[$planet_id];
                }
            }
            echo "</td>";
        }
        $time += $NW_intervall;
        echo "</tr>";
    }

    echo "  <tr class=\"datatablefoot\"><td colspan=\"8\"><input type=\"submit\" value=\"Speichern\" /></td></tr>
          </table>
          </form></td></tr>
          <tr><td>
            <table cellspacing=\"1\" cellpadding=\"3\" class=\"datatable\" style=\"text-align:center;background:#000000;\">
              <tr class=\"datatablehead\"><th colspan=\"8\">Wachplan der letzten Woche für Galaxie ".$selected_gala."</th></tr>
              <tr class=\"datatablehead\">
                <th></th>
                <th style=\"width:100px;\">Mo</th>
                <th style=\"width:100px;\">Di</th>
                <th style=\"width:100px;\">Mi</th>
                <th style=\"width:100px;\">Do</th>
                <th style=\"width:100px;\">Fr</th>
                <th style=\"width:100px;\">Sa</th>
                <th style=\"width:100px;\">So</th>
              </tr>";
    $time = $prevweekstart;
    for($x = 0; $x < count($NW_times)-1;$x++)
    {
        echo "<tr><td class=\"".$style_class[1]."\">".$NW_times[$x]."-".$NW_times[$x+1]."</td>";
        for($y = 0;$y < 7;$y++)
        {
            echo "<td class=\"".$style_class[$y%2]."\">";

            if(isset($data[$time]))
                $planet_id = $data[$time]['planet'.$y];
            else
                $planet_id = 0;

            if($planet_id != 0)
            {
                echo "<span style=\"color:".($data[$time]['done'.$y] ? "#009000" : "#900000").";\">".$gala_member[$planet_id]."</span>";
            }
            else
            {
                echo $gala_member[$planet_id];
            }
            echo "</td>";
        }
        $time += $NW_intervall;
        echo "</tr>";
    }

    echo "  </table>
          </td></tr><tr><td><form action=\"?modul=NWshow#next\" method=\"post\">
          <input type=\"hidden\" name=\"action\" value=\"NWshow\" />
          <input type=\"hidden\" name=\"gala\" value=\"".$selected_gala."\" /><a name=\"next\">
          <table cellspacing=\"1\" cellpadding=\"3\" class=\"datatable\" style=\"text-align:center;background:#000000;\">
            <tr class=\"datatablehead\"><th colspan=\"8\">Wachplan der nächsten Woche für Galaxie ".$selected_gala."</th></tr>
            <tr class=\"datatablehead\">
              <th></th>
              <th style=\"width:100px;\">Mo</th>
              <th style=\"width:100px;\">Di</th>
              <th style=\"width:100px;\">Mi</th>
              <th style=\"width:100px;\">Do</th>
              <th style=\"width:100px;\">Fr</th>
              <th style=\"width:100px;\">Sa</th>
              <th style=\"width:100px;\">So</th>
            </tr>";
            
    $time = $nextweekstart;
    for($x = 0; $x < count($NW_times)-1;$x++)
    {
        echo "<tr><td class=\"".$style_class[1]."\">".$NW_times[$x]."-".$NW_times[$x+1]."</td>";
        for($y = 1;$y <= 7;$y++)
        {
            echo "<td class=\"".$style_class[$y%2]."\">";

            if(isset($data[$time]))
                $planet_id = $data[$time]['planet'.$y];
            else
                $planet_id = 0;

            if($Benutzer['rang'] >= RANG_VIZEADMIRAL)
            {
                echo "<select name=\"nextnachtwache[".($time)."][".$y."]\">";
                foreach($gala_member as $planet => $name)
                {
                    echo "<option value=\"".$planet."\"".($planet == $planet_id ? " selected=\"selected\"" : "").">".$name."</option>";
                }
                echo "</select>";
            }
            else
            {
                echo $gala_member[$planet_id];
            }
            echo "</td>";
        }
        $time += $NW_intervall;
        echo "</tr>";
    }

    echo "  <tr class=\"datatablefoot\"><td colspan=\"8\"><input type=\"submit\" value=\"Speichern\" /></td></tr>
          </table></a>
          </form></tr></td></table>";

?>
<!-- ENDE: inc_NWshow -->
