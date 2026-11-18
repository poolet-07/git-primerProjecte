<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pacient extends Model
{
    protected $table = 'pacients';

    public function familia(): BelongsTo{
        return $this->belongsTo(Familia::class);
    }
}
