<?php

use SBW\Campaigns\Campaign;
use SBW\Campaigns\Commitment;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

if ($entity->model != Campaign::MODEL_RELIEF) {
	return;
}

echo elgg_list_entities([
	'types' => 'object',
	'subtypes' => Commitment::SUBTYPE,
	'container_guids' => $entity->guid,
	'limit' => 20,
	'offset_key' => 'commitments',
	'no_results' => elgg_echo('campaigns:commitments:no_results'),
	'item_view' => 'object/campaign_commitment/module_item',
]);
