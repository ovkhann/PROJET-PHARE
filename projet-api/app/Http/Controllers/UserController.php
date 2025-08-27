<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Gestion des utilisateurs"
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"id","first_name","last_name","email","role"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *     @OA\Property(property="role", type="string", example="ROLE_USER"),
 *     @OA\Property(property="street_name", type="string", nullable=true, example="123 rue Exemple"),
 *     @OA\Property(property="delivery_address", type="integer", nullable=true, example=1),
 *     @OA\Property(property="billing_address", type="integer", nullable=true, example=2),
 *     @OA\Property(property="token", type="string", example="a1b2c3d4e5")
 * )
 */

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Liste des utilisateurs",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Créer un utilisateur",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'role' => ['required', new EnumRule(RoleEnum::class)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
            'street_name' => 'nullable|string',
            'delivery_address' => 'nullable|exists:cities,id',
            'billing_address' => 'nullable|exists:cities,id',
        ]);

        $user = new User();
        $user->fill($formFields);
        $user->token = Str::random(10);
        $user->role = 'ROLE_USER';
        $user->save();

        return response()->json($user, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Afficher un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur trouvé",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Mettre à jour un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur mis à jour",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    public function update(Request $request, User $user)
    {
        $formFields = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'role' => [new EnumRule(RoleEnum::class)],
            'email' => ['string', 'email', 'max:255', Rule::unique('users', 'email')],
        ]);

        $user->fill($formFields);
        $user->save();

        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Supprimer un utilisateur",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Utilisateur supprimé"),
     *     @OA\Response(response=404, description="Utilisateur non trouvé")
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => 'success']);
    }

    /**
     * @OA\Put(
     *     path="/api/users/address",
     *     summary="Mettre à jour l'adresse de l'utilisateur authentifié",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"street_name","billing_address","delivery_address"},
     *             @OA\Property(property="street_name", type="string", example="123 rue Exemple"),
     *             @OA\Property(property="billing_address", type="integer", example=1),
     *             @OA\Property(property="delivery_address", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Adresse mise à jour")
     * )
     */
    public function updateAddress(Request $request)
    {
        $formFields = $request->validate([
            'street_name' => 'required|string|max:255',
            'billing_address' => 'required|exists:cities,id',
            'delivery_address' => 'required|exists:cities,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->fill($formFields);
        $user->save();

        return response()->json(['success' => 'success']);
    }
}
