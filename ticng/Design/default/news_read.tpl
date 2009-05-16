<h2>Nachrichten<h2>
{foreach from=$news_items item=item}
<div class="news_item">
    <table class="news_item">
    {if $item->isRead()}
        <tr class="news_head_read">
    {else}
        <tr class="news_head_unread">
    {/if}
            <td class="news_subject" colspan="2">{$item->getSubjectHTML()}</td>
        </tr>
    {if $item->isRead()}
        <tr class="news_head_read">
    {else}
        <tr class="news_head_unread">
    {/if}
            <td class="news_sender">{$item->getSenderStr()} an {$item->getAudienceIdStr()}</td>
            <td class="news_time">{$item->getTime()}</td>
        </tr>
    {if $item->isRead()}
        <tr class="news_body_read">
    {else}
        <tr class="news_body_unread">
    {/if}
            <td class="news_text" colspan="2">{$item->getTextHTML()}</td>
        </tr>
    {if $item->canDelete()}
        {if $item->isRead()}
        <tr class="news_body_read">
        {else}
        <tr class="news_body_unread">
        {/if}
            <td colspan="2" class="news_delete">
                <form method="post">
                    <input type="hidden" name="news_delete" value="{$item->getId()}">
                    <input class="button" type="submit" value="l&ouml;schen"> 
                </form>
            </td>
        </tr>
    {/if}
    </table>
</div>
{/foreach}
