<?php

$id = get_input('id');
$annotation = elgg_get_annotation_from_id($id);

if (!$annotation instanceof ElggAnnotation) {
	$error = elgg_echo('campaigns:error:not_found');
	return elgg_error_response($error, REFERRER, ELGG_HTTP_NOT_FOUND);
}

if ($annotation->delete()) {
	$message = elgg_echo('campaigns:commitment:delete:success');
	return elgg_ok_response($message);
} else {
	$message = elgg_echo('campaigns:commitment:delete:error');
	return elgg_error_response($message);
}