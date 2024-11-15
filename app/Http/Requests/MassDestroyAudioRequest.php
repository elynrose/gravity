<?php

namespace App\Http\Requests;

use App\Models\Audio;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAudioRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('audio_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:audios,id',
        ];
    }
}
