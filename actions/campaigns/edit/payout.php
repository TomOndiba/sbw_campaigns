<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof SBW\Campaigns\Campaign) {
	$error = elgg_echo('campaigns:error:not_found');
	return elgg_error_response($error, REFERRER, ELGG_HTTP_NOT_FOUND);
}

if (!$entity->canEdit()) {
	$error = elgg_echo('campaigns:error:permissions');
	return elgg_error_response($error, REFERRER, ELGG_HTTP_FORBIDDEN);
}

$entity->payout_instructions = get_input('payout_instructions');

$data = [
	'entity' => $entity,
	'action' => 'payout',
];

$message = elgg_echo('campaigns:edit:payout:success');
return elgg_ok_response($data, $message);