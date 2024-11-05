<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class ModelService
{
    abstract protected function getModelClass(): string;

    protected function getModel(): Model
    {
        $modelClass = $this->getModelClass();
        return new $modelClass;
    }
}
