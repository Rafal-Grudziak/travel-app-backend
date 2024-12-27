<?php

namespace App\Http\DTOs;

use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class TravelStoreDTO
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public string $from,
        public string $to,
        public float $longitude,
        public float $latitude,
    )
    {
    }
}
