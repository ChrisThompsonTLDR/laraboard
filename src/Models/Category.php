<?php

namespace Christhompsontldr\Laraboard\Models;

use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Models\Traits\LaraboardNode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Baum\Node;

class Category extends Node
{

    use LaraboardNode;

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
