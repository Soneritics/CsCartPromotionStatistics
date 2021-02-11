{hook name="soneritics_promotionstats:notice"}
{notes title=__("soneritics_promotionstats_menu")}
    <p>{__('addons.soneritics_promotionstats.manage.orders_sidebar')}</p>
{/notes}
{/hook}

{capture name="mainbox"}
<form action="{""|fn_url}" method="post" target="_self" name="orders_list_form">

    {include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id=$smarty.request.content_id}

    {assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
    {assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
    {assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}

    {assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}

    {if $incompleted_view}
        {assign var="page_title" value=__("incompleted_orders")}
        {assign var="get_additional_statuses" value=true}
    {else}
        {assign var="page_title" value=__("orders")}
        {assign var="get_additional_statuses" value=false}
    {/if}
    {assign var="order_status_descr" value=$smarty.const.STATUSES_ORDER|fn_get_simple_statuses:$get_additional_statuses:true}
    {assign var="extra_status" value=$config.current_url|escape:"url"}
    {$statuses = []}
    {assign var="order_statuses" value=$smarty.const.STATUSES_ORDER|fn_get_statuses:$statuses:$get_additional_statuses:true}

    {if $orders}
        <div class="table-responsive-wrapper">
            <table width="100%" class="table table-middle table-responsive">
                <thead>
                <tr>
                    <th width="17%"><a class="cm-ajax" href="{"`$c_url`&sort_by=order_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("id")}{if $search.sort_by == "order_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
                    <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("date")}{if $search.sort_by == "date"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
                    <th width="20%"><a class="cm-ajax" href="{"`$c_url`&sort_by=customer&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("customer")}{if $search.sort_by == "customer"}{$c_icon nofilter}{/if}</a></th>
                    <th class="mobile-hide">&nbsp;</th>
                    <th width="14%" class="right"><a class="cm-ajax{if $search.sort_by == "total"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=total&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("total")}</a></th>
                </tr>
                </thead>
                {foreach from=$orders item="o"}
                    {hook name="orders:order_row"}
                        <tr>
                            <td data-th="{__("id")}">
                                <a href="{"orders.details?order_id=`$o.order_id`"|fn_url}" class="underlined">{__("order")} <bdi>#{$o.order_id}</bdi></a>
                                {if $order_statuses[$o.status].params.appearance_type == "I" && $o.invoice_id}
                                    <p class="muted">{__("invoice")} #{$o.invoice_id}</p>
                                {elseif $order_statuses[$o.status].params.appearance_type == "C" && $o.credit_memo_id}
                                    <p class="muted">{__("credit_memo")} #{$o.credit_memo_id}</p>
                                {/if}
                                {include file="views/companies/components/company_name.tpl" object=$o}
                            </td>
                            <td class="nowrap" data-th="{__("date")}">{$o.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
                            <td data-th="{__("customer")}">
                                {if $o.email}<a href="mailto:{$o.email|escape:url}">@</a> {/if}
                                {if $o.user_id}<a href="{"profiles.update?user_id=`$o.user_id`"|fn_url}">{/if}{$o.lastname} {$o.firstname}{if $o.user_id}</a>{/if}
                                {if $o.company}<p class="muted">{$o.company}</p>{/if}
                            </td>
                            <td width="5%" class="center" data-th="{__("tools")}">
                                {capture name="tools_items"}
                                    <li>{btn type="list" href="orders.details?order_id=`$o.order_id`" text={__("view")}}</li>
                                    {hook name="orders:list_extra_links"}
                                        <li>{btn type="list" href="order_management.edit?order_id=`$o.order_id`" text={__("edit")}}</li>
                                        <li>{btn type="list" href="order_management.edit?order_id=`$o.order_id`&copy=1" text={__("copy")}}</li>
                                    {assign var="current_redirect_url" value=$config.current_url|escape:url}
                                        <li>{btn type="list" href="orders.delete?order_id=`$o.order_id`&redirect_url=`$current_redirect_url`" class="cm-confirm" text={__("delete")} method="POST"}</li>
                                    {/hook}
                                {/capture}
                                <div class="hidden-tools">
                                    {dropdown content=$smarty.capture.tools_items}
                                </div>
                            </td>
                            <td class="right" data-th="{__("total")}">
                                {include file="common/price.tpl" value=$o.total}
                            </td>
                        </tr>
                    {/hook}
                {/foreach}
            </table>
        </div>
    {else}
        <p class="no-items">{__("no_data")}</p>
    {/if}

    {include file="common/pagination.tpl" div_id=$smarty.request.content_id}

    {capture name="buttons"}{/capture}
    {capture name="adv_buttons"}{/capture}
{/capture}
{include file="common/mainbox.tpl" title=$overview_title content=$smarty.capture.mainbox tools=$smarty.capture.tools select_languages=true buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons}
