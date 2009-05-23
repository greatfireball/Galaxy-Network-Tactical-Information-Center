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

class TaktikAjax extends TICModule
{
    function TaktikAjax()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net")),
	"2",
	"TaktikAjax",
	"Experimentelles Ajax Taktik Modul",
	array(
            "Core" => "4",
            "Design" => "2",
            "UserMan" => "2",
            "ADOdb" => "5",
            "Taktik" => "1"
        ));
    
    }
   
    public function onLoad() {
        global $tic;
        $tic->mod['JSON']->registerAjaxCall('taktikscreens', 'TaktikAjax', 'jsonTaktikScreens');
    }

    public function createMenuEntries($menuroot)
    {
        $main = $menuroot->getChildByName('Main');
        $taktik = $main->getChildByName('Taktik');
        $taktik->addChild(new MenuEntry("Ajax", 4, $this->getName(), "ajax"));
    }

    public function getInstallQueriesMySQL() {return array();}
	public function getInstallQueriesPostgreSQL() {}

    public function onExecute($menuentry)
    {
        $this->setTemplate('taktik_ajax.tpl');
    }

    public function jsonTaktikScreens()
    {
        global $tic;

        if (!isset($_GET['gala']))
            return false;

        $galen = array($_GET['gala']);

        $screens = array();

        foreach ($galen as $gala) {
            $qry = "SELECT user_gala,user_planet, time, now() - time as age FROM taktik_update WHERE galaxie = %s ORDER BY time DESC LIMIT 1";
            $rs = $tic->db->Execute('Taktik', $qry, array($gala));

            $screen = array();
            $screen['gala'] = $gala;

            if (!$rs->EOF) {
                $user = $tic->mod['UserMan']->getUserById($rs->fields['ticuser']);
                $screen['updateUser'] = "{$user->getNick()} ({$user->getKoords()})";
                $screen['date'] = $rs->fields['time'];
                $screen['ageShow'] = true;
                $screen['age'] = $rs->fields['age'];
                if ($rs->fields['age'] < 2*3600) {
                    $screen['ageCss'] = 'taktik_update_new';
                } else {
                    $screen['ageCss'] = 'taktik_update_old';
                }
            } else {
                $screen['updateUser'] = "[unbekannt]";
                $screen['date'] = "noch nie";
                $screen['ageShow'] = false;
                $screen['age'] = '';
                $screen['ageCss'] = '';
            }
            $screen['flotten'] = array();

            $galaObj = new Galaxie($gala);
            $galaUsers = $galaObj->getUsers();

            foreach ($galaUsers as $gUser) {
                $this->insertUser($screen['flotten'], $gUser);
            }

            $a = $tic->mod['Taktik']->findFlotten(array('start_gala' => $gala));
            $b = $tic->mod['Taktik']->findFlotten(array('ziel_gala' => $gala));
            $flotten = array_merge($a, $b);

            $this->insertFlotten($screen['flotten'], $flotten);
            $this->insertSafeInfos($screen['flotten']);

            $screens[] = $screen;
        }

        return $screens;
    }

    private function insertFlotten(&$screen, &$flotten)
    {
        foreach ($flotten as $flotteObj) {
            $flotte = $flotteObj->toJson();
            $zielUser = $flotteObj->getZielUser();
            $startUser = $flotteObj->getStartUser();

            // outgoing
            if ($startUser && array_key_exists($startUser->getKoords(), $screen)) {
                $screen[$startUser->getKoords()]['outgoing'][] =  $flotte;
            }

            // incs
            if ($zielUser && $flotteObj->getAngriff() && !$flotteObj->getRueckflug() && 
                array_key_exists($zielUser->getKoords(), $screen)) {
                $screen[$zielUser->getKoords()]['incs'][] = $flotte;
            }

            // deff
            if ($zielUser && !$flotteObj->getAngriff() && !$flotteObj->getRueckflug() &&
                array_key_exists($zielUser->getKoords(), $screen)) {
                $screen[$zielUser->getKoords()]['deff'][] = $flotte;
            }
        }
    }

    private function insertUser(&$screen, &$user)
    {
        $screen[$user->getKoords()] = array('user' => $user->toString(),
                                            'outgoing' => array(),
                                            'incs' => array(),
                                            'deff' => array()
                                           );
    }

    private function insertSafeInfos(&$screen)
    {
        foreach ($screen as $koords => $user) {
            if (!is_array($user))
                continue;
            $safe = 0;
            $unsafe = 0;
            foreach ($user['incs'] as $inc) {
                if ($inc->getSafe())
                    $safe++;
                else
                    $unsafe++;
            }
            if (!$safe && !$unsafe)
                $screen[$koords]['safe'] = 'nothing';
            else if ($safe && !$unsafe)
                $screen[$koords]['safe'] = 'safe';
            else if (!$safe && $unsafe)
                $screen[$koords]['safe'] = 'unsafe';
            else
                $screen[$koords]['safe'] = 'half_safe';
        }
    }
}
?>
