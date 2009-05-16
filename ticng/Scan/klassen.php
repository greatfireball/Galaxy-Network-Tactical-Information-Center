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
// Scanklassen
//
//

// --------------------------------------------------------------------------------- //

class _scanHeader
{
    private $id, $time, $percent, $zielGala, $zielPlanet, $zielNick, $scannerGala, $scannerPlanet, $scannerNick, $birth, $type;

    public function __construct($array)
    {
        global $tic;

        $this->id = $array[0];
        $this->time = $array[1];
        $this->percent = $array[2];
        $this->zielGala = $array[3];
        $this->zielPlanet = $array[4];
        $this->scannerGala = $array[5];
        $this->scannerPlanet = $array[6];
        $this->birth = $array[7];
        $this->type = $array[8];
        $nick = $tic->mod['UserMan']->getPlayerByKoords($this->zielGala, $this->zielPlanet);
        $this->zielNick = $nick->getNick();
        $nick = $tic->mod['UserMan']->getPlayerByKoords($this->scannerGala, $this->scannerPlanet);
        $this->scannerNick = $nick->getNick();
    }

    public function getID()
    {
        return $this->id;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getGen()
    {
        return $this->percent;
    }

    public function getZielGala()
    {
        return $this->zielGala;
    }

    public function getZielPlanet()
    {
        return $this->zielPlanet;
    }

    public function getZielNick()
    {
        return $this->zielNick;
    }

    public function getScannerGala()
    {
        return $this->scannerGala;
    }

    public function getScannerPlanet()
    {
        return $this->scannerPlanet;
    }

    public function getScannerNick()
    {
        return $this->scannerNick;
    }

    public function getBirth()
    {
        return $this->birth;
    }

    public function getType()
    {
        return $this->type;
    }
}

class sekScan extends _scanHeader
{
    private $punkte, $schiffe, $deff, $me, $ke, $ast;

    public function __construct($array)
    {
        parent::__construct(array($array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8]));
        $this->punkte = $array[9];
        $this->schiffe = $array[10];
        $this->deff = $array[11];
        $this->me = $array[12];
        $this->ke = $array[13];
        $this->ast = $array[14];
    }

    public function getPunkte()
    {
        return $this->punkte;
    }

    public function getSchiffe()
    {
        return $this->schiffe;
    }

    public function getDeff()
    {
        return $this->deff;
    }

    public function getME()
    {
        return $this->me;
    }

    public function getKE()
    {
        return $this->ke;
    }

    public function getAst()
    {
        return $this->ast;
    }
}

class unitScan extends _scanHeader
{
    private $jaeger, $bomber, $freggs, $zerris, $kreuzer, $schlachter, $traeger, $kaper, $cancs;

    public function __construct($array)
    {
        parent::__construct(array($array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8]));
        $this->jaeger = $array[9];
        $this->bomber = $array[10];
        $this->freggs = $array[11];
        $this->zerris = $array[12];
        $this->kreuzer = $array[13];
        $this->schlachter = $array[14];
        $this->traeger = $array[15];
        $this->kaper = $array[16];
        $this->cancs = $array[17];
    }

    public function getJaeger()
    {
        return $this->jaeger;
    }

    public function getBomber()
    {
        return $this->bomber;
    }

    public function getFreggs()
    {
        return $this->freggs;
    }

    public function getZerris()
    {
        return $this->zerris;
    }

    public function getKreuzer()
    {
        return $this->kreuzer;
    }

    public function getSchlachter()
    {
        return $this->schlachter;
    }

    public function getTraeger()
    {
        return $this->traeger;
    }

    public function getKaper()
    {
        return $this->kaper;
    }

    public function getCancs()
    {
        return $this->cancs;
    }
}

class miliScan extends _scanHeader
{
    private $jaeger = array();
    private $bomber = array();
    private $freggs = array();
    private $zerris = array();
    private $kreuzer = array();
    private $schlachter = array();
    private $traeger = array();
    private $kaper = array();
    private $cancs = array();
    private $flottenStatus = array();
    private $flottenZiel = array();

    public function __construct($array)
    {
        parent::__construct(array($array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8]));
        $this->jaeger = array($array[9], $array[18], $array[29]);
        $this->bomber = array($array[10], $array[19], $array[30]);
        $this->freggs = array($array[11], $array[20], $array[31]);
        $this->zerris = array($array[12], $array[21], $array[32]);
        $this->kreuzer = array($array[13], $array[22], $array[33]);
        $this->schlachter = array($array[14], $array[23], $array[34]);
        $this->traeger = array($array[15], $array[24], $array[35]);
        $this->kaper = array($array[16], $array[25], $array[36]);
        $this->cancs = array($array[17], $array[26], $array[37]);
        $this->flottenStatus = array("im", $array[27], $array[38]);
        $this->flottenZiel = array("orbit", $array[28], $array[39]);
    }

    public function getJaeger($id)
    {
        return $this->jaeger[$id];
    }

    public function getBomber($id)
    {
        return $this->bomber[$id];
    }

    public function getFreggs($id)
    {
        return $this->freggs[$id];
    }

    public function getZerris($id)
    {
        return $this->zerris[$id];
    }

    public function getKreuzer($id)
    {
        return $this->kreuzer[$id];
    }

    public function getSchlachter($id)
    {
        return $this->schlachter[$id];
    }

    public function getTraeger($id)
    {
        return $this->traeger[$id];
    }

    public function getKaper($id)
    {
        return $this->kaper[$id];
    }

    public function getCancs($id)
    {
        return $this->cancs[$id];
    }

    public function getFlottenStatus($id)
    {
        global $tic;

        if ($this->flottenStatus[$id] !== false) {
            $param = array(
                'start_gala' => $this->getZielGala(),
                'start_planet' => $this->getZielPlanet()
            );
            $flotten = $tic->mod['Taktik']->findFlotten($param);
            $status = false;
            foreach ($flotten as $value) {
                $flotte = $value->getFlotte();
                if ($flotte == $id) {
                    $angriff = $value->getAngriff();
                    $rueck = $value->getRueckflug();
                    if ($rueck) {
                        $this->flottenStatus[$id] = SCAN_FLOTTENSTATUS_RUECK;
                    } elseif ($angriff) {
                        $this->flottenStatus[$id] = SCAN_FLOTTENSTATUS_ATT;
                    } else {
                        $this->flottenStatus[$id] = SCAN_FLOTTENSTATUS_DEFF;
                    }
                    $nick = $tic->mod['UserMan']->getPlayerByKoords($value->getZielGala(), $value->getZielPlanet());
                    $this->flottenZiel[$id] = $nick->getNick();
                    break;
                } else {
                    $this->flottenZiel[$id] = "";
                    $this->flottenStatus[$id] = -1;
                }
            }
        }
        return $this->flottenStatus[$id];
    }

    public function getFlottenZiel($id)
    {
        return $this->flottenZiel[$id];
    }
}

class geschScan extends _scanHeader
{
    private $lo, $lr, $mr, $sr, $aj;

    public function __construct($array)
    {
        parent::__construct(array($array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8]));
        $this->lo = $array[9];
        $this->lr = $array[10];
        $this->mr = $array[11];
        $this->sr = $array[12];
        $this->aj = $array[13];
    }

    public function getLO()
    {
        return $this->lo;
    }

    public function getLR()
    {
        return $this->lr;
    }

    public function getMR()
    {
        return $this->mr;
    }

    public function getSR()
    {
        return $this->sr;
    }

    public function getAJ()
    {
        return $this->aj;
    }
}

class newsScan extends _scanHeader
{
    private $type, $gala, $planet, $newsTime, $fleet, $eta;

    public function __construct($array)
    {
        parent::__construct(array($array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8]));
        $this->type = $array[9];
        $this->gala = $array[10];
        $this->planet = $array[11];
        $this->newsTime = $array[12];
        $this->fleet = $array[13];
        $this->eta = $array[14];
    }

    public function getType()
    {
        return $this->type;
    }

    public function getGala()
    {
        return $this->gala;
    }

    public function getPlanet()
    {
        return $this->planet;
    }

    public function getNewsTime()
    {
        return $this->newsTime;
    }

    public function getFleet()
    {
        return $this->fleet;
    }

    public function getETA()
    {
        return $this->eta;
    }

    public function getArrivalTime()
    {
        return $this->newsTime + $this->eta * 15 * 60;
    }
}

?>