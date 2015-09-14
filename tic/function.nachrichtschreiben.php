<?PHP
// Nachricht schreiben
    if ($_POST['action'] == 'nachrichtschreiben') {
        if ($Benutzer['rang'] <= $Rang_GC)
            $error_code = 5;
        else {
            if (!isset($_POST['txtTitel'])) $_POST['txtTitel'] = '';
            if (!isset($_POST['txtText'])) $_POST['txtText'] = '';
            if ($_POST['txtTitel'] == '' || $_POST['txtText'] == '')
                $error_code = 6;
            else {
                $_POST['txtText'] = str_replace("\n", '<BR>', $_POST['txtText']);
                $SQL_Result = mysql_query('INSERT INTO `gn4nachrichten` (ticid, name, zeit, titel, text) VALUES ("'.$Benutzer['ticid'].'", "'.$Benutzer['galaxie'].':'.$Benutzer['planet'].' '.$Benutzer['name'].' ('.$RangName[$Benutzer['rang']].' @ ['.$AllianzTag[$Benutzer['allianz']].'])", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$_POST['txtTitel'].'", "'.$_POST['txtText'].'")', $SQL_DBConn) or $error_code = 7;
                if ($error_code == 0) LogAction("Nachricht geschrieben: Titel='".$_POST['txtTitel']."';");
            }
        }
    }
?>
