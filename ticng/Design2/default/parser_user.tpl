<center><form action="{$parser}" method="POST">
    {if isset($smarty.post.user_create[0].nick) && !isset($smarty.post.create_yes) && !isset($smarty.post.create_no)}
        M&ouml;chten sie volgende User anlegen?<br /><br />
        <table border="0">
            <tr align="center">
                <th>Nick</th>
                <th>Galaxie</th>
                <th>Planet</th>
            </tr>
        {foreach key=key item=value from=$smarty.post.user_create}
            <tr align="center">
                <td>{$value.nick}</td>
                <td>{$value.gala}</td>
                <td>{$value.planet}</td>
            </tr>
        {/foreach}
            <tr height="10"><td colspan="3"></td></tr>
            <tr align="center">
                <td colspan="3">
                    <input type="hidden" name="user_create[0]" value="{$smarty.post.user_create}">
                    {foreach key=key0 item=value0 from=$smarty.post.user_create}
                        {foreach key=key1 item=value1 from=$value0}
                            <input type="hidden" name="user_create[{$key0}][{$key1}]" value="{$value1}">
                        {/foreach}
                    {/foreach}
                    <input type="submit" name="create_yes" value="Ja">
                    <input type="submit" name="create_no" value="Nein">
                </form></td>
            </tr>
        </table>
    {/if}

    {if isset($smarty.post.user_move[0].nick) && !isset($smarty.post.move_yes) && !isset($smarty.post.move_no)}
        {if isset($smarty.post.user_create[0].nick) && !isset($smarty.post.create_yes) && !isset($smarty.post.create_no)}
            <input type="hidden" name="userTPL" value="1">
            <br><br><br>
        {/if}
        {if isset($smarty.post.user_del[0].nick) && !isset($smarty.post.del_yes) && !isset($smarty.post.del_no)}
            <input type="hidden" name="userTPL" value="1">
        {/if}
        M&ouml;chten sie volgende User verschieben?<br /><br />
        <table border="0">
            <tr align="center">
                <th>Nick</th>
                <th>Galaxie</th>
                <th>Planet</th>
                <th>Neue Galaxie</th>
                <th>Neuer Planet</th>
            </tr>
        {foreach key=key item=value from=$smarty.post.user_move}
            <tr align="center">
                <td>{$value.nick}</td>
                <td>{$value.gala}</td>
                <td>{$value.planet}</td>
                <td>{$value.new_gala}</td>
                <td>{$value.new_planet}</td>
            </tr>
        {/foreach}
            <tr height="10"><td colspan="5"></td></tr>
            <tr align="center">
                <td colspan="5">
                    <input type="hidden" name="user_move[0]" value="{$smarty.post.user_move}">
                    {foreach key=key0 item=value0 from=$smarty.post.user_move}
                        {foreach key=key1 item=value1 from=$value0}
                            <input type="hidden" name="user_move[{$key0}][{$key1}]" value="{$value1}">
                        {/foreach}
                    {/foreach}
                    <input type="submit" name="move_yes" value="Ja">
                    <input type="submit" name="move_no" value="Nein">
                </td>
            </tr>
        </table>
    {/if}

    {if isset($smarty.post.user_del[0].nick) && !isset($smarty.post.del_yes) && !isset($smarty.post.del_no)}
        {if isset($smarty.post.user_move[0].nick) && !isset($smarty.post.move_yes) && !isset($smarty.post.move_no)}
            <input type="hidden" name="userTPL" value="1">
            <br><br><br>
        {/if}
        {if isset($smarty.post.user_create[0].nick) && !isset($smarty.post.create_yes) && !isset($smarty.post.create_no)}
            <input type="hidden" name="userTPL" value="1">
            <br><br><br>
        {/if}
        M&ouml;chten sie volgende User wirklich l&ouml;schen?<br /><br />
        <table border="0">
            <tr align="center">
                <th>Nick</th>
                <th>Galaxie</th>
                <th>Planet</th>
            </tr>
        {foreach key=key item=value from=$smarty.post.user_del}
            <tr align="center">
                <td>{$value.nick}</td>
                <td>{$value.gala}</td>
                <td>{$value.planet}</td>
            </tr>
        {/foreach}
            <tr height="10"><td colspan="3"></td></tr>
            <tr align="center">
                <td colspan="3">
                    <input type="hidden" name="user_del[0]" value="{$smarty.post.user_del}">
                    {foreach key=key0 item=value0 from=$smarty.post.user_del}
                        {foreach key=key1 item=value1 from=$value0}
                            <input type="hidden" name="user_del[{$key0}][{$key1}]" value="{$value1}">
                        {/foreach}
                    {/foreach}
                    <input type="submit" name="del_yes" value="Ja">
                    <input type="submit" name="del_no" value="Nein">
                </td>
            </tr>
        </table>
    {/if}
</center></form>
