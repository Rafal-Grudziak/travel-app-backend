<?php

namespace App\Filters;

class TimeLineIndexFilter extends BaseIndexFilter
{
    public function filter(): void
    {
        $this->filterByDateRange();

        $this->orderBy('created_at', $this->filterDto->sort_direction ?? 'desc');
    }

    protected function filterByDateRange(): void
    {
        if ($this->filterDto->date_from) {
            $this->builder->where('from', '>=', $this->filterDto->date_from);
        }

        if ($this->filterDto->date_to) {
            $this->builder->where('to', '<=', $this->filterDto->date_to);
        }
    }
}
