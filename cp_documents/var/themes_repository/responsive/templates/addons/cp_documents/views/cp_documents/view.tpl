{if $documents}
<div class="ty-pagination-container cm-pagination-container" id="pagination_contents">
	<div class="ty-sort-container">
		{include file="common/sorting.tpl" sorting_orders=$sorting_orders sorting=$sorting pagination_id="pagination_contents" ajax_class="cm-ajax"}
		{if $search.total_items}
		{assign var="range_url" value=$config.current_url|fn_query_remove:"items_per_page":"page"}
		{assign var="product_steps" value=$settings.Appearance.columns_in_products_list|fn_get_product_pagination_steps:$settings.Appearance.products_per_page}
		<div class="ty-sort-dropdown">
			<a id="sw_elm_pagination_steps" class="ty-sort-dropdown__wrapper cm-combination">{$search.items_per_page} {__("per_page")}<i class="ty-sort-dropdown__icon ty-icon-down-micro"></i></a>
		    <ul id="elm_pagination_steps" class="ty-sort-dropdown__content cm-popup-box hidden">
		        {foreach from=$product_steps item="step"}
		        {if $step != $search.items_per_page}
		            <li class="ty-sort-dropdown__content-item">
		                <a class="cm-ajax ty-sort-dropdown__content-item-a" data-ca-target-id="pagination_contents" href="{"`$range_url`&items_per_page=`$step`"|fn_url}">{$step} {__("per_page")}</a>
		            </li>
		        {/if}
		        {/foreach}
		    </ul>
		</div>
		{/if}
	</div>
	{include file="common/pagination.tpl"}

	<table width="100%" style="margin-top: 0;" class="ty-table">
	<thead>
	    <tr>
	        <th>{__("name")}</th>
	        <th width="25%">{__("category")}</th>
	        <th width="15%">{__("creation_date")}</th>
	    </tr>
	</thead>
	<tbody>
	{foreach from=$documents item="document"}
	    <tr class="cm-row-status-{$document.status|lower}">
	        <td><a href="{"cp_documents.doc_view?doc_id=`$document.doc_id`"|fn_url}">{$document.name}</a>{if $document.type == 'I'}<span class='ty-remove__txt'>({__("internal")})</span>{/if}</td>
	        <td><a href="{"cp_documents.view?categories=`$document.doc_category_id`"|fn_url}">{$document.category_name}</a></td>
	        <td>{$document.create_date|date_format:"`$settings.Appearance.date_format`"}</td>
	    </tr>
	{/foreach}
	</tbody>
	</table>

	{include file="common/pagination.tpl"}
</div>
{else}
<p class="ty-no-items">{__("no_data")}</p>
{/if}

{capture name="mainbox_title"}<span class="ty-mainbox-title__left">{__("cp_documents")}</span>{/capture}