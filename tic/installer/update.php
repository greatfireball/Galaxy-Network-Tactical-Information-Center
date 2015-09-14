<?php
include('../accdata.php');
if(mysql_connect($db_info['host'],$db_info['user'],$db_info['password'])&&mysql_select_db($db_info['dbname'])){
	echo "Verbindung zur DB erfolgreich<br>\n";
}else{ die("Keine Verbindung zur DB möglich<br>\n");}
$SQL_RESULT=mysql_query('Select value FROM `gn4vars` where name="tickdauer";') or die(mysql_error());
$SQL_NUM=mysql_num_rows($SQL_RESULT);
if($SQL_NUM=='0'){
mysql_query('INSERT INTO `gn4vars` (`name` , `value`)VALUES ("tickdauer", "15");');
}
mysql_query("CREATE TABLE `gn4meta` (
  `id` tinyint(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `sysmsg` text NOT NULL,
  `bnds` varchar(255) NOT NULL default '',
  `naps` varchar(255) NOT NULL default '',
  `wars` varchar(255) NOT NULL default '',
  `duell` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;") or die(mysql_error());


mysql_query("INSERT INTO gn4meta SELECT a.ticid as `id`, a.value as name, b.value as sysmsg, '' as bnds, '' as naps, '' as wars, null as duell FROM `gn4vars` as a LEFT JOIN `gn4vars` as b ON(a.ticid = b.ticid AND b.name='systemnachricht') WHERE a.name='ticeb';")or die(mysql_error());
echo "Update der DB erfolgreich!<br>\n";
?>