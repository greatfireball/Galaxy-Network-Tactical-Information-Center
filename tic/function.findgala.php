<?php

    if ( strcmp( $_POST['direction'], "previous" ) == 0 ){
        $sql='select rg from `gn4scans` where rg < '.$_POST['xgala'].' and ticid="'.$Benutzer['ticid'].'" order by rg desc limit 1';
        $SQL_Result = mysql_query( $sql, $SQL_DBConn );
        if ( mysql_num_rows($SQL_Result) <> 0 ){
            $xgala = mysql_result($SQL_Result, 0, 'rg' );
        }
    } else if ( strcmp( $_POST['direction'], "next" ) == 0 ){
        $sql='select rg from `gn4scans` where rg > '.$_POST['xgala'].' and ticid="'.$Benutzer['ticid'].'" order by rg limit 1';
        $SQL_Result = mysql_query( $sql, $SQL_DBConn );
        if ( mysql_num_rows($SQL_Result) <> 0 ){
            $xgala = mysql_result($SQL_Result, 0, 'rg' );
        }
    }

?>
