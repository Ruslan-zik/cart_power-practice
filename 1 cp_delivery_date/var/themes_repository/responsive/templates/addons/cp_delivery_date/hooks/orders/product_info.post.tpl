<div class="ty-control-group product-list-field">
    <label class="ty-control-group__label">{(__("cp_delivery_date.cp_delivery_date"))}:</label>
    <span class="ty-control-group__item">{if $product.cp_delivery_date}{$product.cp_delivery_date|date_format:"`$settings.Appearance.date_format`"}{else}{__("cp_delivery_date.not_indicated")}{/if}</span>
</div>