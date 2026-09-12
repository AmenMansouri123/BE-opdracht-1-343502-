<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Levering Informatie</title>
</head>

<body>

    <h1>Levering Informatie</h1>

    @if (count($leveringen) > 0)

        <h2>{{ $leveringen[0]->ProductNaam }}</h2>

        <p>
            <strong>Leverancier:</strong>
            {{ $leveringen[0]->LeverancierNaam }}
        </p>

        <p>
            <strong>Contactpersoon:</strong>
            {{ $leveringen[0]->ContactPersoon }}
        </p>

        <p>
            <strong>Leveranciernummer:</strong>
            {{ $leveringen[0]->LeverancierNummer }}
        </p>

        <p>
            <strong>Mobiel:</strong>
            {{ $leveringen[0]->Mobiel }}
        </p>

        <table border="1">
            <thead>
                <tr>
                    <th>Datum Levering</th>
                    <th>Aantal</th>
                    <th>Datum Eerstvolgende Levering</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($leveringen as $levering)
                    <tr>
                        <td>{{ $levering->DatumLevering }}</td>
                        <td>{{ $levering->Aantal }}</td>
                        <td>
                            {{ $levering->DatumEerstVolgendeLevering ?? 'Geen datum bekend' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else

        <p>Geen leveringsinformatie beschikbaar.</p>

    @endif

    <br>

    <a href="{{ route('producten.index') }}">
        Terug naar overzicht
    </a>

</body>
</html>