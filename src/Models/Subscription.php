<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = ['user_id', 'post_id'];

    public function __construct()
    {
        $this->table = config('laraboard.table_prefix') . $this->table;

        parent::__construct();
    }

    /**
     * Get the leagues for this game.
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
    }
}