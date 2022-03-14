<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    use ResetsPasswords, Helpers;

    // public function reset(Request $request)
    // {
    //     //$request->validate($this->rules(), $this->validationErrorMessages());
    //     $validator = Validator::make($request->all(), [
    //         'token' => ['required', 'max:255'],
    //         'email' => ['required', 'email', 'max:255' ],
    //         'password' => ['required', 'confirmed', 'min:8'],
    //     ]);

    //     $response = $this->broker()->reset(
    //         $this->credentials($request), function ($user, $password) {
    //             $this->resetPassword($user, $password);
    //         }
    //     );

    //     return $response == Password::PASSWORD_RESET
    //                 ? $this->sendResetResponse($request, $response)
    //                 : $this->sendResetFailedResponse($request, $response);
    // }


    // protected function resetPassword($user, $password)
    // {
    //     $this->setUserPassword($user, $password);

    //     $user->save();
    //     event(new PasswordReset($user));
    // }


    public function passwordReset(Request $request)
    {
        $input = $request->only('email', 'token', 'password', 'password_confirmation');
        $validator = Validator::make($input, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $response = Password::reset($input, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });
        $message = $response == Password::PASSWORD_RESET ? 'Password reset successfully' : GLOBAL_SOMETHING_WANTS_TO_WRONG;
        return response()->json($message);
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['success' => ["message" => trans($response)]], 200);
    }


    protected function sendResetFailedResponse(Request $request, $response)
    {

        return response()->json(['error' => ["message" => trans($response)]], 422);
    }
}
