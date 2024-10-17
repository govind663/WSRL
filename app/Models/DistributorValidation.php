<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistributorValidation extends Model
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
        'distributor_id',
        'product_id',
        'qr_code_id',
        'external_qr_serial',
        'quantity_validated',
        'validation_date',
        'inserted_by',
        'inserted_dt',
        'modified_by',
        'modified_dt',
        'deleted_by',
        'deleted_at',
    ];

    protected $dates = [
        'inserted_dt',
        'modified_dt',
        'deleted_at',
    ];

    // ==== Relationship with Distributor
    public function distributor(){
        return $this->belongsTo(Distributor::class, 'distributor_id', 'id');
    }

    // ==== Relationship with Product
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
