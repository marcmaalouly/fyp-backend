<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'mail_content',
        'year_of_experience',
        'language_id'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
