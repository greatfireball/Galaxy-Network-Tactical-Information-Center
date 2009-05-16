<?php /* Smarty version 2.6.14, created on 2009-05-16 13:25:51
         compiled from /var/www/bekeonweb/ticng/Design/default/right_overview.tpl */ ?>
<h2>Rechteverwaltung</h2>
<br>
<div align="center">
    <table class="border_table">
        <tr>
            <th colspan="2">Ränge</th>
        </tr>
    <?php $_from = $this->_tpl_vars['rang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['r']['name']; ?>
</td>
            <td><a href="wrapper.php?mod=Right&amp;role=<?php echo $this->_tpl_vars['r']['id']; ?>
">Editieren</a></td>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
    </table>

    <br><br>

    <table class="border_table">
        <tr>
            <th colspan="3">Rollen</th>
        </tr>
    <?php $_from = $this->_tpl_vars['role']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['r']['name']; ?>
</td>
            <td><a href="wrapper.php?mod=Right&amp;role=<?php echo $this->_tpl_vars['r']['id']; ?>
">Editieren</a></td>
            <td>Loeschen</td>
        </tr>
    <?php endforeach; endif; unset($_from); ?>
    </table>
</div>