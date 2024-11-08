<?php

namespace App\Http\DTOs;

use Illuminate\Http\Request;

readonly class ProfileSearchDto
{
    public function __construct(
        public int $page = 1,
        public ?string $query = null
    )
    {
    }
}
