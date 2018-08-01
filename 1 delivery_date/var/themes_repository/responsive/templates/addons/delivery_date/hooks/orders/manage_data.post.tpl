	<td class="nowrap">{if isset($o.delivery_date)}{$o.delivery_date|date_format:"`$settings.Appearance.date_format`"}{else}{__("delivery_date.not_indicated")}{/if}</td>
