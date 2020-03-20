<?php

namespace Christhompsontldr\Laraboard\Models;

use Baum\Node;
use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;
use Venturecraft\Revisionable\RevisionableTrait;

use Christhompsontldr\Laraboard\Models\Traits\LaraboardNode;

class Post extends Node
{

    use LaraboardNode;

    public $table = 'posts';

    public static $sortOrder = ['created_at' => 'asc'];

    public function __construct()
    {
        $this->table = config('laraboard.table_prefix') . $this->table;

        $this->dispatchesEvents = [
            'saving'  => PostSaving::class,
            'created' => PostCreated::class,
        ];
    }

    public function toSearchableArray()
    {
        if (in_array($this->type, ['Board', 'Category'])) {
            return;
        }

        return $this->toArray();
    }


    //  RELATIONSHIPS

    public function getThreadAttribute()
    {
        if ($this->type == 'Thread') {
            return \Christhompsontldr\Laraboard\Models\Thread::findOrFail($this->id);
        }
        return \Christhompsontldr\Laraboard\Models\Thread::findOrFail(self::ancestors()->where('type', 'Thread')->first()->id);
    }


    //  ACCESSORS

    public function getRouteAttribute($field)
    {
        $route = [
            $this->thread->board->category->slug,
            $this->thread->board->slug,
            $this->thread->slug,
            $this->thread->name_slug
        ];

        if ($this->page > 1) {
            $route['page'] = $this->page;
        }

        return $route;
    }


    //  MUTATORS

    /**
    * Remove HTML
    *
    * @param mixed $field
    */
    public function setNameAttribute($field)
    {
        $this->attributes['name'] = strip_tags($field);
    }
}
