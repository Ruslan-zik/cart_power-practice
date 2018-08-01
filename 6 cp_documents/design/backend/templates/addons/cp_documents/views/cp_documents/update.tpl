{if $document.doc_id}
    {assign var="id" value=$document.doc_id}
    {$non_editable = !$document|fn_allow_save_object:"cp_documents"}
{else}
    {assign var="id" value=0}
{/if}
{$non_editable = !$document|fn_allow_save_object:"cp_documents"}
{capture name="mainbox"}
<h4 class="subheader   hand" data-toggle="collapse" data-target="#doc_information">{__("information")}<span class="icon-caret-down"></span></h4>
<div id="doc_information" class="collapsed in">
    <form action="{""|fn_url}" method="post" enctype="multipart/form-data" name="cp_document_form" class="form-horizontal form-edit">
        <input type="hidden" name="doc_id" value="{$id}" />

        <div class="control-group">
            <label for="elm_post_name" class="control-label {if !$non_editable} cm-required{/if}">{__("name")}</label>
            <div class="controls">
                {if !$non_editable} 
                <input type="text" name="document_description[name]" id="elm_post_name" value="{$document.name}" size="40" class="input-large user-success">
                {else}
                <span class="shift-input">{$document.name}</span>
                {/if}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label {if !$non_editable} cm-required{/if}" for="category">{__("category")}</label>
            <div class="controls">
                {if !$non_editable} 
                 {include file="addons/cp_documents/views/cp_documents/pickers/categories/picker.tpl" display="radio" but_meta="" extra_url=$extra_url category_name=$document.category_name category_id=$document.doc_category_id data_id="cat_info" input_name="document_data[doc_category_id]"}
                {else}
                <span class="shift-input">{$document.category_name}</span>
                {/if}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="text">{__("text")}</label>
            <div class="controls">
                {if !$non_editable} 
                <textarea class="input-large" rows="4" name="document_description[text]" id="text">{$document.text}</textarea>
                {else}
                <span class="shift-input">{$document.text}</span>
                {/if}
            </div>
        </div>

        {if !$non_editable} 
        <div class="control-group">
            <label class="control-label" for="text">{__("upload_file")}</label>
            <div class="controls">
                {include file="common/fileuploader.tpl" var_name="filename[]" multiupload='Y'}
            </div>
        </div>
        {/if}

        <div class="control-group">
            <label class="control-label {if !$non_editable} cm-required{/if}" for="doc_type">{__("type")}</label>
            <div class="controls">
                {if !$non_editable} 
                <select name="document_data[type]" id="doc_type">
                    <option value="I" {if $document.type == 'I'} selected{/if}>{__("internal")}</option>
                    <option value="G" {if $document.type == 'G'} selected{/if}>{__("global")}</option>
                </select>
                {elseif $document.type == 'I'}
                <span class="shift-input">{__("internal")}</span>
                {else}
                <span class="shift-input">{__("global")}</span>
                {/if}
            </div>
        </div>
        {if !$non_editable} 
        {include file="common/select_status.tpl" input_name="document_data[status]" id="doc_status" obj=$document hidden=true}
        {else}
        <div class="control-group">
            <label class="control-label {if !$non_editable} cm-required{/if}" for="doc_type">{__("status")}</label>
            <div class="controls">
                <span class="shift-input">
                {include file="common/select_popup.tpl" non_editable=true status=$document.status}
                </span>
             </div>
        </div>
        {/if}      
    </form>
</div>
<h4 class="subheader   hand" data-toggle="collapse" data-target="#files_information">{__("files")}<span class="icon-caret-down"></span></h4>
<div id="files_information" class="collapsed in">
{if $files}
    <table width="100%" class="table table-middle">
    <thead>
        <tr>
            <th class="left">{__("name")}</th>
            <th class="right">{__("changed")}</th>
            <th class="right">{__("size")} ({__("bytes")})</th>
            <th width="10%" class="right">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$files item=file}
        <tr class="cm-row-item">
            <td class="left"><span class="row-status">{$file.file_name}</span></td>
            <td class="right"><span class="row-status">{$file.ctime|date_format:"`$settings.Appearance.date_format`"}</span></td>
            <td class="right"><span class="row-status">{$file.size}</span></td>
            <td class="right">
                <div class="hidden-tools">
                    {capture name="tools_list"}
                        <li>{btn type="list" text=__("direct_download") href="cp_documents.getfile?doc_id={$id}&file=`$file.file_name`" method="POST"}</li>
                        {if !$non_editable} 
                        <li>{btn type="list" text=__("delete") class="cm-ajax cm-confirm cm-delete-row" href="cp_documents.f_delete?doc_id={$id}&file_id=`$file.file_id`&file=`$file.file_name`" method="POST"}</li>
                        {/if}
                    {/capture}
                    {dropdown content=$smarty.capture.tools_list}
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}
</div>
{/capture}

{if $in_popup}
    {$smarty.capture.mainbox nofilter}
{else}
    {capture name="buttons"}
        {include file="buttons/save_cancel.tpl" but_name="dispatch[cp_documents.update]" but_target_form="cp_document_form" save=$id}
    {/capture}
    {if !$id}
        {include file="common/mainbox.tpl" title="{__("new_document")}" content=$smarty.capture.mainbox select_languages=true buttons=$smarty.capture.buttons}
    {else}
        {include file="common/mainbox.tpl" title="{__("editing_document")}: {$document.name}" content=$smarty.capture.mainbox select_languages=true buttons=$smarty.capture.buttons}
    {/if}
{/if}