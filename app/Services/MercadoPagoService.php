<?php

namespace App\Services;

use App\Traits\ConsumerExternalServices;
use Illuminate\Http\Request;

class MercadoPagoService {
    use ConsumerExternalServices;

    protected $baseUri;

    protected $key;

    protected $secret;

    protected $currencyBase;

    protected $converter;

    public function __construct(CurrencyConversionService $converter)
    {
        $this->baseUri = config("services.mercadopago.base_uri");
        $this->key = config("services.mercadopago.key");
        $this->secret = config("services.mercadopago.secret");
        $this->currencyBase = config("services.mercadopago.base_currency");
        $this->converter = $converter;
    }

    public function resolveAuthorizacion(&$queryParams,&$formParams,&$headers)
    {
        $queryParams["access_token"] = $this->resolveAccessToken();
    }

    public function resolveAccessToken(){
        return $this->secret;
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            "card_network" => "required",
            "card_token" => "required",
            "email" => "required",
        ]);

        $payment = $this->createPayment(
            $request->value,
            $request->card_network,
            $request->card_token,
            $request->email
        );

        if($payment->status === "approved"){
            $name = $payment->payer->first_name;
            $currency = strtoupper($payment->currency_id);
            $amount = number_format($payment->transaction_amount, 0, ',', '.');

            $originalAmount = $request->value;
            $originalCurrency = "USD";

            return redirect("/")->withSuccess("{$name} tu pago fue realizado con exito por. {$originalAmount}{$originalCurrency} y fue convertido  a {$amount}{$currency}");
        }
        return redirect("/")->withErrors("No se ha podido confirmar el pago, por favor intenta mas tarde");
    }

    public function handleApproval(){
        //
    }

    public function createPayment($value, $cardNetwork, $cardToken, $email, $installments = 1){
        return $this->makeRequest(
            "POST",
            "/v2/payments",
            [],
            [
                "payer" => [
                    "email" => $email,
                ],
               "binary_mode" => true,
               "transaction_amount" => round($value * $this->resolveFactor()),
               "payment_method_id" => $cardNetwork,
               "token" => $cardToken,
               "installments" => $installments,
               "statement_descriptor" => config("app.name"),
            ],
            [],
            true

        );
    }

    public function resolveFactor(){
        return $this->converter->convertCurrency("USD",$this->currencyBase);
    }
}
