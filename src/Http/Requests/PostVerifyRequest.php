<?php

namespace Montopolis\MagicAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostVerifyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            '_token' => 'required|string|min:20',
            'key' => 'required|string',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function response(array $errors)
    {
        return response()->json([
            'message' => 'Bad request.',
            'errors' => $errors,
        ], 400);
    }
}