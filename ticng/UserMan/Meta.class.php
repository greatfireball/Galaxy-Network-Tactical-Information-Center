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

// ==================================== Meta ==================================

class Meta {
    private $id = null;
    private $name = null;
    private $tag = null;

    public function __construct($name = null, $tag = null)
    {
        $this->name = $name;
        $this->tag = $tag;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function load($id = false)
    {
        global $tic;
        if ($id === false)
            $id = $this->id;
        assert($id !== null);

        $qry = "SELECT meta, name, tag FROM meta WHERE meta = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($id));
        if ($rs->EOF)
            return false;
        $this->id = $rs->fields[0];
        $this->name = $rs->fields[1];
        $this->tag = $rs->fields[2];
        return true;
    }

    public function save()
    {
        global $tic;
        assert($this->id !== null);
        $qry = "UPDATE meta SET name = %s, tag = %s WHERE meta = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->name, $this->tag, $this->id));
    }

    public function create()
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(META_CREATE, $this))
            return false;

        if ($tic->mod['UserMan']->getMetaByName($this->name) || $tic->mod['UserMan']->getMetaByTag($this->tag))
            return false;
        $qry = "INSERT INTO meta (name, tag) VALUES (%s, %s)";
        $tic->db->Execute($this->getName(), $qry, array($this->name, $this->tag));
        $this->id = $tic->db->Insert_ID();
        $tic->mod['Logging']->log(META_CREATE, $this);
        return true;
    }

    public function delete()
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(META_DELETE, $this))
            return false;
        $qry = "UPDATE allianz SET meta = NULL WHERE meta = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        $qry = "DELETE FROM meta WHERE meta = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        $tic->mod['Logging']->log(META_DELETE, $this);
    }

    public function getId()
    {
        assert($this->id);
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

    public function getAllianzen()
    {
        global $tic;
        $qry = "SELECT allianz FROM allianz WHERE meta = %s";
        $rs = $tic->db->Execute(get_class($this), $qry, array($this->id));
        $allies = array();
        for (; !$rs->EOF; $rs->moveNext()) {
            array_push($allies, $tic->mod['UserMan']->getAllianzById($rs->fields[0]));
        }
        return $allies;
    }

    public function setName($name)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(META_CHANGE_TAG, $this))
            return false;
        $tic->mod['Logging']->log(META_CHANGE_NAME, $this, $name);
        $this->name = $name;
        $this->save();
        return true;
    }

    public function setTag($tag)
    {
        global $tic;
        if (!$tic->mod['Right']->isAllowed(META_CHANGE_TAG, $this))
            return false;
        $tic->mod['Logging']->log(META_CHANGE_TAG, $this, $tag);
        $this->tag = $tag;
        $this->save();
        return true;
    }

    public function toString()
    {
        return "Meta {$this->tag}";
    }
}

?>
