<?php

namespace Christhompsontldr\Laraboard\Models;

use Baum\Node;
use Christhompsontldr\Laraboard\Events\PostCreated;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Models\Scopes\PrivatePostScope;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;
use Venturecraft\Revisionable\RevisionableTrait;
use Artisanry\Parsedown\Facades\Parsedown as Markdown;
use Illuminate\Database\Eloquent\SoftDeletes;
use Christhompsontldr\Laraboard\Models\Traits\Ordered;
use Carbon\Carbon;

class Post extends Node
{
    use Ordered,
        Searchable,
        SoftDeletes,
        RevisionableTrait;

    public $table = 'posts';

    public static $sortOrder = ['created_at' => 'asc'];

    protected $leftColumnName = 'lft';
    protected $rightColumnName = 'rgt';
    protected $depthColumnName = 'depth';

    public function __construct()
    {
        parent::__construct();

        $this->table = config('laraboard.table_prefix') . $this->table;

        $this->dispatchesEvents = [
            'saving'  => PostSaving::class,
            'created' => PostCreated::class,
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PrivatePostScope);

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
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
        $post = $this->where($this->getRouteKeyName(), $value)->firstOrFail();

        // used because post.edit could be either
        if ($post->type == 'Reply') {
            return Reply::find($post->id);
        }
        elseif ($post->type == 'Thread') {
            return Thread::find($post->id);
        }

        return $post;
    }

    public function toSearchableArray()
    {
        if (in_array($this->type, ['Board', 'Category'])) {
            return;
        }

        return $this->toArray();
    }


    //  RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function updatedByUser()
    {
        return $this->belongsTo(config('auth.providers.user.model'), 'updated_by');
    }


    //  ACCESSORS

    public function getNameSlugAttribute($field)
    {
        return Str::slug(Str::limit($this->name, 50, ''));
    }

    public function getBodyHtmlAttribute($field)
    {
        return Markdown::text($this->body);
    }

    public function getCreatedAttribute($field)
    {
        //  convert all times to user
        if (auth()->check() && is_string($zone = config('laraboard.user.timezone'))) {
            $timezone = config('app.timezone');

            $pieces = explode('.', $zone);

            if (count($pieces) > 1) {
                $timezone = auth()->user()->{$pieces[0]}->{$pieces[1]};
            } else {
                $timezone = auth()->user()->{$zone};
            }

            return Carbon::parse($this->attributes['created_at'])->timezone($timezone)->format('F j, Y g:ia T');
        }

        return Carbon::parse($this->attributes['created_at'])->format('F j, Y g:ia T');
    }

    public function getUpdatedAttribute($field)
    {
        //  convert all times to user
        if (auth()->check() && is_string($zone = config('laraboard.user.timezone'))) {
            $timezone = config('app.timezone');

            if (count($pieces = explode('.', $zone)) > 1) {
                $timezone = auth()->user()->{$pieces[0]}->{$pieces[1]};
            } else {
                $timezone = auth()->user()->{$zone};
            }

            return Carbon::parse($this->attributes['updated_at'])->timezone($timezone)->format('F j, Y g:ia T');
        }

        return Carbon::parse($this->attributes['updated_at'])->format('F j, Y g:ia T');
    }

    public function getDeletedAttribute($field)
    {
        //  convert all times to user
        if (auth()->check() && is_string($zone = config('laraboard.user.timezone'))) {
            $timezone = config('app.timezone');

            if (($pieces = explode('.', $zone)) > 1) {
                $timezone = auth()->user()->{$pieces[0]}->{$pieces[1]};
            } else {
                $timezone = auth()->user()->{$zone};
            }

            return Carbon::parse($this->attributes['deleted_at'])->timezone($timezone)->format('F j, Y g:ia T');
        }

        return Carbon::parse($this->attributes['deleted_at'])->format('F j, Y g:ia T');
    }

    public function getIsOpenAttribute($field)
    {
        if ($this->attributes['status'] == 'Open') {
            return true;
        }

        return false;
    }

    public function getPageAttribute($field)
    {
        $left = $this->where('lft', '<', $this->attributes['lft'])
                     ->where('parent_id', $this->attributes['parent_id'])
                     ->count();

        return (int) ceil(($left + 2) / config('laraboard.post.limit', 15));
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

    /**
    * Remove HTML
    *
    * @param mixed $field
    */
    public function setBodyAttribute($field)
    {
         $this->attributes['body'] = strip_tags($field);
    }


    // SCOPES

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->whereStatus('Closed');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->whereStatus('Open');
    }
}
