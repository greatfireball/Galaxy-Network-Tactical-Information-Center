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
// wrapper.php
// Führt das T.I.C. aus.
//
//
//

// --------------------------------------------------------------------------------- //


// wir wollen sauberen code :)
error_reporting(E_ALL);

// T.I.C. NG Konstanten
define('TIC_ROOT_PATH', dirname(__FILE__).'/');
define('TIC_DEFAULT_MODULE', 'News');

// Core Modul laden und initialisieren
require(TIC_ROOT_PATH.'Core/ModuleManager.class.php');

global $tic;
$tic = new ModuleManager(TIC_ROOT_PATH);

$tic->modsInitialize();
$tic->db->Execute('wrapper.php', "SET NAMES 'UTF8'");
if (isset($_GET['extern'])) { $tic->mod['Design']->show = false; }

if (!isset($_GET['mod']))
    $_GET['mod'] = '';
$active_mod = $_GET['mod'];
if ($active_mod == '')
    $active_mod = TIC_DEFAULT_MODULE;

if (isset($_GET['menu']))
    $menuentry = $_GET['menu'];
else
    $menuentry = '';

if ($tic->modIsLoaded($active_mod))
    $tic->modExecute($active_mod, $menuentry);
elseif ($tic->modExists($active_mod))
    die("Module '$active_mod' not loaded!");
else
    die("Module '$active_mod' does not exist!");

if ($tic->mod['Design']->getShow() === false) {
    switch ($_GET['extern']) {
        case 'scans':
            // Ausgaben ohne smarty fuer extenr oder so
            // Villeihct extra script bauen, hatte ich aber keine Lust zu
            echo $tic->mod['Scan']->getScansForExtension($_GET['gala'], $_GET['planeten']);
            break;
        case 'vag':
            echo $_GET['id'].':||:'.$tic->mod['Parser']->vagLink(urldecode($_GET['link']));
            break;
    }
}

$tic->modsUnload();

?>
