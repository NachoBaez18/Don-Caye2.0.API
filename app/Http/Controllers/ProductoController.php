<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Validator;
use App\Http\Controllers\BaseController as BaseController;

class ProductoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Producto::orderBy('nombre', 'asc');

        $nombre = $request->query('nombre');
        if ($nombre) {
            $query->where('nombre', 'LIKE', '%'.$nombre.'%');
        }

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
        $nombre = $request->input("nombre");
        $stock = $request->input("stock");
        $precio_compra = $request->input("precio_compra");
        $precio_venta = $request->input("precio_venta");

        $validator = Validator::make($request->all(), [
            'nombre'  => 'required',
            'stock'  => 'required',
            'precio_compra'  => 'required',
            'precio_venta'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }

        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->stock = $stock;
        $producto->precio_compra = $precio_compra;
        $producto->precio_venta = $precio_venta;
        $producto->activo = 'S';

        if ($producto->save()) {
            return $this->sendResponse(true, 'Producto registrado', $producto, 201);
        }
        
        return $this->sendResponse(false, 'Producto no registrado', null,400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::find($id);

        if (is_object($producto)) {
            return $this->sendResponse(true, 'Se listaron exitosamente los registros', $producto, 200);
        }

        return $this->sendResponse(false, 'No se encontro el cliente', null);
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
        $nombre = $request->input("nombre");
        $stock = $request->input("stock");
        $precio_compra = $request->input("precio_compra");
        $precio_venta = $request->input("precio_venta");

        $validator = Validator::make($request->all(), [
            'nombre'  => 'required',
            'stock'  => 'required',
            'precio_compra'  => 'required',
            'precio_venta'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }
        $producto = Producto::find($id);
        if ($producto) {
            $producto->nombre = $nombre;
            $producto->stock = $stock;
            $producto->precio_compra = $precio_compra;
            $producto->precio_venta = $precio_venta;
            $producto->activo = 'S';
            if ($producto->save()) {
                return $this->sendResponse(true, 'Producto actualizado', $producto, 200);
            }
            
            return $this->sendResponse(false, 'Producto no actualizado', null, 400);
        }
        
        return $this->sendResponse(false, 'No se encontro la Producto', null, 404);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if ($producto) {
            $producto->activo = ($producto->activo == 'S') ? 'N' : 'S';
            
            if ($producto->update()) {
                return $this->sendResponse(true, 'Producto eliminado', $producto, 200);
            }
            
            return $this->sendResponse(false, 'Producto Eliminado', $producto, 400);
        }
        return $this->sendResponse(true, 'No se encontro el Producto', $producto, 404);
    }
}
