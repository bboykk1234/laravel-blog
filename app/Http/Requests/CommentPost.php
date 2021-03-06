<?php

namespace App\Http\Requests;

use App\Base\ApiFormRequest;

class CommentPost extends ApiFormRequest
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
            'data.article_id' => ['required', 'exists:articles,id'],
            'data.body' => ['required', 'string']
        ];
    }
}
