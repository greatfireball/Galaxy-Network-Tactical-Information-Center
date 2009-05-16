<?php
/*************************************************************************************
 *                                                                                   *
 *  T.I.C. NG - Next Generation of Tactical Information Center                       *
 *  Copyright (C) 2007  Pascal Gollor  <pascal@gollor.org>                           *
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
// Class Tools
//
//

// --------------------------------------------------------------------------------- //

class Tools extends TICModule
{
    function Tools()
    {
	parent::__construct(
	array(new Author("Pascal Gollor", "Hugch", "pascal@gollor.org")),
	"2",
	"Tools",
	"Einige n&uuml;tzliche Tools",
	array(
            "Core" => "5"
        ));
    
    }

    public function createMenuEntries($menuroot)
    {
        $tools = new MenuEntry("Tools", 2, $this->getName(), "tools");
        $tools->addChild(new MenuEntry("GN-Player", 0, $this->getName(), "playerSearch"));
        $tools->addChild(new MenuEntry("Extraktoren", 1, $this->getName(), "extraktoren"));
        $mTools = $menuroot->getChildByName('Tools');
        $mTools->addChild($tools);
    }

    public function getInstallQueriesMySQL() { return array(); }
    public function getInstallQueriesPostgreSQL() { return array(); }

    public function onExecute($menuentry)
    {
        global $tic;

        echo '<pre>';

        $adr = $tic->mod['Core']->getTICAdr();
        $this->setVar('toolsPfad', $adr."?mod=Tools&menu=".$menuentry);

        if (isset($_POST['koordsSearch']) || isset($_POST['playerSearch'])) { $this->playerSearch($_POST); }
        if (isset($_POST['kosten'])) { $this->kosten($_POST); }
        if (isset($_POST['roiden'])) { $this->roiden($_POST); }

        $this->setTemplate('tools/'.$menuentry.'.tpl');

        echo '</pre>';
    }

    private function playerSearch($daten)
    {
        global $tic;

        $player = false;
        if (isset($daten['playerSearch'])) {
            $player[0] = $tic->mod['UserMan']->getPlayerByKoords($daten['gala'], $daten['planet']);
        } elseif (isset($daten['koordsSearch'])) {
            $player = $tic->mod['UserMan']->getPlayerBySubString($daten['nick']);
        }
        $this->setVar('player', $player);
    }

    private function kosten($daten)
    {
        global $tic;

        foreach ($daten as $key => $value) { $daten[$key] = trim($value); }
        if (!is_numeric($daten['aktExen']) || !is_numeric($daten['zuExen'])) {
            $tic->info($this, "Es d&uuml;rfen nur Zahlen eingegeben werden.");
            return false;
        }

        $aktKosten = $daten['aktExen'] * 65;
        $zuKosten = $aktKosten + ($daten['zuExen'] - 1) * 65;
        $gKosten = ($daten['zuExen'] / 2) * ($aktKosten + $zuKosten);
        $gKosten = number_format($gKosten, 0, ',', ' ');

        $this->setVar('gKosten', $gKosten);
    }

    // evenuell in KampfSimu umlagern
    private function roiden($daten)
    {
        global $tic;

        foreach ($daten as $key => $value) { $daten[$key] = trim($value); }
        if (!is_numeric($daten['ticks']) || !is_numeric($daten['ke']) || !is_numeric($daten['me'])) {
            $tic->info($this, "Es d&uuml;rfen nur Zahlen eingegeben werden.");
            return false;
        }

        $ticks[0] = array('me' => $daten['me'], 'ke' => $daten['ke'], 'gME' => 0, 'gKE' => 0);
        $gesamt = array('gME' => 0, 'gKE' => 0);
        for ($i0 = 0; $i0 <= $daten['ticks']; $i0++) {
            if ($i0 != 0) {
                $ticks[$i0]['gME'] = floor($ticks[$i0 - 1]['me'] / 10);
                $ticks[$i0]['gKE'] = floor($ticks[$i0 - 1]['ke'] / 10);
                $ticks[$i0]['me'] = $ticks[$i0 - 1]['me'] - $ticks[$i0]['gME'];
                $ticks[$i0]['ke'] = $ticks[$i0 - 1]['ke'] - $ticks[$i0]['gKE'];
                $gesamt['gME'] = $gesamt['gME'] + $ticks[$i0]['gME'];
                $gesamt['gKE'] = $gesamt['gKE'] + $ticks[$i0]['gKE'];
            }
            foreach ($ticks[$i0] as $key => $value) { $ticks[$i0][$key] = number_format($value, 0, ',', ' '); }
        }
        $gesamt['exen'] = $gesamt['gME'] + $gesamt['gKE'];
        $this->setVar('ticks', $ticks);
        $this->setVar('gesamt', $gesamt);
    }
}

?>
