
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Geleverde producten
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm rounded-lg">

                <h2 class="text-2xl font-bold mb-6">
                    Geleverde producten
                </h2>

                <!-- Leverancier informatie -->
                <div class="mb-6 space-y-2">
                    <p><strong>Naam leverancier:</strong>
                        {{ $leverancier->Naam }}
                    </p>

                    <p><strong>Contactpersoon:</strong>
                        {{ $leverancier->ContactPersoon }}
                    </p>

                    <p><strong>Leveranciernummer:</strong>
                        {{ $leverancier->LeverancierNummer }}
                    </p>

                    <p><strong>Mobiel:</strong>
                        {{ $leverancier->Mobiel }}
                    </p>
                </div>

                <!-- Producten overzicht -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-3 text-left">Naam product</th>
                                <th class="border p-3 text-left">Aantal in magazijn</th>
                                <th class="border p-3 text-left">Verpakkingseenheid</th>
                                <th class="border p-3 text-left">Laatste levering</th>
                                <th class="border p-3 text-left">Nieuwe levering</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($producten as $product)
                                <tr>
                                    <td class="border p-3">
                                        {{ $product->Naam }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $product->AantalAanwezig ?? 'Geen voorraad' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $product->VerpakkingsEenheid ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        {{ $product->LaatsteLevering ?? '-' }}
                                    </td>

                                    <td class="border p-3">
                                        <!-- Knop voor User Story 2 -->
                                        <span class="text-blue-600 text-xl font-bold">
                                            +
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="border p-6 text-center text-red-600 font-semibold">

                                        Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin

                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Navigatie -->
                <div class="flex justify-end gap-3 mt-6">

                    <a href="{{ route('leveranciers.index') }}"
                       class="bg-gray-500 text-white px-5 py-2 rounded">
                        Terug
                    </a>

                    <a href="{{ route('dashboard') }}"
                       class="bg-blue-600 text-white px-5 py-2 rounded">
                        Home
                    </a>

                </div>

            </div>
        </div>
    </div>

    @if($producten->isEmpty())
        <script>
            setTimeout(function () {
                window.location.href = @json(route('leveranciers.index'));
            }, 3000);
        </script>
    @endif

</x-app-layout>
