<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    //

    public function index()
    {

        $users = User::all();

        return view('users', compact('users'));
    }

    public function delete($id)
    {

        $user = User::find($id);
        $user->delete();

        return response()->json(['sucess' => 'deleted']);
    }
}
