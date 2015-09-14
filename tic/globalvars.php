<?PHP
    // Ränge
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
    $ScanTyp[1] = 'Militärscan';
    $ScanTyp[2] = 'Newsscan';

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

    // Schiffe
    $Schiffe[0] = 'j';      // Jäger
    $Schiffe[1] = 'b';      // Bomber
    $Schiffe[2] = 'f';      // Fregatte
    $Schiffe[3] = 'z';      // Zerstörer
    $Schiffe[4] = 'kr';     // Kreuzer
    $Schiffe[5] = 'sa';     // Schlachtschiff
    $Schiffe[6] = 't';      // Trägerschiff
    $Schiffe[7] = 'ko';     // Kommandoschiff
    $Schiffe[8] = 'ka';     // Kaperschiff
    $Schiffe[9] = 'su';     // Schutzschiff

    // Defensiveinheiten
    $Defensiv[0] = 'lo';    // Leichtes Orbitalgeschütz
    $Defensiv[1] = 'lr';    // Leichtes Raumgeschütz
    $Defensiv[2] = 'mr';    // Mittleres Raumgeschütz
    $Defensiv[3] = 'sr';    // Schweres Raumgeschütz
    $Defensiv[4] = 'a';     // Abfangjäger
    $Defensiv[5] = 'r';     // Raumbasis
?>
