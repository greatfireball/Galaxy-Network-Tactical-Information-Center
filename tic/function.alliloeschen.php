<?PHP
// Alli + Account l�schen
if ($Benutzer['rang'] == 5){
        if (!isset($_GET['allid'])) $_GET['allid'] = '';
        if ($_GET['allid'] != '') {
		$SQL_Result = mysql_query('DELETE FROM `gn4allianzen`WHERE id='.$_GET['allid'], $SQL_DBConn) or $error_code = 7;
		$SQL_Result = mysql_query('DELETE FROM `gn4accounts` WHERE allianz='.$_GET['allid'], $SQL_DBConn) or $error_code = 7;
                        if ($error_code == 0) LogAction("Account gel�scht: Alli ID=".$id.";");
                    else
                        $error_code = 5;
                }
  }
?>
