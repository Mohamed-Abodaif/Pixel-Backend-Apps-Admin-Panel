<?php

namespace App\Http\Controllers\SystemAdminPanel\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemAdminPanel\Auth\AdminLoginRequest;
use App\Http\Requests\SystemAdminPanel\Auth\AdminRegisterRequest;
use App\Models\SystemAdminPanel\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    function register(AdminRegisterRequest $request)
    {
        $data = $request->except('avatar');
        $data['password'] = bcrypt($request->password);
        $admin = Admin::create($data);
        return response()->json([
            "data" => $admin
        ], 201);
    }

    public function login(AdminLoginRequest $request)
    {
        $data = $request->all();

        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            if (Hash::check($request->password, $admin->password)) {
                $token = $admin->createToken('Admin Type')->accessToken;
                return response([
                    'token' => $token,
                    "admin" => $admin
                ]);
            } else {
                $response = ["message" => "invalid data"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'Admin does not exist'];
            return response($response, 422);
        }
    }
}
