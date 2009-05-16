<center>
<h1 style="margin: 5px;">Planen</h1>
<br><br>
{if $ma !== false}
    <form action="{$attplanerPfad}" method="POST">
        <table align="center">
            <tr align="center">
                <th>Ziel</th>
                <th colspan="2"">Freigabe</th>
                <th colspan="2">Abflug</th>
                <th>MA</th>
                <td></td>
            </tr>
            <tr align="center">
                <td class="bigger">Gala : Planet</td>
                <td class="bigger">Datum</td>
                <td class="bigger">Zeit</td>
                <td class="bigger">Datum</td>
                <td class="bigger">Zeit</td>
                <td class="bigger" colspan="2"></td>
            </tr>
            <tr height="10"><td colspan="5" style="border: none;"></td></tr>
            <tr align="center">
                <td class="bigger">
                    <input type="text" name="zielGala" size="4" maxlength="4"> : 
                    <input type="text" name="zielPlanet" size="2" maxlength="2">
                </td>
                <td class="bigger">
                    <select name="freigabeDatum" size="1">
{foreach key=key item=value from=$datum}
                        <option value="{$value}">{$value}</option>
{/foreach}
                    </select>
                </td>
                <td class="bigger"><input type="text" name="freigabeZeit" value="{$jetzt}" size="4"></td>
                <td class="bigger">
                    <select name="abflugDatum" size="1">
{foreach key=key item=value from=$datum}
                        <option value="{$value}">{$value}</option>
{/foreach}
                    </select>
                </td>
                <td class="bigger"><input type="text" name="abflugZeit" value="{$jetzt}" size="4"></td>
                <td class="bigger">
                    <select name="ma" size="1">
{foreach key=key item=value from=$ma}
    {if $value.aendern == 1}
                        <option value="{$value.id}">{$value.id} - {$value.planer->getNick()} ({$value.planer->getGalaxie()}:{$value.planer->getPlanet()})</option>
    {/if}
{/foreach}
                    </select>
                </td>
                <td class="bigger"><input type="submit" name="ziel_erfassen" value="Erfassen"></td>
            </tr>
        </table>
    </form>
{else}
    <h3>Es wurde kein Massenatt zum konfigurieren gefunden.</h3>
{/if}
</center>