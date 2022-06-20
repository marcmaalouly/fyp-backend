<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ZoomInformation
 *
 * @property int $id
 * @property int $user_id
 * @property string $access_token
 * @property string $refresh_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoomInformation whereUserId($value)
 * @mixin \Eloquent
 */
class ZoomInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'refresh_token',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
