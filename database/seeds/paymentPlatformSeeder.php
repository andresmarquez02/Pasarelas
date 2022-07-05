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
            "image" => "img/paypal.png"
        ]);

        DB::table("payment_platforms")->insert([
            "platform" => "Stripe",
            "image" => "img/stripe.png"
        ]);

        DB::table("payment_platforms")->insert([
            "platform" => "MercadoPago",
            "image" => "img/mercadopago.svg"
        ]);
    }
}
