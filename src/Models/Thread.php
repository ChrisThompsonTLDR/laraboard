<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

class Thread extends Post
{

    protected $touches = ['board'];

    public static $sortOrder = ['updated_at' => 'desc'];

    public static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = 'Thread';
            }
        });

        parent::boot();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
//    public function getRouteKeyName()
//    {
//        return 'slug';
//    }

    public static function first()
    {
        return parent::ofType(class_basename(__CLASS__))->first();
    }

    public static function get()
    {
        return parent::ofType(class_basename(__CLASS__))->get();
    }


    // RELATIONSHIPS

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


    // ACCESSORS

    public function getLastPageAttribute($field)
    {
        return (int) ceil(($this->replies->count() + 1) / config('laraboard.post.limit', 15));
    }

    public function getLastPageRouteAttribute($field)
    {
        $this->loadMissing(['board', 'board.category']);

        $route = [
            $this->board->category,
            $this->board,
            $this,
            $this->slug
        ];

        if ($this->lastPage > 1) {
            $route['page'] = $this->lastPage;
        }

        return $route;
    }

    public function getRouteAttribute($field)
    {
        return [
            $this->board->category,
            $this->board,
            $this,
            $this->slug,
        ];
    }
}
