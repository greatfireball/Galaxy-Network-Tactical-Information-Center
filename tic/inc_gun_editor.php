<?PHP


    if ( $_POST['galakoord'] == "" || $_POST['planetkoord'] == "" ) {
        echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht!</b></p>';
        return;
    }
    $qry =        'glo, glr, gmr, gsr, ga';

    $SQL_Result2 = mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="3";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
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

<table width="500" border="0" cellspacing="1" cellpadding="0" align="center">
  <tr>
    <td>
      <div align="center">Manuelle Flottenbearbeitung (<?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>)</div>
    </td>
  </tr>
</table>
<form name="form1" method="post" action="./main.php">
  <table width="500" border="0" cellspacing="1" cellpadding="0" align="center">
    <tr>
      <td>
        <div align="center"><font size="-1">Name
          <input type="text" name="trn" size="40" value="<?=$scan_rn?>">
          </font></div>
      </td>
    </tr>
  </table>
  <table width="500" border="1" cellspacing="1" cellpadding="0" align="center">
    <tr bgcolor="#CCCCCC">
      <td width="70%"><font size="-1">Bezeichnung</font></td>
      <td width="10%">
        <div align="center"><font size="-1">Orbit</font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Leichtes Orbitalgesch&uuml;tz &quot;Rubium&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tglo" size="8" value="<?=$scan_glo?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Leichtes Raumgesch&uuml;tz &quot;Pulsar&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tglr" size="8" value="<?=$scan_glr?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Mittleres Raumgesch&uuml;tz &quot;Coon&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tgmr" size="8" value="<?=$scan_gmr?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Schweres Raumgesch&uuml;tz &quot;Centurion&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tgsr" size="8" value="<?=$scan_gsr?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Abfangj&auml;ger &quot;Horus&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tga" size="8" value="<?=$scan_ga?>">
          </font></div>
      </td>
    </tr>
  </table>
  <table width="500" border="0" cellspacing="1" cellpadding="0" align="center">
    <tr>
      <td>
        <div align="right"><br>
          <INPUT TYPE="hidden" NAME="action" VALUE="gun_edit">
          <INPUT TYPE="hidden" NAME="modul" VALUE="scans">
          <input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>">
          <input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>">
          <input type="hidden" name="txtScanGalaxie" value="<?=$_POST['galakoord']?>">
          <input type="hidden" name="txtScanPlanet" value="<?=$_POST['planetkoord']?>">
          <input type="submit" name="Abschicken" value="&Auml;nderungen speichern">
        </div>
      </td>
    </tr>
  </table>
  </form>

