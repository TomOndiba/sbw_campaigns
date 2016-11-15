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
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
