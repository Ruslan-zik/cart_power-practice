{capture name="mainbox"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

<form class="form-horizontal form-edit" action="{""|fn_url}" method="post" name="documents_categories_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

{if $categories}
<table width="100%" class="table table-sort table-middle">
<thead>
    <tr>
        <th class="left" width="1%">{include file="common/check_items.tpl"}</th>
        <th><a class="cm-ajax{if $search.sort_by == "category_name"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=category_name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}</a></th>
        <th width="10%">&nbsp;</th>
        <th class="right" width="12%"><a class="cm-ajax{if $search.sort_by == "status"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}</a></th>
    </tr>
</thead>
<tbody>
{foreach from=$categories item="category"}
    <tr>
        <td class="left"><input type="checkbox" class="cm-item" value="{$category.doc_category_id}" name="doc_ids[]"/></td>
        <td>
            <a class="row-status" href="{"cp_documents.update?doc_category_id=`$category.doc_category_id`"|fn_url}">{$category.category_name}</a>
        </td>
    </tr>
{/foreach}
</tbody>
</table>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl"}

</form>
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        {if $categories}
            <li>{btn type="delete_selected" dispatch="dispatch[cp_documents.m_delete]" form="documents_categories_form"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="cp_documents.add&section=category" prefix="top" hide_tools="true" title=__("add_document_category")}
{/capture}

{include file="common/mainbox.tpl" title=__("documents_categories") content=$smarty.capture.mainbox tools=$smarty.capture.tools select_languages=true buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons}