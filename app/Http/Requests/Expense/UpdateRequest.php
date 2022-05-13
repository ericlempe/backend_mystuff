<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'expiration_day' => ['required', 'integer', 'min:1', 'max:31'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'expiration_day.required' => 'Dia de Vencimento é obrigatório',
            'expiration_day.integer' => 'O Vencimento precisa ser um valor inteiro',
            'expiration_day.min' => 'O Vencimento precisa ser um valor entre 1 a 28',
            'expiration_day.max' => 'O Vencimento precisa ser um valor entre 1 a 28',
        ];
    }
}
