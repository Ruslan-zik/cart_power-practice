{if !$smarty.request.extra}
<script type="text/javascript">
(function(_, $) {
    _.tr('text_items_added', '{__("text_items_added")|escape:"javascript"}');
    var display_type = '{$smarty.request.display|escape:javascript nofilter}';

    $.ceEvent('on', 'ce.formpost_add_category_form', function(frm, elm) {
        var categories = {};

        if ($('input.cm-item:checked', frm).length > 0) {

            $('input.cm-item:checked', frm).each( function() {
                var id = $(this).val();
                var item = $(this).parent().siblings();
                categories[id] = item.find('.category-name').text()
            });
            {literal}
            $.cePicker('add_js_item', frm.data('caResultId'), categories, 'u', {
                '{doc_category_id}': '%id',
                '{category_name}': '%item'
            });
            {/literal}

                $.ceNotification('show', {
                    type: 'N',
                    title: _.tr('notice'),
                    message: _.tr('text_items_added'),
                    message_state: 'I'
                });
        }

        return false;
    });
}(Tygh, Tygh.$));
</script>
{/if}

<form action="{$smarty.request.extra|fn_url}" method="post" data-ca-result-id="{$smarty.request.data_id}" name="add_category_form">

{if $categories}
<table width="100%" class="table table-middle">
<thead>
<tr>
    <th width="1%" class="center"></th>
    <th>{__("category")}</th>
    <th class="right">{__("active")}</th>
</tr>
</thead>
{foreach from=$categories item=category}
<tr>
    <td class="left">
        <input id="input_{$category.doc_category_id}"type="radio" name="selected_category_id" class="cm-item" value="{$category.doc_category_id}" />
    </td>
    <td>
        <label class="category-name inline-label" for="input_{$category.doc_category_id}">{$category.category_name}</label>
        {include file="views/companies/components/company_name.tpl" object=$category}
    </td>
    <td class="right">{if $category.status == "D"}{__("disable")}{else}{__("active")}{/if}</td>
</tr>
{/foreach}
</table>
{else}
    <p class="no-items">{__("no_data")}. <a href="{"cp_categories_docs.add"|fn_url}">{__("new_category")}</a></p>
{/if}

<div class="buttons-container">
    {assign var="but_close_text" value=__("choose")}
    {include file="buttons/add_close.tpl" is_js=$smarty.request.extra|fn_is_empty}
</div>

</form>
