{if $document}
	{if $document.text}
	<div class="ty-feature">
	    <div class="ty-feature__description ty-wysiwyg-content">
	    	<p>{$document.text}</p>
	    </div>
	</div>
	{/if}
	{if $files}
	<table width="100%" class="ty-table">
	<thead>
	    <tr>
	        <th>{__("name")}</th>
	        <th width="25%">{__("changed")}</th>
	        <th width="15%">{__("size")} ({__("bytes")})</th>
	    </tr>
	</thead>
	<tbody>
	{foreach from=$files item=file}
	    <tr>
	        <td>{$file.file_name} <a href="{"cp_documents.doc_view?doc_id={$document.doc_id}&getfile=1&file=`$file.file_name`"|fn_url}" class="cm-post">[{__("direct_download")}]</a></td>
	        <td>{$file.ctime|date_format:"`$settings.Appearance.date_format`"}</td>
	        <td>{$file.size}</td>
	    </tr>
	{/foreach}
	</tbody>
	</table>
	{/if}
	{if !$files && !$document.text}
		<p class="ty-no-items">{__("no_data")}</p>
	{/if}
	{capture name="mainbox_title"}<span class="ty-mainbox-title">{$document.name}</span>{/capture}
{/if}