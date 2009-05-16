<h1 style="margin: 0px;">Ziele</h1>
<br>
{if $ziele !== false}
<div align="center">
    {foreach key=key item=value from=$ziele}
        {assign var="key1" value=$key-1}
        {if $key > 0 && $ziele[$key1].ziel->getGalaxie() != $value.ziel->getGalaxie()}<br>{/if}
        <a href="#{$value.ziel->getKoords()}" style="color: #000000;" title="{$value.ziel->getNick()}">{$value.ziel->getKoords()}</a>
    {/foreach}
</div>
<br>
<table borde="1" align="center">
    <tr>
        <th class="bigger">Ziel</th>
        <th class="bigger">Flotten</th>
        <th class="bigger" colspan="2">Infos</th>
        <th class="bigger">Text</th>
        <td></td>
        <td></td>
    </tr>
    {foreach key=key item=value from=$ziele}
    <tr{if $key % 2 != 0} class="dark"{/if}>
        <td class="bigger" valign="top"{if $value.abflugNow !== false} style="background-color: #78BB78;"{/if}>
            <a name="{$value.ziel->getKoords()}"></a>
            {$value.ziel->geTNick()} ({$value.ziel->getKoords()})
            <span {popup caption="Scans" text=$value.zielScans.unit width="125"}> [{$value.zielScans.alle}]</span>
            <table border="0" style="font-size : 11px;">
        {foreach key=key2 item=value2 from=$sektorNamen}
            {if $key2 != "ast"}
                <tr>
                    <td>{$value2.1}</td>
                    <td>{$value.zielScans.sek[$key2]}</td>
                </tr>
            {/if}
        {/foreach}
            </table>
        </td>
        {if $value.flotten !== false}
          <td class="bigger" valign="top">
            {foreach key=key2 item=value2 from=$value.flotten}
                {$value2.user->getNick()} ({$value2.user->getKoords()}) #{$value2.flotte}
            {/foreach}
        {else}
          <td class="bigger" valign="middle">
        {/if}
            <form action="{$attplanerPfad}" method="POST">
                <input type="hidden" name="zielId" value="{$value.id}">
                <input type="submit" class="button" name="flotte" value="Flotte 1" title="Flotte 1" style="width: 60px; margin-bottom: 5px;"><br>
                <input type="submit" class="button" name="flotte" value="Flotte 2" title="Flotte 2" style="width: 60px;">
            </form>
        </td>
        <td class="bigger" valign="top" style="font-size: 11px;">
        {if $value.zielScans.alle != "-"}
            Punkte:<br>
            4x - {$value.zielScans.sek.punkte4}<br>
            6x - {$value.zielScans.sek.punkte6}
        {else}
            Kein Sektorscan vorhanden.
        {/if}
        </td>
        <td class="bigger" valign="top" style="font-size : 11px;">
            Freigabe:<br>
            {$value.freigabe}<br>
            Abflug:<br>
            {$value.abflug}
        </td>
        <form action="{$attplanerPfad}" method="POST">
            <td class="bigger" align="center">
        {if $value.isAllowed >= 1}
                <textarea id="text{$value.id}" name="text" cols="20" rows="5" wrap="off">{$value.text}</textarea>
        {else}
                {$value.text|default:'-'}
        {/if}
                <input type="hidden" name="ziel_id" value="{$value.id}">
            </td>
        {if $value.isAllowed >= 1}
            <td class="bigger" align="left">
                <input type="submit" class="button" name="ziel_speichern" value="Speichern" title="Speichern" style="width: 70px;">
            {if $value.isAllowed == 2}
                <br><input type="submit" class="button" name="ziel_loeschen" value="L&ouml;schen" title="L&ouml;schen" style="width: 70px; margin-top: 5px;">
            {/if}
            </td>
        {else}
            <td class="blank"></td>
        {/if}
        </form>
        <td>
            <a href="#top">
                <img align="right" src="Design/default/images/arrow_up.gif" width="14" height="10" border="0" alt="top" title="top">
            </a>
        </td>
    </tr>
    {/foreach}
</table>
{else}
<h3>Zur Zeit sind keine Ziele vorhanden.</h3>
{/if}