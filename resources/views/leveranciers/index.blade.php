<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Overzicht Leveranciers
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">

                <h2 class="text-xl font-bold mb-4">
                    Overzicht Leveranciers
                </h2>

                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Contactpersoon</th>
                            <th>Leveranciernummer</th>
                            <th>Mobiel</th>
                            <th>Aantal verschillende producten</th>
                            <th>Toon producten</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($leveranciers as $leverancier)
                            <tr>
                                <td>{{ $leverancier->Naam }}</td>
                                <td>{{ $leverancier->ContactPersoon }}</td>
                                <td>{{ $leverancier->LeverancierNummer }}</td>
                                <td>{{ $leverancier->Mobiel }}</td>
                                <td>{{ $leverancier->AantalProducten }}</td>
                                <td>□</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>