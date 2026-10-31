<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facility extends Model
{
    use HasFactory;

    public const TYPE_UDD = 'udd';
    public const TYPE_HOSPITAL = 'hospital';

    protected $fillable = [
        'region_id',
        'code',
        'name',
        'type',
        'address',
        'phone',
        'operating_hours',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeUdd(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_UDD);
    }

    public function scopeHospitals(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_HOSPITAL);
    }
}
