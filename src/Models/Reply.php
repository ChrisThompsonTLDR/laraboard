<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

use Christhompsontldr\Laraboard\Models\Post;

class Reply extends Post
{
//    protected $touches = ['thread'];

    protected static function boot()
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
