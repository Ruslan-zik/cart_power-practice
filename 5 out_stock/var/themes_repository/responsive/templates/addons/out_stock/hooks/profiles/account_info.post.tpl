	<div class="ty-profile-field__switch ty-address-switch clearfix">
	    <label for='out_of_stock'>{__("out_of_stock")}</label>
	    <div class="ty-profile-field__switch-actions">
	    	<input type="hidden" name="user_data[out_of_stock]" value="N">
	        <input class="ty-form-builder__checkbox checkbox" type="checkbox" name="user_data[out_of_stock]" value = "Y" id="out_of_stock"{if $user_data.out_of_stock == 'Y'} checked="checked"{/if}>
	    </div>
	</div>