<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'id';
    protected $table = 'producto';
    protected $perPage = 5;

    protected $fillable =[
        'nombre','stock', 'precio_compra','precio_venta','activo'	
    ];
}
