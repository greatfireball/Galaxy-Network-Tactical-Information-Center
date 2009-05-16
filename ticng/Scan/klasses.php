<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006  Pascal Gollor  <pascal@gollor.org>                           *
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
// Klassen fuer die das Modul Scan
//
//

// --------------------------------------------------------------------------------- //

class _scanHeader
{
    var $_id;
    var $_time;
    var $_percent;
    var $_zielGala;
    var $_zielPlanet;
    var $_zielNick;
    var $_scannerGala;
    var $_scannerPlanet;
    var $_scannerNick;
    var $_birth;
    var $_type;

    function _scanHeader($arr)
    {
        global $tic;

        $this->_id = $arr[0];
        $this->_time = $arr[1];
        $this->_percent = $arr[2];
        $this->_zielGala = $arr[3];
        $this->_zielPlanet = $arr[4];
        $this->_scannerGala = $arr[5];
        $this->_scannerPlanet = $arr[6];
        $this->_birth = $arr[7];
        $this->_type = $arr[8];
        $nick = $tic->mod['UserMan']->getPlayerByKoords($this->_zielGala, $this->_zielPlanet);
        $this->_zielNick = $nick->getNick();
        $nick = $tic->mod['UserMan']->getPlayerByKoords($this->_scannerGala, $this->_scannerPlanet);
        $this->_scannerNick = $nick->getNick();
    }

    function getID()
    {
        return $this->_id;
    }

    function getTime()
    {
        return $this->_time;
    }

    function getGen()
    {
        return $this->_percent;
    }

    function getZielGala()
    {
        return $this->_zielGala;
    }

    function getZielPlanet()
    {
        return $this->_zielPlanet;
    }

    function getZielNick()
    {
        return $this->_zielNick;
    }

    function getScannerGala()
    {
        return $this->_scannerGala;
    }

    function getScannerPlanet()
    {
        return $this->_scannerPlanet;
    }

    function getScannerNick()
    {
        return $this->_scannerNick;
    }

    function getBirth()
    {
        return $this->_birth;
    }

    function getType()
    {
        return $this->_type;
    }
}

class _sekScan extends _scanHeader
{
    var $_punkte;
    var $_schiffe;
    var $_deff;
    var $_me;
    var $_ke;
    var $_ast;

    function _sekScan($arr)
    {
        parent::_scanHeader(array($arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6], $arr[7], $arr[8]));
        $this->_punkte = $arr[9];
        $this->_schiffe = $arr[10];
        $this->_deff = $arr[11];
        $this->_me = $arr[12];
        $this->_ke = $arr[13];
        $this->_ast = $arr[14];
    }

    function getPunkte()
    {
        return $this->_punkte;
    }

    function getSchiffe()
    {
        return $this->_schiffe;
    }

    function getDeff()
    {
        return $this->_deff;
    }

    function getME()
    {
        return $this->_me;
    }

    function getKE()
    {
        return $this->_ke;
    }

    function getAst()
    {
        return $this->_ast;
    }
}

class _unitScan extends _scanHeader
{
    var $_jaeger;
    var $_bomber;
    var $_freggs;
    var $_zerris;
    var $_kreuzer;
    var $_schlachter;
    var $_traeger;
    var $_kaper;
    var $_cancs;

    function _unitScan($arr)
    {
        parent::_scanHeader(array($arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6], $arr[7], $arr[8]));
        $this->_jaeger = $arr[9];
        $this->_bomber = $arr[10];
        $this->_freggs = $arr[11];
        $this->_zerris = $arr[12];
        $this->_kreuzer = $arr[13];
        $this->_schlachter = $arr[14];
        $this->_traeger = $arr[15];
        $this->_kaper = $arr[16];
        $this->_cancs = $arr[17];
    }

    function getJaeger()
    {
        return $this->_jaeger;
    }

    function getBomber()
    {
        return $this->_bomber;
    }

    function getFreggs()
    {
        return $this->_freggs;
    }

    function getZerris()
    {
        return $this->_zerris;
    }

    function getKreuzer()
    {
        return $this->_kreuzer;
    }

    function getSchlachter()
    {
        return $this->_schlachter;
    }

    function getTraeger()
    {
        return $this->_traeger;
    }

    function getKaper()
    {
        return $this->_kaper;
    }

    function getCancs()
    {
        return $this->_cancs;
    }

    function getFlottenStatus($flotte)
    {
        return $this->_flottenStatus[$flotte]['status'];
    }

    function getFlottenZiel($flotte)
    {
        return $this->_flottenZiel[$flotte]['ziel'];
    }
}

class _miliScan extends _scanHeader
{
    var $_jaeger = array();
    var $_bomber = array();
    var $_freggs = array();
    var $_zerris = array();
    var $_kreuzer = array();
    var $_schlachter = array();
    var $_traeger = array();
    var $_kaper = array();
    var $_cancs = array();
    var $_flottenStatus = array();
    var $_flottenZiel = array();

    function _miliScan($arr)
    {
        parent::_scanHeader(array($arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6], $arr[7], $arr[8]));
        $this->_jaeger = array($arr[9], $arr[18], $arr[29]);
        $this->_bomber = array($arr[10], $arr[19], $arr[30]);
        $this->_freggs = array($arr[11], $arr[20], $arr[31]);
        $this->_zerris = array($arr[12], $arr[21], $arr[32]);
        $this->_kreuzer = array($arr[13], $arr[22], $arr[33]);
        $this->_schlachter = array($arr[14], $arr[23], $arr[34]);
        $this->_traeger = array($arr[15], $arr[24], $arr[35]);
        $this->_kaper = array($arr[16], $arr[25], $arr[36]);
        $this->_cancs = array($arr[17], $arr[26], $arr[37]);
        $this->_flottenStatus = array("im", $arr[27], $arr[38]);
        $this->_flottenZiel = array("orbit", $arr[28], $arr[39]);
    }

    function getJaeger($id)
    {
        return $this->_jaeger[$id];
    }

    function getBomber($id)
    {
        return $this->_bomber[$id];
    }

    function getFreggs($id)
    {
        return $this->_freggs[$id];
    }

    function getZerris($id)
    {
        return $this->_zerris[$id];
    }

    function getKreuzer($id)
    {
        return $this->_kreuzer[$id];
    }

    function getSchlachter($id)
    {
        return $this->_schlachter[$id];
    }

    function getTraeger($id)
    {
        return $this->_traeger[$id];
    }

    function getKaper($id)
    {
        return $this->_kaper[$id];
    }

    function getCancs($id)
    {
        return $this->_cancs[$id];
    }

    function getFlottenStatus($id)
    {
        global $tic;

        if (!$this->_flottenStatus[$id]) {
            $param = array(
                'start_gala' => $this->_zielGala,
                'start_planet' => $this->_zielPlanet
            );
            $flotten = $tic->mod['Taktik']->findFlotten($param);
            $status = false;
            foreach ($flotten as $value) {
                $flotte = $value->getFlotte();
                if ($flotte == $id) {
                    $angriff = $value->getAngriff();
                    $rueck = $value->getRueckflug();
                    if ($rueck) {
                        $this->_flottenStatus[$id] = 3;
                    } elseif ($angriff) {
                        $this->_flottenStatus[$id] = 1;
                    } else {
                        $this->_flottenStatus[$id] = 2;
                    }
                    $nick = $tic->mod['UserMan']->getPlayerByKoords($value->getZielGala(), $value->getZielPlanet());
                    $this->_flottenZiel[$id] = $nick->getNick();
                    break;
                } else {
                    $this->_flottenZiel[$id] = "";
                    $this->_flottenStatus[$id] = -1;
                }
            }
        }
        return $this->_flottenStatus[$id];
    }

    function getFlottenZiel($id)
    {
        return $this->_flottenZiel[$id];
    }
}

class _geschScan extends _scanHeader
{
    var $_lo;
    var $_lr;
    var $_mr;
    var $_sr;
    var $_aj;

    function _geschScan($arr)
    {
        parent::_scanHeader(array($arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6], $arr[7], $arr[8]));
        $this->_lo = $arr[9];
        $this->_lr = $arr[10];
        $this->_mr = $arr[11];
        $this->_sr = $arr[12];
        $this->_aj = $arr[13];
    }

    function getLO()
    {
        return $this->_lo;
    }

    function getLR()
    {
        return $this->_lr;
    }

    function getMR()
    {
        return $this->_mr;
    }

    function getSR()
    {
        return $this->_sr;
    }

    function getAJ()
    {
        return $this->_aj;
    }
}

class _newsScan extends _scanHeader
{
    var $_type;
    var $_gala;
    var $_planet;
    var $_newsTime;
    var $_fleet;
    var $_eta;

    function _newsScan($arr)
    {
        parent::_scanHeader(array($arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6], $arr[7], $arr[8]));
        $this->_type = $arr[9];
        $this->_gala = $arr[10];
        $this->_planet = $arr[11];
        $this->_newsTime = $arr[12];
        $this->_fleet = $arr[13];
        $this->_eta = $arr[14];
    }

    function getType()
    {
        return $this->_type;
    }

    function getGala()
    {
        return $this->_gala;
    }

    function getPlanet()
    {
        return $this->_planet;
    }

    function getNewsTime()
    {
        return $this->_newsTime;
    }

    function getFleet()
    {
        return $this->_fleet;
    }

    function getETA()
    {
        return $this->_eta;
    }

    function getArrivalTime()
    {
        return $this->_newsTime + $this->_eta * 15 * 60;
    }
}

?>