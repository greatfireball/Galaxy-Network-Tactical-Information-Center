<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006,2007  Andreas Hemel  <dai.shan@gmx.net>                       *
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
/* TODO : IFace für Classen schreiben die Right benutzrn */
require_once('capabilities.php');
require_once('Right.iface.php');

class Right extends TICModule
{
    function Right()
    {
	parent::__construct(
	array(
            new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net"),
            new Author("Pascal Gollor", "Hugch", "pascal@gollor.org")
        ),
	"3",
	"Right",
	"Rechteverwaltung",
	array(
            "Core" => "4",
            "Auth" => "1",
            "Konst" => "1"
        ));
    
    }
    
    public function createMenuEntries($menuroot)
    {
        $admin = $menuroot->getChildByName('Admin');
        $admin->addChild(new MenuEntry("Rechte", 1, $this->getName(), 'right'));
    }

    public function onExecute($menuentry)
    {
        if (isset($_GET['role']))
            $this->showEditRole($_GET['role']);
        else
            $this->showOverview();
    }

    private function showEditRole($role)
    {
        global $tic;

        $this->processEditRolePost();

        $qry = "SELECT name, israng FROM role WHERE role = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($role));
        if ($rs->EOF) {
            $tic->error(get_class($this), 'Rang/Gruppe existiert nicht');
            return;
        }
        $name = $rs->fields[0];
        $israng = $rs->fields[1];

        $qry = "SELECT capability FROM role_capability WHERE role = %s AND allowed = '1'";
        $rs = $tic->db->Execute(get_class($this), $qry, array($role));
        $capabilities = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            $capabilities[$rs->fields[0]] = 1;
        }
        $this->setVar('caps', $capabilities);
        $this->setVar('name', $name);
        $this->setVar('id', $role);
        $this->setVar('israng', $israng);
        $this->setTemplate('right_role.tpl');
    }

    private function processEditRolePost()
    {
        global $tic;

        if (!isset($_POST['role_edit_post']) || !isset($_POST['role_id']))
            return;

        $role = $_POST['role_id'];

        $qry = "SELECT israng FROM role WHERE role = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($role));
        if ($rs->EOF)
            return;

        if ($rs->fields['israng']) {
            $ok = $this->isAllowed(RIGHT_EDIT_RANG, null);
        } else {
            $ok = $this->isAllowed(RIGHT_EDIT_ROLE, null);
        }
        if (!$ok)
            return;

        $qry = "DELETE FROM role_capability WHERE role = %s";
        $tic->db->Execute(get_class($this), $qry, array($role));

        $qry = "INSERT INTO role_capability (role, capability, allowed) VALUES (%s, %s, '1')";
        foreach ($_POST as $key => $value) {
            if (preg_match('/right_([0-9]+)/', $key, $matches)) {
                $cap = $matches[1];
                $tic->db->Execute(get_class($this), $qry, array($role, $cap));
            }
        }
    }

    private function showOverview()
    {
        global $tic;

        $qry = "SELECT role, name, israng FROM role ORDER BY role";
        $rs = $tic->db->Execute(get_class($this), $qry);
        $role = array();
        $rang = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            if ($rs->fields[2])
                $rang[] = array('id' => $rs->fields[0], 'name' => $rs->fields[1]);
            else
                $role[] = array('id' => $rs->fields[0], 'name' => $rs->fields[1]);
        }
        $this->setVar('rang', $rang);
        $this->setVar('role', $role);
        $this->setTemplate('right_overview.tpl');
    }

    public function getInstallQueriesMySQL()
    {
        $sql = array(
                'DROP TABLE IF EXISTS role',
                'DROP TABLE IF EXISTS role_capability',
                'CREATE TABLE role (
                    role int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    name varchar(20) NOT NULL UNIQUE,
                    israng tinyint(1) NOT NULL DEFAULT 0
                ) TYPE = INNODB',
                'CREATE TABLE role_capability (
                    role int NOT NULL REFERENCES role(role),
                    capability int NOT NULL,
                    allowed tinyint(1) NOT NULL DEFAULT 0,
                    UNIQUE(role, capability)
                ) TYPE = INNODB'
            );
        $sql2 = $this->createDefaultSQL();
        return array_merge($sql, $sql2);
    }

    function getInstallQueriesPostgreSQL()
    {
        $sql = array(
                'DROP TABLE role CASCADE',
                'DROP TABLE role_capability CASCADE',
                'CREATE TABLE role (
                    role serial NOT NULL PRIMARY KEY,
                    name varchar(20) NOT NULL UNIQUE,
                    israng smallint NOT NULL DEFAULT 0
                )',
                'CREATE TABLE role_capability (
                    role int NOT NULL REFERENCES role(role),
                    capability int NOT NULL,
                    allowed smallint NOT NULL DEFAULT 0,
                    UNIQUE(role, capability)
                )'
            );
        $sql2 = $this->createDefaultSQL();
        return array_merge($sql, $sql2);
    }

    private function createDefaultSQL()
    {
        $member = array();
        $member[] = CAP_INCSAFETY_GALA;
        $member[] = CAP_INCSAFETY_ALLI;
        $member[] = CAP_INCSAFETY_META;
        $member[] = CAP_LOG_SCANS;
        $member[] = CAP_LOG_INCSAFE;
        $member[] = CAP_LOG_GALAUPDATE;

        $vgc = $member;
        $vgc[] = CAP_USERMAN_GALA;
        $vgc[] = CAP_DIPLO_GALA;
        $vgc[] = CAP_NEWS_GALA;

        $gc = $vgc;

        $vhc = $gc;
        $vhc[] = CAP_USERMAN_ALLI;
        $vhc[] = CAP_MOVE_USER_ALLI;
        $vhc[] = CAP_DIPLO_ALLI;
        $vhc[] = CAP_NEWS_ALLI;
        $vhc[] = CAP_LOG_USERMAN;

        $hc = $vhc;

        $metahc = $hc;
        $metahc[] = CAP_USERMAN_META;
        $metahc[] = CAP_MOVE_USER_META;
        $metahc[] = CAP_DIPLO_META;
        $metahc[] = CAP_NEWS_META;

        $techniker = $metahc;
        $techniker[] = CAP_USERMAN_ALL;
        $techniker[] = CAP_MOVE_USER_ALL;
        $techniker[] = CAP_USER_ISBOT;
        $techniker[] = CAP_DIPLO_ALL;
        $techniker[] = CAP_NEWS_ALL;
        $techniker[] = CAP_NEWS_DELETE_OTHER;
        $techniker[] = CAP_INCSAFETY_ALL;
        $techniker[] = CAP_LOG_OTHER;
        $techniker[] = CAP_DEBUG;

        $admin = $techniker;
        $admin[] = CAP_EDIT_RANG;
        $admin[] = CAP_EDIT_ROLE;

        $sql = array();
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('0', 'Member', '1')";
        $sql[] = "UPDATE role SET role = 0 WHERE name = 'Member'"; // fuck mysql
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('1', 'VGC', '1')";
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('2', 'GC', '1')";
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('3', 'VHC', '1')";
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('4', 'HC', '1')";
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('5', 'default', '0')";
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('6', 'Meta HC', '0')";
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('7', 'Techniker', '0')";
        $sql[] = "INSERT INTO role (role, name, israng) VALUES ('8', 'Admin', '0')";

        foreach ($member as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('0', '$capability', '1')";
        foreach ($vgc as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('1', '$capability', '1')";
        foreach ($gc as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('2', '$capability', '1')";
        foreach ($vhc as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('3', '$capability', '1')";
        foreach ($hc as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('4', '$capability', '1')";
        foreach ($metahc as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('6', '$capability', '1')";
        foreach ($techniker as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('7', '$capability', '1')";
        foreach ($admin as $capability)
            $sql[] = "INSERT INTO role_capability (role, capability, allowed) VALUES ('8', '$capability', '1')";

        return $sql;
    }

    private function testCapability($cap)
    {
        global $tic;
        $user = $tic->mod['Auth']->getActiveUser();
        $rang = $user->getGnRangId();
        $role = $user->getRole();
        $qry = "SELECT allowed, israng FROM role_capability NATURAL JOIN role WHERE capability = %s AND (role = %s OR role = %s)";
        $rs = $tic->db->Execute(get_class($this), $qry, array($cap, $rang, $role));

        $rangVal = null;
        $roleVal = null;
        for (; !$rs->EOF; $rs->MoveNext()) {
            if ($rs->fields[1] == 0)
                $roleVal = $rs->fields[0];
            else
                $rangVal = $rs->fields[0];
        }
        return ($roleVal == 1) || ($roleVal === null && $rangVal == 1);
    }

    public function isAllowed($action, $object, $param = null, $test = false)
    {
        global $tic;
        $r = $this->isAllowedHelper($action, $object, $param);
        if (!$r && !$test)
            $tic->error(get_class($this), 'keine Berechtigung diese Funktion auszufÃ¼hren (action: '.$action.')');
        return $r;
    }

    private function isAllowedHelper($action, $object, $param = null)
    {
        global $tic;

        if ($tic->disableSecurity)
            return true;

        $user = $tic->mod['Auth']->getActiveUser();
        switch ($action) {
            case PLAYER_CHANGE_NICK:
            case PLAYER_CHANGE_KOORDS:
                if ($object->getGala() == $user->getGala() && $param->getGala() == $user->getGala()) {
                    return $this->testCapability(CAP_MOVE_USER_GALA);
                } elseif ($object->getAllianz() && $user->getAllianz() && $param->getAllainz() &&
                  $object->getAllianz() == $user->getAllianz() &&
                  $param->getAllainz() == $user->getAllianz()) {
                    return $this->testCapability(CAP_MOVE_USER_ALLI);
                } elseif ($object->getMeta() && $user->getMeta() && $param->getMeta() &&
                  $object->getMeta() == $user->getMeta() &&
                  $param->getMeta() == $user->getMeta()) {
                    return $this->testCapability(CAP_MOVE_USER_META);
                } else {
                    return $this->testCapability(CAP_MOVE_USER_ALL);
                }

            case USER_CREATE:
                return true;
            case USER_DELETE:
            case USER_CHANGE_KOORDS:
            case USER_CHANGE_NICK:
            case USER_CHANGE_PASSWORD:
            case USER_CHANGE_PROFILE:
            case USER_SET_RANDOM_PASSWORD:
            case USER_BAN:
            case USER_UNBAN:
            case USER_CHANGE_GNRANG:
                return true;

            case USER_SET_ISBOT:
                return $this->testCapability(CAP_USER_ISBOT);

            case USER_CHANGE_ROLE:
                return true;

            case GALA_CHANGE_ALLI:
                return $this->testCapability(CAP_DIPLO_GALA);

            case ALLI_CREATE:
            case ALLI_DELETE:
            case ALLI_CHANGE_NAME:
            case ALLI_CHANGE_TAG:
            case ALLI_CHANGE_META:
                $alli = $user->getAllianz();
                if ($alli && $object->getId() == $alli->getId())
                    return $this->testCapability(CAP_DIPLO_ALLI);
                else
                    return $this->testCapability(CAP_DIPLO_ALL);

            case META_CREATE:
            case META_DELETE:
                return $this->testCapability(CAP_DIPLO_ALL);

            case META_CHANGE_NAME:
            case META_CHANGE_TAG:
                $meta = $user->getMeta();
                if ($meta && $object->getId() == $meta->getId())
                    return $this->testCapability(CAP_DIPLO_META);
                else
                    return $this->testCapability(CAP_DIPLO_ALL);

            case TAKTIK_UPDATE_GALA:
                return true;

            case TAKTIK_SET_INC_SAFE:
            case TAKTIK_SET_INC_UNSAFE:
                if ($user->getGalaxie() == $object->getNr())
                    return $this->testCapability(CAP_INCSAFETY_GALA);
                else if ($user->getAllianz()->getId() == $object->getAllianz()->getId())
                    return $this->testCapability(CAP_INCSAFETY_ALLI);
                else if (false /* FIXME META */)
                    return $this->testCapability(CAP_INCSAFETY_META);
                else
                    return $this->testCapability(CAP_INCSAFETY_ALL);

            // wird warscheinlich nicht benoetigt
            /*case SCAN_ADD_SEK:
            case SCAN_ADD_UNIT:
            case SCAN_ADD_MILI:
            case SCAN_ADD_GESCH:
            case SCAN_ADD_NEWS:
                return true;*/

            case NEWS_WRITE_GALA:
                return $this->testCapability(CAP_NEWS_GALA);

            case NEWS_WRITE_ALLI:
                return $this->testCapability(CAP_NEWS_ALLI);

            case NEWS_WRITE_META:
                return $this->testCapability(CAP_NEWS_META);

            case NEWS_WRITE_ALLE:
                return $this->testCapability(CAP_NEWS_ALL);

            case NEWS_DELETE_GALA:
                return $this->testCapability(CAP_NEWS_GALA) || $this->testCapability(CAP_NEWS_DELETE_OTHER);

            case NEWS_DELETE_ALLI:
                return $this->testCapability(CAP_NEWS_ALLI) || $this->testCapability(CAP_NEWS_DELETE_OTHER);

            case NEWS_DELETE_META:
                return $this->testCapability(CAP_NEWS_META) || $this->testCapability(CAP_NEWS_DELETE_OTHER);

            case NEWS_DELETE_ALLE:
                return $this->testCapability(CAP_NEWS_ALL) || $this->testCapability(CAP_NEWS_DELETE_OTHER);

            case ADODB_ACCESS_FAILED_SQL:
                return $this->testCapability(CAP_DEBUG);

            case RIGHT_EDIT_RANG:
                return $this->testCapability(CAP_EDIT_RANG);
            case RIGHT_EDIT_ROLE:
                return $this->testCapability(CAP_EDIT_ROLE);

            default:
                assert(false);
        }
    }

    public function getAllRoles() {
        global $tic;

        $qry = "SELECT role, name FROM role WHERE israng = '0'";
        $rs = $tic->db->Execute(get_class($this), $qry);
        $roles = array();
        for (; !$rs->EOF; $rs->moveNext()) {
            $roles[] = array('name' => $rs->fields['name'], 'id' => $rs->fields['role']);
        }
        return $roles;
    }
}

?>
