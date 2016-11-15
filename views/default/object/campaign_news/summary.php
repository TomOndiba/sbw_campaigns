<?php

use SBW\Campaigns\NewsItem;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof NewsItem) {
	return;
}

$container = $entity->getContainerEntity();

$icon_size = elgg_extract('size', $vars, 'small');
$icon = '';
if ($entity->hasIcon($icon_size)) {
	$icon = elgg_view_entity_icon($entity, $icon_size, [
		'class' => 'center',
	]);
}

$content = elgg_view('output/longtext', [
	'value' => elgg_get_excerpt($entity->description),
		]);

if ($icon) {
	$content = elgg_view_image_block($icon, $content);
}

$subtitle = [];
$subtitle[] = elgg_view('page/elements/by_line', $vars);

$metadata = '';
if (!elgg_in_context('widgets')) {
	$params = $vars;
	$params['sort_by'] = 'priority';
	$params['class'] = 'elgg-menu-hz';
	$metadata = elgg_view_menu('entity', $params);
}

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'subtitle' => implode('<br />', $subtitle),
	'content' => $content,
	'metadata' => $metadata,
		]);
