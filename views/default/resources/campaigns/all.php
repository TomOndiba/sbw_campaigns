<?php

elgg_register_title_button('campaigns', 'add', 'object', SBW\Campaigns\Campaign::SUBTYPE);

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');

$vars['filter_context'] = 'all';

$title = elgg_echo('campaigns:all');
$content = elgg_view('campaigns/listing/all', $vars);
$sidebar = elgg_view('campaigns/sidebar', $vars);

$layout = elgg_view_layout('content', $vars + [
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
]);

echo elgg_view_page($title, $layout, 'default', $vars);