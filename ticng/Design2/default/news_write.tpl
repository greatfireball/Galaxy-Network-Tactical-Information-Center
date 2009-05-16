<h3>Nachricht schreiben</h3>
<br>
<form method="post">
    <input name="subject" size="50" maxlength="255" value="Betreff"><br>
    <textarea name=text cols="80" rows="10">Inhalt</textarea><br>
    <select name="audience">
        {if $gala}<option value="Galaxie">Galaxie</option>{/if}
        {if $alli}<option value="Allianz">Allianz</option>{/if}
        {if $meta}<option value="Meta">Meta</option>{/if}
        {if $alle}<option value="Alle">Alle</option>{/if}
    </select>
    <input type="hidden" name="news_write_post" value="1">
    <input type="submit" value=" OK ">
</form>
