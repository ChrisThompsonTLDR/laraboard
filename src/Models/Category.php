<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

use Christhompsontldr\Laraboard\Models\Post;

class Category extends Post
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('forumCategory', function(Builder $builder) {
            $builder->where('type', 'Category');
        });
    }

    public function boards()
    {
    	return $this->hasMany('Christhompsontldr\Laraboard\Models\Board', 'parent_id', 'id');
    }
}
