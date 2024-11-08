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

        // Paginacja
        $results = $query->paginate($dto->perPage ?? 10);

        // Dodajemy mapowanie dla każdego użytkownika na paginowanych wynikach
        $results->getCollection()->transform(function ($user) use ($userId) {
            if ($user->friendsAsSender->isNotEmpty()) {
                $status = $user->friendsAsSender->first()->pivot->status;
                return [
                    'user' => new ProfileBasicResource($user),
                    'friend_status' => $status,
                    'is_sender' => true
                ];
            } elseif ($user->friendsAsReceiver->isNotEmpty()) {
                $status = $user->friendsAsReceiver->first()->pivot->status;
                return [
                    'user' => new ProfileBasicResource($user),
                    'friend_status' => $status,
                    'is_sender' => false
                ];
            } else {
                return [
                    'user' => new ProfileBasicResource($user),
                    'friend_status' => null
                ];
            }
        });

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
