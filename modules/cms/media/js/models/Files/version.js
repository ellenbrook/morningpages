define([
	'knockout',
], function(ko){
	
	var version = function(data)
	{
		var self = this;
		
		self.id = data.id;
		self.file_id = data.file_id;
		self.title = data.title;
		self.filename = data.filename;
		self.width = data.width;
		self.height = data.height;
		self.time = data.time;
		
	};
	
	return version;
	
});
