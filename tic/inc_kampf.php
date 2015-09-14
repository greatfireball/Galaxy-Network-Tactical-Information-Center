<?

include("GNSimuclass.php");
$ticks = (isset($_POST['ticks']) ? $_POST['ticks'] : 5);

if(isset($_POST['compute']))
{
    $a[0] = $_POST['a1'];
    $a[1] = $_POST['a2'];
    $a[2] = $_POST['a3'];
    $a[3] = $_POST['a4'];
    $a[4] = $_POST['a5'];
    $a[5] = $_POST['a6'];
    $a[6] = $_POST['a7'];
    $a[7] = $_POST['a8'];
    $a[8] = $_POST['a9'];
    $v[0] = $_POST['v1'];
    $v[1] = $_POST['v2'];
    $v[2] = $_POST['v3'];
    $v[3] = $_POST['v4'];
    $v[4] = $_POST['v5'];
    $v[5] = $_POST['v6'];
    $v[6] = $_POST['v7'];
    $v[7] = $_POST['v8'];
    $v[8] = $_POST['v9'];
    $v[9] = $_POST['v10'];
    $v[10] = $_POST['v11'];
    $v[11] = $_POST['v12'];
    $v[12] = $_POST['v13'];
    $v[13] = $_POST['v14'];
    $gnsimu = new GNSimu();
    $gnsimu_m = new GNSimu_Multi();
    $f_att = new Fleet();
    $f_deff = new Fleet();
    $f_att->TicksToStay = 5;
    $f_deff->TicksToStay = 5;
    for($i=0;$i<14;$i++)
    {
        if(isset($a[$i]))
        {
            $gnsimu->attaking[$i] = $a[$i];
            $f_att->Ships[$i] = $a[$i];
        }
        if(isset($v[$i]))
        {
            $gnsimu->deffending[$i] = $v[$i];
            $f_deff->Ships[$i] = $v[$i];
        }
    }
    $gnsimu_m->AddAttFleet($f_att);
    $gnsimu_m->AddDeffFleet($f_deff);
    $gnsimu->mexen = $me = $_POST['me'];
    $gnsimu->kexen = $ke = $_POST['ke'];
}
else
{
    for($i=0;$i<15;$i++)
    {
    if($i<10)
        $a[$i]=0;
    $v[$i]=0;
    }
    $me = 0;
    $ke = 0;
}
    echo "<script type=\"text/javascript\">
        //<![CDATA[
              var newwindow=0;
              function parser(to)
              {
	               newwindow = window.open('parser.php?for='+to, 'Parser',  'menubar=no,width=300,height=230,left=200,top=200,toolbar=no,directories=no,status=no,scrollbars=auto,resizable=no');
              newwindow.focus();
              }
        //]]>
        </script>
        <center><h2>GN-Kampfsimulator v 1.2</h2></center>";
    echo "<form action=\"./main.php?modul=kampf\" method=\"post\" name=\"form1\"><input type=\"hidden\" name=\"compute\" value=\"1\" />";
    echo "<table align=\"center\" class=\"datatable\"><tr class=\"datatablehead\"><td>Schiffstyp</td><td>Verteidigende Flotte</td><td>Angreifende Flotte</td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>J&auml;ger - Leo:</td><td><input type=\"text\" name=\"v1\" value=\"$v[0]\" /></td><td><input type=\"text\" name=\"a1\" value=\"$a[0]\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Bomber - Aquilae:</td><td><input type=\"text\" name=\"v2\" value=\"$v[1]\" /></td><td><input type=\"text\" name=\"a2\" value=\"$a[1]\" /></td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>Fregatte - Fronax:</td><td><input type=\"text\" name=\"v3\" value=\"$v[2]\" /></td><td><input type=\"text\" name=\"a3\" value=\"$a[2]\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Zerst&ouml;rer - Draco:</td><td><input type=\"text\" name=\"v4\" value=\"$v[3]\" /></td><td><input type=\"text\" name=\"a4\" value=\"$a[3]\" /></td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>Kreuzer - Goron:</td><td><input type=\"text\" name=\"v5\" value=\"$v[4]\" /></td><td><input type=\"text\" name=\"a5\" value=\"$a[4]\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Schlachtschiff - Pentalin:</td><td><input type=\"text\" name=\"v6\" value=\"$v[5]\" /></td><td><input type=\"text\" name=\"a6\" value=\"$a[5]\" /></td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>Tr&auml;gerschiff - Zenit:</td><td><input type=\"text\" name=\"v7\" value=\"$v[6]\" /></td><td><input type=\"text\" name=\"a7\" value=\"$a[6]\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Kaperschiff - Cleptor:</td><td><input type=\"text\" name=\"v8\" value=\"$v[7]\" /></td><td><input type=\"text\" name=\"a8\" value=\"$a[7]\" /></td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>Schutzschiff - Cancri:</td><td><input type=\"text\" name=\"v9\" value=\"$v[8]\" /></td><td><input type=\"text\" name=\"a9\" value=\"$a[8]\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Leichtes Orbitalgesch&uuml;tz - Rubium:</td><td><input type=\"text\" name=\"v10\" value=\"$v[9]\" /></td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>Leichtes Raumgesch&uuml;tz - Pulsar:</td><td><input type=\"text\" name=\"v11\" value=\"$v[10]\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Mittlers Raumgesch&uuml;tz - Coon:</td><td><input type=\"text\" name=\"v12\" value=\"$v[11]\" /></td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>Schweres Raumgesch&uuml;tz - Centurion:</td><td><input type=\"text\" name=\"v13\" value=\"$v[12]\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Abfangj&auml;ger - Horus:</td><td><input type=\"text\" name=\"v14\" value=\"$v[13]\" /></td></tr>";
    echo "<tr class=\"fieldnormallight\"><td>Metalextraktoren:</td><td><input type=\"text\" name=\"me\" value=\"".$me."\" /></td></tr>";
    echo "<tr class=\"fieldnormaldark\"><td>Kristalextraktoren:</td><td><input type=\"text\" name=\"ke\" value=\"".$ke."\" /></td></tr>";
    echo "<tr><td></td><td><b><a href=\"javascript:parser(1)\">Parser</a></b></td><td><b><a href=\"javascript:parser(0)\">Parser</a></b></td></tr>
        <tr><td colspan=\"3\">Ticks: <select name=\"ticks\">";
    for($i=1;$i<6;$i++) {
        if($i==$ticks)
            echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>";
        else
            echo "<option value=\"".$i."\">".$i."</option>";
    }

    echo "</select><input type=\"checkbox\" name=\"preticks\"";
    if(isset($_POST['preticks']) || !isset($_POST['ticks'])) {
        echo " checked=\"checked\"";
    }
    echo " />Feuerkraft der Gesch&uuml;tze vor Ankunft der Flotte berechnen</td></tr>";
    echo "<tr><td></td></tr>";
    echo "<tr><td colspan=\"3\" align=\"center\"><input type='submit' value=\"Berechnen\" /></td></tr></table></form>";
    if($ticks<1)
        $ticks=1;
    if($ticks>5)
        $ticks=5;
if(isset($_POST['compute'])) {
    if(isset($_POST['preticks'])) {
        $gnsimu->ComputeTwoTickBefore();
        $gnsimu->PrintStatesGun();
        $gnsimu->ComputeOneTickBefore();
        $gnsimu->PrintStatesGun();

    }
    for($i=0;$i<$ticks;$i++) {
        $gnsimu->Compute($i==$ticks-1);
        $gnsimu->PrintStates();
//        $gnsimu_m->Tick(1);
    }

    $gnsimu->PrintOverView();
//    $gnsimu_m->PrintOverView();
}

?>
