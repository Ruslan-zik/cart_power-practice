{math equation="rand()" assign="rnd"}
{assign var="data_id" value="`$data_id`_`$rnd`"}
{assign var="view_mode" value=$view_mode|default:"mixed"}
{assign var="show_but_text" value=$show_but_text|default:"true"}

{script src="js/tygh/picker.js"}

{assign var="display" value=$display|default:"radio"}

{$_but_text=__("choose_category")}
<div class="mixed-controls">
<div class="form-inline">
<span id="{$data_id}" class="cm-js-item cm-display-radio">

<div class="input-append">
<input id='category' class="cm-picker-value-description {$extra_class}" type="text" value="{$category_name}" size="10" name="category_name" readonly="readonly" {$extra}>

{include file="buttons/button.tpl" but_id="opener_picker_`$data_id`" but_href="cp_documents.picker?display=`$display`&picker_for=`$picker_for`&extra=`$extra_var`&checkbox_name=`$checkbox_name`&root=`$default_name`&except_id=`$except_id`&data_id=`$data_id``$extra_url`"|fn_url but_role="text" but_icon="icon-plus" but_target_id="content_`$data_id`" but_meta="`$but_meta` cm-dialog-opener add-on btn"}

<input id="{if $input_id}{$input_id}{else}u{$data_id}_ids{/if}" type="hidden" class="cm-picker-value" name="{$input_name}" value="{$category_id}" {$extra} />

</div>
</span>
</div>
</div>

<div class="hidden" id="content_{$data_id}" title="{$_but_text}">
</div>