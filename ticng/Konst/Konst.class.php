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
// Class Konst
//
//

// --------------------------------------------------------------------------------- //

require_once('actions.php');
require_once('globals.php');

class Konst extends TICModule
{
   function Konst()
   {
	parent::__construct(
	array(new Author("Pascal Gollor", "Hugch", "pascal@gollor.org")),
	"2",
	"Konst",
	"Stellt Konstanten zur Verfuegung",
	array(
            "Core" => "5",
          ));
   }

    public function getInstallQueriesMySQL() { return array(); }
    public function getInstallQueriesPostgreSQL() { return array(); }

    public function getSektorNamen($format = 1, $assoc = false)
    {
        $sektor = array(
            // 0 := TICNamen
            // 1 := normaler Name
            // 2 := IRCNamen
            array("punkte", "Punktzahl", "Punkt"),
            array("schiffe" ,"Schiff", "Schiff"),
            array("deff" ,"Defensiveinheit", "Verteidigung"),
            array("me" ,"Metall-Extraktor", "M-Exe"),
            array("ke" ,"Kristall-Extraktor", "K-Exe"),
            array("ast" ,"Asteroid", "Asteroid")
        );

        if ($format == 1) {
            $sektor[0][2] .= "e";
            $sektor[1][1] .= "e"; $sektor[1][2] .= "e";
            $sektor[2][1] .= "en";
            $sektor[3][1] .= "en"; $sektor[3][2] .= "n";
            $sektor[4][1] .= "en"; $sektor[4][2] .= "n";
            $sektor[5][1] .= "en"; $sektor[5][2] .= "en";
        }

        if ($assoc) {
            foreach ($sektor as $value) {
                $aSector[$value[0]] = array($value[1], $value[2]);
            }
            return $aSector;
        } else {
            return $sektor;
        }
    }

    public function getSchiffNamen($format = 1, $assoc = false)
    {
        $schiffe = array(
            // 0 := TICNamen
            // 1 := normaler Name
            // 2 := Galaxy-Network Specialbezeichnung
            array("jaeger", "J&auml;ger", "Leo"),
            array("bomber", "Bomber", "Aquilae"),
            array("freggs", "Fregatte", "Fornax"),
            array("zerris", "Zerst&ouml;rer", "Draco"),
            array("kreuzer", "Kreuzer", "Goron"),
            array("schlachter", "Schlachtschiff", "Pentalin"),
            array("traeger", "Tr&auml;ger", "Zenit"),
            array("kaper", "Kaperschiff", "Cleptor"),
            array("cancs", "Schutzschiff", "Cancri"),
        );

        if ($format == 1) {
            $schiffe[2][1] .= "n";
            $schiffe[5][1] .= "e";
            $schiffe[7][1] .= "e";
            $schiffe[8][1] .= "e";
        }

        if ($assoc) {
            foreach ($schiffe as $value) {
                $aSchiffe[$value[0]] = array($value[1], $value[2]);
            }
            return $aSchiffe;
        } else {
            return $schiffe;
        }
    }

    public function getGeschNamen($format = 1, $assoc = false)
    {
        $geschuetze = array(
            // 0 := TICNamen
            // 1 := normaler Name
            // 2 := Galaxy-Network Specialbezeichnung
            array("lo", "Leichtes Orbitalgesch&uuml;tz", "Rubium"),
            array("lr", "Leichtes Raumgesch&uuml;tz", "Pulsar"),
            array("mr", "Mittleres Raumgesch&uuml;tz", "Coon"),
            array("sr", "Schweres Raumgesch&uuml;tz", "Centurion"),
            array("aj", "Abfangj&auml;ger", "Horus")
        );

        if ($format == 1) {
            $geschuetze[0][1] .= "e";
            $geschuetze[1][1] .= "e";
            $geschuetze[2][1] .= "e";
            $geschuetze[3][1] .= "e";
            for ($i0 = 0; $i0 < 4; $i0++) {
                $geschuetze[$i0][1] = preg_replace('/s /', ' ', $geschuetze[$i0][1]);
            }
        }

        if ($assoc) {
            foreach ($geschuetze as $value) {
                $aSchiffe[$value[0]] = array($value[1], $value[2]);
            }
            return $aSchiffe;
        } else {
            return $geschuetze;
        }
    }

    public function getSchiffGeschNamen($format = 1, $assoc = false)
    {
        $schiffe = $this->getSchiffNamen($format, $assoc);
        $geschuetze = $this->getGeschNamen($format, $assoc);

        return array_merge($schiffe, $geschuetze);
    }

    public function getSchiffKosten()
    {
        return array(
            array('met' => 4000, 'kris' => 6000), // jaeger
            array('met' => 2000, 'kris' => 8000), // bomber
            array('met' => 15000, 'kris' => 7500), // freggs
            array('met' => 40000, 'kris' => 30000), // zerris
            array('met' => 65000, 'kris' => 85000), // kreuzer
            array('met' => 250000, 'kris' => 150000), // schlachter
            array('met' => 200000, 'kris' => 50000), // traeger
            array('met' => 1500, 'kris' => 1000), // kaper
            array('met' => 1000, 'kris' => 1500), // cancs
            array('met' => 6000, 'kris' => 2000), // lo
            array('met' => 20000, 'kris' => 10000), // lr
            array('met' => 60000, 'kris' => 100000), // mr
            array('met' => 200000, 'kris' => 300000), // sr
            array('met' => 1000, 'kris' => 1000), // aj
        );
    }

    public function getIRCColor($foreground, $background = false, $format = false)
    {
        $irc = chr(3);
        $bold = chr(2);
        $under = chr(31);

        if(!$foreground = $this->_getIRCColor($foreground)) { return false; }
        if($background && !$background = $this->_getIRCColor($background)) { return false; }

        if (!$background) {
            $color = $irc.$foreground;
        } else {
            $color = $irc.$foreground.','.$background;
        }
        if ($format == "bold" || $format == "fett") { $color = $bold.$color; }
        if ($format == "underlined" || $format == "unterstrichen") { $color = $under.$color; }

        return $color;
    }

    private function _getIRCColor($color)
    {
        switch ($color) {
        case "white":
        case "weiß":
            return '00';
        case "black":
        case "schwarz":
            return '01';
        case "blue":
        case "blau":
            return '02';
        case "green":
        case "gruen":
            return '03';
        case "red":
        case "rot":
            return '04';
        case "brown":
        case "braun":
            return '05';
        case "purple":
        case "lila":
            return '06';
        case "orange":
            return '07';
        case "yellow":
        case "gelb":
            return '08';
        case "lightgreen":
        case "hellgruen":
            return '09';
        case "greenblue":
        case "gruenblau":
            return '10';
        case "lightcyan":
        case "kobaltblau":
            return '11';
        case "lightblue":
        case "hellblau":
            return '12';
        case "pink":
        case "rosa":
            return '13';
        case "grey":
        case "grau":
            return '14';
        case "lightgrey":
        case "hellgrau":
            return '15';
        default:
            return false;
        }
    }

    public function getDefaultIRCColors()
    {
        $irc = chr(3);
        return array(
            $this->getIRCColor("orange", "schwarz"), // Orange auf Schwarz
            $this->getIRCColor("gelb", "schwarz"), // Gelb auf Schwarz
            $this->getIRCColor("hellgruen", "schwarz"), // Hellgruen auf Schwarz
            $this->getIRCColor("gruenblau", "schwarz"), // Tuerkis auf Schwarz
            $this->getIRCColor("rot", "weiß")  // Rot auf Weiß
        );
    }

    public function checkKoords($gala, $planet)
    {
        global $tic;

        if (!is_numeric($gala) || !is_numeric($planet)
          || strlen($gala) > 4 || strlen($planet) > 2
          || substr($gala, 0, 1) == 0 || substr($planet, 0, 1) == 0) {
            $tic->info($this->getName(), "Dies sind keine g&uuml;ltigen Koordinaten!!!"); return false;
        }
        return true;
    }
}

?>
