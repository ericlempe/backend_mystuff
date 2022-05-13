<?php

namespace App\Scopes;

use App\Services\AuthService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExpenseScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $token = request()->bearerToken();
        $user = (new AuthService())->getUser($token);
        $builder->where('user_id', $user->id);
    }

}
