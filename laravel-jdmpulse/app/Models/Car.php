<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'year',
        'color',
        'generation',
        'image_url',
        'edition_id'
    ];
    
    public function edition()
    {
        return $this->belongsTo(Edition::class);
    }

    public function powers()
    {
        return $this->hasMany(Power::class);
    }
}
