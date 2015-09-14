<?PHP
    if (!isset($_POST['txtScanGalaxie']) || !isset($_POST['txtScanPlanet'])) {
        $SQL_Result = mysql_query('SELECT * FROM `gn4scans` WHERE rg<>"0" AND rp<>"0" and ticid="'.$Benutzer['ticid'].'" ORDER BY rg, rp LIMIT 1;', $SQL_DBConn);
        if (mysql_num_rows($SQL_Result) != 0) {
            $_POST['txtScanGalaxie'] = mysql_result($SQL_Result, 0, 'rg');
            $_POST['txtScanPlanet'] = mysql_result($SQL_Result, 0, 'rp');
        } else {
            $_POST['txtScanGalaxie'] = 0;
            $_POST['txtScanPlanet'] = 0;
        }
    }
?>
<CENTER>
  <table border="0" bgcolor="#333333" width="90%">
    <tr><td>
        <table border="0" cellspacing="2" cellpadding="0" width="100%">
          <tr>
            <td valign="top" cbgcolor="#cccccc" width="33%" rowspan="2">
              <table border="0" cellspacing="2" cellpadding="0" align="center" width="100%" height="100%">
                <tr>
                  <td bgcolor="#CCCCCC" align="center"> <font size="-1"><b>Planeten
                    Scan abfragen</b> </font></td>
                </tr>
                <tr>
                  <td bgcolor="#eeeeee" align="center" >
                    <form action="./main.php" method="POST">
                      <br>
                      <font size="-1">
                      <input type="hidden" name="modul" value="showgalascans">
                      <input type="hidden" name="displaymode" value="0">
                      <input type="text" size=4 maxlength=4 name="xgala" value="<?=$_POST['txtScanGalaxie']?>">
                      :
                      <input type="text" size=2 maxlength=2 name="xplanet" value="<?=$_POST['txtScanPlanet']?>">
                      <input type="submit" value="anzeigen" name="submit">
                      </font>
                    </form>
                    <br>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC">
                  <td bgcolor="#CCCCCC" align="center"> <font size="-1"><b>Gala
                    Scans abfragen</b> </font></td>
                </tr>
                <tr>
                  <td bgcolor="#eeeeee" align="center">
                    <form action="./main.php" method="POST">
                      <br>
                      <font size="-1">
                      <input type="hidden" name="modul" value="showgalascans">
                      <input type="hidden" name="displaytype" value="1">
                      <input type="text" size=4 maxlength=4 name="xgala" value="<?=$_POST['txtScanGalaxie']?>">
                      <input type="submit" value="anzeigen" name="submit">
                      <br>
                      </font>
                    </form>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td bgcolor="#CCCCCC" align="center"><font size="-1"><b>Sektor-Eingabe
                    &quot;von Hand&quot;</b></font> </td>
                </tr>
                <tr>
                  <td bgcolor="#eeeeee" align="center">
                    <form name="form1" method="POST" action="./main.php">
                      <br>
                      <font size="-1">
                      <input type="hidden" name="modul" value="sektor_editor">
                      <input type="text" name="galakoord" size="4" maxlength="4" value="<?=$_POST['txtScanGalaxie']?>">
                      :
                      <input type="text" name="planetkoord" size="2" maxlength="2" value="<?=$_POST['txtScanPlanet']?>">
                      <input type="submit" name="manuell" value="manuell erfassen">
                      <br>
                      </font>
                    </form>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td bgcolor="#CCCCCC" align="center"> <font size="-1"><b>Flotten-Eingabe&quot;von
                    Hand&quot;</b> </font></td>
                </tr>
                <tr>
                  <td bgcolor="#eeeeee" align="center" >
                    <form name="form1" method="POST" action="./main.php">
                      <font size="-1"><br>
                      <input type="hidden" name="modul" value="fleet_editor">
                      <input type="text" name="galakoord" size="4" maxlength="4" value="<?=$_POST['txtScanGalaxie']?>">
                      :
                      <input type="text" name="planetkoord" size="2" maxlength="2" value="<?=$_POST['txtScanPlanet']?>">
                      <input type="submit" name="manuell" value="manuell erfassen">
                      <br>
                      </font>
                    </form>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td bgcolor="#CCCCCC" align="center" ><font size="-1"><b>Gesch&uuml;tz-Eingabe&quot;von
                    Hand&quot;</b></font></td>
                </tr>
                <tr>
                  <td bgcolor="#eeeeee" align="center" >
                    <form name="form1" method="POST" action="./main.php">
                      <br>
                      <font size="-1">
                      <input type="hidden" name="modul" value="gun_editor">
                      <input type="text" name="galakoord" size="4" maxlength="4" value="<?=$_POST['txtScanGalaxie']?>">
                      :
                      <input type="text" name="planetkoord" size="2" maxlength="2" value="<?=$_POST['txtScanPlanet']?>">
                      <input type="submit" name="manuell" value="manuell erfassen">
                      <br>
                      </font>
                    </form>
                    <br>
                  </td>
                </tr>
              </table>
            </td>
            <td width="67%" valign="top">
              <table width="100%" cellspacing="2">
                <tr>
                  <td bgcolor=#CCCfCC><b><font size="-1">Daten aus GN einf&uuml;gen
                    (Clipboard)</font></b></td>
                </tr>
                <tr>
                  <td bgcolor="#CCCceC"> <font color="#303030" size="-1"><a href="help/scaneingabe.html" target="tic-help#scan">weiter
                    Infos und Hilfe zum Thema &quot;Scans ...&quot;</a></font></td>
                </tr>
                <tr>
                  <td bgcolor="#eeeeee">
                    <form action="./main.php" method="POST">
                      <font size="-1">
                      <input type="hidden" name="modul2" value="scan">
                      <input type="hidden" name="action" value="addscan">
                      <textarea cols=50 rows=25 name="txtScan"></textarea>
                      <br>
                      <input type="submit" value="Speichern" name="submit2">
                      </font>
                    </form>
                    <br>
                    <br>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td width="67%" valign="top">

          <?php
          /*
          http://localhost/tic/main.php?modul=flotten&selected=wirdangegriffen&id=5&needdeff=5
          echo '<input type="button" name="Verweis2" value="Zur Datenerfassung"onClick="self.location.href=';
          echo "'./main.php?modul=scans&txtScanGalaxie=".$xgala."&txtScanPlanet=".$xplanet."'".'">';
          */
          ?>

            </td>
          </tr>
        </table>
  </td></tr>
  </table>
  <p>&nbsp;</p>
</CENTER>
