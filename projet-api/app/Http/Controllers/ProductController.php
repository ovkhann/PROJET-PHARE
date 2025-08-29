<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        // Routes admin : nécessite auth + rôle admin
        $this->middleware(['auth:sanctum', 'admin'])->only(['store', 'update', 'destroy']);
    }

    /** ---------- PUBLIC ---------- */
    public function index()
    {
        $products = Product::with(['category', 'options'])
            ->where('archived', 0)
            ->get();

        // Ne renvoie que les champs publics
        $publicProducts = $products->map(function ($product) {
            // Si picture est null, on met un tableau vide
            $pictures = is_array($product->picture) ? $product->picture : ($product->picture ? [$product->picture] : []);

            // On transforme chaque chemin en URL complète
            $pictures = array_map(fn($img) => asset(str_replace('public/', 'storage/', $img)), $pictures);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'picture' => $pictures, // tableau d'URLs
                'description' => $product->description,
                'category' => $product->category,
                'options' => $product->options,
            ];
        });

        return response()->json($publicProducts);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'options'])->find($id);
        if (!$product) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        // Gestion du tableau d'images
        $pictures = is_array($product->picture) ? $product->picture : ($product->picture ? [$product->picture] : []);
        $pictures = array_map(fn($img) => asset(str_replace('public/', 'storage/', $img)), $pictures);

        $publicProduct = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'picture' => $pictures, // tableau d'URLs
            'description' => $product->description,
            'category' => $product->category,
            'options' => $product->options,
        ];

        return response()->json($publicProduct);
    }

    /** ---------- ADMIN ---------- */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'picture' => 'nullable|image|max:2048',
            'description' => 'required|string|max:255',
            'archived' => 'boolean',
            'category_id' => 'required|exists:categories,id',
            'option_ids' => 'array',
            'option_ids.*' => 'exists:options,id',
        ]);

        if ($request->hasFile('picture')) {
            $pictures = [];
            foreach ($request->file('picture') as $file) {
                $pictures[] = $file->store('products', 'public');
            }
            $data['picture'] = $pictures;
        }

        $product = Product::create($data);

        if (isset($data['option_ids'])) {
            $product->options()->sync($data['option_ids']);
        }

        return response()->json($product->load('options'), 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produit non trouvé'], 404);

        $data = $request->validate([
            'name' => 'string|max:255',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'picture' => 'nullable|image|max:2048',
            'description' => 'string|max:255',
            'archived' => 'boolean',
            'category_id' => 'exists:categories,id',
            'option_ids' => 'array',
            'option_ids.*' => 'exists:options,id',
        ]);

        if ($request->hasFile('picture')) {
            $pictures = [];
            foreach ($request->file('picture') as $file) {
                $pictures[] = $file->store('products', 'public');
            }
            $data['picture'] = $pictures;
        }

        $product->update($data);

        if (isset($data['option_ids'])) {
            $product->options()->sync($data['option_ids']);
        }

        return response()->json($product->load('options'));
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Produit non trouvé'], 404);

        $product->delete();

        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}
