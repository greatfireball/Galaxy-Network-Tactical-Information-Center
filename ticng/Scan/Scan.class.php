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
// Class Scan
//
//

// --------------------------------------------------------------------------------- //

require_once("klassen.php");

class Scan extends TICModule
{
    private $sektorNamen;
    private $schiffNamen;
    private $geschNamen;
    private $schiffGeschNamen;
    private $color;
    private $resultScan = false;

   function Scan()
    {
	parent::__construct(
	array(new Author("Pascal Gollor", "Hugch", "pascal@gollor.org")),
	"2",
	"Scan",
	"API zum Verwalten aller Galaxy-Network Scans und URL Schnellanzeige",
	array(
            "Core" => "5",
            "ADOdb" => "2",
            "Design" => "2",
            "Konst" => "1",
            "UserMan" => "2"
        ));
    
    }

    public function getMenuEntry()
    {
        $main = new MenuEntry("Main", -10);
        $scan = new MenuEntry("Scan anzeigen", 2, $this, "anzeige");
        $scan->addSubEntry(new MenuEntry("Block", 0, $this, "block"));
        $main->addSubEntry($scan);

        return array($main);
    }

    public function getInstallQueriesMySQL() {
        global $tic;
    	return array_merge($tic->mod['UserMan']->getInstallQueriesMySQL(),
    	array(
            "DROP TABLE IF EXISTS scan_header CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `scan_header` (
			  `scan` INT(11) NOT NULL AUTO_INCREMENT ,
			  `time` INT(10) NOT NULL DEFAULT '-1' ,
			  `percent` INT(3) NOT NULL DEFAULT '-1' ,
			  `birth` INT(1) NOT NULL DEFAULT '-1' ,
			  `type` INT(1) NOT NULL DEFAULT '-1' ,
			  `ziel_planet` INT(11) NOT NULL ,
			  `ziel_gala` INT(11) NOT NULL ,
			  `scanner_gala` INT(11) NULL DEFAULT NULL ,
			  `scanner_planet` INT(11) NULL DEFAULT NULL ,
			  PRIMARY KEY (`scan`) ,
			  INDEX `fk_scan_header_TICUser1` (`scanner_planet` ASC, `scanner_gala` ASC) ,
			  INDEX `fk_scan_header_gnplayer` (`ziel_planet` ASC, `ziel_gala` ASC) ,
			  CONSTRAINT `fk_scan_header_TICUser1`
			    FOREIGN KEY (`scanner_planet` , `scanner_gala` )
			    REFERENCES `tic_user` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_scan_header_gnplayer`
			    FOREIGN KEY (`ziel_planet` , `ziel_gala` )
			    REFERENCES `gnplayer` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 1
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS scan_sek CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `scan_sek` (
			  `scan` INT(11) NOT NULL ,
			  `punkte` INT(11) NOT NULL DEFAULT '-1' ,
			  `schiffe` INT(11) NOT NULL DEFAULT '-1' ,
			  `deff` INT(11) NOT NULL DEFAULT '-1' ,
			  `me` INT(11) NOT NULL DEFAULT '-1' ,
			  `ke` INT(11) NOT NULL DEFAULT '-1' ,
			  `ast` INT(11) NOT NULL DEFAULT '-1' ,
			  PRIMARY KEY (`scan`) ,
			  INDEX `fk_scan_sek_scan_header` (`scan` ASC) ,
			  CONSTRAINT `fk_scan_sek_scan_header`
			    FOREIGN KEY (`scan` )
			    REFERENCES `scan_header` (`scan` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS scan_unit CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `scan_unit` (
			  `scan` INT(11) NOT NULL ,
			  `jaeger` INT(11) NOT NULL DEFAULT '-1' ,
			  `bomber` INT(11) NOT NULL DEFAULT '-1' ,
			  `freggs` INT(11) NOT NULL DEFAULT '-1' ,
			  `zerris` INT(11) NOT NULL DEFAULT '-1' ,
			  `kreuzer` INT(11) NOT NULL DEFAULT '-1' ,
			  `schlachter` INT(11) NOT NULL DEFAULT '-1' ,
			  `traeger` INT(11) NOT NULL DEFAULT '-1' ,
			  `kaper` INT(11) NOT NULL DEFAULT '-1' ,
			  `cancs` INT(11) NOT NULL DEFAULT '-1' ,
			  PRIMARY KEY (`scan`) ,
			  INDEX `fk_scan_unit_scan_header` (`scan` ASC) ,
			  CONSTRAINT `fk_scan_unit_scan_header`
			    FOREIGN KEY (`scan` )
			    REFERENCES `scan_header` (`scan` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS scan_mili CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `scan_mili` (
			  `scan` INT(11) NOT NULL ,
			  `jaeger0` INT(11) NOT NULL DEFAULT '-1' ,
			  `bomber0` INT(11) NOT NULL DEFAULT '-1' ,
			  `freggs0` INT(11) NOT NULL DEFAULT '-1' ,
			  `zerris0` INT(11) NOT NULL DEFAULT '-1' ,
			  `kreuzer0` INT(11) NOT NULL DEFAULT '-1' ,
			  `schlachter0` INT(11) NOT NULL DEFAULT '-1' ,
			  `traeger0` INT(11) NOT NULL DEFAULT '-1' ,
			  `kaper0` INT(11) NOT NULL DEFAULT '-1' ,
			  `cancs0` INT(11) NOT NULL DEFAULT '-1' ,
			  `jaeger1` INT(11) NOT NULL DEFAULT '-1' ,
			  `bomber1` INT(11) NOT NULL DEFAULT '-1' ,
			  `freggs1` INT(11) NOT NULL DEFAULT '-1' ,
			  `zerris1` INT(11) NOT NULL DEFAULT '-1' ,
			  `kreuzer1` INT(11) NOT NULL DEFAULT '-1' ,
			  `schlachter1` INT(11) NOT NULL DEFAULT '-1' ,
			  `traeger1` INT(11) NOT NULL DEFAULT '-1' ,
			  `kaper1` INT(11) NOT NULL DEFAULT '-1' ,
			  `cancs1` INT(11) NOT NULL DEFAULT '-1' ,
			  `jaeger2` INT(11) NOT NULL DEFAULT '-1' ,
			  `bomber2` INT(11) NOT NULL DEFAULT '-1' ,
			  `freggs2` INT(11) NOT NULL DEFAULT '-1' ,
			  `zerris2` INT(11) NOT NULL DEFAULT '-1' ,
			  `kreuzer2` INT(11) NOT NULL DEFAULT '-1' ,
			  `schlachter2` INT(11) NOT NULL DEFAULT '-1' ,
			  `traeger2` INT(11) NOT NULL DEFAULT '-1' ,
			  `kaper2` INT(11) NOT NULL DEFAULT '-1' ,
			  `cancs2` INT(11) NOT NULL DEFAULT '-1' ,
			  `flotte1_status` INT(11) NOT NULL DEFAULT '-1' ,
			  `flotte1_ziel` VARCHAR(50) CHARACTER SET 'utf8' NOT NULL ,
			  `flotte2_status` INT(11) NOT NULL DEFAULT '-1' ,
			  `flotte2_ziel` VARCHAR(50) CHARACTER SET 'utf8' NOT NULL ,
			  PRIMARY KEY (`scan`) ,
			  INDEX `fk_scan_mili_scan_header` (`scan` ASC) ,
			  CONSTRAINT `fk_scan_mili_scan_header`
			    FOREIGN KEY (`scan` )
			    REFERENCES `scan_header` (`scan` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS scan_gesch CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `scan_gesch` (
			  `scan` INT(11) NOT NULL ,
			  `lo` INT(11) NOT NULL DEFAULT '-1' ,
			  `lr` INT(11) NOT NULL DEFAULT '-1' ,
			  `mr` INT(11) NOT NULL DEFAULT '-1' ,
			  `sr` INT(11) NOT NULL DEFAULT '-1' ,
			  `aj` INT(11) NOT NULL DEFAULT '-1' ,
			  PRIMARY KEY (`scan`) ,
			  INDEX `fk_scan_gesch_scan_header` (`scan` ASC) ,
			  CONSTRAINT `fk_scan_gesch_scan_header`
			    FOREIGN KEY (`scan` )
			    REFERENCES `scan_header` (`scan` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS scan_news CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `scan_news` (
			  `scan` INT(11) NOT NULL ,
			  `type` INT(11) NOT NULL DEFAULT '-1' ,
			  `gala` INT(11) NOT NULL ,
			  `planet` INT(11) NOT NULL ,
			  `time` INT(10) NOT NULL DEFAULT '-1' ,
			  `fleet` INT(11) NOT NULL DEFAULT '-1' ,
			  `eta` INT(11) NOT NULL DEFAULT '-1' ,
			  PRIMARY KEY (`scan`) ,
			  INDEX `fk_scan_news_scan_header` (`scan` ASC) ,
			  INDEX `fk_scan_news_GNPlayer` (`planet` ASC, `gala` ASC) ,
			  CONSTRAINT `fk_scan_news_scan_header`
			    FOREIGN KEY (`scan` )
			    REFERENCES `scan_header` (`scan` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_scan_news_GNPlayer`
			    FOREIGN KEY (`planet` , `gala` )
			    REFERENCES `gnplayer` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "DROP TABLE IF EXISTS scan_block CASCADE;",
            "CREATE  TABLE IF NOT EXISTS `scan_block` (
			  `user_planet` INT(11) NOT NULL ,
			  `user_gala` INT(11) NOT NULL ,
			  `zeit` INT(10) NOT NULL ,
			  `svs` INT(10) NOT NULL ,
			  `scantyp` INT(1) NULL DEFAULT NULL ,
			  `planet` INT(11) NOT NULL ,
			  `gala` INT(11) NOT NULL ,
			  UNIQUE INDEX `scantyp` (`scantyp` ASC) ,
			  INDEX `fk_scan_block_TICUser` (`user_planet` ASC, `user_gala` ASC) ,
			  INDEX `fk_scan_block_GNPlayer` (`planet` ASC, `gala` ASC) ,
			  CONSTRAINT `fk_scan_block_TICUser`
			    FOREIGN KEY (`user_planet` , `user_gala` )
			    REFERENCES `tic_user` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_scan_block_GNPlayer`
			    FOREIGN KEY (`planet` , `gala` )
			    REFERENCES `gnplayer` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;"
        ));
    }
    public function getInstallQueriesPostgreSQL()
    {
        return array(
            "DROP TABLE scan_header CASCADE;",
            "CREATE TABLE scan_header (
                scan serial NOT NULL PRIMARY KEY ,
                time int NOT NULL DEFAULT '-1',
                percent smallint NOT NULL DEFAULT '-1',
                ziel_gala int NOT NULL DEFAULT '-1',
                ziel_planet int NOT NULL DEFAULT '-1',
                scanner_gala int NULL DEFAULT '-1',
                scanner_planet int NULL DEFAULT '-1',
                birth smallint NOT NULL DEFAULT '-1',
                type smallint NOT NULL DEFAULT '-1'
            );",
            "DROP TABLE scan_sek CASCADE;",
            "CREATE TABLE scan_sek (
                scan int NOT NULL,
                punkte int NOT NULL DEFAULT '-1',
                schiffe int NOT NULL DEFAULT '-1',
                deff int NOT NULL DEFAULT '-1',
                me int NOT NULL DEFAULT '-1',
                ke int NOT NULL DEFAULT '-1',
                ast int NOT NULL DEFAULT '-1',
                PRIMARY KEY (scan)
            );",
            "DROP TABLE scan_unit CASCADE;",
            "CREATE TABLE scan_unit (
                scan int NOT NULL,
                jaeger int NOT NULL DEFAULT '-1',
                bomber int NOT NULL DEFAULT '-1',
                freggs int NOT NULL DEFAULT '-1',
                zerris int NOT NULL DEFAULT '-1',
                kreuzer int NOT NULL DEFAULT '-1',
                schlachter int NOT NULL DEFAULT '-1',
                traeger int NOT NULL DEFAULT '-1',
                kaper int NOT NULL DEFAULT '-1',
                cancs int NOT NULL DEFAULT '-1',
                PRIMARY KEY (scan)
            );",
            "DROP TABLE scan_mili CASCADE;",
            "CREATE TABLE scan_mili (
                scan int NOT NULL,
                jaeger0 int NOT NULL DEFAULT '-1',
                bomber0 int NOT NULL DEFAULT '-1',
                freggs0 int NOT NULL DEFAULT '-1',
                zerris0 int NOT NULL DEFAULT '-1',
                kreuzer0 int NOT NULL DEFAULT '-1',
                schlachter0 int NOT NULL DEFAULT '-1',
                traeger0 int NOT NULL DEFAULT '-1',
                kaper0 int NOT NULL DEFAULT '-1',
                cancs0 int NOT NULL DEFAULT '-1',
                jaeger1 int NOT NULL DEFAULT '-1',
                bomber1 int NOT NULL DEFAULT '-1',
                freggs1 int NOT NULL DEFAULT '-1',
                zerris1 int NOT NULL DEFAULT '-1',
                kreuzer1 int NOT NULL DEFAULT '-1',
                schlachter1 int NOT NULL DEFAULT '-1',
                traeger1 int NOT NULL DEFAULT '-1',
                kaper1 int NOT NULL DEFAULT '-1',
                cancs1 int NOT NULL DEFAULT '-1',
                jaeger2 int NOT NULL DEFAULT '-1',
                bomber2 int NOT NULL DEFAULT '-1',
                freggs2 int NOT NULL DEFAULT '-1',
                zerris2 int NOT NULL DEFAULT '-1',
                kreuzer2 int NOT NULL DEFAULT '-1',
                schlachter2 int NOT NULL DEFAULT '-1',
                traeger2 int NOT NULL DEFAULT '-1',
                kaper2 int NOT NULL DEFAULT '-1',
                cancs2 int NOT NULL DEFAULT '-1',
                flotte1_status int NOT NULL DEFAULT '-1',
                flotte1_ziel varchar(50) NOT NULL,
                flotte2_status int NULL DEFAULT '-1',
                flotte2_ziel varchar(50) NOT NULL,
                PRIMARY KEY (scan)
            );",
            "DROP TABLE scan_gesch CASCADE;",
            "CREATE TABLE scan_gesch (
                scan int NOT NULL,
                lo int NOT NULL DEFAULT '-1',
                lr int NOT NULL DEFAULT '-1',
                mr int NOT NULL DEFAULT '-1',
                sr int NOT NULL DEFAULT '-1',
                aj int NOT NULL DEFAULT '-1',
                PRIMARY KEY (scan)
            );",
            "DROP TABLE scan_news CASCADE;",
            "CREATE TABLE scan_news (
                scan int NOT NULL,
                type int NOT NULL DEFAULT '-1',
                gala int NOT NULL DEFAULT '-1',
                planet int NOT NULL DEFAULT '-1',
                time int NOT NULL DEFAULT '-1',
                fleet int NOT NULL DEFAULT '-1',
                eta int NOT NULL DEFAULT '-1'
            );",
            "DROP TABLE scan_block CASCADE;",
            "CREATE TABLE scan_block (
                userid int NOT NULL,
                zeit int NOT NULL,
                svs int NOT NULL,
                scantyp smallint,
                gala int NOT NULL,
                planet int NOT NULL,
                UNIQUE (scantyp, gala, planet)
            );"
        );
    }

    public function onExecute($menuentry)
    {
        global $tic;

        $scans = array(
            "sek" => SCAN_TYP_SEK,
            "unit" => SCAN_TYP_UNIT,
            "mili" => SCAN_TYP_MILI,
            "gesch" => SCAN_TYP_GESCH,
            "news" => SCAN_TYP_NEWS
        );
        $this->setVar('scans', $scans);
        $adr = $tic->mod['Core']->getTICAdr();
        $this->setVar('scanPfad', $adr."?mod=Scan&menu=".$menuentry);

        switch($menuentry) {
            case "anzeige":
                $this->scanAnzeige();
                break;
            case "block":
                $this->scanBlock();
                break;
            default:
                $this->scanAnzeige();
                break;
        }
    }

// ================================================== Scanblocks ================================================== //

    private function scanBlock()
    {
        $this->cleanBlocks();
        if (isset($_POST['eintragen'])) { $this->saveBlock($_POST); }
        $this->getBlocks();

        $this->setTemplate('scanBlock.tpl');
    }

    private function saveBlock($daten)
    {
        global $tic;

        if (!is_numeric($daten['svs']) || !$tic->mod['Konst']->checkKoords($daten['gala'], $daten['planet'])) {
            $tic->info($this, "Falsche Angaben.");
            return false;
        }

        $aktUser = $tic->mod['Auth']->getActiveUser();
		$Id = $aktUser->getId();
        $qry = "SELECT svs FROM scan_block WHERE user_gala = %s AND user_planet=%s AND scantyp = %s AND gala = %s AND planet = %s;";
        $rs = $tic->db->Execute($this, $qry, array($Id[0],$Id[1], $daten['scantyp'], $daten['gala'], $daten['planet']));
        if (!$rs->EOF) {
            if ($rs->fields[0] >= $daten['svs']) {
                $tic->info($this, "Es gibt schon ein Scanblock mit mehr oder genau so vielen Scanverst&auml;rkern.");
                return true;
            } else {
                $qry = "DELETE FROM scan_block WHERE user_gala = %s AND user_planet=%s AND scantyp = %s AND gala = %s AND planet = %s;";
                $rs = $tic->db->Execute($this, $qry, array($Id[0],$Id[1], $daten['scantyp'], $daten['gala'], $daten['planet']));
            }
        }
		$planetObj=new GNPlayer($daten['gala'],$daten['planet']);
		$planetObj->create();
        $qry = "INSERT INTO scan_block (user_gala,user_planet, zeit, svs, scantyp, gala, planet) VALUES (%s,%s,%s,%s,%s,%s,%s);";
        $rs = $tic->db->Execute($this, $qry,array($Id[0],$Id[1],time(),$daten['svs'],$daten['scantyp'],$daten['gala'],$daten['planet']));

        return true;
    }

    private function getBlocks()
    {
        global $tic;

        $qry = "SELECT user_gala,user_planet, zeit, svs, scantyp, gala, planet FROM scan_block ORDER BY gala, planet ASC;";
        $rs = $tic->db->Execute($this, $qry);

        $blocks = array();

        while(!$rs->EOF) {
            $ary = $rs->fields;
            $ary['user'] = $tic->mod['UserMan']->getUserById(array($ary['user_gala'],$ary['user_planet']));
            $ary['zeit'] = date("H:i:s - d.m.Y", $ary['zeit']);
            $ary['scantyp'] = $this->scanTypToString($ary['scantyp'], true);
            array_push($blocks, $ary);
            $rs->movenext();
        }

        if (count($blocks) == 0) { $blocks = false; }
        $this->setVar("blocks", $blocks);

        return true;
    }

    private function cleanBlocks()
    {
        global $tic;

        $qry = "SELECT scantyp, gala, planet, zeit FROM scan_block ORDER BY gala, planet, scantyp ASC;";
        $rs = $tic->db->Execute($this, $qry);

        while(!$rs->EOF) {
            if ($rs->fields[3] + 24 * 60 * 60 < time()) {
                $qry = "DELETE FROM scan_block WHERE scantyp = %s AND gala = %s AND planet = %s;";
                $tic->db->Execute($this, $qry, array($rs->fields[0], $rs->fields[1], $rs->fields[2]));
            }
            $rs->movenext();
        }

        return true;
    }

    private function deleteBlock($scantyp, $gala, $planet)
    {
        global $tic;

        $qry = "DELETE FROM scan_block WHERE scantyp = %s AND gala = %s AND planet = %s;";
        $rs = $tic->db->Execute($this, $qry, array($scantyp, $gala, $planet));

        if (!$rs) { return false; }
        return true;
    }

// ================================================== alle Scans loeshcen ================================================== //

    private function scansLoeschen($gala = false, $allianz = false, $meta = false)
    {
        global $tic;

        if (is_numeric($gala)) {
            $qry = "DELETE FROM scan_header NATURAL JOIN scan_sek WHERE ziel_gala = %s";
            $rs = $tic->db->Execute($this, $qry, array($gala));
            if (!$rs) {
                $tic->error($this, "Scans der Gala ".$gala." konnten nicht gel&ouml;scht werden.");
            }
        }

        return true;
    }

// ================================================== Scans anzeigen ================================================== //

    private function scanAnzeige()
    {
        global $tic;

        $re = true;
        $this->color = $tic->mod['Konst']->getDefaultIRCColors();
        $this->sektorNamen = $tic->mod['Konst']->getSektorNamen(1, true);
        $this->schiffNamen = $tic->mod['Konst']->getSchiffNamen(1, true);
        $this->geschNamen = $tic->mod['Konst']->getGeschNamen(1, true);
        $this->schiffGeschNamen = $tic->mod['Konst']->getSchiffGeschNamen(1, true);
        $this->setVar("sektorNamen", $this->sektorNamen);
        $this->setVar("schiffNamen", $this->schiffNamen);
        $this->setVar("geschNamen", $this->geschNamen);
        $this->setVar("tic", $tic);
        $showTPL = $tic->mod['Design']->getDesignPath()."scanShow.tpl";
        if (!is_file($showTPL)) { $showTPL = $tic->mod['Design']->getDefaultDesignPath()."scanShow.tpl"; }
        $this->setVar('scanShowTPL', $showTPL);
        $addTPL = $tic->mod['Design']->getDesignPath()."scanAdd.tpl";
        if (!is_file($addTPL)) { $addTPL = $tic->mod['Design']->getDefaultDesignPath()."scanAdd.tpl"; }
        $this->setVar('scanAddTPL', $addTPL);
        if (isset($_REQUEST['gala']) && isset($_REQUEST['planet']) && isset($_REQUEST['scanSearch'])) {
            if ($_REQUEST['planet'] == 0) {
                $re = $this->showGalaScan($_REQUEST['gala']);
            } else {
                $re = $this->showOneScan($_REQUEST['gala'], $_REQUEST['planet']);
            }
        }
        if (isset($_POST['gala']) && isset($_POST['planet']) && isset($_POST['scanAdd'])) {
            $re = $this->showOneScan($_POST['gala'], $_POST['planet']);
        }
        if (isset($_POST['gala']) && isset($_POST['planet']) && isset($_POST['scanSave'])) {
            $re = $this->saveScan($_POST);
        }

        $this->setTemplate('scan.tpl');
    }

    private function showScan($gala, $planet)
    {
        global $tic;

        if (!$tic->mod['Konst']->checkKoords($gala, $planet)) { return false; }
        $scan = false;
        $scanObj = $this->getSekScan($gala, $planet);
        if ($scanObj) {
            $scan = $this->scanHeaderToArray($scanObj);
            $scan = $this->konvertTime($scan);
            $sek = $this->sekScanToArray($scanObj, true);
            $popup = $this->sekIRCPopup($scan, $sek);
            $sek = array('scan' => $sek, 'header' => $scan, 'popup' => $popup);
        } else {
            $sek = false;
        }
        $scanObj = $this->getunitScan($gala, $planet);
        if ($scanObj) {
            $scan = $this->scanHeaderToArray($scanObj);
            $scan = $this->konvertTime($scan);
            $unit = $this->unitScanToArray($scanObj, true);
            $popup = $this->unitIRCPopup($scan, $unit);
            $unit = array('scan' => $unit, 'header' => $scan, 'popup' => $popup);
        } else {
            $unit = false;
        }
        $scanObj = $this->getmiliScan($gala, $planet);
        if ($scanObj) {
            $scan = $this->scanHeaderToArray($scanObj);
            $scan = $this->konvertTime($scan);
            $mili = $this->miliScanToArray($scanObj, true);
            $flotten = $this->fleetToString($scanObj);
            $popup = $this->miliIRCPopup($scan, $mili, $flotten);
            $mili = array('scan' => $mili, 'header' => $scan, 'flotten' => $flotten, 'popup' => $popup);
        } else {
            $mili = false;
        }
        $scanObj = $this->getgeschScan($gala, $planet);
        if ($scanObj) {
            $scan = $this->scanHeaderToArray($scanObj);
            $scan = $this->konvertTime($scan);
            $gesch = $this->geschScanToArray($scanObj, true);
            $popup = $this->geschIRCPopup($scan, $gesch);
            $gesch = array('scan' => $gesch, 'header' => $scan, 'popup' => $popup);
        } else {
            $gesch = false;
        }
        $scanObj = $this->getNewsScan($gala, $planet);
        if ($scanObj) {
            $scan = $this->scanHeaderToArray($scanObj[0]);
            $scan = $this->konvertTime($scan);
            for ($i0 = 0; $i0 < count($scanObj); $i0++) {
                $news[$i0] = $this->newsScanToArray($scanObj[$i0], true);
                $news[$i0]['time'] = $this->konvertTime($news[$i0]['time']);
                $news[$i0]['arrivalTime'] = $this->konvertTime($news[$i0]['arrivalTime']);
            }
            $popup = $this->newsIRCPopup($scan, $news);
            $news = array('scan' => $news, 'header' => $scan, 'popup' => $popup);
        } else {
            $news = false;
        }

        if (!$scan) {
            $tic->info($this, "Kein Scan gefunden.");
            return false;
        }
        return array(
            'sek' => $sek,
            'unit' => $unit,
            'mili' => $mili,
            'gesch' => $gesch,
            'news' => $news
        );
    }

    private function showOneScan($gala, $planet)
    {
        $scan = $this->showscan($gala, $planet);
        if (!$scan) { return false; }
        if ($scan['sek']['scan']) {
            $this->setVar("sekIRCPopup", array($scan['sek']['popup']));
            $this->setVar("sekHeader", array($scan['sek']['header']));
            $this->setVar("sek", array($scan['sek']['scan']));
            $header = array($scan['sek']['header']);
        }
        if ($scan['unit']['scan']) {
            $this->setVar("unitIRCPopup", array($scan['unit']['popup']));
            $this->setVar("unitHeader", array($scan['unit']['header']));
            $this->setVar("unit", array($scan['unit']['scan']));
            $header = array($scan['unit']['header']);
        }
        if ($scan['mili']['scan']) {
            $this->setVar("miliIRCPopup", array($scan['mili']['popup']));
            $this->setVar("miliHeader", array($scan['mili']['header']));
            $this->setVar("mili", array($scan['mili']['scan']));
            $this->setVar("miliFlotte", array($scan['mili']['flotten']));
            $header = array($scan['mili']['header']);
        }
        if ($scan['gesch']['scan']) {
            $this->setVar("geschIRCPopup", array($scan['gesch']['popup']));
            $this->setVar("geschHeader", array($scan['gesch']['header']));
            $this->setVar("gesch", array($scan['gesch']['scan']));
            $header = array($scan['gesch']['header']);
        }
        if ($scan['news']['scan']) {
            $this->setVar("newsIRCPopup", array($scan['news']['popup']));
            $this->setVar("newsHeader", array($scan['news']['header']));
            $this->setVar("news", array($scan['news']['scan']));
            $header = array($scan['news']['header']);
        }
        $this->setVar("oneScan", true);
        $this->setVar("scanHeader", $header);
        $this->setVar("scanAnzahl", 1);

        return true;
    }

    private function getScanGala($gala)
    {
        global $tic;

        if (!is_numeric($gala)) { return false; }
        $planet = array();
        $qry = "SELECT ziel_planet FROM scan_header WHERE ziel_gala = %s;";
        $rs = $tic->db->Execute($this, $qry, array($gala));
        while (!$rs->EOF) {
            array_push($planet, $rs->fields[0]);
            $rs->MoveNext();
        }

        $planet = array_unique($planet);
        sort($planet);
        if (count($planet) == 0) { $planet = false; }
        return $planet;
    }

    private function showGalaScan($gala)
    {
        global $tic;

        if (!$tic->mod['Konst']->checkKoords($gala, 1)) { return false; }
        if (!$galaUsers = $this->getScanGala($gala)) {
            $tic->info($this, "Es konnte kein Scan f&uuml;r die Galaxie gefunden werden!!!");
            return false;
        }

        $header = false; $sek = false; $unit = false; $mili = false; $gesch = false; $news = false; $i0 = 0;
        foreach ($galaUsers as $value) {
            $re = $this->showScan($gala, $value);
            if (!$re) { return false; }
            $scan = false;
            if ($re['sek']['scan']) {
                $sek['popup'][$i0] = $re['sek']['popup'];
                $sek['header'][$i0] = $re['sek']['header'];
                $sek['scan'][$i0] = $re['sek']['scan'];
                $sek['header'][$i0] = $re['sek']['header'];
                $scan = true;
            }
            if ($re['unit']['scan']) {
                $unit['popup'][$i0] = $re['unit']['popup'];
                $unit['header'][$i0] = $re['unit']['header'];
                $unit['scan'][$i0] = $re['unit']['scan'];
                $unit['header'][$i0] = $re['unit']['header'];
                $scan = true;
            }
            if ($re['mili']['scan']) {
                $mili['popup'][$i0] = $re['mili']['popup'];
                $mili['header'][$i0] = $re['mili']['header'];
                $mili['scan'][$i0] = $re['mili']['scan'];
                $mili['flotten'][$i0] = $re['mili']['flotten'];
                $mili['header'][$i0] = $re['mili']['header'];
                $scan = true;
            }
            if ($re['gesch']['scan']) {
                $gesch['popup'][$i0] = $re['gesch']['popup'];
                $gesch['header'][$i0] = $re['gesch']['header'];
                $gesch['scan'][$i0] = $re['gesch']['scan'];
                $gesch['header'][$i0] = $re['gesch']['header'];
                $scan = true;
            }
            if ($re['news']['scan']) {
                $news[$i0] = true;
            }
            if ($scan) { $i0++; }
        }
        if ($sek) {
            $this->setVar("sekIRCPopup", $sek['popup']);
            $this->setVar("sekHeader", $sek['header']);
            $this->setVar("sek", $sek['scan']);
            if (!$header) { $header = $sek['header']; }
        }
        if ($unit) {
            $this->setVar("unitIRCPopup", $unit['popup']);
            $this->setVar("unitHeader", $unit['header']);
            $this->setVar("unit", $unit['scan']);
            if (!$header) { $header = $unit['header']; }
        }
        if ($mili) {
            $this->setVar("miliIRCPopup", $mili['popup']);
            $this->setVar("miliHeader", $mili['header']);
            $this->setVar("mili", $mili['scan']);
            $this->setVar("miliFlotte", $mili['flotten']);
            if (!$header) { $header = $mili['header']; }
        }
        if ($gesch) {
            $this->setVar("geschIRCPopup", $gesch['popup']);
            $this->setVar("geschHeader", $gesch['header']);
            $this->setVar("gesch", $gesch['scan']);
            if (!$header) { $header = $gesch['header']; }
        }
        if ($news) {
            $this->setVar("news", $news);
        }
        $this->setVar("oneScan", false);
        $this->setVar("scanHeader", $header);
        $this->setVar("scanAnzahl", $i0);
        $this->setVar("galaUsers", $galaUsers);

        return true;
    }

    public function getSekScan($gala, $planet = false)
    {
        global $tic;

        if (is_object($gala)) {
            $obj = $gala;
            $gala = $obj->getGalaxie();
            $planet = $obj->getPlanet();
        }

        if (!is_numeric($gala) || !is_numeric($planet)) { return false; }
        $qry = "SELECT scan_header.scan, time, percent, ziel_gala, ziel_planet, scanner_gala, scanner_planet, birth, "
            ."type, punkte, schiffe, deff, me, ke, ast "
            ."FROM scan_sek NATURAL JOIN scan_header WHERE ziel_gala = %s AND ziel_planet = %s AND type = 1";
        $rs = $tic->db->Execute($this, $qry, array($gala, $planet));
        if ($rs->EOF) {
            return false;
        } else {
            $array = array();
            for ($i0 = 0; $i0 <= 14; $i0++) {
                array_push($array, $rs->fields[$i0]);
            }
            return new sekScan($array);
        }
    }

    public function getUnitScan($gala, $planet = false)
    {
        global $tic;

        if (is_object($gala)) {
            $obj = $gala;
            $gala = $obj->getGalaxie();
            $planet = $obj->getPlanet();
        }

        if (!is_numeric($gala) || !is_numeric($planet)) { return false; }
        $qry = "SELECT scan_header.scan, time, percent, ziel_gala, ziel_planet, scanner_gala, scanner_planet, birth, type, jaeger, bomber, freggs, zerris, kreuzer, schlachter, traeger, kaper, cancs "
            ."FROM scan_unit NATURAL JOIN scan_header WHERE ziel_gala = %s AND ziel_planet = %s AND type = 2";
        $rs = $tic->db->Execute($this, $qry, array($gala, $planet));
        if ($rs->EOF) {
            return false;
        } else {
            $array = array();
            for ($i0 = 0; $i0 <= 17; $i0++) {
                array_push($array, $rs->fields[$i0]);
            }
            return new unitScan($array);
        }
    }

    public function getMiliScan($gala, $planet = false)
    {
        global $tic;

        if (is_object($gala)) {
            $obj = $gala;
            $gala = $obj->getGalaxie();
            $planet = $obj->getPlanet();
        }

        if (!is_numeric($gala) || !is_numeric($planet)) { return false; }
        $qry = "SELECT scan_header.scan, time, percent, ziel_gala, ziel_planet, scanner_gala, scanner_planet, birth, type, "
            ."jaeger0, bomber0, freggs0, zerris0, kreuzer0, schlachter0, traeger0, kaper0, cancs0, "
            ."jaeger1, bomber1, freggs1, zerris1, kreuzer1, schlachter1, traeger1, kaper1, cancs1, flotte1_status, flotte1_ziel, "
            ."jaeger2, bomber2, freggs2, zerris2, kreuzer2, schlachter2, traeger2, kaper2, cancs2, flotte2_status, flotte2_ziel "
            ."FROM scan_mili NATURAL JOIN scan_header WHERE ziel_gala = %s AND ziel_planet = %s AND type = 3";
        $rs = $tic->db->Execute($this, $qry, array($gala, $planet));
        if ($rs->EOF) {
            return false;
        } else {
            $array = array();
            for ($i0 = 0; $i0 <= 39; $i0++) {
                array_push($array, $rs->fields[$i0]);
            }
            return new miliScan($array);
        }
    }

    public function getGeschScan($gala, $planet = false)
    {
        global $tic;

        if (is_object($gala)) {
            $obj = $gala;
            $gala = $obj->getGalaxie();
            $planet = $obj->getPlanet();
        }

        if (!is_numeric($gala) || !is_numeric($planet)) { return false; }
        $qry = "SELECT scan_header.scan, time, percent, ziel_gala, ziel_planet, scanner_gala, scanner_planet, birth, type, lo, lr, mr, sr, aj "
            ."FROM scan_gesch NATURAL JOIN scan_header WHERE ziel_gala = %s AND ziel_planet = %s AND type = 4";
        $rs = $tic->db->Execute($this, $qry, array($gala, $planet));
        if ($rs->EOF) {
            return false;
        } else {
            $array = array();
            for ($i0 = 0; $i0 <= 13; $i0++) {
                array_push($array, $rs->fields[$i0]);
            }
            return new geschScan($array);
        }
    }

    public function getNewsScan($gala, $planet = false)
    {
        global $tic;

        if (is_object($gala)) {
            $obj = $gala;
            $gala = $obj->getGalaxie();
            $planet = $obj->getPlanet();
        }

        $news = array();
        if (!is_numeric($gala) || !is_numeric($planet)) { return false; }
        $qry = "SELECT h.scan , h.time, percent, ziel_gala, ziel_planet, scanner_gala, scanner_planet, birth, h.type, n.type, gala, planet, n.time, fleet, eta "
            ."FROM scan_news AS n NATURAL JOIN scan_header AS h WHERE ziel_gala = %s AND ziel_planet = %s AND h.type = 5";
        $rs = $tic->db->Execute($this, $qry, array($gala, $planet));
        while (!$rs->EOF) {
            $array = array();
            for ($i0 = 0; $i0 <= 14; $i0++) {
                array_push($array, $rs->fields[$i0]);
            }
            array_push($news, new newsScan($array));
            $rs->MoveNext();
        }
        return $news;
    }

// ================================================== Scans speichern ================================================== //

    private function saveScan($daten)
    {
        global $tic;

        if (!$tic->mod['Konst']->checkKoords($daten['gala'], $daten['planet'])) { return false; }

        $scan = array(
            'nick' => $daten['nick'],
            'gala' => $daten['gala'],
            'planet' => $daten['planet'],
            'birth' => SCAN_BIRTH_MANUELL,
            'gen' => 99
        );

        switch ($daten['scantyp']) {
        case SCAN_TYP_SEK:
            foreach ($this->sektorNamen as $key => $value) {
                $sek[$key] = preg_replace('/\D/', '', $daten[$key]);
            }
            if (!$this->addSekScan($scan, $sek)) { return false; }
            break;
        case SCAN_TYP_UNIT:
            foreach ($this->schiffNamen as $key => $value) {
                $unit[$key] = preg_replace('/\D/', '', $daten[$key]);
            }
            if (!$this->addUnitScan($scan, $unit)) { return false; }
            break;
        case SCAN_TYP_MILI:
            foreach ($this->schiffNamen as $key => $value) {
                $mili[$key][0] = preg_replace('/\D/', '', $daten[$key."_0"]);
                $mili[$key][1] = preg_replace('/\D/', '', $daten[$key."_1"]);
                $mili[$key][2] = preg_replace('/\D/', '', $daten[$key."_2"]);
            }
            $mili['flotte'] = array(
                array('status' => "Im", 'ziel' => "Orbit"),
                array('status' => "Im", 'ziel' => "Orbit"),
                array('status' => "Im", 'ziel' => "Orbit")
            );
            if (!$this->addMiliScan($scan, $mili, false)) { return false; }
            break;
        case SCAN_TYP_GESCH:
            foreach ($this->geschNamen as $key => $value) {
                $gesch[$key] = preg_replace('/\D/', '', $daten[$key]);
            }
            if (!$this->addGeschScan($scan, $gesch)) { return false; }
            break;
        }

        return true;
    }
    private function saveHeader($scan, $typ)
    {
        global $tic;
		$planetObj=new GNPlayer($scan['gala'],$scan['planet']);
		$planetObj->create();
        if ($scan['nick'] == "") {
            $tic->error($this, "Dies ist kein g&uuml;ltiger Nick!!!");
            return false;
        }
        if (!$tic->mod['Konst']->checkKoords($scan['gala'], $scan['planet'])) { return false; }

        $aktUser = $tic->mod['Auth']->getActiveUser();
        $player = new GNPlayer($scan['gala'], $scan['planet'], $scan['nick']);
        $player->create();

        $scanTyp = $this->scanTypToString($typ);

        $qry = "SELECT scan FROM scan_header WHERE ziel_gala = %s AND ziel_planet = %s AND type = %s";
        $rs = $tic->db->Execute($this, $qry, array($scan['gala'], $scan['planet'], $typ));
        while (!$rs->EOF) {
            $qry = "DELETE FROM scan_header WHERE scan = %s";
            $tic->db->Execute($this, $qry, array($rs->fields[0]));
            $qry = "DELETE FROM scan_".$scanTyp." WHERE scan = %s";
            $tic->db->Execute($this, $qry, array($rs->fields[0]));
            $rs->MoveNext();
        }
		
        $qry = "INSERT INTO scan_header (time, percent, ziel_gala, ziel_planet, scanner_gala, scanner_planet, birth, type)"
            ." VALUES (%s, %s, %s, %s, %s, %s, %s, %s)";
        $rs = $tic->db->Execute($this, $qry, array(
            time(), $scan['gen'], $scan['gala'], $scan['planet'],$aktUser->getGalaxie(), $aktUser->getPlanet(), $scan['birth'], $typ)
        );
        if (!$rs) {
            $tic->error($this, "Problem beim speichern des Scans.");
            return false;
        }

        $id = $tic->db->Insert_ID();
        $param = false;
        if ($this->resultScan) { $param = "Resultierend aus Sektorscan"; }
        $this->deleteBlock($typ, $scan['gala'], $scan['planet']);
        $typ = (int) $typ + 300;
        $tic->mod['Logging']->log($typ, $player, $param);

        return $id;
    }

    public function addSekScan($scan, $sek)
    {
        global $tic;

        $this->resultScan = false;
        $scanID = $this->saveHeader($scan, SCAN_TYP_SEK);
        if (!$scanID) { return false; }
        $qry = "INSERT INTO scan_sek (scan ,punkte, schiffe, deff, me, ke, ast) VALUES (%s, %s, %s, %s, %s, %s, %s)";
        $rs = $tic->db->Execute($this, $qry, array(
            $scanID, $sek['punkte'], $sek['schiffe'], $sek['deff'], $sek['me'], $sek['ke'], $sek['ast'])
        );
        if (!$rs) {
            $tic->error($this, "Problem beim speichern des Sektorscans.");
            return false;
        }

        $i0 = 0;
        $schiffGeschNamen = $tic->mod['Konst']->getSchiffGeschNamen();
        foreach ($schiffGeschNamen as $value) {
            $i0++;
            if ($i0 < 10) {
                for ($i1 = 0; $i1 < 3; $i1++) { $mili[$value[0]][$i1] = 0; }
            } else {
                $gesch[$value[0]] = 0;
            }
        }
        $mili['flotte'] = array(
            array('status' => 0, 'ziel' => "orbit"),
            array('status' => 0, 'ziel' => "orbit"),
            array('status' => 0, 'ziel' => "orbit")
        );

        if ($sek['schiffe'] == 0) { $this->resultScan = true; $this->addMiliScan($scan, $mili, false); }
        if ($sek['deff'] == 0) { $this->resultScan = true; $this->addGeschScan($scan, $gesch); }

        return true;
    }

    public function addUnitScan($scan, $unit)
    {
        global $tic;

        $scanID = $this->saveHeader($scan, SCAN_TYP_UNIT);
        if (!$scanID) { return false; }
        $qry = "INSERT INTO scan_unit (scan , jaeger, bomber, freggs, zerris, kreuzer, schlachter, traeger, kaper, cancs) VALUES ("
            ."%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";
        $rs = $tic->db->Execute($this, $qry, array(
            $scanID, $unit['jaeger'], $unit['bomber'], $unit['freggs'], $unit['zerris'],
            $unit['kreuzer'], $unit['schlachter'] ,$unit['traeger'] ,$unit['kaper'] ,$unit['cancs'])
        );
        if (!$rs) {
            $tic->error($this, "Problem beim speichern des Einheitenssans.");
            return false;
        }

        return true;
    }

    public function addMiliScan($scan, $mili, $flotte = true)
    {
        global $tic;

        if (!$flotte) {
            $mili['flotte'] = array(
                array('status' => 0, 'ziel' => "orbit"),
                array('status' => false, 'ziel' => false),
                array('status' => false, 'ziel' => false)
            );
        }

        foreach ($mili as $key => $value) {
            if ($key != "flotte") {
                if (!isset($mili[$key][1])) { $mili[$key][1] = 0; }
                if (!isset($mili[$key][2])) { $mili[$key][2] = 0; }
                if (!isset($unit[$key])) { $unit[$key] = 0; }
                $unit[$key] = $unit[$key] + $mili[$key][0] + $mili[$key][1] + $mili[$key][2];
            } else {
                foreach ($value as $key1 => $value1) {
                    if ($value1['status'] === false) {
                        $mili[$key][$key1]['status'] = -2;
                        $mili[$key][$key1]['ziel'] = -2;
                    } else {
                        $mili[$key][$key1] = $this->konvertFlotte($value1);
                        if (!$mili[$key][$key1]) { return false; }
                        if ($value1['status'] == 1) {
                            $user = $tic->mod['UserMan']->getUserByNick($value1['ziel']);
                            if ($user) {
                                $flotte = new Flottenbewegung($scan['gala'], $scan['planet'], $key1, $user->getGalaxie(), $user->getPlanet(), true, false, false, false, false);
                                $tic->mod['Taktik']->updateFlotte($flotte);
                            }
                        }
                    }
                }
            }
        }
        $this->addUnitScan($scan, $unit);

        $scanID = $this->saveHeader($scan, SCAN_TYP_MILI);
        if (!$scanID) { return false; }
        $qry = "INSERT INTO scan_mili (scan , "
            ."jaeger0, bomber0, freggs0, zerris0, kreuzer0, schlachter0, traeger0, kaper0, cancs0, "
            ."jaeger1, bomber1, freggs1, zerris1, kreuzer1, schlachter1, traeger1, kaper1, cancs1, flotte1_status, flotte1_ziel, "
            ."jaeger2, bomber2, freggs2, zerris2, kreuzer2, schlachter2, traeger2, kaper2, cancs2, flotte2_status, flotte2_ziel"
            .") VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s ,%s, %s)";
        $rs = $tic->db->Execute($this, $qry, array($scanID,
            $mili['jaeger'][0], $mili['bomber'][0], $mili['freggs'][0], $mili['zerris'][0], $mili['kreuzer'][0],
                $mili['schlachter'][0], $mili['traeger'][0], $mili['kaper'][0], $mili['cancs'][0],
            $mili['jaeger'][1], $mili['bomber'][1], $mili['freggs'][1], $mili['zerris'][1], $mili['kreuzer'][1],
                $mili['schlachter'][1], $mili['traeger'][1], $mili['kaper'][1], $mili['cancs'][1], $mili['flotte'][1]['status'], $mili['flotte'][1]['ziel'],
            $mili['jaeger'][2], $mili['bomber'][2], $mili['freggs'][2], $mili['zerris'][2], $mili['kreuzer'][2],
                $mili['schlachter'][2], $mili['traeger'][2], $mili['kaper'][2], $mili['cancs'][2], $mili['flotte'][2]['status'], $mili['flotte'][2]['ziel']
        ));
        if (!$rs) {
            $tic->error($this, "Problem beim speichern des Milit&auml;rscans.");
            return false;
        }

        return true;
    }

    public function addGeschScan($scan, $gesch)
    {
        global $tic;

        $scanID = $this->saveHeader($scan, SCAN_TYP_GESCH);
        if (!$scanID) { return false; }
        $qry = "INSERT INTO scan_gesch (scan , lo, lr, mr, sr, aj) VALUES (%s, %s, %s, %s, %s, %s)";
        $rs = $tic->db->Execute($this, $qry, array($scanID, $gesch['lo'], $gesch['lr'], $gesch['mr'], $gesch['sr'], $gesch['aj']));
        if (!$rs) {
            $tic->error($this, "Problem beim speichern des Gesch&uuml;tzscans.");
            return false;
        }

        return true;
    }

    public function addNewsScan($scan, $news)
    {
        global $tic;

        $scanID = $this->saveHeader($scan, SCAN_TYP_NEWS);
        if (!$scanID) { return false; }
        foreach ($news as $value) {
            $koord = preg_split('/:/', $value['koords']);
            $date = preg_split('/\-/', $value['date']);
            $time = preg_split('/:/', $value['time']);
            $stamp = mktime($time[0], $time[1], $time[2], $date[1], $date[0], $date[2]);
            $eta = $this->konvertETA($value['eta']);
            $qry = "INSERT INTO scan_news (scan , type, gala, planet, time, fleet, eta) VALUES (%s, %s, %s, %s, %s, %s, %s)";
            $rs = $tic->db->Execute($this, $qry, array($scanID, $value['typ'], $koord[0], $koord[1], $stamp, $value['flotte'], $eta));
            if (!$rs) {
                $tic->error($this, "Problem beim speichern des Newsscans.");
                return false;
            }
            $player = new GNPlayer($value['gala'], $koord[0], $koord[1]);
            if (!$player->create()) {
                $tic->error($this, "User konnte nicht angelegt werden.");
                return false;
            }
        }

        return true;
    }

// ================================================== Konvertierungsfunktionen ================================================== //

    private function scanTypToSmalString($typ)
    {
        switch ($typ) {
            case 1:
                // Sektorscan
                return "S";
            case 2:
                // Einheitenscan
                return "E";
            case 3:
                // Militaerscan
                return "M";
            case 4:
                // Geschuetzscan
                return "G";
            case 5:
                // Newsscan
                return "N";
            default:
                return false;
        }
    }

    private function scanSmalStringToTyp($scan)
    {
        switch ($scan) {
            case "S":
                // Sektorscan
                return 1;
            case "E":
                // Einheitenscan
                return 2;
            case "M":
                // Militaerscan
                return 3;
            case "G":
                // Geschuetzscan
                return 4;
            case "N":
                // Newsscan
                return 5;
            default:
                return false;
        }
    }

    public function getScansFrom($gala, $planet = false, $format = "normal")
    {
        global $tic;

        if (is_object($gala)) {
        	echo 'TEST';
            $obj = $gala;
            $gala = $obj->getGalaxie();
            $planet = $obj->getPlanet();
        }

        if (!is_numeric($gala) || !is_numeric($planet)) {
            $tic->error($this, "Keine zul&auml;ssigen Koordinaten!!!");
            return false;
        }

        $scan = null;
        $qry = "SELECT type,time FROM scan_header WHERE ziel_gala = %s AND ziel_planet = %s ORDER BY type ASC;";
        $rs = $tic->db->Execute($this, $qry, array($gala, $planet));
        while (!$rs->EOF) {
            $typ = $this->scanTypToSmalString($rs->fields[0]);
            if ($this->ifScanOld($rs->fields[1])) { $typ = $this->scanOldColor($typ, $format); }
            switch ($format) {
                case "normal":
                    $scan .= $typ;
                    break;
                case "irc":
                    break;
                case "extension":
                    if (!is_array($scan)) { $scan = array(); }
                    array_push($scan, $typ);
                    break;
            }
            $rs->MoveNext();
        }

        if ($format == "extension") {
            $string = "SEMGN";
            $string = preg_split('//', $string, -1, PREG_SPLIT_NO_EMPTY);
            $len0 = count($scan);
            $ary = array();
            for ($i0 = 0; $i0 < $len0; $i0++) {
                $ary[$this->scanSmalStringToTyp(preg_replace('/[^A-Z]/', '', $scan[$i0])) - 1] = $scan[$i0];
            }
            $scan = $ary;
            foreach ($string as $key => $value) {
                if (!isset($scan[$key])) { $scan[$key] = '<font color="grey">'.$value.'</font>'; }
            }
            $hilf = '';
            foreach ($scan as $value) {
                $hilf .= $value;
            }
            $scan = $hilf;
        }

        if ($scan == null) { return false; } else { return $scan; }
    }

    public function getScansForExtension($gala, $planeten)
    {
        $planeten = substr($planeten, 0, -1);
        $planeten = preg_split('/:/', $planeten);
        $scans = '';

        foreach ($planeten as $value) {
            $scans .= $this->getScansFrom($gala, $value, "extension").':';
        }

        $scans = substr($scans, 0, -1);
        return $scans;
    }

    public function konvertScanToHTML($obj)
    {
        global $tic;

        if (!is_object($obj)) { return false; }
        
        switch ($obj->getType()) {
            case SCAN_TYP_SEK:
                $aus = '<table border="1">';
                $aus .= '';
                $aus .= '';
                $aus .= '';
                $aus .= '';
                $aus .= '';
                $aus .= '</table>';
                return $aus;
            case SCAN_TYP_UNIT:
                $scan = $this->unitScanToArray($obj, true);
                $schiffe = $tic->mod['Konst']->getSchiffNamen(1, true);

                $aus = "<table border='0' class='scans'>";
                $i = 0;
                foreach ($schiffe as $key => $value) {
                    if ($i % 2 == 0) {
                        $aus .= "<tr class='scan_dunkel'>";
                    } else {
                        $aus .= "<tr>";
                    }
                    $aus .= "<td width='75'>".$value[0]."</td>";
                    $aus .= "<td width='50'>".$scan[$key]."</td>";
                    $aus .= "</tr>";
                    $i++;
                }
                $aus .= "</table>";
                return $aus;
            case SCAN_TYP_MILI:
                return $aus;
            case SCAN_TYP_GESCH:
                return $aus;
            default:
                return false;
        }
    }

    public function fleetToString($obj)
    {
        global $tic;

        $flotte = array("Im Orbit", $obj->getFlottenStatus(1), $obj->getFlottenStatus(2));
        $ziel = array("", $obj->getFlottenZiel(1), $obj->getFlottenZiel(2));
        for ($i0 = 1; $i0 < 3; $i0++) {
            $user = $tic->mod['UserMan']->getPlayerByNick($ziel[$i0]);
            if ($user) { $ziel[$i0] = $ziel[$i0]." (".$user->getKoords().")"; }
            switch ($flotte[$i0]) {
            case -1:
            case -2:
                $flotte[$i0] = "N/A";
                break 1;
            case 0:
                $flotte[$i0] = "Im Orbit";
                break 1;
            case 1;
                $flotte[$i0] = "Angriffsflug<br />".$ziel[$i0];
                break 1;
            case 2;
                $flotte[$i0] = "Verteidigungsflug<br />".$ziel[$i0];
                break 1;
            case 3;
                $flotte[$i0] = "R&uuml;ckflug";
                break 1;
            }
        }

        return $flotte;
    }

    private function newsTypeToString($typ)
    {
        switch ($typ) {
        case 1:
            return "Angriff";
        case 2:
            return "Verteidigung";
        case 3:
            return "R&uuml;ckzug";
        default:
            return false;
        }
    }

// ======================== Scans zu Array ======================== //

    public function scanHeaderToArray($obj)
    {
        $scan = array(
            'id' => $obj->getID(),
            'gala' => $obj->getzielGala(),
            'planet' => $obj->getzielPlanet(),
            'koords' => $obj->getzielGala().":".$obj->getzielPlanet(),
            'nick' => $obj->getzielNick(),
            'time' => $obj->getTime(),
            'gen' => $obj->getGen(),
            'scanner' => array(
                'gala' => $obj->getScannerGala(),
                'planet' => $obj->getScannerPlanet(),
                'nick' => $obj->getScannerNick(),
                'koords' => $obj->getScannerGala().":".$obj->getScannerPlanet(),
                'text' => $obj->getScannerNick()." (".$obj->getScannerGala().":".$obj->getScannerPlanet().")",
                'scans' => $this->getScansFrom($obj->getScannerGala(), $obj->getScannerPlanet())
            ),
            'birth' => $this->konvertBirth($obj->getBirth()),
            'old' => $this->ifScanOld($obj->getTime())
        );

        return $scan;
    }

    public function sekScanToArray($scan, $string = false)
    {
        global $tic;

        $sek = array(
            'punkte' => $scan->getPunkte(),
            'schiffe' => $scan->getSchiffe(),
            'deff' => $scan->getDeff(),
            'me' => $scan->getME(),
            'ke' => $scan->getKE(),
            'ast' => $scan->getAst()
        );
        if ($string) {
            foreach ($sek as $key => $value) {
                $sek[$key] = number_format($sek[$key], 0, ',', '.');
            }
        }

        return $sek;
    }

    public function unitScanToArray($scan, $string = false)
    {
        $unit = array(
            'jaeger' => $scan->getJaeger(),
            'bomber' => $scan->getBomber(),
            'freggs' => $scan->getFreggs(),
            'zerris' => $scan->getZerris(),
            'kreuzer' => $scan->getKreuzer(),
            'schlachter' => $scan->getSchlachter(),
            'traeger' => $scan->getTraeger(),
            'kaper' => $scan->getKaper(),
            'cancs' => $scan->getCancs()
        );
        if ($string) {
            foreach ($unit as $key => $value) {
                $unit[$key] = number_format($unit[$key], 0, ',', '.');
            }
        }

        return $unit;
    }

    public function miliScanToArray($scan, $string = false)
    {
        $mili = array(
            'jaeger' => array($scan->getJaeger(0), $scan->getJaeger(1), $scan->getJaeger(2)),
            'bomber' => array($scan->getBomber(0),$scan->getBomber(1), $scan->getBomber(2)),
            'freggs' => array($scan->getFreggs(0), $scan->getFreggs(1), $scan->getFreggs(2)),
            'zerris' => array($scan->getZerris(0), $scan->getZerris(1), $scan->getZerris(2)),
            'kreuzer' => array($scan->getKreuzer(0), $scan->getKreuzer(1), $scan->getKreuzer(2)),
            'schlachter' => array($scan->getSchlachter(0), $scan->getSchlachter(1), $scan->getSchlachter(2)),
            'traeger' => array($scan->getTraeger(0), $scan->getTraeger(1), $scan->getTraeger(2)),
            'kaper' => array($scan->getKaper(0), $scan->getKaper(1), $scan->getKaper(2)),
            'cancs' => array($scan->getCancs(0), $scan->getCancs(1), $scan->getCancs(2))
        );
        if ($string) {
            foreach ($mili as $key0 => $value0) {
                foreach ($value0 as $key1 => $value1) {
                    $mili[$key0][$key1] = number_format($mili[$key0][$key1], 0, ',', '.');
                }
            }
        }

        return $mili;
    }

    public function geschScanToArray($scan, $string = false)
    {
        $gesch = array(
            'lo' => $scan->getLO(),
            'lr' => $scan->getLR(),
            'mr' => $scan->getMR(),
            'sr' => $scan->getSR(),
            'aj' => $scan->getAJ()
        );
        if ($string) {
            foreach ($gesch as $key => $value) {
                $gesch[$key] = number_format($gesch[$key], 0, ',', '.');
            }
        }

        return $gesch;
    }

    public function newsScanToArray($scan)
    {
        global $tic;

        $nick = $tic->mod['UserMan']->getPlayerByKoords($scan->getGalaxie(), $scan->getPlanet());
        $nick = $nick->getNick();
        $news = array(
            'type' => $this->newsTypeToString($scan->getType()),
            'gala' => $scan->getGalaxie(),
            'planet' => $scan->getPlanet(),
            'koords' => $scan->getGalaxie().":".$scan->getPlanet(),
            'nick' => $nick,
            'fleet' => $scan->getFleet(),
            'eta' => $scan->getETA(),
            'time' => $scan->getNewsTime(),
            'arrivalTime' => $scan->getArrivalTime()
        );

        return $news;
    }

    private function ifScanOld($time)
    {
        if (is_object($time)) { $time = $time->getTime(); }
        if (time() - $time > (SCAN_OLD)) {
            return true;
        } else {
            return false;
        }
    }

    private function scanOldColor($time, $format = "normal")
    {
        global $tic;

        switch ($format) {
            case "normal":
                return '<font color='.SCAN_OLD_COLOR.'>'.$time.'</font>';
            case "irc":
                return $tic->mod['Konst']->getIRCColor(SCAN_OLD_COLOR, 'white').$time;
            case "extension":
                return '<font color="orange">'.$time.'</font>';
        }
    }

// ======================== IRC-Popups ======================== //

    private function sekIRCPopup($scan, $sek)
    {
        $ausgabe = $this->color[0]."Sektorscan (".$this->color[1].$scan['gen']."%".$this->color[0].") ";
        $ausgabe .= $scan['nick']." (".$this->color[1].$scan['koords'].$this->color[0].")<br />";
        $i0 = 0;
        foreach ($this->sektorNamen as $key => $value) {
            $ausgabe .= $this->color[2].$value[1].": ".$this->color[3].$sek[$key];
            if ($i0 % 2 == 0) { $ausgabe .= " "; } else { $ausgabe .= "<br />"; }
            $i0++;
        }
        if ($scan['old'] == true) { $scan['time'] = $this->scanOldColor($scan['time'], "irc"); }
        $ausgabe .= $this->color[0]."Sektorscan (".$this->color[1].$scan['time'].$this->color[0].") ";
        $ausgabe .= $scan['scanner']['nick']." (".$this->color[1].$scan['scanner']['koords'].$this->color[0].") - Herkunft: ".$scan['birth'];
        $ausgabe = '<html><head><title>IRC-Sektorscan</title></head><body><pre>'.$ausgabe.'</pre></body></html>';

        return array('text' => $ausgabe);
    }

    private function unitIRCPopup($scan, $unit)
    {
        $ausgabe = $this->color[0]."Einheitenscan (".$this->color[1].$scan['gen']."%".$this->color[0].") ";
        $ausgabe .= $scan['nick']." (".$this->color[1].$scan['koords'].$this->color[0].")<br />";
        $i0 = 0;
        foreach ($this->schiffNamen as $key => $value) {
            $i0++;
            $ausgabe .= $this->color[2].$value[0].": ".$this->color[3].$unit[$key];
            if ($i0 % 3 == 0) { $ausgabe .= "<br />"; } else { $ausgabe .= " "; }
        }
        if ($scan['old'] == true) { $scan['time'] = $this->scanOldColor($scan['time'], "irc"); }
        $ausgabe .= $this->color[0]."Einheitenscan (".$this->color[1].$scan['time'].$this->color[0].") ";
        $ausgabe .= $scan['scanner']['nick']." (".$this->color[1].$scan['scanner']['koords'].$this->color[0].") - Herkunft: ".$scan['birth'];;
        $ausgabe = '<html><head><title>IRC-Einheitenscan</title></head><body><pre>'.$ausgabe.'</pre></body></html>';

        return array('text' => $ausgabe);
    }

    private function miliIRCPopup($scan, $mili, $flotten)
    {
        $ausgabe = $this->color[0]."Milit&auml;rscan (".$this->color[1].$scan['gen']."%".$this->color[0].") ";
        $ausgabe .= $scan['nick']." (".$this->color[1].$scan['koords'].$this->color[0].")<br />";
        for ($i0 = 0; $i0 < 3; $i0++) {
            switch ($i0) {
            case 0:
                $ausgabe .= $this->color[2]."Orbit: ";
                break 1;
            case 1:
                $ausgabe .= $this->color[2]."Flotte 1: ";
                break 1;
            case 2:
                $ausgabe .= $this->color[2]."Flotte 2: ";
                break 1;
            }
            foreach ($this->schiffNamen as $key => $value) {
                if ($mili[$key][$i0] != 0) {
                    $flotte[$i0] = true;
                    $ausgabe .= $this->color[2].$value[0].": ".$this->color[3].$mili[$key][$i0]." ";
                }
            }
            $flotten[$i0] = preg_replace('/<br \/>/', ' ', $flotten[$i0]);
            if (isset($flotte[$i0]) && $i0 != 0) { $ausgabe .= $this->color[2]." [".$flotten[$i0]."]"; }
            $ausgabe = trim($ausgabe)."<br />";
        }
        if ($scan['old'] == true) { $scan['time'] = $this->scanOldColor($scan['time'], "irc"); }
        $ausgabe .= $this->color[0]."Milit&auml;rscan (".$this->color[1].$scan['time'].$this->color[0].") ";
        $ausgabe .= $scan['scanner']['nick']." (".$this->color[1].$scan['scanner']['koords'].$this->color[0].") - Herkunft: ".$scan['birth'];;
        $ausgabe = '<html><head><title>IRC-Milit&auml;rscan</title></head><body><pre>'.$ausgabe.'</pre></body></html>';

        return array('text' => $ausgabe);
    }

    private function geschIRCPopup($scan, $gesch)
    {
        $ausgabe = $this->color[0]."Gesch&uuml;tzscan (".$this->color[1].$scan['gen']."%".$this->color[0].") ";
        $ausgabe .= $scan['nick']." (".$this->color[1].$scan['koords'].$this->color[0].")<br />";
        $i0 = 0;
        foreach ($this->geschNamen as $key => $value) {
            $i0++;
            $ausgabe .= $this->color[2].$value[1].": ".$this->color[3].$gesch[$key];
            if ($i0 % 3 == 0 || $i0 == 5) { $ausgabe .= "<br />"; } else { $ausgabe .= " "; }
        }
        if ($scan['old'] == true) { $scan['time'] = $this->scanOldColor($scan['time'], "irc"); }
        $ausgabe .= $this->color[0]."Gesch&uuml;tzscan (".$this->color[1].$scan['time'].$this->color[0].") ";
        $ausgabe .= $scan['scanner']['nick']." (".$this->color[1].$scan['scanner']['koords'].$this->color[0].") - Herkunft: ".$scan['birth'];;
        $ausgabe = '<html><head><title>IRC-Gesch&uuml;tzscan</title></head><body><pre>'.$ausgabe.'</pre></body></html>';

        return array('text' => $ausgabe);
    }

    private function newsIRCPopup($scan, $news)
    {
        $ausgabe = $this->color[0]."Newsscan (".$this->color[1].$scan['gen']."%".$this->color[0].") ";
        $ausgabe .= $scan['nick']." (".$this->color[1].$scan['koords'].$this->color[0].")<br />";
        for ($i0 = 0; $i0 < count($news); $i0++) {
            if ($i0 < 6) {
                $ausgabe .= $this->color[2].$news[$i0]['type'].": ".$this->color[3].$news[$i0]['time']." ";
                $ausgabe .= $this->color[2].$news[$i0]['nick']." (".$news[$i0]['koords'].")";
                if ($news[$i0]['type'] != "R&uml;ckzug") {
                    $ausgabe .= $this->color[3]." Flotte: ".$this->color[2].$news[$i0]['fleet'];
                }
                $ausgabe .= "<br />";
                $all = $ausgabe;
            } else {
                $all .= $this->color[2].$news[$i0]['type'].": ".$this->color[3].$news[$i0]['time']." ";
                $all .= $this->color[2].$news[$i0]['nick']." (".$news[$i0]['koords'].")";
                if ($news[$i0]['type'] != "R&uml;ckzug") {
                    $all .= $this->color[3]." Flotte: ".$this->color[2].$news[$i0]['fleet'];
                }
                $all .= "<br />";
            }
        }
        if ($scan['old'] == true) { $scan['time'] = $this->scanOldColor($scan['time'], "irc"); }
        $scanEnd = $this->color[0]."Newsscan (".$this->color[1].$scan['time'].$this->color[0].") ";
        $scanEnd .= $scan['scanner']['nick']." (".$this->color[1].$scan['scanner']['koords'].$this->color[0].") - Herkunft: ".$scan['birth'];;
        $ausgabe = '<html><head><title>IRC-Newsscan</title></head><body><pre>'.$ausgabe.$scanEnd.'</pre></body></html>';
        $all = '<html><head><title>IRC-Newsscan</title></head><body><pre>'.$all.$scanEnd.'</pre></body></html>';

        return array('text' => $ausgabe, 'all' => $all);
    }

    private function konvertETA($eta)
    {
        global $tic;

        $tickFrequency = $tic->mod['Ticker']->getTickFrequency();
        if (preg_match('/Ticks/', $eta)) {
            $eta = preg_replace('/\D/', '', $eta);
        } elseif (preg_match('/Minuten/', $eta)) {
            $eta = ceil($eta / $tickFrequency);
        } else {
            $time = preg_split('/:/', $eta);
            $min = $time[0] * 60 + $time[1];
            $eta = ceil($min / $tickFrequency);
        }

        return $eta;
    }

    private function konvertTime($time)
    {
        if (is_array($time)) {
            $time['time'] = date("H:i:s d.m.Y", $time['time']);
            return $time;
        } else {
            return date("H:i:s d.m.Y", $time);
        }
    }

    private function konvertBirth($birth)
    {
        switch ($birth) {
        case -1:
            return "Nicht erfasst.";
        case SCAN_BIRTH_GNPASTE:
            return "Galaxy-Network Paste";
        case SCAN_BIRTH_FFEXT:
            return "FF Extension";
        case SCAN_BIRTH_IRCPASTE:
            return "IRC Paste";
        case SCAN_BIRTH_IRCBOT:
            return "IRC-Bot";
        case SCAN_BIRTH_MANUELL:
            return "manuelle Eingabe";
        }
    }

    private function konvertFlotte($flotte)
    {
        global $tic;

        if (!isset($flotte['status']) || !isset($flotte['ziel'])) {
            $tic->error($this, "Flottenstatus nicht korrekt!!!");
            return false;
        }
        if (preg_match('/orbit/i', $flotte['status'])) {
            $flotte['status'] = SCAN_FLOTTENSTATUS_ORBIT;
        } elseif (preg_match('/angriff/i', $flotte['status'])) {
            $flotte['status'] = SCAN_FLOTTENSTATUS_ATT;
        } elseif (preg_match('/verteidigung/i', $flotte['status'])) {
            $flotte['status'] = SCAN_FLOTTENSTATUS_DEFF;
        } elseif (preg_match('/r.*ckflug/iU', $flotte['status'])) {
            $flotte['status'] = SCAN_FLOTTENSTATUS_RUECK;
        }
        return $flotte;
    }

    private function scanTypToString($typ, $long = false)
    {
        switch ($typ) {
            case SCAN_TYP_SEK:
                if ($long === false) { return "sek"; } else { return "Sektorscan"; }
            case SCAN_TYP_UNIT:
                if ($long === false) { return "unit"; } else { return "Einheitenscan"; }
            case SCAN_TYP_MILI:
                if ($long === false) { return "mili"; } else { return "Milit&auml;rscan"; }
            case SCAN_TYP_GESCH:
                if ($long === false) { return "gesch"; } else { return "Gesch&uuml;tzsan"; }
            case SCAN_TYP_NEWS:
                if ($long === false) { return "news"; } else { return "Newsscan"; }
        }
    }
}

?>
