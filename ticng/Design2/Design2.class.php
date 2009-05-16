<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006 Andreas Hemel <dai.shan@gmx.net>                              *
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

require_once('Template.class.php');

class Design2 extends TICModule
{
    private $_design = 'default';
    private $_path = '';
    private $_main_template = 'main.tpl';
    
    function Design2()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net")),
	"2",
	"Design2",
	"PHP basiertes Template System",
	array(
            "Core" => "1","ADOdb" => "2"
        ));
    }     
    
   	function get_Design()
	{
		return $this->_design;
	}
	
	function get_Design_path()
	{
		return $this->_path;
	}
	
	function get_Design_maintpl()
	{
		return $this->_main_template;
	} 
    
    function onLoad()
    {
        global $tic;
        $this->_path = dirname(__FILE__);
        $this->_design = $tic->mod['ADOdb']->get($this->getName(), 'design', 'default');
    }
    
    function onUnload()
    {
        return;
        global $tic;
        if ($tic->executed_mod == '')
            return;

        if ($tic->accessDenied) {
            $main = new Template($tic->mod['Auth']->getSmarty_template(), $this->_path.'/'.$this->_design);
            $main->setVars($tic->mod['Auth']->smarty_vars);
        } else {
            $main = $this->_createMainTemplate();
        }
        $main->exec();
    }

    function _createMainTemplate()
    {
        global $tic;

        $main = new Template($this->_main_template, $this->_path.'/'.$this->_design);

        $vars = array();
        foreach ($tic->modListLoaded() as $mod) {
            if ($mod != $tic->executed_mod)
                $vars = array_merge($vars, $tic->mod[$mod]->smarty_vars);
        }
        $main->setVars($vars);

        $tpl_name = $tic->mod[$tic->executed_mod]->getSmarty_template();
        $mod_tpl = new Template($tpl_name, $this->_path.'/'.$this->_design);
        $mod_tpl->setVars($tic->mod[$tic->executed_mod]->smarty_vars);
        $main->setVar('mod_template', $mod_tpl);
        $main->setVar('mod_name', $tic->executed_mod);
        $main->setVar('mod_version', $tic->mod[$tic->executed_mod]->getVersion());
        $main->setVar('tic_version', $tic->getVersion());

        $menu = new MenuEntry("_toplevel_");
        foreach ($tic->modListLoaded() as $mod) {
            $entries = $tic->mod[$mod]->getMenuEntry();
            if (is_array($entries)) {
                foreach ($entries as $entry) {
                    $menu->addSubEntry($entry);
                }
            }
        }
        $menu = $this->_genMenu($menu, -1, true);
        $menu[0]['first'] = true;
        $main->setVar('menu', $menu);

        if (isset($_GET['debug']))
            $main->setVar('debug', true);
        else
            $main->setVar('debug', false);

        $main->setVar('user', $tic->mod['Auth']->getActiveUser());
        $main->setVar('queries', $tic->db->getQueryCounter());
        $main->setVar('queries_failed', $tic->db->getQueryFailedCounter());
        $main->setVar('dbtype', $tic->mod['ADOdb']->getDBType());
        return $main;
    }

    function _genMenu($entry, $level)
    {
        $menu = array();
        if ($entry->isLeaf()) {
            if ($level >= 0) {
                $tmp = array('name' => $entry->name,
                             'mod' => $entry->mod,
                             'param' => $entry->param,
                             'level' => $level,
                             'first' => false
                         );
                array_push($menu, $tmp);
            }
        } else {
            if ($level >= 0) {
                $tmp = array('name' => $entry->name,
                             'mod' => $entry->mod,
                             'param' => $entry->param,
                             'level' => $level,
                             'first' => false
                         );
                array_push($menu, $tmp);
            }
            foreach ($entry->subentries as $sub) {
               $menu = array_merge($menu, $this->_genMenu($sub, $level+1));
            }
        }
        return $menu;
    }

    function getInstallQueriesMySQL() { return array(); }
    function getInstallQueriesPostgreSQL() { return array(); }
}

?>
