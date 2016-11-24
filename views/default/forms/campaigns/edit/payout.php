<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('campaigns:payout:account'),
	'#help' => elgg_echo('campaigns:payout:account:help'),
	'value' => $entity->payout_instructions,
	'name' => 'payout_instructions',
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
			[
			'#type' => 'email',
			'#label' => elgg_echo('campaigns:checkout:email'),
			'name' => 'email',
			'required' => true,
			'value' => elgg_extract('email', $vars, $user->email),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:checkout:first_name'),
			'name' => 'first_name',
			'required' => true,
			'value' => elgg_extract('first_name', $vars, $user->first_name),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:checkout:last_name'),
			'name' => 'last_name',
			'required' => true,
			'value' => elgg_extract('last_name', $vars, $user->last_name),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:checkout:phone'),
			'name' => 'phone',
			'required' => true,
			'value' => elgg_extract('phone', $vars, $user->campaigns_phone),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:checkout:company_name'),
			'name' => 'company_name',
			'value' => elgg_extract('company_name', $vars, $user->campaigns_company_name),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:checkout:tax_id'),
			'name' => 'tax_id',
			'value' => elgg_extract('tax_id', $vars, $user->campaigns_tax_id),
		],
	]
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
