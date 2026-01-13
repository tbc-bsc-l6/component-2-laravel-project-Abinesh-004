<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Module::all();
        
        return response()->json([
            'status' => 'success',
            'count' => $modules->count(),
            'data' => $modules
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json([
                'status' => 'error',
                'message' => 'Module not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $module
        ]);
    }
}
