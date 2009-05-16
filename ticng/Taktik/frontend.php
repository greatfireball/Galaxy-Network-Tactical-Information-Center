<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006,2007 Andreas Hemel  <dai.shan@gmx.net>                        *
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

function showTaktikFlotten()
{
    global $tic;

    $flotten = $tic->mod['Taktik']->findFlotten(array());
    $screen = array(); // array das koord strings von usern als indices hat und weitere arrays enthaelt
    $screen['flotten'] = array();

    insertFlotten($screen['flotten'], $flotten);
    insertSaveInfos($screen['flotten']);

    $tic->mod['Taktik']->setVar('screen', $screen);
    $tic->mod['Taktik']->setTemplate('taktik_flotten.tpl');
}

function showGalaxie()
{
    global $tic;
    $user = $tic->mod['Auth']->getActiveUser();
    $galen = array($user->getGalaxie());
    showTaktikScreens($galen);
}

function showAllianz()
{
    global $tic;
    $user = $tic->mod['Auth']->getActiveUser();
    $alli = $user->getAllianz(); //FIXME was wenn user keine alli hat?
    $galen = $alli->getGalaxienId();
    showTaktikScreens($galen);
}

function showTaktikScreens($galen)
{
    global $tic;
    $screens = array();

    foreach ($galen as $gala) {
        $qry = "SELECT ticuser, time, now() - time as age FROM TaktikUpdate WHERE galaxie = %s ORDER BY time DESC LIMIT 1";
        $rs = $tic->db->Execute('Taktik', $qry, array($gala));

        $screen = array();
        $screen['gala'] = $gala;

        if (!$rs->EOF) {
            $user = $tic->mod['UserMan']->getUserById($rs->fields['ticuser']);
            $screen['user'] = "{$user->getNick()} ({$user->getKoords()})";
            $screen['date'] = $rs->fields['time'];
            $screen['show_age'] = true;
            $screen['age'] = $rs->fields['age'];
            if ($rs->fields['age'] < 2*3600) {
                $screen['age_css'] = 'taktik_update_new';
            } else {
                $screen['age_css'] = 'taktik_update_old';
            }
        } else {
            $screen['user'] = "[unbekannt]";
            $screen['date'] = "noch nie";
            $screen['show_age'] = false;
            $screen['age'] = '';
            $screen['age_css'] = '';
        }
        $screen['flotten'] = array();

        $galaObj = new Galaxie($gala);
        $galaUsers = $galaObj->getUsers();

        foreach ($galaUsers as $gUser) {
            insertUser($screen['flotten'], $gUser);
        }

        $a = $tic->mod['Taktik']->findFlotten(array('start_gala' => $gala));
        $b = $tic->mod['Taktik']->findFlotten(array('ziel_gala' => $gala));
        $flotten = array_merge($a, $b);

        insertFlotten($screen['flotten'], $flotten);
        insertSaveInfos($screen['flotten']);

        $screens[$gala] = $screen;
    }

    $tic->mod['Taktik']->setVar('screens', $screens);
    $tic->mod['Taktik']->setTemplate('taktik_screens.tpl');
}

function insertFlotten(&$screen, &$flotten)
{
    foreach ($flotten as $flotte) {
        $zielUser = $flotte->getZielUser();
        $startUser = $flotte->getStartUser();

        // outgoing
        if ($startUser && array_key_exists($startUser->getKoords(), $screen)) {
            $screen[$startUser->getKoords()]['outgoing'][] =  $flotte;
        }

        // incs
        if ($zielUser && $flotte->getAngriff() && !$flotte->getRueckflug() && 
            array_key_exists($zielUser->getKoords(), $screen)) {
            $screen[$zielUser->getKoords()]['incs'][] = $flotte;
        }

        // deff
        if ($zielUser && !$flotte->getAngriff() && !$flotte->getRueckflug() &&
            array_key_exists($zielUser->getKoords(), $screen)) {
            $screen[$zielUser->getKoords()]['deff'][] = $flotte;
        }
    }
}

function insertUser(&$screen, &$user)
{
    $screen[$user->getKoords()] = array('user' => $user->toString(),
                                        'outgoing' => array(),
                                        'incs' => array(),
                                        'deff' => array()
                                       );
}

function insertSaveInfos(&$screen)
{
    foreach ($screen as $koords => $user) {
        if (!is_array($user))
            continue;
        $save = 0;
        $unsave = 0;
        foreach ($user['incs'] as $inc) {
            if ($inc->getSafe())
                $save++;
            else
                $unsave++;
        }
        if (!$save && !$unsave)
            $screen[$koords]['save'] = 'nothing';
        else if ($save && !$unsave)
            $screen[$koords]['save'] = 'save';
        else if (!$save && $unsave)
            $screen[$koords]['save'] = 'unsave';
        else
            $screen[$koords]['save'] = 'half_save';
    }
}


function showRawTable()
{
    global $tic;

    $flotten = $tic->mod['Taktik']->findFlotten(array());
    $tic->mod['Taktik']->setVar('flotten', $flotten);
    $tic->mod['Taktik']->setTemplate('taktik_raw.tpl');
}

?>
