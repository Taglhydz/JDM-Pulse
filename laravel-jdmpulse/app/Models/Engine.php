<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    use HasFactory;

    protected $fillable = [
        'engine_name',
        'architecture',
        'volume',
        'induction',
        'fuel_type'
    ];
    public function motorizations()
    {
        return $this->hasMany(Motorization::class);
    }
    public function powers()
    {
        return $this->hasMany(Power::class);
    }
}
