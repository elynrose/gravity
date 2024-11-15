<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;


class GenerateAvatar extends Model
{
    use HasFactory;

    /**
     * Send the generated prompt to the DALL-E API.
     *
     * @param  string  $prompt
     * @return array
     */
    public function sendToDalle($prompt)
    {
        try {
            // Set the OpenAI API key from the environment
            $apiKey = env('OPENAI_KEY');

            // Prepare the API request payload
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
            ])->timeout(60)->post('https://api.openai.com/v1/images/generations', [
                'prompt' => $prompt,          // The description prompt
                'n' => 1,                     // Number of images to generate
                'size' => '1792x1024',        // Image size
                'model' => 'dall-e-3',        // Model to use
            ]);

            // Decode and return the response from OpenAI
            return $response->json();
            
        } catch (\Exception $e) {
            // Handle the exception and return a JSON error response
            return response()->json(['error' => 'Failed to generate image, please wait and try again.'.$e], 500);
        }
    }
}
