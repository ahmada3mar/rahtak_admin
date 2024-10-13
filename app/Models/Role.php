<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as Model;


class Role extends Model
{

    protected $casts = [
        'created_at' => 'datetime:Y-m-d | H:i a',
        'updated_at' => 'datetime:Y-m-d | H:i a',
    ];




}
