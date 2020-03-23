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


    public function getThread()
    {
        return Reply::ancestors()->withoutGlobalScope('forumReply')->where('type', 'Thread');
    }

    public function getThreadAttribute()
    {dd($this->getThread());
        return $this->getThread()->first();
    }
}
