<?php

Route::group(['prefix' => 'api/v1'], function()
{
    Route::get('/', function() {
        return '<a href="https://developers.strimoid.pl">API Documentation</a>';
    });

    Route::get('/me', ['middleware' => 'oauth:basic', 'uses' => 'UserController@showCurrentUser']);

    // Auth
    Route::post('/login', ['uses' => 'AuthController@login']);
    Route::post('/logout', ['middleware' => 'auth', 'uses' => 'AuthController@logout']);

    // Contents
    Route::get('/contents', ['middleware' => 'oauth', 'uses' => 'ContentController@getIndex']);
    Route::get('/contents/{content}', 'ContentController@show');
    Route::post('/contents', ['middleware' => 'oauth:contents', 'uses' => 'ContentController@store']);
    Route::patch('/contents/{content}', ['middleware' => 'oauth:contents', 'uses' => 'ContentController@edit']);
    Route::delete('/contents/{content}', ['middleware' => 'oauth:contents', 'uses' => 'ContentController@removeContent']);

    Route::post('/contents/{content}/related', ['middleware' => 'oauth:contents', 'uses' => 'RelatedController@store']);
    Route::delete('/related/{related}', ['middleware' => 'oauth:contents', 'uses' => 'RelatedController@removeRelated']);

    // Comments
    Route::get('/comments', ['uses' => 'CommentController@index']);
    Route::post('/content/{content}/comment', ['middleware' => 'oauth:comments', 'uses' => 'CommentController@store']);
    Route::post('/comment', ['middleware' => 'oauth:comments', 'uses' => 'CommentController@storeReply']);
    Route::patch('/comment/{comment}/{reply?}', ['middleware' => 'oauth:comments', 'uses' => 'CommentController@edit']);
    Route::delete('/comment/{comment}/{reply?}', ['middleware' => 'oauth:comments', 'uses' => 'CommentController@remove']);

    // Entries
    Route::get('/entries', ['middleware' => 'oauth', 'uses' => 'EntryController@getIndex']);
    Route::get('/entries/{entry}', 'EntryController@show');
    Route::post('/entries', ['middleware' => 'oauth:entries', 'uses' => 'EntryController@store']);
    Route::post('/entries/{entry}/replies', ['middleware' => 'oauth:entries', 'uses' => 'EntryController@storeReply']);
    Route::delete('/entries/{entry}', ['middleware' => 'oauth:entries', 'uses' => 'EntryController@remove']);

    // Groups
    Route::resource('groups', 'GroupController', ['only' => ['index', 'show']]);

    // Users
    Route::get('/users/{id}', 'UserController@show');

    // Conversations
    Route::get('/conversations', ['middleware' => 'oauth:conversations', 'uses' => 'ConversationController@getIndex']);
    Route::get('/messages', ['middleware' => 'oauth:conversations', 'uses' => 'ConversationController@getMessages']);

    // Notifications
    Route::get('/notifications', [
        'middleware' => 'oauth:notifications', 'uses' => 'NotificationController@listNotifications'
    ]);
    Route::patch('/notification/{notification}', [
        'middleware' => 'oauth:notifications', 'uses' => 'NotificationController@edit'
    ]);

    Route::post('/notifications/register_gcm', [
        'middleware' => 'oauth:notifications', 'uses' => 'NotificationController@registerGCM'
    ]);

    // Ranking
    Route::get('/ranking', 'RankingController@getIndex');

    // Voting
    Route::post('/votes', ['middleware' => 'oauth:votes', 'uses' => 'VoteController@addVote']);
    Route::delete('/votes', ['middleware' => 'oauth:votes', 'uses' => 'VoteController@removeVote']);
});

/* OAuth2 =========================================================================================================== */
Route::post('/oauth2/token', 'OAuthController@getAccessToken');

Route::get('/oauth2/authorize', ['middleware' => 'auth', 'uses' => 'OAuthController@authorizationForm']);
Route::post('/oauth2/authorize', ['middleware' => 'auth', 'uses' => 'OAuthController@authorize']);

Route::get('/oauth2/apps', ['middleware' => 'auth', 'uses' => 'OAuthController@listApps']);

Route::get('/oauth2/add_app', ['middleware' => 'auth', 'uses' => 'OAuthController@addAppForm']);
Route::post('/oauth2/add_app', ['middleware' => 'auth', 'uses' => 'OAuthController@addApp']);

/* Users ============================================================================================================ */
Route::post('/me/blocked_domain/{domain}', ['middleware' => 'auth', 'uses' => 'UserController@blockDomain']);
Route::delete('/me/blocked_domain/{domain}', ['middleware' => 'auth', 'uses' => 'UserController@unblockDomain']);

Route::get('/users.json', 'UserController@showJSONList');

Route::get('/register', ['middleware' => 'guest', 'uses' => 'UserController@showRegisterForm']);
Route::post('/register', ['middleware' => 'anti_spam|guest','uses' => 'UserController@processRegistration']);

Route::get('/login', ['middleware' => 'guest', 'as' => 'login_form', 'uses' => 'UserController@showLoginForm']);
Route::post('/login', ['middleware' => 'guest', 'uses' => 'UserController@login']);
Route::post('/logout', ['middleware' => 'auth', 'uses' => 'UserController@logout']);

Route::get('/remind', 'UserController@remindPassword');
Route::post('/remind', 'UserController@remindPassword');

Route::get('/password/reset/{token}', 'UserController@showPasswordResetForm');
Route::post('/password/reset/{token}', 'UserController@resetPassword');

Route::get('/account/activate/{token}', 'UserController@activateAccount');

Route::get('/account/remove', 'UserController@showRemoveAccountForm');
Route::post('/account/remove', 'UserController@removeAccount');

Route::get('/u/{username}', ['as' => 'user_profile', 'uses' =>'UserController@showProfile']);

Route::get('/u/{username}/{type}', ['as' => 'user_profile.type_filter', 'uses' =>'UserController@showProfile']);

Route::get('/settings', ['middleware' => 'auth', 'uses' => 'UserController@showSettings']);
Route::post('/settings/change_password', ['middleware' => 'auth', 'uses' => 'UserController@changePassword']);
Route::post('/settings/change_email', ['middleware' => 'auth', 'uses' => 'UserController@changeEmail']);
Route::post('/settings/save/profile', ['middleware' => 'auth', 'uses' => 'UserController@saveProfile']);
Route::post('/settings/save/settings', ['middleware' => 'auth', 'uses' => 'UserController@saveSettings']);

Route::get('/account/change_email/{token}', 'UserController@confirmEmailChange');

Route::post('/ajax/user/block', ['middleware' => 'auth', 'uses' => 'UserController@blockUser']);
Route::post('/ajax/user/unblock', ['middleware' => 'auth', 'uses' => 'UserController@unblockUser']);

Route::post('/ajax/user/observe', ['middleware' => 'auth', 'uses' => 'UserController@observeUser']);
Route::post('/ajax/user/unobserve', ['middleware' => 'auth', 'uses' => 'UserController@unobserveUser']);

/* Conversations ==================================================================================================== */
Route::get('/conversations', ['middleware' => 'auth', 'uses' => 'ConversationController@showConversation']);
Route::get('/conversation/{id}', [
    'as' => 'conversation',
    'middleware' => 'auth',
    'uses' => 'ConversationController@showConversation'
]);

Route::get('/conversations/new', ['middleware' => 'auth', 'uses' => 'ConversationController@showCreateForm']);
Route::get('/conversations/new/{user}', [
    'as' => 'conversation.new_user',
    'middleware' => 'auth',
    'uses' => 'ConversationController@showCreateForm'
]);

Route::post('/conversations/new', ['middleware' => 'auth', 'uses' => 'ConversationController@createConversation']);
Route::post('/conversations/send', ['middleware' => 'auth', 'uses' => 'ConversationController@sendMessage']);


/* Notifications ==================================================================================================== */
Route::get('/ajax/notification/get/{count}', ['middleware' => 'auth', 'uses' => 'NotificationController@showJSONList']);
Route::get('/ajax/notification/get_count', ['middleware' => 'auth', 'uses' => 'NotificationController@showJSONCount']);
Route::post('/ajax/notification/mark_all_read', ['middleware' => 'auth', 'uses' => 'NotificationController@markAllAsRead']);

Route::get('/notifications', ['middleware' => 'auth', 'uses' => 'NotificationController@showList']);


/* Contents ========================================================================================================= */
Route::get('/', ['as' => 'global_contents', 'uses' => 'ContentController@showContents']);
Route::get('/rss', ['as' => 'global_contents_rss', 'uses' => 'ContentController@showContents']);

Route::get('/new', ['as' => 'global_contents_new', 'uses' => 'ContentController@showContents']);
Route::get('/new/rss', ['as' => 'global_contents_new_rss', 'uses' => 'ContentController@showContents']);

Route::get('/g/saved', [
    'as' => 'saved_contents',
    'middleware' => 'auth',
    'uses' => 'SaveController@showContents'
]);

Route::get('/g/saved/new', [
    'as' => 'saved_contents_new',
    'middleware' => 'auth',
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

Route::get('/c/{content}/thumbnail', ['middleware' => 'auth', 'uses' => 'ContentController@chooseThumbnail']);
Route::post('/save_thumbnail', ['middleware' => 'auth', 'uses' => 'ContentController@saveThumbnail']);

Route::get('/add', ['middleware' => 'auth', 'uses' => 'ContentController@showAddForm']);
Route::post('/add', ['middleware' => 'auth', 'uses' => 'ContentController@addContent']);

Route::get('/c/{content}/edit', ['middleware' => 'auth', 'uses' => 'ContentController@showEditForm']);
Route::post('/c/{content}/edit', ['middleware' => 'auth', 'uses' => 'ContentController@editContent']);

Route::post('/ajax/content/remove', ['middleware' => 'auth', 'uses' => 'ContentController@removeContent']);
Route::post('/ajax/mod/content/remove', ['middleware' => 'auth', 'uses' => 'ContentController@softRemoveContent']);

Route::post('/c/{content}/add_related', ['middleware' => 'auth', 'uses' => 'RelatedController@addRelated']);
Route::post('/ajax/related/remove', ['middleware' => 'auth', 'uses' => 'RelatedController@removeRelated']);

Route::get('/c/{content}/{slug}', ['as' => 'content_comments_slug', 'uses' => 'ContentController@showComments']);

Route::post('/c/{content}/add_vote', ['middleware' => 'auth', 'uses' => 'PollController@addVote']);


/* Comments ========================================================================================================= */

Route::get('/comments', ['as' => 'global_comments', 'uses' => 'CommentController@showComments']);

Route::post('/ajax/comment/add', ['middleware' => 'auth', 'uses' => 'CommentController@addComment']);
Route::post('/ajax/comment/add/reply', ['middleware' => 'auth', 'uses' => 'CommentController@addReply']);
Route::post('/ajax/comment/source', ['middleware' => 'auth', 'uses' => 'CommentController@getCommentSource']);
Route::post('/ajax/comment/edit', ['middleware' => 'auth', 'uses' => 'CommentController@editComment']);
Route::post('/ajax/comment/remove', ['middleware' => 'auth', 'uses' => 'CommentController@removeComment']);


/* Entries ========================================================================================================== */
Route::get('/entries', ['as' => 'global_entries', 'uses' => 'EntryController@showEntries']);

Route::get('/g/saved/entries', [
    'as' => 'saved_entries',
    'middleware' => 'auth',
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

Route::post('/ajax/entry/add', ['middleware' => 'auth', 'uses' => 'EntryController@addEntry']);
Route::post('/ajax/entry/add/reply', ['middleware' => 'auth', 'uses' => 'EntryController@addReply']);
Route::post('/ajax/entry/source', ['middleware' => 'auth', 'uses' => 'EntryController@getEntrySource']);
Route::post('/ajax/entry/edit', ['middleware' => 'auth', 'uses' => 'EntryController@editEntry']);
Route::post('/ajax/entry/remove', ['middleware' => 'auth', 'uses' => 'EntryController@removeEntry']);




/* Groups =========================================================================================================== */
Route::get('/intro', 'GroupController@showWizard');

Route::get('/groups/list', 'GroupController@showList');
Route::get('/groups.json', 'GroupController@showJSONList');

Route::get('/ajax/groups/subscribed', 'GroupController@showSubscribed');

Route::get('/groups/create', ['middleware' => 'auth', 'uses' => 'GroupController@showCreateForm']);
Route::post('/groups/create', ['middleware' => 'auth', 'uses' => 'GroupController@createGroup']);

Route::post('/groups/add_moderator', ['middleware' => 'auth', 'uses' => 'GroupController@addModerator']);
Route::post('/groups/remove_moderator', ['middleware' => 'auth', 'uses' => 'GroupController@removeModerator']);

Route::post('/groups/ban', ['middleware' => 'auth', 'uses' => 'GroupController@addBan']);
Route::post('/groups/unban', ['middleware' => 'auth', 'uses' => 'GroupController@removeBan']);

Route::get('/g/{group}/moderators', ['as' => 'group_moderators', 'uses' =>'GroupController@showModeratorList']);
Route::get('/g/{group}/banned', ['as' => 'group_banned', 'uses' =>'GroupController@showBannedList']);

Route::get('/g/{group}/settings', ['middleware' => 'auth', 'as' => 'group_settings', 'uses' =>'GroupController@showSettings']);

Route::post('/g/{group}/settings/save/profile', ['middleware' => 'auth', 'uses' => 'GroupController@saveProfile']);
Route::post('/g/{group}/settings/save/settings', ['middleware' => 'auth', 'uses' => 'GroupController@saveSettings']);
Route::post('/g/{group}/settings/save/style', ['middleware' => 'auth', 'uses' => 'GroupController@saveStyle']);

Route::post('/ajax/group/subscribe', ['middleware' => 'auth', 'uses' => 'GroupController@subscribeGroup']);
Route::post('/ajax/group/unsubscribe', ['middleware' => 'auth', 'uses' => 'GroupController@unsubscribeGroup']);

Route::post('/ajax/group/block', ['middleware' => 'auth', 'uses' => 'GroupController@blockGroup']);
Route::post('/ajax/group/unblock', ['middleware' => 'auth', 'uses' => 'GroupController@unblockGroup']);

Route::get('/kreator', ['as' => 'wizard', 'middleware' => 'auth', 'uses' => 'GroupController@wizard']);
Route::get('/kreator/{tag}', ['as' => 'wizard_tag', 'middleware' => 'auth', 'uses' => 'GroupController@wizard']);

Route::get('/ajax/group/{group}/sidebar', ['middleware' => 'auth', 'uses' => 'GroupController@getSidebar']);


/* Folders ========================================================================================================== */
Route::get('/f/{folder}', ['as' => 'folder_contents', 'middleware' => 'auth', 'uses' => 'ContentController@showContents']);
Route::get('/f/{folder}/new', ['as' => 'folder_contents_new', 'middleware' => 'auth', 'uses' => 'ContentController@showContents']);
Route::get('/f/{folder}/entries', ['as' => 'folder_entries', 'middleware' => 'auth', 'uses' => 'EntryController@showEntries']);
Route::get('/f/{folder}/comments', ['as' => 'folder_comments', 'middleware' => 'auth', 'uses' => 'CommentController@showComments']);

Route::get('/u/{user}/f/{folder}', ['as' => 'user_folder_contents', 'uses' => 'ContentController@showContents']);
Route::get('/u/{user}/f/{folder}/new', ['as' => 'user_folder_contents_new', 'uses' => 'ContentController@showContents']);
Route::get('/u/{user}/f/{folder}/entries', ['as' => 'user_folder_entries', 'uses' => 'EntryController@showEntries']);
Route::get('/u/{user}/f/{folder}/comments', ['as' => 'user_folder_comments', 'uses' => 'CommentController@showComments']);

Route::post('/ajax/folder/create', ['middleware' => 'auth', 'uses' => 'FolderController@createFolder']);
Route::post('/ajax/folder/edit', ['middleware' => 'auth', 'uses' => 'FolderController@editFolder']);
Route::post('/ajax/folder/remove', ['middleware' => 'auth', 'uses' => 'FolderController@removeFolder']);

Route::post('/folder/copy', ['middleware' => 'auth', 'uses' => 'FolderController@copyFolder']);

Route::post('/ajax/folder/add_group', ['middleware' => 'auth', 'uses' => 'FolderController@addToFolder']);
Route::post('/ajax/folder/remove_group', ['middleware' => 'auth', 'uses' => 'FolderController@removeFromFolder']);


/* Voting =========================================================================================================== */
Route::post('/ajax/vote/add', ['middleware' => 'auth', 'uses' => 'VoteController@addVote']);
Route::post('/ajax/vote/remove', ['middleware' => 'auth', 'uses' => 'VoteController@removeVote']);
Route::post('/ajax/vote/get_voters', 'VoteController@getVoters');


/* Saving =========================================================================================================== */
Route::post('/ajax/content/add_save', ['middleware' => 'auth', 'uses' => 'SaveController@saveContent']);
Route::post('/ajax/content/remove_save', ['middleware' => 'auth', 'uses' => 'SaveController@removeContent']);

Route::post('/ajax/entry/add_save', ['middleware' => 'auth', 'uses' => 'SaveController@saveEntry']);
Route::post('/ajax/entry/remove_save', ['middleware' => 'auth', 'uses' => 'SaveController@removeEntry']);


/* Utils ============================================================================================================ */
Route::post('/ajax/utils/get_title', ['middleware' => 'auth', 'uses' => 'UtilsController@getURLTitle']);

Route::post('/queue/receive/Paxij6bGu18NZTeut4B7T5wKO10jUgQz', function()
{
    return Queue::marshal();
});

/* Static pages ===================================================================================================== */
Route::get('/cookies', function() { return view('static.cookies'); });
Route::get('/contact', function() { return view('static.contact'); });
Route::get('/guide', function() { return view('static.guide'); });
Route::get('/rules', function() { return view('static.rules'); });
Route::get('/tag/{tag}', function($tag){ Return view('static.tag', ['tag' => $tag]); });

/* Search =========================================================================================================== */
Route::get('/search', ['as' => 'search', 'uses' => 'SearchController@search']);

/* Ranking ========================================================================================================== */
Route::get('/ranking', ['as' => 'ranking', 'uses' => 'RankingController@showRanking']);
Route::get('/g/{group}/ranking', ['as' => 'group_ranking', 'uses' => 'RankingController@showRanking']);