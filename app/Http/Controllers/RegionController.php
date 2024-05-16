<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    public function index()
    {
        $Regions = Region::all();
        return response()->json([
            'status' => true,
            'message' => 'Regions retrieved successfully',
            'data' => $Regions
        ], 200);
    }

    public function show($id)
    {
        $Region = Region::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Region found successfully',
            'data' => $Region
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

        $Region = Region::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Region created successfully',
            'data' => $Region
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

        $Region = Region::findOrFail($id);
        $Region->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Region updated successfully',
            'data' => $Region
        ], 200);
    }

    public function destroy($id)
    {
        $Region = Region::findOrFail($id);
        $Region->delete();
        
        return response()->json([
            'status' => true,
            'message' => 'Region deleted successfully'
        ], 204);
    }
}