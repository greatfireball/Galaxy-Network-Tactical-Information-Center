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

require_once("Flottenbewegung.class.php");
require_once("frontend.php");

class Taktik extends TICModule
{
    function Taktik()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net")),
	"2",
	"Taktik",
	"Taktik Modul",
	array(
            "Core" => "4",
            "Design" => "2",
            "ADOdb" => "5",
            "UserMan" => "2"
        ));
    
    }

    public function onLoad() {
        global $tic;
        $tic->mod['JSON']->registerAjaxCall('taktikhud', 'Taktik', 'taktikHUD');
    }

    public function createMenuEntries($menuroot)
    {
        $taktik = new MenuEntry("Taktik", 0, $this->getName(), "Taktik");
        $taktik->addChild(new MenuEntry("Incomings", 0, $this->getName(), "Incs"));
        $taktik->addChild(new MenuEntry("Meta", 1, $this->getName(), "Meta"));
        $taktik->addChild(new MenuEntry("Allianz", 2, $this->getName(), "Ally"));
        $taktik->addChild(new MenuEntry("Galaxie", 3, $this->getName(), "Gala"));
        $main = $menuroot->getChildByName('Main');
        $main->addChild($taktik);
    }

    public function onExecute($menuentry)
    {
        switch ($menuentry) {
        case 'Taktik':
        case 'Incs':
        case 'Meta':
            showTaktikFlotten();
            break;
        case 'Ally':
            showAllianz();
            break;
        case 'Gala':
            showGalaxie();
            break;
        default:
            showTaktikFlotten();
            break;
        }
    }

    public function getInstallQueriesMySQL()
    {
    	global $tic;
        return array_merge($tic->mod['UserMan']->getInstallQueriesMySQL(),
        array(
            'DROP TABLE IF EXISTS flotten',
            'DROP TABLE IF EXISTS taktik_update',
            "CREATE  TABLE IF NOT EXISTS `flotten` (
			  `id` INT(11) NOT NULL AUTO_INCREMENT ,
			  `start_gala` INT(11) NOT NULL ,
			  `start_planet` INT(11) NOT NULL ,
			  `flotte` TINYINT(4) NOT NULL ,
			  `ziel_gala` INT(11) NOT NULL ,
			  `ziel_planet` INT(11) NOT NULL ,
			  `angriff` TINYINT(1) NOT NULL ,
			  `rueckflug` TINYINT(1) NOT NULL ,
			  `flugdauer` INT(11) NOT NULL ,
			  `bleibedauer` INT(11) NOT NULL ,
			  `eta` INT(11) NOT NULL ,
			  `safe` TINYINT(1) NOT NULL ,
			  `user_gala` INT(11) NULL DEFAULT NULL ,
			  `user_planet` INT(11) NULL DEFAULT NULL ,
			  PRIMARY KEY (`id`) ,
			  INDEX `fk_Flotten_GNPlayer` (`start_planet` ASC, `start_gala` ASC) ,
			  INDEX `fk_Flotten_GNPlayer1` (`ziel_gala` ASC, `ziel_planet` ASC) ,
			  INDEX `fk_flotten_tic_user` (`user_planet` ASC, `user_gala` ASC) ,
 			 CONSTRAINT `fk_Flotten_GNPlayer`
			    FOREIGN KEY (`start_planet` , `start_gala` )
			    REFERENCES `gnplayer` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
 			 CONSTRAINT `fk_Flotten_GNPlayer1`
			    FOREIGN KEY (`ziel_gala` , `ziel_planet` )
			    REFERENCES `gnplayer` (`gala` , `planet` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_flotten_tic_user`
			    FOREIGN KEY (`user_planet` , `user_gala` )
			    REFERENCES `tic_user` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 1
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "CREATE  TABLE IF NOT EXISTS `taktik_update` (
			  `id` INT(11) NOT NULL AUTO_INCREMENT ,
			  `user_planet` INT(11) NULL ,
			  `user_gala` INT(11) NULL ,
			  `galaxie` INT(11) NOT NULL ,
			  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
			  PRIMARY KEY (`id`) ,
			  INDEX `fk_TaktikUpdate_Galaxie` (`galaxie` ASC) ,
			  INDEX `fk_TaktikUpdate_TICUser` (`user_planet` ASC, `user_gala` ASC) ,
			  CONSTRAINT `fk_TaktikUpdate_Galaxie`
			    FOREIGN KEY (`galaxie` )
			    REFERENCES `galaxie` (`gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_TaktikUpdate_TICUser`
			    FOREIGN KEY (`user_planet` , `user_gala` )
			    REFERENCES `tic_user` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 1
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;"
        ));
    }

	public function getInstallQueriesPostgreSQL()
	{
        return array(
            'DROP TABLE flotten CASCADE',
            'DROP TABLE taktik_update CASCADE',
            'CREATE TABLE flotten (
                id serial NOT NULL PRIMARY KEY,
                start_gala int NOT NULL,
                start_planet int NOT NULL,
                flotte smallint NOT NULL,
                ziel_gala int NOT NULL,
                ziel_planet int NOT NULL,
                angriff smallint NOT NULL,
                rueckflug smallint NOT NULL,
                flugdauer int NOT NULL,
                bleibedauer int NOT NULL,
                eta int NOT NULL,
                safe smallint NOT NULL,
                ticuser int
                --UNIQUE(start_gala, start_planet, flotte)
                --FOREIGN KEY (start_gala, start_planet) REFERENCES GNPlayer(planet, gala),
                --FOREIGN KEY (ziel_gala, ziel_planet) REFERENCES GNPlayer(planet, gala)
            );',
            'CREATE TABLE taktik_update (
                id serial NOT NULL PRIMARY KEY,
                ticuser int,
                galaxie int NOT NULL UNIQUE,
                time timestamp(0) NOT NULL DEFAULT now(),
                FOREIGN KEY (ticuser) REFERENCES TICUser(ticuser)
            );'
        );
    }

    /* erwartet ein assoziatives array das als key die Namen der
     * gesuchten Eigenschaften und als value die gesuchten Werte enthaelt
     *
     * erlaubte keys sind:
     *  - start_gala
     *  - start_planet
     *  - flotte
     *  - ziel_gala
     *  - ziel_planet
     *  - angriff
     *  - flugdauer
     *  - bleibedauer
     *  - eta
     *  - safe
     * */
    public function findFlotten($param) {
        global $tic;

        $qry = "SELECT start_gala, start_planet, flotte, ziel_gala, ziel_planet, ".
            "angriff, rueckflug, flugdauer, bleibedauer, eta, safe, id ".
            "FROM flotten ";

        $args = array();
        $first = true;
        foreach ($param as $key => $value) {
            if ($first) {
                $qry .= "WHERE "; $first = false;
            } else {
                $qry .= "AND ";
            }
            $qry .= "$key = %s ";
            array_push($args, $value);
        }
        $qry .= "ORDER BY ziel_gala, ziel_planet, angriff, eta, start_gala, start_planet, flotte";
        $rs = $tic->db->Execute('Taktik', $qry, $args);
        $fleets = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            $fleet = new Flottenbewegung($rs->fields[0],
                                         $rs->fields[1],
                                         $rs->fields[2],
                                         $rs->fields[3],
                                         $rs->fields[4],
                                         ($rs->fields[5] == 0) ? false : true,
                                         ($rs->fields[6] == 0) ? false : true,
                                         $rs->fields[7],
                                         $rs->fields[8],
                                         $rs->fields[9],
                                         ($rs->fields[10] == 0) ? false : true);
            $fleet->_id = $rs->fields[11];
            array_push($fleets, $fleet);
        }
        return $fleets;
    }

    public function onTick($id)
    {
        $this->calcTicks(1);
    }

    /* berechne n Ticks */
    public function calcTicks($n)
    {
        global $tic;
        $backup = $tic->disableSecurity;
        $tic->disableSecurity = true;
        $flotten = $this->findFlotten(array());
        foreach ($flotten as $flotte) {
            $flotte->calcTicks($n);
            $flotte->save();
        }
        $tic->disableSecurity = $backup;
    }

    public function getAllianzIncCount($alli, $type = 'open')
    {
        global $tic;
        switch ($type) {
        case 'open':
            $qry = "SELECT count(*) FROM flotten JOIN tic_user ".
                   "ON(gala = ziel_gala AND planet = ziel_planet) ".
                   "NATURAL JOIN galaxie ".
                   "WHERE rueckflug = '0' AND angriff = '1' AND safe = '0' AND eta >= 14 ".
                   "AND galaxie.allianz = %s";
            break;
        case 'undertime':
            $qry = "SELECT count(*) FROM flotten JOIN tic_user ".
                   "ON(gala = ziel_gala AND planet = ziel_planet) ".
                   "NATURAL JOIN galaxie ".
                   "WHERE rueckflug = '0' AND angriff = '1' AND safe = '0' AND eta < 14 ".
                   "AND galaxie.allianz = %s";
            break;
        case 'safe':
            $qry = "SELECT count(*) FROM flotten JOIN tic_user ".
                   "ON(gala = ziel_gala AND planet = ziel_planet) ".
                   "NATURAL JOIN galaxie ".
                   "WHERE rueckflug = '0' AND angriff = '1' AND safe = '1' AND eta >= 14 ".
                   "AND galaxie.allianz = %s";
            break;
        default:
            assert(false);
        }
        $rs = $tic->db->Execute($this->getName(), $qry, array($alli));
        return $rs->fields[0];
    }

    // bekommt ein Array mit nicht ge-save()-ten Flotten uebergeben
    public function updateGalaTaktik($new)
    {
        global $tic;

        $user = $tic->mod['Auth']->getActiveUser();
        $gala = $user->getGalaxie();

        $tic->db->StartTrans();

        $qry = "DELETE FROM taktik_update WHERE galaxie = %s";
        $tic->db->Execute($this->getName(), $qry, array($gala));

        $qry = "INSERT INTO taktik_update (user_gala,user_planet, galaxie) VALUES (%s, %s, %s)";
        $tic->db->Execute($this->getName(), $qry, array_merge($user->getId(),array($gala)));

        $arr1 = $this->findFlotten(array('start_gala' => $gala));
        $arr2 = $this->findFlotten(array('ziel_gala' => $gala));
        $old = array_merge($arr1, $arr2);

        $fleetsByStart = array();

        foreach ($old as $oldFleet) {
            $koords = $oldFleet->getStartKoords();
            if (!array_key_exists($koords, $fleetsByStart))
                $fleetsByStart[$koords] = array('old' => array(), 'new' => array());
            $fleetsByStart[$koords]['old'][] = $oldFleet;
        }

        foreach ($new as $newFleet) {
            $koords = $newFleet->getStartKoords();
            if (!array_key_exists($koords, $fleetsByStart))
                $fleetsByStart[$koords] = array('old' => array(), 'new' => array());
            $fleetsByStart[$koords]['new'][] = $newFleet;
        }

        echo '<pre>';
        print_r($fleetsByStart);
        echo '</pre>';

        foreach ($fleetsByStart as $startKey => $start) {
            $this->matchOldAndNew($start['old'], $start['new']);
        }

        if ($tic->db->HasFailedTrans())
            $tic->error($this->getName(), 'Datenbank-Fehler beim Abspeichern der Taktik -> Abbruch');
        $tic->db->CompleteTrans();
    }


    private function matchOldAndNew($old, $new)
    {
        assert(count($old) >= 0 || count($old) <= 2);
        assert(count($new) >= 0 || count($new) <= 2);

        $done = array();

        // alle flotten die auch mit flotten nr. übereinstimmen updaten
        foreach ($old as $oKey => $oFleet) {
            foreach ($new as $nKey => $nFleet) {
                if ($oFleet->match($nFleet, true)) {
                    $oFleet->updateWith($nFleet);
                    $this->fleetsMatched($new, $old, $done, $nKey, $oKey);
                }
            }
        }

        // automatische flottennr vervollständigung
        if (count($done) == 1) {
            foreach ($old as $oKey => $oFleet) {
                foreach ($new as $nKey => $nFleet) {

                    // wir haben schon eine Flotten-Nr.
                    // also kennen wir jetzt auch die andere
                    if ($oFleet->match($nFleet, false) && count($done) == 1) {

                        $doneNr = $done[0]->getFlotte();
                        switch ($doneNr) {
                        case 1:
                            $nFleet->setFlotte(2);
                            break;
                        case 2:
                            $nFleet->setFlotte(1);
                            break;
                        default:
                            assert(false);
                        }
                        $oFleet->updateWith($nFleet);
                        $this->fleetsMatched($new, $old, $done, $nKey, $oKey);
                    }
                }
            }
        }

        echo '<pre>';
        echo "vor machtUnknownFleet()\n";
        print_r(array('old' => $old, 'new' => $new, 'done' => $done));
        echo '</pre>';

        // alte flotten koennen anhand des etas und rueckflugs status
        // den neuen zugeordnet werden
        foreach ($old as $oKey => $oFleet) {
            foreach ($new as $nKey => $nFleet) {
                if ($oFleet->matchUnknownFleet($nFleet)) {
                    $oFleet->updateWith($nFleet);
                    $this->fleetsMatched($new, $old, $done, $nKey, $oKey);
                }
            }
        }

        // =============================================================
        // alles was hier noch übrig ist hat unbekannte oder nicht 
        // übereinstimmende flottennummern und nicht übereinstimmende etas
        // =============================================================

        // überprüfen ob sich die floten alle das gleiche tun 
        // (gleiches ziel, angriff/deff gleich
        /*$allMatch = true;
        foreach ($old as $oKey => $oFleet) {
            foreach ($new as $nKey => $nFleet) {
                if (!$oFleet->match($nFleet))
                    $allMatch = false;
            }
        }

        // alle flotten haben das gleiche ziel und sind im gleichen modus
        if ($allMatch) {
            // wir haben schon bei den alten flotten die flotten nummern
            if (count($old) == count($new) && count($old) > 0) {
                $allKnown = true;
                foreach ($old as $oKey => $oFleet) {
                    if ($oFleet->getFlotte() == -1)
                        $allKnown = false;
                }
                if ($allKnown) {
                    if ($count($old) == 1) {
                        // wir habe nur eine neue und eine alte flotte
                        $old[0]->updateWith($new[0]);
                        $done[] = $old[0];
                        unset($old[0]);
                        unset($new[0]);
                        $old = array_values($old);
                        $new = array_values($new);
                    } else { // FIXME FIXME FIXME eta vergleichen nicht flugdauer
                        // wir haben 2 alte und 2 neue fleets, aber wissen
                        // nich welche neue zu welcher alten gehört, also
                        // gucken wir nach den flugzeiten um das verhältnis
                        // möglichst zu erhalten
                        if ($old[0]->getFlugdauer() < $old[1]->getFlugdauer()) {
                            $oLower = 0;
                            $oHigher = 1;
                        } else {
                            $oLower = 1;
                            $oHigher = 0;
                        }
                        if ($new[0]->getFlugdauer() < $new[1]->getFlugdauer()) {
                            $nLower = 0;
                            $nHigher = 1;
                        } else {
                            $nLower = 1;
                            $nHigher = 0;
                        }
                        $old[$oLower]->updateWith($new[$nLower]);
                        $old[$oHigher]->updateWith($new[$nHigher]);
                        $this->fleetsMatched($new, $old, $done, $nLower, $oLower);
                        $this->fleetsMatched($new, $old, $done, $nHigher, $oHigher);
                    }
                }
            }

            if (count($new) > 0 && count($old) > 0) {
                if (count($old) > count($new)) {
                    // mehr alte als neue flotten, suche die die besser passt
                    $oFleetKey = $this->findBestMatch($new[0], $old);
                    $oFleet = $old[$oFleetKey];
                    $oFleet->updateWith($new[0]);
                    $this->fleetsMatched($new, $old, $done, 0, $oFleetKey);
                } else {
                    // mehr neue als alte flotten
                    $nFleetKey = $this->findBestMatch($old[0], $new);
                    $nFleet = $new[$nFleetKey];
                    $old[0]->updateWith($nFleet);
                    $this->fleetsMatched($new, $old, $done, $nFleetKey, 0);
                }
            }
        }*/

        // beim rest wurden keine übereinstimmungen gefunden
        foreach ($old as $oFleet) {
            $oFleet->delete();
        }
        foreach ($new as $nFleet) {
            $nFleet->save();
        }
    }

    // verschiebt 2 flotten aus $old und $new, die scheinbar 
    // die gleiche sind, in das $done array und sorgt dafür
    // dass die array indices wieder fortlaufend sind
    private function fleetsMatched(&$new, &$old, &$done, $nKey, $oKey)
    {
        $done[] = $old[$oKey];
        unset($old[$oKey]);
        unset($new[$nKey]);
        $new = array_values($new);
        $old = array_values($old);
    }

    // suche die beste flugzeiten übereinstimmung
    private function findBestMatch($fleet, $fleets)
    {
        $diffs = array();
        foreach ($fleets as $key => $val) {
            $diff[$key] = abs($val->getFlugdauer() - $fleet->getFlugdauer());
        }
        $smallest = 0;
        foreach ($diffs as $key => $diff) {
            if ($diffs[$smallest] > $diff) {
                $smallest = $key;
            }
        }
        return $smallest;
    }

    public function updateFleet($newFleet)
    {
        $search = array();
        $search['start_gala'] = $newFleet->getStartGala();
        $search['start_planet'] = $newFleet->getStartPlanet();

        if ($newFleet->getFlotte() != -1) {
            // wir haben eine flotten nummer
            $search['flotte'] = $newFleet->getFlotte();
            $fleets = $this->findFlotten($search);
            assert(count($fleets) <= 1);
            if (count($fleets) == 1) {
                // flotte existiert bereits -> update
                $fleets[0].updateWith($newFleet);
            } else {
                // flotte ist neu
                $fleet->save();
            }
        } else {
            // flotte ist unbekannt
            $search['ziel_gala'] = $newFleet->getZielGala();
            $search['ziel_planet'] = $newFleet->getZielPlanet();
            $search['angriff'] = $newFleet->getZielPlanet();
            $fleets = $this->findFlotten($search);
            $matchFound = false;
            foreach ($fleets as $fleet) {
                if ($fleet->matchUnknownFleet($newFleet)) {
                    $fleet->updateWith($newFleet);
                    $matchFound = true;
                }
            }
            if (!$matchFound)
                $newFleet->save();
        }
    }

    public function updateFlotte($flotte)
    {
        if (!is_object($flotte)) { $tic->error = "Uebergabe muss als Objekt erfolgen!!!"; }
        $flotte->save();
        return true;
    }

    // ============================ callbacks for json generation =============================

    public function taktikHUD()
    {
        global $tic;

        // allianzen mit meta
        $meten = $tic->mod['UserMan']->getAllMeten();
        $metenArr = array();
        foreach ($meten as $meta) {
            $allianzen = $meta->getAllianzen();
            $metaArr = array();
            $metaArr['name'] = $meta->getName();
            $metaArr['tag'] = $meta->getTag();
            $metaArr['allianzen'] = array();
            foreach ($allianzen as $alli) {
                $metaArr['allianzen'][] = $this->taktikHUDHelperCreateAlli($alli);
            }
            $metenArr[] = $metaArr;
        }

        // allianzen ohne meta
        $frei = $tic->mod['UserMan']->getFreieAllianzen();
        $metaArr = array('name' => '[metalose Allianzen]', 'tag' => '[metalos]', 'allianzen' => array());
        foreach ($frei as $alli) {
            $metaArr['allianzen'][] = $this->taktikHUDHelperCreateAlli($alli);
        }
        $metenArr[] = $metaArr;

        return $metenArr;
    }

    private function taktikHUDHelperCreateAlli($alli)
    {
        global $tic;
        $alliArr = array();
        $alliArr['name'] = $alli->getName();
        $alliArr['tag'] = $alli->getTag();
        $alliArr['online'] = (int) $alli->getOnlineUserCount();
        $alliArr['member'] = (int) $alli->getMemberCount();
        $alliArr['open'] = (int) $tic->mod['Taktik']->getAllianzIncCount($alli->getId(), 'open');
        $alliArr['undertime'] = (int) $tic->mod['Taktik']->getAllianzIncCount($alli->getId(), 'undertime');
        $alliArr['safe'] = (int) $tic->mod['Taktik']->getAllianzIncCount($alli->getId(), 'safe');
        $alliArr['online_users'] = array(array('nick' => 'foo', 'koords' => '123:13'), //FIXME
                                         array('nick' => 'bar', 'koords' => '123:14'));
        return $alliArr;
    }


}

?>
