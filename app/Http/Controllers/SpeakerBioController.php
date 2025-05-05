<?php

namespace App\Http\Controllers;

use App\Models\SpeakerBio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpeakerBioController extends Controller
{
    public function generateBio(Request $request)
    {
        try {
            $data = $request->validate([
                'full_name' => 'required|string',
                'job_title' => 'nullable|string',
                'workplace' => 'nullable|string',
                'expertise' => 'required|string',
                'experience_years' => 'required|numeric',
                'topics' => 'required|string',
                'events' => 'nullable|string',
                'awards' => 'nullable|string',
                'motivation' => 'required|string',
                'fun_fact' => 'nullable|string',
            ]);

            $prompt = "You are a helpful assistant writing a first-person professional speaker bio for Knowledge Oman. Based on the following data, generate only the bio — no other text:\n\n";
            foreach ($data as $key => $value) {
                $prompt .= ucfirst(str_replace('_', ' ', $key)) . ": $value\n";
            }

            $openaiKey = env('OPENAI_API_KEY');
            if (!$openaiKey) {
                Log::error('OpenAI API key is not set');
                return response()->json(['error' => 'OpenAI API key is not configured'], 500);
            }

            $response = Http::withToken($openaiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant that writes first-person speaker bios.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->failed()) {
                Log::error('OpenAI API request failed', [
                    'status' => $response->status(),
                    'response' => $response->json() ?: $response->body(),
                ]);
                return response()->json(['error' => 'Failed to generate bio: ' . ($response->json()['error']['message'] ?? 'API error')], 500);
            }

            $responseData = $response->json();
            if (!isset($responseData['choices'][0]['message']['content'])) {
                Log::error('Invalid response format from OpenAI', ['response' => $responseData]);
                return response()->json(['error' => 'Invalid response from OpenAI'], 500);
            }

            $generatedBio = $responseData['choices'][0]['message']['content'];

            // Only store the bio if the user is authenticated
            if (Auth::check()) {
                SpeakerBio::create([
                    'user_id' => Auth::id(),
                    'bio' => $generatedBio,
                ]);
            }

            return response()->json([
                'bio' => $generatedBio,
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating bio: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
