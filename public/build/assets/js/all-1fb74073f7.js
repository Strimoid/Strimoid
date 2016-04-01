/*
 * 	Character Count Plugin - jQuery plugin
 * 	Dynamic character count for text areas and input fields
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/7161/jquery-plugin-simplest-twitterlike-dynamic-character-count-for-textareas
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */

'use strict';

(function ($) {

    $.fn.charCount = function (options) {

        // default configuration properties
        var defaults = {
            allowed: 140,
            warning: 25,
            css: 'counter',
            counterElement: 'span',
            cssWarning: 'warning',
            cssExceeded: 'exceeded',
            counterText: ''
        };

        var options = $.extend(defaults, options);

        function calculate(obj) {
            var count = $(obj).val().length;
            var available = options.allowed - count;
            if (available <= options.warning && available >= 0) {
                $(obj).next().addClass(options.cssWarning);
            } else {
                $(obj).next().removeClass(options.cssWarning);
            }
            if (available < 0) {
                $(obj).next().addClass(options.cssExceeded);
            } else {
                $(obj).next().removeClass(options.cssExceeded);
            }
            $(obj).next().html(options.counterText + available);
        };

        this.each(function () {
            $(this).after('<' + options.counterElement + ' class="' + options.css + '">' + options.counterText + '</' + options.counterElement + '>');
            calculate(this);
            $(this).keyup(function () {
                calculate(this);
            });
            $(this).change(function () {
                calculate(this);
            });
        });
    };
})(jQuery);

/**
 * jQuery.query - Query String Modification and Creation for jQuery
 * Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2009/8/13
 *
 * @author Blair Mitchelmore
 * @version 2.1.7
 *
 **/
new function (settings) {
    // Various Settings
    var $separator = settings.separator || '&';
    var $spaces = settings.spaces === false ? false : true;
    var $suffix = settings.suffix === false ? '' : '[]';
    var $prefix = settings.prefix === false ? false : true;
    var $hash = $prefix ? settings.hash === true ? "#" : "?" : "";
    var $numbers = settings.numbers === false ? false : true;

    jQuery.query = new function () {
        var is = function is(o, t) {
            return o != undefined && o !== null && (!!t ? o.constructor == t : true);
        };
        var parse = function parse(path) {
            var m,
                rx = /\[([^[]*)\]/g,
                match = /^([^[]+)(\[.*\])?$/.exec(path),
                base = match[1],
                tokens = [];
            while (m = rx.exec(match[2])) tokens.push(m[1]);
            return [base, tokens];
        };
        var set = function set(target, tokens, value) {
            var o,
                token = tokens.shift();
            if (typeof target != 'object') target = null;
            if (token === "") {
                if (!target) target = [];
                if (is(target, Array)) {
                    target.push(tokens.length == 0 ? value : set(null, tokens.slice(0), value));
                } else if (is(target, Object)) {
                    var i = 0;
                    while (target[i++] != null);
                    target[--i] = tokens.length == 0 ? value : set(target[i], tokens.slice(0), value);
                } else {
                    target = [];
                    target.push(tokens.length == 0 ? value : set(null, tokens.slice(0), value));
                }
            } else if (token && token.match(/^\s*[0-9]+\s*$/)) {
                var index = parseInt(token, 10);
                if (!target) target = [];
                target[index] = tokens.length == 0 ? value : set(target[index], tokens.slice(0), value);
            } else if (token) {
                var index = token.replace(/^\s*|\s*$/g, "");
                if (!target) target = {};
                if (is(target, Array)) {
                    var temp = {};
                    for (var i = 0; i < target.length; ++i) {
                        temp[i] = target[i];
                    }
                    target = temp;
                }
                target[index] = tokens.length == 0 ? value : set(target[index], tokens.slice(0), value);
            } else {
                return value;
            }
            return target;
        };

        var queryObject = function queryObject(a) {
            var self = this;
            self.keys = {};

            if (a.queryObject) {
                jQuery.each(a.get(), function (key, val) {
                    self.SET(key, val);
                });
            } else {
                jQuery.each(arguments, function () {
                    var q = "" + this;
                    q = q.replace(/^[?#]/, ''); // remove any leading ? || #
                    q = q.replace(/[;&]$/, ''); // remove any trailing & || ;
                    if ($spaces) q = q.replace(/[+]/g, ' '); // replace +'s with spaces

                    jQuery.each(q.split(/[&;]/), function () {
                        var key = decodeURIComponent(this.split('=')[0] || "");
                        var val = decodeURIComponent(this.split('=')[1] || "");

                        if (!key) return;

                        if ($numbers) {
                            if (/^[+-]?[0-9]+\.[0-9]*$/.test(val)) // simple float regex
                                val = parseFloat(val);else if (/^[+-]?[0-9]+$/.test(val)) // simple int regex
                                val = parseInt(val, 10);
                        }

                        val = !val && val !== 0 ? true : val;

                        if (val !== false && val !== true && typeof val != 'number') val = val;

                        self.SET(key, val);
                    });
                });
            }
            return self;
        };

        queryObject.prototype = {
            queryObject: true,
            has: function has(key, type) {
                var value = this.get(key);
                return is(value, type);
            },
            GET: function GET(key) {
                if (!is(key)) return this.keys;
                var parsed = parse(key),
                    base = parsed[0],
                    tokens = parsed[1];
                var target = this.keys[base];
                while (target != null && tokens.length != 0) {
                    target = target[tokens.shift()];
                }
                return typeof target == 'number' ? target : target || "";
            },
            get: function get(key) {
                var target = this.GET(key);
                if (is(target, Object)) return jQuery.extend(true, {}, target);else if (is(target, Array)) return target.slice(0);
                return target;
            },
            SET: function SET(key, val) {
                var value = !is(val) ? null : val;
                var parsed = parse(key),
                    base = parsed[0],
                    tokens = parsed[1];
                var target = this.keys[base];
                this.keys[base] = set(target, tokens.slice(0), value);
                return this;
            },
            set: function set(key, val) {
                return this.copy().SET(key, val);
            },
            REMOVE: function REMOVE(key) {
                return this.SET(key, null).COMPACT();
            },
            remove: function remove(key) {
                return this.copy().REMOVE(key);
            },
            EMPTY: function EMPTY() {
                var self = this;
                jQuery.each(self.keys, function (key, value) {
                    delete self.keys[key];
                });
                return self;
            },
            load: function load(url) {
                var hash = url.replace(/^.*?[#](.+?)(?:\?.+)?$/, "$1");
                var search = url.replace(/^.*?[?](.+?)(?:#.+)?$/, "$1");
                return new queryObject(url.length == search.length ? '' : search, url.length == hash.length ? '' : hash);
            },
            empty: function empty() {
                return this.copy().EMPTY();
            },
            copy: function copy() {
                return new queryObject(this);
            },
            COMPACT: function COMPACT() {
                function build(orig) {
                    var obj = typeof orig == "object" ? is(orig, Array) ? [] : {} : orig;
                    if (typeof orig == 'object') {
                        (function () {
                            var add = function add(o, key, value) {
                                if (is(o, Array)) o.push(value);else o[key] = value;
                            };

                            jQuery.each(orig, function (key, value) {
                                if (!is(value)) return true;
                                add(obj, key, build(value));
                            });
                        })();
                    }
                    return obj;
                }
                this.keys = build(this.keys);
                return this;
            },
            compact: function compact() {
                return this.copy().COMPACT();
            },
            toString: function toString() {
                var i = 0,
                    queryString = [],
                    chunks = [],
                    self = this;
                var encode = function encode(str) {
                    str = str + "";
                    if ($spaces) str = str.replace(/ /g, "+");
                    return encodeURIComponent(str);
                };
                var addFields = function addFields(arr, key, value) {
                    if (!is(value) || value === false) return;
                    var o = [encode(key)];
                    if (value !== true) {
                        o.push("=");
                        o.push(encode(value));
                    }
                    arr.push(o.join(""));
                };
                var build = function build(obj, base) {
                    var newKey = function newKey(key) {
                        return !base || base == "" ? [key].join("") : [base, "[", key, "]"].join("");
                    };
                    jQuery.each(obj, function (key, value) {
                        if (typeof value == 'object') build(value, newKey(key));else addFields(chunks, newKey(key), value);
                    });
                };

                build(this.keys);

                if (chunks.length > 0) queryString.push($hash);
                queryString.push(chunks.join($separator));

                return queryString.join("");
            }
        };

        return new queryObject(location.search, location.hash);
    }();
}(jQuery.query || {}); // Pass in jQuery.query as settings object

function CommentsModule() {

    if (window.username) {
        $('body').on('submit', 'form.comment_add', this.addComment);
        $('body').on('submit', 'form.comment_add_reply', this.addReply);
    }
}

CommentsModule.prototype.addComment = function (e) {
    var form = this;
    var id = $(form).find('input[name=id]').val();

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    var url = laroute.action('CommentController@addComment', { content: id });

    $.post(url, $(form).serialize(), function (data) {
        if (data.status == 'ok') {
            $(form).trigger('reset');

            $('.comments').append(data.comment);
            $('.comments p.no_comments').remove();
        } else {
            $(form).find('.form-group').last().addClass('has-error').append('<p class="help-block">' + data.error + '</p>');
        }
    });

    e.preventDefault();
};

CommentsModule.prototype.addReply = function (e) {
    var form = this;
    var parent = $(form).parent('.comment').prevAll('.comment:not(.comment_reply)').first();

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    $.post($(form).attr('action'), $(form).serialize(), function (data) {
        if (data.status == 'ok') {
            $(form).parent().remove();

            $(parent).nextUntil('.comment:not(.comment_reply)').remove();
            $(parent).after(data.replies);

            $('.md a[href*="youtube.com"]').each(function () {
                var url = $(this).attr('href');
                var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:[^#]+)?(?:#)?(?:t=(\d+))?(?:\S+)?$/i;

                var found = url.match(regex);

                if (found) $(this).addClass('yt-video').data('yt-id', found[1]).data('yt-time', found[2]);
            });
        } else {
            $(form).find('.form-group').last().addClass('has-error').append('<p class="help-block">' + data.error + '</p>');
        }
    });

    e.preventDefault();
};

function ContentsModule() {

    if (window.username) {
        $('span.save_content').click(this.saveContent);
        $('.content_add_form input[name="url"]').on('paste', this.findDuplicates);
        $('.content_add_form input[name="groupname"]').on('input', this.showSidebar).on('typeahead:selected', this.showSidebar);
    }
}

ContentsModule.prototype.saveContent = function (e) {
    var button = $(this);
    var content = $(this).parents('[data-id]').attr('data-id');

    if (button.hasClass('glyphicon-star')) {
        $.post('/ajax/content/remove_save', { content: content }, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('glyphicon-star');
                $(button).addClass('glyphicon-star-empty');
            }
        });
    } else {
        $.post('/ajax/content/add_save', { content: content }, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('glyphicon-star-empty');
                $(button).addClass('glyphicon-star');
            }
        });
    }
};

ContentsModule.prototype.findDuplicates = function () {
    var input = this;

    setTimeout(function () {
        var url = $('input[name="url"]').val();
        var group = $('input[name="groupname"]').val();

        $('.duplicate_info').remove();
        $(input).parent().append('<p class="help-block duplicate_info"><span class="glyphicon glyphicon-refresh spinner"></span> Ładowanie informacji...</p>');

        $.post('/ajax/utils/get_title', { url: url, group: group }, function (data) {
            $('.duplicate_info').remove();

            if (data.status == 'ok') {
                if (!$('input[name="title"]').val()) $('input[name="title"]').val(data.title);

                if (!$('textarea[name="description"]').val()) $('textarea[name="description"]').val(data.description);

                if (data.duplicates.length) {
                    var last = _.last(data.duplicates);

                    var template = _.template('<p class="help-block duplicate_info"><span class="glyphicon glyphicon-info-sign"></span> Link został już dodany do wybranej grupy:<br><a href="/c/<%= id %>"><%= title %></a></p>');
                    var html = template({ id: last._id, title: last.title });

                    $(input).parent().append(html);
                }
            }
        });
    }, 1);
};

ContentsModule.prototype.showSidebar = function () {
    window.clearTimeout($(this).data('timeout'));

    var groupName = $('.content_add_form input[name="groupname"]').val();

    $(this).data('timeout', setTimeout(function () {
        $.get('/ajax/group/' + groupName + '/sidebar', function (data) {
            $('.sidebar .well').html(data.sidebar);
        });
    }, 500));
};

function EntriesModule() {

    if (window.username) {
        // $('span.save_entry').click(this.saveEntry);
        $('body').delegate('span.save_entry', 'click', this.saveEntry);

        $('body').on('submit', 'form.entry_add', this.addEntry);
        $('body').on('submit', 'form.entry_add_reply', this.addReply);
        $('body').on('click', '.entry_edit_link', this.editEntry);
    }
}

EntriesModule.prototype.saveEntry = function (e) {
    var button = $(this);
    var entry = $(this).parents('[data-id]').attr('data-id');

    if (button.hasClass('glyphicon-star')) {
        $.post('/ajax/entry/remove_save', { entry: entry }, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('glyphicon-star');
                $(button).addClass('glyphicon-star-empty');
            }
        });
    } else {
        $.post('/ajax/entry/add_save', { entry: entry }, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('glyphicon-star-empty');
                $(button).addClass('glyphicon-star');
            }
        });
    }
};

EntriesModule.prototype.addEntry = function (e) {
    var form = this;

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    $.post('/ajax/entry/add', $(form).serialize(), function (data) {
        if (data.status == 'ok') {
            $(form).trigger('reset');
        } else {
            $(form).find('.form-group').last().addClass('has-error').append('<p class="help-block">' + data.error + '</p>');
        }
    });

    e.preventDefault();
};

EntriesModule.prototype.addReply = function (e) {
    var form = this;
    var parent = $(form).parent('.entry').prevAll('.entry:not(.entry_reply)').first();

    $(form).find('.form-group').removeClass('has-error');
    $(form).find('.help-block').remove();

    $.post($(form).attr('action'), $(form).serialize(), function (data) {
        if (data.status == 'ok') {
            $(form).parent().remove();

            $(parent).nextUntil('.entry:not(.entry_reply)').remove();
            $(parent).after(data.replies);

            $('.md a[href*="youtube.com"]').each(function () {
                var url = $(this).attr('href');
                var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:[^#]+)?(?:#)?(?:t=(\d+))?(?:\S+)?$/i;

                var found = url.match(regex);

                if (found) $(this).addClass('yt-video').data('yt-id', found[1]).data('yt-time', found[2]);
            });
        } else {
            $(form).find('.form-group').last().addClass('has-error');
            $(form).find('.form-group').last().append('<p class="help-block">' + data.error + '</p>');
        }
    });

    e.preventDefault();
};

EntriesModule.prototype.editEntry = function (e) {
    var entry = $(this).parent().parent();
    var entry_id = $(entry).attr('data-id');
    var entry_old_text = $(entry).find('.entry_text').html();
    var type = entry.hasClass('entry_reply') == true ? 'entry_reply' : 'entry';

    $.post('/ajax/entry/source', { id: entry_id, type: type }, function (data) {
        if (data.status == 'ok') {
            $(entry).find('.entry_text').html('<form role="form" accept-charset="UTF-8" class="enter_send entry_edit"><input type="hidden" name="id" value="' + entry_id + '"><input type="hidden" name="type" value="' + type + '"><div class="form-group"><textarea name="text" class="form-control" rows="3"></textarea></div><div class="btn-group pull-right"><button type="submit" class="btn btn-sm btn-primary">Zapisz</button><button type="button" class="btn btn-sm entry_edit_close">Anuluj</button></div><div class="clearfix"></div></form>');
            $(entry).find('textarea[name="text"]').val(data.source);
            $(entry).find('.entry_actions').hide();

            $(entry).find('form').submit(function (event) {
                $(entry).find('.form-group').removeClass('has-error');
                $(entry).find('.help-block').remove();

                $.post('/ajax/entry/edit', $(entry).find('form').serialize(), function (data) {
                    if (data.status == 'ok') {
                        $(entry).find('.entry_text').html(data.parsed);
                        $(entry).find('.entry_actions').show();
                    } else {
                        $(entry).find('.form-group').addClass('has-error');
                        $(entry).find('.form-group').append('<p class="help-block">' + data.error + '</p>');
                    }
                });

                event.preventDefault();
            });

            $('.entry_edit_close').click(function () {
                $(entry).find('.entry_text').html(entry_old_text);
                $(entry).find('.entry_actions').show();
            });
        }
    });
};

function FoldersModule() {

    if (window.username) {
        $('input.create_folder').keyup(this.createFolder);
        $('input.modify_folder').change(this.modifyFolder);
        $('button.folder_publish').click(this.publishFolder);
        $('button.folder_remove').click(this.removeFolder);
        $('form.folder_add_group').submit(this.addGroup);
        $('button.folder_remove_group').click(this.removeGroup);
    }
}

FoldersModule.prototype.createFolder = function (e) {
    // Return if key is not enter
    if (e.keyCode != 13) return;

    var input = this;
    var group = $(input).parents('.folder-menu').first().attr('data-group');

    $.post('/ajax/folder/create', { name: $(input).val(), groupname: group }, function (data) {
        if (data.status == 'ok') {
            var template = _.template('<li><label><input type="checkbox" data-id="<%= id %>" checked> <%= name %></label></li>');
            var html = template({ id: data.id, name: $(input).val() });

            $(html).insertBefore($(input).parents().eq(1).find('.divider'));
            $(input).val('');
        }
    });
};

FoldersModule.prototype.modifyFolder = function () {
    var input = this;
    var group = $(input).parents('.folder-menu').first().attr('data-group');

    if (this.checked) {
        $.post('/ajax/folder/add_group', { folder: $(this).attr('data-id'), group: group }, function (data) {
            if (data.status == 'ok') $(input).prop('disabled', false);
        });
    } else {
        $.post('/ajax/folder/remove_group', { folder: $(this).attr('data-id'), group: group }, function (data) {
            if (data.status == 'ok') $(input).prop('disabled', false);
        });
    }

    $(input).prop('disabled', true);
};

FoldersModule.prototype.publishFolder = function () {
    var button = $(this);
    var id = $(this).parent().attr('data-id');

    if (button.hasClass('btn-success')) {
        $.post('/ajax/folder/edit', { folder: id, 'public': 'false' }, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-success');
                $(button).addClass('btn-default');
            }
        });
    } else {
        $.post('/ajax/folder/edit', { folder: id, 'public': 'true' }, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-success');
            }
        });
    }
};

FoldersModule.prototype.removeFolder = function () {
    var id = $(this).parent().attr('data-id');

    bootbox.confirm("Na pewno chcesz usunąć wybrany folder?", function (result) {
        if (result) $.post('/ajax/folder/remove', { folder: id }, function (data) {
            if (data.status == 'ok') window.location.href = "/";
        });
    });
};

FoldersModule.prototype.addGroup = function () {
    var form = $(this);
    var folder = $(this).parent().parent().attr('data-folder');
    var group = $(this).find('input[name="group"]').val();

    if (!group) return false;

    $.post('/ajax/folder/add_group', { folder: folder, group: group }, function (data) {
        if (data.status == 'ok') {
            var template = _.template('<li class="list-group-item" style="padding: 5px 15px" ><a href="/g/<%= group %>"><%= group %></a><button type="button" class="btn btn-xs btn-danger folder_remove_group pull-right" data-group="<%= group %>"><span class="glyphicon glyphicon-trash"></span></button></li>');
            var html = template({ group: group });

            var lastChild = $('.folder_groups').children().last();

            $(html).insertBefore(lastChild);

            $(form)[0].reset();
        }
    });

    return false;
};

FoldersModule.prototype.removeGroup = function () {
    var row = $(this).parent();
    var folder = $(this).parent().parent().attr('data-folder');
    var group = $(this).attr('data-group');

    $.post('/ajax/folder/remove_group', { folder: folder, group: group }, function (data) {
        if (data.status == 'ok') $(row).remove();
    });
};

function GroupsModule() {
    if (window.username) {
        $('body').on('click', 'button.group_subscribe_btn', this.subscribeGroup);
        $('body').on('click', 'button.group_block_btn', this.blockGroup);

        $('[data-hover=group_widget]').popover({
            html: true, placement: 'bottom', trigger: 'hover', delay: 500, content: this.renderActionsWidget
        });
    }
}

GroupsModule.prototype.subscribeGroup = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');
    var url = laroute.action('GroupController@subscribeGroup', { group: name });

    if (button.hasClass('btn-success')) {
        $['delete'](url, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-success');
                $(button).addClass('btn-default');
            }
        });
    } else {
        $.post(url, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-success');
            }
        });
    }
};

GroupsModule.prototype.blockGroup = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');
    var url = laroute.action('GroupController@blockGroup', { group: name });

    if (button.hasClass('btn-danger')) {
        $['delete'](url, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-danger');
                $(button).addClass('btn-default');
            }
        });
    } else {
        $.post(url, function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-danger');
            }
        });
    }
};

GroupsModule.prototype.renderActionsWidget = function () {
    var widget = $(this);
    var groupname = $(this).attr('data-group');

    groupname = groupname.replace(/^g\//, '');

    var template = _.template('<div class="btn-group" data-name="<%= groupname %>"><button class="group_subscribe_btn btn btn-sm <%= subscribe_class %>"><span class="glyphicon glyphicon-eye-open"></span></button><button class="group_block_btn btn btn-sm <%= block_class %>"><span class="glyphicon glyphicon-ban-circle"></span></button></div>');

    return template({
        groupname: groupname,
        subscribe_class: _.contains(window.subscribed_groups, groupname) ? 'btn-success' : 'btn-default',
        block_class: _.contains(window.blocked_groups, groupname) ? 'btn-danger' : 'btn-default'
    });
};

function NotificationsModule() {
    var nm = this;

    this.unreadNotifications = parseInt($('.notifications').attr('data-new-notifications'), 10);
    this.pageTitle = document.title;

    if (window.settings && window.settings.notifications_sound) $.ionSound({ path: '/static/sounds/', sounds: ['notification'] });

    if (window.settings && window.settings.notifications && window.settings.notifications.auto_read) {
        $('.notifications_dropdown').on('show.bs.dropdown', function () {
            $.post('/ajax/notification/mark_all_read', {}, function (data) {
                nm.changeUnreadCount(0);
            });
        });

        $('.notifications_dropdown').on('hidden.bs.dropdown', function () {
            $('.notifications_dropdown a.new').removeClass('new');
        });
    }

    $('.notifications_dropdown .mark_as_read_link').click(function () {
        $.post('/ajax/notification/mark_all_read', {}, function () {
            nm.markAllAsRead();
        });

        return false;
    });

    $('.notifications_scroll').each(function () {
        Ps.initialize(this);
    });

    if (this.unreadNotifications > 0) {
        this.updatePageTitle();
    }
}

NotificationsModule.prototype.onNotificationReceived = function (notification) {
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

NotificationsModule.prototype.markAllAsRead = function () {
    $('.notifications_dropdown a.new').removeClass('new');
    this.changeUnreadCount(0);
};

NotificationsModule.prototype.markAsRead = function (id) {
    var notification = $('.notifications_list a[data-id=' + id + ']');

    if (notification.length) {
        this.decrementUnreadCount();
        $(notification).removeClass('new');
    }
};

NotificationsModule.prototype.renderNotification = function (notification) {
    var html = '<a href="' + notification.url + '" class="new" data-id="' + notification.tag + '"><img src="' + notification.img + '" class="pull-left"><div class="media-body">' + notification.title;
    html += '<br><small class="pull-left">' + notification.type + '</small><small class="pull-right">';
    html += '<time pubdate title="' + notification.time + '">chwilę temu</time></small></div><div class="clearfix"></div></a>';

    $('.notifications_list').prepend(html);
};

NotificationsModule.prototype.showNotification = function (data) {
    if (!("Notification" in window)) return false;

    var notification = new Notification(data.type, {
        body: data.title,
        tag: data.tag,
        icon: data.img
    });

    notification.onshow = function () {
        setTimeout(function () {
            notification.close();
        }, 10000);

        if (window.settings.notifications_sound) $.ionSound.play('notification');
    };

    notification.onclick = function () {
        window.location.href = data.url;
        window.focus();
    };
};

NotificationsModule.prototype.updatePageTitle = function () {
    if (this.unreadNotifications > 0) {
        document.title = '(' + this.unreadNotifications + ') ' + this.pageTitle;
    } else {
        document.title = this.pageTitle;
    }
};

NotificationsModule.prototype.updateIcon = function () {
    if (this.unreadNotifications > 0) {
        $('.notifications_dropdown .notifications_icon').addClass('notifications_icon_new');
        $('.notifications_dropdown .badge').text(this.unreadNotifications).show();
        $('.notifications_dropdown .badge').removeClass('hide');
    } else {
        $('.notifications_dropdown .notifications_icon').removeClass('notifications_icon_new');
        $('.notifications_dropdown .badge').text(this.unreadNotifications).hide();
    }
};

NotificationsModule.prototype.changeUnreadCount = function (newValue) {
    this.unreadNotifications = newValue;

    this.updatePageTitle();
    this.updateIcon();
};

NotificationsModule.prototype.incrementUnreadCount = function () {
    this.changeUnreadCount(this.unreadNotifications + 1);
};

NotificationsModule.prototype.decrementUnreadCount = function () {
    this.changeUnreadCount(this.unreadNotifications - 1);
};

function PollsModule() {
    $('.poll input').click(this.updateOptions);
}

PollsModule.prototype.updateOptions = function (e) {
    var question = $(this).parents('.question');

    var checked = $(question).find('input:checked').length;
    var min = $(question).find('.options').attr('data-min');
    var max = $(question).find('.options').attr('data-max');

    $(question).find('.help').removeClass('error');

    if (checked >= max) {
        $(question).find('.help').text('Zaznaczyłeś maksymalną ilość odpowiedzi');
        $(question).find('input:not(:checked)').attr('disabled', 'disabled');
    } else if (checked < min) {
        $(question).find('.help').text('Musisz zaznaczyć jeszcze ' + (min - checked));
        $(question).find('input:not(:checked)').removeAttr('disabled');
    } else {
        $(question).find('.help').text('Możesz zaznaczyć jeszcze ' + (max - checked));
        $(question).find('input:not(:checked)').removeAttr('disabled');
    }
};

function UsersModule() {
    if (window.username) {
        $('body').on('click', 'button.user_observe_btn', this.observeUser);
        $('body').on('click', 'button.user_block_btn', this.blockUser);

        $('[data-hover=user_widget]').popover({
            html: true, placement: 'bottom', trigger: 'hover', delay: 500, content: this.renderActionsWidget
        });
    }
};

UsersModule.prototype.observeUser = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');

    if (button.hasClass('btn-success')) {
        $['delete']('/u/' + name + '/observe', function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-success');
                $(button).addClass('btn-default');

                window.observed_users = _.without(window.observed_users, name);
            }
        });
    } else {
        $.post('/u/' + name + '/observe', function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-success');

                window.observed_users.push(name);
            }
        });
    }
};

UsersModule.prototype.blockUser = function () {
    var button = $(this);
    var name = $(this).parent().attr('data-name');

    if (button.hasClass('btn-danger')) {
        $['delete']('/u/' + name + '/block', function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-danger');
                $(button).addClass('btn-default');

                window.blocked_users = _.without(window.blocked_users, name);
            }
        });
    } else {
        $.post('/u/' + name + '/block', function (data) {
            if (data.status == 'ok') {
                $(button).removeClass('btn-default');
                $(button).addClass('btn-danger');

                window.blocked_users.push(name);
            }
        });
    }
};

UsersModule.prototype.renderActionsWidget = function () {
    var widget = $(this);
    var username = $(this).attr('data-user');

    var template = _.template('<div class="btn-group" data-name="<%= username %>"><a href="/conversations/new/<%= username %>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-envelope"></span></a><button class="user_observe_btn btn btn-sm <%= observe_class %>"><span class="glyphicon glyphicon-eye-open"></span></button><button class="user_block_btn btn btn-sm <%= block_class %>"><span class="glyphicon glyphicon-ban-circle"></span></button></div>');

    return template({
        username: username,
        observe_class: _.contains(window.observed_users, username) ? 'btn-success' : 'btn-default',
        block_class: _.contains(window.blocked_users, username) ? 'btn-danger' : 'btn-default'
    });
};

function VotesModule() {

    if (window.username) {
        $('body').on('click', 'button.vote-btn-up', this.addUpvote).on('click', 'button.vote-btn-down', this.addDownvote);
    }

    $('body').popover({
        html: true,
        selector: 'button.vote-btn-up, button.vote-btn-down',
        trigger: 'hover',
        content: 'ładowanie...',
        placement: 'right',
        delay: 500
    });

    // fix context menu instead of popover for mobile devices:
    $('body').on('contextmenu', 'button.vote-btn-up, button.vote-btn-down', function (event) {
        return false;
    });

    $('body').on('show.bs.popover', 'button.vote-btn-up, button.vote-btn-down', function () {
        var button = this;
        var item = $(this).parent();
        var cid = $(item).attr('data-id');
        var type = $(item).attr('data-type');

        // Prevent loading votes multiple times
        if ($(button).data('loading')) return;

        $(button).data('loading', true);

        var filter;

        if ($(this).hasClass('vote-btn-up')) filter = 'up';else filter = 'down';

        $.post('/ajax/vote/get_voters', { id: cid, type: type, filter: filter }, function (data) {
            if (data.status == 'ok') {
                var content = '';

                data.voters.forEach(function (vote) {
                    content += '<div style="font-size: 11px">';

                    if (vote.up) content += '<span class="glyphicon glyphicon-arrow-up vote-up"></span>';else content += '<span class="glyphicon glyphicon-arrow-down vote-down"></span>';

                    content += ' <a href="/u/' + vote.username + '">' + vote.username + '</a> ';
                    content += '<span style="color: #929292" title="' + vote.time + '">(' + vote.time_ago + ')</span></div>';
                });

                var popover = $(button).data('bs.popover');

                if (content == '') popover.config.content = 'brak głosów';else popover.config.content = content;

                popover.config.animation = false;
                popover.show();
                popover.config.animation = true;
            }
        });
    });
}

VotesModule.prototype.addUpvote = function () {
    var content = $(this).parent();
    var cid = $(content).attr('data-id');
    var state = $(content).attr('data-state');
    var type = $(content).attr('data-type');

    if (state == 'uv') {
        $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
            if (data.status == 'ok') {
                $(content).find('.vote-btn-up').removeClass('btn-success');
                $(content).attr('data-state', 'none');
            }

            $(content).find('.vote-btn-up .count').text(data.uv);
            $(content).find('.vote-btn-down .count').text(data.dv);
        });
    } else if (state == 'dv') {
        $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
            if (data.status == 'ok') {
                $(content).find('.vote-btn-down').removeClass('btn-danger');
                $(content).attr('data-state', 'none');

                $.post('/ajax/vote/add', { id: cid, type: type, up: 'true' }, function (data) {
                    if (data.status == 'ok') {
                        $(content).find('.vote-btn-up').addClass('btn-success');
                        $(content).attr('data-state', 'uv');
                    }

                    $(content).find('.vote-btn-up .count').text(data.uv);
                    $(content).find('.vote-btn-down .count').text(data.dv);
                });
            }
        });
    } else if (state == 'none') {
        $.post('/ajax/vote/add', { id: cid, type: type, up: 'true' }, function (data) {
            if (data.status == 'ok') {
                $(content).find('.vote-btn-up').addClass('btn-success');
                $(content).attr('data-state', 'uv');
            }

            $(content).find('.vote-btn-up .count').text(data.uv);
            $(content).find('.vote-btn-down .count').text(data.dv);
        });
    }
};

VotesModule.prototype.addDownvote = function () {
    var content = $(this).parent();
    var cid = $(content).attr('data-id');
    var state = $(content).attr('data-state');
    var type = $(content).attr('data-type');

    if (state == 'uv') {
        $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
            if (data.status == 'ok') {
                $(content).find('.vote-btn-up').removeClass('btn-success');
                $(content).attr('data-state', 'none');

                $.post('/ajax/vote/add', { id: cid, type: type, up: 'false' }, function (data) {
                    if (data.status == 'ok') {
                        $(content).find('.vote-btn-down').addClass('btn-danger');
                        $(content).attr('data-state', 'dv');
                    }

                    $(content).find('.vote-btn-up .count').text(data.uv);
                    $(content).find('.vote-btn-down .count').text(data.dv);
                });
            }
        });
    } else if (state == 'dv') {
        $.post('/ajax/vote/remove', { id: cid, type: type }, function (data) {
            if (data.status == 'ok') {
                $(content).find('.vote-btn-down').removeClass('btn-danger');
                $(content).attr('data-state', 'none');
            }

            $(content).find('.vote-btn-up .count').text(data.uv);
            $(content).find('.vote-btn-down .count').text(data.dv);
        });
    } else if (state == 'none') {
        $.post('/ajax/vote/add', { id: cid, type: type, up: 'false' }, function (data) {
            if (data.status == 'ok') {
                $(content).find('.vote-btn-down').addClass('btn-danger');
                $(content).attr('data-state', 'dv');
            }

            $(content).find('.vote-btn-up .count').text(data.uv);
            $(content).find('.vote-btn-down .count').text(data.dv);
        });
    }
};

if (typeof String.prototype.endsWith !== 'function') {
    String.prototype.endsWith = function (suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };
}

if (typeof String.prototype.contains !== 'function') {
    String.prototype.contains = function (it) {
        return this.indexOf(it) != -1;
    };
}

var originalLeave = $.fn.popover.Constructor.prototype._leave;
$.fn.popover.Constructor.prototype._leave = function (event, context) {
    var dataKey = this.constructor.DATA_KEY;
    var self = this;

    context = context || $(event.currentTarget).data(dataKey);

    if (!context) {
        context = new this.constructor(event.currentTarget, this._getDelegateConfig());
        $(event.currentTarget).data(dataKey, context);
    }

    originalLeave.call(this, event, context);

    if ($(event.currentTarget)) {
        var container = $(context.tip);

        container.one('mouseenter', function () {
            //We entered the actual popover – call off the dogs
            clearTimeout(context._timeout);
            //Let's monitor popover content instead
            container.one('mouseleave', function () {
                $.fn.popover.Constructor.prototype._leave.call(self, event, context);
            });
        });
    }
};

$(document).ready(function () {
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
        var pusher = new Pusher(data.config.pusher_key);
        pusher.subscribe('privateU' + window.username).bind('new-notification', function (data) {
            notificationsModule.onNotificationReceived(data);
        });

        var thumbnail = $('.img-thumbnail.refreshing');

        if (window.content_id && thumbnail.length) {
            pusher.subscribe('content-' + window.content_id).bind('loaded-thumbnail', function (data) {
                var parent = thumbnail.first().parent();
                thumbnail.remove();
                $(parent).append('<img class="media-object img-thumbnail" src="' + data.url + '">');
            });
        }

        if (window.document.location.pathname.endsWith('/entries') && $.query.get('page') <= 1) {
            var template = Hogan.compile('\
<div class="panel-default entry" data-id="{{ hashId }}">\
    <a name="{{ hashId }}"></a>\
    <div class="entry_avatar">\
        <img src="{{ avatarPath }}" alt="{{ user.name }}">\
    </div>\
    <div class="panel-heading entry_header" data-hover="user_widget" data-user="{{{ user.name }}}">\
        <a href="/u/{{ user.name }}" class="entry_author">{{{ user.name }}}</a>\
        <span class="pull-right">\
            <span class="glyphicon glyphicon-tag"></span>\
            <a href="/g/{{ group.urlname }}" class="entry_group" data-hover="group_widget"  data-group="{{{ group.urlname }}}">g/{{ group.urlname }}</a>\
            <span class="glyphicon glyphicon-time"></span>\
            <a href="/e/{{ hashId }}">\
                <time pubdate title="{{ time }}">chwilę temu</time>\
            </a>\
            <span class="voting" data-id="{{ hashId }}" data-state="none" data-type="entry">\
                <button type="button" class="btn btn-default btn-xs vote-btn-up">\
                    <span class="glyphicon glyphicon-arrow-up vote-up"></span>\
                    <span class="count">0</span>\
                </button>\
                <button type="button" class="btn btn-default btn-xs vote-btn-down">\
                    <span class="glyphicon glyphicon-arrow-down vote-down"></span>\
                    <span class="count">0</span>\
                </button>\
            </span>\
        </span>\
    </div>\
    <div class="entry_text md">{{{ text }}}</div>\
    <div class="entry_actions pull-right">\
        <span class="glyphicon glyphicon-star-empty action_link save_entry" title="zapisz"></span>\
        <a class="entry_reply_link action_link">odpowiedz</a>\
        <a href="{{{ entryUrl }}}">#</a>\
    </div>\
</div>\
');

            pusher.subscribe('entries').bind('new-entry', function (data) {
                if (window.blocked_users.indexOf(data.author) != -1 || window.blocked_groups.indexOf(data.group) != -1) return;
                if (window.group && window.group != 'all') {
                    if (window.group == 'subscribed' && window.subscribed_groups.indexOf(data.group) == -1) return;else if (window.group == 'moderated' && window.moderated_groups.indexOf(data.group) == -1) return;else if (window.group != data.group) return;
                }
                $(template.render(data)).hide().fadeIn(1000).insertBefore($('.entry').eq(1));
            });
        }
    }

    $('.groupbar .dropdown').each(function (index) {
        var menu = $(this).find('.dropdown-menu');
        var menuElements = $(menu).children();
        var columns = Math.min(Math.max(Math.round(menuElements.length / 10), 1), 4).toString();
        var offset = $(this).offset();

        $(menu).css({
            '-moz-column-count': columns,
            '-webkit-column-count': columns,
            'column-count': columns,
            'left': offset.left + 'px'
        });
    });

    $('.content_preview_link').click(function () {
        var content = $(this).closest('.content');
        var content_id = $(content).attr('data-id');

        // Prevent loading preview multiple times
        if ($(content).data('preview-loading')) return;

        if ($(content).find('.content_preview').length == 0) {
            $(content).data('preview-loading', true);

            $.get('/ajax/content/' + content_id + '/embed', function (data) {
                $(content).append('<div class="content_preview">' + data.code + '</div>');
                $(content).data('preview-loading', false);

                $(content).find('.content_preview img').click(function () {
                    $(content).find('.content_preview').remove();

                    return false;
                });
            });
        } else {
            $(content).find('.content_preview').remove();
        }
    });

    $('.content_remove_btn').click(function () {
        var content_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć wybraną treść?", function (result) {
            if (result) $.post('/ajax/content/remove', { id: content_id }, function (data) {
                if (data.status == 'ok') window.location.href = "/";
            });
        });
    });

    $('.content_remove_link').click(function () {
        var content = $(this).closest('.content');
        var content_id = $(content).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć wybraną treść?", function (result) {
            if (result) $.post('/ajax/mod/content/remove', { id: content_id }, function (data) {
                if (data.status == 'ok') $(content).fadeOut();
            });
        });
    });

    $('.related_remove_link').click(function () {
        var related = $(this).parent().parent().parent();
        var related_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć powiązany link?", function (result) {
            if (result) $.post('/ajax/related/remove', { id: related_id }, function (data) {
                if (data.status == 'ok') $(related).fadeOut();
            });
        });
    });

    $('.ban_remove_btn').click(function () {
        var ban = $(this).parent().parent();
        var ban_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz odbanować wybranego użytkownika?", function (result) {
            if (result) $.post('/groups/unban', { id: ban_id }, function (data) {
                if (data.status == 'ok') $(ban).fadeOut();
            });
        });
    });

    $('.moderator_remove_btn').click(function () {
        var mod = $(this).parent().parent();
        var mod_id = $(this).attr('data-id');

        bootbox.confirm("Na pewno chcesz usunąć wybranego moderatora?", function (result) {
            if (result) $.post('/groups/remove_moderator', { id: mod_id }, function (data) {
                if (data.status == 'ok') $(mod).fadeOut();
            });
        });
    });

    $('.folder_publish').click(function () {
        var button = $(this);
        var id = $(this).parent().attr('data-id');

        if (button.hasClass('btn-success')) {
            $.post('/ajax/folder/edit', { folder: id, 'public': 'false' }, function (data) {
                if (data.status == 'ok') {
                    $(button).removeClass('btn-success');
                    $(button).addClass('btn-default');
                }
            });
        } else {
            $.post('/ajax/folder/edit', { folder: id, 'public': 'true' }, function (data) {
                if (data.status == 'ok') {
                    $(button).removeClass('btn-default');
                    $(button).addClass('btn-success');
                }
            });
        }
    });

    $('body').on('click', 'a.entry_reply_link', function () {
        var link = $(this);
        var entry = $(this).parent().parent();
        var author = $(entry).find('.entry_author').text();

        if (entry.hasClass('entry_reply')) var parent = $(entry).prevAll(".entry:not(.entry_reply)").first();else var parent = $(entry);

        var existingField = $(parent).nextUntil(".entry:not(.entry_reply)").find("textarea.reply");

        if (existingField.length) {
            $(existingField).focus().val($(existingField).val() + '@' + author + ': ');
            return;
        }

        var entry_id = $(parent).attr('data-id');

        $(entry).after('<div class="entry entry_reply"><form role="form" action="/entry/' + entry_id + '/reply" method="POST" accept-charset="UTF-8" class="enter_send entry_add_reply"><input type="hidden" name="id" value="' + entry_id + '"><div class="form-group"><textarea name="text" class="form-control reply" rows="3"></textarea></div><div class="btn-group pull-right"><button type="submit" class="btn btn-sm btn-primary">Wyślij</button><button type="button" class="btn btn-sm entry_reply_close">Anuluj</button></div></form></div><div class="clearfix"></div>');

        $(entry).next().find('textarea').focus().val('@' + author + ': ');

        // Needed to replace replies after sending reply using ajax
        $(entry).find('form').data('entry-parent', parent);

        $('.entry_reply_close').click(function () {
            var el = $(this);
            var text = el.closest('.entry').find('textarea').val();
            var isEmpty = /^@([\w]+):?(\W*)$/;

            // Close without confirmation if user didn't wrote anything
            if (!text || isEmpty.test(text)) {
                $(el).closest('.entry').remove();
                $(link).show();

                return;
            }

            bootbox.confirm("Na pewno chcesz zamknąć pole odpowiedzi?", function (result) {
                if (result) {
                    $(el).closest('.entry').remove();
                    $(link).show();
                }
            });
        });
    });

    $('body').on('click', 'a.entry_remove_link', function () {
        var entry = $(this).parent().parent();
        var entry_id = $(entry).attr('data-id');
        var type = entry.hasClass('entry_reply') == true ? 'entry_reply' : 'entry';

        bootbox.confirm("Na pewno chcesz usunąć wybrany wpis?", function (result) {
            if (result) $.post('/ajax/entry/remove', { id: entry_id, type: type }, function (data) {
                if (data.status == 'ok') {
                    if (type == 'entry') $(entry).nextUntil(".entry:not(.entry_reply)").andSelf().remove();else $(entry).remove();
                }
            });
        });
    });

    $('body').on('click', '.entry .show_blocked_link', function () {
        $(this).parent().parent().find('.entry_text.blocked').removeClass('blocked');
        $(this).remove();
    });

    $('body').on('click', '.comment .show_blocked_link', function () {
        $(this).parent().parent().find('.comment_text.blocked').removeClass('blocked');
        $(this).remove();
    });

    $('body').on('click', 'a.show_spoiler', function () {
        $(this).next().show();
        $(this).remove();
    });

    $('body').on('click', 'a.image', function () {
        var children = $(this).children('img').first();

        if (children.length) $(children).first().toggle();else $(this).append('<img src="' + $(this).attr('href') + '">');

        return false;
    });

    function findYTVideos() {
        $('.md a[href*="youtube.com"]').each(function () {
            var url = $(this).attr('href');
            var regex = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:[^#]+)?(?:#)?(?:t=(\d+))?(?:\S+)?$/i;

            var found = url.match(regex);

            if (found) $(this).addClass('yt-video').data('yt-id', found[1]).data('yt-time', found[2]);
        });
    }

    findYTVideos();

    $('body').on('click', 'a.yt-video', function () {
        var id = $(this).data('yt-id');
        var next = $(this).next();

        if (next.length && $(next).hasClass('yt-embed')) $(next).remove();else {
            $(this).after('<video-yt class="yt-embed" style="width: 480px; height: 360px;" vid="' + id + '"></video-yt>');
            riot.mount('video-yt');
        }

        return false;
    });

    $('.entry_expand_replies').click(function () {
        var el = $(this);
        var entry_id = $(this).attr('data-id');

        $.get('/ajax/entry/' + entry_id + '/replies', function (data) {
            $(el).nextUntil('.entry:not(.entry_reply)').remove();
            $(el).after(data);
            $(el).remove();
        });

        $(this).unbind('click');

        findYTVideos();
    });

    $('body').on('click', '.comment_reply_link', function () {
        var link = $(this);
        var comment = $(this).parent().parent();
        var author = $(comment).find('.comment_author').text().trim();

        if (comment.hasClass('comment_reply')) var parent = $(comment).prevAll(".comment:not(.comment_reply)").first();else var parent = $(comment);

        var existingField = $(parent).nextUntil(".comment:not(.comment_reply)").find("textarea.reply");

        if (existingField.length) {
            $(existingField).focus().val($(existingField).val() + '@' + author + ': ');
            return;
        }

        var comment_id = $(parent).attr('data-id');

        $(comment).after('<div class="comment comment_reply"><form role="form" action="/comment/' + comment_id + '/reply" method="POST" accept-charset="UTF-8" class="enter_send comment_add_reply"><input type="hidden" name="id" value="' + comment_id + '"><div class="form-group"><textarea name="text" class="form-control reply" rows="3"></textarea></div><div class="btn-group pull-right"><button type="submit" class="btn btn-sm btn-primary">Wyślij</button><button type="button" class="btn btn-sm comment_reply_close">Anuluj</button></div></form></div><div class="clearfix"></div>');

        $(comment).next().find('textarea').focus().val('@' + author + ': ');

        $('.comment_reply_close').click(function () {
            var el = $(this);
            var text = el.closest('.comment').find('textarea').val();
            var isEmpty = /^@([\w]+):?(\W*)$/;

            // Close without confirmation if user didn't wrote anything
            if (!text || isEmpty.test(text)) {
                $(el).closest('.comment').remove();
                $(link).show();

                return;
            }

            bootbox.confirm("Na pewno chcesz zamknąć pole odpowiedzi?", function (result) {
                if (result) {
                    $(el).closest('.comment').remove();
                    $(link).show();
                }
            });
        });

        $(link).hide();
    });

    $('body').on('click', '.comment_edit_link', function () {
        var comment = $(this).parent().parent();
        var comment_id = $(comment).attr('data-id');
        var comment_old_text = $(comment).find('.comment_text').html();
        var type = comment.hasClass('comment_reply') == true ? 'comment_reply' : 'comment';

        $.post('/ajax/comment/source', { id: comment_id, type: type }, function (data) {
            if (data.status == 'ok') {
                $(comment).find('.comment_text').html('<form role="form" accept-charset="UTF-8" class="enter_send"><input type="hidden" name="id" value="' + comment_id + '"><div class="form-group"><textarea name="text" class="form-control" rows="3"></textarea></div><div class="btn-group pull-right"><button type="button" class="btn btn-sm btn-primary comment_edit_save">Zapisz</button><button type="button" class="btn btn-sm comment_edit_close">Anuluj</button></div><div class="clearfix"></div></form>');
                $(comment).find('textarea[name="text"]').val(data.source);
                $(comment).find('.comment_actions').hide();

                $('.comment_edit_save').click(function () {
                    var comment_new_text = $(comment).find('textarea[name="text"]').val();

                    $.post('/ajax/comment/edit', { id: comment_id, type: type, text: comment_new_text }, function (data) {
                        if (data.status == 'ok') {
                            $(comment).find('.comment_text').html(data.parsed);
                            $(comment).find('.comment_actions').show();
                        }
                    });
                });

                $('.comment_edit_close').click(function () {
                    $(comment).find('.comment_text').html(comment_old_text);
                    $(comment).find('.comment_actions').show();
                });
            }
        });
    });

    $('body').on('click', '.comment_remove_link', function () {
        var comment = $(this).parent().parent();
        var comment_id = $(comment).attr('data-id');
        var type = comment.hasClass('comment_reply') == true ? 'comment_reply' : 'comment';

        bootbox.confirm("Na pewno chcesz usunąć wybrany wpis?", function (result) {
            if (result) $.post('/ajax/comment/remove', { id: comment_id, type: type }, function (data) {
                if (data.status == 'ok') {
                    if (type == 'comment') $(comment).nextUntil(".comment:not(.comment_reply)").andSelf().remove();else $(comment).remove();
                }
            });
        });
    });

    $('.add_related_btn').click(function () {
        $('.related_add_form').toggle();
    });

    $('.toggle_night_mode').click(function () {
        if ($('body').hasClass('night')) {
            $.removeCookie('night_mode', { path: '/' });
            $('body').removeClass('night');
        } else {
            $.cookie('night_mode', 'on', { expires: 365, path: '/' });
            $('body').addClass('night');
        }
    });

    $('.content_sort a').click(function () {
        if ($(this).attr('data-sort')) window.location.search = jQuery.query.set('sort', $(this).attr('data-sort'));else window.location.search = jQuery.query.remove('sort');
    });

    $('.content_filter a').click(function () {
        if ($(this).attr('data-time')) window.location.search = jQuery.query.set('time', $(this).attr('data-time'));else window.location.search = jQuery.query.remove('time');
    });

    $('body').on('mouseup', '.entry_text', function () {
        var entry = $(this).parent();

        setTimeout(function () {
            var sel = window.getSelection();
            var link = $(entry).find('.quote_link');

            if (sel.rangeCount && !sel.isCollapsed && $.contains(entry[0], sel.getRangeAt(0).startContainer.parentNode)) {
                if (!link.length) $(entry).find('.entry_actions').prepend('<a class="quote_link action_link">cytuj</a>');

                $(entry).find('.quote_link').data('text', sel.toString());
            } else {
                $(entry).find('.quote_link').remove();
            }
        }, 10);
    });

    $('body').on('mouseup', '.comment_text', function () {
        var comment = $(this).parent();

        setTimeout(function () {
            var sel = window.getSelection();
            var link = $(comment).find('.quote_link');

            if (sel.rangeCount && !sel.isCollapsed && $.contains(comment[0], sel.getRangeAt(0).startContainer.parentNode)) {
                if (!link.length) $(comment).find('.comment_actions').prepend('<a class="quote_link action_link">cytuj</a>');

                $(comment).find('.quote_link').data('text', sel.toString());
            } else {
                $(comment).find('.quote_link').remove();
            }
        }, 10);
    });

    $('body').on('click', '.entry .quote_link', function () {
        var link = this;
        var entry = $(this).parents('.entry').first();
        var author = $(entry).find('.entry_author').text();

        if (entry.hasClass('entry_reply')) var parent = $(entry).prevAll(".entry:not(.entry_reply)").first();else var parent = $(entry);

        var field = $(parent).nextUntil('.entry:not(.entry_reply)').find('textarea.reply');

        if (!field.length) {
            $(entry).find('.entry_reply_link').click();
            var field = $(parent).nextUntil('.entry:not(.entry_reply)').find('textarea.reply');
        }

        if (!field.val().contains("@" + author)) $(field).focus().val($(field).val().trim() + "\n\n@" + author);

        $(field).focus().val($(field).val().trim() + "\n> " + $(this).data('text') + "\n\n");
    });

    $('body').on('click', '.comment .quote_link', function () {
        var link = this;
        var comment = $(this).parents('.comment').first();
        var author = $(comment).find('.comment_author').text();

        if (comment.hasClass('comment_reply')) var parent = $(comment).prevAll(".comment:not(.comment_reply)").first();else var parent = $(comment);

        var field = $(parent).nextUntil('.comment:not(.comment_reply)').find('textarea.reply');

        if (!field.length) {
            $(comment).find('.comment_reply_link').click();
            var field = $(parent).nextUntil('.comment:not(.comment_reply)').find('textarea.reply');
        }

        if (!field.val().contains("@" + author)) $(field).focus().val($(field).val().trim() + "\n\n@" + author);

        $(field).focus().val($(field).val().trim() + "\n> " + $(this).data('text') + "\n\n");
    });

    $('input[name="browser_notifications"]').click(function () {
        if (this.checked) {
            Notification.requestPermission(function (permission) {
                if (!('permission' in Notification)) Notification.permission = permission;

                if (permission === "granted") $('input[name="browser_notifications"]').prop('checked', true);
            });
        }

        return false;
    });

    if (window.Notification && Notification.permission === "granted") $('input[name="browser_notifications"]').prop('checked', true);

    if (window.username) {
        var groups = new Bloodhound({
            datumTokenizer: function datumTokenizer(d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: '/groups.json',
                filter: function filter(d) {
                    if (window.settings && window.settings.homepage_subscribed) return _.filter(d, function (g) {
                        return _.includes(window.subscribed_groups, g.value);
                    });else return _.filter(d, function (g) {
                        return !_.includes(window.blocked_groups, g.value);
                    });
                }
            },
            sorter: function sorter(a, b) {
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
            datumTokenizer: function datumTokenizer(d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: '/users.json',
                filter: function filter(d) {
                    if (window.blocked_users) return _.filter(d, function (u) {
                        return !_.contains(window.blocked_users, u.value);
                    });else return d;
                }
            }
        });

        users.initialize();

        $('input.user_typeahead').typeahead([{
            name: 'users',
            source: users.ttAdapter(),
            templates: {
                suggestion: _.template('<img src="<%= avatar %>" class="avatar"><p><%= value %></p>')
            }
        }]);
    }

    $('.content_add_form a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('input[name="type"]').val($(e.target).attr('href').substring(1));
    });

    if ($('link[data-id="group_style"]').length) $(document).keypress(function (e) {
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

    $('.dropdown-menu input, .dropdown-menu label').click(function (e) {
        e.stopPropagation();
    });

    $('.has_tooltip').tooltip();

    $('select.image-picker').imagepicker();

    $('input.tags').tagsinput();

    bootbox.setDefaults({ locale: "pl" });

    if ($('textarea.md_editor').length) {
        var textarea = $('textarea.md_editor')[0];
    }

    if ($('textarea.css_editor').length) {
        var textarea = $('textarea.css_editor')[0];

        /*
        var editor = CodeMirror.fromTextArea(textarea, {
            mode: 'text/css',
            lineNumbers: true
        });
         $('a[href=#style]').on('shown.bs.tab', function (e) {
            editor.refresh();
            editor.refresh();
        });
        */
    }

    if (document.location.hash) {
        var id = document.location.hash.substr(1);
        var highlighted = $('div[data-id=' + id + ']');

        if (highlighted.length) $(highlighted).addClass('highlighted');
    }

    if (document.location.hash && $('.nav-tabs a').length) {
        $('.nav-tabs a[href=' + document.location.hash + ']').tab('show');
    }

    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    if ($('.conversation_messages').length) {
        $('.conversation_messages').scrollTop($('.conversation_messages').prop('scrollHeight'));
    }
});
//# sourceMappingURL=all.js.map
