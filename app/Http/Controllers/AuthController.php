<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        $user = User::query()->create([
           'email' => $request->input('email'),
           'password' => Hash::make($request->input('password')),
           'token' => Str::random(32)
        ]);

        event(new Registered($user));

        return $this->sendResponse('', $user, 201);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $attempt = Auth::attempt($request->only(['email', 'password']));

        if (!$attempt) {
            return $this->sendError('Email or password is incorrect', null, 401);
        }

        $user = Auth::user();
        $user->update(['token' => Str::random(32)]);

        return $this->sendResponse('Successful login', $user, 200);
    }

    public function verificationNotice() {
        return response('', 200);
    }

    public function verificationVerify(EmailVerificationRequest $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendError('User was already verified', null, 400);
        }

        $request->fulfill();

        return $this->sendResponse('Successful verification!', null, 200);
    }

    public function verificationSend(Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->sendError('User was already verified', null, 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->sendResponse('Verification link sent!', null, 201);
    }
}
