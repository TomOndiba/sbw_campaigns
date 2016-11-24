<?php

use SBW\Campaigns\Campaign;
use SBW\Campaigns\Menus;

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

$data = elgg_view('campaigns/modules/data', $vars);
if ($data) {
	$data = elgg_view_module('aside', false, $data, [
		'class' => 'campaigns-module',
	]);
}

$video = elgg_view('output/player', [
	'href' => $entity->video_url,
		]);
if ($video) {
	$video = elgg_format_element('div', [
		'class' => 'campaigns-cover-video scraper-card-flex',
			], $video);
}

$news = elgg_view('campaigns/modules/news', $vars);
if ($news) {
	$news = elgg_view_module('aside', elgg_echo('campaigns:news'), $news, [
		'class' => 'campaigns-module',
	]);
}

$about = elgg_view('campaigns/modules/about', $vars);
if ($about) {
	$about = elgg_view_module('aside', elgg_echo('campaigns:about'), $about, [
		'class' => 'campaigns-module',
	]);
}

$donations = elgg_view('campaigns/modules/donations', $vars);
if ($donations) {
	$donations = elgg_view_module('aside', elgg_echo('campaigns:donations'), $donations, [
		'class' => 'campaigns-module',
	]);
}

$comments = elgg_view_comments($entity);

$rewards = elgg_view('campaigns/modules/rewards', $vars);

$items = Menus::getPageMenuItems($entity);
foreach ($items as $item) {
	elgg_register_menu_item('page', $item);
}

$menu = elgg_view_menu('page', [
	'entity' => $entity,
	'sort_by' => 'priority',
		]);
if ($menu) {
	$menu = elgg_view_module('aside', '', $menu, [
		'class' => 'campaigns-module',
	]);
}
?>
<div class="elgg-module campaigns-profile">
	<div class="campaigns-main">
		<?= $video ?>
		<?= $about ?>
		<?= $news ?>
		<?= $donations ?>
		<?= $comments ?>
	</div>
	<div class="campaigns-sidebar">
		<?= $stats ?>
		<?= $menu ?>
		<?= $data ?>
		<?= $rewards ?>
	</div>
</div>