<?php
    // change=gala planet pw alliid umode name rang spy
    if ( !isset( $_POST['uid'] )){
        echo 'Error - no UID set!';
        return;
    }

    $sql='';

    $SQL_Result = mysql_query('SELECT rang, allianz FROM `gn4accounts` WHERE id="'.$_POST['uid'].'";', $SQL_DBConn);
    $usrang=mysql_result($SQL_Result, 0, 'rang');
	if (($Benutzer['rang'] < RANG_STECHNIKER) && (
		($Benutzer['rang'] <= $usrang) ||
		( ($Benutzer['rang'] < RANG_TECHNIKER) && ($Benutzer['allianz'] != mysql_result($SQL_Result, 0, 'allianz')) ) ||
		( ($Benutzer['rang'] == RANG_TECHNIKER) && ($AllianzInfo[$Benutzer['allianz']]['meta'] != $AllianzInfo[mysql_result($SQL_Result, 0, 'allianz')]['meta']) )
	)) {
		echo "Keine Rechte um die Änderungen durchzuführen !<br>\n";
		LogAction( 'Wollte das Userprofile von '.$_POST['uid'].' ändern!' );
		return;
	}

    if ( $_POST['change'] == 'rang' ) {
        $sql = 'UPDATE `gn4accounts` SET rang="'.$_POST['rang'].'" WHERE id='.$_POST['uid'];
        LogAction( 'Rang für ID='.$_POST['uid'].' geändert auf '.$_POST['rang'].'.' );

    } elseif ( $_POST['change'] == 'koords' ) {
        if ( $_POST['selgala'] != 0 ){
            if ( isset( $_POST['planet']) && isset( $_POST['selgala'] ) ) {
                $sql = 'UPDATE `gn4accounts` SET galaxie="'.$_POST['selgala'].'", planet="'.$_POST['planet'].'" WHERE id='.$_POST['uid'];
                addgnuser($_POST['selgala'], $_POST['planet'], $_POST['selname']);
                LogAction( 'Koordinaten für ID='.$_POST['uid'].' geändert auf '.$_POST['selgala'].':'.$_POST['planet'].'.' );
            } elseif ( isset( $_POST['planet'])) {
                $sql = 'UPDATE `gn4accounts` SET planet="'.$_POST['planet'].'" WHERE id='.$_POST['uid'];
                addgnuser($_POST['selgala'], $_POST['planet'], $_POST['selname']);
                LogAction( 'Koordinaten für ID='.$_POST['uid'].' geändert auf '.$_POST['planet'].'.' );
            } else {
                $sql = 'UPDATE `gn4accounts` SET galaxie="'.$_POST['selgala'].'" WHERE id='.$_POST['uid'];
                addgnuser($_POST['selgala'], $selplanet, $_POST['selname']);
                LogAction( 'Koordinaten für ID='.$_POST['uid'].' geändert auf '.$_POST['selgala'].'.' );
            }
        }

    } elseif ( $_POST['change'] == 'pw' ) {
        $sql = 'UPDATE `gn4accounts` SET pwdandern="1", passwort="'.md5($_POST['pw']).'" WHERE id='.$_POST['uid'];
        LogAction( 'Passwort für ID='.$_POST['uid'].' neu vergeben.' );

    } elseif ( $_POST['change'] == 'allianz' ) {
        $sql = 'UPDATE `gn4accounts` SET allianz="'.$_POST['allianz'].'", ticid="'.$AllianzInfo[$_POST['allianz']]['meta'].'" WHERE id='.$_POST['uid'];
        LogAction( 'AllianzID für ID='.$_POST['uid'].' geändert auf '.$_POST['allianz'].'.' );

    } elseif ( $_POST['change'] == 'name' ) {
        $sql = 'UPDATE `gn4accounts` SET name="'.$_POST['name'].'" WHERE id='.$_POST['uid'];
        addgnuser($_POST['selgala'], $selplanet, $_POST['name']);
        LogAction( 'Name für ID='.$_POST['uid'].' geändert auf '.$_POST['name'].'.' );

    } elseif ( $_POST['change'] == 'umode' ) {
        if ( $_POST['umode'] == 'on' ) {
            if ( !isset( $_POST['umodedate'] ) || $_POST['umodedate'] == '' ) {
                $_POST['umodedate'] =  "tt.mm.jjjj";
            }
            $sql = 'UPDATE `gn4accounts` SET umod="'.date("d").'.'.date("m").'.'.date("Y").'-'.$_POST['umodedate'].'" WHERE id="'.$_POST['uid'].'"';
            LogAction( 'Umode für ID='.$_POST['uid'].' gesetzt.' );
        } else {
            $sql = 'UPDATE `gn4accounts` SET umod="" WHERE id='.$_POST['uid'];
            LogAction( 'Umode für ID='.$_POST['uid'].' gelöscht.' );
        }
    } elseif ( $_POST['change'] == 'spy' ) {
    	if ( $_POST['spy'] == 'gesperrt' ) {
    		$sql = 'UPDATE `gn4accounts` SET spy="1" WHERE id='.$_POST['uid'];
    		LogAction( 'Acc '.$_POST['uid'].' gesperrt.' );
    	} else {
    		$sql = 'UPDATE `gn4accounts` SET spy="0", versuche=0, ip="" WHERE id='.$_POST['uid'];
    		LogAction( 'Acc '.$_POST['uid'].' entsperrt.' );
    	}
    }
    if ( $sql != '' ){
        $SQL_result = mysql_query( $sql, $SQL_DBConn);
    }

    $action='';
?>
