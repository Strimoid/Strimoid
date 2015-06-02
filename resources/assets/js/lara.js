if (typeof String.prototype.endsWith !== 'function') {
    String.prototype.endsWith = function(suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };
}

if (typeof String.prototype.contains !== 'function') {
    String.prototype.contains = function(it) {
        return this.indexOf(it) != -1;
    };
}

var originalLeave = $.fn.popover.Constructor.prototype.leave;
$.fn.popover.Constructor.prototype.leave = function(obj){
    var self = obj instanceof this.constructor ?
        obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type);
    var container, timeout;

    originalLeave.call(this, obj);

    if(obj.currentTarget) {
        container = $(obj.currentTarget).siblings('.popover');
        timeout = self.timeout;
        container.one('mouseenter', function(){
            //We entered the actual popover – call off the dogs
            clearTimeout(timeout);
            //Let's monitor popover content instead
            container.one('mouseleave', function(){
                $.fn.popover.Constructor.prototype.leave.call(self, self);
            });
        })
    }
};

$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-XSRF-TOKEN': $.cookie('XSRF-TOKEN') }
    });

    var notificationsModule = new NotificationsModule();
    var votesModule = new VotesModule();
    var foldersModule = new FoldersModule();
    var usersModule = new UsersModule();
    var groupsModule = new GroupsModule();
    var contentsModule = new ContentsModule();
    var commentsModule = new CommentsModule();
    var entriesModule = new EntriesModule();
    var pollsModule = new PollsModule();

    if (window.username && window.WebSocket) {
        var pusher = new Pusher('a77666a1f2baa8903d8b');

        pusher.subscribe('private-u-' + window.username).bind('new-notification', function(data) {
            notificationsModule.onNotificationReceived(data);
        });

        var thumbnail = $('.img-thumbnail.refreshing');

        if (window.content_id && thumbnail.length) {
            pusher.subscribe('content-' + window.content_id).bind('loaded-thumbnail', function(data) {
                var parent = thumbnail.first().parent();
                thumbnail.remove();
                $(parent).append('<img class="media-object img-thumbnail" src="'+ data.url +'">');
            });
        }

        if (window.document.location.pathname.endsWith('/entries') && $.query.get('page') <= 1) {
            var template = Hogan.compile('<div class="panel-default entry" data-id="{{ id }}"><a name="{{ id }}"></a><div class="entry_avatar"><img src="{{ avatar }}" alt="{{ author }}"></div><div class="panel-heading entry_header"><a href="/u/{{ author }}" class="entry_author">{{{ author_color }}}</a><span class="pull-right"><span class="glyphicon glyphicon-tag"></span> <a href="/g/{{ group }}">g/{{ group }}</a><span class="glyphicon glyphicon-time"></span> <a href="/e/{{ id }}"><time pubdate title="{{ time }}">chwilę temu</time></a><span class="voting" data-id="{{ id }}" data-state="none" data-type="entry"><button type="button" class="btn btn-default btn-xs vote-btn-up"><span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">0</span></button><button type="button" class="btn btn-default btn-xs vote-btn-down"><span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">0</span></button></span></span></div><div class="entry_text md">{{{ text }}}</div><div class="entry_actions pull-right"><a class="entry_reply_link action_link">odpowiedz</a></div></div>');
            pusher.subscribe('entries').bind('new-entry', function(data) {
                if (window.blocked_users.indexOf(data.author) != -1 || window.blocked_groups.indexOf(data.group) != -1)
                    return;
                if (window.group) {
                    if (window.group == 'subscribed' && window.subscribed_groups.indexOf(data.group) == -1)
                        return;
                    else if (window.group == 'moderated' && window.moderated_groups.indexOf(data.group) == -1)
                        return;
                    else if (window.group != data.group)
                        return;
                }
                $(template.render(data)).hide().fadeIn(1000).insertBefore($('.entry').eq(1));
            });
        }
    }

    $('.groupbar .dropdown').each(function(index) {
        var menu = $(this).find('.dropdown-menu');
        var menuElements = $(menu).children();
        var columns = Math.min(Math.max(Math.round(menuElements.length/10), 1), 4).toString();
        var offset = $(this).offset();

        $(menu).css({
            '-moz-column-count': columns,
            '-webkit-column-count': columns,
            'column-count': columns,
            'left': offset.left + 'px'
        });
    });

    $('.content_preview_link').click(function(){
        var content = $(this).closest('.content');
        var content_id = $(content).attr('data-id');

        // Prevent loading preview multiple times
        if ($(content).data('preview-loading'))
            return;

        if ($(content).find('.content_preview').length == 0) {
            $(content).data('preview-loading', true);

            $.get('/ajax/content/'+ content_id +'/embed', function(data){
                $(content).append('<div class="content_preview">'+ data.code +'</div>');
                $(content).data('preview-loading', false);

                $(content).find('.content_preview img').click(function() {
                    $(content).find('.content_preview').remove();

                    return false;
                });
            });
        } else {
            $(content).find('.content_preview').remove();
        }
    });

    $('.content_remove_btn').click(function(){
        var content_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć wybraną treść?", function(result) {
            if (result)
                $.post('/ajax/content/remove', { id: content_id }, function(data){
                    if (data.status == 'ok')
                        window.location.href = "/";
                });
        });
    });

    $('.content_remove_link').click(function(){
        var content = $(this).closest('.content');
        var content_id = $(content).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć wybraną treść?", function(result) {
            if (result)
                $.post('/ajax/mod/content/remove', { id: content_id }, function(data){
                    if (data.status == 'ok')
                        $(content).fadeOut();
                });
        });
    });

    $('.related_remove_link').click(function(){
        var related = $(this).parent().parent().parent();
        var related_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć powiązany link?", function(result) {
            if (result)
                $.post('/ajax/related/remove', { id: related_id }, function(data){
                    if (data.status == 'ok')
                        $(related).fadeOut();
                });
        });
    });

    $('.ban_remove_btn').click(function(){
        var ban = $(this).parent().parent();
        var ban_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz odbanować wybranego użytkownika?", function(result) {
            if (result)
                $.post('/groups/unban', { id: ban_id }, function(data){
                    if (data.status == 'ok')
                        $(ban).fadeOut();
                });
        });
    });

    $('.moderator_remove_btn').click(function(){
        var mod = $(this).parent().parent();
        var mod_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć wybranego moderatora?", function(result) {
            if (result)
                $.post('/groups/remove_moderator', { id: mod_id }, function(data){
                    if (data.status == 'ok')
                        $(mod).fadeOut();
                });
        });
    });

    $('.folder_publish').click(function() {
        var button = $(this);
        var id = $(this).parent().attr('data-id');

        if (button.hasClass('btn-success')) {
            $.post('/ajax/folder/edit', { folder: id, public: 'false' }, function(data){
                if (data.status == 'ok') {
                    $(button).removeClass('btn-success');
                    $(button).addClass('btn-default');
                }

            });
        } else {
            $.post('/ajax/folder/edit', { folder: id, public: 'true' }, function(data){
                if (data.status == 'ok') {
                    $(button).removeClass('btn-default');
                    $(button).addClass('btn-success');
                }
            });
        }
    });

    $('body').on('click', 'a.entry_reply_link', function() {
        var link = $(this);
        var entry = $(this).parent().parent();
        var author = $(entry).find('.entry_author').text();

        if (entry.hasClass('entry_reply'))
            var parent = $(entry).prevAll(".entry:not(.entry_reply)").first();
        else
            var parent = $(entry);

        var existingField = $(parent).nextUntil(".entry:not(.entry_reply)").find("textarea.reply");

        if (existingField.length)  {
            $(existingField).focus().val($(existingField).val() + '@'+ author +': ');
            return;
        }

        var entry_id = $(parent).attr('data-id');

        $(entry).after('<div class="entry entry_reply"><form role="form" action="/entry/'+ entry_id +'/reply" method="POST" accept-charset="UTF-8" class="enter_send entry_add_reply"><input type="hidden" name="id" value="'+ entry_id +'"><div class="form-group"><textarea name="text" class="form-control reply" rows="3"></textarea></div><div class="btn-group pull-right"><button type="submit" class="btn btn-sm btn-primary">Wyślij</button><button type="button" class="btn btn-sm entry_reply_close">Anuluj</button></div></form></div><div class="clearfix"></div>');

        $(entry).next().find('textarea').focus().val('@'+ author +': ');

        // Needed to replace replies after sending reply using ajax
        $(entry).find('form').data('entry-parent', parent);

        $('.entry_reply_close').click(function() {
            var el = $(this);
            var text = (el).closest('.entry').find('textarea').val();
            var isEmpty = /^@([\w]+):?(\W*)$/;

            // Close without confirmation if user didn't wrote anything
            if (!text || isEmpty.test(text)) {
                $(el).closest('.entry').remove();
                $(link).show();

                return;
            }

            bootbox.confirm("Na pewno chcesz zamknąć pole odpowiedzi?", function(result) {
                if (result) {
                    $(el).closest('.entry').remove();
                    $(link).show();
                }
            });
        });
    });

    $('body').on('click', 'a.entry_remove_link', function() {
        var entry = $(this).parent().parent();
        var entry_id = $(entry).attr('data-id');
        var type = (entry.hasClass('entry_reply') == true ? 'entry_reply' : 'entry');

        bootbox.confirm("Na pewno chcesz usunąć wybrany wpis?", function(result) {
            if (result)
                $.post('/ajax/entry/remove', { id: entry_id, type: type }, function(data){
                    if (data.status == 'ok') {
                        if (type == 'entry')
                            $(entry).nextUntil(".entry:not(.entry_reply)").andSelf().remove();
                        else
                            $(entry).remove();
                    }
                });
        });
    });

    $('body').on('click', '.entry .show_blocked_link', function() {
        $(this).parent().parent().find('.entry_text.blocked').removeClass('blocked');
        $(this).remove();
    });

    $('body').on('click', '.comment .show_blocked_link', function() {
        $(this).parent().parent().find('.comment_text.blocked').removeClass('blocked');
        $(this).remove();
    });

    $('body').on('click', 'a.show_spoiler', function() {
        $(this).next().show();
        $(this).remove();
    });

    $('body').on('click', 'a.image', function() {
        var children = $(this).children('img').first();

        if (children.length)
            $(children).first().toggle();
        else
            $(this).append('<img src="'+ $(this).attr('href') +'">');

        return false;
    });

    function findYTVideos()
    {
        $('.md a[href*="youtube.com"]').each(function()
        {
            var url = $(this).attr('href');
            var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:[^#]+)?(?:#)?(?:t=(\d+))?(?:\S+)?$/i;

            var found = url.match(regex);

            if (found)
                $(this).addClass('yt-video').data('yt-id', found[1]).data('yt-time', found[2]);
        });
    }

    findYTVideos();

    $('body').on('click', 'a.yt-video', function() {
        var id = $(this).data('yt-id');
        var next = $(this).next();

        if (next.length && $(next).hasClass('yt-embed'))
            $(next).remove();
        else {
            $(this).after('<video-yt class="yt-embed" style="width: 480px; height: 360px;" vid="'+ id +'"></video-yt>');
            riot.mount('video-yt');
        }


        return false;
    });

    $('.entry_expand_replies').click(function() {
        var el = $(this);
        var entry_id = $(this).attr('data-id');

        $.get('/ajax/entry/'+ entry_id +'/replies', function(data){
            $(el).nextUntil('.entry:not(.entry_reply)').remove();
            $(el).after(data);
            $(el).remove();
        });

        $(this).unbind('click');

        findYTVideos();
    });

    $('body').on('click', '.comment_reply_link', function() {
        var link = $(this);
        var comment = $(this).parent().parent();
        var author = $(comment).find('.comment_author').text().trim();

        if (comment.hasClass('comment_reply'))
            var parent = $(comment).prevAll(".comment:not(.comment_reply)").first();
        else
            var parent = $(comment);

        var existingField = $(parent).nextUntil(".comment:not(.comment_reply)").find("textarea.reply");

        if (existingField.length)  {
            $(existingField).focus().val($(existingField).val() + '@'+ author +': ');
            return;
        }

        var comment_id = $(parent).attr('data-id');

        $(comment).after('<div class="comment comment_reply"><form role="form" action="/comment/'+ comment_id +'/reply" method="POST" accept-charset="UTF-8" class="enter_send comment_add_reply"><input type="hidden" name="id" value="'+ comment_id +'"><div class="form-group"><textarea name="text" class="form-control reply" rows="3"></textarea></div><div class="btn-group pull-right"><button type="submit" class="btn btn-sm btn-primary">Wyślij</button><button type="button" class="btn btn-sm comment_reply_close">Anuluj</button></div></form></div><div class="clearfix"></div>');

        $(comment).next().find('textarea').focus().val('@'+ author +': ');

        $('.comment_reply_close').click(function() {
            var el = $(this);
            var text = (el).closest('.comment').find('textarea').val();
            var isEmpty = /^@([\w]+):?(\W*)$/;

            // Close without confirmation if user didn't wrote anything
            if (!text || isEmpty.test(text)) {
                $(el).closest('.comment').remove();
                $(link).show();

                return;
            }

            bootbox.confirm("Na pewno chcesz zamknąć pole odpowiedzi?", function(result) {
                if (result) {
                    $(el).closest('.comment').remove();
                    $(link).show();
                }
            });
        });

        $(link).hide();
    });

    $('body').on('click', '.comment_edit_link', function() {
        var comment = $(this).parent().parent();
        var comment_id = $(comment).attr('data-id');
        var comment_old_text = $(comment).find('.comment_text').html();
        var type = (comment.hasClass('comment_reply') == true ? 'comment_reply' : 'comment');

        $.post('/ajax/comment/source', { id: comment_id, type: type}, function(data){
            if (data.status == 'ok') {
                $(comment).find('.comment_text').html('<form role="form" accept-charset="UTF-8" class="enter_send"><input type="hidden" name="id" value="'+ comment_id +'"><div class="form-group"><textarea name="text" class="form-control" rows="3"></textarea></div><div class="btn-group pull-right"><button type="button" class="btn btn-sm btn-primary comment_edit_save">Zapisz</button><button type="button" class="btn btn-sm comment_edit_close">Anuluj</button></div><div class="clearfix"></div></form>');
                $(comment).find('textarea[name="text"]').val(data.source);
                $(comment).find('.comment_actions').hide();

                $('.comment_edit_save').click(function() {
                    var comment_new_text = $(comment).find('textarea[name="text"]').val();

                    $.post('/ajax/comment/edit', { id: comment_id, type: type, text: comment_new_text }, function(data){
                        if (data.status == 'ok') {
                            $(comment).find('.comment_text').html(data.parsed);
                            $(comment).find('.comment_actions').show();
                        }
                    });
                });

                $('.comment_edit_close').click(function() {
                    $(comment).find('.comment_text').html(comment_old_text);
                    $(comment).find('.comment_actions').show();
                });
            }
        });
    });

    $('body').on('click', '.comment_remove_link', function() {
        var comment = $(this).parent().parent();
        var comment_id = $(comment).attr('data-id');
        var type = (comment.hasClass('comment_reply') == true ? 'comment_reply' : 'comment');

        bootbox.confirm("Na pewno chcesz usunąć wybrany wpis?", function(result) {
            if (result)
                $.post('/ajax/comment/remove', { id: comment_id, type: type }, function(data){
                    if (data.status == 'ok'){
                        if (type == 'comment')
                            $(comment).nextUntil(".comment:not(.comment_reply)").andSelf().remove();
                        else
                            $(comment).remove();
                    }
                });
        });
    });

    $('.add_related_btn').click(function() {
        $('.related_add_form').toggle();
    });

    $('.toggle_night_mode').click(function() {
        if ($('body').hasClass('night')) {
            $.removeCookie('night_mode', { path: '/' });
            $('body').removeClass('night');
        } else {
            $.cookie('night_mode', 'on', { expires: 365, path: '/' });
            $('body').addClass('night');
        }
    });

    $('.content_sort a').click(function() {
        if ($(this).attr('data-sort'))
            window.location.search = jQuery.query.set('sort', $(this).attr('data-sort'));
        else
            window.location.search = jQuery.query.remove('sort');
    });

    $('.content_filter a').click(function() {
        if ($(this).attr('data-time'))
            window.location.search = jQuery.query.set('time', $(this).attr('data-time'));
        else
            window.location.search = jQuery.query.remove('time');
    });

    $('body').on('mouseup', '.entry_text', function() {
        var entry = $(this).parent();

        setTimeout(function(){
            var sel = window.getSelection();
            var link = $(entry).find('.quote_link');

            if (sel.rangeCount &&  !sel.isCollapsed && $.contains(entry[0], sel.getRangeAt(0).startContainer.parentNode)) {
                if (!link.length)
                    $(entry).find('.entry_actions').prepend('<a class="quote_link action_link">cytuj</a>');

                $(entry).find('.quote_link').data('text', sel.toString());
            } else {
                $(entry).find('.quote_link').remove();
            }
        }, 10);
    });

    $('body').on('mouseup', '.comment_text', function() {
        var comment = $(this).parent();

        setTimeout(function(){
            var sel = window.getSelection();
            var link = $(comment).find('.quote_link');

            if (sel.rangeCount &&  !sel.isCollapsed && $.contains(comment[0], sel.getRangeAt(0).startContainer.parentNode)) {
                if (!link.length)
                    $(comment).find('.comment_actions').prepend('<a class="quote_link action_link">cytuj</a>');

                $(comment).find('.quote_link').data('text', sel.toString());
            } else {
                $(comment).find('.quote_link').remove();
            }
        }, 10);
    });

    $('body').on('click', '.entry .quote_link', function(){
        var link = this;
        var entry = $(this).parents('.entry').first();
        var author = $(entry).find('.entry_author').text();

        if (entry.hasClass('entry_reply'))
            var parent = $(entry).prevAll(".entry:not(.entry_reply)").first();
        else
            var parent = $(entry);

        var field = $(parent).nextUntil('.entry:not(.entry_reply)').find('textarea.reply');

        if (!field.length) {
            $(entry).find('.entry_reply_link').click();
            var field = $(parent).nextUntil('.entry:not(.entry_reply)').find('textarea.reply');
        }

        if (!field.val().contains("@" + author))
            $(field).focus().val($(field).val().trim() + "\n\n@" + author);

        $(field).focus().val($(field).val().trim() + "\n> " + $(this).data('text') + "\n\n");
    });

    $('body').on('click', '.comment .quote_link', function(){
        var link = this;
        var comment = $(this).parents('.comment').first();
        var author = $(comment).find('.comment_author').text();

        if (comment.hasClass('comment_reply'))
            var parent = $(comment).prevAll(".comment:not(.comment_reply)").first();
        else
            var parent = $(comment);

        var field = $(parent).nextUntil('.comment:not(.comment_reply)').find('textarea.reply');

        if (!field.length) {
            $(comment).find('.comment_reply_link').click();
            var field = $(parent).nextUntil('.comment:not(.comment_reply)').find('textarea.reply');
        }

        if (!field.val().contains("@" + author))
            $(field).focus().val($(field).val().trim() + "\n\n@" + author);

        $(field).focus().val($(field).val().trim() + "\n> " + $(this).data('text') + "\n\n");
    });

    $('input[name="browser_notifications"]').click(function() {
        if (this.checked) {
            Notification.requestPermission(function (permission) {
                if (!('permission' in Notification))
                    Notification.permission = permission;

                if (permission === "granted")
                    $('input[name="browser_notifications"]').prop('checked', true );
            });
        }

        return false;
    });

    if (window.Notification && Notification.permission === "granted")
        $('input[name="browser_notifications"]').prop('checked', true );

    if (window.username) {
        var groups = new Bloodhound({
            datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: '/groups.json',
                filter: function(d) {
                    if (window.settings && window.settings.homepage_subscribed)
                        return _.filter(d, function(g){ return _.contains(window.subscribed_groups, g.value); })
                    else
                        return _.filter(d, function(g){ return !_.contains(window.blocked_groups, g.value); })
                }
            },
            sorter: function(a, b) {
                return b.contents - a.contents;
            }
        });

        groups.initialize();

        $('input.group_typeahead, .entry_add_form input[name="groupname"], .content_add_form input[name="groupname"]').typeahead(null, {
            name: 'groups',
            source: groups.ttAdapter(),
            templates: {
                suggestion: _.template('<img src="<%= avatar %>" class="avatar"><p><%= value %><span class="count">[<%= contents %>]</span></p>')
            }
        });

        var users = new Bloodhound({
            datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.value); },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: '/users.json',
                filter: function(d) {
                    if (window.blocked_users)
                        return _.filter(d, function(u){ return !_.contains(window.blocked_users, u.value); })
                    else
                        return d;
                }
            }
        });

        users.initialize();

        $('input.user_typeahead').typeahead([
            {
                name: 'users',
                source: users.ttAdapter(),
                templates: {
                    suggestion: _.template('<img src="<%= avatar %>" class="avatar"><p><%= value %></p>')
                }
            }
        ]);
    }

    $('.content_add_form a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('input[name="type"]').val($(e.target).attr('href').substring(1));
    });

    if ($('link[data-id="group_style"]').length)
    $(document).keypress(function (e) {
        if (e.which === 83 && e.shiftKey && !$(e.target).is('input, textarea')) {
            e.preventDefault();
            $('link[data-id="group_style"]').remove();
        }
    });

    $('body').on('keypress', 'form.enter_send', function (e) {
        if (e.which === 13 && !e.shiftKey && window.settings && window.settings.enter_send) {
            e.preventDefault();
            $(this).submit();
        }
    });

    $('.dropdown-menu input, .dropdown-menu label').click(function(e) {
        e.stopPropagation();
    });

    $('.has_tooltip').tooltip();

    $('select.image-picker').imagepicker();

    $('input.tags').tagsinput();

    bootbox.setDefaults({ locale: "pl" });

    if ($('textarea.md_editor').length) {
        var textarea = $('textarea.md_editor')[0];

        var editor = new Editor();
        editor.render(textarea);
    }

    if ($('textarea.css_editor').length) {
        var textarea = $('textarea.css_editor')[0];

        var editor = CodeMirror.fromTextArea(textarea, {
            mode: 'text/css',
            lineNumbers: true
        });

        $('a[href=#style]').on('shown.bs.tab', function (e) {
            editor.refresh();
            editor.refresh();
        });
    }

    if (document.location.hash) {
        var id = document.location.hash.substr(1);
        var highlighted = $('div[data-id='+ id +']');

        if (highlighted.length)
            $(highlighted).addClass('highlighted');
    }

    if (document.location.hash && $('.nav-tabs a').length) {
        $('.nav-tabs a[href='+ document.location.hash +']').tab('show');
    }

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    if ($('.conversation_messages').length) {
        $('.conversation_messages').scrollTop($('.conversation_messages').prop('scrollHeight'));
    }
});
