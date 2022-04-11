<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menurole
 *
 * @property int $id
 * @property string $role_name
 * @property int $menus_id
 * @method static Builder|Menurole newModelQuery()
 * @method static Builder|Menurole newQuery()
 * @method static Builder|Menurole query()
 * @method static Builder|Menurole whereId($value)
 * @method static Builder|Menurole whereMenusId($value)
 * @method static Builder|Menurole whereRoleName($value)
 * @mixin Eloquent
 */
class Menurole extends Model
{
    protected $table = 'menu_role';
    public $timestamps = false;
}
