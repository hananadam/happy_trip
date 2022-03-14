<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails, Helpers;


    public function sendResetLinkEmail(Request $request)
    {
        //$this->validateEmail($request);
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $response = $this->broker()->sendResetLink(
            $this->credentials($request));
        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['success' => ["message" => trans($response)]], 200);
    }

}





