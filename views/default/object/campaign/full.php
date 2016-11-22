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

//$menu = elgg()->menus->getMenu('entity', [
//	'entity' => $entity,
//	'handler' => 'campaigns',
//]);
//
//foreach ($menu->getSections() as $section => $items) {
//	foreach ($items as $item) {
//		elgg_register_menu_item('title', $item);
//	}
//}

echo elgg_view('object/elements/full', [
	'entity' => $entity,
	'summary' => '',
	'body' => $body,
	'icon' => '',
]);
