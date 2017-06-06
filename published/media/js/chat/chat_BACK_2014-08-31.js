(function(){"use strict";function e(e){function o(o,i){var s,h,k=o==window,v=i&&void 0!==i.message?i.message:void 0;if(i=e.extend({},e.blockUI.defaults,i||{}),!i.ignoreIfBlocked||!e(o).data("blockUI.isBlocked")){if(i.overlayCSS=e.extend({},e.blockUI.defaults.overlayCSS,i.overlayCSS||{}),s=e.extend({},e.blockUI.defaults.css,i.css||{}),i.onOverlayClick&&(i.overlayCSS.cursor="pointer"),h=e.extend({},e.blockUI.defaults.themedCSS,i.themedCSS||{}),v=void 0===v?i.message:v,k&&b&&t(window,{fadeOut:0}),v&&"string"!=typeof v&&(v.parentNode||v.jquery)){var y=v.jquery?v[0]:v,m={};e(o).data("blockUI.history",m),m.el=y,m.parent=y.parentNode,m.display=y.style.display,m.position=y.style.position,m.parent&&m.parent.removeChild(y)}e(o).data("blockUI.onUnblock",i.onUnblock);var g,I,w,U,x=i.baseZ;g=r||i.forceIframe?e('<iframe class="blockUI" style="z-index:'+x++ +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+i.iframeSrc+'"></iframe>'):e('<div class="blockUI" style="display:none"></div>'),I=i.theme?e('<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:'+x++ +';display:none"></div>'):e('<div class="blockUI blockOverlay" style="z-index:'+x++ +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>'),i.theme&&k?(U='<div class="blockUI '+i.blockMsgClass+' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:'+(x+10)+';display:none;position:fixed">',i.title&&(U+='<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(i.title||"&nbsp;")+"</div>"),U+='<div class="ui-widget-content ui-dialog-content"></div>',U+="</div>"):i.theme?(U='<div class="blockUI '+i.blockMsgClass+' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:'+(x+10)+';display:none;position:absolute">',i.title&&(U+='<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(i.title||"&nbsp;")+"</div>"),U+='<div class="ui-widget-content ui-dialog-content"></div>',U+="</div>"):U=k?'<div class="blockUI '+i.blockMsgClass+' blockPage" style="z-index:'+(x+10)+';display:none;position:fixed"></div>':'<div class="blockUI '+i.blockMsgClass+' blockElement" style="z-index:'+(x+10)+';display:none;position:absolute"></div>',w=e(U),v&&(i.theme?(w.css(h),w.addClass("ui-widget-content")):w.css(s)),i.theme||I.css(i.overlayCSS),I.css("position",k?"fixed":"absolute"),(r||i.forceIframe)&&g.css("opacity",0);var C=[g,I,w],S=k?e("body"):e(o);e.each(C,function(){this.appendTo(S)}),i.theme&&i.draggable&&e.fn.draggable&&w.draggable({handle:".ui-dialog-titlebar",cancel:"li"});var O=f&&(!e.support.boxModel||e("object,embed",k?null:o).length>0);if(u||O){if(k&&i.allowBodyStretch&&e.support.boxModel&&e("html,body").css("height","100%"),(u||!e.support.boxModel)&&!k)var E=d(o,"borderTopWidth"),T=d(o,"borderLeftWidth"),M=E?"(0 - "+E+")":0,B=T?"(0 - "+T+")":0;e.each(C,function(e,o){var t=o[0].style;if(t.position="absolute",2>e)k?t.setExpression("height","Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.support.boxModel?0:"+i.quirksmodeOffsetHack+') + "px"'):t.setExpression("height",'this.parentNode.offsetHeight + "px"'),k?t.setExpression("width",'jQuery.support.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"'):t.setExpression("width",'this.parentNode.offsetWidth + "px"'),B&&t.setExpression("left",B),M&&t.setExpression("top",M);else if(i.centerY)k&&t.setExpression("top",'(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"'),t.marginTop=0;else if(!i.centerY&&k){var n=i.css&&i.css.top?parseInt(i.css.top,10):0,s="((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "+n+') + "px"';t.setExpression("top",s)}})}if(v&&(i.theme?w.find(".ui-widget-content").append(v):w.append(v),(v.jquery||v.nodeType)&&e(v).show()),(r||i.forceIframe)&&i.showOverlay&&g.show(),i.fadeIn){var j=i.onBlock?i.onBlock:c,H=i.showOverlay&&!v?j:c,z=v?j:c;i.showOverlay&&I._fadeIn(i.fadeIn,H),v&&w._fadeIn(i.fadeIn,z)}else i.showOverlay&&I.show(),v&&w.show(),i.onBlock&&i.onBlock();if(n(1,o,i),k?(b=w[0],p=e(i.focusableElements,b),i.focusInput&&setTimeout(l,20)):a(w[0],i.centerX,i.centerY),i.timeout){var W=setTimeout(function(){k?e.unblockUI(i):e(o).unblock(i)},i.timeout);e(o).data("blockUI.timeout",W)}}}function t(o,t){var s,l=o==window,a=e(o),d=a.data("blockUI.history"),c=a.data("blockUI.timeout");c&&(clearTimeout(c),a.removeData("blockUI.timeout")),t=e.extend({},e.blockUI.defaults,t||{}),n(0,o,t),null===t.onUnblock&&(t.onUnblock=a.data("blockUI.onUnblock"),a.removeData("blockUI.onUnblock"));var r;r=l?e("body").children().filter(".blockUI").add("body > .blockUI"):a.find(">.blockUI"),t.cursorReset&&(r.length>1&&(r[1].style.cursor=t.cursorReset),r.length>2&&(r[2].style.cursor=t.cursorReset)),l&&(b=p=null),t.fadeOut?(s=r.length,r.stop().fadeOut(t.fadeOut,function(){0===--s&&i(r,d,t,o)})):i(r,d,t,o)}function i(o,t,i,n){var s=e(n);if(!s.data("blockUI.isBlocked")){o.each(function(){this.parentNode&&this.parentNode.removeChild(this)}),t&&t.el&&(t.el.style.display=t.display,t.el.style.position=t.position,t.parent&&t.parent.appendChild(t.el),s.removeData("blockUI.history")),s.data("blockUI.static")&&s.css("position","static"),"function"==typeof i.onUnblock&&i.onUnblock(n,i);var l=e(document.body),a=l.width(),d=l[0].style.width;l.width(a-1).width(a),l[0].style.width=d}}function n(o,t,i){var n=t==window,l=e(t);if((o||(!n||b)&&(n||l.data("blockUI.isBlocked")))&&(l.data("blockUI.isBlocked",o),n&&i.bindEvents&&(!o||i.showOverlay))){var a="mousedown mouseup keydown keypress keyup touchstart touchend touchmove";o?e(document).bind(a,i,s):e(document).unbind(a,s)}}function s(o){if("keydown"===o.type&&o.keyCode&&9==o.keyCode&&b&&o.data.constrainTabKey){var t=p,i=!o.shiftKey&&o.target===t[t.length-1],n=o.shiftKey&&o.target===t[0];if(i||n)return setTimeout(function(){l(n)},10),!1}var s=o.data,a=e(o.target);return a.hasClass("blockOverlay")&&s.onOverlayClick&&s.onOverlayClick(o),a.parents("div."+s.blockMsgClass).length>0?!0:0===a.parents().children().filter("div.blockUI").length}function l(e){if(p){var o=p[e===!0?p.length-1:0];o&&o.focus()}}function a(e,o,t){var i=e.parentNode,n=e.style,s=(i.offsetWidth-e.offsetWidth)/2-d(i,"borderLeftWidth"),l=(i.offsetHeight-e.offsetHeight)/2-d(i,"borderTopWidth");o&&(n.left=s>0?s+"px":"0"),t&&(n.top=l>0?l+"px":"0")}function d(o,t){return parseInt(e.css(o,t),10)||0}e.fn._fadeIn=e.fn.fadeIn;var c=e.noop||function(){},r=/MSIE/.test(navigator.userAgent),u=/MSIE 6.0/.test(navigator.userAgent)&&!/MSIE 8.0/.test(navigator.userAgent);document.documentMode||0;var f=e.isFunction(document.createElement("div").style.setExpression);e.blockUI=function(e){o(window,e)},e.unblockUI=function(e){t(window,e)},e.growlUI=function(o,t,i,n){var s=e('<div class="growlUI"></div>');o&&s.append("<h1>"+o+"</h1>"),t&&s.append("<h2>"+t+"</h2>"),void 0===i&&(i=3e3);var l=function(o){o=o||{},e.blockUI({message:s,fadeIn:o.fadeIn!==void 0?o.fadeIn:700,fadeOut:o.fadeOut!==void 0?o.fadeOut:1e3,timeout:o.timeout!==void 0?o.timeout:i,centerY:!1,showOverlay:!1,onUnblock:n,css:e.blockUI.defaults.growlCSS})};l(),s.css("opacity"),s.mouseover(function(){l({fadeIn:0,timeout:3e4});var o=e(".blockMsg");o.stop(),o.fadeTo(300,1)}).mouseout(function(){e(".blockMsg").fadeOut(1e3)})},e.fn.block=function(t){if(this[0]===window)return e.blockUI(t),this;var i=e.extend({},e.blockUI.defaults,t||{});return this.each(function(){var o=e(this);i.ignoreIfBlocked&&o.data("blockUI.isBlocked")||o.unblock({fadeOut:0})}),this.each(function(){"static"==e.css(this,"position")&&(this.style.position="relative",e(this).data("blockUI.static",!0)),this.style.zoom=1,o(this,t)})},e.fn.unblock=function(o){return this[0]===window?(e.unblockUI(o),this):this.each(function(){t(this,o)})},e.blockUI.version=2.66,e.blockUI.defaults={message:"<h1>Please wait...</h1>",title:null,draggable:!0,theme:!1,css:{padding:0,margin:0,width:"30%",top:"40%",left:"35%",textAlign:"center",color:"#000",border:"3px solid #aaa",backgroundColor:"#fff",cursor:"wait"},themedCSS:{width:"30%",top:"40%",left:"35%"},overlayCSS:{backgroundColor:"#000",opacity:.6,cursor:"wait"},cursorReset:"default",growlCSS:{width:"350px",top:"10px",left:"",right:"10px",border:"none",padding:"5px",opacity:.6,cursor:"default",color:"#fff",backgroundColor:"#000","-webkit-border-radius":"10px","-moz-border-radius":"10px","border-radius":"10px"},iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank",forceIframe:!1,baseZ:1e3,centerX:!0,centerY:!0,allowBodyStretch:!0,bindEvents:!0,constrainTabKey:!0,fadeIn:200,fadeOut:400,timeout:0,showOverlay:!0,focusInput:!0,focusableElements:":input:enabled:visible",onBlock:null,onUnblock:null,onOverlayClick:null,quirksmodeOffsetHack:4,blockMsgClass:"blockMsg",ignoreIfBlocked:!1};var b=null,p=[]}"function"==typeof define&&define.amd&&define.amd.jQuery?define(["jquery"],e):e(jQuery)})();

function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  return s;
}

function moveCaretToEnd(el) {
    if (typeof el.selectionStart == "number") {
        el.selectionStart = el.selectionEnd = el.value.length;
    } else if (typeof el.createTextRange != "undefined") {
        el.focus();
        var range = el.createTextRange();
        range.collapse(false);
        range.select();
    }
}

function initChat_UserGroup(group_title, icon) {
	 
	var _group = group_title.replace(/[^a-z0-9]/ig, '');

	var chatbox_type = $('<i class="chatbox chatbox-hover fa fa-' + icon + ' fa-2x" style="top: -2px;" />');
	
	$('div#chatbox-container').append(chatbox_type);
	
	chatbox_type.qtip({
		prerender: true,
		content: {
				text: '\
						<div class="chat-header">\
							<div class="pull-left connection-listeners">' + group_title.toUpperCase() + '&nbsp;(<span id="connection-count-' + _group + '">0</span>)</div>\
							<div class="pull-right" id="connection-status-' + _group + '">CONNECTING <i class="fa fa-globe"></i></div>\
						</div>\
						<div class="clearfix"></div>\
						<div id="type-box-' + _group + '" class="chatbox-box"></div>\
						<input type="text" id="type-input-' + _group + '" class="chatbox-input" style="width: 249px;" maxlength="120" />\
					'
			},
		style: {
				classes: 'qtip-bootstrap',
				tip: {
						corner: true
					},
				width: '280px'
			},
		position: {
				my: 'bottom right',
				at: 'top center',
				viewport: $(window),
				adjust: {
						scroll: false,
						y: -8
					}
			},
		show: {
				event: 'click',
				solo: true
			},
		hide: {
				event: 'click'
			},
		events: {
				show: function () {
				
						if(window['chatbox_type_alert_' + _group]) {
						
							clearInterval(window['chatbox_type_alert_' + _group]);
							
							window['chatbox_type_alert_' + _group] = null;
							
							chatbox_type.addClass('chatbox-hover');
							
							Cookies.expire('unread_ctr_' + _group)
							
						}
						
						window['unread_ctr_' + _group] = 0;
						
						$('span.alert-counter', chatbox_type).remove();
						
						$('div#type-box-' + _group)
							.scrollTop($('div#type-box-' + _group)[0].scrollHeight)
							.perfectScrollbar('update');
						
						setTimeout(function () {
	
							$('input#type-input-' + _group).focus();
							
							$('div#type-box-' + _group)
								.scrollTop($('div#type-box-' + _group)[0].scrollHeight)
								.perfectScrollbar('update');
							
						}, 1);
					},
				render: function (elem, api) {

						$('div#type-box-' + _group).perfectScrollbar({
							suppressScrollX: true
						});
						
						if(NOTIFS.isConnected()) {
							
							if(window['connecting_anim_interval_' + _group])
								clearInterval(window['connecting_anim_interval_' + _group]);
							
							$('div#connection-status-' + _group).html('CONNECTED <i class="fa fa-globe" style="color: green;"></i>');
							
						}
						
						window[_group + '_listeners'] = $('div.connection-listeners', elem.target).qtip({
							prerender: true,
							content: {
									text: '<div class="nick-list"></div>'
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								},
							hide: {
									delay: 500,
									fixed: true
								},
							events: {
									render: function (elem2, api2) {

											$('div.nick-list', elem2.target).perfectScrollbar();
											
											window[_group + '_listeners_api'] = api2;
											
										},
									show: function (e, api) {
										
										if(!$('div.nick-name', e.target).length)
											e.preventDefault();
										
									}
								}
						});
						
						$('input#type-input-' + _group)
							.keyup(function (e) {
						
								var value = $.trim($(this).val());
								
								if(e.keyCode == 13 && value && NOTIFS.isConnected()) {
								
									NOTIFS.spublish(_group, { user: user_nick, type: user_type.substr(0, 3).toUpperCase(), message: value, sent: moment().format('X') }, function (data) {

										$('div.maxlength', elem.target).prepend('<span class="receivedby pull-left">Received by ' + data + ' listener' + (data == 1 ? '' : 's') + '</span>');
										
									});

									$(this).val('');
								}
								else if(e.keyCode == 27) {
									
									chatbox_type.qtip('hide');
									
									$(this).val('');
								}
							})
							.maxlength();
						
						$(elem.target).click(function (e) {
							
							if(e.originalEvent.explicitOriginalTarget.nodeType != 3)
								$('input#type-input-'+ _group).focus();
							
						});
						
						$('div#type-box-' + _group).on('click', 'div.prev a', function (e) {
		
							e.preventDefault();
							
							var that = this;
							var offset = $(this).data('offset') + 1;
							
							NOTIFS.list(_group, function (data) {

								var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
								var list = '';
								var temp_list = $('<div />');
								
								if(data.total > 1 && data.next)
									temp_list.html('<div class="prev"><a href="#" data-offset="' + offset + '">' + moment(data.next + '-' + moment().format('YYYY'), 'MM-DD-YYY').format('MMM DD') + '</a></div>');
								
								if(!$('div.date-' + m_d).length)
									temp_list.append('<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>');
								
								for(var i = 0; i < data.list.length; i++) {
									
									if($('span.username:last', temp_list).text() == ('[' + data.list[i].type + '] ' + data.list[i].user))
										$('div.message:last', temp_list).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>');
									else
										temp_list.append('<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">[' + data.list[i].type + '] ' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>');
								}
								
								$('div#type-box-' + _group).prepend(temp_list.html());
								
								$('div#type-box-' + _group + ' span.sent:not(.hasqtip)').each(function () {

									$(this)
										.addClass('hasqtip')
										.qtip({
											content: {
													text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
												},
											style: {
													classes: 'qtip-bootstrap',
													tip: {
															corner: true
														}
												},
											position: {
													my: 'right center',
													at: 'left center',
													viewport: $(window),
													adjust: {
															x: -10
														}
												}
										});	
								
								});
								
								$(that).remove();
								
								$('div#type-box-' + _group)
									.scrollTop(0)
									.perfectScrollbar('update');
								
							}, offset);
							
						});
						
					}
			}
	});
	
	var chatbox_type_api = chatbox_type.qtip('api');
	
	window['connecting_anim_interval_' + _group] = setInterval(function () {
			
			$('div#connection-status-' + _group + ' i').toggleClass('connecting');
			
		}, 500);
	
	$(window).scroll(function (e) {
		
		chatbox_type_api.reposition(e, false);
		
	});

	NOTIFS
		.onconnect(function () {

			if(window['connecting_anim_interval_' + _group])
				clearInterval(window['connecting_anim_interval_' + _group]);
			
			$('div#connection-status-' + _group).html('CONNECTED <i class="fa fa-globe" style="color: green;"></i>');
			
			$('input#type-input-' + _group).css('color', '#555');

			$('div#type-box-' + _group + ' > div:not(.ps-scrollbar-x-rail, .ps-scrollbar-y-rail)').remove();
			
			var _unread_ctr = Cookies.get('unread_ctr_' + _group);
					
			if(_unread_ctr) {
			
				window['unread_ctr_' + _group] = _unread_ctr;
			
				chatbox_type.append('<span class="label label-danger alert-counter">' + _unread_ctr + '</span>');
		
				if(!window['chatbox_type_alert-' + _group])
					window['chatbox_type_alert-' + _group] = setInterval(function () {
							
							chatbox_type.toggleClass('chatbox-hover');
							
						}, 500);
			}
				
			NOTIFS.list(_group, function (data) {

				var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
				var list = '';
				var temp_list = $('<div />');
				
				if(data.total > 1 && data.next)
					temp_list.html('<div class="prev"><a href="#" data-offset="0">' + moment(data.next + '-' + moment().format('YYYY'), 'MM-DD-YYY').format('MMM DD') + '</a></div>');
				
				if(!$('div.date-' + m_d).length)
					temp_list.append('<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>');
				
				for(var i = 0; i < data.list.length; i++) {
					
					if($('span.username:last', temp_list).text() == ('[' + data.list[i].type + '] ' + data.list[i].user))
						$('div.message:last', temp_list).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>');
					else
						temp_list.append('<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">[' + data.list[i].type + '] ' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>');
				}
				
				$('div#type-box-' + _group)
					.prepend(temp_list.html())
					.perfectScrollbar('update');
				
				$('div#type-box-' + _group + ' span.sent:not(.hasqtip)').each(function () {

					$(this)
						.addClass('hasqtip')
						.qtip({
							content: {
									text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								}
						});	
				
				});
			});
			
		})
		.ondisconnect(function () {
			
			$('div#connection-status-' + _group).html('DISCONNECTED <i class="fa fa-globe"></i>');
			
			$('input#type-input-' + _group).css('color', '#999');

		})
		.onreconnect_attempt(function () {
			
			$('div#connection-status-' + _group).html('RECONNECTING <i class="fa fa-globe"></i>');
			
			window['connecting_anim_interval_' + _group] = setInterval(function () {
			
					$('div#connection-status-' + _group + ' i').toggleClass('connecting');
					
				}, 500);
			
		})
		.subscribe(_group, function (conn) {

			// if(conn.count != undefined)
				// setTimeout(function () {
				
					// var listeners = new Array();
					
					// for(var i = 0; i < conn.listeners.length; i++)
						// if(listeners.indexOf(conn.listeners[i].nick))
							// listeners[listeners.length] = conn.listeners[i].nick;

					// $('span#connection-listeners')
						// .text(conn.count + ' Listener' + (conn.count == 1 ? '' : 's'))
						// .qtip({
							// content: {
									// text: '<div class="nick-list"><div class="nick-name">' + listeners.join('</div><div class="nick-name">') + '</div></div>'
								// },
							// style: {
									// classes: 'qtip-bootstrap',
									// tip: {
											// corner: true
										// }
								// },
							// position: {
									// my: 'right center',
									// at: 'left center',
									// viewport: $(window),
									// adjust: {
											// x: -10
										// }
								// },
							// hide: {
									// delay: 500,
									// fixed: true
								// },
							// events: {
									// render: function () {

											// $('div.nick-list')
												// .perfectScrollbar()
												// .scroll();
											
										// }
								// }
						// });
					
				// }, 100);
			
			// if(conn.count != undefined)
				// $('span#connection-count').text(conn.count);
				
			if(conn.count != undefined)
				setTimeout(function () {

					var nick_list = $('div.nick-list', window[_group + '_listeners_api'].tooltip);

					$('div.nick-name', nick_list).remove();

					for(var i = 0; i < conn.listeners.length; i++)
						nick_list.append('<div class="nick-name">[' + conn.listeners[i].type.substr(0, 3).toUpperCase() + '] ' + conn.listeners[i].nick + '</div>');
					
					$('span#connection-count-' + _group).text(conn.count);
					
					nick_list.perfectScrollbar('update');
					
				}, 1000);
			
			if(conn.message != undefined) {

				if(!chatbox_type_api.elements.tooltip.is(':visible') && conn.message.user != user_nick) {
				
					if('unread_ctr_' + _group in window)
						window['unread_ctr_' + _group]++;
					else
						window['unread_ctr_' + _group] = 1;
					
					Cookies.set('unread_ctr_' + _group, window['unread_ctr_' + _group]);

					if($('span.alert-counter-' + _group, chatbox_type).length)
						$('span.alert-counter-' + _group, chatbox_type).text(window['unread_ctr_' + _group]);
					else
						chatbox_type.append('<span class="label label-danger alert-counter">' + window['unread_ctr_' + _group] + '</span>');
				
					if(!window['chatbox_type_alert_' + _group])
						window['chatbox_type_alert_' + _group] = setInterval(function () {
								
								chatbox_type.toggleClass('chatbox-hover');
								
							}, 500);
				}
				
				var m_d = moment.unix(conn.message.sent).format('MMM-DD');

				if(!$('div.date-' + m_d).length)
					$('div#type-box-' + _group).append('<div class="date-heading">Today</div>');
				
				if($('div#type-box-' + _group + ' div.date-' + m_d + ' span.username:last').text() == ('[' + conn.message.type + '] ' + conn.message.user))
					$('div#type-box-' + _group + ' div.date-' + m_d + ':last').append('<div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div>');
				else
					$('div#type-box-' + _group).append('<div class="message' + (conn.message.user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">[' + conn.message.type + '] ' + conn.message.user + '</span></div><div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div></div>')
				
				$('div#type-box-' + _group + ' span.sent:not(.hasqtip)').each(function () {

					$(this)
						.addClass('hasqtip')
						.qtip({
							content: {
									text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								}
						});	
				
				});
				
				$('div#type-box-' + _group)
					.scrollTop($('div#type-box-' + _group)[0].scrollHeight)
					.perfectScrollbar('update');
				
			}

			// if(conn.connected != undefined && window[user_type + '_listeners_api']) {
			
				// var nick_list = $('div.nick-list', window[user_type + '_listeners_api'].tooltip);
			
				// if(!$('div.nick-name:contains("' + conn.connected.nick + '")', window[user_type + '_listeners_api'].tooltip).length)
					// nick_list.append('<div class="nick-name">' + conn.connected.nick + '</div>');
				
				// $('span#connection-count').text($('div.nick-name', window[user_type + '_listeners_api'].tooltip).length);
				
				
				// nick_list.perfectScrollbar('update');
			// }
			
			// if(conn.disconnected != undefined && window[user_type + '_listeners_api']) {
			
				// $('div.nick-name:contains("' + conn.disconnected.nick + '")', window[user_type + '_listeners_api'].tooltip).remove();
				
				// $('span#connection-count').text($('div.nick-name', window[user_type + '_listeners_api'].tooltip).length);
				
				// $('div.nick-list', window[user_type + '_listeners_api'].tooltip).perfectScrollbar('update');
			// }
			
		});
	
}

function initChat_UserType() {

	var chatbox_type = $('<i class="chatbox chatbox-hover fa fa-group fa-2x" style="top: -2px;" />');
	
	$('div#chatbox-container').append(chatbox_type);
	
	chatbox_type.qtip({
		prerender: true,
		content: {
				text: '\
						<div class="chat-header">\
							<div class="pull-left connection-listeners">' + user_type.toUpperCase() + '&nbsp;(<span id="connection-count">0</span>)</div>\
							<div class="pull-right" id="connection-status">CONNECTING <i class="fa fa-globe"></i></div>\
						</div>\
						<div class="clearfix"></div>\
						<div id="type-box" class="chatbox-box"></div>\
						<input type="text" id="type-input" class="chatbox-input" style="width: 249px;" maxlength="120" />\
					'
			},
		style: {
				classes: 'qtip-bootstrap',
				tip: {
						corner: true
					},
				width: '280px'
			},
		position: {
				my: 'bottom right',
				at: 'top center',
				viewport: $(window),
				adjust: {
						scroll: false,
						y: -8
					}
			},
		show: {
				event: 'click',
				solo: true
			},
		hide: {
				event: 'click'
			},
		events: {
				show: function () {
				
						if(window.chatbox_type_alert) {
						
							clearInterval(window.chatbox_type_alert);
							
							window.chatbox_type_alert = null;
							
							chatbox_type.addClass('chatbox-hover');
							
							Cookies.expire('unread_ctr');
							
						}
						
						window.unread_ctr = 0;
						
						$('span.alert-counter', chatbox_type).remove();
						
						$('div#type-box')
							.scrollTop($('div#type-box')[0].scrollHeight)
							.perfectScrollbar('update');
						
						setTimeout(function () {
	
							$('input#type-input').focus();
							
							$('div#type-box')
								.scrollTop($('div#type-box')[0].scrollHeight)
								.perfectScrollbar('update');
							
						}, 1);
					},
				render: function (elem, api) {

						$('div#type-box').perfectScrollbar({
							suppressScrollX: true
						});
						
						if(NOTIFS.isConnected()) {
							
							if(window.connecting_anim_interval)
								clearInterval(window.connecting_anim_interval);
							
							$('div#connection-status').html('CONNECTED <i class="fa fa-globe" style="color: green;"></i>');
							
						}
						
						window[user_type + '_listeners'] = $('div.connection-listeners', elem.target).qtip({
							prerender: true,
							content: {
									text: '<div class="nick-list"></div>'
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								},
							hide: {
									delay: 500,
									fixed: true
								},
							events: {
									render: function (elem2, api2) {

											$('div.nick-list', elem2.target).perfectScrollbar();
											
											window[user_type + '_listeners_api'] = api2;
											
										},
									show: function (e, api) {
										
										if(!$('div.nick-name', e.target).length)
											e.preventDefault();
										
									}
								}
						});
						
						$('input#type-input')
							.keyup(function (e) {
						
								var value = $.trim($(this).val());
								
								if(e.keyCode == 13 && value && NOTIFS.isConnected()) {
								
									NOTIFS.spublish(user_type, { user: user_nick, message: value, sent: moment().format('X') }, function (data) {

										$('div.maxlength', elem.target).prepend('<span class="receivedby pull-left">Received by ' + data + ' listener' + (data == 1 ? '' : 's') + '</span>');
										
									});

									$(this).val('');
								}
								else if(e.keyCode == 27) {
									
									chatbox_type.qtip('hide');
									
									$(this).val('');
								}
							})
							.maxlength();
						
						$(elem.target).click(function (e) {
							
							if(e.originalEvent.explicitOriginalTarget.nodeType != 3)
								$('input#type-input').focus();
							
						});
						
						$('div#type-box').on('click', 'div.prev a', function (e) {
		
							e.preventDefault();
							
							var that = this;
							var offset = $(this).data('offset') + 1;
							
							NOTIFS.list(user_type, function (data) {

								var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
								var list = '';
								var temp_list = $('<div />');
								
								if(data.total > 1 && data.next)
									temp_list.html('<div class="prev"><a href="#" data-offset="' + offset + '">' + moment(data.next + '-' + moment().format('YYYY'), 'MM-DD-YYY').format('MMM DD') + '</a></div>');
								
								if(!$('div.date-' + m_d).length)
									temp_list.append('<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>');
								
								for(var i = 0; i < data.list.length; i++) {
									
									if($('span.username:last', temp_list).text() == data.list[i].user)
										$('div.message:last', temp_list).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>');
									else
										temp_list.append('<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>');
								}
								
								$('div#type-box').prepend(temp_list.html());
								
								$('div#type-box span.sent:not(.hasqtip)').each(function () {

									$(this)
										.addClass('hasqtip')
										.qtip({
											content: {
													text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
												},
											style: {
													classes: 'qtip-bootstrap',
													tip: {
															corner: true
														}
												},
											position: {
													my: 'right center',
													at: 'left center',
													viewport: $(window),
													adjust: {
															x: -10
														}
												}
										});	
								
								});
								
								$(that).remove();
								
								$('div#type-box')
									.scrollTop(0)
									.perfectScrollbar('update');
								
							}, offset);
							
						});
						
					}
			}
	});
	
	var chatbox_type_api = chatbox_type.qtip('api');
	
	window.connecting_anim_interval = setInterval(function () {
			
			$('div#connection-status i').toggleClass('connecting');
			
		}, 500);
	
	$(window).scroll(function (e) {
		
		chatbox_type_api.reposition(e, false);
		
	});

	NOTIFS
		.onconnect(function () {

			if(window.connecting_anim_interval)
				clearInterval(window.connecting_anim_interval);
			
			$('div#connection-status').html('CONNECTED <i class="fa fa-globe" style="color: green;"></i>');
			
			$('input#type-input').css('color', '#555');

			$('div#type-box > div:not(.ps-scrollbar-x-rail, .ps-scrollbar-y-rail)').remove();
			
			var _unread_ctr = Cookies.get('unread_ctr');
					
			if(_unread_ctr) {
			
				window.unread_ctr = _unread_ctr;
			
				chatbox_type.append('<span class="label label-danger alert-counter">' + _unread_ctr + '</span>');
		
				if(!window.chatbox_type_alert)
					window.chatbox_type_alert = setInterval(function () {
							
							chatbox_type.toggleClass('chatbox-hover');
							
						}, 500);
			}
				
			NOTIFS.list(user_type, function (data) {

				var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
				var list = '';
				var temp_list = $('<div />');
				
				if(data.total > 1 && data.next)
					temp_list.html('<div class="prev"><a href="#" data-offset="0">' + moment(data.next + '-' + moment().format('YYYY'), 'MM-DD-YYY').format('MMM DD') + '</a></div>');
				
				if(!$('div.date-' + m_d).length)
					temp_list.append('<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>');
				
				for(var i = 0; i < data.list.length; i++) {
					
					if($('span.username:last', temp_list).text() == data.list[i].user)
						$('div.message:last', temp_list).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>');
					else
						temp_list.append('<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>');
				}
				
				$('div#type-box')
					.prepend(temp_list.html())
					.perfectScrollbar('update');

				$('div#type-box span.sent:not(.hasqtip)').each(function () {

					$(this)
						.addClass('hasqtip')
						.qtip({
							content: {
									text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								}
						});	
				
				});
			});
			
		})
		.ondisconnect(function () {
			
			$('div#connection-status').html('DISCONNECTED <i class="fa fa-globe"></i>');
			
			$('input#type-input').css('color', '#999');

		})
		.onreconnect_attempt(function () {
			
			$('div#connection-status').html('RECONNECTING <i class="fa fa-globe"></i>');
			
			window.connecting_anim_interval = setInterval(function () {
			
					$('div#connection-status i').toggleClass('connecting');
					
				}, 500);
			
		})
		.subscribe(user_type, function (conn) {

			// if(conn.count != undefined)
				// setTimeout(function () {
				
					// var listeners = new Array();
					
					// for(var i = 0; i < conn.listeners.length; i++)
						// if(listeners.indexOf(conn.listeners[i].nick))
							// listeners[listeners.length] = conn.listeners[i].nick;

					// $('span#connection-listeners')
						// .text(conn.count + ' Listener' + (conn.count == 1 ? '' : 's'))
						// .qtip({
							// content: {
									// text: '<div class="nick-list"><div class="nick-name">' + listeners.join('</div><div class="nick-name">') + '</div></div>'
								// },
							// style: {
									// classes: 'qtip-bootstrap',
									// tip: {
											// corner: true
										// }
								// },
							// position: {
									// my: 'right center',
									// at: 'left center',
									// viewport: $(window),
									// adjust: {
											// x: -10
										// }
								// },
							// hide: {
									// delay: 500,
									// fixed: true
								// },
							// events: {
									// render: function () {

											// $('div.nick-list')
												// .perfectScrollbar()
												// .scroll();
											
										// }
								// }
						// });
					
				// }, 100);
			
			// if(conn.count != undefined)
				// $('span#connection-count').text(conn.count);
				
			if(conn.count != undefined)
				setTimeout(function () {

					var nick_list = $('div.nick-list', window[user_type + '_listeners_api'].tooltip);

					$('div.nick-name', nick_list).remove();

					for(var i = 0; i < conn.listeners.length; i++)
						nick_list.append('<div class="nick-name">' + conn.listeners[i].nick + '</div>');
					
					$('span#connection-count').text(conn.count);
					
					nick_list.perfectScrollbar('update');
					
				}, 1000);
			
			if(conn.message != undefined) {
			
				if(!chatbox_type_api.elements.tooltip.is(':visible') && conn.message.user != user_nick) {
				
					if('unread_ctr' in window)
						window.unread_ctr++;
					else
						window.unread_ctr = 1;
					
					Cookies.set('unread_ctr', window.unread_ctr);

					if($('span.alert-counter', chatbox_type).length)
						$('span.alert-counter', chatbox_type).text(window.unread_ctr);
					else
						chatbox_type.append('<span class="label label-danger alert-counter">' + window.unread_ctr + '</span>');
				
					if(!window.chatbox_type_alert)
						window.chatbox_type_alert = setInterval(function () {
								
								chatbox_type.toggleClass('chatbox-hover');
								
							}, 500);
				}
				
				var m_d = moment.unix(conn.message.sent).format('MMM-DD');
				
				if(!$('div.date-' + m_d).length)
					$('div#type-box').append('<div class="date-heading">Today</div>');
				
				if($('div#type-box div.date-' + m_d + ' span.username:last').text() == conn.message.user)
					$('div#type-box div.date-' + m_d + ':last').append('<div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div>');
				else
					$('div#type-box').append('<div class="message' + (conn.message.user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + conn.message.user + '</span></div><div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div></div>')
				
				$('div#type-box span.sent:not(.hasqtip)').each(function () {

					$(this)
						.addClass('hasqtip')
						.qtip({
							content: {
									text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								}
						});	
				
				});
				
				$('div#type-box')
					.scrollTop($('div#type-box')[0].scrollHeight)
					.perfectScrollbar('update');
				
			}

			// if(conn.connected != undefined && window[user_type + '_listeners_api']) {
			
				// var nick_list = $('div.nick-list', window[user_type + '_listeners_api'].tooltip);
			
				// if(!$('div.nick-name:contains("' + conn.connected.nick + '")', window[user_type + '_listeners_api'].tooltip).length)
					// nick_list.append('<div class="nick-name">' + conn.connected.nick + '</div>');
				
				// $('span#connection-count').text($('div.nick-name', window[user_type + '_listeners_api'].tooltip).length);
				
				
				// nick_list.perfectScrollbar('update');
			// }
			
			// if(conn.disconnected != undefined && window[user_type + '_listeners_api']) {
			
				// $('div.nick-name:contains("' + conn.disconnected.nick + '")', window[user_type + '_listeners_api'].tooltip).remove();
				
				// $('span#connection-count').text($('div.nick-name', window[user_type + '_listeners_api'].tooltip).length);
				
				// $('div.nick-list', window[user_type + '_listeners_api'].tooltip).perfectScrollbar('update');
			// }
			
		});
	
}

function initChat_UserCurrencies(_channel) {

	var __channel = _channel;
	var _channel = _channel.replace(/[^a-z]/ig, '');
	
	var chatbox_curr = $('<i class="chatbox chatbox-hover fa fa-money fa-2x"><span' + (__channel.length > 3 ? ' style="left: -' + ((__channel.length - 3) * 1.33) + 'px;"' : '') + '>' + __channel + '</span></i>');
	
	$('div#chatbox-container').append(chatbox_curr);
	
	chatbox_curr.qtip({
		prerender: true,
		content: {
				text: '\
						<div class="chat-header">\
							<div class="pull-left connection-listeners">' + __channel + '&nbsp;(<span id="connection-count-' + _channel + '">0</span>)</div>\
							<div class="pull-right" id="connection-status-curr-' + _channel + '">CONNECTING <i class="fa fa-globe"></i></div>\
						</div>\
						<div class="clearfix"></div>\
						<div id="curr-box-' + _channel + '" class="chatbox-box"></div>\
						<input type="text" id="curr-input-' + _channel + '" class="chatbox-input" style="width: 249px;" maxlength="120" />\
					'
			},
		style: {
				classes: 'qtip-bootstrap',
				tip: {
						corner: true
					},
				width: '280px'
			},
		position: {
				my: 'bottom right',
				at: 'top center',
				viewport: $(window),
				adjust: {
						scroll: false,
						y: -10
					}
			},
		show: {
				ready: Cookies.get('csa_active_win') == _channel,
				event: 'click',
				solo: true
			},
		hide: {
				event: 'click'
			},
		events: {
				show: function () {

						Cookies.set('csa_active_win', _channel);
				
						if(window['chatbox_curr_alert-' + _channel]) {
						
							clearInterval(window['chatbox_curr_alert-' + _channel]);
							
							window['chatbox_curr_alert-' + _channel] = null;
							
							chatbox_curr.addClass('chatbox-hover');
							
							Cookies.expire('unread_ctr_curr-' + _channel);
							
						}
						
						window['unread_ctr_curr-' + _channel] = 0;
						
						$('span.alert-counter', chatbox_curr).remove();
						
						$('div#curr-box-' + _channel)
							.scrollTop($('div#curr-box-' + _channel)[0].scrollHeight)
							.perfectScrollbar('update');
						
						setTimeout(function () {

							if(Cookies.get('csa_active_win') == _channel) {
								
								$('input#curr-input-' + _channel).val(Cookies.get('csa_active_win_text'))
								
								moveCaretToEnd($('input#curr-input-' + _channel)[0]);
							}
							
							setTimeout(function () {
								
								$('input#curr-input-' + _channel).focus();
								
							}, 1);
							
							$('div#curr-box-' + _channel)
								.scrollTop($('div#curr-box-' + _channel)[0].scrollHeight)
								.perfectScrollbar('update');
							
						}, 1);
					},
				hide: function () {
						
						Cookies.expire('csa_active_win');
						Cookies.expire('csa_active_win_text');
					},
				render: function (elem, api) {

						$('div#curr-box-' + _channel).perfectScrollbar({
							suppressScrollX: true
						});
						
						if(NOTIFS.isConnected()) {
							
							if(window['connecting_anim_interval_curr_' + _channel])
								clearInterval(window['connecting_anim_interval_curr_' + _channel]);
							
							$('div#connection-status-curr-' + _channel).html('CONNECTED <i class="fa fa-globe" style="color: green;"></i>');
							
						}
						
						window[_channel + '_listeners'] = $('div.connection-listeners', elem.target).qtip({
							prerender: true,
							content: {
									text: '<div class="nick-list"></div>'
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								},
							hide: {
									delay: 500,
									fixed: true
								},
							events: {
									render: function (elem2, api2) {

											$('div.nick-list', elem2.target).perfectScrollbar();
											
											window[_channel + '_listeners_api'] = api2;
											
										},
									show: function (e, api) {
										
											if(!$('div.nick-name', e.target).length)
												e.preventDefault();
										}
								}
						});

						$('input#curr-input-' + _channel)
							.keyup(function (e) {
						
								var value = $.trim($(this).val());
								
								Cookies.set('csa_active_win_text', value);
								
								if(e.keyCode == 13 && value && NOTIFS.isConnected()) {
								
									Cookies.expire('csa_active_win_text');
								
									NOTIFS.spublish(__channel, { user: user_nick, message: value, sent: moment().format('X') }, function (data) {
										
										$('div.maxlength', elem.target).prepend('<span class="receivedby pull-left">Received by ' + data + ' listener' + (data == 1 ? '' : 's') + '</span>');
										
									});

									$(this).val('');
								}
								else if(e.keyCode == 27) {
									
									chatbox_curr.qtip('hide');
									
									$(this).val('');
								}
							})
							.maxlength();
						
						$(elem.target).click(function (e) {
							
							if(e.originalEvent.explicitOriginalTarget.nodeType != 3)
								$('input#curr-input-' + _channel).focus();
							
						});
						
						$('div#curr-box-' + _channel).on('click', 'div.prev a', function (e) {
		
							e.preventDefault();
							
							var that = this;
							var offset = $(this).data('offset') + 1;
							
							NOTIFS.list(__channel, function (data) {

								var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
								var list = '';
								var temp_list = $('<div />');
								
								if(data.total > 1 && data.next)
									temp_list.html('<div class="prev"><a href="#" data-offset="' + offset + '">' + moment(data.next + '-' + moment().format('YYYY'), 'MM-DD-YYY').format('MMM DD') + '</a></div>');
								
								if(!$('div.date-' + m_d, $('div#curr-box-' + _channel)[0]).length)
									temp_list.append('<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>');
								
								for(var i = 0; i < data.list.length; i++) {
									
									if($('span.username:last', temp_list).text() == data.list[i].user)
										$('div.message:last', temp_list).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>');
									else
										temp_list.append('<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>');
								}
								
								$('div#curr-box-' + _channel).prepend(temp_list.html());
								
								$('div#curr-box-' + _channel + ' span.sent:not(.hasqtip)').each(function () {

									$(this)
										.addClass('hasqtip')
										.qtip({
											content: {
													text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
												},
											style: {
													classes: 'qtip-bootstrap',
													tip: {
															corner: true
														}
												},
											position: {
													my: 'right center',
													at: 'left center',
													viewport: $(window),
													adjust: {
															x: -10
														}
												}
										});		
								
								});
								
								$(that).remove();
								
								$('div#curr-box-' + _channel)
									.scrollTop(0)
									.perfectScrollbar('update');
								
							}, offset);
							
						});
						
					}
			}
	});
	
	var chatbox_curr_api = chatbox_curr.qtip('api');
	
	window['connecting_anim_interval_curr_' + _channel] = setInterval(function () {
			
			$('div#connection-status-curr-' + _channel + ' i').toggleClass('connecting');
			
		}, 500);
	
	$(window).scroll(function (e) {
		
		chatbox_curr_api.reposition(e, false);
		
	});

	NOTIFS
		.onconnect(function () {

			if(window['connecting_anim_interval_curr_' + _channel])
				clearInterval(window['connecting_anim_interval_curr_' + _channel]);
			
			$('div#connection-status-curr-' + _channel).html('CONNECTED <i class="fa fa-globe" style="color: green;"></i>');
			
			$('input#curr-input-' + _channel).css('color', '#555');

			$('div#curr-box-' + _channel + ' > div:not(.ps-scrollbar-x-rail, .ps-scrollbar-y-rail)').remove();
			
			var _unread_ctr = Cookies.get('unread_ctr_curr-' + _channel);
					
			if(_unread_ctr) {
			
				window['unread_ctr_curr-' + _channel] = _unread_ctr;
			
				chatbox_curr.append('<span class="label label-danger alert-counter">' + _unread_ctr + '</span>');
		
				if(!window['chatbox_curr_alert-' + _channel])
					window['chatbox_curr_alert-' + _channel] = setInterval(function () {
							
							chatbox_curr.toggleClass('chatbox-hover');
							
						}, 500);
			}
				
			NOTIFS.list(__channel, function (data) {

				var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
				var list = '';
				var temp_list = $('<div />');
				
				if(data.total > 1 && data.next)
					temp_list.html('<div class="prev"><a href="#" data-offset="0">' + moment(data.next + '-' + moment().format('YYYY'), 'MM-DD-YYY').format('MMM DD') + '</a></div>');
				
				if(!$('div.date-' + m_d, $('div#curr-box-' + _channel)[0]).length)
					temp_list.append('<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>');
				
				for(var i = 0; i < data.list.length; i++) {
					
					if($('span.username:last', temp_list).text() == data.list[i].user)
						$('div.message:last', temp_list).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>');
					else
						temp_list.append('<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>');
				}
				
				$('div#curr-box-' + _channel)
					.prepend(temp_list.html())
					.perfectScrollbar('update');	

				$('div#curr-box-' + _channel + ' span.sent:not(.hasqtip)').each(function () {

					$(this)
						.addClass('hasqtip')
						.qtip({
							content: {
									text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								}
						});		
				
				});
			});

		})
		.ondisconnect(function () {
			
			$('div#connection-status-curr-' + _channel).html('DISCONNECTED <i class="fa fa-globe"></i>');
			
			$('input#curr-input-' + _channel).css('color', '#999');

		})
		.onreconnect_attempt(function () {
			
			$('div#connection-status-curr-' + _channel).html('RECONNECTING <i class="fa fa-globe"></i>');
			
			window['connecting_anim_interval_curr_' + _channel] = setInterval(function () {
			
					$('div#connection-status-curr-' + _channel + ' i').toggleClass('connecting');
					
				}, 500);
			
		})
		.subscribe(__channel, function (conn) {

			/* if(conn.count != undefined)
				setTimeout(function () {
				
					var listeners = new Array();
					
					for(var i = 0; i < conn.listeners.length; i++)
						if(listeners.indexOf(conn.listeners[i].nick))
							listeners[listeners.length] = conn.listeners[i].nick;

					$('span#connection-listeners')
						.text(conn.count + ' Listener' + (conn.count == 1 ? '' : 's'))
						.qtip({
							content: {
									text: '<div class="nick-list"><div class="nick-name">' + listeners.join('</div><div class="nick-name">') + '</div></div>'
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								},
							hide: {
									delay: 500,
									fixed: true
								},
							events: {
									render: function () {

											$('div.nick-list')
												.perfectScrollbar()
												.scroll();
											
										}
								}
						});
					
				}, 100); */

			if(conn.count != undefined)
				setTimeout(function () {

					var nick_list = $('div.nick-list', window[_channel + '_listeners_api'].tooltip);

					$('div.nick-name', nick_list).remove();

					for(var i = 0; i < conn.listeners.length; i++)
						nick_list.append('<div class="nick-name">' + conn.listeners[i].nick + '</div>');
					
					$('span#connection-count-' + _channel).text(conn.count);
					
					nick_list.perfectScrollbar('update');

				}, window[_channel + '_listeners_api'] == undefined ? 100 : 0);

			if(conn.message != undefined) {
			
				if(!chatbox_curr_api.elements.tooltip.is(':visible') && conn.message.user != user_nick) {
				
					if('unread_ctr_curr-' + _channel in window)
						window['unread_ctr_curr-' + _channel]++;
					else
						window['unread_ctr_curr-' + _channel] = 1;
					
					Cookies.set('unread_ctr_curr-' + _channel, window['unread_ctr_curr-' + _channel]);
					
					if($('span.alert-counter', chatbox_curr).length)
						$('span.alert-counter', chatbox_curr).text(window['unread_ctr_curr-' + _channel]);
					else
						chatbox_curr.append('<span class="label label-danger alert-counter">' + window['unread_ctr_curr-' + _channel] + '</span>');
				
					if(!window['chatbox_curr_alert-' + _channel])
						window['chatbox_curr_alert-' + _channel] = setInterval(function () {
								
								chatbox_curr.toggleClass('chatbox-hover');
								
							}, 500);
				}
				
				var m_d = moment.unix(conn.message.sent).format('MMM-DD');
				
				if(!$('div.date-' + m_d, $('div#curr-box-' + _channel)[0]).length)
					$('div#curr-box-' + _channel).append('<div class="date-heading">Today</div>');
				
				if($('div#curr-box-' + _channel + ' div.date-' + m_d + ' span.username:last').text() == conn.message.user)
					$('div#curr-box-' + _channel + ' div.date-' + m_d + ':last').append('<div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div>');
				else
					$('div#curr-box-' + _channel).append('<div class="message' + (conn.message.user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + conn.message.user + '</span></div><div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div></div>');
				
				$('div#curr-box-' + _channel + ' span.sent:not(.hasqtip)').each(function () {

					$(this)
						.addClass('hasqtip')
						.qtip({
							content: {
									text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
								},
							style: {
									classes: 'qtip-bootstrap',
									tip: {
											corner: true
										}
								},
							position: {
									my: 'right center',
									at: 'left center',
									viewport: $(window),
									adjust: {
											x: -10
										}
								}
						});	
				
				});
				
				$('div#curr-box-' + _channel)
					.scrollTop($('div#curr-box-' + _channel)[0].scrollHeight)
					.perfectScrollbar('update');
				
			}

			// if(conn.connected != undefined && window[_channel + '_listeners_api']) {
			
				// var nick_list = $('div.nick-list', window[_channel + '_listeners_api'].tooltip);
			
				// if(!$('div.nick-name:contains("' + conn.connected.nick + '")', window[_channel + '_listeners_api'].tooltip).length)
					// nick_list.append('<div class="nick-name">' + conn.connected.nick + '</div>');

				// $('span#connection-count-' + _channel).text($('div.nick-name', window[_channel + '_listeners_api'].tooltip).length);
				
				// nick_list.perfectScrollbar('update');
			// }

			// if(conn.disconnected != undefined && window[_channel + '_listeners_api']) {
			
				// $('div.nick-name:contains("' + conn.disconnected.nick + '")', window[_channel + '_listeners_api'].tooltip).remove();
				
				// $('span#connection-count-' + _channel).text($('div.nick-name', window[_channel + '_listeners_api'].tooltip).length);
				
				// $('div.nick-list', window[_channel + '_listeners_api'].tooltip).perfectScrollbar('update');
			// }
			
		});
	
}

function initChat() {

	var chatbox_container = $('<div id="chatbox-container" />');
	
	$('body').append(chatbox_container);
	
	/* if(user_type == 'super_admin' || user_type == 'admin') {
	
		var chatbox_settings = $('<i class="chatbox chatbox-hover fa fa-gear fa-2x"></i>');
		
		$('div#chatbox-container').append(chatbox_settings);
		
		$('body').append('\
			<div class="modal fade" id="chatSettingsModal" tabindex="-1" role="dialog" aria-labelledby="chatSettingsModalLabel" aria-hidden="true">\
				<div class="modal-dialog">\
					<div class="modal-content">\
						<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\
							 <h4 class="modal-title">Group Settings</h4>\
						</div>\
						<div class="modal-body">\
							<div id="carousel-chat-settings" class="carousel slide" data-ride="carousel" data-interval="false" data-pause="false">\
								<div class="carousel-inner">\
									<div class="item active">\
										test\
									</div>\
									<div class="item">\
										test2\
									</div>\
								</div>\
							</div>\
						</div>\
						<div class="modal-footer">\
							<button type="button" class="btn btn-default" id="close-chat-settings">Close</button>\
							<button type="button" class="btn btn-primary" id="next-chat-settings">Add New</button>\
						</div>\
					</div>\
				</div>\
			</div>\
		');
		
		$('div#chatSettingsModal').on('hidden.bs.modal', function () {
			
			$('div#carousel-chat-settings')
				.carousel(0)
				.carousel('pause');
			
			$('button#close-chat-settings').text('Close');
			
			$('button#next-chat-settings')
				.text('Add New')
				.removeAttr('disabled');
			
		});
		
		$('button#close-chat-settings').click(function () {
			
			if($(this).text() == 'Close')
				$('div#chatSettingsModal').modal('hide');
			else if($(this).text() == 'Cancel') {
			
				$(this).text('Close');
				
				$('button#next-chat-settings')
					.text('Add New')
					.removeAttr('disabled');
				
				$('div#carousel-chat-settings')
					.carousel(0)
					.carousel('pause');
			}
			
		});
		
		$('button#next-chat-settings').click(function () {
			
			$('div#carousel-chat-settings')
				.carousel(1)
				.carousel('pause');
			
			$('button#close-chat-settings').text('Cancel');
			
			$(this)
				.text('Save')
				.attr('disabled', 'disabled');
			
		});
		
		chatbox_settings.click(function () {
			
			$('.qtip').qtip('hide');
			
			$('div#chatSettingsModal').modal({
				backdrop: 'static'
			});
			
		});
	} */
	
	$.each(chat_groups, function (i, val) { 
		if($.inArray(user_type, val.users) != -1)  
			initChat_UserGroup(val.title, val.icon); 
	});
	
	// if(user_type == 'super_admin' || user_type == 'admin' || user_type == 'supervisor' || user_type == 'crm')
		// initChat_UserGroup('CRM GROUP', 'comments');
	
	initChat_UserType();
	
	$.each(currency_groups, function (i, val) {
		
		for(var i = 0; i < user_currencies.length; i++) {
			 
			if($.inArray(user_currencies[i], val.currencies) != -1) {
			
				initChat_UserGroup(val.title, val.icon);
				
				break;
			}
		}

	});
	
	for(var i = 0; i < user_currencies.length; i++)
		if($.inArray(user_currencies[i], ['N/A', 'AUD', 'GBP', 'EURO', 'MYR', 'USD']) == -1)
			initChat_UserCurrencies(user_currencies[i]);
}

WS.ready(
	'csachat',
	user_id,
	[
		base_url + 'media/css/chat/chat.css',
		base_url + 'media/css/chat/font-awesome.min.css',
		base_url + 'media/css/chat/jquery.qtip.min.css',
		base_url + 'media/js/chat/jquery.qtip.min.js',
		base_url + 'media/css/chat/perfect-scrollbar-0.4.10.min.css',
		base_url + 'media/js/chat/perfect-scrollbar-0.4.10.with-mousewheel.min.js',
		base_url + 'media/js/chat/moment.min.js',
		base_url + 'media/js/chat/jquery.maxlength.min.js',
		base_url + 'media/js/chat/cookies.min.js'
	],
	initChat,
	{
		nick: user_nick,
		type: user_type
	}
);