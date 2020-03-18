<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

use Christhompsontldr\Laraboard\Models\Post;

class Board extends Post
{

    protected $touches = ['category'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('forumBoard', function(Builder $builder) {
            $builder->where('type', 'Board');
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
    	return $this->belongsTo('Christhompsontldr\Laraboard\Models\Category', 'parent_id', 'id');
    }

    public function threads()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Thread', 'parent_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Post', 'parent_id', 'id')
                    ->orWhere(function ($query) {
                        $query->whereRaw('`lft` > (select fp.`lft` from `' . $this->table . '` AS fp where fp.`id` = ?) AND `rgt` < (select fp.`rgt` from `' . $this->table . '` AS fp where fp.`id` = ?)', [$this->id, $this->id]);
                    })
                    ->onlyReplies();
    }


    // ACCESSORS

    public function getRouteAttribute($field)
    {
        return [
            $this->category,
            $this,
        ];
    }
}
