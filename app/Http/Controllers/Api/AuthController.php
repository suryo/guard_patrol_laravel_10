<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TbUser;
use App\Models\TbPerson;

class AuthController extends Controller
{
    public function register(Request $r)
    {

        $data = $r->validate([
            'userid'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:Tb_users,userName',
            'userpassword' => 'required|string|min:6',
            'userlevel' => 'required|integer',
            'hasmobile' => 'nullable|string|max:100',
            'hasweb' => 'nullable|string|max:100',
            'useremail'    => 'nullable|email|unique:Tb_users,userEmail'

        ]);

        $user = TbUser::create([
            'userId' => $data['userid'],
            'userName' => $data['username'],
            'userPassword' => Hash::make($data['userpassword']),
            'userLevel' => $data['userlevel'],
            'hashMobile' => $data['hashmobile'] ?? null,
            'hashWeb' => $data['hashweb'] ?? null,
            'userEmail'    => $data['useremail'] ?? null,
        ]);



        return response()->json([
            'message' => 'User created successfully',
            'user'    => $user,
        ], 201);
    }

    public function login(Request $r)
    {


        $cred = $r->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // attempt akan:
        // - cari user berdasarkan userName
        // - cek hash terhadap TbUser::getAuthPassword() (userPassword)
        if (! $token = auth('api')->attempt([
            'userName' => $cred['username'],
            'password' => $cred['password'],
        ])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
// dd($cred);
        return response()->json([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // detik
            'user'       => auth('api')->user(),
        ]);
    }

    public function me()
    {
        // hidden field (userPassword) tidak akan ikut karena di model sudah di-hidden
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'logged out']);
    }

    public function refresh()
    {
        return response()->json([
            'token'      => auth('api')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
