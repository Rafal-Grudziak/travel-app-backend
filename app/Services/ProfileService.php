<?php

namespace App\Services;

use App\Http\DTOs\ProfileUpdateDto;
use App\Models\User;

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
