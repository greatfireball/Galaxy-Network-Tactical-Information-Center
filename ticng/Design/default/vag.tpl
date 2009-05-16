<center>
    <table border="0"><form action="{$vag}" method="GET">
        <input type="hidden" name="mod" value="Vag">
        <tr>
            <td align="center"><h2><u>V</u>erlust<u>a</u>us<u>g</u>leich-Rechner</h2></td>
        </tr>
        <tr height="10"><td></td></tr>
        <tr><td>
            <table border="0">
                <tr>
                    <th>Schiffe/Deff</th>
                    <th>Verluste</th>
                    <th>Metall</th>
                    <th>Kristall</th>
                </tr>
                {foreach key=key item=value from=$vagAusgabe}
                    <tr{if $key % 2 != 0} class="dark"{/if}>
                        <td align="left"><span {popup caption=$value.0 text=$value.5}>{$value.0}</span></td>
                        <td align="center"><input class="text" type="text" name="{$value.1}" value="{$value.2}" size="8"></td>
                        <td align="center">{$value.3}</td>
                        <td align="center">{$value.4}</td>
                    </tr>
                {/foreach}
                <tr height="20"><td></td></tr>
                <tr>
                    <td colspan="2" align="right">Gesammte Verluste:</td>
                    <td align="center">{$verluste.met.all}</td>
                    <td align="center">{$verluste.kris.all}</td>
                </tr>
                <tr>
                    <td colspan="2" align="right">Verlustausgleich:</td>
                    <td align="center"><font color="#008000"><u>{$verluste.met.half}</u></font></td>
                    <td align="center"><font color="#008000"><u>{$verluste.kris.half}</u></font></td>
                </tr>
            </table>
        </td></tr>
        <tr height="10"><td></td></tr>
        <tr align="center">
            <td>
                <input type="submit" class="button" name="vag_save" value="Auswerten" title="Auswerten">
                {if $vagLink != ""}
                    <input
                        type="button"
                        class="button"
                        onclick="popup({$IRCPopup.width},{$IRCPopup.height},'{$IRCPopup.text}');"
                        value="IRC-Auswertung"
                        title="IRC-Auswertung"
                    >
                {/if}
            </td>
        </tr>
        {if $vagLink != ""}
            <tr height="10"><td></td></tr>
            <tr align="center">
                <td><a href="{$vagLink}" target="_blank" title="VAG-Link">VAG-Link</a></td>
            </tr>
        {/if}
    </form></table>
</center>
