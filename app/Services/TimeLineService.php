<?php

namespace App\Services;

use App\Filters\BaseIndexFilter;
use App\Filters\TimeLineIndexFilter;
use App\Http\DTOs\TimeLineIndexDTO;
use App\Models\Travel;

class TimeLineService extends ModelService
{

    protected function getModelClass(): string
    {
        return Travel::class;
    }

    public function filter(TimeLineIndexDTO $dto): BaseIndexFilter
    {
        return app()->make(TimeLineIndexFilter::class, [
            'builder' => $this->getModelQuery(),
            'filterDto' => $dto,
        ]);
    }

}
