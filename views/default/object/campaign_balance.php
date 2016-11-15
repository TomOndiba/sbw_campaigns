<?php

$entity = elgg_extract('entity', $vars);

$instructions = elgg_view('output/longtext', [
	'value' => $entity->payout_instructions ? : elgg_echo('campaigns:payout:no_information'),
]);

echo elgg_view_module('info', elgg_echo('campaigns:payout:details'), $instructions);

echo elgg_view('object/transaction', $vars);