<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LanguageSkillExcel
 *
 * @property int $id
 * @property int $language_id
 * @property string $path
 * @property string $file_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel query()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSkillExcel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageSkillExcel extends Model
{
    use HasFactory;
}
