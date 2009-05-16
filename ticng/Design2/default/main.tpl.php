<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Tactical Information Center - Next Generation</title>
        <script src="Design/javascripts/clock.js" type="text/javascript"></script>
        <script src="Design/javascripts/popup.js" type="text/javascript"></script>
        <link href="Design/default/main.css" rel="stylesheet" type="text/css" />
    </head>
<? //<body onLoad="{$tic_close|default:'clock();'}"> ?>
<body onLoad="clock();">
        <a name="top"></a>
        <div class="clock">
            Serverzeit: <span id="clock"></span><br />
            Letzter Tick: <?=$thisTick?>
        </div>
        <div class="logo">logo</div>
        <div class="top">
            <h1>Tactical Information Center</h1>
            <h2>Next Generation</h2>
        </div>
        <div class="middle">
            <table class="middle">
                <tr>
                    <td class="navi">
<? foreach($menu as $entry): ?>
    <? if ($entry['param']): ?>
                        <a href="wrapper.php?mod=<?=$entry['mod']?>&menu=<?=$entry['param']?>" title="<?=$entry['name']?>">
    <? endif; ?>
    <? if ($entry['first']): ?>
                            <span class="menu_lvl_<?=$entry['level']?>_first">
    <? else: ?>
                            <span class="menu_lvl_<?=$entry['level']?>">
    <? endif; ?>
                                <?=$entry['name']?>
                            </span>
    <? if ($entry['param'] != ""): ?>
                        </a>
    <? endif; ?>
<? endforeach; ?>
                    </td>
                    <td class="smal"></td>
                    <td class="tpl">
                        <? $mod_template->exec(); ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="bottom">
            T.I.C. NG version <?=$tic_version?><br />
            <? if ($ticks_done == 1): ?>
                <b>1</b> Tick ausgef&uuml;hrt<br />
            <? elseif ($ticks_done > 1): ?>
                <b><?=$ticks_done?></b> Ticks ausgef&uuml;hrt<br />
            <? endif; ?>
            Module <b><?=$mod_name?></b> version <?=$mod_version?><br />
            <?=$queries_failed?>/<?=$queries?> Fehler/Queries (<?=$dbtype?>)<br />            
            <hr class="smaler" />
            <?=$user->getNick()?> (<?=$user->getKoords()?>)
        </div>
    </body>
</html>
