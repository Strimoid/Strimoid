(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://strimoid.dev',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login_form","action":"AuthController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":null,"action":"AuthController@login"},{"host":null,"methods":["POST"],"uri":"logout","name":null,"action":"AuthController@logout"},{"host":null,"methods":["POST"],"uri":"pusher\/auth","name":null,"action":"AuthController@authenticatePusher"},{"host":null,"methods":["POST"],"uri":"oauth2\/token","name":null,"action":"OAuthController@getAccessToken"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth2\/authorize","name":null,"action":"OAuthController@authorizationForm"},{"host":null,"methods":["POST"],"uri":"oauth2\/authorize","name":null,"action":"OAuthController@authorize"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth2\/apps","name":null,"action":"OAuthController@listApps"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth2\/add_app","name":null,"action":"OAuthController@addAppForm"},{"host":null,"methods":["POST"],"uri":"oauth2\/add_app","name":null,"action":"OAuthController@addApp"},{"host":null,"methods":["POST"],"uri":"me\/blocked_domain\/{domain}","name":null,"action":"UserController@blockDomain"},{"host":null,"methods":["DELETE"],"uri":"me\/blocked_domain\/{domain}","name":null,"action":"UserController@unblockDomain"},{"host":null,"methods":["GET","HEAD"],"uri":"users.json","name":null,"action":"UserController@showJSONList"},{"host":null,"methods":["GET","HEAD"],"uri":"register","name":"auth.register","action":"Auth\RegistrationController@showRegisterForm"},{"host":null,"methods":["POST"],"uri":"register","name":null,"action":"Auth\RegistrationController@processRegistration"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/activate\/{token}","name":null,"action":"Auth\RegistrationController@activateAccount"},{"host":null,"methods":["GET","HEAD"],"uri":"remind","name":"auth.remind","action":"UserController@remindPassword"},{"host":null,"methods":["POST"],"uri":"remind","name":null,"action":"UserController@remindPassword"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset\/{token}","name":null,"action":"UserController@showPasswordResetForm"},{"host":null,"methods":["POST"],"uri":"password\/reset\/{token}","name":null,"action":"UserController@resetPassword"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/remove","name":null,"action":"UserController@showRemoveAccountForm"},{"host":null,"methods":["POST"],"uri":"account\/remove","name":null,"action":"UserController@removeAccount"},{"host":null,"methods":["GET","HEAD"],"uri":"u\/{user}","name":"user_profile","action":"UserController@showProfile"},{"host":null,"methods":["GET","HEAD"],"uri":"u\/{user}\/{type}","name":"user_profile.type_filter","action":"UserController@showProfile"},{"host":null,"methods":["POST"],"uri":"settings\/change_password","name":null,"action":"UserController@changePassword"},{"host":null,"methods":["POST"],"uri":"settings\/change_email","name":null,"action":"UserController@changeEmail"},{"host":null,"methods":["POST"],"uri":"settings\/save\/profile","name":null,"action":"UserController@saveProfile"},{"host":null,"methods":["GET","HEAD"],"uri":"account\/change_email\/{token}","name":null,"action":"UserController@confirmEmailChange"},{"host":null,"methods":["POST"],"uri":"u\/{user}\/block","name":null,"action":"UserController@blockUser"},{"host":null,"methods":["DELETE"],"uri":"u\/{user}\/block","name":null,"action":"UserController@unblockUser"},{"host":null,"methods":["POST"],"uri":"u\/{user}\/observe","name":null,"action":"UserController@observeUser"},{"host":null,"methods":["DELETE"],"uri":"u\/{user}\/observe","name":null,"action":"UserController@unobserveUser"},{"host":null,"methods":["GET","HEAD"],"uri":"settings","name":"user_settings","action":"SettingsController@showSettings"},{"host":null,"methods":["POST"],"uri":"settings\/save\/settings","name":null,"action":"SettingsController@saveSettings"},{"host":null,"methods":["GET","HEAD"],"uri":"conversations","name":null,"action":"ConversationController@showConversation"},{"host":null,"methods":["GET","HEAD"],"uri":"conversation\/{conversation}","name":"conversation","action":"ConversationController@showConversation"},{"host":null,"methods":["GET","HEAD"],"uri":"conversations\/new","name":null,"action":"ConversationController@showCreateForm"},{"host":null,"methods":["GET","HEAD"],"uri":"conversations\/new\/{user}","name":"conversation.new_user","action":"ConversationController@showCreateForm"},{"host":null,"methods":["POST"],"uri":"conversations\/new","name":null,"action":"ConversationController@createConversation"},{"host":null,"methods":["POST"],"uri":"conversations\/send","name":null,"action":"ConversationController@sendMessage"},{"host":null,"methods":["GET","HEAD"],"uri":"ajax\/notification\/get\/{count}","name":null,"action":"NotificationController@showJSONList"},{"host":null,"methods":["GET","HEAD"],"uri":"ajax\/notification\/get_count","name":null,"action":"NotificationController@showJSONCount"},{"host":null,"methods":["POST"],"uri":"ajax\/notification\/mark_all_read","name":null,"action":"NotificationController@markAllAsRead"},{"host":null,"methods":["GET","HEAD"],"uri":"notifications","name":null,"action":"NotificationController@showList"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"global_contents","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"rss","name":"global_contents_rss","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"new","name":"global_contents_new","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"new\/rss","name":"global_contents_new_rss","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{groupname}","name":"group_contents","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{groupname}\/rss","name":"group_contents_rss","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{groupname}\/new","name":"group_contents_new","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{groupname}\/new\/rss","name":"group_contents_new_rss","action":"ContentController@showContentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"c\/{content}","name":"content_comments","action":"ContentController@showComments"},{"host":null,"methods":["GET","HEAD"],"uri":"c\/{content}\/frame","name":null,"action":"ContentController@showFrame"},{"host":null,"methods":["GET","HEAD"],"uri":"ajax\/content\/{content}\/embed","name":null,"action":"Content\EmbedController@getEmbedCode"},{"host":null,"methods":["GET","HEAD"],"uri":"c\/{content}\/thumbnail","name":null,"action":"Content\ThumbnailController@chooseThumbnail"},{"host":null,"methods":["POST"],"uri":"save_thumbnail","name":null,"action":"Content\ThumbnailController@saveThumbnail"},{"host":null,"methods":["GET","HEAD"],"uri":"add","name":null,"action":"ContentController@showAddForm"},{"host":null,"methods":["POST"],"uri":"add","name":null,"action":"ContentController@addContent"},{"host":null,"methods":["GET","HEAD"],"uri":"c\/{content}\/edit","name":null,"action":"ContentController@showEditForm"},{"host":null,"methods":["POST"],"uri":"c\/{content}\/edit","name":null,"action":"ContentController@editContent"},{"host":null,"methods":["POST"],"uri":"ajax\/content\/remove","name":null,"action":"ContentController@removeContent"},{"host":null,"methods":["POST"],"uri":"ajax\/mod\/content\/remove","name":null,"action":"ContentController@softRemoveContent"},{"host":null,"methods":["POST"],"uri":"c\/{content}\/add_related","name":null,"action":"RelatedController@addRelated"},{"host":null,"methods":["POST"],"uri":"ajax\/related\/remove","name":null,"action":"RelatedController@removeRelated"},{"host":null,"methods":["GET","HEAD"],"uri":"c\/{content}\/{slug}","name":"content_comments_slug","action":"ContentController@showComments"},{"host":null,"methods":["POST"],"uri":"c\/{content}\/add_vote","name":null,"action":"PollController@addVote"},{"host":null,"methods":["GET","HEAD"],"uri":"comments","name":"global_comments","action":"CommentController@showCommentsFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{groupname}\/comments","name":"group_comments","action":"CommentController@showCommentsFromGroup"},{"host":null,"methods":["POST"],"uri":"c\/{content}\/comment","name":null,"action":"CommentController@addComment"},{"host":null,"methods":["POST"],"uri":"comment\/{comment}\/reply","name":null,"action":"CommentController@addReply"},{"host":null,"methods":["POST"],"uri":"ajax\/comment\/source","name":null,"action":"CommentController@getCommentSource"},{"host":null,"methods":["POST"],"uri":"ajax\/comment\/edit","name":null,"action":"CommentController@editComment"},{"host":null,"methods":["POST"],"uri":"ajax\/comment\/remove","name":null,"action":"CommentController@removeComment"},{"host":null,"methods":["GET","HEAD"],"uri":"entries","name":"global_entries","action":"EntryController@showEntriesFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{groupname}\/entries","name":"group_entries","action":"EntryController@showEntriesFromGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"e\/{entry}","name":"single_entry","action":"EntryController@showEntry"},{"host":null,"methods":["GET","HEAD"],"uri":"er\/{entry_reply}","name":"single_entry_reply","action":"EntryController@showEntry"},{"host":null,"methods":["GET","HEAD"],"uri":"ajax\/entry\/{entry}\/replies","name":null,"action":"EntryController@getEntryReplies"},{"host":null,"methods":["POST"],"uri":"ajax\/entry\/add","name":null,"action":"EntryController@addEntry"},{"host":null,"methods":["POST"],"uri":"entry\/{entry}\/reply","name":null,"action":"EntryController@addReply"},{"host":null,"methods":["POST"],"uri":"ajax\/entry\/source","name":null,"action":"EntryController@getEntrySource"},{"host":null,"methods":["POST"],"uri":"ajax\/entry\/edit","name":null,"action":"EntryController@editEntry"},{"host":null,"methods":["POST"],"uri":"ajax\/entry\/remove","name":null,"action":"EntryController@removeEntry"},{"host":null,"methods":["GET","HEAD"],"uri":"intro","name":null,"action":"GroupController@showWizard"},{"host":null,"methods":["GET","HEAD"],"uri":"groups\/list","name":null,"action":"GroupController@showList"},{"host":null,"methods":["GET","HEAD"],"uri":"groups.json","name":null,"action":"GroupController@showJSONList"},{"host":null,"methods":["GET","HEAD"],"uri":"ajax\/groups\/subscribed","name":null,"action":"GroupController@showSubscribed"},{"host":null,"methods":["GET","HEAD"],"uri":"groups\/create","name":null,"action":"GroupController@showCreateForm"},{"host":null,"methods":["POST"],"uri":"groups\/create","name":null,"action":"GroupController@createGroup"},{"host":null,"methods":["POST"],"uri":"groups\/add_moderator","name":null,"action":"Group\ModeratorController@addModerator"},{"host":null,"methods":["POST"],"uri":"groups\/remove_moderator","name":null,"action":"Group\ModeratorController@removeModerator"},{"host":null,"methods":["POST"],"uri":"groups\/ban","name":null,"action":"Group\BanController@addBan"},{"host":null,"methods":["POST"],"uri":"groups\/unban","name":null,"action":"Group\BanController@removeBan"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{group}\/moderators","name":"group_moderators","action":"Group\ModeratorController@showModeratorList"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{group}\/banned","name":"group_banned","action":"Group\BanController@showBannedList"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{group}\/settings","name":"group_settings","action":"GroupController@showSettings"},{"host":null,"methods":["POST"],"uri":"g\/{group}\/settings\/save\/profile","name":null,"action":"GroupController@saveProfile"},{"host":null,"methods":["POST"],"uri":"g\/{group}\/settings\/save\/settings","name":null,"action":"GroupController@saveSettings"},{"host":null,"methods":["POST"],"uri":"g\/{group}\/settings\/save\/style","name":null,"action":"GroupController@saveStyle"},{"host":null,"methods":["POST"],"uri":"g\/{group}\/subscription","name":null,"action":"GroupController@subscribeGroup"},{"host":null,"methods":["DELETE"],"uri":"g\/{group}\/subscription","name":null,"action":"GroupController@unsubscribeGroup"},{"host":null,"methods":["POST"],"uri":"g\/{group}\/block","name":null,"action":"GroupController@blockGroup"},{"host":null,"methods":["DELETE"],"uri":"g\/{group}\/block","name":null,"action":"GroupController@unblockGroup"},{"host":null,"methods":["GET","HEAD"],"uri":"kreator","name":"wizard","action":"GroupController@wizard"},{"host":null,"methods":["GET","HEAD"],"uri":"kreator\/{tag}","name":"wizard_tag","action":"GroupController@wizard"},{"host":null,"methods":["GET","HEAD"],"uri":"ajax\/group\/{group}\/sidebar","name":null,"action":"GroupController@getSidebar"},{"host":null,"methods":["GET","HEAD"],"uri":"f\/{folder}","name":"folder_contents","action":"ContentController@showContentsFromFolder"},{"host":null,"methods":["GET","HEAD"],"uri":"f\/{folder}\/new","name":"folder_contents_new","action":"ContentController@showContentsFromFolder"},{"host":null,"methods":["GET","HEAD"],"uri":"f\/{folder}\/entries","name":"folder_entries","action":"EntryController@showEntriesFromFolder"},{"host":null,"methods":["GET","HEAD"],"uri":"f\/{folder}\/comments","name":"folder_comments","action":"CommentController@showCommentsFromFolder"},{"host":null,"methods":["GET","HEAD"],"uri":"u\/{user}\/f\/{folder}","name":"user_folder_contents","action":"ContentController@showContentsFromFolder"},{"host":null,"methods":["GET","HEAD"],"uri":"u\/{user}\/f\/{folder}\/new","name":"user_folder_contents_new","action":"ContentController@showContentsFromFolder"},{"host":null,"methods":["GET","HEAD"],"uri":"u\/{user}\/f\/{folder}\/entries","name":"user_folder_entries","action":"EntryController@showEntriesFromFolder"},{"host":null,"methods":["GET","HEAD"],"uri":"u\/{user}\/f\/{folder}\/comments","name":"user_folder_comments","action":"CommentController@showCommentsFromFolder"},{"host":null,"methods":["POST"],"uri":"ajax\/folder\/create","name":null,"action":"FolderController@createFolder"},{"host":null,"methods":["POST"],"uri":"ajax\/folder\/edit","name":null,"action":"FolderController@editFolder"},{"host":null,"methods":["POST"],"uri":"ajax\/folder\/remove","name":null,"action":"FolderController@removeFolder"},{"host":null,"methods":["POST"],"uri":"folder\/copy","name":null,"action":"FolderController@copyFolder"},{"host":null,"methods":["POST"],"uri":"ajax\/folder\/add_group","name":null,"action":"FolderController@addToFolder"},{"host":null,"methods":["POST"],"uri":"ajax\/folder\/remove_group","name":null,"action":"FolderController@removeFromFolder"},{"host":null,"methods":["POST"],"uri":"ajax\/vote\/add","name":null,"action":"VoteController@addVote"},{"host":null,"methods":["POST"],"uri":"ajax\/vote\/remove","name":null,"action":"VoteController@removeVote"},{"host":null,"methods":["POST"],"uri":"ajax\/vote\/get_voters","name":null,"action":"VoteController@getVoters"},{"host":null,"methods":["POST"],"uri":"ajax\/content\/add_save","name":null,"action":"SaveController@saveContent"},{"host":null,"methods":["POST"],"uri":"ajax\/content\/remove_save","name":null,"action":"SaveController@removeContent"},{"host":null,"methods":["POST"],"uri":"ajax\/entry\/add_save","name":null,"action":"SaveController@saveEntry"},{"host":null,"methods":["POST"],"uri":"ajax\/entry\/remove_save","name":null,"action":"SaveController@removeEntry"},{"host":null,"methods":["POST"],"uri":"ajax\/utils\/get_title","name":null,"action":"UtilsController@getURLTitle"},{"host":null,"methods":["GET","HEAD"],"uri":"cookies","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"contact","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"guide","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"rules","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"tag\/{tag}","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"search","name":"search","action":"SearchController@search"},{"host":null,"methods":["GET","HEAD"],"uri":"ranking","name":"ranking","action":"RankingController@showRanking"},{"host":null,"methods":["GET","HEAD"],"uri":"g\/{group}\/ranking","name":"group_ranking","action":"RankingController@showRanking"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                return this.getCorrectUrl(uri + qs);
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if(!this.absolute)
                    return url;

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

