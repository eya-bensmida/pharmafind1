<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function send(Request $request, ChatbotService $chatbot)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $reply = $chatbot->reply($validated['message']);

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}