<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

use Christhompsontldr\Laraboard\Models\Post;

use Christhompsontldr\Laraboard\Models\Traits\Ordered;

class Thread extends Post
{
    use Ordered;

    protected $touches = ['board'];

    public static $sortOrder = ['updated_at' => 'desc'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('forumThread', function(Builder $builder) {
            $builder->where('type', 'Thread');
        });
    }

    //  RELATIONSHIPS

    public function board()
    {
        return $this->belongsTo('Christhompsontldr\Laraboard\Models\Board', 'parent_id', 'id');
    }

    public function replies()
    {
    	return $this->hasMany('Christhompsontldr\Laraboard\Models\Reply', 'parent_id', 'id');
    }

    public function user()
    {
    	return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
    }

    //  MUTATORS

    public function getLastPageAttribute($field)
    {
        return (int) ceil(($this->replies->count() + 1) / config('laraboard.post.limit', 15));
    }

    public function getLastPageRouteAttribute($field)
    {
        $route = [
            $this->board->category->slug,
            $this->board->slug,
            $this->slug,
            $this->name_slug
        ];

        if ($this->lastPage > 1) {
            $route['page'] = $this->lastPage;
        }

        return $route;
    }

    public function getRouteAttribute($field)
    {
        $route = [
            $this->board->category->slug,
            $this->board->slug,
            $this->slug,
            $this->name_slug
        ];

        return $route;
    }
}
