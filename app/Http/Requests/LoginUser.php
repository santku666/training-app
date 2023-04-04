<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUser extends FormRequest
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
            "email"=>"required|email",
            "password"=>"required"
        ];
    }
    public function messages():array
    {
        return [
            "email.required"=>"Email ID is Required",
            "email.email"=>"Invalid Email ID",
            "password"=>"Password is Required"
        ];
    }

    public function credentials()
    {
        return $this->only(['email','password']);
    }
}
