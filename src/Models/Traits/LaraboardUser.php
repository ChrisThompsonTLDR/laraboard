<?php
namespace Christhompsontldr\Laraboard\Models\Traits;

use Christhompsontldr\Laraboard\Models\Post;
use Christhompsontldr\Laraboard\Models\Reply;
use Christhompsontldr\Laraboard\Models\Subscription;
use Christhompsontldr\Laraboard\Models\Thread;
use Carbon\Carbon;

trait LaraboardUser
{

    private $postCount;

    //  RELATIONSHIPS

    public function forumSubscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function forumThreads()
    {
        return $this->hasMany(Thread::class);
    }

    public function forumReplies()
    {
        return $this->hasMany(Reply::class);
    }

    //  includes threads and reply
    public function forumPosts()
    {
        return $this->hasMany(Post::class)->whereIn('type', ['Reply', 'Thread']);
    }


    //  ACCESSORS

    public function getLaraboardNameAttribute($field)
    {
        $display_name = config('laraboard.user.display_name');

        $pieces = explode('.', $display_name);

        if (count($pieces) > 1) {
            return $this->{$pieces[0]}->{$pieces[1]};
        }

        return $this->attributes[$display_name];
    }

    public function getLaraboardAvatarAttribute($field)
    {dd('here');
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
        if (is_null($this->postCount)) {
            $this->postCount = number_format($this->forumPosts()->count());
        }

        return $this->postCount;
    }

    public function getCreatedAttribute($field)
    {
        //  convert all times to user
        if (auth()->check() && is_string(config('laraboard.user.timezone')) && !empty(auth()->user()->{config('laraboard.user.timezone')})) {
            return Carbon::parse($this->attributes['created_at'])->timezone(auth()->user()->{config('laraboard.user.timezone')})->format('F j, Y g:ia T');
        }

        return Carbon::parse($this->attributes['created_at'])->format('F j, Y g:ia T');
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
