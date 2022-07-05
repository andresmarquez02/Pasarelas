<div class="mb-4">
    <div class="mt-4 mb-3 text-sm text-gray-500">
        Datos de su tarjeta
    </div>
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-4 mb-2 md:col-span-4">
            <input type="text" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5" required placeholder="Num de tarjeta" id="cardNumber" data-checkout="cardNumber">
        </div>
        <div class="col-span-4 mb-2 md:col-span-2">
            <input type="text" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5 dark:bg-gray-700" placeholder="CVC" required data-checkout="securityCode">
        </div>
        <div class="col-span-4 mb-2">
            <input type="text" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5 dark:bg-gray-700" placeholder="MM" required data-checkout="cardExpirationMonth">
        </div>
        <div class="col-span-4 mb-2">
            <input type="text" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5 dark:bg-gray-700" placeholder="YY" required data-checkout="cardExpirationYear">
        </div>
        <div class="col-span-4 mb-2 md:col-span-2">
            <input type="text" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5 dark:bg-gray-700" placeholder="Tu nombre" required data-checkout="cardholderName">
        </div>
        <div class="col-span-4 mb-2 md:col-span-2">
            <input type="text" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5 dark:bg-gray-700" placeholder="email@example.com" name="email" required data-checkout="cardholderEmail">
        </div>
        <div class="col-span-4 mb-2">
            <select class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5 dark:bg-gray-700" placeholder="email@example.com" data-checkout="docType"></select>
        </div>
        <div class="col-span-4 mb-2 md:col-span-3">
            <input type="text" class="shadow-sm bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 ease-in-out block w-full p-2.5 dark:bg-gray-700" placeholder="Document" required data-checkout="docNumber">
        </div>
        <div class="col-span-4 mb-2">
            <div class="text-sm text-gray-400" role="alert">
                Tu pago sera convertido a {{ strtoupper(config("services.mercadopago.currency_base")) }}
            </div>
        </div>
        <div class="col-span-4 mb-2">
            <div class="text-sm text-red-500" id="paymentErrors" role="alert">
            </div>
        </div>
        <input type="hidden" id="cardNetwork" name="card_network">
        <input type="hidden" id="cardToken" name="card_token">
    </div>
</div>
@push('scripts')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago({{ config("services.mercadopago.key") }});
        // Add step #3
        mp.getIdentificationTypes();
    </script>
    <script>
        function setCardNetwork(){
            const cardNumber =  document.getElementById("cardNumber");

            mp.getPaymentMethod(
                { 'bin': cardNumber.value.substring(0,6) },
                function(status, response){
                    const cardNetwork = document.getElementById("cardNetwork");

                    cardNetwork.value = response[0].id;
                }
            );
        }
    </script>
    <script>
        const formularioMercadoPago = document.getElementById("paymentForm");

        formularioMercadoPago.addEventListener("submit", (e) => {
            if(formularioMercadoPago.elements.method.value === "{{ $platform->id }}"){
                e.preventDefault();

                mp.createToken(formularioMercadoPago, function(status,response){
                    if(status != 200 && status != 201){
                        const error = document.getElementById("paymentErrors");
                        error.textContent = response.cause[0].description;
                    }
                    else{
                        const cardToken = document.getElementById("cardToken");

                        setCardNetwork();

                        cardToken.value = response.id;
                        form.submit();
                    }
                });
            }
        });

    </script>
@endpush
