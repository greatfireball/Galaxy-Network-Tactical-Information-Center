<?PHP
    if ( $_POST['galakoord'] == "" || $_POST['planetkoord'] == "" ) {
        echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht!</b></p>';
        return;
    }
    $qry = 'pts, s, d, me, ke, a';

    $SQL_Result2 = mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="0";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
    $SQL_Num = mysql_num_rows($SQL_Result2);
    if ($SQL_Num == 0) {
        // keine scans da

        $scan_rn=gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_pts =  0;
        $scan_s   =  0;
        $scan_d   =  0;
        $scan_me  =  0;
        $scan_ke  =  0;
        $scan_a   =  0;

    } else {
        // scan ist in db vorhanden
        $scan_rn   =  gnuser($_POST['galakoord'], $_POST['planetkoord']);

        $scan_pts =  mysql_result($SQL_Result2, 0, 'pts');
        $scan_s   =  mysql_result($SQL_Result2, 0, 's');
        $scan_d   =  mysql_result($SQL_Result2, 0, 'd');
        $scan_me  =  mysql_result($SQL_Result2, 0, 'me');
        $scan_ke  =  mysql_result($SQL_Result2, 0, 'ke');
        $scan_a   =  mysql_result($SQL_Result2, 0, 'a');
    }
?>

<table width="500" border="0" cellspacing="1" cellpadding="0" align="center">
  <tr>
    <td>
      <div align="center">Manuelle Sektorbearbeitung (
        <?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>
        )</div>
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
      <td width="70%"><font size="-1">Punkte</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tpts" size="8" value="<?=$scan_pts?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Schiffe</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="ts" size="8" value="<?=$scan_s?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Defensiveinheiten</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="td" size="8" value="<?=$scan_d?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Metall Exen</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tme" size="8" value="<?=$scan_me?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Kristall Exen</font></td>
      <td width="10%">
        <div align="right"> <font size="-1">
          <input type="text" name="tke" size="8" value="<?=$scan_ke?>">
          </font></div>
      </td>
    </tr>
    <tr>
      <td width="70%"><font size="-1">Asteroiden</font></td>
      <td width="10%">
          <div align="right"> <font size="-1">
          <input type="text" name="ta" size="8" value="<?=$scan_a?>">
          </font></div>
      </td>
    </tr>
  </table>
  <table width="500" border="0" cellspacing="1" cellpadding="0" align="center">
    <tr>
      <td>
        <div align="right"><br>
          <INPUT TYPE="hidden" NAME="action" VALUE="sektor_edit">
          <INPUT TYPE="hidden" NAME="modul" VALUE="scans">
          <input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>">
          <input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>">
          <input type="submit" name="Abschicken" value="&Auml;nderungen speichern">
        </div>
      </td>
    </tr>
  </table>
  </form>

