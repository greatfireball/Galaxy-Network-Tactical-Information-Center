<?
class CFleet
{
    var $OldShips;  // Schiffe, die im letzten Tick in der Flotte waren
    var $Ships;     // Schiffe, die diesen Tick in der Flotte sind
    var $LostShips; // Schiffe, die diese Flotte verloren hat
    var $StolenExenM;   // Von Dieser Flotte gestohlene Kristallextraktoren
    var $StolenExenK;   // Von Dieser Flotte gestohlene Kristallextraktoren
    var $TicksToStay;   // Dauer in Ticks, bis die Flotte angreift/verteidgt
    var $TicksToWait;   // Wieviele Ticks die Flotte angreift/verteidgt
}
class GNSimu2
{
    var $AttFleets;
    var $DeffFleets;
    var $Deff;      // Geschütze des Verteidigers
    var $Exen_M;    // Metall-Extarktoren des Verteidigers
    var $Exen_K;    // Kristall-Extarktoren des Verteidigers
    function Init()
    {
    // Daten Für Jäger Nr. 0
    $this->attakpower[0][0] = 0.0088;
    $this->attakpower[0][1] = 0.1400;
    $this->attakpower[0][2] = 0.0094;
    $this->shipstoattak[0][0] = 11;
    $this->shipstoattak[0][1] = 1;
    $this->shipstoattak[0][2] = 4;
    $this->percent[0][0] = 0.35;
    $this->percent[0][1] = 0.30;
    $this->percent[0][2] = 0.35;
    $this->strikes[0] = 4;
    $this->mcost[0] = 1500;
    $this->kcost[0] = 2000;
    // Daten Für Bomber Nr. 1
    $this->attakpower[1][0] = 0.0080;
    $this->attakpower[1][1] = 0.0100;
    $this->attakpower[1][2] = 0.0075;
    $this->shipstoattak[1][0] = 12;
    $this->shipstoattak[1][1] = 5;
    $this->shipstoattak[1][2] = 6;
    $this->percent[1][0] = 0.35;
    $this->percent[1][1] = 0.35;
    $this->percent[1][2] = 0.30;
    $this->strikes[1] = 4;
    $this->mcost[1] = 2000;
    $this->kcost[1] = 8000;
    // Daten Für Fregatte Nr. 2
    $this->attakpower[2][0] = 4.5000;
    $this->attakpower[2][1] = 2.5714;
    $this->shipstoattak[2][0] = 13;
    $this->shipstoattak[2][1] = 0;
    $this->percent[2][0] = 0.6;
    $this->percent[2][1] = 0.4;
    $this->strikes[2] = 3;
    $this->mcost[2] = 15000;
    $this->kcost[2] = 7500;
    // Daten Für Zerstörer Nr. 3
    $this->attakpower[3][0] = 3.5000;
    $this->attakpower[3][1] = 1.2444;
    $this->shipstoattak[3][0] = 9;
    $this->shipstoattak[3][1] = 2;
    $this->percent[3][0] = 0.6;
    $this->percent[3][1] = 0.4;
    $this->strikes[3] = 3;
    $this->mcost[3] = 40000;
    $this->kcost[3] = 30000;
    // Daten Für Kreuzer Nr. 4
    $this->attakpower[4][0] = 2.0000;
    $this->attakpower[4][1] = 0.8571;
    $this->attakpower[4][2] = 10.0000;
    $this->shipstoattak[4][0] = 10;
    $this->shipstoattak[4][1] = 3;
    $this->shipstoattak[4][2] = 8;
    $this->percent[4][0] = 0.35;
    $this->percent[4][1] = 0.30;
    $this->percent[4][2] = 0.35;
    $this->strikes[4] = 4;
    $this->mcost[4] = 65000;
    $this->kcost[4] = 85000;    // Daten Für Schalchtschiff Nr. 5
    $this->attakpower[5][0] = 1.0000;
    $this->attakpower[5][1] = 1.0666;
    $this->attakpower[5][2] = 0.4000;
    $this->attakpower[5][3] = 0.3019;
    $this->attakpower[5][4] = 26.6667;
    $this->shipstoattak[5][0] = 11;
    $this->shipstoattak[5][1] = 4;
    $this->shipstoattak[5][2] = 5;
    $this->shipstoattak[5][3] = 6;
    $this->shipstoattak[5][4] = 8;
    $this->percent[5][0] = 0.20;
    $this->percent[5][1] = 0.20;
    $this->percent[5][2] = 0.20;
    $this->percent[5][3] = 0.20;
    $this->percent[5][4] = 0.20;
    $this->strikes[5] = 5;
    $this->mcost[5] = 250000;
    $this->kcost[5] = 150000;   // Daten Für Trägerschiff Nr. 6
    $this->attakpower[6][0] = 25.000;
    $this->attakpower[6][1] = 14.000;
    $this->shipstoattak[6][0] = 7;
    $this->shipstoattak[6][1] = 8;
    $this->percent[6][0] = 0.5;
    $this->percent[6][1] = 0.5;
    $this->strikes[6] = 2;
    $this->mcost[6] = 200000;
    $this->kcost[6] =  50000;
    // Daten für Kaperschiff
    $this->strikes[7] = 0;
    $this->mcost[7] = 1500;
    $this->kcost[7] = 1000;
    // Daten für Schutzschiff
    $this->mcost[8] = 1000;
    $this->kcost[8] = 1500;
    $this->strikes[8] = 0;
    // Daten Für Leichtes Orbligtalgeschütz Nr. 9
    $this->attakpower[9][0] = 0.9150;
    $this->attakpower[9][1] = 1.2800;
    $this->shipstoattak[9][0] = 0;
    $this->shipstoattak[9][1] = 7;
    $this->percent[9][0] = 0.6;
    $this->percent[9][1] = 0.4;
    $this->strikes[9] = 3;
    $this->mcost[9] = 6000;
    $this->kcost[9] = 2000;
    // Daten Für Leichtes Raumgeschütz Nr. 10
    $this->attakpower[10][0] = 1.2000;
    $this->attakpower[10][1] = 0.5334;
    $this->shipstoattak[10][0] = 1;
    $this->shipstoattak[10][1] = 2;
    $this->percent[10][0] = 0.4;
    $this->percent[10][1] = 0.6;
    $this->strikes[10] = 3;
    $this->mcost[10] = 20000;
    $this->kcost[10] = 10000;
    // Daten Für Mittleres Raumgeschütz Nr. 11
    $this->attakpower[11][0] = 0.9143;
    $this->attakpower[11][1] = 0.4267;
    $this->shipstoattak[11][0] = 3;
    $this->shipstoattak[11][1] = 4;
    $this->percent[11][0] = 0.4;
    $this->percent[11][1] = 0.6;
    $this->strikes[11] = 3;
    $this->mcost[11] =  60000;
    $this->kcost[11] = 100000;
    // Daten Für Schweres Raumgeschütz Nr. 12
    $this->attakpower[12][0] = 0.5000;
    $this->attakpower[12][1] = 0.3774;
    $this->attakpower[12][2] = 0.0001;
    $this->shipstoattak[12][0] = 5;
    $this->shipstoattak[12][1] = 6;
    $this->shipstoattak[12][2] = 99;
    $this->percent[12][0] = 0.5;
    $this->percent[12][1] = 0.5;
    $this->percent[12][2] = 0.0;
    $this->strikes[12] = 2;
    $this->mcost[12] = 200000;
    $this->kcost[12] = 300000;
    // Daten Für  Abfangjäger Nr. 13
    $this->attakpower[13][0] = 0.0114;
    $this->attakpower[13][1] = 0.3200;
    $this->shipstoattak[13][0] = 3;
    $this->shipstoattak[13][1] = 7;
    $this->percent[13][0] = 0.4;
    $this->percent[13][1] = 0.6;
    $this->strikes[13] = 3;
    $this->mcost[13] = 1000;
    $this->kcost[13] = 1000;
    }
    function Tick()
    {
    $debug = 0;
    for($i=0;$i<count($this->AttFleets);$i++)
    {
        if($this->AttFleets[$i]->TicksToWait==0)
        {
        $this->AttFleets[$i]->TicksToStay--;
        if($this->AttFleets[$i]->TicksToStay>=0)
        {
            for($j=0;$j<9;$j++)
            $GesAtt[$j] += $this->AttFleets[$i]->Ships[$j];
        }
        }
        else
        {
        $this->AttFleets[$i]->TicksToWiat--;
        }
        $this->AttFleets[$i]->OldShips = $this->AttFleets[$i]->Ships;
    }
    for($i=0;$i<count($this->DeffFleets);$i++)
    {
        if($this->DeffFleets[$i]->TicksToWait==0)
        {
        $this->DeffFleets[$i]->TicksToStay--;
        if($this->DeffFleets[$i]->TicksToStay>=0)
        {
            for($j=0;$j<9;$j++)
            $GesDeff[$j] += $this->DeffFleets[$i]->Ships[$j];
        }
        }
        else
        {
        $this->DeffFleets[$i]->TicksToWait--;
        }
        $this->DeffFleets[$i]->OldShips = $this->DeffFleets[$i]->Ships;
    }
    for($i=0;$i<5;$i++)
    {
        $GesDeff[$i+9] = $this->Deff[$i];
    }
    for($i=0;$i<14;$i++)
    {
        $RestPercentatt = 0;
        $Restpoweratt = $GesAtt[$i];
        $RestPercentdeff = 0;
        $Restpowerdeff = $GesDeff[$i];;
        $count=0;
        if($debug)
        echo "<b>Berechnungen für Schiffstyp ".($i+1)."</b><br>";
        while($count < $this->strikes[$i] +3)
        {
        if($debug)
            echo "<b>Strike".($count-$this->strikes[$i]-3)."</b><br>";
            $OldRestpoweratt = $Restpoweratt;
            $OldRestpowerdeff = $Restpowerdeff;
        for($j=0;$j<count($this->shipstoattak[$i]);$j++)
        {
            if($debug)
            echo "<b>$i gegen ".$this->shipstoattak[$i][$j]."</b><br>";
                // Angreifer
            if($Restpoweratt>0)
            {
            $del = 0;
            $MaxDestruction = floor(($RestPercentatt+$this->percent[$i][$j]) * $OldRestpoweratt * $this->attakpower[$i][$j]);
                if($debug)
                {
                echo "<font color=#ff0000>-</font> Angreifende Schiffe: ".$GesAtt[$i]." Verteidigende Schiffe:".($GesAtt[$this->shipstoattak[$i][$j]]+$ToDelDeff[$this->shipstoattak[$i][$j]])."<br>";
                echo "<font color=#ff0000>-</font> Maximale Zerstörung: floor(($RestPercentatt+".$this->percent[$i][$j].") * $OldRestpoweratt * ".$this->attakpower[$i][$j].")=$MaxDestruction<br>";
            }
                if($count==3)
                $RestPercentatt+=$this->percent[$i][$j];
                $del= floor(max(min($MaxDestruction, $Restpoweratt * $this->attakpower[$i][$j], $GesDeff[$this->shipstoattak[$i][$j]]-$ToDelDeff[$this->shipstoattak[$i][$j]]), 0));
                if($count==3)
                $RestPercentatt-= ($del / $OldRestpoweratt / $this->attakpower[$i][$j]);
                $Firepower = $del/$this->attakpower[$i][$j];
                $Restpoweratt -= $Firepower;
                $ToDelDeff[$this->shipstoattak[$i][$j]]+=$del;
                    if($debug)
                    {
                echo "<font color=#ff0000>-</font> Zerstörte Schiffe: $del<br>
                          <font color=#ff0000>-</font> Benutzte Firepower = $del/".$this->attakpower[$i][$j]." = $Firepower; Restpower = $Restpoweratt<br>";
                }
            }
                // Verteidiger
            if($Restpowerdeff>0)
            {
                $del = 0;
                $MaxDestruction = floor(($RestPercentdeff+$this->percent[$i][$j]) * $OldRestpowerdeff * $this->attakpower[$i][$j]);
                if($debug)
                {
                echo "<font color=#00ff00>-</font> Angreifende Schiffe: ".$GesDeff[$i]." Verteidigende Schiffe:".($GesDeff[$this->shipstoattak[$i][$j]]+$ToDelAtt[$this->shipstoattak[$i][$j]])."<br>";
                echo "<font color=#00ff00>-</font> Maximale Zerstörung: floor(($RestPercentdeff+".$this->percent[$i][$j].") * $OldRestpowerdeff * ".$this->attakpower[$i][$j].")=$MaxDestruction<br>";
                }
                if($count==3)
                $RestPercentdeff+=$this->percent[$i][$j];
                $del = floor(max(min($MaxDestruction, $Restpowerdeff * $this->attakpower[$i][$j], $GesAtt[$this->shipstoattak[$i][$j]]-$ToDelAtt[$this->shipstoattak[$i][$j]]), 0));
                if($count==3)
                $RestPercentdeff-= ($del / $OldRestpowerdeff / $this->attakpower[$i][$j]);
                $Firepower = $del/$this->attakpower[$i][$j];
            $Restpowerdeff -= $Firepower;
                $ToDelAtt[$this->shipstoattak[$i][$j]]+=$del;
                    if($debug)
                    {
                echo "<font color=#00ff00>-</font> Zerstörte Schiffe: $del<br> <font color=#00ff00>-</font> Benutzte Firepower = $del/".$this->attakpower[$i][$j]." = $Firepower; Restpower = $Restpowerdeff<br>";
                }
            }
        }
        $count++;
        }
    }
    //Todel verrechnen
    for($i=0;$i<9;$i++)
    {
        if($GesAtt[$i]>0)
        for($j=0;$j<count($this->AttFleets);$j++)
        {
        $this->AttFleets[$j]->LostShips[$i]+=round($GesAtt[$i]/$this->AttFleets[$j]->Ships[$i]*$ToDelAtt[$i]);
        $this->AttFleets[$j]->Ships[$i]-=round($GesAtt[$i]/$this->AttFleets[$j]->Ships[$i]*$ToDelAtt[$i]);
        }
        if($GesDeff[$i]>0)
        for($j=0;$j<count($this->DeffFleets);$j++)
        {
        $this->DeffFleets[$j]->LostShips[$i]+=round($GesDeff[$i]/$this->DeffFleets[$j]->Ships[$i]*$ToDelDeff[$i]);
        $this->DeffFleets[$j]->Ships[$i]-=round($GesDeff[$i]/$this->DeffFleets[$j]->Ships[$i]*$ToDelDeff[$i]);
        }
    }
    for($j=0;$j<count($this->AttFleets);$j++)
    {
        $maxmexen = ceil((max($this->AttFleets->Ships[7]-$this->AttFleets->Ships[8],0))/2)*($GesAtt[8]/$this->AttFleets->Ships[8]);
        $maxkexen = floor((max($this->AttFleets->Ships[7]-$this->AttFleets->Ships[8],0))/2)*($GesAtt[8]/$this->AttFleets->Ships[8]);
        $rmexen = min($maxmexen, floor($this->Exen_M*0.1));
        if($rmexen != $maxmexen)
        $maxkexen += $maxmexen-$rmexen;
        $rkexen = min($maxkexen, floor($this->Exen_K*0.1));
        if($rmexen != $maxmexen)
        {
        $maxmexen += $maxkexen-$rkexen;
        $rmexen = min($maxmexen, floor($this->Exen_M*0.1));
        }
        $this->Exen_M -=$rmexen;
        $this->Exen_K -=$rkexen;
        $this->AttFleets->Ships[8]-=$rmexen+$rkexen;
        $this->AttFleets->LostShips[8]+=$rmexen+$rkexen;
        $this->AttFleets->StolenExenM+=$this->stolenmexen = $rmexen;
        $this->AttFleets->StolrnExenK+=$this->stolenkexen = $rkexen;
    }
    }
    function AddAttFleet($fleet)
    {
    if(count($this->AttFleets)<100)
        $this->AttFleets[] = $fleet;
    }
    function AddDeffFleet($fleet)
    {
    if(count($this->DeffFleets)<100)
        $this->DeffFleets[] = $fleet;
    }
    function PrintOverview()
    {
    $vklost = $vmlost =$aklost = $amlost = 0;
    for($i=0;$i<count($this->AttFleets);$i++)
    {
        for($j=0;$j<9;$j++)
        {
        $geslostshipsa[$j] = $this->AttFleets[$i]->LostShips[$j];
        if($geslostshipsa[$j]=="")
            $geslostshipsa[$j] = 0;
        }
        $gesstolenexenm+=$this->AttFleets[$i]->StolnenExenM;
        $gesstolenexenk+=$this->AttFleets[$i]->StolnenExenK;
    }
    for($i=0;$i<count($this->DeffFleets);$i++)
    {
        for($j=0;$j<9;$j++)
        {
        $geslostshipsv[$j] = $this->DeffFleets[$i]->LostShips[$j];
        if($geslostshipsv[$j]=="")
            $geslostshipsv[$j] = 0;
        }
    }
    for($i=0;$i<15;$i++)
    {
        $vklost  += $this->kcost[$i]*$geslostshipsv[$i];
        $vmlost  += $this->mcost[$i]*$geslostshipsv[$i];
        $aklost  += $this->kcost[$i]*$geslostshipsa[$i];
        $amlost  += $this->mcost[$i]*$geslostshipsa[$i];
    }
    echo "<br><center><table bgcolor=#555555 cellspacing=1>";
    echo "<tr><td colspan=3 align='center'><u><b>Übersicht</b></u></font></td></tr>";
    echo "<tr bgcolor=#666666><td colspan=3 align='center'><b>Verlorene Schiffe/Geschütze</b></font></td></tr>";
    echo "<tr bgcolor=#777777><td><font size=-1><b>Typ</b></font></td><td align='center'><b>Verteidiger</b></font></td><td align='center'><b>Angreifer</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Jäger</font></td><td><font size=-1>          ".$geslostshipsv[0]."</font></td><td><font size=-1>".$geslostshipsa[0]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Bomber</font></td><td><font size=-1>         ".$geslostshipsv[1]."</font></td><td><font size=-1>".$geslostshipsa[1]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Fregatte</font></td><td><font size=-1>       ".$geslostshipsv[2]."</font></td><td><font size=-1>".$geslostshipsa[2]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Zerstörer</font></td><td><font size=-1>      ".$geslostshipsv[3]."</font></td><td><font size=-1>".$geslostshipsa[3]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kreuzer</font></td><td><font size=-1>        ".$geslostshipsv[4]."</font></td><td><font size=-1>".$geslostshipsa[4]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schlachtschiff</font></td><td><font size=-1>     ".$geslostshipsv[5]."</font></td><td><font size=-1>".$geslostshipsa[5]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Trägerschiff</font></td><td><font size=-1>       ".$geslostshipsv[6]."</font></td><td><font size=-1>".$geslostshipsa[6]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kaperschiff</font></td><td><font size=-1>        ".$geslostshipsv[7]."</font></td><td><font size=-1>".$geslostshipsa[7]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schutzschiff</font></td><td><font size=-1>       ".$geslostshipsv[8]."</font></td><td><font size=-1>".$geslostshipsa[8]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Orbitalgschütz</font></td><td><font size=-1> ".$geslostshipsv[9]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Raumgeschütz</font></td><td><font size=-1>   ".$geslostshipsv[10]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Mittleres Raumgeschütz</font></td><td><font size=-1>  ".$geslostshipsv[11]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schweres Raumgeschütz</font></td><td><font size=-1>  ".$geslostshipsv[12]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Abfangjäger</font></td><td><font size=-1>    ".$geslostshipsv[13]."</font></td>";
    echo "<tr bgcolor=#777777><td align='center' colspan=3><b>Kosten für Neubau</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Metall</font></td><td><font size=-1>$vmlost</font></td><td><font size=-1>$amlost</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kristall</font></td><td><font size=-1>$vklost</font></td><td><font size=-1>$aklost</font></td></tr>";
    echo "<tr bgcolor=#666666><td colspan=3 align='center'><b>Gestohlene Extraktoren</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Metallextraktoren:</font></td><td><font size=-1> ".gesstolenexenm."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kristallextraktoren:</font></td><td><font size=-1>   ".gesstolenexenk."</font></td></tr>";
    echo "</table></center>";
    }
}
class GNSimu
{
    var $attaking;   // Angreifende Schiffe(array von 0-8)
    var $deffending; // Verteidigende Schiffe(array von 0-13)
    var $Oldatt;     //Schiffe die am Anfang des Ticks da waren
    var $Olddeff;
    var $mexen;      // Exen die der Angegriffene hat
    var $kexen;
    var $stolenmexen;  // Geklauteexen
    var $stolenkexen;
    var $gesstolenexenm; // Gesammtgeklaute exen
    var $gesstolenexenk;
    var $geslostshipsatt; // Schiffe die seit erstellung der Klasse zerstört wurden
    var $geslostshipsdeff;
    var $mcost;            // Wie viel ein Schiff Kostet
    var $kcost;
    var $name;
    var $attakpower;
    var $shiptoattak;
    var $percent;
    function GNSimu() // Variablen mit Kampfwerten füllen
    {
        $this->geslostshipsatt = array(0,0,0,0,0,0,0,0,0);
        $this->geslostshipsdeff = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        $this->attaking = array(0,0,0,0,0,0,0,0,0);
        $this->deffending = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
    // Daten Für Jäger Nr. 0
    $this->name[0] = "Jäger";
    $this->attakpower[0]  = array(0.0246, 0.392, 0.0263); // Wie viele Schiffe ein Schiff mit 100% Feuerkrafft zerstören würde
    $this->shiptoattak[0] = array(11,1,4); // Welche Schiffe/Geschütze angegriffen werden
    $this->percent[0]     = array(0.35,0.30,0.35); // Die Verteilung der Prozente, mit der auf die Schiffe geschoßen wird.
    $this->mcost[0] = 4000;
    $this->kcost[0] = 6000;
    // Daten Für Bomber Nr. 1
    $this->attakpower[1]  = array(0.0080,0.0100,0.0075);
    $this->shiptoattak[1] = array(12,5,6);
    $this->percent[1]     = array(0.35,0.35,0.30);
    $this->name[1] = "Bomber";
    $this->mcost[1] = 2000;
    $this->kcost[1] = 8000;
    // Daten Für Fregatte Nr. 2
    $this->attakpower[2]  = array(4.5,0.85);
    $this->shiptoattak[2] = array(13,0);
    $this->percent[2]     = array(0.6,0.4);
    $this->name[2] = "Fregatte";
    $this->mcost[2] = 15000;
    $this->kcost[2] = 7500;
    // Daten Für Zerstörer Nr. 3
    $this->attakpower[3]  = array(3.5,1.2444);
    $this->shiptoattak[3] = array(9,2);
    $this->percent[3]     = array(0.6,0.4);
    $this->name[3]  = "Zerstörer";
    $this->mcost[3] = 40000;
    $this->kcost[3] = 30000;
    // Daten Für Kreuzer Nr. 4
    $this->attakpower[4]  = array(2.0,0.8571,10.0);
    $this->shiptoattak[4] = array(10,3,8);
    $this->percent[4]     = array(0.35,0.30,0.35);
    $this->name[4]       = "Kreuzer";
    $this->mcost[4] = 65000;
    $this->kcost[4] = 85000;
    // Daten Für Schalchtschiff Nr. 5
    $this->attakpower[5]  = array(1.0,1.0666,0.4,0.3019,26.6667);
    $this->shiptoattak[5] = array(11,4,5,6,8);
    $this->percent[5]     = array(0.2,0.2,0.2,0.2,0.2);
    $this->name[5]  = "Schlachtschiff";
    $this->mcost[5] = 250000;
    $this->kcost[5] = 150000;
    // Daten Für Trägerschiff Nr. 6
    $this->attakpower[6]  = array(25.0,14.0);
    $this->shiptoattak[6] = array(7,8);
    $this->percent[6]     = array(0.5,0.5);
    $this->mcost[6] = 200000;
    $this->kcost[6] =  50000;
    $this->name[6]  = "Trägerschiff";
    // Daten für Kaperschiff
    $this->mcost[7] = 1500;
    $this->kcost[7] = 1000;
    $this->name[7] = "Kaperschiff";
    // Daten für Schutzschiff
    $this->mcost[8] = 1000;
    $this->kcost[8] = 1500;
    $this->name[8]  = "Schutzschiff";
    // Daten Für Leichtes Obligtalgeschütz Nr. 9
    $this->attakpower[9]  = array(0.3,1.28);
    $this->shiptoattak[9] = array(0,7);
    $this->percent[9]     = array(0.6,0.4);
    $this->this->mcost[9] = 6000;
    $this->kcost[9] = 2000;
    $this->name[9]  = "Leichtes Obligtalgeschütz";
    // Daten Für Leichtes Raumgeschütz Nr. 10
    $this->attakpower[10]  = array(1.2,0.5334);
    $this->shiptoattak[10] = array(1,2);
    $this->percent[10]     = array(0.4,0.6);
    $this->mcost[10] = 20000;
    $this->kcost[10] = 10000;
    $this->name[10]  = "Leichtes Raumgeschütz";
    // Daten Für Mittleres Raumgeschütz Nr. 11
    $this->attakpower[11]  = array(0.9143,0.4267);
    $this->shiptoattak[11] = array(3,4);
    $this->percent[11]     = array(0.4,0.6);
    $this->mcost[11] =  60000;
    $this->kcost[11] = 100000;
    $this->name[11]  = "Mittleres Raumgeschütz";
        // Daten Für Schweres Raumgeschütz Nr. 12
    $this->attakpower[12]  = array(0.5,0.3774);
    $this->shiptoattak[12] = array(5,6);
    $this->percent[12]     = array(0.5,0.5);
    $this->mcost[12] = 200000;
    $this->kcost[12] = 300000;
    $this->name[12]  = "Schweres Raumgeschütz";
    // Daten Für  Abfangjäger Nr. 13
    $this->attakpower[13]  = array(0.0114,0.32);
    $this->shiptoattak[13] = array(3,7);
    $this->percent[13]     = array(0.4,0.6);
    $this->mcost[13] = 1000;
    $this->kcost[13] = 1000;
    $this->name[13]  = "Abfangjäger";
    }
    function Compute($lasttick) // Dieses ist sie also die mytische Funktion, die Werte in äh ja äh andere Werte verwandeln kann. $lasttick dient dazu im letzten tick die Jäger und Bomber zu zerstören, die über sind.
    {
        // Wenn man tolle Debug informationen sehen will einfach auf 1 setzen
    $debug = 0;
    // "Sicherheitskopie" der Anzahl der Schiffe machen
    for($i=0;$i<14;$i++)
    {
        $this->Olddeff[$i] = $this->deffending[$i];
        if($i<9)
        $this->Oldatt[$i] = $this->attaking[$i];
    }
        //Schleife über alle Schiffe
    for($i=0;$i<14;$i++)
    {
            //Variablen für das nächste Schiff "nullen"
        $RestPercentatt = 0;
        $Restpoweratt = $this->Oldatt[$i]; //Die Power ist gleich der Anzahl der Schiffe die angreifen
        $OldRestpoweratt = 0;
        $RestPercentdeff = 0;
        $Restpowerdeff = $this->Olddeff[$i];
        $OldRestpowerdeff = 0;
        $strike=0;
            //Berechnen wie viele Strikes der aktuelle Schiffstyp hat(eins geteilet durch den kleinsten Prozentwert, mit dem das Schiff feuert und das ganz aufrunden und noch +3)
            if($this->percent[$i])
                $curstrikes = ceil(1/min($this->percent[$i]))+3;
            else
                $curstrikes = 0;
        while($strike < $curstrikes )
        {
        if($debug)
            echo "<b>Strike".($strike-$curstrikes)."</b><br>";
            $OldRestpoweratt = $Restpoweratt;
            $OldRestpowerdeff = $Restpowerdeff;
                // Schleife über alle Schiffe die angeriffen werden sollen
        for($j=0;$j<count($this->attakpower[$i]);$j++)
        {
            if($debug)
            echo $this->name[$i]." gegen ".$this->name[$this->shiptoattak[$i][$j]]."<br>";
                // Angreifer
            if($Restpoweratt>0)
            {
                $del = 0;
                        // Dafür sorgen, dass nicht mit einem Prozentsatz von größer als 100% angerifen wird
                if($RestPercentatt+$this->percent[$i][$j] > 1)
                $RestPercentatt = 1.0 - $this->percent[$i][$j];
                         // Maximale Zerstörung die Angerichtet werden kann. Die Power der Prozentsatz mal die Power der Schiffe mal wie viele Schiffe vom andern tyo von einem zerstört werden
                $MaxDestruction = floor(($RestPercentatt+$this->percent[$i][$j]) * $OldRestpoweratt * $this->attakpower[$i][$j]);
                if($debug)
                {
                echo "<font color=#ff0000>-</font> Angreifende Schiffe: ".$this->Oldatt[$i]." Verteidigende Schiffe:".($this->deffending[$this->shiptoattak[$i][$j]])."<br>";
                echo "<font color=#ff0000>-</font> Maximale Zerstörung: floor(($RestPercentatt+".$this->percent[$i][$j].") * $OldRestpoweratt * ".$this->attakpower[$i][$j].")=$MaxDestruction<br>";
                }
                        // Wie viele Schiffe dann zerstört werden, nich mehr als die maximale zerstörung und nich mehr als mit 100%(was oben eigentlich schon geprüft wird) und nich mehr als Schiffe noch über sind.
                $del= floor(max(min($MaxDestruction, $Restpoweratt * $this->attakpower[$i][$j], $this->deffending[$this->shiptoattak[$i][$j]]), 0));
                        // Im 4ten Strike wird unter bestimmten Umständen(s.u) der Prozentsatz, der beim feuern nicht zum Einsatz gekommen ist zu einer Variable addiert, die zum normalen Prozentsatz dazugerechnet wird.
            if($strike==3)
            {
                            // Wenn es das letzte Schiff im Tick ist oder keine Schiffe zerstört wurden wird Rest-Prozent um den Prozentsatz, der nich verbraucht wird erhöht.
                            // Alles könnte schön und gut sein, wenn da nicht die Schlachter wären, die flogen der Regel nämlich nur wenn sie auf sich selbst oder Kreuzer schießen, sonnst wird immer der Prozentsatz der nicht gebraucht wurde dazugerechnet, warum auch immer...
                            if ( $j == count($this->attakpower[$i])-1 || $del == 0 || ($i == 5 && $this->shiptoattak[$i][$j]!=5 && $this->shiptoattak[$i][$j]!=4))
                            {
                    $RestPercentatt += $this->percent[$i][$j] - ($del / $OldRestpoweratt / $this->attakpower[$i][$j]);
                            }
            }
                        // Benutze Feuerkraft berechnen und subtrahiren
                $Firepower = $del/$this->attakpower[$i][$j];
                $Restpoweratt -= $Firepower;
                        // Schiffe zerstören
                $this->deffending[$this->shiptoattak[$i][$j]] -=$del;
                $this->geslostshipsdeff[$this->shiptoattak[$i][$j]] += $del;
                    if($debug)
                    {
                echo "<font color=#ff0000>-</font> Zerstörte Schiffe: $del<br><font color=#ff0000>-</font> Benutzte Firepower = $del/".$this->attakpower[$i][$j]." = $Firepower; Restpower = $Restpoweratt<br>";
                }
            }
                // Nochmal genau das selbe nur mit Angreifer/Verteidiger vertauschten Variablen.
            if($Restpowerdeff>0)
            {
                $del = 0;
                if($RestPercentdeff+$this->percent[$i][$j] > 1)
                $RestPercentdeff = 1.0 - $this->percent[$i][$j];
                $MaxDestruction = floor(($RestPercentdeff+$this->percent[$i][$j]) * $OldRestpowerdeff * $this->attakpower[$i][$j]);
                if($debug)
                {
                echo "<font color=#00ff00>-</font> Angreifende Schiffe: ".$this->Olddeff[$i]." Verteidigende Schiffe:".($this->attaking[$this->shiptoattak[$i][$j]])."<br>";
                echo "<font color=#00ff00>-</font> Maximale Zerstörung: floor(($RestPercentdeff+".$this->percent[$i][$j].") * $OldRestpowerdeff * ".$this->attakpower[$i][$j].")=$MaxDestruction<br>";
                }
                $del= floor(max(min($MaxDestruction, $Restpowerdeff * $this->attakpower[$i][$j], $this->attaking[$this->shiptoattak[$i][$j]]), 0));
            if($strike==3)
            {
                if ( $j == count($this->attakpower[$i])-1 || $del == 0 || ($i == 5 && $this->shiptoattak[$i][$j]!=5 && $this->shiptoattak[$i][$j]!=4))
                {
                $RestPercentdeff += $this->percent[$i][$j] - ($del / $OldRestpowerdeff / $this->attakpower[$i][$j]);
                            }
            }
                $Firepower = $del/$this->attakpower[$i][$j];
                $Restpowerdeff -= $Firepower;
                $this->attaking[$this->shiptoattak[$i][$j]] -= $del;
                $this->geslostshipsatt[$this->shiptoattak[$i][$j]] += $del;
                    if($debug)
                    {
                echo "<font color=#00ff00>-</font> Zerstörte Schiffe: $del<br><font color=#00ff00>-</font> Benutzte Firepower = $del/".$this->attakpower[$i][$j]." = $Firepower; Restpower = $Restpowerdeff<br>";
                }
            }
        }
        $strike++;
        }
    }
        //Wenn wir im letzen Tick sind wird geprüft ob auch alle Jäger und Bomber mit nach hause fliegn dürfen
    if($lasttick)
    {
        $jaeger =  $this->attaking[0];
        $bomber =  $this->attaking[1];
        $traeger = $this->attaking[6];
        if ( $bomber + $jaeger > $traeger*100)
        {
        $todel = $jaeger + $bomber - $traeger*100;
        $tmp = round($todel*($jaeger/($jaeger + $bomber)));
                $this->attaking[0] -= $tmp;
                $this->geslostshipsatt[0] += $tmp;
        $tmp = round($todel*($bomber/($jaeger + $bomber)));
                $this->attaking[1] -= $tmp;
                $this->geslostshipsatt[1] += $tmp;
        }
    }
        //Dann noch mal eben schnell paar exen klauen
        //Erstmall ausrechnen, wie viele maximal mitgenommen werden können, bin der Meinung mal Iregndwo im Forum gelesen zu haben, dass Metall- auf- und Kristallexen abgerundet werden
    $maxmexen = ceil((max($this->attaking[7]-$this->deffending[8],0))/2);
    $maxkexen = floor((max($this->attaking[7]-$this->deffending[8],0))/2);
        //Dann wie viele Metallexen in den meißten fällen geklaut würden
    $rmexen = min($maxmexen, floor($this->mexen*0.1));
        //Wenn nich alle Schiffe, die für Metallexenlau bereitgestellt waren benutz werden, dürfen diezum Kristallexen klauen Benutzt werden
    if($rmexen != $maxmexen)
        $maxkexen += $maxmexen-$rmexen;
        //Kristallexen in den meißten fällen
    $rkexen = min($maxkexen, floor($this->kexen*0.1));
        // Wenn nich alle zum Kristallexen bereitgestellten Cleps benutzt wurden, rechnen wir nochmal Metallexen ob nich evtl mehr mit genommen werden können.
    if($rkexen != $maxkexen)
    {
        $maxmexen += $maxkexen-$rkexen;
        $rmexen = min($maxmexen, floor($this->mexen*0.1));
    }
        // Exen vom bestand abziehen und auch die benutzen Cleps "zerstören"
    $this->mexen -=$rmexen;
    $this->kexen -=$rkexen;
    $this->attaking[7] -= $rmexen+$rkexen;
    $this->geslostshipsatt[7]+=$rmexen+$rkexen;
        //Für die Statistik, wie viele Exen insgesammt gestohlen wurden.
    $this->gesstolenexenm+=$this->stolenmexen = $rmexen;
    $this->gesstolenexenk+=$this->stolenkexen = $rkexen;
    }
   function ComputeOneTickBefore()
   {
        $debug =0;
    for($i=0;$i<15;$i++)
    {
        $this->Olddeff[$i] = $this->deffending[$i];
        if($i<10);      $this->Oldatt[$i] = $this->attaking[$i];
    }
    unset($todela);
    /// Leichtes Raumgeschütz
    $RestPercentdeff = 0;
    $Restpowerdeff = $this->deffending[10];
    $OldRestpowerdeff = 0;
    $first = 0;
    while($first<6 && ($Restpowerdeff>0))
    {
        $OldRestpowerdeff = $Restpowerdeff;
        // Leichtes Raumgeschütz  gegen Fregatte
        // Verteidiger
        if($Restpowerdeff>0)
        {
        $del = 0;
        $MaxDestruction = floor(($RestPercentdeff+1.0) * $OldRestpowerdeff * 0.5334*0.5);
        if($first==3)
            $RestPercentdeff+=0.6;
        $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.5334*0.5, $this->attaking[2]+$todela[2]), 0));
        if($first==3)
             $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.5334*0.5));
        $Firepower = $del/(0.5334*0.5);
        $Restpowerdeff -= $Firepower;
        $todela[2]-=$del;
        }
        $first++;
    }
    $RestPercentdeff = 0;
    $Restpowerdeff = $this->deffending[11];
    $OldRestpowerdeff = 0;
    $first = 0;
    while($first<6 && ($Restpowerdeff>0))
    {
        $OldRestpowerdeff = $Restpowerdeff;
        // Mittlers Raumgeschütz  gegen Zerstörer
        // Verteidiger
        if($Restpowerdeff>0)
        {
        $del = 0;
        $MaxDestruction = floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.9143*0.5);
        if($first==3)
            $RestPercentdeff+=0.4;
        $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.9143*0.5, $this->attaking[3]+$todela[3]), 0));
        if($first==3)
             $RestPercentdeff-= ($del / $OldRestpowerdeff / (0.9143*0.5));
        $Firepower = $del/(0.9143*0.5);
        $Restpowerdeff -= $Firepower;
        $todela[3]-=$del;
        }
        // Mittlers Raumgeschütz  gegen Kreuzer
        // Verteidiger
        if($Restpowerdeff>0)
        {
        $del = 0;
        $MaxDestruction = floor(($RestPercentdeff+0.6) * $OldRestpowerdeff * 0.4267*0.5);
        if($first==3)           $RestPercentdeff+=0.6;
        $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.4267*0.5, $this->attaking[4]+$todela[4]), 0));
        if($first==3)
             $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.4267*0.5));
        $Firepower = $del/(0.4267*0.5);     $Restpowerdeff -= $Firepower;
        $todela[4]-=$del;
        }
        $first++;
    }
//Mittlers Raumgeschütz
    // Brechnungen für Schweres Raumgeschütz
    $RestPercentdeff = 0;
    $Restpowerdeff = $this->deffending[12];
    $OldRestpowerdeff = 0;
    $first = 0;
    if($debug)
        echo "<b>Berechnungen für Schweres Raumgeschütz</b><br>";
    while($first<8 && ($Restpowerdeff>0))
    {
        if($debug)
        echo "<b>Strike".(-5+$first)."</b><br>";
        $OldRestpowerdeff = $Restpowerdeff;
        // Schweres Raumgeschütz  gegen Schlachtschiff
        if($debug)
        echo "<font color=#00ff00>-</font> Schweres Raumgeschütz  gegen Schlachtschiff<br>";
        // Verteidiger
        if($Restpowerdeff>0)
        {
        $del = 0;
                if($RestPercentdeff+0.4 > 1.0)
                    $RestPercentdeff = 1.0-0.4;
        $MaxDestruction = floor(($RestPercentdeff+0.5) * $OldRestpowerdeff * 0.5*0.6);
        if($debug)
        {
            echo "<font color=#00ff00>-</font> Angreifende Schiffe: ".$this->deffending[12]." Verteidigende Schiffe:".($this->attaking[5]+$todela[5])."<br>";
            echo "<font color=#00ff00>-</font> Maximale Zerstörung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.5)=$MaxDestruction<br>";
        }       if($first==3)
            $RestPercentdeff+=0.5;
        $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.5*0.6, $this->attaking[5]+$todela[5]), 0));
        if($first==3)
             $RestPercentdeff-= ($del / $OldRestpowerdeff / (0.5*0.6));
        $Firepower = $del/(0.5*0.6);
        $Restpowerdeff -= $Firepower;
        $todela[5]-=$del;
        }
        // Schweres Raumgeschütz  gegen Trägerschiff
        if($debug)
        echo "<font color=#00ff00>-</font> Schweres Raumgeschütz  gegen Trägerschiff<br>";
        // Verteidiger
        if($Restpowerdeff>0)        {
        $del = 0;
        $MaxDestruction = floor(($RestPercentdeff+0.5) * $OldRestpowerdeff * 0.3774*0.6);
        if($debug)
        {
            echo "<font color=#00ff00>-</font> Angreifende Schiffe: ".$this->deffending[13]." Verteidigende Schiffe:".($this->attaking[6]+$todela[6])."<br>";
            echo "<font color=#00ff00>-</font> Maximale Zerstörung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.3774*0.6)=$MaxDestruction<br>";
        }       if($first==3)
            $RestPercentdeff+=0.5;
        $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.3774*0.6, $this->attaking[6]+$todela[6]), 0));
        if($first==3)
             $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.3774*0.6));
        $Firepower = $del/(0.3774*0.6);
        $Restpowerdeff -= $Firepower;
        $todela[6]-=$del;
        }
        if($first==3)
            $RestPercentdeff+=0.2;
        $first++;
    }//Schweres Raumgeschütz
    // Übrige Bomber und Jäger zerstören...
    $jaeger =  $this->attaking[0] + $todela[0];
    $bomber =  $this->attaking[1] + $todela[1];
    $traeger = $this->attaking[6] + $todela[6];
    if ( $bomber + $jaeger > $traeger*100)
    {
        $todel = $jaeger + $bomber - $traeger*100;
        $todela[0] = -round($todel*($jaeger/($jaeger + $bomber)));
        $todela[1] = -round($todel*($bomber/($jaeger + $bomber)));
    }
    //Todel verrechnen
    for($i=0;$i<14;$i++)
    {
        $this->geslostshipsatt[$i]-=$todela[$i];
        $this->geslostshipsdeff[$i]-=$todelv[$i];
        $this->attaking[$i]+=$todela[$i];
        $this->deffending[$i]+=$todelv[$i];
    }
   }
   function ComputeTwoTickBefore()
   {
    for($i=0;$i<15;$i++)
    {
        $this->Olddeff[$i] = $this->deffending[$i];
        if($i<10);
        $this->Oldatt[$i] = $this->attaking[$i];
    }
    // Brechnungen für Schweres Raumgeschütz
    $RestPercentdeff = 0;
    $Restpowerdeff = $this->deffending[12];
    $OldRestpowerdeff = 0;
    $first = 0;
    if($debug)
        echo "<b>Berechnungen für Schweres Raumgeschütz</b><br>";
    while($first<8 && ($Restpowerdeff>0))
    {
        if($debug)
        echo "<b>Strike".(-5+$first)."</b><br>";
        $OldRestpowerdeff = $Restpowerdeff;
        // Schweres Raumgeschütz  gegen Schlachtschiff
        if($debug)
        echo "<font color=#00ff00>-</font> Schweres Raumgeschütz  gegen Schlachtschiff<br>";
        // Verteidiger
        if($Restpowerdeff>0)
        {
        $del = 0;
        $MaxDestruction = floor(($RestPercentdeff+0.5) * $OldRestpowerdeff * 0.5*0.2);  if($debug)
        {
            echo "<font color=#00ff00>-</font> Angreifende Schiffe: ".$this->deffending[12]." Verteidigende Schiffe:".($this->attaking[5]+$todela[5])."<br>";
            echo "<font color=#00ff00>-</font> Maximale Zerstörung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.5*0.2)=$MaxDestruction<br>";
        }
        if($first==3)
            $RestPercentdeff+=0.5;
        $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.5*0.2, $this->attaking[5]+$todela[5]), 0));
        if($first==3)
             $RestPercentdeff-= ($del / $OldRestpowerdeff / (0.5*0.2));
        $Firepower = $del/(0.5*0.2);
        $Restpowerdeff -= $Firepower;
        $todela[5]-=$del;
        }
        // Schweres Raumgeschütz  gegen Trägerschiff
        if($debug)
        echo "<font color=#00ff00>-</font> Schweres Raumgeschütz  gegen Trägerschiff<br>";
        // Verteidiger
        if($Restpowerdeff>0)
        {
        $del = 0;
        $MaxDestruction = floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.3774*0.2);
        if($debug)
        {
            echo "<font color=#00ff00>-</font> Angreifende Schiffe: ".$this->deffending[12]." Verteidigende Schiffe:".($this->attaking[6]+$todela[6])."<br>";
            echo "<font color=#00ff00>-</font> Maximale Zerstörung: floor(($RestPercentdeff+0.4) * $OldRestpowerdeff * 0.3774*0.2)=$MaxDestruction<br>";
        }       if($first==3)
            $RestPercentdeff+=0.4;
        $del= floor(max(min($MaxDestruction, $Restpowerdeff *  0.3774*0.2, $this->attaking[6]+$todela[6]), 0));
        if($first==3)
             $RestPercentdeff-= ($del / $OldRestpowerdeff /  (0.3774*0.2));
        $Firepower = $del/(0.3774*0.2);
        $Restpowerdeff -= $Firepower;
        $todela[6]-=$del;
        }
        if($first==3)
            $RestPercentdeff+=0.2;
        $first++;
    }//Schweres Raumgeschütz
    // Übrige Bomber und Jäger zerstören...
    $jaeger =  $this->attaking[0] + $todela[0];
    $bomber =  $this->attaking[1] + $todela[1];
    $traeger = $this->attaking[6] + $todela[6];
    if ( $bomber + $jaeger > $traeger*100)
    {
        $todel = $jaeger + $bomber - $traeger*100;
        $todela[0] = -round($todel*($jaeger/($jaeger + $bomber)));
        $todela[1] = -round($todel*($bomber/($jaeger + $bomber)));
    }
    //Todel verrechnen
    for($i=0;$i<14;$i++)
    {
        $this->geslostshipsatt[$i]-=$todela[$i];
        $this->geslostshipsdeff[$i]-=$todelv[$i];
        $this->attaking[$i]+=$todela[$i];
        $this->deffending[$i]+=$todelv[$i];
    }
   }
   function PrintStates()
   {
    echo "<br><center><table bgcolor=#777777 cellspacing=1>";
    echo "<tr bgcolor=#666666><td><font size=-1></font></td><td colspan=2><b><u>Verteidigende Flotte</u></b></font></td><td colspan=2><b><u>Angreifende Flotte</u></b></font></td></tr>";
    echo "<tr bgcolor=#bbbbbb><td><font size=-1><b>Typ</b><td><font size=-1><b>Vorher</b></font></td><td><font size=-1><b>Nachher</b></font></td><td><font size=-1><b>Vorher</b></font></td><td><font size=-1><b>Nachher</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Jäger</font></td><td><font size=-1>          ".$this->Olddeff[0]."</font></td><td><font size=-1>".$this->deffending[0]."</font></td><td><font size=-1>".$this->Oldatt[0]."</font></td><td><font size=-1>".$this->attaking[0]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Bomber</font></td><td><font size=-1>         ".$this->Olddeff[1]."</font></td><td><font size=-1>".$this->deffending[1]."</font></td><td><font size=-1>".$this->Oldatt[1]."</font></td><td><font size=-1>".$this->attaking[1]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Fregatte</font></td><td><font size=-1>       ".$this->Olddeff[2]."</font></td><td><font size=-1>".$this->deffending[2]."</font></td><td><font size=-1>".$this->Oldatt[2]."</font></td><td><font size=-1>".$this->attaking[2]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Zerstörer</font></td><td><font size=-1>      ".$this->Olddeff[3]."</font></td><td><font size=-1>".$this->deffending[3]."</font></td><td><font size=-1>".$this->Oldatt[3]."</font></td><td><font size=-1>".$this->attaking[3]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kreuzer</font></td><td><font size=-1>        ".$this->Olddeff[4]."</font></td><td><font size=-1>".$this->deffending[4]."</font></td><td><font size=-1>".$this->Oldatt[4]."</font></td><td><font size=-1>".$this->attaking[4]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schlachtschiff</font></td><td><font size=-1>     ".$this->Olddeff[5]."</font></td><td><font size=-1>".$this->deffending[5]."</font></td><td><font size=-1>".$this->Oldatt[5]."</font></td><td><font size=-1>".$this->attaking[5]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Trägerschiff</font></td><td><font size=-1>       ".$this->Olddeff[6]."</font></td><td><font size=-1>".$this->deffending[6]."</font></td><td><font size=-1>".$this->Oldatt[6]."</font></td><td><font size=-1>".$this->attaking[6]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kaperschiff</font></td><td><font size=-1>        ".$this->Olddeff[7]."</font></td><td><font size=-1>".$this->deffending[7]."</font></td><td><font size=-1>".$this->Oldatt[7]."</font></td><td><font size=-1>".$this->attaking[7]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schutzschiff</font></td><td><font size=-1>       ".$this->Olddeff[8]."</font></td><td><font size=-1>".$this->deffending[8]."</font></td><td><font size=-1>".$this->Oldatt[8]."</font></td><td><font size=-1>".$this->attaking[8]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Orbitalgschütz</font></td><td><font size=-1> ".$this->Olddeff[9]."</font></td><td><font size=-1>".$this->deffending[9]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Raumgeschütz</font></td><td><font size=-1>   ".$this->Olddeff[10]."</font></td><td><font size=-1>".$this->deffending[10]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Mittleres Raumgeschütz</font></td><td><font size=-1>  ".$this->Olddeff[11]."</font></td><td><font size=-1>".$this->deffending[11]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schweres Raumgeschütz</font></td><td><font size=-1>  ".$this->Olddeff[12]."</font></td><td><font size=-1>".$this->deffending[12]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Abfangjäger</font></td><td><font size=-1>            ".$this->Olddeff[13]."</font></td><td><font size=-1>".$this->deffending[13]."</font></td></tr>";
    echo "<tr bgcolor=#999999><td colspan=5></font></td>     <tr bgcolor=#dddddd><td><font size=-1>Metallexen geklaut:</font></td><td><font size=-1>    ".$this->stolenmexen."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kristallexen geklaut:</font></td><td><font size=-1>  ".$this->stolenkexen."</font></td>";
    echo "</tr></table></center>";
   }
   function PrintStatesGun()
   {
    echo "<br><center><table bgcolor=#333333 cellspacing=1>";
    echo "<tr bgcolor=#666699><td><font size=-1></font></td><td colspan=2><b><u>Verteidigende Flotte</u></b></font></td><td colspan=2><b><u>Angreifende Flotte</u></b></font></td></tr>";
    echo "<tr bgcolor=#bbbb99><td><font size=-1><b>Typ</b><td><font size=-1><b>Vorher</b></font></td><td><font size=-1><b>Nachher</b></font></td><td><font size=-1><b>Vorher</b></font></td><td><font size=-1><b>Nachher</b></font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Jäger</font></td><td><font size=-1>          ".$this->Olddeff[0]."</font></td><td><font size=-1>".$this->deffending[0]."</font></td><td><font size=-1>".$this->Oldatt[0]."</font></td><td><font size=-1>".$this->attaking[0]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Bomber</font></td><td><font size=-1>         ".$this->Olddeff[1]."</font></td><td><font size=-1>".$this->deffending[1]."</font></td><td><font size=-1>".$this->Oldatt[1]."</font></td><td><font size=-1>".$this->attaking[1]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Fregatte</font></td><td><font size=-1>       ".$this->Olddeff[2]."</font></td><td><font size=-1>".$this->deffending[2]."</font></td><td><font size=-1>".$this->Oldatt[2]."</font></td><td><font size=-1>".$this->attaking[2]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Zerstörer</font></td><td><font size=-1>      ".$this->Olddeff[3]."</font></td><td><font size=-1>".$this->deffending[3]."</font></td><td><font size=-1>".$this->Oldatt[3]."</font></td><td><font size=-1>".$this->attaking[3]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Kreuzer</font></td><td><font size=-1>        ".$this->Olddeff[4]."</font></td><td><font size=-1>".$this->deffending[4]."</font></td><td><font size=-1>".$this->Oldatt[4]."</font></td><td><font size=-1>".$this->attaking[4]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Schlachtschiff</font></td><td><font size=-1>     ".$this->Olddeff[5]."</font></td><td><font size=-1>".$this->deffending[5]."</font></td><td><font size=-1>".$this->Oldatt[5]."</font></td><td><font size=-1>".$this->attaking[5]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Trägerschiff</font></td><td><font size=-1>       ".$this->Olddeff[6]."</font></td><td><font size=-1>".$this->deffending[6]."</font></td><td><font size=-1>".$this->Oldatt[6]."</font></td><td><font size=-1>".$this->attaking[6]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Kaperschiff</font></td><td><font size=-1>        ".$this->Olddeff[7]."</font></td><td><font size=-1>".$this->deffending[7]."</font></td><td><font size=-1>".$this->Oldatt[7]."</font></td><td><font size=-1>".$this->attaking[7]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Schutzschiff</font></td><td><font size=-1>       ".$this->Olddeff[8]."</font></td><td><font size=-1>".$this->deffending[8]."</font></td><td><font size=-1>".$this->Oldatt[8]."</font></td><td><font size=-1>".$this->attaking[8]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Leichtes Orbitalgschütz</font></td><td><font size=-1> ".$this->Olddeff[9]."</font></td><td><font size=-1>".$this->deffending[9]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Leichtes Raumgeschütz</font></td><td><font size=-1>   ".$this->Olddeff[10]."</font></td><td><font size=-1>".$this->deffending[10]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Mittleres Raumgeschütz</font></td><td><font size=-1>  ".$this->Olddeff[11]."</font></td><td><font size=-1>".$this->deffending[11]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Schweres Raumgeschütz</font></td><td><font size=-1>  ".$this->Olddeff[12]."</font></td><td><font size=-1>".$this->deffending[12]."</font></td></tr>";
    echo "<tr bgcolor=#dddd99><td><font size=-1>Abfangjäger</font></td><td><font size=-1>            ".$this->Olddeff[13]."</font></td><td><font size=-1>".$this->deffending[13]."</font></td></tr>";
    //echo "<tr bgcolor=#999999><td colspan=5></font></td>     <tr bgcolor=#dddddd><td><font size=-1>Metallexen geklaut:</font></td><td><font size=-1>    ".$this->stolenmexen."</font></td>";
    //echo "<tr bgcolor=#dddddd><td><font size=-1>Kristallexen geklaut:</font></td><td><font size=-1>  ".$this->stolenkexen."</font></td>";
    echo "</tr></table></center>";
   }
   function PrintStates_ACE()
   {
    echo "<br><center><table bgcolor=#333333 cellspacing=1>";
    echo "<tr bgcolor=#666666><td><font size=-1></font></td><td colspan=2><b><u>Angreifende Flotte</u></b></font></td><td colspan=2><b><u>Verteidigende Flotte</u></b></font></td></tr>";
    echo "<tr bgcolor=#777777><td><font size=-1><b>Typ</b><td><font size=-1><b>Vorher</b></font></td><td><font size=-1><b>Nachher</b></font></td><td><font size=-1><b>Vorher</b></font></td><td><font size=-1><b>Nachher</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Jäger</font></td><td><font size=-1>          ".$this->Oldatt[0]."</font></td><td><font size=-1>".$this->attaking[0]."</font></td><td><font size=-1>".$this->Olddeff[0]."</font></td><td><font size=-1>".$this->deffending[0]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Bomber</font></td><td><font size=-1>         ".$this->Oldatt[1]."</font></td><td><font size=-1>".$this->attaking[1]."</font></td><td><font size=-1>".$this->Olddeff[1]."</font></td><td><font size=-1>".$this->deffending[1]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Fregatte</font></td><td><font size=-1>       ".$this->Oldatt[2]."</font></td><td><font size=-1>".$this->attaking[2]."</font></td><td><font size=-1>".$this->Olddeff[2]."</font></td><td><font size=-1>".$this->deffending[2]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Zerstörer</font></td><td><font size=-1>      ".$this->Oldatt[3]."</font></td><td><font size=-1>".$this->attaking[3]."</font></td><td><font size=-1>".$this->Olddeff[3]."</font></td><td><font size=-1>".$this->deffending[3]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kreuzer</font></td><td><font size=-1>        ".$this->Oldatt[4]."</font></td><td><font size=-1>".$this->attaking[4]."</font></td><td><font size=-1>".$this->Olddeff[4]."</font></td><td><font size=-1>".$this->deffending[4]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schlachtschiff</font></td><td><font size=-1>     ".$this->Oldatt[5]."</font></td><td><font size=-1>".$this->attaking[5]."</font></td><td><font size=-1>".$this->Olddeff[5]."</font></td><td><font size=-1>".$this->deffending[5]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Trägerschiff</font></td><td><font size=-1>       ".$this->Oldatt[6]."</font></td><td><font size=-1>".$this->attaking[6]."</font></td><td><font size=-1>".$this->Olddeff[6]."</font></td><td><font size=-1>".$this->deffending[6]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kaperschiff</font></td><td><font size=-1>        ".$this->Oldatt[7]."</font></td><td><font size=-1>".$this->attaking[7]."</font></td><td><font size=-1>".$this->Olddeff[7]."</font></td><td><font size=-1>".$this->deffending[7]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schutzschiff</font></td><td><font size=-1>       ".$this->Oldatt[8]."</font></td><td><font size=-1>".$this->attaking[8]."</font></td><td><font size=-1>".$this->Olddeff[8]."</font></td><td><font size=-1>".$this->deffending[8]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Orbitalgschütz</font></td><td><font size=-1></font></td><td><font size=-1></font></td><td><font size=-1>   ".$this->Olddeff[9]."</font></td><td><font size=-1>".$this->deffending[9]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Raumgeschütz</font></td><td><font size=-1></font></td><td><font size=-1></font></td><td><font size=-1> ".$this->Olddeff[19]."</font></td><td><font size=-1>".$this->deffending[10]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Mittleres Raumgeschütz</font></td><td><font size=-1></font></td><td><font size=-1></font></td><td><font size=-1>    ".$this->Olddeff[11]."</font></td><td><font size=-1>".$this->deffending[11]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schweres Raumgeschütz</font></td><td><font size=-1></font></td><td><font size=-1></font></td><td><font size=-1>    ".$this->Olddeff[12]."</font></td><td><font size=-1>".$this->deffending[12]."</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Abfangjäger</font></td><td><font size=-1></font></td><td><font size=-1></font></td><td><font size=-1>  ".$this->Olddeff[13]."</font></td><td><font size=-1>".$this->deffending[13]."</font></td></tr>";
    echo "<tr bgcolor=#555555><td colspan=5></font></td>     <tr bgcolor=#dddddd><td><font size=-1>Metallexen geklaut:</font></td><td><font size=-1>    ".$this->stolenmexen."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kristallexen geklaut:</font></td><td><font size=-1>  ".$this->stolenkexen."</font></td>";
    echo "</tr></table></center>";
   }
   function PrintOverView()
   {
    $vklost = $vmlost =$aklost = $amlost = 0;
    for($i=0;$i<15;$i++)
    {
        $vklost  += $this->kcost[$i]*$this->geslostshipsdeff[$i];
        $vmlost  += $this->mcost[$i]*$this->geslostshipsdeff[$i];
        $aklost  += $this->kcost[$i]*$this->geslostshipsatt[$i];
        $amlost  += $this->mcost[$i]*$this->geslostshipsatt[$i];
    }
    echo "<br><center><table bgcolor=#555555 cellspacing=1>";
    echo "<tr><td colspan=3 align='center'><u><b>Übersicht</b></u></font></td></tr>";
    echo "<tr bgcolor=#666666><td colspan=3 align='center'><b>Verlorene Schiffe/Geschütze</b></font></td></tr>";
    echo "<tr bgcolor=#777777><td><font size=-1><b>Typ</b></font></td><td align='center'><b>Verteidiger</b></font></td><td align='center'><b>Angreifer</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Jäger</font></td><td><font size=-1>          ".$this->geslostshipsdeff[0]."</font></td><td><font size=-1>".$this->geslostshipsatt[0]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Bomber</font></td><td><font size=-1>         ".$this->geslostshipsdeff[1]."</font></td><td><font size=-1>".$this->geslostshipsatt[1]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Fregatte</font></td><td><font size=-1>       ".$this->geslostshipsdeff[2]."</font></td><td><font size=-1>".$this->geslostshipsatt[2]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Zerstörer</font></td><td><font size=-1>      ".$this->geslostshipsdeff[3]."</font></td><td><font size=-1>".$this->geslostshipsatt[3]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kreuzer</font></td><td><font size=-1>        ".$this->geslostshipsdeff[4]."</font></td><td><font size=-1>".$this->geslostshipsatt[4]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schlachtschiff</font></td><td><font size=-1>     ".$this->geslostshipsdeff[5]."</font></td><td><font size=-1>".$this->geslostshipsatt[5]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Trägerschiff</font></td><td><font size=-1>       ".$this->geslostshipsdeff[6]."</font></td><td><font size=-1>".$this->geslostshipsatt[6]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kaperschiff</font></td><td><font size=-1>        ".$this->geslostshipsdeff[7]."</font></td><td><font size=-1>".$this->geslostshipsatt[7]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schutzschiff</font></td><td><font size=-1>       ".$this->geslostshipsdeff[8]."</font></td><td><font size=-1>".$this->geslostshipsatt[8]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Orbitalgschütz</font></td><td><font size=-1> ".$this->geslostshipsdeff[9]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Leichtes Raumgeschütz</font></td><td><font size=-1>   ".$this->geslostshipsdeff[10]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Mittleres Raumgeschütz</font></td><td><font size=-1>  ".$this->geslostshipsdeff[11]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Schweres Raumgeschütz</font></td><td><font size=-1>  ".$this->geslostshipsdeff[12]."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Abfangjäger</font></td><td><font size=-1>            ".$this->geslostshipsdeff[13]."</font></td>";
    echo "<tr bgcolor=#777777><td align='center' colspan=3><b>Kosten für Neubau</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Metall</font></td><td><font size=-1>$vmlost</font></td><td><font size=-1>$amlost</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kristall</font></td><td><font size=-1>$vklost</font></td><td><font size=-1>$aklost</font></td></tr>";
    echo "<tr bgcolor=#666666><td colspan=3 align='center'><b>Gestohlene Extraktoren</b></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Metallextraktoren:</font></td><td><font size=-1> ".$this->gesstolenexenm."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1>Kristallextraktoren:</font></td><td><font size=-1>   ".$this->gesstolenexenk."</font></td>";
    echo "</table></center>";
   }
   function PrintOverView_ACE()
   {
    $vklost = $vmlost =$aklost = $amlost = 0;
    for($i=0;$i<15;$i++)
    {
        $vklost  += $this->kcost[$i]*$this->geslostshipsdeff[$i];
        $vmlost  += $this->mcost[$i]*$this->geslostshipsdeff[$i];
        $aklost  += $this->kcost[$i]*$this->geslostshipsatt[$i];
        $amlost  += $this->mcost[$i]*$this->geslostshipsatt[$i];
    }
    echo "<br><center><table bgcolor=#555555 cellspacing=1>";
    echo "<tr><td colspan=3 align='center'><u><b>Übersicht</b></u></font></td></tr>";
    echo "<tr bgcolor=#666666><td colspan=3 align='center'><font size=-1><b>Verlorene Schiffe/Geschütze</b></font></font></td></tr>";
    echo "<tr bgcolor=#777777><td><font size=-1><font size=-1><b>Typ</b></font></font></td><td align='center'><font size=-1><b>Angreifer</b></font></td><td align='center'><font size=-1><b>Verteidiger</b></font></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Jäger</font></font></td><td><font size=-1><font size=-1>           ".$this->geslostshipsatt[0]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[0]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Bomber</font></font></td><td><font size=-1><font size=-1>          ".$this->geslostshipsatt[1]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[1]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Fregatte</font></font></td><td><font size=-1><font size=-1>        ".$this->geslostshipsatt[2]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[2]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Zerstörer</font></font></td><td><font size=-1><font size=-1>       ".$this->geslostshipsatt[3]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[3]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Kreuzer</font></font></td><td><font size=-1><font size=-1>     ".$this->geslostshipsatt[4]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[4]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Schlachtschiff</font></font></td><td><font size=-1><font size=-1>      ".$this->geslostshipsatt[5]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[5]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Trägerschiff</font></font></td><td><font size=-1><font size=-1>        ".$this->geslostshipsatt[6]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[6]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Kaperschiff</font></font></td><td><font size=-1><font size=-1>     ".$this->geslostshipsatt[7]."</font></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[7]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Schutzschiff</font></font></td><td><font size=-1><font size=-1>        ".$this->geslostshipsatt[8]."</font>x</font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[8]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Leichtes Orbitalgschütz</font></font></td><td><font size=-1></font></td><td><font size=-1><font size=-1>".$this->geslostshipsdeff[9]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Leichtes Raumgeschütz</font></font></td><td><font size=-1></font></td><td><font size=-1><font size=-1>   ".$this->geslostshipsdeff[10]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Mittleres Raumgeschütz</font></font></td><td><font size=-1></font></td><td><font size=-1><font size=-1>  ".$this->geslostshipsdeff[11]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Schweres Raumgeschütz</font></font></td><td><font size=-1></font></td><td><font size=-1><font size=-1>  ".$this->geslostshipsdeff[12]."</font></font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Abfangjäger</font></font></td><td><font size=-1></font></td><td><font size=-1><font size=-1>            ".$this->geslostshipsdeff[13]."</font></font></td>";
    echo "<tr bgcolor=#777777><td align='center' colspan=3><font size=-1><b>Kosten für Neubau</b></font></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Metall</font></td><td><font size=-1>$amlost</font></td><td><font size=-1>$amlost</font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Kristall</font></td><td><font size=-1>$aklost</font></td><td><font size=-1>$aklost</font></td></tr>";
    echo "<tr bgcolor=#666666><td colspan=3 align='center'><font size=-1><b>Gestohlene Extraktoren</b></font></font></td></tr>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Metallextraktoren:</font></font></td><td><font size=-1>    ".$this->gesstolenexenm."</font></td>";
    echo "<tr bgcolor=#dddddd><td><font size=-1><font size=-1>Kristallextraktoren:</font></font></td><td><font size=-1>  ".$this->gesstolenexenk."</font></td>";
    echo "</table></center>";
   }
}
?>

