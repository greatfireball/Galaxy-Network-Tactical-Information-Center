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

class JSON extends TICModule
{
    private $callbacks = array();

   function JSON()
   {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"JSON",
	"AJAX Backend für JSON output",
	array(
            "Core" => "10",
            "Taktik" => "10"
        ));
   
   }
    public function onLoad()
    {
    }

    public function onExecute($menuentry)
    {
        global $tic;
        //header('Content-type: application/json; charset="utf-8"');
        header('Content-type: text/plain; charset="utf-8"');
        $data = 'ajax callback not registered';
        foreach ($this->callbacks as $callback) {
            $func = $callback['func'];
            if ($menuentry == $callback['ajaxcall'])
                $data = $tic->mod[$callback['module']]->$func();
        }
        echo json_encode($data);
    }

    public function registerAjaxCall($ajaxCall, $module, $func) {
        $this->callbacks[] = array('ajaxcall' => $ajaxCall, 'module' => $module, 'func' => $func);
    }

    public function getInstallQueriesMySQL() { return array(); }
    public function getInstallQueriesPostgreSQL() { return array(); }
}
