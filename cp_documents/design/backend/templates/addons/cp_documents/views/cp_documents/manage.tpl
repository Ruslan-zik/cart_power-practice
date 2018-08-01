{capture name="mainbox"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

<form class="form-horizontal form-edit" action="{""|fn_url}" method="post" name="documents_form">

{include file="common/pagination.tpl" pagination=$pagination save_current_url=true save_current_page=true}

{if $documents}
<table width="100%" class="table table-sort table-middle table-docs">
<thead>
    <tr>
        <th class="left" width="1%">{include file="common/check_items.tpl"}</th>
        <th width="25%"><a class="cm-ajax{if $search.sort_by == "name"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("name")}</a></th>
        <th width="25%"><a class="cm-ajax{if $search.sort_by == "category"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=category&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("category")}</a></th>
        <th width="25%"><a class="cm-ajax{if $search.sort_by == "type"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=type&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("type")}</a></th>
        <th width="25%"><a class="cm-ajax{if $search.sort_by == "creation_date"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=creation_date&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("creation_date")}</a></th>
        <th width="10%">&nbsp;</th>
        <th class="right" width="12%"><a class="cm-ajax{if $search.sort_by == "status"} sort-link-{$search.sort_order_rev}{/if}" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id="pagination_contents">{__("status")}</a></th>
    </tr>
</thead>
<tbody>
{foreach from=$documents item="document"}
{$non_editable = !$document|fn_allow_save_object:"cp_documents"}
    {if !$non_editable || $document.status != 'H'}
    {$non_script = true}
    <tr class="cm-row-status-{$document.status|lower}">
        <td class="left"><input type="checkbox" class="cm-item" value="{$document.doc_id}" name="doc_ids[]"{if $non_editable} disabled{/if}/></td>
        <td>
            {if !$non_editable}
            <a class="row-status" href="{"cp_documents.update?doc_id=`$document.doc_id`"|fn_url}">{$document.name}</a>
            {else}
            <span class="unedited-element block">{$document.name}</span>
            {/if}
            {include file="views/companies/components/company_name.tpl" object=$document}
        </td>
        <td>
            <span class="row-status">
                {if !$non_editable}
                <a href="{"cp_categories_docs.update?doc_category_id=`$document.doc_category_id`"|fn_url}">{$document.category_name}</a>
                {else}
                <span class="unedited-element block">{$document.category_name}</span>
                {/if}
            </span>
        </td>
        <td>
            <span class="row-status">{if $document.type == 'I'}{__("internal")}{else}{__("global")}{/if}</span>
        </td>   
        <td>
            <span class="row-status">{$document.create_date|date_format:"`$settings.Appearance.date_format`"}</span>
        </td>
        <td>
            <div class="hidden-tools">
                {capture name="tools_list"}
                {if !$non_editable}
                    <li>{btn type="list" text=__("edit") href="cp_documents.update?doc_id=`$document.doc_id`" method="GET"}</li>
                    <li>{btn type="list" class="cm-confirm" text=__("delete") href="cp_documents.delete?doc_id=`$document.doc_id`" method="POST"}</li>
                {else}
                    <li>{include file="common/popupbox.tpl" id="group`$document.doc_id`" text="{__("view")}: `$document.name`" act="edit" link_text=__("view") href="cp_documents.update?doc_id=`$document.doc_id`&in_popup=1" no_icon_link=true}</li>
                {/if}
                {/capture}
                {dropdown content=$smarty.capture.tools_list}
            </div>
        </td>
        <td class="nowrap right">
            {include file="common/select_popup.tpl" id=$document.doc_id status=$document.status items_status="documents"|fn_get_predefined_statuses object_id_name="doc_id" table="cp_documents" hidden=true}
        </td>
    </tr>
    {/if}
{/foreach}
</tbody>
</table>
{if !$non_script}
<script type="text/javascript">
    $("table.table-docs").replaceWith('<p class="no-items">{__("no_data")}</p>');
</script>
{/if}
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl" pagination=$pagination save_current_url=true save_current_page=true}

</form>
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        {if $documents}
            <li>{btn type="delete_selected" dispatch="dispatch[cp_documents.m_delete]" form="documents_form"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="cp_documents.add" prefix="top" hide_tools="true" title=__("add_document")}
{/capture}

{capture name="sidebar"}
    {include file="addons/cp_documents/views/cp_documents/components/documents_search_form.tpl" dispatch="cp_documents.manage"}
{/capture}

{include file="common/mainbox.tpl" title=__("cp_documents") content=$smarty.capture.mainbox tools=$smarty.capture.tools select_languages=true buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}