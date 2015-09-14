<?PHP
    if ( $_POST['galakoord'] == "" || $_POST['planetkoord'] == "" ) {
        echo '<p align="center"><b>Sorry - ohne Koordinaten geht das nicht!</b></p>';
        return;
    }
    $qry = 'pts, s, d, me, ke, a';

    $SQL_Result2 = tic_mysql_query('SELECT '.$qry.' FROM `gn4scans` WHERE rg="'.$_POST['galakoord'].'" AND rp="'.$_POST['planetkoord'].'" AND type="0";', $SQL_DBConn) or die(mysql_errno()." - ".mysql_error());
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

<form name="form1" method="post" action="./main.php?modul=scans">
<input type="hidden" name="action" value="sektor_edit" />
<input type="hidden" name="galakoord" value="<?=$_POST['galakoord']?>" />
<input type="hidden" name="planetkoord" value="<?=$_POST['planetkoord']?>" />
<table align="center">
    <tr>
      <td class="datatablehead" colspan="2">Manuelle Sektorbearbeitung (<?echo $_POST['galakoord'].':'.$_POST['planetkoord']; ?>)</td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Name:</td><td><input type="text" name="trn" value="<?=$scan_rn?>" /></td>
    </tr>
    <tr class="fieldnormallight">
      <td align="left">Punkte:</td>
      <td><input type="text" name="tpts" value="<?=$scan_pts?>" /></td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Schiffe:</td>
      <td><input type="text" name="ts" value="<?=$scan_s?>" /></td>
    </tr>
    <tr class="fieldnormallight">
      <td align="left">Defensiveinheiten:</td>
      <td><input type="text" name="td" value="<?=$scan_d?>" /></td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Metall Exen:</td>
      <td><input type="text" name="tme" value="<?=$scan_me?>" /></td>
    </tr>
    <tr class="fieldnormallight">
      <td align="left">Kristall Exen:</td>
      <td><input type="text" name="tke" value="<?=$scan_ke?>" /></td>
    </tr>
    <tr class="fieldnormaldark">
      <td align="left">Asteroiden:</td>
      <td><input type="text" name="ta" value="<?=$scan_a?>" /></td>
    </tr>
    <tr>
      <td colspan="2" class="datatablefoot"><input type="submit" name="Abschicken" value="&Auml;nderungen speichern"  /></td>
    </tr>
  </table>
</form>

