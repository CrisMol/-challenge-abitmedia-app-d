<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class License extends Model
{
    use HasFactory, SoftDeletes;

    /** Asignación de forma masiva */
    protected $fillable = [
        'serial',
        'status',
        'product_id',
    ];

    /**
     * Obtiene el software al que pertenece la licencia
     */
    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class);
    }
}
