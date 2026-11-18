<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servei extends Model
{
    protected $table = 'serveis';

    public function families(): BelongsToMany{
        return $this->belongsToMany(Familia::class, 'solicitud');
    }
}
