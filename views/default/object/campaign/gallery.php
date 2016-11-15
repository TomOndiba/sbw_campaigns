<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \SBW\Campaigns\Campaign) {
	return;
}

if ($entity->video_url) {
	$player = elgg_view('output/player', [
		'href' => $entity->video_url,
	]);
}

if ($player) {
	$media = elgg_format_element('div', [
		'class' => 'scraper-card-flex',
			], $player);
} else {
	$media_size = elgg_extract('size', $vars, 'card');
	$media = elgg_view_entity_icon($entity, $media_size);
}

$content = elgg_view('output/longtext', [
	'value' => elgg_get_excerpt($entity->description),
		]);

$subtitle = [];
$container = $entity->getContainerEntity();
if ($container) {
	$link = elgg_view('output/url', [
		'href' => $container->getURL(),
		'text' => $container->getDisplayName(),
	]);
	$subtitle[] = elgg_echo('byline', [$link]);
}

$subtitle[] = elgg_echo("campaigns:model:$entity->model");

if ($entity->website) {
	$subtitle[] = elgg_view('output/url', [
		'icon' => 'globe',
		'href' => $entity->website,
		'target' => '_blank',
	]);
}

$items = \SBW\Campaigns\Menus::getProfileMenuItems($entity);
foreach ($items as &$item) {
	$item->addLinkClass('elgg-button elgg-button-action');
}

$metadata = elgg_view_menu('campaign:profile', [
	'entity' => $entity,
	'items' => $items,
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority',
		]);

if (!$entity->isPublished()) {
	$status = elgg_format_element('span', [
		'class' => 'campaigns-status campaigns-state-inactive',
			], elgg_echo('campaigns:status:draft'));
} else if (!$entity->isVerified()) {
	$status = elgg_format_element('span', [
		'class' => 'campaigns-status campaigns-state-inactive',
			], elgg_echo('campaigns:status:pending_verification'));
} else if ($entity->isActive()) {
	$status = elgg_format_element('span', [
		'class' => 'campaigns-status campaigns-state-active',
			], elgg_echo('campaigns:status:ongoing'));
} else if ($entity->calendar_end < time()) {
	$status = elgg_format_element('span', [
		'class' => 'campaigns-status campaigns-state-inactive',
			], elgg_echo('campaigns:status:ended'));
} else if ($entity->calendar_start < time()) {
	$status = elgg_format_element('span', [
		'class' => 'campaigns-status campaigns-state-inactive',
			], elgg_echo('campaigns:status:starting_soon'));
}

$stats = elgg_view('campaigns/stats', [
	'entity' => $entity,
]);

$title = elgg_view('output/url', [
	'text' => $entity->getDisplayName(),
	'href' => $entity->getURL(),
		]);

$title = elgg_format_element('h3', [
	'class' => 'elgg-title',
		], $title);

$subtitle = elgg_format_element('div', [
	'class' => 'elgg-subtext',
		], implode('<br />', $subtitle));

?>
<div class="campaigns-card">
	<div class="campaigns-card-head elgg-head">
		<?= $status ?>
		<h3><?= $title ?></h3>
		<?= $subtitle ?>
	</div>
	<div class="campaigns-card-media">
		<?= $media ?>
	</div>
	<div class="campaigns-card-body elgg-body">
		<?= $content ?>
	</div>
	<div class="campaigns-card-data">
		<?= $stats ?>
	</div>
	<div class="campaigns-card-foot elgg-foot">
		<?= $metadata ?>
	</div>
</div>
