<?php

namespace App\Http\Requests;

use App\Models\Video;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreVideoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('video_create');
    }

    public function rules()
    {
        return [
            'project_id' => [
                'required',
                'integer',
            ],
            'video_code' => [
                'string',
                'nullable',
            ],
            'minutes' => [
                'string',
                'nullable',
            ],
        ];
    }
}
