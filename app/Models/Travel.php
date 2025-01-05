<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travels';


    protected $fillable = [
        'name',
        'description',
        'from',
        'to',
        'longitude',
        'latitude',
        'favourite',
        'user_id',
    ];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
        'longitude' => 'double',
        'latitude' => 'double',
        'favourite' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }
}
