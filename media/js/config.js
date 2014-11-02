require.config({
	'baseUrl':'/media/js',
	'paths':{
		'knockout':'vendor/knockout',
		//'knockout':'vendor/knockout.dev',
		'jquery':'//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min',
		'autogrow':'vendor/jquery.autosize',
		'validate':'vendor/jquery.validate.min',
		'jgrowl':'vendor/jgrowl.min',
		'facebook':'//connect.facebook.net/en_US/all' // Minified
		//'facebook':'//connect.facebook.net/en_US/sdk/debug' // Debugging
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
		},
		'facebook':{
			exports:''
		}
	}
});