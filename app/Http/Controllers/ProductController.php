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
}
