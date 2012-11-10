<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php echo View::factory('restify/header', array('path' => $path)); ?>

	<div id="wrapper">

		<?php echo Form::open($request, array('method' => 'post')) ?>
	
			<div id="header">
	
				<div id="request">
			
					<div id="method">
						<?php echo Form::radio('method', 'GET', TRUE, array('id' => 'method_get')), Form::label('method_get', 'GET'), PHP_EOL; ?>
						<?php echo Form::radio('method', 'POST', FALSE, array('id' => 'method_post')), Form::label('method_post', 'POST'), PHP_EOL; ?>
						<?php echo Form::radio('method', 'PUT', FALSE, array('id' => 'method_put')), Form::label('method_put', 'PUT'), PHP_EOL; ?>
						<?php echo Form::radio('method', 'DELETE', FALSE, array('id' => 'method_delete')), Form::label('method_delete', 'DELETE'); ?>			
					</div>
		
					<?php echo HTML::anchor('#', '<span></span>', array('id' => 'settings')); ?>
	
					<?php echo Form::input('url', NULL, array('id' => 'url', 'placeholder' => 'http://api.example.com/resource.json?key=value', 'class' => 'ui-widget-content ui-corner-all')) ?>
			
					<?php echo Form::submit('submit', 'Request', array('id' => 'submit')); ?>
					
					<div id="loader"></div>
					
					<div class="clear"></div>
				</div>
	
				<div class="clear"></div>
			</div>
	
			<div id="config">
	
				<div class="tabs">
					<ul>
						<li><?php echo HTML::anchor('#config_data', __('Data')); ?></li>
						<li><?php echo HTML::anchor('#config_headers', __('Headers')); ?></li>
						<li><?php echo HTML::anchor('#config_settings', __('Settings')); ?></li>
						<li><?php echo HTML::anchor('#config_about', __('About')); ?></li>
					</ul>
					<div id="config_data" class="post_rows">

						<div id="config_data_type">
							<?php echo Form::radio('config_data_type', 'paired', TRUE, array('id' => 'config_data_type_paired')), Form::label('config_data_type_paired', 'Key / Value'), PHP_EOL; ?>
							<?php echo Form::radio('config_data_type', 'body', FALSE, array('id' => 'config_data_type_body')), Form::label('config_data_type_body', 'Body'), PHP_EOL; ?>
						</div>

						<div id="config_data_paired">

							<?php echo HTML::anchor('#', __('Add Row'), array('id' => 'add_data')); ?>

							<ul></ul>

						</div>

						<div id="config_data_body" class="hide">
							<?php echo Form::textarea('config_data_body'), PHP_EOL; ?>
						</div>

					</div>
					<div id="config_headers" class="post_rows">
						
						<?php echo HTML::anchor('#', __('Add Row'), array('id' => 'add_header')); ?>
	
						<ul></ul>
						
					</div>
					<div id="config_settings">
						
						<dl>
						    <dt><?php echo Form::label('setting_useragent', __('User Agent')) ?></dt>
						    <dd><?php echo Form::input('setting_useragent', $useragent, array('class' => 'ui-widget-content ui-corner-all', 'id' => 'setting_useragent')) ?></dd>
						</dl>	
					
						<dl>
						    <dt><?php echo Form::label('setting_referer', __('Referer')) ?></dt>
						    <dd><?php echo Form::input('setting_referer', $referer, array('class' => 'ui-widget-content ui-corner-all', 'id' => 'setting_referer')) ?></dd>
						</dl>			

					</div>
					<div id="config_about">

						<p>Thank you for your interest in <?php echo HTML::anchor('http://restify.io'); ?>. This tool is here to make RESTful testing easier. It was created by <?php echo HTML::anchor('http://morgan.ly', 'Micheal Morgan'); ?>.</p>
						
						<p>Restify is an open source <?php echo HTML::anchor('http://kohanaframework.org/', 'Kohana'); ?> module and is available on GitHub: <?php echo HTML::anchor('http://github.com/morgan/kohana-restify'); ?>.</p>

					</div>
				</div>
	
			</div>	
		
		<?php echo Form::close(), PHP_EOL; ?>
	
		<div id="message"></div>
			
		<div id="launchpad" title="<?php echo __('Getting Started'); ?>">
		
			<ul>
				<li><?php echo HTML::anchor('http://vimeo.com/michealmorgan/restify-overview', '', array('id' => 'launchpad_vimeo')); ?></li>
				<li><?php echo HTML::anchor('https://github.com/morgan/kohana-restify', '', array('id' => 'launchpad_github')); ?></li>		
				<li><?php echo HTML::anchor('https://twitter.com/michealmorgan', '', array('id' => 'launchpad_twitter')); ?></li>		
				<li><div id="launchpad_sample"></div>
					<ul id="launchpad_sample_links">
					
						<?php foreach ($samples as $sample): ?>
					
							<li><?php echo HTML::anchor($sample, Text::limit_chars($sample, 63, '&#8230')); ?></li>
					
						<?php endforeach; ?>

					</ul>
				</li>
			</ul>

			<div id="launchpad_like">
			
				<iframe src="http://www.facebook.com/plugins/like.php?app_id=232596780114112&amp;href=restify.io&amp;send=false&amp;layout=button_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:21px;" allowTransparency="true"></iframe>
			
			</div>

		</div>
		
		<div id="response">
		
			<div class="tabs">
				<ul>
					<li><?php echo HTML::anchor('#response_content', __('Response')); ?></li>
					<li><?php echo HTML::anchor('#response_headers', __('Headers Received')); ?></li>
					<li><?php echo HTML::anchor('#response_headers_out', __('Headers Sent')); ?></li>
					<li><?php echo HTML::anchor('#response_cookies', __('Cookies')); ?></li>
				</ul>
				<div id="response_content"></div>
				<div id="response_headers"></div>
				<div id="response_headers_out"></div>
				<div id="response_cookies">
				
					<table>
						<thead>
							<tr>
								<th>Domain</th>
								<th>Flag</th>
								<th>Path</th>
								<th>Secure</th>
								<th>Expiration</th>
								<th>Key</th>
								<th>Value</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				
					<?php echo HTML::anchor('http://www.cookiecentral.com/faq/#3.5', 'Cookie FAQ', array('id' => 'cookie_faq', 'target' => '_blank')); ?>
				
				</div>
			</div>
			
		</div>

		<div id="footer_spacer"></div>

		<div id="footer">
		
			<div id="copyright"><?php echo __('Copyright &#169; '), '2011-', date('Y'), ' ', HTML::anchor('http://morgan.ly', __('Micheal Morgan')); ?></div>
		
			<div id="getstarted"><?php echo HTML::anchor('#', __('Get Started')); ?></div>
			
		</div>

	</div>
	
<?php echo View::factory('restify/footer'); ?>
