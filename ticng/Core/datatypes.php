<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006  Tobias Sarnowski  <sarnowski@cosmocode.de>                   *
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
// Datentypen
//
//

// --------------------------------------------------------------------------------- //



abstract class TICModule
// --------------------------------------------------------------------------------- //
{
    private $smarty_template = '';
    private $smarty_vars = array();
    private $autor = array();
    private $version="4711"; //Version des Module
    private $desc="4711"; // Modul beschreibung
    private $name="4711"; // Module name
    private $depend= array("4711");    // Gibt einen Array zurück mit den Namen und Version der Module die vor dem eigenem geladen
									// werden müssen bevor das Modul geladen wird und installiert bevor das Modul installiert wird.
									// Format:  (ModuleName => ModuleVersion)
 /*   function TICModule()
    {
	$this->autor=array();
	$this->version="0";
	$this->desc="incorrectly used as plain module";
	$this->name="Datatype";
	$this->depend=array("Core" => "1");
	
    }*/
    function TICModule($autor=array(),$ver="1",$name="Datatype",$desc="incorrectly used as plain module",$depend=array("Core" => "1"))
    {
	$this->autor=$autor;
	$this->version=$ver;
	$this->desc=$desc;
	$this->name=$name;
	$this->depend=$depend;
    }

    function getAuthors()
    // Gibt einen Array aller Authoren zurück (im Datentyp class Author)
    {
        return $this->autor;
    }

    function getDependencies()
    // Gibt einen Array zurück mit den Namen und Version der Module die vor dem eigenem geladen
    // werden müssen bevor das Modul geladen wird und installiert bevor das Modul installiert wird.
    // Format:  (ModuleName => ModuleVersion)
    {
        return $this->depend;
    }
    
    function getDescription()
    // Text, welcher das Modul beschreibt.
    {
        return $this->desc;
    }

    function getName()
    // Gibt den Namen des Moduls zurück.
    {
        return $this->name;
    }
    
    function __toString()
    {
	return $this->getName();
    }
    
    function getVersion()
    // Gibt die Version des Moduls zurück
    // Format:  major.minor.patch
    {
        return (int) $this->version;
    }

    function getSmarty_template()
    {
    return $this->smarty_template;
    }

    function getSmarty_vars()
    {
	return $this->smarty_vars;
    }

    function createMenuEntries($menuroot)
    {
        return false;
    }

    function isDBMod()
    // Gibt an, ob das Modul $tic->db initialisiert
    {
        return false;
    }

    function onLoad()
    // Wird bei jedem T.I.C. Seitenaufbau aufgerufen, alle Abhängigkeiten
    // sind schon geladen
    {
        return;
    }
    
    function onPostLoad()
    // Wird ausgeführt nachdem alle Module geladen sind.
    {
        return;
    }
    
    function onUnload()
    // Letzte mögliche Aktion
    {
        return;
    }
    
    function onPreExecute($mod_name, $menuentry)
    // Wird vor dem Ausführen eines Moduls ausgeführt
    {
        return;
    }
    
    function onExecute($menuentry)
    // Wird nur aufgerufen wenn das Modul explizit geladen ist, sprich vom
    // User ausgewählt wurde  (wrapper.php?mod=MEINMODUL)
    {
        die('Non-executable class executed!');
    }
    
    function onPostExecute($mod_name, $menuentry)
    // Wird nach dem Ausführen eines Moduls ausgeführt
    {
        return;
    }
    
    function onTick($tick)
    // Wird bei jedem Tick einmal aufgerufen.
    {
    	return;
    }
    
    function execInstall($installdata)
    {
    	return;
    }

    function execUpdate($installdata)
    {
    	return;
    }
    
    function getModPath()
    {
        global $tic;
        return $tic->server_root.$this->getName().'/';
    }
    
    function setTemplate($filename)
    {
        $this->smarty_template = $filename;
    }
    
    function setVar($var_name, $var_value)
    {
        $this->smarty_vars[$var_name] = $var_value;
    }
}


// --------------------------------------------------------------------------------- //
class MenuEntry 
{
    private $name; //angezeigter Name
    private $execParam; //onExecute() uebergebener Parameter
    private $module; //Modulname
    private $children; //Array mit Unter-MenuEntries
    private $priority; //priorität des eintrags, legt die reihenfolge fest

    function __construct($name, $priority = 0, $module = '', $execParam = '')
    {
        $this->name = $name;
        $this->execParam = $execParam;
        $this->module = $module;
        $this->children = array();
        $this->priority = $priority;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getExecParam()
    {
        return $this->execParam;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getChildByName($name)
    {
        if (array_key_exists($name, $this->children))
            return $this->children[$name];
        else
            return false;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function addChild($entry)
    {
        if (array_key_exists($entry->name, $this->children))
            return false;
        
        $this->children[$entry->name] = $entry;
        uasort($this->children, array('MenuEntry', 'cmp'));
        return true;
    }

    public function isLeaf()
    {
        return ($this->children == array());
    }

    public function cmp($a, $b) {
        return $a->priority <= $b->priority ? -1 : 1;
    }
}

class Author
// --------------------------------------------------------------------------------- //
{
    private $Name = '';
    private $Nick = '';
    private $Mail = '';
    
    function Author($myname, $mynick, $mymail)
    {
        $this->Name = $myname;
        $this->Nick = $mynick;
        $this->Mail = $mymail;
    }
    
	function getName()
	{
	return $this->Name;
	}
	
	function getNick()
	{
	return $this->Nick;
	}
	
	function getMail()
	{
	return $this->Mail;
	}
    
}

?>
