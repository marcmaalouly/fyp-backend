<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
