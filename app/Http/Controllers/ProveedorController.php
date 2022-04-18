<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use Validator;
use App\Http\Controllers\BaseController as BaseController;

class ProveedorController extends BaseController
{
  
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Proveedor::orderBy('nombre', 'asc');

        $nombre = $request->query('nombre');
        if ($nombre) {
            $query->where('nombre', 'LIKE', '%'.$nombre.'%');
        }

        $telefono = $request->query('telefono');
        if ($telefono) {
            $query->where('telefono', 'LIKE', '%'.$telefono.'%');
        }

        $ciudad = $request->query('ciudad');
        if ($ciudad) {
            $query->where('ciudad', 'LIKE', '%'.$ciudad.'%');
        }

        $cedulaRuc = $request->query('cedulaRuc');
        if ($cedulaRuc) {
            $query->where('cedulaRuc', 'LIKE', '%'.$cedulaRuc.'%');
        }

        $activo = $request->query('activo');
        if ($activo) {
            $query->where('activo', 'LIKE', '%'.$activo.'%');
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
        $telefono = $request->input("telefono");
        $ciudad = $request->input("ciudad");
        $cedulaRuc = $request->input("cedulaRuc");

        $validator = Validator::make($request->all(), [
            'nombre'  => 'required',
            'telefono'  => 'required',
            'ciudad'  => 'required',
            'cedulaRuc'  => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }

        $proveedor = new Proveedor();
        $proveedor->nombre = $nombre;
        $proveedor->telefono = $telefono;
        $proveedor->ciudad = $ciudad;
        $proveedor->cedulaRuc = $cedulaRuc;
        $proveedor->activo = 'S';

        if ($proveedor->save()) {
            return $this->sendResponse(true, 'Proveedor registrado', $proveedor, 201);
        }
        
        return $this->sendResponse(false, 'Proveedor no registrado', null, 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proveedor = Proveedor::find($id);

        if (is_object($proveedor)) {
            return $this->sendResponse(true, 'Se listaron exitosamente los registros', $proveedor, 200);
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
        $telefono = $request->input("telefono");
        $ciudad = $request->input("ciudad");
        $cedulaRuc = $request->input("cedulaRuc");

        $validator = Validator::make($request->all(), [
            'nombre'  => 'required',
            'telefono'  => 'required',
            'ciudad'  => 'required',
            'cedulaRuc' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, 'Error de validacion', $validator->errors(), 400);
        }

        $proveedor = Proveedor::find($id);
        if ($proveedor) {
            $proveedor->nombre = $nombre;
             $proveedor->telefono = $telefono;
             $proveedor->ciudad = $ciudad;
             $proveedor->cedulaRuc = $cedulaRuc;
             $proveedor->activo = 'S';
            if ($proveedor->save()) {
                return $this->sendResponse(true, 'Prproveedor actualizado', $proveedor, 200);
            }
            
            return $this->sendResponse(false, 'Prproveedor no actualizado', null, 400);
        }
        
        return $this->sendResponse(false, 'No se encontro la Prproveedor', null, 404);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);

        if ($proveedor) {
            $proveedor->activo = ($proveedor->activo == 'S') ? 'N' : 'S';
            
            if ($proveedor->update()) {
                return $this->sendResponse(true, 'Proveedor eliminado', $proveedor, 200);
            }
            
            return $this->sendResponse(false, 'Proveedor Eliminado', $proveedor, 400);
        }
        return $this->sendResponse(true, 'No se encontro el cliente', $proveedor, 400);
}
}
