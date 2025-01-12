<?php

namespace App\Http\DTOs;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class ProfileUpdateDTO
{
    public function __construct(
        public ?string $email = null,
        public ?string $name = null,
        public ?string $bio = null,
        public ?string $facebook_link = null,
        public ?string $instagram_link = null,
        public ?string $x_link = null,
        public ?array  $travel_preferences = null,
        public ?UploadedFile $avatar = null,
    )
    {
    }
}
