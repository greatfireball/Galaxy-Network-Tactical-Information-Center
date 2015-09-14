<?
	include('./accdata.php');
	include('./functions.php');
	include('./globalvars.php');
	
	// Kein Fehler zu Beginn ^^
	$error_code = 0;
	
	// HTML Style
	$htmlstyle['hell'] = 'eeeeee';
	$htmlstyle['dunkel'] = 'dddddd';
	$htmlstyle['hell_rot'] = 'ffaaaa';
	$htmlstyle['dunkel_rot'] = 'ff8888';
	$htmlstyle['hell_gruen'] = 'aaffaa';
	$htmlstyle['dunkel_gruen'] = '88ff88';
	$htmlstyle['hell_blau'] = 'aaaaff';
	$htmlstyle['dunkel_blau'] = '8888ff';
	
	// Standardmodul wählen falls nicht angegeben
	if (!isset($modul)) $modul = '';
	if ($modul == '') $modul = 'nachrichten';
	
	// Verbindung zur Datenbank aufbauen
	$SQL_DBConn = mysql_connect($db_info['host'], $db_info['user'], $db_info['password']  ) or $error_code = 1;
    mysql_select_db($db_info['dbname'], $SQL_DBConn) or $error_code = 2;
    

    // Variablen laden
    include('./vars.php');
	
	// Ticks
	$Ticks['angriffsflug'] = 30;
	$Ticks['angriff'] = 5;
	$Ticks['verteidigungsflug'] = 20;
	$Ticks['verteidigen'] = 25;
		
	// Diverenzberechnung ohne Cron-JOB!!!
	// Dazu gefgt von Mojah 2004
	
	$alt_ticks = $_REQUEST["alt_ticks"];
	$null_ticks = $_REQUEST["null_ticks"];
	
	$div_ticks = $null_ticks - $alt_ticks;
	
	echo "div = $null_ticks - $alt_ticks = $div_ticks<br>";
	
	
	// Ende Vorbereitung!
	
    /* Tick Test (keine Ahnung was da gemacht wird, da lasttick_minute nicht gesetzt ist! Mojah
    $minute_jetzt = date('i') + 60;

    $minute_vergangen = bcmod(($minute_jetzt - $lasttick_minute), 60);
    if ($minute_vergangen < 15) $div_ticks=0;
    $minute_naechste = $lasttick_minute + 15;
    if ($minute_naechste == 60) $minute_naechste = 0;
    */
		
    // cron Berechnungen
if($div_ticks > 0)
{
    $SQL_Result = mysql_query('SELECT * FROM `gn4flottenbewegungen` ORDER BY id;', $SQL_DBConn);
    $SQL_Num = mysql_num_rows($SQL_Result);

	if ($SQL_Num != 0)
	{
	
		for ($n = 0; $n < $SQL_Num; $n++) {
		
		$eintrag_id = mysql_result($SQL_Result, $n, 'id');
		$eintrag_modus = mysql_result($SQL_Result, $n, 'modus');
		$eintrag_angreifer_galaxie = mysql_result($SQL_Result, $n, 'angreifer_galaxie');
		$eintrag_angreifer_planet = mysql_result($SQL_Result, $n, 'angreifer_planet');
		$eintrag_angreifer_name = mysql_result($SQL_Result, $n, 'angreifer_name');
		$eintrag_verteidiger_galaxie = mysql_result($SQL_Result, $n, 'verteidiger_galaxie');
		$eintrag_verteidiger_planet = mysql_result($SQL_Result, $n, 'verteidiger_planet');
		$eintrag_verteidiger_name = mysql_result($SQL_Result, $n, 'verteidiger_name');
		$eintrag_eta = mysql_result($SQL_Result, $n, 'eta');
		$eintrag_flugzeit = mysql_result($SQL_Result, $n, 'flugzeit');
		
		echo "<table><tr><td>Mode=$eintrag_modus</td><td>ETA=$eintrag_eta</td><td>FZ=$eintrag_flugzeit</td></tr></table>";
		echo "eta := $eintrag_eta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; div=$div_ticks<br />";
		if ($eintrag_eta > $div_ticks) 
		{
			$eintrag_eta-= $div_ticks;
			echo 'if ($eintrag_eta > $div_ticks) <br />';
			echo 'UPDATE `gn4flottenbewegungen` SET eta="'.$eintrag_eta.'" WHERE id="'.$eintrag_id.'";<br />';
		} 
		elseif ($eintrag_eta <= $div_ticks) 
		{
			echo 'elseif ($eintrag_eta <= $div_ticks) <br />';
			if ($eintrag_modus == 0) 
			{
				echo 'if ($eintrag_modus == 0) <br />';
				echo 'DELETE FROM `gn4flottenbewegungen` WHERE id='.$eintrag_id.'<br />';
			} 
			else  //1
			{
			 echo 'else //1<br />';
			 if ($eintrag_eta == $div_ticks) 
			 {
					$eintrag_eta-= $div_ticks;
					echo 'if ($eintrag_eta == $div_ticks) <br />';
					echo "eta := $eintrag_eta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; div=$div_ticks<br />";
					echo 'UPDATE `gn4flottenbewegungen` SET eta="'.$eintrag_eta.'" WHERE id="'.$eintrag_id.'";<br />';
			 } 
			 else //2
			 {
			 	echo 'else //2<br />';
			 	// Wie viele Ticks sind noch übrig?
			 	$div_ticks -= $eintrag_eta;
				echo "eta := $eintrag_eta&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; div=$div_ticks<br />";
			 	if ($eintrag_flugzeit > $div_ticks) 
				{
					$eintrag_flugzeit-= $div_ticks;
					echo 'if ($eintrag_flugzeit > $div_ticks) <br />';
					echo 'UPDATE `gn4flottenbewegungen` SET flugzeit="'.$eintrag_flugzeit.'" WHERE id="'.$eintrag_id.'";<br />';
				} 
				else //3
				{
					echo 'else //3<br />';
					if ($eintrag_modus == 1) // Angriffsflug???
					{
						echo 'if ($eintrag_modus == 1) // Angriffsflug???<br />';
						$eintrag_eta = $Ticks['angriffsflug'] + $eintrag_flugzeit - $div_ticks; // Bin schon unterwegs zurück!
						echo 'UPDATE `gn4flottenbewegungen` SET modus="0", flugzeit="0", eta="'.$eintrag_eta.'" WHERE id="'.$eintrag_id.'";<br />';
					} 
					else // => Verteidungsflug!
					{
						echo 'else // => Verteidungsflug!<br />';
						$eintrag_eta = $Ticks['verteidigungsflug'] + $eintrag_flugzeit - $div_ticks; // Bin schon unterwegs zurück!
						echo 'UPDATE `gn4flottenbewegungen` SET modus="0", flugzeit="0", eta="'.$eintrag_eta.'" WHERE id="'.$eintrag_id.'";<br />';
					}
				}
			 }
			}
		}
	}
 }
	//echo 'UPDATE `gn4vars` SET value="'.$minute_naechste.'" WHERE name="lasttick_minute";<br>';
//	echo 'UPDATE `gn4vars` SET value="'.date('H').':'.date('i').':'.date('s').'" WHERE name="lasttick";';
//	$SQL_Result = mysql_query('UPDATE `gn4vars` SET value="'.$minute_naechste.'" WHERE name="lasttick_minute";', $SQL_DBConn) or $error_code = 7;
	$SQL_Result = mysql_query('UPDATE `gn4vars` SET value="'.date('H').':'.date('i').':'.date('s').'" WHERE name="lasttick";', $SQL_DBConn) or $error_code = 7;
}
?>
