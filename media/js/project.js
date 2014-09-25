require.config({
	'baseUrl':'/media/js',
	'paths':{
		'knockout':'vendor/knockout',
		'jquery':'//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min',
		'autogrow':'vendor/jquery.autosize',
		'validate':'vendor/jquery.validate.min',
		'jgrowl':'vendor/jgrowl.min'
	},
	'shim':{
		'autogrow':{
			'deps':['jquery']
		},
		'validate':{
			'deps':['jquery']
		},
		'jgrowl':{
			'deps':['jquery']
		}
	}
});
define(['knockout','jquery','bindings'], function(ko, $){
	
	var project = function(){
		var self = this;
		
		self.hamburgerClick = function(){
			console.log('click');
			$('#user-options-triangle').show();
			$( "#user-options" ).slideToggle( "slow", function() {
				// Animation complete.
				if(!$('#user-options').is(':visible'))
				{
					$('#user-options-triangle').hide();
				}
			});
		};
		
		self.goToPreviousPage = function(obg, ev){
			var date = $(ev.target).val();
			if($(ev.target).val() != 0)
			{
				window.location.href = '/write/'+date;
			}
		};
		
	};
	
	ko.applyBindings(new project());
	
});
