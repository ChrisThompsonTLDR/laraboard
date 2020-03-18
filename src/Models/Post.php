<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Baum\Node;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;
use Venturecraft\Revisionable\RevisionableTrait;

use Christhompsontldr\Laraboard\Models\Traits\Ordered;
use Christhompsontldr\Laraboard\Models\Scopes\PrivatePostScope;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Events\PostCreated;
use Artisanry\Parsedown\Facades\Parsedown as Markdown;

class Post extends Node
{
    use Ordered,
        Searchable,
        SoftDeletes,
        RevisionableTrait;

    /**
     * Table associated with our replies
     *
     * @var string
     */
    protected $table = 'posts';

    protected $parentColumnName = 'parent_id';
    protected $leftColumnName = 'lft';
    protected $rightColumnName = 'rgt';
    protected $depthColumnName = 'depth';
    protected $orderColumnName = 'id';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving'  => PostSaving::class,
        'created' => PostCreated::class,
    ];

    public function __construct()
    {
        $this->table = config('laraboard.table_prefix') . $this->table;

        parent::__construct();
    }

    protected static function boot()
    {
        static::addGlobalScope(new PrivatePostScope);

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        parent::boot();
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
        return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
    }

    public function getThreadAttribute()
    {
        if ($this->type == 'Thread') {
            return \Christhompsontldr\Laraboard\Models\Thread::findOrFail($this->id);
        }
        return \Christhompsontldr\Laraboard\Models\Thread::findOrFail(self::ancestors()->where('type', 'Thread')->first()->id);
    }

    public function updatedByUser()
    {
        return $this->belongsTo(config('auth.providers.user.model', 'App\User'), 'updated_by');
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
        if (\Auth::check() && is_string($zone = config('laraboard.user.timezone'))) {
            $timezone = config('app.timezone');

            $pieces = explode('.', $zone);

            if (count($pieces) > 1) {
                $timezone = \Auth::user()->{$pieces[0]}->{$pieces[1]};
            } else {
                $timezone = \Auth::user()->{$zone};
            }

            return \Carbon\Carbon::parse($this->attributes['created_at'])->timezone($timezone)->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('F j, Y g:ia T');
    }

    public function getUpdatedAttribute($field)
    {
        //  convert all times to user
        if (\Auth::check() && is_string($zone = config('laraboard.user.timezone'))) {
            $timezone = config('app.timezone');

            if (count($pieces = explode('.', $zone)) > 1) {
                $timezone = \Auth::user()->{$pieces[0]}->{$pieces[1]};
            } else {
                $timezone = \Auth::user()->{$zone};
            }

            return \Carbon\Carbon::parse($this->attributes['updated_at'])->timezone($timezone)->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['updated_at'])->format('F j, Y g:ia T');
    }

    public function getDeletedAttribute($field)
    {
        //  convert all times to user
        if (\Auth::check() && is_string($zone = config('laraboard.user.timezone'))) {
            $timezone = config('app.timezone');

            if (($pieces = explode('.', $zone)) > 1) {
                $timezone = \Auth::user()->{$pieces[0]}->{$pieces[1]};
            } else {
                $timezone = \Auth::user()->{$zone};
            }

            return \Carbon\Carbon::parse($this->attributes['deleted_at'])->timezone($timezone)->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['deleted_at'])->format('F j, Y g:ia T');
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

    /**
    * Remove HTML
    *
    * @param mixed $field
    */
    public function setBodyAttribute($field)
    {
         $this->attributes['body'] = strip_tags($field);
    }


    //  SCOPES

    public function scopeClosed($query)
    {
        return $query->whereStatus('Closed');
    }

    public function scopeOpen($query)
    {
        return $query->whereStatus('Open');
    }

    public function scopeOnlyReplies($query)
    {
        return $query->whereType('Reply');
    }
}
