<?php

namespace App\Services;

use App\Http\DTOs\TravelStoreDTO;
use App\Models\Travel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TravelService extends ModelService
{

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
        $travel->save();

        return $travel;
    }

    public function updateTravel(TravelStoreDTO $dto, Travel $travel): Travel
    {
        $travel = $this->setTravelValues($travel, $dto);
        $travel->save();

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

}
