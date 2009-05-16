<form action="{$scan_path}" method="POST">
    <input type="hidden" name="gala" value="{$smarty.request.gala}" />
    <input type="hidden" name="planet" value="{$smarty.request.planet}" />
    <input type="hidden" name="scanType" value="{$smarty.request.scanType}" />
    {if $smarty.request.scanType == "sek"}
        <table border="0">
            <tr align="middle">
                <th colspan="2" class="title">Sektorscan {$scanHeader.0.nick} ({$smarty.request.gala}:{$smarty.request.planet})</th>
            </tr>
            <tr height="10"><td colspan="2"></td></tr>
            <tr>
                <td class="bigger">Nick</td>
                <td class="bigger"><input type="text" name="nick" value="{$scanHeader.0.nick}" size="12" /></td>
            </tr>
            {foreach key=key item=value from=$sektorNamen}
                <tr>
                    <td class="bigger">{$value.1}</td>
                    <td class="bigger"><input type="text" name="{$key}" value="{$sek.0[$key]|default:0}" size="12" /></td>
                </tr>
            {/foreach}
            <tr height="10"><td colspan="2"></td></tr>
            <tr align="center">
                <td colspan="2"><input type="submit" name="scanSave" value="Speichern" /></td>
            </tr>
        </table>
    {elseif $smarty.request.scanType == "unit"}
        <table border="0">
            <tr align="middle">
                <th colspan="2" class="title">Einheitenscan {$scanHeader.0.nick} ({$smarty.request.gala}:{$smarty.request.planet})</th>
            </tr>
            <tr height="10"><td colspan="2"></td></tr>
            <tr>
                <td class="bigger">Nick</td>
                <td class="bigger"><input type="text" name="nick" value="{$scanHeader.0.nick}" size="12" /></td>
            </tr>
            {foreach key=key item=value from=$schiffNamen}
                <tr>
                    <td class="bigger">{$value.0}</td>
                    <td class="bigger"><input type="text" name="{$key}" value="{$unit.0[$key]|default:0}" size="12" /></td>
                </tr>
            {/foreach}
            <tr height="10"><td colspan="2"></td></tr>
            <tr align="center">
                <td colspan="2"><input type="submit" name="scanSave" value="Speichern" /></td>
            </tr>
        </table>
    {elseif $smarty.request.scanType == "mili"}
        <table border="0">
            <tr align="middle">
                <th colspan="4" class="title">Milit&auml;rscan {$scanHeader.0.nick} ({$smarty.request.gala}:{$smarty.request.planet})</th>
            </tr>
            <tr height="10"><td colspan="2"></td></tr>
            <tr>
                <td class="bigger">Nick</td>
                <td colspan="3"><input type="text" name="nick" value="{$scanHeader.0.nick}" size="12" tabindex="1" /></td>
            </tr>
            {foreach key=key item=value from=$schiffNamen}
                <tr>
                    <td class="bigger">{$value.0}</td>
                    <td><input type="text" name="{$key}_0" value="{$mili.0[$key].0|default:0}" size="12" tabindex="2" /></td>
                    <td><input type="text" name="{$key}_1" value="{$mili.0[$key].1|default:0}" size="12" tabindex="+9" /></td>
                    <td><input type="text" name="{$key}_2" value="{$mili.0[$key].2|default:0}" size="12" tabindex="+18" /></td>
                </tr>
            {/foreach}
            <tr height="10"><td colspan="2"></td></tr>
            <tr align="center">
                <td colspan="2"><input type="submit" name="scanSave" value="Speichern" tabindex="29" /></td>
            </tr>
        </table>
    {elseif $smarty.request.scanType == "gesch"}
        <table border="0">
            <tr align="middle">
                <th colspan="2" class="title">Gesch&uuml;tzscan {$scanHeader.0.nick} ({$smarty.request.gala}:{$smarty.request.planet})</th>
            </tr>
            <tr height="10"><td colspan="2"></td></tr>
            <tr>
                <td class="bigger">Nick</td>
                <td class="bigger"><input type="text" name="nick" value="{$scanHeader.0.nick}" size="12" /></td>
            </tr>
            {foreach key=key item=value from=$geschNamen}
                <tr>
                    <td class="bigger">{$value.0}</td>
                    <td class="bigger"><input type="text" name="{$key}" value="{$gesch.0[$key]|default:0}" size="12" /></td>
                </tr>
            {/foreach}
            <tr height="10"><td colspan="2"></td></tr>
            <tr align="center">
                <td colspan="2"><input type="submit" name="scanSave" value="Speichern" /></td>
            </tr>
        </table>
    {/if}
</form>