<?php


namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerCard;
use App\Models\Coupon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Auth; 
use Hash;
use Str;
use Session;
class UserController extends Controller
{
    
    public function register(Request $request, CreatesNewUsers $creator)
    {
        $validator = Validator::make($request->all(), [
            'firstname_signup' => 'required|string|between:2,100',
            'lastname_signup' => 'required|string|between:2,100',
            //'email' => 'required|string|email|max:100|unique:users,email|unique:customers,email',
            'email_signup' => 'required|string|email|max:100',
            'password_signup' => 'required|string|min:6',
            // 'password_confirmation' => 'required',
            'mobile_signup' => 'required|numeric',
            
        ]);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user = new User();
        $user->role_id = 2;
        $user->name = $request->firstname_signup .' ' . $request->lastname_signup;
        $user->email = $request->email_signup;
        $user->remember_token = Str::random(10);
        $user->password = Hash::make($request->password_signup);
        $user->save();

        $customerData= new Customer(); 
        $customerData->user_id=$user->id;
        $customerData->name=$user->name;
        $customerData->email=$user->email;
        $customerData->dailing_code=$request->dialingcode_signup;
        $customerData->mobile=$request->mobile_signup;
        $customerData->save();


        // Session::flash('message', 'This is a message!');
        $request->session()->flash('notification', 'Successfully Registered');

        return redirect('/')->with('message','Successfully Registered');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
         if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }
        
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->intended('/');
       
    }

  


}
