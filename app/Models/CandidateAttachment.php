<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CandidateAttachment
 *
 * @property int $id
 * @property int $candidate_id
 * @property string $path
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment whereCandidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateAttachment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CandidateAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'path',
        'name'
    ];
}
