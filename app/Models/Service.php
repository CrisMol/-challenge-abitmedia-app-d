<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $visible = ['id', 'sku', 'name', 'price', 'status'];

    protected $fillable = [
        'sku',
        'name',
        'price',
        'status'
    ];
}
