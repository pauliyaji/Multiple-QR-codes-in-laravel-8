<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index(){
        $data = User::all();
        
        return view ('users.index', compact('data'));
    }

    public function show($id){
        $data = User::find($id);

        return view('users.show', compact('data'));
    }
}
