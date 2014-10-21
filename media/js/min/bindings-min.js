define(["knockout","jquery","site","autogrow","validate"],function(i,$,n){i.bindingHandlers.autogrow={init:function(n,e,t){i.utils.domNodeDisposal.addDisposeCallback(n,function(){$(n).data("autosize").remove()}),$(n).autosize({append:"\n"}),$(n).focus(function(){$(n).trigger("autosize")})}},i.bindingHandlers.ImAburger={init:function(i){$(i).on("click",function(){$(this).find(".hamburger-line:nth-child(2)").toggleClass("rotate-left"),$(this).find(".hamburger-line:nth-child(3)").toggleClass("rotate-right"),$(this).find(".hamburger-line:nth-child(1)").toggleClass("hide-me"),$(".mobile-navigation").toggleClass("mobile-navigation-active")})}},i.bindingHandlers.fadeVisible={init:function(n,e){var t=e();$(n).toggle(i.utils.unwrapObservable(t))},update:function(n,e){var t=e();i.utils.unwrapObservable(t)?$(n).slideDown():$(n).slideUp()}},i.bindingHandlers.showModal={init:function(i,n){$(i).on("click",function(){return $("#"+n()).show("fast"),$(".modal-overlay").css({display:"block",background:"rgba(0,0,0,.25)"}),!1})}},i.bindingHandlers.hideModal={init:function(i,n){$(i).on("click",function(){return $("#"+n()+", .shortcuts-modal, .modal-overlay").hide("fast"),$(".modal-overlay").css({display:"none",background:"rgba(0,0,0,0)"}),!1})}},i.bindingHandlers.validateForm={init:function(i,e){var t=e(),o=typeof e();if($(i).validate({invalidHandler:function(){return"object"==o&&("string"==typeof t.failnote&&n.say({type:"error",note:t.failnote}),"function"==typeof t.fail)?t.fail():!1}}),"object"==o&&t.rules&&"object"==typeof t.rules)for(elem in t.rules)$(elem).rules("add",t.rules[elem]);$(i).on("submit",function(){return $(i).valid()?"function"==o?t($(i).serialize()):"object"==o&&("string"==typeof t.successnote&&n.say({type:"success",note:t.successnote}),"function"==typeof t.success)?t.success($(i).serialize()):(console.log(""),!0):void 0})}}});