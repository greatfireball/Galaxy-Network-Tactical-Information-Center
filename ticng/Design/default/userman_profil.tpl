<h2>Profil</h2>
<br>
<div align="center">
    <form method="post">
        <table class="border_table">
            <tr>
                <td>Authnick</td>
                <td><input type="text" name="auth" value="{$user->getAuthnick()}"></td>
            </tr>
            <tr>
                <td>IRC Highlight</td>
                <td><input type="text" name="highlight" value="{$user->getHighlight()}"></td>
            </tr>
            <tr>
                <td>Scantyp</td>
                <td>
                    <select size="1" name="scantyp">
                        <option{if $user->getScantyp() == "unbekannt"} selected{/if}>unbekannt</option>
                        <option{if $user->getScantyp() == "Sektorscan"} selected{/if}>Sekorscan</option>
                        <option{if $user->getScantyp() == "Einheitenscan"} selected{/if}>Einheitenscan</option>
                        <option{if $user->getScantyp() == "Militaerscan"} selected{/if}>Militaerscan</option>
                        <option{if $user->getScantyp() == "erw. Militaerscan"} selected{/if}>erw. Militaerscan</option>
                        <option{if $user->getScantyp() == "Geschuetzscan"} selected{/if}>Geschuetzscan</option>
                        <option{if $user->getScantyp() == "Nachrichtenscan"} selected{/if}>Nachrichtenscan</option>
                        <option{if $user->getScantyp() == "erw. Nachrichtenscan"} selected{/if}>erw. Nachrichtenscan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>SVs</td>
                <td><input type="text" name="svs" value="{$user->getSvs()}"></td>
            </tr>
            <tr>
                <td>Elokas</td>
                <td><input type="text" name="elokas" value="{$user->getElokas()}"></td>
            </tr>
            <tr>
                <td>Telefon Nummer</td>
                <td><input type="text" name="tel" value="{$user->getTelnr()}"></td>
            </tr>
            <tr>
                <td>Telefon Kommentar</td>
                <td><input type="text" name="tel_com" value="{$user->getTelnrComment()}"></td>
            </tr>
            <tr>
                <td>Telefon Sichtbarkeit</td>
                <td>
                    <select size="1" name="tel_vis">
                        <option{if $user->getTelnrVisibility() == "Alle"} selected{/if}>Alle</option>
                        <option{if $user->getTelnrVisibility() == "Meta"} selected{/if}>Meta</option>
                        <option{if $user->getTelnrVisibility() == "Allianz"} selected{/if}>Allianz</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>ICQ</td>
                <td><input type="text" name="icq" value="{$user->getICQ()}"></td>
            </tr>
            <tr>
                <td>Jabber</td>
                <td><input type="text" name="jabber" value="{$user->getJabber()}"></td>
            </tr>
            <tr>
                <td>Zeitformat im TIC</td>
                <td>
                    <select size="1" name="timeformat">
                        <option{if $user->getTimeformat() == "Ticks"} selected{/if}>Ticks</option>
                        <option{if $user->getTimeformat() == "Minuten"} selected{/if}>Minuten</option>
                        <option{if $user->getTimeformat() == "Stunden"} selected{/if}>Stunden</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="hidden" name="profile_post" value="1">
        <br>
        <input class="button" type="submit" value=" Speichern ">
    </form>
</div>
