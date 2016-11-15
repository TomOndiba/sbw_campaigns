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

if ($entity->canWriteToContainer(0, 'object', Reward::SUBTYPE) && !$entity->started) {
	$items = [
			[
			'name' => 'rewards:add',
			'text' => elgg_echo('campaigns:rewards:add'),
			'href' => "campaigns/edit/$entity->guid/rewards#campaigns-reward-form",
			'class' => 'elgg-button elgg-button-action',
		]
	];
	echo elgg_view_menu('campaigns:rewards:module', [
		'items' => $items,
		'class' => 'elgg-menu-hz',
		'entity' => $entity,
	]);
}