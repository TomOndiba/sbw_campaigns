<?php

use SBW\Campaigns\Campaign;

$entity = elgg_extract('entity', $vars);

if (!$entity instanceof Campaign) {
	return;
}

$media = elgg_view('campaigns/modules/media', $vars);

$news = elgg_view('campaigns/modules/news', $vars);
if ($news) {
	$news = elgg_view_module('aside', elgg_echo('campaigns:news'), $news, [
		'class' => 'campaigns-module',
	]);
}

$about = elgg_view('campaigns/modules/about', $vars);
if ($about) {
	$about = elgg_view_module('aside', elgg_echo('campaigns:about'), $about, [
		'class' => 'campaigns-module',
	]);
}

$donations = elgg_view('campaigns/modules/donations', $vars);
if ($donations) {
	$donations = elgg_view_module('aside', elgg_echo('campaigns:donations'), $donations, [
		'class' => 'campaigns-module',
	]);
}

$comments = elgg_view_comments($entity);
if ($comments) {
	$comments = elgg_view_module('aside', elgg_echo('comments'), $comments, [
		'class' => 'campaigns-module',
	]);
}

echo $media;
echo $about;
echo $news;
echo $donations;
echo $comments;