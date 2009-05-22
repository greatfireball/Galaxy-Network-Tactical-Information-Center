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
require_once('./Right/Right.iface.php');
class NewsItem implements Right_IFace {
    private $_id = null;          //id der nachricht
    private $_sender = array();      //id des sendenden array(sender_gala,sender_planet)
    private $_time = null;        //uhrzeit als string
    private $_subject = null;     //betreff
    private $_text = null;        //inhalt
    private $_audience = null;    //nummer die angibt ob die nachricht an gala, alli, meta oder alle geht
    private $_audience_id = null; //bedeutung abhÃ¤ngig von _audience (gala nr, alli id, meta id, keine)

    private $_readingNow = false;

    function NewsItem($sender = array(), $subject = null, $text = null, $audience = null, $audience_id = null, $time = null, $news_id = null)
    {
        $this->_id = $news_id;
        if (is_object($sender))
            $this->_sender = $sender->getId();
        else
            $this->_sender = $sender;
        $this->_time = $time;
        $this->_subject = $subject;
        $this->_text = $text;
        if (is_numeric($audience) || $audience === null)
            $this->_audience = $audience;
        else
            $this->_audience = $this->_audienceStrToInt($audience);
        $this->_audience_id = $audience_id;
    }

    function create()
    {
        global $tic;
        assert($this->_id === null);
        assert($this->_time === null);
        $sender = $tic->mod['UserMan']->getUserById($this->_sender);
        switch($this->_audience) {
        case 0:
            $action = NEWS_WRITE_GALA;
            $this->_audience_id = $sender->getGalaxie();
            break;
        case 1:
            $action = NEWS_WRITE_ALLI;
            $alli = $sender->getAllianz();
            if (!$alli)
                return false;
            $this->_audience_id = $alli->getId();
            break;
        case 2:
            $meta = $sender->getMeta();
            if (!$meta)
                return false;
            $this->_audience_id = $meta->getId();
            $action = NEWS_WRITE_META;
            break;
        case 3:
            $action = NEWS_WRITE_ALLE;
            $this->_audience_id = null;
            break;
        default:
            assert(false);
        }
        if (!$tic->mod['Right']->isAllowed($action, $this))
            return false;
        $qry = "INSERT INTO news (sender_gala, sender_planet, subject, text, audience, audience_id) VALUES (%s, %s, %s, %s, %s)";
        $tic->db->Execute(get_class($this), $qry, array($this->_sender[0],$this->_sender[1], $this->_subject, $this->_text, $this->_audience, $this->_audience_id));
        $this->_id = $tic->db->Insert_ID();
        //FIXME get time from DB ??
        $tic->mod['Logging']->log($action, $this);
        return true;
    }
    //FIXME richtige rückgabe werte
	function getGalaxie()
	{
		return;
	} 
	
	function getMeta()
	{
		return;
	}
	
	function getAllianz()
	{
		return;
	}
	
    function delete()
    {
        global $tic;
        $action = $this->_getDeleteAction();
        assert($this->_id !== null);
        if (!$tic->mod['Right']->isAllowed($action, $this))
            return false;
        $qry = "DELETE FROM news_read WHERE news = %s";
        $tic->db->Execute(get_class($this), $qry, array($this->_id));
        $qry = "DELETE FROM news WHERE news = %s";
        $tic->db->Execute(get_class($this), $qry, array($this->_id));
        $tic->mod['Logging']->log($action, $this);
        return true;
    }

    function load($id)
    {
        global $tic;
        $qry = "SELECT news, sender_gala, sender_planet, subject, text, audience, audience_id, time FROM news WHERE news = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($id));
        if ($rs->EOF)
            return false;
        $this->_id = $rs->fields[0];
        $this->_sender = array($rs->fields[1],$rs->fields[2]);
        $this->_subject = $rs->fields[3];
        $this->_text = $rs->fields[4];
        $this->_audience = $rs->fields[5];
        $this->_audience_id = $rs->fields[6];
        $this->_time = $rs->fields[7];
        return true;
    }

    function save()
    {
        //FIXME ???
        assert(false);
    }

    function canDelete()
    {
        global $tic;
        return $tic->mod['Right']->isAllowed($this->getDeleteAction, $this);
    }

    function _getDeleteAction()
    {
        switch($this->_audience) {
        case 0:
            $action = NEWS_DELETE_GALA;
            break;
        case 1:
            $action = NEWS_DELETE_ALLI;
            break;
        case 2:
            $action = NEWS_DELETE_META;
            break;
        case 3:
            $action = NEWS_DELETE_ALLE;
            break;
        default:
            assert(false);
        }
    }

    function isVisible()
    {
        global $tic;
        assert($this->_audience !== null);
        $user = $tic->mod['Auth']->getActiveUser();
        switch ($this->_audience) {
        case 0: // gala
            return ($user->getGalaxie() == $this->_audience_id);
        case 1: // alli
            $alli = $user->getAllianz();
            return ($alli->getId() == $this->_audience_id);
        case 2: // meta
            $meta = $user->getMeta();
            return ($meta->getId() == $this->_audience_id);
        case 3: // alle
            return true;
        default:
            assert(false);
        }
    }

    function isRead()
    {
        global $tic;
        assert($this->_id !== null);
        if ($this->_readingNow)
            return false;
        $user = $tic->mod['Auth']->getActiveUser();
        $user_id = $user->getId();
        $qry = "SELECT * FROM news_read WHERE news = %s AND gala = %s and planet= %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->_id, $user_id[0], $user_id[1]));
        if ($rs->EOF)
            return false;
        else
            return true;
    }

    function markAsRead()
    {
        global $tic;
        assert($this->_id !== null);
        if ($this->isRead())
            return true;
        $user = $tic->mod['Auth']->getActiveUser();
        $user_id = $user->getId();
        $qry = "INSERT INTO news_read (news, gala,planet) VALUES (%s, %s, %s)";
        $tic->db->Execute(get_class($this), $qry, array($this->_id, $user_id[0],$user_id[1]));
        $this->_readingNow = true;
        return true;
    }

    function _audienceStrToInt($audience)
    {
        assert(is_string($audience));
        switch (trim($audience)) {
        case 'Galaxie':
            return 0;
        case 'Allianz':
            return 1;
        case 'Meta':
            return 2;
        case 'Alle':
            return 3;
        default:
            assert(false);
        }
    }

    function _audienceIntToStr($audience)
    {
        assert(is_numeric($audience));
        switch ($audience) {
        case 0:
            return 'Galaxie';
        case 1:
            return 'Allianz';
        case 2:
            return 'Meta';
        case 3:
            return 'Alle';
        default:
            assert(false);
        }
    }

    function getId()
    {
        return $this->_id;
    }

    function getSender()
    {
        return $this->_sender;
    }

    function getSenderStr()
    {
        //FIXME cache ?
        global $tic;
        $sender = $tic->mod['UserMan']->getUserById($this->_sender);
        return $sender->toString();
    }

    function getTime()
    {
        return $this->_time;
    }

    function getSubject()
    {
        return $this->_subject;
    }

    function getSubjectHTML()
    {
        return htmlentities($this->_subject);
    }

    function getText()
    {
        return $this->_text;
    }

    function getTextHTML()
    {
        $text = htmlentities($this->_text);
        return str_replace("\n", "<br>\n", $text);
    }

    function getAudience()
    {
        return $this->_audience;
    }

    function getAudienceStr()
    {
        return $this->_audienceIntToStr($this->_audience);
    }

    function getAudienceId()
    {
        return $this->_audience_id;
    }

    function getAudienceIdStr()
    {
        global $tic;
        $x = null;
        switch ($this->_audience) {
        case 0: //gala
            $gala = $tic->mod['UserMan']->getGalaxie($this->_audience_id);
            return $gala->toString();
        case 1: //alli
            $alli = $tic->mod['UserMan']->getAllianzById($this->_audience_id);
            return $alli->toString();
        case 2:
            $meta = $tic->mod['UserMan']->getMetaById($this->_audience_id);
            return $meta->toString();
        case 3:
            return 'Alle';
        default:
            assert(false);
        }
    }

    function toString()
    {
        global $tic;
        $sender = $tic->mod['UserMan']->getUserById($this->_sender);
        return "Nachricht ({$this->_id}: {$this->_subject})";
    }
}

?>
