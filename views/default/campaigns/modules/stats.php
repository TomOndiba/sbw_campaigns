<?php

use hypeJunction\Payments\Amount;
use SBW\Campaigns\Campaign;
use SBW\Campaigns\Donation;
use SBW\Campaigns\Menus;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Campaign) {
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

$sum_donation = 0;
$count_donation = 0;
$donations = elgg_get_entities([
	'types' => 'object',
	'subtypes' => Donation::SUBTYPE,
	'container_guids' => $entity->guid,
	'limit' => 0,
	'batch' => true,
]);
foreach ($donations as $donation) {
	$count_donation++;
	$sum_donation += $donation->net_amount;
}
$avg_donation = 0;
if ($count_donation) {
	$avg_donation = $sum_donation / $count_donation;
}

$count = elgg_format_element('span', [
		'class' => 'campaigns-stats-counter',
			], (new Amount($avg_donation, $entity->currency))->format());
$avg_donation = elgg_echo('campaigns:avg_donation', [$count]);

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
		<div><?= $avg_donation ?></div>
	</div>
</div>