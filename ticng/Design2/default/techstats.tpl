<h2>geladene Module</h2>
<br>
<center>
    <table class="border_table">
        <tr>
            <th>Modul</th>
            <th>Version</th>
            <th>Beschreibung</th>
            <th>Autoren</th>
        </tr>
{foreach item=mod from=$module}
        <tr>
            <td><b><a href="wrapper.php?mod={$mod.name}">{$mod.name}</a></b></td>
            <td>{$mod.version}</td>
            <td>{$mod.desc}</td>
            <td>
            {foreach item=author from=$mod.authors name=authors}
                {$author->Name} ({$author->Nick})<br>
                <a href="mailto:{$author->Mail}">{$author->Mail}</a>{if !$smarty.foreach.authors.last}<br>{/if}
            {/foreach}
            </td>
        </tr>
{/foreach}
    </table>
</center>
