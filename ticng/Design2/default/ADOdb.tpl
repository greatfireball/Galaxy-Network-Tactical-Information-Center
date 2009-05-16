<h2>Failed SQL Statements</h2>
<br>
<table class="border_table">
    <tr>
        <th>Modul / Klasse</th>
        <th>SQL</th>
        <th>original SQL</th>
        <th>Fehlermeldung</th>
        <th>Zeitpunkt</th>
    </tr>
{foreach item=row from=$errors}
    <tr>
        <td>{$row.module}</td>
        <td>{$row.sql}</td>
        <td>{$row.sql_orig}</td>
        <td>{$row.msg}</td>
        <td>{$row.time}</td>
    </tr>
{/foreach}
</table>
