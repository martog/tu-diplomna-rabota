<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\User;
use App\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function getCurrentUser()
    {
        $user = User::find(Auth::user()->id);
        return new JsonResponse($user);
    }

    public function register(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'username' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'

        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');

        $checkUser = User::exists($email, $username)->first();

        if ($checkUser != null && ($checkUser->username == $username)) {
            return new JsonResponse("This username is already taken!", 409);
        }

        if ($checkUser != null && ($checkUser->email == $email)) {
            return new JsonResponse("This email is already taken!", 409);
        }

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->first_name = $request->get("first_name");
        $user->last_name = $request->get("last_name");
        $user->phone_number = $request->get("phone_number");
        $user->password = Hash::make($password);
        $user->save();
    }


    public function updateCurrentUser(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make($requestData, [
            'first_name' => 'string',
            'last_name' => 'string',
            'phone_number' => 'string',
            'email' => 'email',
            'password' => 'string'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::find(Auth::user()->id);
        $checkUser = User::exists($email)->where('id', '<>', $user->id)->first();


        if ($checkUser != null && $checkUser->email == $email) {
            return new JsonResponse("This email is already taken!", 409);
        }

        foreach ($requestData as $key => $value) {
            if ($key == "password") {
                $value = Hash::make($value);
            }

            $user->$key = $value;
        }
        $user->save();

        return new JsonResponse($user, 200);
    }

    public function deleteCurrentUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "password" => "required|password"
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $user = User::find(Auth::user()->id);

        if (Hash::check($request->get('password'), $user->password)) {
            DB::beginTransaction();
            try {
                $controllerIds = $user->controllers()->pluck("controllers.id");

                $user->devices()->delete();
                Controller::findByIds($controllerIds)->delete();
                $user->delete();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return new JsonResponse("Error removing user!");
            }
            Auth::logout();
        }

        return new JsonResponse("Success!");
    }
}
