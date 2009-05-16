<center>
    <h1 style="margin-top: 0px; margin-bottom: 20px;">Scan Blocks</h1>
    <form action="{$scanPfad}" method="POST">
        <span style="margin-right: 20px;">
            <input type="text" class="text" name="svs" size="6" value=""> SVs
        </span>
        <span style="margin-right: 20px;">
            <select name="scantyp" size="1">
                <option value="{$scans.sek}" checked>Sektorscan</option><br>
                <option value="{$scans.unit}">Einheitenscan</option><br>
                <option value="{$scans.mili}">Milit&auml;rscan</option><br>
                <option value="{$scans.gesch}">Gesch&uuml;tzscan</option><br>
                <option value="{$scans.news}">Newsscan</option>
            </select>
        </span>
        <span style="margin-right: 40px;">
            <input type="text" class="text" name="gala" size="4" value=""> <font size="+1">:</font>
            <input type="text" class="text" name="planet" size="2" value=""> Koordinaten
        </span>
        <span>
            <input type="submit" class="button" name="eintragen" value="Eintragen">
        </span>
    </form>
    <br><br>
{if $blocks !== false}
    <table border="0">
        <tr>
            <th class="bigger">User</th>
            <th class="bigger">Zeit</th>
            <th class="bigger">SVs</th>
            <th class="bigger">Scantyp</th>
            <th class="bigger">Koords</th>
        </tr>
    {foreach key=key item=value from=$blocks}
        <tr{if $key % 2 != 0} class="dark"{/if} align="center">
            <td class="bigger">{$value.user->getNick()} ({$value.user->getKoords()})</td>
            <td class="bigger">{$value.zeit}</td>
            <td class="bigger">{$value.svs}</td>
            <td class="bigger">{$value.scantyp}</td>
            <td class="bigger">{$value.gala}:{$value.planet}</td>
        </tr>
    {/foreach}
    </table>
{else}
    <h3>Keine Scanblocks verf&uuml;gbar.</h3>
{/if}
</center>