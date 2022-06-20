<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

/**
 * App\Models\Candidate
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $mail_content
 * @property string $year_of_experience
 * @property int|null $language_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Language|null $language
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereMailContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereYearOfExperience($value)
 * @mixin \Eloquent
 * @property string|null $mail_content_raw
 * @property string|null $mail_content_html
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereMailContentHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereMailContentRaw($value)
 * @property \Illuminate\Support\Carbon|null $date
 * @property array|null $skills
 * @property array|null $experience
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CandidateAttachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $favored_by_users
 * @property-read int|null $favored_by_users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate eloquentQuery($orderBy = 'id', $orderByDir = 'asc', $searchValue = '', $relationships = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Candidate whereSkills($value)
 */
class Candidate extends Model
{
    use HasFactory;
    use LaravelVueDatatableTrait;

    protected $fillable = [
        'full_name',
        'email',
        'mail_content_raw',
        'mail_content_html',
        'year_of_experience',
        'language_id',
        'skills',
        'experience',
        'date'
    ];

    protected $dates = [
        'date'
    ];

    protected $casts = [
        'skills' => 'array',
        'experience' => 'array'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function attachments()
    {
        return $this->hasMany(CandidateAttachment::class);
    }

    public function favored_by_users()
    {
        return $this->belongsToMany(User::class, 'candidate_user', 'candidate_id');
    }
}
