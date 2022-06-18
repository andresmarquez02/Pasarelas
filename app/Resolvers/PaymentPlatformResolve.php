<?php

namespace App\Resolvers;

use App\paymentPlatform;
use Exception;

class PaymentPlatformResolve {

    public $paymentPlatforms;

    public function __construct()
    {
        $this->paymentPlatforms = paymentPlatform::all();
    }

    public function resolveService($paymentPlatformId)
    {
        $name = strtolower($this->paymentPlatforms->firstWhere("id",$paymentPlatformId)->platform);

        $service = config("services.{$name}.class");

        if($service){
            return resolve($service);
        }

        throw new Exception("La plataforma no exite en nuestras configuraciones {$service}");
    }
}
