<?php

// @codingStandardsIgnoreFile

/** @var Dingo\Api\Routing\Router $api */

$api->get('/', fn () => '<a href="https://developers.strm.pl">API Documentation</a>');

$api->get('me', ['middleware' => 'auth', 'uses' => 'UserController@showCurrentUser']);

// Auth
$api->post('login', ['uses' => 'AuthController@login']);
$api->post('logout', ['middleware' => 'auth', 'uses' => 'AuthController@logout']);

// Contents
$api->get('contents', ['uses' => 'ContentController@index']);
$api->get('contents/{content}', 'ContentController@show');
$api->post('contents', ['middleware' => 'auth', 'uses' => 'ContentController@store']);
$api->patch('contents/{content}', ['middleware' => 'auth', 'uses' => 'ContentController@edit']);
$api->delete('contents/{content}', ['middleware' => 'auth', 'uses' => 'ContentController@removeContent']);

$api->post('contents/{content}/related', ['middleware' => 'auth', 'uses' => 'Content\RelatedController@store']);
$api->delete('related/{related}', ['middleware' => 'auth', 'uses' => 'Content\RelatedController@removeRelated']);

// Comments
$api->get('comments', ['uses' => 'CommentController@index']);
$api->post('content/{content}/comment', ['middleware' => 'auth', 'uses' => 'CommentController@store']);
$api->post('comment', ['middleware' => 'auth', 'uses' => 'CommentController@storeReply']);
$api->patch('comment/{comment}/{reply?}', ['middleware' => 'auth', 'uses' => 'CommentController@edit']);
$api->delete('comment/{comment}/{reply?}', ['middleware' => 'auth', 'uses' => 'CommentController@remove']);

// Entries
$api->get('entries', ['uses' => 'EntryController@index']);
$api->get('entries/{entry}', 'EntryController@show');
$api->post('entries', ['middleware' => 'auth', 'uses' => 'EntryController@store']);
$api->post('entries/{entry}/replies', ['middleware' => 'auth', 'uses' => 'EntryController@storeReply']);
$api->delete('entries/{entry}', ['middleware' => 'auth', 'uses' => 'EntryController@remove']);

// Groups
$api->resource('groups', 'GroupController', ['only' => ['index', 'show']]);

// Users
$api->get('users/{id}', 'UserController@show');

// Conversations
$api->get('conversations', ['middleware' => 'auth', 'uses' => 'ConversationController@getIndex']);
$api->get('messages', ['middleware' => 'auth', 'uses' => 'ConversationController@getMessages']);

// Notifications
$api->get('notifications', [
    'middleware' => 'auth', 'uses' => 'NotificationController@listNotifications',
]);
$api->patch('notification/{notification}', [
    'middleware' => 'auth', 'uses' => 'NotificationController@edit',
]);

$api->post('notifications/register_gcm', [
    'middleware' => 'auth', 'uses' => 'NotificationController@registerGCM',
]);

// Ranking
$api->get('ranking', 'RankingController@getIndex');

// Voting
$api->post('votes', ['middleware' => 'auth', 'uses' => 'VoteController@addVote']);
$api->delete('votes', ['middleware' => 'auth', 'uses' => 'VoteController@removeVote']);
