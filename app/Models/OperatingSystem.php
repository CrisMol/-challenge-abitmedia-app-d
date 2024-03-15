<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OperatingSystem extends Model
{
    use HasFactory, SoftDeletes;

    protected $visible = ['id', 'name'];

    protected $fillable = [
        'name'
    ];

    /**
    * Los software que pertenece el sistema operativo
    */
    public function software(): BelongsToMany
    {
        return $this->belongsToMany(Software::class);
    }
}
