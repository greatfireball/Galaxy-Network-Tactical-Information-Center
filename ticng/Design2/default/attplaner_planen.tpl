<cetner>
    <h1 style="margin: 5px;">Planen</h1>
    <br><br>
    <form action="{$attplanerPfad}" method="POST">
        <table align="center">
            <tr align="center">
                <td class="border">Ziel</td>
                <td colspan="2" class="border">Freigabe</td>
                <td colspan="2" class="border">Abflug</td>
            </tr>
            <tr align="center">
                <td class="bigger border">Gala : Planet</td>
                <td class="bigger border">Datum</td>
                <td class="bigger border">Zeit</td>
                <td class="bigger border">Datum</td>
                <td class="bigger border">Zeit</td>
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
                        <option name="{$value}">{$value}</option>
{/foreach}
                    </select>
                </td>
                <td class="bigger"><input type="text" name="freigabeZeit" value="{$jetzt}" size="4"></td>
                <td class="bigger">
                    <select name="abflugDatum" size="1">
{foreach key=key item=value from=$datum}
                        <option name="{$key}">{$value}</option>
{/foreach}
                    </select>
                </td>
                <td class="bigger"><input type="text" name="abflugZeit" value="{$jetzt}" size="4"></td>
            </tr>
        </table>
    </form>
</center>