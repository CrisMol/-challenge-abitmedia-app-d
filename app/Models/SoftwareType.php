<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftwareType extends Model
{
    use HasFactory, SoftDeletes;

    protected $visible = ['id', 'name'];

    protected $fillable = [
        'name'
    ];

    /**
     * Obtiene los software para el tipo de software
     */
    public function software(): HasMany
    {
        return $this->hasMany(Software::class);
    }
}
