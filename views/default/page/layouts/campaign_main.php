<?php
$class = 'elgg-layout campaigns-main-layout clearfix';
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
		<div class="elgg-module campaigns-profile">
			<div class="campaigns-main">
				<?php
				echo elgg_extract('filter', $vars, '');
				?>
				<div class="campaigns-main-content">
					<?php
					echo elgg_extract('content', $vars, '');
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	echo elgg_view('page/layouts/elements/footer', $vars);
	?>
</div>