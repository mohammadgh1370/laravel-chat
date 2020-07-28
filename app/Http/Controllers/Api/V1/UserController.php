<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);

        $user = $request->user();
        $api_token = $user->createApi_token();

        $user = collect(['api_token' => $api_token])->merge($user);

        return response()->json($user,200);
    }

    public function register(Request $request)
    {
        info(json_encode($request->only('name', 'email', 'password', 'password_confirmation')));
        $validator = Validator::make($request->only('name', 'email', 'password', 'password_confirmation'), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        info($validator->errors());
        if($validator->fails())
            return response()->json(['message' => $validator->errors()], 403);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $api_token = $user->createApi_token();

        $user = collect(['api_token' => $api_token])->merge($user);

        return response()->json($user,200);
    }
}
