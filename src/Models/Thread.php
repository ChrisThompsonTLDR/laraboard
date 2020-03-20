<?php

namespace Christhompsontldr\Laraboard\Models;

use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Models\Traits\LaraboardNode;
use Illuminate\Database\Eloquent\Builder;
use Christhompsontldr\Laraboard\Models\Traits\Ordered;
use Baum\Node;

class Thread extends Node
{

    use LaraboardNode,
        Ordered;

    protected $touches = ['board'];

    public static $sortOrder = ['updated_at' => 'desc'];

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
        static::addGlobalScope('forumThread', function(Builder $builder) {
            $builder->where('type', 'Thread');
        });

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
    public function getRouteKeyName()
    {
        return 'slug';
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
        $this->loadMissing(['board', 'board.category']);

        return [
            $this->board->category,
            $this->board,
            $this,
            $this->slug,
        ];
    }
}
