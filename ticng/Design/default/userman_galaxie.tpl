<h2>Galaxie {$gala}</h2>
<br>
<div align="center">
    <form method="post">
        <p style="margin-bottom: 3em;">
            Die Galaxie {$gala} befindet sich in der Allianz:
            <select name="gala_allianz">
            {foreach from=$allis item=a}
                <option value="{$a->getId()}" {if $alli->getName() == $a->getName()}selected{/if}>{$a->getName()}</option>
            {/foreach}
            </select>
            <input class="button" type="submit" value="speichern">
        </p>
    </form>
    <form method="post">
        <table class="border_table">
            <tr>
                <th>Planet</th>
                <th>Nick</th>
                <th>Rang</th>
                <th>Rolle</th>
                <th>Letzte Aktivit&auml;t</th>
                <th>gesperrt</th>
                <th>neues PW</th>
                <th>L&ouml;schen</th>
                <th>Bearbeiten</th>
            </tr>
        {foreach from=$users item=u}
            <tr>
                <td style="text-align: right;">{$u.planet}</td>
                <td>
                    {if $u.canSetNick}
                    <input type="text" name="user_nick_{$u.id}" value="{$u.nick}" maxlength="50" size="20">
                    {else}{$u.nick}{/if}
                </td>
                <td>
                    {if $u.canSetRang}
                    <select name="user_rang_{$u.id}">
                        <option {if $u.rang == "Member"}selected{/if}>Member</option>
                        <option {if $u.rang == "VGC"}selected{/if}>VGC</option>
                        <option {if $u.rang == "GC"}selected{/if}>GC</option>
                        <option {if $u.rang == "VHC"}selected{/if}>VHC</option>
                        <option {if $u.rang == "HC"}selected{/if}>HC</option>
                    </select>
                    {else}{$u.rang}{/if}
                </td>
                <td>
                    {if $u.canSetRole}
                    <select name="user_role_{$u.id}">
                        {foreach from=$roles item=r}
                        <option value="{$r.id}" {if $u.role == $r.id}selected{/if}>{$r.name}</option>
                        {/foreach}
                    </select>
                    {else}{$u.role}{/if}
                </td>
                <td>{$u.lastactive}</td>
                <td>
                    {if $u.canSetBan}
                    <select name="user_banned_{$u.id}">
                        <option value="1" {if $u.banned}selected{/if}>Ja</option>
                        <option value="0" {if !$u.banned}selected{/if}>Nein</option>
                    </select>
                    {else}{if $u.banned}Ja{else}Nein{/if}{/if}
                <td>
                    {if $u.canSetPW}
                    <a href="wrapper.php?mod=UserMan&amp;pw={$u.id}">neues PW</a><!-- FIXME: button -->
                    {/if}
                </td>
                <td>
                    {if $u.canDelete}<input class="button" type="submit" name="delete_{$u.id}" value="l&ouml;schen">{/if}
                </td>
                <td><a href="javascript:alert('fixme');">bearbeiten</a>
            </tr>
        {/foreach}
        </table>
        <br>
        <input class="button" type="submit" value="speichern">
        <input type="hidden" name="galaxie_post" value="1">
    </form>
</div>
