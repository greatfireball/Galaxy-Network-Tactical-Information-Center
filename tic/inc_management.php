<CENTER>
    <TABLE WIDTH=70%>
        <?php
            if ($Benutzer['rang'] > $Rang_GC) {
                echo '<TR>';
                echo '  <TD><BR></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '  <TD BGCOLOR=#333333><FONT COLOR=#FFFFFF><B><font>Allianzeinstellungen</font></B></FONT></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD>';
                echo '<P CLASS="hell">';
                echo '<FORM ACTION="./main.php" METHOD="POST">';
                echo '<INPUT TYPE="hidden" NAME="modul" VALUE="management">';
                echo '<INPUT TYPE="hidden" NAME="action" VALUE="systemeinstellung">';
                echo '<TABLE>';
                echo '<TR>';
                echo '<TD><font>Allianzcode:</font></TD>';
                echo '<TD>';
                echo '<SELECT NAME="lstCode" SIZE=SMALL>';
                foreach ($AllianzCode as $CodeNummer => $CodeNummerName) {
                    if ($CodeNummer != $AllianzInfo[$Benutzer['allianz']]['code'])
                        echo '<OPTION VALUE="'.$CodeNummer.'">'.$CodeNummerName.'</OPTION>';
                    else
                        echo '<OPTION VALUE="'.$CodeNummer.'" SELECTED>'.$CodeNummerName.'</OPTION>';
                }
                echo '</SELECT>';
                echo '</TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Bündnisse:</font></TD><TD><INPUT TYPE="text" NAME="txtBNDs" VALUE="'.$AllianzInfo[$Benutzer['allianz']]['info_bnds'].'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Offizielle NAPs:</font></TD><TD><INPUT TYPE="text" NAME="txtNAPs" VALUE="'.$AllianzInfo[$Benutzer['allianz']]['info_naps'].'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Inoffizielle NAPs:</font></TD><TD><INPUT TYPE="text" NAME="txtInoffizielleNAPs" VALUE="'.$AllianzInfo[$Benutzer['allianz']]['info_inoffizielle_naps'].'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><font>Kriege:</font></TD><TD><INPUT TYPE="text" NAME="txtKriege" VALUE="'.$AllianzInfo[$Benutzer['allianz']]['info_kriege'].'" SIZE="50"></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD><BR></TD><TD><INPUT TYPE="submit" VALUE="Allianzeinstellungen ändern"></TD>';
                echo '</TR>';
                echo '</TABLE>';
                echo '</FORM>';
                echo '</P>';
                echo '</TD>';
                echo '</TR>';
            }

        if ($Benutzer['rang'] == $Rang_STechniker) {
                echo '<TR>';
                echo '  <TD><BR></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '  <TD BGCOLOR=#333333><FONT COLOR=#FFFFFF><B><font>Tic-Einstellungen</font></B></FONT></TD>';
                echo '</TR>';
                echo '<TR>';
                echo '<TD>';
                echo '<P CLASS="hell">';
                echo '<FORM ACTION="./main.php" METHOD="POST">';
                echo '<INPUT TYPE="hidden" NAME="modul" VALUE="management">';
                echo '<INPUT TYPE="hidden" NAME="action" VALUE="ticeinstellung">';
                echo '<TABLE>';
                echo '<tr><td>Meta Name</td><td><input name="newmeta" value="'.$meta.'"></td></tr>';
                echo '<tr><td>Systemnachricht</td><td><textarea name="newsystemnachricht" rows=5 cols=50>'.$systemnachricht.'</textarea></td></tr>';
                $SQL_Result5=mysql_query('SELECT name, value FROM `gn4vars` WHERE name="botpw" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn) or $error_code = 4;
                $botpw=mysql_result($SQL_Result5,0,"value");
                echo '<tr><td>Bot Pw</td><td><input name="newbotpw" value="'.$botpw.'"></td></tr>';
				echo '<TR>';
                echo '<TD><BR></TD><TD><INPUT TYPE="submit" VALUE="Tic-Einstellungen ändern"></TD>';
                echo '</TR>';
                echo '</TABLE>';
                echo '</FORM>';
                echo '</P>';
                echo '</TD>';
                echo '</TR>';
        }


        ?>

    </TABLE>

    <?php
    if ( $Benutzer['rang'] == $Rang_STechniker ) {
        echo '<br>';
        echo '<FORM ACTION="./main.php" METHOD="POST">';
        echo '<INPUT TYPE="hidden" NAME="modul" VALUE="ally_add">';
        echo '<input type="submit" value="Neue Allianz anlegen">';
        echo '</form>';

        echo '<p>&nbsp;<br>&nbsp;</p>';

        echo '<TABLE>';
        echo '<tr>';
        echo '<td bgcolor="#333333">';
        echo '<TABLE cellspacing="3" >';
        echo '<tr>';
        echo '<td bgcolor="#aaaaaa">';
        echo '&nbsp;Allianzname&nbsp;';
        echo '</td>';
        echo '<td bgcolor="#aaaaaa">';
        echo '&nbsp;Allianz-Tag&nbsp;';
        echo '</td>';
        echo '<td bgcolor="#aaaaaa">';
        echo '&nbsp;GalaList&nbsp;';
        echo '</td>';
        echo '<td bgcolor="#aaaaaa" colspan="2">';
        echo '&nbsp;Bearbeiten&nbsp;';
        echo '</td>';
        echo '</tr>';

        $sql = 'SELECT * from `gn4allianzen` where ticid="'.$Benutzer['ticid'].'" ';
        $SQL_Result = mysql_query( $sql, $SQL_DBConn );
        $n = mysql_num_rows( $SQL_Result );
        if ( $n > 0 ) {
            for ( $i=0; $i<$n; $i++ ) {
                $aname      =  mysql_result($SQL_Result, $i, 'name' );
                $atag       =  mysql_result($SQL_Result, $i, 'tag' );
		$allid 	    =  mysql_result($SQL_Result, $i, 'id' );

                if ( $i%2==0) $color=' bgcolor="#cccccc"';
                else          $color=' bgcolor="#eeeeee"';

                echo '<tr>';
                echo '<td'.$color.'>';
                echo '&nbsp;'.$aname.'&nbsp;';
                echo '</td>';
                echo '<td'.$color.'>';
                echo '&nbsp;'.$atag.'&nbsp;';
                echo '</td>';
                echo '<td'.$color.'>';
            $sql2 = 'select distinct(galaxie) from gn4accounts where allianz="'.$allid.'" and ticid="'.$Benutzer['ticid'].'" order by galaxie ASC LIMIT 0, 30';
            $SQL_result2 = mysql_query($sql2, $SQL_DBConn);
            $galanzahl = mysql_num_rows( $SQL_result2);
            $galanum = mysql_num_rows( $SQL_result2);
            if ( $galanzahl != '' ) {
                for ($p = 0; $p < $galanzahl; $p++) {
                    $SQL_result2 = mysql_query($sql2, $SQL_DBConn);
                    for ($x = 0;$x < $galanum; $x++) {
                          $gala = mysql_fetch_array($SQL_result2, MYSQL_NUM);
                    }
                    $galanum = $galanum - 1;
                    echo "$gala[0] ";
                }
		}
                echo '</td>';
                echo '<td'.$color.'>';
                echo '<a href="./main.php?modul=ally_bear&allid='.$allid.'">&nbsp;Bearbeiten&nbsp;</a>';
                echo '</td>';
                echo '<td'.$color.'>';
                echo '<a href="./main.php?modul=management&action=alliloeschen&allid='.$allid.'">&nbsp;L&ouml;schen&nbsp;</a>';
                echo '</td>';
                echo '</tr>';

            }
        }

        echo '</TABLE>';
        echo '</td>';
        echo '</tr>';
        echo '</TABLE>';
    }
	?>



</CENTER>
