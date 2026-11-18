<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metge extends Model
{
    protected $table = 'metges';

    public function usuari() {
        return $this->hasOne(Usuari::class);
    }
}
