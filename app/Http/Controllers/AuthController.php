<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
           'email' => 'required|unique:users|email',
           'password' => 'required|same:password_repeat'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        return $this->sendResponse('', null, 201);
    }

    public function login(Request $request) {

    }
}
