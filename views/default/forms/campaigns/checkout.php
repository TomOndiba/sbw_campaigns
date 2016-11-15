<?php

use hypeJunction\Payments\SessionStorage;
use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof Campaign) {
	return;
}

$storage = new SessionStorage();
$order = $storage->get($entity->guid);

if (!$order) {
	forward("campaigns/give/$entity->guid");
}

$items = $order->all();
if (empty($items)) {
	forward("campaigns/give/$entity->guid");
	return;
}

echo elgg_view('payments/order', [
	'order' => $order,
]);

$user = elgg_get_logged_in_user_entity();

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
			[
			'#type' => 'email',
			'#label' => elgg_echo('campaigns:checkout:email'),
			'name' => 'email',
			'required' => true,
			'value' => elgg_extract('email', $vars, $user->email),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:checkout:name'),
			'name' => 'name',
			'required' => true,
			'value' => elgg_extract('name', $vars, $user->name),
		]
	]
]);

$contact = elgg_extract('contact', $vars);
echo elgg_view_field([
	'#type' => 'fieldset',
	'#class' => 'campaigns-postal-address',
	'align' => 'horizontal',
	'fields' => [
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:street_address'),
			'name' => "contact[street_address]",
			'value' => elgg_extract('street_address', $contact, $user->campaigns_street_address),
			'required' => true,
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:extended_address'),
			'name' => "{$prefix}[extended_address]",
			'value' => elgg_extract('extended_address', $contact, $user->campaigns_extended_address),
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:locality'),
			'name' => "contact[locality]",
			'value' => elgg_extract('locality', $contact, $user->campaigns_locality),
			'required' => true
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:region'),
			'name' => "contact[region]",
			'value' => elgg_extract('region', $contact, $user->campaigns_region),
			'required' => true
		],
			[
			'#type' => 'text',
			'#label' => elgg_echo('campaigns:postal_address:postal_code'),
			'name' => "contact[postal_code]",
			'value' => elgg_extract('postal_code', $contact, $user->campaigns_postal_code),
			'required' => true
		],
			[
			'#type' => 'country',
			'#label' => elgg_echo('campaigns:postal_address:country'),
			'name' => "contact[country_code]",
			'value' => elgg_extract('country_code', $contact, $user->campaigns_country_code),
			'required' => true
		],
	]
]);

$payment_method = $order->payment_method;

// An extension point for payment providers
$params = $vars;
$params['order'] = $order;
$params['#type'] = "campaigns/payment/$payment_method";
echo elgg_view_field($params);

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('campaigns:checkout:anonymize'),
	'name' => 'anonymize',
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('campaigns:checkout:subscribe'),
	'name' => 'subscribe',
	'checked' => true,
]);

$link = elgg_view('output/url', [
	'target' => '_blank',
	'href' => 'campaigns/terms/campaign?guid=' . $entity->guid,
	'text' => elgg_echo('campaigns:terms:campaign', [$entity->getDisplayName()]),
	'class' => 'elgg-lightbox',
		]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'name' => 'campaign_rules',
	'value' => '1',
	'label' => elgg_echo('campaigns:field:terms', [$link]),
	'required' => true,
]);

$terms = elgg_get_plugin_setting('terms:donor', 'sbw_campaigns');
if ($terms) {
	$link = elgg_view('output/url', [
		'target' => '_blank',
		'href' => 'campaigns/terms/donor',
		'text' => elgg_echo('campaigns:terms:donor'),
		'class' => 'elgg-lightbox',
	]);
	echo elgg_view_field([
		'#type' => 'checkbox',
		'name' => 'donor_terms',
		'value' => '1',
		'label' => elgg_echo('campaigns:field:terms', [$link]),
		'required' => true,
	]);
}

echo elgg_view('input/hidden', [
	'name' => 'guid',
	'value' => $entity->guid,
]);

$footer = elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
			[
			'#type' => 'submit',
			'value' => elgg_echo('campaigns:checkout:pay'),
		],
			[
			'#type' => 'campaigns/cancel',
			'entity' => $entity,
		]
	]
		]);

elgg_set_form_footer($footer);
