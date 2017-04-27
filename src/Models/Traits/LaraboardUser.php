<?php
namespace Christhompsontldr\Laraboard\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait LaraboardUser
{

    //  RELATIONSHIPS

    public function forumSubscriptions()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Subscription');
//        return $this->belongsToMany('\Christhompsontldr\Laraboard\Models\Thread', 'laraboard_subscriptions', 'user_id', 'post_id');
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


    //  ACCESSORS

    public function getDisplayNameAttribute($field)
    {
        $display_name = config('laraboard.user.display_name');

        $pieces = explode('.', $display_name);

        if (count($pieces) > 1) {
            return $this->{$pieces[0]}->{$pieces[1]};
        }

        return $this->attributes[$display_name];
    }

    public function getAvatarAttribute($field)
    {
        $avatar = config('laraboard.user.avatar');

        if (($pieces = explode('.', $avatar)) > 1) {
            return $this->{$pieces[0]}->{$pieces[1]};
        }

        return $this->attributes[$avatar];
    }

    public function getSlugAttribute($field)
    {
        $slug = config('laraboard.user.slug');
        return $this->{$slug};
    }

    public function getPostCountAttribute($field)
    {
        return number_format($this->forumPosts->count());
    }

    public function getCreatedAttribute($field)
    {
        //  convert all times to user
        if (\Auth::check() && is_string(config('laraboard.user.timezone')) && !empty(\Auth::user()->{config('laraboard.user.timezone')})) {
            return \Carbon\Carbon::parse($this->attributes['created_at'])->timezone(\Auth::user()->{config('laraboard.user.timezone')})->format('F j, Y g:ia T');
        }

        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('F j, Y g:ia T');
    }

    public function getAlertsAttribute($field)
    {
        $ids = $this->notifications->where('data.alert.parent_id', '!=', null)->pluck('data.alert.parent_id')->unique();

        $orderBy = 'created_at';
        if ($ids->count() > 0) {
            $orderBy = 'FIELD(id, ' . $ids->implode(', ') . ')';
        }

        return \Christhompsontldr\Laraboard\Models\Thread::whereIn('id', $ids)->orderByRaw($orderBy)->get();
    }

    public function getUnreadAlertsAttribute($field)
    {
        $ids = $this->unreadNotifications->where('data.alert.parent_id', '!=', null)->pluck('data.alert.parent_id')->unique();

        return \Christhompsontldr\Laraboard\Models\Thread::whereIn('id', $ids)->get();
    }

    /**
     * Used to mark the user as admin
     */
    public function getIsAdminAttribute($field)
    {
        return false;
    }
}