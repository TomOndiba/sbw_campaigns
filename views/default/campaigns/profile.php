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

$video = elgg_view('campaigns/module/media', $vars);

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
if ($comments) {
	$comments = elgg_view_module('aside', elgg_echo('comments'), $comments, [
		'class' => 'campaigns-module',
	]);
}

$rewards = elgg_view('campaigns/modules/rewards', $vars);

$skip = ['access', 'give', 'follow', 'unfollow', 'likes', 'unlike', 'likes_count', 'discovery:share'];

$sections = elgg()->menus->getMenu('entity', [
			'entity' => $entity,
		])->getSections();
foreach ($sections as $section => $items) {
	foreach ($items as $item) {
		if (in_array($item->getName(), $skip)) {
			continue;
		}
		if (in_array($item->getName(), ['discovery:edit'])) {
			$text = $item->getText();
			$text .= $item->getTooltip();
			$item->setText($text);
		}
		elgg_register_menu_item('page', $item);
	}
}

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
		<?= $media ?>
		<?= $about ?>
		<?= $news ?>
		<?= $donations ?>
		<?= $comments ?>
	</div>
	<div class="campaigns-sidebar">
		<?= $stats ?>
		<?= $share ?>
		<?= $menu ?>
		<?= $data ?>
		<?= $rewards ?>
	</div>
</div>