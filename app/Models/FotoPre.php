<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoPre extends Model
{
    protected $table = 'fotos_pre';

    public function trobades(): BelongsTo{
        return $this->belongsTo(Presentacio::class);
    }
}
