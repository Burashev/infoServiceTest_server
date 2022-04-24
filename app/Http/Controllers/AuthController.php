<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
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

    public function verificationNotice() {
        return response('', 200);
    }

    public function verificationVerify(EmailVerificationRequest $request) {
        $request->fulfill();

        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendError('User was already verified', null, 400);
        }

        return $this->sendResponse('Successful verification!', null, 201);
    }

    public function verificationSend(Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendError('User was already verified', null, 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->sendResponse('Verification link sent!', null, 201);
    }
}
