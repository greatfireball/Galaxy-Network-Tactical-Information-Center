<?PHP


    if ( $_POST['galakoord'] == "" || $_POST['planetkoord'] == "" ) {
        echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht!</b></p>';
        return;
    }
    $qry =        'sf0j, sf0b, sf0f, sf0z, sf0kr, sf0sa, sf0t, sf0ko, sf0ka, sf0su,';
    $qry = $qry . 'sf1j, sf1b, sf1f, sf1z, sf1kr, sf1sa, sf1t, sf1ko, sf1ka, sf1su,';
    $qry = $qry . 'sf2j, sf2b, sf2f, sf2z, sf2kr, sf2sa, sf2t, sf2ko, sf2ka, sf2su';

    $SQL_Result2 = mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="2";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    $SQL_Num = mysql_num_rows($SQL_Result2);
    if ($SQL_Num == 0) {
        // keine scans da

        $scan_rn=gnuser($_POST['galakoord'], $_POST['planetkoord']);

        // Jäger
        $scan_sf0j =  0;
        $scan_sf1j =  0;
        $scan_sf2j =  0;

        // bomber
        $scan_sf0b =  0;
        $scan_sf1b =  0;
        $scan_sf2b =  0;

        // fregs
        $scan_sf0f =  0;
        $scan_sf1f =  0;
        $scan_sf2f =  0;

        // zerries
        $scan_sf0z =  0;
        $scan_sf1z =  0;
        $scan_sf2z =  0;

        // kreuzer
        $scan_sf0kr = 0;
        $scan_sf1kr = 0;
        $scan_sf2kr = 0;

        // schlachter
        $scan_sf0sa = 0;
        $scan_sf1sa = 0;
        $scan_sf2sa = 0;

        // träger
        $scan_sf0t  = 0;
        $scan_sf1t  = 0;
        $scan_sf2t  = 0;

        // Kaper
        $scan_sf0ka = 0;
        $scan_sf1ka = 0;
        $scan_sf2ka = 0;

        // schutzies
        $scan_sf0su = 0;
        $scan_sf1su = 0;
        $scan_sf2su = 0;
    } else {
        // scan ist in db vorhanden
        $scan_rn   =  gnuser($_POST['galakoord'], $_POST['planetkoord']);

        // Jäger
        $scan_sf0j =  mysql_result($SQL_Result2, 0, 'sf0j');
        $scan_sf1j =  mysql_result($SQL_Result2, 0, 'sf1j');
        $scan_sf2j =  mysql_result($SQL_Result2, 0, 'sf2j');

        // bomber
        $scan_sf0b =  mysql_result($SQL_Result2, 0, 'sf0b');
        $scan_sf1b =  mysql_result($SQL_Result2, 0, 'sf1b');
        $scan_sf2b =  mysql_result($SQL_Result2, 0, 'sf2b');

        // fregs
        $scan_sf0f =  mysql_result($SQL_Result2, 0, 'sf0f');
        $scan_sf1f =  mysql_result($SQL_Result2, 0, 'sf1f');
        $scan_sf2f =  mysql_result($SQL_Result2, 0, 'sf2f');

        // zerries
        $scan_sf0z =  mysql_result($SQL_Result2, 0, 'sf0z');
        $scan_sf1z =  mysql_result($SQL_Result2, 0, 'sf1z');
        $scan_sf2z =  mysql_result($SQL_Result2, 0, 'sf2z');

        // kreuzer
        $scan_sf0kr = mysql_result($SQL_Result2, 0, 'sf0kr');
        $scan_sf1kr = mysql_result($SQL_Result2, 0, 'sf1kr');
        $scan_sf2kr = mysql_result($SQL_Result2, 0, 'sf2kr');

        // schlachter
        $scan_sf0sa = mysql_result($SQL_Result2, 0, 'sf0sa');
        $scan_sf1sa = mysql_result($SQL_Result2, 0, 'sf1sa');
        $scan_sf2sa = mysql_result($SQL_Result2, 0, 'sf2sa');

        // träger
        $scan_sf0t  = mysql_result($SQL_Result2, 0, 'sf0t');
        $scan_sf1t  = mysql_result($SQL_Result2, 0, 'sf1t');
        $scan_sf2t  = mysql_result($SQL_Result2, 0, 'sf2t');

        // Kaper
        $scan_sf0ka = mysql_result($SQL_Result2, 0, 'sf0ka');
        $scan_sf1ka = mysql_result($SQL_Result2, 0, 'sf1ka');
        $scan_sf2ka = mysql_result($SQL_Result2, 0, 'sf2ka');

        // schutzies
        $scan_sf0su = mysql_result($SQL_Result2, 0, 'sf0su');
        $scan_sf1su = mysql_result($SQL_Result2, 0, 'sf1su');
        $scan_sf2su = mysql_result($SQL_Result2, 0, 'sf2su');
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
      <td width="10%">
        <div align="center"><font size="-1">Flotte 1</font></div>
      </td>
      <td width="10%">
        <div align="center"><font size="-1">Flotte 2</font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">J&auml;ger &quot;Leo&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0j" size="8" value="<?=$scan_sf0j?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1j" size="8" value="<?=$scan_sf1j?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2j" size="8" value="<?=$scan_sf2j?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Bomber &quot;Aquilae&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0b" size="8" value="<?=$scan_sf0b?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1b" size="8" value="<?=$scan_sf1b?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2b" size="8" value="<?=$scan_sf2b?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Fregatten &quot;Fornax&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0f" size="8" value="<?=$scan_sf0f?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1f" size="8" value="<?=$scan_sf1f?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2f" size="8" value="<?=$scan_sf2f?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Zerst&ouml;rer &quot;Draco&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0z" size="8" value="<?=$scan_sf0z?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1z" size="8" value="<?=$scan_sf1z?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2z" size="8" value="<?=$scan_sf2z?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Kreuzer &quot;Goron&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0kr" size="8" value="<?=$scan_sf0kr?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1kr" size="8" value="<?=$scan_sf1kr?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2kr" size="8" value="<?=$scan_sf2kr?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Schlachtschiffe &quot;Pentalin&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0sa" size="8" value="<?=$scan_sf0sa?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1sa" size="8" value="<?=$scan_sf1sa?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2sa" size="8" value="<?=$scan_sf2sa?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Tr&auml;ger &quot;Zenit&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0t" size="8" value="<?=$scan_sf0t?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1t" size="8" value="<?=$scan_sf1t?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2t" size="8" value="<?=$scan_sf2t?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Kaperschiffe &quot;Kleptor&quot;&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0ka" size="8" value="<?=$scan_sf0ka?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1ka" size="8" value="<?=$scan_sf1ka?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2ka" size="8" value="<?=$scan_sf2ka?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Schutzschiffe &quot;Cancri&quot;</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf0su" size="8" value="<?=$scan_sf0su?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf1su" size="8" value="<?=$scan_sf1su?>">
          </font></div>
      </td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tsf2su" size="8" value="<?=$scan_sf2su?>">
          </font></div>
      </td>
    </tr>
  </table>
  <table width="500" border="0" cellspacing="1" cellpadding="0" align="center">
    <tr>
      <td>
        <div align="right"><br>
          <INPUT TYPE="hidden" NAME="action" VALUE="fleet_edit">
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

