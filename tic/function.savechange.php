<?php
    if ( !isset( $_GET['fbid'] ) ){
        echo 'internal parameter-error #1';
        return;
    }
    if ( !isset( $_GET['incsave'] ) ){
        echo 'internal parameter-error #2';
        return;
    }

    if ( $_GET['incsave'] == 1 )
        $newsave=0;
    else
        $newsave=1;

    $sql = 'UPDATE `gn4flottenbewegungen` SET save="'.$newsave.'" WHERE id='.$_GET['fbid'];
    if ( $sql != '' ){
        $SQL_result = mysql_query( $sql, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    }


?>
