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
// Class Core
//
//

// --------------------------------------------------------------------------------- //


class Core extends TICModule
{
    var $_fp;
    var $_config = array();
    var $_configfile;
    
    function Core()
    {
	parent::__construct(
	array(new Author("Tobias Sarnowski", "NataS", "sarnowski@new-thoughts.org"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"Core",
	"Verwaltet alle T.I.C. NG Module.",
	array());
    }
    
    
    function onLoad()
    {
        $this->_configfile = $this->getModPath().'conf.php';
        $fp = fopen($this->_configfile, 'r');
        if (!$fp)
            die("Can't read configfile: ".$this->_configfile);
        while (!feof($fp)) {
            $line = trim(fgets($fp, 1024));
            $confline = explode(':|:', $line);
            $confline[0] = trim($confline[0]);
            if (count($confline) > 2)
                $this->_config[$confline[0]][$confline[1]] = $confline[2];
        }
        fclose($fp);
    }
    
    function onUnload()
    {
        $fp = fopen($this->_configfile, 'w');
        if (!$fp)
            die("Can't write configfile: ".$this->_configfile);
        fwrite($fp, "<?/*\n");
        foreach($this->_config as $conf_modname => $mod_config) {
            foreach($mod_config as $mod_key => $mod_value) {
                fwrite($fp, "$conf_modname:|:$mod_key:|:$mod_value\n");
            }
        }
        fwrite($fp, "*/?>\n");
        fclose($fp);
    }
    
    function set($mod_name, $key, $value)
    {
        $this->_config[$mod_name][$key] = $value;
    }

    function get($mod_name, $key, $value = false)
    {
        if (isset($this->_config[$mod_name][$key]))
            return $this->_config[$mod_name][$key];
        else
            return $value;
    }
    
    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    function getTICAdr()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $adr = 'http://'.$_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $adr = 'http://'.$_SERVER['HTTP_HOST'];
        }
        return $adr.$_SERVER['SCRIPT_NAME'];
    }

    function getInstallQueriesMySQL() { return array(); }
    function getInstallQueriesPostgreSQL() { return array(); }
}

?>
