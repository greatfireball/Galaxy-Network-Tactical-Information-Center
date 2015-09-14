<?PHP
// Flottenbewegung ändern
    if ($_POST['action'] == 'flotteaendern') {
        if (!isset($_POST['flottenid'])) $_POST['flottenid'] = '';
        if (!isset($_POST['id'])) $_POST['$id'] = '';
        if (!isset($_POST['lst_Flotte'])) $_POST['lst_Flotte'] = 0;
        $tmp_modus = 0;
        if ($_POST['flottenid'] != '' && $_POST['id'] != '') {
            $SQL_Result = mysql_query('SELECT angreifer_galaxie, verteidiger_galaxie, modus FROM `gn4flottenbewegungen` WHERE id="'.$_POST['flottenid'].'";', $SQL_DBConn) or $error_code = 7;
            if (mysql_num_rows($SQL_Result) == 1) {
                $tmp_galaxie_angreifer = mysql_result($SQL_Result, 0, 'angreifer_galaxie');
                $tmp_galaxie_verteidiger = mysql_result($SQL_Result, 0, 'verteidiger_galaxie');
                $tmp_modus = mysql_result($SQL_Result, 0, 'modus');
            }
            if ($_POST['optModus'] == 0 or $_POST['optModus'] == 3 or $_POST['optModus'] == 4 ) {
                switch( $tmp_modus ) {
                    case 1:
                    case 2:
                        echo "<br>mode changed from ".$tmp_modus;
                        $tmp_modus += 2;
                        echo "to mode ".$tmp_modus."<br>";
                        break;
                    case 3:
                    case 4:
                        // do nothing
                        break;
                    case 0:
                    default:    // besser als nix ...
                        $tmp_modus = 3;
                        break;
                }

                $SQL_Result = mysql_query('UPDATE `gn4flottenbewegungen` SET modus="'.$tmp_modus.'", eta="'.$_POST['lst_ETA0'].'", flottennr="'.$_POST['lst_Flotte'].'" WHERE id="'.$_POST['flottenid'].'";', $SQL_DBConn) or $error_code = 7;
            } else {
                $SQL_Result = mysql_query('UPDATE `gn4flottenbewegungen` SET modus="'.$_POST['optModus'].'", eta="'.$_POST['lst_ETA'].'", flugzeit="'.$_POST['lst_Flugzeit'].'", flottennr="'.$_POST['lst_Flotte'].'" WHERE id="'.$$_POST['flottenid'].'";', $SQL_DBConn) or $error_code = 7;
            }
        } else $error_code = 6;
    }
?>
