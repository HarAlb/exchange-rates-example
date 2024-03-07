<?php

namespace App\Modules\ExchangeRate\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exchange extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'exchange_type_id',
        'number_code',
        'char_code',
        'name',
        'v_unit_rate',
        'last_update'
    ];

    public function exchangeType(): BelongsTo
    {
        return $this->belongsTo(ExchangeType::class);
    }

    public function vUnitRate(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => str_replace(',','.', $value),
        );
    }
}
