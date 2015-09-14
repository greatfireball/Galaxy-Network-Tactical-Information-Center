<?php
    if(!isset($_GET['auto']))
    {
        if(!isset($NW_intervall))
            $NW_intervall = 120;
        $today = date("w");
        if($today == 0)
            $today = 7;
            
        $time = time() - ($today-1)*86400;
            
        tic_mysql_query("UPDATE gn4nachtwache SET done".$today."='1' WHERE ticid = '".$Benutzer['ticid']."' AND gala='".$Benutzer['galaxie']."' AND planet".$today." = '".$Benutzer['planet']."' AND ".$time." >= time AND ".($time-$NW_intervall*60)." <= time") or die(tic_mysql_error(__FILE__,__LINE__));

        tic_mysql_query("DELETE FROM gn4nachtwache WHERE time < ".(time()-1209600)) or die(tic_mysql_error(__FILE__,__LINE__));
    }
?>
