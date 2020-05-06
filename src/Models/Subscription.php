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


    //  RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'post_id');
    }
}
