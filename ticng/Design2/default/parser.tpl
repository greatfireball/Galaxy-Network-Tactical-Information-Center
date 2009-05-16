<center>
    <form action="{$parser}" method="POST">
    <table border="0">
        <tr align="center">
            <td colspan="2">
                <textarea name="paste" cols="70" rows="25" wrap="off">{$parser_paste}</textarea>
            </td>
        </tr>
        <tr align="center">
            <td><br><input class="checkbox" type="checkbox" name="is_irc"> aus dem IRC</td>
            <td><br><input type="checkbox" name="is_IRCkonvert">IRC-Konvertieren</td>
        </tr>
        <tr align="center">
            <td colspan="2"><input class="button" type="submit" name="paste_save" value="Speichern"></td>
        </tr>
        {if $parser_auswertung_ausgabe != "" || $parser_auswertung_error != ""}
            <tr align="center">
                <td colspan="2">
                    <br>
                    {if $parser_auswertung_error != ""}
                        <font color="#FF0000">{$parser_auswertung_error}</font>
                    {else}
                        {$parser_auswertung_ausgabe}
                    {/if}
                </td>
            </tr>
        {/if}
        {if $parser_ausgabe != "" || $parser_error != ""}
            <tr align="center">
                <td colspan="2">
                    <br>
                    {if $parser_error != ""}
                        <font color="#FF0000">{$parser_error}</font>
                    {else}
                        {$parser_ausgabe}
                    {/if}
                </td>
            </tr>
        {/if}
    </table>
    </form>
</center>
