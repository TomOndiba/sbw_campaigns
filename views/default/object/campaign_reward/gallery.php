<?php

use SBW\Campaigns\Menus;
use SBW\Campaigns\Reward;
use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Reward) {
	return;
}

$icon = '';
$icon_size = elgg_extract('size', $vars, 'card');
if ($entity->hasIcon($icon_size)) {
	$icon = elgg_view_entity_icon($entity, $icon_size, [
		'href' => false,
	]);
}

$content = elgg_view('output/longtext', [
	'value' => $entity->description,
		]);

$subtitle = [];

$campaign = $entity->getContainerEntity();
if ($container->model !== 'relief') {
	$subtitle[] = $entity->getPrice()->format();
} else {
	$subtitle[] = elgg_echo('campaigns:rewards:donation_minimum', [$entity->donation_minimum, $entity->target_unit]);
}

$subtitle[] = elgg_echo('campaigns:rewards:in_stock', [$entity->getStock()]);

$items = Menus::getRewardMenuItems($entity);
if ($items) {
	foreach ($items as &$item) {
		$item->addLinkClass('elgg-button elgg-button-action');
	}
}

$metadata = elgg_view_menu('campaign:reward', [
	'entity' => $entity,
	'items' => $items,
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority',
		]);

$title = elgg_view('output/url', [
	'text' => $entity->getDisplayName(),
	'href' => $campaign->getURL(),
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
		<h3><?= $title ?></h3>
		<?= $subtitle ?>
	</div>
	<div class="campaigns-card-media">
		<?= $icon ?>
	</div>
	<div class="campaigns-card-body elgg-body">
		<?= $content ?>
	</div>
	<div class="campaigns-card-foot elgg-foot">
		<?= $metadata ?>
	</div>
</div>