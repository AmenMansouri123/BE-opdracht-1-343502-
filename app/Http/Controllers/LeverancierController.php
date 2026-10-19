<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LeverancierController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Overzicht Leveranciers
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        // Alle leveranciers ophalen met aantal verschillende producten
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
            ->selectRaw('COUNT(DISTINCT ppl.ProductId) as AantalProducten')
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
    | Geleverde producten van een leverancier
    |--------------------------------------------------------------------------
    */

    public function producten($id)
    {
        // Leverancier ophalen
        $leverancier = DB::table('leverancier')
            ->where('Id', $id)
            ->first();

        // Controleer of leverancier bestaat
        if (!$leverancier) {
            abort(404);
        }

        // Producten van deze leverancier ophalen
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
            ->selectRaw('MAX(ppl.DatumLevering) as LaatsteLevering')
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
}
