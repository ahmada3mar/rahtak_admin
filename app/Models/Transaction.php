<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime: Y-m-d H:i:s',
        'amount' => 'decimal:2',
        'fees' => 'decimal:2',
        'rahtak_fees' => 'decimal:2',
        'additional_amount' => 'decimal:2',
    ] ;
    protected $fillable = [
        'customer_id',
        'service_id',
        'user_id',
        'branch_id',
        'amount',
        'fees',
        'additional_amount',
        'rahtak_fees',
        'bankTrxID',
        'invoice'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }


}
