<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trobada extends Model
{
    protected $table = 'trobades';

    public function fotos(): HasMany{
        return $this->hasMany(Foto::class);
    }
}
