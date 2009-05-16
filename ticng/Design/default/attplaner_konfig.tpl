<cetner>
    {$error}
    <h1 style="margin: 5px;">Konfigurieren</h1>
    <br><br>
    <table border="0" align="center"><form action="{$attplanerPfad}" method="POST">
        <tr align="center">
            <td colspan="3" class="title">Massenatt erstellen</td>
        </tr>
        <tr height="10"><td colspan="2"></td></tr>
        <tr>
            <th>F&uuml;r?</th>
            <th>Auswahl</th>
            <th>Attplaner</th>
        </tr>
        <tr>
            <td class="bigger">
                <select name="attTyp" size="1">
{foreach key=key item=value from=$attTyp}
                    <option value="{$key}">{$value}</option>
{/foreach}
                </select>
            </td>
            <td class="bigger">
                <select name="auswahl" size="1">
{foreach key=key item=value from=$auswahl}
                    <option value="{$key}">{$value}</option>
{/foreach}
                </select>
            </td>
            <td class="bigger">
                <!--<input type="hidden" name="planer" value="{$user->getID()}">{$user->getNick()} ({$user->getKoords()})-->
                <select name="planer" size="1">
{foreach key=key item=value from=$attPlaner}
                    <option value="{$value->getID()}">{$value->getNick()} ({$value->getKoords()})</option>
{/foreach}
                </select>
            </td>
        </tr>
        <tr height="10"><td colspan="2"></td></tr>
        <tr align="center">
            <td colspan="3"><input type="submit" name="ma_erstellen" value="Erstellen"></td>
        </tr>
    </form></table>
{if $ma != false}
    <br><br>
    <table border="0" align="center">
        <tr>
            <th>ID</th>
            <th>Planer</th>
            <th>Für</th>
            <th>Auswahl</th>
            <th></th>
        </tr>
{foreach key=key item=value from=$ma}
        <tr><form action="{$attplanerPfad}" method="POST">
            <input type="hidden" name="ma" value="{$value.id}">
            <td class="bigger">{$value.id}</td>
            <td class="bigger">{$value.planer->getNick()} {$value.planer->getKoords()}</td>
            <td class="bigger">{$value.attTyp}</td>
            <td class="bigger">{$value.auswahl}</td>
            <td class="bigger" align="center">
    {if $value.aendern}
                <input type="submit" name="ma_loeschen" value="L&ouml;schen">
    {else}
                L&ouml;schen
    {/if}
            </td>
        </form></tr>
{/foreach}
{/if}
    </table>
</center>