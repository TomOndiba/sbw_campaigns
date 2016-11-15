<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \SBW\Campaigns\Campaign) {
	return;
}

$icon_size = elgg_extract('size', $vars, 'small');
$icon = elgg_view_entity_icon($entity, $icon_size);

$content = elgg_view('campaigns/profile', $vars);

$body = elgg_format_element('div', [
	'class' => 'campaigns-full-listing',
		], $content);


$subtitle = [];
$subtitle[] = elgg_view('page/elements/by_line', $vars);

$subtitle[] = elgg_echo("campaigns:model:$entity->model");

if ($entity->website) {
	$subtitle[] = elgg_view_icon('globe') . elgg_view('output/url', [
		'href' => $entity->website,
		'target' => '_blank',
	]);
}

$params = $vars;
$params['sort_by'] = 'priority';
$params['class'] = 'elgg-menu-hz';
$metadata = elgg_view_menu('entity', $params);

$summary = elgg_view('object/elements/summary', [
	'entity' => $entity,
	'title' => false,
	'subtitle' => implode('<br />', $subtitle),
	'metadata' => $metadata,
		]);

echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'summary' => $summary,
	'body' => $body,
	'icon' => $icon,
]);
