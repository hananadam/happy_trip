<?php


namespace App\Http\Controllers\API;

use App\Models\Customer;
use App\Models\User;
use Auth;
use Dingo\Api\Routing\Helpers;
use Socialite;

class SocialController extends Controller
{
    use Helpers;
    
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function Callback($provider)
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        $users = User::where(['email' => $userSocial->getEmail()])->first();
        if ($users) {
            Auth::login($users);
            return response()->json([
                'message' => 'successfully logged in'
            ]);
        } else {

            $user = new User();
            $user->role_id = 2;
            $user->name = $userSocial->getName();
            $user->email = $userSocial->getEmail();
            $user->provider_id = $userSocial->getId();
            $user->provider = $provider;
            $user->save();

            $customerData = new Customer();
            $customerData->user_id = $user->id;
            $customerData->name = $user->name;
            $customerData->email = $user->email;
            $photo = $userSocial->getAvatar();
            if ($photo) {
                $customerData->photo = $photo;
            }
            $customerData->save();
            $customer = Customer::latest('created_at')->first();

            return response()->json([
                'message' => 'success',
                'customerInfo' => $customer,
            ]);
        }
    }
}
