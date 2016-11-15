<?php

use hypeJunction\Payments\Amount;


$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \SBW\Campaigns\Donation) {
	return;
}

$cion = '';
if ($entity->anonymous) {
	$title = elgg_echo('campaigns:anonymous');
} else {
	$users = get_user_by_email($entity->email);
	if ($users) {
		$user = array_shift($users);
		$title = elgg_view('output/url', [
			'href' => $user->getURL(),
			'text' => $user->getDisplayName(),
			'target' => '_blank',
		]);
		$icon = elgg_view_entity_icon($user, 'small');
	} else {
		$title = $entity->name;
	}
}

$title = elgg_format_element('h3', [
	'class' => 'campaigns-donation-name',
		], $title);

$amount = elgg_format_element('div', [
	'class' => 'campaigns-donation-amount',
		], $entity->getNetAmount()->format());

echo elgg_view_image_block($icon, $title, [
	'image_alt' => $amount,
]);
