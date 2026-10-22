<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Levering product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">

                <h2 class="text-xl font-bold text-red-600 mb-4">
                    Levering niet mogelijk
                </h2>

                <p class="text-red-600 font-semibold">
                    Het product {{ $product->Naam }}
                    wordt niet meer geproduceerd
                </p>

                <p class="mt-4 text-gray-600">
                    U wordt over 4 seconden teruggestuurd.
                </p>

                <a
                    href="{{ route('leveranciers.producten', $leverancierId) }}"
                    class="inline-block mt-5 text-blue-600 underline"
                >
                    Terug
                </a>

            </div>
        </div>
    </div>

    <script>
        setTimeout(function () {
            window.location.href = @json(
                route('leveranciers.producten', $leverancierId)
            );
        }, 4000);
    </script>
</x-app-layout>
