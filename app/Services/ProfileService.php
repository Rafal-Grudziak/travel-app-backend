<?php

namespace App\Services;

use App\Http\DTOs\ProfileSearchDto;
use App\Http\DTOs\ProfileUpdateDto;
use App\Http\Resources\ProfileBasicResource;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProfileService extends ModelService
{

    protected function getModelClass(): string
    {
        return User::class;
    }

    public function updateProfile(User $user, ProfileUpdateDto $dto): User
    {

        $user = $this->setUserValues($user, $dto);
        $user->save();

        return $user;
    }

//    public function search(ProfileSearchDto $dto): LengthAwarePaginator
//    {
//        return User::query()
//            ->where('name', 'LIKE', "%{$dto->query}%")
//            ->where('id', '!=', auth()->id())
//            ->paginate(10);
//    }

    public function search(ProfileSearchDto $dto): LengthAwarePaginator
    {
        $userId = auth()->id();

        $query = User::query()
            ->where('name', 'LIKE', "%{$dto->query}%")
            ->where('id', '!=', $userId)
            ->with([
                'friendsAsSender' => function ($query) use ($userId) {
                    $query->where('sender_id', $userId);
                },
                'friendsAsReceiver' => function ($query) use ($userId) {
                    $query->where('receiver_id', $userId);
                }
            ]);

        $results = $query->paginate($dto->perPage ?? 10);

        return $results;
    }

    private function setUserValues(User $user, ProfileUpdateDto $dto): User
    {
        $user->fill([
            'email' => $dto->email,
            'name' => $dto->name,
            'bio' => $dto->bio,
            'facebook_link' => $dto->facebook_link,
            'instagram_link' => $dto->instagram_link,
            'x_link' => $dto->x_link,
        ]);

        $user->travelPreferences()->sync($dto->travel_preferences);

        return $user;
    }

}
