<?
function injsafe($data) {
	return addcslashes($data, "\000\x00\n\r\\'\"\x1a");
}

function connect() {
    include('./accdata.php' );
    $SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']);
    mysql_select_db($db_info['dbname'], $SQL_DBConn);

    $SQL_Query = "SET CHARACTER SET latin1";
    @mysql_query($SQL_Query, $SQL_DBConn);
    return $SQL_DBConn;
}

function check_user($name, $pass) {
	$SQL_Query = "SELECT ip, versuche FROM `gn4accounts` WHERE name='".injsafe($name)."' LIMIT 1;";
	$SQL_Result_iplock = mysql_query($SQL_Query) or die(mysql_errno()." - ".mysql_error());
	$iplock = mysql_fetch_assoc($SQL_Result_iplock);
	if (!$iplock)
		return false;
	
	if($iplock['ip'] == $_SERVER['REMOTE_ADDR'] && $iplock['versuche'] >= 3)
		die ('Dieser Account ist gesperrt, wenden sie sich an Ihren Adminstrator');
	mysql_free_result($SQL_Result_iplock);
	
	$SQL_Query = "SELECT id FROM gn4accounts WHERE name='".injsafe($name)."' AND passwort=MD5('".injsafe($pass)."') LIMIT 1;";
	$SQL_Result_login = mysql_query($SQL_Query) or die(mysql_errno()." - ".mysql_error());
	if ($user = mysql_fetch_assoc($SQL_Result_login)) {
		mysql_free_result($SQL_Result_login);
		$SQL_Query = "UPDATE gn4accounts SET versuche=0, ip='' WHERE name='".injsafe($name)."';";
		mysql_query($SQL_Query) or die(mysql_errno()." - ".mysql_error());
		return $user['id'];
	}
	
	$SQL_Query = "UPDATE gn4accounts SET versuche=versuche + 1, ip='".$_SERVER['REMOTE_ADDR']."' WHERE name='".injsafe($name)."';";
	mysql_query($SQL_Query) or die(mysql_errno()." - ".mysql_error());
	return false;
}

/* werden derzeit nicht gebraucht
function login($userid)
{
    $sql="UPDATE gn4accounts
    SET session='".session_id()."'
    WHERE id=".$userid." and ticid=".$ticid;
     mysql_query($sql);
}

function logged_in()
{
    $sql="SELECT id
    FROM gn4accounts
    WHERE session='".session_id()."'
    LIMIT 1";
    $result= mysql_query($sql);
      return ( mysql_num_rows($result)==1);
}

function logout()
{
    $sql="UPDATE gn4accounts
    SET session=NULL
    WHERE session='".session_id()."'";
     mysql_query($sql);
}*/

$SQL_DBConn = connect();
?>