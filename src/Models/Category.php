<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Category extends Post
{

    public static function boot()
    {
        static::addGlobalScope('forumCategory', function(Builder $builder) {
            $builder->where('type', 'Category');
        });

        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = 'Category';
            }
        });

        parent::boot();
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

    public function boards()
    {
    	return $this->hasMany(Board::class, 'parent_id', 'id');
    }
}
