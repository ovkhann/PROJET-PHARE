<?php

namespace App\Http\Controllers;

use App\Models\ProductUser;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductUserController extends Controller
{
    /**
     * Afficher le panier de l’utilisateur authentifié
     */
    public function index()
    {
        $cart = ProductUser::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($cart);
    }

    /**
     * Ajouter un produit au panier
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $product = Product::findOrFail($request->product_id);

        $productUser = ProductUser::firstOrNew([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        // Vérifier le stock
        if (($productUser->quantity ?? 0) + $request->quantity > $product->stock) {
            return response()->json(['message' => 'Quantité indisponible'], 400);
        }

        // Ajouter ou mettre à jour la quantité
        $productUser->quantity = ($productUser->quantity ?? 0) + $request->quantity;
        $productUser->save();

        return response()->json([
            'message' => 'Produit ajouté au panier',
            'product_user' => $productUser
        ]);
    }

    /**
     * Mettre à jour la quantité d’un produit
     */
    public function update(Request $request, $productId)
    {
        // Récupérer la quantité, peu importe le type de body
        $quantity = $request->input('quantity');

        // Vérification de la quantité
        if (!isset($quantity) || !is_numeric($quantity) || $quantity < 1 || $quantity > 100) {
            return response()->json(['message' => 'Quantity requis et doit être entre 1 et 100'], 400);
        }

        // Chercher le produit dans le panier de l'utilisateur
        $productUser = ProductUser::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->firstOrFail();

        // Mettre à jour la quantité
        $productUser->quantity = (int) $quantity;
        $productUser->save();

        return response()->json([
            'message' => 'Quantité mise à jour',
            'product_user' => $productUser
        ]);
    }


    /**
     * Supprimer un produit du panier
     */
    public function destroy($productId)
    {
        $productUser = ProductUser::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->firstOrFail();

        $productUser->delete();

        return response()->json([
            'message' => 'Produit supprimé du panier'
        ]);
    }

    /**
     * Vider le panier
     */
    public function clearCart()
    {
        ProductUser::where('user_id', Auth::id())->delete();

        return response()->json([
            'message' => 'Panier vidé'
        ]);
    }
}
