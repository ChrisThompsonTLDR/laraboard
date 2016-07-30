<?php

namespace Christhompsontldr\Laraboard\Models;

use Illuminate\Database\Eloquent\Builder;

use Christhompsontldr\Laraboard\Models\Post;

class Board extends Post
{

    protected $touches = ['category'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('forumBoard', function(Builder $builder) {
            $builder->where('type', 'Board');
        });

        /**  @todo don't publish  */
        static::addGlobalScope('privateTeams', function(Builder $builder) {
            $slug_where = '';

            $team_slugs = [];

            $teams_category = Post::whereSlug('teams')->whereType('Category')->first();

            if (empty($teams_category)) {
                return;
            }

            //  guests can't see any teams
            if (\Auth::guest()) {
                $builder->where('lft', '<', $teams_category->lft)
                        ->orWhere('rgt', '>', $teams_category->rgt);
            }
            elseif(!\Auth::user()->hasRole('admin')) {
                $team_slugs = \Auth::user()->teams->pluck('slug')->toArray();

                //  no teams
                if (count($team_slugs) == 0) {
                    $builder->where('lft', '<', $teams_category->lft)
                            ->orWhere('rgt', '>', $teams_category->rgt);
                } else {
                    $team_ids = Post::whereType('Board')->whereIn('slug', $team_slugs)->pluck('id')->toArray();

                    //  no teams
                    $builder->where('lft', '<', $teams_category->lft)
                            ->orWhere('rgt', '>', $teams_category->rgt);

                    if (count($team_ids) > 0) {
                        $builder->orWhereIn('id', $team_ids);
                    }
                }
            }
        });
    }

    public function category()
    {
    	return $this->belongsTo('Christhompsontldr\Laraboard\Models\Category', 'parent_id', 'id');
    }

    public function threads()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Thread', 'parent_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany('Christhompsontldr\Laraboard\Models\Post', 'parent_id', 'id')
                    ->orWhere(function ($query) {
                        $query->whereRaw('`lft` > (select fp.`lft` from `forum_posts` AS fp where fp.`id` = ?) AND `rgt` < (select fp.`rgt` from `forum_posts` AS fp where fp.`id` = ?)', [$this->id, $this->id]);
                    });
    }
}
