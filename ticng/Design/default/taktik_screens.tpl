<h2>Taktikschirme</h2>
{foreach from=$screens item=screen}
<div class="taktikschirm" align="center">
    <table class="border_table">
        <tr>
            <th class="taktikschirm_status" colspan="7">
                Taktikschirm der Galaxie <strong>{$screen.gala}</strong> -- Letztes Update <strong>{$screen.date}</strong> von <strong>{$screen.user}</strong>.
                {if $screen.show_age}Der Schirm ist <span class="{$screen.age_css}">{$screen.age}</span> alt.{/if}
            </th>
        </tr>
        {include file="taktik_inner.tpl"}
    </table>
</div>
{/foreach}
