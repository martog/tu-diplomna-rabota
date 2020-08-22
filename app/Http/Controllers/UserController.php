<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'

        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $username = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');

        $checkUser = User::exists($email, $username)->first();

        if ($checkUser != null && $checkUser->name == $username) {
            return new JsonResponse("This username is already taken!", 409);
        }

        if ($checkUser != null && $checkUser->email == $email) {
            return new JsonResponse("This email is already taken!", 409);
        }

        $user = new User();
        $user->name = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();
    }


    public function update(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'

        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $username = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::find(Auth::user()->id);
        $checkUser = User::exists($email, $username)->where('id', '<>', $user->id)->first();

        if ($checkUser != null && $checkUser->name == $username) {
            return new JsonResponse("This username is already taken!", 409);
        }

        if ($checkUser != null && $checkUser->email == $email) {
            return new JsonResponse("This email is already taken!", 409);
        }

        $user->name = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        return new JsonResponse($user, 200);
    }
}
