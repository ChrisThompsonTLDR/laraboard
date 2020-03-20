<?php
namespace Christhompsontldr\Laraboard\Models\Traits;


use Illuminate\Database\Eloquent\SoftDeletes;
use Christhompsontldr\Laraboard\Models\Traits\Ordered;
use Laravel\Scout\Searchable;
use Venturecraft\Revisionable\RevisionableTrait;
use Christhompsontldr\Laraboard\Models\Scopes\PrivatePostScope;
use Christhompsontldr\Laraboard\Events\PostSaving;
use Christhompsontldr\Laraboard\Events\PostCreated;
use Illuminate\Support\Str;
use Artisanry\Parsedown\Facades\Parsedown as Markdown;

trait LaraboardNode
{
    use Ordered,
        Searchable,
        SoftDeletes,
        RevisionableTrait;

    protected $leftColumnName = 'lft';
    protected $rightColumnName = 'rgt';
    protected $depthColumnName = 'depth';

    public static function bootLaraboardNode()
    {
        static::addGlobalScope(new PrivatePostScope);

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }


    // RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(config('auth.providers.user.model'));
    }

    public function updatedByUser()
    {
        return $this->belongsTo(config('auth.providers.user.model'), 'updated_by');
    }


    // ACCESSORS

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


    // MUTATORS

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
