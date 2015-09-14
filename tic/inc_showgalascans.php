<?php

function getscannames( $scantype ) {
    $sn = explode( ' ', $scantype );
    $res = '';
    $snarr = array( 'Sektor', 'Einheiten', 'Mili', 'Gesch&uuml;tz' );
    for ( $j=0; $j< count( $sn )-1; $j++ ) {
        $idx = $sn[$j];
        if ( $j < count( $sn )-2 )
            $res .= $snarr[ $idx ].' / ';
        else
            $res .= $snarr[ $idx ];
    }
    return $res;
}
if(isset($_POST['xgala'])) $_GET['xgala'] = $_POST['xgala'];
if(isset($_POST['xplanet'])) $_GET['xplanet'] = $_POST['xplanet'];

    if ( !isset($_POST['displaytype']) )
        $_POST['displaytype'] = 0;   // einzelner planet = 0 / gala= 1 / query = 2

    $sql='';
    $error_occured=0;
    switch($_POST['displaytype']) {
        case 1: // einzelner planet = 0 / gala= 1 / query = 2
            if ( !isset( $_GET['xgala'] ))
                $error_occured = 3;
            else
                $sql  = 'select * from `gn4scans` where rg='.intval($_GET['xgala']).' order by rp, type';
            break;
        case 2: // einzelner planet = 0 / gala= 1 / query = 2
            if ( !isset( $_POST['qvar']) )
                $error_occured = 4;
            else if ( !isset( $_POST['qoperator'] ) )
                $error_occured = 5;
            else if ( !isset( $_POST['qval'] ) )
                $error_occured = 6;
            else {
                $tmparr = explode( ',', $_POST['qvar']);
                if ( strcmp( $qoperator, ">" ) == 0 )
                    $sortdir='';
                else
                    $sortdir='DESC';
                $sql='select * from `gn4scans` where '.$tmparr[0].' '.$_POST['qoperator'].' "'.$_POST['qval'].'" and type='.$tmparr[1].' order by '.$tmparr[0].' '.$sortdir.',rg,rp  limit 10';
            }
            break;
        case 0: // einzelner planet = 0 / gala= 1 / query = 2
        default:
            if ( !isset( $_GET['xgala'] ) )
                $error_occured = 1;
            else if ( !isset( $_GET['xplanet'] ))
                $error_occured = 2;
            else
                $sql='select * from `gn4scans` where rg='.intval($_GET['xgala']).' and rp='.intval($_GET['xplanet']).' order by type';
            break;
    }
    if ( $error_occured > 0){
        echo '<b><font color="#800000">Internal Error ('.$error_occured.')!!! - aborted!</font></b> <br>';
        return;
    }
?>
<center>
  <table border="0" cellspacing="3" cellpadding="0">
    <tr>
      <td><font size="3">Scans, Scans, Scans - Scanausgaben</font></td>
    </tr>
  </table>
  <br>
  <table border="0" cellspacing="3" cellpadding="0" width="100%" bgcolor="#CCCCCC">
    <tr>
      <td bgcolor="#333333">
        <div align="center"><font color="#FFFFFF"><b>Scanausgaben</b></font></div>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="3" cellpadding="0">
          <tr bgcolor="#B3B1C2">
            <td valign="top" width="20%">
              <form name="form1" method="post" action="./main.php">
              <INPUT TYPE="hidden" NAME="modul" VALUE="showgalascans">
              <INPUT TYPE="hidden" NAME="displaytype" VALUE="0">
              <table width="100%" border="0" cellspacing="3" cellpadding="0">
                <tr>
                  <td bgcolor="#333333" width="25%"><b><font color="#FFFFFF">Spezieller
                    Planet</font></b></td>
                </tr>
                <tr>
                  <td width="25%"><font>Einzelner Planet</font></td>
                </tr>
                <tr>
                  <td width="25%"><font>Gala:Planet
                    <input type="text" name="xgala" size="4" value=<?php echo $_GET['xgala'];?>>
                    :
                    <input type="text" name="xplanet" size="2" value=<?php echo $_GET['xplanet'];?>>
                    </font></td>
                </tr>
                <tr>
                  <td width="25%"><font>&nbsp;</font></td>
                </tr>
                <tr>
                  <td width="25%">
                    <div align="right"><font>
                      <input type="submit" name="Abschicken3" value="Anzeigen">
                      </font> </div>
                  </td>
                </tr>
              </table>
              </form>
            </td>
            <td valign="top" width="20%">
              <form name="form2" method="post" action="./main.php">
              <INPUT TYPE="hidden" NAME="modul" VALUE="showgalascans">
              <INPUT TYPE="hidden" NAME="displaytype" VALUE="1">
              <table width="100%" border="0" cellspacing="3" cellpadding="0">
              <table width="100%" border="0" cellspacing="3" cellpadding="0">
                <tr>
                  <td bgcolor="#333333" width="25%"><b><font color="#FFFFFF">Galaxie
                    anzeigen </font></b></td>
                </tr>
                <tr>
                  <td width="25%"><font>Nachbar-Galas: </font></td>
                </tr>
                <tr>
                  <td width="25%"> <font> Galaxie:
                    <?php
                    echo '<input type="button" name="Verweis" value="&lt;&lt;"';
                    echo 'onClick="self.location.href=';
                    echo "'./main.php?modul=showgalascans&action=findgala&displaytype=1&direction=previous&xgala=".$_GET['xgala']."'";
                    echo '">';
                    ?>
                    <input type="text" name="xgala" size="4" value=<?php echo $_GET['xgala'];?> >
                    <?php
                    echo '<input type="button" name="Verweis" value="&gt;&gt;"';
                    echo 'onClick="self.location.href=';
                    echo "'./main.php?modul=showgalascans&action=findgala&displaytype=1&direction=next&xgala=".$_GET['xgala']."'";
                    echo '">';
                    ?>
                    </font></td>
                </tr>
                <tr>
                  <td width="25%"><font>&nbsp;</font></td>
                </tr>
                <tr>
                  <td width="25%">
                    <div align="right"><font>
                      <input type="submit" name="Abschicken4" value="Anzeigen">
                      </font></div>
                  </td>
                </tr>
              </table>
              </form>
            </td>
            <td valign="top" width="20%">
              <form name="form3" method="post" action="./main.php">
              <INPUT TYPE="hidden" NAME="modul" VALUE="showgalascans">
              <INPUT TYPE="hidden" NAME="displaytype" VALUE="2">
              <table width="100%" border="0" cellspacing="3" cellpadding="0">
                <tr>
                  <td bgcolor="#333333"><b><font color="#FFFFFF">Suche
                    Planeten </font></b></td>
                </tr>
                <tr>
                  <td><font>
                    <select name="qvar" size="1">
                      <?php
                        $s1opt='';
                        $s2opt='';
                        $s3opt='';
                        $s4opt='';
                        $s5opt='';
                        if ( strcmp( $_POST['qvar'], "pts,0" ) == 0 )
                            $s1opt=' selected';
                        elseif ( strcmp( $_POST['qvar'], "sfsu,1" ) == 0 )
                            $s2opt=' selected';
                        elseif ( strcmp( $_POST['qvar'], "ga,3" ) == 0 )
                            $s3opt=' selected';
                        elseif ( strcmp( $_POST['qvar'], "me,0" ) == 0 )
                            $s4opt=' selected';
                        else
                            $s5opt=' selected';
                      ?>
                      <option value="pts,0"<?php echo $s1opt; ?>>Punkte</option>
                      <option value="sfsu,1"<?php echo $s2opt; ?>>Schutzies</option>
                      <option value="ga,3"<?php echo $s3opt; ?>>Abfangj&auml;ger</option>
                      <option value="me,0"<?php echo $s4opt; ?>>Metall-Exen</option>
                      <option value="ke,0"<?php echo $s5opt; ?>>Kristall-Exen</option>
                    </select>
                    <select name="qoperator" size="1">
                      <?php
                        $gtopt='';
                        $ltopt='';
                        if ( strcmp( $_POST['qoperator'], "<" ) == 0 )
                            $ltopt=' selected';
                        else
                            $gtopt=' selected';
                      ?>
                      <option value="&gt;"<?php echo $gtopt; ?> >gr&ouml;&szlig;er</option>
                      <option value="&lt;"<?php echo $ltopt; ?>>kleiner</option>
                    </select>
                    </font></td>
                </tr>
                <tr>
                  <td><font>Kriterium:
                    <input type="text" name="qval" value=<?php echo '"'.$_POST['qval'].'"'; ?>>
                    </font></td>
                </tr>
                <tr>
                  <td width="25%"><font>&nbsp;</font></td>
                </tr>
                <tr>
                  <td>
                    <div align="right"><font size="-1">(&lt;=10 Treffer)
                      <input type="submit" name="Abschicken2" value="Anzeigen">
                      </font></div>
                  </td>
                </tr>
              </table>
              </form>
            </td>
            <td valign="top" bgcolor="#CCCCCC" width="30%"> <i><b><font>Was is was?
              </font></b></i>
              <table border="0" cellspacing="3" cellpadding="0" width="100%">
                <tr>
                  <td><font>
                  <b>Spezieller Planet:</b><br>
                    Die &quot;klassische&quot; Anzeige: alle Scans eines Planeten</font>
                  </td>
                </tr>
                <tr>
                  <td><font>
                  <b>Galaxie anzeigen:</b><br>
                  Anzeige alle Scans einer Galaxie und ihrer Planeten </font></td>
                </tr>
                <tr>
                  <td><font>
                    <b>Suche Planeten:</b><br>
                    Ausgabe von max 10 Planeten nach Kriterium</font>
                    </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <?php
  echo '<FORM ACTION="./main.php" METHOD="POST">';
  echo '<INPUT TYPE="hidden" NAME="modul" VALUE="scans">';
  echo '<INPUT TYPE="hidden" NAME="txtScanGalaxie" VALUE="'.$_GET['xgala'].'">';
  echo '<INPUT TYPE="hidden" NAME="txtScanPlanet" VALUE="'.$_GET['xplanet'].'">';
  echo '<input type="submit" value="Zur Datenerfassung">';
  echo '</form>';
  ?>
  <br>
  <br>

<?php
//    echo "sql=".$sql;
    $SQL_Result = mysql_query( $sql, $SQL_DBConn );
    $count =  mysql_num_rows($SQL_Result);
    if ( $count == 0 ) {
        echo '<font color="#800000" size="-1"><b>Sorry - Keine Scans vorhanden.</b></font>';
        return;
    } else {
        // all
        // sektor
        $pts = 0; $me  = 0; $ke  = 0; $sgen=0; $szeit='-'; $s=0; $d=0; $a=0;
        // unit init
        $ja   = 0; $bo   = 0; $fr   = 0; $ze   = 0; $kr   = 0; $sl   = 0; $tr   = 0; $ka   = 0; $ca   = 0; $ugen=0; $uzeit='-';
        // mili init
        $ja0  = 0; $bo0  = 0; $fr0  = 0; $ze0  = 0; $kr0  = 0; $sl0  = 0; $tr0  = 0; $ka0  = 0; $ca0  = 0; $mgen=0; $mzeit='-';
        $ja1  = 0; $bo1  = 0; $fr1  = 0; $ze1  = 0; $kr1  = 0; $sl1  = 0; $tr1  = 0; $ka1  = 0; $ca1  = 0;
        $ja2  = 0; $bo2  = 0; $fr2  = 0; $ze2  = 0; $kr2  = 0; $sl2  = 0; $tr2  = 0; $ka2  = 0; $ca2  = 0;
        // gscan
        $lo = 0; $ro = 0; $mr = 0; $sr = 0; $aj = 0; $ggen=0; $gzeit='-';
        $rscans = '';

        for ( $i=0; $i<$count; $i++ ) {
            if ( $i<($count-1) )
                $rpnext = mysql_result($SQL_Result, $i+1, 'rp' );
            else
                $rpnext = 999;

            $type = mysql_result($SQL_Result, $i, 'type' );
            $rp = mysql_result($SQL_Result, $i, 'rp' );
            $rg = mysql_result($SQL_Result, $i, 'rg' );
            $rname = gnuser($rg, $rp);
            $rscans .= sprintf( "%d ", $type );
//echo '<br>type='.$type.' - ';
            switch( $type ) {   // scan-type
                case 0: // sektor
                    $szeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $sgen   = mysql_result($SQL_Result, $i, 'gen' );
                    $pts    = mysql_result($SQL_Result, $i, 'pts' );
                    $me     = mysql_result($SQL_Result, $i, 'me' );
                    $ke     = mysql_result($SQL_Result, $i, 'ke' );
                    $s      = mysql_result($SQL_Result, $i, 's' );
                    $d      = mysql_result($SQL_Result, $i, 'd' );
                    $a      = mysql_result($SQL_Result, $i, 'a' );
                    break;
                case 1: // unit
                    $uzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $ugen   = mysql_result($SQL_Result, $i, 'gen' );
                    $ja     = mysql_result($SQL_Result, $i, 'sfj' );
                    $bo     = mysql_result($SQL_Result, $i, 'sfb' );
                    $fr     = mysql_result($SQL_Result, $i, 'sff' );
                    $ze     = mysql_result($SQL_Result, $i, 'sfz' );
                    $kr     = mysql_result($SQL_Result, $i, 'sfkr' );
                    $sl     = mysql_result($SQL_Result, $i, 'sfsa' );
                    $tr     = mysql_result($SQL_Result, $i, 'sft' );
                    $ka     = mysql_result($SQL_Result, $i, 'sfka' );
                    $ca     = mysql_result($SQL_Result, $i, 'sfsu' );
                    break;
                case 2: // mili-scan
                    $mzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $mgen   = mysql_result($SQL_Result, $i, 'gen' );
                    $ja0    = mysql_result($SQL_Result, $i, 'sf0j' );
                    $bo0    = mysql_result($SQL_Result, $i, 'sf0b' );
                    $fr0    = mysql_result($SQL_Result, $i, 'sf0f' );
                    $ze0    = mysql_result($SQL_Result, $i, 'sf0z' );
                    $kr0    = mysql_result($SQL_Result, $i, 'sf0kr' );
                    $sl0    = mysql_result($SQL_Result, $i, 'sf0sa' );
                    $tr0    = mysql_result($SQL_Result, $i, 'sf0t' );
                    $ka0    = mysql_result($SQL_Result, $i, 'sf0ka' );
                    $ca0    = mysql_result($SQL_Result, $i, 'sf0su' );
                    $ja1    = mysql_result($SQL_Result, $i, 'sf1j' );
                    $bo1    = mysql_result($SQL_Result, $i, 'sf1b' );
                    $fr1    = mysql_result($SQL_Result, $i, 'sf1f' );
                    $ze1    = mysql_result($SQL_Result, $i, 'sf1z' );
                    $kr1    = mysql_result($SQL_Result, $i, 'sf1kr' );
                    $sl1    = mysql_result($SQL_Result, $i, 'sf1sa' );
                    $tr1    = mysql_result($SQL_Result, $i, 'sf1t' );
                    $ka1    = mysql_result($SQL_Result, $i, 'sf1ka' );
                    $ca1    = mysql_result($SQL_Result, $i, 'sf1su' );
                    $ja2    = mysql_result($SQL_Result, $i, 'sf2j' );
                    $bo2    = mysql_result($SQL_Result, $i, 'sf2b' );
                    $fr2    = mysql_result($SQL_Result, $i, 'sf2f' );
                    $ze2    = mysql_result($SQL_Result, $i, 'sf2z' );
                    $kr2    = mysql_result($SQL_Result, $i, 'sf2kr' );
                    $sl2    = mysql_result($SQL_Result, $i, 'sf2sa' );
                    $tr2    = mysql_result($SQL_Result, $i, 'sf2t' );
                    $ka2    = mysql_result($SQL_Result, $i, 'sf2ka' );
                    $ca2    = mysql_result($SQL_Result, $i, 'sf2su' );

                    break;
                case 3: // geschtz
                    $gzeit  = mysql_result($SQL_Result, $i, 'zeit' );
                    $ggen   = mysql_result($SQL_Result, $i, 'gen' );
                    $lo     = mysql_result($SQL_Result, $i, 'glo' );
                    $lr     = mysql_result($SQL_Result, $i, 'glr' );
                    $mr     = mysql_result($SQL_Result, $i, 'gmr' );
                    $sr     = mysql_result($SQL_Result, $i, 'gsr' );
                    $aj     = mysql_result($SQL_Result, $i, 'ga' );
                    break;
                default:
                    echo '????huh?!??? - Ohooooh';
                    break;
            }


// echo '('.$rpnext.' <>'. $rp.')';
        if ( $rpnext <> $rp ) {
?>
  <table width="100%" border="0" cellspacing="3" cellpadding="0" bgcolor="#666666">
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="3" cellpadding="0" bgcolor="#FFFFFF">
          <tr>
            <td bgcolor="#333333"><b><font color="#FFFFFF" size="-1"><?php echo $rg.':'.$rp.' - '.$rname.' ('.getscannames($rscans).')'; ?>
            </font></b></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC">
              <table width="100%" border="0" cellspacing="3" cellpadding="0">
                <tr bgcolor="#CCCCCC">
                  <td><b><font>Punkte</font></b></td>
                  <td><b><font>MetExen</font></b></td>
                  <td><b><font>KrisExen</font></b></td>
                  <td><b><font>Schiffe</font></b></td>
                  <td><b><font>Deffensiv</font></b></td>
                  <td><b><font>Astros</font></b></td>
                  <td colspan="4"><font>&nbsp;</font></td>
                  <td><b><font>Genauigkeit</font></b></td>
                  <td><b><font>Datum</font></b></td>
                </tr>
                <tr bgcolor="#eeeeee">
                  <td><font><?php echo number_format($pts, 0, ',', '.'); ?></font></td>
                  <td><font><?php echo $me; ?></font></td>
                  <td><font><?php echo $ke; ?></font></td>
                  <td><font><?php echo $s; ?></font></td>
                  <td><font><?php echo $d; ?></font></td>
                  <td><font><?php echo $a; ?></font></td>
                  <?php
                        $sektor =        'Ab hier Kopieren: 00,10Sektorscan (01,10 '.$sgen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)<BR>';
                        $sektor = $sektor.'00,01Punkte: 07,01'.number_format($pts, 0, ',', '.').' 00,01Astros: 07,01'.$a.'<BR>';
                        $sektor = $sektor.'00,01Schiffe: 07,01'.$s.' 00,01Geschtze: 07,01'.$d.'<BR>';
                        $sektor = $sektor.'00,01Metall-Exen: 07,01'.$me.' 00,01Kristall-Exen: 07,01'.$ke.'<BR>';
                        $sektor = $sektor.'00,01Datum: 07,01'.$szeit.'';
                  ?>
                  <td><font><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$sektor.'\', STICKY, CAPTION,\'Sektor\', CENTER);" onmouseout="nd();">Sektor Mirc</a>';?></font></td>
                  <td bgcolor="#CCCCCC" colspan="3"><font color="#CCCCCC" size="-1">&nbsp;</font></td>
                  <td><font><?php echo $sgen; ?></font></td>
                  <td><font><?php echo $szeit; ?></font></td>
                </tr>
                <tr bgcolor="#CCCCCC">
                  <td><b><font>LO</font></b></td>
                  <td><b><font>LR</font></b></td>
                  <td><b><font>MR</font></b></td>
                  <td><b><font>SR</font></b></td>
                  <td><b><font>AJ</font></b></td>
                  <td colspan="5"><b></b><font>&nbsp;</font></td>
                  <td><b><font>Genauigkeit</font></b></td>
                  <td><b><font>Datum</font></b></td>
                </tr>
                <tr bgcolor="#eeeeee">
                  <td><font><?php echo $lo; ?></font></td>
                  <td><font><?php echo $lr; ?></font></td>
                  <td><font><?php echo $mr; ?></font></td>
                  <td><font><?php echo $sr; ?></font></td>
                  <td><font><?php echo $aj; ?></font></td>
                  <?php
                        $gscan = 	  'Ab hier Kopieren: 00,10Geschtzscan (01,10 '.$ggen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)<Br>';
						$gscan = $gscan.'00,01Rubium: 07,01'.$lo.' 00,01Pulsar: 07,01'.$lr.' 00,01Coon: 07,01'.$mr.'<BR>';
						$gscan = $gscan.'00,01Centurion: 07,01'.$sr.' 00,01Horus: 07,01'.$aj.'<BR>';
						$gscan = $gscan.'00,01Datum: 07,01'.$gzeit.'';
                  ?>
                  <td><font><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$gscan.'\', STICKY, CAPTION,\'Gscan\', CENTER);" onmouseout="nd();">Gscan Mirc</a>';?></font></td>
                  <?php
						$MiliH = 	  'Ab hier Kopieren: 00,10Milit�scan (01,10 '.$mgen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)<BR>';
						$Orbit = $text.'00,1Orbit: 07,01'.$ja0.' 00,1Leo 07,01'.$bo0.' 00,1Aquilae 07,01'.$fr0.' 00,1Fornax 07,01'.$ze0.' 00,1Draco 07,01'.$kr0.' 00,1Goron 07,01'.$sl0.' 00,1Pentalin 07,01'.$tr0.' 00,1Zenit 07,01'.$ka0.' 00,1Cleptor 07,01'.$ca0.' 00,1Cancri <BR>';
						$Flotte1 = $text.'00,01Flotte1: 07,01'.$ja1.' 00,01Leo 07,01'.$bo1.' 00,01Aquilae 07,01'.$fr1.' 00,01Fornax 07,01'.$ze1.' 00,01Draco 07,01'.$kr1.' 00,01Goron 07,01'.$sl1.' 00,01Pentalin 07,01'.$tr1.' 00,01Zenit 07,01'.$ka1.' 00,01Cleptor 07,01'.$ca1.' 00,01Cancri <BR>';
						$Flotte2 = $text.'00,01Flotte2: 07,01'.$ja2.' 00,01Leo 07,01'.$bo2.' 00,01Aquilae 07,01'.$fr2.' 00,01Fornax 07,01'.$ze2.' 00,01Draco 07,01'.$kr2.' 00,01Goron 07,01'.$sl2.' 00,01Pentalin 07,01'.$tr2.' 00,01Zenit 07,01'.$ka2.' 00,01Cleptor 07,01'.$ca2.' 00,01Cancri <BR>';
						$MiliF = $text.'00,01Datum: 07,01'.$mzeit.'';
                  ?>
                <td><font><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Orbit.$Flotte1.$Flotte2.$MiliF.'\', STICKY, CAPTION,\'Mili\', CENTER);" onmouseout="nd();">Mili All</a>';?></font></td>
                  <td colspan="3" bgcolor="#CCCCCC">&nbsp;</td>
                  <td><font><?php echo $ggen; ?></font></td>
                  <td><font><?php echo $gzeit; ?></font></td>
                </tr>
                <tr bgcolor="#CCCCCC">
                  <td><b></b></td>
                  <td><b><font>J&auml;ger</font></b></td>
                  <td><b><font>Bomber</font></b></td>
                  <td><b><font>Fregs</font></b></td>
                  <td><b><font>Zerries</font></b></td>
                  <td><b><font>Kreuzer</font></b></td>
                  <td><b><font>Schlachter</font></b></td>
                  <td><b><font>Tr&auml;ger</font></b></td>
                  <td><b><font>Kleps</font></b></td>
                  <td><b><font>Schutzies</font></b></td>
                  <td><b><font>Genauigkeit</font></b></td>
                  <td><b><font>Datum</font></b></td>
                </tr>
                <tr bgcolor="#aaaada">
                  <?php
                        $unit = 	  'Ab hier Kopieren: 00,10Einheitenscan (01,10 '.$ugen.' %00,10 ) '.$rname.' (01,10'.$rg.':'.$rp.'00,10)<BR>';
						$unit = $unit.'00,01Leo: 07,01'.$ja.' 00,01Aquilae: 07,01'.$bo.' 00,01Fronax: 07,01'.$fr.' 00,01Draco: 07,01'.$ze.' 00,01Goron: 07,01'.$kr.'<BR>';
						$unit = $unit.'00,01Pentalin: 07,01'.$sl.' 00,01Zenit: 07,01'.$tr.' 00,01Cleptor: 07,01'.$ka.' 00,01Cancri: 07,01'.$ca.'<BR>';
						$unit = $unit.'00,01Datum: 07,01'.$uzeit.'';
                  ?>
                  <td><font><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$unit.'\', STICKY, CAPTION,\'Unit\', CENTER);" onmouseout="nd();">Summe</a>';?></font></td>
                  <td><font><b><?php echo $ja; ?></b></font></td>
                  <td><font><b><?php echo $bo; ?></b></font></td>
                  <td><font><b><?php echo $fr; ?></b></font></td>
                  <td><font><b><?php echo $ze; ?></b></font></td>
                  <td><font><b><?php echo $kr; ?></b></font></td>
                  <td><font><b><?php echo $sl; ?></b></font></td>
                  <td><font><b><?php echo $tr; ?></b></font></td>
                  <td><font><b><?php echo $ka; ?></b></font></td>
                  <td><font><b><?php echo $ca; ?></b></font></td>
                  <td><font><b><?php echo $ugen; ?></b></font></td>
                  <td><font><b><?php echo $uzeit; ?></b></font></td>
                </tr>
                <tr bgcolor="#eeeeee">
                <td><font><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Orbit.$MiliF.'\', STICKY, CAPTION,\'Orbit\', CENTER);" onmouseout="nd();">Orbit</a>';?></font></td>
                  <td><font><?php echo $ja0; ?></font></td>
                  <td><font><?php echo $bo0; ?></font></td>
                  <td><font><?php echo $fr0; ?></font></td>
                  <td><font><?php echo $ze0; ?></font></td>
                  <td><font><?php echo $kr0; ?></font></td>
                  <td><font><?php echo $sl0; ?></font></td>
                  <td><font><?php echo $tr0; ?></font></td>
                  <td><font><?php echo $ka0; ?></font></td>
                  <td><font><?php echo $ca0; ?></font></td>
                  <td rowspan="3"><font></font><font><?php echo $mgen; ?></font></td>
                  <td rowspan="3"><font></font><font><?php echo $mzeit; ?></font></td>
                </tr>
                <tr bgcolor="#eeeeee">
                <td><font><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Flotte1.$MiliF.'\', STICKY, CAPTION,\'FLotte1\', CENTER);" onmouseout="nd();">Flotte1</a>';?></font></td>
                  <td><font><?php echo $ja1; ?></font></td>
                  <td><font><?php echo $bo1; ?></font></td>
                  <td><font><?php echo $fr1; ?></font></td>
                  <td><font><?php echo $ze1; ?></font></td>
                  <td><font><?php echo $kr1; ?></font></td>
                  <td><font><?php echo $sl1; ?></font></td>
                  <td><font><?php echo $tr1; ?></font></td>
                  <td><font><?php echo $ka1; ?></font></td>
                  <td><font><?php echo $ca1; ?></font></td>
                </tr>
                <tr bgcolor="#eeeeee">
                <td><font><?php echo '<a href="javascript:void(0);" onclick="return overlib(\''.$MiliH.$Flotte2.$MiliF.'\', STICKY, CAPTION,\'Flotte2\', CENTER);" onmouseout="nd();">Flotte2</a>';?></font></td>
                  <td><font><?php echo $ja2; ?></font></td>
                  <td><font><?php echo $bo2; ?></font></td>
                  <td><font><?php echo $fr2; ?></font></td>
                  <td><font><?php echo $ze2; ?></font></td>
                  <td><font><?php echo $kr2; ?></font></td>
                  <td><font><?php echo $sl2; ?></font></td>
                  <td><font><?php echo $tr2; ?></font></td>
                  <td><font><?php echo $ka2; ?></font></td>
                  <td><font><?php echo $ca2; ?></font></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<?php
            // all
            // sektor
            $pts = 0; $me  = 0; $ke  = 0; $sgen=0; $szeit='-'; $s=0; $d=0; $a=0;
            // unit init
            $ja   = 0; $bo   = 0; $fr   = 0; $ze   = 0; $kr   = 0; $sl   = 0; $tr   = 0; $ka   = 0; $ca   = 0; $ugen=0; $uzeit='-';
            // mili init
            $ja0  = 0; $bo0  = 0; $fr0  = 0; $ze0  = 0; $kr0  = 0; $sl0  = 0; $tr0  = 0; $ka0  = 0; $ca0  = 0; $mgen=0; $mzeit='-';
            $ja1  = 0; $bo1  = 0; $fr1  = 0; $ze1  = 0; $kr1  = 0; $sl1  = 0; $tr1  = 0; $ka1  = 0; $ca1  = 0;
            $ja2  = 0; $bo2  = 0; $fr2  = 0; $ze2  = 0; $kr2  = 0; $sl2  = 0; $tr2  = 0; $ka2  = 0; $ca2  = 0;
            // gscan
            $lo = 0; $ro = 0; $mr = 0; $sr = 0; $aj = 0; $ggen=0; $gzeit='-';
            $rscans = '';
            } // end of if (rp != rpnext)
        }   // end of for
    } // end of else
?>
  <p>&nbsp;</p>
</center>
