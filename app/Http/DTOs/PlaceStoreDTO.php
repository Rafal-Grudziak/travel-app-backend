<?php

namespace App\Http\DTOs;

use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class PlaceStoreDTO
{
    public function __construct(
        public string $name,
        public int $category_id,
        public float $longitude,
        public float $latitude,
        public ?string $description = null,
        public ?int $id = null,
    )
    {
    }
}
