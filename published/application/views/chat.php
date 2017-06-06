<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>12Bet - CAL - Chat [<?=$this->session->userdata('mb_nick')?>]</title>
<link type="text/css" href="<?=base_url()?>media/css/chat/bootstrap.min.css" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>media/css/chat/perfect-scrollbar.min.css" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>media/css/chat/font-awesome.min.css" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>media/css/chat/jquery.qtip.min.css" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>media/css/chat/emoticons.css" rel="stylesheet" />
<style type="text/css">
html, body {
	height: 100%;
	overflow: hidden;
}

#chatbox {
	background-color: #ddd;
	position: relative;
	overflow: hidden;
	padding: 10px 0px;
}

#groups, #users {
	position: relative;
	overflow: hidden;
}

#message {
	height: 45px;
	width: 100%;
	padding: 12px 1px;
	resize: none;
	outline: none;
	border: none !important;
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
}

#message[disabled] {
	background-color: #fff;
}

.ps-scrollbar-y-rail {
	z-index: 3 !important;
}

.connecting {
	color: #ccc !important;
}

.maxlength {
	display: none;
}

.message {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	background-clip: padding-box;
	position: relative;
	padding: 10px;
	margin-top: 9px;
	margin-bottom: 6px;
	background-color: #fff;
	text-shadow: none;
	word-wrap: break-word;
}

.message.me {
	margin-left: 22px;
	margin-right: 20%;
	background-color: #cff1fb;
}

.message.notme {
	margin-right: /*70px*/ 22px;
	margin-left: /*40px*/ 20%;
}

.message.me:after {
	border-color: #fff;
	border-left-color: #fff;
	border-width: 8px;
	top: 70%;
	margin-top: -8px;
}

.message.notme:after {
	border-color: #fff;
	border-right-color: #fff;
	border-width: 8px;
	top: 70%;
	margin-top: -8px;
}

.message.me:before {
	top: 70%;
	margin-top: -9px;
	border-color: rgba(201, 201, 201, 0);
	border-right-color: #cff1fb !important;
	border-width: 9px !important;
}

.message.notme:before {
	top: 70%;
	margin-top: -9px;
	border-color: rgba(201, 201, 201, 0);
	border-left-color: #fff !important;
	border-width: 9px !important;
}

.message.me:after, .message.me:before {
	right: 100%;
	content: " ";
	height: 0px;
	width: 0px;
	position: absolute;
	pointer-events: none;
	border: medium solid transparent;
}

.message.notme:after, .message.notme:before {
	left: 100%;
	content: " ";
	height: 0px;
	width: 0px;
	position: absolute;
	pointer-events: none;
	border: medium solid transparent;
}

.username {
	font-weight: bold;
	font-size: small;
	color: #4c8080;
}

.sent {
	font-size: smaller;
	color: #777777;
	font-weight: lighter;
	border-bottom: 1px dashed #ccc;
}

.body {
	color: #666;
}

.prev, .date-heading {
	text-align: center;
	color: #a9a9a9;
	font-size: smaller;
	font-weight: lighter;
}

.date-spacer {
	border: 0;
	height: 1px;
	margin: 6px;
	background-image: -webkit-linear-gradient(left, rgba(180,180,180,0), rgba(180,180,180,0.75), rgba(180,180,180,0));
	background-image: -moz-linear-gradient(left, rgba(180,180,180,0), rgba(180,180,180,0.75), rgba(180,180,180,0));
	background-image: -ms-linear-gradient(left, rgba(180,180,180,0), rgba(180,180,180,0.75), rgba(180,180,180,0));
	background-image: -o-linear-gradient(left, rgba(180,180,180,0), rgba(180,180,180,0.75), rgba(180,180,180,0));
}

.avatar {
	position: absolute;
	bottom: 0px;
	border: 2px solid #fff;
	width: 40px;
	height: 40px;
	border-radius: 24px;
	box-shadow: 0px 1px 3px #666;
}

.avatar-left {
	left: -52px;
}

.avatar-right {
	right: -52px;
}

#send {
	font-size: small;
	margin-left: 3px;
	border-top-left-radius: 4px;
	border-bottom-left-radius: 4px;
}

#groups .active {
	background: url('<?=base_url()?>media/images/patterns/furley_bg2.png') repeat scroll 0% 0% transparent;
	border: 1px solid #C9C9C9;
	border-left: 3px solid #62AEEF;
	color: #777;
}

.badge-danger {
	background-color: #D15B47 !important;
	color: #fff !important;
}

.alert-counter-dim {
	opacity: .8;
}

.emoticon {
	margin-left: 5px;
}

.no-left-margin {
	margin-left: 0;
}

.nav-tabs a {
	outline: 0;
}

.sfx {
	height: 0px;
	width: 0px;
}
</style>
</head>
<body>
<div class="container-fluid" style="padding: 3px; height: 100%;">
	<div class="row" style="height: 100%;">
		<div class="col-xs-4 col-sm-2" style="padding-right: 1px;">
			
			<ul class="nav nav-tabs" role="tablist">
				<li class="active"><a href="#groups" role="tab" data-toggle="tab">Groups</a></li>
				<li class="hidden"><a href="#users" role="tab" data-toggle="tab" id="users-link">Users (<span id="connected-users">0</span>)</a></li>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane fade in active" id="groups">
					
					<div class="list-group">
						<?php
							
							foreach($titles as $k => $title)
								echo "<a href=\"#\" class=\"list-group-item connecting\" id=\"title-$k\" data-channel=\"$title\" data-index=\"$k\" data-type=\"title\">$title <small>connecting&hellip;</small></a>";
							
							foreach($currencies as $k => $currency)
								echo "<a href=\"#\" class=\"list-group-item connecting\" id=\"currency-$k\" data-channel=\"$currency\" data-index=\"$k\" data-type=\"currency\">$currency <small>connecting&hellip;</small></a>";
							
							foreach($custom_groups as $k => $group) {
							
								$group_title = preg_replace('/[^a-z0-9]+/i', '', $group->title);
								
								echo "<a href=\"#\" class=\"list-group-item connecting\" id=\"group-$k\" data-channel=\"$group_title\" data-index=\"$k\" data-type=\"group\">{$group->title} <small>connecting&hellip;</small></a>";
							}
							
						?>
					</div>
					
				</div>
				<div class="tab-pane fade" id="users">
					<ul class="list-group"></ul>
				</div>
			</div>
			
		</div>
		<div class="col-xs-8 col-sm-10" style="padding-left: 1px; height: 100%;">
			
			<div id="chatbox"></div>
			<form action="#" method="post">
				<div class="input-group">
					<textarea class="form-control" id="message" placeholder="Please select a group" disabled="disabled" maxlength="1000"></textarea>
					<span class="input-group-btn">
						<input class="btn btn-primary" type="submit" id="send" disabled="disabled" value="Send" title="Press ENTER to send or Shift+ENTER for new line" />
					</span>
				</div>
			</form>
			
		</div>
	</div>
</div>

<div id="sfx-player" class="sfx"></div>

<script type="text/javascript" src="<?=base_url()?>media/js/chat/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/jquery.qtip.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/jquery.maxlength.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/moment.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/blockies.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/jquery.hotkeys.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/emoticons.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/spin.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/cookies.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/jquery.blockUI.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/desktop-notify-custom.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>media/js/chat/jquery.jplayer.min.js"></script>

<script type="text/javascript">
	var SOCKET_LOCAL = '<?=base_url()?>media/js/chat/socket.io-1.3.6.js';
</script>
<script id="ws-loader" type="text/javascript" src="http://122.53.154.211/ws/loader.js?module=notifs"></script>

<script type="text/javascript">

var base_url = '<?=base_url()?>',
	user_type = '<?=$this->session->userdata('mb_usertype')?>',
	user_nick = '<?=$this->session->userdata('mb_nick')?>',
	user_id = <?=($this->session->userdata('mb_no') ? $this->session->userdata('mb_no') : 0)?>,
	orig_title = document.title,
	title_interval;

var input_focused = false;

function utf8_encode(argString) {

  if (argString === null || typeof argString === 'undefined') {
    return '';
  }

  var string = (argString + '');
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode(
        (c1 >> 6) | 192, (c1 & 63) | 128
      );
    } else if ((c1 & 0xF800) != 0xD800) {
      enc = String.fromCharCode(
        (c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    } else {
      if ((c1 & 0xFC00) != 0xD800) {
        throw new RangeError('Unmatched trail surrogate at ' + n);
      }
      var c2 = string.charCodeAt(++n);
      if ((c2 & 0xFC00) != 0xDC00) {
        throw new RangeError('Unmatched lead surrogate at ' + (n - 1));
      }
      c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
      enc = String.fromCharCode(
        (c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
      );
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}


function utf8_decode(str_data) {

  var tmp_arr = [],
    i = 0,
    ac = 0,
    c1 = 0,
    c2 = 0,
    c3 = 0,
    c4 = 0;

  str_data += '';

  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 <= 191) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 <= 223) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else if (c1 <= 239) {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      c4 = str_data.charCodeAt(i + 3);
      c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
      c1 -= 0x10000;
      tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1 >> 10) & 0x3FF));
      tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
      i += 4;
    }
  }

  return tmp_arr.join('');
}

function HtmlEncode(s)
{
  var el = document.createElement("div");
  // el.innerText = el.textContent = utf8_decode(s);
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  
  // var temp = $('<div>' + $.emoticons.replace(s).replace(/(?:\r\n|\r|\n)/g, '<br />') + '</div>');
  var temp = $('<div>' + s.replace(/(?:\r\n|\r|\n)/g, '<br />') + '</div>');
  
  if($(temp[0].childNodes[0]).hasClass('emoticon'))
	$('span:eq(0)', temp).addClass('no-left-margin');
  
  return temp.html();
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

function titleFlash() {

	var badges = $('span.badge');
	
	if(badges.length) {
		
		if(title_interval == undefined)
			title_interval = setInterval(function () {
			
					var i = 0;
		
					$('span.badge').each(function () {
						
						i += parseInt($(this).text());
						
					});
					
					document.title = document.title == orig_title ? i + ' new message' + (i > 1 ? 's' : '') : orig_title;
					
				}, 2000);
		
	}
	else {
		
		clearInterval(title_interval);
		
		title_interval = undefined;
		
		document.title = orig_title;
	}
	
}

var isCanvasSupported = (function () {

	var elem = document.createElement('canvas');
	return !!(elem.getContext && elem.getContext('2d'));
	
})();

var idle_timeout;

function initChat() {
	
	var titles = <?=json_encode($titles)?>;
	var currencies = <?=json_encode($currencies)?>;
	var custom_groups = <?=json_encode($custom_groups)?>;
	
	$.each(titles, function (i, title) {
	
		var link = $('a#title-' + i);
		
		NOTIFS
			.onconnect(function () {
				
				link.removeClass('connecting');
				
				$('small', link).remove();
				
				var _unread_ctr = Cookies.get('unread_ctr_title_' + i);
				
				if(_unread_ctr) {
					
					window['unread_ctr_title_' + i] = _unread_ctr;
					
					if(!$('span.alert-counter', link).length)
						link.append('<span class="badge badge-danger alert-counter">' + _unread_ctr + '</span>');
					
					window['unread_anim_title_' + i] = setInterval(function () {
								
							$('span.alert-counter', link).toggleClass('alert-counter-dim');
							
						}, 500);
				}
				
				titleFlash();
				
			})
			.subscribe(title, function (conn) {
				
				if(conn.count != undefined) {

					var nick_list = $('div#users ul.list-group');
					 
					for(var i2 = 0; i2 < conn.listeners.length; i2++)
						if(!$('li.user-' + conn.listeners[i2].uid, nick_list).length)
							nick_list.append('<li class="list-group-item title-' + i + ' user-' + conn.listeners[i2].uid + '">' + conn.listeners[i2].nick + '</li>');
						else if($('li.user-' + conn.listeners[i2].uid, nick_list).length && !$('li.user-' + conn.listeners[i2].uid, nick_list).hasClass('title-' + i))
							$('li.user-' + conn.listeners[i2].uid, nick_list).addClass('title-' + i);
					
					//if($('a.list-group-item.active').data('type') == 'title' && $('a.list-group-item.active').data('index') == i) {
					
						//$('li.list-group-item', nick_list).hide();
					
						$('li.title-' + i, nick_list).show();
						$('li:not(.title-' + i + ')', nick_list).hide();
						
						$('span#connected-users').text(conn.count);
					
						var list = $('div#users ul');
						var items = list.children('li').get();
						var scrollIndex = $('div#users').scrollTop();

						items.sort(function(a, b) {
						   return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
						})

						list.empty().append(items);
						
						$('div#users')
							.scrollTop(scrollIndex)
							.perfectScrollbar('update')
							.scroll();
					//}
					
				}
				
				if(conn.message != undefined) {

					if(conn.message.user != user_nick && !input_focused) {
					
						if(document.hasFocus && !document.hasFocus())
							window.focus();
					
						if('unread_ctr_title_' + i in window)
							window['unread_ctr_title_' + i]++;
						else
							window['unread_ctr_title_' + i] = 1;
						
						Cookies.set('unread_ctr_title_' + i, window['unread_ctr_title_' + i]);
						
						window.opener.updateCount('unread_ctr_title_' + i, window['unread_ctr_title_' + i]);
						
						if(!window['unread_anim_title_' + i])
							window['unread_anim_title_' + i] = setInterval(function () {
									
									$('span.alert-counter', link).toggleClass('alert-counter-dim');
									
								}, 500);

						if($('span.alert-counter', link).length)
							$('span.alert-counter', link).text(window['unread_ctr_title_' + i]);
						else
							link.append('<span class="badge badge-danger alert-counter">' + window['unread_ctr_title_' + i] + '</span>');
						
						titleFlash();
						
						if(notify.permissionLevel() == notify.PERMISSION_GRANTED && !input_focused)
							notify.createNotification(title, {
								tag: title,
								icon: base_url + 'media/images/chat/envelop.png',
								body: window['unread_ctr_title_' + i] + ' new message' + (window['unread_ctr_title_' + i] > 1 ? 's' : ''),
								onclick: function () {
										
										window.focus();
										link.click();
									}
							});
						
						$('div#sfx-player').jPlayer('stop').jPlayer('play');
					}
					
					if($('div#groups a.active').data('channel') == title) {
					
						var m_d = moment.unix(conn.message.sent).format('MMM-DD');
						
						if(!$('div.date-' + m_d).length)
							$('div#chatbox').append('<div class="date-heading">Today</div>');
						
						if($('div#chatbox div.date-' + m_d + ' span.username:last').text() == conn.message.user)
							$('div#chatbox div.date-' + m_d + ':last').append('<div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div>');
						else
							$('div#chatbox').append('<div class="message' + (conn.message.user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + conn.message.user + '</span></div><div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div></div>')
						
						$('div#chatbox span.sent:not(.hasqtip)').each(function () {

							$(this)
								.addClass('hasqtip')
								.qtip({
									content: {
											text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
										},
									style: 'qtip-dark',
									position: {
											my: 'right center',
											at: 'left center'
										}
								});	
						
						});
						
						$('div#chatbox')
							.scrollTop($('div#chatbox')[0].scrollHeight)
							.perfectScrollbar('update');
					}
				}
				
			});
		
	});
	
	$.each(currencies, function (i, currency) {
	
		var link = $('a#currency-' + i);
		
		NOTIFS
			.onconnect(function () {
				
				link.removeClass('connecting');
				
				$('small', link).remove();
				
				var _unread_ctr = Cookies.get('unread_ctr_currency_' + i);
				
				if(_unread_ctr) {
					
					window['unread_ctr_currency_' + i] = _unread_ctr;
					
					if(!$('span.alert-counter', link).length)
						link.append('<span class="badge badge-danger alert-counter">' + _unread_ctr + '</span>');
					
					window['unread_anim_currency_' + i] = setInterval(function () {
								
							$('span.alert-counter', link).toggleClass('alert-counter-dim');
							
						}, 500);
				}
				
				titleFlash();
				
			})
			.subscribe(currency, function (conn) {
				
				if(conn.count != undefined) {

					var nick_list = $('div#users ul.list-group');
					 
					for(var i2 = 0; i2 < conn.listeners.length; i2++)
						if(!$('li.user-' + conn.listeners[i2].uid, nick_list).length)
							nick_list.append('<li class="list-group-item currency-' + i + ' user-' + conn.listeners[i2].uid + '">' + conn.listeners[i2].nick + '</li>');
						else if($('li.user-' + conn.listeners[i2].uid, nick_list).length && !$('li.user-' + conn.listeners[i2].uid, nick_list).hasClass('currency-' + i))
							$('li.user-' + conn.listeners[i2].uid, nick_list).addClass('currency-' + i);
					
					//if($('a.list-group-item.active').data('type') == 'currency' && $('a.list-group-item.active').data('index') == i) {
					
						//$('li.list-group-item', nick_list).hide();
					
						$('li.currency-' + i, nick_list).show();
						$('li:not(.currency-' + i + ')', nick_list).hide();
						
						$('span#connected-users').text(conn.count);
					
						var list = $('div#users ul');
						var items = list.children('li').get();
						var scrollIndex = $('div#users').scrollTop();

						items.sort(function(a, b) {
						   return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
						})

						list.empty().append(items);
						
						$('div#users')
							.scrollTop(scrollIndex)
							.perfectScrollbar('update')
							.scroll();
					//}
					
				}
				
				if(conn.message != undefined) {

					if(conn.message.user != user_nick && !input_focused) {
					
						if(document.hasFocus && !document.hasFocus())
							window.focus();
					
						if('unread_ctr_currency_' + i in window)
							window['unread_ctr_currency_' + i]++;
						else
							window['unread_ctr_currency_' + i] = 1;
						
						Cookies.set('unread_ctr_currency_' + i, window['unread_ctr_currency_' + i]);
						
						window.opener.updateCount('unread_ctr_currency_' + i, window['unread_ctr_currency_' + i]);
						
						if(!window['unread_anim_currency_' + i])
							window['unread_anim_currency_' + i] = setInterval(function () {
									
									$('span.alert-counter', link).toggleClass('alert-counter-dim');
									
								}, 500);

						if($('span.alert-counter', link).length)
							$('span.alert-counter', link).text(window['unread_ctr_currency_' + i]);
						else
							link.append('<span class="badge badge-danger alert-counter">' + window['unread_ctr_currency_' + i] + '</span>');
						
						titleFlash();
						
						if(notify.permissionLevel() == notify.PERMISSION_GRANTED && !input_focused)
							notify.createNotification(currency, {
								tag: currency,
								icon: base_url + 'media/images/chat/envelop.png',
								body: window['unread_ctr_currency_' + i] + ' new message' + (window['unread_ctr_currency_' + i] > 1 ? 's' : ''),
								onclick: function () {
										
										window.focus();
										link.click();
									}
							});
						
						$('div#sfx-player').jPlayer('stop').jPlayer('play');
					}
					
					if($('div#groups a.active').data('channel') == currency) {
						
						var m_d = moment.unix(conn.message.sent).format('MMM-DD');
						
						if(!$('div.date-' + m_d).length)
							$('div#chatbox').append('<div class="date-heading">Today</div>');
						
						if($('div#chatbox div.date-' + m_d + ' span.username:last').text() == conn.message.user)
							$('div#chatbox div.date-' + m_d + ':last').append('<div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div>');
						else
							$('div#chatbox').append('<div class="message' + (conn.message.user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + conn.message.user + '</span></div><div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div></div>')
						
						$('div#chatbox span.sent:not(.hasqtip)').each(function () {

							$(this)
								.addClass('hasqtip')
								.qtip({
									content: {
											text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
										},
									style: 'qtip-dark',
									position: {
											my: 'right center',
											at: 'left center'
										}
								});	
						
						});
						
						$('div#chatbox')
							.scrollTop($('div#chatbox')[0].scrollHeight)
							.perfectScrollbar('update');
					}
				}
				
			});
		
	});
	
	$.each(custom_groups, function (i, group) {
	
		var link = $('a#group-' + i);
		
		NOTIFS
			.onconnect(function () {
				
				link.removeClass('connecting');
				
				$('small', link).remove();
				
				var _unread_ctr = Cookies.get('unread_ctr_group_' + i);
				
				if(_unread_ctr) {
					
					window['unread_ctr_group_' + i] = _unread_ctr;
					
					if(!$('span.alert-counter', link).length)
						link.append('<span class="badge badge-danger alert-counter">' + _unread_ctr + '</span>');
					
					window['unread_anim_group_' + i] = setInterval(function () {
								
							$('span.alert-counter', link).toggleClass('alert-counter-dim');
							
						}, 500);
				}
				
				titleFlash();
				
			})
			.subscribe(group.title.replace(/[^a-z0-9]/ig, ''), function (conn) {
				
				if(conn.count != undefined) {

					var nick_list = $('div#users ul.list-group');
					 
					for(var i2 = 0; i2 < conn.listeners.length; i2++)
						if(!$('li.user-' + conn.listeners[i2].uid, nick_list).length)
							nick_list.append('<li class="list-group-item group-' + i + ' user-' + conn.listeners[i2].uid + '">' + conn.listeners[i2].nick + '</li>');
						else if($('li.user-' + conn.listeners[i2].uid, nick_list).length && !$('li.user-' + conn.listeners[i2].uid, nick_list).hasClass('group-' + i))
							$('li.user-' + conn.listeners[i2].uid, nick_list).addClass('group-' + i);
					
					//if($('a.list-group-item.active').data('type') == 'group' && $('a.list-group-item.active').data('index') == i) {
					
						//$('li.list-group-item', nick_list).hide();
					
						$('li.group-' + i, nick_list).show();
						$('li:not(.group-' + i + ')', nick_list).hide();
						
						$('span#connected-users').text(conn.count);
					
						var list = $('div#users ul');
						var items = list.children('li').get();
						var scrollIndex = $('div#users').scrollTop();

						items.sort(function(a, b) {
						   return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
						})

						list.empty().append(items);
						
						$('div#users')
							.scrollTop(scrollIndex)
							.perfectScrollbar('update')
							.scroll();
					//}
					
				}
				
				if(conn.message != undefined) {

					if(conn.message.user != user_nick && !input_focused) {
					
						if(document.hasFocus && !document.hasFocus())
							window.focus();
					
						if('unread_ctr_group_' + i in window)
							window['unread_ctr_group_' + i]++;
						else
							window['unread_ctr_group_' + i] = 1;
						
						Cookies.set('unread_ctr_group_' + i, window['unread_ctr_group_' + i]);
						
						window.opener.updateCount('unread_ctr_group_' + i, window['unread_ctr_group_' + i]);
						
						if(!window['unread_anim_group_' + i])
							window['unread_anim_group_' + i] = setInterval(function () {
									
									$('span.alert-counter', link).toggleClass('alert-counter-dim');
									
								}, 500);

						if($('span.alert-counter', link).length)
							$('span.alert-counter', link).text(window['unread_ctr_group_' + i]);
						else
							link.append('<span class="badge badge-danger alert-counter">' + window['unread_ctr_group_' + i] + '</span>');
						
						titleFlash();
						
						if(notify.permissionLevel() == notify.PERMISSION_GRANTED && !input_focused)
							notify.createNotification(group.title, {
								tag: group.title,
								icon: base_url + 'media/images/chat/envelop.png',
								body: window['unread_ctr_group_' + i] + ' new message' + (window['unread_ctr_group_' + i] > 1 ? 's' : ''),
								onclick: function () {
										
										window.focus();
										link.click();
									}
							});
						
						$('div#sfx-player').jPlayer('stop').jPlayer('play');
					}
					
					if($('div#groups a.active').data('channel') == group.title.replace(/[^a-z0-9]/ig, '')) {
						
						var m_d = moment.unix(conn.message.sent).format('MMM-DD');
						
						if(!$('div.date-' + m_d).length)
							$('div#chatbox').append('<div class="date-heading">Today</div>');
						
						if($('div#chatbox div.date-' + m_d + ' span.username:last').text() == conn.message.user)
							$('div#chatbox div.date-' + m_d + ':last').append('<div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div>');
						else
							$('div#chatbox').append('<div class="message' + (conn.message.user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + conn.message.user + '</span></div><div class="body"><span class="sent" data-timestamp="' + conn.message.sent + '">' + moment.unix(conn.message.sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(conn.message.message) + '</div></div>')
						
						$('div#chatbox span.sent:not(.hasqtip)').each(function () {

							$(this)
								.addClass('hasqtip')
								.qtip({
									content: {
											text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
										},
									style: 'qtip-dark',
									position: {
											my: 'right center',
											at: 'left center'
										}
								});	
						
						});
						
						$('div#chatbox')
							.scrollTop($('div#chatbox')[0].scrollHeight)
							.perfectScrollbar('update');
					}
				}
				
			});
		
	});
}

function showSearch() {

	if(NOTIFS.isConnected() && $('div#groups a.active').length) {
		
		$('input#send').html('<i class="fa fa-search"></i>');
		
		$('textarea#message').focus();
	}
	
}

function getSelectionText() {
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    return text;
}

function getCaret(el) {
  if (el.selectionStart) {
     return el.selectionStart;
  } else if (document.selection) {
     el.focus();

   var r = document.selection.createRange();
   if (r == null) {
    return 0;
   }

    var re = el.createTextRange(),
    rc = re.duplicate();
    re.moveToBookmark(r.getBookmark());
    rc.setEndPoint('EndToStart', re);

    return rc.text.length;
  }  
  return 0;
}

$(function () {

	var resizeTimeout;
	
	$(window).resize(function () {
	
		clearTimeout(resizeTimeout);
		
		resizeTimeout = setTimeout(function () {
				
				$('div#groups, div#users')
					.innerHeight($('div.row').outerHeight() - $('ul.nav-tabs').outerHeight())
					.perfectScrollbar('update');
				
				$('div#chatbox')
					.innerHeight($('div.row').outerHeight() - $('textarea#message').outerHeight())
					.perfectScrollbar('update');
				
			}, 50);
		
	});

	$.emoticons.define({
		"smile": {
			"title": "Smile",
			"codes": [":)", ":=)", ":-)", '=)']
		},
		"sad-smile": {
			"title": "Sad Smile",
			"codes": [":(", ":=(", ":-("]
		},
		"big-smile": {
			"title": "Big Smile",
			"codes": [":D", ":=D", ":-D", ":d", ":=d", ":-d"]
		},
		"cool": {
			"title": "Cool",
			"codes": ["8)", "8=)", "8-)", "B)", "B=)", "B-)", "(cool)"]
		},
		"wink": {
			"title": "Wink",
			"codes": [":o", ":=o", ":-o", ":O", ":=O", ":-O"]
		},
		"crying": {
			"title": "Crying",
			"codes": [";(", ";-(", ";=("]
		},
		"sweating": {
			"title": "Sweating",
			"codes": ["(sweat)", "(:|"]
		},
		"speechless": {
			"title": "Speechless",
			"codes": [":|", ":=|", ":-|"]
		},
		"kiss": {
			"title": "Kiss",
			"codes": [":*", ":=*", ":-*"]
		},
		"tongue-out": {
			"title": "Tongue Out",
			"codes": [":P", ":=P", ":-P", ":p", ":=p", ":-p"]
		},
		"blush": {
			"title": "Blush",
			"codes": ["(blush)", ":$", ":-$", ":=$", ":\">"]
		},
		"wondering": {
			"title": "Wondering",
			"codes": [":^)"]
		},
		"sleepy": {
			"title": "Sleepy",
			"codes": ["|-)", "I-)", "I=)", "(snooze)"]
		},
		"dull": {
			"title": "Dull",
			"codes": ["|(", "|-(", "|=("]
		},
		"in-love": {
			"title": "In love",
			"codes": ["(inlove)"]
		},
		"evil-grin": {
			"title": "Evil grin",
			"codes": ["]:)", ">:)", "(grin)"]
		},
		"talking": {
			"title": "Talking",
			"codes": ["(talk)"]
		},
		"yawn": {
			"title": "Yawn",
			"codes": ["(yawn)", "|-()"]
		},
		"puke": {
			"title": "Puke",
			"codes": ["(puke)", ":&", ":-&", ":=&"]
		},
		"doh!": {
			"title": "Doh!",
			"codes": ["(doh)"]
		},
		"angry": {
			"title": "Angry",
			"codes": [":@", ":-@", ":=@", "x(", "x-(", "x=(", "X(", "X-(", "X=("]
		},
		"it-wasnt-me": {
			"title": "It wasn't me",
			"codes": ["(wasntme)"]
		},
		"party": {
			"title": "Party!!!",
			"codes": ["(party)"]
		},
		"worried": {
			"title": "Worried",
			"codes": [":S", ":-S", ":=S", ":s", ":-s", ":=s"]
		},
		"mmm": {
			"title": "Mmm...",
			"codes": ["(mm)"]
		},
		"nerd": {
			"title": "Nerd",
			"codes": ["8-|", "B-|", "8|", "B|", "8=|", "B=|", "(nerd)"]
		},
		"lips-sealed": {
			"title": "Lips Sealed",
			"codes": [":x", ":-x", ":X", ":-X", ":#", ":-#", ":=x", ":=X", ":=#"]
		},
		"hi": {
			"title": "Hi",
			"codes": ["(hi)"]
		},
		"call": {
			"title": "Call",
			"codes": ["(call)"]
		},
		"devil": {
			"title": "Devil",
			"codes": ["(devil)"]
		},
		"angel": {
			"title": "Angel",
			"codes": ["(angel)"]
		},
		"envy": {
			"title": "Envy",
			"codes": ["(envy)"]
		},
		"wait": {
			"title": "Wait",
			"codes": ["(wait)"]
		},
		"bear": {
			"title": "Bear",
			"codes": ["(bear)", "(hug)"]
		},
		"make-up": {
			"title": "Make-up",
			"codes": ["(makeup)", "(kate)"]
		},
		"covered-laugh": {
			"title": "Covered Laugh",
			"codes": ["(giggle)", "(chuckle)"]
		},
		"clapping-hands": {
			"title": "Clapping Hands",
			"codes": ["(clap)"]
		},
		"thinking": {
			"title": "Thinking",
			"codes": ["(think)", ":?", ":-?", ":=?"]
		},
		"bow": {
			"title": "Bow",
			"codes": ["(bow)"]
		},
		"rofl": {
			"title": "Rolling on the floor laughing",
			"codes": ["(rofl)"]
		},
		"whew": {
			"title": "Whew",
			"codes": ["(whew)"]
		},
		"happy": {
			"title": "Happy",
			"codes": ["(happy)"]
		},
		"smirking": {
			"title": "Smirking",
			"codes": ["(smirk)"]
		},
		"nodding": {
			"title": "Nodding",
			"codes": ["(nod)"]
		},
		"shaking": {
			"title": "Shaking",
			"codes": ["(shake)"]
		},
		"punch": {
			"title": "Punch",
			"codes": ["(punch)"]
		},
		"emo": {
			"title": "Emo",
			"codes": ["(emo)"]
		},
		"yes": {
			"title": "Yes",
			"codes": ["(y)", "(Y)", "(ok)"]
		},
		"no": {
			"title": "No",
			"codes": ["(n)", "(N)"]
		},
		"handshake": {
			"title": "Shaking Hands",
			"codes": ["(handshake)"]
		},
		"skype": {
			"title": "Skype",
			"codes": ["(skype)", "(ss)"]
		},
		"heart": {
			"title": "Heart",
			"codes": ["(h)", "<3", "(H)", "(l)", "(L)"]
		},
		"broken-heart": {
			"title": "Broken heart",
			"codes": ["(u)", "(U)"]
		},
		"mail": {
			"title": "Mail",
			"codes": ["(e)", "(m)"]
		},
		"flower": {
			"title": "Flower",
			"codes": ["(f)", "(F)"]
		},
		"rain": {
			"title": "Rain",
			"codes": ["(rain)", "(london)", "(st)"]
		},
		"sun": {
			"title": "Sun",
			"codes": ["(sun)"]
		},
		"time": {
			"title": "Time",
			"codes": ["(o)", "(O)", "(time)"]
		},
		"music": {
			"title": "Music",
			"codes": ["(music)"]
		},
		"movie": {
			"title": "Movie",
			"codes": ["(~)", "(film)", "(movie)"]
		},
		"phone": {
			"title": "Phone",
			"codes": ["(mp)", "(ph)"]
		},
		"coffee": {
			"title": "Coffee",
			"codes": ["(coffee)"]
		},
		"pizza": {
			"title": "Pizza",
			"codes": ["(pizza)", "(pi)"]
		},
		"cash": {
			"title": "Cash",
			"codes": ["(cash)", "(mo)", "($)"]
		},
		"muscle": {
			"title": "Muscle",
			"codes": ["(muscle)", "(flex)"]
		},
		"cake": {
			"title": "Cake",
			"codes": ["(^)", "(cake)"]
		},
		"beer": {
			"title": "Beer",
			"codes": ["(beer)"]
		},
		"drink": {
			"title": "Drink",
			"codes": ["(d)", "(D)"]
		},
		"dance": {
			"title": "Dance",
			"codes": ["(dance)", "\\o/", "\\:D/", "\\:d/"]
		},
		"ninja": {
			"title": "Ninja",
			"codes": ["(ninja)"]
		},
		"star": {
			"title": "Star",
			"codes": ["(*)"]
		},
		"mooning": {
			"title": "Mooning",
			"codes": ["(mooning)"]
		},
		"finger": {
			"title": "Finger",
			"codes": ["(finger)"]
		},
		"bandit": {
			"title": "Bandit",
			"codes": ["(bandit)"]
		},
		"drunk": {
			"title": "Drunk",
			"codes": ["(drunk)"]
		},
		"smoking": {
			"title": "Smoking",
			"codes": ["(smoking)", "(smoke)", "(ci)"]
		},
		"toivo": {
			"title": "Toivo",
			"codes": ["(toivo)"]
		},
		"rock": {
			"title": "Rock",
			"codes": ["(rock)"]
		},
		"headbang": {
			"title": "Headbang",
			"codes": ["(headbang)", "(banghead)"]
		},
		"bug": {
			"title": "Bug",
			"codes": ["(bug)"]
		},
		"fubar": {
			"title": "Fubar",
			"codes": ["(fubar)"]
		},
		"poolparty": {
			"title": "Poolparty",
			"codes": ["(poolparty)"]
		},
		"swearing": {
			"title": "Swearing",
			"codes": ["(swear)"]
		},
		"tmi": {
			"title": "TMI",
			"codes": ["(tmi)"]
		},
		"heidy": {
			"title": "Heidy",
			"codes": ["(heidy)"]
		},
		"myspace": {
			"title": "MySpace",
			"codes": ["(MySpace)"]
		},
		"malthe": {
			"title": "Malthe",
			"codes": ["(malthe)"]
		},
		"tauri": {
			"title": "Tauri",
			"codes": ["(tauri)"]
		},
		"priidu": {
			"title": "Priidu",
			"codes": ["(priidu)"]
		}
	});

	$('textarea#message')
		.val('')
		.attr('disabled', 'disabled')
		.keydown(function(e){

			if (e.keyCode == 13 && e.shiftKey) {
			
				e.stopPropagation();
				e.preventDefault();
				
				var content = this.value;
				
				var caret = getCaret(this);
				
				this.value = content.substring(0, caret) + '\n' + content.substring(caret, content.length);
				
				if($(this).css('height') != '135px') {
				
					$('div#chatbox').height($('div#chatbox').height() - 90);
					
					$(this).css('height', '135px');
				}
				
			}
			else if(e.keyCode == 13) {
				
				e.stopPropagation();
				e.preventDefault();
				
				if($(this).css('height') == '135px' && $.trim($(this).val())) {
					
					$('div#chatbox').height($('div#chatbox').height() + 90);
					
					$(this).css('height', '45px');
				}
				
				$('input#send').click();
			}
		})
		.blur(function () {
			
			if($(this).css('height') == '135px' && !$.trim($(this).val())) {
				
				$('div#chatbox').height($('div#chatbox').height() + 90);
					
				$(this)
					.css('height', '45px')
					.val('');
				
			}
			
		})
		.on('input propertychange', function () {
			
			var v = $.trim($(this).val());

			if($(this).css('height') == '135px' && (!(v.match(/\n/g)||[]).length || this.clientHeight == this.scrollHeight)) {
				
				$('div#chatbox').height($('div#chatbox').height() + 90);
					
				$(this).css('height', '45px');
			}
			
			if($(this).css('height') != '135px' && ((v.match(/\n/g)||[]).length || this.clientHeight < this.scrollHeight)) {
					
				$('div#chatbox').height($('div#chatbox').height() - 90);
					
				$(this).css('height', '135px');
			}
			
		});
	
	$('input#send')
		.text('Send')
		.attr('disabled', 'disabled');
	
	$('div#groups, div#users')
		.innerHeight($('div.row').outerHeight() - $('ul.nav-tabs').outerHeight())
		.perfectScrollbar({
			suppressScrollX: true,
			minScrollbarLength: 20
		});
	
	$('div#chatbox').innerHeight($('div.row').outerHeight() - $('textarea#message').outerHeight());
	
	$('div#groups a.list-group-item').click(function (e) {
		
		e.preventDefault();
		
		if(!$(this).hasClass('connecting')) {
			
			window.opener.setActiveChannel($(this).data('channel'));
		
			$('div#groups a.list-group-item').removeClass('active');
			
			$(this).addClass('active');
			
			if($('ul.nav-tabs li.hidden').length)
				$('ul.nav-tabs li.hidden').removeClass('hidden');
			
			$('textarea#message')
				.removeAttr('disabled')
				.attr('placeholder', 'Enter your message')
				.focus();
			
			$('input#send').removeAttr('disabled');
				
			var channel = $(this).data('channel');
			
			$('span#connected-users').text(NOTIFS.getSubscribeCountInfo(channel));
			
			$('div#chatbox')
				.empty()
				.append(new Spinner({ color: '#bbb' }).spin().el);
			
			setTimeout(function () {
				
				if($('div.spinner').length)
					$('div.spinner').remove();
				
			}, 1000);

			NOTIFS.list(channel, function (data) {

				$('div#chatbox')
					.empty()
					.perfectScrollbar('destroy')
					.perfectScrollbar({
						suppressScrollX: true,
						minScrollbarLength: 20,
						includePadding: true
					});

				var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
				var list = '';
				var temp_list = new Array(),
					last_user,
					last_message;
				
				if(data.total > 1 && data.next)
					temp_list[temp_list.length] = '<div class="prev"><a href="#" data-offset="0">' + moment(data.next, 'YY-MM-DD').format('MMM DD') + '</a></div>';

				if(!$('div.date-' + m_d).length)
					temp_list[temp_list.length] = (data.next ? '<hr class="date-spacer" />' : '') + '<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>';
				
				for(var i = 0; i < data.list.length; i++) {

					if(last_user == data.list[i].user)
						temp_list[last_message] = $(temp_list[last_message]).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>')
					else {
					
						last_message = temp_list.length;
					
						temp_list[temp_list.length] = '<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>';
					}
						
					last_user = data.list[i].user;
				}
				
				$('div#chatbox')
					.prepend(temp_list)
					.scrollTop($('div#chatbox')[0].scrollHeight)
					.perfectScrollbar('update');
				
				$('span.username').each(function () {
				
					var _parent = $(this).parent().parent();
					
					// _parent.append('<img class="avatar avatar-' + (_parent.hasClass('me') ? 'left' : 'right') + '" src="' + blockies.create({ seed: $(this).text(), size: 10, scale: 4 }).toDataURL() + '" />');
					
				});
				
				$('div#chatbox span.sent:not(.hasqtip)').each(function () {

					$(this)
						.addClass('hasqtip')
						.qtip({
							content: {
									text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
								},
							style: 'qtip-dark',
							position: {
									my: 'right center',
									at: 'left center'
								}
						});	
				
				});
			});
			
			// setTimeout(function () {
				
				// $('ul.nav-tabs a:eq(1)').tab('show');
				
			// }, 100);
		}
		
	});
	
	$('div#chatbox').click(function () {
		
		$('textarea#message').focus();
		
	});
	
	$('div#chatbox').on('click', 'div.message', function (e) {
		
		e.stopPropagation();

	});
	
	$('textarea#message')
		.maxlength()
		.on('update.maxlength', function (event, element, lastLength, length, maxLength, left) {
			
			$('textarea#message').qtip('option', 'content.text', left + ' characters left');
			
			if(!$('div.qtip.qtip-focus:visible').length)
				$(this).qtip('show');
			
			if(maxLength == left)
				$(this).qtip('hide');
			
		})
		.focus(function () {
			
			if($.trim($(this).val()))
				$(this).trigger('chat.focus');
			
			input_focused = true;
			
			//window.opener.setFocused(input_focused);
		})
		.blur(function () {
			
			input_focused = false;
			
			//window.opener.setFocused(input_focused);
			
		})
		.qtip({
			content: {
					text: $('textarea#message').attr('maxlength') + ' characters left'
				},
			style: {
					classes: 'qtip-dark',
					tip: {
							corner: false
						}
				},
			position: {
					my: 'bottom right',
					at: 'bottom right',
					target: $('div#chatbox'),
					adjust: {
							method: 'none shift'
						}
				},
			show: {
					event: 'chat.focus'
				},
			hide: {
				   event: 'blur'
				}
		});
	
	$('a#users-link').on('shown.bs.tab', function () {

		// var link = $('div#groups a.active');
		// var index = link.data('index');
		
		// if(link.hasClass('title'))
			// $('div#users li.title-' + index).show();
		// else
			// $('div#users li:not(.title-' + index + ')').hide();
		
		// if(link.hasClass('currency'))
			// $('div#users li.currency-' + index).show();
		// else
			// $('div#users li:not(.currency-' + index + ')').hide();
		
		var list = $('div#users ul');
		var items = list.children('li').get();

		items.sort(function(a, b) {
		   return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
		})

		list.empty().append(items);
		
		$('div#users')
			.scrollTop(0)
			.perfectScrollbar('update')
			.scroll();
		
	});
	
	$(window).keydown('ctrl+t', function (e) {

		e.preventDefault();

		var t = getSelectionText();
		
		if(t)
			alert(utf8_decode(t));
		
	});
	
	// $('textarea#message').bind('keydown', 'ctrl+f', function (e) {
		
		// e.preventDefault();
		
		// showSearch();
		
	// });
	
	$('textarea#message').focus(function () {
	
		var link = $('div#groups a.active');
		
		if(link.length && $('span.badge', link[0]).length) {
		
			window.opener.reduceCount(parseInt($('span.badge', link[0]).text()), 'unread_ctr_' + link.data('type') + '_' + link.data('index'));
			
			window['unread_ctr_' + link.data('type') + '_' + link.data('index')] = null;
			
			Cookies.expire('unread_ctr_' + link.data('type') + '_' + link.data('index'));
			
			clearInterval(window['unread_anim_' + link.data('type') + '_' + link.data('index')]);
			
			window['unread_anim_' + link.data('type') + '_' + link.data('index')] = null;
			
			$('span.badge', link[0]).remove();
			
			titleFlash();
		}
		
	});
	
	$('form').submit(function (e) {
		
		e.preventDefault();
		
		var channel = $('div#groups a.active').data('channel');
		var t = $.trim($('textarea#message').val());
		
		if($.inArray(channel, NOTIFS.getSubscriptions()) != -1 && t) {
		
			window.pending_message = setTimeout(function () {
					
					$('textarea#message').attr('readonly', 'readonly');
					$('input#send').attr('disabled', 'disabled');
					
				}, 100);
		
			NOTIFS.spublish(channel, { user: user_nick, message: t, sent: moment().format('X') }, function () {
				
				if('pending_message' in window)
					clearTimeout(window.pending_message);
					
				$('textarea#message')
					.val('')
					.removeAttr('readonly');
				
				$('input#send').removeAttr('disabled');
				
			});
			
			$('textarea#message').focus();
		}
		
	});
	
	$('div#chatbox').on('click', 'div.prev a', function (e) {
		
		e.preventDefault();
		
		var that = this;
		var offset = $(this).data('offset') + 1;
		
		NOTIFS.list($('div#groups a.active').data('channel'), function (data) {

			var m_d = moment.unix(data.list[0].sent).format('MMM-DD');
			var list = '';
			var temp_list = new Array(),
				last_user,
				last_message;
		
			if(data.total > 1 && data.next)
				temp_list[temp_list.length] = '<div class="prev"><a href="#" data-offset="' + offset + '">' + moment(data.next, 'YY-MM-DD').format('MMM DD') + '</a></div>';
			
			if(!$('div.date-' + m_d).length)
				temp_list[temp_list.length] = (data.next ? '<hr class="date-spacer" />' : '') + '<div class="date-heading">' + (m_d == moment().format('MMM-DD') ? 'Today' : m_d.replace('-', ' ')) + '</div>';
			
			for(var i = 0; i < data.list.length; i++) {

				if(last_user == data.list[i].user)
					temp_list[last_message] = $(temp_list[last_message]).append('<div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div>');
				else {
				
					last_message = temp_list.length;
					
					temp_list[temp_list.length] = '<div class="message' + (data.list[i].user == user_nick ? ' me' : ' notme') + ' date-' + m_d + '"><div><span class="username">' + data.list[i].user + '</span></div><div class="body"><span class="sent" data-timestamp="' + data.list[i].sent + '">' + moment.unix(data.list[i].sent).format('HH:mm') + '</span>&nbsp;' + HtmlEncode(data.list[i].message) + '</div></div>';
				}
				
				last_user = data.list[i].user;
			}
			
			$('div#chatbox').prepend(temp_list);
			
			$('span.username').each(function () {
				
				var _parent = $(this).parent().parent();
				
				// _parent.append('<img class="avatar avatar-' + (_parent.hasClass('me') ? 'left' : 'right') + '" src="' + blockies.create({ seed: $(this).text(), size: 10, scale: 4 }).toDataURL() + '" />');
				
			});
			
			$('div#chatbox span.sent:not(.hasqtip)').each(function () {

				$(this)
					.addClass('hasqtip')
					.qtip({
						content: {
								text: moment.unix($(this).data('timestamp')).format('MM-DD HH:mm:ss')
							},
						style: 'qtip-dark',
						position: {
								my: 'right center',
								at: 'left center'
							}
					});	
			
			});
			
			$(that)
				.parent()
					.css('height', '12px');

			$(that).remove();
			
			$('div#chatbox')
				.scrollTop(0)
				.perfectScrollbar('update');
			
		}, offset);
		
	});
	
	$(window)
		.blur(function () {
			
			// window.opener.setActiveChannel('');
			
			// $('div#groups a').removeClass('active');
			
			// $('ul.nav-tabs li:eq(1)').addClass('hidden');
			
			// $('textarea#message')
				// .val('')
				// .attr('disabled', 'disabled')
				// .attr('placeholder', 'Please select a group');
			
			// $('input#send').attr('disabled', 'disabled');
			
			// $('div#chatbox').empty();
			
			// window.opener.setFocused(input_focused);
			window.opener.setFocused(false);
			
			idle_timeout = setTimeout(function () {
					
					top.location.reload();
					
				}, 600000);
			
		})
		.focus(function () {
			
			// window.opener.setFocused(input_focused);
			window.opener.setFocused(true);
			
			clearTimeout(idle_timeout);
			
		});
	
	$('input#send').qtip({
		style: 'qtip-dark',
		position: {
				my: 'right center',
				at: 'left center'
			}
	});
	
	WS.ready(
		'csachat',
		user_id,
		null,
		initChat,
		{
			nick: user_nick,
			type: user_type
		}
	);
	
	var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
		// Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
	var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
	var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
		// At least Safari 3+: "[object HTMLElementConstructor]"
	var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
	var isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6
	
	if(notify.permissionLevel() == 'default' && !isIE)
		$.blockUI({
			message: '\
					<div class="container-fluid" style="margin-bottom: 2%;">\
						<div class="row">\
							<div class="col-sm-12">\
								<h4>Desktop Notifications <sup><strong class="text-danger">New!</strong></sup></h2>\
							</div>\
						</div>\
						<div class="row">\
							<div class="col-sm-12">\
								<p></p>\
								<p>After clicking the <em>Enable</em> button below, please select <em>' + (isChrome ? 'Allow' : 'Always Show Notifications') + '</em> inside the prompt.</p>\
							</div>\
						</div>\
						<div class="row">\
							<div class="col-sm-12">\
								<p><button class="btn btn-success" id="enable-alerts">Enable</button></p>\
								<p><img src="<?=base_url();?>media/images/chat/' + (isChrome ? 'enable2' : 'enable') + '.jpg" width="100%" /></p>\
							</div>\
						</div>\
					</div>\
				',
			css: {
					width: '80%',
					left: '10%',
					top: '10%',
					cursor: 'auto'
				},
			overlayCSS: {
					cursor: 'auto'
				}
		});
	
	$(document).on('click', 'button#enable-alerts', function (e) {
		
		e.preventDefault();
		
		notify.requestPermission(
			function () {
			
				if(notify.permissionLevel() == notify.PERMISSION_GRANTED) {
				
					alert('Desktop Notifications has been enabled.\n\nAlerts will show everytime this window is open but not in use.');
					
					$.unblockUI();
				}
				else if(notify.permissionLevel() == notify.PERMISSION_DENIED) {
					
					alert('Desktop Notifications has been disabled.\n\nPlease send request to Programmers if you need to enable it again.');
					
					$.unblockUI();
				}
				
			}
		);
		
	});
	
	$('div#sfx-player').jPlayer({
		ready: function() {
		
				$(this).jPlayer('setMedia', {
					mp3: base_url + 'media/sfx/notify.wav'
				});
			},
		swfPath: base_url + 'media/swf/chat'
	});
});
</script>
</body>
</html>