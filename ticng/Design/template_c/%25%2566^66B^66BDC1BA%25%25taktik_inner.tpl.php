<?php /* Smarty version 2.6.14, created on 2009-05-16 13:26:37
         compiled from taktik_inner.tpl */ ?>
    <tr>
        <th>User</th>
        <th colspan="2">Userfleet</th>
        <th colspan="2">Incomings</th>
        <th colspan="2">Deff</th>
    </tr>
<?php $_from = $this->_tpl_vars['screen']['flotten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['user']):
?>
    <tr class="inc_<?php echo $this->_tpl_vars['user']['save']; ?>
">
        <td><?php echo $this->_tpl_vars['user']['user']; ?>
</td>
        <td>
    <?php $_from = $this->_tpl_vars['user']['outgoing']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['out1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['out1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['out']):
        $this->_foreach['out1']['iteration']++;
?>
            <span class="<?php if ($this->_tpl_vars['out']->getAngriff()): ?>att<?php else: ?>deff<?php endif; ?>">
                <?php echo $this->_tpl_vars['out']->toTaktikString(true); ?>
 #<?php echo $this->_tpl_vars['out']->getFlotte(true); ?>

            </span>
            <?php if (! ($this->_foreach['out1']['iteration'] == $this->_foreach['out1']['total'])): ?><br><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
        </td>
        <td class="eta">
    <?php $_from = $this->_tpl_vars['user']['outgoing']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['out2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['out2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['out']):
        $this->_foreach['out2']['iteration']++;
?>
            <?php echo $this->_tpl_vars['out']->getETA(); ?>

            <?php if (! ($this->_foreach['out2']['iteration'] == $this->_foreach['out2']['total'])): ?><br><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
        </td>
        <td>
    <?php $_from = $this->_tpl_vars['user']['incs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['incs1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['incs1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['inc']):
        $this->_foreach['incs1']['iteration']++;
?>
            <span class="inc_<?php if ($this->_tpl_vars['inc']->getSafe()): ?>save<?php else: ?>unsave<?php endif; ?>">
                <?php echo $this->_tpl_vars['inc']->toTaktikString(false); ?>
 #<?php echo $this->_tpl_vars['inc']->getFlotte(true); ?>

            </span>
            <?php if (! ($this->_foreach['incs1']['iteration'] == $this->_foreach['incs1']['total'])): ?><br><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
        </td>
        <td class="eta">
    <?php $_from = $this->_tpl_vars['user']['incs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['incs2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['incs2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['inc']):
        $this->_foreach['incs2']['iteration']++;
?>
            <span class="inc_<?php if ($this->_tpl_vars['inc']->getSafe()): ?>save<?php else: ?>unsave<?php endif; ?>">
                <?php echo $this->_tpl_vars['inc']->getETA(); ?>

            </span>
            <?php if (! ($this->_foreach['incs2']['iteration'] == $this->_foreach['incs2']['total'])): ?><br><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
        </td>
        <td>
    <?php $_from = $this->_tpl_vars['user']['deff']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['deff1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['deff1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['deff']):
        $this->_foreach['deff1']['iteration']++;
?>
            <?php echo $this->_tpl_vars['deff']->toTaktikString(false); ?>
 #<?php echo $this->_tpl_vars['deff']->getFlotte(true); ?>

            <?php if (! ($this->_foreach['deff1']['iteration'] == $this->_foreach['deff1']['total'])): ?><br><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
        </td>
        <td class="eta">
    <?php $_from = $this->_tpl_vars['user']['deff']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['deff2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['deff2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['deff']):
        $this->_foreach['deff2']['iteration']++;
?>
            <?php echo $this->_tpl_vars['deff']->getETA(); ?>

            <?php if (! ($this->_foreach['deff2']['iteration'] == $this->_foreach['deff2']['total'])): ?><br><?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
        </td>
    </tr>
<?php endforeach; endif; unset($_from); ?>