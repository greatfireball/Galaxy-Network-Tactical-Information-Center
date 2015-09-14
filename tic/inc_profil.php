
<CENTER>
  <TABLE WIDTH=70%>
    <TR>
      <TD BGCOLOR=#333333><font color="#FFFFFF"><B>Spieler Informationen</B></font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="hell">
        <FORM ACTION="./main.php" METHOD="POST">
          <font>
          <INPUT TYPE="hidden" NAME="modul" VALUE="profil">
          <INPUT TYPE="hidden" NAME="action" VALUE="infoaendern">
          </font>
          <TABLE>
            <TR>
              <TD><font>Scantyp:</font></TD>
              <TD> <font>
                <SELECT NAME="lstScanTyp" SIZE=1>
                  <?php
                    foreach ($ScanTyp as $ScanTypNummer => $ScanTypName) {
                            $zusatz = '';
                            if ($ScanTypNummer == $Benutzer['scantyp']) $zusatz = ' SELECTED';
                            echo '<OPTION VALUE="'.$ScanTypNummer.'"'.$zusatz.'>'.$ScanTypName.'</OPTION>';
                    }
                ?>
                </SELECT>
                </font></TD>
            </TR>
            <TR>
              <TD><font>Scanverstärker:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtSVs" MAXLENGTH=10 SIZE=10 VALUE="<?=ZahlZuText($Benutzer['svs'])?>">
                </font></TD>
              <TD><font>Scanblocker:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtSBs" MAXLENGTH=10 SIZE=10 VALUE="<?=ZahlZuText($Benutzer['sbs'])?>">
                </font></TD>
            </TR>
            <TR>
              <TD><font>Punkte:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtPunkte" MAXLENGTH=20 SIZE=10 VALUE="<?=ZahlZuText($Benutzer['punkte'])?>">
                </font></TD>
            </TR>
            <TR>
              <TD><font>Schiffe:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtSchiffe" MAXLENGTH=10 SIZE=10 VALUE="<?=ZahlZuText($Benutzer['schiffe'])?>">
                </font></TD>
              <TD><font>Defensiveinheiten:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtDefensiv" MAXLENGTH=10 SIZE=10 VALUE="<?=ZahlZuText($Benutzer['defensiv'])?>">
                </font></TD>
            </TR>
            <TR>
              <TD><font>Metallextraktoren:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtExen_m" MAXLENGTH=10 SIZE=10 VALUE="<?=ZahlZuText($Benutzer['exen_m'])?>">
                </font></TD>
              <TD><font>Kristallextraktoren:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtExen_k" MAXLENGTH=10 SIZE=10 VALUE="<?=ZahlZuText($Benutzer['exen_k'])?>">
                </font></TD>
            </TR>
            <TR>
              <TD><font><BR>
                </font></TD>
              <TD><font>
                <INPUT TYPE="submit" VALUE="Informationen ändern">
                </font></TD>
            </TR>
          </TABLE>
        </FORM>
        </p>
      </TD>
    </TR>
    <TR>
      <TD><font><BR>
        &nbsp; </font></TD>
    </TR>
    <TR>
      <TD BGCOLOR=#333333><font color="#FFFFFF"><B>Passwort ändern</B></font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="hell">
        <FORM ACTION="./main.php" METHOD="POST">
          <font>
          <INPUT TYPE="hidden" NAME="modul" VALUE="profil">
          <INPUT TYPE="hidden" NAME="action" VALUE="passwortaendern">
          </font>
          <TABLE>
            <TR>
              <TD><font>neues Passwort:</font></TD>
              <TD><font>
                <INPUT TYPE="text" NAME="txtChPasswort" MAXLENGTH=50>
                </font></TD>
            </TR>
            <TR>
              <TD><font><BR>
                </font></TD>
              <TD><font>
                <INPUT TYPE="submit" VALUE="Passwort ändern">
                </font></TD>
            </TR>
          </TABLE>
        </FORM>
        </p>
        </TD>
    </TR>
    <TR>
      <TD>&nbsp;</TD>
    </TR>
		<TR><TD><BR></TD></TR>
		<TR>
			<TD BGCOLOR=#333333><FONT COLOR=#FFFFFF><B>Persönliche Daten</B></FONT></TD>
		</TR>
		<TR>
		<TD>
		<P CLASS="hell">
			<?
				$sql = "SELECT authnick, handy, messangerID, infotext from gn4accounts where id=".$Benutzer["id"].";";
				$SQL_Result = mysql_query($sql, $SQL_DBConn);
				$pdaten = mysql_fetch_array($SQL_Result);
				//echo mysql_error()."<br>".$sql;
			?>
			<FORM ACTION="./main.php" METHOD="POST">
			<INPUT TYPE="hidden" NAME="modul" VALUE="profil">
			<INPUT TYPE="hidden" NAME="action" VALUE="personlich">
			<table>
			<tr><td>Handy-Nummer</td><td><input name="handy" value="<?=$pdaten[1]?>"></td></tr>
			<tr><td>Messanger</td><td><input name="icq" value="<?=$pdaten[2]?>"></td><td>zB.: ICQ : 123 456 678</td></tr>
			<tr><td>Zusatzinfos</td><td><input name="infotext" value="<?=$pdaten[3]?>"></td><td>
      Authnick</td><td><input name="authnick" value="<?=$pdaten[0]?>"></td></tr>
            <TR>
              <TD><font>Zeitformat:</font></TD>
              <TD> <font>
                <SELECT NAME="lstZeitformat" SIZE=1>
                  <?php
                    foreach ($Zeitformat as $ZeitformatNummer => $ZeitformatName) {
                            $zusatz = '';
                            if ($ZeitformatNummer == $Benutzer["zeitformat"]) $zusatz = ' SELECTED';
                            echo '<OPTION VALUE="'.$ZeitformatNummer.'"'.$zusatz.'>'.$ZeitformatName.'</OPTION>';
                    }
                ?>
                </SELECT>
                </font></TD>
            <TD><font>Taktikscreen:</font></TD>
            <TD> <font>
            <?php
                    echo '<select name="ticscreen" size="1">';
                    $freisel='';
                    $sperrsel='';
                    if ( $Benutzer['tcausw'] == 1 ) $freisel=' SELECTED';
                    else $spersel=' SELECTED';

                    echo '<option value="0"'.$spersel.'>Taktikscreen 1</option>';
                    echo '<option value="1"'.$freisel.'>Taktikscreen 2</option>';
                    echo '</select>';
            ?>
            </tr>
            <?php
            $selected='';
            if ( $Benutzer['help'] == 1 ){
                 $selected = 'checked';
                 }

            ?>
            <td width="36%" height="38"><font size="-1">Hilfe
            </font></td>
            <td width="5%" height="38"> <font size="-1">
                <input type="checkbox" name="check" <?php echo $selected; ?> >
                </font></td>
              <td width="59%" height="38"> <font size="-1">
            <TR><TD><BR></TD><TD><INPUT TYPE="submit" VALUE="Persönliche Daten speichern"></TD></TR>
			</table>
			</FORM>
				</P>
			</TD>
		</TR>
    <TR>
      <TD>&nbsp;</TD>
    </TR>
    <TR>
      <TD BGCOLOR=#333333><font color="#FFFFFF"><B>Urlaubs-Modus</B></font></TD>
    </TR>
    <TR>
      <TD>
        <P CLASS="hell">
        <FORM ACTION="./main.php" METHOD="POST">
          <font>
          <INPUT TYPE="hidden" NAME="modul" VALUE="profil">
          <INPUT TYPE="hidden" NAME="action" VALUE="umod">
          <INPUT TYPE="hidden" NAME="UModID" VALUE="<?=$Benutzer['id']?>">
          </font>
          <TABLE>
            <?php
              if ($Benutzer['umod'] == '') {
                  echo '<TR>';
                  echo '  <td><font>Zeitraum des Urlaubs:</td></font><td><font><INPUT TYPE="text" NAME="txtUModZeit" VALUE="'.date("d").'.'.date("m").'.'.date("Y").'-XX.XX.XXXX" MAXLENGTH=21 SIZE=SMALL> (Falls unbekannt einfach so lassen)</td></font>';
                  echo '</TR>';
                  echo '<TR>';
                  echo '  <td><font><BR></td></font><td><font><INPUT TYPE="submit" VALUE="Urlaubs-Modus aktivieren"></td></font>';
                  echo '</TR>';
              } else {
                  echo '<TR>';
                  echo '  <td><font>Zeitraum des Urlaubs:</td></font><td><font><B>'.$Benutzer['umod'].'</B><INPUT TYPE="hidden" NAME="txtUModZeit" VALUE=""></td></font>';
                  echo '</TR>';
                  echo '<TR>';
                  echo '  <td><font><BR></td></font><td><font><INPUT TYPE="submit" VALUE="Urlaubs-Modus deaktivieren"></td></font>';
                  echo '</TR>';
              }
          ?>
          </TABLE>
        </FORM>
      </TD>
    </TR>
  </TABLE>
</CENTER>
