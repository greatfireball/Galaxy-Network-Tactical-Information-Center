<?php /* Smarty version 2.6.14, created on 2009-05-16 13:26:00
         compiled from /var/www/bekeonweb/ticng/Design/default/userman_create.tpl */ ?>
<h2>Neuen Benutzer anlegen</h2>
<br>
<div align="center">
    <?php if ($this->_tpl_vars['created']): ?>
    <p class="created_user">Benutzer <?php echo $this->_tpl_vars['created']->getKoords(); ?>
 <?php echo $this->_tpl_vars['created']->getNick(); ?>
 wurde angelegt. Sein/Ihr vorläufiges Passwort lauted: <strong><?php echo $this->_tpl_vars['createdPw']; ?>
</strong></p>
    <?php endif; ?>
    <form method="post">
        <table class="border_table">
            <tr>
                <td>Koords</td>
                <td><input type="text" name="gala" maxlength="5" size="4" value="">:<input type="text" name="planet" size="2" maxlength="2" value=""></td>
            </tr>
            <tr>
                <td>Nick</td>
                <td><input type="text" name="nick" size="20" maxlength="50" value=""></td>
            </tr>
            <tr>
                <td>Rang</td>
                <td>
                    <select name="rang">
                        <option selected>Member</option>
                        <option>VGC</option>
                        <option>GC</option>
                        <option>VHC</option>
                        <option>HC</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="hidden" name="create_user" value="1">
        <br>
        <input class="button" type="submit" value=" anlegen ">
    </form>
</div>