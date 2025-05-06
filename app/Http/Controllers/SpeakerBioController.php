<?php

namespace App\Http\Controllers;

use App\Models\SpeakerBio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpeakerBioController extends Controller
{
    /**
     * Generate speaker bio using external AI service
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateBio(Request $request)
    {
        try {
            // Simple debug check
            Log::info("Bio generation request received");

            // Validate incoming request data
            $validated = $request->validate([
                'full_name' => 'required|string',
                'job_title' => 'required|string',
                'workplace' => 'required|string',
                'expertise' => 'required|string',
                'experience_years' => 'required|numeric',
                'topics' => 'required|string',
                'events' => 'nullable|string',
                'awards' => 'nullable|string',
                'motivation' => 'required|string',
                'fun_fact' => 'nullable|string',
            ]);

            // Format the body as JSON string
            $jsonBody = json_encode($validated);
            Log::info("Sending request to n8n webhook");

            // Use Laravel's HTTP client with more direct error handling
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->withBody($jsonBody, 'application/json')
                ->get('https://kyle146.app.n8n.cloud/webhook-test/1dfe3e8a-e3e4-40d5-8375-69da944393de');

            // Parse the JSON response
            $responseData = $response->json();

            // Log the response
            Log::info("Response status: " . $response->status());
            Log::info("Response body: " . json_encode($responseData));

            if ($response->successful()) {
                // Extract the bio text from the "output" field
                if (isset($responseData['output'])) {
                    return response()->json([
                        'success' => true,
                        'bio' => $responseData['output']
                    ]);
                } else {
                    Log::error("Missing 'output' field in API response");
                    return response()->json([
                        'success' => false,
                        'error' => 'Invalid API response format: missing output field'
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'External API error: ' . $response->status()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error("Bio generation error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simple endpoint to check API connectivity
     *
     * @return \Illuminate\Http\Response
     */
    public function ping()
    {
        Log::info('API ping received');
        return response('API is available', 200);
    }
}
