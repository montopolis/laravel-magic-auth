<?php

namespace Montopolis\MagicAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            '_token' => 'required|string|min:20'
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors)
    {
        return response()->json([
            'message' => 'Bad request.',
            'errors' => $errors,
        ], 400);
    }
}