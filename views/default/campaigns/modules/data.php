<?php

use hypeJunction\Payments\Amount;
use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

$data = [];

$data['model'] = [
	'icon' => 'line-chart',
	'label' => elgg_echo('campaigns:field:model'),
	'value' => elgg_echo("campaigns:model:$entity->model"),
];

if ($entity->model !== 'relief') {
	$minimum = (new Amount($entity->donation_minimum, $entity->currency))->getConvertedAmount();
	$unit = $entity->currency;
} else {
	$minimum = $entity->donation_minimum;
	$unit = $entity->target_unit;
}

$data['minimum_donation'] = [
	'icon' => 'usd',
	'label' => elgg_echo('campaigns:field:donation_minimum'),
	'value' => "$minimum $unit",
];

if ($entity->model !== 'relief') {
	$minimum = (new Amount($entity->target_amount, $entity->currency))->getConvertedAmount();
	$unit = $entity->currency;
} else {
	$minimum = $entity->target_amount;
	$unit = $entity->target_unit;
}

$data['target_amount'] = [
	'icon' => 'bullseye',
	'label' => elgg_echo('campaigns:field:target_amount'),
	'value' => "$minimum $unit",
];

if (date('Y', $entity->calendar_start) == date('Y', $entity->calendar_end)) {
	$start_format = 'F j';
} else {
	$start_format = 'F j, Y';
}
$period = elgg_echo('campaigns:funding_period', [
	date($start_format, $entity->calendar_start),
	date('F j, Y', $entity->calendar_end)
		]);

$data['funding_period'] = [
	'icon' => 'calendar',
	'label' => elgg_echo('campaigns:field:funding_period'),
	'value' => $period,
];

if ($entity->location) {
	$data['location'] = [
		'icon' => 'map-marker',
		'label' => elgg_echo('campaigns:field:location'),
		'value' => $entity->location,
	];
}

if ($entity->website) {
	$data['link'] = [
		'icon' => 'globe',
		'label' => elgg_echo('campaigns:field:website'),
		'value' => elgg_view('output/url', [
			'text' => parse_url($entity->website, PHP_URL_HOST),
			'href' => $entity->website,
		]),
	];
}
	
if (elgg_extract('full_view', $vars, false)) {
	$data['tags'] = [
		'icon' => 'tag',
		'label' => elgg_echo('campaigns:field:tags'),
		'value' => elgg_view('output/tags', [
			'entity' => $entity,
			'icon_class' => 'hidden',
		]),
	];

	$managers = elgg_view('campaigns/modules/managers', $vars);
	if ($managers) {
		$data['managers'] = [
			'icon' => 'envelope',
			'label' => elgg_echo('campaigns:field:managers'),
			'value' => $managers,
		];
	}
}

foreach ($data as $item) {
	?>
	<div class="campaigns-data-item">
		<?php
		echo elgg_view_icon($item['icon'], [
			'title' => $item['label'],
		]);
		echo elgg_format_element('span', [
			'class' => 'campaigns-data-item-value',
				], $item['value']);
		?>
	</div>
	<?php
}
