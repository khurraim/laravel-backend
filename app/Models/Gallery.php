<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{

    use HasFactory;

    protected $table = 'gallery'; // Specify the table name

    protected $guard = [];

    protected $fillable = [
        'model_id',
        'image'
    ];
}
