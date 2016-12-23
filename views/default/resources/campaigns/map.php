<?php

if (!elgg_is_active_plugin("amap_maps_api")) {
    forward('', '404');
}

elgg_load_library('elgg:amap_maps_api');
elgg_load_library('elgg:amap_maps_api_geo');

$user = elgg_get_logged_in_user_entity();

// Retrieve map width 
$mapwidth = amap_ma_get_map_width();

// Retrieve map height
$mapheight = amap_ma_get_map_height();

// set default parameters
$limit = get_input('limit', 0);
$title = elgg_echo('campaigns:map');

// get variables
$s_location = get_input("l");
$s_radius = (int) get_input("r");
$s_keyword = get_input("q");
$showradius = get_input("sr");

$initial_load = 'location';
if (($s_location)) {
    $search_radius_txt = '';
    $s_radius = ($s_radius ? $s_radius : AMAP_MA_DEFAULT_RADIUS);
    $search_radius_txt = $s_radius;

    // retrieve coords of location asked, if any
    $coords = amap_ma_geocode_location($s_location);

    if ($coords) {
        $s_radius = amap_ma_get_default_radius_search($s_radius);
        $search_location_txt = $s_location;
        $title = elgg_echo('campaigns:map:nearby:search', array($search_location_txt));
    }
	$initial_load = '';
}


// set breadcrumb
elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
elgg_push_breadcrumb(elgg_echo('campaigns:map'));

// load the search form only in global view
$body_vars = array();
$body_vars['s_action'] = 'campaigns/nearby_search';
$body_vars['initial_location'] = $search_location_txt;
$body_vars['initial_radius'] = $search_radius_txt;
$body_vars['initial_keyword'] = $s_keyword;
$body_vars['initial_load'] = $initial_load;
if ($user->location) {
    $body_vars['my_location'] = $user->location;
    if (isset($initial_load) && $initial_load == 'location') {
        $body_vars['initial_location'] = $user->location;
    }
}
$form_vars = array('enctype' => 'multipart/form-data');

$content = elgg_view_form('amap_maps_api/nearby', $form_vars, $body_vars);
$content .= elgg_view('amap_maps_api/map_box', array(
    'mapwidth' => $mapwidth,
    'mapheight' => $mapheight,
));

$vars['filter_context'] = 'map';

$filter = elgg_view('campaigns/filter', $vars);

$params = array(
    'content' => $content,
    'title' => $title,
    'filter' => $filter,
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

