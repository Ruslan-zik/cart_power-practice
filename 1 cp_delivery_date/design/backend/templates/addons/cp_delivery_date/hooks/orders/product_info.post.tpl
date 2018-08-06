<p><strong>{(__("cp_delivery_date.cp_delivery_date"))}:</strong>
    {if $oi.cp_delivery_date}{$oi.cp_delivery_date|date_format:"`$settings.Appearance.date_format`"}{else}{__("cp_delivery_date.not_indicated")}{/if}
</p>