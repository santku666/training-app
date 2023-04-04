<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPost extends FormRequest
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
    public function rules():array
    {
        return [
            'title'=>['required','max:100','unique:posts'],
            'description'=>['required']
        ];
    }

    public function messages():array
    {
        return [
            'title.required'=>'Title is Mandatory',
            'title.unique'=>'Title " '.$this->input('title').' " Already Exists',
            'title.max'=>'Title must be maximum 100 Characters',
            'description.required'=>"Description is Mandatory"
        ];
    }
}
