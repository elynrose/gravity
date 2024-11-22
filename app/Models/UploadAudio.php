<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class UploadAudio extends Model
{
    use HasFactory;

    public function new($audioBinary)
    {
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
    }
}
