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

// ================================== TICUser =================================
require_once('./Right/Right.iface.php'); //Rightinface laden 

class TICUser extends GNPlayer implements Right_IFace {
    // public Konstanten
    public static $timeformatTranslation = array('Ticks' => 0, 'Minuten' => 1, 'Stunden' => 2);
    public static $visibilityTranslation = array('Alle' => 0, 'Meta' => 1, 'Allianz' => 2);

    private $uid = null;
    private $pwAendern = null;
    private $isBot = null;
    private $gnRang = null;
    private $role = null;
    private $lastActive = null;
    private $failedLogins = null;
    private $banned = null;
    private $timeformat = null;
    private $authnick = null;
    private $highlight = null;
    private $scantyp = null;
    private $svs = null;
    private $elokas = null;
    private $telnr = null;
    private $telnrComment = null;
    private $telnrVisibility = null;
    private $icq = null;
    private $jabber = null;

    public function __construct($gala = null, $planet = null, $nick = null)
    {
        parent::__construct($gala, $planet, $nick);
    }

    public function __toString()
    {
        return $this->uid;
    }
    
    function getGala()
    {
    	return $this->getGalaxie();
    }
    //FIXME getAllianz und meta implementieren
    function getMeta()
    {
    	global $tic;
    	return;
    }
    
    function getAllianz()
    {
    	return;
    }

    // ================== save / load / create / delete =====================

    public function load($id = false)
    {
        if ($id === false)
            $id = $this->uid;
        assert($id !== null);

        global $tic;

        $qry = "SELECT TICUser.gala as gala, TICUser.planet as planet, nick, ticuser, pw_aendern, ".
            "is_bot, gn_rang, role, last_active, failed_logins, banned, ".
            "timeformat, highlight, scantyp, svs, elokas, authnick, telnr, ".
            "telnr_comment, telnr_visibility, icq, jabber ".
            "FROM TICUser JOIN GNPlayer USING(gala, planet) WHERE ticuser = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($id));
        if ($rs->EOF)
            return false;
        $arr = $rs->FetchRow();

        $this->gala =          $arr['gala'];
        $this->planet =        $arr['planet'];
        $this->nick =          $arr['nick'];
        $this->uid =           $arr['ticuser'];
        $this->pwAendern =     $arr['pw_aendern'];
        $this->isBot =         $arr['is_bot'];
        $this->gnRang =        $arr['gn_rang'];
        $this->role =          $arr['role'];
        $this->lastActive =    $arr['last_active'];
        $this->failedLogins =  $arr['failed_logins'];
        $this->banned =        $arr['banned'];
        $this->timeformat =    $arr['timeformat'];
        $this->highlight =     $arr['highlight'];
        $this->scantyp =       $arr['scantyp'];
        $this->svs =           $arr['svs'];
        $this->elokas =        $arr['elokas'];
        $this->authnick =      $arr['authnick'];
        $this->telnr =         $arr['telnr'];
        $this->telnrComment =  $arr['telnr_comment'];
        $this->telnrVisibility = $arr['telnr_visibility'];
        $this->icq =           $arr['icq'];
        $this->jabber =        $arr['jabber'];
    }

    public function save()
    {
        global $tic;
        assert($this->uid !== null);

        $qry = "UPDATE TICUser SET ".
            "pw_aendern = %s, ".
            "is_bot = %s, ".
            "gn_rang = %s, ".
            "role = %s, ".
            "banned = %s, ".
            "timeformat = %s, ".
            "highlight = %s, ".
            "scantyp = %s, ".
            "svs = %s, ".
            "elokas = %s, ".
            "highlight = %s, ".
            "authnick = %s, ".
            "telnr = %s, ".
            "telnr_comment = %s, ".
            "telnr_visibility = %s, ".
            "icq = %s, ".
            "jabber = %s ".
            "WHERE ticuser = %s, ";
        $arr = array($this->pwAendern,
                     $this->isBot,
                     $this->gnRang,
                     $this->role,
                     $this->banned,
                     $this->timeformat,
                     $this->highlight,
                     $this->scantyp,
                     $this->svs,
                     $this->elokas,
                     $this->authnick,
                     $this->telnr,
                     $this->telnrComment,
                     $this->telnrVisibility,
                     $this->icq,
                     $this->jabber,
                     $this->uid);
        $rs = $tic->db->Execute(get_class($this), $qry, $arr);
    }

    public function delete()
    {
        global $tic;

        if (!$tic->isAllowed(USER_DELETE, $this))
            return false;
        $qry = "DELETE FROM TICUser WHERE ticuser = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->uid));
        if (!$rs)
            return false;
        else {
            $tic->mod['Logging']->log(USER_DELETE, $this);
            return true;
        }
    }

    public function create($pw_hash = '', $salt = null)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CREATE, $this)) {
            $tic->error('UserMan','Kein rechte dazu den User zu erstellen!');
            return false;
        }

        if ($user=$tic->mod['UserMan']->getUserByNick($this->nick)) {
            $tic->error('UserMan','Nick schon vorhanden, er hat die Koords: '.$user->getKoords());
            return false;
        }
        elseif ($user=$tic->mod['UserMan']->getUserByKoords($this->gala, $this->planet)){
        	$tic->error('UserMan','Koords schon vergeben, der User hat den Nick: '.$user->getNick());
        	return false;
        }

        $gala = new Galaxie($this->gala);
        $gala->create(); // fails if gala already exists

        $qry = "DELETE FROM GNPlayer WHERE nick = %s OR (gala = %s AND planet = %s)";
        $tic->db->Execute(get_class($this), $qry, array($this->nick, $this->gala, $this->planet));

        $qry = "INSERT INTO GNPlayer (nick, gala, planet) VALUES (%s, %s, %s)";
        $tic->db->Execute(get_class($this), $qry, array($this->nick, $this->gala, $this->planet));

        $qry = "INSERT INTO TICUser (gala, planet, pw_hash, salt) VALUES (%s, %s, %s, %s)";
        $tic->db->Execute(get_class($this), $qry, array($this->gala, $this->planet, $pw_hash, $salt));
        $this->uid = $tic->db->Insert_ID();
        if (!$tic->disableSecurity)
            $tic->mod['Logging']->log(USER_CREATE, $this);
        return true;
    }

    // ====================== get* ========================

    public function getId()
    {
        return $this->uid;
    }

    public function getPlanet()
    {
        return $this->planet;
    }

    public function getGalaxie()
    {
        return $this->gala;
    }

    public function getPwAendern()
    {
        return $this->PwAendern;
    }

    public function getIsBot()
    {
        return $this->isBot;
    }

    public function getGnRang()
    {
        return $this->gnRangToStr($this->gnRang);
    }

    public function getGnRangId()
    {
        return $this->gnRang;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getLastActive()
    {
        return $this->lastActive;
    }

    public function getFailedLogins()
    {
        return $this->failedLogins;
    }

    public function getBanned()
    {
        return $this->banned;
    }

    public function getTimeformat()
    {
        $tmp = array_flip(TICUser::$timeformatTranslation);
        return $tmp[$this->timeformat];
    }

    public function getAuthnick()
    {
        return $this->authnick;
    }

    public function getHighlight()
    {
        return $this->highlight;
    }

    public function getScantyp()
    {
        switch ($this->scantyp) {
            case 1: return 'Sektorscan';
            case 2: return 'Einheitenscan';
            case 3: return 'Militaerscan';
            case 4: return 'erw. Militaerscan';
            case 5: return 'Geschuetzscan';
            case 6: return 'Nachrichtenscan';
            case 7: return 'erw. Nachrichtenscan';
            case null: return 'unbekannt';
            default: assert(false);
        }
    }

    public function getSvs()
    {
        return $this->svs;
    }

    public function getElokas()
    {
        return $this->elokas;
    }

    public function getTelnr()
    {
        return $this->telnr;
    }

    public function getTelnrComment()
    {
        return $this->telnrComment;
    }

    public function getTelnrVisibility()
    {
        $tmp = array_flip(TICUser::$visibilityTranslation);
        return $tmp[$this->telnrVisibility];
    }

    public function getIcq()
    {
        return $this->icq;
    }

    public function getJabber()
    {
        return $this->jabber;
    }
    
    // ====================== set* ==========================

    public function setKoords($gala, $planet)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_KOORDS, $this, array($gala, $planet)))
            return false;

        if ($tic->mod['UserMan']->getUserByKoords($gala, $planet))
            return false;

        assert($this->uid !== null);
        $qry = "UPDATE TICUser SET gala = %s, planet = %s WHERE ticuser = %s;";
        $tic->db->Execute(get_class($this), $qry, array($gala, $planet, $this->uid));
        $r = $rs ? true : false;
        $tic->mod['Logging']->log(USER_CHANGE_KOORDS, $this, array($gala, $planet));
        return $r;
    }

    public function setNick($nick)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_NICK, $this))
            return false;

        if ($tic->mod['UserMan']->getUserByNick($nick))
            return false;

        $this->setField('nick', $nick);
        $tic->mod['Logging']->log(USER_CHANGE_NICK, $this, $nick);
        return true;
    }

    // gibt das password zurück damit es einmalig angezeigt werden kann
    public function setRandomPassword()
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_SET_RANDOM_PASSWORD, $this))
            return false;
        $password = $tic->mod['Auth']->generateRandomPassword();
        $this->setPasswordHelper($password);
        $tic->mod['Logging']->log(USER_SET_RANDOM_PASSWORD, $this);
        $this->setPwAendern(true);
        return $password;
    }

    public function setPassword($password)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PASSWORD, $this))
            return false;
        $this->setPasswordHelper($password);
        $tic->mod['Logging']->log(USER_CHANGE_PASSWORD, $this);
        $this->setPwAendern(false);
        return true;
    }

    private function setPasswordHelper($password)
    {
        global $tic;
        $salt = $tic->mod['Auth']->generateSalt();
        $hash = $tic->mod['Auth']->hashPassword($password, $salt);
        $this->setField('pw_hash', $hash);
        $this->setField('salt', $salt);
    }

    public function setPwAendern($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PASSWORD, $this))
            return false;

        $this->setField('pw_aendern', $val);
        $this->pwAendern = $val;
        return true;
    }

    public function setIsBot($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_SET_ISBOT, $this))
            return false;

        $this->setField('is_bot', $val);
        $this->isBot = $val;
        $tic->mod['Logging']->log(USER_SET_ISBOT, $this);
        return true;
    }

    public function setGnRang($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_GNRANG, $this, $val))
            return false;

        $rang = $this->gnRangToInt($val);
        if ($rang === false)
            return false;

        $this->setField('gn_rang', $rang);
        $this->gnRang = $rang;
        $tic->mod['Logging']->log(USER_CHANGE_GNRANG, $this, $val);
        return true;
    }

    public function setRole($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_ROLE, $this, $val))
            return false;

        $this->setField('role', $val);
        $this->role = $val;
        $tic->mod['Logging']->log(USER_CHANGE_ROLE, $this, $val);
        return true;
    }

    // nur für auth modul
    public function setLastActive()
    {
        global $tic;
        $qry = "UPDATE TICUser SET last_active = now(), failed_logins = 0 WHERE ticuser = %s;";
        $tic->db->Execute(get_class($this), $qry, array($this->uid));
    }

    // nur für auth modul
    public function incrementFailedLogins()
    {
        global $tic;
        $qry = "UPDATE TICUser SET failed_logins = failed_logins + 1 WHERE ticuser = %s;";
        $tic->db->Execute(get_class($this), $qry, array($this->uid));
    }

    /*public function resetFailedLogins()
    {
        global $tic;
        $qry = "UPDATE TICUser SET failed_logins = 0 WHERE ticuser = %s;";
        $tic->db->Execute(get_class($this), $qry, array($this->uid));
    }*/


    public function setBanned($val)
    {
        global $tic;
        $action = ($val) ? USER_BAN : USER_UNBAN;
        if (!$tic->mod['Right']->isAllowed($action, $this))
            return false;

        $this->setField('banned', $val);
        $this->isBot = $val;
        $tic->mod['Logging']->log($action, $this);
        return true;
    }

    public function setTimeformat($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        assert(array_key_exists($val, TICUser::$timeformatTranslation));
        $tf = TICUser::$timeformatTranslation[$val];

        $this->setField('timeformat', $tf);
        $this->timeformat = $tf;
        return true;
    }

    public function setAuthnick($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('authnick', $val);
        $this->authnick = $val;
        return true;
    }

    public function setHighlight($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('highlight', $val);
        $this->highlight = $val;
        return true;
    }

    public function setScantyp($val)
    {
        global $tic;
        switch ($val) {
            case 'Sektorscan':          $val = 1; break;
            case 'Einheitenscan':       $val = 2; break;
            case 'Militaerscan':        $val = 3; break;
            case 'erw. Militaerscan':   $val = 4; break;
            case 'Geschuetzscan':       $val = 5; break;
            case 'Nachrichtenscan':     $val = 6; break;
            case 'erw. Nachrichtenscan':$val = 7; break;
            case 'unbekannt':           $val = null; break;
            default: assert(false);
        }
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('scantyp', $val);
        $this->scantyp = $val;
        return true;
    }
    public function setSvs($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('svs', $val);
        $this->svs = $val;
        return true;
    }

    public function setElokas($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('elokas', $val);
        $this->elokas = $val;
        return true;
    }

    public function setTelnr($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('telnr', $val);
        $this->telnr = $val;
        return true;
    }

    public function setTelnrComment($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('telnr_comment', $val);
        $this->telnrComment = $val;
        return true;
    }

    public function setTelnrVisibility($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        if (!array_key_exists($val, TICUser::$visibilityTranslation))
            return false;

        $visi = TICUser::$visibilityTranslation[$val];

        $this->setField('telnr_visibility', $visi);
        $this->telnrVisibility = $visi;
        return true;
    }

    public function setIcq($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('icq', $val);
        $this->icq = $val;
        return true;
    }

    public function setJabber($val)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(USER_CHANGE_PROFILE, $this))
            return false;

        $this->setField('jabber', $val);
        $this->jabber = $val;
        return true;
    }


    // ===================== private =======================


    private function setField($field, $val)
    {
        global $tic;
        assert($this->uid !== null);
        $qry = "UPDATE TICUser SET $field = %s WHERE ticuser = %s;";
        $tic->db->Execute(get_class($this), $qry, array($val, $this->uid));
    }

    private function gnRangToStr($val)
    {
        switch ($val) {
            case 0:
                return 'Member';
            case 1:
                return 'VGC';
            case 2:
                return 'GC';
            case 3:
                return 'VHC';
            case 4:
                return 'HC';
            default:
                //FIXME error
                return false;
        }
    }

    private function gnRangToInt($val)
    {
        switch ($val) {
            case 'Member':
                return 0;
            case 'VGC':
                return 1;
            case 'GC':
                return 2;
            case 'VHC':
                return 3;
            case 'HC':
                return 4;
            default:
                //FIXME error
                return false;
        }
    }
}

?>
