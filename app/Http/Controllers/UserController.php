<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function loginUser(Request $request): Response
    {
        $input = $request->all();
        Auth::attempt($input);
        $user = User::whereEmail($request->email)->first();
        $tokenResult = $user->createToken('Personal Access Token')->accessToken;
        // $token = $tokenResult->token;
        return response(['status' => 200, 'token' => $tokenResult], 200);
    }

    public function getUserDetails()
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            return Response(['data' => $user], 200);
        }
        return response(['data' => 'Unauthorized'], 401);
    }

    public function userLogout(): Response
    {
        //$accessToken = Auth::guard('api')->user()->token();
        
        if (Auth::guard('api')->check()) {
            $accessToken = Auth::guard('api')->user()->token();

            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update(['revoked' => true]);
            $accessToken->revoke();

            return response(['data' => 'Unauthorized', 'message' => 'User logout successfully.'], 200);
        }
        return response(['data' => 'Unauthorized'], 401);
    }
}
