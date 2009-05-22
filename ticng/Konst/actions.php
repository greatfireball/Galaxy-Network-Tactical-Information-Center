<?php
//                                         parameter isAllowed()        parameter log()
// =============== USERMAN ============    =====================        ===============
define('PLAYER_CHANGE_KOORDS',      0); // array($gala, $planet)        "$gala:$planet"
define('PLAYER_CHANGE_NICK',        1); //                              neuer nick

define('USER_CREATE',               2);
define('USER_DELETE',               3);
define('USER_CHANGE_KOORDS',        4); // TICUser Objekt des Ziels     "$gala:$planet"
define('USER_CHANGE_NICK',          5); //           ''                 neuer nick
define('USER_CHANGE_GNRANG',        6); // GN-Rang Konstante            GN-Rang Konstante
define('USER_CHANGE_PROFILE',       7);
define('USER_CHANGE_PASSWORD',      8);
define('USER_SET_RANDOM_PASSWORD',  9);
define('USER_BAN',                 10);
define('USER_UNBAN',               11);
define('USER_SET_ISBOT',           12);
define('USER_CHANGE_ROLE',         13);

define('GALA_CHANGE_ALLI',         20); // Allianz object               Allianz object
define('GALA_DELETE',              21);

define('ALLI_CREATE',              30);
define('ALLI_DELETE',              31);
define('ALLI_CHANGE_NAME',         32); //                              neuer name
define('ALLI_CHANGE_TAG',          33); //                              neuer tag
define('ALLI_CHANGE_META',         34); // Meta object                  Meta object

define('META_CREATE',              40);
define('META_DELETE',              41);
define('META_CHANGE_NAME',         42);
define('META_CHANGE_TAG',          43);

// =============== TAKTIK =============
define('TAKTIK_UPDATE_GALA',      100);
define('TAKTIK_SET_INC_SAFE',     101);
define('TAKTIK_SET_INC_UNSAFE',   102);

// =============== LOG ================
define('LOG_ACCES_SCANS',         203);
define('LOG_ACCES_INCSAFE',       204);
define('LOG_ACCES_GALAUPDATE',    205);

// =============== SCAN ===============
define('SCAN_ADD_SEK',            301);
define('SCAN_ADD_UNIT',           302);
define('SCAN_ADD_MILI',           303);
define('SCAN_ADD_GESCH',          304);
define('SCAN_ADD_NEWS',           305);

// =============== NEWS ===============
define('NEWS_WRITE_GALA',         400);
define('NEWS_WRITE_ALLI',         401);
define('NEWS_WRITE_META',         402);
define('NEWS_WRITE_ALLE',         403);
define('NEWS_DELETE_GALA',        404);
define('NEWS_DELETE_ALLI',        405);
define('NEWS_DELETE_META',        406);
define('NEWS_DELETE_ALLE',        407);

// =============== ADODB ==============
define('ADODB_ACCESS_FAILED_SQL', 500);

// =============== Right ==============
define('RIGHT_EDIT_RANG',         600);
define('RIGHT_EDIT_ROLE',         601);


// =============== ERROR ===============
define('ERROR',                   999); // array(gala, planet)          Modulname: Nachricht

?>
