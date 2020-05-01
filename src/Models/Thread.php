<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Thread extends Post
{

    protected $touches = ['board'];

    public static $sortOrder = ['updated_at' => 'desc'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = 'Thread';
            }
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
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

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $thread = $this->where($this->getRouteKeyName(), $value)
            ->ofType('Thread')
            ->when(request()->route('laraboardBoard'), function ($query, $board) {
                return $query->whereHas('board', function ($query) use ($board) {
                    return $query->where('id', $board->id);
                });
            })
            ->firstOrFail();

        if ($board = request()->route('laraboardBoard')) {
            $thread->board = $board;
        }

        return $thread;
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

    public function board()
    {
        return $this->belongsTo(Board::class, 'parent_id', 'id')
                    ->ofType('Board');
    }

    public function replies()
    {
    	return $this->hasMany(Reply::class, 'parent_id', 'id')
                    ->ofType('Reply');
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
        return [
            $this->board->category,
            $this->board,
            $this,
            $this->slug,
        ];
    }
}
