<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QrCode extends Model
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
        'user_id',
        'quantity',
        'internal_qr_code',
        'external_qr_code',
        'internal_qr_code_status',
        'external_qr_code_status',
        'internal_qr_code_count',
        'external_qr_code_count',
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

    // ==== Relationship with User
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
