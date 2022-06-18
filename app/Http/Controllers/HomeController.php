<?php

namespace App\Http\Controllers;

use App\paymentPlatform;
use App\Resolvers\PaymentPlatformResolve;
use App\Services\PaypalService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $paymentPlatformResolve;

    public function __construct(PaymentPlatformResolve $paymentPlatformResolve)
    {
        $this->paymentPlatformResolve = $paymentPlatformResolve;
    }

    public function index()
    {
        $price = mt_rand(500, 10000) / 100;
        return view('pasarelas')->with("platforms", paymentPlatform::all())->with("price",$price);
    }

    public function pay(Request $request)
    {
        $this->validate($request,[
            "method" => "required|exists:payment_platforms,id",
            "value" => "required|min:0"
        ]);

        // dd($request->all());

        $paymentPlatform = $this->paymentPlatformResolve->resolveService($request->method);

        session()->put("paymentPlatformId",$request->method);

        return $paymentPlatform->handlePayment($request);
    }

    public function paymentApproval()
    {
        if(session()->has("paymentPlatformId"))
        {
            $paymentPlatform = $this->paymentPlatformResolve->resolveService(session()->get("paymentPlatformId"));
            return $paymentPlatform->handleApproval();
        }
        return redirect("/")->withErrors("No se ha podido obtener la plataforma de pago");
    }

    public function paymentCancelled()
    {
        return redirect("/")->withErrors("Has cancelado el pago");
    }
}
