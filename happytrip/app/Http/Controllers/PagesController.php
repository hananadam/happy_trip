<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class PagesController extends Controller
{
    public function showWelcome()
    {
        session(['currency' => 'SAR']);
        session(['rate' => 1]);
        return view('welcome');
    }

    public function showAbout()
    {
        return view('pages.about');
    }

    public function showContactUs()
    {
        return view('pages.customer-service.contact-us');
    }

    public function showFaq()
    {
        return view('pages.customer-service.faq');
    }

    public function convertCurrency(Request $request, $amount = 1, $from = 'SAR', $to = 'USD')
    {
        $to = $request->to;
        $request->session()->put('currency', $to);
        $url = file_get_contents('https://free.currconv.com/api/v7/convert?q=' . $from . '_' . $to . '&compact=ultra&apiKey=5b1fb4aee1dba9726739');

        $json = json_decode($url, true);
        $rate = implode(" ", $json);
        $request->session()->put('rate', $rate);

        //$output = round($amount * $rate);

        return $rate;
    }

    public function showPayment()
    {
        return view('pages.payment');
    }

}
