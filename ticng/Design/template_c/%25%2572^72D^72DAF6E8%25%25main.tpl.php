<?php /* Smarty version 2.6.14, created on 2009-05-16 13:16:50
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'popup_init', 'main.tpl', 15, false),array('modifier', 'default', 'main.tpl', 17, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Tactical Information Center - Next Generation</title>
        <script src="Design/javascripts/ajax.js" type="text/javascript"></script>
        <script src="Design/javascripts/clock.js" type="text/javascript"></script>
        <script src="Design/javascripts/popup.js" type="text/javascript"></script>
        <script src="Design/javascripts/taktik-hud.js" type="text/javascript"></script>
        <script src="Design/javascripts/init.js" type="text/javascript"></script>
        <script src="Design/javascripts/libstd.js" type="text/javascript"></script>
        <script src="Design/javascripts/taktik.js" type="text/javascript"></script>
        <link href="Design/default/main.css" rel="stylesheet" type="text/css">
        <?php echo smarty_function_popup_init(array('src' => "Design/javascripts/overlib/overlib.js"), $this);?>

    </head>
    <body onLoad="<?php echo ((is_array($_tmp=@$this->_tpl_vars['tic_close'])) ? $this->_run_mod_handler('default', true, $_tmp, 'init();') : smarty_modifier_default($_tmp, 'init();')); ?>
">
        <a name="top"></a>
        <div class="clock">
            Serverzeit: <span id="clock"></span><br>
            Letzter Tick: <?php echo $this->_tpl_vars['thisTick']; ?>

        </div>
        <!--<div class="logo">logo</div>-->
        <div id="taktikhud" class="top">
            <h1>Tactical Information Center</h1>
            <h2>Next Generation</h2>
        </div>
        <div class="middle">
            <table class="middle">
                <tr>
                    <td class="navi">
<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['entry']):
?>
    <?php if ($this->_tpl_vars['entry']['param'] != ""): ?>
                        <a href="wrapper.php?mod=<?php echo $this->_tpl_vars['entry']['mod']; ?>
&menu=<?php echo $this->_tpl_vars['entry']['param']; ?>
" title="<?php echo $this->_tpl_vars['entry']['name']; ?>
">
    <?php endif; ?>
    <?php if ($this->_tpl_vars['entry']['first']): ?>
                            <span class="menu_lvl_<?php echo $this->_tpl_vars['entry']['level']; ?>
_first">
    <?php else: ?>
                            <span class="menu_lvl_<?php echo $this->_tpl_vars['entry']['level']; ?>
">
    <?php endif; ?>
                                <?php echo $this->_tpl_vars['entry']['name']; ?>

                            </span>
     <?php if ($this->_tpl_vars['entry']['param'] != ""): ?>
                        </a>
    <?php endif;  endforeach; endif; unset($_from); ?>
                    </td>
                    <td class="smal"></td>
                    <td class="tpl">
<?php if ($this->_tpl_vars['ticngErrors'] != fasle): ?>
                        <center><font color="#FF0000">
    <?php $_from = $this->_tpl_vars['ticngErrors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
                            <?php echo $this->_tpl_vars['value']; ?>
<br>
    <?php endforeach; endif; unset($_from); ?>
                        </font></center>
<?php endif;  if ($this->_tpl_vars['ticngInfos'] != fasle): ?>
                        <center>
    <?php $_from = $this->_tpl_vars['ticngInfos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
                            <?php echo $this->_tpl_vars['value']; ?>
<br>
    <?php endforeach; endif; unset($_from); ?>
                        </center>
<?php endif; ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['mod_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="bottom">
            T.I.C. NG version <?php echo $this->_tpl_vars['tic_version']; ?>
<br>
            <?php if ($this->_tpl_vars['ticks_done'] == 1): ?>
                <b>1</b> Tick ausgef&uuml;hrt<br>
            <?php elseif ($this->_tpl_vars['ticks_done'] > 1): ?>
                <b><?php echo $this->_tpl_vars['ticks_done']; ?>
</b> Ticks ausgef&uuml;hrt<br>
            <?php endif; ?>
            Module <b><?php echo $this->_tpl_vars['mod_name']; ?>
</b> version <?php echo $this->_tpl_vars['mod_version']; ?>
<br>
            <?php echo $this->_tpl_vars['queries_failed']; ?>
/<?php echo $this->_tpl_vars['queries']; ?>
 Fehler/Queries (<?php echo $this->_tpl_vars['dbtype']; ?>
)<br>            
            <hr class="smaler" />
            <?php echo $this->_tpl_vars['user']->getNick(); ?>
 (<?php echo $this->_tpl_vars['user']->getKoords(); ?>
)

        </div>
    </body>
</html>