<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    protected static function bootUuid()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}