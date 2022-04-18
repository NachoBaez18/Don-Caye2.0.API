<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Validator;
use App\Http\Controllers\BaseController as BaseController;

class PedidosController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Pedido::with(['cliente'])->orderBy('created_at', 'asc');
            
    

        $paginar = $request->query('paginar');
        $listar = (boolval($paginar)) ? 'paginate' : 'get';

        $data = $query->$listar();
        
        return $this->sendResponse(true, 'Listado obtenido exitosamente', $data, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id_cliente = $request->input("id_cliente");
        $fecha = $request->input("fecha");
        $timbrado = $request->input("timbrado");
        $numero_factura = $request->input("numero_factura");
        $tipo_boleta = $request->input("tipo_boleta");
        $tipo_pago = $request->input("tipo_pago");
        $entregado = $request->input("entregado");
        $activo = $request->input("activo");
        $detalles = $request->input("detalles");

        $validator = Validator::make($request->all(), [
            'id_cliente'  => 'required',
            'fecha'  => 'required',
            'timbrado' => 'required',
            'numero_factura'  => 'required',
            'tipo_boleta'  => 'required',
            'tipo_pago'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }

        //validar que los detalles llegaron
        if (count($detalles) <= 0) {
            return $this->sendResponse(false, 'Debe agregar por lo menos una sucursal', null);
        }

        $pedido = new Pedido();
        $pedido->id_cliente = $id_cliente;
        $pedido->fecha = $fecha;
        $pedido->timbrado =$timbrado;
        $pedido->numero = $numero_factura;
        $pedido->tipo_pago = $tipo_pago;
        $pedido->tipo_boleta = $tipo_boleta;
       

        if ($pedido->save()) {

            foreach ($detalles as $detalle) {
                $DetallePedido = new DetallePedido();
                $DetallePedido->id_pedido = $pedido->id;
                $DetallePedido->cantidad = $detalle['cantidad'];
                $DetallePedido->descripcion = $detalle['id_producto'];
                $DetallePedido->precio_unitario = $detalle['precio'];
                if (!$DetallePedido->save()) {
                    return $this->sendResponse(true, 'Detalle de pedido no registrado', $facturaDetalle, 400);
                    break;
                }
            }

            return $this->sendResponse(true, 'Pedido registrado', $pedido, 201);
        }
        
        return $this->sendResponse(false, 'Pedido no registrado', null, 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedido = Pedido::with(['cliente'])->find($id);

        if (is_object($compra)) {
            return $this->sendResponse(true, 'Se listaron exitosamente los registros', $compra, 200);
        }

        return $this->sendResponse(false, 'No se encontro la compra ', null);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $id_cliente = $request->input("id_cliente");
        $fecha = $request->input("fecha");
        $timbrado = $request->input("timbrado");
        $numero_factura = $request->input("numero_factura");
        $tipo_boleta = $request->input("tipo_boleta");
        $tipo_pago = $request->input("tipo_pago");
        $entregado = $request->input("entregado");
        $activo = $request->input("activo");
        $detalles = $request->input("detalles");

        $validator = Validator::make($request->all(), [
            'id_cliente'  => 'required',
            'fecha'  => 'required',
            'timbrado' => 'required',
            'numero_factura'  => 'required',
            'tipo_boleta'  => 'required',
            'tipo_pago'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }



        $pedido = Pedido::find($id);
        if ($pedido) {
            $pedido->id_cliente = $id_cliente;
            $pedido->fecha = $fecha;
            $pedido->timbrado =$timbrado;
            $pedido->numero = $numero_factura;
            $pedido->tipo_pago = $tipo_pago;
            $pedido->tipo_boleta = $tipo_boleta;

            if ($factura->save()) {
                $facturaDetalle = FacturaDetalle::where([['id_factura', '=', $factura->id]])->first();
                if (!$facturaDetalle) return $this->sendResponse(false, 'No se encontro detalle de la Factura', null, 404);

                foreach ($detalles as $detalle) {
                    $DetallePedido = new DetallePedido();
                    $DetallePedido->id_pedido = $pedido->id;
                    $DetallePedido->cantidad = $detalle['cantidad'];
                    $DetallePedido->descripcion = $detalle['id_producto'];
                    $DetallePedido->precio_unitario = $detalle['precio'];

                    if (!$facturaDetalle->save()) {
                        return $this->sendResponse(true, 'Detalle de Pedido no actualizada', $DetallePedido, 400);
                        break;
                    }
                }

                return $this->sendResponse(true, 'Pedido actualizada', $pedido, 200);
            }
            
            return $this->sendResponse(false, 'Pedido no actualizada', null, 400);
        }
        
        return $this->sendResponse(false, 'No se encontro la Pedido', null, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pedido = Pedido::find($id);

        if ($pedido) {
            $pedido->activo = ($pedido->activo == 'N') ? 'S' : 'N';
            
            if ($pedido->update()) {
                return $this->sendResponse(true, 'Factura actualizada', $pedido, 200);
            }
            
            return $this->sendResponse(false, 'Factura no actualizada', $pedido, 400);
        }
        
        return $this->sendResponse(true, 'No se encontro la factura', $pedido, 404);
    }
}
