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

// ================================= UserMan ==================================

require_once('GNPlayer.class.php');
require_once('TICUser.class.php');
require_once('Galaxie.class.php');
require_once('Allianz.class.php');
require_once('Meta.class.php');
require_once('frontend.php');

class UserMan extends TICModule
{
    function UserMan()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"UserMan",
	"Benutzer und Diplomatie Verwaltung",
	array(
            "Core" => "10",
            "Design" => "10",
            "ADOdb" => "10"
        ));
    
    }

    public function createMenuEntries($menuroot)
    {
        $um = new MenuEntry("Benutzerverwaltung", 0);
        $um->addChild(new MenuEntry("Meten", 0, $this->getName(), "Meten"));
        $um->addChild(new MenuEntry("Allianzen", 1, $this->getName(), "Allianzen"));
        $um->addChild(new MenuEntry("Galaxien", 2, $this->getName(), "Galaxien"));
        $um->addChild(new MenuEntry("Benutzer anlegen", 3, $this->getName(), "create"));
        $admin = $menuroot->getChildByName('Admin');
        $admin->addChild($um);
        $main = $menuroot->getChildByName('Main');
        $main->addChild(new MenuEntry("Profil", 10, $this->getName(), "Profil"));
    }

    public function onExecute($menuentry)
    {
        if (isset($_GET['gala'])) {
            showGalaxie_($_GET['gala']);
        } else if (isset($_GET['pw'])) {
            setRandomPassword($_GET['pw']);
        } else {
            switch($menuentry) {
            case 'Profil':
                showProfile();
                break;
            case 'Meten':
                showMeten();
                break;
            case 'Allianzen':
                showAllianzen();
                break;
            case 'Galaxien':
                showOverview();
                break;
            case 'create':
                showCreateUser();
                break;
            default:
                assert(false);
                break;
            }
        }
    }

    public function getInstallQueriesPostgreSQL()
    {
        return array(
            'DROP TABLE meta CASCADE;',
            'CREATE TABLE meta (
                meta serial PRIMARY KEY,
                name varchar(60) NOT NULL UNIQUE,
                tag varchar(20) NOT NULL UNIQUE
            )',
            'DROP TABLE allianz CASCADE;',
            'CREATE TABLE allianz (
                allianz serial PRIMARY KEY,
                name varchar(60) NOT NULL UNIQUE,
                tag varchar(20) NOT NULL UNIQUE,
                meta int REFERENCES Meta(meta)
            );',
            'DROP TABLE allianz_bnd CASCADE;',
            'CREATE TABLE allianz_bnd (
                a int NOT NULL REFERENCES Allianz(allianz),
                b int NOT NULL REFERENCES Allianz(allianz)
            );',
            'DROP TABLE galaxie CASCADE;',
            'CREATE TABLE galaxie (
                gala int NOT NULL UNIQUE,
                allianz int REFERENCES Allianz(allianz)
            );',
            'DROP TABLE gnplayer CASCADE;',
            'CREATE TABLE gnplayer (
                gnplayer serial PRIMARY KEY,
                nick varchar(50) NOT NULL,
                planet int NOT NULL,
                gala int NOT NULL REFERENCES galaxie(gala),
                UNIQUE(nick),
                UNIQUE(planet, gala)
            );',
            'DROP TABLE tic_user CASCADE;',
            'CREATE TABLE tic_user (
                planet int NOT NULL,
                gala int NOT NULL,
                pw_hash char(32) NOT NULL,
                salt varchar(24),
                pw_aendern smallint NOT NULL DEFAULT 1,
                is_bot smallint NOT NULL DEFAULT 0,
                gn_rang int NOT NULL DEFAULT 0,
                role int NOT NULL DEFAULT 5,
                last_active timestamp(0),
                failed_logins int NOT NULL DEFAULT 0,
                banned smallint NOT NULL DEFAULT 0,
                timeformat int NOT NULL DEFAULT 0,
                authnick varchar(15),
                highlight varchar(50),
                scantyp int,
                svs int,
                elokas int,
                telnr varchar(20),
                telnr_comment varchar(255),
                telnr_visibility int NOT NULL DEFAULT 0,      -- alli/meta/tic kann die nummer einsehen
                icq varchar(12),
                jabber varchar(200),
                UNIQUE(planet, gala),
                FOREIGN KEY (planet, gala) REFERENCES GNPlayer(planet, gala)
            );'
        );
    }

    public function getInstallQueriesMySQL()
    {
    	global $tic;
        return array_merge($tic->mod['Right']->getInstallQueriesMySQL(),array(
            'DROP TABLE IF EXISTS tic_user CASCADE;',
            'DROP TABLE IF EXISTS gnplayer CASCADE;',
            'DROP TABLE IF EXISTS galaxie CASCADE;',
            'DROP TABLE IF EXISTS allianz_bnd CASCADE;',
            'DROP TABLE IF EXISTS allianz CASCADE;',
            'DROP TABLE IF EXISTS meta CASCADE;',
           "CREATE  TABLE IF NOT EXISTS `meta` (
			  `meta` INT(11) NOT NULL AUTO_INCREMENT ,
			  `name` VARCHAR(60) NOT NULL ,
			  `tag` VARCHAR(20) NOT NULL ,
			  PRIMARY KEY (`meta`) ,
			  UNIQUE INDEX `name` (`name` ASC) ,
			  UNIQUE INDEX `tag` (`tag` ASC) )
			ENGINE = InnoDB
			AUTO_INCREMENT = 3
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "CREATE  TABLE IF NOT EXISTS `allianz` (
			  `allianz` INT(11) NOT NULL AUTO_INCREMENT ,
			  `name` VARCHAR(60) NOT NULL ,
			  `tag` VARCHAR(20) NOT NULL ,
			  `meta` INT(11) NULL DEFAULT NULL ,
			  PRIMARY KEY (`allianz`) ,
			  UNIQUE INDEX `name` (`name` ASC) ,
			  UNIQUE INDEX `tag` (`tag` ASC) ,
			  INDEX `fk_Allianz_Meta` (`meta` ASC) ,
			  CONSTRAINT `fk_Allianz_Meta`
			    FOREIGN KEY (`meta` )
			    REFERENCES `meta` (`meta` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 6
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "CREATE  TABLE IF NOT EXISTS `allianz_bnd` (
			  `a` INT(11) NOT NULL ,
			  `b` INT(11) NOT NULL ,
			  INDEX `fk_AllianzBnd_Allianz` (`a` ASC) ,
			  INDEX `fk_AllianzBnd_Allianz1` (`b` ASC) ,
			  CONSTRAINT `fk_AllianzBnd_Allianz`
			    FOREIGN KEY (`a` )
			    REFERENCES `allianz` (`allianz` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION,
			  CONSTRAINT `fk_AllianzBnd_Allianz1`
			    FOREIGN KEY (`b` )
			    REFERENCES `allianz` (`allianz` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
            "CREATE  TABLE IF NOT EXISTS `galaxie` (
			  `gala` INT(11) NOT NULL ,
			  `allianz` INT(11) NULL DEFAULT NULL ,
			  UNIQUE INDEX `gala` (`gala` ASC, `allianz` ASC) ,
			  PRIMARY KEY (`gala`) ,
			  INDEX `fk_Galaxie_Allianz` (`allianz` ASC) ,
			  CONSTRAINT `fk_Galaxie_Allianz`
			    FOREIGN KEY (`allianz` )
			    REFERENCES `allianz` (`allianz` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",
           "CREATE  TABLE IF NOT EXISTS `gnplayer` (
			  `gala` INT(11) NOT NULL ,
			  `planet` INT(11) NOT NULL ,
			  `nick` VARCHAR(50) NULL ,
			  UNIQUE INDEX `nick` (`nick` ASC) ,
			  PRIMARY KEY (`planet`, `gala`) ,
			  INDEX `fk_GNPlayer_Galaxie` (`gala` ASC) ,
			  CONSTRAINT `fk_GNPlayer_Galaxie`
			    FOREIGN KEY (`gala` )
			    REFERENCES `galaxie` (`gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 5
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;",//Hier konflikt da Role zuvor angelegt werden muss
            "CREATE  TABLE IF NOT EXISTS `tic_user` (
 			 `gala` INT(11) NOT NULL ,
 			 `planet` INT(11) NOT NULL ,
  			`role` INT(11) NOT NULL ,
  			`pw_hash` CHAR(32) NOT NULL ,
  			`pw_aendern` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'geändernt von tinyint auf boolean' ,
  			`is_bot` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'geändernt von tinyint auf boolean' ,
 			 `gn_rang` INT(11) NOT NULL DEFAULT '0' ,
  			`last_active` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
 			 `failed_logins` INT(4) NOT NULL DEFAULT '0' ,
 			 `banned` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'geändernt von tinyint auf boolean' ,
 			 `timeformat` INT(11) NOT NULL DEFAULT '0' ,
 			 `telnr_visibility` TINYINT(1) NOT NULL DEFAULT '0' ,
 			 `authnick` VARCHAR(15) NULL DEFAULT NULL ,
  			`salt` VARCHAR(24) NULL DEFAULT NULL ,
 			 `highlight` VARCHAR(50) NULL DEFAULT NULL ,
			  `scantyp` INT(11) NULL DEFAULT NULL ,
 			 `svs` INT(11) NULL DEFAULT NULL ,
 			 `elokas` INT(11) NULL DEFAULT NULL ,
 			 `telnr` VARCHAR(20) NULL DEFAULT NULL ,
 			 `telnr_comment` VARCHAR(255) NULL DEFAULT NULL ,
  			`icq` VARCHAR(13) NULL DEFAULT NULL ,
  			`jabber` VARCHAR(200) NULL DEFAULT NULL ,
  			PRIMARY KEY (`planet`, `gala`) ,
  			INDEX `fk_TICUser_GNPlayer` (`planet` ASC, `gala` ASC) ,
  			INDEX `fk_TICUser_role` (`role` ASC) ,
  			CONSTRAINT `fk_TICUser_GNPlayer`
    			FOREIGN KEY (`planet` , `gala` )
    			REFERENCES `gnplayer` (`planet` , `gala` )
    			ON DELETE NO ACTION
    			ON UPDATE NO ACTION,
  			CONSTRAINT `fk_TICUser_role`
    			FOREIGN KEY (`role` )
    			REFERENCES `role` (`role` )
    			ON DELETE NO ACTION
    			ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 5
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci;"
        ));
    }

    public function execInstall($installdata)
    {
        if (!(isset($installdata['admin_username']) &&
              isset($installdata['admin_pw']) &&
              isset($installdata['admin_gala']) &&
              isset($installdata['admin_planet'])))
            die("keine informationen zum erstellen des admin accounts vorhanden");

        $admin = new TICUser($installdata['admin_gala'],
                             $installdata['admin_planet'],
                             $installdata['admin_username']);
        if (!$admin->create('', null))
            die("erstellen des admin accounts fehlgeschlagen!");
        $admin->setPassword($installdata['admin_pw']);
        $admin->setRole(8);
    }

    public function onPostLoad()
    {
        return;
    }

    // ================== Player Functions =============================

    public function getPlayerByNick($nick)
    {
        global $tic;
        $qry = "SELECT gala, planet, nick FROM gnplayer WHERE lower(nick) = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array(strtolower($nick)));
        if ($rs->EOF)
            return false;
        else {
            $player = new GNPlayer($rs->fields[0], $rs->fields[1], $rs->fields[2]);
            return $player;
        }
    }

    public function getPlayerBySubString($string)
    {
        global $tic;

        if (trim($string) == "") { return false; }
        $qry = "SELECT gala, planet, nick FROM gnplayer WHERE lower(nick) LIKE %s ORDER BY gala, planet ASC";
        $rs = $tic->db->Execute($this->getName(), $qry, array('%'.strtolower($string).'%'));
        if (!$rs) { return false; }
        $erg = array();
        while (!$rs->EOF) {
            $player = new GNPlayer($rs->fields[0], $rs->fields[1], $rs->fields[2]);
            array_push($erg, $player);
            $rs->movenext();
        }
        if (count($erg) == 0) { return false; }
        return $erg;
    }

    public function getPlayerByKoords($gala, $planet)
    {
        global $tic;
        $qry = "SELECT gala, planet, nick FROM gnplayer WHERE gala = %s AND planet = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($gala, $planet));
        if ($rs->EOF)
            return false;
        else {
            $player = new GNPlayer($rs->fields[0], $rs->fields[1], $rs->fields[2]);
            return $player;
        }
    }


    // ================== User Functions =============================

    public function getUserByNick($nick)
    {
        $where = ' WHERE lower(gnplayer.nick) = %s ';
        $arr = array(strtolower($nick));
        return $this->getUserByX($where, $arr);
    }

    public function getUserByKoords($gala, $planet)
    {
    	$ticObj=new TICUser();
    	$ticObj->load(array($gala,$planet));
        return $ticObj;
    }

    public function getUserById($id) //Neue DB $id ist ein array (gala,planet)
    {
        $where = ' WHERE tic_user.gala = %s and tic_user.planet=%s';
        return $this->getUserByX($where,$id);
    }

    private function getUserByX($where, $arr)
    {
        global $tic;
        $qry = "SELECT tic_user.gala as gala,tic_user.planet as planet FROM tic_user NATURAL JOIN gnplayer ".$where;
        $rs = $tic->db->Execute($this->getName(), $qry, $arr);
        if ($rs->EOF)
            return false;

        $user = new TICUser();
        $user->load(array($rs->fields['gala'],$rs->fields['planet']));
        return $user;
    }

    // ================== Galaxie Functions =============================

    public function getGalaxie($nr)
    {
        $gala = new Galaxie($nr);
        if (!$gala->load())
            return false;
        return $gala;
    }

    public function getFreieGalaxien()
    {
        global $tic;

        $qry = "SELECT gala FROM galaxie WHERE allianz IS NULL ORDER BY gala";
        $rs = $tic->db->Execute($this->getName(), $qry);
        $galen = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            $galen[] = new Galaxie($rs->fields['gala']);
        }
        return $galen;
    }

    // ================== Allianz Functions =============================

    public function getAllianzByName($name)
    {
        global $tic;
        $qry = "SELECT allianz FROM allianz WHERE name = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($name));
        if ($rs->EOF)
            return false;

        return $this->getAllianzById($rs->fields[0]);
    }

    public function getAllianzByTag($tag)
    {
        global $tic;
        $qry = "SELECT allianz FROM allianz WHERE tag = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($tag));
        if ($rs->EOF)
            return false;

        return $this->getAllianzById($rs->fields[0]);
    }

    public function getAllianzById($alli_id)
    {
        assert($alli_id !== null);
        $alli = new Allianz();
        if ($alli->load($alli_id))
            return $alli;
        else
            return false;
    }

    public function getAllAllianzen()
    {
        global $tic;

        $qry = "SELECT allianz FROM allianz ORDER BY allianz";
        $rs = $tic->db->Execute($this->getName(), $qry);
        $allis = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            array_push($allis, $this->getAllianzById($rs->fields[0]));
        }
        return $allis;
    }

    public function getFreieAllianzen()
    {
        global $tic;

        $qry = "SELECT allianz FROM allianz WHERE meta IS NULL ORDER BY allianz";
        $rs = $tic->db->Execute($this->getName(), $qry);
        $allis = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            array_push($allis, $this->getAllianzById($rs->fields[0]));
        }
        return $allis;
    }

    // ================== Meta Functions =============================

    public function getMetaById($meta_id)
    {
        assert($meta_id !== null);
        $meta = new Meta();
        if ($meta->load($meta_id))
            return $meta;
        else
            return false;
    }

    public function getMetaByTag($tag)
    {
        global $tic;
        $qry = "SELECT meta FROM meta WHERE tag = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($tag));
        if ($rs->EOF)
            return false;

        return $this->getMetaById($rs->fields[0]);
    }

    public function getMetaByName($name)
    {
        global $tic;
        $qry = "SELECT meta FROM meta WHERE name = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($name));
        if ($rs->EOF)
            return false;

        return $this->getMetaById($rs->fields[0]);
    }

    public function getAllMeten()
    {
        global $tic;
        $qry = "SELECT meta FROM meta ORDER BY meta";
        $rs = $tic->db->Execute($this->getName(), $qry);
        $meten = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            array_push($meten, $this->getMetaById($rs->fields[0]));
        }
        return $meten;
    }
}

?>
