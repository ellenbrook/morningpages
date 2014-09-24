url = "http://127.0.0.1:8888/";ajaxurl = "http://127.0.0.1:8888/ajax";cronurl = "http://127.0.0.1:8888/cron";$(document).ready(function(){
	
	// Notifications
	if(typeof(notes) != 'undefined')
	{
		setTimeout(function(){
			showNotifications();
		}, 500);
		
		$('.note-alert').on('closed',function(e){
			move_other_notifications($(this));
		});
	}
	
	$('#pastposts').on('change', function(){
		if($(this).val() != 0)
		{
			window.location = url+'write/'+$(this).val();
		}
	});
	
	//$('.collapse').collapse();

	
});

$.fn.isLoading = function(){
	return $(this).html('<img class="loader" src="' + url + '/media/img/spin.gif" alt="Loader" />');
};

// Notifications
function showNotifications(runtime_message)
{
	var animationtime = 200;
	if(runtime_message != undefined)
	{
		notes = runtime_message;
	}
	if(notes && notes != 'false')
	{
		var currentloopnumnote = 0;
		for(var i=0;i<notes.length;i++)
		{
			setTimeout(function(){
				var posbottom = 10;
				var opennotes = $('.note-alert');
				if(opennotes.length > 0)
				{
					var frombottom = parseInt($(opennotes[opennotes.length-1]).css('bottom').split('px').join(''));
					var elemheight = $(opennotes[opennotes.length-1]).outerHeight();
					posbottom = (frombottom + elemheight + 10);
				}
				var heading = '';
				switch(notes[currentloopnumnote].type)
				{
					case 'error':
						heading = 'Error!';
						break;
					case 'info':
					default:
						heading = 'Info:';
						break;
					case 'success':
						heading = 'OK!';
						break;
				}
				var basetime = 4000;
				var worddelay = 300;
				var item = $('<div data-delay="' + (basetime+(notes[currentloopnumnote].note.split(' ').length*worddelay)) + '" class="note-alert alert alert-' + notes[currentloopnumnote].type + '"><a href="#" class="close" data-dismiss="alert">x</a><h4 class="alert-heading">' + heading + '</h4>' + notes[currentloopnumnote].note + '</div>');
				var thetimer = setTimeout(function(){
					item.slideUp('fast', function(){
						item.alert('close');
					});
				}, item.data('delay')); // Average human reads 5 words a sec?
				item.on('close', function(){
					clearInterval(thetimer);
				});
				item.mouseover(function(){
					clearInterval(thetimer);
				});
				item.mouseout(function(){
					thetimer = setTimeout(function(){
						item.alert('close');
					}, item.data('delay'));
				});
				$('body').append($(item));
				$(item).css({
					width:'250px',
					position:'fixed',
					bottom:posbottom + 'px',
					right:'10px',
					display:'none'
				}).slideDown(animationtime);
				
				currentloopnumnote++;
			}, 300 * i);
		}
	}
}

function tell_user(type, msg)
{
	showNotifications([{'type':type,'note':msg}]);
}

// Alias
function show_reply(reply)
{
	if(typeof reply != 'object')
	{
		throw 'show_reply recieved something that was not json';
	}
	tell_user(reply.type, reply.message);
}

$.extend({
	info:{
		success:function(msg){
			showNotifications([{'type':'success','note':msg}]);
		},
		info:function(msg)
		{
			showNotifications([{'type':'info','note':msg}]);
		},
		error:function(msg)
		{
			showNotifications([{'type':'error','note':msg}]);
		}
	}
});

function move_other_notifications(elem)
{
	var index = $('.note-alert').index($(elem));
	var elements = $('.note-alert');
	var me = $(elem);
	var onlyonce = true;
	for(var i = index;i<elements.length;i++)
	{
		var currentelement = elements[i];
		var prevelement = elements[i-1];
		var newbottompos = parseInt($(currentelement).css('bottom').split('px').join('')) - $(me).outerHeight() - 10;
		$(currentelement).animate({
			bottom:newbottompos + 'px'
		}, 200);
	}
}

function scrollToElement(element)
{
	$('body, html').animate({
		scrollTop:$(element).offset().top
	});
}define(['jquery', 'jgrowl'],function($,grr){
	
	var site = function(){
		var self = this;
		
		self.say = function(data, type)
		{
			if(typeof data == "object")
			{
				var message = data.message;
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
