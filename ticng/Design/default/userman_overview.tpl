<div align="center">
    <h2>Galaxie&uuml;bersicht</h2>
    <br>
    <table border=1>
    {foreach key=meta item=allies from=$meten}
    {foreach key=alli item=galen from=$allies}
        <tr>
            <td>{$meta}</td>
            <td>{$alli}</td>
            {foreach item=gala from=$galen}
            <td><a href="wrapper.php?mod=UserMan&amp;gala={$gala}">{$gala}</a></td>
            {/foreach}
        </tr>
    {/foreach}
    {/foreach}
    </table>
</div>
