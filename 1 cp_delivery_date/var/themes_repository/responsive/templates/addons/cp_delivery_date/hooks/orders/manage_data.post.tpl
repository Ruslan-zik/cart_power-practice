	<td class="nowrap">{if isset($o.cp_delivery_date)}{$o.cp_delivery_date|date_format:"`$settings.Appearance.date_format`"}{else}{__("cp_delivery_date.not_indicated")}{/if}</td>
