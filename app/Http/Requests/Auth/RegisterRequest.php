<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'same:passwordConfirmation'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'email.required' => 'E-mail é obrigatório',
            'email.unique' => 'Este E-mail já existe',
            'password.required' => 'Senha é obrigatório',
            'password.min' => 'Senha precisa conter no mínimo 6 caracteres',
            'password.same' => 'Senhas não correspondem',
        ];
    }
}
