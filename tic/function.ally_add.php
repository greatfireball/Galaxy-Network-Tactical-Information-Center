<?php
if($Benutzer['rang']!='5')die('Keine Rechte um eine neue Ally an zulegen Kontaktieren Sie ihren nächsten Admin');
if (!isset($_POST['txtNick'])) die('Kein Namen angeben!');
if (!isset($_POST['txtTag'])) die('Keinen Tag angeben!');
if (!isset($_POST['txtBNDs'])) $_POST['txtBNDs']='';
if (!isset($_POST['txtNAPs'])) $_POST['txtNAPs']='';
if (!isset($_POST['txtInoffizielleNAPs'])) $_POST['txtInoffizielleNAPs']='';
if (!isset($_POST['txtKriege'])) $_POST['txtKriege']='';
mysql_query('INSERT INTO `gn4allianzen` (ticid, name, tag,  info_bnds,  info_naps,  info_inoffizielle_naps,  info_kriege) VALUES ("'.$Benutzer['ticid'].'", "'.$_POST['txtNick'].'", "'.$_POST['txtTag'].'", "'.$_POST['txtBNDs'].'", "'.$_POST['txtNAPs'].'", "'.$_POST['txtInoffizielleNAPs'].'", "'.$_POST['txtKriege'].'")', $SQL_DBConn);


?>