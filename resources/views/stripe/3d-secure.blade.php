@extends("layouts.app")
@section("content")
    <div>
        <div class="flex items-center justify-center w-full min-h-screen bg-gray-100">
            <div class="bg-white p-7 rounded-xl">
                <div>
                    <h2 class="mb-4 text-2xl">Completa tu seguridad de dos pasos</h2>
                    <div class="text-gray-600">
                        <p>Necesitas seguir algunos pasos con tu banco para confirmar tu pago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");

        stripe.handleCardAction("{{ $clientSecret }}")
        .then(function ($result) {
            if($result.error){
                window.location.replace("{{ route('cancelled') }}");
            }
            else{
                window.location.replace("{{ route('approval') }}");
            }
        });
    </script>
@endpush

