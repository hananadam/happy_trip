<?php


namespace App\Http\Controllers\API;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerCard;
use App\Models\Hotelbeds\Hotel;
use App\Models\User;

use App\Http\Requests\API\ReservationRequest;

use Auth;
use Cache;
use Carbon\Carbon;
use DB;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Storage;
use Str;
use stdClass;
class ReservationsController extends Controller
{
    use Helpers;
    
    
    public function confirmBooking(ReservationRequest $request)
    {
        if (auth('sanctum')->check()) {
            $data = auth('sanctum')->user();
            $name = explode(" ", $data->name);
            $holderFirstName = $name[0];
            $holderLastName = $name[1];
            $customerInfo = $data->customerInfo()->first();
            $customerId = $customerInfo->id;
        } else {
            $holderFirstName = $request->firstName;
            $holderLastName = $request->lastName;
            if ($user = User::where('email', $request->email)->first()) {
                if ($user->customerInfo) {
                    $customerId = $user->customerInfo->id;
                    
                } else {
                    $customerData = $user->createCustomer($request->gender, $request->country, $request->mobile_num);
                    $customerId = $customerData->id;
                }
            } else {
                $user = new User();
                $user->name = $holderFirstName . ' ' . $holderLastName;
                $user->email = $request->email;
                $user->password = Hash::make(Str::random(10));
                $user->save();
                $customerData = $user->createCustomer($request->gender, $request->country, $request->mobile_num);
                $customerId = $customerData->id;
            }
        }
        $bookingData= new stdClass;
        $bookingData->firstName=$holderFirstName;
        $bookingData->lastName=$holderLastName;
        $bookingData->email=$request->email;
        $bookingData->mobile_num=$request->mobile_num;
        $bookingData->country=$request->country;
        $bookingData->gender=$request->gender;
        $bookingData->reference_code=$request->reference_code;
        $bookingData->type='hotels';
        $bookingData->payment_method=$request->payment_method;
        $bookingData->voucher=$request->voucher;
        $bookingData->loyality=$request->loyality;

        $bookingData->members = explode(',', $request->members);
        $bookingData->subtotal=$request->subtotal;
        $bookingData->vat='10%';
        $bookingData->total=$request->total;

        if($request->payment_method == 'card'){
            if($request->card_id){
                $cardData=CustomerCard::where('id',$request->card_id)->first();
                $bookingData->card_name=$cardData->name;
                $bookingData->card_number=$cardData->number;
                $bookingData->card_cvv=$cardData->cvv;
                $bookingData->card_expire=$cardData->expire_month . '/' . $cardData->expire_year;
            }
            else{
                $bookingData->card_name=$request->name;
                $bookingData->card_number=$request->number;
                $bookingData->card_cvv=$request->cvv;
                $bookingData->card_expire=$request->expire_month . '/' . $request->expire_year;
            }
            return response()->json([
                'message' => 'successfully paid',
                'bookingData' => $bookingData,
            ]);

        }
        elseif($request->payment_method == 'wallet'){
            $balance = $user->balance;
            if((int)$balance > $request->total){
                $user->withdraw($request->total);
                $current= $user->balance;
                return response()->json([
                    'message' => 'successfully paid',
                    'bookingData' => $bookingData,
                    'current in wallet' => $current
                ]);
            }
            else{
                return response()->json(['message' => 'you dont have enough money in wallet'], 400);
            }
        }
        
     
        return response()->json([
            'message' => 'primitive booking success',
            'bookingData' => $bookingData,
        ]);
    }


    public function payWithWallet(Request $request)
    {
        if (auth('sanctum')->check()) {
            $user = User::find( auth('sanctum')->user()->id);
        }
        else {
            return response()->json(['message' => 'please login first'], 400);
        }
        $balance = $user->balance;

        if($request->amount){
            if((int)$balance > $request->amount){
                $user->withdraw($request->amount);
                $current= $user->balance;
                return response()->json([
                    'message' => 'successfully paid',
                    'current in wallet' => $current
                ]);
            }
            else{
                return response()->json(['message' => 'you dont have enough money to pay'], 400);
            }
        }
      
    }

}