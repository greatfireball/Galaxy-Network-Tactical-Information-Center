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
// Class ModuleManager
//
//

// Ein Modul muß in einem seprataten Ordner liegen, welcher mit einem Großbuchstaben
// beginnt. In ihm muß eine gleichnamige *.class.php Datei liegen, welche eine Klasse
// beinhaltet die ebenso heißt.

require('datatypes.php');


// --------------------------------------------------------------------------------- //


class ModuleManager
{
    var $server_root = '';

    var $mod = array();
    var $_modLoaded = array();
    var $_modsLoadedSorted = array();

    var $executed_mod = '';

    var $db = false;

    var $accessDenied = false;

    var $_error = false;
    var $_info = false;

    // deaktiviert einige Sicherheitsfunktionen die bei der Installation stören
    var $disableSecurity = false;

    function getVersion()
    {
        return '6';
    }

    function getName()
    {
        return "ModuleManager";
    }

    function ModuleManager($tic_path)
    // $tic_path erwartet einen Pfad mit abschließendem /
    {
        $this->server_root = $tic_path;
    }

    function modsInitialize($installdata = false, $execUpdate = false, $noAuth = false)
    {
        $this->_modAdd('Core');
        $this->_modLoad('Core');

        $modlist = $this->_modDirList($this->server_root);
        for ($n = 0; $n < count($modlist); $n++)
            $this->_modAdd($modlist[$n]);

        $sortedMods = $this->_calcDependencyOrder($modlist);

        //FIXME update function!
        if ($installdata)
            $this->_installMods($sortedMods, $installdata);
        foreach ($sortedMods as $mod){
            $this->_modLoad($mod);
	}

        if ($noAuth && $this->_modLoaded['Auth']) {
            $this->mod['Auth']->DISABLE_AUTH = true;
        }

        $this->_modsLoadedSorted = $sortedMods;

        foreach ($sortedMods as $mod)
            $this->mod[$mod]->onPostLoad();
    }

    function _calcDependencyOrder($modlist)
    {
        $sorted = array();
        $unsorted = $modlist;

        while ($unsorted != array()) {
            $anythingAdded = false;
            //iterate over all not sorted modules
            foreach ($unsorted as $mod) {
                $deps = $this->mod[$mod]->getDependencies();
                $allDepsOK = true;
                //check all dependencies of a module
                foreach ($deps as $dep_name => $dep_version) {
                    if (in_array($dep_name, $sorted)) {
                        if ($this->mod[$dep_name]->getVersion() < $dep_version)
                            die("Module '$mod' requires '$dep_name' version $dep_version! (version ".$this->mod[$dep_name]->getVersion()." available)");
                    } else {
                        if (!in_array($dep_name, $unsorted))
                            die("Module '$mod' requires '$dep_name' which does not exist!");
                        $allDepsOK = false;
                    }
                }
                //move module to sorted list if all dependencies are fullfilled
                if ($allDepsOK) {
                    $anythingAdded = true;
                    array_push($sorted, $mod);
                    $unsorted = array_diff($unsorted, array($mod));
                }
            }
            if (!$anythingAdded) {
                print_r($unsorted);
                die("Dependency cycle, can't resolve all modules!");
            }
        }
        return $sorted;
    }

    function modExecute($mod_name, $menuentry)
    {
        if ($this->accessDenied) {
            $this->mod['Auth']->onExecute('access_denied');
            $this->executed_mod = 'Auth';
            return;
        }

        $mods = $this->modListLoaded();
        for ($n = 0; $n < count($mods); $n++) {
            $this->mod[$mods[$n]]->onPreExecute($mod_name, $menuentry);
        }

        $this->mod[$mod_name]->onExecute($menuentry);
        $this->executed_mod = $mod_name;

        for ($n = 0; $n < count($mods); $n++)
            $this->mod[$mods[$n]]->onPostExecute($mod_name, $menuentry);
    }

    function modsUnload()
    {
        $mods = $this->modListLoaded();
        for ($n = count($mods) - 1; $n >= 0; $n--)
            $this->mod[$mods[$n]]->onUnload();
    }

    function modExists($mod_name)
    {
        if (isset($this->mod[$mod_name]))
            return true;
        else
            return false;
    }

    function modIsLoaded($mod_name)
    {
        if ($this->modExists($mod_name))
            return $this->_modLoaded[$mod_name];
        else
            false;
    }

    function modListLoaded()
    {
        return $this->_modsLoadedSorted;
    }

    function _modDirList($mod_path)
    {
        $modlist = array();
        if ($dp = opendir($mod_path)) {
            while (($mod = readdir($dp)) !== false) {
                if (is_dir($mod_path.$mod) && $mod{0} == strtoupper($mod{0}) && $mod != '.' && $mod != '..' && $mod != '_darcs') {
                    $modlist[] = $mod;
                }
            }
        }
        return $modlist;
    }

    function _modAdd($mod_name)
    {
        if (!isset($this->mod[$mod_name])) {
            require($this->server_root.$mod_name.'/'.$mod_name.'.class.php');
            $this->mod[$mod_name] = new $mod_name;
            $this->_modLoaded[$mod_name] = false;
        }
    }

    function _modLoad($mod_name)
    {
        $this->mod[$mod_name]->onLoad();
        $this->_modLoaded[$mod_name] = true;
    }

    function _installMods($sortedMods, $installdata)
    {
        echo "<pre>\n";
        echo "T.I.C. NG (".$this->getVersion().") installation started...\n";
        if (!isset($installdata['dbtype']))
            die("ERROR: Database type not specified!");
        $dbfunction = 'getInstallQueries'.$installdata['dbtype'];
        echo "\nChecking modules for '".$installdata['dbtype']."' compatibility\n";
        foreach ($sortedMods as $mod) {
            echo "checking '$mod'...\t";
            if (method_exists($this->mod[$mod], $dbfunction)) {
                echo "ok\n";
                $sqlqueries[$mod] = $this->mod[$mod]->$dbfunction();
            } else {
                echo "failed\n";
                die("ERROR: Module '$mod' is not ".$installdata['dbtype']." compatible!");
            }
        }
        echo "All modules are compatible.\n";

        echo "\nInitializing database...\n";
        $dbcons = 0;
        foreach ($sortedMods as $mod) {
            if (method_exists($this->mod[$mod], 'execInstallDBStart')) {
                echo "using '$mod'...\t\t";
                $this->mod[$mod]->execInstallDBStart($installdata[$mod]);
                $dbcons++;
                echo "ok\n";
            }
        }
        if ($dbcons == 0)
            die("ERROR: No databases found!");
        $this->db->_DISABLE_LOGGING = true;
        foreach ($sortedMods as $mod) {
            if (count($sqlqueries[$mod]) > 0) {
                echo "creating '$mod' tables...\t";
                for ($n = 0; $n < count($sqlqueries[$mod]); $n++) {
                    if (!$this->db->Execute($mod, $sqlqueries[$mod][$n])) {
                        //ignore the error if we just tried to DROP a table, that probably doesn't exist anyway
                        if (strncasecmp($sqlqueries[$mod][$n], 'DROP', 4) == 0)
                            continue;
                        if (strncasecmp($sqlqueries[$mod][$n], 'INSERT', 5) == 0)
                            continue;
                            echo 'ende?';
                            var_dump($sqlqueries[$mod][$n]);
                        die("failed\n");
                    }
                }
                echo "ok\n";
                // after creating the ADOdb tables we can start logging
                if ($mod == 'ADOdb')
                    $this->db->_DISABLE_LOGGING = false;
            }
        }
        $this->db->_DISABLE_LOGGING = false;
        echo "Database ready.\n";

        echo "\nExecuting install processes...\n";
        foreach ($sortedMods as $mod) {
            if (!isset($installdata[$mod]))
                $installdata[$mod] = array();
            echo "installing '$mod'...\t";
            $this->mod[$mod]->execInstall($installdata[$mod]);
            echo "ok\n";
        }
        echo "All done.\n";
        echo "\nInstallation ended.\n";
        echo "</pre>\n";
    }

    function error($modulName, $text)
    {
        if (trim($modulName) == "" || trim($text) == "") { return false; }
        if ($this->_error === false) { $this->_error = array(); }

        $user = $this->mod['Auth']->getActiveUser();
        $error = "Modul ".$modulName.": ".$text;
        array_push($this->_error, $error);

        $this->mod['Logging']->log(ERROR, $user, $error);
        $this->mod['Core']->setVar('ticngErrors', $this->_error);

        return true;
    }

    function info($modulName, $text)
    {
        if (trim($modulName) == "" || trim($text) == "") { return false; }
        if ($this->_info === false) { $this->_info = array(); }

        $info = "Modul ".$modulName.": ".$text;
        array_push($this->_info, $info);

        $this->mod['Core']->setVar('ticngInfos', $this->_info);
    }

    function isError()
    {
        if ($this->_error === false) { return false; } else { return true; }
    }
}

?>
