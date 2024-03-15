<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Software extends Model
{
    use HasFactory, SoftDeletes;

    const SOFTWARE_DISPONIBLE = 'disponible';
    const SOFTWARE_NO_DISPONIBLE = 'no disponible';

    /** Asignación de forma masiva */
    protected $fillable = [
        'name',
        'sku',
        'status',
    ];

    protected $hidden = [
        'pivot'
    ];

    public function estaDisponible()
    {
        return $this->status == Software::SOFTWARE_DISPONIBLE;
    }

    /**
     * Obtiene el tipo de software que pertenece al software
     */
    public function softwareType(): BelongsTo
    {
        return $this->belongsTo(SoftwareType::class);
    }

    /**
    * Los sistemas operativos que pertenece el software
    */
    public function operatingSystems(): BelongsToMany
    {
        return $this->belongsToMany(OperatingSystem::class);
    }

    /**
     * Obtiene las licencias para el software
     */
    public function software(): HasMany
    {
        return $this->hasMany(License::class);
    }
}
