<?php

$context = 'campaigns';
if (elgg_is_logged_in()) {
	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = elgg_extract('filter_context', $vars, 'all');

	// generate a list of default tabs
	$tabs = array(
		'all' => array(
			'text' => elgg_echo('all'),
			'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
			'selected' => ($filter_context == 'all'),
			'priority' => 200,
		),
		'mine' => array(
			'text' => elgg_echo('mine'),
			'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/owner/$username",
			'selected' => ($filter_context == 'mine'),
			'priority' => 300,
		),
		'friend' => array(
			'text' => elgg_echo('friends'),
			'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$context/friends/$username",
			'selected' => ($filter_context == 'friends'),
			'priority' => 400,
		),
	);

	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
}

echo elgg_view('page/layouts/elements/filter', $vars);
