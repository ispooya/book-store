<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostBookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'isbn' => 'required|unique:books,id|size:13',
            'title' => 'string|required',
            'description' => 'string|required',
            'authors' => 'array|required',
            'authors.*' => 'required|exists:authors,id',
        ];
    }
}
