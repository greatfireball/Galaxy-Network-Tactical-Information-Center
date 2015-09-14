<?php
if($Benutzer['rang']!='5')die('Keine Rechte, Kontaktieren Sie ihren nächsten Admin');
mysql_query('UPDATE `gn4allianzen` SET name="'.$_POST['txtNick'].'", tag="'.$_POST['txtTag'].'", info_bnds="'.$_POST['txtBNDs'].'", info_naps="'.$_POST['txtNAPs'].'",  info_inoffizielle_naps="'.$_POST['txtInoffizielleNAPs'].'", info_kriege="'.$_POST['txtKriege'].'", code="'.$_POST['lstCode'].'" WHERE id="'.$_POST['allid'].'" and ticid="'.$Benutzer['ticid'].'";', $SQL_DBConn);
?>