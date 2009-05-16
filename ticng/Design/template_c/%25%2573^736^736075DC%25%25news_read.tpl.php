<?php /* Smarty version 2.6.14, created on 2009-05-16 13:24:57
         compiled from /var/www/bekeonweb/ticng/Design/default/news_read.tpl */ ?>
<h2>Nachrichten<h2>
<?php $_from = $this->_tpl_vars['news_items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
<div class="news_item">
    <table class="news_item">
    <?php if ($this->_tpl_vars['item']->isRead()): ?>
        <tr class="news_head_read">
    <?php else: ?>
        <tr class="news_head_unread">
    <?php endif; ?>
            <td class="news_subject" colspan="2"><?php echo $this->_tpl_vars['item']->getSubjectHTML(); ?>
</td>
        </tr>
    <?php if ($this->_tpl_vars['item']->isRead()): ?>
        <tr class="news_head_read">
    <?php else: ?>
        <tr class="news_head_unread">
    <?php endif; ?>
            <td class="news_sender"><?php echo $this->_tpl_vars['item']->getSenderStr(); ?>
 an <?php echo $this->_tpl_vars['item']->getAudienceIdStr(); ?>
</td>
            <td class="news_time"><?php echo $this->_tpl_vars['item']->getTime(); ?>
</td>
        </tr>
    <?php if ($this->_tpl_vars['item']->isRead()): ?>
        <tr class="news_body_read">
    <?php else: ?>
        <tr class="news_body_unread">
    <?php endif; ?>
            <td class="news_text" colspan="2"><?php echo $this->_tpl_vars['item']->getTextHTML(); ?>
</td>
        </tr>
    <?php if ($this->_tpl_vars['item']->canDelete()): ?>
        <?php if ($this->_tpl_vars['item']->isRead()): ?>
        <tr class="news_body_read">
        <?php else: ?>
        <tr class="news_body_unread">
        <?php endif; ?>
            <td colspan="2" class="news_delete">
                <form method="post">
                    <input type="hidden" name="news_delete" value="<?php echo $this->_tpl_vars['item']->getId(); ?>
">
                    <input class="button" type="submit" value="l&ouml;schen"> 
                </form>
            </td>
        </tr>
    <?php endif; ?>
    </table>
</div>
<?php endforeach; endif; unset($_from); ?>