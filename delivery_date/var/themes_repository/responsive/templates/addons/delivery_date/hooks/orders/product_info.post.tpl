<div class="ty-control-group product-list-field">
    <label class="ty-control-group__label">{(__("delivery_date.delivery_date"))}:</label>
    <span class="ty-control-group__item">{if $product.delivery_date}{$product.delivery_date|date_format:"`$settings.Appearance.date_format`"}{else}{__("delivery_date.not_indicated")}{/if}</span>
</div>