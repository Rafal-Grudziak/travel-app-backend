<?php

namespace App\Services;

use App\Http\DTOs\ImageStoreDTO;
use App\Http\DTOs\PlaceStoreDTO;
use App\Models\Image;
use App\Models\Place;
use App\Models\Travel;
use Illuminate\Support\Facades\File;

class ImageService extends ModelService
{

    protected function getModelClass(): string
    {
        return Image::class;
    }


    public function store(ImageStoreDTO $dto): Image
    {
        $image = $this->getModel()->newInstance();
        $image->setAttribute('imageable_type', $dto->imageable_type);
        $image->setAttribute('imageable_id', $dto->imageable_id);
        if ($dto->image) {
            $path = $dto->image->store('images');
            $image->setAttribute('path', $path);
        }
        $image->save();

        return $image;
    }


}
