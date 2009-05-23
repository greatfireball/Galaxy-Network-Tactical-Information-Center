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

class Logging extends TICModule
{
    function Logging()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net")),
	"2",
	"Logging",
	"Logging System fuer Useraktionen",
	array(
            "Core" => "4",
            "Konst" => "1",
        ));
    
    }

    function createMenuEntries($menuroot)
    {
        $log = new MenuEntry("Logs", 9);
        $log->addChild(new MenuEntry("Incs safe/unsafe", 0, $this->getName(), "incs"));
        $log->addChild(new MenuEntry("Scans", 1, $this->getName(), "scans"));
        $log->addChild(new MenuEntry("Benutzerverwaltung", 2, $this->getName(), "userman"));
        $log->addChild(new MenuEntry("Error", 3, $this->getName(), "error"));
        $log->addChild(new MenuEntry("Sonstiges", 4, $this->getName(), "other"));
        $admin = $menuroot->getChildByName('Admin');
        $admin->addChild($log);
    }

    function onExecute($menuentry)
    {
        global $tic;
        switch ($menuentry) {
        case 'incs':
            $where = "WHERE action IN (%s, %s)";
            $arr = array(TAKTIK_SET_INC_SAFE, TAKTIK_SET_INC_UNSAFE);
            $type = 'safe/unsafe stellen von Incs';
            break;
        case 'scans':
            $where = "WHERE action >= %s AND action <= %s";
            $arr = array(SCAN_ADD_SEK, SCAN_ADD_NEWS);
            $type = 'Scans';
            break;
        case 'userman':
            $where = "WHERE action < 100";
            $arr = array();
            $type = 'Benutzerverwaltung';
            break;
        case 'error';
            $where = "WHERE action = 999";
            $arr = array();
            $type = 'Error';
            break;
        case 'other':
            $where = 'WHERE action > 399 AND action < 999';
            $arr = array();
            $type = 'Sonstige';
            break;
        default:
            assert(false);
            break;
        }
        $qry = "SELECT gala,planet, action, object, param, time FROM log $where ORDER BY time DESC LIMIT 50";
        $rs = $tic->db->Execute($this->getName(), $qry, $arr);
        $logs = array();
        for (; !$rs->EOF; $rs->MoveNext()) {
            $str = $this->_logToString($rs->fields[2], $rs->fields[3], $rs->fields[4]);
            array_push($logs, array($rs->fields[5], array($rs->fields[0],$rs->fields[1]), $str));
        }
        $this->setVar('logs', $logs); //FIXME da id nun ein array ist k�nnte es hier zu fehler kommen
        $this->setVar('type', $type);
        $this->setTemplate('logging.tpl');
    }

    function getInstallQueriesMySQL()
    {
    	global $tic;
    	
        return array_merge($tic->mod['UserMan']->getInstallQueriesMySQL(),
        array(
            'DROP TABLE IF EXISTS log',
            "CREATE  TABLE IF NOT EXISTS `log` (
			  `id` INT(11) NOT NULL AUTO_INCREMENT ,
			  `gala` INT(11) NOT NULL ,
			  `planet` INT(11) NOT NULL ,
			  `action` INT(11) NOT NULL ,
			  `object` VARCHAR(100) NOT NULL ,
			  `param` VARCHAR(200) NULL DEFAULT NULL ,
			  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
			  PRIMARY KEY (`id`) ,
			  INDEX `fk_log_TICUser` (`planet` ASC, `gala` ASC) ,
			  CONSTRAINT `fk_log_TICUser`
			    FOREIGN KEY (`planet` , `gala` )
			    REFERENCES `tic_user` (`planet` , `gala` )
			    ON DELETE NO ACTION
			    ON UPDATE NO ACTION)
			ENGINE = InnoDB
			AUTO_INCREMENT = 49
			DEFAULT CHARACTER SET = latin1
			COLLATE = latin1_german1_ci
			COMMENT = 'ticuser gegn planet und gala ausgetauscht';"
        ));
    }

    function getInstallQueriesPostgreSQL()
    {
        //FIXME CREATE INDEX action_index ON log (action) ???
        return array(
            'DROP TABLE log',
            'CREATE TABLE log (
                id serial NOT NULL PRIMARY KEY,
                ticuser varchar(100) NOT NULL,
                action int NOT NULL,
                object varchar(100) NOT NULL,
                param varchar(200),
                time timestamp(0) NOT NULL DEFAULT now()
            );'
        );
    }

    /* param sollte entweder null, false, ein string oder ein object mit der methode toString() sein
     */
    function log($action, $object, $param = null)
    {
        global $tic;
        if ($param === false) {
            $param = null;
        } else if (is_object($param)) {
            if (method_exists($param, 'toString')) {
                $param = $param->toString();
            } else {
                assert(false); //FIXME ?
                $param = null;
            }
        }
        if (is_object($object))
            $object = $object->toString();

        $user = $tic->mod['Auth']->getActiveUser();
        if (!$user)
            return;
        $userid=$user->getId();
        $qry = "INSERT INTO log (gala,planet, action, object, param) VALUES (%s, %s, %s, %s,%s)";
        $arr = array($userid[0],$userid[1], $action, $object, $param);
        $tic->db->Execute($this->getName(), $qry, $arr);
    }

    function _logToString($action, $object, $param)
    {
        // FIXME is nur ne vorlage
        switch ($action) {
            // ============= UserMan =============
            case USER_CREATE:
                return "Account $object erstellt";
            case USER_DELETE:
                return "Account $object gelöscht";
            case USER_CHANGE_KOORDS:
                return "Koords von $object geändert auf $param";
            case USER_CHANGE_NICK:
                return "Nick von $object geändert auf $param";
            case USER_CHANGE_GNRANG:
                return "GN-Rang von $object geändert auf $param";
            case USER_CHANGE_PASSWORD:
                return "Hat das Passwort von $object geändert";
            case USER_SET_RANDOM_PASSWORD:
                return "Zufälliges Passwort für $object vergeben";
            case USER_BAN:
                return "$object gesperrt";
            case USER_UNBAN:
                return "$object entsperrt";
            case USER_SET_ISBOT:
                return "Bot-Status von $object geändert";
            case USER_CHANGE_ROLE:
                return "Gruppe von $object geändert";
            case GALA_CHANGE_ALLI:
                if ($param === null)
                    return "$object aus ihrer Allianz herausgenommen";
                else
                    return "Allianz von $object geändert auf $param";
            case ALLI_CREATE:
                return "$object erstellt";
            case ALLI_DELETE:
                return "$object gelöscht";
            case ALLI_CHANGE_NAME:
                return "$object in $param umbenannt";
            case ALLI_CHANGE_TAG:
                return "Tag von $object in $param geändert";
            case ALLI_CHANGE_META:
                if ($param === null)
                    return "Allianz $object aus ihrer Meta herausgenommen";
                else
                    return "Allianz $object in die Meta $param aufgenommen";
            case META_CREATE:
                return "$object erstellt";
            case META_DELETE:
                return "$object gelöscht";
            case META_CHANGE_NAME:
                return "$object in $param umbenannt";
            case META_CHANGE_TAG:
                return "Tag von $object in $param geändert";
            // ============= Taktik ==============
            case TAKTIK_UPDATE_GALA:
                return "Taktik von $object aktualisiert";
            case TAKTIK_SET_INC_SAFE:
                return "Inc von $object auf SAFE gesetzt";
            case TAKTIK_SET_INC_UNSAFE:
                return "Inc von $object auf UNSAFE gesetzt";
            // ============== Scan ===============
            case SCAN_ADD_SEK:
                return "Sektorscan von $object eingetragen. $param";
            case SCAN_ADD_UNIT:
                return "Einheitenscan von $object eingetragen. $param";
            case SCAN_ADD_MILI:
                return "Militärscan von $object eingetragen. $param";
            case SCAN_ADD_GESCH:
                return "Geschützscan von $object eingetragen. $param";
            case SCAN_ADD_NEWS:
                return "Nachrichtenscan von $object eingetragen. $param";
            // ============== News ===============
            case NEWS_WRITE_GALA:
                return "$object an die Galaxie geschrieben";
            case NEWS_WRITE_ALLI:
                return "$object an die Allianz geschrieben";
            case NEWS_WRITE_META:
                return "$object an die Meta geschrieben";
            case NEWS_WRITE_ALLE:
                return "$object an alle geschrieben";
            case NEWS_DELETE_GALA:
                return "$object an die Galaxie gelöscht";
            case NEWS_DELETE_ALLI:
                return "$object an die Allianz gelöscht";
            case NEWS_DELETE_META:
                return "$object an die Meta gelöscht";
            case NEWS_DELETE_ALLE:
                return "$object an alle gelöscht";
            // ============== ERROR ===============
            case ERROR:
                return $param;

            default:
                return "FIXME: action:$action object:$object param:$param";
        }
    }
}

?>
