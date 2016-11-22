<?php
$class = 'elgg-layout campaigns-profile-layout clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
?>

<div class="<?php echo $class; ?>">
	<?php
	echo elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

	echo elgg_view('page/layouts/elements/header', $vars);
	?>
	<div class="elgg-main elgg-body">
		<?php
		if (isset($vars['content'])) {
			echo $vars['content'];
		}
		?>
	</div>
	<?php
	echo elgg_view('page/layouts/elements/footer', $vars);
	?>
</div>