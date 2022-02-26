<?php

// @codingStandardsIgnoreFile

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => '<a href="https://developers.strm.pl">API Documentation</a>');

Route::get('me', ['middleware' => 'auth', 'uses' => 'UserController@showCurrentUser']);

// Auth
Route::post('login', ['uses' => 'AuthController@login']);
Route::post('logout', ['middleware' => 'auth', 'uses' => 'AuthController@logout']);

// Contents
Route::get('contents', ['uses' => 'ContentController@index']);
Route::get('contents/{content}', 'ContentController@show');
Route::post('contents', ['middleware' => 'auth', 'uses' => 'ContentController@store']);
Route::patch('contents/{content}', ['middleware' => 'auth', 'uses' => 'ContentController@edit']);
Route::delete('contents/{content}', ['middleware' => 'auth', 'uses' => 'ContentController@removeContent']);

Route::post('contents/{content}/related', ['middleware' => 'auth', 'uses' => 'Content\RelatedController@store']);
Route::delete('related/{related}', ['middleware' => 'auth', 'uses' => 'Content\RelatedController@removeRelated']);

// Comments
Route::get('comments', ['uses' => 'CommentController@index']);
Route::post('content/{content}/comment', ['middleware' => 'auth', 'uses' => 'CommentController@store']);
Route::post('comment', ['middleware' => 'auth', 'uses' => 'CommentController@storeReply']);
Route::patch('comment/{comment}/{reply?}', ['middleware' => 'auth', 'uses' => 'CommentController@edit']);
Route::delete('comment/{comment}/{reply?}', ['middleware' => 'auth', 'uses' => 'CommentController@remove']);

// Entries
Route::get('entries', ['uses' => 'EntryController@index']);
Route::get('entries/{entry}', 'EntryController@show');
Route::post('entries', ['middleware' => 'auth', 'uses' => 'EntryController@store']);
Route::post('entries/{entry}/replies', ['middleware' => 'auth', 'uses' => 'EntryController@storeReply']);
Route::delete('entries/{entry}', ['middleware' => 'auth', 'uses' => 'EntryController@remove']);

// Groups
Route::resource('groups', 'GroupController', ['only' => ['index', 'show']]);

// Users
Route::get('users/{id}', 'UserController@show');

// Conversations
Route::get('conversations', ['middleware' => 'auth', 'uses' => 'ConversationController@getIndex']);
Route::get('messages', ['middleware' => 'auth', 'uses' => 'ConversationController@getMessages']);

// Notifications
Route::get('notifications', [
    'middleware' => 'auth', 'uses' => 'NotificationController@listNotifications',
]);
Route::patch('notification/{notification}', [
    'middleware' => 'auth', 'uses' => 'NotificationController@edit',
]);

Route::post('notifications/register_gcm', [
    'middleware' => 'auth', 'uses' => 'NotificationController@registerGCM',
]);

// Ranking
Route::get('ranking', 'RankingController@getIndex');

// Voting
Route::post('votes', ['middleware' => 'auth', 'uses' => 'VoteController@addVote']);
Route::delete('votes', ['middleware' => 'auth', 'uses' => 'VoteController@removeVote']);
