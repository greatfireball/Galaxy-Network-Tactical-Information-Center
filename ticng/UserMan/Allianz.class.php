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


// ================================== Allianz =================================

class Allianz {
    private $id = null;
    private $name = null;
    private $tag = null;
    private $meta = null;

    public function Allianz($name = null, $tag = null)
    {
        $this->name = $name;
        $this->tag = $tag;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function load($alli_id = false)
    {
        global $tic;
        if ($alli_id === false)
            $alli_id = $this->id;
        assert($alli_id !== null);

        $qry = "SELECT allianz, name, tag, meta FROM allianz WHERE allianz = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($alli_id));
        if ($rs->EOF)
            return false;
        $this->id = $rs->fields[0];
        $this->name = $rs->fields[1];
        $this->tag = $rs->fields[2];
        $this->meta = $rs->fields[3];
        return true;
    }

    public function save()
    {
        global $tic;
        assert($this->id !== null);
        $qry = "UPDATE allianz SET name = %s, tag = %s, meta = %s WHERE allianz = %s";
        $tic->db->Execute(get_class($this), $qry, array($this->name, $this->tag, $this->meta, $this->id));
    }

    public function create()
    {
    	if($this->name === null ||$this->tag === null) return false;
        global $tic;
        if (!$tic->mod['Right']->isAllowed(ALLI_CREATE, $this))
            return false;

        if ($tic->mod['UserMan']->getAllianzByName($this->name) || $tic->mod['UserMan']->getAllianzByTag($this->tag))
            return false;
        $qry = "INSERT INTO allianz (name, tag) VALUES (%s, %s)";
        $tic->db->Execute(get_class($this), $qry, array($this->name, $this->tag));
        $this->id = $tic->db->Insert_ID();
        assert($this->id);
        $tic->mod['Logging']->log(ALLI_CREATE, $this);
        return true;
    }

    public function delete()
    {
        global $tic;
        assert($this->id !== null);
        if (!$tic->mod['Right']->isAllowed(ALLI_DELETE, $this))
            return false;

        $qry = "UPDATE galaxie SET allianz = NULL WHERE allianz = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        $qry = "DELETE FROM allianz WHERE allianz = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        $tic->mod['Logging']->log(ALLI_DELETE, $this);
        return true;
    }

    public function getId()
    {
      //  assert($this->id);
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function getGalaxien()
    {
        global $tic;

        $qry = "SELECT gala FROM galaxie WHERE allianz = %s;";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        $galaxien = array();
        while (!$rs->EOF) {
            array_push($galaxien, new Galaxie($rs->fields[0]));
            $rs->MoveNext();
        }
        return $galaxien;
    }

    public function getGalaxienId()
    {
        global $tic;

        $qry = "SELECT gala FROM galaxie WHERE allianz = %s;";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        $galaxien = array();
        while (!$rs->EOF) {
            array_push($galaxien, $rs->fields[0]);
            $rs->MoveNext();
        }
        return $galaxien;
    }

    public function getUsers()
    {
        $galen = $this->getGalaxien();
        $users = array();
        foreach ($galen as $gala) {
            foreach ($gala->getUsers() as $user) {
                array_push($users, $user);
            }
        }
        return $users;
    }

    public function getMeta()
    {
        global $tic;
        return $tic->mod['UserMan']->getMetaById($this->meta);
    }

    public function getMetaId()
    {
        return $this->meta;
    }

    public function getMemberCount()
    {
        global $tic;
        $qry = "SELECT count(*) FROM tic_user NATURAL JOIN galaxie WHERE allianz = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        assert($rs);
        return $rs->fields[0];
    }

    public function getOnlineUserCount()
    {
        global $tic;
        $min = 5;
        $qry = "SELECT count(*) FROM tic_user NATURAL JOIN galaxie WHERE allianz = %s AND (last_active + '5'*'.$min.') > now()";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        assert($rs);
        return $rs->fields[0];
    }

    public function setName($name)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(ALLI_CHANGE_NAME, $this))
            return false;
        if ($tic->mod['UserMan']->getMetaByName($name))
            return false;
        $tic->mod['Logging']->log(ALLI_CHANGE_NAME, $this, $name);
        $this->name = $name;
        $this->save();
        return true;
    }

    public function setTag($tag)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(ALLI_CHANGE_TAG, $this))
            return false;
        if ($tic->mod['UserMan']->getMetaByTag($tag))
            return false;
        $tic->mod['Logging']->log(ALLI_CHANGE_TAG, $this, $tag);
        $this->tag = $tag;
        $this->save();
        return true;
    }

    public function setMeta($meta_id)
    {
        global $tic;
        assert(is_numeric($meta_id) || $meta_id === null);

        if ($meta_id === null) {
            if (!$tic->mod['Right']->isAllowed(ALLI_CHANGE_META, $this, null))
                return false;
            $tic->mod['Logging']->log(ALLI_CHANGE_META, $this, null);
        } else {
            $meta = $tic->mod['UserMan']->getMetaById($meta_id);
            if (!$meta)
                return false;
            if (!$tic->mod['Right']->isAllowed(ALLI_CHANGE_META, $this, $meta))
                return false;
            $tic->mod['Logging']->log(ALLI_CHANGE_META, $this, $meta);
        }
        $this->meta = $meta_id;
        $this->save();
        return true;
    }

    public function toString()
    {
        return "Allianz {$this->tag}";
    }
}

?>
