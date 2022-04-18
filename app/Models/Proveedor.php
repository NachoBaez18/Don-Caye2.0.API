<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'proveedors';
    protected $perPage = 5;

    protected $fillable =[
        'nombre','telefono', 'ciudad','cedulaRuc','activo'	
    ];
}
