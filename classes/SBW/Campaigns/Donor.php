<?php

namespace SBW\Campaigns;

use ElggObject;

/**
 * @property int    $amount
 * @property string $currency
 */
class Donor extends ElggObject {

	const SUBTYPE = 'campaign_donor';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

}
