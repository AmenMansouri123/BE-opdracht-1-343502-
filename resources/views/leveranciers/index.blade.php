
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Overzicht Leveranciers
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-xl p-6">

                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Overzicht Leveranciers
                </h2>

                <div class="overflow-x-auto">

                    <table class="min-w-full border-collapse text-sm">

                        <thead>
                            <tr class="bg-gray-100 text-gray-700">

                                <th class="border border-gray-200 px-5 py-4 text-left">
                                    Naam
                                </th>

                                <th class="border border-gray-200 px-5 py-4 text-left">
                                    Contactpersoon
                                </th>

                                <th class="border border-gray-200 px-5 py-4 text-left">
                                    Leveranciernummer
                                </th>

                                <th class="border border-gray-200 px-5 py-4 text-left">
                                    Mobiel
                                </th>

                                <th class="border border-gray-200 px-5 py-4 text-center">
                                    Aantal verschillende producten
                                </th>

                                <th class="border border-gray-200 px-5 py-4 text-center">
                                    Toon producten
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse($leveranciers as $leverancier)

                                <tr class="hover:bg-blue-50 transition-colors">

                                    <td class="border border-gray-200 px-5 py-4 font-semibold">
                                        {{ $leverancier->Naam }}
                                    </td>

                                    <td class="border border-gray-200 px-5 py-4">
                                        {{ $leverancier->ContactPersoon }}
                                    </td>

                                    <td class="border border-gray-200 px-5 py-4">
                                        {{ $leverancier->LeverancierNummer }}
                                    </td>

                                    <td class="border border-gray-200 px-5 py-4 whitespace-nowrap">
                                        {{ $leverancier->Mobiel }}
                                    </td>

                                    <td class="border border-gray-200 px-5 py-4 text-center">
                                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold">
                                            {{ $leverancier->AantalProducten }}
                                        </span>
                                    </td>

                                    <td class="border border-gray-200 px-5 py-4 text-center">

                                        <a
                                            href="{{ route('leveranciers.producten', $leverancier->Id) }}"
                                            title="Toon producten van {{ $leverancier->Naam }}"
                                            aria-label="Toon producten van {{ $leverancier->Naam }}"
                                            class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg transition-colors"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="22"
                                                 height="22"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round">

                                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                                <path d="m3.3 7 8.7 5 8.7-5"/>
                                                <path d="M12 22V12"/>

                                            </svg>
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6"
                                        class="text-center py-8 text-gray-500 border border-gray-200">
                                        Geen leveranciers gevonden.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('dashboard') }}"
                       class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-2 rounded-lg transition-colors">
                        Home
                    </a>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
