<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Accident extends Model
{
    use HasFactory;

    public $appends = ['datetime_string'];

    public function accident_info(): HasOne
    {
        return $this->hasOne(AccidentInfo::class);
    }

    public function accident_category(): BelongsTo
    {
        return $this->belongsTo(AccidentCategory::class);
    }

    public function severity(): BelongsTo
    {
        return $this->belongsTo(Severity::class);
    }

    public function light_conditions(): BelongsTo
    {
        return $this->belongsTo(LightCondition::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function subregion(): BelongsTo
    {
        return $this->belongsTo(Subregion::class);
    }

    protected function datetimeString(): Attribute
    {
        return Attribute::make(
            get: fn() => utf_ucfirst(Carbon::parse($this->datetime)->isoFormat('dddd, DD.MM.YYYY в HH:mm'))
        );
    }
}
