<?php

use SBW\Campaigns\Campaign;
use SBW\Campaigns\NewsItem;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

echo elgg_list_entities([
	'types' => 'object',
	'subtypes' => NewsItem::SUBTYPE,
	'container_guids' => $entity->guid,
	'limit' => 10,
	'offset_key' => 'news',
	'pagination' => true,
	'pagination_type' => 'infinite',
	//'no_results' => elgg_echo('campaigns:news:no_results'),
		]);


if ($entity->canWriteToContainer(0, 'object', NewsItem::SUBTYPE)) {
	$items = [
			[
			'name' => 'news:add',
			'text' => elgg_echo('campaigns:news:add'),
			'href' => "campaigns/edit/$entity->guid/news#campaigns-news-form",
		]
	];
	echo elgg_view_menu('campaigns:news:module', [
		'items' => $items,
		'class' => 'elgg-menu-hz',
		'entity' => $entity,
	]);
}