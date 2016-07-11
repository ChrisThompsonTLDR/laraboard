<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Baum\Node;

class Post extends Node
{
    use SoftDeletes;

    /**
     * Table associated with our replies
     *
     * @var string
     */
    protected $table = 'forum_posts';

    protected $dates = ['created_at','updated_at','deleted_at'];

	/**
	 *
	 */
/*    public function thread()
    {
    	return $this->hasOne('Christhompsontldr\Laraboard\Models\Thread', 'id', 'parent_id')
                    ->orWhere(function ($query) {
                        $query->whereRaw('`id` = (select customfp.`id` from `forum_posts` AS customfp where customfp.`lft` <= ? AND customfp.`type` = "Thread" ORDER BY customfp.lft DESC LIMIT 1)', [$this->lft]);
                    });
    }*/

    public function user()
    {
    	return $this->belongsTo(config('auth.providers.user.model', 'App\User'));
    }

    public function getNameSlugAttribute($field)
    {
        return str_slug($this->name);
    }

    public function createSlug()
    {
        $found = 0;
        while($found < 1) {
            $slug = strtolower((str_random(6)));

            $found = \Christhompsontldr\Laraboard\Models\Post::whereSlug($slug)->count();

            if ($found == 0) {
                $found = 1;
            }
        }

        return $slug;
    }
}
