<a name="scanTop"></a>
<center>
    <table border="0" style="margin: 5px 0px 0px 0px">
        <tr align="center">
            <td valign="top">
                <table border="0">
                    <tr align="center">
                        <th>Scan suchen</th>
                    </tr>
                    <tr align="center">
                        <td style="padding: 10px 5px 0px 5px;"><form action="{$scan_path}" method="POST">
                            <input type="text" class="text" name="gala" size="4" value="{$smarty.request.gala|default:$user->getGala()|default:$user->getGala()}" />:
                            <input type="text" class="text" name="planet" size="2" value="{$smarty.request.planet|default:$user->getPlanet()|default:$user->getPlanet()}" />
                            <input type="submit" class="button" name="scanSearch" value="Suchen" title="Suchen">
                        </form></td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table border="0">
                    <tr align="center">
                        <th>Galaxie suchen</th>
                    </tr>
                    <tr align="center">
                        <td style="padding: 10px 5px 0px 5px;"><form action="{$scan_path}" method="POST">
                            <input type="text"class="text"  name="gala" size="4" value="{$smarty.request.gala|default:$user->getGala()}" />
                            <input type="hidden" name="planet" value="0" />
                            <input type="submit" class="button" name="scanSearch" value="Suchen" title="Suchen">
                        </form></td>

                    </tr>
                </table>
            </td>
            <td valign="top">
                <table border="0">
                    <tr>
                        <th>Scan hinzuf&uuml;gen</th>
                    </tr>
                    <tr>
                        <td style="padding: 10px 5px 0px 5px;"><form action="{$scan_path}" method="POST">
                            <input type="radio" name="scanType" value="sek" checked />Sektorscan<br />
                            <input type="radio" name="scanType" value="unit" />Einheitenscan<br />
                            <input type="radio" name="scanType" value="mili" />Milit&auml;rscan<br />
                            <input type="radio" name="scanType" value="gesch" />Gesch&uuml;tzscan<br /><br />
                            <input type="text" class="text" name="gala" size="4" value="{$smarty.request.gala|default:$user->getGala()}" />:
                            <input type="text" class="text" name="planet" size="2" value="{$smarty.request.planet|default:$user->getPlanet()}" />
                            <input type="submit" class="button" name="scanAdd" value="Hinzuf&uuml;gen" title="Hinzuf&uuml;gen">
                        </form></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br />
    {if $scanError}
        <font color="red">{$scanError}</font>
    {else}
        {if $scan === false}
            <font color="red">Dies sind keine g&uuml;ltigen Koordinaten!!!</font>
        {else}
            {if $smarty.request.scanSearch}{include file=$scanShowTPL}{/if}
            {if $smarty.request.scanAdd}{include file=$scanAddTPL}{/if}
        {/if}
    {/if}
</center>
