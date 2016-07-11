<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'forum_subscriptions';

    protected $fillable = ['user_id', 'post_id'];

    /**
     * Get the leagues for this game.
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
    }
}