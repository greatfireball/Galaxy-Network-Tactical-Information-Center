<center>
    <FORM ACTION="./main.php" METHOD="POST">
      <font size="-1">
      <INPUT TYPE="hidden" NAME="modul" VALUE="userman">
      <INPUT TYPE="hidden" NAME="action" VALUE="accounterstellen">
      </font>
      <P CLASS="hell">
      <TABLE>
        <TR>
          <TD colspan=2 bgcolor="#333333"><font color="#ffffff" size="-1"><b>Neuen Benutzer anlegen</b>
          </font>
          </td>
        </TR>
        <TR>
          <TD><font size="-1">Name:</font></TD>
          <TD><font size="-1">
            <INPUT TYPE="text" NAME="txtAccName" MAXLENGTH=50>
            </font></TD>
        </TR>
        <TR>
          <TD><font size="-1">Koordinaten:</font></TD>
          <TD> <font size="-1">
            <?php
                if ($Benutzer['rang'] != $Rang_GC)
                    echo '<INPUT TYPE="text" NAME="txtAccGalaxie" MAXLENGTH=4 SIZE=4>';
                else
                    echo '<B>'.$Benutzer['galaxie'].'</B>';
            ?>
            <B>:</B>
            <INPUT TYPE="text" NAME="txtAccPlanet" MAXLENGTH=2 SIZE=2>
            </font></TD>
        </TR>
        <TR>
          <TD><font size="-1">Passwort:</font></TD>
          <TD><font size="-1">
            <INPUT TYPE="text" NAME="txtAccPasswort" MAXLENGTH=50>
            </font></TD>
        </TR>
        <TR>
          <TD>
           <font size="-1">Allianz:</font>
          </TD>
           <TD>
            <?php

                if ($Benutzer['rang'] >= $Rang_Techniker) {
                    echo '<SELECT NAME="lstAllianz" SIZE="1">';
                    foreach ($AllianzName as $AllianzNummer => $AllianzNummerName) {
                        $zusatz = '';
                        /*
                        if ($AllianzNummer == $Benutzer['allianz'])
                            $zusatz = ' SELECTED';
                        */
                        echo '<OPTION VALUE="'.$AllianzNummer.'"'.$zusatz.'>'.$AllianzTag[$AllianzNummer].' '.$AllianzNummerName.'</OPTION>';
                    }
                    echo '</SELECT>';
                } else {
                    echo '<B><font size="-1">'.$AllianzTag[$Benutzer['allianz']].' '.$AllianzName[$Benutzer['allianz']].'</font></B>';
                }
            ?>
            </TD>
        </TR>
        <TR>
          <TD><font size="-1">Rang:</font></TD>
          <TD> <font size="-1">
            <SELECT NAME="lstRang" SIZE="1">
              <?php
                  foreach ($RangName as $RangNummer => $RangNummerName) {
                      if ($RangNummer <= $Benutzer['rang']) echo '<OPTION VALUE="'.$RangNummer.'">'.($RangNummer + 1).'. '.$RangNummerName.'</OPTION>';
                  }
              ?>
            </SELECT>
            </font></TD>
            </TR>
            <TR>
            <TD><font size="-1"><BR>
                </font></TD>
            <TD><font size="-1">
                <INPUT TYPE="submit" VALUE="Erstellen">
                </font></TD>
            </TR>
        </TABLE>
        <font size="-1"></P> </font>
    </FORM>

<?php

    echo '<table bgcolor="#333333"><tr><td>';

    echo '<table width="100%"><tr><td bgcolor="#eeeeee">';
    echo '<font size="-1"><b>Benutzer Verwaltung</b></font>';
    echo '</td></tr></table>';

    $sql = "SELECT gn4allianzen.id, gn4allianzen.tag, gn4vars.value as meta FROM gn4allianzen LEFT JOIN gn4vars USING (ticid) WHERE gn4vars.name = 'ticeb' ORDER BY meta, gn4allianzen.tag;";

    $SQL_result = tic_mysql_query( $sql ) or print tic_mysql_error();
    $allianzahl = mysql_num_rows( $SQL_result );
    echo '<table cellspacing="3">';
    if ( $allianzahl > 0 ) {
        for ($n = 0; $n < $allianzahl; $n++) {
            $allid = mysql_result( $SQL_result, $n, 'id');
            echo '<tr>';
            echo '<td bgcolor="#CCCCCC"><font size=-1>'.mysql_result( $SQL_result, $n, 'meta').'</font></td>';
            echo '<td bgcolor="#CCCCCC"><font size=-1>'.mysql_result( $SQL_result, $n, 'tag').'</font></td>';
            $sql2 = "SELECT DISTINCT(galaxie) FROM gn4accounts WHERE allianz='".$allid."' ORDER BY galaxie DESC";
            $SQL_result2 = mysql_query($sql2, $SQL_DBConn);
            $galanzahl = mysql_num_rows( $SQL_result2);
            $galanum = mysql_num_rows( $SQL_result2);
            if ( $galanzahl != '' ) {
                for ($p = 0; $p < $galanzahl; $p++) {
                    $SQL_result2 = mysql_query($sql2, $SQL_DBConn);
                    for ($i = 0;$i < $galanum; $i++) {
                          $gala = mysql_fetch_array($SQL_result2, MYSQL_NUM);
                    }
                    $galanum = $galanum - 1;
                    echo '<td bgcolor="#CCCCCC"><font size=-1>';
                    echo '<a href="./main.php?modul=userman&selgala='.$gala[0].'">['.$gala[0].']</a>';
                    echo '</font></td>';
                }
            }

            echo '</tr>';
        }
    }

    echo '</table><br>';

    if ( isset( $_GET['selgala'] ) ){

        echo '<table cellspacing="3">';
        echo '<tr><td bgcolor="#999999">';
        echo '<font size="-1"><b>Galaxie '.$_GET['selgala'].'</b></font>';
        echo '</td></tr>';
        echo '<tr><td bgcolor="#999999">';
        echo '<table>';
        echo '<tr>';
        echo '<td bgcolor="#cccccc"><font size="-1">';
        echo '&nbsp;Planet&nbsp;';
        echo '</font></td>';
        echo '<td bgcolor="#cccccc"><font size="-1">';
        echo '&nbsp;Name&nbsp;';
        echo '</font></td>';
        echo '<td bgcolor="#cccccc"><font size="-1">';
        echo '&nbsp;Rang&nbsp;';
        echo '</font></td>';
        echo '<td bgcolor="#cccccc"><font size="-1">';
        echo '&nbsp;Allianz&nbsp;';
        echo '</font></td>';
        echo '<td bgcolor="#cccccc"><font size="-1">';
        echo '&nbsp;UMode&nbsp;';
        echo '</font></td>';
        echo '<td bgcolor="#cccccc"><font size="-1">';
        echo '&nbsp;LastLogin&nbsp;';
        echo '</font></td>';
        echo '<td bgcolor="#cccccc"><font size="-1">';
		echo '&nbsp;Status&nbsp;';
        echo '</font></td>';
        echo '<td bgcolor="#cccccc" colspan="8"><font size="-1">';
        echo '&nbsp;Bearbeiten&nbsp;';
        echo '</font></td>';
        echo '</tr>';
        $sql = 'select * from gn4accounts where galaxie='.$_GET['selgala'].' order by planet';
        $SQL_result = mysql_query( $sql, $SQL_DBConn);
		$n = 1;
        while($urow = mysql_fetch_assoc($SQL_result)) {
			echo '<tr style="font-size:8pt;">';

			if ($urow['spy'] == 1)
			{
				$status = '<font color="#cc0000">Gesperrt</font>';
			} else {
				if($urow['versuche'] >= 3 && $urow['ip'] != "")
				    $status = '<font color="#cc0000">IP '.$urow['ip'].' gesperrt</font>';
                else
				    $status = '<font color="#00cc00">Entsperrt</font>';
			}


			if ($n) $colour = ' bgcolor="#eeeeee"';
			else $colour = ' bgcolor="#cccccc"';
			$n = !$n;

			echo '<td'.$colour.'>';
			echo $urow['planet'];
			echo '</td>';

			echo '<td'.$colour.'>';
			echo '&nbsp;'.$urow['name'].'&nbsp;';
			echo '</td>';


			echo '<td'.$colour.'>';
			echo '&nbsp;'.$RangName[$urow['rang']].'&nbsp;';
			echo '</td>';

			echo '<td'.$colour.'>';
			echo '&nbsp;'.$AllianzTag[$urow['allianz']].'&nbsp;';
			echo '</td>';

			echo '<td'.$colour.'>';
			echo '&nbsp;'.$urow['umod'].'&nbsp;';
			echo '</td>';

			echo '<td'.$colour.' style="text-align:center;\">';
			echo '&nbsp;'.($urow['lastlogin'] ? strftime("%d.%m.%Y %H:%M", $urow['lastlogin']) : "-nie-").'&nbsp;';
			echo '</td>';

			echo '<td'.$colour.'>';
			echo '&nbsp;'.$status.'&nbsp;';
			echo '</td>';

			// change gala planet pw alliid umode
			echo '<td'.$colour.'><font size="-1">';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&change=umode&uid='.$urow['id'].'">&nbsp;UMode&nbsp;</a></td><td'.$colour.'><font size="-1"><a href="./main.php?modul=useredit&change=pw&uid='.$urow['id'].'">&nbsp;NeuesPW&nbsp;</a>';
				echo '</font></td><td'.$colour.'><font size="-1">';
				echo  '<a href="./main.php?modul=useredit&change=koords&uid='.$urow['id'].'">&nbsp;Koords&nbsp;</a>';
			} else {
				echo '&nbsp;UMode&nbsp;</font></td>
						 <td'.$colour.'><font size="-1">&nbsp;NeuesPW&nbsp;</font></td>
						 <td'.$colour.'><font size="-1">&nbsp;Koords&nbsp';
			}





			echo '</font></td>';

			echo '<td'.$colour.'><font size="-1">';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&change=name&uid='.$urow['id'].'">&nbsp;Name&nbsp;</a>';
			} else {
				echo  '&nbsp;Name&nbsp;';
			}

			echo '</font></td>';

			echo '<td'.$colour.'><font size="-1">';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&change=allianz&uid='.$urow['id'].'">&nbsp;Alli&nbsp;</a>';
			} else {
				echo  '&nbsp;Alli&nbsp;';
			}

			echo '</font></td>';

			echo '<td'.$colour.'><font size="-1">';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo  '<a href="./main.php?modul=useredit&change=rang&uid='.$urow['id'].'">&nbsp;Rang&nbsp;</a>';
			} else {
				echo  '&nbsp;Rang&nbsp;';
			}

			echo '<td'.$colour.'><font size="-1">';
			if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
			if ( $urow['spy'] == 1 || $urow['versuche'] >= 3 && $urow['ip'] != ""){
			echo  '<a href="./main.php?modul=useredit&change=spy&uid='.$urow['id'].'">&nbsp;Entsperren&nbsp;</a>';
			} else {
			echo  '<a href="./main.php?modul=useredit&change=spy&uid='.$urow['id'].'">&nbsp;Sperren&nbsp;</a>';
			}
			} else {
			echo  '&nbsp;Keine Rechte&nbsp;';
			}

			echo '</font></td>';


				echo '<td'.$colour.'><font size="-1">';
        if ( $Benutzer['rang'] >= $Rang_Techniker || ( $Benutzer['rang'] >  $Rang_GC && $Benutzer['allianz'] == $urow['allianz'] ) || ( $Benutzer['rang'] == $Rang_GC && $Benutzer['galaxie'] == $_GET['selgala'] ) ) {
				echo '<A HREF="./main.php?modul=userman&selgala='.$_GET['selgala'].'&action=accloeschen	&id='.$urow['id'].'">&nbsp;L&ouml;schen&nbsp;</a>';
				echo '</font></td>';
        }else {
				echo  '&nbsp;Löschen&nbsp;';
			}




			echo '</tr>';

		}
		echo '</table>';
		echo '</td></tr>';

        echo '</table>';

    }


    echo '</td></tr></table>';

?>
</center>
