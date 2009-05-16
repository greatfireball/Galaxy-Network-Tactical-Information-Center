<h2>Rechteverwaltung</h2>
<br>
<div align="center">
    <table class="border_table">
        <tr>
            <th colspan="2">Ränge</th>
        </tr>
    {foreach from=$rang item=r}
        <tr>
            <td>{$r.name}</td>
            <td><a href="wrapper.php?mod=Right&amp;role={$r.id}">Editieren</a></td>
        </tr>
    {/foreach}
    </table>

    <br><br>

    <table class="border_table">
        <tr>
            <th colspan="3">Rollen</th>
        </tr>
    {foreach from=$role item=r}
        <tr>
            <td>{$r.name}</td>
            <td><a href="wrapper.php?mod=Right&amp;role={$r.id}">Editieren</a></td>
            <td>Loeschen</td>
        </tr>
    {/foreach}
    </table>
</div>
