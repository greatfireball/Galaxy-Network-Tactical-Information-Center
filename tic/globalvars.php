<?PHP
    // Rï¿½ge
    $RangName[0] = 'Mitglied';
    $RangName[1] = 'Kommodore';
    $RangName[2] = 'Vize Admiral';
    $RangName[3] = 'Admiral';
    $RangName[4] = 'Techniker';
    $RangName[5] = 'Administrator';

    $RangImage[0] = 'bilder/rang/member.gif';
    $RangImage[1] = 'bilder/rang/stern-bronze.gif';
    $RangImage[2] = 'bilder/rang/stern-silber.gif';
    $RangImage[3] = 'bilder/rang/stern-gold.gif';
    $RangImage[4] = 'bilder/rang/schluessel-silber.gif';
    $RangImage[5] = 'bilder/rang/schluessel-gold.gif';

    $Rang_Mitglied = 0;
    $Rang_GC = 1;
    $Rang_VizeAdmiral = 2;
    $Rang_Admiral = 3;
    $Rang_Techniker = 4;
    $Rang_STechniker = 5;

    define("RANG_MITGLIED", 0);
    define("RANG_GC", 1);
    define("RANG_VIZEADMIRAL", 2);
    define("RANG_ADMIRAL", 3);
    define("RANG_TECHNIKER", 4);
    define("RANG_STECHNIKER", 5);

    // Scantypen
    $ScanTyp[0] = 'Unbekannt';
    $ScanTyp[1] = 'Militaerscan';
    $ScanTyp[2] = 'Newsscan';

    // Attplaner Typen von Bytehoppers 20.07.05
    $PlanerTyps[0] = "AllyScanner";
    $PlanerTyps[1] = "AllyPlaner";
    $PlanerTyps[2] = "MetaPlaner";
    $PlanerTyps[3] = "SuperGauPlaner";

   // grün
    $ATTSTATUSINFO[0] = 'Planung';
    $ATTSTATUSHTML[0] = 'aaffaa';
   // gelb
    $ATTSTATUSINFO[1] = 'VOLL';
    $ATTSTATUSHTML[1] = 'FCB60E';
   // grün
    $ATTSTATUSINFO[2] = 'GESTARTET';
    $ATTSTATUSHTML[2] = '0EBC1A';
  // dunkles rot
    $ATTSTATUSINFO[3] = 'STOP';
    $ATTSTATUSHTML[3] = 'F4DFAC';
 // pink
    $ATTSTATUSINFO[4] = 'WARN DEFFER!';
    $ATTSTATUSHTML[4] = 'E85AD6';
 // ROT
    $ATTSTATUSINFO[5] = 'ABBRUCH/RECALL!';
    $ATTSTATUSHTML[5] = 'FF1B1B';


    // Allianzcodes
    $AllianzCode[0] = "<FONT COLOR=#0000FF><B>DEFCON-0</B></FONT>";
    $AllianzCode[1] = "<FONT COLOR=#FFA500><B>DEFCON-1</B></FONT>";
    $AllianzCode[2] = "<FONT COLOR=#FF0000><B>DEFCON-2</B></FONT>";

    // Ticks
    $Ticks['angriffsflug'] = 30;
    $Ticks['angriff'] = 5;
    $Ticks['verteidigungsflug'] = 24;
    $Ticks['verteidigen'] = 20;

    //Zeitformat
    $Zeitformat[0] = 'Minuten';
    $Zeitformat[1] = 'Stunden:Minuten';
    $Zeitformat[2] = 'Ticks';

    define("LOG_SYSTEM", 0);
    define("LOG_ERROR", 1);
    define("LOG_SETSAFE", 2);

    $Ticks['lange']=15;
	$SQL_Result = mysql_query('SELECT value FROM `gn4vars` WHERE name="tickdauer";');
	if(mysql_num_rows($SQL_Result)==1)
	{
		$Ticks['lange'] = mysql_result($SQL_Result,0);
	}

    include './globalvars2.php';
?>
