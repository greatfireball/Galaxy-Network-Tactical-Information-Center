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

class Template
{
    var $_file = null;
    var $_path = null;
    var $_vars = array();

    function Template($file, $path = null)
    {
        $this->_file = $file;
        $this->_path = $path;
    }

    function setVar($key, $value)
    {
        $this->_vars[$key] = $value;
    }

    function setVars($arr)
    {
        $this->_vars = $arr;
    }

    function setPath($path)
    {
        $this->_path = $path;
    }

    function exec()
    {
        extract($this->_vars);
        include($this->_path.'/'.$this->_file.'.php');
    }
}

?>
