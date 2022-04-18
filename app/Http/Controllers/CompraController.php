<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use Validator;
use App\Http\Controllers\BaseController as BaseController;


class CompraController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Compra::with(['proveedor'])->orderBy('created_at', 'asc');
            

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
        $id_proveedor = $request->input("id_proveedor");
        $fecha = $request->input("fecha");
        $timbrado = $request->input("timbrado");
        $numero_factura = $request->input("numero_factura");
        $tipo_boleta = $request->input("tipo_boleta");
        $tipo_pago = $request->input("tipo_pago");
        $entregado = $request->input("entregado");
        $activo = $request->input("activo");
        $detalles = $request->input("detalles");

        $validator = Validator::make($request->all(), [
            'id_proveedor'  => 'required',
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

        $compra = new Compra();
        $compra->id_proveedor = $id_proveedor;
        $compra->fecha = $fecha;
        $compra->timbrado =$timbrado;
        $compra->numero = $numero_factura;
        $compra->tipo_pago = $tipo_pago;
        $compra->tipo_boleta = $tipo_boleta;
       

        if ($compra->save()) {

            foreach ($detalles as $detalle) {
                $DetalleCompra = new DetalleCompra();
                $DetalleCompra->id_pedido = $pedido->id;
                $DetalleCompra->cantidad = $detalle['cantidad'];
                $DetalleCompra->descripcion = $detalle['id_producto'];
                $DetalleCompra->precio_unitario = $detalle['precio'];
                if (!$DetalleCompra->save()) {
                    return $this->sendResponse(true, 'Detalle de compra no registrado', $facturaDetalle, 400);
                    break;
                }
            }

            return $this->sendResponse(true, 'Compra registrado', $compra, 201);
        }
        
        return $this->sendResponse(false, 'Compra no registrado', null, 400);
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

        if (is_object($pedido)) {
            return $this->sendResponse(true, 'Se listaron exitosamente los registros', $pedido, 200);
        }

        return $this->sendResponse(false, 'No se encontro el pedido', null);
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
        $id_compra = $request->input("id_compra");
        $fecha = $request->input("fecha");
        $timbrado = $request->input("timbrado");
        $numero_factura = $request->input("numero_factura");
        $tipo_boleta = $request->input("tipo_boleta");
        $tipo_pago = $request->input("tipo_pago");
        $entregado = $request->input("entregado");
        $activo = $request->input("activo");
        $detalles = $request->input("detalles");

        $validator = Validator::make($request->all(), [
            'id_proveedor'  => 'required',
            'fecha'  => 'required',
            'timbrado' => 'required',
            'numero_factura'  => 'required',
            'tipo_boleta'  => 'required',
            'tipo_pago'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }



        $compra = Compra::find($id);
        if ($compra) {
            $compra->id_cliente = $id_cliente;
            $compra->fecha = $fecha;
            $compra->timbrado =$timbrado;
            $compra->numero = $numero_factura;
            $compra->tipo_pago = $tipo_pago;
            $compra->tipo_boleta = $tipo_boleta;

            if ($compra->save()) {
                $detalleCompra = FacturaDetalle::where([['id_compra', '=', $compra->id]])->first();
                if (!$detalleCompra) return $this->sendResponse(false, 'No se encontro detalle de la Compra', null, 404);

                foreach ($detalles as $detalle) {
                    $DetalleCompra = new DetalleCompra();
                    $DetalleCompra->id_pedido = $pedido->id;
                    $DetalleCompra->cantidad = $detalle['cantidad'];
                    $DetalleCompra->id_producto = $detalle['id_producto'];
                    $DetalleCompra->precio = $detalle['precio'];

                    if (!$DetalleCompra->save()) {
                        return $this->sendResponse(true, 'Detalle de la compra no actualizada', $DetalleCompra, 400);
                        break;
                    }
                }

                return $this->sendResponse(true, 'Compra actualizada', $pedido, 200);
            }
            
            return $this->sendResponse(false, 'Compra no actualizada', null, 400);
        }
        
        return $this->sendResponse(false, 'No se encontro la compra', null, 404);
        
        
    
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
            $pedido->activo = ($pedido->anulado == 'N') ? 'S' : 'N';
            
            if ($pedido->update()) {
                return $this->sendResponse(true, 'Factura actualizada', $pedido, 200);
            }
            
            return $this->sendResponse(false, 'Factura no actualizada', $pedido, 400);
        }
        
        return $this->sendResponse(true, 'No se encontro la factura', $pedido, 404);
    }
}
