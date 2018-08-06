<div class="sidebar-row">
    <h6>{__("search")}</h6>
    <form action="{""|fn_url}" name="documents_search_form" method="get">
        <div class="sidebar-field">
            <label for="text">{__("text")}</label>
            <input type="text" id="text" name="text" size="20" value="{$search.text}" onfocus="this.select();" class="input-text" />
        </div>

        <div class="sidebar-field">
            <label for="doc_file_name">{__("filename")}</label>
            <input type="text" id="doc_file_name" name="file_name" size="20" value="{$search.file_name}" onfocus="this.select();" class="input-text" />
        </div>

        <div class="sidebar-field">
            <label for="doc_categories">{__("category")}</label>
            <div class="object-selector">
                <select id="doc_categories"
                        class="cm-object-selector"
                        multiple
                        name="categories[]"
                        data-ca-enable-search="true"
                        data-ca-close-on-select="false">
                {if $categories}
                    {foreach from=$categories item=category}
                    <option value="{$category.doc_category_id}" {if $selected_categories && $category.doc_category_id|in_array:$selected_categories}selected{/if}>{$category.category_name}</option>
                    {/foreach}
                {/if}
                </select>
            </div>
        </div>
        <div class="sidebar-field">
            <label for="doc_creation_date">{__("creation_date")}</label>
            {include file="common/calendar.tpl" date_id="doc_min_create_date" date_name="min_create_date" date_val=$search.min_create_date} - {include file="common/calendar.tpl" date_id="max_doc_create_date" date_name="max_create_date" date_val=$search.max_create_date}
        </div>

        <div class="sidebar-field">
            {include file="buttons/search.tpl" but_name="dispatch[`$dispatch`]"}
        </div>
    </div>
    </form>
</div>

