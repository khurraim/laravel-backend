<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddRate extends Model
{
    use HasFactory;

    protected $guard = [];

    protected $table = "add_rates";

    // Allow mass asign
    protected $fillable = [
        'model_id',
        'duration',
        'incall',
        'outcall'
    ];

}
