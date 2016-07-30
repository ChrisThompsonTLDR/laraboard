<?php
namespace Christhompsontldr\Laraboard\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Zizaco\Entrust\Traits\EntrustUserTrait;

trait LaraboardUser
{
    use EntrustUserTrait;

    public function forumThreadSubscriptions()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Subscription');
    }

    public function forumSubscriptionAlerts()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Alert')->active();
    }

    public function forumThreads()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Thread');
    }

    public function forumReplies()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Reply');
    }

    //  includes threads and reply
    public function forumPosts()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Post')->whereIn('type', ['Reply', 'Thread']);
    }

    public function getDisplayNameAttribute()
    {
        $display_name = config('laraboard.user.display_name');
        return $this->{$display_name};
    }

    public function getSlugAttribute()
    {
        $slug = config('laraboard.user.slug');
        return $this->{$slug};
    }

    public function getPostCountAttribute()
    {
        return number_format($this->forumPosts->count());
    }

    public function getCreatedAttribute()
    {
        //  convert all times to user
        if (\Auth::check() && is_string(config('laraboard.user.timezone')) && !empty(\Auth::user()->{config('laraboard.user.timezone')})) {
            return \Carbon\Carbon::parse($this->attributes['created_at'])->timezone(\Auth::user()->{config('laraboard.user.timezone')})->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('F j, Y g:ia T');
    }
}