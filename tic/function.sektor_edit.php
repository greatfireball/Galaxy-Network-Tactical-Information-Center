<?php


    if ( $_POST['galakoord'] == "" || $_POST['planetkoord'] == "" ) {
        echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht</b></p>';
        return;
    }

    $trg = $_POST['galakoord'];
    $trp= $_POST['planetkoord'];
    $ttype = 0;
    $tgen = 99;

    $tgr=0;


    $SQL_Result = tic_mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'pts, s, d, me, ke, a';
    $insert_values = '"'.$_POST['tpts'].'", "'.$_POST['ts'].'", "'.$_POST['td'].'", "'.$_POST['tme'].'", "'.$_POST['tke'].'", "'.$_POST['ta'].'"';
    $SQL_Result = tic_mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'","'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
    addgnuser($trg, $trp, $_POST['trn']);
    // ???
    $txtScanGalaxie = $_POST['galakoord'];
    $txtScanPlanet = $_POST['planetkoord'];

?>


