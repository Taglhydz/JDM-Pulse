<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Power extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'engine_id'
    ];

    public $timestamps = false;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    
    public function engine()
    {
        return $this->belongsTo(Engine::class);
    }
}
