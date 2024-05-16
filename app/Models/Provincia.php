<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre_provincia',
        'capital_provincia',
        'descripcion_provincia',
        'poblacion_provincia',
        'superficie_provincia',
        'latitud_provincia',
        'longitud_provincia',
        'id_region',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }
}
