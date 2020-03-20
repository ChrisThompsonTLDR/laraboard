<?php

namespace Christhompsontldr\Laraboard\Models;

use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Illuminate\Database\Eloquent\Builder;
use Baum\Node;
use Christhompsontldr\Laraboard\Models\Traits\LaraboardNode;

class Board extends Node
{
    use LaraboardNode;

    protected $touches = ['category'];

    public $table = 'posts';

    public function __construct()
    {
        $this->table = config('laraboard.table_prefix') . $this->table;

        $this->dispatchesEvents = [
            'saving'  => PostSaving::class,
            'created' => PostCreated::class,
        ];
    }

    public static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = 'Board';
            }
        });

        static::addGlobalScope('forumBoard', function(Builder $builder) {
            $builder->where('type', 'Board');
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
