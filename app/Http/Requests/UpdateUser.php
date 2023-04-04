<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            "name"=>["sometimes","required","max:100"],
            "email"=>["sometimes","required","email","unique:users,email,{$this->input('id')}"],
            "mobile_no"=>["sometimes","required","digits:10","unique:users,mobile_no,{$this->input('id')}"],
        ];
    }

    public function messages():array
    {
        
        return [
            "name.required"=>"name is mandatory",
            "name.max"=>"name must be maximum 100 characters",
            "email.required"=>"email id is mandatory",
            "email.email"=>"invalid email id",
            "email.unique"=>"email ID {$this->input('email')} already exists",
            "mobile_no.required"=>"mobile no is required",
            "mobile_no.digits"=>"mobile no must be 10 digits only",
            "mobile_no.unique"=>"mobile no {$this->input('mobile_no')} already exists"
        ];
    }
}
