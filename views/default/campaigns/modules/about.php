<?php

use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

echo elgg_view('output/longtext', [
	'value' => $entity->description,
]);

$links[] = [
	'name' => 'terms:campaign',
	'target' => '_blank',
	'href' => 'campaigns/terms/campaign?guid=' . $entity->guid,
	'text' => elgg_echo('campaigns:terms:campaign', [$entity->getDisplayName()]),
	'class' => 'elgg-lightbox',
];

$terms = elgg_get_plugin_setting('terms:donor', 'sbw_campaigns');
if ($terms) {
	$links[] = [
		'name' => 'terms:donor',
		'target' => '_blank',
		'href' => 'campaigns/terms/donor',
		'text' => elgg_echo('campaigns:terms:donor'),
		'class' => 'elgg-lightbox',
	];
}

echo elgg_view_menu('campaigns:about:rules', [
	'items' => $links,
]);