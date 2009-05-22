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

// ================================== Galaxie =================================

class Galaxie {
    private $nr = null;
    private $allianz = null;
    private $anzahlPlanet = null;

    public function __construct($nr,$alli=null)
    {
        $this->nr = $nr;
        $this->allianz=$alli;
        $qry = "SELECT count(planet) as count FROM gnplayer WHERE gala = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($nr));
        $this->anzahlPlanet=$rs->fields['count'];
        
    }

    
    
    function getName()
    {
    	return getclass($this);
    }
    
    public function __toString()
    {
        return $this->nr;
    }

    public function load($nr = false)
    {
        global $tic;

        if ($nr === false)
            $nr = $this->nr;
        $qry = "SELECT gala, allianz FROM galaxie WHERE gala = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($nr));
        if ($rs->EOF)
            return false;
        $this->nr = $rs->fields[0];
        $this->allianz = $rs->fields[1];
        return true;
    }

    public function save()
    {
        global $tic;
        assert($this->nr !== null);
        $qry = "UPDATE galaxie SET allianz = %s WHERE gala = %s";
        $tic->db->Execute($this->getName(), $qry, array($this->allianz, $this->nr));
    }

    public function create()
    {
        global $tic;

        $qry = "SELECT * FROM galaxie WHERE gala = %s;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($this->nr));
        if ($rs->EOF) {
            $qry = "INSERT INTO galaxie (gala,allianz) VALUES (%s, %s);";
            $tic->db->Execute($this->getName(), $qry, array($this->nr,$this->allianz));
            return true;
        }
        return false;
    }

    // FIXME Neu 
    public function delete()
    {
    	global $tic;
   		if (!$tic->mod['Right']->isAllowed(GALA_DELETE, $this)) return false;
  		//Kontrollieren ob noch user vorhanden sind	
 		if($this->anzahlPlanet==0)//wenn nicht dann gala löschen
		{
			$qry= "DELETE FROM galaxie where gala = %s ;";
 			$rs= $tic->db->Execute($this->getName(),$qry,array($this->nr));
 			return true;
  		}
		return false; //wenn gnplayer noch exestieren 
    }

    public function getNr()
    {
        return $this->nr;
    }

    public function getAllianz()
    {
        global $tic;
        if ($this->allianz)
            return $tic->mod['UserMan']->getAllianzById($this->allianz);
        else
            return new Allianz();
    }

    public function getMeta()
    {
        global $tic;
        $qry = "SELECT allianz.meta FROM allianz NATURAL JOIN galaxie WHERE gala = %s";
        $rs = $tic->db->Execute($this->getName(), $qry, array($this->nr));
        if ($rs->EOF)
            return false;
        return $tic->mod['UserMan']->getMetaById($rs->fields[0]);
    }

    public function getUsers()
    {
        global $tic;

        $qry = "SELECT tic_user.ticuser ".
            "FROM tic_user WHERE tic_user.gala = %s ORDER BY planet";
        $rs = $tic->db->Execute($this->getName(), $qry, array($this->nr));
        $users = array();
        while (!$rs->EOF) {
            array_push($users, $tic->mod['UserMan']->getUserById(array($this->nr,$rs->fields[0])));
            $rs->MoveNext();
        }
        return $users;
    }

    public function getPlayer()
    {
        global $tic;
        $qry = "SELECT planet FROM gnplayer WHERE gala = %s ORDER BY planet;";
        $rs = $tic->db->Execute($this->getName(), $qry, array($this->nr));
        $player = array();
        while (!$rs->EOF) {
            array_push($player, $tic->mod['UserMan']->getPlayerByKoords($this->nr, $rs->fields[0]));
            $rs->MoveNext();
        }
        return $player;
    }

    public function setAllianz($alli_id)
    {
        global $tic;

        if ($alli_id === null) {
            if (!$tic->mod['Right']->isAllowed(GALA_CHANGE_ALLI, $this, null))
                return false;
            $tic->mod['Logging']->log(GALA_CHANGE_ALLI, $this, null);
        } else {
            $alli = $tic->mod['UserMan']->getAllianzById($alli_id);
            if (!$alli)
                return false;
            if (!$tic->mod['Right']->isAllowed(GALA_CHANGE_ALLI, $this, $alli))
                return false;
            $tic->mod['Logging']->log(GALA_CHANGE_ALLI, $this, $alli);
        }
        $this->allianz = $alli_id;
        $this->save();
        return true;
    }

    public function toString()
    {
        return "Galaxie {$this->nr}";
    }
}

?>
