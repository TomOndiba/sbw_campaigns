<?php

$entity_guid = elgg_extract('guid', $vars);
$entity = get_entity($entity_guid);

if (!$entity instanceof SBW\Campaigns\Campaign) {
	forward('', '404');
}

elgg_set_config('current_campaign', $entity);

$container = $entity->getContainerEntity();

elgg_group_gatekeeper(true, $container->guid);

elgg_set_page_owner_guid($container->guid);

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "/campaigns/owner/$container->username");
} else {
	elgg_push_breadcrumb($container->getDisplayName(), "/campaigns/group/$container->guid");
}

elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());

if ($entity->model == 'tipping_point') {
	$title = elgg_echo('campaigns:give:pledge', [$entity->getDisplayName()]);
} else {
	$title = elgg_echo('campaigns:give:donate', [$entity->getDisplayName()]);
}

elgg_push_breadcrumb($title);

$vars['entity'] = $entity;

$content = elgg_view('campaigns/give', $vars);

if (elgg_is_xhr()) {
	echo elgg_view_module('lightbox', $title, $content);
	return;
}

$layout = elgg_view_layout('campaign_main', $vars + [
	'title' => $title,
	'content' => $content,
		]);

echo elgg_view_page($title, $layout, 'default', $vars);
