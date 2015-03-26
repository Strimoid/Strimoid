function NotificationsModule() {
    var nm = this;

    this.unreadNotifications = parseInt($('.notifications').attr('data-new-notifications'), 10);
    this.pageTitle = document.title;

    if (window.settings && window.settings.notifications_sound)
        $.ionSound({ path: '/static/sounds/', sounds: [ 'notification' ] });

    if (window.settings && window.settings.notifications && window.settings.notifications.auto_read) {
        $('.notifications_dropdown').on('show.bs.dropdown', function() {
            $.post('/ajax/notification/mark_all_read', {}, function(data){
                nm.changeUnreadCount(0);
            });
        });

        $('.notifications_dropdown').on('hidden.bs.dropdown', function() {
            $('.notifications_dropdown a.new').removeClass('new');
        });
    }

    $('.notifications_dropdown .mark_as_read_link').click(function(){
        $.post('/ajax/notification/mark_all_read', {}, function(){
            nm.markAllAsRead();
        });

        return false;
    });

    $('.notifications_scroll').each(function() {
        Ps.initialize(this);
    });

    if (this.unreadNotifications > 0) {
        this.updatePageTitle();
    }
}

NotificationsModule.prototype.onNotificationReceived = function(notification)
{
    if (notification.type == 'notification_read') {
        this.markAsRead(notification.tag);
        return;
    }

    if (notification.type == 'notification_read_all') {
        this.markAllAsRead();
        return;
    }

    this.incrementUnreadCount();

    this.showNotification(notification);
    this.renderNotification(notification);
};

NotificationsModule.prototype.markAllAsRead = function()
{
    $('.notifications_dropdown a.new').removeClass('new');
    this.changeUnreadCount(0);
};

NotificationsModule.prototype.markAsRead = function(id)
{
    var notification = $('.notifications_list a[data-id='+ id +']');

    if (notification.length) {
        this.decrementUnreadCount();
        $(notification).removeClass('new');
    }
};

NotificationsModule.prototype.renderNotification = function(notification)
{
    var html = '<a href="'+ notification.url +'" class="new" data-id="'+ notification.tag +'"><img src="'+ notification.img +'" class="pull-left"><div class="media-body">'+ notification.title;
    html += '<br><small class="pull-left">'+ notification.type +'</small><small class="pull-right">';
    html += '<time pubdate title="'+ notification.time +'">chwilę temu</time></small></div><div class="clearfix"></div></a>';

    $('.notifications_list').prepend(html);
};

NotificationsModule.prototype.showNotification = function(data)
{
    if (!("Notification" in window))
        return false;

    var notification = new Notification(data.type, {
        body: data.title,
        tag: data.tag,
        icon: data.img
    });

    notification.onshow = function() {
        setTimeout(function() { notification.close(); }, 10000);

        if (window.settings.notifications_sound)
            $.ionSound.play('notification');
    };

    notification.onclick = function() {
        window.location.href = data.url;
        window.focus();
    };
};

NotificationsModule.prototype.updatePageTitle = function()
{
    if (this.unreadNotifications > 0) {
        document.title = '(' + this.unreadNotifications + ') ' + this.pageTitle;
    } else {
        document.title = this.pageTitle;
    }
};

NotificationsModule.prototype.updateIcon = function()
{
    if (this.unreadNotifications > 0) {
        $('.notifications_dropdown .notifications_icon').addClass('notifications_icon_new');
        $('.notifications_dropdown .badge').text(this.unreadNotifications).show();
    } else {
        $('.notifications_dropdown .notifications_icon').removeClass('notifications_icon_new');
        $('.notifications_dropdown .badge').text(this.unreadNotifications).hide();
    }
};

NotificationsModule.prototype.changeUnreadCount = function(newValue)
{
    this.unreadNotifications = newValue;

    this.updatePageTitle();
    this.updateIcon();
};

NotificationsModule.prototype.incrementUnreadCount = function()
{
    this.changeUnreadCount(this.unreadNotifications + 1);
};

NotificationsModule.prototype.decrementUnreadCount = function()
{
    this.changeUnreadCount(this.unreadNotifications - 1);
};
