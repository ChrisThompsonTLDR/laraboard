<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Category extends Post
{

    public static function boot()
    {
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

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($this->getRouteKeyName(), $value)->ofType('Category')->firstOrFail();
    }

    public static function first()
    {
        return parent::ofType(class_basename(__CLASS__))->first();
    }

    public static function get()
    {
        return parent::ofType(class_basename(__CLASS__))->get();
    }


    // RELATIONSHIPS

    public function boards()
    {
    	return $this->hasMany(Board::class, 'parent_id', 'id')
                    ->ofType('Board');
    }


    // ACCESSORS

    public function getRouteAttribute($field)
    {
        return [
            $this,
        ];
    }
}
