<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menulist
 *
 * @property int $id
 * @property string $name
 * @method static Builder|Menulist newModelQuery()
 * @method static Builder|Menulist newQuery()
 * @method static Builder|Menulist query()
 * @method static Builder|Menulist whereId($value)
 * @method static Builder|Menulist whereName($value)
 * @mixin Eloquent
 */
class Menulist extends Model
{
    protected $table = 'menulist';
    public $timestamps = false;
}
