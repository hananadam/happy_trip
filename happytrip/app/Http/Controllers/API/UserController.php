<?php


namespace App\Http\Controllers\API;

use App\Http\Resources\UserProfileResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\ForgetRequest;
use App\Http\Requests\Auth\ChangeRequest;
use App\Http\Requests\Auth\ResetRequest;
use App\Http\Requests\API\FavoriteRequest;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Favorite;
use App\Models\Hotelbeds\Hotel;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Dingo\Api\Routing\Helpers;
use Exception;
use Hash;
use Helpers\HotelBeds\Config\HotelBedsConfigManager;
use Helpers\HotelBeds\HotelBedsAdapter;
use Helpers\HotelBeds\HotelBedsExceptionHandler;
use Helpers\HotelBeds\HotelBedsHotelFactory;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Str;
use URL;

class UserController extends Controller
{
    use SendsPasswordResetEmails, Helpers;


    public function __construct()
    {
        $this->adapter = new HotelBedsAdapter(
            new HotelBedsExceptionHandler(),
            new HotelBedsConfigManager(),
            new HotelBedsHotelFactory(),
        );
    }

    public function me()
    {
        return new UserProfileResource(auth()->user());
    }

    /**
     * @OA\POST(
     *      path="/auth/register",
     *      operationId="register",
     *      tags={"Register"},
     *      summary="add new user to system",
     *      description="adding new user",
     *     @OA\Parameter(
     *          name="name",
     *          description="user name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="email",
     *          description="user email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="password",
     *          description="user password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="gender",
     *          description="user gender",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="address",
     *          description="user address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="dailing_code",
     *          description="user dailing_code",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *          name="mobile_num",
     *          description="user mobile_num",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="nationality",
     *          description="user nationality",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="title",
     *          description="user title",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="id_number",
     *          description="user id_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *          name="id_expiry_date",
     *          description="user id_expiry_date",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *          name="photo",
     *          description="user photo",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="image"
     *          )
     *      ),
     *          name="dob",
     *          description="user dob",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function register(RegisterRequest $request, CreatesNewUsers $creator)
    {
        $user = new User();
        $user->role_id = 2;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->remember_token = Str::random(10);
        $user->password = Hash::make($request->password);
        $user->save();

        $customerData = new Customer();
        $customerData->user_id = $user->id;

        $image = $request->file('photo');
        if ($image) {
            $photo = $image->getClientOriginalName();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $photo);
        }

        $customerData->name = $user->name;
        $customerData->email = $user->email;
        $customerData->gender = $request->gender;
        $customerData->country = $request->country;
        $customerData->address = $request->address;
        // $customerData->dailing_code = $request->dailing_code;
        $customerData->mobile_num = $request->mobile_num;
        if ($image) {
            $customerData->photo = $photo;
        }
        $customerData->nationality = $request->nationality;
        $customerData->id_number = $request->id_number ? $request->id_number : 0;
        $customerData->id_expiry_date = $request->id_expiry_date? $request->id_expiry_date : 0;
        $customerData->currency = 'SAR';
        $customerData->language = 'ar';
        $customerData->save();

        $customer = Customer::latest('created_at')->first();
        $token = $user->createToken('mobileApp');
        if ($customer->photo) {
            $customer->photo = URL::to('/uploads') . '/' . $customer->photo;
        }
        return response()->json([
            'message' => 'success',
            'customerInfo' => $customer,
            'token' => $token->plainTextToken
        ]);
    }

    /**
     * @OA\POST(
     *      path="/auth/login",
     *      operationId="Login",
     *      tags={"Login"},
     *      summary="authenticate user ",
     *      description="login to system",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function login(LoginRequest $request)
    {

        if (!auth()->attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'error', 'data' => 'The provided credentials are incorrect.'], 400);
        }

        $user = auth()->user();
        $token = $user->createToken('mobileApp');

        $customerInfo = $user->customerInfo()->first();
        if ($customerInfo->photo) {
            $user->photo = URL::to('/uploads') . '/' . $customerInfo->photo;

        }
        return response()->json([
            'message' => 'success',
            'data' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    /**
     * @OA\POST(
     *      path="/user/logout",
     *      operationId="Logout",
     *      tags={"Logout"},
     *      summary="logout user ",
     *      description="logout from system",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => 'Successfully logged out of application'], 200);
    }

    /**
     * @OA\Get(
     *      path="/user/profile_data",
     *      operationId="Profile Data",
     *      tags={"Profile Data"},
     *      summary="Get user data",
     *      description="Returns list of user data",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function profileData()
    {
        $data = User::find(auth()->user()->id);

        $customerInfo = $data->customerInfo()->first();
        if ($customerInfo->photo) {
            // $customerInfo->photo=url('/public/uploads/').$customerInfo->photo;
            $customerInfo->photo = URL::to('/uploads') . '/' . $customerInfo->photo;

        }
        return response()->json([
            'message' => 'success',
            'data' => $data,
            'customerInfo' => $customerInfo,
        ]);
    }

    /**
     * @OA\POST(
     *      path="/auth/register",
     *      operationId="register",
     *      tags={"Register"},
     *      summary="add new user to system",
     *      description="adding new user",
     *     @OA\Parameter(
     *          name="name",
     *          description="user name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="email",
     *          description="user email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *          name="password",
     *          description="user password",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="gender",
     *          description="user gender",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="address",
     *          description="user address",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="mobile_num",
     *          description="user mobile_num",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="nationality",
     *          description="user nationality",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          name="id_number",
     *          description="user id_number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *          name="id_expiry_date",
     *          description="user id_expiry_date",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *          name="photo",
     *          description="user photo",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="image"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function updateUserProfileInformation(ProfileRequest $request)
    {

        $user = User::findOrFail(auth()->user()->id);
        $user->fill($request->all());
        if ($user->customerInfo) {
            $destinationPath = public_path('/uploads');
            $image = $request->file('photo');
            if ($image) {
                if ($user->customerInfo->photo != '' && $user->customerInfo->photo != null) {
                    $filename = $user->customerInfo->photo;
                    File::delete($filename);
                }
                $photo = $image->getClientOriginalName();
                $image->move($destinationPath, $photo);
                $user->customerInfo->photo = $photo;
            }
            $user->customerInfo->fill($request->all());
        }

        $user->push();
        if ($user->customerInfo->photo) {
            $user->customerInfo->photo = URL::to('/uploads') . '/' . $user->customerInfo->photo;
        }
        $data = User::findOrFail(auth()->user()->id);
        return response()->json([
            'message' => 'success',
            'data' => $data,
            'customerInfo' => $user->customerInfo,
        ]);
    }

    /**
     * @OA\POST(
     *      path="auth/password-forgot",
     *      operationId="Forget Password",
     *      tags={"Forget Password"},
     *      summary="send reset mail",
     *      description="adding email to send reset mail for updating password",
     *     @OA\Parameter(
     *          name="email",
     *          description="user email",
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
    public function sendResetLinkEmail(ForgetRequest $request)
    {
        try {
            $response = $this->broker()->sendResetLink(
                $this->credentials($request));
            $response == Password::RESET_LINK_SENT;
            return response()->json(["message" => 'successfully sent'], 200);
        }
        catch (Exception $e){
            return response()->json(['message' => 'error in sending email'], 400);
        }
    }

    /**
     * @OA\POST(
     *      path="/verify",
     *      operationId="verfiy Email",
     *      tags={"verfiy Email"},
     *      summary="verify mail",
     *      description="verify email by sending verification code (coming form mail)",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function verify(Request $request, $code, $email)
    {
        $user = User::where('two_factor_code', $code)->where('email', $email)->first();
        if ($user) {
            $user->remember_token = Str::random(50);
            $user->save();
            return response()->json([
                'success' => 'verified',
                'token' => $user->remember_token,
                //'reset-url'  =>  URL::to('/api/reset-password/').'/'.$user->remember_token,
            ]);
        } else {
            return response()->json(['message' => 'code is not correct'], 400);
        }
    }

    /**
     * @OA\POST(
     *      path="auth/reset-password",
     *      operationId="Reset Password",
     *      tags={"Reset Password"},
     *      summary="add new password",
     *      description="adding new password and token for updating password",
     *     @OA\Parameter(
     *          name="token",
     *          description="user token coming from verify api",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password",
     *          description="new user password",
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
    public function ResetPassword(ResetRequest $request)
    {
        $user = User::where('remember_token', $request->token)->first();
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(50);
        $user->save();

        return response()->json([
            'sucess' => 'password changed',
            'user' => $user
        ]);

    }

    /**
     * @OA\POST(
     *      path="user/change-password",
     *      operationId="Change Password",
     *      tags={"Change Password"},
     *      summary="add new password from old one",
     *      description="updating old password ",
     *     @OA\Parameter(
     *          name="old_password",
     *          description="user old password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="new_password",
     *          description="user new password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="confirm_password",
     *          description="user confirm new password",
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
    public function changePassword(ChangeRequest $request)
    {
        $hashedPassword = Hash::make(request('old_password'));
        try {
            if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                $arr = array("message" => "Check your old password.", "data" => array(), 400);
                $code = 400;
            } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                $arr = array("message" => "Please enter a password which is not similar then current password.", "data" => array(), 400);
                $code = 400;
            } else {
                User::where('id', auth()->user()->id)->update(['password' => Hash::make($request['new_password'])]);
                $arr = array("message" => "Password updated successfully.", "data" => array(), 200);
                $code = 200;
            }
        }
        catch (Exception $ex) {
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            } else {
                $msg = $ex->getMessage();
            }
            $arr = array("message" => $msg, "data" => array(), 400);
            $code = 400;
        }
        return response()->json([
            'data' => $arr,
        ], $code);
    }

    /**
     * @OA\Get(
     *      path="/user/activities",
     *      operationId="activities",
     *      tags={"Activities"},
     *      summary="Get list of all user activities",
     *      description="Returns list of coming reservations, previous and cancelled ones",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function userActivities(Request $request)
    {
        $data = User::find(auth()->user()->id);
        $customerInfo = $data->customerInfo()->first();

        $coming = Booking::where('check_in', '>=', Carbon::today())->where('customer_id', $customerInfo->id)->get();
        foreach ($coming as $key => $value) {
            if($value->type=='hotel'){
                $hotelData = Hotel::where('code', $value->code)->with('country')->first();
                $value->image = $hotelData->getMainImage();
                $value->city = $hotelData->city . ',' . $hotelData->country->description;
            }
          
        }

        $previous = Booking::where('check_in', '<=', Carbon::today())->where('customer_id', $customerInfo->id)->get();
        foreach ($previous as $key => $value) {
            if($value->type=='hotel'){
                $hotelData = Hotel::where('code', $value->code)->first();
                $value->image = $hotelData->getMainImage();
                $value->city = $hotelData->city . ',' . $hotelData->country->description;
            }
        }

        $cancelled = Booking::where('status','cancelled')->where('customer_id', $customerInfo->id)->get();
        foreach ($cancelled as $key => $value) {
            if($value->type=='hotel'){
                $hotelData = Hotel::where('code', $value->code)->first();
                $value->image = $hotelData->getMainImage();
                $value->city = $hotelData->city . ',' . $hotelData->country->description;
            }
        }

        return response()->json([
            'message' => 'success',
            'comingReservations' => $coming,
            'previuosReservations' => $previous,
            'cancelledReservations' => $cancelled
        ]);
    }
    /**
     * @OA\Get(
     *      path="/user/all-bookings",
     *      operationId="all-bookings",
     *      tags={"Booking"},
     *      summary="Get list of all user all-bookings",
     *      description="Returns list of all bookings",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function allBookings(Request $request)
    {
        $data = User::find(auth()->user()->id);
        $customerInfo = $data->customerInfo()->first();
       
        if($request->booking_id){
            $bookings = Booking::where('id',$request->booking_id)->where('customer_id', $customerInfo->id)->first();
        }
        else{
            $bookings = Booking::where('customer_id', $customerInfo->id)->get();
            foreach ($bookings as $key => $value) {
                if($value->type=='hotel'){
                    $hotelData = Hotel::where('code', $value->code)->with('country')->first();
                    $value->image = $hotelData->getMainImage();
                    $value->city = $hotelData->city . ',' . $hotelData->country->description;
                }
              
            }
        }
        
        return response()->json([
            'message' => 'success',
            'data' => $bookings
        ]);
    }

    /**
     * @OA\Post(
     *      path="/user/cancel-booking",
     *      operationId="cancel booking",
     *      tags={"CancelBooking"},
     *      summary="Post cancel booking",
     *      description="Return cancelled booking",
     *     @OA\Parameter(
     *          name="booking_id",
     *          description="booking id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
    */
    public function cancelBooking(Request $request)
    {
        
        $data = Booking::where('id', $request->booking_id)->first();
        $data->status = 'cancelled';
        $data->save();

        return response()->json([
            'message' => 'booking successfully cancelled',
        ]);

    }

    /**
     * @OA\Get(
     *      path="/user/all-favorites",
     *      operationId="favorites",
     *      tags={"Favorites"},
     *      summary="Get list of all user favorites",
     *      description="Returns list of favorite hotels",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function allFavorites(Request $request)
    {
        $favs = Favorite::where('user_id', auth()->user()->id)->get();
        foreach ($favs as $key => $value) {
            $hotelData = Hotel::where('code', $value->code)->first();
           
            $value->name = $hotelData->name;
            $value->mainImage = $hotelData->getMainImage();
            $value->full_address = $hotelData->city . ',' . $hotelData->country->description;
            $value->rate = $hotelData->ratingStars;
        }

        return response()->json([
            'message' => 'success',
            'data' => $favs,
        ]);
    }
    /**
     * @OA\Get(
     *      path="/user/all-favorites-codes",
     *      operationId="favorites codes",
     *      tags={"Favorites"},
     *      summary="Get list of all user favorites codes ",
     *      description="Returns favorite hotesl codes for checking avalibility and getting prices",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */
    public function allFavoritesCodes(Request $request)
    {
        $favs = Favorite::where('user_id', auth()->user()->id)->get();
        $hotels=[];
        foreach ($favs as $key => $value) {
            $hotels[] = Hotel::where('code', $value->code)->first();
        }

        $collection=collect($hotels);

        $hotelsCodes = $collection->pluck('code')->toArray();
        return response()->json([
            'message' => 'success', 
            'codes' => $hotelsCodes,
        ]);
    }

    /**
     * @OA\POST(
     *      path="user/add-favorite",
     *      operationId="Add Favorite",
     *      tags={"Add_Favorite"},
     *      summary="add to favorite",
     *      description="adding new hotel to favorite list",
     *     @OA\Parameter(
     *          name="code",
     *          description="hotel code",
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
    public function addToFav(FavoriteRequest $request)
    {
        if (Favorite::where('user_id', auth()->user()->id)->where('code', '=', $request->code)->exists()) {
            return response()->json([
                'message' => 'hotel already exists !',
            ]);
        } else {
            $fav = new Favorite();
            $fav->user_id = auth()->user()->id;
            $fav->code = $request->code;
            $fav->save();

            return response()->json([
                'message' => 'successfully added to favorites list',
            ]);
        }
    }

    /**
     * @OA\POST(
     *      path="user/remove-favorite",
     *      operationId="Remove Favorite",
     *      tags={"Remove_Favorite"},
     *      summary="remove from favorite",
     *      description="removing hotel from favorite list",
     *     @OA\Parameter(
     *          name="code",
     *          description="hotel code",
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
    public function removeFromFav(FavoriteRequest $request)
    {
        $oldFav = Favorite::where('user_id', auth()->user()->id)->where('code', $request->code)->first();
        if ($oldFav) {
            $oldFav->delete();
            return response()->json([
                'message' => 'successfully removed from favorites list',
            ]);
        } else {
            return response()->json([
                'message' => 'this hotel not existed in favorites list',
            ]);
        }
    }

    /**
     * @OA\POST(
     *      path="user/update-partners",
     *      operationId="Update Partners",
     *      tags={"update_partners"},
     *      summary="add partners data to user profile",
     *      description="adding partners for booking operation info",
     *     @OA\Parameter(
     *          name="num_adults",
     *          description="number of adults including user",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="num_children",
     *          description="number of children of user",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="ages",
     *          description="ages of added children",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="partners",
     *          description="name of added adults and children",
     *          required=false,
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
    public function editPartners(Request $request)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        $customer->num_adults = $request->num_adults;
        $customer->partners = $request->partners;
        $customer->num_children = $request->num_children;
        $customer->save();
        foreach (explode(',', $request->ages) as $age) {
            // $data = array_map(function ($age) use ($customer) {
            //     return [
            //         'customer_id' => $customer->id,
            //         'age' => $age,
            //         'created_at' => Carbon::today(),

            //     ];
            // }, $age);
            $customer->addChildAge($age);
            // );
        }

        return response()->json([
            'message' => 'success',
            'data' => $customer,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/user/user-partners",
     *      operationId="user partners",
     *      tags={"Partners"},
     *      summary="Get user data",
     *      description="Returns list of user partners",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *      )
     * )
     */

    public function userPartners(Request $request)
    {
        $customer = Customer::where('user_id', auth()->user()->id)->first();
        $child_count=$customer->num_children;
        $ages=$customer->children()->orderBy('id', 'desc')->take($child_count)->pluck('age')->toArray();
        return response()->json([
            'message' => 'success',
            'adults' => $customer->num_adults,
            'chilldren' => $customer->num_children,
            'children_ages' => $ages,
            'partners' => $customer->partners,
        ]);

    }


   
}
