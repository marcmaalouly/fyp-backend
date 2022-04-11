<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menus
 *
 * @property int $id
 * @property string $name
 * @property string|null $href
 * @property string|null $icon
 * @property string $slug
 * @property int|null $parent_id
 * @property int $menu_id
 * @property int $sequence
 * @method static Builder|Menus newModelQuery()
 * @method static Builder|Menus newQuery()
 * @method static Builder|Menus query()
 * @method static Builder|Menus whereHref($value)
 * @method static Builder|Menus whereIcon($value)
 * @method static Builder|Menus whereId($value)
 * @method static Builder|Menus whereMenuId($value)
 * @method static Builder|Menus whereName($value)
 * @method static Builder|Menus whereParentId($value)
 * @method static Builder|Menus whereSequence($value)
 * @method static Builder|Menus whereSlug($value)
 * @mixin Eloquent
 * @property string $unique_id
 * @method static Builder|Menus whereUniqueId($value)
 */
class Menus extends Model
{
    protected $table = 'menus';
    public $timestamps = false;
}
