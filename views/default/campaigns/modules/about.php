<?php

use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

echo elgg_view('output/longtext', [
	'value' => $entity->description,
		]);
