<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'zip_code',
        'ddd',
        'ibge_code',
        'uf_id',
    ];

    public function uf()
    {
        return $this->belongsTo(Uf::class);
    }
}
