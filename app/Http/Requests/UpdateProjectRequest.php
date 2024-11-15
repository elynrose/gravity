<?php

namespace App\Http\Requests;

use App\Models\Project;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('project_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'prompt' => [
                'required',
            ],
            'script' => [
                'required',
            ],
            'status' => [
                'string',
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
