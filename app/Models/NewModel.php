<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function services()
    {
        return $this->hasMany(Service::class, 'model_id');
    }


    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'model_id');
    }

    public function rates()
    {
        return $this->hasMany(AddRate::class,'model_id');
    }

}
