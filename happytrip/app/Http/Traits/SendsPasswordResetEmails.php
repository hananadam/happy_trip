<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
// use App\Http\Traits\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

trait SendsPasswordResetEmails
{

    public function showLinkRequestForm()
    {
        //return view('auth.passwords.email');
        return  new RedirectResponse("http://localhost/happytrip/public/password/reset");
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    protected function sendResetLinkResponse($response)
    {
        return response()->json(['success' => ["message" => trans($response)] ], 200);   
        //return back()->with('status', trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }
        return response()->json(['error' => ["message" => trans($response)] ], 422); 
        // return back()->withErrors(
        //     ['email' => trans($response)]
        // );
    }

    public function broker()
    {
        return Password::broker();
    }
}

  
    