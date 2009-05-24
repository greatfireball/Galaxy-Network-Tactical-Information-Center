<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006  Tobias Sarnowski  <sarnowski@new-thoughts.og>                *
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

//
// Class Design
//
//

require('smarty/Smarty.class.php');

// --------------------------------------------------------------------------------- //


class Design extends TICModule
{
    private $_design = 'default';
    private $_design_path = '';
    private $_design_maintpl = 'main.tpl';
    private $show = true;
    
    private $_smarty;

    function Design()
    {
	parent::__construct(
	array(new Author("Tobias Sarnowski", "NataS", "sarnowski@new-thoughts.org"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"Design",
	"Template Manager",
	array(
            "Core" => "10"
        ));
    }
    
	function get_Design()
	{
		return $this->_design;
	}
	
	function get_Design_path()
	{
		return $this->_design_path;
	}
	
	function get_Design_maintpl()
	{
		return $this->_design_maintpl;
	}
    
    function onLoad()
    {
        global $tic;
        if ($this->show === false) { return; }
        $this->_design_path = $tic->server_root.$this->getName().'/';

        $this->_smarty = new Smarty();
        $this->_smarty->compile_dir = $this->getModPath().'template_c/';

        $this->setDesign($tic->mod['Core']->get($this->getName(), 'design', $this->_design));
    }
    
    function setDesign($design_name)
    {
        global $tic;
        $new_path = $this->getModPath().$design_name.'/';
        if (file_exists($new_path.$this->_design_maintpl)) {
            $this->_design = $design_name;
            $this->_smarty->template_dir = $new_path;
            $tic->mod['Core']->set($this->getName(), 'design', $this->_design);
        }
    }
    
   function getShow()
	{
		return $this->show;
	}
    
    function onUnload()
    {
        global $tic;
        if ($this->show === false)
            return;
        if ($tic->executed_mod == '')
            return;
        if (!$tic->mod[$tic->executed_mod]->getSmarty_template())
            return;

        if ($tic->accessDenied) {
            foreach ($tic->mod['Auth']->getSmarty_vars() as $key => $value)
                $this->_smarty->assign($key, $value);
            $this->_smarty->display($tic->mod['Auth']->getSmarty_template());
        } elseif ($tic->executed_mod == 'JSON') {
            return;
        } else {
            $this->_createSmartyVars();
            $this->_smarty->display($this->_design_maintpl);
        }
    }
    
    function _createSmartyVars()
    {
        global $tic;
        $smartyvars = array();
        $modlist = $tic->modListLoaded();
        for ($n = 0; $n < count($modlist); $n++) {
            if ($modlist[$n] != $tic->executed_mod)
                $smartyvars = array_merge($smartyvars, $tic->mod[$modlist[$n]]->getSmarty_vars());
        }
        $smartyvars = array_merge($smartyvars, $tic->mod[$tic->executed_mod]->getSmarty_vars());

        foreach ($smartyvars as $var_name => $var_value)
            $this->_smarty->assign($var_name, $var_value);

        $menu = new MenuEntry("_toplevel_");
        $menu->addChild(new MenuEntry('Main', 0));
        $menu->addChild(new MenuEntry('Tools', 5));
        $menu->addChild(new MenuEntry('TIC Info', 10));
        $menu->addChild(new MenuEntry('Admin', 15));

        for ($n = 0; $n < count($modlist); $n++) {
            $tic->mod[$modlist[$n]]->createMenuEntries(&$menu);
        }
        $menu = $this->_genMenu($menu, -1, true);
        $menu[0]['first'] = true;
        $this->_smarty->assign('menu', $menu);

        $template = $tic->mod[$tic->executed_mod]->getSmarty_template();
        $file = $this->_design_path.$this->_design.'/'.$template;
        if (!is_file($file)) { $file = $this->_design_path.'default/'.$template; }

        $this->_smarty->assign('mod_template', $file);

        $this->_smarty->assign('mod_name', $tic->executed_mod);
        $this->_smarty->assign('mod_version', $tic->mod[$tic->executed_mod]->getVersion());
        $this->_smarty->assign('tic_version', $tic->getVersion());

        if (isset($_GET['debug']))
            $this->_smarty->assign('debug', true);
        else
            $this->_smarty->assign('debug', false);

        $this->_smarty->assign('user', $tic->mod['Auth']->getActiveUser());
        $this->_smarty->assign('queries', $tic->db->getQueryCounter());
        $this->_smarty->assign('queries_failed', $tic->db->getQueryFailedCounter());
        $this->_smarty->assign('dbtype', $tic->mod['ADOdb']->getDBType());
    }

    function _genMenu($entry, $level)
    {
        $menu = array();
        if ($entry->isLeaf()) {
            if ($level >= 0) {
                $tmp = array('name' => $entry->getName(),
                             'mod' => $entry->getModule(),
                             'param' => $entry->getExecParam(),
                             'level' => $level,
                             'first' => false
                         );
                array_push($menu, $tmp);
            }
        } else {
            if ($level >= 0) {
                $tmp = array('name' => $entry->getName(),
                             'mod' => $entry->getModule(),
                             'param' => $entry->getExecParam(),
                             'level' => $level,
                             'first' => false
                         );
                array_push($menu, $tmp);
            }
            foreach ($entry->getChildren() as $child) {
               $menu = array_merge($menu, $this->_genMenu($child, $level+1));
            }
        }
        return $menu;
    }

    function getDesignPath()
    {
        return $this->_design_path.$this->_design.'/';
    }

    function getDefaultDesignPath()
    {
        return $this->_design_path.'default/';
    }

    function execInstall($installdata)
    {
        if (isset($installdata['design']))
            $this->setDesign($installdata['design']);
    }
    
    function getInstallQueriesMySQL() { return array(); }
    function getInstallQueriesPostgreSQL() { return array(); }
}

?>
