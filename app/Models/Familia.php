<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $table = 'families';

    public function pacients(): HasMany{
        return $this->hasMany(Pacient::class);
    }

    public function serveis(): BelongsToMany{
        return $this->belongsToMany(Servei::class, 'solicitud');
    }
}
