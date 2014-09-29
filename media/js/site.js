define([
	'knockout',
	'jquery',
	'jgrowl'
],function(ko,$,grr){
	
	var site = function(){
		var self = this;
		
		self.theme = ko.observable();
		self.theme.subscribe(function(newclass){
			$('body').removeClass().addClass(newclass);
		});
		
		self.say = function(data, type)
		{
			if(typeof data == "object")
			{
				var message = data.note || data.message;
				var noteclass = data.type ? 'growl-'+data.type : '';
				$.jGrowl(message, {
					position:'bottom-right',
					theme:noteclass,
					header:data.header,
					life:this.calculateGrowlDuration(message),
				});
			}
			else
			{
				if(!type || type.length == 0)
				{
					type = 'info';
				}
				$.jGrowl(data, {
					position:'bottom-right',
					theme:'growl-'+type,
					life:this.calculateGrowlDuration(data),
				});
			}
		};
		
		self.calculateGrowlDuration = function(message)
		{
			message = String(message);
			var duration = (message.split(' ').length) * 300;
			return 4000+duration;
		};
		
	};
	
	return new site();
	
});
