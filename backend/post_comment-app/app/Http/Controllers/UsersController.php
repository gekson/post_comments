<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function get()
    {
        info('AQUI2');
        return User::get();
    }
}
