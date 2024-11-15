<?php

namespace App\Http\Requests;

use App\Models\Audio;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAudioRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('audio_edit');
    }

    public function rules()
    {
        return [
            'project_id' => [
                'required',
                'integer',
            ],
            'audio_url' => [
                'string',
                'nullable',
            ],
            'token' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
