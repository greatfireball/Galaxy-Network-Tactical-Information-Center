<center>
  <table border="0" cellspacing="3" cellpadding="0" width="80%">
    <tr bgcolor="#333333">
      <td><b><font color="#FFFFFF" size="-1">Gala</font></b></td>
      <td><b><font color="#FFFFFF" size="-1">Name</font></b></td>
      <td><b><font color="#FFFFFF" size="-1">Telefon</font></b></td>
      <td><b><font color="#FFFFFF" size="-1">ICQ</font></b></td>
      <td><b><font color="#FFFFFF" size="-1">Zusatzinfos</font></b></td>
      <?php
        if ($Benutzer['rang'] > $Rang_GC) { // vize admiral oder mehr
            echo '<td><b><font color="#FFFFFF" size="-1">Aktion</font></b></td>';
        }
      ?>
    </tr>
        <?php
            $sql = 'SELECT name, galaxie, planet, handy, ';
            $sql .= 'messangerID, infotext, id ';
            $sql .= 'FROM `gn4accounts`';
            $sql .= 'where ticid="'.$Benutzer['ticid'].'" ORDER BY galaxie, planet';

            $SQL_Result = mysql_query( $sql, $SQL_DBConn );
            for ( $i=0; $i<mysql_num_rows($SQL_Result); $i++ ) {

                $name = mysql_result($SQL_Result, $i, 'name' );
                $gala =  mysql_result($SQL_Result, $i, 'galaxie' );
                $gala = $gala .':'. mysql_result($SQL_Result, $i, 'planet' );

                $telno = mysql_result($SQL_Result, $i,   'handy' );
                $icq = mysql_result($SQL_Result, $i,     'messangerID' );
                $infotext = mysql_result($SQL_Result, $i, 'infotext' );
                $teleid = mysql_result($SQL_Result, $i,  'id' ); // used later, to delete the record

                echo '<tr>';
                echo '<td bgcolor="#e0e0e0"><font color="#111111" size="-1">'.$gala.'</font></td>';
                echo '<td bgcolor="#e0e0e0"><font color="#111111" size="-1">'.$name.'</font></td>';
                echo '<td bgcolor="#e0e0e0"><font color="#111111" size="-1">'.$telno.'</font></td>';
                echo '<td bgcolor="#e0e0e0"><font color="#111111" size="-1">'.$icq.'</font></td>';
                echo '<td bgcolor="#e0e0e0"><font color="#111111" size="-1">'.$infotext.'</font></td>';
                if ($Benutzer['rang'] > $Rang_GC) { // vize admiral oder mehr

                    echo '<td bgcolor="#e0e0e0"><font color="#111111" size="-1"><A HREF="./main.php?modul=telelist&action=deltelentry&teleid='.$teleid.'">löschen</a></font></td>';
                }
                echo '</tr>';

            }
        ?>
  </table>

</center>
