<a name="scanTop"></a>
<table border="0" align="center">
    <tr align="center">
        <th>Scan suchen</th>
        <th>Galaxie suchen</th>
        <th>Scan hinzuf&uuml;gen</th>
    </tr>
    <tr>
        <td style="padding: 10px 5px 0px 5px;" align="center" valign="top"><form action="{$scanPfad}" method="POST">
            <input type="text" name="gala" size="4" value="{$smarty.request.gala|default:$user->getGalaxie()}"> <font size="+1">:</font>
            <input type="text" name="planet" size="2" value="{$smarty.request.planet|default:$user->getPlanet()}">
            <input type="submit" name="scanSearch" value="Suchen" title="Suchen">
        </form></td>
        <td style="padding: 15px 5px 0px 5px;" align="center" valign="top"><form action="{$scanPfad}" method="POST">
            <input type="text" name="gala" size="4" value="{$smarty.request.gala|default:$user->getGalaxie()}">
            <input type="hidden" name="planet" value="0">
            <input type="submit" name="scanSearch" value="Suchen" title="Suchen">
        </form></td>
        <td style="padding: 15px 5px 0px 5px;" align="left" valign="top"><form action="{$scanPfad}" method="POST">
            <input type="radio" name="scantyp" value="{$scans.sek}" checked>Sektorscan<br>
            <input type="radio" name="scantyp" value="{$scans.unit}">Einheitenscan<br>
            <input type="radio" name="scantyp" value="{$scans.mili}">Milit&auml;rscan<br>
            <input type="radio" name="scantyp" value="{$scans.gesch}">Gesch&uuml;tzscan<br>
            <input type="text" name="gala" size="4" value="{$smarty.request.gala|default:$user->getGalaxie()}"> <font size="+1">:</font>
            <input type="text" name="planet" size="2" value="{$smarty.request.planet|default:$user->getPlanet()}">
            <input type="submit" name="scanAdd" value="Hinzuf&uuml;gen" title="Hinzuf&uuml;gen">
        </form></td>
    </tr>
</table>
<center>
    <br>
    {if $scan === false}
        <font color="red">Dies sind keine g&uuml;ltigen Koordinaten!!!</font>
    {else}
        {if $smarty.request.scanSearch}{include file=$scanShowTPL}{/if}
        {if $smarty.request.scanAdd}{include file=$scanAddTPL}{/if}
    {/if}
</center>
