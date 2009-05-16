    <tr>
        <th>User</th>
        <th colspan="2">Userfleet</th>
        <th colspan="2">Incomings</th>
        <th colspan="2">Deff</th>
    </tr>
{foreach from=$screen.flotten item=user}
    <tr class="inc_{$user.save}">
        <td>{$user.user}</td>
        <td>
    {foreach from=$user.outgoing item=out name=out1}
            <span class="{if $out->getAngriff()}att{else}deff{/if}">
                {$out->toTaktikString(true)} #{$out->getFlotte(true)}
            </span>
            {if !$smarty.foreach.out1.last}<br>{/if}
    {/foreach}
        </td>
        <td class="eta">
    {foreach from=$user.outgoing item=out name=out2}
            {$out->getETA()}
            {if !$smarty.foreach.out2.last}<br>{/if}
    {/foreach}
        </td>
        <td>
    {foreach from=$user.incs item=inc name=incs1}
            <span class="inc_{if $inc->getSafe()}save{else}unsave{/if}">
                {$inc->toTaktikString(false)} #{$inc->getFlotte(true)}
            </span>
            {if !$smarty.foreach.incs1.last}<br>{/if}
    {/foreach}
        </td>
        <td class="eta">
    {foreach from=$user.incs item=inc name=incs2}
            <span class="inc_{if $inc->getSafe()}save{else}unsave{/if}">
                {$inc->getETA()}
            </span>
            {if !$smarty.foreach.incs2.last}<br>{/if}
    {/foreach}
        </td>
        <td>
    {foreach from=$user.deff item=deff name=deff1}
            {$deff->toTaktikString(false)} #{$deff->getFlotte(true)}
            {if !$smarty.foreach.deff1.last}<br>{/if}
    {/foreach}
        </td>
        <td class="eta">
    {foreach from=$user.deff item=deff name=deff2}
            {$deff->getETA()}
            {if !$smarty.foreach.deff2.last}<br>{/if}
    {/foreach}
        </td>
    </tr>
{/foreach}
