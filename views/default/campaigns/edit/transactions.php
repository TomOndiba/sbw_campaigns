<?php

use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Campaign || !$entity->canEdit()) {
	return;
}

elgg_register_menu_item('title', [
	'name' => 'transactions:download',
	'href' => "campaigns/download/$entity->guid/transactions?view=csv",
	'text' => elgg_echo('download'),
	'link_class' => 'elgg-button elgg-button-action',
]);

echo elgg_view('payments/listing/transactions/merchant', [
	'entity' => $entity,
]);
