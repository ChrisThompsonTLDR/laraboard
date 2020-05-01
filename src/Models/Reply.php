<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

class Reply extends Post
{

    public static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = 'Reply';
            }
        });

        parent::boot();
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

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'parent_id', 'id')
            ->ofType('Thread');
    }


    // ACCESSORS

    public function getRouteAttribute($field)
    {
        return $this->thread->route;
    }
}
