@extends("layouts.app")
@section("content")
    <div>
        <div class="flex items-center justify-center w-full min-h-screen bg-gray-100" x-data="data()">
            <div class="bg-white p-7 rounded-xl xl:w-6/12 lg:w-10/12 md:w-10/12">
                <div class="grid gap-2 md:grid-cols-2">
                    <div>
                        <h2 class="text-3xl font-bold">A pagar</h2>
                    </div>
                    <div>
                        <form action="{{ url("payments") }}" method="post" id="paymentForm">
                            @csrf
                            <div class="text-2xl font-bold">Monto: {{$price}}$</div>
                            <input type="hidden" name="value" value="{{$price}}">
                            <div class="mt-3 mb-2">Seleccione un metodo de pago</div>
                            <div class="mt-2 mb-4">
                                @foreach ($platforms as $key => $platform)
                                    <button type="button" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center transition duration-300 ease-in-out mr-2 mb-2" @click="platform({{ $platform->id }})">
                                        <label for="method{{ $platform->id }}">
                                            <img src="{{asset($platform->image)}}" alt="" class="inline mr-2" srcset="" style="height:2rem;">
                                            {{$platform->platform}}
                                        </label>
                                        <input type="radio" hidden id="method{{ $platform->id }}" name="method" value="{{$platform->id}}">
                                    </button>
                                @endforeach
                            </div>
                            <div>
                                @foreach ($platforms as $key => $platform)
                                    <div class="hidden" id="platform{{ $platform->id }}" area-data="components">
                                        @includeIf("components.".strtolower($platform->platform))
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-5 text-center">
                                <button type="submit" id="payButton" class="text-blue-700 w-full bg-blue-100 hover:bg-blue-300 focus:ring-4 focus:outline-none focus:ring-blue-300 transition duration-300 ease-in-out font-bold rounded-lg px-5 py-2.5 text-center">
                                    <svg class="inline w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                                    Pagar
                                  </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (isset($errors) && $errors->any())
        @foreach ($errors as $message)
            <div class="mb-3 text-red-500 bg-red-200 w-90 p-3 rounded-lg z-10 fixed bottom-0 left-3">
                {{$message}}
            </div>
        @endforeach
    @endif
    @if (session()->has("success"))
        <div class="mb-3 text-green-500 bg-red-200 w-90 p-3 rounded-lg z-10 fixed bottom-0 left-3">
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
