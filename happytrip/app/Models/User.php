<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, Wallet
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasWallet;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
        'provider',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function customerInfo()
    {
        return $this->hasOne(Customer::class);
    }

    public function sendPasswordResetNotification($email)
    {
        $code = $this->generateTwoFactorCode();
        $this->notify(new ResetPassword($email, $code));
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(1000, 9999);
        $this->two_factor_code = 1213;

        $this->two_factor_expires_at = now()->addMinutes(20);
        $this->save();
    }

    public function sendPasswordResetNotification1($token)
    {
        $code = $this->generateTwoFactorCode();
        $this->notify(new ResetPassword($token, $code));

        // $data = [
        // $this->email
        // ];
        // DB::table('password_resets')->insert([
        //         'email' => $this->email,
        //         'token' => $this->quickRandom(60),
        //         'created_at' => Carbon::now()
        // ]);

        // $code=$this->generateTwoFactorCode();
        // $contactEmail=$this->email;
        // $contactName=$this->name;
        // Mail::send('email.resetPassword', [
        //     'fullname'      => $this->name,
        //     'reset_url'     => route('user.password.reset', ['token' => $token, 'email' => $this->email,
        //         'code'=>$code]),
        // ],function($message) use ($contactEmail, $contactName){
        //     $message->to($contactEmail,$contactName)->subject(' Happy Trip Site Reset Password');
        //     $message->from('happytrip@info.com','Happy Trip');
        // });

    }

    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function createCustomer($gender,$title,$country,$nationality,$mobile_num,$dob)
    {
        $customerData = new Customer();
        $customerData->user_id = $this->id;
        $customerData->name = $this->name;
        $customerData->email = $this->email;
        $customerData->gender = $gender;
        $customerData->title = $title;
        $customerData->country = $country;
        $customerData->nationality = $nationality;
        $customerData->mobile_num = $mobile_num;
        $customerData->dob = $dob;
        $customerData->save();

        return $customerData;
    }
}
