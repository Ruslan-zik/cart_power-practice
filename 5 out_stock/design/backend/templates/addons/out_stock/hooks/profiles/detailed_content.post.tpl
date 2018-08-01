	<div class="control-group">
        <label class="control-label" for="out_of_stock">{__("out_of_stock")}</label>
        <div class="controls">
            <input type="hidden" name="user_data[out_of_stock]" value="N"/>
            <input type="checkbox" id="out_of_stock" name="user_data[out_of_stock]" value="Y" {if $user_data.out_of_stock == 'Y'}checked="checked"{/if} />
        </div>
    </div>