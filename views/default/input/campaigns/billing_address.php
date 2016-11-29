<?php

$user = elgg_get_logged_in_user_entity();
$billing = elgg_extract('billing', $vars);
echo elgg_view_field([
	'#type' => 'fieldset',
	'#class' => 'campaigns-postal-address',
	'legend' => elgg_echo('campaigns:billing_address'),
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'checkbox',
			'#class' => 'campaigns-billing-as-shipping',
			'#label' => elgg_echo('campaigns:billing_addres:same_as_shipping'),
			'name' => 'billing_as_shipping',
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:street_address'),
			'name' => "billing[street_address]",
			'value' => elgg_extract('street_address', $billing, $user->campaigns_street_address),
			'required' => true,
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:extended_address'),
			'name' => "billing[extended_address]",
			'value' => elgg_extract('extended_address', $billing, $user->campaigns_extended_address),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:locality'),
			'name' => "billing[locality]",
			'value' => elgg_extract('locality', $billing, $user->campaigns_locality),
			'required' => true
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:region'),
			'name' => "billing[region]",
			'value' => elgg_extract('region', $billing, $user->campaigns_region),
			'required' => true
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:postal_code'),
			'name' => "billing[postal_code]",
			'value' => elgg_extract('postal_code', $billing, $user->campaigns_postal_code),
			'required' => true
		],
			[
			'#type' => 'country',
			'#label' => elgg_echo('campaigns:postal_address:country'),
			'name' => "billing[country_code]",
			'value' => elgg_extract('country_code', $billing, $user->campaigns_country_code),
			'required' => true
		],
	]
]);