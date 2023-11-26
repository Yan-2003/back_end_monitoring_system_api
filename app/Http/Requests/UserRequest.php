<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(request()->routeIs('user.login')){
            return [
                //
                'email' => 'required|string|email|max:255',
                'password' => 'required|min:8',
            ];
        }else{
            return [
                //
                'email' => 'required|string|email|unique:users|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'string|max:255',
                'last_name' => 'required|string|max:255',
                'type' => 'required|string',
                'password' => 'required|min:8',
            ];
        }

    }
}
