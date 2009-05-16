    <center>
        <h2>UserMan</h2>
        <br>
        <table border=1 width=96%>
        {foreach key=meta item=allies from=$meten}
        {foreach key=alli item=galen from=$allies}
            <tr>
                <td>{$meta}</td>
                <td>{$alli}</td>
                {foreach item=gala from=$galen}
                <td>{$gala}</td>
                {/foreach}
            </tr>
        {/foreach}
        {/foreach}
        </table>
    </center>
    <br>
