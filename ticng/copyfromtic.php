<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006,2007 Andreas Hemel <dai.shan@gmx.net>                         *
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

error_reporting(E_ALL);

$TIC1_DSN = 'mysql://daishan@localhost/tic';
$NAME = 'copyfromtic.php';

require_once('ADOdb/adodb/adodb.inc.php');
include_once('ADOdb/adodb/adodb-error.inc.php');
include_once('ADOdb/adodb/lang/adodb-de.inc.php');


define('TIC_ROOT_PATH', dirname(__FILE__).'/');
require(TIC_ROOT_PATH.'Core/ModuleManager.class.php');
global $tic;
$tic = new ModuleManager(TIC_ROOT_PATH);
$tic->disableSecurity = true;
$tic->modsInitialize(false, false, true);
$tic->db->Execute($NAME, "SET NAMES 'LATIN1'");

$tic1 = NewADOConnection($TIC1_DSN);
global $tic1;

if (!isset($_GET['flotten'])) {
    $tic->db->Execute($NAME, "DELETE FROM news_read");
    $tic->db->Execute($NAME, "DELETE FROM news");
    $tic->db->Execute($NAME, "DELETE FROM TICUser WHERE gala != 0");
    $tic->db->Execute($NAME, "DELETE FROM GNPlayer WHERE gala != 0");
    $tic->db->Execute($NAME, "DELETE FROM Galaxie WHERE gala != 0");
    $tic->db->Execute($NAME, "DELETE FROM Allianz");
    $tic->db->Execute($NAME, "DELETE FROM Meta");
}
$tic->db->Execute($NAME, "DELETE FROM Flotten");
//$tic1->Execute("SET NAMES 'UTF8'");

echo '<html><head><title>copyfromtic</title></head><body><pre>';
if (!isset($_GET['flotten'])) {
    gnplayer();
    ticuser();
    allianz();
    meta();
}
flottenbewegungen();
echo '</pre></body></html>';

$tic->modsUnload();

function gnplayer()
{
    //gn4gnuser -> GNPlayer
    global $tic, $tic1;
    echo "gn4gnuser -&gt; GNPlayer\n";
    flush();
    $qry = "SELECT gala, planet, name FROM gn4gnuser;";
    $rs = $tic1->Execute($qry);
    for ($i = 0; !$rs->EOF; $i++) {
        if ($i % 200 == 0 && $i > 0) {
            echo "  $i\n";
            flush();
            //ob_flush();
        }
        $player = new GNPlayer($rs->fields[0], $rs->fields[1], $rs->fields[2]);
        $player->create();
        $rs->MoveNext();
    }
    echo "  $i\n\n";
    flush();
    //ob_flush();
}

function ticuser()
{
    //gn4accounts -> TICUser
    global $tic, $tic1;
    echo "gn4acounts -&gt; TICUser\n";
    flush();
    $qry = "SELECT name, passwort, galaxie, planet, handy, messangerid, authnick, svs, sbs, scantyp, spy ".
           "FROM gn4accounts WHERE galaxie > 0";
    $rs = $tic1->Execute($qry);
    for ($i = 0; !$rs->EOF; $i++) {
        if ($i % 25 == 0 && $i > 0) {
            echo "  $i\n";
            //ob_flush();
            flush();
        }
        $user = new TICUser($rs->fields[2], $rs->fields[3], $rs->fields[0]);
        if (!$user->create($rs->fields[1], null)) {
            echo "    ERROR: couldn't create user: {$rs->fields[1]}:{$rs->fields[2]} {$rs->fields[0]}\n";
        } else {
            $user->setTelnr(substr($rs->fields[4], 0, 20));
            $user->setICQ(substr(trim(preg_replace('/\s*icq:{0,1}\s*/', '', $rs->fields[5])), 0, 12));
            $user->setAuthnick($rs->fields[6]);
            $user->setSvs($rs->fields[7]);
            $user->setElokas($rs->fields[8]);
            switch ($rs->fields[9]) {
                case 0: $scantyp = 'unbekannt';             break;
                case 1: $scantyp = 'erw. Militaerscan';     break;
                case 2: $scantyp = 'erw. Nachrichtenscan';  break;
            }
            $user->setScantyp($scantyp);
            $user->setBanned($rs->fields[10]);
        }
        $rs->MoveNext();
    }
    echo "  $i\n\n";
    //ob_flush();
    flush();
}

function allianz()
{
    //gn4allianzen -> Allianz
    global $tic, $tic1;
    echo "gn4allianzen -&gt; Allianz\n";
    flush();
    $qry = "SELECT name, tag, id FROM gn4allianzen;";
    $rs = $tic1->Execute($qry);
    for ($i = 0; !$rs->EOF; $i++) {
        if ($i % 10 == 0 && $i > 0) {
            echo "  $i\n";
            //ob_flush();
            flush();
        }
        $alli = new Allianz($rs->fields[0], substr($rs->fields[1], 0, 20));
        if ($alli->create(true)) {
            $qry2 = "SELECT galaxie FROM gn4accounts WHERE allianz = '{$rs->fields[2]}' AND galaxie > 0 GROUP BY galaxie;";
            $rs2 = $tic1->Execute($qry2);
            for (; !$rs2->EOF; $rs2->MoveNext()) {
                //echo "    galas:{$rs2->fields[0]}\n";
                $gala = new Galaxie($rs2->fields[0]);
                $gala->create();
                if (!$gala->setAllianz($alli->getId()))
                    echo "    ERROR: couldn't set Allianz of {$gala->toString()}\n";
            }
        } else {
            echo "    ERROR: couldn't create Allianz: {$rs->fields[0]}\n";
        }
        $rs->MoveNext();
    }
    echo "  $i\n\n";
    //ob_flush();
    flush();
}

function meta()
{
    //gn4meta -> Meta
    global $tic, $tic1;
    echo "gn4meta -&gt; Meta\n";
    flush();
    $qry = "SELECT name, id FROM gn4meta;";
    $rs = $tic1->Execute($qry);
    for ($i = 0; !$rs->EOF; $i++) {
        $meta = new Meta($rs->fields[0], substr($rs->fields[0], 0, 20));
        if ($meta->create(true)) {
            $qry2 = "SELECT name FROM gn4allianzen WHERE ticid = '{$rs->fields[1]}' GROUP BY name;";
            $rs2 = $tic1->Execute($qry2);
            for (; !$rs2->EOF; $rs2->MoveNext()) {
                $alli = $tic->mod['UserMan']->getAllianzByName($rs2->fields[0]);
                $alli->setMeta($meta->getId());
            }
        } else {
            echo "    ERROR: couldn't create Meta: {$rs->fields[0]}\n";
        }
        $rs->MoveNext();
    }
    echo "  $i\n\n";
    //ob_flush();
    flush();
}

function flottenbewegungen()
{
    global $tic, $tic1;
    echo "gn4flottenbewegungen -&gt; Flotten\n";
    flush();
    $qry = "SELECT modus, angreifer_galaxie, angreifer_planet, verteidiger_galaxie, verteidiger_planet, ".
        "eta, flottennr, save ".
        "FROM gn4flottenbewegungen";
    $rs = $tic1->Execute($qry);
    for ($i = 0; !$rs->EOF; $rs->MoveNext(), $i++) {
        $angriff = null;
        $rueckflug = null;
        $save = null;
        switch ((int) $rs->fields[0]) {
        case 1:
            $rueckflug = false;
            $angriff = true;
            break;
        case 2:
            $rueckflug = false;
            $angriff = false;
            break;
        case 3:
            $rueckflug = true;
            $angriff = true;
            break;
        case 4:
            $rueckflug = true;
            $angriff = false;
            break;
        default:
            print_r($rs->fields[0]);
            die('unbekannter modus');
            break;
        }
        $start_gala = $rs->fields[1];
        $start_planet = $rs->fields[2];
        $ziel_gala = $rs->fields[3];
        $ziel_planet = $rs->fields[4];
        $eta = $rs->fields[5];
        $flotte = $rs->fields[6];
        $save = ($rs->fields[7] == 1) ? false : true;
        $flugdauer = false;
        $bleibedauer = false;

        $fleet = new Flottenbewegung($start_gala,
                                     $start_planet,
                                     $flotte,
                                     $ziel_gala,
                                     $ziel_planet,
                                     $angriff,
                                     $rueckflug,
                                     $flugdauer,
                                     $bleibedauer,
                                     $eta,
                                     $save);
        $fleet->save();
    }
    echo "  $i\n\n";
    //ob_flush();
    flush();

}

?>
