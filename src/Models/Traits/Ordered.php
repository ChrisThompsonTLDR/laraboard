<?php
namespace Christhompsontldr\Laraboard\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Ordered
{
    public static function bootOrdered()
    {
        if (!isset(self::$sortOrder)) {
            return;
        }
        static::addGlobalScope('ordered', function(Builder $builder) {
            foreach (self::$sortOrder as $column => $direction) {
                $builder->orderBy($column, $direction);
            }
        });
    }
}