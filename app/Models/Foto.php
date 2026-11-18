<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $table = 'fotos';

    public function trobada(): BelongsTo{
        return $this->belongsTo(Trobada::class);
    }
}
