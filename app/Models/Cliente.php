<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'cliente';
    protected $perPage = 5;

    protected $fillable =[
        'nombre','telefono', 'ciudad','cedulaRuc','activo'	
    ];
}
