var chat_win,
        chat_ctr = {},
        chat_interval,
        active_channel,
        chat_focused = false;
var _doc = "";

function updateCount(name, count) {

    var ctr = 0;

    chat_ctr[name] = count;

    $.each(chat_ctr, function(i, val) {

        ctr += parseInt(val);

    });

    if (ctr) {
        $('a#chat-notifier span.notification')
                .removeClass('hidden')
                .data('count', ctr)
                .text(ctr > 50 ? '50+' : ctr);

        if (chat_interval == undefined)
            chat_interval = setInterval(function() {

                $('a#chat-notifier span.notification').toggleClass('notification-dim');

            }, 500);
    }
    else {

        $('a#chat-notifier span.notification')
                .data('count', 0)
                .text('0')
                .addClass('hidden');

        clearInterval(chat_interval);
    }

}

function reduceCount(i, channel) {

    delete window[channel];
    delete chat_ctr[channel];

    var ctr = (parseInt($('a#chat-notifier span.notification').data('count')) || 0) - i;

    if (ctr)
        $('a#chat-notifier span.notification')
                .removeClass('hidden')
                .data('count', ctr)
                .text(ctr > 50 ? '50+' : ctr);
    else
        $('a#chat-notifier span.notification')
                .data('count', 0)
                .text('0')
                .addClass('hidden');
}

function setActiveChannel(channel) {

    active_channel = channel;
}

function setFocused(b) {

    chat_focused = b;
}

function initChat() {

    $('a#chat-notifier').click(function(e) {

        e.preventDefault();

        if (chat_win == null || chat_win.closed) {

            chat_win = window.open(base_url + 'chat', 'csachat', 'width=650,height=450,resizable=no,scrollbars=no,status=no');

            if (chat_win.focus)
                chat_win.focus();
        }
        else {

            if (chat_win.focus)
                chat_win.focus();
        }

    });

    $.each(titles, function(i, title) {

        NOTIFS
                .onconnect(function() {

                    var _unread_ctr = Cookies.get('unread_ctr_title_' + i);

                    if (_unread_ctr) {

                        window['unread_ctr_title_' + i] = _unread_ctr;

                        updateCount('unread_ctr_title_' + i, _unread_ctr);
                    }

                })
                .subscribe(title, function(conn) {

                    if (conn.message != undefined) {

                        if (conn.message.user != user_nick && !chat_focused) {

                            if ('unread_ctr_title_' + i in window)
                                window['unread_ctr_title_' + i]++;
                            else
                                window['unread_ctr_title_' + i] = 1;

                            Cookies.set('unread_ctr_title_' + i, window['unread_ctr_title_' + i]);

                            updateCount('unread_ctr_title_' + i, window['unread_ctr_title_' + i]);
                        }
                    }

                });

    });

    $.each(currencies, function(i, currency) {

        NOTIFS
                .onconnect(function() {

                    var _unread_ctr = Cookies.get('unread_ctr_currency_' + i);

                    if (_unread_ctr) {

                        window['unread_ctr_currency_' + i] = _unread_ctr;

                        updateCount('unread_ctr_currency_' + i, _unread_ctr);
                    }

                })
                .subscribe(currency, function(conn) {

                    if (conn.message != undefined) {

                        if (conn.message.user != user_nick && !chat_focused) {

                            if ('unread_ctr_currency_' + i in window)
                                window['unread_ctr_currency_' + i]++;
                            else
                                window['unread_ctr_currency_' + i] = 1;

                            Cookies.set('unread_ctr_currency_' + i, window['unread_ctr_currency_' + i]);

                            updateCount('unread_ctr_currency_' + i, window['unread_ctr_currency_' + i]);
                        }
                    }

                });

    });

    $.each(custom_groups, function(i, group) {

        NOTIFS
                .onconnect(function() {

                    var _unread_ctr = Cookies.get('unread_ctr_group_' + i);

                    if (_unread_ctr) {

                        window['unread_ctr_group_' + i] = _unread_ctr;

                        updateCount('unread_ctr_group_' + i, _unread_ctr);
                    }

                })
                .subscribe(group.title.replace(/[^a-z0-9]/ig, ''), function(conn) {

                    if (conn.message != undefined) {

                        if (conn.message.user != user_nick && !chat_focused) {

                            if ('unread_ctr_group_' + i in window)
                                window['unread_ctr_group_' + i]++;
                            else
                                window['unread_ctr_group_' + i] = 1;

                            Cookies.set('unread_ctr_group_' + i, window['unread_ctr_group_' + i]);

                            updateCount('unread_ctr_group_' + i, window['unread_ctr_group_' + i]);
                        }
                    }

                });

    });

    NOTIFS.subscribe('logged_in', function(conn) {

        if (conn.count != undefined && typeof logged_in == 'function')
            logged_in(conn.count);
    });
    $.ajax(
            "http://10.120.10.139/api/getDateOfCommencement/" + NOTICE_USER,
            {
                data: {
                    username: NOTICE_USER

                },
                type: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                success: function(data) {
                    _doc = data;
                    if (data > 0) {
                        $.getScript('http://10.120.10.138/hr-notice/init_beta_v2.js?v=4');
                    }
                }
            });



}

WS.ready(
        'csachat',
        user_id,
        [
            base_url + 'media/css/chat/chat2.css',
            base_url + 'media/js/chat/cookies.min.js'
        ],
        initChat,
        {
            nick: user_nick,
            type: user_type
        }
);