<?php

use SBW\Campaigns\Menus;
use SBW\Campaigns\NewsItem;

$entity_guid = elgg_extract('guid', $vars);
$entity = get_entity($entity_guid);

if (!$entity instanceof NewsItem) {
	forward('', '404');
}

$campaign = $entity->getContainerEntity();
$container = $campaign->getContainerEntity();

elgg_group_gatekeeper(true, $container->guid);

elgg_set_page_owner_guid($container->guid);

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
if ($container instanceof ElggUser) {
	elgg_push_breadcrumb($container->getDisplayName(), "/campaigns/owner/$container->username");
} else {
	elgg_push_breadcrumb($container->getDisplayName(), "/campaigns/group/$container->guid");
}

elgg_push_breadcrumb($campaign->getDisplayName(), $campaign->getURL());

$vars['entity'] = $entity;

$title = $entity->getDisplayName();
$content = elgg_view_entity($entity, [
	'full_view' => true,
]);

if (elgg_is_xhr()) {
	echo elgg_view_module('lightbox', $title, $content);
	return;
}

$filter = elgg_view('campaigns/filters/news', $vars);

$layout = elgg_view_layout('campaign_main', $vars + [
	'title' => $title,
	'content' => $content,
	'filter' => $filter,
		]);

echo elgg_view_page($title, $layout, 'default', $vars);
