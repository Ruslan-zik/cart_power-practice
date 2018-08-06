{include file="common/letter_header.tpl"}

<p><a href='{$url}'>{__("customer_changed_data")}</a></p><br>
{if $mes_m}
<p><strong>{__("user_account_info")}:</strong></p>
{foreach from=$mes_m item=i key=k}
	 <p>{$i}</p>
{/foreach}
<br>
{/if}
{if $mes_s}
<p><strong>{__("billing_shipping_address")}:</strong></p>
{foreach from=$mes_s item=i key=k}
	 <p>{$i}</p>
{/foreach}
<br>
{/if}
{if $mes_b}
<p><strong>{__("billing_address")}:</strong></p>
{foreach from=$mes_b item=i key=k}
	 <p>{$i}</p>
{/foreach}
{/if}
    
{include file="common/letter_footer.tpl" }