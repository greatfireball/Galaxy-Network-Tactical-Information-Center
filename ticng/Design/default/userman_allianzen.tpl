<h2>Allianzen</h2>
<br>
<div align="center">
    <form method="post">
        <table class="border_table">
            <tr>
                <th colspan="5">bestehende Allianzen</th>
            </tr>
            <tr>
                <th>Allianz</th>
                <th>Tag</th>
                <th>ist in Meta</th>
                <th></th>
                <th></th>
            </tr>
        {foreach from=$allianzen item=alli}
            <tr>
                <td><input type="text" name="alli_name_{$alli->getId()}" size="30" maxlength="100" value="{$alli->getName()}"></td>
                <td><input type="text" name="alli_tag_{$alli->getId()}" size="20" maxlength="20" value="{$alli->getTag()}"></td>
                <td>
                    <select size="1" name="alli_meta_{$alli->getId()}">
                        <option value="NULL">(keine)</option>
                    {foreach from=$meten item=meta}
                        {if $meta->getId() == $alli->getMetaId()}
                        <option value="{$meta->getid()}" selected>{$meta->getName()}</option>
                        {else}
                        <option value="{$meta->getId()}">{$meta->getName()}</option>
                        {/if}
                    {/foreach}
                    </select>
                </td>
                <td><input class="button" type="submit" name="save_{$alli->getId()}" value="speichern"></td>
                <td><input class="button" type="submit" name="delete_{$alli->getId()}" value="l&ouml;schen"></td>
            </tr>
        {/foreach}
        </table>
        <input type="hidden" name="userman_allianzen" value="1">
    </form>
    <br><br>
    <form method="post">
        <table class="border_table">
            <tr>
                <th colspan="4">Neue Allianz Anlegen</th>
            </tr>
            <tr>
                <th>Allianz</th>
                <th>Tag</th>
                <th>ist in Meta</th>
                <th></th>
            </tr>
            <tr>
                <td><input type="text" name="alli_name_new" size="30" maxlength="100"></td>
                <td><input type="text" name="alli_tag_new" size="20" maxlength="20"></td>
                <td>
                    <select size="1" name="alli_meta_new">
                        <option value="NULL">(keine)</option>
                    {foreach from=$meten item=meta}
                        <option value="{$meta->getId()}">{$meta->getName()}</option>
                    {/foreach}
                    </select>
                </td>
                <td><input class="button" type="submit" name="new" value="neu"></td>
            </tr>
        </table>
   </form>
</div>
