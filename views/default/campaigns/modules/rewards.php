<?php

use SBW\Campaigns\Campaign;
use SBW\Campaigns\Reward;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

$options = [
	'list_type' => 'gallery',
	'gallery_class' => 'campaigns-rewards',
	'no_results' => elgg_echo('campaigns:rewards:no_results'),
];

echo elgg_list_entities_from_metadata($entity->getRewards($options, false));