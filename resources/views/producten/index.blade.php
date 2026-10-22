<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Overzicht Magazijn
        </h2>
    </x-slot>


    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">


                    {{-- ================================================= --}}
                    {{-- ALLERGENEN INFO --}}
                    {{-- ================================================= --}}

                    @if(request()->has('allergenen') && $gekozenProduct)

                        <h1 class="text-2xl font-bold mb-6">
                            Overzicht Allergenen
                        </h1>

                        <div class="mb-6">

                            <p class="mb-2">
                                <strong>Productnaam:</strong>
                                {{ $gekozenProduct->Naam }}
                            </p>

                            <p>
                                <strong>Barcode:</strong>
                                {{ $gekozenProduct->Barcode }}
                            </p>

                        </div>


                        <div class="overflow-x-auto">

                            <table class="min-w-full border border-gray-300 border-collapse">

                                <thead class="bg-gray-100">

                                    <tr>

                                        <th class="border border-gray-300 px-4 py-3 text-left">
                                            Naam
                                        </th>

                                        <th class="border border-gray-300 px-4 py-3 text-left">
                                            Omschrijving
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($allergenen as $allergeen)

                                        <tr>

                                            <td class="border border-gray-300 px-4 py-3">
                                                {{ $allergeen->Naam }}
                                            </td>

                                            <td class="border border-gray-300 px-4 py-3">
                                                {{ $allergeen->Omschrijving }}
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="2"
                                                class="border border-gray-300 px-4 py-4 text-center"
                                            >
                                                <p class="text-red-600 font-semibold">
                                                    In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken
                                                </p>
                                            </td>

                                        </tr>

                                        <script>
                                            setTimeout(function () {
                                                window.location.href = "{{ route('producten.index') }}";
                                            }, 4000);
                                        </script>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>


                        <div class="mt-6">

                            <a
                                href="{{ route('producten.index') }}"
                                class="text-blue-600 hover:underline"
                            >
                                ← Terug naar overzicht
                            </a>

                        </div>



                    {{-- ================================================= --}}
                    {{-- LEVERANTIE INFO --}}
                    {{-- ================================================= --}}

                    @elseif(request()->has('levering') && $gekozenProduct)

                        <h1 class="text-2xl font-bold mb-6">
                            Leveringsinformatie
                        </h1>


                        {{-- Unhappy scenario --}}
                        @if($magazijn && is_null($magazijn->AantalAanwezig))

                            <div class="border border-red-300 bg-red-50 p-6 rounded-lg">

                                <p class="text-red-700 font-semibold">

                                    Er is van dit product op dit moment geen voorraad aanwezig,
                                    de verwachte eerstvolgende levering is:

                                    @if($magazijn->DatumEerstVolgendeLevering)

                                        {{ \Carbon\Carbon::parse($magazijn->DatumEerstVolgendeLevering)->format('d-m-Y') }}

                                    @else

                                        onbekend

                                    @endif

                                </p>

                            </div>


                            <script>
                                setTimeout(function () {
                                    window.location.href = "{{ route('producten.index') }}";
                                }, 4000);
                            </script>


                        {{-- Happy scenario --}}
                        @else

                            @php
                                $eersteLevering = $leveringen[0] ?? null;
                            @endphp


                            {{-- Leverancier informatie boven de tabel --}}
                            @if($eersteLevering)

                                <div class="mb-8">

                                    <p class="mb-2">
                                        <strong>Naam leverancier:</strong>
                                        {{ $eersteLevering->LeverancierNaam }}
                                    </p>

                                    <p class="mb-2">
                                        <strong>Contactpersoon leverancier:</strong>
                                        {{ $eersteLevering->ContactPersoon }}
                                    </p>

                                    <p class="mb-2">
                                        <strong>Leveranciernummer:</strong>
                                        {{ $eersteLevering->LeverancierNummer }}
                                    </p>

                                    <p>
                                        <strong>Mobiel:</strong>
                                        {{ $eersteLevering->Mobiel }}
                                    </p>

                                </div>

                            @endif


                            {{-- Leverantie tabel --}}
                            <div class="overflow-x-auto">

                                <table class="min-w-full border border-gray-300 border-collapse">

                                    <thead class="bg-gray-100">

                                        <tr>

                                            <th class="border border-gray-300 px-4 py-3 text-left">
                                                Naam Product
                                            </th>

                                            <th class="border border-gray-300 px-4 py-3 text-left">
                                                Datum laatste levering
                                            </th>

                                            <th class="border border-gray-300 px-4 py-3 text-left">
                                                Aantal
                                            </th>

                                            <th class="border border-gray-300 px-4 py-3 text-left">
                                                Eerstvolgende levering
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @forelse($leveringen as $levering)

                                            <tr>

                                                <td class="border border-gray-300 px-4 py-3">
                                                    {{ $gekozenProduct->Naam }}
                                                </td>

                                                <td class="border border-gray-300 px-4 py-3">
                                                    {{ \Carbon\Carbon::parse($levering->DatumLevering)->format('d-m-Y') }}
                                                </td>

                                                <td class="border border-gray-300 px-4 py-3">
                                                    {{ $levering->Aantal }}
                                                </td>

                                                <td class="border border-gray-300 px-4 py-3">

                                                    @if($levering->DatumEerstVolgendeLevering)

                                                        {{ \Carbon\Carbon::parse($levering->DatumEerstVolgendeLevering)->format('d-m-Y') }}

                                                    @else

                                                        Geen datum bekend

                                                    @endif

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td
                                                    colspan="4"
                                                    class="border border-gray-300 px-4 py-4 text-center"
                                                >
                                                    Geen leverantie informatie gevonden.
                                                </td>

                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        @endif


                        <div class="mt-6">

                            <a
                                href="{{ route('producten.index') }}"
                                class="text-blue-600 hover:underline"
                            >
                                ← Terug naar overzicht
                            </a>

                        </div>



                    {{-- ================================================= --}}
                    {{-- NORMALE MAGAZIJN TABEL --}}
                    {{-- ================================================= --}}

                    @else

                        <h1 class="text-2xl font-bold mb-6">
                            Overzicht Magazijn Jamin
                        </h1>


                        <div class="overflow-x-auto">

                            <table class="min-w-full border border-gray-300 border-collapse">

                                <thead class="bg-gray-100">

                                    <tr>

                                        <th class="border border-gray-300 px-4 py-3 text-left">
                                            Barcode
                                        </th>

                                        <th class="border border-gray-300 px-4 py-3 text-left">
                                            Naam
                                        </th>

                                        <th class="border border-gray-300 px-4 py-3 text-left">
                                            Verpakkingseenheid
                                        </th>

                                        <th class="border border-gray-300 px-4 py-3 text-left">
                                            Artikelomschrijving
                                        </th>

                                        <th class="border border-gray-300 px-4 py-3 text-center">
                                            Allergenen Info
                                        </th>

                                        <th class="border border-gray-300 px-4 py-3 text-center">
                                            Leverantie Info
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($producten as $product)

                                        <tr class="hover:bg-gray-50">

                                            <td class="border border-gray-300 px-4 py-3">
                                                {{ $product->Barcode }}
                                            </td>

                                            <td class="border border-gray-300 px-4 py-3">
                                                {{ $product->Naam }}
                                            </td>

                                            <td class="border border-gray-300 px-4 py-3">
                                                {{ $product->Verpakkingseenheid }}
                                            </td>

                                            <td class="border border-gray-300 px-4 py-3">
                                                {{ $product->Artikelomschrijving }}
                                            </td>


                                            {{-- Allergenen Info --}}
                                            <td class="border border-gray-300 px-4 py-3 text-center">

                                                <a
                                                    href="{{ route('producten.index', ['allergenen' => $product->Id]) }}"
                                                    class="text-red-600 font-bold text-xl"
                                                >
                                                    ✖
                                                </a>

                                            </td>


                                            {{-- Leverantie Info --}}
                                            <td class="border border-gray-300 px-4 py-3 text-center">

                                                <a
                                                    href="{{ route('producten.index', ['levering' => $product->Id]) }}"
                                                    class="text-blue-600 font-bold text-lg hover:underline"
                                                >
                                                    ?
                                                </a>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="6"
                                                class="border border-gray-300 px-4 py-4 text-center"
                                            >
                                                Geen producten gevonden.
                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    @endif


                </div>

            </div>

        </div>

    </div>

</x-app-layout>