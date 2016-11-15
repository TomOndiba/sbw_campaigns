<?php

use hypeJunction\Payments\Transaction;
use hypeJunction\Payments\TransactionInterface;
use SBW\Campaigns\Campaign;
use SBW\Campaigns\Reward;

$guid = elgg_extract('guid', $vars);
$campaign = get_entity($guid);
if (!$campaign instanceof Campaign || !$campaign->canEdit()) {
	forward('', '404');
}

$ts = time();
$filename = "transactions-{$campaign->guid}-{$ts}.csv";

elgg_set_http_header("Content-type: text/csv");
elgg_set_http_header("Content-Disposition: attachment; filename={$filename}");

$transactions = new ElggBatch('elgg_get_entities_from_relationship', [
	'types' => 'object',
	'subtypes' => Transaction::SUBTYPE,
	'relationship' => 'merchant',
	'relationship_guid' => (int) $campaign->guid,
	'inverse_relationship' => false,
	'limit' => 0,
		]);

$address_part = function(TransactionInterface $transaction, $part) {
	$shipping = $transaction->getOrder()->getShippingAddress();
	if (!$shipping) {
		return '';
	}
	return $shipping->$part ?: '';
};

$headers = [
	'invoice' => function(TransactionInterface $transaction) {
		return $transaction->guid;
	},
	'id' => function(TransactionInterface $transaction) {
		return $transaction->getId();
	},
	'method' => function(TransactionInterface $transaction) {
		return elgg_echo("payments:method:{$transaction->getPaymentMethod()}");
	},
	'status' => function(TransactionInterface $transaction) {
		return elgg_echo("payments:status:{$transaction->getStatus()}");
	},
	'anonymous' => function(TransactionInterface $transaction) {
		return $transaction->anonymous ? 'yes' : 'no';
	},
	'amount' => function(TransactionInterface $transaction) {
		return $transaction->getAmount()->format();
	},
	'payee' => function(TransactionInterface $transaction) {
		$customer = $transaction->getCustomer();
		return $customer ? $customer->name : '';
	},
	'payee_email' => function(TransactionInterface $transaction) {
		$customer = $transaction->getCustomer();
		return $customer ? $customer->email : '';
	},
	'merchant' => function(TransactionInterface $transaction) {
		$merchant = $transaction->getMerchant();
		return $merchant ? $merchant->title : '';
	},
	'reward' => function(TransactionInterface $transaction) {
		$items = $transaction->getOrder()->all();
		foreach ($items as $item) {
			$product = $item->getProduct();
			if ($product instanceof Reward) {
				return $product->title;
			}
		}
		return '';
	},
	'street_address' => $address_part,
	'extended_address' => $address_part,
	'locality' => $address_part,
	'region' => $address_part,
	'postal_code' => $address_part,
	'country_code' => $address_part,
];

$fh = @fopen('php://output', 'w');
$headerDisplayed = false;

foreach ($transactions as $transaction) {

	if (!$headerDisplayed) {
		// Use the keys from $data as the titles
		fputcsv($fh, array_keys($headers));
		$headerDisplayed = true;
	}

	$data = [];
	foreach ($headers as $key => $callback) {
		$data[$key] = call_user_func($callback, $transaction, $key);
	}

	// Put the data into the stream
	fputcsv($fh, $data);
}

fclose($fh);

