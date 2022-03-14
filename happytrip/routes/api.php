<?php

use App\Http\Controllers\API\CreditCardController;
use App\Http\Controllers\API\FlightsController;
use App\Http\Controllers\API\HotelsController;
use App\Http\Controllers\API\PagesController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ReservationsController;
use App\Http\Controllers\API\PackagesController;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'api'], function ($api) {
    $api->get('/ads', [PagesController::class, 'getAds']);
    $api->get('/packages', [PackagesController::class, 'getPackages']);
    $api->get('/package-details/{id}', [PackagesController::class, 'getPackageDetails']);
    $api->get('packages/filter', [PackagesController::class, 'filters']);
    $api->get('/countries', [HotelsController::class, 'getCountries']);
    $api->get('/cities', [HotelsController::class, 'getCities']);
    $api->get('/destinations', [HotelsController::class, 'allDestinations']);
    $api->get('/locations', [FlightsController::class, 'airlines']);

    $api->post('/contact-us', [PagesController::class, 'contactUs']);
    $api->get('/fqs', [PagesController::class, 'fqs']);
    $api->get('/settings', [PagesController::class, 'settings']);

    $api->group(['prefix' => 'flights'], function ($api) {
        $api->get('/search', [FlightsController::class, 'flightsSearch']);
        $api->get('/search_round', [FlightsController::class, 'flightsRoundSearch']);
        $api->post('/search_multi', [FlightsController::class, 'flightsMultiSearch']);
        $api->get('/check', [FlightsController::class, 'checkFlights']);
        $api->post('/book', [FlightsController::class, 'bookFlight']);
        $api->get('/locations', [FlightsController::class, 'locations']);
    });

    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('/forgot-password', [UserController::class, 'forgotPassword']);
        $api->post('password-forgot', [UserController::class, 'sendResetLinkEmail']);
        $api->post('verify/{code}/{email}', [UserController::class, 'verify']);
        $api->post('reset-password', [UserController::class, 'ResetPassword']);
        $api->post('/login', [UserController::class, 'login']);
        $api->post('/register', [UserController::class, 'register']);
        $api->get('/login/{provider}', [SocialController::class, 'redirect']);
        $api->get('/login-callback/{provider}', [SocialController::class, 'Callback']);
    });

    $api->group(['prefix' => 'hotels'], function ($api) {
        $api->get('/search', [HotelsController::class, 'showSearchResults']);
        $api->get('/available', [HotelsController::class, 'checkAvailableHotels']);
        $api->get('/sort/{type}/{by}', [HotelsController::class, 'sort'])->where('type', 'asc|desc');
        $api->get('/filter/{filter}', [HotelsController::class, 'filters']);
        $api->get('/details/{hotelCode}', [HotelsController::class, 'getHotelDetails']);
        $api->get('/hotel-data/{hotelCode}', [HotelsController::class, 'getHotelData']);
        $api->get('/reviews/{hotelCode}', [HotelsController::class, 'getHotelReviews']);
        $api->get('/city/{city}', [HotelsController::class, 'hotelsByCity']);
        $api->get('/availableByCity', [HotelsController::class, 'availableHotelsByCity']);
        $api->get('/bylocation', [HotelsController::class, 'hotelsByLocation']);
        $api->get('/nearby', [HotelsController::class, 'nearbyHotels']);
        $api->post('/booking', [HotelsController::class, 'booking']);
    });
    $api->group(['prefix' => 'reservation'], function ($api) {
        $api->post('/confirm', [ReservationsController::class, 'confirmBooking']);
        $api->post('/payWithWallet', [ReservationsController::class, 'payWithWallet']);
    });
    $api->group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function ($api) {
        $api->get('/me', [UserController::class, 'me']);
        $api->get('/profile-data', [UserController::class, 'profileData']);
        $api->get('/activities', [UserController::class, 'userActivities']);
        $api->get('/all-bookings', [UserController::class, 'allBookings']);
        $api->post('/cancel-booking', [UserController::class, 'cancelBooking']);
        $api->post('/logout', [UserController::class, 'logout']);
        $api->put('/change-password', [UserController::class, 'changePassword']);
        $api->post('/update-profile', [UserController::class, 'updateUserProfileInformation']);
        $api->get('/user-partners', [UserController::class, 'UserPartners']);
        $api->post('/update-partners', [UserController::class, 'editPartners']);
        $api->get('/all-favorites', [UserController::class, 'Allfavorites']);
        $api->get('/all-favorites-codes', [UserController::class, 'AllfavoritesCodes']);
        $api->post('/add-favorite', [UserController::class, 'addToFav']);
        $api->post('/remove-favorite', [UserController::class, 'removeFromFav']);
        $api->post('/language/{locale}', [PagesController::class, 'changeLang']);
        $api->post('/currency/{from}/{to}', [PagesController::class, 'changeCurrency']);
        $api->get('/coupons', [PagesController::class, 'getCoupons']);
        $api->post('/add-coupon', [PagesController::class, 'addCoupons']);
        $api->get('/wallet-balance', [PagesController::class, 'getWalletBalance']);
        $api->post('/wallet-deposite/{amount}', [PagesController::class, 'walletDeposite']);
        $api->post('/wallet-withdrow/{amount}', [PagesController::class, 'walletWithdrow']);
        $api->get('/wallet-transactions', [PagesController::class, 'walletTransactions']);
        $api->get('/credit-cards', [CreditCardController::class, 'index']);
        $api->post('/credit-cards/add', [CreditCardController::class, 'store']);
        $api->put('/credit-cards/update/{id}', [CreditCardController::class, 'update']);
        $api->get('/credit-cards/destroy/{id}', [CreditCardController::class, 'destroy']);
        $api->post('/rating/{hotelCode}', [HotelsController::class, 'Rating']);
    });
});


