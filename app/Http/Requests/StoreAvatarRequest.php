<?php

namespace App\Http\Requests;

use App\Models\Avatar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAvatarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('avatar_create');
    }

    public function rules()
    {
        return [
            'project_id' => [
                'required',
                'integer',
            ],
            'avatar_url' => [
                'string',
                'nullable',
            ],
            'avatar' => [
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
