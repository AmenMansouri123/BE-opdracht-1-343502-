<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Allergenen</title>
</head>

<body>

    <h1>Overzicht Allergenen</h1>

    <p>
        <strong>Productnaam:</strong>
        {{ $product->Naam }}
    </p>

    <p>
        <strong>Barcode:</strong>
        {{ $product->Barcode }}
    </p>

    @if (count($allergenen) > 0)

        <table border="1">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Omschrijving</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($allergenen as $allergeen)
                    <tr>
                        <td>{{ $allergeen->Naam }}</td>
                        <td>{{ $allergeen->Omschrijving }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else

    <p>
        In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken
    </p>

    <meta http-equiv="refresh" content="4;url={{ route('producten.index') }}">

    <p>
        U wordt over 4 seconden teruggestuurd naar het overzicht.
    </p>

@endif

    <br>

    <a href="{{ route('producten.index') }}">
        Terug naar overzicht
    </a>

</body>

</html>