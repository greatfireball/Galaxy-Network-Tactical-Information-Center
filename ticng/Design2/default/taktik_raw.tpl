    <h3>Raw Fleet Table</h3>
<table border=1>
    <tr>
        <th>start</th>
        <th>flotte</th>
        <th>ziel</th>
        <th>angriff</th>
        <th>rueckflug</th>
        <th>flugdauer</th>
        <th>bleibedauer</th>
        <th>eta</th>
        <th>save</th>
    </tr>
{foreach from=$flotten item=flotte}
    <tr>
        <td>{$flotte->getStartGala()}:{$flotte->getStartPlanet()}</td>
        <td>{$flotte->getFlotte()}</td>
        <td>{$flotte->getZielGala()}:{$flotte->getZielPlanet()}</td>
        <td>{if $flotte->getAngriff()}true{else}false{/if}</td>
        <td>{if $flotte->getRueckflug()}true{else}false{/if}</td>
        <td>{$flotte->getFlugdauer()}</td>
        <td>{$flotte->getBleibedauer()}</td>
        <td>{$flotte->getETA()}</td>
        <td>{if $flotte->getSave()}true{else}false{/if}</td>
    </tr>
{/foreach}
</table>
