<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'model_provider',
        'model_version',
        'system_instructions',
    ];
}
