<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<title>restify.io - For RESTful requests.</title>
	
	<?php echo HTML::style($path . 'css/reset.css'), PHP_EOL; ?>
	<?php echo HTML::style($path . 'css/smoothness/jquery-ui-1.8.23.custom.css'), PHP_EOL; ?>
	<?php echo HTML::style($path . 'css/prettify.css'), PHP_EOL; ?>
	<?php echo HTML::style($path . 'css/styles.css'); ?>
	
	<?php echo HTML::script($path . 'js/jquery-1.8.1.min.js'), PHP_EOL; ?>
	<?php echo HTML::script($path . 'js/jquery-ui-1.8.23.custom.min.js'), PHP_EOL; ?>
	<?php echo HTML::script($path . 'js/prettify/prettify.js'), PHP_EOL; ?>
	<?php echo HTML::script($path . 'js/json2.min.js'), PHP_EOL; ?>
	<?php echo HTML::script($path . 'js/json_parse.min.js'), PHP_EOL; ?>
	<?php echo HTML::script($path . 'js/global.js'), PHP_EOL; ?>
	<script>
		var restify = {
			controller: '<?php echo URL::site('restify/request') ?>',
			template: {
				data: '<?php echo View::factory('restify/row', array('prefix' => 'data')); ?>',
				header: '<?php echo View::factory('restify/row', array('prefix' => 'header')); ?>'
			}
		};		
	</script>	
</head>

<body>
