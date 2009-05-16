<center>
    {if $scanAnzahl}
        <br />
        {foreach key=key item=value from=$galaUsers}
            <a href="#{$smarty.request.gala}:{$value}" styel="color: #000000;"
              title="{$smarty.request.gala}:{$value}">{$smarty.request.gala}:{$value}</a>
        {/foreach}
        <br /><br /><br />
    {/if}
    {section name=z1 start=0 loop=$scanAnzahl step=1}
    <font class="title">Scans von {$scanHeader[z1].nick} ({$scanHeader[z1].koords})</font>
    <a name="{$scanHeader[z1].koords}"></a>
    <br /><br />
    <div class="scan"><table border="0" align="center"><tr valign="top" noresize="noresize">
    {if $sek[z1]}
    <td style="padding: 0px 5px 0pX 5px;"><table border="0">
        <tr align="center">
            <th colspan="2">Sektorscan</th>
        </tr>
        <tr height="5"><td colspan="2"></td></tr>
        {php}$i0 = 0;{/php}
        {foreach key=key item=value from=$sektorNamen}
            <tr align="left"{php}if ($i0 % 2 != 0) { echo ' class="dark"'; } $i0++;{/php}>
                <td>{$value.1}</td>
                <td class="bigger">{$sek[z1][$key]}</td>
            </tr>
        {/foreach}
        <tr height="10"><td colspan="2"></td></tr>
        <tr align="left">
            <td>Gen</td>
            <td class="bigger">
                {if $sekHeader[z1].gen < 90}
                    <font color="#FF0000">
                {elseif $sekHeader[z1].gen < 100}
                    <font color="#DD6600">
                {/if}
                {$sekHeader[z1].gen}%
                {if $sekHeader[z1].gen < 100}</font>{/if}
            </font></td>
        </tr>
        <tr align="left" class="dark">
            <td>Datum</td>
            <td class="bigger">
                {if $sekHeader[z1].old === true}<font color="#993300">{else}<font>{/if}
                {$sekHeader[z1].time}
            </font></td>
        </tr>
        <tr align="left">
            <td>Scanner</td>
            <td class="bigger">{$sekHeader[z1].scanner.nick} ({$sekHeader[z1].scanner.koords})</td>
        </tr>
        <tr align="left" class="dark">
            <td>Herkunft</td>
            <td class="bigger">{$sekHeader[z1].birth}</td>
        </tr>
        <tr height="10"><td colspan="2"></td></tr>
        <tr align="center">
            <td colspan="2">
                <input type="button" class="button" value="IRC-Scan"
                    onclick="popup({$sekIRCPopup[z1].width|default:500},{$sekIRCPopup[z1].height|default:100},'{$sekIRCPopup[z1].text}');"
                />
            </td>
        </tr>
    </table></td>
    {/if}
    {if $gesch[z1]}
    <td style="padding: 0px 5px 0px 5px;"><table border="0">
        <tr align="center">
            <th colspan="2">Gesch&uuml;tzscan</th>
        </tr>
        <tr height="5"><td colspan="2"></td></tr>
        {php}$i0 = 0;{/php}
        {foreach key=key item=value from=$geschNamen}
            <tr align="left"{php}if ($i0 % 2 != 0) { echo ' class="dark"'; } $i0++;{/php}>
                <td>{$value.1}</td>
                <td class="bigger">{$gesch[z1][$key]}</td>
            </tr>
        {/foreach}
        <tr height="10"><td colspan="2"></td></tr>
        <tr align="left" class="dark">
            <td>Gen</td>
            <td class="bigger">
                {if $geschHeader[z1].gen < 90}
                    <font color="#FF0000">
                {elseif $geschHeader[z1].gen < 100}
                    <font color="#DD6600">
                {/if}
                {$geschHeader[z1].gen}%
                {if $geschHeader[z1].gen < 100}</font>{/if}
            </font></td>
        </tr>
        <tr align="left">
            <td>Datum</td>
            <td class="bigger">
                {if $geschHeader[z1].old === true}<font color="#993300">{else}<font>{/if}
                {$geschHeader[z1].time}
            </font></td>
        </tr>
        <tr align="left" class="dark">
            <td>Scanner</td>
            <td class="bigger">{$geschHeader[z1].scanner.nick} ({$geschHeader[z1].scanner.koords})</td>
        </tr>
        <tr align="left">
            <td>Herkunft</td>
            <td class="bigger">{$geschHeader[z1].birth}</td>
        </tr>
        <tr height="10"><td colspan="2"></td></tr>
        <tr align="center">
            <td colspan="2">
                <input type="button" class="button" value="IRC-Scan"
                    onclick="popup({$geschIRCPopup[z1].width|default:500},{$geschIRCPopup[z1].height|default:100},'{$geschIRCPopup[z1].text}');"
                />
            </td>
        </tr>
    </table></td>
    {/if}
    {if $unit[z1]}
    <td style="padding: 0px 5px 0px 5px;"><table border="0">
        <tr align="center">
            <th colspan="2">Einheitenscan</th>
        </tr>
        <tr height="5"><td colspan="2"></td></tr>
        <tr align="left">
            <td><b>Schiff</b></td>
            <td><b>Anzahl</b></td>
        </tr>
        {php}$i0 = 0;{/php}
        {foreach key=key item=value from=$schiffNamen}
            <tr align="left"{php}if ($i0 % 2 == 0) { echo ' class="dark"'; } $i0++;{/php}>
                <td>{$value.0}</td>
                <td class="bigger">{$unit[z1][$key]}</td>
            </tr>
        {/foreach}
        <tr height="10"><td colspan="2"></td></tr>
        <tr align="left">
            <td>Gen</td>
            <td class="bigger">
                {if $unitHeader[z1].gen < 90}
                    <font color="#FF0000">
                {elseif $unitHeader[z1].gen < 100}
                    <font color="#DD6600">
                {/if}
                {$unitHeader[z1].gen}%
                {if $unitHeader[z1].gen < 100}</font>{/if}
            </td>
        </tr>
        <tr align="left" class="dark">
            <td>Datum</td>
            <td class="bigger">
                {if $unitHeader[z1].old === true}<font color="#993300">{else}<font>{/if}
                {$unitHeader[z1].time}
            </font></td>
        </tr>
        <tr align="left">
            <td>Scanner</td>
            <td class="bigger">{$unitHeader[z1].scanner.nick} ({$unitHeader[z1].scanner.koords})</td>
        </tr>
        <tr align="left" class="dark">
            <td>Herkunft</td>
            <td class="bigger">{$unitHeader[z1].birth}</td>
        </tr>
        <tr height="10"><td colspan="2"></td></tr>
        <tr align="center">
            <td colspan="2">
                <input type="button" class="button" value="IRC-Scan"
                    onclick="popup({$unitIRCPopup[z1].width|default:500},{$unitIRCPopup[z1].height|default:100},'{$unitIRCPopup[z1].text}');"
                />
            </td>
        </tr>
    </table></dt>
    {/if}
    {if $mili[z1]}
    <td style="padding: 0px 5px 0px 5px;"><table border="0">
        <tr align="center">
            <th colspan="4">Milit&auml;rscan</th>
        </tr>
        <tr height="5"><td colspan="4"></td></tr>
        <tr align="left">
            <td><b>Schiff</b></td>
            <td><b>Orbit</b></td>
            <td><b>Flotte 1</b></td>
            <td><b>Flotte 2</b></td>
        </tr>
        {php}$i0 = 0;{/php}
        {foreach key=key item=value from=$schiffNamen}
            <tr align="left"{php}if ($i0 % 2 == 0) { echo ' class="dark"'; } $i0++;{/php}>
                <td>{$value.0}</td>
                {foreach key=key1 item=value1 from=$mili[z1][$key]}
                    <td class="bigger">{$value1}</td>
                {/foreach}
            </tr>
        {/foreach}
        <tr align="left">
            <td colspan="2"></td>
            <td style="white-space: nowrap;">{$miliFlotte[z1].1}</td>
            <td style="white-space: nowrap;">{$miliFlotte[z1].2}</td>
        </tr>
        <tr height="10"><td colspan="4"></td></tr>
        <tr align="left" class="dark">
            <td>Gen</td>
            <td colspan="3" class="bigger">
                {if $miliHeader[z1].gen < 90}
                    <font color="#FF0000">
                {elseif $miliHeader[z1].gen < 100}
                    <font color="#DD6600">
                {/if}
                {$miliHeader[z1].gen}%
                {if $miliHeader[z1].gen < 100}</font>{/if}
            </font></td>
        </tr>
        <tr align="left">
            <td>Datum</td>
            <td colspan="3" class="bigger">
                {if $miliHeader[z1].old === true}<font color="#993300">{else}<font>{/if}
                {$miliHeader[z1].time}
            </font></td>
        </tr>
        <tr align="left" class="dark">
            <td>Scanner</td>
            <td colspan="3" class="bigger">{$miliHeader[z1].scanner.nick} ({$miliHeader[z1].scanner.koords})</td>
        </tr>
        <tr align="left">
            <td>Herkunft</td>
            <td colspan="3" class="bigger">{$miliHeader[z1].birth}</td>
        </tr>
        <tr height="10"><td colspan="4"></td></tr>
        <tr align="center">
            <td colspan="4">
                <input type="button" class="button" value="IRC-Scan"
                    onclick="popup({$miliIRCPopup[z1].width|default:500},{$miliIRCPopup[z1].height|default:100},'{$miliIRCPopup[z1].text}');"
                />
            </td>
        </tr>
    </table></dt>
    {/if}
    </tr></table></div>
    {if !$oneScan}
        <div>
            {if $news[z1]}
                <a href="{$scan_path}&scanSearch=1&gala={$scanHeader[z1].gala}&planet={$scanHeader[z1].planet}#news"
                  title="Newsscan {$scanHeader[z1].koords}">
                    Newsscan ({$scanHeader[z1].koords})
                </a>
            {/if}
            {if $scanAnzahl != 1}
            <a href="#top">
                <hr />
                <img align="left" src="Design/default/images/arrow_up.gif" width="14" height="10" border="0" alt="top" title="top" />
                <img align="right" src="Design/default/images/arrow_up.gif" width="14" height="10" border="0" alt="top" title="top" />
            </a>
            {/if}
        </div>
    {/if}
    {/section}
    {if $news && $oneScan}
    <table border="0" style="padding: 10px 0px 0px 0px;" align="left">
        <tr align="center">
            <th colspan="6">Newsscan</th>
            <a name="news"></a>
        </tr>
        <tr height="5"><td colspan="6"></td></tr>
        <tr>
            <th class="bigger">Typ</th>
            <th class="bigger">Nick</th>
            <th class="bigger">Flotte</th>
            <th class="bigger">Abflug</td>
            <th class="bigger">ETA</th>
            <th class="bigger">Ankunft</th>
        </tr>
        {php}$i0 = 0;{/php}
        {foreach key=key item=value from=$news.0}
            <tr align="left"{php}if ($i0 % 2 == 0) { echo ' class="dark"'; } $i0++;{/php}>
                <td class="bigger">{$value.type}</td>
                <td class="bigger">{$value.nick} ({$value.koords})</td>
                <td class="bigger" align="center">{$value.fleet}</td>
                <td class="bigger">{$value.time}</td>
                <td class="bigger" align="center">{$value.eta}</td>
                <td class="bigger">{$value.arrivalTime}</td>
            </tr>
        {/foreach}
        <tr height="10"><td colspan="6"></td></tr>
        <tr align="left"{php}if ($i0 % 2 == 0) { echo ' class="dark"'; } $i0++;{/php}>
            <td>Gen</td>
            <td colspan="5" class="bigger">
                {if $newsHeader[z1].gen < 90}
                    <font color="#FF0000">
                {elseif $newsHeader[z1].gen < 100}
                    <font color="#DD6600">
                {/if}
                {$newsHeader[z1].gen}%
                {if $newsHeader[z1].gen < 100}</font>{/if}
            </font></td>
        </tr>
        <tr align="left"{php}if ($i0 % 2 == 0) { echo ' class="dark"'; } $i0++;{/php}>
            <td>Datum</td>
            <td colspan="5" class="bigger">
                {if $newsHeader.0.old === true}<font color="#993300">{else}<font>{/if}
                {$newsHeader.0.time}
            </font></td>
        </tr>
        <tr align="left"{php}if ($i0 % 2 == 0) { echo ' class="dark"'; } $i0++;{/php}>
            <td>Scanner</td>
            <td colspan="5" class="bigger">{$newsHeader.0.scanner.nick} ({$newsHeader.0.scanner.koords})</td>
        </tr>
        <tr align="left"{php}if ($i0 % 2 == 0) { echo ' class="dark"'; } $i0++;{/php}>
            <td>Herkunft</td>
            <td class="bigger">{$newsHeader[z1].birth}</td>
        </tr>
        <tr height="10"><td colspan="6"></td></tr>
        <tr align="center">
            <td colspan="6">
                <input type="button" class="button" value="IRC-Scan"
                    onclick="popup({$newsIRCPopup.width|default:500},{$newsIRCPopup.height|default:100},'{$newsIRCPopup.0.text}');"
                />
                <input type="button" class="button" value="IRC-Scan-Koplett"
                    onclick="popup({$newsIRCPopup.width|default:500},{$newsIRCPopup.height|default:100},'{$newsIRCPopup.0.all}');"
                />
            </td>
        </tr>
    </table>
    {/if}
</center>
