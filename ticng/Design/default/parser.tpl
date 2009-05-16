<form action="{$parserPfad}" method="POST">
    <table border="0" align="center">
        <tr align="center">
            <td colspan="2"><textarea name="paste" cols="70" rows="20" wrap="off">{$parserPaste}</textarea></td>
        </tr>
        <tr align="center">
            <td style="padding-top: 10px;"><input class="checkbox" type="checkbox" name="is_irc"><span {popup caption="Aus dem IRC" text=$parserHilfe.fromIRC}>aus dem IRC</span></td>
            <td style="padding-top: 10px;"><input type="checkbox" name="is_IRCkonvert"><span {popup caption="IRC-Konvertieren" text=$parserHilfe.ircKonvert}>IRC-Konvertieren</span></td>
        </tr>
        <tr align="center">
            <td colspan="2"><input class="button" type="submit" name="paste_save" value="Speichern"></td>
        </tr>
{if $parserAusgabe != ""}
        <tr align="center">
    {foreach key=key item=value from=$parserAusgabe}
            <td colspan="2">{$value}</td>
    {/foreach}
        </tr>
{/if}
    </table>
</form>