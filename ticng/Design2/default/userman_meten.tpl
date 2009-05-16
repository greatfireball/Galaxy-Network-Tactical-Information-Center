<h2>Meten</h2>
<br>
<center>
    <div class="center">
        <form method="post">
            <table class="border_table">
                <tr>
                    <th>Metaname</th>
                    <th>Tag</th>
                    <th></th>
                    <th></th>
                </tr>
            {foreach from=$meten item=meta}
                <tr>
                    <td><input type="text" name="meta_name_{$meta->getId()}" value="{$meta->getName()}" maxlength="60" size="40"></td>
                    <td><input type="text" name="meta_tag_{$meta->getId()}" value="{$meta->getTag()}" maxlength="20" size="20"></td>
                    <td><input class="button" type="submit" name="save_{$meta->getId()}" value="speichern"></td>
                    <td><input class="button" type="submit" name="delete_{$meta->getId()}" value="l&ouml;schen"></td>
                </tr>
            {/foreach}
                <tr>
                    <td><input type="text" name="meta_name_new" maxlength="60" size="40"></td>
                    <td><input type="text" name="meta_tag_new" maxlength="20" size="20"></td>
                    <td class="center" colspan="2"><input class="button" type="submit" name="meta_new" value="Neue Meta"></td>
                </tr>
            </table>
            <input type="hidden" name="userman_meten" value="1">
        </form>
    </div>
</center>
