<p><strong>{(__("delivery_date.delivery_date"))}:</strong>
    {if $oi.delivery_date}{$oi.delivery_date|date_format:"`$settings.Appearance.date_format`"}{else}{__("delivery_date.not_indicated")}{/if}
</p>