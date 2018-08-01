{if $documents || $search.max_create_date || $search.min_create_date || $search.categories || $search.text || $search.file_name}
<form action="{""|fn_url}">
    <div class="ty-product-filters__wrapper">
        <div class="ty-product-filters__block">
            <div style="cursor: auto;"class="ty-product-filters__switch">
                <label for="text" class="ty-product-filters__title">{__("text")}</label>
                    <div class="ty-product-filters__search">
                        <input id="text" type="text" placeholder="{__("search")}" class="ty-input-text-medium" name="text" value="{$search.text}" />
                    </div>
            </div>

            <div style="cursor: auto;"class="ty-product-filters__switch">
                <label for="doc_file_name" class="ty-product-filters__title">{__("filename")}</label>
                    <div class="ty-product-filters__search">
                        <input type="text" id="doc_file_name" placeholder="{__("search")}" name="file_name" class="ty-input-text-medium" value="{$search.file_name}" />
                    </div>
            </div>

        {if $categories}
            <div style="cursor: auto;" class="ty-product-filters__switch">
                <span class="ty-product-filters__title">{__("category")}</span>
                <ul>
                    {foreach from=$categories item="variant"}
                    <li class="ty-product-filters__group">
                        <label><input class="cm-product-filters-checkbox" type="checkbox" name="categories[{$variant.doc_category_id}]" value="{$variant.doc_category_id}"{if $search.categories.{$variant.doc_category_id} || $search.categories|in_array:"{$variant.doc_category_id}"} checked="checked"{/if}>{$variant.category_name}</label>
                    </li>
                    {/foreach}
                </ul>
            </div>
        {/if}
             <div style="cursor: auto;"class="ty-product-filters__switch">
                <label for="create_date" class="ty-product-filters__title">{__("creation_date")}</label>
                <div class="ty-product-filters__search">
            {if $search.min_create_date}      
                    {include file="common/calendar.tpl" date_id="min_create_date" date_name="min_create_date" date_val=$search.min_create_date|fn_parse_date}
            {else}
                    {include file="common/calendar.tpl" date_id="min_create_date" date_name="min_create_date"}
            {/if}
            -
            {if $search.max_create_date}      
                    {include file="common/calendar.tpl" date_id="max_create_date" date_name="max_create_date" date_val=$search.max_create_date|fn_parse_date}
            {else}
                    {include file="common/calendar.tpl" date_id="max_create_date" date_name="max_create_date"}
            {/if}
                </div>
            </div>
            <div class="ty-product-filters__switch ty-center">
                {include file="buttons/search.tpl" but_name="dispatch[cp_documents.view]"}
            </div>
        {if $search.max_create_date || $search.min_create_date || $search.categories || $search.text || $search.file_name}
            <div class="ty-product-filters__tools clearfix">
                <a href="{"cp_documents.view"|fn_url}" class="ty-product-filters__reset-button"><i class="ty-product-filters__reset-icon ty-icon-cw"></i>{__("reset")}</a>
            </div>
        {/if}
        </div>
    </div>
</form>
{/if}