<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as Model;

class Permission extends Model
{

    protected $casts = [
        'created_at' => 'datetime:Y-m-d | H:i a',
        'updated_at' => 'datetime:Y-m-d | H:i a',
    ];


}
