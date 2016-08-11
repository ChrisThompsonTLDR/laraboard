<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Baum\Node;

use Christhompsontldr\Laraboard\Models\Traits\Ordered;

class Post extends Node
{
    use Ordered;
    use SoftDeletes;
    use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * Table associated with our replies
     *
     * @var string
     */
    protected $table = 'forum_posts';

    protected $dates = ['created_at','updated_at','deleted_at'];

    public static $sortOrder = ['lft' => 'asc'];

    //  relationships
    public function user()
    {
    	return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
    }

    public function getThreadAttribute()
    {
        if ($this->type == 'Thread') {
            return \Christhompsontldr\Laraboard\Models\Thread::findOrFail($this->id);
        }
        return self::ancestors()->where('type', 'Thread');
    }


    //  mutators
    public function getNameSlugAttribute($field)
    {
        return str_limit(str_slug($this->name), 50);
    }

    public function getBodyHtmlAttribute($field)
    {
        return \Markdown::text($this->body);
    }

    public function getCreatedAttribute($field)
    {
        //  convert all times to user
        if (\Auth::check() && is_string(config('laraboard.user.timezone')) && !empty(\Auth::user()->{config('laraboard.user.timezone')})) {
            return \Carbon\Carbon::parse($this->attributes['created_at'])->timezone(\Auth::user()->{config('laraboard.user.timezone')})->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('F j, Y g:ia T');
    }

    public function getUpdatedAttribute($field)
    {
        //  convert all times to user
        if (\Auth::check() && is_string(config('laraboard.user.timezone')) && !empty(\Auth::user()->{config('laraboard.user.timezone')})) {
            return \Carbon\Carbon::parse($this->attributes['updated_at'])->timezone(\Auth::user()->{config('laraboard.user.timezone')})->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['updated_at'])->format('F j, Y g:ia T');
    }

    public function getDeletedAttribute($field)
    {
        //  convert all times to user
        if (\Auth::check() && is_string(config('laraboard.user.timezone')) && !empty(\Auth::user()->{config('laraboard.user.timezone')})) {
            return \Carbon\Carbon::parse($this->attributes['deleted_at'])->timezone(\Auth::user()->{config('laraboard.user.timezone')})->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['deleted_at'])->format('F j, Y g:ia T');
    }


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
}
