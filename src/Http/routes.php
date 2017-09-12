<?php
Route::group(['prefix' => config('laraboard.route_prefix', 'forum'), 'middleware' => config('laraboard.route.middleware', 'web')], function () {
    Route::get('/', ['as' => 'forum.index', 'uses' => 'ForumController@index']);
    Route::get('search/{term?}', ['as' => 'forum.search', 'uses' => 'ForumController@search']);

    Route::get('category/create',                        ['as' => 'category.create',     'uses' => 'CategoryController@create']);
    Route::post('category/create',                       ['as' => 'category.store',      'uses' => 'CategoryController@store']);
    Route::get('category/{slug}/edit',                   ['as' => 'category.edit',       'uses' => 'CategoryController@edit'])->where('slug', '[a-z0-9-]+');
    Route::post('category/{slug}/edit',                  ['as' => 'category.update',     'uses' => 'CategoryController@update'])->where('slug', '[a-z0-9-]+');
    Route::get('category/{slug}/reposition/{direction}', ['as' => 'category.reposition', 'uses' => 'CategoryController@reposition'])->where('slug', '[a-z0-9-]+')->where('direction', '(up|down)');

    Route::get('board/create/{category_slug?}',       ['as' => 'board.create',     'uses' => 'BoardController@create'])->where('category_slug', '[a-z0-9-]+');
    Route::post('board/create',                       ['as' => 'board.store',      'uses' => 'BoardController@store']);
    Route::get('board/{slug}/edit',                   ['as' => 'board.edit',       'uses' => 'BoardController@edit'])->where('slug', '[a-z0-9-]+');
    Route::post('board/{slug}/edit',                  ['as' => 'board.update',     'uses' => 'BoardController@update']);
    Route::get('board/{slug}/reposition/{direction}', ['as' => 'board.reposition', 'uses' => 'BoardController@reposition'])->where('slug', '[a-z0-9-]+')->where('direction', '(up|down)');

    Route::get('thread/{slug}/subscribe',   ['as' => 'thread.subscribe',   'uses' => 'ThreadController@subscribe'])->where('slug', '[a-z0-9-]+');
    Route::get('thread/{slug}/unsubscribe', ['as' => 'thread.unsubscribe', 'uses' => 'ThreadController@unsubscribe'])->where('slug', '[a-z0-9-]+');
    Route::get('thread/{slug}/create',      ['as' => 'thread.create',      'uses' => 'ThreadController@create'])->where('slug', '[a-z0-9-]+');
    Route::post('thread/{slug}/create',     ['as' => 'thread.store',       'uses' => 'ThreadController@store'])->where('slug', '[a-z0-9-]+');
    Route::get('thread/{slug}/reply',       ['as' => 'thread.reply',       'uses' => 'ThreadController@reply'])->where('slug', '[a-z0-9-]+');
    Route::get('thread/{slug}/close',       ['as' => 'thread.close',       'uses' => 'ThreadController@close'])->where('slug', '[a-z0-9-]+');
    Route::get('thread/{slug}/open',        ['as' => 'thread.open',        'uses' => 'ThreadController@open'])->where('slug', '[a-z0-9-]+');

    Route::get('post/{id}/edit',   ['as' => 'post.edit',   'uses' => 'PostController@edit'])->where('id', '[0-9]+');
    Route::post('post/{id}/edit',  ['as' => 'post.update', 'uses' => 'PostController@update'])->where('id', '[0-9]+');
    Route::get('post/{id}/delete', ['as' => 'post.delete', 'uses' => 'PostController@delete'])->where('id', '[0-9]+');

    Route::post('thread/{slug}/reply', ['as' => 'thread.store', 'uses' => 'ReplyController@store'])->where('slug', '[a-z0-9-]+');

    Route::get('subscriptions', ['as' => 'subscription.show', 'uses' => 'SubscriptionController@show']);

    // sweeper
    Route::get('{category_slug}/{board_slug}/{slug}/{name_slug?}', ['as' => 'thread.show', 'uses' => 'ThreadController@show'])->where('slug', '[a-z0-9-]+');

    //  sweeper
    Route::get('{category_slug}/{slug}', ['as' => 'board.show', 'uses' => 'BoardController@show'])->where('slug', '[a-z0-9-]+');

    //  sweeper
    Route::get('{slug}', ['as' => 'category.show', 'uses' => 'CategoryController@show'])->where('slug', '[a-z0-9-]+');
});
?>