<?PHP
    $oldName=$_SERVER['PHP_AUTH_USER'];
    unset( $_SERVER['PHP_AUTH_USER'] );
    unset( $_SERVER['PHP_AUTH_PW'] );
    header( 'WWW-Authenticate: Basic realm="T.I.C. | Tactical Information Center"');
    header( "HTTP/1.0 401 Unauthorized" );
    die();
?>
