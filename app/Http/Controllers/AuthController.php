<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    //Register user
    public function register(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);


        //create user
        $user = User::create([
            'name'=> $attrs['name'],
            'email'=> $attrs['email'],
            'password' => bcrypt($attrs['password'])
        ]);

        //return user & token in response
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ]);
    }

    //login user
    public function login(Request $request)
    {
        // Validasi field
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Mencoba login
        if (!Auth::attempt($attrs)) {
            return response([
                'message' => 'Invalid credentials.'
            ], 403);
        }

        // Mengembalikan user dan token dalam response
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken,
        ], 200);
    }

    //logout
    public function logout()
    {
        // Hapus semua token milik user
        auth()->user()->tokens()->delete();

        // Kembalikan respons logout sukses
        return response([
            'message' => 'Logout success.'
        ], 200);
    }

    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }


    //update user
    public function update(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'profiles');
        
        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'User Updated.',
            'user' => auth()->user()
        ], 200);
    }

    


}
