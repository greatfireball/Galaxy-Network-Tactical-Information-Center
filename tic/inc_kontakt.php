<center>
    <h2>Übersicht der Kontakte</h2>
    <table><tr><td>
    <table width="100%"><tr class="datatablehead" align="center"><td>Administratoren des Tactical Information Center</td></tr><tr class="fieldnormallight" style="font-weight:bold;" align="center"><td>
    <?
        $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE rang="'.$Rang_STechniker.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num > 0) {
            for ($n = 0; $n < $SQL_Num; $n++) echo mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' ['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].'] '.mysql_result($SQL_Result, $n, 'name').'<br />';
        }
    ?>
    </td></tr></table></td></tr><tr><td>
        <table width="100%"><tr class="datatablehead" align="center"><td>Techniker des Tactical Information Center</td></tr><tr class="fieldnormallight" style="font-weight:bold;" align="center"><td>
    <?
        $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE rang="'.$Rang_Techniker.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num > 0) {
            for ($n = 0; $n < $SQL_Num; $n++) echo mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' ['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].'] '.mysql_result($SQL_Result, $n, 'name').'<br />';
        }
        echo '</td></tr></table></td></tr>';
        foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
            echo '<tr><td><table width="100%">
                    <tr><td colspan="2" class="datatablehead" align="center">['.$AllianzTag[$AllianzNummer].'] '.$AllianzNummerName.'</td></tr>
                    <tr class="fieldnormaldark" style="font-weight:bold;" align="center"><td>Rang</td><td>Name</td></tr>';
            // admiral
            $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$AllianzNummer.'"  AND rang="'.$Rang_Admiral.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num = mysql_num_rows($SQL_Result);
            $color = 0;
            if ($SQL_Num > 0) {
                for ($n = 0; $n < $SQL_Num; $n++) {
                     $color = !$color;
                     echo '    <tr class="fieldnormal'.($color ? 'light' : 'dark').'><td>'.$RangName[$Rang_Admiral].'</td><td>'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' '.mysql_result($SQL_Result, $n, 'name').'</td></tr>';
                }
            }
            // Vize Admiral
            $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$AllianzNummer.'" AND rang="'.$Rang_VizeAdmiral.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num = mysql_num_rows($SQL_Result);
            if ($SQL_Num > 0) {
                for ($n = 0; $n < $SQL_Num; $n++) {
                    $color = !$color;
                    echo '    <tr class="fieldnormal'.($color ? 'light' : 'dark').'><td>'.$RangName[$Rang_VizeAdmiral].'</td><td>'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' '.mysql_result($SQL_Result, $n, 'name').'</td></tr>';
                }
            }
            // Kommodore
            $SQL_Result = tic_mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$AllianzNummer.'" AND rang="'.$Rang_GC.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num = mysql_num_rows($SQL_Result);
            if ($SQL_Num > 0) {
                for ($n = 0; $n < $SQL_Num; $n++) {
                    $color = !$color;
                    echo '    <tr class="fieldnormal'.($color ? 'light' : 'dark').'><td>'.$RangName[$Rang_GC].'</td><td>'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' '.mysql_result($SQL_Result, $n, 'name').'</td></tr>';
                }
            }
            echo '</table></td></tr>';
        }
    ?>
   </table>
</center>
