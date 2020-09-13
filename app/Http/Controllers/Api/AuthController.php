<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $http = new \GuzzleHttp\Client;

        try {
            $response = $http->post(url('oauth/token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => 'tlLw95B9hgwfM1IH3E0YgSrmkxhqL9Ook4qstLpf',
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $errMsg = [
                'message' => ['Your credentials are incorrect. Please try again']
            ];
            return response()->json(['errors' => $errMsg], $e->getCode());
        }
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        return User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
