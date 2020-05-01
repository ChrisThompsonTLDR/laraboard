<?php

use Christhompsontldr\Laraboard\Http\Controllers\BoardController;
use Christhompsontldr\Laraboard\Http\Controllers\CategoryController;
use Christhompsontldr\Laraboard\Http\Controllers\ForumController;
use Christhompsontldr\Laraboard\Http\Controllers\PostController;
use Christhompsontldr\Laraboard\Http\Controllers\ReplyController;
use Christhompsontldr\Laraboard\Http\Controllers\SubscriptionController;
use Christhompsontldr\Laraboard\Http\Controllers\ThreadController;

Route::group(['prefix' => config('laraboard.route_prefix', 'forum'), 'middleware' => config('laraboard.route.middleware', 'web')], function () {
    Route::get('/', [ForumController::class, 'index'])->name('forum.index');
    Route::get('search/{term?}', [ForumController::class, 'search'])->name('forum.search');

    Route::get('category/create',                                     [CategoryController::class, 'create'])    ->name('category.create');
    Route::post('category/create',                                    [CategoryController::class, 'store'])     ->name('category.store');
    Route::get('category/{laraboardCategory}/edit',                   [CategoryController::class, 'edit'])      ->name('category.edit');
    Route::post('category/{laraboardCategory}/edit',                  [CategoryController::class, 'update'])    ->name('category.update');
    Route::get('category/{laraboardCategory}/reposition/{direction}', [CategoryController::class, 'reposition'])->name('category.reposition');

    Route::get('board/create/{laraboardCategory?}',                [BoardController::class, 'create'])    ->name('board.create');
    Route::post('board/create',                                    [BoardController::class, 'store'])     ->name('board.store');
    Route::get('board/{laraboardCategory}/edit',                   [BoardController::class, 'edit'])      ->name('board.edit');
    Route::post('board/{laraboardCategory}/edit',                  [BoardController::class, 'update'])    ->name('board.update');
    Route::get('board/{laraboardCategory}/reposition/{direction}', [BoardController::class, 'reposition'])->name('board.reposition');

    Route::get('thread/{laraboardBoard}/subscribe',   [ThreadController::class, 'subscribe'])  ->name('thread.subscribe');
    Route::get('thread/{laraboardBoard}/unsubscribe', [ThreadController::class, 'unsubscribe'])->name('thread.unsubscribe');
    Route::get('thread/{laraboardBoard}/create',      [ThreadController::class, 'create'])     ->name('thread.create');
    Route::post('thread/{laraboardBoard}/create',     [ThreadController::class, 'store'])      ->name('thread.store');
    Route::get('thread/{laraboardBoard}/reply',       [ThreadController::class, 'reply'])      ->name('thread.reply');
    Route::get('thread/{laraboardBoard}/close',       [ThreadController::class, 'close'])      ->name('thread.close');
    Route::get('thread/{laraboardBoard}/open',        [ThreadController::class, 'open'])       ->name('thread.open');

    Route::get('post/{laraboardPost}/edit',   [PostController::class, 'edit'])  ->name('post.edit');
    Route::post('post/{laraboardPost}/edit',  [PostController::class, 'update'])->name('post.update');
    Route::get('post/{laraboardPost}/delete', [PostController::class, 'delete'])->name('post.delete');

    Route::post('thread/{laraboardThread}/reply', [ReplyController::class, 'store'])->name('thread.store');

    Route::get('subscriptions', [SubscriptionController::class, 'show'])->name('subscription.show');

    // sweeper
    Route::get('{laraboardCategory}/{laraboardBoard}/{laraboardThread}/{slug}', [ThreadController::class, 'show'])->name('thread.show');

    //  sweeper
    Route::get('{laraboardCategory}/{laraboardBoard}', [BoardController::class, 'show'])->name('board.show');

    //  sweeper
    Route::get('{laraboardCategory}', [CategoryController::class, 'show'])->name('category.show');
});
