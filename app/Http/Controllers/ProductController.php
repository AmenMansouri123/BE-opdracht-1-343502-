<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function dashboard()
    {
        $producten = DB::select("
            SELECT
                p.Id,
                p.Barcode,
                p.Naam,
                p.Verpakkingseenheid,
                p.Artikelomschrijving
            FROM Product p
            WHERE p.IsActief = 1
            ORDER BY p.Barcode ASC
        ");

        return view('dashboard', compact('producten'));
    }


    public function index()
    {
        // Alle actieve producten ophalen
        $producten = DB::select("
            SELECT
                p.Id,
                p.Barcode,
                p.Naam,
                p.Verpakkingseenheid,
                p.Artikelomschrijving
            FROM Product p
            WHERE p.IsActief = 1
            ORDER BY p.Barcode ASC
        ");

        // Standaard waarden
        $gekozenProduct = null;
        $allergenen = [];
        $leveringen = [];
        $magazijn = null;


        /*
        |--------------------------------------------------------------------------
        | Allergenen informatie
        |--------------------------------------------------------------------------
        */

        if (request()->has('allergenen')) {

            $id = request('allergenen');

            // Gekozen product ophalen
            $gekozenProduct = DB::selectOne("
                SELECT
                    Id,
                    Naam,
                    Barcode
                FROM Product
                WHERE Id = ?
            ", [$id]);

            // Allergenen ophalen
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
        }


        /*
        |--------------------------------------------------------------------------
        | Leverantie informatie
        |--------------------------------------------------------------------------
        */

        if (request()->has('levering')) {

            $id = request('levering');

            // Gekozen product ophalen
            $gekozenProduct = DB::selectOne("
                SELECT
                    Id,
                    Naam,
                    Barcode
                FROM Product
                WHERE Id = ?
            ", [$id]);


            // Voorraad controleren
            $magazijn = DB::selectOne("
                SELECT
                    m.AantalAanwezig,
                    ppl.DatumEerstVolgendeLevering
                FROM Magazijn m
                LEFT JOIN ProductPerLeverancier ppl
                    ON m.ProductId = ppl.ProductId
                WHERE m.ProductId = ?
                ORDER BY ppl.DatumLevering DESC
                LIMIT 1
            ", [$id]);


            // Leveringsinformatie ophalen
            $leveringen = DB::select("
                SELECT
                    ppl.DatumLevering,
                    ppl.Aantal,
                    ppl.DatumEerstVolgendeLevering,
                    l.Naam AS LeverancierNaam,
                    l.ContactPersoon,
                    l.LeverancierNummer,
                    l.Mobiel
                FROM ProductPerLeverancier ppl
                INNER JOIN Leverancier l
                    ON ppl.LeverancierId = l.Id
                WHERE ppl.ProductId = ?
                ORDER BY ppl.DatumLevering ASC
            ", [$id]);
        }


        return view('producten.index', compact(
            'producten',
            'gekozenProduct',
            'allergenen',
            'leveringen',
            'magazijn'
        ));
    }


    public function leveringInfo($id)
    {
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

        return view('producten.levering-info', compact('leveringen'));
    }


    public function allergenenInfo($id)
    {
        $product = DB::selectOne("
            SELECT
                Id,
                Naam,
                Barcode
            FROM Product
            WHERE Id = ?
        ", [$id]);

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

        return view(
            'producten.allergenen-info',
            compact('product', 'allergenen')
        );
    }
}