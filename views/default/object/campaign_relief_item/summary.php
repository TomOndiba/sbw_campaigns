<?php

use SBW\Campaigns\ReliefItem;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ReliefItem) {
	return;
}

$icon_size = elgg_extract('size', $vars, 'small');
$icon = elgg_view_entity_icon($entity, $icon_size, [
	'href' => false,
	'use_link' => false,
		]);

$content = elgg_view('output/longtext', [
	'value' => $entity->description,
		]);

$subtitle = [];
$campaign = $entity->getContainerEntity();

$quantity = $entity->required_quantity;
$donated = $entity->getCommitments();

$subtitle[] = elgg_echo('campaigns:relief_items:required', [max(0, $quantity - $donated), $quantity]);

$metadata = '';
if (!elgg_in_context('metadata')) {
	$params = $vars;
	$params['sort_by'] = 'priority';
	$params['class'] = 'elgg-menu-hz';
	$metadata = elgg_view_menu('entity', $params);
}

$summary = elgg_view('object/elements/summary', [
	'entity' => $entity,
	'title' => $entity->getDisplayName(),
	'subtitle' => implode('<br />', $subtitle),
	'content' => $content,
	'metadata' => $metadata,
		]);

echo elgg_view_image_block($icon, $summary);
