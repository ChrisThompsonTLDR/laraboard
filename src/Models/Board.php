<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

class Board extends Post
{

    protected $touches = ['category'];

    public static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = 'Board';
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

    public static function first()
    {
        return parent::ofType(class_basename(__CLASS__))->first();
    }

    public static function get()
    {
        return parent::ofType(class_basename(__CLASS__))->get();
    }


    // RELATIONSHIPS

    public function category()
    {
    	return $this->belongsTo(Category::class, 'parent_id', 'id')
                    ->ofType('Category');
    }

    public function threads()
    {
        return $this->hasMany(Thread::class, 'parent_id', 'id')
                    ->ofType('Thread');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'parent_id', 'id')
                    ->orWhere(function ($query) {
                        $query->whereRaw('`lft` > (select fp.`lft` from `' . $this->table . '` AS fp where fp.`id` = ?) AND `rgt` < (select fp.`rgt` from `' . $this->table . '` AS fp where fp.`id` = ?)', [$this->id, $this->id]);
                    });
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
