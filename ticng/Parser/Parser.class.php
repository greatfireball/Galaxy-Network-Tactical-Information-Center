<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006,2007  Pascal Gollor  <pascal@gollor.org>                      *
 *                                                                                   *
 *  This program is free software; you can redistribute it and/or                    *
 *  modify it under the terms of the GNU General Public License                      *
 *  as published by the Free Software Foundation; either version 2                   *
 *  of the License, or (at your option) any later version.                           *
 *                                                                                   *
 *  This program is distributed in the hope that it will be useful,                  *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of                   *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                    *
 *  GNU General Public License for more details.                                     *
 *                                                                                   *
 *  You should have received a copy of the GNU General Public License                *
 *  along with this program; if not, write to the Free Software                      *
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.  *
 *                                                                                   *
 *************************************************************************************/

//
// Class Parser
//
//

// --------------------------------------------------------------------------------- //

/*

TODO:
    Newsscan aus dem IRC
    Newsscan ueber FF-Extension

*/

require_once("Extension.class.php");
require_once("scanKlassen.php");

class Parser extends TICModule
{
    private $found = false;
    private $ausgabe = array();
    
    function Parser()
    {
	parent::__construct(
	array(new Author("Pascal Gollor", "Hugch", "pascal@gollor.org"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"Parser",
	"Einf&uuml;gen der Scans, Taktik, usw.",
	array(
            "Core" => "10",
            "Design" => "10",
            "UserMan" => "10",
            "Konst" => "10",
            "Scan" => "10",
            "Logging" => "10"
          ));
    
    }

    public function createMenuEntries($menuroot)
    {
        $main = $menuroot->getChildByName('Main');
        $main->addChild(new MenuEntry("Parser", 1, $this, "Parser"));
    }

    public function getInstallQueriesMySQL() { return array(); }
    public function getInstallQueriesPostgreSQL() { return array(); }

    public function onExecute($menuentry)
    {
        global $tic;

        $adr = $tic->mod['Core']->getTICAdr();
        $this->setVar('parserPfad', $adr."?mod=".$this);

        if (isset($_POST['paste_save'])) { $this->pasteAuswertung($_POST['paste']); }
        if (isset($_POST['create_yes'])) { $manageUser = new _manageUser($this, 1, $_POST['user_create']); }
        if (isset($_POST['del_yes'])) { $manageUser = new _manageUser($this, 2, $_POST['user_del']); }
        if (isset($_POST['move_yes'])) { $manageUser = new _manageUser($this, 3, $_POST['user_move']); }

        if (isset($_POST['ff-ext'])) { $ext = new _extension($_POST['ff-ext']); }

        if (isset($manageUser)) {
            $tic->error($this, $manageUser->error);
            $this->setVar('parser_ausgabe', $manageUser->ausgabe);
        }
        if (isset($ext)) {
            $tic->error($this, $ext->error);
            $this->setVar('parser_ausgabe', $ext->ausgabe);
            if ($ext->close) { $this->setVar('tic_close', "window.close();"); }
        }

        $hilfe = array(
            'ircKonvert' => "Hier k&ouml;nnen verschiedene Auswertung von GN gemacht werden.",
            'fromIRC' => "Scans oder Flotten aus dem IRC."
        );
        $this->setVar("parserHilfe", $hilfe);

        if (isset($_POST['userTPL'])) {
            $this->setTemplate('parser_user.tpl');
        } else {
            $this->setTemplate('parser.tpl');
        }
    }

    private function pasteAuswertung($paste)
    {
        global $tic;

        $aktUser = $tic->mod['Auth']->getActiveUser();
        $userAllianz = $aktUser->getAllianz();

        if (isset($_POST['is_IRCkonvert'])) { $auswertung = new _IRCkonvert($paste); }
        elseif (isset($userAllianz) && preg_match('/(Allianz Mitglieder)/', $paste)) { $this->AllianzUser($paste); }
        elseif (preg_match('/(galaxiemitglieder.*nachricht an die gesamte galaxie senden)/is', $paste)) {
            $savePaste = new _galaScans($paste);
            $this->found = true;
            if ($savePaste->error) { $tic->error($this, $savePaste->error); }
            if ($savePaste->ausgabe) { array_push($this->ausgabe, $savePaste->ausgabe); }
        }
        if (isset($_POST['is_irc'])) { $savePaste = new _ircScanSave($paste); }
        elseif (preg_match('/(ergebnis \(genauigkeit)/i', $paste)) { $savePaste = new _scanSave($paste); }
        if (preg_match('/(flottenzusammensetzung von)/i', $paste)) { $savePaste = new _miliDeffSave($paste); }
        if (preg_match('/(www.galaxy-network.de\/game\/ks_extern\.php)/', $paste)) { $savePaste = new _vag($paste); }
        if (preg_match('/(flottenbewegungen.*nachricht an die gesamte galaxie senden)/is', $paste)) { $savePaste = new _galaTaktik($paste); }
        if (preg_match('/(id.*rang.*nick.*punkte.*gr.*ße)/iU', $paste)) { $this->galaxiePlayer($paste); }

        if (isset($savePaste)) {
            $this->found = true;
            if ($savePaste->error) { $tic->error($this, $savePaste->error); }
            if ($savePaste->ausgabe) { array_push($this->ausgabe, $savePaste->ausgabe); }
        }
        if (isset($auswertung)) {
            $this->found = true;
            if ($auswertung->error) { $tic->error($this, $auswertung->error); }
            if ($auswertung->ausgabe) { array_push($this->ausgabe, $auswertung->ausgabe); }
        }
        if (!$this->found) { array_push($this->ausgabe, "Es wurde kein bekanntes Muster gefunden!!!"); }
        if (count($this->ausgabe) == 0) { $this->ausgabe = false; }

        $this->setVar('parserAusgabe', $this->ausgabe);
        $this->setVar('parserPaste' ,$paste);
    }

    public function vagLink($link)
    {
        global $tic;

        $obj = new _vag($link, true);
        if ($obj->error !== false) { return false; }
        return $obj->ausgabe;
    }


    private function AllianzUser($paste)
    {
        global $tic;

        $this->found = true;
        // TIC-Raenge fehlen noch
        $aktUser = $tic->mod['Auth']->getActiveUser();
        $aktAllianz = $aktUser->getAllianz();

        //if (isAllowd) {
            $c0 = 0; $m0 = 0; $d0 = 0; $i0 = 0;
            $split = preg_split('/\r\n/', $paste);
            foreach ($split as $key => $value) {
                $value = trim($value);
                if ($key != 0 && $value != "") {
                    $value = preg_split('/\t/', $value);
                    if (is_numeric(trim($value[0]))) {
                        $gala[$i0] = preg_replace('/(\d+):(\d+)/', '$1', trim($value[1]));
                        $planet[$i0] = preg_replace('/(\d+):(\d+)/', '$2', trim($value[1]));
                        $nick[$i0] = trim(preg_replace('/ \*/', '', $value[2]));
                        $user = $tic->mod['UserMan']->getUserByNick($nick[$i0]);
                        $newGala = $tic->mod['UserMan']->getGalaxie($gala[$i0]);
                        if ($newGala) { $newAllianz = $newGala->getAllianz(); }
                        if (!$user) {
                            $_POST['user_create'][$c0]['nick'] = $nick[$i0];
                            $_POST['user_create'][$c0]['gala'] = $gala[$i0];
                            $_POST['user_create'][$c0]['planet'] = $planet[$i0];
                            $c0++;
                        } elseif ($user->getGalaxie() != $gala[$i0] || $user->getPlanet() != $planet[$i0]) {
                            $userAllianz = $user->getAllianz();
                            $move = false;
                            if ($newGala && $newAllianz && $newAllianz->getName() == $userAllianz->getName()) {
                                $move = true;
                            } elseif (!$newGala) {
                                $move = true;
                            }
                            if ($move) {
                                $_POST['user_move'][$m0]['nick'] = $nick[$i0];
                                $_POST['user_move'][$m0]['gala'] = $user->getGalaxie();
                                $_POST['user_move'][$m0]['planet'] = $user->getPlanet();
                                $_POST['user_move'][$m0]['new_gala'] = $gala[$i0];
                                $_POST['user_move'][$m0]['new_planet'] = $planet[$i0];
                                $m0++;
                            }
                        }
                        $i0++;
                    }
                }
            }
            $allianzUsers = $aktAllianz->getUsers();
            foreach ($allianzUsers as $value) {
                for ($i0 = 0; $i0 < count($nick); $i0++) {
                    $user = false;
                    if ($value->getNick() == $nick[$i0]) {
                        $user = true;
                        break 1;
                    }
                }
                if (!$user) {
                    $_POST['user_del'][$d0]['nick'] = $value->getNick();
                    $_POST['user_del'][$d0]['gala'] = $value->getGalaxie();
                    $_POST['user_del'][$d0]['planet'] = $value->getPlanet();
                    $d0++;
                }
            }

            if (($c0 + $m0 + $d0) == 0) {
                array_push($this->ausgabe, "Es konnte keine &Auml;nderung gefunden werden.");
            } else {
                $_POST['userTPL'] = true;
            }
        /*} else {
            array_push($this->ausgabe, "Sie haben nicht die erforderlichen Rechte!!!");
        }*/
    }

    private function galaxiePlayer($paste)
    {
        global $tic;

        $this->found = true;
        foreach (preg_split('/\r\n/', $paste) as $key => $value) {
            $value = trim($value);
            if ($value != "") {
                $value = preg_split('/\t/', $value);
                if (trim($value[0]) != "ID") {
                    if (count($value) != 6 && count($value) != 7) { $this->error = "Fehler in der Analyse!!!"; return false; }
                    $gala = preg_replace('/(\d+):(\d+)/', '$1', trim($value[0]));
                    $planet = preg_replace('/(\d+):(\d+)/', '$2', trim($value[0]));
                    $nick = trim($value[2]);

                    $player = new GNPlayer($gala, $planet, $nick);

                    $player->create();
                }
            }
        }
    }
}

class _manageUser
{
    public $error = false;
    public $ausgabe = false;
    private $modName;

    public function __construct($modName, $mode, $data)
    {
        $this->modName = $modName;
        switch ($mode) {
            case 1:
                $this->addUser($data);
                break;
            case 2:
                $this->delUser($data);
                break;
            case 3:
                $this->moveUser($data);
                break;
        }
    }

    private function addUser($data)
    {
        global $tic;

        $aktUser = $tic->mod['Auth']->getActiveUser();
        $aktAllianz = $aktUser->getAllianz();

        foreach ($data as $value) {
            $gala = $tic->mod['UserMan']->getGalaxie($value['gala']);
            if ($gala) { $gala->getAllianz($aktAllianz->getId()); }
            $user = new TICUser($value['gala'], $value['planet'], $value['nick']);
            if (!$user->create()) {
                $this->error = "Konnte ".$value['nick']." (".$value['gala'].":".$value['planet'].") nicht anlegen!!!";
                return false;
            }
            $tic->mod['Logging']->log(USER_CREATE, $user, "mit Hilfe vom Parser");
        }
        $this->ausgabe = "User wurden erfolgreich angelegt.";
    }

    private function delUser($data)
    {
        global $tic;

        foreach ($data as $value) {
            $user = $tic->mod['UserMan']->getUserByNick($value['nick']);
            if (!$user->delete()) {
                $this->error = "Konnte ".$value['nick']."(".$value['gala'].":".$value['planet'].") nicht l&ouml;schen!!!";
                return false;
            }
            $tic->mod['Logging']->log(USER_DELETE, $user, "mit Hilfe vom Parser");
        }
        $this->ausgabe = "User wurde erfolgreich gel&ouml;scht.";
    }

    private function moveUser($data)
    {
        global $tic;

        foreach ($data as $value) {
            $user = $tic->mod['UserMan']->getUserByNick($value['nick']);
            if (!$user->setKoords($value['new_gala'], $value['new_planet'])) {
                $this->error = "Konnte ".$value['nick']."(".$value['gala'].":".$value['planet'].") nicht nach ".$value['new_gala'].":".$value['new_planet']." verschieben!!!";
                return false;
            }
            $tic->mod['Logging']->log(USER_CHANGE_KOORDS, $user, "mit Hilfe vom Parser");
        }
        $this->ausgabe = "User wurde erfolgreich verschoben.";
    }
}

class _galaTaktik
{
    public $error = false;
    public $ausgabe = false;

    public function __construct($paste)
    {
        if ($paste) {
            $paste = preg_replace('/(Nachricht an die gesamte Galaxie senden)(.*\r\n)+/', '$1', $paste);
            $paste = preg_replace('/·  Nachricht an die gesamte Galaxie senden ··»/', '', $paste);

            if (preg_match('/[\d]+ Sek/', $paste) || preg_match('/[\d]+ Std/', $paste)) {
                $this->error = "Gerundete Zeiten sind f&uuml;r den Parser zu ungenau!!!";
                return false;
            }

            $paste = preg_replace('/\r\n \r\n/', "\t\t\t\t\r\n", $paste);
            $paste = preg_replace('/(\w) (\w)/', '$1-::-$2', $paste);
            $paste = preg_replace(array('/ \*/', '/ Min/', '/ (\|[SEMGN])+\|/', '/\|/'), array('', 'm', '', ''), $paste);
            $paste = preg_replace('/^.*?(flottenbewegungen)\s+(sektor)/i', "$1\r\n$2", $paste);
            $paste = preg_replace('/(sektor)\s+(kommandant)/i', '$1-$2', $paste);
            $pattern = array(
                '/ (\t)/',                                              // 1
                '/( \r\n){2}/',                                         // 2
                '/ (\r\n)/',                                            // 3
                '/(\d+:\d+)[ \t](\S)/',                                 // 4
                '/(r.*?ckflug)\r\n\((\d+\:\d+)\-::\-([^\n\r]*)\)/i',    // 5
                '/ /',                                                  // 6
                '/\r\n(\t)/',                                           // 7
                '/-(\t)/',                                              // 8
                '/\n/',                                                 // 9
                '/\r+/'                                                 // 10
            );
            $replace = array(
                '$1',               // 1
                '$1',               // 2
                '$1',               // 3
                '$1-::-$2',         // 4
                '$1-::-$2-::-$3',   // 5
                "\t",               // 6
                '$1',               // 7
                '$1',               // 8
                "\r",               // 9
                "\r"               // 10
            );
            $paste = preg_replace($pattern, $replace, $paste);

            $taktik = array();
            $break_it = 0; $stop = false;
            do {
                $break = true;
                if (preg_match('/([^\t\r]*(\t[^\t]*){8})\r/', $paste, $line_reg)) {
                    if (preg_match('/([^\t\r]*)\t([^\t]*)\t([^\t]*)\t([^\t]*)\t([^\t]*)\t([^\t]*)\t([^\t]*)\t([^\t]*)\t([^\t]*)/', $line_reg[1], $cells) && count($cells) == 10) {
                        $temparray = $cells;
                        array_shift($temparray);
                        array_push($taktik, $temparray);
                    }
                    $paste = preg_replace('/'.quotemeta($line_reg[1]).'\r/', '', $paste);
                    $break = false;
                }
                $break_it++;
            } while (($break == false) && ($break_it < 25));

            unset($taktik[0]);
            $this->auswertung($taktik);
        }
    }

    public function auswertung($taktik)
    {
        global $tic;

        $flotten  = array();
        foreach ($taktik as $key => $value) {
            preg_match('/^([\d]+)?:/', $value[0], $match);
            if (!$this->UserTest($value[0])) {
                if ($this->error == null) {
                    $this->error = "Der Taktikbildschirm konnte nicht fehlerfrei ausgewertet werden!!!";
                }
                return false;
            }
            if (count($value) != 9) {
                $this->error = "Der Taktikbildschirm konnte nicht fehlerfrei ausgewertet werden!!!";
                return false;
            }
	        for ($i0 = 0; $i0 < 9; $i0++) {
                if (preg_match('/\r/', $value[$i0])) {
                    $taktik[$key][$i0] = preg_split('/\r/', $value[$i0]);
                    $value[$i0] = $taktik[$key][$i0];
                }
            }
            for ($i0 = 2; $i0 < 9; $i0 = $i0 + 2) {
                if (is_array($value[$i0])) {
                    for ($i1 = 0; $i1 < count($value[$i0]); $i1++) {
                        if (trim($value[$i0][$i1]) != "") {
                            $taktik[$key][$i0][$i1] = $this->konvertEta($value[$i0][$i1]);
                            if ($taktik[$key][$i0][$i1] === false) { return false; }
                            $value[$i0][$i1] = $taktik[$key][$i0][$i1];
                        }
                    }
                } else {
                    if (trim($value[$i0]) != "") {
                        $taktik[$key][$i0] = $this->konvertEta($value[$i0]);
                        if ($taktik[$key][$i0] === false) { return false; }
                        $value[$i0] = $taktik[$key][$i0];
                    }
                }
            }
            // Fuer Taktikmodul preparieren
            // Angriff
            $user = preg_replace('/\-::\-.*$/', '', $value[0]);
            $user = preg_split('/:/', $user);
            if (is_array($value[1]) || trim($value[1]) != "") {
                if (is_array($value[1])) {
                    for ($i0 = 0; $i0 < count($value[1]); $i0++) {
                        $erg = $this->bewegungsAnalyse($value[1][$i0]);
                        if (!$erg) { return false; }
                        $flotte = new Flottenbewegung($user[0], $user[1], false, $erg[0], $erg[1], true, $erg[2], 30, false, $value[2][$i0]);
                        //$flotte->save();
                        array_push($flotten, $flotte);
                    }
                } else {
                    $erg = $this->bewegungsAnalyse($value[1]);
                    if (!$erg) { return false; }
                    $flotte = new Flottenbewegung($user[0], $user[1], false, $erg[0], $erg[1], true, $erg[2], 30, false, $value[2]);
                    //$flotte->save();
                    array_push($flotten, $flotte);
                }
            }
            // Deff
            if (is_array($value[3]) || trim($value[3]) != "") {
                if (is_array($value[3])) {
                    for ($i0 = 0; $i0 < count($value[3]); $i0++) {
                        $erg = $this->bewegungsAnalyse($value[3][$i0]);
                        if (!$erg) { return false; }
                        $flotte = new Flottenbewegung($user[0], $user[1], false, $erg[0], $erg[1], false, $erg[2], false, false, $value[4][$i0]);
                        //$flotte->save();
                        array_push($flotten, $flotte);
                    }
                } else {
                    $erg = $this->bewegungsAnalyse($value[3]);
                    if (!$erg) { return false; }
                    $flotte = new Flottenbewegung($user[0], $user[1], false, $erg[0], $erg[1], false, $erg[2], false, false, $value[4]);
                    //$flotte->save();
                    array_push($flotten, $flotte);
                }
            }
            // Inc
            if (is_array($value[5]) || trim($value[5]) != "") {
                if (is_array($value[5])) {
                    for ($i0 = 0; $i0 < count($value[5]); $i0++) {
                        $erg = $this->bewegungsAnalyse($value[5][$i0]);
                        if (!$erg) { return false; }
                        $flotte = new Flottenbewegung($erg[0], $erg[1], false, $user[0], $user[1], true, false, 30, false, $value[6][$i0]);
                        //$flotte->save();
                        array_push($flotten, $flotte);
                    }
                } else {
                    $erg = $this->bewegungsAnalyse($value[5]);
                    if (!$erg) { return false; }
                    $flotte = new Flottenbewegung($erg[0], $erg[1], false, $user[0], $user[1], true, false, 30, false, $value[6]);
                    //$flotte->save();
                    array_push($flotten, $flotte);
                }
            }
            // Deff von anderen
            if (is_array($value[7]) || trim($value[7]) != "") {
                if (is_array($value[7])) {
                    for ($i0 = 0; $i0 < count($value[7]); $i0++) {
                        $erg = $this->bewegungsAnalyse($value[7][$i0]);
                        if (!$erg) { return false; }
                        $flotte = new Flottenbewegung($erg[0], $erg[1], false, $user[0], $user[1], false, false, false, false, $value[8][$i0]);
                        //$flotte->save();
                        array_push($flotten, $flotte);
                    }
                } else {
                    $erg = $this->bewegungsAnalyse($value[7]);
                    if (!$erg) { return false; }
                    $flotte = new Flottenbewegung($erg[0], $erg[1], false, $user[0], $user[1], false, false, false, false, $value[8]);
                    //$flotte->save();
                    array_push($flotten, $flotte);
                }
            }
        }
        $tic->mod['Taktik']->updateGalaTaktik($flotten);

        return true;
    }

    private function bewegungsAnalyse($be)
    {
        $rueck = false;
        if (preg_match('/r.*?ckflug/i', strtolower($be))) { $rueck = true; }
        $split = preg_split('/\-::\-/', $be);
        if (count($split) == 2) {
            $koord = $split[0];
        } elseif (count($split) == 3) {
            $koord = $split[1];
        } else {
            $this->error = "Fehler in der Eingabe (Flotten)!!!";
            return false;
        }
        $koord = preg_split('/:/', $koord);
        $nick = $split[count($split) - 1];
        $player = new GNPlayer($koord[0], $koord[1], $nick);
        if (!$player->create()) {
            $this->error = "Konnte User nicht erstellen.";
            return false;
        }
        return array($koord[0], $koord[1], $rueck);
    }

    private function konvertETA($eta)
    {
        global $tic;

        $eta = trim($eta);
        $tickFrequency = $tic->mod['Ticker']->getTickFrequency();
        if (preg_match('/(\d+):(\d+):(\d+)/', $eta, $treffer)) {
            // Stunden:Minuten:Sekunden
            $treffer[1] = (int) $treffer[1];
            $treffer[2] = (int) $treffer[2];
            $treffer[3] = (int) $treffer[3];
            $min = $treffer[1] * 60 + $treffer[2];
            if ($treffer[3] != 0) { $min = $min + $treffer[3] / 60; }
            $eta = ceil($min / $tickFrequency);
        } elseif (preg_match('/(\d+):(\d+)/', $eta, $treffer)) {
            // Stunden:Minuten
            $treffer[1] = (int) $treffer[1];
            $treffer[2] = (int) $treffer[2];
            $min = $treffer[1] * 60 + $treffer[2];
            $eta = ceil($min / $tickFrequency);
        } elseif (preg_match('/m/', $eta)) {
            $min = preg_replace('/\D/', '', $eta);
            $eta = ceil($min / $tickFrequency);
        } elseif (preg_match('/sek/i', $eta) || preg_match('/std/', $eta)) {
            $this->error = "Gerundete Stunden sind zu ungenau f&uuml;r den TICNG!!!";
            return false;
        } else {
            // Ticks
        }

        if (!is_numeric($eta)) {
            $this->error = "Fehler in der Flugzeit!!!";
            return false;
        }

        return $eta;
    }

    private function UserTest($data)
    {
        global $tic;

        $aktUser = $tic->mod['Auth']->getActiveUser();
        $aktGala = $aktUser->getGalaxie();

        $data = preg_split('/\-::\-/', $data);
        $koord = $data[0];
        $nick = $data[1];
        $gala = preg_replace('/(\d+):(\d+)/', '$1', $koord);
        $planet = preg_replace('/(\d+):(\d+)/', '$2', $koord);
        $return = true;

        if ($gala != $aktGala) {
            $this->error = "Der Taktikbildschirm ist nicht aus deiner Galaxie!!!";
            return false;
        }
        if (!is_numeric($gala) || !is_numeric($planet)) { $return = false; }
        if (preg_match('/^0/', $gala) || preg_match('/^0/', $planet)) { $return = false; }
        if (strlen($gala) > 4 || strlen($planet) > 2) { $return = false; }

        return $return;
    }
}

class _vag
{
    public $error = false;
    public $ausgabe = false;
    private $merkmale;

    public function __construct($paste, $url = false)
    {
        global $tic;

        $this->merkmale = new _scanMerkmale();
        $seite = trim($paste);
        if (!$handle = fopen($seite, "r")) { die("Konnte Link nicht &ouml;ffnen!!!"); }
        while (!feof($handle)) {
            $seite .= fgets($handle, 1000);
        }
        fclose($handle);

        if (!preg_match('/Zusammenfassender Kampfbericht/', preg_replace('/\r\n/', '', $seite))) { $this->error = "Dies ist kein Zusammenfassender Kampfbericht!!!"; return false; }

        $push = false;
        $splStr = $seite; $seite = array();
        foreach (preg_split('/\r\n/', $splStr) as $key => $value) {
            if (preg_match('/<TD>J.*?ger/', $value)) { $push = true; }
            $value = preg_replace(array('/<.*?>/', '/&nbsp;/'), '', $value);
            if ($push == true && trim($value) != "" && trim($value) != "Defensiveinheiten") { array_push($seite, $value); }
        }
        unset ($seite[count($seite) - 1]);

        if (count($seite) < 72) { $att = true; } else { $att = false; }
        if (count($seite) < 64) {
            $tic->info('Parser', 'Bei diesem Zusammenfassendenkampfbericht gibt es keien Verluste.');
            return;
        }
        for ($i0 = 0; $i0 < 14; $i0++) {
            for ($i1 = 0; $i1 < 5; $i1++) {
                if ($i0 < 9) { $data[$i0][$i1] = $seite[($i0 * 5 + $i1)]; }
                if ($att == true) {
                    if ($i0 >= 9 && $i1 < 3) { $data[$i0][$i1] = $seite[($i0 * 3 + $i1 + 18)]; }
                } else {
                    if ($i0 >= 9 && $i1 < 4) { $data[$i0][$i1] = $seite[($i0 * 4 + $i1 + 9)]; }
                }
            }
        }

        if (!$this->auswertung($data, $att, $url)) { return false; }
        return true;
    }

    private function auswertung($data, $att, $url)
    {
        global $tic;

        foreach ($data as $key => $value) {
            if ($key < 9) {
                $vag['atter'][$this->merkmale->unitName[$key]] = $value[1];
                $vag['deffer'][$this->merkmale->unitName[$key]] = $value[2];
                $vag['eigene'][$this->merkmale->unitName[$key]] = $value[3];
            } else {
                if ($att == true) {
                    $vag['atter'][$this->merkmale->geschName[$key - 9]] = 0;
                    $vag['deffer'][$this->merkmale->geschName[$key - 9]] = $value[1];
                    $vag['eigene'][$this->merkmale->geschName[$key - 9]] = 0;
                } else {
                    $vag['atter'][$this->merkmale->geschName[$key - 9]] = 0;
                    $vag['deffer'][$this->merkmale->geschName[$key - 9]] = $value[1];
                    $vag['eigene'][$this->merkmale->geschName[$key - 9]] = $value[2];
                }
            }
        }

        $link['atter'] = $tic->mod['Vag']->createLink($vag['atter']);
        $link['deffer'] = $tic->mod['Vag']->createLink($vag['deffer']);
        $link['eigene'] = $tic->mod['Vag']->createLink($vag['eigene']);
        $this->ausgabe = '<a href="'.$link['atter'].'" target="_blank" title="Atter">Verluste-Atter</a>';
        $this->ausgabe .= '<br><a href="'.$link['deffer'].'" target="_blank" title"Deffer">Verluste-Deffer</a>';
        $this->ausgabe .= '<br><a href="'.$link['eigene'].'" target="_blank" title="Eigene Flotte">eigene Verluste</a>';

        if ($url) { $this->ausgabe = $link['atter'].':||:'.$link['deffer'].':||:'.$link['eigene']; }
        return true;
    }
}

class _IRCkonvert
{
    public $error = false;
    public $ausgabe = false;
    private $aktUser;
    private $color;

    public function __construct($paste)
    {
        global $tic;

        if (preg_match('/produktion in den schiffswerften/i', preg_replace('/\r\n/', '', $paste))
          || preg_match('/(forschung|konstruktion)/i', preg_replace('/\r\n/', '', $paste))
          || preg_match('/verteidigung/i', preg_replace('/\r\n/', '', $paste))) {
            $match = array('/(typ:)[\s\w]*(klasse:)/iU', '/(\d+? m)\r\n(\d+? k)/',
                '/(in bau)\r\n(\t\d+)/i', '/(\r\n)+?\t (\d+? m)/i');
            $replace = array('$1-$2', '$1-$2', '$1$2', "\t$2");
            $paste = preg_replace($match, $replace, $paste);
        }

        $this->aktUser = $tic->mod['Auth']->getActiveUser();
        $splStr = $paste;
        $paste = array();
        foreach (preg_split('/\r\n/', $splStr) as $key => $value) {
            $value = trim($value);
            if ($value != "") {
                $value = preg_split('/\t/', $value);
                array_push($paste, $value);
            }
        }

        $this->color = $tic->mod['Konst']->getDefaultIRCColors();

        if (!isset($paste[0])) { $this->error = "Fehler in der Auswertung!!!"; return false; }
        if (preg_match('/Einkommen pro Tick/', $paste[0][0])) { $this->rohstoffe($paste); }
        elseif (preg_match('/Galaxiemitglieder/', $paste[0][0]) &&
            preg_match('/Nachricht an die gesamte Galaxie senden/', $paste[count($paste) - 1][0])) {
            $this->gala($paste);
        }
        elseif (preg_match('/allianz mitglieder/i', $paste[0][0])) { $this->allianz($paste); }
        elseif (preg_match('/produktion in den schiffswerften/i', $paste[0][0])) { $this->produktion($paste); }
        elseif (preg_match('/forschung/i', $paste[0][0])) { $this->forschung($paste, 1); }
        elseif (preg_match('/konstruktion/i', $paste[0][0])) { $this->forschung($paste, 2); }
        elseif (preg_match('/verteidigung/i', $paste[0][0])) { $this->verteidigung($paste); }
        //else { $this->error = "Es konnte keine Auswertung vorgenommen werden!!!"; return false; }
    }

    private function e_comp($a, $b)
    {
        if ($a['ge'] == $b['ge']) {
            return 0;
        }
        return ($a['ge'] > $b['ge']) ? -1 : 1;
    }

    private function rohstoffe($paste)
    {
        $resis['met']['for'] = preg_replace('/\D/', '', $paste[3][1]);
        $resis['met']['after'] = preg_replace('/\D/', '', $paste[3][2]);
        $resis['met']['tax'] = preg_replace('/(^\d).*(\d).*/', '$1', $paste[1][1]);
        $resis['kris']['for'] = preg_replace('/\D/', '', $paste[4][1]);
        $resis['kris']['after'] = preg_replace('/\D/', '', $paste[4][2]);
        $resis['kris']['tax'] = preg_replace('/(^\d).*(\d).*/', '$2', $paste[1][1]);
        $resis['me'] = ($resis['met']['for'] - 10000) / 50;
        $resis['ke'] = ($resis['kris']['for'] - 10000) / 50;
        $resis['exen'] = $resis['me'] + $resis['ke'];
        $resis['flaeche'] = preg_replace('/\D/', '', $paste[6][1]) + preg_replace('/\D/', '', $paste[7][1]);
        $resis['eff'] = round($resis['exen'] / $resis['flaeche'] * 100);

        $nick = $this->aktUser->getNick();
        $koords = $this->aktUser->getKoords();

        $ausgabe = $this->color[0]."Rohstoffproduktion von ".$nick." (".$this->color[1].$koords.$this->color[0]."):\n";
        $ausgabe .= $this->color[2]."Extraktoren: ".$this->color[3].$resis['me']." / ".$resis['ke'];
        $ausgabe .= $this->color[2]." Extraktorenfläche: ".$this->color[3].$resis['exen']." / ".$resis['flaeche']." [".$resis['eff']."%]\n";
        $ausgabe .= $this->color[2]."Einkommen pro Tick: ".$this->color[3].number_format($resis['met']['after'], 0, ',', '.')." Metall ";
        $ausgabe .= $this->color[2]." / ".$this->color[3].number_format($resis['kris']['after'], 0, ',', '.')." Kristall\n";
        $ausgabe .= $this->color[2]."Einkommen pro Tag: ".$this->color[3].number_format($resis['met']['after'] * 90, 0, ',', '.')." Metall ";
        $ausgabe .= $this->color[2]." / ".$this->color[3].number_format($resis['kris']['after'] * 90, 0, ',', '.')." Kristall\n";
        $ausgabe .= $this->color[0]."Steuersatz der Galaxie ".$this->color[1].$resis['met']['tax']."% Metall".$this->color[0]." / ";
        $ausgabe .= $this->color[1].$resis['kris']['tax']."% Kristall";

        $this->ausgabe = '<textarea cols="30" rows="2" readonly>'.$ausgabe.'</textarea>';
    }

    private function gala($paste)
    {
        global $tic;

        for ($i0 = 2; $i0 < (count($paste) - 3); $i0++) {
            $data[$i0 - 2]['koords'] = $paste[$i0][0];
            $gala = preg_replace('/(\d+):(\d+)/', '$1', $data[$i0 - 2]['koords']);
            $data[$i0 - 2]['nick'] = preg_replace('/ \*/', '', $paste[$i0][1]);
            $data[$i0 - 2]['me'] = preg_replace('/(\d+) \/ (\d+)/', '$1', $paste[$i0][5]);
            $data[$i0 - 2]['ke'] = preg_replace('/(\d+) \/ (\d+)/', '$2', $paste[$i0][5]);
            $data[$i0 - 2]['ge'] = $data[$i0 - 2]['me'] + $data[$i0 - 2]['ke'];
        }

        $ausgabe = $this->color[0]."Extraktorenauswertung der Galaxie: ".$this->color[1].$gala.$this->color[0];
        $ausgabe .= " (Sortierung nach Gesamtextraktoren)\n";
        usort($data, array($this,"e_comp"));

        foreach ($data as $key => $value) {
            $i0 = $key + 1;
            if ($i0 < 10) { $i0 = "0".$i0; }
            $ausgabe .= $this->color[2].$i0.". ".$this->color[3].$value['nick'].$this->color[2]." (";
            $ausgabe .= $this->color[1].$value['koords'].$this->color[2].") - ".$this->color[2]."Metall: ".$this->color[3].$value['me'];
            $ausgabe .= $this->color[2]." Kristall: ".$this->color[3].$value['ke'].$this->color[2]." - Gesammt: ".$this->color[3].$value['ge']."\n";

            // Erstellen oder updaten der GNPlayer
            $gala = preg_replace('/(\d+):(\d+)/', '$1', $value['koords']);
            $planet = preg_replace('/(\d+):(\d+)/', '$2', $value['koords']);
        }

        $ausgabe .= $this->color[0]."Erstellt am: ".$this->color[1].date("Y.m.d").$this->color[0]." um: ".$this->color[1].date("H:i:s");
        $this->ausgabe = '<textarea cols="30" rows="2" readonly>'.$ausgabe.'</textarea>';
    }

    private function allianz($paste)
    {
        $UserAllianz = $this->aktUser->getAllianz();
        if (!$UserAllianz) { $this->error = "Du geh&ouml;rst keiner Allianz an!!!"; return false; }

        $i1 = 0; $gala[$i1]['id'] = 0;
        for ($i0 = 2; $i0 < count($paste); $i0++) {
            $aktGala = preg_replace('/(\d+):(\d+)/', '$1', $paste[$i0][1]);
            if ($gala[$i1]['id'] != $aktGala) {
                $i1++;
                $gala[$i1]['id'] = $aktGala;
                $gala[$i1]['exen']['all'] = 0;
                $gala[$i1]['punkte']['all'] = 0;
                $gala[$i1]['user'] = 0;
                if (($i1 - 1) > 0) {
                    $gala[$i1 - 1]['exen']['mid'] = round($gala[$i1 - 1]['exen']['all'] / $gala[$i1 - 1]['user']);
                    $gala[$i1 - 1]['punkte']['mid'] = round($gala[$i1 - 1]['punkte']['all'] / $gala[$i1 - 1]['user']);
                }
            }
            $gala[$i1]['exen']['all'] = $gala[$i1]['exen']['all'] + preg_replace('/\D/', '', $paste[$i0][3]);
            $gala[$i1]['punkte']['all'] = $gala[$i1]['punkte']['all'] + preg_replace('/\D/', '', $paste[$i0][5]);
            $gala[$i1]['user']++;
        }
        unset($gala[0]);
        $gala[$i1]['exen']['mid'] = round($gala[$i1]['exen']['all'] / $gala[$i1]['user']);
        $gala[$i1]['punkte']['mid'] = round($gala[$i1]['punkte']['all'] / $gala[$i1]['user']);

        $ausgabe = $this->color[0]."Allianzauswertung der Allianz: ".$this->color[1].$UserAllianz->getName()."\n";

        foreach ($gala as $value) {
            $value['exen']['mid'] = number_format($value['exen']['mid'], 0, ",", ".");
            $value['exen']['all'] = number_format($value['exen']['all'], 0, ",", ".");
            $value['punkte']['mid'] = number_format($value['punkte']['mid'], 0, ",", ".");
            $value['punkte']['all'] = number_format($value['punkte']['all'], 0, ",", ".");

            $ausgabe .= $this->color[2]."Gala: ".$this->color[3].$value['id'].$this->color[2];
            $ausgabe .= " - Mitglieder: ".$this->color[3].$value['user'].$this->color[2];
            $ausgabe .= " - Punkte: ".$this->color[3]."&Oslash;".$value['punkte']['mid']." (".$value['punkte']['all'].")".$this->color[2];
            $ausgabe .= " - Extraktoren: ".$this->color[3]."&Oslash;".$value['exen']['mid']." (".$value['exen']['all'].")".$this->color[2]."\n";
        }
        $ausgabe .= $this->color[0]."Erstellt am: ".$this->color[1].date("Y.m.d").$this->color[0]." um: ".$this->color[1].date("H:i:s");

        $this->ausgabe = '<textarea cols="30" rows="2" readonly>'.$ausgabe.'</textarea>';
    }

    private function produktion($paste)
    {
        $nick = $this->color[1].$this->aktUser->getNick();
        $koords = $this->color[1].$this->aktUser->getKoords();
        $cost['all']['met'] = 0;
        $cost['all']['kris'] = 0;

        $ausgabe = $this->color[0]."Produktion in den Schiffswerften von ".$nick.$this->color[0]." (";
        $ausgabe .= $this->color[1].$koords.$this->color[0]."):\n";
        for ($i0 = 2; $i0 < count($paste); $i0++) {
            if (count($paste[$i0]) != 6) { $this->error = "Fehler in der Analyse."; return false; }
            if (preg_match('/%/', $paste[$i0][5])) {
                $paste[$i0][0] = preg_split('/-/', $paste[$i0][0]);
                $paste[$i0][1] = preg_split('/-/', $paste[$i0][1]);
                $anzahl = preg_replace('/\D/', '', $paste[$i0][3]);
                if ($anzahl > 1) {
                    $paste[$i0][0][1] = preg_replace(array('/(\wff) /', '/(Fregatte) /'), array('$1e ', '$1n '), $paste[$i0][0][1]);
                }
                $typ = preg_replace('/Klasse: (\w+)/', '$1', $paste[$i0][0][1]);
                $typ = trim($typ);
                $cost['met'] = preg_replace('/\D/', '', $paste[$i0][1][0]) * $anzahl;
                $cost['kris'] = preg_replace('/\D/', '', $paste[$i0][1][1]) * $anzahl;
                $cost['all']['met'] = $cost['all']['met'] + $cost['met'];
                $cost['all']['kris'] = $cost['all']['kris'] + $cost['kris'];
                $cost['met'] = number_format($cost['met'], 0, ",", ".");
                $cost['kris'] = number_format($cost['kris'], 0, ",", ".");
                $anzahl = number_format($anzahl, 0, ",", ".");
                $advance = trim($paste[$i0][5]);

                $ausgabe .= $this->color[3].$anzahl." ".$this->color[2].$typ." in Bau - Kosten: ".$this->color[3].$cost['met'];
                $ausgabe .= $this->color[2]." Metall, ".$this->color[3].$cost['kris'];
                $ausgabe .= $this->color[2]." Kristall - Fortschritt: ".$this->color[3].$advance."\n";
            }
        }
        $cost['all']['met'] = number_format($cost['all']['met'], 0, ",", ".");;
        $cost['all']['kris'] = number_format($cost['all']['kris'], 0, ",", ".");;
        $ausgabe .= $this->color[0]."Gesammter Verbrauch: ".$this->color[1].$cost['all']['met'].$this->color[0]." Metall - ";
        $ausgabe .= $this->color[1].$cost['all']['kris'].$this->color[0]." Kristall";

        $this->ausgabe = '<textarea cols="30" rows="2" readonly>'.$ausgabe.'</textarea>';
    }

    private function forschung($paste, $typ)
    {
        $nick = $this->color[1].$this->aktUser->getNick();
        $koords = $this->color[1].$this->aktUser->getKoords();
        $cost['all']['met'] = 0;
        $cost['all']['kris'] = 0;

        if ($typ == 1) { $typ = "Forschung"; } else { $typ = "Konstruktion"; }
        $ausgabe = $this->color[0].$typ." von ".$nick.$this->color[0]." (".$this->color[1].$koords.$this->color[0]."):\n";

        foreach ($paste as $key => $value) {
            if ($key > 1) {
                if (count($value) != 5) { $this->error = "Fehler in der Analyse."; return false; }
                $vortschritt = trim($value[3]);
                if (preg_match('/\d+? %/', $vortschritt)) {
                    $typ = trim($value[0]);
                    $value[4] = preg_split('/-/', $value[4]);
                    $cost['met'] = preg_replace('/\D/', '', $value[4][0]);
                    $cost['kris'] = preg_replace('/\D/', '', $value[4][1]);
                    $cost['all']['met'] = $cost['all']['met'] + $cost['met'];
                    $cost['all']['kris'] = $cost['all']['kris'] + $cost['kris'];
                    $cost['met'] = number_format($cost['met'], 0, ",", ".");
                    $cost['kris'] = number_format($cost['kris'], 0, ",", ".");
                    $advance = trim($value[3]);

                    $ausgabe .= $this->color[2].$typ." - Kosten: ".$this->color[3].$cost['met'];
                    $ausgabe .= $this->color[2]." Metall, ".$this->color[3].$cost['kris'];
                    $ausgabe .= $this->color[2]." Kristall - Fortschritt: ".$this->color[3].$advance."\n";
                }
            }
        }
        $cost['all']['met'] = number_format($cost['all']['met'], 0, ",", ".");;
        $cost['all']['kris'] = number_format($cost['all']['kris'], 0, ",", ".");;
        $ausgabe .= $this->color[0]."Gesammter Verbrauch: ".$this->color[1].$cost['all']['met'].$this->color[0]." Metall - ";
        $ausgabe .= $this->color[1].$cost['all']['kris'].$this->color[0]." Kristall";

        $this->ausgabe = '<textarea cols="30" rows="2" readonly>'.$ausgabe.'</textarea>';
    }

    private function verteidigung($paste)
    {
        $nick = $this->color[1].$this->aktUser->getNick();
        $koords = $this->color[1].$this->aktUser->getKoords();
        $cost['all']['met'] = 0;
        $cost['all']['kris'] = 0;

        $ausgabe = $this->color[0]."Produktion der Verteidigungseinheiten von ".$nick.$this->color[0]." (";
        $ausgabe .= $this->color[1].$koords.$this->color[0]."):\n";
        for ($i0 = 2; $i0 < count($paste); $i0++) {
            if (count($paste[$i0]) != 6) { $this->error = "Fehler in der Analyse."; return false; }
            if (preg_match('/%/', $paste[$i0][5])) {
                $paste[$i0][0] = preg_split('/-/', $paste[$i0][0]);
                $paste[$i0][1] = preg_split('/-/', $paste[$i0][1]);
                $anzahl = preg_replace('/\D/', '', $paste[$i0][3]);
                $typ = preg_replace('/Typ: (\w+)/', '$1', $paste[$i0][0][0]);
                $typ = trim($typ);
                $cost['met'] = preg_replace('/\D/', '', $paste[$i0][1][0]) * $anzahl;
                $cost['kris'] = preg_replace('/\D/', '', $paste[$i0][1][1]) * $anzahl;
                $cost['all']['met'] = $cost['all']['met'] + $cost['met'];
                $cost['all']['kris'] = $cost['all']['kris'] + $cost['kris'];
                $cost['met'] = number_format($cost['met'], 0, ",", ".");
                $cost['kris'] = number_format($cost['kris'], 0, ",", ".");
                $anzahl = number_format($anzahl, 0, ",", ".");
                $advance = trim($paste[$i0][5]);

                $ausgabe .= $this->color[3].$anzahl." ".$this->color[2].$typ." in Bau - Kosten: ".$this->color[3].$cost['met'];
                $ausgabe .= $this->color[2]." Metall, ".$this->color[3].$cost['kris'];
                $ausgabe .= $this->color[2]." Kristall - Fortschritt: ".$this->color[3].$advance."\n";
            }
        }
        $cost['all']['met'] = number_format($cost['all']['met'], 0, ",", ".");;
        $cost['all']['kris'] = number_format($cost['all']['kris'], 0, ",", ".");;
        $ausgabe .= $this->color[0]."Gesammter Verbrauch: ".$this->color[1].$cost['all']['met'].$this->color[0]." Metall - ";
        $ausgabe .= $this->color[1].$cost['all']['kris'].$this->color[0]." Kristall";

        $this->ausgabe = '<textarea cols="30" rows="2" readonly>'.$ausgabe.'</textarea>';
    }
}

?>
