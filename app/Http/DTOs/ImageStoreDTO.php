<?php

namespace App\Http\DTOs;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class ImageStoreDTO
{
    public function __construct(
        public ?string $imageable_type = null,
        public ?int $imageable_id = null,
        public ?UploadedFile $image = null,
    )
    {
    }
}
