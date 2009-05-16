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
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net")),
	"3",
	"UserMan",
	"Benutzer und Diplomatie Verwaltung",
	array(
            "Core" => "4",
            "Design" => "2",
            "ADOdb" => "5"
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
            'DROP TABLE Meta CASCADE;',
            'CREATE TABLE Meta (
                meta serial PRIMARY KEY,
                name varchar(60) NOT NULL UNIQUE,
                tag varchar(20) NOT NULL UNIQUE
            )',
            'DROP TABLE Allianz CASCADE;',
            'CREATE TABLE Allianz (
                allianz serial PRIMARY KEY,
                name varchar(60) NOT NULL UNIQUE,
                tag varchar(20) NOT NULL UNIQUE,
                meta int REFERENCES Meta(meta)
            );',
            'DROP TABLE AllianzBnd CASCADE;',
            'CREATE TABLE AllianzBnd (
                a int NOT NULL REFERENCES Allianz(allianz),
                b int NOT NULL REFERENCES Allianz(allianz)
            );',
            'DROP TABLE Galaxie CASCADE;',
            'CREATE TABLE Galaxie (
                gala int NOT NULL UNIQUE,
                allianz int REFERENCES Allianz(allianz)
            );',
            'DROP TABLE GNPlayer CASCADE;',
            'CREATE TABLE GNPlayer (
                gnplayer serial PRIMARY KEY,
                nick varchar(50) NOT NULL,
                planet int NOT NULL,
                gala int NOT NULL REFERENCES galaxie(gala),
                UNIQUE(nick),
                UNIQUE(planet, gala)
            );',
            'DROP TABLE TICUser CASCADE;',
            'CREATE TABLE TICUser (
                ticuser serial PRIMARY KEY,
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
        return array(
            'DROP TABLE IF EXISTS TICUser CASCADE;',
            'DROP TABLE IF EXISTS GNPlayer CASCADE;',
            'DROP TABLE IF EXISTS Galaxie CASCADE;',
            'DROP TABLE IF EXISTS AllianzBnd CASCADE;',
            'DROP TABLE IF EXISTS Allianz CASCADE;',
            'DROP TABLE IF EXISTS Meta CASCADE;',
            'CREATE TABLE Meta (
                meta int AUTO_INCREMENT PRIMARY KEY,
                name varchar(60) NOT NULL UNIQUE,
                tag varchar(20) NOT NULL UNIQUE
            ) ENGINE = INNODB;',
            'CREATE TABLE Allianz (
                allianz int AUTO_INCREMENT PRIMARY KEY,
                name varchar(60) NOT NULL UNIQUE,
                tag varchar(20) NOT NULL UNIQUE,
                meta int REFERENCES Meta(meta)
            ) ENGINE = INNODB;',
            'CREATE TABLE AllianzBnd (
                a int NOT NULL REFERENCES Allianz(allianz),
                b int NOT NULL REFERENCES Allianz(allianz)
            ) ENGINE = INNODB;',
            'CREATE TABLE Galaxie (
                gala int NOT NULL UNIQUE,
                allianz int REFERENCES Allianz(allianz)
            ) ENGINE = INNODB;',
            'CREATE TABLE GNPlayer (
                gnplayer int AUTO_INCREMENT PRIMARY KEY,
                nick varchar(50) NOT NULL,
                planet int NOT NULL,
                gala int NOT NULL REFERENCES galaxie(gala),
                UNIQUE(nick),
                UNIQUE(planet, gala)
            ) ENGINE = INNODB;',
            'CREATE TABLE TICUser (
                ticuser int AUTO_INCREMENT PRIMARY KEY,
                planet int NOT NULL,
                gala int NOT NULL,
                pw_hash char(32) NOT NULL,
                salt varchar(24),
                pw_aendern tinyint(1) NOT NULL DEFAULT 1,
                is_bot tinyint(1) NOT NULL DEFAULT 0,
                gn_rang int NOT NULL DEFAULT 0,
                role int NOT NULL DEFAULT 5,
                last_active timestamp,
                failed_logins int NOT NULL DEFAULT 0,
                banned tinyint(1) NOT NULL DEFAULT 0,
                timeformat int NOT NULL DEFAULT 0,
                authnick varchar(15),
                highlight varchar(50),
                scantyp int,
                svs int,
                elokas int,
                telnr varchar(20),
                telnr_comment varchar(255),
                telnr_visibility int NOT NULL DEFAULT 0,      -- alli/meta/tic kann die nummer einsehen
                icq varchar(13),
                jabber varchar(200),
                UNIQUE(planet, gala),
                FOREIGN KEY (planet, gala) REFERENCES GNPlayer(planet, gala)
            ) ENGINE = INNODB;'
        );
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
        $qry = "SELECT gala, planet, nick, gnplayer FROM GNPlayer WHERE lower(nick) = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array(strtolower($nick)));
        if ($rs->EOF)
            return false;
        else {
            $player = new GNPlayer($rs->fields[0], $rs->fields[1], $rs->fields[2]);
            $player->_id = $rs->fields[3];
            return $player;
        }
    }

    public function getPlayerBySubString($string)
    {
        global $tic;

        if (trim($string) == "") { return false; }
        $qry = "SELECT gala, planet, nick, gnplayer FROM GNPlayer WHERE lower(nick) LIKE %s ORDER BY gala, planet ASC";
        $rs = $tic->db->Execute($this->getName(), $qry, array('%'.strtolower($string).'%'));
        if (!$rs) { return false; }
        $erg = array();
        while (!$rs->EOF) {
            $player = new GNPlayer($rs->fields[0], $rs->fields[1], $rs->fields[2]);
            $player->_id = $rs->fields[3];
            array_push($erg, $player);
            $rs->movenext();
        }
        if (count($erg) == 0) { return false; }
        return $erg;
    }

    public function getPlayerByKoords($gala, $planet)
    {
        global $tic;
        $qry = "SELECT gala, planet, nick, gnplayer FROM GNPlayer WHERE gala = %s AND planet = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($gala, $planet));
        if ($rs->EOF)
            return false;
        else {
            $player = new GNPlayer($rs->fields[0], $rs->fields[1], $rs->fields[2]);
            $player->_id = $rs->fields[3];
            return $player;
        }
    }


    // ================== User Functions =============================

    public function getUserByNick($nick)
    {
        $where = ' WHERE lower(nick) = %s ';
        $arr = array(strtolower($nick));
        return $this->getUserByX($where, $arr);
    }

    public function getUserByKoords($gala, $planet)
    {
        $where = ' WHERE TICUser.gala = %s AND TICUser.planet = %s ';
        $arr = array($gala, $planet);
        return $this->getUserByX($where, $arr);
    }

    public function getUserById($id)
    {
        $where = ' WHERE TICUser.ticuser = %s';
        return $this->getUserByX($where, array($id));
    }

    private function getUserByX($where, $arr)
    {
        global $tic;
        $qry = "SELECT ticuser FROM TICUser JOIN GNPlayer USING(gala, planet) ".$where;
        $rs = $tic->db->Execute($this->getName(), $qry, $arr);
        if ($rs->EOF)
            return false;

        $user = new TICUser();
        $user->load($rs->fields[0]);
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

        $qry = "SELECT gala FROM Galaxie WHERE allianz IS NULL ORDER BY gala";
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
        $qry = "SELECT allianz FROM Allianz WHERE name = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($name));
        if ($rs->EOF)
            return false;

        return $this->getAllianzById($rs->fields[0]);
    }

    public function getAllianzByTag($tag)
    {
        global $tic;
        $qry = "SELECT allianz FROM Allianz WHERE tag = %s";
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

        $qry = "SELECT allianz FROM Allianz ORDER BY allianz";
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

        $qry = "SELECT allianz FROM Allianz WHERE meta IS NULL ORDER BY allianz";
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
        $qry = "SELECT meta FROM Meta WHERE tag = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($tag));
        if ($rs->EOF)
            return false;

        return $this->getMetaById($rs->fields[0]);
    }

    public function getMetaByName($name)
    {
        global $tic;
        $qry = "SELECT meta FROM Meta WHERE name = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($name));
        if ($rs->EOF)
            return false;

        return $this->getMetaById($rs->fields[0]);
    }

    public function getAllMeten()
    {
        global $tic;
        $qry = "SELECT meta FROM Meta ORDER BY meta";
        $rs = $tic->db->Execute($this->getName(), $qry);
        $meten = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            array_push($meten, $this->getMetaById($rs->fields[0]));
        }
        return $meten;
    }
}

?>
