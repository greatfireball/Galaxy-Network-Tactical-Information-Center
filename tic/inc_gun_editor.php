<?PHP


    if ( $_POST['galakoord'] == "" || $_POST['planetkoord'] == "" ) {
        echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht!</b></p>';
        return;
    }
    $qry =        'glo, glr, gmr, gsr, ga';

    $SQL_Result2 = tic_mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="3";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    $SQL_Num = mysql_num_rows($SQL_Result2);
    if ($SQL_Num == 0) {
        // keine scans da

        $scan_rn=gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_glo =  0;
        $scan_glr =  0;
        $scan_gmr =  0;
        $scan_gsr =  0;
        $scan_ga  =  0;

    } else {
        // scan ist in db vorhanden
        $scan_rn   =  gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_glo =  mysql_result($SQL_Result2, 0, 'glo');
        $scan_glr =  mysql_result($SQL_Result2, 0, 'glr');
        $scan_gmr =  mysql_result($SQL_Result2, 0, 'gmr');
        $scan_gsr =  mysql_result($SQL_Result2, 0, 'gsr');
        $scan_ga  =  mysql_result($SQL_Result2, 0, 'ga');
    }
?>
<form name="form1" method="post" action="./main.php?modul=scans">
  <input type="hidden" name="action" value="gun_edit" />
  <input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>" />
  <input type="hidden" name="txtScanGalaxie" value="<?=$_POST['galakoord']?>" />
  <input type="hidden" name="txtScanPlanet" value="<?=$_POST['planetkoord']?>" />
  <table align="center">
    <tr>
      <td colspan="2" class="datatablehead">Manuelle Geschützbearbeitung (<?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>)</td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Name:</td><td><input type="text" name="trn" value="<?=$scan_rn?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Leichtes Orbitalgesch&uuml;tz &quot;Rubium&quot;:</td>
      <td><input type="text" name="tglo" value="<?=$scan_glo?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Leichtes Raumgesch&uuml;tz &quot;Pulsar&quot;:</td>
      <td><input type="text" name="tglr" value="<?=$scan_glr?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Mittleres Raumgesch&uuml;tz &quot;Coon&quot;:</td>
      <td><input type="text" name="tgmr" value="<?=$scan_gmr?>" /></td>
    </tr>
    <tr class="fieldnormaldark" align="left">
      <td>Schweres Raumgesch&uuml;tz &quot;Centurion&quot;:</td>
      <td><input type="text" name="tgsr" value="<?=$scan_gsr?>" /></td>
    </tr>
    <tr class="fieldnormallight" align="left">
      <td>Abfangj&auml;ger &quot;Horus&quot;:</td>
      <td><input type="text" name="tga" value="<?=$scan_ga?>" /></td>
    </tr>
    <tr class="datatablefoot">
      <td colspan="2"><input type="submit" name="Abschicken" value="&Auml;nderungen speichern" /></td>
    </tr>
  </table>
  </form>

