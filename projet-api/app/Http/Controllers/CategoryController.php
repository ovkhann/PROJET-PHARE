<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('products')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Liste des catégories récupérée avec succès'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Catégorie créée avec succès'
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('products')->find($id);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Détails de la catégorie récupérés avec succès'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::find($id);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'data' => $category,
            'message' => 'Catégorie mise à jour avec succès'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée avec succès'
        ], 200);
    }


    // public function search(Request $request)
    // {
    //     $query = $request->input('q');
    //     return response()->json(['query' => $query]);
    // }
}
