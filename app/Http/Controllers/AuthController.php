<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', [
            'except' =>[
                'login',
                'create',
                'unauthorized'
            ]
        ]);
    }
}
