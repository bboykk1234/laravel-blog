<?php

namespace App\Http\Requests;

use App\Base\ApiFormRequest;

class RegisterPost extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => ['required', 'array'],
            'data.name' => ['required', 'string', 'max:255'],
            'data.email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'data.password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
