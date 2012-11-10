$(document).ready(function(){
	
	$('#launchpad').dialog({
		width: 600,
		open: function(){
			$('#getstarted').fadeOut();
		},
		close: function(){
			$('#getstarted').fadeIn();
		}
	});
	
	$('#launchpad_sample_links a').click(function(){

		$('#url').val($(this).attr('href'));
		
		$('#submit').click();
		
		$('#launchpad').dialog('close');
		
		return false;
	});
	
	$('#getstarted').click(function(){
		
		$('#launchpad').dialog('open');
		
		return false;
	});
	
	$('#getstarted a').button({
			icons: {
			primary: "ui-icon-info"
		}
	});
	
	$('#method, #config_data_type').buttonset();

	// Make sure radio is refreshed to prevent out of sync with toggle
	$('#config_data_type_paired').click();

	$('[for=config_data_type_paired], [for=config_data_type_body]').click(function(){

		$('#config_data_paired, #config_data_body').toggle();

	});

	$('#config .tabs').tabs();
	
	$('input:submit, #setting_html, #cookie_faq').button();
	
	$('#add_data, #add_header').button({
		icons: {
    		primary: "ui-icon-plusthick"
		}
	});
	
	$('#settings').click(function(){
		
		$('#config, #settings span').toggle();
		
		$('#launchpad').dialog('close');
		
		return false;
	});

	$('#add_data').click(add_data_row);

	$('#add_header').click(add_header_row);
	
	for (i = 0; i < 4; i++)
	{
		add_data_row();
		add_header_row();
	}

	$('.delete_row').live('click', function(){

		$(this).parent().parent().remove();
		
		return false;
	});
	
	$response = $('#response .tabs').tabs();

	$('form').submit(function(){
		
		$('#submit').hide();
		$('#loader').show();

		$('#message').empty().hide();

		$.ajax({
			'url'		: restify.controller,
			'type'		: 'POST',
			'data'		: $(this).serialize(),
			'success'	: function(data){

				$('#response_content').empty();

				$('#response_content').prepend('<pre class="prettyprint"><code>' + filter_content(data.content) + '</code></pre>');
				
				$('#response_headers').empty().prepend('<pre>' + data.headers + '</pre>');
				
				$('#response_headers_out').empty().prepend('<pre>' + data.headers_out + '</pre>');
				
				var response_cookies = $('#response_cookies table tbody');
				
				response_cookies.empty();
				
				if (data.cookies.length > 0)
				{
					response_cookies.html(array_to_rows(data.cookies));
				}
				else
				{
					response_cookies.html('<tr><td colspan="7" class="empty">No cookies for this response.</td></tr>');
				}
				
				prettyPrint();
				
				$('#response').show();
				
				resize_url();
			},
			'complete'	: function(){
				$('#launchpad').dialog('close');
				$('#loader').hide();
				$('#submit').show();	
			},
			'error'		: function(jq, status){
				
				var message = 'There is a problem. It relates to "' + status + '".';
				
				if (status == 'error')
				{
					message = 'There was an API error: "' + jQuery.parseJSON(jq.responseText).error + '"';
				}
				
				$('#message').show().prepend(template_error(message));
			}
		});
			
		return false;
	});

	$controls = $('#request').width() - $('#url').width() + 31;

	resize_url();
	
	$(window).resize(resize_url);
});

function add_data_row()
{
	$('#config_data ul').append(restify.template.data);
	
	$('.delete_row').button({
		icons: {
        	primary: "ui-icon-circle-close"
		}
    });
	
	return false;
}

function add_header_row()
{
	$('#config_headers ul').append(restify.template.header);
	
	$('.delete_row').button({
		icons: {
        	primary: "ui-icon-circle-close"
		}
    });
	
	return false;
}

function array_to_rows(array)
{
	var rows = [];
	
	for (var row = 0; row < array.length; row++)
	{
		var columns = [];
		
		for (var column = 0; column < array[row].length; column++)
		{
			columns.push('<td>' + array[row][column] + '</td>');
		}

		rows.push('<tr>' + columns.join('') + '</tr>');
	}
	
	return rows.join('');
}

function filter_content(content)
{
	try
	{
		// Attempt to parse and format JSON
		content = JSON.stringify(JSON.parse(content), null, 4);
	}
	catch (e) {}

	// HTML Encode
	return $('<div/>').text(content).html();
}

function template_error(message)
{
	return '<div class="ui-widget"><div class="ui-state-error ui-corner-all"><p><span class="ui-icon ui-icon-alert"></span><strong>Alert: </strong>' + message + '</p></div></div>';
}

function resize_url()
{
	$('#url').width($(window).width() - $controls);
}
