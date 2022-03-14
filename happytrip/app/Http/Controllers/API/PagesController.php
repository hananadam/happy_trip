<?php

namespace App\Http\Controllers\API;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\API\CouponRequest;
use App\Http\Requests\API\ContactRequest;
use App\Models\Ad;
use App\Models\Coupon;
use App\Models\CustomerCard;
use App\Models\FqTopic;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Cache;
use Config;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

class PagesController extends Controller
{
    use Helpers;

    /**
     * @OA\POST(
     *      path="/user/add-coupon",
     *      operationId="add_coupon",
     *      tags={"addCoupon"},
     *      summary="Post add new coupon",
     *      description="add new coupon for offers and discounts",
     *     @OA\Parameter(
     *          name="title",
     *          description="coupons title",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="discount",
     *          description="add discount value or percentage",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="code",
     *          description="add discount code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="description",
     *          description="coupon offer description",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="text"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="expire_date",
     *          description="coupon offer expire date",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function addCoupons(CouponRequest $request)
    {
        $data=$request->all();
        $obj = new Coupon();
        $coupon = $obj->createCoupon($data);

        return response()->json([
            'message' => 'success',
            'data' => $coupon,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/user/coupons",
     *      operationId="coupons",
     *      tags={"Coupons"},
     *      summary="Get list of all added coupons",
     *      description="Returns list of coupons",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getCoupons(Request $request)
    {
        $data = Coupon::get();
        if($request->code){
            $existed= Coupon::where('code', '=', $request->code)->first();
            if($existed){
                return response()->json([
                    'message' => 'success',
                    'data' => $existed,
                ]);
            }
            else{
                return response()->json(['message' => 'code is not correct'], 400);
            }
        }

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/user/ads",
     *      operationId="Ads",
     *      tags={"Ads"},
     *      summary="Get list of all advertisements",
     *      description="Returns list of advertisements",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getAds()
    {
        $data = Ad::all();

        $data = $data->map(function (Ad $ad) {
            return [
                'id' => $ad->id,
                'title' => $ad->title,
                'description' => $ad->description,
                'image' => Storage::url($ad->image)
            ];
        });

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\POST(
     *      path="/user/language",
     *      operationId="changeLanguage",
     *      tags={"changeLanguage"},
     *      summary="change website language",
     *      description="change lang",
     *     @OA\Parameter(
     *          name="locale",
     *          description="language key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function changeLang($locale)
    {
        if (in_array($locale, Config::get('app.locales'))) {
            session(['locale' => $locale]);
            Cache::put('locale', $locale);

            if (auth('sanctum')->check()) {
                $data = User::find(auth('sanctum')->user()->id);
                $data->customerInfo->update(['language' => $locale]);
            }
        }
        return response()->json([
            'message' => 'success',
            'language' => $locale,
        ]);
    }

    /**
     * @OA\POST(
     *      path="/user/currency",
     *      operationId="changeCurrency",
     *      tags={"changeCurrency"},
     *      summary="change website currency",
     *      description="change lang",
     *     @OA\Parameter(
     *          name="from",
     *          description="change from currency key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="to",
     *          description="change to currency key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function changeCurrency($from, $to, Request $request, $amount = 1)
    {
        $rate = Currency::convert()->from($from)->to($to)->round(2)->get();
        if (auth('sanctum')->check()) {
            $data = User::find(auth('sanctum')->user()->id);
            $data->customerInfo->update(['currency' => $to]);
        }
        session(['rate' => $rate]);
        session(['currency' => $to]);

        return response()->json([
            'message' => 'success',
            'currency' => $to,
            'rate' => $rate,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/user/wallet-balance",
     *      operationId="wallet-balance",
     *      tags={"Wallet"},
     *      summary="Get current wallet balance",
     *      description="money existed in wallet",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function getWalletBalance(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $value = $user->balance;

        return response()->json([
            'message' => 'success',
            'balance' => $value
        ]);
    }

    /**
     * @OA\POST(
     *      path="/user/wallet-deposite",
     *      operationId="wallet_deposite",
     *      tags={"WalletDeposite"},
     *      summary="add to wallet",
     *      description="charge wallet with deposite",
     *     @OA\Parameter(
     *          name="card_id",
     *          description="the card id that the wallet deposite will be taken from",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="amount",
     *          description="the amount needed to charge with",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function WalletDeposite(Request $request, $amount)
    {
        $user = User::find(auth()->user()->id);
        if($request->card_id){
            $cardData = CustomerCard::where('id', $request->card_id)->where('customer_id',$user->customerInfo->id)->first();
            if($cardData){
                $user->deposit($amount);
                $lastTrans=Transaction::latest()->first();
                $lastTrans->card_id=$request->card_id;
                $lastTrans->save();
                $value = $user->balance;
                return response()->json([
                    'message' => 'success',
                    'balance' => $value,
                    'cardData' => $cardData,
                ]);
            }
            else{
                return response()->json(['message' => 'wrong card id'], 400);
            }
        }
        else{
            return response()->json(['message' => 'card id required'], 400);
        }
       

       
    }

    /**
     * @OA\POST(
     *      path="/user/wallet-withdrow",
     *      operationId="wallet_withdrow",
     *      tags={"walletWithdrow"},
     *      summary="remove from wallet",
     *      description="withdrow money from wallet",
     *     @OA\Parameter(
     *          name="amount",
     *          description="the amount needed to be withdrawn",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function WalletWithdrow(Request $request, $amount)
    {
        $user = User::find(auth()->user()->id);
        $balance = $user->balance;
        if((int)$balance > $amount){
            $user->withdraw($amount);
            $value = $user->balance;

            return response()->json([
                'message' => 'success',
                'balance' => $value
            ]);
        }
        else{
            return response()->json(['message' => 'you dont have enough money in wallet'], 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/wallet-transactions",
     *      operationId="wallet-transactions",
     *      tags={"WalletTransactions"},
     *      summary="Return list of wallet transactions",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function WalletTransactions(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $transData = $user->transactions;
        if($request->date){
            $date=$request->date;
            $transData = $transData->filter(function ($item) use ($date) {
                return (data_get($item, 'created_at') > $date) && (data_get($item, 'created_at') <
                Carbon::createFromFormat('Y-m-d', $date)->endOfDay());
            });
            $transData=$transData->values();
             
        }
        $data=[];
        foreach ($transData as $key => $trans) {
            $data[$key]['type']=$trans['type'];
            $data[$key]['amount']=$trans['amount'];
            $data[$key]['date']=$trans['created_at'];
            $card=CustomerCard::where('id',$trans->card_id)->first();
            if($card){
                $card_num= substr($card->number,-4);
                $data[$key]['card_number']=$card_num;
            }
           
        }
      
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\POST(
     *      path="/contact-us",
     *      operationId="contact_us",
     *      tags={"contactUs"},
     *      summary="contact us messages",
     *      description="messages sent from customers to administrator",
     *     @OA\Parameter(
     *          name="name",
     *          description="customer name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="email",
     *          description="customer email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="subject",
     *          description="message subject",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="email",
     *          description="message content",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function contactUs(ContactRequest $request)
    {
        $msg = new Message();
        $data=$request->all();
        $data = $msg->createMessage($data);
        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/fqs",
     *      operationId="fqs",
     *      tags={"Fqs"},
     *      summary="Return list of user fqs",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function fqs(Request $request)
    {
        $data = FqTopic::with('fqs')->get();

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/settings",
     *      operationId="settings",
     *      tags={"Settings"},
     *      summary="Return website's info",   
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function settings(Request $request)
    {
        $data = Setting::first();
        $data->logo = Storage::url($data->logo);

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ]);
    }

}
