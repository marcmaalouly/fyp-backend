<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CandidateMeeting
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateMeeting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateMeeting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CandidateMeeting query()
 * @mixin \Eloquent
 */
class CandidateMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'user_id',
        'meeting_url',
        'start_meeting_url',
        'start_time'
    ];

    protected $dates = [
        'start_time'
    ];
}
