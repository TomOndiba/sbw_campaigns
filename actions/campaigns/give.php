<?php

use hypeJunction\Payments\Amount;
use hypeJunction\Payments\Order;
use hypeJunction\Payments\SessionStorage;
use SBW\Campaigns\Campaign;
use SBW\Campaigns\Contribution;

elgg_make_sticky_form('campaigns/give');

$guid = get_input('guid');
$campaign = get_entity($guid);

if (!$campaign instanceof Campaign) {
	$error = elgg_echo('campaigns:error:not_found');
	return elgg_error_response($error, REFERRER, ELGG_HTTP_NOT_FOUND);
}

$reward = false;
$reward_id = get_input('reward', 'no_reward');
if ($reward_id !== 'no_reward') {
	$reward = get_entity($reward_id);
}

$currency = $campaign->currency;

$amounts = (array) get_input('amount', []);
$amount = elgg_extract($reward_id, $amounts);

if ($campaign->model != 'relief') {
	$amount = Amount::fromString($amount, $currency)->getAmount();
	$unit = $currency;
} else {
	$unit = $campaign->target_unit;
}

if ($reward) {
	$minimum = $reward->donation_minimum;
	$diff = $amount - $minimum;
} else {
	$minimum = $campaign->donation_minimum;
	$diff = $amount - $minimum;
}

if ($diff < 0) {
	if ($campaign->model != 'relief') {
		$minimum_str = (new Amount($minimum, $unit))->getConvertedAmount();
		$error = elgg_echo('campaigns:error:donation_amount_too_low', [$minimum_str, $unit]);
	} else {
		$error = elgg_echo('campaigns:error:donation_amount_too_low', [$minimum, $unit]);
	}
	return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
}

$payment_method = get_input('payment_method');
if (!$payment_method) {
	$error = elgg_echo('campaigns:error:payment_method_required');
	return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
}

$order = new Order();
$order->setMerchant($campaign);
$order->setCurrency($currency);

if ($reward) {
	$order->add($reward, 1);
}
if ($diff) {
	$price = new Amount($diff, $currency);

	$contribution = new Contribution();
	$contribution->setPrice($price);
	$order->add($contribution, 1);
}

$order->payment_method = $payment_method;

$charges = elgg_trigger_plugin_hook('charges', 'campaigns', [
	'order' => $order,
		]);

if ($charges) {
	$order->setCharges($charges);
}

$storage = new SessionStorage();
$storage->put($campaign->guid, $order);

elgg_clear_sticky_form('campaigns/give');

forward("campaigns/checkout/$campaign->guid");
