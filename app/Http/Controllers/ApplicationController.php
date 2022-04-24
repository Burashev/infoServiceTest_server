<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApplicationController extends BaseController
{
    public function createApplication(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'company' => 'required',
            'application_name' => 'required',
            'message' => 'required',
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $file_name = Str::random() . '.' . $request->file('file')->clientExtension();
        $request->file('file')->move( public_path() . '/upload_files',  $file_name);

        $application = Application::query()->make($validator->validated());
        $application->file ='/upload_files/' . $file_name;

        Auth::user()->applications()->save($application);

        return $this->sendResponse('Application successful created', $application, 201);
    }

    public function showApplications(Request $request) {
        $applications = Auth::user()->applications;

        return $this->sendResponse('', $applications);
    }
}
