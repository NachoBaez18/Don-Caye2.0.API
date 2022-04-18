<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proveedor;

class Compra extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'compras';
    protected $perPage = 5;

    protected $fillable =[
        'id_cliente','fecha','tipo_boleta', 'numero_factura','timbrado',
        'tipo_pago','entregado','activo'	
    ];

    public function proveedor(){
        return $this->belongsTo(Proveedor::class,'id_cliente','id');
    }

}
