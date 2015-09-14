<?

include("GNSimuclass.php");
$ticks = isset($_POST['ticks'])?$_POST['ticks']:5;
$me = $_POST['me'];
$ke = $_POST['ke'];
if($_GET['doing'] == 'compute')
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
    for($i=0;$i<14;$i++)
    {
    if($a[$i])
        $gnsimu->attaking[$i] = $a[$i];
    if($v[$i])
        $gnsimu->deffending[$i] = $v[$i];
    }
    $gnsimu->mexen = $me;
    $gnsimu->kexen = $ke;
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
    echo "<script>
              var newwindow=0;
              function parser(to)
              {
	               newwindow = window.open('parser.php?for='+to, 'Parser',  'menubar=no,width=300,height=230,left=200,top=200,toolbar=no,directories=no,status=no,scrollbars=auto,resizable=no');
              newwindow.focus();
              }
        </script>
        <center><h2>GN-Kampfsimulator v 1.2</h2></center>";
    echo "<form action='./main.php?modul=kampf&doing=compute' method='POST' name='form1'>";
    echo "<table width=100% bgcolor=#999999> <tr><td><font size=-1><b>Schiffs Typ</b></font></td><td><font size=-1><b>Verteidigende Flotte</b></font></td><td><font size=-1><b>Angreifende Flotte</b></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Jäger - Leo:</font></td><td><font size=-1><input type='text' name='v1' value='$v[0]'></font></td><td><font size=-1><input type='text' name='a1' value='$a[0]'></font></td></tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Bomber - Aquilae:</font></td><td><font size=-1><input type='text' name='v2' value='$v[1]'></font></td><td><font size=-1><input type='text' name='a2' value='$a[1]'></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Fregatte - Fronax:</font></td><td><font size=-1><input type='text' name='v3' value='$v[2]'></font></td><td><font size=-1><input type='text' name='a3' value='$a[2]'></font></td></tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Zerstörer - Draco:</font></td><td><font size=-1><input type='text' name='v4' value='$v[3]'></font></td><td><font size=-1><input type='text' name='a4' value='$a[3]'></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Kreuzer - Goron:</font></td><td><font size=-1><input type='text' name='v5' value='$v[4]'></font></td><td><font size=-1><input type='text' name='a5' value='$a[4]'></font></td></tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Schlachtschiff - Pentalin:</font></td><td><font size=-1><input type='text' name='v6' value='$v[5]'></font></td><td><font size=-1><input type='text' name='a6' value='$a[5]'></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Trägerschiff - Zenit:</font></td><td><font size=-1><input type='text' name='v7' value='$v[6]'></font></td><td><font size=-1><input type='text' name='a7' value='$a[6]'></font></td></tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Kaperschiff - Cleptor:</font></td><td><font size=-1><input type='text' name='v8' value='$v[7]'></font></td><td><font size=-1><input type='text' name='a8' value='$a[7]'></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Schutzschiff - Cancri:</font></td><td><font size=-1><input type='text' name='v9' value='$v[8]'></font></td><td><font size=-1><input type='text' name='a9' value='$a[8]'></font></td></tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Leichtes Orbitalgeschütz - Rubium:</font></td><td><font size=-1><input type='text' name='v10' value='$v[9]'></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Leichtes Raumgeschütz - Pulsar:</font></td><td><font size=-1><input type='text' name='v11' value='$v[10]'></font></td></tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Mittlers Raumgeschütz - Coon:</font></td><td><font size=-1><input type='text' name='v12' value='$v[11]'></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Schweres Raumgeschütz - Centurion:</font></td><td><font size=-1><input type='text' name='v13' value='$v[12]'></font></td></tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Abfangjäger - Horus:</font></td><td><font size=-1><input type='text' name='v14' value='$v[13]'></font></td></tr>";
    echo "<tr bgcolor=#cccccc><td><font size=-1>Metalextraktoren:</font></td><td><font size=-1><input type='text' name='me' value='$me'></font></td><tr>";
    echo "<tr bgcolor=#DDDDDD><td><font size=-1>Kristalextraktoren:</font></td><td><font size=-1><input type='text' name='ke' value='$ke'></font></td></tr>";
    echo "<tr><td><font size=-1></font></td><td><font size=-1><b>&nbsp;</b></font></td><td><font size=-1><b>&nbsp;</b></font></td></tr>
	    <tr><td></td><td><b><a href=\"javascript:parser(1)\">Parser</a></b></td><td><b><a href=\"javascript:parser(0)\">Parser</a></b></td></tr>
        <tr><td colspan=3><br><font size=-1>Ticks: </font><select name='ticks'>";
    for($i=1;$i<6;$i++) {
        if($i==$ticks)
            echo "<option value='".$i."' selected>".$i."</option>";
        else
            echo "<option value='".$i."'>".$i."</option>";
    }

    echo "</select><input type='checkbox' name='before'";
    if($before || !isset($_POST['ticks'])) {
        echo "checked";
    }
    echo "><font size=-1>Feuerkraft der Geschütze vor Ankunft der Flotte berechnen</font></td></tr>";
    echo "<tr><td></td></tr>";
    echo "<tr><td colspan=3 align='center'><input type='submit' value='Berechnen'></td></tr>";
    echo "<tr><td colspan=3 align='left' style=\"font-size:7pt;\">Powered by <a href='mailto:laprican@laprican.de'>laprican</a></td></tr></table></form>";
    if($ticks<1)
        $ticks=1;
    if($ticks>5)
        $ticks=5;
if($_GET['doing'] == 'compute') {
    if($_POST['before']) {
        $gnsimu->ComputeTwoTickBefore();
        $gnsimu->PrintStatesGun();
        $gnsimu->ComputeOneTickBefore();
        $gnsimu->PrintStatesGun();

    }
    for($i=0;$i<$ticks;$i++) {
        $gnsimu->Compute($i==$ticks-1);
        $gnsimu->PrintStates();
    }
    $gnsimu->PrintOverView();
    echo "<br>";
}

?>
