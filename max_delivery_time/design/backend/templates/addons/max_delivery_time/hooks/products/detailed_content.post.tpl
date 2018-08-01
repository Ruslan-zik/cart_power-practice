{include file="common/subheader.tpl" title=__("max_delivery_time") target="#acc_max_delivery_time"}
<div id="acc_max_delivery_time" class="collapse in">
    <div class="control-group">
        <label class="control-label" for="elm_max_delivery_time">{__("max_delivery_time")}{include file="common/tooltip.tpl" tooltip={__("max_delivery_time_desc")} params="ty-subheader__tooltip"}:</label>
        <div class="controls">
            <input type="text" name="product_data[max_delivery_time]" id="elm_max_delivery_time" size="10" value="{$product_data.max_delivery_time}" class="input-small">
        </div>
    </div>
</div>