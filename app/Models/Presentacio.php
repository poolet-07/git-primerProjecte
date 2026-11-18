<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentacio extends Model
{
    protected $table = 'presentacions';

    public function fotos_pre(): HasMany{
        return $this->hasMany(FotoPre::class);
    }
}
