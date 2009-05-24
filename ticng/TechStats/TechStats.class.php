<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006  Tobias Sarnowski  <sarnowski@new-thoughts.org>               *
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
// Class TechStats
//
//

// --------------------------------------------------------------------------------- //


class TechStats extends TICModule
{
    function TechStats()
    {
	parent::__construct(
	array(new Author("Tobias Sarnowski", "NataS", "sarnowski@new-thoughts.org"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"TechStats",
	"Macht eine Zusammenfassung aller interessanten technischen Daten",
	array(
        	"Core" => "10",
        	"Ticker" => "10",
        	"ADOdb" => "10",
        	"Design" => "10"
        ));
    
    }

    function createMenuEntries($menuroot)
    {
        $info = new MenuEntry("TIC Info", 8);
        $info->addChild(new MenuEntry("Technische Daten", 0, $this->getName(), "techstats"));
    }
    
    function onExecute($menuentry)
    {
        global $tic;
        $modlist = $tic->modListLoaded();
        $mods = array();
        for ($n = 0; $n < count($modlist); $n++) {
            $mods[$n]['name'] = $modlist[$n];
            $mods[$n]['version'] = $tic->mod[$modlist[$n]]->getVersion();
            $mods[$n]['desc'] = $tic->mod[$modlist[$n]]->getDescription();
            $authors = $tic->mod[$modlist[$n]]->getAuthors();
            $mods[$n]['authors'] = $authors;
        }
        $this->setVar("module", $mods);
        
        $this->setTemplate("techstats.tpl");
    }
    

    function getInstallQueriesMySQL() { return array(); }
	function getInstallQueriesPostgreSQL() { return array(); }
}

?>
