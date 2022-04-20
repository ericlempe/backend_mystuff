<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService
{
    public function login($data)
    {
        $user = (new User())->getUser($data['email']);

        if (!$user) {
            throw new Exception('Usuário não encontrado.');
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new Exception('Senha incorreta.');
        }

        $token = $user->createToken('mystuff@token');
        return $token->plainTextToken;
    }

    public function register($data)
    {
        return (new User())->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $data['avatar'] ?? asset('img/avatar.png'),
        ]);
    }
}
