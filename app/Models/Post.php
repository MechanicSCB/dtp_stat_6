<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $appends = ['date'];

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn() => date_format(date_create($this->datetime),'d.m.Y')
        );
    }
}
