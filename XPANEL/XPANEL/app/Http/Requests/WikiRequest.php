<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WikiRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'slug' => 'required|string|max:255|unique:x_wiki_posts,slug',
            'icon' => 'string|max:255',
            'description' => 'required|string|max:160',
            'category_id' => 'required|integer|exists:x_wiki_categories,id',
        ];
    }
}
