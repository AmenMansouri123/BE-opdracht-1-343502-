<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $producten = DB::select("
            SELECT
                p.Id,
                p.Naam,
                p.Barcode
            FROM Product p
            WHERE p.IsActief = 1
            ORDER BY p.Barcode ASC
        ");

        return view('producten.index', compact('producten'));
    }

    public function leveringInfo($id)
{
    // Controleer eerst hoeveel voorraad er van het product aanwezig is
    $magazijn = DB::selectOne("
        SELECT
            m.AantalAanwezig,
            ppl.DatumEerstVolgendeLevering
        FROM Magazijn m
        INNER JOIN ProductPerLeverancier ppl
            ON m.ProductId = ppl.ProductId
        WHERE m.ProductId = ?
        ORDER BY ppl.DatumLevering DESC
        LIMIT 1
    ", [$id]);

    // Als er geen voorraad aanwezig is, toon de pagina Geen Voorraad
    if ($magazijn && is_null($magazijn->AantalAanwezig)) {
        return view('producten.geen-voorraad', compact('magazijn'));
    }

    // Haal alle leveringsinformatie van het gekozen product op
    $leveringen = DB::select("
        SELECT
            ppl.DatumLevering,
            ppl.Aantal,
            ppl.DatumEerstVolgendeLevering,
            l.Naam AS LeverancierNaam,
            l.ContactPersoon,
            l.LeverancierNummer,
            l.Mobiel,
            p.Naam AS ProductNaam
        FROM ProductPerLeverancier ppl
        INNER JOIN Leverancier l
            ON ppl.LeverancierId = l.Id
        INNER JOIN Product p
            ON ppl.ProductId = p.Id
        WHERE ppl.ProductId = ?
        ORDER BY ppl.DatumLevering ASC
    ", [$id]);

    // Stuur de leveringsgegevens naar de view
    return view('producten.levering-info', compact('leveringen'));
    }



public function allergenenInfo($id)
{
    // Haal het gekozen product op
    $product = DB::selectOne("
        SELECT
            Id,
            Naam,
            Barcode
        FROM Product
        WHERE Id = ?
    ", [$id]);

    // Haal alle allergenen van het gekozen product op
    $allergenen = DB::select("
        SELECT
            a.Naam,
            a.Omschrijving
        FROM ProductPerAllergeen ppa
        INNER JOIN Allergeen a
            ON ppa.AllergeenId = a.Id
        WHERE ppa.ProductId = ?
        ORDER BY a.Naam ASC
    ", [$id]);

    // Stuur de gegevens naar de view
    return view('producten.allergenen-info', compact('product', 'allergenen'));
}
}