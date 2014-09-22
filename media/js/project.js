requirejs.config({
	'paths':{
		'jquery':'vendor/jquery.2.1.1',
		'knockout':'vendor/knockout',
		'autosize':'vendor/autosize',
	},
	'shim':{
		'autosize':{
			'deps':['jquery']
		}
	}
});

require([
	'knockout',
	'jquery',
], function(ko, $){
	
	var project = function(){
		
	};
	
	new project();
	
});
