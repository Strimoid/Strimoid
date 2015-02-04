<?php

Route::group(['prefix' => 'api/v1'], function()
{
    Route::get('/', function() {
        return '<a href="https://developers.strimoid.pl">API Documentation</a>';
    });

    Route::get('/me', ['middleware' => 'auth', 'uses' => 'UserController@showCurrentUser']);

    // Auth
    Route::post('/login', ['uses' => 'AuthController@login']);
    Route::post('/logout', ['middleware' => 'auth', 'uses' => 'AuthController@logout']);

    // Contents
    Route::get('/contents', ['uses' => 'Api\ContentController@index']);
    Route::get('/contents/{content}', 'Api\ContentController@show');
    Route::post('/contents', ['middleware' => 'auth', 'uses' => 'Api\ContentController@store']);
    Route::patch('/contents/{content}', ['middleware' => 'auth', 'uses' => 'Api\ContentController@edit']);
    Route::delete('/contents/{content}', ['middleware' => 'auth', 'uses' => 'ContentController@removeContent']);

    Route::post('/contents/{content}/related', ['middleware' => 'auth', 'uses' => 'RelatedController@store']);
    Route::delete('/related/{related}', ['middleware' => 'auth', 'uses' => 'RelatedController@removeRelated']);

    // Comments
    Route::get('/comments', ['uses' => 'Api\CommentController@index']);
    Route::post('/content/{content}/comment', ['middleware' => 'auth', 'uses' => 'Api\CommentController@store']);
    Route::post('/comment', ['middleware' => 'auth', 'uses' => 'Api\CommentController@storeReply']);
    Route::patch('/comment/{comment}/{reply?}', ['middleware' => 'auth', 'uses' => 'Api\CommentController@edit']);
    Route::delete('/comment/{comment}/{reply?}', ['middleware' => 'auth', 'uses' => 'Api\CommentController@remove']);

    // Entries
    Route::get('/entries', ['uses' => 'Api\EntryController@index']);
    Route::get('/entries/{entry}', 'Api\EntryController@show');
    Route::post('/entries', ['middleware' => 'auth', 'uses' => 'Api\EntryController@store']);
    Route::post('/entries/{entry}/replies', ['middleware' => 'auth', 'uses' => 'Api\EntryController@storeReply']);
    Route::delete('/entries/{entry}', ['middleware' => 'auth', 'uses' => 'Api\EntryController@remove']);

    // Groups
    Route::resource('groups', 'Api\GroupController', ['only' => ['index', 'show']]);

    // Users
    Route::get('/users/{id}', 'UserController@show');

    // Conversations
    Route::get('/conversations', ['middleware' => 'auth', 'uses' => 'ConversationController@getIndex']);
    Route::get('/messages', ['middleware' => 'auth', 'uses' => 'ConversationController@getMessages']);

    // Notifications
    Route::get('/notifications', [
        'middleware' => 'auth', 'uses' => 'NotificationController@listNotifications'
    ]);
    Route::patch('/notification/{notification}', [
        'middleware' => 'auth', 'uses' => 'NotificationController@edit'
    ]);

    Route::post('/notifications/register_gcm', [
        'middleware' => 'auth', 'uses' => 'NotificationController@registerGCM'
    ]);

    // Ranking
    Route::get('/ranking', 'RankingController@getIndex');

    // Voting
    Route::post('/votes', ['middleware' => 'auth', 'uses' => 'VoteController@addVote']);
    Route::delete('/votes', ['middleware' => 'auth', 'uses' => 'VoteController@removeVote']);
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
Route::post('/register', ['middleware' => 'guest','uses' => 'UserController@processRegistration']);

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

Route::post('/settings/change_password', ['middleware' => 'auth', 'uses' => 'UserController@changePassword']);
Route::post('/settings/change_email', ['middleware' => 'auth', 'uses' => 'UserController@changeEmail']);
Route::post('/settings/save/profile', ['middleware' => 'auth', 'uses' => 'UserController@saveProfile']);

Route::get('/account/change_email/{token}', 'UserController@confirmEmailChange');

Route::post('/ajax/user/block', ['middleware' => 'auth', 'uses' => 'UserController@blockUser']);
Route::post('/ajax/user/unblock', ['middleware' => 'auth', 'uses' => 'UserController@unblockUser']);

Route::post('/ajax/user/observe', ['middleware' => 'auth', 'uses' => 'UserController@observeUser']);
Route::post('/ajax/user/unobserve', ['middleware' => 'auth', 'uses' => 'UserController@unobserveUser']);

// Settings
Route::get('/settings', ['middleware' => 'auth', 'uses' => 'SettingsController@showSettings']);
Route::post('/settings/save/settings', ['middleware' => 'auth', 'uses' => 'SettingsController@saveSettings']);

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
Route::get('/', ['as' => 'global_contents', 'uses' => 'ContentController@showContentsFromGroup']);
Route::get('/rss', ['as' => 'global_contents_rss', 'uses' => 'ContentController@showContentsFromGroup']);

Route::get('/new', ['as' => 'global_contents_new', 'uses' => 'ContentController@showContentsFromGroup']);
Route::get('/new/rss', ['as' => 'global_contents_new_rss', 'uses' => 'ContentController@showContentsFromGroup']);

Route::get('/g/{group}', [
    'as' => 'group_contents',
    'uses' => 'ContentController@showContentsFromGroup'
]);

Route::get('/g/{group}/rss', [
    'as' => 'group_contents_rss',
    'uses' => 'ContentController@showContentsFromGroup'
]);

Route::get('/g/{group}/new', [
    'as' => 'group_contents_new',
    'uses' => 'ContentController@showContentsFromGroup'
]);

Route::get('/g/{group}/new/rss', [
    'as' => 'group_contents_new_rss',
    'uses' => 'ContentController@showContentsFromGroup'
]);

Route::get('/g/{group}/deleted', [
    'as' => 'group_contents_deleted',
    'uses' => 'ContentController@showContentsFromGroup'
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

Route::get('/comments', ['as' => 'global_comments', 'uses' => 'CommentController@showCommentsFromGroup']);

Route::get('/g/{group}/comments', [
    'as' => 'group_comments',
    'uses' => 'CommentController@showCommentsFromGroup'
]);

Route::post('/ajax/comment/add', ['middleware' => 'auth', 'uses' => 'CommentController@addComment']);
Route::post('/ajax/comment/add/reply', ['middleware' => 'auth', 'uses' => 'CommentController@addReply']);
Route::post('/ajax/comment/source', ['middleware' => 'auth', 'uses' => 'CommentController@getCommentSource']);
Route::post('/ajax/comment/edit', ['middleware' => 'auth', 'uses' => 'CommentController@editComment']);
Route::post('/ajax/comment/remove', ['middleware' => 'auth', 'uses' => 'CommentController@removeComment']);


/* Entries ========================================================================================================== */
Route::get('/entries', ['as' => 'global_entries', 'uses' => 'EntryController@showEntriesFromGroup']);

Route::get('/g/{group}/entries', [
    'as' => 'group_entries',
    'uses' => 'EntryController@showEntriesFromGroup'
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
Route::get('/f/{folder}', ['as' => 'folder_contents', 'middleware' => 'auth', 'uses' => 'ContentController@showContentsFromFolder']);
Route::get('/f/{folder}/new', ['as' => 'folder_contents_new', 'middleware' => 'auth', 'uses' => 'ContentController@showContentsFromFolder']);
Route::get('/f/{folder}/entries', ['as' => 'folder_entries', 'middleware' => 'auth', 'uses' => 'EntryController@showEntriesFromFolder']);
Route::get('/f/{folder}/comments', ['as' => 'folder_comments', 'middleware' => 'auth', 'uses' => 'CommentController@showCommentsFromFolder']);

Route::get('/u/{user}/f/{folder}', ['as' => 'user_folder_contents', 'uses' => 'ContentController@showContentsFromFolder']);
Route::get('/u/{user}/f/{folder}/new', ['as' => 'user_folder_contents_new', 'uses' => 'ContentController@showContentsFromFolder']);
Route::get('/u/{user}/f/{folder}/entries', ['as' => 'user_folder_entries', 'uses' => 'EntryController@showEntriesFromFolder']);
Route::get('/u/{user}/f/{folder}/comments', ['as' => 'user_folder_comments', 'uses' => 'CommentController@showCommentsFromFolder']);

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

/* Static pages ===================================================================================================== */
Route::get('/cookies', function() { return view('static.cookies'); });
Route::get('/contact', function() { return view('static.contact'); });
Route::get('/guide', function() { return view('static.guide'); });
Route::get('/rules', function() { return view('static.rules'); });
Route::get('/tag/{tag}', function($tag){ return view('static.tag', ['tag' => $tag]); });

/* Search =========================================================================================================== */
Route::get('/search', ['as' => 'search', 'uses' => 'SearchController@search']);

/* Ranking ========================================================================================================== */
Route::get('/ranking', ['as' => 'ranking', 'uses' => 'RankingController@showRanking']);
Route::get('/g/{group}/ranking', ['as' => 'group_ranking', 'uses' => 'RankingController@showRanking']);