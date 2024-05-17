<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        
        try {        
            $numPerPage = $request->input('NumRecordsPage', 115);
            $order = $request->input('Order', 'id');
            $allRegisters = $request->input('AllRegisters', false);
            $sort = $request->input('Sort', 'asc');
            $numFilter = $request->input('NumFilter');
            $textFilter = $request->input('TextFilter');
            $nombreFilter = $request->input('NombreFilter');
            $codigoFilter = $request->input('CodigoFilter');
            $numPage = $request->input('NumPage');    
            $query = Empleado::with('provincia')->with('provinciaCargo');
            if ($numFilter && $textFilter) {
                switch ($numFilter) {
                    case 1:
                        $query->where('nombres', 'like', '%' . $textFilter . '%');
                        break;
                    case 2:
                        $query->where('apellidos', 'like', '%' . $textFilter . '%');
                        break;
                    case 3:
                        $query->where('cedula', 'like', '%' . $textFilter . '%');
                        break;
                }
            }
            if ($nombreFilter && $codigoFilter) {
                $query->where(function ($query) use ($nombreFilter) {
                    $query->where('nombres', 'like', '%' . $nombreFilter . '%')
                          ->orWhere('apellidos', 'like', '%' . $nombreFilter . '%');
                });
                $query->where('codigo', 'like', '%' . $codigoFilter . '%');
            } elseif ($nombreFilter) {
                $query->where(function ($query) use ($nombreFilter) {
                    $query->where('nombres', 'like', '%' . $nombreFilter . '%')
                          ->orWhere('apellidos', 'like', '%' . $nombreFilter . '%');
                });
            } elseif ($codigoFilter) {
                $query->where('codigo', 'like', '%' . $codigoFilter . '%');
            }
            
            $totalRecords = $query->count();
            $totalPages = ceil($totalRecords / $numPerPage);
            $query->orderBy($sort, $order);
            if (!$allRegisters){
                $empleados = $query->paginate($numPerPage, ['*'], 'page', $numPage);
            } else {
                $empleados = $query->get();
            }
            return response()->json([
                'status' => true,
                'message' => 'Employees retrieved successfully',
                'data' => $empleados
            ], 200);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error',
                'data' => $ex->getMessage(),
            ], 500);
        }
    }
    
    public function show($id)
    {
        $Empleado = Empleado::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Employee found successfully',
            'data' => $Empleado
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $foto = $request->file('foto');
            $nombreArchivo = $foto->getClientOriginalName();
            $empleado->foto = 'http://localhost:8000/empleados/'+$nombreArchivo;
        }
        $empleado = Empleado::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Employee created successfully',
            'data' => $empleado
        ], 201);
    }
    public function update(Request $request, $id)
    {
        
        $empleado = Empleado::findOrFail($id);
        if (!$empleado) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found',
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $foto = $request->file('foto');
            $nombreArchivo = $foto->getClientOriginalName();
            $foto->move(public_path('empleados'), $nombreArchivo);
            $empleado->foto = 'http://localhost:8000/empleados/'+$nombreArchivo;
        }

        $empleado->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Employee updated successfully',
            'data' => $empleado
        ], 200);
    }


    public function destroy($id)
    {
        $Empleado = Empleado::findOrFail($id);
        $Empleado->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully'
        ], 204);
    }
}
