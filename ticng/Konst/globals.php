<?php

// Scan-Modul
define('SCAN_OLD', 60 * 60 * 2); // In Sekunden angeben, default sind 2 Stunden
define('SCAN_OLD_COLOR', 'red');

define('SCAN_BIRTH_GNPASTE',    0);
define('SCAN_BIRTH_FFEXT',      1);
define('SCAN_BIRTH_IRCPASTE',   2);
define('SCAN_BIRTH_IRCBOT',     3);
define('SCAN_BIRTH_MANUELL',    4);

define('SCAN_TYP_SEK',      1);
define('SCAN_TYP_UNIT',     2);
define('SCAN_TYP_MILI',     3);
define('SCAN_TYP_GESCH',    4);
define('SCAN_TYP_NEWS',     5);

define('SCAN_FLOTTENSTATUS_ORBIT',  0);
define('SCAN_FLOTTENSTATUS_ATT',    1);
define('SCAN_FLOTTENSTATUS_DEFF',   2);
define('SCAN_FLOTTENSTATUS_RUECK',  3);

// Attplaner
define('ATTPLANER_TYP_GALA',    0);
define('ATTPLANER_TYP_ALLIANZ', 1);
define('ATTPLANER_TYP_META',    2);
define('ATTPLANER_TYP_ALLE',    3);

?>
