define([
	'jquery',
	'knockout',
	'site',
	'viewModels/Files/editor',
	'typeahead',
	'jqueryui',
	'datepicker',
	'nestedsortable'
], function($, ko, site, fileeditor, typeahead, ui){
	
	ko.bindingHandlers.tabs = {
		init:function(elem) {
			$(elem).find('a').click(function(e){
				e.preventDefault();
				$(this).tab('show');
			});
		}
	};
	
	ko.bindingHandlers.togglemodal = {
		init:function(elem, valueAccessor){
			$(elem).on('click',function(e){
				e.preventDefault();
				$('#'+valueAccessor()).modal('show');
				return false;
			});
		}
	};
	
	ko.bindingHandlers.datepicker = {
		init:function(elem) {
			$(elem).datepicker({
				format: 'dd-mm-yyyy',
				parse: function (string) {
					var matches;
					if ((matches = string.match(/^(\d{2,2})\-(\d{2,2})\-(\d{4,4})$/))) {
						return new Date(matches[3], matches[2] - 1, matches[1]);
					} else {
						return null;
					}
				},
				dates : {
					days: ["Søndag", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag", "Søndag"],
					daysShort: ['Søn','Man','Tir','Ons','Tor','Fre','Lør'],
					daysMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø", "Sø"],
					months: ['Januar','Februar','Marts','April', 'Maj','Juni','Juli','August','September','Oktober','November','December'],
					monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
				}
			});
		}
	};
	
	ko.bindingHandlers.tooltip = {
		init:function(elem, valueAccessor){
			var data = valueAccessor();
			$(elem).tooltip({
				title:data.title
			});
		}
	};
	
	ko.bindingHandlers.visibleSlide = {
		init:function(elem, valueAccessor){
			if(!valueAccessor())
			{
				$(elem).hide();
			}
		},
		update:function(elem, valueAccessor){
			if(valueAccessor())
			{
				$(elem).slideDown('fast');
			}
			else
			{
				$(elem).slideUp('fast');
			}
		}
	};
	
	ko.bindingHandlers.loader = {
		update:function(elem, valueAccessor){
			if(valueAccessor())
			{
				$(elem).html('<img src="'+site.url+'media/img/ajax-loader-widget.gif" alt="Loader.." />').show();
			}
			else
			{
				$(elem).hide();
			}
		}
	};
	
	ko.bindingHandlers.tinymce = {
		init:function(elem) {
			site.loadtinymce($(elem));
		}
	};
	
	ko.bindingHandlers.initWidget = {
		init:function(elem, valueAccessor){
			valueAccessor()(elem);
		}
	};
	
	ko.bindingHandlers.filebrowserbtn = {
		init:function(elem, valueAccessor) {
			var callback = valueAccessor();
			var limit = 0;
			if(typeof valueAccessor() == 'object')
			{
				var data = valueAccessor();
				callback = data.callback;
				limit = data.limit;
			}
			$(elem).off('click').on('click',function(){
				site.filebrowser().show(limit);
				return false;
			});
			$('#filebrowsermodal').off('files-selected').on('files-selected', function(ev, files){
				callback(files);
			});
		}
	};
	
	ko.bindingHandlers.editfile = {
		init:function(elem, valueAccessor) {
			$(elem).off('click').on('click', function(ev){
				var val = valueAccessor();
				$('#filebrowsermodal').modal('hide');
				ko.cleanNode($('#fileeditormodal')[0]);
				ko.applyBindings(new fileeditor(val), $('#fileeditormodal')[0]);
				$('#fileeditormodal').modal('show');
				return false;
			});
		}
	};
	
	var substringMatcher = function(items) {
		return function findMatches(q, cb) {
			var matches, substringRegex;
			matches = [];
			substrRegex = new RegExp(q, 'i');
			$.each(items, function(i, item) {
				if (substrRegex.test(item.title)) {
					matches.push({ title: item.title, id:item.id });
				}
			});
			 
			cb(matches);
		};
	};
	
	/**
	 * typeahead:{
	 * 	data:{id:id,title:title},
	 * 	callback:functionName,
	 * 	enterAction:functionName
	 * }
	 */
	ko.bindingHandlers.typeahead = {
		init:function(elem, valueAccessor, allBindingsAccessor) {
			var args = valueAccessor();
			var allBindingsAccessor = allBindingsAccessor();
			var data = args.data;
			
			var callback = args.callback;
			$(elem).typeahead({
				highlight: true
			}, {	
				displayKey:'title',
				source:substringMatcher(data)
			}).on('typeahead:selected', function(ev, suggestion, datasetName){
				$(elem).typeahead('val','');
				callback(suggestion);
			});
			
			if(args.enterAction && (typeof args.enterAction == 'function'))
			{
				$(elem).on('keydown', function(e){
					if(e.keyCode == 13)
					{
						if($(elem).val() != '')
						{
							args.enterAction($(elem).val());
							$(elem).typeahead('val','');
						}
						e.preventDefault();
						return false;
					}
				});
			}
		}/*,
		updater:function(elem, valueAccessor){
			allBindingsAccessor.value(elem);
			return elem;
		}*/
	};
	
	ko.bindingHandlers.sortablegrid = {
		init:function(elem, valueAccessor){
			$(elem).sortable({
				//helper : 'clone',
				start : function(e, ui)
				{
					//ui.placeholder.height(ui.item.height());
					//ui.placeholder.width(ui.item.width());
					$(ui.item).find('.tinymce').each(function()
					{
						tinyMCE.execCommand('mceRemoveControl', false, $(this).attr('id'));
					});
				},
				stop : function(e, ui)
				{
					$(ui.item).find('.tinymce').each(function()
					{
						tinyMCE.execCommand('mceAddControl', true, $(this).attr('id'));
					});
				},
				//placeholder : 'widget-sorting-placeholder',
				//containment:'parent',
				opacity : '0.7',
				handle : '.widget-mover:first',
				items : '> .block',
				update :function(e, ui)
				{
					valueAccessor()(elem);
					return;
					var blocks = {};
					var i = 0;
					$('.block').each(function()
					{
						blocks[$(this).data('blockid')] = i;
						i++;
					});
					//$.post(site.ajaxurl + '/blocks/setorder', blocks);
				}
				/*handle:'.widget-mover:first',
				axis:'y',
				containment:'parent',
				placeholder:'widget-sorting-placeholder',
				//helper:'clone',
				tolerance:'pointer',
				start: function(e, ui){
			        ui.placeholder.height(ui.item.height());
			    }*/
			});
		}
	};
	
	ko.bindingHandlers.sortable = {
		init:function(elem, valueAccessor){
			$(elem).sortable({
				axis : 'y',
				helper : 'clone',
				start : function(e, ui)
				{
					ui.placeholder.height(ui.item.height());
					$(ui.item).find('.tinymce').each(function()
					{
						tinyMCE.execCommand('mceRemoveControl', false, $(this).attr('id'));
					});
				},
				stop : function(e, ui)
				{
					$(ui.item).find('.tinymce').each(function()
					{
						tinyMCE.execCommand('mceAddControl', true, $(this).attr('id'));
					});
				},
				placeholder : 'widget-sorting-placeholder',
				opacity : '0.7',
				handle : '.widget-mover:first',
				items : '> .block',
				update :function(e, ui)
				{
					valueAccessor()(elem);
					return;
					var blocks = {};
					var i = 0;
					$('.block').each(function()
					{
						blocks[$(this).data('blockid')] = i;
						i++;
					});
					//$.post(site.ajaxurl + '/blocks/setorder', blocks);
				}
				/*handle:'.widget-mover:first',
				axis:'y',
				containment:'parent',
				placeholder:'widget-sorting-placeholder',
				//helper:'clone',
				tolerance:'pointer',
				start: function(e, ui){
			        ui.placeholder.height(ui.item.height());
			    }*/
			});
		}
	};
	
	/*ko.bindingHandlers.tag = {
		init:function(elem) {
			$(elem).addClass('label label-primary');
			$(elem).append('<a href="#">X</a>');
		}
	};*/
	
	ko.bindingHandlers.nestedsortable = {
		init:function(elem, valueAccessor){
			$(elem).nestedSortable({
				items:'li',
				listType:'ul',
				handle : '.widget-mover:first',
				placeholder : 'widget-sorting-placeholder',
				opacity : '0.7',
				helper : 'clone',
				doNotClear:true,
				start : function(e, ui)
				{
					ui.placeholder.height(ui.item.height());
				},
				update:function(e,object)
				{
					
				}
			});
		}
	};
	
	ko.bindingHandlers.checkall = {
		init:function(elem){
			ko.utils.registerEventHandler(elem, 'click',function(){
				var boxes = $(elem).parents('.table').find('input[type="checkbox"]');
				if($(elem).is(':checked'))
				{
					boxes.each(function(){
						$(this).prop('checked', true);
					});
				}
				else
				{
					boxes.each(function(){
						$(this).prop('checked', false);
					});
				}
			});
		}
	};
	
	ko.bindingHandlers.href = {
		update: function (element, valueAccessor) {
			ko.bindingHandlers.attr.update(element, function () {
				return { href: valueAccessor()};
			});
		}
	};
	
	ko.bindingHandlers.src = {
		update: function (element, valueAccessor) {
			ko.bindingHandlers.attr.update(element, function () {
				return { src: valueAccessor()};
			});
		}
	};
	
});

