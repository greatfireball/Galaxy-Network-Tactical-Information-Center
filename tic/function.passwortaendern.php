<?PHP
// Passwort ändern
	if ($_POST['action'] == 'passwortaendern') {
		if (!isset($_POST['txtChPasswort'])) $_POST['txtChPasswort'] = '';
		if ($_POST['txtChPasswort'] != '') {
			$SQL_Result = mysql_query('SELECT passwort FROM `gn4accounts` WHERE id="'.$_SESSION['userid'].'" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn) or $error_code = 4;
			if ( md5($_POST['txtChPasswort']) != mysql_result($SQL_Result, 0, 'passwort') ) {
				$SQL_Result = mysql_query('UPDATE `gn4accounts` SET passwort="'.md5($_POST['txtChPasswort']).'", pwdandern="0" WHERE id="'.$Benutzer['id'].'" AND ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn) or $error_code = 7;
				$Benutzer['pwdandern'] = 0;
			}
		}
	}
?>
