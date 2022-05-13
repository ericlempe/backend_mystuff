<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService
{
    public function login($data)
    {
        $user = (new User())->where('email', $data['email'])->first();

        if (!$user) {
            throw new Exception('Usuário não encontrado.');
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new Exception('Senha incorreta.');
        }

        $token = (new JwtService())->createToken(['id' => $user->id]);
        return $token;
    }

    public function register($data)
    {
        return (new User())->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function getUser($token)
    {
        $data = (new JwtService())->validateToken($token);
        return (new User())->where('id', $data->id)->first();
    }
}
