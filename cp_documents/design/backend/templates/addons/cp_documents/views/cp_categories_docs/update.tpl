{if $category.doc_category_id}
    {assign var="id" value=$category.doc_category_id}
{else}
    {assign var="id" value=0}
{/if}
{$non_editable = !$category|fn_allow_save_object:"cp_categories_docs"}
{capture name="mainbox"}

<form action="{""|fn_url}" method="post" enctype="multipart/form-data" name="category_form" class="form-horizontal form-edit ">
<input type="hidden" name="doc_category_id" value="{$id}" />

<div class="control-group">
    <label class="control-label{if !$non_editable} cm-required{/if}" for="name">{__("name")}</label>
    <div class="controls">
        {if !$non_editable}
        <input type="text" name="category_description[category_name]" id="name" value="{$category.category_name}" size="40"/>
        {else}
        <span class="shift-input">{$category.category_name}</span>
        {/if}
    </div>
</div>

{if "ULTIMATE"|fn_allowed_for || "MULTIVENDOR"|fn_allowed_for}
    {assign var="zero_company_id_name_lang_var" value="all_vendors"}

    {if $non_editable}
    {assign var="disable_company_picker" value=true}
    {/if}

    {if "MULTIVENDOR"|fn_allowed_for}
    {assign var="js_action" value="fn_change_vendor_for_page(elm);"}
    {/if}   

    {include file="views/companies/components/company_field.tpl"
        name="category_data[company_id]"
        id="category_data_company_id"
        zero_company_id_name_lang_var=$zero_company_id_name_lang_var
        selected=$category.company_id
        js_action=$js_action
        disable_company_picker=$disable_company_picker
    }
{/if}
{if !$non_editable}
{include file="common/select_status.tpl" input_name="category_data[status]" id="category_status" obj=$category hidden=false}
{else}
<div class="control-group">
    <label class="control-label {if !$non_editable} cm-required{/if}" for="doc_type">{__("status")}</label>
    <div class="controls">
        <span class="shift-input">
        {include file="common/select_popup.tpl" non_editable=true status=$category.status}
        </span>
     </div>
</div>
{/if}
</form>	

{/capture}

{if $in_popup}
    {$smarty.capture.mainbox nofilter}
{else}
    {capture name="buttons"}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[cp_categories_docs.update]" but_target_form="category_form" save=$id}
    {/capture}

    {if !$id}
        {include file="common/mainbox.tpl" title="{__("new_category")}" content=$smarty.capture.mainbox select_languages=true buttons=$smarty.capture.buttons}
    {else}
        {include file="common/mainbox.tpl" title="{__("editing_document_category")}: {$category.category_name}" content=$smarty.capture.mainbox select_languages=true buttons=$smarty.capture.buttons}
    {/if}
{/if}