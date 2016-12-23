<?php

elgg_ajax_gatekeeper();

if (!elgg_is_active_plugin("amap_maps_api")) {
	return elgg_error_response();
}

elgg_load_library('elgg:amap_maps_api');
elgg_load_library('elgg:amap_maps_api_geo');

// get variables
$s_location = get_input("s_location");
$s_radius = (int) get_input('s_radius', 0);
$s_keyword = get_input("s_keyword");
$showradius = get_input("showradius");
$initial_load = get_input("initial_load");
$s_change_title = get_input("s_change_title");

if ($s_radius > 0) {
	$search_radius_txt = amap_ma_get_radius_string($s_radius);
} else {
	$search_radius_txt = amap_ma_get_default_radius_search_string();
}

$s_radius = amap_ma_get_default_radius_search($s_radius);

// retrieve coords of location asked, if any
$coords = amap_ma_geocode_location($s_location);

$title = elgg_echo('campaigns:all');
$options = array([
		'types' => 'object',
		'subtypes' => SBW\Campaigns\Campaign::SUBTYPE,
		'metadata_name_value_pairs' => [
				[
				'name' => 'calendar_end',
				'value' => time() - 24 * 60 * 60,
				'operand' => '>='
			],
				[
				'name' => 'location',
				'value' => "('', NULL)",
				'operand' => 'NOT IN',
			],
		],
		'limit' => get_input('noofusers', 0),
		'offset' => get_input('proximity_offset', 0),
		'count' => false
		]);

if ($initial_load) {
	// retrieve coords of location asked, if any
	$user = elgg_get_logged_in_user_entity();
	if ($user->location) {
		$s_lat = $user->getLatitude();
		$s_long = $user->getLongitude();

		if ($s_lat && $s_long) {
			$s_radius = amap_ma_get_initial_radius();
			$search_radius_txt = $s_radius;
			$s_radius = amap_ma_get_default_radius_search($s_radius);
			$options = add_order_by_proximity_clauses($options, $s_lat, $s_long);
			$options = add_distance_constraint_clauses($options, $s_lat, $s_long, $s_radius);

			$title = elgg_echo('campaigns:map:nearby:search', array($user->location));
		}
	}
} else {
	if ($s_keyword) {
		$db_prefix = elgg_get_config("dbprefix");
		$query = sanitise_string($s_keyword);

		$options["joins"][] = "JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid";
		$options["wheres"][] = "oe.title LIKE '%$query%'";
	}

	if ($coords) {
		$search_location_txt = $s_location;
		$s_lat = $coords['lat'];
		$s_long = $coords['long'];

		if ($s_lat && $s_long) {
			$options = add_order_by_proximity_clauses($options, $s_lat, $s_long);
			$options = add_distance_constraint_clauses($options, $s_lat, $s_long, $s_radius);
		}
		$title = elgg_echo('campaigns:map:nearby:search', array($search_location_txt));
	}
}

$entities = elgg_get_entities_from_metadata($options);

$map_objects = array();
if ($entities) {
	foreach ($entities as $entity) {
		$entity = amap_ma_set_entity_additional_info($entity, 'title', 'description', $entity->location);
	}

	foreach ($entities as $e) {
		if ($e->getLatitude() && $e->getLongitude()) {
			$object_x = array();
			$object_x['guid'] = $e->getGUID();
			$object_x['title'] = amap_ma_remove_shits($e->getVolatileData('m_title'));
			$object_x['description'] = amap_ma_get_entity_description($e->getVolatileData('m_description'));
			$object_x['location'] = elgg_echo('amap_maps_api:location', array(amap_ma_remove_shits($e->getVolatileData('m_location'))));
			$object_x['lat'] = $e->getLatitude();
			$object_x['lng'] = $e->getLongitude();
			$object_x['icon'] = $e->getVolatileData('m_icon');
			$object_x['other_info'] = $e->getVolatileData('m_other_info');
			$object_x['map_icon'] = $e->getVolatileData('m_map_icon');
			$object_x['info_window'] = elgg_view('campaigns/info_window', [
				'entity' => $e,
			]);
			array_push($map_objects, $object_x);
		}
	}
} else {
	$content = elgg_echo('amap_maps_api:search:personalized:empty');
}

$result = array(
	'error' => false,
	'title' => $title,
	'location' => $search_location_txt,
	'radius' => $search_radius_txt,
	's_radius' => amap_ma_get_default_radius_search($s_radius, true),
	's_radius_no' => $s_radius,
	'content' => $content,
	'map_objects' => json_encode($map_objects),
	's_location_lat' => ($s_lat ? $s_lat : ''),
	's_location_lng' => ($s_long ? $s_long : ''),
	's_location_txt' => $search_location_txt,
	'sidebar' => $sidebar,
	's_change_title' => (isset($s_change_title) && $s_change_title == 0 ? false : true),
);

// release variables
unset($entities);
unset($map_objects);

echo json_encode($result);
exit;
