<CENTER>
    <font size="4">Übersicht der Kontakte</font><br><br>
    Administratoren des Tactical Information Center:<BR>
    <?
        $SQL_Result = mysql_query('SELECT * FROM `gn4accounts` WHERE rang="'.$Rang_STechniker.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num > 0) {
            for ($n = 0; $n < $SQL_Num; $n++) echo '<B>'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' ['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].'] '.mysql_result($SQL_Result, $n, 'name').'</B><BR>';
        }
        echo '<BR><BR>';
    ?>
    Techniker des Tactical Information Center:<BR>
    <?
        $SQL_Result = mysql_query('SELECT * FROM `gn4accounts` WHERE rang="'.$Rang_Techniker.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num > 0) {
            for ($n = 0; $n < $SQL_Num; $n++) echo '<B>'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' ['.$AllianzTag[mysql_result($SQL_Result, $n, 'allianz')].'] '.mysql_result($SQL_Result, $n, 'name').'</B><BR>';
        }
        echo '<BR><BR>';
        foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
            echo '<B>['.$AllianzTag[$AllianzNummer].'] '.$AllianzNummerName.'</B><BR>';
            echo '<BR>';
            echo '<TABLE>';
            echo '  <TR><td BGCOLOR=#333333><font size="-1"><FONT COLOR=#FFFFFF><B>Rang</B></FONT></td></font><td BGCOLOR=#333333><font size="-1"><FONT COLOR=#FFFFFF><B>Name</B></FONT></td></font></TR>';
            // Admiral
            $SQL_Result = mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$AllianzNummer.'"  AND rang="'.$Rang_Admiral.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num = mysql_num_rows($SQL_Result);
            if ($SQL_Num > 0) {
                for ($n = 0; $n < $SQL_Num; $n++) echo '    <TR><td><font size="-1"><P CLASS="dunkel">'.$RangName[$Rang_Admiral].'</P></td></font><td><font size="-1"><P CLASS="hell">'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' '.mysql_result($SQL_Result, $n, 'name').'</P></td></font></TR>';
            }
            // Vize Admiral
            $SQL_Result = mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$AllianzNummer.'" AND rang="'.$Rang_VizeAdmiral.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num = mysql_num_rows($SQL_Result);
            if ($SQL_Num > 0) {
                for ($n = 0; $n < $SQL_Num; $n++) echo '    <TR><td><font size="-1"><P CLASS="dunkel">'.$RangName[$Rang_VizeAdmiral].'</P></td></font><td><font size="-1"><P CLASS="hell">'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' '.mysql_result($SQL_Result, $n, 'name').'</P></td></font></TR>';
            }
            // Kommodore
            $SQL_Result = mysql_query('SELECT * FROM `gn4accounts` WHERE allianz="'.$AllianzNummer.'" AND rang="'.$Rang_GC.'" ORDER BY galaxie, planet;', $SQL_DBConn) or $error_code = 4;
            $SQL_Num = mysql_num_rows($SQL_Result);
            if ($SQL_Num > 0) {
                for ($n = 0; $n < $SQL_Num; $n++) echo '    <TR><td><font size="-1"><P CLASS="dunkel">'.$RangName[$Rang_GC].'</P></td></font><td><font size="-1"><P CLASS="hell">'.mysql_result($SQL_Result, $n, 'galaxie').':'.mysql_result($SQL_Result, $n, 'planet').' '.mysql_result($SQL_Result, $n, 'name').'</P></td></font></TR>';
            }
            echo '</TABLE>';
            echo '<BR>';
            echo '<BR>';
        }
    ?>
</CENTER>
