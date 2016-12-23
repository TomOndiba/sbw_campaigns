<?php

namespace SBW\Campaigns;

class Maps {

	/**
	 * Event callback to geocode campaign location
	 *
	 * @param string     $event  "create"|"update"
	 * @param string     $type   "object"
	 * @para \ElggObject $object Campaign
	 * @return void
	 */
	public static function geocodeLocation($event, $type, $object) {

		if (!elgg_is_active_plugin('amap_maps_api')) {
			return;
		}

		if (!$object instanceof Campaign) {
			return;
		}

		elgg_load_library('elgg:amap_maps_api');

		$location = $object->location;

		var_dump($location);
		
		if ($location) {
			amap_ma_save_object_coords($location, $object, 'sbw_campaigns');
		} else {
			$object->setLatLong('', '');
		}
	}

}
