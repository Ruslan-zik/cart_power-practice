{capture name="mainbox"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

<form class="form-horizontal form-edit" action="{""|fn_url}" method="post" name="categories_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true}

{if $categories}
<table width="100%" class="table table-sort table-middle">
<thead>
    <tr>
        <th>{__("name")}</th>
        <th width="10%">&nbsp;</th>
        <th class="right" width="12%">{__("status")}</th>
    </tr>
</thead>
<tbody>
{foreach from=$categories item="category"}
{$non_editable = !$category|fn_allow_save_object:"cp_categories_docs"}
    <tr class="cm-row-status-{$category.status|lower}">
        <td class="row-status">
            {if !$non_editable}
            <a href="{"cp_categories_docs.update?doc_category_id=`$category.doc_category_id`"|fn_url}">{$category.category_name}</a>
            {else}
            <span>{$category.category_name}</span>
            {/if}
            {include file="views/companies/components/company_name.tpl" object=$category}
        </td>
        <td class="row-status">
            <div class="hidden-tools">
                {capture name="tools_list"}
                {if !$non_editable}
                    <li>{btn type="list" text=__("edit") href="cp_categories_docs.update?doc_category_id=`$category.doc_category_id`" method="GET"}</li>
                    <li>{btn type="list" class="cm-confirm" text=__("delete") href="cp_categories_docs.delete?doc_category_id=`$category.doc_category_id`" method="POST"}</li>
                {else}
                    <li>{include file="common/popupbox.tpl" id="group`$category.doc_category_id`" text="{__("view")}: `$category.category_name`" act="edit" link_text=__("view") href="cp_categories_docs.update?doc_category_id=`$category.doc_category_id`&in_popup=1" no_icon_link=true}</li>
                {/if}
                {/capture}
                {dropdown content=$smarty.capture.tools_list}
            </div>
        </td>
        <td class="nowrap right">
            {include file="common/select_popup.tpl" id=$category.doc_category_id status=$category.status items_status="category"|fn_get_predefined_statuses object_id_name="doc_category_id" table="cp_categories_docs"}
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

{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="cp_categories_docs.add" prefix="top" hide_tools="true" title=__("add_document_category")}
{/capture}

{include file="common/mainbox.tpl" title=__("documents_categories") content=$smarty.capture.mainbox tools=$smarty.capture.tools select_languages=true buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons}