<?php

namespace App\Repositories;

use App\Interfaces\AuthI;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthRepository implements AuthI
{
    public function getUser(array $data)
    {
        $user = PersonalAccessToken::findToken($data['token'])?->tokenable;
        return $user ? $user : $this->storeUser($data);
    }

    private function storeUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'avatar' => $data['avatar'] ?? null,
            'password' => bcrypt(Str::random()),
        ]);
        $this->createtoken($user, $data['token'], $data['device']);

        return $user;
    }

    private function createToken(User $user, string $token, string $tokenName)
    {
        $user->tokens()->create([
            'name' => $tokenName,
            'token' => hash('sha256', $token),
            'abilities' => ['*'],
            'expires_at' => null,
        ]);
    }
}
