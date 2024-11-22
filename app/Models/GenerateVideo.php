<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class GenerateVideo extends Model
{
    use HasFactory;

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    function create($imagePath, $mp3Path)
    {
        // Create a new Guzzle HTTP client
        $client = new Client();

        // Prepare the request payload for the D-ID API
        $payload = [
            "source_url" => $imagePath,
            "script" => [
                "type" => "audio",
                "audio_url" => $mp3Path,
            ],
            "config" => [
                "stitch" => true,
            ],
        ];

        // Send a POST request to the D-ID API to generate the talking head video
        try {
            $response = $client->request('POST', 'https://api.d-id.com/talks', [
                'body' => json_encode($payload),
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => 'Basic ' . env('DID_API_KEY'),
                    'content-type' => 'application/json',
                ],
            ]);

        // Decode the JSON response from the API
        //$data = json_decode($response->getBody()->getContents(), true);
       // \Log::info('Response: '.$data);
        return json_decode($response->getBody()->getContents(), true);


        } catch (\Exception $e) {
            // Handle any errors that occur during the API request
            return;
        }

    }
}
