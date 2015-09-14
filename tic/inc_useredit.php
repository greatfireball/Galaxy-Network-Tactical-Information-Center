<center>
<?php

    if ( !isset( $_GET['uid'] )){
        echo '<font color="#800000" size="-1"><b>internal error - no uid set</b></font>';
        return;
    }
    if ( !isset( $Benutzer['rang'])) $Benutzer['rang'] = '0';
    if ( $Benutzer['rang']<'1') die('Rang zu Niedrig');

    $sql = "select * from gn4accounts where id='".$_GET['uid']."';";
    $SQL_result = mysql_query( $sql, $SQL_DBConn);
    $SQL_Num = mysql_num_rows( $SQL_result );
    if ( $SQL_Num == 0 ) {
        echo '<font color="#800000" size="-1"><b>internal error - db access failed</b></font>';
        return;
    }
    $selgala = mysql_result( $SQL_result, 0, 'galaxie');
    $planet  = mysql_result( $SQL_result, 0, 'planet');
    $rang    = mysql_result( $SQL_result, 0, 'rang');
    $name    = mysql_result( $SQL_result, 0, 'name');
    $alli    = mysql_result( $SQL_result, 0, 'allianz');
    $umode   = mysql_result( $SQL_result, 0, 'umod');
    $lastlog = mysql_result( $SQL_result, 0, 'lastlogin');
    $spy     = mysql_result( $SQL_result, 0, 'spy');
    $SQL_Result1 = mysql_query("select tag from gn4allianzen where id='".$alli."';",$SQL_DBConn);
    $allitag = mysql_result( $SQL_Result1, 0, 'tag');
    if( $Benutzer['rang'] == '1') {
    if($selgala != $Benutzer['galaxie']) die('Du hast nur recht in deiner Gala');
    }
    // parm-vars: change gala planet pw alliid umode name rang spy
    if ( !isset( $_GET['change'] )){
        echo '<font color="#800000" size="-1"><b>no access - invalid function</b></font>';
        return;
    }

    echo '<table bgcolor="#333333"><tr><td>';

    echo '<form action="./main.php" method="post">';
    echo '<input type="hidden" name="modul" value="userman">';
    echo '<input type="hidden" name="action" value="userman">';
    echo '<input type="hidden" name="change" value="'.$_GET['change'].'">';
    echo '<input type="hidden" name="selgala" value="'.$selgala.'">';
    echo '<input type="hidden" name="selplanet" value="'.$planet.'">';
    echo '<input type="hidden" name="uid" value="'.$_GET['uid'].'">';

    echo '<font size="-1">';
    echo '<table cellspacing="3" width="100%">';
    echo '<tr><td bgcolor="#999999" colspan="2">';
    echo '<font size="-1"><b>Benutzerdaten</b></font>';

    // galaxie
    echo '<tr>';
    echo '<td bgcolor="#aaaaaa">';
    echo '<font size="-1">';
    echo 'Galaxie:';
    echo '</font>';
    echo '</td>';
    echo '<td bgcolor="#aaaaaa">';
    if ( $_GET['change']=='koords' ) {
        echo '<input type="text" name="selgala" maxlength=4 value="'.$selgala.'">';
        echo '<input type="hidden" name="selname" value="'.$name.'">';
    } else {
        echo '<font size="-1">';
        echo $selgala;
        echo '</font>';
    }
    echo '</td>';
    echo '</tr>';

    // planet
    echo '<tr>';
    echo '<td bgcolor="#aaaaaa">';
    echo '<font size="-1">';
    echo 'Planet:';
    echo '</font>';
    echo '</td>';
    echo '<td bgcolor="#aaaaaa">';
    if ( $_GET['change']=='koords' ) {
        echo '<input type="text" name="planet" maxlength=2 value="'.$planet.'">';
    } else {
        echo '<font size="-1">';
        echo $planet;
        echo '</font>';
    }
    echo '</td>';
    echo '</tr>';

    // name
    echo '<tr>';
    echo '<td bgcolor="#aaaaaa">';
    echo '<font size="-1">';
    echo 'Name:';
    echo '</font>';
    echo '</td>';
    echo '<td bgcolor="#aaaaaa">';
    if ( $_GET['change']=='name' ) {
        echo '<input type="text" name="name" maxlength=50 value="'.$name.'">';
    } else {
        echo '<font size="-1">';
        echo $name;
        echo '</font>';
    }
    echo '</td>';
    echo '</tr>';

    // rang
    echo '<tr>';
    echo '<td bgcolor="#aaaaaa">';
    echo '<font size="-1">';
    echo 'Rang:';
    echo '</font>';
    echo '</td>';
    echo '<td bgcolor="#aaaaaa">';
    if ( $_GET['change']=='rang' ) {
        if ($Benutzer['rang'] >= $Rang_GC && $rang <= $Benutzer['rang'] ) {
            echo '<SELECT NAME="rang" SIZE="1">';
            for ( $n=0; $n<count( $RangName ); $n++ ) {
                $zusatz = '';
                if ($n == $rang )
                    $zusatz = ' SELECTED';

                // man kann leute nur max. auf das eigene level "befördern"
                if ( $Benutzer['rang'] >= $n )
                    echo '<OPTION VALUE="'.$n.'"'.$zusatz.'>'.$RangName[$n].'</OPTION>';
                else
                    break;
            }
            echo '</SELECT>';
        }
    } else {
        echo '<font size="-1">';
        echo $RangName[$rang];
        echo '</font>';
    }
    echo '</td>';
    echo '</tr>';

    // pw
    echo '<tr>';
    echo '<td bgcolor="#aaaaaa">';
    echo '<font size="-1">';
    echo 'Passwort:';
    echo '</font>';
    echo '</td>';
    echo '<td bgcolor="#aaaaaa">';
    if ( $_GET['change']=='pw' ) {
        echo '<input type="text" name="pw" maxlength="20">';
    } else {
        echo '<font size="-1">';
        echo 'xxxxxxxxx';
        echo '</font>';
    }
    echo '</td>';
    echo '</tr>';

    // umode
    echo '<tr>';
    echo '<td bgcolor="#aaaaaa">';
    echo '<font size="-1">';
    echo 'UMode:';
    echo '</font>';
    echo '</td>';
    echo '<td bgcolor="#aaaaaa">';
    if ( $_GET['change']=='umode' ) {
        $add = '';
        if ( $umode!='' )
            $add=' checked';
        echo '<input type="checkbox" name="umode"'.$add.'>';
        echo '<input type="text" value="tt.mm.jjjj" name="umodedate" maxlength="10">';
    } else {
        echo '<font size="-1">';
        echo $umode;
        echo '</font>';
    }
    echo '</td>';
    echo '</tr>';

    // allianz
    echo '<tr>';
    echo '<td bgcolor="#aaaaaa">';
    echo '<font size="-1">';
    echo 'Allianz:';
    echo '</font>';
    echo '</td>';
    echo '<td bgcolor="#aaaaaa">';
    if ( $_GET['change']=='allianz' ) {

        echo '<SELECT NAME="allianz" SIZE="1">';
        foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
            if ( $AllianzNummer == $alli )
                $zusatz = ' SELECTED';
            echo '<OPTION VALUE="'.$AllianzNummer.'"'.$zusatz.'>'.$AllianzTag[$AllianzNummer].' '.$AllianzNummerName.'</OPTION>';
        }
        echo '</SELECT>';

    } else {
        echo '<font size="-1">';
        echo $allitag;
        echo '</font>';
    }
    echo '</td>';
    echo '</tr>';

    // sperren
	    echo '<tr>';
	    echo '<td bgcolor="#aaaaaa">';
	    echo '<font size="-1">';
	    echo 'Sperren:';
	    echo '</font>';
	    echo '</td>';
	    echo '<td bgcolor="#aaaaaa">';
	    if ( $_GET['change']=='spy' ) {
	        $add = '';
	        if ( $spy!='0' )
	            $add=' checked';
	        echo '<input type="checkbox" name="spy"'.$add.' value="gesperrt">';
	    } else {
	        echo '<font size="-1">';

	        //status-anzeige

	        if ($spy == 1)
                {
                $status = '<font color="#ff0000">Gesperrt</font>';
                } else {
					if(mysql_result( $SQL_result, 0, 'versuche') >= 3 && mysql_result( $SQL_result, 0, 'ip') != "")
					    $status = '<font color="#cc0000">IP '.mysql_result( $SQL_result, 0, 'ip').' gesperrt</font>';
					else
                        $status = '<font color="#00cc00">Entsperrt</font>';
                }
	        echo $status;
	        echo '</font>';
	    }
	    echo '</td>';
    echo '</tr>';

    echo '</td>';
    echo '<td colspan="2" bgcolor="#aaaaaa">';
    echo '<br><input type="submit" value="&auml;ndern">';
    echo '</td>';
    echo '</tr>';


    echo '</form>';
    echo '</table>';


    echo '</td></tr></table>';


?>
</center>
