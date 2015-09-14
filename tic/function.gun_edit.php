<?php


    if ( $galakoord == "" || $planetkoord == "" ) {
        echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht</b></p>';
        return;
    }

    $trg = $_POST['galakoord'];
    $trp= $_POST['planetkoord'];
    $ttype = 3;
    $tgen = 99;

    $tgr=0;


    $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$trg.'" AND rp="'.$trp.'" AND type="'.$ttype.'";', $SQL_DBConn);
    $insert_names = 'glo, glr, gmr, gsr, ga, gr';
    $insert_values = '"'.$_POST['tglo'].'", "'.$_POST['tglr'].'", "'.$_POST['tgmr'].'", "'.$_POST['tgsr'].'", "'.$_POST['tga'].'", "'.$_POST['tgr'].'"';
    $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$ttype.'","'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$trg.'", "'.$trp.'", "'.$tgen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
    addgnuser($trg, $trp, $_POST['trn']);
    // ???
    $txtScanGalaxie = $_POST['galakoord'];
    $txtScanPlanet = $_POST['planetkoord'];

?>


