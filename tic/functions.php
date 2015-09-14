<?PHP
    function GetScans($SQL_DBConn, $galaxie, $planet) {
        $scan_type[0] = 'S';
        $scan_type[1] = 'E';
        $scan_type[2] = 'M';
        $scan_type[3] = 'G';
        $SQL_Result = tic_mysql_query('SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;') or die(tic_mysql_error(__FILE__,__LINE__));
        //echo "Scan: ".'SELECT * FROM `gn4scans` WHERE rg="'.$galaxie.'" AND rp="'.$planet.'" ORDER BY type;<br />';
        $SQL_Num = mysql_num_rows($SQL_Result);
        if ($SQL_Num == 0)
            return '[-]';
        else {
            $tmp_result = '[';
            for ($n = 0; $n < $SQL_Num; $n++)
            {
                $tmp_result = $tmp_result.$scan_type[mysql_result($SQL_Result, $n, 'type')];
            }
            $tmp_result = $tmp_result.']';
        //    echo "Scan=>$tmp_result<br />";
            return $tmp_result;
        }
        return null;
    }

    function LogAction($text)
    {
        global $Benutzer;
        global $_SERVER;
        tic_mysql_query('INSERT INTO `gn4log` (ticid, name, accid, rang, allianz, zeit, aktion) VALUES ("'.$Benutzer['ticid'].'", "'.$Benutzer['name'].'", "'.$Benutzer['id'].'", "'.$Benutzer['rang'].'", "'.$Benutzer['allianz'].'", "'.date("d.m.Y H:i").'", "'.$text.'\r\nIP:'.$_SERVER['REMOTE_ADDR'].'")') or die(tic_mysql_error(__FILE__,__LINE__));
    }

    function ZahlZuText($zahl)
    {
        $x = 0;
        $text = '';
        for ($n = strlen($zahl); $n >= 0; $n--)
        {
            $text = substr($zahl, $n, 1).$text;
            if ($x >= 3 && $n > 0) {
                $x = 0;
                $text = '.'.$text;
            }
            $x++;
        }
        return $text;
    }

    function TextZuZahl($text)
    {
        $zahl = str_replace(',', '', $text);
        $zahl = str_replace('.', '', $zahl);
        return intval($zahl);
    }

    function CountScans($id)
    {
        $SQL_Result = tic_mysql_query('SELECT COUNT(id) FROM `gn4accounts` WHERE id="'.$id.'"') or die(tic_mysql_error(__FILE__,__LINE__));;
        $count = mysql_fetch_row($SQL_Result);
        if($count[0])
        {
            tic_mysql_query('UPDATE `gn4accounts` SET scans = scans+1 WHERE id="'.$id.'"') or die(tic_mysql_error(__FILE__,__LINE__));;
        }
    }

    function getime4display( $time_in_min )
    {
        global $Benutzer;
        global $displayflag;
        if ($time_in_min < 0)
            $time_in_min=0;
        if (!isset($displayflag))
        {
            $displayflag=0;
            $SQL_Result3 = tic_mysql_query('SELECT zeitformat FROM `gn4accounts` WHERE id="'.$Benutzer['id'].'"') or die(tic_mysql_error(__FILE__,__LINE__));
            $displayflag =  mysql_result($SQL_Result3, 0, 'zeitformat' );
        }
        switch( $displayflag )
        {

            case 1:     // std:min
                $result_std = sprintf("%02d", intval($time_in_min / 60));
                $result_min = sprintf("%02d", intval($time_in_min % 60));
                $result = $result_std.':'.$result_min;
                break;
            case 2:     // ticks
                $result = (int)($time_in_min / 15);
                break;
           default:
                $result=$time_in_min;
           break;


        }
        return $result;
    }

    function addgnuser($gala, $planet, $name, $kommentare="")
    {
        if ($name != "")
        {
            tic_mysql_query('DELETE FROM `gn4gnuser` WHERE gala="'.$gala.'" AND planet="'.$planet.'"') or die(tic_mysql_error(__FILE__,__LINE__));
            tic_mysql_query('INSERT INTO `gn4gnuser` (gala, planet, name, kommentare, erfasst) VALUES ("'.$gala.'", "'.$planet.'", "'.$name.'", "'.$kommentare.'", "'.time().'")') or die(tic_mysql_error(__FILE__,__LINE__));
        }
    }

    function gnuser($gala, $planet)
    {
        if($gala != "" && $planet != "")
        {
            $SQL_Result = tic_mysql_query('SELECT name FROM `gn4gnuser` WHERE gala="'.$gala.'" AND planet="'.$planet.'"') or die(tic_mysql_error(__FILE__,__LINE__));
            if($user = mysql_fetch_row($SQL_Result))
                return $user[0];
            else
            {
                $SQL_Result = tic_mysql_query('SELECT name FROM `gn4accounts` WHERE galaxie="'.$gala.'" AND planet="'.$planet.'"') or die(tic_mysql_error(__FILE__,__LINE__));
                if($user = mysql_fetch_row($SQL_Result))
                    return $user[0];
            }
        }
        return "¿Unknown?";
    }

    function eta($time1, $time2 = null)
    {
        global $Ticks;
        if($time2 === null)
        {
            $time2 = $time1;
            $time1 = time();
        }
        $eta = ceil((($time2-$time1)/60)/$Ticks['lange']);
        if($eta < 0)
            $eta = 0;
        return $eta;
    }

    function tic_mysql_query($query, $file = null, $line = null)
    {
        $GLOBALS['last_sql_query'] = $query;
        $query_result = mysql_query($query, $GLOBALS['SQL_DBConn']);
        if(!$query_result && $file != null)
        {
            die(tic_mysql_error($file, $line));
        }

        return $query_result;
    }

    function tic_mysql_error($file = null, $line = null)
    {
        return "<div style=\"text-align:left\"><ul><b>Mysql Fehler".($file != "" ? " in ".$file."(".$line.")" : "").":</b>".($GLOBALS['last_sql_query'] ? "\n<li><b>Query:</b> ".$GLOBALS['last_sql_query']."</li>\n" : "")."<li><b>Fehlermeldung:</b> ".mysql_errno()." - ".mysql_error()."</li>\n</ul></div></body></html>";
    }
?>

