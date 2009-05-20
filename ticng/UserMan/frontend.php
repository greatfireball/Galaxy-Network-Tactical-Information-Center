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


function showProfile() 
{
    global $tic;

    $user = $tic->mod['Auth']->getActiveUser();
    if (isset($_POST['profile_post'])) {
        $user->setAuthnick($_POST['auth']);
        $user->setScantyp($_POST['scantyp']);
        $user->setHighlight($_POST['highlight']);
        $user->setSvs($_POST['svs']);
        $user->setElokas($_POST['elokas']);
        $user->setTelnr($_POST['tel']);
        $user->setTelnrComment($_POST['tel_com']);
        if (isset($_POST['tel_vis']))
            $user->setTelnrVisibility($_POST['tel_vis']);
        $user->setICQ($_POST['icq']);
        $user->setJabber($_POST['jabber']);
        if (isset($_POST['timeformat']))
            $user->setTimeformat($_POST['timeformat']);
    }

    //refresh //FIXME: shouldn't be needed
    $user = $tic->mod['Auth']->getActiveUser();

    $tic->mod['UserMan']->setVar('user', $user);
    $tic->mod['UserMan']->setTemplate('userman_profil.tpl');
}

function showMeten()
{
    global $tic;
    processMetenPost();
    $meten = $tic->mod['UserMan']->getAllMeten();
    $tic->mod['UserMan']->setVar('meten', $meten);
    $tic->mod['UserMan']->setTemplate('userman_meten.tpl');
}

function processMetenPost()
{
    global $tic;
    if (isset($_POST['userman_meten'])) {
        foreach ($_POST as $key => $value) {
            $matches = array();
            if (preg_match('/save_([0-9]+)/', $key, $matches)) {
                $meta = $tic->mod['UserMan']->getMetaById($matches[1]);
                $id = $meta->getId();
                if (isset($_POST["meta_name_$id"]) && $meta->getName() != $_POST["meta_name_$id"])
                    $meta->setName($_POST["meta_name_$id"]);
                if (isset($_POST["meta_tag_$id"]) && $meta->getTag() != $_POST["meta_tag_$id"])
                    $meta->setTag($_POST["meta_tag_$id"]);
                continue;
            }
            if (preg_match("/delete_([0-9]+)/", $key, $matches)) {
                $meta = $tic->mod['UserMan']->getMetaById($matches[1]);
                $meta->delete();
                continue;
            }
            if ($key == 'meta_new' && isset($_POST['meta_name_new']) && isset($_POST['meta_tag_new'])) {
                $meta = new Meta($_POST['meta_name_new'], $_POST['meta_tag_new']);
                $meta->create();
            }
        }
    }    
}

function showAllianzen() 
{
    global $tic;
    processAlliPost();
    $allis = $tic->mod['UserMan']->getAllAllianzen();
    $meten = $tic->mod['UserMan']->getAllMeten();
    $tic->mod['UserMan']->setVar('allianzen', $allis);
    $tic->mod['UserMan']->setVar('meten', $meten);
    $tic->mod['UserMan']->setTemplate('userman_allianzen.tpl');
}

function processAlliPost() 
{
    global $tic;

    if (isset($_POST['userman_allianzen'])) {
        foreach ($_POST as $key => $value) {
            $matches = array();
            if (preg_match("/save_([0-9]+)/", $key, $matches)) {
                $alli = $tic->mod['UserMan']->getAllianzById($matches[1]);
                $id = $alli->getId();
                if (isset($_POST["alli_name_$id"]) && $alli->getName() != $_POST["alli_name_$id"])
                    $alli->setName($_POST["alli_name_$id"]);
                if (isset($_POST["alli_tag_$id"]) && $alli->getTag() != $_POST["alli_tag_$id"])
                    $alli->setTag($_POST["alli_tag_$id"]);
                if (isset($_POST["alli_meta_$id"]) && $alli->getMetaId() != $_POST["alli_meta_$id"]) {
                    $metaid = ($_POST["alli_meta_$id"] == "NULL") ? null : $_POST["alli_meta_$id"];
                    $alli->setMeta($metaid);
                }
                continue;
            }
            if (preg_match("/delete_([0-9]+)/", $key, $matches)) {
                $alli = $tic->mod['UserMan']->getAllianzById($matches[1]);
                $alli->delete();
            }
        }
    }
    if (isset($_POST['alli_name_new']) && isset($_POST['alli_tag_new']) && isset($_POST['alli_meta_new'])) {
        $alli = new Allianz($_POST['alli_name_new'], $_POST['alli_tag_new']);
        if (!$alli->create()) {
            //FIXME error
            return;
        }
        $metaid = ($_POST['alli_meta_new'] == "NULL") ? null : $_POST['alli_meta_new'];
        $alli->setMeta($metaid);
    }
}

function showOverview()
{
    global $tic;
//altes qry dessen bedeutung mir unbekannt ist und fehler verursacht
/*    $qry =
        "SELECT Meta.name as meta, Allianz.name as alli, Galaxie.gala as gala ".
        "FROM Meta ".
            "RIGHT JOIN Allianz USING(meta) ".
            "NATURAL FULL OUTER JOIN Galaxie ".
        "WHERE Galaxie.gala IN ".
            "(SELECT Galaxie.gala FROM TICUser NATURAL JOIN Galaxie GROUP BY Galaxie.gala)";
*/ 
    $qry ="SELECT Meta.name as meta, IFNULL(Allianz.name,'No Alli') as alli , Galaxie.gala as gala
FROM Meta 
RIGHT JOIN Allianz Using(meta) 
RIGHT OUTER JOIN Galaxie
ON Galaxie.allianz = Allianz.allianz
order by meta";
    $rs = $tic->db->Execute('UserMan', $qry);

    $meten = array();
    for (;!$rs->EOF; $rs->MoveNext()) {
        $meta = $rs->fields['meta'];
        $alli = $rs->fields['alli'];
        $gala = $rs->fields['gala'];
        if(!array_key_exists($meta, $meten))
            $meten[$meta] = array();
        if (!array_key_exists($alli, $meten[$meta]))
            $meten[$meta][$alli] = array();
        array_push($meten[$meta][$alli], $gala);
    }

    $tic->mod['UserMan']->setVar('meten', $meten);
    $tic->mod['UserMan']->setTemplate('userman_overview.tpl');
}

function showGalaxie_($galanr) 
{
    global $tic;
    processGalaxiePost($galanr);

    $gala = new Galaxie($galanr);
    $users = $gala->getUsers();
    $usersArr = array();
    foreach ($users as $u) {
        $uArr = array();
        $uArr['id'] = $u->getId();
        $uArr['planet'] = $u->getPlanet();
        $uArr['nick'] = $u->getNick();
        $uArr['lastactive'] = $u->getLastActive();
        $uArr['rang'] = $u->getGnRang();
        $uArr['role'] = $u->getRole();
        $uArr['banned'] = $u->getBanned();

        //FIXME
        $uArr['canSetNick'] = true;
        $uArr['canSetPW'] = true;
        $uArr['canSetRang'] = true;
        $uArr['canSetRole'] = true;
        $uArr['canSetBan'] = true;
        $uArr['canDelete'] = true;
        $uArr['canEdit'] = true;
        array_push($usersArr, $uArr);
    }

    $gala = $tic->mod['UserMan']->getGalaxie($galanr);
    $alli = $gala->getAllianz();
    $allis = $tic->mod['UserMan']->getAllAllianzen();

    $tic->mod['UserMan']->setVar('gala', $galanr);
    $tic->mod['UserMan']->setVar('users', $usersArr);
    $tic->mod['UserMan']->setVar('alli', $alli);
    $tic->mod['UserMan']->setVar('allis', $allis);
    $tic->mod['UserMan']->setVar('roles', $tic->mod['Right']->getAllRoles());
    $tic->mod['UserMan']->setTemplate('userman_galaxie.tpl');
}

function processGalaxiePost($galanr)
{
    global $tic;
    if (isset($_POST['galaxie_post'])) {
        foreach ($_POST as $key => $value) {
            if (preg_match('/user_(\w+)_([0-9]+)/', $key, $matches)) {
                $field = $matches[1];
                $id = $matches[2];
                $user = $tic->mod['UserMan']->getUserById($id);
                switch ($field) {
                case 'nick':
                    $user->setNick($value);
                    break;
                case 'rang':
                    $user->setGnRang($value);
                    break;
                case 'role':
                    $user->setRole($value);
                    break;
                case 'banned':
                    $value = $value ? 1 : 0;
                    $user->setBanned($value);
                    break;
                }
            }
        }
    }
    if (isset($_POST['gala_allianz'])) {
        $gala = new Galaxie($galanr);
        if ($tic->mod['UserMan']->getAllianzById($_POST['gala_allianz']))
            $gala->setAllianz($_POST['gala_allianz']);
    }
}

function setRandomPassword($uid)
{
    global $tic;

    $user = $tic->mod['UserMan']->getUserById($uid);
    $pw = $user->setRandomPassword();
    $tic->mod['UserMan']->setVar('koords', $user->getKoords());
    $tic->mod['UserMan']->setVar('nick', $user->getNick());
    $tic->mod['UserMan']->setVar('pass', $pw);
    $tic->mod['UserMan']->setTemplate('userman_showpw.tpl');
}

/*class PostParser 
{
    var $_prefix = null;
    var $_callback = null;

    function PostParser($prefix, $callback)
    {
        $this->_prefix = $prefix;
        $this->_callback = $callback;
    }

    function parse($post) 
    {
        foreach ($post as $key => $value) {
            if (preg_match('/'.$prefix.'_(\w+)_([0-9]+)/', $key, $matches)) {
                $field = $matches[0];
                $id = $mataches[1];
                $callback($field, $id);
            }
        }
    }
}*/


function showCreateUser()
{
    global $tic;
    if (isset($_POST['create_user']) && $_POST['create_user'] == 1) {
        $user = new TICUser($_POST['gala'], $_POST['planet'], $_POST['nick']);
        $user->create();
        $pw = $user->setRandomPassword();
        $user->setGnRang($_POST['rang']);

        $tic->mod['UserMan']->setVar('created', $user);
        $tic->mod['UserMan']->setVar('createdPw', $pw);
    } else {
        $tic->mod['UserMan']->setVar('created', false);
    }
    $tic->mod['UserMan']->setTemplate('userman_create.tpl');
}
