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
// Scanklasse vom Parser
//
//

// --------------------------------------------------------------------------------- //

class _galaScans
{
    public $error = false;
    public $ausgabe = false;
    private $c0 = 0;
    private $m0 = 0;
    private $d0 = 0;

    public function __construct($paste)
    {
        global $tic;

        $aktUser = $tic->mod['Auth']->getActiveUser();

        $muster = array('/\t/', '/ +/', '/\(/', '/\)/', '/ \*/');
        $replace = array(' ', ' ', '', '', '');
        $splitStr = $paste;
        $paste = array();
        $user = array();
        $start = false;
        foreach (preg_split('/\n/', $splitStr) as $key => $value) {
            if (trim($value) != "") {
                $value = preg_replace($muster, $replace, trim($value));
                $value = preg_split('/ /', $value);
                if (preg_match('/(Galaxiemitglieder)/', $value[0])) { $start = true; }
                if (preg_match('/[0-9]{1,4}:[0-9]{1,2}/', $value[0]) && $start == true) {
                    $array = $this->sekSave($value);
                    array_push($user, $array);
                    array_push($paste, $value);
                    $this->manageUser($array['nick'], $array['gala'], $array['planet']);
                }
            }
        }

        if (!$aktUser->getAllianz() && $aktUser->_gnRang > 0) {
            $galaUsers = $aktGala->getUsers();
            foreach ($galaUsers as $value) {
                for ($i0 = 0; $i0 < count($user); $i0++) {
                    $delete = false;
                    if ($value->getNick() == $user[$i0]['nick']) {
                        $delete = true;
                        break 1;
                    }
                }
                if (!$delete) {
                    $_POST['user_del'][$this->d0]['nick'] = $value->getNick();
                    $_POST['user_del'][$this->d0]['gala'] = $value->getGalaxie();
                    $_POST['user_del'][$this->d0]['planet'] = $value->getPlanet();
                    $_POST['userTPL'] = true;
                    $this->d0++;
                }
            }
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$aktUser->getGalaxie()."&planet=0";
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Scans">Scans von '.$aktUser->getGalaxie().'</a>';
    }

    private function sekSave($_scan)
    {
        global $tic;

        $scan = array(
            'gen' => 100,
            'nick' => $_scan[1],
            'gala' => preg_replace('/(\d+):(\d+)/', '$1', $_scan[0]),
            'planet' => preg_replace('/(\d+):(\d+)/', '$2', $_scan[0]),
            'birth' => SCAN_BIRTH_FFEXT
        );
        $sek = array(
            'punkte' => preg_replace('/\D/', '', $_scan[2]),
            'schiffe' => preg_replace('/\D/', '', $_scan[3]),
            'deff' => preg_replace('/\D/', '', $_scan[4]),
            'me' => preg_replace('/\D/', '', $_scan[5]),
            'ke' => preg_replace('/\D/', '', $_scan[7]),
            'ast' => preg_replace('/\D/', '', $_scan[8])
        );

        if (!$tic->mod['Scan']->addSekScan($scan, $sek, 0)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        return array('nick' => $scan['nick'], 'gala' => $scan['gala'], 'planet' => $scan['planet']);
    }

    private function manageUser($nick, $gala, $planet)
    {
        global $tic;

        $aktUser = $tic->mod['Auth']->getActiveUser();
        if ($aktUser->getAllianz() === false || $aktUser->_gnRang < 1) { return false; }
        $user = $tic->mod['UserMan']->getUserByNick($nick);
        $userByKoord = $tic->mod['UserMan']->getUserByKoords($gala, $planet);

        if (!$user) {
            if ($userByKoord) {
                $_POST['user_del'][$this->d0]['nick'] = $userByKoord->getNick();
                $_POST['user_del'][$this->d0]['gala'] = $gala;
                $_POST['user_del'][$this->d0]['planet'] = $planet;
                $this->d0++;
            }
            $_POST['user_create'][$this->c0]['nick'] = $nick;
            $_POST['user_create'][$this->c0]['gala'] = $gala;
            $_POST['user_create'][$this->c0]['planet'] = $planet;
            $_POST['userTPL'] = true;
            $this->c0++;
        } elseif ($user->getPlanet() != $planet) {
            $_POST['user_move'][$this->m0]['nick'] = $nick;
            $_POST['user_move'][$this->m0]['gala'] = $user->getGalaxie();
            $_POST['user_move'][$this->m0]['planet'] = $user->getPlanet();
            $_POST['user_move'][$this->m0]['new_gala'] = $gala;
            $_POST['user_move'][$this->m0]['new_planet'] = $planet;
            $_POST['userTPL'] = true;
            $this->m0++;
        }
    }
}

class _miliDeffSave
{
    public $error = false;
    public $ausgabe = false;
    private $paste;
    private $merkmale;

    public function __construct($paste)
    {
        $muster = array('/\t/', '/ +/', '/\(/', '/\)/');
        $replace = array(' ', ' ', '', '');
        $splitStr = $paste;
        $paste = array();
        foreach (preg_split('/\n/', $splitStr) as $key => $value) {
            if (trim($value) != "") {
                $value = preg_replace($muster, $replace, trim($value));
                $value = preg_split('/ /', $value);
                array_push($paste, $value);
            }
        }
        $this->merkmale = new _scanMerkmale();
        $this->header($paste);
    }

    private function header($paste)
    {
        $this->scan['nick'] = $paste[0][2];
        $this->scan['koords'] = $paste[0][3];
        $this->scan['gala'] = preg_replace('/([0-9]+):([0-9]+)/', '$1', $this->scan['koords']);
        $this->scan['planet'] = preg_replace('/([0-9]+):([0-9]+)/', '$2', $this->scan['koords']);
        $this->scan['gen'] = 100;
        if (!is_numeric($this->scan['gala']) ||
          !is_numeric($this->scan['planet']) ||
          $this->scan['nick'] == "") {
            $this->error = "Fehlerhafter Scankopf!!!";
            return false;
        }
        $this->miliDeff($paste);
    }

    public function miliDeff($paste)
    {
        global $tic;

        for ($i0 = 0; $i0 < 3; $i0++) {
            foreach ($this->merkmale->miliName as $key => $value) {
                $mili[$value][$i0] = preg_replace('/\D/', '', $paste[$key + 2][$i0 + 1]);
                if (!is_numeric($mili[$value][$i0])) { $this->error = "Fehlerhafter Milit&auml;rscan!!!"; return false; }
            }
        }

        if ($paste[11][0] == "Verteidigungseinheiten") {
            $wert = 3;
            foreach ($this->merkmale->geschName as $key => $value) {
                if ($key == 4) { $wert = 2; }
                $gesch[$value] = preg_replace('/\D/', '', $paste[$key + 13][$wert]);
                if (!is_numeric($gesch[$value])) { $this->error = "Fehlerhafter Gesch&uuml;tzscan!!!"; return false; }
            }
        }

        if (!$tic->mod['Scan']->addGeschScan($this->scan, $gesch, 0) || !$tic->mod['Scan']->addMiliScan($this->scan, $mili, 0, false)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Scans">Scans von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }
}

class _ircScanSave
{
    public $error = false;
    public $ausgabe = false;
    private $merkmale;
    private $paste;

    public function __construct($paste)
    {
        $muster = array(
            '/'.chr(3).'[0-9]{2},[0-9]{2}/', '/</', '/>/', '/\[/', '/\]/',
            '/\@/', '/\r/', '/\((\d+:\d+)\)/', '/flug /',
            '/Im Orbit:/', '/Orbit:/', '/Flotte 1:/', '/Flotte 2:/', '/'.chr(160).'/',
            '/'.chr(194).'/', '/Im Orbit/', '/Flotte1:/', '/Flotte2:/'
        );
        $replace = array(
            '', '', '', '', '',
            '', '', '$1', 'flug_',
            'flotte_0', 'flotte_0', 'flotte_1', 'flotte_2', ' ',
            ' ', 'im_orbit', 'flotte_1', 'flotte_2'
        );
        $paste = preg_replace($muster, $replace, $paste);
        $paste = preg_replace('/'.chr(32).'([0-9]{1,4}:[0-9]{1,2})/', '_$1', $paste);
        $paste = preg_replace('/'.chr(32).'+/', ' ', $paste);
        $paste = preg_split('/\n/', $paste);
        foreach ($paste as $key => $value) {
            $paste[$key] = preg_split('/'.chr(32).'/', $value);
        }
        $this->merkmale = new _scanMerkmale();
        $this->scanheader($paste);
    }

    private function scanheader($paste)
    {
        global $tic;

        $i = 0;
        $found = 0;
        foreach ($paste as $key0 => $value0) {
            foreach ($value0 as $value1) {
                if (preg_match('/\d{1,3}%/', $value1)) { $found++; }
                if (preg_match('/.*_\d+:\d+/U', $value1)) { $found++; }
                if ($found >= 2) { $i = $key0; break 2; }
            }
        }

        foreach ($paste[$i] as $key => $value) {
            if (!isset($this->scan['typ']) && preg_match('/.*scan/', strtolower($value))) {
                $this->scan['typ'] = $value;
                if (preg_match('/_/', $this->scan['typ'])) { $this->scan['typ'] = preg_replace('/^(.*)_.*$/', '$1', $this->scan['typ']); }
            }
            if (!isset($this->scan['gen']) && preg_match('/\d{1,3}%/', $value)) {
                $this->scan['gen'] = preg_replace('/\D/', '', $value);
            }
            if (preg_match('/.*_\d+:\d+/', $value)) {
                $this->scan['koords'] = preg_replace('/(.*)_(\d+:\d+)/', '$2', $value);
                $this->scan['gala'] = preg_replace('/(\d+):(\d+)/', '$1', $this->scan['koords']);
                $this->scan['planet'] = preg_replace('/(\d+):(\d+)/', '$2', $this->scan['koords']);
                $this->scan['nick'] = preg_replace('/(.*)_(\d+:\d+)/', '$1', $value);
                $this->scan['birth'] = SCAN_BIRTH_IRCPASTE;
                switch (strtolower($this->scan['nick'])) {
                    case "sektorscan":
                    case "geschützscan":
                    case "einheitenscan":
                    case "miliscan":
                    case "militaerscan":
                    case "militärscan":
                    case "nachrichtenscan":
                    case "newsscan":
                        $this->scan['nick'] = $paste[0][$key + 1];
                        break;
                }
            }
        }

        if (!is_numeric($this->scan['gala']) ||
          !is_numeric($this->scan['planet']) ||
          !is_numeric($this->scan['gen']) ||
          $this->scan['nick'] == "") {
            $this->error = "Fehlerhafter Scankopf!!!";
            return false;
        }

        switch (strtolower($this->scan['typ'])) {
            case "sektorscan":
                $this->sektor($paste);
                break;
            case "geschützscan":
                $this->geschuetz($paste);
                break;
            case "einheitenscan":
                $this->einheiten($paste);
                break;
            case "miliscan":
            case "militaerscan":
            case "militärscan":
                $this->militaer($paste);
                break;
            case "nachrichtenscan":
            case "newsscan":
                $this->news($paste);
                break;
            default:
                $this->error = "Konnte Scantyp nicht erkennen!!!";
                return false;
        }
    }

    private function _konvertStatus($status)
    {
        for ($i0 = 0; $i0 < 3; $i0++) {
            foreach ($this->merkmale->flottenStatus[$i0] as $value) {
                if (preg_match($value, strtolower($status))) { return $i0 + 1; }
            }
        }
        if ($status == "im") { return 0;}
        return false;
    }

    private function sektor($paste)
    {
        global $tic;

        unset($paste[0]);
        foreach ($paste as $value0) {
            foreach ($value0 as $key1 => $value1) {
                foreach ($this->merkmale->sek as $key2 => $value2) {
                    foreach ($value2 as $value3) {
                        if (isset($value0[$key1 + 1])) { $wert = preg_replace('/[^\d]/', '', $value0[$key1 + 1]); }
                        if (!isset($sek[$key2]) && preg_match('/'.$value3.'/', strtolower($value1)) &&
                          preg_match('/^\d+$/', $wert)) {
                            $sek[$key2] = $wert;
                        }
                    }
                }
            }
        }

        foreach ($sek as $value) {
            if (!is_numeric($value) || count($sek) != 6) {
                $this->error = "Es wurde warscheinlich ein Muster verwendet, dass der Parser noch nicht kennt.";
                return false;
            }
        }

        if (!$tic->mod['Scan']->addSekScan($this->scan, $sek, 2)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Sectorscan">Sektorscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    private function einheiten($paste)
    {
        global $tic;

        unset($paste[0]);
        foreach ($paste as $value0) {
            foreach ($value0 as $key1 => $value1) {
                foreach ($this->merkmale->unit as $key2 => $value2) {
                    foreach ($value2 as $value3) {
                        if (isset($value0[$key1 + 1])) { $wert = preg_replace('/[^\d]/', '', $value0[$key1 + 1]); }
                        if (!isset($unit[$key2]) && preg_match('/'.$value3.'/', strtolower($value1)) && preg_match('/^\d+$/', $wert)) {
                            $unit[$key2] = $wert;
                        }
                    }
                }
            }
        }
        foreach ($unit as $value) {
            if (!is_numeric($value) || count($unit) != 9) {
                $this->error = "Es wurde warscheinlich ein Muster verwendet, dass der Parser noch nicht kennt.";
                return false;
            }
        }

        if (!$tic->mod['Scan']->addUnitScan($this->scan, $unit, 2)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Einheitenscan">Einheitenscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    private function militaer($paste)
    {
        global $tic;

        unset($paste[0]);
        $r_mili = false;
        foreach ($paste as $key0 => $value0) {
            foreach ($value0 as $key1 => $value1) {
                if (preg_match('/flotte_[0-2]/', $value1)) {
                    $flotte = preg_replace('/[^\d]/', '', $value1);
                    foreach ($value0 as $key2 => $value2) {
                        foreach ($this->merkmale->mili as $key3 => $value3) {
                            foreach ($value3 as $value4) {
                                if (!isset($mili[$key3][$flotte]) && preg_match('/'.$value4.'/', strtolower($value2))) {
                                    if (isset($value0[$key2 - 1])) { $wert1 = preg_replace(array('/\s/', '/\./', '/\,/'), '', $value0[$key2 - 1]); }
                                    if (isset($value0[$key2 + 1])) { $wert2 = preg_replace(array('/\s/', '/\./', '/\,/'), '', $value0[$key2 + 1]); }
                                    if (preg_match('/^\d+$/', $wert1) && $r_mili == false) {
                                        $mili[$key3][$flotte] = $wert1;
                                        break 2;
                                    } elseif (preg_match('/^\d+$/', $wert2)) {
                                        $r_mili = true;
                                        $mili[$key3][$flotte] = $wert2;
                                        break 2;
                                    }
                                }
                                if (!isset($mili['flotte'][$flotte]['status'])) {
                                    if(preg_match('/flug_/', strtolower($value2)) || preg_match('/im_orbit/', strtolower($value2))) {
                                        $mili['flotte'][$flotte]['status'] = preg_replace('/(.*)_(.*)/', '$1', $value2);
                                        $mili['flotte'][$flotte]['status'] = $this->_konvertStatus($mili['flotte'][$flotte]['status']);
                                        if ($mili['flotte'][$flotte]['status'] === false) {
                                            $this->error = "Der Scan konnte nicht komplett erkannt werden!!!";
                                            return false;
                                        }
                                        $value2 = preg_replace('/_\d+:\d+/', '', $value2);
                                        $mili['flotte'][$flotte]['ziel'] = preg_replace('/(.*)_(.*)/', '$2', $value2);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($this->merkmale->mili as $key => $value) {
            for ($i0 = 0; $i0 < 3; $i0++) {
                if (!isset($mili[$key][$i0])) { $mili[$key][$i0] = 0; }
            }
        }

        if (!isset($mili['flotte'])) {
            $mili['flotte'][1]['status'] = NULL;
            $mili['flotte'][1]['ziel'] = "";
        }
        if (count($mili['flotte']) == 1) {
            $mili['flotte'][2]['status'] = NULL;
            $mili['flotte'][2]['ziel'] = "";
        }

        if (!$tic->mod['Scan']->addMiliScan($this->scan, $mili, 2)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Milit&auml;rscan">Milit&auml;rscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    private function geschuetz($paste)
    {
        global $tic;

        unset($paste[0]);
        foreach ($paste as $value0) {
            foreach ($value0 as $key1 => $value1) {
                foreach ($this->merkmale->gesch as $key2 => $value2) {
                    foreach ($value2 as $value3) {
                        if (isset($value0[$key1 + 1])) { $wert = preg_replace('/[^\d]/', '', $value0[$key1 + 1]); }
                        if (!isset($gesch[$key2]) && preg_match('/'.$value3.'/', strtolower($value1)) &&
                          preg_match('/^\d+$/', $wert)) {
                            $gesch[$key2] = $wert;
                        }
                    }
                }
            }
        }
        foreach ($this->merkmale->gesch as $key => $value) {
            if (!isset($gesch[$key])) { $gesch[$key] = 0; }
            if (!is_numeric($gesch[$key])) {
                $this->error = "Es wurde warscheinlich ein Muster verwendet, dass der Parser noch nicht kennt.";
                return false;
            }
        }

        if (!$tic->mod['Scan']->addGeschScan($this->scan, $gesch, 2)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Gesch&uuml;tzscan">Gesch&uuml;tzscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    private function news($paste)
    {
        unset($paste[0]);
        $newsTyp = -1;
        for ($i0 = 1; $i0 < (count($paste) - 1); $i0++) { // Zeile
            foreach ($paste[$i0] as $key0 => $value0) { // Teil der Zeile
                foreach ($this->merkmale->news as $key1 => $value1) { // Newstyp
                    foreach ($value1[0] as $key2 => $value2) { // Newstyp Zeile
                        if (preg_match('/'.$value2.'/', strtolower($value0))) {
                            $newsTyp = $key1;
                            $this->newsAuswertung($paste, $i0, $key0, $newsTyp);
                            break 4;
                        }
                    }
                }
            }
        }
    }

    private function newsAuswertung($paste, $startNews, $startZeile, $typ)
    {
        for ($i0 = $startNews; $i0 < (count($paste) - 1); $i0++) { // Zeile
            foreach ($paste[$i0] as $key0 => $value0) { // Teil der Zeile
                foreach ($this->merkmale->news[$typ][$key0 - $startZeile] as $key1 => $value1) { // Typ der Flottenbewegung pruefen
                    if (preg_match('/'.$value1.'/', strtolower($value0), $treffer)) {
                        $news[$i0 - $startNews][$key1] = trim($treffer[1]);
                    }
                }
            }
        }

        echo "1360:";
        print_r($news);
        echo "1362:";
        print_r($paste);
    }
}

class _scanSave
{
    public $error = false;
    public $ausgabe = false;
    public $scan;
    private $merkmale;
    private $news;

    public function __construct($paste)
    {
        $this->merkmale = new _scanMerkmale();
        if ($paste) {
            if (preg_match('/Newsscan Ergebnis/', preg_replace('/\r\n/', '', $paste))) {
                $paste = preg_replace('/(.*\r\n)+?(Newsscan Ergebnis.*)/' ,'$2', $paste);
                $this->news = $paste;
            }

            $muster = array('/\t/', '/\(Genauigkeit.([0-9]{1,3})%\)/',
                    '/\r/', '/'.chr(32).'+/',
                    '/(Im Orbit)/', '/(Flotte)(.)([0-9])/',
                    '/(flug)'.chr(32).'/'
                );
            $replace = array(' ', '$1',
                    '', ' ',
                    'im_orbit', 'flotte_$3',
                    'flug_'
                );
            $paste = preg_replace($muster, $replace, $paste);
            $paste = preg_split('/\n/', $paste);

            foreach ($paste as $key => $value) {
                $paste[$key] = preg_split('/'.chr(32).'/', $value);
            }

            $this->scan['typ'] = $paste[0][0];
            $this->scanheader($paste);
        }
    }

    private function scanheader($paste)
    {
        global $tic;

        $this->color = $tic->mod['Konst']->getDefaultIRCColors();
        $this->scan['gen'] = preg_replace('/\D/', '', $paste[0][2]);
        $this->scan['nick'] = $paste[1][1];
        $this->scan['koord'] = $paste[2][1];
        $this->scan['gala'] = preg_replace('/([0-9]+):([0-9]+)/', '$1', $paste[2][1]);
        $this->scan['planet'] = preg_replace('/([0-9]+):([0-9]+)/', '$2', $paste[2][1]);
        $this->scan['birth'] = SCAN_BIRTH_GNPASTE;
        if (!is_numeric($this->scan['gala']) ||
          !is_numeric($this->scan['planet']) ||
          !is_numeric($this->scan['gen']) ||
          $this->scan['nick'] == "") {
            $this->error = "Fehlerhafter Scankopf!!!";
            return false;
        }

        switch(strtolower($this->scan['typ'])) {
            case "sektorscan":
                $this->sektor($paste);
                break;
            case "einheitenscan":
                $this->einheiten($paste);
                break;
            case "rscan":
            case "militärscan":
                $this->militaer($paste);
                break;
            case "tzscan":
            case "geschützscan":
                $this->geschuetz($paste);
                break;
            case "newsscan":
                $this->news();
                break;
            default:
                $this->error = "Konnte Scantyp nicht erkennen!!!";
                return false;
        }
    }

    public function sektor($paste)
    {
        global $tic;

        foreach ($this->merkmale->sekName as $key => $value) {
            $sek[$value] = preg_replace('/\D/', '', $paste[$key + 3][1]);
            if (!is_numeric($sek[$value])) { $this->error = "Fehlerhafter Sektorscan!!!"; return false; }
        }

        if (!$tic->mod['Scan']->addSekScan($this->scan, $sek)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Sektorscan">Sektorscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    public function einheiten($paste, $plus = 2)
    {
        global $tic;

        foreach ($this->merkmale->miliName as $key => $value) {
            $unit[$value] = preg_replace('/\D/', '', $paste[$key + 3][1 + $plus]);
            if (!is_numeric($unit[$value])) { $this->error = "Fehlerhafter Einheitenscan!!!"; return false; }
        }

        if (!$tic->mod['Scan']->addUnitScan($this->scan, $unit)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Einheitenscan">Einheitenscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    public function militaer($paste, $plus = 3)
    {
        global $tic;

        if (isset($paste[3][2]) && ($paste[3][2] == "flotte_1" || $paste[3][2] == "Flotte1")) { $flotte1 = true; } else { $flotte1 = false; }
        if (isset($paste[3][3]) && ($paste[3][3] == "flotte_2" || $paste[3][3] == "Flotte2")) { $flotte2 = true; } else { $flotte2 = false; }

        for ($i0 = 0; $i0 < 3; $i0++) {
            foreach ($this->merkmale->miliName as $key => $value) {
                if ($i0 < 1 || ($i0 = 1 && $flotte1) || ($i0 = 2 && $flotte2)) {
                    $mili[$value][$i0] = preg_replace('/\D/', '', $paste[$key + 4][$i0 + $plus]);
                    if (!is_numeric($mili[$value][$i0])) { $this->error = "Fehlerhafter Milit&auml;rscan!!!"; return false; }
                }
            }
        }

        if ($flotte1) {
            $mili['flotte'][1]['status'] = preg_replace('/(.*)_(.*)/', '$1', $paste[13][1 + $plus]);
            $mili['flotte'][1]['ziel'] = preg_replace('/(.*)_(.*)/', '$2', $paste[13][1 + $plus]);
        } else {
            $mili['flotte'][1]['status'] = false;
            $mili['flotte'][1]['ziel'] = false;
        }
        if ($flotte2) {
            $mili['flotte'][2]['status'] = preg_replace('/(.*)_(.*)/', '$1', $paste[13][2 + $plus]);
            $mili['flotte'][2]['ziel'] = preg_replace('/(.*)_(.*)/', '$2', $paste[13][2 + $plus]);
        } else {
            $mili['flotte'][2]['status'] = false;
            $mili['flotte'][2]['ziel'] = false;
        }

        if (!$tic->mod['Scan']->addMiliScan($this->scan, $mili)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Milit&auml;rscan">Milit&auml;rscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    public function geschuetz($paste, $plus = 3)
    {
        global $tic;

        foreach ($this->merkmale->geschName as $key => $value) {
            if ($key == 4 && $plus == 3) { $plus--; }
            $gesch[$value] = preg_replace('/\D/', '', $paste[$key + 3][1 + $plus]);
            if (!is_numeric($gesch[$value])) { $this->error = "Fehlerhafter Gesch&uuml;tzscan!!!"; return false; }
        }

        if (!$tic->mod['Scan']->addGeschScan($this->scan, $gesch)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'" title="Gesch&uuml;tzscan">Gesch&uuml;tzscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }

    public function news()
    {
        global $tic;

        $paste = array();
        $last = -2; $i0 = 0;
        foreach (preg_split('/\r\n/', $this->news) as $key => $value) {
            if (preg_match('/\] (Verteidigung$|Angriff$|Rückzug$)/', $value)) {
                $last = $key;
                $push = array();
                $match = array('/\[/', '/\]/', '/\//');
                $replace = array('', '', '-');
                $value = preg_replace($match, $replace, $value);
                $value = preg_split('/ /', $value);
                array_push($push, $value);
            }
            if ($last + 1 == $key) {
                $value = preg_replace('/ (Minuten|Ticks)/', '-$1', $value);
                $value = preg_split('/ /', $value);
                array_push($push, $value);
                $paste[$i0] = $push;
                $news[$i0]['date'] = $paste[$i0][0][0];
                $news[$i0]['time'] = $paste[$i0][0][1];
                $news[$i0]['typ_txt'] = $paste[$i0][0][2];
                switch ($paste[$i0][0][2]) {
                case "Angriff":
                    $news[$i0]['typ'] = 1;
                    $news[$i0]['koords'] = $paste[$i0][1][1];
                    $news[$i0]['nick'] = $paste[$i0][1][2];
                    $news[$i0]['flotte'] = $paste[$i0][1][7];
                    $news[$i0]['eta'] = $paste[$i0][1][14];
                    break;
                case "Verteidigung":
                    $news[$i0]['typ'] = 2;
                    $news[$i0]['koords'] = $paste[$i0][1][0];
                    $news[$i0]['nick'] = $paste[$i0][1][1];
                    $news[$i0]['flotte'] = $paste[$i0][1][8];
                    $news[$i0]['eta'] = $paste[$i0][1][18];
                    break;
                case "Rückzug":
                    $news[$i0]['typ'] = 3;
                    $news[$i0]['koords'] = $paste[$i0][1][2];
                    $news[$i0]['nick'] = $paste[$i0][1][1];
                    break;
                default:
                    $this->error = "Fehler bei der Scananalyse!!!";
                    return false;
                }
                $i0++;
            }
        }

        if (!isset($news)) {
            $this->error = "Im Newsscan waren keine rellewanetn Daten vorhanden.";
            return false;
        }
        if (!$tic->mod['Scan']->addNewsScan($this->scan, $news, 0)) {
            $this->error = "Der Scan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        $adr = $tic->mod['Core']->getTICAdr();
        $this->ausgabe = $adr."?mod=Scan&scanSearch=1&gala=".$this->scan['gala']."&planet=".$this->scan['planet'];
        $this->ausgabe = '<a href="'.$this->ausgabe.'#news" title="Newsscan">Newsscan von '.$this->scan['gala'].':'.$this->scan['planet'].'</a>';
    }
}

class _scanMerkmale
{
    public $sek = array();
    public $sekName = array();
    public $gesch = array();
    public $geschName = array();
    public $unit = array();
    public $unitName = array();
    public $mili = array();
    public $miliName = array();
    public $flottenStatus = array();

    public function __construct()
    {
        global $tic;

        $schiffGesch = $tic->mod['Konst']->getSchiffGeschNamen();
        $sektorNamen = $tic->mod['Konst']->getSektorNamen();

        foreach ($schiffGesch as $key => $value) {
            if ($key < 6) { $this->sekName[$key] = $sektorNamen[$key][0]; }
            if ($key < 9) {
                $this->unitName[$key] = $value[0];
            } else {
                $this->geschName[$key - 9] = $value[0];
            }
        }

        $this->miliName = $this->unitName;

        // Die Rheinfolge bie den Scans ist zu beachten,
        // da sonst z.B. Kreuzer als Zerstörer erkannt werden durch "zer".

        $this->sek = array(
            $this->sekName[0] => array("punkte", "pkt"),
            $this->sekName[1] => array("schiffe", "schiffsanzahl"),
            $this->sekName[2] => array("gesch.*tze", "deff", "deffensiv", "verteidigung", "gesch.*zanzahl"),
            $this->sekName[3] => array("metall-extrakoren", "metallextraktoren", "metallexen", "metall-exen", "m-extraktoren", "m-exxen", "mextrakoren", "mexxen", "m-exen", "me"),
            $this->sekName[4] => array("kristall-extrakoren", "kristallextraktoren", "kristall-exen", "kristallexen", "k-extraktoren", "k-exxen", "kextrakoren", "kexxen", "k-exen", "ke"),
            $this->sekName[5] => array("asteroiden", "ast", "astr", "astros")
        );
        $this->gesch = array(
            $this->geschName[0] => array("rubium", "lo"),
            $this->geschName[1] => array("pulsar", "lr"),
            $this->geschName[2] => array("coon", "mr"),
            $this->geschName[3] => array("centurion", "sr"),
            $this->geschName[4] => array("horus", "abfangjäger", "aj")
        );
        $this->unit = array (
            $this->unitName[0] => array("j.*?ger", "jaeger", "leo", "j.*?g"),
            $this->unitName[1] => array("bomber", "aquilae", "bom"),
            $this->unitName[2] => array("fregatten", "fornax", "freggis", "freggs", "friggs", "fregs", "freg", "fre"),
            $this->unitName[4] => array("kreuzer", "goron", "kreu", "kre"),
            $this->unitName[3] => array("zerst.*?rer", "draco", "dessis", "zerris", "zer"),
            $this->unitName[5] => array("schlachtschiffe", "pentalin", "schlachter", "schl"),
            $this->unitName[6] => array("tr.*?gerschiffe", "zenit", "tr.*?ger", "traeger", "tr"),
            $this->unitName[7] => array("kaperschiffe", "cleptor", "cleptoren", "kleptoren", "klepper", "cleps", "kleps", "kap"),
            $this->unitName[8] => array("schutzschiffe", "cancri", "cancris", "sch.*?tzis", "schu", "cancs", "cans")
        );
        $this->mili = $this->unit;
        $this->flottenStatus = array(
            array('/angriff/', '/att/'),  // Angriffsflug = 1
            array('/verteidigung/', '/deff/'),  // Verteidigungsflug = 2
            array('/r.*?ck/')         // Rueckflug = 3
        );

        $this->news = array(
            array(
                array(1 => "(angriff)", 2 => "(verteidigung)", 3 => "(rückzug)"),
                array(
                    'datum' => "([0-9]{2}\/[0-9]{2}-[0-9]{4})_",
                    'zeit' => "_([0-9]{2}:[0-9]{2}:[0-9]{2})_",
                    'koords' => "_([0-9]{1,4}:[0-9]{1,2}$)"
                ),
                array('nick' => "(.*)"),
                4 => array('flotte' => "(^[0-9])")
            ),
            array(
                array(1 => "(angr)", 2 => "(vert)", 3 => "(rück)"),
                array(
                    'datum' => "([0-9]{2}\.[0-9]{2}\.[0-9]{4})_",
                    'zeit' => "_([0-9]{2}:[0-9]{2}:[0-9]{2})",
                ),
                array(
                    'nick' => "(.*)_",
                    'koords' => "_([0-9]{1,4}:[0-9]{1,2}$)"),
                5 => array('flotte' => "(^[0-9])")
            )
        );
    }
}

?>