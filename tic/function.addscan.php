<?PHP

function parseLine( $line_in) {
	$templine = str_replace( chr(9), ' ',  $line_in );
	$templine = str_replace( '  ', ' ', $templine );
	$templine = str_replace( '.', '', $templine );

	return explode( ' ', trim( $templine ));
}

// Übergebene Werte setzen
	$action = isset($_POST['action'])?$_POST['action']:(isset($_GET['action'])?$_GET['action']:"");
	$txtScan = isset($_POST['txtScan'])?$_POST['txtScan']:"";


// Scan hinzufügen
	if ($action == 'addscan') {
		if ($txtScan != '') {
			$txtScanOrg = $txtScan;
			$txtScan = str_replace( " \t", ' ', $txtScan );
			$txtScan = str_replace( "\t", ' ', $txtScan );
			$zeilen = explode("\n", trim($txtScan));		// Zeilenumbrüche
			$zeilen = explode("\x0d", trim($txtScan));		// Zeilenumbrüche
			$zeilen = explode("\x0a", trim($txtScan));		// Zeilenumbrüche
			$zeilen[0] = str_replace(':', ' ', $zeilen[0]);
			$daten = explode(' ', trim($zeilen[0]));		// Sektorscan Ergebnis (Genauigkeit:100%)
			$scan_typ = trim($daten[0]);

			if (ereg("(Flottenbewegungen[^·]*·  Nachricht an die gesamte Galaxie senden ··»)", $txtScanOrg, $ereg_tmp)) {
				$text_in = $ereg_tmp[1];

// Umwandeln der Eingabe auf ein einheitliches Format
				$text_in = ereg_replace( "Flottenbewegungen(.*)Sektor", "Flottenbewegungen".chr(13).chr(10)."Sektor", $text_in );
				$text_in = ereg_replace( "Sektor(.*)Kommandant", "Sektor-Kommandant",$text_in );
				$text_in = str_replace( "Greift an", "Greift_an", $text_in );
				$text_in = str_replace( "Wird angegriffen von", "Wird_angegriffen_von", $text_in );
				$text_in = str_replace( "Wird verteidigt von", "Wird_verteidigt_von", $text_in );
				$text_in = str_replace( "·  Nachricht an die gesamte Galaxie senden ··»", "·__Nachricht_an_die_gesamte_Galaxie_senden_··»", $text_in );
				$text_in = str_replace( " *", "", $text_in );
				$text_in = str_replace( " Min", "m", $text_in );
				$text_in = str_replace( " Std", "s", $text_in );
				$text_in = str_replace(chr(32).chr(9), chr(9), $text_in );
				$text_in = str_replace(chr(32).chr(13).chr(10).chr(32).chr(13).chr(10), chr(32).chr(13).chr(10), $text_in );
				$text_in = str_replace(chr(32).chr(13).chr(10), chr(13).chr(10), $text_in );
				$text_in = preg_replace( "|(\:\d+)[ ".chr(9)."]([^".chr(10).chr(13)."])|", "$1-$2", $text_in );
				$text_in = str_replace("Rückflug".chr(13).chr(10)."(", "Rückflug-", $text_in );
				$text_in = str_replace(")", "", $text_in );
				$text_in = str_replace(chr(32), chr(9), $text_in );
				$text_in = str_replace(chr(13).chr(10).chr(9), chr(9), $text_in );
				$text_in = str_replace("-".chr(9), chr(9), $text_in );


// Zerlegen der Eingabe in die Tabellen-Zellen
				$text_reg = $text_in;
				$taktik = array();
				$break_it = 0;
				do {
					$break = true;
					if ( ereg ( "([^".chr(9).chr(13).chr(10)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*)".chr(13).chr(10), $text_reg, $line_reg) ) {
						if ( ereg ( "([^".chr(9).chr(10).chr(13)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)", $line_reg[1], $cells) and sizeof($cells) == 10) {
							$temparray = $cells;
							array_shift($temparray);
							array_push($taktik, $temparray);
						}
						$text_reg = ereg_replace( quotemeta($line_reg[1]).chr(13).chr(10), "", $text_reg);
						$break = false;
					}
					$break_it++;
				} while (($break == false) && ($break_it < 25));

// Erstellen der einzelnen Flottenbewegungen
				$flottenbewegungen = array();
				$this_galaxy = 0;
				for ($i = 0; $i < sizeof($taktik); $i++) {
					if ($taktik[$i][0] == "Sektor-Kommandant") continue;
					if ( ereg ( "([^:]*):([^-]*)-(.*)", $taktik[$i][0], $temp) ) {
						$local_galaxy = $temp[1];
						$local_planet = $temp[2];
						$local_name = $temp[3];
						if ($this_galaxy == 0) $this_galaxy = $local_galaxy;
						if ( $taktik[$i][1] != "" ) { // --> Angriff
							$flotten = explode(chr(13).chr(10), $taktik[$i][1]);
							$etas = explode(chr(13).chr(10), $taktik[$i][2]);
							for ($ii = 0; $ii < sizeof($etas); $ii++) {
								if (strpos($etas[$ii], ":")>0) {
									if (ereg("00:00", $etas[$ii])) {
										$etas[$ii] = 0;
									} else {
										$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/15)+1;
									}
								} elseif (strpos($etas[$ii], "s")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/15)+1;
								} elseif (strpos($etas[$ii], "m")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/15)+1;
								}
							}
							for ($ii = 0; $ii < sizeof($flotten); $ii++) {
								$modus = 1;
								if ( ereg ( "Rückflug-", $flotten[$ii]) ) {
									$flotten[$ii] = str_replace("Rückflug-", "", $flotten[$ii]);
									$modus += 2;
								}
								if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) {
									$flotte_galaxy = $ftemp[1];
									$flotte_planet = $ftemp[2];
									$flotte_name = $ftemp[3];
									array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $local_galaxy, "start_planet" => $local_planet, "start_name" => $local_name, "ziel_galaxie" => $flotte_galaxy, "ziel_planet" => $flotte_planet, "ziel_name" => $flotte_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0, "mod" => 0));
								}
							}
						}
						if ( $taktik[$i][3] != "" ) { // --> Verteidigung
							$flotten = explode(chr(13).chr(10), $taktik[$i][3]);
							$etas = explode(chr(13).chr(10), $taktik[$i][4]);
							for ($ii = 0; $ii < sizeof($etas); $ii++) {
								if (strpos($etas[$ii], ":")>0) {
									if (ereg("00:00", $etas[$ii])) {
										$etas[$ii] = 0;
									} else {
										$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/15)+1;
									}
								} elseif (strpos($etas[$ii], "s")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/15)+1;
								} elseif (strpos($etas[$ii], "m")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/15)+1;
								}
							}
							for ($ii = 0; $ii < sizeof($flotten); $ii++) {
								$modus = 2;
								if ( ereg ( "Rückflug-", $flotten[$ii]) ) {
									$flotten[$ii] = str_replace("Rückflug-", "", $flotten[$ii]);
									$modus += 2;
								}
								if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) {
									$flotte_galaxy = $ftemp[1];
									$flotte_planet = $ftemp[2];
									$flotte_name = $ftemp[3];
									array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $local_galaxy, "start_planet" => $local_planet, "start_name" => $local_name, "ziel_galaxie" => $flotte_galaxy, "ziel_planet" => $flotte_planet, "ziel_name" => $flotte_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0));
								}
							}
						}
						if ( $taktik[$i][5] != "" ) { // <-- Angriff
							$flotten = explode(chr(13).chr(10), $taktik[$i][5]);
							$etas = explode(chr(13).chr(10), $taktik[$i][6]);
							for ($ii = 0; $ii < sizeof($etas); $ii++) {
								if (strpos($etas[$ii], ":")>0) {
									if (ereg("00:00", $etas[$ii])) {
										$etas[$ii] = 0;
									} else {
										$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/15)+1;
									}
								} elseif (strpos($etas[$ii], "s")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/15)+1;
								} elseif (strpos($etas[$ii], "m")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/15)+1;
								}
							}
							for ($ii = 0; $ii < sizeof($flotten); $ii++) {
								$modus = 1;
								if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) {
									$flotte_galaxy = $ftemp[1];
									$flotte_planet = $ftemp[2];
									$flotte_name = $ftemp[3];
									array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $flotte_galaxy, "start_planet" => $flotte_planet, "start_name" => $flotte_name, "ziel_galaxie" => $local_galaxy, "ziel_planet" => $local_planet, "ziel_name" => $local_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0));
								}
							}
						}
						if ( $taktik[$i][7] != "" ) { // <-- Verteidigung
							$flotten = explode(chr(13).chr(10), $taktik[$i][7]);
							$etas = explode(chr(13).chr(10), $taktik[$i][8]);
							for ($ii = 0; $ii < sizeof($etas); $ii++) {
								if (strpos($etas[$ii], ":")>0) {
									if (ereg("00:00", $etas[$ii])) {
										$etas[$ii] = 0;
									} else {
										$etas[$ii] = (int)((substr($etas[$ii],0,2)*60 + substr($etas[$ii],3,2))/15)+1;
									}
								} elseif (strpos($etas[$ii], "s")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "s"))*60/15)+1;
								} elseif (strpos($etas[$ii], "m")>0) {
									$etas[$ii] = (int)(substr($etas[$ii],0,strpos($etas[$ii], "m"))/15)+1;
								}
							}
							for ($ii = 0; $ii < sizeof($flotten); $ii++) {
								$modus = 2;
								if ( ereg ( "([^:]*):([^-]*)-(.*)", $flotten[$ii], $ftemp) ) {
									$flotte_galaxy = $ftemp[1];
									$flotte_planet = $ftemp[2];
									$flotte_name = $ftemp[3];
									if ($flotte_galaxy != $this_galaxy)
										array_push($flottenbewegungen, array("modus" => $modus, "start_galaxie" => $flotte_galaxy, "start_planet" => $flotte_planet, "start_name" => $flotte_name, "ziel_galaxie" => $local_galaxy, "ziel_planet" => $local_planet, "ziel_name" => $local_name, "eta" => $etas[$ii], "fleet" => 0, "safe" => 0, "mod" => 0));
								}
							}
						}
					}
				}

				$SQL_Query = 'SELECT * FROM `gn4flottenbewegungen` WHERE (angreifer_galaxie='.$this_galaxy.' OR verteidiger_galaxie='.$this_galaxy.') ORDER BY eta;';
				$SQL_Result = mysql_query( $SQL_Query, $SQL_DBConn) or die('<br>mist - n db-error!!!');

				for ($i=0; $i < mysql_num_rows($SQL_Result); $i++){
					$start_galaxie = mysql_result($SQL_Result, $i, 'angreifer_galaxie');
					$start_planet = mysql_result($SQL_Result, $i, 'angreifer_planet');
					$ziel_galaxie = mysql_result($SQL_Result, $i, 'verteidiger_galaxie');
					$ziel_planet = mysql_result($SQL_Result, $i, 'verteidiger_planet');
					for ($ii = 0; $ii < sizeof($flottenbewegungen); $ii++) {
						if ($flottenbewegungen[$ii]["mod"] == 0 && $flottenbewegungen[$ii]["start_galaxie"] == $start_galaxie && $flottenbewegungen[$ii]["start_planet"] == $start_planet && $flottenbewegungen[$ii]["ziel_galaxie"] == $ziel_galaxie && $flottenbewegungen[$ii]["ziel_planet"] == $ziel_planet) {
//							echo "DB-&Uuml;bernahme: ".$start_galaxie.":".$start_planet." -> ".$ziel_galaxie.":".$ziel_planet."<br>\n";
							$flottenbewegungen[$ii]["mod"] = 1;
							$flottenbewegungen[$ii]["fleet"] = mysql_result($SQL_Result, $i, 'flottennr');
							$flottenbewegungen[$ii]["safe"] = 1 - mysql_result($SQL_Result, $i, 'save');
							break;
						}
					}
				}

				$delcommand = 'DELETE FROM `gn4flottenbewegungen` WHERE (angreifer_galaxie='.$this_galaxy.' or verteidiger_galaxie='.$this_galaxy.');';
				$SQL_Result = mysql_query( $delcommand, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
				$action = "flottenbewegung";
				for ($i = 0; $i < sizeof($flottenbewegungen); $i++) {
					switch ($flottenbewegungen[$i]["modus"]) {
						case 1:
							echo "Angriff: ";
							break;
						case 2:
							echo "Verteidigung: ";
							if ($flottenbewegungen[$i]["fleet"] == 0) $flottenbewegungen[$i]["fleet"] = 1;
							break;
						case 3:
							echo "Angriff (R&uuml;ckflug): ";
							break;
						case 4:
							echo "Verteidigung (R&uuml;ckflug): ";
							if ($flottenbewegungen[$i]["fleet"] == 0) $flottenbewegungen[$i]["fleet"] = 1;
							break;
					}
					echo $flottenbewegungen[$i]["start_galaxie"].":".$flottenbewegungen[$i]["start_planet"]." ".$flottenbewegungen[$i]["start_name"]." -> ".$flottenbewegungen[$i]["ziel_galaxie"].":".$flottenbewegungen[$i]["ziel_planet"]." ".$flottenbewegungen[$i]["ziel_name"]." ETA: ".$flottenbewegungen[$i]["eta"]." (".date("d.M H:i", ((int)(time() / 900) + $flottenbewegungen[$i]["eta"]) * 900).") / Flotte: ".$flottenbewegungen[$i]["fleet"]." / safe: ".$flottenbewegungen[$i]["safe"]."<br>\n";

					$txt_Angreifer_Galaxie		= $flottenbewegungen[$i]["start_galaxie"];
					$txt_Angreifer_Planet		= $flottenbewegungen[$i]["start_planet"];
					$txt_Angreifer_Name		= $flottenbewegungen[$i]["start_name"];
					$txt_Verteidiger_Galaxie	= $flottenbewegungen[$i]["ziel_galaxie"];
					$txt_Verteidiger_Planet		= $flottenbewegungen[$i]["ziel_planet"];
					$txt_Verteidiger_Name		= $flottenbewegungen[$i]["ziel_name"];
					$txt_not_safe			= 1 - $flottenbewegungen[$i]["safe"];
					$lst_ETA			= $flottenbewegungen[$i]["eta"];
					$lst_Flotte			= $flottenbewegungen[$i]["fleet"];
					$modus				= $flottenbewegungen[$i]["modus"];
					include("function.flottenbewegung.php");

				}

			}

			if (ereg("(Galaxiemitglieder[^·]*·  Nachricht an die gesamte Galaxie senden ··»)", $txtScanOrg, $ereg_tmp)) {
				$text_in = $ereg_tmp[1];

		// Umwandeln der Eingabe auf ein einheitliches Format
				$text_in = ereg_replace( "Galaxiemitglieder(.*)Sektor", "Galaxiemitglieder".chr(13).chr(10)."Sektor", $text_in );
				$text_in = ereg_replace( "Sektor(.*)Kommandant", "Sektor-Kommandant",$text_in );
				$text_in = str_replace( "Extraktoren [Metall/Kristall]", "Extraktoren", $text_in );
				$text_in = str_replace( " / ", "/", $text_in );
				$text_in = str_replace( " *", "", $text_in );
				$text_in = str_replace( "·  Nachricht an die gesamte Galaxie senden ··»", "·__Nachricht_an_die_gesamte_Galaxie_senden_··»", $text_in );
				$text_in = str_replace(chr(32).chr(9), chr(9), $text_in );
				$text_in = str_replace(chr(32).chr(13).chr(10).chr(32).chr(13).chr(10), chr(32).chr(13).chr(10), $text_in );
				$text_in = str_replace(chr(32).chr(13).chr(10), chr(13).chr(10), $text_in );
				$text_in = preg_replace( "|(\:\d+)[ ".chr(9)."]([^".chr(10).chr(13)."])|", "$1-$2", $text_in );
				$text_in = str_replace(chr(32), chr(9), $text_in );
				$text_in = str_replace(chr(9).chr(9), chr(9), $text_in );
				$text_in = str_replace(chr(13).chr(10).chr(9), chr(9), $text_in );
				$text_in = str_replace(chr(9).chr(13).chr(10), chr(13).chr(10), $text_in );
				$text_in = str_replace("-".chr(9), chr(9), $text_in );

		// Zerlegen der Eingabe in die Tabellen-Zellen
				$text_reg = $text_in;
				$galaxie = array();
				$break_it = 0;
				do {
					$break = true;
					if ( ereg ( "([^".chr(9).chr(13).chr(10)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*".chr(9)."[^".chr(9)."]*)".chr(13).chr(10), $text_reg, $line_reg) ) {
						if ( ereg ( "([^".chr(9).chr(10).chr(13)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)".chr(9)."([^".chr(9)."]*)", $line_reg[1], $cells) and sizeof($cells) == 7) {
							$temparray = $cells;
							array_shift($temparray);
							array_push($galaxie, $temparray);
						}
						$text_reg = ereg_replace( quotemeta($line_reg[1]).chr(13).chr(10), "", $text_reg);
						$break = false;
					}
					$break_it++;
				} while (($break == false) && ($break_it < 25));

		// Daten-Array erstellen
				$galaxiemitglieder = array();
				for ($i = 0; $i < sizeof($galaxie); $i++) {
					if ($galaxie[$i][0] == "Sektor-Kommandant") continue;
					if ($galaxie[$i][0] == "Gesamt:") continue;
					if ($galaxie[$i][0] == "Durchschnitt:") continue;
					if ( ereg ( "([^:]*):([^-]*)-(.*)", $galaxie[$i][0], $temp) ) {
						$local_galaxy = $temp[1];
						$local_planet = $temp[2];
						$local_name = $temp[3];
						ereg ( "([^/]*)/(.*)", $galaxie[$i][4], $temp);
						$mex = $temp[1];
						$kex = $temp[2];
						array_push($galaxiemitglieder, array("galaxie" => $local_galaxy, "planet" => $local_planet, "name" => $local_name, "punkte" => str_replace(".", "", $galaxie[$i][1]), "flotte" => $galaxie[$i][2], "geschuetze" => $galaxie[$i][3], "mextraktoren" => $mex, "kextraktoren" => $kex, "asteroiden" => $galaxie[$i][5]));
					}
				}

				for ($i = 0; $i < sizeof($galaxiemitglieder); $i++) {
					echo $galaxiemitglieder[$i]["galaxie"].":".$galaxiemitglieder[$i]["planet"]." ".$galaxiemitglieder[$i]["name"]." -> ".$galaxiemitglieder[$i]["punkte"]."<br>\n";
					addgnuser($galaxiemitglieder[$i]["galaxie"], $galaxiemitglieder[$i]["planet"], $galaxiemitglieder[$i]["name"]);

					$delcommand = "DELETE FROM `gn4scans` WHERE rg='".$galaxiemitglieder[$i]["galaxie"]."' AND rp='".$galaxiemitglieder[$i]["planet"]."' AND type='0';";
					$SQL_Result = mysql_query( $delcommand, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());

					$addcommand = "INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, pts, s, d, me, ke, a)
								VALUES ('0', '".date("H:i d.m.Y")."', '".$Benutzer['galaxie']."', '".$Benutzer['planet']."', '".$galaxiemitglieder[$i]["galaxie"]."', '".$galaxiemitglieder[$i]["planet"]."', '99', '".$galaxiemitglieder[$i]["punkte"]."', '".$galaxiemitglieder[$i]["flotte"]."', '".$galaxiemitglieder[$i]["geschuetze"]."', '".$galaxiemitglieder[$i]["mextraktoren"]."', '".$galaxiemitglieder[$i]["kextraktoren"]."', '".$galaxiemitglieder[$i]["asteroiden"]."');";
					$SQL_Result = mysql_query( $addcommand, $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
				}

			}

            if ( $scan_typ == 'Flottenzusammensetzung' ) {
                $scan_gen = 99;
                $daten = parseLine( $zeilen[0] );
                $scan_rn = trim( $daten[2] );
                $scan_rg = trim( $daten[3] );                       // Koordinaten: 233:20
                $scan_rg = substr( $scan_rg, 1 );                       // Koordinaten: 233:xx
                $scan_rp = trim( $daten[4] );                      // Koordinaten: 233:20
                $rp_len = strlen( $scan_rp );
                $scan_rp = substr( $scan_rp, 0, $pr_len-1  );                      // Koordinaten: xxx:20

                $scan_type = 2;
                for($n = 2; $n <= 50; $n++) {
                    if (!isset($zeilen[$n])) $zeilen[$n] = '0';
                    $zeilen[$n] = str_replace(',', '', $zeilen[$n]);
                }
                $daten = parseLine( $zeilen[1] );
                if ( $daten[0] == "" ){
                    $idx = 3;   // ie, mozilla
                } else {
                    $idx = 2;   // opera
                }

                $daten = parseLine( $zeilen[$idx] );            // Jäger
                $scan_sf0j = trim(   $daten[1]);
                $scan_sf1j = trim(   $daten[2]);
                $scan_sf2j = trim(   $daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // bomber
                $scan_sf0b = trim(   $daten[1]);
                $scan_sf1b = trim(   $daten[2]);
                $scan_sf2b = trim(   $daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // fregs
                $scan_sf0f = trim(   $daten[1]);
                $scan_sf1f = trim(   $daten[2]);
                $scan_sf2f = trim(   $daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // zerries
                $scan_sf0z = trim(   $daten[1]);
                $scan_sf1z = trim(   $daten[2]);
                $scan_sf2z = trim(   $daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx]);            // kreuzer
                $scan_sf0kr = trim(   $daten[1]);
                $scan_sf1kr = trim(   $daten[2]);
                $scan_sf2kr = trim(   $daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // schlachter
                $scan_sf0sa = trim(   $daten[1]);
                $scan_sf1sa = trim(   $daten[2]);
                $scan_sf2sa = trim(   $daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // träger
                $scan_sf0t  = trim(   $daten[1]);
                $scan_sf1t  = trim(   $daten[2]);
                $scan_sf2t  = trim(   $daten[3]);

                $sf0ko = 0;
                $sf1ko = 0;
                $sf2ko = 0;

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // Kaper
                $scan_sf0ka = trim(   $daten[1]);
                $scan_sf1ka = trim(   $daten[2]);
                $scan_sf2ka = trim(   $daten[3]);

                $idx++;
                $daten = parseLine( $zeilen[$idx] );            // schutzies
                $scan_sf0su = trim(   $daten[1]);
                $scan_sf1su = trim(   $daten[2]);
                $scan_sf2su = trim(   $daten[3]);


                $scan_status0 = 4;
                $scan_status1 = 4;
                $scan_status2 = 4;
                // insert mili  ............................................
                $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ko, sf0ka, sf0su';
                $insert_names = $insert_names.', sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ko, sf1ka, sf1su, status1';
                $insert_names = $insert_names.', sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ko, sf2ka, sf2su, status2';
                $insert_values = '"'.$scan_sf0j.'", "'.$scan_sf0b.'", "'.$scan_sf0f.'", "'.$scan_sf0z.'", "'.$scan_sf0kr.'", "'.$scan_sf0sa.'", "'.$scan_sf0t.'", "'.$scan_sf0ko.'", "'.$scan_sf0ka.'", "'.$scan_sf0su.'"';
                $insert_values = '"'.$scan_sf0j.'", "'.$scan_sf0b.'", "'.$scan_sf0f.'", "'.$scan_sf0z.'", "'.$scan_sf0kr.'", "'.$scan_sf0sa.'", "'.$scan_sf0t.'", "'.$scan_sf0ko.'", "'.$scan_sf0ka.'", "'.$scan_sf0su.'"';
                $insert_values = $insert_values.', "'.$scan_sf1j.'", "'.$scan_sf1b.'", "'.$scan_sf1f.'", "'.$scan_sf1z.'", "'.$scan_sf1kr.'", "'.$scan_sf1sa.'", "'.$scan_sf1t.'", "'.$scan_sf1ko.'", "'.$scan_sf1ka.'", "'.$scan_sf1su.'", "'.$scan_status1.'"';
                $insert_values = $insert_values.', "'.$scan_sf2j.'", "'.$scan_sf2b.'", "'.$scan_sf2f.'", "'.$scan_sf2z.'", "'.$scan_sf2kr.'", "'.$scan_sf2sa.'", "'.$scan_sf2t.'", "'.$scan_sf2ko.'", "'.$scan_sf2ka.'", "'.$scan_sf2su.'", "'.$scan_status2.'"';
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');

                // insert unit  ............................................
                $scan_type = 1;
                // jäger
                $scan_sfj = $scan_sf0j + $scan_sf1j +$scan_sf2j;

                // bomber
                $scan_sfb = $scan_sf0b + $scan_sf1b + $scan_sf2b;

                // fregs
                $scan_sff = $scan_sf0f + $scan_sf1f + $scan_sf2f;

                // zerries
                $scan_sfz = $scan_sf0z + $scan_sf1z + $scan_sf2z;

                // kreuzer
                $scan_sfkr = $scan_sf0kr + $scan_sf1kr + $scan_sf2kr;

                // schlachter
                $scan_sfsa = $scan_sf0sa + $scan_sf1sa + $scan_sf2sa;

                // träger
                $scan_sft  = $scan_sf0t  + $scan_sf1t + $scan_sf2t;

                // komisches ding
                $sfko = 0;

                // Kaper
                $scan_sfka = $scan_sf0ka + $scan_sf1ka + $scan_sf2ka;

                // schutzies
                $scan_sfsu = $scan_sf0su + $scan_sf1su +$scan_sf2su;

                $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfko, sfka, sfsu';
                $insert_values = '"'.$scan_sfj.'", "'.$scan_sfb.'", "'.$scan_sff.'", "'.$scan_sfz.'", "'.$scan_sfkr.'", "'.$scan_sfsa.'", "'.$scan_sft.'", "'.$scan_sfko.'", "'.$scan_sfka.'", "'.$scan_sfsu.'"';
                $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
                addgnuser($scan_rg, $scan_rp, $scan_rn);
//print( $insert_values."<br>" );

                // insert gscan ............................................
                $daten = parseLine( $zeilen[1] );
                if ( $daten[0] == "" ){
                    $daten = parseLine( $zeilen[14] );
                    if ( $daten[0] == 'Verteidigungseinheiten') {
                        $idx2 = 14; // mozilla
                        $idx = 17;
                    } else {
                        $daten = parseLine( $zeilen[17] );
                        if ( $daten[0] == 'Verteidigungseinheiten') {
                            $idx2 = 17; // ie neu
                            $idx = 20;
                        } else {
                            $idx2 = 19; // ie alt
                            $idx = 22;
                        }
                    }
                } else {
                    $idx2 = 12; // opera
                    $idx = 14;
                }
                if ( strstr( $zeilen[$idx2], 'Verteidigungseinheiten') ){ // Verteidigungseinheiten sind vorhanden
                    $scan_type = 3;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Leichtes Orbitalgeschütz 400
                    $daten = substr( $daten, 1 );
                    $scan_glo = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Leichtes Raumgeschütz 0
                    $daten = substr( $daten, 1 );
                    $scan_glr = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Mittleres Raumgeschütz 0
                    $daten = substr( $daten, 1 );
                    $scan_gmr = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Schweres Raumgeschütz 0
                    $daten = substr( $daten, 1 );
                    $scan_gsr = trim($daten);
                    $idx++;
                    $daten = strchr( $zeilen[$idx] , ':' );            // Abfangjäger 1000
                    $daten = substr( $daten, 1 );
                    $scan_ga = trim($daten);
                    $scan_gr = 0;                                 // raumbasis

                    addgnuser($scan_rg, $scan_rp, $scan_rn);
                    $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                    $insert_names = 'glo, glr, gmr, gsr, ga, gr';
                    $insert_values = '"'.$scan_glo.'", "'.$scan_glr.'", "'.$scan_gmr.'", "'.$scan_gsr.'", "'.$scan_ga.'", "'.$scan_gr.'"';
                    $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');

                }
            } else {    // sec, mili, unit, news, gscan
                $scan_gen = trim($daten[3]);
                $scan_gen = substr($scan_gen, 0, strlen($scan_gen) - 2);
                $daten = parseLine( $zeilen[1]);            // Name: FedEx
                $scan_rn = trim($daten[1]);
                $daten = parseLine( $zeilen[2]);            // Koordinaten: 233:20
                $scan_koord = trim($daten[1]);
                $scan_koord = explode(':', $scan_koord);
                $scan_rg = trim($scan_koord[0]);
                $scan_rp = trim($scan_koord[1]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
            }
            if ($scan_typ == 'Sektorscan') {
                $scan_type = 0;
                $daten = parseLine( $zeilen[3]);            // Punktzahl: 3.998.150
                $scan_pts = trim($daten[1]);
				        $daten = parseLine( $zeilen[4]);            // Schiffe: 200
                $scan_s = trim($daten[1]);
                $daten = parseLine( $zeilen[5]);            // Defensiveinheiten: 1150
                $scan_d = trim($daten[1]);
                $daten = parseLine( $zeilen[6]);            // Metall-Extraktoren: 92
                $scan_me = trim($daten[1]);
                $daten = parseLine( $zeilen[7]);            // Kristall-Extraktoren: 0
                $scan_ke = trim($daten[1]);
                $daten = parseLine( $zeilen[8]);            // Asteroiden: 20
                $scan_a = trim($daten[1]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" AND rp="'.$scan_rp.'" and type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'pts, s, d, me, ke, a';
                $insert_values = '"'.$scan_pts.'", "'.$scan_s.'", "'.$scan_d.'", "'.$scan_me.'", "'.$scan_ke.'", "'.$scan_a.'"';
                $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.')', $SQL_DBConn)  or $error_code = 7;
            }
            if ($scan_typ == 'Einheitenscan') {
                $scan_type = 1;
                $daten = parseLine( $zeilen[3]);            // Jäger 0
                $scan_sfj = trim($daten[3]);
                $daten = parseLine( $zeilen[4]);            // Bomber 0
                $scan_sfb = trim($daten[3]);
                $daten = parseLine( $zeilen[5]);            // Fregatte 0
                $scan_sff = trim($daten[3]);
                $daten = parseLine( $zeilen[6]);            // Zerstörer 0
                $scan_sfz = trim($daten[3]);
                $daten = parseLine( $zeilen[7]);            // Kreuzer 0
                $scan_sfkr = trim($daten[3]);
                $daten = parseLine( $zeilen[8]);            // Schlachtschiff 0
                $scan_sfsa = trim($daten[3]);
                $daten = parseLine( $zeilen[9]);            // Trägerschiff 0
                $scan_sft = trim($daten[3]);
//                $daten = explode(' ', trim($zeilen[10]));           // Kommandoschiff 0
                $scan_sfko = 0;
                $daten = parseLine( $zeilen[10]);           // Kaperschiff 1000
                $scan_sfka = trim($daten[3]);
                $daten = parseLine( $zeilen[11]);           // Schutzschiff 500
                $scan_sfsu = trim($daten[3]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" and rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'sfj, sfb, sff, sfz, sfkr, sfsa, sft, sfko, sfka, sfsu';
                $insert_values = '"'.$scan_sfj.'", "'.$scan_sfb.'", "'.$scan_sff.'", "'.$scan_sfz.'", "'.$scan_sfkr.'", "'.$scan_sfsa.'", "'.$scan_sft.'", "'.$scan_sfko.'", "'.$scan_sfka.'", "'.$scan_sfsu.'"';
                $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
            }
            if ($scan_typ == 'Militärscan') {
                $scan_type = 2;
                for($n = 0; $n <= 50; $n++) {
                    if (!isset($zeilen[$n])) $zeilen[$n] = '0';
                    $zeilen[$n] = str_replace(',', '', $zeilen[$n]);
                }

                $daten = parseLine( $zeilen[4]);            // Jäger
                $scan_sf0j = trim(   $daten[3]);
                $scan_sf1j = trim(   $daten[4]);
                $scan_sf2j = trim(   $daten[5]);

                $daten = parseLine( $zeilen[5]);            // bomber
                $scan_sf0b = trim(   $daten[3]);
                $scan_sf1b = trim(   $daten[4]);
                $scan_sf2b = trim(   $daten[5]);

                $daten = parseLine( $zeilen[6]);            // fregs
                $scan_sf0f = trim(   $daten[3]);
                $scan_sf1f = trim(   $daten[4]);
                $scan_sf2f = trim(   $daten[5]);

                $daten = parseLine( $zeilen[7]);            // zerries
                $scan_sf0z = trim(   $daten[3]);
                $scan_sf1z = trim(   $daten[4]);
                $scan_sf2z = trim(   $daten[5]);

                $daten = parseLine( $zeilen[8]);            // kreuzer
                $scan_sf0kr = trim(   $daten[3]);
                $scan_sf1kr = trim(   $daten[4]);
                $scan_sf2kr = trim(   $daten[5]);

                $daten = parseLine( $zeilen[9]);            // schlachter
                $scan_sf0sa = trim(   $daten[3]);
                $scan_sf1sa = trim(   $daten[4]);
                $scan_sf2sa = trim(   $daten[5]);

                $daten = parseLine( $zeilen[10]);            // träger
                $scan_sf0t  = trim(   $daten[3]);
                $scan_sf1t  = trim(   $daten[4]);
                $scan_sf2t  = trim(   $daten[5]);

                $sf0ko = 0;
                $sf1ko = 0;
                $sf2ko = 0;

                $daten = parseLine( $zeilen[11]);            // Kaper
                $scan_sf0ka = trim(   $daten[3]);
                $scan_sf1ka = trim(   $daten[4]);
                $scan_sf2ka = trim(   $daten[5]);

                $daten = parseLine( $zeilen[12]);            // schutzies
                $scan_sf0su = trim(   $daten[3]);
                $scan_sf1su = trim(   $daten[4]);
                $scan_sf2su = trim(   $daten[5]);

                $ipos = parseLine( $zeilen[13]);




                $next_word = trim( $ipos[5] );
                $scan_ziel1 = trim( $ipos[6] );
                switch ( $next_word ) {
                    case 'Im': // orbit
                        $scan_status1 = 0;
                        break;
                    case 'Rückflug':
                        $scan_status1 = 3 ;
                        break;
                    case 'Angriffsflug':
                        $scan_status1 = 1;
                        break;
                    case 'Verteidigungsflug':
                        $scan_status1 = 2;
                        break;
                }

                $next_word = trim( $ipos[7] );
                $scan_ziel2 = trim( $ipos[8] );
                switch ( $next_word ) {
                    case 'Im': // orbit
                        $scan_status2 = 0;
                        break;
                    case 'Rückflug':
                        $scan_status2 = 3;
                        break;
                    case 'Angriffsflug':
                        $scan_status2 = 1;
                        break;
                    case 'Verteidigungsflug':
                        $scan_status2 = 2;
                        break;
                }

                if (!isset($scan_status2)) $scan_status2 = 4;

                $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" and rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ko, sf0ka, sf0su';
                $insert_names = $insert_names.', sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ko, sf1ka, sf1su, status1, ziel1';
                $insert_names = $insert_names.', sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ko, sf2ka, sf2su, status2, ziel2';
                $insert_values = '"'.$scan_sf0j.'", "'.$scan_sf0b.'", "'.$scan_sf0f.'", "'.$scan_sf0z.'", "'.$scan_sf0kr.'", "'.$scan_sf0sa.'", "'.$scan_sf0t.'", "'.$scan_sf0ko.'", "'.$scan_sf0ka.'", "'.$scan_sf0su.'"';
                $insert_values = $insert_values.', "'.$scan_sf1j.'", "'.$scan_sf1b.'", "'.$scan_sf1f.'", "'.$scan_sf1z.'", "'.$scan_sf1kr.'", "'.$scan_sf1sa.'", "'.$scan_sf1t.'", "'.$scan_sf1ko.'", "'.$scan_sf1ka.'", "'.$scan_sf1su.'", "'.$scan_status1.'","'.$scan_ziel1.'"';
                $insert_values = $insert_values.', "'.$scan_sf2j.'", "'.$scan_sf2b.'", "'.$scan_sf2f.'", "'.$scan_sf2z.'", "'.$scan_sf2kr.'", "'.$scan_sf2sa.'", "'.$scan_sf2t.'", "'.$scan_sf2ko.'", "'.$scan_sf2ka.'", "'.$scan_sf2su.'", "'.$scan_status2.'","'.$scan_ziel2.'"';
                $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                if($scan_ziel1!='Orbit'){
                if($scan_status1==1||$scan_status1==2){
                $SQL_Result = mysql_query('SELECT gala,planet FROM `gn4gnuser` WHERE name="'.$scan_ziel1.'";') or die(mysql_errno()." - ".mysql_error());
                if(mysql_num_rows($SQL_Result)==1){
                $ziel1_gala = mysql_result($SQL_Result,0,'gala');
                $ziel1_planet=mysql_result($SQL_Result,0,'planet');
                $SQL_Result = mysql_query('SELECT eta FROM `gn4flottenbewegungen` WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel1_gala.'" and verteidiger_planet="'.$ziel1_planet.'";') or die(mysql_errno()." - ".mysql_error());
                if(mysql_num_rows($SQL_Result) ==1){
                mysql_query('UPDATE `gn4flottenbewegungen` SET flottennr="1" WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel1_gala.'" and verteidiger_planet="'.$ziel1_planet.'";')or die(mysql_errno()." - ".mysql_error());
                }
                }
                }
            }
                if($scan_ziel2!='Orbit'){
                if($scan_status2==1||$scan_status2==2){
                $SQL_Result = mysql_query('SELECT gala,planet FROM `gn4gnuser` WHERE name="'.$scan_ziel2.'";') or die(mysql_errno()." - ".mysql_error());
                $SQL_Num=mysql_num_rows($SQL_Result);
                if($SQL_Num==1){
                $ziel2_gala = mysql_result($SQL_Result,0,'gala');
                $ziel2_planet=mysql_result($SQL_Result,0,'planet');
                $SQL_Result = mysql_query('SELECT eta FROM `gn4flottenbewegungen` WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel2_gala.'" and verteidiger_planet="'.$ziel2_planet.'";') or die(mysql_errno()." - ".mysql_error());
                if(mysql_num_rows($SQL_Result) ==1){
                mysql_query('UPDATE `gn4flottenbewegungen` SET flottennr="2" WHERE angreifer_galaxie="'.$scan_rg.'" and angreifer_planet="'.$scan_rp.'" and verteidiger_galaxie="'.$ziel2_gala.'" and verteidiger_planet="'.$ziel2_planet.'";')or die(mysql_errno()." - ".mysql_error());
                }
                }
                }
            }
            }
            if ($scan_typ == 'Geschützscan') {
                $scan_type = 3;
                $daten = parseLine( $zeilen[3]);            // Leichtes Orbitalgeschütz 400
                $scan_glo = trim($daten[4]);
                $daten = parseLine( $zeilen[4]);            // Leichtes Raumgeschütz 0
                $scan_glr = trim($daten[4]);
                $daten = parseLine( $zeilen[5]);            // Mittleres Raumgeschütz 0
                $scan_gmr = trim($daten[4]);
                $daten = parseLine( $zeilen[6]);            // Schweres Raumgeschütz 0
                $scan_gsr = trim($daten[4]);
                $daten = parseLine( $zeilen[7]);            // Abfangjäger 1000
                $scan_ga = trim($daten[3]);
                addgnuser($scan_rg, $scan_rp, $scan_rn);
                $SQL_Result = mysql_query('DELETE FROM `gn4scans` WHERE rg="'.$scan_rg.'" and rp="'.$scan_rp.'" AND type="'.$scan_type.'";', $SQL_DBConn);
                $insert_names = 'glo, glr, gmr, gsr, ga';
                $insert_values = '"'.$scan_glo.'", "'.$scan_glr.'", "'.$scan_gmr.'", "'.$scan_gsr.'", "'.$scan_ga.'"';
                $SQL_Result = mysql_query('INSERT INTO `gn4scans` (type, zeit, g, p, rg, rp, gen, '.$insert_names.') VALUES ("'.$scan_type.'", "'.date("H").':'.date("i").' '.date("d").'.'.date("m").'.'.date("Y").'", "'.$Benutzer['galaxie'].'", "'.$Benutzer['planet'].'", "'.$scan_rg.'", "'.$scan_rp.'", "'.$scan_gen.'", '.$insert_values.');', $SQL_DBConn) or die('ERROR 2 Konnte Datensatz nicht schreiben');
            }
            CountScans($Benutzer['id']);
            $modul = 'scans';
            $txtScanGalaxie = $scan_rg;
            $txtScanPlanet = $scan_rp;
        } else $error_code = 6;

    // Abrafax:
    if (strlen($tmpGala)>0)
    {
        // Flottenbewegungen wurden gescannt,
        // Anzeige auf Taktikbildschirm umleiten
        $modul = 'taktikbildschirm';
    }
    }

?>
