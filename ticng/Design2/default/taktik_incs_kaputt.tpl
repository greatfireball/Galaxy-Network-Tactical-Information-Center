    <h2>Incommings</h2>
<table class="border_table">
    <tr>
        <th>User</th>
        <th colspan="2">Userfleet</th>
        <th colspan="2">Incommings</th>
        <th colspan="2">Deff</th>
    </tr>
{section name=iter loop=$taktik}
    <tr>
        <td>{$taktik[iter].user}</td>
        <td>
    {foreach $taktik[iter].outgoing item=out name=outgoing1}
            {$out->toTaktikString()}{if !$smarty.foreach.outgoing1.last}<br>{/if}
    {/foreach}
        <td>
        <td>
    {foreach $taktik[iter].outgoing item=out name=outgoing2}
            {$out->getETA()}{if !$smarty.foreach.outgoing2.last}<br>{/if}
    {/foreach}
        <td>
        <td>
    {foreach $taktik[iter].incs item=inc name=incs1}
            {$inc->toTaktikString()}{if !$smarty.foreach.incs1.last}<br>{/if}
    {/foreach}
        <td>
        <td>
    {foreach $taktik[iter].incs item=inc name=incs2}
            {$inc->getETA()}{if !$smarty.foreach.incs2.last}<br>{/if}
    {/foreach}
        <td>
        <td>
    {foreach $taktik[iter].deff item=deff name=deff1}
            {$deff->toTaktikString()}{if !$smarty.foreach.deff1.last}<br>{/if}
    {/foreach}
        <td>
        <td>
    {foreach $taktik[iter].deff item=deff name=deff2}
            {$inc->getETA()}{if !$smarty.foreach.deff2.last}<br>{/if}
    {/foreach}
        <td>
    </tr>
{/section}
</table>
