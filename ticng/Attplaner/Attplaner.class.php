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
// Class Attplaner
//
//

// --------------------------------------------------------------------------------- //

class Attplaner extends TICModule
{
    private $attTyp = array();
    private $auswahl = array();
    
    function Attplaner()
    {
	parent::__construct(
	array(new Author("Pascal Gollor", "Hugch", "pascal@gollor.org")),
	"2",
	"Attplaner",
	"Um die Atts zu planen",
	array(
            "Core" => "5",
            "Design" => "2"
          ));
    }


    public function createMenuEntries($menuroot)
    {
        $planer = new MenuEntry("Attplaner", 0);
        $planer->addChild(new MenuEntry("Konfigurieren", 0, $this->getName(), "konfig"));
        $planer->addChild(new MenuEntry("Planen", 1, $this->getName(), "planen"));
        $planer->addChild(new MenuEntry("Ziele", 2, $this->getName(), "ziele"));

        $tools = $menuroot->getChildByName('Tools');
        $tools->addChild($planer);
    }

    public function onExecute($menuentry)
    {
        global $tic;

        echo '<pre>';

        $adr = $tic->mod['Core']->getTICAdr();
        $this->attTyp = array(
            $this->attTypToString(ATTPLANER_TYP_GALA),
            $this->attTypToString(ATTPLANER_TYP_ALLIANZ),
            $this->attTypToString(ATTPLANER_TYP_META),
            $this->attTypToString(ATTPLANER_TYP_ALLE)
        );
        $this->auswahl = array("frei", "zuteilen");
        $datum = array();
        for ($i0 = 0; $i0 < 7; $i0++) {
            $tag = date("d") + $i0;
            if (strlen($tag) == 1) { $tag = "0".$tag; }
            array_push($datum, $tag.date(".m.Y"));
        }
        //FIXME generierung der User die berechtigt sind
        $attPlaner = array(
            $tic->mod['UserMan']->getUserByKoords('0', '1')
        );
        

        $this->setVar('attPlaner', $attPlaner);
        $this->setVar('attplanerPfad', $adr."?mod=".$this->getName().'&menu='.$menuentry);
        $this->setVar('attTyp', $this->attTyp);
        $this->setVar('auswahl', $this->auswahl);
        $this->setVar('datum', $datum);
        $this->setVar('jetzt', date("H:i"));
        $this->setVar("sektorNamen", $tic->mod['Konst']->getSektorNamen(1, true));

        if (isset($_POST['ma_erstellen'])) { $this->maErstellen($_POST); }
        if (isset($_POST['ma_loeschen'])) { $this->maLoeschen($_POST); }
        if (isset($_POST['ziel_erfassen'])) { $this->zielErfassen($_POST); }
        if (isset($_POST['ziel_speichern'])) { $this->zielSpeichern($_POST); }
        if (isset($_POST['ziel_loeschen'])) { $this->zielLoeschen($_POST); }
        if (isset($_POST['flotte'])) { $this->flotteAdd($_POST); }

        switch ($menuentry) {
            case "konfig":
                $this->setVar('ma', $this->getMa());
                break;
            case "planen":
                $this->setVar('ma', $this->getMa(true));
                break;
            case "ziele":
                $this->setVar('ziele', $this->getZiele());
                break;
        }

        $this->setTemplate('attplaner_'.$menuentry.'.tpl');

        echo '</pre>';
    }

    public function getInstallQueriesMySQL() {
        global $tic;
    	return array_merge($tic->mod['UserMan']->getInstallQueriesMySQL(),
        array(
            "DROP TABLE IF EXISTS attplaner_ma CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `attplaner_ma` (
			  `ma` INT(11) NOT NULL AUTO_INCREMENT ,
			  `planer_gala` INT(11) NOT NULL ,
			  `planer_planet` INT(11) NOT NULL ,
			  `att_typ` INT(1) NOT NULL DEFAULT '-1' ,
			  `auswahl` INT(1) NOT NULL DEFAULT '-1' ,
			  PRIMARY KEY (`ma`) ,
			  INDEX `fk_Attplaner_ma_TICUser` (`planer_planet` ASC, `planer_gala` ASC) ,
			  CONSTRAINT `fk_Attplaner_ma_TICUser`
			    FOREIGN KEY (`planer_planet` , `planer_gala` )
			    REFERENCES `tic_user` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 2
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS attplaner_ziele CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `attplaner_ziele` (
			  `id` INT(11) NOT NULL AUTO_INCREMENT ,
			  `ma` INT(11) NOT NULL ,
			  `ziel_gala` INT(11) NOT NULL ,
			  `ziel_planet` INT(11) NOT NULL ,
			  `freigabe` INT(10) NOT NULL DEFAULT '-1' ,
			  `abflug` INT(10) NOT NULL DEFAULT '-1' ,
			  `text` VARCHAR(150) NULL DEFAULT NULL ,
			  PRIMARY KEY (`id`) ,
			  INDEX `fk_Attplaner_ziele_GNPlayer` (`ziel_planet` ASC, `ziel_gala` ASC) ,
			  INDEX `fk_Attplaner_ziele_Attplaner_ma` (`ma` ASC) ,
			  CONSTRAINT `fk_Attplaner_ziele_GNPlayer`
			    FOREIGN KEY (`ziel_planet` , `ziel_gala` )
			    REFERENCES `gnplayer` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_Attplaner_ziele_Attplaner_ma`
			    FOREIGN KEY (`ma` )
			    REFERENCES `attplaner_ma` (`ma` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 2
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS attplaner_flotten CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `attplaner_flotten` (
			  `flotte` INT(1) NOT NULL ,
			  `planet` INT(11) NOT NULL ,
			  `gala` INT(11) NOT NULL ,
			  `ziele_id` INT(11) NOT NULL ,
			  UNIQUE INDEX `ziel_id` (`flotte` ASC, `planet` ASC, `gala` ASC, `ziele_id` ASC) ,
			  PRIMARY KEY (`planet`, `gala`, `ziele_id`) ,
			  INDEX `fk_Attplaner_flotten_TICUser` (`planet` ASC, `gala` ASC) ,
			  INDEX `fk_Attplaner_flotten_Attplaner_ziele` (`ziele_id` ASC) ,
			  CONSTRAINT `fk_Attplaner_flotten_TICUser`
			    FOREIGN KEY (`planet` , `gala` )
			    REFERENCES `tic_user` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_Attplaner_flotten_Attplaner_ziele`
			    FOREIGN KEY (`ziele_id` )
			    REFERENCES `attplaner_ziele` (`id` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;"
        ));
    }

    public function getInstallQueriesPostgreSQL() {
        return array(
            "DROP TABLE Attplaner_ziele CASCADE;",
            "CREATE TABLE Attplaner_ziele (
                id serial NOT NULL PRIMARY KEY,
                ma int NOT NULL DEFAULT '-1',
                ziel_gala int NOT NULL DEFAULT '-1',
                ziel_planet int NOT NULL DEFAULT '-1',
                freigabe int NOT NULL DEFAULT '-1',
                abflug int NOT NULL DEFAULT '-1',
                text VARCHAR(150) NULL,
                UNIQUE(ma, ziel_gala, ziel_planet)
            );",
            "DROP TABLE Attplaner_ma CASCADE;",
            "CREATE TABLE Attplaner_ma (
                ma serial NOT NULL PRIMARY KEY,
                planer int NOT NULL DEFAULT '-1',
                attTyp smallint NOT NULL DEFAULT '-1',
                auswahl smallint NOT NULL DEFAULT '-1'
            );",
            "DROP TABLE Attplaner_flotten CASCADE;",
            "CREATE TABLE Attplaner_flotten (
                ziel_id int NOT NULL,
                ticuser int NOT NULL,
                flotte smallint NOT NULL,
                UNIQUE(ziel_id, ticuser, flotte)
            );"
        );
    }

    private function attTypToString($typ)
    {
        switch ($typ) {
            case ATTPLANER_TYP_GALA:
                return "Gala";
            case ATTPLANER_TYP_ALLIANZ:
                return "Allianz";
            case ATTPLANER_TYP_META:
                return "Meta";
            case ATTPLANER_TYP_ALLE:
                return "Alle";
        }
        return false;
    }

    private function auswahlToString($num)
    {
        global $tic;
        if (!is_numeric($num) || $num < 0 || $num > 1) { $tic->error($this->getName(), "Error!!!"); return false; }
        return $this->auswahl[$num];
    }

    private function getMA($break = false)
    {
        global $tic;

        $qry = "SELECT ma, planer_gala,planer_planet, att_typ, auswahl FROM attplaner_ma ORDER BY ma ASC;";
        $rs = $tic->db->Execute($this->getName(), $qry);
        $user = $tic->mod['Auth']->getActiveUser();
        $ma = array();
        while (!$rs->EOF) {
            $planer = $tic->mod['UserMan']->getUserByID(array($rs->fields[1],$rs->fields[2]));
            if ($user->getId() == $planer->getId()) { $aendern = true; } else { if ($break) { break; } }
            array_push($ma, array(
                'id' => $rs->fields[0],
                'planer' => $planer,
                'attTyp' => $this->attTypToString($rs->fields[3]),
                'auswahl' => $this->auswahlToString($rs->fields[4]),
                'aendern' => $aendern
            ));
            $rs->MoveNext();
        }
        if (count($ma) == 0) { return false; }

        return $ma;
    }

    private function maErstellen($daten)
    {
        global $tic;

        /*$daten:
            ['planer'] Id von dem Attplaner (aktuell noch der aktive User)
            ['attTyp'] 0 => Gala; 1 => Ally; 2 => Meta; 3 => alle
            ['auswahl'] 0 => freie Wahl; 1 => zugeteilte Ziele
        */
		$userid=split(':',$daten['planer']);
        $planer = $tic->mod['UserMan']->getUserById($userid);
        $planerAllianz = $planer->getAllianz();
        if ($daten['att_typ'] == 1 && $planerAllianz === false) {
            $tic->error($this->getName(), "Du geh&ouml;rst keiner Allianz an.");
            return false;
        } elseif ($daten['att_typ'] == 2 && $planerAllianz->getMeta() === false) {
            $tic->error($this->getName(), "Deine Allianz geh&ouml;rt keiner Meta an.");
            return false;
        }
		$userid=split(':',$daten['planer']);
        $qry = "INSERT INTO attplaner_ma (planer_gala,planer_planet, att_typ, auswahl) VALUES (%s,%s, %s, %s);";
        $rs = $tic->db->Execute($this->getName(), $qry, array($userid[0],$userid[1], $daten['att_typ'], $daten['auswahl']));
        if (!$rs) {
            $tic->error($this->getName(), "Problem beim speichern des Massenatts.");
            return false;
        }
    }

    private function maLoeschen($daten)
    {
        global $tic;

        $qry = "DELETE FROM attplaner_ma WHERE ma = %s;";
        $rs0 = $tic->db->Execute($this->getName(), $qry, array($daten['ma']));
        $qry = "DELETE F FROM attplaner_ziele AS Z, attplaner_flotten AS F WHERE Z.id = F.ziel_id AND Z.ma = %s;";
        $rs1 = $tic->db->Execute($this->getName(), $qry, array($daten['ma']));
        $qry = "DELETE FROM attplaner_ziele WHERE ma = %s;";
        $rs2 = $tic->db->Execute($this->getName(), $qry, array($daten['ma']));
        if (!$rs0 || !$rs1 || !$rs2) {
            $tic->error($this->getName(), "Problem beim l&ouml;schen des Massenatts.");
            return false;
        }
        return true;
    }

    private function zielErfassen($daten)
    {
        global $tic;

        if (!is_numeric($daten['zielGala'])) {
            $tic->error($this->getName(), "Es m&uuml;ss mindestens eine Galaxie angeben werden.");
            return false;
        }
        if (trim($daten['zielPlanet']) != '' && !is_numeric($daten['zielPlanet'])) {
            $tic->error($this->getName(), "Der Planet ist ung&uuml;ltig;");
            return false;
        }

        if (is_numeric($daten['zielPlanet'])) {
            $this->einZielErfassen($daten);
        } else {
            $gala = new Galaxie($daten['zielGala']);
            if (count($gala->getPlayer()) == 0) {
                $tic->error($this->geTName(), "Die Gala ".$daten['zielGala']." konnte nicht gefunden werden.");
                return false;
            }
            $sArray = $daten;
            foreach ($gala->getPlayer() as $value) {
                $sArray['zielPlanet'] = $value->getPlanet();
                if (!$this->einZielErfassen($sArray)) { return false; }
            }
        }

        return true;
    }

    private function einZielErfassen($daten)
    {
        global $tic;

        $daten['freigabeDatum'] = preg_split('/\./', $daten['freigabeDatum']);
        $daten['freigabeZeit'] = preg_split('/:/', $daten['freigabeZeit']);
        $daten['abflugDatum'] = preg_split('/\./', $daten['abflugDatum']);
        $daten['abflugZeit'] = preg_split('/:/', $daten['abflugZeit']);
        $freigabe = mktime($daten['freigabeZeit'][0], $daten['freigabeZeit'][1], 0, $daten['freigabeDatum'][1], $daten['freigabeDatum'][0], $daten['freigabeDatum'][2]);
        $abflug = mktime($daten['abflugZeit'][0], $daten['abflugZeit'][1], 0, $daten['abflugDatum'][1], $daten['abflugDatum'][0], $daten['abflugDatum'][2]);
		$planetObj=new GNPlayer($daten['zielGala'],$daten['zielPlanet']);// Vorsichtshalber anlegen 
		$planetObj->create();
        $qry = "INSERT INTO Attplaner_ziele (ma, ziel_gala, ziel_planet, freigabe, abflug) VALUES (%s, %s, %s, %s, %s)";
        $rs = $tic->db->Execute($this->getName(), $qry, array($daten['ma'], $daten['zielGala'], $daten['zielPlanet'], $freigabe, $abflug));

        if (!$rs) {
            $tic->error($this->getName(), "Problem beim Speichern des Zieles.");
            return false;
        }

        return true;
    }

    private function getZiele()
    {
        global $tic;

        $qry = 'SELECT Z.id,Z.ma,Z.ziel_gala,Z.ziel_planet,Z.freigabe,Z.abflug,Z.text,M.planer_gala,M.planer_planet,M.att_typ,M.auswahl FROM '.
            'Attplaner_ziele AS Z JOIN Attplaner_ma AS M USING(ma) ORDER BY Z.ziel_gala,Z.ziel_planet ASC;';
        $rs = $tic->db->Execute($this->getName(), $qry);

        $aktUser = $tic->mod['Auth']->getActiveUser();
        $aktGala = $tic->mod['UserMan']->getGalaxie($aktUser->getGalaxie());
        $aktAllianz = $aktGala->getAllianz();
        if ($aktAllianz) { $aktMeta = $aktAllianz->getMeta(); }
        $ausgabe = array();

        if (!$rs) {
            $tic->error($this->getName(), "Fehler in der Abfrage der Zeile.");
            return false;
        }

        while (!$rs->EOF) {
            $daten = $rs->fields;
            $planer = $tic->mod['UserMan']->getUserById(array($daten[7],$daten[8]));
            $daten['planer'] = $planer;
            $daten['isAllowed'] = false;

            $daten['isAllowed'] = $this->isAllowed($daten[0]);
            if ($aktUser->getId() != $planer->getId() && $daten['freigabe'] > time()) { $rs->MoveNext(); continue; }

            if ($daten[10] == 1 && $aktUser->getId() == $planer->getId()) { $daten['isAllowed'] = true; }
            $erg = $this->getFlotteByZielId($daten[0]);
            if ($erg === false) {
                $tic->error($this->getName(), "Fehler in der Abfrage der Flotten.");
                return false;                
            }

            $daten['flotten'] = array();
            foreach ($erg as $value) {
                $value['user'] = $tic->mod['UserMan']->getUserById($value['ticuser']);
                array_push($daten['flotten'], $value);
            }

            if (count($daten['flotten']) == 0) { $daten['flotten'] = false; }
            $daten['ziel'] = $tic->mod['UserMan']->getPlayerByKoords($daten[2], $daten[3]);
            $daten['zielScans']['alle'] = $tic->mod['Scan']->getScansFrom($daten['ziel']);
            if ($daten['zielScans']['alle'] === false) { $daten['zielScans']['alle'] = "-"; }
            $daten['zielScans']['sek'] = $tic->mod['Scan']->getSekScan($daten['ziel']);
            if ($daten['zielScans']['sek'] !== false) {
                $daten['zielScans']['sek'] = $tic->mod['Scan']->sekScanToArray($daten['zielScans']['sek'], true);
                $daten['zielScans']['sek']['punkte4'] = preg_replace('/\D/', '', $daten['zielScans']['sek']['punkte']) * 4;
                $daten['zielScans']['sek']['punkte4'] = number_format($daten['zielScans']['sek']['punkte4'], 0, ',', '.');
                $daten['zielScans']['sek']['punkte6'] = preg_replace('/\D/', '', $daten['zielScans']['sek']['punkte']) * 6;
                $daten['zielScans']['sek']['punkte6'] = number_format($daten['zielScans']['sek']['punkte6'], 0, ',', '.');
            } else {
                $daten['zielScans']['sek'] = "-";
            }
            $daten['zielScans']['unit'] = $tic->mod['Scan']->getUnitScan($daten['ziel']);
            if ($daten['zielScans']['unit'] !== false) {
                $daten['zielScans']['unit'] = $tic->mod['Scan']->konvertScanToHTML($daten['zielScans']['unit']);
            } else {
                $daten['zielScans']['unit'] = "-";
            }

            $daten['freigabe'] = date("H:i:s d.m.Y", $daten['freigabe']);
            if ($daten['abflug'] <= time()) { $daten['abflugNow'] = true; } else { $daten['abflugNow'] = false; }
            $daten['abflug'] = date("H:i:s d.m.Y", $daten['abflug']);

            array_push($ausgabe, $daten);
            $rs->MoveNext();
        }

        if (count($ausgabe) > 0) {
            return($ausgabe);
        } else {
            return false;
        }
    }

    private function zielSpeichern($daten)
    {
        global $tic;

        if ($this->isAllowed($daten['ziel_id']) == 0) { return false; }

        $qry = "UPDATE attplaner_ziele SET text = %s WHERE id = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($daten['text'], $daten['ziel_id']));
        if (!$rs) { $tic->error($this->getName(), "Fehler beim speichern des Texts."); return false; }

        return true;
    }

    private function isAllowed($ziel, $user = false)
    {
        global $tic;

        if (!is_numeric($ziel)) { return false; }
        if (!is_object($user)) { $user = $tic->mod['Auth']->getActiveUser(); }
        $isAllowed = false;

        $qry = "SELECT planer_gala,planer_planet,Att_typ,auswahl FROM attplaner_ma JOIN attplaner_ziele USING(ma) WHERE id = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($ziel));
        if (!$rs) { $tic->error($this->getName(), "Fehler bei der Abfrage."); return false; }
        $planer = $tic->mod['UserMan']->getUserById(array($rs->fields[0],$rs->fields[1]));

        $planerGala = $tic->mod['UserMan']->getGalaxie($planer->getGalaxie());
        $userGala = $tic->mod['UserMan']->getGalaxie($user->getGalaxie());
        if ($rs->fields[2] >= ATTPLANER_TYP_ALLIANZ) {
            $planerAllianz = $planerGala->getAllianz();
            $userAllianz = $userGala->getAllianz();
        }
        if ($rs->fields[2] == ATTPLANER_TYP_META) {
            $planerMeta = $planerAllianz->GetMeta();
            $userMeta = $userAllianz->GetMeta();
        }

        if ($rs->fields[3] == 0) {
            switch ($rs->fields[2]) {
                case ATTPLANER_TYP_GALA:
                    if ($userGala->getNr() == $planerGala->getNr()) { $isAllowed = 1; }
                    break;
                case ATTPLANER_TYP_ALLIANZ:
                    if ($planerAllianz->getId() == $userAllianz->getId()) { $isAllowed = 1; }
                    break;
                case ATTPLANER_TYP_META:
                    if ($planerMeta->getId() == $userMeta->getId()) { $isAllowed = 1; }
                    break;
            }
        }
        if ($planer->getId() == $user->getId()) {
            $isAllowed = 2;
        }

        return $isAllowed;
    }

    private function zielLoeschen($daten)
    {
        global $tic;

        if ($this->isAllowed($daten['ziel_id']) == 0) { return false; }

        $qry = "DELETE FROM attplaner_ziele WHERE id = %s";
        $rs0 = $tic->db->Execute($this->getName(), $qry, array($daten['ziel_id']));
        $qry = "DELETE FROM attplaner_flotten WHERE ziel_id = %s";
        $rs1 = $tic->db->Execute($this->getName(), $qry, array($daten['ziel_id']));
        if (!$rs0 || !$rs1) { $tic->error($this->getName(), "Fehler beim l&ouml;schen des Ziels."); return false; }

        return true;
    }

    private function flotteAdd($daten)
    {
        global $tic;

        if ($this->getFlotteByZielId($daten['zielId'])) { return false; }
        $aktUser = $tic->mod['Auth']->getActiveUser();
        $userid= $aktUser->getId();
        $flotte = preg_replace('/\D/', '', $daten['flotte']);
        $qry = "INSERT INTO attplaner_flotten (ziel_id, gala,planet, flotte) VALUES (%s,%s, %s, %s)";
        $rs = $tic->db->Execute($this, $qry, array($daten['zielId'], $userid[0],$userid[1], $flotte));
        
        //print_r($this->getZielById($daten['zielID']));
    }

    private function getZielById($id)
    {
        global $tic;

        $qry = "SELECT id, ma, ziel_gala, ziel_planet, freigabe, abflug, text FROM attplaner_ziele WHERE id = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($id));
        if (!$rs || $rs->EOF) { return false; }
        return $rs->fields;
    }

    private function getFlotteByZielId($zielId)
    {
        global $tic;
		//FIXME neue fehler quele da id nun ein array ist
        $qry = "SELECT ziel_id, gala,planet, flotte FROM attplaner_flotten WHERE ziel_id = %s ORDER BY ticuser ASC";
        $rs = $tic->db->Execute($this->getName(), $qry, array($zielId));
        if (!$rs) { return false; }
        $array = array();
        while (!$rs->EOF) {
            array_push($array, $rs->fields[0],array($rs->fields['gala'],$rs->fields['planet']),$rs->fields['flotte']);
            $rs->movenext();
        }
        return $array;
    }
}

?>
