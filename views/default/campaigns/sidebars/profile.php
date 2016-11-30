<?php

use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

$stats = elgg_view('campaigns/modules/stats', $vars);
if ($stats) {
	$stats = elgg_view_module('aside', false, $stats, [
		'class' => 'campaigns-module',
	]);
}

$share = elgg_view('campaigns/modules/share', $vars);
if ($share) {
	$share = elgg_view_module('aside', false, $share, [
		'class' => 'campaigns-module',
	]);
}

$data = elgg_view('campaigns/modules/data', $vars);
if ($data) {
	$data = elgg_view_module('aside', false, $data, [
		'class' => 'campaigns-module',
	]);
}

$menu = elgg_view('campaigns/modules/menu', $vars);
if ($menu) {
	$menu = elgg_view_module('aside', '', $menu, [
		'class' => 'campaigns-module',
	]);
}

$rewards = elgg_view('campaigns/modules/rewards', $vars);
$relief_items = elgg_view('campaigns/modules/relief_items', $vars);

echo $stats;
echo $share;
echo $menu;
echo $data;
echo $rewards;
echo $relief_items;