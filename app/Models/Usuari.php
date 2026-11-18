<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuari extends Model
{
    protected $table = 'usuaris';

    public function metge() {
        return $this->belongsTo(Metge::class);
    }
}
