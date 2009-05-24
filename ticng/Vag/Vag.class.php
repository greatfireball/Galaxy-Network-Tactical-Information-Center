<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2006,2007  Pascal Gollor  <pascal@gollor.org>                      *
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
// Class Vag
//
//

// --------------------------------------------------------------------------------- //

class Vag extends TICModule
{
    function Vag()
    {
	parent::__construct(
	array(new Author("Andreas Hemel", "daishan", "dai.shan@gmx.net"),
	new Author("AlbertLast","AlbertLast","#tic-progger@quakenet")),
	"10",
	"Vag",
	"Berechnen des Verlustausgleiches",
	array(
            "Core" => "10",
            "Design" => "10",
            "Konst" => "10"
          ));
    
    }

    function createMenuEntries($menuroot)
    {
        $tools = $menuroot->getChildByName('Tools');
        $tools->addChild(new MenuEntry("Vag-Rechner", 1, $this->getName(), "Vag"));
    }

    function onPostLoad()
    {
    }

    function onExecute($menuentry)
    {
        global $tic;

        $schiffeGeschuetze = $tic->mod['Konst']->getSchiffGeschNamen();
        $schiffKosten = $tic->mod['Konst']->getSchiffKosten();
        $this->setVar('vag', $_SERVER['REQUEST_URI']);
        if (isset($_GET['bomber'])) {
            $this->_count();
        } else {
            foreach ($schiffeGeschuetze as $key => $value) {
                $ausgabe[$key] = array(
                    $value[1].' - '.$value[2],
                    $value[0],
                    "0",
                    "0",
                    "0",
                    number_format($schiffKosten[$key]['met'], 0, ',', ' ').'m - '.number_format($schiffKosten[$key]['kris'], 0, ',', ' ').'k'
                );
            }
            $verluste = array(
                'met' => array('all' => "0", 'half' => "0"),
                'kris' => array('all' => "0", 'half' => "0")
            );
            $this->setVar('vagAusgabe', $ausgabe);
            $this->setVar('verluste', $verluste);
        }
        $this->setTemplate('vag.tpl');
    }

    function getInstallQueriesMySQL() { return array(); }
    function getInstallQueriesPostgreSQL() { return array(); }

    function _count()
    {
        global $tic;

        $colors = $tic->mod['Konst']->getDefaultIRCColors();
        $schiffeGeschuetze = $tic->mod['Konst']->getSchiffGeschNamen();
        $schiffKosten = $tic->mod['Konst']->getSchiffKosten();

        $verluste['met']['all'] = 0;
        $verluste['kris']['all'] = 0;
        $ircAuswertung = '';
        $links = false;

        for ($i0 = 0; $i0 < count($schiffeGeschuetze); $i0++) {
            if ($_GET[$schiffeGeschuetze[$i0][0]] > 0) { $links = true; }
            if ($_GET[$schiffeGeschuetze[$i0][0]] == 1) {
                $schiffeGeschuetze = $tic->mod['Konst']->getSchiffGeschNamen(0);
            } else {
                $schiffeGeschuetze = $tic->mod['Konst']->getSchiffGeschNamen(1);
            }
            $ver['met'] = $_GET[$schiffeGeschuetze[$i0][0]] * $schiffKosten[$i0]['met'];
            $ver['kris'] = $_GET[$schiffeGeschuetze[$i0][0]] * $schiffKosten[$i0]['kris'];
            $ver['all'] = $ver['met'] + $ver['kris'];
            $ausgabe[$i0] = array(
                $schiffeGeschuetze[$i0][1].' - '.$schiffeGeschuetze[$i0][2],
                $schiffeGeschuetze[$i0][0],
                $_GET[$schiffeGeschuetze[$i0][0]],
                number_format($ver['met'], 0, ',', ' '),
                number_format($ver['kris'], 0, ',', ' '),
                number_format($schiffKosten[$i0]['met'], 0, ',', ' ').'m - '.number_format($schiffKosten[$i0]['kris'], 0, ',', ' ').'k'
            );
            if ($ausgabe[$i0][2] != 0) {
                $ircAuswertung .= $colors[3].$ausgabe[$i0][2].' '.$colors[2].$schiffeGeschuetze[$i0][1].' - ';
                $ircAuswertung .= $colors[3].$ausgabe[$i0][3].$colors[2].' Metall, ';
                $ircAuswertung .= $colors[3].$ausgabe[$i0][4].$colors[2].' Kristall, ';
                $ircAuswertung .= $colors[3].number_format($ver['all'], 0, ',', ' ').$colors[2].' Resis<br>';
            }
            $verluste['met']['all'] = $verluste['met']['all'] + $ver['met'];
            $verluste['kris']['all'] = $verluste['kris']['all'] + $ver['kris'];
        }

        $verluste['met']['half'] = number_format($verluste['met']['all'] / 2, 0, ',', ' ');
        $verluste['met']['all'] = number_format($verluste['met']['all'], 0, ',', ' ');
        $verluste['kris']['half'] = number_format($verluste['kris']['all'] / 2, 0, ',', ' ');
        $verluste['kris']['all'] = number_format($verluste['kris']['all'], 0, ',', ' ');

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $adr = 'http://'.$_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $adr = 'http://'.$_SERVER['HTTP_HOST'];
        }
        $adr .= $_SERVER['SCRIPT_NAME'];
        $adr = $adr."?".$_SERVER['QUERY_STRING'];

        $ircAuswertung .= $colors[0].'Gesammte Verluste: '.$colors[1].$verluste['met']['all'].$colors[0].' Metall, ';
        $ircAuswertung .= $colors[1].$verluste['kris']['all'].$colors[0].' Kristall<br>';
        $ircAuswertung .= $colors[0].'Verlustausgleich: '.$colors[1].$verluste['met']['half'].$colors[0].' Metall, ';
        $ircAuswertung .= $colors[1].$verluste['kris']['half'].$colors[0].' Kristall';

        $popup = array(
            'text' => '<html><head><title>IRC-Auswertung</title></head><body><pre>'.$ircAuswertung.'</pre></body></html>',
            'width' => 500,
            'height' => 100
        );

        $this->setVar('vagAusgabe', $ausgabe);
        $this->setVar('verluste', $verluste);
        if ($links) {
            $this->setVar('vagLink', $adr);
            $this->setVar('IRCPopup', $popup);
        }
    }

    function createLink($verluste)
    {
        global $tic;

        $schiffeGeschuetze = $tic->mod['Konst']->getSchiffGeschNamen(1);
        $schiffKosten = $tic->mod['Konst']->getSchiffKosten();

        $i0 = 0;
        $kosten['gesammt']['met'] = 0;
        $kosten['gesammt']['kris'] = 0;
        $returnStr = "";
        foreach ($verluste as $key => $value) {
            $kosten[$i0]['met'] = $value * $schiffKosten[$i0]['met'];
            $kosten[$i0]['kris'] = $value * $schiffKosten[$i0]['kris'];
            $kosten['gesammt']['met'] = $kosten['gesammt']['met'] + $kosten[$i0]['met'];
            $kosten['gesammt']['kris'] = $kosten['gesammt']['kris'] + $kosten[$i0]['kris'];
            $returnStr .= "&".$schiffeGeschuetze[$i0][0]."=".$value;
            $i0++;
        }
        $adr = $tic->mod['Core']->getTICAdr();
        return $adr."?mod=Vag".$returnStr;
    }
}

?>
