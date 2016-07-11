<?php
Route::group(['prefix' => 'forum', 'middleware' => 'web'], function () {
    Route::get('/', ['as' => 'forum.index', 'uses' => 'ForumController@index']);

    Route::get('board/create/{category_slug?}', ['as' => 'board.create',   'uses' => 'BoardController@create'])->where('category_slug', '[a-z0-9-]+');
    Route::post('board/create',       ['as' => 'board.store',     'uses' => 'BoardController@store']);

    Route::get('thread/{slug}/subscribe',    ['as' => 'thread.subscribe',   'uses' => 'ThreadController@subscribe'])->where('slug', '[a-z0-9-]+');
    Route::get('thread/{slug}/unsubscribe',  ['as' => 'thread.unsubscribe', 'uses' => 'ThreadController@unsubscribe'])->where('slug', '[a-z0-9-]+');
    Route::get('thread/{slug}/create',       ['as' => 'thread.create',      'uses' => 'ThreadController@create'])->where('slug', '[a-z0-9-]+');
    Route::post('thread/create',             ['as' => 'thread.store',       'uses' => 'ThreadController@store']);
    Route::get('thread/{slug}/reply',        ['as' => 'thread.reply',       'uses' => 'ThreadController@reply'])->where('slug', '[a-z0-9-]+');

    Route::get('reply/{id}/delete', ['as' => 'reply.delete', 'uses' => 'ReplyController@delete'])->where('id', '[0-9]+');

    Route::post('thread/{slug}/reply', ['as' => 'thread.store', 'uses' => 'ReplyController@store'])->where('slug', '[a-z0-9-]+');

    Route::get('subscriptions', ['as' => 'subscription.show',   'uses' => 'SubscriptionController@show']);

    // sweeper
    Route::get('thread/{slug}/{name_slug?}', ['as' => 'thread.show', 'uses' => 'ThreadController@show'])->where('slug', '[a-z0-9-]+');

    //  sweeper
    Route::get('{slug}/{name_slug?}', ['as' => 'board.show',   'uses' => 'BoardController@show'])->where('slug', '[a-z0-9-]+');

    /**
     * TopicsController GET
     */
//    Route::get('topic/{id}/edit', ['as' => 'topic.edit', 'uses' => 'TopicsController@edit']);
//    Route::get('topic/{id}/reply', ['as' => 'topic.reply', 'uses' => 'RepliesController@create']);
//    Route::get('topic/{id}/report', ['as' => 'topic.report', 'uses' => 'TopicsController@report']);
//    Route::get('topic/{id}/ignore', ['as' => 'topic.ignore', 'uses' => 'TopicsController@ignore']);
//    Route::get('topic/{id}/subscribe', ['as' => 'topic.subscribe', 'uses' => 'TopicsController@subscribe']);
//    Route::get('topic/{id}/delete', ['as' => 'topic.delete', 'uses' => 'TopicsController@delete']);
//
//    /**
//     * TopicsController POST
//     */
//    Route::post('board/{id}/create', ['as' => 'board.create', 'uses' => 'TopicController@store']);
//    Route::post('topic/{id}/update', ['as' => 'topic.update', 'uses' => 'TopicController@update']);
//    Route::post('topic/{id}/reply', ['as' => 'topic.reply', 'uses' => 'ReplyController@store']);

    /**
     * TopicsController GET
     * This is here because reasons
     */
//    Route::get('thread/{slug}/{titleSlug?}', ['as' => 'threads.show', 'uses' => 'ThreadController@show'])->where('slug', '[a-z0-9-]+');

    /**
     * UsersController GET
     */
//    Route::get('profile/{id}', 'UsersController@show');
//    Route::get('profile/settings', 'UsersController@settings');
//    Route::get('profile/{id}/topics', 'UsersController@showTopics');
//    Route::get('profile/{id}/replies', 'UsersController@showReplies');
//    Route::get('members', 'UsersController@index');
});