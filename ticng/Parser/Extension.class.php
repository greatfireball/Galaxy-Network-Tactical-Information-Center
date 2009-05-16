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
// Klasse der Firefox Extension vom Parser
//
//

// --------------------------------------------------------------------------------- //

class _extension
{
    public $error = false;
    public $ausgabe = false;
    public $close = false;

    public function __construct($paste)
    {
        global $tic;

        $paste = urldecode($paste);
        $pattern = array('/&nbsp;/');
        $replace = array(' ');
        $paste = preg_replace($pattern, $replace, $paste);

        // Was wurde uebergeben?
        if (preg_match('/flottentransfer/i', $paste)) { if (!$this->flottentransfer($paste)) { return false; } }
        elseif (preg_match('/flottenbewegungen/i', $paste)) { if (!$this->taktik($paste)) { return false; } }
        if (preg_match('/galaxiemitglieder/i', $paste)) { if (!$this->galaScans($paste)) { return false; } }
        if (preg_match('/ergebnis \(genauigkeit/i', $paste)) { if (!$this->scans($paste)) { return false; } }
        if (preg_match('/flottenzusammensetzung <.*>von/iU', $paste)) { if (!$this->geschMili($paste)) { return false; } }
        if (preg_match('/galaxieansicht/i', $paste)) { if (!$this->galaxiePlayer($paste)) { return false; } }

        if ($this->error == null && $tic->isError() === false) { $this->close = true; }
    }

    private function taktik($paste)
    {
        $pattern = array(
            '/^.*<td>zeit<\/td><\/tr>/siU',                                 // 1
            '/ \*/',                                                        // 2
            '/<\/tr>[\r\n]+<tr>.*$/is',                                     // 3
            '/<[\/]{0,1}(span|nobr|a).*>/iU',                               // 4
            '/[\r\n]+/',                                                    // 5
            '/ +/',                                                         // 6
            '/(\d+:\d+)<\/td>\s*<td class\=".*">([\w\(\)\.:\-_]+)/iU',      // 7
            '/<br>(r.*ckflug)/iU',                                          // 8
            '/(r.*ckflug)\s{0,1}<br>/iU',                                   // 9
            '/(\d+:\d+) /',                                                 // 10
            '/[\(\)]/',                                                     // 11
            '/<br>/',                                                       // 12
            '/ class\=".*"/iU',                                             // 13
            '/ Min/',                                                       // 14
            '/ (\|[SEMGN])+\|/'                                             // 15
        );
        $replace = array(
            '',             // 1
            '',             // 2
            '',             // 3
            '',             // 4
            '',             // 5
            ' ',            // 6
            '$1-::-$2',     // 7
            "\r$1",         // 8
            '$1-::-',       // 9
            '$1-::-',       // 10
            '',             // 11
            "\r",           // 12
            '',             // 13
            'm',            // 14
            ''              // 15
        );
        $paste = preg_replace($pattern, $replace, $paste);
        $paste = preg_split('/<\/tr>/i', $paste);

        for ($i0 = 0; $i0 < count($paste); $i0++) {
            $paste[$i0] = preg_split('/<\/td>\s*<td>/i', $paste[$i0]);
            for ($i1 = 0; $i1 < count($paste[$i0]); $i1++) {
                $paste[$i0][$i1] = preg_replace('/<.*>/U', '', $paste[$i0][$i1]);
                $paste[$i0][$i1] = trim($paste[$i0][$i1]);
            }
        }

        $taktik = new _galaTaktik(false);
        $taktik->auswertung($paste);
        if ($taktik->ausgabe != "") { $this->ausgabe = $taktik->ausgabe; }
        if ($taktik->error != "") { $this->error = $taktik->error; }

        return true;
    }

    private function galaScans($paste)
    {
        global $tic;

        $pattern = array(
            '/[\r\n]+/',        // 1
            '/ \*/',            // 2
            '/.*Asteroiden/',   // 3
            '/Gesamt:.*/',      // 4
            '/<td.*>/iU',       // 5
            '/<\/td.*>/iU',     // 6
            '/<.*>/U',          // 7
            '/\t/',             // 8
            '/ \/ /',           // 9
            '/ +/'              // 10
        );
        $replace = array(
            ' ',    // 1
            '',     // 2
            '',     // 3
            '',     // 4
            ' ',    // 5
            ' ',    // 6
            '',     // 7
            ' ',    // 8
            ' ',    // 9
            ' '     // 10
        );
        $paste = preg_replace($pattern, $replace, $paste);
        $paste = preg_split('/ /', trim($paste));

        $sekNamen = $tic->mod['Konst']->getSektorNamen();
        for ($i0 = 0; $i0 < count($paste); $i0 = $i0 + 8) {
            $scan = array(
                'gen' => 100,
                'nick' => $paste[$i0 + 1],
                'gala' => preg_replace('/(\d+):(\d+)/', '$1', $paste[$i0]),
                'planet' => preg_replace('/(\d+):(\d+)/', '$2', $paste[$i0]),
                'birth' => SCAN_BIRTH_FFEXT
            );
            foreach ($sekNamen as $key => $value) {
                $sek[$value[0]] = preg_replace('/\D/', '', $paste[$i0 + 2 +$key]);
            }
            if (!$tic->mod['Scan']->addSekScan($scan, $sek)) {
                $this->error = "Die Sektorscans konnten nicht erfolgreich gespeichert werden!!!";
                return false;
            }
        }

        return true;
    }

    private function scans($paste)
    {
        //preg_match('/anzahl eigener scanverst.*rker:<\/td>.*<td.*>(\d+)</isU', $paste, $match);
        //$svs = $match[1];
        $pattern = array(
            '/^.*(\w+ ergebnis \(genauigkeit)/isU',     // 1
            '/(.*)<\/table>.*$/isU',                    // 2
            '/(<\w+) .*(>)/U',                          // 3
            '/(flug) (.)/',                             // 4
            '/\s/',                                     // 5
            '/<\/{0,1}a.*>/iU'                          // 6
        );
        $replace = array(
            '$1',       // 1
            '$1',       // 2
            '$1$2',     // 3
            '$1_$2',    // 4
            '',         // 5
            ''          // 6
        );
        $paste = preg_replace($pattern, $replace, $paste);
        $paste = preg_split('/<\/tr><tr>/i', trim($paste));

        for ($i0 = 0; $i0 < count($paste); $i0++) {
            $paste[$i0] = preg_replace(array('/^<td>/iU', '/<\/td>$/iU'), '', $paste[$i0]);
            $paste[$i0] = preg_split('/<\/td><td>/i', $paste[$i0]);
            for ($i1 = 0; $i1 < count($paste[$i0]); $i1++) {
                $paste[$i0][$i1] = preg_replace('/<.*>/U', '', $paste[$i0][$i1]);
            }
        }

        $scan = $this->scanHeader($paste);
        $scanSave = new _scanSave(false);
        $scanSave->scan = $scan;

        if (preg_match('/sektorscan/i', $paste[0][0])) {
            $scanSave->sektor($paste);
        } elseif (preg_match('/einheitenscan/i', $paste[0][0])) {
            $scanSave->einheiten($paste, 0);
        } elseif (preg_match('/rscan/i', $paste[0][0])) {
            $scanSave->militaer($paste, 1);
        } elseif (preg_match('/tzscan/i', $paste[0][0])) {
            $scanSave->geschuetz($paste, 0);
        } elseif (preg_match('/newsscan/i', $paste[0][0])) {
            $this->newsScan($paste, $scan);
        } else {
            $this->error = "Scanerkennung fehlgeschlagen!!!";
            return false;
        }

        return true;
    }

    private function scanHeader($paste)
    {
        $scan = array(
            'gen' => preg_replace('/\D/', '', $paste[0][0]),
            'nick' => $paste[1][1],
            'gala' => preg_replace('/(\d+):(\d+)/', '$1', $paste[2][1]),
            'planet' => preg_replace('/(\d+):(\d+)/', '$2', $paste[2][1]),
            'birth' => SCAN_BIRTH_FFEXT
        );
        return $scan;
    }

    private function newsScan($paste)
    {
        global $tic;

        print_r($paste);
    }

    private function geschMili($paste)
    {
        global $tic;

        $paste = trim($paste);
        $pattern = array('/.*flottenzusammensetzung/is', '/[\r\n]/', '/(<\w+) .*(>)/U', '/.*flotte2<\/td><\/tr><tr>/iU', '/<\tr><\/tbody>.*$/');
        $replace = array('', '', '$1$2', '', '');
        $paste = preg_replace($pattern, $replace, $paste);

        preg_match('/schiffstyp<\/td>(.*)<\/tr><\/tbody>/iU', $paste, $match);
        $schiffe = $match[1];
        preg_match('/einheitentyp<\/td>(.*)<\/tr><\/tbody>/iU', $paste, $match);
        $geschuetze = $match[1];
        preg_match('/\w+ \(\d+:\d+\)/iU', $paste, $match);
        $user = preg_replace('/[\(\)]/', '', $match[0]);

        $schiffe = preg_split('/<\/tr><tr>/i', trim($schiffe));
        $geschuetze = preg_split('/<\/tr><tr>/i', trim($geschuetze));
        $user = preg_split('/ /', trim($user));

        $schiffNamen = $tic->mod['Konst']->getSchiffNamen();
        $geschNamen = $tic->mod['Konst']->getGeschNamen();
        $scan = array(
            'gen' => 100,
            'nick' => trim($user[0]),
            'gala' => preg_replace('/(\d+):(\d+)/', '$1', $user[1]),
            'planet' => preg_replace('/(\d+):(\d+)/', '$2', $user[1]),
            'birth' => SCAN_BIRTH_FFEXT
        );

        for ($i0 = 1; $i0 < count($schiffe); $i0++) {
            $schiffe[$i0] = preg_split('/<\/td><td>/i', $schiffe[$i0]);
            for ($i1 = 1; $i1 < 4; $i1++) {
                $schiffe[$i0][$i1] = preg_replace('/<.*>/U', '', $schiffe[$i0][$i1]);
            }
            $mili[$schiffNamen[$i0 - 1][0]][0] = $schiffe[$i0][1];
            $mili[$schiffNamen[$i0 - 1][0]][1] = $schiffe[$i0][2];
            if (is_numeric($schiffe[$i0][3])) { $mili[$schiffNamen[$i0 - 1][0]][2] = $schiffe[$i0][3]; }
        }

        for ($i0 = 1; $i0 < count($geschuetze); $i0++) {
            $geschuetze[$i0] = preg_split('/<\/td><td>/i', $geschuetze[$i0]);
            for ($i1 = 1; $i1 < 2; $i1++) {
                $geschuetze[$i0][$i1] = preg_replace('/<.*>/U', '', $geschuetze[$i0][$i1]);
            }
            $gesch[$geschNamen[$i0 - 1][0]] = $geschuetze[$i0][1];
        }

        if (!$tic->mod['Scan']->addGeschScan($scan, $gesch)) {
            $this->error = "Der Gesch&uuml;tzscan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }
        if (!$tic->mod['Scan']->addMiliScan($scan, $mili, false)) {
            $this->error = "Der Milit&auml;rsan konnte nicht erfolgreich gespeichert werden!!!";
            return false;
        }

        return true;
    }

    private function galaxiePlayer($paste)
    {
        global $tic;

        $pattern = array(
            '/.*(id<\/td>\s*<td>rang<\/td>\s*<td>nick)/siU',    // 1
            '/<(td|tr) .*>/isU',                                // 2
            '/<a>M<\/a>/',                                      // 3
            '/<(\/){0,1}a.*>/U',                                // 4
            '/<(\/){0,1}span.*>/iU',                            // 5
            '/<\/tr><\/tbody>.*/si',                            // 6
            '/\s/'                                              // 7
        );
        $replace = array(
            '$1',       // 1
            '<$1>',     // 2
            '',         // 3
            '',         // 4
            '',         // 5
            '',         // 6
            ''          // 7
        );
        $paste = preg_replace($pattern, $replace, $paste);

        $paste = preg_split('/<\/tr><tr>/', $paste);
        unset($paste[0]);
        foreach ($paste as $key => $value) {
            $value = preg_split('/<\/td><td>/', $value);
            $koord = preg_replace('/[^:\d]/', '', $value[0]);
            $koord = preg_split('/:/', $koord);

            $player = new GNPlayer($koord[0], $koord[1], $value[2]);
            $player->create();
        }

        return true;
    }

    private function flottentransfer($paste)
    {
        $pattern = array('/<(tr|td).*>/iU', '/<\/{0,1}(form|input|option|tbody).*>/iU');
        $replace = array('<$1>', '');
        $paste = preg_replace($pattern, $replace, $paste);

        $pattern = array('/\s/', '/.*<b>(flotten<\/b>)/is', '/(schutzschiff.*<\/tr>).*$/isU', '/.*(schiffstyp)/is');
        $replace = array('', '$1', '$1', '$1');
        $flotten = preg_replace($pattern, $replace, $paste);
        $flotten = preg_split('/<\/tr><tr>/', $flotten);

        $pattern = array(
            '/.*<b>(flottenstatus<\/b>)/is',    // 1
            '/(.*<\/div>).*$/isU',              // 2
            '/.*(flotte 1)/is',                 // 3
            '/[\r\n\t]/',                       // 4
            '/ +/',                             // 5
            '/(\d+:\d+) (.*) /U',
            '/(eta) (\d+) (ticks)/i',
            '/(sektor) (\d+) (ticks)/i'
        );
        $replace = array(
            '$1',   // 1
            '$1',   // 2
            '$1',   // 3
            '',     // 4
            ' ',    // 5
            '$1:||:$2',
            '$1-$2-$3',
            '$1-$2-$3'
        );
        $transfer = preg_replace($pattern, $replace, $paste);
        $transfer = preg_split('/<\/tr><tr>/', $transfer);

        $this->auswertungTransfer($transfer);
        $this->auswertungFlotten($flotten);

        return true;
    }

    private function auswertungFlotten($flotten)
    {
        global $tic;

        $schiffNamen = $tic->mod['Konst']->getSchiffNamen();

        $len0 = count($flotten);
        for ($i0 = 0; $i0 < $len0; $i0++) {
            $flotten[$i0] = preg_split('/<\/td><td>/', $flotten[$i0]);
            for ($i1 = 0; $i1 < count($flotten[$i0]); $i1++) {
                $flotten[$i0][$i1] = preg_replace('/<.*>/U', '', $flotten[$i0][$i1]);
            }
        }
        unset($flotten[0]);

        foreach ($schiffNamen as $key0 => $value0) {
            foreach ($flotten as $key1 => $value1) {
                if (preg_match('/'.strtolower($value0[2]).'/', strtolower($value1[0]))) {
                    for ($i0 = 0; $i0 <= 2; $i0++) {
                        $mili[$value0[0]][$i0] = preg_replace('/\D/', '', $value1[$i0 + 1]);
                    }
                    break 1;
                }
            }
            if (!isset($mili[$value0[0]])) {
                $mili[$value0[0]][0] = 0;
                $mili[$value0[0]][1] = 0;
                $mili[$value0[0]][2] = 0;
            }
        }

        $aktUser = $tic->mod['Auth']->getActiveUser();
        $scan = array(
            'gala' => $aktUser->getGalaxie(),
            'planet' => $aktUser->getPlanet(),
            'nick' => $aktUser->getNick(),
            'gen' => '100',
            'birth' => SCAN_BIRTH_FFEXT,
        );

        $tic->mod['Scan']->addMiliScan($scan, $mili, false);
    }

    private function auswertungTransfer($transfer)
    {
        global $tic;

        $flotte[1]['string'] = $transfer[0];
        $flotte[2]['string'] = $transfer[2];

        foreach ($flotte as $key => $value) {
            $value['angriff'] = false;
            $value['rueck'] = false;
            if (preg_match('/angriffsflug/i', $value['string'])) {
                $value['typ'] = 1;
                $value['angriff'] = true;
            } elseif (preg_match('/verteidigungsflug/i', $value['string'])) {
                $value['typ'] = 2;
            } elseif (preg_match('/r.*ckflug/iU', $value['string'])) {
                $value['typ'] = 3;
                $value['rueck'] = true;
            } else {
                $value['typ'] = 0;
            }
            if ($value['typ'] != 0) {
                preg_match_all('/(\d+):(\d+):\|\|:(.*) /U', $value['string'], $match);
                $s = 0; $z = 1;
                if ($value['rueck'] == true) { $s = 1; $z = 0; }
                $value['startGala'] = $match[1][$s];
                $value['startPlanet'] = $match[2][$s];
                $value['startNick'] = $match[3][$s];
                $value['zielGala'] = $match[1][$z];
                $value['zielPlanet'] = $match[2][$z];
                $value['zielNick'] = $match[3][$z];
                preg_match('/eta\-(\d+)\-/i', $value['string'], $match);
                $value['eta'] = $match[1];
                preg_match('/sektor\-(\d+)\-/i', $value['string'], $match);
                if (isset($match[1])) { $value['dauer'] = $match[1]; } else { $value['dauer'] = false; }
                $fbewegung = new Flottenbewegung($value['startGala'], $value['startPlanet'], $key, $value['zielGala'], $value['zielPlanet'],
                    $value['angriff'], $value['rueck'], false, $value['dauer'], $value['eta']);
                $tic->mod['Taktik']->updateFlotte($fbewegung);

                $player = new GNPlayer($value['startGala'], $value['startPlanet'], $value['startNick']);
                $player->create();
                $player = new GNPlayer($value['zielGala'], $value['zielPlanet'], $value['zielNick']);
                $player->create();
            }
        }
    }
}

?>