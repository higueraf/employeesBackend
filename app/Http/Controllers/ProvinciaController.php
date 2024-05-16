<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinciaController extends Controller
{
    public function index()
    {
        $Provincias = Provincia::all();
        return response()->json([
            'status' => true,
            'message' => 'Provincias retrieved successfully',
            'data' => $Provincias
        ], 200);
    }

    public function show($id)
    {
        $Provincia = Provincia::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Provincia found successfully',
            'data' => $Provincia
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $Provincia = Provincia::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Provincia created successfully',
            'data' => $Provincia
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $Provincia = Provincia::findOrFail($id);
        $Provincia->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Provincia updated successfully',
            'data' => $Provincia
        ], 200);
    }

    public function destroy($id)
    {
        $Provincia = Provincia::findOrFail($id);
        $Provincia->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Provincia deleted successfully'
        ], 204);
    }
}