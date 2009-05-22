<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006  Andreas Hemel  <dai.shan@gmx.net>                            *
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

define('FLUGDAUER_ANGRIFF',                 30);
define('FLUGDAUER_VERTEIDIGUNG_GALA',       18);
define('FLUGDAUER_VERTEIDIGUNG_ALLI',       20);
define('FLUGDAUER_VERTEIDIGUNG_META',       22);
define('FLUGDAUER_VERTEIDIGUNG_BUENDNISS',  22);
define('FLUGDAUER_VERTEIDIGUNG_ANDERE',     24);
define('BLEIBEDAUER_ANGRIFF',               5);
define('BLEIBEDAUER_VERTEIDIGUNG_GALA',     20);
define('BLEIBEDAUER_VERTEIDIGUNG_ALLI',     20);
define('BLEIBEDAUER_VERTEIDIGUNG_META',     20);
define('BLEIBEDAUER_VERTEIDIGUNG_BUENDNISS', 20);
define('BLEIBEDAUER_VERTEIDIGUNG_ANDERE',   17);

class Flottenbewegung
{
    var $_id = null;
    var $_start_gala = null;
    var $_start_planet = null;
    var $_flotte = null;       // Nummer der Flotte
    var $_ziel_gala = null;
    var $_ziel_planet = null;
    var $_angriff = null;      // 1 -> Angriff  | 0 -> Verteidigung
    var $_rueckflug = null;    // 1 -> befindet sich auf dem Rückflug | 0 -> nicht am Rückflug
    var $_flugdauer = null;    // Dauer des Flugs in eine Richtung
    var $_bleibedauer = null;  // Zeit, die die Flotte im fremden Orbit verbleibt
    var $_eta = null;          // Hinflug: ETA bis die Flotte im fremden Orbit ankommt
                               // Rückflug: Zeit bis die Flotte wieder im eigenen Orbit ist 
                               // Wenn negativ: Zeit die die Flotte noch im fremden Orbit bleibt
    var $_safe = null;         // 1 -> safe | 0 -> unsafe

    var $_delete = false;      // wenn true löscht sich die Flotte beim save()-en

    /* Die Parameter fuer Flotte, Flugdauer, Bleibedauer und ETA
     * koennen auf false gesetzt werden, die Klasse berechnet oder raet dann
     * automatisch die Werte
     *
     * Fuer angriff und reuckflug wird je ein boolean erwartet,
     * die restlichen Wertebreiche sollten klar sein */
    function Flottenbewegung($start_gala,
                             $start_planet,
                             $flotte,
                             $ziel_gala,
                             $ziel_planet,
                             $angriff,
                             $rueckflug,
                             $flugdauer,
                             $bleibedauer,
                             $eta,
                             $safe = false)
    {
        $this->_start_gala = $start_gala;
        $this->_start_planet = $start_planet;
        $this->_flotte = $flotte;
        $this->_ziel_gala = $ziel_gala;
        $this->_ziel_planet = $ziel_planet;
        $this->_angriff = $angriff;
        $this->_rueckflug = $rueckflug;
        $this->_flugdauer = $flugdauer;
        $this->_bleibedauer = $bleibedauer;
        $this->_eta = $eta;
        $this->_safe = $safe;

        if ($this->_flotte == false)
            $this->_flotte = -1;

        if ($this->_flugdauer == false) {
            if ($this->_angriff) {
                $this->_flugdauer = FLUGDAUER_ANGRIFF;
            } else {
                if ($start_gala == $ziel_gala)
                    $this->_flugdauer = FLUGDAUER_VERTEIDIGUNG_GALA;
                else if (/* FIXME same alli*/ false)
                    $this->_flugdauer = FLUGDAUER_VERTEIDIGUNG_ALLI;
                else if (/* FIXME same meta*/ true)
                    $this->_flugdauer = FLUGDAUER_VERTEIDIGUNG_META;
                else if (/* FIXME bnd*/ false)
                    $this->_flugdauer = FLUGDAUER_VERTEIDIGUNG_BUENDNISS;
                else
                    $this->_flugdauer = FLUGDAUER_VERTEIDIGUNG_ANDERE;
            }
        }

        if ($this->_bleibedauer == false) {
            if ($this->_angriff) {
                $this->_bleibedauer = BLEIBEDAUER_ANGRIFF;
            } else {
                if ($start_gala == $ziel_gala)
                    $this->_bleibedauer = BLEIBEDAUER_VERTEIDIGUNG_GALA;
                else if (/* FIXME same alli*/ false)
                    $this->_bleibedauer = BLEIBEDAUER_VERTEIDIGUNG_ALLI;
                else if (/* FIXME same meta*/ true)
                    $this->_bleibedauer = BLEIBEDAUER_VERTEIDIGUNG_META;
                else if (/* FIXME bnd*/ false)
                    $this->_bleibedauer = BLEIBEDAUER_VERTEIDIGUNG_BUENDNISS;
                else
                    $this->_bleibedauer = BLEIBEDAUER_VERTEIDIGUNG_ANDERE;
            }
        }

        if ($this->_eta == false) {
            $this->_eta = $this->_flugdauer;
        }
    }

    // ======================== save / delete =================================
    function save()
    {
        global $tic;

        if ($tic->disableSecurity) {
            $userid = null;
        } else {
            $user = $tic->mod['Auth']->getActiveUser();
            $userid = $user->getId();
        }

        if ($this->_delete) {
            $qry = "DELETE FROM flotten WHERE id = %s";
            $tic->db->Execute(get_class($this), $qry, array($this->_id));
        }
        // planet anlegen wenn nicht vorhaden
		$gala=new Galaxie($this->_start_gala,$this->_start_planet);
        $gala->create();
        $gala=new Galaxie($this->_ziel_gala,$this->_ziel_planet);
        $gala->create();
        if ($this->_id === null) {
        	
        	
        	
            $qry_ins = "INSERT INTO flotten (start_gala, start_planet, flotte, ".
                "ziel_gala, ziel_planet, angriff, rueckflug, flugdauer, ".
                "bleibedauer, eta, safe, user_gala,user_planet) ".
                "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";
            $data = array($this->_start_gala,
                          $this->_start_planet,
                          $this->_flotte,
                          $this->_ziel_gala,
                          $this->_ziel_planet,
                          $this->_angriff,
                          $this->_rueckflug,
                          $this->_flugdauer,
                          $this->_bleibedauer,
                          $this->_eta,
                          $this->_safe,//FIXME hier weiter machen
                          $userid[0],
                          $userid[1]);
            $tic->db->Execute('Taktik', $qry_ins, $data);
            $this->_id = $tic->db->Insert_ID();
            return true;
        } else {
            $qry_upd = "UPDATE flotten SET start_gala = %s, start_planet = %s, flotte = %s, ".
                "ziel_gala = %s, ziel_planet = %s, angriff = %s, rueckflug = %s, flugdauer = %s, ".
                "bleibedauer = %s, eta = %s, safe = %s, user_gala = %s, user_planet=%s WHERE id = %s";
            $data = array($this->_start_gala,
                          $this->_start_planet,
                          $this->_flotte,
                          $this->_ziel_gala,
                          $this->_ziel_planet,
                          $this->_angriff,
                          $this->_rueckflug,
                          $this->_flugdauer,
                          $this->_bleibedauer,
                          $this->_eta,
                          $this->_safe,
                          $userid[0],
                          $userid[1],
                          $this->_id);
            $tic->db->Execute('Taktik', $qry_upd, $data);
            return true;
        }
    }

    function delete()
    {
        global $tic;
        $qry = "DELETE FROM flotten WHERE id = %s";
        if ($this->_id !== null)
            $tic->db->Execute('Taktik', $qry, array($this->_id));
    }

    // =========================== match / update =============================
    
    function match($fleet, $checkFleet)
    {
        return ($this->_start_gala == $fleet->_start_gala &&
                $this->_start_planet == $fleet->_start_planet &&
                $this->_ziel_gala == $fleet->_ziel_gala &&
                $this->_ziel_planet == $fleet->_ziel_planet &&
                (!$checkFleet || 
                    ($this->_flotte == $fleet->_flotte && 
                    $this->_flotte > 0)) &&
                $this->_angriff == $fleet->_angriff);
    }

    function matchUnknownFleet($fleet)
    {
        assert($this->_flotte == -1 && $fleet->_flotte == -1);

        return ($this->_start_gala == $fleet->_start_gala &&
                $this->_start_planet == $fleet->_start_planet &&
                $this->_ziel_gala == $fleet->_ziel_gala &&
                $this->_ziel_planet == $fleet->_ziel_planet &&
                $this->_angriff == $fleet->_angriff &&
                $this->_eta == $fleet->_eta && 
                $this->_rueckflug == $fleet->_rueckflug);
    }

    function updateWith($fleet)
    {
        assert($this->match($fleet));


    }

    // ============================= get* =====================================

    function getId()
    {
        return $this->_id;
    }

    function getStartGala()
    {
        return $this->_start_gala;
    }

    function getStartPlanet()
    {
        return $this->_start_planet;
    }

    function getFlotte($forOutput = false)
    {
        if ($forOutput)
            return $this->_flotte;
        else
            return ($this->_flotte == -1) ? '?' : $this->_flotte;
    }

    function getZielGala()
    {
        return $this->_ziel_gala;
    }

    function getZielPlanet()
    {
        return $this->_ziel_planet;
    }

    function getAngriff()
    {
        return $this->_angriff;
    }

    function getRueckflug()
    {
        return $this->_rueckflug;
    }

    function getFlugdauer()
    {
        return $this->_flugdauer;
    }

    function getBleibedauer()
    {
        return $this->_bleibedauer;
    }

    function getETA()
    {
        return $this->_eta;
    }

    function getSafe()
    {
        return $this->_safe;
    }

    // ============================= set* =====================================

    function setStartGala($val)
    {
        $this->_start_gala = $val;
    }

    function setStartPlanet($val)
    {
        $this->_start_planet = $val;
    }

    function setFlotte($val)
    {
        assert($val == 1 || $val == 2 || $val == -1);
        $this->_flotte = $val;
    }

    function setZielGala($val)
    {
        $this->_ziel_gala = $val;
    }

    function setZielPlanet($val)
    {
        $this->_ziel_planet = $val;
    }

    function setAngriff($val)
    {
        assert(is_bool($val));
        $this->_angriff = $val;
    }

    function setRueckflug($val)
    {
        assert(is_bool($val));
        $this->_rueckflug = $val;
    }

    function setFlugdauer($val)
    {
        $this->_flugdauer = $val;
    }

    function setBleibedauer($val)
    {
        $this->_bleibedauer = $val;
    }

    function setETA($val)
    {
        $this->_ETA = $val;
    }

    function setSafe($val)
    {
        assert(is_bool($val));
        $this->_safe = $val;
    }

    // ============================= specials =================================

    function calcTicks($n)
    {
        $this->_eta -= $n;
        if (!$this->_rueckflug) {
            if ($this->_eta < 0) {
                // FIXME: scans als "hat gekaempft" markieren?
                $bleibt = abs($this->_eta) - $this->_bleibedauer;
                if ($bleibt <= 0) {
                    $this->_rueckflug = true;
                    $this->_eta = $this->_flugdauer + $bleibt;
                }
            }
        } else {
            if ($this->_eta < 0) {
                $this->_delete = true;
                return;
            }
        }
    }

    function getStartPlayer()
    {
        global $tic;
        return $tic->mod['UserMan']->getPlayerByKoords($this->_start_gala, $this->_start_planet);
    }

    function getZielPlayer()
    {
        global $tic;
        return $tic->mod['UserMan']->getPlayerByKoords($this->_ziel_gala, $this->_ziel_planet);
    }

    function getStartUser()
    {
        global $tic;
        return $tic->mod['UserMan']->getUserByKoords($this->_start_gala, $this->_start_planet);
    }

    function getZielUser()
    {
        global $tic;
        return $tic->mod['UserMan']->getUserByKoords($this->_ziel_gala, $this->_ziel_planet);
    }

    function getStartKoords()
    {
        return $this->_start_gala.':'.$this->_start_planet;
    }

    function getZielKoords()
    {
        return $this->_ziel_gala.':'.$this->_ziel_planet;
    }

    function toTaktikString($ziel)
    {
        $str = '';
        $A_D = $this->getAngriff() ? 'A:' : 'D:';
        $RF = $this->getRueckflug() ? 'RF:' : '';
        if ($ziel) {
            $str = $A_D.$RF.' ';
            $player = $this->getZielPlayer();
        } else {
            $str = $RF.' ';
            $player = $this->getStartPlayer();
        }
        if (!$player)
            return '&gt;ERROR&lt;';
        $alli = $player->getAllianz();
        if ($alli)
            $str .= '['.$alli->getTag().'] ';

        $flotte = $this->_flotte == -1 ? '?' : $this->_flotte;
        $str .= $player->getKoords().' '.$player->getNick();
        return $str;
    }

    function toString()
    {
        return "Flottenbeweung FIXME";
    }

    function toJson()
    {
        return array(
            'id'            => $this->_id,
            'startGala'     => $this->_start_gala,
            'startPlanet'   => $this->_start_planet,
            'flotte'        => $this->_flotte,
            'zielGala'      => $this->_ziel_gala,
            'zielPlanet'    => $this->_ziel_planet,
            'angriff'       => $this->_angriff,
            'rueckflug'     => $this->_rueckflug,
            'flugdauer'     => $this->_flugdauer,
            'bleibedauer'   => $this->_bleibedauer,
            'eta'           => $this->_eta,
            'safe'          => $this->_safe
        );
    }
}

?>
