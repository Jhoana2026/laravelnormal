<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conexion extends Model
{
    protected $table = 'estudiantes';
    protected $primaryKey = 'cedula';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'telefono',
        'direccion',
    ];

    
}
