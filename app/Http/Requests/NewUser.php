<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewUser extends FormRequest
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
            "name"=>["required","max:100"],
            "email"=>["required","email","unique:users"],
            "mobile_no"=>["required","digits:10","unique:users"],
            "password"=>["required","min:8","max:255"]
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
            "mobile_no.unique"=>"mobile no {$this->input('mobile_no')} already exists",
            "password.required"=>"password is mandatory",
            "password.min"=>"password must be minimum 8 characters",
            "password.max"=>"password must be maximum 255 characters",
        ];
    }
}
