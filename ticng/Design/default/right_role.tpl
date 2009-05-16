<h2>Rechte {if $israng}des Ranges{else}der Rolle{/if} {$name}</h2>
<br>
<div align="center">
    <form method="post">
        <table class="border_table">
            <tr><th colspan="2">Eigenschaften von Benutzern verändern</th><tr>
            <tr>
                <td>Benutzerverwaltung - Galaxie</td>
                <td><input type="checkbox" name="right_0" value="1" {if $caps[0]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzerverwaltung - Allianz</td>
                <td><input type="checkbox" name="right_1" value="1" {if $caps[1]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzerverwaltung - Meta</td>
                <td><input type="checkbox" name="right_2" value="1" {if $caps[2]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzerverwaltung - Alle</td>
                <td><input type="checkbox" name="right_3" value="1" {if $caps[3]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzer umziehen innerhalb der Allianz</td>
                <td><input type="checkbox" name="right_4" value="1" {if $caps[4]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzer umziehen innerhalb der Meta</td>
                <td><input type="checkbox" name="right_5" value="1" {if $caps[5]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzer umziehen</td>
                <td><input type="checkbox" name="right_6" value="1" {if $caps[6]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzer als Bot eintragen</td>
                <td><input type="checkbox" name="right_7" value="1" {if $caps[7]}checked{/if}></td>
            </tr>

            <tr><th colspan="2">Zugehörigkeit von Diplomatischen Entitäten verändern</th><tr>
            <tr>
                <td>Diplomatie der Galaxie ändern</td>
                <td><input type="checkbox" name="right_100" value="1" {if $caps[100]}checked{/if}></td>
            </tr>
            <tr>
                <td>Diplomatie der Allianz ändern</td>
                <td><input type="checkbox" name="right_101" value="1" {if $caps[101]}checked{/if}></td>
            </tr>
            <tr>
                <td>Diplomatie der Meta ändern</td>
                <td><input type="checkbox" name="right_102" value="1" {if $caps[102]}checked{/if}></td>
            </tr>
            <tr>
                <td>Diplomatie von allen ändern</td>
                <td><input type="checkbox" name="right_103" value="1" {if $caps[103]}checked{/if}></td>
            </tr>

            <tr><th colspan="2">Nachrichten erstellen/löschen</th><tr>
            <tr>
                <td>Nachricht an Galaxie senden</td>
                <td><input type="checkbox" name="right_200" value="1" {if $caps[200]}checked{/if}></td>
            </tr>
            <tr>
                <td>Nachricht an Allianz senden</td>
                <td><input type="checkbox" name="right_201" value="1" {if $caps[201]}checked{/if}></td>
            </tr>
            <tr>
                <td>Nachricht an Meta senden</td>
                <td><input type="checkbox" name="right_202" value="1" {if $caps[202]}checked{/if}></td>
            </tr>
            <tr>
                <td>Nachricht an alle senden</td>
                <td><input type="checkbox" name="right_203" value="1" {if $caps[203]}checked{/if}></td>
            </tr>
            <tr>
                <td>Nachricht von anderen Benutzern löschenn</td>
                <td><input type="checkbox" name="right_204" value="1" {if $caps[204]}checked{/if}></td>
            </tr>

            <tr><th colspan="2">Taktik</th><tr>
            <tr>
                <td>Incomings in der Galaxie auf save stellen</td>
                <td><input type="checkbox" name="right_300" value="1" {if $caps[300]}checked{/if}></td>
            </tr>
            <tr>
                <td>Incomings in der Allianz auf save stellen</td>
                <td><input type="checkbox" name="right_301" value="1" {if $caps[301]}checked{/if}></td>
            </tr>
            <tr>
                <td>Incomings in der Meta auf save stellen</td>
                <td><input type="checkbox" name="right_302" value="1" {if $caps[302]}checked{/if}></td>
            </tr>
            <tr>
                <td>Incomings überall safe stellen</td>
                <td><input type="checkbox" name="right_303" value="1" {if $caps[303]}checked{/if}></td>
            </tr>

            <tr><th colspan="2">Logs einsehen</th><tr>
            <tr>
                <td>Scaneinträge</td>
                <td><input type="checkbox" name="right_500" value="1" {if $caps[500]}checked{/if}></td>
            </tr>
            <tr>
                <td>Incomings safe/unsafe setzen</td>
                <td><input type="checkbox" name="right_501" value="1" {if $caps[501]}checked{/if}></td>
            </tr>
            <tr>
                <td>Galaxie Taktik updates</td>
                <td><input type="checkbox" name="right_502" value="1" {if $caps[502]}checked{/if}></td>
            </tr>
            <tr>
                <td>Benutzerverwaltung</td>
                <td><input type="checkbox" name="right_503" value="1" {if $caps[503]}checked{/if}></td>
            </tr>
            <tr>
                <td>Sonstige</td>
                <td><input type="checkbox" name="right_504" value="1" {if $caps[504]}checked{/if}></td>
            </tr>

            <tr><th colspan="2">Rechte ändern</th><tr>
            <tr>
                <td>Rechte von Rängen ändern</td>
                <td><input type="checkbox" name="right_600" value="1" {if $caps[600]}checked{/if}></td>
            </tr>
            <tr>
                <td>Rechte von Gruppen ändern</td>
                <td><input type="checkbox" name="right_601" value="1" {if $caps[601]}checked{/if}></td>
            </tr>
            <tr><th colspan="2">Sonstiges</th><tr>
            <tr>
                <td>Debugging Informationen einsehen</td>
                <td><input type="checkbox" name="right_1000" value="1" {if $caps[1000]}checked{/if}></td>
            </tr>
        </table>
        <input type="hidden" name="role_edit_post" value="1">
        <input type="hidden" name="role_id" value="{$id}">
        <input type="submit" class="button" value="speichern">
    </form>
</div>
