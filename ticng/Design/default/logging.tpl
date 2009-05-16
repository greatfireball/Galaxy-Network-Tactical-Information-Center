<h2>Logs - {$type}</h2>
<center>
    <table class="border_table">
        <tr>
            <th>Zeit</th>
            <th>Benutzer</th>
            <th>Aktion</th>
        </tr>
    {foreach item=entry from=$logs}
        <tr>
            <td>{$entry[0]}</td>
            <td>{$entry[1]}</td>
            <td>{$entry[2]}</td>
        </tr>
    {/foreach}
    </table>
</center>
