@extends("layouts.app")
@section("content")
    <div>
        <div class="flex items-center justify-center w-full min-h-screen bg-gray-100" x-data="data()">
            <div class="bg-white p-7 rounded-xl">
                <div class="grid gap-2 md:grid-cols-2">
                    <div>
                        <h2 class="text-2xl">Paquete</h2>
                    </div>
                    <div>
                        <form action="{{ url("payments") }}" method="post" id="paymentForm">
                            @csrf
                            <div class="text-lg">Monto: {{$price}}$</div>
                            <input type="hidden" name="value" value="{{$price}}">
                            <div class="mt-3 mb-2">Seleccione un metodo de pago</div>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ($platforms as $key => $platform)
                                    <div class="p-1 transition duration-300 ease-in-out border border-blue-300 rounded-lg hover:shadow" @click="platform({{ $platform->id }})">
                                        <label for="method{{ $platform->id }}">
                                            <img src="{{asset($platform->image)}}" alt="" srcset="">
                                        </label>
                                        <input type="radio" hidden id="method{{ $platform->id }}" name="method" value="{{$platform->id}}">
                                    </div>
                                @endforeach
                            </div>
                            <div>
                                @foreach ($platforms as $key => $platform)
                                    <div class="hidden" id="platform{{ $platform->id }}" area-data="components">
                                        @includeIf("components.".strtolower($platform->platform))
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center">
                                <button type="submit" id="payButton" class="px-3 py-2 text-white transition duration-300 ease-in-out bg-indigo-500 rounded-lg outline-none text-md hover:bg-indigo-700 hover:shadow-lg">Pagar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (isset($errors) && $errors->any())
        @foreach ($errors as $message)
            <div class="mb-3 text-red-500 bg-red-200 w-90">
                {{$message}}
            </div>
        @endforeach
    @endif
    @if (session()->has("success"))
        <div class="mb-3 text-red-500 bg-red-200 w-90">
            {{session()->get("success")}}
        </div>
    @endif
@endsection
@push("scripts")
    <script>
        function data(){
            return {
                platform(id){
                    components = document.querySelectorAll("div[area-data='components']");
                    components.forEach(element => {
                        element.classList = "hidden";
                    });
                    document.querySelector(`#platform${id}`).classList = "";
                }
            }
        }
    </script>
@endpush
