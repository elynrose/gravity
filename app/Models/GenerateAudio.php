<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;


class GenerateAudio extends Model
{
    use HasFactory;

      public function textToSpeech($text, $voice)
    {
        // Prepare the payload for the API request
        $payload = [
            'input' => $text,
            'voice' => $voice,
            'model' => 'tts-1',
            'config' => [
                'encoding' => 'MP3', // Supported encodings: 'MP3', 'LINEAR16', 'OGG_OPUS'
                'sample_rate_hertz' => 24000,
                'language_code' => 'en-US',
            ],
        ];

        // Initialize the Guzzle HTTP client
        $client = new Client();

        try {
            // Make the request to the OpenAI Text-to-Speech API
            $response = $client->post('https://api.openai.com/v1/audio/speech', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            // Check if the response status code indicates success
            if ($response->getStatusCode() == 200) {
                $audioBinary = $response->getBody();

                // Check if audio was successfully generated
                if ($audioBinary == null) {
                    return response()->json(['error' => 'Failed to generate audio'], 500);
                }

                // Generate a unique file name for the audio
                $fileName = 'audio/' . uniqid() . '.mp3';

                // Save the audio file to S3 storage
                $path = Storage::disk('s3')->put($fileName, $audioBinary);

                // Check if the audio file was successfully saved
                if ($path) {

                    // Get the URL of the saved audio file
                    $mp3Path = Storage::disk('s3')->url($fileName);

                    return $mp3Path; // Return the URL of the saved audio file
                    
                } else {
                    \Log::error('Failed to save audio file to S3 storage');
                }
            } else {
                // Return an error response if the API request was unsuccessful
                \Log::error('Failed to generate audio: ' . $response->getBody());
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the API request
            \Log::error('Failed to generate audio: ' . $e->getMessage());
        }
    }
}
