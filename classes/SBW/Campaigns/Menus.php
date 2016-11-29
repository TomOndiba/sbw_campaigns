<?php

namespace SBW\Campaigns;

use ElggMenuItem;

class Menus {

	/**
	 * Setup filter menu on campaigns edit page
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:entity"
	 * @param ElggMenuItem[] $return Menu items
	 * @param array          $params Hook params
	 * @return ElggMenuItem[]
	 */
	public static function setupEditFilterMenu($hook, $type, $return, $params) {

		$route = elgg_extract('route', $params);
		if ($route != 'campaigns/edit') {
			return;
		}

		$entity = elgg_extract('entity', $params);
		$container = elgg_extract('container', $params);
		$filter_context = elgg_extract('filter_context', $params, 'about');

		$return[] = ElggMenuItem::factory([
					'name' => 'about',
					'href' => ($entity) ? "campaigns/edit/$entity->guid" : "campaigns/add/$container->guid",
					'text' => elgg_echo('campaigns:edit:about'),
					'priority' => 100,
					'selected' => $filter_context == 'about',
		]);

		$return[] = ElggMenuItem::factory([
					'name' => 'rewards',
					'href' => $entity ? "campaigns/edit/$entity->guid/rewards" : '#',
					'text' => elgg_echo('campaigns:edit:rewards'),
					'priority' => 200,
					'selected' => $filter_context == 'rewards',
					'item_class' => !$entity ? 'elgg-state-disabled' : '',
		]);

		$return[] = ElggMenuItem::factory([
					'name' => 'news',
					'href' => $entity ? "campaigns/edit/$entity->guid/news" : '#',
					'text' => elgg_echo('campaigns:edit:news'),
					'priority' => 200,
					'selected' => $filter_context == 'news',
					'item_class' => !$entity ? 'elgg-state-disabled' : '',
		]);

		$return[] = ElggMenuItem::factory([
					'name' => 'transactions',
					'href' => $entity ? "campaigns/edit/$entity->guid/transactions" : '#',
					'text' => elgg_echo('campaigns:edit:transactions'),
					'priority' => 200,
					'selected' => $filter_context == 'transactions',
					'item_class' => !$entity ? 'elgg-state-disabled' : '',
		]);

		$return[] = ElggMenuItem::factory([
					'name' => 'balance',
					'href' => $entity ? "campaigns/edit/$entity->guid/balance" : '#',
					'text' => elgg_echo('campaigns:edit:balance'),
					'priority' => 200,
					'selected' => $filter_context == 'balance',
					'item_class' => !$entity ? 'elgg-state-disabled' : '',
		]);

		return $return;
	}

	/**
	 * Setup campaign entity menu
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:entity"
	 * @param ElggMenuItem[] $return Menu items
	 * @param array          $params Hook params
	 * @return ElggMenuItem[]
	 */
	public static function setupCampaignEntityMenu($hook, $type, $return, $params) {

		$entity = elgg_extract('entity', $params);
		if (!$entity instanceof Campaign) {
			return;
		}

		$profile_items = self::getProfileMenuItems($entity);
		foreach ($profile_items as $item) {
			$return[] = $item;
		}

		$page_items = self::getPageMenuItems($entity);
		foreach ($profile_items as $item) {
			$return[] = $item;
		}
		return $return;
	}

	/**
	 * Setup reward
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:entity"
	 * @param ElggMenuItem[] $return Menu items
	 * @param array          $params Hook params
	 * @return ElggMenuItem[]
	 */
	public static function setupRewardEntityMenu($hook, $type, $return, $params) {

		$entity = elgg_extract('entity', $params);
		if (!$entity instanceof Reward) {
			return;
		}

		$campaign = $entity->getContainerEntity();

		if ($entity->canEdit()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'edit',
						'text' => elgg_echo('edit'),
						'icon' => 'pencil',
						'href' => "campaigns/edit/$campaign->guid/rewards?guid=$entity->guid#campaigns-reward-form",
						'priority' => 600,
			]);
		}

		if ($entity->canDelete()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'delete',
						'text' => elgg_echo('delete'),
						'icon' => 'delete',
						'href' => "action/entity/delete?guid=$entity->guid",
						'confirm' => true,
						'is_action' => true,
						'priority' => 700,
			]);
		}

		return $return;
	}

	/**
	 * Setup news item menu
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:entity"
	 * @param ElggMenuItem[] $return Menu items
	 * @param array          $params Hook params
	 * @return ElggMenuItem[]
	 */
	public static function setupNewsEntityMenu($hook, $type, $return, $params) {

		$entity = elgg_extract('entity', $params);
		if (!$entity instanceof NewsItem) {
			return;
		}

		$campaign = $entity->getContainerEntity();

		if ($entity->canEdit()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'edit',
						'text' => elgg_echo('edit'),
						'icon' => 'pencil',
						'href' => "campaigns/edit/$campaign->guid/news?guid=$entity->guid#campaigns-news-form",
						'priority' => 600,
			]);
		}

		if ($entity->canDelete()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'delete',
						'text' => elgg_echo('delete'),
						'icon' => 'delete',
						'href' => "action/entity/delete?guid=$entity->guid",
						'confirm' => true,
						'is_action' => true,
						'priority' => 700,
			]);
		}

		return $return;
	}

	/**
	 * Returns campaign profile menu items
	 *
	 * @param Campaign $entity Campaign entity
	 * @return ElggMenuItem[]
	 */
	public static function getProfileMenuItems(Campaign $entity) {

		$user = elgg_get_logged_in_user_entity();

		$return = [];

		if ($entity->isActive()) {
			if ($entity->model == 'tipping_point') {
				$text = elgg_echo('campaigns:pledge');
			} else {
				$text = elgg_echo('campaigns:donate');
			}

			$return[] = ElggMenuItem::factory([
						'name' => 'give',
						'text' => $text,
						'href' => elgg_http_add_url_query_elements("campaigns/give/$entity->guid", [
							'reward' => 'no_reward',
						]),
						'priority' => 300,
			]);
		}

		if ($user) {
			$subscribed = false;
			$methods = elgg_get_notification_methods();
			foreach ($methods as $method) {
				if (check_entity_relationship($user->guid, "notify$method", $entity->guid)) {
					$subscribed = true;
					break;
				}
			}

			if (!$subscribed) {
				$return[] = ElggMenuItem::factory([
							'name' => 'follow',
							'text' => elgg_echo('campaigns:follow'),
							'href' => "action/campaigns/follow?guid=$entity->guid",
							'is_action' => true,
							'item_class' => $subscribed ? 'hidden' : '',
							'priority' => 400,
							'deps' => ['campaigns/follow'],
				]);

				$return[] = ElggMenuItem::factory([
							'name' => 'unfollow',
							'text' => elgg_echo('campaigns:unfollow'),
							'href' => "action/campaigns/unfollow?guid=$entity->guid",
							'is_action' => true,
							'item_class' => $subscribed ? '' : 'hidden',
							'priority' => 400,
							'deps' => ['campaigns/follow'],
				]);
			}
		}

		if (elgg_is_active_plugin('hypeDiscovery')) {
			if (\hypeJunction\Discovery\is_discoverable($entity)) {
				$text = elgg_echo('discovery:entity:share');
				$return[] = ElggMenuItem::factory(array(
							'name' => 'discovery:share',
							'text' => $text,
							'href' => "opengraph/share/$entity->guid",
							'title' => elgg_echo('discovery:entity:share'),
							'link_class' => 'elgg-lightbox',
							'data-colorbox-opts' => json_encode([
								'maxWidth' => '600px',
							]),
							'data' => [
								'icon' => 'share',
							],
							'priority' => 700,
							'deps' => ['elgg/lightbox'],
				));
			}
		}

		return $return;
	}

	/**
	 * Returns campaign page menu items
	 *
	 * @param Campaign $entity Campaign entity
	 * @return ElggMenuItem[]
	 */
	public static function getPageMenuItems(Campaign $entity) {

		$user = elgg_get_logged_in_user_entity();

		$return = [];

		if ($entity->canEdit()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'edit',
						'text' => elgg_view_icon('pencil') . elgg_echo('campaigns:edit'),
						'icon' => 'pencil',
						'href' => "campaigns/edit/$entity->guid",
						'priority' => 600,
						'section' => 'owner',
			]);
		}

		if ($entity->canDelete()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'delete',
						'text' => elgg_view_icon('trash') . elgg_echo('delete'),
						'icon' => 'delete',
						'href' => "action/entity/delete?guid=$entity->guid",
						'confirm' => true,
						'is_action' => true,
						'priority' => 900,
						'section' => 'owner',
			]);
		}

		if (elgg_is_admin_logged_in()) {

			if (!$entity->isVerified()) {
				$return[] = ElggMenuItem::factory([
							'name' => 'verify',
							'text' => elgg_view_icon('checkmark') . elgg_echo('campaigns:verify'),
							'href' => "action/campaigns/verify?guid=$entity->guid",
							'is_action' => true,
							'confirm' => true,
							'section' => 'admin',
				]);
			}

			if (!$entity->started) {
				$return[] = ElggMenuItem::factory([
							'name' => 'manual_start',
							'text' => elgg_view_icon('play') . elgg_echo('campaigns:manual_start'),
							'href' => "action/campaigns/start?guid=$entity->guid",
							'confirm' => elgg_echo('campaigns:manual_start:confirm'),
							'section' => 'admin',
				]);
			} else if (!$entity->ended) {
				$return[] = ElggMenuItem::factory([
							'name' => 'manual_end',
							'text' => elgg_view_icon('stop') . elgg_echo('campaigns:manual_end'),
							'href' => "action/campaigns/end?guid=$entity->guid",
							'confirm' => elgg_echo('campaigns:manual_end:confirm'),
							'section' => 'admin',
				]);
			}
		}

		if (!$entity->isPublished() && $entity->canEdit()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'publish',
						'text' => elgg_view_icon('eye') . elgg_echo('campaigns:publish'),
						'href' => "action/campaigns/publish?guid=$entity->guid",
						'is_action' => true,
						'confirm' => true,
						'section' => 'owner',
			]);
		}

		if ($entity->canWriteToContainer(0, 'object', NewsItem::SUBTYPE)) {
			$return[] = ElggMenuItem::factory([
						'name' => 'news:add',
						'text' => elgg_view_icon('newspaper-o') . elgg_echo('campaigns:news:add'),
						'href' => "campaigns/edit/$entity->guid/news#campaigns-news-form",
						'section' => 'owner',
			]);
		}

		if ($entity->canWriteToContainer(0, 'object', Reward::SUBTYPE) && !$entity->started) {
			$return[] = ElggMenuItem::factory([
						'name' => 'rewards:add',
						'text' => elgg_view_icon('gift') . elgg_echo('campaigns:rewards:add'),
						'href' => "campaigns/edit/$entity->guid/rewards#campaigns-reward-form",
						'section' => 'owner',
			]);
		}

		if ($user) {
			$subscribed = false;
			$methods = elgg_get_notification_methods();
			foreach ($methods as $method) {
				if (check_entity_relationship($user->guid, "notify$method", $entity->guid)) {
					$subscribed = true;
					break;
				}
			}

			if ($subscribed) {
				$return[] = ElggMenuItem::factory([
							'name' => 'follow',
							'text' => elgg_view_icon('hand-o-right') . elgg_echo('campaigns:follow'),
							'href' => "action/campaigns/follow?guid=$entity->guid",
							'is_action' => true,
							'item_class' => $subscribed ? 'hidden' : '',
							'priority' => 400,
							'deps' => ['campaigns/follow'],
				]);

				$return[] = ElggMenuItem::factory([
							'name' => 'unfollow',
							'text' => elgg_view_icon('hand-o-right') . elgg_echo('campaigns:unfollow'),
							'href' => "action/campaigns/unfollow?guid=$entity->guid",
							'is_action' => true,
							'item_class' => $subscribed ? '' : 'hidden',
							'priority' => 400,
							'deps' => ['campaigns/follow'],
				]);
			}
		}

		if ($entity->canEdit() && $entity->started) {
			$return[] = ElggMenuItem::factory([
						'name' => 'campaigns:transaction:view',
						'text' => elgg_view_icon('usd') . elgg_echo('campaigns:transactions:view'),
						'href' => "campaigns/edit/$entity->guid/transactions",
						'section' => 'owner',
			]);
		}

		return $return;
	}

	/**
	 * Returns campaign reward menu items
	 *
	 * @param Reward $entity Reward entity
	 * @return ElggMenuItem[]
	 */
	public static function getRewardMenuItems(Reward $entity) {

		$campaign = $entity->getContainerEntity();
		if (!$campaign instanceof Campaign) {
			return;
		}

		if ($campaign->isActive() && $entity->inStock()) {
			$return[] = ElggMenuItem::factory([
						'name' => 'give',
						'text' => elgg_echo('campaigns:give:get_reward'),
						'href' => elgg_http_add_url_query_elements("campaigns/give/$campaign->guid", [
							'reward' => $entity->guid,
						]),
			]);
		}

		return $return;
	}

	/**
	 * Setup owner block menu
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:owner_block"
	 * @param ElggMenuItem[] $return Menu items
	 * @param array          $params Hook params
	 * @return ElggMenuItem[]
	 */
	public static function setupOwnerBlockMenu($hook, $type, $return, $params) {
		$entity = elgg_extract('entity', $params);

		if (!$entity instanceof \ElggUser) {
			return;
		}

		$return[] = ElggMenuItem::factory([
			'name' => 'campaigns',
			'text' => elgg_echo('campaigns'),
			'href' => "campaigns/owner/$entity->username",
			'section' => ''
		]);

		return $return;
	}
}
