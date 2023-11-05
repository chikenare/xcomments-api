<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    private $model;

    public function __construct(AuthRepository $authRepository)
    {
        $this->model = $authRepository;
    }


    public function getUser(Request $request)
    {
        try {
            $user = $this->model->getUser($request->all());
            return response()->json(['data' => ['user' => $user]]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
