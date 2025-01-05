<?php

namespace App\Services;

use App\Http\DTOs\TravelStoreDTO;
use App\Models\Travel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TravelService extends ModelService
{
    public function __construct(protected PlaceService $placeService)
    {
    }


    protected function getModelClass(): string
    {
        return Travel::class;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Travel::query()
            ->where('user_id', auth()->id())
            ->paginate($perPage);
    }

    public function storeTravel(TravelStoreDTO $dto): Travel
    {
        $travel = $this->getModel()->newInstance();
        $travel = $this->setTravelValues($travel, $dto);
        $travel->user_id = auth()->id();
        $travel->favourite = false;
        $travel->save();

        foreach ($dto->places ?? [] as $place) {
            $this->placeService->store($travel, $place);
        }

        return $travel;
    }

    public function updateTravel(TravelStoreDTO $dto, Travel $travel): Travel
    {
        $travel = $this->setTravelValues($travel, $dto);
        $travel->save();

        $this->handlePlaces($travel, $dto->places);

        return $travel;
    }

    public function toggleFavourite(Travel $travel): Travel
    {
        $travel->favourite = !$travel->favourite;
        $travel->save();

        return $travel;
    }

    private function setTravelValues(Travel $travel, TravelStoreDTO $dto): Travel
    {
        $travel->fill([
            'name' => $dto->name,
            'description' => $dto->description,
            'from' => $dto->from,
            'to' => $dto->to,
            'longitude' => $dto->longitude,
            'latitude' => $dto->latitude,
        ]);

        return $travel;
    }

    protected function handlePlaces(Travel $travel, Collection $places): void
    {
        $travel->places()->whereNotIn('id', $places->pluck('id')->filter(fn ($item) => $item !== null))->delete();

        foreach ($places->whereNotNull('id') as $place) {
            $travelCondition = $this->placeService->update($place, $travel->places()->find($place->id));
        }

        foreach ($places->whereNull('id') as $place) {
            $travelCondition = $this->placeService->store($travel, $place);
        }
    }


}
