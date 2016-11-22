<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \SBW\Campaigns\Campaign) {
	return;
}

$funded_percentage = round((float) $entity->funded_percentage);
$backers = (int) $entity->backers;

$count = elgg_format_element('span', [
		'class' => 'campaigns-stats-counter',
			], "{$funded_percentage}%");
$funded = elgg_echo('campaigns:funded', [$count]);

$count = elgg_format_element('span', [
		'class' => 'campaigns-stats-counter',
			], "{$backers}");
$backers = elgg_echo('campaigns:backers', [$count]);

$diff = $entity->calendar_end - time();

if ($diff > 24 * 60 * 60) {
	$count = (int) floor($diff / (24 * 60 * 60));
	$count = elgg_format_element('span', [
		'class' => 'campaigns-stats-counter',
			], $count);
	$days = elgg_echo('campaigns:ends:in_days', [$count]);
} else if ($diff > 60 * 60) {
	$count = (int) floor($diff / (60 * 60));
	$count = elgg_format_element('span', [
		'class' => 'campaigns-stats-counter',
			], $count);
	$days = elgg_echo('campaigns:ends:in_hours', [$count]);
} else if ($diff > 0) {
	$count = (int) floor($diff / 60);
	$count = elgg_format_element('span', [
		'class' => 'campaigns-stats-counter',
			], $count);
	$days = elgg_echo('campaigns:ends:in_minutes', [$count]);
} else {
	$days = '';
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

?>
<div class="campaigns-stats">
	<div class="campaigns-progress-container">
		<div class="campaigns-progress-bar"
			 style="width:<?= $funded_percentage ?>%"
			 title="<?= elgg_echo('campaigns:funded_percentage', ["{$funded_percentage}%"]) ?>"
		></div>
	</div>
	<div class="campaigns-stats-counters">
		<div><?= $funded ?></div>
		<div><?= $backers ?></div>
		<div><?= $days ?></div>
	</div>
</div>
<div class="campaigns-actions">
	<?= $metadata ?>
</div>