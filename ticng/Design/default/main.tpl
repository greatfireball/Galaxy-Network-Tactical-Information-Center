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
        {popup_init src="Design/javascripts/overlib/overlib.js"}
    </head>
    <body onLoad="{$tic_close|default:'init();'}">
        <a name="top"></a>
        <div class="clock">
            Serverzeit: <span id="clock"></span><br>
            Letzter Tick: {$thisTick}
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
{foreach from=$menu item=entry}
    {if $entry.param != ""}
                        <a href="wrapper.php?mod={$entry.mod}&menu={$entry.param}" title="{$entry.name}">
    {/if}
    {if $entry.first}
                            <span class="menu_lvl_{$entry.level}_first">
    {else}
                            <span class="menu_lvl_{$entry.level}">
    {/if}
                                {$entry.name}
                            </span>
     {if $entry.param != ""}
                        </a>
    {/if}
{/foreach}
                    </td>
                    <td class="smal"></td>
                    <td class="tpl">
{if $ticngErrors != fasle}
                        <center><font color="#FF0000">
    {foreach key=key item=value from=$ticngErrors}
                            {$value}<br>
    {/foreach}
                        </font></center>
{/if}
{if $ticngInfos != fasle}
                        <center>
    {foreach key=key item=value from=$ticngInfos}
                            {$value}<br>
    {/foreach}
                        </center>
{/if}
                        {include file=$mod_template}
                    </td>
                </tr>
            </table>
        </div>
        <div class="bottom">
            T.I.C. NG version {$tic_version}<br>
            {if $ticks_done == 1}
                <b>1</b> Tick ausgef&uuml;hrt<br>
            {elseif $ticks_done > 1}
                <b>{$ticks_done}</b> Ticks ausgef&uuml;hrt<br>
            {/if}
            Module <b>{$mod_name}</b> version {$mod_version}<br>
            {$queries_failed}/{$queries} Fehler/Queries ({$dbtype})<br>            
            <hr class="smaler" />
            {$user->getNick()} ({$user->getKoords()})

        </div>
    </body>
</html>
