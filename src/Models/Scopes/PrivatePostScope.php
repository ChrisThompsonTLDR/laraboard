<?php

namespace Christhompsontldr\Laraboard\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\PermissionUser;

class PrivatePostScope implements Scope
{
    /**
     * Only allow users to see the posts they are authorized to see
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $privateIds = [];

        //  only do for logged in users
        if (auth()->check()) {
            //  if admin, they see everything
            if (auth()->user()->is_admin) {
                return;
            }

            //  laratrust is a pain and doesn't bust cache
            $permissionUsers = PermissionUser::whereUserId(auth()->id())->has('permission')->get();

            foreach ($permissionUsers as $permissionUser) {
                $privateIds[] = str_replace('laraboard-', '', $permissionUser->permission->name);
            }
        }

        $builder->whereIn('id', $privateIds)
                ->orWhere('private', false);
    }
}