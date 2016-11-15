<?php

namespace SBW\Campaigns;

use ElggBatch;
use ElggEntity;
use hypeJunction\Payments\Amount;
use hypeJunction\Payments\Order;
use hypeJunction\Payments\OrderInterface;
use hypeJunction\Payments\ProcessingFee;
use hypeJunction\Payments\Transaction;

class Payments {

	/**
	 * Returns available payment methods
	 * 
	 * @param string $hook   "payment_methods"
	 * @param string $type   "campaigns"
	 * @param array  $return Methods
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function getPaymentMethods($hook, $type, $return, $params) {

		$entity = elgg_extract('entity', $params);

		if (elgg_is_active_plugin('payments_wire')) {

			$percentile = (float) elgg_get_plugin_setting('wire_percentile_fee', 'sbw_campaigns');
			$flat = (float) elgg_get_plugin_setting('wire_flat_fee', 'sbw_campaigns');

			$fee = [];
			if ($percentile) {
				$fee[] = "{$percentile}%";
			}
			if ($flat) {
				$fee[] = "{$flat}{$entity->currency}";
			}

			$return[] = [
				'id' => 'wire',
				'name' => elgg_echo('payments:method:wire'),
				'icon' => '',
				'fee' => implode(' + ', $fee),
			];
		}

		if (elgg_is_active_plugin('payments_stripe')) {
			$adapter = new \hypeJunction\Payments\Stripe\Adapter();
			$spec = $adapter->getCountrySpec();
			if (in_array(strtolower($entity->currency), $spec->supported_payment_currencies)) {
				$percentile = (float) elgg_get_plugin_setting('stripe_percentile_fee', 'sbw_campaigns');
				$flat = (float) elgg_get_plugin_setting('stripe_flat_fee', 'sbw_campaigns');

				$fee = [];
				if ($percentile) {
					$fee[] = "{$percentile}%";
				}
				if ($flat) {
					$fee[] = "{$flat}{$entity->currency}";
				}

				$return[] = [
					'id' => 'stripe',
					'name' => elgg_echo('payments:method:stripe'),
					'icon' => elgg_view('payments/method/stripe_icons'),
					'fee' => implode(' + ', $fee),
				];
			}
		}

		if (elgg_is_active_plugin('payments_paypal_api')) {

			$percentile = (float) elgg_get_plugin_setting('paypal_percentile_fee', 'sbw_campaigns');
			$flat = (float) elgg_get_plugin_setting('paypal_flat_fee', 'sbw_campaigns');

			$fee = [];
			if ($percentile) {
				$fee[] = "{$percentile}%";
			}
			if ($flat) {
				$fee[] = "{$flat}{$entity->currency}";
			}

			$return[] = [
				'id' => 'paypal',
				'name' => elgg_echo('payments:method:paypal'),
				'icon' => elgg_view('output/img', array(
					'src' => elgg_get_simplecache_url('payments/method/pp-logo-100px.png'),
					'alt' => elgg_echo('payments:method:paypal'),
				)),
				'fee' => implode(' + ', $fee),
			];
		}

		return $return;
	}

	/**
	 * Returns charges associated with the campaign
	 *
	 * @param string $hook   "charges"
	 * @param string $type   "campaigns"
	 * @param array  $return Charges
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function getCharges($hook, $type, $return, $params) {

		$order = elgg_extract('order', $params);

		if (!$order instanceof OrderInterface) {
			return;
		}

		switch ($order->payment_method) {

			case 'paypal' :
				$percentile = (float) elgg_get_plugin_setting('paypal_percentile_fee', 'sbw_campaigns');
				$flat = elgg_get_plugin_setting('paypal_flat_fee', 'sbw_campaigns', '0');

				if ($percentile || $flat) {
					$flat = Amount::fromString($flat, $order->getCurrency());
					$return[] = new ProcessingFee('paypal_fee', $percentile, $flat);
				}
				break;

			case 'wire' :
				$percentile = (float) elgg_get_plugin_setting('wire_percentile_fee', 'sbw_campaigns');
				$flat = elgg_get_plugin_setting('wire_flat_fee', 'sbw_campaigns', '0');

				if ($percentile || $flat) {
					$flat = Amount::fromString($flat, $order->getCurrency());
					$return[] = new ProcessingFee('wire_fee', $percentile, $flat);
				}
				break;

			case 'stripe' :
				$percentile = (float) elgg_get_plugin_setting('wire_percentile_fee', 'sbw_campaigns');
				$flat = elgg_get_plugin_setting('wire_flat_fee', 'sbw_campaigns', '0');

				if ($percentile || $flat) {
					$flat = Amount::fromString($flat, $order->getCurrency());
					$return[] = new ProcessingFee('stripe_fee', $percentile, $flat);
				}
				break;
		}

		return $return;
	}

	/**
	 * Marks transaction as paid and creates a new donation object
	 *
	 * @param string $hook   "transaction:paid"
	 * @param string $type   "payments"
	 * @param array  $return Status set?
	 * @param array  $params Hook params
	 * @return bool
	 */
	public static function processPaidTransaction($hook, $type, $return, $params) {

		$transaction = elgg_extract('entity', $params);
		if (!$transaction instanceof Transaction) {
			return;
		}

		$ia = elgg_set_ignore_access(true);

		$campaign = $transaction->getMerchant();
		$order = $transaction->getOrder();

		if (!$campaign instanceof Campaign || !$order instanceof OrderInterface) {
			elgg_set_ignore_access($ia);
			return;
		}

		if (elgg_instanceof($transaction, 'object', Transaction::SUBTYPE)) {
			$donation = Donation::getFromTransactionId($transaction->transaction_id);

			if ($donation) {
				elgg_set_ignore_access($ia);
				return true;
			}

			$site = elgg_get_site_entity();

			$donation = new Donation();
			$donation->owner_guid = $site->guid;
			$donation->email = $transaction->email;
			$donation->name = $transaction->name;
			$donation->anonymous = $transaction->anonymous;
			$donation->net_amount = $order->getSubtotalAmount()->getAmount();
			$donation->gross_amount = $order->getTotalAmount()->getAmount();
			$donation->currency = $order->getTotalAmount()->getCurrency();
			$donation->transaction_id = $transaction->getId();
			$donation->container_guid = $campaign->guid;
			$donation->access_id = $campaign->access_id;
			$donation->save();

			$campaign->addDonation($donation);

			foreach ($order->all() as $item) {
				$product = $item->getProduct();
				if (!$product instanceof Reward) {
					continue;
				}

				$product->addStock(-$item->getQuantity());
				add_entity_relationship($product->guid, 'claimed', $transaction->guid);
			}

			$subject = elgg_echo('campaigns:transaction:paid:notify:subject', [$campaign->getDisplayName()]);
			$body = elgg_echo('campaigns:transaction:paid:notify:body', [
				$transaction->getAmount()->format(),
				elgg_echo("payments:method:$transaction->payment_method"),
				$campaign->getDisplayName(),
				$campaign->getURL(),
			]);

			$from = elgg_get_site_entity()->email;
			$to = $transaction->email;
			if ($to) {
				elgg_send_email($from, $to, $subject, $body, [
					'campaign' => $campaign,
					'donation' => $donation,
					'transaction' => $transaction,
				]);
			}
		} else if (elgg_instanceof($transaction, 'object', Balance::SUBTYPE)) {
			$subject = elgg_echo('campaigns:balance:paid:notify:subject', [$campaign->getDisplayName()]);
			$body = elgg_echo('campaigns:balance:paid:notify:body', [
				$transaction->getAmount()->format(),
				elgg_echo("payments:method:$transaction->payment_method"),
				$campaign->getDisplayName(),
				$campaign->getURL(),
			]);

			$from = elgg_get_site_entity()->email;
			$managers = $campaign->getManagers();
			foreach ($managers as $manager) {
				elgg_send_email($from, $manager->email, $subject, $body, [
					'campaign' => $campaign,
					'donation' => $donation,
					'transaction' => $transaction,
				]);
			}
		}

		elgg_set_ignore_access($ia);
		return true;
	}

	/**
	 * Marks transaction as failed and removes associated donation object
	 *
	 * @param string $hook   "transaction:failed"
	 * @param string $type   "payments"
	 * @param array  $return Status set?
	 * @param array  $params Hook params
	 * @return bool
	 */
	public static function processFailedTransaction($hook, $type, $return, $params) {

		$transaction = elgg_extract('entity', $params);
		if (!$transaction instanceof Transaction) {
			return;
		}

		$ia = elgg_set_ignore_access(true);

		$campaign = $transaction->getMerchant();
		$data = $transaction->getDetails();

		if (!$campaign instanceof Campaign) {
			elgg_set_ignore_access($ia);
			return;
		}

		if (elgg_instanceof($transaction, 'object', Transaction::SUBTYPE)) {
			$donation = Donation::getFromTransactionId($transaction->transaction_id);

			$subject = elgg_echo('campaigns:transaction:failed:notify:subject', [$campaign->getDisplayName()]);
			$body = elgg_echo('campaigns:transaction:failed:notify:body', [
				$transaction->getAmount()->format(),
				elgg_echo("payments:method:$transaction->payment_method"),
				$campaign->getDisplayName(),
				$transaction->transaction_id,
				$campaign->getURL(),
			]);

			$from = elgg_get_site_entity()->email;
			$to = $transaction->email;
			if ($to) {
				elgg_send_email($from, $to, $subject, $body, [
					'campaign' => $campaign,
					'transaction' => $transaction,
					'donation' => $donation,
				]);
			}

			if (!$donation) {
				elgg_set_ignore_access($ia);
				return true;
			}

			$campaign->removeDonation($donation);
			$donation->delete();

			$order = $transaction->getOrder();
			if ($order) {
				foreach ($order->all() as $item) {
					$product = $item->getProduct();
					if (!$product instanceof Reward) {
						continue;
					}

					$product->addStock($item->getQuantity());
					remove_entity_relationship($product->guid, 'claimed', $transaction->guid);
				}
			}
		}

		elgg_set_ignore_access($ia);
		return true;
	}

	/**
	 * Marks transaction as refunded and removes associated donation object
	 *
	 * @param string $hook   "transaction:refunded"
	 * @param string $type   "payments"
	 * @param array  $return Status set?
	 * @param array  $params Hook params
	 * @return bool
	 */
	public static function processRefundedTransaction($hook, $type, $return, $params) {

		$transaction = elgg_extract('entity', $params);
		if (!$transaction instanceof Transaction) {
			return;
		}

		$ia = elgg_set_ignore_access(true);

		$campaign = $transaction->getMerchant();
		$data = $transaction->getDetails();

		if (!$campaign instanceof Campaign) {
			elgg_set_ignore_access($ia);
			return;
		}

		if (elgg_instanceof($transaction, 'object', Transaction::SUBTYPE)) {
			$donation = Donation::getFromTransactionId($transaction->transaction_id);

			$subject = elgg_echo('campaigns:transaction:refunded:notify:subject', [$campaign->getDisplayName()]);
			$body = elgg_echo('campaigns:transaction:refunded:notify:body', [
				$transaction->getAmount()->format(),
				elgg_echo("payments:method:$transaction->payment_method"),
				$campaign->getDisplayName(),
				$campaign->getURL(),
			]);

			$from = elgg_get_site_entity()->email;
			$to = $transaction->email;
			if ($to) {
				elgg_send_email($from, $to, $subject, $body, [
					'campaign' => $campaign,
					'transaction' => $transaction,
					'donation' => $donation,
				]);
			}

			if (!$donation) {
				elgg_set_ignore_access($ia);
				return true;
			}

			if ($campaign->isActive()) {
				// We don't want to affect totals of campaigns that have ended
				// Doing this only for active campaigns
				$campaign->removeDonation($donation);
				$donation->delete();
			}

			$order = $transaction->getOrder();
			if ($order) {
				foreach ($order->all() as $item) {
					$product = $item->getProduct();
					if (!$product instanceof Reward) {
						continue;
					}

					$product->addStock($item->getQuantity());
					remove_entity_relationship($product->guid, 'claimed', $transaction->guid);
				}
			}
		}

		elgg_set_ignore_access($ia);
		return true;
	}

	/**
	 * Refund transactions when tipping point campaign is ended without reaching
	 * target
	 *
	 * @param string     $event  "end"
	 * @param string     $type   "object"
	 * @param ElggEntity $entity Campaign
	 * @return void
	 */
	public static function endCampaign($event, $type, $entity) {

		if (!$entity instanceof Campaign) {
			return;
		}

		if ($entity->model == 'tipping_point') {
			$fee = (float) elgg_get_plugin_setting('tipping_point_fee', 'sbw_campaigns', 0);
			if ($entity->funded_percentage < 100) {
				$ia = elgg_set_ignore_access(true);

				$transactions = new ElggBatch('elgg_get_entities_from_relationship', [
					'types' => 'object',
					'subtypes' => Transaction::SUBTYPE,
					'relationship' => 'merchant',
					'relationship_guid' => (int) $entity->guid,
					'inverse_relationship' => false,
					'limit' => 0,
				]);

				foreach ($transactions as $transaction) {
					$params = [
						'entity' => $transaction,
					];
					elgg_trigger_plugin_hook('refund', 'payments', $params, false);
				}

				elgg_set_ignore_access($ia);
				return;
			}
		} else if ($entity->model == 'pot') {
			$fee = (float) elgg_get_plugin_setting('money_pot_fee', 'sbw_campaigns', 0);
		} else {
			return;
		}

		$ia = elgg_set_ignore_access(true);
		$donations = new ElggBatch('elgg_get_entities', [
			'types' => 'object',
			'subtypes' => Donation::SUBTYPE,
			'container_guids' => $entity->guid,
			'limit' => 0,
		]);

		$site = elgg_get_site_entity();

		$order = new Order();
		$order->setCurrency($entity->currency);
		$order->setCustomer($site);
		$order->setMerchant($entity);

		foreach ($donations as $donation) {
			/* @var $donation Donation */
			$item = new Contribution();
			$item->setPrice($donation->getNetAmount());
			$item->title = elgg_echo('campaigns:contribution:from', [$donation->name]);
			$order->add($item);
		}

		$commission = new Commission('site_commission', -$fee);
		$order->setCharges([$commission]);

		$balance = new Balance();
		$balance->setOrder($order);
		$balance->setPaymentMethod('wire');
		$balance->email = $entity->getOwnerEntity()->email;
		$balance->owner_guid = $site->guid;
		$balance->container_guid = $entity->guid;
		$balance->access_id = $entity->write_access_id;
		$balance->save();
		elgg_set_ignore_access($ia);
	}

}
