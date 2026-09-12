<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Magazijn Jamin</title>
</head>

<body>

    <h1>Overzicht Magazijn Jamin</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Barcode</th>
                <th>Leverantie Info</th>
                <th>Allergenen Info</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($producten as $product)
                <tr>
                    <td>{{ $product->Naam }}</td>

                    <td>{{ $product->Barcode }}</td>

                    <td>
                        <a href="{{ route('producten.leveringInfo', $product->Id) }}">
                            ?
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('producten.allergenenInfo', $product->Id) }}"
                           style="color: red; text-decoration: none;">
                            ✖
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>