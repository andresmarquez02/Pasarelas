<?php

use Illuminate\Database\Seeder;
use app\paymentPlatform;
use Illuminate\Support\Facades\DB;

class paymentPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("payment_platforms")->truncate();
        DB::table("payment_platforms")->insert([
            "platform" => "Paypal",
            "image" => "img/paypal.jpg"
        ]);

        DB::table("payment_platforms")->insert([
            "platform" => "Stripe",
            "image" => "img/stripe.jpg"
        ]);

        DB::table("payment_platforms")->insert([
            "platform" => "MercadoPago",
            "image" => "img/mercadopago.jpg"
        ]);

        DB::table("payment_platforms")->insert([
            "platform" => "PayU",
            "image" => "img/payu.jpg"
        ]);
    }
}
