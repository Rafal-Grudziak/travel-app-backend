<?php

namespace App\Services;

use App\Http\DTOs\PlaceStoreDTO;
use App\Models\Place;
use App\Models\Travel;

class PlaceService extends ModelService
{

    protected function getModelClass(): string
    {
        return Place::class;
    }


    public function store(Travel $travel, PlaceStoreDTO $dto): Place
    {
        $place = $this->getModel()->newInstance();
        $place->setAttribute('travel_id', $travel->id);
        $place = $this->setPlaceValues($place, $dto);
        $place->save();

        return $place;
    }

    public function update(PlaceStoreDTO $dto, Place $place): Place
    {
        $place = $this->setPlaceValues($place, $dto);
        $place->save();

        return $place;
    }

    protected function setPlaceValues(Place $place, PlaceStoreDTO $dto): Place
    {
        $place->setAttribute('name', $dto->name);
        $place->setAttribute('description', $dto->description);
        $place->setAttribute('category_id', $dto->category_id);
        $place->setAttribute('longitude', $dto->longitude);
        $place->setAttribute('latitude', $dto->latitude);

        return $place;
    }

}
