<html>
    <head>
        <title>T.I.C. NG Installation</title>
    </head>
    <body>
        <h1>T.I.C. NG Installation</h1>
<?php
error_reporting(E_ALL);

// ============ AB HIER EINSTELLUNGEN ANPASSEN ============
// tickdauer
$installdata['Ticker']['TickFrequency'] = 15;
// design
$installdata['Design']['design'] = 'default';

// das Format des nächsten Eintrags sieht wie folgt aus:
// 'datenbanktyp://login:passwort@rechner/datenbankname'
//    - datenbanktyp ist entweder mysql oder postgres
//    - login ist der benutzername fuer die datenbank
//    - passwort ist das passwort fuer die datenbank, 
//      kann bei authentifizierung ueber systemuser weggelassen werden
//    - rechner ist der rechnername, meist localhost
//    - datenbankname is der name der datenbank die genutzt werden soll
$mysqlbenutzer="name";
$mysqlpw="pw";
$mysqldatenbank="db";


// Admin user, wird am Anfang als einzigster erstellt und sollte später gelöscht werden
$installdata['UserMan']['admin_username'] = 'admin';
$installdata['UserMan']['admin_pw'] = 'geheim';
$installdata['UserMan']['admin_gala'] = 0;
$installdata['UserMan']['admin_planet'] = 1;
// ============ BIS HIER EINSTELLUNGEN ANPASSEN ============
$installdata['ADOdb']['dsn'] = 'mysql://'.$mysqlbenutzer.':'.$mysqlpw.'@localhost/'.$mysqldatenbank;
$tmp = explode('://', $installdata['ADOdb']['dsn']);
switch ($tmp[0]) {
    case 'mysql':
        $installdata['dbtype'] = 'MySQL';
        break;
    case 'postgres':
        $installdata['dbtype'] = 'PostgreSQL';
        break;
    default:
        die("FEHLER: unbekannter Datenbanktyp<br>");
}
       
//$installdata['dbtype'] = postgresql;



// Dateien und Verzeichnisse anlegen und schreibbar machen
checkFile('ticng.log', false);
checkFile('Core/conf.php', true);
checkDir('Design/template_c');

function checkFile($filename, $truncate = false)
{
    $mode = ($truncate) ? 'w' : 'a';

    if (!file_exists($filename)) {
        $fd = fopen($filename, $mode);
        if (!$fd)
            echo "WARNUNG: du mußt die Datei $filename anlegen! (und schreibbar machen)<br>";
        else
            fclose($fd);
    } else if (!is_writeable($filename)) {
    	echo "WARNUNG: die Datei $filename ist nicht schreibbar!<br>";
    } else if ($truncate) {
        fclose(fopen($filename, $mode));
    }
}

function checkDir($dirname)
{   
    if (!is_writeable($dirname))
	    echo "WARNUNG: das Verzeichnis $dirname ist nicht schreibbar!<br>";
}

require('Core/ModuleManager.class.php');
$tic = new ModuleManager(dirname(__FILE__).'/');


echo "<h3>Konfiguration:</h3>";
echo "<pre>";
print_r($installdata);
echo "</pre>";

echo "<h3>T.I.C. NG Ausgabe:</h3>";
echo "<hr width=100%>";
$tic->modsInitialize($installdata);
echo "<hr width=100%>";
$qry="DELETE FROM sql_error;";//installation verursacht error bei insert und drope die aber unrelevant sind
$tic->db->Execute("install",$qry);
$tic->modsUnload();
echo "<h2>T.I.C. NG successfully installed!</h2>";

?>
