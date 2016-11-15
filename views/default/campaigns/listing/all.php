<?php

echo elgg_list_entities_from_metadata([
	'types' => 'object',
	'subtypes' => SBW\Campaigns\Campaign::SUBTYPE,
	'metadata_name_value_pairs' => [
			[
			'name' => 'calendar_end',
			'value' => time() - 24 * 60 * 60,
			'operand' => '>='
		],
	],
	'order_by_metadata' => [
		'name' => 'calendar_start',
		'direction' => 'ASC',
		'as' => 'integer',
	],
	'no_results' => elgg_echo('campaigns:no_results'),
	'preload_owners' => true,
	'preload_containers' => true,
	'list_type' => 'gallery',
	'gallery_class' => 'campaigns-gallery',
]);
