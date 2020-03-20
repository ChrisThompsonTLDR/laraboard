<?php

namespace Christhompsontldr\Laraboard\Models;

use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Models\Traits\LaraboardNode;
use Illuminate\Database\Eloquent\Builder;
use Baum\Node;

class Reply extends Node
{
    use LaraboardNode;

    public $table = 'posts';

    public function __construct()
    {
        $this->table = config('laraboard.table_prefix') . $this->table;

        $this->dispatchesEvents = [
            'saving'  => PostSaving::class,
            'created' => PostCreated::class,
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('forumReply', function(Builder $builder) {
            $builder->where('type', 'Reply');
        });
    }

    public function getThread()
    {
        return Reply::ancestors()->withoutGlobalScope('forumReply')->where('type', 'Thread');
    }

    public function getThreadAttribute()
    {dd($this->getThread());
        return $this->getThread()->first();
    }

/*	public function thread()
	{
//		return Reply::ancestors()->withoutGlobalScope('forumReply')->where('type', 'Thread');
        return $this->withoutGlobalScope('forumReply')->belongsTo('Christhompsontldr\Laraboard\Models\Thread')
	}*/

	public function user()
	{
		return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
	}
}
