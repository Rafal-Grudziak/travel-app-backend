<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FriendStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasMergedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'facebook_link',
        'instagram_link',
        'x_link',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function travelPreferences(): BelongsToMany
    {
        return $this->belongsToMany(TravelPreference::class);
    }

    public function friendsAsSender(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'sender_id', 'receiver_id')
            ->withPivot('status');
    }

    public function friendsAsReceiver(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'receiver_id', 'sender_id')
            ->withPivot('status');
    }

    public function acceptedFriendsAsSender()
    {
        return $this->friendsAsSender()->wherePivot('status', FriendStatus::REQUEST_ACCEPTED->value);
    }

    public function acceptedFriendsAsReceiver()
    {
        return $this->friendsAsReceiver()->wherePivot('status', FriendStatus::REQUEST_ACCEPTED->value);
    }

    public function friends()
    {
        return $this->mergedRelationWithModel(User::class, 'friends_view');
    }
}
