<?php

namespace App\Services;

use App\Traits\ConsumerExternalServices;
use Illuminate\Http\Request;

class PaypalService {
    use ConsumerExternalServices;

    protected $baseUri;

    protected $clientId;

    protected $clientSecret;

    public function __construct()
    {
        $this->baseUri = config("services.paypal.base_uri");
        $this->clientId = config("services.paypal.client_id");
        $this->clientSecret = config("services.paypal.client_secret");
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
        $order = $this->createOrder($request->value);

        $orderLinks = collect($order->links);

        $approve = $orderLinks->where("rel","approve")->first();

        session()->put("id",$order->id);

        return redirect($approve->href);
    }

    public function handleApproval(){
        if(session()->has("id")){

            $id = session()->get("id");
            $payment = $this->capturePayment($id);

            $name = $payment->payer->name->given_name;
            $payment = $payment->purchase_units[0]->payments->captures[0]->amount;
            $amount = $payment->value;
            $currency = $payment->currency_code;

            return redirect("/")->withSuccess("{$name} tu pago fue realizado con exito por. {$amount}{$currency}");

        }
        return redirect("/")->withErrors("Por favor intente de nuevo");
    }

    public function resolveAccessToken(){
        $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");
        return "Basic {$credentials}";
    }

    public function createOrder($value){
        return $this->makeRequest(
            "POST",
            '/v2/checkout/orders',
            [],
            [
                'intent' => "CAPTURE",
                "purchase_units" => [
                    0 => [
                        'amount' => [
                            "currency_code" => "EUR",
                            "value" => round($value)
                        ]
                    ]
                ],
                "application_context" => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => "NO_SHIPPING",
                    "user_action" => "PAY_NOW",
                    "return_url" => url(request()->url()."/approval"),
                    "cancel_url" => url(request()->url()."/cancelled"),
                ]
            ],
            [],
            //Tipo de codificacion en este caso es json
            true
        );
    }

    public function capturePayment($id){
        return $this->makeRequest(
            "POST",
            "/v2/checkout/orders/{$id}/capture",
            [],
            [],
            [
                "Content-Type" => "application/json"
            ]
        );
    }
}
