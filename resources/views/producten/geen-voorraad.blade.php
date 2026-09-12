<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="refresh" content="4;url={{ route('producten.index') }}">

    <title>Geen voorraad</title>
</head>

<body>

    <h1>Levering Informatie</h1>

    <p>
        Er is van dit product op dit moment geen voorraad aanwezig,
        de verwachte eerstvolgende levering is:
        {{ \Carbon\Carbon::parse($magazijn->DatumEerstVolgendeLevering)->format('d-m-Y') }}
    </p>

    <p>
        U wordt over 4 seconden teruggestuurd naar het overzicht.
    </p>

</body>
</html>