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
            $sort = $request->input('Sort', 'asc');
            $numFilter = $request->input('NumFilter');
            $textFilter = $request->input('TextFilter');
            $numPage = $request->input('NumPage');
    
            $query = Empleado::with('provincia');
           
    
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
            $totalRecords = $query->count();
            $totalPages = ceil($totalRecords / $numPerPage); // Calcular total de páginas
            
            $query->orderBy($sort, $order);
            
            //$empleados = $query->paginate($numPerPage);
            $empleados = $query->paginate($numPerPage, ['*'], 'page', $numPage);
            return response()->json([
                'status' => true,
                'message' => 'Empleados retrieved successfully',
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
        echo "show";
        $Empleado = Empleado::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Empleado found successfully',
            'data' => $Empleado
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreArchivo = $foto->getClientOriginalName();
            $foto->move(public_path('empleados'), $nombreArchivo);
        }
        $empleado = Empleado::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Empleado creado exitosamente',
            'data' => $empleado
        ], 201);
    }
    public function update(Request $request, $id)
{
    $empleado = Empleado::find($id);

    if (!$empleado) {
        return response()->json([
            'status' => false,
            'message' => 'Empleado no encontrado',
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'nombres' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422);
    }

    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $nombreArchivo = $foto->getClientOriginalName();
        $foto->move(public_path('empleados'), $nombreArchivo);
        $empleado->foto = $nombreArchivo;
    }

    $empleado->update($request->all());

    return response()->json([
        'status' => true,
        'message' => 'Empleado actualizado exitosamente',
        'data' => $empleado
    ], 200);
}


    public function destroy($id)
    {
        $Empleado = Empleado::findOrFail($id);
        $Empleado->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Empleado deleted successfully'
        ], 204);
    }
}