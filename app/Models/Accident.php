<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Accident extends Model
{
    use HasFactory;

    protected function datetimeString(): Attribute
    {
        return Attribute::make(
            get: fn() => utf_ucfirst(Carbon::parse($this->datetime)->isoFormat('dddd, DD.MM.YYYY в HH:mm'))
        );
    }
}
