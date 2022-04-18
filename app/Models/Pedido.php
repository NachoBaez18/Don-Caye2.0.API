<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;

class Pedido extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'pedidos';
    protected $perPage = 5;

    protected $fillable =[
        'id_cliente','fecha','tipo_boleta', 'numero_factura','timbrado',
        'tipo_pago','entregado','activo'	
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class,'id_cliente','id');
    }
}
