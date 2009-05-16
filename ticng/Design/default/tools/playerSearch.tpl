<h1 style="margin: 0px;">Suche nach Galaxy-Network Playern</h1>
<br><br>
<form action="{$toolsPfad}" method="POST">
    <div align="center">
        <span style="margin-right: 20px;">
            <input type="text" class="text" name="nick" value="{$smarty.request.nick|default:$user->getNick()}"> 
            <input type="submit" class="button" name="koordsSearch" value="Suchen">
        </span>
        <span style="margin-right: 20px;">-</span>
        <span>
            <input type="text" class="text" name="gala" value="{$smarty.request.gala|default:$user->getGalaxie()}" size="4"> <font size="+1">:</font>
            <input type="text" class="text" name="planet" value="{$smarty.request.planet|default:$user->getPlanet()}" size="2"> 
            <input type="submit" class="button" name="playerSearch" value="Suchen">
        </span>
    </div>
    <br><br>
{if $player != false}
    <table border="0" align="center">
        <tr align="center">
            <th class="bigger">Nick</th>
            <th class="bigger">Koords</th>
        </tr>
    {foreach key=key item=value from=$player}
        <tr align="left"{if $key % 2 != 0} class="dark"{/if}>
            <td class="bigger">{$value->getNick()}</td>
            <td class="bigger">{$value->getKoords()}</td>
        </tr>
    {/foreach}
    </table>
{/if}
</form>
