<?php

namespace SBW\Campaigns;

use ElggBatch;

class Cron {

	public static function endCampaigns() {

		sleep(1);
		
		$ia = elgg_set_ignore_access(true);

		$campaigns = new ElggBatch('elgg_get_entities_from_metadata', [
			'types' => 'object',
			'subtypes' => Campaign::SUBTYPE,
			'metadata_name_value_pairs' => [
					[
					'name' => 'calendar_end',
					'value' => time(),
					'operand' => '<=',
				]
			],
			'limit' => 0,
		]);

		foreach ($campaigns as $campaign) {
			/* @var $campaign Campaign */
			$campaign->end();
		}

		elgg_set_ignore_access($ia);

	}

	public static function startCampagns() {

		sleep(1);
		
		$ia = elgg_set_ignore_access(true);

		$campaigns = new ElggBatch('elgg_get_entities_from_metadata', [
			'types' => 'object',
			'subtypes' => Campaign::SUBTYPE,
			'metadata_name_value_pairs' => [
					[
					'name' => 'calendar_start',
					'value' => time(),
					'operand' => '<=',
				],
					[
					'name' => 'started',
					'value' => false,
				]
			],
			'limit' => 0,
		]);

		foreach ($campaigns as $campaign) {
			/* @var $campaign Campaign */
			$campaign->start();
		}

		elgg_set_ignore_access($ia);
	}

}
