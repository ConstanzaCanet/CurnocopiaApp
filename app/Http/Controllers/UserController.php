<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

        protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'last_name' =>$data['last_name'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => null, 
        ]);
    }
    
    public function destroy(User $user)
    {
        $user->delete();
        $user->notify(new UserDeletedNotification());
        return redirect()->route('home')->with('uccess', 'Usuario eliminado correctamente.');
    }
}