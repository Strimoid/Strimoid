<?php

/*

Route::bind('user', function($value, $route)
{
    return User::shadow($value)->first();
});

Route::bind('group', function($value, $route)
{
    return Group::shadow($value)->first();
});

*/

Route::model('content', 'Content');
Route::model('related', 'ContentRelated');
Route::model('notification', 'Notification');
Route::model('comment', 'Comment');
Route::model('comment_reply', 'CommentReply');
Route::model('entry', 'Entry');
Route::model('entry_reply', 'EntryReply');

/* API ============================================================================================================== */
Route::group(['domain' => 'api.strimoid.pl'], function()
{
    Route::get('/me', ['before' => 'oauth:basic', 'uses' => 'UserController@showCurrentUser']);

    // Contents

    Route::get('/contents', ['before' => 'oauth', 'uses' => 'ContentController@getIndex']);

    Route::get('/content/{content}', 'ContentController@show');
    Route::post('/content', ['before' => 'oauth:contents', 'uses' => 'ContentController@store']);
    Route::patch('/content/{content}', ['before' => 'oauth:contents', 'uses' => 'ContentController@edit']);
    Route::delete('/content/{content}', ['before' => 'oauth:contents', 'uses' => 'ContentController@removeContent']);

    Route::post('/content/{content}/related', ['before' => 'oauth:contents', 'uses' => 'RelatedController@store']);
    Route::delete('/related/{related}', ['before' => 'oauth:contents', 'uses' => 'RelatedController@removeRelated']);

    // Comments

    Route::get('/comments', ['uses' => 'CommentController@index']);

    Route::post('/content/{content}/comment', ['before' => 'oauth:comments', 'uses' => 'CommentController@store']);
    Route::post('/comment', ['before' => 'oauth:comments', 'uses' => 'CommentController@storeReply']);

    Route::patch('/comment/{comment}', ['before' => 'oauth:comments', 'uses' => 'CommentController@edit']);
    Route::patch('/comment/{comment_reply}/reply', ['before' => 'oauth:comments', 'uses' => 'CommentController@edit']);

    Route::delete('/comment/{comment}', ['before' => 'oauth:comments', 'uses' => 'CommentController@remove']);
    Route::delete('/comment/{comment_reply}/reply', ['before' => 'oauth:comments', 'uses' => 'CommentController@remove']);

    // Entries

    Route::get('/entries', ['before' => 'oauth', 'uses' => 'EntryController@getIndex']);
    Route::get('/entry/{entry}', 'EntryController@show');

    Route::post('/entry', ['before' => 'oauth:entries', 'uses' => 'EntryController@store']);
    Route::post('/entry/{entry}/reply', ['before' => 'oauth:entries', 'uses' => 'EntryController@store']);

    Route::delete('/entry/{entry}', ['before' => 'oauth:entries', 'uses' => 'EntryController@remove']);

    // Groups

    Route::get('/groups', 'GroupController@index');
    Route::get('/group/{id}', 'GroupController@show');

    // Users

    Route::get('/user/{id}', 'UserController@show');

    // Conversations
    Route::get('/conversations', ['before' => 'oauth:conversations', 'uses' => 'ConversationController@getIndex']);
    Route::get('/messages', ['before' => 'oauth:conversations', 'uses' => 'ConversationController@getMessages']);

    // Notifications

    Route::get('/notifications', [
        'before' => 'oauth:notifications', 'uses' => 'NotificationController@listNotifications'
    ]);
    Route::patch('/notification/{notification}', [
        'before' => 'oauth:notifications', 'uses' => 'NotificationController@edit'
    ]);

    Route::post('/notifications/register_gcm', [
        'before' => 'oauth:notifications', 'uses' => 'NotificationController@registerGCM'
    ]);

    // Ranking
    Route::get('/ranking', 'RankingController@getIndex');

    // Voting
    Route::post('/vote', ['before' => 'oauth:votes', 'uses' => 'VoteController@addVote']);
    Route::delete('/vote', ['before' => 'oauth:votes', 'uses' => 'VoteController@removeVote']);
});

Route::group(['prefix' => 'api/v1'], function()
{
    Route::get('/', function() {
        return '<a href="https://developers.strimoid.pl">API Documentation</a>';
    });

    Route::get('/me', ['before' => 'oauth:basic', 'uses' => 'UserController@showCurrentUser']);

    // Auth
    Route::post('/login', ['uses' => 'AuthController@login']);
    Route::post('/logout', ['before' => 'auth', 'uses' => 'AuthController@logout']);

    // Contents

    // Route::resource('contents', 'ContentController', ['only' => ['index', 'show']]);

    Route::get('/contents', ['before' => 'oauth', 'uses' => 'ContentController@getIndex']);

    Route::get('/contents/{content}', 'ContentController@show');
    Route::post('/contents', ['before' => 'oauth:contents', 'uses' => 'ContentController@store']);
    Route::patch('/contents/{content}', ['before' => 'oauth:contents', 'uses' => 'ContentController@edit']);
    Route::delete('/contents/{content}', ['before' => 'oauth:contents', 'uses' => 'ContentController@removeContent']);

    Route::post('/content/{content}/related', ['before' => 'oauth:contents', 'uses' => 'RelatedController@store']);
    Route::delete('/related/{related}', ['before' => 'oauth:contents', 'uses' => 'RelatedController@removeRelated']);

    // Comments

    Route::get('/comments', ['uses' => 'CommentController@index']);
    Route::post('/content/{content}/comment', ['before' => 'oauth:comments', 'uses' => 'CommentController@store']);
    Route::post('/comment', ['before' => 'oauth:comments', 'uses' => 'CommentController@storeReply']);
    Route::patch('/comment/{comment}/{reply?}', ['before' => 'oauth:comments', 'uses' => 'CommentController@edit']);
    Route::delete('/comment/{comment}/{reply?}', ['before' => 'oauth:comments', 'uses' => 'CommentController@remove']);

    // Entries

    Route::get('/entries', ['before' => 'oauth', 'uses' => 'EntryController@getIndex']);
    Route::get('/entry/{entry}', 'EntryController@show');
    Route::post('/entry', ['before' => 'oauth:entries', 'uses' => 'EntryController@store']);
    Route::post('/entry/{entry}/reply', ['before' => 'oauth:entries', 'uses' => 'EntryController@store']);
    Route::delete('/entry/{entry}', ['before' => 'oauth:entries', 'uses' => 'EntryController@remove']);

    // Groups
    Route::resource('groups', 'GroupController', ['only' => ['index', 'show']]);

    // Users
    Route::get('/users/{id}', 'UserController@show');

    // Conversations
    Route::get('/conversations', ['before' => 'oauth:conversations', 'uses' => 'ConversationController@getIndex']);
    Route::get('/messages', ['before' => 'oauth:conversations', 'uses' => 'ConversationController@getMessages']);

    // Notifications

    Route::get('/notifications', [
        'before' => 'oauth:notifications', 'uses' => 'NotificationController@listNotifications'
    ]);
    Route::patch('/notification/{notification}', [
        'before' => 'oauth:notifications', 'uses' => 'NotificationController@edit'
    ]);

    Route::post('/notifications/register_gcm', [
        'before' => 'oauth:notifications', 'uses' => 'NotificationController@registerGCM'
    ]);

    // Ranking
    Route::get('/ranking', 'RankingController@getIndex');

    // Voting
    Route::post('/votes', ['before' => 'oauth:votes', 'uses' => 'VoteController@addVote']);
    Route::delete('/votes', ['before' => 'oauth:votes', 'uses' => 'VoteController@removeVote']);
});

/* OAuth2 =========================================================================================================== */
Route::post('/oauth2/token', 'OAuthController@getAccessToken');

Route::get('/oauth2/authorize', ['before' => 'auth', 'uses' => 'OAuthController@authorizationForm']);
Route::post('/oauth2/authorize', ['before' => 'auth', 'uses' => 'OAuthController@authorize']);

Route::get('/oauth2/apps', ['before' => 'auth', 'uses' => 'OAuthController@listApps']);

Route::get('/oauth2/add_app', ['before' => 'auth', 'uses' => 'OAuthController@addAppForm']);
Route::post('/oauth2/add_app', ['before' => 'auth|anti_flood', 'uses' => 'OAuthController@addApp']);

/* Users ============================================================================================================ */
Route::post('/me/blocked_domain/{domain}', ['before' => 'auth', 'uses' => 'UserController@blockDomain']);
Route::delete('/me/blocked_domain/{domain}', ['before' => 'auth', 'uses' => 'UserController@unblockDomain']);

Route::get('/users.json', 'UserController@showJSONList');

Route::get('/register', ['before' => 'guest', 'uses' => 'UserController@showRegisterForm']);
Route::post('/register', ['before' => 'anti_spam|guest','uses' => 'UserController@processRegistration']);

Route::get('/login', ['before' => 'guest', 'as' => 'login_form', 'uses' => 'UserController@showLoginForm']);
Route::post('/login', ['before' => 'guest', 'uses' => 'UserController@login']);
Route::post('/logout', ['before' => 'auth', 'uses' => 'UserController@logout']);

Route::get('/remind', 'UserController@remindPassword');
Route::post('/remind', 'UserController@remindPassword');

Route::get('/password/reset/{token}', 'UserController@showPasswordResetForm');
Route::post('/password/reset/{token}', 'UserController@resetPassword');

Route::get('/account/activate/{token}', 'UserController@activateAccount');

Route::get('/account/remove', 'UserController@showRemoveAccountForm');
Route::post('/account/remove', 'UserController@removeAccount');

Route::get('/u/{username}', ['as' => 'user_profile', 'uses' =>'UserController@showProfile']);

Route::get('/u/{username}/{type}', ['as' => 'user_profile.type_filter', 'uses' =>'UserController@showProfile']);

Route::get('/settings', ['before' => 'auth', 'uses' => 'UserController@showSettings']);
Route::post('/settings/change_password', ['before' => 'csrf|auth', 'uses' => 'UserController@changePassword']);
Route::post('/settings/change_email', ['before' => 'csrf|auth', 'uses' => 'UserController@changeEmail']);
Route::post('/settings/save/profile', ['before' => 'csrf|auth', 'uses' => 'UserController@saveProfile']);
Route::post('/settings/save/settings', ['before' => 'csrf|auth', 'uses' => 'UserController@saveSettings']);

Route::get('/account/change_email/{token}', 'UserController@confirmEmailChange');

Route::post('/ajax/user/block', ['before' => 'auth', 'uses' => 'UserController@blockUser']);
Route::post('/ajax/user/unblock', ['before' => 'auth', 'uses' => 'UserController@unblockUser']);

Route::post('/ajax/user/observe', ['before' => 'csrf|auth', 'uses' => 'UserController@observeUser']);
Route::post('/ajax/user/unobserve', ['before' => 'csrf|auth', 'uses' => 'UserController@unobserveUser']);

/* Conversations ==================================================================================================== */
Route::get('/conversations', ['before' => 'auth', 'uses' => 'ConversationController@showConversation']);
Route::get('/conversation/{id}', [
    'as' => 'conversation',
    'before' => 'auth',
    'uses' => 'ConversationController@showConversation'
]);

Route::get('/conversations/new', ['before' => 'auth', 'uses' => 'ConversationController@showCreateForm']);
Route::get('/conversations/new/{user}', [
    'as' => 'conversation.new_user',
    'before' => 'auth',
    'uses' => 'ConversationController@showCreateForm'
]);

Route::post('/conversations/new', ['before' => 'auth|anti_flood', 'uses' => 'ConversationController@createConversation']);
Route::post('/conversations/send', ['before' => 'auth|anti_flood', 'uses' => 'ConversationController@sendMessage']);


/* Notifications ==================================================================================================== */
Route::get('/ajax/notification/get/{count}', ['before' => 'auth.ajax', 'uses' => 'NotificationController@showJSONList']);
Route::get('/ajax/notification/get_count', ['before' => 'auth.ajax', 'uses' => 'NotificationController@showJSONCount']);
Route::post('/ajax/notification/mark_all_read', ['before' => 'auth.ajax', 'uses' => 'NotificationController@markAllAsRead']);

Route::get('/notifications', ['before' => 'auth', 'uses' => 'NotificationController@showList']);


/* Contents ========================================================================================================= */
Route::get('/', ['as' => 'global_contents', 'uses' => 'ContentController@showContents']);
Route::get('/rss', ['as' => 'global_contents_rss', 'uses' => 'ContentController@showContents']);

Route::get('/new', ['as' => 'global_contents_new', 'uses' => 'ContentController@showContents']);
Route::get('/new/rss', ['as' => 'global_contents_new_rss', 'uses' => 'ContentController@showContents']);

Route::get('/g/saved', [
    'as' => 'saved_contents',
    'before' => 'auth',
    'uses' => 'SaveController@showContents'
]);

Route::get('/g/saved/new', [
    'as' => 'saved_contents_new',
    'before' => 'auth',
    'uses' => 'SaveController@showContents'
]);

Route::get('/g/{group}', [
    'as' => 'group_contents',
    'uses' => 'ContentController@showContents'
]);

Route::get('/g/{group}/rss', [
    'as' => 'group_contents_rss',
    'uses' => 'ContentController@showContents'
]);

Route::get('/g/{group}/new', [
    'as' => 'group_contents_new',
    'uses' => 'ContentController@showContents'
]);

Route::get('/g/{group}/new/rss', [
    'as' => 'group_contents_new_rss',
    'uses' => 'ContentController@showContents'
]);

Route::get('/g/{group}/deleted', [
    'as' => 'group_contents_deleted',
    'uses' => 'ContentController@showContents'
]);

Route::get('/g/{group}/comments', [
    'as' => 'group_comments',
    'uses' => 'CommentController@showComments'
]);

Route::get('/c/{content}', ['as' => 'content_comments', 'uses' => 'ContentController@showComments']);

Route::get('/c/{content}/frame', ['uses' => 'ContentController@showFrame']);
Route::get('/ajax/content/{content}/embed', 'ContentController@getEmbedCode');

Route::get('/c/{content}/thumbnail', ['before' => 'auth', 'uses' => 'ContentController@chooseThumbnail']);
Route::post('/save_thumbnail', ['before' => 'auth', 'uses' => 'ContentController@saveThumbnail']);

Route::get('/add', ['before' => 'auth', 'uses' => 'ContentController@showAddForm']);
Route::post('/add', ['before' => 'auth|anti_flood', 'uses' => 'ContentController@addContent']);

Route::get('/c/{content}/edit', ['before' => 'auth', 'uses' => 'ContentController@showEditForm']);
Route::post('/c/{content}/edit', ['before' => 'auth', 'uses' => 'ContentController@editContent']);

Route::post('/ajax/content/remove', ['before' => 'auth', 'uses' => 'ContentController@removeContent']);
Route::post('/ajax/mod/content/remove', ['before' => 'auth', 'uses' => 'ContentController@softRemoveContent']);

Route::post('/c/{content}/add_related', ['before' => 'auth|anti_flood', 'uses' => 'RelatedController@addRelated']);
Route::post('/ajax/related/remove', ['before' => 'auth', 'uses' => 'RelatedController@removeRelated']);

Route::get('/c/{content}/{slug}', ['as' => 'content_comments_slug', 'uses' => 'ContentController@showComments']);

Route::post('/c/{content}/add_vote', ['before' => 'auth|anti_flood', 'uses' => 'PollController@addVote']);


/* Comments ========================================================================================================= */

Route::get('/comments', ['as' => 'global_comments', 'uses' => 'CommentController@showComments']);

Route::post('/ajax/comment/add', ['before' => 'auth|anti_flood', 'uses' => 'CommentController@addComment']);
Route::post('/ajax/comment/add/reply', ['before' => 'auth|anti_flood', 'uses' => 'CommentController@addReply']);
Route::post('/ajax/comment/source', ['before' => 'auth', 'uses' => 'CommentController@getCommentSource']);
Route::post('/ajax/comment/edit', ['before' => 'auth', 'uses' => 'CommentController@editComment']);
Route::post('/ajax/comment/remove', ['before' => 'auth', 'uses' => 'CommentController@removeComment']);


/* Entries ========================================================================================================== */
Route::get('/entries', ['as' => 'global_entries', 'uses' => 'EntryController@showEntries']);

Route::get('/g/saved/entries', [
    'as' => 'saved_entries',
    'before' => 'auth',
    'uses' => 'SaveController@showEntries'
]);

Route::get('/g/{group}/entries', [
    'as' => 'group_entries',
    'uses' => 'EntryController@showEntries'
]);

Route::get('/e/{id}', [
    'as' => 'single_entry',
    'uses' => 'EntryController@showEntry'
]);

Route::get('/er/{id}', [
    'as' => 'single_entry_reply',
    'uses' => 'EntryController@showEntry'
]);

Route::get('/ajax/entry/{id}/replies', 'EntryController@getEntryReplies');

Route::post('/ajax/entry/add', ['before' => 'auth|anti_flood', 'uses' => 'EntryController@addEntry']);
Route::post('/ajax/entry/add/reply', ['before' => 'auth|anti_flood', 'uses' => 'EntryController@addReply']);
Route::post('/ajax/entry/source', ['before' => 'auth', 'uses' => 'EntryController@getEntrySource']);
Route::post('/ajax/entry/edit', ['before' => 'auth', 'uses' => 'EntryController@editEntry']);
Route::post('/ajax/entry/remove', ['before' => 'auth', 'uses' => 'EntryController@removeEntry']);




/* Groups =========================================================================================================== */
Route::get('/intro', 'GroupController@showWizard');

Route::get('/groups/list', 'GroupController@showList');
Route::get('/groups.json', 'GroupController@showJSONList');

Route::get('/ajax/groups/subscribed', 'GroupController@showSubscribed');

Route::get('/groups/create', ['before' => 'auth', 'uses' => 'GroupController@showCreateForm']);
Route::post('/groups/create', ['before' => 'auth', 'uses' => 'GroupController@createGroup']);

Route::post('/groups/add_moderator', ['before' => 'auth|anti_flood', 'uses' => 'GroupController@addModerator']);
Route::post('/groups/remove_moderator', ['before' => 'auth', 'uses' => 'GroupController@removeModerator']);

Route::post('/groups/ban', ['before' => 'auth|anti_flood', 'uses' => 'GroupController@addBan']);
Route::post('/groups/unban', ['before' => 'auth', 'uses' => 'GroupController@removeBan']);

Route::get('/g/{group}/moderators', ['as' => 'group_moderators', 'uses' =>'GroupController@showModeratorList']);
Route::get('/g/{group}/banned', ['as' => 'group_banned', 'uses' =>'GroupController@showBannedList']);

Route::get('/g/{group}/settings', ['before' => 'auth', 'as' => 'group_settings', 'uses' =>'GroupController@showSettings']);

Route::post('/g/{group}/settings/save/profile', ['before' => 'auth', 'uses' => 'GroupController@saveProfile']);
Route::post('/g/{group}/settings/save/settings', ['before' => 'auth', 'uses' => 'GroupController@saveSettings']);
Route::post('/g/{group}/settings/save/style', ['before' => 'auth', 'uses' => 'GroupController@saveStyle']);

Route::post('/ajax/group/subscribe', ['before' => 'auth', 'uses' => 'GroupController@subscribeGroup']);
Route::post('/ajax/group/unsubscribe', ['before' => 'auth', 'uses' => 'GroupController@unsubscribeGroup']);

Route::post('/ajax/group/block', ['before' => 'auth', 'uses' => 'GroupController@blockGroup']);
Route::post('/ajax/group/unblock', ['before' => 'auth', 'uses' => 'GroupController@unblockGroup']);

Route::get('/kreator', ['as' => 'wizard', 'before' => 'auth', 'uses' => 'GroupController@wizard']);
Route::get('/kreator/{tag}', ['as' => 'wizard_tag', 'before' => 'auth', 'uses' => 'GroupController@wizard']);

Route::get('/ajax/group/{group}/sidebar', ['before' => 'auth', 'uses' => 'GroupController@getSidebar']);


/* Folders ========================================================================================================== */
Route::get('/f/{folder}', ['as' => 'folder_contents', 'before' => 'auth', 'uses' => 'ContentController@showContents']);
Route::get('/f/{folder}/new', ['as' => 'folder_contents_new', 'before' => 'auth', 'uses' => 'ContentController@showContents']);
Route::get('/f/{folder}/entries', ['as' => 'folder_entries', 'before' => 'auth', 'uses' => 'EntryController@showEntries']);
Route::get('/f/{folder}/comments', ['as' => 'folder_comments', 'before' => 'auth', 'uses' => 'CommentController@showComments']);

Route::get('/u/{user}/f/{folder}', ['as' => 'user_folder_contents', 'uses' => 'ContentController@showContents']);
Route::get('/u/{user}/f/{folder}/new', ['as' => 'user_folder_contents_new', 'uses' => 'ContentController@showContents']);
Route::get('/u/{user}/f/{folder}/entries', ['as' => 'user_folder_entries', 'uses' => 'EntryController@showEntries']);
Route::get('/u/{user}/f/{folder}/comments', ['as' => 'user_folder_comments', 'uses' => 'CommentController@showComments']);

Route::post('/ajax/folder/create', ['before' => 'auth', 'uses' => 'FolderController@createFolder']);
Route::post('/ajax/folder/edit', ['before' => 'auth', 'uses' => 'FolderController@editFolder']);
Route::post('/ajax/folder/remove', ['before' => 'auth', 'uses' => 'FolderController@removeFolder']);

Route::post('/folder/copy', ['before' => 'auth', 'uses' => 'FolderController@copyFolder']);

Route::post('/ajax/folder/add_group', ['before' => 'auth', 'uses' => 'FolderController@addToFolder']);
Route::post('/ajax/folder/remove_group', ['before' => 'auth', 'uses' => 'FolderController@removeFromFolder']);


/* Voting =========================================================================================================== */
Route::post('/ajax/vote/add', ['before' => 'auth.ajax', 'uses' => 'VoteController@addVote']);
Route::post('/ajax/vote/remove', ['before' => 'auth.ajax', 'uses' => 'VoteController@removeVote']);
Route::post('/ajax/vote/get_voters', 'VoteController@getVoters');


/* Saving =========================================================================================================== */
Route::post('/ajax/content/add_save', ['before' => 'auth.ajax', 'uses' => 'SaveController@saveContent']);
Route::post('/ajax/content/remove_save', ['before' => 'auth.ajax', 'uses' => 'SaveController@removeContent']);

Route::post('/ajax/entry/add_save', ['before' => 'auth.ajax', 'uses' => 'SaveController@saveEntry']);
Route::post('/ajax/entry/remove_save', ['before' => 'auth.ajax', 'uses' => 'SaveController@removeEntry']);


/* Utils ============================================================================================================ */
Route::post('/ajax/utils/get_title', ['before' => 'auth.ajax', 'uses' => 'UtilsController@getURLTitle']);

Route::post('/queue/receive/Paxij6bGu18NZTeut4B7T5wKO10jUgQz', function()
{
    return Queue::marshal();
});

/* Static pages ===================================================================================================== */
Route::get('/cookies', function() { return View::make('static.cookies'); });
Route::get('/contact', function() { return View::make('static.contact'); });
Route::get('/guide', function() { return View::make('static.guide'); });
Route::get('/rules', function() { return View::make('static.rules'); });
Route::get('/tag/{tag}', function($tag){ Return View::make('static.tag', array('tag' => $tag)); });

/* Search =========================================================================================================== */
Route::get('/search', ['as' => 'search', 'uses' => 'SearchController@search']);

/* Ranking ========================================================================================================== */
Route::get('/ranking', ['as' => 'ranking', 'uses' => 'RankingController@showRanking']);
Route::get('/g/{group}/ranking', ['as' => 'group_ranking', 'uses' => 'RankingController@showRanking']);