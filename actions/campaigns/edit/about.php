<?php

use SBW\Campaigns\Campaign;

elgg_make_sticky_form('campaigns/edit/about');

$user = elgg_get_logged_in_user_entity();

$guid = get_input('guid');

if ($guid) {
	$entity = get_entity($guid);
	if (!$entity instanceof Campaign) {
		$error = elgg_echo('campaigns:error:not_found');
		return elgg_error_response($error, REFERRER, ELGG_HTTP_NOT_FOUND);
	}
	if (!$entity->canEdit()) {
		$error = elgg_echo('campaigns:error:permissions');
		return elgg_error_response($error, REFERRER, ELGG_HTTP_FORBIDDEN);
	}

	$container = $entity->getContainerEntity();

	$action = 'edit';
} else {
	$container_guid = get_input('container_guid');
	$container = get_entity($container_guid);
	if (!$container || !$container->canWriteToContainer(0, 'object', Campaign::SUBTYPE)) {
		$error = elgg_echo('campaigns:error:container_permissions');
		return elgg_error_response($error, REFERRER, ELGG_HTTP_FORBIDDEN);
	}

	$entity = new Campaign();
	$entity->owner_guid = $user->guid;
	$entity->container_guid = $container->guid;

	$action = 'create';
}

$title = get_input('title', '');
$description = get_input('description', '');
$rules = get_input('rules', '');

if (empty($title) || empty($description) || empty($rules)) {
	$error = elgg_echo('campaigns:error:required');
	return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
}
$terms = get_input('terms');
if (empty($terms)) {
	$error = elgg_echo('campaigns:error:terms');
	return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
}
$terms = time();

if (!$entity->guid || !$entity->started) {
	$calendar_start = (int) get_input('calendar_start', '');
	$calendar_end = (int) get_input('calendar_end', '');
	if (empty($calendar_start) || empty($calendar_end)) {
		$error = elgg_echo('campaigns:error:required');
		return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
	}

	$dt_ct = new DateTime();

	$cutoff_time = (int) elgg_get_plugin_setting('cutoff_time', 'sbw_campaigns', 12 * 3600);
	$dt_ct->setTimestamp($cutoff_time);

	$dt = new DateTime();

	$dt->setTimestamp($calendar_start);
	$dt->setTime($dt_ct->format('H'), $dt_ct->format('i'), $dt->format('s'));
	$calendar_start = $dt->getTimestamp();

	$dt->setTimestamp($calendar_end);
	$dt->setTime($dt_ct->format('H'), $dt_ct->format('i'), $dt->format('s'));
	$calendar_end = $dt->getTimestamp();

	if ($calendar_end <= $calendar_start) {
		$error = elgg_echo('campaigns:error:dates');
		return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
	}

	$model = get_input('model');
	if (!in_array($model, ['tipping_point', 'pot', 'relief'])) {
		$error = elgg_echo('campaigns:error:invalid');
		return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
	}

	$target_amount = (int) floor(get_input('target_amount'));
	$donation_minimum = (int) floor(get_input('donation_minimum'));
	$currency = get_input('currency');
	$target_unit = get_input('target_unit');

	if (empty($target_amount) || ($model == 'relief' && empty($target_unit)) || ($model != 'relief' && empty($currency))) {
		$error = elgg_echo('campaigns:error:required');
		return elgg_error_response($error, REFERRER, ELGG_HTTP_BAD_REQUEST);
	}
}

$access_id = get_input('access_id', '');
$tags = get_input('tags', '');
$video_url = get_input('video_url', '');
$website = get_input('website', '');
$location = get_input('location', '');

$entity->title = htmlentities($title, ENT_QUOTES, 'UTF-8');
$entity->description = $description;
$entity->rules = $rules;
$entity->tags = string_to_tag_array($tags);
$entity->video_url = $video_url;
$entity->website = $website;
$entity->location = $location;
$entity->{'terms:campaigner'} = $terms;
$entity->access_id = $access_id;

if (!$entity->guid || !$entity->started) {
	$entity->model = $model;
	$entity->calendar_start = $calendar_start;
	$entity->calendar_end = $calendar_end;
	if ($entity->model !== 'relief') {
		$entity->target_amount = \SebastianBergmann\Money\Money::fromString((string) $target_amount, $currency)->getAmount();
		$entity->donation_minimum = \SebastianBergmann\Money\Money::fromString((string) $donation_minimum, $currency)->getAmount();
	} else {
		$entity->target_amount = $target_amount;
		$entity->donation_minimum = $donation_minimum;
	}
	$entity->currency = $currency;
	$entity->target_unit = $target_unit;
}

if ($entity->save()) {
	elgg_clear_sticky_form('campaigns/edit/about');
	$entity->saveIconFromUploadedFile('icon');

	if ($entity->owner_guid == $user->guid) {
		$managers = (array) get_input('managers', []);
		$manager_collection_members = (array) get_members_of_access_collection($entity->write_access_id, true);
		$manager_collection_members[] = $entity->owner_guid;

		$remove = array_diff($manager_collection_members, $managers);
		$add = array_diff($managers, $manager_collection_members);

		foreach ($remove as $manager_guid) {
			if ($manager_guid == $entity->owner_guid) {
				continue;
			}

			$manager = get_entity($manager_guid);
			remove_user_from_access_collection($manager_guid, $entity->write_access_id);

			if (!$manager) {
				continue;
			}

			$subject = elgg_echo('campaigns:remove_manager:notify:subject');
			$body = elgg_echo('campaigns:remove_manager:notify:body', [
				$manager->getDisplayName(),
				$user->getDisplayName(),
				$entity->getDisplayName(),
			]);
			notify_user($manager_guid, $user->guid, $subject, $message, [
				'action' => 'remove_manager',
				'object' => $entity,
			]);
		}

		foreach ($add as $manager_guid) {
			$manager = get_entity($manager_guid);
			if (!$manager) {
				continue;
			}
			add_user_to_access_collection($manager->guid, $entity->write_access_id);
			$methods = elgg_get_notification_methods();
			foreach ($methods as $method) {
				elgg_add_subscription($manager->guid, $method, $campaign->guid);
			}

			if ($manager->guid != elgg_get_logged_in_user_guid()) {
				$subject = elgg_echo('campaigns:add_manager:notify:subject');
				$body = elgg_echo('campaigns:add_manager:notify:body', [
					$manager->getDisplayName(),
					$user->getDisplayName(),
					$entity->getDisplayName(),
					$entity->getURL(),
				]);
				notify_user($manager_guid, $user->guid, $subject, $message, [
					'action' => 'add_manager',
					'object' => $entity,
				]);
			}
		}
	}

	$status = get_input('status');
	if ($status == 'published' && !$entity->isPublished()) {
		$entity->publish();
	}

	$data = [
		'entity' => $entity,
		'action' => $action,
	];

	$message = elgg_echo('campaigns:success', [$entity->getDisplayName()]);
	return elgg_ok_response($data, $message, $entity->getURL());
}

$error = elgg_echo('campaigns:error:general');
return elgg_error_response($error, REFERRER, ELGG_HTTP_UNPROCESSABLE_ENTITY);
