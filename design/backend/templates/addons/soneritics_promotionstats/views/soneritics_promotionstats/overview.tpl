{hook name="soneritics_promotionstats:notice"}
    {notes title=__("soneritics_promotionstats_menu")}
        <p>{__('addons.soneritics_promotionstats.manage.overview_sidebar')}</p>
    {/notes}
{/hook}

{capture name="mainbox"}
    {if $overview}
        <table class="table sortable table-middle">
            <thead>
            <tr>
                <th class="nowrap">{__("promotion")}</th>
                <th class="nowrap">{__("code")}</th>
                <th class="nowrap right">{__("orders")}</th>
                <th></th>
            </tr>
            </thead>
            {foreach $overview as $overviewLine}
                <tr>
                    <td><a href="{"soneritics_promotionstats.orders_promo?promoId=`$overviewLine->getPromotionId()`"|fn_url}">{$overviewLine->getPromotionName()}</a></td>
                    <td><a href="{"soneritics_promotionstats.orders_code?code=`$overviewLine->getCode()`"|fn_url}">{$overviewLine->getCode()}</a></td>
                    <td class="nowrap right">{$overviewLine->getAmount()}</td>
                </tr>
            {/foreach}
        </table>
    {else}
        <p class="no-items">{__("no_data")}</p>
    {/if}

    {capture name="buttons"}{/capture}
    {capture name="adv_buttons"}{/capture}
{/capture}
{include file="common/mainbox.tpl" title=__("soneritics_promotionstats_menu") content=$smarty.capture.mainbox tools=$smarty.capture.tools select_languages=true buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons}
