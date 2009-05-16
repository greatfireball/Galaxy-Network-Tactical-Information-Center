<h1 style="margin: 0px;">Extraktoren Berechnungen</h1>
<br><br>
<form action="{$toolsPfad}" method="POST">
    <table border="0" align="center">
        <tr align="center">
            <th colspan="2">Kosten weiterer Extraktoren</th>
            <td width="100"></td>
            <th colspan="3">Wie viel Extraktoren bekomme ich?</th>
        </tr>
        <tr align="center">
            <td class="bigger">aktuelle Extr.</td>
            <td class="bigger">weitere Extr.</td>
            <td width="100"></td>
            <td class="bigger">Ticks</td>
            <td class="bigger">Met-Extr.</td>
            <td class="bigger">Kris-Extr.</td>
        </tr>
        <tr align="center">
            <td class="bigger"><input type="text" name="aktExen" value="{$smarty.post.aktExen}" title="aktuelle Extr." size="12"></td>
            <td class="bigger"><input type="text" name="zuExen" value="{$smarty.post.zuExen}" title="weitere Extr." size="12"></td>
            <td width="100"></td>
            <td class="bigger"><input type="text" name="ticks" value="{$smarty.post.ticks}" title="Ticks" size="10"></td>
            <td class="bigger"><input type="text" name="me" value="{$smarty.post.me}" title="Met-Extr." size="10"></td>
            <td class="bigger"><input type="text" name="ke" value="{$smarty.post.ke}" title="Kris-Extr." size="10"></td>
        </tr>
        <tr align="center">
            <td colspan="2" style="padding-top: 15px;"><input type="submit" name="kosten" value="Berechnen" title="Berechnen"></td>
            <td width="100"></td>
            <td colspan="3" style="padding-top: 15px;"><input type="submit" name="roiden" value="Berechnen" title="Berechnen"></td>
        </tr>
    </table>
</form>
{if $gKosten}
<br><br>
<div align="center">{if $gKosten == 1}Ein weiterer Extraktor kostet{else}{$smarty.post.zuExen} weitere Extraktoren kosten{/if} <b>{$gKosten}</b> Metall</div>
{/if}
{if $ticks}
<br>
<table border="0" align="center">
    <tr align="center">
        <th>Ticks</th>
        <th>geklaute Met Extr.</th>
        <th>geklaute Kris Extr.</th>
        <th>Rest Met Extr,</th>
        <th>Rest Kris Extr.</th>
    </tr>
    {foreach key=key item=value from=$ticks}
    <tr align="center"{if $key % 2 != 0} class="dark"{/if}>
        <td>Tick {$key}</td>
        <td>{$value.gME}</td>
        <td>{$value.gKE}</td>
        <td>{$value.me}</td>
        <td>{$value.ke}</td>
    </tr>
        {if $key % 5 == 0}<tr height="10"><td colspan="5"></td></tr>{/if}
    {/foreach}
    <tr align="center"{if ($key + 1) % 2 != 0} class="dark"{/if}>
        <td>Gesamt</td>
        <td><u>{$gesamt.gME}</u></td>
        <td><u>{$gesamt.gKE}</u></td>
        <td colspan="2" align="left" class="bigger"> => {$gesamt.exen}</td>
    </tr>
</table>
{/if}