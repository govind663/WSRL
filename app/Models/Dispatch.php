<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispatch extends Model
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
        'dispatch_code',
        'user_id',
        'distributor_id',
        'product_id',
        'quantity',
        'remarks',
        'externally_qr_code_mapping',
        'dispatched_at',
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

    // relationship with user_id
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // relationship with distributor_id
    public function distributor(){
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    // relationship with product_id
    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}
