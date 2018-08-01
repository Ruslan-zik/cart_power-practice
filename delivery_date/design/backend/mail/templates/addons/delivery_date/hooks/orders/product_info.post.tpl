<div class="ty-control-group product-list-field">
    <label class="ty-control-group__label">{(__("delivery_date.delivery_date"))}:</label>
    <span class="ty-control-group__item">{if $oi.delivery_date}{$oi.delivery_date|date_format:"`$settings.Appearance.date_format`"}{else}{__("delivery_date.not_indicated")}{/if}</span>
</div>