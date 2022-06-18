@push("styles")
    <style>
        .StripeElement{
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #cfd7df;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus{
            box-shadow: 0 1px 3px 0 #cfd7df;
        }
        .StripeElement--invalid{
            box-shadow: 0 1px 3px 0 #fa755a;
        }
        .StripeElement--webkit-autofill{
            background-color: #fefde5 !important;
        }
    </style>
@endpush
<div class="mb-4">
    <div class="mt-4 mb-3 text-sm text-gray-500">
        Datos de su tarjeta
    </div>
    <div id="cardElement"></div>
    <small class="my-3 text-gray-200" id="cardErrors" role="alert"></small>
    <input type="hidden" name="payment_method" id="paymentMethod">
</div>
@push('scripts')
    <script src=""></script>
    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");

        const elements = stripe.elements({locale: 'es'});
        const cardElement = elements.create('card');

        cardElement.mount('#cardElement');
    </script>
    <script>
        const form = document.getElementById("paymentForm");
        const payButton = document.getElementById("payButton");

        payButton.addEventListener("click",async(e) => {

            if(form.elements.method.value === "{{ $platform->id }}"){
                e.preventDefault();
            }

            console.log("click");

            const {paymentMethod, error} = await stripe.createPaymentMethod(
                'card',cardElement, {
                    billing_details: {
                        "name": "Andres Marquez",
                        "email": "andres03ruht@gmail.com"
                    }
                }
            );
            if(error)
            {
                const displayError = document.getElementById("cardErrors");
                displayError.innerHTML = error.message;
                console.log(error.message);
            }
            else
            {
                document.getElementById("paymentMethod").value= paymentMethod.id;
                form.submit();
            }
        });
    </script>
@endpush
