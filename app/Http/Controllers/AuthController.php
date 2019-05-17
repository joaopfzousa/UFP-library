<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\APIHelpers;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class AuthController extends Controller
{
    private $request;
    private $apiHelpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, APIHelpers $apiHelpers)
    {
        $this->request = $request;
        $this->apiHelpers = $apiHelpers;
    }

    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt",
            'number' => $user->number,
            'iat' => time(),
            'exp' => time() + 60*60
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    public function login()
    {
        $this->validate($this->request, [
            'number'    => 'required',
            'password'  => 'required'
        ]);

        $user = User::where('number', $this->request->input('number'))->first();

        if (!$user) {
            return response()->json([
                'error' => 'User does not exist.'
            ], 400);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

        return response()->json([
            'error' => 'User or password is wrong.'
        ], 400);
    }
}