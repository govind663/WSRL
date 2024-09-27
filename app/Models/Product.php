<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'unique_number',
        'sku',
        'name',
        'description',
        'in_stock',
        'total_quantity',
        'available_quantity',
        'price',
        'image',
        'inserted_by',
        'inserted_at',
        'modified_by',
        'modified_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $dates = [
        'inserted_at',
        'modified_at',
        'deleted_at',
    ];

    protected $casts = [
        'in_stock' => 'boolean',
        'total_quantity' => 'integer',
        'available_quantity' => 'integer',
        'price' => 'integer',
        'inserted_by' => 'integer',
        'inserted_at' => 'datetime',
        'modified_by' => 'integer',
        'modified_at' => 'datetime',
        'deleted_by' => 'integer',
        'deleted_at' => 'datetime',
    ];
}
