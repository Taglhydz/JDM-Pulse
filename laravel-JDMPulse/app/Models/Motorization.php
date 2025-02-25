<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'power',
        'torque',
        'consumption',
        'engine_id'
    ];

    public function engine()
    {
        return $this->belongsTo(Engine::class);
    }
}
