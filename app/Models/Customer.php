<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d | H:i a',

    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
