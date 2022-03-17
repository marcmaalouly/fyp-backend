<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SkillKey
 *
 * @property int $id
 * @property string $key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language[] $languages
 * @property-read int|null $languages_count
 * @method static \Illuminate\Database\Eloquent\Builder|SkillKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SkillKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SkillKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|SkillKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SkillKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SkillKey whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SkillKey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SkillKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'key'
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_skill');
    }
}
