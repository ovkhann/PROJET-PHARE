<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $message = Message::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès.',
            'data' => $message
        ]);
    }

    public function index()
    {
        return Message::latest()->get();
    }
}
