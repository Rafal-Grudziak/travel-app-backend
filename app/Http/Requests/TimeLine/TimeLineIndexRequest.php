<?php

namespace App\Http\Requests\TimeLine;

use Illuminate\Foundation\Http\FormRequest;

class TimeLineIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
        ];
    }
}
