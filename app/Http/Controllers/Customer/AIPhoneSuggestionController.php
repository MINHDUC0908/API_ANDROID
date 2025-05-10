<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AIPhoneSuggestionController extends Controller
{
    public function chat(Request $request)
    {
        $message = $request->input('message');

        $client = new Client();
        $apiKey = env('GEMINI_API_KEY'); // Lưu API key trong file .env

        try {
            $response = $client->post('https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent', [
                'query' => ['key' => $apiKey],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => $message]]]
                    ]
                ]
            ]);
            Log::info('Chatbot API nhận request:', $request->all());
            $data = json_decode($response->getBody(), true);

            return response()->json([
                'data' => $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi từ Gemini.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
