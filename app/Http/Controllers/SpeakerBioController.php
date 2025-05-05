<?php

namespace App\Http\Controllers;

use App\Models\SpeakerBio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SpeakerBioController extends Controller
{
    public function generateBio(Request $request)
    {
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

        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that writes first-person speaker bios.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
        ]);

        // store the response in the database
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to generate bio'], 500);
        }
        $response = $response->json();
        if (!isset($response['choices'][0]['message']['content'])) {
            return response()->json(['error' => 'Invalid response from OpenAI'], 500);
        }
        // Store the generated bio in the database
        SpeakerBio::create([
            'user_id' => Auth::user()->id,
            'bio' => $response['choices'][0]['message']['content'],
        ]);

        return response()->json([
            'bio' => $response['choices'][0]['message']['content'],
        ]);
        //stor
    }
}
