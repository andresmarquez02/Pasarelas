<?php

namespace App\Services;

use App\Traits\ConsumerExternalServices;
use Illuminate\Http\Request;
use Str;

class StripeService {
    use ConsumerExternalServices;

    protected $baseUri;

    protected $key;

    protected $secret;

    public function __construct()
    {
        $this->baseUri = config("services.stripe.base_uri");
        $this->key = config("services.stripe.key");
        $this->secret = config("services.stripe.secret");
    }

    public function resolveAuthorizacion(&$queryParams,&$formParams,&$headers)
    {
        $headers["Authorization"] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            "payment_method" => "required"
        ]);
        $intent = $this->createIntent($request->value, $request->payment_method);
        session()->put("paymentIntentId", $intent->id);
        return redirect()->route("approval");
    }

    public function createIntent($value,$paymentMethod){
        return $this->makeRequest(
            "POST",
            "/v1/payment_intents",
            [],
            [
                "amount" => round($value),
                "currency" => strtolower("EUR"),
                "payment_method" => $paymentMethod,
                "confirmation_method" => "manual",

            ],
            []
        );
    }

    public function confirmPayment($paymentIntentId){
        return $this->makeRequest(
            "POST",
            "/v1/payment_intents/{$paymentIntentId}/confirm"
        );
    }

    public function handleApproval(){
        if(session()->has("paymentIntentId")){

            $id = session()->get("paymentIntentId");
            $confirmation = $this->confirmPayment($id);

            if($confirmation->status == "requires_action"){
                $clientSecret = $confirmation->client_secret;

                return view("stripe.3d-secure")->with("clientSecret",$clientSecret);
            }

            if($confirmation->status == "succeeded"){
                $name = $confirmation->charges->data[0]->billing_details->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount;
                return redirect("/")->withSuccess("{$name} tu pago fue realizado con exito por. {$amount}{$currency}");
            }

        }
        return redirect("/")->withErrors("No se ha podido confirmar el pago, por favor intenta de nuevo");
    }

    public function resolveAccessToken(){
        return "Bearer {$this->secret}";
    }
}
