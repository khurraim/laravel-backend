<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'twitter_link',
        'instagram_link',
        'visa_link',
        'mastercard_link',
        'site_logo',
        'background_banner'
    ];

}
