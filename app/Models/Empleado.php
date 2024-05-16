<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'cedula',
        'id_provincia',
        'fecha_nacimiento',
        'email',
        'observaciones',
        'foto',
        'fecha_ingreso',
        'cargo',
        'departamento',
        'jornada_parcial',
        'id_provincia_cargo',
        'observaciones_cargo',
        'sueldo',
        'codigo',
        'estado',
    ];

    // Relación con la tabla provincias
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }

    // Relación con la tabla provincias (para el cargo)
    public function provinciaCargo()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia_cargo');
    }
}
