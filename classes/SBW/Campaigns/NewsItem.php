<?php

namespace SBW\Campaigns;

use ElggObject;

class NewsItem extends ElggObject {

	const SUBTYPE = 'campaign_news';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

}
