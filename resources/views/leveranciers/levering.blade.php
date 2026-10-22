
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Levering product
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow rounded-lg">

                <h2 class="text-2xl font-bold mb-6">
                    Levering product
                </h2>

                <!-- Product en leverancier informatie -->
                <div class="mb-6 space-y-2">
                    <p>
                        <strong>Product:</strong>
                        {{ $product->Naam }}
                    </p>

                    <p>
                        <strong>Leverancier:</strong>
                        {{ $leverancier->Naam }}
                    </p>

                    <p>
                        <strong>Contactpersoon:</strong>
                        {{ $leverancier->ContactPersoon }}
                    </p>

                    <p>
                        <strong>Mobiel:</strong>
                        {{ $leverancier->Mobiel }}
                    </p>
                </div>

                <!-- Foutmeldingen -->
                @if ($errors->any())
                    <div class="mb-5 bg-red-100 text-red-700 p-4 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Nieuwe levering formulier -->
                <form
                    method="POST"
                    action="{{ route('leveranciers.levering.store', [
                        'leverancierId' => $leverancier->Id,
                        'productId' => $product->Id
                    ]) }}"
                >
                    @csrf

                    <!-- Aantal producteenheden -->
                    <div class="mb-4">
                        <label for="aantal"
                               class="block font-semibold mb-2">
                            Aantal producteenheden
                        </label>

                        <input
                            type="number"
                            id="aantal"
                            name="aantal"
                            min="1"
                            step="1"
                            value="{{ old('aantal') }}"
                            required
                            class="w-full rounded border-gray-300"
                        >

                        @error('aantal')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Datum eerstvolgende levering -->
                    <div class="mb-6">
                        <label for="datum"
                               class="block font-semibold mb-2">
                            Datum eerstvolgende levering
                        </label>

                        <input
                            type="date"
                            id="datum"
                            name="datum"
                            value="{{ old('datum') }}"
                            required
                            class="w-full rounded border-gray-300"
                        >

                        @error('datum')
                            <p class="text-red-600 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Knoppen -->
                    <div class="flex gap-3">

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"
                        >
                            Sla op
                        </button>

                        <a
                            href="{{ route('leveranciers.producten', $leverancier->Id) }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded"
                        >
                            Terug
                        </a>

                        <a
                            href="{{ route('dashboard') }}"
                            class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded"
                        >
                            Home
                        </a>

                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
