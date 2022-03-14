<?php

namespace App\Http\Controllers;

use Tap\TapPayment\Facade\TapPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function showPayment()
    {
        return view('pages.payment');
    }
    public function pay(Request $request)
    {
         
        try {
            $payment = TapPayment::createCharge();
            //dd($payment);
            $payment->setCustomerName("John Doe");
            $payment->setCustomerPhone("965", "123456789");
            $payment->setDescription("Some description");
            $payment->setAmount(123);
            $payment->setCurrency("KWD");
            $payment->setSource("src_kw.knet");
            $payment->setRedirectUrl("http://127.0.0.1:8000/payment");
            //$payment->setMetaData(['package' => json_encode($package)]); // if you want to send metadata
            $invoice = $payment->pay();
            //dd($invoice);
        } catch (\Exception $exception) {
            // your handling of request failure
            dd($exception);
        }

        $payment->isSuccess(); // check if TapPayment has successfully handled request.
    }

    public function check($id)
    {
        try {
            $invoice = TapPayment::findCharge($id);
        } catch (\Exception $exception) {
            // your handling of request failure
        }

        $invoice->checkHash($request->header('Hashstring')); // check hashstring to make sure that request comes from Tap
        $invoice->isSuccess(); // check if invoice is paid
        $invoice->isInitiated(); // check if invoice is unpaid yet
    }

}
