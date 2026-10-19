<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LeverancierController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Story 1 - Overzicht Leveranciers
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        // Alle leveranciers met aantal verschillende producten
        $leveranciers = DB::table('leverancier as l')
            ->leftJoin(
                'productperleverancier as ppl',
                'l.Id',
                '=',
                'ppl.LeverancierId'
            )
            ->select(
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel'
            )
            ->selectRaw(
                'COUNT(DISTINCT ppl.ProductId) as AantalProducten'
            )
            ->groupBy(
                'l.Id',
                'l.Naam',
                'l.ContactPersoon',
                'l.LeverancierNummer',
                'l.Mobiel'
            )
            ->orderByDesc('AantalProducten')
            ->get();

        return view('leveranciers.index', compact('leveranciers'));
    }

    /*
    |--------------------------------------------------------------------------
    | User Story 1 - Geleverde producten
    |--------------------------------------------------------------------------
    */

    public function producten($id)
    {
        // Leverancier ophalen
        $leverancier = DB::table('leverancier')
            ->where('Id', $id)
            ->first();

        if (!$leverancier) {
            abort(404);
        }

        // Producten ophalen die deze leverancier levert
        $producten = DB::table('productperleverancier as ppl')
            ->join(
                'product as p',
                'ppl.ProductId',
                '=',
                'p.Id'
            )
            ->leftJoin(
                'magazijn as m',
                'p.Id',
                '=',
                'm.ProductId'
            )
            ->where('ppl.LeverancierId', $id)
            ->select(
                'p.Id',
                'p.Naam',
                'm.VerpakkingsEenheid',
                'm.AantalAanwezig'
            )
            ->selectRaw(
                'MAX(ppl.DatumLevering) as LaatsteLevering'
            )
            ->groupBy(
                'p.Id',
                'p.Naam',
                'm.VerpakkingsEenheid',
                'm.AantalAanwezig'
            )
            ->orderByDesc('m.AantalAanwezig')
            ->get();

        return view('leveranciers.producten', compact(
            'leverancier',
            'producten'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | User Story 2 - Levering product
    |--------------------------------------------------------------------------
    */

    public function createLevering($leverancierId, $productId)
    {
        // Leverancier ophalen
        $leverancier = DB::table('leverancier')
            ->where('Id', $leverancierId)
            ->first();

        if (!$leverancier) {
            abort(404);
        }

        // Product ophalen bij de gekozen leverancier
        $product = DB::table('productperleverancier as ppl')
            ->join(
                'product as p',
                'p.Id',
                '=',
                'ppl.ProductId'
            )
            ->where('ppl.LeverancierId', $leverancierId)
            ->where('ppl.ProductId', $productId)
            ->select(
                'p.Id',
                'p.Naam',
                'p.IsActief'
            )
            ->first();

        if (!$product) {
            abort(404);
        }

        // Formulier voor nieuwe levering tonen
        return view('leveranciers.levering', compact(
            'leverancier',
            'product'
        ));
    }
}
