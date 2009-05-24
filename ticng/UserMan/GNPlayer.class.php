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

// ================================== GNPlayer =================================

class GNPlayer {
    protected $gala = null;
    protected $planet = null;
    protected $nick = null;
    protected $id = array();

    public function __construct($gala = null, $planet = null, $nick = null)
    {
        $this->gala = $gala;
        $this->planet = $planet;
        $this->nick = $nick;
        $this->id=array($gala,$planet);
    }

    public function __toString()
    {
        return $this->id;
    }

    public function save()
    {
        global $tic;
        assert($this->id !== null);
        $qry = "UPDATE gnplayer SET nick = %s WHERE gala = %s and planet = %s";
        $tic->db->Execute(get_class($this), $qry, array($this->nick, $this->gala, $this->planet));
    }

    public function load($id = false)
    {
        global $tic;

        if ($id === false)
            $id = $this->id;
        assert($id !== null);

        $qry = "SELECT nick FROM gnplayer WHERE gala=%s and planet=%s";
        $rs = $tic->db->Execute(get_class($this), $qry, $id);
        if ($rs->EOF)
            return false;
        $this->nick = $rs->fields['nick'];
        $this->gala = $id[0];
        $this->palnet = $id[1];
        $this->id = $id;
        return true;
    }
    public function torelocate($gala,$planet) // wenn planet geändert soll
    {
    	
    	return;
    }

    public function create()
    {
        global $tic;
        // ist nicht mehr zeit gemaess assert($this->nick !== null && $this->gala !== null && $this->planet !== null && $this->id === null);
        $nick = $this->nick;
        $gala = $this->gala;
        $planet = $this->planet;

        //check ob nick *und* koords schon existieren, d.h. player existiert schon, alles in butter
        $qry = "SELECT nick, gala, planet FROM gnplayer WHERE nick = %s or (gala = %s AND planet = %s)";
        $rs = $tic->db->Execute(get_class($this), $qry, array($nick, $gala, $planet));
        if ($rs->RecordCount()==1 && $nick==$rs->fields[0]) {
            $this->id = array($rs->fields[1],$rs->fields[2]);
            return true;
        }else if($rs->RecordCount()==2)
        {
        	return false; //nick und Koords in anderen Rows  vorhanden sind
        }

        //check ob nick *oder* koords schon existieren
        $qry = "SELECT nick, gala, planet FROM gnplayer WHERE lower(nick) = %s OR (gala = %s AND planet = %s)";
        $rs = $tic->db->Execute(get_class($this), $qry, array(strtolower($nick), $gala, $planet));
        if (!$rs->EOF) {
            // es existieren bereits player mit gleichen koords oder nick
            $qry = "SELECT * FROM gnplayer NATURAL JOIN tic_user ".
                "WHERE nick = %s OR (gnplayer.gala = %s AND gnplayer.planet = %s)";
            $rs = $tic->db->Execute(get_class($this), $qry, array($nick, $planet, $gala));
            if ($rs->EOF) {
                // kein user existiert zu den player mit gleichen koords oder nick
                // wir koennen die player einfach loeschen
                $qry = "DELETE FROM gnplayer WHERE nick = %s OR (gala = %s AND planet = %s)";
                $tic->db->Execute(get_class($this), $qry, array($nick, $planet, $gala));
            } else {
                // es existiert schon ein user, player kann nicht erstellt werden
                return false;
            }
            $this->id = $rs->fields['gnplayer'];
        }
        $galaobj = new Galaxie($gala);
        $galaobj->create(); //simply failes if gala already exists
        if($nick===null){ // wenn der nick in der DB schon richtig drin steht um dsa er nicht ueberchrieben wird
        	$qry = "INSERT INTO gnplayer (planet, gala) VALUES (%s, %s)";
       		$tic->db->Execute(get_class($this), $qry, array($planet, $gala));
        }else{
        $qry = "INSERT INTO gnplayer (nick, planet, gala) VALUES (%s, %s, %s)";
        $tic->db->Execute(get_class($this), $qry, array($nick, $planet, $gala));
        }
        $this->id = array($gala,$planet);
        return true;
    }

    public function delete()
    {
    	//FIXME user muss überall vorher gelöscht werden
    	if($this->checkForUser()==true) return false; //user noch in tic_user vorhanden daher nicht lösch bar
    	global $tic;
    	$sql =" Delete FROM gnplayer";
        assert(false);
    }


    public function getGalaxie()
    {
        return $this->gala;
    }

    public function getPlanet()
    {
        return $this->planet;
    }

    public function getKoords()
    {
        return $this->gala.':'.$this->planet;
    }

    public function getNick()
    {
        return $this->nick;
    }

    public function getUser()
    {
        global $tic;
        return $tic->mod['UserMan']->getUserByKoords($this->gala, $this->planet);
    }

    public function getGalaxieObj()
    {
        global $tic;
        return $tic->mod['UserMan']->getGalaxie($this->gala);
    }

    public function getAllianz()
    {
        global $tic;
        $qry = "SELECT allianz FROM galaxie NATURAL JOIN gnplayer ".
            "WHERE gnplayer.gala = %s AND gnplayer.planet = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->gala, $this->planet));
        if ($rs->EOF)
            return false;
        if (!is_numeric($rs->fields[0]))
            return new Allianz();
        return $tic->mod['UserMan']->getAllianzById($rs->fields[0]);
    }

    public function getMeta()
    {
        global $tic;
        $qry = "SELECT meta FROM allianz NATURAL JOIN galaxie NATURAL JOIN gnplayer ".
            "WHERE gnplayer.gala = %s AND gnplayer.planet = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->gala, $this->planet));
        if ($rs->EOF)
            return false;
        if (!is_numeric($rs->fields[0]))
            return false;
        return $tic->mod['UserMan']->getMetaById($rs->fields[0]);
    }
//FIXME ist die funktion noch sinvoll ?
    public function setKoords($gala, $planet)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(PLAYER_CHANGE_KOORDS, $this))
            return false;

        if ($this->checkForUser())
            return false;

        $this->gala = $gala;
        $this->planet = $planet;
        $this->save();
        return true;
    }

    public function setNick($nick)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(PLAYER_CHANGE_NICK, $this))
            return false;

        if ($this->checkForUser())
            return false;

        $this->nick = $nick;
        $this->save();
    }

    public function toString() {
        return "({$this->gala}:{$this->planet}) {$this->nick}";
    }

    private function checkForUser()
    {
        global $tic;
        // gibt true zurÃ¼ck wenn ein user zu diesem player existiert
        $qry = "SELECT count(*) FROM tic_user WHERE gala = %s AND planet = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->gala, $this->planet));
        if ($rs->fields[0] == 0)
            return false;
        else
            return true;
    }
}

?>
