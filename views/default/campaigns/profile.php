<?php

use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

$stats = elgg_view('campaigns/stats', $vars);

$video = elgg_view('output/player', [
	'href' => $entity->video_url,
		]);
if ($video) {
	$video = elgg_format_element('div', [
		'class' => 'campaigns-cover-video scraper-card-flex',
			], $video);
}

$news = elgg_view('campaigns/modules/news', $vars);
if ($news) {
	$news = elgg_view_module('info', elgg_echo('campaigns:news'), $news);
}

$about = elgg_view('campaigns/modules/about', $vars);
if ($about) {
	$about = elgg_view_module('info', elgg_echo('campaigns:about'), $about);
}

$rules = elgg_view('campaigns/modules/rules', $vars);
if ($rules) {
	$rules = elgg_view_module('info', elgg_echo('campaigns:rules'), $rules);
}

$donations = elgg_view('campaigns/modules/donations', $vars);
if ($donations) {
	$donations = elgg_view_module('info', elgg_echo('campaigns:donations'), $donations);
}

$rewards = elgg_view('campaigns/modules/rewards', $vars);
if ($rewards) {
	$rewards = elgg_view_module('info', elgg_echo('campaigns:rewards'), $rewards);
}
?>
<div class="elgg-module campaigns-intro">
	<?= $stats ?>
	<?= $video ?>
</div>
<div class="elgg-module campaigns-profile">
	<div class="campaigns-main">
		<div class="campaigns-details">
			<?= $news ?>
			<?= $about ?>
			<?= $rules ?>
			<?= $donations ?>
		</div>
	</div>
	<div class="campaigns-sidebar">
		<?= $rewards ?>
	</div>
</div>