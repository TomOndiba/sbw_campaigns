<?php

if (!elgg_is_active_plugin('amap_maps_api')) {
	return;
}

$entity = elgg_extract('entity', $vars);
if (!$entity->location) {
	return;
}

$google_api_key = trim(elgg_get_plugin_setting('google_api_key', AMAP_MA_PLUGIN_ID));
if (!$google_api_key) {
	return;
}

echo elgg_format_element('iframe', [
	'width' => '100%',
	'height' => '300',
	'frameborder' => 0,
	'src' => elgg_http_add_url_query_elements("https://www.google.com/maps/embed/v1/place", [
		'key' => $google_api_key,
		'q' => $entity->location,
	]),
]);