<?php

namespace App\Http\Requests;

use App\Base\ApiFormRequest;

class ArticleWrite extends ApiFormRequest
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
            'data.title' => ['required', 'string', 'max:255'],
            'data.description' => ['required', 'string']
        ];
    }
}
