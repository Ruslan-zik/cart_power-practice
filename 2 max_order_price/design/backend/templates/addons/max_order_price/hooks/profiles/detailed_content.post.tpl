{if $user_type == "C"}
    <div class="control-group">
        <label for="max_order_price" class="control-label">{__("max_order_price")}</label>
        <div class="controls">
            {include file="common/price.tpl" value=$user_data.max_order_price input_name='user_data[max_order_price]' view='input'}
        </div>
    </div>    
{/if}