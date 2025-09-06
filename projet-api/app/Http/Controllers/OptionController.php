<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $options = Option::with('products')->get();

        return response()->json([
            'success' => true,
            'data' => $options,
            'message' => 'Liste des options récupérée avec succès'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:5',
        ]);

        $option = Option::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $option,
            'message' => 'Option créée avec succès'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $option = Option::with('products')->find($id);

        return response()->json([
            'success' => true,
            'data' => $option,
            'message' => 'Détails de la taille récupérés avec succès'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string|max:5',
        ]);

        $option = Option::find($id);

        $option->update([
            'size' => $request->size,
        ]);

        return response()->json([
            'success' => true,
            'data' => $option,
            'message' => 'Option mise à jour avec succès'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $option = Option::find($id);

        $option->delete();

        return response()->json([
            'success' => true,
            'message' => 'Option supprimée avec succès'
        ], 200);
    }
}
