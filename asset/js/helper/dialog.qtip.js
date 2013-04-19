/*
	USING HELPER
	=============
	Alert('Peringatan bagi anda');
	
	Prompt('How would you describe qTip2?', 'Awesome!', function(response) {
		if(response)
			<do respon>
	});
	
	Confirm('Click Ok if you love qTip2', function(yes) {
		if(yes)
			<do yes function
	});
*/
	
//$(document).ready(function()
//{
	// QTIP DIALOG
	function dialogue(content, title) {
	
		$('<div />').qtip(
		{
			content: {
				text: content,
				title: title
			},
			solo: true,
			position: {
				my: 'center', at: 'center', 
				target: $(window) 
			},
			show: {
				ready: true,
				modal: {
					on: true,
					blur: false 
				}
			},
			hide: false,
			style: {
				classes: 'ui-tooltip-rounded ui-tooltip-shadow ui-tooltip-dialogue',// ui-tooltip-light ', 
				widget: true,
			},
			events: {
				render: function(event, api) {
					$('button', api.elements.content).click(api.hide);
				},
				hide: function(event, api) { api.destroy(); }
			}
		});
		
		$.fn.qtip.plugins.modal.zindex = 99999;
	}

	function dialog_alert(message)
	{
		var message = $('<p />', { text: message }),
			ok = $('<button />', { 
				text: 'Ok', 
				'class': 'full ui-state-hover'
			});
	
		dialogue( message.add(ok), 'Peringatan!' );
	}

	function dialog_prompt(question, initial, callback)
	{
		var message = $('<p />', { text: question }),
			input = $('<input />', { val: initial }),
			ok = $('<button />', { 
				text: 'Ok',
				'class': 'ui-state-hover',
				click: function() { callback( input.val() ); }
			}),
			cancel = $('<button />', {
				text: 'Batal',
				'class': 'ui-state-error',
				click: function() { callback(null); }
			});

		dialogue( message.add(input).add(ok).add(cancel), 'Perhatian!' );
	}
	
	function dialog_confirm(question, callback)
	{
		var message = $('<p />', { text: question }),
			ok = $('<button />', { 
				text: 'Ok',
				'class': 'ui-state-hover',
				click: function() { callback(true); }
			}),
			cancel = $('<button />', { 
				text: 'Batal',
				'class': 'ui-state-error',
				click: function() { callback(false); }
			});

		dialogue( message.add(ok).add(cancel), 'Konfirmasi?' );
	}
	
	// NATIVE ALERT OVERIDE by achri
	(function() {
		nalert = window.alert;
		Type = {
			native: 'native',
			custom: 'custom'
		};
	})();
		
	(function(proxy) {
		proxy.alert = function () {
			var message = (!arguments[0]) ? 'null': arguments[0];
			var type = (!arguments[1]) ? '': arguments[1];

			if(type && type == 'native') {
				nalert(message);
			}
			else {
				dialog_alert(message);
			}     
		};
	})(this);

//});