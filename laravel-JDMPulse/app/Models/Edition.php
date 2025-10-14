<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    use HasFactory;

    protected $fillable = [
        'edition_name'
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
